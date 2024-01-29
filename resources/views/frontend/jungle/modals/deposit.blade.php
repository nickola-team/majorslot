<div id="popdeposit" style="display:none;">
<div class="popup-overlay "
        style="position: fixed; inset: 0px; background: rgba(0, 0, 0, 0.5); display: flex; z-index: 999;">
        <div class="popup-content "
            style="position: relative; background: rgb(0, 0, 0); margin: auto; border: none; padding: 5px; z-index: 99;">
            <div id="fade_2" class="slideDown popup_none popup_content" data-popup-initialized="true" aria-hidden="false"
                role="dialog"
                style="opacity: 1; visibility: visible; display: inline-block; outline: none; transition: all 0.3s ease 0s; text-align: left; position: relative; vertical-align: middle; overflow-y: auto; height: 1000px;">
                <div class="popup_wrap">
                    <div class="close_box" onclick="closePopupDiv('popdeposit');"><a href="#" class="fade_1_close"><img src="/frontend/jungle/images/popup_close.png"></a>
                    </div>
                    <div class="popupbox">
                        <div id="popuptab_cont2" class="popuptab_cont">
                            <div class="title1">입금신청</div>
                            <div class="contents_in">
                                <div class="con_box00">
                                    <div class="info_wrap">
                                        <div class="info2">충전 신청시 참고사항</div>
                                        <div class="info3">1. 토스페이,카카오페이,간편계좌 , CD기 입금안됩니다.
                                            <br>2. 환전계좌로 등록된 계좌로만 입금해주셔야합니다.
                                            <br>3. 입금시 본인이름, 등록된계좌 아닌 타행은행 본인명의 또한 충전이 되지 않습니다.
                                            <br>4. 입금 [최소금액]은 1만원 [최대금액] 900만원 입니다.
                                            <br>  ex. 1000만원 입금시 900만원 / 100 만원 or 550만원 / 450 만원으로 필히 입금부탁 드리겠습니다.
                                        </div>
                                    </div>
                                </div>
                                <div class="con_box10">
                                    <div class="info_wrap">
                                        <div class="info2" style="text-align: center;"><span class="ww_font">내 지갑 <img
                                                    src="/frontend/jungle/images/ww_icon.png" height="30"><input
                                                    class="input1 walletBalance" id="balance_offer" readonly="" value="{{number_format(auth()->user()->balance)}}">
                                                원</span></div>
                                    </div>
                                </div>
                                <div class="con_box10" id="deposit">
                                <form method="post" id="fundFrm" name="fundFrm">
                                    <table class="write_title_top">
                                        <tbody>
                                            <tr>
                                                <td class="write_title">ID</td>
                                                <td class="write_td"></td>
                                                <td class="write_basic"><input class="input1 userID" readonly=""
                                                        value="{{auth()->user()->username}}"></td>
                                            </tr>
                                            <tr>
                                                <td style="height: 5px;"></td>
                                            </tr>
                                            <tr>
                                                <td class="write_title">은행이름</td>
                                                <td class="write_td"></td>
                                                <td class="write_basic"><input class="input1" id="bankname" value="{{auth()->user()->bank_name}}" disabled></td>
                                            </tr>
                                            <tr>
                                                <td class="write_title">계좌번호</td>
                                                <td class="write_td"></td>
                                                <td class="write_basic"><input class="input1" id="accountno" value="{{auth()->user()->account_no}}" disabled></td>
                                            </tr>
                                            <tr>
                                                <td class="write_title">입금자명</td>
                                                <td class="write_td"></td>
                                                <td class="write_basic"><input class="input1 userName" id="name" value="{{auth()->user()->recommender}}" disabled></td>
                                            </tr>
                                            <tr>
                                                <td style="height: 5px;"></td>
                                            </tr>
                                            <tr>
                                                <td class="write_title">입금금액</td>
                                                <td class="write_td"></td>
                                                <td class="write_basic">
                                                    <input id="money" type="hidden" name="money" value="0">
                                                    <input class="input1" id="money1" name="money1" placeholder="0" value="0" onchange="comma()">
                                                    <a href="javascript:money_count('10000');" style="padding-left: 5px;"><span class="btn1_2">1만원</span></a>
                                                    <a href="javascript:money_count('50000');" style="padding-left: 5px;"><span class="btn1_2">5만원</span></a>
                                                    <a href="javascript:money_count('100000');" style="padding-left: 5px;"><span class="btn1_2">10만원</span></a>
                                                    <a href="javascript:money_count('500000');" style="padding-left: 5px;"><span class="btn1_2">50만원</span></a>
                                                    <a href="javascript:money_count('1000000');" style="padding-left: 5px;"><span class="btn1_2">100만원</span></a>
                                                    <a href="javascript:money_count('5000000');" style="padding-left: 5px;"><span class="btn1_2">500만원</span></a>
                                                    <a href="javascript:money_count_hand();" style="padding-left: 5px;"><span class="btn1_1">정정</span></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="write_title">입금계좌</td>
                                                <td class="write_td"></td>
                                                <td class="write_basic"><span id="depositAccount">****</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </form>
                                </div>
                                <div class="con_box20">
                                    <div class="btn_wrap_center">
                                        <ul>
                                            <li><a onclick="requestAccount();"><span class="btn3_1">계좌요청</span></a></li>
                                            <li><a onclick="deposit();"><span class="btn3_1">입금신청하기</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>