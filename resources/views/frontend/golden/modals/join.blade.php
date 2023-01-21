<!-- ------------------------------------- Join ------------------------------------ -->
<div class="joinModal modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" data-dismiss="modal" class="modal-close-btn w-ba"></button>
				<div class="modal-banner">
					<a href="javascript:void(0);" class="modal-logo">
						<img class="w-100" src="/frontend/golden/images/logo/aria.png">
					</a>
					<div class="modal-title">
						<div class="modal-icon dflex-ac-jc">
							<i class="fa-solid fa-handshake-angle"></i>
						</div>
						<div class="title-panel">
							<span class="title">회원가입</span>
							<span class="sub w-mob-title">언제나 노력하는 GOLDENBULL CASINO 되겠습니다!</span>
						</div>
					</div>
				</div>
				<div class="modal-head-panel dflex-ac-jc">
					<span class="modal-title-mob">언제나 노력하는 GOLDENBULL CASINO 되겠습니다!</span>
				</div>
			</div>
			<div class="modal-body pt-md-5 pt-3">
				<div class="container-fluid px-md-3 px-0">
					<form id="signUpForm" name="frm_join" onsubmit="onJoin(); return false;" data-parsley-validate>
						<div class="row">
							<div class="col-12 col-md-6">
								<div class="form-container">
									<div class="form-group">
										<div class="labels w-ba">
											<span>아이디 *</span>
										</div>
										<div class="infos">
											<div class="input-container">
												<input class="form-control" data-parsley-trigger="focusout" data-parsley-pattern="/^\w{6,12}$/g" data-parsley-pattern-message="유효하지 않은 값입니다." maxlength="12" data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." data-parsley-minlength="6" data-parsley-minlength-message="6자 이상 입력하세요." data-parsley-maxlength="12" data-parsley-maxlength-message="12 자 이하로 입력하세요." placeholder="6자이상 영문, 숫자만 가능" autocomplete="off" name="username" type="text" value="" onkeyup="chkCharCode(event)">
											</div>
											<!-- <button type="button" onclick="checkUsernameConflict()" class="form-btn">중복확인</button> -->
										</div>
									</div>
									<div class="form-group">
										<div class="labels w-ba">
											<span>닉네임 *</span>
										</div>
										<div class="infos">
											<div class="input-container">
												<input class="form-control" data-parsley-trigger="focusout" data-parsley-pattern="/^[ㄱ-ㅎ|가-힣|\w|]+$/" data-parsley-pattern-message="유효하지 않은 값입니다." data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." data-parsley-minlength="2" data-parsley-minlength-message="2 자 이상 입력하세요." data-parsley-maxlength="15" data-parsley-maxlength-message="15 자 이하로 입력하세요."  placeholder="2자이상 한글,영문만 가능" autocomplete="off" name="first_name" type="text" value="">
											</div>
											<!-- <button type="button" onclick="checkNicknameConflict()" class="form-btn">중복확인</button> -->
										</div>
									</div>
									<div class="form-group">
										<div class="labels w-ba">
											<span>비밀번호 *</span>
										</div>
										<div class="infos">
											<div class="input-container">
												<input class="form-control " id="strPassword" maxlength="16" data-parsley-trigger="change" data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." data-parsley-maxlength="16" data-parsley-maxlength-message="16 자 이하로 입력하세요." data-parsley-minlength="4" data-parsley-minlength-message="4 자 이상 입력하세요." autocomplete="off" name="password" type="password" value="">
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="labels w-ba">
											<span>비밀번호확인 *</span>
										</div>
										<div class="infos">
											<div class="input-container">
												<input class="form-control " data-parsley-equalto="#strPassword" data-parsley-equalto-message="비밀번호가 일치하지 않습니다." maxlength="12" data-parsley-trigger="change" data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." data-parsley-minlength="4" data-parsley-minlength-message="4 자 이상 입력하세요." autocomplete="off" name="password_confirm" type="password" value="">
											</div>
										</div>
									</div>
									<!-- <div class="form-group">
										<div class="labels w-ba">
											<span>추천코드 *</span>
										</div>
										<div class="infos">
											<div class="input-container">
												<input id="strMark" class="form-control " maxlength="30" data-parsley-trigger="change" data-parsley-maxlength="30" data-parsley-maxlength-message="30 자 이하로 입력하세요." data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." autocomplete="off" name="referee" type="text" value="">
											</div>
										</div>
									</div> -->
								</div>
							</div>
							<div class="col-12 col-md-6">
								<div class="form-container">
									<div class="form-group">
										<div class="labels w-ba">
											<span>핸드폰 *</span>
										</div>
										<div class="infos">
											<div class="input-container">
												<input name="phone" type="text" value="" class="sign-up" data-parsley-type="digits" data-parsley-type-message="이 값은 숫자만 입력 가능합니다." data-parsley-pattern="/^[0-9]*$/" data-parsley-pattern-message="유효하지 않은 값입니다." maxlength="15" data-parsley-trigger="change input focusin focusout" data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." data-parsley-maxlength="15" data-parsley-maxlength-message="15 자 이하로 입력하세요." data-parsley-minlength="9" data-parsley-minlength-message="9 자 이상 입력하세요." placeholder="&#039;-&#039;없이 숫자 만 입력" autocomplete="off">
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="labels w-ba">
											<span>은행명 *</span>
										</div>
										<div class="infos">
											<div class="input-container">
												<div class="select-input">
													<select class="form-control " data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." name="bank_name">
														<option value="">은행선택</option>
														
															<option value="국민은행">국민은행</option>
														
															<option value="광주은행">광주은행</option>
														
															<option value="경남은행">경남은행</option>
														
															<option value="기업은행">기업은행</option>
														
															<option value="농협은행">농협은행</option>
														
															<option value="대구은행">대구은행</option>
														
															<option value="도이치은행">도이치은행</option>
														
															<option value="부산은행">부산은행</option>
														
															<option value="상호저축은행">상호저축은행</option>
														
															<option value="새마을금고">새마을금고</option>
														
															<option value="수협은행">수협은행</option>
														
															<option value="신협은행">신협은행</option>
														
															<option value="신한은행">신한은행</option>
														
															<option value="외환은행">외환은행</option>
														
															<option value="우리은행">우리은행</option>
														
															<option value="우체국">우체국</option>
														
															<option value="전북은행">전북은행</option>
														
															<option value="제주은행">제주은행</option>
														
															<option value="하나은행">하나은행</option>
														
															<option value="한국씨티은행">한국씨티은행</option>
														
															<option value="HBC은행">HBC은행</option>
														
															<option value="SC제일은행">SC제일은행</option>
														
															<option value="산림조합">산림조합</option>
														
															<option value="카카오뱅크">카카오뱅크</option>
														
															<option value="케이뱅크">케이뱅크</option>
														
															<option value="SB저축은행">SB저축은행</option>
														
															<option value="테스트">테스트</option>
														
															<option value="신협">신협</option>
														
													</select>
													<i class="fas fa-caret-down"></i>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="labels w-ba">
											<span>예금주명 *  (입금과 출금시 사용/수정불가/고객센터문의)</span>
										</div>
										<div class="infos">
											<div class="input-container">
												<input class="form-control" data-parsley-trigger="change" data-parsley-pattern="/^[ㄱ-ㅎ|가-힣|\w|]+$/" data-parsley-pattern-message="유효하지 않은 값입니다." maxlength="15" data-parsley-minlength="2" data-parsley-minlength-message="2 자 이상 입력하세요." data-parsley-maxlength="15" data-parsley-maxlength-message="15 자 이하로 입력하세요." data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." placeholder="2자이상 한글, 영문만 가능" autocomplete="off" name="recommender" type="text" value="">
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="labels w-ba">
											<span>계좌번호 *</span>
										</div>
										<div class="infos">
											<div class="input-container">
												<input class="form-control " data-parsley-type="digits" data-parsley-type-message="이 값은 숫자만 입력 가능합니다." data-parsley-pattern="/^[0-9]*$/" data-parsley-pattern-message="유효하지 않은 값입니다." maxlength="30" data-parsley-trigger="change" data-parsley-maxlength="30" data-parsley-maxlength-message="30 자 이하로 입력하세요." data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." placeholder="&#039;-&#039;없이 숫자 만 입력" autocomplete="off" name="account_no" type="text" value="">
											</div>
										</div>
									</div>
									<!-- <div class="form-group">
										<div class="labels w-ba">
											<span>환전 비밀번호 *</span>
										</div>
										<div class="infos">
											<div class="input-container">
												<input class="form-control " maxlength="30" data-parsley-trigger="change" data-parsley-maxlength="30" data-parsley-maxlength-message="30 자 이하로 입력하세요." data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." autocomplete="off" name="pwd_xchg" type="text" value="">
											</div>
										</div>
									</div> -->
								</div>
							</div>
						</div>
						<div class="form-footer dflex-ac-jc">
							<button type="submit" class="btn-gold">회원가입</button>
							<button type="button" class="btn-red login-link">로그인</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$("[name='username']").parsley().addAsyncValidator(
			'checkuserid', function (xhr) {
				if(xhr.status == '200'){
					response = $.parseJSON(xhr.responseText);
					if(response.error){
						return false;
					}
		
					return true;
				}
		
				return false;
			}
		);
	});

	function onJoin() {
		$("#signUpForm").parsley().validate();

		if ($("#signUpForm").parsley().isValid()) {
			var formData = new FormData();
			formData.append("_token", $('#_token').val());
			formData.append("username", $("[name='username']").val());
			formData.append("first_name", $("[name='first_name']").val());
			formData.append("password", $("[name='password_confirm']").val());
			formData.append("bank_name", $("[name='bank_name']").val());
			formData.append("account_no", $("[name='account_no']").val());
			formData.append("recommender", $("[name='recommender']").val());
			formData.append("phone", $("[name='phone']").val());

			$.ajax({
				type: "POST",
				url: "/api/join",
				data: formData,
				processData: false,
				contentType: false,
				cache: false,
				async: false,
				success: function(data, status) {
					if (data.error) {
						alert(data.msg);
						return;
					} 

					alert("회원가입 요청이 접수되었습니다. 승인여부는 고객센터에 문의해주세요.");
					location.reload(true);
				},
				error: function(err, xhr) {}
			});
		} 
	}

	function chkCharCode(event) {
		const regExp = /[^0-9a-zA-Z_]/g;
		const ele = event.target;
		if (regExp.test(ele.value)) {
			ele.value = ele.value.replace(regExp, '');
		}
	}
		
</script>