@extends('backend.argon.layouts.app',[
        'parentSection' => 'share',
        'elementName' => 'share-game'
    ])
@section('page-title',  '받치기 게임내역')
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
                            <label for="player" class="col-md-2 col-form-label form-control-label text-center">유저아이디</label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" value="{{Request::get('player')}}" id="player"  name="player">
                            </div>
                            <label for="game" class="col-md-2 col-form-label form-control-label text-center">게임이름</label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" value="{{Request::get('game')}}" id="game"  name="game">
                            </div>

                            <div class="col-md-1">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="win_from" class="col-md-2 col-form-label form-control-label text-center">최소당첨금</label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" value="{{Request::get('win_from')}}" id="win_from"  name="win_from">
                            </div>
                            <label for="win_to" class="col-md-2 col-form-label form-control-label text-center">최대당첨금</label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" value="{{Request::get('win_to')}}" id="win_to"  name="win_to">
                            </div>

                            <div class="col-md-1">
                            </div>
                        </div>
                        @if (!auth()->user()->hasRole('comaster'))
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="partner" class="col-md-2 col-form-label form-control-label text-center">파트너아이디</label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" value="{{Request::get('partner')}}" id="partner"  name="partner">
                            </div>
                        </div>
                        @endif

                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="dates" class="col-md-2 col-form-label form-control-label text-center">배팅시간</label>
                            <div class="col-md-2">
                            <input class="form-control" type="datetime-local" value="{{Request::get('dates')[0]??date('Y-m-d\TH:i', strtotime('-1 days'))}}" id="dates" name="dates[]">
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
        <h3 class="mb-0">받치기 게임내역</h3>
    </div>
    <div class="table-responsive">
            <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <tr>
                <th scope="col">유저</th>
                <th scope="col">파트너아이디</th>
                <th scope="col">받치기상위</th>
                <th scope="col">게임사</th>
                <th scope="col">게임명</th>
                <th scope="col">배팅금</th>
                <th scope="col">당첨금</th>
                <th scope="col">롤링</th>
                <th scope="col">롤링금</th>


                <th scope="col">배팅시간</th>
                <th scope="col">배팅상세</th>
                </tr>
            </thead>
            <tbody class="list">
                @if (count($sharebetlogs) > 0)
                    @foreach ($sharebetlogs as $stat)
                        <tr>
                            @include('backend.argon.share.partials.row_game')
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="11">{{__('No Data')}}</td></tr>
                @endif
            </tbody>
            </table>
    </div>
    <!-- Card footer -->
    <div class="card-footer py-4">
        {{ $sharebetlogs->withQueryString()->links('backend.argon.vendor.pagination.argon') }}
    </div>
    </div>
</div>
</div>
@stop