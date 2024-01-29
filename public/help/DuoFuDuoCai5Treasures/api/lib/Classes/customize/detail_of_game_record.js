let icons = {
    time: 'far fa-clock',
    date: 'fas fa-calendar',
    up: 'fas fa-chevron-up',
    down: 'fas fa-chevron-down',
    previous: 'fas fa-angle-double-left',
    next: 'fas fa-angle-double-right',
    today: 'fas fa-screenshot',
    clear: 'fas fa-trash',
    close: 'fas fa-remove'
}
$(document).ready(function() {
    var current = new Date();

    var month = current.getMonth() + 1;
    var day = current.getDate();
    var year = current.getFullYear();
    if (month < 10) {
        month = '0' + month;
    }
    let cur_date = year + "-" + month + "-" + day;
    let dateFrom = moment().subtract(7, 'd').format('YYYY-MM-DD');

    $('.dtpicker').datetimepicker({
        icons,
        format: 'YYYY-MM-DD HH:mm:ss',
        useCurrent: 'day',
        ignoreReadonly: true,
        focusOnShow: false
    });
    $('#input_startTime').data("DateTimePicker").minDate(dateFrom);
    $('#input_endTime').data("DateTimePicker").minDate(dateFrom);
    $('#input_endTime').data("DateTimePicker").maxDate(cur_date + " 23:59:59");
    $("#input_startTime").on("dp.change", function(e) {
        $('#input_endTime').data("DateTimePicker").minDate(e.date);
    });
    $("#input_endTime").on("dp.change", function(e) {
        $('#input_startTime').data("DateTimePicker").maxDate(e.date);
    });
    $("#input_startTime").attr('value', cur_date + " 00:00:00");
    $("#input_endTime").attr('value', cur_date + " 23:59:59");
});