<!-- <div id="wrap" class="mainboard" style="display: block;"> -->
    <div id="contents_wrap" class="mainboard" style="display: block;">
        <div class="contents_box">
            <div class="con_box00">
                @if( $detect->isMobile() || $detect->isTablet() ) 
                    <div class="jackpot_wrap">  
                        <div>
                            <img src="/frontend/todayslot/images/jackpot.png" width="200" style="padding:0 0 0 10px;">
                            <br>
                            <br> 
                        </div>
                        <div>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr>
                                        <td width="100%" style="text-align:center;"><span id="JackpotMoney" style="text-align:center;letter-spacing: 1px;font-family: 'Open Sans', sans-serif;font-size: 23px;font-style: normal;font-weight: 900;color:#ffc600;">0</span></td>
                                    </tr>
                                </tbody>
                            </table> 
                            
                            
                        </div>
                        
                    @else     
                    <div class="jackpot_zone">           
                        <table border="0" cellspacing="0" cellpadding="0" width="900px" height="100" scrolling="auto" style="position:absolute;margin-left:-350px;margin-top:-10px">
                            <tbody>
                                <tr>
                                    <td width="800" height="50" style="text-align:right;float:right;padding-top:0px"><span class="style1 odometer odometer-auto-theme odometer-animating-up odometer-animating" id="JackpotMoney" style="display: block;letter-spacing: 6px;font-family: 'Open Sans', sans-serif;font-size: 48px;font-weight: 900;line-height: 50px;width: 800px;text-align: right;color:#ffc600;"></span></td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
        
                    <script type="text/javascript"> 
                        var HouseMoneyFst = 1000;
                        var jackPotMoneyFst = -66225331;
                        var jackPotMoneyUd = 0;
                        var HouseMoneyExt = 0;
                        
                        var hm_prefix = 0;
                        var jp_prefix = 0;
                        var hsMn;
                        var hm_options = {
                            startVal: 0,
                            separator: ',',
                        };
                        
                        var jppp = (new Date).getTime() + "";
                    
                        jppp = jppp.substring(2, 12) / 100 + -66225331 + "",
                        0 === parseInt(jppp.substr(0, -1)) && console.log("0");
                        
                        function GetJP(){
                            var get_jp = Math.floor(new Date().getTime() / 100);
                            var str_length = Math.floor(new Date().getTime() / 100).toString().length;
                            var jp_add = get_jp.toString().substr(str_length-7, 6);
                            jackPotMoneyUd = jp_add;
                            var new_jp = parseInt(jackPotMoneyFst) + parseInt(jackPotMoneyUd);
                            return new_jp;
                        }
                        
                        setInterval(function(){ 
                            var jppp = (new Date).getTime() + "";
                            jppp = jppp.substring(2, 12) / 100 + -66225331 + "",
                            0 === parseInt(jppp.substr(0, -1)) && console.log("0");
                            jp_om.update(jppp);
                        }, 2200);
                        
                        var jp_e = document.querySelector("#JackpotMoney");
                    
                        var jp_om = new Odometer({
                            el: jp_e,
                            value: 0,
                            format: "(,ddd).dd",
                        });
                        
                        setTimeout(function(){ 
                            var jppp = (new Date).getTime() + "";
                            jppp = jppp.substring(2, 12) / 100 + -66225331 + "",
                            0 === parseInt(jppp.substr(0, -1)) && console.log("0");
                            jp_om.update(jppp);
                        }, 1000);
                        //jp_om.update(jppp);                
                    </script>

                </div>
                <div class="board_zone" style="height: 253.5px">
                    <div class="board_title">
                        <img src="/frontend/todayslot/images/board_title.jpg" calss="board_top" width="100%">
                    </div>
                    @if( $detect->isMobile() || $detect->isTablet() ) 
                        <div class="withdrawStatus" style="top:41px">
                    @else
                        <div class="withdrawStatus">
                    @endif
                    
                        <ul id="realtimeWithdraw">					
                        </ul>
                        <script>

                        setInterval(function(){
                            withdrawScrolled();
                        }, 3000);
                        var listheight = -1;
                        var listBlock = [];
                        NewGetRealtime();
                        function withdrawScrolled(){
                            var position = -44;
                            for(var idx in listBlock){
                                var block = listBlock[idx];
                                if(idx == 1) withdrawSelectOn(block);
                                else withdrawSelectOff(block);

                                TweenMax.to(block, .8, {top:position, ease:Power4.easeInOut});

                                position += idx == 1 ? 72 : 43;
                            }

                            block = listBlock.shift();
                            listBlock.push(block);

                            TweenMax.delayedCall(.8, function(){
                                block.css({top:position})
                            })
                        }
                        function withdrawSelectOn(item){
                            TweenMax.to(item, .8, { height:67,  backgroundPositionY:0, borderColor:'#000', ease:Power4.easeInOut, overwrite:1});

                            TweenMax.to(item.find('ol > li'), .6, {delay:.2, color:'#000', lineHeight:'67px', fontWeight:700, ease:Power4.easeInOut});
                            TweenMax.to(item.find('ol > li').eq(2).find('span'), .4, {delay:.2, fontSize:'20px', fontWeight:700, ease:Power4.easeInOut});

                            TweenMax.to(item.find('.ico_money'), .6, {delay:.2, opacity:1, ease:Power4.easeInOut});
                        }
                        function withdrawSelectOff(item){
                            TweenMax.to(item, .8, {height:38, backgroundPositionY:67, borderColor:'#525151',  ease:Power4.easeInOut, overwrite:1});

                            TweenMax.to(item.find('ol > li'), .6, {delay:.3, color:'#fff', lineHeight:'38px', fontWeight:500, ease:Power4.easeInOut});
                            TweenMax.to(item.find('ol > li').eq(2).find('span'), .4, {delay:.3,fontSize:'14px', fontWeight:500, ease:Power4.easeInOut});

                            TweenMax.to(item.find('.ico_money'), .5, {opacity:0, ease:Power4.easeInOut});
                        }
                        function NewGetRealtime(dtype, listtype){
                            var data = [
                                "출금^rla***^1,070,000^2023-09-15^xxx^15:45^김*일",
                                "출금^dnr***^300,000^2023-09-15^xxx^15:36^정*욱",
                                "출금^hhh***^510,000^2023-09-15^xxx^13:32^황*집",
                                "출금^Aaa***^400,000^2023-09-15^xxx^13:28^김*욱",
                                "출금^194***^580,000^2023-09-15^xxx^12:50^이*상",
                                "출금^bea***^50,000^2023-09-15^xxx^12:24^김*",
                                "출금^bea***^50,000^2023-09-15^xxx^11:59^김*",
                                "출금^194***^690,000^2023-09-15^xxx^11:27^이*상",
                                "출금^hak***^200,000^2023-09-15^xxx^11:02^이*선",
                                "출금^ldh***^1,130,000^2023-09-15^xxx^04:38^이*헌",
                                "출금^rjs***^290,000^2023-09-15^xxx^04:36^이*식",
                                "출금^k12***^170,000^2023-09-15^xxx^02:22^권*희",
                                "출금^xhf***^350,000^2023-09-15^xxx^01:24^곽*석",
                                "출금^rij***^100,000^2023-09-15^xxx^01:06^마*훈",
                                "출금^hak***^50,000^2023-09-14^xxx^21:39^이*선",
                                "출금^par***^200,000^2023-09-14^xxx^20:41^류*우",
                                "출금^ydb***^1,000,000^2023-09-14^xxx^20:11^장*현",
                                "출금^dlw***^900,000^2023-09-14^xxx^18:25^이*열",
                                "출금^344***^200,000^2023-09-14^xxx^17:22^우*렬",
                                "출금^rla***^2,150,000^2023-09-14^xxx^16:53^김*일",
                                "출금^zxc***^110,000^2023-09-14^xxx^16:08^이*재",
                                "출금^asd***^100,000^2023-09-14^xxx^13:12^오*현",
                                "출금^aa9***^450,000^2023-09-14^xxx^12:39^오*준",
                                "출금^194***^460,000^2023-09-14^xxx^11:44^이*상",
                                "출금^hak***^150,000^2023-09-14^xxx^10:12^이*선",
                                "출금^sud***^170,000^2023-09-14^xxx^09:07^이*영",
                                "출금^hhh***^1,630,000^2023-09-14^xxx^08:06^황*집",
                                "출금^194***^250,000^2023-09-14^xxx^07:57^이*상",
                                "출금^qwe***^100,000^2023-09-14^xxx^07:02^이*진",
                                "출금^rjs***^250,000^2023-09-14^xxx^04:50^이*식",
                                "출금^alf***^100,000^2023-09-14^xxx^03:16^홍*옥",
                                "출금^rij***^200,000^2023-09-14^xxx^02:18^마*훈",
                                "출금^rij***^70,000^2023-09-14^xxx^01:20^마*훈",
                                "출금^alf***^100,000^2023-09-14^xxx^00:28^홍*옥",
                                "출금^aks***^200,000^2023-09-14^xxx^00:13^김*복",
                                "출금^rla***^1,070,000^2023-09-15^xxx^15:45^김*옥",
                                "출금^dnr***^300,000^2023-09-15^xxx^15:36^정*민",
                                "출금^hhh***^510,000^2023-09-15^xxx^13:32^황*범",
                                "출금^Aaa***^400,000^2023-09-15^xxx^13:28^김*성",
                                "출금^194***^580,000^2023-09-15^xxx^12:50^이*정",
                                "출금^bea***^50,000^2023-09-15^xxx^12:24^이*",
                                "출금^bea***^50,000^2023-09-15^xxx^11:59^한*",
                                "출금^194***^690,000^2023-09-15^xxx^11:27^이*연",
                                "출금^hak***^270,000^2023-09-15^xxx^11:02^이*수",
                                "출금^ldh***^1,130,000^2023-09-15^xxx^04:38^김*현",
                                "출금^rjs***^290,000^2023-09-15^xxx^04:36^이*석",
                                "출금^k12***^170,000^2023-09-15^xxx^02:22^임*해",
                                "출금^xhf***^350,000^2023-09-15^xxx^01:24^황*민",
                                "출금^rij***^100,000^2023-09-15^xxx^01:06^마*인",
                                "출금^hak***^50,000^2023-09-14^xxx^21:39^최*영",
                                "출금^par***^200,000^2023-09-14^xxx^20:41^류*재",
                                "출금^ydb***^1,230,000^2023-09-14^xxx^20:11^장*국",
                                "출금^dlw***^900,000^2023-09-14^xxx^18:25^이*라",
                                "출금^344***^200,000^2023-09-14^xxx^17:22^우*진",
                                "출금^rla***^1,150,000^2023-09-14^xxx^16:53^김*원",
                                "출금^zxc***^110,000^2023-09-14^xxx^16:08^이*지",
                                "출금^asd***^130,000^2023-09-14^xxx^13:12^고*진",
                                "출금^aa9***^480,000^2023-09-14^xxx^12:39^오*주",
                                "출금^194***^440,000^2023-09-14^xxx^11:44^이*영",
                                "출금^hak***^170,000^2023-09-14^xxx^10:12^이*아",
                                "출금^sud***^120,000^2023-09-14^xxx^09:07^이*금",
                                "출금^hhh***^1,830,000^2023-09-14^xxx^08:06^황*모",
                                "출금^194***^240,000^2023-09-14^xxx^07:57^이*임",
                                "출금^qwe***^120,000^2023-09-14^xxx^07:02^이*신",
                                "출금^rjs***^250,000^2023-09-14^xxx^04:50^이*유",
                                "출금^alf***^170,000^2023-09-14^xxx^03:16^홍*욱",
                                "출금^rij***^240,000^2023-09-14^xxx^02:18^마*휘",
                                "출금^rij***^80,000^2023-09-14^xxx^01:20^마*경",
                                "출금^alf***^120,000^2023-09-14^xxx^00:28^홍*광",
                                "출금^aks***^230,000^2023-09-14^xxx^00:13^김*제"
                            ]
                            q = 0;
                            var html_withdraw = "";
                            $.each(data, function(k, v){
                                    RealtimeHouseMoney = 0;
                                    if(v != null){
                                        var rData = v.split('^');

                                        if(rData[0] == '출금' && q < 15){
                                            seq_w = q + 1;

                                            if (q == 0)
                                            {
                                                html_withdraw += '<li class="on"><a href="#"><ol>';

                                            } else {
                                            html_withdraw += '<li><a href="#"><ol>';
                                            }

                                            var date = Date.now(); 
                                            var miniDate = date - (3600000 * 6);                                        
                                            var displayDate = randomNum(miniDate,date);
                                            var curdate = new Date(displayDate); 

                                            var year = curdate.getFullYear().toString().slice(-2); 
                                            var month = ("0" + (curdate.getMonth() + 1)).slice(-2); 
                                            var day = ("0" + curdate.getDate()).slice(-2); 
                                            var hour = ("0" + curdate.getHours()).slice(-2); 
                                            var minute = ("0" + curdate.getMinutes()).slice(-2); 
                                            var second = ("0" + curdate.getSeconds()).slice(-2);
                                            
                                            var returnDate = curdate.getFullYear() + "-" + month + "-" + day + "-" + hour + "-" + minute;
                                                                    
                                            html_withdraw += '<li>' + returnDate + '</li>';                            
                                            html_withdraw += '<li>' + rData[1] + '</li>';                                

                                            html_withdraw += '<li><span class="ico_money"><img src="/frontend/todayslot/images/ico_money.png"/></span><span>' + rData[2] + '</span></li>';                                                       

                                            html_withdraw += '</ol></a></li>';
                                            
                                            q++;
                                        }
                                    }
                                    
                                });
                                

                                $("#realtimeWithdraw").html(html_withdraw);

                                $('.withdrawStatus ul > li').each(function(idx, item){
                                    $(item).css({
                                        position:'absolute',
                                        top: listheight
                                    });
                                    listBlock.push($(item));

                                    var plusheight = !idx ? 72 : 43;

                                    listheight += plusheight
                                });

                                withdrawSelectOn(listBlock[0])

                                $("#realtimeDeposit").hide();
                        }

                        function randomNum(min, max){
                            var randNum = Math.floor(Math.random()*(max-min+1)) + min;
                            return randNum;
                        }

                        </script>
                    </div>				
                </div>
                <div class="con_box30">
                    
                        @if( $detect->isMobile() || $detect->isTablet() ) 
                        <div class="main_slot">
                            @if ($categories && count($categories))
                                <ul>                                    
                                    @foreach($categories AS $index=>$category)
                                        @if(!(isset ($errors) && count($errors) > 0) && !Session::get('success', false))
                                            @if ($category->status == 0)
                                            <li>
                                                <a href="#" onclick="alert('점검중입니다');">
                                                    <img src="/frontend/thezone/img/slot/{{ $category->title.'.png' }}" width="100%">
                                                </a>
                                            </li>
                                            
                                            @else
                                            @if($category->type =='sports')
                                                <li>
                                                    <a href="#" onclick="startGameByProvider('nexus', 'bt1_sports')">
                                                            <img src="/frontend/thezone/img/slot/{{ $category->title.'.png' }}" width = "100%" onerror="this.src='/frontend/thezone/img/slot/coming_soon.png';">
                                                    </a>
                                                </li>
                                                @else
                                                <li>
                                                    <a href="#" onclick="goMSlot('{{ $category->title }}', '{{ $category->href }}', 0)">
                                                            <img src="/frontend/thezone/img/slot/{{ $category->title.'.png' }}" width = "100%" onerror="this.src='/frontend/thezone/img/slot/coming_soon.png';">
                                                    </a>
                                                </li>
                                                @endif
                                            @endif
                                        @endif
                                    @endforeach
                                
                                    
                                <?php
                                $comingSoon = (intval(count($categories)/6) + 1 ) * 6 - count($categories);
                                ?>
                                @for ($i=0;$i<$comingSoon;$i++)
                                    <li>
                                        <a href="javascript:void(0);" onclick="alert('준비중입니다.');">
                                        <img src="/frontend/thezone/img/slot/coming_soon.png" width="100%">
                                            
                                        </a>
                                    </li>                                       
                                @endfor
                                </ul>
                            @endif
                        @else
                        <div class="sc-inner">
                            @if ($categories && count($categories))
                                    @foreach($categories AS $index=>$category)
                                        @if(!(isset ($errors) && count($errors) > 0) && !Session::get('success', false))
                                            @if ($category->status == 0)
                                            <a href="#" onclick="alert('점검중입니다');" class=" slot-btn">
                                                <div class="inner"><img src="/frontend/thezone/img/slot/{{ $category->title.'.png' }}">
                                                </div>
                                            </a>
                                            @else
                                                @if($category->type =='sports')
                                                <a href="#" onclick="startGameByProvider('nexus', 'bt1_sports')" class=" slot-btn">
                                                    <div class="inner">
                                                        <img src="/frontend/thezone/img/slot/{{ $category->title.'.png' }}">
                                                    </div>
                                                </a>
                                                @else
                                                <a href="#" onclick="goSlot('{{ $category->title }}', '{{ $category->href }}', 0)" class=" slot-btn">
                                                    <div class="inner">
                                                        <img src="/frontend/thezone/img/slot/{{ $category->title.'.png' }}">
                                                    </div>
                                                </a>
                                                @endif
                                            @endif
                                        @endif
                                    @endforeach
                                <?php
                                $comingSoon = (intval(count($categories)/6) + 1 ) * 6 - count($categories);
                                ?>
                                @for ($i=0;$i<$comingSoon;$i++)
                                    <a href="javascript:void(0);" class="slot-btn" onclick="alert('준비중입니다.');">
                                            <div class="inner-cont">
                                                <div class="main-cont">
                                                    <img class="main-img" src="/frontend/thezone/img/slot/coming_soon.png">
                                                    <div class="hover">
                                                        <div class="center">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                @endfor
                            @endif
                        @endif
                        
                        
                    </div>
                </div>
                <!-- ▲overeffect1▲ -->    

                
                @if( $detect->isMobile() || $detect->isTablet() ) 
                <div class="customer" style="height:100px;width:50%;text-align:center;margin:0px;padding:0px;margin-top:50px;">

                    <ul>
                        <li><span style="border:3px solid #ffd300;border-radius:25px;padding:6px;"><img src="/frontend/todayslot/images/sns_kakao.png" style="margin-top:-4px;margin-left:-3px">&nbsp;&nbsp;@yield('page-title')&nbsp;&nbsp;</span></li>                                   
                    </ul>

                </div>


                <div class="customer" style="height:100px;width:50%;text-align:center;margin:0px;padding:0px;margin-top:50px;">

                    <ul>
                        <li><span style="border:3px solid #0eb6ff;border-radius:25px;padding:6px;"><img src="/frontend/todayslot/images/sns_telegram.png" style="margin-top:-4px;margin-left:-3px">&nbsp;&nbsp;@yield('page-title')&nbsp;&nbsp;</span></li>                                   
                    </ul>
                </div>
                @else
                <div class="main_customer_wrap">
                    <div class="main_customer1">
                        <span style="border:3px solid #ffd300;border-radius:25px;padding:6px;">
                            <img src="/frontend/todayslot/images/sns_kakao.png" width="40" style="margin-top:-8px;margin-left:-3px"> 카카오톡 고객센터 &nbsp; 
                        </span>&nbsp;&nbsp; 
                        <span style="border:3px solid #0eb6ff;border-radius:25px;padding:6px;">
                            <img src="/frontend/todayslot/images/sns_telegram.png" width="40" style="margin-top:-8px;margin-left:-3px"> 텔레그램 고객센터 &nbsp; 
                        </span>
                    </div><br><br>
                    <div class="main_customer3">저희 @yield('page-title')은 여러분의 안전하고 즐거운 배팅을 위하여 항상 최선을 다하고 있습니다.<br>365일 24시간 상담 가능하며, 회원님들의 의견에 항상 귀 기울이도록 노력하고 있습니다.
                    </div>
                @endif
                </div>                        
            </div>
        </div>
    </div>
<!-- </div> -->



