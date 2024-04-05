
<!DOCTYPE html>
<html lang="ko">
<!-- <html class="no-js" ng-app="casinoApp"> -->
  <head>
	<title>&starf;&starf;!!@yield('page-title')!!!&starf;&starf;</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- <link rel="stylesheet" href="/frontend/boss/V/swal.css"> light -->
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, height=device-height, minimal-ui, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta http-equiv="refresh" content="1801">

    <?php
    $detect = new \Detection\MobileDetect();
  ?> 
  <script src="/frontend/newheaven/common/js/jquery.min.js"></script>
  <script src="/frontend/newheaven/common/js/jquery-jOdometer.min.js"></script>
  <script src="/frontend/newheaven/common/js/customFunc.js"></script>
  <script src="/frontend/newheaven/common/js/sweetalert.min.js"></script>

  <link rel="stylesheet" href="/frontend/boss/V/main1.css">
  <script src="/frontend/newheaven/common/js/jquery.newsTicker.min.js"></script>
  
    <!-- mobile -->
@if( $detect->isMobile() || $detect->isTablet() )

    <link rel="icon" sizes="114x114" href="/frontend/newheaven/mobile/common/images/splash/splash-icon.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/frontend/newheaven/mobile/common/images/splash/splash-icon.png">
    <link rel="apple-touch-startup-image" href="/frontend/newheaven/mobile/common/images/splash/splash-screen.png" media="screen and (max-device-width: 320px)" />
    <link rel="apple-touch-startup-image" href="/frontend/newheaven/mobile/common/images/splash/splash-screen@2x.png" media="(max-device-width: 480px) and (-webkit-min-device-pixel-ratio: 2)" />
    <link rel="apple-touch-startup-image" sizes="640x1096" href="/frontend/newheaven/mobile/common/images/splash/splash-screen@3x.png" />
    <link rel="apple-touch-startup-image" sizes="1024x748" href="/frontend/newheaven/mobile/common/images/splash/splash-screen-ipad-landscape.png" media="screen and (min-device-width : 481px) and (max-device-width : 1024px) and (orientation : landscape)" />
    <link rel="apple-touch-startup-image" sizes="768x1004" href="/frontend/newheaven/mobile/common/images/splash/splash-screen-ipad-portrait.png" media="screen and (min-device-width : 481px) and (max-device-width : 1024px) and (orientation : portrait)" />
    <link rel="apple-touch-startup-image" sizes="1536x2008" href="/frontend/newheaven/mobile/common/images/splash/splash-screen-ipad-portrait-retina.png" media="(device-width: 768px)  and (orientation: portrait) and (-webkit-device-pixel-ratio: 2)" />
    <link rel="apple-touch-startup-image" sizes="1496x2048" href="/frontend/newheaven/mobile/common/images/splash/splash-screen-ipad-landscape-retina.png" media="(device-width: 768px) and (orientation: landscape)    and (-webkit-device-pixel-ratio: 2)" />

    <link rel="icon" type="image/png" sizes="16x16" href="/frontend/newheaven/mobile/common/images/favicon/16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/frontend/newheaven/mobile/common/images/favicon/32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/frontend/newheaven/mobile/common/images/favicon/96x96.png">

    <link rel="stylesheet" href="/frontend/newheaven/mobile/common/css/plugins/animate.css">
    <link rel="stylesheet" href="/frontend/newheaven/mobile/common/css/plugins/font-awesome.css">
    <link rel="stylesheet" href="/frontend/newheaven/mobile/common/css/plugins/intlTelInput.css">
    <link rel="stylesheet" href="/frontend/newheaven/mobile/common/css/plugins/ngDialog.css">
    <link rel="stylesheet" href="/frontend/newheaven/mobile/common/css/plugins/ngDialog-theme-default.css">
    <link rel="stylesheet" href="/frontend/newheaven/mobile/common/css/plugins/slick.css">
    <link rel="stylesheet" href="/frontend/newheaven/mobile/common/css/plugins/slick-theme.css">
    <link rel="stylesheet" href="/frontend/newheaven/mobile/common/css/plugins/sweetalert.css">
    <link rel="stylesheet" href="/frontend/newheaven/mobile/common/css/plugins/datetimepicker.css">
    <link rel="stylesheet" href="/frontend/newheaven/mobile/common/css/bootstrap.min.css">

    <link rel="stylesheet" href="/frontend/newheaven/mobile/common/css/responsive-thumbnails.css">
    <link rel="stylesheet" href="/frontend/newheaven/mobile/common/css/settlement.css">
    <link rel="stylesheet" href="/frontend/newheaven/mobile/directive/component/jackpot/jackpot.css">
    <link rel="stylesheet" href="/frontend/newheaven/mobile/directive/component/slotsview/slots-view.css">
    <link rel="stylesheet" href="/frontend/newheaven/mobile/directive/component/gamebutton/gamebutton.css">
    <link rel="stylesheet" href="/frontend/newheaven/mobile/directive/component/customer/customer.css">
    <link rel="stylesheet" href="/frontend/newheaven/mobile/directive/component/announcement/announcement.css">
    <link rel="stylesheet" href="/frontend/newheaven/mobile/directive/component/footer/footer.css">
    <link rel="stylesheet" href="/frontend/newheaven/mobile/directive/component/balance/balance.css">
    <link rel="stylesheet" href="/frontend/newheaven/mobile/common/css/app-mobile.css">
    <link rel="stylesheet" href="/frontend/boss/V/ngDialog-games-popup.css?v0.2">
    <link rel="stylesheet" href="/frontend/newheaven/mobile/common/css/ngdialog-default.css">
    <link rel="stylesheet" href="/frontend/newheaven/mobile/common/css/multiple-popup.css">
    
    <link rel="stylesheet" href="/frontend/newheaven/mobile/common/css/add2Home.css">
    <link rel="stylesheet" href="/frontend/newheaven/common/plugins/build/css/intlTelInput.css">
    <script type="text/javascript" src="/frontend/newheaven/common/js/addtohomescreen.js"></script>
    <link rel="stylesheet" href="/frontend/newheaven/common/css/custom.css">
    <link rel="stylesheet" href="/frontend/newheaven/mobile/common/css/custom.css">
        
