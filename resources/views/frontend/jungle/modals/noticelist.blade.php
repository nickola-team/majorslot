<div id="popnoticelist" style="display:none;">
<div class="popup-overlay "
    style="position: fixed; inset: 0px; background: rgba(0, 0, 0, 0.5); display: flex; z-index: 999;">
    <div class="popup-content "
        style="position: relative; background: none; margin: auto; border: none; padding: 5px; z-index: 99999;">
        <div id="fade_2" class="slideDown popup_none popup_content" data-popup-initialized="true" aria-hidden="false"
            role="dialog"
            style="opacity: 1; visibility: visible; display: inline-block; outline: none; transition: all 0.3s ease 0s; text-align: left; position: relative; vertical-align: middle; overflow-y: auto; height: 600px;">
            <div class="popup_wrap">
                <div class="close_box"><a class="fade_1_close" onclick="closePopupDiv('popnoticelist');"><img src="/frontend/jungle/images/popup_close.png"></a></div>
                <div class="popupbox">
                    <div id="popuptab_cont8" class="popuptab_cont popupvis_hidden">
                        <div class="title1">공지사항</div>
                    </div>
                    <div id="popuptab_cont9" class="popuptab_cont">
                        <div class="title1">공지사항</div>
                        <div class="contents_in">
                            <div class="con_box00">
                                <table style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td class="list_title1" style="width: 10%;">번호</td>
                                            <td class="list_title1">제목</td>
                                            <td class="list_title1" style="width: 10%;">작성일</td>
                                        </tr>
                                        @if ($noticelist!=null && count($noticelist) >0)
                                        @foreach ($noticelist as $ntc)    
                                        @if ($ntc->popup == 'general')
                                            <tr onclick="openNotice({{$ntc->id}});"  class="cp">
                                                <td  class="list_notice2" style="width: 10%;">{{$loop->index+1}}</td>
                                                <td  class="list_notice2">{{$ntc->title}}</td>
                                                <td  class="list_notice2" style="width: 20%;">{{$ntc->date_time}}</td>
                                            </tr>
                                            <tr id="ntc_{{$ntc->id}}" style="display:none;">
                                                <td colspan="3">
                                                    <div style="padding : 10px 10px 10px 10px;">
                                                        <?php  echo $ntc->content; ?>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endif
                                        @endforeach
                                        @else
                                        <tr><td>공지가 없습니다</td></tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>