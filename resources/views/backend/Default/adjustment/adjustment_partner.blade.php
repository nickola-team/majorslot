@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '실시간정산')
@section('page-heading', '실시간정산')

@section('content')

	<section class="content-header">
		@include('backend.Default.partials.messages')
	</section>

	<section class="content">
		<form action="" method="GET">
			<div class="box box-danger users_show">
			{{--<div class="box-header with-border">
					<h3 class="box-title">@lang('app.filter')</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
					</div>
				</div> --}}
				<div class="box-body">
					<div class="col-md-6">
						<div class="form-group">
							<label>기간선택</label>
							<input type="text" class="form-control" name="dates" value="{{ Request::get('dates') }}">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>파트너이름</label>
							<input type="text" class="form-control" name="search" value="{{ Request::get('search') }}">
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
						<th>등급</th>
						<th>충전</th>
						<th>환전</th>
						@if(auth()->user()->hasRole(['admin', 'master']))
						<th>본사수익</th>
						@endif
						<th>딜비수익</th>
						<th>수익금전환</th>
						<th>매장충전</th>
						<th>매장환전</th>
						<th>베팅금</th>
						<th>당첨금</th>
						<th>매장수익</th>
						@if(auth()->user()->hasRole(['admin', 'master']))
						<th>최종수익</th>
						@endif
					</tr>
					</thead>
					<tbody>
					@if (count($adjustments))
						@foreach ($adjustments as $adjustment)
							@include('backend.Default.adjustment.partials.row_partner')
						@endforeach
					@else
						<tr><td colspan="9">@lang('app.no_data')</td></tr>
					@endif
					</tbody>
					<thead>
					<tr>
						<th>이름</th>
						<th>등급</th>
						<th>충전</th>
						<th>환전</th>
						@if(auth()->user()->hasRole(['admin', 'master']))
						<th>본사수익</th>
						@endif
						<th>딜비수익</th>
						<th>수익금전환</th>
						<th>매장충전</th>
						<th>매장환전</th>
						<th>베팅금</th>
						<th>당첨금</th>
						<th>매장수익</th>
						@if(auth()->user()->hasRole(['admin', 'master']))
						<th>최종수익</th>
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