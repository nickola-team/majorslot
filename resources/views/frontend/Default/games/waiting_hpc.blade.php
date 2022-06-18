<!DOCTYPE html>
<html>
    <head>
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="/frontend/Default/css/wait/wait.css">
    <link rel="stylesheet" href="/frontend/Default/css/wait/normalize.css">
	<link rel="stylesheet" href="/frontend/Default/css/wait/main.css">
    <script src="/frontend/Default/js/jquery-3.4.1.min.js"></script>
    <script src="js/vendor/modernizr-2.6.2.min.js"></script>
    <!-- <script src='/frontend/Default/js/dev_tools.js'></script> -->
    </head>
    @if ($prompt)
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