<?php
namespace BA\Enquiries\Model\Product\Type;

use Magento\Catalog\Model\Product;

class Enquiry extends \Magento\Catalog\Model\Product\Type\AbstractType
{

    public function deleteTypeSpecificData(Product $product) { }

}