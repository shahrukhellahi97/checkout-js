<?php
namespace BA\Punchout\Model\ResourceModel\Credential;

use BA\Punchout\Api\Data\CredentialInterface;
use BA\Punchout\Model\Credential;
use BA\Punchout\Model\ResourceModel\Credential as ResourceModelCredential;
use Magento\Catalog\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = CredentialInterface::CREDENTIAL_ID;

    protected function _construct()
    {
        $this->_init(Credential::class, ResourceModelCredential::class);
    }
}