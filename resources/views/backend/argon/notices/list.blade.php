@extends('backend.argon.layouts.app',[
        'parentSection' => 'customer',
        'elementName' => 'notice-list'
    ])

@section('page-title', '공지관리')

@section('content')
<div class="container-fluid">
	<div class="row">
        <div class="col">
            <div class="card mt-4">
                <div class="card-header border-0">
                    <div class="pull-right">
						<a href="{{ argon_route('argon.notice.create') }}" class="btn btn-primary btn-sm">@lang('app.add')</a>
					</div>
                    <h3 class="mb-0">공지</h3>
                </div>
                <div class="table-responsive">
					<table class="table align-items-center table-flush">
						<thead class="thead-light">
							<tr>
								<th scope="col">제목</th>
								<th scope="col">등록날짜</th>
								<th scope="col">공지형식</th>
								<th scope="col">공지대상</th>
								<th scope="col">상태</th>
								@if (auth()->user()->hasRole('admin'))
								<th scope="col">작성자</th>
								@endif
								<th></th>
							</tr>
						</thead>
						<tbody>
							@if (count($notices))
								@foreach ($notices as $notice)
									@include('backend.argon.notices.partials.row')
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