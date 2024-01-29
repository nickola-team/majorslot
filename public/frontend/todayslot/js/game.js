
/////////////////로그인/////////////
//로그인
function loginSubmit(frm) {
    event.preventDefault();
    var userid = $("#login_id").val();
    var userpw = $("#login_pw").val();
    if (userid.value == "아이디" || userid.value == "") {
        alert("로그인 아이디를 입력해 주세요");
        userid.focus();
        return;
    }
    if (userpw.value == "******" || userpw.value == "") {
        alert("비밀번호를 입력해 주세요");
        userpw.focus();
        return;
    }

    var formData = new FormData();
    formData.append("_token", $("#_token").val());
    formData.append("username", userid);
    formData.append("password", userpw);

    $.ajax({
        type: "POST",
        url: "/api/login",
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        success: function (data, status) {
            if (data.error) {
                alert(data.msg);
            }
            // var doc = document.getElementById("m_login_section");
            // doc.setAttribute('style', 'display:none');
            // var doc = document.getElementById("m_my_section");
            // doc.setAttribute('style', 'display:block');
            location.reload();
        },
        error: function (err, xhr) {
        }
    });
}

//로그아웃
function goLogout() {
    //location.href = "/login/logout.asp";
    location.href = "/logout";
}


function showLoginAlert() {
    alert("로그인 후 이용가능합니다.");
    $("#userid").focus();
}
////////////////////////////////////

////////////////회원가입////////////

//회원가입
function goJoin() {
    var doc = document.getElementById("etc_pop1");
    doc.setAttribute('style', 'display:block');
    var doc = document.getElementsByClassName("mainboard");
    for (var i = 0; i < doc.length; i++) {
        doc[i].setAttribute('style', 'display:none');                  
    }
}

//아이디 중복확인
function checkid()
		{
			var form = document.getElementById( "frm_join" );

			if(form.username.value.length < 3)
			{
				alert("ID는 3~10자 까지 입력 가능합니다.");
				form.username.focus();
				return false ;
			}

			if(form.username.value.length > 10)
			{
				alert("ID는 3~10자 까지 입력 가능합니다.");
				form.username.focus();
				return false ;
			}

			var Item = isCheckOK1(form.username.value);

		if (form.username.value == "")
		{
			alert("ID를 입력해 주십시오.");
			form.username.focus();
			return false ;
		}

		if ( Item == false )
		{
			alert("아이디에 허용되지 않는 문자가 포함되어 있습니다.");
			form.username.value = "";
			form.username.focus();
			return false ;
		}
		$.ajax({
			type: 'POST',
			url: "/api/checkid",
			data: {id: form.username.value },
			cache: false,
			async: false,
			success: function (data) {
				if (data.error) {
					alert(data.msg);
					return;
				}
				if (data.ok == 1){
					alert('이 아이디는 이용하실수 있습니다.');
				}
				else
				{
					alert('이 아이디는 이미 사용중입니다.');                    
				}
			},
			error: function (err, xhr) {
				alert(err.responseText);
			}
    	});

		return true ;
	}

    function isCheckOK1(strVal) {
        var intTempIdx;
        var strTemp;
    
        for (intTempIdx = 0; intTempIdx < strVal.length; intTempIdx++) {
            if (
                (strVal.charCodeAt(intTempIdx) < 127 &&
                    strVal.charCodeAt(intTempIdx) > 122) ||
                (strVal.charCodeAt(intTempIdx) < 48 &&
                    strVal.charCodeAt(intTempIdx) > 31) ||
                (strVal.charCodeAt(intTempIdx) < 65 &&
                    strVal.charCodeAt(intTempIdx) > 57) ||
                (strVal.charCodeAt(intTempIdx) < 97 &&
                    strVal.charCodeAt(intTempIdx) > 90) ||
                strVal.charCodeAt(intTempIdx) == 12288
            ) {
                return false;
            }
        }
    
        if (strVal.length != intTempIdx) return false;
        else return true;
    }

