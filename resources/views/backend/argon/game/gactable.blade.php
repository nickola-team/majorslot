@extends('backend.argon.layouts.app',[
        'parentSection' => 'game',
        'elementName' => 'game-betlimit'
    ])
@section('page-title',  '에볼루션 테이블설정')

@section('content')
<div class="container-fluid">
    <!-- Search -->
    <div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="mb-0">설정</h3>
            </div>
            <div class="table-responsive">
            <table class="table align-items-center table-flush" id="agentlist">
                <thead class="thead-light">
                    <tr>
                    <th scope="col" style="width:30%">테이블이름</th>
                    <th scope="col">상태</th>
                    <th scope="col"></th>
                    </tr>
                </thead>
                <tbody class="list">
                    @foreach ($gactables as $table)
                        <tr>
                            <td>{{$table['title']}}</td>
                            @if ($table['view']==0)
                            <td>
                                <i class="bg-warning"></i>
                                <span class="text-red">정지</span>
                            </td>
                            <td>
                                <a href="{{argon_route('argon.game.gactable.update',['table'=>$table['gamecode'], 'view'=>1])}}" ><button class="btn btn-success btn-sm" >오픈</button></a>
                            </td>
                            @else
                            <td>
                                <i class="bg-success"></i>
                                <span class="text-success">오픈</span>
                            </td>
                            <td>
                            <a href="{{argon_route('argon.game.gactable.update',['table'=>$table['gamecode'], 'view'=>0])}}" ><button class="btn btn-warning btn-sm" >정지</button></a>
                            </td>
                            @endif

                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
    </div>
</div>
@stop