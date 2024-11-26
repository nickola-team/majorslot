
@if( $detect->isMobile() || $detect->isTablet() ) 
  <div id="register-popup" class="ngdialog ngdialog-theme-default ngdialog-direct ng-scope ng-hide" role="alertdialog" aria-labelledby="ngdialog4-aria-labelledby" aria-describedby="ngdialog4-aria-describedby">
    <div class="ngdialog-content" role="document">
      <div class="ngdialog-signup" ng-controller="SignUpController">
        <div class="ngdialog__header">
          <div class="ngdialog__header__title">
            <h1 translate="" id="ngdialog5-aria-labelledby" class="ng-scope">회원가입</h1>
          </div>
          <div class="ngdialog-close-container">
            <a class="ngdialog-close-btn" href="" ng-click="closeThisDialog(0)"><i class="fa fa-times"></i></a>
          </div>
        </div>
        <div class="ngdialog__content ng-scope">
          <form name="signUp" novalidate="" ng-submit="processForm()" class="form-signup ng-pristine ng-invalid ng-invalid-required ng-valid-pattern ng-valid-minlength ng-valid-maxlength ng-valid-have-special-char ng-valid-special-char-c ng-valid-no-match" id="freg">              
			        <input type="hidden" name="EMODE" value="MEMADD">			          
			        <fieldset class="ngdialog__form-group">                
                <div class="col-xs-12 no-padding">                  
                  <div class="col-xs-7 no-padding" style="padding-right: 10px;">
                    <label class="ngdialog__form__label">
                      <span translate="" class="ng-scope">아이디</span>
                      <span>*</span>
                    </label>
                    <input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-pattern ng-valid-minlength ng-valid-maxlength ng-touched" name="username" id="reg-username" placeholder="아이디는 영문, 숫자만 가능합니다" oninput="this.value=this.value.replace(/[^A-Za-z0-9\s]/g,'');" lang="en">
                  </div>  
                  <div class="col-xs-5 clear-container no-padding">
                    <div class="col-xs-8 mobile_signup1">
                      <button type="button" class="btn btn-primary btn-block ng-scope" id="btn-username">중복확인</button>
                    </div>
                    <div class="col-xs-4 text-center mobile_signup2">
                      <span class="signup-inquiry ng-scope" data-toggle="tooltip" data-placement="top" title="아이디는 4-12자리 입니다">영문,숫자</span>
                    </div>  
                  </div>                  
                </div>      
              </fieldset>
              <!--Member Password-->
              <fieldset class="ngdialog__form-group">
                <label class="ngdialog__form__label">
                  <span translate="" class="ng-scope">비밀번호</span>
                  <span>*</span>
                </label>
                <input type="password" autocomplete="new-password" class="form-control ng-pristine ng-untouched ng-valid-have-special-char ng-valid-special-char-c ng-invalid ng-invalid-required ng-valid-minlength ng-valid-maxlength" name="password" id="reg-password">
                <div class="form-notice">
                  <p class="signup-inquiry ng-scope" data-toggle="tooltip" data-placement="top" title="패스워드는 6-16 자리 입니다" translate="">패스워드는 6-16 자리 입니다</p>
                </div>                
              </fieldset>

              <fieldset class="ngdialog__form-group">
                <label class="ngdialog__form__label">
                  <span translate="" class="ng-scope">비밀번호 재입력</span>
                  <span>*</span>
                </label>
                <input type="password" autocomplete="new-password" class="form-control ng-pristine ng-untouched ng-isolate-scope ng-valid-have-special-char ng-valid-special-char-c ng-invalid ng-invalid-required ng-valid-minlength ng-valid-maxlength ng-valid-no-match" name="passwordcw" id="reg-cpassword">                
              </fieldset>
              
              <fieldset class="ngdialog__form-group">
                <label class="ngdialog__form__label">
                  <span translate="" class="ng-scope">전화번호</span>
                  <span>*</span>
                </label>
                <input type="text" class="form-control ng-pristine ng-untouched ng-invalid ng-invalid-required" onkeypress="isInputNumber(event)" name="tel1" placeholder="'-' 없이 숫자만 입력" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" id="reg-phone">                
              </fieldset>
			        
              <fieldset class="ngdialog__form-group">
                <label class="ngdialog__form__label">
                  <span ng-bind="'Bank' | translate" class="ng-binding">은행명</span>
                  <span>*</span>
                </label>
                <p>
                  <select class="form-control ng-pristine ng-untouched ng-valid" name="bank_name" id="reg-bankname">
                  <?php
                    $banks = \VanguardLTE\User::$values['banks'];
                    foreach ($banks as $b)
                    {
                        echo '<option value="'.$b.'">'.$b.'</option>';
                    }
                    ?>
                  </select>
                </p>                
              </fieldset>

              <fieldset class="ngdialog__form-group">
                <label class="ngdialog__form__label">
                  <span ng-bind="'Account Number' | translate" class="ng-binding">계좌번호</span>
                  <span>*</span>
                </label>
                <input type="text" class="form-control ng-pristine ng-untouched ng-invalid ng-invalid-required ng-valid-pattern" onkeypress="isInputNumber(event)" placeholder="계좌번호" name="account_no" id="reg-accountnum">
              </fieldset>

              <fieldset class="ngdialog__form-group">
                <label class="ngdialog__form__label">
                  <span translate="" class="ng-scope">예금주</span>
                  <span>*</span>
                </label>
                <input type="text" class="form-control ng-pristine ng-untouched ng-invalid ng-invalid-required ng-valid-minlength ng-valid-maxlength" name="recommender" id="reg-name">                
              </fieldset>
              
			        <fieldset class="ngdialog__form-group">
                <label class="ngdialog__form__label">
                  <span translate="" class="ng-scope">추천인코드</span>
                  <span>*</span>
                </label>
                <input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-pattern ng-valid-minlength ng-valid-maxlength ng-touched" name="friend" id="reg-refercode" value="">
              </fieldset>

              <div class="form-footer">
                <button type="button" class="btn btn-primary ngdialog__btn" id="reg-continue">회원가입</button>
              </div>              
          </form>
        </div>
      </div>
    </div>
  </div>        
