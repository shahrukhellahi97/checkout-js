<?php
namespace BA\BasysCatalog\Model\Config\Source;

use BA\BasysCatalog\Api\Data\SourceCodeInterface;
use BA\BasysCatalog\Helper\Data;
use BA\BasysCatalog\Model\ResourceModel\SourceCode\CollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

class SourceCode implements OptionSourceInterface
{
    /**
     * @var \BA\BasysCatalog\Model\ResourceModel\Catalog\CollectionFactory
     */
    protected $sourceCodeCollectionFactory;

    /**
     * @var \BA\BasysCatalog\Helper\Data
     */
    protected $helper;

    public function __construct(CollectionFactory $sourceCodeCollectionFactory, Data $helper)
    {
        $this->sourceCodeCollectionFactory = $sourceCodeCollectionFactory;
        $this->helper = $helper;
    }

    public function toOptionArray()
    {
        // todo: refactor to model
        $activeCatalogIds = $this->helper->getActiveCatalogIds($this->helper->getCurrentStoreId());
        $collection = $this->sourceCodeCollectionFactory->create();
        $sourceCodes = $collection->addFieldToFilter(SourceCodeInterface::CATALOG_ID, $activeCatalogIds)->load();

        $result = [];

        /** @var BA\BasysCatalog\Model\Catalog $catalogs */
        foreach ($sourceCodes as $sourceCode) {
            $result[] = [
                'label' => $sourceCode->getName(),
                'value' => (int) $sourceCode->getId(),
            ];
        }

        return $result;
    }
}