<?php
namespace BA\BasysCatalog\Webservices\Response;

use BA\Basys\Webservices\Response\HandlerInterface;
use BA\BasysCatalog\Api\Data\BasysProductInterfaceFactory;
use BA\BasysCatalog\Api\Data\BasysProductPriceInterfaceFactory;
use BA\BasysCatalog\Api\Data\BasysProductPriceTypeInterface;

class GetProductDetailsHandler implements HandlerInterface
{
    /**
     * @var \Magento\Framework\Xml\Parser
     */
    protected $parser;

    /**
     * @var \BA\BasysCatalog\Api\Data\BasysProductInterfaceFactory
     */
    protected $basysProductFactory;

    /**
     * @var \BA\BasysCatalog\Api\Data\BasysProductPriceInterfaceFactory
     */
    protected $basysProductPriceFactory;

    public function __construct(
        \Magento\Framework\Xml\Parser $parser,
        BasysProductInterfaceFactory $basysProductFactory,
        BasysProductPriceInterfaceFactory $basysProductPriceFactory
    ) {
        $this->parser = $parser;
        $this->basysProductFactory = $basysProductFactory;
        $this->basysProductPriceFactory = $basysProductPriceFactory;
    }

    public function handle($response, array $additional = [])
    {
        $x = array_keys($response);
        
        $xml = $response['GetProductDetailsResult']['any'];
        $x = $this->parser->loadXML($xml)->xmlToArray();

        return $this->getProducts(
            $x['Catalogue']['Product'],
            $additional['division_id']
        );
    }

    /**
     * @param array $response
     * @param int $divisionId
     * @return \BA\BasysCatalog\Api\Data\BasysProductInterface[]|array
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    protected function getProducts(array $response, $divisionId)
    {
        $result = [];

        foreach ($response as $data) {
            if (is_array($data)) {
                /** @var \BA\BasysCatalog\Api\Data\BasysProductInterface $product */
                $product = $this->basysProductFactory->create();

                $product->setCatalogId($this->getFromArray($data, 'CATALOGUE_ID'))
                ->setBasysId($this->getFromArray($data, 'PRODUCT_ID'))
                ->setDivisionId($divisionId)
                ->setSku($this->getFromArray($data, 'CATALOGUE_ALIAS'))
                ->setTitle($this->getFromArray($data, 'PRODUCT_DESCRIPTION'))
                ->setDescription($this->getFromArray($data, 'WEB_DESCRIPTION'))
                ->setWeight($this->getFromArray($data, 'DIMENSIONAL_WEIGHT'))
                ->setUsnspc($this->getFromArray($data, 'UNSPSC_CODE'))
                ->setReportSku($this->getFromArray($data, 'REPORT_SKU'))
                ->setReportTitle($this->getFromArray($data, 'REPORT_SKU_DESCRIPTION'))
                ->setBaseColour($this->getFromArray($data, 'BASE_COLOUR'))
                ->setTrimColour($this->getFromArray($data, 'TRIM_COLOUR'))
                ->setPricesId($this->getFromArray($data, 'PRICES_ID'))
                ->setPriceBreakId($this->getFromArray($data, 'PRICE_BREAK_ID'))
                ->setStockItem($this->getFromArray($data, 'STOCK_ITEM') == 'Y' ? true : false)
                ->setVersion($this->getFromArray($data, 'CHANGE_ID'))
                ->setPointsRate($this->getFromArray($data, 'POINTS_RATE'))
                ->setPointsValid($this->getFromArray($data, 'POINTS_VALID') == 'Y' ? true : false);

                $prices = [];

                for ($i = 1; $i <= 5; $i++) {
                    if (isset($data['SALES_PRICE' . $i])) {
                        /** @var \BA\BasysCatalog\Api\Data\BasysProductPriceInterface $price */
                        $price = $this->basysProductPriceFactory->create();
                        try {
                            $price->setBasysId($product->getBasysId())
                            ->setBreak($data['BREAK' . $i])
                            ->setPrice($data['SALES_PRICE' . $i])
                            ->setCatalogId($product->getCatalogId())
                            ->setType(constant(BasysProductPriceTypeInterface::class . '::SALES_PRICE_' . $i));

                            $prices[] = $price;
                        } catch (\Exception $e) {
                            $x = $e->getMessage();
                        }
                    }
                }

                $product->setPrices($prices);

                $result[] = $product;
            }
        }

        return $result;
    }

    protected function getFromArray(array $arr, $key, $default = null)
    {
        return isset($arr[$key]) ? $arr[$key] : $default;
    }
}