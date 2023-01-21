<!-- ------------------------------------- My Page ------------------------------------ -->
<div class="myPageModal modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered-not" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button data-dismiss="modal" class="modal-close-btn w-ba"></button>
				<div class="modal-banner">
					<a href="javascript:void(0);" class="modal-logo">
						<img class="w-100" src="/frontend/golden/images/logo/aria.png">
					</a>
					<div class="modal-title">
						<div class="modal-icon dflex-ac-jc">
							<i class="fa-solid fa-user-gear"></i>
						</div>
						<div class="title-panel">
							<span class="title">마이페이지</span>
							<span class="sub">MY PAGE</span>
						</div>
					</div>
				</div>
				<div class="modal-head-panel dflex-ac-jc">
					<div class="btn-grp ml-auto dflex-ac-jc">
						<button class="btn-gold deposit-link">메뉴보기</button>
						<button class="btn-gold deposit-hist-link">입출금내역</button>
					</div>
				</div>
			</div>
			<div class="modal-body">
				<div class="modal-menu modal-nav dflex-ac-js">
					<ul class="bs-ul w-100 table-layout-fixed">
						<li>
							<a href="javascript:void(0);" class="dflex-ac-jc item-info active" data-class="item-info" data-target=".tab-info">
								<div class="icon-panel dflex-ac-jc">
									<i class="fa-solid fa-house-chimney-user"></i>
								</div>
								<span>나의 정보</span>
							</a>
						</li>
						
						<li>
							<a href="javascript:void(0);" class="dflex-ac-jc item-letter" data-class="item-letter" data-target=".tab-letter">
								<div class="icon-panel dflex-ac-jc">
									<i class="fa-solid fa-square-envelope"></i>
								</div>
								<span>쪽지함</span>
							</a>
						</li>
						<li>
							<a href="javascript:void(0);" class="dflex-ac-jc item-qna" data-class="item-qna" data-target=".tab-qna">
								<div class="icon-panel dflex-ac-jc">
									<i class="fa-solid fa-comment-dots"></i>
								</div>
								<span>문의하기</span>
							</a>
						</li>
					</ul>
				</div>
				<div class="modal-panel">
					<!-- Tab -->
					<div class="modal-tab tab-info active">
						
						<form name="frm_mypage" onsubmit="updateMyInfo(); return false;" >
							<input name="withdraw_method" type="hidden" value="BANK">
							<input name="withdraw_classification" type="hidden" value="">
							<div class="form-container">
								<div class="form-group">
									<div class="labels w-ba">
										<span>아이디(닉네임)</span>
									</div>
									<div class="infos">
										<div class="input-container">
											<input type="text" name="username" value="{{ Auth::user()->username }}" readonly>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="labels w-ba">
										<span>보유금액</span>
									</div>
									<div class="infos">
										<div class="input-container">
											<input type="text" class="player-balance-input" value="{{ number_format(Auth::user()->balance,2) }}" readonly>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="labels w-ba">
										<span>은행명</span>
									</div>
									<div class="infos">
										<div class="input-container">
											<input type="text" name="bank_name" value="{{ Auth::user()->bank_name }}" readonly>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="labels w-ba">
										<span>예금주</span>
									</div>
									<div class="infos">
										<div class="input-container">
											<input type="text" name="bank_holder" value="{{ Auth::user()->recommender }}" readonly>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="labels w-ba">
										<span>계좌번호</span>
									</div>
									<div class="infos">
										<div class="input-container">
											<input type="text" name="bank_accnum" value="{{ Auth::user()->account_no }}" readonly>
										</div>
									</div>
								</div>
								<!-- <div class="form-group">
									<div class="labels w-ba">
										<span>현재 비밀번호</span>
									</div>
									<div class="infos">
										<div class="input-container">
											<input type="password" name="password" value="">
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="labels w-ba">
										<span>변경 비밀번호</span>
									</div>
									<div class="infos">
										<div class="input-container">
											<input type="password" name="chg_pwd" value="">
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="labels w-ba">
										<span>변경 비번확인</span>
									</div>
									<div class="infos">
										<div class="input-container">
											<input type="password" name="confirm_pwd" value="">
										</div>
									</div>
								</div> -->
							</div>
							<div class="form-footer dflex-ac-jc">
								<!-- <button type="submit" class="btn-yellow">정보수정</button>
								<button type="reset" class="btn-red" data-dismiss="modal">취소</button> -->
							</div>
						</form>
						<!-- <script type="text/javascript">
							function updateMyInfo() {
								ajaxFormSend('frm_mypage', '/front/mypage/info/update.json', { }, function(data) {
									if (data.success) {
										showMsg('조작성공!', 'success');
									} else {
										if (data.message) showMsg(data.message, 'error');
									}
								})
							}
						</script> -->
					</div>
					
					<div class="modal-tab tab-letter">
						<table class="bs-table with-depth">
							<thead>
								<tr>
									<th colspan="6">쪽지함</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
						<div class="tb_empty hidden">데이터가 없습니다.</div>
						<div class="paging_box"></div>
					</div>

					<div class="modal-tab tab-qna">
						<form name="frm_qna" onsubmit="sendQna(); return false;" data-parsley-validate>
							<div class="form-container mw-100 mt-5">
								<div class="form-group">
									<div class="infos">
										<div class="input-container">
											<input class="" data-parsley-trigger="change" data-parsley-required="true" data-parsley-required-message="필수 항목입니다." data-parsley-maxlength="30" data-parsley-maxlength-message="30자이하로 입력해주세요." placeholder="제목(30자 이내)" name="qna_title" type="text" value="">
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="infos">
										<div class="input-container text-area">
											<textarea type="text" class="reqcontent w_100 vertical" id="tinymce" value="" placeholder="내용"></textarea>
										</div>
									</div>
								</div>
							</div>

							<div class="form-footer dflex-ac-jc">
								<button type="submit" class="btn-gold">문의하기</button>
								<button type="reset" class="btn-red" data-dismiss="modal">취소하기</button>
							</div>
						</form>
					</div>
					<!-- End Tab -->
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	$(function () {
		$('.myPageModal .modal-panel .tab-letter').on('tab-clicked', function () {
			getLetterList(0);
		});

		$('.myPageModal .modal-panel .tab-letter table').on('click', '.delete-btn', function (e) {
			let id = $(this).closest('tr').data('id');
			let type = $(this).closest('tr').data('type');
			deleteLetter(id, type);
			e.preventDefault();
			e.stopPropagation();
		});

		$('.myPageModal .modal-panel .tab-letter table tbody').on('click', 'tr.depth-click', function(e) {
			if ($(e.target).closest('button.delete-btn').length > 0) {
				return;
			}

			let $row = $(this);
			$(this).toggleClass("active");
			$(this).siblings(".depth-click").removeClass("active");
			let $content = $(this).next(".dropdown");
			if (!$content.length) {
				$content = $('<tr class="dropdown">\
			        <td colspan="6">\
						<div class="message-content" style="display: none;">\
							<div class="inner-container">데이터가 없습니다.</div>\
						</div>\
			        </td>\
			    </tr>');
				$(this).after($content);

				let letterId = $(this).data('id');
				let letterType = $(this).data('type');
				let letterStatus = $(this).data('status');

				$.ajax({
					url: '/api/getdatabyjson',
					type: 'POST',
					data: {page: 1, target: 'MGD', idx: letterId},
					success: function (res) {
						if (res.error) {
							return;
						}

						$content.find('.inner-container').html(res.data.content);

						if (letterStatus == 2) {
							$.ajax({
								url: '/api/setdata/readmsg',
								type: 'POST',
								data: {messageId: letterId, receiveType: letterType, readStatus: letterStatus},
								headers: {},
								success: function (data) {
									$row.removeClass('tr-new');

									let newCount = $('.userLetterCount').text() - 1;
									$('.userLetterCount').html(newCount);
								},
								error: function (xhr, status, error) {
								},
								complete: function () {
								}
							});
						}
					},
					error: function (xhr, status, error) {
						alert(error.responseText);
					}
				});
			}

			if ($(this).hasClass("active")) {
				$content.find(".message-content").slideDown();
				$content.siblings(".dropdown").find(".message-content").slideUp();
			}
			else $content.find(".message-content").slideUp();
		});
	});

	function getLetterList(pg_start = -1) {
		$.ajax({
			url: '/api/getdatabyjson',
			type: 'POST',
			data: {page: 1, target: 'MG'},
			success: function (res) {
				if (res.error) {
					return;
				}

				let $sel = "";
				let data = res.data;
				let newCount = 0;
				for (let i = 0; i < data.length; i ++) {
					let info = data[i];
					let readStatus = '';
					if (!info.checked_at) {
						readStatus = 'tr-new';
						newCount ++;
					}
					
					$sel +=
						'<tr class="depth-click ' + readStatus +'" data-id="' + info.id + '" data-status="' + (info.checked_at ? 1 : 2)  + '"data-type="' + info.target + '" >\
							<td class="title-td">' + info.subject + '</td>\
							<td class="nav-td">\
								<button type="button" class="delete-btn">\
									<i class="fa fa-trash" aria-hidden="true"></i>\
								</button>\
							</td>\
							<td class="nav-td">\
								<button type="button" class="nav-btn plus-btn"></button>\
							</td>\
						</tr>';
				}

				$('.userLetterCount').html(newCount);

				if (data.length > 0) {
					$('.myPageModal .tab-letter>table>tbody').html($sel);
					$('.myPageModal .tab-letter>.tb_empty').addClass('hidden');
				} else {
					$('.myPageModal .tab-letter>table>tbody').empty();
					$('.myPageModal .tab-letter>.tb_empty').removeClass('hidden');
				}
			},
			error: function (xhr, status, error) {
				alert(error.responseText);
			}
		});
	}

	function deleteLetter(id, target) {
		confirmMsgYn('해당 쪽지를 삭제하시겠습니까?', function (){
			$.ajax({
				url: '/api/setdata/delmsg',
				type: 'POST',
				data: {messageId: id, receiveType: target},
				success: function (data) {
					showMsg('조작 성공', 'success');
					getLetterList();
				},
				error: function (xhr, status, error) {
				},
				complete: function () {
				}
			});
		})
	}

	$(function () {
		if($('textarea.reqcontent').length > 0) {   
			tinymce.remove();
			tinymce.init({
				mode : "specific_textareas"
				, editor_selector : "reqcontent"
				, language: 'ko_KR'
				, plugins: ["advlist", "autolink", "lists", "link", "image", "charmap", "print", "preview", "anchor", "searchreplace", "visualblocks", "code", "fullscreen", "insertdatetime", "media", "table", "paste", "code", "help", "wordcount", "save"]
				, toolbar: 'fontselect | fontsizeselect | forecolor | bold italic underline | alignjustify alignleft aligncenter alignright |  numlist | table tabledelete | link image'
				, toolbar_mode: 'wrap'
				
				, menubar: false
				// statusbar: false,
				, branding: false
				, elementpath: false
				, relative_urls : false
				, remove_script_host : false
				, convert_urls : true
				, image_advtab: false

				, mobile : {
					plugins: ["advlist", "autolink", "lists", "link", "image", "charmap", "print", "preview", "anchor", "searchreplace", "visualblocks", "code", "fullscreen", "insertdatetime", "media", "table", "paste", "code", "help", "wordcount", "save", 'autoresize']
					, toolbar: 'fontselect | fontsizeselect | forecolor | bold italic underline | alignjustify alignleft aligncenter alignright |  numlist | table tabledelete | link image'
					, toolbar_mode: 'wrap'
					
					, menubar: false
					// statusbar: false,
					, branding: false
					, elementpath: false
					, relative_urls : false
					, remove_script_host : false
					, convert_urls : true

					, image_advtab: false
					, placeholder: "내용을 입력하세요."
				}
				// , skin: "sublime-dark"
				// , content_css: "sub-dark"
				, content_style: `
					.mce-content-body[data-mce-placeholder]:not(.mce-visualblocks)::before {
						color: rgb(255 255 255 / 30%);
					}`
			});
		}
			
	});

	function sendQna() {
		let title = $('form[name=frm_qna] input[name=qna_title]').val();
		let content = tinymce.get('tinymce').getContent();

		var formData = new FormData();
		formData.append("_token", $('#_token').val());
		formData.append("subject", title);
		formData.append("content", content);

		confirmMsgYn("고객센터로 문의를 보내시겠습니까?", function(){
			$.ajax({
				type: "POST",
				url: "/api/setdata/writemsg",
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

					showMsg(data.msg, 'success');
					location.reload(true);
				},
				error: function(err, xhr) {}
			});
		})
	}


	$(function () {
		$('.myPageModal').on('show.bs.modal', function () {
			let last_tab = $(this).data('last_tab');
			if (!last_tab) {
				return;
			}
			$('.myPageModal .modal-menu > ul > li > a' + last_tab).click();
		});

		let $tabs = $('.myPageModal .modal-menu > ul > li > a');
		$tabs.click(function () {
			$tabs.removeClass('active');
			$(this).addClass('active');
			let selTarget = $(this).data('target');

			$('.myPageModal .modal-tab' + selTarget).addClass('active').siblings().removeClass('active');
			$('.myPageModal .modal-tab' + selTarget).triggerHandler('tab-clicked');

			let tabClass = $(this).data('class');
			$('.myPageModal').data('last_tab', "." + tabClass);
		});
	});
</script>
