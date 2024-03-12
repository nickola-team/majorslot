function moneyMove(){
    if(!confirm('보너스를 전환 하시겠습니까?')){
        return;
    }

    $('.loaderBg').css('display','block');
    $.ajax({
        type: "POST",
        url: "/api/convert_deal_balance",
        data: null,
        cache: false,
        async: false,
        success: function (data) {
            $('.loaderBg').css('display','none');
            if (data.error) {
                alert(data.msg);
                return;
            }

            alert("보너스가 전환되었습니다.");
            document.location.reload();
        },
        error: function (err, xhr) {
            alert(err.responseText);
        },
    });


}


$(document).ready(function(){
    // setInterval(function(){
    //     ugml();
    //  },30000);
     ugml();
});


function ugml(){
    $.ajax({
        type: "POST",
        url: "/api/balance",
        data: null,
        cache: false,
        async: false,
        success: function (data) {
            if (data.error) {
                return;
            }
            $('.userMoney').text(data.balance);
            $('.userPoint').text(data.deal);
            if (data.msgCount > 0)
            {
                $('#msgBg').remove();
                var msgHtml = '<div id="msgBg" style="width:100%; height:100%; position:fixed; top:0; left:0; background:RGBA(0,0,0,0.7); z-index:9999"> <div style="width: 300px; height: 105px; background: #fff; color: #000; position: absolute; top: calc(50% - 100px); left:  calc(50% - 160px); border: 5px solid #666; border-radius:10px; text-align: center; padding:30px 15px; font-weight: bold; cursor:pointer;" onclick="board(\'message\')">안 읽은 메세지가 <span style="color:orange">'+data.msgCount+'</span>개 있습니다.<br>메세지를 읽으셔야 게임이 가능합니다.</div></div>';
                // $('body').append(msgHtml);
            }

        },
        error: function (err, xhr) {
            return;
        },
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
