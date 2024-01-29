<!DOCTYPE html>
<html>

<head>
    <title>Game Drawer</title>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="data:image/x-icon;," type="image/x-icon">
    <style>
        html,
        body {
            font-family: sans-serif;
            padding: 0;
            margin: 0;
            width: 100%;
            height: 100%;
            min-width: 100%;
            min-height: 100%;
        }

        body {
            overflow: hidden;
        }
    </style>
</head>

<body>
    <div id="rounds-info"></div>
    <script src="/op/games/grab_more_gold/index.js?_ts=c923994f"></script>
    <script>
        (function() {
            function debounce(fn, ms, immediate) {
                var timeout;
                return function() {
                    var context = this,
                        args = arguments,
                        callNow = !timeout && immediate;
                    clearTimeout(timeout);
                    timeout = setTimeout(function() {
                        timeout = null;
                        if (!callNow) fn.apply(context, args);
                    }, ms);
                    if (callNow) fn.apply(context, args);
                };
            }

            var draw = function(el, details, minRatio, maxRatio) {
                if (!details) return;
                details = JSON.parse(JSON.stringify(details));
                while (el.firstChild) {
                    el.removeChild(el.firstChild);
                }
                // apply size restriction;
                var wWidth = window.innerWidth,
                    wHeight = window.innerHeight,
                    ratio = wWidth / wHeight;

                if (minRatio && ratio < minRatio)
                    ratio = minRatio;
                else if (maxRatio && ratio > maxRatio)
                    ratio = maxRatio;

                var w = Math.min(wWidth, wHeight * ratio) | 0,
                    h = (w / ratio) | 0,
                    t = (wHeight - h) >> 1,
                    l = (wWidth - w) >> 1;

                el.style.width = w + 'px';
                el.style.height = h + 'px';
                el.style.top = t + 'px';
                el.style.left = l + 'px';
                el.style.position = 'relative';

                if (window.Drawer) {
                    (new Drawer('/op/games/grab_more_gold/', 'c923994f')).draw(el, details);
                    // mobile chrome 90 crashes on orientation change, if svg is last child
                    el.appendChild(document.createElement('br'));
                } else {
                    el.innerHTML = 'No drawer provided';
                }
            };

            var getIsInIframe = function() {
                try {
                    return window.self !== window.top;
                } catch (e) {
                    return true;
                }
            };

            var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent",
                eventer = window[eventMethod],
                messageEvent = eventMethod === "attachEvent" ? "onmessage" : "message",
                resizeEvent = eventMethod === "attachEvent" ? "onresize" : "resize",
                loadEvent = eventMethod === "attachEvent" ? "onload" : "load",
                el = document.getElementById('rounds-info'),
                debouncedDraw = debounce(draw, 100, true),
                details = {!! $details !!},
                inIframe = getIsInIframe(),
                maxRatio = inIframe ? null : 2560 / 1080,
                minRatio = inIframe ? null : 1920 / 1200;

            eventer(messageEvent, function(e) {
                var received_data = JSON.parse(e.data);
                if (received_data.event === 'details') {
                    details = received_data.details;
                    debouncedDraw(el, details, minRatio, maxRatio);
                }
            }, false);

            eventer(resizeEvent, function(e) {
                debouncedDraw(el, details, minRatio, maxRatio);
            }, false);

            eventer(loadEvent, function() {
                debouncedDraw(el, details, minRatio, maxRatio);
            });
        })();
    </script>
</body>

</html>