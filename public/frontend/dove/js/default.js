var slot_games = [];
$(document).ready(function(){
    setInterval(function () {
      updateLastBet();  
    }, 10000);
    updateLastBet();
});
$(document).mouseup(function(e) 
{
    var container = $("#profile-drop");

    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0) 
    {
        container.hide();
    }
});
function openNav() {
    $('.slideout-wrapper').css('display', 'block');
}
  
function closeNav() {
    $('.slideout-wrapper').css('display', 'none');
}
function logOut() {
  top.location.href="/logout";
}
function updateRandomLastBet(){

}
function updateLastBet() {
  $.ajax({
    url: '/api/last_bet',
    type: 'POST',
    dataType: "json",
    data : null,
    success: function(result) {
        if (result.error == false)
        {
          const div = document.getElementById('last_bet_list');
          var htmlasthistory = ``;
          var datas = result.data;
          for(var i = 0; i < datas.length; i++){
            htmlasthistory += `<div data-v-fad191c6="" class="data row" style="flex-direction: row;">
                          <div data-v-fad191c6="" class="row" style="flex-direction: row;">
                              <span data-v-fad191c6="" class="game-name text">${datas[i].game}</span>
                          </div> 
                          <div data-v-fad191c6="" class="row" style="flex-direction: row;">
                              <span data-v-fad191c6="" class="text">${datas[i].username}</span>
                          </div> 
                          <div data-v-fad191c6="" class="row" style="flex-direction: row;">
                              <span data-v-fad191c6="" class="text">${datas[i].time}</span>
                          </div> 
                          <div data-v-fad191c6="" class="row" style="flex-direction: row;">
                              <span data-v-fad191c6="" class="text">₩${datas[i].betamount}</span>
                          </div> 
                          <div data-v-fad191c6="" class="row" style="flex-direction: row;">
                              <span data-v-fad191c6="" class="text">${datas[i].odd}x</span>
                          </div> 
                          <div data-v-fad191c6="" class="row" style="flex-direction: row;">
                              <span data-v-fad191c6="" class="win text">₩${datas[i].winamount}</span>
                          </div>
                      </div>`;
          }
          div.innerHTML = htmlasthistory;
        }
    }
  });
}
function PointToMoney() {
  Swal.fire({
    title: '',
    text: '모든 포인트를 머니로 변환하시겠습니까?',
    icon: 'warning',
    
    showCancelButton: false, // cancel버튼 보이기. 기본은 원래 없음
    confirmButtonColor: '#3085d6', // confrim 버튼 색깔 지정
    confirmButtonText: '확인', // confirm 버튼 텍스트 지정
    
    reverseButtons: true, // 버튼 순서 거꾸로
    
 }).then(result => {
    // 만약 Promise리턴을 받으면,
    if (result.isConfirmed) { // 만약 모달창에서 confirm 버튼을 눌렀다면    
      $.ajax({
        url: '/api/convert_deal_balance',
        type: 'POST',
        dataType: "json",
        data : null,
        success: function(result) {
  
            if (result.error == false)
            {
                swal2('전환되었습니다', 'success', ()=>{window.location.href = "/";});
            }else{
              swal2(result.msg, 'error');
            }
        }
      });
    }
 });
}
function showProfile(){
  if($('#profile-drop').css('display') == 'flex')
    $('#profile-drop').css('display', 'none');
  else
    $('#profile-drop').css('display', 'flex');
}
function openLoginModal(logo, banks) {    
    const div = document.getElementById('main-modal');

    var logincontent = `<div id="login-modal" class="dialog row" style="flex-direction: row;">
    <div class="container row" style="flex-direction: row; max-width: 400px;">
      <button class="close-button button" style="background: rgb(44, 48, 58);" onclick="closeModal('main-modal');">
        <i data-v-e56d064c="" class="fa-solid fa-times" style="color: rgb(255, 255, 255);"></i>
      </button>
      <div data-v-6194c674="" class="container column">
        <div data-v-6194c674="" class="logo-wrap row" style="flex-direction: row;">
            <img src="/frontend/${logo}/logo/${logo}.png">
        </div>
        <div data-v-6194c674="" class="header-text row" style="flex-direction: row;">
          <span data-v-6194c674="" class="text-level-5-5 text">회원님의 접속을 환영합니다</span>
        </div>
        <div data-v-6194c674="" class="form-wrap column">
          <div data-v-6194c674="" class="form column">
            <input data-v-578d3222="" data-v-6194c674="" type="text" placeholder="아이디를 입력하세요" inputmode="text" class="input"  id="sID">
            <input data-v-578d3222="" data-v-6194c674="" type="password" placeholder="비밀번호를 입력하세요" inputmode="text" class="input" id="sPASS">
            <button data-v-6194c674="" class="login-btn button block" onclick="submitLogin();"style="height: 45px;">
              <span data-v-6194c674="" class="text">로그인</span>
            </button>
          </div>
          <div data-v-6194c674="" class="spacer"></div>
          <div data-v-6194c674="" class="forgot-password row" style="flex-direction: row;">
            <div data-v-6194c674="" class="row" style="height: 25px; flex-direction: row;">
              <div data-v-f92ca952="" data-v-6194c674="" class="row" style="flex-direction: row;">
                <div class="row" style="width: 100%; flex-direction: row;">
                  <button data-v-6194c674="" class="margin-left-5 button text" style="color: rgb(255, 255, 255); background: transparent;" onclick="openRegisterModal('${logo}','${banks}');">
                    <span data-v-6194c674="" class="text">회원가입</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>`;
  div.innerHTML = logincontent;
}
function closeModal(modalname){
    document.getElementById(modalname).innerHTML = "";
    if(modalname == "dehitory-modal"){
      document.getElementById(modalname).remove();
    }
}
function openRegisterModal(logo, banks) {
    if(Array.isArray(banks)){
      var banklist = '';
      for(var k = 0; k < banks.length; k++){
          banklist += `<option class="vs__dropdown-option" value="${banks[k]}">${banks[k]}</option>`;
      }
    }else{
      var tempbanks = banks.split(',');
      var banklist = '';
      for(var k = 0; k < tempbanks.length; k++){
          banklist += `<option class="vs__dropdown-option" value="${tempbanks[k]}">${tempbanks[k]}</option>`;
      }
    }
    
    const div = document.getElementById('main-modal');
    var logincontent = `<div class="dialog row" id="register-popup" style="flex-direction: row;">
    <div class="container row" style="flex-direction: row; max-width: 400px;">
      <button class="close-button button" style="background: rgb(44, 48, 58);" onclick="closeModal('main-modal');">
        <i data-v-e56d064c="" class="fa-solid fa-times" style="color: rgb(255, 255, 255);"></i>
      </button>
      <div data-v-06860046="" class="container column">
        <div data-v-06860046="" class="logo-wrap row" style="flex-direction: row;">
        <img src="/frontend/${logo}/logo/${logo}.png">
        </div>
        <div data-v-06860046="" class="header-text row" style="flex-direction: row;">
          <span data-v-06860046="" class="text-level-5-5 text">도브카지노에 오신 것을 환영합니다</span>
        </div>
        <div data-v-06860046="" class="header-text-2 row" style="flex-direction: row;">
          <span data-v-06860046="" class="text-level-9 text">회원가입 후 프로모에 참가하세요</span>
        </div>
        <div data-v-06860046="" class="form-wrap scrollable-auto column" id="freg">
          <div data-v-06860046="" class="form column">
            <span data-v-06860046="" class="title text">아이디</span>
            <div data-v-06860046="" class="column">
              <input data-v-578d3222="" data-v-06860046="" type="text" placeholder="아이디를 입력하세요" inputmode="text" class="input" id="reg-username" style="background-color: rgb(44, 48, 58);">
            </div>
            <span data-v-06860046="" class="title text">비밀번호</span>
            <div data-v-06860046="" class="column">
              <input data-v-578d3222="" data-v-06860046="" type="password" placeholder="비밀번호를 입력하세요" inputmode="text" class="input" id="reg-password" style="background-color: rgb(44, 48, 58);">
            </div>
            <span data-v-06860046="" class="title text">비밀번호확인</span>
            <div data-v-06860046="" class="column">
              <input data-v-578d3222="" data-v-06860046="" type="password" placeholder="비밀번호를 다시 입력하세요" inputmode="text" class="input" id="reg-cpassword" style="background-color: rgb(44, 48, 58);">
            </div>            
            <div data-v-06860046="" class="margin-0 row" style="flex-direction: row;">
              <span data-v-06860046="" class="title text">휴대폰번호</span>
            </div>
            <div data-v-06860046="" class="column">
              <div data-v-06860046="" class="row" style="flex-direction: row;">
                <input data-v-578d3222="" data-v-06860046="" type="text" placeholder="휴대폰번호를 입력하세요" inputmode="text" class="input" id="reg-phone" style="width: unset; background-color: rgb(44, 48, 58); flex-grow: 2;">
                <!---->
              </div>
            </div>
            <!---->
            <span data-v-06860046="" class="title text">은행명</span>
            <div data-v-06860046="" class="column">
              <div data-v-06860046="" dir="auto" class="v-select vs--single vs--unsearchable">
                <div id="vs5__combobox" role="combobox" aria-expanded="false" aria-owns="vs5__listbox" aria-label="Search for option" class="vs__dropdown-toggle">                  
                  <select class="vs__selected-options" name="bank_name" id="reg-bankname">
                    ${banklist}
                  </select>
                </div>
              </div>
            </div>
            <span data-v-06860046="" class="title text">계좌번호</span>
            <div data-v-06860046="" class="column">
              <input data-v-578d3222="" data-v-06860046="" type="text" placeholder="계좌번호를 입력하세요" onkeypress="isInputNumber(event)" inputmode="text" class="input" id="reg-accountnum" style="background-color: rgb(44, 48, 58);">
            </div>
            <span data-v-06860046="" class="title text">예금주</span>
            <div data-v-06860046="" class="column">
              <input data-v-578d3222="" data-v-06860046="" type="text" placeholder="예금주를 입력하세요" inputmode="text" class="input" id="reg-name" style="background-color: rgb(44, 48, 58);">
            </div>            
            <span data-v-06860046="" class="title text">추천인코드</span>
            <div data-v-06860046="" class="column">
              <input data-v-578d3222="" data-v-06860046="" type="text" placeholder="추천인코드를 입력하세요" inputmode="text" class="input" id="reg-refercode" style="background-color: rgb(44, 48, 58);">
            </div>
            <div data-v-06860046="" class="column">
              <button data-v-06860046="" class="signin-btn button" id="reg-continue" onclick="submitRegister()" style="height: 45px;">
                <span data-v-06860046="" class="text">가입완료</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>`;
  div.innerHTML = logincontent;
}

