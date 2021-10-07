<?php
namespace BA\BasysCatalog\Model\Config\Source;

use BA\BasysCatalog\Api\Data\CatalogInterface;
use BA\BasysCatalog\Helper\Data;
use BA\BasysCatalog\Model\ResourceModel\Catalog\CollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

class Catalog implements OptionSourceInterface
{
    /**
     * @var \BA\BasysCatalog\Model\ResourceModel\Catalog\CollectionFactory
     */
    protected $catalogCollectionFactory;

    /**
     * @var \BA\BasysCatalog\Helper\Data
     */
    protected $helper;

    public function __construct(CollectionFactory $catalogCollectionFactory, Data $helper)
    {
        $this->catalogCollectionFactory = $catalogCollectionFactory;
        $this->helper = $helper;
    }

    public function toOptionArray()
    {
        $storeId = $this->helper->getCurrentStoreId();
        // todo: refactor to model
        $divisionId = $this->helper->getDivisionId($storeId);
        $collection = $this->catalogCollectionFactory->create();
        $catalogs = $collection->addFieldToFilter(CatalogInterface::DIVISION_ID, $divisionId)->load();

        $result = [];

        /** @var \BA\BasysCatalog\Model\Catalog $catalog */
        foreach ($catalogs as $catalog) {
            $result[] = [
                'label' => sprintf("%s - %s ( ID: %s )",
                    $catalog->getCurrency(),
                    $catalog->getName(),
                    $catalog->getId()
                ),
                'value' => (int) $catalog->getId(),
            ];
        }

        return $result;
    }
}