@extends('backend.argon.layouts.app',[
        'parentSection' => 'report',
        'elementName' => 'report-game'
    ])
@section('page-title',  '게임별벳윈')

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
                        <span class="h2 font-weight-bold mb-0 text-success">{{number_format($totalsummary[0]->totalbet)}}</span>
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
                        <span class="h2 font-weight-bold mb-0 text-warning">{{number_format($totalsummary[0]->totalwin)}}</span>
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
                        <span class="h2 font-weight-bold mb-0 text-primary">{{number_format($totalsummary[0]->totalbet - $totalsummary[0]->totalwin)}}</span>
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
                        <h3 class="card-title text-info mb-0 ">롤링 합계</h3>
                        <span class="h2 font-weight-bold mb-0 text-info">{{number_format($totalsummary[0]->totaldeal)}}</span>
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
                            <input class="form-control" type="hidden" value="{{Request::get('cat_id')}}" id="cat_id"  name="cat_id">
                            <div class="form-group row">
                                <div class="col-md-1">
                                </div>
                                <label for="player" class="col-md-2 col-form-label form-control-label text-center">게임이름</label>
                                <div class="col-md-3">
                                    <input class="form-control" type="text" value="{{Request::get('game')}}" id="game"  name="game">
                                </div>
                                <div class="col-md-1">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-1">
                                </div>
                                <label for="dates" class="col-md-2 col-form-label form-control-label text-center">기간선택</label>
                                <div class="col-md-2">
                                <input class="form-control" type="date" value="{{Request::get('dates')[0]??date('Y-m-01')}}" id="dates" name="dates[]">
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
                    <h3 class="mb-0">게임별벳윈</h3>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="gamelist">
                        <thead class="thead-light">
                            <tr>
                                <th>기간내 합계</th>
                                <th>파트너이름</th>
                                <th>게임이름</th>
								<th>배팅금</th>
								<th>당첨금</th>
								<th>벳윈</th>
								<th>롤링금</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @if (count($totalstatics) > 0)
                                @foreach ($totalstatics as $stat)
                                    @if ($loop->index == 0)
                                        <tr><td rowspan="{{count($totalstatics)}}" style="border-right: 1px solid rgb(233 236 239);"></td>
                                    @else
                                        <tr>
                                    @endif
                                    @include('backend.argon.report.partials.row_game_details', ['total' => true])
                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan="6">{{__('No Data')}}</td></tr>
                            @endif
                        </tbody>
                    </table>
                    <table class="table align-items-center table-flush" id="gamelist">
                        <thead class="thead-light">
                            <tr>
                                <th>날짜</th>
                                <th>파트너이름</th>
                                <th>게임이름</th>
								<th>배팅금</th>
								<th>당첨금</th>
								<th>벳윈</th>
								<th>롤링금</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @if (count($categories) > 0)
                                @foreach ($categories as $category)
                                <tr>
                                    <td rowspan="{{count($category['cat'])}}" style="border-right: 1px solid rgb(233 236 239);"> {{ $category['date'] }}</td>
                                        @include('backend.argon.report.partials.row_game_details', ['total' => false])
                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan="6">{{__('No Data')}}</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="card-footer py-4">
                {{ $statistics->withQueryString()->links('backend.argon.vendor.pagination.argon') }}
                </div>
            </div>
        </div>
    </div>
</div>
@stop
