<?php
namespace BA\BasysOrders\Model;

use BA\BasysOrders\Api\Data\PaymentTypeInterface;
use BA\BasysOrders\Model\ResourceModel\PaymentType as ResourceModelPaymentType;
use Magento\Framework\Model\AbstractModel;

class PaymentType extends AbstractModel implements PaymentTypeInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModelPaymentType::class);
    }

    public function getDivisionId()
    {
        return $this->getData(PaymentTypeInterface::DIVISION_ID);
    }

    public function setDivisionId($divisionId)
    {
        return $this->setData(PaymentTypeInterface::DIVISION_ID, $divisionId);
    }

    public function getPaymentTypeId()
    {
        return $this->getData(PaymentTypeInterface::PAYMENT_TYPE_ID);
    }

    public function setPaymentTypeId($paymentId)
    {
        return $this->setData(PaymentTypeInterface::PAYMENT_TYPE_ID, $paymentId);
    }

    public function getReference()
    {
        return $this->getData(PaymentTypeInterface::REFERENCE);
    }

    public function setReference($reference)
    {
        return $this->setData(PaymentTypeInterface::REFERENCE, $reference);
    }

    public function getMethod()
    {
        return $this->getData(PaymentTypeInterface::METHOD);
    }

    public function setMethod($method)
    {
        return $this->setData(PaymentTypeInterface::METHOD, $method);
    }

    public function getDefault()
    {
        return $this->getData(PaymentTypeInterface::DEFAULT);
    }

    public function setDefault($default)
    {
        return $this->setData(PaymentTypeInterface::DEFAULT, $default);
    }

    public function setUserDefinedFields($fields)
    {
        return $this->setData('udfs', $fields);
    }

    public function getUserDefinedFields()
    {
        if ($data = $this->getData('udfs')) {
            return $data;
        }

        return [];
    }
}