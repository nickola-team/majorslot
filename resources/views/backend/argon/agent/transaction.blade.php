@extends('backend.argon.layouts.app',[
        'parentSection' => 'agent',
        'elementName' => 'agent-transaction'
    ])
@section('page-title',  '파트너 지급내역')
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

    <div class="col-xl-3 col-lg-3">
        <div class="card card-stats  mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col ">
                        <h3 class="card-title text-primary mb-0 ">롤링전환합계</h3>
                        <span class="h2 font-weight-bold mb-0 text-primary">{{number_format($total['deal_out'])}}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                            <i class="fas fa-chart-area"></i>
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
                        <h3 class="card-title text-info mb-0 ">벳윈전환합계</h3>
                        <span class="h2 font-weight-bold mb-0 text-info">{{number_format($total['ggr_out'])}}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                            <i class="fas fa-chart-line"></i>
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
                            <label for="user" class="col-md-2 col-form-label form-control-label text-center">파트너이름</label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" value="{{Request::get('user')}}" id="user" name="user">
                            </div>

                            <label for="admin" class="col-md-2 col-form-label form-control-label text-center">지급자이름</label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" value="{{Request::get('admin')}}" id="admin"  name="admin">
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="type" class="col-md-2 col-form-label form-control-label text-center">타입</label>
                            <div class="col-md-3">
                                <select class="form-control" id="type" name="type">
                                    <option value="" @if (Request::get('type') == '') selected @endif>@lang('app.all')</option>
									<option value="add" @if (Request::get('type') == 'add') selected @endif>충전</option>
									<option value="out" @if (Request::get('type') == 'out') selected @endif>환전</option>
									<option value="deal_out" @if (Request::get('type') == 'deal_out') selected @endif>롤링전환</option>
									<option value="ggr_out" @if (Request::get('type') == 'ggr_out') selected @endif>벳윈전환</option>
                                </select>
                            </div>
                            <label for="mode" class="col-md-2 col-form-label form-control-label text-center">방식</label>
                            <div class="col-md-3">
                                <select class="form-control" id="mode" name="mode">
                                    <option value="" @if (Request::get('mode') == '') selected @endif>@lang('app.all')</option>
									<option value="manual" @if (Request::get('mode') == 'manual') selected @endif>수동</option>
									<option value="account" @if (Request::get('mode') == 'account') selected @endif>계좌</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-1">
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
                        </div>
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="dates" class="col-md-2 col-form-label form-control-label text-center">처리시간</label>
                            <div class="col-md-2">
                            <input class="form-control" type="datetime-local" value="{{Request::get('dates')[0]??date('Y-m-01\T00:00')}}" id="dates" name="dates[]">
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
        <h3 class="mb-0">파트너 지급내역</h3>
    </div>
    <div class="table-responsive">
            <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <tr>
                <th scope="col">번호</th>
                <th scope="col">파트너</th>
                <th scope="col">지급자</th>
                <th scope="col">지급자보유금</th>
                <th scope="col">변동전 금액</th>
                <th scope="col">변동후 금액</th>
                <th scope="col">금액</th>
                <th scope="col">타입</th>
                <th scope="col">방식</th>
                <th scope="col">처리 시간</th>
                </tr>
            </thead>
            <tbody class="list">
                @if (count($statistics) > 0)
                    @foreach ($statistics as $stat)
                        <tr>
                            @include('backend.argon.agent.partials.row_transaction')
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
        {{ $statistics->withQueryString()->links('backend.argon.vendor.pagination.argon') }}
    </div>
    </div>
</div>
</div>
@stop