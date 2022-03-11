@extends('backend.argon.layouts.app')
@section('page-title',  $type=='daily'?'일별벳윈':'월별벳윈')

@push('css')
<link type="text/css" href="{{ asset('back/argon') }}/css/jquery.treetable.css" rel="stylesheet">
<link type="text/css" href="{{ asset('back/argon') }}/css/jquery.treetable.theme.default.css" rel="stylesheet">
@endpush

@section('content-header')
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
                                <label for="player" class="col-md-2 col-form-label form-control-label text-center">파트너이름</label>
                                <div class="col-md-3">
                                    <input class="form-control" type="text" value="{{Request::get('partner')}}" id="partner"  name="partner">
                                </div>
                                <div class="col-md-1">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-1">
                                </div>
                                <label for="dates" class="col-md-2 col-form-label form-control-label text-center">기간선택</label>
                                <div class="col-md-2">
                                <input class="form-control" type="{{$type=='daily'?'date':'month'}}" value="{{Request::get('dates')[0]??$type=='daily'?date('Y-m-d', strtotime('-1 days')):date('Y-m', strtotime('-1 months'))}}" id="dates" name="dates[]">
                                </div>
                                <label for="dates" class="col-form-label form-control-label" >~</label>
                                <div class="col-md-2">
                                <input class="form-control" type="{{$type=='daily'?'date':'month'}}" value="{{Request::get('dates')[1]??$type=='daily'?date('Y-m-d'):date('Y-m')}}" id="dates" name="dates[]">
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
                                <th scope="col">이름</th>
                                <th scope="col">날짜</th>
                                <th scope="col">딜비수익</th>
                                <th scope="col">베팅금</th>
                                <th scope="col">당첨금</th>
                                <th scope="col">죽은금액</th>
                                <th scope="col">보유금</th>
                                <th scope="col">하위보유금(합계)</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @include('backend.argon.report.partials.childs_daily')
                            @if (count($summary))
                            <tr>
                                <td><span class='text-red'></span></td>
                                <td><span class='text-red'>합계</span></td>
                                <td><span class='text-red'>{{ number_format($summary->sum('total_deal')- $summary->sum('total_mileage'),0) }}</span></td>
                                <td><span class='text-red'>{{number_format($summary->sum('totalbet'),0)}}</span></td>
                                <td><span class='text-red'>{{number_format($summary->sum('totalwin'),0)}}</span></td>
                                <td><span class='text-red'>{{ number_format($summary->sum('totalbet')-$summary->sum('totalwin'),0) }}</span></td>
                                <td><span class='text-red'>0</span></td>
                                <td><span class='text-red'>0</span></td>
                            </tr>
                            @endif
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