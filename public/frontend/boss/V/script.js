
//v1.7
// Flash Player Version Detection
// Detect Client Browser type
// Copyright 2005-2008 Adobe Systems Incorporated.  All rights reserved.
var isIE  = (navigator.appVersion.indexOf("MSIE") != -1) ? true : false;
var isWin = (navigator.appVersion.toLowerCase().indexOf("win") != -1) ? true : false;
var isOpera = (navigator.userAgent.indexOf("Opera") != -1) ? true : false;
function ControlVersion()
{
	var version;
	var axo;
	var e;
	// NOTE : new ActiveXObject(strFoo) throws an exception if strFoo isn't in the registry
	try {
		// version will be set for 7.X or greater players
		axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.7");
		version = axo.GetVariable("$version");
	} catch (e) {
	}
	if (!version)
	{
		try {
			// version will be set for 6.X players only
			axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.6");
			
			// installed player is some revision of 6.0
			// GetVariable("$version") crashes for versions 6.0.22 through 6.0.29,
			// so we have to be careful. 
			
			// default to the first public version
			version = "WIN 6,0,21,0";
			// throws if AllowScripAccess does not exist (introduced in 6.0r47)		
			axo.AllowScriptAccess = "always";
			// safe to call for 6.0r47 or greater
			version = axo.GetVariable("$version");
		} catch (e) {
		}
	}
	if (!version)
	{
		try {
			// version will be set for 4.X or 5.X player
			axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.3");
			version = axo.GetVariable("$version");
		} catch (e) {
		}
	}
	if (!version)
	{
		try {
			// version will be set for 3.X player
			axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.3");
			version = "WIN 3,0,18,0";
		} catch (e) {
		}
	}
	if (!version)
	{
		try {
			// version will be set for 2.X player
			axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash");
			version = "WIN 2,0,0,11";
		} catch (e) {
			version = -1;
		}
	}
	
	return version;
}
// JavaScript helper required to detect Flash Player PlugIn version information
function GetSwfVer(){
	// NS/Opera version >= 3 check for Flash plugin in plugin array
	var flashVer = -1;
	
	if (navigator.plugins != null && navigator.plugins.length > 0) {
		if (navigator.plugins["Shockwave Flash 2.0"] || navigator.plugins["Shockwave Flash"]) {
			var swVer2 = navigator.plugins["Shockwave Flash 2.0"] ? " 2.0" : "";
			var flashDescription = navigator.plugins["Shockwave Flash" + swVer2].description;
			var descArray = flashDescription.split(" ");
			var tempArrayMajor = descArray[2].split(".");			
			var versionMajor = tempArrayMajor[0];
			var versionMinor = tempArrayMajor[1];
			var versionRevision = descArray[3];
			if (versionRevision == "") {
				versionRevision = descArray[4];
			}
			if (versionRevision[0] == "d") {
				versionRevision = versionRevision.substring(1);
			} else if (versionRevision[0] == "r") {
				versionRevision = versionRevision.substring(1);
				if (versionRevision.indexOf("d") > 0) {
					versionRevision = versionRevision.substring(0, versionRevision.indexOf("d"));
				}
			}
			var flashVer = versionMajor + "." + versionMinor + "." + versionRevision;
		}
	}
	// MSN/WebTV 2.6 supports Flash 4
	else if (navigator.userAgent.toLowerCase().indexOf("webtv/2.6") != -1) flashVer = 4;
	// WebTV 2.5 supports Flash 3
	else if (navigator.userAgent.toLowerCase().indexOf("webtv/2.5") != -1) flashVer = 3;
	// older WebTV supports Flash 2
	else if (navigator.userAgent.toLowerCase().indexOf("webtv") != -1) flashVer = 2;
	else if ( isIE && isWin && !isOpera ) {
		flashVer = ControlVersion();
	}	
	return flashVer;
}
// When called with reqMajorVer, reqMinorVer, reqRevision returns true if that version or greater is available
function DetectFlashVer(reqMajorVer, reqMinorVer, reqRevision)
{
	versionStr = GetSwfVer();
	if (versionStr == -1 ) {
		return false;
	} else if (versionStr != 0) {
		if(isIE && isWin && !isOpera) {
			// Given "WIN 2,0,0,11"
			tempArray         = versionStr.split(" "); 	// ["WIN", "2,0,0,11"]
			tempString        = tempArray[1];			// "2,0,0,11"
			versionArray      = tempString.split(",");	// ['2', '0', '0', '11']
		} else {
			versionArray      = versionStr.split(".");
		}
		var versionMajor      = versionArray[0];
		var versionMinor      = versionArray[1];
		var versionRevision   = versionArray[2];
        	// is the major.revision >= requested major.revision AND the minor version >= requested minor
		if (versionMajor > parseFloat(reqMajorVer)) {
			return true;
		} else if (versionMajor == parseFloat(reqMajorVer)) {
			if (versionMinor > parseFloat(reqMinorVer))
				return true;
			else if (versionMinor == parseFloat(reqMinorVer)) {
				if (versionRevision >= parseFloat(reqRevision))
					return true;
			}
		}
		return false;
	}
}
function AC_AddExtension(src, ext)
{
  if (src.indexOf('?') != -1)
    return src.replace(/\?/, ext+'?'); 
  else
    return src + ext;
}
function AC_Generateobj(objAttrs, params, embedAttrs) 
{ 
  var str = '';
  if (isIE && isWin && !isOpera)
  {
    str += '<object ';
    for (var i in objAttrs)
    {
      str += i + '="' + objAttrs[i] + '" ';
    }
    str += '>';
    for (var i in params)
    {
      str += '<param name="' + i + '" value="' + params[i] + '" /> ';
    }
    str += '</object>';
  }
  else
  {
    str += '<embed ';
    for (var i in embedAttrs)
    {
      str += i + '="' + embedAttrs[i] + '" ';
    }
    str += '> </embed>';
  }
  document.write(str);
}
function AC_FL_RunContent(){
  var ret = 
    AC_GetArgs
    (  arguments, ".swf", "movie", "clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
     , "application/x-shockwave-flash"
    );
  AC_Generateobj(ret.objAttrs, ret.params, ret.embedAttrs);
}
function AC_SW_RunContent(){
  var ret = 
    AC_GetArgs
    (  arguments, ".dcr", "src", "clsid:166B1BCA-3F9C-11CF-8075-444553540000"
     , null
    );
  AC_Generateobj(ret.objAttrs, ret.params, ret.embedAttrs);
}
function AC_GetArgs(args, ext, srcParamName, classid, mimeType){
  var ret = new Object();
  ret.embedAttrs = new Object();
  ret.params = new Object();
  ret.objAttrs = new Object();
  for (var i=0; i < args.length; i=i+2){
    var currArg = args[i].toLowerCase();    
    switch (currArg){	
      case "classid":
        break;
      case "pluginspage":
        ret.embedAttrs[args[i]] = args[i+1];
        break;
      case "src":
      case "movie":	
        args[i+1] = AC_AddExtension(args[i+1], ext);
        ret.embedAttrs["src"] = args[i+1];
        ret.params[srcParamName] = args[i+1];
        break;
      case "onafterupdate":
      case "onbeforeupdate":
      case "onblur":
      case "oncellchange":
      case "onclick":
      case "ondblclick":
      case "ondrag":
      case "ondragend":
      case "ondragenter":
      case "ondragleave":
      case "ondragover":
      case "ondrop":
      case "onfinish":
      case "onfocus":
      case "onhelp":
      case "onmousedown":
      case "onmouseup":
      case "onmouseover":
      case "onmousemove":
      case "onmouseout":
      case "onkeypress":
      case "onkeydown":
      case "onkeyup":
      case "onload":
      case "onlosecapture":
      case "onpropertychange":
      case "onreadystatechange":
      case "onrowsdelete":
      case "onrowenter":
      case "onrowexit":
      case "onrowsinserted":
      case "onstart":
      case "onscroll":
      case "onbeforeeditfocus":
      case "onactivate":
      case "onbeforedeactivate":
      case "ondeactivate":
      case "type":
      case "codebase":
      case "id":
        ret.objAttrs[args[i]] = args[i+1];
        break;
      case "width":
      case "height":
      case "align":
      case "vspace": 
      case "hspace":
      case "class":
      case "title":
      case "accesskey":
      case "name":
      case "tabindex":
        ret.embedAttrs[args[i]] = ret.objAttrs[args[i]] = args[i+1];
        break;
      default:
        ret.embedAttrs[args[i]] = ret.params[args[i]] = args[i+1];
    }
  }
  ret.objAttrs["classid"] = classid;
  if (mimeType) ret.embedAttrs["type"] = mimeType;
  return ret;
}

