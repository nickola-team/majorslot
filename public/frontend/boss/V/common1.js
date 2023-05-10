var SCROLL_CONTENTS = 304;

$(document).on("focus", "input[type=text], textarea", function() {
    $(this).siblings("label").hide();
}).on("blur", "input[type=text], textarea", function() {
    if(!$.trim($(this).val()))
        $(this).siblings("label").show();
    else
        $(this).siblings("label").hide();
});

$(function() {

    $("input[type=text], textarea").blur();

    try{
        $('#fix', parent.document).removeClass('fix'); //�덈줈怨좎묠�� fix ��젣
    }catch(e){}

});

if (typeof(COMMON_JS) == 'undefined') { // �쒕쾲留� �ㅽ뻾
    var COMMON_JS = true;

    // �꾩뿭 蹂���
    var errmsg = "";
    var errfld;

    // �꾨뱶 寃���
    function check_field(fld, msg) 
    {
        if ((fld.value = trim(fld.value)) == "")               
            error_field(fld, msg);
        else
            clear_field(fld);
        return;
    }

    // �꾨뱶 �ㅻ쪟 �쒖떆
    function error_field(fld, msg) 
    {
        if (msg != "")
            errmsg += msg + "\n";
        if (!errfld) errfld = fld;
        fld.style.background = "#BDDEF7";
    }

    // �꾨뱶瑜� 源⑤걮�섍쾶
    function clear_field(fld) 
    {
        fld.style.background = "#FFFFFF";
    }

    function trim(s)
    {
        var t = "";
        var from_pos = to_pos = 0;

        for (i=0; i<s.length; i++)
        {
            if (s.charAt(i) == ' ')
                continue;
            else 
            {
                from_pos = i;
                break;
            }
        }

        for (i=s.length; i>=0; i--)
        {
            if (s.charAt(i-1) == ' ')
                continue;
            else 
            {
                to_pos = i;
                break;
            }
        }   

        t = s.substring(from_pos, to_pos);
        //              alert(from_pos + ',' + to_pos + ',' + t+'.');
        return t;
    }

    // �먮컮�ㅽ겕由쏀듃濡� PHP�� number_format �됰궡瑜� ��

    // �レ옄�� , 瑜� 異쒕젰
    function number_format(data) {

        var tmp = '';
        var number = '';
        var cutlen = 3;
        var comma = ',';
        var i;

        data = Number(data);
        data = String(data);

        len = data.length;
        mod = (len % cutlen);
        k = cutlen - mod;
        for (i=0; i<data.length; i++) {

            number = number + data.charAt(i);

            if (i < data.length - 1) {
                k++;
                if ((k % cutlen) == 0)  {
                    number = number + comma;
                    k = 0;
                }
            }
        }

        if (!number) number = 0;
        return number;
    }

    // �� 李�
    function popup_window(url, winname, opt)
    {
        window.open(url, winname, opt);
    }


    // �쇰찓�� 李�
    function popup_formmail(url)
    {
        opt = 'scrollbars=yes,width=417,height=385,top=10,left=20';
        popup_window(url, "wformmail", opt);
    }

    // , 瑜� �놁븻��.
    function no_comma(data)
    {
        var tmp = '';
        var comma = ',';
        var i;

        for (i=0; i<data.length; i++)
        {
            if (data.charAt(i) != comma)
                tmp += data.charAt(i);
        }
        return tmp;
    }

    // ��젣 寃��� �뺤씤
    function del(href) 
    {
        if(confirm("�쒕쾲 ��젣�� �먮즺�� 蹂듦뎄�� 諛⑸쾿�� �놁뒿�덈떎.\n\n�뺣쭚 ��젣�섏떆寃좎뒿�덇퉴?")) {
            if (g4_charset.toUpperCase() == 'EUC-KR') 
                document.location.href = href;
            else
                document.location.href = encodeURI(href);
        }
    }

    // 荑좏궎 �낅젰
    //function set_cookie(name, value, expirehours, domain) {
    //    var today = new Date();
    //    today.setTime(today.getTime() + (60*60*1000*expirehours));
    //    document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + today.toGMTString() + ";";
    //    if (domain) {
    //        document.cookie += "domain=" + domain + ";";
    //    }
    //}

    // 荑좏궎 �살쓬
    //function get_cookie(name) {
    //    var find_sw = false;
    //    var start, end;
    //    var i = 0;

    //    for (i=0; i<= document.cookie.length; i++)
    //    {
    //        start = i;
    //        end = start + name.length;

    //        if(document.cookie.substring(start, end) == name) 
    //        {
    //            find_sw = true
    //            break
    //        }
    //    }

    //    if (find_sw == true) 
    //    {
    //        start = end + 1;
    //        end = document.cookie.indexOf(";", start);

    //        if(end < start)
    //            end = document.cookie.length;

    //        return document.cookie.substring(start, end);
    //    }
    //    return "";
    //}

    // 荑좏궎 吏���
    //function delete_cookie(name) 
    //{
    //    var today = new Date();

    //    today.setTime(today.getTime() - 1);
    //    var value = get_cookie(name);
    //    if(value != "")
    //        document.cookie = name + "=" + value + "; path=/; expires=" + today.toGMTString();
    //}

    /*function image_window(img)
    {
        var w = Number(img.getAttribute("rel_width"));
        if(w==0) w = Number(img.getAttribute("width"));
        var h = Number(img.getAttribute("rel_height"));
        if(h==0) h = Number(img.getAttribute("height")); 
        var winl = (screen.width-w)/2; 
        var wint = (screen.height-h)/3; 

        if (w >= screen.width) { 
            winl = 0; 
            h = (parseInt)(w * (h / w)); 
        } 

        if (h >= screen.height) { 
            wint = 0; 
            w = (parseInt)(h * (w / h)); 
        } 

        var js_url = "<script type='text/javascript'> \n"; 
            js_url += "<!-- \n"; 
            js_url += "var ie=document.all; \n"; 
            js_url += "var nn6=document.getElementById&&!document.all; \n"; 
            js_url += "var isdrag=false; \n"; 
            js_url += "var x,y; \n"; 
            js_url += "var dobj; \n"; 
            js_url += "function movemouse(e) \n"; 
            js_url += "{ \n"; 
            js_url += "  if (isdrag) \n"; 
            js_url += "  { \n"; 
            js_url += "    dobj.style.left = nn6 ? tx + e.clientX - x : tx + event.clientX - x; \n"; 
            js_url += "    dobj.style.top  = nn6 ? ty + e.clientY - y : ty + event.clientY - y; \n"; 
            js_url += "    return false; \n"; 
            js_url += "  } \n"; 
            js_url += "} \n"; 
            js_url += "function selectmouse(e) \n"; 
            js_url += "{ \n"; 
            js_url += "  var fobj      = nn6 ? e.target : event.srcElement; \n"; 
            js_url += "  var topelement = nn6 ? 'HTML' : 'BODY'; \n"; 
            js_url += "  while (fobj.tagName != topelement && fobj.className != 'dragme') \n"; 
            js_url += "  { \n"; 
            js_url += "    fobj = nn6 ? fobj.parentNode : fobj.parentElement; \n"; 
            js_url += "  } \n"; 
            js_url += "  if (fobj.className=='dragme') \n"; 
            js_url += "  { \n"; 
            js_url += "    isdrag = true; \n"; 
            js_url += "    dobj = fobj; \n"; 
            js_url += "    tx = parseInt(dobj.style.left+0); \n"; 
            js_url += "    ty = parseInt(dobj.style.top+0); \n"; 
            js_url += "    x = nn6 ? e.clientX : event.clientX; \n"; 
            js_url += "    y = nn6 ? e.clientY : event.clientY; \n"; 
            js_url += "    document.onmousemove=movemouse; \n"; 
            js_url += "    return false; \n"; 
            js_url += "  } \n"; 
            js_url += "} \n"; 
            js_url += "document.onmousedown=selectmouse; \n"; 
            js_url += "document.onmouseup=new Function('isdrag=false'); \n"; 
            js_url += "//--> \n"; 
            js_url += "</"+"script> \n"; 

        var settings;

        if (g4_is_gecko) {
            settings  ='width='+(w+10)+','; 
            settings +='height='+(h+10)+','; 
        } else {
            settings  ='width='+w+','; 
            settings +='height='+h+','; 
        }
        settings +='top='+wint+','; 
        settings +='left='+winl+','; 
        settings +='scrollbars=no,'; 
        settings +='resizable=yes,'; 
        settings +='status=no'; 


        win=window.open("","image_window",settings); 
        win.document.open(); 
        win.document.write ("<html><head> \n<meta http-equiv='imagetoolbar' CONTENT='no'> \n<meta http-equiv='content-type' content='text/html; charset="+g4_charset+"'>\n"); 
        var size = "�대�吏� �ъ씠利� : "+w+" x "+h;
        win.document.write ("<title>"+size+"</title> \n"); 
        if(w >= screen.width || h >= screen.height) { 
            win.document.write (js_url); 
            var click = "ondblclick='window.close();' style='cursor:move' title=' "+size+" \n\n �대�吏� �ъ씠利덇� �붾㈃蹂대떎 �쎈땲��. \n �쇱そ 踰꾪듉�� �대┃�� �� 留덉슦�ㅻ� ��吏곸뿬�� 蹂댁꽭��. \n\n �붾툝 �대┃�섎㈃ �ロ���. '"; 
        } 
        else 
            var click = "onclick='window.close();' style='cursor:pointer' title=' "+size+" \n\n �대┃�섎㈃ �ロ���. '"; 
        win.document.write ("<style>.dragme{position:relative;}</style> \n"); 
        win.document.write ("</head> \n\n"); 
        win.document.write ("<body leftmargin=0 topmargin=0 bgcolor=#dddddd style='cursor:arrow;'> \n"); 
        win.document.write ("<table width=100% height=100% cellpadding=0 cellspacing=0><tr><td align=center valign=middle><img src='"+img.src+"' width='"+w+"' height='"+h+"' border=0 class='dragme' "+click+"></td></tr></table>");
        win.document.write ("</body></html>"); 
        win.document.close(); 

        if(parseInt(navigator.appVersion) >= 4){win.window.focus();} 
    }

    // a �쒓렇�먯꽌 onclick �대깽�몃� �ъ슜�섏� �딄린 �꾪빐
    function win_open(url, name, option)
    {
        var popup = window.open(url, name, option);
        popup.focus();
    }

    // 履쎌� 李�
    function win_memo(url)
    {
        if (!url)
            url = g4_path + "/memo/memo_recv.php";
        win_open(url, "winMemo", "left=50,top=50,width=620,height=495,scrollbars=no, resize=no");
    }

    // �⑤꼸�� 李�
    function win_penalty(url)
    {
        var ht = 334;
        var wd = 415;
        win_open(url, "winPenalty", "scrollbars=no,toolbar=no,location=no,directories=no,status=yes,resizable=yes,width=" + wd + ",height=" + ht + ",left=" + (screen.width - wd) * .5 + ",top=" + (screen.height - ht) * .5);
    }

    // 移쒓뎄 李�
    function win_friend(url)
    {
        if (!url)
            url = g4_path + "/memo/memo_abook.php";
        win_open(url, "winFriend", "left=50,top=50,width=595,height=495,scrollbars=no, resize=no");
    }

    // 釉붾옓由ъ뒪�� 李�
    function win_black(url)
    {
        if (!url)
            url = g4_path + "/memo/memo_abook.php?find=B";
        win_open(url, "winFriend", "left=50,top=50,width=595,height=495,scrollbars=no, resize=no");
    }


    // �ъ씤�� 李�
    function win_point(url)
    {
        win_open(g4_path + "/" + g4_bbs + "/market_point.php", "winPoint", "left=20, top=20, width=790, height=800, scrollbars=no");
        
    }

    // �ㅽ겕�� 李�
    function win_scrap(url)
    {
        if (!url)
            url = g4_path + "/" + g4_bbs + "/scrap.php";
        win_open(url, "scrap", "left=20, top=20, width=616, height=500, scrollbars=1");
    }

    function win_cmd(url)
    {
        if (!url) return false;
        win_open(url, "wincmd", "left=20, top=20, width=500, height=300, scrollbars=1");
    }*/

    // �덈줈�� �⑥뒪�뚮뱶 遺꾩떎 李� : 100902
    /*function win_password_lost()
    {
        win_open(g4_path + "/" + g4_bbs + "/password_lost.php", 'winPasswordLost', 'left=50, top=50, width=617, height=480, scrollbars=0');
    }

    // �덈줈�� �꾩씠�� 李얘린 李� : 100902
    function win_id_find()
    {
        win_open(g4_path + "/" + g4_bbs + "/id_lost.php", 'winIdFind', 'left=50, top=50, width=617, height=480, scrollbars=0');
    }

    // �⑥뒪�뚮뱶 遺꾩떎 李�
    function win_password_forget()
    {
        win_open(g4_path + "/" + g4_bbs + "/password_forget.php", 'winPasswordForget', 'left=50, top=50, width=616, height=500, scrollbars=1');
    }*/

    // 肄붾찘�� 李�
    function win_comment(url)
    {
        win_open(url, "winComment", "left=50, top=50, width=800, height=600, scrollbars=1");
    }

    // �쇰찓�� 李�
    function win_formmail(mb_id, name, email)
    {
        if (g4_charset.toLowerCase() == 'euc-kr')
            win_open(g4_path+"/" + g4_bbs + "/formmail.php?mb_id="+mb_id+"&name="+name+"&email="+email, "winFormmail", "left=50, top=50, width=600, height=500, scrollbars=0");
        else
            win_open(g4_path+"/" + g4_bbs + "/formmail.php?mb_id="+mb_id+"&name="+encodeURIComponent(name)+"&email="+email, "winFormmail", "left=50, top=50, width=600, height=480, scrollbars=0");
    }

    // �щ젰 李�
    function win_calendar(fld, cur_date, delimiter, opt)
    {
        if (!opt)
            opt = "left=50, top=50, width=240, height=230, scrollbars=0,status=0,resizable=0";
        win_open(g4_path+"/" + g4_bbs + "/calendar.php?fld="+fld+"&cur_date="+cur_date+"&delimiter="+delimiter, "winCalendar", opt);
    }

    // �ㅻЦ議곗궗 李�
    function win_poll(url)
    {
        if (!url)
            url = "";
        win_open(url, "winPoll", "left=50, top=50, width=616, height=500, scrollbars=1");
    }

    //1:1 梨꾪똿李� �앹뾽    
    function win_date(mb_id, mb_nick) {
        var target_site_id = "localhost";
        var url = g4_path+"/nlivechat/date.php?target_site_id=" + target_site_id + "&tuserid=" + mb_id + "&tname=" + mb_nick + "&mode=REQ_DATE";
        win_open(url, "winFormmail", "left=50, top=50, width=420, height=490, scrollbars=0");
    }

    // �ㅼ엫移대뱶 �앹뾽
    /*function popupNamecard(type){
    
        var popUrl = g4_path + "/user/namecard.php?mb_id="+type;
        var popOption = "width=749, height=430, resizable=no, scrollbars=no, status=no;" ;
    
        window.open(popUrl, "win_namecard", popOption);
    } */      

    // �ㅽ떚而�
    /*function win_sticker(mode) {
        url = g4_path + "/mypage/sticker.php";
        if (mode == "edit") url += "?mode=edit";
        win_open(url, "winUserPicture", "left=50,top=50,width=588,height=702,scrollbars=no, resize=no");
    }*/

    try{   
        // �ㅼ엫移대뱶 �덉씠�� �リ린
        $('#fixed-body', parent.document).click(function(){

            $(this).find('#itemBuyLayer').remove();
        });
    }catch(e){}

        
    var last_id = null;
    function menu(id)
    {
        if (id != last_id)
        {
            if (last_id != null)
                document.getElementById(last_id).style.display = "none";
            document.getElementById(id).style.display = "block";
            last_id = id;
        }
        else
        {
            document.getElementById(id).style.display = "none";
            last_id = null;
        }
    }

    function textarea_decrease(id, row)
    {
        if (document.getElementById(id).rows - row > 0)
            document.getElementById(id).rows -= row;
    }

    function textarea_original(id, row)
    {
        document.getElementById(id).rows = row;
    }

    function textarea_increase(id, row)
    {
        document.getElementById(id).rows += row;
    }

    // 湲��レ옄 寃���
    function check_byte(content, target)
    {
        var i = 0;
        var cnt = 0;
        var ch = '';
        var cont = document.getElementById(content).value;

        for (i=0; i<cont.length; i++) {
            ch = cont.charAt(i);
            if (escape(ch).length > 4) {
                cnt += 2;
            } else {
                cnt += 1;
            }
        }
        // �レ옄瑜� 異쒕젰
        document.getElementById(target).innerHTML = cnt;

        return cnt;
    }

    // 釉뚮씪�곗��먯꽌 �ㅻ툕�앺듃�� �쇱そ 醫뚰몴
    function get_left_pos(obj)
    {
        var parentObj = null;
        var clientObj = obj;
        //var left = obj.offsetLeft + document.body.clientLeft;
        var left = obj.offsetLeft;

        while((parentObj=clientObj.offsetParent) != null)
        {
            left = left + parentObj.offsetLeft;
            clientObj = parentObj;
        }

        return left;
    }

    // 釉뚮씪�곗��먯꽌 �ㅻ툕�앺듃�� �곷떒 醫뚰몴
    function get_top_pos(obj)
    {
        var parentObj = null;
        var clientObj = obj;
        //var top = obj.offsetTop + document.body.clientTop;
        var top = obj.offsetTop;

        while((parentObj=clientObj.offsetParent) != null)
        {
            top = top + parentObj.offsetTop;
            clientObj = parentObj;
        }

        return top;
    }

    function flash_movie(src, ids, width, height, wmode)
    {
        var wh = "";
        if (parseInt(width) && parseInt(height)) 
            wh = " width='"+width+"' height='"+height+"' ";
        return "<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0' "+wh+" id="+ids+"><param name=wmode value="+wmode+"><param name=movie value="+src+"><param name=quality value=high><embed src="+src+" quality=high wmode="+wmode+" type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/shockwave/download/index.cgi?p1_prod_version=shockwaveflash' "+wh+"></embed></object>";
    }

    function obj_movie(src, ids, width, height, autostart)
    {
        var wh = "";
        if (parseInt(width) && parseInt(height)) 
            wh = " width='"+width+"' height='"+height+"' ";
        if (!autostart) autostart = false;
        return "<embed src='"+src+"' "+wh+" autostart='"+autostart+"'></embed>";
    }

    function doc_write(cont)
    {
        document.write(cont);
    }


    //移쒓뎄異붽�
    /*function add_friend(uid) {
        window.open("/pop/add_friend.php?mb_id="+uid,"date_"+uid,"scrollbars=no,toolbar=no,location=no,directories=no,status=yes,resizable=yes,width=415,height=432,left=0,top=0"); 
    }

    //移쒓뎄異붽�
    function add_black(uid) {
        window.open("/pop/add_friend.php?mf_type=BAD&mb_id="+uid,"friend_"+uid,"scrollbars=no,toolbar=no,location=no,directories=no,status=yes,resizable=yes,width=415,height=432,left=0,top=0"); 
    }*/

/*
    function add_friend(mb_id) {
        $.post(g4_path + "/memo/ajax/ajax_friend_group.php", 
              {cmd : "add_friend", fr_id:mb_id},
              function(data) {
                  if(data["returnCode"] == "100") {//�깃났
                        alert(data["returnMsg"]);
                  } else {
                        alert(data["errorMsg"]);
                  }

              },
              'json'
            );
    }
*/

}

