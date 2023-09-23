<!-- 마이페이지, 정보수정, 입금/출금내역, 지인친구목록, 출석체크목록, 머니이동목록, 쪽지함 -->
<div id="sub_pop2" class="popup_style04 popup_none" style="display: none;">
	<div class="popup_wrap">
		<div class="close_box"><a href="#" class="sub_pop2_close" onclick="myPageClose();"><img src="/frontend/todayslot/images/popup_close.png"></a></div>
		<div class="popupbox">            
			<div class="popup_tab_wrap" margin="auto">
				<ul class="popup_tab popup_tab2">
					<li class="tab1 sk_tab_active_02" data-target="#sk_tab_con_02_1">
                        @auth()
                        <a href="javascript:;" onClick="tabActionProc('tab1', 'mytab_page');"><span style="font-size:16px">MY페이지</span></a>
                        @else
                        <a href="javascript:;" onClick="showLoginAlert();"><span style="font-size:16px">MY페이지</span></a>
                        @endif
                    </li>
					<li class="tab2" data-target="#sk_tab_con_02_1">
                        @auth()
                        <a href="javascript:;" onClick="tabActionProc('tab2', 'deposit_page');mydepositlist(); mywithdrawlist();"><span style="font-size:16px">입금신청</span></a>
                        @else
                        <a href="javascript:;" onClick="showLoginAlert();"><span style="font-size:16px">입금신청</span></a>
                        @endif                        
                    </li>
					<li class="tab3" data-target="#sk_tab_con_02_1">
                        @auth()
                        <a href="javascript:;" onClick="tabActionProc('tab3', 'withdraw_page'); mywithdrawlist(); mydepositlist();"><span style="font-size:16px">출금신청</span></a>
                        @else
                        <a href="javascript:;" onClick="showLoginAlert();"><span style="font-size:16px">출금신청</span></a>
                        @endif                         
                    </li>
					<li class="tab4" data-target="#sk_tab_con_02_1">
                        @auth()
                        <a href="javascript:;" onClick="tabActionProc('tab4', 'notice_page');"><span style="font-size:16px">공지사항</span></a>
                        @else
                        <a href="javascript:;" onClick="showLoginAlert();"><span style="font-size:16px">공지사항</span></a>
                        @endif 
                    </li>
                    <li class="tab5" data-target="#sk_tab_con_02_1">
                        @auth()
                        <a href="javascript:;" onClick="tabActionProc('tab5', 'event_page');"><span style="font-size:16px">이벤트</span></a>
                        @else
                        <a href="javascript:;" onClick="showLoginAlert();"><span style="font-size:16px">이벤트</span></a>
                        @endif 
                        
                    </li>  
					<li class="tab6" data-target="#sk_tab_con_02_1">
                        @auth()
                        <a href="javascript:;" onClick="tabActionProc('tab6', 'attendance_page');getCustomerPage();"><span style="font-size:16px">고객센터</span></a>
                        @else
                        <a href="javascript:;" onClick="showLoginAlert();"><span style="font-size:16px">고객센터</span></a>
                        @endif 
                        
                    </li>                                                                                
				</ul>
			</div> 
            @auth
                
                <div class="page_tab_wrap" margin="auto">
                    <ul class="page_tab page_tab2">
                        <!-- 마이페이지 -->
                        <div id="mytab_page" class="pagetab" style="display:block">
                            <div class="title1" style="text-align:center;color:#ffba0a">MY페이지</div>
                            <div class="con_box10">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="write_title_top">
                                    <tbody>
                                        <tr>
                                            <td class="write_title">아이디</td>
                                            @auth()
                                            <td class="write_basic">{{ Auth::user()->username }}</td>
                                            @else
                                            <td class="write_basic"></td>
                                            @endif
                                            <tr>
                                            <td class="write_title">보유머니</td>
                                            @auth()
                                            <td class="write_basic"><span id="myPageWallet">{{ number_format(Auth::user()->balance,2) }}&nbsp;원</span></td>
                                            @else
                                            <td class="write_basic"></td>
                                            @endif
                                            <tr>
                                        </tr> 
                                    </tbody>
                                </table>                
                            </div>
                        </div>

                        <!-- 입금신청 -->
                        <div id="deposit_page" class="pagetab" style="display:none">
                            <div class="title1" style="text-align:center;color:#ffba0a">입금신청</div>
                            <div class="con_box10">
                                <div class="info_wrap">
                                    <div class="info2">주의사항</div>
                                    <div class="info3" style="font-size:15px">
                                        - 수표입금시 입금처리 절대 되지 않습니다<br>
                                        - 23:50 ~ 00:30, 휴일 다음 첫 영업일 새벽에는 은행점검으로 인해 계좌이체가 지연될 수 있습니다.<br>
                                        - 위 시간 이외에도 몇몇 은행은 추가적 점검시간이 따로 있으니 이점 유념하시기 바랍니다.<br>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="con_box10">
                                <div class="money">
                                    <ul>
                                        <li style="width:250px; text-align:left;">
                                        <img src="/frontend/todayslot/images/ww_icon.png" height="26"> 보유머니 : <span class="font05" id="myWalletPop">&nbsp;&nbsp;{{ number_format(Auth::user()->balance,2) }}&nbsp;원</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
                                        <li style="width:250px; text-align:left;"> <img src="/frontend/todayslot/images/ww_icon2.png" height="26"> 보유포인트 : <span class="font05" id="myPointPop">&nbsp;&nbsp;{{number_format(auth()->user()->deal_balance,0)}}</span>&nbsp;</li>  
                                        <li><a href="#" onclick="getBalance();"><span class="btn1_2"><img src="/frontend/todayslot/images/icon_re.png" height="20"> 새로고침</span></a> </li><li>                            
                                        <input type="hidden" class="walletYn" id="walletYn" value="Y">
                                        <input type="hidden" class="offerGameCode" id="offerGameCode" value="999">               
                                        </li>
                                    </ul>
                                </div>                
                            </div>



                            <div class="con_box10" id="fundFrm" name="fundFrm">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="write_title_top">
                                    <tbody>
                                        <tr>
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
                                                <a href="#" onclick="addMoneyDeposit(10000);"><span class="btn1_2">1만원</span></a>  <a href="#" onclick="addMoneyDeposit(50000);"><span class="btn1_2">5만원</span></a> <a href="#" onclick="addMoneyDeposit(100000);"><span class="btn1_2">10만원</span></a> <a href="#" onclick="addMoneyDeposit(500000);"><span class="btn1_2">50만원</span></a> <a href="#" onclick="addMoneyDeposit(1000000);"><span class="btn1_2">100만원</span></a> <a href="#" onclick="addMoneyDeposit(5000000);"><span class="btn1_2">500만원</span></a> <a href="#" onclick="addMoneyDeposit(10000000);"><span class="btn1_2">1000만원</span></a> <a href="#" onclick="addMoneyDeposit();"><span class="btn1_1">정정</span></a><br>10,000원 단위로 입금신청해주시기 바랍니다.</td>
                                        </tr>  
                                    </tbody>
                                </table>                
                            </div>


                            <div class="con_box20">
                                <div class="btn_wrap_center">
                                    <ul>
                                        <li style="width:30%"><a href="#"onclick="deposit();" style="width:30%"><span class="btn3_1" style="width:30%">본사쪽 입금하기</span></a></li>
                                        <li id="depositAccount" style="width:30%"><a id="btn_ask" href="#"onclick="askAccount();" style="width:30%"><span class="btn3_1" style="width:30%">계좌문의</span></a></li>
                                    </ul>
                                </div>
                            </div>

                            <font size="" color="#ff00ff"><b>*최근 1주일</b></font>
                            <div class="con_box10 deposit_history_scroll">
                                <table class="history-table table" id="mydeposit" width="100%" border="0" cellspacing="0" cellpadding="0">
                                
                                </table>
                            </div>
                            
                        </div>

                        <!-- 출금신청 -->
                        <div id="withdraw_page" class="pagetab" style="display:none">
                            <div class="title1" style="text-align:center;color:#ffba0a">출금신청 <!--<span style="font-size:14px; color:#ff00f6">&nbsp;&nbsp;&nbsp;&nbsp;* 출금하기전에 전체머니회수 처리하시고 신청하기 바랍니다. *</span>-->
                            </div>

                            <div class="con_box10">
                                <div class="info_wrap">
                                    <div class="info2">주의사항</div>
                                        <div class="info3" style="font-size:15px">
                                            - 출금은 10,000원 단위로 출금신청 해주시기 바랍니다.<br>
                                            - 입금자명과 출금자명이 다를경우 본인확인 요청이 있을 수 있습니다.
                                        </div>
                                </div>
                            </div>

                            <div class="con_box10">
                                <div class="money">
                                    <ul>                         
                                        <li style="width:250px; text-align:left;"><img src="/frontend/todayslot/images/ww_icon.png" height="26"> 보유머니 : <span class="font05" id="myWalletWithdraw">&nbsp;&nbsp;{{ number_format(Auth::user()->balance,2) }}&nbsp;원</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>               
                                        <li style="width:250px; text-align:left;"><img src="/frontend/todayslot/images/ww_icon2.png" height="26"> 보유포인트 : <span class="font05" id="myPointWithdraw">&nbsp;&nbsp;{{number_format(auth()->user()->deal_balance,0)}}</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li> 
                                        <li><a href="#" onclick="getBalance();"><span class="btn1_2"><img src="/frontend/todayslot/images/icon_re.png" height="20"> 새로고침</span></a> 
                                        <!--<a href="#" onclick="ajaxCasBalancePop('move');"><span class="btn1_2"><img src="images/icon_re.png" height="20"> 전체머니회수</span></a>-->
                                        </li>
                                        <li>                            
                                        <input type="hidden" class="walletYn" id="walletYn" value="Y">
                                        <input type="hidden" class="offerGameCode" id="offerGameCode" value="999">
                                        </li>
                                    </ul>
                                </div>                
                            </div>

                            <div class="con_box10" id="fundFrm" name="fundFrm">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="write_title_top">
                                    <tr>
                                        <td class="write_title">신청금액</td>

                                        <td class="write_basic"><input id="exchangemoney" type="hidden" name="exchangemoney" value="0">
                                            <input type="text" class="input1" name="exchange_money" id="exchange_money" placeholder="0" onchange="comma()" /> 
                                            <a href="#" onclick="addMoney(10000);"><span class="btn1_2">1만원</span></a>  <a href="#" onclick="addMoney(50000);"><span class="btn1_2">5만원</span></a> 
                                            <a href="#" onclick="addMoney(100000);"><span class="btn1_2">10만원</span></a> <a href="#" onclick="addMoney(500000);"><span class="btn1_2">50만원</span></a> 
                                            <a href="#" onclick="addMoney(1000000);"><span class="btn1_2">100만원</span></a> <a href="#" onclick="addMoney(5000000);"><span class="btn1_2">500만원</span></a> 
                                            <a href="#" onclick="addMoney(10000000);"><span class="btn1_2">1000만원</span></a> 
                                            <a href="#" onclick="resetMoney();"><span class="btn1_1">정정</span></a>
                                        </td>
                                    </tr>                                                            
                                </table>                
                            </div>

                            <div class="con_box20">
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

                        <!-- 공지사항 -->
                        <div id="notice_page" class="pagetab" style="display:none">
                            <div class="title1" style="text-align:center;color:#ffba0a">
                                    공지사항
                            </div>

                            <div class="con_box10">
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

                        <!-- 이벤트 -->
                        <div id="event_page" class="pagetab" style="display:none">
                            <div class="title1" style="text-align:center;color:#ffba0a">
                                이벤트
                            </div>

                            <div class="contents_in">
                                <div class="con_box10">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tbody><tr>
                                            <td width="10%" class="list_title1">번호</td>
                                            <td class="list_title1">제목</td>
                                        </tr>                                                                                    
                                    </tbody></table>
                                </div>
                            </div>
                        </div>

                        <!-- 고객센터 -->
                        <div id="attendance_page" class="pagetab" style="display:none">
                            <div class="title1" style="text-align:center;color:#ffba0a">
                                고객센터
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
                                            <th translate="" class="list_title1" style="padding-left: 20px">제목</th>
                                            <th translate="" class="list_title1">작성 일시</th>
                                            <th translate="" class="list_title1">수신 일시</th>
                                            <th translate="" class="list_title1">타입</th>
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
                                                <td class="write_basic"><input class="input1" id="txt_title" name="name" size="30" value="" placeholder="제목을 입력해주세요.">
                                                @else
                                                <td class="write_basic">
                                                @endif
                                                    <a href="#" onclick="backCustomerRequest();"><span class="back_btn"><img src="/frontend/todayslot/images/left.png" height="20"></span></a>
                                                </td>
                                                
                                            </tr> 


                                            <input type="hidden" class="trade_type0_yn" id="trade_type0_yn" value="01">

                                            <tr>
                                                <td class="write_title">내용</td>
                                                
                                                <td class="write_basic">
                                                    <textarea rows="4" cols="20" maxlength="300" class="inputcustomer font02" value="" placeholder="내용은 1000자 이하로 입력해주세요" id="content_txt"></textarea>
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

                        

                    </ul>
                </div>
            @else
            <script>
                
            </script>
                
            @endif
        </div>
	</div>
