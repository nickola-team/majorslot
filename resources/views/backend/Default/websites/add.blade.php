@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '도메인추가')
@section('page-heading', '도메인추가')

@section('content')

<section class="content-header">
@include('backend.Default.partials.messages')
</section>

    <section class="content">
            {!! Form::open(['route' => $admurl.'.website.store', 'id' => 'user-form']) !!}
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">도메인추가</h3>
        </div>

        <div class="box-body">
          <div class="row">

            @include('backend.Default.websites.partials.base', ['edit' => false])

          </div>
        </div>

        <div class="box-footer">
            <button type="submit" class="btn btn-primary">
                추가
            </button>
        </div>
      </div>
            {!! Form::close() !!}
    </section>

@stop
