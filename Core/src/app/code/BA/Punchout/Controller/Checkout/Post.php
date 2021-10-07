<?php
namespace BA\Punchout\Controller\Checkout;

class Post extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Controller\ResultFactory
     */
    protected $resultFactory;

    /**
     * @var \Magento\Framework\HTTP\ClientInterface
     */
    protected $httpClient;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\ResultFactory $resultFactory,
        \Magento\Framework\HTTP\ClientInterface $httpClient
    ) {
        $this->resultFactory = $resultFactory;
        $this->httpClient = $httpClient;

        parent::__construct($context);
    }

    public function execute()
    {
        /** @var \Magento\Framework\Controller\ResultInterface $response */
        $response = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON);

        try {
            $rr = $this->makeRequest();
        } catch (\Exception $e){
            $rr = $e->getMessage();
        }

        $response->setData([
            'response' => $rr
        ]);

        return $response;
    }

    /**
     * Obviously just for testing
     * 
     * @return string[] 
     */
    private function makeRequest()
    {
        /** @var \Magento\Framework\App\RequestInterface $request */
        $request  = $this->getRequest();

        $this->httpClient->setTimeout(10);
        $this->httpClient->setHeaders([
            'procurement-source' => $request->getParam('post_headers'),
            'content-type' => 'application/json'
        ]);

        $this->httpClient->post($request->getParam('post_url'), $request->getParam('post_payload'));

        return $this->httpClient->getBody();
    }
}