function swal2(message, status='success', callback=null){ // status : 'success', 'warning', 'error'
    Swal.fire(
        '',
        message,
        status
      ).then(result => {
        // 만약 Promise리턴을 받으면,
        if (result.isConfirmed) { // 만약 모달창에서 confirm 버튼을 눌렀다면    
          if(callback != null)
          {
            callback();
          }
        }
     });
}


function swalPop(message, status='success'){ // status : 'success', 'warning', 'error'
  Swal.fire({
    title: '',
    text: message,
    icon: 'warning',
    
    showCancelButton: false, // cancel버튼 보이기. 기본은 원래 없음
    confirmButtonColor: '#3085d6', // confrim 버튼 색깔 지정
    confirmButtonText: '확인', // confirm 버튼 텍스트 지정
    
    reverseButtons: true, // 버튼 순서 거꾸로
    
 }).then(result => {
    // 만약 Promise리턴을 받으면,
    if (result.isConfirmed) { // 만약 모달창에서 confirm 버튼을 눌렀다면
    
      window.location.href = "/";
    }
 });
}


// show content
function showContent(id) {
    $('#main_content').css('display', 'none');
    $('#live_content').css('display', 'none');
    $('#slot_content').css('display', 'none');
    $('#notice_content').css('display', 'none');

    $('#' + id).css('display', 'flex');
    closeNav();
}

function scrolRight(id) {
    var leftPos = $('#' + id).scrollLeft();
    $('#' + id).animate({scrollLeft: leftPos + 200}, 0);
}
function scrolLeft(id) {
    var leftPos = $('#' + id).scrollLeft();
    $('#' + id).animate({scrollLeft: leftPos - 200}, 0);
}



var sID = '';
var sPASS = '';
var ajaxStart = false;

var ip = "";
var provider = "";
var address = "";




$('#sPASS, #sID').keypress(function(e) {
  $(".sbmt-login").removeAttr('style');
  if (e.which == 13) {
    $(".sbmt-login").click();
  }
});
function submitLogin() {
  var site_url = "yj-u.com";
  $(this).addClass('is-loading disabled');
  sID = $("#sID").val();
  sPASS = $("#sPASS").val();
  var csrf_token = $("#token").val();
  var location = localStorage.getItem('location');
  var provider = localStorage.getItem('provider');
  if (ajaxStart == true) {
    return false;
  } else {
    ajaxStart = true;
loginx();
  }
}

$("#sID").on('input', function() {
  $(this).removeClass('error');
  $(".error-text").text("");
});
$("#sPASS").on('input', function() {
  $(this).removeClass('error');
  $(".error-text").text("");
});


function loginx() {

  if (ajaxStart == false) {
    return false;
  }
  var site_url = "yj-u.com";
  var location = localStorage.getItem('location');
  var provider = localStorage.getItem('provider');

  var isRemembered = $(".auto_login").prop('checked');

  if (sID == '' || sPASS == '') {
    $(".error-text").text("정확한 정보를 입력해주세요.");
    $(".error-text").removeAttr('style');
    $("#sID2").addClass('error');
    $("#sPASS2").addClass('error');
    $(".sbmt-login").removeClass('is-loading disabled');
    ajaxStart = false;
  } else {
    var data = {
      'username': sID,
      'password': sPASS,
    };
    $('#loading-page').show();
    $.ajax({
      url: '/api/login',
      type: 'POST',
      data: data,
      dataType: "json",
      success: function(result) {
        $('#loading-page').hide();
        $(".sbmt-login").removeClass('is-loading disabled');
        if (result.error==false) {
          if (isRemembered) {
            localStorage.setItem("sID", sID);
            localStorage.setItem("sPASS", sPASS);
          } else {
            localStorage.setItem("sID", "");
            localStorage.setItem("sPASS", "");
          }
          window.location.href = "/";
        } else {
          // swal2(result.msg,"error").then(function() {
          //   window.location.reload();
          // });
          swal2(result.msg,"error");
        }
        ajaxStart = false;
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        $('#loading-page').hide();
        swal2("some error");
      }
    });
  }
}

$(document).on('input', '#reg-username', function() {
  let vl = $(this).val();
  let patt = /[a-zA-Z0-9]/g;
  if (vl == '') {
    $(this).parent().removeClass('has-success');
    $(this).parent().addClass('has-error');
  } else if (vl.length < 4) {
    $(this).parent().removeClass('has-success');
    $(this).parent().addClass('has-error');
  } else if (!patt.test(vl)) {
    $(this).parent().addClass('has-success');
    $(this).parent().removeClass('has-error');
  } else {
    $(this).parent().addClass('has-success');
    $(this).parent().removeClass('has-error');
  }
});

$(document).on('input', '#reg-password', function() {
  let vl = $(this).val();
  if (vl == '' || vl.length < 6) {
    $(this).parent().removeClass('has-success');
    $(this).parent().addClass('has-error');
  } else if (vl.length < 6) {
    $(this).parent().removeClass('has-success');
    $(this).parent().addClass('has-error');
  } else {
    $(this).parent().addClass('has-success');
    $(this).parent().removeClass('has-error');
  }
});
$(document).on('input', '#reg-cpassword', function() {
  let vl = $(this).val();
  let np = $("#reg-cpassword").val();
  $(".reg-cpassword").text('');
  if (vl == '') {
    $(this).parent().removeClass('has-success');
    $(this).parent().addClass('has-error');
  } else if (np != vl) {
    $(this).parent().removeClass('has-success');
    $(this).parent().addClass('has-error');
    $(".reg-cpassword").text('Password not match');
  } else {
    $(this).parent().addClass('has-success');
    $(this).parent().removeClass('has-error');
  }
});

$(document).on('input', '#reg-name', function() {
  let vl = $(this).val();
  if (vl == '') {
    $(this).parent().removeClass('has-success');
    $(this).parent().addClass('has-error');
  } else {
    $(this).parent().addClass('has-success');
    $(this).parent().removeClass('has-error');
  }
});
$(document).on('input', '#reg-phone', function() {
  let vl = $(this).val();
  if (vl == '') {
    $(this).parent().parent().removeClass('has-success');
    $(this).parent().parent().addClass('has-error');
  } else if (vl.length < 11) {
    $(this).parent().parent().removeClass('has-success');
    $(this).parent().parent().addClass('has-error');
  } else {
    $(this).parent().parent().addClass('has-success');
    $(this).parent().parent().removeClass('has-error');
  }
});
$("#reg-phone").on('keypress', function(e) {
  if ($(this).val().length > 10) {
    return false;
  }
});
$(document).on('input', '#reg-accountnum', function() {
  let vl = $(this).val();
  if (vl == '') {
    $(this).parent().removeClass('has-success');
    $(this).parent().addClass('has-error');
  } else {
    $(this).parent().addClass('has-success');
    $(this).parent().removeClass('has-error');
  }
});

$(document).on('change', '#reg-bankname', function() {
  let vl = $(this).val();
  if (vl == '') {
    $(this).parent().parent().parent().removeClass('has-success');
    $(this).parent().parent().parent().addClass('has-error');
  } else {
    $(this).parent().parent().parent().addClass('has-success');
    $(this).parent().parent().parent().removeClass('has-error');
  }
})


$(document).on('input', '#reg-refercode', function() {
  let vl = $(this).val();
  let patt = /[a-zA-Z0-9]/g;
  if (vl == '') {
    $(this).parent().removeClass('has-success');
    $(this).parent().addClass('has-error');
  } else if (vl.length < 4) {
    $(this).parent().removeClass('has-success');
    $(this).parent().addClass('has-error');
  } else if (!patt.test(vl)) {
    $(this).parent().addClass('has-success');
    $(this).parent().removeClass('has-error');
  } else {
    $(this).parent().addClass('has-success');
    $(this).parent().removeClass('has-error');
  }
});
$(document).on('input', '#register-popup input', function() {
  if ($('#register-popup .has-success').length == 8) {
    $('#reg-continue').removeAttr('disabled');
  } else {
    $('#reg-continue').attr('disabled', 'disabled');
  }
});

function submitRegister() {

  var formData = $("#freg").serializeArray();
  var hasError = false;
  $('#register-popup input').trigger('input');
  $('#register-popup select').trigger('change');
  let err = $('#register-popup .has-success').length;
  if (err < 8) {
    return false;
  }

  var data = {
    'friend': $("#reg-refercode").val(),
    'username': $("#reg-username").val(),
    'password': $("#reg-password").val(),
    'passwordcw': $("#reg-cpassword").val(),
    'bank_name': $("#reg-bankname").val(),
    'recommender': $("#reg-name").val(),
    'account_no': $("#reg-accountnum").val(),
    'tel1': $("#reg-phone").val(),
  };

  $('#loading-page').show();
  $.ajax({
    url: '/api/join',
    type: 'POST',
    data: data,
    dataType: "json",
    async: false,
    success: function(result) {
      $('#loading-page').hide();
      if (result.error == false) {
        $("#freg input").val('');
        swal2(result.msg);
      } else {
        swalPop(result.msg);
      }
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
      $('#loading-page').hide();
      swal2(result.msg,"error");
    }
  });
};


function isInputNumber(evt) {
  var ch = String.fromCharCode(evt.which);
  if (!(/[0-9]/.test(ch))) {
    evt.preventDefault();
  }
}


