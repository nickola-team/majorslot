@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '파트너관리')
@section('page-heading', '파트너관리')
<?php  
			$available_roles = Auth::user()->available_roles( true );
			$available_roles_trans = [];
			foreach ($available_roles as $key=>$role)
			{
				$role = \VanguardLTE\Role::find($key)->description;
				$available_roles_trans[$key] = $role;
			}
?>
@section('content')

    <section class="content-header">
        @include('backend.Default.partials.messages')
    </section>

    <section class="content">
		<div class="row">
			<div class="col-lg-6 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-light-blue">
					<div class="inner">
						<h3>{{ $stat['count'] }}</h3>
						<p>전체 {{$available_roles_trans[$role_id]}}수</p>
					</div>
					<div class="icon">
						<i class="fa fa-refresh"></i>
					</div>
				</div>
			</div>
			
			<div class="col-lg-6 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-red">
					<div class="inner">
						<h3>{{ number_format($stat['sum']) }}</h3>
						<p>@lang('app.total_credit')</p>
					</div>
					<div class="icon">
						<i class="fa fa-line-chart"></i>
					</div>
				</div>
			</div>
			<!-- ./col -->

		</div>
	<form action="" method="GET" id="users-form" >

	<div class="box box-danger collapsed-box users_show">

			<div class="box-header with-border">
				<h3 class="box-title">@lang('app.filter')</h3>
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
				</div>
			</div>
			<div class="box-body">
					<div class="col-md-6">
						<div class="form-group">
						<label>파트너이름</label>
						<input type="text" class="form-control" name="search" value="{{ Request::get('search') }}" placeholder="이름">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>최소보유금액</label>
							<input type="text" class="form-control" name="credit_from" value="{{ Request::get('credit_from') }}">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>최대보유금액</label>
							<input type="text" class="form-control" name="credit_to" value="{{ Request::get('credit_to') }}">
						</div>
					</div>
			</div>
			<div class="box-footer">
				<button type="submit" class="btn btn-primary">
					@lang('app.filter')
				</button>
			</div>

			</div>
		</form>

        <div class="box box-primary">
            <div class="box-header with-border">
                {{-- <h3 class="box-title">{{ $role->name }} @lang('app.tree')</h3> --}}
                <h3 class="box-title">{{$available_roles_trans[$role_id]}}목록</h3>
                <div class="pull-right box-tools" style="display:flex;align-item:center;">
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>이름</th>
							<?php
                            	for ($r=$role_id+1;$r<auth()->user()->role_id;$r++)
								{
									echo '<th>'.$available_roles_trans[$r].'</th>';
								}
							?>
                            <th>보유금</th>
                            <th>딜비수익</th>
							@if (auth()->user()->hasRole('admin')  || auth()->user()->ggr_percent > 0 || (auth()->user()->hasRole('manager') && auth()->user()->shop->ggr_percent > 0))
							<th>죽장수익</th>
							@endif
                            <th>딜비</th>
							<th>라이브딜비</th>
							@if (auth()->user()->hasRole('admin')  || auth()->user()->ggr_percent > 0 || (auth()->user()->hasRole('manager') && auth()->user()->shop->ggr_percent > 0))
							<th>죽장퍼센트</th>
							<th>정산기간</th>
							<th>다음정산시간</th>
							@endif
							@if ($role_id==7)
							<th>머니퍼센트</th>
							@endif
							@if ($role_id==6)
							<th>수익금리셋</th>
							@endif
                            <th>충전</th>
                            <th>환전</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($partners))
                            @foreach ($partners as $partner)
								@if ($partner['role_id'] > 2)
                                <tr>
                                    @include('backend.Default.user.partials.row_partner', ['user' => $partner])
                                </tr>
								@endif
                            @endforeach
                        @else
                            <tr><td colspan="8">@lang('app.no_data')</td></tr>
                        @endif
                        </tbody>
                        <thead>
                            <tr>
                                <th>이름</th>
                                <?php
                            	for ($r=$role_id+1;$r<auth()->user()->role_id;$r++)
								{
									echo '<th>'.$available_roles_trans[$r].'</th>';
								}
								?>
                                <th>보유금</th>
								<th>딜비수익</th>
								@if (auth()->user()->hasRole('admin')  || auth()->user()->ggr_percent > 0 || (auth()->user()->hasRole('manager') && auth()->user()->shop->ggr_percent > 0))
								<th>죽장수익</th>
								@endif
								<th>딜비</th>
								<th>라이브딜비</th>
								@if (auth()->user()->hasRole('admin')  || auth()->user()->ggr_percent > 0 || (auth()->user()->hasRole('manager') && auth()->user()->shop->ggr_percent > 0))
								<th>죽장퍼센트</th>
								<th>정산기간</th>
								<th>다음정산시간</th>
								@endif
								@if ($role_id==7)
								<th>머니퍼센트</th>
								@endif
								@if ($role_id==6)
								<th>수익금리셋</th>
								@endif
                                <th>충전</th>
                                <th>환전</th>
                            </tr>
                        </thead>
                    </table>
                </div>
				{{ $partners->appends(Request::except('page'))->links() }}
            </div>
        </div>
    </section>

    <div class="modal fade" id="openAddModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="{{ route($admurl.'.user.balance.update') }}" method="POST" id="addForm">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">@lang('app.balance') @lang('app.pay_in')</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="OutSum">충전금액</label>
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
							<p></p>
							<label for="OutSum">충전사유</label>
							<input type="text" class="form-control" id="reason" name="reason" placeholder="충전사유를 입력해주세요">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">@lang('app.close')</button>
						<button type="submit" class="btn btn-primary" id="btnAddSum">확인</button>
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
							<p></p>
							<label for="OutSum">환전사유</label>
							<input type="text" class="form-control" id="reason" name="reason" placeholder="환전사유를 입력해주세요">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">@lang('app.close')</button>
						<button type="button" class="btn btn-danger" id="doOutAll">@lang('app.all') @lang('app.pay_out')</button>
						<button type="submit" class="btn btn-primary" id="btnOutSum">확인</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="openResetModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="{{ route($admurl.'.user.partner.reset_ggr') }}" method="POST" id="resetForm">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">리셋</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="OutSum">리셋하시겠습니까</label>
							<input type="hidden" id="masterid" name="masterid">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">아니오</button>
						<button type="submit" class="btn btn-primary" id="btnAddSum">네</button>
					</div>
				</form>
			</div>
		</div>
	</div>


@stop

@section('scripts')
	<script>

		$(function() {


		$('.allowReset').click(function(event){
			if( $(event.target).is('.newReset') ){
				var id = $(event.target).attr('data-id');
			}else{
				var id = $(event.target).parents('.newReset').attr('data-id');
			}
			$('#masterid').val(id);
		});
		$('.addPayment').click(function(event){
			if( $(event.target).is('.newPayment') ){
				var id = $(event.target).attr('data-id');
			}else{
				var id = $(event.target).parents('.newPayment').attr('data-id');
			}
			$('#AddId').val(id);
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

		$('#btnAddSum').click(function() {
			$(this).attr('disabled', 'disabled');
			$('form#addForm').submit();
		});
		$('#btnOutSum').click(function() {
			$(this).attr('disabled', 'disabled');
			$('form#outForm').submit();
		});

		$('#doOutAll').click(function () {
			$(this).attr('disabled', 'disabled');
			$('#outAll').val('1');
			$('form#outForm').submit();
		});
		});
	</script>
@stop