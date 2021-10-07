<?php
namespace BA\BasysOrders\Webservices\Request;

use BA\Basys\Webservices\Request\RequestBuilderInterface;

class GetPaymentTypes implements RequestBuilderInterface
{
    public function build(array $arguments)
    {
        return [
            'DivisionPaymentTypes' => [
                'divisionId' => $arguments['division_id']
            ]
        ];
    }
}