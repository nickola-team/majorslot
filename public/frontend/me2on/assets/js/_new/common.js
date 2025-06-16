// function resetVideo(filename, isDesktop){
// 	let screenWith = window.innerWidth;
// 	let url = "/assets/images/common/"
// 	if(screenWith > 1024 && isDesktop) {
// 		url += filename + ".mp4"
// 	} else {
// 		url += filename + "_mo.mp4"
// 	}

// 	$("#vod_home")[0].setAttribute('src', url);
// }
 
function resetVideo(filename, isDesktop) {
    let screenWith = window.innerWidth;
    let screenHeight = window.innerHeight;

    let className = "vod_home";
    if (screenWith <= 1024 || !isDesktop)
        className = "vod_home_mobile";

    $("#vod_home")[0].className = className;

    $(".slogan").show();
    if (screenHeight < 500) {
        $(".slogan").hide();
    }
}

$(function() {
    $(window).scroll(function() {

        scroll = $(window).scrollTop();
        if (scroll > 0) {
            $("#header").addClass("act");
        } else {
            $("#header").removeClass("act");
        }

    });

    btnmenu = 0;
    $(".btn_menu").click(function() {
        if (btnmenu == 0) {
            $("#header").addClass("all");
            $(this).stop().addClass("openmenu");
            $(".allmenu").slideDown();
            btnmenu = 1;
        } else {
            $("#header").removeClass("all");
            $(this).stop().removeClass("openmenu");
            $(".allmenu").slideUp();
            btnmenu = 0;
        }
    });

    $(".pc_nav>li").mouseover(function() {
        liNum = $(".pc_nav>li").index($(this));
        $("#header").addClass("onsub");
        $(this).addClass("on").siblings().removeClass("on");
        $(".submenu ul:eq(" + liNum + ")").show().siblings().hide();
        $(".submenu").slideDown();
    });
    $("#header").mouseleave(function() {
        $("#header").removeClass("onsub");
        $(".submenu").slideUp();
        $(".pc_nav>li").removeClass("on");
    })

    $(".m_nav .sub").on("click", function() {
        if ($(this).attr("class") == "sub on") {
            $(this).find("ul").slideUp();
        } else {
            $(this).find("ul").slideDown();
        }
        $(this).toggleClass("on");
        $(this).siblings("li").removeClass("on");
        $(this).siblings("li").find("ul").slideUp();

    });

    langchk = 0;
    $(".lang").click(function() {
        if (langchk == 0) {
            $(this).find("ul").slideDown();
            langchk = 1;
        } else {
            $(this).find("ul").slideUp();
            langchk = 0;
        }
    })

    famchk = 0;
    $(".family").click(function() {
        if (famchk == 0) {
            $(this).find("ul").slideDown();
            famchk = 1;
        } else {
            $(this).find("ul").slideUp();
            famchk = 0;
        }
    })


    $(".btn_top").click(function() {
        $('body,html').animate({
            scrollTop: 0
        }, 400);
    });
    $(window).scroll(function() {

        scroll = $(window).scrollTop();
        wH = $(window).height() + ($(window).height() / 3);
        if (scroll > wH) {
            $(".btn_top").addClass("on");
        } else {
            $(".btn_top").removeClass("on");
        }

    })



})




$(document).ready(function() {
    gsap.registerPlugin(ScrollTrigger);
    headerInit();
})

const isMobile = $(window).width() < 900;

