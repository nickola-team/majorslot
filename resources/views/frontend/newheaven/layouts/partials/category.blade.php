<gamebuttons-page>
    <div class="button-container">
        <div class="button-content">
            <!-- ngRepeat: games in gameCatergory -->
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
            <!-- end ngRepeat: games in gameCatergory -->
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
            <!-- end ngRepeat: games in gameCatergory -->
            <div role="button" ng-repeat="games in gameCatergory" class="gamebutton ng-scope">
                <div class="gamebuttons game-button-5 sports" onclick=
                @auth
                {{$isSports = false}}
                    @foreach($categories AS $index=>$category)
                        @if ($category->type =='sports')
                            @if ($category->status == 0)
                                "swal('점검중입니다');"
                            @elseif ($category->view == 0)
                                "swal('지원하지 않는 게임입니다.');"
                            @else
                                "startGameByProvider('bti', 'sports')"
                            @endif 
                                {{$isSports = true}} 
                                @break
                        @endif
                    @endforeach
                    @if(!$isSports)
                        "swal('지원하지 않는 게임입니다.');"
                    @endif
                  @else
                    "navClick('login-popup')"
                  @endif
                  ng-class="games.category">
                </div>
            </div>
            <!-- end ngRepeat: games in gameCatergory -->
            <div role="button" ng-repeat="games in gameCatergory" class="gamebutton ng-scope">
                <div class="gamebuttons game-button-4 Others" ng-click="" ng-class="games.category">
                </div>
            </div>
            <!-- end ngRepeat: games in gameCatergory -->
            <div role="button" ng-repeat="games in gameCatergory" class="gamebutton ng-scope">
                <div class="gamebuttons game-button-2 Powerball" onclick=
                @auth
                    @php
                    $isMini = false;
                    @endphp
                    @foreach($categories AS $index=>$category)
                        @if ($category->type =='pball')
                            @if ($category->view == 0)
                                "swal('지원하지 않는 게임입니다.');"
                            @elseif ($category->status == 0)
                                "swal('점검중입니다');"
                            @else
                                "minisGame('{{$category->href}}','{{$category->trans?$category->trans->trans_title:$category->title}}');"
                            @endif
                                @php
                                    $isMini = true;
                                @endphp
                            @break
                        @endif
                    @endforeach
                    @if(!$isMini)
                        "swal('지원하지 않는 게임입니다.');"
                    @endif
                @else
                "navClick('login-popup')"
                @endif
                ng-class="games.category">
                </div>
            </div>
            <!-- end ngRepeat: games in gameCatergory -->
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