<?php
namespace BA\BasysGiftCertificate\Api;

interface GiftManagementInterface
{
    /**
     * Check Balance
     * @param mixed $certificateReference
     * @return mixed
     */
    public function checkBalance($certificateReference);
}
