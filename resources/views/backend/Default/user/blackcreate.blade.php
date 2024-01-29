@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '블랙리스트추가')
@section('page-heading', '블랙리스트추가')

@section('content')

    <section class="content-header">
        @include('backend.Default.partials.messages')
    </section>

    <section class="content">
        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title">블랙리스트 추가</h3>
            </div>
			{!! Form::open(['route' => $admurl.'.black.store', 'id' => 'csv-upload-form']) !!}

            <div class="box-body">
				@include('backend.Default.user.partials.blackbase', ['edit'=>false])
				<div class="box-footer">
					<button type="submit" class="btn btn-primary">
						추가
					</button>
           		 </div>
            </div>
			{{ Form::close() }}
        </div>
    </section>

@stop
