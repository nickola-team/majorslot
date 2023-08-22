@extends('backend.argon.layouts.app',[
        'parentSection' => 'report',
        'elementName' => 'report-dailydw'
    ])
@section('page-title',  '일별정산')

@push('css')
<link type="text/css" href="{{ asset('back/argon') }}/css/jquery.treetable.css" rel="stylesheet">
<link type="text/css" href="{{ asset('back/argon') }}/css/jquery.treetable.theme.default.css" rel="stylesheet">
@if (auth()->user()->hasRole('admin'))
<style>
    .bw-btn {
        display: none;
      }
    .bw-title:hover .bw-btn {
        display: inline-block;
      }
</style>
@endif
@endpush

@section('content-header')
<div class="row">
    <div class="col-xl-3 col-lg-3">
        <div class="card card-stats  mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col ">
                        <h3 class="card-title text-success mb-0 ">계좌충전 합계</h3>
                        <span class="h2 font-weight-bold mb-0 text-success">{{number_format($total['totalin'])}}</span>
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
                        <h3 class="card-title text-warning mb-0 ">계좌환전 합계</h3>
                        <span class="h2 font-weight-bold mb-0 text-warning">{{number_format($total['totalout'])}}</span>
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
                        <h3 class="card-title text-primary mb-0 ">수동충전 합계</h3>
                        <span class="h2 font-weight-bold mb-0 text-primary">{{number_format($total['moneyin'])}}</span>
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
                        <h3 class="card-title text-info mb-0 ">수동환전 합계</h3>
                        <span class="h2 font-weight-bold mb-0 text-info">{{number_format($total['moneyout'])}}</span>
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
                                <label for="player" class="col-md-2 col-form-label form-control-label text-center">파트너아이디</label>
                                <div class="col-md-3" style="display:flex;">
                                    <div class="col-md-8">
                                    <input class="form-control" type="text" value="{{Request::get('partner')}}" id="partner"  name="partner">
                                    </div>
                                    <div class="custom-control custom-checkbox mt-2">
                                    <input class="custom-control-input" id="includename" name="includename" type="checkbox" {{Request::get('includename')=='on'?'checked':''}}>   <label class="custom-control-label" for="includename">포함된이름</label>

                                    </div>
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
                    <h3 class="mb-0">일별정산</h3>
                </div>
                <div class="table-responsive">
                        <table class="table align-items-center table-flush" id="dailydwtotal">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">이름</th>
                                <th scope="col">기간내 합계</th>
                                <th scope="col">계좌충환전</th>
                                <th scope="col">수동충환전</th>
                                <th scope="col">롤링전환</th>
                                @if (auth()->user()->isInOutPartner())
                                <th scope="col">배팅/당첨금</th>
                                <th scope="col">공배팅/당첨금</th>
                                @else
                                <th scope="col">배팅/당첨금</th>
                                @endif
                                <th scope="col">죽장금</th>
                                <th scope="col">롤링금</th>
                                <th scope="col">보유금</th>
                            </tr>
                            </thead>
                            <tbody class="list">
                                @if ($total['id'] != '')
                                <tr data-tt-id="{{$total['user_id'] }}~{{$total['daterange']}}" data-tt-parent-id="{{$total['user_id']}}" data-tt-branch="{{$total['role_id']>3?'true':'false'}}">
                                @else
                                <tr >
                                @endif
                                    <td  >{{$total['id']}}</td>
                                    <td >{{$total['daterange']}}</td>
                                    <td ><ul>
                                        <li>
                                            <span class='text-green'>충전 : {{number_format($total['totalin'])}}</span>
                                        </li>
                                        <li>
                                            <span class='text-red'>환전 : {{number_format($total['totalout'])}}</span>
                                        </li>
                                        <li>
                                            <span >정산금 : {{number_format($total['totalin'] - $total['totalout'])}}</span>
                                        </li>
                                    </ul></td>
                                    <td ><ul>
                                        <li>
                                            <span class='text-green'>충전 : {{number_format($total['moneyin'])}}</span>
                                        </li>
                                        <li>
                                            <span class='text-red'>환전 : {{number_format($total['moneyout'])}}</span>
                                        </li>
                                        <li>
                                            <span >정산금 : {{number_format($total['moneyin'] - $total['moneyout'])}}</span>
                                        </li>
                                    </ul></td>
                                    <td >{{number_format($total['dealout'])}}</td>
                                    @if (auth()->user()->isInOutPartner())
                                    <td>
                                    @foreach ($total['betwin'] as $type => $bt)
                                        <div class="d-flex">

                                        <div class="d-flex" style="justify-content : center;align-items : center;">
                                                {{__($type)}}
                                        </div>
                                        <div class="d-flex flex-column justify-content-center" style="margin-left:1.6rem">
                                            <ul>
                                            <li>
                                                <span class='text-green'>배팅 : {{number_format($bt['totalbet'])}}</span>
                                            </li>
                                            <li>
                                                <span class='text-red'>당첨 : {{number_format($bt['totalwin'])}}</span>
                                            </li>
                                            <li>
<<<<<<< HEAD
                                            롤링금 : {{number_format($bt['total_deal']>0?$bt['total_deal']:$bt['total_mileage'])}}
                                            </li>
                                            <li>
                                            정산금 : {{number_format($bt['totalbet']-$bt['totalwin'] - ($bt['total_deal']>0?$bt['total_deal']:$bt['total_mileage']))}}
=======
                                            롤링금 : {{number_format($bt['total_mileage'])}}
                                            </li>
                                            <li>
                                            정산금 : {{number_format($bt['totalbet']-$bt['totalwin'] - $bt['total_mileage'])}}
