
<div id="slots-popup" class="ngdialog ngdialog-theme-default ngdialog-gamespopup ng-scope ng-hide">
        <div class="ngdialog-overlay"></div>
        <div class="ngdialog-content" role="document">
          <div class="ngdialog-games-page" ng-init="activeCategory">
            <div class="title ng-binding"> 슬 롯 </div>
            <div class="game-button-container live">
				@foreach($categories AS $index=>$category)
				@if ($category->type =='slot')
        {{-- @if ($category->status == 0)
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
							<img class="slot-img" src="/frontend/boss/assets/images/{{strtoupper($category->title)}}.png">
							</div>
						</div>
					</a> --}}

        @if ($category->status == 0)
        <div class="slot-btn gl-title-click" style="background:url('/frontend/boss/assets/images/{{strtoupper($category->title)}}.png') no-repeat #000000;background-size:contain;" onclick="swal('점검중입니다');">
        @else
        <div class="slot-btn gl-title-click" style="background:url('/frontend/boss/assets/images/{{strtoupper($category->title)}}.png') no-repeat #000000;background-size:contain;" onclick="slotGame('{{$category->href}}','{{$category->trans?$category->trans->trans_title:$category->title}}');">
        @endif
            {{-- <span ng-bind="gameButton.gspName_krw" class="ng-binding">{{$category->trans?$category->trans->trans_title:$category->title}}</span>
            <button class="btn btn-yellow pull-right" style="padding: 3px 35px;font-size: 12px;">플레이</button> --}}
        </div>
				@endif
				@endforeach

        </div>

          </div>
          <div class="ngdialog-close"></div>
        </div>
      </div>
      <div id="slots-game-popup" class="ngdialog ngdialog-theme-default ngdialog-gamespopup ng-scope ng-hide">
        <div class="ngdialog-overlay"></div>
        <div class="ngdialog-content" role="document">
          <div class="ngdialog-games-page" ng-init="activeCategory">
            <div class="title ng-binding"> <span id="provider-title">게임사</span> </div>
            <div class="game-button-container live">
					<div class="gl-hotgames">
						<div class="center" id="game-list-area">
						</div>
					</div>
            </div>
          </div>
          <div class="ngdialog-close"></div>
        </div>
      </div>



