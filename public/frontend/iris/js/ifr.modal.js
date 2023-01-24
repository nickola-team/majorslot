function searchGo(){
    frm.submit();
}

function requestAccount(){
    $.post('/board/proc/requestBankAccount.php',{},function(data){
        if(data == 1){
            alert('요청되었습니다.');
            parent.board('person');
            parent.closeMenu();
        } else {
            alert(data);
        }
    });
}
