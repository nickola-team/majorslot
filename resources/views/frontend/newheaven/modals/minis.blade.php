<div id="minis-popup" class="ngdialog ngdialog-theme-default ngdialog-gamespopup ng-scope ng-hide">
    <div class="ngdialog-overlay"></div>
    <div class="ngdialog-content" role="document" style="background: #2d2d2d;">
        <div class="ngdialog-games-page" ng-init="activeCategory">
            @if($detect->isMobile() || $detect->isTablet())
            <div class="ngdialog__header">
            <div class="ngdialog__header__title">
            <h1 class="ng-scope" translate="" id="msgPop">미니게임</h1>
            </div>
            </div>
            @else
            <nav >
            <ul class="list-inline games-nav">
                <li class="ng-scope active">
                미니게임 
                </li>
            </ul>
            </nav>
            @endif
            
            <div class="game-button-container live" id="mini-game-container" style="display: flex;">
            </div>
        </div>
        @if( $detect->isMobile() || $detect->isTablet() )
        <div class="ngdialog-close" style="margin-top: 8px;"></div>
        @else
        <div class="ngdialog-close"></div>
        @endif
    </div>
</div>
