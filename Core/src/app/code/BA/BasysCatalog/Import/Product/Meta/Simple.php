<?php
namespace BA\BasysCatalog\Import\Product\Meta;

use BA\BasysCatalog\Api\Data\BasysProductInterface;
use BA\BasysCatalog\Import\Product\ProductMetadataProviderInterface;

class Simple implements ProductMetadataProviderInterface
{
    public function getSku(BasysProductInterface $product): string
    {
        if ($this->isSimple($product)) {
            return $product->getSku();
        }

        if ($product->getDivisionId() == 433 && preg_match('/^[0-9]+$/', $product->getSku())) {
            return 'SCA' . $product->getReportSku();
        }

        return $product->getReportSku();
    }

    private function isSimple(BasysProductInterface $product): bool
    {
        return false;
    }
}