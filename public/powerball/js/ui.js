$(function() {
    // Bg : video
    if (document.getElementById('uservideo-canvas')) {
        var uservideo_canvas = document.getElementById('uservideo-canvas');
        var uservideo_ctx = uservideo_canvas.getContext('2d');
        var uservideo_video = document.getElementById('uservideo-video');
        var uservideo_ratio;

        // Bg : video : auto start
        setTimeout(function() {
            uservideo_video.play();
        }, 10);
        $(document).bind('touchstart', function(e) {
            uservideo_video.play();
        });

        // set Canvas Size = video size when known.
        uservideo_video.addEventListener('loadedmetadata', function() {
            uservideo_canvas.width = uservideo_video.videoWidth;
            uservideo_canvas.height = uservideo_video.videoHeight;
            uservideo_ratio = uservideo_video.videoWidth / uservideo_video.videoHeight;
        });

        uservideo_video.addEventListener('play', function() {
            var $this = this; // Cache
            (function loop() {
                // Resizing
                uservideo_canvas.width = window.innerWidth;
                uservideo_canvas.height = window.innerHeight;

                // get X, Y, W, H
                var canvas_width = window.innerWidth;
                var canvas_height = window.innerHeight;
                var canvas_ratio = canvas_width / canvas_height;

                // 캔버스 비율이 더 클 때는, 가로폭이 100퍼여야 한다. + y방향으로 오프셋되어야 한다.
                if (canvas_ratio > uservideo_ratio) {
                    var video_width = canvas_width;
                    var video_height = video_width / uservideo_ratio; // 유저비디오 오리지널비율
                    var video_x = 0;
                    var video_y = (canvas_height - video_height) / 2;
                }

                // 캔버스 비율이 더 작을 때는, 세로폭이 100퍼여야 한다. + x방향으로 오프셋되어야 한다.
                // 오프셋 : 캔버스 - 비디오 / 2.
                if (canvas_ratio < uservideo_ratio) {
                    var video_height = canvas_height;
                    var video_width = video_height * uservideo_ratio; // 유저비디오 오리지널비율
                    var video_x = (canvas_width - video_width) / 2;
                    var video_y = 0;
                }

                // Drawing Image
                if (!$this.paused) {
                    uservideo_ctx.drawImage($this, video_x, video_y, video_width, video_height);
                    setTimeout(loop, 0); // 1000 / 30 : drawing at 30fps
                }
            })();
        }, 0);
    }

    // Popup
    var popup = $('.popup-wrap').each(function(idx, item) {
        $(item).find('.item-close').click(function() {
            $(item).removeClass('active');
        });
        $(item).find('.item-confirm').click(function() {
            $(item).removeClass('active');
        });

    });

    $('.item-action-up').click(function() {
        $('html, body').animate({
            'scrollTop': 0
        }, 300, 'swing');
    });

    // Cart Pin Toggle
    $('.bettingcart-title, .js-bettingcart-toggle').click(function() {
        $('#bettingcart-toggle').each(function() {
            $(this).prop('checked', function() {
                return !$(this).prop('checked');
            });
            $('.js-bettingcart-accordion').toggleClass('active', $(this).prop('checked'));
        });
    });

    // Recommand list : date picker
    $('.recommendlist-search .item-from, .recommendlist-search .item-to').datepicker({
        dateFormat: 'yy-mm-dd',
        prevText: '이전 달',
        nextText: '다음 달',
        monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        dayNames: ['일', '월', '화', '수', '목', '금', '토'],
        dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
        dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
        showMonthAfterYear: true,
        changeMonth: true,
        changeYear: true,
        yearSuffix: '년'
    });

});

// Timer
var digitSegments = [
    [1, 2, 3, 4, 5, 6],
    [2, 3],
    [1, 2, 7, 5, 4],
    [1, 2, 7, 3, 4],
    [6, 7, 2, 3],
    [1, 6, 7, 3, 4],
    [1, 6, 5, 4, 3, 7],
    [1, 2, 3],
    [1, 2, 3, 4, 5, 6, 7],
    [1, 2, 7, 3, 6]
]

//document.addEventListener('DOMContentLoaded', function() {
$(function() {
    var _hours = document.querySelectorAll('.hours');
    var _minutes = document.querySelectorAll('.minutes');
    var _seconds = document.querySelectorAll('.seconds');

    setInterval(function() {
        var date = new Date();
        var hours = date.getHours(),
            minutes = date.getMinutes(),
            seconds = date.getSeconds();

        var count = _hours.length / 2;
        for (var i = 0; i < count; i++) {
            setNumber(_hours[i * 2], Math.floor(hours / 10), 1);
            setNumber(_hours[i * 2 + 1], hours % 10, 1);

            setNumber(_minutes[i * 2], Math.floor(minutes / 10), 1);
            setNumber(_minutes[i * 2 + 1], minutes % 10, 1);

            setNumber(_seconds[i * 2], Math.floor(seconds / 10), 1);
            setNumber(_seconds[i * 2 + 1], seconds % 10, 1);
        }
    }, 1000);
});

var setNumber = function(digit, number, on) {
    var segments = digit.querySelectorAll('.segment');
    var current = parseInt(digit.getAttribute('data-value'));

    // only switch if number has changed or wasn't set
    if (!isNaN(current) && current != number) {
        // unset previous number
        digitSegments[current].forEach(function(digitSegment, index) {
            setTimeout(function() {
                segments[digitSegment - 1].classList.remove('on');
            }, index * 45)
        });
    }

    if (isNaN(current) || current != number) {
        // set new number after
        setTimeout(function() {
            digitSegments[number].forEach(function(digitSegment, index) {
                setTimeout(function() {
                    segments[digitSegment - 1].classList.add('on');
                }, index * 45)
            });
        }, 250);
        digit.setAttribute('data-value', number);
    }
}