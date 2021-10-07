<?php
/**
 * Copyright @ 2020 Adflex Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Adflex\Payments\Controller\Index;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\View\Result\PageFactory;
use Magento\Payment\Gateway\Command\CommandManagerPoolInterface;

/**
 * Class Session
 *
 * @package Adflex\Payments\Controller
 */
class Session extends Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_jsonResultFactory;
    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    protected $_formKeyValidator;
    /**
     * @var \Magento\Payment\Gateway\Command\CommandManagerPoolInterface
     */
    protected $_commandPool;
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * Session constructor.
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonResultFactory
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Magento\Payment\Gateway\Command\CommandManagerPoolInterface $commandPool
     * @param \Magento\Checkout\Model\Session $session
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        JsonFactory $jsonResultFactory,
        Validator $formKeyValidator,
        CommandManagerPoolInterface $commandPool,
        CheckoutSession $session
    ) {
        $this->_pageFactory = $pageFactory;
        $this->_jsonResultFactory = $jsonResultFactory;
        $this->_formKeyValidator = $formKeyValidator;
        $this->_commandPool = $commandPool;
        $this->_checkoutSession = $session;
        return parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * It has been rendered so we just need to call Adflex to create a session for this transaction, show Ajax spinner while waiting.
     */
    public function execute()
    {
        // 2.3.x
        $commandPool = $this->_commandPool->get('adflex');
        if (interface_exists("\Magento\Framework\App\CsrfAwareActionInterface")) {
            $request = $this->getRequest();
            if ($request instanceof Http && $request->isPost() && !is_null($request->getParam('form_key'))) {
                $formKeyValid = $this->_formKeyValidator->validate($request);
                if ($formKeyValid) {
                    $commandPool->executeByCode('session');
                    $session = $this->_checkoutSession->getAdflexData();
                } else {
                    $session = ['error_message' => 'form key is invalid'];
                }
            } else {
                $session = ['error_message' => 'form key is missing'];
            }
        } else {
            // 2.2.x / 2.1.x / 2.0.x
            $commandPool->executeByCode('session');
            $session = $this->_checkoutSession->getAdflexData();
        }

        $jsonResult = $this->_jsonResultFactory->create();
        $jsonResult->setData($session);
        return $jsonResult;
    }

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @return \Magento\Framework\App\Request\InvalidRequestException|null
     */
    public function createCsrfValidationException(
        RequestInterface $request
    ): ?InvalidRequestException {
        return null;
    }

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @return bool|null
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }
}
