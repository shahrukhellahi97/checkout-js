<?php
namespace BA\BasysCatalog\Model\Product;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;

class BasysReadHandler implements ExtensionInterface
{
    public function execute($product, $arguments = [])
    {
        $extensionAttributes = $product->getExtensionAttributes();
        $extensionAttributes->setBasysId('100');
        $product->setExtensionAttributes($extensionAttributes);

        return $product;
    }
}