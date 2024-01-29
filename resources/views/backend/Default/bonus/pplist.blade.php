@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '프라그메틱 보너스')
@section('page-heading', '프라그메틱 보너스')

@section('content')

	<section class="content-header">
		@include('backend.Default.partials.messages')
	</section>

	<section class="content">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">프리스핀 보너스</h3>
                <div class="pull-right box-tools">
                    <a href="{{ route($admurl.'.bonus.ppadd') }}" class="btn btn-block btn-primary btn-sm">@lang('app.add')</a>
                </div>
			</div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>아이디</th>
                            <th>회원이름</th>
                            <th>보너스코드</th>
                            <th>게임이름</th>
                            <th>프리스핀갯수</th>
							<th>지급된 프리스핀수</th>
                            <th>시작시간</th>
                            <th>만료시간</th>
							<th>상태</th>
                            <th>====</th>
                        </tr>
                        </thead>
                        <tbody>
							@forelse ($frbs as $bonus)
								<tr>
								<td>{{ $loop->index + 1 }} </td>
								<td>{{ $bonus['username'] }} </td>
								<td>{{ $bonus['bonusCode'] }} </td>
								<td>{{ $bonus['gamename'] }} </td>
								<td>{{ $bonus['rounds'] }} </td>
								<td>{{ $bonus['roundsPlayed'] }} </td>
								<td>{{ $bonus['startDate'] }} </td>
								<td>{{ date(config('app.date_time_format'),intval($bonus['expirationDate'])) }} </td>
								<td>{{ $bonus['status']?'활성':'비활성' }} </td>
								<td>
								<a href="{{ route($admurl.'.bonus.ppcancel', $bonus['bonusCode']) }}"
									class="btn btn-danger btn-block"
									data-method="DELETE"
									data-confirm-title="보너스취소"
									data-confirm-text="정말 취소하시겠습니까?"
									data-confirm-delete="확인">
									취소
								</a>
								</a>
								</td>
								</tr>
							@empty
                            <tr><td colspan="9">@lang('app.no_data')</td></tr>
							@endforelse
                        </tbody>
                        <thead>
                        <tr>
                            <th>아이디</th>
                            <th>회원이름</th>
                            <th>보너스코드</th>
                            <th>게임이름</th>
                            <th>프리스핀갯수</th>
							<th>지급된 프리스핀</th>
                            <th>시작시간</th>
                            <th>만료시간</th>
							<th>상태</th>
                            <th>====</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
		</div>

	</section>

	<!-- Modal -->
	<div class="modal fade" id="openAddModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form action="{{ route($admurl.'.jpgame.balance') }}" method="POST">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">@lang('app.balance') @lang('app.pay_in')</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="AddSum">@lang('app.sum')</label>
							<input type="text" class="form-control" id="AddSum" name="summ" placeholder="@lang('app.sum')" required>
							<input type="hidden" name="type" value="add">
							<input type="hidden" id="AddId" name="jackpot_id">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('app.close')</button>
						<button type="submit" class="btn btn-primary">@lang('app.pay_in')</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="openOutModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form action="{{ route($admurl.'.jpgame.balance') }}" method="POST" id="outForm">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">@lang('app.balance') @lang('app.pay_out')</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="OutSum">@lang('app.sum')</label>
							<input type="text" class="form-control" id="OutSum" name="summ" placeholder="@lang('app.sum')" required>
							<input type="hidden" id="outAll" name="all" value="0">
							<input type="hidden" name="type" value="out">
							<input type="hidden" id="OutId" name="jackpot_id">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('app.close')</button>
						<button type="button" class="btn btn-danger" id="doOutAll">@lang('app.pay_out') @lang('app.all')</button>
						<button type="submit" class="btn btn-primary">@lang('app.pay_out')</button>
					</div>
				</form>
			</div>
		</div>
	</div>

@stop

@section('scripts')
	<script>
		$('#jackpots-table').dataTable();
		$("#status").change(function () {
			$("#users-form").submit();
		});
		$('.addPayment').click(function(event){
			console.log($(event.target));
			var item = $(event.target).hasClass('addPayment') ? $(event.target) : $(event.target).parent();
			var id = item.attr('data-id');
			$('#AddId').val(id);
			$('#outAll').val('0');
		});


		$('#doOutAll').click(function () {
			$('#outAll').val('1');
			$('form#outForm').submit();
		});

		$('.outPayment').click(function(event){
			console.log($(event.target));
			var item = $(event.target).hasClass('outPayment') ? $(event.target) : $(event.target).parent();
			console.log(item);
			var id = item.attr('data-id');
			$('#OutId').val(id);
		});

		$("#shop_id").change(function () {
			$("#change-shop-form").submit();
		});
	</script>
@stop