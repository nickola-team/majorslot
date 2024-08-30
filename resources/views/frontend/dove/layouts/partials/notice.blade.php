<div data-v-525011a5="" class="vue-portal-target" id="pop{{$ntc->id}}" 
@if( $detect->isMobile() || $detect->isTablet() ) 
<?php
$width = '100vw'; $height = '100vh';
?>
  style="position:absolute; left:0px; top:0px; z-index:200; width:100vw;height:100vh;" 
@else
<?php
$width = '500px'; $height = '400px';
?>
    style="width:{{$width}};height:{{$width}};padding:30px;" 
            @endif>
  <div class="dialog row" style="flex-direction: row; position:relative;">
    <div class="container row" style="flex-direction: row; width: 100%;  margin: unset;">
      <button class="close-button button" style="background: rgb(44, 48, 58);top: 4px;right: 15px;" onclick="closeWinpop({{$ntc->id}});">
        <i data-v-e56d064c="" class="fa-solid fa-times" style="color: rgb(255, 255, 255);"></i>
      </button>  
      <div data-v-5290ad82="" class="fill-height">
        <div data-v-5290ad82="" class="container column">
          <div data-v-5290ad82="" class="dialog-title row" >
            <span data-v-5290ad82="" class="text-level-7 text" maxlength="10" style="overflow: hidden;text-overflow: ellipsis;white-space: nowrap;"><img data-v-5290ad82="" src="/frontend/dove/assets/img/notice.d792102.svg" class="margin-right-5" style="width: 20px; height: 20px; "><p style="width:220px;">{{$ntc->title}}</p></span>
          </div> 
          <div data-v-5290ad82="" class="margin-bottom-10 column">
            <div data-v-5290ad82="" class="title scrollable-atuo row" style="flex-direction: row;">
              <span data-v-5290ad82="" style="overflow: auto !important;text-overflow: ellipsis;white-space: normal;line-height: 1.2;height: 200px;text-align: left;   word-wrap: break-word;"><?php echo $ntc->content; ?></span>
            </div>
          </div> 
          <div data-v-5290ad82="" class="margin-bottom-10 column" style="position: absolute;bottom: 0;width: 100%;">
            <div data-v-5290ad82="" class="button-wrap row" style="flex-direction: row;">
              <div data-v-40c960e6="" data-v-5290ad82="" class="row" style="flex-direction: row; display:none;">
                <div class="row" style="width: 100%; flex-direction: row;">
                  <button data-v-5290ad82="" class="history button text" style="background: transparent;" onclick="depositHistoryPop();">
                    <span data-v-5290ad82="" class="text"><i data-v-e56d064c="" data-v-5290ad82="" class="margin-right-5 fa-solid fa-list"></i>입금내역</span>
                  </button>
                </div> <!---->
              </div> 
              <div data-v-5290ad82="" class="spacer"></div> 
              <div style="padding-right: 15px;">
              <button data-v-5290ad82="" class="withdraw button" id="notice_chk{{$notice->id}}" onclick="closeWinpopDay({{$ntc->id}});" style="background-color: #2c303a;border-radius: 8px;">오늘 하루 보지않기</button> 
              </div>
              
              <!---->
              <div>
              <button data-v-5290ad82="" class="deposit-button button" onclick="closeWinpop({{$ntc->id}});">닫기</button> 
              </div>
              
              <div data-v-3b808694="" data-v-5290ad82="" class="row" style="flex-direction: row;">
                <div class="row" style="width: 100%; flex-direction: row;"></div> <!---->
              </div> 
              <div data-v-75458f4a="" data-v-5290ad82="" class="row" style="flex-direction: row;">
                <div class="row" style="width: 100%; flex-direction: row;"></div> <!---->
              </div>
            </div>
          </div>
        </div> <!---->
      </div>
    </div>
  </div>
</div>

