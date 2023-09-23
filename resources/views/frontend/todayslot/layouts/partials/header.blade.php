<!-- <div id="wrap" class="mainboard" style="display: block;"> -->
  <div id="header_wrap" class="mainboard">
    <div class="header_box">
      <div class="logo">
        <a href="./">
          <img src="/frontend/todayslot/images/{{$logo}}_logo.png">
        </a>
      </div>
      <div class="my">
        <span id="UserInfo" data-accountholder="" data-checkedupdate="" data-moneymoveused="" data-bankinfo="" style="display: none;"></span>        
        
            @auth()
            <div class="login" style="margin: 20px 0 0 0;">
            <ul>
              <li>
                <a href="#" onclick="PointToMoney();">
                  <img src="/frontend/todayslot/images/ww_icon2.png" height="26"> 보유포인트 : 
                  <span class="font05" id="myPoint">&nbsp;&nbsp;&nbsp;{{number_format(auth()->user()->deal_balance,0)}}P&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                </a>
              </li> 
              <li>
              </li>
            </ul>
            @endif
            <ul>
                @auth()
                <li>
                  <a href="#" onclick="goMypage(5);" class="sub_pop2_open">
                    <img id="letterimg" src="/frontend/todayslot/images/letter_on.gif" width="16" height="16" border="0">
                    <span id="letteralarm" style="font-size:11px"> {{$unreadmsg}}건</span>
                  </a>&nbsp;&nbsp;&nbsp;
                </li>                
                <li>
                  <span class="font05">
                    <img src="https://img.asia8game.com/images/player_level/icon/UYWMD___.png" width="35">
                  </span>회원&nbsp;&nbsp;&nbsp;
                  <span class="font05">{{Auth::user()->username}}               
                  </span>님 &nbsp; 
                </li>  
                <li>&nbsp;&nbsp;
                  <img src="/frontend/todayslot/images/ww_icon.png" height="26"> 보유머니 : 
                  <span class="font05" id="myHeaderWallet">{{ number_format(Auth::user()->balance,2) }}&nbsp;원</span>
                </li>                     
                <li>
                  <a href="#" onclick="getBalance();">
                    <img src="/frontend/todayslot/images/icon_re.png" height="20">
                  </a> 
                </li>
                
                <li>
                  <a href="#" onclick="goMypage(0);" class="sub_pop2_open">
                    <span class="login_btn">마이페이지</span>
                  </a> 
                  <a href="#" onclick="javascript:goLogout();">
                    <span class="login_btn">로그아웃</span>
                  </a>
                </li>                      
                @else
                <div class="login" style="margin: 55px 0 0 0;">
                <li>
                  <input type="text" class="input_login" name="login_id" id="login_id" placeholder="아이디"> 
                  <input type="password" class="input_login" name="login_pw" id="login_pw" onkeypress="if(event.keyCode == 13) loginSubmit();" placeholder="비밀번호">
                </li>  
                <li>
                  <a href="#" onclick="loginSubmit();">
                    <span class="login_btn">로그인</span>
                  </a> 
                  <a href="#" onclick="goJoin();" class="etc_pop1_open">
                    <span class="login_btn">회원가입</span>
                  </a>
                </li>                      
                @endif
            </ul>			
        </div>
      </div>
    </div>
    <div class="gnb" style="width:1600px">
      <ul>
          <li>
            @auth()
            <a href="#" onclick="goMypage(1);" class="sub_pop2_open" >
            @else
            <a href="#" onclick="showLoginAlert();" class="sub_pop2_open" >
            @endif
              <img src="/frontend/todayslot/images/gnb_icon1.png">MY페이지
            </a>
          </li>
          <li class="gnb_line">
            <img src="/frontend/todayslot/images/gnb_line.png">
          </li>
          <li>
            @auth()
            <a href="#" onclick="goMypage(2);mydepositlist();" class="sub_pop2_open">
            @else
            <a href="#" onclick="showLoginAlert();" class="sub_pop2_open" >
            @endif
            <img src="/frontend/todayslot/images/gnb_icon1.png">입금신청
          </a>
          </li>
          <li class="gnb_line">
            <img src="/frontend/todayslot/images/gnb_line.png">
          </li>
          <li>
          @auth()
          <a href="#" onclick="goMypage(3); mywithdrawlist();" class="sub_pop2_open" >
            @else
            <a href="#" onclick="showLoginAlert();" class="sub_pop2_open" >
            @endif
            
              <img src="/frontend/todayslot/images/gnb_icon2.png">출금신청
            </a>
          </li>
          <li class="gnb_line">
            <img src="/frontend/todayslot/images/gnb_line.png">
          </li>
          <!-- <li><a href="#" onclick="goMypage();" class="sub_pop2_open"><img src="/frontend/todayslot/images/gnb_icon6.png">이벤트</a></li>
          <li class="gnb_line"><img src="/frontend/todayslot/images/gnb_line.png"></li> -->
          <li>
          @auth()
          <a href="#" onclick="goMypage(4);" class="sub_pop2_open">
            @else
            <a href="#" onclick="showLoginAlert();" class="sub_pop2_open" >
            @endif
            
              <img src="/frontend/todayslot/images/gnb_icon7.png">공지사항
            </a>
          </li>
          <li class="gnb_line">
            <img src="/frontend/todayslot/images/gnb_line.png">
          </li>
          <li>
          @auth()
          <a href="#" onclick="goMypage(5);" class="sub_pop2_open">
            @else
            <a href="#" onclick="showLoginAlert();" class="sub_pop2_open" >
            @endif
            
              <img src="/frontend/todayslot/images/gnb_icon4.png">고객센터
            </a>
          </li>
        </ul>
    </div>
  </div>
<!-- </div> -->

