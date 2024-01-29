<div id="pop{{$notice->id}}" 
@if( $detect->isMobile() || $detect->isTablet() ) 
<?php
$width = '100vw'; $height = '100vh';
?>
  style="position:absolute; left:0px; top:0px; z-index:200; width:100vw;height:100vh; visibility:visible; display:table;border: 2px solid #d5a451;" 
@else
<?php
$width = '330px'; $height = '400px';
?>
    style="position:absolute; left:{{50+$loop->index * 350}}px; top:150px; z-index:200; width:{{$width}};height:{{$width}}; visibility:visible; display:table;border: 2px solid #d5a451;" 
@endif
>
<form name="notice_formpop{{$notice->id}}">

  <table style="border:1px solid #EAEAEA; background-color:RGBA(0,0,0,0.9);  width:{{$width}}; height:{{$height}}">
  <tbody>
    <tr>
        <td height="40px" bgcolor="#726c6c" valign="top" align="center" style="padding-top: 5px !important; padding-bottom : 5px !important;"><b>{{$notice->title}}</b></td>
    </tr>
    <tr>
        <td valign="top" style="padding-left: 5px !important; padding-right : 5px !important;">
        <div style="height: calc({{$height}} - 77px); overflow:auto; background-color:RGBA(0,0,0,0.9);color:white;"><?php echo $notice->content; ?>
        </div>
      </td>
    </tr>
    <tr>
      <td bgcolor="#d5a451" height="30" valign="bottom" align="right">
      <input type="checkbox" name="chkbox" value="checkbox" id="notice_chk{{$notice->id}}" onclick="closeWinpopDay({{$notice->id}})"><b>하루 동안 이 창을 열지 않음</b> <a href="javascript:closeWinpop({{$notice->id}});"><span class="navi_name">[닫기]</span></a>&nbsp;
      </td>
    </tr>
  </tbody>
  </table>
</form>
  </div>