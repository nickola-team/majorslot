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
                            <label for="minslot" class="col-md-2 col-form-label form-control-label text-center">최소슬롯환수금</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="minslot" value="{{ $minslot?$minslot->value:'' }}" placeholder="">
                            </div>
                            <label for="maxslot" class="col-md-2 col-form-label form-control-label text-center">최대슬롯환수금</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="maxslot" value="{{ $maxslot?$maxslot->value:'' }}" placeholder="">
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="minbonus" class="col-md-2 col-form-label form-control-label text-center">최소보너스환수금</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="minbonus" value="{{ $minbonus?$minbonus->value:'' }}" placeholder="">
                            </div>
                            <label for="maxbonus" class="col-md-2 col-form-label form-control-label text-center">최대보너스환수금</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="maxbonus" value="{{ $maxbonus?$maxbonus->value:'' }}" placeholder="">
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
                    <th scope="col">에이전트이름</th>
                    <th scope="col">환수율%</th>
                    <th scope="col">환수금</th>
                    <th scope="col"><a href="javascript:;" class="small-box-footer openAdd" data-toggle="modal" data-target="#openAddModal" data-type="slots" data-shop="{{ 0 }}">일괄수정 <i class="fa fa-arrow-circle-right"></i></a></th>
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
                    <th scope="col">에이전트이름</th>
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