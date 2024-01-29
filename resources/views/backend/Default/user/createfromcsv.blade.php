@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', 'CSV로 생성')
@section('page-heading', 'CSV로 생성')

@section('content')

    <section class="content-header">
        @include('backend.Default.partials.messages')
    </section>

    <section class="content">
        <div class="box box-primary">
		@if ($ispartner == 1)
		{!! Form::open(['route' => $admurl.'.user.storepartnerfromcsv', 'id' => 'csv-upload-form', 'files' => true]) !!}
		@else
		{!! Form::open(['route' => $admurl.'.user.storeuserfromcsv', 'id' => 'csv-upload-form', 'files' => true]) !!}
		@endif
            <div class="box-header with-border">
                <h3 class="box-title">CSV로 {{$ispartner==1?'파트너/매장':'회원' }} 생성</h3>
            </div>
            <div class="box-body">
				<div class="row">
					<div class="col-md-6">
					<div class="form-group">
                        <label>CSV 업로드</label>
                        <input type="file" id="csvfile" name="csvfile">
					</div>
					</div>
				</div>
				@if (isset($data))
				<div class="row">
					<p>{{print_r($data)}}</p>
				</div>
				@endif
				<div class="box-footer">
					<button type="submit" class="btn btn-primary">
						업로드
					</button>
           		 </div>
            </div>
			{{ Form::close() }}
			@if (isset($msg))
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
					<p>{{$msg}}</p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
				<div class="form-group">
					@if (isset($fuser))
						@foreach($fuser as $user)
							<p>{{implode(',', $user)}}</p>
						@endforeach
					@endif
				</div>
				</div>
			</div>
			@endif
        </div>
    </section>

@stop
