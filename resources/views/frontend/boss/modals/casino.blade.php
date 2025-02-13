
<div id="casino-popup" class="ngdialog ngdialog-theme-default ngdialog-gamespopup ng-scope ng-hide">
        <div class="ngdialog-overlay"></div>
        <div class="ngdialog-content" role="document">
          <div class="ngdialog-games-page" ng-init="activeCategory">
            <div class="title ng-binding"> 라이브 카지노 </div>
            <div class="game-button-container live">
            @foreach($categories AS $index=>$category)
						@if ($category->type =='live')
            @if ($category->status == 0)
            <div class="game-buttons live" style="background:url('/frontend/boss/assets/images/{{strtoupper($category->title)}}.png') no-repeat #000000;background-size:contain;" onclick="javascript:swal('점검중입니다');">
            @else
            <div class="game-buttons live" style="background:url('/frontend/boss/assets/images/{{strtoupper($category->title)}}.png') no-repeat #000000;background-size:contain;" onclick="javascript:casinoGameStart('{{$category->href}}');">
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