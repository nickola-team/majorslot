function writeObject(Ftrans, wid, hei) {
    mainbody =
        "<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0' id='" +
        Ftrans +
        "'  width='" +
        wid +
        "' height='" +
        hei +
        "'>";
    mainbody += "<param name='movie' value= '" + Ftrans + "'>";
    mainbody += "<param name='quality' value='high'>";
    mainbody += "<param name='wmode' value='transparent'>";
    mainbody += "<param name='menu' value='false'>";
    mainbody += "<param name='allowScriptAccess' value='sameDomain'>";
    mainbody +=
        "<embed src='" +
        Ftrans +
        "' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash' width='" +
        wid +
        "' height='" +
        hei +
        "'  wmode='transparent'></embed>";
    mainbody += "</object>";
    document.write(mainbody);
    return;
}
function popClose() {
    $(".tbox").hide();
}
// function loginSubmit(frm) {
//     if (frm.userid.value == "") {
//         alert("로그인 아이디를 입력해 주세요");
//         frm.userid.focus();
//         return;
//     }
//     if (frm.password.value == "") {
//         alert("비밀번호를 입력해 주세요");
//         frm.password.focus();
//         return;
//     }
//     frm.action = "user/login/index.html";
//     frm.submit();
// }
function KeyCapEvent_GO(type) {
    if (event.keyCode == 13) {
        loginSubmit(type);
    }
}
function Space_chk(chk_val) {
    var val_len;
    var spc = 0;
    val_len = chk_val.length;
    if (val_len == 0) {
        return true;
    } else {
        for (i = 0; i < val_len; i++) {
            if (chk_val.charAt(i) == " ") {
                return true;
            }
        }
    }
    return false;
}
function isChk(str) {
    var chkStr = str;
    var re = /(^([a-z0-9]+)([a-z0-9_]+$))/;
    if (re.test(chkStr)) {
        return false;
    } else {
        return true;
    }
}
function isNums(strNums) {
    var id_ck = strNums;
    if (isNaN(id_ck) == false) {
        return true;
    } else {
        return false;
    }
}
function isAccNum(str) {
    var strVal = "0123456789";
    for (i = 0; i < str.length; i++) {
        ch = str.charAt(i);
        for (j = 0; j < strVal.length; j++) if (ch == strVal.charAt(j)) break;
        if (j == strVal.length) return false;
    }
    return true;
}
function kor_eng_chk(instr) {
    for (kk = 0; kk < instr.length; kk++) {
        mmstr = instr.substr(kk, 1).charCodeAt(0);
        if (
            mmstr < 65 ||
            (mmstr > 90 && mmstr < 97) ||
            (mmstr > 122 && mmstr < 44032) ||
            mmstr > 63086
        ) {
            return false;
            break;
        }
    }
    return true;
}
function Add_MoneyComma(Name) {
    var src;
    var i;
    var factor;
    var su;
    var SpaceSize = 0;
    var chkValue;
    chkValue = "";
    su = Name.value.length;
    for (i = 0; i < su; i++) {
        src = Name.value.substring(i, i + 1);
        if (src != ",") {
            factor = parseInt(src);
            if (isNaN(factor)) {
                alert("숫자가 아닌 값이 입력되었습니다. 숫자만 입력해주세요.");
                Name.focus();
            } else {
                chkValue += src;
            }
        }
    }
    Name.value = chkValue;
    factor = Name.value.length % 3;
    su = (Name.value.length - factor) / 3;
    src = Name.value.substring(0, factor);
    for (i = 0; i < su; i++) {
        if (factor == 0 && i == 0) {
            src += Name.value.substring(factor + 3 * i, factor + 3 + 3 * i);
        } else {
            src += ",";
            src += Name.value.substring(factor + 3 * i, factor + 3 + 3 * i);
        }
    }
    Name.value = src;
    return true;
}
function centerPopup(divname) {
    var windowWidth = document.documentElement.clientWidth;
    var windowHeight = document.documentElement.clientHeight;
    var popupHeight = $("#" + divname).height();
    var popupWidth = $("#" + divname).width();
    $("#" + divname).css({
        position: "absolute",
        top: windowHeight / 2 - popupHeight / 2,
        left: windowWidth / 2 - popupWidth / 2,
    });
}
function centerWidthPopup(divname, topPos) {
    var windowWidth = document.documentElement.clientWidth;
    var popupWidth = $("#" + divname).width();
    $("#" + divname).css({
        position: "absolute",
        top: topPos,
        left: windowWidth / 2 - popupWidth / 2,
    });
}
function comma_add_return(n) {
    var reg = /(^[+-]?\d+)(\d{3})/;
    n += "";
    while (reg.test(n)) n = n.replace(reg, "$1" + "," + "$2");
    return n;
}
function number_change_sosu(num) {
    num = String(num);
    num = num.replace(",", "");
    num = num.replace(".", "");
    num =
        num.substring(0, num.length - 2) +
        "." +
        num.substring(num.length - 2, num.length);
    num = comma_add_return(num);
    return num;
}

