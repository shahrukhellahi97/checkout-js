/**
 * Copyright @ 2020 Adflex Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push({
            type: 'adflex',
            component: 'Adflex_Payments/js/view/payment/method-renderer/adflex-payments'
        });

        /** Add view logic here if needed */
        return Component.extend({});
    }
);