@else
    <link rel="icon" type="image/png" sizes="16x16" href="/frontend/newheaven/common/images/favicon/16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/frontend/newheaven/common/images/favicon/32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/frontend/newheaven/common/images/favicon/96x96.png">

    <link rel="stylesheet" href="/frontend/newheaven/common/css/app.css?_v=1711076938">

    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700" rel="stylesheet">
    <link href="http://fonts.googleapis.com/earlyaccess/notosanskr.css" rel="stylesheet">

    <link rel="stylesheet" href="/frontend/newheaven/common/plugins/build/css/intlTelInput.css">
    <link rel="stylesheet" href="/frontend/newheaven/common/css/plugins/colorbox.css">

    
    <script src="/frontend/newheaven/common/js/angular.min.js"></script>
    <script src="/frontend/newheaven/common/js/angular-idle.min.js"></script>
    <script src="/frontend/newheaven/common/js/utill.js"></script>
    <script src="/frontend/newheaven/common/js/jquery-intlTelInput.js"></script>
    <script src="/frontend/newheaven/common/js/jquery-browser.min.js"></script>
    <script src="/frontend/newheaven/common/js/jquery-moment.min.js"></script>
    <script src="/frontend/newheaven/common/js/jquery-moment-timezone.min.js"></script>
    <script src="/frontend/newheaven/common/js/jstz-1.0.4.min.js"></script>

    <script src="/frontend/newheaven/common/js/es5-shim.js"></script>
    <script src="/frontend/newheaven/common/js/angular-route.js"></script>
    <script src="/frontend/newheaven/common/js/angular-routeData.js"></script>
    <script src="/frontend/newheaven/common/js/ngDialog.js"></script>
    <script src="/frontend/newheaven/common/js/angular-messages.js"></script>
    <script src="/frontend/newheaven/common/js/angular-cookies.min.js"></script>
    <script src="/frontend/newheaven/common/js/angular-module-currencyCode.min.js"></script>
    <script src="/frontend/newheaven/common/js/angular-pagination-ui-bootstrap.js"></script>

    <script src="/frontend/newheaven/common/js/angular-translate.min.js"></script>
    <script src="/frontend/newheaven/common/js/angular-translate-storage-cookie.min.js"></script>
    <script src="/frontend/newheaven/common/js/angular-translate-storage-local.min.js"></script>
    <script src="/frontend/newheaven/common/js/angular-translate-loader-static-files.min.js"></script>

    <script src="/frontend/newheaven/common/js/angular-custom-module.js"></script>
    <script src="/frontend/newheaven/common/js/angular-custom-common.js"></script>
    <script src="/frontend/newheaven/common/js/angular-custom-customer.js"></script>
    <script src="/frontend/newheaven/common/js/angular-custom-signup.js"></script>
    <script src="/frontend/newheaven/common/js/angular-custom-wallet.js"></script>
    <script src="/frontend/newheaven/common/js/angular-custom-slots.js"></script>   
    
    <script src="/frontend/newheaven/common/js/controllers/multiple-popup.controller.js"></script>
    <script src="/frontend/newheaven/common/js/factory/csrf.factory.js"></script>

    <script src="/frontend/newheaven/common/plugins/build/js/intlTelInput.js"></script>

    <script src="/frontend/newheaven/common/js/bootstrap.min.js"></script>
    <script src="/frontend/newheaven/common/js/jquery-easing.min.js"></script>
    <script src="/frontend/newheaven/common/js/jquery.placeholder.min.js"></script>
    
    <script src="/frontend/newheaven/common/js/jquery-contained-sticky-scroll.js"></script>
    <script src="/frontend/newheaven/common/js/slick.js"></script>

    <script src="/frontend/newheaven/common/js/datetimepicker.js"></script>
    <script src="/frontend/newheaven/common/js/jquery.simpleTicker.js"></script>
    <script src="/frontend/newheaven/common/js/news-ticker.min.js"></script>
    <script src="/frontend/newheaven/common/js/colorbox.js"></script>
    <script src="/frontend/newheaven/common/js/app.js"></script>
    <script src="/frontend/newheaven/common/js/classie.js"></script>

    
    <link rel="stylesheet" href="/frontend/boss/V/ngDialog-games-popup.css?v0.2">
    <link rel="stylesheet" href="/frontend/newheaven/common/css/custom.css">
  @endif
    <style>
      .ng-hide {
        display: none !important;
      }
    </style>
  </head>

  