//Deposit

function openDepositModal(username){
  const div = document.getElementById('main-modal');
    var depositcontent = `<div class="dialog row" style="flex-direction: row;">
							<div class="container row" style="flex-direction: row; max-width: 500px;">
								<button class="close-button button" style="background: rgb(44, 48, 58);" onclick="closeModal('main-modal');">
									<i data-v-e56d064c="" class="fa-solid fa-times" style="color: rgb(255, 255, 255);"></i>
								</button>  
								<div data-v-5290ad82="" class="fill-height">
									<div data-v-5290ad82="" class="container column">
										<div data-v-5290ad82="" class="dialog-title row" style="flex-direction: row;">
											<span data-v-5290ad82="" class="text-level-7 text"><img data-v-5290ad82="" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IS0tIFVwbG9hZGVkIHRvOiBTVkcgUmVwbywgd3d3LnN2Z3JlcG8uY29tLCBHZW5lcmF0b3I6IFNWRyBSZXBvIE1peGVyIFRvb2xzIC0tPgo8c3ZnIHdpZHRoPSI4MDBweCIgaGVpZ2h0PSI4MDBweCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8cGF0aCBkPSJNMTkgOFY4QzIwLjEwNDYgOCAyMSA4Ljg5NTQzIDIxIDEwVjE4QzIxIDE5LjEwNDYgMjAuMTA0NiAyMCAxOSAyMEg2QzQuMzQzMTUgMjAgMyAxOC42NTY5IDMgMTdWN0MzIDUuMzQzMTUgNC4zNDMxNSA0IDYgNEgxN0MxOC4xMDQ2IDQgMTkgNC44OTU0MyAxOSA2VjhaTTE5IDhIN00xNyAxNEgxNiIgc3Ryb2tlPSIjZmZmZmZmIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4=" class="margin-right-5" style="width: 20px; height: 20px;">입금신청</span>
										</div> 
										<div data-v-5290ad82="" class="margin-bottom-10 column">
											<div data-v-5290ad82="" class="title row" style="flex-direction: row;">
												<span data-v-5290ad82="" class="text">입금방식</span>
											</div> <div data-v-5290ad82="" class="deposit-method row" style="flex-direction: row; flex-shrink: 0;">
												<div data-v-5290ad82="" dir="auto" class="v-select vs--single vs--unsearchable"> 
													<div id="vs20__combobox" role="combobox" aria-expanded="false" aria-owns="vs20__listbox" aria-label="Search for option" class="vs__dropdown-toggle">
                            <select class="vs__selected-options" value="현금">
                              <option class="vs__dropdown-option" value="1">현금</option>
                            </select>
													</div> 
												</div> 
												<div data-v-5290ad82="" class="detail-btn-wrap row" style="flex-direction: row;">
													<button data-v-5290ad82="" class="detail-btn button" onclick="depositGuidePop();">
														<span data-v-5290ad82="" class="text"><i data-v-e56d064c="" data-v-5290ad82="" class="fa-solid fa-circle-question fa-lg"></i></span>
													</button>
												</div>
											</div>
										</div> 
										<div data-v-5290ad82="" class="margin-bottom-10 column">
											<div data-v-5290ad82="" class="title row" style="flex-direction: row;">
												<span data-v-5290ad82="" class="text">입금자명</span>
											</div> 
											<div data-v-5290ad82="" class="row" style="flex-direction: row;">
												<input data-v-578d3222="" data-v-5290ad82="" type="text" inputmode="text" disabled="disabled" class="input account-name" value="${username}">
											</div>
										</div> 
										<div data-v-5290ad82="" class="margin-bottom-10 column">
											<div data-v-5290ad82="" class="title row" style="flex-direction: row;">
												<span data-v-5290ad82="" class="text">입금 금액</span>
											</div> 
											<div data-v-5290ad82="" class="margin-bottom-10 row" style="flex-direction: row;">
												<input data-v-578d3222="" data-v-5290ad82="" id="charge_money" type="text" placeholder="입금하실 금액을 입력해주세요" inputmode="numeric" class="input deposit-money money">
                        <button data-v-43e90682="" class="reset-button button" onclick="resetMoney()"><i data-v-e56d064c="" data-v-43e90682="" class="fa-solid fa-xmark"></i></button>
											</div> 
											<div data-v-5290ad82="" class="deposit-money-btn row" style="flex-direction: row;">
												<button data-v-5290ad82="" class="button" onclick="addMoneyDeposit(10000)">
													<span data-v-5290ad82="" class="text">10,000</span>
												</button> 
												<button data-v-5290ad82="" class="button" onclick="addMoneyDeposit(30000)">
													<span data-v-5290ad82="" class="text">30,000</span>
												</button> 
												<button data-v-5290ad82="" class="button" onclick="addMoneyDeposit(50000)">
													<span data-v-5290ad82="" class="text">50,000</span>
												</button> 
												<button data-v-5290ad82="" class="button" onclick="addMoneyDeposit(100000)">
													<span data-v-5290ad82="" class="text">100,000</span>
												</button> 
												<button data-v-5290ad82="" class="button" onclick="addMoneyDeposit(500000)">
													<span data-v-5290ad82="" class="text">500,000</span>
												</button> 
												<button data-v-5290ad82="" class="button" onclick="addMoneyDeposit(1000000)">
													<span data-v-5290ad82="" class="text">1,000,000</span>
												</button>
											</div>
										</div> 
										<div data-v-5290ad82="" class="margin-bottom-10 column">
											<div data-v-5290ad82="" class="button-wrap row" style="flex-direction: row;">
												<div data-v-40c960e6="" data-v-5290ad82="" class="row" style="flex-direction: row;">
													<div class="row" style="width: 100%; flex-direction: row;">
														<button data-v-5290ad82="" class="history button text" style="background: transparent;" onclick="depositHistoryPop();">
															<span data-v-5290ad82="" class="text"><i data-v-e56d064c="" data-v-5290ad82="" class="margin-right-5 fa-solid fa-list"></i>입금내역</span>
														</button>
													</div> <!---->
												</div> 
												<div data-v-5290ad82="" class="spacer"></div> 
                        <div style="padding-right: 15px;">
                        <button data-v-5290ad82="" class="withdraw button" onclick="autorequest();" style="background-color: #2c303a;border-radius: 8px;">빠른 계좌문의</button> 
                        </div>
                        
                        <!---->
                        <div>
                        <button data-v-5290ad82="" class="deposit-button button" onclick="depositRequest();">입금 신청</button> 
                        </div>
												
												<div data-v-3b808694="" data-v-5290ad82="" class="row" style="flex-direction: row;">
													<div class="row" style="width: 100%; flex-direction: row;"></div> <!---->
												</div> 
												<div data-v-75458f4a="" data-v-5290ad82="" class="row" style="flex-direction: row;">
													<div class="row" style="width: 100%; flex-direction: row;"></div> <!---->
												</div>
											</div>
										</div>
									</div> <!---->
								</div>
							</div>
						</div>`;
  div.innerHTML = depositcontent;
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

function addMoneyDeposit(money) {
  var str = $("#charge_money").val();
  if (str == null || str.length == 0) str = "0";
  str = replaceComma(str);
  var betMoney = parseInt(str);
  betMoney += money;
  $("#charge_money").val(insertComma("" + betMoney));
}

  function depositGuidePop(){
    const div = document.getElementById('pop-modal');
    var depositguidecontent = `<div class="swal2-container swal2-center v-application swal2-backdrop-show" style="overflow-y: auto;">
          <div aria-labelledby="swal2-title" aria-describedby="swal2-html-container" class="swal2-popup swal2-modal swal2-show" tabindex="-1" role="dialog" aria-live="assertive" aria-modal="true" style="display: grid;">
              <button type="button" class="swal2-close" aria-label="Close this dialog" style="display: none;">×</button>
              <ul class="swal2-progress-steps" style="display: none;"></ul>
              <div class="swal2-icon" style="display: none;"></div>
              <img class="swal2-image" style="display: none;">
              <h2 class="swal2-title" id="swal2-title" style="display: none;"></h2>
              <div class="swal2-html-container" id="swal2-html-container" style="display: block;">
                  <p><span style="color: rgb(239, 198, 49);">수표입금시 입금처리 절대 되지 않습니다.</span></p>
                  <p><span style="color: rgb(239, 198, 49);">23:50 ~ 00:30, 휴일 다음 첫 영업일 새벽에는 은행점검으로 인해 계좌이체가 지연될 수 있습니다.</span></p>
                  <p><span style="color: rgb(239, 198, 49);">위 시간 이외에도 몇몇 은행은 추가적 점검시간이 따로 있으니 이점 유념하시기 바랍니다. </span></p>
                  <p><br></p>
              </div>
              <div class="swal2-actions" style="display: flex;">
                  <div class="swal2-loader"></div>
                  <button type="button" class="swal2-confirm v-btn v-btn--depressed theme--dark v-size--default primary mx-1" aria-label="" style="display: inline-block;"  onclick="closeModal('pop-modal');">확인</button>
              </div>
          </div>
      </div>`;
  div.innerHTML = depositguidecontent;
  }


  function depositHistoryPop(){    
    const div = document.getElementById('main-modal');
    var dehistorycontent= `<div class="dialog row" id="dehitory-modal" style="flex-direction: row; ">
  <div class="container row" style="flex-direction: row; max-width: 450px; min-height:250px">
    <button class="close-button button" style="background: rgb(44, 48, 58);" onclick="closeModal('dehitory-modal');">
      <i data-v-e56d064c="" class="fa-solid fa-times" style="color: rgb(255, 255, 255);"></i>
    </button>  
    <div data-v-40c960e6="" class="container column"><div data-v-40c960e6="" class="dialog-title row" style="flex-direction: row;">
      <span data-v-40c960e6="" class="text-level-7 text"><img data-v-40c960e6="" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IS0tIFVwbG9hZGVkIHRvOiBTVkcgUmVwbywgd3d3LnN2Z3JlcG8uY29tLCBHZW5lcmF0b3I6IFNWRyBSZXBvIE1peGVyIFRvb2xzIC0tPgo8c3ZnIHdpZHRoPSI2MHB4IiBoZWlnaHQ9IjYwcHgiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTYuNzMgMTkuN0M3LjU1IDE4LjgyIDguOCAxOC44OSA5LjUyIDE5Ljg1TDEwLjUzIDIxLjJDMTEuMzQgMjIuMjcgMTIuNjUgMjIuMjcgMTMuNDYgMjEuMkwxNC40NyAxOS44NUMxNS4xOSAxOC44OSAxNi40NCAxOC44MiAxNy4yNiAxOS43QzE5LjA0IDIxLjYgMjAuNDkgMjAuOTcgMjAuNDkgMTguMzFWNy4wNEMyMC41IDMuMDEgMTkuNTYgMiAxNS43OCAySDguMjJDNC40NCAyIDMuNSAzLjAxIDMuNSA3LjA0VjE4LjNDMy41IDIwLjk3IDQuOTYgMjEuNTkgNi43MyAxOS43WiIgc3Ryb2tlPSIjZmZmZmZmIiBzdHJva2Utd2lkdGg9IjEuNSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+Cjwvc3ZnPg==" class="margin-right-5" style="width: 20px; height: 20px;">입금내역</span>
    </div> 
    <div data-v-40c960e6="" class="fill-height" id="mydeposit">
    </div>
  </div>
</div>`;
    div.innerHTML += dehistorycontent;
    mydepositlist();
  }

  function mydepositlist() {
    $('#loading-page').show();
    $.ajax({
      type: "POST",
      cache: false,
      async: true,
      url: '/api/inouthistory',
      dataType: 'json',
      data : {type: 'add'},
      success: function(data) {
        $('#loading-page').hide();
        if(data.error == false){
          var html = `<tbody style="width: 100%;max-width: 100%;margin-bottom: 20px;">
                <tr>
                  <th style="padding: 5px;text-align: center;vertical-align: middle; width:10%">번호</td>
                  <th style="padding: 5px;text-align: center;vertical-align: middle; width:10%">충전금액</td>
                  <th style="padding: 5px;text-align: center;vertical-align: middle; width:10%">신청날짜</td>
                  <th style="padding: 5px;text-align: center;vertical-align: middle; width:10%">상태</td>
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
                <td style="padding: 5px;text-align: center;vertical-align: middle;">${i+1}</td>
                <td style="padding: 5px;text-align: center;vertical-align: middle;">${parseInt(data.data[i].sum).toLocaleString()}원</td>
                <td style="padding: 5px;text-align: center;vertical-align: middle;">${date.toLocaleString()}</td>
                <td style="padding: 5px;text-align: center;vertical-align: middle;">${status_name[data.data[i].status]}</td>
                </tr>
                </thead>`;
            }
            
          }else{
            html += `<tr><td colspan="12" style="text-align: center;padding-top: 20px;">데이터가 없습니다.</td></tr>`;
          }
          html += `</table>`;
          $("#mydeposit").html(html);
          
        } else {
          $('#loading-page').hide();
          swal2(data.msg, "error");
        }
      }
    });
  }
  function openProfileModal(username, balance, dealbalance){
    const div = document.getElementById('main-modal');
      var depositcontent = `<div class="dialog row" style="flex-direction: row;">
                <div class="container row" style="flex-direction: row; max-width: 500px;">
                  <button class="close-button button" style="background: rgb(44, 48, 58);" onclick="closeModal('main-modal');">
                    <i data-v-e56d064c="" class="fa-solid fa-times" style="color: rgb(255, 255, 255);"></i>
                  </button>  
                  <div data-v-5290ad82="" class="fill-height">
                    <div data-v-5290ad82="" class="container column">
                      <div data-v-5290ad82="" class="dialog-title row" style="flex-direction: row;">
                        <span data-v-5290ad82="" class="text-level-7 text"><img data-v-5290ad82="" src="/frontend/dove/assets/img/mypage-icon.6ac2d7a.svg" class="margin-right-5" style="width: 20px; height: 20px;">마이페이지</span>
                      </div> 
                      <div data-v-5290ad82="" class="margin-bottom-10 column">
                        <div data-v-5290ad82="" class="title row" style="flex-direction: row;">
                          <span data-v-5290ad82="" class="text">닉네임</span>
                        </div> 
                        <div data-v-5290ad82="" class="row" style="flex-direction: row;">
                          <input data-v-578d3222="" data-v-5290ad82="" type="text" inputmode="text" disabled="disabled" class="input account-name" value="${username}">
                        </div>
                      </div> 
                      <div data-v-5290ad82="" class="margin-bottom-10 column">
                        <div data-v-5290ad82="" class="title row" style="flex-direction: row;">
                          <span data-v-5290ad82="" class="text">보유금</span>
                        </div> 
                        <div data-v-5290ad82="" class="row" style="flex-direction: row;">
                          <input data-v-578d3222="" data-v-5290ad82="" type="text" inputmode="text" disabled="disabled" class="input account-name" value="${balance}">
                        </div>
                      </div> 
                      <div data-v-5290ad82="" class="margin-bottom-10 column">
                        <div data-v-5290ad82="" class="title row" style="flex-direction: row;">
                          <span data-v-5290ad82="" class="text">보유포인트</span>
                        </div> 
                        <div data-v-5290ad82="" class="row" style="flex-direction: row;">
                          <input data-v-578d3222="" data-v-5290ad82="" type="text" inputmode="text" disabled="disabled" class="input account-name" value="${dealbalance}">
                        </div>
                      </div> 
                    </div> <!---->
                  </div>
                </div>
              </div>`;
    div.innerHTML = depositcontent;
  }
  function startGameByProvider(provider, gamecode,max = false) {
    var formData = new FormData();
    formData.append("provider", provider);
    formData.append("gamecode", gamecode);
    $('#loading-page').show();
    $.ajax({
    type: "POST",
    url: "/api/getgamelink",
    data: formData,
    processData: false,
    contentType: false,
    cache: false,
    async: false,
    success: function (data) {
      $('#loading-page').hide();
      if (data.error) {
        swal2(data.msg, "error");
        return;
      }
      if (max)
      {
        window.open(data.data.url, "game", "width=" + screen.width + ", height=" + screen.height + ", left=100, top=50");
      }else{
        window.open(data.data.url, "game", "width=1280, height=720, left=100, top=50");
      }
    },
    complete: function() {
      $('#loading-page').hide();
    }
    });
    
   }
  function slotGame(category, title){
    const div = document.getElementById('main-modal');
    $('#loading-page').show();
    $.ajax({
      type: "POST",
      url: "/api/getgamelist",
      data: {category : category},
      cache: false,
      async: true,
      success: function (data, status) {
        if (data.error) {
          swal2(data.msg, "error");
          return;
        }
        if (data.games.length > 0) {
          slot_games =  JSON.stringify(data.games);
          var innerlistcss = `list-4`;
          if(window.innerWidth > 960)
          {
            innerlistcss = `list-6`;
          }
          var htmgames = `<div data-v-4f69ab08="" class="casino-list row ${innerlistcss}" style="flex-direction: row; overflow:scroll">`;
          for (i=0;i<data.games.length;i++)
          {
            if (data.games[i].provider)
            {
              htmgames += `<button data-v-4f69ab08="" id="slot_${i}" class="zoomIn button" onclick="startGameByProvider('${data.games[i].provider}', '${data.games[i].gamecode}');">
                            <img data-v-4f69ab08="" src="${data.games[i].icon}">
                            <span style="display:none"><p>${data.games[i].title}</p></span>
                          </button>`;
            }
            else
            {
              htmgames += `<button data-v-4f69ab08="" id="slot_${i}" class="zoomIn button" onclick="startGameByProvider(null, '${data.games[i].name}');">
                            <img data-v-4f69ab08="" src="/frontend/Default/ico/${data.games[i].name}.jpg">
                            <span style="display:none"><p>${data.games[i].title}</p></span>
                          </button>`;
            }
          }
          htmgames += `</div>`;
          var htmldoc = `<div class="dialog row" id="dehitory-modal" style="flex-direction: row;">
                          <div class="container row" style="flex-direction: row; max-width:1480px; max-height:95%; min-height:95%; padding:10px">
                            <button class="close-button button" style="background: rgb(44, 48, 58);" onclick="closeModal('dehitory-modal');">
                              <i data-v-e56d064c="" class="fa-solid fa-times" style="color: rgb(255, 255, 255);"></i>
                            </button>  
                            <div data-v-40c960e6="" class="container column">
                              <div data-v-40c960e6="" class="dialog-title row" style="flex-direction: row;flex-shrink:0;">
                                <span data-v-40c960e6="" class="text-level-7 text">${title}</span>
                              </div>
                              <div data-v-4f69ab08="" class="search-bar row" style="flex-direction: row;">
                                <div data-v-4f69ab08="" class="row" style="flex-direction: row; background-color: #22262e;">
                                  <input data-v-578d3222="" data-v-4f69ab08="" id="slot_search" type="text" placeholder="검색할 게임을 입력하세요" inputmode="text" class="input input" style="background-color: #22262e;" onchange="slotSearchFunc()">
                                  <button data-v-4f69ab08="" class="search-btn button" style="background-color: #22262e;" onclick="slotSearchFunc();">
                                    <i data-v-e56d064c="" data-v-4f69ab08="" class="fa-regular fa-magnifying-glass" style="padding: 0px;"></i>
                                  </button>
                                  <!---->
                                </div>
                              </div>
                              ${htmgames}
                            </div>
                          </div>
                        </div> `;
          div.innerHTML = htmldoc;
        }
        else
        {
          swal2("게임이 없습니다", "error");
        }
      },
      complete: function() {
        $('#loading-page').hide();
      }
    });
    closeNav();
  }
  function casinoGameStart(category){
    $('#loading-page').show();
    $.ajax({
       type: "POST",
       url: "/api/getgamelist",
       data: {category : category},
       cache: false,
       async: true,
       success: function (data, status) {
        $('#loading-page').hide();
         if (data.error) {
          swal2(data.msg, "error");
           return;
         }
         if (data.games.length > 0) {
         startGameByProvider(data.games[0].provider, data.games[0].gamecode);
         }
         else
         {
          swal2("게임실행 오류", "error");
         }
        },
        complete: function() {
          $('#loading-page').hide();
        }
       });
   }
  
  function startGameByProvider(provider, gamecode,max = false) {
    var formData = new FormData();
    formData.append("provider", provider);
    formData.append("gamecode", gamecode);
    $('#loading-page').show();
    $.ajax({
    type: "POST",
    url: "/api/getgamelink",
    data: formData,
    processData: false,
    contentType: false,
    cache: false,
    async: false,
    success: function (data) {
      $('#loading-page').hide();
      if (data.error) {
        swal2(data.msg, "error");
        return;
      }
          if (max)
         {
           window.open(data.data.url, "game", "width=" + screen.width + ", height=" + screen.height + ", left=100, top=50");
         }else{
           window.open(data.data.url, "game", "width=1280, height=720, left=100, top=50");
         }
    },
    complete: function() {
      $('#loading-page').hide();
    }
    });
    
   }
   function slotSearchFunc(){
    var searchText = $('#slot_search').val();
    var games = JSON.parse(slot_games);
    for (i=0;i<games.length;i++)
    {
      if(games[i]['title'].toLowerCase().indexOf(searchText.toLowerCase()) !== -1)
      {
        $('#slot_' + i).css('display', 'block');
      }else{
        $('#slot_' + i).css('display', 'none');
      }
    }
   }

  function autorequest(){
    var f = confirm("계좌요청을 하시겠습니까?");
    if(!f){
        return false;
    }
    $('#loading-page').show();
    $.ajax({
        type: "POST",
        url: "/api/depositAccount",
        data: null,
        cache: false,
        async: false,
        success: function (data) {
            $('#loading-page').hide();
            if (data.error) {
                swal2(data.msg, "error");
                return;
            }
            if (data.url != null)
            {
                var leftPosition, topPosition;
                width = 600;
                height = 1000;
                leftPosition = (window.screen.width / 2) - ((width / 2) + 10);
                topPosition = (window.screen.height / 2) - ((height / 2) + 50);
                wndGame = window.open(data.url, "Deposit",
                "status=no,height=" + height + ",width=" + width + ",resizable=yes,left="
                + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY="
                + topPosition + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no");
            }
            else
            {
                swal2(data.msg, "error");
            }
            
        },
        error: function (err, xhr) {
            $('#loading-page').hide();
            
        },
    });
}

function depositRequest() {
  var cmoney = $('.money').val();
  var cmoneyx = $('.money').val().replace(/,/g, '');
  var y = parseNumberToInt(cmoney);
  var x = 10000;
  var remainder = Math.floor(y % x);
  if (remainder != 0) {
      swal2('입금신청은 만원단위로 가능합니다. 만원단위로 신청해주시기 바랍니다.', "error");
      return false;
  }
  var conf = confirm('입금신청을 하시겠습니까?');
  if (!conf) {
      return false;
  }
  if (cmoney <= 0) {
      swal2('신청하실 충전금액을 입력해주세요.', "error");
      return false;
  }
  $('#loading-page').show();
  $.ajax({
      url: '/api/addbalance',
      type: 'POST',
      dataType: "json",
      data: {
      money: cmoney,
      },
      success: function(result) {
        $('#loading-page').hide();
        if (result.error == false) {
            $("#charge_money").val(0);
            swal2('신청완료 되었습니다.');
        } else {
            swal2( result.msg);
        }
      },
      error: function (err, xhr) {
          $('#loading-page').hide();
          
      }
  });
  $(".btn-pointr").on('click', function() {
      $(".btn-pointr").removeClass('active');
      $(this).addClass('active');
      isReceivedPoint = $(this).attr('data-type');
  });
}

function parseNumberToInt(val) {
  val = val.replace(/[^\/\d]/g, '');
  return parseInt(val);
}




//Withdraw
function openWithdrawModal(userbalance){
  const div = document.getElementById('main-modal');
  var wdcontent = `<div class="dialog row" style="flex-direction: row;">
    <div class="container row" style="flex-direction: row; max-width: 500px;">
        <button class="close-button button" style="background: rgb(44, 48, 58);" onclick="closeModal('main-modal');">
           <i data-v-e56d064c="" class="fa-solid fa-times" style="color: rgb(255, 255, 255);"></i>
        </button>  
        <div data-v-43e90682="" class="container column">
            <div data-v-43e90682="" class="dialog-title row" style="flex-direction: row;"> 
             <span data-v-43e90682="" class="text-level-7 text"><img data-v-43e90682="" src="/frontend/dove/assets/img/withdraw-icon.9e0a7e8.svg" class="margin-right-5" style="width: 20px; height: 20px;">출금신청</span> 
        </div> 
        <div data-v-43e90682="" class="margin-bottom-10 column">
            <div data-v-43e90682="" class="title row" style="flex-direction: row;"> 
                <span data-v-43e90682="" class="text">보유금액</span>
            </div> 
            <div data-v-43e90682="" class="row" style="flex-direction: row;">
                <input data-v-578d3222="" data-v-43e90682="" type="text" inputmode="numeric" disabled="disabled" class="input account-name" value="${userbalance}">
            </div>
        </div>         
        <div data-v-43e90682="" class="margin-bottom-10 column">
            <div data-v-43e90682="" class="title row" style="flex-direction: row;">
                <span data-v-43e90682="" class="text">출금금액</span>
            </div> 
            <div data-v-43e90682="" class="margin-bottom-10 row" style="flex-direction: row;">
                <input data-v-578d3222="" data-v-43e90682="" type="text" placeholder="출금하실 금액을 입력해주세요" id="exchange_money" inputmode="numeric" class="input withdraw-money exchange_money"> 
                <button data-v-43e90682="" class="reset-button button" onclick="resetMoney()"><i data-v-e56d064c="" data-v-43e90682="" class="fa-solid fa-xmark"></i></button>
            </div> 
            <div data-v-43e90682="" class="withdraw-money-btn row" style="flex-direction: row;">
                <button data-v-43e90682="" class="button"><span data-v-43e90682="" class="text" onclick="addMoneyWithdraw(10000)">10,000</span></button> 
                <button data-v-43e90682="" class="button"><span data-v-43e90682="" class="text" onclick="addMoneyWithdraw(30000)">30,000</span></button> 
                <button data-v-43e90682="" class="button"><span data-v-43e90682="" class="text" onclick="addMoneyWithdraw(50000)">50,000</span></button> 
                <button data-v-43e90682="" class="button"><span data-v-43e90682="" class="text" onclick="addMoneyWithdraw(100000)">100,000</span></button> 
                <button data-v-43e90682="" class="button"><span data-v-43e90682="" class="text" onclick="addMoneyWithdraw(500000)">500,000</span></button> 
                <button data-v-43e90682="" class="button"><span data-v-43e90682="" class="text" onclick="addMoneyWithdraw(1000000)">1,000,000</span></button>
            </div>
        </div> 
        <div data-v-43e90682="" class="margin-bottom-10 column">
            <div data-v-43e90682="" class="button-wrap row" style="flex-direction: row;">
                <div data-v-29f40e58="" data-v-43e90682="" class="row" style="flex-direction: row;">
                    <div class="row" style="width: 100%; flex-direction: row;">
                        <button data-v-43e90682="" class="history button text" style="background: transparent;" onclick="withdrawHistoryPop();">
                            <span data-v-43e90682="" class="text"><i data-v-e56d064c="" data-v-43e90682="" class="margin-right-5 fa-solid fa-list" ></i>출금내역</span>
                        </button>
                    </div> <!---->
                </div> 
                <div data-v-43e90682="" class="spacer"></div> 
                <div data-v-43e90682="" class="detail-btn-wrap row" style="flex-direction: row;">
                    <button data-v-43e90682="" class="detail-btn button" onclick="WithdrawGuidePop();"><span data-v-43e90682="" class="text"><i data-v-e56d064c="" data-v-43e90682="" class="fa-solid fa-circle-question fa-lg"></i></span></button>
                </div> <!----> 
                <button data-v-43e90682="" class="withdraw-button button" onclick="withdrawRequest();">출금신청</button>
            </div>
        </div>
    </div> <!---->
</div>`;
  div.innerHTML = wdcontent;
}

function addMoneyWithdraw(money) {
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
  var obj1 = $('#charge_money').val(0);
}

function WithdrawGuidePop(){
  const div = document.getElementById('pop-modal');
  var wdguidecontent = `<div class="swal2-container swal2-center v-application swal2-backdrop-show" style="overflow-y: auto;">
        <div aria-labelledby="swal2-title" aria-describedby="swal2-html-container" class="swal2-popup swal2-modal swal2-show" tabindex="-1" role="dialog" aria-live="assertive" aria-modal="true" style="display: grid;">
            <button type="button" class="swal2-close" aria-label="Close this dialog" style="display: none;">×</button>
            <ul class="swal2-progress-steps" style="display: none;"></ul>
            <div class="swal2-icon" style="display: none;"></div>
            <img class="swal2-image" style="display: none;">
            <h2 class="swal2-title" id="swal2-title" style="display: none;"></h2>
            <div class="swal2-html-container" id="swal2-html-container" style="display: block;">
                <p><span style="color: rgb(239, 198, 49);">입금자명과 출금자명이 다를경우 본인확인 요청이 있을 수 있습니다.</span></p>
            </div>
            <div class="swal2-actions" style="display: flex;">
                <div class="swal2-loader"></div>
                <button type="button" class="swal2-confirm v-btn v-btn--depressed theme--dark v-size--default primary mx-1" aria-label="" style="display: inline-block;"  onclick="closeModal('pop-modal');">확인</button>
            </div>
        </div>
    </div>`;
div.innerHTML = wdguidecontent;
}

function withdrawHistoryPop(){    
  const div = document.getElementById('main-modal');
  var wdhistorycontent= `<div class="dialog row" id="dehitory-modal" style="flex-direction: row; ">
<div class="container row" style="flex-direction: row; max-width: 450px;min-height:250px">
  <button class="close-button button" style="background: rgb(44, 48, 58);" onclick="closeModal('dehitory-modal');">
    <i data-v-e56d064c="" class="fa-solid fa-times" style="color: rgb(255, 255, 255);"></i>
  </button>  
  <div data-v-40c960e6="" class="container column"><div data-v-40c960e6="" class="dialog-title row" style="flex-direction: row;">
    <span data-v-40c960e6="" class="text-level-7 text"><img data-v-40c960e6="" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IS0tIFVwbG9hZGVkIHRvOiBTVkcgUmVwbywgd3d3LnN2Z3JlcG8uY29tLCBHZW5lcmF0b3I6IFNWRyBSZXBvIE1peGVyIFRvb2xzIC0tPgo8c3ZnIHdpZHRoPSI2MHB4IiBoZWlnaHQ9IjYwcHgiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTYuNzMgMTkuN0M3LjU1IDE4LjgyIDguOCAxOC44OSA5LjUyIDE5Ljg1TDEwLjUzIDIxLjJDMTEuMzQgMjIuMjcgMTIuNjUgMjIuMjcgMTMuNDYgMjEuMkwxNC40NyAxOS44NUMxNS4xOSAxOC44OSAxNi40NCAxOC44MiAxNy4yNiAxOS43QzE5LjA0IDIxLjYgMjAuNDkgMjAuOTcgMjAuNDkgMTguMzFWNy4wNEMyMC41IDMuMDEgMTkuNTYgMiAxNS43OCAySDguMjJDNC40NCAyIDMuNSAzLjAxIDMuNSA3LjA0VjE4LjNDMy41IDIwLjk3IDQuOTYgMjEuNTkgNi43MyAxOS43WiIgc3Ryb2tlPSIjZmZmZmZmIiBzdHJva2Utd2lkdGg9IjEuNSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+Cjwvc3ZnPg==" class="margin-right-5" style="width: 20px; height: 20px;">출금내역</span>
  </div> 
  <div data-v-40c960e6="" class="fill-height" id="mywithdraw">
  </div>
</div>
</div>`;
  div.innerHTML += wdhistorycontent;
  mywithdrawlist();
}

function mywithdrawlist() {
  $('#loading-page').show();
  $.ajax({
      type: "POST",
      cache: false,
      async: true,
      url: '/api/inouthistory',
      dataType: 'json',
      data : {type: 'out'},
      success: function(data) {
        $('#loading-page').hide();
        if(data.error == false){
            var html = `<tbody style="width: 100%;max-width: 100%;margin-bottom: 20px;">
                        <tr>
                            <th style="padding: 5px;text-align: center;vertical-align: middle; width:10%">번호</td>
                            <th style="padding: 5px;text-align: center;vertical-align: middle; width:10%">환전금액</td>
                            <th style="padding: 5px;text-align: center;vertical-align: middle; width:10%">신청날짜</td>
                            <th style="padding: 5px;text-align: center;vertical-align: middle; width:10%">상태</td>
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
                        <td style="padding: 5px;text-align: center;vertical-align: middle;">${i+1}</td>
                        <td style="padding: 5px;text-align: center;vertical-align: middle;">${parseInt(data.data[i].sum).toLocaleString()}원</td>
                        <td style="padding: 5px;text-align: center;vertical-align: middle;">${date.toLocaleString()}</td>
                        <td style="padding: 5px;text-align: center;vertical-align: middle;">${status_name[data.data[i].status]}</td>
                        </tr>
                        </thead>`;
                }
                
            }else{
              html += `<tr><td colspan="12" style="text-align: center;padding-top: 20px;">데이터가 없습니다.</td></tr>`;
            }
            html += `</table>`;
            $("#mywithdraw").html(html);
            
        } else {
            swal2(data.msg, "error");
        }
      },
      error: function (err, xhr) {
          $('#loading-page').hide();
          
      }
    });
}



