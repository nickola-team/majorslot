@extends('backend.argon.layouts.app')
@section('page-title',  $type=='add'?'충전':'환전')

@section('content')
<div class="container-fluid">
<div class="row">
    <div class="col col-lg-8 m-auto">
        <div class="card">
            <div class="card-header border-0" id="headingOne">
                <div class="row align-items-center box">
                    <div class="col-8">
                        <h3 class="mb-0">{{$type=='add'?'충전':'환전'}}</h3>
                    </div>
                </div>
            </div>
            <hr class="my-1">
            <div class="card-body">
                <form action="" method="POST"  id="form">
                    <input type="hidden" value="{{$type}}" name="type">
                    <input type="hidden" value="{{$url}}" name="url">
                    <input type="hidden" value="{{$user->id}}" name="user_id">
                    <input type="hidden" id="outAll" name="all" value="0">
                    <div class="form-group row">
                        <div class="col-6 text-center">
                            이 름
                        </div>
                        <div class="col-6">
                            {{$user->username}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-6 text-center">
                            레 벨
                        </div>
                        <div class="col-6">
                            {{$user->role->description}}
                        </div>
                    </div>
                    <!-- <div class="form-group row">
                        <div class="col-6 text-center">
                            계좌 정보
                        </div>
                        <div class="col-6">
                            {{$user->bankInfo()}}
                        </div>
                    </div> -->
                    <div class="form-group row">
                        <div class="col-6 text-center">
                            현재 보유금
                        </div>
                        <div class="col-6">
                            {{number_format($user->balance)}}
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-6 text-center">
                            적용할 금액
                        </div>
                        <div class="col-6">
                            <input class="form-control col-8" type="text" value="" id="amount" name="amount">
                            <p></p>
                            <button type="button" class="btn btn-success mb-1 changeAmount" data-value="10000">1만</button>
                            <button type="button" class="btn btn-success mb-1 changeAmount" data-value="20000">2만</button>
                            <button type="button" class="btn btn-success mb-1 changeAmount" data-value="50000">5만</button>
                            <button type="button" class="btn btn-success mb-1 changeAmount" data-value="100000">10만</button>
                            <button type="button" class="btn btn-success mb-1 changeAmount" data-value="200000">20만</button>
                            <button type="button" class="btn btn-success mb-1 changeAmount" data-value="500000">50만</button>
                            <button type="button" class="btn btn-primary mb-1 changeAmount" data-value="0">정정</button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-6 text-center">
                        {{$type=='add'?'충전':'환전'}}사유
                        </div>
                        <div class="col-6">
                            <input class="form-control col-8" type="text" value="" id="reason" name="reason">
                        </div>
                    </div>
                    <div class="form-group row">
                        @if ($type=='add')
                            <div class="col-6 text-right">
                                <button type="button" class="btn btn-primary col-8" id="doSubmit">충전</button>
                            </div>
                            <div class="col-6 text-left">
                                <button type="button" class="btn btn-secondary col-8" onclick="location.href='{{$url}}';">취소</button>
                            </div>
                        @else
                            <div class="col-4 text-right">
                                <button type="button" class="btn btn-warning col-8" id="doSubmit">환전</button>
                            </div>
                            <div class="col-4 text-center">
                                <button type="button" class="btn btn-danger col-8" id="doOutAll">모두환전</button>
                            </div>
                            <div class="col-4 text-left">
                                <button type="button" class="btn btn-secondary col-8" onclick="location.href='{{$url}}';">취소</button>
                            </div>
                        @endif
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
    $('.changeAmount').click(function(event){
        $v = Number($('#amount').val());
        if ($(event.target).data('value') == 0)
        {
            $('#amount').val(0);
        }
        else
        {
            $('#amount').val($v + $(event.target).data('value'));
        }
    });
    $('#doOutAll').click(function () {
			$(this).attr('disabled', 'disabled');
			$('#outAll').val('1');
			$('form#form').submit();
    });
    $('#doSubmit').click(function () {
        $(this).attr('disabled', 'disabled');
        $('form#form').submit();
    });
</script>
@endpush