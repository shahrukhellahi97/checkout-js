<?php
namespace BA\Basys\Webservices\Http\Converter;

use BA\Basys\Webservices\Http\ConverterInterface;

class ObjectToArrayConverter implements ConverterInterface
{
    public function convert($response)
    {
        $response = (array) $response;
        
        foreach ($response as $key => $value) {
            if (is_object($value)) {
                $response[$key] = $this->convert($value);
            }
        }

        return $response;
    }
}