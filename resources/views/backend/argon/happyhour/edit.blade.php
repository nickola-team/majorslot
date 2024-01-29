@extends('backend.argon.layouts.app',[
        'parentSection' => 'game',
        'elementName' => 'game-happyhour-edit'
    ])

@section('page-title', '콜 편집')


@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col col-lg-6 m-auto">
            <div class="card">
              <div class="card-header border-0" id="headingOne">
                    <div class="row align-items-center box">
                        <div class="col-8">
                            <h3 class="mb-0">콜 편집</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{argon_route('argon.happyhour.update', ['id'=>$happyhour->id])}}" id="user-form" method="POST" >
                      <div class="pl-lg-4">
                          @include('backend.argon.happyhour.partials.base', ['edit' => true])
                        <div class="text-center">
                          <button type="submit" class="btn btn-primary mt-4 col-6">수정</button>
                        </div>
                      </div>
                    </from>
                </div>
            </div>
        </div>
    </div>
</div>

@stop