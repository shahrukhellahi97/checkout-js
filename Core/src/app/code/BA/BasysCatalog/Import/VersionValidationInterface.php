<?php
namespace BA\BasysCatalog\Import;

use BA\BasysCatalog\Api\Data\BasysProductInterface;
use BA\BasysCatalog\Api\Data\ChecksumInterface;

interface VersionValidationInterface
{
    /**
     * @param \BA\BasysCatalog\Api\Data\BasysProductInterface $product
     * @return bool
     */
    public function valid(BasysProductInterface $product);

    /**
     * @param int $catalogId
     * @param int $productId
     * @param int $version
     * @return void
     */
    public function set($catalogId, $productId, $version);
}
