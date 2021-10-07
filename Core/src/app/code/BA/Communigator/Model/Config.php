<?php
namespace BA\Communigator\Model;

use BA\Communigator\Api\Data\ConfigInterface;
use Magento\Framework\DataObject;

class Config extends DataObject implements ConfigInterface
{

    public function getClientId()
    {
        return 'BrandAddition';
    }

    public function getClientSecret()
    {
        return 'Iy57wSi^$Q&^OMHS';
    }

    public function getEncodedCredentials()
    {
        return base64_encode(implode(':', [
            $this->getClientId(),
            $this->getClientSecret()
        ]));
    }

    public function getUsername()
    {
        return 'websupport@brandaddition.com';
    }

    public function getPassword()
    {
        return 'Gace9Fr3T4H!';
    }
    
}