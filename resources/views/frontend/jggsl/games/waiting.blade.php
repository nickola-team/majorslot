<!DOCTYPE html>
<html>
    <head>
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="/frontend/jggsl/css/wait.css">
    <script src="/frontend/Default/js/jquery-3.4.1.min.js"></script>
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300' rel='stylesheet' type='text/css'>
    <!-- <script src='/frontend/Default/js/dev_tools.js'></script> -->
    </head>
    @if ($prompt)
    <body>
    <div id="loader_container">
    
        <div id="bar_container">
            <img src='/frontend/jggsl/tutu/images/loading.gif' width="100%" height="100%"/>
        </div><!--of #bar_container-->
        
        <div id="text_container">
            즐거운 하루 되세요
            <span>무료스핀 구매 한도는 50만원 입니다.</span>
        </div><!--of #text_container-->
        
    </div><!--of #loader_container-->
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