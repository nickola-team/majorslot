@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '도메인관리')
@section('page-heading', '도메인관리')

@section('content')

	<section class="content-header">
		@include('backend.Default.partials.messages')
	</section>

	<section class="content">

		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">도메인관리</h3>
				<div class="pull-right box-tools">
					<a href="{{ route($admurl.'.website.create') }}" class="btn btn-block btn-primary btn-sm">@lang('app.add')</a>
				</div>
			</div>
			<div class="box-body">
				<table class="table table-bordered table-striped">
					<thead>
					<tr>
						<th>도메인</th>
						<th>제목</th>
						<th>페이지디자인</th>
						<th>관리자디자인</th>
                        <th>총본사</th>
						<th>작성날짜</th>
                        <th>편집</th>
					</tr>
					</thead>
					<tbody>
					@if (count($websites))
						@foreach ($websites as $website)
							@include('backend.Default.websites.partials.row')
						@endforeach
					@else
						<tr><td colspan="7">@lang('app.no_data')</td></tr>
					@endif
					</tbody>
					<thead>
					<tr>
						<th>도메인</th>
						<th>제목</th>
						<th>페이지디자인</th>
						<th>관리자디자인</th>
                        <th>총본사</th>
						<th>작성날짜</th>
                        <th>편집</th>
					</tr>
					</thead>
				</table>
			</div>
		</div>
	</section>

@stop
