define(['jquery','owlcarousel'], function($){
    "use strict";
        return function loadCarousel()
        {
            $("#owlslider").owlCarousel({
                navigation : true, // Show next and prev buttons
                autoPlay: false, //Set AutoPlay to 3 seconds
                smartSpeed: 1500,
                animateIn: 'fadeIn',
                animateOut: 'fadeOut',
                items : 1
            });
        }
 });
