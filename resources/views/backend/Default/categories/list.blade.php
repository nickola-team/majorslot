@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '게임제공사관리')
@section('page-heading', '게임제공사관리')

@section('content')

	<section class="content-header">
		@include('backend.Default.partials.messages')
	</section>

	<section class="content">

		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">게임제공사목록</h3>
			</div>
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
						<thead>
						<tr>
							<th>도메인</th>
							<th>이름</th>
							<th>표시순서</th>
							<th>상태</th>
							<th>상태변경</th>
							
						</tr>
						</thead>
						<tbody>
						@if (count($categories))
							@foreach ($categories as $category)
								@include('backend.Default.categories.partials.row', ['base' => true])
								@foreach ($category->inner as $category)
									@include('backend.Default.categories.partials.row', ['base' => false])
								@endforeach
							@endforeach
						@else
							<tr><td colspan="4">@lang('app.no_data')</td></tr>
						@endif
						</tbody>
						<thead>
						<tr>
							<th>도메인</th>
							<th>이름</th>
							<th>표시순서</th>
							<th>상태</th>
							<th>상태변경</th>
						</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</section>

@stop

@section('scripts')
	<script>
		$('#categories-table').dataTable();
		$("#view").change(function () {
			$("#users-form").submit();
		});
		$("#shop_id").change(function () {
			$("#change-shop-form").submit();
		});
	</script>
@stop