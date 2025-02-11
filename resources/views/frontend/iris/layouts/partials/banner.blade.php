<div class="banner-main">
    <div class="banner-center">
        <div class="carousel-bg carousel slide" data-ride="carousel" data-pause="false">
            <div class="carousel-inner">
                <div class="item active" >
                  <img class="cbgbg-bg" src="/frontend/iris/theme/sp/images/banner/bn_slot_girl3.png"> 
                </div>                
            </div>
        </div>
        @if(isset($logo) && $logo != 'best')
        <div class="carousel-text-cont">
            <div class="ct-center">
                <div class="carousel-ct carousel" data-ride="carousel" data-pause="false">
                    <div class="carousel-inner">
                      <div class="item active">
                        <div class="text-cont">
                          <img src="/frontend/iris/theme/sp/images/banner/bn_text_welcome.png" class="welcomeText">
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="carousel-text-cont1">
            <div class="ct-center">
                <div class="carousel-ct carousel" data-ride="carousel" data-pause="false">
                    <div class="carousel-inner">
                      <div class="item active">
                        <div class="text-cont">
                          <img src="/frontend/iris/theme/sp/images/banner/bn_text_intro.png" class="welcomeText">
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>