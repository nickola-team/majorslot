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
        <div class="box__ghost">
            <div class="symbol"></div>
            <div class="symbol"></div>
            <div class="symbol"></div>
            <div class="symbol"></div>
            <div class="symbol"></div>
            <div class="symbol"></div>
            
            <div class="box__ghost-container">
            <div class="box__ghost-eyes">
                <div class="box__eye-left"></div>
                <div class="box__eye-right"></div>
            </div>
            <div class="box__ghost-bottom">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
            </div>
            <div class="box__ghost-shadow"></div>
        </div>
        
        <div class="box__description">
            <div class="box__description-container">
            <div class="box__description-title">앗! 이런!</div>
            <div class="box__description-text">게임로드중 오류가 발생하였습니다.</div>
            <div class="box__description-text">오류코드: {{json_encode($data)}}</div>
            <div class="box__description-text">잠시후 다시 시도해주세요</div>
            </div>            
        </div>
        </div>
    @endif
    </body>
    @if (isset($alonegame) && $alonegame == 0)
    <script type="text/javascript">
        window.onbeforeunload = function () {
            console.log('exiting game');
            var formData = new URLSearchParams();
            formData.append("name", "exitGame");
            navigator.sendBeacon('/tp/signal', formData);
        }

        var updateTime = 3 * 1000;

        var updateUserBalance = function (callback) {
            console.log('user playing game');

            $.ajax({
                type: "POST",
                url: "/tp/signal",
                data: {name:'playing'},
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                success: function (data) {
                    if (data.error != 0)
                    {
                        alert('오류가 발생하였습니다. 게임을 다시 시작해주세요');
                        window.close();
                    }
                    timeout = setTimeout(updateUserBalance, updateTime);
                },
                error: function(data)
                {
                    timeout = setTimeout(updateUserBalance, updateTime);
                }
            });

        };
        timeout = setTimeout(updateUserBalance, updateTime);
    </script>
    @endif
</html>