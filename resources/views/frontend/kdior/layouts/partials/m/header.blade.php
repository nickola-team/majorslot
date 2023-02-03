<div id="header_wrap">
    @auth()
        <div class="top">
            <div class="m_logo"><a href="/"><img src="/frontend/kdior/images/logo.png?v=202302021619" width="130"></a></div>
        </div>
        <div class="my_wrap">
            <img src='/img/lv1.png' align='absmiddle' width='35'>&nbsp;test01님 &nbsp;&nbsp;
            캐쉬 <span class="font11">121,930</span> &nbsp;&nbsp; 
            콤프 <span class="font11">0</span> &nbsp;&nbsp; 
            <a href="/member/coupon_list.asp">쿠폰 <span class="font11">0</span></a>
        </div>
        <div class="top_menu">
            <ul>
                <li><a href="/"><img src="/frontend/kdior/images/m_main_game1.png?v=202302021619" width="100%"></a></li>
                <li><a href="/"><img src="/frontend/kdior/images/m_main_game2.png?v=202302021619" width="100%"></a></li>
                <li><a href="/"><img src="/frontend/kdior/images/m_main_game3.png?v=202302021619" width="100%"></a></li>                        
            </ul>                 	
        </div>       
    @else
        <div class="top">
            <div class="m_logo"><a href="/"><img src="/frontend/kdior/images/logo.png?v=202302021619" width="130"></a></div>
        </div>
        <div class="login">
            <ul>
                <li style="width:30%;margin-top:10px;"><input name="IU_ID" id="IU_ID" class="input_login" placeholder="아이디"></li>
                <li style="width:30%;margin-top:10px;"><input name="IU_PW" id="IU_PW" type="password" class="input_login" placeholder="비밀번호" onKeyPress="if(event.keyCode == 13) {loginChk();}"></li>
                <li style="width:20%"><a href="#" onclick="loginChk();"><span class="login_btn01">로그인</span></a></li>
            
                <li style="width:20%"><a href="/codePop.asp"><span class="login_btn02">회원가입</span></a></li>
            
            </ul>
        </div>  
    @endif
</div><!-- header_wrap -->