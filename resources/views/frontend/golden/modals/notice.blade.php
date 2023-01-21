<!-- ------------------------------------- Notice ------------------------------------ -->
<div class="noticeModal modal fade" tabindex="-1" role="dialog">
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
							<i class="fa-solid fa-bell"></i>
						</div>
						<div class="title-panel">
							<span class="title">공지사항</span>
							<span class="sub">NOTICE</span>
						</div>
					</div>
				</div>
				<div class="modal-head-panel dflex-ac-jc">
					<div class="btn-grp ml-auto dflex-ac-jc">
						<button class="btn-gold mypage-link">마이페이지</button>
						<button class="btn-gold deposit-hist-link">입출금내역</button>
					</div>
				</div>
			</div>
			<div class="modal-body">
				<div class="modal-menu dflex-ac-js">
					<ul class="bs-ul w-100 table-layout-fixed">
						<li>
							<a href="javascript:void(0);" class="deposit-link dflex-ac-jc">
								<div class="icon-panel dflex-ac-jc">
									<i class="fa-solid fa-piggy-bank"></i>
								</div>
								<span>입금신청</span>
							</a>
						</li>
						<li>
							<a href="javascript:void(0);" class="withdraw-link dflex-ac-jc">
								<div class="icon-panel dflex-ac-jc">
									<i class="fa-solid fa-vault"></i>
								</div>
								<span>출금신청</span>
							</a>
						</li>
						<li>
							<a href="javascript:void(0);" class="notice-link dflex-ac-jc active">
								<div class="icon-panel dflex-ac-jc">
									<i class="fa-solid fa-bell"></i>
								</div>
								<span>공지사항</span>
							</a>
						</li>
						<li>
							<a href="javascript:void(0);" class="event-link dflex-ac-jc">
								<div class="icon-panel dflex-ac-jc">
									<i class="fa-solid fa-martini-glass-citrus"></i>
								</div>
								<span>이벤트</span>
							</a>
						</li>
					</ul>
				</div>
				<div class="notice-board-section">
					<table class="bs-table notice-table">
						<colgroup>
							<col width="10%"/>
							<col width="50%"/>
							<col width="40%"/>
						</colgroup>
						<thead>
						<tr>
							<th>번호</th>
							<th>제목</th>
							<th>작성일</th>
						</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
					<div class="tb_empty hidden">데이터가 없습니다.</div>
					<div class="paging_box"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- ------------------------------------- Notice View ------------------------------------ -->
<div class="noticeViewModal modal fade" tabindex="-1" role="dialog">
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
							<i class="fa-solid fa-bell"></i>
						</div>
						<div class="title-panel">
							<span class="title">공지사항</span>
							<span class="sub">NOTICE</span>
						</div>
					</div>
				</div>
				<div class="modal-head-panel dflex-ac-jc">
					<div class="btn-grp ml-auto dflex-ac-jc">
						<button class="btn-gold mypage-link">마이페이지</button>
						<button class="btn-gold deposit-hist-link">입출금내역</button>
					</div>
				</div>
			</div>
			<div class="modal-body">
				<div class="modal-menu dflex-ac-js">
					<ul class="bs-ul w-100 table-layout-fixed">
						<li>
							<a href="javascript:void(0);" class="deposit-link dflex-ac-jc">
								<div class="icon-panel dflex-ac-jc">
									<i class="fa-solid fa-piggy-bank"></i>
								</div>
								<span>입금신청</span>
							</a>
						</li>
						<li>
							<a href="javascript:void(0);" class="withdraw-link dflex-ac-jc">
								<div class="icon-panel dflex-ac-jc">
									<i class="fa-solid fa-vault"></i>
								</div>
								<span>출금신청</span>
							</a>
						</li>
						<li>
							<a href="javascript:void(0);" class="notice-link dflex-ac-jc active">
								<div class="icon-panel dflex-ac-jc">
									<i class="fa-solid fa-bell"></i>
								</div>
								<span>공지사항</span>
							</a>
						</li>
						<li>
							<a href="javascript:void(0);" class="event-link dflex-ac-jc">
								<div class="icon-panel dflex-ac-jc">
									<i class="fa-solid fa-martini-glass-citrus"></i>
								</div>
								<span>이벤트</span>
							</a>
						</li>
					</ul>
				</div>
				<div class="notice-view-section">
					<table class="bs-table">
						<thead>
						<tr>
							<th>&nbsp;</th>
							<th>&nbsp;</th>
							<th>&nbsp;</th>
						</tr>
						</thead>
						<tbody>
						<tr class="active">
							<td colspan="3"><a href="javascript:void(0)">데이터를 가져오는 중입니다.</td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

$(function () {
	$('.noticeModal').on('shown.bs.modal', function () {
		let tmp_start = $(this).data('tmp_start') || -1;
		let tmp_active_id = $(this).data('tmp_active_id') || 0;
		$(this).data('tmp_start', undefined);
		$(this).data('tmp_active_id', undefined);

		getNoticeList(tmp_start, tmp_active_id);
	});
	$('.noticeViewModal').on('shown.bs.modal', function () {
		viewNoticeDetail($(this).data('id'));
	});
});

function setNoticeTmpParam(tmp_start, tmp_active_id) {
	$('.noticeModal').data('tmp_start', tmp_start);
	$('.noticeModal').data('tmp_active_id', tmp_active_id);
}

function getNoticeList(pg_start = -1, active_id = 0) {
	$.ajax({
        url: '/api/getdatabyjson',
        type: 'POST',
        data: {page: 1, target: 'NT'},
        success: function (res) {
            if (res.error) {
				return;
			}

			let $sel = "";
			let data = res.data;
			for (let i = 0; i < data.length; i ++) {
				let info = data[i];
				$sel +='<tr class="noticeview-link ' + (info.id == active_id ? 'active' : '') + '" data-id=' + info.id + '>\
					<td class="text-center count-td">' + (i + 1) + '</td>\
					<td class="text-left title-td cursor-pointer" >\
						<a href="javascript:void(0);">&lt;' + (info.title || '') + '&gt;</a>\
					</td>\
					<td class="text-center date-td">' + (info.date_time || '-') + '</td>\
				</tr>';
			}

			if (data.length > 0) {
				$('.noticeModal .notice-board-section > table > tbody').html($sel);
				$('.noticeModal .notice-board-section > .tb_empty').addClass('hidden');
			} else {
				$('.noticeModal .notice-board-section > table > tbody').empty();
				$('.noticeModal .notice-board-section > .tb_empty').removeClass('hidden');
			}
        },
        error: function (xhr, status, error) {
            alert(error.responseText);
        }
    });
}

function viewNoticeDetail(id) {
	if (!id) {
		showMsg('글이 존재하지 않습니다.', 'error');
		return;
	}
	if (!chkSignedIn()) {
		return;
	}

	$.ajax({
        url: '/api/getdatabyjson',
        type: 'POST',
        data: {page: 1, target: 'NTD', idx: id},
        success: function (res) {
            if (res.error) {
				return;
			}

			$('.noticeViewModal .notice-view-section').html(res.data.content);
			$(".noticeViewModal").modal("show");
			$(".noticeModal").modal("hide");
		},
        error: function (xhr, status, error) {
            alert(error.responseText);
        }
	});
}
</script>