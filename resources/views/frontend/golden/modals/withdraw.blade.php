<!-- ------------------------------------- Withdraw ------------------------------------ -->
<div class="withdrawModal modal fade" tabindex="-1" role="dialog">
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
							<i class="fa-solid fa-vault"></i>
						</div>
						<div class="title-panel">
							<span class="title">출금신청</span>
							<span class="sub">WITHDRAW</span>
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
				<form name="frm_cash_out" onsubmit="reqCashOut(); return false;" data-parsley-validate>
					<input name="withdraw_method" type="hidden" value="BANK">
					<input name="withdraw_classification" type="hidden" value="">
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
								<a href="javascript:void(0);" class="withdraw-link dflex-ac-jc active">
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
								<a href="javascript:void(0);" class="event-link dflex-ac-jc">
									<div class="icon-panel dflex-ac-jc">
										<i class="fa-solid fa-martini-glass-citrus"></i>
									</div>
									<span>이벤트</span>
								</a>
							</li>
						</ul>
					</div>
					<div class="form-container">
						<div class="form-group">
							<div class="labels w-ba">
								<span>은행명</span>
							</div>
							<div class="infos">
								<div class="input-container">
									<div class="select-input form-emphase-disabled">
										<input class="text-violet" readonly type="text" value="{{ Auth::user()->bank_name }}">
										<i class="fas fa-caret-down"></i>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="labels w-ba">
								<span>예금주</span>
							</div>
							<div class="infos">
								<div class="input-container">
									<input class="text-violet form-emphase-disabled" readonly type="text" value="{{ Auth::user()->recommender }}">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="labels w-ba">
								<span>계좌번호</span>
							</div>
							<div class="infos">
								<div class="input-container">
									<input class="text-violet form-emphase-disabled" readonly type="text" value="{{ Auth::user()->account_no }}">
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="labels w-ba">
								<span>보유금액</span>
							</div>
							<div class="infos">
								<div class="input-container">
									<input class="text-violet userCashAmount" type="text" value="{{ number_format(Auth::user()->balance,2) }}" readonly disabled>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="labels w-ba">
								<span>출금금액</span>
							</div>
							<div class="infos">
								<div class="input-container">
									<input id="withdrawal_amount" data-parsley-pattern="/^[0-9,]*$/" data-parsley-pattern-message="유효하지 않은 값입니다." data-parsley-trigger="focusin change" data-parsley-required="true" data-parsley-required-message="필수입력 항목입니다." placeholder="최소출금 30,000원 부터" name="amount" type="text" value="">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="infos">
								<div class="btn-grp" style="float: none">
									<button type="button" class="btn btn-plus" data-value="1">1만</button>
									<button type="button" class="btn btn-plus" data-value="5">5만</button>
									<button type="button" class="btn btn-plus" data-value="10">10만</button>
									<button type="button" class="btn btn-plus" data-value="50">50만</button>
									<button type="button" class="btn btn-plus" data-value="100">100만</button>
									<button type="button" class="btn btn-reset">정정하기</button>
								</div>
							</div>
						</div>
					</div>
					<div class="form-footer dflex-ac-jc">
						<button type="submit" class="btn-yellow">출금하기</button>
						<button type="reset" class="btn-red" data-dismiss="modal">취소</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	var pg_start = 0;
	$(function() {
		$('form[name=frm_cash_out]').on('click', '.btn-plus', function(){
			let $input = $(this).closest('form').find('input[name=amount]');
			let cur = $input.val() || 0;
			let delta = parseCommaInteger(cur) + parseInt($(this).data('value')) * 10000;
			$input.val(formatComma(delta));
			// $input.val(delta);
		});

		$('form[name=frm_cash_out]').on('click', '.btn-reset', function(){
			$(this).closest('form').find('input[name=amount]').val(0);
		})
	})

	function reqCashOut() {
		var formData = new FormData();
		formData.append("_token", $("#_token").val());
		formData.append("money", parseCommaInteger($("#withdrawal_amount").val() || 0));

		$.ajax({
			type: "POST",
			url: "/api/withdraw",
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
				
				showMsg("출금신청 되엇습니다.", success);
				$("#withdrawal_amount").val(0);
			},
			error: function(err, xhr) {}
		});
	}

