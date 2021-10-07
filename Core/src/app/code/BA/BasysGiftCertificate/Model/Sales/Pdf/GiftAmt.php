<?php
namespace BA\BasysGiftCertificate\Model\Sales\Pdf;

use Psr\Log\LoggerInterface;

class GiftAmt extends \Magento\Sales\Model\Order\Pdf\Total\DefaultTotal
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
    public function getTotalsForDisplay()
    {
        try {
            $giftAmt = $this->getOrder()->getGiftAmt();
            $amount = $this->getOrder()->formatPriceTxt($giftAmt);
            if ($this->getAmountPrefix()) {
                $amount = $this->getAmountPrefix() . $amount;
            }

            $title = __($this->getTitle());
            if ($this->getTitleSourceField()) {
                $label = $title . ' (' . $this->getTitleDescription() . '):';
            } else {
                $label = $title . ':';
            }

            $fontSize = $this->getFontSize() ? $this->getFontSize() : 7;
            $total = ['amount' => $amount, 'label' => $label, 'font_size' => $fontSize];
            return [$total];
        } catch (\Exception $e) {
            $this->logger->info('pdf error');
            $this->logger->error($e->getMessage());
        }
    }
}
