@extends('backend.argon.layouts.app',[
        'parentSection' => 'share',
        'elementName' => 'share-game'
    ])
@section('page-title',  '받치기 일별정산')

@push('css')
<link type="text/css" href="{{ asset('back/argon') }}/css/jquery.treetable.css" rel="stylesheet">
<link type="text/css" href="{{ asset('back/argon') }}/css/jquery.treetable.theme.default.css" rel="stylesheet">
@endpush


@section('content-header')
<div class="row">
    <div class="col-xl-2 col-lg-2">
        <div class="card card-stats  mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col ">
                        <h3 class="card-title text-success mb-0 ">배팅합계</h3>
                        <span class="h2 font-weight-bold mb-0 text-success">{{number_format($total['bet'])}}</span>
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
    <div class="col-xl-2 col-lg-2">
        <div class="card card-stats  mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col ">
                        <h3 class="card-title text-warning mb-0 ">당첨합계</h3>
                        <span class="h2 font-weight-bold mb-0 text-warning">{{number_format($total['win'])}}</span>
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

    <div class="col-xl-2 col-lg-2">
        <div class="card card-stats  mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col ">
                        <h3 class="card-title text-primary mb-0 ">받치기배팅합계</h3>
                        <span class="h2 font-weight-bold mb-0 text-primary">{{number_format($total['sharebet'])}}</span>
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
    <div class="col-xl-2 col-lg-2">
        <div class="card card-stats  mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col ">
                        <h3 class="card-title text-info mb-0 ">받치기당첨합계</h3>
                        <span class="h2 font-weight-bold mb-0 text-info">{{number_format($total['sharewin'])}}</span>
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
    <div class="col-xl-2 col-lg-2">
        <div class="card card-stats  mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col ">
                        <h3 class="card-title text-dark mb-0 ">받치기롤링합계</h3>
                        <span class="h2 font-weight-bold mb-0 text-dark">{{number_format($total['sharedeal'])}}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-dark text-white rounded-circle shadow">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-2">
        <div class="card card-stats  mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col ">
                        <h3 class="card-title text-dark mb-0 ">롤링전환</h3>
                        <span class="h2 font-weight-bold mb-0 text-dark">{{number_format($total['dealout'])}}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-dark text-white rounded-circle shadow">
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
                        
                        @if (!auth()->user()->hasRole('comaster'))
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="partner" class="col-md-2 col-form-label form-control-label text-center">파트너이름</label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" value="{{Request::get('partner')}}" id="partner"  name="partner">
                            </div>
                        </div>
                        @endif

                        <div class="form-group row">
                                <div class="col-md-1">
                                </div>
                                <label for="dates" class="col-md-2 col-form-label form-control-label text-center">기간선택</label>
                                <div class="col-md-2">
                                <input class="form-control" type="date" value="{{Request::get('dates')[0]??date('Y-m-d', strtotime('-1 days'))}}" id="dates" name="dates[]">
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
    <!-- Light table -->
    <!-- Card header -->
    <div class="card-header border-0">
        <h3 class="mb-0">받치기 일별정산</h3>
    </div>
    <div class="table-responsive">
            <table class="table align-items-center table-flush" id="dailysummary">
            <thead class="thead-light">
                <tr>
                <th scope="col"></th>
                <th scope="col">기간내 합계</th>
                <th scope="col">배팅금</th>
                <th scope="col">당첨금</th>
                <th scope="col">벳원금</th>
                <th scope="col">롤링금</th>

                <th scope="col">롤링전환</th>

                </tr>
            </thead>
            <tbody class="list">
                <tr>
                <td colspan='2'>{{$total['daterange']}}</td>
                <td ><ul>
                    <li>총배팅금 : {{number_format($total['bet'])}}</li>
                    <li><span class='text-green'>파트너배팅금 : {{number_format($total['betlimit'])}}</span></li>
                    <li><span class='text-red'>받치기배팅금 : {{number_format($total['sharebet'])}}</span></li>
                </ul></td>
                <td ><ul>
                    <li>총당첨금 : {{number_format($total['win'])}}</li>
                    <li><span class='text-green'>파트너당첨금 : {{number_format($total['winlimit'])}}</span></li>
                    <li><span class='text-red'>받치기당첨금 : {{number_format($total['sharewin'])}}</span></li>
                </ul></td>
                <td ><ul>
                    <li>총벳윈 : {{number_format($total['bet']-$total['win'])}}</li>
                    <li><span class='text-green'>파트너벳윈 : {{number_format($total['betlimit']-$total['winlimit'])}}</span></li>
                    <li><span class='text-red'>받치기벳윈 : {{number_format($total['sharebet']-$total['sharewin'])}}</span></li>
                </ul></td>
                <td ><ul>
                    <li>총롤링금 : {{number_format($total['deallimit']+$total['sharedeal'])}}</li>
                    <li><span class='text-green'>파트너롤링금 : {{number_format($total['deallimit'])}}</span></li>
                    <li><span class='text-red'>받치기롤링금 : {{number_format($total['sharedeal'])}}</span></li>
                </ul></td>
                <td>{{number_format($total['dealout'])}}</td>
                </tr>
            </tbody>
            </table>

            <table class="table align-items-center table-flush" id="dailylist">
            <thead class="thead-light">
                <tr>
                <th scope="col">파트너이름</th>
                <th scope="col">날짜</th>
                <!-- <th scope="col">게임사</th> -->
                <th scope="col">배팅금</th>
                <th scope="col">당첨금</th>
                <th scope="col">벳원금</th>
                <th scope="col">롤링금</th>

                <th scope="col">롤링전환</th>

                </tr>
            </thead>
            <tbody class="list">
                @include('backend.argon.share.partials.childs_daily')
            </tbody>
            </table>
    </div>
    <div id="waitAjax" class="loading" style="margin-left: 0px; display:none;">
        <img src="{{asset('back/argon')}}/img/theme/loading.gif">
    </div>
    <!-- Card footer -->
    <div class="card-footer py-4">
        {{ $summary->withQueryString()->links('backend.argon.vendor.pagination.argon') }}
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
                url: "{{argon_route('argon.share.report.childdaily')}}?id="+node.id
                }).done(function(html) {
                    var rows = $(html).filter("tr");
                    table.treetable("loadBranch", node, rows);
                    $('#waitAjax').hide();
            });
        }
    });
</script>
@endpush