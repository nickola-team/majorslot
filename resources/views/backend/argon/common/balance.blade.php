<div class="modal fade" id="AddPaymentModal" tabindex="-1" role="dialog" aria-labelledby="AddPaymentModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">지급</h5>
            </div>
            <div class="modal-body">
                <form action="{{argon_route('argon.common.balance')}}" method="POST"  id="addform">
                    <input type="hidden" value="add" name="type">
                    <input type="hidden" value="" id="add_user_id" name="user_id">
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            이 름
                        </div>
                        <div class="col-7" id="addusername">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            레 벨
                        </div>
                        <div class="col-7" id="adduserlevel">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            현재 보유금
                        </div>
                        <div class="col-7">
                        <span  id="adduserbalance"></span>
                        &nbsp;<a class="btn btn-success btn-sm" id="addterminate" href="" data-method="DELETE"
                                data-confirm-title="확인"
                                data-confirm-text="유저의 게임을 종료하시겠습니까? 종료버튼클릭후 진행한 배팅은 무시됩니다."
                                data-confirm-delete="확인"
                                data-confirm-cancel="취소"
                                onClick="$(this).css('pointer-events', 'none');"
                                >게임종료</a>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            적용할 금액
                        </div>
                        <div class="col-7">
                            <input class="form-control col-10" type="text" value="" id="addamount" name="amount">
                            <p></p>
                            <button type="button" class="btn btn-success mb-1 addAmount btn-sm col-3" data-value="10000">1만</button>
                            <button type="button" class="btn btn-success mb-1 addAmount btn-sm col-3" data-value="20000">2만</button>
                            <button type="button" class="btn btn-success mb-1 addAmount btn-sm col-3" data-value="50000">5만</button>
                            <button type="button" class="btn btn-success mb-1 addAmount btn-sm col-3" data-value="100000">10만</button>
                            <button type="button" class="btn btn-success mb-1 addAmount btn-sm col-3" data-value="200000">20만</button>
                            <button type="button" class="btn btn-success mb-1 addAmount btn-sm col-3" data-value="500000">50만</button>
                            <button type="button" class="btn btn-primary mb-1 addAmount btn-sm col-3" data-value="0">리셋</button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-5 text-center">
                        지급사유
                        </div>
                        <div class="col-7">
                            <input class="form-control col-10" type="text" value="" id="reason" name="reason">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="addSubmit">지급</button>
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">취소</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="OutPaymentModal" tabindex="-1" role="dialog" aria-labelledby="OutPaymentModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">회수</h5>
            </div>
            <div class="modal-body">
                <form action="{{argon_route('argon.common.balance')}}" method="POST"  id="outform">
                    <input type="hidden" value="out" name="type">
                    <input type="hidden" value="" id="out_user_id" name="user_id">
                    <input type="hidden" id="outAll" name="all" value="0">
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            이 름
                        </div>
                        <div class="col-7" id="outusername">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            레 벨
                        </div>
                        <div class="col-7" id="outuserlevel">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            현재 보유금
                        </div>
                        <div class="col-7">
                        <span  id="outuserbalance"></span>
                        &nbsp;<a class="btn btn-success btn-sm" id="outterminate" href="" data-method="DELETE"
                                data-confirm-title="확인"
                                data-confirm-text="유저의 게임을 종료하시겠습니까? 종료버튼클릭후 진행한 배팅은 무시됩니다."
                                data-confirm-delete="확인"
                                data-confirm-cancel="취소"
                                onClick="$(this).css('pointer-events', 'none');"
                                >게임종료</a>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-5 text-center">
                            적용할 금액
                        </div>
                        <div class="col-7">
                            <input class="form-control col-10" type="text" value="" id="outamount" name="amount">
                            <p></p>
                            <button type="button" class="btn btn-success mb-1 outAmount btn-sm col-3" data-value="10000">1만</button>
                            <button type="button" class="btn btn-success mb-1 outAmount btn-sm col-3" data-value="20000">2만</button>
                            <button type="button" class="btn btn-success mb-1 outAmount btn-sm col-3" data-value="50000">5만</button>
                            <button type="button" class="btn btn-success mb-1 outAmount btn-sm col-3" data-value="100000">10만</button>
                            <button type="button" class="btn btn-success mb-1 outAmount btn-sm col-3" data-value="200000">20만</button>
                            <button type="button" class="btn btn-success mb-1 outAmount btn-sm col-3" data-value="500000">50만</button>
                            <button type="button" class="btn btn-primary mb-1 outAmount btn-sm col-3" data-value="0">리셋</button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-5 text-center">
                        회수사유
                        </div>
                        <div class="col-7">
                            <input class="form-control col-10" type="text" value="" id="reason" name="reason">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" id="outSubmit">회수</button>
                <button type="button" class="btn btn-danger" id="doOutAll">모두회수</button>
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">취소</button>
            </div>
        </div>
    </div>
</div>
@push('js')
<script>
    $('.addAmount').click(function(event){
        $v = Number($('#addamount').val());
        if ($(event.target).data('value') == 0)
        {
            $('#addamount').val(0);
        }
        else
        {
            $('#addamount').val($v + $(event.target).data('value'));
        }
    });
    $('.outAmount').click(function(event){
        $v = Number($('#outamount').val());
        if ($(event.target).data('value') == 0)
        {
            $('#outamount').val(0);
        }
        else
        {
            $('#outamount').val($v + $(event.target).data('value'));
        }
    });
    $('#doOutAll').click(function () {
            $(this).attr('disabled', 'disabled');
            $('#outAll').val('1');
            $('form#outform').submit();
    });
    $('#outSubmit').click(function () {
        $(this).attr('disabled', 'disabled');
        $('form#outform').submit();
    });
    $('#addSubmit').click(function () {
        $(this).attr('disabled', 'disabled');
        $('form#addform').submit();
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