<?php
/**
 * Copyright @ 2020 Adflex Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Adflex\Payments\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Adflex\Payments\Gateway\Http\Client\AdflexClient;

/**
 * Class ConfigProvider
 */
final class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'adflex';
    const CC_VAULT_CODE = 'adflex_vault';

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig()
    {
        return [];
    }
}
