<?php

namespace Adflex\Payments\Plugins;

use Magento\Vault\Model\Method\Vault;

/**
 * Class InitVault
 *
 * @package Adflex\Payments\Plugins
 */
class InitVault
{
    /**
     * @param \Magento\Vault\Model\Method\Vault $subject
     * @param $result
     * @return bool
     * Vault payments are not initialised as they are currently unimplemented in the core module, and unnecessary
     * as we have the token information already.
     */
    public function afterIsInitializeNeeded(
        Vault $subject,
        $result
    ) {
        $code = $subject->getCode();
        if ($code == 'adflex_vault') {
            return false;
        }
        return $result;
    }
}
