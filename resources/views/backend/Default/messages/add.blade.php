@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '쪽지발송')
@section('page-heading', '쪽지발송')

@section('content')

<section class="content-header">
@include('backend.Default.partials.messages')
</section>

    <section class="content">
            {!! Form::open(['route' => $admurl.'.msg.store', 'id' => 'user-form']) !!}
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">쪽지발송</h3>
        </div>

        <div class="box-body">
          <div class="row">

            @include('backend.Default.messages.partials.base', ['edit' => false])

          </div>
        </div>

        <div class="box-footer">
            <button type="submit" class="btn btn-primary">
                보내기
            </button>
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