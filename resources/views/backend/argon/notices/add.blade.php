@extends('backend.argon.layouts.app',[
        'parentSection' => 'customer',
        'elementName' => 'notice-add'
    ])

@section('page-title', '공지추가')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col col-lg-6 m-auto">
            <div class="card">
              <div class="card-header border-0" id="headingOne">
                    <div class="row align-items-center box">
                        <div class="col-8">
                            <h3 class="mb-0">공지추가</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{argon_route('argon.notice.store')}}" id="user-form" method="POST" >
                      <div class="pl-lg-4">
                          @include('backend.argon.notices.partials.base', ['edit' => false])
                        <div class="text-center">
                          <button type="submit" class="btn btn-primary mt-4 col-6">추가</button>
                        </div>
                      </div>
                    </from>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@push('js')
<!-- CK Editor -->
<script src="/back/bower_components/ckeditor/ckeditor.js"></script>
<script>
  $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('content')
  })
</script>

@endpush