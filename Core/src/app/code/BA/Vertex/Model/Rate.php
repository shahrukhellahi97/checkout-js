<?php
namespace BA\Vertex\Model;

use BA\Vertex\Api\Data\RateInterface;
use Magento\Framework\Model\AbstractModel;

class Rate extends AbstractModel implements RateInterface
{
    public function getDestinationCountryId()
    {
        return $this->getData(RateInterface::DESTINATION_COUNTRY_ID);
    }

    public function setDestinationCountryId($countryId)
    {
        return $this->setData(RateInterface::DESTINATION_COUNTRY_ID, $countryId);
    }

    public function getSourceCountryId()
    {
        return $this->getData(RateInterface::SOURCE_COUNTRY_ID);
    }

    public function setSourceCountryId($countryId)
    {
        return $this->setData(RateInterface::SOURCE_COUNTRY_ID, $countryId);
    }

    public function getProductSourceCountryId()
    {
        return $this->getData(RateInterface::PRODUCT_SOURCE_COUNTRY_ID);
    }

    public function setProductSourceCountryId($countryId)
    {
        return $this->setData(RateInterface::PRODUCT_SOURCE_COUNTRY_ID, $countryId);
    }

    public function getBasysId()
    {
        return $this->getData(RateInterface::BASYS_ID);
    }

    public function setBasysId($basysId)
    {
        return $this->setData(RateInterface::BASYS_ID, $basysId);
    }

    public function setSku($sku)
    {
        return $this->setData(RateInterface::SKU, $sku);
    }

    public function getSku()
    {
        return $this->getData(RateInterface::SKU);
    }

    public function getRate()
    {
        return $this->getData(RateInterface::RATE);
    }

    public function setRate($rate)
    {
        return $this->setData(RateInterface::RATE, $rate);
    }

    public function getModified()
    {
        return $this->getData(RateInterface::MODIFIED);
    }

    public function setModified($timestamp)
    {
        return $this->setData(RateInterface::MODIFIED, $timestamp);
    }
}
