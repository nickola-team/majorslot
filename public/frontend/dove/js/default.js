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
      <button class="close-button button" style="background: rgb(44, 48, 58);" onclick="closeModal();">
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
function closeModal(){
    document.getElementById('main-modal').innerHTML = "";
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
      <button class="close-button button" style="background: rgb(44, 48, 58);" onclick="closeModal();">
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