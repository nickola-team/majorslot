@extends('backend.Default.layouts.app')

@section('page-title', '파트너충환전내역')
@section('page-heading', '파트너충환전내역')

@section('content')

	<section class="content-header">
		@include('backend.Default.partials.messages')
	</section>

	<section class="content">
		<form action="" method="GET">
			<div class="box box-danger collapsed-box pay_stat_show">
				<div class="box-header with-border">
					<h3 class="box-title">@lang('app.filter')</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
					</div>
				</div>
				<div class="box-body">
					<div class="row">
						{{-- <div class="col-md-6">
							<div class="form-group">
								<label>@lang('app.system')</label>
								<input type="text" class="form-control" name="system_str" value="{{ Request::get('system_str') }}">
							</div>
						</div> --}}
						<div class="col-md-6">
							<div class="form-group">
								<label>충/환전</label>
								<select name="type" class="form-control">
									<option value="" @if (Request::get('type') == '') selected @endif>@lang('app.all')</option>
									<option value="add" @if (Request::get('type') == 'add') selected @endif>충전</option>
									<option value="out" @if (Request::get('type') == 'out') selected @endif>환전</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>@lang('app.user')</label>
								<input type="text" class="form-control" name="user" value="{{ Request::get('user') }}">
							</div>
						</div>
						{{-- <div class="col-md-6">
							<div class="form-group">
								<label>@lang('app.payeer')</label>
								<input type="text" class="form-control" name="payeer" value="{{ Request::get('payeer') }}">
							</div>
						</div> --}}
						<div class="col-md-3">
							<div class="form-group">
								<label>최소금액</label>
								<input type="text" class="form-control" name="sum_from" value="{{ Request::get('sum_from') }}">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>최대금액</label>
								<input type="text" class="form-control" name="sum_to" value="{{ Request::get('sum_to') }}">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>기간</label>
								<input type="text" class="form-control" name="dates" value="{{ Request::get('dates') }}">
							</div>
						</div>
						{{-- <div class="col-md-12">
							<div class="form-group">
								@php
									 $filter = ['' => '---'];
                                    $shifts = \VanguardLTE\OpenShift::where('shop_id', Auth::user()->shop_id)->orderBy('start_date', 'DESC')->get();
                                    if( count($shifts) ){
                                        foreach($shifts AS $shift){
                                            $filter[$shift->id] = $shift->id . ' - ' . $shift->start_date;
                                        }
                                    }
								@endphp
								<label>@lang('app.shifts')</label>
								{!! Form::select('shifts', $filter, Request::get('shifts'), ['id' => 'shifts', 'class' => 'form-control']) !!}
							</div>
						</div> --}}
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
				{{-- <h3 class="box-title">@lang('app.pay_stats')</h3> --}}
				<h3 class="box-title">파트너충환전내역</h3>
			</div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
					<thead>
					<tr>
						{{-- <th>@lang('app.system')</th> --}}
						<th>이름(아이디)</th>
						<th>상위파트너</th>
						<th>충전</th>
						<th>환전</th>
						<th>수익금전환</th>
						<th>계좌정보</th>
						<th>예금주</th>
						<th>시간</th>
					</tr>
					</thead>
					<tbody>
					@if (count($statistics))
						@foreach ($statistics as $stat)
							@if($stat instanceof \VanguardLTE\ShopStat)
								@include('backend.Default.stat.partials.row_shop_stat')
							@else
								@include('backend.Default.stat.partials.row_stat')
							@endif
						@endforeach
					@else
						<tr><td colspan="8">@lang('app.no_data')</td></tr>
					@endif
					</tbody>
					<thead>
					<tr>
						{{-- <th>@lang('app.system')</th> --}}
						<th>이름(아이디)</th>
						<th>상위파트너</th>
						<th>충전</th>
						<th>환전</th>
						<th>수익금전환</th>
						<th>계좌정보</th>
						<th>예금주</th>
						<th>시간</th>
					</tr>
					</thead>
                            </table>
                        </div>
						{{ $statistics->appends(Request::except('page'))->links() }}
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
	</script>
@stop