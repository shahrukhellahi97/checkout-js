/**
 * Copyright @ 2020 Adflex Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
define(
    [
        'Magento_Checkout/js/view/payment/default',
        'Magento_Payment/js/view/payment/cc-form',
        'Magento_Checkout/js/action/place-order',
        'Magento_Checkout/js/model/full-screen-loader',
        'Magento_Checkout/js/model/payment/additional-validators',
        'Magento_Payment/js/model/credit-card-validation/validator',
        'mage/url',
        'jquery',
        'Magento_Vault/js/view/payment/vault-enabler',
        'Magento_Customer/js/model/customer',
        'arrive',
        'Magento_Checkout/js/action/redirect-on-success',
        'Magento_Checkout/js/model/quote'
    ],
    function (
        Component,
        ccForm,
        placeOrder,
        fullScreenLoader,
        additionalValidators,
        ccValidator,
        url,
        $,
        vaultEnabler,
        customer,
        arrive,
        redirectOnSuccessAction,
        quote
    ) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Adflex_Payments/payment/form'
            },
            redirectAfterPlaceOrder: true,

            /**
             * Initialises Adflex payments, and vault integration.
             */
            initialize: function() {
                // Render form etc.
                let self = this;
                self._super();
                this.vaultEnabler = new vaultEnabler();
                this.vaultEnabler.setPaymentCode(this.getVaultCode());
                // Determine which Adflex library we should load, and instantiate it.
                if(window.checkoutConfig.adflex.environment === 'test') {
                    require(['adflex-test'], function (adflex) {
                        self.createSession();
                    });
                } else {
                    require(['adflex'], function (adflex) {
                        self.createSession();
                    });
                }

                return self;
            },
            /**
             * @returns {Boolean}
             */
            isVaultEnabled: function () {
                return this.vaultEnabler.isVaultEnabled();
            },
            /**
             * Returns vault code.
             *
             * @returns {String}
             */
            getVaultCode: function () {
                return window.checkoutConfig.payment[this.getCode()].ccVaultCode;
            },
            /**
             * @returns {{additional_data: {}, method: *}}
             */
            getData: function() {
                // Compile a custom data object
                let data = {
                    method: this.getCode(),
                    additional_data: {}
                };
                // Get all input fields during quote process
                let fields = 'payment_form_' + this.getCode();
                fields = document.getElementById(fields);
                if (fields) {
                    fields = fields.getElementsByTagName('input');
                    [].slice.call(fields).forEach((i) => {
                        var name = i.name;
                        data.additional_data[name] = i.value;
                    });
                }

                // If the session executes with the radio tab closed, it has no height, we need to set something.
                // Runs everytime the radio button is clicked so ideal location.
                let adflexInlineIframe = $('#adflex-inline').find('iframe');
                if(adflexInlineIframe.length > 0) {
                    let height = adflexInlineIframe.css('height');
                    if(height === '0px') {
                        adflexInlineIframe.css('min-height', '265px');
                    }
                }

                this.vaultEnabler.visitAdditionalData(data);
                return data;
            },
            /**
             * Returns payment code.
             * @returns {string}
             */
            getCode: function() {
                return 'adflex';
            },
            /**
             * Enables payment method.
             * @returns {boolean}
             */
            isActive: function() {
                return true;
            },
            /**
             * Creates a session with Adflex
             */
            createSession: function() {
                let form_key = $('#co-payment-form input[name="form_key"]').attr('value');
                let result = false;
                let sessionParent = this;
                fullScreenLoader.startLoader();
                // We have our own pay button and will trigger place order via Adflex.
                $('.adflex-wrapper .action.primary.checkout').css('display', 'none');
                if (form_key === '' || form_key === null) {
                    form_key = $('input[name="form_key"]').attr('value');
                }
                $.ajax({
                    type: "POST",
                    url: url.build('adflex/index/session'),
                    data: {form_key: form_key},
                })
                .done(function(response) {
                    // Instantiate adflex JS widget.
                    let displayType = window.checkoutConfig.adflex.display_type;
                    if(response['error_message'] !== undefined) {
                        if(response['error_message'] === 'form key is invalid'
                            || response['error_message'] === 'form key is missing') {
                            // When you login while in-checkout, occasionally the form key is invalid.
                            if(customer.isLoggedIn()) {
                                location.reload();
                            } else {
                                if(window.checkoutConfig.adflex.environment === 'test') {
                                    alert('If you are using a third party checkout, it needs to implement the form_key' +
                                        'otherwise, Magento is unable to verify whether or not the transaction is an XSS attack.' +
                                        'Please contact Adflex support or your developer.')
                                }
                                alert(response['error_message']);
                            }
                        }
                    } else {
                        // Execute AHPP call.
                        sessionParent.executeAHPP(displayType, response);
                        // Initialise oncompletion handler.
                        AdflexAHPP.OnCompletion = function(subCode, token, statusMessage, additionalInfo) {
                            // Pass token to be dealt with later by Magento if successful.
                            if(sessionParent.handleStatus(subCode) === 50001) {
                                fullScreenLoader.startLoader();
                                $("input[name='adflex_token']").attr('value', token);
                                $("input[name='adflex_sub_code']").attr('value', subCode);
                                $("input[name='adflex_status_msg']").attr('value', statusMessage);
                                $("input[name='adflex_additional_info']").attr('value', additionalInfo);
                                // Trigger place order M2 function, has its own loader function.
                                sessionParent.placeOrder();
                                // Just in case a loader is running.
                                fullScreenLoader.stopLoader();
                            }
                        }
                        result = true;
                    }
                })
                .fail(function() {
                    fullScreenLoader.stopLoader();
                    if(window.checkoutConfig.adflex.environment === 'test') {
                        alert('A serious error occurred, this could be many things. Have you installed the JWT (lcobucci/jwt)' +
                            ' composer library? Have you filled in your Adflex credentials? Failures here are typically' +
                            ' authentication related, please revisit the installation steps or contact Adflex support.');
                    } else {
                        alert('A serious error occurred, please contact the merchant for more information.');
                    }
                });

                return result !== false;
            },
            /**
             * Place order function, add failure handling conditions for Adflex JS widget.
             */
            placeOrder: function (data, event) {
                let self = this;

                if (event) {
                    event.preventDefault();
                }

                if (this.validate() &&
                    additionalValidators.validate() &&
                    this.isPlaceOrderActionAllowed() === true
                ) {
                    this.isPlaceOrderActionAllowed(false);

                    this.getPlaceOrderDeferredObject()
                        .done(
                            function () {
                                self.afterPlaceOrder();

                                if (self.redirectAfterPlaceOrder) {
                                    redirectOnSuccessAction.execute();
                                }
                            }
                        ).fail(
                            function () {
                                // If fails creating the order, then destroy current session and recreate.
                                AdflexAHPP.destroy();
                                if(window.checkoutConfig.adflex.display_type === 'inline') {
                                    $('#adflex-inline').empty();
                                } else {
                                    $('#adflex-lightbox').html('<button id="pp_lightbox" type="button">Pay Now</button>');
                                }

                                self.createSession();
                            }
                         )
                        .always(
                        function () {
                            self.isPlaceOrderActionAllowed(true);
                        }
                    );

                    return true;
                }

                return false;
            },
            // Handles status if it's something that prohibits or inhibits the transaction.
            handleStatus: function (subCode) {
                switch (subCode) {
                    case 50001:
                        // All okay no need to throw an alert.
                        break;
                    case 51404:
                        alert('Unfortunately, the transaction has timed out, please refresh the page and try again.');
                        break;
                    case 53306:
                        alert('Account verification declined.');
                        break;
                    case 53009:
                        alert('An unknown failure occurred, please refresh the page and try again.');
                        break;
                    case 53302:
                        alert('Account verification failed. Please contact the merchant.');
                        break;
                    case 53303:
                        alert('Merchant selector engine has failed. Please contact the merchant.');
                        break;
                    case 53304:
                        alert('Transaction rule data is missing, please ensure your details are correctly entered.');
                        break;
                    case 53305:
                        alert('Transaction rule data failed, please ensure your details are correctly entered.');
                        break;
                    case 51615:
                        alert('Unfortunately, your card is not supported by this merchant. Please try another card or contact the merchant for further details.');
                        break;
                    default:
                        alert('An error occurred, please contact the merchant.');
                        break;
                }
                return subCode;
            },
            /**
             * Executes the AHPP library with appropriate elements.
             * @param displayType
             * @param response
             */
            executeAHPP: function (displayType, response) {
                // Hide elements we are not going to be using.
                let adflexInline = $('#adflex-inline');
                let adflexLightbox = $('#adflex-lightbox');
                if(displayType === 'inline') {
                    adflexLightbox.css('display', 'none');
                } else {
                    adflexInline.css('display', 'none');
                }
                // Initialise Adflex form
                AdflexAHPP.init({
                    sessionID: response['session_id'],
                    lang: window.checkoutConfig.adflex.locale,
                    targetElement: (displayType === 'inline') ? 'adflex-inline' : 'pp_lightbox',
                    type: displayType,
                    borderRadius: "true",
                    logoUrl: window.checkoutConfig.adflex.store_logo
                });
                fullScreenLoader.stopLoader();
            },
        });
    }
);
