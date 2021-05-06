@extends('backend.layouts.app')

@section('page-title', '실시간정산')
@section('page-heading', '실시간정산')

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
						<div class="col-md-4">
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
				<h3 class="box-title">
				실시간정산
				</h3>
					@if($user != null)
					<a href="{{ route('backend.adjustment_partner', $user->id==auth()->user()->id?'':'parent='.$user->parent_id) }}">
						{{$user->username}}
						[
						@foreach(['7'=>'app.admin', '6' => 'app.master','5' => 'app.agent', '4' => 'app.distributor', 'shop' => 'app.shop', '3' => 'app.manager'] AS $role_id=>$role_name)
						@if($role_id == $user->role_id)
							@lang($role_name)
						@endif
						@endforeach
						]
					</a>
					@endif
				
			</div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
					<thead>
					<tr>
						<th>이름</th>
						<th>충전</th>
						<th>환전</th>
						<th>하위충전</th>
						<th>하위환전</th>
						<th>베팅금</th>
						<th>당첨금</th>
						<th>딜비수익</th>
						<th>하위수익</th>
						<th>최종수익</th>
						<th>현재보유금</th>
						@if(auth()->user()->hasRole('admin'))
						<th>관리자수익</th>
						@endif
					</tr>
					</thead>
					<tbody>
					@if (count($adjustments))
						@foreach ($adjustments as $adjustment)
							@include('backend.adjustment.partials.row_partner')
						@endforeach
					@else
						<tr><td colspan="9">@lang('app.no_data')</td></tr>
					@endif
					</tbody>
					<thead>
					<tr>
						<th>이름</th>
						<th>충전</th>
						<th>환전</th>
						<th>하위충전</th>
						<th>하위환전</th>
						<th>베팅금</th>
						<th>당첨금</th>
						<th>딜비수익</th>
						<th>하위수익</th>
						<th>최종수익</th>
						<th>현재보유금</th>
						@if(auth()->user()->hasRole('admin'))
						<th>관리자수익</th>
						@endif						
					</tr>
					</thead>
                    </table>
                    </div>
						{{ $childs->appends(Request::except('page'))->links() }}
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

			// $('.btn-box-tool').click(function(event){
			// 	if( $('.pay_stat_show').hasClass('collapsed-box') ){
			// 		$.cookie('pay_stat_show', '1');
			// 	} else {
			// 		$.removeCookie('pay_stat_show');
			// 	}
			// });

			// if( $.cookie('pay_stat_show') ){
			// 	$('.pay_stat_show').removeClass('collapsed-box');
			// 	$('.pay_stat_show .btn-box-tool i').removeClass('fa-plus').addClass('fa-minus');
			// }
		});
	</script>
@stop