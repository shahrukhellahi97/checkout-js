<?php
namespace BA\Punchout\Model\ResourceModel\Request;

use BA\Punchout\Api\Data\RequestInterface;
use BA\Punchout\Model\Request;
use BA\Punchout\Model\ResourceModel\Request as ResourceModelRequest;
use Magento\Catalog\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = RequestInterface::REQUEST_ID;

    protected function _construct()
    {
        $this->_init(Request::class, ResourceModelRequest::class);
    }
}