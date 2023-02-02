<div id="pop{{$notice->id}}" class="notice_popup1" style="position:absolute;top:50px;left:{{50+$loop->index * 200}}px;" >
    <div class="notice_popup_wrap" style="position:absolute;top:50px;left:{{50+$loop->index * 200}}px;">
        <div>
            <div class="notice_popup_text" style="background-color: #000;border: 3px solid var(--c-10-20, #f9e569);">
                <span class="notice_popup_font2">
                    <div style="background:#222222; border:1px solid #cccccc">
                        <?php echo $notice->content; ?>
                    </div>
                </span>
            </div>
        </div>
        <div class="notice_popup_btn_wrap">
            <ul>
                <li><a onclick="closeWin('pop{{$notice->id}}', 'Y');"><span class="notice_popup_btn">오늘 하루 이 창을 열지 않음</span></a></li>
                <li><a onclick="closeWin('pop{{$notice->id}}', 'N');"><span class="notice_popup_btn">닫기 X</span></a></li>                            
            </ul>
        </div>             
	</div>
</div>
<script>
    cookiedata = document.cookie;
    if ( cookiedata.indexOf("pop{{$notice->id}}=done") < 0 )
    {
        document.getElementById("pop{{$notice->id}}").style.visibility = "visible";
    }
    else
    {
        document.getElementById("pop{{$notice->id}}").style.visibility = "hidden";
    }
</script>