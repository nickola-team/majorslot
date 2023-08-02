@extends('backend.argon.layouts.app',[
        'parentSection' => 'dw',
        'elementName' => 'dw-history'
    ])
@section('page-title',  '충환전 내역')
@section('content-header')
<div class="row">
    <div class="col-xl-3 col-lg-3">
        <div class="card card-stats  mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col ">
                        <h3 class="card-title text-success mb-0 ">충전합계</h3>
                        <span class="h2 font-weight-bold mb-0 text-success">{{number_format($total['add'])}}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3">
        <div class="card card-stats  mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col ">
                        <h3 class="card-title text-warning mb-0 ">환전합계</h3>
                        <span class="h2 font-weight-bold mb-0 text-warning">{{number_format($total['out'])}}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</div>
@endsection
@section('content')
<div class="container-fluid">
    <!-- Search -->
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
                    <form action="" method="GET" >
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="user" class="col-md-2 col-form-label form-control-label text-center">신청자이름</label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" value="{{Request::get('user')}}" id="user" name="user">
                            </div>
                            <label for="type" class="col-md-2 col-form-label form-control-label text-center">타입</label>
                            <div class="col-md-3">
                                <select class="form-control" id="type" name="type">
                                    <option value="" @if (Request::get('type') == '') selected @endif>@lang('app.all')</option>
									<option value="add" @if (Request::get('type') == 'add') selected @endif>충전</option>
									<option value="out" @if (Request::get('type') == 'out') selected @endif>환전</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="partner" class="col-md-2 col-form-label form-control-label text-center">파트너아이디</label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" value="{{Request::get('partner')}}" id="partner" name="partner">
                            </div>
                            <label for="role" class="col-md-2 col-form-label form-control-label text-center">파트너 레벨</label>
                            <div class="col-md-3">
                                <select class="form-control" id="role" name="role">
                                    <option value="" @if (Request::get('role') == '') selected @endif>@lang('app.all')</option>
                                    @for ($level=3;$level<=auth()->user()->role_id;$level++)
                                    <option value="{{$level}}" @if (Request::get('role') == $level) selected @endif> {{\VanguardLTE\Role::find($level)->description}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="dates" class="col-md-2 col-form-label form-control-label text-center">처리시간</label>
                            <div class="col-md-2">
                            <input class="form-control" type="datetime-local" value="{{Request::get('dates')[0]??date('Y-m-d\T00:00')}}" id="dates" name="dates[]">
                            </div>
                            <label for="dates" class="col-form-label form-control-label" >~</label>
                            <div class="col-md-2">
                            <input class="form-control" type="datetime-local" value="{{Request::get('dates')[1]??date('Y-m-d\TH:i')}}" id="dates" name="dates[]">
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
        <h3 class="mb-0">충환전내역</h3>
    </div>
    <div class="table-responsive">
            <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <tr>
                <th scope="col">번호</th>
                <th scope="col">신청자</th>
                <th scope="col">변동전 금액</th>
                <th scope="col">변동후 금액</th>
                <th scope="col">신청금액</th>
                <th scope="col">타입</th>
                <th scope="col">거래계좌</th>
                <th scope="col">신청 시간</th>
                <th scope="col">처리 시간</th>
                <th scope="col">상태</th>
                </tr>
            </thead>
            <tbody class="list">
                @if (count($in_out_logs) > 0)
                    @foreach ($in_out_logs as $stat)
                        <tr>
                            @include('backend.argon.dw.partials.row_history')
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="10">{{__('No Data')}}</td></tr>
                @endif
            </tbody>
            </table>
    </div>
    <!-- Card footer -->
    <div class="card-footer py-4">
        {{ $in_out_logs->withQueryString()->links('backend.argon.vendor.pagination.argon') }}
    </div>
    </div>
</div>
</div>
@stop