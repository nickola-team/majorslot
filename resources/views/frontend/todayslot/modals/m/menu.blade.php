<!-- 우측메뉴-->
<div class="aside2" id="side_menu_bar" style="display: none;">
	<div class="aside_wrap">
		<div class="aside_top_wrap">
			<div class="aside_top_left">
				<a href="./"><img src="/frontend/todayslot/images/{{$logo}}_logo.png" width="110"></a>
			</div>
			<div data-dismiss="aside" class="aside_top_right"><img src="/frontend/todayslot/images/m_close.png" onclick="asidebar('close');" width="40"></div>
		</div> 
		@auth
		<div class="aside2_box1_wrap" id="m_my_section">
			<div class="aside2_box1">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-top:solid 1px rgba(0,0,0,0.99)">

				<tbody><tr>
					<td align="center" colspan="2"><img src="https://img.asia8game.com/images/player_level/icon/UYWMD___.png" width="20"><span class="font01">{{Auth::user()->username}}</span>님 환영합니다. 
					<!--<a href="?pid=transfer"><span class="btn1_1">지갑이동</span></a>-->
					
					<a href="#" onclick="javascript:goLogout();"><span class="btn1_2">로그아웃</span></a>
				</tr> 
							
				</tbody>
			</table>

					<!-- <a href="?pid=message"><img id="letterimg" src="/frontend/todayslot/images/letter_on.gif" width="16" height="16" border="0"><span id="letteralarm" style="font-size:11px"> {{$unreadmsg}}건</span>&nbsp;&nbsp;</a>

					
					<img src="https://img.asia8game.com/images/player_level/icon/UYWMD___.png" width="20"> 회원&nbsp;<span class="font01">{{Auth::user()->username}}님 </span> </img>
					
					<span style="float:right"><img src="/frontend/todayslot/images/ww_icon1.png" height="16"> 보유머니 <span class="font05">{{ number_format(Auth::user()->balance,2) }}&nbsp;원</span> &nbsp;&nbsp;
					 -->
					<!-- <img src="/frontend/todayslot/images/ww_icon2.png" height="16"> 보유포인트 <span class="font05">{{number_format(auth()->user()->deal_balance,0)}}&nbsp;</span></span>- -->

				</span>
			</div>
		</div>
		@else
		<div class="aside2_box1_wrap">
			<div class="aside2_box1">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-top:solid 1px rgba(0,0,0,0.99)">
					<tr>
						<td align="center" style="color:#edddd8;">로그인 후 이용해주세요</td>
					</tr>

				</table>
			</div>
		</div>  
		@endif
		      
		<div class="aside2_box2_wrap">
			<div class="aside2_box2">                        
                <div class="con_box00">
                    <table width="100%" border="0" align="center" cellspacing="2" cellpadding="0">  
					@auth()
						<tr>
                            <td width="10%" align="center"><a href="#" onclick="mtabActionProc('mytab_page');"><span class="aside_btn1">MY페이지</span></a></td>
                            <td width="10%" align="center"><a href="#" onclick="mtabActionProc('deposit_page'); mydepositlist(); mywithdrawlist();"><span class="aside_btn1">입금신청</span></a></td>                  
                        </tr> 
                        <tr>	                            
                            <td width="10%" align="center"><a href="#" onclick="mtabActionProc('withdraw_page'); mywithdrawlist(); mydepositlist();"><span class="aside_btn1">출금신청</span></a></td>                                                                                              
                            <td width="10%" align="center"><a href="#" onclick="mtabActionProc('notice_page');"><span class="aside_btn1">공지사항</span></a></td>
                        </tr> 
                        <tr>	                                 
                            <td width="10%" align="center"><a href="#" onclick="mtabActionProc('event_page');"><span class="aside_btn1">이벤트</span></a></td>
                            <td width="10%" align="center"><a href="#" onclick="mtabActionProc('attendance_page'); getCustomerPage();"><span class="aside_btn1">고객센터</span></a></td>
                        </tr> 
					@else
						<tr>
                            <td width="10%" align="center"><a href="#" onclick="showLoginAlert();"><span class="aside_btn1">MY페이지</span></a></td>
                            <td width="10%" align="center"><a href="#" onclick="showLoginAlert();"><span class="aside_btn1">입금신청</span></a></td>                  
                        </tr> 
                        <tr>	                            
                            <td width="10%" align="center"><a href="#" onclick="showLoginAlert();"><span class="aside_btn1">출금신청</span></a></td>                                                                                              
                            <td width="10%" align="center"><a href="#" onclick="showLoginAlert();"><span class="aside_btn1">공지사항</span></a></td>
                        </tr> 
                        <tr>	                                 
                            <td width="10%" align="center"><a href="#" onclick="showLoginAlert();"><span class="aside_btn1">이벤트</span></a></td>
                            <td width="10%" align="center"><a href="#" onclick="showLoginAlert();"><span class="aside_btn1">고객센터</span></a></td>
                        </tr> 
					@endif                   
                        
                        


                    </table>                       
                </div>
			</div>
		</div>
	</div>
</div>