//가입하기 버튼
    function Regist( form )
		{
			if (form.username.value == "")
			{
				alert("ID를 입력해 주십시오.");
				form.username.focus();
				return false ;
			}

			if(form.username.value.length < 3)
			{
				alert("ID는 3~10자 까지 입력 가능합니다.");
				form.username.focus();
				return false ;
			}

			if(form.username.value.length > 10)
			{
				alert("ID는 3~10자 까지 입력 가능합니다.");
				form.username.focus();
				return false ;
			}

			var Item = isCheckOK1(form.username.value);

			if ( Item == false )
			{
				alert("아이디에 허용되지 않는 문자가 포함되어 있습니다.");
				form.username.value = "";
				form.username.focus();
				return false ;
			}

			if (form.password.value == "")
			{
				alert("Password를 입력해 주십시오.");
				form.password.focus();
				return false ;
			}

			if (form.password1.value == "")
			{
				alert("Password를 입력해 주십시오.");
				form.password1.focus();
				return false ;
			}

			if(form.password.value != form.password1.value)
			{
				alert("비밀번호가 일치하지 않습니다.");
				form.password1.focus();
				return false ;
			}

			if(form.password.value.length < 4)
			{
				alert("Password는 4~12까지 입력 가능합니다.");
				form.password.focus();
				return false ;
			}

			if(form.password.value.length > 12)
			{
				alert("Password는 4~12까지 입력 가능합니다.");
				form.password.focus();
				return false ;
			}

			if (form.tel1.value == "")
			{
				alert("연락처를 입력해 주십시오.");
				form.tel1.focus();
				return false ;
			}

			if (form.tel2.value == "")
			{
				alert("연락처를 입력해 주십시오.");
				form.tel2.focus();
				return false ;
			}

			if (form.tel3.value == "")
			{
				alert("연락처를 입력해 주십시오.");
				form.tel3.focus();
				return false ;
			}

			if (form.bank_name.value == "")
			{
				alert("계좌정보를 입력해 주십시오.");
				form.bank_name.focus();
				return false ;
			}

			if (form.account_no.value == "")
			{
				alert("계좌정보를 입력해 주십시오.");
				form.account_no.focus();
				return false ;
			}

			if (form.recommender.value == "")
			{
				alert("계좌정보를 입력해 주십시오.");
				form.recommender.focus();
				return false ;
			}


			if (form.friend.value == "")
			{
				alert("추천인코드를 입력해 주세요.");
				form.friend.focus();
				return;
			}

			$.ajax({
				type: 'POST',
				url: "/api/join",
				data: {	username: form.username.value,
						password: form.password.value,
						tel1: form.tel1.value,
						tel2: form.tel2.value,
						tel3: form.tel3.value,
						bank_name: form.bank_name.value,
						recommender: form.recommender.value,
						account_no: form.account_no.value,
						friend: form.friend.value},
				cache: false,
				async: false,
				success: function (data) {
					if (data.error) {
						alert(data.msg);
						return;
					}
					else{
						alert(data.msg);
						window.parent.TINY.box.hide();
					}

				},
				error: function (err, xhr) {
					alert(err.responseText);
				}
			});
		}

///////////////////////////////////

///////////MY페지/////////////////

function goMypage(id) {
    if (true) {
        var doc = document.getElementById("sub_pop2");
        doc.setAttribute('style', 'display:block');
        var doc = document.getElementsByClassName("mainboard");
        for (var i = 0; i < doc.length; i++) {
            doc[i].setAttribute('style', 'display:none'); 
        }
        if(id==1){
            tabActionProc('tab1', 'mytab_page');
        }else if(id==2){
            tabActionProc('tab2', 'deposit_page');
        }else if(id==3){
            tabActionProc('tab3', 'withdraw_page');
        }else if(id==4){
            tabActionProc('tab4', 'notice_page');
        }else if(id==5){
            tabActionProc('tab6', 'attendance_page');
            getCustomerPage();
        }
    } else {
        //showLoginAlert();
    }
}

function tabActionProc(obj, pid) {
    
    if (true){
         if(obj) {            
            var tab = $(".popup_tab2 li." + obj).closest(".popup_tab2 > li");
            tab.siblings().removeClass("sk_tab_active_02");
            tab.addClass("sk_tab_active_02");
        } 
        if(pid){
            var doc = document.getElementsByClassName("pagetab");
            for (var i = 0; i < doc.length; i++) {
                if (doc[i].id != pid) {
                    notes = doc[i];
                    notes.setAttribute('style', 'display:none');
                }else{
                    notes = doc[i];
                    notes.setAttribute('style', 'display:block');
                }                    
            }
        }
    } else {
        showLoginAlert();
    }
}

