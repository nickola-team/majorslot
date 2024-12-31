
<!DOCTYPE html>
<html lang="ko">
  <head>
	<title>&starf;&starf;!!@yield('page-title')!!!&starf;&starf;</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="/frontend/boss/V/swal.css">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, height=device-height, minimal-ui, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta http-equiv="refresh" content="1801">
    <link rel="stylesheet" href="/frontend/boss/V/font-awesome.min.css">
    <link rel="stylesheet" href="/frontend/boss/V/bootstrap.min.css">
    <link rel="stylesheet" href="/frontend/boss/V/navigation.css">
    <link rel="stylesheet" href="/frontend/boss/V/structure.css">
    <link rel="stylesheet" href="/frontend/boss/V/ngDialog.css">
    <link rel="stylesheet" href="/frontend/boss/V/customer.css">
    <link rel="stylesheet" href="/frontend/boss/V/customer-popup.css">
    <link rel="stylesheet" href="/frontend/poseidon02/ngDialog-games-popup.css?v0.2">
    <link rel="stylesheet" href="/frontend/boss/V/ngDialog-theme-default.css">
    <link rel="stylesheet" href="/frontend/boss/V/ngdialog-multiplepopup.css">
    <link rel="stylesheet" href="/frontend/boss/V/ngdialog-default.css">
    <link rel="stylesheet" href="/frontend/boss/V/ngDialog-notice.css">
    <link rel="stylesheet" href="/frontend/boss/V/multiple-popup.css">
    <link rel="stylesheet" href="/frontend/boss/V/preloader.css">
    <link rel="stylesheet" href="/frontend/boss/V/slick-theme.css" charset="utf-8" >
    <link rel="stylesheet" href="/frontend/boss/V/slick.css" charset="utf-8" >
    <link rel="stylesheet" href="/frontend/boss/V/banner-slider.css">
    <link rel="stylesheet" href="/frontend/poseidon02/main1.css">
    <link rel="stylesheet" href="/frontend/boss/V/coupon.css">
    <link rel="stylesheet" href="/frontend/boss/V/transaction.css">
    <link rel="stylesheet" href="/frontend/boss/V/quick-links.css">
    <link rel="stylesheet" href="/frontend/boss/V/ngDialog-login.css">
    <link rel="stylesheet" href="/frontend/boss/V/ngDialog-signup.css">
    <link rel="stylesheet" href="/frontend/boss/V/wallet.css">
    <link rel="stylesheet" href="/frontend/boss/V/pagination.css">
    <link rel="stylesheet" href="/frontend/boss/V/sweet-alert.css">
    <link rel="shortcut icon" href="/frontend/boss/V/splash-icon.PNG" type="image/x-icon">
    <link rel="icon" href="/frontend/boss/V/splash-icon.PNG" type="image/x-icon">
    <link rel="stylesheet" href="/frontend/boss/V/responsive-themes.css">
    <script src="/frontend/boss/V/jquery.min.js"></script>
    <!-- jQuery Modal -->
    <script type="text/javascript" src="/frontend/boss/V/jquery.modal.min.js"></script>
    <link rel="stylesheet" href="/frontend/boss/V/jquery.modal.min.css">
    <script type="text/javascript" src="/frontend/boss/V/script.js" async=""></script>
    <script type="text/javascript" src="/frontend/boss/V/flash.js" async=""></script>
    <script type="text/javascript" src="/frontend/boss/V/hotnews.js" async=""></script>
    <!-- <script type="text/javascript" src="/js/sweetalert2.min.js" async></script> -->
    <script type="text/javascript" src="/frontend/boss/V/jquery.min1.js"></script>
    <script type="text/javascript" src="/frontend/boss/V/script1.js" async=""></script>
    <script type="text/javascript" src="/frontend/boss/V/cookie-manager.js"></script>
    <script type="text/javascript" src="/frontend/boss/V/common.js" async=""></script>
    <script type="text/javascript" src="/frontend/boss/V/ajax.js" async=""></script>
    <script type="text/javascript" src="/frontend/boss/V/jquery-1.12.3.min.js"></script>
    <script type="text/javascript" src="/frontend/boss/V/jwt-decode.min.js"></script>
    <script type="text/javascript" src="/frontend/boss/V/sport.js"></script>
    <script type="text/javascript" src="/frontend/boss/V/common1.js"></script>
    <script type="text/javascript" src="/frontend/boss/V/jquery.animateNumber.min.js"></script>
    <script type="text/javascript" src="/frontend/boss/V/moment.min.js"></script>
    <!--  -->
    <script type="text/javascript" src="/frontend/boss/V/jquery.newsTicker.min.js"></script>
    <script type="text/javascript" src="/frontend/boss/V/jquery.simpleTicker.js"></script>
    <script type="text/javascript" src="/frontend/boss/V/jquery-moment.min.js"></script>
    <script type="text/javascript" src="/frontend/boss/V/jquery-moment-timezone.min.js"></script>
    <script type="text/javascript" src="/frontend/boss/V/news-ticker.min.js"></script>
    <script type="text/javascript" src="/frontend/boss/V/slick.js"></script>
    <script type="text/javascript" src="/frontend/boss/V/sweetalert.min.js"></script>
    <script type="text/javascript" src="/frontend/boss/V/jquery-jOdometer.min.js"></script>
    <!--  -->
    <!-- <script language="javascript" type="text/javascript" src="/frontend/boss/V/socket.io.js"></script> -->
    <script language="javascript" type="text/javascript" src="/frontend/poseidon02/customFunc.js"></script>
    <style>
      .ng-hide {
        display: none !important;
      }
    </style>
  </head>

  <?php
    $detect = new \Detection\MobileDetect();
  ?>