// <!--

// 한글만 입력 가능
// 예 : <input type="text" name="post" onKeyPress="OnlyHangul()">
function OnlyHangul() {
    if (event.keyCode < 12592 || event.keyCode > 12687)
        event.returnValue = false;
}

// 숫자만 입력 가능
// 예 : <input type="text" name="post" onKeyPress="OnlyNum()">
function OnlyNum() {
    if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;
}

// 이미지 롤오버
// 예 :  <img src="/images/test_off.gif" name="test" onmouseover="EImgChg('on')" onmouseout="EImgChg('off')">
function EImgChg(flag) {
    source = event.srcElement;
    if (source.name == "") {
        return false;
    } else if (document.images && source.tagName == "IMG") {
        imgElement = source.name; // 이미지 name
        imgPath = source.src; // 이미지 src 속성값

        imgPathLen = imgPath.length;
        imgPathFlag = imgPath.lastIndexOf("/");
        imgName = imgPath.substring(0, imgPathFlag + 1);

        document.images[imgElement].src =
            imgName + imgElement + "_" + flag + ".gif";
    }
}

// Layer 보이게 혹은 감추게 한다
// 예 : <a href="JavaScript:LayerSH('testLayer','show')">보임</a> <a href="JavaScript:LayerSH('testLayer','hide')">안보임</a> , testLayer : Layer Name
function LayerSH(LayerName, Status) {
    ns4 = document.layers ? true : false;
    ie4 = document.all ? true : false;

    if (ns4) {
        LayerN = document.layers[LayerName];
        if (Status == "show") LayerN.visibility = "show";
        if (Status == "hide") LayerN.visibility = "hidden";
    }
    if (ie4) {
        LayerN = document.all[LayerName].style;
        if (Status == "show") LayerN.visibility = "visible";
        if (Status == "hide") LayerN.visibility = "hidden";
    }
}

// 이미지 팝업으로 띄우기
// 예 : <a href="JavaScript:PopImg('test','../images/test_off.gif',900,500)">링크</a>
function PopImg(winname, url, width, height) {
    var win = window.open(
        url,
        winname,
        "left=0, top=0, toolbar=0,menubar=0,scrollbars=no,resizable=no,width=" +
            width +
            ",height=" +
            height +
            ";"
    );
    win.focus();
}

// 페이지 팝업으로 띄우기
// 예 : <a href="JavaScript:PopWindow('test','../images/test_off.gif',0,0,900,500)">링크</a>
function PopWindow(winname, url, left, top, width, height) {
    var sFeatures =
        "left=" +
        left +
        ", top=" +
        top +
        ", width=" +
        width +
        ",height=" +
        height +
        ", toolbar=0,menubar=0,scrollbars=yes,resizable=no";
    var win = window.open(url, winname, sFeatures);
    win.focus();
}

// 숫자만 들어가 있는지 필드 검사
// 예 : var Item = isNumber(document.form.ItemPrice.value);
//		if ( Item == false )
//		{
//			alert("숫자외의 문자가 포함되어 있습니다.");
//			document.form.ItemPrice.focus();
//			return;
//		}
function isNumber(intNum) {
    for (i = 0; i < intNum.length; i++) {
        if (intNum.charCodeAt(i) < 48 || intNum.charCodeAt(i) > 57) {
            return false;
        }
    }
    return true;
}

