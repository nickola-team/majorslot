@extends('backend.argon.layouts.app',[
        'parentSection' => 'customer',
        'elementName' => 'message-add'
    ])

@section('page-title', '쪽지발송')
@section('content')
<div class="container-fluid">
    <div class="row ">
        <div class="col-8">
            <div class="card h-100">
              <div class="card-header border-0" id="headingOne">
                    <div class="row align-items-center box">
                        <div class="col-8">
                            <h3 class="mb-0">쪽지발송</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{argon_route('argon.msg.store')}}" id="user-form" method="POST" >
                      <input type="hidden" id="ref_id" name="ref_id" value="{{$refmsg?$refmsg->id:0}}">
                      <input type="hidden" id="type" name="type" value="{{\Request::get('type')}}">
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

        <div class="col-4">
            <div class="card h-100">
              <div class="card-header border-0" id="headingOne">
                    <div class="row align-items-center box">
                        <div class="col-8">
                            <h3 class="mb-0">많이 쓰는 메시지</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                      <div class="pl-lg-4">
                        @include('backend.argon.msgtemp.partials.title')
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
    CKEDITOR.replace('content');
  });
  function addTemplate(data) {
    CKEDITOR.instances['content'].setData(data);
  }
</script>

@endpush