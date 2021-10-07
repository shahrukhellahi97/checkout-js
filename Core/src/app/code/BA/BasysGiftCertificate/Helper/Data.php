<?php
namespace BA\BasysGiftCertificate\Helper;

use BA\BasysGiftCertificate\Api\GiftManagementInterface;
use Psr\Log\LoggerInterface;

class Data
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(
        GiftManagementInterface $giftManagement,
        LoggerInterface $logger
    ) {
        $this->giftManagement = $giftManagement;
        $this->logger = $logger;
    }
    /**
     * Validate gift reference
     * @param mixed $certificateRef
     * @return int|false
     */
    // public function validateCertificateRef($certificateRef)
    // {
    //     return preg_match("/^([0-9A-Z]{4}-){3}[0-9A-Z]{4}$/", $certificateRef);
    // }
    // public function getGiftBalanceFromWebServices($certificateRef)
    // {
    //     $balanceData = [];
    //     try {
    //         $balanceInfo = $this->giftManagement->checkBalance($certificateRef);
    //         if (isset($balanceInfo['InvalidMsg'])) {
    //             $balanceData['Error'] = 'The Gift Certificate is not valid';
    //         } else {
    //             $balanceData['Balance'] = $balanceInfo['Amount'];

    //         }
    //     } catch (\Exception $e) {
    //         $this->logger->error($e->getMessage());
    //     }
    //     return $balanceData;
    // }
}
