@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '매장충환전내역')
@section('page-heading', '매장충환전내역')

@section('content')

	<section class="content-header">
		@include('backend.Default.partials.messages')
	</section>

	<section class="content">
		<form action="" method="GET">
			<div class="box box-danger collapsed-box shop_stat_show">
				<div class="box-header with-border">
					<h3 class="box-title">@lang('app.filter')</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
					</div>
				</div>
				<div class="box-body">
					<div class="row">
					@if (!auth()->user()->hasRole('manager'))
						<div class="col-md-6">
							<div class="form-group">
								<label>매장</label>
								<input type="text" class="form-control" name="name" value="{{Request::get('name') }}">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>파트너이름</label>
								<input type="text" class="form-control" name="user" value="{{ Request::get('user') }}">
							</div>
						</div>
					@endif
						<div class="col-md-6">
							<div class="form-group">
								<label>충환전타입</label>
								{!! Form::select('type', ['' => '모두', 'add' => '충전', 'out' => '환전', 'deal_out' => '딜비전환', 'ggr_out' => '죽장전환'], Request::get('type'), ['id' => 'type', 'class' => 'form-control']) !!}
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>계좌정보</label>
								<select name="in_out" class="form-control">
									<option value="" @if (Request::get('in_out') == '') selected @endif>@lang('app.all')</option>
									<option value="1" @if (Request::get('in_out') == '1') selected @endif>있음</option>
									<option value="0" @if (Request::get('in_out') == '0') selected @endif>없음</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>최소금액</label>
								<input type="text" class="form-control" name="sum_from" value="{{ Request::get('sum_from') }}">
							</div>
						</div>
						<div class="col-md-6">
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
				{{-- <h3 class="box-title">@lang('app.shop_stats')</h3> --}}
				<h3 class="box-title">매장충환전내역</h3>
			</div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
					<thead>
					<tr>
						<th>매장</th>
						<th>파트너이름</th>
						@if (auth()->user()->isInoutPartner())
						<th>파트너보유금</th>
						@endif
						<th>변동전금액</th>
						<th>변동후금액</th>
						<th>충전</th>
						<th>환전</th>
						<th>딜비전환</th>
						@if (auth()->user()->hasRole('admin')  || auth()->user()->ggr_percent > 0 || (auth()->user()->hasRole('manager') && auth()->user()->shop->ggr_percent > 0))
						<th>죽장전환</th>
						@endif
						<th>계좌정보</th>
						<th>예금주</th>
						<th>시간</th>
					</tr>
					</thead>
					<tbody>
					<?php
						$totalin=0;
						$totalout=0;
						$totaldealout=0;
						$totalggrout=0;
					?>
					@if (count($shops_stat))
						@foreach ($shops_stat as $stat)
							@include('backend.Default.stat.partials.row_shop_stat')
							<?php
								if ($stat->type == 'add'){ $totalin = $totalin + abs($stat->sum);}
								if ($stat->type == 'out'){ $totalout = $totalout + abs($stat->sum);}
								if ($stat->type == 'deal_out'){ $totaldealout = $totaldealout + abs($stat->sum);}
								if ($stat->type == 'ggr_out'){ $totalggrout = $totalggrout + abs($stat->summ);}
							?>
						@endforeach
						<td></td>
						<td><span class="text-red">합계</span></td>
						@if (auth()->user()->isInoutPartner())
						<td></td>
						@endif						
						<td></td>
						<td></td>
						<td><span class="text-green">{{number_format($totalin,0)}}</span></td>
						<td><span class="text-red">{{number_format($totalout,0)}}</span></td>
						<td><span class="text-red">{{number_format($totaldealout,0)}}</span></td>
						@if (auth()->user()->hasRole('admin')  || auth()->user()->ggr_percent > 0 || (auth()->user()->hasRole('manager') && auth()->user()->shop->ggr_percent > 0))
						<td><span class="text-red">{{number_format($totalggrout,0)}}</span></td>
						@endif
						<td></td>
						<td></td>
						<td></td>
					@else
						<tr><td colspan="7">@lang('app.no_data')</td></tr>
					@endif
					</tbody>
					<thead>
					<tr>
						<th>매장</th>
						<th>파트너이름</th>
						@if (auth()->user()->isInoutPartner())
						<th>파트너보유금</th>
						@endif
						<th>변동전금액</th>
						<th>변동후금액</th>
						<th>충전</th>
						<th>환전</th>
						<th>딜비전환</th>
						@if (auth()->user()->hasRole('admin')  || auth()->user()->ggr_percent > 0 || (auth()->user()->hasRole('manager') && auth()->user()->shop->ggr_percent > 0))
						<th>죽장전환</th>
						@endif
						<th>계좌정보</th>
						<th>예금주</th>
						<th>시간</th>
					</tr>
					</thead>
                            </table>
                        </div>
						{{ $shops_stat->appends(Request::except('page'))->links() }}
                    </div>			
		</div>
	</section>

@stop

@section('scripts')
	<script>
		$(function() {
			$('#stat-table').dataTable();
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
				if( $('.shop_stat_show').hasClass('collapsed-box') ){
					$.cookie('shop_stat_show', '1');
				} else {
					$.removeCookie('shop_stat_show');
				}
			});

			if( $.cookie('shop_stat_show') ){
				$('.shop_stat_show').removeClass('collapsed-box');
				$('.shop_stat_show .btn-box-tool i').removeClass('fa-plus').addClass('fa-minus');
			}
		});
	</script>
@stop