///공지사항
function navClick(id) {
    $("#" + id).removeClass('ng-hide');
}
function setTab(name, elem) {
    $(".contentSet").html('');
    $(".ngdialog-main-default__content").addClass('ng-hide');
    $("#" + name).removeClass('ng-hide');
    $(".ngdialog-main-nav li").removeClass('active');
    $(elem).addClass('active');
}

function getNotice(objId) {
    dis = document.getElementById(objId).style.display == "none" ? "table-row" : "none";
      document.getElementById(objId).style.display = dis;
}

/////////////////////////////////////


///////////CloseBtn//////////////

//회원가입 Close
function joinClose(){
    var doc = document.getElementById("etc_pop1");
    doc.setAttribute('style', 'display:none');
    var doc = document.getElementsByClassName("mainboard");
    for (var i = 0; i < doc.length; i++) {
        doc[i].setAttribute('style', 'display:block'); 
    }
}

//MyPage Close
function myPageClose(){
    var doc = document.getElementById("sub_pop2");
    doc.setAttribute('style', 'display:none');
    var doc = document.getElementsByClassName("mainboard");
    for (var i = 0; i < doc.length; i++) {
        doc[i].setAttribute('style', 'display:block'); 
    }
}


//////////////////////입금신청//////////////////
//신청금액 버튼함수
function addMoneyDeposit(money) {
    // alert(money)
    // var obj  =   $("#money");
    var str = $("#charge_money").val();
    if (str == null || str.length == 0) str = "0";
    str = replaceComma(str);
    var betMoney = parseInt(str);
    betMoney += money;
    $("#charge_money").val(insertComma("" + betMoney));
}

function numChk(num)
{
	var rightchar = replaceComma(num.value);
	var moneychar="";
	for(index=rightchar.length-1;index>=0;index--){
		splitchar = rightchar.charAt(index);
		if(isNaN(splitchar)){
			num.value="";
			num.focus();
			return false;
		}
		moneychar = splitchar+moneychar;
		if(index%3==rightchar.length%3 && index  !=0) {
			moneychar = ','+moneychar;
		}
	}
	num.value = moneychar;
	return true;
}

function resetMoneyDeposit() {
    var obj = $('#charge_money').val(0);
    }

/////

//계좌문의
function askAccount() {
    var accountname = $("#deposit_page #name").val();
    var money = $("#deposit_page #charge_money").val();
    var _token = $('#_token').val();

    document.getElementById("btn_ask").style.pointerEvents = 'none';

    $.ajax({
        cache: false,
        url: "/api/depositAccount",
        type: "POST",
        data: { money: money, _token: _token, account:accountname },
        success: function(data) {
            document.getElementById("btn_ask").style.pointerEvents = 'auto';
            if (data.error) {
                $("#deposit_page #btn_ask").show();
                alert(data.msg);
                if (data.code == '001') {
                    location.reload(true);
                }
                else if (data.code == '002') {
                    $("#deposit_page #charge_money").focus();
                }
                else if (data.code == '003') {
                    $("#deposit_page #name").focus();
                }
                return;
            }else{
                if(data.url != ""){
                    window.open(data.url, "myWindow", 'width=800,height=600');
                    window.close(); 
                }
            }
            depositAccountRequested = true;
            alert(data.msg);
        }
    });
}


//입금하기
function deposit() {
    var cmoney = $('#charge_money').val();
    var cmoneyx = $('#charge_money').val().replace(/,/g, '');
    var y = parseNumberToInt(cmoney);
    var x = 10000;
    var remainder = Math.floor(y % x);
    if (remainder != 0) {
        alert('입금신청은 만원단위로 가능합니다. 만원단위로 신청해주시기 바랍니다.');
        return false;
    }
    var conf = confirm('입금신청을 하시겠습니까?');
    if (!conf) {
        return false;
    }
    if (cmoney <= 0) {
        alert('신청하실 충전금액을 입력해주세요.');
        return false;
    }
    $.ajax({
        url: '/api/addbalance',
        type: 'POST',
        dataType: "json",
        data: {
        money: cmoney,
        },
        success: function(result) {
        if (result.error == false) {
            $("#charge_money").val(0);
            swal('신청완료 되었습니다.');
            mydepositlist();
        } else {
            swal('Oops!', result.msg, 'error');
        }
        }
    });
}

