@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '게임별정산')
@section('page-heading', '게임별정산')

@section('content')

	<section class="content-header">
		@include('backend.Default.partials.messages')
	</section>

	<section class="content">
		<form action="" method="GET">
			<div class="box box-danger pay_stat_show">
				<div class="box-header with-border">
					<h3 class="box-title">@lang('app.filter')</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div>
				</div> 
				<div class="box-body">
					@if (!auth()->user()->hasRole('manager'))
					<div class="col-md-3">
						<div class="form-group">
							<label>파트너이름</label>
							<input type="text" class="form-control" name="partner" value="{{ Request::get('partner') }}">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>타입</label>
							{!! Form::select('type', ['partner' => '파트너', 'shop' => '매장'], Request::get('type'), ['id' => 'type', 'class' => 'form-control']) !!}
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>게임사이름</label>
							<input type="text" class="form-control" name="category" value="{{ Request::get('category') }}">
						</div>
					</div>
					@endif
					<div class="col-md-6">
						<div class="form-group">
							<label>기간선택</label>
							<input type="text" class="form-control" name="dates" value="{{ Request::get('dates') }}">
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
				<h3 class="box-title">게임별정산</h3>
				<div class="pull-right box-tools">
							마지막갱신시간 {{$updated_at}}
				</div>
			</div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
					<thead>
					<tr>
						<th>날짜</th>
						<th>게임이름</th>
						<th>배팅금</th>
						<th>당첨금</th>
						<th>죽은금액</th>
						<th>베팅횟수</th>
						<th>딜비수익</th>
						<th>하위수익</th>
						<th>최종수익</th>
					</tr>
					</thead>
					<tbody>
					@if (isset($categories))
						@foreach ($categories as $adjustment)
						<tr>
						<td rowspan="{{count($adjustment['cat'])}}"> {{ $adjustment['date'] }}</td>
							@include('backend.Default.adjustment.partials.row_game', ['total' => false])
						</tr>

						@endforeach
					@endif
					<tr>
					<td > {{ $totalcategory['date'] }}</td>
					@include('backend.Default.adjustment.partials.row_game', ['adjustment' => ['cat' => [$totalcategory]], 'total' => true])
					</tr>
					</tbody>
                    </table>
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