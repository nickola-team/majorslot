
<!DOCTYPE html>
<html lang="ko">
  <head>
	<title>&starf;&starf;!!Boss!!!&starf;&starf;</title>
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
    <link rel="stylesheet" href="/frontend/boss/V/ngDialog-games-popup.css?v0.2">
    <link rel="stylesheet" href="/frontend/boss/V/ngDialog-theme-default.css">
    <link rel="stylesheet" href="/frontend/boss/V/ngdialog-multiplepopup.css">
    <link rel="stylesheet" href="/frontend/boss/V/ngdialog-default.css">
    <link rel="stylesheet" href="/frontend/boss/V/ngDialog-notice.css">
    <link rel="stylesheet" href="/frontend/boss/V/multiple-popup.css">
    <link rel="stylesheet" href="/frontend/boss/V/preloader.css">
    <link rel="stylesheet" href="/frontend/boss/V/slick-theme.css" charset="utf-8" >
    <link rel="stylesheet" href="/frontend/boss/V/slick.css" charset="utf-8" >
    <link rel="stylesheet" href="/frontend/boss/V/banner-slider.css">
    <link rel="stylesheet" href="/frontend/boss/V/main1.css">
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
    <script language="javascript" type="text/javascript" src="/frontend/boss/V/customFunc.js"></script>
    <script type="text/javascript">
      var isSport = 0;
    </script>
    <style>
      .ng-hide {
        display: none !important;
      }
    </style>
  </head>

  <body class="ng-scope bg-main">
    <div class="wrapper">

      @include('frontend.boss.layouts.partials.header')
      
      <div ng-view="" class="ng-scope">
        <main-page class="ng-scope">
          <div class="main-page">
            @include('frontend.boss.layouts.partials.banner')
            @include('frontend.boss.layouts.partials.linenotice')


              <div class="main-container games">
                <div class="content">
                @include('frontend.boss.layouts.partials.category')
                @include('frontend.boss.layouts.partials.notice')
                @include('frontend.boss.layouts.partials.footer')
                </div>
              </div>
            </div>
      <script type="text/javascript">
        depositRealtime();
        withdrawRealtime();
        function getCookieWel(name) {
          var Found = false;
          var start, end;
          var i = 0;
          while (i <= document.cookie.length) {
            start = i;
            end = start + name.length;
            if (document.cookie.substring(start, end) == name) {
              Found = true;
              break;
            }
            i++
          }
          if (Found == true) {
            start = end + 1;
            end = document.cookie.indexOf(";", start);
            if (end < start) end = document.cookie.length;
            return document.cookie.substring(start, end)
          }
          return ""
        }
        function setCookie( name, value, expiredays ) { 
        var todayDate = new Date(); 
        todayDate.setDate( todayDate.getDate() + expiredays ); 
        document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";" 
        } 

        function closeWinpopDay(id) { 
          if ( document.getElementById("notice_chk" + id).checked ){ 
          setCookie( "pop" + id, "done" , 1 ); 
          } 

          document.getElementById("pop" + id).style.visibility = "hidden"; 
        } 

        function closeWinpop(id) {
          document.getElementById("pop" + id).style.visibility = "hidden"; 
        }
        
      </script>
      </main-page>
    </div>
    <?php
    $detect = new \Detection\MobileDetect();
  ?>
    @include('frontend.boss.modals.common')
    @if (!Auth::check())    
    @include('frontend.boss.modals.login')
    @include('frontend.boss.modals.register')
    @else
    @include('frontend.boss.modals.page')      
    @include('frontend.boss.modals.msg')      
    @include('frontend.boss.modals.casino')      
    @include('frontend.boss.modals.slot')
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

<div class="customizer-tele">
	<span class="tele" onclick="window.open('https://t.me/Boss텔레');" style="cursor:pointer;"><img src="/frontend/boss/v/quick_customer2.png">Boss텔레</span>
</div>
  </body>

<!-- dd -->
</html>