<div id="etc_pop1" style="display: none;">
	<div class="popup_wrap">   
		<div class="close_box">
            <a href="#" class="etc_pop1_close" onclick="joinClose();">
                <img src="/frontend/todayslot/images/popup_close.png">
            </a>
        </div>
		<div class="popupbox" id="popupbox_ajax">
            <form id="frm_join" name="frm_join">
                <input type="hidden" name="TOKEN" value="">
                <input type="hidden" name="CmdMode" value="92DEC5EABAB6BF16">
                <input type="hidden" name="gender" value="M">
                <div class="title1" style="text-align:center;color:#ffba0a">
                    회원가입
                </div>
                <div class="contents_in_2">                            
                    <div class="con_box10">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="write_title_top">                            
                            <tbody>
                                <tr>
                                    <td class="write_title">아이디</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic"><input class="input1" name="username" type="text" id="username"> <a href="#" onclick="javascript:checkid();"><span class="btn1_1">중복확인</span></a>
                                        <span>
                                            &nbsp;&nbsp;
                                                    *회원아이디는 3자이상 10자이하로 입력하세요.
                                        </span>
                                    </td>                                
                                </tr> 

                                <tr>
                                    <td class="write_title">비밀번호</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic"><input class="input1" name="password" type="password" id="password">
                                        <span>
                                            &nbsp;&nbsp;
                                                    
                                                    *비밀번호는 4자이상 12자이하로 입력하세요.
                                        </span>
                                    </td>                                    
                                </tr>
                                
                                <tr>
                                    <td class="write_title">비밀번호 확인</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic"><input class="input1" name="password1" type="password" id="password1"></td>
                                </tr> 
                                
                                <tr>
                                    <td class="write_title"></td>
                                    <td class="write_td"></td>
                                    <td class="write_basic">*반드시 본인의 핸드폰번호를 정확하게 입력하시기 바랍니다.</td>
                                </tr>

                                <tr>
                                    <td class="write_title">핸드폰</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic">
                                        <select class="input1" name="tel1" id="tel1">
                                            <option value="010" selected="">010</option>
                                            <option value="011">011</option>
                                            <option value="016">016</option>
                                            <option value="017">017</option>
                                            <option value="018">018</option>
                                            <option value="019">019</option>
                                        </select> 
                                        - <input class="input1" size="8" type="text" name="tel2" id="tel2" maxlength="4"> - <input class="input1" size="8" type="text" name="tel3" id="tel3" maxlength="4">
                                        <!-- <span>
                                            &nbsp;&nbsp;                                                    
                                                    *입금하시는 정확한 계좌를 입력해주셔야 출금시 같은계좌로 정상적으로 처리됩니다.                                                
                                        </span> -->
                                    </td>
                                </tr>    

                                <tr>
                                    <td class="write_title">은행명</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic">
                                        <input class="input1" name="bank_name" type="text" id="bank_name" maxlength="20" placeholder="*은행명">
                                    </td>
                                </tr> 

                                <tr>
                                    <td class="write_title">예금주</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic"><input class="input1" name="recommender" type="text" id="recommender" maxlength="4" onkeypress="return digit_check(event)" placeholder="*예금주"></td>
                                </tr> 

                                <tr>
                                    <td class="write_title">계좌번호</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic">
                                        <input class="input1" name="account_no" type="text" id="account_no" placeholder="*계좌번호">
                                    </td>
                                </tr>    

                                <tr>
                                    <td class="write_title">추천인코드</td>
                                    <td class="write_td"></td>
                                    <td class="write_basic">
                                        <input class="input1" name="friend" type="text" id="friend">                                    
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="con_box10">
                        <div class="btn_wrap_center">
                            <ul>
                                <li><a href="#" onclick="Regist(frm_join);"><span class="btn3_1">가입하기</span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>    
            </form>
        </div>
	</div>
</div>