@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '파트너생성')
@section('page-heading', '파트너생성')

@section('content')

    <section class="content-header">
        @include('backend.Default.partials.messages')
    </section>

    <section class="content">
	<div class="box box-danger users_show">
			<div class="box-header with-border">
			{{--<h3 class="box-title">@lang('app.filter')</h3>
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
				</div>--}}
			</div> 
			<div class="box-body">
				<form action="" method="GET" id="users-form" >
					<div class="col-md-6">
						<div class="form-group">
						<label>파트너이름</label>
						<input type="text" class="form-control" name="search" value="{{ Request::get('search') }}" placeholder="이름">
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
			<?php  
				$available_roles = \jeremykenedy\LaravelRoles\Models\Role::orderby('id')->pluck('name', 'id');
				$available_roles_trans = [];
				foreach ($available_roles as $key=>$role)
				{
					$role = \VanguardLTE\Role::find($key)->description;
					$available_roles_trans[$key] = $role;
				}
			?>
                <h3 class="box-title">파트너목록</h3>
                @if($user != null && !$user->hasRole('admin'))
					<a href="{{ route($admurl.'.user.tree', $user->id==auth()->user()->id?'':'parent='.$user->parent_id) }}">
						{{$user->username}}
						[ {{$available_roles_trans[$user->role_id]}}]
					</a>
				@endif
                <div class="pull-right box-tools" style="display:flex;align-item:center;">
					
                    @permission('users.add')
					@if (Auth::user()->hasRole('admin'))
                    <a href="{{ route($admurl.'.user.createpartnerfromcsv') }}" class="btn btn-danger btn-sm" style="margin-right:5px;">csv로 추가</a>
                    @endif
                    @if (Auth::user()->hasRole(['admin','comaster','master', 'agent','distributor']))
                    <a href="{{ route($admurl.'.user.create') }}" class="btn btn-primary btn-sm">@lang('app.add')</a>
                    @endif
                    @endpermission
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>이름</th>
                            <th>등급</th>
                            @if ( ($user!=null && $user->hasRole('distributor')) )
                            <th>매장이름</th>
                            @endif
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
							@endif
                            <th>편집</th>
                            <th>충전</th>
                            <th>환전</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($partners))
                            @foreach ($partners as $partner)
								@if ($partner['role_id'] > 2)
                                <tr>
                                    @include('backend.Default.user.partials.row_tree', ['user' => $partner])
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
                                <th>등급</th>
                                @if ( ($user!=null && $user->hasRole('distributor')) )
                                <th>매장이름</th>
                                @endif
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
								@endif
                                <th>편집</th>
                                <th>충전</th>
                                <th>환전</th>
                            </tr>
                        </thead>
                    </table>
                </div>
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
							<p></p>
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


@stop

@section('scripts')
	<script>

		$(function() {

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