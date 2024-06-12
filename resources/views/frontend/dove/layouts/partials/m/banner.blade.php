<script type="text/javascript">
    jssor_1_slider_init = function() {

        var jssor_1_SlideoTransitions = [
          [{b:900,d:2000,x:-379,e:{x:7}}],
          [{b:900,d:2000,x:-379,e:{x:7}}],
          [{b:-1,d:1,o:-1,sX:2,sY:2},{b:0,d:900,x:-171,y:-341,o:1,sX:-2,sY:-2,e:{x:3,y:3,sX:3,sY:3}},{b:900,d:1600,x:-283,o:-1,e:{x:16}}]
        ];

        var jssor_1_options = {
          $AutoPlay: 1,
          $SlideDuration: 800,
          $SlideEasing: $Jease$.$OutQuint,
          $CaptionSliderOptions: {
            $Class: $JssorCaptionSlideo$,
            $Transitions: jssor_1_SlideoTransitions
          },
          $ArrowNavigatorOptions: {
            $Class: $JssorArrowNavigator$
          },
          $BulletNavigatorOptions: {
            $Class: $JssorBulletNavigator$
          }
        };

        var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);

        /*#region responsive code begin*/
        /*remove responsive code if you don't want the slider scales while window resizing*/
        function ScaleSlider() {
            var refSize = jssor_1_slider.$Elmt.parentNode.clientWidth;
            if (refSize) {
                refSize = Math.min(refSize, 3000);
                jssor_1_slider.$ScaleWidth(refSize);
            }
            else {
                window.setTimeout(ScaleSlider, 30);
            }
        }
        ScaleSlider();
        $Jssor$.$AddEvent(window, "load", ScaleSlider);
        $Jssor$.$AddEvent(window, "resize", ScaleSlider);
        $Jssor$.$AddEvent(window, "orientationchange", ScaleSlider);
        /*#endregion responsive code end*/
    };
</script>
    

<div id="m_banner_wrap" class="mainboard" style="display:block">
    <div id="jssor_1" style="position:relative;margin:0 auto;top:0px;left:0px;width:720px;height:330px;overflow:hidden;visibility:hidden;">
        <div data-u="slides" style="cursor:default;position:relative;top:0px;left:0px;width:720px;height:330px;overflow:hidden;">
            <div>
                <img data-u="image" src="/frontend/todayslot/images/m_slideshow1.png" />
            </div>
            <div>
                <img data-u="image" src="/frontend/todayslot/images/m_slideshow2.png" />
            </div> 
            <div>
                <img data-u="image" src="/frontend/todayslot/images/m_slideshow3.png" />
            </div>
            <div>
                <img data-u="image" src="/frontend/todayslot/images/m_slideshow4.png" />
            </div>
            <div>
                <img data-u="image" src="/frontend/todayslot/images/m_slideshow5.png" />
            </div> 
            <div>
                <img data-u="image" src="/frontend/todayslot/images/m_slideshow6.png" />
            </div>     

            <a data-u="any" href="https://www.jssor.com" style="display:none">slider bootstrap</a>
        </div>
        <!-- Bullet Navigator -->
        <div data-u="navigator" class="jssorb051" style="position:absolute;bottom:14px;right:12px;" data-autocenter="1" data-scale="0.5" data-scale-bottom="0.75">
            <div data-u="prototype" class="i" style="width:18px;height:18px;">
                <svg viewbox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                    <circle class="b" cx="8000" cy="8000" r="5800"></circle>
                </svg>
            </div>
        </div>
        <!-- Arrow Navigator -->
        <div data-u="arrowleft" class="jssora051" style="width:65px;height:65px;top:0px;left:25px;" data-autocenter="2" data-scale="0.75" data-scale-left="0.75">
            <svg viewbox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                <polyline class="a" points="11040,1920 4960,8000 11040,14080 "></polyline>
            </svg>
        </div>

        <div data-u="arrowright" class="jssora051" style="width:65px;height:65px;top:0px;right:25px;" data-autocenter="2" data-scale="0.75" data-scale-right="0.75">
            <svg viewbox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                <polyline class="a" points="4960,1920 11040,8000 4960,14080 "></polyline>
            </svg>
        </div>


    </div>
</div>				

<script type="text/javascript">jssor_1_slider_init();</script>