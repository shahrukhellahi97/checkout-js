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
            type: 'ba_punchout',
            component: 'BA_Punchout/js/view/payment/method-renderer/punchout'
        });

        /** Add view logic here if needed */
        return Component.extend({});
    }
);
