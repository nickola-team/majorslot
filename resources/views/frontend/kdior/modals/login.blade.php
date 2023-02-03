

<!-- 로그인 -->
<div id="etc_pop2" class="popup_style04 popup_none">
    <div class="popup_wrap_login">   
        <div class="close_box"><a href="#" class="etc_pop2_close"><img src="/frontend/kdior/images/popup_close.png?v=202301301150"></a></div>
        <div class="popup_box_login">
            <form method="post" name="LoginFrm" id="frmLogin">
            <input type="hidden" value="<?= csrf_token() ?>" name="_token" id="_token">
            <div class="popup_login">
                <ul>
                    <li><img src="/frontend/kdior/images/login_title.png?v=202301301150"></li>                                    
                    <li><input name="IU_ID" id="IU_ID" type="text" class="input_popup_login" placeholder="아이디"></li>                    
                    <li><input name="IU_PW" id="IU_PW" type="password" class="input_popup_login" placeholder="비밀번호" onKeyPress="if(event.keyCode == 13) {loginChk();}"></li>
                    <li><a href="#" onclick="loginChk();"><span class="popup_login_btn1">로그인</span></a></li>       
                    
                    <li><a href="#" class="etc_pop3_open etc_pop2_close"><span class="popup_login_btn2">회원가입</span></a></li> 
                                                        
                </ul>
            </div>  
            </form>
        </div>
    </div>
</div>
