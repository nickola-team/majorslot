
function commify(n) {
    var reg = /(^[+-]?\d+)(\d{3})/;   // 정규식
    n += '';                                       // 숫자를 문자열로 변환

    while (reg.test(n))
        n = n.replace(reg, '$1' + ',' + '$2');
    return n;
}
function easyput(val){
	if (val == "0"){
	$("#in_price").val(0);
	$("#check_price").val(0);
	}
	price	= $("#in_price").val();
	$("#in_price").val(parseInt(val)+parseInt(price));
	$("#check_price").val(commify(parseInt(val)+parseInt(price)));
}

function goSlot(title, category, isConst) {
    
        var formData = new FormData();
        formData.append("_token", $("#_token").val());
        formData.append("category", category);
    
        $.ajax({
            type: "POST",
            url: "/api/getgamelist",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            success: function (data) {
                if (data.error) {
                    alert(data.msg);
                    if (data.code == "001") {
                        location.reload();
                    }
                    return;
                }
    
                var strHtml = `
                <style>

                    body, td, p, input, button, textarea, select, .c1 {background:#000}
                    .main-img {
                        transition: 0.3s;
                        width: 240px;
                        height: 200px;
                        border: solid 3px #ccc;
                    }
                    td#slotchoice{
                                margin-top: 10px;
                                line-height: 1.6
                            }
                            #slotchoice .container{
                            
                                margin: 0 auto;
                                padding:0 10px;
                                height: 800px;
                            }
                    
                    
                    
                            #slotchoice ul.tabs{
                                margin: 0px;
                                padding: 0px;
                                list-style: none;
                                border-bottom: thin solid #999;
                                
                            }
                            #slotchoice ul.tabs li{
                                background: none;
                                color: #fff;
                                display: inline-block;
                                padding: 10px 15px;
                                font-size: 12px;
                                cursor: pointer;
                            }
                    
                            #slotchoice ul.tabs li.current{
                                background: #ededed;
                                color: #222;
                                font-weight: bold;
                                border-top: thin solid #999;
                                border-left: thin solid #999;
                                border-right: thin solid #999;
                            }
                    
                            #slotchoice .tab-content{
                                display: none;
                                background: #ededed;
                                padding: 15px;
                            }
                    
                            #slotchoice .tab-content.current{
                                display: inherit;
                            }
                            
                    #slotchoice div .line { 
                        border: 3px solid #ccc; 
                        color: #000;
                        float: left;
                        padding: 10px 12px;
                        margin: 10px; 
                        text-align: center;
                        font-size: 12px;
                        height: 150px;
                        display:inline-block;width:130px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
                    
                        
                        }
                    #slotchoice img{position:relative;left:-7px;}
                    .subLayerPop {height: 100%;};
                    </style>	
                    <div class="subLayerPop">
                	<div class="subBg"></div>
                        <div class="subWrap" style="background:transparent;height: 100%;">
                            <div class="subMain moneyOutWrap" style="padding-left:0px;height: 100%;">
                                <div class="subHeaderWrap">
                                </div>
                                <table class="subCont" style="width: 100%;height:100%;background:#fff">
                                    <tr>
                                        <td style="padding-left: 0px;" id="slotchoice">
                                        <div style="height:90px;text-align:center;line-height:90px;">
                                        <p>
                                        <font style="color:#ffff00;font-weight:bold;font-size:20px">${title}</font>
                                        </p>
                                        </div>
                                        <div class="container">
                                            <div>	 
                                            <table style="width:100%">
                                            <tbody>`;

                if (data.games.length > 0) {
                    for (var i = 0; i < data.games.length; i++) {
                        if (i % 6 == 0)
                        {
                            strHtml += `<tr>`;
                        }
                        strHtml += `<td style="width:16%" align="center">`;
                        if (data.games[i].provider)
                        {
                            strHtml += `
                            <a onClick="startGameByProvider('${data.games[i].provider}', '${data.games[i].gamecode}');" style="cursor: pointer;">`;
                            if (data.games[i].icon)
                            {
                                strHtml += `<img class="main-img" src="${data.games[i].icon}" title="${data.games[i].title}" >`;
                            }
                            else {
                                strHtml += `<img class="main-img" src="/frontend/Default/ico/${data.games[i].provider}/${data.games[i].gamecode}_${data.games[i].name}.jpg" title="${data.games[i].title}">`;
                            }
                            strHtml += `
                            <br>
							 <font style="color:#ffff00;font-weight:bold;font-size:14px">${data.games[i].title}</font>`;
                        }
                        else
                        {
                            strHtml += `
                            <a onClick="startGame('${data.games[i].name}');" style="cursor: pointer;">
                                <img class="main-img" src="/frontend/Default/ico/${data.games[i].name}.jpg" title="${data.games[i].title}">
                                <br>
                                <font style="color:#ffff00;font-weight:bold;font-size:14px">${data.games[i].title}</font>`;
                        }
                        strHtml += `</td>`;
                        if (i % 6 == 5)
                        {
                            strHtml += `</tr>`;
                        }
                    }
                } else {
                }
                strHtml += `</tbody></table>
                                </div>
                            </div>					
                            </td>
                        </tr>
                    </table>
                    <div class="btnClose" style="top:50px;right:-100px;"><a href="./"><img src="./images/btn_close.png" alt=""></a></div>
                </div>
                </div>`;

                TINY.box.show({
                    html: strHtml,
                    width: 1600,
                    height: 800
                });
            },
            error: function (err, xhr) {
                alert(err.responseText);
            }
        });

}

