@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '공지편집')
@section('page-heading', '공지편집')

@section('content')

<section class="content-header">
@include('backend.Default.partials.messages')
</section>

    <section class="content">
        {!! Form::open(['route' => array($admurl.'.notice.update', $notice->id),  'id' => 'user-form']) !!}
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">공지편집</h3>
        </div>

        <div class="box-body">
          <div class="row">

            @include('backend.Default.notices.partials.base', ['edit' => true])

          </div>
        </div>

        <div class="box-footer">
            <button type="submit" class="btn btn-primary">
                수정
            </button>
            <a href="{{ route($admurl.'.notice.delete', $notice->id) }}"
            class="btn btn-danger"
            data-method="DELETE"
            data-confirm-title="@lang('app.please_confirm')"
            data-confirm-text="공지를 삭제하시겠습니까?"
            data-confirm-delete="@lang('app.yes_delete_him')">
                공지삭제
            </a>
        </div>
      </div>
            {!! Form::close() !!}
    </section>

@stop
@section('scripts')

<!-- FastClick -->
<script src="/back/bower_components/fastclick/lib/fastclick.js"></script>
<!-- CK Editor -->
<script src="/back/bower_components/ckeditor/ckeditor.js"></script>
<script>
  $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('content')
  })
</script>
@stop