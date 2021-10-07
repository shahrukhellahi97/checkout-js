<?php
namespace BA\BasysCatalog\Api;

use BA\BasysCatalog\Api\Data\BasysProductInterface;

interface BasysProductManagementInterface
{
    public function getLevel(BasysProductInterface $basysProduct);
}
