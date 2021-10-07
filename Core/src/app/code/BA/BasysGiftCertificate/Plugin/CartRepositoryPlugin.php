<?php
namespace BA\BasysGiftCertificate\Plugin;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartExtensionFactory;
use Magento\Quote\Api\Data\CartInterface;

/**
 * Class CartRepositoryPlugin
 */
class CartRepositoryPlugin
{
    /**
     * @var CartExtensionFactory
     */
    private $extensionFactory;

    /**
     * CartRepositoryPlugin constructor.
     *
     * @param CartExtensionFactory $orderExtensionFactory
     */
    public function __construct(
        CartExtensionFactory $orderExtensionFactory
    ) {
        $this->extensionFactory = $orderExtensionFactory;
    }

    /**
     * @param CartRepositoryInterface $subject
     * @param CartInterface $resultEntity
     * @return CartInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGet(
        CartRepositoryInterface $subject,
        CartInterface $resultEntity
    ) {
        /** @var CartExtension $extensionAttributes */
        $extensionAttributes = $resultEntity->getExtensionAttributes() ? : 
        $this->extensionFactory->create();
        $extensionAttributes->setGiftAmt($resultEntity->getData('gift_amt'));
        $extensionAttributes->setUsedGiftAmt(
            $resultEntity->getData('used_gift_amt')
        );
        $extensionAttributes->setCertificateref(
            $resultEntity->getData('certificateref')
        );
        $extensionAttributes->setGiftEnabled($resultEntity->getData('gift_enabled'));
        $resultEntity->setExtensionAttributes($extensionAttributes);
        return $resultEntity;
    }

    /**
     * @param CartRepositoryInterface $subject
     * @param CartInterface $result
     * @return array
     */
    public function beforeSave(
        CartRepositoryInterface $subject,
        CartInterface $quote
    ) {
        $extensionAttributes = $quote->getExtensionAttributes() ?: 
        $this->extensionFactory->create();
        if ($extensionAttributes !== null) {
            $quote->setGiftAmt($extensionAttributes->getGiftAmt());
            $quote->setUsedGiftAmt($extensionAttributes->getUsedGiftAmt());
            $quote->setCertificateref($extensionAttributes->getCertificateref());
            $quote->setGiftEnabled($extensionAttributes->getGiftEnabled());
        }
        return [$quote];
    }
}
