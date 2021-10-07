<?php
namespace BA\Basys\Webservices\Http;

use Magento\Framework\DataObject;

abstract class AbstractTransfer extends DataObject implements TransferInterface
{
    public function getMethod(): string
    {
        return $this->getData('method');
    }

    public function setMethod($method)
    {
        return $this->setData('method', $method);
    }

    public function getUri(): string
    {
        return $this->getData('uri');
    }

    public function setUri($uri)
    {
        return $this->setData('uri', $uri);
    }

    public function getBody()
    {
        return $this->getData('body');
    }

    public function setBody($body)
    {
        return $this->setData('body', $body);
    }

    public function getConfig(): ?array
    {
        return $this->getData('config');
    }

    public function setConfig(array $config)
    {
        return $this->setData('config', $config);
    }
}