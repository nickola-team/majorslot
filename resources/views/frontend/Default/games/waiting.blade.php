<!DOCTYPE html>
<html>
    <head>
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, maximum-scale=1, user-scalable=no">
    <style>
        body {
        margin: 0;
        }
    </style>
    <link rel="stylesheet" href="/frontend/Default/css/wait.css">
    <script src="/frontend/Default/js/jquery-3.4.1.min.js"></script>
    
    </head>
    @if ($prompt)
    <body>
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
            <div class="box__description-text">머니 이동중입니다.</div>
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