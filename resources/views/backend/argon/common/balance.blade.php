@extends('backend.argon.layouts.app')
@section('page-title',  $type=='add'?'충전':'환전')

@section('content')
<div class="container-fluid">
<div class="row">
    <div class="col col-lg-8 m-auto">
        <div class="card">
            <div class="card-header border-0" id="headingOne">
                <div class="row align-items-center box">
                    <div class="col-8">
                        <h3 class="mb-0">{{$type=='add'?'충전':'환전'}}</h3>
                    </div>
                </div>
            </div>
            <hr class="my-1">
            <div class="card-body">
                <form action="" method="GET" >
                    <div class="d-flex align-items-center">
                    <div class="text-center w-5">
                    <i class="fas fa-desktop text-lg opacity-6" aria-hidden="true"></i>
                    </div>
                    <div class="my-auto ms-3">
                    <div class="h-100">
                        <p class="text-sm mb-1">
                        Bucharest 68.133.163.201
                        </p>
                        <p class="mb-0 text-xs">
                        Your current session
                        </p>
                    </div>
                    </div>
                    <span class="badge badge-success badge-sm my-auto ms-auto me-3">Active</span>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@stop
