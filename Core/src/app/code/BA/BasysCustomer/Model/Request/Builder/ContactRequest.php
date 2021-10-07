<?php
namespace BA\BasysCustomer\Model\Request\Builder;

use BA\BasysCustomer\Model\Request\Builder\CustomerRequestInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Psr\Log\LoggerInterface;

class ContactRequest extends AbstractRequest implements CustomerRequestInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    protected $builders;

    public function __construct(
        LoggerInterface $logger,
        $builders = []
    ) {
        parent::__construct($builders);
        $this->logger = $logger;
        $this->builders = $builders;
    }

    public function build(CustomerInterface $customer): array
    {
        try {
            $result = [];
            /** @var \BA\BasysCustomer\Model\Request\CustomerRequestInterface $builder */
            foreach ($this->builders as $builder) {
                $result = $this->merge($result, $builder->build($customer));
            }
            return $result;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
