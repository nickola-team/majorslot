<!DOCTYPE html>
<html lang="ko">
<head>
<title>KHAN-1</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" /><meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=1.0, user-scalable=0" />
    <!-- WEB FONT -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/earlyaccess/nanumgothic.css" /><link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet" />
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://code.jquery.com/jquery.min.js"></script>
	<script src="/frontend/k1slot/js/jquery.ui.touch-punch.min.js"></script>
	<link rel="stylesheet" href="/frontend/k1slot/css/bootstrap-reboot.css" />
    
    <script>
        $(document).ready(function () {
            $('#login_id').css({ 'background': '/imgaes/login_id_focus.png' });
            $('input').attr('autocomplete', 'off');

            var paddingTop = ($(window).height() - $('#login').height()) / 2;
            $('#login').css('padding-top', paddingTop);
        });
	</script>
	<style>
		@import url ( 'https://fonts.googleapis.com/css?family=Raleway'); 
		html, body { width:100%;height:100%; }
		body { background:url('/frontend/k1slot/images/bg.jpg') no-repeat center center;background-size:cover; }       
        /*.login {width:382px;position: relative;margin:0px auto;padding: 20px 5px 10px 5px;background: url(/images/userform-bg.png);border-radius: 3px;-webkit-border-radius: 3px;box-shadow: 3px 3px 10px #000;border: 1px solid rgba(255, 255, 255, 0.2);}*/
		.login_id { color:#fff;font-weight:bold;width:370px;height:50px;background:url('/frontend/k1slot/images/login_id.png') no-repeat;border:0;padding:0 0 0 65px;margin:0 0 10px 0; }
		.login_pw { color:#fff;font-weight:bold;width:370px;height:50px;background:url('/frontend/k1slot/images/login_pw.png') no-repeat;border:0;padding:0 0 0 65px;margin:0 0 10px 0; }
		.btn_login { display:inline-block;width:370px;height:70px;line-height:70px;font-size:30px;font-weight:800;font-family: 'Raleway', sans-serif;color:#fff;background:#be1414;margin:0 0 10px 0;border-radius:4px; }
		.btn_login:hover { text-decoration:none;background:#c91616;color:#fff; }
	</style>
</head>
<body style="text-align:center;">
  <form role="form" action="{{ route('frontend.auth.login.post') }}" method="POST" id="login-form" autocomplete="off">
    <div id="login" style="padding-top: 300px;">
	    <div class="logo"><img src="/frontend/k1slot/images/logo.png?=1" width="" /><br><br></div>
	    <div class="login">
		    <div>
			    <div>
                    <input name="username" type="text" id="username" tabindex="1" class="login_id" onkeypress="$(this).css({&#39;background&#39;:&#39;url(\&#39;/frontend/k1slot/images/login_id_focus.png\&#39;) no-repeat&#39;});" autocomplete="new-username" />
			    </div>
			    <div>
                    <input name="password" type="password" id="password" tabindex="2" class="login_pw" onkeypress="$(this).css({&#39;background&#39;:&#39;url(\&#39;/frontend/k1slot/images/login_pw_focus.png\&#39;) no-repeat&#39;});" autocomplete="new-username" />     
			    </div>
                <div style="border:none;background-color:#d8d8d8;box-shadow:none;text-align:center;margin:0px auto 10px;width:370px;display:none;">
				</div>
		    </div>
		    <div>
			    <div>
                    <input type="submit" name="ctl00$BodyContent$btnLogin" value="로그인" id="BodyContent_btnLogin" tabindex="4" class="lg_bt btn_login" style="border:none;" />
			    </div>
		    </div>
	    </div>
    </div>

    </form>
</body>
</html>
