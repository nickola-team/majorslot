@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', trans('app.add_happyhour'))
@section('page-heading', trans('app.add_happyhour'))

@section('content')

  <section class="content-header">
    @include('backend.Default.partials.messages')
  </section>

  <section class="content">
    <div class="box box-default">
      {!! Form::open(['route' => $admurl.'.happyhour.store']) !!}
      <div class="box-header with-border">
        <h3 class="box-title">@lang('app.add_happyhour')</h3>
      </div>

      <div class="box-body">
        <div class="row">
          @include('backend.Default.happyhours.partials.base', ['edit' => false])
        </div>
      </div>

      <div class="box-footer">
        <button type="submit" class="btn btn-primary">
          @lang('app.add_happyhour')
        </button>
      </div>
      {!! Form::close() !!}
    </div>
  </section>

@stop