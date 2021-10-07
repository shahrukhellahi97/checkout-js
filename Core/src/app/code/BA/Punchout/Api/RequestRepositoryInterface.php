<?php
namespace BA\Punchout\Api;

use BA\Punchout\Api\Data\RequestInterface;

/**
 * Entry point for POSR on Web API
 * 
 * @package BA\Punchout\Api
 */
interface RequestRepositoryInterface
{
    /**
     * @param \BA\Punchout\Api\Data\RequestInterface $request 
     * @return bool 
     */
    public function save(RequestInterface $request): bool;

    /**
     * @param int $id
     * @return \BA\Punchout\Api\Data\RequestInterface 
     */
    public function loadById($id): RequestInterface;

    /**
     * @param string $token 
     * @return \BA\Punchout\Api\Data\RequestInterface 
     */
    public function loadByToken($token): RequestInterface;
}