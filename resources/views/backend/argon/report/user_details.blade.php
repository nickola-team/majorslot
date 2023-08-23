@extends('backend.argon.layouts.app',[
        'parentSection' => 'report',
        'elementName' => 'report-game'
    ])
@section('page-title',  '유저일별정산')

@push('css')
<link type="text/css" href="{{ asset('back/argon') }}/css/jquery.treetable.css" rel="stylesheet">
<link type="text/css" href="{{ asset('back/argon') }}/css/jquery.treetable.theme.default.css" rel="stylesheet">
@endpush

@section('content-header')
<div class="row">
    <div class="col-xl-3 col-lg-3">
        <div class="card card-stats  mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col ">
                        <h3 class="card-title text-success mb-0 ">배팅 합계</h3>
                        <span class="h2 font-weight-bold mb-0 text-success">{{number_format($totalsummary['totalbet'])}}</span>
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
                        <h3 class="card-title text-warning mb-0 ">당첨 합계</h3>
                        <span class="h2 font-weight-bold mb-0 text-warning">{{number_format($totalsummary['totalwin'])}}</span>
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
                        <h3 class="card-title text-primary mb-0 ">벳윈 합계</h3>
                        <span class="h2 font-weight-bold mb-0 text-primary">{{number_format($totalsummary['totalbet'] - $totalsummary['totalwin'])}}</span>
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
                                <input class="form-control" type="hidden" value="{{Request::get('user_id')}}" id="user_id" name="user_id">
                                </div>
                                <label for="dates" class="col-md-2 col-form-label form-control-label text-center">기간선택</label>
                                <div class="col-md-2">
                                <input class="form-control" type="date" value="{{Request::get('dates')[0]??date('Y-m-d')}}" id="dates" name="dates[]">
                                </div>
                                <label for="dates" class="col-form-label form-control-label" >~</label>
                                <div class="col-md-2">
                                <input class="form-control" type="date" value="{{Request::get('dates')[1]??date('Y-m-d')}}" id="dates" name="dates[]">
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
                <div class="card-header border-0">
                    <h3 class="mb-0">유저일별정산</h3>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="gamelist">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">날짜</th>
                                <th scope="col">아이디</th>
                                <th scope="col">매장</th>
                                <th scope="col">카지노배팅금</th>
                                <th scope="col">카지노당첨금</th>
                                <th scope="col">카지노벳윈</th>
                                <th scope="col">슬롯배팅금</th>
                                <th scope="col">슬롯당첨금</th>
                                <th scope="col">슬롯벳윈</th>
                                <th scope="col">파워볼배팅금</th>
                                <th scope="col">파워볼당첨금</th>
                                <th scope="col">파워볼벳윈</th>
                                <th scope="col">합계배팅금</th>
                                <th scope="col">합계당첨금</th>
                                <th scope="col">합계벳윈</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @if (count($dailyinfos) > 0)
                                @foreach($dailyinfos as $static)
                                    <tr>
                                        <td>{{$static['date']}}</td>
                                        <td>{{$static['user']->username}}</td>
                                        <td>{{$static['user']->referral->username}}</td>
                                        <td>{{number_format($static['tablebet'])}}</td>
                                        <td>{{number_format($static['tablewin'])}}</td>
                                        <td>
                                            @if($static['tablebet'] > $static['tablewin'])
                                            <span class='text-green'>{{number_format($static['tablebet'] - $static['tablewin'])}}</span>
                                            @else
                                            <span class='text-red'>{{number_format($static['tablebet'] - $static['tablewin'])}}</span>
                                            @endif
                                        </td>
                                        <td>{{number_format($static['slotbet'])}}</td>
                                        <td>{{number_format($static['slotwin'])}}</td>
                                        <td>
                                            @if($static['slotbet'] > $static['slotwin'])
                                            <span class='text-green'>{{number_format($static['slotbet'] - $static['slotwin'])}}</span>
                                            @else
                                            <span class='text-red'>{{number_format($static['slotbet'] - $static['slotwin'])}}</span>
                                            @endif
                                        </td>
                                        <td>{{number_format($static['pballbet'])}}</td>
                                        <td>{{number_format($static['pballwin'])}}</td>
                                        <td>
                                            @if($static['pballbet'] > $static['pballwin'])
                                            <span class='text-green'>{{number_format($static['pballbet'] - $static['pballwin'])}}</span>
                                            @else
                                            <span class='text-red'>{{number_format($static['pballbet'] - $static['pballwin'])}}</span>
                                            @endif
                                        </td>
                                        <td>{{number_format($static['allbet'])}}</td>
                                        <td>{{number_format($static['allwin'])}}</td>
                                        <td>
                                            @if($static['allbet'] > $static['allwin'])
                                            <span class='text-green'>{{number_format($static['allbet'] - $static['allwin'])}}</span>
                                            @else
                                            <span class='text-red'>{{number_format($static['allbet'] - $static['allwin'])}}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan="15">{{__('No Data')}}</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
