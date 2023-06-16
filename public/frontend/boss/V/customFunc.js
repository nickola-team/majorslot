var globalobj = {
    'countdowntimer' : 10,
    'lastrequesttimestamp' : 0,
    'firsttimeaound':1,
};
var sport_type='';
var match_type='';
var newEventsUpdate=true;
function slotGame(category, title){
	$.ajax({
		type: "POST",
		url: "/api/getgamelist",
		data: {category : category},
		cache: false,
		async: true,
		success: function (data, status) {
			if (data.error) {
				alert(data.msg);
				return;
			}
			if (data.games.length > 0) {
				var htmldoc = ``;
				for (i=0;i<data.games.length;i++)
				{
					if (data.games[i].provider)
					{
						htmldoc += `<a href="#" onclick="startGameByProvider('${data.games[i].provider}', '${data.games[i].gamecode}');" class="hg-btn"><div class="img-cont"><img class="main-img" src="${data.games[i].icon}" alt="" style="height: 135px;width: 155px;"></div><div class="foot"><p>${data.games[i].title}</p></div>`;
					}
					else
					{
						htmldoc += `<a href="#" onclick="startGameByProvider(null, '${data.games[i].name}');"  class="hg-btn"><div class="img-cont"><img class="main-img" src="/frontend/Default/ico/${data.games[i].name}.jpg" alt="" style="height: 135px;width: 155px;"></div><div class="foot"><p>${data.games[i].title}</p></div>`;
					}
                    htmldoc += `<div class="overlay">
                            <p><i class="glyphicon glyphicon-log-in"></i> 게임하기</p>
                        </div></a>`;
				}
                
				$('#provider-title').html(title);
				$('#game-list-area').html(htmldoc);
			}
			else
			{
				 alert('게임이 없습니다');
			}
		   },
           complete: function() {
            $('.loading').hide();
            navClick('slots-game-popup');
        }
	});
}

