$(document).ready(function() {




});



function open_modal(type, page = 0) {
    
    if (type == "reg" || type == "login") {

        $(".modal." + type + "Modal").modal();

    } else {
        if(is_login == 'N')
        {
            alert("로그인 후 사용하세요");
            return;
        }
        $modal = $(".modal.layoutModal");
        bannerhtml = ``;
        bodyhtml = ``;
        if(type == 'deposit'){            
            bannerhtml = `<div class="panel">
                    <div class="title-panel"><h1 class="title">입금신청</h1></div>
                    <div class="modal-menu">
                        <button class="login-link active" onclick="open_modal('deposit');">
                            <div class="icon-panel">
                                <i class="fas fa-piggy-bank"></i>
                            </div>
                            <span>입금신청</span>
                        </button>
                        <button class="login-link " onclick="open_modal('withdraw');">
                            <div class="icon-panel">
                                <i class="fas fa-university"></i>
                            </div>
                            <span>출금신청</span>
                        </button>
                        <button class="join-link " onclick="open_modal('point_list');">
                            <div class="icon-panel">
                                <i class="fas fa-list"></i>
                            </div>
                            <span>포인트내역</span>
                        </button>
                        <button class="login-link " onclick="open_modal('notice');">
                            <div class="icon-panel">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            <span>공지사항</span>
                        </button>
                        <button class="join-link " onclick="open_modal('event');">
                            <div class="icon-panel">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <span>이벤트</span>
                        </button>
                        <button class="login-link " onclick="open_modal('qna');">
                            <div class="icon-panel">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <span>고객센터</span>
                        </button>
                        <button class="login-link " onclick="open_modal('msg');">
                            <div class="icon-panel">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <span>쪽지</span>
                        </button>

                    </div>
                    <div class="information fs-lg">
                        <span><i class="fas fa-exclamation"></i> 보이스피싱 및 3자사기로 협박하더라도 협상 및 타협 절대없음</span><br>
                    </div>
                    <div class="information">
                        <span><i class="fas fa-check"></i> 계좌거래시 입금/출금 이름이 동일해야 입출금이 가능하오니 정확히 입력해 주세요.</span><br>
                        <span><i class="fas fa-check"></i> 수표나 타인계좌로 이름변경하여 입금시 머니몰수 및 아이디정지 처리되오니 타인명의로 입금해야될시 문의를 먼저하시기 바랍니다</span>
                        
                    </div>
                </div>`;

                bodyhtml=`<div class="modal-panel">

					<a class="link-coupon" href="#">※ 나의 입금계좌 확인 ※ </a>
					<style>

						div.deposit-table {
							width: 65%;
						}
						div.deposit-info{
							float: right;
							width: 35%;
						    display: block;
						    background-color: rgba(0, 0, 0, .5);
						}

						div.deposit-info span.info{
						    padding: 10px;
						    display: table-cell;
						    vertical-align: middle;
						    text-align: left;
						}

						a {color: #fcfb26;}

						.bs-table tr td a:hover {color: #fff;}


						@media(max-width:1260px) {
							div.deposit-table {
								width: 100%;
								max-width: 100%;
							}
							div.deposit-info{
								float: right;
								width: 100%;
							    display: block;
							}
						}

						

					</style>
                    <div class="board-table">
                    	<form name="deposit" method="POST">
                    		<div class="form-container">
                    			<div class="deposit-info"><span class="info"><p><span style="font-size:16px"><span style="color:#ffe400">&nbsp; &nbsp;★ 입금시 주의사항 ★</span></span><br>
<br>
<span style="font-size:14px"><span style="color:#ff0000">■ 1회 최대 1천만원까지 무제한으로&nbsp;입금가능</span></span></p>

<p><span style="font-size:14px"><span style="color:#ff0000">■ 최초 가입시 기재하신 출금 계좌만&nbsp;입금가능</span></span></p>

<p><span style="font-size:14px"><span style="color:#ff0000">■ 은행 어플이 아닌 다른방법&nbsp;입금은 절대불가</span></span></p>

<p><span style="font-size:14px"><span style="color:#ff0000">■ 간편송금(토스이체, 카카오페이 등)입금불가</span></span></p>

<p><span style="font-size:14px"><span style="color:#ff0000">■ 본인명의 다른계좌, 3자명의, ATM입금 불가</span></span></p>

<p><span style="font-size:14px"><span style="color:#ff0000">■ K뱅크,경남,수협,저축,토스,기업등..입금불가</span></span></p>

<p><span style="font-size:14px"><span style="color:#ff0000">■ 카카오 미성년계좌&nbsp;3355, 7979, 7777 불가</span></span><br>
<br>
<span style="color:#00d8ff"><span style="font-size:14px">보안과 안전한 이용을 위해 노력하겠습니다</span></span></p>
</span></div>

                    			<div class="deposit-table">
		                    		<table class="bs-table b-font">
		                    			<tbody>
		                    				<tr>
		                    					<td>아이디</td>
			                    				<td>${username}</td>
		                    				</tr>
		                    				<tr>
		                    					<td>입금계좌</td>
			                    				<td class="v_account"><a href="#">※ 나의 입금계좌 확인 ※ </a><!--  --></td>
		                    				</tr>
		                    				<tr>
		                    					<td>신청금액</td>
			                    				<td>
						                    		<div class="form-group">
							                            <div class="infos">
							                                <div class="input-container">
							                                    <input type="text" name="apply_amount" onkeyup="check_num_format(this);" placeholder="0" class="money">
							                                    <i class="fas fa-money-check  icon-label"></i>
							                                </div>
								                        </div>
								                    </div>
									            </td>
		                    				</tr>

		                    				<tr>
		                    					<td>게임타입</td>
			                    				<td style="text-align: left; padding-left: 30px;">
				                                	<label for="b_bonus"><input type="radio" id="b_bonus" name="game_type" onclick="change_bonus_list('b_b');">카지노</label>&nbsp;
				                                	<label for="s_bonus"><input type="radio" id="s_bonus" name="game_type" onclick="change_bonus_list('s_b');">슬롯</label>
									            </td>

		                    				</tr>

		                    				<tr>
		                    					<td>보너스</td>
			                    				<td>
						                    		<div class="form-group">
							                            <div class="infos">
							                                <div class="input-container">
							                                	<select name="bonus_type" class="b_b" style="display:none;">
							                                		<option value="BF">카지노첫충전 10% (롤링 300%)</option>
							                                		<option value="N">보너스 미선택 (롤링 100%)</option>
							                                	</select>

							                                	<select name="bonus_type" class="s_b" style="display:none;">
							                                		<option value="SF">슬롯첫충전 15% (롤링 300%)</option>
							                                		<option value="N">보너스 미선택 (롤링 100%)</option>
							                                	</select>
							                                </div>
							                            </div>
							                        </div>
									            </td>

		                    				</tr>
		                    				<tr>
									            <td colspan="2">
						                    		<div class="form-group">	                                    
						                                <div class="infos">
						                                    <div class="btn-grp">
						                                        <button type="button" onclick="reprice_point(10000);">1만원</button>
						                                        <button type="button" onclick="reprice_point(50000);">5만원</button>
						                                        <button type="button" onclick="reprice_point(100000);">10만원</button>
						                                        <button type="button" onclick="reprice_point(500000);">50만원</button>
						                                        <button type="button" onclick="reprice_point(1000000);">100만원</button>
						                                        <button type="button" onclick="reprice_point(5000000);">500만원</button>
						                                        <button type="button" onclick="reprice_point(10000000);">1000만원</button>
						                                        <button type="button" onclick="reprice_point(0);">정정</button>
						                                    </div>
						                                </div>
						                            </div>
			                    				</td>
			                    			</tr>

		                    			</tbody>
		                    		</table>

		                    		<div class="form-footer">
										<button type="button" class="yellow-bg join-link" onclick="depositRequest();">
											<span><i class="fas fa-check"></i> 충전신청</span>
										</button>
										<button type="button" class="green-bg join-link" onclick="open_modal('deposit_list');">
											<span><i class="fas fa-check"></i> 입금내역</span>
										</button>
									</div>
		                    	</div>
	                    	</div>
						</form>
                    </div>

                    
                </div>`;
                
        }else if(type == 'withdraw'){
            bannerhtml = `<div class="panel">
                    <div class="title-panel"><h1 class="title">출금신청</h1></div>
                    <div class="modal-menu">
                        <button class="login-link " onclick="open_modal('deposit');">
                            <div class="icon-panel">
                                <i class="fas fa-piggy-bank"></i>
                            </div>
                            <span>입금신청</span>
                        </button>
                        <button class="login-link active" onclick="open_modal('withdraw');">
                            <div class="icon-panel">
                                <i class="fas fa-university"></i>
                            </div>
                            <span>출금신청</span>
                        </button>
                        <button class="join-link " onclick="open_modal('point_list');">
                            <div class="icon-panel">
                                <i class="fas fa-list"></i>
                            </div>
                            <span>포인트내역</span>
                        </button>
                        <button class="login-link " onclick="open_modal('notice');">
                            <div class="icon-panel">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            <span>공지사항</span>
                        </button>
                        <button class="join-link " onclick="open_modal('event');">
                            <div class="icon-panel">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <span>이벤트</span>
                        </button>
                        <button class="login-link " onclick="open_modal('qna');">
                            <div class="icon-panel">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <span>고객센터</span>
                        </button>
                        <button class="login-link " onclick="open_modal('msg');">
                            <div class="icon-panel">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <span>쪽지</span>
                        </button>
                        <!-- <button class="login-link " onClick="open_modal('myinfo');">
                            <div class="icon-panel">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <span>마이페이지</span>
                        </button>
                        <button class="join-link " onClick="open_modal('update_info');">
                        <div class="icon-panel">
                            <i class="fas fa-wrench"></i>
                        </div>
                        <span>정보수정</span>
                        </button> -->

                    </div>
                    <div class="information">
                        <span><i class="fas fa-check"></i> 출금은 최소 1만원이상 출금신청 해주시기 바랍니다.</span><br>
                        <span><i class="fas fa-check"></i> 출금은 입금 신청하실때 신청하신 계좌로만 출금이 가능합니다.</span>
                        
                    </div>
                </div>`;
                bodyhtml=`<div class="modal-panel">
                    <div class="board-table">
                    	<form name="withdraw" method="POST">
                    		<div class="form-container">
	                    		<table class="bs-table b-font">
	                    			<tbody>
	                    				<tr>
											<td>보유머니</td>
											<td>
												${userbalance} 원
											</td>
										</tr>
										<tr>
											<td>아이디</td>
											<td>${username}</td>
										</tr>
										<tr>
	                    					<td>예금주</td>
		                    				<td>${recommendername}
								            </td>
	                    				</tr>
	                    				<tr>
	                    					<td>은행</td>
		                    				<td>${userbank}</td>
	                    				</tr>
	                    				<tr>
	                    					<td>계좌번호</td>
		                    				<td>${accountno}</td>
	                    				</tr>
	                    				<tr>
	                    					<td>환전비밀번호</td>
		                    				<td>
					                    		<div class="form-group">
						                            <div class="infos">
						                                <div class="input-container">
						                                    <input type="password" name="e_pass" placeholder="2차 비밀번호" disabled>
						                                    <i class="fas fa-unlock-alt  icon-label"></i>
						                                </div>
							                        </div>
							                    </div>
								            </td>
	                    				</tr>
	                    				<tr>
	                    					<td>신청금액</td>
		                    				<td>
					                    		<div class="form-group">
						                            <div class="infos">
						                                <div class="input-container">
						                                    <input type="text" name="apply_amount" onkeyup="check_num_format(this);" placeholder="0" class="exchange_money">
						                                    <i class="fas fa-money-check icon-label"></i>
						                                </div>
							                        </div>
							                    </div>
								            </td>
	                    				</tr>
	                    				<tr>
								            <td colspan="2">
					                    		<div class="form-group">	                                    
					                                <div class="infos">
					                                    <div class="btn-grp">
					                                        <button type="button" onclick="reprice_point(10000);">1만원</button>
					                                        <button type="button" onclick="reprice_point(50000);">5만원</button>
					                                        <button type="button" onclick="reprice_point(100000);">10만원</button>
					                                        <button type="button" onclick="reprice_point(500000);">50만원</button>
					                                        <button type="button" onclick="reprice_point(1000000);">100만원</button>
					                                        <button type="button" onclick="reprice_point(5000000);">500만원</button>
					                                        <button type="button" onclick="reprice_point(10000000);">1000만원</button>
					                                        <button type="button" onclick="reprice_point(0);">정정</button>
					                                    </div>
					                                </div>
					                            </div>
		                    				</td>
		                    			</tr>                                                          
									</tbody>
								</table>
							</div>
							<div class="form-footer">
								<button type="button" class="yellow-bg join-link" onclick="withdrawRequest();">
									<span><i class="fas fa-check"></i>출금신청</span>
								</button>
								<button type="button" class="green-bg join-link" onclick="open_modal('withdraw_list');">
									<span><i class="fas fa-check"></i> 출금내역</span>
								</button>
							</div>
						</form>
					</div>
					
				</div>`;
        }else if(type == 'point_list'){
            bannerhtml = `<div class="panel">
                    <div class="title-panel"><h1 class="title">포인트내역</h1></div>
                    <div class="modal-menu">
                        <button class="login-link " onclick="open_modal('deposit');">
                            <div class="icon-panel">
                                <i class="fas fa-piggy-bank"></i>
                            </div>
                            <span>입금신청</span>
                        </button>
                        <button class="login-link " onclick="open_modal('withdraw');">
                            <div class="icon-panel">
                                <i class="fas fa-university"></i>
                            </div>
                            <span>출금신청</span>
                        </button>
                        <button class="join-link active" onclick="open_modal('point_list');">
                            <div class="icon-panel">
                                <i class="fas fa-list"></i>
                            </div>
                            <span>포인트내역</span>
                        </button>
                        <button class="login-link " onclick="open_modal('notice');">
                            <div class="icon-panel">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            <span>공지사항</span>
                        </button>
                        <button class="join-link " onclick="open_modal('event');">
                            <div class="icon-panel">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <span>이벤트</span>
                        </button>
                        <button class="login-link " onclick="open_modal('qna');">
                            <div class="icon-panel">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <span>고객센터</span>
                        </button>
                        <button class="login-link " onclick="open_modal('msg');">
                            <div class="icon-panel">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <span>쪽지</span>
                        </button>
                        <!-- <button class="login-link " onClick="open_modal('myinfo');">
                            <div class="icon-panel">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <span>마이페이지</span>
                        </button>
                        <button class="join-link " onClick="open_modal('update_info');">
                        <div class="icon-panel">
                            <i class="fas fa-wrench"></i>
                        </div>
                        <span>정보수정</span>
                        </button> -->

                    </div>
                </div>`;
                bodyhtml = `<div class="modal-panel">
					<div>
						<table class="bs-table">
							<thead>
								<tr>
									<th width="25px"><input type="checkbox" id="ckbs"></th>
									<th class="list_title1">포인트종류</th>
									<th width="20%" class="list_title1">일시</th>
									<th width="15%" class="list_title1">이전포인트</th>
									<th width="15%" class="list_title1">포인트금액</th>
									<th width="15%" class="list_title1">이후포인트</th>
								</tr> 
							</thead>
							<tbody>
								

							</tbody>
						</table>
						<div class="form-footer">
							<button type="button" class="yellow-bg" onclick=""><span><i class="fas fa-check"></i>선택삭제</span></button>
						</div>
					</div>
					<div></div>
				</div>`;
        }else if(type == 'notice'){
            bannerhtml = `<div class="panel">
                    <div class="title-panel"><h1 class="title">공지사항</h1></div>
                    <div class="modal-menu">
                        <button class="login-link " onclick="open_modal('deposit');">
                            <div class="icon-panel">
                                <i class="fas fa-piggy-bank"></i>
                            </div>
                            <span>입금신청</span>
                        </button>
                        <button class="login-link " onclick="open_modal('withdraw');">
                            <div class="icon-panel">
                                <i class="fas fa-university"></i>
                            </div>
                            <span>출금신청</span>
                        </button>
                        <button class="join-link " onclick="open_modal('point_list');">
                            <div class="icon-panel">
                                <i class="fas fa-list"></i>
                            </div>
                            <span>포인트내역</span>
                        </button>
                        <button class="login-link active" onclick="open_modal('notice');">
                            <div class="icon-panel">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            <span>공지사항</span>
                        </button>
                        <button class="join-link " onclick="open_modal('event');">
                            <div class="icon-panel">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <span>이벤트</span>
                        </button>
                        <button class="login-link " onclick="open_modal('qna');">
                            <div class="icon-panel">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <span>고객센터</span>
                        </button>
                        <button class="login-link " onclick="open_modal('msg');">
                            <div class="icon-panel">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <span>쪽지</span>
                        </button>
                        <!-- <button class="login-link " onClick="open_modal('myinfo');">
                            <div class="icon-panel">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <span>마이페이지</span>
                        </button>
                        <button class="join-link " onClick="open_modal('update_info');">
                        <div class="icon-panel">
                            <i class="fas fa-wrench"></i>
                        </div>
                        <span>정보수정</span>
                        </button> -->

                    </div>
                </div>`;
            bodyhtml = `<div class="modal-panel">
                <div class="board-table">
                    <table class="bs-table">
                        <thead>
                            <tr>
                                <th>제목</th>
                            </tr>
                        </thead>
                        <tbody>`;
                        for (var i = 0; i < noticelist.length; i++) {
                            bodyhtml += `<tr>
                                                <td class="c_title" onclick="view_content_bbs(this);" data-idx="${noticelist[i].id}">
                                                    <p><span style="font-size:14px"><strong>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;<span style="color:#ffd700"> ${noticelist[i].title}</span></strong></span></p>

                                                </td>
                                            </tr>
                                            <tr class="content content_${noticelist[i].id}" style="display: none;">
                                                <td class="c_content">
                                                    ${noticelist[i].content}
                                                </td>
                                            </tr>`;
                        }
                        bodyhtml += `	</tbody>
						</table>
					</div>
					<div></div>

				</div>`;

        }else if(type == 'event'){
            bannerhtml = `<div class="panel">
                    <div class="title-panel"><h1 class="title">이벤트</h1></div>
                    <div class="modal-menu">
                        <button class="login-link " onclick="open_modal('deposit');">
                            <div class="icon-panel">
                                <i class="fas fa-piggy-bank"></i>
                            </div>
                            <span>입금신청</span>
                        </button>
                        <button class="login-link " onclick="open_modal('withdraw');">
                            <div class="icon-panel">
                                <i class="fas fa-university"></i>
                            </div>
                            <span>출금신청</span>
                        </button>
                        <button class="join-link " onclick="open_modal('point_list');">
                            <div class="icon-panel">
                                <i class="fas fa-list"></i>
                            </div>
                            <span>포인트내역</span>
                        </button>
                        <button class="login-link " onclick="open_modal('notice');">
                            <div class="icon-panel">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            <span>공지사항</span>
                        </button>
                        <button class="join-link active" onclick="open_modal('event');">
                            <div class="icon-panel">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <span>이벤트</span>
                        </button>
                        <button class="login-link " onclick="open_modal('qna');">
                            <div class="icon-panel">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <span>고객센터</span>
                        </button>
                        <button class="login-link " onclick="open_modal('msg');">
                            <div class="icon-panel">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <span>쪽지</span>
                        </button>
                        <!-- <button class="login-link " onClick="open_modal('myinfo');">
                            <div class="icon-panel">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <span>마이페이지</span>
                        </button>
                        <button class="join-link " onClick="open_modal('update_info');">
                        <div class="icon-panel">
                            <i class="fas fa-wrench"></i>
                        </div>
                        <span>정보수정</span>
                        </button> -->

                    </div>
                </div>`;
                bodyhtml = `<div class="modal-panel">
					<div class="board-table">
						<table class="bs-table">
							<thead>
								<tr>
									<th>제목</th>
								</tr>
							</thead>
							<tbody>

								<tr>
									<td class="c_title" onclick="view_content_bbs(this);" data-idx="9725">
										 <p><span style="color:#daa520">&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;<span style="font-size:14px">[ EVENT ]&nbsp;☞ 첫충, 매충전 포인트지급 이벤트</span></span></p>

									</td>
								</tr>
								<tr class="content content_9725" style="display: none;">
									<td class="c_content">
										<p style="text-align:center"><br>
                                        <br>
                                        <br>
                                        <br>
                                        <span style="font-size:16px">안녕하세요&nbsp;<span style="color:#99ccff">♠CASINO♠ 관리자</span>&nbsp;입니다</span></p>

                                        <p style="text-align:center"><br>
                                        <br>
                                        <span style="font-size:16px">회원님들께서 보내주신 성원에&nbsp;감사하는 마음을&nbsp;보답하고자&nbsp;<br>
                                        <br>
                                        충전 포인트 지급 이벤트를 준비하였으니 많은이용 바랍니다</span></p>

                                        <p style="text-align:center"><br>
                                        <br>
                                        <br>
                                        &nbsp;</p>

                                        <p style="text-align:center"><span style="font-size:20px"><span style="color:#ff33ff">★&nbsp;첫충, 매충전 이벤트 안내&nbsp;★</span></span><br>
                                        &nbsp;</p>

                                        <p style="text-align:center"><br>
                                        <br>
                                        <br>
                                        <span style="font-size:18px"><strong><span style="color:#ffffff"><span style="background-color:#ff0000">카지노 충전 이벤트</span></span></strong></span><br>
                                        <br>
                                        <span style="font-size:16px">매일 첫 입금시&nbsp;</span><span style="color:#00ffff"><span style="font-size:28px">10%</span></span><span style="font-size:16px"><span style="font-size:28px">&nbsp;</span>, 추가 입금시&nbsp;&nbsp;</span><span style="color:#ff8c00"><span style="font-size:28px">5%&nbsp;</span></span><span style="font-size:16px">추가 지급</span><br>
                                        <br>
                                        <span style="font-size:18px">00시 기준 1일 최대&nbsp;20만원&nbsp;까지 무한지급 !!</span><br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <strong><span style="color:#ffffff"><span style="font-size:18px"><span style="background-color:#ff0000">슬롯게임 충전이벤트</span></span></span></strong><br>
                                        <br>
                                        <span style="font-size:16px">매일 첫 입금시&nbsp;</span><span style="color:#00ffff"><span style="font-size:28px">15%</span></span><span style="font-size:16px"><span style="font-size:28px">&nbsp;</span>, 추가 입금시&nbsp; </span><span style="color:#ff8c00"><span style="font-size:28px">5%</span></span><span style="font-size:16px"> 추가 지급</span><br>
                                        <br>
                                        <span style="font-size:18px">00시 기준 1일 최대 20만원&nbsp;까지 무한지급 !!</span><br>
                                        &nbsp;</p>

                                        <p style="text-align:center"><br>
                                        <br>
                                        <span style="font-size:18px"><span style="color:#ff0000">★ 이벤트 주의사항 ★</span></span></p>

                                        <p style="text-align:center"><br>
                                        &nbsp;</p>

                                        <p style="text-align:center"><span style="font-size:16px">※ 당일 00시 기준으로 환전내역 있을&nbsp;경우에는 포인트 지급불가 ※</span><br>
                                        <br>
                                        <span style="color:#ff0000"><span style="font-size:16px">※ 환전가능한 롤링은 입금액+지급받은 포인트합산 300% 입니다 ※</span></span><br>
                                        <br>
                                        <span style="font-size:16px">※ 운영진 판단하에&nbsp;양방,부정배팅으로&nbsp;판단시&nbsp;포인트몰수및 지급제한&nbsp;※</span><br>
                                        <br>
                                        <span style="font-size:16px">※ 1인이 다계정 이용시 1계정 에서만 포인트 지급가능(중복 지급불가)&nbsp;※</span><br>
                                        <br>
                                        <span style="font-size:16px">또한 아이디 대여, 양도, 매매 하시는&nbsp;배팅사무실&nbsp;적발시 배팅리밋 제재<br>
                                        <br>
                                        보유머니 출금제한,&nbsp;이용제재 등 불이익을 받으실수 있으니 참고바랍니다</span></p>

                                        <p style="text-align:center"><br>
                                        <br>
                                        &nbsp;</p>

                                        <p style="text-align:center"><span style="font-size:16px">별도의 공지가 있을때까지 진행예정 이오니 많은참여 부탁드립니다</span></p>

                                        <p style="text-align:center"><br>
                                        <br>
                                        <br>
                                        <br>
                                        &nbsp;</p>

									</td>
								</tr>
								

							</tbody>
						</table>
					</div>
					<div></div>

				</div>`;
        }else if(type == 'qna'){
            bannerhtml = `<div class="panel">
                    <div class="title-panel"><h1 class="title">고객센터</h1></div>
                    <div class="modal-menu">
                        <button class="login-link " onclick="open_modal('deposit');">
                            <div class="icon-panel">
                                <i class="fas fa-piggy-bank"></i>
                            </div>
                            <span>입금신청</span>
                        </button>
                        <button class="login-link " onclick="open_modal('withdraw');">
                            <div class="icon-panel">
                                <i class="fas fa-university"></i>
                            </div>
                            <span>출금신청</span>
                        </button>
                        <button class="join-link " onclick="open_modal('point_list');">
                            <div class="icon-panel">
                                <i class="fas fa-list"></i>
                            </div>
                            <span>포인트내역</span>
                        </button>
                        <button class="login-link " onclick="open_modal('notice');">
                            <div class="icon-panel">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            <span>공지사항</span>
                        </button>
                        <button class="join-link " onclick="open_modal('event');">
                            <div class="icon-panel">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <span>이벤트</span>
                        </button>
                        <button class="login-link active" onclick="open_modal('qna');">
                            <div class="icon-panel">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <span>고객센터</span>
                        </button>
                        <button class="login-link " onclick="open_modal('msg');">
                            <div class="icon-panel">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <span>쪽지</span>
                        </button>
                        <!-- <button class="login-link " onClick="open_modal('myinfo');">
                            <div class="icon-panel">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <span>마이페이지</span>
                        </button>
                        <button class="join-link " onClick="open_modal('update_info');">
                        <div class="icon-panel">
                            <i class="fas fa-wrench"></i>
                        </div>
                        <span>정보수정</span>
                        </button> -->

                    </div>
                </div>`;

            
                            $.ajax({
                                type: "POST",
                                cache: false,
                                async: true,
                                url: '/api/messages',
                                dataType: 'json',
                                success: function(data) {
                                if(data.error == false){
                                    bodyhtml = `<div class="modal-panel">
                                        <div class="board-table">
                                            <div class="form-footer d-button">
                                                <button type="button" class="green-bg" onclick="" data-flag="Y"><span><i class="fas fa-check"></i>전체선택</span></button>
                                                <button type="button" class="red-bg" onclick=""><span><i class="fas fa-check"></i>선택삭제</span></button>
                                            </div>
                                            <table class="bs-table tb_qna">
                                                <colgroup>
                                                    <col width="5%">
                                                    <col width="15%">
                                                    <col width="60%">
                                                    <col width="20%">
                                                </colgroup>
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" id="ckbs"></th>
                                                        <th colspan="2">제목</th>
                                                        <th>작성일</th>
                                                    </tr>
                                                </thead>
                                                <tbody>`;
                                    if (data.data.length > 0) {
                                        for (var i = 0; i < data.data.length; i++) {
                                            date = new Date(data.data[i].created_at);
                                            if (data.data[i].read_at == null)
                                            {
                                                read = '읽지 않음';
                                            }
                                            else
                                            {
                                                read = '읽음';
                                                // date1 = new Date(data.data[i].read_at);
                                                // read = date1.toLocaleString();
                                            }
                                            type = (data.user_id!=data.data[i].writer_id)?'수신':'발신';
                                            bodyhtml += `
                                                <tr>
                                                    <td><input type="checkbox" name="id[]" class="ckb" value="${data.data[i].id}"></td>
                                                    <td colspan="2" class="c_title" onclick="view_content_qna(this);" data-idx="${data.data[i].id}" data-is_read="Y">
                                                        ${data.data[i].title}<span style="color:#74b4eb;"> [${read}]</span>									</td>
                                                    <td>${date.toLocaleString()}</td>
                                                    
                                                </tr>
                                                <tr class="content content_${data.data[i].id}" style="display:none;">
                                                    <td colspan="4" class="c_content w_board">
                                                    ${data.data[i].content}
                                                    </td>
                                                </tr>`;
                                        }
                                        
                                    }
                                       
                                    bodyhtml += `<tr>
									<td colspan="4">
                						<div class="form-container">
				                    		<div class="form-group">	                                    
				                                <div class="infos">
				                                    <div class="btn-grp">
				                                        <button type="button" style="float: initial;" onclick="write_qna(3);">계좌문의</button>
				                                        <button type="button" style="float: initial;" onclick="">전화 상담</button>
				                                        <button type="button" style="float: initial;" onclick="write_qna(0);">기타문의</button>
				                                    </div>
				                                </div>
				                            </div>
				                        </div>
                    				</td>
								</tr>
							</tbody>
						</table>
						<div class="form-footer d-button">
							<button type="button" class="green-bg" onclick="" data-flag="Y"><span><i class="fas fa-check"></i>전체선택</span></button>
							<button type="button" class="red-bg" onclick=""><span><i class="fas fa-check"></i>선택삭제</span></button>
						</div>

						<div class="form_qna" id="form_qna" style="display: none;">
							<form name="qna" method="POST">
		                        <div class="form-container">
		                            <div class="form-group" style="margin-bottom: 20px;">
		                                <div class="labels" style="width:auto;"><span>제목</span></div>
		                                <div class="infos">
		                                    <div class="input-container">
		                                        <input type="text" name="title" id="txt_title" maxlength="10" placeholder="제목을 적어주세요">
		                                        <i class="fas fa-id-badge icon-label"></i>
		                                    </div>
		                                </div>
		                            </div>
		                            <div class="form-group">
		                                <div class="labels" style="width:auto;"><span>문의내용</span></div>
		                                <div class="infos">
		                                	<div class="textarea-container">
		                                		<textarea name="question" id="content_txt" placeholder="내용을 적어주세요"></textarea>
		                                	</div>
		                                </div>
		                            </div>
		                        </div>
								<div class="form-footer">
									<button type="button" class="yellow-bg join-link" onclick="send_text();"><span><i class="fas fa-check"></i>문의하기</span></button>
								</div>
							</form>
						</div>
					</div>
					<div></div>

				</div>`;           
                $modal.find(".modal-body").html(bodyhtml);                                                         
                                } else {
                                    alert(data.msg);
                                }
                            }
                        });

								
        }else if(type == 'msg'){
            bannerhtml = `<div class="panel">
                    <div class="title-panel"><h1 class="title">쪽지</h1></div>
                    <div class="modal-menu">
                        <button class="login-link " onclick="open_modal('deposit');">
                            <div class="icon-panel">
                                <i class="fas fa-piggy-bank"></i>
                            </div>
                            <span>입금신청</span>
                        </button>
                        <button class="login-link " onclick="open_modal('withdraw');">
                            <div class="icon-panel">
                                <i class="fas fa-university"></i>
                            </div>
                            <span>출금신청</span>
                        </button>
                        <button class="join-link " onclick="open_modal('point_list');">
                            <div class="icon-panel">
                                <i class="fas fa-list"></i>
                            </div>
                            <span>포인트내역</span>
                        </button>
                        <button class="login-link " onclick="open_modal('notice');">
                            <div class="icon-panel">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            <span>공지사항</span>
                        </button>
                        <button class="join-link " onclick="open_modal('event');">
                            <div class="icon-panel">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <span>이벤트</span>
                        </button>
                        <button class="login-link " onclick="open_modal('qna');">
                            <div class="icon-panel">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <span>고객센터</span>
                        </button>
                        <button class="login-link active" onclick="open_modal('msg');">
                            <div class="icon-panel">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <span>쪽지</span>
                        </button>

                    </div>
                </div>`;
            bodyhtml = `<div class="modal-panel">
					<div class="board-table">

						<div class="form-footer d-button">
							<button type="button" class="green-bg" onclick="" data-flag="Y"><span><i class="fas fa-check"></i>전체선택</span></button>
							<button type="button" class="red-bg" onclick=""><span><i class="fas fa-check"></i>선택삭제</span></button>
						</div>
						<table class="bs-table">
							<thead>
								<tr>
									<th width="5%"><input type="checkbox" id="ckbs"></th>
									<th width="60%">제목</th>
									<th width="20%">보낸시간</th>
									<th width="15%">상태</th>
								</tr>
							</thead>
							<tbody>

								<tr>
									<td><input type="checkbox" name="id[]" class="ckb" value="3281305"></td>
									<td class="c_title" onclick="view_content_msg(this);" data-idx="3281305" data-is_read="Y">
										<p><span style="color:#ff4655"><span style="font-size:14px"><strong>☆[ BBIN아시아 ]☆ [긴급점검]</strong>
									</span></span></p></td><td>2024-11-13 07:15:55</td>
									<td class="state">
<span style="color:#74b4eb;"> [읽음]</span>									</td>
								</tr>
								<tr class="content content_3281305" style="display: none;">
									<td colspan="4" style="font-size: 14px;">
										<p style="text-align:center"><br><br><br>
<span style="font-size:14px"><span style="font-family:맑은 고딕">안녕하세요~&nbsp;<span style="color:#ffd700">고객지원팀</span> 입니다<br><br><br>
☆ [ BBIN아시아 ]<span style="color:#e1b312">&nbsp;</span>☆ <span style="color:#ff4655">긴급점검</span> 으로 인해 잠시동안<br><br><br>
서비스중단 되오니 잠시&nbsp;다른게임 이용부탁드립니다<br><br><br>
<span style="color:#ffd700">궁금하신점&nbsp;문의사항이 있을시&nbsp;고객센터&nbsp;문의바랍니다<br><br><br>
오늘도 좋은하루 되세요~ 감사합니다</span></span></span></p><br><br>

									</td>
								</tr>
								<tr>
									<td><input type="checkbox" name="id[]" class="ckb" value="3278069"></td>
									<td class="c_title" onclick="view_content_msg(this);" data-idx="3278069" data-is_read="Y">
										<p><span style="color:#33ccff"><span style="font-size:14px"><strong>☆[ ag카가얀 외 6개 게임사 ]☆ [점검완료]</strong>
									</span></span></p></td><td>2024-11-11 13:40:00</td>
									<td class="state">
<span style="color:#74b4eb;"> [읽음]</span>									</td>
								</tr>
								<tr class="content content_3278069" style="display: none;">
									<td colspan="4" style="font-size: 14px;">
										<p style="text-align:center"><br><br><br>
<span style="font-size:14px"><span style="font-family:맑은 고딕">안녕하세요~&nbsp;<span style="color:#ffd700">고객지원팀</span>입니다<br><br><br>
금일 정기점검 진행되었던<span style="color:#3366ff">&nbsp;</span><span style="color:#33ccff">☆ ag카가얀 외 6개 게임사 ☆</span><br><br><br>
위 게임사는&nbsp;정기점검&nbsp;완료되어 정상화 되었습니다<br><br><br>
안정적인 서비스 제공을 위해 더욱 노력하겠습니다<br><br><br>
<span style="color:#ffd700">추가 궁금한사항 있으시면 고객센터로 문의 바랍니다<br><br><br>
오늘도 좋은하루 되세요~ 감사합니다</span></span></span></p><br><br>

									</td>
								</tr>
								<tr>
									<td><input type="checkbox" name="id[]" class="ckb" value="3274834"></td>
									<td class="c_title" onclick="view_content_msg(this);" data-idx="3274834" data-is_read="Y">
										<p><span style="color:#ff4655"><span style="font-size:14px"><strong>☆[ ag카가얀 외 6개 게임사 ]☆ [정기점검]</strong>
									</span></span></p></td><td>2024-11-11 12:11:05</td>
									<td class="state">
<span style="color:#74b4eb;"> [읽음]</span>									</td>
								</tr>
								<tr class="content content_3274834" style="display: none;">
									<td colspan="4" style="font-size: 14px;">
										<p style="text-align:center"><br><br><br>
<span style="font-size:14px"><span style="font-family:맑은 고딕">안녕하세요~&nbsp;<span style="color:#ffd700">고객지원팀</span>입니다<br><br><br>
2024- 11-11&nbsp;(월요일) 게임사 점검공지&nbsp;<br><br><br>
<span style="color:#ff0000">☆[ ag카가얀 외 6개 게임사 ]☆</span> 소요시간 : 12:30&nbsp;~ 15:00<br><br><br>
해당 게임을 이용중이신 회원님들은 점검시간 이전에<br><br><br>
타사게임 또는 환전으로 보유머니&nbsp;이전 부탁드립니다<br><br><br>
점검&nbsp;게임이용시&nbsp;보유머니가 오류&nbsp;발생될수&nbsp;있습니다<br><br><br>
추가연장 점검공지가 없을경우 별도 공지없이 지정된<br><br><br>
시간으로&nbsp;점검완료 되오니 참고하시고 이용바랍니다<br><br><br>
<span style="color:#ffd700">추가 궁금한사항 있으시면 고객센터로 문의 바랍니다<br><br><br>
오늘도 좋은하루 되세요~ 감사합니다</span></span></span></p><br><br>

									</td>
								</tr>
								<tr>
									<td><input type="checkbox" name="id[]" class="ckb" value="3271598"></td>
									<td class="c_title" onclick="view_content_msg(this);" data-idx="3271598" data-is_read="Y">
										<p><span style="color:#33ccff"><span style="font-size:14px"><strong>☆[ PG소프트 ]☆ [점검완료]</strong>
									</span></span></p></td><td>2024-11-09 22:43:43</td>
									<td class="state">
<span style="color:#74b4eb;"> [읽음]</span>									</td>
								</tr>
								<tr class="content content_3271598" style="display: none;">
									<td colspan="4" style="font-size: 14px;">
										<p style="text-align:center"><br><br><br>
<span style="font-family:맑은 고딕"><span style="font-size:14px">안녕하세요~&nbsp;<span style="color:#ffd700">고객지원팀</span> 입니다<br><br><br>
☆ [ PG소프트 ] ☆&nbsp;<span style="color:#33ccff">점검완료</span> 정상화 되었습니다<br><br><br>
정상적으로 이용가능하오니 많은 이용 부탁드립니다<br><br><br>
보다 안정적인 서비스 제공을 위해 노력하겠습니다<br><br><br>
<span style="color:#ffd700">궁금하신&nbsp;사항 있으시면 고객센터로 문의 바랍니다<br><br><br>
오늘도 좋은하루 되세요~ 감사합니다</span></span></span></p><br><br>

									</td>
								</tr>

							</tbody>
						</table>
						<div class="form-footer d-button">
							<button type="button" class="green-bg" onclick="" data-flag="Y"><span><i class="fas fa-check"></i>전체선택</span></button>
							<button type="button" class="red-bg" onclick=""><span><i class="fas fa-check"></i>선택삭제</span></button>
						</div>
					</div>
					<div></div>
					

				</div>`;
        }else if(type == 'deposit_list'){
            bannerhtml = `<div class="panel">
                    <div class="title-panel"><h1 class="title">입금내역</h1></div>
                    <div class="modal-menu">
                        <button class="login-link " onclick="open_modal('deposit');">
                            <div class="icon-panel">
                                <i class="fas fa-piggy-bank"></i>
                            </div>
                            <span>입금신청</span>
                        </button>
                        <button class="login-link " onclick="open_modal('withdraw');">
                            <div class="icon-panel">
                                <i class="fas fa-university"></i>
                            </div>
                            <span>출금신청</span>
                        </button>
                        <button class="join-link " onclick="open_modal('point_list');">
                            <div class="icon-panel">
                                <i class="fas fa-list"></i>
                            </div>
                            <span>포인트내역</span>
                        </button>
                        <button class="login-link " onclick="open_modal('notice');">
                            <div class="icon-panel">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            <span>공지사항</span>
                        </button>
                        <button class="join-link " onclick="open_modal('event');">
                            <div class="icon-panel">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <span>이벤트</span>
                        </button>
                        <button class="login-link " onclick="open_modal('qna');">
                            <div class="icon-panel">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <span>고객센터</span>
                        </button>
                        <button class="login-link " onclick="open_modal('msg');">
                            <div class="icon-panel">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <span>쪽지</span>
                        </button>

                    </div>
                    <div class="information fs-lg">
                        <span><i class="fas fa-exclamation"></i> 5분내 미완료시 자동삭제</span><br>
                        <span><i class="fas fa-exclamation"></i>신청한 내용이 완료되지 않거나 취소되지 않은경우에는 중복신청불가 !!</span><br>
                        
                    </div>
                </div>`;
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: true,
                    url: '/api/inouthistory',
                    dataType: 'json',
                    data : {type: 'add'},
                    success: function(data) {
                      if(data.error == false){
                        bodyhtml = `<div class="modal-panel">
                            <div>
                            <table class="bs-table">
                                <thead>
                                    <tr>
                                        <th width="50px"><input type="checkbox" id="ckbs"></th>
                                        <th class="list_title1">신청종류</th>
                                        <th width="40%" class="list_title1">신청일시</th>
                                        <th width="20%" class="list_title1">신청금액</th>
                                        <th width="20%" class="list_title1">진행상태</th>
                                    </tr> 
                                </thead>
                                <tbody>
                                      `;
                          if (data.data.length > 0) {
                              status_name = {
                                  0 : '승인대기',
                                  1 : '승인완료',
                                  2 : '승인취소'
                               };
                               status_color={
                                0 : 'wait',
                                1 : 'success',
                                2 : 'cancel'
                               }
                              for (var i = 0; i < data.data.length; i++) {
                                  date = new Date(data.data[i].created_at);
                                  bodyhtml += `
                                      <tr>
                                      <td><input type="checkbox" name="id[]" class="ckb" value="168947"></td>
									<td class="list_notice1">
										<span class="badge primary">입금</span>
									</td>
									<td class="list_notice1">${date.toLocaleString()}</td>
									<td class="list_notice1" style="color:#8ebfe1;">${parseInt(data.data[i].sum).toLocaleString()}원</td>
									<td class="list_notice1">
                                        <span class="badge ${status_color[data.data[i].status]}">${status_name[data.data[i].status]}</span>
									</td>
								</tr>`;
                              }
                              
                          }
                          bodyhtml += `</tbody>
                            </table>
                            <div class="form-footer">
                                <button type="button" class="yellow-bg" onclick=""><span><i class="fas fa-check"></i>선택삭제</span></button>
                            </div>
                            </div>
					<div></div>
				</div>`;
                $modal.find(".modal-body").html(bodyhtml);      
                      } else {
                          alert(data.msg);
                      }
                    }
                  });
        }else if(type == 'withdraw_list'){
            bannerhtml = `<div class="panel">
                    <div class="title-panel"><h1 class="title">출금내역</h1></div>
                    <div class="modal-menu">
                        <button class="login-link " onclick="open_modal('deposit');">
                            <div class="icon-panel">
                                <i class="fas fa-piggy-bank"></i>
                            </div>
                            <span>입금신청</span>
                        </button>
                        <button class="login-link " onclick="open_modal('withdraw');">
                            <div class="icon-panel">
                                <i class="fas fa-university"></i>
                            </div>
                            <span>출금신청</span>
                        </button>
                        <button class="join-link " onclick="open_modal('point_list');">
                            <div class="icon-panel">
                                <i class="fas fa-list"></i>
                            </div>
                            <span>포인트내역</span>
                        </button>
                        <button class="login-link " onclick="open_modal('notice');">
                            <div class="icon-panel">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            <span>공지사항</span>
                        </button>
                        <button class="join-link " onclick="open_modal('event');">
                            <div class="icon-panel">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <span>이벤트</span>
                        </button>
                        <button class="login-link " onclick="open_modal('qna');">
                            <div class="icon-panel">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <span>고객센터</span>
                        </button>
                        <button class="login-link " onclick="open_modal('msg');">
                            <div class="icon-panel">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <span>쪽지</span>
                        </button>

                    </div>
                </div>`;
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: true,
                    url: '/api/inouthistory',
                    dataType: 'json',
                    data : {type: 'out'},
                    success: function(data) {
                        if(data.error == false){
                          var bodyhtml = `<div class="modal-panel">
                                <div>
                                    <table class="bs-table">
                                        <thead>
                                            <tr>
                                                <th width="50px"><input type="checkbox" id="ckbs"></th>
                                                <th class="list_title1">신청종류</th>
                                                <th width="40%" class="list_title1">신청일시</th>
                                                <th width="20%" class="list_title1">신청금액</th>
                                                <th width="20%" class="list_title1">진행상태</th>
                                            </tr> 
                                        </thead>
                                        <tbody>
                                      `;
                            if (data.data.length > 0) {
                                status_name = {
                                    0 : '승인대기',
                                    1 : '승인완료',
                                    2 : '승인취소'
                                };
                                status_color={
                                    0 : 'wait',
                                    1 : 'success',
                                    2 : 'cancel'
                                }
                              for (var i = 0; i < data.data.length; i++) {
                                  date = new Date(data.data[i].created_at);
                                  bodyhtml += `
                                  <tr>
									<td><input type="checkbox" name="id[]" class="ckb" value="58101"></td>
									<td class="list_notice1">
										<span class="badge danger">출금</span>
									</td>
									<td class="list_notice1">${date.toLocaleString()}</td>
									<td class="list_notice1" style="color:#dd4b39;">${parseInt(data.data[i].sum).toLocaleString()}원</td>
									<td class="list_notice1">
                                        <span class="badge ${status_color[data.data[i].status]}">${status_name[data.data[i].status]}</span>
									</td>
								</tr>
                                  `;
                              }
                              
                            }
                            bodyhtml += `</tbody>
                                        </table>
                                        <div class="form-footer">
                                            <button type="button" class="yellow-bg" onclick=""><span><i class="fas fa-check"></i>선택삭제</span></button>
                                        </div>
                                    </div>
                                    <div></div>
                                </div>`;
                            $modal.find(".modal-body").html(bodyhtml);
                          
                        } else {
                          alert(data.msg);
                        } 
                    }
                });
        }
        $modal.find(".modal-banner").html(bannerhtml);
        $modal.find(".modal-body").html(bodyhtml);
        $modal.removeClass("deposit withdraw notice qna memo");
        $modal.addClass(type);

        $modal.modal();
        // $.get("/modal/get_content", {
        //     type: type,
        //     page: page
        // }, function(data) {

        //     var data = JSON.parse(data);

        //     if (!data.result) {
        //         alert(data.ment);
        //     } else {

        //         $modal.find(".modal-banner").html(data.banner);
        //         $modal.find(".modal-body").html(data.content);
        //         $modal.removeClass("deposit withdraw notice qna memo");
        //         $modal.addClass(type);

        //         $modal.modal();
        //     }
        // });
    }
}


