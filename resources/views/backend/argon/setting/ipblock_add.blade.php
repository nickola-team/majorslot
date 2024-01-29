@extends('backend.argon.layouts.app',[
        'parentSection' => 'setting',
        'elementName' => 'ipblock-add'
    ])

@section('page-title', 'IP추가')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col col-lg-6 m-auto">
            <div class="card">
              <div class="card-header border-0" id="headingOne">
                    <div class="row align-items-center box">
                        <div class="col-8">
                            <h3 class="mb-0">IP추가</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{argon_route('argon.ipblock.store')}}" id="user-form" method="POST" >
                      <div class="pl-lg-4">
                        <div class="form-group">
                            <label class="form-control-label" for="ip_address"><span class="text-red"> ***아이피가 여러개이면 반점(,)으로 구분해주세요***</span></label>
                            <textarea id="ip_address" name="ip_address" class="form-control" rows="5"></textarea>
                        </div>
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
