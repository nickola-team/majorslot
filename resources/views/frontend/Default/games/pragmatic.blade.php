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
    <script src="https://client.pragmaticplaylive.net/desktop/assets/api/fullscreenApi.js" ></script>
    <script src="/frontend/Default/js/jquery-3.4.1.min.js"></script>
    <script>
        function injectJS() {
            // var iFrameDoc = window.frames["embedgameIframe"].contentWindow.document;         
            // var myscript = document.createElement('script');
            // myscript.type = 'text/javascript';
            // myscript.src = '/frontend/Default/js/dev_tools.js';
            // if (iFrameDoc){
            //     iFrameDoc.head.appendChild(myscript);
            // }
        }
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
    <script type="text/javascript">
        /* 
        * Parent (iframe holder) postMessage 'receiving' javascript.
        * (If you want the complete postMessage code, see the links below.)
        * 
        * Modified by Neal Delfeld.
        * Released under the Apache 2.0 license. 
        * 
        * 
        * Other reference info:
        * 
        * A backwards compatable implementation of postMessage.
        * By Josh Fraser (joshfraser.com, http://www.onlineaspect.com/2010/01/15/backwards-compatible-postmessage/)
        * Released under the Apache 2.0 license. 
        *
        * This code was adapted from Ben Alman's jQuery postMessage code found at:
        * http://benalman.com/projects/jquery-postmessage-plugin/
        * 
        * Other inspiration was taken from Luke Shepard's code for Facebook Connect:
        * http://github.com/facebook/connect-js/blob/master/src/core/xd.js
        *
        * The goal of this project was to make a backwards compatable version of postMessage
        * without having any dependency on jQuery or the FB Connect libraries.
        *
        * My goal was to keep this as terse as possible since my own purpose was to use this 
        * as part of a distributed widget where filesize could be sensative.
        * 
        */

        /* everything is wrapped in the XD function to reduce namespace collisions */
        var XD = function(){

            var interval_id,
            last_hash,
            cache_bust = 1,
            attached_callback,
            window = this;

            return {
                receiveMessage : function(callback, source_origin) {

                    /* browser supports window.postMessage */
                    if (window['postMessage']) {
                        /* bind the callback to the actual event associated with window.postMessage */
                        if (callback) {
                            attached_callback = function(e) {
                                if ((typeof source_origin === 'string' && e.origin !== source_origin)
                                || (Object.prototype.toString.call(source_origin) === "[object Function]" && source_origin(e.origin) === !1)) {
                                    return !1;
                                }
                                callback(e);
                            };
                        }
                        if (window['addEventListener']) {
                            window[callback ? 'addEventListener' : 'removeEventListener']('message', attached_callback, !1);
                        } else {
                            window[callback ? 'attachEvent' : 'detachEvent']('onmessage', attached_callback);
                        }
                    } else {
                        /* a polling loop is started & callback is called whenever the location.hash changes */
                        interval_id && clearInterval(interval_id);
                        interval_id = null;

                        if (callback) {
                            interval_id = setInterval(function(){
                                var hash = document.location.hash,
                                re = /^#?\d+&/;
                                if (hash !== last_hash && re.test(hash)) {
                                    last_hash = hash;
                                    callback({data: hash.replace(re, '')});
                                }
                            }, 100);
                        }
                    }
                }
            };
        }();

        </script>
    </head>
    <body>
    @if (isset($url))
    <div style="position:relative; width:100%; height:100%;">
        <iframe id="embedgameIframe" style="margin:0; padding:0; white-space: nowrap; border: 0; width:100%;height:100%;" frameborder="0" border="0" cellspacing="0"
        src=" {{ $url }}" onLoad="injectJS()" allowfullscreen></iframe>
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
        var urlPath = "{{$url??''}}";
        const url = new URL(urlPath);
        XD.receiveMessage(handler,url.origin);

        function handler(message)
        {
            console.log("---[" + message.data.name + "]--- received on " + window.location.host);
            if (message.data.name == 'gameRoundEnded' || message.data.name == 'gameRoundStart' || message.data.name == 'notifyCloseContainer')
            {
                $.ajax({
                    type: 'POST',
                    url: '/pp/userbet',
                    data: message.data,
                    cache: false,
                    async: false,
                    success: function (data) {
                        console.log(data);
                    },
                    error: function (err, xhr) {
                        console.log(err.responseText);
                    }
                });
            }
            console.log(JSON.stringify(message.data));

        }
        //for pp live games
        window.onbeforeunload = function () {
            console.log('exiting game');
            var formData = new FormData();
            formData.append("name", "notifyCloseContainer");
            navigator.sendBeacon('/pp/userbet', formData);
        }
    </script>
    @endif
</html>