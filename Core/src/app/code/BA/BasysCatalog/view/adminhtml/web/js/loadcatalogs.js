define(['jquery'], function ($) {
    "use strict";
    return function(config) {
        $("#basys_store_store_division_id").on('change', function() {
            var divisionId = $("#basys_store_store_division_id").val();

            $.ajax({
                url: config.ajaxUrlValue,
                type: "POST",
                data: { 
                    divisionId: divisionId,
                    form_key: window.FORM_KEY
                },
                showLoader: true,
                cache: false,
                success: function(response){
                    console.log(response);

                    var str = '';
                    
                    $(response).each(function(index, element) {
                        str += '<option value="'+element.value+'" selected="selected">' + element.label + '</option>';
                    });

                    $('#basys_store_store_active').empty().append(str).find('option').attr("selected","selected");            
                }
        });      
    });
}
});


