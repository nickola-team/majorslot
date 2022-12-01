@extends('backend.argon.layouts.app',[
        'parentSection' => 'customer',
        'elementName' => 'msgtemp-list'
    ])

@section('page-title', '템플릿관리')

@section('content')
<div class="container-fluid">
	<div class="row">
        <div class="col">
            <div class="card mt-4">
                <div class="card-header border-0">
                    <div class="pull-right">
						<a href="{{ argon_route('argon.msgtemp.create') }}" class="btn btn-primary btn-sm">@lang('app.add')</a>
					</div>
                    <h3 class="mb-0">메시지 템플릿</h3>
                </div>
                <div class="table-responsive">
					<table class="table align-items-center table-flush">
						<thead class="thead-light">
							<tr>
								<th scope="col">번호</th>
								<th scope="col">제목</th>
								<th scope="col">순서</th>
								<th scope="col">등록날짜</th>
								@if (auth()->user()->hasRole('admin'))
								<th scope="col">작성자</th>
								@endif
								<th></th>
							</tr>
						</thead>
						<tbody>
							@if (count($msgtemps))
								@foreach ($msgtemps as $msgtemp)
									@include('backend.argon.msgtemp.partials.row')
								@endforeach
							@else
								<tr><td colspan='5'>No Data</td></tr>
							@endif
						</tbody>
					</table>
				</div>
				<!-- Card footer -->
				<div class="card-footer py-4">
					{{ $msgtemps->withQueryString()->links('backend.argon.vendor.pagination.argon') }}
				</div>
			</div>
		</div>
	</div>
</div>
@stop