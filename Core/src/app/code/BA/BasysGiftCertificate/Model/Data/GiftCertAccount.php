<?php
namespace BA\BasysGiftCertificate\Model\Data;

use BA\BasysGiftCertificate\Api\Data\GiftCertAccountInterface;

class GiftCertAccount implements GiftCertAccountInterface
{
    private $giftAmt;
    private $giftEnabled;
    private $giftRefNumber;
    private $curCurrency;
    private $giftAmtUsed;
    private $divisionId;

    /**
     * Get Gift Enabled
     * @return $this
     */
    public function getGiftEnabled()
    {
        return $this->giftEnabled;
    }

    /**
     * Set Gift Enabled
     * @param int $giftEnabled
     * @return $this
     */
    public function setGiftEnabled($giftEnabled)
    {
        $this->giftEnabled = $giftEnabled;
        return $this;
    }

    /**
     * Get gift reference number
     * @return $this
     */
    public function getGiftRefNumber()
    {
        return $this->giftRefNumber;
    }

    /**
     * Get gift reference number
     * @param string $giftRefNumber
     * @return $this
     */
    public function setGiftRefNumber($giftRefNumber)
    {
        $this->giftRefNumber = $giftRefNumber;
        return $this;
    }
    /**
     * Get current currency
     * @return $this
     */
    public function getCurCurrency()
    {
        return $this->curCurrency;
    }
    /**
     * Set curCurrency
     * @param string $curCurrency
     * @return $this
     */
    public function setCurCurrency($curCurrency)
    {
        $this->curCurrency = $curCurrency;
        return $this;
    }

    /**
     * Get gift amt
     * @return $this
     */
    public function getGiftAmt()
    {
        return $this->giftAmt;
    }
    /**
     * set gift amount
     * @param float $giftAmt
     * @return $this
     */
    public function setGiftAmt($giftAmt)
    {
        $this->giftAmt = $giftAmt;
        return $this;
    }

    /**
     * Get gift amt used
     * @return $this
     */
    public function getGiftAmtUsed()
    {
        return $this->giftAmtUsed;
    }

    /**
     * Set gift amt used
     * @param float $giftAmtUsed
     * @return $this
     */
    public function setGiftAmtUsed($giftAmtUsed)
    {
        $this->giftAmtUsed = $giftAmtUsed;
        return $this;
    }

    /**
     * Get division Id
     * @return $this
     */
    public function getDivisionId()
    {
        return $this->divisionId;
    }

    /**
     * Set division Id
     * @param int $divisionId
     * @return $this
     */
    public function setDivisionId($divisionId)
    {
        $this->divisionId = $divisionId;
        return $this;
    }
}
