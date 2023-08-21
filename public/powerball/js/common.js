/*==============================================
 - Description -
 01. front.init()				:	프론트 스크립트 초기실행
 02. front.ready()				:	html 문서 로드 완료시
 03. front.resize()				:	window 크기 변경시
 04. front.scroll()				:	window 스크롤시

 ==============================================*/

/*===================================
 @ front
 ===================================*/
var front = {
    winW: null, //윈도우 넓이
    winH: null, //윈도우 높이
    browser: null, //브라우저 종류
    sc: null, //스크롤 상단값
    pcOnly: false,
    isDevice: 'pc',
    popupSwiper: null,
    popupSwiperInit: false,
    mainSwiperPc: null,
    mainSwiperMobile: null,

    init: function() {
        front.winW = $(window).width();
        front.winH = $(window).height();
        front.browser = navigator.userAgent;

        front.scroll();

        front.common.init();
        front.common.tab();
        front.common.trade();
        front.common.helpLayer();
    },

    ready: function() {
        $(".contract_times .selectbox select").chosen({
            disable_search_threshold: 10
        });

        front.resize()

        $(function() {
            if ($("body").hasClass("pc") && front.isDevice === 'pc') {
                if (window.sticky) {
                    $("#idCartPosition").sticky({
                        topSpacing: 0
                    });
                }
            }

            $(".slip-open").click(function() {
                if (front.isDevice === 'pc') return

                $(this).parent().parent().toggleClass("open", !$(this).parent().parent().hasClass("open"))
            })

            $(".sports_score_item .odd_box").click(function() {
                $("#idCartPosition").toggleClass("open", !$("#idCartPosition").hasClass("open"))
            })

            $(".sports_score_head .btn_score").click(function() {
                var panel = $(this).parent().parent().parent().next('.sports_score_body');

                if (!panel.is(":visible")) {
                    panel.show()
                } else {
                    panel.hide();
                }
            })

            $("#toggleStickyBtn").click(function() {
                $(this).toggleClass('on', !$(this).hasClass('on'))

                if ($(this).hasClass('on')) {
                    if ($("body").hasClass("pc") && front.isDevice === 'pc') {
                        $("#idCartPosition").sticky({
                            topSpacing: 0
                        });
                    }
                } else {
                    $("#idCartPosition").unstick();
                }
            })
        });
    },

    resize: function() {
        var mobileWidth = 1280;

        front.winW = $(window).width();
        front.winH = $(window).height();

        if (!front.pcOnly) {
            if (front.winW < mobileWidth && !$("body").hasClass("mobile")) {
                front.isDevice = "mobile";

                $("body").removeClass("pc");
                $("body").addClass("mobile");
                front.common.scaling();
            }

            if (front.winW > mobileWidth && !$("body").hasClass("pc")) {
                front.isDevice = "pc";
                $(".target0").removeAttr("style");
                $("body").addClass("pc");
                $("body").removeClass("mobile");
            }
        } else {
            front.isDevice = "pc";
            $("body").addClass("pc");
        }

        if ($("body").hasClass("pc") && front.isDevice === 'pc') {
            if ($("#toggleStickyBtn").hasClass('on')) {
                if (window.sticky) {
                    $("#idCartPosition").sticky({
                        topSpacing: 0
                    });
                }
            } else {
                if ($("#idCartPosition").length > 1) {
                    $("#idCartPosition").unstick();
                }
            }

            if (front.popupSwiper && front.popupSwiper.$el) {
                front.popupSwiper.destroy(true, true);
                front.popupSwiper = null
                front.popupSwiperInit = false
            }
        } else {
            if ($("#idCartPosition").length > 1) {
                $("#idCartPosition").unstick();
            }

            if (!front.popupSwiperInit.$el) {
                front.popupSwiperInit = true;
                front.popupSwiper = new Swiper('.layer_popup_swiper .swiper-container', {
                    slidesPerView: 1,
                    spaceBetween: 0,
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                });
            }
        }

        if (front.mainSwiperPc.$el) {
            front.mainSwiperPc.update();
        }

        if (front.mainSwiperMobile.$el) {
            front.mainSwiperPc.update();
        }
    },

    scroll: function() {
        front.winW = $(window).width();
        front.winH = $(window).height();
        front.sc = $(document).scrollTop();

        $('#header').toggleClass('scroll', front.sc > 0);
    },

    common: {
        init: function() {
            var common = front.common;

            common.gnb.init();
            common.home.init();
            common.subPage.init();
            common.footer.init();
            common.scaling();
        },

        gnb: {
            init() {
                var el = $('#header');
                var timeoutGnb;

                if (el.lenght <= 0) {
                    return;
                }

                TweenMax.set(el.find('.gnb .depth2_area .depth2'), {
                    opacity: 0,
                    y: -20
                });

                el.find('.gnb > li > a').on('mouseenter focus', function() {
                    clearTimeout(timeoutGnb);

                    $(this).closest('li').addClass('on').siblings().removeClass('on');

                    el.find('.depth2_area').hide();

                    if (el.find('.gnb').hasClass('active')) {
                        $(this).next('.depth2_area').css('display', 'block');
                    } else {
                        TweenMax.to(el.find('.gnb .depth2_area .depth2'), 0.5, {
                            opacity: 1,
                            y: 0
                        });
                        $(this).next('.depth2_area').stop().slideDown(300);
                        $(this).closest('.gnb').addClass('active');
                        el.addClass('active');
                    }
                });

                el.find('.logo a, .btn_all_menu').on('focus', function() {
                    gnbHide();
                });

                el.find('.gnb').on('mouseleave', function() {
                    gnbHide();
                });

                function gnbHide() {
                    clearTimeout(timeoutGnb);
                    timeoutGnb = setTimeout(function() {
                        TweenMax.to(el.find('.gnb .depth2_area .depth2'), 0.25, {
                            opacity: 0,
                            y: -20
                        });
                        el.find('.depth2_area').stop().slideUp(250);
                        el.find('.gnb > li').removeClass('on');
                        el.find('.gnb').removeClass('active');
                        el.removeClass('active');
                    }, 100);
                }

                /*전체 메뉴*/
                $('.btn_all_menu').click(function(e) {
                    e.preventDefault();

                    $('.all_menu.right').addClass('on');
                    $('.gnb_dim').addClass('on');
                });

                $(".all_menu.right .btn_close").click(function(e) {
                    e.preventDefault();

                    $('.all_menu.right').removeClass('on');
                    $('.gnb_dim').removeClass('on');
                });

                $(".util .mode .layer a").click(function(e) {
                    e.preventDefault();

                    var $layer = $(this).parent().prev();

                    if ($layer.hasClass('dark')) {
                        $layer.removeClass('dark');
                        $layer.text('Dark');
                    } else {
                        $layer.addClass('dark');
                        $layer.text('Light');
                    }
                });

            },
        },

        home: {
            init() {
                front.mainSwiperPc = new Swiper('.main_swiper.pc_swiper .swiper-container', {
                    slidesPerView: 1,
                    spaceBetween: 0,
                    loop: true,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    navigation: {
                        nextEl: '.pc_swiper .swiper-button-next',
                        prevEl: '.pc_swiper .swiper-button-prev',
                    },
                    pagination: {
                        el: '.swiper-pagination-pc',
                    },
                });

                front.mainSwiperMobile = new Swiper('.main_swiper.mobile_swiper .swiper-container', {
                    slidesPerView: 1,
                    spaceBetween: 0,
                    loop: true,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    navigation: {
                        nextEl: '.mobile_swiper .swiper-button-next',
                        prevEl: '.mobile_swiper .swiper-button-prev',
                    },
                    pagination: {
                        el: '.swiper-pagination-mobile',
                    },
                });

                function tick2() {
                    $('#ticker li:first-child').slideUp(function() {
                        $(this).appendTo($('#ticker')).slideDown();
                    });
                }

                setInterval(function() {
                    tick2()
                }, 2000);
            }
        },

        scaling: function() {
            function get_attr(target, attr) {
                return $(target).attr(attr).split("px")[0] * 1;
            }

            function scailing() {
                var target0 = ".target0";
                var target1 = ".target1";
                var target2 = ".target2";
                var limit = 1000;

                var w_width = $(window).width();
                if (w_width > limit)
                    w_width = limit;

                var ref_width = $(target0).attr("o_width").split("px")[0] * 1;
                var ratio = (w_width / ref_width).toFixed(3) * 1;

                var width = get_attr(target0, "o_width") * ratio;
                var height = get_attr(target0, "o_height") * ratio;
                var margin_left = get_attr(target1, "o_margin_left") * ratio;
                var margin_top = get_attr(target1, "o_margin_top") * ratio;

                //$(target0).css("width", width).css("height", height);
                $(target0).css("height", height);
                $(target1).css("margin-left", margin_left).css("margin-top", margin_top);
                //$(target2).css("-webkit-transform", "scale(" + ratio + ")");
            }

            var is_live_iframe = $(".contract_game").length;
            if (is_live_iframe > 0) {

                console.log('front.isDevice', front.isDevice)

                if (front.isDevice === 'mobile') {
                    scailing();
                }

                $(window).resize(function() {
                    if (front.isDevice === 'mobile') {
                        scailing();
                    }
                });
            }
        },

        subPage: {
            init() {
                //	서브 로케이션 클릭 이벤트
                $(".location_wrap .location .sub").click(function() {
                    if (!$(this).hasClass("on")) {
                        $(this).addClass("on");
                        $(this).parent().find(".sub_menu").stop(true, true).slideDown(300);
                    } else {
                        $(this).removeClass("on");
                        $(this).parent().find(".sub_menu").stop(true, true).slideUp(300);
                    }
                });

                $(".share_area .btn_open").click(function() {
                    if ($(this).next().is(":visible")) {
                        $(this).next().hide();
                    } else {
                        $(this).next().show();
                    }
                })

                $(".share_area .btn_close").click(function() {
                    $(this).parent().hide();
                });
            }
        },

        footer: {
            init() {
                //관련사이트
                $('.site_list').find('> a').on("click", function(e) {
                    e.preventDefault();
                    if (!$(this).closest('.site_list').hasClass("open")) {
                        $(this).closest('.site_list').addClass("open")
                    } else {
                        $(this).closest('.site_list').removeClass("open")
                    }
                });
            }
        },

        // 탑버튼
        moveScroll(tgY, speed) {
            if (speed == null || speed == 'undefind')
                speed = 1000;
            $('html, body').stop().animate({
                'scrollTop': tgY
            }, {
                queue: false,
                duration: speed,
                easing: 'easeOutCubic'
            });
        },

        locationCheck() {
            var _flag = true;
            $('.location_menu').find('ul > li').each(function() {
                if ($(this).hasClass('open'))
                    _flag = false;
            });

            if (_flag) {
                $('.location_menu').removeClass('open');
            }
        },

        locationClose() {
            $('.location_menu').find('ul > li').each(function() {
                if ($(this).hasClass('open')) {
                    $(this).removeClass('open');
                    $(this).find('.nav_list').stop(true).slideUp(450);
                }
            });

            front.common.locationCheck();
        },

        trade() {
            var tabArea = $('.tab_list .tabs');
            tabAreaLi = tabArea.find('li a'),
                tabCon = $('.tab_cont .tab_table');

            tabAreaLi.on('click', function(e) {
                var _this = $(this).parent(),
                    idx = _this.index();

                showTabResult(idx);

                _this.addClass('on').siblings().removeClass('on');
                tabCon.eq(idx).addClass('on').siblings().removeClass('on');

                return false;
            });
        },

        // 탭 메뉴
        tab() {
            var tabArea = $('.tabs');
            tabAreaLi = tabArea.find('li a'),
                tabCon = $('.tab_cont .tab_table');

            tabAreaLi.on('click', function(e) {
                var _this = $(this).parent(),
                    idx = _this.index();

                _this.addClass('on').siblings().removeClass('on');
                tabCon.eq(idx).addClass('on').siblings().removeClass('on');

                return false;
            });
        },

        helpLayer() {
            var $btnHelp = $('.contract_chart .btn_help');
            var $helpLayer = $btnHelp.next('.help_layer');
            var $btnClose = $helpLayer.find('.btn_help_close');

            $btnHelp.on('click', function() {
                $helpLayer.toggleClass('on');
            });

            $btnClose.on('click', function() {
                $helpLayer.removeClass('on');
            });
        }
    }

}
/*===================================
 @ init
 ===================================*/
$(function() {
    front.init();
});

/*===================================
 @ document ready
 ===================================*/
$(document).ready(function() {
    front.ready();
});


/*===================================
 @ window resize
 ===================================*/
$(window).resize(function() {
    front.resize();
});


/*===================================
 @ window scroll
 ===================================*/
$(window).scroll(function() {
    front.scroll();
});