define(['jquery'], function($){
    "use strict";
        return function styleProdDetails()
        {
            $(".retailbb-attribute-tbl input:button").click(function()
            {
                $(".item_status").removeClass('is-active');
                var selectedClass = $(this).attr('class').split(" ")[0];
		            $("."+selectedClass).addClass('is-active');
            });
        }
 });