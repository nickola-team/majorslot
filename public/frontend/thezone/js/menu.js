function goHome() {
    top.location.href = "/";
}

function goCasino() {
    if (loginYN == "Y") {
        var strHtml = `
        <div class="subcontent">
<div id="sub_box">
	<div id="sub_title"><img src="/frontend/thezone/img/casino_title.png" /></div>
	<div id="data_box">
		<div class="dbox2">
			<div id="weekly_item2">
				<div class="remote_area" style="font-size:16px;">
					<p><span class="txt_style01"><b>MGM카지노에 오신것을 환영합니다.</b></span><br />
					MGM카지노는 2009년 7월1일 각 게임사와 정식으로 에이전트 계약을 성사 시키고 중국,필리핀,캄보디아등지에서 온/오프라인 카지노운영의 노하우를 바탕으로 2014년 국내 온라인서비스를 시작하였습니다.</p>
					<p>카지노는 게임 개발에 대한 충분한 투자를 바탕으로 온라인 환경에 최적합한 영상 및 게임 진행 방식을 제공하며 최고의 기술력을 통한 안정적이고 공정한 게임진행을 약속드립니다.<br/>
					인생 최고의 순간! 가장 짜릿한 카지노 게임을 MGM카지노에서 경험해보세요.여러분께 잊지 못할 최고의 순간을 전해드립니다.<br/></p>
					<p><span class="txt_style02">MGM카지노는 언제나 안전하고 정확한 서비스를 약속드립니다.</span></p>
				</div>
				<div style="clear:both"></div>
				<br />
				<div class="middle"> 마이크로</div>
				<div class="dd_area2">
					<span class="txt_area3">Microgaming은 1994 년에 세계 최초의 온라인 카지노 소프트웨어를 개발하고 2004 년 우리의 제품에서 최초의 모바일 카지노 소프트웨어는 카지노, 포커, 빙고, 스포츠 북, 토지 기반의 살아있는 상인 및 아발론 Quickfire. 홈, 벼락, Dragonz 및 기타 서사시 포함 게임, 같은 쥬라기 월드 ™, 왕좌의 게임 ™ 및 플레이 보이 ™ 골드로하지 브랜드의 언급 blockerbusters, 우리는 데스크톱과 모바일을 통해 매월 새로운 타이틀을 시작합니다.
					</span>
				</div>
				<div style="clear:both"></div>
				<br />
				<div class="middle"> 태산</div>
				<div class="dd_area2">
					<span class="txt_area3">타이샨 글로벌은 세계 최고 온라인 카지노 업체를 목표로 오랜기간 동안 온/오프라인 카지노를 개발/운영하고 그 노하우를 바탕으로 타이샨 온라인 카지노를 개발하였습니다. 타이샨 글로벌은 해외 현지 카지노에서만 경험할수 있는 바카라, 룰렛, 드래곤타이거, 식보등의 다양한 종류의 카지노 게임을 온라인으로 서비스하고 있습니다.
					</span>
				</div>
				<div style="clear:both"></div>
				<br />
				<div class="middle"> 아시아</div>
				<div class="dd_area2">
					<span class="txt_area3">아시아는 현재 아시아 시장에서 가장 신뢰받고 가장 인기있는 대형 게임 회사 중의 하나 입니다. 2009년부터 온라인 엔터테인먼트 서비스를 제공하기 시작해 긍정적인 변화와 혁신적인 기술을 개발하여 점차 각계의 인정을 받고 있습니다. 아시아를 통해 온라인 인터랙티브 엔터테인먼트의 재미를 경험해보세요.
					</span>
				</div>
				<div style="clear:both"></div>
				<br />

			</div>
			<div style="clear:both"></div>
		</div>
	</div>
</div></div>`;

        TINY.box.show({
            html: strHtml,
            width: 955,
            height: 600,
        });
    } else {
        showLoginAlert();
    }
}

