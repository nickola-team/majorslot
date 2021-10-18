<!DOCTYPE html>
<html lang="en">

<head>
    <base href="{{Request::root()}}/pphistory/">
    <script>
        window.token = "{{$usertoken}}";
        window.language = "ko"
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=0.6">
    <meta name="google" content="notranslate">
    <title>Game history</title>
    <link href="main.{{$hash}}.min.css" rel="stylesheet">
</head>

<body>
    <div id="root"></div>
    <div id="modal-root"></div>
    <script src="main.{{$hash}}.min.js"></script>
</body>

</html>