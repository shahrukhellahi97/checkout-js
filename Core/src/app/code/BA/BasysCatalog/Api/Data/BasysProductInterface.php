<?php
namespace BA\BasysCatalog\Api\Data;

interface BasysProductInterface
{
    const SCHEMA = 'ba_basys_catalog_product';

    /**#@+
     * Constants defined for keys of  data array
     */
    const PRODUCT_ID = 'product_id';
    const CATALOG_ID = 'catalog_id';
    const BASYS_ID = 'basys_id';
    const DIVISION_ID = 'division_id';
    const SKU = 'sku';
    const TITLE = 'title';
    const DESCRIPTION = 'description';
    const WEIGHT = 'weight';
    const USNSPC = 'usnspc';
    const REPORT_SKU = 'report_sku';
    const REPORT_TITLE = 'report_title';
    const BASE_COLOUR = 'base_colour';
    const TRIM_COLOUR = 'trim_colour';
    const PRICES_ID = 'prices_id';
    const PRICE_BREAK_ID = 'price_break_id';
    const STOCK_ITEM = 'stock_item';
    const POINTS_RATE = 'points_rate';
    const POINTS_VALID = 'points_valid';
    const VERSION = 'version';
    /**#@-*/

    const ATTRIBUTES = [
        self::PRODUCT_ID,
        self::CATALOG_ID,
        self::BASYS_ID,
        self::DIVISION_ID,
        self::SKU,
        self::TITLE,
        self::DESCRIPTION,
        self::WEIGHT,
        self::USNSPC,
        self::REPORT_SKU,
        self::REPORT_TITLE,
        self::BASE_COLOUR,
        self::TRIM_COLOUR,
        self::PRICES_ID,
        self::PRICE_BREAK_ID,
        self::STOCK_ITEM,
        self::POINTS_RATE,
        self::POINTS_VALID,
        self::VERSION,
    ];

    /**
     * Get catalog id
     *
     * @return int
     */
    public function getProductId();

    /**
     * Set product id
     *
     * @param int $id
     * @return self
     */
    public function setProductId($id);

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
     * Get basys
     *
     * @return int
     */
    public function getBasysId();

    /**
     * Get basys id
     *
     * @param int $id
     * @return self
     */
    public function setBasysId($id);

    /**
     * Get division id
     *
     * @return int
     */
    public function getDivisionId();

    /**
     * Set division id
     *
     * @param int $id
     * @return self
     */
    public function setDivisionId($id);

    /**
     * Get SKU
     *
     * @return string
     */
    public function getSku();

    /**
     * Set SKU
     *
     * @param string $sku
     * @return self
     */
    public function setSku($sku);

    /**
     * Get product title
     *
     * @return string
     */
    public function getTitle();

    /**
     * Set product title
     *
     * @param string $title
     * @return self
     */
    public function setTitle($title);

    /**
     * Get product web notes
     *
     * @return self
     */
    public function getDescription();

    /**
     * Set product web notes
     *
     * @param string $description
     * @return self
     */
    public function setDescription($description);

    /**
     * Get dimensional weight
     *
     * @return float
     */
    public function getWeight();

    /**
     * Set dimensional weight
     *
     * @param float $weight
     * @return self
     */
    public function setWeight($weight);

    /**
     * Get USNSPC
     *
     * @return int
     */
    public function getUsnspc();

    /**
     * Set USNSPC
     *
     * @param int $code
     * @return self
     */
    public function setUsnspc($code);

    /**
     * Get report SKU
     *
     * @return self
     */
    public function getReportSku();

    /**
     * Set report SKU
     *
     * @param string $sku
     * @return self
     */
    public function setReportSku($sku);

    /**
     * Get report SKU
     *
     * @return string
     */
    public function getReportTitle();

    /**
     * Set report title
     *
     * @param string $title
     * @return self
     */
    public function setReportTitle($title);

    /**
     * Get base colour ID
     *
     * @return int
     */
    public function getBaseColour();

    /**
     * Set base colour ID
     *
     * @param int $colourId
     * @return self
     */
    public function setBaseColour($colourId);

    /**
     * Get trim colour ID
     *
     * @return int
     */
    public function getTrimColour();

    /**
     * Set trim colour ID
     *
     * @param int $colourId
     * @return self
     */
    public function setTrimColour($colourId);

    /**
     * Get prices ID
     *
     * @return int
     */
    public function getPricesId();

    /**
     * Set prices ID
     *
     * @param int $pricesId
     * @return self
     */
    public function setPricesId($pricesId);

    /**
     * Get prices ID
     *
     * @return int
     */
    public function getPriceBreakId();

    /**
     * Set prices ID
     *
     * @param int $pricesId
     * @return self
     */
    public function setPriceBreakId($priceBreakId);

    /**
     * Check if this is a stock item
     *
     * @return bool
     */
    public function getStockItem();

    /**
     * Set if this is a stock item
     *
     * @param bool $isStockItem
     * @return self
     */
    public function setStockItem($isStockItem);

    /**
     * Get points rate
     *
     * @return float
     */
    public function getPointsRate();

    /**
     * Set points rate
     *
     * @param float $rate
     * @return self
     */
    public function setPointsRate($rate);

    /**
     * @return bool
     */
    public function getPointsValid();

    /**
     * @param bool $pointsValid
     * @return self
     */
    public function setPointsValid($pointsValid);

    /**
     * Get change ID
     *
     * @return int
     */
    public function getVersion();

    /**
     * Set change ID
     *
     * @param int $version
     * @return self
     */
    public function setVersion($version);

    /**
     * Get current price
     *
     * @param int $quantity
     * @return float
     */
    public function getPrice($quantity = 1);

    /**
     * Get list of all prices
     *
     * @return \BA\BasysCatalog\Api\Data\BasysProductPriceInterface[]|array
     */
    public function getPrices();

    /**
     * Set list of a prices
     *
     * @param \BA\BasysCatalog\Api\Data\BasysProductPriceInterface[]|array $prices
     * @return self
     */
    public function setPrices($prices);
}
