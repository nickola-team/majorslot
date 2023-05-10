
<div id="slots-popup" class="ngdialog ngdialog-theme-default ngdialog-gamespopup ng-scope ng-hide">
        <div class="ngdialog-overlay"></div>
        <div class="ngdialog-content" role="document">
          <div class="ngdialog-games-page" ng-init="activeCategory">
            <div class="title ng-binding"> 슬 롯 </div>
            <div class="game-button-container live">
				@foreach($categories AS $index=>$category)
				@if ($category->type =='slot')
					<a href="javascript:void(0);" onclick="slotGame('{{$category->href}}','{{$category->trans?$category->trans->trans_title:$category->title}}');" class="slot-btn gl-title-click">
						<div class="inner">
							<img class="slot-bg" src="/frontend/jungle/images/slot-bg.png">
							<div class="hover-bg">
							<span></span><span></span><span></span><span></span>
							</div>
							<div class="slot-cont">
							<img class="slot-img" src="/frontend/jungle/images/slots/{{strtoupper($category->title)}}.png">
							</div>
						</div>
					</a>
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