function parseNumberToInt(val) {
    val = val.replace(/[^\/\d]/g, '');
    return parseInt(val);
  }


//입금리력 얻기
function mydepositlist() {
    $.ajax({
      type: "POST",
      cache: false,
      async: true,
      url: '/api/inouthistory',
      dataType: 'json',
      data : {type: 'add'},
      success: function(data) {
        if(data.error == false){
			var html = `<tbody>
						<tr>
							<th style="width:25%;" class="list_title1">번호</td>
							<th style="width:25%;" class="list_title1">충전금액</td>
							<th style="width:25%;" class="list_title1">신청날짜</td>
							<th style="width:25%;" class="list_title1">상태</td>
						</tr>
                        `;
			if (data.data.length > 0) {
				status_name = {
					0 : '대기',
					1 : '완료',
					2 : '취소'
				 };
				for (var i = 0; i < data.data.length; i++) {
					date = new Date(data.data[i].created_at);
					html += `<tr style="text-align:center">
						<td class="write_basic">${i+1}</td>
						<td class="write_basic">${parseInt(data.data[i].sum).toLocaleString()}원</td>
						<td class="write_basic">${date.toLocaleString()}</td>
						<td class="write_basic">${status_name[data.data[i].status]}</td>
						</tr>
						</thead>`;
				}
				
			}
			html += `</table>`;
			$("#mydeposit").html(html);
			
        } else {
            alert(data.msg);
        }
      }
    });
  }

/////////////////////////////


//////////보유머니//////////
function getBalance() {
    $.ajax({
    type: "POST",
    url: "/api/balance",
    data: null,
    processData: false,
    contentType: false,
    cache: false,
    async: false,
    success: function (data) {
        if (data.error) {
            alert(data.msg);
            return;
        }
        $("#myWalletPop").html(data['balance']+".00원");
        $("#myWalletWithdraw").html(data['balance']+".00원");
        $("#myHeaderWallet").html(data['balance']+".00원");
        $("#myPageWallet").html(data['balance']+".00원");
        mywithdrawlist();
        mydepositlist();
    }
    });
}
///////////////////////////


/////////////출금신청//////////////
function addMoney(money) {
    var str = $("#exchange_money").val();
    if (str == null || str.length == 0) str = "0";
    str = replaceComma(str);
    var betMoney = parseInt(str);
    betMoney += money;
    $("#exchange_money").val(insertComma("" + betMoney));
}

function replaceComma(str)
{
	if(str==null || str.length==0) return "";
	while(str.indexOf(",")>-1){
		str = str.replace(",","");
	}
	return str;
}

function insertComma(str)
{
	var rightchar = replaceComma(str);
	var moneychar="";
	for(index=rightchar.length-1;index>=0;index--){
		splitchar = rightchar.charAt(index);
		if(isNaN(splitchar)){
			return "";
		}
		moneychar = splitchar+moneychar;
		if(index%3==rightchar.length%3 && index  !=0) {
			moneychar = ','+moneychar;
		}
	}
	str = moneychar;
	return str;
}

function resetMoney() {
var obj = $('#exchange_money').val(0);
}

//출금버튼
function withdrawRequest() {
    var cmoney = $('#exchange_money').val();
    var y = parseNumberToInt(cmoney);
    var x = 10000;
    var remainder = Math.floor(y % x);
    if (remainder != 0) {
      alert('출금신청은 만원단위로 가능합니다. 만원단위로 신청해주시기 바랍니다.');
      return false;
    }
    var conf = confirm('출금신청을 하시겠습니까?');
    if (!conf) {
      return false;
    }
    if (cmoney <= 0) {
      alert('정확한 금액을 입력해주세요');
      return false;
    }
    $.ajax({
      url: '/api/outbalance',
      type: 'POST',
      dataType: "json",
      data: {
        money: cmoney,
      },
      success: function(result) {
        if (result.error == false) {
          $("#exchange_money").val(0);
          swal('신청완료 되었습니다.');
          mywithdrawlist();
        } else {
            swal('Oops!', result.msg, 'error');
        }
      }
    })
  }


