// added by oh7even
var types = [
    'all',
    'pb_odd_even',
    'pb_under_over',
    'st_odd_even',
    'st_under_over'
]
var patterns = [];
var type;
var pageSize = 20;

function searchType_change() {
    type = $("#searchKind").val();  
    types.forEach(x => {
        if(x == type) {
            $('#' + x).show();
        } else {
            $('#' + x).hide();
        }
    });
}

function rowNumber_change() {
    pageSize = parseInt($("#rowNums").val());  
    if(pageSize == undefined) {
        pageSize = 20;
    }
}

function pattern_click(type, index) {    
    if(patterns.length < (index + 1)) {
        for(var i = patterns.length; i <= index; i ++) {
            patterns.push(0);
        }
    }
    patterns[index] ++
    patterns[index] = patterns[index] % 3;    
    showPattern(type, index, patterns[index]);
}

function search_click() {
    var from = $("#tbxStartDate").val();
    var to = $("#tbxEndDate").val();
    var param = {
        type,
        from,
        to,
        patterns
    }
    $(".lds-spinner").show();
    $(".btn_search").attr('disabled', true);
    $.ajax({
        type: 'POST',
        url: host + '/api/searchResult',
        crossDomain: true,
        data: param,
        dataType: 'json',
        success: (data) => {
            $(".lds-spinner").hide();
            $(".btn_search").attr('disabled', false);
            var results = data.results;
            var strHtml = `<tr> <td>${results.total}</td>` +
                `<td>${results.pb_odd}</td>` +
                `<td>${results.pb_even}</td>` +
                `<td>${results.st_odd}</td>` +
                `<td>${results.st_even}</td>` +
                `<td>${results.pb_over}</td>` +
                `<td>${results.pb_under}</td>` +
                `<td>${results.st_over}</td>` +
                `<td>${results.st_under}</td> </tr>`;
            $("#dashboard").html(strHtml);
            buildPagiNation(results.data);            
        }
    });
}

function buildPagiNation(dataSource) {    
    let container = $('#pagination');
    container.pagination({
        dataSource,
        pageSize,
        callback: function (results, pagination) {
            $("#searchResult").html('');
            results.forEach(x => {
                var created_at = moment(x.created_at).format("YYYY-MM-DD hh:mm:ss");
                var g_info = x.g_info.split(',');

                var recent_pb_odd_even = x.recent_pb_odd_even.replace(/1/gi, '��,');
                recent_pb_odd_even = recent_pb_odd_even.replace(/2/gi, '吏�,');

                recent_pb_under_over = x.recent_pb_under_over.replace(/U/gi, '��,');
                recent_pb_under_over = recent_pb_under_over.replace(/O/gi, '��,');

                var recent_st_odd_even = x.recent_st_odd_even.replace(/1/gi, '��');
                recent_st_odd_even = recent_st_odd_even.replace(/2/gi, '吏�');

                var recent_st_under_over = x.recent_st_under_over.replace(/U/gi, '��,');
                recent_st_under_over = recent_st_under_over.replace(/O/gi, '��,');
                var strHtml = `<tr> <td>${created_at}</td>` +
                `<td>${(x.cur_round)}(${x.total_round})</td>` +
                `<td>${x.g_values}</td>` +
                `<td>${g_info[0]}</td>` +
                `<td>${g_info[1]}</td>` +
                `<td>${g_info[2]}</td>` +
                `<td>${g_info[3]}</td>` +
                `<td style="text-overflow: ellipsis; overflow: hidden; white-space: nowrap;" title="${recent_st_odd_even}">${recent_st_odd_even}</td>` +
                `<td style="text-overflow: ellipsis; overflow: hidden; white-space: nowrap;" title="${recent_st_under_over}">${recent_st_under_over}</td>` +
                `<td style="text-overflow: ellipsis; overflow: hidden; white-space: nowrap;" title="${recent_pb_odd_even}">${recent_pb_odd_even}</td>` +
                `<td style="text-overflow: ellipsis; overflow: hidden; white-space: nowrap;" title="${recent_pb_under_over}">${recent_pb_under_over}</td>` +
                '</tr>';
                $("#searchResult").append(strHtml);
            });
        }
    })
}
function showPattern(type, index, val) {
    var id = type + index;
    var path = "/images/icon_";
    if(val == 0) {
        path += 'standard';
    } else {
        if(type == "pb_odd_even") {
            path += 'power_';
            if(val == 1) {
                path += 'odd';
            } else if(val == 2) {
                path += 'even';
            }
        } else if(type == "pb_under_over") {
            path += 'power_';
            if(val == 1) {
                path += 'over';
            } else if(val == 2) {
                path += 'under';
            }
        } else if(type == "st_odd_even") {
            path += 'normal_';
            if(val == 1) {
                path += 'odd';
            } else if(val == 2) {
                path += 'even';
            }
        } else if(type == "st_under_over") {
            path += 'normal_';
            if(val == 1) {
                path += 'over';
            } else if(val == 2) {
                path += 'under';
            }
        }
    }
    path += '.png';
    $('#' + id).attr('src', path);    
}