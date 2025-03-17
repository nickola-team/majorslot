@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '정산')
@section('page-heading', '정산')

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
				<input type="hidden" name="start_date" id="start_date" value="{{ $start_date }}">
				<input type="hidden" name="end_date" id="end_date" value="{{ $end_date }}">
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

		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">
				{{$type=='daily'?'일별':'월별'}}정산
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
					<a href="{{ route($admurl.'.adjustment_daily', $user->id==auth()->user()->id?'':'parent='.$user->parent_id) }}">
						{{$user->username}}
						[ {{$available_roles_trans[$user->role_id]}} ]
					</a>
					@endif
				
			</div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
					<thead>
					<tr>
						<th>이름</th>
						<th>날짜</th>
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
						@if ($type=='daily')
						<th>보유금</th>
						<th>하위보유금(합계)</th>
						<th>총보유금</th>
						@endif
						@if(auth()->user()->isInoutPartner())
						<th>머니금액</th>
						<th>순이익금</th>
						@endif
					</tr>
					</thead>
					<tbody>
					<?php
						$totalggr = 0;
					?>
					@if (count($summary))
						@foreach ($summary as $adjustment)
							<?php
								$ggr = 0;
								$comaster = $adjustment->user;
								while ($comaster && !$comaster->hasRole('comaster'))
								{
									$comaster = $comaster->referral;
								}
								if ($comaster)
								{
									$ggr = ($adjustment->totalbet - $adjustment->totalwin) * $comaster->money_percent / 100;
								}
								$totalggr = $totalggr + $ggr;
							?>
							@include('backend.Default.adjustment.partials.row_daily', ['ggr' => $ggr])
						@endforeach
						<tr>
						<td><span class='text-red'></span></td>
						<td><span class='text-red'>합계</span></td>
						<td><span class='text-red'>{{number_format($summary->sum('totalin'),0)}}</span></td>
						<td><span class='text-red'>{{number_format($summary->sum('totalout'),0)}}</span></td>
						<td><span class='text-red'>{{number_format($summary->sum('moneyin'),0)}}</span></td>
						<td><span class='text-red'>{{number_format($summary->sum('moneyout'),0)}}</span></td>
						@if(auth()->user()->isInoutPartner())
						<td><span class='text-red'>{{number_format($summary->sum('totalin') - $summary->sum('totalout'),0)}}</span></td>
						@endif
						<td><span class='text-red'>{{ number_format($summary->sum('total_deal')- $summary->sum('total_mileage'),0) }}</span></td>
						<td><span class='text-red'>{{number_format($summary->sum('dealout'),0)}}</span></td>
						@if ( auth()->user()->hasRole('admin') || auth()->user()->ggr_percent > 0 || (auth()->user()->hasRole('manager') && auth()->user()->shop->ggr_percent > 0))
						<td><span class='text-red'>{{ number_format($summary->sum('total_ggr')- $summary->sum('total_ggr_mileage')-($summary->sum('total_deal')- $summary->sum('total_mileage')),0) }}</span></td>
						<td><span class='text-red'>{{number_format($summary->sum('ggrout'),0)}}</span></td>
						@endif
						<td><span class='text-red'>{{number_format($summary->sum('totalbet'),0)}}</span></td>
						<td><span class='text-red'>{{number_format($summary->sum('totalwin'),0)}}</span></td>
						<td><span class='text-red'>{{ number_format($summary->sum('totalbet')-$summary->sum('totalwin'),0) }}</span></td>
						@if ($type=='daily')
						<td><span class='text-red'>0</span></td>
						<td><span class='text-red'>0</span></td>
						<td><span class='text-red'>0</span></td>
						@endif
						@if(auth()->user()->isInoutPartner())
						<td><span class='text-red'>{{ number_format($totalggr ,0) }}</span></td>
						<td><span class='text-red'>{{ number_format($summary->sum('totalin') - $summary->sum('totalout') - $totalggr,0) }}</span></td>
						@endif

						</tr>
					@else
						<tr><td colspan="14">@lang('app.no_data')</td></tr>
					@endif
					</tbody>
					<thead>
					<tr>
						<th>이름</th>
						<th>날짜</th>
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
						@if ($type=='daily')
						<th>보유금</th>
						<th>하위보유금(합계)</th>
						<th>총보유금</th>
						@endif
						@if(auth()->user()->isInoutPartner())
						<th>머니금액</th>
						<th>순이익금</th>
						@endif
					</tr>
					</thead>
                    </table>
                    </div>
						{{ $summary->appends(Request::except('page'))->links() }}
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
					format: 'YYYY-MM{{$type=="daily"?"-DD":""}}'
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