function casinoGameStart(category){
 
	$.ajax({
	   type: "POST",
	   url: "/api/getgamelist",
	   data: {category : category},
	   cache: false,
	   async: true,
	   success: function (data, status) {
		   if (data.error) {
			   alert(data.msg);
			   return;
		   }
		   if (data.games.length > 0) {
			 startGameByProvider(data.games[0].provider, data.games[0].gamecode);
		   }
		   else
		   {
				alert('게임실행 오류');
		   }
		  }
	   });
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

function getGame(ptype,cname) {
    $.ajax({
         type: "POST",
         cache: false,
         async: true,
         url: '/ajax/apigamestable.asp?ptype='+ptype+'&cname='+cname,
         dataType: 'html',
         success: function(res) {
             $('#provider-title').html(getCnamePrint(cname));		
             $('#game-list-area').html(res);
         },
         error: function(res, xhr, aa) {
             alert("게임목록 연동오류가 발생했습니다.");
             log("게임목록 연동오류가 발생했습니다." + res);
         },
         complete: function() {
             $('.loading').hide();
             navClick('slots-game-popup');
         }
     });
 }

function logOut() {
    top.location.href="/logout";
}

function getCookieWel(name) {
    var Found = false;
    var start, end;
    var i = 0;
    while (i <= document.cookie.length) {
      start = i;
      end = start + name.length;
      if (document.cookie.substring(start, end) == name) {
        Found = true;
        break;
      }
      i++
    }
    if (Found == true) {
      start = end + 1;
      end = document.cookie.indexOf(";", start);
      if (end < start) end = document.cookie.length;
      return document.cookie.substring(start, end)
    }
    return ""
  }
  function setCookie( name, value, expiredays ) { 
  var todayDate = new Date(); 
  todayDate.setDate( todayDate.getDate() + expiredays ); 
  document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";" 
  } 

  function closeWinpopDay(id) { 
    if ( document.getElementById("notice_chk" + id).checked ){ 
    setCookie( "pop" + id, "done" , 1 ); 
    } 

    document.getElementById("pop" + id).style.visibility = "hidden"; 
  } 

  function closeWinpop(id) {
    document.getElementById("pop" + id).style.visibility = "hidden"; 
  }


function _number(id, amount) {
    //$('.jack-money'+idx).val("0");
    var comma_separator_number_step = $.animateNumber.numberStepFactories.separator(',');
    $('#' + id).animateNumber({
        number: amount, numberStep: comma_separator_number_step
    },500);
}

function getBalance() {
    $.ajax({
      url: "/api/balance",
      type: 'GET',
      dataType: 'json',
      success: function(result) {
        $("#onofOpenPopup").removeClass('hidemsgs');
        $("#UserMoney").html(result.balance);
        $("#point").html(result.deal + "P");
      }
    });
  }

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
$(document).on('click', '.ngdialog-close', function() {
    $(this).parent().parent().addClass('ng-hide');
});

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
function withdrawRealtime() {
    var html = ``;
    var date = new Date();

    for (i=0;i<5;i++)
    {
        var delta = Math.floor(Math.random() * 10);
        var amount = Math.floor(Math.random() * 500) * 10000;
        var id = Math.random().toString(36).substr(2,3) + "***";
        date.setMinutes(date.getMinutes() - delta);

        fakedata = {
            date : date.toLocaleString(),
            amount : insertComma("" + amount),
            id : id
        };
        html += `<li class="items ng-scope">
                <div class="transaction-date text-left"><span class="ng-binding">${fakedata['date']}</span></div>
                <div class="transaction-item goldTxt text-center"><span class="ng-binding">${fakedata['amount']}원</span></div>
                <div class="transaction-item text-left"><span class="ng-binding">${fakedata['id']}</span></div>
            </li>`

    }
    
        $("#withdraw-ticker").html(html);
  }

  function depositRealtime() {
        var html = ``;
        var date = new Date();

        for (i=0;i<5;i++)
        {
            var delta = Math.floor(Math.random() * 10);
            var amount = Math.floor(Math.random() * 500) * 10000;
            var id = Math.random().toString(36).substr(2,3) + "***";
            date.setMinutes(date.getMinutes() - delta);

            fakedata = {
                date : date.toLocaleString(),
                amount : insertComma("" + amount),
                id : id
            };
            html += `<li class="items ng-scope">
                    <div class="transaction-date text-left"><span class="ng-binding">${fakedata['date']}</span></div>
                    <div class="transaction-item goldTxt text-center"><span class="ng-binding">${fakedata['amount']}원</span></div>
                    <div class="transaction-item text-left"><span class="ng-binding">${fakedata['id']}</span></div>
                </li>`

        }
        
        $("#deposit-ticker").html(html);
  }

function deleteAllCookies() {
        var cookies = document.cookie.split(";");
        for (var i = 0; i < cookies.length; i++) {
            var cookie = cookies[i];
            var eqPos = cookie.indexOf("=");
            var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
            var popvar = name.trim();
            console.log(popvar)
            if (popvar.match(/PopupNotice.*/) || popvar == 'all') {
                  //don't clear popup cokkies
            } else{
                document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
            }  
        }

        
    }


function permis(){
    var location = localStorage.getItem('location');
    var provider = localStorage.getItem('provider');
    $.ajax({
        //if the account is logged into another PC using different IP we logout the previous account
        url: '/_sub/ajax/permission.php',
        success: function(result){
            if(result == 1){
                alert("자동 로그아웃. 회원님의 계정이 자동 로그아웃 되었습니다")
                window.location = "/_sub/ajax/logout.php?location="+location+"&provider="+provider;
            }else{
                setTimeout(function(){
                    permis();
                }, 1000);
            }
        }
    });
}


function force(){
    var location = localStorage.getItem('location');
    var provider = localStorage.getItem('provider');
    $.ajax({
        url: '/_sub/ajax/force-out.php',
        success: function(result){
            if(result == 1){
                alert('<?=_IPForce; ?>');
                window.location="/_sub/ajax/logout.php?location="+location+"&provider="+provider;
            }else{
                setTimeout(function(){
                    force();
                }, 5000);
            }
        }
    });
}

var convertss=false;
function pointsExchange(){
    var r = confirm('포인트를 머니로 전환하시겠습니까?');
    if(!r){
        return false;
    }
    if(convertss==true){
        return false;
    }
    convertss=true;
    $.ajax({
        url: '/_sub/ajax/epoints.php',
        success: function(result){
            $(location).attr('href',window.location);
        }
    });
}

function save_linkFromCustom(page) {
    $.ajax({
        cache:false,
        url: '/_sub/ajax/save_link.php',
        type: 'POST',
        data: { link: page },     
        success: function (result) {
         
        },
        error: function (error) {
            
        }

    });
}

function save_linkss(page){
    $.ajax({
       url: '/_sub/ajax/save_link.php',
       type: 'POST',
       data: {'link': page}
   });
}

function autorequest(){
    var f = confirm("계좌요청을 하시겠습니까?");
    if(!f){
        return false;
    }
    $.ajax({
        type: "POST",
        url: "/api/depositAccount",
        data: null,
        cache: false,
        async: false,
        success: function (data) {
            alert(data.msg);
        },
        error: function (err, xhr) {
            alert(err.responseText);
        },
    });
}
var coupid = '';
var isReceivedPoint = "1";

function changeExchangeMoney(obj) {
//var obj   =   document.formCharge;
str = obj.value;
str = replaceComma(str);
var m = parseInt(str);
if (str.length > 0) charge_money = m;
else charge_money = 0;
str = "" + charge_money;
str = insertComma(str);
obj.value = str;
}

function changeChargeMoney(obj) {
//var obj   =   document.formCharge;
str = obj.value;
str = replaceComma(str);
var m = parseInt(str);
if (str.length > 0) charge_money = m;
else charge_money = 0;
str = "" + charge_money;
str = insertComma(str);
obj.value = str;
}

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

function resetMoneyDeposit() {
var obj = $('#charge_money').val(0);
}

function parseNumberToInt(val) {
val = val.replace(/[^\/\d]/g, '');
return parseInt(val);
}
function depositRequest() {
    // alert('asd');
    var cmoney = $('.money').val();
    var cmoneyx = $('.money').val().replace(/,/g, '');
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
        // alert('신청하실 충전금액을 입력해주세요.');
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
        } else {
            swal('Oops!', result.msg, 'error');
        }
        }
    });
    $(".btn-pointr").on('click', function() {
        $(".btn-pointr").removeClass('active');
        $(this).addClass('active');
        isReceivedPoint = $(this).attr('data-type');
    });
}

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
							<th width="10%" class="ng-scope">번호</td>
							<th width="10%" class="ng-scope">충전금액</td>
							<th width="10%" class="ng-scope">신청날짜</td>
							<th width="10%" class="ng-scope">상태</td>
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
					html += `<tr>
						<td class="ng-binding">${i+1}</td>
						<td class="ng-binding">${parseInt(data.data[i].sum).toLocaleString()}원</td>
						<td class="ng-binding">${date.toLocaleString()}</td>
						<td class="ng-binding">${status_name[data.data[i].status]}</td>
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
                              <th width="10%" class="ng-scope">번호</td>
                              <th width="10%" class="ng-scope">환전금액</td>
                              <th width="10%" class="ng-scope">신청날짜</td>
                              <th width="10%" class="ng-scope">상태</td>
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
                      html += `<tr>
                          <td class="ng-binding">${i+1}</td>
                          <td class="ng-binding">${parseInt(data.data[i].sum).toLocaleString()}원</td>
                          <td class="ng-binding">${date.toLocaleString()}</td>
                          <td class="ng-binding">${status_name[data.data[i].status]}</td>
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
function getNotice(objId) {
    dis = document.getElementById(objId).style.display == "none" ? "table-row" : "none";
      document.getElementById(objId).style.display = dis;
  }