function goRemote() {
    if (loginYN == "Y") {
        var strHtml = `
        <div class="subcontent">
<div id="sub_box">
<div id="sub_title"><img src="/frontend/thezone/img/remote_title.png" /></div>
<div id="data_box">
    <div class="dbox2">
        <div class="remote_area">
            일대일원격지원 서비스는 게임이용과 관련하여 장애가 발생하는 고객에게 <span class="txt_style02">고객지원센터에서 고객님의 PC에 직접 접속해</span> 각종 오류 및 기술적 문제를 해결함으로써<br />
            원활한 게임접속을 도와드리는 기술지원서비스입니다.
        </div>
        <div class="remote_area2">
            <table align="center">
            <tr>
                <td>
                    <a href="http://download.teamviewer.com/download/TeamViewer_Setup_ko.exe" target="_blank"><img src="/frontend/thezone/img/remote.gif" border="0" /></a><br />
                    <span class="txt_style02">⊙ 해당 프로그램을 다운로드 설치후, 귀하의 ID 비밀번호를 관리자에게 알려주시면 됩니다.</span><br />
                </td>
            </tr>
            </table>
        </div>
        <div class="remote_area"><br/>※ 카지노 이용시 게임실행이 안되시거나 사이트 이용시 불편한 점이 있는 경우에는 24시간 고객센터로 연락주시면 신속히 처리해드립니다.</div><div style="clear:both"></div>
    </div>
</div>
</div>
        </div>`;

        TINY.box.show({
            html: strHtml,
            width: 955,
            height: 600,
        });
    } else {
        showLoginAlert();
    }
}

function goRule(type) {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: "http://thezone777.com/etc/rule/" + type + ".asp",
            width: 955,
            height: 600,
        });
    } else {
        showLoginAlert();
    }
}

function goinfoBaccarat() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: "/info/info_rule.asp?baccarat",
            width: 850,
            height: 600,
        });
    } else {
        showLoginAlert();
    }
}

function goinfoBlackjack() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: "/info/info_rule.asp?blackjack",
            width: 850,
            height: 600,
        });
    } else {
        showLoginAlert();
    }
}

function goinfoRoulette() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: "/info/info_rule.asp?roulette",
            width: 850,
            height: 600,
        });
    } else {
        showLoginAlert();
    }
}

function goinfoSicbo() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: "/info/info_rule.asp?sicbo",
            width: 850,
            height: 600,
        });
    } else {
        showLoginAlert();
    }
}

function goinfoDragontiger() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: "/info/info_rule.asp?dragontiger",
            width: 850,
            height: 600,
        });
    } else {
        showLoginAlert();
    }
}

function goinfoCaribbean() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: "/info/info_rule.asp?caribbean",
            width: 850,
            height: 600,
        });
    } else {
        showLoginAlert();
    }
}

function goinfoTripleface() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: "/info/info_rule.asp?tripleface",
            width: 850,
            height: 600,
        });
    } else {
        showLoginAlert();
    }
}

