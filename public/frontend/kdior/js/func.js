

function setComma(str) { 
        str = ""+str+""; 
        var retValue = ""; 
        for(i=0; i<str.length; i++) 
        { 
               if(i > 0 && (i%3)==0) { 
                       retValue = str.charAt(str.length - i -1) + "," + retValue; 
                 } else { 
                        retValue = str.charAt(str.length - i -1) + retValue; 
                } 
        } 
        return retValue ; 
} 

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function chkNum(checkStr) {
    if(checkStr == "") return false;
	for(i = 0;i < checkStr.length;i++) {
		ch = checkStr.charAt(i);
		if(!(ch >= "0" && ch <= "9"))
			return false;
	}
	return true;
}

// ���������� üũ..
function chkEng(checkStr) {
	var str_space = /s/;

    if(checkStr == "") return false;
	for(i = 0;i < checkStr.length;i++) {
		ch = checkStr.charAt(i);
		if(!((ch >= "A" && ch <= "Z") || (ch >= "a" && ch <= "z") || ch == "." || ch == "," || ch == ' '))
			return false;
		
	}
	return true;
}


// ���ڿ� ���������� üũ..
function chkNumEng(checkStr) {
    if(checkStr == "") return false;
	for(i = 0;i < checkStr.length;i++) {
		ch = checkStr.charAt(i);
		if(!((ch >= "0" && ch <= "9") || (ch >= "A" && ch <= "Z") || (ch >= "a" && ch <= "z")))
			return false;
	}
	return true;
}

// ���ڿ� �ĸ� üũ..
function chkNumEngComma(checkStr) {
    if(checkStr == "") return false;
	for(i = 0;i < checkStr.length;i++) {
		ch = checkStr.charAt(i);
		if(!((ch >= "0" && ch <= "9") || ch == "."))
			return false;
	}
	return true;
}

// ���̵� üũ
function chkIDStr(checkStr) {
    if(checkStr == "") return false;
	for(i = 0;i < checkStr.length;i++) {
		ch = checkStr.charAt(i);
		if(!((ch >= "0" && ch <= "9") || (ch >= "A" && ch <= "Z") || (ch >= "a" && ch <= "z") || ch == "-" || ch == "_" || ch == "."))
			return false;
	}
	return true;
}

// ���� �̸� üũ
function chkNameENG(checkStr) {
    if(checkStr == "") return false;
	for(i = 0;i < checkStr.length;i++) {
		ch = checkStr.charAt(i);
		if(!
			(
				(ch >= "A" && ch <= "Z") || 
				(ch >= "a" && ch <= "z")
			)
		)
			return false;
	}
	return true;
}

function email_check(emailad) {
	var exclude=/[^@\-\.\w]|^[_@\.\-]|[\._\-]{2}|[@\.]{2}| [^@]*\1/;
	var check=/@[\w\-]+\./;
	var checkend=/\.[a-zA-Z]{2,3}$/;
	var parse_email = emailad.split("@");
	if ( emailad ) {
		if(((emailad.search(exclude) != -1)||(emailad.search(check)) == -1)||(emailad.search(checkend) == -1)){
   	    	   return false;
		} else {
			return true;
		}
	}
}

function newPopup(width, height, url, popName) {

	var width = width;
	var height = height;
	var topPosition = (screen.height - height) / 2;
	var leftPosition = (screen.width - width) / 2;
	
	window.open(url,popName,'top='+topPosition+', left='+leftPosition+', width='+width+', height='+height+', statusbar=no,scrollbars=auto,toolbar=no,resizable=no')
}

function newItemBoardPopup(){
	newPopup(317, 271, '/EToTo_Admin/member/new_item_board_popup.php', "newItemPopup");
}
function GetCookie(sName) {
  var aCookie = document.cookie.split("; ");

  for (var i=0; i < aCookie.length; i++) {
    var aCrumb = aCookie[i].split("=");

    if (sName == aCrumb[0]) {
        return unescape(aCrumb[1]);
    }
  }
  return null;
}



