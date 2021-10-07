<?php
namespace BA\BasysOrders\Controller\Adminhtml\System\Config;

use BA\BasysOrders\Api\PaymentTypeManagmentInterface;
use BA\BasysCatalog\Helper\Data as CatalogHelper;
use BA\BasysOrders\Model\PaymentTypeFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class Refresh extends Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \BA\BasysOrders\Api\PaymentTypeManagmentInterface
     */
    protected $paymentTypeManagment;

    /**
     * @var \BA\BasysCatalog\Helper\Data
     */
    protected $catalogHelper;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        PaymentTypeManagmentInterface $paymentTypeManagment,
        CatalogHelper $catalogHelper
    ) {
        parent::__construct($context);

        $this->catalogHelper = $catalogHelper;
        $this->paymentTypeManagment = $paymentTypeManagment;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();

        $divisionId = $this->catalogHelper->getDivisionId(
            $this->catalogHelper->getCurrentStoreId()
        );

        if ($divisionId) {
            $types = $this->paymentTypeManagment->getAllPaymentTypes($divisionId, true);
            $this->paymentTypeManagment->save($types);

            $resultArr = [];

            /** @var \BA\BasysOrders\Model\PaymentType $type */
            foreach ($types as $type) {
                $resultArr[] = $type->toArray();
            }

            return $result->setData($resultArr);
        }

        return $result->setData(['err' => true]);
    }
}