function goDeposit() {
    if (loginYN == "Y") {
        var strHtml = `
            <div class="subcontent" id="deposit">
<form method="post" id="fundFrm" name="fundFrm">

<div id="sub_box">
	<div id="sub_title"><img src="/frontend/thezone/img/pay_title.png" /></div>
	<div id="data_box">
		<div class="txt"><span class="txt_style01"><b>- 신속한 충전을 위해서는 올바른 입금정보를 입력해 주셔야 합니다.<br />- 입금확인 즉시 신청하신 아이디로 게임머니를 충전해드립니다.<b></span></div>
		<div class="txt">
			<b> 회원님들의 안전과 보안을 위해 <span class="txt_style02">입금계좌는 수시변경 될수</span>있습니다.<br />
			</b>
		</div>
		<div class="dbox0">
			<table class="table100" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="line2">입금하실 금액</td>
					<td class="line3">
						<div class="txt_area2">
							<input id="money" type="hidden" name="money" value="0">
							<input type="text" name="money1" id="money1" style="width:80px; height:18px" onchange="comma()" />
							<a href="javascript:money_count('50000');"><img src="/frontend/thezone/img/5man.gif" width="46" height="22" border="0" align="absmiddle" /></a>
							<a href="javascript:money_count('100000');"><img src="/frontend/thezone/img/10man.gif" width="46" height="22" border="0" align="absmiddle" /></a>
							<a href="javascript:money_count('300000');"><img src="/frontend/thezone/img/30man.gif" width="46" height="22" border="0" align="absmiddle" /></a>
							<a href="javascript:money_count('500000');"><img src="/frontend/thezone/img/50man.gif" width="46" height="22" border="0" align="absmiddle" /></a>
							<a href="javascript:money_count('1000000');"><img src="/frontend/thezone/img/100man.gif" width="46" height="22" border="0" align="absmiddle" /></a>
							<a href="javascript:money_count_hand();"><img src="/frontend/thezone/img/direct.gif" width="46" height="22" border="0" align="absmiddle" /></a>
						</div>
						<div class="txt_area"></div>
					</td>
				</tr>
                <tr>
					<td class="line2">입금자명</td>
					<td class="line3">
						<div class="txt_area2">
							<input type="text" name="name" id="name" style="width:80px; height:18px" value="${accountName}"/>
						</div>
						<div class="txt_area3">* 입금시 입금자명이 다르면 충전이 불가합니다</div>
					</td>
				</tr>
                <tr>
					<td class="line2">은행계좌</td>
					<td class="line3">
						<div class="txt_area2">
							<input type="text" name="bank" id="bank" style="width:80px; height:18px" value="${bankName}"/>
						</div>
                        <div class="txt_area3">* 예: 케이뱅크</div>
					</td>
				</tr>
                <tr>
					<td class="line2">계좌번호</td>
					<td class="line3">
						<div class="txt_area2">
							<input type="text" name="accountno" id="accountno" style="width:80px; height:18px" value="${account_no}"/>
						</div>
                        <div class="txt_area3">* 예: 1234567890</div>
					</td>
				</tr>
                <tr>
					<td class="line2">입금계좌</td>
					<td class="line3">
                        <span id="depositAccount" class="txt_area3">
                        <a id="btn_ask" href="#" onclick="askAccount();"><img src="/frontend/thezone/img/request_btn.png" border="0" /></a>
                        </span>
					</td>
				</tr>
			</table>
		</div>
		<div class="btn"><a href="#" onClick="deposit();"><img src="/frontend/thezone/img/in_btn.png" border="0" /></a></div>
	</div>
</div>
</form>
</div>`;

        TINY.box.show({
            html: strHtml,
            width: 955,
            height: 680,
        });
    } else {
        showLoginAlert();
    }
}

