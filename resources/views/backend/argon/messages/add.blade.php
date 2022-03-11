@extends('backend.argon.layouts.app')

@section('page-title', '쪽지발송')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col col-lg-6 m-auto">
            <div class="card">
              <div class="card-header border-0" id="headingOne">
                    <div class="row align-items-center box">
                        <div class="col-8">
                            <h3 class="mb-0">쪽지발송</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{argon_route('argon.msg.store')}}" id="user-form" method="POST" >
                      <div class="pl-lg-4">
                        @include('backend.argon.messages.partials.base', ['edit' => false])
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary mt-4 col-6">보내기</button>
                        </div>
                      </div>
                    </from>
                </div>
            </div>
        </div>
    </div>
</div>

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