$.fn.extend({
    loadSelectBox : function(selected) {

        var $select = $(this);
        var $label = $select.find(".label");
        var $options = $select.find(".options");

        // 湲곕낯媛�
        if(selected) {
            var $selected = $options.find("[rel='"+selected+"']");
            $label.attr("title", $selected.attr("title")).html($selected.html());
            $selected.closest("li").addClass("selected")
        }

        $label.click(function(e) {
            $select.closest(".selectbox").toggleClass("opened");
            e.stopPropagation();
        });

        $(document).click(function() {
            $select.closest(".selectbox").removeClass("opened");
        });

        return $selected;
    }
});

/*
function bluring() {
    if(event.srcElement.tagName=="A"||event.srcElement.tagName=="IMG") 
    document.body.focus();
}

document.onfocusin=bluring;
*/

function get_snstime(unixtime) {

    var loadDt = new Date();
    var curtime = Date.parse(loadDt)/1000;
    var writetime = Number(curtime - unixtime);
    var time;
    var writetime_mm = Math.floor(writetime/60);
    var writetime_hh = Math.floor(writetime/60/60);
    var writetime_dd = Math.floor(writetime/60/60/24);

    //<![CDATA[
    if(writetime_mm <= 0) time = "諛⑷툑";
    else if(writetime_mm < 60) time = "<em>"+writetime_mm+"</em>遺꾩쟾";
    else if(writetime_hh < 24) time = "<em>"+writetime_hh+"</em>�쒓컙��";
    else time = "<em>"+writetime_dd+"</em>�쇱쟾";
    //]]>
    return time;

}


