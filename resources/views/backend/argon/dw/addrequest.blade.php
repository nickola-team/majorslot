@extends('backend.argon.layouts.app')
@section('page-title',  '충전신청')

@section('content')
<div class="container-fluid">
<div class="row">
    <div class="col col-lg-8 m-auto">
        <div class="card">
            <div class="card-header border-0" id="headingOne">
                <div class="row align-items-center box">
                    <div class="col-8">
                        <h3 class="mb-0">충전신청</h3>
                    </div>
                </div>
            </div>
            <hr class="my-1">
            <div class="card-body">
                <form action="" method="POST"  id="form">
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            이 름
                        </div>
                        <div class="col-7">
                            {{auth()->user()->username}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            레 벨
                        </div>
                        <div class="col-7">
                            {{auth()->user()->role->description}}
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-5 text-center">
                            현재 보유금
                        </div>
                        <div class="col-7">
                            {{number_format(auth()->user()->balance)}}
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            신청금액
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
                        <div class="col-5 text-center">
                            거래은행
                        </div>
                        <div class="col-7">
                            {{auth()->user()->bank_name}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            계좌번호
                        </div>
                        <div class="col-7">
                            {{auth()->user()->account_no}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            예금주
                        </div>
                        <div class="col-7">
                            {{auth()->user()->recommender}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            입금계좌
                        </div>
                        <div class="col-7">
                            {{auth()->user()->username}}
                        </div>
                    </div>
                    <div class="form-group row mt-5">
                            <div class="col-6 text-center">
                                <button type="button" class="btn btn-primary col-8" id="doSubmit">충전신청</button>
                            </div>
                            <div class="col-6 text-center">
                                <button type="button" class="btn btn-secondary col-8" >취소</button>
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
    $('#doSubmit').click(function () {
        $(this).attr('disabled', 'disabled');
        $('form#form').submit();
    });
</script>
@endpush