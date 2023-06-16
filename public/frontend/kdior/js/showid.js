/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +--------------------------------------------------------+
// | Internet Explorer 5.5 & Firefox 1.0                    |
// +--------------------------------------------------------+
// | Copyright (c) 2007 Song Hyo-Jin                        |
// +--------------------------------------------------------+
// | This source file is BSD license.                       |
// +--------------------------------------------------------+
// | Author : Song Hyo-Jin <shj at xenosi.de>               |
// +--------------------------------------------------------+
//
// $Id: showid.js, v 0.0.2 2007/05/17 17:40:00 crosser Exp $
//
// image button libraries


/*     
遺덈윭�ㅺ린 :     <script language="javascript" src='js/showid.js'></script>
留덉슦�ㅻ·�ㅻ쾭 : <a href="#" onMouseOver="show_over(this);" onMouseOut="show_out(this);"><img src="�대�吏�"><img src="�대�吏�" style="display:none;"></a>

�щ럞�ㅻ찓�대컮 : <a href="#" onmouseover="show_over(this);" onMouseOut="show_out(this); show_layer('layers_1');"><img src="�대�吏�"><img src="�대�吏�" style="display:none;"></a>
               <!--�щ럞��1--> 
               <div id="layers_1" style="display:none;">
               <a href="#" onmouseover="show_over(this);" onMouseOut="show_out(this);"><img src="�대�吏�"><img src="�대�吏�" style="display:none;"></a>
               </div>
*/

function show_over(atag, disptype, tagName)
{
	if(!disptype) {
		disptype = 'inline';
	}
	if(!tagName) {
		tagName = 'IMG';
	}
	var subs = atag.getElementsByTagName(tagName);
	if(subs.length < 2) {
		return;
	}
	if(atag.name) {
		hidden_name(atag.name, disptype, tagName);
	}
	subs[0].style.display = 'none';
	subs[1].style.display = disptype;
}

function hidden_name(atag_name, disptype, tagName)
{
	if(!disptype) {
		disptype = 'inline';
	}
	if(!tagName) {
		tagName = 'IMG';
	}
	var i, rsubs, hdn = document.getElementsByName(atag_name);
	for(i = 0; i < hdn.length; i ++) {
		rsubs = hdn[i].getElementsByTagName(tagName);
		if(rsubs.length < 2) {
			continue;
		}
		rsubs[0].style.display = disptype;
		rsubs[1].style.display = 'none';
	}
}

function show_out(atag, disptype, tagName)
{
	if(!disptype) {
		disptype = 'inline';
	}
	if(!tagName) {
		tagName = 'IMG';
	}
	var subs = atag.getElementsByTagName(tagName);
	if(subs.length < 2) {
		return;
	}
	subs[0].style.display = disptype;
	subs[1].style.display = 'none';
}

function show_toggle(atag, disptype, tagName)
{
	if(!disptype) {
		disptype = 'inline';
	}
	if(atag.tagName != 'A') {
		if(atag.style.display == 'none') {
			atag.style.display = disptype;
		} else {
			atag.style.display = 'none';
		}
		return;
	}
	if(!tagName) {
		tagName = 'IMG';
	}
	if(!disptype) {
		disptype = 'inline';
	}
	var subs = atag.getElementsByTagName(tagName);
	if(subs.length < 2) {
		return;
	}
	if(subs[0].style.display == 'none') {
		subs[0].style.display = disptype;
		subs[1].style.display = 'none';
	} else {
		subs[0].style.display = 'none';
		subs[1].style.display = disptype;
	}
}

function show_layer(tgt, disptype)
{
	if(!disptype) {
		disptype = 'inline';
	}
	var vsb = document.getElementById(tgt);
	var pattern = /^([A-Za-z_]+)[0-9]*$/;
	pattern.test(tgt);
	var hdn, i = 1;
	while((hdn = document.getElementById(RegExp.$1 + i)) != null) {
		hdn.style.display = 'none';
		i ++;
	}
	if(vsb != null) {
		vsb.style.display = disptype;
	}
}

function show_class(tgt, orig, targ)
{
	var vsb = document.getElementById(tgt);
	var pattern = /^([A-Za-z_]+)[0-9]*$/;
	pattern.test(tgt);
	var hdn, i = 1;
	while((hdn = document.getElementById(RegExp.$1 + i)) != null) {
		hdn.className = orig;
		i ++;
	}
	if(vsb != null) {
		vsb.className = targ;
	}
}

//�먭컻�� �덉씠�대� �숈떆�� �⑥삤�꾪븯湲곗쐞�� �먮컮�ㅽ겕由쏀듃

function layer_onoff_1(){ 
document.getElementById("Layer_bg").style.display='block'; 
document.getElementById("layers_1").style.display='block';
}

//留뚯빟 �꾩옱�ъ씠�몄긽�쒖뿉�� 湲곗〈�뚯썝�낆옣�� 癒쇱� �꾩썙�� �� 寃쎌슦 �섎떒�댁슜異붽��� �몄텧�쒕떎.
//利� �꾩옱�� �먭컻�� �덉씠��(Layer_bg�쒖쇅)濡� 援щ룞 �섏�留� �レ옄媛� �섏뼱�� 寃쎌슦 �덉씠�댁삩�ㅽ봽媛� �� 異붽��섏빞 �쒕떎.
function layer_onoff_2(){ 
document.getElementById("Layer_bg").style.display='block'; 
document.getElementById("layers_2").style.display='block';
}

//html �곸뿉�� 遺덈윭�ㅼ씠湲� <a href="#"><img src="�대�吏�.gif" onClick="layer_onoff_1()"></a>

