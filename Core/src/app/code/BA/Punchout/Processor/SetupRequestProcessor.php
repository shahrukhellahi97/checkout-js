<?php
namespace BA\Punchout\Processor;

use BA\Punchout\Api\Data\DTOs\Request\SetupRequestInterface;
use BA\Punchout\Api\Data\DTOs\ResponseInterface;
use BA\Punchout\Api\Data\DTOs\ResponseInterfaceFactory;
use BA\Punchout\Api\Processor\SetupRequestProcessorInterface;
use BA\Punchout\Api\RequestRepositoryInterface;
use BA\Punchout\Model\Request;
use BA\Punchout\Model\RequestFactory;
use Magento\Framework\Exception\AlreadyExistsException;

class SetupRequestProcessor implements SetupRequestProcessorInterface
{
    /**
     * @var \BA\Punchout\Api\RequestRepositoryInterface
     */
    protected $requestRepository;

    /**
     * @var \BA\Punchout\Model\RequestFactory
     */
    protected $requestFactory;

    /**
     * @var \BA\Punchout\Api\Data\DTOs\ResponseInterfaceFactory
     */
    protected $responseFactory;

    /**
     * @var \Magento\Framework\Webapi\Rest\Response
     */
    protected $restResponse;

    /**
     * @var \Magento\Framework\Webapi\Rest\Request
     */
    protected $restRequest;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;

    public function __construct(
        RequestRepositoryInterface $requestRepository,
        RequestFactory $requestFactory,
        ResponseInterfaceFactory $responseFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Webapi\Rest\Request $restRequest,
        \Magento\Framework\Webapi\Rest\Response $restResponse,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
    ) {
        $this->date = $date;
        $this->storeManager = $storeManager;
        $this->requestRepository = $requestRepository;
        $this->requestFactory = $requestFactory;
        $this->responseFactory = $responseFactory;
        $this->restResponse = $restResponse;
        $this->restRequest = $restRequest;
        $this->jsonFactory = $jsonFactory;
    }

    public function process(SetupRequestInterface $request)
    {
        $request->setTimestamp($this->date->gmtTimestamp());

        /** @var Request */
        $model = $this->requestFactory->create();

        $model->createWithSetupRequest($request);
        $model->setProcurementApplication($this->restRequest->getHeader('procurement-source', null));
        $model->setPayloadId($model->generateToken());

        try {
            $this->restResponse->setStatusCode(200);
            
            /** @var \Magento\Store\Model\Store $store */
            $store = $this->storeManager->getStore();
            
            $model->setStoreId($store->getStoreId());
            $model->getResource()->save($model);

            return $this->createResponse(
                200,
                'Success',
                $store->getUrl('punchout/login', [
                    '_secure' => true,
                    '_query' => [
                        't' => $model->getToken(),
                    ]
                ])
            );
        } catch (AlreadyExistsException $e) {
            $this->restResponse->setStatusCode(400);
            return $this->createResponse(400, 'Payload ID already exists', null);
        } catch (\Exception $e) {
            $this->restResponse->setStatusCode(400);
            return $this->createResponse(400, $e->getMessage(), '/');
        }
    }

    private function createResponse($code, $message, $url)
    {
        /** @var ResponseInterface */
        $res = $this->responseFactory->create();

        $res->getStatus()->setCode($code);
        $res->getStatus()->setText($message);
        $res->getStartPage()->setUrl($url);

        return $res;
    }
}
