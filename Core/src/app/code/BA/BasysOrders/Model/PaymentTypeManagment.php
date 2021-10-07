<?php
namespace BA\BasysOrders\Model;

use BA\Basys\Webservices\Command\CommandPoolInterface;
use BA\BasysCatalog\Helper\Data as CatalogHelper;
use BA\BasysOrders\Api\Data\PaymentTypeInterface;
use BA\BasysOrders\Api\PaymentTypeManagmentInterface;
use BA\BasysOrders\Helper\Data;
use BA\BasysOrders\Model\ResourceModel\PaymentTypeFactory as PaymentTypeResourceFactory;
use BA\BasysOrders\Model\ResourceModel\UserDefinedFieldFactory;
use BA\BasysOrders\Model\ResourceModel\UserDefinedFieldOptionFactory;

class PaymentTypeManagment implements PaymentTypeManagmentInterface
{
    /**
     * @var \BA\Basys\Webservices\Command\CommandPoolInterface
     */
    protected $commandPool;

    /**
     * @var \BA\BasysOrders\Model\PaymentTypeFactory
     */
    protected $paymentTypeFactory;

    /**
     * @var \BA\BasysOrders\Model\ResourceModel\PaymentTypeFactory
     */
    protected $paymentTypeResourceFactory;

    /**
     * @var \BA\BasysOrders\Model\ResourceModel\PaymentType
     */
    protected $paymentTypeResource;

    /**
     * @var \BA\BasysOrders\Model\ResourceModel\UserDefinedFieldFactory
     */
    protected $userDefinedFieldFactory;

    /**
     * @var \BA\BasysOrders\Model\ResourceModel\UserDefinedField
     */
    protected $userDefinedFieldResource;

    /**
     * @var \BA\BasysOrders\Model\ResourceModel\UserDefinedFieldOptionFactory
     */
    protected $userDefinedFieldOptionFactory;

    /**
     * @var \BA\BasysOrders\Model\ResourceModel\UserDefinedFieldOption
     */
    protected $userDefinedFieldOptionResource;

    /**
     * @var \BA\BasysOrders\Helper\Data
     */
    protected $helper;

    /**
     * @var \BA\BasysCatalog\Helper\Data
     */
    protected $catalogHelper;

    /**
     * @var \BA\BasysOrders\Api\Data\PaymentTypeInterface[]|array
     */
    protected $visibleTypes;

    public function __construct(
        CommandPoolInterface $commandPool,
        PaymentTypeFactory $paymentTypeFactory,
        PaymentTypeResourceFactory $paymentTypeResourceFactory,
        UserDefinedFieldFactory $userDefinedFieldFactory,
        UserDefinedFieldOptionFactory $userDefinedFieldOptionFactory,
        Data $helper,
        CatalogHelper $catalogHelper
    ) {
        $this->commandPool = $commandPool;
        $this->paymentTypeFactory = $paymentTypeFactory;
        $this->paymentTypeResourceFactory = $paymentTypeResourceFactory;
        $this->userDefinedFieldFactory = $userDefinedFieldFactory;
        $this->userDefinedFieldOptionFactory = $userDefinedFieldOptionFactory;
        $this->helper = $helper;
        $this->catalogHelper = $catalogHelper;
    }

    public function getAllPaymentTypes($divisionId = null, $fromWebservices = false)
    {
        if ($fromWebservices) {
            return $this->getPaymentFromWebservices($divisionId);
        }

        return [];
    }

    private function getPaymentFromWebservices($divisionId)
    {
        $command = $this->commandPool->get('payment_get_types');

        $result = $command->execute(
            ['division_id' => $divisionId],
            ['division_id' => $divisionId]
        );

        return $result;
    }

    /**
     * Get all visible payment types
     *
     * @param string|array|null $paymentMethod
     * @return \BA\BasysOrders\Api\Data\PaymentTypeInterface[]|array|null
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getVisiblePaymentTypes($paymentMethod = null)
    {
        if (!$this->visibleTypes) {
            $result = [];

            $paymentTypes = $this->helper->getActivePaymentTypeIds(
                $this->catalogHelper->getCurrentStoreId()
            );

            foreach ($paymentTypes as $paymentTypeId) {
                $model = $this->paymentTypeFactory->create();

                $divisionId = $this->catalogHelper->getDivisionId(
                    $this->catalogHelper->getCurrentStoreId()
                );

                $model = $this->getPaymentTypeResource()->getPaymentType(
                    $divisionId,
                    $paymentTypeId,
                    true,
                    $paymentMethod
                );

                if ($model) {
                    $result[] = $model;
                }
            }

            $this->visibleTypes = $result;
        }

        return $this->visibleTypes;
    }

    public function save($type)
    {
        if (is_array($type)) {
            /** @var \BA\BasysOrders\Api\Data\PaymentTypeInterface $object */
            foreach ($type as $object) {
                $this->saveObject($object);
            }
        } else {
            $this->saveObject($type);
        }

        return $type;
    }

    private function saveObject(PaymentTypeInterface $object)
    {
        /** @var \BA\BasysOrders\Model\PaymentType $object */
        $this->getPaymentTypeResource()->save($object);
    }

    /**
     * @return \BA\BasysOrders\Model\ResourceModel\PaymentType
     */
    private function getPaymentTypeResource()
    {
        if (!$this->paymentTypeResource) {
            $this->paymentTypeResource = $this->paymentTypeResourceFactory->create();
        }

        return $this->paymentTypeResource;
    }

    /**
     * @return \BA\BasysOrders\Model\ResourceModel\UserDefinedField
     */
    private function getUserDefinedFieldResource()
    {
        if (!$this->userDefinedFieldResource) {
            $this->userDefinedFieldResource = $this->userDefinedFieldFactory->create();
        }

        return $this->userDefinedFieldResource;
    }

    /**
     * @return \BA\BasysOrders\Model\ResourceModel\UserDefinedFieldOption
     */
    private function getUserDefinedFieldOptionResource()
    {
        if (!$this->userDefinedFieldOptionResource) {
            $this->userDefinedFieldOptionResource = $this->userDefinedFieldOptionFactory->create();
        }

        return $this->userDefinedFieldOptionResource;
    }
}
