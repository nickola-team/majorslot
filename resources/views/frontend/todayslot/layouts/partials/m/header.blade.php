<div id="m_header_wrap" class="mainboard" style="display:block">
    <div class="top">
		<div class="m_logo">
			<a href="./">
                <img src="/frontend/todayslot/images/{{$logo}}_logo.png" >
			</a>
		</div>		
        <div class="m_menu"><a href="#" onclick="asidebar('open');"><img src="/frontend/todayslot/images/m_menu.png" width="40"></a></div>
    </div>   
	


	
	@auth()
	<div class="my_wrap" id="m_my_section">
		<div class="my">

				<a href="#" onclick="mtabActionProc('attendance_page'); getCustomerPage();"><img id="letterimg" src="/frontend/todayslot/images/letter_on.gif" width="16" height="16" border="0"><span id="letteralarm" style="font-size:11px"> {{$unreadmsg}}건</span>&nbsp;&nbsp;</a>

				
				<img src="https://img.asia8game.com/images/player_level/icon/UYWMD___.png" width="20"> 회원&nbsp;<span class="font01">{{Auth::user()->username}}님 </span> </img>
				
				<span style="float:right"><img src="/frontend/todayslot/images/ww_icon1.png" height="16"> 보유머니 <span class="font05" id="myHeaderWallet">{{ number_format(Auth::user()->balance,2) }}&nbsp;원</span> &nbsp;&nbsp;
				
				<!-- <img src="/frontend/todayslot/images/ww_icon2.png" height="16"> 보유포인트 <span class="font05">{{number_format(auth()->user()->deal_balance,0)}}&nbsp;</span></span>- -->

			</span>
		</div>
	</div>
	@else
	<div class="login" id="m_login_section">
		<ul>
			<li style="width:30%"><input name="login_id" id="login_id" type="text" class="input_login" placeholder="아이디"></li>
			<li style="width:30%"><input name="login_pw" id="login_pw" type="password" class="input_login" placeholder="비밀번호"></li>
			<li style="width:20%"><a href="#" onclick="loginSubmit();"><span class="login_btn01">로그인</span></a></li>
			<li style="width:20%"><a href="#" onclick="mtabActionProc('join_page');"><span class="login_btn02">회원가입</span></a></li>
		</ul>
	</div>  
	@endif


</div>


@auth
<!--My페이지-->
<div id="mytab_page" class="pagetab" style="display:none">
	<div id="contents_wrap">
		<div class="title_wrap">
			<div class="title">마이페이지</div>
		</div>       
		<div class="con_box10">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="write_title_top">
				<tbody>                  
				<tr>
					<td class="write_title" style="text-align:center">아이디</td>
					<td class="write_basic"><span class="font02">{{Auth::user()->username}}</span></td>
				</tr> 
				<tr>
					<td class="write_title" style="text-align:center">보유머니</td>
					<td class="write_basic"><span class="font02" id="myPageWallet">{{ number_format(Auth::user()->balance,2) }}&nbsp;원</span></td>
				</tr>                                                                                               
			</tbody></table>                
		</div>
	</div>
</div>

