
<div class="joinModal modal fade" role="dialog">
    <div class="login_modal evolution_modal modal-dialog">
        <div class="modal-head">
  			<span class="title">
  				<p>회원가입&nbsp;<span style="color: #e81010; font-size: 18px; font-weight: bolder;">[개인정보 불일치 시 가입불가]</span></p>
  				<p class="sub">성공적인 베팅을 위한 파트너 IRIS!</p>
  			</span>
            <a href="#" class="close-btn" data-dismiss="modal"><i class="fa fa-times"></i></a>
        </div>
        <div class="modal_body">
            <form id="signUpForm" action="/signUp" method="get" data-parsley-validate="" novalidate="">
                <input id="certified_number" name="certified_number" type="hidden" value="">
                <input type="hidden" name="_token" value="ca98287489bd41bc982ccae863076c9a0e4ccbd665ce69295f089113cfa736dc">
                <div class="form-group">
                    <label>아이디(6자이상 영문, 숫자만 가능)</label>
                    <input class="form-control " id="mb_login_id_signup" data-parsley-trigger="change" data-parsley-pattern="/^[a-z]+[a-z0-9]{5,12}$/g" data-parsley-pattern-message="유효하지 않은 값입니다." maxlength="12" data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." data-parsley-minlength="6" data-parsley-minlength-message="6 자 이상 입력하세요." data-parsley-maxlength="12" data-parsley-maxlength-message="12 자 이하로 입력하세요."  data-parsley-remote-message="이미 사용 중입니다." data-parsley-remote-validator="reverse" autocomplete="off" name="mb_login_id" type="text" value="">
                </div>
                <div class="form-group">
                    <label>이름(2자이상 한글, 영문만 가능(예금주와 동일))</label>
                    <input class="form-control " id="mb_player_name" data-parsley-trigger="input change" data-parsley-pattern="/^[ㄱ-ㅎ|가-힣|a-z|A-Z|]+$/" data-parsley-pattern-message="유효하지 않은 값입니다." data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." data-parsley-minlength="2" data-parsley-minlength-message="2 자 이상 입력하세요." data-parsley-maxlength="6" data-parsley-maxlength-message="6 자 이하로 입력하세요." autocomplete="off" name="mb_name1" type="text" value="">
                </div>
                <div class="form-group">
                    <label>닉네임(2자이상 한글, 영문만 가능)</label>
                    <input class="form-control " id="mb_player_nickname" data-parsley-trigger="input change" data-parsley-pattern="/^[ㄱ-ㅎ|가-힣|a-z|A-Z|]+$/" data-parsley-pattern-message="유효하지 않은 값입니다." data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." data-parsley-minlength="2" data-parsley-minlength-message="2 자 이상 입력하세요." data-parsley-maxlength="8" data-parsley-maxlength-message="8 자 이하로 입력하세요." autocomplete="off" name="mb_nickname" type="text" value="">
                </div>
                <!--<div class="form-group">
                    <label>생년월일/통신사(예:881212KT)</label>
                    <input class="form-control " data-parsley-trigger="input change" data-parsley-pattern="/^([0-9][0-9]|20\d{2})(0[0-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])+([a-z|A-Z]){2,3}$/" data-parsley-pattern-message="유효하지 않은 값입니다." data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." data-parsley-minlength="6" data-parsley-minlength-message="6 자 이상 입력하세요." data-parsley-maxlength="10" data-parsley-maxlength-message="9 자 이하로 입력하세요." autocomplete="off" name="mb_nick" type="text" value="">
                </div>-->
                <div class="form-group">
                    <label>비밀번호(8자이상)</label>
                    <input class="form-control " id="password2" maxlength="12" data-parsley-trigger="change" data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." data-parsley-maxlength="12" data-parsley-maxlength-message="12 자 이하로 입력하세요." data-parsley-minlength="6" data-parsley-minlength-message="6 자 이상 입력하세요." autocomplete="off" name="password" type="password" value="">
                </div>
                <div class="form-group">
                    <label>비밀번호확인</label>
                    <input class="form-control " data-parsley-equalto="#password2" data-parsley-equalto-message="비밀번호가 일치하지 않습니다." maxlength="12" data-parsley-trigger="change" data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." data-parsley-minlength="6" data-parsley-minlength-message="6 자 이상 입력하세요." autocomplete="off" name="password_confirm" type="password" value="">
                </div>
                <div class="form-group w-btn">
                    <label>전화번호(숫자만 입력)</label>
                    <input class="form-control" id="mb_tel" data-parsley-type="digits" data-parsley-type-message="이 값은 숫자만 입력 가능합니다." data-parsley-pattern="/^[0-9]*$/" data-parsley-pattern-message="유효하지 않은 값입니다." maxlength="15" data-parsley-trigger="change" data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." data-parsley-maxlength="15" data-parsley-maxlength-message="15 자 이하로 입력하세요." data-parsley-minlength="9" data-parsley-minlength-message="9 자 이상 입력하세요."  data-parsley-remote-message="이미 등록된 전화번호 입니다." data-parsley-remote-validator="reverse" autocomplete="off" name="mb_tel" type="text" value="">
                    <!--<button type="button" onclick="requestCertifyNumber()">인증요청</button>-->
                </div>
                <!--<div class="form-group w-btn">
                    <label>인증번호</label>
                    <input class="form-control" data-parsley-type="digits" data-parsley-type-message="이 값은 숫자만 입력 가능합니다." data-parsley-pattern="/^[0-9]*$/" data-parsley-pattern-message="유효하지 않은 값입니다." data-parsley-trigger="change input focusin" data-parsley-required="true" data-parsley-equalto="#certified_number" data-parsley-equalto-message="버튼을 눌러 인증번호를 확인하세요." data-parsley-required-message="필수입력 항목입니다." autocomplete="off" name="certify_number" type="text" value="">
                    <button type="button" onclick="requestCertifyCheck()">인증확인</button>
                </div>-->
                <div class="form-group">
                    <select id="mb_bank_name" class="form-control " data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." name="mb_bank_name"><option value="" selected="selected">선택하세요.</option><option value="KB국민은행">KB국민은행</option><option value="신한은행">신한은행</option><option value="우리은행">우리은행</option><option value="KEB하나은행">KEB하나은행</option><option value="카카오뱅크">카카오뱅크</option><option value="카카오증권">카카오증권</option><option value="KDB산업은행">KDB산업은행</option><option value="IBK기업은행">IBK기업은행</option><option value="NH농협은행">NH농협은행</option><option value="수협은행">수협은행</option><option value="대구은행">대구은행</option><option value="BNK부산은행">BNK부산은행</option><option value="BNK경남은행">BNK경남은행</option><option value="광주은행">광주은행</option><option value="전북은행">전북은행</option><option value="제주은행">제주은행</option><option value="농·축협">농·축협</option><option value="농협">농협</option><option value="축협">축협</option><option value="새마을금고">새마을금고</option><option value="우체국">우체국</option><option value="신용협동조합">신용협동조합</option><option value="산림조합">산림조합</option><option value="HSBC은행">HSBC은행</option><option value="한국씨티은행">한국씨티은행</option><option value="한국스탠다드차타드은행">한국스탠다드차타드은행</option><option value="신한금융투자">신한금융투자</option><option value="하나금융투자">하나금융투자</option><option value="DB금융투자">DB금융투자</option><option value="유안타증권">유안타증권</option><option value="한국투자증권">한국투자증권</option><option value="상호저축은행중앙회">상호저축은행중앙회</option><option value="미래애셋">미래에셋</option><option value="삼성증권">삼성증권</option><option value="SC제일은행">SC제일은행</option><option value="키움증권">키움증권</option></select>
                </div>
                <div class="form-group">
                    <label>계좌번호(숫자만 입력)</label>
                    <input id="mb_bank_account" class="form-control " data-parsley-type="digits" data-parsley-type-message="이 값은 숫자만 입력 가능합니다." data-parsley-pattern="/^[0-9]*$/" data-parsley-pattern-message="유효하지 않은 값입니다." maxlength="30" data-parsley-trigger="change" data-parsley-maxlength="30" data-parsley-maxlength-message="30 자 이하로 입력하세요." data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." autocomplete="off" name="mb_bank_account" type="text" value="">
                </div>
                <div class="form-group">
                    <label>예금주(2자이상 한글, 영문만 가능)</label>
                    <input id="mb_account_name" class="form-control" data-parsley-trigger="change" data-parsley-pattern="/^[ㄱ-ㅎ|가-힣|a-z|A-Z|]+$/" data-parsley-pattern-message="유효하지 않은 값입니다." maxlength="12" data-parsley-minlength="2" data-parsley-minlength-message="3 자 이상 입력하세요." data-parsley-maxlength="12" data-parsley-maxlength-message="12 자 이하로 입력하세요." data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." autocomplete="off" name="mb_account_name" type="text" value="">
                </div>
                <div class="form-group" >
                    <label>추천 코드</label>
                    <input id="mb_recommand_code" class="form-control" data-parsley-trigger="change"  data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." autocomplete="off" name="mb_recommand_code" type="text" value="">
                </div>
                <div class="modal_btn_grp">
                    <button type="submit">회원가입</button>
                    <button type="reset" data-dismiss="modal">취소하기</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    //spinner action
    var sf = $("#signUpForm");
    sf.submit(function () {
        $(this).parsley().validate();
        if ($(this).parsley().isValid()) {
            $('.wrapper_loading').removeClass('hidden');
        }
    });
</script>

