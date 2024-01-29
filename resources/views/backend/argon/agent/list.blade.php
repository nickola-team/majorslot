@extends('backend.argon.layouts.app',
    [
        'parentSection' => 'agent',
        'elementName' => 'agent-list'
    ])
@section('page-title',  '파트너 목록')
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
                        <h3 class="card-title text-success mb-0 ">총 파트너</h3>
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
                        <span class="h2 font-weight-bold mb-0 text-warning">{{number_format($total['balance']+$total['childbalance'])}}</span>
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
            <hr class="my-1">
            <div id="collapseOne" class="collapse show">
                <div class="card-body">
                    <form action="" method="GET" >
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="user" class="col-md-2 col-form-label form-control-label text-center">파트너아이디</label>
                            <div class="col-md-3 d-flex">
                                <div class="col-md-8">
                                    <input class="form-control" type="text" value="{{Request::get('user')}}" id="user" name="user">
                                </div>
                                <div class="custom-control custom-checkbox mt-2">
                                    <input class="custom-control-input" id="includename" name="includename" type="checkbox" {{Request::get('includename')=='on'?'checked':''}}>   <label class="custom-control-label" for="includename">포함된이름</label>
                                </div>
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
                            <label for="status" class="col-md-2 col-form-label form-control-label text-center">상태</label>
                            <div class="col-md-3">
                                <select class="form-control" id="status" name="status">
                                    <option value="" @if (Request::get('status') == '') selected @endif>@lang('app.all')</option>
									<option value="{{\VanguardLTE\Support\Enum\UserStatus::ACTIVE}}" @if (Request::get('status') == \VanguardLTE\Support\Enum\UserStatus::ACTIVE) selected @endif>활성</option>
                                    <option value="{{\VanguardLTE\Support\Enum\UserStatus::BANNED}}" @if (Request::get('status') == \VanguardLTE\Support\Enum\UserStatus::BANNED) selected @endif>차단</option>
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
                            <label for="phone" class="col-md-2 col-form-label form-control-label text-center">전화번호</label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" value="{{Request::get('phone')}}" id="phone" name="phone">
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
        <h3 class="mb-0">파트너 목록</h3>
    </div>
    <div class="table-responsive">
            <table class="table align-items-center table-flush" id="agentlist">
            <thead class="thead-light">
                <tr>
                <th scope="col">파트너</th>
                <th scope="col">번호</th>
                <th scope="col">레벨</th>
                <th scope="col">보유금</th>
                <th scope="col">롤링금</th>
                <th scope="col">롤링%</th>
                <th scope="col">죽장%</th>
                <th scope="col">가입날짜</th>
                <th></th>
                </tr>
            </thead>
            <tbody class="list">
                @include('backend.argon.agent.partials.childs')
            </tbody>
            </table>
    </div>
    <div id="waitAjax" class="loading" style="margin-left: 0px; display:none;">
        <img src="{{asset('back/argon')}}/img/theme/loading.gif">
    </div>
    <!-- Card footer -->
    <div class="card-footer py-4">
        {{ $users->withQueryString()->links('backend.argon.vendor.pagination.argon') }}
    </div>
    </div>
</div>
</div>
@include('backend.argon.common.balance')
@stop

@push('js')
<script src="{{ asset('back/argon') }}/js/jquery.treetable.js"></script>
<script>
    var table = $("#agentlist");
    $("#agentlist").treetable({ 
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
                url: "{{argon_route('argon.agent.child')}}?id="+node.id+"status="+"{{Request::get('status')}}"
                }).done(function(html) {
                    var rows = $(html).filter("tr");
                    table.treetable("loadBranch", node, rows);
                    $('#waitAjax').hide();
            });
        }
    });
    function AddPayment(userinfo){
        if(userinfo != ''){
            var arr_user = userinfo.split("--");
            $('#add_user_id').val(arr_user[0]);
            $('#adduserbalance').text(arr_user[1]);
            $('#adduserlevel').text(arr_user[2]);
            $('#addusername').text(arr_user[3]);
            $('#addterminate').css("display" ,"none");
        }
    }
    function OutPayment(userinfo){
        if(userinfo != null){
            if(userinfo != ''){
            var arr_user = userinfo.split("--");
            $('#out_user_id').val(arr_user[0]);
            $('#outuserbalance').text(arr_user[1]);
            $('#outuserlevel').text(arr_user[2]);
            $('#outusername').text(arr_user[3]);
            $('#outterminate').css("display" ,"none");
        }              
        }
    }
</script>
@endpush