// parseUri 1.2.2
// (c) Steven Levithan <stevenlevithan.com>
// MIT License

function parseUri (str) {
    var o   = parseUri.options,
        m   = o.parser[o.strictMode ? "strict" : "loose"].exec(str),
        uri = {},
        i   = 14;

    while (i--) uri[o.key[i]] = m[i] || "";

    uri[o.q.name] = {};
    uri[o.key[12]].replace(o.q.parser, function ($0, $1, $2) {
        if ($1) uri[o.q.name][$1] = $2;
    });

    return uri;
};

parseUri.options = {
    strictMode: false,
    key: ["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","anchor"],
    q:   {
        name:   "queryKey",
        parser: /(?:^|&)([^&=]*)=?([^&]*)/g
    },
    parser: {
        strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
        loose:  /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/
    }
};

// 異⑹쟾
/*function charge_pop(){ 

    var popUrl = g4_path + "/payment/req_charge.php";
    var popOption = "width=797, height=930, resizable=yes, scrollbars=no, status=no;";
    window.open(popUrl, "req_charge", popOption);
}*/



function number2html(number) {

    number = number == undefined ? "0" : number.toString();
    var html = "";
    for (var i=0; i<number.length; i++) {
        html += '<span class="nb n' + number[i] + '">' + number[i] + '</span>';
    }
    return html;
}

