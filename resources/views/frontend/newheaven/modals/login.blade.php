<div id="login-popup" class="ngdialog ngdialog-theme-default ngdialog-login ng-scope ng-hide" role="alertdialog">

    <div class="ngdialog-overlay"></div>
    <div class="ngdialog-content" role="document">      
      @if( $detect->isMobile() || $detect->isTablet() ) 
      <div class="ngdialog-login-page ng-scope" ng-controller="LoginController">
          <div class="ngdialog__header">
            <div class="ngdialog__header__title">
              <h1 translate="" id="ngdialog4-aria-labelledby" class="ng-scope">로그인</h1>
            </div>
            <div class="ngdialog-close-container">
            <a class="ngdialog-close-btn" href="" ng-click="closeThisDialog(0)"><i class="fa fa-times"></i></a>
          </div>
          </div>
          <div class="ngdialog__logo text-center">
            <img src="/frontend/{{$logo}}/LOGO.png?0.1">
          </div>
          <div class="middle__text">
            <h5 class="ngdialog__title text-center"><strong translate="" class="ng-scope">로그인 후 이용 가능합니다.</strong></h5>
          </div>
          <div class="ngdialog__content">
            <form ng-submit="processForm()" class="ng-pristine ng-valid form-login">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input type="text" class="popup-form__element form-control ng-pristine ng-valid ng-touched" ng-model="loginForm.nickname" placeholder="아이디" id="sID">
              </div>
              <div class="input-group second-input">
                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                <input type="password" class="popup-form__element form-control ng-pristine ng-untouched ng-valid" autocomplete="new-password" ng-model="loginForm.password" placeholder="비밀번호" id="sPASS">
              </div>
              <button class="btn btn-block btn-primary ng-scope sbmt-login" type="button" ng-disabled="isProcessing" translate="">로그인</button>
              <!-- <button class="btn btn-block btn-primary ng-scope sbmt-login" type="submit" ng-disabled="isProcessing" translate="">로그인</button> -->
            </form>
            <div class=""></div>
            <p class="text-center" onclick="navClick(&#39;register-popup&#39;);">아직 계정이 없으신가요? <span style="color:#cab593;">신규 계정 생성</span>
            <div class="clearfix"></div>
          </div>
        </div>
        
      </div>
      @else
      <div class="ngdialog-login-page ng-scope" ng-controller="LoginController">
        <div class="ngdialog-login__header logo text-center">
          <!-- <img src="/frontend/{{$logo??'boss'}}/LOGO.png?0.1" width="260px" > -->
          <img src="/frontend/{{$logo}}/LOGO.png?0.1">
        </div>
        <div class="ngdialog-login__content">
          <p class="text-center login-title ng-scope" translate="">로그인 후 이용 가능합니다.</p>
          <form ng-submit="processForm()" class="ng-pristine ng-valid form-login">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-user"></i></span>
              <input type="text" class="popup-form__element form-control ng-pristine ng-valid ng-touched" ng-model="loginForm.nickname" placeholder="아이디" id="sID">
            </div>
            <div class="input-group second-input">
              <span class="input-group-addon"><i class="fa fa-lock"></i></span>
              <input type="password" class="popup-form__element form-control ng-pristine ng-untouched ng-valid" autocomplete="new-password" ng-model="loginForm.password" placeholder="비밀번호" id="sPASS">
            </div>
            <button class="btn btn-block btn-primary ng-scope sbmt-login" type="button" ng-disabled="isProcessing" translate="">로그인</button>
            <!-- <button class="btn btn-block btn-primary ng-scope sbmt-login" type="submit" ng-disabled="isProcessing" translate="">로그인</button> -->
          </form>
          <div class=""></div>
          <p class="text-center text-white" onclick="navClick(&#39;register-popup&#39;);">아직 계정이 없으신가요? <span style="color:#cab593;">신규 계정 생성</span>
          <div class="clearfix"></div>
        </div>
          
          <div class=""></div>
          
        <div class="clearfix"></div>
      </div>
      <div class="ngdialog-close"></div>
    </div>
      @endif 
</div>
<script>
var sID = '';
var sPASS = '';
var ajaxStart = false;
$(".show-pw").on('click', function() {
  let ac = $(this).hasClass('fa-eye-slash');
  if (ac) {
    $(this).removeClass('fa-eye-slash');
    $(this).addClass('fa-eye');
    $(this).siblings('input').attr('type', 'password');
  } else {
    $(this).removeClass('fa-eye');
    $(this).addClass('fa-eye-slash');
    $(this).siblings('input').attr('type', 'text');
  }
});
$(document).ready(function() {
  let uid = localStorage.getItem('sID');
  let upass = localStorage.getItem('sPASS');
  if ((uid && uid != '') && (upass && upass != '')) {
    $("#sID").val(uid);
    $("#sPASS").val(upass);
    $(".auto_login").prop('checked', true);
  }
  $(".remember-login").on('click', function() {
    $(".auto_login").click();
  });
});
var ip = "";
var provider = "";
var address = "";

$('#sPASS, #sID').keypress(function(e) {
  $(".sbmt-login").removeAttr('style');
  if (e.which == 13) {
    $(".sbmt-login").click();
  }
});
$(".sbmt-login").click(function() {
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
});
$("#sID").on('input', function() {
  $(this).removeClass('error');
  $(".error-text").text("");
});
$("#sPASS").on('input', function() {
  $(this).removeClass('error');
  $(".error-text").text("");
});
$(".sbmt-login2").click(function() {

  var site_url = "yj-u.com";
  $(this).addClass('is-loading disabled');
  sID = $("#sID2").val();
  sPASS = $("#sPASS2").val();
  var csrf_token = $("#token").val();
  var location = localStorage.getItem('location');
  var provider = localStorage.getItem('provider');
  if (ajaxStart == true) {
    return false;
  } else {
    ajaxStart = true;
loginx();
  }
});
// })

function loginx() {

  if (ajaxStart == false) {
    return false;
  }
  var site_url = "yj-u.com";
  var location = localStorage.getItem('location');
  var provider = localStorage.getItem('provider');
  //var sID = $("#sID2").val();
  //var sPASS = $("#sPASS2").val();
  var isRemembered = $(".auto_login").prop('checked');

  if (sID == '' || sPASS == '') {
    // alert("로그인에 실패 하였습니다. 정확한 정보를 입력하여 주세요.");
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
          // alert("로그인에 실패하였습니다. 다시 시도해 주세요.");
          // $(".error-text").text("로그인에 실패하였습니다. 다시 시도해 주세요.");
          // $(".error-text").removeAttr('style');
  //swal("Oops!", result.message , "error");
  swal({
    title: "Oops!",
    text: result.msg,
    type: "error"
  }).then(function() {
    window.location.reload();
  });
          //window.location.reload();
        }
        ajaxStart = false;
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        alert("some error");
      }
    });
    // },1000);
  }
}
</script>