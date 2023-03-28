<div class="nav-main">
    <div class="nav-center">
        <a href="/" class="logo" style="height: 100px; !important">
            <img src="/frontend/{{$logo}}/img/iris_logo.png?v=1671720585">
        </a>
        <button class="m-menu-btn"><i class="fa fa-bars"></i></button>
        <ul>
            <li>
                @auth()
                <a href="javascript:void(0)" class="evol-mdl-trigger" onclick="charge('add'); closeMenu();" >
                @else
                <a href="javascript:void(0)" class="evol-mdl-trigger" onclick="mustSignIn('로그인이 필요한 메뉴입니다.')" >
                @endif
                    <p>입금신청</p>
                </a>
            </li>
            <li>
                @auth()
                <a href="javascript:void(0)" class="evol-mdl-trigger" onclick="charge('out'); closeMenu();" >
                @else
                <a href="javascript:void(0)" class="evol-mdl-trigger" onclick="mustSignIn('로그인이 필요한 메뉴입니다.')" >
                @endif
                    <p>출금신청</p>
                </a>
            </li>
            <li>
                @auth()
                <a href="javascript:void(0)" class="evol-mdl-trigger" onclick="boardnotice(); closeMenu();" >
                @else
                <a href="javascript:void(0)" class="evol-mdl-trigger" onclick="mustSignIn('로그인이 필요한 메뉴입니다.')" >
                @endif
                    <p>공지사항</p>
                </a>
            </li>
            <li>
                @auth()
                <a href="javascript:void(0)" class="evol-mdl-trigger" onclick="boardperson(); closeMenu();" >
                @else
                <a href="javascript:void(0)" class="evol-mdl-trigger" onclick="mustSignIn('로그인이 필요한 메뉴입니다.')" >
                @endif
                    <p>1:1 문의</p>
                </a>
            </li>
            <li>
                @auth()
                <a href="javascript:void(0)" class="evol-mdl-trigger" onclick="closeMenu();" >
                @else
                <a href="javascript:void(0)" class="evol-mdl-trigger" onclick="mustSignIn('로그인이 필요한 메뉴입니다.')" >
                @endif
                    <p>이벤트</p>
                </a>
            </li>
        </ul>
        <div class="btn-grp">
          <div class="bg-cont ll"> 
            @if (Auth::check())
            <div class="signInInfo">
                {{auth()->user()->username}}<br>
                <span id="moneyTxt">보유머니&nbsp;</span><b><span class="userMoney">{{number_format(auth()->user()->balance)}}</span> 원</b><br>
                <span id="pointTxt">보너스&nbsp;</span><b><span class="userPoint" style="cursor: pointer;" onclick="moneyMove('point')">{{number_format(auth()->user()->deal_balance)}}</span> P</b>

            </div>               
            <button class="login-bg-btn" onclick="document.location.replace('/logout')">
                <img src="/frontend/iris/theme/sp/images/common/ico_login.png" style="-moz-transform: scaleX(-1);-o-transform: scaleX(-1);-webkit-transform: scaleX(-1);"> <span>로그아웃</span>
            </button>
            @else
            <button class="login-bg-btn login_btn" data-toggle="modal" data-target=".loginModal" id="loginBtn">
                    <img src="/frontend/iris/theme/sp/images/common/ico_login.png"> <span>로그인</span>
            </button>
            <button class="login-bg-btn" data-toggle="modal" data-target=".joinModal">
                <img src="/frontend/iris/theme/sp/images/common/ico_join.png"> <span>회원가입</span>
            </button>
            @endif
          </div>
        </div>
    </div>
</div>