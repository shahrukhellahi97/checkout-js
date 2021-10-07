define(['jquery'], function($){
    "use strict";
        return function checkDoubleOptin()
        {
           $('div.confirm-again').hide();
           $("input[name$='send_email_confirm']").click(function() {
            var clickedValue = $(this).val();            
            if(clickedValue == 'yes') {
                $('div.confirm-again').slideDown(200);

            }
            else {
                $('div.confirm-again').slideUp('fast');
            } 
        }); 

    }
 });