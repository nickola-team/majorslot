@extends('backend.argon.layouts.app',
    [
        'parentSection' => 'game',
        'elementName' => 'game-happyhour'
    ])
@section('page-title',  '유저 콜')

@section('content')

<div class="container-fluid">
<div class="row">
<div class="col">
    <div class="card mt-4">
    <!-- Light table -->
    <!-- Card header -->
    <div class="card-header border-0">
		<div class="pull-right">
			<a href="{{ argon_route('argon.happyhour.create') }}" class="btn btn-primary btn-sm">새로추가</a>
		</div>
        <h3 class="mb-0">유저 콜 목록</h3>
    </div>
    <div class="table-responsive">
            <table class="table align-items-center table-flush" id="agentlist">
            <thead class="thead-light">
                <tr>
                <th>번호</th>
                <th>작성자</th>
				<th>회원아이디</th>
				<th>총 당첨금</th>
				<th>남은당첨금</th>
				<th>오버된당첨금</th>
				<!-- <th>잭팟기능</th> -->
                <th>작성시간</th>
                <th>완료시간</th>
				<th>상태</th>
				<th></th>
                </tr>
            </thead>
            <tbody class="list">
					@if (count($happyhours))
						@foreach ($happyhours as $happyhour)
							@include('backend.argon.happyhour.partials.row')
						@endforeach
					@else
						<tr><td colspan="6">{{__('No Data')}}</td></tr>
					@endif
            </tbody>
            </table>
    </div>
    <!-- Card footer -->
    <div class="card-footer py-4">
        {{ $happyhours->withQueryString()->links('backend.argon.vendor.pagination.argon') }}
    </div>
    </div>
</div>
</div>
@stop

