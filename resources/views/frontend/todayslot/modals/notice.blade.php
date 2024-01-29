
<style type="text/css">

/* 공지팝업 */

</style>

@if( $detect->isMobile() || $detect->isTablet() )
<div id="pop{{$notice->id}}" class="notice_popup1" >
    <div class="notice_popup_wrap">
@else
<div id="pop{{$notice->id}}" class="notice_popup1" style="position:absolute;top:50px;left:{{50+$loop->index * 500}}px;">
    <div class="notice_popup_wrap" style="position:absolute;top:50px;left:{{50+$loop->index * 500}}px;">
@endif


        
        <div class="pop02_popup_btn_wrap">
            <ul>
                <li><a onclick="closeWin('pop{{$notice->id}}', 'Y');"><span class="pop02_popup_btn">오늘 하루 이 창을 열지 않음</span></a></li>
                <li><a onclick="closeWin('pop{{$notice->id}}', 'N');"><span class="pop02_popup_btn">닫기 X</span></a></li>                            
            </ul>
        </div> 
        <div class="notice_popup_box">
            <div class="notice_popup_text" style="background:#222222;border: 3px solid var(--c-10-20, #f9e569);">
                <span class="pop02_popup_font1" style="border-bottom:2px solid #fff;margin-bottom:15px">
                    <?php echo $notice->title; ?> 
                </span>
                <span class="notice_popup_font2">
                    <div style="background:#222222;">
                        <?php echo $notice->content; ?>                    
                    </div>
                </span>
            </div>
        </div>            
	</div>
</div>

<script>
    cookiedata = document.cookie;
    if ( cookiedata.indexOf("pop{{$notice->id}}=done") < 0 )
    {
        var doc = document.getElementById("pop{{$notice->id}}");
        doc.setAttribute('style', 'display:block');
    }
    else
    {
        var doc = document.getElementById("pop{{$notice->id}}");
        doc.setAttribute('style', 'display:none');
    }
</script>