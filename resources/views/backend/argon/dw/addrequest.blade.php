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
                            {{number_format(auth()->user()->balance)}}
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-5 text-center">
                            현재 롤링금
                        </div>
                        <div class="col-7">
                            <div class="row">
                                <div class="col-6">
                                    <span >{{number_format(auth()->user()->deal_balance - auth()->user()->mileage)}}</span> 
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
                                    <span>{{auth()->user()->bankInfo()}}</span> 
                                </div>
                                <div class="col-6">
                                <a href="{{argon_route('argon.common.profile', ['id' => auth()->user()->id])}}"><button type="button" class="btn btn-info btn-sm">계좌수정</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            입금계좌
                        </div>
                        <div class="col-7">
                            <div class="row">
                                <div class="col-6">
                                    <span id="bankinfo">확인버튼을 눌러주세요</span> 
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-warning btn-sm" id="togglebankinfo">계좌확인</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mt-5">
                        <div class="col col-lg-6 m-auto">
                            <button type="button" class="btn btn-primary col-12" id="doSubmit" onclick="deposit_balance();">충전신청</button>
                        </div>
                    </div>
                    
                </form>
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
        <h3 class="mb-0">신청내역</h3>
    </div>
    <div class="table-responsive">
            <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <tr>
                <th scope="col">번호</th>
                <th scope="col">신청자</th>
                <th scope="col">변동전 금액</th>
                <th scope="col">변동후 금액</th>
                <th scope="col">신청금액</th>
                <th scope="col">타입</th>
                <th scope="col">거래계좌</th>
                <th scope="col">신청 시간</th>
                <th scope="col">처리 시간</th>
                <th scope="col">상태</th>
                </tr>
            </thead>
            <tbody class="list">
                    <tr><td colspan="10">{{__('No Data')}}</td></tr>
            </tbody>
            </table>
    </div>
    </div>
</div>
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
	var depositAccountRequested = false;
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


    $('#togglebankinfo').click(function() {
        var money = $('#amount').val();
        var accountname = $('#recommender').val();
        $.ajax({
            type: 'POST',
            url: "{{route('frontend.api.depositAccount')}}",
            data: { money: money, account:accountname },
            cache: false,
            async: false,
            success: function (data) {
                if (data.error) {
                    show_alarm(data.msg);
                    if (data.code == '001') {
                        location.reload(true);
                    }
                    else if (data.code == '002') {
                        $('#withdraw_money').focus();
                    }
                    else if (data.code == '003') {
                        $('#recommender').focus();
                    }
                    return;
                }
                $("#bankinfo").html(data.msg);
                depositAccountRequested = true;
                if (data.url != '')
                {
                    var leftPosition, topPosition;
                    width = 600;
                    height = 1000;
                    leftPosition = (window.screen.width / 2) - ((width / 2) + 10);
                    topPosition = (window.screen.height / 2) - ((height / 2) + 50);
                    wndGame = window.open(data.url, "Deposit",
                    "status=no,height=" + height + ",width=" + width + ",resizable=yes,left="
                    + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY="
                    + topPosition + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no");
                }
                
            },
            error: function (err, xhr) {
                show_alarm(err.responseText);
            }
        });       
    });

    function deposit_balance() {
        if (!depositAccountRequested)
        {
            show_alarm('입금계좌확인을 먼저 하세요');
            return;
        }
        $("#doSubmit").attr('disabled', 'disabled');
        var money = $('#amount').val();
        $.ajax({
            type: 'POST',
            url: '/api/addbalance',
            data: { money: money },
            cache: false,
            async: false,
            success: function (data) {
                if (data.error) {
                    show_alarm(data.msg);
                    $("#doSubmit").attr('disabled', false);
                    if (data.code == '001') {
                        // location.reload(true);
                    }
                    else if (data.code == '002') {
                        $('#amount').focus();
                    }
                    else if (data.code == '003') {
                        $('#amount').val('0');
                    }
                    return;
                }
                show_alarm('충전 신청이 완료되었습니다.', function() { location.reload(true);});
            },
            error: function (err, xhr) {
                show_alarm(err.responseText);
                $("#doSubmit").attr('disabled', false);
            }
        });
    }
</script>
@endpush