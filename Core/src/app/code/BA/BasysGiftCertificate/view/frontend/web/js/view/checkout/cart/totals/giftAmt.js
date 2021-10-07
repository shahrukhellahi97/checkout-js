define(
    [
        'jquery',
        'BA_BasysGiftCertificate/js/view/checkout/summary/giftAmt',
        'mage/url',
        'Magento_Checkout/js/model/cart/cache',
        'Magento_Checkout/js/model/cart/totals-processor/default',
        'Magento_Checkout/js/model/quote'
    ],
    function ($,Component,url,cartCache, totalsProcessor, quote) {
        'use strict';

        return Component.extend({

            /**
             * @override
             */
            isDisplayed: function () {
                var displayDiv = true;
                $.ajax({
                    url: url.build('giftcardremove/index/remove'),
                    showLoader: true,
                    cache: false,
                    success: function(response){      
                       if(response) {
                        $('.giftAmt').css('display','block');
                       }
                       else {
                        $('.giftAmt').css('display','none');
                       }
                    }
               }); 
            },
            removeGiftCard: function() {
                $('.giftAmt').fadeOut(1500);
                var removeGiftCard = 1;
                $.ajax({
                    url: url.build('giftcardremove/index/remove'),
                    type: "POST",
                    data: { removeGiftCard:removeGiftCard },
                    showLoader: true,
                    cache: false,
                    success: function(response){              
                        cartCache.clear('cartVersion');
                        totalsProcessor.estimateTotals(quote.shippingAddress());   
                    }
               }); 
            }          
        });
      
    }
);