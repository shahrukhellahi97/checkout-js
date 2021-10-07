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
            type: 'ba_consolidated_invoice',
            component: 'BA_BasysOrders/js/view/payment/method-renderer/consolidated-invoice'
        });

        /** Add view logic here if needed */
        return Component.extend({});
    }
);
