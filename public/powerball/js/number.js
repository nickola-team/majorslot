function NumberAddComma(num) {
    var regexp = /\B(?=(\d{3})+(?!\d))/g;
    return num.toString().replace(regexp, ',');
}

function isNumeric(input) {
    var RE = /^-{0,1}[0-9\,]*\.{0,1}\d+$/;
    return (RE.test(input));
}

function number_format(num) {
    var reg = /(^[+-]?\d+)(\d{3})/;
    var n = num.toString();
    while (reg.test(n))
        n = n.replace(reg, '$1' + ',' + '$2');
    return n;
}

function number_format_remove(num) {
    return num.toString().replace(/,/g, "");
}

function changeNumberFormat(item, event) {
    if (event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40) {
        return;
    }
    item.value = number_format(number_format_remove(item.value));
}