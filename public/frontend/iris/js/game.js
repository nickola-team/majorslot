
   bg = '<div id="gameBgs" style="width:100%; height:100%; z-index:1201; position:fixed; background:RGBA(0,0,0,0.9); top:0; left:0;" onclick="closeIfr()"></div>';
   ifr = '<iframe src="about:blank" style="width:{{width}}; height:{{ifrHgt}}px; top:{{top}}; left:{{left}}; position:absolute; z-index:1201; text-align:center;" allowTransparency="true" frameborder="0" id="gameIfr"></iframe>';

function iframeOn(src, type = ''){
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
   
   var ifrMake = ifr;
   ifrMake = ifrMake.replace('{{ifrHgt}}',hgt);
   ifrMake = ifrMake.replace('{{top}}',top);
   ifrMake = ifrMake.replace('{{width}}',width);
   ifrMake = ifrMake.replace('{{left}}',left);
   $(document ).scrollTop(0);
   $('body').append(bg+ifrMake);
   var frame = document.getElementById("gameIfr");
   var fdoc = frame.contentDocument;
   fdoc.write(src);
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

function casinoGameStart(category){
   $('.loaderBg').css('display','block');

   $.ajax({
      type: "POST",
      url: "/api/getgamelist",
      data: {category : category},
      cache: false,
      async: true,
      success: function (data, status) {
          if (data.error) {
              alert(data.msg);
              return;
          }
          closeLoader();
          if (data.games.length > 0) {
            startGameByProvider(data.games[0].provider, data.games[0].gamecode);
          }
         }
      });
}


function slotGame(category){
   $('.loaderBg').css('display','block');

   $.ajax({
      type: "POST",
      url: "/api/getgamelist",
      data: {category : category},
      cache: false,
      async: true,
      success: function (data, status) {
          if (data.error) {
              alert(data.msg);
          }
          var htmldoc = `<!DOCTYPE html>
               <head>
                  <meta charset="utf-8">
                  <meta name="viewport" content="width=device-width, initial-scale=1">
                  <meta http-equiv="X-UA-Compatible" content="IE=edge">
                  <title></title>
                  <script src="https://kit.fontawesome.com/c9af18662b.js" crossorigin="anonymous"></script>
                  <script src="/frontend/iris/vendor/jquery/jquery.min.js"></script>
                     <link rel="preconnect" href="https://fonts.googleapis.com">
                     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                     <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR&display=swap" rel="stylesheet">
                  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
                  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
                  <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
                  <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
                  <link href="/frontend/iris/css/sb-admin-2.css?v=1668975069" rel="stylesheet"> 
                  <link href="/frontend/iris/css/powerball.css?v=1665916371" rel="stylesheet">
                  <style>
                     html,body {background: RGBA(0,0,0,0); height: 100%:}
                     .modal-content {background: RGBA(0,0,0,0.7)}
                     .modal-header {background: #fff}
                     .modal-body { font-family: NotoSansKr-Bold; font-size:1.2em; font-weight: bold; text-align: center; overflow-y: auto; height: 100%;}
                     .items {display: inline-block; margin:10px; cursor:pointer}
                     .itemName {text-align: center; max-width:190px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; margin-top: 3px;}
                           </style>
               </head>
               <body>
               
               <div class="modal-content">
                     <script>
                     function imgError(image) {
                        var imgWidth = $('img').eq(0).width();
                        var imgheight = $('img').eq(0).height();
                        if(imgWidth < 100)
                           imgWidth = '192';
                        if(imgheight < 100)
                           imgheight = '192';
                        image.onerror = "";
                        image.src = "/frontend/iris/img/no_img.jpg";
                        $(image).css({width : imgWidth+'px', height : imgheight+'px'});
                        return true;
                     }
                  </script>
                  <div class="modal-content">
                        <div class="modal-header">
                           <h5 class="modal-title ipchulTitle" id="ipgumLabel"></h5>
                           <button class="close" type="button" onclick="parent.closeIfr()">
                              <span aria-hidden="true">×</span>
                           </button>
                        </div>
                        <div class="modal-body" style="border:1px solid #fff">`;
                        if (data.games.length > 0) {
                           for (var i = 0; i < data.games.length; i++) {
                              if (data.games[i].provider)
                              {
                                 htmldoc += `<div class="items" onclick="parent.startGameByProvider('${data.games[i].provider}', '${data.games[i].gamecode}');">
                                       <div>
                                          <img src="${data.games[i].icon}" style="width:192px;" onerror="imgError(this);">
                                       </div>
                                       <div class="itemName">${data.games[i].title}</div>
                                    </div>`;
                              }
                              else
                              {
                                 htmldoc += `<div class="items" onclick="parent.startGameByProvider(null, '${data.games[i].name}');">
                                 <div>
                                    <img src="/frontend/Default/ico/${data.games[i].name}.jpg" style="width:192px;" onerror="imgError(this);">
                                 </div>
                                 <div class="itemName">${data.games[i].title}</div>
                              </div>`;
                              }
                              
                           }
                        }
                        else
                        {
                           htmldoc += `<div class="items">
                                 <div>
                                    <img src="/frontend/iris/img/no_img.jpg" style="width:192px;" onerror="imgError(this);">
                                 </div>
                                 <div class="itemName">
                                    게임이 없습니다</div>
                              </div>`;
                        }

                     htmldoc += `</div>                
                        </div>
                        <script>
                              parent.closeLoader();
               
                              $(function(){
                                 $(window).resize(function(){
                                    hgtRe();
                                 });
                                 hgtRe();
                              });
                        
                              function hgtRe(){
                                 var hgt = window.innerHeight- 137;
                                 $('.modal-body').css('height',hgt+'px');
                              }
                           </script>
                        </body>
                        </html>
                        `;
         iframeOn(htmldoc, 'slot');
          
      },
      error: function (err, xhr) {
      }
  });
}

function startGameByProvider(provider, gamecode,max = false) {
   var formData = new FormData();
   formData.append("provider", provider);
   formData.append("gamecode", gamecode);
   formData.append("_token", $("#_token").val());
   $.ajax({
   type: "POST",
   url: "/api/getgamelink",
   data: formData,
   processData: false,
   contentType: false,
   cache: false,
   async: false,
   success: function (data) {
       if (data.error) {
           alert(data.msg);
           return;
       }
       if (max)
       {
         window.open(data.data.url, "game", "width=" + screen.width + ", height=" + screen.height + ", left=100, top=50");
       }else{
         window.open(data.data.url, "game", "width=1280, height=720, left=100, top=50");
       }
   }
   });
   
}


function charge(type){
   title = ((type=='add')?'입금':'출금');
   $('.loaderBg').css('display','block');

   $.ajax({
      type: "POST",
      url: "/api/inouthistory",
      data: {type: type},
      cache: false,
      async: true,
      success: function (data, status) {
          if (data.error) {
              alert(data.msg);
          }
          history = data.data;
          var chargesrcdoc = `<!DOCTYPE html>
            <head>
               <meta charset="utf-8">
               <meta name="viewport" content="width=device-width, initial-scale=1">
               <meta http-equiv="X-UA-Compatible" content="IE=edge">
               <title>충전</title>
               <script src="https://kit.fontawesome.com/c9af18662b.js" crossorigin="anonymous"></script>
               <script src="/frontend/iris/vendor/jquery/jquery.min.js"></script>
               <link rel="preconnect" href="https://fonts.googleapis.com">
               <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
               <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR&display=swap" rel="stylesheet">
               <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
               <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
               <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
               <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
               <link href="/frontend/iris/css/sb-admin-2.css?v=1668975069" rel="stylesheet"> 
               <link rel="stylesheet" href="/frontend/iris/css/loader.css?v=1649740588">   
               <style>
                  html,body {background: RGBA(0,0,0,0); height: 100%}
                  .modal-body { font-family: NotoSansKr-Bold; overflow-y: auto; height: 100%;}
               </style>
            </head>
            <body class="Ifrmain">
               <div class="loaderBg">
                  <div class="lds-dual-ring"></div>
               </div><!--  입출금신청 Modal-->
                        <div class="modal-content">
                           <div class="modal-header">
                              <h5 class="modal-title ipchulTitle" id="ipgumLabel">${title} 신청</h5>
                              <button class="close" type="button" onclick="parent.closeIfr()">
                                    <span aria-hidden="true">×</span>
                              </button>
                           </div>
                           <div class="modal-body">
                              <div style="margin-bottom:10px">
                                 <div style="float:right; font-size:1.6em">
                                    현재 금액 <span class="userMoney" style="color:#4e73df">${parseInt(data.balance).toLocaleString()}</span> 원
                                 </div>
                              </div>
                              <input type="text" class="form-control small" placeholder="금액입력" id="ipchulMoney" aria-label="money" aria-describedby="basic-addon2">
                              <div class="btns">
                                 <button class="btn btn-primary" type="button" onclick="$('#ipchulMoney').val(($('#ipchulMoney').val() * 1)+10000)">10,000 원</button>
            
                                 <button class="btn btn-success" type="button" onclick="$('#ipchulMoney').val(($('#ipchulMoney').val() * 1)+50000)">50,000 원</button>
            
                                 <button class="btn btn-info" type="button" onclick="$('#ipchulMoney').val(($('#ipchulMoney').val() * 1)+100000)">100,000 원</button>
            
                                 <button class="btn btn-warning" type="button" onclick="$('#ipchulMoney').val(($('#ipchulMoney').val() * 1)+500000)">500,000 원</button>
            
                                 <button class="btn btn-danger" type="button" onclick="$('#ipchulMoney').val(($('#ipchulMoney').val() * 1)+1000000)">1,000,000 원</button>
            
                                 <button class="btn btn-secondary fr" type="button" onclick="$('#ipchulMoney').val('')">리셋</button>
                              </div>
                              <div id="ipchullog">
                                 <br>
                                 <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                       <tr>
                                          <th>번호</th>
                                          <th>금액</th>
                                          <th>날짜</th>
                                          <th>상태</th>
                                       </tr>
                                    </thead>
                                    <tbody>`;
                              if (data.data.length > 0) {
                                 status_name = {
                                    0 : '대기',
                                    1 : '완료',
                                    2 : '취소'
                                 };
                                 for (var i = 0; i < data.data.length; i++) {
                                    date = new Date(data.data[i].created_at);
                                    chargesrcdoc += `<tr>
                                       <td>${i+1}</td>
                                       <td>${parseInt(data.data[i].sum).toLocaleString()}원</td>
                                       <td>${date.toLocaleString()}</td>
                                       <td>${status_name[data.data[i].status]}</td>
                                       </tr>`;
                                 }
                              }
                              else
                              {
                                 chargesrcdoc += `<tr><td colspan='4'>내역이 없습니다.</td></tr>`;
                              }
                     chargesrcdoc += `
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                           <div class="modal-footer">`;
                     if (type == 'add')
                     {
                        chargesrcdoc += `
                              <button class="btn btn-warning" type="button" onclick="requestAccount()">계좌 요청</button>
                              <button class="btn btn-secondary" type="button" onclick="parent.closeIfr()">닫기</button>
                              <a class="btn btn-primary ipchulTitle" onclick="deposit()">입금 신청</a>`;
                     }
                     else
                     {
                        chargesrcdoc += `
                              <button class="btn btn-secondary" type="button" onclick="parent.closeIfr()">닫기</button>
                              <a class="btn btn-primary ipchulTitle" onclick="withdraw()">출금 신청</a>`;
                     }
                     chargesrcdoc += `
                           </div>
                        </div>
            
                        <script src="/frontend/iris/js/money.js?v=1672945882"></script>
                        <script src="/frontend/iris/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
                        <script src="/frontend/iris/vendor/jquery-easing/jquery.easing.min.js"></script>
                        <script src="/frontend/iris/js/sb-admin-2.js"></script>
                        <script>
                              parent.closeLoader();
               
                              $(function(){
                                 $(window).resize(function(){
                                    hgtRe();
                                 });
                                 hgtRe();
                              });
                        
                              function hgtRe(){
                                 var hgt = window.innerHeight- 137;
                                 $('.modal-body').css('height',hgt+'px');
                              }
                           </script>
                        </body>
                        </html>
                        `;
         iframeOn(chargesrcdoc);
          
      },
      error: function (err, xhr) {
      }
  });

   
}

function boardnotice(){
   $('.loaderBg').css('display','block');

   $.ajax({
      type: "POST",
      url: "/api/notices",
      data: null,
      cache: false,
      async: true,
      success: function (data, status) {
          if (data.error) {
              alert(data.msg);
          }
          history = data.data;
          var htmldoc = `<!DOCTYPE html>
          <head>
             <meta charset="utf-8">
             <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
             <title>보드</title>
             <script src="https://kit.fontawesome.com/c9af18662b.js" crossorigin="anonymous"></script>
             <script src="/frontend/iris/vendor/jquery/jquery.min.js"></script>
               <link rel="preconnect" href="https://fonts.googleapis.com">
               <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
               <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR&display=swap" rel="stylesheet">
             <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
             <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
              <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
              <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
              <link href="/frontend/iris/css/sb-admin-2.css?v=1668975069" rel="stylesheet"> 
              <link rel="stylesheet" href="/frontend/iris/css/loader.css?v=1649740588">   
              <style>
                 html,body {background: RGBA(0,0,0,0); height: 100%}
                  .modal-body { font-family: NotoSansKr-Bold; overflow-y: auto; height: 100%;}
              </style>
          </head>
          <body class="Ifrmain">
             <div class="loaderBg">
                 <div class="lds-dual-ring"></div>
             </div>
             <div class="modal-content">
                        <div class="modal-header">
                           <h5 class="modal-title ipchulTitle" id="ipgumLabel">공지사항</h5>
                           <button class="close" type="button" onclick="parent.closeIfr()">
                                 <span aria-hidden="true">×</span>
                           </button>
                        </div>
                        <div class="modal-body">    
                              <div>
                              <br>
                                 <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                       <tr> 
                                          <th width="70">번호</th>
                                          <th>제목</th>
                                       </tr>
                                    </thead>
                                    <tbody>`;
                              if (data.data.length > 0) {
                                 for (var i = 0; i < data.data.length; i++) {
                                    date = new Date(data.data[i].date_time);
                                    htmldoc += `<tr onclick="openNotice(${data.data[i].id})" class="cp">
                                       <td  align="right">${i+1}</td>
                                       <td id="subj_${data.data[i].id}">${data.data[i].title}</td>
                                       </tr>`;
                                    htmldoc += `<tr id="cont_${data.data[i].id}" style="display:none">
                                       <td colspan="2">
                                       <div class="contents">`;
                                    htmldoc += data.data[i].content;
                                    htmldoc += `
                                       </div>
                                       </td>
                                       </tr>`;
                                 }
                              }
                              else
                              {
                                 htmldoc += `<tr><td colspan='2'>내역이 없습니다.</td></tr>`;
                              }
                     htmldoc += `
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                           <div class="modal-footer">
                           <button class="btn btn-secondary" type="button" onclick="parent.closeIfr()">닫기</button>
                           </div>
                        </div>
            
                        <script src="/frontend/iris/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
                        <script src="/frontend/iris/vendor/jquery-easing/jquery.easing.min.js"></script>
                        <script src="/frontend/iris/js/sb-admin-2.js"></script>
                        <script src="/frontend/iris/js/board.js"></script>
                        <script>
                              parent.closeLoader();
               
                              $(function(){
                                 $(window).resize(function(){
                                    hgtRe();
                                 });
                                 hgtRe();
                              });
                        
                              function hgtRe(){
                                 var hgt = window.innerHeight- 137;
                                 $('.modal-body').css('height',hgt+'px');
                              }
                           </script>
                        </body>
                        </html>
                        `;
         iframeOn(htmldoc);
          
      },
      error: function (err, xhr) {
      }
  });
}

function boardperson(){
   $('#msgBg').remove();
   $('.loaderBg').css('display','block');


   $.ajax({
      type: "POST",
      url: "/api/messages",
      data: null,
      cache: false,
      async: true,
      success: function (data, status) {
          if (data.error) {
              alert(data.msg);
          }
          history = data.data;
          var htmldoc = `<!DOCTYPE html>
               <head>
                  <meta charset="utf-8">
                  <meta name="viewport" content="width=device-width, initial-scale=1">
                  <meta http-equiv="X-UA-Compatible" content="IE=edge">
                  <title>보드</title>
                  <script src="https://kit.fontawesome.com/c9af18662b.js" crossorigin="anonymous"></script>
                  <script src="/frontend/iris/vendor/jquery/jquery.min.js"></script>
                     <link rel="preconnect" href="https://fonts.googleapis.com">
                     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                     <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR&display=swap" rel="stylesheet">
                  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
                  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
                  <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
                  <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
                  <link href="/frontend/iris/css/sb-admin-2.css?v=1668975069" rel="stylesheet"> 
                  <link rel="stylesheet" href="/frontend/iris/css/loader.css?v=1649740588">   
                  <style>
                     html,body {background: RGBA(0,0,0,0); height: 100%}
                     .modal-body { font-family: NotoSansKr-Bold; overflow-y: auto; height: 100%;}
                  </style>
               </head>
                  <body class="Ifrmain">
                     <div class="loaderBg">
                        <div class="lds-dual-ring"></div>
                     </div>            <div class="modal-content">
                                 <div class="modal-header">
                                       <h5 class="modal-title ipchulTitle" id="ipgumLabel">1:1 문의</h5>
                                       <button class="close" type="button" onclick="parent.closeIfr()">
                                          <span aria-hidden="true">×</span>
                                       </button>
                                 </div>
                                                   <div class="modal-body">    
                                       <div>
                                                                  <br>
                                          <table class="table table-bordered" width="100%" cellspacing="0">
                                             <thead>
                                                <tr> 
                                                   <th width="70">번호</th>
                                                   <th>제목</th>
                                                   <th width="148">작성시간</th>
                                                   <th width="148">읽은시간</th>
                                                   <th width="80">타입</th>
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
                                       date1 = new Date(data.data[i].read_at);
                                       read = date1.toLocaleString();
                                    }
                                    
                                    type = (data.user_id==data.data[i].user_id)?'수신':'발신';
                                    htmldoc += `<tr onclick="openMessage(${data.data[i].id})" class="cp">
                                       <td  align="right">${i+1}</td>
                                       <td id="subj_${data.data[i].id}">${data.data[i].title}</td>
                                       <td align="right">${date.toLocaleString()}</td>
                                       <td align="right">${read}</td>
                                       <td align="right">${type}</td>
                                       </tr>`;
                                    htmldoc += `<tr id="cont_${data.data[i].id}" style="display:none">
                                       <td colspan="5">
                                       <div class="contents">`;
                                    htmldoc += data.data[i].content;
                                    htmldoc += `
                                       </div>
                                       </td>
                                       </tr>`;
                                 }
                              }
                              else
                              {
                                 htmldoc += `<tr><td colspan='5'>내역이 없습니다.</td></tr>`;
                              }
                     htmldoc += `
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                           <div class="modal-footer">
                              <button class="btn btn-warning" type="button" onclick="writeReqAccountMsg()">계좌 요청</button>
                              <button class="btn btn-secondary" type="button" onclick="parent.closeIfr()">닫기</button>
                              <a href="#" class="btn btn-primary btn-icon-split fr" data-dismiss="modal"  data-toggle="modal" data-target="#write"> <span class="text">쓰기</span> </a>
                           </div>
                           <div class="modal fade" id="write" tabindex="-1" role="dialog" aria-labelledby="writeLabel" aria-hidden="true">
                           <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                    <div class="modal-header">
                                       <h5 class="modal-title" id="writeLabel">1:1문의 <span id="reply">작성</span></h5>
                                       <button class="close" type="button" data-dismiss="modal" aria-label="Close"">
                                          <span aria-hidden="true">×</span>
                                       </button>
                                    </div>
                                    <div class="modal-body2" style="padding:1rem; flex:1 1 auto; position: relative;">
                                       <input type="text" class="form-control small" placeholder="제목" id="subject" aria-describedby="basic-addon2">
                                       <br>
                                       <textarea id="writeArea" class="form-control small" placeholder="내용" style="height:200px"></textarea>
                                       <br>
                                    </div>
                                    <div class="modal-footer">
                                       <button class="btn btn-secondary" type="button" data-dismiss="modal">닫기</button>
                                       <a class="btn btn-primary" onclick="writeMessage()">저장</a>
                                    </div>
                              </div>
                           </div>
                        </div>
            
                        <script src="/frontend/iris/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
                        <script src="/frontend/iris/vendor/jquery-easing/jquery.easing.min.js"></script>
                        <script src="/frontend/iris/js/sb-admin-2.js"></script>
                        <script src="/frontend/iris/js/board.js"></script>
                        <script>
                              parent.closeLoader();
               
                              $(function(){
                                 $(window).resize(function(){
                                    hgtRe();
                                 });
                                 hgtRe();
                              });
                        
                              function hgtRe(){
                                 var hgt = window.innerHeight- 137;
                                 $('.modal-body').css('height',hgt+'px');
                              }
                           </script>
                        </body>
                        </html>
                        `;
         iframeOn(htmldoc);
          
      },
      error: function (err, xhr) {
      }
  });
}



function Mobile() {return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);}