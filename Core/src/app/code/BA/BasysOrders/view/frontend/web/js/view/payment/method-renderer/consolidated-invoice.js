/**
 * Copyright @ 2020 Adflex Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
define(
    [
        'Magento_Checkout/js/view/payment/default',
        'Magento_Checkout/js/action/place-order',
        'ko',
    ], 
    function (
        Component,
        placeOrder,
        ko
    ) {
        'use strict';

        return Component.extend({
            redirectAfterPlaceOrder: true,
            defaults: {
                template: 'BA_BasysOrders/payment/consolidated-invoice',
                types: window.checkoutConfig.payment.ba_consolidated_invoice.types,
                selectedItem: ko.observable(),
                selectedItemUdfs: ko.observableArray([]),
                hasFields: ko.observable(false)
            },
            initialize: function() {
                let self = this;
                self._super();

                self.selectedItem.subscribe(function(value) {
                    let type = self.types.filter(x => {
                        return x.value == value;
                    });

                    if (type.length >= 1) {
                        self.setActiveUDFs(type[0]);
                    } else {
                        self.clearActiveUDFs();
                    }
                });

                if (self.types.length == 1) {
                    self.selectedItem(self.types[0].value);
                }

                return self;
            },
            clearActiveUDFs: function() {
                this.hasFields(false);
                this.selectedItemUdfs([]);
            },
            setActiveUDFs: function(paymentType) {
                this.clearActiveUDFs();
                this.selectedItemUdfs(paymentType.udfs);
                this.hasFields(paymentType.udfs.length >= 1);
            },
            getData: function () {
                var data = {
                    method: this.getCode(),
                    additional_data: {
                        type: this.selectedItem(),
                        udfs: JSON.stringify(this.selectedItemUdfs())
                    }
                };

                return data;
            },
            getCode: function(){
                return 'ba_consolidated_invoice';
            },
            hasMultiplePaymentTypes: function() {
                return this.types.length > 1;
            },
            isPicklist: function(udf) {
                return Array.isArray(udf.value);
            }
        });
    }
);