/*function scrollAfterHref() {
    $(top.document).find('body, html').scrollTop(SCROLL_CONTENTS);
    $(top.document).find('body, html').scrollLeft(336);
}

// 蹂댁쑀 �꾩씠�� �앹뾽
function popMyItem(no) {
    var url = "/market/itemshop/item_detail.php?no=" + no;
    window.open(url, "win_item", "left=50, top=50, width=300, height=400, scrollbars=0");   
}

// �꾩씠�� �좊Ъ�섍린
function popGiftItem(no) {
    var url = "/market/itemshop/item_gift.php?no=" + no;
    window.open(url, "win_item", "left=50, top=50, width=450, height=650, scrollbars=0");
}

// �ㅻ뒛�섑븳留덈뵒
function popTodayWord(){
    var popUrl = g4_path + "/mypage/info/popup/change_today_talk.popup.php";
    var popOption = "width=600, height=270, resizable=no, scrollbars=no, status=no;";
    window.open(popUrl,"aphorizm",popOption);
}

// GP�꾪솚 �앹뾽
function pop_gp_exchange(){

    var popUrl = g4_path + "/mypage/gp_exchange.php";
    var popOption = "width=500, height=330, resizable=no, scrollbars=no, status=no;";
    window.open(popUrl,"gp_exchange",popOption);
}*/

