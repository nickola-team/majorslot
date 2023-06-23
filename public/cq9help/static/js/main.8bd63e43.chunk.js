(this.webpackJsonpwoodstock = this.webpackJsonpwoodstock || []).push([
    [0], {
        32: function(e) {
            e.exports = JSON.parse('{"name":"woodstock","version":"0.4.32","private":true,"dependencies":{"@testing-library/jest-dom":"^5.11.4","@testing-library/react":"^11.1.0","@testing-library/user-event":"^12.1.10","axios":"^0.24.0","classnames":"^2.3.1","http-proxy-middleware":"^1.0.6","immutable":"^4.0.0","lodash":"^4.17.21","react":"^17.0.2","react-dom":"^17.0.2","react-icons":"^4.3.1","react-scripts":"4.0.3","styled-components":"^5.3.3","uuid":"^8.3.2","web-vitals":"^1.0.1","yarn":"^1.22.17","chinese-conv":"^1.0.1"},"scripts":{"start":"react-scripts start","build":"react-scripts build","start168":"yarn Env168 react-scripts start","build168":"yarn Env168 react-scripts build","Env168":"BUILD_PATH=\'./build168\' REACT_APP_SITE_TYPE=\'168\' REACT_APP_API_DOMAIN=\'rebirth.games\' REACT_APP_API_DOMAIN_PROD=\'rebirth.games\' REACT_APP_API_DOMAIN_PREFIX=\'\' REACT_APP_IMG_DOMAIN=\'s3.ap-northeast-1.amazonaws.com/prd-images.rebirth.games\' ","test":"react-scripts test","eject":"react-scripts eject"},"eslintConfig":{"extends":["react-app","react-app/jest"]},"browserslist":{"production":[">0.2%","not dead","not op_mini all"],"development":["last 1 chrome version","last 1 firefox version","last 1 safari version"]}}')
        },
        37: function(e, t, n) {},
        62: function(e, t, n) {
            "use strict";
            n.r(t);
            var a, i, c, s, l, o, r, m, d, b, g, j, y, h, u, x, p, f, O, v, N, S, w, I = n(1),
                L = n.n(I),
                z = n(27),
                _ = n.n(z),
                D = (n(37), n(6)),
                P = n(5),
                T = n(4),
                k = n(3),
                C = n(8),
                F = n(9),
                H = window.location.protocol,
                W = "".concat(H, "//").concat("images.cq9web.com"),
                A = function(e) {
                    var t = e.html,
                        n = void 0 === t ? "" : t,
                        a = e.game_id,
                        i = void 0 === a ? "1" : a,
                        c = function() {
                            var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : "";
                            switch (e) {
                                case "WW":
                                case "WILD":
                                    return "W";
                                case "SF":
                                case "SC":
                                case "FREE GAME ICONS":
                                case "SCATTER":
                                case "symbol":
                                case "symbol_F":
                                    return "F";
                                case "SB":
                                case "SBS":
                                    return "SBS";
                                case "FREE":
                                case "icons":
                                    return "icons";
                                default:
                                    return e
                            }
                        };
                    return function() {
                        var e = n;
                        if (1 !== n.split('<figure class="table">').length)
                            for (var t = 0; t < n.split('<figure class="table">').length - 1; t++) e = e.replace('<figure class="table">', '<figure class="table-ckeditor">');
                        var a = new RegExp("(\\[).+?(\\])", "g"),
                            s = n.match(a),
                            l = null === s || void 0 === s ? void 0 : s.map((function(e) {
                                return e.slice(1, -1)
                            }));
                        if (l)
                            for (var o = 0; o < l.length; o++) e = e.replace("[".concat(l[o], "]"), '<img src="'.concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(c(l[o]), '.png" alt="" />'));
                        if (1 !== n.split("<p>#").length)
                            for (var r = 0; r < n.split("<p>#").length - 1; r++) e = e.replace("<p>#", '<p class="indent">');
                        if (1 !== n.split("<p>!").length)
                            for (var m = 0; m < n.split("<p>!").length - 1; m++) e = e.replace("<p>!", '<p class="img-text">');
                        if (1 !== n.split("<p>^").length)
                            for (var d = 0; d < n.split("<p>^").length - 1; d++) e = e.replace("<p>^", '<p class="highlight">');
                        if (1 !== n.split("<p>@").length)
                            for (var b = 0; b < n.split("<p>@").length - 1; b++) e = e.replace("<p>@", '<p class="no-dot">');
                        var g = document.createElement("div");
                        g.innerHTML = e;
                        var j = [];
                        return g.childNodes.forEach((function(e, t) {
                            if ("P" === e.tagName) {
                                var n = [];
                                e.childNodes.forEach((function(e) {
                                    if (void 0 === e.tagName) {
                                        var t = new RegExp("\\d+X|\\d+x|\\d+", "g"),
                                            a = e.textContent.replace(t, (function(e) {
                                                return '<span class="number">'.concat(e, "</span>")
                                            }));
                                        n.push(a)
                                    } else n.push(e.outerHTML)
                                })), j.push('<p class="'.concat(e.className, '">').concat(n.join(""), "</p>"))
                            } else j.push(e.outerHTML)
                        })), j.reduce((function(e, t) {
                            return (e || "") + (t || "")
                        }), "")
                    }()
                },
                M = function(e) {
                    var t = e,
                        n = new RegExp("(\\[).+?(\\])", "g"),
                        a = e.match(n),
                        i = null === a || void 0 === a ? void 0 : a.map((function(e) {
                            return e.slice(1, -1)
                        }));
                    if (i)
                        for (var c = 0; c < a.length; c++) t = t.replaceAll("[".concat(i[c], "]"), '<img src="'.concat(W, "/help/common/currency/").concat(i[c], '.png" alt=""/> &nbsp;'));
                    return {
                        __html: t
                    }
                },
                E = n.p + "static/media/UI_Button.f4df4ad0.mp3",
                Y = n.p + "static/media/UI_Button_GB.bf81f694.mp3",
                R = function(e) {
                    var t = window.location.search.split("?"),
                        n = t.slice(1, t.length).join("");
                    return new URLSearchParams(n).get(e)
                },
                B = {
                    Android: function() {
                        return navigator.userAgent.match(new RegExp("Android", "i"))
                    },
                    BlackBerry: function() {
                        return navigator.userAgent.match(new RegExp("BlackBerry", "i"))
                    },
                    iPhone: function() {
                        return navigator.userAgent.match(new RegExp("iPhone", "i"))
                    },
                    Opera: function() {
                        return navigator.userAgent.match(new RegExp("Opera Mini", "i"))
                    },
                    Windows: function() {
                        return navigator.userAgent.match(new RegExp("IEMobile", "i"))
                    },
                    any: function() {
                        return B.Android() || B.BlackBerry() || B.iPhone() || B.Opera() || B.Windows()
                    }
                },
                G = {
                    default: {
                        id: "0",
                        symbol: ""
                    },
                    cny: {
                        id: "1",
                        symbol: "\xa5"
                    },
                    twd: {
                        id: "2",
                        symbol: "$"
                    },
                    usd: {
                        id: "3",
                        symbol: "$"
                    },
                    krw: {
                        id: "4",
                        symbol: "\u20a9"
                    },
                    jpy: {
                        id: "5",
                        symbol: "\xa5"
                    },
                    php: {
                        id: "6",
                        symbol: "\u20b1"
                    },
                    thb: {
                        id: "7",
                        symbol: "\u0e3f"
                    },
                    myr: {
                        id: "8",
                        symbol: "[rm]"
                    },
                    vnd: {
                        id: "9",
                        symbol: "[vnd]"
                    },
                    eur: {
                        id: "10",
                        symbol: "\u20ac"
                    },
                    mmk: {
                        id: "11",
                        symbol: "K"
                    },
                    idr: {
                        id: "12",
                        symbol: "[rp]"
                    },
                    aud: {
                        id: "13",
                        symbol: "$"
                    },
                    sgd: {
                        id: "14",
                        symbol: "$"
                    },
                    gbp: {
                        id: "15",
                        symbol: "\xa3"
                    },
                    inr: {
                        id: "16",
                        symbol: "\u20b9"
                    },
                    "vnd(k)": {
                        id: "17",
                        symbol: "[vndk]"
                    },
                    "idr(k)": {
                        id: "18",
                        symbol: "[rpk]"
                    },
                    hkd: {
                        id: "19",
                        symbol: "[hk]"
                    },
                    cqp2: {
                        id: "20",
                        symbol: ""
                    },
                    mmkp1: {
                        id: "21",
                        symbol: ""
                    },
                    cfc: {
                        id: "22",
                        symbol: "[cfc]"
                    },
                    rub: {
                        id: "23",
                        symbol: "\u20bd"
                    },
                    pln: {
                        id: "24",
                        symbol: "z\u0142"
                    },
                    dimc: {
                        id: "25",
                        symbol: "[dimc]"
                    },
                    godc: {
                        id: "26",
                        symbol: "[godc]"
                    },
                    mbtc: {
                        id: "27",
                        symbol: "[mbtc]"
                    },
                    meth: {
                        id: "28",
                        symbol: "[meth]"
                    },
                    eos: {
                        id: "29",
                        symbol: "[eos]"
                    },
                    mltc: {
                        id: "30",
                        symbol: "[mltc]"
                    },
                    khr: {
                        id: "31",
                        symbol: "\u17db"
                    },
                    ygb: {
                        id: "32",
                        symbol: "[ygb]"
                    },
                    egc: {
                        id: "33",
                        symbol: ""
                    },
                    "khr(moha)": {
                        id: "34",
                        symbol: ""
                    },
                    sek: {
                        id: "35",
                        symbol: "[sek]"
                    },
                    nok: {
                        id: "36",
                        symbol: "[nok]"
                    },
                    cad: {
                        id: "37",
                        symbol: "[cad]"
                    },
                    usdx: {
                        id: "38",
                        symbol: "[usdx]"
                    },
                    ezgc: {
                        id: "39",
                        symbol: ""
                    },
                    blc: {
                        id: "40",
                        symbol: "[blc]"
                    },
                    ct: {
                        id: "41",
                        symbol: "[ct]"
                    },
                    dccb: {
                        id: "42",
                        symbol: "[dccb]"
                    },
                    otc: {
                        id: "43",
                        symbol: "[otc]"
                    },
                    "dscb(k)": {
                        id: "44",
                        symbol: "[dscbk]"
                    },
                    hda: {
                        id: "45",
                        symbol: "[hda]"
                    },
                    poc: {
                        id: "46",
                        symbol: "[poc]"
                    },
                    usdt: {
                        id: "47",
                        symbol: "[usdt]"
                    },
                    mbhd: {
                        id: "48",
                        symbol: "[mbhd]"
                    },
                    meos: {
                        id: "49",
                        symbol: "[meos]"
                    },
                    mxn: {
                        id: "50",
                        symbol: "[mxn]"
                    },
                    wicks: {
                        id: "51",
                        symbol: ""
                    },
                    bb$: {
                        id: "52",
                        symbol: "[bb]"
                    },
                    "mmk(100)": {
                        id: "53",
                        symbol: "[mmk100]"
                    },
                    bet: {
                        id: "54",
                        symbol: "[bet]"
                    },
                    brl: {
                        id: "55",
                        symbol: "R$"
                    },
                    kes: {
                        id: "56",
                        symbol: "[kes]"
                    },
                    "inr(0.01)": {
                        id: "57",
                        symbol: ""
                    },
                    zar: {
                        id: "58",
                        symbol: "R"
                    },
                    ils: {
                        id: "59",
                        symbol: "\u20aa"
                    },
                    try: {
                        id: "60",
                        symbol: "\u20ba"
                    },
                    mgc: {
                        id: "61",
                        symbol: "[mgc]"
                    },
                    bnd: {
                        id: "62",
                        symbol: "B$"
                    },
                    "mmk(k)": {
                        id: "63",
                        symbol: "[mmkk]"
                    },
                    cop: {
                        id: "64",
                        symbol: "[cop]"
                    },
                    bdt: {
                        id: "65",
                        symbol: "\u09f3"
                    },
                    "usdt(0.1)": {
                        id: "66",
                        symbol: "[usdt01]"
                    },
                    clp: {
                        id: "67",
                        symbol: "$"
                    },
                    ubtc: {
                        id: "68",
                        symbol: "[ubtc]"
                    },
                    doge: {
                        id: "69",
                        symbol: "\xd0"
                    },
                    pkr: {
                        id: "70",
                        symbol: "Rs"
                    },
                    pk5: {
                        id: "71",
                        symbol: "Rs"
                    },
                    lkr: {
                        id: "72",
                        symbol: "Rs"
                    },
                    irr: {
                        id: "73",
                        symbol: "\ufdfc"
                    },
                    nzd: {
                        id: "74",
                        symbol: "$"
                    },
                    uah: {
                        id: "75",
                        symbol: "\u20b4"
                    },
                    kzt: {
                        id: "76",
                        symbol: "T"
                    },
                    uzs: {
                        id: "77",
                        symbol: ""
                    },
                    azn: {
                        id: "78",
                        symbol: "\u20bc"
                    },
                    gel: {
                        id: "79",
                        symbol: "\u20be"
                    },
                    amd: {
                        id: "80",
                        symbol: "[amd]"
                    },
                    kgs: {
                        id: "81",
                        symbol: "[kgs]"
                    },
                    tmt: {
                        id: "82",
                        symbol: ""
                    },
                    byn: {
                        id: "83",
                        symbol: "Br"
                    },
                    czk: {
                        id: "84",
                        symbol: "K\u010d"
                    },
                    chf: {
                        id: "85",
                        symbol: "[chf]"
                    },
                    mdl: {
                        id: "86",
                        symbol: "L"
                    },
                    huf: {
                        id: "87",
                        symbol: "ft"
                    },
                    kez: {
                        id: "88",
                        symbol: ""
                    },
                    tzs: {
                        id: "89",
                        symbol: "Sh"
                    },
                    zmw: {
                        id: "90",
                        symbol: ""
                    },
                    tnd: {
                        id: "91",
                        symbol: "TND"
                    },
                    ngn: {
                        id: "92",
                        symbol: "\u20a6"
                    },
                    ugx: {
                        id: "93",
                        symbol: ""
                    },
                    xaf: {
                        id: "94",
                        symbol: "Sh"
                    },
                    ghs: {
                        id: "95",
                        symbol: "\u20b5"
                    },
                    pen: {
                        id: "96",
                        symbol: "S/."
                    },
                    in5: {
                        id: "97",
                        symbol: ""
                    },
                    kvdn: {
                        id: "98",
                        symbol: ""
                    },
                    kidr: {
                        id: "99",
                        symbol: ""
                    },
                    trx: {
                        id: "100",
                        symbol: "TRX"
                    },
                    npr: {
                        id: "101",
                        symbol: "NPR"
                    },
                    lak: {
                        id: "102",
                        symbol: "\u20ad"
                    },
                    aed: {
                        id: "103",
                        symbol: "AED"
                    },
                    "usd(0.1)": {
                        id: "104",
                        symbol: "$"
                    },
                    tw: {
                        id: "105",
                        symbol: "NT$"
                    },
                    ves: {
                        id: "106",
                        symbol: "VES"
                    }
                },
                $ = function() {
                    return Object.keys(G)
                },
                X = {
                    cn: {
                        key: "cn",
                        title: "\u7b80\u4f53\u4e2d\u6587",
                        img: "".concat(W, "/help/common/lang/cn.png")
                    },
                    en: {
                        key: "en",
                        title: "English",
                        img: "".concat(W, "/help/common/lang/en.png")
                    },
                    th: {
                        key: "th",
                        title: "\u0e1b\u0e23\u0e30\u0e40\u0e17\u0e28\u0e44\u0e17\u0e22",
                        img: "".concat(W, "/help/common/lang/th.png")
                    },
                    id: {
                        key: "id",
                        title: "Indonesia",
                        img: "".concat(W, "/help/common/lang/id.png")
                    },
                    vn: {
                        key: "vn",
                        title: "Vi\u1ec7t nam",
                        img: "".concat(W, "/help/common/lang/vn.png")
                    },
                    ko: {
                        key: "ko",
                        title: "\ud55c\uad6d\uc778",
                        img: "".concat(W, "/help/common/lang/kr.png")
                    },
                    es: {
                        key: "es",
                        title: "Espa\xf1a",
                        img: "".concat(W, "/help/common/lang/es.png")
                    },
                    ja: {
                        key: "ja",
                        title: "\u65e5\u672c\u8a9e",
                        img: "".concat(W, "/help/common/lang/jp.png")
                    },
                    "pt-br": {
                        key: "pt-br",
                        title: "Portugu\xeas brasileiro",
                        img: "".concat(W, "/help/common/lang/pt.png")
                    },
                    ph: {
                        key: "ph",
                        title: "Pilipinas",
                        img: "".concat(W, "/help/common/lang/ru.png")
                    }
                },
                U = function() {
                    return Object.keys(X)
                },
                q = function() {
                    var e = Object(I.useState)(""),
                        t = Object(P.a)(e, 2),
                        n = t[0],
                        a = t[1],
                        i = Object(I.useState)(""),
                        c = Object(P.a)(i, 2),
                        s = c[0],
                        l = c[1],
                        o = Object(I.useState)(0),
                        r = Object(P.a)(o, 2),
                        m = r[0],
                        d = r[1],
                        b = Object(I.useState)(0),
                        g = Object(P.a)(b, 2),
                        j = g[0],
                        y = g[1],
                        h = Object(I.useState)(1),
                        u = Object(P.a)(h, 2),
                        x = u[0],
                        p = u[1],
                        f = Object(I.useState)(0),
                        O = Object(P.a)(f, 2),
                        v = O[0],
                        N = O[1],
                        S = Object(I.useState)(1),
                        w = Object(P.a)(S, 2),
                        L = w[0],
                        z = w[1],
                        _ = Object(I.useState)(!0),
                        D = Object(P.a)(_, 2),
                        T = D[0],
                        k = D[1],
                        C = Object(I.useState)("default"),
                        F = Object(P.a)(C, 2),
                        H = F[0],
                        W = F[1],
                        A = Object(I.useState)(!1),
                        M = Object(P.a)(A, 2),
                        E = M[0],
                        Y = M[1];
                    return Object(I.useEffect)((function() {
                        var e = R("language"),
                            t = "7006" === R("gameid") ? "GB6" : "7012" === R("gameid") ? "GB12" : R("gameid"),
                            n = R("extra"),
                            i = R("denom"),
                            c = R("bet"),
                            s = R("betLevel"),
                            o = R("currency") && R("currency").toLowerCase(),
                            r = R("isCurrency"),
                            m = R("soundOn");
                        t && l(t), n && d(n.split(",")[0]), n && y(n.split(",")[1]), i && p(i), c && N(c), s && z(s), "false" === m && k(!1), "true" === r && Y(!0), "zh-cn" === e ? a("cn") : e ? U().includes(e) ? a(e) : a("en") : a("cn"), $().includes(o) && W(o)
                    }), []), {
                        defaultLang: n,
                        gameId: s,
                        minExtra: m,
                        maxExtra: j,
                        denom: x,
                        bet: v,
                        betLevel: L,
                        isSoundOn: T,
                        currency: H,
                        isCurrency: E
                    }
                },
                J = {
                    payTable: {
                        "zh-tw": "\u8ce0\u4ed8\u8868",
                        cn: "\u8d54\u4ed8\u8868",
                        en: "PAY TABLE",
                        th: "\u0e15\u0e32\u0e23\u0e32\u0e07\u0e01\u0e32\u0e23\u0e08\u0e48\u0e32\u0e22",
                        id: "PAY TABLE",
                        vn: "PAY TABLE",
                        ko: "PAY TABLE",
                        es: "TABLA DE PAGOS",
                        ja: "\u914d\u5f53\u8868",
                        "pt-br": "MESA DE PAGAMENTOS",
                        ph: "PAY TABLE"
                    },
                    totalBet: {
                        "zh-tw": "\u7e3d\u62bc\u6ce8",
                        cn: "\u603b\u62bc\u6ce8",
                        en: "Total Play Amount",
                        th: "\u0e40\u0e14\u0e34\u0e21\u0e1e\u0e31\u0e19",
                        id: "Bertaruh",
                        vn: "C\u01af\u1ee2C",
                        ko: "\ubc30\ud305",
                        es: "Apuesta Total",
                        ja: "\u30d9\u30c3\u30c8\u984d",
                        "pt-br": "Aposta Total",
                        ph: "Taya"
                    },
                    ways: {
                        "zh-tw": "\u8def",
                        cn: "\u8def",
                        en: "WAYS",
                        th: "WAYS",
                        id: "WAYS",
                        vn: "WAYS",
                        ko: "WAYS",
                        es: "FORMAS",
                        ja: "\u901a\u308a",
                        "pt-br": "CAMINHOS",
                        ph: "WAYS"
                    },
                    lines: {
                        "zh-tw": "\u7dda",
                        cn: "\u7ebf",
                        en: "LINES",
                        th: "LINES",
                        id: "LINES",
                        vn: "LINES",
                        ko: "LINES",
                        es: "L\xcdNEAS",
                        ja: "\u884c",
                        "pt-br": "LINHAS",
                        ph: "LINES"
                    },
                    line: {
                        "zh-tw": "\u7dda",
                        cn: "\u7ebf",
                        en: "LINE",
                        th: "LINE",
                        id: "LINE",
                        vn: "LINE",
                        ko: "LINE",
                        es: "L\xcdNEA",
                        ja: "\u884c",
                        "pt-br": "LINHA",
                        ph: "LINE"
                    },
                    cautions: {
                        "zh-tw": "\u904a\u6232\u51fa\u73fe\u6545\u969c\u6642\uff0c\u6240\u6709\u8ce0\u4ed8\u548c\u904a\u6232\u90fd\u8996\u70ba\u7121\u6548",
                        cn: "\u6e38\u620f\u51fa\u73b0\u6545\u969c\u65f6\uff0c\u6240\u6709\u8d54\u4ed8\u548c\u6e38\u620f\u90fd\u89c6\u4e3a\u65e0\u6548\u3002",
                        en: "Malfunction Voids All Pays and Play.",
                        th: "\u0e01\u0e32\u0e23\u0e17\u0e33\u0e07\u0e32\u0e19\u0e1c\u0e34\u0e14\u0e1b\u0e01\u0e15\u0e34\u0e08\u0e30\u0e17\u0e33\u0e43\u0e2b\u0e49\u0e01\u0e32\u0e23\u0e08\u0e48\u0e32\u0e22\u0e40\u0e07\u0e34\u0e19\u0e41\u0e25\u0e30\u0e01\u0e32\u0e23\u0e40\u0e25\u0e48\u0e19\u0e17\u0e31\u0e49\u0e07\u0e2b\u0e21\u0e14\u0e40\u0e1b\u0e47\u0e19\u0e42\u0e21\u0e06\u0e30",
                        id: "Malfungsi membatalkan Bayaran dan Permainan.",
                        vn: "Tr\u1ea3 th\u01b0\u1edfng v\xe0 ho\u1ea1t \u0111\u1ed9ng game v\xf4 hi\u1ec7u trong tr\u01b0\u1eddng h\u1ee3p c\xf3 s\u1ef1 c\u1ed1",
                        ko: "\uc624\uc791\ub3d9\uc73c\ub85c \uc778\ud55c \ud50c\ub808\uc774\uc640 \ubc30\ud305\uc740 \ubb34\ud6a8\ud654 \ub429\ub2c8\ub2e4.",
                        es: "Si se produce un error de funcionamiento, se anular\xe1n todas las jugadas y pagos.",
                        ja: "\u4e0d\u5177\u5408\u304c\u751f\u3058\u305f\u5834\u5408\u3001\u3059\u3079\u3066\u306e\u914d\u5f53\u3068\u30b2\u30fc\u30e0\u304c\u7121\u52b9\u3068\u306a\u308a\u307e\u3059\u3002",
                        "pt-br": "Mau funcionamento anula todos os pagamentos e jogadas.",
                        ph: "Ang Hindi Paggana ay Pinapawalang-bisa ang Lahat ng Bayad at Laro."
                    },
                    reelways: {
                        "zh-tw": "\u8def\u8def\u767c",
                        cn: "\u8def\u8def\u53d1",
                        en: "REEL WAYS",
                        th: "REEL WAYS",
                        id: "REEL WAYS",
                        vn: "REEL WAYS",
                        ko: "REEL WAYS",
                        es: "FORMAS DE RODILLO",
                        ja: "\u8def\u8def\u767a",
                        "pt-br": "MANEIRAS DO MOLINETE",
                        ph: ""
                    },
                    richways: {
                        "zh-tw": "\u842c\u8def\u767c",
                        cn: "\u4e07\u8def\u53d1",
                        en: "RICH WAYS",
                        th: "RICH WAYS",
                        id: "RICH WAYS",
                        vn: "RICH WAYS",
                        ko: "RICH WAYS",
                        es: "RICH WAYS",
                        ja: "\u4e07\u8def\u53d1",
                        "pt-br": "RICH WAYS",
                        ph: ""
                    },
                    any3: {
                        "zh-tw": "\u4efb\u610f3",
                        cn: "\u4efb\u610f3",
                        en: "ANY 3",
                        th: "ANY 3",
                        id: "ANY 3",
                        vn: "ANY 3",
                        ko: "ANY 3",
                        es: "ANY 3",
                        ja: "ANY 3",
                        "pt-br": "ANY 3",
                        ph: "ANY 3"
                    },
                    any5: {
                        "zh-tw": "\u4efb\u610f5",
                        cn: "\u4efb\u610f5",
                        en: "ANY 5",
                        th: "ANY 5",
                        id: "ANY 5",
                        vn: "ANY 5",
                        ko: "ANY 5",
                        es: "ANY 5",
                        ja: "ANY 5",
                        "pt-br": "ANY 5",
                        ph: "ANY 5"
                    },
                    any6: {
                        "zh-tw": "\u4efb\u610f6",
                        cn: "\u4efb\u610f6",
                        en: "ANY 6",
                        th: "ANY 6",
                        id: "ANY 6",
                        vn: "ANY 6",
                        ko: "ANY 6",
                        es: "ANY 6",
                        ja: "ANY 6",
                        "pt-br": "ANY 6",
                        ph: "ANY 6"
                    },
                    any: {
                        "zh-tw": "\u4efb\u610f",
                        cn: "\u4efb\u610f",
                        en: "ANY ",
                        th: "ANY ",
                        id: "ANY ",
                        vn: "ANY ",
                        ko: "ANY ",
                        es: "ANY ",
                        ja: "ANY ",
                        "pt-br": "ANY ",
                        ph: "ANY "
                    },
                    freeGameOnly: {
                        "zh-tw": "\u53ea\u51fa\u73fe\u5728\u514d\u8cbb\u904a\u6232",
                        cn: "\u53ea\u51fa\u73b0\u5728\u514d\u8d39\u6e38\u620f",
                        en: "FREE GAME ONLY",
                        th: "\u0e1b\u0e23\u0e32\u0e01\u0e0e\u0e40\u0e09\u0e1e\u0e32\u0e30\u0e43\u0e19\u0e1f\u0e23\u0e35\u0e40\u0e01\u0e21\u0e40\u0e17\u0e48\u0e32\u0e19\u0e31\u0e49\u0e19",
                        id: "MAIN GRATIS SAJA",
                        vn: "CH\u1ec8 XU\u1ea4T HI\u1ec6N \u1ede V\xd2NG QUAY MI\u1ec4N PH\xcd",
                        ko: "\ud504\ub9ac\uac8c\uc784\ub9cc \uc9c4\ud589 \ud569\ub2c8\ub2e4.",
                        es: "SOLO JUEGO GRATIS",
                        ja: "\u30d5\u30ea\u30fc\u30b2\u30fc\u30e0\u9650\u5b9a",
                        "pt-br": "APENAS JOGO GRATUITO",
                        ph: "LIBRENG LARO LAMANG"
                    }
                },
                V = 700,
                K = {
                    title: "#d88c33",
                    bar: "#221a0e",
                    barText: "#5c4f49",
                    border: "#221a0e",
                    hr: "#3a2c25",
                    totalBetText: "#937645",
                    cautions: "#6f5f57"
                },
                Q = n(2),
                Z = n.n(Q),
                ee = n(0),
                te = k.b.div(a || (a = Object(T.a)(["\n  display: flex;\n  justify-content: center;\n  align-items: center;\n  position: fixed;\n  width: 50px;\n  height: 40px;\n  /* background-color: #2c2011; */\n  right: 0px;\n  margin-right: constant(safe-area-inset-right);\n  margin-right: min(30px, env(safe-area-inset-right));\n  bottom: 0px;\n  cursor: pointer;\n  transition: all 0.2s ease;\n  z-index: 1;\n  & > img {\n    width: 33px;\n    height: 21px;\n    object-fit: contain;\n  }\n"]))),
                ne = k.b.ul(i || (i = Object(T.a)(["\n  position: fixed;\n  /* width: 100px; */\n  max-height: 70vh;\n  overflow: scroll;\n  right: 7px;\n  margin-right: constant(safe-area-inset-right);\n  margin-right: min(30px, env(safe-area-inset-right));\n  bottom: 40px;\n  border-radius: 5px;\n  background-color: #dedede;\n  z-index: 1;\n  &::-webkit-scrollbar {\n    display: none;\n  }\n  & > li {\n    box-sizing: border-box;\n    width: 100%;\n    height: 35px;\n    padding-right: 10px;\n    display: flex;\n    justify-content: flex-start;\n    align-items: center;\n    cursor: pointer;\n    border-bottom: 1px solid #c4c4c4;\n    &:hover {\n      background-color: rgba(0, 0, 0, 0.1);\n    }\n    & > img {\n      height: 20px;\n      width: 40px;\n      margin: 0 5px;\n      object-fit: contain;\n    }\n    & > p {\n      color: #808080;\n      &.current {\n        color: #000;\n      }\n    }\n  }\n"]))),
                ae = function(e) {
                    var t = e.lang,
                        n = e.popupHandler,
                        a = e.popupState,
                        i = e.popupRef,
                        c = e.langHandler,
                        s = e.edited,
                        l = void 0 === s ? Object(C.a)() : s,
                        o = e.windowDimensions;
                    return Object(I.useEffect)((function() {
                        var e = !1;
                        a && (e = !0), document.getElementById("root").style.overflowY = e ? "hidden" : "scroll"
                    }), [a]), Object(ee.jsxs)(ee.Fragment, {
                        children: [Object(ee.jsx)(te, {
                            onClick: n,
                            windowDimensions: o,
                            isMobile: B,
                            children: Object(ee.jsx)("img", {
                                alt: "",
                                src: X[t].img
                            })
                        }), a && Object(ee.jsx)(ne, {
                            ref: i,
                            windowDimensions: o,
                            isMobile: B,
                            children: function() {
                                for (var e = [], t = function(t) {
                                        e.push(Object.values(X).find((function(e) {
                                            return e.key.toLowerCase() === l.get(t).toLowerCase()
                                        })))
                                    }, n = 0; n < l.size; n++) t(n);
                                return e
                            }().map((function(e) {
                                return Object(ee.jsxs)("li", {
                                    onClick: function() {
                                        return c(e.key)
                                    },
                                    children: [Object(ee.jsx)("img", {
                                        alt: "",
                                        src: e.img
                                    }), Object(ee.jsx)("p", {
                                        className: Z()({
                                            current: e.key === t
                                        }),
                                        children: e.title
                                    })]
                                }, e.key)
                            }))
                        })]
                    })
                },
                ie = k.b.div(c || (c = Object(T.a)(["\n  width: 100%;\n  display: flex;\n  flex-wrap: wrap;\n  flex-direction: ", ";\n  & .wildfree-rules {\n    width: ", ";\n    @media (max-width: 699px) {\n      width: 100%;\n    }\n  }\n\n  & .half {\n    width: 100%;\n    display: flex;\n    flex-direction: column;\n    align-items: center;\n    &.continue {\n      margin-top: 0;\n    }\n    &.m-row {\n      flex-direction: ", ";\n      justify-content: center;\n    }\n    & .circle {\n      display: flex;\n      align-items: center;\n      justify-content: center;\n      border: 3px solid;\n      border-radius: 50%;\n      width: 30px;\n      height: 30px;\n      &.correct {\n        color: #45fe01;\n        border-color: #45fe01;\n      }\n      &.incorrect {\n        color: #f80701;\n        border-color: #f80701;\n      }\n    }\n  }\n"])), (function(e) {
                    return e.windowDimensions.width >= V ? "row" : "column"
                }), (function(e) {
                    return 2 === e.wildFreeCount ? "80%" : "100%"
                }), (function(e) {
                    return e.windowDimensions.width >= V ? "column" : "row-reverse"
                })),
                ce = k.b.div(s || (s = Object(T.a)(["\n  display: flex;\n  justify-content: center;\n  height: 140px;\n  ", "\n  ", "\n  margin: 10px 0;\n  & .mw100 {\n    max-width: 100%;\n  }\n  & .mw200px {\n    max-width: 200px;\n  }\n  & .half {\n    display: flex;\n    flex-direction: row;\n    align-items: center;\n\n    & .pic {\n      width: 110px;\n      padding: 20px 0;\n      margin-right: 10px;\n      text-align: center;\n      .pay-img {\n        width: 110px;\n        height: 110px;\n        image-rendering: -webkit-optimize-contrast;\n      }\n    }\n    & .list {\n      flex: 1;\n      display: flex;\n      flex-direction: column;\n      justify-content: center;\n      align-items: flex-start;\n      font-size: 24px;\n      margin-left: 10px;\n      & > div {\n        margin: 3px 0;\n        white-space: nowrap;\n      }\n      & span {\n        font-size: 24px;\n        color: #ffd542;\n        margin-left: 5px;\n      }\n    }\n  }\n"])), (function(e) {
                    return ("133" === e.gameId || "TA2" === e.gameId) && "free_game_feature" === e.isImg && "height:auto;"
                }), (function(e) {
                    return "133" !== e.gameId && "TA2" !== e.gameId || "free_game_feature" !== e.isImg ? "" : e.windowDimensions.width < 700 ? "width:100%;" : "width:60%;"
                })),
                se = function(e) {
                    for (var t = e.helpData, n = e.windowDimensions, a = e.createContent, i = e.gameId, c = e.payTableData, s = e.isCurrency, l = e.moneyConvert, o = e.denom, r = e.betLevel, m = e.currency, d = e.lang, b = 0, g = 0; g < t.getIn(["default_data"]).size; g++) {
                        var j = t.getIn(["default_data", g, "icon", "link"]);
                        "F" !== j && "W" !== j && "W_S" !== j && "super_wild" !== j || (b += 1)
                    }
                    return Object(ee.jsx)(ee.Fragment, {
                        children: Object(ee.jsx)(ie, {
                            windowDimensions: n,
                            wildFreeCount: b,
                            children: "" !== t.get("default_data") && t.get("default_data").map((function(e, g) {
                                return Object(ee.jsxs)("div", {
                                    className: Z()("flex-column aic", {
                                        continue: "+" === e.get("title"),
                                        w50: 2 === b && ("F" === e.getIn(["icon", "link"]) || "W" === e.getIn(["icon", "link"]) || "W_S" === e.getIn(["icon", "link"]) || "super_wild" === e.getIn(["icon", "link"])) && n.width >= 700 || "242" === i && 0 !== g && n.width >= 700,
                                        w100: !("F" === e.getIn(["icon", "link"]) || "W" === e.getIn(["icon", "link"]) || "W_S" === e.getIn(["icon", "link"]) || "super_wild" === e.getIn(["icon", "link"]) || "242" === i && "random_wild" === e.getIn(["icon", "link"])) || "242" === i && 0 === g || 2 !== b || n.width < 700,
                                        "mt-60": 2 === b && ("F" !== t.getIn(["default_data", 0]) || "W" !== t.getIn(["default_data", 0]) || "W_S" !== t.getIn(["default_data", 0]) || "super_wild" !== t.getIn(["default_data", 0])) && n.width >= 700
                                    }),
                                    children: ["+" !== e.get("title") && Object(ee.jsx)("p", {
                                        className: "title",
                                        children: "zh-tw" === d ? Object(F.tify)(e.get("title")) : "cn" === d ? Object(F.sify)(e.get("title")) : e.get("title")
                                    }), e.getIn(["icon", "link"]) && Object(ee.jsxs)(ce, {
                                        wildFreeCount: b,
                                        isImg: e.getIn(["icon", "link"]),
                                        gameId: i,
                                        windowDimensions: n,
                                        children: [Object(ee.jsx)("img", {
                                            className: "h100 object-fit-contain mw200px",
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.getIn(["icon", "link"]), ".png")
                                        }), "F" === e.getIn(["icon", "link"]) || "W_S" === e.getIn(["icon", "link"]) && "199" === i ? c.get("math_data") && c.get("math_data").filter((function(e) {
                                            return ("F" === e.get("SymbolName") || "SC" === e.get("SymbolName")) && !e.get("SymbolPays").every((function(e) {
                                                return 0 === e
                                            }))
                                        })).slice(0, 1).map((function(e) {
                                            return Object(ee.jsx)("div", {
                                                className: "half",
                                                children: Object(ee.jsx)("div", {
                                                    className: "list",
                                                    children: e.get("SymbolPays").reverse().map((function(e, t) {
                                                        var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                                        return Object(ee.jsx)(I.Fragment, {
                                                            children: 0 !== e && Object(ee.jsxs)("div", {
                                                                children: [n.size - t, " -", Object(ee.jsx)("span", {
                                                                    children: "".concat(e, "X")
                                                                })]
                                                            }, t)
                                                        }, t)
                                                    }))
                                                })
                                            }, e.get("SymbolID"))
                                        })) : c.get("math_data") && c.get("math_data").filter((function(t) {
                                            return t.get("SymbolName") === e.getIn(["icon", "link"]) && !t.get("SymbolPays").every((function(e) {
                                                return 0 === e
                                            }))
                                        })).map((function(e) {
                                            return Object(ee.jsx)("div", {
                                                className: "half",
                                                children: Object(ee.jsx)("div", {
                                                    className: "list",
                                                    children: e.get("SymbolPays").reverse().map((function(e, t) {
                                                        var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                                        return Object(ee.jsx)(I.Fragment, {
                                                            children: 0 !== e && Object(ee.jsxs)("div", {
                                                                children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                                    className: Z()({
                                                                        money: s
                                                                    }),
                                                                    children: [s && Object(ee.jsx)("div", {
                                                                        className: "symbol",
                                                                        dangerouslySetInnerHTML: M(G[m].symbol)
                                                                    }), s ? l(e * o * r) : e * r]
                                                                })]
                                                            }, t)
                                                        }, t)
                                                    }))
                                                })
                                            }, e.get("SymbolID"))
                                        }))]
                                    }), Object(ee.jsx)("div", {
                                        className: "rules wildfree-rules",
                                        children: 0 !== t.size && t.get("default_data") && Object(ee.jsx)("div", {
                                            dangerouslySetInnerHTML: a(e.get("content"), i)
                                        })
                                    })]
                                }, g)
                            }))
                        })
                    })
                },
                le = k.b.div(l || (l = Object(T.a)(["\n  width: 100%;\n  display: flex;\n  flex-direction: column;\n  & .half {\n    display: flex;\n    flex-direction: column;\n    align-items: center;\n    &.m-row {\n      flex-direction: ", ";\n      justify-content: center;\n    }\n    & .circle {\n      display: flex;\n      align-items: center;\n      justify-content: center;\n      border: 3px solid;\n      border-radius: 50%;\n      width: 30px;\n      height: 30px;\n      &.correct {\n        color: #45fe01;\n        border-color: #45fe01;\n      }\n      &.incorrect {\n        color: #f80701;\n        border-color: #f80701;\n      }\n    }\n  }\n"])), (function(e) {
                    return e.windowDimensions.width >= V ? "column" : "row-reverse"
                })),
                oe = k.b.div(o || (o = Object(T.a)(["\n  display: flex;\n  justify-content: center;\n  align-items: center;\n  height: ", ";\n  margin: 10px 0;\n  & .half {\n    display: flex;\n    flex-direction: row;\n    align-items: center;\n\n    & .pic {\n      width: 110px;\n      padding: 20px 0;\n      margin-right: 10px;\n      text-align: center;\n      .pay-img {\n        width: 110px;\n        height: 110px;\n        image-rendering: -webkit-optimize-contrast;\n      }\n    }\n    & .list {\n      flex: 1;\n      display: flex;\n      flex-direction: column;\n      justify-content: center;\n      align-items: flex-start;\n      font-size: 24px;\n      margin-left: 10px;\n      & > div {\n        margin: 3px 0;\n      }\n      & span {\n        font-size: 24px;\n        color: #ffd542;\n        margin-left: 5px;\n      }\n    }\n  }\n"])), (function(e) {
                    return e.img && (e.isHorizontal ? "200px" : "350px")
                })),
                re = function(e) {
                    var t = e.rule,
                        n = e.createContent,
                        a = e.gameId,
                        i = e.payTableData,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.moneyConvert,
                        o = e.denom,
                        r = e.betLevel,
                        m = e.windowDimensions,
                        d = e.lang,
                        b = e.sify,
                        g = e.tify,
                        j = Object(I.useState)(!0),
                        y = Object(P.a)(j, 2),
                        h = y[0],
                        u = y[1];
                    return Object(ee.jsxs)(ee.Fragment, {
                        children: ["+" !== t.get("title") && Object(ee.jsx)("hr", {}), Object(ee.jsxs)(le, {
                            windowDimensions: m,
                            children: ["+" !== t.get("title") && Object(ee.jsx)("p", {
                                className: "title",
                                children: "zh-tw" === d ? g(t.get("title")) : "cn" === d ? b(t.get("title")) : t.get("title")
                            }), t.getIn(["icon", "link"]) && Object(ee.jsxs)(oe, {
                                img: t.getIn(["icon", "link"]),
                                isHorizontal: h,
                                children: [Object(ee.jsx)("img", {
                                    className: Z()("object-fit-scale mb20 h100", {
                                        w100: "F" !== t.getIn(["icon", "link"]) && "W" !== t.getIn(["icon", "link"])
                                    }),
                                    alt: "",
                                    onLoad: function(e) {
                                        return function(e) {
                                            e.target.naturalHeight / e.target.naturalWidth > 1.2 && u(!1)
                                        }(e)
                                    },
                                    src: "".concat(W, "/order-detail/common/").concat(a, "/symbolList/").concat(t.getIn(["icon", "link"]), ".png")
                                }), "F" === t.getIn(["icon", "link"]) ? i.get("math_data") && i.get("math_data").filter((function(e) {
                                    return ("F" === e.get("SymbolName") || "SC" === e.get("SymbolName")) && !e.get("SymbolPays").every((function(e) {
                                        return 0 === e
                                    }))
                                })).map((function(e) {
                                    return Object(ee.jsx)("div", {
                                        className: "half",
                                        children: Object(ee.jsx)("div", {
                                            className: "list",
                                            children: e.get("SymbolPays").reverse().map((function(e, t) {
                                                var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                                return Object(ee.jsx)(I.Fragment, {
                                                    children: 0 !== e && Object(ee.jsxs)("div", {
                                                        children: [n.size - t, " -", Object(ee.jsx)("span", {
                                                            children: "".concat(e, "X")
                                                        })]
                                                    }, t)
                                                }, t)
                                            }))
                                        })
                                    }, e.get("SymbolID"))
                                })) : i.get("math_data") && i.get("math_data").filter((function(e) {
                                    return e.get("SymbolName") === t.getIn(["icon", "link"]) && !e.get("SymbolPays").every((function(e) {
                                        return 0 === e
                                    }))
                                })).map((function(e) {
                                    return Object(ee.jsx)("div", {
                                        className: "half",
                                        children: Object(ee.jsx)("div", {
                                            className: "list",
                                            children: e.get("SymbolPays").reverse().map((function(e, t) {
                                                var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                                return Object(ee.jsx)(I.Fragment, {
                                                    children: 0 !== e && Object(ee.jsxs)("div", {
                                                        children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                            className: Z()({
                                                                money: c
                                                            }),
                                                            children: [c && Object(ee.jsx)("div", {
                                                                className: "symbol",
                                                                dangerouslySetInnerHTML: M(G[s].symbol)
                                                            }), c ? l(e * o * r) : e * r]
                                                        })]
                                                    }, t)
                                                }, t)
                                            }))
                                        })
                                    }, e.get("SymbolID"))
                                }))]
                            }), Object(ee.jsx)("div", {
                                className: "rules",
                                children: Object(ee.jsx)("div", {
                                    dangerouslySetInnerHTML: n(t.get("content"), a)
                                })
                            })]
                        })]
                    })
                },
                me = function(e) {
                    var t = e.helpData;
                    return Object(ee.jsx)(ee.Fragment, {
                        children: t.get("data").map((function(t, n) {
                            return Object(ee.jsx)(re, Object(D.a)({
                                rule: t
                            }, e), n)
                        }))
                    })
                },
                de = k.b.div(r || (r = Object(T.a)(["\n  display: flex;\n  flex-direction: row;\n  justify-content: center;\n  align-items: center;\n  height: ", ";\n  margin: 10px 0;\n  & .free-span {\n    font-weight: bold;\n    font-size: 20px;\n    display: flex;\n    & > span {\n      font-weight: normal;\n      margin-left: 3px;\n      color: rgb(255, 213, 66);\n      display: flex;\n      align-items: center;\n    }\n  }\n\n  & .half {\n    display: flex;\n    flex-direction: column;\n    align-items: center;\n    height: 70%;\n    justify-content: space-evenly;\n    & > div {\n      display: flex;\n      align-items: center;\n      padding: 0 5px;\n    }\n    & img {\n      object-fit: scale-down;\n      height: 40px;\n      vertical-align: middle;\n    }\n    & .pic {\n      width: 110px;\n      padding: 20px 0;\n      margin-right: 10px;\n      text-align: center;\n      .pay-img {\n        width: 110px;\n        height: 110px;\n        image-rendering: -webkit-optimize-contrast;\n      }\n    }\n  }\n"])), (function(e) {
                    return e.img && (e.isHorizontal ? "200px" : "350px")
                })),
                be = function(e) {
                    var t = e.helpData,
                        n = e.createContent,
                        a = e.gameId,
                        i = e.payTableData,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.moneyConvert,
                        o = e.denom,
                        r = e.betLevel,
                        m = e.windowDimensions,
                        d = e.lang,
                        b = e.sify,
                        g = e.tify,
                        j = Object(I.useRef)(),
                        y = Object(I.useState)(!0),
                        h = Object(P.a)(y, 2),
                        u = h[0],
                        x = h[1];
                    return Object(I.useEffect)((function() {
                        var e, t;
                        (null === (e = j.current) || void 0 === e ? void 0 : e.naturalHeight) / (null === (t = j.current) || void 0 === t ? void 0 : t.naturalWidth) > 1.2 && x(!1)
                    }), [j]), Object(ee.jsxs)(ee.Fragment, {
                        children: ["+" !== t.getIn(["data", 0, "title"]) && Object(ee.jsx)("hr", {}), Object(ee.jsxs)(le, {
                            windowDimensions: m,
                            children: ["+" !== t.getIn(["data", 0, "title"]) && Object(ee.jsx)("p", {
                                className: "title",
                                children: "zh-tw" === d ? g(t.getIn(["data", 0, "title"])) : "cn" === d ? b(t.getIn(["data", 0, "title"])) : t.getIn(["data", 0, "title"])
                            }), Object(ee.jsxs)(de, {
                                img: t.getIn(["data", 0, "icon", "link"]),
                                isHorizontal: u,
                                children: [t.getIn(["data", 0, "icon", "link"]) && Object(ee.jsx)("img", {
                                    className: "object-fit-scale h100",
                                    alt: "",
                                    ref: j,
                                    src: "".concat(W, "/order-detail/common/").concat(a, "/symbolList/").concat(t.getIn(["data", 0, "icon", "link"]), ".png")
                                }), Object(ee.jsxs)("div", {
                                    className: "half list",
                                    children: [Object(ee.jsxs)("div", {
                                        children: [Object(ee.jsx)("img", {
                                            className: "object-fit-scale",
                                            alt: "",
                                            ref: j,
                                            src: "".concat(W, "/order-detail/common/").concat(a, "/symbolList/F3.png")
                                        }), Object(ee.jsxs)("span", {
                                            className: "free-span",
                                            children: ["1-", Object(ee.jsxs)("span", {
                                                className: Z()({
                                                    money: c
                                                }),
                                                children: [c && Object(ee.jsx)("div", {
                                                    className: "symbol",
                                                    dangerouslySetInnerHTML: M(G[s].symbol)
                                                }), c ? l(i.getIn(["math_data", 1, "SymbolPays", 0]) * o * r) : i.getIn(["math_data", 1, "SymbolPays", 0]) * r]
                                            })]
                                        })]
                                    }), Object(ee.jsxs)("div", {
                                        children: [Object(ee.jsx)("img", {
                                            className: "object-fit-scale",
                                            alt: "",
                                            ref: j,
                                            src: "".concat(W, "/order-detail/common/").concat(a, "/symbolList/F2.png")
                                        }), Object(ee.jsxs)("span", {
                                            className: "free-span",
                                            children: ["1-", Object(ee.jsxs)("span", {
                                                className: Z()({
                                                    money: c
                                                }),
                                                children: [c && Object(ee.jsx)("div", {
                                                    className: "symbol",
                                                    dangerouslySetInnerHTML: M(G[s].symbol)
                                                }), c ? l(i.getIn(["math_data", 2, "SymbolPays", 0]) * o * r) : i.getIn(["math_data", 2, "SymbolPays", 0]) * r]
                                            })]
                                        })]
                                    })]
                                })]
                            }), Object(ee.jsx)("div", {
                                className: "rules",
                                children: Object(ee.jsx)("div", {
                                    dangerouslySetInnerHTML: n(t.getIn(["data", 0, "content"]), a)
                                })
                            })]
                        }), "+" !== t.getIn(["data", 1, "title"]) && Object(ee.jsx)("hr", {}), Object(ee.jsxs)(le, {
                            windowDimensions: m,
                            children: ["+" !== t.getIn(["data", 1, "title"]) && Object(ee.jsx)("p", {
                                className: "title",
                                children: "zh-tw" === d ? g(t.getIn(["data", 1, "title"])) : "cn" === d ? b(t.getIn(["data", 1, "title"])) : t.getIn(["data", 1, "title"])
                            }), t.getIn(["data", 1, "icon", "link"]) && Object(ee.jsxs)(oe, {
                                img: t.getIn(["data", 1, "icon", "link"]),
                                isHorizontal: u,
                                children: [Object(ee.jsx)("img", {
                                    className: "object-fit-scale mb20 h100",
                                    alt: "",
                                    ref: j,
                                    src: "".concat(W, "/order-detail/common/").concat(a, "/symbolList/").concat(t.getIn(["data", 1, "icon", "link"]), ".png")
                                }), "F" === t.getIn(["data", 1, "icon", "link"]) ? i.get("math_data") && i.get("math_data").filter((function(e) {
                                    return ("F" === e.get("SymbolName") || "SC" === e.get("SymbolName")) && !e.get("SymbolPays").every((function(e) {
                                        return 0 === e
                                    }))
                                })).map((function(e) {
                                    return Object(ee.jsx)("div", {
                                        className: "half",
                                        children: Object(ee.jsx)("div", {
                                            className: "list",
                                            children: e.get("SymbolPays").reverse().map((function(e, t) {
                                                var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                                return Object(ee.jsx)(I.Fragment, {
                                                    children: 0 !== e && Object(ee.jsxs)("div", {
                                                        children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                            className: Z()({
                                                                money: c
                                                            }),
                                                            children: [c && Object(ee.jsx)("div", {
                                                                className: "symbol",
                                                                dangerouslySetInnerHTML: M(G[s].symbol)
                                                            }), c ? l(e * o * r) : e * r]
                                                        })]
                                                    }, t)
                                                }, t)
                                            }))
                                        })
                                    }, e.get("SymbolID"))
                                })) : i.get("math_data") && i.get("math_data").filter((function(e) {
                                    return e.get("SymbolName") === t.getIn(["data", 1, "icon", "link"]) && !e.get("SymbolPays").every((function(e) {
                                        return 0 === e
                                    }))
                                })).map((function(e) {
                                    return Object(ee.jsx)("div", {
                                        className: "half",
                                        children: Object(ee.jsx)("div", {
                                            className: "list",
                                            children: e.get("SymbolPays").reverse().map((function(e, t) {
                                                var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                                return Object(ee.jsx)(I.Fragment, {
                                                    children: 0 !== e && Object(ee.jsxs)("div", {
                                                        children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                            className: Z()({
                                                                money: c
                                                            }),
                                                            children: [c && Object(ee.jsx)("div", {
                                                                className: "symbol",
                                                                dangerouslySetInnerHTML: M(G[s].symbol)
                                                            }), c ? l(e * o * r) : e * r]
                                                        })]
                                                    }, t)
                                                }, t)
                                            }))
                                        })
                                    }, e.get("SymbolID"))
                                }))]
                            }), Object(ee.jsx)("div", {
                                className: "rules",
                                children: Object(ee.jsx)("div", {
                                    dangerouslySetInnerHTML: n(t.getIn(["data", 1, "content"]), a)
                                })
                            })]
                        })]
                    })
                },
                ge = k.b.div(m || (m = Object(T.a)(["\n  display: flex;\n  justify-content: space-between;\n  flex-wrap: wrap;\n  .item {\n    display: flex;\n    flex-direction: ", ";\n    width: ", ";\n    .object-fit-scale {\n      width: 112px;\n      height: 112px;\n    }\n    .list {\n      flex: 1;\n      display: flex;\n      flex-direction: column;\n      justify-content: center;\n      align-items: flex-start;\n      font-size: 24px;\n      margin-left: 10px;\n      & > div {\n        margin: 3px 0;\n      }\n      & span {\n        font-size: 24px;\n        color: #ffd542;\n        margin-left: 5px;\n      }\n      .any {\n        font-size: 18px;\n        white-space: nowrap;\n      }\n    }\n    .half {\n      align-items: flex-start;\n    }\n  }\n"])), (function(e) {
                    return e.windowDimensions.width >= 1024 ? "column" : "row"
                }), (function(e) {
                    return e.windowDimensions.width >= 1024 ? "20%" : e.windowDimensions.width >= V ? "50%" : "100%"
                })),
                je = function(e) {
                    var t = e.helpData,
                        n = e.createContent,
                        a = e.gameId,
                        i = e.payTableData,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.moneyConvert,
                        o = e.denom,
                        r = e.betLevel,
                        m = e.windowDimensions,
                        d = e.lang,
                        b = e.sify,
                        g = e.tify,
                        j = Object(I.useState)(Object(C.a)()),
                        y = Object(P.a)(j, 2),
                        h = y[0],
                        u = y[1],
                        x = Object(I.useState)(Object(C.a)()),
                        p = Object(P.a)(x, 2),
                        f = p[0],
                        O = p[1];
                    return Object(I.useEffect)((function() {
                        var e = ["H5", "N4", "N5", "H8"];
                        if (i.get("math_data")) {
                            var t = i.get("math_data").filter((function(t) {
                                    return e.includes(t.get("SymbolName"))
                                })),
                                n = i.get("math_data").filter((function(e) {
                                    return "SC" === e.get("SymbolName")
                                }));
                            u(t), O(n)
                        }
                    }), [i]), Object(ee.jsxs)(ee.Fragment, {
                        children: [Object(ee.jsx)("hr", {}), Object(ee.jsxs)(le, {
                            windowDimensions: m,
                            children: [Object(ee.jsx)("p", {
                                className: "title",
                                children: "zh-tw" === d ? g(t.getIn(["data", 0, "title"])) : "cn" === d ? b(t.getIn(["data", 0, "title"])) : t.getIn(["data", 0, "title"])
                            }), Object(ee.jsxs)("div", {
                                className: "rules",
                                children: [Object(ee.jsx)("div", {
                                    dangerouslySetInnerHTML: n(t.getIn(["data", 0, "content"]), a)
                                }), Object(ee.jsxs)(ge, {
                                    windowDimensions: m,
                                    children: [h.map((function(e, t) {
                                        return Object(ee.jsxs)("div", {
                                            className: "item",
                                            children: [Object(ee.jsx)("img", {
                                                className: "object-fit-scale",
                                                alt: "",
                                                src: "".concat(W, "/order-detail/common/").concat(a, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                            }), Object(ee.jsx)("div", {
                                                className: "half",
                                                children: Object(ee.jsx)("div", {
                                                    className: "list",
                                                    children: e.get("SymbolPays").reverse().map((function(e, n) {
                                                        var a = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                                        return Object(ee.jsx)(I.Fragment, {
                                                            children: 0 !== e && Object(ee.jsxs)("div", {
                                                                className: 3 === t ? "any" : "",
                                                                children: [3 === t && J.any[d], a.size - n, " -", Object(ee.jsxs)("span", {
                                                                    className: Z()({
                                                                        money: c
                                                                    }),
                                                                    children: [c && Object(ee.jsx)("div", {
                                                                        className: "symbol",
                                                                        dangerouslySetInnerHTML: M(G[s].symbol)
                                                                    }), c ? l(e * o * r) : e * r]
                                                                })]
                                                            }, n)
                                                        }, n)
                                                    }))
                                                })
                                            })]
                                        })
                                    })), f.map((function(e, t) {
                                        return Object(ee.jsxs)("div", {
                                            className: "item",
                                            children: [Object(ee.jsx)("img", {
                                                className: "object-fit-scale",
                                                alt: "",
                                                src: "".concat(W, "/order-detail/common/").concat(a, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                            }), Object(ee.jsx)("div", {
                                                className: "list",
                                                children: e.get("SymbolPays").reverse().map((function(e, t) {
                                                    var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                                    return Object(ee.jsx)(I.Fragment, {
                                                        children: 0 !== e && Object(ee.jsxs)("div", {
                                                            children: [n.size - t, " -", Object(ee.jsx)("span", {
                                                                children: "".concat(e, "X")
                                                            })]
                                                        }, t)
                                                    }, t)
                                                }))
                                            })]
                                        })
                                    }))]
                                }), Object(ee.jsx)("div", {
                                    dangerouslySetInnerHTML: n(t.getIn(["data", 1, "content"]), a)
                                })]
                            })]
                        })]
                    })
                },
                ye = k.b.div(d || (d = Object(T.a)(["\n  display: flex;\n  flex-direction: row;\n  justify-content: center;\n  align-items: center;\n  height: ", ";\n  margin: 10px 0;\n  & .free-span {\n    font-weight: bold;\n    font-size: ", ";\n    display: flex;\n    & > span {\n      font-weight: normal;\n      margin-left: 3px;\n      color: rgb(255, 213, 66);\n      display: flex;\n      align-items: center;\n    }\n  }\n  & .list {\n    flex: 1;\n    display: flex;\n    flex-direction: column;\n    justify-content: center;\n    align-items: flex-start;\n    font-size: 20px;\n    margin-left: 10px;\n    & > div {\n      white-space: nowrap;\n      margin: 3px 0;\n    }\n    & span {\n      font-size: ", ";\n      color: #ffd542;\n      margin-left: 5px;\n    }\n  }\n  & .half {\n    display: flex;\n    flex-direction: row;\n    align-items: center;\n    & > div {\n      display: flex;\n      align-items: flex-start;\n      padding: 0 5px;\n    }\n    & img {\n      object-fit: scale-down;\n      height: 35px;\n      vertical-align: middle;\n    }\n    & .pic {\n      width: 110px;\n      padding: 20px 0;\n      margin-right: 10px;\n      text-align: center;\n      .pay-img {\n        width: 110px;\n        height: 110px;\n        image-rendering: -webkit-optimize-contrast;\n      }\n    }\n  }\n"])), (function(e) {
                    return e.img && (e.isHorizontal ? "200px" : "350px")
                }), (function(e) {
                    return e.windowDimensions.width >= V ? "24px" : "20px"
                }), (function(e) {
                    return e.windowDimensions.width >= V ? "24px" : "20px"
                })),
                he = function(e) {
                    var t = e.helpData,
                        n = e.createContent,
                        a = e.gameId,
                        i = e.payTableData,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.moneyConvert,
                        o = e.denom,
                        r = e.betLevel,
                        m = e.windowDimensions,
                        d = e.lang,
                        b = e.sify,
                        g = e.tify,
                        j = Object(I.useRef)(),
                        y = Object(I.useState)(!0),
                        h = Object(P.a)(y, 2),
                        u = h[0],
                        x = h[1];
                    return Object(I.useEffect)((function() {
                        var e, t;
                        (null === (e = j.current) || void 0 === e ? void 0 : e.naturalHeight) / (null === (t = j.current) || void 0 === t ? void 0 : t.naturalWidth) > 1.2 && x(!1)
                    }), [j]), Object(ee.jsxs)(ee.Fragment, {
                        children: ["+" !== t.getIn(["data", 0, "title"]) && Object(ee.jsx)("hr", {}), Object(ee.jsxs)(le, {
                            windowDimensions: m,
                            children: ["+" !== t.getIn(["data", 0, "title"]) && Object(ee.jsx)("p", {
                                className: "title",
                                children: "zh-tw" === d ? g(t.getIn(["data", 0, "title"])) : "cn" === d ? b(t.getIn(["data", 0, "title"])) : t.getIn(["data", 0, "title"])
                            }), Object(ee.jsxs)(ye, {
                                img: t.getIn(["data", 0, "icon", "link"]),
                                isHorizontal: u,
                                windowDimensions: m,
                                children: [t.getIn(["data", 0, "icon", "link"]) && Object(ee.jsx)("img", {
                                    className: "object-fit-scale h100 w70",
                                    alt: "",
                                    ref: j,
                                    src: "".concat(W, "/order-detail/common/").concat(a, "/symbolList/").concat(t.getIn(["data", 0, "icon", "link"]), ".png")
                                }), i.get("math_data") && i.get("math_data").filter((function(e) {
                                    return "W" === e.get("SymbolName")
                                })).map((function(e) {
                                    return Object(ee.jsx)("div", {
                                        className: "half",
                                        children: Object(ee.jsx)("div", {
                                            className: "list",
                                            children: e.get("SymbolPays").reverse().map((function(e, t) {
                                                var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                                return Object(ee.jsx)(I.Fragment, {
                                                    children: 0 !== e && Object(ee.jsxs)("div", {
                                                        children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                            className: Z()({
                                                                money: c
                                                            }),
                                                            children: [c && Object(ee.jsx)("div", {
                                                                className: "symbol",
                                                                dangerouslySetInnerHTML: M(G[s].symbol)
                                                            }), c ? l(e * o * r) : e * r]
                                                        })]
                                                    }, t)
                                                }, t)
                                            }))
                                        })
                                    }, e.get("SymbolID"))
                                }))]
                            }), Object(ee.jsx)("div", {
                                className: "rules",
                                children: Object(ee.jsx)("div", {
                                    dangerouslySetInnerHTML: n(t.getIn(["data", 0, "content"]), a)
                                })
                            })]
                        }), "+" !== t.getIn(["data", 1, "title"]) && Object(ee.jsx)("hr", {}), Object(ee.jsxs)(le, {
                            windowDimensions: m,
                            children: ["+" !== t.getIn(["data", 1, "title"]) && Object(ee.jsx)("p", {
                                className: "title",
                                children: "zh-tw" === d ? g(t.getIn(["data", 1, "title"])) : "cn" === d ? b(t.getIn(["data", 1, "title"])) : t.getIn(["data", 1, "title"])
                            }), t.getIn(["data", 1, "icon", "link"]) && Object(ee.jsxs)(oe, {
                                img: t.getIn(["data", 1, "icon", "link"]),
                                isHorizontal: u,
                                children: [Object(ee.jsx)("img", {
                                    className: "object-fit-scale mb20 h100",
                                    alt: "",
                                    ref: j,
                                    src: "".concat(W, "/order-detail/common/").concat(a, "/symbolList/").concat(t.getIn(["data", 1, "icon", "link"]), ".png")
                                }), "F" === t.getIn(["data", 1, "icon", "link"]) ? i.get("math_data") && i.get("math_data").filter((function(e) {
                                    return ("F" === e.get("SymbolName") || "SC" === e.get("SymbolName")) && !e.get("SymbolPays").every((function(e) {
                                        return 0 === e
                                    }))
                                })).map((function(e) {
                                    return Object(ee.jsx)("div", {
                                        className: "half",
                                        children: Object(ee.jsx)("div", {
                                            className: "list",
                                            children: e.get("SymbolPays").reverse().map((function(e, t) {
                                                var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                                return Object(ee.jsx)(I.Fragment, {
                                                    children: 0 !== e && Object(ee.jsxs)("div", {
                                                        children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                            className: Z()({
                                                                money: c
                                                            }),
                                                            children: [c && Object(ee.jsx)("div", {
                                                                className: "symbol",
                                                                dangerouslySetInnerHTML: M(G[s].symbol)
                                                            }), c ? l(e * o * r) : e * r]
                                                        })]
                                                    }, t)
                                                }, t)
                                            }))
                                        })
                                    }, e.get("SymbolID"))
                                })) : i.get("math_data") && i.get("math_data").filter((function(e) {
                                    return e.get("SymbolName") === t.getIn(["data", 1, "icon", "link"]) && !e.get("SymbolPays").every((function(e) {
                                        return 0 === e
                                    }))
                                })).map((function(e) {
                                    return Object(ee.jsx)("div", {
                                        className: "half",
                                        children: Object(ee.jsx)("div", {
                                            className: "list",
                                            children: e.get("SymbolPays").reverse().map((function(e, t) {
                                                var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                                return Object(ee.jsx)(I.Fragment, {
                                                    children: 0 !== e && Object(ee.jsxs)("div", {
                                                        children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                            className: Z()({
                                                                money: c
                                                            }),
                                                            children: [c && Object(ee.jsx)("div", {
                                                                className: "symbol",
                                                                dangerouslySetInnerHTML: M(G[s].symbol)
                                                            }), c ? l(e * o * r) : e * r]
                                                        })]
                                                    }, t)
                                                }, t)
                                            }))
                                        })
                                    }, e.get("SymbolID"))
                                }))]
                            }), Object(ee.jsx)("div", {
                                className: "rules",
                                children: Object(ee.jsx)("div", {
                                    dangerouslySetInnerHTML: n(t.getIn(["data", 1, "content"]), a)
                                })
                            })]
                        })]
                    })
                },
                ue = k.b.div(b || (b = Object(T.a)(["\n  display: flex;\n  flex-direction: column;\n  justify-content: center;\n  align-items: center;\n  height: ", ";\n  margin: 10px 0;\n  & .free-span {\n    font-weight: bold;\n    font-size: 20px;\n    display: flex;\n    & > span {\n      font-weight: normal;\n      margin-left: 3px;\n      color: rgb(255, 213, 66);\n      display: flex;\n      align-items: center;\n    }\n  }\n\n  & .half {\n    display: flex;\n    flex-direction: row;\n    align-items: center;\n    & > div {\n      display: flex;\n      align-items: center;\n      padding: 0 5px;\n    }\n    & img {\n      object-fit: scale-down;\n      height: 35px;\n      vertical-align: middle;\n    }\n    & .pic {\n      width: 110px;\n      padding: 20px 0;\n      margin-right: 10px;\n      text-align: center;\n      .pay-img {\n        width: 110px;\n        height: 110px;\n        image-rendering: -webkit-optimize-contrast;\n      }\n    }\n  }\n"])), (function(e) {
                    return e.img && (e.isHorizontal ? "200px" : "350px")
                })),
                xe = function(e) {
                    var t = e.helpData,
                        n = e.createContent,
                        a = e.gameId,
                        i = e.payTableData,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.moneyConvert,
                        o = e.denom,
                        r = e.betLevel,
                        m = e.windowDimensions,
                        d = e.lang,
                        b = e.sify,
                        g = e.tify,
                        j = Object(I.useRef)(),
                        y = Object(I.useState)(!0),
                        h = Object(P.a)(y, 2),
                        u = h[0],
                        x = h[1];
                    return Object(I.useEffect)((function() {
                        var e, t;
                        (null === (e = j.current) || void 0 === e ? void 0 : e.naturalHeight) / (null === (t = j.current) || void 0 === t ? void 0 : t.naturalWidth) > 1.2 && x(!1)
                    }), [j]), Object(ee.jsxs)(ee.Fragment, {
                        children: ["+" !== t.getIn(["data", 0, "title"]) && Object(ee.jsx)("hr", {}), Object(ee.jsxs)(le, {
                            windowDimensions: m,
                            children: ["+" !== t.getIn(["data", 0, "title"]) && Object(ee.jsx)("p", {
                                className: "title",
                                children: "zh-tw" === d ? g(t.getIn(["data", 0, "title"])) : "cn" === d ? b(t.getIn(["data", 0, "title"])) : t.getIn(["data", 0, "title"])
                            }), Object(ee.jsxs)(ue, {
                                img: t.getIn(["data", 0, "icon", "link"]),
                                isHorizontal: u,
                                children: [t.getIn(["data", 0, "icon", "link"]) && Object(ee.jsx)("img", {
                                    className: "object-fit-scale h100",
                                    alt: "",
                                    ref: j,
                                    src: "".concat(W, "/order-detail/common/").concat(a, "/symbolList/").concat(t.getIn(["data", 0, "icon", "link"]), ".png")
                                }), Object(ee.jsxs)("div", {
                                    className: "half mb20 list",
                                    children: [Object(ee.jsxs)("div", {
                                        children: [Object(ee.jsx)("img", {
                                            className: "object-fit-scale",
                                            alt: "",
                                            ref: j,
                                            src: "".concat(W, "/order-detail/common/").concat(a, "/symbolList/F3.png")
                                        }), Object(ee.jsxs)("span", {
                                            className: "free-span",
                                            children: ["1-", Object(ee.jsxs)("span", {
                                                className: Z()({
                                                    money: c
                                                }),
                                                children: [c && Object(ee.jsx)("div", {
                                                    className: "symbol",
                                                    dangerouslySetInnerHTML: M(G[s].symbol)
                                                }), c ? l(i.getIn(["math_data", 1, "SymbolPays", 0]) * o * r) : i.getIn(["math_data", 1, "SymbolPays", 0]) * r]
                                            })]
                                        })]
                                    }), Object(ee.jsxs)("div", {
                                        children: [Object(ee.jsx)("img", {
                                            className: "object-fit-scale",
                                            alt: "",
                                            ref: j,
                                            src: "".concat(W, "/order-detail/common/").concat(a, "/symbolList/F2.png")
                                        }), Object(ee.jsxs)("span", {
                                            className: "free-span",
                                            children: ["1-", Object(ee.jsxs)("span", {
                                                className: Z()({
                                                    money: c
                                                }),
                                                children: [c && Object(ee.jsx)("div", {
                                                    className: "symbol",
                                                    dangerouslySetInnerHTML: M(G[s].symbol)
                                                }), c ? l(i.getIn(["math_data", 2, "SymbolPays", 0]) * o * r) : i.getIn(["math_data", 2, "SymbolPays", 0]) * r]
                                            })]
                                        })]
                                    })]
                                })]
                            }), Object(ee.jsx)("div", {
                                className: "rules",
                                children: Object(ee.jsx)("div", {
                                    dangerouslySetInnerHTML: n(t.getIn(["data", 0, "content"]), a)
                                })
                            })]
                        }), "+" !== t.getIn(["data", 1, "title"]) && Object(ee.jsx)("hr", {}), Object(ee.jsxs)(le, {
                            windowDimensions: m,
                            children: ["+" !== t.getIn(["data", 1, "title"]) && Object(ee.jsx)("p", {
                                className: "title",
                                children: "zh-tw" === d ? g(t.getIn(["data", 1, "title"])) : "cn" === d ? b(t.getIn(["data", 1, "title"])) : t.getIn(["data", 1, "title"])
                            }), t.getIn(["data", 1, "icon", "link"]) && Object(ee.jsxs)(oe, {
                                img: t.getIn(["data", 1, "icon", "link"]),
                                isHorizontal: u,
                                children: [Object(ee.jsx)("img", {
                                    className: "object-fit-scale mb20 h100",
                                    alt: "",
                                    ref: j,
                                    src: "".concat(W, "/order-detail/common/").concat(a, "/symbolList/").concat(t.getIn(["data", 1, "icon", "link"]), ".png")
                                }), "F" === t.getIn(["data", 1, "icon", "link"]) ? i.get("math_data") && i.get("math_data").filter((function(e) {
                                    return ("F" === e.get("SymbolName") || "SC" === e.get("SymbolName")) && !e.get("SymbolPays").every((function(e) {
                                        return 0 === e
                                    }))
                                })).map((function(e) {
                                    return Object(ee.jsx)("div", {
                                        className: "half",
                                        children: Object(ee.jsx)("div", {
                                            className: "list",
                                            children: e.get("SymbolPays").reverse().map((function(e, t) {
                                                var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                                return Object(ee.jsx)(I.Fragment, {
                                                    children: 0 !== e && Object(ee.jsxs)("div", {
                                                        children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                            className: Z()({
                                                                money: c
                                                            }),
                                                            children: [c && Object(ee.jsx)("div", {
                                                                className: "symbol",
                                                                dangerouslySetInnerHTML: M(G[s].symbol)
                                                            }), c ? l(e * o * r) : e * r]
                                                        })]
                                                    }, t)
                                                }, t)
                                            }))
                                        })
                                    }, e.get("SymbolID"))
                                })) : i.get("math_data") && i.get("math_data").filter((function(e) {
                                    return e.get("SymbolName") === t.getIn(["data", 1, "icon", "link"]) && !e.get("SymbolPays").every((function(e) {
                                        return 0 === e
                                    }))
                                })).map((function(e) {
                                    return Object(ee.jsx)("div", {
                                        className: "half",
                                        children: Object(ee.jsx)("div", {
                                            className: "list",
                                            children: e.get("SymbolPays").reverse().map((function(e, t) {
                                                var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                                return Object(ee.jsx)(I.Fragment, {
                                                    children: 0 !== e && Object(ee.jsxs)("div", {
                                                        children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                            className: Z()({
                                                                money: c
                                                            }),
                                                            children: [c && Object(ee.jsx)("div", {
                                                                className: "symbol",
                                                                dangerouslySetInnerHTML: M(G[s].symbol)
                                                            }), c ? l(e * o * r) : e * r]
                                                        })]
                                                    }, t)
                                                }, t)
                                            }))
                                        })
                                    }, e.get("SymbolID"))
                                }))]
                            }), Object(ee.jsx)("div", {
                                className: "rules",
                                children: Object(ee.jsx)("div", {
                                    dangerouslySetInnerHTML: n(t.getIn(["data", 1, "content"]), a)
                                })
                            })]
                        })]
                    })
                },
                pe = function(e) {
                    var t = e.helpData,
                        n = e.createContent,
                        a = e.gameId,
                        i = e.payTableData,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.moneyConvert,
                        o = e.denom,
                        r = e.betLevel,
                        m = e.windowDimensions,
                        d = e.lang,
                        b = e.sify,
                        g = e.tify,
                        j = Object(I.useState)(!0),
                        y = Object(P.a)(j, 2),
                        h = y[0],
                        u = y[1],
                        x = function(e) {
                            e.target.naturalHeight / e.target.naturalWidth > 1.2 && u(!1)
                        };
                    return Object(ee.jsxs)(ee.Fragment, {
                        children: [Object(ee.jsxs)(le, {
                            windowDimensions: m,
                            children: [Object(ee.jsx)("p", {
                                className: "title",
                                children: "zh-tw" === d ? g(t.getIn(["data", 0, "title"])) : "cn" === d ? b(t.getIn(["data", 0, "title"])) : t.getIn(["data", 0, "title"])
                            }), t.getIn(["data", 0, "icon", "link"]) && Object(ee.jsxs)(oe, {
                                img: t.getIn(["data", 0, "icon", "link"]),
                                isHorizontal: h,
                                children: [Object(ee.jsx)("img", {
                                    className: Z()("object-fit-scale mb20 h100", {
                                        w100: "F" !== t.getIn(["data", 0, "icon", "link"]) && "W" !== t.getIn(["data", 0, "icon", "link"])
                                    }),
                                    alt: "",
                                    onLoad: function(e) {
                                        return x(e)
                                    },
                                    src: "".concat(W, "/order-detail/common/").concat(a, "/symbolList/").concat(t.getIn(["data", 0, "icon", "link"]), ".png")
                                }), "F" === t.getIn(["data", 0, "icon", "link"]) ? i.get("math_data") && i.get("math_data").filter((function(e) {
                                    return ("F" === e.get("SymbolName") || "SC" === e.get("SymbolName")) && !e.get("SymbolPays").every((function(e) {
                                        return 0 === e
                                    }))
                                })).map((function(e) {
                                    return Object(ee.jsx)("div", {
                                        className: "half",
                                        children: Object(ee.jsx)("div", {
                                            className: "list",
                                            children: e.get("SymbolPays").reverse().map((function(e, t) {
                                                var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                                return Object(ee.jsx)(I.Fragment, {
                                                    children: 0 !== e && Object(ee.jsxs)("div", {
                                                        children: [n.size - t, " -", Object(ee.jsx)("span", {
                                                            children: "".concat(e, "X")
                                                        })]
                                                    }, t)
                                                }, t)
                                            }))
                                        })
                                    }, e.get("SymbolID"))
                                })) : i.get("math_data") && i.get("math_data").filter((function(e) {
                                    return e.get("SymbolName") === t.getIn(["data", 0, "icon", "link"]) && !e.get("SymbolPays").every((function(e) {
                                        return 0 === e
                                    }))
                                })).map((function(e) {
                                    return Object(ee.jsx)("div", {
                                        className: "half",
                                        children: Object(ee.jsx)("div", {
                                            className: "list",
                                            children: e.get("SymbolPays").reverse().map((function(e, t) {
                                                var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                                return Object(ee.jsx)(I.Fragment, {
                                                    children: 0 !== e && Object(ee.jsxs)("div", {
                                                        children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                            className: Z()({
                                                                money: c
                                                            }),
                                                            children: [c && Object(ee.jsx)("div", {
                                                                className: "symbol",
                                                                dangerouslySetInnerHTML: M(G[s].symbol)
                                                            }), c ? l(e * o * r) : e * r]
                                                        })]
                                                    }, t)
                                                }, t)
                                            }))
                                        })
                                    }, e.get("SymbolID"))
                                }))]
                            }), Object(ee.jsx)("div", {
                                className: "rules",
                                children: Object(ee.jsx)("div", {
                                    dangerouslySetInnerHTML: n(t.getIn(["data", 0, "content"]), a)
                                })
                            })]
                        }), t.get("data").shift().map((function(e, t) {
                            return Object(ee.jsxs)(I.Fragment, {
                                children: ["+" !== e.get("title") && Object(ee.jsx)("hr", {}), Object(ee.jsxs)(le, {
                                    windowDimensions: m,
                                    children: ["+" !== e.get("title") && Object(ee.jsx)("p", {
                                        className: "title",
                                        children: "zh-tw" === d ? g(e.get("title")) : "cn" === d ? b(e.get("title")) : e.get("title")
                                    }), e.getIn(["icon", "link"]) && Object(ee.jsxs)(oe, {
                                        img: e.getIn(["icon", "link"]),
                                        isHorizontal: h,
                                        children: [Object(ee.jsx)("img", {
                                            className: Z()("object-fit-scale mb20 h100", {
                                                w100: "F" !== e.getIn(["icon", "link"]) && "W" !== e.getIn(["icon", "link"])
                                            }),
                                            alt: "",
                                            onLoad: function(e) {
                                                return x(e)
                                            },
                                            src: "".concat(W, "/order-detail/common/").concat(a, "/symbolList/").concat(e.getIn(["icon", "link"]), ".png")
                                        }), "F" === e.getIn(["icon", "link"]) ? i.get("math_data") && i.get("math_data").filter((function(e) {
                                            return ("F" === e.get("SymbolName") || "SC" === e.get("SymbolName")) && !e.get("SymbolPays").every((function(e) {
                                                return 0 === e
                                            }))
                                        })).map((function(e) {
                                            return Object(ee.jsx)("div", {
                                                className: "half",
                                                children: Object(ee.jsx)("div", {
                                                    className: "list",
                                                    children: e.get("SymbolPays").reverse().map((function(e, t) {
                                                        var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                                        return Object(ee.jsx)(I.Fragment, {
                                                            children: 0 !== e && Object(ee.jsxs)("div", {
                                                                children: [n.size - t, " -", Object(ee.jsx)("span", {
                                                                    children: "".concat(e, "X")
                                                                })]
                                                            }, t)
                                                        }, t)
                                                    }))
                                                })
                                            }, e.get("SymbolID"))
                                        })) : i.get("math_data") && i.get("math_data").filter((function(t) {
                                            return t.get("SymbolName") === e.getIn(["icon", "link"]) && !t.get("SymbolPays").every((function(e) {
                                                return 0 === e
                                            }))
                                        })).map((function(e) {
                                            return Object(ee.jsx)("div", {
                                                className: "half",
                                                children: Object(ee.jsx)("div", {
                                                    className: "list",
                                                    children: e.get("SymbolPays").reverse().map((function(e, t) {
                                                        var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                                        return Object(ee.jsx)(I.Fragment, {
                                                            children: 0 !== e && Object(ee.jsxs)("div", {
                                                                children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                                    className: Z()({
                                                                        money: c
                                                                    }),
                                                                    children: [c && Object(ee.jsx)("div", {
                                                                        className: "symbol",
                                                                        dangerouslySetInnerHTML: M(G[s].symbol)
                                                                    }), c ? l(e * o * r) : e * r]
                                                                })]
                                                            }, t)
                                                        }, t)
                                                    }))
                                                })
                                            }, e.get("SymbolID"))
                                        }))]
                                    }), Object(ee.jsx)("div", {
                                        className: "rules",
                                        children: Object(ee.jsx)("div", {
                                            dangerouslySetInnerHTML: n(e.get("content"), a)
                                        })
                                    })]
                                })]
                            }, t)
                        }))]
                    })
                },
                fe = Object(k.b)(le)(g || (g = Object(T.a)(["\n  & .rules {\n    p {\n      &.img-text {\n        > img {\n          height: 120px;\n        }\n      }\n    }\n  }\n"]))),
                Oe = function(e) {
                    var t = e.rule,
                        n = e.createContent,
                        a = e.gameId,
                        i = e.payTableData,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.moneyConvert,
                        o = e.denom,
                        r = e.betLevel,
                        m = e.windowDimensions,
                        d = e.lang,
                        b = e.sify,
                        g = e.tify,
                        j = Object(I.useState)(!0),
                        y = Object(P.a)(j, 2),
                        h = y[0],
                        u = y[1];
                    return Object(ee.jsxs)(ee.Fragment, {
                        children: ["+" !== t.get("title") && Object(ee.jsx)("hr", {}), Object(ee.jsxs)(fe, {
                            windowDimensions: m,
                            children: ["+" !== t.get("title") && Object(ee.jsx)("p", {
                                className: "title",
                                children: "zh-tw" === d ? g(t.get("title")) : "cn" === d ? b(t.get("title")) : t.get("title")
                            }), t.getIn(["icon", "link"]) && Object(ee.jsxs)(oe, {
                                img: t.getIn(["icon", "link"]),
                                isHorizontal: h,
                                children: [Object(ee.jsx)("img", {
                                    className: Z()("object-fit-scale mb20 h100", {
                                        w100: "F" !== t.getIn(["icon", "link"]) && "W" !== t.getIn(["icon", "link"])
                                    }),
                                    alt: "",
                                    onLoad: function(e) {
                                        return function(e) {
                                            e.target.naturalHeight / e.target.naturalWidth > 1.2 && u(!1)
                                        }(e)
                                    },
                                    src: "".concat(W, "/order-detail/common/").concat(a, "/symbolList/").concat(t.getIn(["icon", "link"]), ".png")
                                }), "F" === t.getIn(["icon", "link"]) ? i.get("math_data") && i.get("math_data").filter((function(e) {
                                    return ("F" === e.get("SymbolName") || "SC" === e.get("SymbolName")) && !e.get("SymbolPays").every((function(e) {
                                        return 0 === e
                                    }))
                                })).map((function(e) {
                                    return Object(ee.jsx)("div", {
                                        className: "half",
                                        children: Object(ee.jsx)("div", {
                                            className: "list",
                                            children: e.get("SymbolPays").reverse().map((function(e, t) {
                                                var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                                return Object(ee.jsx)(I.Fragment, {
                                                    children: 0 !== e && Object(ee.jsxs)("div", {
                                                        children: [n.size - t, " -", Object(ee.jsx)("span", {
                                                            children: "".concat(e, "X")
                                                        })]
                                                    }, t)
                                                }, t)
                                            }))
                                        })
                                    }, e.get("SymbolID"))
                                })) : i.get("math_data") && i.get("math_data").filter((function(e) {
                                    return e.get("SymbolName") === t.getIn(["icon", "link"]) && !e.get("SymbolPays").every((function(e) {
                                        return 0 === e
                                    }))
                                })).map((function(e) {
                                    return Object(ee.jsx)("div", {
                                        className: "half",
                                        children: Object(ee.jsx)("div", {
                                            className: "list",
                                            children: e.get("SymbolPays").reverse().map((function(e, t) {
                                                var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                                return Object(ee.jsx)(I.Fragment, {
                                                    children: 0 !== e && Object(ee.jsxs)("div", {
                                                        children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                            className: Z()({
                                                                money: c
                                                            }),
                                                            children: [c && Object(ee.jsx)("div", {
                                                                className: "symbol",
                                                                dangerouslySetInnerHTML: M(G[s].symbol)
                                                            }), c ? l(e * o * r) : e * r]
                                                        })]
                                                    }, t)
                                                }, t)
                                            }))
                                        })
                                    }, e.get("SymbolID"))
                                }))]
                            }), Object(ee.jsx)("div", {
                                className: "rules",
                                children: Object(ee.jsx)("div", {
                                    dangerouslySetInnerHTML: n(t.get("content"), a)
                                })
                            })]
                        })]
                    })
                },
                ve = function(e) {
                    var t = e.helpData;
                    return Object(ee.jsx)(ee.Fragment, {
                        children: t.get("data").map((function(t, n) {
                            return Object(ee.jsx)(Oe, Object(D.a)({
                                rule: t
                            }, e), n)
                        }))
                    })
                },
                Ne = function(e) {
                    var t = e.rule,
                        n = e.createContent,
                        a = e.gameId,
                        i = e.payTableData,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.moneyConvert,
                        o = e.denom,
                        r = e.betLevel,
                        m = e.windowDimensions,
                        d = e.lang,
                        b = e.sify,
                        g = e.tify,
                        j = Object(I.useState)(!0),
                        y = Object(P.a)(j, 2),
                        h = y[0],
                        u = y[1];
                    return Object(ee.jsxs)(ee.Fragment, {
                        children: ["+" !== t.get("title") && Object(ee.jsx)("hr", {}), Object(ee.jsxs)(le, {
                            windowDimensions: m,
                            children: ["+" !== t.get("title") && Object(ee.jsx)("p", {
                                className: "title",
                                children: "zh-tw" === d ? g(t.get("title")) : "cn" === d ? b(t.get("title")) : t.get("title")
                            }), t.getIn(["icon", "link"]) && Object(ee.jsxs)(oe, {
                                img: t.getIn(["icon", "link"]),
                                isHorizontal: h,
                                children: [Object(ee.jsx)("img", {
                                    className: "object-fit-scale mb20 h100",
                                    alt: "",
                                    onLoad: function(e) {
                                        return function(e) {
                                            e.target.naturalHeight / e.target.naturalWidth > 1.2 && u(!1)
                                        }(e)
                                    },
                                    src: "".concat(W, "/order-detail/common/").concat(a, "/symbolList/").concat(t.getIn(["icon", "link"]), ".png")
                                }), "W_S" === t.getIn(["icon", "link"]) ? i.get("math_data") && i.get("math_data").filter((function(e) {
                                    return "SC" === e.get("SymbolName") && !e.get("SymbolPays").every((function(e) {
                                        return 0 === e
                                    }))
                                })).map((function(e) {
                                    return Object(ee.jsx)("div", {
                                        className: "half",
                                        children: Object(ee.jsx)("div", {
                                            className: "list",
                                            children: e.get("SymbolPays").reverse().map((function(e, t) {
                                                var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                                return Object(ee.jsx)(I.Fragment, {
                                                    children: 0 !== e && Object(ee.jsxs)("div", {
                                                        children: [n.size - t, " -", Object(ee.jsx)("span", {
                                                            children: "".concat(e, "X")
                                                        })]
                                                    }, t)
                                                }, t)
                                            }))
                                        })
                                    }, e.get("SymbolID"))
                                })) : i.get("math_data") && i.get("math_data").filter((function(e) {
                                    return e.get("SymbolName") === t.getIn(["icon", "link"]) && !e.get("SymbolPays").every((function(e) {
                                        return 0 === e
                                    }))
                                })).map((function(e) {
                                    return Object(ee.jsx)("div", {
                                        className: "half",
                                        children: Object(ee.jsx)("div", {
                                            className: "list",
                                            children: e.get("SymbolPays").reverse().map((function(e, t) {
                                                var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                                return Object(ee.jsx)(I.Fragment, {
                                                    children: 0 !== e && Object(ee.jsxs)("div", {
                                                        children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                            className: Z()({
                                                                money: c
                                                            }),
                                                            children: [c && Object(ee.jsx)("div", {
                                                                className: "symbol",
                                                                dangerouslySetInnerHTML: M(G[s].symbol)
                                                            }), c ? l(e * o * r) : e * r]
                                                        })]
                                                    }, t)
                                                }, t)
                                            }))
                                        })
                                    }, e.get("SymbolID"))
                                }))]
                            }), Object(ee.jsx)("div", {
                                className: "rules",
                                children: Object(ee.jsx)("div", {
                                    dangerouslySetInnerHTML: n(t.get("content"), a)
                                })
                            })]
                        })]
                    })
                },
                Se = function(e) {
                    var t = e.helpData;
                    return Object(ee.jsx)(ee.Fragment, {
                        children: t.get("data").map((function(t, n) {
                            return Object(ee.jsx)(Ne, Object(D.a)({
                                rule: t
                            }, e), n)
                        }))
                    })
                },
                we = n(16),
                Ie = ["1", "5", "5_2", "8", "9", "9_2", "10", "10_2", "10_3", "10_4", "10_5", "15", "15_2", "20", "20_2", "25", "25_2", "25_3", "30", "40", "40_2", "50", "50_2", "60", "88"],
                Le = ["108", "243", "576", "720", "1024", "4096", "any3", "any5", "any6", "3x5ways", "4x5ways", "richways"],
                ze = function(e) {
                    var t = Ie.includes(e),
                        n = "";
                    return Le.includes(e) ? n = "ways" : t && (n = "lines"), n
                },
                _e = k.b.div(j || (j = Object(T.a)(["\n  width: 100%;\n  display: flex;\n  flex-direction: column;\n"]))),
                De = k.b.div(y || (y = Object(T.a)(["\n  width: 100%;\n  display: flex;\n  flex-direction: ", ";\n  & .half {\n    width: 100%;\n    display: flex;\n    flex-direction: column;\n    align-items: center;\n    &.m-row {\n      flex-direction: ", ";\n      justify-content: center;\n    }\n    & .circle {\n      display: flex;\n      align-items: center;\n      justify-content: center;\n      border: 3px solid;\n      border-radius: 50%;\n      width: 30px;\n      height: 30px;\n      &.correct {\n        color: #45fe01;\n        border-color: #45fe01;\n      }\n      &.incorrect {\n        color: #f80701;\n        border-color: #f80701;\n      }\n    }\n  }\n  & > div:not(:first-child) {\n    margin-top: ", ";\n  }\n"])), (function(e) {
                    return e.windowDimensions.width >= V ? "row" : "column"
                }), (function(e) {
                    return e.windowDimensions.width >= V ? "column" : "row-reverse"
                }), (function(e) {
                    return e.windowDimensions.width >= V ? "0" : "40px"
                })),
                Pe = function(e) {
                    var t = e.helpData,
                        n = e.lang,
                        a = e.windowDimensions,
                        i = e.createContent,
                        c = e.gameId,
                        s = !["155", "76", "95", "GB5032"].includes(c);
                    return Object(ee.jsxs)(ee.Fragment, {
                        children: [s && Object(ee.jsx)("hr", {}), t.get("line") && "ways" === ze(t.get("line")) ? Object(ee.jsx)(ee.Fragment, {
                            children: Object(ee.jsxs)(_e, {
                                children: [Object(ee.jsx)("p", {
                                    className: "title",
                                    children: t.get("line").includes("ways") ? "richways" === t.get("line") ? J.richways[n] : J.reelways[n] : t.get("line").includes("any") ? J[t.get("line")][n] : "".concat(t.get("line"), " ").concat(J.ways[n])
                                }), Object(ee.jsx)("div", {
                                    className: "rules",
                                    children: t.getIn(["line_content", "data"]) && Object(ee.jsx)("div", {
                                        dangerouslySetInnerHTML: i(t.getIn(["line_content", "data"]), c)
                                    })
                                }), Object(ee.jsxs)(De, {
                                    windowDimensions: a,
                                    children: [Object(ee.jsxs)("div", {
                                        className: "half m-row",
                                        children: [Object(ee.jsx)("div", {
                                            className: "circle correct",
                                            children: Object(ee.jsx)(we.a, {})
                                        }), Object(ee.jsx)("img", {
                                            className: "ways-img max300 m-10 object-fit-contain",
                                            alt: "",
                                            src: "".concat(W, "/help/admin/slot/line/").concat(t.get("line"), "c.png")
                                        })]
                                    }), Object(ee.jsxs)("div", {
                                        className: "half m-row",
                                        children: [Object(ee.jsx)("div", {
                                            className: "circle incorrect",
                                            children: Object(ee.jsx)(we.b, {})
                                        }), Object(ee.jsx)("img", {
                                            className: "ways-img max300 m-10 object-fit-contain",
                                            alt: "",
                                            src: "".concat(W, "/help/admin/slot/line/").concat(t.get("line"), "e.png")
                                        })]
                                    })]
                                })]
                            })
                        }) : Object(ee.jsx)(ee.Fragment, {
                            children: Object(ee.jsxs)(_e, {
                                children: [Object(ee.jsxs)("p", {
                                    className: "title",
                                    children: [t.get("line").split("_")[0], " ", "1" === t.get("line") ? J.line[n] : J.lines[n]]
                                }), Object(ee.jsx)("div", {
                                    className: "rules",
                                    children: t.getIn(["line_content", "data"]) && Object(ee.jsx)("div", {
                                        dangerouslySetInnerHTML: i(t.getIn(["line_content", "data"]), c)
                                    })
                                }), Object(ee.jsx)(De, {
                                    className: "jcc",
                                    windowDimensions: a,
                                    children: Object(ee.jsx)("div", {
                                        className: "w100",
                                        children: Object(ee.jsx)("img", {
                                            className: "object-fit-scale m-w100 object-top",
                                            alt: "",
                                            src: "".concat(W, "/help/admin/slot/line/").concat(t.get("line"), ".png")
                                        })
                                    })
                                })]
                            })
                        })]
                    })
                },
                Te = k.b.div(h || (h = Object(T.a)(["\n  display: flex;\n  align-items: center;\n  position: relative;\n  height: 40px;\n  width: 100%;\n  top: -30px;\n\n  & .totalBet-bg {\n    position: absolute;\n    right: 0px;\n    top: 0px;\n    padding: 5px 10px;\n    border-radius: 16px;\n    border: ", ";\n    font-size: 16px;\n    color: ", ";\n    & > div {\n      display: flex;\n      align-items: center;\n      justify-content: center;\n    }\n    & .symbol {\n      color: #ffd542;\n      height: 13px;\n    }\n    & .total {\n      color: #ffd542;\n      display: flex;\n      align-items: center;\n      justify-content: center;\n      margin-left: 10px;\n    }\n  }\n"])), (function(e) {
                    return "2px solid ".concat(e.theme.colors.border)
                }), (function(e) {
                    return e.theme.colors.totalBetText
                })),
                ke = function(e) {
                    var t = e.lang,
                        n = e.isCurrency,
                        a = e.currency,
                        i = e.bet,
                        c = e.denom;
                    return Object(ee.jsx)(Te, {
                        children: Object(ee.jsx)("div", {
                            className: "totalBet-bg",
                            children: Object(ee.jsxs)("div", {
                                children: ["".concat(J.totalBet[t]), Object(ee.jsx)("span", {
                                    className: "total",
                                    children: n ? Object(ee.jsxs)(ee.Fragment, {
                                        children: [Object(ee.jsx)("div", {
                                            className: "symbol",
                                            dangerouslySetInnerHTML: M(G[a].symbol)
                                        }), (1 * i).toFixed(2)]
                                    }) : "".concat(i / c)
                                })]
                            })
                        })
                    })
                },
                Ce = k.b.div(u || (u = Object(T.a)(["\n  width: 100%;\n  display: flex;\n  flex-direction: column;\n"]))),
                Fe = k.b.div(x || (x = Object(T.a)(["\n  width: 100%;\n  display: flex;\n  flex-direction: ", ";\n  text-align: left;\n  flex-wrap: wrap;\n  & .half {\n    width: 100%;\n    display: flex;\n    flex-direction: row;\n    align-items: center;\n    margin-bottom: ", ";\n    @media (min-width: 700px) {\n      width: 50%;\n    }\n    @media (min-width: 1024px) {\n      width: 33.3%;\n    }\n    & .pic {\n      width: 40%;\n      padding: 20px 0;\n      text-align: right;\n      .pay-img {\n        width: 112px;\n        height: 112px;\n        image-rendering: -webkit-optimize-contrast;\n      }\n    }\n    & .list {\n      flex: 1;\n      display: flex;\n      flex-direction: column;\n      justify-content: center;\n      font-size: 24px;\n      margin-left: 10px;\n      & > div {\n        margin: 3px 0;\n        white-space: nowrap;\n      }\n      & span {\n        font-size: 24px;\n        color: #ffd542;\n        margin-left: 5px;\n      }\n    }\n  }\n"])), (function(e) {
                    return e.windowDimensions.width >= V ? "row" : "column"
                }), (function(e) {
                    return e.isLongList ? "40px" : "-5px"
                })),
                He = function(e) {
                    var t = e.lang,
                        n = e.windowDimensions,
                        a = e.payTableData,
                        i = e.gameId,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.denom,
                        o = e.betLevel,
                        r = e.moneyConvert,
                        m = e.symbol,
                        d = e.bet,
                        b = e.isLongList;
                    return Object(ee.jsxs)(Ce, {
                        children: [Object(ee.jsx)(ke, {
                            lang: t,
                            isCurrency: c,
                            currency: s,
                            bet: d,
                            denom: l
                        }), Object(ee.jsx)("p", {
                            className: "title",
                            children: J.payTable[t]
                        }), Object(ee.jsx)(Fe, {
                            windowDimensions: n,
                            isLongList: b,
                            children: a.get("math_data") && a.get("math_data").filter((function(e) {
                                return !e.get("SymbolPays").every((function(e) {
                                    return 0 === e
                                })) && "SC" !== e.get("SymbolName") && "W" !== e.get("SymbolName") && "F" !== e.get("SymbolName") && "W1" !== e.get("SymbolName") && "W2" !== e.get("SymbolName") && "W3" !== e.get("SymbolName") && "W4" !== e.get("SymbolName") && "W5" !== e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            }))
                        })]
                    })
                },
                We = function(e) {
                    var t = e.lang,
                        n = e.windowDimensions,
                        a = e.payTableData,
                        i = e.gameId,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.denom,
                        o = e.betLevel,
                        r = e.moneyConvert,
                        m = e.symbol,
                        d = e.bet;
                    return Object(ee.jsxs)(Ce, {
                        children: [Object(ee.jsx)(ke, {
                            lang: t,
                            isCurrency: c,
                            currency: s,
                            bet: d,
                            denom: l
                        }), Object(ee.jsx)("p", {
                            className: "title",
                            children: J.payTable[t]
                        }), Object(ee.jsx)(Fe, {
                            windowDimensions: n,
                            children: a.get("math_data") && a.get("math_data").filter((function(e) {
                                return !e.get("SymbolPays").every((function(e) {
                                    return 0 === e
                                })) && "SC" !== e.get("SymbolName") && "W" !== e.get("SymbolName") && "F" !== e.get("SymbolName") && "W1" !== e.get("SymbolName") && "W2" !== e.get("SymbolName") && "W3" !== e.get("SymbolName") && "W4" !== e.get("SymbolName") && "W5" !== e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            }))
                        })]
                    })
                },
                Ae = k.b.div(p || (p = Object(T.a)(["\n    font-size: 18px;\n    white-space: nowrap;\n"]))),
                Me = function(e) {
                    var t = e.lang,
                        n = e.windowDimensions,
                        a = e.payTableData,
                        i = e.gameId,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.denom,
                        o = e.betLevel,
                        r = e.moneyConvert,
                        m = e.symbol,
                        d = e.bet;
                    return Object(ee.jsxs)(Ce, {
                        children: [Object(ee.jsx)(ke, {
                            lang: t,
                            isCurrency: c,
                            currency: s,
                            bet: d,
                            denom: l
                        }), Object(ee.jsx)("p", {
                            className: "title",
                            children: J.payTable[t]
                        }), Object(ee.jsx)(Fe, {
                            windowDimensions: n,
                            children: a.get("math_data") && a.get("math_data").filter((function(e) {
                                return !e.get("SymbolPays").every((function(e) {
                                    return 0 === e
                                })) && "SC" !== e.get("SymbolName") && "W" !== e.get("SymbolName") && "F" !== e.get("SymbolName") && "W1" !== e.get("SymbolName") && "W2" !== e.get("SymbolName") && "W3" !== e.get("SymbolName") && "W4" !== e.get("SymbolName") && "W5" !== e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: "H5" === e.get("SymbolName") || "N4" === e.get("SymbolName") ? e.get("SymbolPays").reverse().map((function(e, n) {
                                            var a = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)(Ae, {
                                                    children: [J.any[t], a.size - n, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, n)
                                            }, n)
                                        })) : e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            }))
                        })]
                    })
                },
                Ee = function(e) {
                    var t = e.lang,
                        n = e.windowDimensions,
                        a = e.payTableData,
                        i = e.gameId,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.denom,
                        o = e.betLevel,
                        r = e.moneyConvert,
                        m = e.symbol,
                        d = e.bet;
                    return Object(ee.jsxs)(Ce, {
                        children: [Object(ee.jsx)(ke, {
                            lang: t,
                            isCurrency: c,
                            currency: s,
                            bet: d,
                            denom: l
                        }), Object(ee.jsx)("p", {
                            className: "title",
                            children: J.payTable[t]
                        }), Object(ee.jsx)(Fe, {
                            windowDimensions: n,
                            children: a.get("math_data") && a.get("math_data").filter((function(e) {
                                return !e.get("SymbolPays").every((function(e) {
                                    return 0 === e
                                })) && "SC" !== e.get("SymbolName") && "W" !== e.get("SymbolName") && "F" !== e.get("SymbolName") && "W1" !== e.get("SymbolName") && "W2" !== e.get("SymbolName") && "W3" !== e.get("SymbolName") && "W4" !== e.get("SymbolName") && "W5" !== e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: "H5" === e.get("SymbolName") || "N4" === e.get("SymbolName") ? e.get("SymbolPays").reverse().map((function(e, n) {
                                            var a = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)(Ae, {
                                                    children: [J.any[t], a.size - n, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, n)
                                            }, n)
                                        })) : e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            }))
                        })]
                    })
                },
                Ye = function(e) {
                    var t = e.lang,
                        n = e.windowDimensions,
                        a = e.payTableData,
                        i = e.gameId,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.denom,
                        o = e.betLevel,
                        r = e.moneyConvert,
                        m = e.symbol,
                        d = e.bet;
                    return Object(ee.jsxs)(Ce, {
                        children: [Object(ee.jsx)(ke, {
                            lang: t,
                            isCurrency: c,
                            currency: s,
                            bet: d,
                            denom: l
                        }), Object(ee.jsx)("p", {
                            className: "title",
                            children: J.payTable[t]
                        }), Object(ee.jsx)(Fe, {
                            windowDimensions: n,
                            children: a.get("math_data") && a.get("math_data").filter((function(e) {
                                return !e.get("SymbolPays").every((function(e) {
                                    return 0 === e
                                })) && "SC" !== e.get("SymbolName") && "W" !== e.get("SymbolName") && "F" !== e.get("SymbolName") && "W1" !== e.get("SymbolName") && "W2" !== e.get("SymbolName") && "W3" !== e.get("SymbolName") && "W4" !== e.get("SymbolName") && "W5" !== e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: "N4" === e.get("SymbolName") ? e.get("SymbolPays").reverse().map((function(e, n) {
                                            var a = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)(Ae, {
                                                    children: [J.any[t], a.size - n, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, n)
                                            }, n)
                                        })) : e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            }))
                        })]
                    })
                },
                Re = function(e) {
                    var t = e.lang,
                        n = e.windowDimensions,
                        a = e.payTableData,
                        i = e.gameId,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.denom,
                        o = e.betLevel,
                        r = e.moneyConvert,
                        m = e.symbol,
                        d = e.bet;
                    return Object(ee.jsxs)(Ce, {
                        children: [Object(ee.jsx)(ke, {
                            lang: t,
                            isCurrency: c,
                            currency: s,
                            bet: d,
                            denom: l
                        }), Object(ee.jsx)("p", {
                            className: "title",
                            children: J.payTable[t]
                        }), Object(ee.jsx)(Fe, {
                            windowDimensions: n,
                            children: a.get("math_data") && a.get("math_data").filter((function(e) {
                                return !e.get("SymbolPays").every((function(e) {
                                    return 0 === e
                                })) && "SC" !== e.get("SymbolName") && "W" !== e.get("SymbolName") && "F" !== e.get("SymbolName") && "W1" !== e.get("SymbolName") && "W2" !== e.get("SymbolName") && "W3" !== e.get("SymbolName") && "W4" !== e.get("SymbolName") && "W5" !== e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: "H5" === e.get("SymbolName") || "N4" === e.get("SymbolName") ? e.get("SymbolPays").reverse().map((function(e, n) {
                                            var a = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)(Ae, {
                                                    children: [J.any[t], a.size - n, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, n)
                                            }, n)
                                        })) : e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            }))
                        })]
                    })
                },
                Be = function(e) {
                    var t = e.lang,
                        n = e.windowDimensions,
                        a = e.payTableData,
                        i = e.gameId,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.denom,
                        o = e.betLevel,
                        r = e.moneyConvert,
                        m = e.symbol,
                        d = e.bet;
                    return Object(ee.jsxs)(Ce, {
                        children: [Object(ee.jsx)(ke, {
                            lang: t,
                            isCurrency: c,
                            currency: s,
                            bet: d,
                            denom: l
                        }), Object(ee.jsx)("p", {
                            className: "title",
                            children: J.payTable[t]
                        }), Object(ee.jsxs)(Fe, {
                            windowDimensions: n,
                            children: [a.get("math_data") && a.get("math_data").filter((function(e) {
                                return !e.get("SymbolPays").every((function(e) {
                                    return 0 === e
                                })) && "H1" === e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            })), a.get("math_data") && a.get("math_data").filter((function(e) {
                                return !e.get("SymbolPays").every((function(e) {
                                    return 0 === e
                                })) && "SC" === e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            })), a.get("math_data") && a.get("math_data").filter((function(e) {
                                return !e.get("SymbolPays").every((function(e) {
                                    return 0 === e
                                })) && "SC" !== e.get("SymbolName") && "W" !== e.get("SymbolName") && "F" !== e.get("SymbolName") && "W1" !== e.get("SymbolName") && "W2" !== e.get("SymbolName") && "W3" !== e.get("SymbolName") && "W4" !== e.get("SymbolName") && "W5" !== e.get("SymbolName") && "H1" !== e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: "N5" === e.get("SymbolName") ? e.get("SymbolPays").reverse().map((function(e, n) {
                                            var a = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)(Ae, {
                                                    children: [J.any[t], a.size - n, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, n)
                                            }, n)
                                        })) : e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            }))]
                        })]
                    })
                },
                Ge = function(e) {
                    var t = e.lang,
                        n = e.windowDimensions,
                        a = e.payTableData,
                        i = e.gameId,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.denom,
                        o = e.betLevel,
                        r = e.moneyConvert,
                        m = e.symbol,
                        d = e.bet;
                    return Object(ee.jsxs)(Ce, {
                        children: [Object(ee.jsx)(ke, {
                            lang: t,
                            isCurrency: c,
                            currency: s,
                            bet: d,
                            denom: l
                        }), Object(ee.jsx)("p", {
                            className: "title",
                            children: J.payTable[t]
                        }), Object(ee.jsx)(Fe, {
                            windowDimensions: n,
                            children: a.get("math_data") && a.get("math_data").filter((function(e) {
                                return !e.get("SymbolPays").every((function(e) {
                                    return 0 === e
                                })) && "SC" !== e.get("SymbolName") && "W" !== e.get("SymbolName") && "F" !== e.get("SymbolName") && "FW" !== e.get("SymbolName") && "W1" !== e.get("SymbolName") && "W2" !== e.get("SymbolName") && "W3" !== e.get("SymbolName") && "W4" !== e.get("SymbolName") && "W5" !== e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            }))
                        })]
                    })
                },
                $e = function(e) {
                    var t = e.lang,
                        n = e.windowDimensions,
                        a = e.payTableData,
                        i = e.gameId,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.denom,
                        o = e.betLevel,
                        r = e.moneyConvert,
                        m = e.symbol,
                        d = e.bet;
                    return Object(ee.jsxs)(Ce, {
                        children: [Object(ee.jsx)(ke, {
                            lang: t,
                            isCurrency: c,
                            currency: s,
                            bet: d,
                            denom: l
                        }), Object(ee.jsx)("p", {
                            className: "title",
                            children: J.payTable[t]
                        }), Object(ee.jsx)(Fe, {
                            windowDimensions: n,
                            children: a.get("math_data") && a.get("math_data").filter((function(e) {
                                return !e.get("SymbolPays").every((function(e) {
                                    return 0 === e
                                })) && "SC" !== e.get("SymbolName") && "W" !== e.get("SymbolName") && "F" !== e.get("SymbolName") && "B1" !== e.get("SymbolName") && "W1" !== e.get("SymbolName") && "W2" !== e.get("SymbolName") && "W3" !== e.get("SymbolName") && "W4" !== e.get("SymbolName") && "W5" !== e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            }))
                        })]
                    })
                },
                Xe = function(e) {
                    var t = e.lang,
                        n = e.windowDimensions,
                        a = e.payTableData,
                        i = e.gameId,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.denom,
                        o = e.betLevel,
                        r = e.moneyConvert,
                        m = e.symbol,
                        d = e.bet;
                    return Object(ee.jsxs)(Ce, {
                        children: [Object(ee.jsx)(ke, {
                            lang: t,
                            isCurrency: c,
                            currency: s,
                            bet: d,
                            denom: l
                        }), Object(ee.jsx)("p", {
                            className: "title",
                            children: J.payTable[t]
                        }), Object(ee.jsxs)(Fe, {
                            windowDimensions: n,
                            children: [a.get("math_data") && a.get("math_data").filter((function(e) {
                                return "H1" === e.get("SymbolName") || "H2" === e.get("SymbolName") || "H3" === e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            })), a.get("math_data") && a.get("math_data").filter((function(e) {
                                return "SC" === e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        children: [e, "X"]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            })), a.get("math_data") && a.get("math_data").filter((function(e) {
                                return "N1" === e.get("SymbolName") || "N2" === e.get("SymbolName") || "N3" === e.get("SymbolName") || "N4" === e.get("SymbolName") || "N5" === e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            }))]
                        })]
                    })
                },
                Ue = function(e) {
                    var t = e.lang,
                        n = e.windowDimensions,
                        a = e.payTableData,
                        i = e.gameId,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.denom,
                        o = e.betLevel,
                        r = e.moneyConvert,
                        m = e.symbol,
                        d = e.bet;
                    return Object(ee.jsxs)(Ce, {
                        children: [Object(ee.jsx)(ke, {
                            lang: t,
                            isCurrency: c,
                            currency: s,
                            bet: d,
                            denom: l
                        }), Object(ee.jsx)("p", {
                            className: "title",
                            children: J.payTable[t]
                        }), Object(ee.jsx)(Fe, {
                            windowDimensions: n,
                            children: a.get("math_data") && a.get("math_data").filter((function(e) {
                                return !e.get("SymbolPays").every((function(e) {
                                    return 0 === e
                                })) && "SC" !== e.get("SymbolName") && "W" !== e.get("SymbolName") && "F" !== e.get("SymbolName") && "W1" !== e.get("SymbolName") && "W2" !== e.get("SymbolName") && "W3" !== e.get("SymbolName") && "W4" !== e.get("SymbolName") && "W5" !== e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            }))
                        }), Object(ee.jsx)("p", {
                            children: "( ".concat({
                                cn: "2\u4e2a\u76f8\u540c\u56fe\u6807\u7684\u5f97\u5206\u5956\u52b1\uff0c\u53ea\u51fa\u73b0\u5728\u514d\u8d39\u6e38\u620f",
                                en: "2 of a kind reward only appears in free games.",
                                th: "\u0e2a\u0e31\u0e0d\u0e25\u0e31\u0e01\u0e29\u0e13\u0e4c\u0e40\u0e2b\u0e21\u0e37\u0e2d\u0e19\u0e01\u0e31\u0e19 2 \u0e15\u0e31\u0e27\u0e17\u0e35\u0e48\u0e0a\u0e19\u0e30\u0e23\u0e32\u0e07\u0e27\u0e31\u0e25\u0e08\u0e30\u0e1b\u0e23\u0e32\u0e01\u0e0e\u0e40\u0e09\u0e1e\u0e32\u0e30\u0e43\u0e19\u0e1f\u0e23\u0e35\u0e40\u0e01\u0e21\u0e40\u0e17\u0e48\u0e32\u0e19\u0e31\u0e49\u0e19",
                                id: "2 of a kind reward only appears in free games.",
                                vn: "Th\u01b0\u1edfng 2 h\xecnh gi\u1ed1ng nhau ch\u1ec9 xu\u1ea5t hi\u1ec7n trong Tr\xf2 ch\u01a1i mi\u1ec5n ph\xed",
                                ko: "\ub3d9\uc77c\ud55c2\uac1c \uc544\uc774\ucf58\uc758 \ub4dd\uc810 \ubcf4\uc0c1\uc740 \ubb34\ub8cc \uac8c\uc784\uc5d0\ub9cc \ub098\ud0c0\ub0a9\ub2c8\ub2e4",
                                es: "2 of a kind reward only appears in free games.",
                                ja: "2 of a kind reward only appears in free games.",
                                "pt-br": "Pr\xeamio de 2 imagens id\xeanticas somente aparecem em jogos gratuitos",
                                ph: "2 of a kind reward only appears in free games."
                            }[t], " )")
                        })]
                    })
                },
                qe = function(e) {
                    var t = e.lang,
                        n = e.windowDimensions,
                        a = e.payTableData,
                        i = e.gameId,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.denom,
                        o = e.betLevel,
                        r = e.moneyConvert,
                        m = e.symbol,
                        d = e.bet;
                    return Object(ee.jsxs)(Ce, {
                        children: [Object(ee.jsx)(ke, {
                            lang: t,
                            isCurrency: c,
                            currency: s,
                            bet: d,
                            denom: l
                        }), Object(ee.jsx)("p", {
                            className: "title",
                            children: J.payTable[t]
                        }), Object(ee.jsx)(Fe, {
                            windowDimensions: n,
                            children: a.get("math_data") && a.get("math_data").filter((function(e) {
                                return !e.get("SymbolPays").every((function(e) {
                                    return 0 === e
                                })) && "SC" !== e.get("SymbolName") && "W" !== e.get("SymbolName") && "F" !== e.get("SymbolName") && "W1" !== e.get("SymbolName") && "W2" !== e.get("SymbolName") && "W3" !== e.get("SymbolName") && "W4" !== e.get("SymbolName") && "W5" !== e.get("SymbolName") && "H5" !== e.get("SymbolName") && "N4" !== e.get("SymbolName") && "N5" !== e.get("SymbolName") && "H8" !== e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: "H6" === e.get("SymbolName") || "H7" === e.get("SymbolName") ? e.get("SymbolPays").reverse().map((function(e, n) {
                                            var a = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)(Ae, {
                                                    children: [J.any[t], a.size - n, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, n)
                                            }, n)
                                        })) : e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            }))
                        })]
                    })
                },
                Je = function(e) {
                    var t = e.lang,
                        n = e.windowDimensions,
                        a = e.payTableData,
                        i = e.gameId,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.denom,
                        o = e.betLevel,
                        r = e.moneyConvert,
                        m = e.symbol,
                        d = e.bet;
                    return Object(ee.jsxs)(Ce, {
                        children: [Object(ee.jsx)(ke, {
                            lang: t,
                            isCurrency: c,
                            currency: s,
                            bet: d,
                            denom: l
                        }), Object(ee.jsx)("p", {
                            className: "title",
                            children: J.payTable[t]
                        }), Object(ee.jsxs)(Fe, {
                            windowDimensions: n,
                            children: [a.get("math_data") && a.get("math_data").filter((function(e) {
                                return "H1L" === e.get("SymbolName") || "H2L" === e.get("SymbolName") || "H3L" === e.get("SymbolName") || "H4L" === e.get("SymbolName") || "H5L" === e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            })), a.get("math_data") && a.get("math_data").filter((function(e) {
                                return "N1" === e.get("SymbolName") || "N2" === e.get("SymbolName") || "N3" === e.get("SymbolName") || "N4" === e.get("SymbolName") || "N5" === e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            }))]
                        }), Object(ee.jsx)("hr", {}), Object(ee.jsx)("p", {
                            className: "title",
                            children: J.payTable[t]
                        }), Object(ee.jsxs)(Fe, {
                            windowDimensions: n,
                            children: [a.get("math_data") && a.get("math_data").filter((function(e) {
                                return "H1" === e.get("SymbolName") || "H2" === e.get("SymbolName") || "H3" === e.get("SymbolName") || "H4" === e.get("SymbolName") || "H5" === e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            })), a.get("math_data") && a.get("math_data").filter((function(e) {
                                return "N1" === e.get("SymbolName") || "N2" === e.get("SymbolName") || "N3" === e.get("SymbolName") || "N4" === e.get("SymbolName") || "N5" === e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            }))]
                        })]
                    })
                },
                Ve = function(e) {
                    var t = e.lang,
                        n = e.windowDimensions,
                        a = e.payTableData,
                        i = e.gameId,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.denom,
                        o = e.betLevel,
                        r = e.moneyConvert,
                        m = e.symbol,
                        d = e.bet;
                    return Object(ee.jsxs)(Ce, {
                        children: [Object(ee.jsx)(ke, {
                            lang: t,
                            isCurrency: c,
                            currency: s,
                            bet: d,
                            denom: l
                        }), Object(ee.jsx)("p", {
                            className: "title",
                            children: J.payTable[t]
                        }), Object(ee.jsxs)(Fe, {
                            windowDimensions: n,
                            children: [a.get("math_data") && Object(ee.jsxs)("div", {
                                className: "half",
                                children: [Object(ee.jsx)("div", {
                                    className: "pic",
                                    children: Object(ee.jsx)("img", {
                                        className: Z()("pay-img object-fit-scale", {
                                            bigSymbol: m.some((function(e) {
                                                return "H1" === e
                                            })),
                                            mobileSize: n.width < 375
                                        }),
                                        alt: "",
                                        src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/H1.png")
                                    })
                                }), Object(ee.jsx)("div", {
                                    className: "list",
                                    children: a.get("math_data").find((function(e) {
                                        return "H1" === e.get("SymbolName")
                                    })).get("SymbolPays").reverse().map((function(e, n) {
                                        var a = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                        return Object(ee.jsx)(I.Fragment, {
                                            children: 0 !== e && Object(ee.jsxs)(Ae, {
                                                children: [2 !== n && J.any[t], a.size - n, " -", Object(ee.jsxs)("span", {
                                                    className: Z()({
                                                        money: c
                                                    }),
                                                    children: [c && Object(ee.jsx)("div", {
                                                        className: "symbol",
                                                        dangerouslySetInnerHTML: M(G[s].symbol)
                                                    }), c ? r(e * l * o) : e * o]
                                                })]
                                            }, n)
                                        }, n)
                                    }))
                                })]
                            }), a.get("math_data") && a.get("math_data").filter((function(e) {
                                return !e.get("SymbolPays").every((function(e) {
                                    return 0 === e
                                })) && "SC" !== e.get("SymbolName") && "W" !== e.get("SymbolName") && "F" !== e.get("SymbolName") && "W1" !== e.get("SymbolName") && "W2" !== e.get("SymbolName") && "W3" !== e.get("SymbolName") && "W4" !== e.get("SymbolName") && "W5" !== e.get("SymbolName") && "H1" !== e.get("SymbolName") && "N4" !== e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            })), a.get("math_data") && Object(ee.jsxs)("div", {
                                className: "half",
                                children: [Object(ee.jsx)("div", {
                                    className: "pic",
                                    children: Object(ee.jsx)("img", {
                                        className: Z()("pay-img object-fit-scale", {
                                            bigSymbol: m.some((function(e) {
                                                return "N4" === e
                                            })),
                                            mobileSize: n.width < 375
                                        }),
                                        alt: "",
                                        src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/N4.png")
                                    })
                                }), Object(ee.jsx)("div", {
                                    className: "list",
                                    children: a.get("math_data").find((function(e) {
                                        return "N4" === e.get("SymbolName")
                                    })).get("SymbolPays").reverse().map((function(e, n) {
                                        var a = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                        return Object(ee.jsx)(I.Fragment, {
                                            children: 0 !== e && Object(ee.jsxs)(Ae, {
                                                children: [J.any[t], a.size - n, " -", Object(ee.jsxs)("span", {
                                                    className: Z()({
                                                        money: c
                                                    }),
                                                    children: [c && Object(ee.jsx)("div", {
                                                        className: "symbol",
                                                        dangerouslySetInnerHTML: M(G[s].symbol)
                                                    }), c ? r(e * l * o) : e * o]
                                                })]
                                            }, n)
                                        }, n)
                                    }))
                                })]
                            })]
                        })]
                    })
                },
                Ke = function(e) {
                    var t = e.lang,
                        n = e.windowDimensions,
                        a = e.payTableData,
                        i = e.gameId,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.denom,
                        o = e.betLevel,
                        r = e.moneyConvert,
                        m = e.symbol,
                        d = e.bet;
                    return Object(ee.jsxs)(Ce, {
                        children: [Object(ee.jsx)(ke, {
                            lang: t,
                            isCurrency: c,
                            currency: s,
                            bet: d,
                            denom: l
                        }), Object(ee.jsx)("p", {
                            className: "title",
                            children: J.payTable[t]
                        }), Object(ee.jsx)(Fe, {
                            windowDimensions: n,
                            children: a.get("math_data") && a.get("math_data").filter((function(e) {
                                return !e.get("SymbolPays").every((function(e) {
                                    return 0 === e
                                })) && "SC" !== e.get("SymbolName") && "W" !== e.get("SymbolName") && "F" !== e.get("SymbolName") && "W1" !== e.get("SymbolName") && "W2" !== e.get("SymbolName") && "W3" !== e.get("SymbolName") && "W4" !== e.get("SymbolName") && "W5" !== e.get("SymbolName") && "H1_2" !== e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            }))
                        })]
                    })
                },
                Qe = k.b.div(f || (f = Object(T.a)(["\n  width: 100%;\n  display: flex;\n  flex-direction: ", ";\n  text-align: left;\n  flex-wrap: wrap;\n  justify-content: space-between;\n  & .row-pair {\n    width: 100%;\n    display: flex;\n    flex-direction: row;\n  }\n  & .half {\n    width: 100%;\n    display: flex;\n    flex-direction: column;\n    align-items: center;\n    margin-bottom: -5px;\n    border: 1px solid #3a2c25;\n    box-sizing: border-box;\n    margin-bottom: 15px;\n\n    @media (min-width: 700px) {\n      width: 48%;\n    }\n    @media (min-width: 1024px) {\n      width: 33%;\n    }\n    & .pic {\n      width: 40%;\n      padding: 5px 0;\n      text-align: right;\n      .pay-img {\n        width: 112px;\n        height: 112px;\n        image-rendering: -webkit-optimize-contrast;\n      }\n    }\n    & .list {\n      flex: 1;\n      display: flex;\n      flex-direction: column;\n      justify-content: center;\n      font-size: 24px;\n      margin-left: 10px;\n      & > div {\n        margin: 3px 0;\n        white-space: nowrap;\n      }\n      & span {\n        font-size: 24px;\n        color: #ffd542;\n        margin-left: 5px;\n      }\n    }\n  }\n"])), (function(e) {
                    return e.windowDimensions.width >= V ? "row" : "column"
                })),
                Ze = function(e) {
                    var t, n = e.lang,
                        a = e.windowDimensions,
                        i = e.payTableData,
                        c = e.gameId,
                        s = e.isCurrency,
                        l = e.currency,
                        o = e.denom,
                        r = e.betLevel,
                        m = e.moneyConvert,
                        d = e.symbol,
                        b = e.bet,
                        g = new RegExp("H\\d$", "g"),
                        j = null === (t = i.get("math_data")) || void 0 === t ? void 0 : t.filter((function(e) {
                            return e.get("SymbolName").match(g)
                        }));
                    return Object(ee.jsxs)(Ce, {
                        children: [Object(ee.jsx)(ke, {
                            lang: n,
                            isCurrency: s,
                            currency: l,
                            bet: b,
                            denom: o
                        }), Object(ee.jsx)("p", {
                            className: "title",
                            children: J.payTable[n]
                        }), Object(ee.jsxs)(Qe, {
                            windowDimensions: a,
                            children: [i.get("math_data") && j.map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [i.get("math_data").some((function(t) {
                                        return t.get("SymbolName") === "".concat(e.get("SymbolName"), "L")
                                    })) && Object(ee.jsxs)("div", {
                                        className: "row-pair",
                                        children: [Object(ee.jsx)("div", {
                                            className: "pic",
                                            children: Object(ee.jsx)("img", {
                                                className: Z()("pay-img object-fit-scale", {
                                                    bigSymbol: d.some((function(t) {
                                                        return t === "".concat(e.get("SymbolName"), "L")
                                                    })),
                                                    mobileSize: a.width < 375
                                                }),
                                                alt: "",
                                                src: "".concat(W, "/order-detail/common/").concat(c, "/symbolList/").concat(e.get("SymbolName"), "L.png")
                                            })
                                        }), Object(ee.jsx)("div", {
                                            className: "list",
                                            children: i.get("math_data") && i.get("math_data").filter((function(t) {
                                                return t.get("SymbolName") === "".concat(e.get("SymbolName"), "L")
                                            })).getIn([0, "SymbolPays"]).reverse().map((function(e, t) {
                                                var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                                return Object(ee.jsx)(I.Fragment, {
                                                    children: 0 !== e && Object(ee.jsxs)("div", {
                                                        children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                            className: Z()({
                                                                money: s
                                                            }),
                                                            children: [s && Object(ee.jsx)("div", {
                                                                className: "symbol",
                                                                dangerouslySetInnerHTML: M(G[l].symbol)
                                                            }), s ? m(e * o * r) : e * r]
                                                        })]
                                                    }, t)
                                                }, t)
                                            }))
                                        })]
                                    }), Object(ee.jsxs)("div", {
                                        className: "row-pair",
                                        children: [Object(ee.jsx)("div", {
                                            className: "pic",
                                            children: Object(ee.jsx)("img", {
                                                className: Z()("pay-img object-fit-scale", {
                                                    bigSymbol: d.some((function(t) {
                                                        return t === e.get("SymbolName")
                                                    })),
                                                    mobileSize: a.width < 375
                                                }),
                                                alt: "",
                                                src: "".concat(W, "/order-detail/common/").concat(c, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                            })
                                        }), Object(ee.jsx)("div", {
                                            className: "list",
                                            children: e.get("SymbolPays").reverse().map((function(e, t) {
                                                var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                                return Object(ee.jsx)(I.Fragment, {
                                                    children: 0 !== e && Object(ee.jsxs)("div", {
                                                        children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                            className: Z()({
                                                                money: s
                                                            }),
                                                            children: [s && Object(ee.jsx)("div", {
                                                                className: "symbol",
                                                                dangerouslySetInnerHTML: M(G[l].symbol)
                                                            }), s ? m(e * o * r) : e * r]
                                                        })]
                                                    }, t)
                                                }, t)
                                            }))
                                        })]
                                    })]
                                }, e.get("SymbolID"))
                            })), Object(ee.jsx)("div", {
                                className: "half",
                                children: Object(ee.jsxs)("div", {
                                    className: "row-pair",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                mobileSize: a.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(c, "/symbolList/AKQJ109.png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: i.get("math_data") && i.get("math_data").filter((function(e) {
                                            return "N1" === e.get("SymbolName")
                                        })).getIn([0, "SymbolPays"]).reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: s
                                                        }),
                                                        children: [s && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[l].symbol)
                                                        }), s ? m(e * o * r) : e * r]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                })
                            })]
                        })]
                    })
                },
                et = k.b.div(O || (O = Object(T.a)(["\n  width: 100%;\n  display: flex;\n  flex-direction: ", ";\n  text-align: left;\n  flex-wrap: wrap;\n  & .outline {\n    width: 100%;\n    display: flex;\n    flex-direction: ", ";\n    text-align: left;\n    flex-wrap: wrap;\n    border: solid 1px #3a2c25;\n    & p {\n      width: 100%;\n      text-align: center;\n      margin: 0 0 20px 0;\n      line-height: 2;\n    }\n    & .half {\n      @media (min-width: 700px) {\n        width: 50%;\n      }\n      @media (min-width: 1024px) {\n        width: 50%;\n      }\n    }\n  }\n  & .half {\n    width: 100%;\n    display: flex;\n    flex-direction: row;\n    align-items: center;\n    margin-bottom: -5px;\n    @media (min-width: 700px) {\n      width: 50%;\n    }\n    @media (min-width: 1024px) {\n      width: 33.3%;\n    }\n    & .pic {\n      width: 40%;\n      padding: 20px 0;\n      text-align: right;\n      .pay-img {\n        width: 112px;\n        height: 112px;\n        image-rendering: -webkit-optimize-contrast;\n        &.h-pic {\n          width: 112px;\n          height: 200px;\n        }\n      }\n    }\n    & .list {\n      flex: 1;\n      display: flex;\n      flex-direction: column;\n      justify-content: center;\n      font-size: 24px;\n      margin-left: 10px;\n      & > div {\n        margin: 3px 0;\n        white-space: nowrap;\n      }\n      & span {\n        font-size: 24px;\n        color: #ffd542;\n        margin-left: 5px;\n      }\n    }\n  }\n"])), (function(e) {
                    return e.windowDimensions.width >= V ? "row" : "column"
                }), (function(e) {
                    return e.windowDimensions.width >= V ? "row" : "column"
                })),
                tt = function(e) {
                    var t = e.lang,
                        n = e.windowDimensions,
                        a = e.payTableData,
                        i = e.gameId,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.denom,
                        o = e.betLevel,
                        r = e.moneyConvert,
                        m = e.symbol,
                        d = e.bet;
                    return Object(ee.jsxs)(Ce, {
                        children: [Object(ee.jsx)(ke, {
                            lang: t,
                            isCurrency: c,
                            currency: s,
                            bet: d,
                            denom: l
                        }), Object(ee.jsx)("p", {
                            className: "title",
                            children: J.payTable[t]
                        }), Object(ee.jsxs)(et, {
                            windowDimensions: n,
                            children: [Object(ee.jsxs)("div", {
                                className: "outline",
                                children: [a.get("math_data") && a.get("math_data").filter((function(e) {
                                    return "H1" === e.get("SymbolName") || "H2" === e.get("SymbolName") || "H3" === e.get("SymbolName") || "H4" === e.get("SymbolName")
                                })).map((function(e) {
                                    return Object(ee.jsxs)("div", {
                                        className: "half",
                                        children: [Object(ee.jsx)("div", {
                                            className: "pic",
                                            children: Object(ee.jsx)("img", {
                                                className: Z()("pay-img object-fit-scale h-pic", {
                                                    bigSymbol: m.some((function(t) {
                                                        return t === e.get("SymbolName")
                                                    })),
                                                    mobileSize: n.width < 375
                                                }),
                                                alt: "",
                                                src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                            })
                                        }), Object(ee.jsx)("div", {
                                            className: "list",
                                            children: e.get("SymbolPays").reverse().map((function(e, t) {
                                                var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                                return Object(ee.jsx)(I.Fragment, {
                                                    children: 0 !== e && Object(ee.jsxs)("div", {
                                                        children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                            className: Z()({
                                                                money: c
                                                            }),
                                                            children: [c && Object(ee.jsx)("div", {
                                                                className: "symbol",
                                                                dangerouslySetInnerHTML: M(G[s].symbol)
                                                            }), c ? r(e * l * o) : e * o]
                                                        })]
                                                    }, t)
                                                }, t)
                                            }))
                                        })]
                                    }, e.get("SymbolID"))
                                })), Object(ee.jsx)("p", {
                                    children: "( ".concat({
                                        cn: "2\u4e2a\u76f8\u540c\u56fe\u6807\u7684\u5f97\u5206\u5956\u52b1\uff0c\u53ea\u51fa\u73b0\u5728\u514d\u8d39\u6e38\u620f",
                                        en: "2 of a kind reward only appears in free games.",
                                        th: "\u0e2a\u0e31\u0e0d\u0e25\u0e31\u0e01\u0e29\u0e13\u0e4c\u0e40\u0e2b\u0e21\u0e37\u0e2d\u0e19\u0e01\u0e31\u0e19 2 \u0e15\u0e31\u0e27\u0e17\u0e35\u0e48\u0e0a\u0e19\u0e30\u0e23\u0e32\u0e07\u0e27\u0e31\u0e25\u0e08\u0e30\u0e1b\u0e23\u0e32\u0e01\u0e0e\u0e40\u0e09\u0e1e\u0e32\u0e30\u0e43\u0e19\u0e1f\u0e23\u0e35\u0e40\u0e01\u0e21\u0e40\u0e17\u0e48\u0e32\u0e19\u0e31\u0e49\u0e19",
                                        id: "2 of a kind reward only appears in free games.",
                                        vn: "Th\u01b0\u1edfng 2 h\xecnh gi\u1ed1ng nhau ch\u1ec9 xu\u1ea5t hi\u1ec7n trong Tr\xf2 ch\u01a1i mi\u1ec5n ph\xed",
                                        ko: "\ub3d9\uc77c\ud55c2\uac1c \uc544\uc774\ucf58\uc758 \ub4dd\uc810 \ubcf4\uc0c1\uc740 \ubb34\ub8cc \uac8c\uc784\uc5d0\ub9cc \ub098\ud0c0\ub0a9\ub2c8\ub2e4",
                                        es: "2 of a kind reward only appears in free games.",
                                        ja: "2 of a kind reward only appears in free games.",
                                        "pt-br": "Pr\xeamio de 2 imagens id\xeanticas somente aparecem em jogos gratuitos",
                                        ph: "2 of a kind reward only appears in free games."
                                    }[t], " )")
                                })]
                            }), a.get("math_data") && a.get("math_data").filter((function(e) {
                                return "N1" === e.get("SymbolName") || "N2" === e.get("SymbolName") || "N3" === e.get("SymbolName") || "N4" === e.get("SymbolName") || "N5" === e.get("SymbolName") || "N6" === e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            }))]
                        })]
                    })
                },
                nt = k.b.div(v || (v = Object(T.a)(["\n  width: 100%;\n  display: flex;\n  flex-direction: ", ";\n  text-align: left;\n  flex-wrap: wrap;\n  justify-content: space-between;\n  & .row-pair {\n    width: 100%;\n    display: flex;\n    flex-direction: row;\n  }\n  & .half {\n    width: 100%;\n    display: flex;\n    flex-direction: column;\n    align-items: center;\n    margin-bottom: -5px;\n    border: 1px solid #3a2c25;\n    box-sizing: border-box;\n    margin-bottom: 15px;\n\n    @media (min-width: 700px) {\n      width: 48%;\n    }\n    @media (min-width: 1024px) {\n      width: 33%;\n    }\n    & .pic {\n      width: 40%;\n      padding: 5px 0;\n      text-align: right;\n      .pay-img {\n        width: 112px;\n        height: 112px;\n        image-rendering: -webkit-optimize-contrast;\n      }\n    }\n    & .list {\n      flex: 1;\n      display: flex;\n      flex-direction: column;\n      justify-content: center;\n      font-size: 24px;\n      margin-left: 10px;\n      & > div {\n        margin: 3px 0;\n        white-space: nowrap;\n      }\n      & span {\n        font-size: 24px;\n        color: #ffd542;\n        margin-left: 5px;\n      }\n    }\n  }\n"])), (function(e) {
                    return e.windowDimensions.width >= V ? "row" : "column"
                })),
                at = function(e) {
                    var t, n = e.lang,
                        a = e.windowDimensions,
                        i = e.payTableData,
                        c = e.gameId,
                        s = e.isCurrency,
                        l = e.currency,
                        o = e.denom,
                        r = e.betLevel,
                        m = e.moneyConvert,
                        d = e.symbol,
                        b = e.bet,
                        g = new RegExp("H\\d$", "g"),
                        j = null === (t = i.get("math_data")) || void 0 === t ? void 0 : t.filter((function(e) {
                            return e.get("SymbolName").match(g)
                        }));
                    return Object(ee.jsxs)(Ce, {
                        children: [Object(ee.jsx)(ke, {
                            lang: n,
                            isCurrency: s,
                            currency: l,
                            bet: b,
                            denom: o
                        }), Object(ee.jsx)("p", {
                            className: "title",
                            children: J.payTable[n]
                        }), Object(ee.jsxs)(nt, {
                            windowDimensions: a,
                            children: [i.get("math_data") && j.map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [i.get("math_data").some((function(t) {
                                        return t.get("SymbolName") === "".concat(e.get("SymbolName"), "L")
                                    })) && Object(ee.jsxs)("div", {
                                        className: "row-pair",
                                        children: [Object(ee.jsx)("div", {
                                            className: "pic",
                                            children: Object(ee.jsx)("img", {
                                                className: Z()("pay-img object-fit-scale", {
                                                    bigSymbol: d.some((function(t) {
                                                        return t === "".concat(e.get("SymbolName"), "L")
                                                    })),
                                                    mobileSize: a.width < 375
                                                }),
                                                alt: "",
                                                src: "".concat(W, "/order-detail/common/").concat(c, "/symbolList/").concat(e.get("SymbolName"), "L.png")
                                            })
                                        }), Object(ee.jsx)("div", {
                                            className: "list",
                                            children: i.get("math_data") && i.get("math_data").filter((function(t) {
                                                return t.get("SymbolName") === "".concat(e.get("SymbolName"), "L")
                                            })).getIn([0, "SymbolPays"]).reverse().map((function(e, t) {
                                                var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                                return Object(ee.jsx)(I.Fragment, {
                                                    children: 0 !== e && Object(ee.jsxs)("div", {
                                                        children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                            className: Z()({
                                                                money: s
                                                            }),
                                                            children: [s && Object(ee.jsx)("div", {
                                                                className: "symbol",
                                                                dangerouslySetInnerHTML: M(G[l].symbol)
                                                            }), s ? m(e * o * r) : e * r]
                                                        })]
                                                    }, t)
                                                }, t)
                                            }))
                                        })]
                                    }), Object(ee.jsxs)("div", {
                                        className: "row-pair",
                                        children: [Object(ee.jsx)("div", {
                                            className: "pic",
                                            children: Object(ee.jsx)("img", {
                                                className: Z()("pay-img object-fit-scale", {
                                                    bigSymbol: d.some((function(t) {
                                                        return t === e.get("SymbolName")
                                                    })),
                                                    mobileSize: a.width < 375
                                                }),
                                                alt: "",
                                                src: "".concat(W, "/order-detail/common/").concat(c, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                            })
                                        }), Object(ee.jsx)("div", {
                                            className: "list",
                                            children: e.get("SymbolPays").reverse().map((function(e, t) {
                                                var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                                return Object(ee.jsx)(I.Fragment, {
                                                    children: 0 !== e && Object(ee.jsxs)("div", {
                                                        children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                            className: Z()({
                                                                money: s
                                                            }),
                                                            children: [s && Object(ee.jsx)("div", {
                                                                className: "symbol",
                                                                dangerouslySetInnerHTML: M(G[l].symbol)
                                                            }), s ? m(e * o * r) : e * r]
                                                        })]
                                                    }, t)
                                                }, t)
                                            }))
                                        })]
                                    })]
                                }, e.get("SymbolID"))
                            })), Object(ee.jsx)("div", {
                                className: "half",
                                children: Object(ee.jsxs)("div", {
                                    className: "row-pair",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                mobileSize: a.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(c, "/symbolList/N.png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: i.get("math_data") && i.get("math_data").filter((function(e) {
                                            return "N1" === e.get("SymbolName")
                                        })).getIn([0, "SymbolPays"]).reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: s
                                                        }),
                                                        children: [s && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[l].symbol)
                                                        }), s ? m(e * o * r) : e * r]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                })
                            })]
                        })]
                    })
                },
                it = function(e) {
                    var t = e.lang,
                        n = e.windowDimensions,
                        a = e.payTableData,
                        i = e.gameId,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.denom,
                        o = e.betLevel,
                        r = e.moneyConvert,
                        m = e.symbol,
                        d = e.bet,
                        b = "";
                    switch (t) {
                        case "cn":
                            b = "cn";
                            break;
                        case "en":
                            b = "en";
                            break;
                        case "ko":
                            b = "kr";
                            break;
                        default:
                            b = "en"
                    }
                    return Object(ee.jsxs)(Ce, {
                        children: [Object(ee.jsx)(ke, {
                            lang: t,
                            isCurrency: c,
                            currency: s,
                            bet: d,
                            denom: l
                        }), Object(ee.jsx)("p", {
                            className: "title",
                            children: J.payTable[t]
                        }), Object(ee.jsxs)(Fe, {
                            windowDimensions: n,
                            children: [a.get("math_data") && a.get("math_data").filter((function(e) {
                                return !e.get("SymbolPays").every((function(e) {
                                    return 0 === e
                                })) && ("H1" === e.get("SymbolName") || "H2" === e.get("SymbolName") || "H3" === e.get("SymbolName"))
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            })), a.get("math_data") && a.get("math_data").filter((function(e) {
                                return "H4" === e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), "_").concat(b, ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            })), a.get("math_data") && a.get("math_data").filter((function(e) {
                                return !e.get("SymbolPays").every((function(e) {
                                    return 0 === e
                                })) && ("N1" === e.get("SymbolName") || "N2" === e.get("SymbolName"))
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            })), a.get("math_data") && a.get("math_data").filter((function(e) {
                                return "N3" === e.get("SymbolName") || "N4" === e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), "_").concat(b, ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            }))]
                        })]
                    })
                },
                ct = function(e) {
                    var t = e.lang,
                        n = e.windowDimensions,
                        a = e.payTableData,
                        i = e.gameId,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.denom,
                        o = e.betLevel,
                        r = e.moneyConvert,
                        m = e.symbol,
                        d = e.bet,
                        b = e.isLongList;
                    return Object(ee.jsxs)(Ce, {
                        children: [Object(ee.jsx)(ke, {
                            lang: t,
                            isCurrency: c,
                            currency: s,
                            bet: d,
                            denom: l
                        }), Object(ee.jsx)("p", {
                            className: "title",
                            children: J.payTable[t]
                        }), Object(ee.jsx)(Fe, {
                            windowDimensions: n,
                            isLongList: b,
                            children: a.get("math_data") && a.get("math_data").filter((function(e) {
                                return !e.get("SymbolPays").every((function(e) {
                                    return 0 === e
                                })) && "SC" !== e.get("SymbolName") && "W" !== e.get("SymbolName") && "F" !== e.get("SymbolName") && "W1" !== e.get("SymbolName") && "W2" !== e.get("SymbolName") && "W3" !== e.get("SymbolName") && "W4" !== e.get("SymbolName") && "W5" !== e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsxs)("div", {
                                        className: "list",
                                        children: [Object(ee.jsxs)("div", {
                                            children: ["5 -", Object(ee.jsx)("span", {
                                                children: "\u4f9d\u5716\u6a19\u500d\u6578"
                                            })]
                                        }), e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))]
                                    })]
                                }, e.get("SymbolID"))
                            }))
                        })]
                    })
                },
                st = function(e) {
                    var t = e.lang,
                        n = e.windowDimensions,
                        a = e.payTableData,
                        i = e.gameId,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.denom,
                        o = e.betLevel,
                        r = e.moneyConvert,
                        m = e.symbol,
                        d = e.bet,
                        b = e.isLongList;
                    return Object(ee.jsxs)(Ce, {
                        children: [Object(ee.jsx)(ke, {
                            lang: t,
                            isCurrency: c,
                            currency: s,
                            bet: d,
                            denom: l
                        }), Object(ee.jsx)("p", {
                            className: "title",
                            children: J.payTable[t]
                        }), Object(ee.jsx)(Fe, {
                            windowDimensions: n,
                            isLongList: b,
                            children: a.get("math_data") && a.get("math_data").filter((function(e) {
                                return !e.get("SymbolPays").every((function(e) {
                                    return 0 === e
                                })) && "SC" !== e.get("SymbolName") && "W" !== e.get("SymbolName") && "F" !== e.get("SymbolName") && "W1" !== e.get("SymbolName") && "W2" !== e.get("SymbolName") && "W3" !== e.get("SymbolName") && "W4" !== e.get("SymbolName") && "W5" !== e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: e.get("SymbolPays").splice(16).reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, 0 === t && "\u4ee5\u4e0a", " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            }))
                        })]
                    })
                },
                lt = function(e) {
                    var t = e.lang,
                        n = e.windowDimensions,
                        a = e.payTableData,
                        i = e.gameId,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.denom,
                        o = e.betLevel,
                        r = e.moneyConvert,
                        m = e.bet,
                        d = Object(C.a)(["H1", "H2", "H3", "H4", "H5"]);
                    return Object(ee.jsxs)(Ce, {
                        children: [Object(ee.jsx)(ke, {
                            lang: t,
                            isCurrency: c,
                            currency: s,
                            bet: m,
                            denom: l
                        }), Object(ee.jsx)("p", {
                            className: "title",
                            children: J.payTable[t]
                        }), Object(ee.jsx)(Fe, {
                            windowDimensions: n,
                            children: a.get("math_data") && d.map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e, ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: a.get("math_data").filter((function(t) {
                                            return t.get("SymbolName").includes(e)
                                        })).reverse().map((function(e, t) {
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e.getIn(["SymbolPays", 0]) && Object(ee.jsxs)("div", {
                                                    children: ["-", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e.getIn(["SymbolPays", 0]) * l * o) : e.getIn(["SymbolPays", 0]) * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e)
                            }))
                        })]
                    })
                },
                ot = function(e) {
                    var t = e.lang,
                        n = e.windowDimensions,
                        a = e.payTableData,
                        i = e.gameId,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.denom,
                        o = e.betLevel,
                        r = e.moneyConvert,
                        m = e.symbol,
                        d = e.bet;
                    return Object(ee.jsxs)(Ce, {
                        children: [Object(ee.jsx)(ke, {
                            lang: t,
                            isCurrency: c,
                            currency: s,
                            bet: d,
                            denom: l
                        }), Object(ee.jsx)("p", {
                            className: "title",
                            children: J.payTable[t]
                        }), Object(ee.jsx)(Fe, {
                            windowDimensions: n,
                            children: a.get("math_data") && a.get("math_data").filter((function(e) {
                                return !e.get("SymbolPays").every((function(e) {
                                    return 0 === e
                                })) && "SC" !== e.get("SymbolName") && "W" !== e.get("SymbolName") && "F" !== e.get("SymbolName") && "B1" !== e.get("SymbolName") && "W1" !== e.get("SymbolName") && "W2" !== e.get("SymbolName") && "W3" !== e.get("SymbolName") && "W4" !== e.get("SymbolName") && "W5" !== e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            }))
                        })]
                    })
                },
                rt = k.b.span(N || (N = Object(T.a)(["\n  font-size: 20px !important;\n  &.en {\n    font-size: 14px !important;\n  }\n"]))),
                mt = function(e) {
                    var t = e.lang,
                        n = e.windowDimensions,
                        a = e.payTableData,
                        i = e.gameId,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.denom,
                        o = e.betLevel,
                        r = e.moneyConvert,
                        m = e.symbol,
                        d = e.bet,
                        b = e.isLongList,
                        g = {
                            tw: "\u7e3d\u986f\u793a\u5206\u6578",
                            cn: "\u603b\u663e\u793a\u5206\u6570",
                            en: "TOTAL VALUE AMOUNT",
                            ko: "\ucd1d \uc2ec\ubc8c \uc810\uc218",
                            th: "\u0e41\u0e2a\u0e14\u0e07\u0e04\u0e30\u0e41\u0e19\u0e19\u0e23\u0e27\u0e21"
                        },
                        j = function() {
                            var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : "",
                                t = "zh-tw" === e,
                                n = "cn" === e,
                                a = "ko" === e,
                                i = "th" === e;
                            return t || n || a || i ? e : "en"
                        };
                    return Object(ee.jsxs)(Ce, {
                        children: [Object(ee.jsx)(ke, {
                            lang: t,
                            isCurrency: c,
                            currency: s,
                            bet: d,
                            denom: l
                        }), Object(ee.jsx)("p", {
                            className: "title",
                            children: J.payTable[t]
                        }), Object(ee.jsx)(Fe, {
                            windowDimensions: n,
                            isLongList: b,
                            children: a.get("math_data") && a.get("math_data").filter((function(e) {
                                return !e.get("SymbolPays").every((function(e) {
                                    return 0 === e
                                })) && "SC" !== e.get("SymbolName") && "W" !== e.get("SymbolName") && "F" !== e.get("SymbolName") && "W1" !== e.get("SymbolName") && "W2" !== e.get("SymbolName") && "W3" !== e.get("SymbolName") && "W4" !== e.get("SymbolName") && "W5" !== e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsxs)("div", {
                                        className: "list",
                                        children: ["H5" === e.get("SymbolName") && Object(ee.jsxs)("div", {
                                            children: ["5 -", Object(ee.jsx)(rt, {
                                                className: Z()({
                                                    en: "en" === j(t)
                                                }),
                                                children: g[j(t)]
                                            })]
                                        }), e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))]
                                    })]
                                }, e.get("SymbolID"))
                            }))
                        })]
                    })
                },
                dt = function(e) {
                    var t = e.lang,
                        n = e.windowDimensions,
                        a = e.payTableData,
                        i = e.gameId,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.denom,
                        o = e.betLevel,
                        r = e.moneyConvert,
                        m = e.symbol,
                        d = e.bet;
                    return Object(ee.jsxs)(Ce, {
                        children: [Object(ee.jsx)(ke, {
                            lang: t,
                            isCurrency: c,
                            currency: s,
                            bet: d,
                            denom: l
                        }), Object(ee.jsx)("p", {
                            className: "title",
                            children: J.payTable[t]
                        }), Object(ee.jsx)(Fe, {
                            windowDimensions: n,
                            children: a.get("math_data") && a.get("math_data").filter((function(e) {
                                return !e.get("SymbolPays").every((function(e) {
                                    return 0 === e
                                })) && "SC" !== e.get("SymbolName") && "W" !== e.get("SymbolName") && "F" !== e.get("SymbolName") && "B1" !== e.get("SymbolName") && "W1" !== e.get("SymbolName") && "W2" !== e.get("SymbolName") && "W3" !== e.get("SymbolName") && "W4" !== e.get("SymbolName") && "W5" !== e.get("SymbolName")
                            })).map((function(e) {
                                return Object(ee.jsxs)("div", {
                                    className: "half",
                                    children: [Object(ee.jsx)("div", {
                                        className: "pic",
                                        children: Object(ee.jsx)("img", {
                                            className: Z()("pay-img object-fit-scale", {
                                                bigSymbol: m.some((function(t) {
                                                    return t === e.get("SymbolName")
                                                })),
                                                mobileSize: n.width < 375
                                            }),
                                            alt: "",
                                            src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                        })
                                    }), Object(ee.jsx)("div", {
                                        className: "list",
                                        children: e.get("SymbolPays").reverse().map((function(e, t) {
                                            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                            return Object(ee.jsx)(I.Fragment, {
                                                children: 0 !== e && Object(ee.jsxs)("div", {
                                                    children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                        className: Z()({
                                                            money: c
                                                        }),
                                                        children: [c && Object(ee.jsx)("div", {
                                                            className: "symbol",
                                                            dangerouslySetInnerHTML: M(G[s].symbol)
                                                        }), c ? r(e * l * o) : e * o]
                                                    })]
                                                }, t)
                                            }, t)
                                        }))
                                    })]
                                }, e.get("SymbolID"))
                            }))
                        })]
                    })
                },
                bt = n(64),
                gt = k.b.div(S || (S = Object(T.a)(["\n  width: 100%;\n  display: flex;\n  flex-direction: column;\n  border: 2px solid #422e1e;\n  box-sizing: border-box;\n  margin-bottom: 20px;\n  & .level-title {\n    background-color: #422e1e;\n    padding: 10px 0;\n    font-weight: bold;\n    font-size: 20px;\n  }\n"]))),
                jt = k.b.div(w || (w = Object(T.a)(["\n  padding: 10px;\n  width: 100%;\n  display: flex;\n  flex-direction: ", ";\n  text-align: left;\n  flex-wrap: wrap;\n  & .half {\n    width: 100%;\n    display: flex;\n    flex-direction: row;\n    align-items: center;\n    margin-bottom: -5px;\n    @media (min-width: 700px) {\n      width: 50%;\n    }\n    @media (min-width: 1024px) {\n      width: 33.3%;\n    }\n    & .pic {\n      width: 35%;\n      padding: 20px 0;\n      text-align: right;\n      .pay-img {\n        width: 112px;\n        height: 112px;\n        image-rendering: -webkit-optimize-contrast;\n      }\n    }\n    & .list {\n      flex: 1;\n      display: flex;\n      flex-direction: column;\n      justify-content: center;\n      font-size: 24px;\n      margin-left: 10px;\n      & > div {\n        margin: 3px 0;\n        white-space: nowrap;\n      }\n      & span {\n        font-size: 24px;\n        color: #ffd542;\n        margin-left: 5px;\n      }\n    }\n  }\n"])), (function(e) {
                    return e.windowDimensions.width >= V ? "row" : "column"
                })),
                yt = function(e) {
                    var t = e.lang,
                        n = e.windowDimensions,
                        a = e.payTableData,
                        i = e.gameId,
                        c = e.isCurrency,
                        s = e.currency,
                        l = e.denom,
                        o = e.betLevel,
                        r = e.moneyConvert,
                        m = e.symbol,
                        d = e.bet,
                        b = Object(I.useState)(1),
                        g = Object(P.a)(b, 2),
                        j = g[0],
                        y = g[1],
                        h = Object(I.useState)([]),
                        u = Object(P.a)(h, 2),
                        x = u[0],
                        p = u[1];
                    Object(I.useEffect)((function() {
                        if (a.get("math_data")) {
                            var e = a.get("math_data").filter((function(e) {
                                return e.get("SymbolName").includes("H1_")
                            }));
                            y(e.size)
                        }
                    }), [a]), Object(I.useEffect)((function() {
                        if (a.get("math_data")) {
                            var e = a.get("math_data").filter((function(e) {
                                return e.get("SymbolName").includes("_1")
                            })).map((function(e) {
                                return e.get("SymbolName").slice(0, -1)
                            }));
                            p(e)
                        }
                    }), [a]);
                    var f = function(e, t) {
                        switch (e) {
                            case "cn":
                                return "\u7b2c".concat(function(e) {
                                    switch (e) {
                                        case 1:
                                            return "\u4e00";
                                        case 2:
                                            return "\u4e8c";
                                        case 3:
                                            return "\u4e09";
                                        case 4:
                                            return "\u56db";
                                        case 5:
                                            return "\u4e94"
                                    }
                                }(t), "\u5173\u8d54\u7387\u8868");
                            case "en":
                                return "Level ".concat(t);
                            case "ko":
                                return "\uc81c ".concat(t, "\ub808\ubca8");
                            case "th":
                                return "\u0e14\u0e48\u0e32\u0e19\u0e17\u0e35\u0e48".concat(t);
                            case "vn":
                                return "C\u1eeda \u1ea3i s\u1ed1 ".concat(t);
                            case "id":
                                return "Level ".concat(t);
                            case "ja":
                                return "\u30b9\u30c6\u30fc\u30b8".concat(t);
                            case "es":
                                return "Nivel".concat(t);
                            case "pt-br":
                                return "n\xedvel ".concat(t);
                            default:
                                return "Level ".concat(t)
                        }
                    };
                    return Object(ee.jsxs)(ee.Fragment, {
                        children: [Object(ee.jsx)(ke, {
                            lang: t,
                            isCurrency: c,
                            currency: s,
                            bet: d,
                            denom: l
                        }), Object(ee.jsx)("p", {
                            className: "title",
                            children: J.payTable[t]
                        }), Array.from({
                            length: j
                        }, (function(e, t) {
                            return t
                        })).map((function(e, d) {
                            return Object(ee.jsx)(I.Fragment, {
                                children: Object(ee.jsxs)(gt, {
                                    children: [Object(ee.jsx)("p", {
                                        className: "level-title",
                                        children: f(t, d + 1)
                                    }), Object(ee.jsxs)(jt, {
                                        windowDimensions: n,
                                        children: [x.map((function(t, b) {
                                            return Object(ee.jsxs)("div", {
                                                className: "half",
                                                children: [Object(ee.jsx)("div", {
                                                    className: "pic",
                                                    children: Object(ee.jsx)("img", {
                                                        className: Z()("pay-img object-fit-scale", {
                                                            bigSymbol: m.some((function(t) {
                                                                return t === e.get("SymbolName")
                                                            })),
                                                            mobileSize: n.width < 375
                                                        }),
                                                        alt: "",
                                                        src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/", "".concat(t).concat(d + 1), ".png")
                                                    })
                                                }), Object(ee.jsx)("div", {
                                                    className: "list",
                                                    children: a.get("math_data") && a.get("math_data").filter((function(e) {
                                                        return e.get("SymbolName") === "".concat(t).concat(d + 1)
                                                    })).getIn([0, "SymbolPays"]).reverse().map((function(e, t) {
                                                        return Object(ee.jsx)(I.Fragment, {
                                                            children: 0 !== e && Object(ee.jsxs)("div", {
                                                                children: [5 - t, " -", Object(ee.jsxs)("span", {
                                                                    className: Z()({
                                                                        money: c
                                                                    }),
                                                                    children: [c && Object(ee.jsx)("div", {
                                                                        className: "symbol",
                                                                        dangerouslySetInnerHTML: M(G[s].symbol)
                                                                    }), c ? r(e * l * o) : e * o]
                                                                })]
                                                            }, Object(bt.a)())
                                                        }, Object(bt.a)())
                                                    }))
                                                })]
                                            }, Object(bt.a)())
                                        })), a.get("math_data") && a.get("math_data").filter((function(e) {
                                            return !e.get("SymbolPays").every((function(e) {
                                                return 0 === e
                                            })) && !e.get("SymbolName").includes("_") && "SC" !== e.get("SymbolName") && "W" !== e.get("SymbolName") && "F" !== e.get("SymbolName") && "W1" !== e.get("SymbolName") && "W2" !== e.get("SymbolName") && "W3" !== e.get("SymbolName") && "W4" !== e.get("SymbolName") && "W5" !== e.get("SymbolName")
                                        })).map((function(e) {
                                            return Object(ee.jsxs)("div", {
                                                className: "half",
                                                children: [Object(ee.jsx)("div", {
                                                    className: "pic",
                                                    children: Object(ee.jsx)("img", {
                                                        className: Z()("pay-img object-fit-scale", {
                                                            bigSymbol: m.some((function(t) {
                                                                return t === e.get("SymbolName")
                                                            })),
                                                            mobileSize: n.width < 375
                                                        }),
                                                        alt: "",
                                                        src: "".concat(W, "/order-detail/common/").concat(i, "/symbolList/").concat(e.get("SymbolName"), ".png")
                                                    })
                                                }), Object(ee.jsx)("div", {
                                                    className: "list",
                                                    children: e.get("SymbolPays").reverse().map((function(e, t) {
                                                        var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : e.get("SymbolPays");
                                                        return Object(ee.jsx)(I.Fragment, {
                                                            children: 0 !== e && Object(ee.jsxs)("div", {
                                                                children: [n.size - t, " -", Object(ee.jsxs)("span", {
                                                                    className: Z()({
                                                                        money: c
                                                                    }),
                                                                    children: [c && Object(ee.jsx)("div", {
                                                                        className: "symbol",
                                                                        dangerouslySetInnerHTML: M(G[s].symbol)
                                                                    }), c ? r(e * l * o) : e * o]
                                                                })]
                                                            }, Object(bt.a)())
                                                        }, Object(bt.a)())
                                                    }))
                                                })]
                                            }, e.get("SymbolID"))
                                        }))]
                                    })]
                                })
                            }, Object(bt.a)())
                        }))]
                    })
                };

            function ht() {
                var e = window;
                return {
                    width: e.innerWidth,
                    height: e.innerHeight
                }
            }
            var ut = n(31),
                xt = n.n(ut),
                pt = function(e) {
                    var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : xt.a;
                    Object(I.useEffect)((function() {
                        function n(n) {
                            e.current && !e.current.contains(n.target) && t()
                        }
                        return document.addEventListener("mousedown", n),
                            function() {
                                document.removeEventListener("mousedown", n)
                            }
                    }), [t, e])
                },
                ft = n(13),
                Ot = n.n(ft),
                vt = window.location.host,
                Nt = "https:",
                St = "guardians.one",
                wt = "rd3-",
                It = {
                    dev: "".concat(wt, "dev-gapi.").concat(St, "/api"),
                    qa: "".concat(wt, "qa-gapi.").concat(St, "/api"),
                    int: "".concat(wt, "int-gapi.").concat(St, "/api"),
                    clinetInt: "gapi.cqgame.games/api",
                    prod: "gapi.".concat("cq9web.com", "/api")
                },
                Lt = function(e) {
                    var t = e.getDev,
                        n = void 0 === t ? "dev-" : t,
                        a = e.getQa,
                        i = void 0 === a ? "qa-" : a,
                        c = e.getInt,
                        s = void 0 === c ? "int-" : c,
                        l = e.isclinetInt,
                        o = void 0 !== l && l,
                        r = !!~vt.indexOf(":"),
                        m = !!~vt.indexOf(n),
                        d = !!~vt.indexOf(i),
                        b = !!~vt.indexOf(s);
                    return r || m ? "".concat(Nt, "//").concat(It.dev) : d ? "".concat(Nt, "//").concat(It.qa) : b ? o ? "".concat(Nt, "//").concat(It.clinetInt) : "".concat(Nt, "//").concat(It.int) : "".concat(Nt, "//").concat(It.prod)
                },
                zt = Lt({
                    isclinetInt: !0,
                    getInt: "help.cqgame.games"
                });
            Ot.a.defaults.withCredentials = !0;
            var _t, Dt, Pt, Tt, kt = "fbfbf3a5a4d168940fa2c0516725439ef4f5d46fa6805653b2dc59308de65f0e",
                Ct = n(32),
                Ft = k.b.div(_t || (_t = Object(T.a)(["\n  box-sizing: border-box;\n  width: 100%;\n  /* height: 100%; */\n  min-height: 100vh;\n  color: #fff;\n  background-color: rgba(0, 0, 0, 0.95);\n  & .symbol {\n    display: flex;\n    align-items: center;\n    justify-content: center;\n    height: 18px;\n    padding-right: 2px;\n    color: #ffd542;\n    & > img {\n      height: 100%;\n      object-fit: contain;\n    }\n  }\n  .back-icon {\n    position: fixed;\n    font-size: 40px;\n    bottom: 40px;\n    left: 10px;\n    cursor: pointer;\n  }\n  .table,\n  .table-ckeditor {\n    display: flex;\n    align-self: center;\n    justify-content: center;\n    margin: 30px 0;\n    & img {\n      width: 50px;\n      height: 50px;\n      object-fit: scale-down;\n    }\n    table,\n    tbody,\n    tr,\n    td {\n      max-width: 100%;\n    }\n    td {\n      padding: 0.4em;\n      min-width: 2em;\n      border: 1px solid #6393db;\n    }\n    td:first-child {\n      background-color: #092b5e;\n    }\n    & > table {\n      display: block;\n      overflow: auto;\n    }\n  }\n"]))),
                Ht = k.b.div(Dt || (Dt = Object(T.a)(["\n  box-sizing: border-box;\n  width: 100vw;\n  height: 100vh;\n  display: flex;\n  align-items: center;\n  justify-content: center;\n"]))),
                Wt = k.b.div(Pt || (Pt = Object(T.a)(["\n  position: relative;\n  box-sizing: border-box;\n  width: 100%;\n  height: 100%;\n  max-width: ", ";\n  padding: ", ";\n  margin: 0 auto;\n  font-size: 16px;\n  text-align: center;\n  .caution-hr {\n    margin: 80px 0 40px;\n  }\n  hr {\n    margin: 80px 0;\n    border-color: ", ";\n  }\n  .number {\n    color: #ffff1a;\n    margin: 0 5px;\n  }\n  .cautions {\n    font-size: 16px;\n    color: ", ";\n    line-height: 28px;\n  }\n  .half .list span.money {\n    font-size: ", ";\n    display: inline-flex;\n    align-items: center;\n    margin-left: 5px;\n  }\n  .title {\n    font-size: 28px;\n    color: ", ";\n    margin-bottom: 20px;\n    font-weight: bold;\n  }\n  .flex-column {\n    display: flex;\n    flex-direction: column;\n  }\n\n  .jcc {\n    justify-content: center;\n  }\n  .aic {\n    align-items: center;\n  }\n  .ways-img {\n    width: 50%;\n    @media (max-width: 666px) {\n      width: 70%;\n    }\n  }\n  .w50 {\n    width: 50%;\n    /* &:not(:nth-child(1)):not(:nth-child(2)) {\n      margin-top: 60px;\n    } */\n  }\n  .mt-60 {\n    margin-top: 60px;\n  }\n  .bigSymbol {\n    transform: scale(1.2);\n  }\n  .mobileSize {\n    padding-right: 20px;\n    box-sizing: border-box;\n  }\n  .max130 {\n    max-width: 130px;\n  }\n  .h100px {\n    height: 100px;\n  }\n  .h150px {\n    height: 150px;\n  }\n  .h100 {\n    height: 100%;\n  }\n  .w100px {\n    width: 100px;\n  }\n  .w150px {\n    width: 150px;\n  }\n  .max300 {\n    max-width: 300px;\n  }\n  .my-10 {\n    margin: 10px 0;\n  }\n  .m-10 {\n    margin: 10px;\n  }\n  .mb20 {\n    margin-bottom: 20px;\n  }\n  .object-fit-contain {\n    object-fit: contain;\n  }\n  .object-fit-scale {\n    object-fit: scale-down;\n  }\n  .object-fit-cover {\n    object-fit: cover;\n  }\n  .object-top {\n    object-position: top;\n  }\n  .w100 {\n    width: 100%;\n    &:not(:first-child) {\n      margin-top: 60px;\n    }\n    &.continue {\n      margin-top: 0;\n    }\n  }\n  .w70 {\n    width: 70%;\n  }\n  .m-w100 {\n    max-width: 100%;\n    @media (max-width: 666px) {\n      width: 100%;\n    }\n  }\n  .rules {\n    text-align: left;\n    margin-bottom: 20px;\n    & .num {\n      color: #ffff00;\n    }\n    p {\n      position: relative;\n      font-size: ", ';\n      line-height: 50px;\n      padding-left: 20px;\n      margin-bottom: 10px;\n      /* height:40px; */\n      -moz-text-size-adjust: none;\n      -webkit-text-size-adjust: none;\n      text-size-adjust: none;\n      & img {\n        object-fit: scale-down;\n        /* width: 40px; */\n        max-height: 50px;\n        vertical-align: middle;\n        /* margin: 0 3px; */\n      }\n      &::before {\n        content: "\u25c6";\n        font-size: 14px;\n        vertical-align: middle;\n        position: absolute;\n        left: 0px;\n        height: 50px;\n        display: flex;\n        align-items: center;\n      }\n      &.indent {\n        margin-left: 20px;\n        &::before {\n          content: "";\n        }\n      }\n      &.no-dot {\n        &::before {\n          content: "";\n        }\n      }\n      &.img-text {\n        text-align: center;\n        margin-bottom: 20px;\n        padding-left: 0;\n        &::before {\n          content: "";\n        }\n      }\n      &.highlight {\n        text-align: center;\n        margin-bottom: 20px;\n        padding-left: 0;\n        font-size: 28px;\n        &::before {\n          content: "";\n        }\n      }\n    }\n  }\n'])), (function(e) {
                    return e.windowDimensions.width >= V ? "1024px" : "375px"
                }), (function(e) {
                    return e.windowDimensions.width >= V ? "40px 50px 80px" : "40px 35px 80px"
                }), (function(e) {
                    return e.theme.colors.hr
                }), (function(e) {
                    return e.theme.colors.cautions
                }), (function(e) {
                    return e.isMoneyOver6 ? e.isMoneyOver7 ? "16px" : "18px" : "20px"
                }), (function(e) {
                    return e.theme.colors.title
                }), (function(e) {
                    return e.windowDimensions.width >= V ? "20px" : "16px"
                })),
                At = k.b.div(Tt || (Tt = Object(T.a)(["\n  position: fixed;\n  display: flex;\n  align-items: center;\n  justify-content: center;\n  bottom: 0;\n  width: 100%;\n  height: 40px;\n  background-color: ", ";\n  & p {\n    font-size: 14px;\n    color: ", ";\n    font-weight: bold;\n    text-shadow: 0px 1px 0 rgba(0, 0, 0, 0.38);\n  }\n"])), (function(e) {
                    return e.theme.colors.bar
                }), (function(e) {
                    return e.theme.colors.barText
                }));
            var Mt = function() {
                var e = q(),
                    t = e.defaultLang,
                    n = e.gameId,
                    a = e.denom,
                    i = e.bet,
                    c = e.betLevel,
                    s = e.isSoundOn,
                    l = e.currency,
                    o = e.isCurrency,
                    r = Object(I.useRef)(null),
                    m = function() {
                        var e = Object(I.useState)(ht()),
                            t = Object(P.a)(e, 2),
                            n = t[0],
                            a = t[1];
                        return Object(I.useEffect)((function() {
                            function e() {
                                a(ht())
                            }
                            return window.addEventListener("resize", e),
                                function() {
                                    return window.removeEventListener("resize", e)
                                }
                        }), []), n
                    }(),
                    d = Object(I.useState)(!1),
                    b = Object(P.a)(d, 2),
                    g = b[0],
                    j = b[1],
                    y = Object(I.useState)(!0),
                    h = Object(P.a)(y, 2),
                    u = h[0],
                    x = h[1],
                    p = Object(I.useState)(t),
                    f = Object(P.a)(p, 2),
                    O = f[0],
                    v = f[1],
                    N = Object(I.useState)(!1),
                    S = Object(P.a)(N, 2),
                    w = S[0],
                    L = S[1],
                    z = Object(I.useState)(Object(C.b)()),
                    _ = Object(P.a)(z, 2),
                    T = _[0],
                    H = _[1],
                    W = Object(I.useState)(Object(C.b)()),
                    M = Object(P.a)(W, 2),
                    R = M[0],
                    B = M[1],
                    G = Object(I.useState)(!1),
                    $ = Object(P.a)(G, 2),
                    X = $[0],
                    U = $[1],
                    V = Object(I.useState)(!1),
                    Q = Object(P.a)(V, 2),
                    Z = Q[0],
                    te = Q[1],
                    ne = {
                        colors: K
                    },
                    ie = function(e) {
                        var t = new Audio(e);
                        t.play(), t.onended = function() {
                            t.remove(), t.srcObject = null
                        }
                    };
                pt(r, (function() {
                    L(!1)
                }));
                var ce = function(e, t) {
                        return {
                            __html: A({
                                html: "zh-tw" === O ? Object(F.tify)(e) : "cn" === O ? Object(F.sify)(e) : e,
                                game_id: t
                            })
                        }
                    },
                    le = function(e) {
                        var t = !["cny", "usd", "thb", "krw"].includes(l.toLowerCase()) && e.toFixed(0).toString().length > 3;
                        if (e.toFixed(0).toString().length >= 6 && !t && U(!0), e.toFixed(0).toString().length >= 7 && !t && te(!0), t) {
                            if (e.toFixed(0).toString().length > 6) {
                                var n = "".concat(e.toFixed(0).toString().slice(0, -6)).concat("0" !== e.toFixed(0).toString().slice(-6, -5) ? ".".concat(e.toFixed(0).toString().slice(-6, -5)) : ".0").concat("0" !== e.toFixed(0).toString().slice(-5, -4) ? "".concat(e.toFixed(0).toString().slice(-5, -4)) : "0").concat("0" !== e.toFixed(0).toString().slice(-4, -3) ? "".concat(e.toFixed(0).toString().slice(-4, -3)) : "0").concat("0" !== e.toFixed(0).toString().slice(-3, -2) ? "".concat(e.toFixed(0).toString().slice(-3, -2)) : "0").concat("0" !== e.toFixed(0).toString().slice(-2, -1) ? "".concat(e.toFixed(0).toString().slice(-2, -1)) : "0").concat("0" !== e.toFixed(0).toString().slice(-1) ? "".concat(e.toFixed(0).toString().slice(-1)) : "0");
                                return "".concat(1 * n, "M")
                            }
                            var a = "".concat(e.toFixed(0).toString().slice(0, -3)).concat("0" !== e.toFixed(0).toString().slice(-3, -2) ? ".".concat(e.toFixed(0).toString().slice(-3, -2)) : ".0").concat("0" !== e.toFixed(0).toString().slice(-2, -1) ? "".concat(e.toFixed(0).toString().slice(-2, -1)) : "0").concat("0" !== e.toFixed(0).toString().slice(-1) ? "".concat(e.toFixed(0).toString().slice(-1)) : "0");
                            return "".concat(1 * a, "K")
                        }
                        return e.toLocaleString(void 0, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        })
                    };
                return document.addEventListener("contextmenu", (function(e) {
                    return e.preventDefault()
                })), Object(I.useEffect)((function() {
                    n && O && (function(e, t) {
                        return Ot.a.get("".concat(zt, "/frontend/help/?game_id=").concat(e, "&lang=").concat(t), {
                            headers: {
                                token: kt
                            }
                        })
                    }(n, O).then((function(e) {
                        1706006 === e.data.error_code && j(!0), e.data.result.edited.some((function(e) {
                            return e === O
                        })) || v("en"), H(Object(C.c)(e.data.result))
                    })).then((function() {
                        x(!1)
                    })).catch((function(e) {
                        return console.log(e)
                    })), function(e) {
                        return Ot.a.get("".concat(zt, "/frontend/pay_table/?game_id=").concat(e), {
                            headers: {
                                token: kt
                            }
                        })
                    }(n).then((function(e) {
                        return B(Object(C.c)(e.data.result))
                    })))
                }), [O, n]), Object(I.useEffect)((function() {
                    v(t)
                }), [t]), Object(ee.jsx)(k.a, {
                    theme: ne,
                    children: Object(ee.jsx)(Ft, {
                        "data-version": Ct.version,
                        children: u ? Object(ee.jsx)(Ht, {
                            children: "Loading..."
                        }) : g ? Object(ee.jsx)(Ht, {
                            children: "Maintaining..."
                        }) : T.get("data") && null !== T.get("data") ? Object(ee.jsxs)(ee.Fragment, {
                            children: [Object(ee.jsxs)(Wt, {
                                windowDimensions: m,
                                isMoneyOver6: X,
                                isMoneyOver7: Z,
                                children: [T.get("default_data") && T.get("default_data").size > 0 && Object(ee.jsx)(se, {
                                    lang: O,
                                    helpData: T,
                                    windowDimensions: m,
                                    createContent: ce,
                                    gameId: n,
                                    payTableData: R,
                                    isCurrency: o,
                                    currency: l,
                                    denom: a,
                                    betLevel: c,
                                    moneyConvert: le
                                }), T.get("default_data") && T.get("default_data").size > 0 && T.getIn(["pay_table", "status"]) && Object(ee.jsx)("hr", {}), T.getIn(["pay_table", "status"]) && function(e) {
                                    var t = e.type,
                                        a = e.props;
                                    switch (n) {
                                        case "19":
                                            return Object(ee.jsx)(Me, Object(D.a)({}, a));
                                        case "20":
                                            return Object(ee.jsx)(Ee, Object(D.a)({}, a));
                                        case "21":
                                            return Object(ee.jsx)(Ye, Object(D.a)({}, a));
                                        case "22":
                                            return Object(ee.jsx)(Re, Object(D.a)({}, a));
                                        case "26":
                                            return Object(ee.jsx)(Be, Object(D.a)({}, a));
                                        case "32":
                                            return Object(ee.jsx)(Ge, Object(D.a)({}, a));
                                        case "35":
                                            return Object(ee.jsx)($e, Object(D.a)({}, a));
                                        case "47":
                                            return Object(ee.jsx)(Xe, Object(D.a)({}, a));
                                        case "51":
                                            return Object(ee.jsx)(Ue, Object(D.a)({}, a));
                                        case "66":
                                            return Object(ee.jsx)(qe, Object(D.a)({}, a));
                                        case "117":
                                            return Object(ee.jsx)(Je, Object(D.a)({}, a));
                                        case "128":
                                            return Object(ee.jsx)(Ve, Object(D.a)({}, a));
                                        case "130":
                                            return Object(ee.jsx)(Ke, Object(D.a)({}, a));
                                        case "TA2":
                                        case "133":
                                            return Object(ee.jsx)(Ze, Object(D.a)({}, a));
                                        case "171":
                                            return Object(ee.jsx)(tt, Object(D.a)({}, a));
                                        case "192":
                                            return Object(ee.jsx)(at, Object(D.a)({}, a));
                                        case "242":
                                            return Object(ee.jsx)(mt, Object(D.a)({}, a));
                                        case "251":
                                            return Object(ee.jsx)(dt, Object(D.a)({}, a));
                                        case "GB3":
                                            return Object(ee.jsx)(it, Object(D.a)({}, a));
                                        case "GB5019":
                                            return Object(ee.jsx)(ct, Object(D.a)({}, a));
                                        case "TA25":
                                            return Object(ee.jsx)(st, Object(D.a)({}, a));
                                        case "TA29":
                                            return Object(ee.jsx)(lt, Object(D.a)({}, a));
                                        case "TA33":
                                            return Object(ee.jsx)(ot, Object(D.a)({}, a))
                                    }
                                    switch (t) {
                                        case "normal":
                                            return Object(ee.jsx)(He, Object(D.a)({}, a));
                                        case "pair":
                                            return Object(ee.jsx)(We, Object(D.a)({}, a));
                                        case "challenge":
                                            return Object(ee.jsx)(yt, Object(D.a)({}, a))
                                    }
                                }({
                                    type: T.getIn(["pay_table", "type"]),
                                    props: {
                                        lang: O,
                                        windowDimensions: m,
                                        payTableData: R,
                                        gameId: n,
                                        isCurrency: o,
                                        currency: l,
                                        denom: a,
                                        betLevel: c,
                                        moneyConvert: le,
                                        symbol: T.getIn(["pay_table", "symbol"]),
                                        bet: i,
                                        isLongList: ["34", "136", "153", "184", "GB5001", "TA25"].includes(n)
                                    }
                                }), 0 !== T.size && 0 !== T.get("data").size && function(e) {
                                    var t = e.gameId,
                                        n = e.props;
                                    switch (t) {
                                        case "24":
                                            return Object(ee.jsx)(be, Object(D.a)({}, n));
                                        case "66":
                                            return Object(ee.jsx)(je, Object(D.a)({}, n));
                                        case "77":
                                            return Object(ee.jsx)(he, Object(D.a)({}, n));
                                        case "121":
                                            return Object(ee.jsx)(xe, Object(D.a)({}, n));
                                        case "204":
                                            return Object(ee.jsx)(pe, Object(D.a)({}, n));
                                        case "TA29":
                                            return Object(ee.jsx)(ve, Object(D.a)({}, n));
                                        case "GB5015":
                                            return Object(ee.jsx)(Se, Object(D.a)({}, n));
                                        default:
                                            return Object(ee.jsx)(me, Object(D.a)({}, n))
                                    }
                                }({
                                    gameId: n,
                                    props: {
                                        lang: O,
                                        helpData: T,
                                        betLevel: c,
                                        denom: a,
                                        moneyConvert: le,
                                        currency: l,
                                        isCurrency: o,
                                        payTableData: R,
                                        createContent: ce,
                                        gameId: n,
                                        windowDimensions: m,
                                        sify: F.sify,
                                        tify: F.tify
                                    }
                                }), "none" !== T.get("line") && Object(ee.jsx)(Pe, {
                                    helpData: T,
                                    lang: O,
                                    windowDimensions: m,
                                    createContent: ce,
                                    gameId: n
                                }), Object(ee.jsx)("hr", {
                                    className: "caution-hr"
                                }), Object(ee.jsx)("p", {
                                    className: "cautions",
                                    children: J.cautions[O]
                                })]
                            }), Object(ee.jsx)(ae, {
                                lang: O,
                                popupHandler: function() {
                                    L(!w), s && (n.includes("GB") ? ie("GB8" === n || "GB9" === n || "GB12" === n ? E : Y) : ie(E))
                                },
                                popupState: w,
                                langHandler: function(e) {
                                    v(e), s && (n.includes("GB") ? ie("GB8" === n || "GB9" === n || "GB12" === n ? E : Y) : ie(E)), L(!1)
                                },
                                popupRef: r,
                                edited: T.get("edited"),
                                windowDimensions: m
                            }), T.get("game_title") && T.get("game_name") && Object(ee.jsx)(At, {
                                windowDimensions: m,
                                children: T.getIn(["game_name", O.toLowerCase()]) && Object(ee.jsx)("p", {
                                    children: "zh-tw" === O ? T.getIn(["game_name", "tw"]) : "cn" === O ? Object(F.sify)(T.getIn(["game_name", "cn"])) : T.getIn(["game_name", O.toLowerCase()]) || T.getIn(["game_name", "en"]) || Object(F.sify)(T.getIn(["game_name", "cn"]))
                                })
                            })]
                        }) : Object(ee.jsx)(Ht, {
                            children: "Data Not Found"
                        })
                    })
                })
            };
            _.a.render(Object(ee.jsx)(L.a.StrictMode, {
                children: Object(ee.jsx)(Mt, {})
            }), document.getElementById("root"))
        }
    },
    [
        [62, 1, 2]
    ]
]);
//# sourceMappingURL=main.8bd63e43.chunk.js.map