<?php
namespace BA\Punchout\Model\ResourceModel;

use BA\Punchout\Api\Data\CredentialInterface;
use BA\Punchout\Api\Data\RequestInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Request extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(RequestInterface::SCHEMA, RequestInterface::REQUEST_ID);
    }

    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object instanceof \BA\Punchout\Model\Request) {
            if (!$object->hasData(RequestInterface::TOKEN)){
                $object->setData(RequestInterface::TOKEN, $object->generateToken());
            }
        }
    }

    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        // Save credentials with new ID
        if ($object instanceof \BA\Punchout\Model\Request) {
            /** @var \BA\Punchout\Model\Credential $credential */
            foreach ($object->getCredentials() as $type => $credential) {
                $credential->setTypeId($type);
                $credential->setRequestId($object->getId());

                $credential->getResource()->save($credential);
            }
        }
    }

    public function getCredential(int $credentialType)
    {
    }
}