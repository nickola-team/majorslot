@extends('backend.argon.layouts.app')
@section('page-title',  '환수금수정')

@section('content')
<div class="container-fluid">
<div class="row">
    <div class="col col-lg-8 m-auto">
        <div class="card">
            <div class="card-header border-0" id="headingOne">
                <div class="row align-items-center box">
                    <div class="col-8">
                        <h3 class="mb-0">환수금수정</h3>
                    </div>
                </div>
            </div>
            <hr class="my-1">
            <div class="card-body">
                <form action="" method="POST"  id="form">
                    <input type="hidden" value="{{$bankinfo['type']}}" name="banktype">
                    <input type="hidden" value="{{$bankinfo['id']}}" name="id">
                    <input type="hidden" value="{{$bankinfo['batch']}}" name="batch">
                    <input type="hidden" value="{{$bankinfo['master']}}" name="master">
                    <input type="hidden" id="outAll" name="outAll" value="0">
                    <input type="hidden" value="" name="balancetype" id="balancetype">
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            파트너아이디
                        </div>
                        <div class="col-7">
                            {{$bankinfo['name']}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            환수금타입
                        </div>
                        <div class="col-7">
                            {{$bankinfo['type']=='bonus'?'보너스':'슬롯'}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            현재 환수금
                        </div>
                        <div class="col-7">
                            {{number_format($bankinfo['balance'])}}
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            적용할 금액
                        </div>
                        <div class="col-7">
                            <input class="form-control col-8" type="text" value="" id="amount" name="amount">
                            <p></p>
                            <button type="button" class="btn btn-success mb-1 changeAmount" data-value="10000">1만</button>
                            <button type="button" class="btn btn-success mb-1 changeAmount" data-value="20000">2만</button>
                            <button type="button" class="btn btn-success mb-1 changeAmount" data-value="50000">5만</button>
                            <button type="button" class="btn btn-success mb-1 changeAmount" data-value="100000">10만</button>
                            <button type="button" class="btn btn-success mb-1 changeAmount" data-value="200000">20만</button>
                            <button type="button" class="btn btn-success mb-1 changeAmount" data-value="500000">50만</button>
                            <button type="button" class="btn btn-primary mb-1 changeAmount" data-value="0">리셋</button>
                        </div>
                    </div>
                    <div class="form-group row">
                            <div class="col-3 text-center">
                                <button type="button" class="btn btn-primary col-12" id="doAdd">충전</button>
                            </div>
                            <div class="col-3 text-center">
                                <button type="button" class="btn btn-warning col-12" id="doOut">환전</button>
                            </div>
                            <div class="col-3 text-center">
                                <button type="button" class="btn btn-danger col-12" id="doOutAll">모두환전</button>
                            </div>
                            <div class="col-3 text-center">
                                <button type="button" class="btn btn-secondary col-12" onclick="window.history.back();">취소</button>
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
    $('#doAdd').click(function () {
        $(this).attr('disabled', 'disabled');
        $('#balancetype').val('add');
        $('form#form').submit();
    });

    $('#doOut').click(function () {
        $(this).attr('disabled', 'disabled');
        $('#balancetype').val('out');
        $('form#form').submit();
    });
</script>
@endpush