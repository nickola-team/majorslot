@extends('backend.argon.layouts.app')
@section('page-title',  $type=='add'?'지급':'회수')

@section('content')
<div class="container-fluid">
<div class="row">
    <div class="col col-lg-8 m-auto">
        <div class="card">
            <div class="card-header border-0" id="headingOne">
                <div class="row align-items-center box">
                    <div class="col-8">
                        <h3 class="mb-0">{{$type=='add'?'지급':'회수'}}</h3>
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
                        <div class="col-5 text-center">
                            이 름
                        </div>
                        <div class="col-7">
                            {{$user->username}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            레 벨
                        </div>
                        <div class="col-7">
                            {{$user->role->description}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            현재 보유금
                        </div>
                        <div class="col-7">
                            <span id="uid_{{$user->id}}">
                            @if ($user->hasRole('manager'))
                            {{number_format($user->shop->balance)}}
                            @else
                            {{number_format($user->balance)}}
                            @endif
                            </span>
                            @if ($user->hasRole('user'))
                            &nbsp;<a href="#" onclick="refreshPlayerBalance({{$user->id}});return false;" class="btn btn-xs btn-icon-only btn-info" id="rfs_{{$user->id}}"><i class="fas fa-undo"></i></a>
                            &nbsp;<a class="btn btn-success btn-sm" href="{{argon_route('argon.player.terminate', ['id' => $user->id])}}" data-method="DELETE"
                                data-confirm-title="확인"
                                data-confirm-text="유저의 게임을 종료하시겠습니까? 종료버튼클릭후 진행한 배팅은 무시됩니다."
                                data-confirm-delete="확인"
                                data-confirm-cancel="취소"
                                onClick="$(this).css('pointer-events', 'none');"
                                >게임종료</a>
                            @endif
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
                        <div class="col-5 text-center">
                        {{$type=='add'?'지급':'회수'}}사유
                        </div>
                        <div class="col-7">
                            <input class="form-control col-8" type="text" value="" id="reason" name="reason">
                        </div>
                    </div>
                    <div class="form-group row">
                        @if ($type=='add')
                            <div class="col-6 text-center">
                                <button type="button" class="btn btn-primary col-8" id="doSubmit">지급</button>
                            </div>
                            <div class="col-6 text-center">
                                <button type="button" class="btn btn-secondary col-8" onclick="location.href='{{$url}}';">취소</button>
                            </div>
                        @else
                            <div class="col-4 text-center">
                                <button type="button" class="btn btn-warning col-12" id="doSubmit">회수</button>
                            </div>
                            <div class="col-4 text-center">
                                <button type="button" class="btn btn-danger col-12" id="doOutAll">모두회수</button>
                            </div>
                            <div class="col-4 text-center">
                                <button type="button" class="btn btn-secondary col-12" onclick="location.href='{{$url}}';">취소</button>
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