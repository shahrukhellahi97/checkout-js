<?php
namespace BA\Vertex\Webservices\Response\Async;

use BA\Basys\Webservices\Response\HandlerInterface;
use BA\Vertex\Model\ResourceModel\RateFactory;

class CalculateTaxVHandler implements HandlerInterface
{
    /**
     * @var \BA\Vertex\Model\ResourceModel\RateFactory
     */
    protected $rateFactory;

    public function __construct(
        RateFactory $rateFactory
    ) {
        $this->rateFactory = $rateFactory;
    }

    public function handle($response, array $additional = [])
    {
        /** @var \BA\Vertex\Model\ResourceModel\Rate $rateResource */
        $rateResource = $this->rateFactory->create();

        /** @var \BA\Vertex\Model\Rate $rate */
        foreach ($response as $rate) {
            $rate->setDestinationCountryId($additional['destination_id']);
        }

        $rateResource->updateRates($response);
    }
}