// checkOK 변수에 있는 글자만 허용되고 나머지는 허용안됨
// 예 : var Item = isCheckOK(document.form.ItemPrice.value);
//		if ( Item == false )
//		{
//			alert("허용되지 않는 문자가 포함되어 있습니다.");
//			document.form.ItemPrice.focus();
//			return;
//		}
function isCheckOK(val) {
    var checkOK =
        "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    for (i = 0; i < val.length; i++) {
        ch = val.charAt(i);
        for (j = 0; j < checkOK.length; j++) if (ch == checkOK.charAt(j)) break;
        if (j == checkOK.length) {
            return false;
            break;
        }
    }
    return true;
}

// 숫자,한글,영문소대문자만 입력 가능 ( 특수문자 제외 )
// 예 : var Item = isCheckOK1(document.form.ItemPrice.value);
//		if ( Item == false )
//		{
//			alert("허용되지 않는 문자가 포함되어 있습니다.");
//			document.form.ItemPrice.focus();
//			return;
//		}
function isCheckOK1(strVal) {
    var intTempIdx;
    var strTemp;

    for (intTempIdx = 0; intTempIdx < strVal.length; intTempIdx++) {
        if (
            (strVal.charCodeAt(intTempIdx) < 127 &&
                strVal.charCodeAt(intTempIdx) > 122) ||
            (strVal.charCodeAt(intTempIdx) < 48 &&
                strVal.charCodeAt(intTempIdx) > 31) ||
            (strVal.charCodeAt(intTempIdx) < 65 &&
                strVal.charCodeAt(intTempIdx) > 57) ||
            (strVal.charCodeAt(intTempIdx) < 97 &&
                strVal.charCodeAt(intTempIdx) > 90) ||
            strVal.charCodeAt(intTempIdx) == 12288
        ) {
            return false;
        }
    }

    if (strVal.length != intTempIdx) return false;
    else return true;
}

// 공백이 포함되어 있는지 체크 ( 없으면 true , 있으면 false )
// 예 : var Item = isSpace(document.form.ItemName);
//		if ( Item == false )
//		{
//			alert("공백이 포함되어 있습니다.");
//			document.form.ItemName.focus();
//			return;
//		}
function isSpace(strObj) {
    var strValue = strObj.value;
    var intCode = 0;

    for (i = 0; i < strValue.length; i++) {
        var intCode = strValue.charCodeAt(i);
        var retChar = strValue.substr(i, 1).toUpperCase();
        intCode = parseInt(intCode);
        if (retChar == " ") return false;
    }
    return true;
}

// 3자리 마다 콤마 찍는 기능 ( Money 를 표시할 경우 )
// 예 : var Item = fCurrency(document.form.ItemPrice.value);
//=======================================================================
function fCurrency(strVal) {
    var valLen = strVal.length;
    var comNo = 0;
    var fLen = 0;
    var fIdx = 0;
    var rtn = 0;
    //alert(valLen);
    if (valLen > 3) {
        if (valLen == 6) {
            comNo = 1;
        } else {
            comNo = parseInt(valLen / 3);
        }
        fLen = valLen - comNo * 3;

        rtn = strVal.substring(0, fLen);

        for (var i = 0; i < comNo; i++) {
            fIdx = fLen + i * 3;
            rtn += "," + strVal.substring(fIdx, fIdx + 3);
        }
    } else rtn = strVal;

    return rtn;
}

// 커서 모양이 손으로 변하는 함수 ( style="cursor:hand" 와 같은 기능 )
// 예 : CursorHand(document.form.id);
function CursorHand(obj) {
    obj.style.cursor = "hand";
}

