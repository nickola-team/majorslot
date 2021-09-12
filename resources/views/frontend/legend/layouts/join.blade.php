<!DOCTYPE html>
<html>
<head>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>@yield('page-title')</title>
    <meta name="viewport" content="width=device-width">
    <link rel="icon" href="/frontend/legend/img/logo_layer.png" >

	<script type="text/javascript" src="/frontend/legend/js/jquery-1.12.3.min.js"></script>
	<script type="text/javascript" src="/frontend/legend/js/jquery-ui.js"></script>
	<script type="text/javascript" src="/frontend/legend/js/TINYbox.js"></script>
	<script type="text/javascript" src="/frontend/legend/js/jquery.vticker.min.js"></script>
	<script type="text/javascript" src="/frontend/legend/js/jquery.bxslider.min.js"></script>

	<link rel="stylesheet" type="text/css" href="/frontend/legend/css/normalize.css" />
	<link rel="stylesheet" type="text/css" href="/frontend/legend/css/main.css?ver=6038" />
	<link rel="stylesheet" type="text/css" href="/frontend/legend/css/all.css">
	<link rel="stylesheet" type="text/css" href="/frontend/legend/css/Style_sub.css" />

    <script type="text/javascript" src="/frontend/legend/count/jquery.counter.js"></script>

	<script type='text/javascript' src="/frontend/legend/js/game.js?ver=5210"></script>
	<script type='text/javascript' src='/frontend/legend/js/menu.js?ver=3007'></script>
	<script type='text/javascript' src='/frontend/legend/js/main.js?ver=6959'></script>
	<script type='text/javascript' src='/frontend/legend/js/fund.js?ver=5645'></script>
	<script type='text/javascript' src='/frontend/legend/js/common.js?ver=7896'></script>


	<!-- Sweet-Alert  -->
	<script type="text/javascript" src="/frontend/legend/js/sweetalert/sweetalert.min.js"></script>
	<script type="text/javascript" src="/frontend/legend/js/sweetalert/jquery.sweet-alert.custom.js"></script>
	<link rel="stylesheet" href="/frontend/legend/js/sweetalert/sweetalert.css" type="text/css">
</head>
	<script type="text/javascript">
		function getContentDocument()
		{
			var objFrame = window.document.getElementById( "iframe" ) ;
			return (objFrame.contentDocument) ? objFrame.contentDocument : objFrame.contentWindow.document;
		}

		function Regist( form )
		{
			if (form.username.value == "")
			{
				alert("ID를 입력해 주십시오.");
				form.username.focus();
				return false ;
			}

			if(form.username.value.length < 3)
			{
				alert("ID는 3~10자 까지 입력 가능합니다.");
				form.username.focus();
				return false ;
			}

			if(form.username.value.length > 10)
			{
				alert("ID는 3~10자 까지 입력 가능합니다.");
				form.username.focus();
				return false ;
			}

			var Item = isCheckOK1(form.username.value);

			if ( Item == false )
			{
				alert("아이디에 허용되지 않는 문자가 포함되어 있습니다.");
				form.username.value = "";
				form.username.focus();
				return false ;
			}

			if (form.password.value == "")
			{
				alert("Password를 입력해 주십시오.");
				form.password.focus();
				return false ;
			}

			if (form.password1.value == "")
			{
				alert("Password를 입력해 주십시오.");
				form.password1.focus();
				return false ;
			}

			if(form.password.value != form.password1.value)
			{
				alert("비밀번호가 일치하지 않습니다.");
				form.password1.focus();
				return false ;
			}

			if(form.password.value.length < 4)
			{
				alert("Password는 4~12까지 입력 가능합니다.");
				form.password.focus();
				return false ;
			}

			if(form.password.value.length > 12)
			{
				alert("Password는 4~12까지 입력 가능합니다.");
				form.password.focus();
				return false ;
			}

			if (form.tel1.value == "")
			{
				alert("연락처를 입력해 주십시오.");
				form.tel1.focus();
				return false ;
			}

			if (form.tel2.value == "")
			{
				alert("연락처를 입력해 주십시오.");
				form.tel2.focus();
				return false ;
			}

			if (form.tel3.value == "")
			{
				alert("연락처를 입력해 주십시오.");
				form.tel3.focus();
				return false ;
			}

			if (form.bank_name.value == "")
			{
				alert("계좌정보를 입력해 주십시오.");
				form.bank_name.focus();
				return false ;
			}

			if (form.account_no.value == "")
			{
				alert("계좌정보를 입력해 주십시오.");
				form.account_no.focus();
				return false ;
			}

			if (form.recommender.value == "")
			{
				alert("계좌정보를 입력해 주십시오.");
				form.recommender.focus();
				return false ;
			}


			if (form.friend.value == "")
			{
				alert("추천인코드를 입력해 주세요.");
				form.friend.focus();
				return;
			}

			$.ajax({
				type: 'POST',
				url: "{{route('frontend.api.join')}}",
				data: {	username: form.username.value,
						password: form.password.value,
						tel1: form.tel1.value,
						tel2: form.tel2.value,
						tel3: form.tel3.value,
						bank_name: form.bank_name.value,
						recommender: form.recommender.value,
						account_no: form.account_no.value,
						friend: form.friend.value},
				cache: false,
				async: false,
				success: function (data) {
					if (data.error) {
						alert(data.msg);
						return;
					}
					else{
						alert(data.msg);
						window.parent.TINY.box.hide();
					}

				},
				error: function (err, xhr) {
					alert(err.responseText);
				}
			});
		}

		function checkid()
		{
			var form = window.document.getElementById( "frmRegist" );

			if(form.username.value.length < 3)
			{
				alert("ID는 3~10자 까지 입력 가능합니다.");
				form.username.focus();
				return false ;
			}

			if(form.username.value.length > 10)
			{
				alert("ID는 3~10자 까지 입력 가능합니다.");
				form.username.focus();
				return false ;
			}

			var Item = isCheckOK1(form.username.value);

		if (form.username.value == "")
		{
			alert("ID를 입력해 주십시오.");
			form.username.focus();
			return false ;
		}

		if ( Item == false )
		{
			alert("아이디에 허용되지 않는 문자가 포함되어 있습니다.");
			form.username.value = "";
			form.username.focus();
			return false ;
		}
		$.ajax({
			type: 'POST',
			url: "{{route('frontend.api.checkid')}}",
			data: {id: form.username.value },
			cache: false,
			async: false,
			success: function (data) {
				if (data.error) {
					alert(data.msg);
					return;
				}
				if (data.ok == 1){
					alert('이 아이디는 이용하실수 있습니다.');
				}
				else
				{
					alert('이 아이디는 이미 사용중입니다.');
				}
			},
			error: function (err, xhr) {
				alert(err.responseText);
			}
    	});

		return true ;
	}

