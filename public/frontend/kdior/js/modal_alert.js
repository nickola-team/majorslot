class ModalAlert{

	constructor() { 
		this.arrSkippedMsg = []; //�대� �꾩슫 �뚮┝ ���ν빐�� 諛곗뿴
	}


	/*
	isCanShow(idx){	//異쒕젰�대룄 �섎뒗吏� �뺤씤�섎뒗 硫붿냼��
		let result = this.arrSkippedMsg.indexOf(idx);	//�꾨떖�� idx媛� skip 由ъ뒪�몄뿉 �덈뒗吏� 李얜뒗��.

		if (result == -1) return true;	//紐살갼�쇰㈃ true(�꾩썙�꾨맖)
		else return false;	//李얠쑝硫� false
	}
	*/


	isOpenAlert(){
		if($("#pop_alert").length) return true;
		else return false;
		 
	}
		
	openAlert(data){
		//this.arrSkippedMsg.push(data.idx);	//�쒕쾲 �꾩슫 �뚮┝�� skip 由ъ뒪�몄뿉 ���ν븳��.

		if(!$("#pop_alert").length){	//�대� �좎엳�붿븣由쇱씠 �덉쑝硫� �덉갹�� 留뚮뱾吏� �딄퀬, �댁슜留� 諛붽퓞

			let html = "<div id='pop_alert' style='opacity:0; display:none; position:fixed; background-color:#00000066; width:100%; height:100%; top:0px; left:0px; vertical-align:bottom; z-index:2147483648;'><table id='pop_alert_table' style='position:fixed; top:0px; bottom:0px; width:100%; height:100%;'><tr><td style='text-align:center;'><table style='display:inline-table; width:300px; background-color:#FFFFFF; border-radius:10px;'><tr><td class='title' style='height:50px; padding:20px 20px 20px 20px; white-space:nowrap; font-size:16px; font-weight:bold; color:#333333; text-align:left;'>�쒕ぉ</td></tr><tr><td class='content' style='padding:10px 20px 0px 20px; text-align:left; color:#333333; font-size:14px; '>�댁슜</td></tr><tr><td style='padding:20px 20px 10px 20px; text-align:center;'><input type='button' class='bt1' style='padding:7px 25px 7px 25px; font-size:14px; background-color:#b79057; border-radius:100px; border:1px solid #ba955e; cursor:pointer; color:#ffffff; font-weight:bold;' value='踰꾪듉1' onclick=''></td></tr></table></td></tr></table></div>";
			
			$('body').append(html);
			console.log('add');
		}
		else{
			console.log('change');

		}

		
/*
		html += "<div id='pop_alert' style='opacity:0; display:none; position:fixed; background-color:#00000066; width:100%; height:100%; top:0px; left:0px; vertical-align:bottom; z-index:2147483648;'>";
			html += "<table id='pop_alert_table' style='position:fixed; bottom:0px; width:100%; height:100%;'>";
				html += "<tr>";
				html += "<td style='text-align:center;'>";


					html += "<table style='display:inline-table; width:300px; background-color:#FFFFFF; border-radius:10px;'>";
						html += "<tr>";
							html += "<td class='title' style='height:50px; padding:0px 20px 0px 20px; white-space:nowrap; font-size:14px; font-weight:bold; color:#333333;'>�쒕ぉ</td>";
						html += "</tr>";
						html += "<tr>";
							html += "<td class='content' style='padding:10px 20px 0px 20px; text-align:left; color:#333333; font-size:14px; '>�댁슜</td>";
						html += "</tr>";
						html += "<tr>";
						html += "<td style='padding:20px 20px 10px 20px; text-align:center;'>";
							html += "<input type='button' class='bt1' style='padding:7px 25px 7px 25px; background:linear-gradient(to right, #ba955e, #e0dbb1); border-radius:3px; border:1px solid #ba955e; cursor:pointer; color:#000000; font-weight:bold;' value='踰꾪듉1' onclick=''>";
						html += "</td>";
						html += "</tr>";
					html += "</table>";


				html += "</td>";
				html += "</tr>";
			html += "</table>";
		html += "</div>";
*/
		

		
		$("#pop_alert_table").find(".title").html(data.title);
		$("#pop_alert_table").find(".content").html(data.content);
		$("#pop_alert_table").find(".bt1").val(data.bt1_text);
		$("#pop_alert_table").find(".bt1").click(data.bt1_func);

		
		
		$("#pop_alert").css("opacity", "0");
		$("#pop_alert_table").css("bottom", "-30px");
		$("#pop_alert_table").css("opacity", "0");
		
		$("#pop_alert").css("display", "block");
		$("#pop_alert_table").css("display", "table");
		
		$("#pop_alert").stop().animate( { "opacity":"1" }, 400);
		$("#pop_alert_table").stop().animate( { "bottom":"0px", "opacity":"1" }, 400, 
			function(){
				
			}
		);
	}


	closeAlert(){	//�リ린
		$("#pop_alert").stop().animate( { "opacity":"0" }, 400,);
		$("#pop_alert_table").stop().animate( { "bottom":"-30px", "opacity":"0" }, 400, 
			function(){
				$("#pop_alert").css("display", "none");
				$("#pop_alert_table").css("display", "none");
				$("#pop_alert").remove();
			}
		);
	}

}