>>>>>>> 6e42aae5246f761b007c8e7f726bd4c247a5c346
                                            </li>
                                            </ul>
                                        </div>
                                        
                                        </div>
                                        @if ($loop->index < count($total['betwin'])-1)
                                        <hr style="margin-top:0.5rem !important; margin-bottom:0.5rem !important;">
                                        @endif

                                    @endforeach
                                    </td>
                                    @endif
                                    <td>
                                    @foreach ($total['betwin'] as $type => $bt)
                                        <div class="d-flex">

                                        <div class="d-flex" style="justify-content : center;align-items : center;">
                                                {{__($type)}}
                                        </div>
                                        <div class="d-flex flex-column justify-content-center" style="margin-left:1.6rem">
                                            <ul>
                                            <li>
                                                <span class='text-green'>배팅 : {{number_format($bt['totaldealbet'])}}</span>
                                            </li>
                                            <li>
                                                <span class='text-red'>당첨 : {{number_format($bt['totaldealwin'])}}</span>
                                            </li>
                                            <li>
<<<<<<< HEAD
                                            롤링금 : {{number_format($bt['total_deal']>0?$bt['total_deal']:$bt['total_mileage'])}}
                                            </li>
                                            <li>
                                            정산금 : {{number_format($bt['totaldealbet']-$bt['totaldealwin'] -($bt['total_deal']>0?$bt['total_deal']:$bt['total_mileage']))}}
=======
                                            롤링금 : {{number_format($bt['total_mileage'])}}
                                            </li>
                                            <li>
                                            정산금 : {{number_format($bt['totaldealbet']-$bt['totaldealwin'] - $bt['total_mileage'])}}
>>>>>>> 6e42aae5246f761b007c8e7f726bd4c247a5c346
                                            </li>
                                            </ul>
                                        </div>
                                        
                                        </div>
                                        @if ($loop->index < count($total['betwin'])-1)
                                        <hr style="margin-top:0.5rem !important; margin-bottom:0.5rem !important;">
                                        @endif

                                    @endforeach
                                    </td>
                                    <td >
                                            <ul>
                                                <li>총죽장 : {{ number_format($total['betwin']['total']['total_ggr'])}}</li>
                                                <li>하부죽장 : {{ number_format($total['betwin']['total']['total_ggr_mileage'])}}</li>
                                                <li>본인죽장 : {{ number_format($total['betwin']['total']['total_ggr']-$total['betwin']['total']['total_ggr_mileage'])}}</li>
                                            </ul>
                                    </td>
                                    <td >
                                        <ul>
                                            <li>총롤링 : {{ number_format($total['betwin']['total']['total_deal'])}}</li>
                                            <li>하부롤링 : {{ number_format($total['betwin']['total']['total_mileage'])}}</li>
                                            <li>본인롤링 : {{ number_format($total['betwin']['total']['total_deal']-$total['betwin']['total']['total_mileage'])}}</li>
                                        </ul>
                                    </td>
                                    <td >{{number_format($total['balance']+$total['childsum'])}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table align-items-center table-flush" id="dailydwlist">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">이름</th>
                                <th scope="col">날짜</th>
                                <th scope="col">계좌충환전</th>
                                <th scope="col">수동충환전</th>
                                <th scope="col">롤링전환</th>
                                @if (auth()->user()->isInOutPartner())
                                <th scope="col">배팅/당첨금</th>
                                <th scope="col">공배팅/당첨금</th>
                                @else
                                <th scope="col">배팅/당첨금</th>
                                @endif
                                <th scope="col">죽장금</th>
                                <th scope="col">롤링금</th>
                                <th scope="col">보유금</th>
                                <th scope="col">롤링보유금</th>
                                @if (auth()->user()->hasRole('admin'))
                                <th scope="col">마진금</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="list">
                            @include('backend.argon.report.partials.childs_dailydw')
                        </tbody>
                        </table>
                </div>
                <div id="waitAjax" class="loading" style="margin-left: 0px; display:none;">
                    <img src="{{asset('back/argon')}}/img/theme/loading.gif">
                </div>
                <div class="card-footer py-4">
                    {{ $summary->withQueryString()->links('backend.argon.vendor.pagination.argon') }}
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('js')
<script src="{{ asset('back/argon') }}/js/jquery.treetable.js"></script>
<script>
    var table = $("#dailydwlist");
    $("#dailydwlist").treetable({ 
        expandable: true ,
        onNodeCollapse: function() {
            var node = this;
            table.treetable("unloadBranch", node);
        },
        onNodeExpand: function() {
            var node = this;
            table.treetable("unloadBranch", node);
            $('#waitAjax').show();
            $.ajax({
                async: true,
                url: "{{argon_route('argon.report.childdaily.dw', 'dw')}}?id="+node.id
                }).done(function(html) {
                    var rows = $(html).filter("tr");
                    table.treetable("loadBranch", node, rows);
                    $('#waitAjax').hide();
            });
        }
    });

    var table1 = $("#dailydwtotal");
    $("#dailydwtotal").treetable({ 
        expandable: true ,
        onNodeCollapse: function() {
            var node = this;
            table1.treetable("unloadBranch", node);
        },
        onNodeExpand: function() {
            var node = this;
            table1.treetable("unloadBranch", node);
            $('#waitAjax').show();
            $.ajax({
                async: true,
                url: "{{argon_route('argon.report.childdaily.dw', 'dw')}}?id="+node.id
                }).done(function(html) {
                    var rows = $(html).filter("tr");
                    table1.treetable("loadBranch", node, rows);
                    $('#waitAjax').hide();
            });
        }
    });
</script>
@endpush