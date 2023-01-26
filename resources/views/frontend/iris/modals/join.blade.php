
<div class="joinModal modal fade" role="dialog">
    <div class="login_modal evolution_modal modal-dialog">
        <div class="modal-head">
  			<span class="title">
  				<p>회원가입&nbsp;<span style="color: #e81010; font-size: 18px; font-weight: bolder;">[개인정보 불일치 시 가입불가]</span></p>
  				<p class="sub">성공적인 베팅을 위한 파트너 @yield('page-title')!</p>
  			</span>
            <a href="#" class="close-btn" data-dismiss="modal"><i class="fa fa-times"></i></a>
        </div>
        <div class="modal_body">
            <form id="signUpForm" action="/" method="get" data-parsley-validate="" novalidate="">
                <input id="certified_number" name="certified_number" type="hidden" value="">
                <input type="hidden" value="<?= csrf_token() ?>" name="_token" id="_token">
                <div class="form-group">
                    <label>아이디(2자이상 한글, 영문, 숫자만 가능)</label>
                    <input class="form-control " id="username" data-parsley-trigger="input change" data-parsley-pattern="/^[ㄱ-ㅎ|가-힣|a-z|A-Z|0-9|]+$/" data-parsley-pattern-message="유효하지 않은 값입니다." data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." data-parsley-minlength="2" data-parsley-minlength-message="2 자 이상 입력하세요." data-parsley-maxlength="12" data-parsley-maxlength-message="12 자 이하로 입력하세요." autocomplete="off" name="username" type="text" value="">
                </div>
                <div class="form-group">
                    <label>비밀번호(6자이상)</label>
                    <input class="form-control " id="password" maxlength="12" data-parsley-trigger="change" data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." data-parsley-maxlength="12" data-parsley-maxlength-message="12 자 이하로 입력하세요." data-parsley-minlength="6" data-parsley-minlength-message="6 자 이상 입력하세요." autocomplete="off" name="password" type="password" value="">
                </div>
                <div class="form-group">
                    <label>비밀번호확인</label>
                    <input class="form-control " data-parsley-equalto="#password" data-parsley-equalto-message="비밀번호가 일치하지 않습니다." maxlength="12" data-parsley-trigger="change" data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." data-parsley-minlength="6" data-parsley-minlength-message="6 자 이상 입력하세요." autocomplete="off" name="password_confirm" type="password" value="">
                </div>
                <div class="form-group w-btn">
                    <label>전화번호(숫자만 입력)</label>
                    <input class="form-control" id="tel1" data-parsley-type="digits" data-parsley-type-message="이 값은 숫자만 입력 가능합니다." data-parsley-pattern="/^[0-9]*$/" data-parsley-pattern-message="유효하지 않은 값입니다." maxlength="15" data-parsley-trigger="change" data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." data-parsley-maxlength="15" data-parsley-maxlength-message="15 자 이하로 입력하세요." data-parsley-minlength="9" data-parsley-minlength-message="9 자 이상 입력하세요."  data-parsley-remote-message="이미 등록된 전화번호 입니다." data-parsley-remote-validator="reverse" autocomplete="off" name="tel1" type="text" value="">
                </div>
                <div class="form-group">
                    <select id="bank_name" class="form-control " data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." name="bank_name">
                    <option value="" selected="selected">은행명을 선택하세요.</option>
                    <?php
                        $banks = \VanguardLTE\User::$values['banks'];
                        foreach ($banks as $b)
                        {
                            echo '<option value="'.$b.'">'.$b.'</option>';
                        }

                    ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>계좌번호(숫자만 입력)</label>
                    <input id="account_no" class="form-control " data-parsley-type="digits" data-parsley-type-message="이 값은 숫자만 입력 가능합니다." data-parsley-pattern="/^[0-9]*$/" data-parsley-pattern-message="유효하지 않은 값입니다." maxlength="30" data-parsley-trigger="change" data-parsley-maxlength="30" data-parsley-maxlength-message="30 자 이하로 입력하세요." data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." autocomplete="off" name="account_no" type="text" value="">
                </div>
                <div class="form-group">
                    <label>예금주(2자이상 한글, 영문만 가능)</label>
                    <input id="recommender" class="form-control" data-parsley-trigger="change" data-parsley-pattern="/^[ㄱ-ㅎ|가-힣|a-z|A-Z|]+$/" data-parsley-pattern-message="유효하지 않은 값입니다." maxlength="12" data-parsley-minlength="2" data-parsley-minlength-message="3 자 이상 입력하세요." data-parsley-maxlength="12" data-parsley-maxlength-message="12 자 이하로 입력하세요." data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." autocomplete="off" name="recommender" type="text" value="">
                </div>
                <div class="form-group" >
                    <label>추천 코드</label>
                    <input id="friend" class="form-control" data-parsley-trigger="change"  data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." autocomplete="off" name="friend" type="text" value="">
                </div>
                <div class="modal_btn_grp">
                    <button type="button" onclick="goJoin();">회원가입</button>
                    <button type="reset" data-dismiss="modal">취소하기</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>

    function goJoin( )
    {
        var sf = $("#signUpForm");
        sf.parsley().validate();
        if (sf.parsley().isValid()) {
            $('.wrapper_loading').removeClass('hidden');
            $.ajax({
                type: 'POST',
                url: "/api/join",
                data: {	username: $("#signUpForm #username").val(),
                        password: $("#signUpForm #password").val(),
                        tel1: $("#signUpForm #tel1").val(),
                        tel2: '',
                        tel3: '',
                        bank_name: $("#signUpForm #bank_name").val(),
                        recommender: $("#signUpForm #recommender").val(),
                        account_no: $("#signUpForm #account_no").val(),
                        friend: $("#signUpForm #friend").val()},
                cache: false,
                async: false,
                success: function (data) {
                    if (data.error) {
                        alert(data.msg);
                        $('.wrapper_loading').addClass('hidden');
                        return;
                    }
                    else{
                        alert(data.msg);
                        window.location.href = "/";
                    }

                },
                error: function (err, xhr) {
                    alert(err.responseText);
                    $('.wrapper_loading').addClass('hidden');
                }
            });
        }
        else
        {
            alert('정보를 정확히 입력하세요.');
        }
    }
</script>

