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
							@if (count($users))
								@foreach ($users as $user)
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
						<button type="submit" class="btn btn-primary" id="btnAddSum">확인</button>
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
						<button type="submit" class="btn btn-primary" id="btnOutSum">확인</button>
					</div>
				</form>
			</div>
		</div>
	</div>

@stop

@section('scripts')
	<script>

		$(function() {

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
		});
	</script>
@stop