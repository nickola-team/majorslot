@if($detect->isMobile() || $detect->isTablet())
<div id="casino-popup" class="ngdialog ngdialog-theme-default ngdialog-gamespopup ng-scope ng-hide">
@else
<div id="casino-popup" class="ngdialog ngdialog-theme-default ngdialog-gamespopup ng-scope ng-hide">
@endif

  <div class="ngdialog-overlay"></div>
    <div class="ngdialog-content" role="document" style="background: #2d2d2d; height: auto;">
      <div class="ngdialog-games-page" ng-init="activeCategory">
        @if($detect->isMobile() || $detect->isTablet())
        <div class="ngdialog__header">
          <div class="ngdialog__header__title">
          <h1 class="ng-scope" translate="" id="msgPop">라이브 카지노</h1>
          </div>
        </div>
        @else
        <nav >
          <ul class="list-inline games-nav">
            <li class="ng-scope active">
            라이브 카지노 
            </li>
          </ul>
        </nav>
        @endif
        <div class="game-button-container live" style="text-align: center;">
        @foreach($categories AS $index=>$category)
						@if ($category->type =='live')
            @if ($category->status == 0)
            <div class="game-buttons live" style="background:url('/frontend/kdior/images/game/{{strtoupper($category->title)}}.png') no-repeat #000000;background-size:contain;" onclick="javascript:swal('점검중입니다');">
              <span ng-bind="gameButton.gspName_krw" class="ng-binding">{{$category->trans?$category->trans->trans_title:$category->title}}</span>
            @else
            <div class="game-buttons live" style="background:url('/frontend/kdior/images/game/{{strtoupper($category->title)}}.png') no-repeat #000000;background-size:contain;" onclick="javascript:casinoGameStart('{{$category->href}}');">
              <span ng-bind="gameButton.gspName_krw" class="ng-binding">{{$category->trans?$category->trans->trans_title:$category->title}}</span>
            @endif
                
            </div>
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
</div>