//출금리력
function mywithdrawlist() {
    $.ajax({
        type: "POST",
        cache: false,
        async: true,
        url: '/api/inouthistory',
        dataType: 'json',
        data : {type: 'out'},
        success: function(data) {
          if(data.error == false){
              var html = `<tbody>
                          <tr>
                              <th style="width:25%;" class="list_title1">번호</td>
                              <th style="width:25%;" class="list_title1">환전금액</td>
                              <th style="width:25%;" class="list_title1">신청날짜</td>
                              <th style="width:25%;" class="list_title1">상태</td>
                          </tr>
                          `;
              if (data.data.length > 0) {
                  status_name = {
                      0 : '대기',
                      1 : '완료',
                      2 : '취소'
                   };
                  for (var i = 0; i < data.data.length; i++) {
                      date = new Date(data.data[i].created_at);
                      html += `<tr style="text-align:center">
                          <td class="write_basic">${i+1}</td>
                          <td class="write_basic">${parseInt(data.data[i].sum).toLocaleString()}원</td>
                          <td class="write_basic">${date.toLocaleString()}</td>
                          <td class="write_basic">${status_name[data.data[i].status]}</td>
                          </tr>
                          </thead>`;
                  }
                  
              }
              html += `</table>`;
              $("#mywithdraw").html(html);
              
          } else {
              alert(data.msg);
          }
        }
      });
}
//////////////////////////////////

/////////////게임/////////////


function goStartGame(gametype) {
    noService();
    return;
    if (gametype == "micro") {
        underConstruction();
        //gameStart("M");
    } else {
        noService();
    }
}

function gameStart(strName) {
    var width = screen.width;
    var man = "";
    var win;

    if (loginYN == "N") {
        alert("로그인 후 사용가능합니다");
        return;
    }

    noService();
    return;

    if ("M" == strName) {
        man = "/api/m/mlink.asp";
    } else {
        noService();
        return;
    }

    if (width < 1024) {
        win = window.open(man, 'Gaming', 'width=800,height=600,resizable=yes,scrollbars=o,status=0,toolbar=0,screenX=150,screenY=100');
    } else {
        win = window.open(man, 'Gaming', 'width=1000,height=690,resizable=no,scrollbars=0,status=0,toolbar=0,screenX=150,screenY=100');
    }
    if (win != undefined) {
        win.focus();
    }
}

function noService() {
    alert("서비스 준비중입니다");
}

function underConstruction() {
    alert("서비스 점검중입니다");
}



function lauchXiLive(gid, isConstruct, pv) {
    

    var gurl = "/api/ximax/xirun.asp?gid=" + gid + "&gtype=" + gid + "&pv=" + pv + "&nowtime=" + (new Date()).getTime();
    var game_main = window.open(gurl, 'game_main', 'width=1350, height=900, status=no, scrollbars=yes, resizable=yes');
}

function lauchXiGame(gid, gtype, pv) {
    var gurl = "/api/ximax/xirun.asp?gid=" + gid + "&gtype=" + gtype + "&pv=" + pv + "&nowtime=" + (new Date()).getTime();
    var game_main = window.open(gurl, 'game_main', 'width=1350, height=900, status=no, scrollbars=yes, resizable=yes');
}


