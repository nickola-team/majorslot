@extends('frontend.golden.layouts.app')
@section('page-title', $title)

@section('content')
<main class="page-content w-ba">
	<section class="toggle-section toggle-2">
		<div class="container dflex-ac-ja">
			<button class="toggle-btn casino-toggle" data-target="casino">
				<div class="btn-panel dflex-ac-js w-ba ml-auto">
					<div class="icon-panel w-ba dflex-ac-jc">
						<img src="/frontend/golden/images/icon/casino-icon.png">
					</div>
					<span class="category">라이브카지노</span>
					<i class="fa-solid fa-caret-down indicator ml-md-3 ml-1"></i>
				</div>
			</button>
			
			<button class="toggle-btn slot-toggle" data-target="slot">
				<div class="btn-panel dflex-ac-je w-ba mr-auto">
					<i class="fa-solid fa-caret-down indicator mr-md-3 mr-1"></i>
					<span class="category">슬롯게임</span>
					<div class="icon-panel w-ba dflex-ac-jc">
						<img src="/frontend/golden/images/icon/slot-icon.png">
					</div>
				</div>
			</button>
		</div>
	</section>
	<section class="game-provider">
		<div class="container">
			<div class="casino-section sc-section">
			@if ($categories && count($categories))
				@foreach($categories AS $index=>$category)

                    <a data-code="{{ $category->game_code }}" data-title="{{$category->trans_kr}}" class="sc-btn w-ba main_pop_open}">
	
                        <div class="g-panel w-ba">
                            <div class="g-cont">
                                <img class="g-img" src="/frontend/golden/images/provider/live/{{ $category->title.'.png' }}">
                                <button type="button" class="play-btn btn-yellow" onclick="openGameLobbyByProvider('{{ $category->game_code }}')">게임입장</button>
                            </div>
                            <div class="g-footer w-ba">
                                <!-- <div class="g-logo dflex-ac-jc w-ba">
                                    <img class="icon-img" src="/frontend/golden/images/provider-icon/{{ $category->title.'.png' }}">
                                </div> -->
                            </div>
                            <div class="g-info">
                                <span class="g-name">{{$category->trans_kr}}</span>
                                <span class="en-text">{{$category->title}}</span>
                            </div>
                            <div class="glass"></div>
                        </div>
                    </a>

                @endforeach
            @endif
			</div>
			<div class="slot-section sc-section">
            @if ($categories && count($categories))
				@foreach($categories AS $index=>$category)

                    <a data-code="{{ $category->game_code }}" data-title="{{$category->trans_kr}}" class="sc-btn w-ba main_pop_open}">
	
                        <div class="g-panel w-ba">
                            <div class="g-cont">
                                <img class="g-img" src="/frontend/golden/images/provider/slot/{{ $category->title.'.png' }}">
                                <button type="button" class="play-btn btn-yellow" onclick="showGamesPopup('{{ $category->title }}', '{{ $category->game_code }}')">게임입장</button>
                            </div>
                            <div class="g-footer w-ba">
                                <div class="g-logo dflex-ac-jc w-ba">
                                    <img class="icon-img" src="/frontend/golden/images/provider-icon/{{ $category->title.'.png' }}">
                                </div>
                            </div>
                            <div class="g-info">
                                <span class="g-name">{{$category->trans_kr}}</span>
                                <span class="en-text">{{$category->title}}</span>
                            </div>
                            <div class="glass"></div>
                        </div>
                    </a>

                @endforeach
            @endif
			</div>
		</div>
	</section>