@if( $detect->isMobile() || $detect->isTablet() ) 
  <link rel="stylesheet" href="/frontend/poseidon02/app-mobile.css">
@endif
  <body class="ng-scope bg-main">
    <div class="wrapper">
      @if( $detect->isMobile() || $detect->isTablet() ) 
      @else
      @include('frontend.poseidon02.layouts.partials.header')
      @endif
      
      <div ng-view="" class="ng-scope">
        <main-page class="ng-scope">
          <div class="main-page">
            @if( $detect->isMobile() || $detect->isTablet() ) 
              @include('frontend.poseidon02.layouts.partials.m.banner')
            @else
            @include('frontend.boss.layouts.partials.banner')
            @endif

            @include('frontend.boss.layouts.partials.linenotice')

            @if( $detect->isMobile() || $detect->isTablet() ) 
              @include('frontend.boss.layouts.partials.m.content')
            @else
            <div class="main-container games">
                <div class="content">
                @include('frontend.boss.layouts.partials.category')
                @include('frontend.boss.layouts.partials.notice')
                @include('frontend.poseidon02.layouts.partials.footer')
                </div>
              </div>
            @endif
              
            </div>
      </main-page>
    </div>

    @include('frontend.boss.modals.common')
    @if (!Auth::check())    
      @include('frontend.poseidon02.modals.login')
      @include('frontend.poseidon02.modals.register')
    @else
      @if( $detect->isMobile() || $detect->isTablet() ) 
        @include('frontend.poseidon02.modals.m.myinfo')
      @endif
      @include('frontend.poseidon02.modals.page')      
      @include('frontend.boss.modals.msg')      
      @include('frontend.poseidon02.modals.casino')
      @include('frontend.poseidon02.modals.slot')
      @include('frontend.boss.modals.minis')
      
    @endif
    @foreach ($noticelist as $ntc)
      @if ($ntc->popup == 'popup')
        @include('frontend.boss.modals.popup',  ['notice' => $ntc])
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

<!-- dd -->
</html>

@push('js')
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
        counterStart: 48878441,
        counterEnd: false,
        numbersImage: '/frontend/boss/V/odometer.png?ver=1.01',
        spaceNumbers: -3,
        formatNumber: true,
        widthNumber: 45,
        heightNumber: 95
      });
    @endif
      depositRealtime();
      withdrawRealtime();
      
});
</script>
@endpush