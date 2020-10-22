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
            itemSelector: '.projetc-item-image > img'
        });

// external js: isotope.pkgd.js

// init Isotope
        var iso = new Isotope(' .grid-team ', {
            itemSelector: '.col-member',
            layoutMode: 'fitRows'
        });

// filter functions
        var filterFns = {
        };

// bind filter button click
        var filtersElem = document.querySelector('.filters-button-group');
        filtersElem.addEventListener('click', function (event) {
            // only work with buttons
            if (!matchesSelector(event.target, 'button')) {
                return;
            }
            var filterValue = event.target.getAttribute('data-filter');
            // use matching filter function
            filterValue = filterFns[ filterValue ] || filterValue;
            iso.arrange({filter: filterValue});
        });

// change is-checked class on buttons
        var buttonGroups = document.querySelectorAll('.button-group');
        for (var i = 0, len = buttonGroups.length; i < len; i++) {
            var buttonGroup = buttonGroups[i];
            radioButtonGroup(buttonGroup);
        }

        function radioButtonGroup(buttonGroup) {
            buttonGroup.addEventListener('click', function (event) {
                // only work with buttons
                if (!matchesSelector(event.target, 'button')) {
                    return;
                }
                buttonGroup.querySelector('.is-checked').classList.remove('is-checked');
                event.target.classList.add('is-checked');
            });
        }


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