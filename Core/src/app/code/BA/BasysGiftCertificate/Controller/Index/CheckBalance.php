<?php
namespace BA\BasysGiftCertificate\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Backend\App\Action\Context;
use Psr\Log\LoggerInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use BA\BasysGiftCertificate\Api\GiftManagementInterface;

class CheckBalance extends Action
{

    protected $resultJsonFactory;
    protected $logger;
    protected $giftManagement;
   
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        GiftManagementInterface $giftManagement,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->giftManagement = $giftManagement;
        parent::__construct($context);
    }

    public function execute()
    {
        try {
            $getAjaxPostValues = $this->getRequest()->getPostValue();
            $balanceInfo = $this->giftManagement->checkBalance(
                trim($getAjaxPostValues['certificateRef'])
            );
            /** @var \Magento\Framework\Controller\Result\Json $result */
            $resultJson = $this->resultJsonFactory->create();
            $resultJson->setData($balanceInfo);
            return $resultJson;
                
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
