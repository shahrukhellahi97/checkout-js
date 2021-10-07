/**
 * Copyright @ 2020 Adflex Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
define(
    [
        'Magento_Checkout/js/view/payment/default',
        'Magento_Checkout/js/action/place-order',
    ], 
    function (
        Component,
        placeOrder
    ) {
        'use strict';

        return Component.extend({
            redirectAfterPlaceOrder: true,
            defaults: {
                template: 'BA_Punchout/payment/punchout'
            },
            getCode: function(){
                return 'ba_punchout';
            },
            isPlaceOrderDisabled: function(){
                return false;
            },
        });
    }
);
