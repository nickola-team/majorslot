
<div id="popupID{{$notice->id}}" class="popup_window"
     style="background-color: RGBA(0,0,0,0);">
    <table style="border:1px solid #EAEAEA; background-color:RGBA(0,0,0,0.9); color: #fff; width:100%; height:100%">
        <tbody style="vertical-align: middle;">
        <tr>
            <td style="padding:20px;">
            <div id="popcontent" style="height: 100%; overflow:auto;">
                <?php echo $notice->content; ?>
            </div>
            </td>
        </tr>
        <tr>
            <td height="22" align="left" valign="middle" bgcolor="#D5D5D5">
                <div align="left" style="width:calc(100% - 10px); padding:10px;">
                    <input type="checkbox" name="chkbox" value=""
                           onclick="hidePopup('popupID{{$notice->id}}', 'master_done', '1');"/>
                    <font size="1" color="#000000">오늘 하루 이창을 열지않음</font>
                    <a href="javascript:popupClose('popupID{{$notice->id}}');"><b><font size="2" color="#000000">[닫기]</font></b></a>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>