</div>
<!-- 
<script>
    function send_text() {
    var title = $("#txt_title").val();
    var message = $('#content_txt').val();
    if ((title == '') || (message == '')) 
    {
      swal('', '제목과 내용을 입력해주세요', 'error');
    } 
    else 
    {
        $.ajax({
            url: "/api/writeMsg",
            type: "POST",
            dataType: "json",
            data: {
              title: title,
              content: message
            },
            success: function(result) {
              if (result.error == false) {
                swal('저장 되었습니다');
                $("#txt_title").val('');
                $('#content_txt').val('');
                              getCustomerPage();
                $(".cc-from").addClass('ng-hide');
                $(".cc-list").removeClass('ng-hide');
                // },2000);
              }
              else 
              {
                swal('Opps!', result.msg, "error");
              }
            }
          });
    }              
}

function getCustomerPage() {
	$.ajax({
        type: "POST",
        cache: false,
        async: true,
        url: '/api/messages',
        dataType: 'json',
        success: function(data) {
        if(data.error == false){
			var html = `<tr>
                            <th translate="" class="list_title1" style="padding-left: 20px">제목</th>
                            <th translate="" width="20%" class=" list_title1">작성 일시</th>
                            <th translate="" width="20%" class=" list_title1">수신 일시</th>
                            <th translate="" width="10%" class=" list_title1">타입</th>
                        </tr>`;
			if (data.data.length > 0) {
				for (var i = 0; i < data.data.length; i++) {
					date = new Date(data.data[i].created_at);
					if (data.data[i].read_at == null)
					{
						read = '읽지 않음';
					}
					else
					{
						date1 = new Date(data.data[i].read_at);
						read = date1.toLocaleString();
					}
					type = (data.user_id!=data.data[i].writer_id)?'수신':'발신';
					html += `<tr onclick="showMsg('${data.data[i].id}')">
                                <td class="list_notice1" style="padding-left: 20px">${data.data[i].title}</td>
                                <td width="20%" class="list_notice1">${date.toLocaleString()}</td>
                                <td width="20%" class="list_notice1">${read}</td>
                                <td width="10%" class="list_notice1">${type}</td>
                            </tr>
                            <tr class="list_notice2" id="msg${data.data[i].id}" style="display:none;">
                                <td colspan="4" class="list_notice2">${data.data[i].content}</td>
                            </tr>`;
				}
				
			}
			$("#customerList").html(html);
            
			
        } else {
            alert(data.msg);
        }
    }
    });
    
}

function showMsg(objId) {
    dis = document.getElementById("msg" + objId).style.display == "none" ? "table-row" : "none";
    document.getElementById("msg" + objId).style.display = dis;
    $.post('/api/readMsg',{id : objId},function(data){
    }); 
}

function customerRequest() {
    if (true) {
        var doc = document.getElementById("customerlist");
        doc.setAttribute('style', 'display:none');
        var doc1 = document.getElementById("customerregist");
        doc1.setAttribute('style', 'display:block');
    } else {
        showLoginAlert();
    }
}

function backCustomerRequest(){
    if (true) {
        var doc = document.getElementById("customerlist");
        doc.setAttribute('style', 'display:block');
        var doc1 = document.getElementById("customerregist");
        doc1.setAttribute('style', 'display:none');
    } else {
        showLoginAlert();
    }
}
</script> -->