// ����,���ڸ� ����
function ch(obj){
	var str = obj.src;               
	if(str.indexOf('_on.gif') < 0){         
	ss = str.substr(0, str.indexOf('.gif'))      
	obj.src = ss + "_on.gif";                         
	}else{                                      
	ss = str.substr(0, str.indexOf('_on.gif'))
	obj.src = ss + ".gif";
	}
}

function EnNumCheck(word)
{
	  var str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
	  for (i=0; i< word.length; i++)
	  {
		idcheck = word.charAt(i);
		for (j = 0 ;  j < str.length ; j++)
		{
		  if (idcheck == str.charAt(j)) break;
		  if (j+1 == str.length)
		  {
			return false;
		  }
		}
	  }
	  return true;
}

function NumCommaCheck(word)
{
	var str = ",1234567890";
	for (i=0; i< word.length; i++)
	  {
		idcheck = word.charAt(i);
		for (j = 0 ;  j < str.length ; j++)
		{
		  if (idcheck == str.charAt(j)) break;
		  if (j+1 == str.length)
		  {
			return false;
		  }
		}
	  }
	  return true;
}

function NumDash(word)
{
	var str = "-1234567890";
	for (i=0; i< word.length; i++)
	  {
		idcheck = word.charAt(i);
		for (j = 0 ;  j < str.length ; j++)
		{
		  if (idcheck == str.charAt(j)) break;
		  if (j+1 == str.length)
		  {
			return false;
		  }
		}
	  }
	  return true;
}

function NumCheck(word)
{
	var str = "1234567890";
	  for (i=0; i< word.length; i++) {
			idcheck = word.charAt(i);
			for (j = 0 ;  j < str.length ; j++)
			{
		  	if (idcheck == str.charAt(j)) break;
		  	if (j+1 == str.length)
		  	{
					return false;
		  	}
			}
	  }
	  return true;
}


//	��ȭ��ȣ üũ[���ڷθ� �����ǰ� 3 ~ 4���� ���̸� ������ ��]
function IsPhoneChek(strNumber)
{
	var regExpr = /^[0-9]{3,4}$/;
	
	if ( regExpr.test( strNumber ) )
		return true;
	else
		return false;
}


function keyCheck(){
  if(event.keyCode < 48 || event.keyCode > 57)// 0~9�� ASCII�ڵ��� �� ����
  {
   alert("���ڸ� ����Ҽ� �ֽ��ϴ�");
   event.returnValue= false ;//���ڰ� �ƴѰ�� �Է��� �ȵ�   
  }
}



function loginChk() {
    var id = $('#frmLogin #IU_ID').val();
    var pass = $('#frmLogin #IU_PW').val();
    if(!id || !pass){
        alert('아이디와 비밀번호를 입력하세요.');
    } else {

        var formData = new FormData();
        formData.append("_token", $("#_token").val());
        formData.append("username", id);
        formData.append("password", pass);

        $.ajax({
            type: "POST",
            url: "/api/login",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            success: function (data, status) {
                if (data.error) {
                    alert(data.msg);
                }

                location.reload();
            },
            error: function (err, xhr) {
            }
        });
    }
}


