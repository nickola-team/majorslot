@extends('backend.layouts.app')

@section('page-title', '충환전신청')
@section('page-heading', '충환전신청')

@section('content')

	<section class="content-header">
		@include('backend.partials.messages')
	</section>

	<section class="content">
		
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">충환전신청</h3>
			</div>

			<input type="hidden" value="<?= csrf_token() ?>" name="_token" id="_token">

			{{-- <li class="list-group-item">
				<div class="row">
					<div class="col-md-3">
						<b>본사은행계좌:</b> <a class="pull-right"></a>
					</div>
				</div>
			</li> --}}

			<li class="list-group-item">
				<div class="row">
					<div class="col-md-3">
						<b>은행계좌:</b> 
							@php
								$banks = array_combine(\VanguardLTE\User::$values['banks'], \VanguardLTE\User::$values['banks']);
							@endphp
							{!! Form::select('bank_name', $banks, auth()->user()->bank_name ? auth()->user()->bank_name : '', ['class' => 'form-control', 'id' => 'bank_name']) !!}		
					</div>
				</div>
			</li>

			<li class="list-group-item">
				<div class="row">
					<div class="col-md-3">
						<b>계좌번호:</b> 
						<input type="text" class="form-control" id="account_no" name="account_no" value="{{ auth()->user()->account_no ? auth()->user()->account_no : '' }}">
					</div>
				</div>
			</li>

			<li class="list-group-item">
				<div class="row">
					<div class="col-md-3">
						<b>예금주:</b> 
						<input type="text" class="form-control" id="recommender" name="recommender" value="{{ auth()->user()->recommender ? auth()->user()->recommender : '' }}">
					</div>
				</div>
			</li>

			<div class="box-footer">
				<button class="btn btn-primary" id="change-bank-account-btn" onclick="change_bank_account_info();">
					계좌정보변경
				</button>
			</div>


			@if(auth()->user()->hasRole('manager'))
			<li class="list-group-item">
				<div class="row">
					<div class="col-md-3">
						<b>@lang('app.balance'):</b> <a class="pull-right">{{ auth()->user()->shop->balance }}</a>
					</div>
				</div>
			</li>
			@else
			<li class="list-group-item">
				<div class="row">
					<div class="col-md-3">
						<b>보유금:</b> <a class="pull-right">{{ auth()->user()->balance }}원</a>
					</div>
				</div>
			</li>
			@endif
			@if(auth()->user()->hasRole('agent') || auth()->user()->hasRole('distributor'))
			<li class="list-group-item">
				<div class="row">
					<div class="col-md-3">
						<b>수익금:</b> <a class="pull-right">{{ auth()->user()->deal_balance - auth()->user()->mileage }}원</a>
					</div>
				</div>
			</li>
			@endif

			@if(auth()->user()->hasRole('manager'))
			<li class="list-group-item">
				<div class="row">
					<div class="col-md-3">
						<b>수익금:</b> <a class="pull-right">{{ auth()->user()->shop->deal_balance }}원</a>
					</div>
				</div>
			</li>
			@endif


			{{-- @if(auth()->user()->hasRole('agent'))
				<div class="list-group-item">
					<label>@lang('app.bank_account')</label>
					<input type="text" class="form-control" id="bank_account" name="bank_account" value= "{{ $bank_account }}">
				</div>
				<div class="box-footer">
					<button class="btn btn-primary" id="withdraw-balance-btn" onclick="change_bank_account();">
						@lang('app.change_bank_account')
					</button>
				</div>
			@elseif($user->hasRole('distributor') || $user->hasRole('manager'))
				<li class="list-group-item">
					<b>@lang('app.bank_account')</b> <a class="pull-right">{{ $bank_account }}</a>
				</li>
			@endif --}}

			@if(auth()->user()->hasRole('agent') || auth()->user()->hasRole('distributor') || auth()->user()->hasRole('manager'))
			<div class="list-group-item">
				<div class="row">
					<div class="col-md-3">
						<label>충/환전금액:</label>
						<input type="number" class="form-control" id="withdraw_money" name="withdraw_money" value="" placeholder="충/환전금액을 입력하세요.">
					</div>
				</div>
			</div>

			<div class="box-footer">
				<button class="btn btn-primary" id="money-50k" onclick="add_money(50000);">
					+50,000
				</button>
				<button class="btn btn-primary" id="money-100k" onclick="add_money(100000);">
					+100,000
				</button>
				<button class="btn btn-primary" id="money-500k" onclick="add_money(500000);">
					+500,000
				</button>
				<button class="btn btn-primary" id="money-1m" onclick="add_money(1000000);">
					+1,000,000
				</button>
				<button class="btn btn-primary" id="money-3m" onclick="add_money(3000000);">
					+3,000,000
				</button>
				<button class="btn btn-primary" id="money-5m" onclick="add_money(5000000);">
					+5,000,000
				</button>
				<button class="btn btn-primary" id="reset" onclick="reset_money();">
					초기화
				</button>
			</div>

			<div class="box-footer">
				<button class="btn btn-danger" id="withdraw-balance-btn" onclick="withdraw_balance();">
					환전신청
				</button>
				<button class="btn btn-success" id="deposit-balance-btn" onclick="deposit_balance();">
					충전신청
				</button>
				<button class="btn btn-success" id="convert-deal-balance-btn" onclick="convert_deal_balance();">
					수익금전환
				</button>
			</div>
			@endif

				{{-- @if( $user->hasRole('user') )
				<li class="list-group-item">
					<b>@lang('app.in')</b> <a class="pull-right">{{ number_format($user->present()->total_in,2) }}</a>
				</li>
				<li class="list-group-item">
					<b>@lang('app.out')</b> <a class="pull-right">{{ number_format($user->present()->total_out,2) }}</a>
				</li>
				<li class="list-group-item">
					<b>@lang('app.total')</b> <a class="pull-right">{{ number_format($user->present()->total_in - $user->present()->total_out,2) }}</a>
				</li>
				@endif --}}
		</div>
		

		{{-- <form action="" method="GET">
			<div class="box box-danger collapsed-box pay_stat_show">
				<div class="box-header with-border">
					<h3 class="box-title">@lang('app.filter')</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
					</div>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>기간</label>
								<input type="text" class="form-control" name="dates" value="{{ Request::get('dates') }}">
							</div>
						</div>
					</div>
				</div>
				<div class="box-footer">
					<button type="submit" class="btn btn-primary">
						@lang('app.filter')
					</button>
				</div>
			</div>
		</form>--}}

		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">충환전신청내역</h3>
			</div>
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
						<thead>
						<tr>
							@if(auth()->user()->hasRole('manager'))
							<th>매장이름</th>
							@else
							<th>파트너이름</th>
							@endif
							<th>충전</th>
							<th>환전</th>
							<th>계좌번호</th>
							<th>예금주</th>
							<th>신청시간</th>
							<th>상태</th>
						</tr>
						</thead>
						<tbody>
						@if (count($in_out_logs))
							@foreach ($in_out_logs as $in_out_log)
								@include('backend.adjustment.partials.row_in_out')
							@endforeach
						@else
							<tr><td colspan="4">@lang('app.no_data')</td></tr>
						@endif
						</tbody>
					</table>
				</div>
				{{ $in_out_logs->appends(Request::except('page'))->links() }}
			</div>			
		</div> 
	</section>

