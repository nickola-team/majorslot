<gamebuttons-page>
    <div class="button-container" style="min-height:360px;">
        <div class="button-content">
            
            <div role="button" ng-repeat="games in gameCatergory" class="gamebutton ng-scope">
                <div class="gamebuttons game-button-1 live" onclick=
                    @auth
                    "navClick('casino-popup')"
                    @else
                    "navClick('login-popup')"
                    @endif 
                    ng-class="games.category">
                </div>
            </div>
            
            <div role="button" ng-repeat="games in gameCatergory" class="gamebutton ng-scope">
                <div class="gamebuttons game-button-3 slot" onclick=
                @auth
                "navClick('slots-popup')"
                @else
                "navClick('login-popup')"
                @endif 
                ng-class="games.category">
                </div>
            </div>
            <div role="button" class="gamebutton" ng-click="displayGames('slot')">
                <div class="slots-jackpot-container click-disable">
                    <div class="jackpot-odometer jackpot">
                        <img src="/frontend/newheaven/common/images/jackpot/won-sign.png" alt="">
                    </div>
                </div>
            </div>
            <div class="clearfix">        
            </div>
        </div>
    </div>
</gamebuttons-page>


<script>
  $(document).ready(function(){
    @if( $detect->isMobile() || $detect->isTablet() ) 
    $('.jackpot-odometer').jOdometer({
        increment: 24,
        counterStart: 39888441,
        counterEnd: false,
        numbersImage: '/frontend/boss/V/mobile-odometer-small.png',
        spaceNumbers: 0,
        formatNumber: true,
        widthNumber: 17,
        heightNumber: 36
      });
    @else
    $('.jackpot-odometer').jOdometer({
        increment: 24,
        counterStart: 51242620842,
        counterEnd: false,
        numbersImage: '/frontend/newheaven/common/images/jackpot/odometer.png',
        spaceNumbers: -3,
        formatNumber: true,
        widthNumber: 35,
        heightNumber: 74
      });
    @endif
      depositRealtime();
      withdrawRealtime();
      
});
</script>