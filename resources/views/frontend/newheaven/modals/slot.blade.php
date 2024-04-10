@if($detect->isMobile() || $detect->isTablet())
<div id="slots-popup" class="ngdialog ngdialog-theme-default ngdialog-games ng-scope ng-hide">
@else
<div id="slots-popup" class="ngdialog ngdialog-theme-default ngdialog-gamespopup ng-scope ng-hide">
@endif
  <div class="ngdialog-overlay"></div>
    <div class="ngdialog-content" role="document" style="background: #2d2d2d; height: auto;">
      <div class="ngdialog-games-page" ng-init="activeCategory">
        @if($detect->isMobile() || $detect->isTablet())
        <div class="ngdialog__header">
          <div class="ngdialog__header__title">
          <h1 class="ng-scope" translate="" id="msgPop">슬 롯</h1>
          </div>
        </div>
        @else
        <nav >
          <ul class="list-inline games-nav">
            <li class="ng-scope active">
            슬 롯 
            </li>
          </ul>
        </nav>
        @endif
        
        <div class="game-button-container live text-center">
          @foreach($categories AS $index=>$category)
          @if ($category->type =='slot')
            @if ($category->status == 0)
            <a href="javascript:void(0);" onclick="swal('점검중입니다');" class="slot-btn gl-title-click">
            @else
            <a href="javascript:void(0);" onclick="slotGame('{{$category->href}}','{{$category->trans?$category->trans->trans_title:$category->title}}');" class="slot-btn gl-title-click">
            @endif
              <div class="inner">
                <img class="slot-bg" src="/frontend/jungle/images/slot-bg.png">
                <div class="hover-bg">
                <span></span><span></span><span></span><span></span>
                </div>
                <div class="slot-cont">
                  <img class="slot-img" src="/frontend/jungle/images/slots/{{$category->title}}.png">
                </div>
              </div>
            </a>
          @endif
          @endforeach
        </div>
      </div>
      @if( $detect->isMobile() || $detect->isTablet() )
      <div class="ngdialog-close" style="margin-top: 8px;"></div>
      @else
      <div class="ngdialog-close"></div>
      @endif
    </div>
  </div>
  <div id="slots-game-popup" class="ngdialog ngdialog-theme-default ngdialog-gamespopup ng-scope ng-hide">
    <div class="ngdialog-overlay"></div>
    <div class="ngdialog-content text-center" role="document">
      <div class="ngdialog-games-page" ng-init="activeCategory">
        <div class="ng-binding"> 
          @if($detect->isMobile() || $detect->isTablet())
          <div class="ngdialog__header">
            <div class="ngdialog__header__title">
            <h1 class="ng-scope" translate="" id="provider-title">슬 롯</h1>
            </div>
          </div>
          @else
          <nav >
            <ul class="list-inline games-nav">
              <li class="ng-scope active" id="provider-title">
              슬 롯 
              </li>
            </ul>
          </nav>
          @endif
            
        </div>
        @if( $detect->isMobile() || $detect->isTablet() )
        <div class="game-button-container live">
          <div class="gl-hotgames">
            <div class="center" id="game-list-area">
            </div>
          </div>
        </div>
        @else
        <div class="slots-games-container slots-page ng-scope" style="display: block;">
          <div class="slot-wrapper clearfix scrollbar">
            <div class="center" id="game-list-area">
            </div>
          </div>
        </div>
        @endif
      </div>
      
      @if( $detect->isMobile() || $detect->isTablet() )
      <div class="ngdialog-close" style="margin-top: 8px;"></div>
      @else
      <div class="ngdialog-close"></div>
      @endif
    </div>
  </div>
</div>



