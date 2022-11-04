@extends('backend.argon.layouts.app',[
        'parentSection' => 'game',
        'elementName' => 'game-transaction'
    ])
@section('page-title',  '공배팅관리')

@section('content')
<div class="container-fluid">
    <!-- Search -->
    <div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header border-0" id="headingOne">
                <div class="row align-items-center box">
                    <div class="col-8">
                        <h3 class="mb-0">설정</h3>
                    </div>
                    <div class="col-4 text-right box-tools">
                        <a class="box-button" data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne"></a>
                    </div>
                </div>
            </div>
            <hr class="my-1">
            <div id="collapseOne" class="collapse show">
                <div class="card-body">
                    <form action="{{argon_route('argon.game.banksetting')}}" method="POST" >
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="minslot" class="col-md-2 col-form-label form-control-label text-center">슬롯 총배팅수</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="minslot" value="" placeholder="">
                            </div>
                            <label for="maxslot" class="col-md-2 col-form-label form-control-label text-center">슬롯 공배팅수</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="maxslot" value="" placeholder="">
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="minbonus" class="col-md-2 col-form-label form-control-label text-center">카지노 총배팅수</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="minbonus" value="" placeholder="">
                            </div>
                            <label for="maxbonus" class="col-md-2 col-form-label form-control-label text-center">카지노 공배팅수</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="maxbonus" value="" placeholder="">
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>   
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="times" class="col-md-2 col-form-label form-control-label text-center">공배팅 적용시간</label>
                            <div class="col-md-2">
                            <input class="form-control" type="time" value="{{Request::get('times')[0]??date('H:i:s', strtotime('-1 hours'))}}" id="times" name="times[]">
                            </div>
                            <label for="times" class="col-form-label form-control-label" >~</label>
                            <div class="col-md-2">
                            <input class="form-control" type="time" value="{{Request::get('times')[1]??date('H:i:s')}}" id="times" name="times[]">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <button type="submit" class="btn btn-primary col-md-10">설정</button>
                            <div class="col-md-1">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="row">
    <div class="col">
        <div class="card mt-4">
        <!-- Light table -->
        <!-- Card header -->
        <div class="card-header border-0">
            <h3 class="mb-0">매장별 설정</h3>
        </div>
        <div class="table-responsive">
                <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                    <th scope="col">번호</th>
                    <th scope="col">에이전트이름</th>
                    <th scope="col">슬롯롤링%</th>
                    <th scope="col">라이브롤링%</th>
                    <th scope="col"></th>
                    </tr>
                </thead>
                <tbody class="list">
                    <tr><td colspan="5">{{__('No Data')}}</td></tr>
                </tbody>
                </table>
        </div>
        <!-- Card footer -->
         <div class="card-footer py-4">
        </div> 
        </div>
    </div>
    </div>

</div>
@stop