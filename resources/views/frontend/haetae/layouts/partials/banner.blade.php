<div class="banner">
    <div class="banner-slider">
        <div style="object-fit: cover; height:600px; margin-top:135px; width:100%;">
        <img src="/frontend/haetae/images/slideshow1.png">
        </div>
        <div style="width:100%;">
        <img class="banner-outside" src="/frontend/haetae/images/slideshow2.png">
        </div>
        <!--<div>
        <img class="banner-outside" src="/frontend/nine/images/slideshow3.png">
        </div>
        <div>
        <img class="banner-outside" src="/frontend/haetae/images/slideshow4.png">
        </div> -->
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