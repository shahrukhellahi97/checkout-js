define([
    'Magento_Vault/js/view/payment/method-renderer/vault',
    'Magento_Ui/js/model/messageList',
    'Magento_Checkout/js/model/full-screen-loader',
    'jquery'
], function (VaultComponent, messageList, fullScreenLoader, $) {
    'use strict';

    return VaultComponent.extend({
        defaults: {
            template: 'Adflex_Payments/payment/vault_form'
        },
        initialize: function() {
            this._super();
        },
        /**
         * Get last 4 digits of card
         * @returns {String}
         */
        getMaskedCard: function () {
            return this.details.maskedCC;
        },

        /**
         * Get expiration date
         * @returns {String}
         */
        getExpirationDate: function () {
            return this.details.expirationDate;
        },

        /**
         * Returns public hash.
         * @returns {*}
         */
        getToken: function () {
            return this.publicHash;
        },

        /**
         * Checks to see if button is meant to be active or not.
         * @returns {any}
         */
        isButtonActive: function() {
            return this.isButtonActive;
        },

        /**
         * @returns {*}
         */
        getData: function () {
            var data = {
                method: this.getCode()
            };

            data['additional_data'] = {};
            data['additional_data']['public_hash'] = this.getToken();
            data['additional_data']['cvc'] = $('#' + this.getId() + '_cvc').attr('value');
            return data;
        },

        /**
         * Get card type
         * @returns {String}
         */
        getCardType: function () {
            let type = null;
            switch(this.details.type) {
                case 'MasterCard':
                case 'Debit MasterCard':
                    type = 'MC';
                    break;
                case 'Visa Purchase':
                case 'Visa':
                case 'VISA':
                case 'Visa Electron':
                    type = 'VI';
                    break;
                case 'American Express':
                case 'Amex':
                case 'AMEX':
                    type = 'AE';
                    break;
                case 'Solo':
                    type = 'SO';
                    break;
                case 'Switch/Maestro':
                    type = 'SM';
                    break;
                case 'Maestro International':
                case 'International Maestro':
                    type = 'MI';
                    break;
                case 'Maestro Domestic':
                    type = 'MD';
                    break;
            }
            return type;
        },
    });
});
