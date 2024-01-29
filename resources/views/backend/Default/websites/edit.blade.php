@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '도메인편집')
@section('page-heading', '도메인편집')

@section('content')

<section class="content-header">
@include('backend.Default.partials.messages')
</section>

    <section class="content">
        {!! Form::open(['route' => array($admurl.'.website.update', $website->id),  'id' => 'user-form']) !!}
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">도메인편집</h3>
        </div>

        <div class="box-body">
          <div class="row">

            @include('backend.Default.websites.partials.base', ['edit' => true])

          </div>
        </div>

        <div class="box-footer">
            <button type="submit" class="btn btn-primary">
                수정
            </button>
            <a href="{{ route($admurl.'.website.delete', $website->id) }}"
            class="btn btn-danger"
            data-method="DELETE"
            data-confirm-title="@lang('app.please_confirm')"
            data-confirm-text="도메인을 삭제하시겠습니까?"
            data-confirm-delete="@lang('app.yes_delete_him')">
                도메인삭제
            </a>
        </div>
      </div>
            {!! Form::close() !!}
    </section>

@stop
@section('scripts')
@stop