function view_content_msg(td){

    $td = $(td);

    $("tr.content").not(".content_" + $td.data("idx")).css("display", "none");
    $(".content_" + $td.data("idx")).toggle();

    if($td.data("is_read")=='N'){
        $.get("memo/update_read", {idx:$td.data("idx")}, function(data){

            var data = JSON.parse(data);
            if( data.result ){
                $td.data("is_read", "Y");
                $td.closest("tr").find("td.state span").text("[읽음]");
                $td.closest("tr").find("td.state span").css("color", "#74b4eb");
            }
        })
    }
}




function view_content_qna(a){

    $a = $(a);
    $("tr.content").not(".content_" + $a.data("idx")).css("display", "none");
    $(".content_" + $a.data("idx")).toggle();

    if($a.data("is_read")=='N'){
        $.get("/question/update_read", {idx:$a.data("idx")}, function(data){

            var data = JSON.parse(data);
            if( data.result ){
                $a.data("is_read", "Y");
                $a.find("span").text(" [답변완료]");
                $a.find("span").css("color", "#74b4eb");
            }
        })
    }
}



function view_content_bbs(a){

    $a = $(a);

    $("tr.content").not(".content_" + $a.data("idx")).css("display", "none");
    $(".content_" + $a.data("idx")).toggle();
}

