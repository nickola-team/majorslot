// sk_tab 2020��02��


// 移댁���, �щ’
function sk_tab_title_00(obj) {
	var tab = obj.closest(".popup_tab > li");  //ysk li �대옒�ㅻ챸
	tab.siblings().removeClass("sk_tab_active_00");
	tab.addClass("sk_tab_active_00");

	var target = $(tab.attr("data-target")+".sk_tab_con_00");
	$(".sk_tab_con_00").addClass("sk_tab_hidden_00");
	target.removeClass("sk_tab_hidden_00");
}


// �낃툑�좎껌, 異쒓툑�좎껌, 癒몃땲�대룞, 荑좏룿�좎껌, 肄ㅽ봽�좎껌, �대깽��, 怨듭��ы빆
function sk_tab_title_01(obj) {
	var tab = obj.closest(".popup_tab > li");  //ysk li �대옒�ㅻ챸
	tab.siblings().removeClass("sk_tab_active_01");
	tab.addClass("sk_tab_active_01");

	var target = $(tab.attr("data-target")+".sk_tab_con_01");
	$(".sk_tab_con_01").addClass("sk_tab_hidden_01");
	target.removeClass("sk_tab_hidden_01");
}


// 留덉씠�섏씠吏�, �뺣낫�섏젙, �낃툑/異쒓툑�댁뿭, 吏��몄튇援щぉ濡�, 異쒖꽍/泥댄겕紐⑸줉, 履쎌���
function sk_tab_title_02(obj) {
	var tab = obj.closest(".popup_tab > li");  //ysk li �대옒�ㅻ챸
	tab.siblings().removeClass("sk_tab_active_02");
	tab.addClass("sk_tab_active_02");

	var target = $(tab.attr("data-target")+".sk_tab_con_02");
	$(".sk_tab_con_02").addClass("sk_tab_hidden_02");
	target.removeClass("sk_tab_hidden_02");
}


// 以�鍮꾩쨷
function sk_tab_title_03(obj) {
	var tab = obj.closest(".popup_tab > li");  //ysk li �대옒�ㅻ챸
	tab.siblings().removeClass("sk_tab_active_03");
	tab.addClass("sk_tab_active_03");

	var target = $(tab.attr("data-target")+".sk_tab_con_03");
	$(".sk_tab_con_03").addClass("sk_tab_hidden_03");
	target.removeClass("sk_tab_hidden_03");
}


// 寃뚯엫�댁슜諛⑸쾿
function sk_tab_title_04(obj) {
	var tab = obj.closest(".popup_tab > li");  //ysk li �대옒�ㅻ챸
	tab.siblings().removeClass("sk_tab_active_04");
	tab.addClass("sk_tab_active_04");

	var target = $(tab.attr("data-target")+".sk_tab_con_04");
	$(".sk_tab_con_04").addClass("sk_tab_hidden_04");
	target.removeClass("sk_tab_hidden_04");
}


// 硫붿씤�� ��씠 援ъ꽦�� �붿옄�� �곹솴�쇰븣 (�쇱씠釉뚯뭅吏���, �명뀛移댁���, �щ’寃뚯엫�� 由ъ뒪�몃줈 肉뚮젮吏� �뺥깭�� 援ъ“)
function sk_tab_title_10(obj) {
	var tab = obj.closest(".main_popup_tab > li");  //ysk li �대옒�ㅻ챸
	tab.siblings().removeClass("sk_tab_active_10");
	tab.addClass("sk_tab_active_10");

	var target = $(tab.attr("data-target")+".sk_tab_con_10");
	$(".sk_tab_con_10").addClass("sk_tab_hidden_10");
	target.removeClass("sk_tab_hidden_10");
}











