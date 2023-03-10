function writeMessage(){
    var subject = $('#subject').val();
    var writeArea = $('#writeArea').val();
    if(!subject){
        alert('제목을 입력해주세요');
        return;
    }
    if(!writeArea){
        alert('내용을 입력해주세요');
        return;
    }
    $('.loaderBg').css('display','block');
    $.post('/api/writeMsg', {
        title : subject, 
        content : writeArea, 
    },function(data){
        $('.loaderBg').css('display','none');
        if(data.error == false){
            alert('저장 되었습니다');
            parent.closeIfr();
        } else {
            alert(data.msg);
        }
    });
}

function writeReqAccountMsg(){
    var subject = '계좌요청입니다';
    var writeArea = '입금 계좌 요청드립니다';
    $('.loaderBg').css('display','block');
    $.post('/api/writeMsg', {
        title : subject, 
        content : writeArea, 
        type : 1,
    },function(data){
        $('.loaderBg').css('display','none');
        if(data.error == false){
            alert('저장 되었습니다');
            parent.closeIfr();
        } else {
            alert(data.msg);
        }
    });
}

function openNotice(idx){
    if($('#cont_'+idx).hasClass("open")){
        $('#cont_'+idx).css('display','none').removeClass('open');;
    } else {
        $('#cont_'+idx).css('display','table-row').addClass('open');
    }
}
function openMessage(idx){
    if($('#cont_'+idx).hasClass("open")){
        $('#cont_'+idx).css('display','none').removeClass('open');;
    } else {
        $('#cont_'+idx).css('display','table-row').addClass('open');
    }

    $.post('/api/readMsg',{id : idx},function(data){
    }); 
}
