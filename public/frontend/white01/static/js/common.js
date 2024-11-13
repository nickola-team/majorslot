$(document).ready(function() {

    if (is_login == 'Y') realtime_refresh();

    $(document).on('click', '#ckbs', function() {
        if ($(this).prop('checked')) {
            $('.ckb').prop('checked', true);
        } else {
            $('.ckb').prop('checked', false);
        }
    });

});



function realtime_refresh() {
    $.get("/common/realtime_refresh", function(data) {

        var data = JSON.parse(data);


        if (data.result) {

            var preparing_casinos = data['preparing_casinos'];
            var preparing_slots = data['preparing_slots'];
            var msg_data = data['msg'];
            var qna_data = data['qna'];
            var user_data = data['user_info'];
            const api_config = data["api_config"];

            //보유커니션 갱신
            $("div.user-point span").text("포인트(" + comma_from_num(user_data["point"]) + "P)");

            //점검중 게임사체크
            $(".comp-panel").removeClass("preparing");

            for (casino of preparing_casinos) {
                $(".game-list:not(.slot) .comp-panel.id_" + casino.id).addClass("preparing");
            }

            for (slot of preparing_slots) {
                $(".game-list.slot .comp-panel.id_" + slot.id).addClass("preparing");
            }

            for (game_type in api_config) {

                if (api_config[game_type] == "Y") continue;

                if (game_type == "slot") {
                    $(".comp-panel.slot, .comp-panel.live").addClass("preparing");

                } else if (game_type == "evol") {
                    $(".comp-panel.evol, .comp-panel.evol_s").addClass("preparing");

                } else {
                    $(".comp-panel." + game_type).addClass("preparing");
                }

            }


            $modal = $(".modal.msg");

            if (parseInt(msg_data) > 0 && !$modal.hasClass("show")) {
                alert('쪽지가 도착했습니다.');
                open_modal("msg");
            } else {
                $qna_modal = $(".modal.qna");
                if (qna_data.length > 0 && !$qna_modal.hasClass("show")) {
                    alert('고객센터 문의에 답변이 작성 되었습니다.');
                    open_modal("qna");
                }
            }

            setTimeout(realtime_refresh, 5000);


        } else {

            location.href = "/";
        }

    })
}

function trans_point() {

    // var con = confirm("포인트를 게임머니로 전환하시겠습니까?");
    // if (!con) return false;

    // $.get("/common/trans_point", function(data) {

    //     var data = JSON.parse(data);
    //     console.log(data);
    //     alert(data.ment);
    //     if (data.result) location.reload();
    // })
    if (confirm('포인트를 게임머니로 전환하시겠습니까?')) {
        $.ajax({
          url: '/api/convert_deal_balance',
          type: 'POST',
          dataType: "json",
          data : null,
          success: function(result) {
  
              if (result.error == false)
              {
                alert('전환되었습니다');
                location.reload();
              }else{
                  alert(result.msg);
              }
          }
        });
      }
}


function reprice_point(amount) {

    var apply_amount = $('input[name=apply_amount]');

    if (amount == 0) {

        apply_amount.val('');
    } else {

        if (apply_amount.val() == '') {

            apply_amount.val(comma_from_num(parseInt(amount)));
        } else {

            amount = parseInt(amount) + parseInt(num_from_comma(apply_amount.val()));
            apply_amount.val(comma_from_num(amount))
        }

    }
}