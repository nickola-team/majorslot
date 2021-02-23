var spin_obj = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <span class="sr-only">Loading...</span>';
var icon_obj = '<i class="material-icons">view_module</i>';

var gameTypeLoaded = false;
//var option_obj = '<option value="" selected="selected">Select</option>';

$(document).ready(function() {
    $(document).on('click', '.btn-detail', function(event) {
        let target = $(this).attr('data-target');
        if ($(target).length === 0) {
            let rid = target.replace('#modal_', '');
            loadDetail(rid);
            $(this).children('i').remove();
            $(this).append(spin_obj);
            $(this).attr('disabled', 'disabled');
        }

        // $('notice').alert();
    });

    if (!gameTypeLoaded) {
        let gid = document.getElementById('game_id').value;
        let firm = document.getElementById('firm').value;
        let player = document.getElementById('player').value;
        if (gid || (firm && player)) {
            loadGameType(gid, firm, player);
        }
    }
});

function loadDetail(rid) {
    $.ajax({
            url: 'getGameDetail.php',
            type: 'POST',
            dataType: 'html',
            data: {
                rid: rid
            },
        })
        .done(function(resp_text) {
            console.log(resp_text);
            let main = document.getElementById('main');
            main.innerHTML = main.innerHTML + resp_text;
            $('#modal_' + rid).modal('show');
            // console.log("success");
        })
        .fail(function() {
            console.log("error");
        })
        .always(function(resp_text) {
            $('#btn_' + rid).children('span').remove();
            $('#btn_' + rid).append(icon_obj);
            $('#btn_' + rid).prop("disabled", false);
        });
}

function loadGameType(gid = null, firm = null, player = null) {
    $.ajax({
            url: 'getPlayerGameType.php',
            type: 'POST',
            dataType: 'html',
            data: {
                gameId: gid,
                firm: firm,
                player: player
            },
        })
        .done(function(resp_text) {
            console.log(resp_text);
            let types = JSON.parse(resp_text);
            let select_root = document.getElementById('game');

            select_root.options.length = 1;
            for (let key in types) {
                select_root.options[select_root.options.length] = new Option(types[key], key);
            }

            gameTypeLoaded = true;
        })
        .fail(function() {
            console.log("load game type error");
        })
        .always(function(resp_text) {
            //console.log("always");
        });
}