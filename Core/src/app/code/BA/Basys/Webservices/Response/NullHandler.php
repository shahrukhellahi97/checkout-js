<?php
namespace BA\Basys\Webservices\Response;

class NullHandler implements HandlerInterface
{
    public function handle($response, array $additional = [])
    {
        return null;
    }
}