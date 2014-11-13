jQuery(document).ready(function($) {
    $('.owl-carousel').owlCarousel({
        items: 4,
        loop: true,
        margin: 5,
        nav: true,
        navText: ['&#10094;', '&#10095;'],
        dots: false,
        lazyLoad: true,
        responsive: {
            0:{
                items:1
            },
            600:{
                items:3
            },
            1000:{
                items:5
            }
        }
    });
});