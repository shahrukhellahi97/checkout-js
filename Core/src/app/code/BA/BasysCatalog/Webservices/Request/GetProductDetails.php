<?php
namespace BA\BasysCatalog\Webservices\Request;

use BA\Basys\Webservices\Request\RequestBuilderInterface;

class GetProductDetails implements RequestBuilderInterface
{
    public function build(array $arguments)
    {
        return [
            'GetProductDetails' => [
                'catalogueID'  => $arguments['catalog_id'],
                'divisionID' => $arguments['division_id'],
            ]
        ];
    }
}