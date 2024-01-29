@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '가입신청관리')
@section('page-heading', '가입신청관리')

@section('content')
<?php  
			$available_roles = Auth::user()->available_roles( true );
			$available_roles_trans = [];
			foreach ($available_roles as $key=>$role)
			{
				$role = \VanguardLTE\Role::find($key)->description;
				$available_roles_trans[$key] = $role;
			}
	?>

	<section class="content-header">
		@include('backend.Default.partials.messages')
	</section>

	<section class="content">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">가입신청리스트</h3>
			</div>
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>이름(아이디)</th>
							<?php
								for ($r=3;$r<auth()->user()->role_id;$r++)
								{
									echo '<th>'.$available_roles_trans[$r].'</th>';
								}
							?>
							<th>폰번호</th>
							<th>계좌번호</th>
							<th>예금주</th>
							<th>신청시간</th>
							<th>승인</th>
							<th>취소</th>
						</tr>
						</thead>
						<tbody>
						@if (isset($joinusers) && count($joinusers))
							@foreach ($joinusers as $user)
								@include('backend.Default.user.partials.row_join')
							@endforeach
						@else
							<tr><td colspan="12">@lang('app.no_data')</td></tr>
						@endif
						</tbody>
						<thead>
						<tr>
							<th>이름(아이디)</th>
							<?php
								for ($r=3;$r<auth()->user()->role_id;$r++)
								{
									echo '<th>'.$available_roles_trans[$r].'</th>';
								}
							?>
							<th>폰번호</th>
							<th>계좌번호</th>
							<th>예금주</th>
							<th>신청시간</th>
							<th>승인</th>
							<th>취소</th>
						</tr>
						</thead>
					</table>
				</div>
			</div>				
		</div>
			
	</section>

	<section class="content">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">VIP회원리스트</h3>
			</div>
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>이름(아이디)</th>
							<?php
								for ($r=3;$r<auth()->user()->role_id;$r++)
								{
									echo '<th>'.$available_roles_trans[$r].'</th>';
								}
							?>
							@permission('users.balance.manage')
							<th>@lang('app.balance')</th>
							<th>딜비수익</th>
							<th>딜비</th>
							<th>라이브딜비</th>
							<th>@lang('app.total_in')</th>
							<th>@lang('app.total_out')</th>
							<th>전화번호</th>
							<th>@lang('app.status')</th>
							<th>@lang('app.pay_in')</th>
							<th>@lang('app.pay_out')</th>
							<th>딜비전환</th>
							@if (auth()->user()->isInoutPartner())
							<th>쪽지</th>
							@endif
							@endpermission

						</tr>
						</thead>
						<tbody>
						@if (count($users))
							@foreach ($users as $user)
								@include('backend.Default.user.partials.row')
							@endforeach
						@else
							<tr><td colspan="12">@lang('app.no_data')</td></tr>
						@endif
						</tbody>
						<thead>
						<tr>
							<th>이름(아이디)</th>
							<?php
								for ($r=3;$r<auth()->user()->role_id;$r++)
								{
									echo '<th>'.$available_roles_trans[$r].'</th>';
								}
							?>
							<th>@lang('app.balance')</th>
							<th>딜비수익</th>
							<th>딜비</th>
							<th>라이브딜비</th>
							<th>@lang('app.total_in')</th>
							<th>@lang('app.total_out')</th>
							<th>전화번호</th>
							<th>@lang('app.status')</th>
							<th>@lang('app.pay_in')</th>
							<th>@lang('app.pay_out')</th>
							<th>딜비전환</th>
							@if (auth()->user()->isInoutPartner())
							<th>쪽지</th>
							@endif
						</tr>
						</thead>
					</table>
				</div>
				{{ $users->appends(Request::except('page'))->links() }}
			</div>				
		</div>
			
	</section>

	<div class="modal fade" id="openAllowModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="{{ route($admurl.'.user.processjoin') }}" method="POST" id="addForm">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">심사</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="OutSum">승인하시겠습니까</label>
							<input type="hidden" id="user_id" name="user_id">
							<input type="hidden" id="type" name="type" value="allow">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">취소</button>
						<button type="submit" class="btn btn-primary" id="btnAllow">확인</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="openRejectModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="{{ route($admurl.'.user.processjoin') }}" method="POST" id="outForm">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">심사</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="OutSum">취소하시겠습니까</label>
							<input type="hidden" id="user_id" name="user_id">
							<input type="hidden" id="type" name="type" value="reject">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">취소</button>
						<button type="submit" class="btn btn-primary" id="btnReject">확인</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="openDealOutModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="#" method="GET" id="dealoutForm">
				<input type="hidden" id="dealOutId" name="user_id">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">정산</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="OutSum">딜비전환</label>
							<input type="text" class="form-control" id="DealOutSum" name="DealOutSum" placeholder="전환금액"   required>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">@lang('app.close')</button>
						<button type="button" class="btn btn-primary" onclick="convert_deal_balance();">확인</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="openAddModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="{{ route($admurl.'.user.balance.update') }}" method="POST"  id="addForm">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">@lang('app.balance') @lang('app.pay_in')</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="AddSum">충전금액</label>
							<input type="text" class="form-control" id="AddSum" name="summ" placeholder="충전금액" required autofocus>
							<input type="hidden" name="type" value="add">
							<input type="hidden" id="AddId" name="user_id">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<br>
							<button type="button" class="btn btn-default changeAddSum" data-value="10000">10000</button>
							<button type="button" class="btn btn-default changeAddSum" data-value="20000">20000</button>
							<button type="button" class="btn btn-default changeAddSum" data-value="30000">30000</button>
							<button type="button" class="btn btn-default changeAddSum" data-value="50000">50000</button>
							<button type="button" class="btn btn-default changeAddSum" data-value="100000">100000</button>
							<button type="button" class="btn btn-default changeAddSum" data-value="200000">200000</button>
							<button type="button" class="btn btn-default changeAddSum" data-value="300000">300000</button>
							<button type="button" class="btn btn-primary changeAddSum" data-value="0">초기화</button>
							@if (auth()->user()->hasRole('manager'))
							@else
							<p></p>
							<label for="OutSum">충전사유</label>
							<input type="text" class="form-control" id="reason" name="reason" placeholder="충전사유를 입력해주세요">
							@endif
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">@lang('app.close')</button>
						<button type="submit" id='btnAddSum' class="btn btn-primary">확인</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="openOutModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="{{ route($admurl.'.user.balance.update') }}" method="POST" id="outForm">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">@lang('app.balance') @lang('app.pay_out')</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="OutSum">환전금액</label>
							<input type="text" class="form-control" id="OutSum" name="summ" placeholder="환전금액"  required autofocus>
							<input type="hidden" name="type" value="out">
							<input type="hidden" id="outAll" name="all" value="0">
							<input type="hidden" id="OutId" name="user_id">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<br>
							<button type="button" class="btn btn-default changeOutSum" data-value="10000">10000</button>
							<button type="button" class="btn btn-default changeOutSum" data-value="20000">20000</button>
							<button type="button" class="btn btn-default changeOutSum" data-value="30000">30000</button>
							<button type="button" class="btn btn-default changeOutSum" data-value="50000">50000</button>
							<button type="button" class="btn btn-default changeOutSum" data-value="100000">100000</button>
							<button type="button" class="btn btn-default changeOutSum" data-value="200000">200000</button>
							<button type="button" class="btn btn-default changeOutSum" data-value="300000">300000</button>
							<button type="button" class="btn btn-primary changeOutSum" data-value="0">초기화</button>
							@if (auth()->user()->hasRole('manager'))
							@else
							<p></p>
							<label for="OutSum">환전사유</label>
							<input type="text" class="form-control" id="reason" name="reason" placeholder="환전사유를 입력해주세요">
							@endif
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">@lang('app.close')</button>
						<button type="button" class="btn btn-danger" id="doOutAll">@lang('app.all') @lang('app.pay_out')</button>
						<button type="submit" class="btn btn-primary" id='btnOutSum'>확인</button>
					</div>
				</form>
			</div>
		</div>
	</div>

