@extends('backend.argon.layouts.app',[
        'parentSection' => 'game',
        'elementName' => 'game-transaction'
    ])
@section('page-title',  '환수금관리')

@section('content')
<div class="container-fluid">
    <!-- Search -->
    <div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header border-0" id="headingOne">
                <div class="row align-items-center box">
                    <div class="col-8">
                        <h3 class="mb-0">설정</h3>
                    </div>
                    <div class="col-4 text-right box-tools">
                        <a class="box-button" data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne"></a>
                    </div>
                </div>
            </div>
            <hr class="my-1">
            <div id="collapseOne" class="collapse show">
                <div class="card-body">
                    <form action="{{argon_route('argon.game.banksetting')}}" method="POST" >
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="minslot1" class="col-md-2 col-form-label form-control-label text-center">최소슬롯환수금1</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="minslot1" value="{{ $minslot1?$minslot1->value:'' }}" placeholder="">
                            </div>
                            <label for="maxslot1" class="col-md-2 col-form-label form-control-label text-center">최대슬롯환수금1</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="maxslot1" value="{{ $maxslot1?$maxslot1->value:'' }}" placeholder="">
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="minslot2" class="col-md-2 col-form-label form-control-label text-center">최소슬롯환수금2</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="minslot2" value="{{ $minslot2?$minslot2->value:'' }}" placeholder="">
                            </div>
                            <label for="maxslot2" class="col-md-2 col-form-label form-control-label text-center">최대슬롯환수금2</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="maxslot2" value="{{ $maxslot2?$maxslot2->value:'' }}" placeholder="">
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="minslot3" class="col-md-2 col-form-label form-control-label text-center">최소슬롯환수금3</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="minslot3" value="{{ $minslot3?$minslot3->value:'' }}" placeholder="">
                            </div>
                            <label for="maxslot3" class="col-md-2 col-form-label form-control-label text-center">최대슬롯환수금3</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="maxslot3" value="{{ $maxslot3?$maxslot3->value:'' }}" placeholder="">
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="minslot4" class="col-md-2 col-form-label form-control-label text-center">최소슬롯환수금4</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="minslot4" value="{{ $minslot4?$minslot4->value:'' }}" placeholder="">
                            </div>
                            <label for="maxslot4" class="col-md-2 col-form-label form-control-label text-center">최대슬롯환수금4</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="maxslot4" value="{{ $maxslot4?$maxslot4->value:'' }}" placeholder="">
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="minslot5" class="col-md-2 col-form-label form-control-label text-center">최소슬롯환수금5</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="minslot5" value="{{ $minslot5?$minslot5->value:'' }}" placeholder="">
                            </div>
                            <label for="maxslot5" class="col-md-2 col-form-label form-control-label text-center">최대슬롯환수금5</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="maxslot5" value="{{ $maxslot5?$maxslot5->value:'' }}" placeholder="">
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="minbonus1" class="col-md-2 col-form-label form-control-label text-center">최소보너스환수금1</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="minbonus1" value="{{ $minbonus1?$minbonus1->value:'' }}" placeholder="">
                            </div>
                            <label for="maxbonus1" class="col-md-2 col-form-label form-control-label text-center">최대보너스환수금1</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="maxbonus1" value="{{ $maxbonus1?$maxbonus1->value:'' }}" placeholder="">
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="minbonus2" class="col-md-2 col-form-label form-control-label text-center">최소보너스환수금2</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="minbonus2" value="{{ $minbonus2?$minbonus2->value:'' }}" placeholder="">
                            </div>
                            <label for="maxbonus2" class="col-md-2 col-form-label form-control-label text-center">최대보너스환수금2</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="maxbonus2" value="{{ $maxbonus2?$maxbonus2->value:'' }}" placeholder="">
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="minbonus3" class="col-md-2 col-form-label form-control-label text-center">최소보너스환수금3</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="minbonus3" value="{{ $minbonus3?$minbonus3->value:'' }}" placeholder="">
                            </div>
                            <label for="maxbonus3" class="col-md-2 col-form-label form-control-label text-center">최대보너스환수금3</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="maxbonus3" value="{{ $maxbonus3?$maxbonus3->value:'' }}" placeholder="">
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="minbonus4" class="col-md-2 col-form-label form-control-label text-center">최소보너스환수금4</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="minbonus4" value="{{ $minbonus4?$minbonus4->value:'' }}" placeholder="">
                            </div>
                            <label for="maxbonus4" class="col-md-2 col-form-label form-control-label text-center">최대보너스환수금4</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="maxbonus4" value="{{ $maxbonus4?$maxbonus4->value:'' }}" placeholder="">
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="minbonus5" class="col-md-2 col-form-label form-control-label text-center">최소보너스환수금5</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="minbonus5" value="{{ $minbonus5?$minbonus5->value:'' }}" placeholder="">
                            </div>
                            <label for="maxbonus5" class="col-md-2 col-form-label form-control-label text-center">최대보너스환수금5</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="maxbonus5" value="{{ $maxbonus5?$maxbonus5->value:'' }}" placeholder="">
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="reset_bank" class="col-md-2 col-form-label form-control-label text-center">리셋주기</label>
                            <div class="col-md-3">
                            {!! Form::select('reset_bank',
								['1시간','2시간','6시간','12시간','매일'], $reset_bank?$reset_bank->value:0, ['class' => 'form-control', 'style' => 'width: 100%;', 'id' => 'reset_bank']) !!}
                            </div>
                            
                        </div>
                            
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <button type="submit" class="btn btn-primary col-md-10">설정</button>
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
            <h3 class="mb-0">슬롯 환수금</h3>
        </div>
        <div class="table-responsive">
                <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                    <th scope="col">번호</th>
                    <th scope="col">파트너이름</th>
                    <th scope="col">게임이름</th>
                    <th scope="col">환수율%</th>
                    <th scope="col">환수금1</th>
                    <th scope="col">환수금2</th>
                    <th scope="col">환수금3</th>
                    <th scope="col">환수금4</th>
                    <th scope="col">환수금5</th>
                    <th scope="col"><a href="{{argon_route('argon.game.bankbalance', ['batch'=>1,'type'=>'slots','id'=>0])}}">일괄수정 <i class="fa fa-arrow-circle-right"></i></a></th>
                    </tr>
                </thead>
                <tbody class="list">
                    @if (count($gamebank) > 0)
                        @foreach ($gamebank as $bank)
                            <tr>
                                @include('backend.argon.game.partials.row_bank')
                            </tr>
                        @endforeach
                    @else
                        <tr><td colspan="5">{{__('No Data')}}</td></tr>
                    @endif
                </tbody>
                </table>
        </div>
        <!-- Card footer -->
        <div class="card-footer py-4">
            {{ $gamebank->withQueryString()->links('backend.argon.vendor.pagination.argon') }}
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
            <h3 class="mb-0">보너스 환수금</h3>
        </div>
        <div class="table-responsive">
                <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                    <th scope="col">번호</th>
                    <th scope="col">파트너이름</th>
                    <th scope="col">게임갯수</th>
                    <th scope="col">환수금 합계</th>
                    </tr>
                </thead>
                <tbody class="list">
                    @if (count($bonusbank) > 0)
                        @foreach ($bonusbank as $bank)
                            <tr>
                                @include('backend.argon.game.partials.row_bonus_bank')
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