<div class="banner-containers clearfix" ng-cloak="">
    <div class="header clearfix">
        <div class="sticky-header">
            @if (Auth::check())
            <button class="pull-left btn btn-custom-shape top-left withdraw" onclick="navClick('myinfo-popup')">마이 페이지</button>
            <button class="pull-right btn btn-custom-shape top-right deposit ng-scope" onclick="logOut();">로그아웃</button>
            @else
            <button class="pull-left btn btn-custom-shape top-left deposit" onclick="navClick('login-popup')">로그인</button>
            <button class="pull-right btn btn-custom-shape top-right withdraw" onclick="navClick('register-popup')">회원가입</button>

            @endif
        </div>
    </div>
    <div class="logo">
        <img src="/frontend/{{$logo}}/LOGO.png?0.1" height="30%" width="30%">
    </div>
    <div class="banner-sliders col-xs-12" role="toolbar">
        <img src="/frontend/newheaven/mobile/common/images/main/bn_text_welcome.png" class="mobile-welcom-txt1">
        <img src="/frontend/newheaven/mobile/common/images/main/bn_text_intro.png" class="mobile-welcom-txt2">
        
    </div>
</div>