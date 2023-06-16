<!-- 슬롯게임상세 -->
<div id="etc_pop1" class="etc_pop1 popup_style04 popup_none">
    <div class="popup_wrap_1360">   
        <div class="close_box"><a href="#" class="etc_pop1_close"><img src="/frontend/kdior/images/popup_close.png"></a></div>
        <div class="popupbox">
            <div class="title1" id="pop1_title">슬롯게임</div><!-- 타이틀 -->
            <div class="game" id="pop1_content">
            </div>
        </div>
    </div>
</div>
<!-- 입금신청 -->
<div id="sub_pop1" class="popup_style04 popup_none">
    <div class="popup_wrap_1000">   
        <div class="close_box"><a href="#" class="sub_pop1_close"><img src="/frontend/kdior/images/popup_close.png"></a></div>
        <div class="popupbox">
            <form name="charge_frm" method="post">
            <div class="title1">입금신청</div><!-- 타이틀 -->
            <div class="con_box10">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="write_title_top">  
                    <tr>
                        <td class="write_title">입금계좌</td>
                        <td class="write_basic"><a href="#"><span class="btn1_1" onclick="requestAccount();">입금계좌문의</span></a></td>
                    </tr> 
                    <tr>
                        <td class="write_title">충전금액</td>
                        <td class="write_basic">
                            <input name="IC_Amount" id="IC_Amount" value="0" class="input1"><br>
                            <a href="#"><span class="btn1_2" onclick="moneyadd('10,000');">1만원</span></a>  
                            <a href="#"><span class="btn1_2" onclick="moneyadd('50,000');">5만원</span></a> 
                            <a href="#"><span class="btn1_2" onclick="moneyadd('100,000');">10만원</span></a> 
                            <a href="#"><span class="btn1_2" onclick="moneyadd('500,000');">50만원</span></a> 
                            <a href="#"><span class="btn1_2" onclick="moneyadd('1,000,000');">100만원</span></a> 
                            <a href="#"><span class="btn1_2" onclick="moneyadd('5,000,000');">500만원</span></a> 
                            <a href="#"><span class="btn1_1" onclick="moneyadd('0');">정정</span></a>
                        </td>
                    </tr>                   							
                </table>                
            </div> 
            <div class="con_box10">
                <div class="btn_wrap_center">
                    <ul>
                        <li><a href="#" onclick="depositChk();"><span class="btn3_1">입금신청</span></a></li>
                    </ul>
                </div>
            </div>  
            </form>
        </div>
    </div>
</div>

<!-- 출금신청 -->
<div id="sub_pop2" class="sub_pop2 popup_style04 popup_none">
    <div class="popup_wrap_1000">   
        <div class="close_box"><a href="#" class="sub_pop2_close"><img src="/frontend/kdior/images/popup_close.png"></a></div>
        <div class="popupbox">
            <form method="post" name="exchange_frm">
            <div class="title1">출금신청</div><!-- 타이틀 -->
            <div class="con_box10">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="write_title_top">  
                    <tbody>
                    <tr>
                        <td class="write_title">보유금액</td>
                        <td class="write_basic"><input class="input1" value="{{number_format(auth()->user()->balance)}}" readonly=""></td>
                    </tr> 
                                                                                                                                                                                                
                    <tr>
                        <td class="write_title">출금금액</td>
                        <td class="write_basic">
                            <input name="IE_Amount" id="IE_Amount" class="input1" value="0"><br>
                            <a href="#"><span class="btn1_2" onclick="moneyadd2('10,000');">1만원</span></a>  
                            <a href="#"><span class="btn1_2" onclick="moneyadd2('50,000');">5만원</span></a> 
                            <a href="#"><span class="btn1_2" onclick="moneyadd2('100,000');">10만원</span></a> 
                            <a href="#"><span class="btn1_2" onclick="moneyadd2('500,000');">50만원</span></a> 
                            <a href="#"><span class="btn1_2" onclick="moneyadd2('1,000,000');">100만원</span></a> 
                            <a href="#"><span class="btn1_2" onclick="moneyadd2('5,000,000');">500만원</span></a> 
                            <a href="#"><span class="btn1_1" onclick="moneyadd2('0');">정정</span></a>
                        </td>
                    </tr> 
                </tbody>
                </table>                
            </div> 
            <div class="con_box10">
                <div class="btn_wrap_center">
                    <ul>
                        <li><a href="#" onclick="withdrawChk();"><span class="btn3_1">출금신청</span></a></li>
                    </ul>
                </div>
            </div> 
            </form>
        </div>
    </div>
</div>