<!--입금신청-->
<div id="deposit_page" class="pagetab" style="display:none">
	<div id="contents_wrap">
		<div class="title_wrap">
			<div class="title">입금신청</div>
		</div>
		<div class="con_box10">
			<div class="info_wrap">
				<div class="info2">주의사항</div>
				<div class="info3">
					<span style="color:#ff0000"></span>
						- 수표입금시 입금처리 절대 되지 않습니다<br>
						- 23:50 ~ 00:30, 휴일 다음 첫 영업일 새벽에는 은행점검으로 인해 계좌이체가 지연될 수 있습니다.<br>
						- 위 시간 이외에도 몇몇 은행은 추가적 점검시간이 따로 있으니 이점 유념하시기 바랍니다.<br>
						- 10,000원 단위로 입금신청해주시기 바랍니다.<br>

				</div>
			</div>
		</div>     
		<div class="con_box10">
			<div class="money">
				<ul>
					<li>
						<img src="/frontend/todayslot/images/ww_icon.png" height="20"> 보유머니 : <span class="font05" id="myWalletPop">&nbsp;&nbsp;{{ number_format(Auth::user()->balance,2) }}&nbsp;원</span>
					</li>                            
					<li onclick="PointToMoney();">
						<img src="/frontend/todayslot/images/ww_icon2.png" height="26"> 보유포인트 : <span class="font05" id="myPointPop">&nbsp;&nbsp;{{number_format(auth()->user()->deal_balance,0)}}&nbsp;P</span>
					</li>     
					<li>
						<a href="#" onclick="getBalance();" style="margin:10px"><span class="m_btn1_2"><img src="/frontend/todayslot/images/icon_re.png" height="20"> 새로고침</span></a>
					<!--<a href="#none" onclick="ajaxCasBalance2('move');"><span class="btn1_2"><img src="images/icon_re.png" height="20"> 전체머니회수</span></a>-->
					</li>
				</ul>
			</div>                
		</div>	
		<div class="con_box10" id="fundFrm" name="fundFrm">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="write_title_top">
				<tbody><tr>
						<td class="write_title">아이디</td>
						@auth()
						<td class="write_basic"><input class="input1" id="name" name="name" size="30" value="{{ Auth::user()->username }}" readonly=""></td>
						@else
						<td class="write_basic"></td>
						@endif
					</tr>
					<input type="hidden" class="trade_type0_yn" id="trade_type0_yn" value="01">
					<tr>
						<td class="write_title">신청금액</td>
						
						<td class="write_basic">
							<input id="chargemoney" type="hidden" name="chargemoney" value="0">
							<input type="text" class="input1" name="charge_money" id="charge_money" onchange="numChk(this)" /> 
							<table width="100%" border="0" align="center" cellspacing="2" cellpadding="0">
								<tbody>
									<tr>
										<td width="10%" align="center"><a href="#" onclick="addMoneyDeposit(10000);"><span class="m_btn1_2">1만원</span></a></td>                
										<td width="10%" align="center"><a href="#" onclick="addMoneyDeposit(50000);"><span class="m_btn1_2">5만원</span></a></td>                
										<td width="10%" align="center"><a href="#" onclick="addMoneyDeposit(100000);"><span class="m_btn1_2">10만원</span></a></td>                
									</tr>            
									<tr>
										<td width="10%" align="center"><a href="#" onclick="addMoneyDeposit(500000);"><span class="m_btn1_2">50만원</span></a></td>                
										<td width="10%" align="center"><a href="#" onclick="addMoneyDeposit(1000000);"><span class="m_btn1_2">100만원</span></a> </td>                
										<td width="10%" align="center"><a href="#" onclick="addMoneyDeposit(5000000);"><span class="m_btn1_2">500만원</span></a></td>                
									</tr>
									<tr>
										<td width="10%" align="center"><a href="#" onclick="addMoneyDeposit(10000000);"><span class="m_btn1_2">1000만원</span></a></td>                
										<td width="10%" align="center"><a href="#" onclick="addMoneyDeposit();"><span class="m_btn1_1">정정</span></a><br></td> 								                         
									</tr>              
								</tbody>
							</table>
						</td>
					</tr> 
																
				</tbody>
			</table>                
		</div>
		<div class="con_box10">
			<div class="btn_wrap_center">
				<ul>
					<li style="width:30%"><a href="#"onclick="deposit();" style="width:30%"><span class="btn3_1" style="width:30%">본사쪽 입금하기</span></a></li>
					<li id="depositAccount" style="width:30%"><a id="btn_ask" href="#"onclick="askAccount();" style="width:30%"><span class="btn3_1" style="width:30%">계좌문의</span></a></li>
				</ul>
			</div>
		</div>
		<font size="" color="#ff00ff"><b>*최근 1주일</b></font>
		<div class="con_box10">
			<div class="deposit_history_scroll">
				<table class="history-table table" id="mydeposit" width="100%" border="0" cellspacing="0" cellpadding="0">
				
				</table>
			</div>
		</div> 
	</div>
</div>


