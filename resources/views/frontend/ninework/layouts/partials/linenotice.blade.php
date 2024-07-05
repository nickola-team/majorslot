<style type="text/css">
.notice_wrap {float:center; width:100%; height:45px; }
.notice_box {margin:0px auto;}
.notice_text {float:center; width:100%; height:45px; line-height:45px; font-size:18px; letter-spacing:-1px; font-weight:900; color:#daa520;}
</style>


<div class="notice_wrap">
    <div class="notice_box">
        <div class="notice_text">
            <marquee id="NewsMarQuee" onmouseover="stop()" style="margin-top: 0px; width: 100%; height: 51px;" onmouseout="start()" scrollamount="8" scrolldelay="1" direction="left">
            @foreach ($noticelist as $ntc)
                <span style="cursor: pointer;" class="sub_pop1_open"> <img src="/frontend/boss/V/notice_title.png" /> {{$ntc->title}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            @endforeach
        </marquee>
        </div>
    </div>
</div>