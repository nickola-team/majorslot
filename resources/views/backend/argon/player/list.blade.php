@extends('backend.argon.layouts.app',[
        'parentSection' => 'player',
        'elementName' => 'player-list'
    ])
@section('page-title',  '유저 목록')

@section('content-header')
<div class="row">
    <div class="col-xl-3 col-lg-3">
        <div class="card card-stats  mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col ">
                        <h3 class="card-title text-success mb-0 ">총 유저</h3>
                        <span class="h2 font-weight-bold mb-0 text-success">{{number_format($total['count'])}}</span>
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
                    <div class="col">
                        <a href="{{argon_route('argon.player.list') . '?join[]='. date('Y-m-d\T00:00') . '&join[]='.date('Y-m-d\TH:i')}}">
                        <h3 class="card-title text-danger mb-0 ">신규 유저</h3>
                        <span class="h2 font-weight-bold mb-0 text-danger">{{number_format($total['new'])}}</span>
                        </a>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
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
                    <div class="col">
                        <a href="{{argon_route('argon.player.list', ['online' => 1])}}">
                        <h3 class="card-title text-primary mb-0 ">접속중 유저</h3>
                        <span class="h2 font-weight-bold mb-0 text-primary">{{number_format($total['online'])}}</span>
                        </a>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
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
                        <a href="{{argon_route('argon.player.list', ['balance' => 1])}}">
                        <h3 class="card-title text-warning mb-0 ">보유금합계</h3>
                        <span class="h2 font-weight-bold mb-0 text-warning">{{number_format($total['balance'])}}</span>
                        </a>
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
            <?php 

                $statuses = \VanguardLTE\Support\Enum\UserStatus::lists(); 
                $status_class = \VanguardLTE\Support\Enum\UserStatus::bgclass(); 
            ?>
            <hr class="my-1">
            <div id="collapseOne" class="collapse show">
                <div class="card-body">
                    <form action="" method="GET" >
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="user" class="col-md-2 col-form-label form-control-label text-center">아이디</label>
                            <div class="col-md-3 d-flex">
                                <div class="col-md-8">
                                    <input class="form-control" type="text" value="{{Request::get('user')}}" id="user" name="user">
                                </div>
                                <div class="custom-control custom-checkbox mt-2">
                                    <input class="custom-control-input" id="includename" name="includename" type="checkbox" {{Request::get('includename')=='on'?'checked':''}}>   <label class="custom-control-label" for="includename">포함된이름</label>
                                </div>
                            </div>
                            <label for="role" class="col-md-2 col-form-label form-control-label text-center">매장이름</label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" value="{{Request::get('shop')}}" id="shop" name="shop">
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="account_no" class="col-md-2 col-form-label form-control-label text-center">계좌번호</label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" value="{{Request::get('account_no')}}" id="account_no" name="account_no">
                            </div>
                            <label for="recommender" class="col-md-2 col-form-label form-control-label text-center">예금주이름</label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" value="{{Request::get('recommender')}}" id="recommender" name="recommender">
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="user" class="col-md-2 col-form-label form-control-label text-center">상태</label>
                            <div class="col-md-3">
                                <select class="form-control" id="online" name="online">
                                    <option value="" @if (Request::get('online') == '') selected @endif>@lang('app.all')</option>
									<option value="1" @if (Request::get('online') == 1) selected @endif> 온라인</option>
                                </select>
                            </div>
                            <label for="user" class="col-md-2 col-form-label form-control-label text-center">보유금순</label>
                            <div class="col-md-3">
                                <select class="form-control" id="balance" name="balance">
                                    <option value="" @if (Request::get('balance') == '') selected @endif>순서없음</option>
									<option value="1" @if (Request::get('balance') == 1) selected @endif> 많은순서</option>
                                    <option value="2" @if (Request::get('balance') == 2) selected @endif> 작은순서</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="ordername" class="col-md-2 col-form-label form-control-label text-center">이름순</label>
                            <div class="col-md-3">
                                <select class="form-control" id="ordername" name="ordername">
                                    <option value="" @if (Request::get('ordername') == '') selected @endif>순서없음</option>
									<option value="1" @if (Request::get('ordername') == 1) selected @endif>A-Z순</option>
                                    <option value="2" @if (Request::get('ordername') == 2) selected @endif>Z-A순</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="join" class="col-md-2 col-form-label form-control-label text-center">가입날짜</label>
                            <div class="col-md-2">
                            <input class="form-control" type="datetime-local" value="{{Request::get('join')[0]??date('Y-01-01\T00:00')}}" id="join" name="join[]">
                            </div>
                            <label for="join" class="col-form-label form-control-label" >~</label>
                            <div class="col-md-2">
                            <input class="form-control" type="datetime-local" value="{{Request::get('join')[1]??date('Y-m-d\TH:i')}}" id="join" name="join[]">
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
        <h3 class="mb-0">유저 목록</h3>
    </div>
    <div class="table-responsive">
            <table class="table align-items-center table-flush" id="datalist">
            <thead class="thead-light">
                <tr>
                <th scope="col">번호</th>
                <th scope="col">이름</th>
                <th scope="col">매장</th>
                <th scope="col">보유금</th>
                <th scope="col">롤링금</th>
                <th scope="col">총충전금</th>
                <th scope="col">총환전금</th>
                <th scope="col">상태</th>
                <th scope="col">가입날짜</th>
                <th scope="col">최근로그인</th>
                <th></th>
                </tr>
            </thead>
            <tbody class="list">
                @if (count($users) > 0)
                    @foreach ($users as $user)
                        <tr>
                            @include('backend.argon.player.partials.row')
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="8">{{__('No Data')}}</td></tr>
                @endif
            </tbody>
            </table>
    </div>
    <!-- Card footer -->
    <div class="card-footer py-4">
        {{ $users->withQueryString()->links('backend.argon.vendor.pagination.argon') }}
    </div>
    </div>
</div>
</div>
@stop


@push('js')
<script>
    function refreshPlayerBalance(userid)
    {
        $('#uid_' + userid).text('머니요청중...');
        $('#rfs_' + userid).removeClass("btn-info");
        $('#rfs_' + userid).css("pointer-events", "none");
        setTimeout(() => {
                    $('#rfs_' + userid).css("pointer-events", "auto"); 
                    $('#rfs_' + userid).addClass("btn-info");
                }, 10000);
        $.ajax({
            url: "{{argon_route('argon.player.refresh')}}",
            type: "GET",
            data: {id:  userid},
            dataType: 'json',
            success: function (data) {
                if (data.error)
                {
                    alert(data.msg);
                }
                else
                {
                    $('#uid_' + userid).text(data.balance);
                }
                
            },
            error: function () {
            }
        });
    }
</script>
@endpush