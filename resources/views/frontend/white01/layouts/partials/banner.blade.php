<div class="banner">
    <div class="banner-slider">
        {{-- <div>
        <img class="banner-outside" src="/frontend/nine/images/slideshow1.png">
        </div> --}}
        <div>
        <img class="banner-outside" src="/frontend/nine/images/slideshow2.png">
        </div>
        <div>
        <img class="banner-outside" src="/frontend/nine/images/slideshow3.png">
        </div>
        <div>
        <img class="banner-outside" src="/frontend/nine/images/slideshow4.png">
        </div>
    </div>
</div>

<script>
$(function() {
    $(".banner-slider").not('.slick-initialized').slick({
    dots: false,
    autoplay: true,
    speed: 1500,
    fade: true,
    cssEase: 'linear'
    });
});
</script>