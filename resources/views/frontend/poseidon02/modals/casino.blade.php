
<div id="casino-popup" class="ngdialog ngdialog-theme-default ngdialog-gamespopup ng-scope ng-hide">
        <div class="ngdialog-overlay"></div>
        @if( $detect->isMobile() || $detect->isTablet() ) 
        <div class="ngdialog-content" role="document">
        @else
        <div class="ngdialog-content" role="document" style="padding: 0; background: #c3c2c2;  border-radius: 0; border-style: inset; border-color: red;">        
        @endif
          <div class="ngdialog-games-page" ng-init="activeCategory">
            <div class="title ng-binding"> 라이브 카지노 </div>
            <div class="game-button-container live" style="text-align: center;">
            @foreach($categories AS $index=>$category)
						@if ($category->type =='live')
            @if ($category->status == 0)
              @if( $detect->isMobile() || $detect->isTablet() ) 
              <div class="game-buttons live" style="background:url('/frontend/kdior/images/game/{{strtoupper($category->title)}}.png') no-repeat #000000;" onclick="javascript:swal('점검중입니다');">
              @else
              <div class="game-buttons live" style="background:url('/frontend/kdior/images/game/{{strtoupper($category->title)}}.png') no-repeat #000000;width: 43%; background-size:contain;height: 200px;margin-right: 3px;" onclick="javascript:swal('점검중입니다');">
              @endif
            
            @else
              @if( $detect->isMobile() || $detect->isTablet() ) 
              <div class="game-buttons live" style="background:url('/frontend/kdior/images/game/{{strtoupper($category->title)}}.png') no-repeat #000000;" onclick="javascript:casinoGameStart('{{$category->href}}');">
              @else
              <div class="game-buttons live" style="background:url('/frontend/kdior/images/game/{{strtoupper($category->title)}}.png') no-repeat #000000;background-size:contain;width: 43%;height: 200px;margin-right: 3px;" onclick="javascript:casinoGameStart('{{$category->href}}');">
              @endif
            
            @endif
                <span ng-bind="gameButton.gspName_krw" class="ng-binding">{{$category->trans?$category->trans->trans_title:$category->title}}</span>
                <button class="btn btn-yellow pull-right">플레이</button>
            </div>
            @endif
            @endforeach
            </div>
          </div>
          <div class="ngdialog-close"></div>
        </div>
      </div>