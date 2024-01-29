//콤마찍기
function comma(str) {
	str = String(str);
	return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
}
 
//콤마풀기
function uncomma(str) {
	str = String(str);
	return str.replace(/[^\d]+/g, '');
}
 
//값 입력시 콤마찍기
function inputNumberFormat(obj) {
	obj.value = comma(uncomma(obj.value));
}

//숫자만 입력
function numCheck(evt) {
	var code = evt.which?evt.which:event.keyCode;
	if(code < 48 || code > 57) {
		return false;
	}
}

function InfoUser(){

	$.ajax({
		type: "GET",
		url: "../_proc/_info.asp?" + Math.random(),
		cache: false,
		async : false,
		
		beforeSend: function(){
			
		},
		success: function(response, status){
			
			if(response.result == 'success'){
				$('.userID').val(response.data.player_id).html(response.data.player_id);
				$(".userAccountNumber").val(response.data.account_number).html(response.data.account_number);
				$(".userAccountHolder").val(response.data.account_holder).html(response.data.account_holder);
				$(".userBankName").val(response.data.bank_name).html(response.data.bank_name);
				$(".userName").val(response.data.player_name).html(response.data.player_name);
				$("#UserInfo").data('accountholder', response.data.account_holder).data('checkedupdate', response.data.checked_update).data('moneymoveused', response.data.moneymove_used);
				
				$("#UserInfo").data('bankinfo', response.data.account_holder + '^' + response.data.bank_name + '^' + response.data.account_number);
				
				$("#UserCompPer").html(response.data.comp_set);
			}
			
		},
		error: function(err, xhr){
			
		}
	});
}

