@extends('backend.layouts.app')

@section('page-title', '게임별정산')
@section('page-heading', '게임별정산')

@section('content')

	<section class="content-header">
		@include('backend.partials.messages')
	</section>

	<section class="content">
		<form action="" method="GET">
			<div class="box box-danger pay_stat_show">
				{{-- <div class="box-header with-border">
					<h3 class="box-title">@lang('app.filter')</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
					</div>
				</div> 
				<input type="hidden" name="start_date" id="start_date" value="{{ $start_date }}">
				<input type="hidden" name="end_date" id="end_date" value="{{ $end_date }}">--}}
				<div class="box-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>이름</label>
								<input type="text" class="form-control" name="search" value="{{ Request::get('search') }}" placeholder="게임이름">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>@lang('app.category')</label>
								<select class="form-control select2" name="category[]" id="category" multiple="multiple" style="width: 100%;" data-placeholder="">
									<option value="0">선택안함</option>
									@foreach ($categories as $key=>$category)
										<option value="{{ $category->id }}" {{ $category->id == $saved_category ? 'selected="selected"' : '' }}>{{ $category->title }}</option>
									@endforeach
								</select>
							</div>
						</div>

						
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<table class="table">
								<td style="vertical-align: inherit;">
									<span>기간선택</span>
								</td>
								<td>
									<input type="text" class="form-control" name="dates" value="{{ Request::get('dates') }}">
								</td>
								<td>
									<button type="submit" class="btn btn-primary">
									@lang('app.filter')
									</button>
								</td>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>

		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">게임별정산</h3>
			</div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
					<thead>
					<tr>
						<th>게임이름</th>
						<th>카테고리</th>
						<th>베팅금</th>
						<th>당첨금</th>
						@if(auth()->user()->hasRole('admin'))
						<th>환수금</th>
						<th>잭팟금</th>
						<th>수익금</th>
						@endif
						<th>베팅횟수</th>
						<th>딜비수익</th>
						<th>하위수익</th>
						<th>최종수익</th>
					</tr>
					</thead>
					<tbody>
					@if (count($adjustments))
						@foreach ($adjustments as $adjustment)
							@include('backend.adjustment.partials.row_game')
						@endforeach
					@else
						<tr><td colspan="4">@lang('app.no_data')</td></tr>
					@endif
					</tbody>
					<thead>
					<tr>
						<td><span class="text-red">합계</span></td>
						<td></td>
						<td><span class="text-red">{{number_format($sum_adjustment->total_bet,2)}}</span></th>
						<td><span class="text-red">{{number_format($sum_adjustment->total_win,2)}}</span></td>
						@if(auth()->user()->hasRole('admin'))
						<td><span class="text-red">{{number_format($sum_adjustment->total_percent,2)}}</span></td>
						<td><span class="text-red">{{number_format($sum_adjustment->total_percent_jps+$sum_adjustment->total_percent_jpg,2)}}</span></td>
						<td><span class="text-red">{{number_format($sum_adjustment->total_profit,2)}}</span></td>
						@endif
						<td><span class="text-red">{{number_format($sum_adjustment->total_bet_count)}}</span></td>
						<td><span class="text-red">{{number_format($sum_adjustment->total_deal,2)}}</span></td>
						<td><span class="text-red">{{number_format($sum_adjustment->total_mileage,2)}}</span></td>
						@if(auth()->user()->hasRole('admin'))
						<td><span class="text-red">{{number_format($sum_adjustment->total_real_profit,2)}}</span></td>
						@else
						<td><span class="text-red">{{number_format($sum_adjustment->total_deal - $sum_adjustment->total_mileage,2)}}</span></td>
						@endif
					</tr>
					</thead>
                    </table>
                    </div>
						{{ $games->appends(Request::except('page'))->links() }}
                    </div>			
		</div>
	</section>

@stop

@section('scripts')
	<script>
		$('#stat-table').dataTable();
		$(function() {
			$('input[name="dates"]').daterangepicker({
				timePicker: false,
				timePicker24Hour: false,
				startDate: moment().subtract(1, 'day'),
				endDate: moment().add(0, 'day'),

				locale: {
					format: 'YYYY-MM-DD'
				}
			});
			$('input[name="dates"]').data('daterangepicker').setStartDate("{{$start_date}}");
			$('input[name="dates"]').data('daterangepicker').setEndDate("{{ $end_date }}");
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