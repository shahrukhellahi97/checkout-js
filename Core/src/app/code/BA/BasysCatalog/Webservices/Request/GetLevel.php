<?php
namespace BA\BasysCatalog\Webservices\Request;

use BA\Basys\Webservices\Request\RequestBuilderInterface;

class GetLevel implements RequestBuilderInterface
{
    public function build(array $arguments)
    {
        return [
            'LevelEnquiry' => [
                'ProductID'  => $arguments['product_id'],
                'BaseColour' => $arguments['base_colour'],
                'TrimColour' => $arguments['trim_colour'],
            ]
        ];
    }
}