function joinChk() {
	var frm = document.join_frm;
	var pattern1 = /[0-9]/;
	var pattern2 = /[a-zA-Z]/;
	var pattern3 = /[~!@\#$%<>^&*]/;     // 원하는 특수문자 추가 제거

	if (frm.ChkID.value != 1) {
		alert(" 아이디 중복체크를 해주세요.");
		frm.IU_ID.focus();
		return ;
	}
	if ((frm.IU_ID.value.length == 0) || (frm.IU_ID.value.length < 2) || (frm.IU_ID.value.length > 12)) {
		alert(" 사용하실 아이디를 정확히 넣어주세요.\n아이디는 2~12까지만 입력이 가능합니다.");
		frm.IU_ID.focus();
		return ;
	}
	
	// 비밀번호 체크
	// if(!pattern1.test(frm.IU_PW.value)||!pattern2.test(frm.IU_PW.value)||!pattern3.test(frm.IU_PW.value)||frm.IU_PW.value.length<8||frm.IU_PW.value.length>50){
	// 	alert("영문+숫자+특수기호 8자리 이상으로 구성하여야 합니다.");
	// 	frm.IU_PW.select();
	// 	frm.IU_PW.focus();
	// 	return false;
	// } 

	if (frm.IU_PW.value != frm.IU_PW1.value) {
		alert(" 비밀번호와 비밀번호 확인이 일치하지 않습니다.");
		frm.IU_PW1.value = "";
		frm.IU_PW1.focus();
		return ;
	}
	if (frm.IU_Mobile.value == "") {
		alert("휴대폰번호를 입력해 주세요.");
		frm.IU_Mobile.focus();
		return ;
	}
	if (frm.recom_id.value == "") {
		alert("추천인 아이디를 입력해 주세요");
		frm.recom_id.focus();
		return ;
	}
	if ((frm.IU_BankOwner.value == "") || (frm.IU_BankOwner.value.length < 2)) {
		alert("예금주를  정확하게 입력해주세요.");
		frm.IU_BankOwner.focus();
		return ;
	}
	if ((frm.IU_BankNum.value == "") || (frm.IU_BankNum.value.length < 10)) {
		alert("계좌번호를 정확하게 입력해주세요.");
		frm.IU_BankNum.focus();
		return ;
	}
	if (NumDash(frm.IU_BankNum.value) == false) {
		alert("계좌번호는 숫자와 '-' 만을 사용해서 입력해주세요.");
		frm.IU_BankNum.value = "";
		frm.IU_BankNum.focus();
		return ;
	}

	$.ajax({
		type: 'POST',
		url: "/api/join",
		data: {	username: frm.IU_ID.value,
				password: frm.IU_PW.value,
				tel1: frm.IU_Mobile.value,
				tel2: '',
				tel3: '',
				bank_name: frm.IU_BankName.value,
				recommender: frm.IU_BankOwner.value,
				account_no: frm.IU_BankNum.value,
				friend: frm.recom_id.value
			},
		cache: false,
		async: false,
		success: function (data) {
			if (data.error) {
				alert(data.msg);
				return;
			}
			else{
				alert(data.msg);
				window.location.href = "/";
			}

		},
		error: function (err, xhr) {
			alert(err.responseText);
		}
	});

}

function idDblChk() {
	var frm = document.join_frm;
	if (frm.IU_ID.value == "") {
		alert("중복체크하실 아이디를 적어주세요.");
		frm.IU_ID.focus();
		return ;
	}
	else if ((frm.IU_ID.value.length == 0) || (frm.IU_ID.value.length < 2) || (frm.IU_ID.value.length > 12)) {
		alert(" 사용하실 아이디를 정확히 넣어주세요.\n아이디는 2~12까지만 입력이 가능합니다.");
		frm.IU_ID.focus();
		return ;
	}
	else {
		$.ajax({
			type: 'POST',
			url: "/api/checkid",
			data: {	id: frm.IU_ID.value,},
			cache: false,
			async: false,
			success: function (data) {
				if (data.error) {
					alert('오류가 발생했습니다. 다시 시도해주세요');
					return;
				}
				else{
					if (data.ok==0)
					{
						alert('이미 사용된 아이디입니다');
					}
					else
					{
						alert('사용할수 있는 아이디입니다');
						frm.ChkID.value = 1;
					}
				}

			},
			error: function (err, xhr) {
				alert(err.responseText);
			}
		});
	}
}


function moneyadd(str) {
	var str = str.replace(/,/gi, '');

	if (str == '0') {
		document.charge_frm.IC_Amount.value = '';
	}else {
		if(document.charge_frm.IC_Amount.value == '') {
			document.charge_frm.IC_Amount.value = 0;
		}
		var a = document.charge_frm.IC_Amount.value;
		a = a.replace(/,/gi, '');
		a = parseInt(a) + parseInt(str);
		document.charge_frm.IC_Amount.value = setComma(a);
	}
	window.focus();
}

function moneyadd2(str) {
	var str = str.replace(/,/gi, '');
	if (str == '0') {
		document.exchange_frm.IE_Amount.value = '';
		document.exchange_frm.IE_Amount.focus();
		return;
	}else {
		if(document.exchange_frm.IE_Amount.value == '') {
			document.exchange_frm.IE_Amount.value = 0;
		}
		var a = document.exchange_frm.IE_Amount.value;
		a = a.replace(/,/gi, '');
		a = parseInt(a) + parseInt(str);
		document.exchange_frm.IE_Amount.value = setComma(a);
	}
	window.focus();
}

function depositChk() {
	var frm = document.charge_frm;
	if (frm.IC_Amount.value == "") {
		alert("충전요청 금액을 입력해주세요.");
		frm.IC_Amount.focus();
		return;
	}
	if (parseInt(frm.IC_Amount.value.replace(/,/gi, '')) < 10000) {
		alert("충전 요청 금액은 10,000원 이상 입력해주세요.");
		frm.IC_Amount.value = "";
		frm.IC_Amount.focus();
		return;
	}
	$.ajax({
        type: "POST",
        url: "/api/addbalance",
        data: {money: frm.IC_Amount.value },
        cache: false,
        async: false,
        success: function (data) {
            if (data.error) {
                alert(data.msg);
                
                return;
            }

            alert("신청완료 되었습니다.");
            window.location.href = "/";
        },
        error: function (err, xhr) {
            alert(err.responseText);
        },
    });
}

function requestAccount(){
    $.ajax({
        type: "POST",
        url: "/api/depositAccount",
        data: null,
        cache: false,
        async: false,
        success: function (data) {
            if (data.error) {
                alert(data.msg);
                return;
            }
            if (data.url != null)
            {
                var leftPosition, topPosition;
                width = 600;
                height = 1000;
                leftPosition = (window.screen.width / 2) - ((width / 2) + 10);
                topPosition = (window.screen.height / 2) - ((height / 2) + 50);
                wndGame = window.open(data.url, "Deposit",
                "status=no,height=" + height + ",width=" + width + ",resizable=yes,left="
                + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY="
                + topPosition + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no");
            }
            else
            {
                alert(data.msg);
            }
        },
        error: function (err, xhr) {
            alert(err.responseText);
        },
    });

}

function withdrawChk() {
	var f = document.exchange_frm;

	if (f.IE_Amount.value.split(" ").join("")== "") {
		alert("희망하시는 환전금액을 입력하여 주십시오 ! ");
		f.IE_Amount.value = "";
		f.IE_Amount.focus();
		return;
	}
	
    var money = f.IE_Amount.value;

    if(money % 10000 > 0){
        alert('만 단위로 신청가능합니다.');
        return;
    }

    $.ajax({
        type: "POST",
        url: "/api/outbalance",
        data: { money: money  },
        cache: false,
        async: false,
        success: function (data) {
            if (data.error) {
                alert(data.msg);
                return;
            }

            alert("신청완료 되었습니다.");
            window.location.href = "/";
        },
        error: function (err, xhr) {
            alert(err.responseText);
        },
    });
}

function showBoard(objId) {
	dis = document.getElementById(objId).style.display == "none" ? "table-row" : "none";
	document.getElementById(objId).style.display = dis;
}
function showMsg(objId) {
	dis = document.getElementById("msg" + objId).style.display == "none" ? "table-row" : "none";
	document.getElementById("msg" + objId).style.display = dis;
	$.post('/api/readMsg',{id : objId},function(data){
    }); 
}


function compExchange(){
    if(!confirm('가지고 계신 콤프를 캐쉬로 전환 하시겠습니까?')){
        return;
    }

    $.ajax({
        type: "POST",
        url: "/api/convert_deal_balance",
        data: null,
        cache: false,
        async: false,
        success: function (data) {
            if (data.error) {
                alert(data.msg);
                return;
            }

            alert("콤프가 전환되었습니다.");
            document.location.reload();
        },
        error: function (err, xhr) {
            alert(err.responseText);
        },
    });
}


function FreAdd(){

	var f = document.FreContent;
	if (f.BC_TITLE.value.split(" ").join("") == "") { alert("\n 글제목을 입력하세요. \n"); f.BC_TITLE.value = ""; f.BC_TITLE.focus(); return false; }

	if(f.BC_CONTENT.value.split(" ").join("") == ""){
		alert('내용을 입력하여 주십시오 !  ');
		f.BC_CONTENT.value = "";
		f.BC_CONTENT.focus();
		return;
	}

    $.post('/api/writeMsg', {
        title : f.BC_TITLE.value, 
        content : f.BC_CONTENT.value, 
    },function(data){
        if(data.error == false){
            alert('저장 되었습니다');
			location.reload();			
			
        } else {
            alert(data.msg);
        }
    });
}

function support_detail() {
	$.post('/api/messages', null, function(data){
        if(data.error == false){
			var html = `<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
						<td width="15%" class="list_title1">번호</td>
						<td class="list_title1">제목</td>
						<td class="list_title1">작성시간</td>
						<td class="list_title1">읽은시간</td>
						<td width="12%" class="list_title1">타입</td>
					</tr>`;
			if (data.data.length > 0) {
				for (var i = 0; i < data.data.length; i++) {
					date = new Date(data.data[i].created_at);
					if (data.data[i].read_at == null)
					{
						read = '읽지 않음';
					}
					else
					{
						date1 = new Date(data.data[i].read_at);
						read = date1.toLocaleString();
					}
					type = (data.user_id!=data.data[i].writer_id)?'수신':'발신';
					html += `<tr>
						<td class="list_notice1">${i+1}</td>
						<td class="list_notice2"> <a href="#" onclick="showMsg('${data.data[i].id}')">${data.data[i].title}</a></td>
						<td class="list_notice1">${date.toLocaleString()}</td>
						<td class="list_notice1">${read}</td>
						<td class="list_notice1">${type}</td>
						</tr>
						<tr id="msg${data.data[i].id}" style="display:none;">
						<td class="list_notice1"></td>
						<td colspan="4" class="list_notice2">${data.data[i].content}</td>
						</tr>`;
				}
				
			}
			html += `</table>`;
			$("#sub_pop6_content").html(html);
            
			
        } else {
            alert(data.msg);
        }
    });
}

function deposit_detail() {
	$.post('/api/inouthistory', {type : 'add'}, function(data){
        if(data.error == false){
			var html = `<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="10%" class="list_title1">번호</td>
							<td width="10%" class="list_title1">충전금액</td>
							<td width="10%" class="list_title1">신청날짜</td>
							<td width="10%" class="list_title1">상태</td>
						</tr>`;
			if (data.data.length > 0) {
				status_name = {
					0 : '대기',
					1 : '완료',
					2 : '취소'
				 };
				for (var i = 0; i < data.data.length; i++) {
					date = new Date(data.data[i].created_at);
					html += `<tr>
						<td class="list_notice1">${i+1}</td>
						<td class="list_notice1">${parseInt(data.data[i].sum).toLocaleString()}원</td>
						<td class="list_notice1">${date.toLocaleString()}</td>
						<td class="list_notice1">${status_name[data.data[i].status]}</td>
						</tr>
						`;
				}
				
			}
			html += `</table>`;
			$("#sub_pop8_content").html(html);
			
        } else {
            alert(data.msg);
        }
    });
}

function withdraw_detail() {
	$.post('/api/inouthistory', {type : 'out'}, function(data){
        if(data.error == false){
			var html = `<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="10%" class="list_title1">번호</td>
							<td width="10%" class="list_title1">환전금액</td>
							<td width="10%" class="list_title1">신청날짜</td>
							<td width="10%" class="list_title1">상태</td>
						</tr>`;
			if (data.data.length > 0) {
				status_name = {
					0 : '대기',
					1 : '완료',
					2 : '취소'
				 };
				for (var i = 0; i < data.data.length; i++) {
					date = new Date(data.data[i].created_at);
					html += `<tr>
						<td class="list_notice1">${i+1}</td>
						<td class="list_notice1">${parseInt(data.data[i].sum).toLocaleString()}원</td>
						<td class="list_notice1">${date.toLocaleString()}</td>
						<td class="list_notice1">${status_name[data.data[i].status]}</td>
						</tr>
						`;
				}
				
			}
			html += `</table>`;
			$("#sub_pop9_content").html(html);
			
        } else {
            alert(data.msg);
        }
    });
}

function setCookie( name, value, expiredays ) {
	var todayDate = new Date();
	todayDate.setDate( todayDate.getDate() + expiredays );
	document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
}

function closeWin(name, check) {
	if (check=='Y' ){
		setCookie( name, "done" , 1 );
	}
	document.getElementById(name).style.visibility = "hidden";
}

function slotGame(category, title){
	$.ajax({
		type: "POST",
		url: "/api/getgamelist",
		data: {category : category},
		cache: false,
		async: true,
		success: function (data, status) {
			if (data.error) {
				alert(data.msg);
				return;
			}
			if (data.games.length > 0) {
				var htmldoc = `<ul class="gamelist">`;
				for (i=0;i<data.games.length;i++)
				{
					if (data.games[i].provider)
					{
						htmldoc += `<li><a href="#" onMouseOver="show_over(this);" onMouseOut="show_out(this);" onclick="startGameByProvider('${data.games[i].provider}', '${data.games[i].gamecode}');"><img src="${data.games[i].icon}"><img src="${data.games[i].icon}" class="mouseover3" style="display:none;"><br>${data.games[i].title}</a></li>`;
					}
					else
					{
						htmldoc += `<li><a href="#" onMouseOver="show_over(this);" onMouseOut="show_out(this);" onclick="startGameByProvider(null, '${data.games[i].name}');"><img src="/frontend/Default/ico/${data.games[i].name}.jpg" ><img src="/frontend/Default/ico/${data.games[i].name}.jpg" class="mouseover3" style="display:none;"><br>${data.games[i].title}</a></li>`;
					}
				}
				htmldoc += `</ul>`;
				$('#pop1_title').html(title);
				$('#pop1_content').html(htmldoc);
			}
			else
			{
				 alert('게임이 없습니다');
			}
		   }
	});
}

function casinoGameStart(category){
 
	$.ajax({
	   type: "POST",
	   url: "/api/getgamelist",
	   data: {category : category},
	   cache: false,
	   async: true,
	   success: function (data, status) {
		   if (data.error) {
			   alert(data.msg);
			   return;
		   }
		   if (data.games.length > 0) {
			 startGameByProvider(data.games[0].provider, data.games[0].gamecode);
		   }
		   else
		   {
				alert('게임실행 오류');
		   }
		  }
	   });
 }

function startGameByProvider(provider, gamecode,max = false) {
	var formData = new FormData();
	formData.append("provider", provider);
	formData.append("gamecode", gamecode);
	$.ajax({
	type: "POST",
	url: "/api/getgamelink",
	data: formData,
	processData: false,
	contentType: false,
	cache: false,
	async: false,
	success: function (data) {
		if (data.error) {
			alert(data.msg);
			return;
		}
		if (max)
       {
         window.open(data.data.url, "game", "width=" + screen.width + ", height=" + screen.height + ", left=100, top=50");
       }else{
         window.open(data.data.url, "game", "width=1280, height=720, left=100, top=50");
       }
	}
	});
	
 }