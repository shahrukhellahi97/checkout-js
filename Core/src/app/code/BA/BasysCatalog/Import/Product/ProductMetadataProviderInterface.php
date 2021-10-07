<?php
namespace BA\BasysCatalog\Import\Product;

use BA\BasysCatalog\Api\Data\BasysProductInterface;

interface ProductMetadataProviderInterface
{
    public function getSku(BasysProductInterface $product): string;
}