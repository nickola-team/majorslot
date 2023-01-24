var idx = 0;
var mode = 'write';
 function save(){
    var subject = $('#subject').val();
    var writeArea = $('#writeArea').val();
    var sub = $('#sub').val();
    var start_date = $('#start_date_ev').val();
    var end_date = $('#end_date_ev').val();
    var popup_w = $('#popup_w').val();
    var popup_h = $('#popup_h').val();
    var popup_x = $('#popup_x').val();
    var popup_y = $('#popup_y').val();
    var popup_padding = $('#popup_padding').val();
    var popup_bg = $('#popup_bg').val();
    if($('#popup_ban').is(':checked')){
        popup_bg = 'ban';
    }

    var reg_date = $('#reg_dates').val();
    var view = 0;
    var messageUser = $('#sendUserSelect').val();

    if(messageUser == -1){
        alert('보낼유저를 선택해주세요');
        return;
    }
    if($('#view').is(':checked')){
        view = 1;
    }
    if(!subject){
        alert('제목을 입력해주세요');
        return;
    }
    if(!writeArea){
        alert('내용을 입력해주세요');
        return;
    }
    $('.loaderBg').css('display','block');
    $.post('/board/proc/write.board.php', {
        subject : subject, 
        contents : writeArea, 
        view : view, 
        board_idx : board_idx, 
        idx : idx, 
        mode : mode, 
        sub : sub, 
        start_date : start_date, 
        end_date : end_date,
        popup_w : popup_w,
        popup_h : popup_h,
        popup_x : popup_x,
        popup_y : popup_y,
        popup_padding : popup_padding,
        popup_bg : popup_bg,
        messageUser : messageUser,
        reg_date : reg_date
    },function(data){
        $('.loaderBg').css('display','none');
        if(data == 1){
            alert('저장 되었습니다');
            console.log(document.location.href.indexOf('/menu/board'));
            if(type == 'message' && document.location.href.indexOf('/menu/board') !== -1){
                document.location.href = '/menu/board.php?type=message&title=보드';
            } else {
                document.location.reload();
            }
        } else {
            alert(data);
        }
    });
}
var addLine = "\r\n\r\n──────────────────────────\r\n\r\n";
function writeMode(m, idxx){
    $('#subject').val('');
    $('#writeArea').val('');
    $('#sub').val('');
    $('#start_date_ev').val('');
    $('#end_date_ev').val('');
    $('#view').prop('checked',false);
    loadBoardInit();
    if(m == 'write'){
        idx = 0;
    } else if(m == 'modi'){
        idx = idxx;    
        $('.loaderBg').css('display','block');                            
        $.post('/board/proc/load.board.php', {idx : idxx},function(data){
            $('.loaderBg').css('display','none');
            var json = JSON.parse(data);
            $('#subject').val(json.subject);
            $('#writeArea').val(json.contents);            
            $('#sub').val(json.sub_title);
            $('#start_date_ev').val(json.start_date);
            $('#end_date_ev').val(json.end_date);
            $('#reg_dates').val(json.reg_date);
            $('#view').prop('checked',false);
            if(json.is_view == 1){
                $('#view').prop('checked',true);
            }
            var popupSize = json.popup_size.split(',');
            $('#popup_w').val(popupSize[2]);
            $('#popup_h').val(popupSize[3]);
            $('#popup_x').val(popupSize[0]);
            $('#popup_y').val(popupSize[1]);
            $('#popup_padding').val(json.popup_padding);
            if(json.popup_background == 'ban'){
                $('#popup_bg').val('#000');
                $('#popup_ban').prop('checked',true);
            } else {
                $('#popup_bg').val(json.popup_background);
            }
            
            loadBoard();
        });
    } else if(m == 'reply'){
        $('#reply').text('답변');
        idx = idxx;
        $('#subject').val($('#subj_'+idx).text()).attr('disabled',true);
        $('#writeArea').val($('#cont_'+idx+' .contents').text()+addLine);
    }
     mode = m;

}

function viewChange(idx, num){
    if(num == 2){
        if(!confirm('삭제 하시겠습니까?')){
            return;
        }
    } else {
         if(!confirm('노출 변경 하시겠습니까?')){
            if($('.is_view_'+idx).is(':checked')){
                $('.is_view_'+idx).prop('checked',false);
            } else {
                $('.is_view_'+idx).prop('checked',true);
            }
            return;
        }
        var num = 0;
        if($('.is_view_'+idx).is(':checked')){
            num = 1;
        }
    }
    $('.loaderBg').css('display','block');
     $.post('/board/proc/viewChange.board.php', {idx : idx, num : num},function(data){
        $('.loaderBg').css('display','none');
        if(data == 1){
            if(num == 2){
                alert('삭제 되었습니다');
                document.location.reload();
            } else {
                 alert('변경 되었습니다');
            }
            
        } else {
            alert(data);
        }
    });
}                       

function openContents(idx){
    if($('#cont_'+idx).hasClass("open")){
        $('#cont_'+idx).css('display','none').removeClass('open');;
    } else {
        $('#cont_'+idx).css('display','table-row').addClass('open');
    }

    if((type == 'message' && type2 == 'receive') || type == 'person'){
        $.post('/board/proc/messageRead.proc.php',{idx : idx},function(data){
        }); 
        console.log($('#readStates'+idx));
        $('#readStates'+idx).text('읽음').removeClass('text-warning').addClass('text-primary');
    }
}

function readAll(){
    $.post('/board/proc/messageReadAll.proc.php',{idx : idx},function(data){
        });
    $('.readStatesAll').text('읽음').removeClass('text-warning').addClass('text-primary');
}

function accountPlz(){
    $.post('/proc/accountPlz.php',{},function(data){
        console.log(data);
        var json = JSON.parse(data);
        if(json.rtn == 2){
            $('#subject').val(json.subject);
            $('#writeArea').val(json.text);
        } else if(json.rtn == 1){
            parent.iframeOn('/menu/account.php?account='+json.text);
            
        }
    });
}