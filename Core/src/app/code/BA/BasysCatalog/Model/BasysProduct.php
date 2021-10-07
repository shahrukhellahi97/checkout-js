<?php
namespace BA\BasysCatalog\Model;

use BA\BasysCatalog\Api\Data\BasysProductInterface;
use BA\BasysCatalog\Api\Data\ChecksumInterface;
use BA\BasysCatalog\Model\ResourceModel\BasysProduct as ResourceModelBasysProduct;
use BA\BasysCatalog\Model\ResourceModel\BasysProductPrice as ResourceModelBasysProductPrice;
use Magento\Framework\Model\AbstractModel;

class BasysProduct extends AbstractModel implements BasysProductInterface, ChecksumInterface
{
    /**
     * @var \BA\BasysCatalog\Model\ResourceModel\BasysProductPrice
     */
    protected $basysPriceResource;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        ResourceModelBasysProductPrice $basysPriceResource,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);

        $this->basysPriceResource = $basysPriceResource;
    }

    public function _construct()
    {
        $this->_init(ResourceModelBasysProduct::class);
    }

    public function getPrice($quantity = 1)
    {
        return $this->basysPriceResource
            ->getBasysPriceForQuantity($this, $quantity)
            ->getPrice();
    }

    public function getPrices()
    {
        if (!$this->hasData('prices')) {
            $this->setPrices($this->basysPriceResource->getBasysPrices($this));
        }

        return $this->getData('prices');
    }

    public function setPrices($prices)
    {
        return $this->setData('prices', $prices);
    }

    public function getProductId()
    {
        return $this->getData(BasysProductInterface::PRODUCT_ID);
    }

    public function setProductId($id)
    {
        return $this->setData(BasysProductInterface::PRODUCT_ID, $id);
    }

    public function getCatalogId()
    {
        return $this->getData(BasysProductInterface::CATALOG_ID);
    }

    public function setCatalogId($id)
    {
        return $this->setData(BasysProductInterface::CATALOG_ID, $id);
    }

    public function getBasysId()
    {
        return $this->getData(BasysProductInterface::BASYS_ID);
    }

    public function setBasysId($id)
    {
        return $this->setData(BasysProductInterface::BASYS_ID, $id);
    }

    public function getDivisionId()
    {
        return $this->getData(BasysProductInterface::DIVISION_ID);
    }

    public function setDivisionId($id)
    {
        return $this->setData(BasysProductInterface::DIVISION_ID, $id);
    }

    public function getSku()
    {
        return $this->getData(BasysProductInterface::SKU);
    }

    public function setSku($sku)
    {
        return $this->setData(BasysProductInterface::SKU, $sku);
    }

    public function getTitle()
    {
        return $this->getData(BasysProductInterface::TITLE);
    }

    public function setTitle($title)
    {
        return $this->setData(BasysProductInterface::TITLE, $title);
    }

    public function getDescription()
    {
        return $this->getData(BasysProductInterface::DESCRIPTION);
    }

    public function setDescription($description)
    {
        return $this->setData(BasysProductInterface::DESCRIPTION, $description);
    }

    public function getWeight()
    {
        return $this->getData(BasysProductInterface::WEIGHT);
    }

    public function setWeight($weight)
    {
        return $this->setData(BasysProductInterface::WEIGHT, $weight);
    }

    public function getUsnspc()
    {
        return $this->getData(BasysProductInterface::USNSPC);
    }

    public function setUsnspc($code)
    {
        return $this->setData(BasysProductInterface::USNSPC, $code);
    }

    public function getReportSku()
    {
        return $this->getData(BasysProductInterface::REPORT_SKU);
    }

    public function setReportSku($sku)
    {
        return $this->setData(BasysProductInterface::REPORT_SKU, $sku);
    }

    public function getReportTitle()
    {
        return $this->getData(BasysProductInterface::REPORT_TITLE);
    }

    public function setReportTitle($title)
    {
        return $this->setData(BasysProductInterface::REPORT_TITLE, $title);
    }

    public function getBaseColour()
    {
        return $this->getData(BasysProductInterface::BASE_COLOUR);
    }

    public function setBaseColour($colourId)
    {
        return $this->setData(BasysProductInterface::BASE_COLOUR, $colourId);
    }

    public function getTrimColour()
    {
        return $this->getData(BasysProductInterface::TRIM_COLOUR);
    }

    public function setTrimColour($colourId)
    {
        return $this->setData(BasysProductInterface::TRIM_COLOUR, $colourId);
    }

    public function getPricesId()
    {
        return $this->getData(BasysProductInterface::PRICES_ID);
    }

    public function setPricesId($pricesId)
    {
        return $this->setData(BasysProductInterface::PRICES_ID, $pricesId);
    }

    public function getPriceBreakId()
    {
        return $this->getData(BasysProductInterface::PRICE_BREAK_ID);
    }

    public function setPriceBreakId($priceBreakId)
    {
        return $this->setData(BasysProductInterface::PRICE_BREAK_ID, $priceBreakId);
    }

    public function getStockItem()
    {
        return $this->getData(BasysProductInterface::STOCK_ITEM);
    }

    public function setStockItem($isStockItem)
    {
        return $this->setData(BasysProductInterface::STOCK_ITEM, $isStockItem);
    }

    public function getPointsRate()
    {
        return $this->getData(BasysProductInterface::POINTS_RATE);
    }

    public function getPointsValid()
    {
        return $this->getData(BasysProductInterface::POINTS_VALID);
    }

    public function setPointsValid($pointsValid)
    {
        return $this->setData(BasysProductInterface::POINTS_VALID, (bool) $pointsValid);
    }

    public function setPointsRate($rate)
    {
        return $this->setData(BasysProductInterface::POINTS_RATE, $rate);
    }

    public function getVersion()
    {
        return $this->getData(BasysProductInterface::VERSION);
    }

    public function setVersion($version)
    {
        return $this->setData(BasysProductInterface::VERSION, $version);
    }

    public function getChecksum()
    {
        $values = [];

        foreach ($this->getChecksumColumnNames() as $columName) {
            $data = $this->getData($columName);

            // hack job due to me not reading documentation correctly - aaron
            if ($columName == BasysProductInterface::WEIGHT && $data != null) {
                $data = number_format($data, 2, '.', '');
            }

            $values[] = $data == null ? '' : $data;
        }

        return crc32(implode("", $values));
    }

    public function getChecksumColumnNames()
    {
        return [
            BasysProductInterface::SKU,
            BasysProductInterface::TITLE,
            BasysProductInterface::DESCRIPTION,
            BasysProductInterface::WEIGHT,
            BasysProductInterface::USNSPC,
            BasysProductInterface::PRICE_BREAK_ID,
            BasysProductInterface::REPORT_SKU,
            BasysProductInterface::REPORT_TITLE
        ];
    }
}
