(this.webpackJsonpmegalon = this.webpackJsonpmegalon || []).push([
    [0], {
        106: function(e, n, t) {
            "use strict";
            t.r(n);
            var a = t(1),
                i = t(2),
                r = t.n(i),
                o = t(40),
                s = t.n(o),
                c = t(3),
                l = t(26),
                d = new URL(window.location).searchParams.get("gametoken") ? new URL(window.location).searchParams.get("gametoken") : null,
                u = new URL(window.location).searchParams.get("language") ? new URL(window.location).searchParams.get("language") : null,
                p = t(12),
                b = t(11),
                g = t.n(b),
                f = function(e) {
                    var n = !(arguments.length > 1 && void 0 !== arguments[1]) || arguments[1],
                        t = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : 0,
                        a = g.a.tz(new Date, "America/Caracas").format(n ? "YYYY-MM-DDT00:00:00" : "YYYY-MM-DDT23:59:59"),
                        i = g.a.tz(new Date, "Asia/Shanghai").format(n ? "YYYY-MM-DDT00:00:00" : "YYYY-MM-DDT23:59:59"),
                        r = g.a.tz(new Date, "Europe/London").format(n ? "YYYY-MM-DDT00:00:00" : "YYYY-MM-DDT23:59:59");
                    switch (e) {
                        case "4":
                            return "".concat(g()(r).add(-t, "days").add("-".concat(e), "hours").format("YYYY-MM-DDTHH:mm:ss"), "-04:00");
                        case "12":
                            return "".concat(g()(i).add(-t, "days").add("-".concat(e), "hours").format("YYYY-MM-DDTHH:mm:ss"), "-04:00");
                        default:
                            return "".concat(g()(a).add(-t, "days").format("YYYY-MM-DDTHH:mm:ss"), "-04:00")
                    }
                },
                h = function() {
                    var e = g.a.tz(new Date, "America/Caracas");
                    return "".concat(g()(e).format("YYYY-MM-DDTHH:mm:ss"), "-04:00")
                },
                m = function() {
                    var e = g.a.tz(new Date, "America/Caracas");
                    return "".concat(g()(e).add(-30, "minutes").format("YYYY-MM-DDTHH:mm:ss"), "-04:00")
                },
                x = function() {
                    var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : 0;
                    return f(e, !0)
                },
                y = function() {
                    var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : 0;
                    return f(e, !1)
                },
                j = function() {
                    var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : 0,
                        n = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : 0;
                    return f(n, !0, e)
                },
                v = function() {
                    var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : 0,
                        n = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : 0;
                    return f(n, !1, e)
                },
                O = function() {
                    var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : 0,
                        n = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : 0,
                        t = g.a.tz(new Date, "America/Caracas");
                    return "".concat(g()(t).add(-e, "days").add(n, "hours").format("YYYY/MM/DD"))
                },
                T = t(4),
                D = t(5),
                w = t(41),
                S = t(6),
                I = t(42),
                k = t.n(I),
                C = t(17),
                z = t.n(C),
                N = window.location.host,
                M = ~N.indexOf(":") ? "//".concat(N, "/api") : "//".concat(N, "/api");
            z.a.defaults.withCredentials = !0;
            var B = window.location.protocol,
                L = t(43),
                P = t.n(L),
                R = function(e) {
                    var n = e.setFun,
                        t = void 0 === n ? k.a : n,
                        a = e.code,
                        i = void 0 === a ? "" : a,
                        r = e.status;
                    t({
                        type: "setDataList",
                        payload: {
                            isLoading: void 0 === r || r,
                            data: Object(p.b)(),
                            code: i,
                            offset: Number(),
                            totalCount: Number()
                        }
                    })
                },
                _ = function() {
                    var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : 0;
                    switch (e) {
                        case 4006:
                            return !0;
                        default:
                            return !1
                    }
                },
                Z = function(e) {
                    var n = e.props,
                        t = n.res,
                        a = t.data.error_msg,
                        i = t.data.error_code;
                    if (n.isId) {
                        var r = t.data.result.data ? t.data.result.data : [];
                        return 4006 === i ? (n.dispatch({
                            type: "getIDsuccess",
                            payload: {
                                DataRenderDoneStatus: !1,
                                IsNoDataLayerStatus: !1,
                                MSearchPopupStatus: !1
                            }
                        }), void R({
                            setFun: n.dispatch,
                            code: i,
                            status: _(i)
                        })) : 0 === r.length || "SUCCESS" !== a ? void n.dispatch({
                            type: "getIDsuccess",
                            payload: {
                                DataRenderDoneStatus: !0,
                                IsNoDataLayerStatus: !0,
                                MSearchPopupStatus: !1
                            }
                        }) : (n.dispatch({
                            type: "setDataList",
                            payload: {
                                isLoading: !1,
                                data: Object(p.b)(r),
                                code: i,
                                offset: 1,
                                totalCount: 1
                            }
                        }), void n.dispatch({
                            type: "getIDfaile",
                            payload: {
                                DataRenderDoneStatus: !0,
                                MSearchPopupStatus: !1
                            }
                        }))
                    }
                    if ("SUCCESS" === a) {
                        var o = t.data.result.data.list ? t.data.result.data.list : [],
                            s = t.data.result.data.count ? t.data.result.data.count : Number();
                        n.dispatch({
                            type: "setDataList",
                            payload: {
                                isLoading: !1,
                                data: Object(p.b)(o),
                                code: i,
                                offset: n.offset,
                                totalCount: s
                            }
                        })
                    } else R({
                        setFun: n.dispatch,
                        code: i,
                        status: _(i)
                    })
                },
                Y = function(e) {
                    var n = e.props,
                        t = n.offset,
                        a = void 0 === t ? "0" : t,
                        i = n.count,
                        r = void 0 === i ? "1000" : i;
                    R({
                            setFun: n.dispatch
                        }),
                        function(e) {
                            return z.a.get("".concat(B).concat(M, "/player_betting/search_time"), {
                                params: e
                            })
                        }({
                            token: n.getGameToken,
                            begin: n.beginTime,
                            end: n.endTime,
                            offset: a,
                            count: r
                        }).then((function(e) {
                            return Z({
                                props: Object(c.a)(Object(c.a)({}, n), {}, {
                                    res: e
                                })
                            })
                        })).catch((function(e) {
                            return console.log(e)
                        }))
                },
                A = function(e) {
                    var n = e.props;
                    (function(e) {
                        return z.a.get("".concat(B).concat(M, "/player_betting/search_id"), {
                            params: e
                        })
                    })({
                        token: n.getGameToken,
                        id: n.rounid
                    }).then((function(e) {
                        return Z({
                            props: Object(c.a)(Object(c.a)({}, n), {}, {
                                res: e,
                                isId: !0
                            })
                        })
                    })).catch((function(e) {
                        return console.log(e)
                    }))
                },
                W = function() {
                    var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : "";
                    return ~e.indexOf(":") || ~e.indexOf("rd3-dev") || ~e.indexOf("rd3-qa") ? "qa" : ~e.indexOf("rd3-int") || ~e.indexOf("detail.cqgame.games") ? "int" : "pord"
                },
                E = function() {
                    var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : "old";
                    switch (W(window.location.host)) {
                        case "dev":
                            return "old" !== e ? "https://rd3-dev-fdetail" : "https://rd3-dev-fdetail-old";
                        case "qa":
                            return "old" !== e ? "https://rd3-qa-fdetail" : "https://rd3-qa-fdetail-old";
                        case "int":
                            return "old" !== e ? "https://fdetail.cqgame.games" : "https://fdetail-old.cqgame.games";
                        default:
                            return "old" !== e ? "https://fdetail.myzsj" : "https://fdetail-old.myzsj"
                    }
                },
                F = function(e) {
                    var n = e.getGameToken,
                        t = e.roundid,
                        a = e.langType,
                        i = void 0 === a ? "" : a,
                        r = e.gamecode,
                        o = void 0 === r ? "game_id" : r,
                        s = {
                            token: n,
                            id: t,
                            game_id: o
                        },
                        c = P()(navigator.userAgent),
                        l = c ? function() {} : window.open("about:blank", "redirect");
                    (function(e) {
                        return z.a.get("".concat(B).concat(M, "/player_betting/detail_link"), {
                            params: e
                        })
                    })(s).then((function(e) {
                        var n = e.data.error_msg;
                        if ("SUCCESS" === n) {
                            var a = function() {
                                    var e = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : "",
                                        n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : "",
                                        t = "AT01" === e,
                                        a = !!~(arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : "").indexOf("AT01m") && "AT01" === e;
                                    return t && !a ? "".concat(E("old")).concat(n.split(E("now"))[1]) : n
                                }(t, o, e.data.result.data.link),
                                r = "".concat(a, "&language=").concat(i, "&gamecode=").concat(o, "&roundid=").concat(t);
                            window.$APIAPP && window.$APIAPP.openView && window.$APIAPP.openView(r), c ? window.location.href = r : l.open(r, "redirect")
                        } else l.open("", "redirect"), console.log(n)
                    })).catch((function(e) {
                        return console.log(e)
                    }))
                },
                G = Object(p.b)({
                    main: {
                        selected: "#F4C000",
                        bodyBackground: "#242830",
                        listBackground: "#30323E",
                        button: "#484C5C",
                        buttonDarken: "#3A3E4C",
                        border: "#444855",
                        text: "#888D9F",
                        textDeep: "#5B5D71",
                        selectedBorder: "#898d9d66"
                    },
                    accent: {
                        green: "#01BA80",
                        red: "#D02A43",
                        blue: "#4C63FF",
                        purple: "#DA4ACB"
                    },
                    other: {
                        cover: "#242830f2",
                        line: "#898d9d66"
                    }
                }),
                q = t(16),
                H = Object(p.b)({
                    "zh-cn": {
                        loader: {
                            title: "\u6b63\u5728\u8f7d\u5165\u4e2d",
                            verificationTitle: "\u8ba4\u8bc1\u5931\u6548",
                            verification: "\u5e10\u53f7\u8ba4\u8bc1\u5df2\u5931\u6548\uff0c\u8bf7\u5173\u95ed\u9875\u9762"
                        },
                        header: {
                            title: "\u6e38\u620f\u7eaa\u5f55"
                        },
                        timeZone: [{
                            value: "0",
                            label: "\u7f8e\u4e1c\u65f6\u533a"
                        }, {
                            value: "12",
                            label: "\u5317\u4eac\u65f6\u533a"
                        }, {
                            value: "4",
                            label: "\u4f26\u6566\u65f6\u533a"
                        }],
                        cusDate: [{
                            value: 0,
                            label: "\u81ea\u8ba2"
                        }, {
                            value: 1,
                            label: "\u524d\u4e00\u5929"
                        }, {
                            value: 2,
                            label: "\u524d\u4e8c\u5929"
                        }, {
                            value: 3,
                            label: "\u524d\u4e09\u5929"
                        }, {
                            value: 4,
                            label: "\u524d\u56db\u5929"
                        }, {
                            value: 5,
                            label: "\u524d\u4e94\u5929"
                        }, {
                            value: 6,
                            label: "\u524d\u516d\u5929"
                        }],
                        alert: {
                            TZchange: "\u5207\u6362\u65f6\u533a\u6210\u529f",
                            busy: "\u64cd\u4f5c\u592a\u9891\u7e41\uff0c\u8bf7\u7a0d\u540e"
                        },
                        dataList: {
                            freegame: "\u514d\u8d39\u6e38\u620f",
                            singlerowbet: "\u518d\u65cb\u8f6c",
                            bonus: "\u7ea2\u5229\u6e38\u620f",
                            norecord: "\u6ca1\u6709\u5386\u53f2\u7eaa\u5f55"
                        },
                        headerList: [{
                            type: "default",
                            name: "\u9ed8\u8ba4"
                        }, {
                            type: "isWin",
                            name: "\u4e2d\u5956"
                        }, {
                            type: "isLose",
                            name: "\u672a\u4e2d\u5956"
                        }],
                        beforeThirty: "\u524d30\u5206",
                        today: "\u4eca\u5929",
                        customized: "\u81ea\u8ba2",
                        singleSearch: "\u5355\u53f7\u641c\u7d22",
                        recordSearch: "\u7eaa\u5f55\u641c\u7d22",
                        dateSearch: "\u65e5\u671f\u641c\u7d22\uff1a",
                        numberSearch: "\u5355\u53f7\u641c\u7d22 (7\u5929\u5167)\uff1a",
                        btnSearch: "\u641c\u7d22",
                        searchPlaceholder: "\u8bf7\u8f93\u5165\u5355\u53f7...",
                        backHome: "\u56de\u9996\u9875",
                        norecord: "\u7a7a\u7a7a\u5982\u4e5f",
                        norecordSub: "\u627e\u4e0d\u7740\u300c|\u300d\u8fd9\u7b14\u8d44\u6599"
                    },
                    en: {
                        loader: {
                            title: "Loading",
                            verificationTitle: "Invalid Verification",
                            verification: "Account verification is invalid, please close the page."
                        },
                        header: {
                            title: "Game History"
                        },
                        timeZone: [{
                            value: "0",
                            label: "North American Eastern"
                        }, {
                            value: "12",
                            label: "Beijing"
                        }, {
                            value: "4",
                            label: "London"
                        }],
                        cusDate: [{
                            value: 0,
                            label: "Customize"
                        }, {
                            value: 1,
                            label: "the day before"
                        }, {
                            value: 2,
                            label: "2 days ago"
                        }, {
                            value: 3,
                            label: "3 days ago"
                        }, {
                            value: 4,
                            label: "4 days ago"
                        }, {
                            value: 5,
                            label: "5 days ago"
                        }, {
                            value: 6,
                            label: "6 days ago"
                        }],
                        alert: {
                            TZchange: "Switch Time Zone Successfully.",
                            busy: "Server too busy, please wait."
                        },
                        dataList: {
                            freegame: "Free Game",
                            singlerowbet: "Respin",
                            bonus: "Bonus Game",
                            norecord: "No Game History"
                        },
                        headerList: [{
                            type: "default",
                            name: "Default"
                        }, {
                            type: "isWin",
                            name: "Winning"
                        }, {
                            type: "isLose",
                            name: "Not Winning"
                        }],
                        beforeThirty: "Pre 30 Minutes",
                        today: "Today",
                        customized: "Customize",
                        singleSearch: "Order History",
                        recordSearch: "Record Search",
                        dateSearch: "Date\uff1a",
                        numberSearch: "Order History (Within 7 Days)\uff1a",
                        btnSearch: "Search",
                        searchPlaceholder: "Please Enter Order No. ...",
                        backHome: "Home",
                        norecord: "No Data",
                        norecordSub: "Record\u300c|\u300dIs Not Found."
                    },
                    th: {
                        loader: {
                            title: "\u0e01\u0e33\u0e25\u0e31\u0e07\u0e42\u0e2b\u0e25\u0e14",
                            verificationTitle: "\u0e01\u0e32\u0e23\u0e22\u0e37\u0e19\u0e22\u0e31\u0e19\u0e44\u0e21\u0e48\u0e16\u0e39\u0e01\u0e15\u0e49\u0e2d\u0e07",
                            verification: "\u0e01\u0e32\u0e23\u0e22\u0e37\u0e19\u0e22\u0e31\u0e19\u0e1a\u0e31\u0e0d\u0e0a\u0e35\u0e44\u0e21\u0e48\u0e16\u0e39\u0e01\u0e15\u0e49\u0e2d\u0e07 \u0e42\u0e1b\u0e23\u0e14\u0e1b\u0e34\u0e14\u0e2b\u0e19\u0e49\u0e32"
                        },
                        header: {
                            title: "\u0e1b\u0e23\u0e30\u0e27\u0e31\u0e15\u0e34\u0e40\u0e01\u0e21"
                        },
                        timeZone: [{
                            value: "0",
                            label: "\u0e2d\u0e40\u0e21\u0e23\u0e34\u0e01\u0e32\u0e15\u0e30\u0e27\u0e31\u0e19\u0e2d\u0e2d\u0e01"
                        }, {
                            value: "12",
                            label: "\u0e1b\u0e31\u0e01\u0e01\u0e34\u0e48\u0e07"
                        }, {
                            value: "4",
                            label: "\u0e25\u0e2d\u0e19\u0e14\u0e2d\u0e19"
                        }],
                        cusDate: [{
                            value: 0,
                            label: "\u0e1b\u0e23\u0e31\u0e1a\u0e41\u0e15\u0e48\u0e07"
                        }, {
                            value: 1,
                            label: "\u0e27\u0e31\u0e19\u0e01\u0e48\u0e2d\u0e19\u0e2b\u0e19\u0e49\u0e32\u0e19\u0e35\u0e49"
                        }, {
                            value: 2,
                            label: "2\u0e27\u0e31\u0e19\u0e17\u0e35\u0e48\u0e41\u0e25\u0e49\u0e27"
                        }, {
                            value: 3,
                            label: "3\u0e27\u0e31\u0e19\u0e17\u0e35\u0e48\u0e41\u0e25\u0e49\u0e27"
                        }, {
                            value: 4,
                            label: "4\u0e27\u0e31\u0e19\u0e17\u0e35\u0e48\u0e41\u0e25\u0e49\u0e27"
                        }, {
                            value: 5,
                            label: "5\u0e27\u0e31\u0e19\u0e17\u0e35\u0e48\u0e41\u0e25\u0e49\u0e27"
                        }, {
                            value: 6,
                            label: "6\u0e27\u0e31\u0e19\u0e17\u0e35\u0e48\u0e41\u0e25\u0e49\u0e27"
                        }],
                        alert: {
                            TZchange: "\u0e42\u0e0b\u0e19\u0e40\u0e27\u0e25\u0e32\u0e2a\u0e25\u0e31\u0e1a\u0e2a\u0e33\u0e40\u0e23\u0e47\u0e08",
                            busy: "\u0e40\u0e0b\u0e34\u0e23\u0e4c\u0e1f\u0e40\u0e27\u0e2d\u0e23\u0e4c\u0e44\u0e21\u0e48\u0e27\u0e48\u0e32\u0e07\u0e14\u0e1b\u0e23\u0e14\u0e23\u0e2d\u0e2a\u0e31\u0e01\u0e04\u0e23\u0e39\u0e48"
                        },
                        dataList: {
                            freegame: "\u0e40\u0e01\u0e21\u0e1f\u0e23\u0e35",
                            singlerowbet: "\u0e2b\u0e21\u0e38\u0e19\u0e2d\u0e35\u0e01\u0e04\u0e23\u0e31\u0e49\u0e07",
                            bonus: "\u0e40\u0e01\u0e21\u0e42\u0e1a\u0e19\u0e31\u0e2a",
                            norecord: "\u0e44\u0e21\u0e48\u0e21\u0e35\u0e1b\u0e23\u0e30\u0e27\u0e31\u0e15\u0e34\u0e40\u0e01\u0e21"
                        },
                        headerList: [{
                            type: "default",
                            name: "\u0e04\u0e48\u0e32\u0e40\u0e23\u0e48\u0e21\u0e15\u0e49\u0e19"
                        }, {
                            type: "isWin",
                            name: "\u0e0a\u0e19\u0e30"
                        }, {
                            type: "isLose",
                            name: "\u0e44\u0e21\u0e48\u0e0a\u0e19\u0e30"
                        }],
                        beforeThirty: "\u0e01\u0e48\u0e2d\u0e19 30 \u0e19\u0e32\u0e17\u0e35",
                        today: "\u0e27\u0e31\u0e19\u0e19\u0e35\u0e49",
                        customized: "\u0e1b\u0e23\u0e31\u0e1a\u0e41\u0e15\u0e48\u0e07",
                        singleSearch: "\u0e2b\u0e21\u0e32\u0e22\u0e40\u0e25\u0e02\u0e2a\u0e31\u0e48\u0e07\u0e0b\u0e37\u0e49\u0e2d",
                        recordSearch: "\u0e1a\u0e31\u0e19\u0e17\u0e36\u0e01\u0e01\u0e32\u0e23\u0e04\u0e49\u0e19\u0e2b\u0e32",
                        dateSearch: "\u0e27\u0e31\u0e19\u0e17\u0e35\u0e48\uff1a",
                        numberSearch: "\u0e1b\u0e23\u0e30\u0e27\u0e31\u0e15\u0e34\u0e01\u0e32\u0e23\u0e2a\u0e31\u0e48\u0e07\u0e0b\u0e37\u0e49\u0e2d(\u0e20\u0e32\u0e22\u0e43\u0e19 7 \u0e27\u0e31\u0e19)\uff1a",
                        btnSearch: "\u0e04\u0e49\u0e19\u0e2b\u0e32",
                        searchPlaceholder: "\u0e01\u0e23\u0e38\u0e13\u0e32\u0e01\u0e23\u0e2d\u0e01\u0e25\u0e33\u0e14\u0e31\u0e1a\u0e17\u0e35\u0e48",
                        backHome: "\u0e1a\u0e49\u0e32\u0e19",
                        norecord: "\u0e44\u0e21\u0e48\u0e21\u0e35\u0e02\u0e49\u0e2d\u0e21\u0e39\u0e25",
                        norecordSub: "\u0e44\u0e21\u0e48\u0e1e\u0e1a\u0e1a\u0e31\u0e19\u0e17\u0e36\u0e01\u300c|\u300d\u0e40\u0e0b\u0e34\u0e23\u0e4c\u0e1f\u0e40\u0e27\u0e2d\u0e23\u0e4c\u0e44\u0e21\u0e48\u0e27\u0e48\u0e32\u0e07\u0e14\u0e1b\u0e23\u0e14\u0e23\u0e2d\u0e2a\u0e31\u0e01\u0e04\u0e23\u0e39\u0e48\u0e01\u0e32\u0e23\u0e22\u0e37\u0e19\u0e22\u0e31\u0e19\u0e44\u0e21\u0e48\u0e16\u0e39\u0e01\u0e15\u0e49\u0e2d\u0e07"
                    },
                    vn: {
                        loader: {
                            title: "\u0110ang t\u1ea3i",
                            verificationTitle: "Ch\u1ee9ng nh\u1eadn v\xf4 hi\u1ec7u",
                            verification: "Ch\u1ee9ng nh\u1eadn c\u1ee7a t\xe0i kho\u1ea3n \u0111\xe3 v\xf4 hi\u1ec7u, vui l\xf2ng \u0111\xf3ng trang"
                        },
                        header: {
                            title: "L\u1ecbch s\u1eed tr\xf2 ch\u01a1i"
                        },
                        timeZone: [{
                            value: "0",
                            label: "M\xfai gi\u1edd M\u1ef9 \u0110\xf4ng"
                        }, {
                            value: "12",
                            label: "M\xfai gi\u1edd B\u1eafc Kinh"
                        }, {
                            value: "4",
                            label: "M\xfai gi\u1edd Lu\xe2n \u0110\xf4n"
                        }],
                        cusDate: [{
                            value: 0,
                            label: "T\u1ef1 \u0111\u1eb7t"
                        }, {
                            value: 1,
                            label: "H\xf4m qua"
                        }, {
                            value: 2,
                            label: "2 ng\xe0y tr\u01b0\u1edbc"
                        }, {
                            value: 3,
                            label: "3 ng\xe0y tr\u01b0\u1edbc"
                        }, {
                            value: 4,
                            label: "4 ng\xe0y tr\u01b0\u1edbc"
                        }, {
                            value: 5,
                            label: "5 ng\xe0y tr\u01b0\u1edbc"
                        }, {
                            value: 6,
                            label: "6 ng\xe0y tr\u01b0\u1edbc"
                        }],
                        alert: {
                            TZchange: "Chuy\u1ec3n \u0111\u1ed5i m\xfai gi\u1edd th\xe0nh c\xf4ng",
                            busy: "Thao t\xe1c qu\xe1 nhi\u1ec1u l\u1ea7n, vui l\xf2ng ch\u1edd \u0111\u1ee3i"
                        },
                        dataList: {
                            freegame: "Tr\xf2 ch\u01a1i mi\u1ec5n ph\xed",
                            singlerowbet: "Respin",
                            bonus: "Bonus Game",
                            norecord: "Kh\xf4ng c\xf3 l\u1ecbch s\u1eed ghi ch\xe9p"
                        },
                        headerList: [{
                            type: "default",
                            name: "M\u1eb7c \u0111\u1ecbnh"
                        }, {
                            type: "isWin",
                            name: "Tr\xfang th\u01b0\u1edfng"
                        }, {
                            type: "isLose",
                            name: "Khu \u0111\u1eb7t c\u01b0\u1ee3c"
                        }],
                        beforeThirty: "Tr\u01b0\u1edbc 30 ph\xfat",
                        today: "H\xf4m nay",
                        customized: "T\u1ef1 \u0111\u1eb7t",
                        singleSearch: "T\xecm ki\u1ebfm s\u1ed1 \u0111\u01a1n",
                        recordSearch: "L\u1ecbch s\u1eed t\xecm ki\u1ebfm",
                        dateSearch: "Ng\xe0y t\xecm ki\u1ebfm\uff1a",
                        numberSearch: "T\xecm ki\u1ebfm s\u1ed1 \u0111\u01a1n (Trong v\xf2ng 7 ng\xe0y)\uff1a",
                        btnSearch: "T\xecm ki\u1ebfm",
                        searchPlaceholder: "Vui l\xf2ng nh\u1eadp s\u1ed1 \u0111\u01a1n",
                        backHome: "V\u1ec1 trang ch\u1ee7",
                        norecord: "Kh\xf4ng c\xf3 ghi ch\xe9p",
                        norecordSub: "Kh\xf4ng t\xecm th\u1ea5y d\u1eef li\u1ec7u\u300c|\u300dn\xe0y"
                    }
                });

            function U() {
                var e = Object(D.a)(["\n  max-width: 1100px;\n  min-width: 800px;\n  margin: 0 auto;\n  padding: 0 20px;\n  text-align: center;\n  p {\n    margin-top: 30px;\n    color: #fff;\n    font-size: 18px;\n  }\n"]);
                return U = function() {
                    return e
                }, e
            }

            function V() {
                var e = Object(D.a)(["\n  position: fixed;\n  top: 0;\n  left: 0;\n  width: 100%;\n  min-height: 100vh;\n  z-index: 101;\n  background-color: ", ';\n  .cover {\n    position: absolute;\n    top: 160px;\n    left: 50%;\n    transform: translateX(-50%);\n    text-align: center;\n    p {\n      margin-top: 30px;\n      color: #fff;\n      font-size: 18px;\n    }\n    .verification-img {\n      width: 220px;\n      height: 220px;\n      background: url("./error.png") no-repeat;\n      background-size: 100%;\n    }\n    .verification-sub {\n      margin-top: 15px;\n      font-size: 14px;\n      color: #888d9f;\n    }\n  }\n  ', "\n  ", ";\n"]);
                return V = function() {
                    return e
                }, e
            }
            var J = S.a.div(V(), G.getIn(["main", "bodyBackground"]), (function(e) {
                    return e.RWD.isTablet && "\n    .cover p {\n      font-size:20px;\n    }\n    .cover .verification-img {\n      width:330px;\n      height:330px;\n    }\n    .cover .verification-sub {\n      margin-top:10px;\n      font-size:15px;\n    }\n  "
                }), (function(e) {
                    var n = e.RWD;
                    return !n.isTablet && !n.isMobile && "\n    .cover p {\n      font-size:22px;\n    }\n    .cover .verification-img {\n      width:330px;\n      height:330px;\n    }\n    .cover .verification-sub {\n      margin-top:15px;\n      font-size:16px;\n    }\n  "
                })),
                K = function(e) {
                    var n = e.props,
                        t = n.langType,
                        i = n.code,
                        r = n.isMobile,
                        o = n.isTablet,
                        s = function(e) {
                            switch (e.code) {
                                case 4006:
                                    return Object(a.jsxs)(a.Fragment, {
                                        children: [Object(a.jsx)("div", {
                                            className: "verification-img"
                                        }), Object(a.jsx)("p", {
                                            children: H.getIn([t, "loader", "verificationTitle"])
                                        }), Object(a.jsx)("div", {
                                            className: "verification-sub",
                                            children: H.getIn([t, "loader", "verification"])
                                        })]
                                    });
                                default:
                                    return Object(a.jsxs)(a.Fragment, {
                                        children: [Object(a.jsx)(q.ClipLoader, {
                                            size: 45,
                                            color: "#f4c000"
                                        }), Object(a.jsx)("p", {
                                            children: H.getIn([t, "loader", "title"])
                                        })]
                                    })
                            }
                        };
                    return Object(a.jsx)(J, {
                        RWD: {
                            isMobile: r,
                            isTablet: o
                        },
                        children: Object(a.jsx)("div", {
                            className: "cover",
                            children: Object(a.jsx)(s, {
                                code: i
                            })
                        })
                    })
                },
                X = S.a.div(U()),
                $ = function(e) {
                    var n = e.props.langType;
                    return Object(a.jsxs)(X, {
                        children: [Object(a.jsx)(q.ClipLoader, {
                            size: 45,
                            color: "#f4c000"
                        }), Object(a.jsx)("p", {
                            children: H.getIn([n, "loader", "title"])
                        })]
                    })
                },
                Q = t(45);

            function ee() {
                var e = Object(D.a)(["\n  @keyframes fadeInOut {\n    0% {transform: translate(-50%,0px);opacity:0;}\n    10% {transform: translate(-50%,-40px);opacity:1;}\n    90% {transform: translate(-50%,-40px);opacity:1;}\n    100% {transform: translate(-50%,0px);opacity:0;}\n  };\n  @keyframes desktopfadeInOut {\n    0% {transform: translate(-50%,0px);opacity:0;}\n    10% {transform: translate(-50%,30px);opacity:1;}\n    90% {transform: translate(-50%,30px);opacity:1;}\n    100% {transform: translate(-50%,0px);opacity:0;}\n  };\n  position: fixed;\n  opacity:0;\n  left:50%;\n  bottom:0;\n  width:85%;\n  transform: translate(-50%,0px);\n  background-color: ", ";\n  padding:11px 18px;\n  border-radius:5px;\n  box-sizing:border-box;\n  display:flex;\n  align-items:center;\n  z-index:101;\n  animation: fadeInOut 2.5s;\n  .svg-AiOutlineExclamationCircle {\n    color: #fff;\n    width:18px;\n    height:18px;\n    margin-right:10px;\n  }\n  .text {\n    color: #fff;\n    font-size:14px;\n  }\n  ", "\n  ", "\n"]);
                return ee = function() {
                    return e
                }, e
            }
            var ne = S.a.div(ee(), (function(e) {
                    return e.color
                }), (function(e) {
                    return e.RWD.isTablet && "\n    .svg-AiOutlineExclamationCircle {\n      width:23px;\n      height:23px;\n    }\n    .text {\n      font-size:16px;\n    }\n  "
                }), (function(e) {
                    var n = e.RWD;
                    return !n.isMobile && !n.isTablet && "\n    bottom:unset;\n    top:0;\n    left:calc(50% + 180px);\n    width:600px;\n    animation: desktopfadeInOut 2.5s;\n    .svg-AiOutlineExclamationCircle {\n      width:23px;\n      height:23px;\n    }\n    .text {\n      font-size:16px;\n    }\n  "
                })),
                te = function(e) {
                    var n = e.alertArrCount,
                        t = void 0 === n ? 0 : n,
                        r = Object(i.useContext)(kn),
                        o = Object(T.a)(r, 2),
                        s = o[0],
                        c = o[1];
                    return Object(i.useEffect)((function() {
                        0 === t && c({
                            type: "setAlertArr",
                            payload: {
                                data: Object(p.a)()
                            }
                        })
                    }), [t, c]), Object(a.jsxs)(ne, {
                        color: s.alertText.color,
                        RWD: {
                            isMobile: s.isMobile,
                            isTablet: s.isTablet
                        },
                        children: [Object(a.jsx)(Q.a, {
                            className: "svg-AiOutlineExclamationCircle"
                        }), Object(a.jsx)("span", {
                            className: "text",
                            children: s.alertText.text
                        })]
                    })
                };

            function ae() {
                var e = Object(D.a)(["\n  position: absolute;\n  top:0;\n  width:100%;\n  height:100vh;\n  background-color:", ";\n  z-index:", ";\n  display:flex;\n  align-items:center;\n  justify-content:center;\n  .container {\n    text-align:center;\n  }\n  .status-img {\n    margin:0 auto;\n    width:240px;\n    height:240px;\n    background:url('./empty.png')no-repeat;\n    background-size:100%;\n  }\n  .main-title {\n    margin-top:15px;\n    color:#fff;\n    font-size:18px;\n  }\n  .subtitle {\n    margin-top:15px;\n    font-size: 14px;\n    color:", ";\n  }\n  .subtitle .keyword {\n    color:", ";\n    margin:0 5px;\n  }\n  .back-btn {\n    display:inline-block;\n    font-size:14px;\n    border-radius:5px;\n    padding:10px 30px;\n    margin-top:25px;\n    color:", ";\n    background-color:", ";\n  }\n  ", "\n\n  ", "\n"]);
                return ae = function() {
                    return e
                }, e
            }
            var ie = S.a.div(ae(), G.getIn(["main", "bodyBackground"]), (function(e) {
                    return e.zIndex
                }), G.getIn(["main", "text"]), G.getIn(["main", "selected"]), G.getIn(["main", "bodyBackground"]), G.getIn(["main", "selected"]), (function(e) {
                    return e.RWD.isTablet && "\n    .status-img {\n      width:300px;\n      height:300px;\n    }\n    .main-title {\n      font-size:20px;\n    }\n    .subtitle {\n      font-size:15px;\n    }\n    .back-btn {\n      margin-top:30px;\n      padding:12px 35px;\n      font-size:16px;\n    }\n  "
                }), (function(e) {
                    var n = e.RWD;
                    return !n.isTablet && !n.isMobile && "\n    position: unset;\n    height:calc(100vh - 245px);\n    min-width:800px;\n    max-width:1100px;\n    margin:0 auto;\n    align-items:flex-start;\n    .status-img {\n      width:300px;\n      height:300px;\n    }\n    .main-title {\n      margin-top:25px;\n      font-size:20px;\n    }\n    .subtitle {\n      font-size:15px;\n    }\n    .back-btn {\n      margin-top:50px;\n      padding:12px 35px;\n      font-size:16px;\n      cursor: pointer;\n    }\n  "
                })),
                re = function(e) {
                    var n = e.zIndex,
                        t = void 0 === n ? 3 : n,
                        r = Object(i.useContext)(kn),
                        o = Object(T.a)(r, 2),
                        s = o[0],
                        c = o[1];
                    return Object(a.jsx)(ie, {
                        zIndex: t,
                        RWD: {
                            isMobile: s.isMobile,
                            isTablet: s.isTablet
                        },
                        children: Object(a.jsxs)("div", {
                            className: "container",
                            children: [Object(a.jsx)("div", {
                                className: "status-img"
                            }), Object(a.jsx)("div", {
                                className: "main-title",
                                children: H.getIn([s.langType, "norecord"])
                            }), Object(a.jsxs)("div", {
                                className: "subtitle",
                                children: [Object(a.jsx)("span", {
                                    children: H.getIn([s.langType, "norecordSub"]).split("|")[0]
                                }), Object(a.jsx)("span", {
                                    className: "keyword",
                                    children: s.inputCurrentVal
                                }), Object(a.jsx)("span", {
                                    children: H.getIn([s.langType, "norecordSub"]).split("|")[1]
                                })]
                            }), Object(a.jsx)("div", {
                                className: "back-btn ga_order_home",
                                onClick: function() {
                                    return c({
                                        type: "setIsNoDataLayer",
                                        payload: {
                                            status: !1
                                        }
                                    })
                                },
                                children: H.getIn([s.langType, "backHome"])
                            })]
                        })
                    })
                },
                oe = t(15),
                se = t(14),
                ce = t(7),
                le = t.n(ce),
                de = t(10);

            function ue() {
                var e = Object(D.a)(["\n  position: absolute;\n  bottom: 0;\n  left: 50%;\n  transform: translate(-50%, 50%);\n  width: 100%;\n  display: flex;\n  align-items: center;\n  justify-content: center;\n  z-index: 2;\n  li {\n    width: 100%;\n    padding: 11px 0;\n    color: #fff;\n    font-size: 13px;\n    background-color: ", ";\n    animation: 0.5s fade;\n    text-align: center;\n    &:nth-child(1) {\n      border-radius: 5px 0 0 5px;\n    }\n    &:nth-child(2) {\n      border-left: 1px solid ", ";\n      border-right: 1px solid ", ";\n    }\n    &:nth-child(3) {\n      border-radius: 0 5px 5px 0;\n    }\n    &:nth-child(4) {\n      padding: 0;\n      background-color: unset;\n    }\n    &.selected {\n      background-color: ", ";\n      color: ", ";\n    }\n  }\n  ", ";\n  ", "\n"]);
                return ue = function() {
                    return e
                }, e
            }

            function pe() {
                var e = Object(D.a)(["\n  position: relative;\n  padding: 11px 10px;\n  display: flex;\n  align-items: center;\n  justify-content: space-between;\n  border-radius: 5px;\n  background-color: ", ";\n  margin-left: 8px;\n  .left {\n    padding-right: 20px;\n  }\n  select {\n    position: absolute;\n    top: 0;\n    left: 0;\n    width: 100%;\n    height: 100%;\n    opacity: 0;\n    animation: 0.5s fade;\n  }\n  .desktop-select {\n    position: absolute;\n    top: calc(100% + 2px);\n    left: 0;\n    width: 100%;\n    .list-style {\n      position: relative;\n      display: flex;\n      align-items: center;\n      justify-content: center;\n      background-color: #fff;\n      font-size: 15px;\n      color: ", ";\n      height: 49px;\n      border-bottom: 1px solid ", ";\n      &:nth-child(1) {\n        border-top-left-radius: 5px;\n        border-top-right-radius: 5px;\n      }\n      &:last-child {\n        border-bottom-left-radius: 5px;\n        border-bottom-right-radius: 5px;\n        height: 50px;\n        border-bottom: unset;\n      }\n      &:hover {\n        background-color: ", ";\n        border-bottom: 1px solid ", ";\n        font-weight: bold;\n      }\n    }\n    .list-style .svg-BsCheck {\n      position: absolute;\n      top: 50%;\n      left: 10px;\n      width: 18px;\n      height: 18px;\n      transform: translateY(-50%);\n    }\n  }\n  ", "\n"]);
                return pe = function() {
                    return e
                }, e
            }
            var be = S.a.div(pe(), G.getIn(["main", "button"]), G.getIn(["main", "bodyBackground"]), G.getIn(["main", "selectedBorder"]), G.getIn(["main", "selected"]), G.getIn(["main", "selected"]), (function(e) {
                    var n = e.RWD;
                    return !n.isMobile && !n.isTablet && "\n    padding:0 12px;\n    margin-left:0;\n    height:100%;\n    border-radius:4px;\n    .left { padding-right:30px; }\n    select { cursor: pointer; }\n  "
                })),
                ge = function(e) {
                    var n = e.alertPop,
                        t = Object(i.useContext)(kn),
                        r = Object(T.a)(t, 2),
                        o = r[0],
                        s = r[1],
                        c = Object(i.useState)(!1),
                        l = Object(T.a)(c, 2),
                        d = l[0],
                        u = l[1],
                        p = !o.isMobile && !o.isTablet;
                    return Object(i.useEffect)((function() {
                        var e = function(e) {
                            var n = document.getElementById("pagesStatus");
                            n && !n.contains(e.target) && u(!1)
                        };
                        return document.addEventListener("mousedown", e),
                            function() {
                                document.removeEventListener("mousedown", e)
                            }
                    }), []), Object(a.jsxs)(be, {
                        className: "ga_displaynum",
                        RWD: {
                            isMobile: o.isMobile,
                            isTablet: o.isTablet
                        },
                        onClick: function() {
                            p ? u((function(e) {
                                return !e
                            })) : s({
                                type: "setMSelectorPopup",
                                payload: {
                                    status: !0,
                                    type: "pageCount",
                                    selectedCusDate: o.mSelectorPopup.selectedCusDate
                                }
                            })
                        },
                        children: [Object(a.jsx)("div", {
                            className: "left ga_displaynum",
                            children: o.currentCount
                        }), Object(a.jsx)(de.b, {
                            className: "svg-IoIosArrowDown ga_displaynum"
                        }), p && d && Object(a.jsx)("div", {
                            className: "desktop-select",
                            children: ["300", "500", "800", "1000"].map((function(e, t) {
                                return Object(a.jsxs)("div", {
                                    className: "list-style ga_displaynum_".concat(e),
                                    onClick: function() {
                                        o.timeBusy || o.dataList.isLoading ? (n({
                                            text: H.getIn([o.langType, "alert", "busy"]),
                                            color: G.getIn(["accent", "red"])
                                        }), s({
                                            type: "setCurrentCount",
                                            payload: {
                                                number: o.currentCount
                                            }
                                        })) : s({
                                            type: "setStatusSelected",
                                            payload: {
                                                dataRenderDone: !1,
                                                timeBusy: !0,
                                                currentCount: e,
                                                currentOffset: "0"
                                            }
                                        })
                                    },
                                    children: [o.currentCount === e && Object(a.jsx)(oe.a, {
                                        className: "svg-BsCheck ga_displaynum_".concat(e)
                                    }), e]
                                }, t)
                            }))
                        })]
                    })
                },
                fe = S.a.ul(ue(), G.getIn(["main", "button"]), G.getIn(["other", "line"]), G.getIn(["other", "line"]), G.getIn(["main", "selected"]), G.getIn(["main", "bodyBackground"]), (function(e) {
                    return e.RWD.isTablet && "\n    width:60%;\n    li {\n      font-size:14px;\n    }\n  "
                }), (function(e) {
                    var n = e.RWD;
                    return !n.isMobile && !n.isTablet && "\n    position: relative;\n    left:0;\n    transform: unset;\n    justify-content:flex-start;\n    margin-top: 35px;\n    li {\n      display:flex;\n      align-items:center;\n      justify-content:center;\n      padding:0;\n      width:90px;\n      height:50px;\n      cursor: pointer;\n      &:nth-child(1) {\n        border-radius:4px 0 0 4px;\n      }\n      &:nth-child(3) {\n        border-radius:0 4px 4px 0;\n      }\n      &:nth-child(4) {\n        position: absolute;\n        right: 0;\n      }\n    }\n  "
                })),
                he = function(e) {
                    var n = e.alertPop,
                        t = Object(i.useContext)(kn),
                        r = Object(T.a)(t, 2),
                        o = r[0],
                        s = r[1],
                        c = H.getIn([o.langType, "headerList"]).toJS();
                    return Object(a.jsxs)(fe, {
                        RWD: {
                            isMobile: o.isMobile,
                            isTablet: o.isTablet
                        },
                        children: [c.map((function(e, n) {
                            return Object(a.jsx)("li", {
                                className: le()("".concat(function(e) {
                                    switch (e) {
                                        case "default":
                                            return "ga_winpoint_default";
                                        case "isWin":
                                            return "ga_winpoint_win";
                                        case "isLose":
                                            return "ga_winpoint_notwin"
                                    }
                                }(e.type)), {
                                    selected: o.filterType === e.type
                                }),
                                onClick: function() {
                                    return s({
                                        type: "setFilterType",
                                        payload: {
                                            type: e.type
                                        }
                                    })
                                },
                                children: e.name
                            }, n)
                        })), Object(a.jsx)("li", {
                            className: "ga_displaynum",
                            children: Object(a.jsx)(ge, {
                                alertPop: n
                            })
                        })]
                    })
                };

            function me() {
                var e = Object(D.a)(["\n  max-width:1100px;\n  min-width:800px;\n  margin:0 auto;\n  padding:55px 20px 35px 20px;\n  z-index:2;\n  background-color:", ";\n  .show-TZ {\n    display:flex;\n    align-items:center;\n    width:100%;\n    height:70px;\n    border-radius:5px;\n    padding:0 25px;\n    box-sizing:border-box;\n    color:#fff;\n    background-color:", ";\n  }\n"]);
                return me = function() {
                    return e
                }, e
            }

            function xe() {
                var e = Object(D.a)(["\n  position: fixed;\n  top:0;\n  left:0;\n  width:100%;\n  z-index:99;\n  background-color:", ";\n  /* top-area */\n  .top-area {\n    position: relative;\n    height: 55px;\n    padding: 15px 0;\n    box-sizing:border-box;\n    border-bottom:1px solid ", ";\n    .title {\n      text-align:center;\n      font-size:17px;\n      color:#fff;\n    }\n  }\n  .svg-BsGear {\n    position: absolute;\n    top:50%;\n    left:25px;\n    width:22px;\n    height:22px;\n    fill:#fff;\n    transform:translateY(-50%);\n    animation:.5s fade;\n  }\n  .svg-BiGlobe {\n    position: absolute;\n    top:50%;\n    right:25px;\n    width:22px;\n    height:22px;\n    fill:#fff;\n    transform:translateY(-50%);\n  }\n  /* bottom-area */\n  .bottom-area {\n    position: relative;\n    width:calc(100% - 40px);\n    height:104px;\n    margin:0 auto;\n    display:flex;\n    justify-content:center;\n    &:before {\n      content:'';\n      position: absolute;\n      z-index:1;\n      background-color:", ";\n      width:calc(100% + 40px);\n      height:40px;\n      bottom:0;\n      left:50%;\n      transform:translate(-50%,100%);\n    }\n  }\n  .show-status {\n    transform:translateY(30px);\n    text-align:center;\n    span {\n      color:#fff;\n      font-size:14px;\n    }\n  }\n  ", ";\n"]);
                return xe = function() {
                    return e
                }, e
            }
            var ye = S.a.div(xe(), G.getIn(["main", "listBackground"]), G.getIn(["main", "border"]), G.getIn(["main", "bodyBackground"]), (function(e) {
                    return e.RWD.isTablet && "\n    .top-area {\n      height:75px;\n      padding:25px 0;\n    }\n    .top-area .title {\n      font-size:20px;\n    }\n    .svg-BsGear { \n      left:35px;\n    }\n    .svg-BiGlobe {\n      right:35px;\n    }\n    .bottom-area {\n      width: calc(100% - 70px);\n      &:before {\n        width: calc(100% + 70px);\n      }\n    }\n    .show-status span {\n      font-size:15px;\n    }\n  "
                })),
                je = S.a.div(me(), G.getIn(["main", "bodyBackground"]), G.getIn(["main", "listBackground"])),
                ve = function(e) {
                    var n = e.alertPop,
                        t = e.isDesktop,
                        r = void 0 !== t && t,
                        o = e.callAlertStatus,
                        s = void 0 !== o && o,
                        c = e.setCallAlertStatus,
                        l = Object(i.useContext)(kn),
                        d = Object(T.a)(l, 2),
                        u = d[0],
                        p = d[1],
                        b = function() {
                            switch (u.currentTZ) {
                                case "0":
                                    return H.getIn([u.langType, "timeZone", 0, "label"]);
                                case "12":
                                    return H.getIn([u.langType, "timeZone", 1, "label"]);
                                case "4":
                                    return H.getIn([u.langType, "timeZone", 2, "label"]);
                                default:
                                    return H.getIn([u.langType, "timeZone", 0, "label"])
                            }
                        },
                        g = function() {
                            switch (u.timeSearchType.type) {
                                case "beforeThirty":
                                    return H.getIn([u.langType, "beforeThirty"]);
                                case "today":
                                    return H.getIn([u.langType, "today"]);
                                case "singleSearch":
                                    return H.getIn([u.langType, "singleSearch"]);
                                case "customized":
                                    return O(u.timeSearchType.content, u.currentTZ);
                                default:
                                    return H.getIn([u.langType, "beforeThirty"])
                            }
                        };
                    return Object(i.useEffect)((function() {
                        s && u.dataRenderDone && (n({
                            text: H.getIn([u.langType, "alert", "TZchange"]),
                            color: G.getIn(["accent", "green"])
                        }), c(!1))
                    }), [s, u.dataRenderDone, u.langType, n, c]), Object(a.jsx)(a.Fragment, {
                        children: r ? Object(a.jsxs)(je, {
                            children: [Object(a.jsxs)("div", {
                                className: "show-TZ",
                                children: [Object(a.jsxs)("span", {
                                    children: [b(), "\uff1a"]
                                }), Object(a.jsx)("span", {
                                    children: g()
                                })]
                            }), "singleSearch" !== u.timeSearchType.type && Object(a.jsx)(he, {
                                alertPop: n
                            })]
                        }) : Object(a.jsxs)(ye, {
                            RWD: {
                                isMobile: u.isMobile,
                                isTablet: u.isTablet
                            },
                            children: [Object(a.jsxs)("div", {
                                className: "top-area",
                                children: [Object(a.jsx)(oe.b, {
                                    className: "svg-BsGear ga_funcbtn_filter",
                                    onClick: function() {
                                        return p({
                                            type: "setMSearchPopup",
                                            payload: {
                                                status: !u.mSearchPopup
                                            }
                                        })
                                    }
                                }), Object(a.jsx)("div", {
                                    className: "title",
                                    children: H.getIn([u.langType, "header", "title"])
                                }), Object(a.jsx)(se.a, {
                                    className: "svg-BiGlobe ga_timezone",
                                    onClick: function() {
                                        return p({
                                            type: "setMSelectorPopup",
                                            payload: {
                                                status: !0,
                                                type: "timeZone",
                                                selectedCusDate: u.mSelectorPopup.selectedCusDate
                                            }
                                        })
                                    }
                                })]
                            }), !u.isNoDataLayer && Object(a.jsxs)("div", {
                                className: "bottom-area",
                                children: [Object(a.jsxs)("div", {
                                    className: "show-status",
                                    children: [Object(a.jsxs)("span", {
                                        children: [b(), "\uff1a"]
                                    }), Object(a.jsx)("span", {
                                        children: g()
                                    })]
                                }), "singleSearch" !== u.timeSearchType.type && Object(a.jsx)(he, {
                                    alertPop: n
                                })]
                            })]
                        })
                    })
                },
                Oe = t(23),
                Te = t(21),
                De = t(22),
                we = t.n(De);

            function Se() {
                var e = Object(D.a)(["\n  position: fixed;\n  right:0;\n  bottom:30px;\n  width:40px;\n  height:40px;\n  background-color: ", ";\n  border-radius:5px 0 0 5px;\n  box-shadow:0 3px 5px rgba(0,0,0,0.2);\n  z-index:2;\n  -webkit-tap-highlight-color:transparent;\n  cursor: pointer;\n  svg {\n    position: absolute;\n    top:50%;\n    left:50%;\n    transform:translate(-50%,-50%);\n    color: ", ";\n    width:50%;\n    height:50%;\n  }\n  ", "\n  ", "\n"]);
                return Se = function() {
                    return e
                }, e
            }
            var Ie = S.a.div(Se(), G.getIn(["main", "selected"]), G.getIn(["main", "bodyBackground"]), (function(e) {
                    return e.RWD.isTablet && "\n    bottom:45px;\n    width:45px;\n    height:45px;\n  "
                }), (function(e) {
                    var n = e.RWD;
                    return !n.isMobile && !n.isTablet && "\n    bottom:55px;\n    width:45px;\n    height:45px;\n  "
                })),
                ke = function(e) {
                    var n = e.bodyRef,
                        t = void 0 === n ? null : n,
                        i = e.isMobile,
                        r = void 0 === i || i,
                        o = e.isTablet,
                        s = void 0 !== o && o;
                    return Object(a.jsx)(Ie, {
                        className: "ga_gotop",
                        RWD: {
                            isMobile: r,
                            isTablet: s
                        },
                        onClick: function() {
                            var e = setInterval((function() {
                                var n = !r && !s ? 70 : 110,
                                    a = Math.floor(t.current.state.scrollOffset / n),
                                    i = Math.floor(a / 3);
                                t.current.scrollToItem(i, "auto"), i <= 0 && clearInterval(e)
                            }), 30)
                        },
                        children: Object(a.jsx)(de.d, {
                            className: "ga_gotop"
                        })
                    })
                };

            function Ce() {
                var e = Object(D.a)(["\n  padding-bottom:30px;\n  .container {\n    display:flex;\n    width:100%;\n    align-items:center;\n    justify-content:center;\n    .btn {\n      position: relative;\n      width:40px;\n      height:40px;\n      background-color:", ";\n      svg {\n        position: absolute;\n        top:50%;\n        left:50%;\n        transform:translate(-50%,-50%);\n        width:50%;\n        height:50%;\n        fill:", ";\n      }\n      &.prev {\n        border-radius:5px 0 0 5px;\n      }\n      &.next {\n        border-radius:0 5px 5px 0;\n      }\n      &.isAble {\n        svg {\n          fill:#fff;\n        }\n      }\n    }\n    .ex-pages {\n      display:flex;\n      align-items:center;\n      justify-content:center;\n      height:40px;\n      padding:0 15px;\n      margin:0 1px;\n      background-color:", ";\n      span {\n        font-size:14px;\n        color:#fff;\n        &:nth-child(1) {\n          color: ", ";\n        }\n        &:nth-child(2) {\n          margin:0 10px;\n        }\n      }\n    }\n  }\n  ", "\n"]);
                return Ce = function() {
                    return e
                }, e
            }
            var ze = S.a.div(Ce(), G.getIn(["main", "button"]), G.getIn(["main", "text"]), G.getIn(["main", "button"]), G.getIn(["main", "selected"]), (function(e) {
                    return !e.RWD.isMobile && "\n    padding-bottom:35px;\n    .container {\n      .btn {\n        width:45px;\n        height:45px;\n        svg { \n          width:40%;\n          height:40%;\n        }\n        &.isAble {\n          cursor: pointer;\n        }\n      }\n      .ex-pages { \n        height:45px;\n        span {\n          font-size:15px;\n        }\n      }\n    }\n  "
                })),
                Ne = function() {
                    var e = Object(i.useContext)(kn),
                        n = Object(T.a)(e, 2),
                        t = n[0],
                        r = n[1],
                        o = 1 === t.pagenations.current,
                        s = t.pagenations.current === t.pagenations.total;
                    return Object(a.jsx)(ze, {
                        RWD: {
                            isMobile: t.isMobile,
                            isTablet: t.isTablet
                        },
                        children: Object(a.jsxs)("div", {
                            className: "container",
                            children: [Object(a.jsx)("div", {
                                className: le()("btn prev", {
                                    isAble: !o
                                }),
                                onClick: function() {
                                    o || (r({
                                        type: "setDataRenderDone",
                                        payload: {
                                            status: !1
                                        }
                                    }), r({
                                        type: "setCurrentOffset",
                                        payload: {
                                            number: "".concat(Number(t.currentOffset) - Number(t.currentCount))
                                        }
                                    }))
                                },
                                children: Object(a.jsx)(de.a, {})
                            }), Object(a.jsxs)("div", {
                                className: "ex-pages",
                                children: [Object(a.jsx)("span", {
                                    children: t.pagenations.current
                                }), Object(a.jsx)("span", {
                                    children: "/"
                                }), Object(a.jsx)("span", {
                                    children: t.pagenations.total
                                })]
                            }), Object(a.jsx)("div", {
                                className: le()("btn next", {
                                    isAble: !s
                                }),
                                onClick: function() {
                                    s || (r({
                                        type: "setDataRenderDone",
                                        payload: {
                                            status: !1
                                        }
                                    }), r({
                                        type: "setCurrentOffset",
                                        payload: {
                                            number: "".concat(Number(t.currentOffset) + Number(t.currentCount))
                                        }
                                    }))
                                },
                                children: Object(a.jsx)(de.c, {})
                            })]
                        })
                    })
                };

            function Me() {
                var e = Object(D.a)(["\n  @keyframes iconmark {\n    0% {\n      transform: translateY(0);\n    }\n    55% {\n      transform: translateY(-8%);\n    }\n    100% {\n      transform: translateY(0);\n    }\n  }\n  @keyframes iconshadow {\n    0% {\n      opacity: 0.8;\n      transform: scale(1);\n    }\n    25% {\n      opacity: 0.3;\n      transform: scale(0.6);\n    }\n    55% {\n      opacity: 0;\n      transform: scale(0.3);\n    }\n    75% {\n      opacity: 0.3;\n      transform: scale(0.6);\n    }\n    100% {\n      opacity: 0.8;\n      transform: scale(1);\n    }\n  }\n  position: fixed;\n  right: 15px;\n  bottom: 100px;\n  z-index: 100;\n  cursor: pointer;\n  .btn {\n    position: relative;\n    width: 60px;\n    height: 60px;\n  }\n  img {\n    width: 100%;\n    position: absolute;\n    top: 0;\n    left: 0;\n  }\n  .mark {\n    transform: translateY(0);\n    animation: iconmark 2s linear infinite;\n    /* z-index: 3; */\n  }\n  .shadow {\n    opacity: 0.8;\n    animation: iconshadow 2s linear;\n    /* z-index: 2; */\n  }\n  ", "\n  ", "\n"]);
                return Me = function() {
                    return e
                }, e
            }
            var Be = S.a.div(Me(), (function(e) {
                return e.RWD.isTablet && "\n    .btn {\n      width: 80px;\n      height: 80px;\n    }\n  "
            }), (function(e) {
                var n = e.RWD;
                return !n.isMobile && !n.isTablet && "\n  "
            }));

            function Le(e) {
                var n = e.isMobile,
                    t = void 0 === n || n,
                    r = e.isTablet,
                    o = void 0 !== r && r,
                    s = Object(i.useContext)(kn),
                    c = Object(T.a)(s, 2),
                    l = c[0],
                    d = c[1];
                return Object(a.jsx)(Be, {
                    className: "ga_anti_fake",
                    RWD: {
                        isMobile: t,
                        isTablet: o
                    },
                    onClick: function() {
                        return d({
                            type: "setIdentifyPopup",
                            payload: !l.identifyPopup
                        })
                    },
                    children: Object(a.jsxs)("div", {
                        className: "btn",
                        children: [Object(a.jsx)("img", {
                            src: "/cq9history/id-bg.png",
                            alt: ""
                        }), Object(a.jsx)("img", {
                            className: "shadow",
                            src: "\n          /cq9history/id-shadow.png",
                            alt: ""
                        }), Object(a.jsx)("img", {
                            className: "mark",
                            src: "\n          /cq9history/id-mark.png",
                            alt: ""
                        })]
                    })
                })
            }

            function Pe() {
                var e = Object(D.a)(["\n  width: calc(100% - 40px);\n  margin: 0 auto;\n  height: calc(100vh - 200px);\n  .data-list {\n    box-sizing: border-box;\n    padding: 15px 17px;\n    border-radius: 5px;\n    background-color: ", ";\n    margin-bottom: 15px;\n    animation: 0.5s fade;\n  }\n  .layout {\n    &:last-child .data-list {\n      margin-bottom: 0;\n    }\n  }\n  .padd {\n    height: 25px;\n  }\n  .data-list .top-area {\n    position: relative;\n    display: flex;\n    align-items: flex-end;\n    padding-bottom: 10px;\n    border-bottom: 1px solid ", ";\n    .typeTag {\n      font-size: 12px;\n      color: #fff;\n      padding: 4px 8px;\n      border-radius: 2px;\n      margin-left: 20px;\n      &.freegame {\n        background-color: ", ";\n      }\n      &.bonus {\n        background-color: ", ";\n      }\n      &.singlerowbet {\n        background-color: ", ";\n      }\n    }\n    .svg-IoIosArrowForward {\n      position: absolute;\n      right: 5px;\n      top: calc(50% - 5px);\n      transform: translateY(-50%);\n      width: 20px;\n      height: 20px;\n      fill: #fff;\n    }\n  }\n  .data-list .top-area .captions {\n    .main-title {\n      color: #fff;\n      font-size: 15px;\n    }\n    .date {\n      color: ", ";\n      font-size: 12px;\n    }\n  }\n  .data-list .bottom-area {\n    display: flex;\n    align-items: center;\n    justify-content: space-between;\n    padding-top: 10px;\n    .roundid {\n      font-size: 14px;\n      color: #fff;\n    }\n    .wins {\n      color: #fff;\n      font-size: 16px;\n      &.isWin {\n        color: ", ";\n      }\n    }\n  }\n  .norecord {\n    padding-top: 45px;\n    text-align: center;\n    font-size: 16px;\n    color: ", ";\n  }\n  ", ";\n"]);
                return Pe = function() {
                    return e
                }, e
            }
            var Re = S.a.div(Pe(), G.getIn(["main", "listBackground"]), G.getIn(["main", "border"]), G.getIn(["accent", "blue"]), G.getIn(["accent", "purple"]), G.getIn(["accent", "green"]), G.getIn(["main", "text"]), G.getIn(["accent", "green"]), G.getIn(["main", "text"]), (function(e) {
                    return e.RWD.isTablet && "\n    width:calc(100% - 70px);\n    .data-list { \n      padding:15px 25px;\n      margin-bottom:25px;\n    }\n    .layout {\n      &:last-child .data-list{\n        margin-bottom:0;\n      }\n    }\n    .padd { height:35px; }\n    .data-list .top-area {\n      padding-bottom: 16px;\n    }\n    .data-list .top-area .svg-IoIosArrowForward {\n      width:22px;\n      height:22px;\n    }\n    .data-list .top-area .captions {\n      .main-title {\n        font-size:16px;\n      }\n      .date {\n        padding-top:2px;\n        font-size:13px;\n      }\n    }\n    .data-list .bottom-area {\n      padding-top:12px;\n      .roundid { \n        font-size:15px;\n      }\n      .wins {\n        font-size:18px;\n      }\n    }\n  "
                })),
                _e = function(e) {
                    var n = e.filterNameset,
                        t = e.timeShowFilter,
                        r = e.filterTypeTag,
                        o = e.filterCA01Type,
                        s = Object(i.useRef)(null),
                        c = Object(i.useState)(!1),
                        l = Object(T.a)(c, 2),
                        d = l[0],
                        u = l[1],
                        p = Object(i.useContext)(kn),
                        b = Object(T.a)(p, 2),
                        g = b[0],
                        f = b[1],
                        h = function(e) {
                            var i = e.index,
                                s = e.style,
                                c = e.data,
                                l = i + 1 === g.showDataList.size;
                            return Object(a.jsxs)("div", {
                                className: "layout",
                                style: s,
                                children: [Object(a.jsxs)("div", {
                                    className: "data-list",
                                    onClick: function() {
                                        F({
                                            getGameToken: g.getGameToken,
                                            roundid: c.getIn([i, "roundid"]),
                                            langType: g.langType,
                                            gamecode: c.getIn([i, "gamecode"])
                                        })
                                    },
                                    children: [Object(a.jsxs)("div", {
                                        className: "top-area",
                                        children: [Object(a.jsxs)("div", {
                                            className: "captions",
                                            children: [Object(a.jsxs)("div", {
                                                className: "main-title",
                                                children: [n(c.getIn([i, "nameset"]), c.getIn([i, "gamename"])), "" !== c.getIn([i, "tabletype"]) && o(c.getIn([i, "tabletype"]))]
                                            }), Object(a.jsx)("div", {
                                                className: "date",
                                                children: t(c.getIn([i, "createtime"]))
                                            })]
                                        }), r(c.getIn([i, "detail"])), Object(a.jsx)(de.c, {
                                            className: "svg-IoIosArrowForward"
                                        })]
                                    }), Object(a.jsxs)("div", {
                                        className: "bottom-area",
                                        children: [Object(a.jsx)("div", {
                                            className: "roundid",
                                            children: c.getIn([i, "roundid"])
                                        }), Object(a.jsxs)("div", {
                                            className: le()("wins", {
                                                isWin: c.getIn([i, "wins"]) >= 0
                                            }),
                                            children: [c.getIn([i, "wins"]) > 0 && "+", we()(c.getIn([i, "wins"])).format("0.00")]
                                        })]
                                    })]
                                }), l && Object(a.jsx)("div", {
                                    className: "padd"
                                }), g.pagenations.status && l && Object(a.jsx)(Ne, {})]
                            })
                        };
                    return Object(i.useEffect)((function() {
                        f({
                            type: "setDataRenderDone",
                            payload: {
                                status: !0
                            }
                        })
                    }), [f]), Object(a.jsxs)(Re, {
                        RWD: {
                            isMobile: g.isMobile,
                            isTablet: g.isTablet
                        },
                        children: [g.dataRenderDone && g.showDataList.size > 0 && Object(a.jsx)(a.Fragment, {
                            children: Object(a.jsx)(Te.a, {
                                children: function(e) {
                                    var n = e.height,
                                        t = e.width;
                                    return Object(a.jsx)(Oe.a, {
                                        ref: s,
                                        height: n,
                                        width: t,
                                        itemCount: g.showDataList.size,
                                        itemData: g.showDataList,
                                        itemSize: g.isTablet ? 151 : 126,
                                        onScroll: function(e) {
                                            e.scrollOffset > 0 ? u(!0) : u(!1)
                                        },
                                        children: h
                                    })
                                }
                            })
                        }), g.dataRenderDone && 0 === g.showDataList.size && Object(a.jsx)("div", {
                            className: "norecord",
                            children: H.getIn([g.langType, "dataList", "norecord"])
                        }), Object(a.jsx)(Le, {
                            isMobile: g.isMobile,
                            isTablet: g.isTablet
                        }), d && Object(a.jsx)(ke, {
                            bodyRef: s,
                            isMobile: g.isMobile,
                            isTablet: g.isTablet
                        })]
                    })
                };

            function Ze() {
                var e = Object(D.a)(["\n  position: relative;\n  -webkit-overflow-scrolling: touch;\n  height: calc(100vh - 245px);\n  min-height: 555px;\n  width: 100%;\n  max-width: 1140px;\n  min-width: 800px;\n  padding: 0 20px;\n  margin: 0 auto;\n  box-sizing: border-box;\n  ", "\n  .data-list {\n    display: flex;\n    align-items: center;\n    width: 100%;\n    height: 70px;\n    background-color: ", ";\n    box-sizing: border-box;\n    border-bottom: 1px solid ", ";\n    animation: 0.5s fade;\n    cursor: pointer;\n    &:hover {\n      background-color: ", ";\n    }\n    .main-title {\n      width: 185px;\n      color: #fff;\n      font-size: 16px;\n      box-sizing: border-box;\n      padding-left: 40px;\n    }\n    .date {\n      text-align: center;\n      width: 170px;\n      color: ", ";\n      font-size: 15px;\n    }\n    .bonus-tag {\n      width: 200px;\n      padding-left: 65px;\n      box-sizing: border-box;\n      .typeTag {\n        display: inline-block;\n        font-size: 13px;\n        color: #fff;\n        padding: 5px 9px;\n        border-radius: 3px;\n        &.freegame {\n          background-color: ", ";\n        }\n        &.bonus {\n          background-color: ", ";\n        }\n        &.singlerowbet {\n          background-color: ", ";\n        }\n      }\n    }\n    .roundid {\n      width: 280px;\n      font-size: 15px;\n      color: #fff;\n    }\n    .wins {\n      text-align: right;\n      width: 100px;\n      color: #fff;\n      font-size: 16px;\n      &.isWin {\n        color: ", ";\n      }\n    }\n    .arrow {\n      width: 125px;\n      box-sizing: border-box;\n      padding-right: 20px;\n      text-align: right;\n      .svg-IoIosArrowForward {\n        width: 20px;\n        height: 20px;\n        fill: #fff;\n      }\n    }\n  }\n  .padd {\n    height: 35px;\n  }\n  .layout {\n    &:nth-child(1) .data-list {\n      border-top-left-radius: 8px;\n      border-top-right-radius: 8px;\n    }\n    &:last-child .data-list {\n      border-bottom-left-radius: 8px;\n      border-bottom-right-radius: 8px;\n      border-bottom: none;\n    }\n  }\n  .norecord {\n    padding-top: 45px;\n    text-align: center;\n    font-size: 18px;\n    color: ", ";\n  }\n"]);
                return Ze = function() {
                    return e
                }, e
            }
            var Ye = S.a.div(Ze(), (function(e) {
                    var n = e.RWD,
                        t = e.isNoDataLayer;
                    return !n.isMobile && !n.isTablet && "\n    ".concat(t && "display:none", ";\n  ")
                }), G.getIn(["main", "listBackground"]), G.getIn(["other", "line"]), G.getIn(["main", "border"]), G.getIn(["main", "text"]), G.getIn(["accent", "blue"]), G.getIn(["accent", "purple"]), G.getIn(["accent", "green"]), G.getIn(["accent", "green"]), G.getIn(["main", "text"])),
                Ae = function(e) {
                    var n = e.filterNameset,
                        t = e.timeShowFilter,
                        r = e.filterTypeTag,
                        o = e.filterCA01Type,
                        s = Object(i.useRef)(null),
                        c = Object(i.useState)(!1),
                        l = Object(T.a)(c, 2),
                        d = l[0],
                        u = l[1],
                        p = Object(i.useContext)(kn),
                        b = Object(T.a)(p, 2),
                        g = b[0],
                        f = b[1],
                        h = function(e) {
                            var i = e.index,
                                s = e.style,
                                c = e.data,
                                l = i + 1 === g.showDataList.size;
                            return Object(a.jsxs)("div", {
                                className: "layout",
                                style: s,
                                children: [Object(a.jsxs)("div", {
                                    className: "data-list",
                                    onClick: function() {
                                        F({
                                            getGameToken: g.getGameToken,
                                            roundid: c.getIn([i, "roundid"]),
                                            langType: g.langType,
                                            gamecode: c.getIn([i, "gamecode"])
                                        })
                                    },
                                    children: [Object(a.jsxs)("div", {
                                        className: "main-title",
                                        children: [n(c.getIn([i, "nameset"]), c.getIn([i, "gamename"])), "" !== c.getIn([i, "tabletype"]) && o(c.getIn([i, "tabletype"]))]
                                    }), Object(a.jsx)("div", {
                                        className: "date",
                                        children: t(c.getIn([i, "createtime"]), !0)
                                    }), Object(a.jsx)("div", {
                                        className: "bonus-tag",
                                        children: r(c.getIn([i, "detail"]))
                                    }), Object(a.jsx)("div", {
                                        className: "roundid",
                                        children: c.getIn([i, "roundid"])
                                    }), Object(a.jsxs)("div", {
                                        className: le()("wins", {
                                            isWin: c.getIn([i, "wins"]) >= 0
                                        }),
                                        children: [c.getIn([i, "wins"]) > 0 && "+", we()(c.getIn([i, "wins"])).format("0.00")]
                                    }), Object(a.jsx)("div", {
                                        className: "arrow",
                                        children: Object(a.jsx)(de.c, {
                                            className: "svg-IoIosArrowForward"
                                        })
                                    })]
                                }), l && Object(a.jsx)("div", {
                                    className: "padd"
                                }), g.pagenations.status && l && Object(a.jsx)(Ne, {})]
                            })
                        };
                    return Object(i.useEffect)((function() {
                        f({
                            type: "setDataRenderDone",
                            payload: {
                                status: !0
                            }
                        })
                    }), [f]), Object(a.jsxs)(Ye, {
                        RWD: {
                            isMobile: g.isMobile,
                            isTablet: g.isTablet
                        },
                        isNoDataLayer: g.isNoDataLayer,
                        children: [g.dataRenderDone && g.showDataList.size > 0 && Object(a.jsx)(a.Fragment, {
                            children: Object(a.jsx)(Te.a, {
                                children: function(e) {
                                    var n = e.height,
                                        t = e.width;
                                    return Object(a.jsx)(Oe.a, {
                                        ref: s,
                                        height: n,
                                        width: t,
                                        itemCount: g.showDataList.size,
                                        itemData: g.showDataList,
                                        itemSize: 70,
                                        onScroll: function(e) {
                                            e.scrollOffset > 0 ? u(!0) : u(!1)
                                        },
                                        children: h
                                    })
                                }
                            })
                        }), g.dataRenderDone && 0 === g.showDataList.size && Object(a.jsx)("div", {
                            className: "norecord",
                            children: H.getIn([g.langType, "dataList", "norecord"])
                        }), Object(a.jsx)(Le, {
                            isMobile: g.isMobile,
                            isTablet: g.isTablet
                        }), d && Object(a.jsx)(ke, {
                            bodyRef: s,
                            isMobile: g.isMobile,
                            isTablet: g.isTablet
                        })]
                    })
                };

            function We() {
                var e = Object(D.a)(["\n  color: #f4c000;\n"]);
                return We = function() {
                    return e
                }, e
            }

            function Ee() {
                var e = Object(D.a)(["\n  ", "\n  ", "\n  ", "\n"]);
                return Ee = function() {
                    return e
                }, e
            }
            var Fe = S.a.div(Ee(), (function(e) {
                    return e.RWD.isMobile && "\n    padding-top:200px;\n  "
                }), (function(e) {
                    return e.RWD.isTablet && "\n    padding-top:220px;\n  "
                }), (function(e) {
                    var n = e.RWD;
                    return !n.isMobile && !n.isTablet && "\n    padding-top:0px;\n  "
                })),
                Ge = S.a.span(We()),
                qe = function() {
                    var e = Object(i.useContext)(kn),
                        n = Object(T.a)(e, 1)[0],
                        t = function(e) {
                            var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : "",
                                a = e.filter((function(e) {
                                    return e.get("lang") === n.langType
                                })),
                                i = a.getIn([0, "name"]) ? a.getIn([0, "name"]) : t;
                            return i
                        },
                        r = function() {
                            var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : "",
                                n = "none";
                            switch (e) {
                                case "1":
                                    n = "\u767e\u5bb6\u4e50";
                                    break;
                                case "2":
                                    n = "\u8f6e\u76d8";
                                    break;
                                case "3":
                                    n = "\u9ab0\u5b9d";
                                    break;
                                case "4":
                                    n = "\u9f99\u864e"
                            }
                            return Object(a.jsxs)(a.Fragment, {
                                children: ["none" !== n && "-", "none" !== n && Object(a.jsx)(Ge, {
                                    children: n
                                })]
                            })
                        },
                        o = function(e) {
                            var t = arguments.length > 1 && void 0 !== arguments[1] && arguments[1],
                                a = e.split("T"),
                                i = a[1].split("-"),
                                r = g()("".concat(a[0], " ").concat(i[0])),
                                o = g()(r).add(n.currentTZ, "hours").format("YYYY-MM-DD HH:mm:ss"),
                                s = g()(r).add(n.currentTZ, "hours").format("YYYY/MM/DD HH:mm:ss");
                            return t ? s : o
                        },
                        s = function(e) {
                            return e.map((function(e, t) {
                                var i = Object.keys(e.toJS())[0];
                                return "freegame" === i && e.get(i) > 0 ? Object(a.jsx)("div", {
                                    className: "typeTag freegame",
                                    children: H.getIn([n.langType, "dataList", "freegame"])
                                }, t) : "bonus" === i && e.get(i) > 0 ? Object(a.jsx)("div", {
                                    className: "typeTag bonus",
                                    children: H.getIn([n.langType, "dataList", "bonus"])
                                }, t) : "singlerowbet" === i && e.get(i) > 0 ? Object(a.jsx)("div", {
                                    className: "typeTag singlerowbet",
                                    children: H.getIn([n.langType, "dataList", "singlerowbet"])
                                }, t) : null
                            }))
                        };
                    return Object(a.jsxs)(Fe, {
                        RWD: {
                            isMobile: n.isMobile,
                            isTablet: n.isTablet
                        },
                        children: [n.isMobile && Object(a.jsx)(_e, {
                            filterNameset: t,
                            timeShowFilter: o,
                            filterTypeTag: s,
                            filterCA01Type: r
                        }), !n.isMobile && !n.isTablet && Object(a.jsx)(Ae, {
                            filterNameset: t,
                            timeShowFilter: o,
                            filterTypeTag: s,
                            filterCA01Type: r
                        })]
                    })
                };

            function He() {
                var e = Object(D.a)(["\n  width:100%;\n  box-sizing:border-box;\n  padding:30px 25px 0 25px;\n  .title {\n    font-size:15px;\n    color:#fff;\n  }\n  .src {\n    position: relative;\n    display:flex;\n    align-items:center;\n    justify-content:center;\n    box-sizing:border-box;\n    border-radius:5px;\n    border:1px solid ", ";\n    background-color:", ";\n    height:45px;\n    margin-top:15px;\n    transition:.2s;\n    span {\n      color:#fff;\n      font-size:14px;\n      transition:.2s;\n    }\n    &.active {\n      border:1px solid ", ";\n      span {\n        color:", ";\n      }\n    }\n    &.blur {\n      border:1px solid transparent;\n      background-color:", ";\n      span {\n        color:", ";\n      }\n    }\n  }\n  .id-search {\n    margin-top:30px;\n  }\n  .id-search .btn {\n    text-align:center;\n    font-size:14px;\n    margin:15px 0;\n    padding:12px 0;\n    border-radius:5px;\n    color:", ";\n    background-color:", ";\n    transition:.2s;\n    &.focus {\n      color:", ";\n      background-color:", ";\n    }\n  }\n  .id-search label {\n    width:100%;\n    position: relative;\n  }\n  .svg-BiSearch {\n    position: absolute;\n    top:50%;\n    left:15px;\n    width:18px;\n    height:18px;\n    transform:translateY(-50%);\n    fill:", ";\n  }\n  .id-search .close {\n    opacity:0;\n    position: absolute;\n    top:50%;\n    right:15px;\n    width:18px;\n    height:18px;\n    transform:translateY(-50%);\n    border-radius:50%;\n    background-color:", ";\n    transition:.2s;\n    z-index:-1;\n    svg {\n      width:100%;\n      height:100%;\n      color:#fff;\n    }\n  }\n  .id-search input {\n    width:100%;\n    font-size:14px;\n    margin-top:15px;\n    padding:12px 40px;\n    box-sizing:border-box;\n    border-radius:5px;\n    outline:none;\n    background-color:", ";\n    border:1px solid ", ";\n    color:", ";\n    transition:.2s;\n    &::placeholder {\n      color:", ";\n    }\n    &:focus {\n      color:#fff;\n      border:1px solid #fff;\n      &::placeholder {\n        color:#fff;\n      }\n      ~.svg-BiSearch {\n        fill:#fff;\n      }\n      ~.close {\n        opacity:1;\n        z-index:2;\n      }\n    }\n  }\n  ", "\n"]);
                return He = function() {
                    return e
                }, e
            }
            var Ue = S.a.div(He(), G.getIn(["main", "listBackground"]), G.getIn(["main", "bodyBackground"]), G.getIn(["main", "selected"]), G.getIn(["main", "selected"]), G.getIn(["main", "listBackground"]), G.getIn(["main", "textDeep"]), G.getIn(["main", "text"]), G.getIn(["main", "button"]), G.getIn(["main", "bodyBackground"]), G.getIn(["main", "selected"]), G.getIn(["main", "textDeep"]), G.getIn(["main", "button"]), G.getIn(["main", "bodyBackground"]), G.getIn(["main", "listBackground"]), G.getIn(["main", "textDeep"]), G.getIn(["main", "textDeep"]), (function(e) {
                    return e.RWD.isTablet && "\n    padding:60px 0 0 0;\n    margin:0 auto;\n    width:320px;\n  "
                })),
                Ve = function(e) {
                    var n = e.props,
                        t = Object(i.useRef)(null),
                        r = Object(i.useContext)(kn),
                        o = Object(T.a)(r, 2),
                        s = o[0],
                        c = o[1],
                        l = Object(i.useState)(s.selectedDateSearch.type),
                        d = Object(T.a)(l, 2),
                        u = d[0],
                        p = d[1],
                        b = function(e) {
                            var t = e.type,
                                a = void 0 === t ? "" : t,
                                i = e.val,
                                r = void 0 === i ? 0 : i,
                                o = e.currentTZ,
                                l = void 0 === o ? 0 : o,
                                d = function(e) {
                                    var n = e.type,
                                        t = void 0 === n ? "" : n,
                                        a = e.currentTZ,
                                        i = void 0 === a ? 0 : a,
                                        r = {
                                            beginTime: "",
                                            endTime: ""
                                        };
                                    switch (t) {
                                        case "beforeThirty":
                                            r.beginTime = m(), r.endTime = h();
                                            break;
                                        case "today":
                                            r.beginTime = x(i), r.endTime = y(i);
                                            break;
                                        default:
                                            r.beginTime = m(), r.endTime = h()
                                    }
                                    return r
                                };
                            s.timeBusy ? n.alertPop({
                                text: H.getIn([s.langType, "alert", "busy"]),
                                color: G.getIn(["accent", "red"])
                            }) : (c({
                                type: "setTimeBusy",
                                payload: {
                                    status: !0
                                }
                            }), c({
                                type: "setIsNoDataLayer",
                                payload: {
                                    status: !1
                                }
                            }), c({
                                type: "setMSelectorPopup",
                                payload: {
                                    status: !1,
                                    type: "",
                                    selectedCusDate: 0
                                }
                            }), n.setDataUpdate({
                                beginTime: d({
                                    type: a,
                                    val: r,
                                    currentTZ: l
                                }).beginTime,
                                endTime: d({
                                    type: a,
                                    val: r,
                                    currentTZ: l
                                }).endTime,
                                type: a,
                                dateContent: r
                            }))
                        };
                    return Object(a.jsxs)(Ue, {
                        RWD: {
                            isMobile: s.isMobile,
                            isTablet: s.isTablet
                        },
                        children: [Object(a.jsxs)("div", {
                            className: "date-search",
                            children: [Object(a.jsx)("div", {
                                className: "title",
                                children: H.getIn([s.langType, "dateSearch"])
                            }), Object(a.jsx)("div", {
                                className: le()("src ga_quicksearch_30", {
                                    active: "beforeThirty" === s.selectedDateSearch.type
                                }, {
                                    blur: "blur" === s.selectedDateSearch.type
                                }),
                                onClick: function() {
                                    return b({
                                        type: "beforeThirty"
                                    })
                                },
                                children: Object(a.jsx)("span", {
                                    className: "ga_quicksearch_30",
                                    children: H.getIn([s.langType, "beforeThirty"])
                                })
                            }), Object(a.jsx)("div", {
                                className: le()("src ga_quicksearch_today", {
                                    active: "today" === s.selectedDateSearch.type
                                }, {
                                    blur: "blur" === s.selectedDateSearch.type
                                }),
                                onClick: function() {
                                    return b({
                                        type: "today",
                                        currentTZ: s.currentTZ
                                    })
                                },
                                children: Object(a.jsx)("span", {
                                    className: "ga_quicksearch_today",
                                    children: H.getIn([s.langType, "today"])
                                })
                            }), Object(a.jsx)("div", {
                                className: le()("src ga_quicksearch_custom", {
                                    active: "customized" === s.selectedDateSearch.type
                                }, {
                                    blur: "blur" === s.selectedDateSearch.type
                                }),
                                onClick: function() {
                                    return c({
                                        type: "setMSelectorPopup",
                                        payload: {
                                            status: !0,
                                            type: "customized",
                                            selectedCusDate: s.mSelectorPopup.selectedCusDate
                                        }
                                    })
                                },
                                children: Object(a.jsx)("span", {
                                    className: "ga_quicksearch_custom",
                                    children: H.getIn([s.langType, "customized"])
                                })
                            })]
                        }), Object(a.jsxs)("div", {
                            className: "id-search",
                            children: [Object(a.jsx)("div", {
                                className: "title",
                                children: H.getIn([s.langType, "numberSearch"])
                            }), Object(a.jsxs)("label", {
                                children: [Object(a.jsx)("input", {
                                    className: "input",
                                    ref: t,
                                    placeholder: H.getIn([s.langType, "searchPlaceholder"]),
                                    onFocus: function() {
                                        p(s.selectedDateSearch.type), c({
                                            type: "setSelectedDateSearch",
                                            payload: {
                                                type: "blur"
                                            }
                                        })
                                    },
                                    onBlur: function() {
                                        return c({
                                            type: "setSelectedDateSearch",
                                            payload: {
                                                type: u
                                            }
                                        })
                                    }
                                }), Object(a.jsx)(se.c, {
                                    className: "svg-BiSearch"
                                }), Object(a.jsx)("div", {
                                    className: "close",
                                    onClick: function() {
                                        t.current.value = ""
                                    },
                                    children: Object(a.jsx)(de.e, {})
                                })]
                            }), Object(a.jsx)("div", {
                                className: le()("btn ga_quicksearch_order", {
                                    focus: "blur" === s.selectedDateSearch.type
                                }),
                                onClick: function() {
                                    "" !== t.current.value && (s.timeBusy || s.dataList.isLoading ? n.alertPop({
                                        text: H.getIn([s.langType, "alert", "busy"]),
                                        color: G.getIn(["accent", "red"])
                                    }) : (c({
                                        type: "setIdSearch",
                                        payload: {
                                            timeBusy: !0,
                                            inputCurrentVal: t.current.value,
                                            dataRenderDone: !1,
                                            isNoDataLayer: !1,
                                            selectedDateSearch: "singleSearch",
                                            timeSearchType: {
                                                type: "singleSearch",
                                                content: s.timeSearchType.content
                                            }
                                        }
                                    }), A({
                                        props: {
                                            getGameToken: s.getGameToken,
                                            rounid: t.current.value,
                                            dispatch: c
                                        }
                                    }), t.current.value = ""))
                                },
                                children: H.getIn([s.langType, "btnSearch"])
                            })]
                        })]
                    })
                };

            function Je() {
                var e = Object(D.a)(["\n  position:fixed;\n  top:0;\n  left:0;\n  width:100%;\n  height:100vh;\n  overflow:auto;\n  background-color:", ";\n  z-index:100;\n  transform:translateX(100%);\n  transition:.2s;\n  &.active {\n    transform:translateX(0%);\n  }\n  header {\n    position: relative;\n    width:100%;\n    height:55px;\n    padding:15px 0;\n    box-sizing:border-box;\n    background-color:", ";\n    border-bottom:1px solid ", ";\n    .title {\n      font-size:17px;\n      color:#fff;\n      text-align:center;\n    }\n  }\n  header .go-back {\n    position:absolute;\n    width:30px;\n    height:30px;\n    top:50%;\n    left:25px;\n    transform:translateY(-50%);\n    .content {\n      position: relative;\n      width:100%;\n      height:100%;\n      background-color:", ";\n      border-radius:50%;\n    }\n    .svg-BiLeftArrowAlt {\n      position: absolute;\n      top:50%;\n      left:50%;\n      transform:translate(-50%,-50%);\n      fill:#fff;\n      width:20px;\n      height:20px;\n    }\n  }\n  ", ";\n"]);
                return Je = function() {
                    return e
                }, e
            }
            var Ke = S.a.div(Je(), G.getIn(["main", "bodyBackground"]), G.getIn(["main", "bodyBackground"]), G.getIn(["main", "border"]), G.getIn(["main", "button"]), (function(e) {
                    return e.RWD.isTablet && "\n    header {\n      height:75px;\n      padding:23px 0;\n      .title {\n        font-size:20px;\n      }\n    }\n    header .go-back {\n      left:30px;\n    }\n  "
                })),
                Xe = function(e) {
                    var n = e.alertPop,
                        t = Object(i.useContext)(kn),
                        r = Object(T.a)(t, 2),
                        o = r[0],
                        s = r[1];
                    return Object(a.jsxs)(Ke, {
                        className: le()({
                            active: o.mSearchPopup
                        }),
                        RWD: {
                            isMobile: o.isMobile,
                            isTablet: o.isTablet
                        },
                        children: [Object(a.jsxs)("header", {
                            children: [Object(a.jsx)("div", {
                                className: "go-back",
                                onClick: function() {
                                    return s({
                                        type: "setMSearchPopup",
                                        payload: {
                                            status: !1
                                        }
                                    })
                                },
                                children: Object(a.jsx)("div", {
                                    className: "content",
                                    children: Object(a.jsx)(se.b, {
                                        className: "svg-BiLeftArrowAlt"
                                    })
                                })
                            }), Object(a.jsx)("div", {
                                className: "title",
                                children: H.getIn([o.langType, "recordSearch"])
                            })]
                        }), Object(a.jsx)(Ve, {
                            props: {
                                alertPop: n,
                                setDataUpdate: function(e) {
                                    var n = e.beginTime,
                                        t = void 0 === n ? "" : n,
                                        a = e.endTime,
                                        i = void 0 === a ? "" : a,
                                        r = e.type,
                                        o = void 0 === r ? "" : r,
                                        c = e.dateContent;
                                    s({
                                        type: "mobileSearchPageDataUpdate",
                                        payload: {
                                            filterTypeType: "default",
                                            currentOffsetNumber: "0",
                                            sendTimeZoneBegin: t,
                                            sendTimeZoneEnd: i,
                                            timeSearchTypeType: o,
                                            timeSearchTypeContent: void 0 === c ? "" : c,
                                            mSearchPopupStatus: !1,
                                            dataRenderDoneStatus: !1,
                                            selectedDateSearchType: o
                                        }
                                    })
                                }
                            }
                        })]
                    })
                },
                $e = function(e) {
                    var n = e.props,
                        t = Object(i.useContext)(kn),
                        r = Object(T.a)(t, 2),
                        o = r[0],
                        s = r[1],
                        c = n.alertPop,
                        l = n.setCallAlertStatus,
                        d = n.StyledSelector,
                        u = H.getIn([o.langType, "timeZone"]),
                        p = H.getIn([o.langType, "timeZone"]).filter((function(e) {
                            return e.get("value") === o.currentTZ
                        })),
                        b = function(e) {
                            switch (e) {
                                case "0":
                                    return "ga_timezone_eastern";
                                case "12":
                                    return "ga_timezone_beijing";
                                case "4":
                                    return "ga_timezone_london"
                            }
                        };
                    return Object(a.jsx)(d, {
                        children: u.map((function(e, n) {
                            return Object(a.jsxs)("div", {
                                className: le()("list-style ".concat(b(e.get("value"))), {
                                    active: p.getIn([0, "value"]) === e.get("value")
                                }),
                                onClick: function() {
                                    if (o.timeBusy || o.dataList.isLoading) c({
                                        text: H.getIn([o.langType, "alert", "busy"]),
                                        color: G.getIn(["accent", "red"])
                                    });
                                    else {
                                        if (s({
                                                type: "setTimeBusy",
                                                payload: {
                                                    status: !0
                                                }
                                            }), s({
                                                type: "setDataRenderDone",
                                                payload: {
                                                    status: !1
                                                }
                                            }), "singleSearch" === o.timeSearchType.type) return s({
                                            type: "setStaticCurrentTZ",
                                            payload: {
                                                number: e.get("value")
                                            }
                                        }), void l(!0);
                                        var n = function() {
                                            var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : 0,
                                                n = {
                                                    beginTime: "",
                                                    endTime: ""
                                                };
                                            switch (o.timeSearchType.type) {
                                                case "beforeThirty":
                                                    n.beginTime = o.sendTimeZone.begin, n.endTime = o.sendTimeZone.end;
                                                    break;
                                                case "today":
                                                    n.beginTime = x(e), n.endTime = y(e);
                                                    break;
                                                case "customized":
                                                    n.beginTime = j(o.customizedBeforeDay, e), n.endTime = v(o.customizedBeforeDay, e);
                                                    break;
                                                default:
                                                    n.beginTime = o.sendTimeZone.begin, n.endTime = o.sendTimeZone.end
                                            }
                                            return n
                                        };
                                        l(!0), s({
                                            type: "setCurrentTZ",
                                            payload: {
                                                number: e.get("value"),
                                                beginTime: n(e.get("value")).beginTime,
                                                endTime: n(e.get("value")).endTime
                                            }
                                        }), s({
                                            type: "setMSelectorPopup",
                                            payload: {
                                                status: !1,
                                                type: "",
                                                selectedCusDate: o.mSelectorPopup.selectedCusDate
                                            }
                                        })
                                    }
                                },
                                children: [Object(a.jsx)("div", {
                                    className: "text ".concat(b(e.get("value"))),
                                    children: e.get("label")
                                }), Object(a.jsx)("div", {
                                    className: "circle ".concat(b(e.get("value")))
                                })]
                            }, n)
                        }))
                    })
                },
                Qe = function(e) {
                    var n = e.props,
                        t = Object(i.useContext)(kn),
                        r = Object(T.a)(t, 2),
                        o = r[0],
                        s = r[1],
                        c = n.alertPop,
                        l = n.StyledSelector,
                        d = H.getIn([o.langType, "cusDate"]).filter((function(e, n) {
                            return 0 !== n
                        }));
                    return Object(a.jsx)(l, {
                        children: d.map((function(e, n) {
                            var t = "".concat(e.get("label"), " ").concat(O(e.get("value"), o.currentTZ));
                            return Object(a.jsxs)("div", {
                                className: le()("list-style ga_customdate_".concat(e.get("value")), {
                                    active: e.get("value") === o.mSelectorPopup.selectedCusDate
                                }),
                                onClick: function() {
                                    o.timeBusy ? c({
                                        text: H.getIn([o.langType, "alert", "busy"]),
                                        color: G.getIn(["accent", "red"])
                                    }) : (s({
                                        type: "setTimeBusy",
                                        payload: {
                                            status: !0
                                        }
                                    }), s({
                                        type: "setIsNoDataLayer",
                                        payload: {
                                            status: !1
                                        }
                                    }), s({
                                        type: "mobileSearchPageDataUpdate",
                                        payload: {
                                            filterTypeType: "default",
                                            currentOffsetNumber: "0",
                                            sendTimeZoneBegin: j(e.get("value"), o.currentTZ),
                                            sendTimeZoneEnd: v(e.get("value"), o.currentTZ),
                                            timeSearchTypeType: "customized",
                                            timeSearchTypeContent: e.get("value"),
                                            mSearchPopupStatus: !1,
                                            dataRenderDoneStatus: !1,
                                            selectedDateSearchType: "customized"
                                        }
                                    }), s({
                                        type: "setMSelectorPopup",
                                        payload: {
                                            status: !1,
                                            type: "",
                                            selectedCusDate: e.get("value")
                                        }
                                    }))
                                },
                                children: [Object(a.jsx)("div", {
                                    className: "text ga_customdate_".concat(e.get("value")),
                                    children: t
                                }), Object(a.jsx)("div", {
                                    className: "circle ga_customdate_".concat(e.get("value"))
                                })]
                            }, n)
                        }))
                    })
                },
                en = function(e) {
                    var n = e.props,
                        t = Object(i.useContext)(kn),
                        r = Object(T.a)(t, 2),
                        o = r[0],
                        s = r[1],
                        c = n.alertPop,
                        l = n.StyledSelector;
                    return Object(a.jsx)(l, {
                        children: ["300", "500", "800", "1000"].map((function(e, n) {
                            return Object(a.jsxs)("div", {
                                className: le()("list-style ga_displaynum_".concat(e), {
                                    active: o.currentCount === e
                                }),
                                onClick: function() {
                                    o.timeBusy || o.dataList.isLoading ? (c({
                                        text: H.getIn([o.langType, "alert", "busy"]),
                                        color: G.getIn(["accent", "red"])
                                    }), s({
                                        type: "setCurrentCount",
                                        payload: {
                                            number: o.currentCount
                                        }
                                    })) : (s({
                                        type: "setStatusSelected",
                                        payload: {
                                            dataRenderDone: !1,
                                            timeBusy: !0,
                                            currentCount: e,
                                            currentOffset: "0"
                                        }
                                    }), s({
                                        type: "setMSelectorPopup",
                                        payload: {
                                            status: !1,
                                            type: "",
                                            selectedCusDate: o.mSelectorPopup.selectedCusDate
                                        }
                                    }))
                                },
                                children: [Object(a.jsx)("div", {
                                    className: "text ga_displaynum_".concat(e),
                                    children: e
                                }), Object(a.jsx)("div", {
                                    className: "circle ga_displaynum_".concat(e)
                                })]
                            }, n)
                        }))
                    })
                };

            function nn() {
                var e = Object(D.a)(["\n  position: absolute;\n  top:50%;\n  left:50%;\n  transform:translate(-50%,-50%);\n  z-index:2;\n  width:90%;\n  max-width:320px;\n  margin:0 auto;\n  .list-style {\n    display:flex;\n    align-items:center;\n    justify-content:space-between;\n    background-color:#fff;\n    border-bottom:1px solid #d0d1d7;\n    box-sizing:border-box;\n    padding:13px 15px;\n    &:nth-child(1) {\n      border-top-left-radius:5px;\n      border-top-right-radius:5px;\n    }\n    &:last-child {\n      border-bottom-left-radius:5px;\n      border-bottom-right-radius:5px;\n      border-bottom:none;\n    }\n    &.active {\n      .text {\n        color:#24292f;\n      }\n      .circle {\n        border: 1px solid #24292f;\n        &:before {\n          opacity:1;\n        }\n      }\n    }\n  }\n  .list-style .text {\n    font-size:15px;\n    color: #888e9e;\n  }\n  .list-style .circle {\n    position: relative;\n    border-radius:50px;\n    width:16px;\n    height:16px;\n    border: 1px solid #888e9e;\n    &:before {\n      content:'';\n      opacity:0;\n      position: absolute;\n      top:50%;\n      left:50%;\n      transform:translate(-50%,-50%);\n      width:10px;\n      height:10px;\n      border-radius:50px;\n      background-color:#24292f;\n    }\n  }\n"]);
                return nn = function() {
                    return e
                }, e
            }

            function tn() {
                var e = Object(D.a)(["\n  position:fixed;\n  z-index:101;\n  top:0;\n  width:100%;\n  height:100vh;\n  .background {\n    position: absolute;\n    z-index:1;\n    top:0;\n    left:0;\n    width:100%;\n    height:100%;\n    background:rgba(36, 40, 48, 0.9);\n  }\n"]);
                return tn = function() {
                    return e
                }, e
            }
            var an = S.a.div(tn()),
                rn = S.a.div(nn()),
                on = function(e) {
                    var n = e.alertPop,
                        t = e.setCallAlertStatus,
                        r = Object(i.useContext)(kn),
                        o = Object(T.a)(r, 2),
                        s = o[0],
                        c = o[1];
                    return Object(a.jsxs)(an, {
                        children: ["timeZone" === s.mSelectorPopup.type && Object(a.jsx)($e, {
                            props: {
                                alertPop: n,
                                setCallAlertStatus: t,
                                StyledSelector: rn
                            }
                        }), "customized" === s.mSelectorPopup.type && Object(a.jsx)(Qe, {
                            props: {
                                alertPop: n,
                                StyledSelector: rn
                            }
                        }), "pageCount" === s.mSelectorPopup.type && Object(a.jsx)(en, {
                            props: {
                                alertPop: n,
                                StyledSelector: rn
                            }
                        }), Object(a.jsx)("div", {
                            className: "background",
                            onClick: function() {
                                return c({
                                    type: "setMSelectorPopup",
                                    payload: {
                                        status: !1,
                                        type: "",
                                        selectedCusDate: s.mSelectorPopup.selectedCusDate
                                    }
                                })
                            }
                        })]
                    })
                };

            function sn() {
                var e = Object(D.a)(["\n  position: absolute;\n  top:calc(100% + 4px);\n  width:100%;\n  z-index:2;\n  li {\n    display:flex;\n    align-items:center;\n    justify-content:center;\n    width:100%;\n    height:44px;\n    background-color:#fff;\n    border-bottom:1px solid ", ";\n    transition:.1s;\n    .text {\n      position: relative;\n      font-size:15px;\n      color:", ";\n      .svg-BsCheck {\n        opacity:0;\n        position: absolute;\n        top:50%;\n        left:0;\n        width:18px;\n        height:18px;\n        transform: translate(calc(-100% - 12px),-50%);\n        fill:", ";\n      }\n    }\n    &:nth-child(1) {\n      border-radius:5px 5px 0 0;\n    }\n    &:last-child {\n      border-radius:0 0 5px 5px;\n    }\n    &:hover {\n      background-color: ", ";\n    }\n    &.active {\n      .text .svg-BsCheck {\n        opacity:1;\n      }\n    }\n  }\n"]);
                return sn = function() {
                    return e
                }, e
            }
            var cn = S.a.ul(sn(), G.getIn(["main", "selectedBorder"]), G.getIn(["main", "bodyBackground"]), G.getIn(["main", "bodyBackground"]), G.getIn(["main", "selected"])),
                ln = function(e) {
                    var n = e.props,
                        t = n.selectedCusDate,
                        r = n.onChangeFun,
                        o = Object(i.useContext)(kn),
                        s = Object(T.a)(o, 1)[0],
                        c = H.getIn([s.langType, "cusDate"]);
                    return Object(a.jsx)(cn, {
                        children: c.map((function(e, n) {
                            return 0 === n ? null : Object(a.jsx)("li", {
                                className: le()("ga_customdate_".concat(e.get("value")), {
                                    active: t === n
                                }),
                                onClick: function() {
                                    return r(n)
                                },
                                children: Object(a.jsxs)("div", {
                                    className: "text ga_customdate_".concat(e.get("value")),
                                    children: [Object(a.jsx)(oe.a, {
                                        className: "svg-BsCheck ga_customdate_".concat(e.get("value"))
                                    }), e.get("label"), " ", O(e.get("value"), s.currentTZ)]
                                })
                            }, n)
                        }))
                    })
                };

            function dn() {
                var e = Object(D.a)(["\n  margin-top:30px;\n  .idSearch-title {\n    font-size:15px;\n    color:#fff;\n  }\n  .btn {\n    text-align:center;\n    font-size:14px;\n    margin-top:15px;\n    padding:12px 0;\n    border-radius:5px;\n    color:", ";\n    background-color:", ";\n    transition:.2s;\n    &.focus {\n      color:", ";\n      background-color:", ";\n      cursor: pointer;\n    }\n  }\n  label {\n    width:100%;\n    position: relative;\n  }\n  .svg-BiSearch {\n    position: absolute;\n    top:50%;\n    left:15px;\n    width:18px;\n    height:18px;\n    transform:translateY(-50%);\n    fill:", ";\n  }\n  .close {\n    opacity:0;\n    position: absolute;\n    top:50%;\n    right:15px;\n    width:18px;\n    height:18px;\n    transform:translateY(-50%);\n    border-radius:50%;\n    background-color:", ";\n    transition:.2s;\n    cursor: pointer;\n    svg {\n      width:100%;\n      height:100%;\n      color:#fff;\n    }\n  }\n  .input {\n    width:100%;\n    font-size:14px;\n    margin-top:15px;\n    padding:12px 40px;\n    box-sizing:border-box;\n    border-radius:5px;\n    outline:none;\n    background-color:", ";\n    border:1px solid ", ";\n    color:", ";\n    transition:.2s;\n    &::placeholder {\n      color:", ";\n    }\n    &:focus {\n      color:#fff;\n      border:1px solid #fff;\n      &::placeholder {\n        color:#fff;\n      }\n      ~.svg-BiSearch {\n        fill:#fff;\n      }\n      ~.close {\n        opacity:1;\n      }\n    }\n  }\n"]);
                return dn = function() {
                    return e
                }, e
            }
            var un = S.a.div(dn(), G.getIn(["main", "text"]), G.getIn(["main", "button"]), G.getIn(["main", "bodyBackground"]), G.getIn(["main", "selected"]), G.getIn(["main", "textDeep"]), G.getIn(["main", "button"]), G.getIn(["main", "listBackground"]), G.getIn(["main", "buttonDarken"]), G.getIn(["main", "textDeep"]), G.getIn(["main", "textDeep"])),
                pn = function(e) {
                    var n = e.alertPop,
                        t = Object(i.useRef)(null),
                        r = Object(i.useContext)(kn),
                        o = Object(T.a)(r, 2),
                        s = o[0],
                        c = o[1],
                        l = Object(i.useState)(!1),
                        d = Object(T.a)(l, 2),
                        u = d[0],
                        p = d[1],
                        b = Object(i.useState)(s.selectedDateSearch.type),
                        g = Object(T.a)(b, 2),
                        f = g[0],
                        h = g[1];
                    return Object(a.jsxs)(un, {
                        children: [Object(a.jsx)("div", {
                            className: "idSearch-title",
                            children: H.getIn([s.langType, "numberSearch"])
                        }), Object(a.jsxs)("label", {
                            children: [Object(a.jsx)("input", {
                                className: "input",
                                ref: t,
                                placeholder: H.getIn([s.langType, "searchPlaceholder"]),
                                onFocus: function() {
                                    c({
                                        type: "setSelectedDateSearch",
                                        payload: {
                                            type: "blur"
                                        }
                                    }), "blur" !== s.selectedDateSearch.type && h(s.selectedDateSearch.type)
                                },
                                onBlur: function() {
                                    u ? t.current.focus() : c({
                                        type: "setSelectedDateSearch",
                                        payload: {
                                            type: f
                                        }
                                    })
                                }
                            }), Object(a.jsx)(se.c, {
                                className: "svg-BiSearch",
                                onMouseOut: function() {
                                    return p(!1)
                                },
                                onMouseOver: function() {
                                    return p(!0)
                                }
                            }), Object(a.jsx)("div", {
                                className: "close",
                                onMouseOut: function() {
                                    return p(!1)
                                },
                                onMouseOver: function() {
                                    return p(!0)
                                },
                                onClick: function() {
                                    t.current.value = ""
                                },
                                children: Object(a.jsx)(de.e, {})
                            })]
                        }), Object(a.jsx)("div", {
                            className: le()("btn ga_quicksearch_order", {
                                focus: "blur" === s.selectedDateSearch.type
                            }),
                            onClick: function() {
                                "" !== t.current.value && (s.timeBusy || s.dataList.isLoading ? n({
                                    text: H.getIn([s.langType, "alert", "busy"]),
                                    color: G.getIn(["accent", "red"])
                                }) : (c({
                                    type: "setIdSearch",
                                    payload: {
                                        timeBusy: !0,
                                        inputCurrentVal: t.current.value,
                                        dataRenderDone: !1,
                                        isNoDataLayer: !1,
                                        selectedDateSearch: "singleSearch",
                                        timeSearchType: {
                                            type: "singleSearch",
                                            content: s.timeSearchType.content
                                        }
                                    }
                                }), A({
                                    props: {
                                        getGameToken: s.getGameToken,
                                        rounid: t.current.value,
                                        dispatch: c
                                    }
                                }), t.current.value = ""))
                            },
                            children: H.getIn([s.langType, "btnSearch"])
                        })]
                    })
                };

            function bn() {
                var e = Object(D.a)(["\n  position: absolute;\n  width:calc(100% - 80px);\n  left:40px;\n  bottom:60px;\n  .main-content {\n    position: relative;\n    display:flex;\n    align-items:center;\n    justify-content:center;\n    width:100%;\n    height:50px;\n    border-radius:5px;\n    background-color: ", ";\n    cursor: pointer;\n    &.active {\n      background-color: ", ";\n      p { \n        color:", "; \n      }\n      .svg-BiGlobe, .svg-IoIosArrowDown { \n        fill:", ";\n      }\n    }\n  }\n  .main-content p {\n    color:#fff;\n    font-size:15px;\n  }\n  .svg-BiGlobe {\n    position: absolute;\n    top:50%;\n    left:20px;\n    fill:#fff;\n    width:17px;\n    height:17px;\n    transform:translateY(-50%);\n  }\n  .svg-IoIosArrowDown {\n    position: absolute;\n    top:50%;\n    right:20px;\n    fill:#fff;\n    width:17px;\n    height:17px;\n    transform:translateY(-50%);\n  }\n  .main-content .TZ-list {\n    position: absolute;\n    bottom:calc(100% + 2px);\n    left:0;\n    width:100%;\n    .list-style {\n      display:flex;\n      align-items:center;\n      justify-content:center;\n      height:45px;\n      background-color:#fff;\n      &:nth-child(1) {\n        border-top-left-radius:5px;\n        border-top-right-radius:5px;\n      }\n      &:last-child {\n        border-bottom-left-radius:5px;\n        border-bottom-right-radius:5px;\n      }\n      &:hover {\n        background-color:", ";\n      }\n      &.active span {\n        font-weight:bold;\n        .svg-BsCheck {\n          opacity:1;\n        }\n      }\n    }\n    .list-style span {\n      position: relative;\n      font-size:15px;\n      color:", ";\n      .svg-BsCheck { \n        opacity:0;\n        position: absolute;\n        top:50%;\n        left:0;\n        width:18px;\n        height:18px;\n        transform: translate(calc(-100% - 12px),-50%);\n        fill:", ";\n      }\n    }\n  }\n"]);
                return bn = function() {
                    return e
                }, e
            }
            var gn = S.a.div(bn(), G.getIn(["main", "button"]), G.getIn(["main", "selected"]), G.getIn(["main", "bodyBackground"]), G.getIn(["main", "bodyBackground"]), G.getIn(["main", "selected"]), G.getIn(["main", "bodyBackground"]), G.getIn(["main", "bodyBackground"])),
                fn = function(e) {
                    var n = e.alertPop,
                        t = Object(i.useContext)(kn),
                        r = Object(T.a)(t, 2),
                        o = r[0],
                        s = r[1],
                        c = Object(i.useState)(!1),
                        l = Object(T.a)(c, 2),
                        d = l[0],
                        u = l[1],
                        p = Object(i.useState)(!1),
                        b = Object(T.a)(p, 2),
                        g = b[0],
                        f = b[1],
                        h = H.getIn([o.langType, "timeZone"]).filter((function(e) {
                            return e.get("value") === o.currentTZ
                        })),
                        m = function(e) {
                            switch (e) {
                                case "0":
                                    return "ga_timezone_eastern";
                                case "12":
                                    return "ga_timezone_beijing";
                                case "4":
                                    return "ga_timezone_london"
                            }
                        };
                    return Object(i.useEffect)((function() {
                        g && o.dataRenderDone && (n({
                            text: H.getIn([o.langType, "alert", "TZchange"]),
                            color: G.getIn(["accent", "green"])
                        }), f(!1))
                    }), [g, o.dataRenderDone, o.langType, n]), Object(i.useEffect)((function() {
                        var e = function(e) {
                            var n = document.getElementById("TZchange");
                            n && !n.contains(e.target) && u(!1)
                        };
                        return document.addEventListener("mousedown", e),
                            function() {
                                document.removeEventListener("mousedown", e)
                            }
                    }), []), Object(a.jsx)(gn, {
                        className: "ga_timezone",
                        children: Object(a.jsxs)("div", {
                            className: le()("main-content ga_timezone", {
                                active: d
                            }),
                            onClick: function() {
                                return u((function(e) {
                                    return !e
                                }))
                            },
                            children: [Object(a.jsx)("p", {
                                className: "ga_timezone",
                                children: h.getIn([0, "label"])
                            }), Object(a.jsx)(se.a, {
                                className: "svg-BiGlobe ga_timezone"
                            }), Object(a.jsx)(de.b, {
                                className: "svg-IoIosArrowDown ga_timezone"
                            }), d && Object(a.jsx)("div", {
                                className: "TZ-list",
                                children: H.getIn([o.langType, "timeZone"]).map((function(e, t) {
                                    return Object(a.jsx)("div", {
                                        className: le()("list-style ".concat(m(e.get("value"))), {
                                            active: e.get("value") === h.getIn([0, "value"])
                                        }),
                                        onClick: function() {
                                            if (o.timeBusy || o.dataList.isLoading) n({
                                                text: H.getIn([o.langType, "alert", "busy"]),
                                                color: G.getIn(["accent", "red"])
                                            });
                                            else {
                                                if (s({
                                                        type: "setTimeBusy",
                                                        payload: {
                                                            status: !0
                                                        }
                                                    }), s({
                                                        type: "setDataRenderDone",
                                                        payload: {
                                                            status: !1
                                                        }
                                                    }), "singleSearch" === o.timeSearchType.type) return s({
                                                    type: "setStaticCurrentTZ",
                                                    payload: {
                                                        number: e.get("value")
                                                    }
                                                }), void f(!0);
                                                var t = function() {
                                                    var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : 0,
                                                        n = {
                                                            beginTime: "",
                                                            endTime: ""
                                                        };
                                                    switch (o.timeSearchType.type) {
                                                        case "beforeThirty":
                                                            n.beginTime = o.sendTimeZone.begin, n.endTime = o.sendTimeZone.end;
                                                            break;
                                                        case "today":
                                                            n.beginTime = x(e), n.endTime = y(e);
                                                            break;
                                                        case "customized":
                                                            n.beginTime = j(o.customizedBeforeDay, e), n.endTime = v(o.customizedBeforeDay, e);
                                                            break;
                                                        default:
                                                            n.beginTime = o.sendTimeZone.begin, n.endTime = o.sendTimeZone.end
                                                    }
                                                    return n
                                                };
                                                s({
                                                    type: "setCurrentTZ",
                                                    payload: {
                                                        number: e.get("value"),
                                                        beginTime: t(e.get("value")).beginTime,
                                                        endTime: t(e.get("value")).endTime
                                                    }
                                                }), f(!0)
                                            }
                                        },
                                        children: Object(a.jsxs)("span", {
                                            className: "".concat(m(e.get("value"))),
                                            children: [Object(a.jsx)(oe.a, {
                                                className: "svg-BsCheck ".concat(m(e.get("value")))
                                            }), e.get("label")]
                                        })
                                    }, t)
                                }))
                            })]
                        })
                    })
                };

            function hn() {
                var e = Object(D.a)(["\n  position: relative;\n  min-width:360px;\n  height:100vh;\n  min-height:800px;\n  z-index:100;\n  background-color: ", ";\n  box-sizing:border-box;\n  padding:0 40px;\n  .content {\n    width:100%;\n    padding-top:60px;\n  }\n  .date-search {\n    padding-top:60px;\n  }\n  .main-title {\n    text-align:center;\n    font-size:24px;\n    color:#fff;\n  }\n  .date-title {\n    font-size:15px;\n    color:#fff;\n  }\n  .src {\n    position: relative;\n    display:flex;\n    align-items:center;\n    justify-content:center;\n    box-sizing:border-box;\n    border-radius:5px;\n    border:1px solid ", ";\n    background-color:", ";\n    height:45px;\n    margin-top:15px;\n    transition:.2s;\n    cursor: pointer;\n    span {\n      color:#fff;\n      font-size:15px;\n      transition:.2s;\n    }\n    &.active {\n      border:1px solid ", ";\n      background-color:", ";\n      span {\n        color:", ";\n        font-weight:bold;\n      }\n    }\n    &.blur {\n      border:1px solid transparent;\n      background-color:", ";\n      span {\n        color:", ";\n      }\n    }\n    .cusDate-select {\n      position: absolute;\n      z-index:2;\n      width:100%;\n      height:100%;\n      opacity:0;\n    }\n  }\n"]);
                return hn = function() {
                    return e
                }, e
            }
            var mn = S.a.div(hn(), G.getIn(["main", "listBackground"]), G.getIn(["main", "border"]), G.getIn(["main", "listBackground"]), G.getIn(["main", "selected"]), G.getIn(["main", "selected"]), G.getIn(["main", "bodyBackground"]), G.getIn(["main", "buttonDarken"]), G.getIn(["main", "textDeep"])),
                xn = function(e) {
                    var n = e.alertPop,
                        t = Object(i.useContext)(kn),
                        r = Object(T.a)(t, 2),
                        o = r[0],
                        s = r[1],
                        c = Object(i.useState)(0),
                        l = Object(T.a)(c, 2),
                        d = l[0],
                        u = l[1],
                        p = Object(i.useState)(!1),
                        b = Object(T.a)(p, 2),
                        g = b[0],
                        f = b[1],
                        O = function(e) {
                            var t = e.type,
                                a = void 0 === t ? "" : t,
                                i = e.val,
                                r = void 0 === i ? 0 : i,
                                c = e.currentTZ,
                                l = void 0 === c ? 0 : c,
                                d = function(e) {
                                    var n = e.type,
                                        t = void 0 === n ? "" : n,
                                        a = e.val,
                                        i = void 0 === a ? 0 : a,
                                        r = e.currentTZ,
                                        o = void 0 === r ? 0 : r,
                                        s = {
                                            beginTime: "",
                                            endTime: ""
                                        };
                                    switch (t) {
                                        case "beforeThirty":
                                            s.beginTime = m(), s.endTime = h();
                                            break;
                                        case "today":
                                            s.beginTime = x(o), s.endTime = y(o);
                                            break;
                                        case "customized":
                                            s.beginTime = j(i, o), s.endTime = v(i, o);
                                            break;
                                        default:
                                            s.beginTime = m(), s.endTime = h()
                                    }
                                    return s
                                };
                            o.timeBusy || o.dataList.isLoading ? n({
                                text: H.getIn([o.langType, "alert", "busy"]),
                                color: G.getIn(["accent", "red"])
                            }) : (s({
                                type: "setTimeBusy",
                                payload: {
                                    status: !0
                                }
                            }), s({
                                type: "setIsNoDataLayer",
                                payload: {
                                    status: !1
                                }
                            }), s({
                                type: "setCustomizedBeforeDay",
                                payload: {
                                    day: r
                                }
                            }), function(e) {
                                var n = e.beginTime,
                                    t = void 0 === n ? "" : n,
                                    a = e.endTime,
                                    i = void 0 === a ? "" : a,
                                    r = e.type,
                                    o = void 0 === r ? "" : r,
                                    c = e.dateContent,
                                    l = void 0 === c ? "" : c,
                                    d = e.selectedCusDate,
                                    p = void 0 === d ? 0 : d;
                                s({
                                    type: "SearchDataUpdate",
                                    payload: {
                                        filterTypeType: "default",
                                        currentOffsetNumber: "0",
                                        sendTimeZoneBegin: t,
                                        sendTimeZoneEnd: i,
                                        timeSearchTypeType: o,
                                        timeSearchTypeContent: l,
                                        dataRenderDoneStatus: !1,
                                        selectedDateSearchType: o
                                    }
                                }), u(p)
                            }({
                                beginTime: d({
                                    type: a,
                                    val: r,
                                    currentTZ: l
                                }).beginTime,
                                endTime: d({
                                    type: a,
                                    val: r,
                                    currentTZ: l
                                }).endTime,
                                type: a,
                                dateContent: r,
                                selectedCusDate: r
                            }))
                        };
                    return Object(i.useEffect)((function() {
                        var e = function(e) {
                            var n = document.getElementById("dateSearch");
                            n && !n.contains(e.target) && f(!1)
                        };
                        return document.addEventListener("mousedown", e),
                            function() {
                                document.removeEventListener("mousedown", e)
                            }
                    }), []), Object(a.jsxs)(mn, {
                        children: [Object(a.jsxs)("div", {
                            className: "content",
                            children: [Object(a.jsx)("div", {
                                className: "main-title",
                                children: H.getIn([o.langType, "header", "title"])
                            }), Object(a.jsxs)("div", {
                                className: "date-search",
                                children: [Object(a.jsx)("div", {
                                    className: "date-title",
                                    children: H.getIn([o.langType, "dateSearch"])
                                }), Object(a.jsx)("div", {
                                    className: le()("src ga_quicksearch_30", {
                                        active: "beforeThirty" === o.selectedDateSearch.type
                                    }, {
                                        blur: "blur" === o.selectedDateSearch.type
                                    }),
                                    onClick: function() {
                                        return O({
                                            type: "beforeThirty"
                                        })
                                    },
                                    children: Object(a.jsx)("span", {
                                        className: "ga_quicksearch_30",
                                        children: H.getIn([o.langType, "beforeThirty"])
                                    })
                                }), Object(a.jsx)("div", {
                                    className: le()("src ga_quicksearch_today", {
                                        active: "today" === o.selectedDateSearch.type
                                    }, {
                                        blur: "blur" === o.selectedDateSearch.type
                                    }),
                                    onClick: function() {
                                        return O({
                                            type: "today",
                                            currentTZ: o.currentTZ
                                        })
                                    },
                                    children: Object(a.jsx)("span", {
                                        className: "ga_quicksearch_today",
                                        children: H.getIn([o.langType, "today"])
                                    })
                                }), Object(a.jsxs)("div", {
                                    className: le()("src ga_quicksearch_custom", {
                                        active: "customized" === o.selectedDateSearch.type
                                    }, {
                                        blur: "blur" === o.selectedDateSearch.type
                                    }),
                                    onClick: function() {
                                        return f((function(e) {
                                            return !e
                                        }))
                                    },
                                    children: [Object(a.jsx)("span", {
                                        className: "ga_quicksearch_custom",
                                        children: H.getIn([o.langType, "customized"])
                                    }), g && Object(a.jsx)(ln, {
                                        props: {
                                            selectedCusDate: d,
                                            onChangeFun: function(e) {
                                                return O({
                                                    type: "customized",
                                                    val: e,
                                                    currentTZ: o.currentTZ
                                                })
                                            }
                                        }
                                    })]
                                })]
                            }), Object(a.jsx)(pn, {
                                alertPop: n
                            })]
                        }), Object(a.jsx)(fn, {
                            alertPop: n
                        })]
                    })
                };

            function yn() {
                var e = Object(D.a)(["\n  position: fixed;\n  z-index: 101;\n  top: 0;\n  left: 0;\n  width: 100%;\n  height: 100vh;\n  background: rgba(0, 0, 0, 0.5);\n  .if-layout {\n    position: relative;\n    margin: 5vh auto;\n    width: 60vw;\n    height: 90vh;\n    background-color: #e5e5e5;\n    font-size: 0;\n    border-radius: 5px;\n    overflow: hidden;\n  }\n  .loader {\n    position: absolute;\n    left: 50%;\n    top: 50%;\n    transform: translate(-50%, -50%);\n  }\n  .close {\n    position: absolute;\n    top: 3.5%;\n    right: 5%;\n    width: 4.5vw;\n    height: 4.5vw;\n    cursor: pointer;\n  }\n  #website-identify {\n    width: 100%;\n    height: 100%;\n  }\n  ", "\n"]);
                return yn = function() {
                    return e
                }, e
            }
            var jn = S.a.div(yn(), (function(e) {
                return e.RWD.isMobile && "\n    .if-layout {\n      width:85vw;\n    }\n    .close {\n      width: 40px;\n      height: 40px;\n    }\n  "
            }));

            function vn(e) {
                var n = e.isMobile,
                    t = void 0 === n || n,
                    r = e.isTablet,
                    o = void 0 !== r && r,
                    s = Object(i.useContext)(kn),
                    c = Object(T.a)(s, 2),
                    l = c[0],
                    p = c[1],
                    b = Object(i.useState)(!1),
                    g = Object(T.a)(b, 2),
                    f = g[0],
                    h = g[1];
                return Object(a.jsx)(jn, {
                    RWD: {
                        isMobile: t,
                        isTablet: o
                    },
                    onClick: function() {
                        return p({
                            type: "setIdentifyPopup",
                            payload: !l.identifyPopup
                        })
                    },
                    children: Object(a.jsxs)("div", {
                        className: "if-layout",
                        children: [!f && Object(a.jsx)("div", {
                            className: "loader",
                            children: Object(a.jsx)(q.ClipLoader, {
                                size: 50,
                                color: "#f4c000"
                            })
                        }), f && Object(a.jsx)(se.d, {
                            className: "close",
                            onClick: function() {
                                return p({
                                    type: "setIdentifyPopup",
                                    payload: !l.identifyPopup
                                })
                            }
                        }), Object(a.jsx)("iframe", {
                            onLoad: function() {
                                return h(!0)
                            },
                            id: "website-identify",
                            title: "website identify",
                            src: "".concat(function() {
                                return window.location.protocol + "//".concat(window.location.host, "/verify");
                                // switch (function() {
                                //     var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : "";
                                //     return ~e.indexOf(":") || ~e.indexOf("rd3-dev") ? "dev" : ~e.indexOf("rd3-qa") ? "qa" : ~e.indexOf("rd3-int") || ~e.indexOf("detail.cqgame.games") ? "int" : "pord"
                                // }(window.location.host)) {
                                //     case "dev":
                                //         return "https://rd3-dev-verify.guardians.one/";
                                //     case "qa":
                                //         return "https://rd3-qa-verify.guardians.one/";
                                //     case "int":
                                //         return "https://rd3-int-verify.guardians.one/";
                                //     default:
                                //         return "https://verify.cq9site.com/"
                                // }
                            }(), "?token=").concat(d, "&lang=").concat(u, "&timezone=").concat(l.currentTZ)
                        })]
                    })
                })
            }

            function On() {
                var e = Object(D.a)(["\n  ", "\n"]);
                return On = function() {
                    return e
                }, e
            }

            function Tn() {
                var e = Object(D.a)(["\n  width: 100%;\n  min-height: 100vh;\n  @keyframes fade {\n    0% {\n      pointer-events: none;\n    }\n    99% {\n      pointer-events: none;\n    }\n    100% {\n      pointer-events: unset;\n    }\n  } ;\n"]);
                return Tn = function() {
                    return e
                }, e
            }
            var Dn = S.a.div(Tn()),
                wn = S.a.div(On(), (function(e) {
                    var n = e.RWD;
                    return !n.isMobile && !n.isTablet && "\n    display:flex;\n  "
                })),
                Sn = function() {
                    var e = Object(i.useContext)(kn),
                        n = Object(T.a)(e, 2),
                        t = n[0],
                        r = n[1],
                        o = Object(i.useState)(0),
                        s = Object(T.a)(o, 2),
                        c = s[0],
                        l = s[1],
                        d = Object(i.useState)(!1),
                        u = Object(T.a)(d, 2),
                        b = u[0],
                        g = u[1],
                        f = !t.isMobile && !t.isTablet,
                        h = f && 4006 === t.dataList.code,
                        m = function(e) {
                            var n = e.type,
                                t = void 0 === n ? "default" : n,
                                a = e.data,
                                i = void 0 === a ? Object(p.a)() : a;
                            switch (t) {
                                case "default":
                                    return i;
                                case "isWin":
                                    return i.filter((function(e) {
                                        return e.get("wins") > 0
                                    }));
                                case "isLose":
                                    return i.filter((function(e) {
                                        return e.get("wins") <= 0
                                    }));
                                default:
                                    return i
                            }
                        },
                        x = function(e) {
                            var n = e.text,
                                a = void 0 === n ? "" : n,
                                i = e.color;
                            r({
                                type: "setAlertText",
                                payload: {
                                    text: a,
                                    color: void 0 === i ? "" : i
                                }
                            }), r({
                                type: "setAlertArr",
                                payload: {
                                    data: t.alertArr.update((function(e) {
                                        return e.push(e.size)
                                    }))
                                }
                            }), l((function(e) {
                                return e + 1
                            }));
                            var o = setTimeout((function() {
                                return l((function(e) {
                                    return e - 1
                                })), clearTimeout(o)
                            }), 5e3)
                        };
                    return Object(i.useEffect)((function() {
                        var e = !!~window.location.href.indexOf("detail.tchl1935.com") ? "GTM-WGTJ98C" : "GTM-K6TNGCX";
                        document.getElementById("BODY_GA_NO_SCRIPT").innerHTML = '<iframe src="https://www.googletagmanager.com/ns.html?id='.concat(e, '"height="0" width="0" style="display:none;visibility:hidden"></iframe>')
                    }), []), Object(i.useEffect)((function() {
                        Y({
                            props: {
                                getGameToken: t.getGameToken,
                                beginTime: t.sendTimeZone.begin,
                                endTime: t.sendTimeZone.end,
                                offset: t.currentOffset,
                                count: t.currentCount,
                                dispatch: r
                            }
                        })
                    }), [t.getGameToken, t.sendTimeZone, t.currentOffset, t.currentCount, r]), Object(i.useEffect)((function() {
                        !t.dataList.isLoading && r({
                            type: "setShowDataList",
                            payload: {
                                data: m({
                                    type: t.filterType,
                                    data: t.dataList.data
                                })
                            }
                        })
                    }), [t.dataList.data, t.dataList.isLoading, t.filterType, r]), Object(i.useEffect)((function() {
                        var e;
                        (e = {
                            totalCount: t.dataList.totalCount,
                            currentCount: t.currentCount
                        }).totalCount / e.currentCount >= 1 ? r({
                            type: "setPagenations",
                            payload: {
                                status: !0,
                                current: Math.ceil(t.currentOffset / t.currentCount) + 1,
                                total: Math.ceil(t.dataList.totalCount / t.currentCount)
                            }
                        }) : r({
                            type: "setPagenations",
                            payload: {
                                status: !1,
                                current: 1,
                                total: 0
                            }
                        })
                    }), [t.dataList.totalCount, t.currentOffset, t.currentCount, r]), Object(i.useEffect)((function() {
                        if (t.timeBusy) var e = setTimeout((function() {
                            return r({
                                type: "setTimeBusy",
                                payload: {
                                    status: !1
                                }
                            }), clearTimeout(e)
                        }), 4e3)
                    }), [t.timeBusy, t.setTimeBusy, r]), Object(a.jsxs)(wn, {
                        RWD: {
                            isMobile: t.isMobile,
                            isTablet: t.isTablet
                        },
                        children: [f && Object(a.jsx)(xn, {
                            alertPop: x
                        }), t.identifyPopup && Object(a.jsx)(vn, {
                            RWD: {
                                isMobile: t.isMobile,
                                isTablet: t.isTablet
                            }
                        }), Object(a.jsxs)(Dn, {
                            "data-version": w.version,
                            children: [(!t.dataRenderDone && !f || h) && Object(a.jsx)(K, {
                                props: {
                                    langType: t.langType,
                                    code: t.dataList.code,
                                    isMobile: t.isMobile,
                                    isTablet: t.isTablet
                                }
                            }), f && Object(a.jsx)(ve, {
                                alertPop: x,
                                isDesktop: f
                            }), !t.dataRenderDone && f && Object(a.jsx)($, {
                                props: {
                                    langType: t.langType
                                }
                            }), !t.dataList.isLoading && Object(a.jsxs)(a.Fragment, {
                                children: [t.isMobile && Object(a.jsx)(ve, {
                                    alertPop: x,
                                    callAlertStatus: b,
                                    setCallAlertStatus: g
                                }), Object(a.jsx)(qe, {})]
                            }), t.isMobile && Object(a.jsx)(Xe, {
                                alertPop: x
                            }), t.isMobile && t.mSelectorPopup.status && Object(a.jsx)(on, {
                                alertPop: x,
                                setCallAlertStatus: g
                            }), t.isNoDataLayer && Object(a.jsx)(re, {
                                zIndex: 3
                            }), t.alertArr.map((function(e, n) {
                                return Object(a.jsx)(te, {
                                    alertArrCount: c,
                                    alertText: t.alertText
                                }, n)
                            }))]
                        })]
                    })
                },
                In = "zh-cn" !== u && "th" !== u && "en" !== u && "vn" !== u ? "en" : u;
            ! function(e) {
                var n = "";
                switch (e) {
                    case "zh-cn":
                        n = "\u73a9\u5bb6\u6ce8\u5355\u67e5\u8be2";
                        break;
                    case "th":
                        n = "\u0e01\u0e32\u0e23\u0e27\u0e34\u0e08\u0e31\u0e22\u0e01\u0e32\u0e23\u0e40\u0e14\u0e34\u0e21\u0e1e\u0e31\u0e19\u0e02\u0e2d\u0e07\u0e1c\u0e39\u0e49\u0e40\u0e25\u0e48\u0e19";
                        break;
                    case "vn":
                        n = "L\u1ecbch s\u1eed \u0111\u01a1n c\u01b0\u1ee3c";
                        break;
                    default:
                        n = "Player Betting Research"
                }
                document.title = n
            }(u);
            var kn = Object(i.createContext)(),
                Cn = {
                    identifyPopup: !1,
                    getGameToken: d,
                    langType: In,
                    isMobile: !l.a.desktop(),
                    isTablet: l.a.tablet(),
                    alertArr: Object(p.a)(),
                    alertText: {
                        text: "",
                        color: ""
                    },
                    currentTZ: "0",
                    currentCount: "300",
                    currentOffset: "0",
                    customizedBeforeDay: "0",
                    dataList: {
                        isLoading: !0,
                        data: Object(p.a)(),
                        code: "",
                        offset: Number(),
                        totalCount: Number()
                    },
                    dataRenderDone: !1,
                    filterType: "default",
                    sendTimeZone: {
                        begin: m(),
                        end: h()
                    },
                    showDataList: Object(p.a)(),
                    isNoDataLayer: !1,
                    inputCurrentVal: "",
                    pagenations: {
                        status: !1,
                        current: 1,
                        total: 0
                    },
                    timeBusy: !1,
                    timeSearchType: {
                        type: "beforeThirty",
                        content: ""
                    },
                    selectedDateSearch: {
                        type: "beforeThirty"
                    },
                    searchTypeCatch: {
                        type: "beforeThirty"
                    },
                    mSearchPopup: !1,
                    mSelectorPopup: {
                        status: !1,
                        type: "",
                        selectedCusDate: 0
                    }
                },
                zn = function(e, n) {
                    var t = n.payload;
                    switch (n.type) {
                        case "setIdentifyPopup":
                            return Object(c.a)(Object(c.a)({}, e), {}, {
                                identifyPopup: t
                            });
                        case "setAlertArr":
                            return Object(c.a)(Object(c.a)({}, e), {}, {
                                alertArr: t.data
                            });
                        case "setAlertText":
                            return Object(c.a)(Object(c.a)({}, e), {}, {
                                alertText: {
                                    text: t.text,
                                    color: t.color
                                }
                            });
                        case "setStaticCurrentTZ":
                            return Object(c.a)(Object(c.a)({}, e), {}, {
                                filterType: "default",
                                currentTZ: t.number,
                                dataRenderDone: !0
                            });
                        case "setCurrentTZ":
                            return Object(c.a)(Object(c.a)({}, e), {}, {
                                currentOffset: "0",
                                filterType: "default",
                                currentTZ: t.number,
                                sendTimeZone: {
                                    begin: t.beginTime,
                                    end: t.endTime
                                }
                            });
                        case "setCurrentCount":
                            return Object(c.a)(Object(c.a)({}, e), {}, {
                                currentCount: t.number
                            });
                        case "setCurrentOffset":
                            return Object(c.a)(Object(c.a)({}, e), {}, {
                                currentOffset: t.number
                            });
                        case "setDataList":
                            return Object(c.a)(Object(c.a)({}, e), {}, {
                                dataList: {
                                    isLoading: t.isLoading,
                                    data: t.data,
                                    code: t.code,
                                    offset: t.offset,
                                    totalCount: t.totalCount
                                }
                            });
                        case "setDataRenderDone":
                            return Object(c.a)(Object(c.a)({}, e), {}, {
                                dataRenderDone: t.status
                            });
                        case "setFilterType":
                            return Object(c.a)(Object(c.a)({}, e), {}, {
                                filterType: t.type
                            });
                        case "setPagenations":
                            return Object(c.a)(Object(c.a)({}, e), {}, {
                                pagenations: {
                                    status: t.status,
                                    current: t.current,
                                    total: t.total
                                }
                            });
                        case "setSendTimeZone":
                            return Object(c.a)(Object(c.a)({}, e), {}, {
                                sendTimeZone: {
                                    begin: t.begin,
                                    end: t.end
                                }
                            });
                        case "setShowDataList":
                            return Object(c.a)(Object(c.a)({}, e), {}, {
                                showDataList: t.data
                            });
                        case "setIsNoDataLayer":
                            return Object(c.a)(Object(c.a)({}, e), {}, {
                                isNoDataLayer: t.status,
                                selectedDateSearch: {
                                    type: e.searchTypeCatch.type
                                },
                                timeSearchType: {
                                    type: e.searchTypeCatch.type,
                                    content: e.timeSearchType.content
                                }
                            });
                        case "setInputCurrentVal":
                            return Object(c.a)(Object(c.a)({}, e), {}, {
                                inputCurrentVal: t.text
                            });
                        case "setTimeBusy":
                            return Object(c.a)(Object(c.a)({}, e), {}, {
                                timeBusy: t.status
                            });
                        case "setTimeSearchType":
                            return Object(c.a)(Object(c.a)({}, e), {}, {
                                timeSearchType: {
                                    type: t.type,
                                    content: t.content
                                }
                            });
                        case "setSelectedDateSearch":
                            return Object(c.a)(Object(c.a)({}, e), {}, {
                                selectedDateSearch: {
                                    type: t.type
                                }
                            });
                        case "setCustomizedBeforeDay":
                            return Object(c.a)(Object(c.a)({}, e), {}, {
                                customizedBeforeDay: t.day
                            });
                        case "setMSearchPopup":
                            return Object(c.a)(Object(c.a)({}, e), {}, {
                                mSearchPopup: t.status
                            });
                        case "setMSelectorPopup":
                            return Object(c.a)(Object(c.a)({}, e), {}, {
                                mSelectorPopup: {
                                    status: t.status,
                                    type: t.type,
                                    selectedCusDate: t.selectedCusDate
                                }
                            });
                        case "SearchDataUpdate":
                            return Object(c.a)(Object(c.a)({}, e), {}, {
                                filterType: t.filterTypeType,
                                currentOffset: t.currentOffsetNumber,
                                dataRenderDone: t.dataRenderDoneStatus,
                                sendTimeZone: {
                                    begin: t.sendTimeZoneBegin,
                                    end: t.sendTimeZoneEnd
                                },
                                timeSearchType: {
                                    type: t.timeSearchTypeType,
                                    content: t.timeSearchTypeContent
                                },
                                selectedDateSearch: {
                                    type: t.selectedDateSearchType
                                },
                                searchTypeCatch: {
                                    type: t.selectedDateSearchType
                                }
                            });
                        case "mobileSearchPageDataUpdate":
                            return Object(c.a)(Object(c.a)({}, e), {}, {
                                filterType: t.filterTypeType,
                                currentOffset: t.currentOffsetNumber,
                                mSearchPopup: t.mSearchPopupStatus,
                                dataRenderDone: t.dataRenderDoneStatus,
                                sendTimeZone: {
                                    begin: t.sendTimeZoneBegin,
                                    end: t.sendTimeZoneEnd
                                },
                                timeSearchType: {
                                    type: t.timeSearchTypeType,
                                    content: t.timeSearchTypeContent
                                },
                                selectedDateSearch: {
                                    type: t.selectedDateSearchType
                                },
                                searchTypeCatch: {
                                    type: t.selectedDateSearchType
                                }
                            });
                        case "setIdSearch":
                            return Object(c.a)(Object(c.a)({}, e), {}, {
                                timeBusy: t.timeBusy,
                                inputCurrentVal: t.inputCurrentVal,
                                dataRenderDone: t.dataRenderDone,
                                isNoDataLayer: t.isNoDataLayer,
                                selectedDateSearch: t.selectedDateSearch,
                                timeSearchType: t.timeSearchType
                            });
                        case "setStatusSelected":
                            var a = t.currentCount === e.currentCount,
                                i = t.currentOffset === e.currentOffset;
                            return a && i ? e : Object(c.a)(Object(c.a)({}, e), {}, {
                                dataRenderDone: t.dataRenderDone,
                                timeBusy: t.timeBusy,
                                currentCount: t.currentCount,
                                currentOffset: t.currentOffset
                            });
                        case "getIDsuccess":
                            return Object(c.a)(Object(c.a)({}, e), {}, {
                                dataRenderDone: t.DataRenderDoneStatus,
                                isNoDataLayer: t.IsNoDataLayerStatus,
                                mSearchPopup: t.MSearchPopupStatus
                            });
                        case "getIDfaile":
                            return Object(c.a)(Object(c.a)({}, e), {}, {
                                dataRenderDone: t.DataRenderDoneStatus,
                                mSearchPopup: t.MSearchPopupStatus
                            });
                        default:
                            return e
                    }
                },
                Nn = function() {
                    var e = Object(i.useReducer)(zn, Cn);
                    return Object(a.jsx)(kn.Provider, {
                        value: e,
                        children: Object(a.jsx)(Sn, {})
                    })
                },
                Mn = function(e) {
                    e && e instanceof Function && t.e(3).then(t.bind(null, 107)).then((function(n) {
                        var t = n.getCLS,
                            a = n.getFID,
                            i = n.getFCP,
                            r = n.getLCP,
                            o = n.getTTFB;
                        t(e), a(e), i(e), r(e), o(e)
                    }))
                };
            s.a.render(Object(a.jsx)(r.a.StrictMode, {
                children: Object(a.jsx)(Nn, {})
            }), document.getElementById("root")), Mn()
        },
        41: function(e) {
            e.exports = JSON.parse('{"name":"megalon","version":"0.2.18","private":true,"dependencies":{"@testing-library/jest-dom":"^5.11.4","@testing-library/react":"^11.1.0","@testing-library/user-event":"^12.1.10","axios":"^0.21.1","classnames":"^2.2.6","current-device":"^0.10.1","http-proxy-middleware":"^1.0.6","immutable":"^4.0.0-rc.12","is-ua-webview":"^1.1.2","lodash":"^4.17.20","moment-timezone":"^0.5.32","numeral":"^2.0.6","react":"^17.0.1","react-dom":"^17.0.1","react-hot-loader":"^4.13.0","react-icons":"^4.1.0","react-loader-spinner":"^3.1.14","react-scripts":"4.0.1","react-spinners":"^0.10.4","react-virtualized-auto-sizer":"^1.0.4","react-window":"^1.8.6","styled-components":"^5.2.1","web-vitals":"^0.2.4"},"scripts":{"start":"react-scripts start","build":"react-scripts build","test":"react-scripts test","eject":"react-scripts eject"},"eslintConfig":{"extends":["react-app","react-app/jest"]},"browserslist":{"production":[">0.2%","not dead","not op_mini all"],"development":["last 1 chrome version","last 1 firefox version","last 5 safari version"]},"homepage":"./"}')
        }
    },
    [
        [106, 1, 2]
    ]
]);