$(document).ready(function () {
    $('.logout-btn').on('click', function () {
        alertify.confirm("로그아웃", "로그아웃 하시겠습니까?", function () {
            $('.wrapper_loading').removeClass('hidden');
            window.location.href = "/logout";
        }, function () {
            //statusUpdate('logOn');
        });
    });

    $('.join-btn').click(function () {
        $('.joinModal').modal('show');
        $('.loginModal').modal('hide');
    });

    $('.deposit-link').click(function () {
        $('.depositModal').modal('show');
        $('.withdrawModal').modal('hide');
    });

    $('.withdraw-link').click(function () {
        $('.withdrawModal').modal('show');
        $('.depositModal').modal('hide');
    });

    $('.event-link').click(function () {
        $('.eventModal').modal('show');
        $('.eventSeeModal').modal('hide');
        $('.noticeModal').modal('hide');
        $('.noticeSeeModal').modal('hide');
    });

    $('.notice-link').click(function () {
        $('.noticeModal').modal('show');
        $('.noticeSeeModal').modal('hide');
        $('.eventModal').modal('hide');
        $('.eventSeeModal').modal('hide');
    });
    $('.notice-a').click(function () {
        $('.noticeSeeModal').modal('show');
        $('.noticeModal').modal('hide');
    });

    $('.cs-mess-tbl').click(function () {
        $(this).next('.cs-mess-content').slideToggle();
        $(this).next('.cs-mess-content').siblings('.cs-mess-content').slideUp();
    });

    /*
    $('.game-pg-modal .game-menu a').click(function () {
        $(this).addClass('active');
        $(this).siblings().removeClass('active');
    });
    */

    $('.game-pg-modal .game-menu .web-act').click(function () {
        $(this).addClass('active');
        $(this).siblings().removeClass('active');
        //$('.slot-list-view').empty();
    });

    $('.game-pg-modal .cgame-cont .cg-head a').click(function () {
        $(this).addClass('active');
        $(this).siblings().removeClass('active');
    });

    /* My Page */
    $('.mypage-modal .mp-head a').click(function () {
        $(this).addClass('active');
        $(this).siblings().removeClass('active');
    });

    $('.mypage-modal .my-account .mp-head a:nth-child(1)').click(function () {
        $('.mypage-modal .my-account .mp-tab:nth-child(1)').addClass('active');
        $('.mypage-modal .my-account .mp-tab:nth-child(1)').siblings().removeClass('active');
    });

    $('.mypage-modal .my-account .mp-head a:nth-child(2)').click(function () {
        $('.mypage-modal .my-account .mp-tab:nth-child(2)').addClass('active');
        $('.mypage-modal .my-account .mp-tab:nth-child(2)').siblings().removeClass('active');
    });

    $('.mypage-modal .my-account .mp-head a:nth-child(3)').click(function () {
        $('.mypage-modal .my-account .mp-tab:nth-child(3)').addClass('active');
        $('.mypage-modal .my-account .mp-tab:nth-child(3)').siblings().removeClass('active');
    });

    $('.mypage-modal .my-account .mp-head a:nth-child(4)').click(function () {
        $('.mypage-modal .my-account .mp-tab:nth-child(4)').addClass('active');
        $('.mypage-modal .my-account .mp-tab:nth-child(4)').siblings().removeClass('active');
    });

    $('.mypage-modal .my-table .mp-head a:nth-child(1)').click(function () {
        $('.mypage-modal .my-table .mp-tab:nth-child(1)').addClass('active');
        $('.mypage-modal .my-table .mp-tab:nth-child(1)').siblings().removeClass('active');
    });

    $('.mypage-modal .my-table .mp-head a:nth-child(2)').click(function () {
        $('.mypage-modal .my-table .mp-tab:nth-child(2)').addClass('active');
        $('.mypage-modal .my-table .mp-tab:nth-child(2)').siblings().removeClass('active');
    });

    $('.mypage-modal .my-table .mp-head a:nth-child(3)').click(function () {
        $('.mypage-modal .my-table .mp-tab:nth-child(3)').addClass('active');
        $('.mypage-modal .my-table .mp-tab:nth-child(3)').siblings().removeClass('active');
    });

    /* M O B I L E */
    $(".m-menu-btn").click(function () {
        $('.sidenav').toggleClass('active');
        if ($('.sidenav').hasClass('active')) {
            $('.sn-overlay').addClass('active');
        } else {
            $('.sn-overlay').removeClass('active');
        }
        $('body').toggleClass('active');
    });

    $(".sn-overlay").click(function () {
        $(this).toggleClass('active');
        $('.sidenav').removeClass('active');
        $('body').removeClass('active');
    });

    $('.game-menu.selected:nth-child(1)').click(function () {
        console.log('!!!!');
        $('.game-pg-modal .game-menu .d-icon').toggleClass('active');
        $('.game-pg-modal .game-menu .drop-d').slideToggle()
    });

    $('.game-pg-modal .game-menu a').click(function () {
        /*
        $('.game-pg-modal .game-menu .d-icon').toggleClass('active');
        $('.game-pg-modal .game-menu .drop-d').slideToggle();
        */
    });

    $('.link-trigger').attr("data-toggle", "modal");
    $('.game-cont-inner .games-btn').on('click', function () {
        if (!parseInt($('#is_sign_in').val())) return;
        var id = $(this).attr('id');
        $('.gamePageModal').modal('show');
        $('.game-pg-modal .game-menu a').removeClass('active');
        $(id).addClass('active');
        //$('.slot-list-view').empty();
    });

    $('.mypage-btn').click(function () {
        $(this).attr("data-toggle", "modal");
        if ($('.finance-list').children().length === 1) {
            postAjax(1, 'FC');
            postAjax(1, 'BH');
            postAjax(1, 'BW');
        }
    });

    $(window).resize(function () {
        $('.sidenav').removeClass('active');
        $('body').removeClass('active');
        $('.sn-overlay').removeClass('active');
    });

    $(".game-pg-modal .cgame-cont .cg-head .link-grp a").click(function () {
        $(".cg-body").addClass("hidden");
        var id = $(this).attr('id');
        $(id).removeClass('hidden');
    });

    $(".game-pg-modal .game-menu .drop-d a").click(function () {
        $(this).addClass('active');
        $(this).siblings().removeClass('active');
    });

    $('.mypage-modal .my-account .mp-head a:nth-child(5)').click(function(){
        $('.mypage-modal .my-account .mp-tab:nth-child(5)').addClass('active');
        $('.mypage-modal .my-account .mp-tab:nth-child(5)').siblings().removeClass('active');
    });

    /*$('.with-depth .depth-click').click(function(){
        $(this).toggleClass('active');
        $(this).siblings('.depth-click').removeClass('active');
        $(this).next('.dropdown').find('.message-content').slideToggle();
        $(this).next('.dropdown').siblings('.dropdown').find('.message-content').slideUp();
    });

    $('.bs-table tr td .delete-btn').click(function(){
        $(this).parentsUntil('tbody').css('display','none');
        $(this).parentsUntil('tbody').next('.dropdown').css('display','none');
    });*/
});