@else
<div id="register-popup" class="ngdialog ngdialog-theme-default ngdialog-signup ng-scope  ng-hide">
    <div class="ngdialog-overlay"></div>
    <div class="ngdialog-content" role="document" style="padding:0;">
        <div class="ngdialog-signup-page ng-scope" ng-controller="SignUpController">
          <div class="ngdialog-signup__header">
            <img src="/frontend/{{$logo}}/LOGO.png?0.1">
          </div>
          <div class="ngdialog-signup__content">
            <form name="signUp" novalidate="" ng-submit="processForm()" class="form-signup ng-pristine ng-invalid ng-invalid-required ng-valid-pattern ng-valid-minlength ng-valid-maxlength ng-valid-have-special-char ng-valid-special-char-c ng-valid-no-match" id="freg">
              
			        <input type="hidden" name="EMODE" value="MEMADD">
			  
			          <fieldset>
                  <div class="col-sm-3 text-right">
                    <label>
                      <span translate="" class="ng-scope">아이디</span>
                      <span>*</span>
                    </label>
                  </div>
                  <div class="col-sm-5">
                    <input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-pattern ng-valid-minlength ng-valid-maxlength ng-touched" name="username" id="reg-username" placeholder="아이디는 영문, 숫자만 가능합니다" oninput="this.value=this.value.replace(/[^A-Za-z0-9\s]/g,'');" lang="en">
                  </div>
                  <div class="col-sm-4">
                    <span style="padding-left: 15px">
                      <button type="button" class="btn btn-sync btn-sm btn-red sms ng-scope" id="btn-username">중복확인</button>
                    </span>
                    <span class="signup-inquiry ng-scope" data-toggle="tooltip" data-placement="top" title="아이디는 4-12자리 입니다" style="padding-left: 12px;">영문,숫자</span>
                  </div>
                </fieldset>
              
                <fieldset>
                  <div class="col-sm-3 text-right">
                    <label>
                      <span translate="" class="ng-scope">비밀번호</span>
                      <span>*</span>
                    </label>
                  </div>
                  <div class="col-sm-5">
                    <input type="password" autocomplete="new-password" class="form-control ng-pristine ng-untouched ng-valid-have-special-char ng-valid-special-char-c ng-invalid ng-invalid-required ng-valid-minlength ng-valid-maxlength" name="password" id="reg-password">
                  </div>
                  <div class="col-sm-4">                    
                    <span class="signup-inquiry ng-scope" data-toggle="tooltip" data-placement="top" title="패스워드는 6-16 자리 입니다" translate="">패스워드는 6-16 자리 입니다</span>
                  </div>
                </fieldset>
              
                <fieldset>
                  <div class="col-sm-3 text-right">
                    <label>
                      <span translate="" class="ng-scope">비밀번호 재입력</span>
                      <span>*</span>
                    </label>
                  </div>
                  <div class="col-sm-5">
                    <input type="password" autocomplete="new-password" class="form-control ng-pristine ng-untouched ng-isolate-scope ng-valid-have-special-char ng-valid-special-char-c ng-invalid ng-invalid-required ng-valid-minlength ng-valid-maxlength ng-valid-no-match" name="passwordcw" id="reg-cpassword">
                  </div>
                  <div class="col-sm-4">                  
                  </div>
                </fieldset>
              
                <fieldset>
                  <div class="col-sm-3 text-right">
                    <label>
                      <span translate="" class="ng-scope">전화번호</span>
                      <span>*</span>
                    </label>
                  </div>
                  <div class="col-sm-5">
                    <input type="text" class="form-control ng-pristine ng-untouched ng-invalid ng-invalid-required" onkeypress="isInputNumber(event)" name="tel1" placeholder="'-' 없이 숫자만 입력" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" id="reg-phone">

                  </div>
                  <div class="col-sm-4">
                    
                  </div>
                </fieldset>

                <fieldset>
                  <div class="form-group">
                    <div class="col-sm-3 text-right">
                      <label>
                        <span ng-bind="'Bank' | translate" class="ng-binding">은행명</span>
                        <span>*</span>
                      </label>
                    </div>
                    <div class="col-sm-5">
                      <p>
                        <select class="form-control ng-pristine ng-untouched ng-valid" name="bank_name" id="reg-bankname">
                        <?php
                          $banks = \VanguardLTE\User::$values['banks'];
                          foreach ($banks as $b)
                          {
                              echo '<option value="'.$b.'">'.$b.'</option>';
                          }
                          ?>
                      </select>
                      </p>
                    </div>
                  </div>
                </fieldset>

                <fieldset>
                  <div class="form-group">
                    <div class="col-sm-3 text-right">
                      <label>
                        <span ng-bind="'Account Number' | translate" class="ng-binding">계좌번호</span>
                        <span>*</span>
                      </label>
                    </div>
                    <div class="col-sm-5">
                      <input type="text" class="form-control ng-pristine ng-untouched ng-invalid ng-invalid-required ng-valid-pattern" onkeypress="isInputNumber(event)" placeholder="계좌번호" name="account_no" id="reg-accountnum">
                    </div>
                    <div class="col-sm-4">
                    </div>
                  </div>
                </fieldset>
                <fieldset>
                  <div class="col-sm-3 text-right">
                    <label>
                      <span translate="" class="ng-scope">예금주</span>
                      <span>*</span>
                    </label>
                  </div>
                  <div class="col-sm-5">
                    <input type="text" class="form-control ng-pristine ng-untouched ng-invalid ng-invalid-required ng-valid-minlength ng-valid-maxlength" name="recommender" id="reg-name">
                  </div>
                  <div class="col-sm-4">
                  </div>
                </fieldset>
              
			          <fieldset>
                  <div class="col-sm-3 text-right">
                    <label>
                      <span translate="" class="ng-scope">추천인코드</span>
                      <span>*</span>
                    </label>
                  </div>
                  <div class="col-sm-5">
                    <input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-pattern ng-valid-minlength ng-valid-maxlength ng-touched" name="friend" id="reg-refercode" value="">
                  </div>
                  <div class="clearfix"></div>
                </fieldset>
                <button type="button" class="btn btn-primary btn-block ng-scope" id="reg-continue">회원가입</button>
            </form>
          </div>
        </div>
        <div class="ngdialog-close"></div>
    </div>
