<?php
namespace BA\BasysCatalog\Model\Config\Source;

use BA\BasysCatalog\Api\Data\KeyGroupInterface;
use BA\BasysCatalog\Helper\Data;
use BA\BasysCatalog\Model\ResourceModel\KeyGroup\CollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

class KeyGroup implements OptionSourceInterface
{
    /**
     * @var \BA\BasysCatalog\Model\ResourceModel\Catalog\CollectionFactory
     */
    protected $keygroupCollectionFactory;

    /**
     * @var \BA\BasysCatalog\Helper\Data
     */
    protected $helper;

    public function __construct(CollectionFactory $keygroupCollectionFactory, Data $helper)
    {
        $this->keygroupCollectionFactory = $keygroupCollectionFactory;
        $this->helper = $helper;
    }

    public function toOptionArray()
    {
        // todo: refactor to model
        $divisionId = $this->helper->getDivisionId($this->helper->getCurrentStoreId());
        $collection = $this->keygroupCollectionFactory->create();
        $keyGroups = $collection->addFieldToFilter(KeyGroupInterface::DIVISION_ID, $divisionId)->load();

        $result = [];

        /** @var BA\BasysCatalog\Model\Catalog $catalogs */
        foreach ($keyGroups as $keyGroup) {
            $result[] = [
                'label' => $keyGroup->getName(),
                'value' => (int) $keyGroup->getId(),
            ];
        }

        return $result;
    }
}