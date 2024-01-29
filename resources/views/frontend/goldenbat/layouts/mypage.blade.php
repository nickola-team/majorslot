<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>@yield('page-title')</title>
    <meta name="viewport" content="width=device-width">
    <link rel="icon" href="/frontend/goldenbat/img/logo_layer.png" >

	<script type="text/javascript" src="/frontend/goldenbat/js/jquery-1.12.3.min.js"></script>
	<script type="text/javascript" src="/frontend/goldenbat/js/jquery-ui.js"></script>
	<script type="text/javascript" src="/frontend/goldenbat/js/TINYbox.js"></script>
	<script type="text/javascript" src="/frontend/goldenbat/js/jquery.vticker.min.js"></script>
	<script type="text/javascript" src="/frontend/goldenbat/js/jquery.bxslider.min.js"></script>

	<link rel="stylesheet" type="text/css" href="/frontend/goldenbat/css/normalize.css" />
	<link rel="stylesheet" type="text/css" href="/frontend/goldenbat/css/main.css?ver=6038" />
	<link rel="stylesheet" type="text/css" href="/frontend/goldenbat/css/all.css">
	<link rel="stylesheet" type="text/css" href="/frontend/goldenbat/css/Style_sub.css" />

    <script type="text/javascript" src="/frontend/goldenbat/count/jquery.counter.js"></script>

	<script type='text/javascript' src="/frontend/goldenbat/js/game.js?ver=5210"></script>
	<script type='text/javascript' src='/frontend/goldenbat/js/menu.js?ver=3007'></script>
	<script type='text/javascript' src='/frontend/goldenbat/js/main.js?ver=6959'></script>
	<script type='text/javascript' src='/frontend/goldenbat/js/fund.js?ver=5645'></script>
	<script type='text/javascript' src='/frontend/goldenbat/js/common.js?ver=7896'></script>


	<!-- Sweet-Alert  -->
	<script type="text/javascript" src="/frontend/goldenbat/js/sweetalert/sweetalert.min.js"></script>
	<script type="text/javascript" src="/frontend/goldenbat/js/sweetalert/jquery.sweet-alert.custom.js"></script>
	<link rel="stylesheet" href="/frontend/goldenbat/js/sweetalert/sweetalert.css" type="text/css">
</head>
<body>
<div class="subcontent" id="inbox">
	<div id="sub_box">
		<div id="sub_title"><img src="/frontend/goldenbat/img/mypage_title.png" /></div>
		<div id="data_box">
			<div class="txt text01"> 가입정보 안내</div>
			<div class="dbox0">
				<table class="table100" border="0" cellspacing="0" cellpadding="0">
                    <tr>
						<td width="130" class="line2">아이디</td>
						<td class="line2">{{auth()->user()->username}}</td>
					</tr>
				</table>
			</div>
		</div>

		<div id="data_box" style="margin-top:30px;">
			<div class="txt text01"> 쪽지함</div>
			<div class="dbox0">
				<table class="bs-table table100" border="0" cellspacing="0" cellpadding="0">
					<colgroup>
					<col width="55%">
					<col width="25%">
					<col width="10%">
					<col width="10%">
					</colgroup>

					<tbody>
						<tr>
							<td class='line2'>제목</td>
							<td class='line2'>작성일</td>
							<td class='line2'>작성자</td>
							<td class='line2'></td>
						</tr>
						@if (count($msgs) > 0)
						@foreach ($msgs as $msg)
						<tr  >
							<td class='line2' onclick="readMessage('{{$msg->id}}')">{{$msg->title}}</td>
							<td class='line2' onclick="readMessage('{{$msg->id}}')">{{$msg->created_at}}</td>
							<td class='line2' onclick="readMessage('{{$msg->id}}')">{{$msg->writer->hasRole('admin')?'어드민':'총본사'}}</td>
							<td class='line2'>
							<button type="button" class="delete-btn" onclick="deleteMessage('{{$msg->id}}')" style="background:transparent;color:red;border:none;">삭제</button>
							</td>
						</tr>
						<tr class="dropdown">
							<td colspan="4"  style="height: auto;">
								<div class="inner" id="msgcontent{{$msg->id}}" style="display:none;">
									<?php echo $msg->content ?>
								</div>
							</td>
							</tr>
						@endforeach
						@else
						<tr>
							<td class='line2' colspan='4' align="center">쪽지가 없습니다.</td>
						</tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
</body>
<script>
	$(document).ready(function() {
		$('.delete-btn').click(function() {
        
    });
	});
</script>
</html>