function withdrawRequest() {
  var cmoney = $('.exchange_money').val();
  var y = parseNumberToInt(cmoney);
  var x = 10000;
  var remainder = Math.floor(y % x);
  if (remainder != 0) {
    swal2('출금신청은 만원단위로 가능합니다. 만원단위로 신청해주시기 바랍니다.');
    return false;
  }
  var conf = confirm('출금신청을 하시겠습니까?');
  if (!conf) {
    return false;
  }
  if (cmoney <= 0) {
    // alert('Invalid Amount');
    swal2('정확한 금액을 입력해주세요');
    return false;
  }
  $('#loading-page').show();
  $.ajax({
    url: '/api/outbalance',
    type: 'POST',
    dataType: "json",
    data: {
      money: cmoney,
    },
    success: function(result) {
      $('#loading-page').hide();
      if (result.error == false) {
        $("#exchange_money").val(0);
        swal2('신청완료 되었습니다.');
      } else {
          swal2(result.msg);
      }
    },
    error: function (err, xhr) {
        $('#loading-page').hide();
        
    }
  })
}


//1:1 Request
function openRequestPop(){
  const div = document.getElementById('main-modal');
  var requestcontent= `<v class="dialog row" style="flex-direction: row;">
  <div class="container row" style="flex-direction: row; max-width: 500px;">
    <button class="close-button button" style="background: rgb(44, 48, 58);" onclick="closeModal('main-modal');">
      <i data-v-e56d064c="" class="fa-solid fa-times" style="color: rgb(255, 255, 255);"></i>
    </button>  
    <div data-v-ca1f65a4="" class="container column" id="request-modal">
      <div data-v-ca1f65a4="" class="dialog-title row" style="flex-direction: row;">
        <span data-v-ca1f65a4="" class="text-level-7 text">
          <img data-v-ca1f65a4="" src="/frontend/dove/assets/img/inquiry-icon.75f60ef.svg" class="margin-right-5" style="width: 20px; height: 20px;">1:1문의
        </span>
      </div> 
      <div data-v-ca1f65a4="" class="inquiry-list column" id="inquiry-list">
        <div data-v-ca1f65a4="" class="fill-height">
          <div data-v-ca1f65a4="" class="list-wrap column">
            <div data-v-ca1f65a4="" class="list scrollable-auto column"> 
              <div data-v-ca1f65a4="" class="column" id="customerList" style=" align-items: center;">
                <!--<span data-v-ca1f65a4="" class="text" style="opacity: 0.6;">작성된 글이 없습니다</span>-->
                
              </div>
            </div> 
            <div data-v-ca1f65a4="" class="margin-bottom-10 padding-horizontal-10 row" style="flex-direction: row;">
              <button data-v-ca1f65a4="" class="remove-btn padding-horizontal-10 button" style="height: 30px; display:none;">
                <span data-v-ca1f65a4="" class="text">선택내역삭제</span>
              </button> <div data-v-ca1f65a4="" class="spacer">              
            </div> 
            <button data-v-ca1f65a4="" class="write-btn padding-horizontal-10 button" style="height: 30px;" onclick="openWriteMsgPop();">
              <span data-v-ca1f65a4="" class="text"><i data-v-e56d064c="" data-v-ca1f65a4="" class="margin-right-5 fa-solid fa-pen-to-square" ></i>문의작성</span>
            </button>
          </div> 
        </div>
      </div>
    </div>
  </div>
</div>`;
  div.innerHTML += requestcontent;
  getCustomerPage();
}
var msg_data = null;
function getCustomerPage() {
	$.ajax({
        type: "POST",
        cache: false,
        async: true,
        url: '/api/messages',
        dataType: 'json',
        success: function(data) {
        if(data.error == false){
      var html = ``;
			if (data.data.length > 0) {
        msg_data = data.data;
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
                html += `<div data-v-ca1f65a4="" class="list column" style="height:60px;width:100%">
            <button data-v-ca1f65a4="" class="button text" style="background: transparent;" onclick="showMsg(${i});">
              <div data-v-ca1f65a4="" class="inquiry column">
                <div data-v-ca1f65a4="" class="margin-bottom-5 row" style="flex-direction: row; align-items: center;">
                  <span data-v-ca1f65a4="" class="text-ellipsis text" style="display: inline-block;">${data.data[i].title}</span> 
                  <div data-v-ca1f65a4="" class="spacer"></div> 
                </div> 
                <div data-v-ca1f65a4="" class="row" style="flex-direction: row;">
                  <span data-v-ca1f65a4="" class="text" style="opacity: 0.6;">${date.toLocaleString()}</span> 
                  <div data-v-ca1f65a4="" class="spacer"></div> 
                  <span data-v-ca1f65a4="" class="text" style="opacity: 0.6;"> ${type} </span>
                </div>
              </div>
            </button>
          </div>`;
				}
        // html +=`</tbody>
        //         </table>`;
				
			}else{
        html = `<span data-v-ca1f65a4="" class="text" style="opacity: 0.6;">작성된 글이 없습니다</span>`;
      }
			$("#customerList").html(html);
            
			
        } else {
            swal2(data.msg);
        }
    }
    });
}

