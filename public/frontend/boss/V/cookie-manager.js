function setCookie(key, value, exdays)
{
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
    document.cookie = key + "=" + c_value;
}

function getCookie(key) {
    var re = new RegExp(key + "=([^;]+)");
    var value = re.exec(document.cookie);
    var tt = (value != null) ? unescape(value[1]) : null;
    return tt;
}


function updateCookie(key, value)
{

}

function deleteCookie(key)
{
    document.cookie = key + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
}

function deleteAllCookies() {
    var cookies = document.cookie.split(";");
    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        var eqPos = cookie.indexOf("=");
        var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
    }
}