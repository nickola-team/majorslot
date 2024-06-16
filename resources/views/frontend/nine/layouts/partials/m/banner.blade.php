<div class="banner-container">
    <div class="logo">
        <img src="/frontend/{{$logo??'boss'}}/LOGO_m.png" onclick="window.location.href='/';" style="width: 17%;">
    </div>
    <div class="main-banner-slider">
        <!-- <div aria-live="polite" class="slick-list draggable"> -->
        <!-- <div class="slick-track" style="opacity: 1; width: 1500px;" role="listbox"> -->
        
        <div>
            <img src="/frontend/nine/images/mobile/slideshow1.png" style="width:100%">
        </div>
        <div>
            <img src="/frontend/nine/images/mobile/slideshow2.png" style="width:100%">
        </div> 
        <div>
            <img src="/frontend/nine/images/mobile/slideshow3.png" style="width:100%">
        </div>
        <div>
            <img src="/frontend/nine/images/mobile/slideshow4.png" style="width:100%">
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


$(document).ready(function(){
    @if( $detect->isMobile() || $detect->isTablet() ) 
    $('.jackpot-odometer').jOdometer({
        increment: 24,
        counterStart: 39888441,
        counterEnd: false,
        numbersImage: '/frontend/boss/V/mobile-odometer-small.png',
        spaceNumbers: 0,
        formatNumber: true,
        widthNumber: 17,
        heightNumber: 36
      });
    @else
    $('.jackpot-odometer').jOdometer({
        increment: 24,
        counterStart: 48878441,
        counterEnd: false,
        numbersImage: '/frontend/boss/V/odometer.png?ver=1.01',
        spaceNumbers: -3,
        formatNumber: true,
        widthNumber: 45,
        heightNumber: 95
      });
    @endif
      depositRealtime();
      withdrawRealtime();
      
});
</script>