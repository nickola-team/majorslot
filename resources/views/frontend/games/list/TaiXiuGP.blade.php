<!DOCTYPE html>
<html>
<head>
    <title>{{ $game->title }}</title>
    <meta charset="utf-8">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, minimal-ui">
    <style type="text/css">
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            overflow: auto;
            text-align: center;
            background-color: #000000;
        }

        object:focus {
            outline: none;
        }

        #html5content {
            width: 100%;
            height: 100%;
            background-color: #1F1F1F;
        }

        #gameframe {
            width: 100%;
            height: 100%;
        }

        #gameframe.webpk10 {
            width: 520px;
            height: 590px;
        }

        #html5content.webpk10 {
            background: url(./Images/webbgpk10.jpg?game=pk10&v=1) center center;
            background-size: cover;
        }

        
        #errorDialog {
            font-family: "Arial", "Veradana";
            color: #fff;
            left: 0;
            top: 0;
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.6);
            display: block;
            text-align: left
        }

        #errorDialog .error-bg {
            cursor: pointer;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            background-color: rgba(0,0,0,0.6);
        }

        #errorDialog .error-content {
            max-width: 600px;
            min-width: 350px;
            min-height: 50px;
            border-radius: 6px;
            border: 1px solid #9CCC02;
            background-color: #fff;
            position: absolute;
            left: 50%;
            top: 50%;
            z-index: 2;
            -webkit-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
        }

        #errorDialog .erroHeader {
            height: 40px;
            width: 100%;
            background-color: #9CCC02;
            border-top-left-radius: 6px;
            border-top-right-radius: 6px;
            text-shadow: 0 -1px rgba(0, 0, 0, 0.3);
        }

        #errorDialog .errorTitle {
            text-shadow: 0 -1px rgba(0, 0, 0, 0.3);
            padding: 10px 0px 0px 10px;
        }

        #errorDialog #errormessage {
            padding: 5px 20px;
            font-size: 14px;
            line-height: 18px;
            color: #232323;
        }

        #errorDialog .box-icon {
            display: block;
            float: left;
            width: 30px;
            height: 25px;
            margin-top: -2px;
            background-repeat: no-repeat;
            background-position: 2px 1px;
            background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAQAAAAngNWGAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QA/4ePzL8AAAAHdElNRQfkCgUJARg5yYk/AAAAsUlEQVQoz5VTbRXCMBBL9xCABCRMQnEwR4CCShgOKoGhYDhAwuYg/BjQNj0+lv7o6zXv7ppLHSHwz32QONPyjMwR6dNtogVaCEqM/ISYE4Ncbdlq1qW3Eh1REEm/ELXsnT0nLe8IVAoZcM1btxdmnLHHTdXdSGDACQOAWVM2ct7hUMznC/EKoDO6JOTN3pySIQ8IXqqpV4KPBMGWoyV4Xmrikb1ljJWmWGGzn8Z1/36FB9hWt0tFs7izAAAAJXRFWHRkYXRlOmNyZWF0ZQAyMDIwLTEwLTA1VDA5OjAxOjI0KzAzOjAw3ypt9AAAACV0RVh0ZGF0ZTptb2RpZnkAMjAyMC0xMC0wNVQwOTowMToyNCswMzowMK531UgAAAAZdEVYdFNvZnR3YXJlAEFkb2JlIEltYWdlUmVhZHlxyWU8AAAAAElFTkSuQmCC);
        }
    </style>
   </head>

<body>
<?php
$platform = 'HTML5';
$detect = new \Detection\MobileDetect();

if( $detect->isMobile() || $detect->isTablet() ) 
{
    $platform = 'Mobile';
}
?>
<div id="html5content">
    <div style="position: absolute; top: 0px; left: 0px; color: #4bff4b; background: rgba(0,0,0,0.5); display: none; pointer-events: none; padding: 2px " id="debug"></div>
    <iframe id='gameframe' src='/games/TaiXiuGP/game/TaiXiu/{{$platform}}/?lang={{$pagelang?($pagelang=="ko"?"ko-kr":"vi-vn"):"ko-kr"}}&mode=True&t={{auth()->user()->api_token}}' frameborder="0"></iframe>
</div>

<script type="text/javascript">
            function debounce(method, delay) {
                clearTimeout(method._tId);
                method._tId = setTimeout(function () {
                    method();
                }, delay);
            }
            if (!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                window.onload = function () {
                    document.getElementsByTagName('iframe')[0].contentWindow.focus();
                    window.pk10Scale && window.pk10Scale();
                }
            } else {
                var gameframe = document.getElementById("gameframe");
                gameframe.setAttribute("allowfullscreen", "true");
                gameframe.setAttribute("webkitallowfullscreen", "true");
                gameframe.setAttribute("mozallowfullscreen", "true");
                let width = Math.min(window.innerWidth, document.body.clientWidth);
                let height = Math.min(window.innerHeight, document.body.clientHeight);
                function resize() {
                    let newWidth = Math.min(window.innerWidth, document.body.clientWidth);
                    let newHeight = Math.min(window.innerHeight, document.body.clientHeight);
                    document.getElementById("gameframe").style = `width: ${newWidth}px; height: ${newHeight}px`;
                    setTimeout(() => {
                        window.scrollTo(0, 0);
                        if (newHeight != height || newWidth != width) {
                            setTimeout(() => resize(), 200);
                        }
                        width = newWidth;
                        height = newHeight;
                    }, 100);
                }
                window.onresize = resize;
                window.onscroll = debounce(resize, 200);
                setTimeout(() => resize(), 200);
            }
        </script>
</body>

</html>
