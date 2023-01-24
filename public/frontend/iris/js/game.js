
   bg = '<div id="gameBgs" style="width:100%; height:100%; z-index:1201; position:fixed; background:RGBA(0,0,0,0.9); top:0; left:0;" onclick="closeIfr()"></div>';
   ifr = '<iframe src="{{ifrSrc}}" style="width:{{width}}; height:{{ifrHgt}}px; top:{{top}}; left:{{left}}; position:absolute; z-index:1201; text-align:center;" allowTransparency="true" frameborder="0" id="gameIfr"></iframe>';

function iframeOn(src, type = ''){
   $('.loaderBg').css('display','block');
   var hgt = window.innerHeight / 100 * 70;
   var top = '15%';
   var width = '80%';
   var left = '10%';
   if(type == 'powerball' || type == 'slot'){
      hgt = window.innerHeight / 100 * 90;
      top = '5%';
   }
   if(type == 'powerball'){
      width = (window.innerWidth / 100 * 90)+'px';
      left = '5%';
   }
   if(Mobile()){
      hgt = window.innerHeight;
      top = 0;
      width = '100%';
      left = 0;
   }
   
   var ifrMake = ifr.replace('{{ifrSrc}}',src);
   ifrMake = ifrMake.replace('{{ifrHgt}}',hgt);
   ifrMake = ifrMake.replace('{{top}}',top);
   ifrMake = ifrMake.replace('{{width}}',width);
   ifrMake = ifrMake.replace('{{left}}',left);
   $(document ).scrollTop(0);
   $('body').append(bg+ifrMake);
   $('body').css({'overflow':'hidden','height':'100%'});

  
}

function closeLoader(){
   $('.loaderBg').css('display','none');
}

function closeIfr(){
   $('#gameBgs, #gameIfr').remove();
   $('body').css({'overflow':'auto','height':'auto'});
   $('.nav-main').removeClass('active');
}

$(function(){
    $(window).resize(function(){
      /*var hgt = window.innerHeight / 100 * 70;
      $('#gameIfr').css('height',hgt+'px');*/
    });

});

function goStartGame(game){
      if(game == 'game01slot'){
         slotGame(8,'프라그매틱플레이')
      }else if(game == 'game02slot'){
        slotGame(29,'플레이스타')
      }else if(game == 'game03slot'){
        slotGame(40,'에보플레이')
      }else if(game == 'game04slot'){
         slotGame(24,'하바네로')
      }else if(game == 'game08live'){
         casinoGame(8);
      }else if(game == 'game07live'){
         casinoGame(18);
      }else if(game == 'game06live'){
         casinoGame(15);
      }else if(game == 'game05live'){
         casinoGame(9);
      }else if(game == 'game04live'){
         casinoGame(44);
      }else if(game == 'game03live'){
         casinoGame(28);
      }else if(game == 'game02live'){
         casinoGame(15);
      }else if(game == 'game01live'){
         casinoGame(45);
      }else{
         alert('준비중입니다.');
      }
   }

function casinoGame(num, vendor){
   console.log(num);
   var size = 'width=1024, height=640, menubar=no, status=no, toolbar=no';
   if(num == 'seastory' || num == 'davinci'){
      size = 'width=540, height=900, menubar=no, status=no, toolbar=no';
   } else if(vendor == 'soul'){
      size = 'width=1280, height=725, menubar=no, status=no, toolbar=no, scrolling=no';
   }

   console.log(size);
   opWin = window.open('/casino/game.php?gameId='+num+'&type=casino&vendor='+vendor,'Casino 팝업',size);
}


function slotGame(num,title){
   iframeOn('/game/slot.list.php?gameId='+num+'&title='+title, 'slot');
   //var opWin = window.open('/game/slot.list.php?gameId='+num+'&title='+title,'슬롯 팝업','width=1024, height=640, menubar=no, status=no, toolbar=no');
}

function powerballStart(type){
   iframeOn('/game/powerball.php?type='+type, 'powerball');
   //var opWin = window.open('/game/slot.list.php?gameId='+num+'&title='+title,'슬롯 팝업','width=1024, height=640, menubar=no, status=no, toolbar=no');
}

function slotGameStart(id,gameCode,name,name_ko){
   opWin = window.open('/casino/game.php?gameId='+id+'&type=slot&gameCode='+gameCode+'&name='+name+'&name_ko='+name_ko,'Casino 팝업','width=1024, height=640, menubar=no, status=no, toolbar=no');
}


function charge(num){
   iframeOn('/menu/charge.php?type='+num+'&title=입금/출금');
}

function board(id){
   var type2 = '';
   if(id == 'message'){
      type2 = 'receive';
      $('#msgBg').remove();
   }
   iframeOn('/menu/board.php?type='+id+'&title=보드&type2='+type2);
}

function boardOn(id,idx){
   iframeOn('/menu/board.php?type='+id+'&title=보드&idx='+idx);
}

function Mobile() {return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);}


function speak(text, opt_prop) {
   if(soundOffChk)
       return;
   if (typeof SpeechSynthesisUtterance === "undefined" || typeof window.speechSynthesis === "undefined") {
       //alert("이 브라우저는 음성 합성을 지원하지 않습니다.")
       return
   }
   
   //window.speechSynthesis.cancel() // 현재 읽고있다면 초기화

   const prop = opt_prop || {}

   const speechMsg = new SpeechSynthesisUtterance()
   speechMsg.rate = prop.rate || 1.2 // 속도: 0.1 ~ 10      
   speechMsg.pitch = prop.pitch || 2 // 음높이: 0 ~ 2
   speechMsg.lang = prop.lang || "ko-KR"
   speechMsg.text = text
   
   // SpeechSynthesisUtterance에 저장된 내용을 바탕으로 음성합성 실행
   window.speechSynthesis.speak(speechMsg)
}