</main>
<section class="board-section w-ba">
	<div class="container">
		<div class="row dflex-ac-jc align-items-start">
			<div class="board-panel event-board">
				<div class="header dflex-ac-jc w-ba">
					<div class="title-panel dflex-ac-js">
						<div class="icon-panel dflex-ac-jc align-content-end">
							<img class="icon" src="/frontend/golden/images/icon/event-icon.png">
						</div>
						<span data-text="이벤트" class="title">이벤트</span>
					</div>
					
					<a href="javascript:void(0);" class="more-link ml-auto event-link"><i class="fa-solid fa-bars-staggered"></i> 더보기</a>
				</div>
				<div class="content btm-event-section">
					<table>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>
			<div class="realtime-board w-ba btm-finance-section">
				<div class="realtime-nav dflex-ac-jc">
					<button type="button" class="active">실시간출금</button>
					<button type="button">실시간입금</button>
				</div>
				<div class="rb-panel w-ba">
					<div class="rb-cont">
						<div class="rolling-realtime withdraw-realtime active paused" style="overflow: hidden; position: relative; height: 240px;">
							<ul style="position: absolute; margin: 0px; padding: 0px; top: 0px;">

								
								
							</ul>
						</div>
						<div class="rolling-realtime deposit-realtime" style="overflow: hidden; position: relative; height: 240px;">
							<ul style="position: absolute; margin: 0px; padding: 0px; top: 0px;">
								
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="board-panel notice-board">
				<div class="header dflex-ac-jc w-ba">
					<div class="title-panel dflex-ac-jc">
						<div class="icon-panel dflex-ac-jc align-content-end">
							<img class="icon" src="/frontend/golden/images/icon/notice-icon.png">
						</div>
						<span data-text="이벤트" class="title">공지사항</span>
					</div>
					<a href="javascript:void(0);" class="more-link ml-auto notice-link"><i class="fa-solid fa-bars-staggered"></i> 더보기</a>
				</div>
				<div class="content btm-notice-section">
					<table>
						<tbody>
						@foreach($noticelist AS $notice)
						<tr>
							<td class="text-left">
								<a href="javascript:void(0);" class="noticeview-link" data-id="{{$notice->id}}">{{$notice->title}}</a>
							</td>
							<td class="date-td">{{$notice->date_time}}</td>
						</tr>
						@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>

@if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
<input type="hidden" id="is_sign_in" value="1"/>

<!-- ------------------------------------- Gamelist ------------------------------------ -->
<div class="gamelistModal modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
    <div class="modal-dialog modal-dialog-centered-not pop-slot-section" role="document">

        <script>
            $(document).ready(function () {
                $('img.lazyLoad').lazyload();
            });

            $('.gamelistModal .slot-division').on('click', function () {
                $('.slot-division').removeClass('active');
                $('.slots').addClass('hidden');
                $(this).addClass('active');
                $($(this).data('target')).removeClass('hidden');
            });
        </script>
        
        <script type="text/javascript">
            $(function() {
                $('.gamelistModal .slot_list').on('click', '.play_slot', function() {
                    let title = $(this).data('name');
                    openPopupWindow("Loading...");
                    ajaxSend('/front/game/play/slot.json', {
                        vendor_code: 'pragmatic.slot'
                        , name: $(this).data('name')
                        , uuid: $(this).data('uuid')
                    }, function(data) {
                        if (data.success) {
                            // openPopupGame(data.message);
                            openPopupGame("/front/game/play?title=" + title + "&key=" + data.message);
                        } else {
                            openPopupWindow(data.message);
                        }
                    })
                });

                $('input[name=search]').change(function() {
                    searchSlotList();
                });
                
            });

            function searchSlotList() {
                $('.gamelistModal .slot_list>a.game-btn').each(function() {
                    let search = $('input[name=search]').val();
                    let name = $(this).find('.play_slot').data('name');
                    if (createFuzzyMatcher(search).test(name)) {
                        $(this).removeClass('hidden');
                    } else {
                        $(this).addClass('hidden');
                    }
                });
            }

            function clearSearchString(){
                $('.gamelistModal input[name=search]').val('');
                searchSlotList();
            }

        </script>
    </div>
</div>
@endif
@endsection

@push('js')


<script type="text/javascript">
// const INTERVAL = 10 * 1000; // 10s 간격으로 실시간통계요청 (쪽지,문의처리까지)

// function ignitTimeout() {
// 	setTimeout(getTopStats, INTERVAL);
// }
// var audio = new Audio();
// var lastReqStats = {};

// function getTopStats(onlyOnce = false) {
// 	ajaxSend('/front/top_stats.json', { }, function(jData) {
// 		if (!jData.success) {
// 			console.log(jData);
// 			if (jData.retCode == ERROR_LOGIN) {
// 				confirmMsgOnly("로그인정보가 유효하지 않습니다.", function() {
// 					location.reload();
// 				});
// 				onlyOnce = true;
// 			}
// 			return;
// 		}
// 		let reqStats = jData.data.selfReqStats;

// 		let audioPlayed = tryMarkAndPlayAudio(reqStats);

// 		let letterCnt = sessionStorage.getItem('letterCnt') || '0';
// 		if (parseInt(letterCnt) < reqStats.letterCnt) {
// 			showAlarmMsg("문자 확인", '.item-letter');
// 		}
// 		let replyCnt = sessionStorage.getItem('replyCnt') || '0';
// 		if (parseInt(replyCnt) < reqStats.replyCnt) {
// 			showAlarmMsg("질문&답변 확인", '.item-qna');
// 		}

