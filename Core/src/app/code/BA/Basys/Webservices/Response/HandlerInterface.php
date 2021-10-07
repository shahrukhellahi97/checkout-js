<?php
namespace BA\Basys\Webservices\Response;

interface HandlerInterface
{
    /**
     * Handle response from request
     *
     * @param mixed $response
     * @param array $additional
     * @return mixed
     */
    public function handle($response, array $additional = []);
}
