<?php
namespace BA\BasysCatalog\Api\Data;

interface BasysProductPriceInterface
{
    const SCHEMA = 'ba_basys_catalog_product_price';

    /**#@+
     * Constants defined for keys of  data array
     */
    const CATALOG_ID = 'catalog_id';
    const BASYS_ID = 'basys_id';
    const TYPE_ID = 'type_id';
    const PRICE = 'price';
    const BREAK = 'break';
    /**#@-*/

    const ATTRIBUTES = [
        self::CATALOG_ID,
        self::BASYS_ID,
        self::TYPE_ID,
        self::PRICE,
        self::BREAK
    ];

    /**
     * Get catalog id
     *
     * @return int
     */
    public function getCatalogId();

    /**
     * Set catalog id
     *
     * @param int $id
     * @return self
     */
    public function setCatalogId($id);

    /**
     * Get BAsys product id
     *
     * @return int
     */
    public function getBasysId();

    /**
     * Set BAsys product id
     *
     * @param int $id
     * @return self
     */
    public function setBasysId($id);

    /**
     * Get product price
     * 
     * @return float 
     */
    public function getPrice();

    /**
     * Set Product Price
     * 
     * @param float $price 
     * @return self 
     */
    public function setPrice($price);

    /**
     * Set type id
     * 
     * @return int 
     */
    public function getType();

    /**
     * Set type id
     * 
     * @param int $type 
     * @return self 
     */
    public function setType($type);

    /**
     * Get price break
     * 
     * @return int
     */
    public function getBreak();

    /**
     * Set price break
     * 
     * @param int $break 
     * @return self 
     */
    public function setBreak($break);
}