//IE 踰꾩쟾 泥댄겕
function ie_ver_check(){

    var ie_ver = false;
    var agt = navigator.userAgent.toLowerCase();
    if (agt.indexOf("msie") != -1)     {

        var trident = navigator.userAgent.match(/Trident\/(\d.\d)/i);
        if (trident == null){

            ie_ver = 7; //IE7

        } else if (trident[1] == '4.0') {

            ie_ver = 8; //IE8

        } else if (trident[1] == '5.0') {

            ie_ver = 9; //IE9

        }
    }
    return ie_ver;
}

// �レ옄留� �낅젰
var isCtrl = false;
function onlyNumber(e) {

    var key;
    var keychar;

    if (window.event) key = window.event.keyCode;
    else if (e) key = e.which;
    else return true;

    keychar = String.fromCharCode(key);


    if ((key == null) || (key == 0) || (key == 8) || (key == 9) || (key == 13) || (key == 27) || (key == 46) || (key == 37) || (key == 39) || (key == 35) || (key == 36) || (key == 86) || (key >= 96 && key <= 105)) {
        return true;
    }
    else if ((("0123456789").indexOf(keychar) > -1)) {
        return true;
    } else {
        if(e.which == 17) isCtrl=true;

        if(e.which == 65 && isCtrl == true) {
            return true;

        }else {
            return false;
        }
    }
}

