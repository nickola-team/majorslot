<div id="register-popup" class="ngdialog ngdialog-theme-default ngdialog-signup ng-scope ng-hide">
      <div class="ngdialog-overlay"></div>
      <div class="ngdialog-content" role="document">
        <div class="ngdialog-signup-page ng-scope" ng-controller="SignUpController">
          <div class="ngdialog-signup__content">
            <div class="logo">
              <img src="/frontend/{{$logo??'boss'}}/LOGO.png" width="20%">
            </div>
            <form name="signUp" novalidate="" ng-submit="processForm()" class="form-signup ng-pristine ng-invalid ng-invalid-required ng-valid-pattern ng-valid-minlength ng-valid-maxlength ng-valid-have-special-char ng-valid-special-char-c ng-valid-no-match" id="freg">
              <!--Member ID-->
			  <input type="hidden" name="EMODE" value="MEMADD">
			  <!-- <span class="e-msg"></span> -->
			  <fieldset>
                <div class="col-sm-3">
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
                    <button type="button" class="btn btn-sm btn-yellow ng-scope" id="btn-username">중복확인</button>
                  </span>
                  <span class="signup-inquiry ng-scope" data-toggle="tooltip" data-placement="top" title="아이디는 4-12자리 입니다" style="padding-left: 12px;">영문,숫자</span>
                </div>
              </fieldset>
              <!--Member Password-->
              <fieldset>
                <div class="col-sm-3">
                  <label>
                    <span translate="" class="ng-scope">비밀번호</span>
                    <span>*</span>
                  </label>
                </div>
                <div class="col-sm-5">
                  <input type="password" autocomplete="new-password" class="form-control ng-pristine ng-untouched ng-valid-have-special-char ng-valid-special-char-c ng-invalid ng-invalid-required ng-valid-minlength ng-valid-maxlength" name="password" id="reg-password">
                </div>
                <div class="col-sm-4">
                  <!-- ngIf: signUp.MemberPwd.$dirty -->
                  <span class="signup-inquiry ng-scope" data-toggle="tooltip" data-placement="top" title="패스워드는 6-16 자리 입니다" translate="">패스워드는 6-16 자리 입니다</span>
                </div>
              </fieldset>
              <!--Member Valid Password-->
              <fieldset>
                <div class="col-sm-3">
                  <label>
                    <span translate="" class="ng-scope">비밀번호 재입력</span>
                    <span>*</span>
                  </label>
                </div>
                <div class="col-sm-5">
                  <input type="password" autocomplete="new-password" class="form-control ng-pristine ng-untouched ng-isolate-scope ng-valid-have-special-char ng-valid-special-char-c ng-invalid ng-invalid-required ng-valid-minlength ng-valid-maxlength ng-valid-no-match" name="passwordcw" id="reg-cpassword">
                </div>
                <div class="col-sm-4">
                  <!-- ngIf: signUp.MemberValidPwd.$dirty -->
                </div>
              </fieldset>
              <!--Member Withdraw Password-->
              
              <!--Mobile Number-->
              <fieldset>
                <div class="col-sm-3">
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
			  <!--BankInfo Name-->

              <fieldset>
                <div class="form-group">
                  <div class="col-sm-3">
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
                  <div class="col-sm-3">
                    <label>
                      <span ng-bind="'Account Number' | translate" class="ng-binding">계좌번호</span>
                      <span>*</span>
                    </label>
                  </div>
                  <div class="col-sm-5">
                    <input type="text" class="form-control ng-pristine ng-untouched ng-invalid ng-invalid-required ng-valid-pattern" onkeypress="isInputNumber(event)" placeholder="계좌번호" name="account_no" id="reg-accountnum">
                  </div>
                  <div class="col-sm-4">
                    <!-- ngIf: signUp.AccountNumber.$dirty -->
                  </div>
                </div>
              </fieldset>
              <fieldset>
                <div class="col-sm-3">
                  <label>
                    <span translate="" class="ng-scope">예금주</span>
                    <span>*</span>
                  </label>
                </div>
                <div class="col-sm-5">
                  <input type="text" class="form-control ng-pristine ng-untouched ng-invalid ng-invalid-required ng-valid-minlength ng-valid-maxlength" name="recommender" id="reg-name">
                </div>
                <div class="col-sm-4">
                  <!-- ngIf: signUp.MemberName.$dirty -->
                  <!-- <span class="signup-inquiry ng-scope" data-toggle="tooltip" data-placement="top" title="이름은 한글 2-5 자리 입니다" translate="">이름은 한글 2-5 자리 입니다</span> -->
                </div>
              </fieldset>
              <!--Member Reference-->
			  <fieldset>
                <div class="col-sm-3">
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
              <button type="button" class="btn btn-block btn-signup ng-scope" id="reg-continue">회원가입</button>
            </form>
          </div>
        </div>
        <div class="ngdialog-close"></div>
      </div>
    </div>

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
              // setTimeout(function() {
              //    $(".success-text").text('');
              // }, 3500);
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
            // setTimeout(function() {
            //    $(".success-text").text('');
            // }, 3500);
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
          $(this).parent().parent().removeClass('has-success');
          $(this).parent().parent().addClass('has-error');
        } else {
          $(this).parent().parent().addClass('has-success');
          $(this).parent().parent().removeClass('has-error');
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
        if ($('#register-popup .has-success').length == 8) {
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
        if (err < 8) {
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
              // $("#reg-notice").text(result.message);
              // $("#reg-continue").removeClass('is-loading disabled');
            }
            $('input[name="token"]').val(result.token);
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) {
            // alert('zzzz')
            // $("#reg-notice").text("유효하지 않는 코드입니다.");
            swal('Oops!', "오류가 발생하였습니다", 'error');
            // $("#reg-continue").removeClass('is-loading disabled');
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
