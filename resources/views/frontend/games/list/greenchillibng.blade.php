<!DOCTYPE html>
<html>
<head>
    <title>{{ $game->title }}</title>
    <meta charset="utf-8">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, minimal-ui">
      <style>
         body,
         html {
         position: fixed;
         } 
      </style>
   </head>

<script>

    if( !sessionStorage.getItem('sessionId') ){
        sessionStorage.setItem('sessionId', parseInt(Math.random() * 1000000));
    }


addEventListener('message',function(ev){
	
if(ev.data=='CloseGame'){

document.location.href='../../';	
}
	
	});
</script>

<body style="margin:0px;width:100%;background-color:black;overflow:hidden">


<iframe id='game' style="margin:0px;border:0px;width:100%;height:100vh;" src="/games/greenchillibng/game.html?token=_major:17390&game=greenchillibng&lang=ko&sound=1&quickspin=1&ts=1645537656&exit_url=&cashier_url=&history_url=/op/major/history.html&player_id=@if( auth()->user() != null){{ auth()->user()->id }}@endif&game_id={{isset($envID)?$envID:0}}&currency=KRW&mode=REAL&brand=major&tz=0&lang=ko&mobile=0&title=&wl=prod" allowfullscreen>



</iframe>




</body>

</html>
