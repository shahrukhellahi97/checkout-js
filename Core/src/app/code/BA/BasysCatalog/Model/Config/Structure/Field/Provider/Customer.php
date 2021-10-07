<?php
namespace BA\BasysCatalog\Model\Config\Structure\Field\Provider;

use BA\BasysCatalog\Api\Data\CatalogInterface;
use BA\BasysCatalog\Helper\Data;
use BA\BasysCatalog\Model\Config\Structure\Field\FieldProviderInterface;
use BA\BasysCatalog\Model\ResourceModel\Customer\CollectionFactory;

class Customer implements FieldProviderInterface
{
    /**
     * @var \BA\BasysCatalog\Model\ResourceModel\Customer\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \BA\BasysCatalog\Helper\Data
     */
    protected $helper;

    public function __construct(
        CollectionFactory $collectionFactory,
        Data $helper
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->helper = $helper;
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
            'id' => 'customer',
            'type' => 'select',
            'sortOrder' => 50,
            'showInDefault' => '1',
            'showInWebsite' => '1',
            'showInStore' => '1',
            'label' => 'Default Customer',
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

        $storeId = $this->helper->getCurrentStoreId();

        /** @var \BA\BasysCatalog\Model\ResourceModel\Customer\Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('division_id', $catalogInterface->getDivisionId());

        if ($sourceCode = $this->helper->getSourceCodeId($catalogInterface, $storeId)) {
            $collection->addFieldToFilter('source_code_id', $sourceCode);
        }

        $collection->load();

        /** @var \BA\BasysCatalog\Model\Customer $customer */
        foreach ($collection as $customer) {
            $options[] = [
                'label' => $customer->getName() . ' - ID: ' . $customer->getId(),
                'value' => $customer->getCustomerId(),
            ];
        }

        return $options;
    }
}
