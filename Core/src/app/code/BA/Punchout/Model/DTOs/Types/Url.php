<?php
namespace BA\Punchout\Model\DTOs\Types;

use BA\Punchout\Api\Data\DTOs\Types\UrlInterface;

class Url extends AbstractType implements UrlInterface
{
    public function getUrl() 
    {
        if ($this->hasData(UrlInterface::URL)) {
            return $this->getData(UrlInterface::URL);
        }

        return '';
    }

    public function setUrl($url)
    {
        return $this->setData(UrlInterface::URL, $url);
    }    
}