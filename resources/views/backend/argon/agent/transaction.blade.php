@extends('backend.argon.layouts.app')
@section('page-title',  '에이전트 지급내역')
@section('content-header')
<div class="row">
    <div class="col-xl-3 col-lg-3">
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h3 class="card-title text-uppercase text-muted mb-0 text-green">충전</h3>
                        <span class="h2 font-weight-bold mb-0 text-green">{{number_format($total['add'])}}</span>
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
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h3 class="card-title text-uppercase text-muted mb-0  text-red">환전</h3>
                        <span class="h2 font-weight-bold mb-0  text-red">{{number_format($total['out'])}}</span>
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
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h3 class="card-title text-uppercase text-muted mb-0  text-red">롤링전환</h3>
                        <span class="h2 font-weight-bold mb-0  text-red">{{number_format($total['out'])}}</span>
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
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h3 class="card-title text-uppercase text-muted mb-0  text-red">벳윈전환</h3>
                        <span class="h2 font-weight-bold mb-0  text-red">{{number_format($total['out'])}}</span>
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
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">검색</h3>
                    </div>
                    <div class="col-4 text-right">
                        <a  data-toggle="collapse" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne"><i class="fa fa-plus"></i></a>
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
                            <label for="name" class="col-md-2 col-form-label form-control-label">에이전트</label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" value="{{Request::get('name')}}" id="name" name="name">
                            </div>

                            <label for="admin" class="col-md-2 col-form-label form-control-label">지급자</label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" value="{{Request::get('admin')}}" id="admin"  name="admin">
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="type" class="col-md-2 col-form-label form-control-label">타입</label>
                            <div class="col-md-3">
                                <select class="form-control" id="type" name="type">
                                    <option value="" @if (Request::get('type') == '') selected @endif>@lang('app.all')</option>
									<option value="add" @if (Request::get('type') == 'add') selected @endif>충전</option>
									<option value="out" @if (Request::get('type') == 'out') selected @endif>환전</option>
									<option value="deal_out" @if (Request::get('type') == 'deal_out') selected @endif>딜비전환</option>
									<option value="ggr_out" @if (Request::get('type') == 'ggr_out') selected @endif>죽장전환</option>
                                </select>
                            </div>
                            <label for="mode" class="col-md-2 col-form-label form-control-label">방식</label>
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
                            <label for="dates" class="col-md-2 col-form-label form-control-label">처리시간</label>
                            <div class="col-md-2">
                            <input class="form-control" type="datetime-local" value="{{Request::get('dates')??date('Y-m-01\T00:00')}}" id="dates" name="dates">
                            </div>
                            <label for="dates" class="col-form-label form-control-label" >~</label>
                            <div class="col-md-2">
                            <input class="form-control" type="datetime-local" value="{{Request::get('dates')??date('Y-m-d\TH:i')}}" id="dates" name="dates">
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
        <h3 class="mb-0">에이전트 지급내역</h3>
    </div>
    <div class="table-responsive">
            <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <tr>
                <th scope="col">번호</th>
                <th scope="col">에이전트</th>
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
                    <tr><td colspan="7">{{__('No Data')}}</td></tr>
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