@extends('backend.argon.layouts.app',[
        'parentSection' => 'setting',
        'elementName' => 'ipblock-list'
    ])

@section('page-title', 'IP차단관리')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col">
			<div class="card">
				<div class="card-header border-0" id="headingOne">
					<div class="row align-items-center box">
						<div class="col-8">
							<h3 class="mb-0">검색</h3>
						</div>
						<div class="col-4 text-right box-tools">
							<a class="box-button" data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne"></a>
						</div>
					</div>
				</div>
				<hr class="my-1">
				<div id="collapseOne" class="collapse show">
					<div class="card-body">
						<form action="" method="GET" >
							<div class="form-group row">
								<div class="col-md-1">
								</div>
								<label for="partner" class="col-md-1 col-form-label form-control-label text-center">파트너</label>
								<div class="col-md-4">
									<input class="form-control" type="text" value="{{Request::get('partner')}}" id="partner" name="partner">
								</div>
								<label for="ip" class="col-md-1 col-form-label form-control-label text-center">IP</label>
								<div class="col-md-4">
									<input class="form-control" type="text" value="{{Request::get('ip')}}" id="ip" name="ip">
								</div>
								<div class="col-md-1">
								</div>
							</div>
							<div class="form-group row">
								<div class="col-md-1">
								</div>
								<button type="submit" class="btn btn-primary col-md-10">검색</button>
								<div class="col-md-1">
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
        <div class="col">
            <div class="card mt-4">
                <div class="card-header border-0">
					@if (auth()->user()->isInoutPartner())
                    <div class="pull-right">
						<a href="{{ argon_route('argon.ipblock.add') }}" class="btn btn-primary btn-sm">@lang('app.add')</a>
					</div>
					@endif
                    <h3 class="mb-0">IP차단목록</h3>
                </div>
                <div class="table-responsive">
					<table class="table align-items-center table-flush">
						<thead class="thead-light">
							<tr>
								<th scope="col">아이디</th>
								<th scope="col">파트너</th>
								<th scope="col" style="width:50%">IP</th>
								<th scope="col"></th>
							</tr>
						</thead>
						<tbody>
							@if (count($ipblocks))
								@foreach ($ipblocks as $ipblock)
									@include('backend.argon.setting.partials.ipblock_row')
								@endforeach
							@else
							<tr><td colspan='4'>No Data</td></tr>
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@stop
