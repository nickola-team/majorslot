@extends('backend.argon.layouts.app',[
        'parentSection' => 'setting',
        'elementName' => 'domain-list'
    ])

@section('page-title', '도메인관리')

@section('content')
<div class="container-fluid">
	<div class="row">
        <div class="col">
            <div class="card mt-4">
                <div class="card-header border-0">
                    <div class="pull-right">
						<a href="{{ argon_route('argon.website.create') }}" class="btn btn-primary btn-sm">@lang('app.add')</a>
					</div>
                    <h3 class="mb-0">도메인</h3>
                </div>
                <div class="table-responsive">
					<table class="table align-items-center table-flush">
						<thead class="thead-light">
							<tr>
								<th scope="col">아이디</th>
								<th scope="col">도메인</th>
								<th scope="col">제목</th>
								<th scope="col">페이지디자인</th>
								<th scope="col">관리자디자인</th>
								<th scope="col">총본사</th>
								<th scope="col">작성날짜</th>
								<th scope="col"></th>
							</tr>
						</thead>
						<tbody>
							@if (count($websites))
								@foreach ($websites as $website)
									@include('backend.argon.setting.partials.row')
								@endforeach
							@else
							<tr><td colspan='9'>No Data</td></tr>
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@stop
