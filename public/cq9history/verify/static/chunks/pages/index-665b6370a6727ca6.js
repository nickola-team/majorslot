(self.webpackChunk_N_E = self.webpackChunk_N_E || []).push([
    [405], {
        5557: function(n, t, e) {
            (window.__NEXT_P = window.__NEXT_P || []).push(["/", function() {
                return e(5290)
            }])
        },
        4967: function(n, t, e) {
            "use strict";
            e.d(t, {
                Yu: function() {
                    return i
                }
            });
            let i = function(n) {
                let t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : [],
                    e = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : "",
                    i = n,
                    s = Array.isArray(t) && t.length > 0;
                if (void 0 === n && null === n || !s) return e;
                for (let a = 0; a < t.length; a++) i = void 0 !== i[t[a]] ? i[t[a]] : e;
                return i
            }
        },
        8834: function(n, t, e) {
            "use strict";
            e.d(t, {
                Z: function() {
                    return l
                }
            });
            var i = e(7297),
                s = e(5893),
                a = e(9521);

            function o() {
                let n = (0, i.Z)(["\n  position: fixed;\n  z-index: 1;\n  top: 0;\n  left: 0;\n  width: 100%;\n  height: 100vh;\n  background-image: linear-gradient(\n    45deg,\n    ", ",\n    ", " 25%,\n    #000 25%,\n    #000 50%,\n    ", " 50%,\n    ", " 75%,\n    #000 75%\n  );\n  background-size: ", "px ", "px;\n  pointer-events: none;\n"]);
                return o = function() {
                    return n
                }, n
            }
            let c = "#F9BE00",
                r = a.ZP.div.withConfig({
                    componentId: "sc-24ad2bf-0"
                })(o(), c, c, c, c, 96, 96);

            function l() {
                return (0, s.jsx)(r, {})
            }
        },
        8057: function(n, t, e) {
            "use strict";
            e.d(t, {
                $A: function() {
                    return o
                },
                AV: function() {
                    return a
                },
                UC: function() {
                    return c
                },
                qn: function() {
                    return r
                }
            });
            var i = e(7484),
                s = e.n(i);
            let a = {
                    desktop: 1024,
                    tablet: 768
                },
                o = function() {
                    let n = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : 0,
                        t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : "default";
                    return "".concat(s()(new Date).tz("America/Caracas").add(n, "day").format("YYYY-MM-DDT".concat("end" === t ? "23:59:59" : "start" === t ? "00:00:00" : "HH:mm:ss")), "-04:00")
                },
                c = function() {
                    let n = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : "",
                        t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : "v1";
                    switch (n) {
                        case "zh-cn":
                        case "ko":
                            return n;
                        default:
                            return "v2" === t ? "ko" : "en"
                    }
                },
                r = function() {
                    let n = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : "",
                        t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : 0,
                        e = n.split("T"),
                        i = e[1] ? e[1].split("-") : "",
                        a = s()("".concat(e[0], " ").concat(i[0])).add(t, "hours").format("YYYY/MM/DD HH:mm:ss");
                    return "Invalid Date" === a ? "-" : a
                }
        },
        5290: function(n, t, e) {
            "use strict";
            e.r(t), e.d(t, {
                default: function() {
                    return J
                }
            });
            var i = e(7297),
                s = e(5893),
                a = e(7294),
                o = e(5152),
                c = e.n(o),
                r = e(9008),
                l = e.n(r),
                d = e(5675),
                p = e.n(d),
                h = e(1163),
                x = e(9521),
                f = e(8057);
            let u = "guardians.one",
                g = "rd3-",
                m = {
                    dev: "".concat(g, "dev-gapi.").concat(u, "/api"),
                    qa: "".concat(g, "qa-gapi.").concat(u, "/api"),
                    int: "".concat(g, "int-gapi.").concat(u, "/api"),
                    clinetInt: "gapi.cqgame.games/api",
                    fifaInt: "int-fifaworldcup2022.rebirth.games/api",
                    prod: "gapi.".concat("cq9site.com", "/api"),
                    fifaProd: "fifaworldcup2022.rebirth.games/api"
                },
                b = n => {
                    let {
                        getDev: t = "dev-",
                        getQa: e = "qa-",
                        getInt: i = "int-",
                        getFifaInt: s = "int-fifaworldcup2022",
                        getFifaProd: a = "fifaworldcup2022",
                        isclinetInt: o = !1,
                        protocol: c = "https:",
                        host: r = ""
                    } = n, l = !!~r.indexOf(":"), d = !!~r.indexOf(t), p = !!~r.indexOf(e), h = !!~r.indexOf(i), x = !!~r.indexOf(s), f = !!~r.indexOf(a);
                    return l || d ? "".concat(c, "//").concat(m.dev) : p ? "".concat(c, "//").concat(m.qa) : h ? o ? "".concat(c, "//").concat(m.clinetInt) : "".concat(c, "//").concat(m.int) : x ? "".concat(c, "//").concat(m.fifaInt) : f ? "".concat(c, "//").concat(m.fifaProd) : "".concat(c, "//").concat(m.prod)
                };
            var v = e(4967),
                y = e(4318),
                j = e(4147),
                w = e(4760),
                k = e(8834);

            function N() {
                let n = (0, i.Z)(["\n  display: flex;\n  align-items: center;\n  justify-content: ", ";\n  img {\n    transform: ", ";\n    width: 14px;\n    height: 14px;\n  }\n  img:last-child,\n  img:nth-last-child(2) {\n    display: none;\n  }\n  @media screen and (min-width: ", "px) {\n    img:last-child,\n    img:nth-last-child(2) {\n      display: block;\n    }\n    img {\n      width: 22px;\n      height: 22px;\n    }\n  }\n  @media screen and (min-width: ", "px) {\n    img {\n      width: 24px;\n      height: 24px;\n    }\n  }\n"]);
                return N = function() {
                    return n
                }, n
            }
            let C = x.ZP.div.withConfig({
                componentId: "sc-795ec77c-0"
            })(N(), n => {
                let {
                    type: t
                } = n;
                return "left" === t ? "flex-start" : "flex-end"
            }, n => {
                let {
                    type: t
                } = n;
                return "rotateY(".concat("left" === t ? 180 : 0, "deg)")
            }, f.AV.tablet, f.AV.desktop);

            function z(n) {
                let {
                    type: t = "left"
                } = n, e = Array(8).fill(null).map((n, t) => t);
                return (0, s.jsx)(C, {
                    type: t,
                    children: e.map((n, t) => (0, s.jsx)(p(), {
                        src: "/imgs/arrow.png",
                        alt: "",
                        width: 28,
                        height: 28
                    }, t))
                })
            }

            function A() {
                let n = (0, i.Z)(["\n  margin-top: 15px;\n  .domain-info-module {\n    display: flex;\n    height: 60px;\n    border-top: 2px solid #000;\n    &:last-child {\n      border-bottom: 2px solid #000;\n    }\n    &-info {\n      display: flex;\n      align-items: center;\n      justify-content: center;\n      font-size: 15px;\n      &.left {\n        border-left: 2px solid #000;\n        width: 22%;\n      }\n      &.right {\n        border-left: 2px solid #000;\n        border-right: 2px solid #000;\n        width: 78%;\n        word-break: break-all;\n      }\n      .dr-text {\n        max-width: calc(100% - 24px);\n        line-height: 18px;\n      }\n    }\n  }\n  @media screen and (min-width: ", "px) {\n    margin-top: 25px;\n    .domain-info-module {\n      &-info {\n        font-size: 16px;\n        .dr-text {\n          line-height: 24px;\n        }\n        &.left {\n          width: 12%;\n        }\n        &.right {\n          width: 88%;\n          justify-content: flex-start;\n          padding-left: 24px;\n        }\n      }\n    }\n  }\n  @media screen and (min-width: ", "px) {\n    .domain-info-module {\n      &-info {\n        font-size: 18px;\n        &.left {\n          width: 10%;\n        }\n        &.right {\n          width: 90%;\n        }\n      }\n    }\n  }\n"]);
                return A = function() {
                    return n
                }, n
            }
            let q = x.ZP.div.withConfig({
                componentId: "sc-a3deb5eb-0"
            })(A(), f.AV.tablet, f.AV.desktop);

            function Y(n) {
                let {
                    lang: t = "en"
                } = n, e = n => {
                    let {
                        domain: e = history_url +"/platform/"
                    } = n;
                    return (0, s.jsxs)("div", {
                        className: "domain-info-module",
                        children: [(0, s.jsx)("div", {
                            className: "domain-info-module-info left",
                            children: w[t].s3static[1]
                        }), (0, s.jsx)("div", {
                            className: "domain-info-module-info right",
                            children: (0, s.jsx)("div", {
                                className: "dr-text",
                                children: e
                            })
                        })]
                    })
                };
                return (0, s.jsx)(q, {
                    children: (0, s.jsx)(e, {})
                })
            }
            var Q = e(7484),
                I = e.n(Q),
                Z = e(2077),
                P = e.n(Z);

            function _() {
                let n = (0, i.Z)(["\n  background-color: #30323e;\n  border-radius: 6px;\n  margin-top: 16px;\n  overflow: hidden;\n  .mobile-type {\n    padding: 0 16px;\n  }\n  .desktop-type {\n    display: none;\n  }\n  .desktop-type .captions {\n    box-sizing: border-box;\n    padding: 20px 40px;\n    color: #fff;\n    font-size: 16px;\n    display: flex;\n    align-items: center;\n    span {\n      width: 30%;\n    }\n    span:nth-child(4) {\n      width: 10%;\n    }\n    &.up {\n      background-color: #484c5c;\n    }\n    .list2 {\n      color: #888d9f;\n    }\n    .wins {\n      color: #01ba80;\n    }\n  }\n  .pb-list {\n    color: #fff;\n    font-size: 16px;\n    display: flex;\n    align-items: center;\n    justify-content: space-between;\n    padding: 16px 0;\n    border-bottom: 1px solid #888d9f;\n    &:last-child {\n      border-bottom: 0;\n    }\n    .list2 {\n      color: #888d9f;\n      text-align: right;\n    }\n    .wins {\n      color: #01ba80;\n    }\n  }\n  @media screen and (min-width: ", "px) {\n    margin-top: 24px;\n    .mobile-type {\n      padding: 0 24px;\n    }\n  }\n  @media screen and (min-width: ", "px) {\n    margin-top: 20px;\n    .mobile-type {\n      display: none;\n    }\n    .desktop-type {\n      display: block;\n    }\n  }\n"]);
                return _ = function() {
                    return n
                }, n
            }
            let T = x.ZP.div.withConfig({
                componentId: "sc-dfd00403-0"
            })(_(), f.AV.tablet, f.AV.desktop);

            function O(n) {
                let {
                    lang: t = "en",
                    timezone: e = 0,
                    data: i = [],
                    dataIsLoading: a = !0
                } = n, o = () => {
                    let n = (0, v.Yu)(i, [0, "nameset"], []),
                        e = (0, v.Yu)(i, [0, "gamename"], "-"),
                        s = n.find(n => n.lang === t) || {};
                    return (0, v.Yu)(s, ["name"], e)
                }, c = (0, v.Yu)(i, [0, "createtime"]), r = P()((0, v.Yu)(i, [0, "wins"], 0)).format("0.00");
                return (0, s.jsxs)(T, {
                    children: [(0, s.jsxs)("div", {
                        className: "mobile-type",
                        children: [(0, s.jsxs)("div", {
                            className: "pb-list",
                            children: [(0, s.jsx)("span", {
                                children: w[t].s4static[1]
                            }), (0, s.jsx)("span", {
                                children: a ? "loading..." : o()
                            })]
                        }), (0, s.jsxs)("div", {
                            className: "pb-list",
                            children: [(0, s.jsx)("span", {
                                children: w[t].s4static[2]
                            }), (0, s.jsx)("span", {
                                className: "list2",
                                children: a ? "loading..." : (0, f.qn)(c, e)
                            })]
                        }), (0, s.jsxs)("div", {
                            className: "pb-list",
                            children: [(0, s.jsx)("span", {
                                children: w[t].s4static[3]
                            }), (0, s.jsx)("span", {
                                children: a ? "loading..." : (0, v.Yu)(i, [0, "roundid"], "-")
                            })]
                        }), (0, s.jsxs)("div", {
                            className: "pb-list",
                            children: [(0, s.jsx)("span", {
                                children: w[t].s4static[4]
                            }), (0, s.jsxs)("span", {
                                className: "wins",
                                children: [r > 0 && "+", a ? "loading..." : r]
                            })]
                        })]
                    }), (0, s.jsxs)("div", {
                        className: "desktop-type",
                        children: [(0, s.jsxs)("div", {
                            className: "captions up",
                            children: [(0, s.jsx)("span", {
                                children: w[t].s4static[1]
                            }), (0, s.jsx)("span", {
                                children: w[t].s4static[2]
                            }), (0, s.jsx)("span", {
                                children: w[t].s4static[3]
                            }), (0, s.jsx)("span", {
                                children: w[t].s4static[4]
                            })]
                        }), (0, s.jsxs)("div", {
                            className: "captions down",
                            children: [(0, s.jsx)("span", {
                                className: "",
                                children: a ? "loading..." : o()
                            }), (0, s.jsx)("span", {
                                className: "list2",
                                children: a ? "loading..." : (0, f.qn)(c, e)
                            }), (0, s.jsx)("span", {
                                className: "",
                                children: a ? "loading..." : (0, v.Yu)(i, [0, "roundid"], "-")
                            }), (0, s.jsx)("span", {
                                className: "wins",
                                children: a ? "loading..." : r
                            })]
                        })]
                    })]
                })
            }
            var E = e(9734);
            let V = new Headers({
                    "Content-Type": "text/json",
                    token: "d2777be739f7674d1299bdafa8050f36d1c0cfd3904e241d6a756c08c5b6bfdf"
                }),
                D = {
                    method: "GET",
                    headers: V
                },
                B = n => fetch(n, D).then(n => n.json()),
                R = n => {
                    let {
                        apiurl: t = "",
                        params: e = {}
                    } = n, {
                        token: i = "",
                        begin: s = "",
                        end: a = "",
                        offset: o = "0",
                        count: c = "1"
                    } = e, {
                        data: r,
                        error: l,
                        isLoading: d,
                        isValidating: p,
                        mutate: h
                    } = (0, E.ZP)(i ? "/api/player_betting/search_time?token=".concat(i, "&begin=").concat(s, "&end=").concat(a, "&offset=").concat(o, "&count=").concat(c) : "", B, {
                        revalidateOnFocus: !1,
                        revalidateOnReconnect: !1
                    });
                    return {
                        playerBettingData: r ? r.result : {},
                        playerBettingError: l,
                        playerBettingIsLoading: d,
                        playerBettingIsValidating: p,
                        playerBettingMutate: h
                    }
                };
            var H = e(178),
                M = e.n(H),
                S = e(9387),
                F = e.n(S);

            function $() {
                let n = (0, i.Z)(["\n  position: fixed;\n  z-index: 2;\n  top: ", "px;\n  left: ", "px;\n  width: calc(100vw - ", "px);\n  height: calc(100vh - ", "px);\n  box-sizing: border-box;\n  padding: 25px;\n  background-color: #fff;\n  color: #1a1a1a;\n  overflow: auto;\n  .wrap {\n    max-width: 1000px;\n    margin: 0 auto;\n  }\n  .flex-module {\n    display: flex;\n    align-items: center;\n    justify-content: center;\n  }\n  .logo {\n    width: 100px;\n    height: 50px;\n  }\n  h1 {\n    color: #e50913;\n    text-align: center;\n    font-size: 30px;\n    border: solid 3px #e50914;\n    padding: 8px 12px;\n    margin-top: 45px;\n  }\n  /* s1 */\n  .s1 {\n    text-align: center;\n    font-size: 16px;\n    line-height: 24px;\n    margin-top: 24px;\n    .s1-tablet-s1 {\n      display: none;\n    }\n  }\n  /* s2 */\n  .s2 {\n    margin-top: 40px;\n  }\n  .s2-static {\n    text-align: center;\n    font-size: 20px;\n    line-height: 36px;\n  }\n  /* s3 */\n  .s3-static {\n    margin-top: 25px;\n    font-size: 18px;\n  }\n  /* s4 */\n  .s4-static {\n    font-size: 18px;\n    margin-top: 24px;\n  }\n  /* s5 */\n  .s5 {\n    margin-top: 25px;\n  }\n  .s5-static {\n    font-size: 16px;\n  }\n  .telegram-qrcode {\n    margin-top: 8px;\n    img {\n      display: block;\n      margin: 0 auto;\n    }\n    .sub {\n      text-align: center;\n      font-size: 16px;\n      line-height: 18px;\n    }\n  }\n  .mobile-buffer {\n    height: 50px;\n  }\n  @media screen and (min-width: ", "px) {\n    top: ", "px;\n    left: ", "px;\n    width: calc(100vw - ", "px);\n    height: calc(100vh - ", "px);\n    padding: 35px;\n    .logo {\n      width: 120px;\n      height: 60px;\n    }\n    h1 {\n      font-size: 42px;\n      line-height: 1.4;\n      border: solid 5px #e50914;\n      padding: 10px 20px;\n      margin-top: 40px;\n    }\n    /* s1 */\n    .s1 {\n      font-size: 18px;\n      line-height: 30px;\n      margin-top: 35px;\n      .s1-tablet-s1 {\n        display: inline;\n      }\n      .s1-mobile-s1 {\n        display: none;\n      }\n    }\n    /* s2 */\n    .s2 {\n      display: flex;\n      align-items: center;\n      justify-content: space-between;\n    }\n    .s2-static {\n      font-size: 24px;\n    }\n    /* s3 */\n    .s3-static {\n      margin-top: 40px;\n      font-size: 20px;\n    }\n    /* s4 */\n    .s4-static {\n      font-size: 20px;\n      margin-top: 30px;\n    }\n    /* s5 */\n    .s5 {\n      margin-top: 40px;\n    }\n    .telegram-qrcode {\n      margin-top: 16px;\n    }\n  }\n  @media screen and (min-width: ", "px) {\n    padding: 75px;\n    .logo {\n      width: 150px;\n      height: 75px;\n    }\n    h1 {\n      font-size: 48px;\n      line-height: 1;\n      padding: 16px 20px;\n      border: solid 8px #e50914;\n      margin-top: 15px;\n    }\n    /* s1 */\n    .s1 {\n      font-size: 24px;\n      line-height: 40px;\n    }\n    /* s2 */\n    .s2 {\n      margin-top: 60px;\n    }\n    .s2-static {\n      font-size: 32px;\n    }\n    /* s3 */\n    .s3-static {\n      margin-top: 45px;\n      font-size: 24px;\n    }\n    /* s4 */\n    .s4-static {\n      font-size: 24px;\n      margin-top: 65px;\n    }\n  }\n"]);
                return $ = function() {
                    return n
                }, n
            }
            I().extend(M()), I().extend(F());
            let G = x.ZP.div.withConfig({
                    componentId: "sc-9a50d7fb-0"
                })($(), 8, 8, 16, 16, f.AV.tablet, 16, 16, 32, 32, f.AV.desktop),
                L = () => {
                    let n = (0, v.Yu)((0, h.useRouter)(), ["query", "token"]),
                        t = (0, f.UC)((0, v.Yu)((0, h.useRouter)(), ["query", "lang"], "en")),
                        e = (0, v.Yu)((0, h.useRouter)(), ["query", "timezone"], 0),
                        [i, o] = (0, a.useState)({
                            begin: (0, f.$A)(0, "start"),
                            end: (0, f.$A)(0, "end")
                        }),
                        [c, r] = (0, a.useState)(0),
                        {
                            playerBettingData: d,
                            playerBettingIsLoading: x
                        } = R({
                            apiurl: b({
                                host: window.location.host
                            }),
                            params: {
                                token: n,
                                begin: i.begin,
                                end: i.end
                            }
                        });
                    return (0, a.useEffect)(() => {
                        if (c > 6) return;
                        let t = (0, v.Yu)(d, ["data", "list"], "none");
                        "none" !== t && 0 === t.length && n && (r(n => n += 1), o({
                            begin: (0, f.$A)(-1 * c, "start"),
                            end: (0, f.$A)(-1 * c, "end")
                        }))
                    }, [d, c]), (0, s.jsxs)(s.Fragment, {
                        children: [(0, s.jsxs)(l(), {
                            children: [(0, s.jsx)("title", {
                                children: "Website identify"
                            }), (0, s.jsx)("meta", {
                                name: "description",
                                content: ""
                            }), (0, s.jsx)("meta", {
                                name: "viewport",
                                content: "width=device-width, initial-scale=1"
                            }), (0, s.jsx)("link", {
                                rel: "icon",
                                href: "/cq9history/verify/image/favicon.ico"
                            })]
                        }), (0, s.jsx)(y.Z, {}), (0, s.jsx)(k.Z, {}), (0, s.jsx)(G, {
                            "data-version": j.i8,
                            children: (0, s.jsxs)("div", {
                                className: "wrap",
                                children: [(0, s.jsx)(p(), {
                                    className: "logo",
                                    alt: "",
                                    src: "/imgs/logo.png",
                                    width: 150,
                                    height: 75
                                }), (0, s.jsx)("div", {
                                    className: "flex-module",
                                    children: (0, s.jsx)("h1", {
                                        children: w[t].h1
                                    })
                                }), (0, s.jsxs)("div", {
                                    className: "s1",
                                    children: [(0, s.jsxs)("div", {
                                        style: {
                                            textAlign: "center"
                                        },
                                        children: [(0, s.jsx)("span", {
                                            children: w[t].s1static[0]
                                        }), (0, s.jsx)("span", {
                                            className: "s1-tablet-s1",
                                            children: w[t].s1static[1]
                                        })]
                                    }), (0, s.jsx)("div", {
                                        className: "s1-mobile-s1",
                                        children: w[t].s1static[1]
                                    }), (0, s.jsx)("div", {
                                        children: w[t].s1static[2]
                                    })]
                                }), (0, s.jsxs)("div", {
                                    className: "s2",
                                    children: [(0, s.jsx)(z, {
                                        type: "left"
                                    }), (0, s.jsxs)("div", {
                                        className: "s2-static",
                                        children: [(0, s.jsx)("span", {
                                            children: w[t].s2static[0]
                                        }), (0, s.jsx)("span", {
                                            style: {
                                                color: "#e50914"
                                            },
                                            children: w[t].s2static[1]
                                        })]
                                    }), (0, s.jsx)(z, {
                                        type: "right"
                                    })]
                                }), (0, s.jsxs)("div", {
                                    className: "s3",
                                    children: [(0, s.jsx)("div", {
                                        className: "s3-static",
                                        children: w[t].s3static[0]
                                    }), (0, s.jsx)(Y, {
                                        lang: t
                                    })]
                                }), n && (0, s.jsxs)(s.Fragment, {
                                    children: [(0, s.jsx)("div", {
                                        className: "s4-static",
                                        children: w[t].s4static[0]
                                    }), (0, s.jsx)(O, {
                                        lang: t,
                                        timezone: e,
                                        data: (0, v.Yu)(d, ["data", "list"], []),
                                        dataIsLoading: x
                                    })]
                                })]
                            })
                        })]
                    })
                },
                U = c()(() => Promise.resolve(L), {
                    ssr: !1
                });

            function J() {
                return (0, s.jsx)(U, {})
            }
        },
        4318: function(n, t, e) {
            "use strict";
            e.d(t, {
                Z: function() {
                    return o
                }
            });
            var i = e(7297),
                s = e(9521);

            function a() {
                let n = (0, i.Z)(["\n/*\nhtml5doctor.com Reset Stylesheet\nv1.6.1\nLast Updated: 2010-09-17\nAuthor: Richard Clark - http://richclarkdesign.com\nTwitter: @rich_clark\n*/\nhtml, body, div, span, object, iframe,\nh1, h2, h3, h4, h5, h6, p, blockquote, pre,\nabbr, address, cite, code,\ndel, dfn, em, img, ins, kbd, q, samp,\nsmall, strong, sub, sup, var,\nb, i,\ndl, dt, dd, ol, ul, li,\nfieldset, form, label, legend,\ntable, caption, tbody, tfoot, thead, tr, th, td,\narticle, aside, canvas, details, figcaption, figure,\nfooter, header, hgroup, menu, nav, section, summary,\ntime, mark, audio, video {\n    margin:0;\n    padding:0;\n    border:0;\n    outline:0;\n    font-size:100%;\n    vertical-align:baseline;\n    background:transparent;\n}\n\nbody {\n    line-height:1;\n    font-family:  PingFangSC-Semibold, Arial, Microsoft YaHei;\n}\n\narticle,aside,details,figcaption,figure,\nfooter,header,hgroup,menu,nav,section {\n    display:block;\n}\n\nnav ul {\n    list-style:none;\n}\n\nblockquote, q {\n    quotes:none;\n}\n\nblockquote:before, blockquote:after,\nq:before, q:after {\n    content:'';\n    content:none;\n}\n\na {\n    margin:0;\n    padding:0;\n    font-size:100%;\n    vertical-align:baseline;\n    background:transparent;\n}\n\n/* change colours to suit your needs */\nins {\n    background-color:#ff9;\n    color:#000;\n    text-decoration:none;\n}\n\n/* change colours to suit your needs */\nmark {\n    background-color:#ff9;\n    color:#000;\n    font-style:italic;\n    font-weight:bold;\n}\n\ndel {\n    text-decoration: line-through;\n}\n\nabbr[title], dfn[title] {\n    border-bottom:1px dotted;\n    cursor:help;\n}\n\ntable {\n    border-collapse:collapse;\n    border-spacing:0;\n}\n\n/* change border colour to suit your needs */\nhr {\n    display:block;\n    height:1px;\n    border:0;  \n    border-top:1px solid #cccccc;\n    margin:1em 0;\n    padding:0;\n}\n\ninput, select {\n    vertical-align:middle;\n}\n"]);
                return a = function() {
                    return n
                }, n
            }
            let o = (0, s.vJ)(a())
        },
        4147: function(n) {
            "use strict";
            n.exports = {
                i8: "0.1.0"
            }
        },
        4760: function(n) {
            "use strict";
            n.exports = JSON.parse('{"zh-cn":{"h1":"正版声明公告","s1static":["近期仿冒CQ9本公司网站猖獗，","您所玩的游戏若未经正版授权，","储值资金将面临巨大风险，請务必多加提防，以防权益受损。"],"s2static":["完成以下动作"," [验证正版游戏]"],"s3static":["一、验证正版CQ9玩家查询注单网域","网域"],"s4static":["二、验证您在本站上次游玩的游戏","游戏名称","成单时间","交易单号","净利"],"s5static":"如有疑虑或欲举报盗版网站游戏，请联系我们","v2s0static":["请点击CQ9官方 Telegram 频道","@verifyCQ9"],"v2s1static":["近期仿冒CQ9本公司游戏猖獗，若非正版授权的游戏将面临资金风险，请务必加以验证。"],"v2s2static":["验证您玩的是正版CQ9游戏"],"v2s3static":["step1. 请截图含有游戏单号的游戏画面。"],"v2s4static":["step2. 请点击CQ9官方 Telegram 频道 ","@verifyCQ9","，在聊天室提供游戏截图。"],"v2s5static":["step3. 我们将尽快验证您所玩的游戏。"],"v2s6static":["如有疑虑或欲举报盗版网站游戏，请联系我们"]},"en":{"h1":"Genuine Copy Announcement","s1static":["Piracy is prevalent on this site recently,","if the game you are playing is not genuine,","your top-up funds will be at a huge risk. Please be careful."],"s2static":["Complete the following tasks"," [verify genuine game]"],"s3static":["1. Authenticate the Genuine CQ9 Player Betting Research Domain","Domain"],"s4static":["2. Authenticate the last game you played on this website","Game Name","Established Time","Order No.","Net Income"],"s5static":"If you have any concerns or want to report a pirated content, please contact us.","v2s0static":["CQ9 공식 Telegram  채널","@verifyCQ9"],"v2s1static":["최근 CQ9의 게임에 대한 위조가 만연하고 있습니다. 공식적으로 승인되지 않은 게임은 금전적 위험에 직면할 수 있으니 꼭 확인하시기 바랍니다."],"v2s2static":["정품 CQ9 게임인지 확인합니다."],"v2s3static":["step1. 게임 번호가 포함된 게임 화면을 스크린샷으로 찍어주세요."],"v2s4static":["step2. CQ9 공식 Telegram  채널 ","@verifyCQ9"," 를 클릭한 후 채팅방에서 게임 스크린샷을 제공하세요."],"v2s5static":["stpe3. 최대한 빨리 귀하의 게임을 확인하겠습니다."],"v2s6static":["If you have any concerns or want to report a pirated content, please contact us."]},"ko":{"h1":"정품 공고","s1static":["최근 당사 홈페이지의 위조가 만연하고 있어,","본인이 플레이하는 게임이 정품인증이 아닌 경우,","예치금이 손실하지 않고 귀하의 권익이 손상되지 않도록 주의하세요."],"s2static":["다음 작업을 완료하세요"," [정품 게임 확인]"],"s3static":["1. 정품 CQ9 플레이어 쿼리 도메인 확인","도메인"],"s4static":["2. 이 사이트에서 마지막으로 플레이한 게임을 확인합니다","게임 이름","주문 시간","거래 번호","순이익"],"s5static":"의심스러운 점이 있거나 불법 복제 웹사이트 게임을 신고하려면 연락주세요.","v2s0static":["CQ9 공식 Telegram  채널","@verifyCQ9"],"v2s1static":["최근 CQ9의 게임에 대한 위조가 만연하고 있습니다. 공식적으로 승인되지 않은 게임은 금전적 위험에 직면할 수 있으니 꼭 확인하시기 바랍니다."],"v2s2static":["정품 CQ9 게임인지 확인합니다"],"v2s3static":["step1. 게임 번호가 포함된 게임 화면을 스크린샷으로 찍어주세요."],"v2s4static":["step2. CQ9 공식 Telegram  채널 ","@verifyCQ9"," 를 클릭한 후 채팅방에서 게임 스크린샷을 제공하세요."],"v2s5static":["step3. 최대한 빨리 귀하의 게임을 확인하겠습니다."],"v2s6static":["의심스러운 점이 있거나 불법 복제 웹사이트 게임을 신고하려면 연락주세요."]}}')
        }
    },
    function(n) {
        n.O(0, [303, 244, 774, 888, 179], function() {
            return n(n.s = 5557)
        }), _N_E = n.O()
    }
]);