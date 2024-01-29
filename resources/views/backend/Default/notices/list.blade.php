@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '공지관리')
@section('page-heading', '공지관리')

@section('content')

	<section class="content-header">
		@include('backend.Default.partials.messages')
	</section>

	<section class="content">

		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">공지관리</h3>
				<div class="pull-right box-tools">
					<a href="{{ route($admurl.'.notice.create') }}" class="btn btn-block btn-primary btn-sm">@lang('app.add')</a>
				</div>
			</div>
			<div class="box-body">
				<table class="table table-bordered table-striped">
					<thead>
					<tr>
						<th>제목</th>
						<th>등록날짜</th>
						<th>공지대상</th>
						<th>상태</th>
                        @if (auth()->user()->hasRole('admin'))
                        <th>작성자</th>
                        @endif
                        <th>편집</th>
					</tr>
					</thead>
					<tbody>
					@if (count($notices))
						@foreach ($notices as $notice)
							@include('backend.Default.notices.partials.row')
						@endforeach
					@else
						<tr><td colspan="4">@lang('app.no_data')</td></tr>
					@endif
					</tbody>
					<thead>
					<tr>
                        <th>제목</th>
						<th>등록날짜</th>
						<th>공지대상</th>
						<th>상태</th>
                        @if (auth()->user()->hasRole('admin'))
                        <th>작성자</th>
                        @endif
                        <th>편집</th>
					</tr>
					</thead>
				</table>
			</div>
		</div>
	</section>

@stop
