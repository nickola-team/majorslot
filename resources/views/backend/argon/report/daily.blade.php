@extends('backend.argon.layouts.app',[
        'parentSection' => 'report',
        'elementName' => 'report-daily'
    ])
@section('page-title',  $type=='daily'?'일별벳윈':'월별벳윈')

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
                        <span class="h2 font-weight-bold mb-0 text-success">{{number_format($total['totalbet'])}}</span>
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
                        <span class="h2 font-weight-bold mb-0 text-warning">{{number_format($total['totalwin'])}}</span>
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
                        <span class="h2 font-weight-bold mb-0 text-primary">{{number_format($total['ggr'])}}</span>
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
                                </div>
                                <label for="player" class="col-md-2 col-form-label form-control-label text-center">파트너아이디</label>
                                <div class="col-md-3">
                                    <input class="form-control" type="text" value="{{Request::get('partner')}}" id="partner"  name="partner">
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
                                <input class="form-control" type="{{$type=='daily'?'date':'month'}}" value="{{Request::get('dates')[0]??($type=='daily'?date('Y-m-d'):date('Y-m', strtotime('-1 months')))}}" id="dates" name="dates[]">
                                </div>
                                <label for="dates" class="col-form-label form-control-label" >~</label>
                                <div class="col-md-2">
                                <input class="form-control" type="{{$type=='daily'?'date':'month'}}" value="{{Request::get('dates')[1]??($type=='daily'?date('Y-m-d'):date('Y-m'))}}" id="dates" name="dates[]">
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
                    <h3 class="mb-0">{{$type=='daily'?'일별벳윈':'월별벳윈'}}</h3>
                </div>
                <div class="table-responsive">
                        <table class="table align-items-center table-flush" id="dailylist">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">아이디</th>
                                <th scope="col">날짜</th>
                                <th scope="col">배팅금</th>
                                <th scope="col">당첨금</th>
                                <th scope="col">벳윈수익</th>
                                <th scope="col">롤링금</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @include('backend.argon.report.partials.childs_daily')
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
    var table = $("#dailylist");
    $("#dailylist").treetable({ 
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
                url: "{{$type=='daily'?argon_route('argon.report.childdaily'):argon_route('argon.report.childmonthly')}}?id="+node.id
                }).done(function(html) {
                    var rows = $(html).filter("tr");
                    table.treetable("loadBranch", node, rows);
                    $('#waitAjax').hide();
            });
        }
    });
    // methods.init();
</script>
@endpush