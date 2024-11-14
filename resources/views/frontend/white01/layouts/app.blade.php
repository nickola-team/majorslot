<!doctype html>
<html>

<head>
    <title>WHITE</title>
    <link rel="shortcut icon" href="/frontend/white01/static/images/favicon/fa.png" type="image/x-icon">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="/frontend/white01/static/fontawesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="/frontend/white01/static/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/frontend/white01/static/css/common.css?v=1731504619">
    <link rel="stylesheet" type="text/css" href="/frontend/white01/static/css/mobile.css?v=1731504619">

    <script type="text/javascript" src="/frontend/white01/static/js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="/frontend/white01/static/js/jQueryUI/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/frontend/white01/static/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/frontend/white01/static/js/utils.js?v=1731504619"></script>
    <script type="text/javascript" src="/frontend/white01/static/js/common.js?v=1731504619"></script>
    <script type="text/javascript" src="/frontend/white01/static/js/func.js?v=1731504619"></script>
    <script type="text/javascript" src="/frontend/white01/static/js/modal.js?v=1731504619"></script>
    @if(Auth::check())
    <script>
        var is_login = 'Y';
    </script>
    @else
    <script>
        var is_login = 'N';
    </script>
    @endif


    <script type="text/javascript">
        function menu_toggle(btn) {

            var nav = $(".app-header-nav");

            if ($(btn).hasClass("opened")) {

                $(btn).removeClass("opened");
                nav.removeClass("opened");
            } else {

                $(btn).addClass("opened");
                nav.addClass("opened");
            }
        }
    </script>


</head>

