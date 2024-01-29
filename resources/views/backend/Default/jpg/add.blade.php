@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', 'Add JPG')
@section('page-heading', 'Add JPG')

@section('content')

<section class="content-header">
@include('backend.Default.partials.messages')
</section>

    <section class="content">
      <div class="box box-default">
		{!! Form::open(['route' => $admurl.'.jpgame.store', 'files' => true, 'id' => 'user-form']) !!}
        <div class="box-header with-border">
          <h3 class="box-title">Add JPG</h3>
        </div>

        <div class="box-body">
          <div class="row">
            @include('backend.Default.jpg.partials.base', ['edit' => false])
          </div>
        </div>

        <div class="box-footer">
        <button type="submit" class="btn btn-primary">
          Add JPG
        </button>
        </div>
		{!! Form::close() !!}
      </div>
    </section>

@stop