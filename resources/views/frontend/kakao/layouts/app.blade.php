<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('page-title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/frontend/iris/img/seven3.png" type="image/x-icon">
    <script type="text/javascript" src="/frontend/iris/theme/sp/js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="/frontend/iris/theme/sp/js/jquery.number.min.js"></script>
    <script type="text/javascript" src="/frontend/iris/theme/sp/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/frontend/iris/theme/sp/js/font-awesome.min.js"></script>
    <script type="text/javascript" src="/frontend/iris/theme/sp/js/parsley.min.js"></script>
    <script type="text/javascript" src="/frontend/iris/theme/sp/js/parsley.remote.min.js"></script>

    <link rel="stylesheet" href="/frontend/iris/theme/sp/css/fonts.css" >
    <link rel="stylesheet" href="/frontend/iris/theme/sp/css/font-awesome.min.css">
    <link rel="stylesheet" href="/frontend/iris/theme/sp/css/bootstrap.min.css">
    <link rel="stylesheet" href="/frontend/iris/theme/sp/css/style.css?v=1673872993">
    <link rel="stylesheet" href="/frontend/iris/css/loader.css?v=1649740588">    
    <link rel="stylesheet" href="/frontend/iris/theme/sp/css/fdsi_common.css"> 

    <script type="text/javascript" src="/frontend/iris/theme/sp/js/fdsi.min.js?v=1673873466"></script>

    <script type="text/javascript" src="//js.pusher.com/4.1/pusher.min.js"></script>
    <script>
        $(document).ready(function () {
            $(document).bind("contextmenu", function (e) {
                return false;
            });
            var isLogin = {{Auth::check()?1:0}};
            var newMsgCount = {{$unreadmsg}};
            if (parseInt(newMsgCount) > 0 && isLogin){
                $('#message2Player').html('새로운 쪽지가 ' + newMsgCount + '개 있습니다. [쪽지함]에서 확인하세요.');
                $('#modal-alert-message').modal();
            }
        });

        $(document).bind('selectstart', function () {
            return false;
        });
        $(document).bind('dragstart', function () {
            return false;
        });

        window.onload = function () {
          //팝업
          @foreach ($noticelist as $ntc)
          if (getCookie('popupID{{$ntc->id}}') === "master_done") {
          }
          else
          {
            <?php
                $detect = new \Detection\MobileDetect();
                if( $detect->isMobile() || $detect->isTablet() ) 
                {
                    $top = 0;
                    $left = 0;
                    $width = 'window.innerWidth';
                    $height = 'window.innerHeight';
                }
                else
                {
                    $top = 100;
                    $left = 100+425*$loop->index;
                    $width = 420;
                    $height = 380;
                }
            ?>
            addPopup('popupID{{$ntc->id}}', {{$top}}, {{$left}}, {{$width}}, {{$height}});
          }
          @endforeach
        }
          
    </script>
    
</head>

<body>
<div class="loaderBg">
    <div class="lds-dual-ring"></div>
</div>


<audio id="newMSG">
    <source src="/frontend/iris/theme/fm/sound/message.mp3" type="audio/mpeg"/>
</audio>
<audio id="alertMSG">
    <source src="/frontend/iris/theme/fm/sound/alert.wav" type="audio/mpeg"/>
</audio>
<div class='wrapper_loading hidden'>
    <img src="/frontend/iris/theme/fm/images/loading.gif" class="wrapper_loading_img" alt="">
</div>

<div class="wrapper">
    @include('frontend.iris.layouts.partials.header', ['logo' => 'kakao'])
    @include('frontend.iris.layouts.partials.banner')
    @include('frontend.iris.layouts.partials.category')
    
    @yield('content')

    @include('frontend.iris.layouts.partials.board')
    @include('frontend.iris.layouts.partials.footer', ['logo' => 'kakao'])

    @include('frontend.iris.modals.common')
    @auth()
    @else
    @include('frontend.iris.modals.login')
    @include('frontend.iris.modals.join')
    @endauth
    @foreach ($noticelist as $ntc)
    @if ($ntc->popup == 'popup')
      @include('frontend.iris.modals.notice', ['notice' => $ntc])
    @endif
    @endforeach

<audio src="/frontend/iris/snd/message.wav" id="messageSnd"></audio>
</body>

<script type="text/javascript" src="/frontend/iris/js/game.js?v=1673741516"></script>
<script type="text/javascript" src="/frontend/iris/js/money.js?v=1672945882"></script>

</body>
</html><style>
   .popup_window img {max-width:100% !important;} 
</style>



