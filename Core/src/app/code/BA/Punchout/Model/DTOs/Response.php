<?php
namespace BA\Punchout\Model\DTOs;

use BA\Punchout\Api\Data\DTOs\ResponseInterface;
use BA\Punchout\Api\Data\DTOs\Types\StatusInterface;
use BA\Punchout\Api\Data\DTOs\Types\StatusInterfaceFactory;
use BA\Punchout\Api\Data\DTOs\Types\UrlInterface;
use BA\Punchout\Model\DTOs\Types\AbstractType;

class Response extends AbstractType implements ResponseInterface
{
    /**
     * @var \BA\Punchout\Api\Data\DTOs\Types\StatusInterfaceFactory
     */
    protected $statusFactory;

    /**
     * @var \BA\Punchout\Api\Data\DTOs\Types\UrlInterfaceFactory
     */
    protected $urlFactory;

    public function __construct(
        \BA\Punchout\Api\Data\DTOs\Types\StatusInterfaceFactory $statusFactory,
        \BA\Punchout\Api\Data\DTOs\Types\UrlInterfaceFactory $urlFactory)
    {
        $this->setStartPage($urlFactory->create());
        $this->setStatus($statusFactory->create());

        $this->getStatus()->setStatus(200, 'OK');
        $this->getStartPage()->setUrl('/');
    }

    public function getStatus()
    {
        return $this->getData(ResponseInterface::STATUS);
    }

    public function setStatus(StatusInterface $status)
    {
        return $this->setData(ResponseInterface::STATUS, $status);
    }

    public function getStartPage()
    {
        return $this->getData(ResponseInterface::START_PAGE);
    }

    public function setStartPage(UrlInterface $url)
    {
        return $this->setData(ResponseInterface::START_PAGE, $url);
    }
}