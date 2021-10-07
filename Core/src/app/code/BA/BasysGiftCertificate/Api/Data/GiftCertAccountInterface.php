<?php
namespace BA\BasysGiftCertificate\Api\Data;

interface GiftCertAccountInterface
{

    /**
     * Get gift enabled
     * @return $this
     */
    public function getGiftEnabled();

    /**
     * Set gift enabled
     * @param int $giftEnabled
     * @return $this
     */
    public function setGiftEnabled($giftEnabled);

    /**
     * Get gift reference number
     * @return $this
     */
    public function getGiftRefNumber();

    /**
     * Set giftRefNumber
     * @param string $giftRefNumber
     * @return $this
     */
    public function setGiftRefNumber($giftRefNumber);

    /**
     * Get gift amount
     * @return $this
     */
    public function getGiftAmt();

    /**
     * Set gift amount
     * @param float $giftAmt
     * @return $this
     */
    public function setGiftAmt($giftAmt);

    /**
     * Get gift amount used
     * @return $this
     */
    public function getGiftAmtUsed();

    /**
     * Set gift amount
     * @param float $giftAmtUsed
     * @return $this
     */
    public function setGiftAmtUsed($giftAmtUsed);

    /**
     * Get currency
     * @return this
     */
    public function getCurCurrency();

    /**
     * Set current currency
     * @param string $curCurrency
     * @return $this
     */
    public function setCurCurrency($curCurrency);

    /**
     * Get division Id
     * @return this
     */
    public function getDivisionId();

    /**
     * Set division Id
     * @param int $divisionId
     * @return $this
     */
    public function setDivisionId($divisionId);

}