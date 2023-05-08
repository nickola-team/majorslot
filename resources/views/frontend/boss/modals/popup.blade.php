<div id="pop{{$notice->id}}" 
@if( $detect->isMobile() || $detect->isTablet() ) 
@else
    style="position:absolute; left:{{50+$loop->index * 350}}px; top:150px; z-index:200; width:330;height:400; visibility:visible; display:table;border: 2px solid #d5a451;" 

@endif
>
<form name="notice_formpop{{$notice->id}}">

  <table width="330" height="400" border="0" cellpadding="0" cellspacing="0">
  <tbody><tr>
    <td height="100%" bgcolor="#726c6c" valign="top">
      <table cellpadding="0" cellspacing="0" border="0">
        <tbody><tr>
          <td align="center" style="padding-top: 5px !important; padding-bottom : 5px !important;"><b>{{$notice->title}}</b></td>
        </tr>
        <tr>
          <td valign="top" style="padding-left: 5px !important; padding-right : 5px !important;"><?php echo $notice->content; ?></td>
        </tr>
      </tbody></table>
    </td>
  </tr>
  <tr>
    <td bgcolor="#d5a451" height="28" align="right">
    <input type="checkbox" name="chkbox" value="checkbox" id="notice_chk{{$notice->id}}" onclick="closeWinpopDay({{$notice->id}})"><b>하루 동안 이 창을 열지 않음</b> <a href="javascript:closeWinpop({{$notice->id}});"><span class="navi_name">[닫기]</span></a>&nbsp;
    </td>
  </tr>
  </tbody></table>
</form>
  </div>