//////슬롯목록/
function goSlot(title, category, isConst) {
    //if( isConst == "1" ) { underConstruction(); return; }
    if (true) {
    
        var formData = new FormData();
        formData.append("_token", $("#_token").val());
        formData.append("category", category);
    
        $.ajax({
            type: "POST",
            url: "/api/getgamelist",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            success: function (data) {
                if (data.error) {
                    alert(data.msg);
                    if (data.code == "001") {
                        location.reload();
                    }
                    return;
                }
                var doc = document.getElementById("etc_pop1_game");
                doc.setAttribute('style', 'display:block');
                var doc = document.getElementsByClassName("mainboard");
                for (var i = 0; i < doc.length; i++) {
                    doc[i].setAttribute('style', 'display:none'); 
                }

                var strHtml = `
                    <div>
                        <div class="con_box10">
                            <div class="title1">
                            ${title} <span style="float:right; padding:0 10px 0 10px;margin-top:-5px;">
                            <style>
                            </style>
                            </div>
                        </div>
                        <div style="width:100%;margin-top:-5px; max-height: 1560px;overflow-y: auto; ">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tbody><tr>`;
                                if (data.games.length > 0) {
                                    for (var i = 0; i < data.games.length; i++) {
                                        if(i > 0 && i % 5 == 0){
                                            strHtml += `</tr><tr>`;
                                        }
                                        if (data.games[i].provider)
                                        {
                                            strHtml += `<td>
                                           <a href="#" onClick="startGameByProvider('${data.games[i].provider}', '${data.games[i].gamecode}');" class="hg-btn"><div class="img-cont"><img class="main-img" src="${data.games[i].icon}" alt="" style="height: 135px;width: 155px;"></div><div class="foot"><p>${data.games[i].title}</p></div>`;
                                            // if (data.games[i].icon)
                                            // {
                                            //     strHtml += `<img class="main-img" src="${data.games[i].icon}" alt="thumbnail" id="xImag">`;
                                            // }
                                            // else {
                                            //     strHtml += `<img class="main-img" src="/frontend/Default/ico/${data.games[i].provider}/${data.games[i].gamecode}_${data.games[i].name}.jpg" alt="thumbnail" id="xImag">`;
                                            // }
                                            strHtml += `
                                                <div class="foot">
                                                    <p style="word-break: break-all;text-align: center">${data.games[i].title}</p>
                                                </div>`;
                                        }
                                        else
                                        {
                                            strHtml += `<td>
                                            <a href="#" onClick="startGame('${data.games[i].name}');"class="hg-btn"><div class="img-cont">
                                                <img class="main-img" src="/frontend/Default/ico/${data.games[i].name}.jpg" alt="" style="height: 135px;width: 155px;"></div><div class="foot"><p>${data.games[i].title}</p></div>`;
                                        }
                                        strHtml += `<div class="overlay">
                                            <p><i class="glyphicon glyphicon-log-in"></i> 게임하기</p>
                                        </div></a></td>`;
                                    }
                                    
                                } else {
                                    
                                }
                                
                strHtml += `</tr></tbody>
                                </table>
                        </div>
                        <div class="con_box20">
                                <div class="btn_wrap_center">
                                    <ul>
                                        <li><a href="#" onclick="slotListClose();"><span class="btn3_1">닫기</span></a></li>
                                    </ul>
                                </div>
                            </div> 
                            <div class="con_box20">
                                <div class="btn_wrap_center">
                                </div>
                            </div> 
                    </div>`
                $("#popupbox_game").html(strHtml);
            },
            error: function (err, xhr) {
                var doc = document.getElementById("etc_pop1_game");
                doc.setAttribute('style', 'display:block');
                var doc = document.getElementsByClassName("mainboard");
                for (var i = 0; i < doc.length; i++) {
                    doc[i].setAttribute('style', 'display:none'); 
                }
                var strHtml = `
                    <div>
                        <div class="con_box10">
                            <div class="title1">
                            ${title} <span style="float:right; padding:0 10px 0 10px;margin-top:-5px;">
                            </div>
                        </div>
                        <div style="width:100%;margin-top:-5px">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tbody><tr>`;                                
                strHtml += `</tr></tbody>
                                </table>
                            <div class="con_box20">
                                <div class="btn_wrap_center">
                                    <ul>
                                        <li><a href="#" onclick="slotListClose();"><span class="btn3_1">닫기</span></a></li>
                                    </ul>
                                </div>
                            </div> 
                            <div class="con_box20">
                                <div class="btn_wrap_center">
                                </div>
                            </div> 
                        </div>
                    </div>`
                $("#popupbox_game").html(strHtml);
            }
        });


    } else {
        showLoginAlert();
    }
}


