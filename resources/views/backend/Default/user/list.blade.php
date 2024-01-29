@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', trans('app.users'))
@section('page-heading', trans('app.users'))

@section('content')

	<section class="content-header">
		@include('backend.Default.partials.messages')
	</section>
	<?php  
			$available_roles = Auth::user()->available_roles( true );
			$available_roles_trans = [];
			foreach ($available_roles as $key=>$role)
			{
				$role = \VanguardLTE\Role::find($key)->description;
				$available_roles_trans[$key] = $role;
			}
	?>


	<section class="content">
			<div class="row">
				<div class="col-lg-4 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-light-blue">
						<div class="inner">
							<h3>{{ number_format($stat['totaluser'], 0) }}</h3>
							<p>전체 회원수</p>
						</div>
						<div class="icon">
							<i class="fa fa-refresh"></i>
						</div>
					</div>
				</div>
				<!-- ./col -->
				<div class="col-lg-4 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-light-blue">
						<div class="inner">
							<h3>{{ number_format($stat['onlineuser'], 0) }}</h3>
							<p>온라인 회원수</p>
						</div>
						<div class="icon">
							<i class="fa fa-level-up"></i>
						</div>
					</div>
				</div>
				<!-- ./col -->
				<div class="col-lg-4 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-light-blue">
						<div class="inner">
							<h3>{{ number_format ($stat['totalbalance'], 0) }}</h3>
							<p>회원 보유금</p>
						</div>
						<div class="icon">
							<i class="fa fa-level-down"></i>
						</div>
					</div>
				</div>
			</div>


		<div class="box box-danger collapsed-box users_show">
			<div class="box-header with-border">
				<h3 class="box-title">@lang('app.filter')</h3>
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
				</div>
			</div>
			<div class="box-body">
			<form action="" method="GET" id="users-form" >
					<div class="col-md-6">
						<div class="form-group">
						<label>이름(아이디)</label>
						<input type="text" class="form-control" name="search" value="{{ Request::get('search') }}" placeholder="@lang('app.search_for_users')">
						</div>
					</div>
					<div class="col-md-6">
					<div class="form-group">
						<label>@lang('app.status')</label>
						{!! Form::select('status', $statuses, Request::get('status'), ['id' => 'status', 'class' => 'form-control']) !!}
					</div>
					</div>
				
					<div class="col-md-6">
					<div class="form-group">
						<label>매장이름</label>
						<input type="text" class="form-control" name="shopname" value="{{ Request::get('shopname') }}" placeholder="">
					</div>
					</div>

					<div class="col-md-6">
					<div class="form-group">
						<label>정렬순</label>
						{!! Form::select('orderby',
							['알파벳','보유금'], Request::get('orderby'), ['class' => 'form-control', 'style' => 'width: 100%;', 'id' => 'orderby']) !!}
					</div>
					</div>
			</div>
			<div class="box-footer">
				<button type="submit" class="btn btn-primary">
					@lang('app.filter')
				</button>
			</div>
			</form>

			</div>

			<div class="box box-primary">
				<div class="box-header with-border">
					{{-- <h3 class="box-title">@lang('app.users')</h3> --}}
					<h3 class="box-title">회원리스트</h3>
					<div class="pull-right box-tools">
						@permission('users.add')
						@if (Auth::user()->hasRole('admin') && !Session::get('isCashier'))
						<a href="{{ route($admurl.'.user.createuserfromcsv') }}" class="btn btn-danger btn-sm" style="margin-right:5px;">csv로 추가</a>
						@endif
						@if (Auth::user()->hasRole('cashier') || Auth::user()->hasRole('manager'))
						<a href="{{ route($admurl.'.user.create') }}" class="btn btn-block btn-primary btn-sm">@lang('app.add')</a>
						@endif
						@endpermission
					</div>
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
						@if($happyhour && auth()->user()->hasRole('cashier'))
							<div class="alert alert-success">
								<h4>@lang('app.happyhours')</h4>
								<p> @lang('app.all_player_deposits') {{ $happyhour->multiplier }}</p>
							</div>
						@endif
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

		var table = $('#users-table').dataTable();
		$("#view").change(function () {
			$("#shops-form").submit();
		});

		$("#filter").detach().appendTo("div.toolbar");


		$("#status").change(function () {
			$("#users-form").submit();
		});
		$("#role").change(function () {
			$("#users-form").submit();
		});
		$("#shop_id").change(function () {
			$("#users-form").submit();
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


		$('.btn-box-tool').click(function(event){
			if( $('.users_show').hasClass('collapsed-box') ){
				$.cookie('users_show', '1');
			} else {
				$.removeCookie('users_show');
			}
		});

		if( $.cookie('users_show') ){
			$('.users_show').removeClass('collapsed-box');
			$('.users_show .btn-box-tool i').removeClass('fa-plus').addClass('fa-minus');
		}
		});

		$('.dealoutPayment').click(function(event){
			if( $(event.target).is('.newPayment') ){
				var id = $(event.target).attr('data-id');
				var value = $(event.target).attr('data-val');
			}else{
				var id = $(event.target).parents('.newPayment').attr('data-id');
				var value = $(event.target).parents('.newPayment').attr('data-val');
			}
			$('#dealOutId').val(id);
			$('#DealOutSum').val(value);
		});

		function convert_deal_balance() {
            var _token = $('#_token').val();
			var _dealsum = $('#DealOutSum').val();
			var _dealid = $('#dealOutId').val();

            $.ajax({
                type: 'POST',
                url: '/api/convert_deal_balance',
                data: { _token: _token, summ: _dealsum, user_id : _dealid },
                cache: false,
                async: false,
                success: function (data) {
                    if (data.error) {
                        alert(data.msg);
                        return;
                    }
                    alert('수익금이 보유금으로 전환되었습니다.');
                    location.reload(true);
                },
                error: function (err, xhr) {
                    alert(err.responseText);
                }
            });
        }
	</script>
@stop