<?php
namespace BA\BasysGiftCertificate\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Backend\App\Action\Context;
use Psr\Log\LoggerInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Controller\Result\JsonFactory;
use BA\BasysGiftCertificate\Api\Data\GiftCertAccountInterface;
use BA\BasysGiftCertificate\Api\GiftQuoteManagementInterface;

class Remove extends Action
{
    protected $logger;
    protected $checkoutSession;
    protected $resultJsonFactory;
    protected $giftQuoteManagement;
    protected $giftCertAccount;
   
    public function __construct(
        Context $context,
        CheckoutSession $checkoutSession,
        JsonFactory $resultJsonFactory,
        LoggerInterface $logger,
        GiftCertAccountInterface $giftCertAccount,
        GiftQuoteManagementInterface $giftQuoteManagement
    ) {
        $this->logger = $logger;
        $this->checkoutSession = $checkoutSession;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->giftQuoteManagement = $giftQuoteManagement;
        $this->giftCertAccount = $giftCertAccount;
        parent::__construct($context);
    }

    public function execute()
    {
        try {
            $getAjaxPostValues = $this->getRequest()->getPostValue();
            if (isset($getAjaxPostValues['removeGiftCard'])) {
                $this->giftQuoteManagement->removeGiftCard(
                    $this->checkoutSession->getQuoteId()
                );
                $this->messageManager->addSuccessMessage(
                    __('Gift card removed successfully')
                );
            } else {
                $visibleDiv = $this->giftQuoteManagement->checkGiftCardAdded(
                    $this->checkoutSession->getQuoteId()
                );
                $this->logger->info('controller '.$visibleDiv);

                /** @var \Magento\Framework\Controller\Result\Json $result */
                $resultJson = $this->resultJsonFactory->create();
                $resultJson->setData($visibleDiv);
                return $resultJson;
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
