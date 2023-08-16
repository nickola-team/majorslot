<div id="unreadmessage" class="notice_popup1" 
@if( $detect->isMobile() || $detect->isTablet() ) 
@else
    style="position:absolute;top:50px;" 
@endif
>
    <div class="notice_popup_wrap" 
@if( $detect->isMobile() || $detect->isTablet() ) 
@else
    style="position:absolute;top:50px;"
@endif
    >
        <div class="notice_popup_box">
            <div class="notice_popup_text" style="background:#222222;border: 3px solid var(--c-10-20, #f9e569);">
                <span class="notice_popup_font2">
                    <div style="background:#222222;">
                        쪽지를 확인해주세요
                    </div>
                </span>
            </div>
        </div>
        <div class="notice_popup_btn_wrap">
            <ul>
                <li><a onclick="closeWin('unreadmessage', 'N');"><span class="notice_popup_btn">닫기 X</span></a></li>                            
            </ul>
        </div>             
	</div>
</div>
<script>
    document.getElementById('unreadmessage').style.visibility = "hidden";
		@if (Auth::check() )
        $( document ).ready(function() {
            @if ($unreadmsg > 0)
				document.getElementById('unreadmessage').style.visibility = "visible";
			@else
				document.getElementById('unreadmessage').style.visibility = "hidden";
            @endif
            var updateTime = 10000;
            var apiUrl="/api/inoutlist.json";
            var timeout;
            var lastRequest = 0;
            var new_msg = new Audio("{{ url('/frontend/Major/major/audio/new-message.mp3')}}");
            var updateInOutRequest = function (callback) {
                if (true) {
                    var timestamp = + new Date();
                    $.ajax({
                        url: apiUrl,
                        type: "GET",
                        data: {'ts':timestamp,
                            'last':lastRequest, 
                            'id': 
                            @if (Auth::check())
                                {{auth()->user()->id}} },
                            @else
                            0},
                            @endif
                        dataType: 'json',
                        success: function (data) {
                            var inouts=data;
                            lastRequest = inouts['now'];
                            if (inouts['msg'] > 0)
                            {
                                new_msg.play();
                                document.getElementById('unreadmessage').style.visibility = "visible";
                                // $("#msgbutton").click();
                            }else{
                                document.getElementById('unreadmessage').style.visibility = "hidden";
                            }
                            timeout = setTimeout(updateInOutRequest, updateTime);
                            if (callback != null) callback();
                        },
                        error: function () {
                            timeout = setTimeout(updateInOutRequest, updateTime);
                            if (callback != null) callback();
                        }
                    });
                } else {
                    clearTimeout(timeout);
                }
            };
            timeout = setTimeout(updateInOutRequest, updateTime);
        });
		@endif
	</script>