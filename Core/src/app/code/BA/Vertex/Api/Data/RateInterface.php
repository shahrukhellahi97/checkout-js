<?php
namespace BA\Vertex\Api\Data;

interface RateInterface
{
    const SCHEMA_NAME = 'ba_vertex_tax_rates';

    const DESTINATION_COUNTRY_ID = 'destination_country_id';
    const SOURCE_COUNTRY_ID = 'source_country_id';
    const PRODUCT_SOURCE_COUNTRY_ID = 'product_source_country_id';
    const BASYS_ID = 'basys_id';
    const SKU = 'sku';
    const RATE = 'rate';
    const MODIFIED = 'modified';

    /**
     * Get Country ID
     *
     * @return string
     */
    public function getDestinationCountryId();

    /**
     * Set Country ID
     *
     * @param string $countryId
     * @return self
     */
    public function setDestinationCountryId($countryId);

    /**
     * Get Country ID
     *
     * @return string
     */
    public function getSourceCountryId();

    /**
     * Set Country ID
     *
     * @param string $countryId
     * @return self
     */
    public function setSourceCountryId($countryId);

    /**
     * Get Country ID
     *
     * @return string
     */
    public function getProductSourceCountryId();

    /**
     * Set Country ID
     *
     * @param string $countryId
     * @return self
     */
    public function setProductSourceCountryId($countryId);

    /**
     * Set basys product id
     *
     * @return int
     */
    public function getBasysId();

    /**
     * Set product SKU
     *
     * @param string
     * @return self
     */
    public function setSku($sku);

    /**
     * Get product SKU
     *
     * @return string
     */
    public function getSku();

    /**
     * Set basys product id
     *
     * @param int $basysId
     * @return self
     */
    public function setBasysId($basysId);

    /**
     * Get tax rate
     *
     * @return float
     */
    public function getRate();

    /**
     * Set tax rate
     *
     * @param float $rate
     * @return self
     */
    public function setRate($rate);

    /**
     * Get modified date
     *
     * @return int
     */
    public function getModified();

    /**
     * Set modified date
     *
     * @param int $timestamp
     * @return self
     */
    public function setModified($timestamp);
}
