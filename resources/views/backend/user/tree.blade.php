@extends('backend.layouts.app')

@section('page-title', '파트너관리')
@section('page-heading', '파트너관리')

@section('content')

    <section class="content-header">
        @include('backend.partials.messages')
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                {{-- <h3 class="box-title">{{ $role->name }} @lang('app.tree')</h3> --}}
                <h3 class="box-title">파트너관리</h3>
                <div class="pull-right box-tools">
                    @permission('users.add')
                    @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('agent') || Auth::user()->hasRole('distributor'))
                    <a href="{{ route('backend.user.create') }}" class="btn btn-block btn-primary btn-sm">@lang('app.add')</a>
                    @endif
                    @endpermission
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            @if( auth()->user()->hasRole(['admin','agent']) )
                                <th>@lang('app.agent')</th>
                                <th>보유금/수익금/딜비</th>
                            @endif
                            <th>@lang('app.distributor')</th>
                            <th>보유금/수익금/딜비</th>
                            <th>매장이름</th>
                            <th>보유금/수익금/딜비</th>
                            <th>매장관리자</th>
                            {{-- <th>@lang('app.cashier')</th> --}}
                            <th>입금</th>
                            <th>출금</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($users))
                            @foreach ($users as $user)
                                <tr>
                                @if($user->hasRole('agent'))
                                    <td rowspan="{{ $user->getRowspan() }}" style="vertical-align : middle;">
                                    @if( auth()->user()->hasRole('admin') )
                                        <a href="{{ route('backend.user.edit', $user->id) }}">
                                            {{ $user->username ?: trans('app.n_a') }}
                                        </a>
                                    @else
                                        {{ $user->username ?: trans('app.n_a') }}
                                    @endif
                                    </td>
                                    <td rowspan="{{ $user->getRowspan() }}" style="vertical-align : middle;">
                                    {{ number_format($user->balance,2)}}원 / {{ number_format($user->deal_balance - $user->mileage,2)}}원 / {{ $user->deal_percent}}%
                                    </td>

                                    @if( $distributors = $user->getInnerUsers() )
                                        @foreach($distributors AS $distributor)
                                            @include('backend.user.partials.distributor', ['agentCount' => $loop->index, 'agentRowSpan' => $user->getRowspan(), 'agentID' => $user->id ])
                                        @endforeach
                                    @else
                                        <td colspan="5"></td>
                                        @if( auth()->user()->hasRole('admin') )
                                            <td rowspan="{{ $distributor->getRowspan() }}" style="vertical-align : middle;">
                                            <a class="newPayment addPayment" href="#" data-toggle="modal" data-target="#openAddModal" data-id="{{ $user->id }}" >
                                            <button type="button" class="btn btn-block btn-success btn-xs">@lang('app.in')</button>
                                            </a>
                                            </td>
                                            <td rowspan="{{ $distributor->getRowspan() }}" style="vertical-align : middle;">
                                            <a class="newPayment outPayment" href="#" data-toggle="modal" data-target="#openOutModal" data-id="{{ $user->id }}" >
                                            <button type="button" class="btn btn-block btn-danger btn-xs">@lang('app.out')</button>
                                            </a>
                                            </td>
                                        @endif
                                        </tr><tr></tr><tr>
                                    @endif
                                @endif
                                @if($user->hasRole('distributor'))
                                    @include('backend.user.partials.distributor', ['distributor' => $user])
                                @endif
                                </tr>
                            @endforeach
                        @else
                            <tr><td colspan="4">@lang('app.no_data')</td></tr>
                        @endif
                        </tbody>
                        <thead>
                            <tr>
                                @if( auth()->user()->hasRole(['admin','agent']) )
                                    <th>@lang('app.agent')</th>
                                    <th>보유금/수익금/딜비</th>
                                @endif
                                <th>@lang('app.distributor')</th>
                                <th>보유금/수익금/딜비</th>
                                <th>매장이름</th>
                                <th>보유금/수익금/딜비</th>
                                <th>매장관리자</th>
                                {{-- <th>@lang('app.cashier')</th> --}}
                                <th>입금</th>
                                <th>출금</th>
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
				<form action="{{ route('backend.user.balance.update') }}" method="POST">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">@lang('app.balance') @lang('app.pay_in')</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="OutSum">입금금액</label>
							<input type="text" class="form-control" id="OutSum" name="summ" placeholder="입금금액" required autofocus>
							<input type="hidden" name="type" value="add">
							<input type="hidden" id="AddId" name="user_id">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">@lang('app.close')</button>
						<button type="submit" class="btn btn-primary">확인</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="openOutModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="{{ route('backend.user.balance.update') }}" method="POST" id="outForm">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">@lang('app.balance') @lang('app.pay_out')</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="OutSum">출금금액</label>
							<input type="text" class="form-control" id="OutSum" name="summ" placeholder="출금금액"  required autofocus>
							<input type="hidden" name="type" value="out">
							<input type="hidden" id="outAll" name="all" value="0">
							<input type="hidden" id="OutId" name="user_id">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">@lang('app.close')</button>
						<button type="button" class="btn btn-danger" id="doOutAll">@lang('app.all') @lang('app.pay_out')</button>
						<button type="submit" class="btn btn-primary">확인</button>
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
			$('#outAll').val('1');
			$('form#outForm').submit();
		});


		});
	</script>
@stop