function parseNumberToInt(val) {
    val = val.replace(/[^\/\d]/g, '');
    return parseInt(val);
}

function depositRequest() {
    // alert('asd');
    var cmoney = $('.money').val();
    var cmoneyx = $('.money').val().replace(/,/g, '');
    var y = parseNumberToInt(cmoney);
    var x = 10000;
    var remainder = Math.floor(y % x);
    if (remainder != 0) {
        alert('입금신청은 만원단위로 가능합니다. 만원단위로 신청해주시기 바랍니다.');
        return false;
    }
    var conf = confirm('입금신청을 하시겠습니까?');
    if (!conf) {
        return false;
    }
    if (cmoney <= 0) {
        // alert('신청하실 충전금액을 입력해주세요.');
        alert('신청하실 충전금액을 입력해주세요.');
        return false;
    }
    $.ajax({
        url: '/api/addbalance',
        type: 'POST',
        dataType: "json",
        data: {
        money: cmoney,
        },
        success: function(result) {
        if (result.error == false) {
            $("#charge_money").val(0);
            swal('신청완료 되었습니다.');
        } else {
            swal('Oops!', result.msg, 'error');
        }
        }
    });
    $(".btn-pointr").on('click', function() {
        $(".btn-pointr").removeClass('active');
        $(this).addClass('active');
        isReceivedPoint = $(this).attr('data-type');
    });
}

