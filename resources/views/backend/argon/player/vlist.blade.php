@extends('backend.argon.layouts.app',[
        'parentSection' => 'player',
        'elementName' => 'player-list'
    ])
@section('page-title',  $title??'개인유저 목록')
<?php 
    $statuses = \VanguardLTE\Support\Enum\UserStatus::lists(); 
    $status_class = \VanguardLTE\Support\Enum\UserStatus::bgclass(); 
?>
@if ($total)
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
                    <div class="col ">
                        <h3 class="card-title text-warning mb-0 ">보유금합계</h3>
                        <span class="h2 font-weight-bold mb-0 text-warning">{{number_format($total['balance'])}}</span>
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
@endif
@section('content')
<div class="container-fluid">

<div class="row">
<div class="col">
    <div class="card mt-4">
    <!-- Light table -->
    <!-- Card header -->
    <div class="card-header border-0">
        <h3 class="mb-0">신청 목록</h3>
    </div>
    <div class="table-responsive">
            <table class="table align-items-center table-flush" id="datalist">
            <thead class="thead-light">
                <tr>
                <th scope="col">번호</th>
                <th scope="col">아이디</th>
                <th scope="col">상위</th>
                <th scope="col">연락처</th>
                <th scope="col">계좌정보</th>
                <th scope="col">신청시간</th>
                <th></th>
                </tr>
            </thead>
            <tbody class="list">
                @if (count($joinusers) > 0)
                    @foreach ($joinusers as $user)
                        <tr>
                            @include('backend.argon.player.partials.row_vrequest', ['join' => true])
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
        <h3 class="mb-0">대기 목록</h3>
    </div>
    <div class="table-responsive">
            <table class="table align-items-center table-flush" id="datalist">
            <thead class="thead-light">
                <tr>
                <th scope="col">번호</th>
                <th scope="col">아이디</th>
                <th scope="col">상위</th>
                <th scope="col">연락처</th>
                <th scope="col">계좌정보</th>
                <th scope="col">신청시간</th>
                <th></th>
                </tr>
            </thead>
            <tbody class="list">
                @if (count($confirmusers) > 0)
                    @foreach ($confirmusers as $user)
                        <tr>
                            @include('backend.argon.player.partials.row_vrequest', ['join' => false])
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
    </div>
</div>
</div>
</div>

@if ($users)
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
                            <label for="user" class="col-md-2 col-form-label form-control-label text-center">유저아이디</label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" value="{{Request::get('user')}}" id="user" name="user">
                            </div>
                            <label for="role" class="col-md-2 col-form-label form-control-label text-center">추천인아이디</label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" value="{{Request::get('shop')}}" id="shop" name="shop">
                            </div>
                            <div class="col-md-1">
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
        <h3 class="mb-0">개인유저 목록</h3>
    </div>
    <div class="table-responsive">
            <table class="table align-items-center table-flush" id="datalist">
            <thead class="thead-light">
                <tr>
                <th scope="col">번호</th>
                <th scope="col">아이디</th>
                <th scope="col">추천인</th>
                <th scope="col">보유금</th>
                <th scope="col">롤링금</th>
                <th scope="col">총충전금</th>
                <th scope="col">총환전금</th>
                <th scope="col">연락처</th>
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
                            @include('backend.argon.player.partials.row_v')
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
@endif
@include('backend.argon.common.balance')
@stop

@push('js')
<script>
    function refreshPlayerBalance(userid)
    {
        $('#uid_' + userid).text('머니요청중...');
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
    function AddPayment(userinfo){
        if(userinfo != ''){
            var arr_user = userinfo.split("--");
            $('#add_user_id').val(arr_user[0]);
            $('#addusername').text(arr_user[1]);
            $('#adduserlevel').text('회원');
            $('#adduserbalance').text('머니요청중...');
            $.ajax({
                url: "{{argon_route('argon.player.refresh')}}",
                type: "GET",
                data: {id:  arr_user[0]},
                dataType: 'json',
                success: function (data) {
                    if (data.error)
                    {
                        alert(data.msg);
                    }
                    else
                    {
                        $('#adduserbalance').text(data.balance);
                    }
                    
                },
                error: function () {
                }
            });
        }
    }
    function OutPayment(userinfo){
        if(userinfo != null){
            if(userinfo != ''){
            var arr_user = userinfo.split("--");
            $('#out_user_id').val(arr_user[0]);
            $('#outusername').text(arr_user[1]);
            $('#outuserlevel').text('회원');
            $('#outuserbalance').text('머니요청중...');
            $.ajax({
                url: "{{argon_route('argon.player.refresh')}}",
                type: "GET",
                data: {id:  arr_user[0]},
                dataType: 'json',
                success: function (data) {
                    if (data.error)
                    {
                        alert(data.msg);
                    }
                    else
                    {
                        $('#outuserbalance').text(data.balance);
                    }
                    
                },
                error: function () {
                }
            });
        }              
        }
    }
</script>
@endpush