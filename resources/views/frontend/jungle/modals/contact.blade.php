<div id="popcontact" style="display:none;">
<div class="popup-overlay "
    style="position: fixed; inset: 0px; background: rgba(0, 0, 0, 0.5); display: flex; z-index: 999;">
    <div class="popup-content "
        style="position: relative; background: rgb(0, 0, 0); margin: auto; border: none; padding: 5px; z-index: 99;">
        <div id="fade_2" class="slideDown popup_none popup_content" data-popup-initialized="true" aria-hidden="false"
            role="dialog"
            style="opacity: 1; visibility: visible; display: inline-block; outline: none; transition: all 0.3s ease 0s; text-align: left; position: relative; vertical-align: middle; overflow-y: auto; height: 800px;">
            <div class="popup_wrap">
                <div class="close_box"><a class="fade_1_close" onclick="closePopupDiv('popcontact');"><img src="/frontend/jungle/images/popup_close.png"></a></div>
                <div class="popupbox">
                    <div id="popuptab_cont8" class="popuptab_cont popupvis_hidden">
                        <div class="title1">고객센터</div>
                    </div>
                    <div id="popuptab_cont9" class="popuptab_cont">
                        <div class="title1">고객센터</div>
                        <div class="contents_in">
                            <div class="con_box00" id="contactlist">
                                <table style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td class="list_title1">번호</td>
                                            <td class="list_title1" style="width: 40%;">제목</td>
                                            <td class="list_title1" style="width: 10%;">작성자</td>
                                            <td class="list_title1" style="width: 10%;">작성일</td>
                                        </tr>
                                        @if ($msgs!=null && count($msgs) >0)
                                        @foreach ($msgs as $m)    
                                            <tr onclick="openMessage({{$m->id}});"  class="cp">
                                                <td  class="list_notice2" style="width: 10%;">{{$loop->index+1}}</td>
                                                <td  class="list_notice2">{{$m->title}}</td>
                                                <td  class="list_notice2">{{$m->writer_id==auth()->user()->id?auth()->user()->username:'관리자'}}</td>
                                                <td  class="list_notice2" style="width: 20%;">{{$m->created_at}}</td>
                                            </tr>
                                            <tr id="msg_{{$m->id}}" style="display:none;">
                                                <td colspan="4">
                                                    <div style="padding : 10px 10px 10px 10px;">
                                                        <?php  echo $m->content; ?>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endforeach
                                        @else
                                        <tr><td colspan="4" class="list_notice2">쪽지가 없습니다</td></tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="con_box20">
                                    <div class="btn_wrap_center">
                                        <ul>
                                            <li><a onclick="showWriteContactPopup();"><span class="btn3_1">문의하기</span></a></li>
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

<div id="popwritecont" style="display:none;">
<div class="popup-overlay "
    style="position: fixed; inset: 0px; background: rgba(0, 0, 0, 0.5); display: flex; z-index: 999;">
    <div class="popup-content "
        style="position: relative; background: rgb(0, 0, 0); margin: auto; border: none; padding: 5px; z-index: 99;">
        <div id="fade_2" class="slideDown popup_none popup_content" data-popup-initialized="true" aria-hidden="false"
            role="dialog"
            style="opacity: 1; visibility: visible; display: inline-block; outline: none; transition: all 0.3s ease 0s; text-align: left; position: relative; vertical-align: middle; overflow-y: auto; height: 800px;">
            <div class="popup_wrap">
                <div class="close_box"><a class="fade_1_close" onclick="closePopupDiv('popwritecont');"><img src="/frontend/jungle/images/popup_close.png"></a></div>
                <div class="popupbox">
                    <div id="popuptab_cont8" class="popuptab_cont popupvis_hidden">
                        <div class="title1">문의하기</div>
                    </div>
                    <div id="popuptab_cont9" class="popuptab_cont">
                        <div class="title1">문의하기</div>
                        <div class="contents_in">
                            <div class="con_box00">
                                <table style="width: 100%;">
                                    <tbody>
                                        <tr>
                                                <td class="write_title">제목</td>
                                                <td class="write_td"></td>
                                                <td class="write_basic"><input class="input1" style="width:450px;" value="" id="subject"></td>
                                            </tr>
                                            <tr>
                                                <td style="height: 5px;"></td>
                                            </tr>
                                            <tr>
                                                <td class="write_title">내용</td>
                                                <td class="write_td"></td>
                                                <td class="write_basic"><textarea id="writeArea" class="input1" placeholder="내용" style="height:200px;width:450px;"></textarea></td>
                                            </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="con_box20">
                                    <div class="btn_wrap_center">
                                        <ul>
                                            <li><a onclick="writeMessage();"><span class="btn3_1">보내기</span></a></li>
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