function flashShow(url, w, h, id, bg, win){

	domain = document.URL;
	parse = domain.split("/");
	loc = parse[3];
	loc_par = '';

	switch(loc){
		case "Company":
			loc_par = '1';
			break;
		case "Glyco":
			loc_par = '2';
			break;
		case "Community":
			loc_par = '3';
			break;
		case "Data":
			loc_par = '4';
			break;
		case "Customer":
			loc_par = '5';
			break;
		case "MyOffice":
			loc_par = '6';
			break;
	}

	var flashStr=
	"<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='../fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0/#version=8,0,0,0/default.htm/#version=8,0,0,0/#version=8,0,0,0/default.htm/default.htm' width='"+w+"' height='"+h+"' id='"+id+"' align='middle'>"+
	"<param name='movie' value='"+url+"' />"+
	"<param name='menu' value='false' />"+
	"<param name='quality' value='high' />"+
	"<param name='wmode' value='transparent' />"+
	"<param name='FlashVars' value='main="+loc_par+"&'>"  +
	"<embed src='"+url+"' menu='false' wmode='transparent' quality='high' width='"+w+"' height='"+h+"' name='"+id+"' align='middle' type='application/x-shockwave-flash' pluginspage='../www.macromedia.com/go/getflashplayer' />"+
	"</object>";
	document.write(flashStr);
}
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}
function FindEitorControl(obj_id) {
	// 占쏙옙占쏙옙占쏙옙트 ID占쏙옙 占쎌선 占싯삼옙
	if (document.getElementById(obj_id) != null) {
		return document.getElementById(obj_id);
	}
	// 占승깍옙占싱몌옙占쏙옙占쏙옙 LIKE 占싯삼옙
	else {
		var obj		= document.getElementsByTagName('TEXTAREA');
		
		for(var i = 0; i < obj.length ; i++) {
			if (obj[i].id.indexOf(obj_id) > -1) {
				return document.all[obj[i].id];
			}
		}
	}
    return null;
}

