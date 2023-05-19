<div class="banner-container">
    <div class="logo">
        <img src="/frontend/{{$logo??'boss'}}/LOGO.png" width="120" onclick="window.location.href='/';">
    </div>
    <div class="main-banner-slider">
        <!-- <div aria-live="polite" class="slick-list draggable"> -->
        <!-- <div class="slick-track" style="opacity: 1; width: 1500px;" role="listbox"> -->
        <div>
            <img src="/frontend/boss/V/slider-mobile-1.png" style="width:100%">
        </div>
        <div>
            <img src="/frontend/boss/V/slider-mobile-2.png" style="width:100%">
        </div>
        <!-- <img src="/V/main-banner-mobile-5.jpg"> -->
        <!-- <img src="/V/main-banner-mobile-6.jpg"> -->
        <!-- </div> -->
        <!-- </div> -->
    </div>
</div>
<script>
$(function() {
    $('.main-banner-slider').not('.slick-initialized').slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          autoplay: true,
          autoplaySpeed: 3000,
          dots: false,
          arrows: false,
          /*asNavFor: '.text-slider',*/
          fade: true,
          cssEase: 'linear',
          speed: 2000
        });
});
</script>