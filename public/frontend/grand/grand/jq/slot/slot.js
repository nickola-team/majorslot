$(document).ready(function() {

    var n = 0;
    $(".al-row").each(function() {
        $(this).css("animation-delay", n + "s"), n += .1
    });
    var i = $(".slot-btn"),
        l = $(".slot-btn .close-btn");
    i.mouseover(function() {
        $(this).prevAll(".slot-btn").addClass("before-hover"), $(this).nextAll(".slot-btn").addClass("after-hover")
    }), i.mouseout(function() {
        i.removeClass("before-hover"), i.removeClass("after-hover")
    }), l.click(function() {
        $(this).parentsUntil(".sc-inner").css("display", "none")
    }), $(".c-modal-menu .link-btn").click(function() {
        $(this).addClass("active"), $(this).siblings(".link-btn").removeClass("active"), $(".gm-selector").removeClass("active")
    }), $(".gm-selector").click(function() {
        $(this).addClass("active"), $(".drop-mm-mob .link-btn").removeClass("active")
    });

});