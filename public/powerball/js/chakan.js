function showLoading(modal_name, title) {
    $("#" + modal_name).waitMe({
        effect: 'facebook',
        text: title,
        bg: 'rgba(255, 255, 255, 0.7)',
        color: 'black',
        maxSize: '',
        waitTime: -1,
        textPos: 'vertical',
        fontSize: '',
        source: '',
        onClose: function() {}
    });
}

function hideLoading(modal_name) {
    $("#" + modal_name).waitMe('hide');
}

function replaceComma(str) {
    if (str == null || str.length == 0) return "";
    while (str.indexOf(",") > -1) {
        str = str.replace(",", "");
    }
    return str;
}

function insertComma(str) {
    var val = str.split('.');
    var str2 = 0;
    if (val.length == 2) {
        str = val[0];
        str2 = val[1];
    }

    var rightchar = replaceComma(str);
    var moneychar = "";
    for (index = rightchar.length - 1; index >= 0; index--) {
        splitchar = rightchar.charAt(index);
        if (isNaN(splitchar)) {
            alert(splitchar + "Not a number Please enter again");
            return "";
        }
        moneychar = splitchar + moneychar;
        if (index % 3 == rightchar.length % 3 && index != 0) {
            moneychar = ',' + moneychar;
        }
    }
    str = moneychar;

    if (val.length == 2) {
        str = str + "." + str2;
    }

    return str;
}

function numChk(num) {
    var rightchar = replaceComma(num.value);
    var tmp = parseFloat(rightchar);
    rightchar = "" + tmp;
    if (isNaN(rightchar)) {} else {
        var moneychar = "";
        for (index = rightchar.length - 1; index >= 0; index--) {
            splitchar = rightchar.charAt(index);
            if (isNaN(splitchar)) {
                alert("'" + splitchar + "' ���ڰ� �ƴմϴ�.\n�ٽ� �Է����ֽʽÿ�.");
                num.value = "";
                num.focus();
                return false;
            }
            moneychar = splitchar + moneychar;
            if (index % 3 == rightchar.length % 3 && index != 0) {
                moneychar = ',' + moneychar;
            }
        }
        num.value = moneychar;
    }
    return true;
}

function pad(n, width) {
    n = n + '';
    return n.length >= width ? n : new Array(width - n.length + 1).join('0') + n;
}