function goWithdraw() {
    if (loginYN == "Y") {
        var strHtml = `
<div class="subcontent" id="withdraw">

<form method="post" id="fundFrm" name="fundFrm">
<input type="hidden" name="curmoney" id="curmoney" value="0">
<input type="hidden" name="tel1" id="tel1" value="000">
<input type="hidden" name="tel2" id="tel2" value="0000">
<input type="hidden" name="tel3" id="tel3" value="0000">
<input type="hidden" name="gamecategory" id="gamecategory" value="XI Gaming">


<div id="sub_box">
	<div id="sub_title"><img src="/frontend/thezone/img/paypay_title.png" /></div>
	<div id="data_box">
		<div class="txt"><span class="txt_style01"><b>- 24시간 출금이 언제든지 가능하나, 은행 및 금융감독원 점검시간 및 전산장애 시에는 지연될 수 있습니다.<br />- 또한 </span><span class="txt_style02">만원 단위로 출금 가능</span><span class="txt_style01">합니다.</b></span></div>
		<div class="dbox0">
			<table class="table100" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="line2">출금가능금액</td>
					<td class="line3"><div id="memmoney" style="position:;" class="txt_area">${currentBalance} 원</div></td>
				</tr>
				<tr>
					<td class="line2">출금하실 금액</td>
					<td class="line3">
						<div class="txt_area2">
							<input id="money" type="hidden" name="money" value="0">
							<input type="text" name="money1" id="money1" style="width:80px; height:18px" onchange="comma()" />
							<a href="javascript:money_count('50000');"><img src="/frontend/thezone/img/5man.gif" width="46" height="22" border="0" align="absmiddle" /></a>
							<a href="javascript:money_count('100000');"><img src="/frontend/thezone/img/10man.gif" width="46" height="22" border="0" align="absmiddle" /></a>
							<a href="javascript:money_count('300000');"><img src="/frontend/thezone/img/30man.gif" width="46" height="22" border="0" align="absmiddle" /></a>
							<a href="javascript:money_count('500000');"><img src="/frontend/thezone/img/50man.gif" width="46" height="22" border="0" align="absmiddle" /></a>
							<a href="javascript:money_count('1000000');"><img src="/frontend/thezone/img/100man.gif" width="46" height="22" border="0" align="absmiddle" /></a>
							<a href="javascript:money_count_hand();"><img src="/frontend/thezone/img/direct.gif" width="46" height="22" border="0" align="absmiddle" /></a>
						</div>
						<div class="txt_area"></div>
					</td>
				</tr>

				<tr>
					<td class="line2">출금자명</td>
					<td class="line3">
						<div class="txt_area2">
							<input type="text" name="name" id="name" style="width:80px; height:18px" value="${accountName}"/>
						</div>
					</td>
				</tr>
                <tr>
					<td class="line2">은행계좌</td>
					<td class="line3">
						<div class="txt_area2">
							<input type="text" name="bank" id="bank" style="width:80px; height:18px" value="${bankName}"/>
						</div>
                        <div class="txt_area3">* 예: 케이뱅크</div>
					</td>
				</tr>
                <tr>
					<td class="line2">계좌번호</td>
					<td class="line3">
						<div class="txt_area2">
							<input type="text" name="accountno" id="accountno" style="width:80px; height:18px" value="${account_no}"/>
						</div>
                        <div class="txt_area3">* 예: 1234567890</div>
					</td>
				</tr>
			
			</table>
		</div>
		<div class="btn"><a href="#" onClick="withdraw();"><img src="/frontend/thezone/img/out_btn.png" border="0" /></a></div>
	</div>
</div>
</form></div>`;

        TINY.box.show({
            html: strHtml,
            width: 955,
            height: 500,
        });
    } else {
        showLoginAlert();
    }
}

function goMove() {
    if (loginYN == "Y") {
        alert("현재 사용되지 않은 기능입니다.");
    } else {
        showLoginAlert();
    }
}

function goPoint() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: "/exchange/point.asp",
            width: 955,
            height: 780,
        });
    } else {
        showLoginAlert();
    }
}

function goCoupon() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: "/exchange/coupon.asp",
            width: 955,
            height: 780,
        });
    } else {
        showLoginAlert();
    }
}

function goHistory() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: "/exchange/history.asp",
            width: 955,
            height: 780,
        });
    } else {
        showLoginAlert();
    }
}

function goMemo() {
    if (loginYN == "Y") {
        var strHtml = `
        <div class="subcontent">
<div id="sub_box">
<div id="sub_title">
    <h1 class="sub-title">쪽지함</h1>
</div>
<div id="data_box" style="padding-top: 10px;">
    <div class="dbox1">
        <span style="color:white;">* 쪽지는 1일후에 자동 삭제 됩니다.</span>
        <table class="table100" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td colspan=3 align='center'>쪽지가 없습니다.</td>
                </tr>
        </table>
    </div>
</div>
</div></div>`;

        TINY.box.show({
            html: strHtml,
            width: 955,
            height: 780,
        });
    } else {
        showLoginAlert();
    }
}

function goCustomer() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: "/etc/customer.asp",
            width: 950,
            height: 700,
        });
    } else {
        showLoginAlert();
    }
}

function goMail() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: "/user/mypage_mail.asp",
            width: 850,
            height: 570,
        });
    } else {
        showLoginAlert();
    }
}

function goRank() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: "/user/mypage.asp?rank",
            width: 850,
            height: 570,
        });
    } else {
        showLoginAlert();
    }
}

function goIdSearch() {
    alert("아이디비번문의는 카톡ID: 추가 후 문의주세요");
}

