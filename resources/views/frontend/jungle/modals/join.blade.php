<div id="popjoin" style="display:none;">
<div class="popup-overlay "
    style="position: fixed; inset: 0px; background: rgba(0, 0, 0, 0.5); display: flex; z-index: 999;">
    <div class="popup-content "
        style="position: relative; background: rgb(0, 0, 0); margin: auto; border: none; padding: 5px; z-index: 99;">
        <div id="fade_2" class="slideDown popup_none popup_content" data-popup-initialized="true" aria-hidden="false"
            role="dialog"
            style="opacity: 1; visibility: visible; display: inline-block; outline: none; transition: all 0.3s ease 0s; text-align: left; position: relative; vertical-align: middle; overflow-y: auto; height: 900px;">
            <div class="popup_wrap">
                <div class="close_box"><a href="#" class="fade_1_close" onclick="closePopupDiv('popjoin');"><img src="/frontend/jungle/images/popup_close.png"></a>
                </div>
                <div class="popupbox">
                    <div id="popuptab_cont4" class="popuptab_cont">
                        <div class="title1">회원가입</div>
                        <div class="contents_in" id="joindiv">
                            <table class="write_title_top" style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td class="write_title">회원아이디 </td>
                                        <td class="write_basic"><input class="input1" id="username" value=""
                                                style="width: 200px;">
                                                <span>* 회원 아이디는 3자 이상 10자 이하로 입력하세요.</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="write_title">비밀번호</td>
                                        <td class="write_basic"><input class="input1" id="password" type="password" value=""
                                        style="width: 200px;"><span>* 비밀번호는 4자 이상 12자 이하로 입력하세요.</span></td>
                                    </tr>
                                    <tr>
                                        <td class="write_title">비밀번호확인</td>
                                        <td class="write_basic"><input class="input1" id="password_confirm" type="password" value=""
                                        style="width: 200px;"></td>
                                    </tr>
                                    <tr>
                                        <td class="write_title">전화번호</td>
                                        <td class="write_basic"><input class="input1" id="phonenumber" value=""
                                        style="width: 200px;"><span>* 반드시 본인의 핸드폰번호를 정확하게 입력하시기 바랍니다.</span></td>
                                    </tr>
                                    <tr>
                                        <td class="write_title">은행명</td>
                                        <td class="write_basic">
                                            <select id="bankname" class="input1" name="bankname" style="width: 200px;">
                                            <?php
                                                $banks = \VanguardLTE\User::$values['banks'];
                                                foreach ($banks as $b)
                                                {
                                                    echo '<option value="'.$b.'">'.$b.'</option>';
                                                }

                                            ?>
                                            </select>
                                            <span>* 입금하시는 정확한 계좌를 입력해주셔야 출금시 같은계좌로  정상적으로 처리됩니다.</span></td>
                                    </tr>
                                    <tr>
                                        <td class="write_title">계좌번호</td>
                                        <td class="write_basic"><input class="input1" id="accountno" value=""
                                        style="width: 200px;"></td>
                                    </tr>
                                    <tr>
                                        <td class="write_title">입금자명</td>
                                        <td class="write_basic"><input class="input1" id="recommender" value=""
                                        style="width: 200px;"></td>
                                    </tr>
                                    <tr>
                                        <td class="write_title">추천인코드</td>
                                        <td class="write_basic"><input class="input1" id="friend" value=""
                                        style="width: 200px;"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="con_box20">
                            <div class="btn_wrap_center">
                                <ul>
                                    <li><a onclick="goJoin();"><span class="btn3_1">가입신청</span></a></li>
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