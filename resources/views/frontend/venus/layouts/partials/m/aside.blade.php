<div class="aside2">
		<div class="aside_wrap">
			<div class="aside_top_wrap">
				<div class="aside_top_left">
					<a href="#"><img src="/frontend/venus/{{$logo}}.png?v=202302162217" width="135"></a>
				</div>
				<div data-dismiss="aside" class="aside_top_right"><img src="/frontend/kdior/images/m_close.png?v=202302162217" width="40"></div>
			</div> 
			<div class="aside2_box1_wrap">
				<div class="aside2_box1">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tbody><tr>
							<td align="center">
								<span class="font01">{{auth()->user()->username}}님</span>
								<a href="/logout"><span class="btn1_2">로그아웃</span></a>
							</td>
						</tr>                                 
					</tbody></table>
				</div>
			</div>     
			<div class="aside2_box2_wrap">       
				<div class="aside2_box2" style="margin:10px 0 0 0;">
					<div class="con_box10">
						<table width="100%" border="0" align="center" cellspacing="2" cellpadding="0">
							<tbody>
								<tr>
									<td width="10%" align="center"><a href="#" class="casino_3_open"><img src="/frontend/kdior/images/m_main_game3.png?v=202302021619" width="100%"></a></td>
									<td width="10%" align="center"><a href="#" 
									@if (isset($live) && $live=='disabled')
									onclick="alert('점검중입니다');"
									@else
									class="casino_1_open"
									@endif
									><img src="/frontend/kdior/images/m_main_game1.png?v=202302021619" width="100%"></a></a></td>
								</tr>
								</tr>	
								@if($logo != 'dorosi')
									{{--
									<td width="10%" align="center"><a href="#" 
									@if (isset($hotel) && $hotel=='disabled')
									onclick="alert('점검중입니다');"
									@else
									class="casino_2_open"
									@endif
									><img src="/frontend/kdior/images/m_main_game2.png?v=202302021619" width="100%"></a></td>--}}
									<td width="10%" align="center"><a href="#" 
									@if (isset($mini) && $mini=='disabled')
									onclick="alert('점검중입니다');"
									@else
									class="casino_4_open"
									@endif
									><img src="/frontend/kdior/images/m_main_game4.png?v=202302021619" width="100%"></a></td>
								@endif
								<td width="10%" align="center"><a href="#" 
								@auth
								{{$isSports = false}}
								@foreach($categories AS $index=>$category)
									@if ($category->type =='sports')
										@if ($category->status == 0)
											onclick="alert('점검중입니다');"
										@elseif ($category->view == 0)
											onclick="alert('지원하지 않는 게임입니다.');"
										@else
											onclick="startGameByProvider('bti', 'sports')"
										@endif 
											{{$isSports = true}} 
											@break
									@endif
								@endforeach
								@if(!$isSports)
									onclick="alert('지원하지 않는 게임입니다.');"
								@endif
								@else
								
								@endif
								><img src="/frontend/kdior/images/m_main_game5.png?v=202302021619" width="100%"></a></td>
								</tr>                                
						</tbody></table>                 	
					</div>                                    
					<div class="con_box10">
						<table width="100%" border="0" align="center" cellspacing="2" cellpadding="0">
							<tbody><tr>
								<td width="10%" align="center"><a href="#" class="sub_pop1_open"><span class="aside_btn1">입금신청</span></a></td>
								<td width="10%" align="center"><a href="#" class="sub_pop2_open"><span class="aside_btn1">출금신청</span></a></td>                  
							</tr> 
							<tr>	                            
								<td width="10%" align="center"><a href="#" class="sub_pop3_open"><span class="aside_btn1">공지사항</span></a></td>                                                                                              
                                <td width="10%" align="center"><a href="#" class="sub_pop5_open"><span class="aside_btn1">보너스</span></a></td>
							</tr> 
							<tr>	                                 
								<td width="10%" align="center"><a href="#" class="sub_pop6_open" onclick="support_detail();"><span class="aside_btn1">고객센터</span></a></td>                                                          
                                <td width="10%" align="center"><a href="#" class="sub_pop8_open" onclick="deposit_detail();"><span class="aside_btn1">충전내역</span></a></td>                          
							</tr> 
							<tr>	                
								
								<td width="10%" align="center"><a href="#" class="sub_pop9_open" onclick="withdraw_detail();"><span class="aside_btn1">환전내역</span></a></td>                            
                                <td width="10%" align="center"><a href="/logout"><span class="aside_btn1">로그아웃</span></a></td>
							</tr> 
						</tbody></table>                       
					</div>                                               
				</div>
			</div>
		</div>
	</div>
    <script src="/js/modal_alert.js?v=202302162217"></script>