function slotListClose(){
    var doc = document.getElementById("etc_pop1_game");
    doc.setAttribute('style', 'display:none');
    var doc = document.getElementsByClassName("mainboard");
    for (var i = 0; i < doc.length; i++) {
        doc[i].setAttribute('style', 'display:block'); 
    }
}
function slotListMClose(){
    var doc = document.getElementsByClassName("mainboard");
    for (var i = 0; i < doc.length; i++) {
        doc[i].setAttribute('style', 'display:block'); 
        if(doc[i].id == "etc_pop1_m_game"){
            doc[i].setAttribute('style', 'display:none');
        }
    }
}

function startGame(gamename) {
    startGameByProvider(null, gamename);
}

function startGameByProvider(provider, gamecode) {
        var formData = new FormData();
        formData.append("provider", provider);
        formData.append("gamecode", gamecode);
        $.ajax({
        type: "POST",
        url: "/api/getgamelink",
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        success: function (data) {
            if (data.error) {
                alert(data.msg);
                return;
            }
            window.open(data.data.url, "game", "width=1280, height=720, left=100, top=50");
        }
        });
        
    }


///////////쿠키관리/////////

function closeWin(name, check) {
    if (check=='Y' ){
        setCookie( name, "done" , 1 );
    }
    var doc = document.getElementById(name);
        doc.setAttribute('style', 'display:none');
}

function setCookie( name, value, expiredays ) {
	var todayDate = new Date();
	todayDate.setDate( todayDate.getDate() + expiredays );
	document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
}


//포인트전환
function PointToMoney() {
    if (confirm('모든 포인트를 머니로 변환하시겠습니까?')) {
      $.ajax({
        url: '/api/convert_deal_balance',
        type: 'POST',
        dataType: "json",
        data : null,
        success: function(result) {

            if (result.error == false)
            {
                swal('전환되었습니다');
                window.location.href = "/";
            }else{
                alert(result.msg);
            }
        }
      });
    }
}

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
                            <td class="list_title1" style="width:25%;">제목</td>
                            <td class="list_title1" style="width:25%;">작성 일시</td>
                            <td class="list_title1" style="width:25%;">수신 일시</td>
                            <td class="list_title1" style="width:25%;">타입</td>
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
                                <td class="list_notice1" style="width:25%; word-break: break-all;
                                text-align: center;">${data.data[i].title}</td>
                                <td style="width:25%;" class="list_notice1">${date.toLocaleString()}</td>
                                <td style="width:25%;" class="list_notice1">${read}</td>
                                <td style="width:25%;" class="list_notice1">${type}</td>
                            </tr>
                            <tr class="list_notice2" id="msg${data.data[i].id}" style="display:none;">
                                <td colspan="4" class="list_notice2" style="width:100%; word-break:break-all; text-align:center;">${data.data[i].content}</td>
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


////////////////모바일/////////////
function mtabActionProc(pid){
    var doc = document.getElementsByClassName("pagetab");
    for (var i = 0; i < doc.length; i++) {
        if (doc[i].id != pid) {
            notes = doc[i];
            notes.setAttribute('style', 'display:none');
        }else{
            notes = doc[i];
            notes.setAttribute('style', 'display:block');
        }                    
    }
    var mainDoc = document.getElementsByClassName("mainboard");
    for(var i=0; i<mainDoc.length;i++){
        mainDoc[i].setAttribute('style', 'display:none');
        if(mainDoc[i].id == "m_header_wrap" || mainDoc[i].id == "footerwrap"){
            mainDoc[i].setAttribute('style', 'display:block');
        }
    }
    if(true){
        asidebar("tabclose");
    }
    
}


