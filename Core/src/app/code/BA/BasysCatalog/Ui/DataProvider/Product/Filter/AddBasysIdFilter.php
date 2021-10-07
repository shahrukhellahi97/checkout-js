<?php
namespace BA\BasysCatalog\Ui\DataProvider\Product\Filter;

use Magento\Framework\Data\Collection;
use Magento\Ui\DataProvider\AddFilterToCollectionInterface;

class AddBasysIdFilter implements AddFilterToCollectionInterface
{
    public function addFilter(Collection $collection, $field, $condition = null)
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */

        $x = $field;
    }
}