<!-- 공지사항 -->
<div id="sub_pop3" class="popup_style04 popup_none">
    <div class="popup_wrap_1000">   
        <div class="close_box"><a href="#" class="sub_pop3_close"><img src="/frontend/kdior/images/popup_close.png"></a></div>
        <div class="popupbox">
            <div class="title1">공지사항</div><!-- 타이틀 -->
            <div class="con_box10">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="10%" class="list_title1">번호</td>
                        <td class="list_title1">제목</td>
                        <td width="12%" class="list_title1">작성자</td>                  
                        <td width="12%" class="list_title1">날짜</td>
                    </tr>
                    @if (count($noticelist) > 0)
                    @foreach ($noticelist as $ntc)
                        <tr>
                            <td class="list_notice1"><img src="/frontend/kdior/images/icon_notice.png"></td>
                            <td class="list_notice2"><a href="#" onclick="showBoard('board{{$ntc->id}}')">{{$ntc->title}}</a></td>
                            <td class="list_notice1">관리자</td>
                            <td class="list_notice1">{{date('Y-m-d', strtotime($ntc->date_time))}}</td>       
                        </tr> 
                        <tr id="board{{$ntc->id}}" style="display:none;">
                            <td class="list_notice1"></td>
                            <td class="list_notice2" colspan="3"><?php echo $ntc->content; ?></td>
                        </tr> 
                    @endforeach
                    @else
                    <tr>
                        <td class="list_notice1"></td>
                        <td class="list_notice2" colspan="3">공지사항이 없습니다</td>
                    </tr>
                    @endif

                </table>
            </div> 
        </div>
    </div>
</div>


<!-- 보너스 -->

<div id="sub_pop5" class="popup_style04 popup_none">
    <div class="popup_wrap_1000">   
        <div class="close_box"><a href="#" class="sub_pop5_close"><img src="/frontend/kdior/images/popup_close.png"></a></div>
        <div class="popupbox">
            <div class="title1">보너스</div><!-- 타이틀 -->
            <form method="post" name="frm">
            <div class="con_box10">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="write_title_top">  
                    <tr>
                        <td class="write_title">아이디</td>
                        <td class="write_basic">{{auth()->user()->username}}</td>
                    </tr> 
                    <tr>
                        <td class="write_title">보유보너스</td>
                        <td class="write_basic"><span class="font11">{{number_format(auth()->user()->deal_balance)}}</span> P</td>
                    </tr>                     							
                </table>                
            </div> 
            <div class="con_box10">
                <div class="btn_wrap_center">
                    <ul>
                        <li><a href="#" onclick="compExchange();"><span class="btn3_1">보너스사용</span></a></li>
                    </ul>
                </div>
            </div>   
            </form>
        </div>
    </div>
</div>

<!-- 고객센터 -->
<div id="sub_pop6" class="sub_pop6 popup_style04 popup_none">
    <div class="close_box"><a href="#" class="sub_pop6_close"><img src="/frontend/kdior/images/popup_close.png"></a></div>
    <div class="popupbox">
        <div class="title1">고객센터</div><!-- 타이틀 -->
        <div class="con_box10" id="sub_pop6_content">
        </div> 
        <div class="con_box10">
            <div class="btn_wrap_right">
                <ul>
                    <li><a href="#" class="sub_pop6_close" onclick="requestAccount();"><span class="btn2_1">빠른계좌 문의하기</span></a></li>
                    <li><a href="#" class="sub_pop62_open sub_pop6_close"><span class="btn2_1">문의하기</span></a></li>
                </ul>
            </div>
        </div>    
    </div>
</div>
	
<!-- 고객센터 쓰기 -->
<div id="sub_pop62" class="popup_style04 popup_none">
    <div class="popup_wrap_1000">   
        <div class="close_box"><a href="#" class="sub_pop62_close"><img src="/frontend/kdior/images/popup_close.png"></a></div>
        <div class="popupbox">
            <div class="title1">고객센터</div><!-- 타이틀 -->
            <form name="FreContent" method="post">
                <input type="hidden" name="EMODE" value="QNAADD">
                <input type="hidden" name="BC_WRITER" value="test01">
            <div class="con_box10">        
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="write_title_top">
                    <tr>
                        <td class="write_title">제목</td>
                        <td class="write_basic"><input id="BC_TITLE" name="BC_TITLE" class="input1" size="115" style="width:100%"></td>
                    </tr>
                    <tr>
                        <td class="write_title">내용</td>
                        <td class="write_basic"><textarea id="BC_CONTENT" name="BC_CONTENT" cols="115" rows="15" class="input2" style="width:100%"></textarea></td>
                    </tr>
                </table>
            </div>    
            <div class="con_box10">
                <div class="btn_wrap_center">
                    <ul>
                        <li><a href="#"><span class="btn2_1" onclick="FreAdd();">확인</span></a></li>                                                             
                        <li><a href="#"><span class="btn2_2 sub_pop62_close">취소</span></a></li>                                                                         
                    </ul>
                </div>
            </div>  
            </form>
        </div>
    </div>
</div>


<!-- 충전내역 -->
<div id="sub_pop8" class="popup_style04 popup_none">
    <div class="popup_wrap_1000">   
        <div class="close_box"><a href="#" class="sub_pop8_close"><img src="/frontend/kdior/images/popup_close.png"></a></div>
        <div class="popupbox">
            <div class="title1">충전내역</div><!-- 타이틀 -->
            <div class="con_box10" id="sub_pop8_content">
            </div>      
        </div>
    </div>
</div>

<!-- 환전내역 -->
<div id="sub_pop9" class="popup_style04 popup_none">
    <div class="popup_wrap_1000">   
        <div class="close_box"><a href="#" class="sub_pop9_close"><img src="/frontend/kdior/images/popup_close.png"></a></div>
        <div class="popupbox">
            <div class="title1">환전내역</div><!-- 타이틀 -->
            <div class="con_box10"  id="sub_pop9_content">
            </div>      
        </div>
    </div>
</div>