function GetBetGroupValues(findRows, oddsArrayList){
    var oddspair=[];
    for(var i=0; i<allReadyDone.length;i++){
        if(findRows.account_number==allReadyDone[i].account_number){
            var obj = { };
            oddspair[allReadyDone[i].oddsid] = allReadyDone[i].stake;

        }
    }
    var oddspairCnt = found = Object.keys(oddspair).length;
    var stackDone=0;
    for (var k in oddspair){
        if (oddspair.hasOwnProperty(k)) {
             if(oddsArrayList.includes(k)){
                found--;
                stackDone=oddspair[k];
            }
        }
    }
    if(oddspairCnt>0 && found==0 && oddsArrayList.length==oddspairCnt){
        return stackDone;
    }else{
        return 0;
    }

}

function multipleBetAmount(){
    var get_cooki=getCookie('cookies_betting');
    var oddsArrayList = [];
    var stacks=0;
    if(get_cooki !=null){
        var a_c=JSON.parse(get_cooki);
        $.each(a_c,function(k,m){
                $.each(m,function(k1,m1){
                oddsArrayList.push(k1);
            });
        });
    }
    if(oddsArrayList.length>0){
        for(var i=0; i<allReadyDone.length;i++){
            if(oddsArrayList.includes(allReadyDone[i].oddsid)){
                var stacks = GetBetGroupValues(allReadyDone[i], oddsArrayList);
            }
        }
    }
    return parseInt(stacks);
}