function goMypage() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: "/profile/mypage",
            width: 955,
            height: 500,
        });
    } else {
        showLoginAlert();
    }
}

function goDealOut() {
    TINY.box.show({
        iframe: "/profile/dealout",
        width: 955,
        height: 500,
    });
}

function dealout() {
    var _token = $('#_token').val();
    var _dealsum = $('#money').val();

    $.ajax({
        type: 'POST',
        url: '/api/convert_deal_balance',
        data: { _token: _token, summ: _dealsum },
        cache: false,
        async: false,
        success: function (data) {
            if (data.error) {
                alert(data.msg);
                return;
            }
            alert('수익금이 보유금으로 전환되었습니다.');
            location.reload(true);
        },
        error: function (err, xhr) {
            alert(err.responseText);
        }
    });
}

function goJoin() {
    TINY.box.show({
        iframe: "/join",
        width: 955,
        height: 670,
    });
}

function goSign() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: "/member/sign.asp",
            width: 1060,
            height: 570,
        });
    } else {
        showLoginAlert();
    }
}

function goTerms() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: "/member/terms.asp",
            width: 1060,
            height: 570,
        });
    } else {
        showLoginAlert();
    }
}

function goLogin() {
    TINY.box.show({
        iframe: "/user/login.asp",
        width: 955,
        height: 300,
    });
}

function goLogout() {
    location.href = "/logout";
}

function goBoardList(type) {
    if (loginYN == "Y") {
        var strHtml = `
            <div class="subcontent">
	<div id="sub_box">
		<div id="sub_title">
			<img src="/frontend/thezone/img/${type}_title.png" />
		</div>
		<div id="data_box" style="padding-top: 10px;">
			<div class="dbox1">
				<div id="line_gray">
					<table class="table100_txtwhite" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="80" height="30" align="center">No.</td>
							<td width="3" align="center"><img src="/frontend/thezone/img/line.gif" /></td>
							<td align="center">제목</td>
							<td width="3" align="center"><img src="/frontend/thezone/img/line.gif" /></td>
							<td width="150" align="center">등록일</td>
						</tr>
					</table>
				</div>
				<table class="table100" border="0" cellspacing="0" cellpadding="0">
					
						<tr>
							<td colspan=5 align='center'>게시물이 없습니다.</td>
						</tr>
						
			</table>
			<table class="table100" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="line">
						<img src='/frontend/thezone/img/icon_prev01.png' border=0 align=absmiddle>&nbsp;<img src='/frontend/thezone/img/icon_next01.png' border=0 align=absmiddle>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>
            </div>`;

        TINY.box.show({
            html: strHtml,
            width: 955,
            height: 650,
        });
    } else {
        showLoginAlert();
    }
}

function goBoardView(type, num) {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: "/board/bview/" + type + ".asp?idx=" + num,
            width: 955,
            height: 650,
        });
    } else {
        showLoginAlert();
    }
}

function showLoginAlert() {
    alert("로그인 후 이용가능합니다.");
    $("#userid").focus();
}
function askAccount() {
    var accountname = $("#deposit #name").val();
    var money = $("#deposit #money").val();
    var _token = $('#_token').val();

    $("#deposit #btn_ask").hide();

    $.ajax({
        cache: false,
        url: "/api/depositAccount",
        type: "POST",
        data: { money: money, _token: _token, account:accountname },
        success: function(data) {
            //console.log(data);
            if (data.error) {
                $("#deposit #btn_ask").show();
                alert(data.msg);
                if (data.code == '001') {
                    location.reload(true);
                }
                else if (data.code == '002') {
                    $("#deposit #money").focus();
                }
                else if (data.code == '003') {
                    $("#deposit #name").focus();
                }
                return;
            }
            depositAccountRequested = true;
            $("#deposit #depositAccount").html(data.msg);
        }
    });
}

