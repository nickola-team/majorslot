<!DOCTYPE html>
<html>
    <head>
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="/frontend/goldslot/css/wait.css">
    <script src="/frontend/Default/js/jquery-3.4.1.min.js"></script>
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300' rel='stylesheet' type='text/css'>
    <script src='/frontend/Default/js/dev_tools.js'></script>
    <script>
        $( document ).ready(function() {
            var bar=$('#progress_bar');
            var percentage=parseInt($('#progress_percentage').html());

            function stopProgress(){
                clearInterval(progress);
            }

            var progress= setInterval(function(){
                percentage++;
                if (percentage<=100){
                    $('#progress_percentage').html(percentage+'%');
                    if (percentage>10) {
                    bar.css('width',percentage+'%');
                    }
                }
                else {
                    stopProgress();
                }
            },200);
        });
    </script>
    </head>
    @if ($prompt)
    <body>
    <div id="loader_container">
    
        <div id="bar_container">
            <div id="progress_bar">
            <div id="progress_percentage">
                10%
            </div><!--of #progress_percentage-->
            </div><!--of #progress_bar-->
        </div><!--of #bar_container-->
        
        <div id="text_container">
            즐거운 하루 되세요
            <span>무료스핀 구매 한도는 50만원 입니다.</span>
        </div><!--of #text_container-->
        
    </div><!--of #loader_container-->
    <div class="loader">
        <ul>
            <li><div></div></li>
            <li><div></div></li>
            <li><div></div></li>
            <li><div></div></li>
            <li><div></div></li>
            <li><div></div></li>
            <li><div></div></li>
            <li><div></div></li>
            <li><div></div></li>
            <li><div></div></li>
            <li><div></div></li>
            <li><div></div></li>
        </ul>
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