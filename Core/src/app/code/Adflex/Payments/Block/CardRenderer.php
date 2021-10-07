<?php
/**
 * Copyright @ 2020 Adflex Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Adflex\Payments\Block;

use Adflex\Payments\Ui\ConfigProvider;
use Magento\Vault\Api\Data\PaymentTokenInterface;
use Magento\Vault\Block\AbstractCardRenderer;

/**
 * Class CardRenderer
 *
 * @package Adflex\Payments\Block
 */
class CardRenderer extends AbstractCardRenderer
{
    /**
     * Can render specified token
     *
     * @param PaymentTokenInterface $token
     * @return boolean
     * @since 100.1.0
     */
    public function canRender(PaymentTokenInterface $token)
    {
        return $token->getPaymentMethodCode() === ConfigProvider::CODE;
    }

    /**
     * @return false|string
     */
    public function getNumberLast4Digits()
    {
        return $this->getTokenDetails()['maskedCC'];
    }

    /**
     * @return false|string
     */
    public function getExpDate()
    {
        return date('m/y', strtotime($this->getTokenDetails()['expirationDate']));
    }

    /**
     * @return false|string
     */
    public function getIconUrl()
    {
        return $this->getIconForType($this->getCardType($this->getTokenDetails()['type']))['url'];
    }

    /**
     * @return false|int
     */
    public function getIconHeight()
    {
        return $this->getIconForType($this->getCardType($this->getTokenDetails()['type']))['height'];
    }

    /**
     * @return false|int
     */
    public function getIconWidth()
    {
        return $this->getIconForType($this->getCardType($this->getTokenDetails()['type']))['width'];
    }

    /**
     * @param $type
     * @return string|null
     * Gets the card type and converts into Magento standard.
     */
    private function getCardType($type)
    {
        switch ($this->getTokenDetails()['type']) {
            case 'MasterCard':
            case 'Debit MasterCard':
                $type = 'MC';
                break;
            case 'Visa Purchase':
            case 'Visa':
            case 'VISA':
            case 'Visa Electron':
                $type = 'VI';
                break;
            case 'American Express':
            case 'Amex':
            case 'AMEX':
                $type = 'AE';
                break;
            case 'Solo':
                $type = 'SO';
                break;
            case 'Switch/Maestro':
                $type = 'SM';
                break;
            case 'Maestro International':
            case 'International Maestro':
                $type = 'MI';
                break;
            case 'Maestro Domestic':
                $type = 'MD';
                break;
        }
        return $type;
    }
}
