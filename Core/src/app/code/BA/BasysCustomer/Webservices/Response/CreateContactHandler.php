<?php
namespace BA\BasysCustomer\Webservices\Response;

use BA\Basys\Webservices\Response\HandlerInterface;
use BA\BasysCustomer\Model\CustomerContactFactory;
use Psr\Log\LoggerInterface;

class CreateContactHandler implements HandlerInterface
{
    /**
     * @var \BA\BasysCustomer\Model\CustomerContactFactory
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
        /** @var \BA\BasysCustomer\Model\CustomerContact $model */
        $model = $this->customerContactFactory->create();

        $this->logger->debug('xxxxx', ['x' => $response]);
        
        return $model->setData($additional)
            ->setContactId($response['CreateContactResult']);
    }
}
