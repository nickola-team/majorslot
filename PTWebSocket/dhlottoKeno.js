var express = require('express');
const request = require('request');
const Cookie = require('request-cookies').Cookie;
const crypto = require('crypto');
/*

"cmd=GETLOTTOINFO&lotto_code=KENO&userid=lee7699&etc=&end=E"
"cmd=GETJACKPOT&lotto_code=KENO&end=E"
"cmd=GETTIME&lotto_code=KENO&round_num=1248020&end=E"

"cmd=GETMESSAGE&lotto_code=KENO&userid=lee7699&end=E"

*/

var KenRoundData = {
    NowRound : 0,
    LastSeconds : 0,
    PrevWinNumbers : [],
    PrevWinSum : 0,
};
var DHCookies = {};

var CurrentUserIdx = -1;
const MaxLoggedInTime = 2400; // 40 min
var LoggedInTime = -1;

let UserInfo = [
    {
        id : 'kjosj', 'pwd' : 'kjpj@3651'
    },
    {
        id : 'lkw0411', 'pwd' : 'lkw@110127'
    },
    {
        id : 'ysw6841', 'pwd' : '@ysw2237188'
    },
    {
        id : 'viva79', 'pwd' : 'ju!1644017'
    },
    {
        id : 'k3616', 'pwd' : 'js@@sy0136'
    },
];

const DHLottoLogin = 'https://www.dhlottery.co.kr/userSsl.do?method=login';
const DHLottoKenoAPI = 'https://el.dhlottery.co.kr/game/keno/control.jsp';
const DHLottoKenoRefer = 'https://el.dhlottery.co.kr/game/keno/game.jsp';

function _urlEncode(e) {
    for (var o, t, s, n = "", r = 0, i = new RegExp("(^[a-zA-Z0-9_.-~]*)"); r < e.length;) o = i.exec(e.substr(r)), null != o && o.length > 1 && "" != o[1] ? (n += o[1], r += o[1].length) : (" " == e.substr(r, 1) ? n += "+" : (t = e.charCodeAt(r), s = t.toString(16), n += "%" + (s.length < 2 ? "0" : "") + s.toUpperCase()), r++);
    return n
}

function _queryStringToJSON(e)
{
    var o = e.split("&"),
    t = {};
    return o.forEach(function (e) {
        var o = e.substr(0, e.indexOf("=")),
            s = e.substr(e.indexOf("=") + 1);
        o.length && (void 0 !== t[o] ? (t[o].push || (t[o] = [t[o]]), t[o].push(s || "")) : t[o] = s || "")
    }), t
}

function encrypt(txt)
{
    var t = DHCookies.JSESSIONID;
    var s = t.substr(0, 32);
    n = crypto.randomBytes(32);
    r = crypto.randomBytes(16);
    i = crypto.pbkdf2Sync(s, n, 1000, 16, 'sha256');
    aescipher = crypto.createCipheriv(
        'aes-128-cbc',
        i,
        r,
      );
    u = aescipher.update(txt);
    l = n.toString('hex') + r.toString('hex') + Buffer.concat([u, aescipher.final()]).toString('base64');
    return _urlEncode(l);
}

function decrypt(e)
{
    var ss = DHCookies.JSESSIONID;
    var t = ss.substr(0, 32);
    s = e.substr(0, 64);
    n = e.substr(64, 32);
    r = e.substr(96);

    i = crypto.pbkdf2Sync(t, Buffer.from(s, "hex"), 1000, 16, 'sha256');
    aesdecipher = crypto.createDecipheriv(
        'aes-128-cbc',
        i,
        Buffer.from(n, "hex"),
    );
    u = aesdecipher.update(Buffer.from(r,'base64'));
    return Buffer.concat([u, aesdecipher.final()]).toString('utf-8');
}

function requestKenoData(query, callback)
{
    encdata = encrypt(query);

    cookies = [];
    for (const [key, value] of Object.entries(DHCookies)) {
        cookies.push(`${key}=${value}`);
    };
    const options = {
        uri: DHLottoKenoAPI,
        method: 'POST',
        headers : {
            'User-Agent' : 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36',
            'Accept' : '*/*',
            'Accept-Encoding': 'gzip, deflate, br',
            'Accept-Language': 'ko-KR,ko;q=0.9,en-US;q=0.8,en;q=0.7',
            'Cookie' : cookies.join('; '),            
            'Referer': DHLottoKenoRefer,
        },
        form: {
            q : encdata
        }
    };
    request.post(options, function (error, response, body) {
        if (!error) {
            r = body.replace(/[\r|\n]/gi, "");
            if (r.indexOf("page_not_found")  != -1)
            {
                console.log('Incorrect response');
                callback({
                    result_code : -1
                });
                return;
            }

            if (r.indexOf("result_code")  != -1)
            {
                console.log('Account is logged out');
                callback({
                    result_code : -2
                });
                return;
            }

            result = _queryStringToJSON(r);
            decdata = decrypt(result.q);
            console.log(decdata);
            result = _queryStringToJSON(decdata);
            callback(result);
            return;
        }
        callback({
            result_code : -3
        });
        return;
    });
}

