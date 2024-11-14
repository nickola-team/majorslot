function num_from_comma(str) {
    str += '';
    str = str.replace(/,/gi, '');
    return str;
}

function comma_from_num(num) {
    num = num_from_comma(num);
    var reg = /(^[+-]?\d+)(\d{3})/;
    num += '';

    while (reg.test(num)) {
        num = num.replace(reg, '$1' + ',' + '$2');
    }

    return num;
}


function setCookie(cookie_name, value, days) {
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + days);
    // 설정 일수만큼 현재시간에 만료값으로 지정

    var cookie_value = escape(value) + ((days == null) ? '' : '; expires=' + exdate.toUTCString());
    document.cookie = cookie_name + '=' + cookie_value;
}

function getCookie(cookie_name) {
    var x, y;
    var val = document.cookie.split(';');

    for (var i = 0; i < val.length; i++) {
        x = val[i].substr(0, val[i].indexOf('='));
        y = val[i].substr(val[i].indexOf('=') + 1);
        x = x.replace(/^\s+|\s+$/g, ''); // 앞과 뒤의 공백 제거하기
        if (x == cookie_name) {
            return unescape(y); // unescape로 디코딩 후 값 리턴
        }
    }
}

function addCookie(cookie_name, add_val) {
    var items = getCookie(cookie_name); // 이미 저장된 값을 쿠키에서 가져오기
    var maxItemNum = 3; // 최대 저장 가능한 아이템개수
    var expire = 7; // 쿠키값을 저장할 기간
    if (items) {
        var itemArray = items.split('|');

        if (itemArray.indexOf(add_val) != -1) {
            // 이미 존재하는 경우 종료
            console.log('Already exists.');
        } else {
            // 새로운 값 저장 및 최대 개수 유지하기
            itemArray.unshift(add_val);
            if (itemArray.length > maxItemNum) itemArray.length = maxItemNum;
            items = itemArray.join('|');
            setCookie(cookie_name, items, expire);
        }
    } else {
        // 신규 id값 저장하기
        setCookie(cookie_name, add_val, expire);
    }
}


function getToday() {
    var date = new Date();
    var year = date.getFullYear();
    var month = ("0" + (1 + date.getMonth())).slice(-2);
    var day = ("0" + date.getDate()).slice(-2);

    return year + "-" + month + "-" + day;
}