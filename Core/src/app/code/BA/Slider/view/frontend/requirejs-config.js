var config = {
  
    deps: [
        'owlcarousel'
    ],
    paths: {            
            'owlcarousel': "BA_Slider/js/owl.carousel.min"
        },   
  
    map: {
        '*': {
            loadCarousel: 'BA_Slider/js/owljs',
        }
    }
};