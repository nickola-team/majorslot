<div id="header_wrap">
        <div class="night">
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
        </div>
    @auth()

        <div class="top">
            <div class="m_logo"><a href="/"><img src="/frontend/venus/{{$logo}}.png?v=202302021619" width="130"></a></div>
            <div class="m_menu"><a href="#" onclick="$('.aside2').asidebar('open')"><img src="/frontend/kdior/images/m_menu.png" width="40"></a></div>
        </div>
        <div class="my_wrap">
            <img src='/frontend/kdior/img/lv1.png' align='absmiddle' width='35'>&nbsp;{{auth()->user()->username}}님 &nbsp;&nbsp;
            캐쉬 <span class="font11">{{number_format(auth()->user()->balance)}}</span> &nbsp;&nbsp; 
            보너스 <span class="font11">{{number_format(auth()->user()->deal_balance)}}</span> &nbsp;&nbsp; 
        </div>
        
    @else
        <div class="top">
            <div class="m_logo"><a href="/"><img src="/frontend/venus/{{$logo}}.png?v=202302021619" width="130"></a></div>
        </div>
        <div class="login">
            <ul>
                <form method="post" name="LoginFrm" id="frmLogin">
                <li style="width:30%;margin-top:10px;"><input name="IU_ID" id="IU_ID" class="input_login" placeholder="아이디"></li>
                <li style="width:30%;margin-top:10px;"><input name="IU_PW" id="IU_PW" type="password" class="input_login" placeholder="비밀번호" onKeyPress="if(event.keyCode == 13) {loginChk();}"></li>
                <li style="width:20%"><a href="#" onclick="loginChk();"><span class="login_btn01">로그인</span></a></li>
            
                <li style="width:20%"><a href="#" class="etc_pop3_open"><span class="login_btn02">회원가입</span></a></li>
                </form>
            
            </ul>
        </div>
    
    @endif
    <div class="top_menu">
        <ul>
        <li style='width:50%'><a href="#" class="casino_3_open"><img src="/frontend/amor/images/m_main_game3.png?v=202302021619" width="100%"></a></li>
            <li style='width:50%'><a href="#" 
            @if (isset($live) && $live=='disabled')
                onclick="alert('점검중입니다');"
            @else
                class="casino_1_open"
            @endif
            ><img src="/frontend/amor/images/m_main_game1.png?v=202302021619" width="100%"></a></li>
        </ul>
    </div>       
</div><!-- header_wrap -->