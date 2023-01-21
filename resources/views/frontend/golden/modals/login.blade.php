<!-- ------------------------------------- Login ------------------------------------ -->
<div class="loginModal modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button data-dismiss="modal" class="modal-close-btn w-ba"></button>
				<div class="modal-banner">
					<a href="javascript:void(0);" class="modal-logo">
						<img class="w-100" src="/frontend/golden/images/logo/aria.png">
					</a>
					<div class="modal-title">
						<div class="modal-icon dflex-ac-jc">
							<i class="fa-solid fa-door-open"></i>
						</div>
						<div class="title-panel">
							<span class="title">로그인</span>
							<span class="sub w-mob-title">WELCOME TO GOLDENBULL CASINO</span>
						</div>
					</div>
				</div>
				<div class="modal-head-panel dflex-ac-jc">
					<span class="modal-title-mob">WELCOME TO GOLDENBULL CASINO</span>
				</div>
			</div>
			<div class="modal-body pt-md-5 pt-3">
				<form name="pop_login" onsubmit="loginSubmit(this); return false;" data-parsley-validate>
					<div class="form-container">
						<div class="form-group">
							<div class="infos">
								<div class="input-container">
									<input type="text" placeholder="아이디" name="userid"
										data-parsley-required="required"
										data-parsley-required-message="필수입력 항목입니다."
										autocomplete="off">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="infos">
								<div class="input-container">
									<input type="password" placeholder="비밀번호" name="password"
										data-parsley-required="required"
										data-parsley-required-message="필수입력 항목입니다."
										autocomplete="off">
								</div>
							</div>
						</div>
					</div>
					<div class="form-footer dflex-ac-jc">
						<button type="submit" class="btn-yellow login-btn">로그인</button>
						<button type="button" class="btn-red join-link">회원가입</button>
					</div>
				</form>

			</div>
		</div>
	</div>
</div>