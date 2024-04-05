<div id="pop{{$notice->id}}" 
@if( $detect->isMobile() || $detect->isTablet() ) 
<?php
$width = '100vw'; $height = '100vh';
?>
  style="position:fixed; left:0px; top:0px; right:0px; bottom: 0px; z-index:200; width:100vw;height:100%; visibility:visible; display:table;border: 2px solid #08bbff;" 
@else
<?php
$width = '330px'; $height = '400px';
?>
    style="position:absolute; left:{{50+$loop->index * 350}}px; top:150px; z-index:200; width:{{$width}};height:{{$width}}; visibility:visible; display:table;border: 1px solid #08bbff;" 
@endif
>
<form name="notice_formpop{{$notice->id}}">

  <table style="border:1px solid #08bbff; background-color:white;  width:{{$width}}; height:{{$height}}">
  <tbody>
    <tr>
        <td height="40px" bgcolor="#333333" valign="top" align="center" style="padding-top: 5px !important; padding-bottom : 5px !important; color:white;"><b>{{$notice->title}}</b></td>
        <a class="btn mp-close-btn text-center" href="javascript:closeWinpop({{$notice->id}});" style="top: 3px;" ><i class="fa fa-close"></i></a>
    </tr>
    <tr>
        <td valign="top" style="padding-left: 5px !important; padding-right : 5px !important;">
        <div style="height: calc({{$height}} - 77px); overflow:auto; background-color:white;color:black;"><?php echo $notice->content; ?>
        </div>
      </td>
    </tr>
    <tr>
      <td >
      <!-- <input type="checkbox" name="chkbox" value="checkbox" id="notice_chk{{$notice->id}}" onclick="closeWinpopDay({{$notice->id}})"><b>하루 동안 이 창을 열지 않음</b> -->
      <button type="button" class="btn btn-primary-bordered btn-block mp-not-today-btn pull-right ng-scope" id="notice_chk{{$notice->id}}" onclick="closeWinpopDay({{$notice->id}})" translate="">오늘 하루 열지 않기</button>
      </td>
    </tr>
  </tbody>
  </table>
</form>
</div>


