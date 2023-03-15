@extends('backend.argon.layouts.app',[
        'parentSection' => 'game',
        'elementName' => 'game-betlimit'
    ])
@section('page-title',  '에볼루션 배팅한도')
@section('content')
<div class="container-fluid">
@foreach ($betLimits as $tbllimit)
    <div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="mb-0">설정 [{{implode(',', $tbllimit['tableName'])}}]</h3>
            </div>
            <form action="" method="POST" >
                <input type="hidden" id="tableid" name="tableid" value="{{implode(',', $tbllimit['tableIds'])}}">
            <div class="table-responsive">
            <table class="table align-items-center table-flush" id="agentlist">
                <thead class="thead-light">
                    <tr>
                    <th scope="col" style="width:30%">항목</th>
                    <th scope="col">값</th>
                    </tr>
                </thead>
                <tbody class="list">
                    @foreach ($tbllimit['BetLimit'] as $k=>$v)
                        <tr>
                            <td>{{$k}}</td>
                            <td>
                                <input class="form-control" type="text" value="{{$v}}" id="{{$k}}" name="{{$k}}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            <hr class="my-1">
            <div class="form-group row mt-1 justify-content-center">
                <div class="col-6 text-center">
                <button type="submit" class="btn btn-primary">설정</button>
                </div>
            </div>
            </form>
        </div>
    </div>
    </div>
@endforeach
</div>
@stop