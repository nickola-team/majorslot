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

        $.get("/modal/get_content", {
            type: type,
            page: page
        }, function(data) {

            var data = JSON.parse(data);

            if (!data.result) {
                alert(data.ment);
            } else {

                $modal.find(".modal-banner").html(data.banner);
                $modal.find(".modal-body").html(data.content);
                $modal.removeClass("deposit withdraw notice qna memo");
                $modal.addClass(type);

                $modal.modal();
            }
        });
    }
}