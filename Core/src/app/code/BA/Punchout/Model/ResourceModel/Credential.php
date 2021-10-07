<?php
namespace BA\Punchout\Model\ResourceModel;

use BA\Punchout\Api\Data\CredentialInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Credential extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(CredentialInterface::SCHEMA, CredentialInterface::CREDENTIAL_ID);
    }
}