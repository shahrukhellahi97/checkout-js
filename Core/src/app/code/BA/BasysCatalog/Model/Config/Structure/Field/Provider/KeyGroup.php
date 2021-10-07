<?php
namespace BA\BasysCatalog\Model\Config\Structure\Field\Provider;

use BA\BasysCatalog\Api\Data\CatalogInterface;
use BA\BasysCatalog\Model\Config\Structure\Field\FieldProviderInterface;
use BA\BasysCatalog\Model\ResourceModel\KeyGroup\CollectionFactory;

class KeyGroup implements FieldProviderInterface
{
    /**
     * @var \BA\BasysCatalog\Model\ResourceModel\KeyGroup\CollectionFactory
     */
    protected $collectionFactory;

    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
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
            'id' => 'key_group',
            'type' => 'select',
            'sortOrder' => 10,
            'showInDefault' => '1',
            'showInWebsite' => '1',
            'showInStore' => '1',
            'label' => 'Key Group',
            'options' => [
                'option' => $this->getOptionsForCatalog($catalog)
            ],
            'comment' => null,
            '_elementType' => 'field',
        ];
    }

    public function getOptionsForCatalog(CatalogInterface $catalogInterface)
    {
        $options[] = [
            'value' => null,
            'label' => ''
        ];

        /** @var \BA\BasysCatalog\Model\ResourceModel\KeyGroup\Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('division_id', $catalogInterface->getDivisionId());
        $collection->load();

        /** @var \BA\BasysCatalog\Model\KeyGroup $keyGroup */
        foreach ($collection as $keyGroup) {
            $options[] = [
                'label' => $keyGroup->getName() . ' - ID: ' . $keyGroup->getId(),
                'value' => $keyGroup->getId(),
            ];
        }

        return $options;
    }
}