</script>
</head>
<body style="background-color:transparent;">
<div class="subcontent" id="deposit">
<form action="" method="post" id="frmRegist" name="frmRegist">
<div id="sub_box">
	<div id="sub_title"><img src="/frontend/legend/img/join_title.png" /></div>
	<div id="data_box">
	<div class="txt"><span class="txt_style02"><b>- 중복 회원가입 방지를 위해 아이디는 중복확인을 해주십시오. 회원가입에 필요한 정보를 입력해주세요.<b></span></div>
		<div class="dbox0">
            <input type="hidden" value="<?= csrf_token() ?>" name="_token" id="_token">
			<table class="table100" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="line2">아이디</td>
					<td class="line3">
						<div class="txt_area2">
							<input type="text" name="username" maxlength="12" style="width:80px; height:18px ime-mode:disabled;" />
							<a href="#" onclick="javascript:checkid();"><img src="/frontend/legend/img/double_btn.gif" width="60" height="22" border="0" align="absmiddle" /></a>
							<span name="div_id" id="div_id">* 회원 아이디는 3자 이상 10자 이하로 입력하세요.</span>
						</div>
					</td>
				</tr>
				<tr>
					<td class="line2">비밀번호</td>
					<td class="line3">
						<div class="txt_area2">
							<input type="password" name="password" id="password" style="width:80px; height:18px" />
							* 비밀번호는 4자 이상 12자 이하로 입력하세요.
						</div>
					</td>
				</tr>
				<tr>
					<td width="120" class="line2">비밀번호확인</td>
					<td class="line3">
						<span class="txt_area2">
							<input type="password" name="password1" id="password1" style="width:80px; height:18px" />
						</span>
					</td>
				</tr>
				<tr>
					<td class="line2">&nbsp;</td>
					<td class="line3"><span class="txt_style03">* 반드시 본인의 핸드폰번호를 정확하게 입력하시기 바랍니다.</span></td>
				</tr>
				<tr>
					<td class="line2">핸드폰</td>
					<td class="line3">
						<select name="tel1" class="input3" id="tel1" span="span" style="width:60px;position: relative; top: 0.1em">
							<option value="">-선택-</option>
							<option value="010">010</option>
							<option value="011">011</option>
							<option value="016">016</option>
							<option value="017">017</option>
							<option value="018">018</option>
							<option value="019">019</option>
						</select>
						- <input name="tel2" type="text" id="tel2" maxlength="4" style="width:50px; height:18px" value="" />
						- <input name="tel3" type="text" id="tel3" maxlength="4" style="width:50px; height:18px" value="" />
					</td>
				</tr>
			<tr>
			  <td class="line2">&nbsp;</td>
			  <td class="line3"><span class="txt_style03">* 입금하시는 정확한 계좌를 입력해주셔야 출금시 같은계좌로  정상적으로 처리됩니다.</span></td>
			</tr>
			<tr>
			  <td class="line2">은행명</td>
			  <td class="line3">
					<input type="text" name="bank_name" id="bank_name" value="" size="15" maxlength="30"  placeholder="* 은행명" style="font-size:1em; width: 50%;"/>
			  </td>
			</tr>

			<tr>
			  <td class="line2">예금주</td>
			  <td class="line3">
					<input type="text" name="recommender" id="recommender" value="" size="15" maxlength="30"  placeholder="* 예금주" style="font-size:1em; width: 50%;"/>
			  </td>
			</tr>
			<tr>
			  <td class="line2">계좌번호</td>
			  <td class="line3">
					<input type="text" name="account_no" id="account_no" value="" size="15" maxlength="30"  placeholder="* 계좌번호" style="font-size:1em; width: 50%;" />
			  </td>
			</tr>

			<tr>
				<td class="line2">추천인코드</td>
				<td class="line3">
					<input type="text" id="friend" name="friend" style="width:80px; height:18px;" maxlength="12" value="">
				</td>
			</tr>
			</table>
		</div>
		<div class="btn"><a href="#" onClick="Regist(frmRegist);"><img src="/frontend/legend/img/join_btn.png" border="0" /></a></div>
	</div>
</div>
</form>
</div>
</body>
</html>