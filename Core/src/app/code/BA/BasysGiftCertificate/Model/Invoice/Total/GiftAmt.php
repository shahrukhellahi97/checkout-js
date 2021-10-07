<?php
namespace BA\BasysGiftCertificate\Model\Invoice\Total;

use Magento\Sales\Model\Order\Invoice\Total\AbstractTotal;
use Psr\Log\LoggerInterface;

class GiftAmt extends AbstractTotal
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    
    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }
    /**
     * @param \Magento\Sales\Model\Order\Invoice $invoice
     * @return $this
     */
    public function collect(\Magento\Sales\Model\Order\Invoice $invoice)
    {
        try {
            $invoice->setGiftAmt(0);
            $amount = $invoice->getOrder()->getGiftAmt();
            $invoice->setGiftAmt($amount);
            if ($invoice->getGrandTotal() == abs($amount)) {
                $invoice->setGrandTotal(0);
                $invoice->setBaseGrandTotal(0);
            } else {
                $invoice->setGrandTotal($invoice->getGrandTotal() - abs($amount));
                $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() - abs($amount));
            }
            return $this;
        } catch (\Exception $e) {
            $this->logger->info('invoice error');
            $this->logger->error($e->getMessage());
        }
    }
}
