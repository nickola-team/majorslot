@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '게임뱅크충환전내역')
@section('page-heading', '게임뱅크충환전내역')

@section('content')

	<section class="content-header">
		@include('backend.Default.partials.messages')
	</section>

	<section class="content">
		<form action="" method="GET">
			<div class="box box-danger collapsed-box bank_stat_show">
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
								<label>@lang('app.name')</label>
								<input type="text" class="form-control" name="name" value="{{ Request::get('name') }}">
							</div>
						</div>
						{{-- <div class="col-md-6">
							<div class="form-group">
								<label>@lang('app.user')</label>
								<input type="text" class="form-control" name="user" value="{{ Request::get('user') }}">
							</div>
						</div> --}}
						<div class="col-md-6">
							<div class="form-group">
								<label>충/환전</label>
								{!! Form::select('type', ['' => 'All', 'add' => 'Add', 'out' => 'Out'], Request::get('type'), ['id' => 'type', 'class' => 'form-control']) !!}
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>최소충환전금</label>
								<input type="text" class="form-control" name="sum_from" value="{{ Request::get('sum_from') }}">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>최대충환전금</label>
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
				{{-- <h3 class="box-title">@lang('app.bank_stats')</h3> --}}
				<h3 class="box-title">게임뱅크충환전내역</h3>
			</div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
					<thead>
					<tr>
						<th>@lang('app.name')</th>
						{{-- <th>@lang('app.user')</th> --}}
						<th>변동전금액</th>
						<th>변동후금액</th>
						<th>충전금액</th>
						<th>환전금액</th>
						<th>시간</th>
					</tr>
					</thead>
					<tbody>
					@if (count($bank_stat))
						@foreach ($bank_stat as $stat)
							@include('backend.Default.stat.partials.row_bank_stat')
						@endforeach
					@else
						<tr><td colspan="7">@lang('app.no_data')</td></tr>
					@endif
					</tbody>
					<thead>
					<tr>
						<th>@lang('app.name')</th>
						{{-- <th>@lang('app.user')</th> --}}
						<th>변동전금액</th>
						<th>변동후금액</th>
						<th>충전금액</th>
						<th>환전금액</th>
						<th>시간</th>
					</tr>
					</thead>
                            </table>
                        </div>
						{{ $bank_stat->appends(Request::except('page'))->links() }}
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
				if( $('.bank_stat_show').hasClass('collapsed-box') ){
					$.cookie('bank_stat_show', '1');
				} else {
					$.removeCookie('bank_stat_show');
				}
			});

			if( $.cookie('bank_stat_show') ){
				$('.bank_stat_show').removeClass('collapsed-box');
			}
		});
	</script>
@stop