function MM_showHideLayers() { //v6.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

// -->
function replaceComma(str)
{
	if(str==null || str.length==0) return "";
	while(str.indexOf(",")>-1){
		str = str.replace(",","");
	}
	return str;
}
function insertComma(str)
{
	var rightchar = replaceComma(str);
	var moneychar="";
	for(index=rightchar.length-1;index>=0;index--){
		splitchar = rightchar.charAt(index);
		if(isNaN(splitchar)){
			//alert(splitchar+"占쏙옙占쌘곤옙 占싣닙니댐옙.\n占쌕쏙옙 占쌉뤄옙占쏙옙占쌍십시울옙.");
			return "";
		}
		moneychar = splitchar+moneychar;
		if(index%3==rightchar.length%3 && index  !=0) {
			moneychar = ','+moneychar;
		}
	}
	str = moneychar;
	return str;
}
function numChk(num)
{
	var rightchar = replaceComma(num.value);
	var moneychar="";
	for(index=rightchar.length-1;index>=0;index--){
		splitchar = rightchar.charAt(index);
		if(isNaN(splitchar)){
			//alert("'"+splitchar+"' 占쏙옙占쌘곤옙 占싣닙니댐옙.\n占쌕쏙옙 占쌉뤄옙占쏙옙占쌍십시울옙.");
			num.value="";
			num.focus();
			return false;
		}
		moneychar = splitchar+moneychar;
		if(index%3==rightchar.length%3 && index  !=0) {
			moneychar = ','+moneychar;
		}
	}
	num.value = moneychar;
	return true;
}
function floatNumChk(num)
{
	var rightchar = replaceComma(num.value);
	var moneychar="";
	for(index=rightchar.length-1;index>=0;index--){
		splitchar = rightchar.charAt(index);
		if(isNaN(splitchar)){
			//alert("'"+splitchar+"' 占쏙옙占쌘곤옙 占싣닙니댐옙.\n占쌕쏙옙 占쌉뤄옙占쏙옙占쌍십시울옙.");
			num.value="";
			num.focus();
			return false;
		}
		moneychar = splitchar+moneychar;
		if(index%3==rightchar.length%3 && index  !=0) {
			moneychar = ','+moneychar;
		}
	}
	num.value = moneychar;
	return true;
}
function goMenu(idx)
{
	document.main_form.menu.value=idx;
	document.main_form.submit();
}
function notice(str)
{
	alert(str);
}
//占쏙옙占쌘몌옙
function checkDigitOnly( digitChar )
{
	if ( digitChar == null ) return false ;

	for(var i=0;i<digitChar.length;i++){
		var c=digitChar.charCodeAt(i);
		if( !(  0x30 <= c && c <= 0x39 )){
			return false ;
		}
	}
	return true ;
}
//占싼글몌옙
function checkKoreanOnly( koreanChar )
{
	if ( koreanChar == null ) return false ;

	for(var i=0; i < koreanChar.length; i++){ 
		var c=koreanChar.charCodeAt(i); 
		//( 0xAC00 <= c && c <= 0xD7A3 ) 占쏙옙占쏙옙占쏙옙占쏙옙占쏙옙 占쏙옙占쏙옙 占싼깍옙占쏙옙 
		//( 0x3131 <= c && c <= 0x318E ) 占쏙옙占쏙옙 占쏙옙占쏙옙 
		if( !( ( 0xAC00 <= c && c <= 0xD7A3 ) || ( 0x3131 <= c && c <= 0x318E ) ) ) {
			return false ; 
		}
	}
	return true ;
}
//占쏙옙占쏙옙占쏙옙
function checkEnglishOnly( englishChar )
{

	if ( englishChar == null ) return false ;

	for( var i=0; i < englishChar.length;i++){
		var c=englishChar.charCodeAt(i);
		if( !( (  0x61 <= c && c <= 0x7A ) || ( 0x41 <= c && c <= 0x5A ) ) ) {
			return false ;       
		}
	}
	return true ;
}
//占쏙옙占쏙옙占쌘몌옙
function checkEnglishDigitOnly( englishChar )
{

	if ( englishChar == null ) return false ;

	for( var i=0; i < englishChar.length;i++){
		var c=englishChar.charCodeAt(i);
		if(!((0x61 <= c && c <= 0x7A ) || ( 0x41 <= c && c <= 0x5A )) && !(0x30 <= c && c <= 0x39))
		{
			return false ;       
		}
	}
	return true ;
}
//占싼글쇽옙占쌘몌옙
function checkKoreanDigitOnly( koreanChar )
{
	if ( koreanChar == null ) return false ;

	for(var i=0; i < koreanChar.length; i++){ 
		var c=koreanChar.charCodeAt(i); 
		//( 0xAC00 <= c && c <= 0xD7A3 ) 占쏙옙占쏙옙占쏙옙占쏙옙占쏙옙 占쏙옙占쏙옙 占싼깍옙占쏙옙 
		//( 0x3131 <= c && c <= 0x318E ) 占쏙옙占쏙옙 占쏙옙占쏙옙 
		if( !((0xAC00 <= c && c <= 0xD7A3 ) || ( 0x3131 <= c && c <= 0x318E)) && !(0x30 <= c && c <= 0x39)) {
			return false ;
		}
	}
	return true ;
}


function changePoint()
{
	if(!confirm("占쏙옙占쏙옙트占쏙옙 占쏙옙占쏙옙占쌥억옙占쏙옙占쏙옙 占쏙옙환占싹시겠쏙옙占싹깍옙?")){
		return;
	}
	document.formPoint.change_point.value	=	"change";
	document.formPoint.submit();
}
function modifyMemberInfo()
{
	
}
function logout()
{
	if(!confirm("占싸그아울옙 占싹시겠쏙옙占싹깍옙?")){
		return;
	}
	document.location.href="@mode=logout";
}
function drawLevel(level,id)
{
	if(level>3){
	AC_FL_RunContent(
		'codebase', '../download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0',
		'width', '21',
		'height', '19',
		'src', 'images/level/level_'+level,
		'quality', 'high',
		'pluginspage', '../www.adobe.com/go/getflashplayer',
		'align', 'middle',
		'play', 'true',
		'loop', 'true',
		'scale', 'showall',
		'wmode', 'transparent',
		'devicefont', 'false',
		'id', 'level_'+level+'_'+id,
		'bgcolor', '#ffffff',
		'name', 'level_'+level+'_'+id,
		'menu', 'true',
		'allowFullScreen', 'false',
		'allowScriptAccess','sameDomain',
		'movie', 'level_'+level,
		'salign', ''
		);
	}else{
		document.write("<img class='png24' src='images/level/level_"+level+".png'");
	}
}

function setPng24(obj)
{
	obj.width=obj.height=1;
	obj.className=obj.className.replace(/\bpng24\b/i,'');
	obj.style.filter="progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+obj.src+"', sizingMethod='image');";
	obj.src="about:blank;";
	return '';
}
function goMenu(menu)
{
	var url = "./";

	switch(menu){
		case 1:
			url	=	"member/default.htm";
			break;
		case 10:
			url	=	"game/default.htm";
			break;
		case 11:
			url	=	"game/@kind=h.htm";
			break;
		case 12:
			url	=	"game/@kind=s.htm";
			break;
		case 110:
			url	=	"game/real.jsp.htm";
			break;
		case 20:
			url	=	"event/default.htm";
			break;
		case 30:
			url	=	"result/default.htm";
			break;
		case 31:
			url	=	"result/@kind=h.htm";
			break;
		case 32:
			url	=	"result/@kind=s.htm";
			break;
		case 40:
			url	=	"bbs/default.htm";
			break;
		case 50:
			url	=	"customer.asp";
			break;
		case 51:
			url	=	"customer/@kind=q.htm";
			break;
		case 52:
			url	=	"customer/@kind=m.htm";
			break;
		case 60:
			url	=	"rule/default.htm";
			break;
		case 61:
			url	=	"rule/@sub=1.htm";
			break;
		case 62:
			url	=	"rule/@sub=2.htm";
			break;
		case 63:
			url	=	"rule/@sub=3.htm";
			break;
		case 64:
			url	=	"rule/@sub=4.htm";
			break;
		case 65:
			url	=	"rule/@sub=5.htm";
			break;
		case 66:
			url	=	"rule/@sub=6.htm";
			break;
		case 70:
			url	=	"charge/default.htm";
			break;
		case 71:
			url	=	"charge/@obj=list.htm";
			break;
		case 80:
			url	=	"exchange/default.htm";
			break;
		case 81:
			url	=	"exchange/@obj=list.htm";
			break;
		case 90:
			url	=	"betting/default.htm";
			break;
		case 91:
			url	=	"history/@obj=betting_cancel";
			break;
		case 100:
			url	=	"live";
			break;
		case 150:
			url	=	"message/default.htm";
			break;
		default:
			url	=	"main/default.htm";
	}
	document.location.href=url;
}

function linkurl(j,k){
	switch(j){
		case 0:

			break;
		case 1:
			goMenu(10);
			break;
		case 2:
			goMenu(11);
			break;
		case 3:
			goMenu(12);
			break;
		case 4:
			goMenu(20);
			break;
		case 5:
			goMenu(30);
			break;
		case 6:
			goMenu(100);
			break;
		case 7:
			goMenu(40);
			break;
		case 8:
			goMenu(50);
			break;
		case 9:
			goMenu(60);
			break;
	}
}

var BrowserDetect = {
	init: function () {
		this.browser = this.searchString(this.dataBrowser) || "An unknown browser";
		this.version = this.searchVersion(navigator.userAgent)
			|| this.searchVersion(navigator.appVersion)
			|| "an unknown version";
		this.OS = this.searchString(this.dataOS) || "an unknown OS";
	},
	searchString: function (data) {
		for (var i=0;i<data.length;i++)	{
			var dataString = data[i].string;
			var dataProp = data[i].prop;
			this.versionSearchString = data[i].versionSearch || data[i].identity;
			if (dataString) {
				if (dataString.indexOf(data[i].subString) != -1)
					return data[i].identity;
			}
			else if (dataProp)
				return data[i].identity;
		}
	},
	searchVersion: function (dataString) {
		var index = dataString.indexOf(this.versionSearchString);
		if (index == -1) return;
		return parseFloat(dataString.substring(index+this.versionSearchString.length+1));
	},
	dataBrowser: [
		{
			string: navigator.userAgent,
			subString: "Chrome",
			identity: "Chrome"
		},
		{
			prop: window.opera,
			identity: "Opera"
		},
		{
			string: navigator.userAgent,
			subString: "Firefox",
			identity: "Firefox"
		},
		{		
			string: navigator.userAgent,
			subString: "Netscape",
			identity: "Netscape"
		},
		{
			string: navigator.userAgent,
			subString: "MSIE",
			identity: "Explorer",
			versionSearch: "MSIE"
		},
		{ 		// for older Netscapes (4-)
			string: navigator.userAgent,
			subString: "Mozilla",
			identity: "Netscape",
			versionSearch: "Mozilla"
		}
	],
	dataOS : [
		{
			string: navigator.platform,
			subString: "Win",
			identity: "Windows"
		},
		{
			string: navigator.platform,
			subString: "Mac",
			identity: "Mac"
		},
		{
			   string: navigator.userAgent,
			   subString: "iPhone",
			   identity: "iPhone/iPod"
	    },
		{
			string: navigator.platform,
			subString: "Linux",
			identity: "Linux"
		}
	]

};
BrowserDetect.init();

function lineMouseOver()
{
	return;
	if(document.getElementById("sep_left")) document.getElementById("sep_left").style.backgroundColor="#770101";
	if(document.getElementById("sep_center")) document.getElementById("sep_center").style.backgroundColor="#770101";
	if(document.getElementById("sep_right")) document.getElementById("sep_right").style.backgroundColor="#770101";
}
function lineMouseOut()
{
	return;
	if(document.getElementById("sep_left")) document.getElementById("sep_left").style.backgroundColor="";
	if(document.getElementById("sep_center")) document.getElementById("sep_center").style.backgroundColor="";
	if(document.getElementById("sep_right")) document.getElementById("sep_right").style.backgroundColor="";

}