<!--출금신청-->
<div id="withdraw_page" class="pagetab" style="display:none"> 
	<div id="contents_wrap">
		<div class="title_wrap">
			<div class="title">출금신청</div>
		</div>
		<div class="con_box10">
			<div class="info_wrap">
				<div class="info2">주의사항</div>
				<div class="info3">
					- 출금은 10,000원 단위로 출금신청 해주시기 바랍니다.<br>
					- 입금자명과 출금자명이 다를경우 본인확인 요청이 있을 수 있습니다.
				</div>
			</div>
		</div>        
		<div class="con_box10">
			<div class="money">
				<ul>                         
					<li><img src="/frontend/todayslot/images/ww_icon.png" height="26"> 보유머니 : <span class="font05" id="myWalletWithdraw">&nbsp;&nbsp;{{ number_format(Auth::user()->balance,2) }}&nbsp;원</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>               
					<li onclick="PointToMoney();"><img src="/frontend/todayslot/images/ww_icon2.png" height="26"> 보유포인트 : <span class="font05" id="myPointWithdraw">&nbsp;&nbsp;{{number_format(auth()->user()->deal_balance,0)}}</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li> 
					<li><a href="#" onclick="getBalance();"><span class="m_btn1_2"><img src="/frontend/todayslot/images/icon_re.png" height="20"> 새로고침</span></a> 
					<!--<a href="#" onclick="ajaxCasBalancePop('move');"><span class="btn1_2"><img src="images/icon_re.png" height="20"> 전체머니회수</span></a>-->
					</li>
				</ul>
			</div>                
		</div>
		<div class="con_box10" id="fundFrm" name="fundFrm">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="write_title_top">
				<tbody>
					<tr>
						<td class="write_title">신청금액</td>

						<td class="write_basic">
							<input id="exchangemoney" type="hidden" name="exchangemoney" value="0">
							<input type="text" class="input1" name="exchange_money" id="exchange_money" placeholder="0" onchange="comma()" />
							<table width="100%" border="0" align="center" cellspacing="2" cellpadding="0">
								<tbody>
									<tr>
										<td width="10%" align="center"><a href="#" onclick="addMoney(10000);"><span class="m_btn1_2">1만원</span></a></td>                
										<td width="10%" align="center"><a href="#" onclick="addMoney(50000);"><span class="m_btn1_2">5만원</span></a></td>                
										<td width="10%" align="center"><a href="#" onclick="addMoney(100000);"><span class="m_btn1_2">10만원</span></a></td>                
									</tr>            
									<tr>
										<td width="10%" align="center"><a href="#" onclick="addMoney(500000);"><span class="m_btn1_2">50만원</span></a> </td>                
										<td width="10%" align="center"><a href="#" onclick="addMoney(1000000);"><span class="m_btn1_2">100만원</span></a> </td>                
										<td width="10%" align="center"><a href="#" onclick="addMoney(5000000);"><span class="m_btn1_2">500만원</span></a> </td>                
									</tr>
									<tr>
										<td width="10%" align="center"><a href="#" onclick="addMoney(10000000);"><span class="m_btn1_2">1000만원</span></a> </td>                
										<td width="10%" align="center"><a href="#" onclick="resetMoney();"><span class="m_btn1_1">정정</span></a><br></td> 								                         
									</tr>              
								</tbody>
							</table> 							   
							
						</td>
					</tr>                                                            
				</tbody>
			</table>                
		</div>
		<div class="con_box10">
			<div class="btn_wrap_center">
				<ul>
					<li><a href="#" onclick="withdrawRequest();"><span class="btn3_1">출금신청하기</span></a></li>
				</ul>
			</div>
		</div>
		<font size="" color="#ff00ff"><b>*최근 1주일</b></font>
		<div class="con_box10 deposit_history_scroll">
			<table class="history-table table" id="mywithdraw" width="100%" border="0" cellspacing="0" cellpadding="0">
			
			</table>
		</div>
	</div>
</div>


<!--공지사항-->
<div id="notice_page" class="pagetab" style="display:none">
	<div id="contents_wrap">
		<div class="title_wrap">
			<div class="title">공지사항</div>
		</div>

		<div class="con_box05">   
			<div class="page_scroll">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tbody>
						<tr>
							<td width="10%" class="list_title1">번호</td>
							<td class="list_title1">제목</td>
						</tr>

						@foreach ($noticelist as $ntc)
							<tr>
								<th class="list_notice1"><img src="/frontend/todayslot/images/icon_notice.png"></th>
								<th class="list_notice1" style="color:#6e645b" onclick="navClick('msg-popup');setTab('notice-set','#msg-popup &gt; div.ngdialog-content &gt; div.ngdialog-customer-page.ngdialog-main-default-page.ng-scope &gt; ul &gt; li:nth-child(1)');getNotice('notice{{$ntc->id}}');">
								<span  class="ng-binding">{{$ntc->title}}</span>
								</th>
							</tr>
							<tr id="notice-set" class="list_notice2">              
									<tr ng-repeat="announce in filteredPage" class="list_notice2" id="notice{{$ntc->id}}" style="display:none;">
										<th colspan="3" class="list_notice2">
											<div class="list_notice2"><?php echo $ntc->content; ?>
											</div>
										</th>                                                    
									</tr>

						</tr>
						@endforeach
						
					</tbody>
				</table>
			</div>
		</div>                             
	</div>
