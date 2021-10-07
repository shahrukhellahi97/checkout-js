<?php
namespace BA\BasysCatalog\ViewModel;

use BA\BasysCatalog\Helper\Data;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class LoadajaxUrl implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \BA\BasysCatalog\Helper\Data
     */
    protected $helper;

    public function __construct(
        StoreManagerInterface $storeManager,
        Data $helper,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->helper = $helper;
        $this->storeManager = $storeManager;
    }
    public function getAjaxUrl()
    {
        /** @var \Magento\Store\Model\Store $store */
        $store = $this->storeManager->getStore();

        return $store->getBaseUrl() . 'admin/ba_catalog/index/index';
    }
}