function goMSlot(title, category, isConst) {
    //if( isConst == "1" ) { underConstruction(); return; }
    if (true) {
    
        var formData = new FormData();
        formData.append("_token", $("#_token").val());
        formData.append("category", category);
    
        $.ajax({
            type: "POST",
            url: "/api/getgamelist",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            success: function (data) {
                if (data.error) {
                    alert(data.msg);
                    if (data.code == "001") {
                        location.reload();
                    }
                    return;
                }
                
                var doc = document.getElementsByClassName("mainboard");
                for (var i = 0; i < doc.length; i++) {
                    doc[i].setAttribute('style', 'display:none'); 
                    if(doc[i].id == "m_header_wrap" || doc[i].id == "footerwrap"){
                        doc[i].setAttribute('style', 'display:block');
                    }
                    if(doc[i].id == "etc_pop1_m_game"){
                        doc[i].setAttribute('style', 'display:block');
                    }
                }
                var strHtml = `
                    <div>
                        <div class="con_box10">
                        <div class="title_wrap">
                            <div class="title">
                            ${title} <span style="float:right; padding:0 10px 0 10px;margin-top:-5px;">
                            <style>
                                #gamelist {width:100%;}
                            </style>
                            </div>
                            </div>                            
                        </div>
                        <div style="width:100%;margin-top:-5px; max-height: 560px;overflow-y: auto; align: center;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tbody><tr>`;
                                if (data.games.length > 0) {
                                    for (var i = 0; i < data.games.length; i++) {
                                        if(i > 0 && i % 2 == 0){
                                            strHtml += `</tr><tr>`;
                                        }
                                        if (data.games[i].provider)
                                        {
                                            strHtml += `<td style="padding:10px">                                            
                                           <a href="#" onClick="startGameByProvider('${data.games[i].provider}', '${data.games[i].gamecode}');" class="hg-btn" ><div class="img-cont"><img class="main-img" src="${data.games[i].icon}" alt="" style="height: 135px;width: 155px;"></div><div class="foot"><p>${data.games[i].title}</p></div>`;                                            
                                            strHtml += `
                                                <div class="foot">
                                                    <p style="word-break: break-all;text-align: center">${data.games[i].title}</p>
                                                </div>`;
                                        }
                                        else
                                        {
                                            strHtml += `
                                            <td style="padding:10px">
                                            <a href="#" onClick="startGame('${data.games[i].name}');" id="gamelist" class="hg-btn"><div class="img-cont">
                                                <img class="main-img" src="/frontend/Default/ico/${data.games[i].name}.jpg" alt="" style="min-height: 135px;min-width: 155px;"></div><div class="foot"><p>${data.games[i].title}</p></div>`;
                                        }
                                        strHtml += `<div class="overlay">
                                            <p><i class="glyphicon glyphicon-log-in"></i> 게임하기</p>
                                        </div></a></td>`;
                                    }
                                    
                                } else {
                                    
                                }
                                
                strHtml += `</tr></tbody>
                                </table>
                        </div>
                        <div class="con_box20">
                                <div class="btn_wrap_center">
                                    <ul>
                                        <li><a href="#" onclick="slotListMClose();"><span class="btn3_1">닫기</span></a></li>
                                    </ul>
                                </div>
                            </div> 
                            <div class="con_box20">
                                <div class="btn_wrap_center">
                                </div>
                            </div> 
                    </div>`
                $("#popupbox_game").html(strHtml);
            },
            error: function (err, xhr) {
                var doc = document.getElementById("etc_pop1_game");
                doc.setAttribute('style', 'display:block');
                var doc = document.getElementsByClassName("mainboard");
                for (var i = 0; i < doc.length; i++) {
                    doc[i].setAttribute('style', 'display:none'); 
                }
                var strHtml = `
                    <div>
                        <div class="con_box10">
                            <div class="title1">
                            ${title} <span style="float:right; padding:0 10px 0 10px;margin-top:-5px;">
                            </div>
                        </div>
                        <div style="width:100%;margin-top:-5px">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tbody><tr>`;                                
                strHtml += `</tr></tbody>
                                </table>
                            <div class="con_box20">
                                <div class="btn_wrap_center">
                                    <ul>
                                        <li><a href="#" onclick="slotListClose();"><span class="btn3_1">닫기</span></a></li>
                                    </ul>
                                </div>
                            </div> 
                            <div class="con_box20">
                                <div class="btn_wrap_center">
                                </div>
                            </div> 
                        </div>
                    </div>`
                $("#popupbox_game").html(strHtml);
            }
        });


    } else {
        showLoginAlert();
    }
}

function goMLobby(){
    var doc = document.getElementsByClassName("mainboard");
    for (var i = 0; i < doc.length; i++) {
        doc[i].setAttribute('style', 'display:block'); 
        if(doc[i].id=="etc_pop1_m_game"){
            doc[i].setAttribute('style', 'display:none'); 
        }
    }
    var doc = document.getElementsByClassName("pagetab");
    for (var i = 0; i < doc.length; i++) {
        doc[i].setAttribute('style', 'display:none'); 
    }
}