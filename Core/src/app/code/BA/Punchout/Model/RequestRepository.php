<?php
namespace BA\Punchout\Model;

use BA\Punchout\Api\Data\DTOs\Request\SetupRequestInterface;
use BA\Punchout\Api\Data\RequestInterface;
use BA\Punchout\Api\Processor\SetupRequestProcessorInterface;
use BA\Punchout\Api\RequestRepositoryInterface;

class RequestRepository implements RequestRepositoryInterface
{
    /**
     * @var \BA\Punchout\Model\RequestFactory
     */
    protected $requestFactory;

    public function __construct(\BA\Punchout\Model\RequestFactory $requestFactory)
    {
        $this->requestFactory = $requestFactory;
    }

    public function save(RequestInterface $request): bool
    {
        // $object = $this->requestFactory->create();
        // $object->load() 
        return true;
    }

    public function loadById($id): RequestInterface
    {
        /** @var \BA\Punchout\Model\Request $request */
        $request = $this->requestFactory->create();
        $request->getResource()->load($request, $id);

        return $request;
    }

    public function loadByToken($token): RequestInterface
    {
        /** @var \BA\Punchout\Model\Request $request */
        $request = $this->requestFactory->create();
        $request->getResource()->load($request, $token, RequestInterface::TOKEN);

        return $request;
    }
}