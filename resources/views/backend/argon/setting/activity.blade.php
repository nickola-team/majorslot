@extends('backend.argon.layouts.app',[
        'parentSection' => 'setting',
        'elementName' => 'activity'
    ])

@section('page-title', '접속로그')

@section('content')

<div class="container-fluid">
    <!-- Search -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header border-0" id="headingOne">
                    <div class="row align-items-center box">
                        <div class="col-8">
                            <h3 class="mb-0">검색</h3>
                        </div>
                        <div class="col-4 text-right box-tools">
                            <a class="box-button" data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne"></a>
                        </div>
                    </div>
                </div>
                <hr class="my-1">
                <div id="collapseOne" class="collapse show">
                    <div class="card-body">
                        <form action="" method="GET" >
                            <div class="form-group row">
                                <div class="col-md-1">
                                </div>
                                <label for="player" class="col-md-2 col-form-label form-control-label text-center">아이디</label>
                                <div class="col-md-7">
									<input type="text" class="form-control" name="username" value="{{ Request::get('username') }}" placeholder="@lang('app.search_for_users')">
                                </div>
                                <div class="col-md-1">
                                </div>
                            </div>
							<div class="form-group row">
                                <div class="col-md-1">
                                </div>
                                <label for="player" class="col-md-2 col-form-label form-control-label text-center">@lang('app.message')</label>
                                <div class="col-md-7">
									<input type="text" class="form-control" name="search" value="{{ Request::get('search') }}" placeholder="">
                                </div>
                                <div class="col-md-1">
                                </div>
                            </div>
							<div class="form-group row">
                                <div class="col-md-1">
                                </div>
                                <label for="player" class="col-md-2 col-form-label form-control-label text-center">@lang('app.ip')</label>
                                <div class="col-md-7">
									<input type="text" class="form-control" name="ip" value="{{ Request::get('ip') }}" placeholder="">
                                </div>
                                <div class="col-md-1">
                                </div>
                            </div>
                                
                            <div class="form-group row">
                                <div class="col-md-1">
                                </div>
                                <button type="submit" class="btn btn-primary col-md-10">검색</button>
                                <div class="col-md-1">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<div class="row">
        <div class="col">
            <div class="card mt-4">
                <div class="card-header border-0">
                    <h3 class="mb-0">접속로그</h3>
                </div>
                <div class="table-responsive">
					<table class="table align-items-center table-flush">
						<thead class="thead-light">
							<tr>
								@if (isset($adminView))
									<th>아이디</th>
								@endif
								<th>@lang('app.ip')</th>
								<th>@lang('app.message')</th>
								<th>접속시간</th>
								<th>상세정보</th>
							</tr>
						</thead>
						<tbody class="list">
							@if (count($activities))
								@foreach ($activities as $activity)
									<tr>
										@if (isset($adminView))
											<td>
												@if (isset($user))
													{{ $activity->user->present()->username }}
												@else
													<a href="{{ argon_route('argon.activity.user', $activity->user_id) }}">{{ $activity->userdata->username }}</a>
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
								<tr><td colspan="@if (isset($adminView)) 5 @else 4 @endif">No Data</td></tr>
							@endif
						</tbody>
					</table>
				</div>
                <div class="card-footer py-4">
                    {{ $activities->withQueryString()->links('backend.argon.vendor.pagination.argon') }}
                </div>
            </div>
        </div>
    </div>
</div>
@stop