function deposit() {
    var accountname = $("#deposit #name").val();
    var bankname = $("#deposit #bank").val();
    var accountno = $("#deposit #accountno").val();
    var money = $("#deposit #money").val();

    if (accountname == "") {
        alert("입금자명을 입력해주세요.");
        $("#deposit #name").focus();
        return;
    }

    if (money < 30000) {
        alert("충전 최소금액은 30,000원 입니다.");
        return;
    }
    if (money % 10000 > 0) {
        alert("10,000원 단위로만 충전 가능합니다.");
        return;
    }

    $.ajax({
        type: "POST",
        url: "/api/addbalance",
        data: { accountName: accountname, bank:bankname, no:accountno, money: money },
        cache: false,
        async: false,
        success: function (data) {
            if (data.error) {
                alert(data.msg);
                if (data.code == "001") {
                    location.reload();
                } else if (data.code == "002") {
                    $("#deposit #name").focus();
                }
                return;
            }

            alert("충전 신청이 완료되었습니다.");
            // $("#deposit .btn3_2").click();
        },
        error: function (err, xhr) {
            alert(err.responseText);
        },
    });
}

function withdraw() {
    var accountname = $("#withdraw #name").val();
    var bankname = $("#withdraw #bank").val();
    var accountno = $("#withdraw #accountno").val();
    var money = $("#withdraw #money").val();
    if (money < 30000) {
        alert("환전 최소금액은 30,000원 입니다.");
        return;
    }
    if (money % 10000 > 0) {
        alert("10,000원 단위로만 환전 가능합니다.");
        return;
    }

    $.ajax({
        type: "POST",
        url: "/api/outbalance",
        data: { accountName: accountname, bank:bankname, no:accountno, money: money },
        cache: false,
        async: false,
        success: function (data) {
            if (data.error) {
                alert(data.msg);
                return;
            }

            alert("환전 신청이 완료되었습니다.");
            // location.reload();
        },
        error: function (err, xhr) {
            alert(err.responseText);
        },
    });
}

function updateMyInfo() {
    var cur_pwd = $("#cur_pwd").val();
    var new_pwd = $("#new_pwd").val();
    var new_pwd_confirm = $("#new_pwd_confirm").val();

    if (cur_pwd == "") {
        alert("비밀번호를 입력해주세요.");
        $("#cur_pwd").focus();
        return;
    }
    if (new_pwd == "") {
        alert("새 비밀번호가 입력되지 않았습니다.");
        $("#new_pwd").focus();
        return;
    }
    if (new_pwd_confirm == "") {
        alert("새 비밀번호 재입력을 해주세요.");
        $("#new_pwd_confirm").focus();
        return;
    }
    if (new_pwd != new_pwd_confirm) {
        alert("새비밀번호와 확인비밀번호가 일치하지 않습니다.");
        return;
    }

    $.ajax({
        type: "POST",
        url: "/api/updateinfo",
        data: { cur_pwd: cur_pwd, new_pwd: new_pwd },
        cache: false,
        async: false,
        success: function (data) {
            if (data.error) {
                alert(data.msg);
                if (data.code == "001") {
                    location.reload();
                } else if (data.code == "002") {
                    $("#cur_pwd").focus();
                } else if (data.code == "003") {
                    $("#new_pwd").focus();
                }
                return;
            }

            alert("비밀번호가 수정되었습니다.");

            goLogout();
        },
        error: function (err, xhr) {
            alert(err.responseText);
        },
    });
}


function readMessage(idx)
{
    if ($("#msgcontent"+idx).is(":visible"))
    {
        $("#msgcontent"+idx).hide();
    }
    else
    {
        $("#msgcontent"+idx).show();
    }

    $.ajax({
        url: "/api/readMsg",
        type: 'POST',
        data: { id: idx },
        dataType: 'html',
        headers: {},
        success: function(data) {},
        error: function(xhr, status, error) {},
        complete: function() {}
    });
}

function deleteMessage(idx)
{
    if (confirm('쪽지를 삭제하시겠습니까?\n삭제후 복구불가합니다.') == true) {
        $.ajax({
            url: "/api/deleteMsg",
            type: "POST",
            data: { id: idx },
            dataType: "html",
            success: function(t) {
                location.reload();
            }
        });
    }
}