@stop

@section('scripts')
	<script>
		$('#stat-table').dataTable();
		$(function() {
			$('input[name="dates"]').daterangepicker({
				timePicker: true,
				timePicker24Hour: true,
				startDate: moment().subtract(30, 'day'),
				endDate: moment().add(7, 'day'),

				locale: {
					format: 'YYYY-MM-DD HH:mm'
				}
			});
			$('.btn-box-tool').click(function(event){
				if( $('.pay_stat_show').hasClass('collapsed-box') ){
					$.cookie('pay_stat_show', '1');
				} else {
					$.removeCookie('pay_stat_show');
				}
			});

			if( $.cookie('pay_stat_show') ){
				$('.pay_stat_show').removeClass('collapsed-box');
				$('.pay_stat_show .btn-box-tool i').removeClass('fa-plus').addClass('fa-minus');
			}
		});

		function withdraw_balance() {
            var money = $('#withdraw_money').val();
            var _token = $('#_token').val();

            $.ajax({
                type: 'POST',
                url: '/api/withdraw',
                data: { money: money, _token: _token },
                cache: false,
                async: false,
                success: function (data) {
                    if (data.error) {
                        alert(data.msg);
                        if (data.code == '001') {
                            location.reload(true);
                        }
                        else if (data.code == '002') {
                            $('#withdraw_money').focus();
                        }
                        else if (data.code == '003') {
                            $('#withdraw_money').val('0');
                        }
                        return;
                    }
                    alert('환전 신청이 완료되었습니다.');
                    location.reload(true);
                },
                error: function (err, xhr) {
                    alert(err.responseText);
                }
            });
        }

		function deposit_balance() {
            var money = $('#withdraw_money').val();
            var _token = $('#_token').val();

            $.ajax({
                type: 'POST',
                url: '/api/deposit',
                data: { money: money, _token: _token },
                cache: false,
                async: false,
                success: function (data) {
                    if (data.error) {
                        alert(data.msg);
                        if (data.code == '001') {
                            location.reload(true);
                        }
                        else if (data.code == '002') {
                            $('#withdraw_money').focus();
                        }
                        else if (data.code == '003') {
                            $('#withdraw_money').val('0');
                        }
                        return;
                    }
                    alert('충전 신청이 완료되었습니다.');
                    location.reload(true);
                },
                error: function (err, xhr) {
                    alert(err.responseText);
                }
            });
        }

		function withdraw_deal_balance() {
            var money = $('#withdraw_money').val();
            var _token = $('#_token').val();

            $.ajax({
                type: 'POST',
                url: '/api/deal_withdraw',
                data: { money: money, _token: _token },
                cache: false,
                async: false,
                success: function (data) {
                    if (data.error) {
                        alert(data.msg);
                        if (data.code == '001') {
                            location.reload(true);
                        }
                        else if (data.code == '002') {
                            $('#withdraw_money').focus();
                        }
                        else if (data.code == '003') {
                            $('#withdraw_money').val('0');
                        }
                        return;
                    }
                    alert('환전 신청이 완료되었습니다.');
                    location.reload(true);
                },
                error: function (err, xhr) {
                    alert(err.responseText);
                }
            });
        }

		function convert_deal_balance() {
            var _token = $('#_token').val();

            $.ajax({
                type: 'POST',
                url: '/api/convert_deal_balance',
                data: { _token: _token },
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

		function change_bank_account_info() {
            var bank_name = $('#bank_name').val();
			var account_no = $('#account_no').val();
			var recommender = $('#recommender').val();
            var _token = $('#_token').val();

            $.ajax({
                type: 'POST',
                url: '/api/change_bank_account',
                data: { bank_name: bank_name, account_no: account_no, recommender: recommender, _token: _token },
                cache: false,
                async: false,
                success: function (data) {
                    if (data.error) {
                        alert(data.msg);
                        return;
                    }
                    alert('입금 계좌가 변경되었습니다.');
                    location.reload(true);
                },
                error: function (err, xhr) {
                    alert(err.responseText);
                }
            });
        }

		function add_money(amount) {
			var withdraw_money = $('#withdraw_money').val();
			if(withdraw_money == ''){
				withdraw_money = 0;
			}
			withdraw_money = parseInt(withdraw_money) + amount;
			$('#withdraw_money').val(withdraw_money);
		}

		function reset_money() {
			$('#withdraw_money').val(0);
		}


	</script>
@stop