</div>
@endif

    <script type="text/javascript">
      var isSend = 0;
      $(document).on('input', '#reg-username', function() {
        let vl = $(this).val();
        let patt = /[a-zA-Z0-9]/g;
        if (vl == '') {
          $(this).parent().parent().removeClass('has-success');
          $(this).parent().parent().addClass('has-error');
        } else if (vl.length < 4) {
          $(this).parent().parent().removeClass('has-success');
          $(this).parent().parent().addClass('has-error');
        } else if (!patt.test(vl)) {
          $(this).parent().parent().addClass('has-success');
          $(this).parent().parent().removeClass('has-error');
        } else {
          $(this).parent().parent().addClass('has-success');
          $(this).parent().parent().removeClass('has-error');
        }
      });

      $("#btn-username").on('click', function() {
        if (isSend == 1) return false;
        let username = $("#reg-username").val();
        if (username == '') {
          $("#reg-username").parent().parent().addClass('has-error');
          $("#reg-username").parent().parent().removeClass('has-success');
          return false;
        }
        $(".e-msg").html('');
        isSend = 1;
        $.ajax({
          url: '/api/checkid',
          type: 'POST',
          data: {
            "id": username
          },
          dataType: 'json',
          success: function(result) {
            isSend = 0;
            if (result.ok === 1) {
              $(".s-msg").html('확인');
			        swal('확인');
              $("#reg-username").parent().parent().removeClass('has-error');
              $("#reg-username").parent().parent().addClass('has-success');              
            } else {
              $(".e-msg").text('중복된 아이디입니다');
			        swal("Oops!", '중복된 아이디입니다', "error");
              $("#reg-username").parent().parent().addClass('has-error');
            }
            $('input[name="token"]').val(result.token)
          },
          error: function(err) {
            $(".e-msg").text('검증 실패.');
			      swal("Oops!", '검증 실패.', "error");
            isSend = 0;
          }
        });
      });

      $(document).on('input', '#reg-password', function() {
        let vl = $(this).val();
        if (vl == '' || vl.length < 6) {
          $(this).parent().parent().removeClass('has-success');
          $(this).parent().parent().addClass('has-error');
        } else if (vl.length < 6) {
          $(this).parent().parent().removeClass('has-success');
          $(this).parent().parent().addClass('has-error');
        } else {
          $(this).parent().parent().addClass('has-success');
          $(this).parent().parent().removeClass('has-error');
        }
      });
      $(document).on('input', '#reg-cpassword', function() {
        let vl = $(this).val();
        let np = $("#reg-cpassword").val();
        $(".reg-cpassword").text('');
        if (vl == '') {
          $(this).parent().parent().removeClass('has-success');
          $(this).parent().parent().addClass('has-error');
        } else if (np != vl) {
          $(this).parent().parent().removeClass('has-success');
          $(this).parent().parent().addClass('has-error');
          $(".reg-cpassword").text('Password not match');
        } else {
          $(this).parent().parent().addClass('has-success');
          $(this).parent().parent().removeClass('has-error');
        }
      });
      $(document).on('input', '#reg-wpassword', function() {
        let vl = $(this).val();
        if (vl == '') {
          $(this).parent().parent().removeClass('has-success');
          $(this).parent().parent().addClass('has-error');
        } else if (vl.length < 4 || vl.length > 16) {
          $(this).parent().parent().removeClass('has-success');
          $(this).parent().parent().addClass('has-error');
        } else {
          $(this).parent().parent().addClass('has-success');
          $(this).parent().parent().removeClass('has-error');
        }
      });

      $(document).on('input', '#reg-name', function() {
        let vl = $(this).val();
        if (vl == '') {
          $(this).parent().parent().removeClass('has-success');
          $(this).parent().parent().addClass('has-error');
        } else {
          $(this).parent().parent().addClass('has-success');
          $(this).parent().parent().removeClass('has-error');
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
          $(this).parent().parent().parent().removeClass('has-success');
          $(this).parent().parent().parent().addClass('has-error');
        } else {
          $(this).parent().parent().parent().addClass('has-success');
          $(this).parent().parent().parent().removeClass('has-error');
        }
      });

      $(document).on('change', '#reg-bankname', function() {
        let vl = $(this).val();
        if (vl == '') {
          $(this).parent().parent().parent().parent().removeClass('has-success');
          $(this).parent().parent().parent().parent().addClass('has-error');
        } else {
          $(this).parent().parent().parent().parent().addClass('has-success');
          $(this).parent().parent().parent().parent().removeClass('has-error');
        }
      })
      $(document).on('input', '#register-popup input', function() {
        if ($('#register-popup .has-success').length >= 7) {
          $('#reg-continue').removeAttr('disabled');
        } else {
          $('#reg-continue').attr('disabled', 'disabled');
        }
      });

      $(document).on('input', '#reg-refercode', function() {
        let vl = $(this).val();
        let patt = /[a-zA-Z0-9]/g;
        if (vl == '') {
          $(this).parent().parent().removeClass('has-success');
          $(this).parent().parent().addClass('has-error');
        } else if (vl.length < 4) {
          $(this).parent().parent().removeClass('has-success');
          $(this).parent().parent().addClass('has-error');
        } else if (!patt.test(vl)) {
          $(this).parent().parent().addClass('has-success');
          $(this).parent().parent().removeClass('has-error');
        } else {
          $(this).parent().parent().addClass('has-success');
          $(this).parent().parent().removeClass('has-error');
        }
      });

      
	  
	  $("#reg-continue").click(function() {

        var formData = $("#freg").serializeArray();
        var hasError = false;
        $('#register-popup input').trigger('input');
        $('#register-popup select').trigger('change');
        let err = $('#register-popup .has-success').length;
        if (err < 7) {
          return false;
        }
        $(this).addClass('is-loading disabled');
        $.ajax({
          url: '/api/join',
          type: 'POST',
          data: formData,
          dataType: "json",
          async: false,
          success: function(result) {
            if (result.error == false) {
              $("#freg input").val('');
              swal(result.msg).then((value) => {
                $('#register-popup').addClass('ng-hide');
                $("fieldset").removeClass('has-success');
                $("fieldset").removeClass('has-error');
              });
            } else {
              swal('Oops!', result.msg, 'error');
            }
            $('input[name="token"]').val(result.token);
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) {
            swal('Oops!', "오류가 발생하였습니다", 'error');
          }
        });
      });

      function isInputNumber(evt) {
        var ch = String.fromCharCode(evt.which);
        if (!(/[0-9]/.test(ch))) {
          evt.preventDefault();
        }
      }
    </script>
