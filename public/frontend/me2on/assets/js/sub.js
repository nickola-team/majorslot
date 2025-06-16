$(function() {


    var $animation_elements = $('.ani');
    var $window = $(window);


    function check_if_in_view() {
        var window_height = $window.height();
        var window_top_position = $window.scrollTop();
        var window_bottom_position = (window_top_position + window_height);

        $.each($animation_elements, function() {
            var $element = $(this);
            var element_height = $element.outerHeight();
            var element_top_position = $element.offset().top + (window_height / 2);
            var element_bottom_position = (element_top_position + element_height);

            //check to see if this current container is within viewport
            if ((element_bottom_position >= window_top_position) &&
                (element_top_position <= window_bottom_position)) {
                $element.addClass('in-view');
            } else {
                //$element.removeClass('in-view');
            }

        });
    }
    $window.on('scroll resize', check_if_in_view);
    $window.trigger('scroll');



    var s = skrollr.init({
        edgeStrategy: 'set',
        easing: {
            WTF: Math.random,
            inverted: function(p) {
                return 1 - p;
            }
        },
        forceHeight: false,
        mobileCheck: function() {
            if ((/Android|iPhone|iPad|iPod|BlackBerry|Windows Phone/i).test(navigator.userAgent || navigator.vendor || window.opera)) {
                // mobile device
            }
        }

    });



    $(".m33_tabs ul li").click(function() {
        m33tabNum = $(".m33_tabs ul li").index($(this));
        $(this).addClass("on").siblings().removeClass("on");
        $(".m33 .tabwrap .boxwrap").hide();
        $(".m33 .tabwrap .boxwrap:eq(" + m33tabNum + ")").show();
    })

})