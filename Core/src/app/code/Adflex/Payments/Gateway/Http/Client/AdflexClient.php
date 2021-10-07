<?php
/**
 * Copyright @ 2020 Adflex Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Adflex\Payments\Gateway\Http\Client;

use Adflex\Payments\Gateway\Http\Client\Api\Json;
use Adflex\Payments\Helper\Data;
use Magento\Checkout\Model\Session;
use Magento\Payment\Gateway\Http\ClientException;
use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\ConverterException;
use Magento\Payment\Gateway\Http\TransferInterface;
use Magento\Payment\Model\Method\Logger;

/**
 * Class AdflexClient
 *
 * @package Adflex\Payments\Gateway\Http\Client
 */
class AdflexClient implements ClientInterface
{
    /**
     * @var \Magento\Payment\Model\Method\Logger
     */
    protected $_logger;
    /**
     * @var \Magento\Payment\Gateway\Http\Client\Zend
     */
    protected $_http;
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
    /**
     * @var \Adflex\Payments\Helper\Data
     */
    protected $_helper;

    /**
     * AdflexClient constructor.
     *
     * @param \Magento\Payment\Model\Method\Logger $logger
     * @param \Adflex\Payments\Gateway\Http\Client\Api\Json $http
     * @param \Magento\Checkout\Model\Session $session
     * @param \Adflex\Payments\Helper\Data $helper
     */
    public function __construct(
        Logger $logger,
        Json $http,
        Session $session,
        Data $helper
    ) {
        $this->_logger = $logger;
        $this->_http = $http;
        $this->_checkoutSession = $session;
        $this->_helper = $helper;
    }

    /**
     * @param \Magento\Payment\Gateway\Http\TransferInterface $transferObject
     * @return array
     * Makes request to Adflex.
     */
    public function placeRequest(TransferInterface $transferObject)
    {
        $response = [];

        try {
            $response = $this->_http->placeRequest($transferObject);
        } catch (ClientException $e) {
            $this->_logger->debug([
                'error_message' => $e->getMessage()
            ]);
        } catch (ConverterException $e) {
            $this->_logger->debug([
                'error_message' => $e->getMessage()
            ]);
        } catch (\Zend_Http_Client_Exception $e) {
            $this->_logger->debug([
                'error_message' => $e->getMessage()
            ]);
        }

        return $response;
    }

    /**
     * @return array|bool
     * Returns adflex session.
     */
    private function hasAdflexData()
    {
        $adflexSession = $this->_checkoutSession->getAdflexData();
        if (!is_null($adflexSession)) {
            $adflexTtl = $adflexSession['time_to_live'];
            $sessionCreated = $adflexSession['request_made_at'];
            $currentTime = time();

            if ((($sessionCreated + $adflexTtl) >= $currentTime)
                && $adflexSession['mode'] == $this->_helper->getActualMode()) {
                // Return what we already have.
                return $adflexSession;
            }
        }

        return false;
    }
}