// 		if (audioPlayed) {
// 			lastReqStats = reqStats;
// 			setTimeout(function () {
// 				tryMarkAndPlayAudio(lastReqStats); // 10초 요청이 너무 오래므로 5초 후에 다시 플레이한다.
// 			}, INTERVAL / 2);
// 		}



// 		selfAskCnt = reqStats.askCnt || 0;
// 		selfLetterCnt = reqStats.letterCnt || 0;
// 		selfReplyCnt = reqStats.replyCnt || 0;
// 		selfInCnt = reqStats.inCnt || 0;
// 		selfOutCnt = reqStats.outCnt || 0;
// 		selfCompXchgCnt = reqStats.compXchgCnt || 0;

// 		sessionStorage.setItem('letterCnt', selfLetterCnt);
// 		sessionStorage.setItem('replyCnt', selfReplyCnt);
// 		sessionStorage.setItem('statInCnt', selfInCnt);
// 		sessionStorage.setItem('statOutCnt', selfOutCnt);
// 		sessionStorage.setItem('compXchgCnt', selfCompXchgCnt);

// 		$('.userLetterCount').html(selfLetterCnt);
// 		$('.userReplyCount').html(selfReplyCnt);


// 		let ownStats = jData.data.userOwnStats;
// 		let cashAmount = formatComma(ownStats.cashAmount + (ownStats.cashIngame || 0));
// 		$('.userCashAmount').each(function () {
// 			if ($(this).prop("tagName") == 'INPUT') {
// 				$(this).val(cashAmount);
// 				return;
// 			}
// 			$(this).html(cashAmount);
// 		});
// 		let cashPoint = formatComma(ownStats.cashPoint);
// 		$('.userCashPoint').each(function () {
// 			if ($(this).prop("tagName") == 'INPUT') {
// 				$(this).val(cashPoint);
// 				return;
// 			}
// 			$(this).html(cashPoint);
// 		});
// 		let cashRolling = formatComma(ownStats.cashRolling);
// 		$('.userCashRolling').each(function () {
// 			if ($(this).prop("tagName") == 'INPUT') {
// 				$(this).val(cashRolling);
// 				return;
// 			}
// 			$(this).html(cashRolling);
// 		});
// 	}, undefined
// 	, function (data) {
// 		if (!onlyOnce) {
// 			ignitTimeout();
// 		}
// 	});
// }

// function tryMarkAndPlayAudio(reqStats) {
// 	let audioPlayed = false;

// 	if (reqStats.letterCnt > 0) {
// 		$('.mypage-letter-panel > .labels').addClass('ic-new');
// 		$('.myPageModal .item-letter > span').addClass('ic-new');

// 		playAudio(audio, 'selfLetter');
// 		audioPlayed = true;
// 	} else {
// 		$('.mypage-letter-panel > .labels').removeClass('ic-new');
// 		$('.myPageModal .item-letter > span').removeClass('ic-new');

// 		if (!audioPlayed) stopAudio(audio);
// 	}

// 	if (reqStats.replyCnt > 0) {
// 		$('.mypage-qna-panel > .labels').addClass('ic-new');
// 		$('.myPageModal .item-qna > span').addClass('ic-new');

// 		playAudio(audio, 'selfReply');
// 		audioPlayed = true;
// 	} else {
// 		$('.mypage-qna-panel > .labels').removeClass('ic-new');
// 		$('.myPageModal .item-qna > span').removeClass('ic-new');

// 		if (!audioPlayed) stopAudio(audio);
// 	}

// 	if ($('.mypage-letter-panel > .labels, .mypage-qna-panel > .labels').hasClass('ic-new')) {
// 		$('.mypage-btn').addClass('pulse-rect');
// 	} else {
// 		$('.mypage-btn').removeClass('pulse-rect');
// 	}

// 	return audioPlayed;
// }

	if ($('.withdraw-realtime ul li').length > 1) {
		$( function () {
			$( '.withdraw-realtime' ).vTicker(
				'init', {
					speed: 1500,
					pause: 0,
					showItems: 5,
					padding: 0,
				});
		});
	}

	if ($('.deposit-realtime ul li').length > 1) {
		$( function () {
			$( '.deposit-realtime' ).vTicker(
				'init', {
					speed: 1500,
					pause: 0,
					showItems: 5,
					padding: 0,
				});
		});
	}

	$(".realtime-nav button").click(function(){
		$(this).addClass('active');
		$(this).siblings().removeClass('active');

		var rti = $(this).index() + 1;

		$(this).parentsUntil('.board-section').find('.rolling-realtime:nth-child(' + rti + ')').addClass('active');
		$(this).parentsUntil('.board-section').find('.rolling-realtime:nth-child(' + rti + ')').siblings().removeClass('active');
	});

