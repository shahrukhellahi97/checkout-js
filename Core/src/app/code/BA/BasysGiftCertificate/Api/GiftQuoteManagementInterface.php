<?php
namespace BA\BasysGiftCertificate\Api;

use BA\BasysGiftCertificate\Api\Data\GiftCertAccountInterface;
use Magento\Quote\Api\Data\CartInterface;

interface GiftQuoteManagementInterface
{
    /**
     * Apply Gift Amount
     * @param string $certificateRef
     * @param int $quoteId
     * @param float $giftAmt
     * @return
     */
    public function applyGiftCard($certificateRef, $quoteId, $giftAmt);

    /**
     * Remove giftcard
     * @param int $quoteId
     * @return mixed
     */
    public function removeGiftCard($quoteId);

    /**
     * Check if gift card applied to given cart.
     * @param mixed $quoteId
     * @return bool
     */
    public function checkGiftCardAdded($quoteId):int;

    /**
     * 
     * @param mixed $quote
     * @return \BA\BasysGiftCertificate\Api\Data\GiftCertAccountInterface
     */
    public function get(CartInterface $quote) : GiftCertAccountInterface;
}
