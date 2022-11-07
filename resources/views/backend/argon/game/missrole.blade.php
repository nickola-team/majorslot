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
                    <form action="{{argon_route('argon.game.missroleupdate')}}" method="POST" >
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="slot_total_deal" class="col-md-2 col-form-label form-control-label text-center">슬롯 총배팅수</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="slot_total_deal" value="{{$data['slot_total_deal']}}" placeholder="">
                            </div>
                            <label for="slot_total_miss" class="col-md-2 col-form-label form-control-label text-center">슬롯 공배팅수</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="slot_total_miss" value="{{$data['slot_total_miss']}}" placeholder="">
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="table_total_deal" class="col-md-2 col-form-label form-control-label text-center">라이브 총배팅수</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="table_total_deal" value="{{$data['table_total_deal']}}" placeholder="">
                            </div>
                            <label for="table_total_miss" class="col-md-2 col-form-label form-control-label text-center">라이브 공배팅수</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="table_total_miss" value="{{$data['table_total_miss']}}" placeholder="">
                            </div>
                            <div class="col-md-1">
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
        <div class="card">
            <div class="card-header border-0" id="headingOne">
                <div class="row align-items-center box">
                    <div class="col-8">
                        <h3 class="mb-0">검색</h3>
                    </div>
                    <div class="col-4 text-right box-tools">
                        <a class="box-button" data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne"></a>
                    </div>
                </div>
            </div>
            <hr class="my-1">
            <div id="collapseOne" class="collapse show">
                <div class="card-body">
                    <form action="{{argon_route('argon.game.missroleupdate')}}" >
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="slot_total_deal" class="col-md-2 col-form-label form-control-label text-center">매장이름</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="shopname" value="{{Request::get('shopname')??''}}" placeholder="">
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <button type="submit" class="btn btn-primary col-md-10">검색</button>
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
                    <th scope="col">매장이름</th>
                    <th scope="col">슬롯롤링%</th>
                    <th scope="col">라이브롤링%</th>
                    @if (auth()->user()->hasRole('admin'))
                    <th scope="col">슬롯난수</th>
                    <th scope="col">카지노난수</th>
                    @endif
                    <th scope="col">슬롯공배팅상태</th>
                    <th scope="col">라이브공배팅상태</th>
                    </tr>
                </thead>
                <tbody class="list">
                @if (count($shops) > 0)
                    @foreach ($shops as $shop)
                        <tr>
                            @include('backend.argon.game.partials.row_missrole')
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="5">{{__('No Data')}}</td></tr>
                @endif
                </tbody>
                </table>
        </div>
        <!-- Card footer -->
         <div class="card-footer py-4">
            {{ $shops->withQueryString()->links('backend.argon.vendor.pagination.argon') }}
        </div> 
        </div>
    </div>
    </div>

</div>
@stop