function openWriteMsgPop(){
  const inqdiv = document.getElementById('inquiry-list');
  inqdiv.style.display="none";
  const reqdiv = document.getElementById('request-modal');
  var reqcontent= `<div data-v-ca1f65a4="" class="column" id="requset-write">
    <div data-v-ca1f65a4="" class="write-form column">
        <div data-v-ca1f65a4="" class="row" style="flex-direction: row; border-bottom: 1px solid rgb(35, 38, 46);">
            <div data-v-ca1f65a4="" class="category row" style="flex-direction: row;"><div data-v-ca1f65a4="" dir="auto" class="v-select vs--single vs--unsearchable"> 
            <ul id="vs13__listbox" role="listbox" style="display: none; visibility: hidden;"></ul> 
        </div>
    </div> 
    <input data-v-578d3222="" data-v-ca1f65a4="" type="text" placeholder="제목" inputmode="text" id="txt_title" class="input">
</div> 
<div data-v-63cabf15="" data-v-ca1f65a4="" class="editr">
    
    <textarea contenteditable="true" tabindex="1" placeholder="내용을 입력하세요." id="content_txt" class="editr--content" style="resize:none;"></textarea>
</div> <!----> 
<div data-v-ca1f65a4="" class="row" style="flex-direction: row; margin-top: 10px; margin-bottom: 10px;">
    <button data-v-ca1f65a4="" class="cancel-btn button" style="height: 30px;" onclick="cancelWrite();">
        <span data-v-ca1f65a4="" class="text">작성취소</span>
    </button> 
    <div data-v-ca1f65a4="" class="spacer"></div> 
    <button data-v-ca1f65a4="" class="write-btn button" style="height: 30px;" onclick="send_text();">
        <span data-v-ca1f65a4="" class="text">작성완료</span>
    </button>
</div>`;
reqdiv.innerHTML += reqcontent;
}


