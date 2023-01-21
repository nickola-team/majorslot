<!-- 헤더영역 -->
<section class="banner-section w-ba">
	<img class="banner-bg" src="/frontend/golden/images/bg/banner-bg.jpg">
	<div class="container h-100 dflex-ac-jc">
		<div class="center w-100">
			<a href="" class="logo">
				<img class="logo-bg" src="/frontend/golden/images/logo/logo-bg.png">
				<img class="logo-img" src="/frontend/golden/images/logo/aria.png">
				<img class="a-b" src="/frontend/golden/images/logo/aria.png">
				<!-- <img class="r" src="/frontend/golden/images/logo/r.png">
				
				<img class="a" src="/frontend/golden/images/logo/a.png"> -->
			</a>
			<div class="banner-carousel carousel dflex-ac-jc align-content-center w-ba pointer-event" data-ride="carousel" data-pause="false" data-interval="6000">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="text-panel w-ba">
                            
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="text-panel w-ba">
                            
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="text-panel w-ba">
                            
                        </div>
                    </div>
                </div>
                <ol class="carousel-indicators dflex-ac-jc">
                    <li data-target=".banner-carousel" data-slide-to="0" class="active"></li>
                    <li data-target=".banner-carousel" data-slide-to="1" class=""></li>
                    <li data-target=".banner-carousel" data-slide-to="2" class=""></li>
                </ol>
            </div>
		</div>
		<div class="banner-background w-ba">
			<div class="light">
				<img src="/frontend/golden/images/banner/light.png">
			</div>
			<div class="glow-left">
				<img src="/frontend/golden/images/banner/glow-left.png">
			</div>
			<img class="casino-items" src="/frontend/golden/images/banner/casino-items.png">
			<img class="girl-left" src="/frontend/golden/images/banner/girl-left.png">
			<img class="girl-right" src="/frontend/golden/images/banner/girl-right.png">
			<img class="girl-center" src="/frontend/golden/images/banner/girl-center.png">
			<div class="glow-right">
				<img src="/frontend/golden/images/banner/glow-right.png">
			</div>
			<img class="candy-slot" src="/frontend/golden/images/banner/candy-slot.png">
			<img class="money-slot" src="/frontend/golden/images/banner/money-slot.png">
		</div>
	</div>
</section>

<header class="header-section w-ba">
	<div class="container dflex-ac-jc h-100">
		<ul class="bs-ul main-menu sidebar-left">
			<li><a href="javascript:void(0);" class="deposit-link dflex-ac-jc w-ba"><i class="fa-solid fa-piggy-bank"></i> 입금신청</a></li>
			<li><a href="javascript:void(0);" class="withdraw-link dflex-ac-jc w-ba"><i class="fa-solid fa-vault"></i> 출금신청</a></li>
			<li><a href="javascript:void(0);" class="event-link dflex-ac-jc w-ba"><i class="fa-solid fa-martini-glass-citrus"></i> 이벤트</a></li>
			<li><a href="javascript:void(0);" class="notice-link dflex-ac-jc w-ba"><i class="fa-solid fa-bell"></i> 공지사항</a></li>
		</ul>
		<div class="bal-container ml-auto">
			<div class="before-login active">
                @if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
                    <div class="mobile">
						<div class="dflex-ac-jc">
							<button class="btn-orange mypage-btn"><i class="fa-solid fa-caret-down mr-2"></i> 나의 정보</button>
							<button class="btn-gold logout-btn"><i class="fa-solid fa-right-from-bracket mr-2"></i> 로그아웃</button>
						</div>
					</div>
					<div class="sidebar">
						<div class="al-inner">
							<a href="javascript:void(0);" class="al-cont mypage-link" data-target=".item-info">
								<div class="labels">
									<i class="fas fa-user-tie"></i>
								</div>
								<div class="info">
									<span>{{ Auth::user()->username }}</span>
									<span class="symbol">님</span>
								</div>
							</a>
							<a href="javascript:void(0);" class="al-cont mypage-link mypage-letter-panel" data-target=".item-letter">
								<div class="labels">
									<i class="fas fa-square-envelope"></i>
								</div>
								<div class="info">
									<span>쪽지</span>
									<span class="message-count userLetterCount">{{$newMsgCount}}</span>
								</div>
							</a>
							<div class="al-cont btn-group">
								<button type="button" class="btn-yellow" onclick="getBalance();">새로고침</button> 
							</div>
							<a href="javascript:void(0);" class="al-cont deposit-link">
								<div class="labels">
									<i class="fa-solid fa-sack-dollar"></i>
								</div>
								<div class="info">
									<span class="wager-reload">보유머니 <span class="userCashAmount">{{ number_format(Auth::user()->balance,2) }}</span></span>
									<span class="symbol">원</span>
								</div>
							</a>
							<!-- hidden panel -->
							<a href="javascript:void(0);" class="al-cont mypage-link mypage-qna-panel" data-target=".item-qna">
								<div class="labels">
									<i class="fas fa-comment-dots"></i>
								</div>
								<div class="info">
									<span>문의하기</span>
									<!-- <span class="message-count userReplyCount">0</span> -->
								</div>
							</a>
							
							
							<div class="al-cont btn-group">
								<button type="button" class="btn-gold logout-btn desktop">로그아웃</button>
								
							</div>
						</div>
					</div>
                @else
                    <form name="login" onsubmit="loginSubmit(this); return false;">
						<div class="desktop">
							<div class="dflex-ac-jc">
								<div class="input-panel dflex-ac-jc">
									<div class="icon-panel dflex-ac-jc">
										<i class="fas fa-user-tie"></i>
									</div>
									<input type="text" placeholder="아이디" name="userid" data-parsley-required="required" data-parsley-required-message="" autocomplete="off">
								</div>
								<div class="input-panel dflex-ac-jc">
									<div class="icon-panel dflex-ac-jc">
										<i class="fas fa-unlock-alt"></i>
									</div>
									<input type="password" placeholder="비밀번호" name="password" data-parsley-required="required" data-parsley-required-message="" autocomplete="off">
								</div>
								<button type="submit" class="btn-yellow login-btn"">로그인</button>
								<button type="button" class="btn-red join-link">회원가입</button>
							</div>
						</div>
						<div class="mobile">
							<div class="dflex-ac-jc">
								<button type="button" class="btn-yellow login-link"><i class="fa-solid fa-right-to-bracket mr-2"></i> 로그인</button>
								<button type="button" class="btn-red join-link"><i class="fa-solid fa-user-plus mr-2"></i> 회원가입</button>
							</div>
						</div>
					</form>
                @endif
				
			</div>
		</div>
	</div>
</header>