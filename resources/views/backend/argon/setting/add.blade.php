@extends('backend.argon.layouts.app',[
        'parentSection' => 'setting',
        'elementName' => 'domain-add'
    ])

@section('page-title', '도메인추가')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col col-lg-6 m-auto">
            <div class="card">
              <div class="card-header border-0" id="headingOne">
                    <div class="row align-items-center box">
                        <div class="col-8">
                            <h3 class="mb-0">도메인추가</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{argon_route('argon.website.store')}}" id="user-form" method="POST" >
                      <div class="pl-lg-4">
                          @include('backend.argon.setting.partials.base', ['edit' => false])
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
