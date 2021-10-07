<?php
namespace BA\BasysGiftCertificate\Model\Request\Builder;

use Psr\Log\LoggerInterface;
//use BA\BasysStore\Api\BasysStoreManagementInterface;
//use BA\BasysStore\Helper\Data;

class GiftRequest
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    /**
     * @var \BA\BasysStore\Api\BasysStoreManagementInterface
     */
    protected $basysStoreManagement;
    /**
     * @var \BA\BasysStore\Helper\Data
     */
    protected $configHelper;

    public function __construct(
        LoggerInterface $logger
       // BasysStoreManagementInterface $basysStoreManagement
      //  Data $configHelper
    ) {
        $this->logger = $logger;
     //   $this->basysStoreManagement = $basysStoreManagement;
       // $this->configHelper = $configHelper;
    }
   
    public function build($certificateReference): array
    {
        try {
           // $this->logger->info('division id : '.$this->configHelper->getDivisionId());
           // $this->logger->info('Active catalog : '.$this->basysStoreManagement->getActiveCatalog()->getId());

                return [
                'CheckBalance' => [
                    'CertificateReference' => $certificateReference,
                    'Currency' => 'EUR',
                   // 'Currency' => $this->basysStoreManagement->getActiveCatalog()->getId(),
                    'DivisionID' => '218'
                ],
                ];
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
