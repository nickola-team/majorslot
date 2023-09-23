$(function() {
    $('.sub_pop1_open').on('click', function() { // 입금신청, 출금신청, 머니이동, 쿠폰신청, 콤프신청, 이벤트, 공지사항
        $('#sub_pop1').show();
        $('#wrap').hide();

    });
    $('.sub_pop1_close').on('click', function() { // 입출금 닫기
        $('#sub_pop1').hide();
        $('#wrap').show();

    });
    // $('.sub_pop2_open').on('click', function() { // 마이페이지, 정보수정, 입금/출금내역, 지인친구목록, 출석체크목록, 머니이동목록, 쪽지함
    //     $('#sub_pop2').show();
    //     $('#wrap').hide();
    // });
    $('.sub_pop2_close').on('click', function() { // 마이페이지 닫기
        $('#sub_pop2').hide();
        $('#wrap').show();

    });
    $('.slot_loding_open').on('click', function() { // 슬롯로딩
        $('#slot_loding').show();
        $('#wrap').hide();
    });
    $('.slot_loding_close').on('click', function() { // 슬롯로딩 닫기
        $('#slot_loding').hide();
        $('#wrap').show();
    });
    $('.etc_pop1_open').on('click', function() { // 회원가입
        $('#etc_pop1').show();
        $('#wrap').hide();
    });
    $('.etc_pop1_close').on('click', function() { // 회원가입 닫기
        $('#etc_pop1').hide();
        $('#wrap').show();        
    });
    $('.etc_pop2_open').on('click', function() { // 머니이동 중간
        $('#etc_pop2').show();
        $('#wrap').hide();
    });
    $('.etc_pop2_close').on('click', function() { // 머니이동 닫기
        $('#etc_pop2').hide();
        $('#wrap').show();
    });
    $('.etc_pop4_open').on('click', function() { // 파트너제휴 중간
        $('#etc_pop4').show();
        $('#wrap').hide();
    });
    $('.etc_pop4_close').on('click', function() { // 파트너제휴 닫기
        $('#etc_pop4').hide();
        $('#wrap').show();
    });
    $('.slot_01_open').on('click', function() { // 슬롯게임
        $('#slot_01').show();
        $('#wrap').hide();
    });
    $('.slot_01_close').on('click', function() { // 슬롯게임
        $('#slot_01').hide();
        $('#wrap').show();
    });

    $('.etc_event1_open').on('click', function() { // 룰렛이벤트
        $('#etc_event1').show();
        $('#wrap').hide();
    });
    $('.etc_event1_close').on('click', function() { // 룰렛이벤트 닫기
        $('#etc_event1').hide();
        $('#wrap').show();
    });

    $('.top_banner_wrap').hide();
    $('.top_banner_wrap_open').on('click', function() {
        var $this = $(this);
        var showbanner = $('.top_banner_wrap');
        $('.top_banner_wrap_open').not(this).removeClass('like');
        if ($this.hasClass('like')) {
            $this.removeClass('like');
            showbanner.slideUp();
        } else {
            $this.addClass('like');
            showbanner.slideDown();
        };
        return false;
    });

    setInterval(function() {
        repeat();
    }, 8000);

});