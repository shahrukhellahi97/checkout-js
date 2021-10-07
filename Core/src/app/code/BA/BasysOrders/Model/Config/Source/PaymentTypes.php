<?php
namespace BA\BasysOrders\Model\Config\Source;

use BA\BasysOrders\Api\Data\PaymentTypeMethodMetadataInterface;
use Magento\Framework\Data\OptionSourceInterface;

class PaymentTypes implements OptionSourceInterface
{
    /**
     * @var \BA\BasysOrders\Model\ResourceModel\PaymentTypeFactory
     */
    protected $paymentTypeResourceFactory;

    /**
     * @var \BA\BasysOrders\Model\PaymentTypeFactory
     */
    protected $paymentTypeFactory;

    /**
     * @var \BA\BasysCatalog\Helper\Data
     */
    protected $catalogHelper;

    public function __construct(
        \BA\BasysOrders\Model\ResourceModel\PaymentTypeFactory $paymentTypeResourceFactory,
        \BA\BasysOrders\Model\PaymentTypeFactory $paymentTypeFactory,
        \BA\BasysCatalog\Helper\Data $catalogHelper
    ) {
        $this->paymentTypeResourceFactory = $paymentTypeResourceFactory;
        $this->paymentTypeFactory = $paymentTypeFactory;
        $this->catalogHelper = $catalogHelper;
    }

    public function toOptionArray()
    {
        /** @var \BA\BasysOrders\Model\ResourceModel\PaymentType $resource */
        $resource = $this->paymentTypeResourceFactory->create();
        $connection = $resource->getConnection();
        $divisionId = $this->catalogHelper->getDivisionId(
            $this->catalogHelper->getCurrentStoreId()
        );

        if ($divisionId) {
            $select = $connection->select()
                ->from($resource->getMainTable())
                ->where('division_id = ?', $divisionId);

            $result = [];

            foreach ($connection->fetchAll($select) as $row) {
                $result[] = [
                    'label' => sprintf(
                        '%s ( ID: %s - %s )',
                        trim($row['reference']),
                        $row['payment_type_id'],
                        $this->getMethodTitle($row['method'])
                    ),
                    'value' => $row['payment_type_id'],
                ];
            }

            return $result;
        }

        return [];
    }

    private function getMethodTitle($title) 
    {
        switch ($title) {
            case PaymentTypeMethodMetadataInterface::METHOD_CREDIT:
                return 'Credit';
            case PaymentTypeMethodMetadataInterface::METHOD_INVOICE:
                return 'Invoice';
            case PaymentTypeMethodMetadataInterface::METHOD_CONSOLIDATED_INVOICE:
                return 'Consolidated Invoice';
        }

        
    }
}