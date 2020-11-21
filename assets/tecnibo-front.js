/*
 * 
 */
;
(function ($) {

    function getSliderSettings() {
        return {
            dots: false,
            infinite: true,
            speed: 300,
            slidesToShow: 4,
            slidesToScroll: 4,
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
        $('.project-photos, .product-photos').slick(getSliderSettings( ));
        $('.project-photos').slickLightbox({
            background: 'rgb(255 255 255)',
            src: 'src',
            itemSelector: '.projetc-item-image  img'
        });
         
                $('#tecnibo-canditate').click(function () {
                    $(this).closest('.elementor-section').next().slideToggle();
                });
        /* Product Display the full featured image carousel  */
        $('.element-carousel-product').on('click', function (event) {
            event.preventDefault();
            $fullImageUrl = $(this).data('full');
            $('.gallery-image').fadeOut(function () {
                $('.item-image').css('backgroundImage', 'url(' + $fullImageUrl + ')');
            }).fadeIn();

//            $activeImg = $("<img/>", {"src" : $fullImageUrl});
//            $img = $('.product-main-image').html($activeImg);

        });
    });

})(jQuery);