define(['jquery'], function ($) {
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