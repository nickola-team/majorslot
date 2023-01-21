<!-- ------------------------------------- Event ------------------------------------ -->
<div class="eventModal modal fade" tabindex="-1" role="dialog">
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
							<i class="fa-solid fa-martini-glass-citrus"></i>
						</div>
						<div class="title-panel">
							<span class="title">이벤트</span>
							<span class="sub">EVENT</span>
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
							<a href="javascript:void(0);" class="notice-link dflex-ac-jc">
								<div class="icon-panel dflex-ac-jc">
									<i class="fa-solid fa-bell"></i>
								</div>
								<span>공지사항</span>
							</a>
						</li>
						<li>
							<a href="javascript:void(0);" class="event-link dflex-ac-jc active">
								<div class="icon-panel dflex-ac-jc">
									<i class="fa-solid fa-martini-glass-citrus"></i>
								</div>
								<span>이벤트</span>
							</a>
						</li>
					</ul>
				</div>
				<div class="event-board-section">
					<table class="bs-table event-table">
						<colgroup>
							<col width="10%">
							<col width="50%">
							<col width="40%">
						</colgroup>
						<thead>
						<tr>
							<th>번호</th>
							<th>제목</th>
							<th>이벤트기간</th>
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
<!-- ------------------------------------- Event View ------------------------------------ -->
<div class="eventViewModal modal fade" tabindex="-1" role="dialog">
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
							<i class="fa-solid fa-martini-glass-citrus"></i>
						</div>
						<div class="title-panel">
							<span class="title">이벤트</span>
							<span class="sub">EVENT</span>
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
							<a href="javascript:void(0);" class="notice-link dflex-ac-jc">
								<div class="icon-panel dflex-ac-jc">
									<i class="fa-solid fa-bell"></i>
								</div>
								<span>공지사항</span>
							</a>
						</li>
						<li>
							<a href="javascript:void(0);" class="event-link dflex-ac-jc active">
								<div class="icon-panel dflex-ac-jc">
									<i class="fa-solid fa-martini-glass-citrus"></i>
								</div>
								<span>이벤트</span>
							</a>
						</li>
					</ul>
				</div>
				<div class="event-view-section">
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
							<td colspan="3">데이터를 가져오는 중입니다.</td>
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
	$('.eventModal').on('shown.bs.modal', function () {
		let tmp_start = $(this).data('tmp_start') || -1;
		let tmp_active_id = $(this).data('tmp_active_id') || 0;
		$(this).data('tmp_start', undefined);
		$(this).data('tmp_active_id', undefined);

		getEventList(tmp_start, tmp_active_id);
	});
	$('.eventViewModal').on('shown.bs.modal', function () {
		viewEventDetail($(this).data('id'));
	});
});

function setEventTmpParam(tmp_start, tmp_active_id) {
	$('.eventModal').data('tmp_start', tmp_start);
	$('.eventModal').data('tmp_active_id', tmp_active_id);
}

function getEventList(pg_start = -1, active_id = 0) {
	if (pg_start < 0) {
		pg_start = this.start || 0;
	}
	this.length = 10;
	this.start = Math.floor(pg_start / this.length) * this.length;

	ajaxSend('/front/header/event/list.json', {
		start: this.start, 
		length: this.length
	}, function(jData) {
		if (jData.success) {
			let data = jData.data;
			// console.log(jData);
			let $sel = "";
			for(let i = 0; i < data.list.length; i ++) {
				let info = data.list[i];
				$sel +='<tr class="eventview-link ' + (info.id == active_id ? 'active' : '') + '" data-id=' + info.id + '>\
					<td class="count-td"><span class="count-tag">' + (data.total - data.start - i) + '</span></td>\
					<td class="text-left title-td cursor-pointer" >\
						<a href="javascript:void(0);">&lt;' + (info.title || '') + '&gt;</a>\
					</td>\
					<td class="text-center date-td">' + (info.begDate || '') + ' - ' + (info.endDate || '') + '</td>\
				</tr>';

				if (data.total > 0) {
					$('.eventModal .event-board-section > table > tbody').html($sel);
					$('.eventModal .event-board-section > .tb_empty').addClass('hidden');
				} else {
					$('.eventModal .event-board-section > table > tbody').empty();
					$('.eventModal .event-board-section > .tb_empty').removeClass('hidden');
				}

				$('.eventModal .event-board-section > .paging_box').twbsPagination({
					start: data.start,
					length: data.length,
					total: data.total,
					onPageClick: function (event, start) {
						getEventList(start);
					}
				});
			}
		}
	});
}

function viewEventDetail(id) {
	if (!id) {
		showMsg('글이 존재하지 않습니다.', 'error');
		return;
	}
	if (!chkSignedIn()) {
		return;
	}

	ajaxGetSend('/front/header/event.details', {
		id: id
	}, function(data) {
		if (data.success === undefined) {
			$('.eventViewModal .event-view-section').html(data);
			$(".eventViewModal").modal("show");
			$(".eventModal").modal("hide");
		} else if (!data.success) {
			if (data.message) confirmMsgOnly(data.message);
		}
	});
}
</script>