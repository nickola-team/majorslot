<div class="banner">
    <div class="banner-slider">
        <div>
        <img class="banner-outside" src="/frontend/boss/V/slide2.png">
        </div>
        <div>
        <img class="banner-outside" src="/frontend/boss/V/slide1.png">
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