function KenoData()
{
    currTime = new Date();
    sixDate = new Date(currTime.getFullYear(), currTime.getMonth(), currTime.getDate(), 06, 00, 00);
    
    deltaTime = sixDate.getTime() - currTime.getTime();
    if (deltaTime > 1000 * 60 )
    {
        console.log('Idle Time.');
        setTimeout(KenoData, 1000 * 60);
        return;
    }
    requestKenoData(`cmd=GETLOTTOINFO&lotto_code=KENO&userid=${UserInfo[CurrentUserIdx].id}&etc=&end=E`, (data) => {
        if (data.result_code == 99) // no sale time, Idle Time
        {
            setTimeout(KenoData, 1000 * 60);
            return;
        }
        if (data.result_code != 0)
        {
            console.log('Result code is ' + data.result_code);
            loginToDHLotto();
            return;
        }

        KenRoundData.NowRound = data.now_round_num;
        KenRoundData.LastSeconds = data.last_sec;
        var winnum = data.prev_winnum.split(':');
        var WinNumbers = [];
        var WinSum = winnum[1];
        for (m = 0; m < winnum[0].length; m += 2) {
            var f = Number(winnum[0].substr(m, 2));
            WinNumbers.push(f);
        }
        KenRoundData.PrevWinNumbers = WinNumbers;
        KenRoundData.PrevWinSum = WinSum;
        LoggedInTime = LoggedInTime - 1;
        if (LoggedInTime <= 0)
        {
            loginToDHLotto();
            return;
        }
        setTimeout(KenoData, 1000);

    });
}

function loginToDHLotto()
{
    var tryCnt = 0;
    var randIdx = Math.floor(Math.random() * UserInfo.length);
    while (tryCnt < 1000 && CurrentUserIdx == randIdx){
        randIdx = Math.floor(Math.random() * UserInfo.length);
        tryCnt = tryCnt + 1;
    }
    CurrentUserIdx = randIdx;
    console.log(`Login with ${UserInfo[CurrentUserIdx].id}`);

    const options = {
        uri: DHLottoLogin,
        method: 'POST',
        headers : {
            'User-Agent' : 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36',
            'Accept' : 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Accept-Encoding': 'gzip, deflate, br',
            'Accept-Language': 'ko-KR,ko;q=0.9',
            'Referer': 'https://dhlottery.co.kr',
        },
        form: {
            returnUrl:'/',
            newsEventYn : null,
            token : crypto.randomBytes(16).toString('hex'),
            userId : UserInfo[CurrentUserIdx].id,
            password : UserInfo[CurrentUserIdx].pwd,
            checkSave : 'on'
        }
    };
    request.post(options, function (error, response, body) {
        if (!error) {
            if (response.statusCode == 302){
                redirectUrl = response.headers['location'];
                if (redirectUrl.indexOf('/common.do?method=loginResult') != -1)
                {
                    var rawcookies = response.headers['set-cookie'];
                    for (var i in rawcookies) {
                        var cookie = new Cookie(rawcookies[i]);
                        DHCookies[cookie.key] = cookie.value;
                    }
    
                    console.log('Login Successed');
                    LoggedInTime = MaxLoggedInTime;
                    setTimeout(KenoData, 1000);
                    return;
                }
                else
                {
                    console.log('Login Failed');
                }
            }
        }
        //failed with any reason
        setTimeout(loginToDHLotto, 3000);
    });
}


loginToDHLotto();

var server = express();
server.get('/dhlotto/keno', function(req, res){
    var apikey = req.query.key;
    if (apikey != 'major33456')
    {
        res.json({
            Msg : 'Need api key'
        });
    }
    res.json(KenRoundData);
});
server.get('/', function(req, res){
    res.json({
        Msg : 'Welcome to DHLotto page'
    });
});
server.listen(8800);