// 체크박스 리스트 모두 선택
//예 : 체크박스 이름은 list_check로 한다.
var blnCheck = false;
function SelectAll() {
    var list_size = document.all.list_check.length;
    if (list_size == null) list_size = 0;
    if (list_size != 0) {
        if (!blnCheck) {
            for (i = 0; i < list_size; i++) {
                document.all.list_check[i].checked = true;
            }
            blnCheck = true;
        } else {
            for (i = 0; i < list_size; i++) {
                document.all.list_check[i].checked = false;
            }
            blnCheck = false;
        }
    } else {
        if (!blnCheck) {
            document.all.list_check.checked = true;
            blnCheck = true;
        } else {
            document.all.list_check.checked = false;
            blnCheck = false;
        }
    }
}

// 이미지 롤오버 처리용
// 디자이너가 드림위버 사용할때 롤오버 구현할려고 많이 사용
function MM_preloadImages() {
    //v3.0
    var d = document;
    if (d.images) {
        if (!d.MM_p) d.MM_p = new Array();
        var i,
            j = d.MM_p.length,
            a = MM_preloadImages.arguments;
        for (i = 0; i < a.length; i++)
            if (a[i].indexOf("#") != 0) {
                d.MM_p[j] = new Image();
                d.MM_p[j++].src = a[i];
            }
    }
}

function MM_findObj(n, d) {
    //v4.01
    var p, i, x;
    if (!d) d = document;
    if ((p = n.indexOf("?")) > 0 && parent.frames.length) {
        d = parent.frames[n.substring(p + 1)].document;
        n = n.substring(0, p);
    }
    if (!(x = d[n]) && d.all) x = d.all[n];
    for (i = 0; !x && i < d.forms.length; i++) x = d.forms[i][n];
    for (i = 0; !x && d.layers && i < d.layers.length; i++)
        x = MM_findObj(n, d.layers[i].document);
    if (!x && d.getElementById) x = d.getElementById(n);
    return x;
}

function MM_swapImgRestore() {
    //v3.0
    var i,
        x,
        a = document.MM_sr;
    for (i = 0; a && i < a.length && (x = a[i]) && x.oSrc; i++) x.src = x.oSrc;
}

function MM_swapImage() {
    //v3.0
    var i,
        j = 0,
        x,
        a = MM_swapImage.arguments;
    document.MM_sr = new Array();
    for (i = 0; i < a.length - 2; i += 3)
        if ((x = MM_findObj(a[i])) != null) {
            document.MM_sr[j++] = x;
            if (!x.oSrc) x.oSrc = x.src;
            x.src = a[i + 2];
        }
}

function MM_showHideLayers() {
    //v6.0
    var i,
        p,
        v,
        obj,
        args = MM_showHideLayers.arguments;
    for (i = 0; i < args.length - 2; i += 3)
        if ((obj = MM_findObj(args[i])) != null) {
            v = args[i + 2];
            if (obj.style) {
                obj = obj.style;
                v = v == "show" ? "visible" : v == "hide" ? "hidden" : v;
            }
            obj.visibility = v;
        }
}

//
function changeHeaderWorldList(world) {
    var game = $("#headerGameList").val();
    var cookieGame = $.cookie("itempf_game");
    var cookieWorld = $.cookie("itempf_world");

    if (0 == game) {
        if (cookieGame) {
            game = cookieGame;
            world = cookieWorld;
            $("#headerGameList").val(game);
        } else {
            $("#headerWorldList").html(
                '<option value="0" selected>전체 검색</option>'
            );
            return false;
        }
    }

    $.getJSON(
        "/game/getGameAndWorldList.asp",
        { gameId: game },
        function (data) {
            $("#headerWorldList").html(
                '<option value="0" selected>전체 검색</option>'
            );

            $.each(data.listWorld, function (i, item) {
                if (item.worldId == world) {
                    $("#headerWorldList").append(
                        $(
                            '<option value="' +
                                item.worldId +
                                '" selected></option>'
                        ).html(item.name)
                    );
                } else {
                    $("#headerWorldList").append(
                        $(
                            '<option value="' + item.worldId + '"></option>'
                        ).html(item.name)
                    );
                }
            });

            var moneyUnit = data.game[0].moneyUnit || "게임머니";
            $($("#headerCategory").find("option").get(0)).html(moneyUnit);
        }
    );
}

//-->