function startGame(gamename) {
    window.open("/game/" + gamename, gamename, "width=1280, height=720, left=100, top=50");
}

function startGameByProvider(provider, gamecode) {
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
            window.open(data.data.url, "game", "width=1280, height=720, left=100, top=50");
        }
        });
        
}

function depositWindow()
{
    strHtml = `
        <div class="sub-modal">
        <div class="sub-content">
            <h2> 입금신청 ( DEPOSIT )</h2>
        
            <div class="info">
            </div>
        
            <div class="sub_table money">
                <form name="fwrite" method="post" action="cash_charge_proc.php" enctype="multipart/form-data">
                <table>
                    <tr>
                        <td>입금자명</td>
                        <td>
                            <input name="name" type="text" value="">&nbsp;<p>*입금자 성함을 기입해주세요.</p>
                        </td>
                    </tr>
                    <tr>
                        <td>입금금액</td>
                        <td>
                            <input type="hidden" name="amount" id="amount" value="0" onkeyup="this.value=c_currency(this.value);" readonly />
                            <input type="hidden" name="in_price" id="in_price" value="0" Reg Hname="입금액" Patt="Num" />
                            <input type="text" name="ormoney" id="check_price" Req Hname="입금금액" onkeyup="commify(this.value);this.value=c_currency(this.value);mdisple_html(this.value);" onchange="commify(this.value)"  value=""/>
                            <p>* 최소 입금액은 50,000원입니다</p>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <div class="btn" onclick="easyput('10000');">1만원</div>
                            <div class="btn" onclick="easyput('50000');">5만원</div>
                            <div class="btn" onclick="easyput('100000');">10만원</div>
                            <div class="btn" onclick="easyput('500000');">50만원</div>
                            <div class="btn" onclick="easyput('1000000');">100만원</div>
                            <div class="btn" onclick="easyput('2000000');">200만원</div>
                            <div class="btn" onclick="easyput('0');">정정</div>
                        </td>
                    </tr>
                    <tr>
                        <td>입금계좌번호</td>
                        <td>
                            <a href="javascript:void(0);" onClick="javascript:window.open('acc.php','exc1111','width=500,height=200,top=100,left=300,scrollbars=yes,resizable=no')" style="color:#ffff00;font-weight:bold;font-size:16px">[계좌문의]</a>
                        </td>
                    </tr>
        
                </table>
                </form>
            </div>
            <div class="submit_wrap">
                <div class="btn" onclick="fwrite.submit();document.getElementById('a1').disabled='true';">입금신청하기</div>
            </div>
        
                                                            <br/><br/>
                                    <table width="100%" cellpadding=0 cellspacing=1>
                                    <colgroup width=20%>
                                    <colgroup width=30%>
                                    <colgroup width=30%>
                                    <colgroup width=20%>
                                    <colgroup>
                                    <tr class='bgcol1 bold col1 ht center'>
                                        <td style="background-color: rgb(22, 22, 22);height: 41px;color:#ffffcc;text-align:center;border-bottom:solid 1px #ffffcc;border-top:solid 1px #ffffcc;font-weight:bold;font-size:14px">상태</td>
                                        <td style="background-color: rgb(22, 22, 22);height: 41px;color:#ffffcc;text-align:center;border-bottom:solid 1px #ffffcc;border-top:solid 1px #ffffcc;font-weight:bold;font-size:14px">신청금액</td>
                                        <td style="background-color: rgb(22, 22, 22);height: 41px;color:#ffffcc;text-align:center;border-bottom:solid 1px #ffffcc;border-top:solid 1px #ffffcc;font-weight:bold;font-size:14px">신청날짜</td>
                                        <td style="background-color: rgb(22, 22, 22);height: 41px;color:#ffffcc;text-align:center;border-bottom:solid 1px #ffffcc;border-top:solid 1px #ffffcc;font-weight:bold;font-size:14px">삭제</td>
                                    </tr>	
                                                                </table>
        </div>
        </div>
        
        `;
    TINY.box.show({
        html: strHtml,
        width: 1200,
        height: 600
    });
}