<body>
    <div id="root">
        <div id="header">
            <div class="top">

                <button class="left-menu-btn mobile" onClick="menu_toggle(this)">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <div class="top-center">
                    <div class="logo" onClick="javascript:location.href='/'">
                        <img src="/frontend/white01/static/images/logo_white.png">
                    </div>

                    <nav class="app-header-nav">
                        <div class="nav-list">
                            <div class="nav-list-buttonbox">
                                <button class="nav-button" data-for="" data-tip="false" onClick="open_modal('deposit');"><i class="fas fa-piggy-bank"></i>입금신청</button>
                            </div>
                            <div class="nav-list-buttonbox">
                                <button class="nav-button" data-for="" data-tip="false" onClick="open_modal('withdraw');"><i class="fas fa-coins"></i>출금신청</button>
                            </div>
                            <div class="nav-list-buttonbox">
                                <button class="nav-button" data-for="" data-tip="false" onClick="open_modal('notice');"><i class="fas fa-newspaper"></i>공지사항</button>
                            </div>
                            <div class="nav-list-buttonbox">
                                <button class="nav-button" data-for="" data-tip="false" onClick="open_modal('qna');"><i class="fas fa-question-circle"></i>고객센터</button>
                            </div>
                            <div class="nav-list-buttonbox">
                                <button class="nav-button" data-for="" data-tip="false" onClick="open_modal('event');"><i class="fas fa-calendar-alt"></i>이벤트</button>
                            </div>
                            <div class="nav-list-buttonbox">
                                <button class="nav-button" data-for="" data-tip="false" onClick="open_modal('msg');"><i class="fas fa-envelope"></i>쪽지</button>
                            </div>
                        </div>
                    </nav>

                    <div class="top-btn">
                        @if(Auth::check())
                        <div>
                            <div class="user-nick">
                                <span class="welcom">환영합니다.</span><span class="nick">{{auth()->user()->username}}</span>
                            </div>
                            <div class="user-money">
                                <span>카지노({{number_format(auth()->user()->balance, 0)}}원)</span>
                            </div>
                            <div class="user-point">
                                <button type="button" onClick="trans_point();">머니전환</button>
                                <span onClick="trans_point();">포인트({{number_format(auth()->user()->deal_balance,0)}}P)</span>
                            </div>
                        </div>
                        <button class="logout-button btn c-y" onClick="javascript:location.href='/logout'"><i class="fas fa-sign-in-alt"></i>로그아웃</button>
                        @else
                        <button class="login-button btn" onClick="open_modal('login');"><i class="fas fa-sign-in-alt"></i>로그인</button>
                        <button class="register-button btn btn--shockwave is-active c-y" onClick="open_modal('reg');"><i class="fas fa-user-plus"></i>회원가입</button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="slide desktop">
                <div class="container">
                    <div id="slideshow" class="carousel slide" data-ride="carousel" data-interval="11000">
                        <ol class="carousel-indicators">
                            <li data-target="#slideshow" data-slide-to="0" class="active"></li>
                            <li data-target="#slideshow" data-slide-to="1"></li>
                            <li data-target="#slideshow" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner">
                            <style>
                                .slide .elem {
                                    position: relative;
                                }

                                .slide .elem img:not(.s_bg) {
                                    position: absolute;
                                    top: 0;
                                }

                                .slide .elem .s_bg {
                                    position: relative;
                                }

                                .slide_1 .s_l {
                                    opacity: 0;
                                }

                                .slide_1 .s_r,
                                .slide_2 .s_l,
                                .slide_2 .s_l_2,
                                .slide_3 .s_l {
                                    opacity: 0;
                                    animation: from_left 10s ease infinite;
                                }

                                .slide_1 .s_c,
                                .slide_3 .s_r {
                                    opacity: 0;
                                    animation: from_right 10s ease infinite;
                                    animation-delay: 0.5s;
                                }

                                .slide_2 .s_l_2 {
                                    animation-delay: 0.5s;
                                }

                                .slide_2 .s_c {
                                    opacity: 0;
                                    animation: from_bottom 10s ease infinite;
                                    animation-delay: 1s;
                                }

                                .slide_1 .s_r {
                                    left: -1400px;
                                }

                                .slide_1 .s_c {
                                    left: 600px;
                                }

                                @keyframes from_left {
                                    0% {
                                        opacity: 0;
                                        transform: translateX(-15%);
                                    }
                                    2% {
                                        opacity: 0;
                                        transform: translateX(-15%);
                                    }
                                    10% {
                                        opacity: 1;
                                        transform: translateX(0);
                                    }
                                    95% {
                                        opacity: 1;
                                        transform: translateX(0);
                                    }
                                    100% {
                                        opacity: 0;
                                        transform: translateX(0);
                                    }
                                }

                                @keyframes from_right {
                                    0% {
                                        opacity: 0;
                                        transform: translateX(15%);
                                    }
                                    2% {
                                        opacity: 0;
                                        transform: translateX(15%);
                                    }
                                    10% {
                                        opacity: 1;
                                        transform: translateX(0);
                                    }
                                    95% {
                                        opacity: 1;
                                        transform: translateX(0);
                                    }
                                    100% {
                                        opacity: 0;
                                        transform: translateX(0);
                                    }
                                }

                                @keyframes from_bottom {
                                    0% {
                                        opacity: 0;
                                        transform: translateY(15%);
                                    }
                                    2% {
                                        opacity: 0;
                                        transform: translateY(15%);
                                    }
                                    10% {
                                        opacity: 1;
                                        transform: translateY(0);
                                    }
                                    95% {
                                        opacity: 1;
                                        transform: translateY(0);
                                    }
                                    100% {
                                        opacity: 0;
                                        transform: translateY(0);
                                    }
                                }

                                @keyframes event_blink {
                                    0% {
                                        opacity: 0;
                                        box-shadow: 1px 1px 1px 1px #f18f8f;
                                    }
                                    50% {
                                        opacity: 0.5;
                                        box-shadow: 1px 1px 14px 1px #f18f8f;
                                    }
                                    100% {
                                        opacity: 0;
                                        box-shadow: 1px 1px 1px 1px #f18f8f;
                                    }
                                }

                                @keyframes effect_extension {
                                    0% {
                                        height: 0px;
                                        margin-top: 150px;
                                        transform: rotateY(0deg);
                                    }
                                    10% {
                                        height: 150px;
                                        margin-top: 0px;
                                        transform: rotateY(0deg);
                                    }
                                    30% {
                                        transform: rotateY(360deg);
                                    }
                                    95% {
                                        height: 150px;
                                        margin-top: 0px;
                                        transform: rotateY(360deg);
                                    }
                                    100% {
                                        height: 0px;
                                        margin-top: 150px;
                                        transform: rotateY(0deg);
                                    }
                                }

                                .s_infomation {
                                    position: absolute;
                                    width: 900px;
                                    height: 0px;
                                    left: calc(50% - 450px);
                                    top: calc(50% - 20px);
                                    background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgb(0 0 0 / 60%), rgb(0 0 0 / 0%));
                                    display: flex;
                                    flex-wrap: wrap;
                                    justify-content: center;
                                    align-content: center;
                                    overflow: hidden;
                                    animation: effect_extension 10s ease infinite;
                                    animation-delay: 1s;
                                }

                                .s_infomation:before,
                                .s_infomation:after {
                                    content: "";
                                    height: 1px;
                                    width: 100%;
                                    position: absolute;
                                    background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgb(255 255 255), rgb(255 255 255 / 0%));
                                }

                                .s_infomation:before {
                                    top: 0px;
                                }

                                .s_infomation:after {
                                    bottom: 0px;
                                }

                                .s_infomation .event {
                                    border-bottom: 1px solid #fff;
                                    box-shadow: 1px 1px 14px 1px #f18f8f;
                                    font-size: 25px;
                                    padding: 2px 20px;
                                    width: 100%;
                                    height: 50px;
                                    background-color: #ff2e2ec9;
                                    animation: event_blink 2s ease infinite;
                                }

                                .s_infomation .title {
                                    width: 100%;
                                    font-size: 35px;
                                    height: 60px;
                                    color: orange;
                                    text-shadow: 1px 1px 7px #e15e11;
                                    text-align: center;
                                }

                                .s_infomation .content {
                                    width: 100%;
                                    font-size: 26px;
                                    color: #37b0cb;
                                    text-shadow: 1px 1px 7px #297dc5;
                                    text-align: center;
                                }
                            </style>
                            <div class="elem slide_1 carousel-item active">
                                <img class="s_bg" src="/frontend/white01/static/images/slide_1_bg.png">
                                <img class="s_c" src="/frontend/white01/static/images/slide_1_c.png">
                                <img class="s_r" src="/frontend/white01/static/images/slide_1_r.png">
                                <div class="s_infomation">
                                    <!-- <button class="event">EVENT</button> -->
                                    <div class="title"><span>페이백 5%~10%</span></div>
                                    <div class="content"><span>최대 100만원 지급</span></div>
                                </div>
                            </div>
                            <div class="elem slide_2 carousel-item">
                                <img class="s_bg" src="/frontend/white01/static/images/slide_2_bg.png">
                                <img class="s_l" src="/frontend/white01/static/images/slide_2_l_1.png">
                                <img class="s_l_2" src="/frontend/white01/static/images/slide_2_l_2.png">
                                <img class="s_c" src="/frontend/white01/static/images/slide_2_c.png">
                                <div class="s_infomation">
                                    <!-- <button class="event">EVENT</button> -->
                                    <div class="title"><span>휴면회원 이벤트 15%지급</span></div>
                                    <div class="content"><span>복귀후 충전시 최대 50만원지급</span></div>
                                </div>
                            </div>
                            <div class="elem slide_3 carousel-item">
                                <img class="s_bg" src="/frontend/white01/static/images/slide_3_bg.png">
                                <img class="s_l" src="/frontend/white01/static/images/slide_3_l.png">
                                <img class="s_r" src="/frontend/white01/static/images/slide_3_r.png">
                                <div class="s_infomation">
                                    <!-- <button class="event">EVENT</button> -->
                                    <div class="title"><span>지인추천 이벤트</span></div>
                                    <div class="content"><span>추천인 10만 + 지인 10만지급</span></div>
                                </div>
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#slideshow" role="button" data-slide="prev">
							<span class="carousel-control-prev-icon" aria-hidden="true"></span>
							<span class="sr-only">Previous</span>
						</a>
                        <a class="carousel-control-next" href="#slideshow" role="button" data-slide="next">
							<span class="carousel-control-next-icon" aria-hidden="true"></span>
							<span class="sr-only">Next</span>
						</a>
                    </div>
                </div>
            </div>
        </div>

        <div id="body">
            <script type="text/javascript">
                function change_cate(target, li) {
                    $("div.game-list").css("display", "none");
                    $("div.game-list." + target).css("display", "block");

                    $("div.cate li").removeClass("active");
                    $("div.game-type-list button.game-type").removeClass("active");
                    $(li).addClass("active");

                }


                function go_lobby(type, code, is_use, id = "", ban_code = "") {

                    if (is_login == "Y") {
                    	alert("점검중입니다.");
                    	return false;
                    }else{
                      alert("로그인 후 사용하세요");
                      return false;
                    }

                    $.get("/game/game_lobby", {
                        type: type,
                        code: code,
                        id: id,
                        ban_code: ban_code
                    }, function(data) {

                        var data = JSON.parse(data);

                        if (!data.result) {
                            alert(data.ment);
                        } else {

                            if (type == "slot" || type == "evol_s" || type == "ban" || type == "ban_c") {

                                $(".modal.gameModal").find(".information span.warning").empty();
                                $(".modal.gameModal").find("h1.title").text(data.title);
                                $(".modal.gameModal").find(".gamelist-container").html(data.content);
                                $(".modal.gameModal").find(".search-text").val("");
                                $(".modal.gameModal").removeClass("hidden-search");
                                $(".modal.gameModal").addClass("show-search");
                                $(".modal.gameModal").modal("show");

                            } else {
                                if (data.warning_ment != "") {
                                    var check = "<div class='form-check form-switch'>";
                                    check += "<input class='' type='checkbox' id='bet_agree'/>";
                                    check += "<label class='' for='bet_agree'>동의 후 게임진행</label>";
                                    check += "</div>";

                                    data.warning_ment = data.warning_ment + check;
                                    $(".modal.gameModal").find(".information span.warning").html(data.warning_ment);
                                } else {
                                    $(".modal.gameModal").find(".information span.warning").empty();
                                }
                                $(".modal.gameModal").find("h1.title").text(data.title);
                                $(".modal.gameModal").find(".gamelist-container").html(data.content);
                                $(".modal.gameModal").removeClass("show-search");
                                $(".modal.gameModal").addClass("hidden-search");
                                $(".modal.gameModal").modal("show");
                            }
                        }

                    })

                }


                function view_game_list(type) {

                    var key = "";

                    if (type == "searched") {
                        key = getCookie("search_keys");
                    } else {
                        key = $(".search-form input.all").val();
                    }

                    if (key == "" || key === undefined) {
                        alert("검색값이 비어있습니다.");
                        return;
                    }

                    $.get("/game/search_game", {
                        key: key
                    }, function(data) {

                        var data = JSON.parse(data);

                        if (!data.result) {
                            alert(data.ment);
                        } else {
                            $(".modal.gameModal").find(".information span.warning").empty();
                            $(".modal.gameModal").find("h1.title").text(data.title);
                            $(".modal.gameModal").find(".gamelist-container").html(data.content);
                            $(".modal.gameModal").modal("show");
                            $(".modal.gameModal").find(".search-text").val("");
                            if (type != "searched") addCookie("search_keys", key);
                        }

                    })

                }
            </script>

            <div class="cate">
                <ul>
                    <li class="active" onClick="change_cate('live', this)">
                        <span>라이브카지노</span>
                    </li>
                    <li class="" onClick="change_cate('hotel', this)">
                        <span>호텔영상카지노</span>
                    </li>
                    <li class="" onClick="change_cate('slot', this)">
                        <span>슬롯게임</span>
                    </li>
                </ul>
            </div>

            <div class="game-list live">
                <div class="category">
                    <span class="subtitle">라이브카지노</span>
                </div>
                <div class="comp-list">

                    <div class="evol comp-panel w-ba sc-btn id_13  sample1" onclick="casinoGameStart('gvo');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/casino/evolution_baccarat_sicbo.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/evolution_baccarat_sicbo.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">에볼루션</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="impe comp-panel w-ba sc-btn id_18  sample1" onclick="go_lobby('impe', 'pragmatic', 'Y');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/casino/pragmatic.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/pragmatic.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">프라그매틱 라이브</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="live comp-panel w-ba sc-btn id_23  sample1" onclick="go_lobby('live', '5', 'Y');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/casino/5.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/5.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">섹시카지노</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="impe comp-panel w-ba sc-btn id_6  sample1" onclick="go_lobby('impe', 'og_plus', 'Y');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/casino/og_plus.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/og_plus.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">오리엔탈 플러스</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="impe comp-panel w-ba sc-btn id_7  sample1" onclick="go_lobby('impe', 'bbin_asia', 'Y');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/casino/bbin_asia.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/bbin_asia.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">BBIN아시아</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="impe comp-panel w-ba sc-btn id_3  sample1" onclick="go_lobby('impe', 'dream', 'Y');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/casino/dream.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/dream.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">드림 게이밍</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="impe comp-panel w-ba sc-btn id_8  sample1" onclick="go_lobby('impe', 'ag_asia', 'Y');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/casino/ag_asia.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/ag_asia.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">아시아 게이밍</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="live comp-panel w-ba sc-btn id_24  sample1" onclick="go_lobby('live', '12', 'Y');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/casino/12.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/12.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">WM 카지노</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="live comp-panel w-ba sc-btn id_22  sample1" onclick="go_lobby('live', '1', 'Y');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/casino/1.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/1.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">VIVO</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="live comp-panel w-ba sc-btn id_16  sample1" onclick="go_lobby('live', '4', 'Y');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/casino/4.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/4.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">올벳</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>


                </div>
            </div>


            <div class="game-list hotel" style="display: none;">
                <div class="category">
                    <span class="subtitle">호텔영상카지노</span>
                </div>
                <div class="comp-list">
                    <div class="impe comp-panel w-ba sc-btn id_35 " onclick="go_lobby('impe', 'ag_cagayan', 'Y', '35');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/casino/ag_cagayan.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba impe">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/ag_cagayan.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">AG 카가얀</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="bota comp-panel w-ba sc-btn id_21 " onclick="go_lobby('bota', 'SOLAIRE', 'Y', '21');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/casino/SOLAIRE.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba bota">
                                <div class="comp-bota"><img src="static/images/comp/casino/bota_.png"></div>
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/SOLAIRE.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">솔레어</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="bota comp-panel w-ba sc-btn id_10 " onclick="go_lobby('bota', 'PD', 'Y', '10');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/casino/PD.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba bota">
                                <div class="comp-bota"><img src="static/images/comp/casino/bota_.png"></div>
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/PD.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">리조트월드</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="bota comp-panel w-ba sc-btn id_37 " onclick="go_lobby('bota', 'NUSTAR', 'Y', '37');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/casino/NUSTAR.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba bota">
                                <div class="comp-bota"><img src="static/images/comp/casino/bota_.png"></div>
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/NUSTAR.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">누스타</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="bota comp-panel w-ba sc-btn id_9 " onclick="go_lobby('bota', 'TG', 'Y', '9');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/casino/TG.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba bota">
                                <div class="comp-bota"><img src="static/images/comp/casino/bota_.png"></div>
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/TG.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">뉴리조트</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="bota comp-panel w-ba sc-btn id_20 " onclick="go_lobby('bota', 'HANN', 'Y', '20');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/casino/HANN.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba bota">
                                <div class="comp-bota"><img src="static/images/comp/casino/bota_.png"></div>
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/HANN.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">HANN 카지노</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                </div>
            </div>

            <div class="game-list slot" style="display: none;">
                <div class="category">
                    <span class="subtitle">슬롯게임</span>
                </div>
                <div class="search-form">
                    <button class="search-btn searched" onclick="view_game_list('searched');" style="left: 5px;">최근검색게임</button>
                    <input class="search-text all" type="text" placeholder="게임이름으로 검색">
                    <button class="search-btn" onclick="view_game_list();">게임검색</button>
                </div>
                <div class="comp-list">

                    <div class="ban comp-panel w-ba sc-btn id_213 sample2 " onclick="go_lobby('ban', 'pp', 'Y', '', 'sg_pp');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/pragmatic.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/pragmatic.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">프라그매틱플레이</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="slot comp-panel w-ba sc-btn id_154 sample2 " onclick="go_lobby('slot', '14', 'Y', '', '');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/pgs.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/pgs.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">Pocket Game</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="evol_s comp-panel w-ba sc-btn id_165 sample2 " onclick="go_lobby('evol_s', 'nolimit city', 'Y', '', '');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/nolimitcity.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/nolimitcity.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">노리밋 시티</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="slot comp-panel w-ba sc-btn id_83 sample2 " onclick="go_lobby('slot', '51', 'Y', '', '');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/playngo.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/playngo.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">플레이앤고</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="slot comp-panel w-ba sc-btn id_73 sample2 " onclick="go_lobby('slot', '37', 'Y', '', '');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/booongo.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/booongo.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">부운고</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="slot comp-panel w-ba sc-btn id_63 sample2 " onclick="go_lobby('slot', '70', 'Y', '', '');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/micro.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/micro.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">마이크로 게이밍</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="ban_c comp-panel w-ba sc-btn id_198 sample2 " onclick="go_lobby('ban_c', 'habanero', 'Y', '', 'center_slot');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/habanero.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/habanero.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">하바네로</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="slot comp-panel w-ba sc-btn id_64 sample2 " onclick="go_lobby('slot', '23', 'Y', '', '');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/cq9.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/cq9.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">시큐9</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="slot comp-panel w-ba sc-btn id_175 sample2 " onclick="go_lobby('slot', '60', 'Y', '', '');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/spade.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/spade.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">스페이드 게이밍</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="slot comp-panel w-ba sc-btn id_149 sample2 " onclick="go_lobby('slot', '11', 'Y', '', '');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/novo.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/novo.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">노보매틱</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="slot comp-panel w-ba sc-btn id_158 sample2 " onclick="go_lobby('slot', '52', 'Y', '', '');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/manna.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/manna.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">만나플레이</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="slot comp-panel w-ba sc-btn id_70 sample2 " onclick="go_lobby('slot', '33', 'Y', '', '');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/genesis.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/genesis.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">제네시스</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="slot comp-panel w-ba sc-btn id_71 sample2 " onclick="go_lobby('slot', '34', 'Y', '', '');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/triple.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/triple.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">트리플프로핏</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="slot comp-panel w-ba sc-btn id_74 sample2 " onclick="go_lobby('slot', '38', 'Y', '', '');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/bgaming.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/bgaming.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">비게이밍</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="slot comp-panel w-ba sc-btn id_76 sample2 " onclick="go_lobby('slot', '40', 'Y', '', '');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/evoplay.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/evoplay.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">에보플레이</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="slot comp-panel w-ba sc-btn id_77 sample2 " onclick="go_lobby('slot', '41', 'Y', '', '');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/dream.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/dream.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">드림테크</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="slot comp-panel w-ba sc-btn id_78 sample2 " onclick="go_lobby('slot', '72', 'Y', '', '');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/asia.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/asia.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">아시아 게이밍</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="ban_c comp-panel w-ba sc-btn id_190 sample2 " onclick="go_lobby('ban_c', '1x2 gaming', 'Y', '', 'center_slot');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/1x2gaming.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/1x2gaming.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">1X2 게이밍</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="slot comp-panel w-ba sc-btn id_56 sample2 " onclick="go_lobby('slot', '6', 'Y', '', '');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/bbtech.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/bbtech.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">비비테크</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="slot comp-panel w-ba sc-btn id_159 sample2 " onclick="go_lobby('slot', '55', 'Y', '', '');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/aspect.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/aspect.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">아스펙 게이밍</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="slot comp-panel w-ba sc-btn id_157 sample2 " onclick="go_lobby('slot', '54', 'Y', '', '');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/skywind_s.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/skywind_s.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">스카이윈드</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="ban_c comp-panel w-ba sc-btn id_197 sample2 " onclick="go_lobby('ban_c', 'gameart', 'Y', '', 'center_slot');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/gameart.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/gameart.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">게임아트</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="evol_s comp-panel w-ba sc-btn id_162 sample2 " onclick="go_lobby('evol_s', 'blueprint gaming', 'Y', '', '');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/blueprint.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/blueprint.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">블루프린트 게이밍</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="slot comp-panel w-ba sc-btn id_214 sample2 " onclick="go_lobby('slot', '64', 'Y', '', '');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/octoplay.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/octoplay.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">옥토플레이</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="ban_c comp-panel w-ba sc-btn id_206 sample2 " onclick="go_lobby('ban_c', 'playson', 'Y', '', 'center_slot');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/playson.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/playson.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">플레이슨</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="slot comp-panel w-ba sc-btn id_215 sample2 " onclick="go_lobby('slot', '67', 'Y', '', '');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/hacksaw.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/hacksaw.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">핵쏘게이밍</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="evol_s comp-panel w-ba sc-btn id_219 sample2 " onclick="go_lobby('evol_s', 'kalamba', 'Y', '', '');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/kalamba.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/kalamba.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">칼람바</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="evol_s comp-panel w-ba sc-btn id_220 sample2 " onclick="go_lobby('evol_s', 'revolver', 'Y', '', '');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/revolver.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/revolver.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">리볼버</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="evol_s comp-panel w-ba sc-btn id_221 sample2 " onclick="go_lobby('evol_s', 'onetouch', 'Y', '', '');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/onetouch.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/onetouch.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">원터치</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="ban_c comp-panel w-ba sc-btn id_207 sample2 " onclick="go_lobby('ban_c', 'playstar', 'Y', '', 'center_slot');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/playstar.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/playstar.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">플레이스타</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="slot comp-panel w-ba sc-btn id_216 sample2 " onclick="go_lobby('slot', '68', 'Y', '', '');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/slotmill.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/slotmill.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">슬롯밀</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="ban_c comp-panel w-ba sc-btn id_208 sample2 " onclick="go_lobby('ban_c', 'red tiger', 'Y', '', 'center_slot');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/redtiger.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/redtiger.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">레드타이거</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="ban_c comp-panel w-ba sc-btn id_211 sample2 " onclick="go_lobby('ban_c', 'thunderkick', 'Y', '', 'center_slot');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/thunderkick.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/thunderkick.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">썬더킥</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="slot comp-panel w-ba sc-btn id_217 sample2 " onclick="go_lobby('slot', '72', 'Y', '', '');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/xin.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/xin.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">씬게이밍</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>
                    <div class="slot comp-panel w-ba sc-btn id_218 sample2 " onclick="go_lobby('slot', '75', 'Y', '', '');">
                        <div class="panel-inside w-ba">
                            <div class="panel-top">
                                <img class="comp-img" src="/frontend/white01/static/images/comp/slot/jili.jpg">
                                <div class="comp-play"><i class="far fa-play-circle"></i></div>
                            </div>
                            <div class="panel-middle w-ba">
                                <div class="g-logo w-ba">
                                    <img class="comp-logo" src="/frontend/white01/static/images/comp/logo-gold/jili.png">
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="comp-name-kr">질리</span>
                            </div>
                            <div class="glass">
                            </div>
                        </div>
                        <div class="comp-preparing"><button>점검중</button></div>
                    </div>


                </div>
            </div>
        </div>

        <div id="footer">
            <div class="partners desktop">
                <div class="logos">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/ag_cagayan.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/SOLAIRE.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/PD.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/NUSTAR.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/TG.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/HANN.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/evolution_baccarat_sicbo.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/pragmatic.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/5.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/bbin_asia.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/dream.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/ag_asia.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/12.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/1.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/4.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/pragmatic.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/pgs.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/nolimitcity.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/playngo.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/booongo.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/micro.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/habanero.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/cq9.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/spade.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/novo.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/manna.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/genesis.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/triple.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/bgaming.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/evoplay.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/dream.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/asia.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/1x2gaming.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/bbtech.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/aspect.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/skywind_s.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/gameart.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/blueprint.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/octoplay.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/playson.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/hacksaw.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/kalamba.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/revolver.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/onetouch.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/playstar.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/slotmill.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/redtiger.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/thunderkick.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/xin.png">
                    <img class="partner" src="/frontend/white01/static/images/comp/logo-gold/jili.png">
                </div>
            </div>
            <div class="company-info">
                <div class="callcenter">
                    <div class="kakao">
                        <img src="/frontend/white01/static/images/kakao.png">
                        <div></div>
                    </div>
                    <div class="telegram">
                        <img src="/frontend/white01/static/images/telegram.png">
                        <div></div>
                    </div>
                </div>
                <div class="footer-wrap">
                    <div class="b_logo"><img src="/frontend/white01/static/images/logo_white.png"></div>
                    <div class="copyright">Copyright ⓒ 2018 All rights reserved.</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function login_proc_() {

            $form = $("form[name=login_form]");
            var userid = $form.find("input[name=login_id]").val();
            var userpw = $form.find("input[name=login_pw]").val();

            if (userid.length < 1) {
                alert('아이디를 입력 해 주세요.');
                return false;
            } else if (userpw.length < 1) {
                alert('비밀번호를 입력 해 주세요.');
                return false;
            }

            var formData = new FormData();
            formData.append("_token", $("#_token").val());
            formData.append("username", userid);
            formData.append("password", userpw);
            $.ajax({
            type: "POST",
            url: "/api/login",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            success: function (data, status) {
                if (data.error) {
                    alert(data.msg);
                }

                location.reload();
            },
            error: function (err, xhr) {
            }
        });
        }


        function user_info_qna_toggle() {

            $("div.check_info").toggle(500, function() {
                const check_info_type = $(this).css("display");

                if (check_info_type == "none") {
                    $("div.user_info_qna a").text("로그인이 되지 않는다면 ??? <<< 클릭");
                } else {
                    $("div.user_info_qna a").text("로그인 화면으로 이동");
                }

                $("form[name=login_form]").toggle(500);
            });
        }


        function user_info_qna() {

            const check_id = $("div.check_info input[name=check_id]").val();
            const check_nick = $("div.check_info input[name=check_nick]").val();
            const check_phone = $("div.check_info input[name=check_phone]").val();

            if (!check_phone) {
                alert("전화번호는 필수입니다.");
                return false;
            }

            const title = "고객정보 문의입니다.";
            const question = "아이디 : [" + check_id + "] / 닉네임 : [" + check_nick + "] / 전화번호 : [" + check_phone + "]";

            $.get("/common/is_black_user", {
                check_id: check_id,
                check_phone: check_phone
            }, function(data) {
                let r1_data = JSON.parse(data);
                if (!r1_data.result) {
                    $.post("/question", {
                        title: title,
                        question: question
                    }, function(data) {
                        let r_data = JSON.parse(data);
                        alert(r_data.ment);

                        $(".modal.loginModal").modal("hide");
                    })
                } else {
                    console.log(r1_data.ment);
                    $(".modal.loginModal").modal("hide");
                }
            })

        }
    </script>

    <div class="modal loginModal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog login">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="btn-group">
                        <button class="modal-close-btn" data-dismiss="modal"></button>
                    </div>
                    <span>MEMBER LOGIN</span>
                </div>
                <div class="modal-body">
                    <div class="modal-panel">
                        <form name="login_form" method="POST">
                            <div class="form-container">
                                <div class="form-group">
                                    <div class="infos">
                                        <div class="input-container">
                                            <input type="text" name="login_id" maxlength="12" placeholder="아이디">
                                            <i class="fas fa-user-tie icon-label"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="infos">
                                        <div class="input-container">
                                            <input type="password" name="login_pw" maxlength="12" placeholder="비밀번호">
                                            <i class="fas fa-unlock-alt icon-label"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-footer">
                                <button type="button" onclick="login_proc_();" class="yellow-bg login-btn" style="width: 38%;">
                            <span><i class="fas fa-sign-in-alt"></i> 로그인</span>
                        </button>
                                <button type="button" class="join-link yellow-bg" style="width: 38%;" onClick="open_modal('reg');">
                            <span><i class="fas fa-user-plus"></i> 회원가입</span>
                        </button>
                            </div>
                        </form>

                        <style type="text/css">
                            div.user_info_qna {
                                text-align: center;
                                margin: 20px 0 0 0;
                            }

                            div.user_info_qna a {
                                color: #5ef113;
                            }
                        </style>
                        <div class="check_info" style="display:none;">
                            <div class="form-container">
                                <div class="form-group">
                                    <div class="infos">
                                        <div class="input-container">
                                            <input type="text" name="check_id" maxlength="12" placeholder="아이디">
                                            <i class="fas fa-user-tie icon-label"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="infos">
                                        <div class="input-container">
                                            <input type="text" name="check_nick" maxlength="12" placeholder="닉네임">
                                            <i class="fas fa-user-tie icon-label"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="infos">
                                        <div class="input-container">
                                            <input type="text" name="check_phone" maxlength="12" placeholder="연락처">
                                            <i class="fas fa-phone-alt icon-label"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-footer">
                                <button type="button" onclick="user_info_qna();" class="yellow-bg login-btn" style="width:80%">
                                <span><i class="fas fa-question-circle"></i> 계정정보 문의하기</span>
                            </button>
                            </div>
                        </div>

                        <div class="user_info_qna">
                            <a href="#" onClick="user_info_qna_toggle()">로그인이 되지 않는다면 ??? <<< 클릭</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function registration() {

            $datas = $("form[name=regist] input, form[name=regist] select");

            var state = true;
            var user_pw = "";
            var se_pw = "";
            var params = {};

            $datas.each(function(idx, item) {
                $item = $(item);
                var title = $item.closest(".form-group").find(".labels span").text();
                var key = $item.attr("name");
                var val = $item.val();

                if (val == '' && key != "recommender") {

                    alert("[" + title + "] 란이 비어 있습니다.");
                    state = false;
                    return false;
                }

                if (key == "user_id") {

                    if (!is_alpha_num(val)) {
                        alert("아이디는 영어, 숫자만 가능합니다.");
                        state = false;
                        return false;
                    }

                    if (!check_size(val, 3, 10)) {
                        alert("아이디는 3 ~ 10자만 가능합니다.");
                        state = false;
                        return false;
                    }

                    if ($item.data("is_duple") == "Y") {
                        alert("[" + title + "]을 중복체크 해 주세요");
                        state = false;
                        return false;
                    }
                } else if (key == "user_pw1") {

                    if (!check_size(val, 6, 20)) {
                        alert("비밀번호는 6자 이상만 가능합니다.");
                        state = false;
                        return false;
                    } else {
                        user_pw = val;
                    }

                } else if (key == "user_pw2") {

                    if (user_pw != val) {
                        alert("비밀번호가 일치하지 않습니다.");
                        state = false;
                        return false;
                    }
                } else if (key == "user_nick") {

                    if ($item.data("is_duple") == "Y") {
                        alert("[" + title + "]을 중복체크 해 주세요");
                        state = false;
                        return false;
                    }

                } else if (key == "user_hp") {

                    if (!is_num(val)) {
                        alert("전화번호는 숫자만 가능합니다.");
                        state = false;
                        return false;
                    }
                } else if (key == "se_pw1") {
                    se_pw = val;

                } else if (key == "se_pw2") {

                    if (se_pw != val) {
                        alert("2차 비밀번호가 일치하지 않습니다.");
                        state = false;
                        return false;
                    }
                }

                params[key] = val;
            });


            if (!state) return false;

            // $.post("/registration", params, function(data) {
            //     var data = JSON.parse(data);
            //     alert(data.ment);
            //     if (data.result) {
            //         location.reload();
            //         $("button.modal-close-btn").trigger("click");
            //     }
            // })
            var formData = {
                'username':params['user_id'],
                'password':params['user_pw1'],
                'bank_name':params['bankname'],
                'recommender':params['bankuser'],
                'account_no':params['bankaccount'],
                'tel1':params['user_hp'],
                'friend':params['recommender']
            }
            $.ajax({
                url: '/api/join',
                type: 'POST',
                data: formData,
                dataType: "json",
                async: false,
                success: function(result) {
                    if (result.error == false) {
                        alert("등록되었습니다");
                        $("button.modal-close-btn").trigger("click");
                    } else {
                        alert(result.msg);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert("오류가 발생하였습니다");
                }
            });

        }

        function is_alpha_num(str) {

            var reg = /^[A-Za-z0-9]*$/;
            return reg.test(str);
        }

        function is_num(str) {

            var reg = /^[0-9]*$/;
            return reg.test(str);
        }

        function check_size(str, min, max) {

            if (str.length < min || str.length > max) {
                return false;
            } else {
                return true;
            }
        }

        function duple_check(a, type) {

            $ds = $(a).closest(".infos").find("input[name=" + type + "]");
            if(type == 'user_id')
            {
                $.ajax({
                url: '/api/checkid',
                type: 'POST',
                data: {
                    "id": $ds.val()
                },
                dataType: 'json',
                success: function(result) {
                    isSend = 0;
                    if (result.ok === 1) {
                        $ds.data("is_duple", "N");
                    } else {
                        $ds.data("is_duple", "Y");
                        alert('중복된 아이디입니다');
                    }
                },
                error: function(err) {
                
                }
                });
            }
            else
            {
                $ds.data("is_duple", "N");
            }
        }
    </script>
    <div class="modal regModal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="btn-group">
                        <button class="modal-close-btn" data-dismiss="modal"></button>
                    </div>
                </div>
                <div class="modal-banner">
                    <div class="panel">
                        <div class="title-panel">
                            <h1 class="title">회원가입</h1>
                        </div>
                        <div class="information">
                            <span><i class="fas fa-handshake"></i> 환영합니다. 항상 최선을 다하는 WHITE 입니다.</span>
                        </div>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="modal-panel">
                        <form name="regist" method="POST">
                            <div class="form-container">
                                <div class="form-group w-btn">
                                    <div class="labels"><span>아이디</span></div>
                                    <div class="infos">
                                        <div class="input-container">
                                            <input type="text" name="user_id" maxlength="10" placeholder="영문, 숫자 3 ~ 10자">
                                            <i class="fas fa-id-badge icon-label"></i>
                                        </div>
                                        <button type="button" onclick="duple_check(this, 'user_id');" class="form-btn">중복확인</button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="labels">
                                        <span>비밀번호</span>
                                    </div>
                                    <div class="infos">
                                        <div class="input-container">
                                            <input type="password" name="user_pw1" maxlength="12" placeholder="6자 이상">
                                            <i class="fas fa-unlock-alt icon-label"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="labels">
                                        <span>비밀번호확인</span>
                                    </div>
                                    <div class="infos">
                                        <div class="input-container">
                                            <input type="password" name="user_pw2" maxlength="12">
                                            <i class="fas fa-lock icon-label"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group w-btn">
                                    <div class="labels">
                                        <span>닉네임</span>
                                    </div>
                                    <div class="infos">
                                        <div class="input-container">
                                            <input type="text" name="user_nick" maxlength="12" placeholder="2자이상 한글,영문">
                                            <i class="fas fa-user-tag icon-label"></i>
                                        </div>
                                        <button type="button" onclick="duple_check(this, 'user_nick');" class="form-btn">중복확인</button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="labels">
                                        <span>휴대폰번호</span>
                                    </div>
                                    <div class="infos">
                                        <div class="input-container">
                                            <input type="text" name="user_hp" placeholder="‘-’없이 숫자만 입력">
                                            <i class="fas fa-phone-square icon-label"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="labels">
                                        <span>출금비밀번호</span>
                                    </div>
                                    <div class="infos">
                                        <div class="input-container">
                                            <input type="password" name="se_pw1" maxlength="12" placeholder="환전시 이용되는 비밀번호입니다.">
                                            <i class="fas fa-unlock-alt icon-label"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="labels">
                                        <span>출금비번확인</span>
                                    </div>
                                    <div class="infos">
                                        <div class="input-container">
                                            <input type="password" name="se_pw2" maxlength="12">
                                            <i class="fas fa-lock icon-label"></i>
                                        </div>
                                    </div>
                                </div>



                                <div class="form-group">
                                    <div class="labels">
                                        <span>이름</span>
                                    </div>
                                    <div class="infos">
                                        <div class="input-container">
                                            <input type="text" name="bankuser" placeholder="입/출금시 예금주가 같지 않으면 출금이 불가">
                                            <i class="fas fa-user-tie icon-label"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="labels">
                                        <span>은행명</span>
                                    </div>
                                    <div class="infos">
                                        <div class="input-container">
                                            <input type="text" name="bankname" placeholder="케이뱅크,경남은행,저축은행,증권계좌 승인불가">
                                            <i class="fas fa-user-tie icon-label"></i>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="labels">
                                        <span>계좌번호</span>
                                    </div>
                                    <div class="infos" style="display: inline-block;">
                                        <div class="input-container">
                                            <input type="text" name="bankaccount" placeholder="‘-’없이 숫자만 입력">
                                            <i class="fas fa-money-check icon-label"></i>
                                        </div>
                                        <div style="text-align: left;">
                                            <span>신한은행 앞자리:562/카카오뱅크 앞자리 3355/7979/7777</span><br>
                                            <span style="color:#fff945">위 계좌번호를 사용하시는 경우 승인처리 불가합니다</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group w-btn">
                                    <div class="labels">
                                        <span>추천코드</span>
                                    </div>
                                    <div class="infos">
                                        <div class="input-container">
                                            <input type="text" name="recommender" placeholder="추천코드 없이 가입 불가">
                                            <i class="fas fa-user-shield icon-label"></i>
                                        </div>
                                        <button type="button" onclick="duple_check(this, 'recommender');" class="form-btn">추천확인</button>
                                    </div>
                                </div>

                            </div>
                        </form>
                        <div class="form-footer">
                            <button type="button" class="yellow-bg join-link" onclick="registration();"><span><i class="fas fa-check"></i> 회원가입</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function sel_menu(li) {

            $lis = $(".gamelist-menu ul li");

            $lis.removeClass("active");
            $(li).addClass("active");

            show_games();
        }


        function show_games() {
            var search_key = $(".gameModal .search-form input.search-text").val();
            var selected_menu = $(".gamelist-menu ul li.active").data("type");

            var type = "";

            if (selected_menu != "") {
                type = "." + selected_menu;
            }

            $games = $("div.game-btn" + type);

            $("div.game-btn").css("display", "none");

            if (search_key == "") {
                $games.css("display", "inline-block");

            } else {
                for (var game of $games) {

                    var name = $(game).find(".name-text").text();

                    if (name.includes(search_key)) {
                        $(game).css("display", "inline-block");
                    }
                }
            }
        }
    </script>
    <div class="modal gameModal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog game">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="btn-group">
                        <button class="modal-close-btn" data-dismiss="modal"></button>
                    </div>
                </div>
                <div class="modal-banner">
                    <div class="panel">
                        <div class="title-panel">
                            <h1 class="title">게임리스트</h1>
                        </div>
                        <div class="information">
                            <span><i class="fas fa-handshake"></i> 환영합니다. 항상 최선을 다하는 WHITE 입니다.</span>
                            <span class="warning"></span>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="search-form">
                        <button class="search-btn searched" onclick="view_game_list('searched');" style="left: 5px;">최근검색게임</button>
                        <input class="search-text" type="text" onkeyUp="show_games()" placeholder="결과내 재검색">
                    </div>
                    <div style="clear:both;"></div>
                </div>

                <div class="modal-body">
                    <div class="modal-panel">

                        <div class="gamelist-menu">
                            <ul>
                                <li class="active" onClick="sel_menu(this);" data-type="">
                                    <div class="icon-box"><i class="fas fa-globe"></i></div>
                                    <span>전체</span>
                                </li>
                                <li onClick="sel_menu(this);" data-type="POP">
                                    <div class="icon-box"><i class="fas fa-thumbs-up"></i></div>
                                    <span>인기</span>
                                </li>
                                <li onClick="sel_menu(this);" data-type="NEW">
                                    <div class="icon-box"><i class="fas fa-calendar-alt"></i></div>
                                    <span>신규게임</span>
                                </li>
                                <!-- 
                            <li>
                                <div class="icon-box"><i class="fas fa-calendar-alt"></i></div>
                                <span>테이블</span>
                            </li> -->
                            </ul>
                        </div>

                        <div class="gamelist-container">

                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    <div class="modal layoutModal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog main">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="btn-group">
                        <button class="modal-close-btn" data-dismiss="modal"></button>
                    </div>
                </div>
                <div class="modal-banner">
                </div>

                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>

</body>
<script type="text/javascript">
    $(function() {
        $('.popup').draggable({
            opacity: 0.7,
            cursor: 'move'
        });


    });


    function popup_close_today(idx) {

        $("#popup_" + idx).prop("checked", true);
        popup_close(idx);
    }

    function popup_close(idx) {

        if ($("#popup_" + idx).prop("checked")) {

            var exp_date = new Date();
            exp_date.setHours(0, 0, 0, 0);
            exp_date.setDate(exp_date.getDate() + 1);

            document.cookie = 'popup_' + idx + '=' + escape('none') + '; path=/; expires=' + exp_date.toGMTString() + ';';
        }

        $(".popup_" + idx).css("display", "none");
    }
</script>
<style type="text/css">
    @media(max-width:767px) {
            {
            left: 50%;
            top: 50%;
            width: 90%;
            height: auto;
            transform: translate(-50%, -50%);
        }
    }
</style>


</html>