function parseNrNM2(val){
        val = val.replace(/[^\/\d]/g,'');
        return parseInt(val);
    }

//-> �좏깮�� 湲덉븸留뚰겮 諛고똿湲덉븸 利앷�

    function bettingMoneyPlus(plusMoney, type) {

        var spbet = parseFloat($('#sp_bet').text());
        if(spbet<1){
            alert("踰좏똿�� 癒쇱� �좏깮�댁＜�몄슂.");
            return false;
        }

        var this_money = betForm.betprice.value;
        this_money = this_money.replace(/,/g,"");
        if ( plusMoney == 0 ) {
            if ( type ) {
                var end_money = 0;
            } else {
                var end_money = parseInt(eval("VarMinBet"));
            }
        } else {
            if(type==true){
                var end_money = parseInt(plusMoney);   
            }else{
                var end_money = parseInt(this_money) + parseInt(plusMoney);
            }
            
        }

        var max_bet_p = parseNrNM2($('#max_bet_p').text());
        var amountDone = multipleBetAmount();
        var nextBetAmount = amountDone + end_money;
        if(amountDone>0 && nextBetAmount>max_bet_p){
            end_money = end_money - amountDone;
        }  

        if(end_money>max_bet_p || amountDone==max_bet_p){
            alert("理쒕� 踰좏똿�쒕룄瑜� 珥덇낵 �섏��듬땲��.");
            return false;
        }
        //Check If Balance is low
        if(walletBalance < end_money && type==true){
            end_money = walletBalance;
        }
        if(walletBalance < end_money || walletBalance==0){
            $("#btnPlaceBet").attr('disabled',true);
            alert('蹂댁쑀癒몃땲媛� 遺�議깊빀�덈떎.');
            return false;
        }else{
            $("#btnPlaceBet").removeAttr('disabled');
            
        }
        betForm.betprice.value = MoneyFormat(end_money);
        calc();
    }

    function applyMax(){
        var bal = $('#max_bet_p').html().replace(/,/g,"");
        bettingMoneyPlus(parseInt(bal),true)
        //alert(parseInt(bal));
        //$('#betprice').val(MoneyFormat(bal));
    }


    function getObject(objectId) {
        if(document.getElementById && document.getElementById[objectId]) { 
            return document.getElementById[objectId]; 
        } else if (document.all && document.all[objectId]) { 
            return document.all[objectId]; 
        } else if (document.layers && document.layers[objectId]) { 
            return document.layers[objectId]; 
        } else if(document.getElementById && document.getElementById(objectId)) { 
            return document.getElementById(objectId); 
        } else { 
            return false; 
        } 
    }

    //-> 諛고똿�щ┰ �ㅽ겕濡�
    var betslipStopFlag = 0;
    function betSlipStop(obj) {
        if ( betslipStopFlag == 0 ) {
            betslipStopFlag = 1;
            $(obj).attr('src','/images/cart_head_2o.png');
        } else {
            betslipStopFlag = 0;
            $(obj).attr('src','/images/cart_head_2.png');
        }

        initMoving();
    }

    function initMoving() {
        var obj = document.getElementById("contents_right");
        obj.initTop = 12;
        obj.topLimit = 12;
        obj.bottomLimit = document.documentElement.scrollHeight - 100;
         
        obj.style.position = "absolute";
        obj.top = obj.initTop;
        obj.left = obj.initLeft;
        if (typeof(window.pageYOffset) == "number") {
            obj.getTop = function() {
                return window.pageYOffset;
            }
        } else if (typeof(document.documentElement.scrollTop) == "number") {
            obj.getTop = function() {
                return document.documentElement.scrollTop;
            }
        } else {
            obj.getTop = function() {
                return 0;
            }
        }
         
        if (self.innerHeight) {
            obj.getHeight = function() {
                return self.innerHeight;
            }
        } else if(document.documentElement.clientHeight) {
            obj.getHeight = function() {
                return document.documentElement.clientHeight;
            }
        } else {
            obj.getHeight = function() {
                return 1000;
            }
        }

        if (betslipStopFlag){
            clearInterval(obj.move);
            return false;
        }else{ 
            obj.move = setInterval(quickdiv, 30); //�대룞�띾룄
        }
    }

    function quickdiv() {
        var obj = document.getElementById("contents_right");
        pos = obj.getTop() - 256;
        if (pos > obj.bottomLimit)
        pos = obj.bottomLimit;
        if (pos < obj.topLimit)
        pos = obj.topLimit;
        interval = obj.top - pos;
        obj.top = obj.top - interval / 3;
        obj.style.top = obj.top + "px";
    }

