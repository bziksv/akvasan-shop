$(document).ready(function(){
    $('.slider-images .bxslider').bxSlider({
        controls:false,
        auto:true
    });
    
    $('.slider-partners .bxslider').bxSlider({
        infiniteLoop:true,
        auto:true,
        prevText:'',
        nextText:'',
        minSlides:7,
        maxSlides:7,
        moveSlides:1,
        slideWidth:160
    });
    
    
    $('.slider-partners-mobile .bxslider').bxSlider({
        infiniteLoop:true,
        auto:true,
        prevText:'',
        nextText:'',
        minSlides:2,
        maxSlides:2,
        moveSlides:1,
        slideWidth:200
        
    });
    
    $('.product-img .bxslider').bxSlider({
        controls:false,
        pagerCustom: '.bx-pager-mini',
        adaptiveHeight: true,
    });

    if($(".mini-img-slider .bx-pager-mini img").length > 5) {
        $('.mini-img-slider .bx-pager-mini').bxSlider({
            mode: 'vertical',
            autoControls: true,
            randomStart: false,
            pager: false,
            auto: false,
            autoHover: true,
            captions: false,
            controls: true,
            minSlides: 5,
            maxSlides: 5,
            //moveSlides: 5,
            slideWidth: 80
        });
    }
})