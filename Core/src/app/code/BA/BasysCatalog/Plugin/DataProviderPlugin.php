<?php
namespace BA\BasysCatalog\Plugin;

class DataProviderPlugin 
{
    public function afterGetData(\Magento\Catalog\Ui\DataProvider\Product\ProductDataProvider $subject, $result)
    {
        return $result;
    }
}