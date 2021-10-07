<?php
namespace BA\BasysGiftCertificate\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Backend\App\Action\Context;
use Psr\Log\LoggerInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Checkout\Model\Session as CheckoutSession;
use BA\BasysGiftCertificate\Api\GiftManagementInterface;
use BA\BasysGiftCertificate\Api\GiftQuoteManagementInterface;

class ApplyGiftCard extends Action
{

    protected $resultJsonFactory;
    protected $logger;
    protected $giftManagement;
    protected $giftQuoteManagement;
    private $checkoutSession;
     
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        CheckoutSession $checkoutSession,
        LoggerInterface $logger,
        GiftManagementInterface $giftManagement,
        GiftQuoteManagementInterface $giftQuoteManagement
    ) {
        $this->logger = $logger;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->checkoutSession = $checkoutSession;
        $this->giftManagement = $giftManagement;
        $this->giftQuoteManagement = $giftQuoteManagement;
        parent::__construct($context);
    }

    public function execute()
    {
        try {
            $getAjaxPostValues = $this->getRequest()->getPostValue();
            $balanceInfo = $this->giftManagement->checkBalance(
                trim($getAjaxPostValues['certificateRef'])
            );
            if (isset($balanceInfo['Balance'])) {
                $this->giftQuoteManagement->applyGiftCard(
                    trim($getAjaxPostValues['certificateRef']),
                    $this->checkoutSession->getQuoteId(),
                    $balanceInfo['Balance']
                );
                $this->messageManager->addSuccessMessage(
                    __('Gift card "%1" added', $getAjaxPostValues['certificateRef'])
                );
            }
    
            /** @var \Magento\Framework\Controller\Result\Json $result */
            $resultJson = $this->resultJsonFactory->create();
            $resultJson->setData($balanceInfo);
            return $resultJson;
                
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
