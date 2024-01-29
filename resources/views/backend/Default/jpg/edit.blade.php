@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '잭팟편집')
@section('page-heading', $jackpot->title)

@section('content')

<section class="content-header">
@include('backend.Default.partials.messages')
</section>

    <section class="content">
      <div class="box box-default">
		{!! Form::open(['route' => array($admurl.'.jpgame.update', $jackpot->id), 'files' => true, 'id' => 'user-form']) !!}
        <div class="box-header with-border">
          <h3 class="box-title">잭팟편집</h3>
        </div>

        <div class="box-body">
          <div class="row">
            @include('backend.Default.jpg.partials.base', ['edit' => true])
          </div>
        </div>

        <div class="box-footer">
        <button type="submit" class="btn btn-primary">
          편집
        </button>

        </div>
		{!! Form::close() !!}
      </div>
    </section>

@stop