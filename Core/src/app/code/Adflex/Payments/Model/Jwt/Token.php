<?php
/**
 * Copyright @ 2020 Adflex Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Adflex\Payments\Model\Jwt;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;

/**
 * Class Token
 *
 * @package Adflex\Payments\Model\Jwt
 */
class Token
{
    /**
     * @param $url
     * @param $secretKey
     * @return string
     * Generates Jwt token for Adflex API call.
     */
    public function generateToken($url, $secretKey)
    {
        $token = (new Builder())
        ->issuedBy('Adflex')
        ->permittedFor($url)
        ->issuedAt(new \DateTimeImmutable('@' . time()))
        ->identifiedBy(self::generateGuid())
        ->getToken(new Sha256(), new Key($secretKey));

        return $token->__toString();
    }

    /**
     * @return string
     * Generates a GUID.
     */
    public function generateGuid()
    {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }
        return sprintf(
            '%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(16384, 20479),
            mt_rand(32768, 49151),
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535)
        );
    }
}
