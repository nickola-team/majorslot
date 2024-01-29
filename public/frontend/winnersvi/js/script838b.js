$(document).ready(function() {
    $(".login-btn").click(function() {});

    $(".logout-btn").on("click", function(e) {
        goLogout();
        e.preventDefault();
    });





    $(".my-page-btn").on('click', function() {
        $('.mypageModal .bs-modal .nav-mdl .nav-btn:nth-child(1) button').click();
    });

    $(".message-btn").on('click', function() {
        $('.mypageModal .bs-modal .nav-mdl .nav-btn:nth-child(2) button').click();
    });

    $(".point-info-btn").on('click', function() {
        $('.mypageModal .bs-modal .nav-mdl .nav-btn:nth-child(3) button').click();
    });

    $(".balance-btn").on('click', function() {
        $('.mypageModal .bs-modal .nav-mdl .nav-btn:nth-child(1) button').click();
    });




    /* -- MODAL -- */
    $('.join-btn').click(function() {
        $('.joinModal').modal('show');
        $('.loginModal').modal('hide');
        $('body').addClass('mld-active');
    });

    // $('.slot-btn').click(function(){
    // 	$('.gamelistModal').modal('show');
    // });
    $('.mypage-link').click(function() {
        $('.mypageModal').modal('show');
    });
    $('.subpg-link').click(function() {
        $('.subpgModal').modal('show');
    });
    $(".nav-mdl .nav-btn button").click(function() {
        $(this).parent().addClass('active');
        $(this).parent().siblings('.nav-btn').removeClass('active');
    });



    $(".deposit-link").click(function() {
        $('.mdl-head .mdl-title .deposit').addClass('active');
        $('.mdl-head .mdl-title .deposit').siblings().removeClass('active');
        $('.nav-mdl .deposit-link').parent().addClass('active');
        $('.nav-mdl .deposit-link').parent().siblings('.nav-btn').removeClass('active');
        $('.tab-mdl.deposit').addClass('active');
        $('.tab-mdl.deposit').siblings('.tab-mdl').removeClass('active');
    });

    $(".withdraw-link").click(function() {
        reGetMoney();
        $('.mdl-head .mdl-title .withdraw').addClass('active');
        $('.mdl-head .mdl-title .withdraw').siblings().removeClass('active');
        $('.nav-mdl .withdraw-link').parent().addClass('active');
        $('.nav-mdl .withdraw-link').parent().siblings('.nav-btn').removeClass('active');
        $('.tab-mdl.withdraw').addClass('active');
        $('.tab-mdl.withdraw').siblings('.tab-mdl').removeClass('active');
    });

    $(".event-link").click(function() {
        $('.mdl-head .mdl-title .event').addClass('active');
        $('.mdl-head .mdl-title .event').siblings().removeClass('active');
        $('.nav-mdl .event-link').parent().addClass('active');
        $('.nav-mdl .event-link').parent().siblings('.nav-btn').removeClass('active');
        $('.tab-mdl.event').addClass('active');
        $('.tab-mdl.event').siblings('.tab-mdl').removeClass('active');

        if (parseInt($('#is_sign_in').val())) {
            postAjax(1, 'EV');
        }
    });

    $(".notice-link").click(function() {
        $('.mdl-head .mdl-title .notice').addClass('active');
        $('.mdl-head .mdl-title .notice').siblings().removeClass('active');
        $('.nav-mdl .notice-link').parent().addClass('active');
        $('.nav-mdl .notice-link').parent().siblings('.nav-btn').removeClass('active');
        $('.tab-mdl.notice').addClass('active');
        $('.tab-mdl.notice').siblings('.tab-mdl').removeClass('active');

        if (parseInt($('#is_sign_in').val())) {
            postAjax(1, 'NT');
        }

    });

    $('.bs-modal .mdl-close-btn').click(function() {
        $('body').removeClass('mld-active');
    });





    /* My Page */
    $('.account-link').click(function() {
        $('.mypageModal').modal('show');
        $('.historyModal').modal('hide');
        $('body').addClass('mld-active');
    });

    $('.history-link').click(function() {
        $('.historyModal').modal('show');
        $('.mypageModal').modal('hide');
        $('body').addClass('mld-active');

        if (parseInt($('.deposit-list').children().length) === 1) {
            postAjax(1, 'DP');
        }
    });


    $('.bs-modal .nav-mdl .nav-btn:nth-child(1) button').click(function() {
        $('.bs-modal .mp-tab:nth-child(1)').addClass('active');
        $('.bs-modal .mp-tab:nth-child(1)').siblings().removeClass('active');
    });
    $('.bs-modal .nav-mdl .nav-btn:nth-child(2) button').click(function() {
        $('.bs-modal .mp-tab:nth-child(2)').addClass('active');
        $('.bs-modal .mp-tab:nth-child(2)').siblings().removeClass('active');

        if (parseInt($('#is_sign_in').val())) {
            postAjax(1, 'MM');
        }
    });
    $('.bs-modal .nav-mdl .nav-btn:nth-child(3) button').click(function() {
        $('.bs-modal .mp-tab:nth-child(3)').addClass('active');
        $('.bs-modal .mp-tab:nth-child(3)').siblings().removeClass('active');
    });
    $('.bs-modal .nav-mdl .nav-btn:nth-child(4) button').click(function() {
        $('.bs-modal .mp-tab:nth-child(4)').addClass('active');
        $('.bs-modal .mp-tab:nth-child(4)').siblings().removeClass('active');

        if (parseInt($('#is_sign_in').val())) {
            postAjax(1, 'CP');
        }
    })
    $('.bs-modal .nav-mdl .nav-btn:nth-child(5) button').click(function() {
        $('.bs-modal .mp-tab:nth-child(5)').addClass('active');
        $('.bs-modal .mp-tab:nth-child(5)').siblings().removeClass('active');
    });



    $('.with-depth .depth-click').click(function() {
        $(this).toggleClass('active');
        $(this).find('.oc-btn').toggleClass('active');
        $(this).next('.dropdown').find('.mess-cont').slideToggle();
        $(this).next('.dropdown').siblings('.dropdown').find('.mess-cont').slideUp();
        if ($(this).hasClass('active')) {
            $(this).find('.oc-btn').html('닫기');
            $(this).siblings('.depth-click').find('.oc-btn').html('열기');
            $(this).siblings('.depth-click').find('.oc-btn').removeClass('active');
        } else {
            $(this).find('.oc-btn').html('열기');
        }
    });
    $('.with-depth .delete-btn').click(function() {
        $(this).parentsUntil('tbody').css('display', 'none');
        $(this).parentsUntil('tbody').next('.dropdown').css('display', 'none');
    });




    $('.historyModal .bs-modal .nav-mdl .nav-btn').on('click', function() {
        if ($(this).data('target') === undefined) return;
        var target = $(this).data('target');
        if (parseInt($(target).children().length) === 1) {
            postAjax(1, $(this).data('id'));
        }
    });




    /* Mobile */
    $(".mypg-btn").click(function() {
        $(this).toggleClass("opened");
        $('.after-login .desktop').toggleClass('active');
        if ($('.after-login .desktop').hasClass('active')) {
            $('.sn-overlay').addClass('active');
        } else {
            $('.sn-overlay').removeClass('active');
        }
        $('body').toggleClass('active');
    });
    $(".sn-overlay").click(function() {
        $(this).toggleClass('active');
        $('body').removeClass('active');
        $('.mypg-btn').removeClass("opened");
        $('.after-login .desktop').removeClass('active');
    });

    /*$( function () {
    	$( '.rolling-realtime' ).vTicker(
    		'init', {
    		speed:1500,
    		pause:0,
    		showItems:7,
    		padding:0,
    	});
    });*/
});