</script>

<!-- ------------------------------------- Withdraw History ------------------------------------ -->
<div class="withdrawHistModal modal fade" tabindex="-1" role="dialog">
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
							<i class="fa-solid fa-book"></i>
						</div>
						<div class="title-panel">
							<span class="title">입출금내역</span>
							<span class="sub">HISTORY</span>
						</div>
					</div>
				</div>
				<div class="modal-head-panel dflex-ac-jc">
					<div class="btn-grp ml-auto dflex-ac-jc">
						<button class="btn-gold deposit-link">메뉴보기</button>
						<button class="btn-gold mypage-link">마이페이지</button>
					</div>
				</div>
			</div>
			<div class="modal-body">
				<div class="modal-menu dflex-ac-js">
					<ul class="bs-ul w-100 table-layout-fixed">
						<li>
							<a href="javascript:void(0);" class="dflex-ac-jc deposit-hist-link" data-id="DP" data-target=".deposit-list">
								<div class="icon-panel dflex-ac-jc">
									<i class="fa-solid fa-download"></i>
								</div>
								<span>입금</span>
							</a>
						</li>
						<li>
							<a href="javascript:void(0);" class="dflex-ac-jc active">
								<div class="icon-panel dflex-ac-jc">
									<i class="fa-solid fa-upload"></i>
								</div>
								<span>출금</span>
							</a>
						</li>
					</ul>
				</div>
				<div class="withdraw-list">
					<table class="bs-table">
						<colgroup>
							<col width="10%"/>
							<col width="30%"/>
							<col width="15%"/>
						</colgroup>
						<thead>
							<tr>
								<th>번호</th>
								<th>출금금액</th>
								<th>상태</th>
								<th>신청일자</th>
								<th>처리일자</th>
							</tr>
						</thead>
						<tbody>
						
						</tbody>
					</table>
					<div class="tb_empty hide">데이터가 없습니다.</div>
					<div class="paging_box"></div>
				</div>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">

$(function () {
	$('.withdrawHistModal').on('shown.bs.modal', function () {
		getCashOutList();
	});
});

function getCashOutList(pg_start = -1) {
	$.ajax({
        url: '/api/getdatabyjson',
        type: 'POST',
        data: {page: 1, target: 'WD'},
        success: function (res) {
            if (res.error) {
				return;
			}

			let $sel = "";
			let data = res.data;
			for (let i = 0; i < data.length; i ++) {
				let info = data[i];
				let status = '-';
				if (info.status == '0') {
					status = '처리대기';
				} else if (info.status == '1') {
					status = '처리완료';
				} else if (info.status == '2') {
					status = '처리취소';
				}

				$sel +='<tr>\
					<td class="list1">' + (i + 1) + '</th>\
					<td class="list1 font05">' + formatComma(info.sum || '') + '</td>\
					<td class="list1">' + (status || '') + '</td>\
					<td class="list1">' + (new Date(info.created_at).toLocaleString() || '') + '</td>\
					<td class="list1">' + (info.status == '0' ? '' : new Date(info.updated_at).toLocaleString()) + '</td>\
				</tr>';
			}

			if (data.length > 0) {
				$('.withdrawHistModal .withdraw-list > table > tbody').html($sel);
				$('.withdrawHistModal .withdraw-list > .tb_empty').addClass('hidden');
			} else {
				$('.withdrawHistModal .withdraw-list > table > tbody').empty();
				$('.withdrawHistModal .withdraw-list > .tb_empty').removeClass('hidden');
			}
        },
        error: function (xhr, status, error) {
            alert(error.responseText);
        }
    });
}

</script>