function cancelWrite(){
  const reqdiv = document.getElementById('requset-write');
  reqdiv.remove();
  const inqdiv = document.getElementById('inquiry-list');
  inqdiv.style.display="block";
 
}

function send_text() {
  var title = $("#txt_title").val();
  var message = $('#content_txt').val();
  if ((title == '') || (message == '')) 
  {
    swal2('제목과 내용을 입력해주세요', 'error');
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
              swal2('저장 되었습니다');
              $("#txt_title").val('');
              $('#content_txt').val('');
              getCustomerPage();
              // },2000);
            }
            else 
            {
              swal2('Opps!', result.msg, "error");
            }
          }
        });
  }              
}


function showMsg(id){
  var objId = msg_data[id].id;
  var objContent = msg_data[id].content;
  var objTitle = msg_data[id].title;
  $.post('/api/readMsg',{id : objId},function(data){
  }); 
            
  const inqdiv = document.getElementById('inquiry-list');
  inqdiv.style.display="none";
  const reqdiv = document.getElementById('request-modal');
  var reqcontent= `<div data-v-ca1f65a4="" class="column" id="requset-write">
    <div data-v-ca1f65a4="" class="write-form column">
        <div data-v-ca1f65a4="" class="row" style="flex-direction: row; border-bottom: 1px solid rgb(35, 38, 46);">
            <div data-v-ca1f65a4="" class="category row" style="flex-direction: row;"><div data-v-ca1f65a4="" dir="auto" class="v-select vs--single vs--unsearchable"> 
            <ul id="vs13__listbox" role="listbox" style="display: none; visibility: hidden;"></ul> 
        </div>
    </div> 
    <input data-v-578d3222="" data-v-ca1f65a4="" type="text" value='${objTitle}' inputmode="text" id="txt_title" class="input" readonly>
</div> 
<div data-v-63cabf15="" data-v-ca1f65a4="" class="editr">
    
    <div contenteditable="false" tabindex="1" placeholder="내용을 입력하세요." id="content_txt" class="editr--content" style="resize:none;" readonly>`;
    reqcontent += objContent;
    reqcontent += `</div>
</div> <!----> 
<div data-v-ca1f65a4="" class="row" style="flex-direction: row; margin-top: 10px; margin-bottom: 10px;">
    <button data-v-ca1f65a4="" class="cancel-btn button" style="height: 30px; display:none;" onclick="cancelWrite();">
        <span data-v-ca1f65a4="" class="text">작성취소</span>
    </button> 
    <div data-v-ca1f65a4="" class="spacer"></div> 
    <button data-v-ca1f65a4="" class="write-btn button" style="height: 30px;" onclick="cancelWrite();">
        <span data-v-ca1f65a4="" class="text">완료</span>
    </button>
</div>`;

reqdiv.innerHTML += reqcontent;

}