@stop

@section('scripts')
	<script>

		$(function() {

			$('input[type="checkbox"].flat-green, input[type="radio"].flat-green').iCheck({
				checkboxClass: 'icheckbox_flat-green',
				radioClass   : 'iradio_flat-green'
			});
			$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
				checkboxClass: 'icheckbox_flat-red',
				radioClass   : 'iradio_flat-red'
			})
			$('.allowJoin').click(function(event){
				if( $(event.target).is('.newPayment') ){
					var id = $(event.target).attr('data-id');
				}else{
					var id = $(event.target).parents('.newPayment').attr('data-id');
				}
				$('#openAllowModal #user_id').val(id);

			});

			$('.rejectJoin').click(function(event){
				if( $(event.target).is('.newPayment') ){
					var id = $(event.target).attr('data-id');
				}else{
					var id = $(event.target).parents('.newPayment').attr('data-id');
				}
				$('#openRejectModal #user_id').val(id);
			});

			$('.addPayment').click(function(event){
				if( $(event.target).is('.newPayment') ){
					var id = $(event.target).attr('data-id');
				}else{
					var id = $(event.target).parents('.newPayment').attr('data-id');
				}
				$('#AddId').val(id);

			});
			$('#btnAddSum').click(function() {
				$(this).attr('disabled', 'disabled');
				$('form#addForm').submit();
			});
			$('#btnOutSum').click(function() {
				$(this).attr('disabled', 'disabled');
				$('form#outForm').submit();
			});

			$('.changeAddSum').click(function(event){
				$v = Number($('#AddSum').val());
				if ($(event.target).data('value') == 0)
				{
					$('#AddSum').val(0);
				}
				else
				{
					$('#AddSum').val($v + $(event.target).data('value'));
				}
			});

			$('.changeOutSum').click(function(event){
				$v = Number($('#OutSum').val());
				if ($(event.target).data('value') == 0)
				{
					$('#OutSum').val(0);
				}
				else
				{
					$('#OutSum').val($v + $(event.target).data('value'));
				}
			});

			$('.outPayment').click(function(event){
				if( $(event.target).is('.newPayment') ){
					var id = $(event.target).attr('data-id');
				}else{
					var id = $(event.target).parents('.newPayment').attr('data-id');
				}
				$('#OutId').val(id);
				$('#outAll').val('');
			});

			$('#doOutAll').click(function () {
				$(this).attr('disabled', 'disabled');
				$('#outAll').val('1');
				$('form#outForm').submit();
			});
		});
	</script>
@stop