<?php

namespace Adflex\Payments\Ui\Token;

use Adflex\Payments\Ui\ConfigProvider;
use Magento\Vault\Api\Data\PaymentTokenInterface;
use Magento\Vault\Model\Ui\TokenUiComponentInterface;
use Magento\Vault\Model\Ui\TokenUiComponentInterfaceFactory;
use Magento\Vault\Model\Ui\TokenUiComponentProviderInterface;
use Magento\Framework\Serialize\SerializerInterface;

/**
 * Class TokenUiComponentProvider
 *
 * @package Adflex\Payments\Model\Ui\Token
 */
class TokenUiComponentProvider implements TokenUiComponentProviderInterface
{
    /**
     * @var \Magento\Vault\Model\Ui\TokenUiComponentInterfaceFactory
     */
    protected $_componentFactory;
    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    protected $_serializer;

    /**
     * TokenUiComponentProvider constructor.
     *
     * @param \Magento\Vault\Model\Ui\TokenUiComponentInterfaceFactory $componentInterfaceFactory
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     */
    public function __construct(
        TokenUiComponentInterfaceFactory $componentInterfaceFactory,
        SerializerInterface $serializer
    ) {
        $this->_componentFactory = $componentInterfaceFactory;
        $this->_serializer = $serializer;
    }

    /**
     * Get UI component for token
     * @param PaymentTokenInterface $paymentToken
     * @return TokenUiComponentInterface
     */
    public function getComponentForToken(PaymentTokenInterface $paymentToken)
    {
        $jsonDetails = $this->_serializer->unserialize($paymentToken->getTokenDetails() ?: '{}');
        $jsonDetails['expirationDate'] = date('m/Y', strtotime($jsonDetails['expirationDate']));
        return $this->_componentFactory->create(
            [
                'config' => [
                    'code' => ConfigProvider::CC_VAULT_CODE,
                    TokenUiComponentProviderInterface::COMPONENT_DETAILS => $jsonDetails,
                    TokenUiComponentProviderInterface::COMPONENT_PUBLIC_HASH => $paymentToken->getPublicHash()
                ],
                'name' => 'Adflex_Payments/js/view/payment/method-renderer/vault'
            ]
        );
    }
}
