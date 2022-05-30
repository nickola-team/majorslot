$(document).ready(function(){
	$.datepicker.regional['ko'] = {
		closeText: "�リ린",
		prevText: "�댁쟾��",
		nextText: "�ㅼ쓬��",
		currentText: "�ㅻ뒛",
		monthNames: ["1��","2��","3��","4��","5��","6��","7��","8��","9��","10��","11��","12��"],
		monthNamesShort: ["1��","2��","3��","4��","5��","6��","7��","8��","9��","10��","11��","12��"],
		dayNames: ["��","��","��","��","紐�","湲�","��"],
		dayNamesShort: ["��","��","��","��","紐�","湲�","��"],
		dayNamesMin: ["��","��","��","��","紐�","湲�","��"],
		dateFormat: "yy-mm-dd",
		showMonthAfterYear: true,
		yearSuffix: "��"
	};
	// added by oh7even
	$.datepicker.setDefaults($.datepicker.regional['ko']);
	$( "#tbxStartDate" ).datepicker();
	$( "#tbxEndDate" ).datepicker();
	$( ".insert_game_cal" ).datepicker();
	$( "a.add_next_insert" ).button();
	$( "#btn_submit" ).button();
});

function html_auto_br(obj) {
    if (obj.checked) {
        write = confirm("�먮룞 以꾨컮轅덉쓣 �섏떆寃좎뒿�덇퉴?\n\n�먮룞 以꾨컮轅덉� 寃뚯떆臾� �댁슜以� 以꾨컮�� 怨녹쓣<br>�쒓렇濡� 蹂��섑븯�� 湲곕뒫�낅땲��.");
        if (write)
            obj.value = "html2";
        else
            obj.value = "html1";
    }
    else
        obj.value = "";
}

function check_key() {
 var char_ASCII = event.keyCode;

	//�レ옄 -+ �놁씠 �レ옄留�
	if ((char_ASCII >= 48 && char_ASCII <= 57 ) || (char_ASCII == 46) || (char_ASCII == 43) || (char_ASCII == 45))
	   return 1;
	 //�곸뼱
	 else if ((char_ASCII>=65 && char_ASCII<=90) || (char_ASCII>=97 && char_ASCII<=122))
		return 2;
	 //�뱀닔湲고샇
	 else if ((char_ASCII>=33 && char_ASCII<=47) || (char_ASCII>=58 && char_ASCII<=64)
	   || (char_ASCII>=91 && char_ASCII<=96) || (char_ASCII>=123 && char_ASCII<=126))
		return 4;
	 //�쒓�
	 else if ((char_ASCII >= 12592) || (char_ASCII <= 12687))
		return 3;
	//�レ옄 -+ �놁씠 �レ옄留�
	 else if ((char_ASCII >= 48 && char_ASCII <= 57 ) || (char_ASCII == 8))
	   return 5;
		
	 else
		return 0;
}

//�띿뒪�� 諛뺤뒪�� �レ옄�� �곷Ц留� �낅젰�좎닔�덈룄濡�

function nonHangulSpecialKey() {

 if(check_key() != 5 && check_key() != 2) {
  event.returnValue = false;
  alert("�レ옄�� �곷Ц�먮쭔 �낅젰�섏꽭��!");
  return;
 }
}

//�띿뒪�� 諛뺤뒪�� �レ옄留� �낅젰�좎닔 �덈룄濡�
function numberKey() {
	 if(check_key() != 1 ) {
	  event.returnValue = false;
	  //alert("�レ옄留� �낅젰�� �� �덉뒿�덈떎.");
	  return;
	 }
}

function pure_numberKey() {
	var char_ASCII = event.keyCode;
	if ((char_ASCII >= 48 && char_ASCII <= 57 ) || (char_ASCII == 8)){
		return true;
	} else {
		alert("�レ옄留� �낅젰�� �� �덉뒿�덈떎.");
		return;		
	}
}

function IsNumber(obj) {
	if (isNaN(obj.value)) {
		alert('�レ옄留� �낅젰�섏떗�쒖삤');
		obj.value = '';
		obj.focus();
		obj.select();
	}
}

function MoneyFormat(str)
{
	var re="";
	str = str + "";
	str=str.replace(/-/gi,"");
	str=str.replace(/ /gi,"");
	
	str2=str.replace(/-/gi,"");
	str2=str2.replace(/,/gi,"");
	str2=str2.replace(/\./gi,"");	
	
	if(isNaN(str2) && str!="-") return "";
	try
	{
		for(var i=0;i<str2.length;i++)
		{
			var c = str2.substring(str2.length-1-i,str2.length-i);
			re = c + re;
			if(i%3==2 && i<str2.length-1) re = "," + re;
		}
		
	}catch(e)
	{
		re="";
	}
	
	if(str.indexOf("-")==0)
	{
		re = "-" + re;
	}

	return re;
}

function hourKey(str) {
    if(Number(str.value) > 23){
    	alert('1 遺��� 23 �댄븯留� �낅젰�� �� �덉뒿�덈떎.');
    	str.value = "";
    	str.focus();
    	return;
    }else if (str.value.length == 1){
		str.value = "0" + str.value +"";
    }
}
function minKey(str) {
    if(Number(str.value) > 60){
    	alert('1 遺��� 59 �댄븯留� �낅젰�� �� �덉뒿�덈떎.');
    	str.value = "";
    	str.focus();
    	return;
    }else if (str.value.length == 1){
		str.value = "0" + str.value +"";
    }
}

function vs(obj, elName){
	if(obj.checked){
		$j('#'+elName).val('-');
	}else{
		$j('#'+elName).val('');
	}
}
function bet_alert(title,msg,type,showCancelButton,showLoaderOnConfirm,customfunction ){
	swal({
		title: title,
		text:msg,
		type:type,
		showCancelButton:showCancelButton,
		showLoaderOnConfirm:showLoaderOnConfirm,
		closeOnConfirm: false,
		html:true,
	},
	customfunction);		
}
function bet_alert_e(msg)
{
	return bet_alert('�뚮엺',msg,'error');
}
function sCustomScroll(){
	$('.scroll').mCustomScrollbar({
		axis:"x",
		theme:"dark-thin",
		autoExpandScrollbar:true,
		advanced:{autoExpandHorizontalScroll:true},
		scrollButtons:{enable:true},
		scrollInertia:400,
		mouseWheel:{ enable: false },
		//advanced:{ updateOnContentResize: true },
		scrollbarPosition:"inside" ,
		setLeft: "500000px" ,
	});
}