//Notice
function openNoticePanel(){
  const div = document.getElementById('main-modal');
  var requestcontent= `<v class="dialog row" style="flex-direction: row;">
  <div class="container row" style="flex-direction: row; max-width: 500px;">
    <button class="close-button button" style="background: rgb(44, 48, 58);" onclick="closeModal('main-modal');">
      <i data-v-e56d064c="" class="fa-solid fa-times" style="color: rgb(255, 255, 255);"></i>
    </button>  
    <div data-v-ca1f65a4="" class="container column" id="request-modal">
      <div data-v-ca1f65a4="" class="dialog-title row" style="flex-direction: row;">
        <span data-v-ca1f65a4="" class="text-level-7 text">
          <img data-v-ca1f65a4="" src="/frontend/dove/assets/img/inquiry-icon.75f60ef.svg" class="margin-right-5" style="width: 20px; height: 20px;">공지사항
        </span>
      </div> 
      <div data-v-ca1f65a4="" class="inquiry-list column" id="inquiry-list">
        <div data-v-ca1f65a4="" class="fill-height">
          <div data-v-ca1f65a4="" class="list-wrap column">
            <div data-v-ca1f65a4="" class="list scrollable-auto column"> 
              <div data-v-ca1f65a4="" class="column" id="noticeList" style=" align-items: center;">
                <!--<span data-v-ca1f65a4="" class="text" style="opacity: 0.6;">작성된 글이 없습니다</span>-->
                
              </div>
            </div> 
            <div data-v-ca1f65a4="" class="margin-bottom-10 padding-horizontal-10 row" style="flex-direction: row;">
              <button data-v-ca1f65a4="" class="remove-btn padding-horizontal-10 button" style="height: 30px; display:none;">
                <span data-v-ca1f65a4="" class="text">선택내역삭제</span>
              </button> <div data-v-ca1f65a4="" class="spacer">              
            </div> 
            <button data-v-ca1f65a4="" class="write-btn padding-horizontal-10 button" style="height: 30px; display:none;" onclick="openWriteMsgPop();">
              <span data-v-ca1f65a4="" class="text"><i data-v-e56d064c="" data-v-ca1f65a4="" class="margin-right-5 fa-solid fa-pen-to-square" ></i>문의작성</span>
            </button>
          </div> 
        </div>
      </div>
    </div>
  </div>
</div>`;
  div.innerHTML += requestcontent;
  getNoticeList();
}

