<!DOCTYPE html>
<html>
    <head>
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, maximum-scale=1, user-scalable=no">
    <style>
        body {
        margin: 0;
        }
    </style>
    <link rel="stylesheet" href="/frontend/Default/css/wait/wait.css">
    <script src="/frontend/Default/js/jquery-3.4.1.min.js"></script>
    <script>
        window.onload = function () {
            document.getElementById('embedgameIframe').focus();

            document.documentElement.style.width = "100%";
            document.documentElement.style.height = "100%";
            document.documentElement.style.overflow = 'hidden';
            document.body.style.width = "100%";
            document.body.style.height = "100%";
            var viewport = document.querySelector('meta[name=viewport]');
            if (!viewport) {
                var metaTag = document.createElement('meta');
                metaTag.name = 'viewport';
                metaTag.content = 'width=device-width, height=device-height, initial-scale=1, maximum-scale=1, user-scalable=no';
                document.getElementsByTagName('head')[0].appendChild(metaTag);
            }
            else {
                viewport.setAttribute('content', 'width=device-width, height=device-height, initial-scale=1,maximum-scale=1, user-scalable=no');
            }
        };
    </script>
    </head>
    <body>
    @if (isset($url))
    <div style="position:relative; width:100%; height:100%;">
        <iframe id="embedgameIframe" style="margin:0; padding:0; white-space: nowrap; border: 0; width:100%;height:100%;" frameborder="0" border="0" cellspacing="0"
        src=" {{ $url }}" allowfullscreen></iframe>
    </div>
    @else
    <div class="box">
    </div>
    @endif
    </body>
</html>