@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', trans('app.happyhours'))
@section('page-heading', trans('app.happyhours'))

@section('content')

	<section class="content-header">
		@include('backend.Default.partials.messages')
	</section>

	<section class="content">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">@lang('app.happyhours') - 현재 @lang('app.time') {{ \Carbon\Carbon::now()->format('H:i:s') }}</h3>
				@permission('happyhours.add')
				<div class="pull-right box-tools">
					<a href="{{ route($admurl.'.happyhour.create') }}" class="btn btn-block btn-primary btn-sm">@lang('app.add')</a>
				</div>
				@endpermission
			</div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
					<thead>
					<tr>
						<th>@lang('app.id')</th>
						<th>회원아이디</th>
						<th>총 당첨금</th>
						<th>남은당첨금</th>
						<th>오버된당첨금</th>
						<th>잭팟기능</th>
						<th>@lang('app.time')</th>
						<th>@lang('app.status')</th>
					</tr>
					</thead>
					<tbody>
					@if (count($happyhours))
						@foreach ($happyhours as $happyhour)
							@include('backend.Default.happyhours.partials.row')
						@endforeach
					@else
						<tr><td colspan="6">@lang('app.no_data')</td></tr>
					@endif
					</tbody>
					<thead>
					<tr>
					<th>@lang('app.id')</th>
						<th>회원아이디</th>
						<th>총 당첨금</th>
						<th>남은당첨금</th>
						<th>오버된당첨금</th>
						<th>잭팟기능</th>
						<th>@lang('app.time')</th>
						<th>@lang('app.status')</th>
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
	</script>
@stop