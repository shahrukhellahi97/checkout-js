<?php
namespace BA\BasysCatalog\Model\Layer\Filter;

use BA\BasysCatalog\Model\Layer\DataProvider\Price as PriceProvider;
use BA\BasysCatalog\Api\BasysStoreManagementInterface;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Filter\DataProvider\PriceFactory;
use Magento\Catalog\Model\Layer\Filter\Item\DataBuilder;
use Magento\Catalog\Model\Layer\Filter\ItemFactory;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class Price extends \Magento\Catalog\Model\Layer\Filter\AbstractFilter
{

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \BA\BasysPriceLayeredNav\Helper\Data
     */
    protected $layerPriceHelper;

    /**
     * @var \BA\BasysCatalog\Api\BasysStoreManagementInterface
     */
    protected $BasysStoreManagement;

    public function __construct(
        ItemFactory $filterItemFactory,
        StoreManagerInterface $storeManager,
        Layer $layer,
        DataBuilder $itemDataBuilder,
        PriceCurrencyInterface $priceCurrency,
        PriceFactory $dataProviderFactory,
        BasysStoreManagementInterface $BasysStoreManagement,
        PriceProvider $layerPriceHelper,
        LoggerInterface $logger,
        array $data = []
    ) {
        parent::__construct($filterItemFactory, $storeManager, $layer, $itemDataBuilder, $data);
        $this->_requestVar = 'basys_price';
        $this->logger = $logger;
        $this->BasysStoreManagement = $BasysStoreManagement;
        $this->priceCurrency = $priceCurrency;
        $this->layerPriceHelper = $layerPriceHelper;
        $this->dataProvider = $dataProviderFactory->create(['layer' => $this->getLayer()]);
    }

    /**
     * Apply basys price filter to layer
     *
     * @param   \Magento\Framework\App\RequestInterface $request
     * @return  $this
     */
    public function apply(\Magento\Framework\App\RequestInterface $request)
    {
        $filter = $request->getParam($this->getRequestVar());

        if (!$filter || is_array($filter)) {
            return $this;
        }

        //validate filter
        $filterParams = explode(',', $filter);
        $filter = $this->dataProvider->validateFilter($filterParams[0]);

        if (!$filter) {
            return $this;
        }

        list($from, $to) = $filter;
    
        $collection = $this->getLayer()->getProductCollection();
        $collection->getSelect()
            ->join(['mapp'=>'ba_basys_catalog_product_map'], 'e.entity_id = mapp.entity_id')
            ->join(['productprice'=>'ba_basys_catalog_product_price'], 'productprice.basys_id = mapp.basys_id')
            ->where('productprice.catalog_id = ?', $this->BasysStoreManagement->getActiveCatalog()->getId())
            ->where('productprice.price >= ?', $from)
            ->where('productprice.price <= ?', $to);

        $this->getLayer()
            ->getState()
            ->addFilter(
                $this->_createItem($this->_renderRangeLabel(empty($from) ? 0 : $from, $to), $filter)
            );

        return $this;
    }

    /**
     * Get filter value for reset current filter state
     *
     * @return mixed|null
     */
    public function getResetValue()
    {
        return $this->dataProvider->getResetValue();
    }

    /**
     * Get filter name
     *
     * @return \Magento\Framework\Phrase
     */
    public function getName()
    {
        return __('Custom Price');
    }

    /**
     * Prepare text of range label
     *
     * @param float|string $fromPrice
     * @param float|string $toPrice
     * @return float|\Magento\Framework\Phrase
     */
    protected function _renderRangeLabel($fromPrice, $toPrice)
    {
        $formattedFromPrice = $this->priceCurrency->format($fromPrice);

        if ($toPrice === '') {
            return __('%1 and above', $formattedFromPrice);
        } else {
            return __('%1 - %2', $formattedFromPrice, $this->priceCurrency->format($toPrice));
        }
    }

    /**
     * Get price ranges and create ranges
     *
     * @return array
     */
    protected function _getItemsData()
    {
        $ranges = $this->layerPriceHelper->getPrices();

        foreach ($ranges as $range) {
            $lowLimitLbl = $this->priceCurrency->format($range['lowLimit']);
            $highLimitLbl = $this->priceCurrency->format($range['highLimit']);

            $this->itemDataBuilder->addItemData(
                $lowLimitLbl . ' - ' . $highLimitLbl,
                $range['lowLimit'] . '-' . $range['highLimit'],
                $range['count']
            );
        }
        return $this->itemDataBuilder->build();
    }
}
