<?php
namespace BA\BasysCatalog\Model\Config\Structure\Field\Provider;

use BA\BasysCatalog\Api\Data\CatalogInterface;
use BA\BasysCatalog\Model\Config\Structure\Field\FieldProviderInterface;
use BA\BasysCatalog\Model\ResourceModel\SourceCode\CollectionFactory;

class SourceCode implements FieldProviderInterface
{
    /**
     * @var \BA\BasysCatalog\Model\ResourceModel\SourceCode\CollectionFactory
     */
    protected $sourceCollectionFactory;

    public function __construct(CollectionFactory $sourceCollectionFactory)
    {
        $this->sourceCollectionFactory = $sourceCollectionFactory;
    }

    /**
     * Process
     *
     * @param \BA\BasysCatalog\Api\Data\CatalogInterface $catalog
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Exception
     */
    public function process($catalog)
    {
        return [
            'id' => 'source_code',
            'type' => 'select',
            'sortOrder' => 20,
            'showInDefault' => '1',
            'showInWebsite' => '1',
            'showInStore' => '1',
            'label' => 'Source Code',
            'options' => [
                'option' => $this->getOptionsForCatalog($catalog)
            ],
            'comment' => null,
            '_elementType' => 'field',
        ];
    }

    public function getOptionsForCatalog(CatalogInterface $catalogInterface)
    {
        /** @var \BA\BasysCatalog\Model\ResourceModel\SourceCode\Collection $collection */
        $collection = $this->sourceCollectionFactory->create();
        $codes = $collection->addFieldToFilter('catalog_id', $catalogInterface->getId())->load();

        $options[] = [
            'value' => null,
            'label' => '---'
        ];

        /** @var \BA\BasysCatalog\Api\Data\SourceCodeInterface $code */
        foreach ($codes as $code) {
            $options[] = [
                'value' => $code->getId(),
                'label' => $code->getName() . ' - ID: ' . $code->getId()
            ];
        }

        return $options;
    }
}
