@extends('backend.argon.layouts.app',[
        'parentSection' => 'dw',
        'elementName' => 'dw-deal'
    ])
@section('page-title',  '롤링전환')

@section('content')
<div class="container-fluid">
<div class="row">
    <div class="col col-lg-8 m-auto">
        <div class="card">
            <div class="card-header border-0" id="headingOne">
                <div class="row align-items-center box">
                    <div class="col-8">
                        <h3 class="mb-0">롤링전환</h3>
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
                            슬롯롤링%
                        </div>
                        <div class="col-7">
                            @if (auth()->user()->hasRole('manager'))
                                {{auth()->user()->shop->deal_percent}} %
                            @else
                                {{auth()->user()->deal_percent}} %
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-5 text-center">
                            라이브 롤링%
                        </div>
                        <div class="col-7">
                            @if (auth()->user()->hasRole('manager'))
                                {{auth()->user()->shop->table_deal_percent}} %
                            @else
                                {{auth()->user()->table_deal_percent}} %
                            @endif
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
                        @if (auth()->user()->hasRole('manager'))
                            {{number_format(auth()->user()->shop->deal_balance - auth()->user()->shop->mileage)}}
                        @else
                            {{number_format(auth()->user()->deal_balance - auth()->user()->mileage)}}
                        @endif
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            신청금액
                        </div>
                        <div class="col-7">
                            <input class="form-control col-8" type="text" value="0" id="amount" name="amount">
                            <p></p>
                            <button type="button" class="btn btn-success mb-1 changeAmount" data-value="10000">1만</button>
                            <button type="button" class="btn btn-success mb-1 changeAmount" data-value="20000">2만</button>
                            <button type="button" class="btn btn-success mb-1 changeAmount" data-value="50000">5만</button>
                            <button type="button" class="btn btn-success mb-1 changeAmount" data-value="100000">10만</button>
                            <button type="button" class="btn btn-success mb-1 changeAmount" data-value="200000">20만</button>
                            <button type="button" class="btn btn-primary mb-1 changeAmount" data-value="0">리셋</button>
                        </div>
                    </div>
                    
                   
                    <div class="form-group row mt-5">
                        <div class="col col-lg-6 m-auto">
                            <button type="button" class="btn btn-warning col-12" id="doSubmit" onclick="convert_deal_balance();">롤링전환</button>
                        </div>
                    </div>
                    
                </form>
            </div>
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


    function convert_deal_balance() {
        $("#doSubmit").attr('disabled', 'disabled');
        var money = $('#amount').val();
        $.ajax({
            type: 'POST',
            url: '/api/convert_deal_balance',
            data: { summ: money},
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
                    show_alarm(data.msg);
                    return;
                }
                show_alarm('롤링금이 전환되었습니다.', function() { location.reload(true);});
            },
            error: function (err, xhr) {
                show_alarm(err.responseText);
                $("#doSubmit").attr('disabled', false);
            }
        });
    }
</script>
@endpush