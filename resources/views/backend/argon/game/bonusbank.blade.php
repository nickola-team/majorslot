@extends('backend.argon.layouts.app',[
        'parentSection' => 'game',
        'elementName' => 'game-transaction'
    ])
@section('page-title',  '보너스환수금')

@section('content')
<div class="container-fluid">
    <div class="row">
    <div class="col">
        <div class="card mt-4">
        <!-- Light table -->
        <!-- Card header -->
        <div class="card-header border-0">
            <h3 class="mb-0">보너스 환수금 -  {{$master->username}}본사</h3>
        </div>
        <div class="table-responsive">
                <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                    <th scope="col">번호</th>
                    <th scope="col">게임이름</th>
                    <th scope="col">환수금1&nbsp;<a href="{{argon_route('argon.game.bankbalance', ['batch'=>1,'type'=>'bonus','id'=>0,'master'=>$master->id])}}">일괄수정 <i class="fa fa-arrow-circle-right"></i></a></th>
                    <th scope="col">환수금2&nbsp;<a href="{{argon_route('argon.game.bankbalance', ['batch'=>1,'type'=>'bonus','id'=>0,'master'=>$master->id])}}">일괄수정 <i class="fa fa-arrow-circle-right"></i></a></th>
                    <th scope="col">환수금3&nbsp;<a href="{{argon_route('argon.game.bankbalance', ['batch'=>1,'type'=>'bonus','id'=>0,'master'=>$master->id])}}">일괄수정 <i class="fa fa-arrow-circle-right"></i></a></th>
                    <th scope="col">환수금4&nbsp;<a href="{{argon_route('argon.game.bankbalance', ['batch'=>1,'type'=>'bonus','id'=>0,'master'=>$master->id])}}">일괄수정 <i class="fa fa-arrow-circle-right"></i></a></th>
                    <th scope="col">환수금5&nbsp;<a href="{{argon_route('argon.game.bankbalance', ['batch'=>1,'type'=>'bonus','id'=>0,'master'=>$master->id])}}">일괄수정 <i class="fa fa-arrow-circle-right"></i></a></th>
                    <th scope="col">최대환수금</th>
                    </tr>
                </thead>
                <tbody class="list">
                    @if (count($bonusbank) > 0)
                        @foreach ($bonusbank as $bank)
                            <tr>
                                @include('backend.argon.game.partials.row_game_bonus')
                            </tr>
                        @endforeach
                    @else
                        <tr><td colspan="4">{{__('No Data')}}</td></tr>
                    @endif
                </tbody>
                </table>
        </div>
        <!-- Card footer -->
        <div class="card-footer py-4">
        </div>
        </div>
    </div>
    </div>
</div>
@stop