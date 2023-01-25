function moneyMove(type){
    var  title = '';
    if(type == 'casino'){
        title = '카지노 머니';
    } else if(type == 'rolling_point'){
        title = '롤링 포인트';
    } else if(type == 'loosing_point'){            
        title = '루징 포인트';
    } else if(type == 'point'){
        title = '포인트';

    } 

    if(!confirm(title+'를 전환 하시겠습니까?')){
        return;
    }

    $('.loaderBg').css('display','block');
    $.post('/proc/casino.money.proc.php',{type : type},function(data){
        if(type == 'point'){
            type = 'rolling_point';
            $.post('/proc/casino.money.proc.php',{type : type},function(data2){
                $('.loaderBg').css('display','none');
                 if(data == 1 || data2 == 1){
                    alert(title+'가 전환 되었습니다.');
                    document.location.reload();
                } else {
                    alert(data2);
                }
            });
        } else {
            $('.loaderBg').css('display','none');
             if(data == 1){
                alert(title+'가 전환 되었습니다.');
                document.location.reload();
            } else {
                alert(data);
            }
        }
    });

}
function moneyMoveOne(type,title){   

    if(!confirm(title+'를 전환 하시겠습니까?')){
        return;
    }

    $('.loaderBg').css('display','block');
    $.post('/proc/casino.money.proc.php',{type : type},function(data){
        
            $('.loaderBg').css('display','none');
             if(data == 1){
                alert(title+'가 전환 되었습니다.');
                document.location.reload();
            } else {
                alert(data);
            }
     
    });

}

function loadMoney(){
    $('#moneyMove').css('display','block');
}

function casinoMoneyMove(){
    var money = $('#casino_money').val();
    if(money <= 0){
        alert('0원 이상을 입력해주세요');
        return;
    }
    $('.loaderBg').css('display','block');
    $.post('/proc/casino.money.move.proc.php',{money : money},function(data){
        $('.loaderBg').css('display','none');
        if(data == 1){
            alert('머니가 이동 되었습니다.');
            document.location.reload();
        } else {
            alert(data);
        }
    });
}

function checkMoney(val){
    var money = $('#casino_money').val();
    if(val < money){
        $('#casino_money').val(val);
    }        
 }

setInterval(function(){
   ugml();
},30000);
$(document).ready(function(){
    setTimeout(function(){
        ugml();
    },1);
});


function ugml(){
    return;
    $.post('/proc/user.game.money.load.php',{load : 1},function(data){});
    $.post('data/user.game.money.load.php',{load : 1, sess : sess},function(data){
        return;
        if(data.indexOf('reload') > -1){  
            /*if(opWin)
                opWin.close();*/          
            setTimeout(function(){
                document.location.reload();
            },1000);
        }
        if(data){
            var json = JSON.parse(data);
            if(json.code == 1){

                $('.casinoMoney').text(json.casino_money.format());
                $('.userMoney').text(json.money.format());
                if(json.msgCount > 0 && window.location.href.indexOf('/partner/') == -1){
                    $('#msgBg').remove();
                    var msgHtml = '<div id="msgBg" style="width:100%; height:100%; position:fixed; top:0; left:0; background:RGBA(0,0,0,0.7); z-index:9999"> <div style="width: 300px; height: 105px; background: #fff; color: #000; position: absolute; top: calc(50% - 100px); left:  calc(50% - 160px); border: 5px solid #666; border-radius:10px; text-align: center; padding:30px 15px; font-weight: bold; cursor:pointer;" onclick="board(\'message\')">안 읽은 메세지가 <span style="color:orange">'+json.msgCount+'</span>개 있습니다.<br>메세지를 읽으셔야 게임이 가능합니다.</div></div>';
                    $('body').append(msgHtml);
                }
            } else {
                alert(json.message);
            }
        }
    });

}


Number.prototype.format = function(){
    if(this==0 || this==null) return 0;
 
    var reg = /(^[+-]?\d+)(\d{3})/;
    var n = (this + '');
 
    while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');
 
    return n;
};
 
// 문자열 타입에서 쓸 수 있도록 format() 함수 추가
String.prototype.format = function(){
    if(this==0 || this==null) return 0;
    var num = parseFloat(this);
    if( isNaN(num) ) return "0";
 
    return num.format();
};

var ipchulType = 2;
function moneyModal(ipchul){
ipchulType = ipchul;
var txt = "출금";
if(ipchul == 0){
    txt = "입금";
} 

$('.ipchulTitle').text(txt+'신청');
$('.loaderBg').css('display','block');
$.post('/partner/proc/ipchul.log.php',{type : ipchul},function(data){
    $('.loaderBg').css('display','none');
    var json = JSON.parse(data);
    var html = '';
    if(json.length > 0){
        html += '<br><table class="table table-bordered" width="100%" cellspacing="0">';
        html += '<thead>';
        html += '<tr>';
        html += '<th>구분</th>';
        html += '<th>금액</th>';
        html += '<th>날짜</th>';
        html += '<th>상태</th>';
        html += '</tr>';
        html += '</thead>';
        html += '<tbody>';
        
        for(var i = 0; i < json.length; i++){  
            html += '<tr>';                 
            html += '<td>'+json[i].type+'</td>';
            html += '<td>'+json[i].money+' 원</td>';
            html += '<td>'+json[i].reg_date+'</td>';                    
            html += '<td>'+json[i].state+'</td>';
            html += '</tr>';
        }
        
        html += '</tbody>';
        html += '</table>';
       
    } else {
        html = '<br><br><center>'+txt+' 내역이 없습니다.</center><br><br>';
    }
    $('#ipchullog').html(html);
});

}

function deposit() {
    var money = $("#ipchulMoney").val();

    if(money == 0){
        alert('입금 금액을 입력하세요.');
        return;
    }
    if(money % 10000 > 0){
        alert('만 단위로 신청가능합니다.');
        return;
    }

    $.ajax({
        type: "POST",
        url: "/api/addbalance",
        data: {money: money },
        cache: false,
        async: false,
        success: function (data) {
            if (data.error) {
                alert(data.msg);
                parent.closeIfr();
                return;
            }

            alert("신청완료 되었습니다.");
            parent.closeIfr();
        },
        error: function (err, xhr) {
            alert(err.responseText);
        },
    });
}

function requestAccount(){
    $.ajax({
        type: "POST",
        url: "/api/depositAccount",
        data: null,
        cache: false,
        async: false,
        success: function (data) {
            alert(data.msg);
        },
        error: function (err, xhr) {
            alert(err.responseText);
        },
    });

}


function withdraw() {
    var money = $("#ipchulMoney").val();

    if(money == 0){
        alert('출금 금액을 입력하세요.');
        return;
    }
    if(money % 10000 > 0){
        alert('만 단위로 신청가능합니다.');
        return;
    }

    $.ajax({
        type: "POST",
        url: "/api/outbalance",
        data: { money: money  },
        cache: false,
        async: false,
        success: function (data) {
            if (data.error) {
                alert(data.msg);
                parent.closeIfr();
                return;
            }

            alert("신청완료 되었습니다.");
            parent.closeIfr();
        },
        error: function (err, xhr) {
            alert(err.responseText);
        },
    });
}
