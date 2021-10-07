<?php
namespace BA\BasysCatalog\Model\ResourceModel\BasysProduct;

use BA\BasysCatalog\Model\BasysProduct;
use BA\BasysCatalog\Model\ResourceModel\BasysProduct as ResourceModelBasysProduct;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(BasysProduct::class, ResourceModelBasysProduct::class);
    }

    public function getProductsForCatalog($catalogId)
    {
        return $this->getSelect()->where('catalog_id = ?', (int) $catalogId);
    }
}