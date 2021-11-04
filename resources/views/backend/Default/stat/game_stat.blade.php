@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '게임배팅내역')
@section('page-heading', '게임배팅내역')

@section('content')

	<section class="content-header">
		@include('backend.Default.partials.messages')
	</section>

	<section class="content">
		<div class="row">
			<div class="col-lg-6 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-green">
					<div class="inner">
						<h3>{{ number_format($totalbet) }}</h3>
						<p>총 배팅금</p>
					</div>
					<div class="icon">
						<i class="fa fa-refresh"></i>
					</div>
				</div>
			</div>
			
			<div class="col-lg-6 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-red">
					<div class="inner">
						<h3>{{ number_format($totalwin) }}</h3>
						<p>총 당첨금</p>
					</div>
					<div class="icon">
						<i class="fa fa-line-chart"></i>
					</div>
				</div>
			</div>
			<!-- ./col -->

		</div>
		<form action="" method="GET">
			<div class="box box-danger collapsed-box game_stat_show">
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
								<label>게임명</label>
								<input type="text" class="form-control" name="game" value="{{ Request::get('game') }}">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>회원이름</label>
								<input type="text" class="form-control" name="user" value="{{ Request::get('user') }}">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>최소보유금액</label>
								<input type="text" class="form-control" name="balance_from" value="{{ Request::get('balance_from') }}">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>최대보유금액</label>
								<input type="text" class="form-control" name="balance_to" value="{{ Request::get('balance_to') }}">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>최소베팅금액</label>
								<input type="text" class="form-control" name="bet_from" value="{{ Request::get('bet_from') }}">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>최대베팅금액</label>
								<input type="text" class="form-control" name="bet_to" value="{{ Request::get('bet_to') }}">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>최소당첨금액</label>
								<input type="text" class="form-control" name="win_from" value="{{ Request::get('win_from') }}">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>최대당첨금액</label>
								<input type="text" class="form-control" name="win_to" value="{{ Request::get('win_to') }}">
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
					@if( Auth::user()->hasRole('admin') )
						<a href="{{ route($admurl.'.game_stat.clear') }}"
						   class="btn btn-danger"
						   data-method="DELETE"
						   data-confirm-title="경고"
						   data-confirm-text="모든 게임로그를 삭제하시겠습니까?"
						   data-confirm-delete="확인">
							게임베팅내역 삭제
						</a>
					@endif
				</div>
			</div>
		</form>

		<div class="box box-primary">
			<div class="box-header with-border">
				{{-- <h3 class="box-title">@lang('app.game_stats')</h3> --}}
				<h3 class="box-title">게임배팅내역</h3>
			</div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
					<thead>
					<tr>
						<th>게임명</th>
						<th>아이디</th>
						<th>보유금액</th>
						<th>베팅금액</th>
						<th>당첨금액</th>
						{{-- <th>@lang('app.in_game')</th>
						<th>@lang('app.in_jps')</th>
						<th>@lang('app.in_jpg')</th> --}}
						{{-- @if(auth()->user()->hasRole('admin'))
							<th>@lang('app.profit')</th>
						@endif --}}
						{{-- <th>@lang('app.denomination')</th> --}}
						<th>베팅시간</th>
					</tr>
					</thead>
					<tbody>
					@if (count($game_stat))
						@foreach ($game_stat as $stat)
							@include('backend.Default.games.partials.row_stat')
						@endforeach
					@else
						<tr><td colspan="11">@lang('app.no_data')</td></tr>
					@endif
					</tbody>
					<thead>
					<tr>
						<th>게임명</th>
						<th>아이디</th>
						<th>보유금액</th>
						<th>베팅금액</th>
						<th>당첨금액</th>
						{{-- <th>@lang('app.in_game')</th>
						<th>@lang('app.in_jps')</th>
						<th>@lang('app.in_jpg')</th>
						@if(auth()->user()->hasRole('admin'))
							<th>@lang('app.profit')</th>
						@endif
						<th>@lang('app.denomination')</th> --}}
						<th>베팅시간</th>
					</tr>
					</thead>
                            </table>
                        </div>
						{{ $game_stat->appends(Request::except('page'))->links() }}
                    </div>			
		</div>
	</section>

@stop

@section('scripts')
	<script>
		$('#stats-table').dataTable();
		$(function() {
			$('input[name="dates"]').daterangepicker({
				timePicker: true,
				timePicker24Hour: true,
				startDate: moment().subtract(1, 'hour'),
				endDate: moment().add(0, 'day'),

				locale: {
					format: 'YYYY-MM-DD HH:mm'
				}
			});
			$('.btn-box-tool').click(function(event){
				if( $('.game_stat_show').hasClass('collapsed-box') ){
					$.cookie('game_stat_show', '1');
				} else {
					$.removeCookie('game_stat_show');
				}
			});
			$('.getbalance').click(function(event)
			{
				var item = $(event.target);
				var id = item.attr('data-id');
				$.ajax({
					type: 'GET',
					url: "{{route('backend.game_stat.balance')}}?id=" + id,
					cache: false,
					async: false,
					success: function (data) {
						if (data['error'] == false)
						{
							$(event.target).removeClass('text-red').addClass('text-green');
							$(event.target).text(data['balance']);
						}
					},
					error: function (err, xhr) {
						console.log(err.responseText);
					},
				});
			});

			if( $.cookie('game_stat_show') ){
				$('.game_stat_show').removeClass('collapsed-box');
				$('.game_stat_show .btn-box-tool i').removeClass('fa-plus').addClass('fa-minus');
			}
		});
	</script>
@stop