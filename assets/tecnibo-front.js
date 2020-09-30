/*
 * 
 */
;
(function ($) {

    function getSliderSettings(indexSide) {
        return {
            dots: false,
            infinite: true,
            speed: 300,
            slidesToShow: 3,
            slidesToScroll: 3,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }

            ]
        };
    }
    $(document).ready(function () {
        $('.project-photos , .product-photos').slick(getSliderSettings(1));
        
        /* Display the full featured image carousel  */
        $('.element-carousel-product').on('click', function ( event ){
            event.preventDefault();
            $fullImageUrl = $(this).data('full') ;
            $('.gallery-image').fadeOut(function(){
                $('.item-image').css('backgroundImage', 'url(' + $fullImageUrl + ')');
            }).fadeIn();

//            $activeImg = $("<img/>", {"src" : $fullImageUrl});
//            $img = $('.product-main-image').html($activeImg);
            
        });
    });

})(jQuery);