</div>

<!--이벤트-->
<div id="event_page" class="pagetab" style="display:none">
	<div id="contents_wrap">
		<div class="title_wrap">
			<div class="title">이벤트</div>
		</div>

			<div class="con_box05">   
			<div class="contents_in">
				<div class="con_box10">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tbody><tr>
							<td width="20%" class="list_title1">번호</td>
							<td class="list_title1">제목</td>
						</tr>                                                                                    
					</tbody></table>
				</div>
			</div>
		</div>								
	</div>
</div>
		

<!-- 고객센터-->
<div id="attendance_page" class="pagetab" style="display:none">
	
	<div id="contents_wrap">
		<div class="title_wrap">
			<div class="title">
				고객센터
			</div>
		</div>

		<!-- <div class="con_box10">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tbody>
					<tr>
						<td width="10%" class="list_title1">번호</td>
						<td class="list_title1">제목</td>
					</tr>
				</tbody>
			</table>
		</div> -->
			<div id="customerlist" style="display:block">
				<div class="con_box10 customer_scroll">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tbody id="customerList">
							<tr class="table-border">
							<td class="list_title1" width="10%">제목</td>
							<td class="list_title1" width="10%">작성 일시</td>
							<td class="list_title1" width="10%">수신 일시</td>
							<td class="list_title1" width="10%">타입</td>
							</tr>
							<!-- ngRepeat: DirectMessage in filteredPage -->
						</tbody>
					</table>
				</div>
				<div class="con_box20">
					<div class="btn_wrap_center">
						<ul>
							<li><a href="#" onclick="customerRequest();"><span class="btn3_1">문의하기</span></a></li>
						</ul>
					</div>
				</div>
			</div>
			<div id="customerregist" style="display:none">

				<div class="con_box10" id="fundFrm" name="fundFrm">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="write_title_top">
						<tbody>                                            
							<tr>
								<td class="write_title">제목</td>
								@auth()
								<td class="write_basic"><input class="input1" id="txt_title" name="name" size="30" value="" placeholder="제목을 입력해주세요."></td>
								<td class="write_basic">
									<a href="#" onclick="backCustomerRequest();"><span class="back_btn"><img src="/frontend/todayslot/images/left.png" height="20"></span></a>
								</td>
								@else
								<td class="write_basic">
									<a href="#" onclick="backCustomerRequest();"><span class="back_btn"><img src="/frontend/todayslot/images/left.png" height="20"></span></a>
								</td>
								@endif
									
								
							</tr> 


							<input type="hidden" class="trade_type0_yn" id="trade_type0_yn" value="01">

							<tr>
								<td class="write_title">내용</td>
								
								<td class="write_basic">
									<textarea rows="4" cols="20" maxlength="300" class="inputcustomer font02" value="" placeholder="내용은 1000자 이하로 입력해주세요" id="content_txt" style="width: 100%;"></textarea>
								</td>
								<td class="write_basic"></td>
							</tr>  
						</tbody>
					</table>                
				</div>
				<div class="con_box20">
					<div class="btn_wrap_center">
						<ul>
							<li><a href="#" onclick="send_text();"><span class="btn3_1">작성 완료</span></a></li>
						</ul>
					</div>
				</div>
			</div>
	</div>
</div>
@else
<div id="join_page" class="pagetab" style="display:none">
	<div id="contents_wrap">
		<div class="title_wrap">
			<div class="title">회원가입</div>
		</div>     
		<form id="frm_join" name="frm_join">
			<input type="hidden" name="TOKEN" value="">
			<input type="hidden" name="CmdMode" value="92DEC5EABAB6BF16">
			<input type="hidden" name="gender" value="M">
			<div class="con_box05">
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
		</form>
	</div>
</div>

@endif


