<div class="con2">
    <ul>
        @auth()
        <li><a href="#" class="sub_pop1_open"><img src="/frontend/kdior/images/org/menu1.png?v=202301301150"></a></li>
        <li><img src="/frontend/kdior/images/menu_line.png"></li>
        <li><a href="#" class="sub_pop2_open"><img src="/frontend/kdior/images/org/menu2.png?v=202301301150"></a></li>
        <li><img src="/frontend/kdior/images/menu_line.png"></li>
        <li><a href="#" class="sub_pop3_open"><img src="/frontend/kdior/images/org/menu3.png?v=202301301150"></a></li>
        <li><img src="/frontend/kdior/images/menu_line.png"></li>
        <li><a href="#" class="sub_pop5_open"><img src="/frontend/kdior/images/org/menu4.png?v=202301301150"></a></li>
        <li><img src="/frontend/kdior/images/menu_line.png"></li>
        <li><a href="#" class="sub_pop8_open" onclick="deposit_detail();"><img src="/frontend/kdior/images/org/menu5.png?v=202301301150"></a></li>
        <li><img src="/frontend/kdior/images/menu_line.png"></li>
        <li><a href="#" class="sub_pop9_open" onclick="withdraw_detail();"><img src="/frontend/kdior/images/org/menu6.png?v=202301301150"></a></li>  
        @else
        <li><a href="#" class="etc_pop2_open"><img src="/frontend/kdior/images/org/menu1.png?v=202301301150"></a></li>
        <li><img src="/frontend/kdior/images/menu_line.png"></li>
        <li><a href="#" class="etc_pop2_open"><img src="/frontend/kdior/images/org/menu2.png?v=202301301150"></a></li>
        <li><img src="/frontend/kdior/images/menu_line.png"></li>
        <li><a href="#" class="etc_pop2_open"><img src="/frontend/kdior/images/org/menu3.png?v=202301301150"></a></li>
        <li><img src="/frontend/kdior/images/menu_line.png"></li>
        <li><a href="#" class="etc_pop2_open"><img src="/frontend/kdior/images/org/menu4.png?v=202301301150"></a></li>
        <li><img src="/frontend/kdior/images/menu_line.png"></li>
        <li><a href="#" class="etc_pop2_open"><img src="/frontend/kdior/images/org/menu5.png?v=202301301150"></a></li>
        <li><img src="/frontend/kdior/images/menu_line.png"></li>
        <li><a href="#" class="etc_pop2_open"><img src="/frontend/kdior/images/org/menu6.png?v=202301301150"></a></li>  
        @endif
    </ul>
</div>

<div class="con3_wrap">
    <div class="con3_box alone">
        <div class="main_con_title"><img src="/frontend/kdior/images/main_con3_title_550.png?v=202301301150"></div>
        <div class="main_con">          
            <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
            @if (count($noticelist) > 0)
                @foreach ($noticelist as $ntc)
                    <tr>
                        @auth()
                        <td style="width:400px; overflow-x: hidden;"><a href="#" class="sub_pop3_open">{{$ntc->title}}</a></td>
                        @else
                        <td style="width:400px; overflow-x: hidden;"><a href="#" class="etc_pop2_open">{{$ntc->title}}</a></td>
                        @endif
                        <td align="right"><span class="font15">{{date('Y-m-d',strtotime($ntc->date_time))}}</span></td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td align="center">공지사항이 없습니다</td>
                </tr>
            @endif
            </table>                  
        </div>
    </div>			
</div>