function withdrawWindow()
{
    strHtml = `
        <div class="sub-modal">
        <div class="sub-content">
            <h2> 출금신청 ( WITHDRAW )</h2>

            <div class="info">
                
            </div>
            <div class="sub_table money">
                <form name="fwrite" method="post" action="cash_draw_proc.php" enctype="multipart/form-data">
                <table>
                    <tr>
                        <td>회원정보</td>
                        <td>
                            <input name="name" type="text" value="aaaa01"/>
                        </td>
                    </tr>
                    <tr>
                        <td>출금금액</td>
                        <td>
                            <input type="hidden" name="amount" id="amount" value="0" onkeyup="this.value=c_currency(this.value);" readonly />
                            <input type="hidden" name="in_price" id="in_price" value="0" Reg Hname="입금액" Patt="Num" />
                            <input type="text" name="ormoney" id="check_price" Req Hname="입금금액" onkeyup="commify(this.value);this.value=c_currency(this.value);mdisple_html(this.value);" onchange="commify(this.value)"  value=""/>
                            <p>* 최소 출금액은 50,000원입니다</p>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <div class="btn" onclick="easyput('10000');">1만원</div>
                            <div class="btn" onclick="easyput('50000');">5만원</div>
                            <div class="btn" onclick="easyput('100000');">10만원</div>
                            <div class="btn" onclick="easyput('500000');">50만원</div>
                            <div class="btn" onclick="easyput('1000000');">100만원</div>
                            <div class="btn" onclick="easyput('2000000');">200만원</div>
                            <div class="btn" onclick="easyput('0');">정정</div>
                        </td>
                    </tr>
                    <tr>
                        <td>예금주명</td>
                        <td>
                            <input type="text" name="name" required style="IME-MODE:inactive" value="" Req Hname="예금주">
                        </td>
                    </tr>
                    <tr>
                        <td>은행</td>
                        <td>
                            <input type="text" name="name2" value="" required style="IME-MODE:inactive" Req Hname="출금은행">
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>입금받을계좌</td>
                        <td>
                            <input type="text" name="name3" value="" required style="IME-MODE:inactive" Req Hname="계좌번호">
                            <p>* 입금시 등록된 계좌번호로만 출금이 가능합니다.</p>
                        </td>
                    </tr>
                </table>
                </form>
            </div>
            <div class="submit_wrap">
                <div class="btn" onclick="fwrite.submit();;document.getElementById('a1').disabled='true';">출금신청하기</div>
            </div>
                                                            <br/><br/>
                                    <table width="100%" cellpadding=0 cellspacing=1>
                                    <colgroup width=20%>
                                    <colgroup width=30%>
                                    <colgroup width=30%>
                                    <colgroup width=20%>
                                    <colgroup>
                                    <tr class='bgcol1 bold col1 ht center'>
                                        <td style="background-color: rgb(22, 22, 22);height: 41px;color:#ffffcc;text-align:center;border-bottom:solid 1px #ffffcc;border-top:solid 1px #ffffcc;font-weight:bold;font-size:14px">상태</td>
                                        <td style="background-color: rgb(22, 22, 22);height: 41px;color:#ffffcc;text-align:center;border-bottom:solid 1px #ffffcc;border-top:solid 1px #ffffcc;font-weight:bold;font-size:14px">신청금액</td>
                                        <td style="background-color: rgb(22, 22, 22);height: 41px;color:#ffffcc;text-align:center;border-bottom:solid 1px #ffffcc;border-top:solid 1px #ffffcc;font-weight:bold;font-size:14px">신청날짜</td>
                                        <td style="background-color: rgb(22, 22, 22);height: 41px;color:#ffffcc;text-align:center;border-bottom:solid 1px #ffffcc;border-top:solid 1px #ffffcc;font-weight:bold;font-size:14px">삭제</td>
                                    </tr>	
                                                                </table>
        </div>
        </div>
        
        `;
    TINY.box.show({
        html: strHtml,
        width: 1200,
        height: 600
    });
}