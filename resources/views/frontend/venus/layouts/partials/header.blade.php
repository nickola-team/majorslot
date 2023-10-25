<div id="header_wrap">
    <div class="top_wrap">
        <div class="top1_box">
            <div class="gnb_left">
                <ul>
                    <li><a href="#" class="casino_3_open"><img src="/frontend/kdior/images/gnb3.png?v=202301301150"></a></li>                           
                    <li><a href="#" 
                    @if (isset($live) && $live=='disabled')
                    onclick="alert('점검중입니다');"
                    @else
                    class="casino_1_open"
                    @endif
                    ><img src="/frontend/kdior/images/gnb1.png?v=202301301150"></a></li>
                    @if ($logo != 'dorosi')
                    <li><a href="#" 
                    @if (isset($hotel) && $hotel=='disabled')
                    onclick="alert('점검중입니다');"
                    @else
                    class="casino_2_open"
                    @endif
                    ><img src="/frontend/kdior/images/gnb4.png?v=202301301150"></a></li>
                    <li><a href="#" 
                    @if (isset($mini) && $mini=='disabled')
                    onclick="alert('점검중입니다');"
                    @else
                    class="casino_4_open"
                    @endif
                    ><img src="/frontend/kdior/images/gnb5.png?v=202301301150"></a></li>
                    @endif
                </ul>
            </div>
            <div class="logo" style="margin-left:-130px;"><a href="/"><img src="/frontend/venus/{{$logo}}.png?v=202302162217" class="bounce" width="260px"></a></div>
            <div class="login">
                <ul>
                    @auth()
                        <li><span class="font11" style="color:#fb0000;"><img src='/frontend/kdior/img/lv1.png' align='absmiddle' width='35'>&nbsp;{{auth()->user()->username}}</span>&nbsp;&nbsp;&nbsp;</li>                    
                        <li>&nbsp;캐쉬 <span class="font11">{{number_format(auth()->user()->balance)}}</span>&nbsp;&nbsp;&nbsp;</li>
                        <li>&nbsp;보너스 <span class="font11">{{number_format(auth()->user()->deal_balance)}}</span>&nbsp;&nbsp;&nbsp;</li>

                        <li><a href="/logout"><span class="login_btn2" style="min-width: 60px;">로그아웃</span></a></li>                                    
                    @else
                    <li><a href="#" class="etc_pop2_open"><span class="login_btn1">로그인</span></a></li> 
                    <li><a href="#" class="etc_pop3_open"><span class="login_btn2">회원가입</span></a></li>						
                    @endif
                </ul>
            </div> 
        </div>
        <div class="top2_box">
            <div class="gnb">
                <ul>
                @auth()
                    <li><a href="#" class="sub_pop1_open">입금신청</a></li>
                    <li><a href="#" class="sub_pop2_open">출금신청</a></li>
                    <li><a href="#" class="sub_pop3_open">공지사항</a></li>
                    <li><a href="#" class="sub_pop5_open">보너스</a></li>
                    <li><a href="#" class="sub_pop6_open" onclick="support_detail();">고객센터</a></li>
                    <li><a href="#" class="sub_pop8_open" onclick="deposit_detail();">충전내역</a></li>
                    <li><a href="#" class="sub_pop9_open" onclick="withdraw_detail();">환전내역</a></li>                             
                @else
                    <li><a href="#" class="etc_pop2_open">입금신청</a></li>
                    <li><a href="#" class="etc_pop2_open">출금신청</a></li>
                    <li><a href="#" class="etc_pop2_open">공지사항</a></li>
                    <li><a href="#" class="etc_pop2_open">보너스</a></li>
                    <li><a href="#" class="etc_pop2_open">고객센터</a></li>
                    <li><a href="#" class="etc_pop2_open">충전내역</a></li>
                    <li><a href="#" class="etc_pop2_open">환전내역</a></li>                                         
                @endif
                </ul>
            </div>        
        </div>  		
    </div>
</div>