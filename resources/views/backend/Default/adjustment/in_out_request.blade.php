@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '충환전신청')
@section('page-heading', '충환전신청')

@section('content')

	<section class="content-header">
		@include('backend.Default.partials.messages')
	</section>

	<section class="content">
		
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">충환전신청</h3>
			</div>
			<div class="box-body">
			<div class="row">
				<div class="col-md-6">
					<input type="hidden" value="<?= csrf_token() ?>" name="_token" id="_token">

					<div class="row">
						<div class="col-md-12" style="margin-top: 10px;">
							<b class="text-red">주의사항</b>
								<ul>
								<li>입금계좌는 수시로 변경될수 있습니다. 입금하시기전에  입금계좌신청을 해주세요.</li>
								<li>반드시 본인의 명의로 입금해야 정상적인 충전처리가 진행됩니다.</li>
								<li>환전신청시 입금계좌요청은 안하셔도 됩니다.</li>
								</ul>
						</div>
					</div>

					<div class="row" style="margin-top: 20px;">
						<div class="col-md-6">
							<b>본인 은행계좌:</b> 
								@php
									$banks = array_combine(\VanguardLTE\User::$values['banks'], \VanguardLTE\User::$values['banks']);
								@endphp
								{!! Form::select('bank_name', $banks, auth()->user()->bank_name ? auth()->user()->bank_name : '', ['class' => 'form-control', 'id' => 'bank_name']) !!}		
						</div>
					</div>

					<div class="row" style="margin-top: 20px;">
						<div class="col-md-6">
							<b>계좌번호:</b> 
							<input type="text" class="form-control" id="account_no" name="account_no" value="{{ auth()->user()->account_no ? auth()->user()->account_no : '' }}">
						</div>
					</div>

					<div class="row" style="margin-top: 20px;">
						<div class="col-md-6">
							<b>예금주:</b> 
							<input type="text" class="form-control" id="recommender" name="recommender" value="{{ auth()->user()->recommender ? auth()->user()->recommender : '' }}">
						</div>
					</div>

					<div class="row" style="margin-top: 20px;">
						<div class="col-md-6">
						<button class="btn btn-primary" id="change-bank-account-btn" onclick="change_bank_account_info();">
							계좌정보변경
						</button>
						</div>
					</div>

				<?php
				$dealvalue = auth()->user()->hasRole('manager')?(auth()->user()->shop->deal_balance - auth()->user()->shop->mileage):(auth()->user()->deal_balance - auth()->user()->mileage);
				$balance = auth()->user()->hasRole('manager')?auth()->user()->shop->balance:auth()->user()->balance;
				?>
				<div class="row" style="margin-top: 20px;">
					<div class="col-md-6">
						<b>보유금:</b> <a class="pull-right">{{ number_format($balance,0) }}원</a>
					</div>
				</div>
				@if(auth()->user()->hasRole(['agent','distributor','manager']) || (auth()->user()->hasRole('master') && settings('enable_master_deal')))
				
					<div class="row" style="margin-top: 20px;">
						<div class="col-md-6" style="line-height:2">
							<b>딜비수익:</b> ({{number_format(auth()->user()->hasRole('manager')?auth()->user()->shop->deal_percent:auth()->user()->deal_percent,2)}}%,{{number_format(auth()->user()->hasRole('manager')?auth()->user()->shop->table_deal_percent:auth()->user()->table_deal_percent,2)}}%) <a class="pull-right">{{ 
								number_format($dealvalue,0) 
								}}원 </a>
								
						</div>
						<div class="col-md-6">
						<a class="newPayment outPayment" href="#" data-toggle="modal" data-target="#openOutModal"  data-id="{{ (int)($dealvalue / 10000) * 10000 }}">
								<button class="btn btn-success" id="convert-deal-balance-btn">
								딜비전환
								</button>
							</a>
						</div>
					</div>
				@endif


				<div class="row" style="margin-top: 20px;">
					<div class="col-md-6">
						<label>충/환전금액:</label>
						<input type="number" class="form-control" id="withdraw_money" name="withdraw_money" value="" placeholder="충/환전금액을 입력하세요." readonly>
					</div>
				</div>
				<div class="row" style="margin-top: 20px;">
					<div class="col-md-6">
						<label>환전비밀번호:</label>
						<input type="password" class="form-control" id="confirmation_token" name="confirmation_token" value="" placeholder="환전하시려면 환전비밀번호를 입력하세요">
					</div>
				</div>
				</div>
				<div class='col-md-6'>
				<div class="banktable">
                                <h3 class="banktable-meta">은행별 점검시간</h3>
                                <ul class="banktable-data">
                                            <li class="item">
                                                <div class="item-logo" style="background-image:url(/back/img/bank/kb.png);">국민은행</div>
                                                <div class="item-num">23:30 ~ 00:05</div>
                                            </li>
                                            <li class="item">
                                                <div class="item-logo" style="background-image:url(/back/img/bank/woori.png);">우리은행</div>
                                                <div class="item-num">00:00 ~ 00:10</div>
                                            </li>
                                            <li class="item">
                                                <div class="item-logo" style="background-image:url(/back/img/bank/shinhan.png);">신한은행</div>
                                                <div class="item-num">23:00 ~ 24:00</div>
                                            </li>
                                            <li class="item">
                                                <div class="item-logo" style="background-image:url(/back/img/bank/hana.png);">하나은행</div>
                                                <div class="item-num">00:00 ~ 01:00</div>
                                            </li>
                                            <li class="item">
                                                <div class="item-logo" style="background-image:url(/back/img/bank/sc.png);">SC제일은행</div>
                                                <div class="item-num">00:00 ~ 00:30</div>
                                            </li>
                                            <li class="item">
                                                <div class="item-logo" style="background-image:url(/back/img/bank/citi.png);">citibank</div>
                                                <div class="item-num">23:40 ~ 00:05</div>
                                            </li>
                                            <li class="item">
                                                <div class="item-logo" style="background-image:url(/back/img/bank/keb.png);">KEB 외환은행</div>
                                                <div class="item-num">23:55 ~ 00:05</div>
                                            </li>
                                            <li class="item">
                                                <div class="item-logo" style="background-image:url(/back/img/bank/dgb.png);">대구은행</div>
                                                <div class="item-num">00:00 ~ 01:00</div>
                                            </li>
                                            <li class="item">
                                                <div class="item-logo" style="background-image:url(/back/img/bank/bs.png);">부산은행</div>
                                                <div class="item-num">00:00 ~ 01:30</div>
                                            </li>
                                            <li class="item">
                                                <div class="item-logo" style="background-image:url(/back/img/bank/kj.png);">광주은행</div>
                                                <div class="item-num">23:50 ~ 00:10</div>
                                            </li>
                                            <li class="item">
                                                <div class="item-logo" style="background-image:url(/back/img/bank/kn.png);">경남은행</div>
                                                <div class="item-num">00:00 ~ 00:20</div>
                                            </li>
                                            <li class="item">
                                                <div class="item-logo" style="background-image:url(/back/img/bank/nh.png);">농협</div>
                                                <div class="item-num">00:00 ~ 00:30</div>
                                            </li>
                                            <li class="item">
                                                <div class="item-logo" style="background-image:url(/back/img/bank/sh.png);">수협</div>
                                                <div class="item-num">00:00 ~ 01:00</div>
                                            </li>
                                            <li class="item">
                                                <div class="item-logo" style="background-image:url(/back/img/bank/ibk.png);">기업은행</div>
                                                <div class="item-num">00:00 ~ 00:10</div>
                                            </li>
                                            <li class="item">
                                                <div class="item-logo" style="background-image:url(/back/img/bank/cu.png);">신협</div>
                                                <div class="item-num">23:50 ~ 00:05</div>
                                            </li>
                                            <li class="item">
                                                <div class="item-logo" style="background-image:url(/back/img/bank/mg.png);">MG새마을금고</div>
                                                <div class="item-num">23:50 ~ 00:30</div>
                                            </li>
                                            <li class="item">
                                                <div class="item-logo" style="background-image:url(/back/img/bank/sangho.png);">상호저축은행</div>
                                                <div class="item-num">23:00 ~ 08:00</div>
                                            </li>
                                            <li class="item">
                                                <div class="item-logo" style="background-image:url(/back/img/bank/jeonbook.png);">전북은행</div>
                                                <div class="item-num">23:50 ~ 00:10</div>
                                            </li>
                                            <li class="item">
                                                <div class="item-logo" style="background-image:url(/back/img/bank/jeju.png);">제주은행</div>
                                                <div class="item-num">00:00 ~ 00:10</div>
                                            </li>
                                            <li class="item">
                                                <div class="item-logo" style="background-image:url(/back/img/bank/mirae.png);">미래에셋</div>
                                                <div class="item-num">23:30 ~ 00:30</div>
                                            </li>
                                            <li class="item">
                                                <div class="item-logo" style="background-image:url(/back/img/bank/samsung.png);">삼성증권</div>
                                                <div class="item-num">23:50 ~ 00:10</div>
                                            </li>
                                            <li class="item">
                                                <div class="item-logo" style="background-image:url(/back/img/bank/hyundai.png);">현대증권</div>
                                                <div class="item-num">23:45 ~ 00:15</div>
                                            </li>
                                            <li class="item">
                                                <div class="item-logo" style="background-image:url(/back/img/bank/sk.png);">SK증권</div>
                                                <div class="item-num">23:50 ~ 06:00</div>
                                            </li>
                                            <li class="item">
                                                <div class="item-logo" style="background-image:url(/back/img/bank/kbank.png);">케이뱅크</div>
                                                <div class="item-num">23:50 ~ 12:30</div>
                                            </li>
                                            <li class="item">
                                                <div class="item-logo" style="background-image:url(/back/img/bank/postbank.png);">우체국</div>
                                                <div class="item-num">23:50 ~ 00:30</div>
                                            </li>
                                            <li class="item">
                                                <div class="item-logo" style="background-image:url(/back/img/bank/kbstock.png);">KB증권</div>
                                                <div class="item-num">23:50~ 00:30</div>
                                            </li>
                                            <li class="item">
                                                <div class="item-logo" style="background-image:url(/back/img/bank/kinvest.png);">한국투자증권</div>
                                                <div class="item-num">23:50~ 00:30</div>
                                            </li>
                                </ul>
                            </div>
				</div>
			</div>


			<div class="row" style="margin: 20px 0px;">
				<div class="col-md-6">
					<button class="btn btn-primary" id="money-50k" onclick="add_money(10000);">
						+10,000
					</button>
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
					<button class="btn btn-primary" id="reset" onclick="reset_money();">
						초기화
					</button>
				</div>
			</div>
			<div class="row" style="margin: 20px 0px;">
				<div class="col-md-6">
					<label>입금계좌:</label>
					<button class="btn btn-danger" id="togglebankinfo">
						계좌요청
					</button>
					<span class="text-red" id="bankinfo" style="display:none;">
						{{$bankinfo}}
					</span>
				</div>
			</div>

			<div class="box-footer">
				<button class="btn btn-danger" id="withdraw-balance-btn" onclick="withdraw_balance();">
					환전신청
				</button>
				
				<button class="btn btn-success" id="deposit-balance-btn" onclick="deposit_balance();" {{(auth()->user()->hasRole(['agent','distributor']) || (auth()->user()->hasRole('master') && settings('enable_master_deal')))?'disabled':''}}>
					충전신청
				</button>
			</div>
			

			</div>
		
		</div>
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
							<th>신청금액</th>
							<th>계좌번호</th>
							<th>예금주</th>
							<th>신청시간</th>
							<th>상태</th>
						</tr>
						</thead>
						<tbody>
						@if (count($in_out_logs))
							@foreach ($in_out_logs as $in_out_log)
								@include('backend.Default.adjustment.partials.row_in_out')
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

	<div class="modal fade" id="openOutModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="#" method="GET" id="outForm">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">정산</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="OutSum">딜비전환</label>
							<input type="text" class="form-control" id="OutSum" name="OutSum" placeholder="전환금액"   required>
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


