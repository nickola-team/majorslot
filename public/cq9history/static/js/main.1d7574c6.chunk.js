(this.webpackJsonpmegalon = this.webpackJsonpmegalon || []).push([
    [0], {
        100: function (e, t, n) {
            "use strict";
            n.r(t);
            var a = n(1),
                r = n(2),
                i = n.n(r),
                o = n(41),
                c = n.n(o),
                s = n(3),
                l = n(26),
                d = new URL(window.location).searchParams.get("gametoken") ? new URL(window.location).searchParams.get("gametoken") : null,
                u = new URL(window.location).searchParams.get("language") ? new URL(window.location).searchParams.get("language") : null,
                p = n(12),
                b = n(10),
                g = n.n(b),
                m = function (e) {
                    var t = g.a.tz(new Date, "America/Caracas").format("YYYY/MM/DD"),
                        n = g.a.tz(new Date, "Asia/Shanghai").format("YYYY/MM/DD"),
                        a = g.a.tz(new Date, "Europe/London").format("YYYY/MM/DD"),
                        r = t === n && t === a;
                    return "4" === e ? "".concat(r && "-").concat(r ? e : "8") : "".concat(r && "-").concat(e)
                },
                f = function () {
                    var e = g.a.tz(new Date, "America/Caracas");
                    return "".concat(g()(e).format("YYYY-MM-DDTHH:mm:ss"), "-04:00")
                },
                h = function () {
                    var e = g.a.tz(new Date, "America/Caracas");
                    return "".concat(g()(e).add(-30, "minutes").format("YYYY-MM-DDTHH:mm:ss"), "-04:00")
                },
                x = function () {
                    var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : 0,
                        t = g.a.tz(new Date, "America/Caracas").format("YYYY-MM-DDT00:00:00");
                    return "".concat(g()(t).add(m(e), "hours").format("YYYY-MM-DDTHH:mm:ss"), "-04:00")
                },
                y = function () {
                    var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : 0,
                        t = g.a.tz(new Date, "America/Caracas").format("YYYY-MM-DDT23:59:59");
                    return "".concat(g()(t).add(m(e), "hours").format("YYYY-MM-DDTHH:mm:ss"), "-04:00")
                },
                j = function () {
                    var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : 0,
                        t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : 0,
                        n = g.a.tz(new Date, "America/Caracas").format("YYYY-MM-DDT00:00:00");
                    return "".concat(g()(n).add(-e, "days").add(m(t), "hours").format("YYYY-MM-DDTHH:mm:ss"), "-04:00")
                },
                v = function () {
                    var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : 0,
                        t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : 0,
                        n = g.a.tz(new Date, "America/Caracas").format("YYYY-MM-DDT23:59:59");
                    return "".concat(g()(n).add(-e, "days").add(m(t), "hours").format("YYYY-MM-DDTHH:mm:ss"), "-04:00")
                },
                T = function () {
                    var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : 0,
                        t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : 0,
                        n = g.a.tz(new Date, "America/Caracas");
                    return "".concat(g()(n).add(-e, "days").add(t, "hours").format("YYYY/MM/DD"))
                },
                O = n(4),
                D = n(5),
                S = n(42),
                w = n(6),
                I = n(43),
                k = n.n(I),
                C = n(16),
                z = n.n(C),
                N = window.location.host,
                B = ~N.indexOf(":") ? "/api" : "//".concat(N, "/api");
            z.a.defaults.withCredentials = !0;
            var M = window.location.protocol,
                L = function (e) {
                    var t = e.setFun,
                        n = void 0 === t ? k.a : t,
                        a = e.code,
                        r = void 0 === a ? "" : a,
                        i = e.status;
                    n({
                        type: "setDataList",
                        payload: {
                            isLoading: void 0 === i || i,
                            data: Object(p.b)(),
                            code: r,
                            offset: Number(),
                            totalCount: Number()
                        }
                    })
                },
                P = function () {
                    var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : 0;
                    switch (e) {
                        case 4006:
                            return !0;
                        default:
                            return !1
                    }
                },
                _ = function (e) {
                    var t = e.props,
                        n = t.res,
                        a = n.data.error_msg,
                        r = n.data.error_code;
                    if (t.isId) {
                        var i = n.data.result.data ? n.data.result.data : [];
                        return 4006 === r ? (t.dispatch({
                            type: "getIDsuccess",
                            payload: {
                                DataRenderDoneStatus: !1,
                                IsNoDataLayerStatus: !1,
                                MSearchPopupStatus: !1
                            }
                        }), void L({
                            setFun: t.dispatch,
                            code: r,
                            status: P(r)
                        })) : 0 === i.length || "SUCCESS" !== a ? void t.dispatch({
                            type: "getIDsuccess",
                            payload: {
                                DataRenderDoneStatus: !0,
                                IsNoDataLayerStatus: !0,
                                MSearchPopupStatus: !1
                            }
                        }) : (t.dispatch({
                            type: "setDataList",
                            payload: {
                                isLoading: !1,
                                data: Object(p.b)(i),
                                code: r,
                                offset: 1,
                                totalCount: 1
                            }
                        }), void t.dispatch({
                            type: "getIDfaile",
                            payload: {
                                DataRenderDoneStatus: !0,
                                MSearchPopupStatus: !1
                            }
                        }))
                    }
                    if ("SUCCESS" === a) {
                        var o = n.data.result.data.list ? n.data.result.data.list : [],
                            c = n.data.result.data.count ? n.data.result.data.count : Number();
                        t.dispatch({
                            type: "setDataList",
                            payload: {
                                isLoading: !1,
                                data: Object(p.b)(o),
                                code: r,
                                offset: t.offset,
                                totalCount: c
                            }
                        })
                    } else L({
                        setFun: t.dispatch,
                        code: r,
                        status: P(r)
                    })
                },
                R = function (e) {
                    var t = e.props,
                        n = t.offset,
                        a = void 0 === n ? "0" : n,
                        r = t.count,
                        i = void 0 === r ? "1000" : r;
                    L({
                            setFun: t.dispatch
                        }),
                        function (e) {
                            return z.a.get("".concat(M).concat(B, "/player_betting/search_time"), {
                                params: e
                            })
                        }({
                            token: t.getGameToken,
                            begin: t.beginTime,
                            end: t.endTime,
                            offset: a,
                            count: i
                        }).then((function (e) {
                            return _({
                                props: Object(s.a)(Object(s.a)({}, t), {}, {
                                    res: e
                                })
                            })
                        })).catch((function (e) {
                            return console.log(e)
                        }))
                },
                Z = function (e) {
                    var t = e.props;
                    (function (e) {
                        return z.a.get("".concat(M).concat(B, "/player_betting/search_id"), {
                            params: e
                        })
                    })({
                        token: t.getGameToken,
                        id: t.rounid
                    }).then((function (e) {
                        return _({
                            props: Object(s.a)(Object(s.a)({}, t), {}, {
                                res: e,
                                isId: !0
                            })
                        })
                    })).catch((function (e) {
                        return console.log(e)
                    }))
                },
                Y = function (e) {
                    var t = e.getGameToken,
                        n = e.rounid,
                        a = e.langType,
                        r = void 0 === a ? "" : a,
                        i = e.gamecode,
                        o = void 0 === i ? "game_id" : i,
                        c = {
                            token: t,
                            id: n,
                            game_id: o
                        },
                        s = window.open("about:blank", "redirect");
                    (function (e) {
                        return z.a.get("".concat(M).concat(B, "/player_betting/detail_link"), {
                            params: e
                        })
                    })(c).then((function (e) {
                        var t = e.data.error_msg;
                        if ("SUCCESS" === t) {
                            var n = e.data.result.data.link,
                                a = "".concat(n, "&language=").concat(r, "&gamecode=").concat(o);
                            window.$APIAPP && window.$APIAPP.openView && window.$APIAPP.openView(a), s.open(a, "redirect")
                        } else s.open("", "redirect"), console.log(t)
                    })).catch((function (e) {
                        return console.log(e)
                    }))
                },
                A = Object(p.b)({
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
                W = n(29),
                E = Object(p.b)({
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
                        numberSearch: "\u5355\u53f7\u641c\u7d22 (90\u5929\u5167)\uff1a",
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
                        numberSearch: "Order History (Within 90 Days)\uff1a",
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
                        numberSearch: "\u0e1b\u0e23\u0e30\u0e27\u0e31\u0e15\u0e34\u0e01\u0e32\u0e23\u0e2a\u0e31\u0e48\u0e07\u0e0b\u0e37\u0e49\u0e2d(\u0e20\u0e32\u0e22\u0e43\u0e19 90 \u0e27\u0e31\u0e19)\uff1a",
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
                            freegame: "Free Game",
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
                        numberSearch: "T\xecm ki\u1ebfm s\u1ed1 \u0111\u01a1n (Trong v\xf2ng 90 ng\xe0y)\uff1a",
                        btnSearch: "T\xecm ki\u1ebfm",
                        searchPlaceholder: "Vui l\xf2ng nh\u1eadp s\u1ed1 \u0111\u01a1n",
                        backHome: "V\u1ec1 trang ch\u1ee7",
                        norecord: "Kh\xf4ng c\xf3 ghi ch\xe9p",
                        norecordSub: "Kh\xf4ng t\xecm th\u1ea5y d\u1eef li\u1ec7u\u300c|\u300dn\xe0y"
                    }
                });

            function F() {
                var e = Object(D.a)(["\n  max-width:1100px;\n  min-width:800px;\n  margin:0 auto;\n  padding:0 20px;\n  text-align:center;\n  p {\n    margin-top:30px;\n    color:#fff;\n    font-size:18px;\n  }\n"]);
                return F = function () {
                    return e
                }, e
            }

            function G() {
                var e = Object(D.a)(["\n  position:fixed;\n  top:0;\n  left:0;\n  width:100%;\n  min-height:100vh;\n  z-index:101;\n  background-color: ", ";\n  .cover {\n    position: absolute;\n    top:160px;\n    left:50%;\n    transform:translateX(-50%);\n    text-align:center;\n    p {\n      margin-top:30px;\n      color:#fff;\n      font-size:18px;\n    }\n    .verification-img {\n      width:220px;\n      height:220px;\n      background:url('./error.png')no-repeat;\n      background-size:100%;\n    }\n    .verification-sub {\n      margin-top:15px;\n      font-size:14px;\n      color:#888d9f;\n    }\n  }\n  ", "\n  ", ";\n"]);
                return G = function () {
                    return e
                }, e
            }
            var H = w.a.div(G(), A.getIn(["main", "bodyBackground"]), (function (e) {
                    return e.RWD.isTablet && "\n    .cover p {\n      font-size:20px;\n    }\n    .cover .verification-img {\n      width:330px;\n      height:330px;\n    }\n    .cover .verification-sub {\n      margin-top:10px;\n      font-size:15px;\n    }\n  "
                }), (function (e) {
                    var t = e.RWD;
                    return !t.isTablet && !t.isMobile && "\n    .cover p {\n      font-size:22px;\n    }\n    .cover .verification-img {\n      width:330px;\n      height:330px;\n    }\n    .cover .verification-sub {\n      margin-top:15px;\n      font-size:16px;\n    }\n  "
                })),
                q = function (e) {
                    var t = e.props,
                        n = t.langType,
                        r = t.code,
                        i = t.isMobile,
                        o = t.isTablet,
                        c = function (e) {
                            switch (e.code) {
                                case 4006:
                                    return Object(a.jsxs)(a.Fragment, {
                                        children: [Object(a.jsx)("div", {
                                            className: "verification-img"
                                        }), Object(a.jsx)("p", {
                                            children: E.getIn([n, "loader", "verificationTitle"])
                                        }), Object(a.jsx)("div", {
                                            className: "verification-sub",
                                            children: E.getIn([n, "loader", "verification"])
                                        })]
                                    });
                                default:
                                    return Object(a.jsxs)(a.Fragment, {
                                        children: [Object(a.jsx)(W.ClipLoader, {
                                            size: 45,
                                            color: "#f4c000"
                                        }), Object(a.jsx)("p", {
                                            children: E.getIn([n, "loader", "title"])
                                        })]
                                    })
                            }
                        };
                    return Object(a.jsx)(H, {
                        RWD: {
                            isMobile: i,
                            isTablet: o
                        },
                        children: Object(a.jsx)("div", {
                            className: "cover",
                            children: Object(a.jsx)(c, {
                                code: r
                            })
                        })
                    })
                },
                U = w.a.div(F()),
                V = function (e) {
                    var t = e.props.langType;
                    return Object(a.jsxs)(U, {
                        children: [Object(a.jsx)(W.ClipLoader, {
                            size: 45,
                            color: "#f4c000"
                        }), Object(a.jsx)("p", {
                            children: E.getIn([t, "loader", "title"])
                        })]
                    })
                },
                J = n(45);

            function K() {
                var e = Object(D.a)(["\n  @keyframes fadeInOut {\n    0% {transform: translate(-50%,0px);opacity:0;}\n    10% {transform: translate(-50%,-40px);opacity:1;}\n    90% {transform: translate(-50%,-40px);opacity:1;}\n    100% {transform: translate(-50%,0px);opacity:0;}\n  };\n  @keyframes desktopfadeInOut {\n    0% {transform: translate(-50%,0px);opacity:0;}\n    10% {transform: translate(-50%,30px);opacity:1;}\n    90% {transform: translate(-50%,30px);opacity:1;}\n    100% {transform: translate(-50%,0px);opacity:0;}\n  };\n  position: fixed;\n  opacity:0;\n  left:50%;\n  bottom:0;\n  width:85%;\n  transform: translate(-50%,0px);\n  background-color: ", ";\n  padding:11px 18px;\n  border-radius:5px;\n  box-sizing:border-box;\n  display:flex;\n  align-items:center;\n  z-index:101;\n  animation: fadeInOut 2.5s;\n  .svg-AiOutlineExclamationCircle {\n    color: #fff;\n    width:18px;\n    height:18px;\n    margin-right:10px;\n  }\n  .text {\n    color: #fff;\n    font-size:14px;\n  }\n  ", "\n  ", "\n"]);
                return K = function () {
                    return e
                }, e
            }
            var X = w.a.div(K(), (function (e) {
                    return e.color
                }), (function (e) {
                    return e.RWD.isTablet && "\n    .svg-AiOutlineExclamationCircle {\n      width:23px;\n      height:23px;\n    }\n    .text {\n      font-size:16px;\n    }\n  "
                }), (function (e) {
                    var t = e.RWD;
                    return !t.isMobile && !t.isTablet && "\n    bottom:unset;\n    top:0;\n    left:calc(50% + 180px);\n    width:600px;\n    animation: desktopfadeInOut 2.5s;\n    .svg-AiOutlineExclamationCircle {\n      width:23px;\n      height:23px;\n    }\n    .text {\n      font-size:16px;\n    }\n  "
                })),
                $ = function (e) {
                    var t = e.alertArrCount,
                        n = void 0 === t ? 0 : t,
                        i = Object(r.useContext)(ht),
                        o = Object(O.a)(i, 2),
                        c = o[0],
                        s = o[1];
                    return Object(r.useEffect)((function () {
                        0 === n && s({
                            type: "setAlertArr",
                            payload: {
                                data: Object(p.a)()
                            }
                        })
                    }), [n, s]), Object(a.jsxs)(X, {
                        color: c.alertText.color,
                        RWD: {
                            isMobile: c.isMobile,
                            isTablet: c.isTablet
                        },
                        children: [Object(a.jsx)(J.a, {
                            className: "svg-AiOutlineExclamationCircle"
                        }), Object(a.jsx)("span", {
                            className: "text",
                            children: c.alertText.text
                        })]
                    })
                };

            function Q() {
                var e = Object(D.a)(["\n  position: absolute;\n  top:0;\n  width:100%;\n  height:100vh;\n  background-color:", ";\n  z-index:", ";\n  display:flex;\n  align-items:center;\n  justify-content:center;\n  .container {\n    text-align:center;\n  }\n  .status-img {\n    margin:0 auto;\n    width:240px;\n    height:240px;\n    background:url('./empty.png')no-repeat;\n    background-size:100%;\n  }\n  .main-title {\n    margin-top:15px;\n    color:#fff;\n    font-size:18px;\n  }\n  .subtitle {\n    margin-top:15px;\n    font-size: 14px;\n    color:", ";\n  }\n  .subtitle .keyword {\n    color:", ";\n    margin:0 5px;\n  }\n  .back-btn {\n    display:inline-block;\n    font-size:14px;\n    border-radius:5px;\n    padding:10px 30px;\n    margin-top:25px;\n    color:", ";\n    background-color:", ";\n  }\n  ", "\n\n  ", "\n"]);
                return Q = function () {
                    return e
                }, e
            }
            var ee = w.a.div(Q(), A.getIn(["main", "bodyBackground"]), (function (e) {
                    return e.zIndex
                }), A.getIn(["main", "text"]), A.getIn(["main", "selected"]), A.getIn(["main", "bodyBackground"]), A.getIn(["main", "selected"]), (function (e) {
                    return e.RWD.isTablet && "\n    .status-img {\n      width:300px;\n      height:300px;\n    }\n    .main-title {\n      font-size:20px;\n    }\n    .subtitle {\n      font-size:15px;\n    }\n    .back-btn {\n      margin-top:30px;\n      padding:12px 35px;\n      font-size:16px;\n    }\n  "
                }), (function (e) {
                    var t = e.RWD;
                    return !t.isTablet && !t.isMobile && "\n    position: unset;\n    height:calc(100vh - 245px);\n    min-width:800px;\n    max-width:1100px;\n    margin:0 auto;\n    align-items:flex-start;\n    .status-img {\n      width:300px;\n      height:300px;\n    }\n    .main-title {\n      margin-top:25px;\n      font-size:20px;\n    }\n    .subtitle {\n      font-size:15px;\n    }\n    .back-btn {\n      margin-top:50px;\n      padding:12px 35px;\n      font-size:16px;\n      cursor: pointer;\n    }\n  "
                })),
                te = function (e) {
                    var t = e.zIndex,
                        n = void 0 === t ? 3 : t,
                        i = Object(r.useContext)(ht),
                        o = Object(O.a)(i, 2),
                        c = o[0],
                        s = o[1];
                    return Object(a.jsx)(ee, {
                        zIndex: n,
                        RWD: {
                            isMobile: c.isMobile,
                            isTablet: c.isTablet
                        },
                        children: Object(a.jsxs)("div", {
                            className: "container",
                            children: [Object(a.jsx)("div", {
                                className: "status-img"
                            }), Object(a.jsx)("div", {
                                className: "main-title",
                                children: E.getIn([c.langType, "norecord"])
                            }), Object(a.jsxs)("div", {
                                className: "subtitle",
                                children: [Object(a.jsx)("span", {
                                    children: E.getIn([c.langType, "norecordSub"]).split("|")[0]
                                }), Object(a.jsx)("span", {
                                    className: "keyword",
                                    children: c.inputCurrentVal
                                }), Object(a.jsx)("span", {
                                    children: E.getIn([c.langType, "norecordSub"]).split("|")[1]
                                })]
                            }), Object(a.jsx)("div", {
                                className: "back-btn ga_order_home",
                                onClick: function () {
                                    return s({
                                        type: "setIsNoDataLayer",
                                        payload: {
                                            status: !1
                                        }
                                    })
                                },
                                children: E.getIn([c.langType, "backHome"])
                            })]
                        })
                    })
                },
                ne = n(15),
                ae = n(14),
                re = n(7),
                ie = n.n(re),
                oe = n(11);

            function ce() {
                var e = Object(D.a)(["\n  position: absolute;\n  bottom: 0;\n  left: 50%;\n  transform: translate(-50%, 50%);\n  width: 100%;\n  display: flex;\n  align-items: center;\n  justify-content: center;\n  z-index: 2;\n  li {\n    width: 100%;\n    padding: 11px 0;\n    color: #fff;\n    font-size: 13px;\n    background-color: ", ";\n    animation: 0.5s fade;\n    text-align: center;\n    &:nth-child(1) {\n      border-radius: 5px 0 0 5px;\n    }\n    &:nth-child(2) {\n      border-left: 1px solid ", ";\n      border-right: 1px solid ", ";\n    }\n    &:nth-child(3) {\n      border-radius: 0 5px 5px 0;\n    }\n    &:nth-child(4) {\n      padding: 0;\n      background-color: unset;\n    }\n    &.selected {\n      background-color: ", ";\n      color: ", ";\n    }\n  }\n  ", ";\n  ", "\n"]);
                return ce = function () {
                    return e
                }, e
            }

            function se() {
                var e = Object(D.a)(["\n  position: relative;\n  padding: 11px 10px;\n  display: flex;\n  align-items: center;\n  justify-content: space-between;\n  border-radius: 5px;\n  background-color: ", ";\n  margin-left: 8px;\n  .left {\n    padding-right: 20px;\n  }\n  select {\n    position: absolute;\n    top: 0;\n    left: 0;\n    width: 100%;\n    height: 100%;\n    opacity: 0;\n    animation: 0.5s fade;\n  }\n  .desktop-select {\n    position: absolute;\n    top: calc(100% + 2px);\n    left: 0;\n    width: 100%;\n    .list-style {\n      position: relative;\n      display: flex;\n      align-items: center;\n      justify-content: center;\n      background-color: #fff;\n      font-size: 15px;\n      color: ", ";\n      height: 49px;\n      border-bottom: 1px solid ", ";\n      &:nth-child(1) {\n        border-top-left-radius: 5px;\n        border-top-right-radius: 5px;\n      }\n      &:last-child {\n        border-bottom-left-radius: 5px;\n        border-bottom-right-radius: 5px;\n        height: 50px;\n        border-bottom: unset;\n      }\n      &:hover {\n        background-color: ", ";\n        border-bottom: 1px solid ", ";\n        font-weight: bold;\n      }\n    }\n    .list-style .svg-BsCheck {\n      position: absolute;\n      top: 50%;\n      left: 10px;\n      width: 18px;\n      height: 18px;\n      transform: translateY(-50%);\n    }\n  }\n  ", "\n"]);
                return se = function () {
                    return e
                }, e
            }
            var le = w.a.div(se(), A.getIn(["main", "button"]), A.getIn(["main", "bodyBackground"]), A.getIn(["main", "selectedBorder"]), A.getIn(["main", "selected"]), A.getIn(["main", "selected"]), (function (e) {
                    var t = e.RWD;
                    return !t.isMobile && !t.isTablet && "\n    padding:0 12px;\n    margin-left:0;\n    height:100%;\n    border-radius:4px;\n    .left { padding-right:30px; }\n    select { cursor: pointer; }\n  "
                })),
                de = function (e) {
                    var t = e.alertPop,
                        n = Object(r.useContext)(ht),
                        i = Object(O.a)(n, 2),
                        o = i[0],
                        c = i[1],
                        s = Object(r.useState)(!1),
                        l = Object(O.a)(s, 2),
                        d = l[0],
                        u = l[1],
                        p = !o.isMobile && !o.isTablet;
                    return Object(r.useEffect)((function () {
                        var e = function (e) {
                            var t = document.getElementById("pagesStatus");
                            t && !t.contains(e.target) && u(!1)
                        };
                        return document.addEventListener("mousedown", e),
                            function () {
                                document.removeEventListener("mousedown", e)
                            }
                    }), []), Object(a.jsxs)(le, {
                        className: "ga_displaynum",
                        RWD: {
                            isMobile: o.isMobile,
                            isTablet: o.isTablet
                        },
                        onClick: function () {
                            p ? u((function (e) {
                                return !e
                            })) : c({
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
                        }), Object(a.jsx)(oe.b, {
                            className: "svg-IoIosArrowDown ga_displaynum"
                        }), p && d && Object(a.jsx)("div", {
                            className: "desktop-select",
                            children: ["300", "500", "800", "1000"].map((function (e, n) {
                                return Object(a.jsxs)("div", {
                                    className: "list-style ga_displaynum_".concat(e),
                                    onClick: function () {
                                        o.timeBusy || o.dataList.isLoading ? (t({
                                            text: E.getIn([o.langType, "alert", "busy"]),
                                            color: A.getIn(["accent", "red"])
                                        }), c({
                                            type: "setCurrentCount",
                                            payload: {
                                                number: o.currentCount
                                            }
                                        })) : c({
                                            type: "setStatusSelected",
                                            payload: {
                                                dataRenderDone: !1,
                                                timeBusy: !0,
                                                currentCount: e,
                                                currentOffset: "0"
                                            }
                                        })
                                    },
                                    children: [o.currentCount === e && Object(a.jsx)(ne.a, {
                                        className: "svg-BsCheck ga_displaynum_".concat(e)
                                    }), e]
                                }, n)
                            }))
                        })]
                    })
                },
                ue = w.a.ul(ce(), A.getIn(["main", "button"]), A.getIn(["other", "line"]), A.getIn(["other", "line"]), A.getIn(["main", "selected"]), A.getIn(["main", "bodyBackground"]), (function (e) {
                    return e.RWD.isTablet && "\n    width:60%;\n    li {\n      font-size:14px;\n    }\n  "
                }), (function (e) {
                    var t = e.RWD;
                    return !t.isMobile && !t.isTablet && "\n    position: relative;\n    left:0;\n    transform: unset;\n    justify-content:flex-start;\n    margin-top: 35px;\n    li {\n      display:flex;\n      align-items:center;\n      justify-content:center;\n      padding:0;\n      width:90px;\n      height:50px;\n      cursor: pointer;\n      &:nth-child(1) {\n        border-radius:4px 0 0 4px;\n      }\n      &:nth-child(3) {\n        border-radius:0 4px 4px 0;\n      }\n      &:nth-child(4) {\n        position: absolute;\n        right: 0;\n      }\n    }\n  "
                })),
                pe = function (e) {
                    var t = e.alertPop,
                        n = Object(r.useContext)(ht),
                        i = Object(O.a)(n, 2),
                        o = i[0],
                        c = i[1],
                        s = E.getIn([o.langType, "headerList"]).toJS();
                    return Object(a.jsxs)(ue, {
                        RWD: {
                            isMobile: o.isMobile,
                            isTablet: o.isTablet
                        },
                        children: [s.map((function (e, t) {
                            return Object(a.jsx)("li", {
                                className: ie()("".concat(function (e) {
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
                                onClick: function () {
                                    return c({
                                        type: "setFilterType",
                                        payload: {
                                            type: e.type
                                        }
                                    })
                                },
                                children: e.name
                            }, t)
                        })), Object(a.jsx)("li", {
                            className: "ga_displaynum",
                            children: Object(a.jsx)(de, {
                                alertPop: t
                            })
                        })]
                    })
                };

            function be() {
                var e = Object(D.a)(["\n  max-width:1100px;\n  min-width:800px;\n  margin:0 auto;\n  padding:55px 20px 35px 20px;\n  z-index:2;\n  background-color:", ";\n  .show-TZ {\n    display:flex;\n    align-items:center;\n    width:100%;\n    height:70px;\n    border-radius:5px;\n    padding:0 25px;\n    box-sizing:border-box;\n    color:#fff;\n    background-color:", ";\n  }\n"]);
                return be = function () {
                    return e
                }, e
            }

            function ge() {
                var e = Object(D.a)(["\n  position: fixed;\n  top:0;\n  left:0;\n  width:100%;\n  z-index:99;\n  background-color:", ";\n  /* top-area */\n  .top-area {\n    position: relative;\n    height: 55px;\n    padding: 15px 0;\n    box-sizing:border-box;\n    border-bottom:1px solid ", ";\n    .title {\n      text-align:center;\n      font-size:17px;\n      color:#fff;\n    }\n  }\n  .svg-BsGear {\n    position: absolute;\n    top:50%;\n    left:25px;\n    width:22px;\n    height:22px;\n    fill:#fff;\n    transform:translateY(-50%);\n    animation:.5s fade;\n  }\n  .svg-BiGlobe {\n    position: absolute;\n    top:50%;\n    right:25px;\n    width:22px;\n    height:22px;\n    fill:#fff;\n    transform:translateY(-50%);\n  }\n  /* bottom-area */\n  .bottom-area {\n    position: relative;\n    width:calc(100% - 40px);\n    height:104px;\n    margin:0 auto;\n    display:flex;\n    justify-content:center;\n    &:before {\n      content:'';\n      position: absolute;\n      z-index:1;\n      background-color:", ";\n      width:calc(100% + 40px);\n      height:40px;\n      bottom:0;\n      left:50%;\n      transform:translate(-50%,100%);\n    }\n  }\n  .show-status {\n    transform:translateY(30px);\n    text-align:center;\n    span {\n      color:#fff;\n      font-size:14px;\n    }\n  }\n  ", ";\n"]);
                return ge = function () {
                    return e
                }, e
            }
            var me = w.a.div(ge(), A.getIn(["main", "listBackground"]), A.getIn(["main", "border"]), A.getIn(["main", "bodyBackground"]), (function (e) {
                    return e.RWD.isTablet && "\n    .top-area {\n      height:75px;\n      padding:25px 0;\n    }\n    .top-area .title {\n      font-size:20px;\n    }\n    .svg-BsGear { \n      left:35px;\n    }\n    .svg-BiGlobe {\n      right:35px;\n    }\n    .bottom-area {\n      width: calc(100% - 70px);\n      &:before {\n        width: calc(100% + 70px);\n      }\n    }\n    .show-status span {\n      font-size:15px;\n    }\n  "
                })),
                fe = w.a.div(be(), A.getIn(["main", "bodyBackground"]), A.getIn(["main", "listBackground"])),
                he = function (e) {
                    var t = e.alertPop,
                        n = e.isDesktop,
                        i = void 0 !== n && n,
                        o = e.callAlertStatus,
                        c = void 0 !== o && o,
                        s = e.setCallAlertStatus,
                        l = Object(r.useContext)(ht),
                        d = Object(O.a)(l, 2),
                        u = d[0],
                        p = d[1],
                        b = function () {
                            switch (u.currentTZ) {
                                case "0":
                                    return E.getIn([u.langType, "timeZone", 0, "label"]);
                                case "12":
                                    return E.getIn([u.langType, "timeZone", 1, "label"]);
                                case "4":
                                    return E.getIn([u.langType, "timeZone", 2, "label"]);
                                default:
                                    return E.getIn([u.langType, "timeZone", 0, "label"])
                            }
                        },
                        g = function () {
                            switch (u.timeSearchType.type) {
                                case "beforeThirty":
                                    return E.getIn([u.langType, "beforeThirty"]);
                                case "today":
                                    return E.getIn([u.langType, "today"]);
                                case "singleSearch":
                                    return E.getIn([u.langType, "singleSearch"]);
                                case "customized":
                                    return T(u.timeSearchType.content, u.currentTZ);
                                default:
                                    return E.getIn([u.langType, "beforeThirty"])
                            }
                        };
                    return Object(r.useEffect)((function () {
                        c && u.dataRenderDone && (t({
                            text: E.getIn([u.langType, "alert", "TZchange"]),
                            color: A.getIn(["accent", "green"])
                        }), s(!1))
                    }), [c, u.dataRenderDone, u.langType, t, s]), Object(a.jsx)(a.Fragment, {
                        children: i ? Object(a.jsxs)(fe, {
                            children: [Object(a.jsxs)("div", {
                                className: "show-TZ",
                                children: [Object(a.jsxs)("span", {
                                    children: [b(), "\uff1a"]
                                }), Object(a.jsx)("span", {
                                    children: g()
                                })]
                            }), "singleSearch" !== u.timeSearchType.type && Object(a.jsx)(pe, {
                                alertPop: t
                            })]
                        }) : Object(a.jsxs)(me, {
                            RWD: {
                                isMobile: u.isMobile,
                                isTablet: u.isTablet
                            },
                            children: [Object(a.jsxs)("div", {
                                className: "top-area",
                                children: [Object(a.jsx)(ne.b, {
                                    className: "svg-BsGear ga_funcbtn_filter",
                                    onClick: function () {
                                        return p({
                                            type: "setMSearchPopup",
                                            payload: {
                                                status: !u.mSearchPopup
                                            }
                                        })
                                    }
                                }), Object(a.jsx)("div", {
                                    className: "title",
                                    children: E.getIn([u.langType, "header", "title"])
                                }), Object(a.jsx)(ae.a, {
                                    className: "svg-BiGlobe ga_timezone",
                                    onClick: function () {
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
                                }), "singleSearch" !== u.timeSearchType.type && Object(a.jsx)(pe, {
                                    alertPop: t
                                })]
                            })]
                        })
                    })
                },
                xe = n(23),
                ye = n(21),
                je = n(22),
                ve = n.n(je);

            function Te() {
                var e = Object(D.a)(["\n  position: fixed;\n  right:0;\n  bottom:30px;\n  width:40px;\n  height:40px;\n  background-color: ", ";\n  border-radius:5px 0 0 5px;\n  box-shadow:0 3px 5px rgba(0,0,0,0.2);\n  z-index:2;\n  -webkit-tap-highlight-color:transparent;\n  cursor: pointer;\n  svg {\n    position: absolute;\n    top:50%;\n    left:50%;\n    transform:translate(-50%,-50%);\n    color: ", ";\n    width:50%;\n    height:50%;\n  }\n  ", "\n  ", "\n"]);
                return Te = function () {
                    return e
                }, e
            }
            var Oe = w.a.div(Te(), A.getIn(["main", "selected"]), A.getIn(["main", "bodyBackground"]), (function (e) {
                    return e.RWD.isTablet && "\n    bottom:45px;\n    width:45px;\n    height:45px;\n  "
                }), (function (e) {
                    var t = e.RWD;
                    return !t.isMobile && !t.isTablet && "\n    bottom:55px;\n    width:45px;\n    height:45px;\n  "
                })),
                De = function (e) {
                    var t = e.bodyRef,
                        n = void 0 === t ? null : t,
                        r = e.isMobile,
                        i = void 0 === r || r,
                        o = e.isTablet,
                        c = void 0 !== o && o;
                    return Object(a.jsx)(Oe, {
                        className: "ga_gotop",
                        RWD: {
                            isMobile: i,
                            isTablet: c
                        },
                        onClick: function () {
                            var e = setInterval((function () {
                                var t = !i && !c ? 70 : 110,
                                    a = Math.floor(n.current.state.scrollOffset / t),
                                    r = Math.floor(a / 3);
                                n.current.scrollToItem(r, "auto"), r <= 0 && clearInterval(e)
                            }), 30)
                        },
                        children: Object(a.jsx)(oe.d, {
                            className: "ga_gotop"
                        })
                    })
                };

            function Se() {
                var e = Object(D.a)(["\n  padding-bottom:30px;\n  .container {\n    display:flex;\n    width:100%;\n    align-items:center;\n    justify-content:center;\n    .btn {\n      position: relative;\n      width:40px;\n      height:40px;\n      background-color:", ";\n      svg {\n        position: absolute;\n        top:50%;\n        left:50%;\n        transform:translate(-50%,-50%);\n        width:50%;\n        height:50%;\n        fill:", ";\n      }\n      &.prev {\n        border-radius:5px 0 0 5px;\n      }\n      &.next {\n        border-radius:0 5px 5px 0;\n      }\n      &.isAble {\n        svg {\n          fill:#fff;\n        }\n      }\n    }\n    .ex-pages {\n      display:flex;\n      align-items:center;\n      justify-content:center;\n      height:40px;\n      padding:0 15px;\n      margin:0 1px;\n      background-color:", ";\n      span {\n        font-size:14px;\n        color:#fff;\n        &:nth-child(1) {\n          color: ", ";\n        }\n        &:nth-child(2) {\n          margin:0 10px;\n        }\n      }\n    }\n  }\n  ", "\n"]);
                return Se = function () {
                    return e
                }, e
            }
            var we = w.a.div(Se(), A.getIn(["main", "button"]), A.getIn(["main", "text"]), A.getIn(["main", "button"]), A.getIn(["main", "selected"]), (function (e) {
                    return !e.RWD.isMobile && "\n    padding-bottom:35px;\n    .container {\n      .btn {\n        width:45px;\n        height:45px;\n        svg { \n          width:40%;\n          height:40%;\n        }\n        &.isAble {\n          cursor: pointer;\n        }\n      }\n      .ex-pages { \n        height:45px;\n        span {\n          font-size:15px;\n        }\n      }\n    }\n  "
                })),
                Ie = function () {
                    var e = Object(r.useContext)(ht),
                        t = Object(O.a)(e, 2),
                        n = t[0],
                        i = t[1],
                        o = 1 === n.pagenations.current,
                        c = n.pagenations.current === n.pagenations.total;
                    return Object(a.jsx)(we, {
                        RWD: {
                            isMobile: n.isMobile,
                            isTablet: n.isTablet
                        },
                        children: Object(a.jsxs)("div", {
                            className: "container",
                            children: [Object(a.jsx)("div", {
                                className: ie()("btn prev", {
                                    isAble: !o
                                }),
                                onClick: function () {
                                    o || (i({
                                        type: "setDataRenderDone",
                                        payload: {
                                            status: !1
                                        }
                                    }), i({
                                        type: "setCurrentOffset",
                                        payload: {
                                            number: "".concat(Number(n.currentOffset) - Number(n.currentCount))
                                        }
                                    }))
                                },
                                children: Object(a.jsx)(oe.a, {})
                            }), Object(a.jsxs)("div", {
                                className: "ex-pages",
                                children: [Object(a.jsx)("span", {
                                    children: n.pagenations.current
                                }), Object(a.jsx)("span", {
                                    children: "/"
                                }), Object(a.jsx)("span", {
                                    children: n.pagenations.total
                                })]
                            }), Object(a.jsx)("div", {
                                className: ie()("btn next", {
                                    isAble: !c
                                }),
                                onClick: function () {
                                    c || (i({
                                        type: "setDataRenderDone",
                                        payload: {
                                            status: !1
                                        }
                                    }), i({
                                        type: "setCurrentOffset",
                                        payload: {
                                            number: "".concat(Number(n.currentOffset) + Number(n.currentCount))
                                        }
                                    }))
                                },
                                children: Object(a.jsx)(oe.c, {})
                            })]
                        })
                    })
                };

            function ke() {
                var e = Object(D.a)(["\n  width: calc(100% - 40px);\n  margin: 0 auto;\n  height: calc(100vh - 200px);\n  .data-list {\n    box-sizing: border-box;\n    padding: 15px 17px;\n    border-radius: 5px;\n    background-color: ", ";\n    margin-bottom: 15px;\n    animation: 0.5s fade;\n  }\n  .layout {\n    &:last-child .data-list {\n      margin-bottom: 0;\n    }\n  }\n  .padd {\n    height: 25px;\n  }\n  .data-list .top-area {\n    position: relative;\n    display: flex;\n    align-items: flex-end;\n    padding-bottom: 10px;\n    border-bottom: 1px solid ", ";\n    .typeTag {\n      font-size: 12px;\n      color: #fff;\n      padding: 4px 8px;\n      border-radius: 2px;\n      margin-left: 20px;\n      &.freegame {\n        background-color: ", ";\n      }\n      &.bonus {\n        background-color: ", ";\n      }\n      &.singlerowbet {\n        background-color: ", ";\n      }\n    }\n    .svg-IoIosArrowForward {\n      position: absolute;\n      right: 5px;\n      top: calc(50% - 5px);\n      transform: translateY(-50%);\n      width: 20px;\n      height: 20px;\n      fill: #fff;\n    }\n  }\n  .data-list .top-area .captions {\n    .main-title {\n      color: #fff;\n      font-size: 15px;\n    }\n    .date {\n      color: ", ";\n      font-size: 12px;\n    }\n  }\n  .data-list .bottom-area {\n    display: flex;\n    align-items: center;\n    justify-content: space-between;\n    padding-top: 10px;\n    .roundid {\n      font-size: 14px;\n      color: #fff;\n    }\n    .wins {\n      color: #fff;\n      font-size: 16px;\n      &.isWin {\n        color: ", ";\n      }\n    }\n  }\n  .norecord {\n    padding-top: 45px;\n    text-align: center;\n    font-size: 16px;\n    color: ", ";\n  }\n  ", ";\n"]);
                return ke = function () {
                    return e
                }, e
            }
            var Ce = w.a.div(ke(), A.getIn(["main", "listBackground"]), A.getIn(["main", "border"]), A.getIn(["accent", "blue"]), A.getIn(["accent", "purple"]), A.getIn(["accent", "green"]), A.getIn(["main", "text"]), A.getIn(["accent", "green"]), A.getIn(["main", "text"]), (function (e) {
                    return e.RWD.isTablet && "\n    width:calc(100% - 70px);\n    .data-list { \n      padding:15px 25px;\n      margin-bottom:25px;\n    }\n    .layout {\n      &:last-child .data-list{\n        margin-bottom:0;\n      }\n    }\n    .padd { height:35px; }\n    .data-list .top-area {\n      padding-bottom: 16px;\n    }\n    .data-list .top-area .svg-IoIosArrowForward {\n      width:22px;\n      height:22px;\n    }\n    .data-list .top-area .captions {\n      .main-title {\n        font-size:16px;\n      }\n      .date {\n        padding-top:2px;\n        font-size:13px;\n      }\n    }\n    .data-list .bottom-area {\n      padding-top:12px;\n      .roundid { \n        font-size:15px;\n      }\n      .wins {\n        font-size:18px;\n      }\n    }\n  "
                })),
                ze = function (e) {
                    var t = e.filterNameset,
                        n = e.timeShowFilter,
                        i = e.filterTypeTag,
                        o = e.filterCA01Type,
                        c = Object(r.useRef)(null),
                        s = Object(r.useState)(!1),
                        l = Object(O.a)(s, 2),
                        d = l[0],
                        u = l[1],
                        p = Object(r.useContext)(ht),
                        b = Object(O.a)(p, 2),
                        g = b[0],
                        m = b[1],
                        f = function (e) {
                            var r = e.index,
                                c = e.style,
                                s = e.data,
                                l = r + 1 === g.showDataList.size;
                            return Object(a.jsxs)("div", {
                                className: "layout",
                                style: c,
                                children: [Object(a.jsxs)("div", {
                                    className: "data-list",
                                    onClick: function () {
                                        Y({
                                            getGameToken: g.getGameToken,
                                            rounid: s.getIn([r, "roundid"]),
                                            langType: g.langType,
                                            gamecode: s.getIn([r, "gamecode"])
                                        })
                                    },
                                    children: [Object(a.jsxs)("div", {
                                        className: "top-area",
                                        children: [Object(a.jsxs)("div", {
                                            className: "captions",
                                            children: [Object(a.jsxs)("div", {
                                                className: "main-title",
                                                children: [t(s.getIn([r, "nameset"]), s.getIn([r, "gamename"])), "" !== s.getIn([r, "tabletype"]) && o(s.getIn([r, "tabletype"]))]
                                            }), Object(a.jsx)("div", {
                                                className: "date",
                                                children: n(s.getIn([r, "createtime"]))
                                            })]
                                        }), i(s.getIn([r, "detail"])), Object(a.jsx)(oe.c, {
                                            className: "svg-IoIosArrowForward"
                                        })]
                                    }), Object(a.jsxs)("div", {
                                        className: "bottom-area",
                                        children: [Object(a.jsx)("div", {
                                            className: "roundid",
                                            children: s.getIn([r, "roundid"])
                                        }), Object(a.jsxs)("div", {
                                            className: ie()("wins", {
                                                isWin: s.getIn([r, "wins"]) >= 0
                                            }),
                                            children: [s.getIn([r, "wins"]) > 0 && "+", ve()(s.getIn([r, "wins"])).format("0.00")]
                                        })]
                                    })]
                                }), l && Object(a.jsx)("div", {
                                    className: "padd"
                                }), g.pagenations.status && l && Object(a.jsx)(Ie, {})]
                            })
                        };
                    return Object(r.useEffect)((function () {
                        m({
                            type: "setDataRenderDone",
                            payload: {
                                status: !0
                            }
                        })
                    }), [m]), Object(a.jsxs)(Ce, {
                        RWD: {
                            isMobile: g.isMobile,
                            isTablet: g.isTablet
                        },
                        children: [g.dataRenderDone && g.showDataList.size > 0 && Object(a.jsx)(ye.a, {
                            children: function (e) {
                                var t = e.height,
                                    n = e.width;
                                return Object(a.jsx)(xe.a, {
                                    ref: c,
                                    height: t,
                                    width: n,
                                    itemCount: g.showDataList.size,
                                    itemData: g.showDataList,
                                    itemSize: g.isTablet ? 151 : 126,
                                    onScroll: function (e) {
                                        e.scrollOffset > 0 ? u(!0) : u(!1)
                                    },
                                    children: f
                                })
                            }
                        }), g.dataRenderDone && 0 === g.showDataList.size && Object(a.jsx)("div", {
                            className: "norecord",
                            children: E.getIn([g.langType, "dataList", "norecord"])
                        }), d && Object(a.jsx)(De, {
                            bodyRef: c,
                            isMobile: g.isMobile,
                            isTablet: g.isTablet
                        })]
                    })
                };

            function Ne() {
                var e = Object(D.a)(["\n  position: relative;\n  -webkit-overflow-scrolling: touch;\n  height: calc(100vh - 245px);\n  min-height: 555px;\n  width: 100%;\n  max-width: 1140px;\n  min-width: 800px;\n  padding: 0 20px;\n  margin: 0 auto;\n  box-sizing: border-box;\n  ", "\n  .data-list {\n    display: flex;\n    align-items: center;\n    width: 100%;\n    height: 70px;\n    background-color: ", ";\n    box-sizing: border-box;\n    border-bottom: 1px solid ", ";\n    animation: 0.5s fade;\n    cursor: pointer;\n    &:hover {\n      background-color: ", ";\n    }\n    .main-title {\n      width: 185px;\n      color: #fff;\n      font-size: 16px;\n      box-sizing: border-box;\n      padding-left: 40px;\n    }\n    .date {\n      text-align: center;\n      width: 170px;\n      color: ", ";\n      font-size: 15px;\n    }\n    .bonus-tag {\n      width: 200px;\n      padding-left: 65px;\n      box-sizing: border-box;\n      .typeTag {\n        display: inline-block;\n        font-size: 13px;\n        color: #fff;\n        padding: 5px 9px;\n        border-radius: 3px;\n        &.freegame {\n          background-color: ", ";\n        }\n        &.bonus {\n          background-color: ", ";\n        }\n        &.singlerowbet {\n          background-color: ", ";\n        }\n      }\n    }\n    .roundid {\n      width: 280px;\n      font-size: 15px;\n      color: #fff;\n    }\n    .wins {\n      text-align: right;\n      width: 100px;\n      color: #fff;\n      font-size: 16px;\n      &.isWin {\n        color: ", ";\n      }\n    }\n    .arrow {\n      width: 125px;\n      box-sizing: border-box;\n      padding-right: 20px;\n      text-align: right;\n      .svg-IoIosArrowForward {\n        width: 20px;\n        height: 20px;\n        fill: #fff;\n      }\n    }\n  }\n  .padd {\n    height: 35px;\n  }\n  .layout {\n    &:nth-child(1) .data-list {\n      border-top-left-radius: 8px;\n      border-top-right-radius: 8px;\n    }\n    &:last-child .data-list {\n      border-bottom-left-radius: 8px;\n      border-bottom-right-radius: 8px;\n      border-bottom: none;\n    }\n  }\n  .norecord {\n    padding-top: 45px;\n    text-align: center;\n    font-size: 18px;\n    color: ", ";\n  }\n"]);
                return Ne = function () {
                    return e
                }, e
            }
            var Be = w.a.div(Ne(), (function (e) {
                    var t = e.RWD,
                        n = e.isNoDataLayer;
                    return !t.isMobile && !t.isTablet && "\n    ".concat(n && "display:none", ";\n  ")
                }), A.getIn(["main", "listBackground"]), A.getIn(["other", "line"]), A.getIn(["main", "border"]), A.getIn(["main", "text"]), A.getIn(["accent", "blue"]), A.getIn(["accent", "purple"]), A.getIn(["accent", "green"]), A.getIn(["accent", "green"]), A.getIn(["main", "text"])),
                Me = function (e) {
                    var t = e.filterNameset,
                        n = e.timeShowFilter,
                        i = e.filterTypeTag,
                        o = e.filterCA01Type,
                        c = Object(r.useRef)(null),
                        s = Object(r.useState)(!1),
                        l = Object(O.a)(s, 2),
                        d = l[0],
                        u = l[1],
                        p = Object(r.useContext)(ht),
                        b = Object(O.a)(p, 2),
                        g = b[0],
                        m = b[1],
                        f = function (e) {
                            var r = e.index,
                                c = e.style,
                                s = e.data,
                                l = r + 1 === g.showDataList.size;
                            return Object(a.jsxs)("div", {
                                className: "layout",
                                style: c,
                                children: [Object(a.jsxs)("div", {
                                    className: "data-list",
                                    onClick: function () {
                                        Y({
                                            getGameToken: g.getGameToken,
                                            rounid: s.getIn([r, "roundid"]),
                                            langType: g.langType,
                                            gamecode: s.getIn([r, "gamecode"])
                                        })
                                    },
                                    children: [Object(a.jsxs)("div", {
                                        className: "main-title",
                                        children: [t(s.getIn([r, "nameset"]), s.getIn([r, "gamename"])), "" !== s.getIn([r, "tabletype"]) && o(s.getIn([r, "tabletype"]))]
                                    }), Object(a.jsx)("div", {
                                        className: "date",
                                        children: n(s.getIn([r, "createtime"]), !0)
                                    }), Object(a.jsx)("div", {
                                        className: "bonus-tag",
                                        children: i(s.getIn([r, "detail"]))
                                    }), Object(a.jsx)("div", {
                                        className: "roundid",
                                        children: s.getIn([r, "roundid"])
                                    }), Object(a.jsxs)("div", {
                                        className: ie()("wins", {
                                            isWin: s.getIn([r, "wins"]) >= 0
                                        }),
                                        children: [s.getIn([r, "wins"]) > 0 && "+", ve()(s.getIn([r, "wins"])).format("0.00")]
                                    }), Object(a.jsx)("div", {
                                        className: "arrow",
                                        children: Object(a.jsx)(oe.c, {
                                            className: "svg-IoIosArrowForward"
                                        })
                                    })]
                                }), l && Object(a.jsx)("div", {
                                    className: "padd"
                                }), g.pagenations.status && l && Object(a.jsx)(Ie, {})]
                            })
                        };
                    return Object(r.useEffect)((function () {
                        m({
                            type: "setDataRenderDone",
                            payload: {
                                status: !0
                            }
                        })
                    }), [m]), Object(a.jsxs)(Be, {
                        RWD: {
                            isMobile: g.isMobile,
                            isTablet: g.isTablet
                        },
                        isNoDataLayer: g.isNoDataLayer,
                        children: [g.dataRenderDone && g.showDataList.size > 0 && Object(a.jsx)(ye.a, {
                            children: function (e) {
                                var t = e.height,
                                    n = e.width;
                                return Object(a.jsx)(xe.a, {
                                    ref: c,
                                    height: t,
                                    width: n,
                                    itemCount: g.showDataList.size,
                                    itemData: g.showDataList,
                                    itemSize: 70,
                                    onScroll: function (e) {
                                        e.scrollOffset > 0 ? u(!0) : u(!1)
                                    },
                                    children: f
                                })
                            }
                        }), g.dataRenderDone && 0 === g.showDataList.size && Object(a.jsx)("div", {
                            className: "norecord",
                            children: E.getIn([g.langType, "dataList", "norecord"])
                        }), d && Object(a.jsx)(De, {
                            bodyRef: c,
                            isMobile: g.isMobile,
                            isTablet: g.isTablet
                        })]
                    })
                };

            function Le() {
                var e = Object(D.a)(["\n  color: #f4c000;\n"]);
                return Le = function () {
                    return e
                }, e
            }

            function Pe() {
                var e = Object(D.a)(["\n  ", "\n  ", "\n  ", "\n"]);
                return Pe = function () {
                    return e
                }, e
            }
            var _e = w.a.div(Pe(), (function (e) {
                    return e.RWD.isMobile && "\n    padding-top:200px;\n  "
                }), (function (e) {
                    return e.RWD.isTablet && "\n    padding-top:220px;\n  "
                }), (function (e) {
                    var t = e.RWD;
                    return !t.isMobile && !t.isTablet && "\n    padding-top:0px;\n  "
                })),
                Re = w.a.span(Le()),
                Ze = function () {
                    var e = Object(r.useContext)(ht),
                        t = Object(O.a)(e, 1)[0],
                        n = function (e) {
                            var n = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : "",
                                a = e.filter((function (e) {
                                    return e.get("lang") === t.langType
                                })),
                                r = a.getIn([0, "name"]) ? a.getIn([0, "name"]) : n;
                            return r
                        },
                        i = function () {
                            var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : "",
                                t = "none";
                            switch (e) {
                                case "1":
                                    t = "\u767e\u5bb6\u4e50";
                                    break;
                                case "2":
                                    t = "\u8f6e\u76d8";
                                    break;
                                case "3":
                                    t = "\u9ab0\u5b9d";
                                    break;
                                case "4":
                                    t = "\u9f99\u864e"
                            }
                            return Object(a.jsxs)(a.Fragment, {
                                children: ["none" !== t && "-", "none" !== t && Object(a.jsx)(Re, {
                                    children: t
                                })]
                            })
                        },
                        o = function (e) {
                            var n = arguments.length > 1 && void 0 !== arguments[1] && arguments[1],
                                a = e.split("T"),
                                r = a[1].split("-"),
                                i = g()("".concat(a[0], " ").concat(r[0])),
                                o = g()(i).add(t.currentTZ, "hours").format("YYYY-MM-DD HH:mm:ss"),
                                c = g()(i).add(t.currentTZ, "hours").format("YYYY/MM/DD HH:mm:ss");
                            return n ? c : o
                        },
                        c = function (e) {
                            return e.map((function (e, n) {
                                var r = Object.keys(e.toJS())[0];
                                return "freegame" === r && e.get(r) > 0 ? Object(a.jsx)("div", {
                                    className: "typeTag freegame",
                                    children: E.getIn([t.langType, "dataList", "freegame"])
                                }, n) : "bonus" === r && e.get(r) > 0 ? Object(a.jsx)("div", {
                                    className: "typeTag bonus",
                                    children: E.getIn([t.langType, "dataList", "bonus"])
                                }, n) : "singlerowbet" === r && e.get(r) > 0 ? Object(a.jsx)("div", {
                                    className: "typeTag singlerowbet",
                                    children: E.getIn([t.langType, "dataList", "singlerowbet"])
                                }, n) : null
                            }))
                        };
                    return Object(a.jsxs)(_e, {
                        RWD: {
                            isMobile: t.isMobile,
                            isTablet: t.isTablet
                        },
                        children: [t.isMobile && Object(a.jsx)(ze, {
                            filterNameset: n,
                            timeShowFilter: o,
                            filterTypeTag: c,
                            filterCA01Type: i
                        }), !t.isMobile && !t.isTablet && Object(a.jsx)(Me, {
                            filterNameset: n,
                            timeShowFilter: o,
                            filterTypeTag: c,
                            filterCA01Type: i
                        })]
                    })
                };

            function Ye() {
                var e = Object(D.a)(["\n  width:100%;\n  box-sizing:border-box;\n  padding:30px 25px 0 25px;\n  .title {\n    font-size:15px;\n    color:#fff;\n  }\n  .src {\n    position: relative;\n    display:flex;\n    align-items:center;\n    justify-content:center;\n    box-sizing:border-box;\n    border-radius:5px;\n    border:1px solid ", ";\n    background-color:", ";\n    height:45px;\n    margin-top:15px;\n    transition:.2s;\n    span {\n      color:#fff;\n      font-size:14px;\n      transition:.2s;\n    }\n    &.active {\n      border:1px solid ", ";\n      span {\n        color:", ";\n      }\n    }\n    &.blur {\n      border:1px solid transparent;\n      background-color:", ";\n      span {\n        color:", ";\n      }\n    }\n  }\n  .id-search {\n    margin-top:30px;\n  }\n  .id-search .btn {\n    text-align:center;\n    font-size:14px;\n    margin:15px 0;\n    padding:12px 0;\n    border-radius:5px;\n    color:", ";\n    background-color:", ";\n    transition:.2s;\n    &.focus {\n      color:", ";\n      background-color:", ";\n    }\n  }\n  .id-search label {\n    width:100%;\n    position: relative;\n  }\n  .svg-BiSearch {\n    position: absolute;\n    top:50%;\n    left:15px;\n    width:18px;\n    height:18px;\n    transform:translateY(-50%);\n    fill:", ";\n  }\n  .id-search .close {\n    opacity:0;\n    position: absolute;\n    top:50%;\n    right:15px;\n    width:18px;\n    height:18px;\n    transform:translateY(-50%);\n    border-radius:50%;\n    background-color:", ";\n    transition:.2s;\n    z-index:-1;\n    svg {\n      width:100%;\n      height:100%;\n      color:#fff;\n    }\n  }\n  .id-search input {\n    width:100%;\n    font-size:14px;\n    margin-top:15px;\n    padding:12px 40px;\n    box-sizing:border-box;\n    border-radius:5px;\n    outline:none;\n    background-color:", ";\n    border:1px solid ", ";\n    color:", ";\n    transition:.2s;\n    &::placeholder {\n      color:", ";\n    }\n    &:focus {\n      color:#fff;\n      border:1px solid #fff;\n      &::placeholder {\n        color:#fff;\n      }\n      ~.svg-BiSearch {\n        fill:#fff;\n      }\n      ~.close {\n        opacity:1;\n        z-index:2;\n      }\n    }\n  }\n  ", "\n"]);
                return Ye = function () {
                    return e
                }, e
            }
            var Ae = w.a.div(Ye(), A.getIn(["main", "listBackground"]), A.getIn(["main", "bodyBackground"]), A.getIn(["main", "selected"]), A.getIn(["main", "selected"]), A.getIn(["main", "listBackground"]), A.getIn(["main", "textDeep"]), A.getIn(["main", "text"]), A.getIn(["main", "button"]), A.getIn(["main", "bodyBackground"]), A.getIn(["main", "selected"]), A.getIn(["main", "textDeep"]), A.getIn(["main", "button"]), A.getIn(["main", "bodyBackground"]), A.getIn(["main", "listBackground"]), A.getIn(["main", "textDeep"]), A.getIn(["main", "textDeep"]), (function (e) {
                    return e.RWD.isTablet && "\n    padding:60px 0 0 0;\n    margin:0 auto;\n    width:320px;\n  "
                })),
                We = function (e) {
                    var t = e.props,
                        n = Object(r.useRef)(null),
                        i = Object(r.useContext)(ht),
                        o = Object(O.a)(i, 2),
                        c = o[0],
                        s = o[1],
                        l = Object(r.useState)(c.selectedDateSearch.type),
                        d = Object(O.a)(l, 2),
                        u = d[0],
                        p = d[1],
                        b = function (e) {
                            var n = e.type,
                                a = void 0 === n ? "" : n,
                                r = e.val,
                                i = void 0 === r ? 0 : r,
                                o = e.currentTZ,
                                l = void 0 === o ? 0 : o,
                                d = function (e) {
                                    var t = e.type,
                                        n = void 0 === t ? "" : t,
                                        a = e.currentTZ,
                                        r = void 0 === a ? 0 : a,
                                        i = {
                                            beginTime: "",
                                            endTime: ""
                                        };
                                    switch (n) {
                                        case "beforeThirty":
                                            i.beginTime = h(), i.endTime = f();
                                            break;
                                        case "today":
                                            i.beginTime = x(r), i.endTime = y(r);
                                            break;
                                        default:
                                            i.beginTime = h(), i.endTime = f()
                                    }
                                    return i
                                };
                            c.timeBusy ? t.alertPop({
                                text: E.getIn([c.langType, "alert", "busy"]),
                                color: A.getIn(["accent", "red"])
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
                                type: "setMSelectorPopup",
                                payload: {
                                    status: !1,
                                    type: "",
                                    selectedCusDate: 0
                                }
                            }), t.setDataUpdate({
                                beginTime: d({
                                    type: a,
                                    val: i,
                                    currentTZ: l
                                }).beginTime,
                                endTime: d({
                                    type: a,
                                    val: i,
                                    currentTZ: l
                                }).endTime,
                                type: a,
                                dateContent: i
                            }))
                        };
                    return Object(a.jsxs)(Ae, {
                        RWD: {
                            isMobile: c.isMobile,
                            isTablet: c.isTablet
                        },
                        children: [Object(a.jsxs)("div", {
                            className: "date-search",
                            children: [Object(a.jsx)("div", {
                                className: "title",
                                children: E.getIn([c.langType, "dateSearch"])
                            }), Object(a.jsx)("div", {
                                className: ie()("src ga_quicksearch_30", {
                                    active: "beforeThirty" === c.selectedDateSearch.type
                                }, {
                                    blur: "blur" === c.selectedDateSearch.type
                                }),
                                onClick: function () {
                                    return b({
                                        type: "beforeThirty"
                                    })
                                },
                                children: Object(a.jsx)("span", {
                                    className: "ga_quicksearch_30",
                                    children: E.getIn([c.langType, "beforeThirty"])
                                })
                            }), Object(a.jsx)("div", {
                                className: ie()("src ga_quicksearch_today", {
                                    active: "today" === c.selectedDateSearch.type
                                }, {
                                    blur: "blur" === c.selectedDateSearch.type
                                }),
                                onClick: function () {
                                    return b({
                                        type: "today",
                                        currentTZ: c.currentTZ
                                    })
                                },
                                children: Object(a.jsx)("span", {
                                    className: "ga_quicksearch_today",
                                    children: E.getIn([c.langType, "today"])
                                })
                            }), Object(a.jsx)("div", {
                                className: ie()("src ga_quicksearch_custom", {
                                    active: "customized" === c.selectedDateSearch.type
                                }, {
                                    blur: "blur" === c.selectedDateSearch.type
                                }),
                                onClick: function () {
                                    return s({
                                        type: "setMSelectorPopup",
                                        payload: {
                                            status: !0,
                                            type: "customized",
                                            selectedCusDate: c.mSelectorPopup.selectedCusDate
                                        }
                                    })
                                },
                                children: Object(a.jsx)("span", {
                                    className: "ga_quicksearch_custom",
                                    children: E.getIn([c.langType, "customized"])
                                })
                            })]
                        }), Object(a.jsxs)("div", {
                            className: "id-search",
                            children: [Object(a.jsx)("div", {
                                className: "title",
                                children: E.getIn([c.langType, "numberSearch"])
                            }), Object(a.jsxs)("label", {
                                children: [Object(a.jsx)("input", {
                                    className: "input",
                                    ref: n,
                                    placeholder: E.getIn([c.langType, "searchPlaceholder"]),
                                    onFocus: function () {
                                        p(c.selectedDateSearch.type), s({
                                            type: "setSelectedDateSearch",
                                            payload: {
                                                type: "blur"
                                            }
                                        })
                                    },
                                    onBlur: function () {
                                        return s({
                                            type: "setSelectedDateSearch",
                                            payload: {
                                                type: u
                                            }
                                        })
                                    }
                                }), Object(a.jsx)(ae.c, {
                                    className: "svg-BiSearch"
                                }), Object(a.jsx)("div", {
                                    className: "close",
                                    onClick: function () {
                                        n.current.value = ""
                                    },
                                    children: Object(a.jsx)(oe.e, {})
                                })]
                            }), Object(a.jsx)("div", {
                                className: ie()("btn ga_quicksearch_order", {
                                    focus: "blur" === c.selectedDateSearch.type
                                }),
                                onClick: function () {
                                    "" !== n.current.value && (c.timeBusy || c.dataList.isLoading ? t.alertPop({
                                        text: E.getIn([c.langType, "alert", "busy"]),
                                        color: A.getIn(["accent", "red"])
                                    }) : (s({
                                        type: "setIdSearch",
                                        payload: {
                                            timeBusy: !0,
                                            inputCurrentVal: n.current.value,
                                            dataRenderDone: !1,
                                            isNoDataLayer: !1,
                                            selectedDateSearch: "singleSearch",
                                            timeSearchType: {
                                                type: "singleSearch",
                                                content: c.timeSearchType.content
                                            }
                                        }
                                    }), Z({
                                        props: {
                                            getGameToken: c.getGameToken,
                                            rounid: n.current.value,
                                            dispatch: s
                                        }
                                    }), n.current.value = ""))
                                },
                                children: E.getIn([c.langType, "btnSearch"])
                            })]
                        })]
                    })
                };

            function Ee() {
                var e = Object(D.a)(["\n  position:fixed;\n  top:0;\n  left:0;\n  width:100%;\n  height:100vh;\n  overflow:auto;\n  background-color:", ";\n  z-index:100;\n  transform:translateX(100%);\n  transition:.2s;\n  &.active {\n    transform:translateX(0%);\n  }\n  header {\n    position: relative;\n    width:100%;\n    height:55px;\n    padding:15px 0;\n    box-sizing:border-box;\n    background-color:", ";\n    border-bottom:1px solid ", ";\n    .title {\n      font-size:17px;\n      color:#fff;\n      text-align:center;\n    }\n  }\n  header .go-back {\n    position:absolute;\n    width:30px;\n    height:30px;\n    top:50%;\n    left:25px;\n    transform:translateY(-50%);\n    .content {\n      position: relative;\n      width:100%;\n      height:100%;\n      background-color:", ";\n      border-radius:50%;\n    }\n    .svg-BiLeftArrowAlt {\n      position: absolute;\n      top:50%;\n      left:50%;\n      transform:translate(-50%,-50%);\n      fill:#fff;\n      width:20px;\n      height:20px;\n    }\n  }\n  ", ";\n"]);
                return Ee = function () {
                    return e
                }, e
            }
            var Fe = w.a.div(Ee(), A.getIn(["main", "bodyBackground"]), A.getIn(["main", "bodyBackground"]), A.getIn(["main", "border"]), A.getIn(["main", "button"]), (function (e) {
                    return e.RWD.isTablet && "\n    header {\n      height:75px;\n      padding:23px 0;\n      .title {\n        font-size:20px;\n      }\n    }\n    header .go-back {\n      left:30px;\n    }\n  "
                })),
                Ge = function (e) {
                    var t = e.alertPop,
                        n = Object(r.useContext)(ht),
                        i = Object(O.a)(n, 2),
                        o = i[0],
                        c = i[1];
                    return Object(a.jsxs)(Fe, {
                        className: ie()({
                            active: o.mSearchPopup
                        }),
                        RWD: {
                            isMobile: o.isMobile,
                            isTablet: o.isTablet
                        },
                        children: [Object(a.jsxs)("header", {
                            children: [Object(a.jsx)("div", {
                                className: "go-back",
                                onClick: function () {
                                    return c({
                                        type: "setMSearchPopup",
                                        payload: {
                                            status: !1
                                        }
                                    })
                                },
                                children: Object(a.jsx)("div", {
                                    className: "content",
                                    children: Object(a.jsx)(ae.b, {
                                        className: "svg-BiLeftArrowAlt"
                                    })
                                })
                            }), Object(a.jsx)("div", {
                                className: "title",
                                children: E.getIn([o.langType, "recordSearch"])
                            })]
                        }), Object(a.jsx)(We, {
                            props: {
                                alertPop: t,
                                setDataUpdate: function (e) {
                                    var t = e.beginTime,
                                        n = void 0 === t ? "" : t,
                                        a = e.endTime,
                                        r = void 0 === a ? "" : a,
                                        i = e.type,
                                        o = void 0 === i ? "" : i,
                                        s = e.dateContent;
                                    c({
                                        type: "mobileSearchPageDataUpdate",
                                        payload: {
                                            filterTypeType: "default",
                                            currentOffsetNumber: "0",
                                            sendTimeZoneBegin: n,
                                            sendTimeZoneEnd: r,
                                            timeSearchTypeType: o,
                                            timeSearchTypeContent: void 0 === s ? "" : s,
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
                He = function (e) {
                    var t = e.props,
                        n = Object(r.useContext)(ht),
                        i = Object(O.a)(n, 2),
                        o = i[0],
                        c = i[1],
                        s = t.alertPop,
                        l = t.setCallAlertStatus,
                        d = t.StyledSelector,
                        u = E.getIn([o.langType, "timeZone"]),
                        p = E.getIn([o.langType, "timeZone"]).filter((function (e) {
                            return e.get("value") === o.currentTZ
                        })),
                        b = function (e) {
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
                        children: u.map((function (e, t) {
                            return Object(a.jsxs)("div", {
                                className: ie()("list-style ".concat(b(e.get("value"))), {
                                    active: p.getIn([0, "value"]) === e.get("value")
                                }),
                                onClick: function () {
                                    if (o.timeBusy || o.dataList.isLoading) s({
                                        text: E.getIn([o.langType, "alert", "busy"]),
                                        color: A.getIn(["accent", "red"])
                                    });
                                    else {
                                        if (c({
                                                type: "setTimeBusy",
                                                payload: {
                                                    status: !0
                                                }
                                            }), c({
                                                type: "setDataRenderDone",
                                                payload: {
                                                    status: !1
                                                }
                                            }), "singleSearch" === o.timeSearchType.type) return c({
                                            type: "setStaticCurrentTZ",
                                            payload: {
                                                number: e.get("value")
                                            }
                                        }), void l(!0);
                                        var t = function () {
                                            var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : 0,
                                                t = {
                                                    beginTime: "",
                                                    endTime: ""
                                                };
                                            switch (o.timeSearchType.type) {
                                                case "beforeThirty":
                                                    t.beginTime = o.sendTimeZone.begin, t.endTime = o.sendTimeZone.end;
                                                    break;
                                                case "today":
                                                    t.beginTime = x(e), t.endTime = y(e);
                                                    break;
                                                case "customized":
                                                    t.beginTime = j(o.customizedBeforeDay, e), t.endTime = v(o.customizedBeforeDay, e);
                                                    break;
                                                default:
                                                    t.beginTime = o.sendTimeZone.begin, t.endTime = o.sendTimeZone.end
                                            }
                                            return t
                                        };
                                        l(!0), c({
                                            type: "setCurrentTZ",
                                            payload: {
                                                number: e.get("value"),
                                                beginTime: t(e.get("value")).beginTime,
                                                endTime: t(e.get("value")).endTime
                                            }
                                        }), c({
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
                            }, t)
                        }))
                    })
                },
                qe = function (e) {
                    var t = e.props,
                        n = Object(r.useContext)(ht),
                        i = Object(O.a)(n, 2),
                        o = i[0],
                        c = i[1],
                        s = t.alertPop,
                        l = t.StyledSelector,
                        d = E.getIn([o.langType, "cusDate"]).filter((function (e, t) {
                            return 0 !== t
                        }));
                    return Object(a.jsx)(l, {
                        children: d.map((function (e, t) {
                            var n = "".concat(e.get("label"), " ").concat(T(e.get("value"), o.currentTZ));
                            return Object(a.jsxs)("div", {
                                className: ie()("list-style ga_customdate_".concat(e.get("value")), {
                                    active: e.get("value") === o.mSelectorPopup.selectedCusDate
                                }),
                                onClick: function () {
                                    o.timeBusy ? s({
                                        text: E.getIn([o.langType, "alert", "busy"]),
                                        color: A.getIn(["accent", "red"])
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
                                    }), c({
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
                                    children: n
                                }), Object(a.jsx)("div", {
                                    className: "circle ga_customdate_".concat(e.get("value"))
                                })]
                            }, t)
                        }))
                    })
                },
                Ue = function (e) {
                    var t = e.props,
                        n = Object(r.useContext)(ht),
                        i = Object(O.a)(n, 2),
                        o = i[0],
                        c = i[1],
                        s = t.alertPop,
                        l = t.StyledSelector;
                    return Object(a.jsx)(l, {
                        children: ["300", "500", "800", "1000"].map((function (e, t) {
                            return Object(a.jsxs)("div", {
                                className: ie()("list-style ga_displaynum_".concat(e), {
                                    active: o.currentCount === e
                                }),
                                onClick: function () {
                                    o.timeBusy || o.dataList.isLoading ? (s({
                                        text: E.getIn([o.langType, "alert", "busy"]),
                                        color: A.getIn(["accent", "red"])
                                    }), c({
                                        type: "setCurrentCount",
                                        payload: {
                                            number: o.currentCount
                                        }
                                    })) : (c({
                                        type: "setStatusSelected",
                                        payload: {
                                            dataRenderDone: !1,
                                            timeBusy: !0,
                                            currentCount: e,
                                            currentOffset: "0"
                                        }
                                    }), c({
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
                            }, t)
                        }))
                    })
                };

            function Ve() {
                var e = Object(D.a)(["\n  position: absolute;\n  top:50%;\n  left:50%;\n  transform:translate(-50%,-50%);\n  z-index:2;\n  width:90%;\n  max-width:320px;\n  margin:0 auto;\n  .list-style {\n    display:flex;\n    align-items:center;\n    justify-content:space-between;\n    background-color:#fff;\n    border-bottom:1px solid #d0d1d7;\n    box-sizing:border-box;\n    padding:13px 15px;\n    &:nth-child(1) {\n      border-top-left-radius:5px;\n      border-top-right-radius:5px;\n    }\n    &:last-child {\n      border-bottom-left-radius:5px;\n      border-bottom-right-radius:5px;\n      border-bottom:none;\n    }\n    &.active {\n      .text {\n        color:#24292f;\n      }\n      .circle {\n        border: 1px solid #24292f;\n        &:before {\n          opacity:1;\n        }\n      }\n    }\n  }\n  .list-style .text {\n    font-size:15px;\n    color: #888e9e;\n  }\n  .list-style .circle {\n    position: relative;\n    border-radius:50px;\n    width:16px;\n    height:16px;\n    border: 1px solid #888e9e;\n    &:before {\n      content:'';\n      opacity:0;\n      position: absolute;\n      top:50%;\n      left:50%;\n      transform:translate(-50%,-50%);\n      width:10px;\n      height:10px;\n      border-radius:50px;\n      background-color:#24292f;\n    }\n  }\n"]);
                return Ve = function () {
                    return e
                }, e
            }

            function Je() {
                var e = Object(D.a)(["\n  position:fixed;\n  z-index:101;\n  top:0;\n  width:100%;\n  height:100vh;\n  .background {\n    position: absolute;\n    z-index:1;\n    top:0;\n    left:0;\n    width:100%;\n    height:100%;\n    background:rgba(36, 40, 48, 0.9);\n  }\n"]);
                return Je = function () {
                    return e
                }, e
            }
            var Ke = w.a.div(Je()),
                Xe = w.a.div(Ve()),
                $e = function (e) {
                    var t = e.alertPop,
                        n = e.setCallAlertStatus,
                        i = Object(r.useContext)(ht),
                        o = Object(O.a)(i, 2),
                        c = o[0],
                        s = o[1];
                    return Object(a.jsxs)(Ke, {
                        children: ["timeZone" === c.mSelectorPopup.type && Object(a.jsx)(He, {
                            props: {
                                alertPop: t,
                                setCallAlertStatus: n,
                                StyledSelector: Xe
                            }
                        }), "customized" === c.mSelectorPopup.type && Object(a.jsx)(qe, {
                            props: {
                                alertPop: t,
                                StyledSelector: Xe
                            }
                        }), "pageCount" === c.mSelectorPopup.type && Object(a.jsx)(Ue, {
                            props: {
                                alertPop: t,
                                StyledSelector: Xe
                            }
                        }), Object(a.jsx)("div", {
                            className: "background",
                            onClick: function () {
                                return s({
                                    type: "setMSelectorPopup",
                                    payload: {
                                        status: !1,
                                        type: "",
                                        selectedCusDate: c.mSelectorPopup.selectedCusDate
                                    }
                                })
                            }
                        })]
                    })
                };

            function Qe() {
                var e = Object(D.a)(["\n  position: absolute;\n  top:calc(100% + 4px);\n  width:100%;\n  z-index:2;\n  li {\n    display:flex;\n    align-items:center;\n    justify-content:center;\n    width:100%;\n    height:44px;\n    background-color:#fff;\n    border-bottom:1px solid ", ";\n    transition:.1s;\n    .text {\n      position: relative;\n      font-size:15px;\n      color:", ";\n      .svg-BsCheck {\n        opacity:0;\n        position: absolute;\n        top:50%;\n        left:0;\n        width:18px;\n        height:18px;\n        transform: translate(calc(-100% - 12px),-50%);\n        fill:", ";\n      }\n    }\n    &:nth-child(1) {\n      border-radius:5px 5px 0 0;\n    }\n    &:last-child {\n      border-radius:0 0 5px 5px;\n    }\n    &:hover {\n      background-color: ", ";\n    }\n    &.active {\n      .text .svg-BsCheck {\n        opacity:1;\n      }\n    }\n  }\n"]);
                return Qe = function () {
                    return e
                }, e
            }
            var et = w.a.ul(Qe(), A.getIn(["main", "selectedBorder"]), A.getIn(["main", "bodyBackground"]), A.getIn(["main", "bodyBackground"]), A.getIn(["main", "selected"])),
                tt = function (e) {
                    var t = e.props,
                        n = t.selectedCusDate,
                        i = t.onChangeFun,
                        o = Object(r.useContext)(ht),
                        c = Object(O.a)(o, 1)[0],
                        s = E.getIn([c.langType, "cusDate"]);
                    return Object(a.jsx)(et, {
                        children: s.map((function (e, t) {
                            return 0 === t ? null : Object(a.jsx)("li", {
                                className: ie()("ga_customdate_".concat(e.get("value")), {
                                    active: n === t
                                }),
                                onClick: function () {
                                    return i(t)
                                },
                                children: Object(a.jsxs)("div", {
                                    className: "text ga_customdate_".concat(e.get("value")),
                                    children: [Object(a.jsx)(ne.a, {
                                        className: "svg-BsCheck ga_customdate_".concat(e.get("value"))
                                    }), e.get("label"), " ", T(e.get("value"), c.currentTZ)]
                                })
                            }, t)
                        }))
                    })
                };

            function nt() {
                var e = Object(D.a)(["\n  margin-top:30px;\n  .idSearch-title {\n    font-size:15px;\n    color:#fff;\n  }\n  .btn {\n    text-align:center;\n    font-size:14px;\n    margin-top:15px;\n    padding:12px 0;\n    border-radius:5px;\n    color:", ";\n    background-color:", ";\n    transition:.2s;\n    &.focus {\n      color:", ";\n      background-color:", ";\n      cursor: pointer;\n    }\n  }\n  label {\n    width:100%;\n    position: relative;\n  }\n  .svg-BiSearch {\n    position: absolute;\n    top:50%;\n    left:15px;\n    width:18px;\n    height:18px;\n    transform:translateY(-50%);\n    fill:", ";\n  }\n  .close {\n    opacity:0;\n    position: absolute;\n    top:50%;\n    right:15px;\n    width:18px;\n    height:18px;\n    transform:translateY(-50%);\n    border-radius:50%;\n    background-color:", ";\n    transition:.2s;\n    cursor: pointer;\n    svg {\n      width:100%;\n      height:100%;\n      color:#fff;\n    }\n  }\n  .input {\n    width:100%;\n    font-size:14px;\n    margin-top:15px;\n    padding:12px 40px;\n    box-sizing:border-box;\n    border-radius:5px;\n    outline:none;\n    background-color:", ";\n    border:1px solid ", ";\n    color:", ";\n    transition:.2s;\n    &::placeholder {\n      color:", ";\n    }\n    &:focus {\n      color:#fff;\n      border:1px solid #fff;\n      &::placeholder {\n        color:#fff;\n      }\n      ~.svg-BiSearch {\n        fill:#fff;\n      }\n      ~.close {\n        opacity:1;\n      }\n    }\n  }\n"]);
                return nt = function () {
                    return e
                }, e
            }
            var at = w.a.div(nt(), A.getIn(["main", "text"]), A.getIn(["main", "button"]), A.getIn(["main", "bodyBackground"]), A.getIn(["main", "selected"]), A.getIn(["main", "textDeep"]), A.getIn(["main", "button"]), A.getIn(["main", "listBackground"]), A.getIn(["main", "buttonDarken"]), A.getIn(["main", "textDeep"]), A.getIn(["main", "textDeep"])),
                rt = function (e) {
                    var t = e.alertPop,
                        n = Object(r.useRef)(null),
                        i = Object(r.useContext)(ht),
                        o = Object(O.a)(i, 2),
                        c = o[0],
                        s = o[1],
                        l = Object(r.useState)(!1),
                        d = Object(O.a)(l, 2),
                        u = d[0],
                        p = d[1],
                        b = Object(r.useState)(c.selectedDateSearch.type),
                        g = Object(O.a)(b, 2),
                        m = g[0],
                        f = g[1];
                    return Object(a.jsxs)(at, {
                        children: [Object(a.jsx)("div", {
                            className: "idSearch-title",
                            children: E.getIn([c.langType, "numberSearch"])
                        }), Object(a.jsxs)("label", {
                            children: [Object(a.jsx)("input", {
                                className: "input",
                                ref: n,
                                placeholder: E.getIn([c.langType, "searchPlaceholder"]),
                                onFocus: function () {
                                    s({
                                        type: "setSelectedDateSearch",
                                        payload: {
                                            type: "blur"
                                        }
                                    }), "blur" !== c.selectedDateSearch.type && f(c.selectedDateSearch.type)
                                },
                                onBlur: function () {
                                    u ? n.current.focus() : s({
                                        type: "setSelectedDateSearch",
                                        payload: {
                                            type: m
                                        }
                                    })
                                }
                            }), Object(a.jsx)(ae.c, {
                                className: "svg-BiSearch",
                                onMouseOut: function () {
                                    return p(!1)
                                },
                                onMouseOver: function () {
                                    return p(!0)
                                }
                            }), Object(a.jsx)("div", {
                                className: "close",
                                onMouseOut: function () {
                                    return p(!1)
                                },
                                onMouseOver: function () {
                                    return p(!0)
                                },
                                onClick: function () {
                                    n.current.value = ""
                                },
                                children: Object(a.jsx)(oe.e, {})
                            })]
                        }), Object(a.jsx)("div", {
                            className: ie()("btn ga_quicksearch_order", {
                                focus: "blur" === c.selectedDateSearch.type
                            }),
                            onClick: function () {
                                "" !== n.current.value && (c.timeBusy || c.dataList.isLoading ? t({
                                    text: E.getIn([c.langType, "alert", "busy"]),
                                    color: A.getIn(["accent", "red"])
                                }) : (s({
                                    type: "setIdSearch",
                                    payload: {
                                        timeBusy: !0,
                                        inputCurrentVal: n.current.value,
                                        dataRenderDone: !1,
                                        isNoDataLayer: !1,
                                        selectedDateSearch: "singleSearch",
                                        timeSearchType: {
                                            type: "singleSearch",
                                            content: c.timeSearchType.content
                                        }
                                    }
                                }), Z({
                                    props: {
                                        getGameToken: c.getGameToken,
                                        rounid: n.current.value,
                                        dispatch: s
                                    }
                                }), n.current.value = ""))
                            },
                            children: E.getIn([c.langType, "btnSearch"])
                        })]
                    })
                };

            function it() {
                var e = Object(D.a)(["\n  position: absolute;\n  width:calc(100% - 80px);\n  left:40px;\n  bottom:60px;\n  .main-content {\n    position: relative;\n    display:flex;\n    align-items:center;\n    justify-content:center;\n    width:100%;\n    height:50px;\n    border-radius:5px;\n    background-color: ", ";\n    cursor: pointer;\n    &.active {\n      background-color: ", ";\n      p { \n        color:", "; \n      }\n      .svg-BiGlobe, .svg-IoIosArrowDown { \n        fill:", ";\n      }\n    }\n  }\n  .main-content p {\n    color:#fff;\n    font-size:15px;\n  }\n  .svg-BiGlobe {\n    position: absolute;\n    top:50%;\n    left:20px;\n    fill:#fff;\n    width:17px;\n    height:17px;\n    transform:translateY(-50%);\n  }\n  .svg-IoIosArrowDown {\n    position: absolute;\n    top:50%;\n    right:20px;\n    fill:#fff;\n    width:17px;\n    height:17px;\n    transform:translateY(-50%);\n  }\n  .main-content .TZ-list {\n    position: absolute;\n    bottom:calc(100% + 2px);\n    left:0;\n    width:100%;\n    .list-style {\n      display:flex;\n      align-items:center;\n      justify-content:center;\n      height:45px;\n      background-color:#fff;\n      &:nth-child(1) {\n        border-top-left-radius:5px;\n        border-top-right-radius:5px;\n      }\n      &:last-child {\n        border-bottom-left-radius:5px;\n        border-bottom-right-radius:5px;\n      }\n      &:hover {\n        background-color:", ";\n      }\n      &.active span {\n        font-weight:bold;\n        .svg-BsCheck {\n          opacity:1;\n        }\n      }\n    }\n    .list-style span {\n      position: relative;\n      font-size:15px;\n      color:", ";\n      .svg-BsCheck { \n        opacity:0;\n        position: absolute;\n        top:50%;\n        left:0;\n        width:18px;\n        height:18px;\n        transform: translate(calc(-100% - 12px),-50%);\n        fill:", ";\n      }\n    }\n  }\n"]);
                return it = function () {
                    return e
                }, e
            }
            var ot = w.a.div(it(), A.getIn(["main", "button"]), A.getIn(["main", "selected"]), A.getIn(["main", "bodyBackground"]), A.getIn(["main", "bodyBackground"]), A.getIn(["main", "selected"]), A.getIn(["main", "bodyBackground"]), A.getIn(["main", "bodyBackground"])),
                ct = function (e) {
                    var t = e.alertPop,
                        n = Object(r.useContext)(ht),
                        i = Object(O.a)(n, 2),
                        o = i[0],
                        c = i[1],
                        s = Object(r.useState)(!1),
                        l = Object(O.a)(s, 2),
                        d = l[0],
                        u = l[1],
                        p = Object(r.useState)(!1),
                        b = Object(O.a)(p, 2),
                        g = b[0],
                        m = b[1],
                        f = E.getIn([o.langType, "timeZone"]).filter((function (e) {
                            return e.get("value") === o.currentTZ
                        })),
                        h = function (e) {
                            switch (e) {
                                case "0":
                                    return "ga_timezone_eastern";
                                case "12":
                                    return "ga_timezone_beijing";
                                case "4":
                                    return "ga_timezone_london"
                            }
                        };
                    return Object(r.useEffect)((function () {
                        g && o.dataRenderDone && (t({
                            text: E.getIn([o.langType, "alert", "TZchange"]),
                            color: A.getIn(["accent", "green"])
                        }), m(!1))
                    }), [g, o.dataRenderDone, o.langType, t]), Object(r.useEffect)((function () {
                        var e = function (e) {
                            var t = document.getElementById("TZchange");
                            t && !t.contains(e.target) && u(!1)
                        };
                        return document.addEventListener("mousedown", e),
                            function () {
                                document.removeEventListener("mousedown", e)
                            }
                    }), []), Object(a.jsx)(ot, {
                        className: "ga_timezone",
                        children: Object(a.jsxs)("div", {
                            className: ie()("main-content ga_timezone", {
                                active: d
                            }),
                            onClick: function () {
                                return u((function (e) {
                                    return !e
                                }))
                            },
                            children: [Object(a.jsx)("p", {
                                className: "ga_timezone",
                                children: f.getIn([0, "label"])
                            }), Object(a.jsx)(ae.a, {
                                className: "svg-BiGlobe ga_timezone"
                            }), Object(a.jsx)(oe.b, {
                                className: "svg-IoIosArrowDown ga_timezone"
                            }), d && Object(a.jsx)("div", {
                                className: "TZ-list",
                                children: E.getIn([o.langType, "timeZone"]).map((function (e, n) {
                                    return Object(a.jsx)("div", {
                                        className: ie()("list-style ".concat(h(e.get("value"))), {
                                            active: e.get("value") === f.getIn([0, "value"])
                                        }),
                                        onClick: function () {
                                            if (o.timeBusy || o.dataList.isLoading) t({
                                                text: E.getIn([o.langType, "alert", "busy"]),
                                                color: A.getIn(["accent", "red"])
                                            });
                                            else {
                                                if (c({
                                                        type: "setTimeBusy",
                                                        payload: {
                                                            status: !0
                                                        }
                                                    }), c({
                                                        type: "setDataRenderDone",
                                                        payload: {
                                                            status: !1
                                                        }
                                                    }), "singleSearch" === o.timeSearchType.type) return c({
                                                    type: "setStaticCurrentTZ",
                                                    payload: {
                                                        number: e.get("value")
                                                    }
                                                }), void m(!0);
                                                var n = function () {
                                                    var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : 0,
                                                        t = {
                                                            beginTime: "",
                                                            endTime: ""
                                                        };
                                                    switch (o.timeSearchType.type) {
                                                        case "beforeThirty":
                                                            t.beginTime = o.sendTimeZone.begin, t.endTime = o.sendTimeZone.end;
                                                            break;
                                                        case "today":
                                                            t.beginTime = x(e), t.endTime = y(e);
                                                            break;
                                                        case "customized":
                                                            t.beginTime = j(o.customizedBeforeDay, e), t.endTime = v(o.customizedBeforeDay, e);
                                                            break;
                                                        default:
                                                            t.beginTime = o.sendTimeZone.begin, t.endTime = o.sendTimeZone.end
                                                    }
                                                    return t
                                                };
                                                c({
                                                    type: "setCurrentTZ",
                                                    payload: {
                                                        number: e.get("value"),
                                                        beginTime: n(e.get("value")).beginTime,
                                                        endTime: n(e.get("value")).endTime
                                                    }
                                                }), m(!0)
                                            }
                                        },
                                        children: Object(a.jsxs)("span", {
                                            className: "".concat(h(e.get("value"))),
                                            children: [Object(a.jsx)(ne.a, {
                                                className: "svg-BsCheck ".concat(h(e.get("value")))
                                            }), e.get("label")]
                                        })
                                    }, n)
                                }))
                            })]
                        })
                    })
                };

            function st() {
                var e = Object(D.a)(["\n  position: relative;\n  min-width:360px;\n  height:100vh;\n  min-height:800px;\n  z-index:100;\n  background-color: ", ";\n  box-sizing:border-box;\n  padding:0 40px;\n  .content {\n    width:100%;\n    padding-top:60px;\n  }\n  .date-search {\n    padding-top:60px;\n  }\n  .main-title {\n    text-align:center;\n    font-size:24px;\n    color:#fff;\n  }\n  .date-title {\n    font-size:15px;\n    color:#fff;\n  }\n  .src {\n    position: relative;\n    display:flex;\n    align-items:center;\n    justify-content:center;\n    box-sizing:border-box;\n    border-radius:5px;\n    border:1px solid ", ";\n    background-color:", ";\n    height:45px;\n    margin-top:15px;\n    transition:.2s;\n    cursor: pointer;\n    span {\n      color:#fff;\n      font-size:15px;\n      transition:.2s;\n    }\n    &.active {\n      border:1px solid ", ";\n      background-color:", ";\n      span {\n        color:", ";\n        font-weight:bold;\n      }\n    }\n    &.blur {\n      border:1px solid transparent;\n      background-color:", ";\n      span {\n        color:", ";\n      }\n    }\n    .cusDate-select {\n      position: absolute;\n      z-index:2;\n      width:100%;\n      height:100%;\n      opacity:0;\n    }\n  }\n"]);
                return st = function () {
                    return e
                }, e
            }
            var lt = w.a.div(st(), A.getIn(["main", "listBackground"]), A.getIn(["main", "border"]), A.getIn(["main", "listBackground"]), A.getIn(["main", "selected"]), A.getIn(["main", "selected"]), A.getIn(["main", "bodyBackground"]), A.getIn(["main", "buttonDarken"]), A.getIn(["main", "textDeep"])),
                dt = function (e) {
                    var t = e.alertPop,
                        n = Object(r.useContext)(ht),
                        i = Object(O.a)(n, 2),
                        o = i[0],
                        c = i[1],
                        s = Object(r.useState)(0),
                        l = Object(O.a)(s, 2),
                        d = l[0],
                        u = l[1],
                        p = Object(r.useState)(!1),
                        b = Object(O.a)(p, 2),
                        g = b[0],
                        m = b[1],
                        T = function (e) {
                            var n = e.type,
                                a = void 0 === n ? "" : n,
                                r = e.val,
                                i = void 0 === r ? 0 : r,
                                s = e.currentTZ,
                                l = void 0 === s ? 0 : s,
                                d = function (e) {
                                    var t = e.type,
                                        n = void 0 === t ? "" : t,
                                        a = e.val,
                                        r = void 0 === a ? 0 : a,
                                        i = e.currentTZ,
                                        o = void 0 === i ? 0 : i,
                                        c = {
                                            beginTime: "",
                                            endTime: ""
                                        };
                                    switch (n) {
                                        case "beforeThirty":
                                            c.beginTime = h(), c.endTime = f();
                                            break;
                                        case "today":
                                            c.beginTime = x(o), c.endTime = y(o);
                                            break;
                                        case "customized":
                                            c.beginTime = j(r, o), c.endTime = v(r, o);
                                            break;
                                        default:
                                            c.beginTime = h(), c.endTime = f()
                                    }
                                    return c
                                };
                            o.timeBusy || o.dataList.isLoading ? t({
                                text: E.getIn([o.langType, "alert", "busy"]),
                                color: A.getIn(["accent", "red"])
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
                                type: "setCustomizedBeforeDay",
                                payload: {
                                    day: i
                                }
                            }), function (e) {
                                var t = e.beginTime,
                                    n = void 0 === t ? "" : t,
                                    a = e.endTime,
                                    r = void 0 === a ? "" : a,
                                    i = e.type,
                                    o = void 0 === i ? "" : i,
                                    s = e.dateContent,
                                    l = void 0 === s ? "" : s,
                                    d = e.selectedCusDate,
                                    p = void 0 === d ? 0 : d;
                                c({
                                    type: "SearchDataUpdate",
                                    payload: {
                                        filterTypeType: "default",
                                        currentOffsetNumber: "0",
                                        sendTimeZoneBegin: n,
                                        sendTimeZoneEnd: r,
                                        timeSearchTypeType: o,
                                        timeSearchTypeContent: l,
                                        dataRenderDoneStatus: !1,
                                        selectedDateSearchType: o
                                    }
                                }), u(p)
                            }({
                                beginTime: d({
                                    type: a,
                                    val: i,
                                    currentTZ: l
                                }).beginTime,
                                endTime: d({
                                    type: a,
                                    val: i,
                                    currentTZ: l
                                }).endTime,
                                type: a,
                                dateContent: i,
                                selectedCusDate: i
                            }))
                        };
                    return Object(r.useEffect)((function () {
                        var e = function (e) {
                            var t = document.getElementById("dateSearch");
                            t && !t.contains(e.target) && m(!1)
                        };
                        return document.addEventListener("mousedown", e),
                            function () {
                                document.removeEventListener("mousedown", e)
                            }
                    }), []), Object(a.jsxs)(lt, {
                        children: [Object(a.jsxs)("div", {
                            className: "content",
                            children: [Object(a.jsx)("div", {
                                className: "main-title",
                                children: E.getIn([o.langType, "header", "title"])
                            }), Object(a.jsxs)("div", {
                                className: "date-search",
                                children: [Object(a.jsx)("div", {
                                    className: "date-title",
                                    children: E.getIn([o.langType, "dateSearch"])
                                }), Object(a.jsx)("div", {
                                    className: ie()("src ga_quicksearch_30", {
                                        active: "beforeThirty" === o.selectedDateSearch.type
                                    }, {
                                        blur: "blur" === o.selectedDateSearch.type
                                    }),
                                    onClick: function () {
                                        return T({
                                            type: "beforeThirty"
                                        })
                                    },
                                    children: Object(a.jsx)("span", {
                                        className: "ga_quicksearch_30",
                                        children: E.getIn([o.langType, "beforeThirty"])
                                    })
                                }), Object(a.jsx)("div", {
                                    className: ie()("src ga_quicksearch_today", {
                                        active: "today" === o.selectedDateSearch.type
                                    }, {
                                        blur: "blur" === o.selectedDateSearch.type
                                    }),
                                    onClick: function () {
                                        return T({
                                            type: "today",
                                            currentTZ: o.currentTZ
                                        })
                                    },
                                    children: Object(a.jsx)("span", {
                                        className: "ga_quicksearch_today",
                                        children: E.getIn([o.langType, "today"])
                                    })
                                }), Object(a.jsxs)("div", {
                                    className: ie()("src ga_quicksearch_custom", {
                                        active: "customized" === o.selectedDateSearch.type
                                    }, {
                                        blur: "blur" === o.selectedDateSearch.type
                                    }),
                                    onClick: function () {
                                        return m((function (e) {
                                            return !e
                                        }))
                                    },
                                    children: [Object(a.jsx)("span", {
                                        className: "ga_quicksearch_custom",
                                        children: E.getIn([o.langType, "customized"])
                                    }), g && Object(a.jsx)(tt, {
                                        props: {
                                            selectedCusDate: d,
                                            onChangeFun: function (e) {
                                                return T({
                                                    type: "customized",
                                                    val: e,
                                                    currentTZ: o.currentTZ
                                                })
                                            }
                                        }
                                    })]
                                })]
                            }), Object(a.jsx)(rt, {
                                alertPop: t
                            })]
                        }), Object(a.jsx)(ct, {
                            alertPop: t
                        })]
                    })
                };

            function ut() {
                var e = Object(D.a)(["\n  ", "\n"]);
                return ut = function () {
                    return e
                }, e
            }

            function pt() {
                var e = Object(D.a)(["\n  width: 100%;\n  min-height: 100vh;\n  @keyframes fade {\n    0% {\n      pointer-events: none;\n    }\n    99% {\n      pointer-events: none;\n    }\n    100% {\n      pointer-events: unset;\n    }\n  } ;\n"]);
                return pt = function () {
                    return e
                }, e
            }
            var bt = w.a.div(pt()),
                gt = w.a.div(ut(), (function (e) {
                    var t = e.RWD;
                    return !t.isMobile && !t.isTablet && "\n    display:flex;\n  "
                })),
                mt = function () {
                    var e = Object(r.useContext)(ht),
                        t = Object(O.a)(e, 2),
                        n = t[0],
                        i = t[1],
                        o = Object(r.useState)(0),
                        c = Object(O.a)(o, 2),
                        s = c[0],
                        l = c[1],
                        d = Object(r.useState)(!1),
                        u = Object(O.a)(d, 2),
                        b = u[0],
                        g = u[1],
                        m = !n.isMobile && !n.isTablet,
                        f = m && 4006 === n.dataList.code,
                        h = function (e) {
                            var t = e.type,
                                n = void 0 === t ? "default" : t,
                                a = e.data,
                                r = void 0 === a ? Object(p.a)() : a;
                            switch (n) {
                                case "default":
                                    return r;
                                case "isWin":
                                    return r.filter((function (e) {
                                        return e.get("wins") > 0
                                    }));
                                case "isLose":
                                    return r.filter((function (e) {
                                        return e.get("wins") <= 0
                                    }));
                                default:
                                    return r
                            }
                        },
                        x = function (e) {
                            var t = e.text,
                                a = void 0 === t ? "" : t,
                                r = e.color;
                            i({
                                type: "setAlertText",
                                payload: {
                                    text: a,
                                    color: void 0 === r ? "" : r
                                }
                            }), i({
                                type: "setAlertArr",
                                payload: {
                                    data: n.alertArr.update((function (e) {
                                        return e.push(e.size)
                                    }))
                                }
                            }), l((function (e) {
                                return e + 1
                            }));
                            var o = setTimeout((function () {
                                return l((function (e) {
                                    return e - 1
                                })), clearTimeout(o)
                            }), 5e3)
                        };
                    return Object(r.useEffect)((function () {
                    }), []), Object(r.useEffect)((function () {
                        R({
                            props: {
                                getGameToken: n.getGameToken,
                                beginTime: n.sendTimeZone.begin,
                                endTime: n.sendTimeZone.end,
                                offset: n.currentOffset,
                                count: n.currentCount,
                                dispatch: i
                            }
                        })
                    }), [n.getGameToken, n.sendTimeZone, n.currentOffset, n.currentCount, i]), Object(r.useEffect)((function () {
                        !n.dataList.isLoading && i({
                            type: "setShowDataList",
                            payload: {
                                data: h({
                                    type: n.filterType,
                                    data: n.dataList.data
                                })
                            }
                        })
                    }), [n.dataList.data, n.dataList.isLoading, n.filterType, i]), Object(r.useEffect)((function () {
                        var e;
                        (e = {
                            totalCount: n.dataList.totalCount,
                            currentCount: n.currentCount
                        }).totalCount / e.currentCount >= 1 ? i({
                            type: "setPagenations",
                            payload: {
                                status: !0,
                                current: Math.ceil(n.currentOffset / n.currentCount) + 1,
                                total: Math.ceil(n.dataList.totalCount / n.currentCount)
                            }
                        }) : i({
                            type: "setPagenations",
                            payload: {
                                status: !1,
                                current: 1,
                                total: 0
                            }
                        })
                    }), [n.dataList.totalCount, n.currentOffset, n.currentCount, i]), Object(r.useEffect)((function () {
                        if (n.timeBusy) var e = setTimeout((function () {
                            return i({
                                type: "setTimeBusy",
                                payload: {
                                    status: !1
                                }
                            }), clearTimeout(e)
                        }), 4e3)
                    }), [n.timeBusy, n.setTimeBusy, i]), Object(a.jsxs)(gt, {
                        RWD: {
                            isMobile: n.isMobile,
                            isTablet: n.isTablet
                        },
                        children: [m && Object(a.jsx)(dt, {
                            alertPop: x
                        }), Object(a.jsxs)(bt, {
                            "data-version": S.version,
                            children: [(!n.dataRenderDone && !m || f) && Object(a.jsx)(q, {
                                props: {
                                    langType: n.langType,
                                    code: n.dataList.code,
                                    isMobile: n.isMobile,
                                    isTablet: n.isTablet
                                }
                            }), m && Object(a.jsx)(he, {
                                alertPop: x,
                                isDesktop: m
                            }), !n.dataRenderDone && m && Object(a.jsx)(V, {
                                props: {
                                    langType: n.langType
                                }
                            }), !n.dataList.isLoading && Object(a.jsxs)(a.Fragment, {
                                children: [n.isMobile && Object(a.jsx)(he, {
                                    alertPop: x,
                                    callAlertStatus: b,
                                    setCallAlertStatus: g
                                }), Object(a.jsx)(Ze, {})]
                            }), n.isMobile && Object(a.jsx)(Ge, {
                                alertPop: x
                            }), n.isMobile && n.mSelectorPopup.status && Object(a.jsx)($e, {
                                alertPop: x,
                                setCallAlertStatus: g
                            }), n.isNoDataLayer && Object(a.jsx)(te, {
                                zIndex: 3
                            }), n.alertArr.map((function (e, t) {
                                return Object(a.jsx)($, {
                                    alertArrCount: s,
                                    alertText: n.alertText
                                }, t)
                            }))]
                        })]
                    })
                },
                ft = "zh-cn" !== u && "th" !== u && "en" !== u && "vn" !== u ? "en" : u;
            ! function (e) {
                var t = "";
                switch (e) {
                    case "zh-cn":
                        t = "\u73a9\u5bb6\u6ce8\u5355\u67e5\u8be2";
                        break;
                    case "th":
                        t = "\u0e01\u0e32\u0e23\u0e27\u0e34\u0e08\u0e31\u0e22\u0e01\u0e32\u0e23\u0e40\u0e14\u0e34\u0e21\u0e1e\u0e31\u0e19\u0e02\u0e2d\u0e07\u0e1c\u0e39\u0e49\u0e40\u0e25\u0e48\u0e19";
                        break;
                    case "vn":
                        t = "L\u1ecbch s\u1eed \u0111\u01a1n c\u01b0\u1ee3c";
                        break;
                    default:
                        t = "Player Betting Research"
                }
                document.title = t
            }(u);
            var ht = Object(r.createContext)(),
                xt = {
                    getGameToken: d,
                    langType: ft,
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
                        begin: h(),
                        end: f()
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
                yt = function (e, t) {
                    var n = t.payload;
                    switch (t.type) {
                        case "setAlertArr":
                            return Object(s.a)(Object(s.a)({}, e), {}, {
                                alertArr: n.data
                            });
                        case "setAlertText":
                            return Object(s.a)(Object(s.a)({}, e), {}, {
                                alertText: {
                                    text: n.text,
                                    color: n.color
                                }
                            });
                        case "setStaticCurrentTZ":
                            return Object(s.a)(Object(s.a)({}, e), {}, {
                                filterType: "default",
                                currentTZ: n.number,
                                dataRenderDone: !0
                            });
                        case "setCurrentTZ":
                            return Object(s.a)(Object(s.a)({}, e), {}, {
                                currentOffset: "0",
                                filterType: "default",
                                currentTZ: n.number,
                                sendTimeZone: {
                                    begin: n.beginTime,
                                    end: n.endTime
                                }
                            });
                        case "setCurrentCount":
                            return Object(s.a)(Object(s.a)({}, e), {}, {
                                currentCount: n.number
                            });
                        case "setCurrentOffset":
                            return Object(s.a)(Object(s.a)({}, e), {}, {
                                currentOffset: n.number
                            });
                        case "setDataList":
                            return Object(s.a)(Object(s.a)({}, e), {}, {
                                dataList: {
                                    isLoading: n.isLoading,
                                    data: n.data,
                                    code: n.code,
                                    offset: n.offset,
                                    totalCount: n.totalCount
                                }
                            });
                        case "setDataRenderDone":
                            return Object(s.a)(Object(s.a)({}, e), {}, {
                                dataRenderDone: n.status
                            });
                        case "setFilterType":
                            return Object(s.a)(Object(s.a)({}, e), {}, {
                                filterType: n.type
                            });
                        case "setPagenations":
                            return Object(s.a)(Object(s.a)({}, e), {}, {
                                pagenations: {
                                    status: n.status,
                                    current: n.current,
                                    total: n.total
                                }
                            });
                        case "setSendTimeZone":
                            return Object(s.a)(Object(s.a)({}, e), {}, {
                                sendTimeZone: {
                                    begin: n.begin,
                                    end: n.end
                                }
                            });
                        case "setShowDataList":
                            return Object(s.a)(Object(s.a)({}, e), {}, {
                                showDataList: n.data
                            });
                        case "setIsNoDataLayer":
                            return Object(s.a)(Object(s.a)({}, e), {}, {
                                isNoDataLayer: n.status,
                                selectedDateSearch: {
                                    type: e.searchTypeCatch.type
                                },
                                timeSearchType: {
                                    type: e.searchTypeCatch.type,
                                    content: e.timeSearchType.content
                                }
                            });
                        case "setInputCurrentVal":
                            return Object(s.a)(Object(s.a)({}, e), {}, {
                                inputCurrentVal: n.text
                            });
                        case "setTimeBusy":
                            return Object(s.a)(Object(s.a)({}, e), {}, {
                                timeBusy: n.status
                            });
                        case "setTimeSearchType":
                            return Object(s.a)(Object(s.a)({}, e), {}, {
                                timeSearchType: {
                                    type: n.type,
                                    content: n.content
                                }
                            });
                        case "setSelectedDateSearch":
                            return Object(s.a)(Object(s.a)({}, e), {}, {
                                selectedDateSearch: {
                                    type: n.type
                                }
                            });
                        case "setCustomizedBeforeDay":
                            return Object(s.a)(Object(s.a)({}, e), {}, {
                                customizedBeforeDay: n.day
                            });
                        case "setMSearchPopup":
                            return Object(s.a)(Object(s.a)({}, e), {}, {
                                mSearchPopup: n.status
                            });
                        case "setMSelectorPopup":
                            return Object(s.a)(Object(s.a)({}, e), {}, {
                                mSelectorPopup: {
                                    status: n.status,
                                    type: n.type,
                                    selectedCusDate: n.selectedCusDate
                                }
                            });
                        case "SearchDataUpdate":
                            return Object(s.a)(Object(s.a)({}, e), {}, {
                                filterType: n.filterTypeType,
                                currentOffset: n.currentOffsetNumber,
                                dataRenderDone: n.dataRenderDoneStatus,
                                sendTimeZone: {
                                    begin: n.sendTimeZoneBegin,
                                    end: n.sendTimeZoneEnd
                                },
                                timeSearchType: {
                                    type: n.timeSearchTypeType,
                                    content: n.timeSearchTypeContent
                                },
                                selectedDateSearch: {
                                    type: n.selectedDateSearchType
                                },
                                searchTypeCatch: {
                                    type: n.selectedDateSearchType
                                }
                            });
                        case "mobileSearchPageDataUpdate":
                            return Object(s.a)(Object(s.a)({}, e), {}, {
                                filterType: n.filterTypeType,
                                currentOffset: n.currentOffsetNumber,
                                mSearchPopup: n.mSearchPopupStatus,
                                dataRenderDone: n.dataRenderDoneStatus,
                                sendTimeZone: {
                                    begin: n.sendTimeZoneBegin,
                                    end: n.sendTimeZoneEnd
                                },
                                timeSearchType: {
                                    type: n.timeSearchTypeType,
                                    content: n.timeSearchTypeContent
                                },
                                selectedDateSearch: {
                                    type: n.selectedDateSearchType
                                },
                                searchTypeCatch: {
                                    type: n.selectedDateSearchType
                                }
                            });
                        case "setIdSearch":
                            return Object(s.a)(Object(s.a)({}, e), {}, {
                                timeBusy: n.timeBusy,
                                inputCurrentVal: n.inputCurrentVal,
                                dataRenderDone: n.dataRenderDone,
                                isNoDataLayer: n.isNoDataLayer,
                                selectedDateSearch: n.selectedDateSearch,
                                timeSearchType: n.timeSearchType
                            });
                        case "setStatusSelected":
                            var a = n.currentCount === e.currentCount,
                                r = n.currentOffset === e.currentOffset;
                            return a && r ? e : Object(s.a)(Object(s.a)({}, e), {}, {
                                dataRenderDone: n.dataRenderDone,
                                timeBusy: n.timeBusy,
                                currentCount: n.currentCount,
                                currentOffset: n.currentOffset
                            });
                        case "getIDsuccess":
                            return Object(s.a)(Object(s.a)({}, e), {}, {
                                dataRenderDone: n.DataRenderDoneStatus,
                                isNoDataLayer: n.IsNoDataLayerStatus,
                                mSearchPopup: n.MSearchPopupStatus
                            });
                        case "getIDfaile":
                            return Object(s.a)(Object(s.a)({}, e), {}, {
                                dataRenderDone: n.DataRenderDoneStatus,
                                mSearchPopup: n.MSearchPopupStatus
                            });
                        default:
                            return e
                    }
                },
                jt = function () {
                    var e = Object(r.useReducer)(yt, xt);
                    return Object(a.jsx)(ht.Provider, {
                        value: e,
                        children: Object(a.jsx)(mt, {})
                    })
                },
                vt = function (e) {
                    e && e instanceof Function && n.e(3).then(n.bind(null, 101)).then((function (t) {
                        var n = t.getCLS,
                            a = t.getFID,
                            r = t.getFCP,
                            i = t.getLCP,
                            o = t.getTTFB;
                        n(e), a(e), r(e), i(e), o(e)
                    }))
                };
            c.a.render(Object(a.jsx)(i.a.StrictMode, {
                children: Object(a.jsx)(jt, {})
            }), document.getElementById("root")), vt()
        },
        42: function (e) {
            e.exports = JSON.parse('{"name":"megalon","version":"0.2.1","private":true,"dependencies":{"@testing-library/jest-dom":"^5.11.4","@testing-library/react":"^11.1.0","@testing-library/user-event":"^12.1.10","axios":"^0.21.1","classnames":"^2.2.6","current-device":"^0.10.1","http-proxy-middleware":"^1.0.6","immutable":"^4.0.0-rc.12","lodash":"^4.17.20","moment-timezone":"^0.5.32","numeral":"^2.0.6","react":"^17.0.1","react-dom":"^17.0.1","react-hot-loader":"^4.13.0","react-icons":"^4.1.0","react-loader-spinner":"^3.1.14","react-scripts":"4.0.1","react-spinners":"^0.10.4","react-virtualized-auto-sizer":"^1.0.4","react-window":"^1.8.6","styled-components":"^5.2.1","web-vitals":"^0.2.4"},"scripts":{"start":"react-scripts start","build":"react-scripts build","test":"react-scripts test","eject":"react-scripts eject"},"eslintConfig":{"extends":["react-app","react-app/jest"]},"browserslist":{"production":[">0.2%","not dead","not op_mini all"],"development":["last 1 chrome version","last 1 firefox version","last 5 safari version"]},"homepage":"./"}')
        }
    },
    [
        [100, 1, 2]
    ]
]);
