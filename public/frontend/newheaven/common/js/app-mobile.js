$(document).ready(function() {
    addToHomescreen();

    $('.banner-slider').slick({
        autoplay: false,
        arrows: false,
        dots: true,
    });

});

function responsiveThumbnail() {
    responsiveThumbnails('.responsive-thumbnails', '120px', '4px', '130px');
}