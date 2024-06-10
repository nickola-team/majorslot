function openNav() {
    $('.slideout-wrapper').css('display', 'block');
}
  
function closeNav() {
    $('.slideout-wrapper').css('display', 'none');
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

function swal2(message, status='success'){ // status : 'success', 'warning', 'error'
    Swal.fire(
        '',
        message,
        status
      );
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
    $.ajax({
      url: '/api/login',
      type: 'POST',
      data: data,
      dataType: "json",
      success: function(result) {
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

  $(this).addClass('is-loading disabled');
  $.ajax({
    url: '/api/join',
    type: 'POST',
    data: data,
    dataType: "json",
    async: false,
    success: function(result) {
      if (result.error == false) {
        $("#freg input").val('');
        // swal2(result.msg).then((value) => {
        //   $('#register-popup').addClass('ng-hide');
        //   $("fieldset").removeClass('has-success');
        //   $("fieldset").removeClass('has-error');
        // });
        swal2(result.msg);
        // $('#register-popup').addClass('ng-hide');
        // $("fieldset").removeClass('has-success');
        // $("fieldset").removeClass('has-error');
      } else {
        swalPop(result.msg);
        // $("#reg-notice").text(result.message);
        // $("#reg-continue").removeClass('is-loading disabled');
      }
      // $('input[name="token"]').val(result.token);
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
      // alert('zzzz')
      // $("#reg-notice").text("유효하지 않는 코드입니다.");
      swal2(result.msg,"error");
      // $("#reg-continue").removeClass('is-loading disabled');
    }
  });
};


function isInputNumber(evt) {
  var ch = String.fromCharCode(evt.which);
  if (!(/[0-9]/.test(ch))) {
    evt.preventDefault();
  }
}



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
														<div class="vs__selected-options">
															<span class="vs__selected">현금</span> 
															<input readonly="readonly" aria-autocomplete="list" aria-labelledby="vs20__combobox" aria-controls="vs20__listbox" type="search" autocomplete="off" class="vs__search">
														</div> 
														<div class="vs__actions">
															<button type="button" title="Clear Selected" aria-label="Clear Selected" class="vs__clear" style="display: none;">
																<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10">
																	<path d="M6.895455 5l2.842897-2.842898c.348864-.348863.348864-.914488 0-1.263636L9.106534.261648c-.348864-.348864-.914489-.348864-1.263636 0L5 3.104545 2.157102.261648c-.348863-.348864-.914488-.348864-1.263636 0L.261648.893466c-.348864.348864-.348864.914489 0 1.263636L3.104545 5 .261648 7.842898c-.348864.348863-.348864.914488 0 1.263636l.631818.631818c.348864.348864.914773.348864 1.263636 0L5 6.895455l2.842898 2.842897c.348863.348864.914772.348864 1.263636 0l.631818-.631818c.348864-.348864.348864-.914489 0-1.263636L6.895455 5z"></path>
																</svg>
															</button> 
															<svg xmlns="http://www.w3.org/2000/svg" width="14" height="10" role="presentation" class="vs__open-indicator">
																<path d="M9.211364 7.59931l4.48338-4.867229c.407008-.441854.407008-1.158247 0-1.60046l-.73712-.80023c-.407008-.441854-1.066904-.441854-1.474243 0L7 5.198617 2.51662.33139c-.407008-.441853-1.066904-.441853-1.474243 0l-.737121.80023c-.407008.441854-.407008 1.158248 0 1.600461l4.48338 4.867228L7 10l2.211364-2.40069z"></path>
															</svg> 
															<div class="vs__spinner" style="display: none;">Loading...</div>
														</div>
													</div> 
													<ul id="vs20__listbox" role="listbox" style="display: none; visibility: hidden;"></ul> 
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
												<input data-v-578d3222="" data-v-5290ad82="" id="charge_money" type="text" placeholder="입금하실 금액을 입력해주세요" inputmode="numeric" class="input deposit-money">
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
												<button data-v-5290ad82="" class="deposit-button button">입금 계좌요청</button> 
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
  // alert(money)
  // var obj  =   $("#money");
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
    var dehistorycontent= `<div class="container row" style="flex-direction: row; max-width: 450px;"><button class="close-button button" style="background: rgb(44, 48, 58);"><i data-v-e56d064c="" class="fa-solid fa-times" style="color: rgb(255, 255, 255);"></i></button>  <div data-v-40c960e6="" class="container column"><div data-v-40c960e6="" class="dialog-title row" style="flex-direction: row;"><span data-v-40c960e6="" class="text-level-7 text"><img data-v-40c960e6="" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IS0tIFVwbG9hZGVkIHRvOiBTVkcgUmVwbywgd3d3LnN2Z3JlcG8uY29tLCBHZW5lcmF0b3I6IFNWRyBSZXBvIE1peGVyIFRvb2xzIC0tPgo8c3ZnIHdpZHRoPSI2MHB4IiBoZWlnaHQ9IjYwcHgiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTYuNzMgMTkuN0M3LjU1IDE4LjgyIDguOCAxOC44OSA5LjUyIDE5Ljg1TDEwLjUzIDIxLjJDMTEuMzQgMjIuMjcgMTIuNjUgMjIuMjcgMTMuNDYgMjEuMkwxNC40NyAxOS44NUMxNS4xOSAxOC44OSAxNi40NCAxOC44MiAxNy4yNiAxOS43QzE5LjA0IDIxLjYgMjAuNDkgMjAuOTcgMjAuNDkgMTguMzFWNy4wNEMyMC41IDMuMDEgMTkuNTYgMiAxNS43OCAySDguMjJDNC40NCAyIDMuNSAzLjAxIDMuNSA3LjA0VjE4LjNDMy41IDIwLjk3IDQuOTYgMjEuNTkgNi43MyAxOS43WiIgc3Ryb2tlPSIjZmZmZmZmIiBzdHJva2Utd2lkdGg9IjEuNSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+Cjwvc3ZnPg==" class="margin-right-5" style="width: 20px; height: 20px;">입금내역</span></div> <div data-v-40c960e6="" class="button-wrap margin-bottom-10 row" style="flex-direction: row;"><div data-v-40c960e6="" class="spacer"></div> <button data-v-40c960e6="" class="button"><span data-v-40c960e6="" class="text">선택내역삭제</span></button></div> <div data-v-40c960e6="" class="fill-height"><div data-v-40c960e6="" class="deposit-log-list scrollable-auto margin-bottom-10 column"><div data-v-40c960e6="" class="no-data row" style="flex-direction: row;"><span data-v-40c960e6="" class="text">데이터가 없습니다.</span></div></div></div> <div data-v-40c960e6="" class="margin-bottom-10 row" style="flex-direction: row;"><div data-v-30f53f18="" data-v-40c960e6="" class="pagination row" style="flex-direction: row; align-items: center;"><div data-v-30f53f18="" class="row" style="flex-direction: row;"><div data-v-30f53f18="" dir="auto" class="v-select vs--single vs--unsearchable" style="margin-right: 5px;"> <div id="vs3__combobox" role="combobox" aria-expanded="false" aria-owns="vs3__listbox" aria-label="Search for option" class="vs__dropdown-toggle"><div class="vs__selected-options"><span class="vs__selected">
            15
           <!----></span> <input readonly="readonly" aria-autocomplete="list" aria-labelledby="vs3__combobox" aria-controls="vs3__listbox" type="search" autocomplete="off" class="vs__search"></div> <div class="vs__actions"><button type="button" title="Clear Selected" aria-label="Clear Selected" class="vs__clear" style="display: none;"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"><path d="M6.895455 5l2.842897-2.842898c.348864-.348863.348864-.914488 0-1.263636L9.106534.261648c-.348864-.348864-.914489-.348864-1.263636 0L5 3.104545 2.157102.261648c-.348863-.348864-.914488-.348864-1.263636 0L.261648.893466c-.348864.348864-.348864.914489 0 1.263636L3.104545 5 .261648 7.842898c-.348864.348863-.348864.914488 0 1.263636l.631818.631818c.348864.348864.914773.348864 1.263636 0L5 6.895455l2.842898 2.842897c.348863.348864.914772.348864 1.263636 0l.631818-.631818c.348864-.348864.348864-.914489 0-1.263636L6.895455 5z"></path></svg></button> <svg xmlns="http://www.w3.org/2000/svg" width="14" height="10" role="presentation" class="vs__open-indicator"><path d="M9.211364 7.59931l4.48338-4.867229c.407008-.441854.407008-1.158247 0-1.60046l-.73712-.80023c-.407008-.441854-1.066904-.441854-1.474243 0L7 5.198617 2.51662.33139c-.407008-.441853-1.066904-.441853-1.474243 0l-.737121.80023c-.407008.441854-.407008 1.158248 0 1.600461l4.48338 4.867228L7 10l2.211364-2.40069z"></path></svg> <div class="vs__spinner" style="display: none;">Loading...</div></div></div> <ul id="vs3__listbox" role="listbox" style="display: none; visibility: hidden;"></ul> </div></div> <div data-v-30f53f18="" class="row" style="flex-direction: row; align-items: center;"><span data-v-30f53f18="" class="text" style="opacity: 0.6;">
      개 씩 표시
    </span></div> <div data-v-30f53f18="" class="spacer"></div> <div data-v-30f53f18="" class="row" style="flex-direction: row;"><div data-v-30f53f18="" dir="auto" class="v-select vs--single vs--unsearchable" style="margin-right: 5px;"> <div id="vs4__combobox" role="combobox" aria-expanded="false" aria-owns="vs4__listbox" aria-label="Search for option" class="vs__dropdown-toggle"><div class="vs__selected-options"><span class="vs__selected">
            1
           <!----></span> <input readonly="readonly" aria-autocomplete="list" aria-labelledby="vs4__combobox" aria-controls="vs4__listbox" type="search" autocomplete="off" class="vs__search"></div> <div class="vs__actions"><button type="button" title="Clear Selected" aria-label="Clear Selected" class="vs__clear" style="display: none;"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"><path d="M6.895455 5l2.842897-2.842898c.348864-.348863.348864-.914488 0-1.263636L9.106534.261648c-.348864-.348864-.914489-.348864-1.263636 0L5 3.104545 2.157102.261648c-.348863-.348864-.914488-.348864-1.263636 0L.261648.893466c-.348864.348864-.348864.914489 0 1.263636L3.104545 5 .261648 7.842898c-.348864.348863-.348864.914488 0 1.263636l.631818.631818c.348864.348864.914773.348864 1.263636 0L5 6.895455l2.842898 2.842897c.348863.348864.914772.348864 1.263636 0l.631818-.631818c.348864-.348864.348864-.914489 0-1.263636L6.895455 5z"></path></svg></button> <svg xmlns="http://www.w3.org/2000/svg" width="14" height="10" role="presentation" class="vs__open-indicator"><path d="M9.211364 7.59931l4.48338-4.867229c.407008-.441854.407008-1.158247 0-1.60046l-.73712-.80023c-.407008-.441854-1.066904-.441854-1.474243 0L7 5.198617 2.51662.33139c-.407008-.441853-1.066904-.441853-1.474243 0l-.737121.80023c-.407008.441854-.407008 1.158248 0 1.600461l4.48338 4.867228L7 10l2.211364-2.40069z"></path></svg> <div class="vs__spinner" style="display: none;">Loading...</div></div></div> <ul id="vs4__listbox" role="listbox" style="display: none; visibility: hidden;"></ul> </div></div> <div data-v-30f53f18="" class="row" style="flex-direction: row;"><button data-v-30f53f18="" class="button icon" disabled="disabled" style="background: rgb(34, 42, 51);"><i data-v-e56d064c="" data-v-30f53f18="" class="fa-solid fa-chevron-left"></i></button></div> <div data-v-30f53f18="" class="row" style="flex-direction: row;"><button data-v-30f53f18="" class="button icon" disabled="disabled" style="background: rgb(34, 42, 51);"><i data-v-e56d064c="" data-v-30f53f18="" class="fa-solid fa-chevron-right"></i></button></div></div></div></div></div>`;
    div.innerHTML = dehistorycontent;
  }