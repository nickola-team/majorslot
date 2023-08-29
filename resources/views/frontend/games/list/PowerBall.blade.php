<!DOCTYPE html>
<html lang="kr">
    <head>
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
        <meta charset="utf-8">
        <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
        <!--<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">-->
        <meta http-equiv="Content-Script-Type" content="text/javascript">
        <meta http-equiv="Content-Style-Type" content="text/css">
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta http-equiv="Cache-Control" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="X-UA-Compatible" content="requiresActiveX=true" />
        <title>@lang('gamename.'.$gameName)</title>

        <link href="/powerball/css/all.css" rel="stylesheet" />

        <link href="/powerball/css/style.css" rel="stylesheet" />

        <link href="/powerball/css/jquery-ui.css" rel="stylesheet" />

        <link href="/powerball/plugins/swiper/swiper.min.css" rel="stylesheet" />

        <link href="/powerball/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />

        <link href="/powerball/plugins/sweetalert/sweetalert2.min.css" rel="stylesheet" />

        <link href="/powerball/plugins/waitMe/waitMe.min.css" rel="stylesheet" />


        <script src="/powerball/js/jquery-1.12.2.min.js"></script>

        <script src="/powerball/js/jquery-ui.min.js"></script>

        <script src="/powerball/js/jquery.cookie.js"></script>

        <script src="/powerball/js/chosen.jquery.min.js"></script>

        <script src="/powerball/js/jquery.sticky.js"></script>

        <script src="/powerball/js/plugin.js"></script>

        <script src="/powerball/js/ui.js"></script>

        <script src="/powerball/js/chakan.js"></script>

        <script src="/powerball/js/common.js"></script>

        <script src="/powerball/js/number.js"></script>

        <script src="/powerball/plugins/swiper/swiper.min.js"></script>

        <script src="/powerball/plugins/select2/dist/js/select2.min.js"></script>

        <script src="/powerball/plugins/sweetalert/sweetalert2.min.js"></script>

        <script src="/powerball/plugins/waitMe/waitMe.min.js"></script>
    </head>
    <body class="pc">
        <div id="wrap">
            <div class="sub_container">
                <div class="sub_article">
                    <div class="inner_wrap">
                        <div class="sub_content">
                            <div class="sub_game_title">
                                <h2 class="h2">@lang('gamename.'.$gameName)</h2>
                                <ul class="game_menus">
                                    <li><a href="#" onclick="gameDisplay();">영상보기</a></li>
                                </ul>
                            </div>
                            <div id="contract_wrap" class="contract_wrap">
                                <div class="contract_game_wrap">
                                    <div id="game_player" class="contract_game" style="display:none;">
                                        <div class="target0" o_width="830" o_height="640">
                                            <div class="target1" o_margin_left="0" o_margin_top="0">
                                                <iframe class="target2" scrolling="no" frameborder="0"
                                                    src="{{$gameInfo->VIDEO_URL}}"
                                                    style="height: 640px;"></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="contract_bet_wrap">
                                    <input id="round" type="hidden" value="" />
                                    <input id="marcket_info" type="hidden" value="" />
                                    <input id="bet_ratio" type="hidden" value="0" />
                                    <input id="bet_title" type="hidden" value="" />
                                    <input id="bet_maxmoney" type="hidden" value="{{$betMax}}" />
                                    <div class="contract_bet_cont">
                                        <div class="contract_bet_cont">
                                            <div class="powerball_pannel">
                                                <div class="powerball_status">
                                                    <span id="cur_round" style="font-size:16px;color:red;">[ --- 회
                                                        ]</span>
                                                    <span id="betcloserest" class="count"
                                                        style="font-size:18px;">--:--</span>
                                                </div>
                                                <div class="powerball_wrap">
                                                    <div class="powerball_title" style="">파워볼</div>
                                                    <div id="category_1">
                                                    </div>
                                                    <div class="powerball_title" style="">일반볼</div>
                                                    <div id="category_0">
                                                    </div>
                                                    <div class="powerball_title" style="">조합</div>
                                                    <div id="category_2">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="contract_bet_side">
                                        <div id="bet_cart" class="contract_game_cart">
                                            <div class="cart_info">
                                                <div class="row">
                                                    <em>게임분류</em>
                                                    <span class="f_yellow">@lang('gamename.'.$gameName)</span>
                                                </div>
                                                <div class="row">
                                                    <em>유저이름</em>
                                                    <span class="f_yellow name"></span>
                                                </div>
                                                <div class="row">
                                                    <em>보유금액</em>
                                                    <b id="cur_money">0</b>원
                                                </div>
                                                <div class="row">
                                                    <em>게임선택</em>
                                                    <span class="tx" id="cur_title"></span>
                                                </div>
                                                <div class="row">
                                                    <em>배당률</em>
                                                    <span id="cur_ratio">---</span>
                                                </div>
                                            </div>
                                            <div class="cart_bet">
                                                <div class="cart_form">
                                                    <ul class="cart_list">
                                                        <li>
                                                            <div class="bet_money">
                                                                <div class="input_area">
                                                                    <input id="bet_money" class="input_text" type="text"
                                                                        value="0" autocomplete="off" onkeyup="setamount();">
                                                                    <span>배팅 금액</span>
                                                                </div>
                                                                <div class="input_area">
                                                                    <input id="win_money" class="input_text" type="text"
                                                                        value="0" readonly="">
                                                                    <span>적중 예상금액</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="bet_btns">
                                                                <button type="button" class="btn_ui btn_small gray"
                                                                    onclick="selectMoney(5000)"><span>5,000</span></button>
                                                                <button type="button" class="btn_ui btn_small gray"
                                                                    onclick="selectMoney(10000)"><span>10,000</span></button>
                                                                <button type="button" class="btn_ui btn_small gray"
                                                                    onclick="selectMoney(50000)"><span>50,000</span></button>
                                                                <button type="button" class="btn_ui btn_small gray"
                                                                    onclick="selectMoney(100000)"><span>100,000</span></button>
                                                                <button type="button" class="btn_ui btn_small gray"
                                                                    onclick="selectMoney(500000)"><span>500,000</span></button>
                                                                <button type="button" class="btn_ui btn_small gray"
                                                                    onclick="selectMoney(1000000)"><span>1,000,000</span></button>
                                                                <button type="button"
                                                                    class="btn_ui btn_small light_gray modify"
                                                                    onclick="amountreset()"><span>정정</span></button>
                                                                <button type="button" class="btn_ui btn_small blue modify"
                                                                    onclick="allin()"><span>MAX</span></button>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="bet_btns">
                                                                <div class="btn_area">
                                                                    <button type="button"
                                                                        class="btn_ui btn_small light_gray"
                                                                        onclick="amountreset()"><span>정정</span></button>
                                                                    <button type="button" class="btn_ui btn_small blue"
                                                                        onclick="allin()"><span>MAX</span></button>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                    <a href="javascript:betting(0);" class="btn_betting_confirm">배팅하기</a>
                                                </div>
                                            </div>
                                            <div id="betlock" class="bet_disable"><span
                                                    style="top: 80px; position: relative;">배팅이 마감 되었습니다.</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="contract_section">
                                    <h2 class="contract_title">최근 배팅내역</h2>
                                    <div class="tbl_scroll">
                                        <div class="tbl_board tbl_game_result">
                                            <input id="hd_cancel_bet" type="hidden" value="0">
                                            <table>
                                                <colgroup>
                                                    <col width="12%">
                                                    <col width="12%">
                                                    <col width="16%">
                                                    <col width="15%">
                                                    <col width="10%">
                                                    <col width="15%">
                                                    <col width="10%">
                                                    <col width="10%">
                                                    <col>
                                                </colgroup>
                                                <thead>
                                                    <tr>
                                                        <th scope="col">배팅일시</th>
                                                        <th scope="col">게임</th>
                                                        <th scope="col">진행회차</th>
                                                        <th scope="col">배팅대상</th>
                                                        <th scope="col">배팅금</th>
                                                        <th scope="col">당첨배당</th>
                                                        <th scope="col">당첨금</th>
                                                        <th scope="col">상태</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="bet_list">
                                                    <tr>
                                                        <td class="noresult" colspan="8"><i
                                                                class="fal fa-stream"></i><br>배팅내역이 존재하지 않습니다</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script>
        var apiURL = '';
        var token = '{{$token}}';
        var bettingLock = false;
        Date.prototype.yyyymmdd = function() {
            var mm = this.getMonth() + 1; // getMonth() is zero-based
            var dd = this.getDate();
            var hh = this.getHours();
            var MM = this.getMinutes();
            var ss = this.getSeconds();

            return [this.getFullYear(),
                    (mm>9 ? '' : '0') + mm,
                    (dd>9 ? '' : '0') + dd
                    ].join('-') + " " +[ (hh>9 ? '' : '0') + hh,
                    (MM>9 ? '' : '0') + MM,
                    (ss>9 ? '' : '0') + ss
                    ].join(':');
        };
        $(document).ready(function () {
            initUserInfo();
            initBetInfo();
            $(window).scroll(function () 
                {
                    moveSlip();
                }
            );
            betHistoryList();
        });
        function moveSlip() {
            let height = $('.powerball_wrap').height();
            height -= 400;
            slippos = parseInt($(document).scrollTop());
            slippos -= 80;
            if ($('body').attr('class') == 'mobile') {
                slippos = 0;
            } else if ($('body').attr('class') == 'pc' && $("#game_player").css(
                    'display') == 'block') {
                slippos -= 260;
            }
            if (slippos < 0) slippos = 0;
            if (slippos > height) slippos = height;
            $("#bet_cart").stop().animate({
                "margin-top": slippos + "px"
            }, 300);
        }

        var isInit = true;

        function initUserInfo() {
            var formData = new FormData();
            formData.append('token', token);
            if (isInit)
            {
                wait(true);
            }
            $.ajax({
                type: 'POST',
                url: apiURL + '/api/pbgame/userinfo',
                data: formData,
                processData: false,
                contentType: false,
                crossOrigin: true,
                success: function (jsonData) {
                    if (jsonData.error) {
                        Swal.fire({
                            icon: 'error',
                            title: '알림',
                            html: '유저 정보를 확인 하세요.',
                            confirmButtonText: '확인'
                        });
                    } else {
                        $('.name').html(jsonData.username);
                        $('#cur_money').html(insertComma(parseInt(jsonData.balance).toFixed(0)));

                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }

        function initBetInfo() {
            let marcketRowHtml =
                '<div class="powerball_row" style=""><div class="powerball_box"><div class="powerball_list"><div class="bundle col1"><div  class="box"></div></div></div></div></div>';
            let rows = null
            let box = null;
            let colorType = 0;
            let categoryStr = '';
            @foreach($pbGameResults as $pgr)
                rows = $('#category_' + {{$pgr->category}}).find('.powerball_row');
                box = null;
                colorType = 0;
                if (rows.length <= 0) {
                    $('#category_' + {{$pgr->category}}).append(marcketRowHtml);
                    box = $('#category_' + {{$pgr->category}}).find('.box');
                } else {
                    // find seat to set marcket
                    while (box == null) {
                        for (let i = 0; i < rows.length; i++) {
                            let rowItem = $(rows[i]);
                            let boxCols = rowItem.find('.box_col');
                            if (boxCols.length < 4) {
                                if (boxCols.length % 2 == 1) {
                                    colorType = 1;
                                }
                                box = rowItem.find('.box');
                                break;
                            }
                        }
                        if (box == null) {
                            $('#category_' + {{$pgr->category}}).append(marcketRowHtml);
                            rows = $('#category_' + {{$pgr->category}}).find('.powerball_row');
                        }
                    }
                }
                @switch($pgr->category) 
                    @case(0)
                        categoryStr = '일반볼'
                        @break 
                    @case(1)
                        categoryStr ='파워볼'
                        @break 
                    @case(2)
                        categoryStr ='조합'
                        @break 
                    @default
                @endswitch
                box.append(
                    '<div class="box_col"><label id="{{$pgr->rt_no}}" class="bet_label powerball_22_1" onclick="selectbtn(this);" bet_ratio="{{$pgr->rt_rate}}" bet_title="'+ categoryStr +' [{{$pgr->rt_name}}]" bet_maxmoney="{{$betMax}}" colorType=' + colorType + ' style="background: linear-gradient' +
                    (colorType == 0 ? '(#3b78ca, #0035c7)' : '(#f10000, #910000)') +
                    ';"><span class="icon" style="font-size:28px">{{$pgr->rt_name}} <em class="power">' + categoryStr + '</em></span><span class="rate">{{$pgr->rt_rate}}</span></label></div>');
            @endforeach
        }

        var init_betlist = true;

        function onTimer() {
            var formData = new FormData();
            formData.append('game_id', '{{$gameInfo->game}}');
            if (isInit && $('#round').val() != '' && $('#cur_money').text() != '')
            {
                isInit = false;
                wait(false);
            }
            $.ajax({
                type: 'POST',
                url: apiURL + '/api/pbgame/round',
                data: formData,
                processData: false,
                contentType: false,
                crossOrigin: true,
                success: function (jsonData) {
                    if (!jsonData.error) {
                        var left_time = jsonData.round.remindtime;
                        left_time -= {{$gameInfo->LAST_LIMIT_TIME}};
                        let rountNum = parseInt(jsonData.round.dround_no);
                        $('#cur_round').html('[ ' + ' (' + rountNum + ')회 ]');
                        $('#round').val(jsonData.round.ground_no);
                        var min = parseInt(left_time / 60);
                        var sec = left_time - (min * 60);
                        if (sec < 0) {
                            if (min > 0) {
                                sec += 60;
                                --min;
                            } else {
                                min = 0;
                                sec = 0;
                            }
                        }

                        if (jsonData.round.remindtime > {{$gameInfo->GAME_PERIOD}} -30 && jsonData.round.remindtime < {{$gameInfo->GAME_PERIOD}})
                        {
                            betHistoryList();
                            initUserInfo();
                        }

                        if (left_time == 0) {
                            init_betlist = false;

                            $('#betcloserest').html('00:00');
                            $('#betlock').html(
                                '<span style="top: 80px; position: relative;">다음회차 준비중</span>');
                            $('#betlock').addClass('bet_lock');
                            $('#betlock').css('display', 'block');
                        } else if (left_time > 0) {
                            if (!init_betlist) {
                                init_betlist = true;
                            }

                            if (min < 10) min = '0' + min;
                            if (sec < 10) sec = '0' + sec;

                            $('#betcloserest').html(min + ':' + sec);
                            $('#betlock').removeClass('bet_lock');
                            $('#betlock').css('display', 'none');
                        } else {
                            $('#betcloserest').html('00:00');
                            $('#betlock').html(
                                '<span style="top: 80px; position: relative;">배팅이 마감되었습니다</span>');
                            $('#betlock').addClass('bet_lock');
                            $('#betlock').css('display', 'block');
                        }
                    }else{
                        wait(false);
                        clearInterval(timer);
                        $('.bet_label').css('background','');
                        $('.bet_label').addClass('lock');
                        $('.btn_betting_confirm').addClass('lock');
                    
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
        var timer = setInterval(onTimer, 1000);

        function selectbtn(obj) {
            if ($('#marcket_info').val() != '')
            {
                let info = $('#marcket_info').val().split('-');
                $('#' + info[0]).css('background','linear-gradient'+ (info[1] == 0 ? '(#3b78ca, #0035c7)' : '(#f10000, #910000)'));
            }
            var bet_ratio = $(obj).attr('bet_ratio');
            var bet_title = $(obj).attr('bet_title');
            var bet_maxmoney = $(obj).attr('bet_maxmoney');
            $('#bet_ratio').val(bet_ratio);
            $('#bet_title').val(bet_title);
            $('#bet_maxmoney').val(bet_maxmoney);
            $('#cur_title').html(bet_title);
            $('#cur_ratio').html(bet_ratio);
            $('#marcket_info').val($(obj).attr('id') + '-' + $(obj).attr('colorType'));
            $(obj).css('background', '#2a2a2a');
            betCalculate();
        }

        function betCalculate() {
            var betAmount = $('#bet_money').val().replace(/,/gi, '');
            var betRatio = $('#bet_ratio').val();
            $('#win_money').val(insertComma((betAmount * betRatio).toFixed(0)));
        }

        function amountreset() {
            $('#bet_money').val(0);
            betCalculate();
        }

        function allin() {

            Swal.fire({
                icon: 'info',
                title: '알림',
                html: '최대금액은 배팅 가능한 최대금액 (' +insertComma('{{$betMax}}')+ ' 원) 이 배팅됩니다. 의도하신것이 맞습니까?',
                showCancelButton: true,
                confirmButtonText: '확인',
                cancelButtonText: '취소',
            }).then(result =>{
                if (result.value)
                {
                    var cur_money = parseInt($('#cur_money').html().replace(/,/gi, ''));
                    var bet_maxmoney = parseInt($('#bet_maxmoney').val());

                    if (bet_maxmoney > cur_money) {
                        $('#bet_money').val(0);
                        Swal.fire({
                            icon: 'info',
                            title: '알림',
                            html: '잔액이 부족합니다. 잔액을 충전 하세요.',
                            confirmButtonText: '확인'
                        });
                    } else {
                        $('#bet_money').val(insertComma(bet_maxmoney.toString()));
                    }

                    betCalculate();
                }
            });
        }

        function setamount() {
            var amount = $('#bet_money').val().replace(/,/gi, '');

            if (isNaN(amount)) {
                $('#bet_money').val(0);
            } else {
                $('#bet_money').val(insertComma(amount));
            }

            var cur_money = parseInt($('#cur_money').html().replace(/,/gi, ''));
            if (amount > cur_money) {
                amount = 0;
                Swal.fire({
                    icon: 'error',
                    title: '알림',
                    html: '잔액이 부족합니다.',
                    confirmButtonText: '확인'
                });
            }

            $('#bet_money').val(insertComma(amount.toFixed(0)));
            betCalculate();
        }

        function selectMoney(Amount) {
            var bet_money = parseInt($('#bet_money').val().replace(/,/gi, ''));
            if (bet_money > 0) {
                bet_money = bet_money + Amount;
            } else {
                bet_money = Amount;
            }

            var cur_money = parseInt($('#cur_money').html().replace(/,/gi, ''));
            var bet_maxmoney = parseInt($('#bet_maxmoney').val());
            if (bet_money > bet_maxmoney)
            {
                bet_money = bet_money - Amount;
                Swal.fire({
                    icon: 'error',
                    title: '알림',
                    html: '최대금액을 초과합니다.',
                    confirmButtonText: '확인'
                });
            }
            if (bet_money > cur_money) {
                bet_money = bet_money - Amount;
                Swal.fire({
                    icon: 'error',
                    title: '알림',
                    html: '잔액이 부족합니다.',
                    confirmButtonText: '확인'
                });
            }

            $('#bet_money').val(insertComma(bet_money.toFixed(0)));
            betCalculate();
        }

        function insertComma(num) {
            if (num.indexOf(".") == -1) {
                num = num.replace(/,/g, "");
                var num_str = num.toString();
                var result = '';

                for (var i = 0; i < num_str.length; i++) {
                    var tmp = num_str.length - (i + 1);
                    if (i % 3 == 0 && i != 0) result = ',' + result;
                    result = num_str.charAt(tmp) + result;
                }

                return result;
            } else {
                return num;
            }
        }

        function betHistoryList()
        {
            var formData = new FormData();
            formData.append('token', token);
            formData.append('game_id', 0);
            $.ajax({
                type: 'POST',
                url: apiURL + '/api/pbgame/history',
                data: formData,
                processData: false,
                contentType: false,
                crossOrigin: true,
                success: function (jsonData) {
                    let strHtml = '';
                    if (!jsonData.error && jsonData.bets.length > 0)
                    {
                        for(let i = 0; i < jsonData.bets.length; i++)
                        {
                            let bet = jsonData.bets[i];
                            let betTime = new Date(bet.created_at);
                            strHtml += '<tr>';
                            strHtml += '<td align="center">' + betTime.yyyymmdd() + '</td>';
                            strHtml += '<td align="center">' + bet.gameName + '</td>';
                            strHtml += '<td align="center">' + parseInt(bet.ground_no.substring(bet.ground_no.length - 3,bet.ground_no.length)) + '회차</td>';
                            strHtml += '<td align="center">';
                            strHtml += '<span class="left" style="padding-left:10px;">' + $('#' + bet.result).attr('bet_title') + '</span>';
                            strHtml += '</td>';
                            strHtml += '<td align="center">' + insertComma(bet.amount.toString()) + '원</td>';
                            strHtml += '<td align="center">' + bet.rate + '</td>';
                            strHtml += '<td align="center"><b>' + insertComma(bet.win.toString()) + '원</b></td>';
                            strHtml += '<td align="center" class="content4">';
                            if (bet.status == 0) {
                                strHtml += '<b><font color="yellow">결과대기</font></b>';
                                if (i == 0) isNext = true;
                            } else if (bet.status == 1) {
                                strHtml += '<b><font color="green">결과완료</font></b>';
                            } else if (bet.status == 2) {
                                strHtml += '<b><font color="red">정산완료</font></b>';
                            } else if (bet.status == 3) {
                                strHtml += '<b><font color="gray">무효</font></b>';
                            } else if (bet.status == 4) {
                                strHtml += '<b><font color="gray">정산대기</font></b>';
                            }
                            strHtml += '</td>';
                            strHtml += '<td width="10%" align="center" class="content4"></td>';
                            strHtml += '</tr>';
                        }
                    }else{
                        strHtml += '<tr><td class="noresult" colspan="8"><i class="fal fa-stream"></i><br>배팅내역이 존재하지 않습니다</td></tr>';
                    }
                    $('#bet_list').html(strHtml);
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }

        function wait(isShow)
        {
            if (isShow)
            {
                $('#wrap').waitMe({
                    effect : 'bounce',
                    text : '',
                    bg : 'rgba(255,255,255,0.7)',
                    color : '#000'
                });
            }else
            {
                $('#wrap').waitMe('hide');
            }
        }

        function betting() {
            //check balance and max betting
            if (!bettingLock)
            {
                bettingLock = true;
                var cur_money = parseInt($('#cur_money').html().replace(/,/gi, ''));
                var bet_maxmoney = parseInt($('#bet_maxmoney').val());
                var betAmount = $('#bet_money').val().replace(/,/gi, '');
                var round = $('#round').val();
                if (betAmount <= 0 || round == '')
                {
                    betAmount = 0;
                    $('#bet_money').val(insertComma(betAmount.toFixed(0)));
                    Swal.fire({
                        icon: 'error',
                        title: '알림',
                        html: '베팅금액을 확인 하세요.',
                        confirmButtonText: '확인'
                    });
                    bettingLock = false;
                    return;
                } else if (betAmount > cur_money)
                {
                    betAmount = 0;
                    $('#bet_money').val(insertComma(betAmount.toFixed(0)));
                    Swal.fire({
                        icon: 'error',
                        title: '알림',
                        html: '잔액이 부족합니다.',
                        confirmButtonText: '확인'
                    });
                    bettingLock = false;
                    return;
                } else if($('#marcket_info').val() == '')
                {
                    Swal.fire({
                        icon: 'info',
                        title: '알림',
                        html: '마켓을 선택하세요.',
                        confirmButtonText: '확인'
                    });
                    bettingLock = false;
                    return;
                } else if (betAmount > bet_maxmoney)
                {
                    betAmount = 0;
                    $('#bet_money').val(insertComma(betAmount.toFixed(0)));
                    Swal.fire({
                        icon: 'error',
                        title: '알림',
                        html: '최대 금액을 초과 합니다.',
                        confirmButtonText: '확인'
                    });
                    bettingLock = false;
                    return;
                } else{
                    var formData = new FormData();
                    formData.append('token', token);
                    formData.append('game_id', '{{$gameInfo->game}}');
                    formData.append('ground_no', parseInt($('#round').val()));
                    formData.append('result',$('#marcket_info').val().split('-')[0]);
                    formData.append('amount',betAmount);
                    wait(true);
                    $.ajax({
                        type: 'POST',
                        url: apiURL + '/api/pbgame/placebet',
                        data: formData,
                        processData: false,
                        contentType: false,
                        crossOrigin: true,
                        success: function (jsonData) {
                            bettingLock = false;
                            wait(false);
                            let info = $('#marcket_info').val().split('-');
                            $('#' + info[0]).css('background','linear-gradient'+ (info[1] == 0 ? '(#3b78ca, #0035c7)' : '(#f10000, #910000)'));
                            $('#marcket_info').val('');
                            $('#bet_ratio').val(0);
                            betAmount = 0;
                            $('#bet_money').val(insertComma(betAmount.toFixed(0)));
                            $('#win_money').val(0);
                            $('#cur_title').text('');
                            $('#cur_ratio').text('---');
                            if (!jsonData.error) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '알림',
                                    html: '베팅 성공',
                                    confirmButtonText: '확인'
                                });
                                initUserInfo();
                            }else{
                                Swal.fire({
                                    icon: 'error',
                                    title: '알림',
                                    html: '베팅 실패 - ' + jsonData.msg,
                                    confirmButtonText: '확인'
                                });
                            }
                            betHistoryList();
                        },
                        error: function (error) {
                            console.log(error);
                        }
                    });
                }
            }
        }

        function gameDisplay() {
            if ($('#game_player').css('display') == 'none') {
                $('#game_player').css('display', 'block');
            } else {
                $('#game_player').css('display', 'none');
            }
        }
    </script>
</html>