function headerInit() {

    const paths = $('#logo-path .path');
    let delay = 0;

    paths.each(function(index, path) {
        gsap.set(path, {
            transformOrigin: "center center"
        }); // transform-origin 설정
        gsap.timeline({
                repeat: -1,
                repeatDelay: 4.5
            })
            .to(path, {
                rotationY: 360,
                duration: 1,
                ease: "power1.inOut",
                delay: delay
            });

        delay += 0.5;
    });


    var $nav = $('#header-new');
    var lastScrollTop = 0; // 마지막 스크롤 위치를 저장하기 위한 변수 초기화
    $(window).on('scroll', function() { // 윈도우 객체에 스크롤 이벤트 핸들러를 등록
        var currentScroll = $(this).scrollTop(); // 현재 스크롤 위치를 가져옴
        if (currentScroll > 40) { // 현재 스크롤 위치가 150픽셀보다 큰 경우
            if (currentScroll > lastScrollTop) { // 현재 스크롤 위치가 마지막 스크롤 위치보다 큰 경우 (스크롤 다운)
                $nav.addClass('scroll');
                $nav.addClass('active');
                $('.content-feature').css('opacity', 1)
                $('.content-feature').css('pointerEvents', 'auto')
            } else { // 현재 스크롤 위치가 마지막 스크롤 위치보다 작거나 같은 경우 (스크롤 업)
                $nav.removeClass('scroll');
            }
            $('.list_gnb > li > a').on('mouseover', function() {
                const indexNum = $('.list_gnb > li > a').index(this)
                $('.list_gnb > li').removeClass('over')
                $('.list_gnb > li').eq(indexNum).addClass('over')

                $('.lnb-inner ul').hide()
                $('.lnb-inner ul').eq(indexNum).css('display', 'flex')
                $('.content-feature').css('opacity', 0)
                $('.content-feature').css('pointerEvents', 'none')
            })
            $('#header-new').on('mouseleave', function() {
                $nav.addClass('scroll');
                $nav.addClass('active');
                $('.list_gnb > li').removeClass('over')
                $('.lnb-inner ul').hide()
                $('.content-feature').css('opacity', 1)
                $('.content-feature').css('pointerEvents', 'auto')

            })

        } else { // 현재 스크롤 위치가 150픽셀 이하인 경우
            $nav.removeClass('scroll');
            $nav.removeClass('active')
            $('.content-feature').css('opacity', 0)
            $('.content-feature').css('pointerEvents', 'none')
            $('.list_gnb > li > a').on('mouseover', function() {
                const indexNum = $('.list_gnb > li > a').index(this)
                $('.list_gnb > li').removeClass('over')
                $('.list_gnb > li').eq(indexNum).addClass('over')
                $('.lnb-inner ul').hide()
                $('.lnb-inner ul').eq(indexNum).css('display', 'flex')
                $('.content-feature').css('opacity', 0)
                $('.content-feature').css('pointerEvents', 'none')
            })
            $('#header-new').on('mouseleave', function() {
                $nav.removeClass('scroll');
                $nav.removeClass('active')
                $('.list_gnb > li').removeClass('over')
                $('.lnb-inner ul').hide()
                $('.content-feature').css('opacity', 0)
                $('.content-feature').css('pointerEvents', 'none')

            })
        }
        lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
    });
    $nav.find('.inner_head .list_gnb a').on('mouseover', function() {
        $nav.addClass('active')
    })

    $('.over-text-wrap').each(function(e) {
        $(this).find(' > *').addClass('over-text').wrapInner('<span class="over-text-con"></span>');
        $('.over-text').css('overflow', 'hidden');
        $('.over-text-con').css('display', 'block');

        let wrap = $(this).find('.over-text');
        let text = $(this).find('.over-text-con');

        gsap.set(text, {
            y: '100%',
        });

        gsap.to($(text), {
            y: 0,
            delay: 1,
            duration: 1.8,
            ease: "power4.inOut",
            stagger: {
                amount: 0.3
            }
        });
    });

    $('.m-nav dt').on('click', function() {
        $(this).parent().siblings('dl').removeClass('active')
        $(this).parent().toggleClass('active')
    })
    $('.m-menu button').on('click', function() {
        $('.mob-menu').addClass('active')
    })
    $('.mob-menu button.close').on('click', function() {
        $('.mob-menu').removeClass('active')
    })

}