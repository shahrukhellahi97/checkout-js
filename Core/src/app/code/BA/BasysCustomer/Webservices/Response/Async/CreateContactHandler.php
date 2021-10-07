<?php
namespace BA\BasysCustomer\Webservices\Response\Async;

use BA\Basys\Webservices\Response\HandlerInterface;
use BA\BasysCustomer\Model\ResourceModel\CustomerContactFactory;
use Psr\Log\LoggerInterface;

class CreateContactHandler implements HandlerInterface
{
    /**
     * @var \BA\BasysCustomer\Model\ResourceModel\CustomerContactFactory
     */
    protected $customerContactFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(
        CustomerContactFactory $customerContactFactory,
        LoggerInterface $logger
    ) {
        $this->customerContactFactory = $customerContactFactory;
        $this->logger = $logger;
    }

    public function handle($response, array $additional = [])
    {
        /** @var \BA\BasysCustomer\Model\ResourceModel\CustomerContact $resource */
        $resource = $this->customerContactFactory->create();

        try {
            $resource->save($response);
        } catch (\Exception $e) {
            $this->logger->error('unable to save contact', ['e' => $e->getMessage()]);
        }
    }
}
