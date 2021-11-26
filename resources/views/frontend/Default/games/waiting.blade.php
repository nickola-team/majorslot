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
    <link rel="stylesheet" href="/frontend/Default/css/wait/normalize.css">
	<link rel="stylesheet" href="/frontend/Default/css/wait/main.css">
    <script src="/frontend/Default/js/jquery-3.4.1.min.js"></script>
    <script src="js/vendor/modernizr-2.6.2.min.js"></script>
    <script src='/frontend/Default/js/dev_tools.js'></script>
    </head>
    @if ($prompt)
    <body>
    <div class="box">
        <div class="box__ghost">
            <div id="loader-wrapper">
                <div id="loader"></div>
            </div>
        </div>
        <div class="progress">
            <div class="color"></div>
        </div>
        <div class="box__description">
            <div class="box__description-container">
            <div class="box__description-title_neon">
                즐거운 하루 되세요
            </div>
            <div class="box__description-text">
            무료스핀 구매 한도는 50만원 입니다.
            </div>
            </div>
        </div>
        </div>
    </body>
    @else
    <body style="background:black;">
    </body>
    @endif
    <script type="text/javascript">
        function doAjax() {
            $.ajax({
                        type: 'GET',
                        url: "{{route('frontend.providers.launch', $requestId)}}",
                        cache: false,
                        async: false,
                        success: function (data) {
                            if (data['error'] == false)
                            {
                                window.location.href = data['url'];
                            }
                            else
                            {
                                setTimeout(doAjax, 1000);
                            }
                        },
                        error: function (err, xhr) {
                            console.log(err.responseText);
                            setTimeout(doAjax, 1000);
                        },
                    });
        }
        setTimeout(doAjax, 1000);

        
    </script>
</html>