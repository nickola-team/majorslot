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
							<input type="text" class="form-control" name="search" value="{{ Request::get('search') }}">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>타입</label>
							{!! Form::select('type', ['partner' => '파트너', 'shop' => '매장'], Request::get('type'), ['id' => 'type', 'class' => 'form-control']) !!}
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

		@if (auth()->user()->isInoutPartner())
		@else

		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">
					수익금
				</h3>
			</div>
			<?php
				$shop = \VanguardLTE\Shop::find( auth()->user()->shop_id );
				$ggr_percent = auth()->user()->hasRole('manager')?$shop->ggr_percent:auth()->user()->ggr_percent;
				$deal_percent = auth()->user()->hasRole('manager')?$shop->deal_percent:auth()->user()->deal_percent;
				$table_percent = auth()->user()->hasRole('manager')?$shop->table_deal_percent:auth()->user()->table_deal_percent;
				$deal_balance = auth()->user()->hasRole('manager')?($shop->deal_balance-$shop->mileage):(auth()->user()->deal_balance - auth()->user()->mileage);
				$ggr_balance =  auth()->user()->hasRole('manager')?($shop->ggr_balance - $shop->count_deal_balance):(auth()->user()->ggr_balance - auth()->user()->ggr_mileage - (auth()->user()->count_deal_balance - auth()->user()->count_mileage));
				if (auth()->user()->hasRole('manager')){
					$reset_time = $shop->last_reset_at?\Carbon\Carbon::parse($shop->last_reset_at)->addDays($shop->reset_days):date('Y-m-d 00:00:00', strtotime("+" . $shop->reset_days . " days"));
				}
				else
				{
					$reset_time = auth()->user()->last_reset_at?\Carbon\Carbon::parse(auth()->user()->last_reset_at)->addDays(auth()->user()->reset_days):date('Y-m-d', strtotime("+" . auth()->user()->reset_days . " days"));
				}
        	?>
			<div class="box-body">
				<div class="col-md-3">
					<div class="form-group">
						<label>딜비수익({{$deal_percent}}%, {{$table_percent}}%)</label>
						<input type="text" class="form-control" value="{{ number_format($deal_balance,0) }}원" disabled>
					</div>
				</div>
				
				@if ($ggr_percent > 0)
				<div class="col-md-3">
					<div class="form-group">
						<label>죽장수익({{$ggr_percent}}%)</label>
						<input type="text" class="form-control" value="{{ number_format($ggr_balance,0) }}원" disabled>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>정산시간</label>
						<input type="text" class="form-control" value="{{ $reset_time }}" disabled>
					</div>
				</div>
				@endif
            </div>			
		</div>
		@endif

		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">
				실시간정산
				</h3>
				<?php  
					$available_roles = Auth::user()->available_roles( true );
					$available_roles_trans = [];
					foreach ($available_roles as $key=>$role)
					{
						$role = \VanguardLTE\Role::find($key)->description;
						$available_roles_trans[$key] = $role;
					}
				?>
					@if($user != null)
					<a href="{{ route($admurl.'.adjustment_partner', $user->id==auth()->user()->id?'':'parent='.$user->parent_id) }}">
						{{$user->username}}
						[ {{$available_roles_trans[$user->role_id]}} ]
					</a>
					@endif
				<div class="pull-right box-tools">
							마지막갱신시간 {{$updated_at}}
				</div>
				
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
						<th>수동충전</th>
						<th>수동환전</th>
						@if(auth()->user()->isInoutPartner())
						<th>이익금</th>
						@endif
						<th>딜비수익</th>
						<th>딜비전환</th>
						@if ( auth()->user()->hasRole('admin') || auth()->user()->ggr_percent > 0 || (auth()->user()->hasRole('manager') && auth()->user()->shop->ggr_percent > 0))
						<th>죽장수익</th>
						<th>죽장전환</th>
						@endif
						<th>배팅금</th>
						<th>당첨금</th>
						<th>죽은금액</th>
						@if(auth()->user()->isInoutPartner())
						<th>머니금액</th>
						<th>순이익금</th>
						@endif		
					</tr>
					</thead>
					<tbody>
					@if (count($adjustments))
						@foreach ($adjustments as $adjustment)
							@include('backend.Default.adjustment.partials.row_partner')
						@endforeach
					@else
						<tr><td colspan="14">@lang('app.no_data')</td></tr>
					@endif
					</tbody>
					<thead>
					<tr>
					<th>이름</th>
						<th>등급</th>
						<th>충전</th>
						<th>환전</th>
						<th>수동충전</th>
						<th>수동환전</th>
						@if(auth()->user()->isInoutPartner())
						<th>이익금</th>
						@endif
						<th>딜비수익</th>
						<th>딜비전환</th>
						@if ( auth()->user()->hasRole('admin') || auth()->user()->ggr_percent > 0 || (auth()->user()->hasRole('manager') && auth()->user()->shop->ggr_percent > 0))
						<th>죽장수익</th>
						<th>죽장전환</th>
						@endif
						<th>배팅금</th>
						<th>당첨금</th>
						<th>죽은금액</th>
						@if(auth()->user()->isInoutPartner())
						<th>머니금액</th>
						<th>순이익금</th>
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