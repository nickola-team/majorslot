@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', trans('app.categories'))
@section('page-heading', trans('app.categories'))

@section('content')

	<section class="content-header">
		@include('backend.Default.partials.messages')
	</section>

	<section class="content">
		<div class="box box-danger shops_show">
			<div class="box-body">
				<form id="change-shop-form"  name = "set_shop" action="{{ route('backend.profile.setshop') }}" method="POST">
					<div class="col-md-6">
						<div class="form-group">
							<label>매장</label>
							{!! Form::select('shop_id',
								[0=>'기본매장']+Auth::user()->shops_array(), Auth::user()->shop_id, ['class' => 'form-control', 'style' => 'width: 100%;', 'id' => 'shop_id']) !!}
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
						</div>
					</div>
				</form>	
			</div>
		</div>

		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">@lang('app.categories')</h3>
				@permission('categories.add')
				<div class="pull-right box-tools">
					<a href="{{ route('backend.category.create') }}" class="btn btn-block btn-primary btn-sm">@lang('app.add')</a>
				</div>
				@endpermission
			</div>
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
						<thead>
						<tr>
							<th>@lang('app.name')</th>
							<th>표시순서</th>
							<th>링크</th>
							{{-- <th>@lang('app.count')</th> --}}
							<th>갯수</th>
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
							<th>@lang('app.name')</th>
							<th>@lang('app.position')</th>
							<th>@lang('app.href')</th>
							{{-- <th>@lang('app.count')</th> --}}
							<th>갯수</th>
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