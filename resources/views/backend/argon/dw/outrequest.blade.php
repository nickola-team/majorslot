@extends('backend.argon.layouts.app',[
        'parentSection' => 'dw',
        'elementName' => 'dw-outrequest'
    ])
@section('page-title',  '환전신청')

@section('content')
<div class="container-fluid">
<div class="row">
    <div class="col col-lg-8 m-auto">
        <div class="card">
            <div class="card-header border-0" id="headingOne">
                <div class="row align-items-center box">
                    <div class="col-8">
                        <h3 class="mb-0">환전신청</h3>
                    </div>
                </div>
            </div>
            <hr class="my-1">
            <div class="card-body">
                <form action="" method="POST"  id="form">
                    @csrf
                    <input type="hidden" id="recommender" value="{{auth()->user()->recommender}}">
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
                            @if (auth()->user()->hasRole('manager'))
                                {{number_format(auth()->user()->shop->balance)}}
                            @else
                                {{number_format(auth()->user()->balance)}}
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-5 text-center">
                            현재 롤링금
                        </div>
                        <div class="col-7">
                            <div class="row">
                                <div class="col-6">
                                @if (auth()->user()->hasRole('manager'))
                                    {{number_format(auth()->user()->shop->deal_balance - auth()->user()->shop->mileage)}}
                                @else
                                    {{number_format(auth()->user()->deal_balance - auth()->user()->mileage)}}
                                @endif
                                </div>
                                <div class="col-6">
                                    <a href="{{argon_route('argon.dw.dealconvert')}}"><button type="button" class="btn btn-danger btn-sm">롤링전환</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            신청금액
                        </div>
                        <div class="col-7">
                            <input class="form-control col-8" type="text" value="0" id="amount" name="amount">
                            <p></p>
                            <button type="button" class="btn btn-success mb-1 changeAmount" data-value="50000">5만</button>
                            <button type="button" class="btn btn-success mb-1 changeAmount" data-value="100000">10만</button>
                            <button type="button" class="btn btn-success mb-1 changeAmount" data-value="200000">20만</button>
                            <button type="button" class="btn btn-success mb-1 changeAmount" data-value="500000">50만</button>
                            <button type="button" class="btn btn-success mb-1 changeAmount" data-value="1000000">100만</button>
                            <button type="button" class="btn btn-primary mb-1 changeAmount" data-value="0">리셋</button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            거래계좌
                        </div>
                        <div class="col-7">
                            <div class="row">
                                <div class="col-6">
                                    <span>{{auth()->user()->bankInfo(true)}}</span> 
                                </div>
                                <!-- <div class="col-6">
                                <a href="{{argon_route('argon.common.profile', ['id' => auth()->user()->id])}}"><button type="button" class="btn btn-info btn-sm">계좌수정</button></a>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            환전비밀번호
                        </div>
                        <div class="col-7">
                            <input class="form-control col-8" type="password" value="" id="confirmation_token" name="confirmation_token">
                        </div>
                    </div>
                   
                    <div class="form-group row mt-5">
                        <div class="col col-lg-6 m-auto">
                            <button type="button" class="btn btn-warning col-12" id="doSubmit" onclick="withdraw_balance();">환전신청</button>
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>
@include('backend.argon.dw.partials.recent_history')
</div>

<div class="modal" tabindex="-1" role="dialog" id="alarm">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">알림</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="msgbody"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
      </div>
    </div>
  </div>
</div>
@stop

@push('js')
<script>
    function show_alarm(msg, aftercallback)
    {
        $('#msgbody').html(msg);
        $('#alarm').on('hidden.bs.modal', function () {
                if (aftercallback) {
                    aftercallback();
                }
        });
        $('#alarm').modal();
    }
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


    

    function withdraw_balance() {
        $("#doSubmit").attr('disabled', 'disabled');
        var money = $('#amount').val();
        var confirmation_token = $('#confirmation_token').val();
        if (money < 30000)
        {
            show_alarm('환전은 3만원이상부터 가능합니다.');
        }
        $.ajax({
            type: 'POST',
            url: '/api/outbalance',
            data: { money: money, confirmation_token : confirmation_token },
            cache: false,
            async: false,
            success: function (data) {
                if (data.error) {
                    $("#doSubmit").attr('disabled', false);
                    if (data.code == '001') {
                    }
                    else if (data.code == '002') {
                        $('#amount').focus();
                    }
                    else if (data.code == '003') {
                        $('#amount').val('0');
                    }
                    else if (data.code == '010')
                    {
                        show_alarm(data.msg, function() {
                            location.href = "{{argon_route('argon.common.profile')}}";
                        });
                        return;
                    }
                    show_alarm(data.msg);
                    return;
                }
                show_alarm('환전 신청이 완료되었습니다.', function() { location.reload(true);});
            },
            error: function (err, xhr) {
                show_alarm(err.responseText);
                $("#doSubmit").attr('disabled', false);
            }
        });
    }
</script>
@endpush