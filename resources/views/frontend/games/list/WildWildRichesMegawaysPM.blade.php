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



<iframe id='game' style="margin:0px;border:0px;width:100%;height:100vh;" src='/games/_WildWildRichesMegawaysPM/openGame.do?lang=en&cur=@if( auth()->user() != null && auth()->user()->present()->shop ){{ auth()->user()->present()->shop->currency }}@endif&extGame=1&gameSymbol=vswayswwriches&websiteUrl=&lobbyURL=&envID={{isset($envID)?$envID:0}}&userID={{isset($userId)?$userId:0}}&styleName={{isset($styleName)?$styleName:""}}&replayURL={{isset($replayUrl)?$replayUrl:""}}' allowfullscreen>


</iframe>




</body>

</html>