@if( $detect->isMobile() || $detect->isTablet() ) 
  
@endif
@if (empty($logo))
		<?php $logo = 'newheaven'; ?>
		@endif
   
      @if( $detect->isMobile() || $detect->isTablet() ) 
      <body ontouchstart="" ng-controller="CommonController" class="ng-scope" style="" >
        <div class="wrapper">  
        @include('frontend.newheaven.layouts.partials.m.banner')
        @include('frontend.newheaven.layouts.partials.m.content')
      @else   
      <body class="ng-scope bg-main" ng-controller="CommonController" ng-class="RouteData.get('bodyClass')">
        <div class="wrapper">  
          <div ng-view="" class="ng-scope">
            <main-page class="ng-scope">
              <div class="main-page main-first">
                @include('frontend.newheaven.layouts.partials.header')
                <div class="main-container">
                  <div class="main-content">
                    <div class="left-container">
                        @include('frontend.newheaven.layouts.partials.banner')
                        @include('frontend.newheaven.layouts.partials.category')
                        @include('frontend.newheaven.layouts.partials.notice')
                    </div>
                      <div class="right-container">
                        @include('frontend.newheaven.layouts.partials.rightbar')       
                      </div>
                  </div> 
                            
                </div>
                
              </div>
            </main-page>
          </div>
          @include('frontend.newheaven.layouts.partials.footer')
      @endif  

      @include('frontend.newheaven.modals.common')
      @if (!Auth::check())    
        @include('frontend.newheaven.modals.login')
        @include('frontend.newheaven.modals.register')
      @else
        @if( $detect->isMobile() || $detect->isTablet() ) 
          @include('frontend.newheaven.modals.m.myinfo')
        @endif
        @include('frontend.newheaven.modals.page')      
        @include('frontend.newheaven.modals.msg')      
        @include('frontend.newheaven.modals.casino')
        @include('frontend.newheaven.modals.slot')
        @include('frontend.newheaven.modals.minis')
        
      @endif
      @foreach ($noticelist as $ntc)
        @if ($ntc->popup == 'popup')
          @include('frontend.newheaven.modals.popup',  ['notice' => $ntc])
          <script>
            if (getCookie('pop{{$ntc->id}}') === "done") {
              closeWinpop({{$ntc->id}});
            }
          </script>
        @endif
      @endforeach
    </div>
    @if( $detect->isMobile() || $detect->isTablet() ) 
    @elseif (isset($telegram) && $telegram!='')
    <div class="customizer-tele">
      <span class="tele" onclick="window.open('https://t.me/{{$telegram??'Boss텔레'}}');" style="cursor:pointer;"><img src="/frontend/boss/V/quick_customer2.png"></span>
    </div>
    @endif
  </body>

</html>


