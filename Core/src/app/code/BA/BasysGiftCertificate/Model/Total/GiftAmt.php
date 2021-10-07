<?php
namespace BA\BasysGiftCertificate\Model\Total;

use Psr\Log\LoggerInterface;
use BA\BasysGiftCertificate\Api\GiftQuoteManagementInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Quote\Api\CartRepositoryInterface;
use BA\BasysGiftCertificate\Api\Data\GiftCertAccountInterface;

class GiftAmt extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    /**
     * Collect grand total address amount
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this
     */
    protected $quoteValidator = null;
    protected $checkoutSession;
    protected $logger;
    protected $giftManagement;
    protected $quoteRepository;
   
    public function __construct(
        \Magento\Quote\Model\QuoteValidator $quoteValidator,
        CheckoutSession $checkoutSession,
        LoggerInterface $logger,
        GiftQuoteManagementInterface $giftManagement,
        CartRepositoryInterface $quoteRepository,
        GiftCertAccountInterface $giftAccountData,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->quoteValidator = $quoteValidator;
        $this->priceCurrency = $priceCurrency;
        $this->giftManagement = $giftManagement;
        $this->logger = $logger;
        $this->giftAccountData = $giftAccountData ;
        $this->quoteRepository = $quoteRepository;
    }
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        $items = $shippingAssignment->getItems();
        if (!count($items)) {
            return $this;
        }
        parent::collect($quote, $shippingAssignment, $total);

        $giftAmt = (float) $quote->getExtensionAttributes()->getGiftAmt();
      
        if ($quote->getExtensionAttributes()->getGiftEnabled()) {
            $usedGiftAmt = ($giftAmt >= $quote->getSubtotal()) ?
            $quote->getSubtotal() : $giftAmt;
            $total->addTotalAmount('gift_amt', -$usedGiftAmt);
            $total->addBaseTotalAmount('gift_amt', -$usedGiftAmt);
            $quote->setGiftAmt($usedGiftAmt);
            $total->setBaseGrandTotal($total->getBaseGrandTotal());
            $total->setGrandTotal($total->getGrandTotal());
            $quote->getExtensionAttributes()->setUsedGiftAmt($usedGiftAmt);
            $this->giftAccountData->setGiftAmtUsed($usedGiftAmt);        
        }


        return $this;
    }

    protected function clearValues(Address\Total $total)
    {
        $total->setTotalAmount('subtotal', 0);
        $total->setBaseTotalAmount('subtotal', 0);
        $total->setTotalAmount('tax', 0);
        $total->setBaseTotalAmount('tax', 0);
        $total->setTotalAmount('discount_tax_compensation', 0);
        $total->setBaseTotalAmount('discount_tax_compensation', 0);
        $total->setTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setBaseTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setSubtotalInclTax(0);
        $total->setBaseSubtotalInclTax(0);
    }
   
    /**
     * Assign subtotal amount and label to address object
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param Address\Total $total
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {
        return [
            'code' => 'gift_amt',
            'title' => 'gift_amt',
            'value' => -$this->giftAccountData->getGiftAmtUsed()
        ];
    }

    /**
     * Get Subtotal label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __('giftAmt');
    }
}