function openBetHistoryPanel(){
  const div = document.getElementById('main-modal');
  var requestcontent = `<div class="dialog row" style="flex-direction: row;">
                <div class="container row" style="flex-direction: row; max-width: 600px;">
                  <button class="close-button button" style="background: rgb(44, 48, 58);" onclick="closeModal('main-modal');">
                    <i data-v-e56d064c="" class="fa-solid fa-times" style="color: rgb(255, 255, 255);"></i>
                  </button>
                  <div data-v-db5cba90="" class="container column">
                    <div data-v-db5cba90="" class="dialog-title row" style="flex-direction: row;">
                      <span data-v-db5cba90="" class="text-level-7 text">
                        <img data-v-db5cba90="" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IS0tIFVwbG9hZGVkIHRvOiBTVkcgUmVwbywgd3d3LnN2Z3JlcG8uY29tLCBHZW5lcmF0b3I6IFNWRyBSZXBvIE1peGVyIFRvb2xzIC0tPgo8c3ZnIHdpZHRoPSI2MHB4IiBoZWlnaHQ9IjYwcHgiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTYuNzMgMTkuN0M3LjU1IDE4LjgyIDguOCAxOC44OSA5LjUyIDE5Ljg1TDEwLjUzIDIxLjJDMTEuMzQgMjIuMjcgMTIuNjUgMjIuMjcgMTMuNDYgMjEuMkwxNC40NyAxOS44NUMxNS4xOSAxOC44OSAxNi40NCAxOC44MiAxNy4yNiAxOS43QzE5LjA0IDIxLjYgMjAuNDkgMjAuOTcgMjAuNDkgMTguMzFWNy4wNEMyMC41IDMuMDEgMTkuNTYgMiAxNS43OCAySDguMjJDNC40NCAyIDMuNSAzLjAxIDMuNSA3LjA0VjE4LjNDMy41IDIwLjk3IDQuOTYgMjEuNTkgNi43MyAxOS43WiIgc3Ryb2tlPSIjZmZmZmZmIiBzdHJva2Utd2lkdGg9IjEuNSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+Cjwvc3ZnPg==" class="margin-right-5" style="width: 20px; height: 20px;">베팅로그 </span>
                    </div>
                    <div data-v-db5cba90="" class="fill-height">
                      <div data-v-db5cba90="" class="mybet-list margin-top-10 column">
                        <div data-v-db5cba90="" class="select-type row" style="flex-direction: row;">
                          <button data-v-db5cba90="" id="btn_table" class="button active" onclick="getBetHistory('table');"> 라이브카지노 </button>
                          <button data-v-db5cba90="" id="btn_slot" class="button" onclick="getBetHistory('slot');"> 슬롯머신 </button>
                          <button data-v-db5cba90="" id="btn_sports" class="button" onclick="getBetHistory('sports');"> 스포츠 </button>
                          <button data-v-db5cba90="" id="btn_pball" class="button" onclick="getBetHistory('pball');"> 파워볼 </button>
                        </div>
                        <div data-v-db5cba90="" id="bethistory_list" class="list scrollable-auto margin-bottom-10 column">
                          <table data-v-db5cba90="">
                            <thead data-v-db5cba90="">
                              <tr data-v-db5cba90="">
                                <th data-v-db5cba90="">게임명</th>
                                <th data-v-db5cba90="">베팅시간</th>
                                <th data-v-db5cba90="">베팅금액</th>
                                <th data-v-db5cba90="">배당</th>
                                <th data-v-db5cba90="">당첨금액</th>
                              </tr>
                            </thead>
                            <tbody data-v-db5cba90="" id="bethistory_body">
                              
                            </tbody>
                          </table>
                        </div>
                        <div data-v-30f53f18="" data-v-db5cba90="" class="pagination row" style="flex-direction: row; align-items: center;">
                          <div data-v-30f53f18="" class="column" style="flex-direction: row;">
                            <div data-v-30f53f18="" dir="auto" class="v-select vs--single vs--unsearchable" style="margin-right: 5px;">
                              <div id="vs11__combobox" role="combobox" aria-expanded="false" aria-owns="vs11__listbox" aria-label="Search for option" class="vs__dropdown-toggle">
                                <select class="vs__selected-options" name="bethistory_page_count" style="background-color:#22262e;" id="bethistory_page_count">
                                  <option class="vs__dropdown-option" value="5" style="background-color:#22262e;">5</option>
                                  <option class="vs__dropdown-option" value="10" style="background-color:#22262e;">10</option>
                                  <option class="vs__dropdown-option" value="20" style="background-color:#22262e;">20</option>
                                  <option class="vs__dropdown-option" value="50" style="background-color:#22262e;">50</option>
                                  <option class="vs__dropdown-option" value="100" style="background-color:#22262e;">100</option>
                                  <option class="vs__dropdown-option" value="200" style="background-color:#22262e;">200</option>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div data-v-30f53f18="" class="row" style="flex-direction: row; align-items: center;">
                            <span data-v-30f53f18="" class="text" style="opacity: 0.6;"> 개 씩 표시 </span>
                          </div>
                          <div data-v-30f53f18="" class="spacer"></div>
                          <div data-v-30f53f18="" class="row" style="flex-direction: row;">
                            <div data-v-30f53f18="" dir="auto" class="v-select vs--single vs--unsearchable" style="margin-right: 5px;">
                              <div id="vs12__combobox" role="combobox" aria-expanded="false" aria-owns="vs12__listbox" aria-label="Search for option" class="vs__dropdown-toggle">
                              <select class="vs__selected-options" name="bethistory_page_num" style="background-color:#22262e;" id="bethistory_page_num">
                                
                              </select>
                              </div>
                            </div>
                          </div>
                          <div data-v-30f53f18="" id="btn_bethistory_before_page" class="row" style="flex-direction: row;">
                            
                          </div>
                          <div data-v-30f53f18="" id="btn_bethistory_next_page" class="row" style="flex-direction: row;">
                            
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>`;
  div.innerHTML += requestcontent;
  $("#bethistory_page_count").val(10);
  getBetHistory('table');
}
$(document).on('change', '#bethistory_page_count', function() {
  var gametype = '';
  if($('#btn_table').hasClass('active')){
    gametype = 'table';
  }else if($('#btn_slot').hasClass('active')){
    gametype = 'slot';
  }else if($('#btn_sports').hasClass('active')){
    gametype = 'sports';
  }else if($('#btn_pball').hasClass('active')){
    gametype = 'pball';
  }
  getBetHistory(gametype, 1);
})
$(document).on('change', '#bethistory_page_num', function() {
  var gametype = '';
  if($('#btn_table').hasClass('active')){
    gametype = 'table';
  }else if($('#btn_slot').hasClass('active')){
    gametype = 'slot';
  }else if($('#btn_sports').hasClass('active')){
    gametype = 'sports';
  }else if($('#btn_pball').hasClass('active')){
    gametype = 'pball';
  }
  getBetHistory(gametype, $("#bethistory_page_num").val());
})
function getBetHistory(gametype, pagenum){
  $('#btn_table').removeClass('active');
  $('#btn_slot').removeClass('active');
  $('#btn_sports').removeClass('active');
  $('#btn_pball').removeClass('active');
  $('#btn_' + gametype).addClass('active');

  $('#loading-page').show();
  $.ajax({
    url: '/api/mybethistory',
    type: 'POST',
    dataType: "json",
    data: {
      type: gametype,
      pagecount:$("#bethistory_page_count").val(),
      pagenum:pagenum
    },
    success: function(result) {
      $('#loading-page').hide();
      if (result.error == false) {
        stats = result.data.stats;
        var first = result.data.first;
        var last = result.data.last;
        var current = result.data.current;
        pagination = result.data.pagination;
        var htmlhistory = ``;
        var htmlpagenumlist = ``;
        var htmlbeforepage = ``;
        var htmlnextpage = ``;
        if(stats.length > 0){
          for(var i = 0; i < stats.length; i++)
          {
            var color = `#22262e`
            if(i % 2 == 0)
            {
              color = `rgb(41, 46, 56)`;
            }
            htmlhistory += `<tr><td data-v-db5cba90="" colspan="1" style="background-color: ${color};">
                              <span data-v-db5cba90="" class="text" style="justify-content: center;"> ${stats[i].game} </span>
                            </td>
                            <td data-v-db5cba90="" colspan="1" style="background-color: ${color};">
                              <span data-v-db5cba90="" class="text" style="justify-content: center;"> ${stats[i].time} </span>
                            </td>
                            <td data-v-db5cba90="" colspan="1" style="background-color: ${color};">
                              <span data-v-db5cba90="" class="text" style="justify-content: center;"> ₩${stats[i].betamount} </span>
                            </td>
                            <td data-v-db5cba90="" colspan="1" style="background-color: ${color};">
                              <span data-v-db5cba90="" class="text" style="justify-content: center;"> ${stats[i].odd} </span>
                            </td>
                            <td data-v-db5cba90="" colspan="1" style="background-color: ${color};">
                              <span data-v-db5cba90="" class="win text" style="justify-content: center;"> ₩${stats[i].winamount} </span>
                            </td></tr>`
          }
        }else{
          htmlhistory =  `<td data-v-db5cba90="" colspan="5" style="background-color: rgb(41, 46, 56);">
          <span data-v-db5cba90="" class="text" style="justify-content: center;"> 베팅내역이 없습니다 </span>
        </td>`;
        }
        for(var i = 0; i < last; i++)
        {
          htmlpagenumlist += `<option class="vs__dropdown-option" style="background-color:#22262e;" value="${(i+1)}">${(i+1)}</option>`;
        }
        if(current > first){
          htmlbeforepage = `<button data-v-30f53f18="" class="button icon" onclick="getBetHistory('${gametype}', ${(current - 1)});" style="background: #e3e3e3;">
                            <i data-v-e56d064c="" data-v-30f53f18="" class="fa-solid fa-chevron-left"></i>
                          </button>`;
        }else{
          htmlbeforepage = `<button data-v-30f53f18="" class="button icon" disabled="disabled" style="background: rgb(34, 42, 51);">
                              <i data-v-e56d064c="" data-v-30f53f18="" class="fa-solid fa-chevron-left"></i>
                            </button>`;
        }
        if(current < last){
          htmlnextpage = `<button data-v-30f53f18="" class="button icon" onclick="getBetHistory('${gametype}', ${(current + 1)});"  style="background: #e3e3e3;">
                            <i data-v-e56d064c="" data-v-30f53f18="" class="fa-solid fa-chevron-right"></i>
                          </button>`;
        }else{
          htmlnextpage = `<button data-v-30f53f18="" class="button icon" disabled="disabled" style="background: rgb(34, 42, 51);">
                            <i data-v-e56d064c="" data-v-30f53f18="" class="fa-solid fa-chevron-right"></i>
                          </button>`;
        }
        document.getElementById('bethistory_body').innerHTML = htmlhistory;
        document.getElementById('bethistory_page_num').innerHTML = htmlpagenumlist;
        document.getElementById('btn_bethistory_before_page').innerHTML = htmlbeforepage;
        document.getElementById('btn_bethistory_next_page').innerHTML = htmlnextpage;
        $('#bethistory_page_num').val(current);
      } else {
          swal2(result.msg, "error");
      }
    },
    error: function (err, xhr) {
        $('#loading-page').hide();
    }
  });
}
function getNoticeList() {
	$.ajax({
        type: "POST",
        cache: false,
        async: true,
        url: '/api/notices',
        dataType: 'json',
        success: function(data) {
        if(data.error == false){
      var html = ``;
			if (data.data.length > 0) {
        msg_data = data.data;
				for (var i = 0; i < data.data.length; i++) {
					date = new Date(data.data[i].date_time);
					if (data.data[i].read_at == null)
					{
						read = '읽지 않음';
					}
					else
					{
						date1 = new Date(data.data[i].date_time);
						read = date1.toLocaleString();
					}
					type = (data.data[i].writer_id!='popup')?'팝업':'일반';				
                html += `<div data-v-ca1f65a4="" class="list column" style="height:60px;width:100%">
            <button data-v-ca1f65a4="" class="button text" style="background: transparent;" onclick="showMsg(${i});">
              <div data-v-ca1f65a4="" class="inquiry column">
                <div data-v-ca1f65a4="" class="margin-bottom-5 row" style="flex-direction: row; align-items: center;">
                  <span data-v-ca1f65a4="" class="text-ellipsis text" style="display: inline-block;">${data.data[i].title}</span> 
                  <div data-v-ca1f65a4="" class="spacer"></div> 
                </div> 
                <div data-v-ca1f65a4="" class="row" style="flex-direction: row;">
                  <span data-v-ca1f65a4="" class="text" style="opacity: 0.6;">${date.toLocaleString()}</span> 
                  <div data-v-ca1f65a4="" class="spacer"></div> 
                  <span data-v-ca1f65a4="" class="text" style="opacity: 0.6;"> ${type} </span>
                </div>
              </div>
            </button>
          </div>`;
				}
        // html +=`</tbody>
        //         </table>`;
				
			}else{
        html = `<span data-v-ca1f65a4="" class="text" style="opacity: 0.6;">작성된 글이 없습니다</span>`;
      }
			$("#noticeList").html(html);
            
			
        } else {
            swal2(data.msg);
        }
    }
    });
}


function getCookie(key) {
  var re = new RegExp(key + "=([^;]+)");
  var value = re.exec(document.cookie);
  var tt = (value != null) ? unescape(value[1]) : null;
  return tt;
}

function setCookie( name, value, expiredays ) { 
  var todayDate = new Date(); 
  todayDate.setDate( todayDate.getDate() + expiredays ); 
  document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";" 
  } 

function closeWinpopDay(id) { 
  setCookie( "pop" + id, "done" , 1 ); 

  document.getElementById("pop" + id).style.visibility = "hidden"; 
} 

function closeWinpop(id) {
  document.getElementById("pop" + id).style.visibility = "hidden"; 
}