function showMsg(objId) {
    dis = document.getElementById("msg" + objId).style.display == "none" ? "table-row" : "none";
    document.getElementById("msg" + objId).style.display = dis;
    $.post('/api/readMsg',{id : objId},function(data){
}); 
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

function getCustomerPage() {
	$.ajax({
        type: "POST",
        cache: false,
        async: true,
        url: '/api/messages',
        dataType: 'json',
        success: function(data) {
        if(data.error == false){
			var html = `<tr class="table-border">
                            <th translate="" class="text-left ng-scope" style="padding-left: 20px">제목</th>
                            <th translate="" width="20%" class=" text-center ng-scope">작성 일시</th>
                            <th translate="" width="20%" class=" text-center ng-scope">수신 일시</th>
                            <th translate="" width="10%" class=" text-center ng-scope">타입</th>
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
					html += `<tr>
						<td class="text-left ng-scope" style="padding-left: 20px"> <a href="#" onclick="showMsg('${data.data[i].id}')">${data.data[i].title}</a></td>
						<td width="20%" class="text-center ng-scope">${date.toLocaleString()}</td>
						<td width="20%" class="text-center ng-scope">${read}</td>
						<td width="10%" class="text-center ng-scope">${type}</td>
						</tr>
						<tr id="msg${data.data[i].id}" style="display:none;">
						<td colspan="4" class="ng-scope">${data.data[i].content}</td>
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

function checkExchange() {
    var bCk = true;
    var obj = document.formCharge;
    var str = obj.exchange_money.value;
    var err = "";
    if (str == null || str.length == 0) str = "0";
    str = replaceComma(str);
    var betMoney = parseInt(str);
    if (betMoney < 10000) {
      bCk = false;
      err = "신청금액이 10,000원 미만입니다.";
    } else if (betMoney % 10000 != 0) {
      bCk = false;
      err = "금액은 10,000원단위로만 신청가능합니다.";
    }
    if (!bCk) {
      alert(err);
    } else {
      obj.mode.value = "out_money";
      obj.exchange_money.value = str;
    }
    return bCk;
  }

  function addMoney(money) {
    // var obj  =   $("#money");
    var str = $("#exchange_money").val();
    if (str == null || str.length == 0) str = "0";
    str = replaceComma(str);
    var betMoney = parseInt(str);
    betMoney += money;
    $("#exchange_money").val(insertComma("" + betMoney));
  }

  function resetMoney() {
    var obj = $('#exchange_money').val(0);
  }

  function changeExchangeMoney(obj) {
    //var obj   =   document.formCharge;
    str = obj.value;
    str = replaceComma(str);
    var m = parseInt(str);
    if (str.length > 0) charge_money = m;
    else charge_money = 0;
    str = "" + charge_money;
    str = insertComma(str);
    obj.value = str;
  }

  function changeChargeMoney(obj) {
    //var obj   =   document.formCharge;
    str = obj.value;
    str = replaceComma(str);
    var m = parseInt(str);
    if (str.length > 0) charge_money = m;
    else charge_money = 0;
    str = "" + charge_money;
    str = insertComma(str);
    obj.value = str;
  }

  function parseNumberToInt(val) {
    val = val.replace(/[^\/\d]/g, '');
    return parseInt(val);
  }
function withdrawRequest() {
    var cmoney = $('.exchange_money').val();
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
      // alert('Invalid Amount');
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
          $("#expassword").val('');
          swal('신청완료 되었습니다.');
        } else {
            swal('Oops!', result.msg, 'error');
        }
      }
    })
  }
  
/**
* @Description: Result league sport page data display 
* @author: Rajesh Upadhyay (made changes)
* @created: 12-07-2018 
*/

function result_league_sports_display(sporttype,isloadmore,eventslist){
	
		//when load more then set last request
		if(isloadmore){
			var d = new Date();
			//set request time
			globalobj.lastrequesttimestamp = d.getTime();
		}

		//loader gif show
		$('#load-more-loader').show();
		
		//response will render after 5 seconds
		setTimeout(function() {
			
			$.ajax({
				url: "./ajax/result_league_sports_display.php",
				type: "POST",
				data: {"sporttype":sporttype,"eventslist":eventslist},
				dataType: "json",
				async:false,
				success: function(result){

					//if more than records then display
					if(result.matchcount > 0){

						if(isloadmore){
							//display the result 
							$('#leanguesport').append(result.lenguedetals);
						}else{
							//display the result 
                            $('#leanguesport').html(result.lenguedetals);
                        }

						//set data values
						$("#eventlists").html(JSON.stringify(result.eventidarraylist));
						
						//side scroll moving
						//initMoving();
					}
					
					//show loadmore button 
					$("#loadmorediv").show();

					//loader gif hide
					$('#load-more-loader').hide();
					
				}
			});
		},500);


    }


/**
* @Description: league sport page data display 
* @author: lokendra meena
* @created: 30-05-2018 
*/

    function league_sports_display(sporttype,matchtype,isloadmore,eventslist){
	   
        sport_type=sporttype;
        match_type=matchtype;
		newEventsUpdate=false;
		//when load more then set last request
		if(isloadmore){
			var d = new Date();
			//set request time
			globalobj.lastrequesttimestamp = d.getTime();
		}
        
		//loader gif show
		$('#load-more-loader').show();
		//response will render after 5 seconds
		setTimeout(function() {
			
			$.ajax({
				url: "./ajax/league_sports_display.php",
				type: "POST",
				data: {"sporttype":sporttype,"matchtype":matchtype,"eventslist":eventslist},
				dataType: "json",
				async:false,
				success: function(result){

					//if more than records then display

					if(result.matchcount > 0){

						if(isloadmore){
							//display the result 
							$('#leanguesport').append(result.lenguedetals);
						}else{
							//display the result 
                            $('#leanguesport').html(result.lenguedetals);
                        }

						//set data values
						$("#eventlists").html(JSON.stringify(result.eventidarraylist));
						
						//side scroll moving
						//initMoving();
					}
					
					$("#loadmorediv").show();

					//loader gif hide
					$('#load-more-loader').hide();
					newEventsUpdate=true;
				}
			});
		},500);


    }

/**
* @Description: league sport page data display 
* @author: lokendra meena
* @created: 30-05-2018 
*/

/*function displayall_sub_oddsbest(eventinfoobj,isRefresh){
	
	if(isRefresh==false){
		//display the result 
		if($('#oddschilds_'+eventinfoobj.eventid+':visible').length == 0){
			//show loader on odds bet childs
			$('#oddschilds_'+eventinfoobj.eventid).html('<img  src="/images/loader-img.gif" style="width: 35px;" alt="loader"> ').show();
            $('#icon_'+eventinfoobj.eventid).removeClass('fa-chevron-down').addClass('fa-minus');	
        }else{
           $('#oddschilds_'+eventinfoobj.eventid).html('').hide();
           $('#icon_'+eventinfoobj.eventid).removeClass('fa-minus').addClass('fa-chevron-down');
           return false;
       }
   }
    //response will render after 5 seconds
    $.ajax({
        url: "/_sub/ajax/sub_odds_bet_display.php",
        type: "POST",
        data: eventinfoobj,
        dataType: "html",
        // async:false,
        success: function(result){

			//display result
			$('#oddschilds_'+eventinfoobj.eventid).html(result);
			
        }
    });

}*/


/**
* @Description: league sport page data display 
* @author: lokendra meena
* @created: 30-05-2018 
*/

function result_displayall_sub_oddsbest(eventinfoobj,isRefresh){
	
	if(isRefresh==false){
		//display the result 

		if($('#oddschilds_'+eventinfoobj.eventid+':visible').length == 0){
			//show loader on odds bet childs
         $('#detailCont_'+eventinfoobj.eventid).css("display","block");
         $('#oddschilds_'+eventinfoobj.eventid).html('<img  src="/images/loader-img.gif" style="width: 35px;" alt="loader"> ').show();
         $('#icon_'+eventinfoobj.eventid).removeClass('fa-arrow-circle-down').addClass('fa-arrow-circle-up');	
     }else{
       $('#oddschilds_'+eventinfoobj.eventid).html('').hide();
       $('#detailCont_'+eventinfoobj.eventid).css("display","none");
       $('#icon_'+eventinfoobj.eventid).removeClass('fa-arrow-circle-up').addClass('fa-arrow-circle-down');
       return false;
   }
}
    //response will render after 5 seconds
    $.ajax({
        url: "./ajax/result_sub_odds_bet_display.php",
        type: "POST",
        data: eventinfoobj,
        dataType: "html",
        // async:false,
        success: function(result){

			//display result
			$('#oddschilds_'+eventinfoobj.eventid).html(result);
			
			//side scroll moving
			//initMoving();
        }
    });

}

 

/**
* league sport page  auto count down timers 
* @author: lokendra meena
* @created: 30-05-2018 
*/

function refreshcountdown(){
    if(globalobj.countdowntimer < 0){
        globalobj.countdowntimer = 10;
    }else{
        globalobj.countdowntimer = globalobj.countdowntimer - 1;

    }
    var getcountdowntimer = globalobj.countdowntimer;
    $("#refresh-countdown").text(getcountdowntimer);
    
    setTimeout(refreshcountdown,1000);
}

/**
* league sport & live page page loading manages
* @author: lokendra meena
*  
*/

function pageLoading(){
	
	var acc = document.getElementsByClassName("accordion");
	var i;

	for (i = 0; i < acc.length; i++) {
		acc[i].addEventListener("click", function() {
			/* Toggle between adding and removing the "active" class,
			to highlight the button that controls the panel */
			this.classList.toggle("active");

			/* Toggle between hiding and showing the active panel */
			var panel = this.nextElementSibling;
			if (panel.style.display === "block") {
				panel.style.display = "none";
			} else {
				panel.style.display = "block";
			}
		});
	}
	
}