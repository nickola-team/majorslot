@extends('backend.argon.layouts.app',[
        'parentSection' => 'agent',
        'elementName' => 'agent-move'
    ])
@section('page-title',  '파트너 이동')

@section('content')
<div class="container-fluid">
<div class="row">
    <div class="col col-lg-8 m-auto">
        <div class="card">
            <div class="card-header border-0" id="headingOne">
                <div class="row align-items-center box">
                    <div class="col-8">
                        <h3 class="mb-0">파트너 이동</h3>
                    </div>
                </div>
            </div>
            <hr class="my-1">
            <div class="card-body">
                <form action="" method="POST"  id="form">
                    <input type="hidden" value="{{$user->id}}" name="user_id">
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            이 름
                        </div>
                        <div class="col-7">
                            {{$user->username}}
                            <p><span class="h5">{{$user->parents(auth()->user()->role_id)}}</span></p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            레 벨
                        </div>
                        <div class="col-7">
                            {{$user->role->description}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            현재 보유금
                        </div>
                        <div class="col-7">
                            @if ($user->hasRole('manager'))
                            {{number_format($user->shop->balance)}}
                            @else
                            {{number_format($user->balance)}}
                            @endif
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            이동할 상위파트너
                        </div>
                        <div class="col-7">
                            <input class="form-control col-8" type="text" value="" id="parent" name="parent">
                        </div>
                    </div>
                    <div class="form-group row">
                            <div class="col-6 text-center">
                                <button type="submit" class="btn btn-primary col-8" id="doSubmit">이동</button>
                            </div>
                            <div class="col-6 text-center">
                                <button type="button" class="btn btn-secondary col-8" onclick="window.history.back();">취소</button>
                            </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@stop