function withdrawRequest() {
    var cmoney = $('.exchange_money').val();
    var y = parseNumberToInt(cmoney);
    var x = 10000;
    var remainder = Math.floor(y % x);
    if (remainder != 0) {
      alert('출금신청은 만원단위로 가능합니다. 만원단위로 신청해주시기 바랍니다.');
      return false;
    }
    var conf = confirm('출금신청을 하시겠습니까?');
    if (!conf) {
      return false;
    }
    if (cmoney <= 0) {
      // alert('Invalid Amount');
      alert('정확한 금액을 입력해주세요');
      return false;
    }
    $.ajax({
      url: '/api/outbalance',
      type: 'POST',
      dataType: "json",
      data: {
        money: cmoney,
      },
      success: function(result) {
        if (result.error == false) {
          $("#exchange_money").val(0);
        //   $("#expassword").val('');
          swal('신청완료 되었습니다.');
        } else {
            $("#exchange_money").val(0);
            swal('Oops!', result.msg, 'error');
            
        }
      }
    })
  }
  


function mywithdrawlist() {
    $.ajax({
        type: "POST",
        cache: false,
        async: true,
        url: '/api/inouthistory',
        dataType: 'json',
        data : {type: 'out'},
        success: function(data) {
          if(data.error == false){
              var html = `<tbody>
                          <tr>
                              <th width="10%" class="ng-scope">번호</td>
                              <th width="10%" class="ng-scope">환전금액</td>
                              <th width="10%" class="ng-scope">신청날짜</td>
                              <th width="10%" class="ng-scope">상태</td>
                          </tr>
                          `;
              if (data.data.length > 0) {
                  status_name = {
                      0 : '대기',
                      1 : '완료',
                      2 : '취소'
                   };
                  for (var i = 0; i < data.data.length; i++) {
                      date = new Date(data.data[i].created_at);
                      html += `<tr>
                          <td class="ng-binding">${i+1}</td>
                          <td class="ng-binding">${parseInt(data.data[i].sum).toLocaleString()}원</td>
                          <td class="ng-binding">${date.toLocaleString()}</td>
                          <td class="ng-binding">${status_name[data.data[i].status]}</td>
                          </tr>
                          </thead>`;
                  }
                  
              }
              html += `</table>`;
              $("#mywithdraw").html(html);
              
          } else {
              alert(data.msg);
          }
        }
      });
}


function send_text() {
    var title = $("#txt_title").val();
    var message = $('#content_txt').val();
    if ((title == '') || (message == '')) 
    {
      swal('', '제목과 내용을 입력해주세요', 'error');
    } 
    else 
    {
        $.ajax({
            url: "/api/writeMsg",
            type: "POST",
            dataType: "json",
            data: {
              title: title,
              content: message
            },
            success: function(result) {
              if (result.error == false) {
                swal('저장 되었습니다');
                $("#txt_title").val('');
                $('#content_txt').val('');
                              getCustomerPage();
                              //$("form_qna").css("display", "none");
                              document.getElementById("form_qna").style.display = "none";
                // $(".cc-list").removeClass('ng-hide');
                // },2000);
              }
              else 
              {
                swal('Opps!', result.msg, "error");
              }
            }
          });
    }              
}

function write_qna(idx){
    if(idx == 0){
        //$("form_qna").toggle();
        document.getElementById("form_qna").style.display = "block";
    }else if(idx == 3){
        autorequest();
    }
}

function autorequest(){
    var f = confirm("계좌요청을 하시겠습니까?");
    if(!f){
        return false;
    }
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