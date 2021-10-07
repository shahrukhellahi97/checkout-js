<?php
namespace BA\BasysCatalog\Import\Product\Save\Table\Grouped\Attributes;

use BA\BasysCatalog\Import\Product\Save\TableInterface;
use Magento\Catalog\Api\Data\ProductInterface;

class Order implements TableInterface
{
    public function isMultipleInserts(): bool
    {
        return true;
    }

    public function getTable()
    {
        return 'catalog_product_link_attribute_int';
    }

    public function getRows(ProductInterface $product): array
    {
        $links = $product->getProductLinks();

        if (count($links) >= 1) {
            $result = [];

            $i = 1;

            foreach ($links as $link) 
            {
                $result[] = [
                    'product_link_attribute_id' => 5,
                    'link_id'  => $link->getId(),
                    'value' => $i
                ];

                $i += 1;
            }

            return $result;
        }

        return [];
    }

}