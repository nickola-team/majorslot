@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '접속로그')
@section('page-heading', '접속로그')

@section('content')

	<section class="content-header">
		@include('backend.Default.partials.messages')
	</section>

	<section class="content">
		<form action="" method="GET">
			<div class="box box-danger collapsed-box activity_show">
				<div class="box-header with-border">
					<h3 class="box-title">@lang('app.filter')</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
					</div>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>이름(아이디)</label>
								<input type="text" class="form-control" name="username" value="{{ Request::get('username') }}" placeholder="@lang('app.search_for_users')">
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>@lang('app.message')</label>
								<input type="text" class="form-control" name="search" value="{{ Request::get('search') }}" placeholder="">
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>@lang('app.ip')</label>
								<input type="text" class="form-control" name="ip" value="{{ Request::get('ip') }}" placeholder="">
							</div>
						</div>
					</div>
				</div>
				<div class="box-footer">
					<button type="submit" class="btn btn-primary">
						검색
					</button>

					@if( Auth::user()->hasRole('admin') )
						<a href="{{ route($admurl.'.activity.clear') }}"
						   class="btn btn-danger"
						   data-method="DELETE"
						   data-confirm-title="경고"
						   data-confirm-text="접속로그정보를 삭제하시겠습니까?"
						   data-confirm-delete="확인">
							로그 삭제
						</a>
					@endif

				</div>
			</div>
		</form>

		<div class="box box-primary">
			<div class="box-header with-border">
				{{-- <h3 class="box-title">@lang('app.activity_log')</h3> --}}
				<h3 class="box-title">접속로그</h3>
			</div>
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
						<thead>
						<tr>
							@if (isset($adminView))
								<th>이름(아이디)</th>
							@endif
							<th>@lang('app.ip')</th>
							<th>@lang('app.message')</th>
							<th>접속시간</th>
							<th>상세정보</th>
						</tr>
						</thead>
						<tbody>
						@if (count($activities))
							@foreach ($activities as $activity)
								<tr>
									@if (isset($adminView))
										<td>
											@if (isset($user))
												{{ $activity->user->present()->username }}
											@else
												<a href="{{ route($admurl.'.activity.user', $activity->user_id) }}">{{ $activity->userdata->username }}</a>
											@endif
										</td>
									@endif
									<td>{{ $activity->ip_address }}</td>
									<td>{{ $activity->description }}</td>
									<td>{{ date(config('app.date_time_format'), strtotime($activity->created_at)) }}</td>
									<td>{{ $activity->user_agent }}</td>
								</tr>
							@endforeach
						@else
							<tr><td colspan="@if (isset($adminView)) 5 @else 4 @endif">@lang('app.no_data')</td></tr>
						@endif
						</tbody>
						<thead>
						<tr>
							@if (isset($adminView))
								<th>이름(아이디)</th>
							@endif
							<th>@lang('app.ip')</th>
							<th>@lang('app.message')</th>
							<th>접속시간</th>
							<th>상세정보</th>
						</tr>
						</thead>
					</table>
				</div>
				{{ $activities->appends(Request::except('page'))->links() }}
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
				if( $('.activity_show').hasClass('collapsed-box') ){
					$.cookie('activity_show', '1');
				} else {
					$.removeCookie('activity_show');
				}
			});

			if( $.cookie('activity_show') ){
				$('.activity_show').removeClass('collapsed-box');
				$('.activity_show .btn-box-tool i').removeClass('fa-plus').addClass('fa-minus');
			}
		});
	</script>
@stop