@stop

@section('scripts')
	<script>
		var depositAccountRequested = false;
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

			$('#togglebankinfo').click(function() {
				if (depositAccountRequested)
				{
					if($("#bankinfo").is(":visible")){
						$("#bankinfo").hide();
						$('#togglebankinfo').html('보이기');
					}
					else
					{
						$("#bankinfo").show();
						$('#togglebankinfo').html('숨기기');
					}
				}
				else
				{
					result = confirm('입금계좌요청은 충전시에만 필요합니다. 요청하시겠습니까?');
					if (result){
						var money = $('#withdraw_money').val();
						var accountname = $('#recommender').val();
						var _token = $('#_token').val();
						$.ajax({
							type: 'POST',
							url: "{{route('frontend.api.depositAccount')}}",
							data: { money: money, _token: _token, account:accountname },
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
										$('#recommender').focus();
									}
									return;
								}
								$("#bankinfo").html(data.msg);
								$("#bankinfo").show();
								$('#togglebankinfo').html('숨기기');
								depositAccountRequested = true;
							},
							error: function (err, xhr) {
								alert(err.responseText);
							}
						});
					}
				}
				
				
			});

			$('.outPayment').click(function(event){
				if( $(event.target).is('.newPayment') ){
					var id = $(event.target).attr('data-id');
				}else{
					var id = $(event.target).parents('.newPayment').attr('data-id');
				}
				$('#OutSum').val(id);
			});
		});


		function withdraw_balance() {
			$('#withdraw-balance-btn').attr('disabled', 'disabled');
            var money = $('#withdraw_money').val();
			var confirmation_token = $('#confirmation_token').val();
            var _token = $('#_token').val();

            $.ajax({
                type: 'POST',
                url: '/api/outbalance',
                data: { money: money, _token: _token, confirmation_token : confirmation_token },
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
						else if (data.code == '010') {
                            location.href = "{{ route($admurl.'.user.edit', auth()->user()->id) }}";
							return;
                        }
						else
						{
							location.reload(true);
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

			if (!depositAccountRequested)
			{
				alert('입금계좌요청을 먼저 하세요');
				return;
			}

            $.ajax({
                type: 'POST',
                url: '/api/addbalance',
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
            var _token = $('#_token').val();
			var _dealsum = $('#OutSum').val();

            $.ajax({
                type: 'POST',
                url: '/api/deal_withdraw',
                data: { summ: _dealsum, _token: _token },
                cache: false,
                async: false,
                success: function (data) {
                    if (data.error) {
                        alert(data.msg);
						location.reload(true);
                        return;
                    }
                    alert('수익금환전 신청이 완료되었습니다.');
                    location.reload(true);
                },
                error: function (err, xhr) {
                    alert(err.responseText);
                }
            });
        }

		function convert_deal_balance() {
            var _token = $('#_token').val();
			var _dealsum = $('#OutSum').val();

            $.ajax({
                type: 'POST',
                url: '/api/convert_deal_balance',
                data: { _token: _token, summ: _dealsum },
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