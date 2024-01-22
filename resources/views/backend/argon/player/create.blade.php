@extends('backend.argon.layouts.app',[
        'parentSection' => 'player',
        'elementName' => 'player-create'
    ])
@section('page-title',  '유저 생성')

@section('content')
<div class="container-fluid">
        <div class="row">
            <div class="col col-lg-6 m-auto">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">유저 생성</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" autocomplete="off">
                            @csrf
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('username') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="username">아이디</label>
                                    <div class="d-flex">
                                        <div class="col-md-10">
                                            <input type="text" name="username" id="username" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" value="" required>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-warning" id="btncheckId">중복확인</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('parent') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="parent">매장이름</label>
                                    <input type="text" name="parent" id="parent" class="form-control{{ $errors->has('parent') ? ' is-invalid' : '' }}" value="{{auth()->user()->username}}" required>
                                </div>
                                <div class="form-group{{ $errors->has('phone') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="phone">연락처</label>
                                    <input type="text" name="phone" id="phone" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" value="" >
                                </div>
                                <div class="form-group{{ $errors->has('bank_name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="bank_name">은행</label>
                                    @php
                                        $banks = array_combine(\VanguardLTE\User::$values['banks'], \VanguardLTE\User::$values['banks']);
                                    @endphp
                                    {!! Form::select('bank_name', $banks, '', ['class' => 'form-control', 'id' => 'bank_name']) !!}		
                                </div>
                                <div class="form-group{{ $errors->has('account_no') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="account_no">계좌번호</label>
                                    <input type="text" name="account_no" id="account_no" class="form-control" value="" >
                                </div>
                                <div class="form-group{{ $errors->has('recommender') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="recommender">예금주명</label>
                                    <input type="text" name="recommender" id="recommender" class="form-control" value="" >
                                </div>
				                <div class="form-group table-responsive">
                                    <table class="table align-items-center table-flush">
                                        <tr>
                                            <th>슬롯롤링%</th>
                                            <th>라이브롤링%</th>
                                            <th>스포츠롤링%</th>
                                            <th style="width:25%">파워볼 단폴롤링%</th>
                                            <th style="width:25%">파워볼 조합롤링%</th>
                                        </tr>
                                        <tr>
                                            <td style="padding:3px;"><input type="text" name="deal_percent" id="deal_percent" class="form-control" value="0"></td>
                                            <td style="padding:3px;"><input type="text" name="table_deal_percent" id="table_deal_percent" class="form-control" value="0"></td>
                                            <td style="padding:3px;"><input type="text" name="sports_deal_percent" id="sports_deal_percent" class="form-control" value="0"></td>
                                            <td style="padding:3px;"><input type="text" name="pball_single_percent" id="pball_single_percent" class="form-control" value="0"></td>
                                            <td style="padding:3px;"><input type="text" name="pball_comb_percent" id="pball_comb_percent" class="form-control" value="0"></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="password">비밀번호</label>
                                    <input type="password" name="password" id="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="" value="" required>
                                </div>
                                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="password_confirmation">비밀번호확인</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" placeholder="" value="" required>
                                </div>

                                <div class="text-center">
                                    <button type="button" onclick="submitCheck(this.form)" class="btn btn-success mt-4 col-6">생성</button>
                                </div>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

@push('js')
<script>
$('#btncheckId').click(function () {
    userid = $('#username').val();
    $.ajax({
            url: "/api/checkid",
            type: "POST",
            data: {id:  userid},
            dataType: 'json',
            success: function (data) {
                if (data.error)
                {
                    alert('오류가 발생했습니다');
                }
                else
                {
                    if (data.ok == 1)
                    {
                        alert('사용가능한 아이디입니다');
                    }
                    else
                    {
                        alert('이미 사용중인 아이디입니다');
                    }
                }
            },
            error: function () {
            }
        });
});
var doubleSubmitFlag = false;
function submitCheck(form){
    userid = $('#username').val();
    if(doubleSubmitFlag == true){
        alert(userid + '유저 생성중입니다.');
        return false;
    }else{
        doubleSubmitFlag = true;
        form.submit();
    }
}
</script>
@endpush