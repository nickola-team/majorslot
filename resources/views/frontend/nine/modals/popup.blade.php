<div id="pop{{$notice->id}}" 
@if( $detect->isMobile() || $detect->isTablet() ) 
<?php
$width = '90vw'; $height = '60vh';
?>
  style="position:fixed; left:5vw; top:10vh; z-index:200; width:90vw;height:85vh; visibility:visible; display:table;border: 2px solid #d5a451;" 
@else
<?php
$width = '330px'; $height = '400px';
?>
    style="position:absolute; left:{{275+floor($loop->index%3) * 350}}px; top:{{125+ floor($loop->index / 4) * 450}}px; z-index:200; width:{{$width}};height:{{$width}}; " 
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
      <td bgcolor="#d5a451" height="40" valign="bottom" align="right">
      @if( $detect->isMobile() || $detect->isTablet() ) 
      <input type="checkbox" name="chkbox" value="checkbox" id="notice_chk{{$notice->id}}" onclick="closeWinpopDay({{$notice->id}})" ><b style="font-size:23px;">하루 동안 이 창을 열지 않음</b> <a href="javascript:closeWinpop({{$notice->id}});"><span class="navi_name" style="font-size:20px;">[닫기]</span></a>&nbsp;
      @else
      <input type="checkbox" name="chkbox" value="checkbox" id="notice_chk{{$notice->id}}" onclick="closeWinpopDay({{$notice->id}})" ><b style="font-size:17px;">하루 동안 이 창을 열지 않음</b> <a href="javascript:closeWinpop({{$notice->id}});"><span class="navi_name" style="font-size:20px;">[닫기]</span></a>&nbsp;
      @endif
      </td>
    </tr>
  </tbody>
  </table>
</form>
  </div>

