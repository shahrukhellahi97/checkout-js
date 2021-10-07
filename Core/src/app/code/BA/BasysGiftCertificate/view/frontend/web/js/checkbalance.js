define(['jquery',
'Magento_Checkout/js/model/cart/cache',
'Magento_Checkout/js/model/cart/totals-processor/default',
'Magento_Checkout/js/model/quote'
], function ($, cartCache, totalsProcessor, quote) {
    "use strict";
    return function(config) {             
         $( "#check-balance" ).click(function(e) {
            e.preventDefault();
            var certificateRef = jQuery('#certificate-reference-id').val();
            if($.trim(certificateRef).length > 0) {
                $.ajax({
                    url: config.ajaxUrlValue,
                    type: "POST",
                    data: { certificateRef:certificateRef },
                    showLoader: true,
                    cache: false,
                    success: function(response){
                        var str = '';
                        console.log(response);
                        $.each(response, function (index, elem) {
                            str += '<span class="gift-lbl-wrap gift-'+index+'">';
                            str += '<span class="gift-label">'+index+'</span> : <span class="gift-val">'+elem+'</span>';
                            str += '</span>';
                        });
                        $('#display-message').empty().append(str);        
                    }
                });
            } else {
                var errorMsg = 'Please enter a valid certificate reference';  
                $("#display-message").html("<span class='gift-lbl-wrap gift-Error'>"+errorMsg+'</span>');
         
            }       
        });
        $('.basys-gift-card').click(function(e) {
            $('.basys-gift-card').toggleClass('active');
            $('.gift-card-wrap').slideToggle();
        });
       
        $( "#apply-gift-card" ).click(function(e) {
            e.preventDefault();
            var certificateRef = jQuery('#certificate-reference-id').val();
            if($.trim(certificateRef).length > 0) {
                $.ajax({
                    url: config.ajaxUrlApplyButton,
                    type: "POST",
                    data: { certificateRef:certificateRef },
                    showLoader: true,
                    cache: false,
                    success: function(response){
                        var str = '';
                        $.each(response, function (index, elem) {
                            str += '<span class="gift-lbl-wrap gift-'+index+'">';
                            str += '<span class="gift-label">'+index+'</span> : <span class="gift-val">'+elem+'</span>';
                            str += '</span>';
                            cartCache.clear('cartVersion');
                            totalsProcessor.estimateTotals(quote.shippingAddress());   
                        });
                        $('.giftAmt').fadeIn(1500);
                        $('#display-message').empty().append(str);    
                          
                    }
                });
            } else {
                var errorMsg = 'Please enter a valid certificate reference';  
                $("#display-message").html("<span class='gift-lbl-wrap gift-Error'>"+errorMsg+'</span>');
            }       
        });
    }
});