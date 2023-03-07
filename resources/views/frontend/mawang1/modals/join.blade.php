<!-- 회원가입 -->
<div id="etc_pop3" class="etc_pop3 popup_style04 popup_none">
<div class="popup_wrap_1000">   
			<form name="join_frm" method="post">
            <input type="hidden" name="ChkID" value="0">
			<div class="close_box"><a href="#" class="etc_pop3_close"><img src="/frontend/kdior/images/popup_close.png"></a></div>
			<div class="popupbox">
				<div class="title1">회원가입</div><!-- 타이틀 -->
				<div class="con_box10">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="write_title_top">                    
						<tr>
							<td class="write_title">아이디</td>
							<td class="write_basic"><input id="IU_ID" name="IU_ID" class="input1"> 
                            <a href="#" onclick="idDblChk();"><span class="btn1_1">중복확인</span></a></td>
						</tr>                 
						<tr>
							<td class="write_title">비밀번호</td>
							<td class="write_basic"><input id="IU_PW" name="IU_PW" type="password" class="input1"  placeholder="6자리 이상"></td>
						</tr>    
						<tr>
							<td class="write_title">비밀번호 확인</td>
							<td class="write_basic"><input id="IU_PW1" name="IU_PW1" type="password" class="input1" placeholder="비밀번호와 동일"></td>
						</tr> 

						<tr>
							<td class="write_title">휴대폰 번호</td>
							<td class="write_basic"><input id="IU_Mobile" name="IU_Mobile" class="input1"></td>
						</tr>
						<tr>
							<td class="write_title">은행명</td>
							<td class="write_basic">
								<select name="IU_BankName" id="IU_BankName" required  class="input1" title="은행선택">
								<option value="">선택</option>
								<?php
                                    $banks = \VanguardLTE\User::$values['banks'];
                                    foreach ($banks as $b)
                                    {
                                        echo '<option value="'.$b.'">'.$b.'</option>';
                                    }

                                ?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="write_title">예금주</td>
							<td class="write_basic"><input id="IU_BankOwner" name="IU_BankOwner" class="input1" placeholder="2자이상 한글, 영문만 가능"><p> (입금과 출금시 사용하시는 실제 예금주명으로 기입하여 주시기 바랍니다)</td>
						</tr>

						<tr>
							<td class="write_title">계좌번호</td>
							<td class="write_basic"><input id="IU_BankNum" name="IU_BankNum" class="input1"></td>
						</tr>                                                                                
						
                        <tr style="display:none;">
							<td class="write_title">추천인ID</td>
							<td class="write_basic"><input id="recom_id" name="recom_id" class="input1" value="바카라장군"></td>
						</tr>

					</table>
				</div>
				<div class="con_box10">
					<div class="btn_wrap_center">
						<ul>
							<li><a href="#"><span class="btn3_1" onclick="joinChk();">회원가입완료</span></a></li>
						</ul>
					</div>
				</div>     
			</div>
			</form>
		</div>
</div>

		
