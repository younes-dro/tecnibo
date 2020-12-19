/*
 * 
 */
;
(function ($) {
        $('.project-photos, .product-photos, .tecnibo-customsers ').on('init', function(event, slick){
//            $(this).find('button.slick-next').addClass('fa fa-chevron-right');
//            $(this).find('button.slick-prev').addClass('fa fa-chevron-left');
        });
    function getSliderSettings() {
        return {
            dots: false,
            infinite: true,
            speed: 300,
            slidesToShow: 4,
            slidesToScroll: 4,
            prevArrow: '<button type="button" class="slick-prev fa fa-chevron-left">Previous</button>',
            nextArrow: '<button type="button" class="slick-next fa fa-chevron-right">Next</button>',
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
        $('.tecnibo-customsers').slick({
            dots: true,
            infinite: true,
            rows: 3,
            arrows: true,
            speed: 300,
            slidesPerRow: 4,
            prevArrow: '<button type="button" class="slick-prev fa fa-chevron-left">Previous</button>',
            nextArrow: '<button type="button" class="slick-next fa fa-chevron-right">Next</button>',            
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesPerRow: 3
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesPerRow: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        rows: 2,
                        slidesPerRow: 1
                    }
                }

            ]
        });
        $('.project-photos').slickLightbox({
            background: 'rgb(255 255 255)',
            src: 'src',
            itemSelector: '.projetc-item-image  img',
            slick:{
                prevArrow: '<button type="button" class="slick-prev fa fa-chevron-left">Previous</button>',
                nextArrow: '<button type="button" class="slick-next fa fa-chevron-right">Next</button>',
            }
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