$(function() {
	// 게임탭 선택
	let lastCategory = getSessionStorage('category', 'slot');
	
	$(".slot-toggle").on("click", function () {
		let isReClick = $(this).hasClass("active");

		$(this).addClass("active");
		$(this).siblings("button").removeClass("active");
		
		$(".slot-section").addClass("active");
		$(".casino-section").removeClass("active");
		$(".sc-card").removeClass("is-flipped");
		
		// avoid click at first time (reload)
		if ($(this).data('click-toggled')) {
			$("html, body").animate({
				scrollTop: $(".header-section").offset().top
			}, 500);
		}
		$(this).data('click-toggled', true);

		let target = $(this).data('target');
		setSessionStorage('category', target);
		// if (!isReClick || !$(".slot-section").children().length) getGameList(target);
	});
	$(".casino-toggle").on("click", function () {
		let isReClick = $(this).hasClass("active");

		$(this).addClass("active");
		$(this).siblings("button").removeClass("active");

		$(".casino-section").addClass("active");
		$(".slot-section").removeClass("active");
		$(".sc-card").addClass("is-flipped");
		
		// avoid click at first time (reload)
		if ($(this).data('click-toggled')) {
			$("html, body").animate({
				scrollTop: $(".header-section").offset().top
			}, 500);
		}
		$(this).data('click-toggled', true);

		let target = $(this).data('target');
		setSessionStorage('category', target);
		// if (!isReClick || !$(".casino-section").children().length) getGameList(target);
	});

	$("." + lastCategory + "-toggle").click();


	$('#tb_main_notice').on('click', '.title', function() {
		ajaxSend('/front/header/notice/info.json', {
			id: $(this).data('id')
		}, function(jData) {
			if (jData.success) {
				$('#notice_pop').popup('show');
				$('#notice_pop .title').html(jData.data.title);
				$('#notice_pop .content').html(jData.data.content);
			} else {
				if (jData.message) showMsg(jData.message, 'error');
			}
		});
	});

	$('.main_con_wrap').on('click', '.more', function() {
		$('#main_pop').find('.pop_content').empty();
		$('#main_pop').find('.pop_loading').removeClass('hide');
		$('#main_pop').popup('show');

		loading.open();
		ajaxGetSend('/front/header/main', {
			idx: 5
		}, function(data) {
			$('#main_pop').find('.pop_loading').addClass('hide');
			$('#main_pop .popupbox>.pop_content').html(data);
			loading.close();
		}, function () {
			loading.close();
		 });
	})

});

function getGameList(target) {
	loading.open();
	ajaxGetSend('/front/game/' + target, {
	}, function(data) {
		$("." + target + "-section").html(data);
		loading.close();
	}, function () {
		loading.close();
	});
}

// 팝업공지1
	$(function() {
		var ids = getCookie().split(",");

		$('.notice_popup_area').on('click', '.btn_close', function() {
			$(this).closest('.notice_popup').addClass('hidden');
		});
	
		$('.notice_popup_area').on('click', '.btn_today_close', function() {
			$(this).closest('.notice_popup').addClass('hidden');
			let id = $(this).closest('.notice_popup').data('id');
			console.log(id);
			setCookie(id);
		});

		$('.notice_popup_area>.notice_popup').each(function() {
			let id = $(this).data('id');
			if(!ids.includes(id.toString())) $(this).removeClass('hidden');
		})
	});

	function setCookie(id) {
		let ids = getCookie() + id + ",";
		let date = new Date();
        date.setTime(date.getTime() + (24 * 60 * 60 * 1000));
        const expires = "expires=" + date.toUTCString();
        document.cookie = "hide-today=" + ids + "; " + expires + "; path=/";
	}

	function getCookie() {
		const cDecoded = decodeURIComponent(document.cookie); // to be careful
		const cArr = cDecoded .split('; ');
		let res = "";
		cArr.forEach(val => {
			if (val.indexOf("hide-today=") === 0) res = val.substring("hide-today=".length);
		})
		return res;
	}
</script>
@endpush