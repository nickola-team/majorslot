@extends('backend.argon.layouts.app',[
        'parentSection' => 'customer',
        'elementName' => 'message-list'
    ])

@section('page-title',  '쪽지')

@section('content')
<div class="container-fluid">
	<div class="row">
        <div class="col">
            <div class="card mt-4">
                <div class="card-header border-0">
                    <div class="pull-right">
					@if (auth()->user()->isInoutPartner())
						<a href="{{ argon_route('argon.msg.create') }}" class="btn btn-primary btn-sm">보내기</a>
					@endif
					</div>
                    <h3 class="mb-0">쪽지</h3>
                </div>
                <div class="table-responsive">
					<table class="table align-items-center table-flush">
						<thead class="thead-light">
						<tr>
							<th scope="col">회원아이디</th>
							<th scope="col">제목</th>
							<th scope="col">등록날짜</th>
							<th scope="col">읽은날짜</th>
							@if (auth()->user()->hasRole('admin'))
							<th scope="col">작성자</th>
							@endif
							<th scope="col"></th>
						</tr>
						</thead>
						<tbody>
						@if (count($msgs))
							@foreach ($msgs as $msg)
								@include('backend.argon.messages.partials.row')
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
