"use strict";
(self.webpackChunk_bithumb_pc = self.webpackChunk_bithumb_pc || []).push([
    [6762], {
        12003: function(e, t, n) {
            n.r(t), n.d(t, {
                default: function() {
                    return po
                }
            });
            var i, r = n(46140),
                o = n(38153),
                a = n(9967),
                s = n(88212),
                c = n.n(s),
                l = n(93953),
                d = n(76546),
                _ = n(34626),
                m = n(31087),
                u = n(24103),
                p = c().bind({
                    chart: "Chart_chart__Yu6tW"
                }),
                f = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.service.subscribeTradeStatus,
                        n = e.sessionService.getCustom;
                    return (0, u.jsx)("div", {
                        className: p("chart"),
                        style: {
                            display: n(_._28.hideChart) ? "none" : "block"
                        },
                        children: (0, u.jsx)(m.Z, {
                            width: 998,
                            height: 445,
                            subscribeOrderSuccess: function(e) {
                                return t((function(t) {
                                    t.type === _.OJD.orderSuccess && e()
                                }))
                            }
                        })
                    })
                })),
                h = {
                    "history-contract": "Contract_history-contract__IneoN",
                    "history-contract__sticky": "Contract_history-contract__sticky__vtJOO",
                    "history-contract-table": "Contract_history-contract-table__qpafb",
                    "trade-pending-table--header": "Contract_trade-pending-table--header__AjZhj",
                    "history-contract-table__left": "Contract_history-contract-table__left__ZYSXp",
                    "history-contract-table__right": "Contract_history-contract-table__right__Lyr3T",
                    "history-contract-table__text-red": "Contract_history-contract-table__text-red__ZDhVN",
                    "history-contract-table__text-blue": "Contract_history-contract-table__text-blue__GCIUz",
                    "history-contract-table__text-opacity": "Contract_history-contract-table__text-opacity__s2XjT",
                    "history-contract-table__none": "Contract_history-contract-table__none__Za745",
                    "auto-scale": "Contract_auto-scale__A0eBF",
                    "history-contract-table__scale-box": "Contract_history-contract-table__scale-box__kZsQX",
                    "history-contract-table__line": "Contract_history-contract-table__line__EMxuR",
                    "history-contract-bottom": "Contract_history-contract-bottom__Zvfbm",
                    "history-contract-bottom__button": "Contract_history-contract-bottom__button__oJexk",
                    "history-contract-timeout": "Contract_history-contract-timeout__R3EVC",
                    "history-contract-dot": "Contract_history-contract-dot__2KKcw",
                    "history-contract-dot__loading": "Contract_history-contract-dot__loading__1wrRk"
                },
                g = n(60006),
                x = n(93493),
                b = JSON.parse('{"page.tradeApiTimeout.msg001":"The service cannot be used as the {{tagBr/}}network is not smooth. {{tagBr/}}Please try again later.","page.tradeApiTimeout.msg002":"The service cannot be used as the network is not smooth. {{tagBr/}}Please try again later."}'),
                v = JSON.parse('{"page.tradeApiTimeout.msg001":"\ub124\ud2b8\uc6cc\ud06c\uac00 \uc6d0\ud65c\ud558\uc9c0 \uc54a\uc544 {{tagBr/}}\uc11c\ube44\uc2a4 \uc774\uc6a9\uc774 \ubd88\uac00\ud569\ub2c8\ub2e4. {{tagBr/}}\uc7a0\uc2dc \ud6c4 \ub2e4\uc2dc \uc2dc\ub3c4\ud574 \uc8fc\uc138\uc694.","page.tradeApiTimeout.msg002":"\ub124\ud2b8\uc6cc\ud06c\uac00 \uc6d0\ud65c\ud558\uc9c0 \uc54a\uc544 \uc11c\ube44\uc2a4 \uc774\uc6a9\uc774 \ubd88\uac00\ud569\ub2c8\ub2e4. {{tagBr/}}\uc7a0\uc2dc \ud6c4 \ub2e4\uc2dc \uc2dc\ub3c4\ud574 \uc8fc\uc138\uc694."}'),
                y = c().bind({
                    "trade-api-timeout--coin-info": "TradeApiTimeout_trade-api-timeout--coin-info__Xx2Yd",
                    "trade-api-timeout__paragraph": "TradeApiTimeout_trade-api-timeout__paragraph__ZQA7S",
                    "trade-api-timeout--order-history": "TradeApiTimeout_trade-api-timeout--order-history__0PINm"
                }),
                j = (0, l.Pi)((function(e) {
                    var t = e.className,
                        n = e.caseStyle,
                        o = (0, r.G2)().localeService.locale;
                    return (0, u.jsx)("div", {
                        className: y("trade-api-timeout", {
                            "trade-api-timeout--coin-info": n === i.coinInfo
                        }, {
                            "trade-api-timeout--order-history": n === i.orderHistory
                        }, t),
                        children: (0, u.jsxs)("p", {
                            className: y("trade-api-timeout__paragraph"),
                            children: [n === i.coinInfo && (0, u.jsx)(u.Fragment, {
                                children: o("page.tradeApiTimeout.msg001", {
                                    tagBr: (0, u.jsx)("br", {})
                                })
                            }), n === i.orderHistory && (0, u.jsx)(u.Fragment, {
                                children: o("page.tradeApiTimeout.msg002", {
                                    tagBr: (0, u.jsx)("br", {})
                                })
                            })]
                        })
                    })
                }));
            ! function(e) {
                e.coinInfo = "coin-info", e.orderHistory = "order-history"
            }(i || (i = {}));
            var N, k = (0, r.FU)(j, {
                    ko: v,
                    en: b
                }),
                C = n(50611),
                S = n(34999),
                w = n(26958),
                T = n.n(w),
                P = n(94438),
                I = c().bind(h),
                D = (0, l.Pi)((function(e) {
                    var t = e.orderHistories,
                        n = (0, r.G2)(),
                        i = n.localeService.locale,
                        o = n.coinService,
                        a = o.getMarketInfo,
                        s = o.getCoinInfo,
                        c = n.sessionService,
                        l = c.login,
                        d = c.language,
                        _ = (0, S.PZ)().getInstance;
                    return l ? (0, u.jsx)(u.Fragment, {
                        children: t.length > 0 ? t.map((function(e, t) {
                            var n, r = "1" !== e.tradeTypeCd,
                                o = "2" === e.orderDiviType,
                                c = "1" === e.orderPatternCd,
                                l = a(e.crncCd),
                                m = s(e.coinType);
                            return r && o ? n = i("page.trade.msg038") : r && !o ? n = i(c ? "page.trade.msg046" : "page.trade.msg040") : !r && o ? n = i("page.trade.msg039") : r || o || (n = i(c ? "page.trade.msg047" : "page.trade.msg041")), (0, u.jsxs)("tr", {
                                children: [(0, u.jsx)("td", {
                                    className: I("history-contract-table__left"),
                                    children: T()(e.contractDate).format("YYYY-MM-DD HH:mm:ss")
                                }), (0, u.jsx)("td", {
                                    className: I("history-contract-table__left"),
                                    children: "".concat(m.coinSymbol, " / ").concat(l.marketSymbol)
                                }), (0, u.jsx)("td", {
                                    className: I(r ? "history-contract-table__text-red" : "history-contract-table__text-blue"),
                                    children: n
                                }), (0, u.jsx)("td", {
                                    className: I("history-contract-table__right"),
                                    children: e.contractQty ? (0, u.jsxs)("div", {
                                        className: I("history-contract-table__scale-box"),
                                        children: [(0, u.jsx)(P.Z, {
                                            className: I("auto-scale"),
                                            children: _(e.contractQty).toFormat()
                                        }), (0, u.jsxs)("span", {
                                            children: ["\xa0", m.coinSymbol]
                                        })]
                                    }) : "-"
                                }), (0, u.jsx)("td", {
                                    className: I("history-contract-table__right"),
                                    children: c ? i("page.trade.msg125") : (0, u.jsxs)(u.Fragment, {
                                        children: [_(e.unitPrice).toFormat(), (0, u.jsxs)("span", {
                                            children: [" ", (0, C.c0)(l.marketSymbol, d)]
                                        })]
                                    })
                                }), (0, u.jsx)("td", {
                                    className: I("history-contract-table__right"),
                                    children: "0" === e.contractPrice ? "-" : (0, u.jsxs)(u.Fragment, {
                                        children: [_(e.contractPrice).toFormat(), (0, u.jsxs)("span", {
                                            children: [" ", (0, C.c0)(l.marketSymbol, d)]
                                        })]
                                    })
                                }), (0, u.jsx)("td", {
                                    className: I("history-contract-table__right"),
                                    children: e.contractAmt ? (0, u.jsxs)("div", {
                                        className: I("history-contract-table__scale-box"),
                                        children: [(0, u.jsx)(P.Z, {
                                            className: I("auto-scale"),
                                            children: _(e.contractAmt).toFormat()
                                        }), (0, u.jsxs)("span", {
                                            children: [" ", (0, C.c0)(l.marketSymbol, d)]
                                        })]
                                    }) : "-"
                                }), (0, u.jsx)("td", {
                                    className: I("history-contract-table__right"),
                                    children: T()(e.orderDate).format("YYYY-MM-DD HH:mm:ss")
                                })]
                            }, "".concat(e.orderNo, "_").concat(t))
                        })) : (0, u.jsx)("tr", {
                            children: (0, u.jsx)("td", {
                                className: I("history-contract-table__none"),
                                colSpan: 8,
                                children: i("page.trade.msg048")
                            })
                        }, 0)
                    }) : (0, u.jsx)("tr", {
                        children: (0, u.jsx)("td", {
                            className: I("history-contract-table__none"),
                            colSpan: 8,
                            children: i("page.trade.msg048")
                        })
                    }, 0)
                })),
                O = n(29048),
                Z = c().bind({
                    "history-controls": "Controls_history-controls__bbTev",
                    "history-controls__left": "Controls_history-controls__left__Q9HWW",
                    "history-controls__right": "Controls_history-controls__right__fwifj",
                    "history-controls__button": "Controls_history-controls__button__mTBKU",
                    "history-controls__cancel-button": "Controls_history-controls__cancel-button__v+rdr",
                    "history-controls-list": "Controls_history-controls-list__HSlx2",
                    "history-controls-list__item": "Controls_history-controls-list__item__bmJSU",
                    "history-controls-select": "Controls_history-controls-select__1HlG8",
                    "history-controls-select__label": "Controls_history-controls-select__label__LLjtv",
                    "history-controls-select__select": "Controls_history-controls-select__select__eVPlu"
                }),
                A = "pending",
                F = "contract",
                M = (0, l.Pi)((function(e) {
                    var t = e.type,
                        n = e.defaultMarket,
                        i = e.defaultType,
                        s = void 0 === i ? _.qTS.ALL : i,
                        c = e.callback,
                        l = e.isCancelPending,
                        d = e.fnCancel,
                        m = e.arrCancelList,
                        p = (0, r.G2)(),
                        f = p.localeService.locale,
                        h = p.coinService.getMarketList,
                        x = (0, O.Vy)(),
                        b = (0, a.useRef)(!1),
                        v = (0, a.useState)(n),
                        y = (0, o.Z)(v, 2),
                        j = y[0],
                        N = y[1],
                        k = (0, a.useState)(s),
                        C = (0, o.Z)(k, 2),
                        S = C[0],
                        w = C[1],
                        T = t === A;
                    (0, a.useEffect)((function() {
                        N(n), w(s)
                    }), [n, s]), (0, a.useEffect)((function() {
                        b.current ? j && c && c(j, S) : b.current = !0
                    }), [j, S, t]);
                    var P = (0, a.useMemo)((function() {
                        switch (t) {
                            case A:
                                return x.ORDER_HISTORY;
                            case F:
                                return x.TRADE_HISTORY
                        }
                    }), [t]);
                    return (0, u.jsxs)("div", {
                        className: Z("history-controls"),
                        children: [(0, u.jsxs)("div", {
                            className: Z("history-controls__left"),
                            children: [(0, u.jsx)("div", {
                                className: Z("history-controls-select"),
                                children: (0, u.jsxs)("label", {
                                    className: Z("history-controls-select__label"),
                                    htmlFor: "marketTypeSelect",
                                    children: [(0, u.jsx)("span", {
                                        className: "blind",
                                        children: "\ub9c8\ucf13 \uc885\ub958 \uc120\ud0dd"
                                    }), (0, u.jsxs)("select", {
                                        id: "marketTypeSelect",
                                        className: Z("history-controls-select__select"),
                                        value: j,
                                        onChange: function(e) {
                                            return N(e.target.value)
                                        },
                                        children: [(0, u.jsx)("option", {
                                            value: "ALL",
                                            children: f("page.trade.msg025")
                                        }, "ALL"), h().map((function(e) {
                                            return (0, u.jsx)("option", {
                                                value: e.crncCd,
                                                children: e.marketSymbol
                                            }, e.crncCd)
                                        }))]
                                    })]
                                })
                            }), (0, u.jsx)("div", {
                                className: Z("history-controls-select"),
                                children: (0, u.jsxs)("label", {
                                    className: Z("history-controls-select__label"),
                                    htmlFor: "orderTypeSelect",
                                    children: [(0, u.jsx)("span", {
                                        className: "blind",
                                        children: "\uc8fc\ubb38 \uc885\ub958 \uc120\ud0dd"
                                    }), (0, u.jsxs)("select", {
                                        id: "orderTypeSelect",
                                        className: Z("history-controls-select__select"),
                                        value: s,
                                        onChange: function(e) {
                                            return w(e.target.value)
                                        },
                                        children: [(0, u.jsx)("option", {
                                            value: _.qTS.ALL,
                                            children: f("page.trade.msg026")
                                        }), (0, u.jsx)("option", {
                                            value: _.qTS.BUY_ONLY,
                                            children: f("page.trade.msg027")
                                        }), (0, u.jsx)("option", {
                                            value: _.qTS.SELL_ONLY,
                                            children: f("page.trade.msg028")
                                        }), T && (0, u.jsx)("option", {
                                            value: _.qTS.RESERVATION_ONLY,
                                            children: f("page.trade.msg029")
                                        })]
                                    })]
                                })
                            }), (0, u.jsx)(g.ZP, {
                                className: Z("history-controls__button"),
                                to: P,
                                children: f("page.trade.msg024")
                            })]
                        }), T && (0, u.jsx)("div", {
                            className: Z("history-controls__right"),
                            children: (0, u.jsx)(g.ZP, {
                                type: g.PD.DefaultNew,
                                color: g.n5.Primary,
                                className: Z("history-controls__cancel-button"),
                                disabled: !l,
                                onClick: function() {
                                    d && d(m || [], !1)
                                },
                                children: f("page.trade.msg206")
                            })
                        })]
                    })
                })),
                R = c().bind(h),
                B = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.localeService.locale,
                        n = e.coinService,
                        s = n.getCoin.coinType,
                        c = n.getMarket.crncCd,
                        l = e.socketService.subscribeAssetChange,
                        d = e.sessionService.login,
                        m = e.httpService.get,
                        p = (0, a.useRef)(""),
                        f = (0, a.useState)(!1),
                        h = (0, o.Z)(f, 2),
                        b = h[0],
                        v = h[1],
                        y = (0, a.useState)(!1),
                        j = (0, o.Z)(y, 2),
                        N = j[0],
                        C = j[1],
                        S = (0, a.useState)(!1),
                        w = (0, o.Z)(S, 2),
                        T = w[0],
                        P = w[1],
                        I = (0, a.useRef)(!1),
                        O = (0, a.useState)({
                            market: c,
                            type: _.qTS.ALL
                        }),
                        Z = (0, o.Z)(O, 2),
                        A = Z[0],
                        F = Z[1],
                        B = (0, a.useState)([]),
                        L = (0, o.Z)(B, 2),
                        E = L[0],
                        U = L[1],
                        q = function(e) {
                            d && !I.current && (e && C(!0), I.current = !0, m("/v2/trade/orders/contract", {
                                coinType: s,
                                crncCd: "ALL" === A.market ? null : A.market,
                                orderKind: A.type,
                                pageOffset: p.current,
                                pageLimit: 50
                            }, !1, null, !0).then((function(t) {
                                200 === t.status && (P(!1), U(e ? t.data.contractOrderList : E.concat(t.data.contractOrderList)), p.current = t.data.nextPageOffset, v(t.data.hasNextPage))
                            })).catch((function() {
                                P(!0)
                            })).finally((function() {
                                I.current = !1, C(!1)
                            })))
                        },
                        G = function(e, t) {
                            p.current = "", F({
                                market: e,
                                type: t
                            })
                        };
                    return (0, a.useEffect)((function() {
                        G(c, _.qTS.ALL)
                    }), [s, c]), (0, a.useEffect)((function() {
                        var e;
                        return p.current = "", v(!1), U([]), d && (q(!0), e = l((function(e) {
                                if (e.list) {
                                    var t = !1,
                                        n = !1;
                                    e.list.forEach((function(e) {
                                        e.coinType === s && (t = !0), e.coinType !== A.market && A.market !== _.qTS.ALL || (n = !0)
                                    })), t && n && (I.current = !1, p.current = "", q(!0))
                                }
                            }))),
                            function() {
                                e && e()
                            }
                    }), [d, A]), (0, u.jsxs)("div", {
                        className: R("history-contract"),
                        children: [(0, u.jsxs)("div", {
                            className: R("history-contract__sticky"),
                            children: [(0, u.jsx)(M, {
                                type: "contract",
                                defaultType: A.type,
                                defaultMarket: A.market,
                                callback: G
                            }), (0, u.jsx)("div", {
                                className: R("history-contract-table"),
                                children: (0, u.jsxs)("table", {
                                    children: [(0, u.jsx)("caption", {
                                        children: "\uccb4\uacb0 \uc8fc\ubb38"
                                    }), (0, u.jsxs)("colgroup", {
                                        children: [(0, u.jsx)("col", {
                                            style: {
                                                width: "145px"
                                            }
                                        }), (0, u.jsx)("col", {}), (0, u.jsx)("col", {
                                            style: {
                                                width: "95px"
                                            }
                                        }), (0, u.jsx)("col", {
                                            style: {
                                                width: "130px"
                                            }
                                        }), (0, u.jsx)("col", {
                                            style: {
                                                width: "130px"
                                            }
                                        }), (0, u.jsx)("col", {
                                            style: {
                                                width: "130px"
                                            }
                                        }), (0, u.jsx)("col", {
                                            style: {
                                                width: "130px"
                                            }
                                        }), (0, u.jsx)("col", {
                                            style: {
                                                width: "145px"
                                            }
                                        })]
                                    }), (0, u.jsx)("thead", {
                                        children: (0, u.jsxs)("tr", {
                                            children: [(0, u.jsxs)("th", {
                                                className: R("history-contract-table__left"),
                                                scope: "col",
                                                children: [t("page.trade.msg049"), (0, u.jsx)("div", {
                                                    className: R("history-contract-table__line")
                                                })]
                                            }), (0, u.jsxs)("th", {
                                                className: R("history-contract-table__left"),
                                                scope: "col",
                                                children: [t("page.trade.msg031"), (0, u.jsx)("div", {
                                                    className: R("history-contract-table__line")
                                                })]
                                            }), (0, u.jsxs)("th", {
                                                scope: "col",
                                                children: [t("page.trade.msg032"), (0, u.jsx)("div", {
                                                    className: R("history-contract-table__line")
                                                })]
                                            }), (0, u.jsxs)("th", {
                                                className: R("history-contract-table__right"),
                                                scope: "col",
                                                children: [t("page.trade.msg050"), (0, u.jsx)("div", {
                                                    className: R("history-contract-table__line")
                                                })]
                                            }), (0, u.jsxs)("th", {
                                                className: R("history-contract-table__right"),
                                                scope: "col",
                                                children: [t("page.trade.msg035"), (0, u.jsx)("div", {
                                                    className: R("history-contract-table__line")
                                                })]
                                            }), (0, u.jsxs)("th", {
                                                className: R("history-contract-table__right"),
                                                scope: "col",
                                                children: [t("page.trade.msg051"), (0, u.jsx)("div", {
                                                    className: R("history-contract-table__line")
                                                })]
                                            }), (0, u.jsxs)("th", {
                                                className: R("history-contract-table__right"),
                                                scope: "col",
                                                children: [t("page.trade.msg052"), (0, u.jsx)("div", {
                                                    className: R("history-contract-table__line")
                                                })]
                                            }), (0, u.jsxs)("th", {
                                                className: R("history-contract-table__right"),
                                                scope: "col",
                                                children: [t("page.trade.msg030"), (0, u.jsx)("div", {
                                                    className: R("history-contract-table__line")
                                                })]
                                            })]
                                        })
                                    }), (0, u.jsx)("tbody", {
                                        children: T ? (0, u.jsx)("tr", {
                                            children: (0, u.jsx)("td", {
                                                colSpan: 9,
                                                className: R("history-contract-timeout"),
                                                children: (0, u.jsx)(k, {
                                                    caseStyle: i.orderHistory
                                                })
                                            })
                                        }) : N ? (0, u.jsx)("tr", {
                                            children: (0, u.jsx)("td", {
                                                colSpan: 9,
                                                className: R("history-contract-dot"),
                                                children: (0, u.jsx)(x.k_, {
                                                    type: x.G0.Vertical,
                                                    className: R("history-contract-dot__loading")
                                                })
                                            })
                                        }) : (0, u.jsx)(D, {
                                            orderHistories: E
                                        })
                                    })]
                                })
                            })]
                        }), b && (0, u.jsx)("div", {
                            className: R("history-contract-bottom"),
                            children: (0, u.jsx)(g.ZP, {
                                className: R("history-contract-bottom__button"),
                                onClick: function() {
                                    return q()
                                },
                                children: t("page.trade.msg043")
                            })
                        })]
                    })
                })),
                L = n(40242),
                E = {
                    "trade-pending": "Pending_trade-pending__X6I3j",
                    "trade-pending__sticky": "Pending_trade-pending__sticky__RvzEc",
                    "trade-pending-table": "Pending_trade-pending-table__eGjBA",
                    "trade-pending-table__left": "Pending_trade-pending-table__left__lvP0V",
                    "trade-pending-table__right": "Pending_trade-pending-table__right__x8aYF",
                    "trade-pending-table__line": "Pending_trade-pending-table__line__kgsEL",
                    "trade-pending-table__text-red": "Pending_trade-pending-table__text-red__G0VFk",
                    "trade-pending-table__text-blue": "Pending_trade-pending-table__text-blue__OYc2d",
                    "trade-pending-table__none": "Pending_trade-pending-table__none__t6x+Z",
                    "auto-scale": "Pending_auto-scale__oNNc2",
                    "trade-pending-table__scale-box": "Pending_trade-pending-table__scale-box__SeQuH",
                    "trade-pending-table__cancel": "Pending_trade-pending-table__cancel__fTYER",
                    "trade-pending-bottom": "Pending_trade-pending-bottom__QClHJ",
                    "trade-pending-bottom__button": "Pending_trade-pending-bottom__button__FzghV",
                    "trading-pending-timeout": "Pending_trading-pending-timeout__WYWZB",
                    "trading-pending-dot": "Pending_trading-pending-dot__X4MPg",
                    "trading-pending-dot__loading": "Pending_trading-pending-dot__loading__5zmXL"
                },
                U = n(36296),
                q = c().bind(E),
                G = (0, l.Pi)((function(e) {
                    var t = e.unitPrice,
                        n = e.marketSymbol,
                        i = (0, r.G2)().sessionService.language,
                        o = (0, S.PZ)().getInstance;
                    return (0, u.jsx)("td", {
                        className: q("trade-pending-table__right"),
                        children: "0" === t ? "-" : (0, u.jsxs)(u.Fragment, {
                            children: [o(t).toFormat(), (0, u.jsxs)("span", {
                                children: [" ", (0, C.c0)(n, i)]
                            })]
                        })
                    })
                })),
                H = c().bind(E),
                Q = function(e) {
                    var t = e.orderQty,
                        n = e.coinSymbol,
                        i = (0, S.PZ)().getInstance;
                    return (0, u.jsx)("td", {
                        className: H("trade-pending-table__right"),
                        children: "0" === t ? "-" : (0, u.jsxs)("div", {
                            className: H("trade-pending-table__scale-box"),
                            children: [(0, u.jsx)(P.Z, {
                                className: H("auto-scale"),
                                children: i(t).toFormat()
                            }), (0, u.jsxs)("span", {
                                children: ["\xa0", n]
                            })]
                        })
                    })
                },
                K = c().bind(E),
                Y = function(e) {
                    var t = e.orderQty,
                        n = e.contractQty,
                        i = e.coinSymbol,
                        r = (0, S.PZ)().getInstance;
                    return (0, u.jsx)("td", {
                        className: K("trade-pending-table__right"),
                        children: "0" === t ? "-" : (0, u.jsxs)("div", {
                            className: K("trade-pending-table__scale-box"),
                            children: [(0, u.jsx)(P.Z, {
                                className: K("auto-scale"),
                                children: r(t).minus(n).toFormat()
                            }), (0, u.jsxs)("span", {
                                children: ["\xa0", i]
                            })]
                        })
                    })
                },
                V = c().bind(E),
                W = (0, l.Pi)((function(e) {
                    var t = e.bIsAuto,
                        n = e.watchPrice,
                        i = e.marketSymbol,
                        o = e.sWatchType,
                        a = (0, r.G2)().sessionService.language,
                        s = (0, S.PZ)().getInstance;
                    return (0, u.jsx)("td", {
                        className: V("trade-pending-table__right"),
                        children: t && "0" !== n ? (0, u.jsxs)(u.Fragment, {
                            children: [o, " ", s(n).toFormat(), " ", (0, C.c0)(i, a)]
                        }) : "-"
                    })
                })),
                z = c().bind(E),
                X = (0, l.Pi)((function(e) {
                    var t = e.cancelList,
                        n = e.pendingOrderList,
                        i = e.onCancelOrderChecked,
                        o = e.onCancelOrderUnChecked,
                        a = e.onOrderCancel,
                        s = (0, r.G2)(),
                        c = s.sessionService.login,
                        l = s.localeService.locale,
                        d = s.coinService,
                        _ = d.getCoinInfo,
                        m = d.getMarketInfo,
                        p = function(e, t, n) {
                            return l(t ? e ? "page.trade.msg038" : "page.trade.msg039" : n ? e ? "page.trade.msg245" : "page.trade.msg246" : e ? "page.trade.msg040" : "page.trade.msg041")
                        };
                    return c ? (0, u.jsx)(u.Fragment, {
                        children: n.length > 0 ? n.map((function(e) {
                            var n = "1" !== e.tradeTypeCd,
                                r = "0" !== e.watchPrice && "" !== e.watchTypeCd,
                                s = m(e.crncCd),
                                c = _(e.coinType),
                                d = t.some((function(t) {
                                    return t.orderNo === e.orderNo
                                })),
                                f = "";
                            return r && "0" !== e.watchPrice && ("UP" === e.watchTypeCd ? f = "\u2265 " : "DN" === e.watchTypeCd && (f = "\u2264 ")), (0, u.jsxs)("tr", {
                                children: [(0, u.jsx)("td", {
                                    className: z("trade-pending-table__left"),
                                    children: (0, u.jsx)(U.ZP, {
                                        type: U.Su.CircleOrange,
                                        size: U.VD.Size20,
                                        textNone: !0,
                                        label: "\ubbf8\uccb4\uacb0 \uc8fc\ubb38 \uc120\ud0dd",
                                        onChange: function(t) {
                                            t.target.checked ? i(e) : o(e)
                                        },
                                        checked: d
                                    })
                                }), (0, u.jsx)("td", {
                                    children: T()(e.orderDate).format("YYYY-MM-DD HH:mm:ss")
                                }), (0, u.jsx)("td", {
                                    children: "".concat(c.coinSymbol, " / ").concat(s.marketSymbol)
                                }), (0, u.jsx)("td", {
                                    children: (0, u.jsx)("strong", {
                                        className: z(n ? "trade-pending-table__text-red" : "trade-pending-table__text-blue"),
                                        children: p(n, r, e.multipleRental)
                                    })
                                }), (0, u.jsx)(Q, {
                                    orderQty: e.orderQty,
                                    coinSymbol: c.coinSymbol
                                }), (0, u.jsx)(Y, {
                                    orderQty: e.orderQty,
                                    contractQty: e.contractQty,
                                    coinSymbol: c.coinSymbol
                                }), (0, u.jsx)(G, {
                                    unitPrice: e.unitPrice,
                                    marketSymbol: s.marketSymbol
                                }), (0, u.jsx)(W, {
                                    bIsAuto: r,
                                    watchPrice: e.watchPrice,
                                    marketSymbol: s.marketSymbol,
                                    sWatchType: f
                                }), (0, u.jsx)("td", {
                                    children: (0, u.jsx)(g.ZP, {
                                        className: z("trade-pending-table__cancel"),
                                        onClick: function() {
                                            a(e)
                                        },
                                        children: l("button.msg08")
                                    })
                                })]
                            }, e.orderNo)
                        })) : (0, u.jsx)("tr", {
                            children: (0, u.jsx)("td", {
                                className: z("trade-pending-table__none"),
                                colSpan: 9,
                                children: l("page.trade.msg048")
                            })
                        })
                    }) : (0, u.jsx)("tr", {
                        children: (0, u.jsx)("td", {
                            className: z("trade-pending-table__none"),
                            colSpan: 9,
                            children: l("page.trade.msg048")
                        })
                    })
                })),
                J = n(84841),
                $ = n.n(J),
                ee = n(45149),
                te = n(45738),
                ne = n(15822),
                ie = c().bind({
                    "trade-cancel-modal": "CancelModal_trade-cancel-modal__C6f1A",
                    "trade-cancel-modal__title": "CancelModal_trade-cancel-modal__title__+b8gQ",
                    "modal-confirm-modal__text": "CancelModal_modal-confirm-modal__text__mGHqX",
                    "trade-cancel-modal-warning": "CancelModal_trade-cancel-modal-warning__kcJPz",
                    "trade-cancel-modal-warning__text": "CancelModal_trade-cancel-modal-warning__text__SrlLA",
                    "trade-cancel-modal-table": "CancelModal_trade-cancel-modal-table__zCtXD",
                    "trade-cancel-modal-table__text-gray": "CancelModal_trade-cancel-modal-table__text-gray__BFRCc",
                    "auto-scale__box": "CancelModal_auto-scale__box__c7Pbg",
                    "auto-scale": "CancelModal_auto-scale__70E6e",
                    "modal-confirm-modal-state": "CancelModal_modal-confirm-modal-state__Blax5",
                    "modal-confirm-modal-state__text": "CancelModal_modal-confirm-modal-state__text__rOvz0",
                    "modal-confirm-modal-state__text-sub": "CancelModal_modal-confirm-modal-state__text-sub__aRJME",
                    "modal-confirm-button": "CancelModal_modal-confirm-button__OwYEl"
                }),
                re = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.modalService,
                        n = t.visible,
                        i = t.hideModal,
                        s = e.localeService.locale,
                        c = e.httpService.post,
                        l = e.coinService,
                        d = l.getCoin.infoOnMarket,
                        m = l.getMarketInfo,
                        p = l.getCoinInfo,
                        f = l.getIntroUnitPrice,
                        h = e.sessionService.language,
                        x = e.toastService.addToast,
                        b = e.gaService.fnGASendEvent,
                        v = n(ne.dSS),
                        y = (0, a.useState)(null),
                        j = (0, o.Z)(y, 2),
                        N = j[0],
                        k = j[1],
                        S = (0, a.useState)(null),
                        w = (0, o.Z)(S, 2),
                        I = w[0],
                        D = w[1],
                        O = (null === v || void 0 === v ? void 0 : v.params.order) || [],
                        Z = (null === v || void 0 === v ? void 0 : v.params.isSingleOrder) || !1,
                        A = O.some((function(e) {
                            return e.autoTrading
                        })) || !1;
                    (0, a.useEffect)((function() {
                        if (O.length) {
                            var e = h === _.DfJ.ko,
                                t = O[0],
                                n = "0" !== t.watchPrice && "" !== t.watchTypeCd,
                                i = m(t.crncCd),
                                r = p(t.coinType),
                                o = "";
                            ! function() {
                                if (d && "0" !== d.closeExceptedDate) {
                                    var e = T()(d.closeExceptedDate);
                                    k((0, u.jsx)("div", {
                                        className: ie("trade-cancel-modal-warning"),
                                        children: (0, u.jsx)("p", {
                                            className: ie("trade-cancel-modal-warning__text"),
                                            children: s("pop.trade.orderCancel.msg001", {
                                                MM: e.format("MM"),
                                                DD: e.format("DD"),
                                                HH: e.format("HH")
                                            })
                                        })
                                    }))
                                } else d && d.isInvestment ? k((0, u.jsx)("div", {
                                    className: ie("trade-cancel-modal-warning"),
                                    children: (0, u.jsx)("p", {
                                        className: ie("trade-cancel-modal-warning__text"),
                                        children: s("pop.trade.orderCancel.msg002")
                                    })
                                })) : k(null)
                            }(), n && "0" !== t.watchPrice && ("UP" === t.watchTypeCd ? o = "\u2265 " : "DN" === t.watchTypeCd && (o = "\u2264 ")), D(A ? (0, u.jsxs)(u.Fragment, {
                                children: [N, (0, u.jsxs)("div", {
                                    className: ie("modal-confirm-modal-state"),
                                    children: [(0, u.jsx)("p", {
                                        className: ie("modal-confirm-modal-state__text"),
                                        children: s("pop.trade.orderCancel.msg010", {
                                            tag: (0, u.jsx)("br", {})
                                        })
                                    }), (0, u.jsx)("p", {
                                        className: ie("modal-confirm-modal-state__text-sub"),
                                        children: s("pop.trade.orderCancel.msg012", {
                                            tag: (0, u.jsx)("br", {})
                                        })
                                    })]
                                })]
                            }) : (0, u.jsxs)(u.Fragment, {
                                children: [(0, u.jsx)("h2", {
                                    className: ie("trade-cancel-modal__title"),
                                    children: s("pop.trade.orderCancel.msg009")
                                }), N, (0, u.jsx)("div", {
                                    className: ie("trade-cancel-modal-table"),
                                    children: (0, u.jsxs)("table", {
                                        children: [(0, u.jsx)("caption", {
                                            children: "\uc8fc\ubb38 \ud655\uc778"
                                        }), (0, u.jsx)("colgroup", {
                                            children: (0, u.jsx)("col", {
                                                style: {
                                                    width: "65px"
                                                }
                                            })
                                        }), (0, u.jsxs)("tbody", {
                                            children: [(0, u.jsxs)("tr", {
                                                children: [(0, u.jsx)("th", {
                                                    scope: "row",
                                                    children: s("pop.trade.orderCancel.msg005")
                                                }), (0, u.jsx)("td", {
                                                    children: "".concat(e ? r.coinName : r.coinNameEn, "(").concat(r.coinSymbol, ")")
                                                })]
                                            }), n && (0, u.jsxs)("tr", {
                                                children: [(0, u.jsx)("th", {
                                                    scope: "row",
                                                    children: s("pop.trade.orderCancel.msg006")
                                                }), (0, u.jsx)("td", {
                                                    children: "0" === t.watchPrice ? "-" : (0, u.jsxs)(u.Fragment, {
                                                        children: [o, new($())(t.watchPrice).toFormat(f(t.watchPrice, t.coinType, t.crncCd).lang, $().ROUND_DOWN), (0, u.jsxs)("span", {
                                                            className: ie("trade-cancel-modal-table__text-gray"),
                                                            children: ["\xa0", (0, C.c0)(i.marketSymbol)]
                                                        })]
                                                    })
                                                })]
                                            }), (0, u.jsxs)("tr", {
                                                children: [(0, u.jsx)("th", {
                                                    scope: "row",
                                                    children: s("pop.trade.orderCancel.msg007")
                                                }), (0, u.jsx)("td", {
                                                    children: "0" === t.unitPrice ? "-" : (0, u.jsxs)(u.Fragment, {
                                                        children: [new($())(t.unitPrice).toFormat(f(t.unitPrice, t.coinType, t.crncCd).lang, $().ROUND_DOWN), (0, u.jsxs)("span", {
                                                            className: ie("trade-cancel-modal-table__text-gray"),
                                                            children: ["\xa0", (0, C.c0)(i.marketSymbol)]
                                                        })]
                                                    })
                                                })]
                                            }), (0, u.jsxs)("tr", {
                                                children: [(0, u.jsx)("th", {
                                                    scope: "row",
                                                    children: s("pop.trade.orderCancel.msg008")
                                                }), (0, u.jsx)("td", {
                                                    children: "0" === t.orderQty ? "-" : (0, u.jsxs)("div", {
                                                        className: ie("auto-scale__box"),
                                                        children: [(0, u.jsx)(P.Z, {
                                                            className: ie("auto-scale"),
                                                            children: (0, C.Cs)(new($())(t.orderQty).toFormat(8, $().ROUND_DOWN))
                                                        }), (0, u.jsxs)("span", {
                                                            className: ie("trade-cancel-modal-table__text-gray"),
                                                            children: ["\xa0", r.coinSymbol]
                                                        })]
                                                    })
                                                })]
                                            })]
                                        })]
                                    })
                                })]
                            }))
                        } else k(null), D(null)
                    }), [O]);
                    var F = function() {
                        var e = O.map((function(e) {
                            return {
                                orderNo: e.orderNo,
                                coinType: e.coinType,
                                crncCd: e.crncCd,
                                tradeTypeCd: e.tradeTypeCd
                            }
                        }));
                        c("/v1/trade/cancel-orders", {
                            cancelOrders: e
                        }).then((function(e) {
                            200 === e.status && (O.forEach((function(e) {
                                var t = "2" === e.tradeTypeCd,
                                    n = t ? "toast-element__trade-icon--buy" : "toast-element__trade-icon--sell",
                                    i = s(t ? "toast.trade.msg06" : "toast.trade.msg07"),
                                    r = '<span class="toast-element__trade-icon '.concat(n, '">\n\t\t\t\t\t\t\t').concat(p(e.coinType).coinSymbol, "/\n\t\t\t\t\t\t\t").concat(m(e.crncCd).marketSymbol, "\n\t\t\t\t\t\t\t").concat(i, '\n\t\t\t\t\t\t</span>\n\t\t\t\t\t\t<span class="toast-element__trade-info">').concat(s("toast.trade.msg01"), "</span>");
                                x(r)
                            })), b("\uc8fc\ubb38", "\uc77c\ubc18\uc8fc\ubb38", "\uc8fc\ubb38\ucde8\uc18c"))
                        })).finally((function() {
                            i(ne.dSS)
                        }))
                    };
                    return Z ? (0, u.jsxs)(ee.ZP, {
                        type: ee.y7.Default,
                        visible: v,
                        hideButton: !0,
                        className: ie("trade-cancel-modal"),
                        children: [I, (0, u.jsx)(te.XY, {
                            className: ie("modal-confirm-button"),
                            modalBtn: {
                                text: s("button.msg41"),
                                feature: te.p0.CUSTOM,
                                customStyle: {
                                    type: g.PD.DefaultNew,
                                    color: g.n5.Secondary,
                                    size: g.VA.Large
                                },
                                callback: function() {
                                    i(ne.dSS)
                                }
                            },
                            modalBtn1: {
                                text: s("button.msg70"),
                                feature: te.p0.CUSTOM,
                                customStyle: {
                                    type: g.PD.DefaultNew,
                                    color: g.n5.Primary,
                                    size: g.VA.Large
                                },
                                callback: F
                            },
                            onClose: function() {
                                return i(ne.dSS)
                            }
                        })]
                    }) : (0, u.jsxs)(ee.ZP, {
                        type: ee.y7.Default,
                        visible: v,
                        hideButton: !0,
                        className: ie("trade-cancel-modal"),
                        children: [N, (0, u.jsxs)("div", {
                            className: ie("modal-confirm-modal-state"),
                            children: [(0, u.jsx)("p", {
                                className: ie("modal-confirm-modal-state__text"),
                                children: s("pop.trade.orderCancel.msg010", {
                                    tag: (0, u.jsx)("br", {})
                                })
                            }), (0, u.jsx)("p", {
                                className: ie("modal-confirm-modal-state__text-sub"),
                                children: s("pop.trade.orderCancel.msg012", {
                                    tag: (0, u.jsx)("br", {})
                                })
                            })]
                        }), (0, u.jsx)(te.XY, {
                            className: ie("modal-confirm-button"),
                            modalBtn: {
                                text: s("button.msg41"),
                                feature: te.p0.CUSTOM,
                                customStyle: {
                                    type: g.PD.DefaultNew,
                                    color: g.n5.Secondary,
                                    size: g.VA.Large
                                },
                                callback: function() {
                                    i(ne.dSS)
                                }
                            },
                            modalBtn1: {
                                text: s("button.msg70"),
                                feature: te.p0.CUSTOM,
                                customStyle: {
                                    type: g.PD.DefaultNew,
                                    color: g.n5.Primary,
                                    size: g.VA.Large
                                },
                                callback: F
                            },
                            onClose: function() {
                                return i(ne.dSS)
                            }
                        })]
                    })
                })),
                oe = c().bind(E),
                ae = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.service.subscribeTradeStatus,
                        n = e.localeService.locale,
                        s = e.coinService,
                        c = s.getCoin.coinType,
                        l = s.getMarket.crncCd,
                        m = e.modalService,
                        p = m.showModal,
                        f = m.updateParams,
                        h = e.httpService.get,
                        b = e.sessionService.login,
                        v = e.socketService.subscribeAssetChange,
                        y = (0, d.s0)(),
                        j = (0, O.Vy)(),
                        N = (0, a.useRef)(!1),
                        C = (0, a.useRef)(!1),
                        S = (0, a.useState)({
                            market: l,
                            type: _.qTS.ALL
                        }),
                        w = (0, o.Z)(S, 2),
                        T = w[0],
                        P = w[1],
                        I = (0, a.useState)([]),
                        D = (0, o.Z)(I, 2),
                        Z = D[0],
                        A = D[1],
                        F = (0, a.useRef)(Z.length),
                        R = (0, a.useState)(!1),
                        B = (0, o.Z)(R, 2),
                        E = B[0],
                        q = B[1],
                        G = (0, a.useRef)(""),
                        H = (0, a.useState)([]),
                        Q = (0, o.Z)(H, 2),
                        K = Q[0],
                        Y = Q[1],
                        V = K.length,
                        W = (0, a.useState)(!1),
                        z = (0, o.Z)(W, 2),
                        J = z[0],
                        $ = z[1],
                        ee = (0, a.useState)(!1),
                        te = (0, o.Z)(ee, 2),
                        ie = te[0],
                        ae = te[1],
                        se = function(e, t) {
                            !b || N.current || F.current > 200 || (e && $(!0), N.current = !0, h("/v2/trade/orders/pending", {
                                coinType: c,
                                crncCd: "ALL" === T.market ? null : T.market,
                                orderKind: T.type,
                                pageOffset: G.current,
                                pageLimit: t && F.current > 50 ? F.current : 50
                            }, !1, null, !0).then((function(t) {
                                if (200 === t.status) {
                                    var n = e ? t.data.pendingOrderList : Z.concat(t.data.pendingOrderList);
                                    F.current = n.length, A(n), G.current = t.data.nextPageOffset, ae(!1), q(t.data.hasNextPage)
                                }
                            })).catch((function() {
                                ae(!0)
                            })).finally((function() {
                                N.current = !1, $(!1)
                            })))
                        },
                        ce = function(e, t) {
                            G.current = "", Y([]), P({
                                market: e,
                                type: t
                            })
                        };
                    (0, a.useEffect)((function() {
                        ce(l, _.qTS.ALL)
                    }), [c, l]), (0, a.useEffect)((function() {
                        var e, n;
                        return G.current = "", q(!1), A([]), Y([]), b && (se(!0, !1), e = t((function(e) {
                                e.type === _.OJD.orderSuccess && (G.current = "", se(!0, !0))
                            })), n = v((function(e) {
                                if (e.list) {
                                    var t = !1;
                                    e.list.forEach((function(e) {
                                        e.coinType === c && (t = !0)
                                    })), t && (G.current = "", se(!0, !0))
                                }
                            }))),
                            function() {
                                e && e(), n && n()
                            }
                    }), [b, T]), (0, a.useEffect)((function() {
                        var e = Z.filter((function(e) {
                            return K.some((function(t) {
                                return e.orderNo === t.orderNo
                            }))
                        }));
                        Y(e), !C.current && f(ne.dSS, {
                            order: e
                        })
                    }), [Z]);
                    var le = function(e, t) {
                        p(ne.dSS, {
                            order: e,
                            isSingleOrder: t
                        }), C.current = t
                    };
                    return (0, u.jsxs)("div", {
                        className: oe("trade-pending"),
                        children: [(0, u.jsxs)("div", {
                            className: oe("trade-pending__sticky"),
                            children: [(0, u.jsx)(M, {
                                type: "pending",
                                callback: ce,
                                defaultMarket: T.market,
                                defaultType: T.type,
                                arrCancelList: K,
                                isCancelPending: !!V,
                                fnCancel: le
                            }), (0, u.jsx)("div", {
                                className: oe("trade-pending-table"),
                                children: (0, u.jsxs)("table", {
                                    children: [(0, u.jsx)("caption", {
                                        children: "\uc8fc\ubb38 \ud655\uc778"
                                    }), (0, u.jsxs)("colgroup", {
                                        children: [(0, u.jsx)("col", {
                                            style: {
                                                width: "40px"
                                            }
                                        }), (0, u.jsx)("col", {
                                            style: {
                                                width: "145px"
                                            }
                                        }), (0, u.jsx)("col", {
                                            style: {
                                                width: "112px"
                                            }
                                        }), (0, u.jsx)("col", {
                                            style: {
                                                width: "80px"
                                            }
                                        }), (0, u.jsx)("col", {
                                            style: {
                                                width: "151px"
                                            }
                                        }), (0, u.jsx)("col", {
                                            style: {
                                                width: "151px"
                                            }
                                        }), (0, u.jsx)("col", {
                                            style: {
                                                width: "130px"
                                            }
                                        }), (0, u.jsx)("col", {
                                            style: {
                                                width: "130px"
                                            }
                                        }), (0, u.jsx)("col", {
                                            style: {
                                                width: "61px"
                                            }
                                        })]
                                    }), (0, u.jsx)("thead", {
                                        children: (0, u.jsxs)("tr", {
                                            children: [(0, u.jsxs)("th", {
                                                className: oe("trade-pending-table__left"),
                                                scope: "col",
                                                children: [(0, u.jsx)(U.ZP, {
                                                    type: U.Su.CircleOrange,
                                                    size: U.VD.Size20,
                                                    textNone: !0,
                                                    label: "\ubbf8\uccb4\uacb0 \uc804\uccb4 \uc120\ud0dd",
                                                    onChange: function(e) {
                                                        e.target.checked ? Y(Z) : Y([])
                                                    },
                                                    checked: !!V && F.current === V
                                                }), (0, u.jsx)("div", {
                                                    className: oe("trade-pending-table__line")
                                                })]
                                            }), (0, u.jsxs)("th", {
                                                scope: "col",
                                                children: [n("page.trade.msg030"), (0, u.jsx)("div", {
                                                    className: oe("trade-pending-table__line")
                                                })]
                                            }), (0, u.jsxs)("th", {
                                                scope: "col",
                                                children: [n("page.trade.msg031"), (0, u.jsx)("div", {
                                                    className: oe("trade-pending-table__line")
                                                })]
                                            }), (0, u.jsxs)("th", {
                                                scope: "col",
                                                children: [n("page.trade.msg032"), (0, u.jsx)("div", {
                                                    className: oe("trade-pending-table__line")
                                                })]
                                            }), (0, u.jsxs)("th", {
                                                className: oe("trade-pending-table__right"),
                                                scope: "col",
                                                children: [n("page.trade.msg033"), (0, u.jsx)("div", {
                                                    className: oe("trade-pending-table__line")
                                                })]
                                            }), (0, u.jsxs)("th", {
                                                className: oe("trade-pending-table__right"),
                                                scope: "col",
                                                children: [n("page.trade.msg034"), (0, u.jsx)("div", {
                                                    className: oe("trade-pending-table__line")
                                                })]
                                            }), (0, u.jsxs)("th", {
                                                className: oe("trade-pending-table__right"),
                                                scope: "col",
                                                children: [n("page.trade.msg035"), (0, u.jsx)("div", {
                                                    className: oe("trade-pending-table__line")
                                                })]
                                            }), (0, u.jsxs)("th", {
                                                className: oe("trade-pending-table__right"),
                                                scope: "col",
                                                children: [n("page.trade.msg036"), (0, u.jsx)("div", {
                                                    className: oe("trade-pending-table__line")
                                                })]
                                            }), (0, u.jsxs)("th", {
                                                scope: "col",
                                                children: [n("page.trade.msg037"), (0, u.jsx)("div", {
                                                    className: oe("trade-pending-table__line")
                                                })]
                                            })]
                                        })
                                    })]
                                })
                            })]
                        }), (0, u.jsx)("div", {
                            className: oe("trade-pending-table"),
                            children: (0, u.jsxs)("table", {
                                children: [(0, u.jsx)("caption", {
                                    children: "\uc8fc\ubb38 \ud655\uc778"
                                }), (0, u.jsxs)("colgroup", {
                                    children: [(0, u.jsx)("col", {
                                        style: {
                                            width: "40px"
                                        }
                                    }), (0, u.jsx)("col", {
                                        style: {
                                            width: "145px"
                                        }
                                    }), (0, u.jsx)("col", {
                                        style: {
                                            width: "112px"
                                        }
                                    }), (0, u.jsx)("col", {
                                        style: {
                                            width: "80px"
                                        }
                                    }), (0, u.jsx)("col", {
                                        style: {
                                            width: "151px"
                                        }
                                    }), (0, u.jsx)("col", {
                                        style: {
                                            width: "151px"
                                        }
                                    }), (0, u.jsx)("col", {
                                        style: {
                                            width: "130px"
                                        }
                                    }), (0, u.jsx)("col", {
                                        style: {
                                            width: "130px"
                                        }
                                    }), (0, u.jsx)("col", {
                                        style: {
                                            width: "61px"
                                        }
                                    })]
                                }), (0, u.jsx)("tbody", {
                                    children: ie ? (0, u.jsx)("tr", {
                                        children: (0, u.jsx)("td", {
                                            colSpan: 9,
                                            className: oe("trading-pending-timeout"),
                                            children: (0, u.jsx)(k, {
                                                caseStyle: i.orderHistory
                                            })
                                        })
                                    }) : J ? (0, u.jsx)("tr", {
                                        children: (0, u.jsx)("td", {
                                            colSpan: 9,
                                            className: oe("trading-pending-dot"),
                                            children: (0, u.jsx)(x.k_, {
                                                type: x.G0.Vertical,
                                                className: oe("trading-pending-dot__loading")
                                            })
                                        })
                                    }) : (0, u.jsx)(X, {
                                        pendingOrderList: Z,
                                        cancelList: K,
                                        onCancelOrderChecked: function(e) {
                                            return Y((function(t) {
                                                return [].concat((0, L.Z)(t), [e])
                                            }))
                                        },
                                        onCancelOrderUnChecked: function(e) {
                                            return Y((function(t) {
                                                return t.filter((function(t) {
                                                    return t.orderNo !== e.orderNo
                                                }))
                                            }))
                                        },
                                        onOrderCancel: function(e) {
                                            return le([e], !0)
                                        }
                                    })
                                })]
                            })
                        }), E && (0, u.jsx)("div", {
                            className: oe("trade-pending-bottom"),
                            children: (0, u.jsx)(g.ZP, {
                                className: oe("trade-pending-bottom__button"),
                                onClick: function() {
                                    F.current >= 200 ? y(j.ORDER_HISTORY) : se(!1, !1)
                                },
                                children: F.current >= 200 ? n("page.trade.msg024") : n("page.trade.msg043")
                            })
                        }), (0, u.jsx)(re, {})]
                    })
                })),
                se = c().bind({
                    "trade-history": "History_trade-history__tw-UC",
                    "trade-history-tab": "History_trade-history-tab__6Ck18",
                    "trade-history-tab__button": "History_trade-history-tab__button__AETUJ"
                }),
                ce = (0, l.Pi)((function() {
                    var e = (0, r.G2)().localeService.locale,
                        t = (0, a.useState)(0),
                        n = (0, o.Z)(t, 2),
                        i = n[0],
                        s = n[1];
                    return (0, u.jsxs)("div", {
                        className: se("trade-history"),
                        children: [(0, u.jsxs)("div", {
                            className: se("trade-history-tab"),
                            role: "tablist",
                            children: [(0, u.jsx)(g.ZP, {
                                className: se("trade-history-tab__button"),
                                role: "tab",
                                "aria-selected": 0 === i ? "true" : "false",
                                onClick: function() {
                                    return s(0)
                                },
                                children: e("page.trade.msg022")
                            }), (0, u.jsx)(g.ZP, {
                                className: se("trade-history-tab__button"),
                                role: "tab",
                                "aria-selected": 1 === i ? "true" : "false",
                                onClick: function() {
                                    return s(1)
                                },
                                children: e("page.trade.msg023")
                            })]
                        }), (0, u.jsxs)("h2", {
                            className: "blind",
                            children: [0 === i && e("page.trade.msg022"), 1 === i && e("page.trade.msg023")]
                        }), 0 === i && (0, u.jsx)(ae, {}), 1 === i && (0, u.jsx)(B, {})]
                    })
                })),
                le = n(38347),
                de = n(68144),
                _e = n(64001),
                me = c().bind({
                    "contract-list": "ContractList_contract-list__TfqQW",
                    "contract-list-header": "ContractList_contract-list-header__UaE2W",
                    "contract-list-content": "ContractList_contract-list-content__+pcYd",
                    "contract-list-content__rate-up": "ContractList_contract-list-content__rate-up__6opg0",
                    "contract-list-content__rate-down": "ContractList_contract-list-content__rate-down__9s9A5",
                    "auto-scale-box": "ContractList_auto-scale-box__CnqUD",
                    "auto-scale-box__price": "ContractList_auto-scale-box__price__mL7sf",
                    "contract-list-content__loading": "ContractList_contract-list-content__loading__fRkEj"
                }),
                ue = (0, l.Pi)((function(e) {
                    var t = e.isLoad,
                        n = e.quoteList,
                        i = (0, r.G2)(),
                        o = i.coinService,
                        a = o.getCoin,
                        s = a.coinType,
                        c = a.coinSymbol,
                        l = o.getMarket,
                        d = l.crncCd,
                        m = l.marketSymbol,
                        p = o.formatQtyDecimalByMarket,
                        f = o.formatAmountDecimalByMarket,
                        h = i.localeService.locale,
                        g = i.sessionService.getCustom,
                        b = (0, S.PZ)().getInstance;
                    return (0, u.jsxs)("div", {
                        id: "chartTabPanel01",
                        className: me("contract-list"),
                        role: "tabpanel",
                        children: [(0, u.jsx)("div", {
                            className: me("contract-list-header"),
                            children: (0, u.jsxs)("table", {
                                children: [(0, u.jsxs)("colgroup", {
                                    children: [(0, u.jsx)("col", {
                                        style: {
                                            width: "65px"
                                        }
                                    }), (0, u.jsx)("col", {
                                        style: {
                                            width: "126px"
                                        }
                                    }), (0, u.jsx)("col", {})]
                                }), (0, u.jsx)("thead", {
                                    children: (0, u.jsxs)("tr", {
                                        children: [(0, u.jsx)("th", {
                                            scope: "col",
                                            children: h("page.trade.msg083")
                                        }), (0, u.jsxs)("th", {
                                            scope: "col",
                                            children: [h("page.trade.msg084"), "(", m, ")"]
                                        }), (0, u.jsx)("th", {
                                            scope: "col",
                                            children: g(_._28.orderBookPrice) ? "".concat(h("page.trade.msg243"), "(").concat(c, ")") : "".concat(h("page.trade.msg052"), "(").concat(m, ")")
                                        })]
                                    })
                                })]
                            })
                        }), (0, u.jsx)("div", {
                            className: me("contract-list-content", "cm-gray-scroll"),
                            children: (0, u.jsxs)("table", {
                                children: [(0, u.jsxs)("colgroup", {
                                    children: [(0, u.jsx)("col", {
                                        style: {
                                            width: "65px"
                                        }
                                    }), (0, u.jsx)("col", {
                                        style: {
                                            width: "126px"
                                        }
                                    }), (0, u.jsx)("col", {})]
                                }), (0, u.jsx)("tbody", {
                                    children: t ? function() {
                                        if (!n.length) return null;
                                        var e = "0",
                                            t = "";
                                        return (0, L.Z)(n).reverse().map((function(n, i) {
                                            b(n.contPrice).isGreaterThan(e) ? t = "contract-list-content__rate-up" : b(n.contPrice).isLessThan(e) && (t = "contract-list-content__rate-down"), e = n.contPrice;
                                            var r = !g(_._28.orderBookPrice) ? f(n.contAmt, s, d) : p(n.contQty, s, d);
                                            return (0, u.jsxs)("tr", {
                                                children: [(0, u.jsx)("td", {
                                                    children: n.contDtm && n.contDtm.substring(11, 19)
                                                }), (0, u.jsx)("td", {
                                                    children: new($())(n.contPrice).toFormat()
                                                }), (0, u.jsx)("td", {
                                                    className: me(t),
                                                    children: (0, u.jsx)("div", {
                                                        className: me("auto-scale-box"),
                                                        children: n.contQty ? (0, u.jsx)(P.Z, {
                                                            className: me("auto-scale-box__price"),
                                                            children: r
                                                        }) : 0
                                                    })
                                                })]
                                            }, "quotes-".concat(s, "-").concat(d, "-").concat(i))
                                        })).reverse()
                                    }() : (0, u.jsx)("tr", {
                                        children: (0, u.jsx)("td", {
                                            className: me("contract-list-content__loading"),
                                            colSpan: 3,
                                            children: (0, u.jsx)(x.gb, {})
                                        })
                                    })
                                })]
                            })
                        })]
                    })
                })),
                pe = n(96094),
                fe = c().bind({
                    "global-price": "GlobalPrice_global-price__xYuF3",
                    "global-price-content": "GlobalPrice_global-price-content__dDkCh",
                    "global-price-content__column--text-align-center": "GlobalPrice_global-price-content__column--text-align-center__weFaP",
                    "global-price__head-button": "GlobalPrice_global-price__head-button__2StK5",
                    "global-price__head-button--active": "GlobalPrice_global-price__head-button--active__gfGrM",
                    "global-price-content__none": "GlobalPrice_global-price-content__none__g8M1e",
                    "global-price-content__loading": "GlobalPrice_global-price-content__loading__wFOlR",
                    "global-price__price-diff": "GlobalPrice_global-price__price-diff__6LTRe",
                    "global-price__price-diff-text": "GlobalPrice_global-price__price-diff-text__cPovW",
                    "global-price__price-diff-sub-text": "GlobalPrice_global-price__price-diff-sub-text__TJ9i6",
                    "global-price__price-diff--up": "GlobalPrice_global-price__price-diff--up__SHZU8",
                    "global-price__price-diff--down": "GlobalPrice_global-price__price-diff--down__GLB7Q",
                    "global-price__qty": "GlobalPrice_global-price__qty__dcT+R",
                    "global-price__qty-text": "GlobalPrice_global-price__qty-text__Aa+lt",
                    "global-price__auto-scale": "GlobalPrice_global-price__auto-scale__8mjZi"
                }),
                he = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.sessionService,
                        n = t.currencyRate,
                        i = t.language,
                        s = e.localeService.locale,
                        c = e.httpService.get,
                        l = e.coinService,
                        d = l.getCoin,
                        _ = l.getMarket,
                        m = l.getTicker,
                        p = l.getCoinInfo,
                        f = l.subscribeTicker,
                        h = l.getIntroUnitPrice,
                        g = e.developService.fnGetDataId,
                        b = (0, a.useRef)(g()),
                        v = (0, S.pg)([]),
                        y = (0, o.Z)(v, 2),
                        j = y[0],
                        N = y[1],
                        k = (0, a.useState)(!1),
                        w = (0, o.Z)(k, 2),
                        T = w[0],
                        I = w[1],
                        D = (0, a.useState)("globalDiff"),
                        O = (0, o.Z)(D, 2),
                        Z = O[0],
                        A = O[1],
                        F = (0, a.useState)("0"),
                        M = (0, o.Z)(F, 2),
                        R = M[0],
                        B = M[1],
                        L = (0, a.useRef)(0),
                        E = (0, a.useRef)({
                            coinType: d.coinType,
                            crcnCd: _.crncCd
                        }),
                        U = (0, a.useCallback)((function(e, t) {
                            var i = n("USD").rate,
                                r = new($())(e);
                            if (!n(t)) {
                                var o, a = p(t);
                                r = new($())((null === (o = m(a.coinType, _.crncCd)) || void 0 === o ? void 0 : o.closePrice) || "0").div(i).multipliedBy(r)
                            }
                            var s, c = new($())(0);
                            i && (c = new($())(e).multipliedBy(i), "JPY" === t && (c = c.dividedBy(100)), _.crncCd !== C.Eo && (c = c.dividedBy(new($())((null === (s = m(_.crncCd, C.Eo)) || void 0 === s ? void 0 : s.closePrice) || 0))));
                            return {
                                conversionPrice: c,
                                localCurrency: r
                            }
                        }), [d.coinType, _.crncCd]),
                        q = function e() {
                            window.clearTimeout(L.current), c("/v1/tradeinfo/ext/ticker", {
                                coinSymbol: d.coinSymbol
                            }).then((function(e) {
                                if (200 === e.status && E.current.coinType === d.coinType) {
                                    var t = e.data.extTickerCoinMap;
                                    if (t) {
                                        var n = t[d.coinSymbol];
                                        N(n.map((function(e) {
                                            var t = U(e.data.last, e.rate).conversionPrice,
                                                n = h(t.toFixed(), d.coinType, _.crncCd).lang,
                                                i = t.toFixed(n, $().ROUND_DOWN);
                                            return {
                                                exchangeName: e.name,
                                                sAmount: t.toFormat(n, $().ROUND_DOWN),
                                                volumeAmt: new($())(e.data.vol_cur).toFormat(),
                                                fixedConversionPrice: i
                                            }
                                        })))
                                    }
                                }
                            })).finally((function() {
                                var t = E.current,
                                    n = t.coinType,
                                    i = t.crcnCd;
                                n === d.coinType && i === _.crncCd && (L.current = window.setTimeout(e, 6e4), I(!0))
                            }))
                        },
                        G = function(e) {
                            var t = e.visibleClosePrice;
                            B(null !== t && void 0 !== t ? t : "0")
                        };
                    return (0, a.useEffect)((function() {
                        var e, t;
                        B(null !== (e = null === (t = m(d.coinType, _.crncCd)) || void 0 === t ? void 0 : t.visibleClosePrice) && void 0 !== e ? e : "0");
                        var n = f(d.coinType, _.crncCd, G, b.current);
                        return N([]), I(!1), q(), E.current = {
                                coinType: d.coinType,
                                crcnCd: _.crncCd
                            },
                            function() {
                                n && n(), window.clearTimeout(L.current), L.current = 0
                            }
                    }), [d.coinType, _.crncCd]), (0, u.jsx)("div", (0, pe.Z)((0, pe.Z)({
                        id: "chartTabPanel02",
                        className: fe("global-price"),
                        role: "tabpanel"
                    }, b.current), {}, {
                        children: (0, u.jsx)("div", {
                            className: fe("global-price-content", "cm-gray-scroll"),
                            children: (0, u.jsxs)("table", {
                                children: [(0, u.jsx)("caption", {
                                    children: "\uc138\uacc4\uc2dc\uc138"
                                }), (0, u.jsxs)("colgroup", {
                                    children: [(0, u.jsx)("col", {
                                        style: {
                                            width: "90px"
                                        }
                                    }), (0, u.jsx)("col", {
                                        style: {
                                            width: "96px"
                                        }
                                    }), (0, u.jsx)("col", {
                                        style: {
                                            width: "163px"
                                        }
                                    })]
                                }), (0, u.jsx)("thead", {
                                    children: (0, u.jsxs)("tr", {
                                        children: [(0, u.jsx)("th", {
                                            scope: "col",
                                            children: s("page.trade.msg086")
                                        }), (0, u.jsx)("th", {
                                            scope: "col",
                                            children: s("page.trade.msg087", {
                                                unit: _.marketSymbol
                                            })
                                        }), (0, u.jsx)("th", {
                                            scope: "col",
                                            children: (0, u.jsxs)("button", {
                                                type: "button",
                                                className: fe("global-price__head-button", {
                                                    "global-price__head-button--active": "qty" === Z
                                                }),
                                                onClick: function() {
                                                    return A("qty" === Z ? "globalDiff" : "qty")
                                                },
                                                children: ["globalDiff" === Z && (0, u.jsx)("span", {
                                                    className: fe("global-price__head-button-text"),
                                                    children: s("page.trade.msg088", {
                                                        unit: _.marketSymbol
                                                    })
                                                }), "qty" === Z && (0, u.jsx)("span", {
                                                    className: fe("global-price__head-button-text"),
                                                    children: s("page.trade.msg089", {
                                                        coinSymbol: d.coinSymbol
                                                    })
                                                })]
                                            })
                                        })]
                                    })
                                }), (0, u.jsxs)("tbody", {
                                    children: [T && (j.length > 0 ? j.map((function(e) {
                                        var t = (0, C.KJ)(R, e.fixedConversionPrice),
                                            n = new($())(R).minus(e.fixedConversionPrice).abs();
                                        return (0, u.jsxs)("tr", {
                                            children: [(0, u.jsx)("td", {
                                                children: e.exchangeName
                                            }), (0, u.jsx)("td", {
                                                children: "".concat(e.sAmount, " ").concat(_.crncCd === C.Eo ? (0, C.c0)(_.crncCd, i) : "")
                                            }), "globalDiff" === Z && (0, u.jsxs)("td", {
                                                className: fe("global-price__price-diff", {
                                                    "global-price__price-diff--up": new($())(t.data).isGreaterThan(0)
                                                }, {
                                                    "global-price__price-diff--down": new($())(t.data).isLessThan(0)
                                                }),
                                                children: [(0, u.jsx)("span", {
                                                    className: fe("global-price__price-diff-text"),
                                                    children: n.decimalPlaces(h(n.toFixed(), d.coinType, _.crncCd).lang, C.ok.ROUND_DOWN).toFormat()
                                                }), (0, u.jsxs)("span", {
                                                    className: fe("global-price__price-diff-sub-text"),
                                                    children: [t.view, "%"]
                                                })]
                                            }), "qty" === Z && (0, u.jsx)("td", {
                                                className: fe("global-price__qty"),
                                                children: (0, u.jsx)("span", {
                                                    className: fe("global-price__qty-text"),
                                                    children: (0, u.jsx)(P.Z, {
                                                        className: fe("global-price__auto-scale"),
                                                        children: e.volumeAmt
                                                    })
                                                })
                                            })]
                                        }, e.exchangeName)
                                    })) : (0, u.jsx)("tr", {
                                        children: (0, u.jsx)("td", {
                                            className: fe("global-price-content__none"),
                                            colSpan: 4,
                                            children: s("page.trade.msg090")
                                        })
                                    })), !T && (0, u.jsx)("tr", {
                                        children: (0, u.jsx)("td", {
                                            className: fe("global-price-content__loading"),
                                            colSpan: 4,
                                            children: (0, u.jsx)(x.gb, {})
                                        })
                                    })]
                                })]
                            })
                        })
                    }))
                })),
                ge = n(66144),
                xe = c().bind({
                    "mini-chart": "MiniChart_mini-chart__j14ku"
                }),
                be = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.coinService,
                        n = t.getCoin.coinType,
                        i = t.getMarket.crncCd,
                        s = t.getAjaxCandleStickNew,
                        c = t.getClosePriceInfo,
                        l = t.getIntroUnitPrice,
                        d = e.sessionService.getCustom,
                        m = (0, a.useState)([]),
                        p = (0, o.Z)(m, 2),
                        f = p[0],
                        h = p[1],
                        g = (0, a.useMemo)((function() {
                            return !d(_._28.theme)
                        }), [d(_._28.theme)]),
                        x = (0, S.tm)(),
                        b = (0, a.useRef)(0),
                        v = (0, a.useRef)({
                            coinType: n,
                            crncCd: i
                        }),
                        y = function e() {
                            clearTimeout(b.current), s(v.current.coinType, v.current.crncCd).then((function(e) {
                                200 === e.status && "0000" === e.data.status && x() && h(e.data.data)
                            })).finally((function() {
                                x() && (b.current = window.setTimeout(e, 6e4))
                            }))
                        };
                    return (0, a.useEffect)((function() {
                        return v.current = {
                                coinType: n,
                                crncCd: i
                            }, y(),
                            function() {
                                clearTimeout(b.current)
                            }
                    }), [n, i]), (0, u.jsx)("div", {
                        className: xe("mini-chart"),
                        children: (0, u.jsx)(ge.Du, {
                            data: f,
                            width: 360,
                            height: 115,
                            xAxis: {
                                theme: {
                                    fill: "#666",
                                    stroke: g ? "#cacaca" : "#666"
                                }
                            },
                            theme: {
                                stroke: g ? "rgb(87, 149, 241)" : "#2e5dff",
                                fill: "rgba(124,181,236,0.2)",
                                cover: {
                                    fill: g ? "white" : "#2a3138"
                                }
                            },
                            unitPrice: l(c(n, i).price, n, i).unitPrice,
                            tooltip: !0,
                            disabledResizeCheck: !0
                        })
                    })
                })),
                ve = c().bind({
                    "chart-content-tab": "ChartTab_chart-content-tab__+hp3+",
                    "chart-content-tab__button-box": "ChartTab_chart-content-tab__button-box__iQWyy",
                    "chart-content-tab__button": "ChartTab_chart-content-tab__button__rBODD",
                    "chart-content-tab__sub-button-box": "ChartTab_chart-content-tab__sub-button-box__xzZz+",
                    "chart-content-tab__sub-button": "ChartTab_chart-content-tab__sub-button__4BsKw",
                    "chart-content-tab__sub-button--order": "ChartTab_chart-content-tab__sub-button--order__+s3wx",
                    "chart-content-tab__sub-button--chart": "ChartTab_chart-content-tab__sub-button--chart__rXlp0"
                }),
                ye = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.HOST_OBSERVER,
                        n = e.httpService.get,
                        i = e.coinService,
                        s = i.getCoin,
                        c = i.getMarket,
                        l = e.localeService.locale,
                        d = e.socketService.subscribeTransaction,
                        _ = e.gaService.fnGASendEvent,
                        m = (0, a.useState)(0),
                        p = (0, o.Z)(m, 2),
                        f = p[0],
                        h = p[1],
                        x = s.coinType,
                        b = c.crncCd,
                        v = (0, a.useState)([]),
                        y = (0, o.Z)(v, 2),
                        j = y[0],
                        N = y[1],
                        k = (0, a.useState)(!1),
                        C = (0, o.Z)(k, 2),
                        S = C[0],
                        w = C[1],
                        P = function(e) {
                            if (e && e.transactionList.length && e.coinType === x && e.crncCd === b) {
                                var t = (0, L.Z)(e.transactionList).reverse().map((function(e) {
                                    var t = Math.floor(e.dateTime / 1e3),
                                        n = e.dateTime.toString().substring(10),
                                        i = "".concat(T()(t).format("YYYY-MM-DD HH:mm:ss"), ".").concat(n);
                                    return {
                                        seq: Number(e.num),
                                        contAmt: e.contAmt,
                                        contDtm: i,
                                        contPrice: e.contPrice,
                                        contQty: e.contQty,
                                        tradeTypeCd: e.transactionType
                                    }
                                }));
                                N((function(e) {
                                    var n = t.length,
                                        i = e.slice(0, n),
                                        r = n > 0 && i.length > 0 ? (0, de.Z)([].concat((0, L.Z)(t), (0, L.Z)(i)), "seq") : [].concat((0, L.Z)(t), (0, L.Z)(i));
                                    return [].concat((0, L.Z)(r), (0, L.Z)(e.slice(n))).slice(0, 26)
                                }))
                            }
                        };
                    return (0, a.useEffect)((function() {
                        var e;
                        return N([]), w(!1), n("".concat(t, "/trade/v2/quote/").concat(x, "-").concat(b), {
                                limit: 26
                            }).then((function(t) {
                                200 === t.status && (N((function(e) {
                                    var n = t.data.coinQuoteList.filter((function(e) {
                                            return e.contDtm
                                        })),
                                        i = (0, de.Z)([].concat((0, L.Z)(n), (0, L.Z)(e)), "seq");
                                    return (0, _e.Z)(i, ["contDtm", "seq"], ["desc", "desc"])
                                })), e = d(P))
                            })).finally((function() {
                                return w(!0)
                            })),
                            function() {
                                e && e()
                            }
                    }), [x, b]), (0, u.jsxs)("div", {
                        id: "infoTabPanel01",
                        className: ve("chart-content"),
                        role: "tabpanel",
                        children: [(0, u.jsx)(be, {}), (0, u.jsxs)("div", {
                            className: ve("chart-content-tab"),
                            role: "tablist",
                            children: [(0, u.jsxs)("div", {
                                className: ve("chart-content-tab__button-box"),
                                children: [(0, u.jsx)(g.ZP, {
                                    className: ve("chart-content-tab__button"),
                                    role: "tab",
                                    "aria-selected": 0 === f,
                                    "aria-controls": "chartTabPanel01",
                                    onClick: function() {
                                        return h(0)
                                    },
                                    children: l("page.trade.msg073")
                                }), (0, u.jsx)(g.ZP, {
                                    className: ve("chart-content-tab__button"),
                                    role: "tab",
                                    "aria-selected": 1 === f,
                                    "aria-controls": "chartTabPanel02",
                                    onClick: function() {
                                        return h(1)
                                    },
                                    children: l("page.trade.msg074")
                                })]
                            }), (0, u.jsxs)("h2", {
                                className: "blind",
                                children: [0 === f && l("page.trade.msg073"), 1 === f && l("page.trade.msg074")]
                            }), (0, u.jsxs)("div", {
                                className: ve("chart-content-tab__sub-button-box"),
                                children: [(0, u.jsx)(g.ZP, {
                                    className: ve("chart-content-tab__sub-button", "chart-content-tab__sub-button--order"),
                                    onClick: function() {
                                        return _("\uc8fc\ubb38", "\uc77c\ubc18\uc8fc\ubb38", "\uc8fc\ubb38/\uac70\ub798\ud604\ud669")
                                    },
                                    to: "/trade/status/".concat(s.coinSymbol, "-").concat(c.marketSymbol),
                                    children: l("page.trade.msg055")
                                }), (0, u.jsx)(g.ZP, {
                                    className: ve("chart-content-tab__sub-button", "chart-content-tab__sub-button--chart"),
                                    onClick: function() {
                                        _("\uc8fc\ubb38", "\uc77c\ubc18\uc8fc\ubb38", "\ud31d\uc5c5\ucc28\ud2b8"), window.open("/frontend/bithumb/react/trade/chart/".concat(s.coinSymbol, "-").concat(c.marketSymbol), "", "width=1024, height=511, resizable=no, scrollbars=no, status=no")
                                    },
                                    children: l("page.trade.msg056")
                                })]
                            })]
                        }), (0, u.jsxs)("div", {
                            children: [0 === f && (0, u.jsx)(ue, {
                                isLoad: S,
                                quoteList: j
                            }), 1 === f && (0, u.jsx)(he, {})]
                        })]
                    })
                })),
                je = n(29721),
                Ne = n(97663),
                ke = c().bind({
                    "info-head-rolling-banner": "RollingBanner_info-head-rolling-banner__erJ6z",
                    "info-head-rolling-banner__title": "RollingBanner_info-head-rolling-banner__title__NCbbD",
                    "info-head-rolling-banner__content": "RollingBanner_info-head-rolling-banner__content__3O-v2",
                    "info-head-rolling-banner__box": "RollingBanner_info-head-rolling-banner__box__pvVV-",
                    "info-head-rolling-banner__box--2": "RollingBanner_info-head-rolling-banner__box--2__oLqov",
                    "bannerMove--2": "RollingBanner_bannerMove--2__P3IDQ",
                    "info-head-rolling-banner__box--3": "RollingBanner_info-head-rolling-banner__box--3__vA3JH",
                    "bannerMove--3": "RollingBanner_bannerMove--3__CCUhs",
                    "info-head-rolling-banner__box--4": "RollingBanner_info-head-rolling-banner__box--4__jCSjT",
                    "bannerMove--4": "RollingBanner_bannerMove--4__qkkjN",
                    "info-head-rolling-banner__box--5": "RollingBanner_info-head-rolling-banner__box--5__+C7tJ",
                    "bannerMove--5": "RollingBanner_bannerMove--5__rEh20",
                    "info-head-rolling-banner__box--6": "RollingBanner_info-head-rolling-banner__box--6__GJV0Z",
                    "bannerMove--6": "RollingBanner_bannerMove--6__A35uV",
                    "info-head-rolling-banner__text": "RollingBanner_info-head-rolling-banner__text__83dLn",
                    "info-head-rolling-banner__text--only": "RollingBanner_info-head-rolling-banner__text--only__bMRG5",
                    bannerOpacity: "RollingBanner_bannerOpacity__OQfj+"
                }),
                Ce = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.localeService.locale,
                        n = e.coinService,
                        i = n.getDaxaWarning,
                        s = n.getCoin,
                        c = n.getMarket,
                        l = e.sessionService.language,
                        d = (0, a.useMemo)((function() {
                            var e;
                            return e = {}, (0, je.Z)(e, _.c96.type1, t("page.trade.msg198")), (0, je.Z)(e, _.c96.type2, t("page.trade.msg199")), (0, je.Z)(e, _.c96.type3, t("page.trade.msg200")), (0, je.Z)(e, _.c96.type4, t("page.trade.msg201")), (0, je.Z)(e, _.c96.type5, t("page.trade.msg202")), (0, je.Z)(e, _.c96.type6, t("page.trade.msg203")), e
                        }), [l]),
                        m = (0, a.useState)([]),
                        p = (0, o.Z)(m, 2),
                        f = p[0],
                        h = p[1],
                        g = (0, a.useRef)({
                            market: "",
                            coin: ""
                        }),
                        x = (0, a.useState)(0),
                        b = (0, o.Z)(x, 2),
                        v = b[0],
                        y = b[1],
                        j = (0, a.useRef)(0);
                    return (0, a.useEffect)((function() {
                        return function() {
                            j.current && window.clearTimeout(j.current)
                        }
                    }), []), (0, a.useEffect)((function() {
                        if (i && i[c.crncCd] && i[c.crncCd][s.coinType]) {
                            var e = i[c.crncCd][s.coinType];
                            (0, Ne.Z)(f, e) && g.current.market === c.crncCd && g.current.coin === s.coinType || (y(0), h(e), j.current = window.setTimeout((function() {
                                y(e.length)
                            }), 10))
                        } else y(0), h([]);
                        g.current = {
                            market: c.crncCd,
                            coin: s.coinType
                        }
                    }), [s.coinType, c.crncCd, i]), f.length ? (0, u.jsxs)("div", {
                        className: ke("info-head-rolling-banner"),
                        children: [(0, u.jsx)("strong", {
                            className: ke("info-head-rolling-banner__title"),
                            children: (0, u.jsx)("span", {
                                className: "blind",
                                children: t("page.trade.msg197")
                            })
                        }), (0, u.jsx)("div", {
                            className: ke("info-head-rolling-banner__content"),
                            children: 1 === v ? (0, u.jsx)("p", {
                                className: ke("info-head-rolling-banner__text", "info-head-rolling-banner__text--only"),
                                children: d[f[0]]
                            }) : (0, u.jsxs)("div", {
                                className: ke("info-head-rolling-banner__box", "info-head-rolling-banner__box--".concat(v)),
                                children: [f.map((function(e) {
                                    return (0, u.jsx)("p", {
                                        className: ke("info-head-rolling-banner__text"),
                                        children: d[e]
                                    }, d[e])
                                })), (0, u.jsx)("p", {
                                    className: ke("info-head-rolling-banner__text"),
                                    children: d[f[0]]
                                })]
                            })
                        })]
                    }) : (0, u.jsx)(u.Fragment, {})
                })),
                Se = c().bind({
                    "info-head-price": "InfoHead_info-head-price__S3s+a",
                    "info-head-price__close-price": "InfoHead_info-head-price__close-price__XjrXA",
                    "info-head-price__close-price--up": "InfoHead_info-head-price__close-price--up__Iq8Ua",
                    "info-head-price__close-price--down": "InfoHead_info-head-price__close-price--down__mhIkR",
                    "info-head-price__close-price--new": "InfoHead_info-head-price__close-price--new__n3+JP",
                    "info-head-price_inner": "InfoHead_info-head-price_inner__ShezB",
                    "info-head-price__rate": "InfoHead_info-head-price__rate__sEUCP",
                    "info-head-price__rate--up": "InfoHead_info-head-price__rate--up__L4cNX",
                    "info-head-price__rate--down": "InfoHead_info-head-price__rate--down__dSWHq",
                    "info-head-price__krw": "InfoHead_info-head-price__krw__7PTMM",
                    "info-head-list": "InfoHead_info-head-list__rjoQY",
                    "info-head-list__box": "InfoHead_info-head-list__box__2v5lT",
                    "info-head-list__col": "InfoHead_info-head-list__col__G5qtE",
                    "info-head-info__rate-up": "InfoHead_info-head-info__rate-up__DzdLz",
                    "info-head-info__rate-down": "InfoHead_info-head-info__rate-down__Es1ov",
                    "info-head-list__font-11": "InfoHead_info-head-list__font-11__KWlVx",
                    "info-head-list__font-10": "InfoHead_info-head-list__font-10__24CVc",
                    "auto-scale__box": "InfoHead_auto-scale__box__K-L2K",
                    "auto-scale": "InfoHead_auto-scale__e0aWW",
                    "info-head-list__symbol": "InfoHead_info-head-list__symbol__qpI2V",
                    "info-head-info__krw": "InfoHead_info-head-info__krw__kGB1W"
                }),
                we = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.coinService,
                        n = t.getCoin,
                        i = t.getMarket,
                        s = t.getTicker,
                        c = t.getTickType,
                        l = t.getClosePriceInfo,
                        d = t.subscribeTicker,
                        m = t.fnToKrw,
                        p = e.localeService.locale,
                        f = e.sessionService,
                        h = f.language,
                        g = f.fnRateMillionPrice,
                        x = f.getRate,
                        b = e.httpService.get,
                        v = e.developService.fnGetDataId,
                        y = (0, a.useRef)(v()),
                        j = (0, S.PZ)(),
                        N = j.getInstance,
                        k = j.ROUNDING_MODE,
                        w = (0, a.useState)("0"),
                        T = (0, o.Z)(w, 2),
                        I = T[0],
                        D = T[1],
                        O = (0, a.useState)({
                            chgAmt: "0",
                            chgRate: "0",
                            closePrice: "0",
                            visibleClosePrice: "0",
                            coinType: n.coinType,
                            crncCd: i.crncCd,
                            highPrice: "0",
                            lowPrice: "0",
                            openPrice: "0",
                            prevClosePrice: "0",
                            tickType: _.mSn.MID,
                            value24H: "0",
                            buyVolume: "0",
                            date: "",
                            sellVolume: "0",
                            time: "",
                            value: "0",
                            volume: "0",
                            volume24H: "0",
                            volumePower: "0",
                            volumePower24H: "0"
                        }),
                        Z = (0, o.Z)(O, 2),
                        A = Z[0],
                        F = Z[1],
                        M = (0, a.useMemo)((function() {
                            return l(n.coinType, i.crncCd)
                        }), [n.coinType, i.crncCd, A]),
                        R = M.visiblePrice,
                        B = M.price,
                        L = M.bIsListed,
                        E = (0, a.useMemo)((function() {
                            var e = g(A.value24H, i, N(A.value24H).isLessThan(1e9), [1, 2]);
                            return {
                                price: Number((0, C.uR)(e.price)) ? e.price : "0",
                                unit: e.unit
                            }
                        }), [h, A.value24H]),
                        U = E.price,
                        q = E.unit,
                        G = (0, a.useMemo)((function() {
                            return {
                                volumeLength: N(A.volume24H).toFixed(4, k.ROUND_DOWN).length,
                                nOutLength: N(A.volume24H).isGreaterThan(A.crncCd === C.NE ? 1e3 : 1e4) ? 0 : 3
                            }
                        }), [A.volume24H]),
                        H = G.volumeLength,
                        Q = G.nOutLength,
                        K = (0, a.useCallback)((function(e) {
                            var t = i.crncCd === C.Eo ? x((0, C.uR)(e), 2, !0, k.ROUND_HALF_UP, 2) : m(n.coinType, i.crncCd, (0, C.uR)(e));
                            return "".concat(t, " ").concat(p("page.trade.msg136"))
                        }), [n.coinType, i.crncCd, h]);
                    return (0, a.useEffect)((function() {
                        var e, t = function(e) {
                                F((function(t) {
                                    return (0, pe.Z)((0, pe.Z)({}, t), e)
                                }))
                            },
                            r = s();
                        r && t(r);
                        var o = d(n.coinType, i.crncCd, t, y.current);
                        return i.crncCd !== C.Eo && (e = d(i.crncCd, C.Eo, (function() {
                                var e = s();
                                e && t(e)
                            }), y.current)), b("/v1/trade/info-coin/".concat(n.coinType, "-").concat(i.crncCd)).then((function(e) {
                                200 === e.status && D(e.data.yesterday_price)
                            })),
                            function() {
                                o && o(), e && e()
                            }
                    }), [n.coinType, i.crncCd]), (0, u.jsxs)("div", (0, pe.Z)((0, pe.Z)({
                        className: Se("info-head")
                    }, y.current), {}, {
                        children: [(0, u.jsxs)("div", {
                            className: Se("info-head-price"),
                            children: [(0, u.jsx)("h3", {
                                className: Se("info-head-price__close-price", {
                                    "info-head-price__close-price--up": Number(A.chgRate) > 0,
                                    "info-head-price__close-price--down": Number(A.chgRate) < 0,
                                    "info-head-price__close-price--new": L
                                }),
                                children: R
                            }), (0, u.jsxs)("div", {
                                className: Se("info-head-price_inner"),
                                children: [(0, u.jsxs)("span", {
                                    className: Se("info-head-price__rate", {
                                        "info-head-price__rate--up": Number(A.chgRate) > 0,
                                        "info-head-price__rate--down": Number(A.chgRate) < 0
                                    }),
                                    children: [N(A.chgRate).abs().toFormat(2), "%"]
                                }), !(i.crncCd === C.Eo && h === _.DfJ.ko) && (0, u.jsx)("span", {
                                    className: Se("info-head-price__krw"),
                                    children: K(B)
                                })]
                            })]
                        }), (0, u.jsx)(Ce, {}), (0, u.jsx)("div", {
                            className: Se("info-head-list"),
                            children: (0, u.jsxs)("dl", {
                                className: Se("info-head-list__box"),
                                children: [(0, u.jsxs)("div", {
                                    className: Se("info-head-list__col"),
                                    children: [(0, u.jsx)("dt", {
                                        children: p("page.trade.msg077")
                                    }), (0, u.jsxs)("dd", {
                                        className: Se({
                                            "info-head-list__font-11": H >= 17 && H < 19,
                                            "info-head-list__font-10": H >= 19
                                        }),
                                        children: [(0, u.jsx)("div", {
                                            className: Se("auto-scale__box"),
                                            children: (0, u.jsx)(P.Z, {
                                                className: Se("auto-scale"),
                                                children: N(A.volume24H).toFormat(Q, k.ROUND_HALF_UP)
                                            })
                                        }), (0, u.jsx)("span", {
                                            className: Se("info-head-list__symbol"),
                                            children: n.coinSymbol
                                        })]
                                    })]
                                }), (0, u.jsxs)("div", {
                                    className: Se("info-head-list__col"),
                                    children: [(0, u.jsxs)("dt", {
                                        children: [p("page.trade.msg080"), "(", c === _.mSn.MID ? p("page.trade.msg076") : c, ")"]
                                    }), (0, u.jsxs)("dd", {
                                        children: [Number(A.highPrice) ? N(A.highPrice).toFormat() : "-", !(i.crncCd === C.Eo && h === _.DfJ.ko) && Number(A.highPrice) > 0 && (0, u.jsx)("span", {
                                            className: Se("info-head-info__krw"),
                                            children: K(A.highPrice)
                                        })]
                                    })]
                                }), (0, u.jsxs)("div", {
                                    className: Se("info-head-list__col"),
                                    children: [(0, u.jsx)("dt", {
                                        children: p("page.trade.msg078")
                                    }), (0, u.jsxs)("dd", {
                                        children: [U, (0, u.jsx)("span", {
                                            className: Se("info-head-list__symbol"),
                                            children: q
                                        })]
                                    })]
                                }), (0, u.jsxs)("div", {
                                    className: Se("info-head-list__col"),
                                    children: [(0, u.jsxs)("dt", {
                                        children: [p("page.trade.msg081"), "(", c === _.mSn.MID ? p("page.trade.msg076") : c, ")"]
                                    }), (0, u.jsxs)("dd", {
                                        children: [Number(A.lowPrice) ? N(A.lowPrice).toFormat() : "-", !(i.crncCd === C.Eo && h === _.DfJ.ko) && Number(A.lowPrice) > 0 && (0, u.jsx)("span", {
                                            className: Se("info-head-info__krw"),
                                            children: K(A.lowPrice)
                                        })]
                                    })]
                                }), (0, u.jsxs)("div", {
                                    className: Se("info-head-list__col"),
                                    children: [(0, u.jsx)("dt", {
                                        children: p("page.trade.msg079")
                                    }), (0, u.jsxs)("dd", {
                                        className: Se({
                                            "info-head-info__rate-up": Number(A.volumePower) >= 100,
                                            "info-head-info__rate-down": 0 !== Number(A.volumePower) && Number(A.volumePower) < 100
                                        }),
                                        children: [N(Number(A.volumePower) > 500 ? "500" : A.volumePower).toFormat(500 !== Number(A.volumePower) && Number(A.volumePower) ? 2 : 0, k.ROUND_DOWN), "%"]
                                    })]
                                }), (0, u.jsxs)("div", {
                                    className: Se("info-head-list__col"),
                                    children: [(0, u.jsx)("dt", {
                                        children: p("page.trade.msg082")
                                    }), (0, u.jsxs)("dd", {
                                        children: ["0" === I ? "-" : N(I).toFormat(), !(i.crncCd === C.Eo && h === _.DfJ.ko) && Number(I) > 0 && (0, u.jsx)("span", {
                                            className: Se("info-head-info__krw"),
                                            children: K(I)
                                        })]
                                    })]
                                })]
                            })
                        })]
                    }))
                })),
                Te = c().bind({
                    info: "Info_info__lcEsd",
                    "info-content": "Info_info-content__ctp7S",
                    "info-content-top": "Info_info-content-top__Elyn5",
                    "info-content-top-tab": "Info_info-content-top-tab__u+YR2",
                    "info-content-top-tab__button": "Info_info-content-top-tab__button__9rP91",
                    "info-content-top__button-premium-box": "Info_info-content-top__button-premium-box__+4OMM",
                    "info-content-top__button-premium": "Info_info-content-top__button-premium__pW4nU",
                    "info-content-top__button-text": "Info_info-content-top__button-text__Ohy9E",
                    "info-content-top__button-badge": "Info_info-content-top__button-badge__zsZ87"
                }),
                Pe = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.localeService.locale,
                        n = e.coinInfoService,
                        i = n.getInfo,
                        s = n.openPremium,
                        c = n.setOpenPremium,
                        l = e.gaService.fnGASendEvent,
                        _ = (0, d.UO)().coin,
                        m = (0, a.useRef)({
                            prevCoinInfo: "",
                            timerId: null
                        }),
                        p = function() {
                            return i.market.crncCd === C.Eo
                        },
                        f = (0, a.useState)(p),
                        h = (0, o.Z)(f, 2),
                        x = h[0],
                        b = h[1];
                    return (0, a.useEffect)((function() {
                        m.current.prevCoinInfo !== _ && b(p)
                    }), [_]), (0, a.useEffect)((function() {
                        if (x) return (0, le.Z)(m.current.timerId) && (m.current.timerId = window.setTimeout((function() {
                                b(!1), m.current.timerId = null
                            }), 3e3)),
                            function() {
                                m.current.timerId && clearTimeout(m.current.timerId)
                            }
                    }), [x]), (0, u.jsxs)("div", {
                        className: Te("info-content"),
                        children: [(0, u.jsxs)("div", {
                            className: Te("info-content-top"),
                            children: [(0, u.jsx)("div", {
                                className: Te("info-content-top-tab"),
                                role: "tablist",
                                children: (0, u.jsx)(g.ZP, {
                                    className: Te("info-content-top-tab__button"),
                                    role: "tab",
                                    "aria-selected": !0,
                                    "aria-controls": "infoTabPanel01",
                                    children: t("page.trade.msg053")
                                })
                            }), (0, u.jsx)("h2", {
                                className: "blind",
                                children: t("page.trade.msg053")
                            }), (0, u.jsxs)("div", {
                                className: Te("info-content-top__button-premium-box"),
                                children: [(0, u.jsx)(g.ZP, {
                                    className: Te("info-content-top__button-premium"),
                                    onClick: function() {
                                        c(!s), l("\uc8fc\ubb38", "\uc77c\ubc18\uc8fc\ubb38", "\uac70\ub798\uc18c \uc815\ubcf4")
                                    },
                                    children: (0, u.jsxs)("span", {
                                        className: Te("info-content-top__button-text"),
                                        children: [i.coin.coinSymbol, " ", t("page.trade.msg247")]
                                    })
                                }), (0, u.jsx)("span", {
                                    className: Te("info-content-top__button-badge"),
                                    "aria-hidden": !x,
                                    children: t("page.trade.msg248")
                                })]
                            })]
                        }), (0, u.jsx)(ye, {})]
                    })
                })),
                Ie = function() {
                    return (0, u.jsxs)("div", {
                        className: Te("info"),
                        children: [(0, u.jsx)(we, {}), (0, u.jsx)(Pe, {})]
                    })
                },
                De = (0, a.memo)(Ie),
                Oe = c().bind({
                    "chg-unit-price": "ChgUnitPrice_chg-unit-price__D-C2+",
                    "chg-unit-setting": "ChgUnitPrice_chg-unit-setting__f+OVw",
                    "chg--unit-button": "ChgUnitPrice_chg--unit-button__679o3",
                    "chg--unit-button--left": "ChgUnitPrice_chg--unit-button--left__aGF0W",
                    "chg--unit-button--right": "ChgUnitPrice_chg--unit-button--right__QoSp0",
                    "chg-unit-setting__info": "ChgUnitPrice_chg-unit-setting__info__NygiI",
                    "chg-unit-setting__active": "ChgUnitPrice_chg-unit-setting__active__MaT3Y",
                    "chg-unit-setting__text": "ChgUnitPrice_chg-unit-setting__text__OH1UE",
                    "chg-unit-setting__sub-text": "ChgUnitPrice_chg-unit-setting__sub-text__2fJJW",
                    "chg-unit-setting__none": "ChgUnitPrice_chg-unit-setting__none__jZdPC",
                    "chg__sort-button": "ChgUnitPrice_chg__sort-button__OZcZt",
                    "chg__sort-button-ico-box": "ChgUnitPrice_chg__sort-button-ico-box__LQZkc",
                    "chg-unit-price-option": "ChgUnitPrice_chg-unit-price-option__2kXze",
                    "chg-unit-price-option__button": "ChgUnitPrice_chg-unit-price-option__button__urkXi",
                    "chg-unit-price-option__list": "ChgUnitPrice_chg-unit-price-option__list__-J3Ws"
                }),
                Ze = ["0", "10", "100"],
                Ae = (0, l.Pi)((function(e) {
                    var t = e.setUnitPrice,
                        n = (0, r.G2)(),
                        i = n.sessionService,
                        s = i.setCustom,
                        c = i.getCustom,
                        l = n.coinService,
                        d = l.getCoin,
                        m = l.getMarket,
                        p = l.getClosePriceInfo,
                        f = l.getIntroUnitPrice,
                        h = n.localeService.locale,
                        x = n.gaService.fnGASendEvent,
                        b = (0, a.useState)(1),
                        v = (0, o.Z)(b, 2),
                        y = v[0],
                        j = v[1],
                        N = (0, a.useMemo)((function() {
                            return C.s8.indexOf(y)
                        }), [y]),
                        k = c(_._28.orderBookPrice),
                        S = (0, a.useMemo)((function() {
                            var e = p(d.coinType, m.crncCd).price;
                            if (!e) return Ze;
                            var t = f(e, d.coinType, m.crncCd),
                                n = t.unitPrice,
                                i = t.lang;
                            return ["0", new($())(n).multipliedBy(10).dp(i).toFixed(), new($())(n).multipliedBy(100).dp(i).toFixed()]
                        }), [d.coinType, m.crncCd, y]);
                    (0, a.useEffect)((function() {
                        t({
                            price: S[C.s8.indexOf(y)],
                            unit: y
                        })
                    }), [y]);
                    var w = function(e) {
                            var t = 0;
                            "down" === e ? t = 0 === N ? C.s8.length - 1 : N - 1 : "up" === e && (t = N + 1 === C.s8.length ? 0 : N + 1), j(C.s8[t]), x("\uc8fc\ubb38", "\uc77c\ubc18\uc8fc\ubb38", "\ud638\uac00\ubaa8\uc544\ubcf4\uae30", null, {
                                ep_button_detail: "".concat(["\ubbf8\uc0ac\uc6a9", "x10", "x100"][t])
                            })
                        },
                        T = function(e, t) {
                            return (0, u.jsx)("svg", {
                                width: "100%",
                                height: "100%",
                                viewBox: "0 0 7 4",
                                fill: "none",
                                xmlns: "http://www.w3.org/2000/svg",
                                style: {
                                    transform: "rotate(".concat(e, "deg)")
                                },
                                children: (0, u.jsx)("path", {
                                    fillRule: "evenodd",
                                    clipRule: "evenodd",
                                    d: "M3.87629 3.56996C3.67708 3.79762 3.32292 3.79762 3.12371 3.56996L0.725595 0.829251C0.442715 0.05959 0.672305 -5.53185e-07 1.10188 -5.1563e-07L5.89812 -9.63297e-08C6.3277 -5.87747e-08 6.55728 0.505961 6.2744 0.829252L3.87629 3.56996Z",
                                    fill: t ? c(_._28.theme) ? "#777" : "#DADDE1" : c(_._28.theme) ? "#FFF" : "#707882"
                                })
                            })
                        };
                    return (0, u.jsxs)("div", {
                        className: Oe("chg-unit-price"),
                        children: [(0, u.jsxs)("div", {
                            className: Oe("chg-unit-setting"),
                            children: [(0, u.jsx)(g.ZP, {
                                className: Oe("chg--unit-button", "chg--unit-button--left"),
                                onClick: function() {
                                    return w("down")
                                },
                                children: T(90, !1)
                            }), (0, u.jsx)("span", {
                                className: Oe("chg-unit-setting__info"),
                                children: y === C.s8[0] ? (0, u.jsx)("span", {
                                    className: Oe("chg-unit-setting__none"),
                                    children: h("page.trade.msg094")
                                }) : (0, u.jsxs)("span", {
                                    className: Oe("chg-unit-setting__active"),
                                    children: [(0, u.jsx)("span", {
                                        className: Oe("chg-unit-setting__text"),
                                        children: new($())(S[C.s8.indexOf(y)]).toFormat()
                                    }), (0, u.jsx)("span", {
                                        className: Oe("chg-unit-setting__sub-text"),
                                        children: "x".concat(C.s8[C.s8.indexOf(y)])
                                    })]
                                })
                            }), (0, u.jsx)(g.ZP, {
                                className: Oe("chg--unit-button", "chg--unit-button--right"),
                                onClick: function() {
                                    return w("up")
                                },
                                children: T(270, !1)
                            })]
                        }), (0, u.jsxs)(g.ZP, {
                            className: Oe("chg__sort-button"),
                            onClick: function() {
                                s(_._28.orderBookPrice, !k)
                            },
                            children: [h(k ? "page.trade.msg241" : "page.trade.msg242"), (0, u.jsxs)("span", {
                                className: Oe("chg__sort-button-ico-box"),
                                children: [T(180, k), " ", T(0, !k), " "]
                            })]
                        })]
                    })
                })),
                Fe = n(67333),
                Me = n(61752),
                Re = n(56358),
                Be = n(96767),
                Le = n(3307),
                Ee = {
                    "order-book-list-title": "OrderBookList_order-book-list-title__b9ZPZ",
                    "order-book-list-title__text": "OrderBookList_order-book-list-title__text__A79eh",
                    "order-book-list-content": "OrderBookList_order-book-list-content__pCMFp",
                    "order-book-list-content__row": "OrderBookList_order-book-list-content__row__gNNtt",
                    "order-book-list-content__row--active": "OrderBookList_order-book-list-content__row--active__HEyE6",
                    "order-book-list-content__price-col": "OrderBookList_order-book-list-content__price-col__zpFaH",
                    "order-book-list-content__price-col--order": "OrderBookList_order-book-list-content__price-col--order__cVJeL",
                    "order-book-list-content-price": "OrderBookList_order-book-list-content-price__efNaG",
                    "order-book-list-content-price__num": "OrderBookList_order-book-list-content-price__num__Op+cK",
                    "order-book-list-price__krw": "OrderBookList_order-book-list-price__krw__iFgLO",
                    "order-book-list__auto-scale": "OrderBookList_order-book-list__auto-scale__8JI4+",
                    "order-book-list-content-price__button": "OrderBookList_order-book-list-content-price__button__P+Cpq",
                    "order-book-list-content__rate": "OrderBookList_order-book-list-content__rate__25fcK",
                    "order-book-list-content__rate-auto-scale": "OrderBookList_order-book-list-content__rate-auto-scale__RgjTI",
                    "order-book-list-content__price-col--rate-up": "OrderBookList_order-book-list-content__price-col--rate-up__YxoVD",
                    "order-book-list-content__price-col--rate-down": "OrderBookList_order-book-list-content__price-col--rate-down__lWmwi",
                    "order-book-list-content__qty": "OrderBookList_order-book-list-content__qty__DOHWF",
                    "order-book-list-content__percent-bar": "OrderBookList_order-book-list-content__percent-bar__laPSH",
                    "order-book-list-content__info-qty": "OrderBookList_order-book-list-content__info-qty__BXxKq",
                    "order-book-list-content__info-qty-auto-scale": "OrderBookList_order-book-list-content__info-qty-auto-scale__L2fNQ",
                    "order-book-list-content__info-qty__button": "OrderBookList_order-book-list-content__info-qty__button__b9hdu",
                    "order-book-list-content__row--ask": "OrderBookList_order-book-list-content__row--ask__W8cqv",
                    "order-book-list-content__row--bid": "OrderBookList_order-book-list-content__row--bid__tGDDS"
                },
                Ue = c().bind(Ee),
                qe = (0, l.Pi)((function(e) {
                    var t = e.type,
                        n = e.maxVolume,
                        i = e.pending,
                        s = e.price,
                        c = e.qty,
                        l = e.unitPrice,
                        d = e.bidFirst,
                        m = (0, r.G2)(),
                        p = m.service,
                        f = p.setBuys,
                        h = p.setSells,
                        x = p.buySell,
                        b = p.orderType,
                        v = p.setNeedCheckPrice,
                        y = m.coinService,
                        j = y.getTicker,
                        N = y.getTickType,
                        k = y.getCoin,
                        w = y.getMarket,
                        T = y.getClosePriceInfo,
                        I = y.fnToKrw,
                        D = y.subscribeTicker,
                        O = y.getIntroUnitPrice,
                        Z = y.getRoundedClosePrice,
                        A = y.formatQtyDecimalByMarket,
                        F = y.formatAmountDecimalByMarket,
                        M = m.sessionService,
                        R = M.language,
                        B = M.login,
                        L = M.getCustom,
                        E = m.developService.fnGetDataId,
                        U = (0, a.useRef)(E()),
                        q = (0, S.PZ)().getInstance,
                        G = (0, a.useState)(!1),
                        H = (0, o.Z)(G, 2),
                        Q = H[0],
                        K = H[1],
                        Y = (0, a.useState)(""),
                        V = (0, o.Z)(Y, 2),
                        W = V[0],
                        z = V[1],
                        X = (0, a.useState)(!1),
                        J = (0, o.Z)(X, 2),
                        $ = J[0],
                        ee = J[1],
                        te = (0, a.useState)(""),
                        ne = (0, o.Z)(te, 2),
                        ie = ne[0],
                        re = ne[1],
                        oe = (0, a.useState)(""),
                        ae = (0, o.Z)(oe, 2),
                        se = ae[0],
                        ce = ae[1],
                        le = (0, a.useState)(""),
                        de = (0, o.Z)(le, 2),
                        _e = de[0],
                        me = de[1],
                        ue = (0, a.useState)("0"),
                        fe = (0, o.Z)(ue, 2),
                        he = fe[0],
                        ge = fe[1],
                        xe = (0, a.useState)(""),
                        be = (0, o.Z)(xe, 2),
                        ve = be[0],
                        ye = be[1],
                        je = function() {
                            var e = s || "0",
                                r = j(),
                                o = q("0");
                            if (r) {
                                var a = q(r.prevClosePrice);
                                o = N === _.mSn.MID && a.isGreaterThan(0) ? a : q(Number(r.openPrice) ? r.openPrice : r.closePrice), K(q(e).isEqualTo(r.visibleClosePrice))
                            } else K(!1);
                            o.isZero() && (o = q(T(k.coinType, w.crncCd).price));
                            var d, m = o.isZero() ? "0.00" : q(e).div(o).multipliedBy(100).minus(100).abs().toFixed(2);
                            if (Number(m) >= 1e5 && (m = "99999.99"), L(_._28.orderBookPrice)) d = A(c, k.coinType, w.crncCd);
                            else {
                                var u = q(c).multipliedBy(s);
                                d = F(u, k.coinType, w.crncCd)
                            }
                            var p = "";
                            o.isLessThan(e) ? p = "order-book-list-content__price-col--rate-up" : o.isGreaterThan(e) && (p = "order-book-list-content__price-col--rate-down"), z(p), ee(i.some((function(n) {
                                var i = n.tradeTypeCd,
                                    r = n.unitPrice,
                                    o = n.crncCd,
                                    a = n.rsvtOrderStatCd;
                                if ("" !== a && "F" !== a) return !1;
                                if (!(i === (t === _.U1l.Ask ? "1" : "2"))) return !1;
                                var s = "1" === i ? "up" : "down",
                                    c = O(r, k.coinType, o).unitPrice;
                                Number(l.price) && (c = q(c).multipliedBy(l.unit).toFixed());
                                var d = Z(r, c, s, !0);
                                return q(e).isEqualTo(d)
                            }))), re(m), ce(r && w.crncCd !== C.Eo ? I(r.crncCd, r.crncCd, e) : ""), me((0, C.Aq)(e, !0));
                            var f = L(_._28.orderBookPrice) ? q(c) : q(c).multipliedBy(s),
                                h = f.isZero() ? "0" : "".concat(f.div(n).multipliedBy(100).toFixed(), "%");
                            ge(h), ye(d)
                        };
                    (0, a.useEffect)((function() {
                        var e, t = D(k.coinType, w.crncCd, je, U.current);
                        return w.crncCd !== C.Eo && (e = D(w.crncCd, C.Eo, je, U.current)),
                            function() {
                                t && t(), e && e()
                            }
                    }), [k.coinType, w.crncCd, je]), (0, a.useEffect)(je, [s, c, n, R, i, w.crncCd, l, L(_._28.orderBookPrice)]);
                    var Ne = (0, a.useCallback)((function() {
                            if (1 !== b) {
                                v(!0);
                                var e = _e.replace(/,/g, "");
                                0 === x ? f({
                                    price: e,
                                    wchUprc: e
                                }) : h({
                                    price: e,
                                    wchUprc: e
                                })
                            }
                        }), [x, _e, b]),
                        ke = (0, a.useCallback)((function() {
                            0 === x ? f({
                                qty: c
                            }, !1) : h({
                                qty: c
                            }, !1)
                        }), [B, x, c, b]);
                    return (0, u.jsxs)("li", (0, pe.Z)((0, pe.Z)({
                        className: Ue("order-book-list-content__row", {
                            "order-book-list-content__row--active": Q,
                            "order-book-list-content__row--ask": t === _.U1l.Ask,
                            "order-book-list-content__row--bid": t === _.U1l.Bid
                        }),
                        "data-bidfirst": d
                    }, U.current), {}, {
                        children: [(0, u.jsxs)("div", {
                            className: Ue("order-book-list-content__price-col", W, {
                                "order-book-list-content__price-col--order": $
                            }),
                            children: [(0, u.jsx)("div", {
                                className: Ue("order-book-list-content-price"),
                                children: (0, u.jsxs)("strong", {
                                    className: Ue("order-book-list-content-price__num"),
                                    children: [(0, u.jsx)(P.Z, {
                                        className: Ue("order-book-list__auto-scale"),
                                        children: "" !== s && _e
                                    }), "" !== se && (0, u.jsxs)("em", {
                                        className: Ue("order-book-list-price__krw"),
                                        children: [se, " ", R === _.DfJ.ko && "\uc6d0"]
                                    })]
                                })
                            }), "" !== ie && (0, u.jsx)("span", {
                                className: Ue("order-book-list-content__rate"),
                                children: (0, u.jsxs)(P.Z, {
                                    className: Ue("order-book-list-content__rate-auto-scale"),
                                    children: [ie, "%"]
                                })
                            }), (0, u.jsx)(g.ZP, {
                                className: Ue("order-book-list-content-price__button"),
                                onClick: Ne,
                                "aria-label": "\uac00\uaca9 \uc120\ud0dd"
                            })]
                        }), (0, u.jsxs)("div", {
                            className: Ue("order-book-list-content__qty"),
                            children: [(0, u.jsx)("i", {
                                className: Ue("order-book-list-content__percent-bar"),
                                style: {
                                    width: he
                                },
                                "data-q": ve,
                                "data-bar": n
                            }), (0, u.jsx)("p", {
                                className: Ue("order-book-list-content__info-qty"),
                                children: "" !== c && (0, u.jsx)(P.Z, {
                                    className: Ue("order-book-list-content__info-qty-auto-scale"),
                                    children: ve
                                })
                            }), (0, u.jsx)(g.ZP, {
                                className: Ue("order-book-list-content__info-qty__button"),
                                onClick: ke,
                                "aria-label": "\uc218\ub7c9 \uc120\ud0dd"
                            })]
                        })]
                    }))
                })),
                Ge = c().bind(Ee),
                He = function(e) {
                    var t = e.maxLength,
                        n = e.data,
                        i = e.type,
                        r = e.maxVolume,
                        o = e.pending,
                        s = e.unitPrice,
                        c = (0, a.useMemo)((function() {
                            if (n.length < t) {
                                var e = new Array(t - n.length).fill(null);
                                return i === _.U1l.Ask ? [].concat((0, L.Z)(e), (0, L.Z)(n)) : [].concat((0, L.Z)(n), (0, L.Z)(e))
                            }
                            return n
                        }), [n, i]);
                    return (0, u.jsx)(u.Fragment, {
                        children: c && c.map((function(e, t) {
                            return e && e.p ? (0, u.jsx)(qe, {
                                type: i,
                                price: e.p,
                                qty: e.q,
                                maxVolume: r,
                                pending: o,
                                unitPrice: s,
                                bidFirst: !t && i === _.U1l.Bid
                            }, "orderbook-".concat(i, "-").concat(t)) : (0, u.jsx)("li", {
                                className: Ge("order-book-list-content__row", {
                                    "order-book-list-content__row--ask": i === _.U1l.Ask,
                                    "order-book-list-content__row--bid": i === _.U1l.Bid
                                }),
                                "data-bidfirst": !t && i === _.U1l.Bid
                            }, "orderbook-".concat(i, "--").concat(t))
                        }))
                    })
                },
                Qe = (0, a.memo)(He, (function(e, t) {
                    return (0, Ne.Z)(e.data, t.data) && (0, Ne.Z)(e.maxVolume, t.maxVolume) && (0, Ne.Z)(e.unitPrice, t.unitPrice) && (0, Ne.Z)(e.pending, t.pending)
                })),
                Ke = c().bind(Ee),
                Ye = {
                    ask: [{
                        p: "",
                        q: ""
                    }],
                    bid: [{
                        p: "",
                        q: ""
                    }],
                    minBidPrice: null,
                    maxAskPrice: null,
                    askTotalPrice: "0",
                    askTotalQty: "0",
                    bidTotalPrice: "0",
                    bidTotalQty: "0",
                    askMaxQty: "0",
                    bidMaxQty: "0",
                    askMaxPrice: "0",
                    bidMaxPrice: "0"
                },
                Ve = (0, l.Pi)((function(e) {
                    var t = e.unitPrice,
                        n = (0, r.G2)(),
                        i = n.service,
                        s = i.hostObserver,
                        c = i.setFirstOrderPrice,
                        l = i.subscribeTradeStatus,
                        d = i.subscribeSocket,
                        m = n.socketService,
                        p = m.subscribeOrderBook,
                        f = m.subscribeAssetChange,
                        h = n.coinService,
                        g = h.getCoin,
                        x = h.getMarket,
                        b = h.getIntroUnitPrice,
                        v = h.subscribeTickerList,
                        y = n.httpService.get,
                        j = n.localeService.locale,
                        N = n.sessionService,
                        k = N.login,
                        w = N.getCustom,
                        T = n.developService.fnGetDataId,
                        P = (0, a.useRef)(T()),
                        I = (0, S.PZ)(),
                        D = I.getInstance,
                        O = I.max,
                        Z = (0, a.useRef)(null),
                        A = (0, a.useRef)(!0),
                        F = (0, a.useRef)(null),
                        M = (0, a.useRef)(null),
                        R = (0, a.useRef)(0),
                        B = (0, a.useRef)(0),
                        E = (0, a.useRef)(0),
                        U = (0, a.useRef)(0),
                        q = (0, a.useRef)(!1),
                        G = (0, a.useRef)(t),
                        H = (0, a.useRef)(null),
                        Q = (0, a.useRef)({}),
                        K = (0, a.useState)(Ye),
                        Y = (0, o.Z)(K, 2),
                        V = Y[0],
                        W = Y[1],
                        z = (0, a.useState)([]),
                        X = (0, o.Z)(z, 2),
                        J = X[0],
                        $ = X[1],
                        ee = (0, a.useCallback)((function() {
                            if (H.current) {
                                var e = H.current,
                                    t = e.ask,
                                    n = e.bid;
                                c({
                                    ask: t.length ? t[0].p : "",
                                    bid: n.length ? n[0].p : ""
                                }, !0)
                            }
                        }), []),
                        te = (0, a.useCallback)((function() {
                            var e = (0, pe.Z)({}, Ye),
                                t = [-1, "0", "0", "0"];
                            H.current && H.current.ask && H.current.bid && ((0, Me.Z)(H.current, (function(n, i) {
                                var r = i === _.U1l.Ask;
                                e[i] = [], t = [-1, "0", "0", "0", "0", 0];
                                var o = D("0"),
                                    a = D("0");
                                if ("object" === typeof n) {
                                    n instanceof Array && (0, Be.Z)(n, (function(n) {
                                        t[0] > 28 || "" === n.p && "" === n.q || (t = [Number(t[0]) + 1, n.p, n.q, n.t || "0", n.d || "0", -1 !== n.p.indexOf(".") ? n.p.split(".")[1].length : 0], o = o.plus(n.q || 0), a = a.plus(D(n.q || 0).multipliedBy(n.p || 0)), e[i][t[0]] = {
                                            p: t[1],
                                            q: t[2],
                                            t: t[3],
                                            d: t[4],
                                            dp: t[5]
                                        })
                                    })), r && e[i].reverse();
                                    var s = o.toFormat(4),
                                        c = (0, C.Aq)(a.toString(), !0);
                                    r ? (e.askTotalQty = s, e.askTotalPrice = c, e.askMaxQty = O(e.ask, (function(e) {
                                        return e.q
                                    })).toFixed(), e.askMaxPrice = O(e.ask, (function(e) {
                                        return D(e.q).multipliedBy(D(e.p))
                                    })).toFixed()) : (e.bidTotalQty = s, e.bidTotalPrice = c, e.bidMaxQty = O(e.bid, (function(e) {
                                        return e.q
                                    })).toFixed(), e.bidMaxPrice = O(e.bid, (function(e) {
                                        return D(e.q).multipliedBy(D(e.p))
                                    })).toFixed())
                                }
                            })), W(e), A.current && (A.current = !1, clearTimeout(E.current), E.current = window.setTimeout((function() {
                                var e = document.querySelector("[data-bidfirst=true]");
                                Z.current && e && (Z.current.scrollTop = 0, Z.current.scrollTop = e.getBoundingClientRect().top - Z.current.getBoundingClientRect().top - Z.current.clientHeight / 2)
                            }), 100)))
                        }), [t, x.crncCd]),
                        ne = function e() {
                            var t, n;
                            q.current || (q.current = !0, null === (t = F.current) || void 0 === t || t.cancel(), F.current = Fe.Z.CancelToken.source(), Fe.Z.get("".concat(s, "/orderbook/v1/orderbook/").concat(g.coinSymbol, "_").concat(x.marketSymbol, "/").concat(G.current.unit, "?retry=").concat(U.current), {
                                headers: {
                                    Accept: "application/json, text/javascript, */*"
                                },
                                cancelToken: null === (n = F.current) || void 0 === n ? void 0 : n.token
                            }).then((function(e) {
                                if (e && 200 === e.status) {
                                    var t = e.data,
                                        n = t.data[_.U1l.Ask],
                                        i = t.data[_.U1l.Bid];
                                    U.current = 0, H.current = {
                                        ask: n,
                                        bid: i,
                                        minBidPrice: i.length >= 30 ? i[i.length - 1].p : null,
                                        maxAskPrice: n.length >= 30 ? n[n.length - 1].p : null
                                    }, ee(), te()
                                } else U.current++
                            })).catch((function(e) {
                                Fe.Z.isCancel(e) || U.current++
                            })).finally((function() {
                                F.current = null, q.current = !1, clearTimeout(R.current), R.current = window.setTimeout((function() {
                                    return e()
                                }), 3e4)
                            })))
                        },
                        ie = function() {
                            var e;
                            if (k) {
                                var t;
                                if (M.current) null === (t = M.current) || void 0 === t || t.cancel(), M.current = null;
                                M.current = Fe.Z.CancelToken.source(), y("/v1/trade/pending-orders/".concat(g.coinType, "-").concat(x.crncCd, "/100"), {
                                    autotrading: "N"
                                }, !1, {
                                    cancelToken: null === (e = M.current) || void 0 === e ? void 0 : e.token
                                }).then((function(e) {
                                    200 === e.status && $(e.data.pendingOrderList)
                                }))
                            }
                        },
                        re = function() {
                            clearTimeout(B.current), B.current = window.setTimeout(ie, 1e3)
                        },
                        oe = function(e) {
                            if (H.current && g.coinType === e.coinType && x.crncCd === e.crncCd) {
                                var n = (0, pe.Z)({}, H.current),
                                    i = [],
                                    r = e.sellOrderList.map((function(e) {
                                        return {
                                            o: "1",
                                            p: e.price,
                                            q: e.quantity
                                        }
                                    })),
                                    o = e.buyOrderList.map((function(e) {
                                        return {
                                            o: "2",
                                            p: e.price,
                                            q: e.quantity
                                        }
                                    }));
                                [].concat((0, L.Z)(r), (0, L.Z)(o)).forEach((function(e) {
                                    var t = e.o,
                                        r = e.q,
                                        o = e.p;
                                    o = D(o).toFixed(b(o, g.coinType, x.crncCd).lang);
                                    var a = "1" === t,
                                        s = a ? "ask" : "bid",
                                        c = a ? !(0, Re.Z)(n.maxAskPrice) : !(0, Re.Z)(n.minBidPrice),
                                        l = !1;
                                    if ("0" === e.q && i.push({
                                            isRemove: !0,
                                            orderBookType: s
                                        }), n[s].forEach((function(e, t) {
                                            if (D(o).isEqualTo(e.p))
                                                if (l = !0, "0" === r) n[s].splice(t, 1);
                                                else {
                                                    var i = D(r).minus(n[s][t].q).toFixed(),
                                                        a = {
                                                            qty: i,
                                                            type: s
                                                        };
                                                    a.timeOut = window.setTimeout((function() {
                                                        if (H.current) {
                                                            var e = (0, pe.Z)({}, H.current),
                                                                t = Q.current[o];
                                                            if (t) {
                                                                var n = e[t.type],
                                                                    i = (0, Le.Z)(n, {
                                                                        p: o
                                                                    });
                                                                delete Q.current[o], -1 === i || -1 !== i && n[i].d !== t.qty || (delete e[t.type][i].d, H.current = (0, pe.Z)({}, e), ee(), te())
                                                            }
                                                        }
                                                    }), 1e3), Q.current[o] && window.clearTimeout(Q.current[o].timeOut), Q.current[o] = a, n[s][t].d = i, n[s][t].q = D(r).toFixed()
                                                }
                                        })), !l && "0" !== r) {
                                        var d = {
                                            o: t,
                                            q: r,
                                            p: o
                                        };
                                        c ? a ? n.maxAskPrice && D(o).isLessThan(n.maxAskPrice) && n[s].push(d) : n.minBidPrice && D(o).isGreaterThan(n.minBidPrice) && n[s].push(d) : n[s].push(d), n[s] = n[s].sort((function(e, t) {
                                            var n = D(e.p),
                                                i = D(t.p);
                                            return i.isLessThan(n) ? a ? 1 : -1 : i.isGreaterThan(n) ? a ? -1 : 1 : 0
                                        }))
                                    }
                                })), i.filter((function(e) {
                                    return e.isRemove && n[e.orderBookType].length < 30
                                })).length > 0 && ne(), n.ask.length > 0 && n.bid.length > 0 && (!t.price && D(n.ask[0].p).isLessThanOrEqualTo(n.bid[0].p) || t.price && D(n.ask[0].p).isLessThan(n.bid[0].p)) ? ne() : (H.current = (0, pe.Z)({}, n), ee(), te())
                            }
                        },
                        ae = function() {
                            return w(_._28.orderBookPrice) ? D(V.askMaxQty).isGreaterThan(V.bidMaxQty) ? V.askMaxQty : V.bidMaxQty : D(V.askMaxPrice).isGreaterThan(V.bidMaxPrice) ? V.askMaxPrice : V.bidMaxPrice
                        };
                    return (0, a.useEffect)((function() {
                        (0, Ne.Z)(G.current, t) || (A.current = !0, G.current = t, q.current = !1, ne(), d())
                    }), [t]), (0, a.useEffect)((function() {
                        var e, t, n, i;
                        return A.current = !0, H.current = null, q.current = !1, W(Ye), $([]), ne(), k && (ie(), e = l((function(e) {
                                e.type === _.OJD.orderSuccess && ie()
                            })), t = f(re)), n = p(oe), i = v(ne, P.current),
                            function() {
                                var r, o;
                                e && e(), t && t(), n && n(), i && i(), clearTimeout(R.current), clearTimeout(B.current), clearTimeout(E.current), null === (r = F.current) || void 0 === r || r.cancel(), null === (o = M.current) || void 0 === o || o.cancel()
                            }
                    }), [k, g.coinType, x.crncCd, g.coinSymbol]), (0, u.jsxs)("div", (0, pe.Z)((0, pe.Z)({
                        className: Ke("order-book-list")
                    }, P.current), {}, {
                        children: [(0, u.jsxs)("h3", {
                            className: Ke("order-book-list-title"),
                            children: [(0, u.jsxs)("span", {
                                className: Ke("order-book-list-title__text"),
                                children: [j("page.trade.msg091"), "(", x.marketSymbol, ")"]
                            }), w(_._28.orderBookPrice) ? (0, u.jsxs)("span", {
                                className: Ke("order-book-list-title__text"),
                                children: [j("page.trade.msg092"), "(", g.coinSymbol, ")"]
                            }) : (0, u.jsxs)("span", {
                                className: Ke("order-book-list-title__text"),
                                children: [j("page.trade.msg093"), "(", x.marketSymbol, ")"]
                            })]
                        }), (0, u.jsx)("div", {
                            className: Ke("cm-gray-scroll ", "order-book-list-content"),
                            ref: Z,
                            children: (0, u.jsxs)("ul", {
                                children: [(0, u.jsx)(Qe, {
                                    maxLength: 30,
                                    type: _.U1l.Ask,
                                    data: V.ask,
                                    maxVolume: ae(),
                                    pending: J,
                                    unitPrice: t
                                }), (0, u.jsx)(Qe, {
                                    maxLength: 30,
                                    type: _.U1l.Bid,
                                    data: V.bid,
                                    maxVolume: ae(),
                                    pending: J,
                                    unitPrice: t
                                })]
                            })
                        })]
                    }))
                })),
                We = c().bind({
                    "order-book": "OrderBook_order-book__mMEAi",
                    "order-book__notice": "OrderBook_order-book__notice__9-Lwj"
                }),
                ze = (N = {}, (0, je.Z)(N, C.Eo, {
                    ko: "4",
                    en: "fourth"
                }), (0, je.Z)(N, C.B4, {
                    ko: "4",
                    en: "fourth"
                }), (0, je.Z)(N, C.NE, {
                    ko: "5",
                    en: "fifth"
                }), N),
                Xe = (0, l.Pi)((function() {
                    var e, t = (0, r.G2)(),
                        n = t.service,
                        i = n.unitPrice,
                        o = n.setUnitPrice,
                        a = n.tradeInfo.crncCd,
                        s = t.sessionService.language,
                        c = t.localeService.locale,
                        l = null === (e = ze[a]) || void 0 === e ? void 0 : e[s];
                    return (0, u.jsxs)("div", {
                        className: We("order-book"),
                        children: [(0, u.jsx)("h3", {
                            className: "blind",
                            children: "\ud638\uac00"
                        }), (0, u.jsx)(Ae, {
                            setUnitPrice: o
                        }), (0, u.jsx)(Ve, {
                            unitPrice: i
                        }), (0, u.jsx)("p", {
                            className: We("order-book__notice"),
                            children: c("page.trade.msg097", {
                                decimal: l
                            })
                        })]
                    })
                })),
                Je = {
                    "buy-sell-tab": "BuySellTab_buy-sell-tab__AuPL0",
                    "buy-sell-tab__row": "BuySellTab_buy-sell-tab__row__RRPPF",
                    "buy-sell-tab__row--sub": "BuySellTab_buy-sell-tab__row--sub__Lq1g0",
                    "buy-sell-tab__title": "BuySellTab_buy-sell-tab__title__mXkft",
                    "buy-sell-tab__title--top": "BuySellTab_buy-sell-tab__title--top__Xp4ve",
                    "buy-sell-tab__box": "BuySellTab_buy-sell-tab__box__5jjBr",
                    "buy-sell-tab-radio": "BuySellTab_buy-sell-tab-radio__Jzu-2",
                    "buy-sell-tab-radio__inner": "BuySellTab_buy-sell-tab-radio__inner__2jAnK",
                    "buy-sell-tab-radio__label": "BuySellTab_buy-sell-tab-radio__label__dWE84",
                    "buy-sell-tab-radio__icon": "BuySellTab_buy-sell-tab-radio__icon__VSgOF",
                    "buy-sell-tab-tip": "BuySellTab_buy-sell-tab-tip__Qwa-e",
                    "buy-sell-tab-tip__button": "BuySellTab_buy-sell-tab-tip__button__eWfab",
                    "buy-sell-tab-tip-layer": "BuySellTab_buy-sell-tab-tip-layer__cgK8-",
                    "buy-sell-tab-tip-layer__title": "BuySellTab_buy-sell-tab-tip-layer__title__kjiWc",
                    "buy-sell-tab-tip-layer__title--red": "BuySellTab_buy-sell-tab-tip-layer__title--red__ygvms",
                    "buy-sell-tab-tip-layer__title--blue": "BuySellTab_buy-sell-tab-tip-layer__title--blue__RDcZA",
                    "buy-sell-tab-tip-layer__text": "BuySellTab_buy-sell-tab-tip-layer__text__tp7Wh",
                    "buy-sell-tab-tip-layer__list": "BuySellTab_buy-sell-tab-tip-layer__list__DuzpY",
                    "buy-sell-tab__available-price": "BuySellTab_buy-sell-tab__available-price__kVt-e",
                    "buy-sell-tab__available-trans-price": "BuySellTab_buy-sell-tab__available-trans-price__5lrjg",
                    "buy-sell-tab__available-symbol": "BuySellTab_buy-sell-tab__available-symbol__Q2xmY"
                },
                $e = c().bind(Je),
                et = (0, l.Pi)((function() {
                    var e, t, n, i, s = (0, r.G2)(),
                        c = s.localeService.locale,
                        l = s.coinService,
                        d = l.getCoin,
                        m = l.getMarket,
                        p = l.fnToKrw,
                        f = l.getCoinInfo,
                        h = l.subscribeTicker,
                        g = s.assetService,
                        x = g.getCoinAsset,
                        b = g.subscribeAssetChange,
                        v = s.sessionService,
                        y = v.language,
                        j = v.getRate,
                        N = v.login,
                        k = s.service.buySell,
                        w = s.developService.fnGetDataId,
                        T = (0, a.useRef)(w()),
                        P = (0, S.PZ)().getInstance,
                        I = (0, a.useState)({
                            price: "0",
                            symbol: ""
                        }),
                        D = (0, o.Z)(I, 2),
                        O = D[0],
                        Z = D[1],
                        A = (0, a.useState)(""),
                        F = (0, o.Z)(A, 2),
                        M = F[0],
                        R = F[1],
                        B = (0, a.useCallback)((function(e) {
                            var t = 0 === k,
                                n = y === _.DfJ.ko;
                            if (m.crncCd === C.Eo) R(n ? "" : t ? "\u2248 ".concat(j((0, C.uR)(e), 2)) : p(d.coinType, m.crncCd, e, !0));
                            else if (!N || new($())(e).isZero()) R(n ? "\u2248 0 ".concat(c("page.trade.msg136")) : "\u2248 $0.00");
                            else if (t) R("\u2248 ".concat(p(m.crncCd, m.crncCd, e), " ").concat(c("page.trade.msg136")));
                            else {
                                var i = f(d.coinType, C.Eo, !0).infoOnMarket;
                                R("\u2248 ".concat(p(d.coinType, i ? C.Eo : m.crncCd, e, !0), " ").concat(c("page.trade.msg136")))
                            }
                        }), [k, y, d.coinType, m.crncCd]),
                        L = (0, a.useCallback)((function() {
                            var e = 0 === k,
                                t = m.crncCd === C.Eo,
                                n = {
                                    price: "0",
                                    symbol: e ? (0, C.c0)(m.marketSymbol, y) : d.coinSymbol
                                };
                            if (N)
                                if (e) {
                                    var i, r = (null === (i = x(m.crncCd)) || void 0 === i ? void 0 : i.available) || 0;
                                    n.price = t ? new($())(r).toFormat(0, $().ROUND_DOWN) : P(r).toFormat()
                                } else {
                                    var o;
                                    n.price = P((null === (o = x(d.coinType)) || void 0 === o ? void 0 : o.available) || 0).toFormat()
                                }
                            else t && e || (n.price = new($())(0).toFormat(8));
                            B(n.price), Z((function() {
                                return n
                            }))
                        }), [N, d.coinType, m.crncCd, null === (e = x(m.crncCd)) || void 0 === e ? void 0 : e.available, null === (t = x(d.coinType)) || void 0 === t ? void 0 : t.available, k, y]);
                    return (0, a.useEffect)(L, [N, d.coinType, m.crncCd, null === (n = x(m.crncCd)) || void 0 === n ? void 0 : n.available, null === (i = x(d.coinType)) || void 0 === i ? void 0 : i.available, k, y]), (0, a.useEffect)((function() {
                        var e, t;
                        return N && (e = b(d.coinType, L), m.crncCd !== C.Eo && (t = h(m.crncCd, C.Eo, L, T.current))),
                            function() {
                                e && e(), t && t()
                            }
                    }), [N, d.coinType, m.crncCd, L]), (0, u.jsxs)(u.Fragment, {
                        children: [(0, u.jsx)("h4", {
                            className: $e("buy-sell-tab__title", "buy-sell-tab__title--top"),
                            children: c("page.trade.msg128")
                        }), (0, u.jsxs)("div", (0, pe.Z)((0, pe.Z)({
                            className: $e("buy-sell-tab__box")
                        }, T.current), {}, {
                            children: [(0, u.jsxs)("span", {
                                className: $e("buy-sell-tab__available-price"),
                                children: [O.price, " ", (0, u.jsx)("em", {
                                    className: $e("buy-sell-tab__available-symbol"),
                                    children: O.symbol
                                })]
                            }), M && (0, u.jsx)("strong", {
                                className: $e("buy-sell-tab__available-trans-price"),
                                children: M
                            })]
                        }))]
                    })
                })),
                tt = {
                    "button-row": "ButtonRow_button-row__dX+Av",
                    "button-row__login": "ButtonRow_button-row__login__fOphb",
                    "button-row__stop": "ButtonRow_button-row__stop__sEnI7",
                    "button-row__buy": "ButtonRow_button-row__buy__LFqYt",
                    "button-row__sell": "ButtonRow_button-row__sell__1LIMv",
                    "button-inner-countdown": "ButtonRow_button-inner-countdown__9XXvC",
                    "button-inner-countdown__typo": "ButtonRow_button-inner-countdown__typo__bMW+p",
                    "button-inner-countdown__timer": "ButtonRow_button-inner-countdown__timer__SxlJS",
                    "button-bottom-countdown": "ButtonRow_button-bottom-countdown__XOEXr",
                    "button-bottom-countdown__typo": "ButtonRow_button-bottom-countdown__typo__5i7nX",
                    "button-bottom-countdown__timer": "ButtonRow_button-bottom-countdown__timer__zPdol",
                    "button-bottom-countdown--highlight": "ButtonRow_button-bottom-countdown--highlight__H5hon"
                },
                nt = n(83178),
                it = n.n(nt);
            T().extend(it());
            var rt = c().bind(tt),
                ot = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.service.buySell,
                        n = e.localeService.locale,
                        i = e.countdownService,
                        o = i.isListedNew,
                        a = i.willSupportBuyTrading,
                        s = i.willSupportSellTrading,
                        c = i.buyOpenTimeLeft,
                        l = i.sellOpenTimeLeft,
                        d = t === _.v$z.BUY,
                        m = T().duration(d ? c : l),
                        p = m.asSeconds(),
                        f = String(Math.floor(m.asDays())).padStart(2, "0"),
                        h = m.asDays() >= 1,
                        g = m.format("HH"),
                        x = m.format("mm"),
                        b = m.format("ss");
                    return (d ? a : s) ? (0, u.jsx)("span", {
                        className: rt("button-inner-countdown"),
                        children: (0, u.jsx)("span", {
                            className: rt("button-inner-countdown__timer"),
                            children: n("page.trade.buttonRow.msg002")
                        })
                    }) : p > 0 && o ? (0, u.jsxs)("span", {
                        className: rt("button-inner-countdown"),
                        children: [(0, u.jsx)("span", {
                            className: rt("button-inner-countdown__typo"),
                            children: n("page.trade.buttonRow.msg001")
                        }), (0, u.jsxs)("span", {
                            className: rt("button-inner-countdown__timer"),
                            children: [h ? "".concat(f, ":") : "", g, ":", x, ":", b]
                        })]
                    }) : null
                }));
            T().extend(it());
            var at, st = c().bind(tt),
                ct = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.service.buySell,
                        n = e.localeService.locale,
                        i = e.countdownService,
                        o = i.isListedNew,
                        a = i.willSupportBuyTrading,
                        s = i.willSupportSellTrading,
                        c = i.buyOpenTimeLeft,
                        l = i.sellOpenTimeLeft,
                        d = t === _.v$z.BUY,
                        m = T().duration(d ? c : l),
                        p = m.asSeconds(),
                        f = String(Math.floor(m.asDays())).padStart(2, "0"),
                        h = m.asDays() >= 1,
                        g = m.format("HH"),
                        x = m.format("mm");
                    return (d ? a : s) ? (0, u.jsx)("div", {
                        className: st("button-bottom-countdown"),
                        children: (0, u.jsx)("span", {
                            className: st("button-bottom-countdown__typo"),
                            children: n(d ? "page.trade.buttonRow.msg005" : "page.trade.buttonRow.msg006")
                        })
                    }) : p > 0 && o ? (0, u.jsxs)("div", {
                        className: st("button-bottom-countdown", {
                            "button-bottom-countdown--highlight": p < 60
                        }),
                        children: [(0, u.jsx)("span", {
                            className: st("button-bottom-countdown__typo"),
                            children: n(d ? "page.trade.buttonRow.msg003" : "page.trade.buttonRow.msg004")
                        }), (0, u.jsxs)("span", {
                            className: st("button-bottom-countdown__timer"),
                            children: [h ? "".concat(f, ":") : "", g, ":", x, ":", (0, u.jsx)("em", {
                                children: m.format("ss")
                            })]
                        })]
                    }) : null
                })),
                lt = c().bind({
                    "confirm-modal": "ConfirmModal_confirm-modal__nnXMh",
                    "confirm-modal__title": "ConfirmModal_confirm-modal__title__Eqd+d",
                    "confirm-modal-warring": "ConfirmModal_confirm-modal-warring__84DUQ",
                    "confirm-modal-warring__title": "ConfirmModal_confirm-modal-warring__title__XHzWO",
                    "confirm-modal-warring__date": "ConfirmModal_confirm-modal-warring__date__TsrGj",
                    "confirm-modal-warring__text": "ConfirmModal_confirm-modal-warring__text__uk8cH",
                    "confirm-modal-info-table": "ConfirmModal_confirm-modal-info-table__11TEM",
                    "confirm-modal-info-table__unit": "ConfirmModal_confirm-modal-info-table__unit__Z98ed",
                    "auto-scale": "ConfirmModal_auto-scale__U6WAB",
                    "confirm-modal-scale-box": "ConfirmModal_confirm-modal-scale-box__jBK3M",
                    "confirm-modal-button": "ConfirmModal_confirm-modal-button__yvXgX",
                    "confirm-modal-button__button": "ConfirmModal_confirm-modal-button__button__JQa5d",
                    "confirm-modal-button__button--buy": "ConfirmModal_confirm-modal-button__button--buy__9R5hA",
                    "confirm-modal-button__button--sell": "ConfirmModal_confirm-modal-button__button--sell__26ccb",
                    "confirm-modal-notice-list": "ConfirmModal_confirm-modal-notice-list__N9o8P",
                    "confirm-modal-notice-list__item": "ConfirmModal_confirm-modal-notice-list__item__ImZBL",
                    "confirm-modal-notice-list__item-text--buy": "ConfirmModal_confirm-modal-notice-list__item-text--buy__2Lzzi",
                    "confirm-modal-notice-list__item-text--sell": "ConfirmModal_confirm-modal-notice-list__item-text--sell__Pn7ht"
                }),
                dt = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.service,
                        n = t.buySell,
                        i = t.orderType,
                        s = t.buyInfo,
                        c = t.sellInfo,
                        l = e.modalService.visible,
                        d = e.localeService.locale,
                        m = e.coinService,
                        p = m.getCoin,
                        f = m.getMarket,
                        h = m.getTicker,
                        x = e.sessionService.language,
                        b = (0, S.PZ)().getInstance,
                        v = l(ne.ZM),
                        y = (0, a.useState)("0"),
                        j = (0, o.Z)(y, 2),
                        N = j[0],
                        k = j[1],
                        w = (0, a.useState)({
                            price: "",
                            qty: "",
                            amt: "",
                            realAmt: "",
                            per: "0",
                            wchUprc: ""
                        }),
                        I = (0, o.Z)(w, 2),
                        D = I[0],
                        O = I[1],
                        Z = f.crncCd === C.Eo ? d("comm.msg016") : f.marketSymbol;
                    return (0, a.useEffect)((function() {
                        if (v) {
                            var e = h();
                            e && k(e.closePrice), O((function() {
                                return 0 === n ? (0, pe.Z)({}, s) : (0, pe.Z)({}, c)
                            }))
                        }
                    }), [!!v]), (0, u.jsx)(ee.ZP, {
                        visible: v,
                        children: (0, u.jsxs)("div", {
                            className: lt("confirm-modal"),
                            children: [(0, u.jsx)("h3", {
                                className: lt("confirm-modal__title"),
                                children: d(0 === n ? "pop.trade.orderConfirm.msg019" : "pop.trade.orderConfirm.msg020")
                            }), p.infoOnMarket && (p.infoOnMarket.isInvestment || "0" !== p.infoOnMarket.closeExceptedDate) && (0, u.jsx)("div", {
                                className: lt("confirm-modal-warring"),
                                children: "0" === p.infoOnMarket.closeExceptedDate ? (0, u.jsxs)(u.Fragment, {
                                    children: [(0, u.jsx)("h4", {
                                        className: lt("confirm-modal-warring__title"),
                                        children: d("pop.trade.orderConfirm.msg021")
                                    }), (0, u.jsx)("p", {
                                        className: lt("confirm-modal-warring__text"),
                                        children: d("pop.trade.orderConfirm.msg022", {
                                            tag: (0, u.jsx)("br", {})
                                        })
                                    })]
                                }) : (0, u.jsxs)(u.Fragment, {
                                    children: [(0, u.jsx)("h4", {
                                        className: lt("confirm-modal-warring__title"),
                                        children: d("pop.trade.orderConfirm.msg001")
                                    }), (0, u.jsxs)("p", {
                                        className: lt("confirm-modal-warring__text"),
                                        children: [(0, u.jsx)("strong", {
                                            className: lt("confirm-modal-warring__date"),
                                            children: T()(p.infoOnMarket.closeExceptedDate).format("YYYY-MM-DD HH:mm")
                                        }), d("pop.trade.orderConfirm.msg002", {
                                            tag: (0, u.jsx)("br", {})
                                        })]
                                    })]
                                })
                            }), (0, u.jsx)("div", {
                                className: lt("confirm-modal-info-table"),
                                children: (0, u.jsxs)("table", {
                                    children: [(0, u.jsx)("caption", {
                                        children: "\uc8fc\ubb38\ub0b4\uc5ed \uc0c1\uc138"
                                    }), (0, u.jsxs)("colgroup", {
                                        children: [(0, u.jsx)("col", {
                                            style: {
                                                width: "76px"
                                            }
                                        }), (0, u.jsx)("col", {})]
                                    }), (0, u.jsxs)("tbody", {
                                        children: [(0, u.jsxs)("tr", {
                                            children: [(0, u.jsx)("th", {
                                                scope: "row",
                                                children: d("pop.trade.orderConfirm.msg003")
                                            }), (0, u.jsx)("td", {
                                                children: "".concat(x === _.DfJ.ko ? p.coinName : p.coinNameEn, "(").concat(p.coinSymbol, ")")
                                            })]
                                        }), 2 === i && (0, u.jsxs)(u.Fragment, {
                                            children: [(0, u.jsxs)("tr", {
                                                children: [(0, u.jsx)("th", {
                                                    scope: "row",
                                                    children: d("pop.trade.orderConfirm.msg004")
                                                }), (0, u.jsx)("td", {
                                                    children: (0, u.jsx)("strong", {
                                                        children: d("pop.trade.orderConfirm.msg005")
                                                    })
                                                })]
                                            }), (0, u.jsxs)("tr", {
                                                children: [(0, u.jsx)("th", {
                                                    scope: "row",
                                                    children: d("pop.trade.orderConfirm.msg006")
                                                }), (0, u.jsxs)("td", {
                                                    children: [D.price ? b(D.price).toFormat() : "0", (0, u.jsx)("span", {
                                                        className: lt("confirm-modal-info-table__unit"),
                                                        children: Z
                                                    })]
                                                })]
                                            }), (0, u.jsxs)("tr", {
                                                children: [(0, u.jsx)("th", {
                                                    scope: "row",
                                                    children: d("pop.trade.orderConfirm.msg007")
                                                }), (0, u.jsx)("td", {
                                                    children: (0, u.jsxs)("div", {
                                                        className: lt("confirm-modal-scale-box"),
                                                        children: [(0, u.jsx)("strong", {
                                                            children: D.qty ? (0, u.jsx)(P.Z, {
                                                                className: lt("auto-scale"),
                                                                children: b(D.qty).toFormat()
                                                            }) : "0"
                                                        }), (0, u.jsx)("span", {
                                                            className: lt("confirm-modal-info-table__unit"),
                                                            children: p.coinSymbol
                                                        })]
                                                    })
                                                })]
                                            }), 0 === n && (0, u.jsxs)("tr", {
                                                children: [(0, u.jsx)("th", {
                                                    scope: "row",
                                                    children: d("pop.trade.orderConfirm.msg008")
                                                }), (0, u.jsxs)("td", {
                                                    children: [D.amt ? b(D.amt).toFormat() : "0", (0, u.jsx)("span", {
                                                        className: lt("confirm-modal-info-table__unit"),
                                                        children: Z
                                                    })]
                                                })]
                                            })]
                                        }), 1 === i && (0, u.jsxs)(u.Fragment, {
                                            children: [(0, u.jsxs)("tr", {
                                                children: [(0, u.jsx)("th", {
                                                    scope: "row",
                                                    children: d("pop.trade.orderConfirm.msg004")
                                                }), (0, u.jsx)("td", {
                                                    children: d("pop.trade.orderConfirm.msg009")
                                                })]
                                            }), (0, u.jsx)("tr", {
                                                children: 0 === n ? (0, u.jsxs)(u.Fragment, {
                                                    children: [(0, u.jsx)("th", {
                                                        scope: "row",
                                                        children: d("pop.trade.orderConfirm.msg008")
                                                    }), (0, u.jsxs)("td", {
                                                        children: [D.amt ? b(D.amt).toFormat() : "0", (0, u.jsx)("span", {
                                                            className: lt("confirm-modal-info-table__unit"),
                                                            children: Z
                                                        })]
                                                    })]
                                                }) : (0, u.jsxs)(u.Fragment, {
                                                    children: [(0, u.jsx)("th", {
                                                        scope: "row",
                                                        children: d("pop.trade.orderConfirm.msg007")
                                                    }), (0, u.jsxs)("td", {
                                                        children: [D.qty ? (0, u.jsx)(P.Z, {
                                                            className: lt("auto-scale"),
                                                            children: b(D.qty).toFormat()
                                                        }) : "0", (0, u.jsx)("span", {
                                                            className: lt("confirm-modal-info-table__unit"),
                                                            children: p.coinSymbol
                                                        })]
                                                    })]
                                                })
                                            })]
                                        }), 3 === i && (0, u.jsxs)(u.Fragment, {
                                            children: [(0, u.jsxs)("tr", {
                                                children: [(0, u.jsx)("th", {
                                                    scope: "row",
                                                    children: d("pop.trade.orderConfirm.msg004")
                                                }), (0, u.jsx)("td", {
                                                    children: d(0 === n ? "pop.trade.orderConfirm.msg010" : "pop.trade.orderConfirm.msg011")
                                                })]
                                            }), (0, u.jsxs)("tr", {
                                                children: [(0, u.jsx)("th", {
                                                    scope: "row",
                                                    children: d("pop.trade.orderConfirm.msg012")
                                                }), (0, u.jsxs)("td", {
                                                    children: [D.wchUprc ? (0, u.jsxs)(u.Fragment, {
                                                        children: [b(D.wchUprc).isGreaterThanOrEqualTo(N) ? "\u2265 " : "\u2264 ", b(D.wchUprc).toFormat()]
                                                    }) : "0", (0, u.jsx)("span", {
                                                        className: lt("confirm-modal-info-table__unit"),
                                                        children: Z
                                                    })]
                                                })]
                                            }), (0, u.jsxs)("tr", {
                                                children: [(0, u.jsx)("th", {
                                                    scope: "row",
                                                    children: d("pop.trade.orderConfirm.msg006")
                                                }), (0, u.jsxs)("td", {
                                                    children: [D.price ? b(D.price).toFormat() : "0", (0, u.jsx)("span", {
                                                        className: lt("confirm-modal-info-table__unit"),
                                                        children: Z
                                                    })]
                                                })]
                                            }), (0, u.jsxs)("tr", {
                                                children: [(0, u.jsx)("th", {
                                                    scope: "row",
                                                    children: d("pop.trade.orderConfirm.msg007")
                                                }), (0, u.jsx)("td", {
                                                    children: (0, u.jsxs)("div", {
                                                        className: lt("confirm-modal-scale-box"),
                                                        children: [(0, u.jsx)("strong", {
                                                            children: D.qty ? (0, u.jsx)(P.Z, {
                                                                className: lt("auto-scale"),
                                                                children: b(D.qty).toFormat()
                                                            }) : "0"
                                                        }), (0, u.jsx)("span", {
                                                            className: lt("confirm-modal-info-table__unit"),
                                                            children: p.coinSymbol
                                                        })]
                                                    })
                                                })]
                                            }), (0, u.jsxs)("tr", {
                                                children: [(0, u.jsx)("th", {
                                                    scope: "row",
                                                    children: d("pop.trade.orderConfirm.msg008")
                                                }), (0, u.jsxs)("td", {
                                                    children: [D.amt ? b(D.amt).toFormat() : "0", (0, u.jsx)("span", {
                                                        className: lt("confirm-modal-info-table__unit"),
                                                        children: Z
                                                    })]
                                                })]
                                            })]
                                        })]
                                    })]
                                })
                            }), 3 === i && (0, u.jsxs)("ul", {
                                className: lt("confirm-modal-notice-list"),
                                children: [(0, u.jsx)("li", {
                                    className: lt("confirm-modal-notice-list__item"),
                                    children: d(0 === n ? "pop.trade.orderConfirm.msg013" : "pop.trade.orderConfirm.msg014", {
                                        coinNm: x === _.DfJ.ko ? p.coinName : p.coinNameEn,
                                        symbol: p.coinSymbol,
                                        wPrice: "".concat(b(D.wchUprc || "0").toFormat(), " ").concat(Z),
                                        tag: (0, u.jsx)("span", {
                                            className: lt({
                                                "confirm-modal-notice-list__item-text--buy": 0 === n,
                                                "confirm-modal-notice-list__item-text--sell": 1 === n
                                            })
                                        }),
                                        price: "".concat(b(D.price || "0").toFormat(), " ").concat(Z)
                                    })
                                }), (0, u.jsx)("li", {
                                    className: lt("confirm-modal-notice-list__item"),
                                    children: d("pop.trade.orderConfirm.msg015")
                                })]
                            }), (0, u.jsxs)("div", {
                                className: lt("confirm-modal-button"),
                                children: [(0, u.jsx)(g.ZP, {
                                    className: lt("confirm-modal-button__button", 0 === n ? "confirm-modal-button__button--buy" : "confirm-modal-button__button--sell"),
                                    onClick: null === v || void 0 === v ? void 0 : v.params.okCallback,
                                    children: d(0 === n ? "pop.trade.orderConfirm.msg016" : "pop.trade.orderConfirm.msg017")
                                }), (0, u.jsx)(g.ZP, {
                                    className: lt("confirm-modal-button__button"),
                                    onClick: null === v || void 0 === v ? void 0 : v.params.cancelCallback,
                                    children: d("pop.trade.orderConfirm.msg018")
                                })]
                            })]
                        })
                    })
                })),
                _t = c().bind(tt),
                mt = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.service,
                        n = t.buySell,
                        i = t.orderConfirm,
                        o = e.sessionService,
                        s = o.login,
                        c = o.getCustom,
                        l = e.localeService.locale,
                        m = e.gaService.fnGASendEvent,
                        p = e.coinService,
                        f = p.getMarket,
                        h = p.getCoin,
                        x = p.getIntro,
                        b = (0, d.s0)(),
                        v = (0, d.TH)().pathname,
                        y = (0, a.useMemo)((function() {
                            return null === x || void 0 === x ? void 0 : x.coinsOnMarketList[f.crncCd].find((function(e) {
                                return e.coinSymbol === h.coinSymbol
                            }))
                        }), [null === x || void 0 === x ? void 0 : x.coinsOnMarketList, h.coinSymbol, f.crncCd]),
                        j = n === _.v$z.BUY;
                    return s ? null !== y && void 0 !== y && y.canTrade ? (0, u.jsxs)(u.Fragment, {
                        children: [(0, u.jsx)("div", {
                            className: _t("button-row"),
                            children: (0, u.jsxs)(g.ZP, {
                                type: g.PD.DefaultNew,
                                size: g.VA.Large,
                                className: _t(j ? "button-row__buy" : "button-row__sell"),
                                onClick: function() {
                                    return i()
                                },
                                children: [null === y || void 0 === y ? void 0 : y.coinSymbol, " ", l(j ? "page.trade.buttonRow.msg009" : "page.trade.buttonRow.msg010"), (0, u.jsx)(ot, {})]
                            })
                        }), !c(_._28.hideOrderConfirm) && (0, u.jsx)(dt, {})]
                    }) : (0, u.jsx)("div", {
                        className: _t("button-row"),
                        children: (0, u.jsx)(g.ZP, {
                            type: g.PD.DefaultNew,
                            color: g.n5.Secondary,
                            size: g.VA.Large,
                            children: l("page.trade.buttonRow.msg008")
                        })
                    }) : (0, u.jsxs)(u.Fragment, {
                        children: [(0, u.jsx)("div", {
                            className: _t("button-row"),
                            children: (0, u.jsx)(g.ZP, {
                                type: g.PD.DefaultNew,
                                size: g.VA.Large,
                                className: _t("button-row__login"),
                                onClick: function() {
                                    m("\uc8fc\ubb38", "\uc77c\ubc18\uc8fc\ubb38", "\ub85c\uadf8\uc778"), window.privateParams = {
                                        buySell: n
                                    }, b("/login", {
                                        state: {
                                            from: {
                                                pathname: v
                                            },
                                            replace: !0
                                        }
                                    })
                                },
                                children: l("page.trade.buttonRow.msg007")
                            })
                        }), (0, u.jsx)(ct, {})]
                    })
                })),
                ut = JSON.parse('{"page.trade.buttonRow.msg001":"Pending","page.trade.buttonRow.msg002":"Coming Soon","page.trade.buttonRow.msg003":"Pending Buy","page.trade.buttonRow.msg004":"Pending Sell","page.trade.buttonRow.msg005":"Coming Soon","page.trade.buttonRow.msg006":"Coming Soon","page.trade.buttonRow.msg007":"Log in","page.trade.buttonRow.msg008":"\uac70\ub798\uc815\uc9c0","page.trade.buttonRow.msg009":"Buy","page.trade.buttonRow.msg010":"Sell"}'),
                pt = JSON.parse('{"page.trade.buttonRow.msg001":"\ub300\uae30","page.trade.buttonRow.msg002":"\ub300\uae30 \ubbf8\uc815","page.trade.buttonRow.msg003":"\ub9e4\uc218\ub300\uae30","page.trade.buttonRow.msg004":"\ub9e4\ub3c4\ub300\uae30","page.trade.buttonRow.msg005":"\ub9e4\uc218\ub300\uae30 \ubbf8\uc815","page.trade.buttonRow.msg006":"\ub9e4\ub3c4\ub300\uae30 \ubbf8\uc815","page.trade.buttonRow.msg007":"\ub85c\uadf8\uc778","page.trade.buttonRow.msg008":"\uac70\ub798\uc815\uc9c0","page.trade.buttonRow.msg009":"\ub9e4\uc218","page.trade.buttonRow.msg010":"\ub9e4\ub3c4"}'),
                ft = (0, r.FU)(mt, {
                    ko: pt,
                    en: ut
                }),
                ht = JSON.parse('{"pop.trade.feeInfo.msg001":"{{market}} Market Fees","pop.trade.feeInfo.msg002":"KRW","pop.trade.feeInfo.msg003":"Free","pop.trade.feeInfo.msg004":"\uc8fc\ubb38 \uccb4\uacb0 \uc774\uc804 \ucfe0\ud3f0 \uc0ac\uc6a9\uc774 \uc885\ub8cc\ub418\ub294 \uacbd\uc6b0 \uae30\ubcf8 \uc218\uc218\ub8cc ({{rate}})\uac00 \uc801\uc6a9\ub429\ub2c8\ub2e4.","pop.trade.feeInfo.msg005":"\ucfe0\ud3f0 \uc801\uc6a9","pop.trade.feeInfo.msg006":"\uba54\uc774\ucee4 (Maker)","pop.trade.feeInfo.msg007":"\ud638\uac00\uc5d0 \ub9e4\uc218/\ub9e4\ub3c4 \uc794\ub7c9\uc744 \ucd94\uac00\ud558\ub294 \uc8fc\ubb38","pop.trade.feeInfo.msg008":"\ud14c\uc774\ucee4 (Taker)","pop.trade.feeInfo.msg009":"\ud638\uac00\uc5d0 \uc313\uc778 \uc794\ub7c9\uc744 \uc989\uc2dc \uccb4\uacb0\uc2dc\ud0a4\ub294 \uc8fc\ubb38","pop.trade.feeInfo.msg010":"\uae30\ubcf8 \uc218\uc218\ub8cc : {{defaultFee}}","pop.trade.feeInfo.msg011":"\uae30\ubcf8 \uc218\uc218\ub8cc\uac00 \ucfe0\ud3f0 \uc801\uc6a9 \uc218\uc218\ub8cc\ubcf4\ub2e4 \ub0ae\uc740 \uacbd\uc6b0 \ucfe0\ud3f0\uc740 \uc0ac\uc6a9\ub418\uc9c0 \uc54a\uc2b5\ub2c8\ub2e4.","pop.trade.feeInfo.msg012":"\uc8fc\ubb38 \uccb4\uacb0 \uc774\uc804 \ucfe0\ud3f0 \uc0ac\uc6a9\uc774 \uc885\ub8cc\ub418\ub294 \uacbd\uc6b0 \uae30\ubcf8 \uc218\uc218\ub8cc\uac00 \uc801\uc6a9\ub429\ub2c8\ub2e4.","pop.trade.feeInfo.msg013":"\uc790\uc138\ud55c \ub0b4\uc6a9\uc740 {{tagButton}}\uc5ec\uae30{{/tagButton}}\ub97c \ub20c\ub7ec \ud655\uc778\ud558\uc138\uc694.","pop.trade.feeInfo.msg014":"\uba54\uc774\ucee4","pop.trade.feeInfo.msg015":"\ud14c\uc774\ucee4","pop.trade.feeInfo.msg016":"\ucfe0\ud3f0 \ub4f1\ub85d \uc2dc, \uc720\ud6a8\uae30\uac04 30\uc77c (\ub4f1\ub85d\uc77c \uae30\uc900)","pop.trade.feeInfo.msg017":"\uc218\uc218\ub8cc \ucfe0\ud3f0\uc758 \uc720\ud6a8\uae30\uac04\uc740 30\uc77c (\ub4f1\ub85d\uc77c \uae30\uc900) \uc785\ub2c8\ub2e4."}'),
                gt = JSON.parse('{"pop.trade.feeInfo.msg001":"{{market}} \ub9c8\ucf13 \uc218\uc218\ub8cc","pop.trade.feeInfo.msg002":"\uc6d0\ud654","pop.trade.feeInfo.msg003":"\ubb34\ub8cc","pop.trade.feeInfo.msg004":"\uc8fc\ubb38 \uccb4\uacb0 \uc774\uc804 \ucfe0\ud3f0 \uc0ac\uc6a9\uc774 \uc885\ub8cc\ub418\ub294 \uacbd\uc6b0 \uae30\ubcf8 \uc218\uc218\ub8cc ({{rate}})\uac00 \uc801\uc6a9\ub429\ub2c8\ub2e4.","pop.trade.feeInfo.msg005":"\ucfe0\ud3f0 \uc801\uc6a9","pop.trade.feeInfo.msg006":"\uba54\uc774\ucee4 (Maker)","pop.trade.feeInfo.msg007":"\ud638\uac00\uc5d0 \ub9e4\uc218/\ub9e4\ub3c4 \uc794\ub7c9\uc744 \ucd94\uac00\ud558\ub294 \uc8fc\ubb38","pop.trade.feeInfo.msg008":"\ud14c\uc774\ucee4 (Taker)","pop.trade.feeInfo.msg009":"\ud638\uac00\uc5d0 \uc313\uc778 \uc794\ub7c9\uc744 \uc989\uc2dc \uccb4\uacb0\uc2dc\ud0a4\ub294 \uc8fc\ubb38","pop.trade.feeInfo.msg010":"\uae30\ubcf8 \uc218\uc218\ub8cc : {{defaultFee}}","pop.trade.feeInfo.msg011":"\uae30\ubcf8 \uc218\uc218\ub8cc\uac00 \ucfe0\ud3f0 \uc801\uc6a9 \uc218\uc218\ub8cc\ubcf4\ub2e4 \ub0ae\uc740 \uacbd\uc6b0 \ucfe0\ud3f0\uc740 \uc0ac\uc6a9\ub418\uc9c0 \uc54a\uc2b5\ub2c8\ub2e4.","pop.trade.feeInfo.msg012":"\uc8fc\ubb38 \uccb4\uacb0 \uc774\uc804 \ucfe0\ud3f0 \uc0ac\uc6a9\uc774 \uc885\ub8cc\ub418\ub294 \uacbd\uc6b0 \uae30\ubcf8 \uc218\uc218\ub8cc\uac00 \uc801\uc6a9\ub429\ub2c8\ub2e4.","pop.trade.feeInfo.msg013":"\uc790\uc138\ud55c \ub0b4\uc6a9\uc740 {{tagButton}}\uc5ec\uae30{{/tagButton}}\ub97c \ub20c\ub7ec \ud655\uc778\ud558\uc138\uc694.","pop.trade.feeInfo.msg014":"\uba54\uc774\ucee4","pop.trade.feeInfo.msg015":"\ud14c\uc774\ucee4","pop.trade.feeInfo.msg016":"\ucfe0\ud3f0 \ub4f1\ub85d \uc2dc, \uc720\ud6a8\uae30\uac04 30\uc77c (\ub4f1\ub85d\uc77c \uae30\uc900)","pop.trade.feeInfo.msg017":"\uc218\uc218\ub8cc \ucfe0\ud3f0\uc758 \uc720\ud6a8\uae30\uac04\uc740 30\uc77c (\ub4f1\ub85d\uc77c \uae30\uc900) \uc785\ub2c8\ub2e4."}'),
                xt = {
                    "fee-info-display": "FeeInfoModal_fee-info-display__f601X",
                    "fee-info-display--fee-different": "FeeInfoModal_fee-info-display--fee-different__2Ye8I",
                    "fee-info-display__title-box": "FeeInfoModal_fee-info-display__title-box__tXLDN",
                    "fee-info-display__title": "FeeInfoModal_fee-info-display__title__+i-Ik",
                    "fee-info-tooltip__button": "FeeInfoModal_fee-info-tooltip__button__Hozvl",
                    "fee-info-tooltip__content": "FeeInfoModal_fee-info-tooltip__content__jcmiA",
                    "fee-info-tooltip__text": "FeeInfoModal_fee-info-tooltip__text__nGE2e",
                    "fee-info-display__info": "FeeInfoModal_fee-info-display__info__1ziyV",
                    "fee-info-display__info--coupon": "FeeInfoModal_fee-info-display__info--coupon__3jbfe",
                    "fee-info-display__info-text": "FeeInfoModal_fee-info-display__info-text__VmTnc",
                    "fee-info-display__info-text-sub": "FeeInfoModal_fee-info-display__info-text-sub__C4zkB",
                    "fee-info-display-different": "FeeInfoModal_fee-info-display-different__h6J0a",
                    "fee-info-display-different-item": "FeeInfoModal_fee-info-display-different-item__9rm2C",
                    "fee-info-display-different-item__text": "FeeInfoModal_fee-info-display-different-item__text__L8uHK",
                    "fee-info-display-different-notice": "FeeInfoModal_fee-info-display-different-notice__yZGh5",
                    "fee-info-display-different-notice__link": "FeeInfoModal_fee-info-display-different-notice__link__nvfOj",
                    "fee-info-tooltip-dl-item": "FeeInfoModal_fee-info-tooltip-dl-item__X4hGa",
                    "fee-info-tooltip-dl-item__list": "FeeInfoModal_fee-info-tooltip-dl-item__list__O7gQx",
                    "fee-info-tooltip__notice": "FeeInfoModal_fee-info-tooltip__notice__sVA33",
                    "fee-info-tooltip__notice-text": "FeeInfoModal_fee-info-tooltip__notice-text__Ta8kL",
                    "fee-info-display__info-description": "FeeInfoModal_fee-info-display__info-description__1tRNF"
                },
                bt = c().bind(xt),
                vt = (0, l.Pi)((function(e) {
                    var t = e.sTitle,
                        n = e.bIsCoupon,
                        i = e.takerFeeRate,
                        o = e.makerFeeRate,
                        a = e.tradeFeeRateTaker,
                        s = e.tradeFeeRateMaker,
                        c = (0, r.G2)().localeService.locale;
                    return (0, u.jsx)(u.Fragment, {
                        children: (0, u.jsxs)("div", {
                            className: bt("fee-info-display", "fee-info-display--fee-different"),
                            children: [(0, u.jsxs)("div", {
                                className: bt("fee-info-display__title-box"),
                                children: [(0, u.jsx)("h3", {
                                    className: bt("fee-info-display__title"),
                                    children: t
                                }), (0, u.jsx)(g.ZP, {
                                    className: bt("fee-info-tooltip__button"),
                                    children: (0, u.jsx)("span", {
                                        className: "blind",
                                        children: "\ud234\ud301"
                                    })
                                }), (0, u.jsxs)("div", {
                                    className: bt("fee-info-tooltip__content"),
                                    children: [!n && (0, u.jsxs)("dl", {
                                        className: bt("fee-info-tooltip-dl"),
                                        children: [(0, u.jsxs)("div", {
                                            className: bt("fee-info-tooltip-dl-item"),
                                            children: [(0, u.jsx)("dt", {
                                                children: c("pop.trade.feeInfo.msg006")
                                            }), (0, u.jsx)("dd", {
                                                children: c("pop.trade.feeInfo.msg007")
                                            })]
                                        }), (0, u.jsxs)("div", {
                                            className: bt("fee-info-tooltip-dl-item"),
                                            children: [(0, u.jsx)("dt", {
                                                children: c("pop.trade.feeInfo.msg008")
                                            }), (0, u.jsx)("dd", {
                                                children: c("pop.trade.feeInfo.msg009")
                                            })]
                                        })]
                                    }), n && (0, u.jsxs)(u.Fragment, {
                                        children: [(0, u.jsxs)("dl", {
                                            className: bt("fee-info-tooltip-dl"),
                                            children: [(0, u.jsxs)("div", {
                                                className: bt("fee-info-tooltip-dl-item"),
                                                children: [(0, u.jsx)("dt", {
                                                    children: c("pop.trade.feeInfo.msg006")
                                                }), (0, u.jsx)("dd", {
                                                    children: (0, u.jsxs)("ul", {
                                                        className: bt("fee-info-tooltip-dl-item__list"),
                                                        children: [(0, u.jsx)("li", {
                                                            children: c("pop.trade.feeInfo.msg007")
                                                        }), (0, u.jsx)("li", {
                                                            children: c("pop.trade.feeInfo.msg010", {
                                                                defaultFee: s
                                                            })
                                                        })]
                                                    })
                                                })]
                                            }), (0, u.jsxs)("div", {
                                                className: bt("fee-info-tooltip-dl-item"),
                                                children: [(0, u.jsx)("dt", {
                                                    children: c("pop.trade.feeInfo.msg008")
                                                }), (0, u.jsx)("dd", {
                                                    children: (0, u.jsxs)("ul", {
                                                        className: bt("fee-info-tooltip-dl-item__list"),
                                                        children: [(0, u.jsx)("li", {
                                                            children: c("pop.trade.feeInfo.msg009")
                                                        }), (0, u.jsx)("li", {
                                                            children: c("pop.trade.feeInfo.msg010", {
                                                                defaultFee: a
                                                            })
                                                        })]
                                                    })
                                                })]
                                            })]
                                        }), (0, u.jsxs)("div", {
                                            className: bt("fee-info-tooltip__notice"),
                                            children: [(0, u.jsx)("p", {
                                                className: bt("fee-info-tooltip__notice-text"),
                                                children: c("pop.trade.feeInfo.msg011")
                                            }), (0, u.jsx)("p", {
                                                className: bt("fee-info-tooltip__notice-text"),
                                                children: c("pop.trade.feeInfo.msg012")
                                            })]
                                        })]
                                    })]
                                })]
                            }), (0, u.jsxs)("div", {
                                className: bt("fee-info-display-different"),
                                children: [(0, u.jsxs)("dl", {
                                    className: bt("fee-info-display-different__list"),
                                    children: [(0, u.jsxs)("div", {
                                        className: bt("fee-info-display-different-item"),
                                        children: [(0, u.jsx)("dt", {
                                            children: c("pop.trade.feeInfo.msg014")
                                        }), (0, u.jsxs)("dd", {
                                            children: [o, n && (0, u.jsx)("span", {
                                                className: bt("fee-info-display-different-item__text"),
                                                children: c("pop.trade.feeInfo.msg005")
                                            })]
                                        })]
                                    }), (0, u.jsxs)("div", {
                                        className: bt("fee-info-display-different-item"),
                                        children: [(0, u.jsx)("dt", {
                                            children: c("pop.trade.feeInfo.msg015")
                                        }), (0, u.jsxs)("dd", {
                                            children: [i, n && (0, u.jsx)("span", {
                                                className: bt("fee-info-display-different-item__text"),
                                                children: c("pop.trade.feeInfo.msg005")
                                            })]
                                        })]
                                    })]
                                }), (0, u.jsxs)("ul", {
                                    className: bt("fee-info-display-different-notice"),
                                    children: [n && (0, u.jsx)("li", {
                                        children: c("pop.trade.feeInfo.msg017")
                                    }), (0, u.jsx)("li", {
                                        children: c("pop.trade.feeInfo.msg013", {
                                            tagButton: (0, u.jsx)(g.ZP, {
                                                className: bt("fee-info-display-different-notice__link"),
                                                href: "https://www.bithumb.com/customer_support/info_guide?seq=4803&categorySeq=611",
                                                target: "_self"
                                            })
                                        })
                                    })]
                                })]
                            })]
                        })
                    })
                })),
                yt = c().bind(xt),
                jt = (0, l.Pi)((function(e) {
                    var t = e.sTitle,
                        n = e.bIsCoupon,
                        i = e.rate,
                        o = e.tradeFeeRate,
                        a = (0, r.G2)().localeService.locale;
                    return (0, u.jsx)(u.Fragment, {
                        children: (0, u.jsxs)("div", {
                            className: yt("fee-info-display"),
                            children: [(0, u.jsxs)("div", {
                                className: yt("fee-info-display__title-box"),
                                children: [(0, u.jsx)("h3", {
                                    className: yt("fee-info-display__title"),
                                    children: t
                                }), n && (0, u.jsxs)(u.Fragment, {
                                    children: [(0, u.jsx)(g.ZP, {
                                        className: yt("fee-info-tooltip__button"),
                                        children: (0, u.jsx)("span", {
                                            className: "blind",
                                            children: "\ud234\ud301"
                                        })
                                    }), (0, u.jsx)("div", {
                                        className: yt("fee-info-tooltip__content"),
                                        children: (0, u.jsx)("p", {
                                            className: yt("fee-info-tooltip__text"),
                                            children: a("pop.trade.feeInfo.msg004", {
                                                rate: o
                                            })
                                        })
                                    })]
                                })]
                            }), (0, u.jsxs)("div", {
                                className: yt("fee-info-display__info", n && "fee-info-display__info--coupon"),
                                children: [n && (0, u.jsx)("p", {
                                    className: yt("fee-info-display__info-text-sub"),
                                    children: a("pop.trade.feeInfo.msg005")
                                }), (0, u.jsx)("p", {
                                    className: yt("fee-info-display__info-text"),
                                    children: i
                                }), n && (0, u.jsx)("p", {
                                    className: yt("fee-info-display__info-description"),
                                    children: a("pop.trade.feeInfo.msg016")
                                })]
                            })]
                        })
                    })
                })),
                Nt = c().bind(xt),
                kt = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.localeService.locale,
                        n = e.modalService,
                        i = n.visible,
                        o = n.hideModal,
                        s = e.coinService.getMarket,
                        c = s.crncCd,
                        l = s.marketSymbol,
                        d = e.service,
                        _ = d.buySell,
                        m = d.tradeInfo,
                        p = m.hasCoupon,
                        f = m.tradeFeeRateTaker,
                        h = m.tradeFeeRateMaker,
                        x = m.makerFeeRate,
                        b = m.takerFeeRate,
                        v = (0, S.PZ)().getInstance,
                        y = "Y" === p,
                        j = (0, a.useMemo)((function() {
                            return v(f).isEqualTo(v(h))
                        }), [f, h]),
                        N = (0, a.useMemo)((function() {
                            var e = c === C.Eo;
                            return t("pop.trade.feeInfo.msg001", {
                                market: e ? t("pop.trade.feeInfo.msg002") : l
                            })
                        }), [c]),
                        k = function(e) {
                            var n = v(e);
                            return n.isZero() ? t("pop.trade.feeInfo.msg003") : "".concat(n.multipliedBy(100).toFixed(), "%")
                        };
                    return (0, u.jsxs)(ee.ZP, {
                        visible: i(ne.hS8),
                        type: ee.y7.Default,
                        className: Nt("fee-info-modal"),
                        hideButton: !0,
                        closeByBg: !0,
                        children: [(0, u.jsxs)("div", {
                            className: Nt("fee-info"),
                            children: [j && (0, u.jsx)(jt, {
                                sTitle: N,
                                bIsCoupon: y,
                                rate: k(_ ? x : b),
                                tradeFeeRate: k(_ ? h : f)
                            }), !j && (0, u.jsx)(vt, {
                                sTitle: N,
                                bIsCoupon: y,
                                takerFeeRate: k(b),
                                makerFeeRate: k(x),
                                tradeFeeRateTaker: k(f),
                                tradeFeeRateMaker: k(h)
                            })]
                        }), (0, u.jsx)(te.XY, {
                            modalBtn: {
                                text: t("button.msg11"),
                                feature: te.p0.CLOSE,
                                customStyle: {
                                    type: g.PD.DefaultNew,
                                    color: g.n5.Primary,
                                    size: g.VA.Large
                                }
                            },
                            onClose: function() {
                                return o(ne.hS8)
                            }
                        })]
                    })
                })),
                Ct = (0, r.FU)(kt, {
                    ko: gt,
                    en: ht
                }),
                St = c().bind({
                    "fee-row": "FeeRow_fee-row__eHg-N",
                    "fee-row__link": "FeeRow_fee-row__link__1ZdwN",
                    "fee-row__used": "FeeRow_fee-row__used__S3jw2",
                    "fee-row-tip": "FeeRow_fee-row-tip__vU-2s",
                    "fee-row-tip__text": "FeeRow_fee-row-tip__text__5OIGZ",
                    "fee-row-tip__button": "FeeRow_fee-row-tip__button__bzGrq",
                    "fee-info": "FeeRow_fee-info__k3mG+",
                    "fee-info__col": "FeeRow_fee-info__col__knLFX",
                    "fee-info__title": "FeeRow_fee-info__title__J4Vug",
                    "fee-info__text": "FeeRow_fee-info__text__io3X9"
                }),
                wt = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.service.tradeInfo,
                        n = t.tradeFeeRateTaker,
                        i = t.tradeFeeRateMaker,
                        o = e.modalService.showModal,
                        a = e.localeService.locale;
                    return (0, u.jsxs)(u.Fragment, {
                        children: [0 === Number(n) && 0 === Number(i) ? (0, u.jsx)("div", {
                            className: St("fee-row"),
                            children: (0, u.jsx)("p", {
                                className: St("fee-row__used"),
                                children: a("page.trade.msg161")
                            })
                        }) : (0, u.jsx)("div", {
                            className: St("fee-row"),
                            children: (0, u.jsxs)("div", {
                                className: St("fee-row-tip"),
                                children: [(0, u.jsx)("strong", {
                                    className: St("fee-row-tip__text"),
                                    children: a("page.trade.msg160")
                                }), (0, u.jsx)(g.ZP, {
                                    className: St("fee-row-tip__button"),
                                    onClick: function() {
                                        return o(ne.hS8)
                                    },
                                    children: (0, u.jsx)("span", {
                                        className: St("blind"),
                                        children: "TIP"
                                    })
                                })]
                            })
                        }), (0, u.jsx)(Ct, {})]
                    })
                })),
                Tt = n(41833),
                Pt = c().bind({
                    "lending-application-layer": "LendingApplicationLayer_lending-application-layer__jVGxw",
                    "lending-application-layer__close": "LendingApplicationLayer_lending-application-layer__close__55xkR"
                }),
                It = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.coinService.getCoin,
                        n = e.modalService,
                        i = n.visible,
                        o = n.hideModal,
                        a = e.service.buySell,
                        s = (0, S.D9)(a, a);
                    return i(ne.Owu) && s === a ? (0, u.jsxs)("div", {
                        className: Pt("lending-application-layer"),
                        children: [(0, u.jsx)(Tt.default, {
                            layer: !0,
                            symbol: t.coinSymbol,
                            buySell: a
                        }), (0, u.jsx)(g.ZP, {
                            className: Pt("lending-application-layer__close"),
                            onClick: function() {
                                return o(ne.Owu)
                            },
                            children: (0, u.jsx)("span", {
                                className: "blind",
                                children: "\ub2eb\uae30"
                            })
                        })]
                    }) : null
                })),
                Dt = c().bind({
                    "lending-button": "Lending_lending-button__qiuTh",
                    "lending-button--up": "Lending_lending-button--up__fQQTb"
                }),
                Ot = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.coinService,
                        n = t.getCoin,
                        i = t.getMarket,
                        s = e.sessionService.login,
                        c = e.localeService.locale,
                        l = e.service,
                        _ = l.buySell,
                        m = l.setLendingList,
                        p = e.lendingService.getAjaxLendingAvailableList,
                        f = e.modalService,
                        h = f.showModal,
                        x = f.hideModal,
                        b = (0, a.useState)([]),
                        v = (0, o.Z)(b, 2),
                        y = v[0],
                        j = v[1],
                        N = (0, a.useState)(!1),
                        k = (0, o.Z)(N, 2),
                        S = k[0],
                        w = k[1],
                        T = (0, d.s0)(),
                        P = (0, d.TH)().pathname,
                        I = (0, a.useCallback)((function() {
                            s ? h(ne.Owu, null, !0) : (window.privateParams = {
                                buySell: _
                            }, T("/login", {
                                state: {
                                    from: {
                                        pathname: P
                                    },
                                    replace: !0
                                }
                            }))
                        }), [_, s]);
                    return (0, a.useEffect)((function() {
                        p((function(e) {
                            m(e), j(e)
                        }))
                    }), [i.crncCd]), (0, a.useEffect)((function() {
                        var e;
                        w(!!(y.length && y.findIndex((function(e) {
                            return e.coinType === n.coinType
                        })) > -1 && i.crncCd === C.Eo && null !== (e = n.infoOnMarket) && void 0 !== e && e.canTrade))
                    }), [y, n.coinType, i.crncCd]), (0, a.useEffect)((function() {
                        x(ne.Owu)
                    }), [_, n.coinType, i.crncCd]), S ? (0, u.jsxs)(u.Fragment, {
                        children: [(0, u.jsx)(g.ZP, {
                            className: Dt("lending-button", {
                                "lending-button--up": 0 === _
                            }),
                            onClick: I,
                            children: c(0 === _ ? "page.trade.msg158" : "page.trade.msg159")
                        }), (0, u.jsx)(It, {})]
                    }) : null
                })),
                Zt = {
                    "order-form": "OrderForm_order-form__m+LQ-",
                    "order-form__row": "OrderForm_order-form__row__1IOJV",
                    "order-form__row--sub": "OrderForm_order-form__row--sub__d4-LQ",
                    "order-form__row--select": "OrderForm_order-form__row--select__wGoBZ",
                    "order-form__title": "OrderForm_order-form__title__iLzMw",
                    "order-form__title--top": "OrderForm_order-form__title--top__wGxMo",
                    "order-form__box": "OrderForm_order-form__box__56niQ",
                    "order-form__line": "OrderForm_order-form__line__bmPJF",
                    "order-form__input--left": "OrderForm_order-form__input--left__HtG5G",
                    "order-form__select": "OrderForm_order-form__select__SCvuz",
                    "order-form__select-amount": "OrderForm_order-form__select-amount__qK9m+",
                    "order-form__max-order": "OrderForm_order-form__max-order__N+hrE",
                    "order-form-amount-trans": "OrderForm_order-form-amount-trans__Wnbqw",
                    "order-form-amount-trans__text": "OrderForm_order-form-amount-trans__text__nG3Hy",
                    "order-form-simple-result": "OrderForm_order-form-simple-result__OUhd9",
                    "order-form-simple-result__unit": "OrderForm_order-form-simple-result__unit__xrpM+",
                    "order-form-simple-result__trans": "OrderForm_order-form-simple-result__trans__1Ta4T",
                    "order-form-qty-per": "OrderForm_order-form-qty-per__3eR94",
                    "order-form-qty-per__button": "OrderForm_order-form-qty-per__button__MYSqC",
                    "order-form-qty-per--range": "OrderForm_order-form-qty-per--range__wu9IG",
                    "order-form-qty-per__range": "OrderForm_order-form-qty-per__range__jKs0c",
                    "order-form-qty-per__info": "OrderForm_order-form-qty-per__info__T-OVe",
                    "order-form-qty-per__percent": "OrderForm_order-form-qty-per__percent__rG0vl",
                    "order-form-qty-per__max-button": "OrderForm_order-form-qty-per__max-button__cjiY7",
                    "order-form-radio": "OrderForm_order-form-radio__6jAkk",
                    "order-form-radio__inner": "OrderForm_order-form-radio__inner__gwYWF",
                    "order-form-radio__label": "OrderForm_order-form-radio__label__d6jca",
                    "order-form-radio__icon": "OrderForm_order-form-radio__icon__wF4ix",
                    "order-form-tip": "OrderForm_order-form-tip__KRRxh",
                    "order-form-tip__button": "OrderForm_order-form-tip__button__aojfk",
                    "order-form-tip-layer": "OrderForm_order-form-tip-layer__Hor0U",
                    "order-form-tip-layer__title": "OrderForm_order-form-tip-layer__title__M2GdE",
                    "order-form-tip-layer__text": "OrderForm_order-form-tip-layer__text__FDJrI"
                },
                At = n(12955),
                Ft = c().bind(Zt),
                Mt = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.localeService.locale,
                        n = e.service,
                        i = n.orderType,
                        s = n.buySell,
                        c = n.buyInfo,
                        l = n.sellInfo,
                        d = n.setBuySellData,
                        m = e.coinService,
                        p = m.getCoin,
                        f = m.getMarket,
                        h = m.fnToKrw,
                        g = m.subscribeTicker,
                        x = m.getIntroTradeInfo,
                        b = e.sessionService.language,
                        v = e.developService.fnGetDataId,
                        y = (0, a.useRef)(v()),
                        j = (0, S.PZ)().getInstance,
                        N = (0, a.useRef)({
                            inputFocus: !1
                        }),
                        k = (0, a.useState)(""),
                        w = (0, o.Z)(k, 2),
                        T = w[0],
                        P = w[1],
                        I = (0, a.useState)(""),
                        D = (0, o.Z)(I, 2),
                        O = D[0],
                        Z = D[1],
                        A = (0, a.useMemo)((function() {
                            return x(p.coinType, f.crncCd)
                        }), [p.coinType, f.crncCd]),
                        F = A.limitMaxOrderValue,
                        M = A.limitMinOrderValue,
                        R = A.marketMinOrderValue,
                        B = A.marketMaxOrderValue,
                        L = A.orderAmtDecimalPlace,
                        E = (0, a.useMemo)((function() {
                            return 0 === s ? c.amt : l.amt
                        }), [s, c.amt, l.amt]),
                        U = (0, a.useMemo)((function() {
                            return j(1 === i ? R : M).toFormat()
                        }), [i, M, R]),
                        q = (0, a.useCallback)((function(e, t) {
                            var n = e;
                            switch (t) {
                                case _.e9D.onBlur:
                                    var r = j(e);
                                    if (e && !r.isZero()) {
                                        if (1 === i && r.isLessThan(R)) return R;
                                        if (1 !== i && r.isLessThan(M)) return M
                                    }
                                    break;
                                case _.e9D.onChange:
                                case _.e9D.onInput:
                                default:
                                    return n
                            }
                            return n
                        }), [b, M, R]),
                        G = (0, a.useCallback)((function(e) {
                            N.current.inputFocus ? (P(e), d({
                                amt: q(e, _.e9D.onInput)
                            })) : d({
                                amt: q(e, _.e9D.onChange)
                            }, !0)
                        }), [s]),
                        H = (0, a.useCallback)((function() {
                            N.current.inputFocus = !1;
                            var e = q(T, _.e9D.onBlur);
                            T === E && j(E).isGreaterThan(e) || d({
                                amt: e
                            })
                        }), [s, c.amt, l.amt, T]),
                        Q = (0, a.useCallback)((function() {
                            N.current.inputFocus = !0, P(E)
                        }), [E]),
                        K = (0, a.useCallback)((function() {
                            if (f.crncCd !== C.Eo) {
                                var e = 0 === s ? c : l,
                                    t = j(e.price).multipliedBy(e.qty).toFixed();
                                Z(h(p.coinType, f.crncCd, t))
                            } else Z("")
                        }), [b, p.coinType, f.crncCd, s, c.amt, l.amt]);
                    return (0, a.useEffect)((function() {
                        var e;
                        return K(), f.crncCd !== C.Eo && (e = g(f.crncCd, C.Eo, K, y.current)),
                            function() {
                                e && e()
                            }
                    }), [p.coinType, f.crncCd, K]), (0, u.jsxs)(u.Fragment, {
                        children: [(0, u.jsxs)("div", (0, pe.Z)((0, pe.Z)({
                            className: Ft("order-form__row")
                        }, y.current), {}, {
                            children: [(0, u.jsx)("h4", {
                                className: Ft("order-form__title"),
                                children: t("page.trade.msg135")
                            }), (0, u.jsx)("div", {
                                className: Ft("order-form__box"),
                                children: (0, u.jsx)(At.ZP, {
                                    type: At.nc.Number,
                                    className: Ft(1 !== i ? "" : "order-form__input--left"),
                                    validate: f.crncCd === C.Eo ? At.zZ.Price : At.zZ.PriceFloat,
                                    renderType: At.Ve.BuySell,
                                    placeholder: 1 === i ? t("page.trade.msg196", {
                                        price: U,
                                        market: (0, C.c0)(f.marketSymbol, b)
                                    }) : "".concat(t("page.trade.msg137"), " ").concat(U),
                                    subLabel: 1 !== i && (0, C.c0)(f.marketSymbol, b),
                                    maxDecimal: Number(L),
                                    maxLength: j(1 === i ? B : F).toFixed(0).length,
                                    value: N.current.inputFocus ? T : E,
                                    onChange: G,
                                    onBlur: H,
                                    onFocus: Q
                                })
                            })]
                        })), 1 !== i && O && (0, u.jsx)("div", {
                            className: Ft("order-form-amount-trans"),
                            children: (0, u.jsx)("p", {
                                className: Ft("order-form-amount-trans__text"),
                                children: "\u2248 ".concat(O, " ").concat(t("page.trade.msg136"))
                            })
                        })]
                    })
                })),
                Rt = (0, l.Pi)((function() {
                    var e, t = (0, r.G2)(),
                        n = t.service,
                        i = n.sellInfo,
                        s = n.fnCalcAmt,
                        c = t.assetService.getCoinAsset,
                        l = t.coinService,
                        d = l.getCoin,
                        _ = l.getMarket,
                        m = (0, S.PZ)().getInstance,
                        p = (0, a.useState)(m(0).toFormat(_.crncCd === C.Eo ? 0 : 8)),
                        f = (0, o.Z)(p, 2),
                        h = f[0],
                        g = f[1];
                    return (0, a.useEffect)((function() {
                        var e, t = m(null === (e = c(d.coinType)) || void 0 === e ? void 0 : e.available),
                            n = s(i.price, t.toFixed()).amt,
                            r = m(n);
                        g(r.toFormat(_.crncCd === C.Eo ? 0 : 8))
                    }), [i.price, d.coinType, _.crncCd, null === (e = c(d.coinType)) || void 0 === e ? void 0 : e.available]), (0, u.jsx)(u.Fragment, {
                        children: h
                    })
                })),
                Bt = (0, l.Pi)((function() {
                    var e, t = (0, r.G2)(),
                        n = t.service,
                        i = n.buyInfo,
                        s = n.fnCalcPer,
                        c = n.tradeInfo,
                        l = t.assetService.getCoinAsset,
                        d = t.coinService,
                        _ = d.getCoin,
                        m = d.getMarket,
                        p = d.getIntroTradeInfo,
                        f = (0, S.PZ)().getInstance,
                        h = (0, a.useState)("0"),
                        g = (0, o.Z)(h, 2),
                        x = g[0],
                        b = g[1],
                        v = p(_.coinType, m.crncCd).orderQtyUnit;
                    return (0, a.useEffect)((function() {
                        var e = s("100", i.price, !0).qty,
                            t = new($())(e || 0);
                        b(t.isZero() ? "0" : t.toFormat(8, $().ROUND_DOWN))
                    }), [i.price, m.crncCd, null === (e = l(m.crncCd)) || void 0 === e ? void 0 : e.available, c]), (0, u.jsx)(u.Fragment, {
                        children: f(x).toFormat((0, C.JQ)(v))
                    })
                })),
                Lt = c().bind(Zt),
                Et = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.service.buySell,
                        n = e.localeService.locale;
                    return (0, u.jsx)("div", {
                        className: Lt("order-form__row", "order-form__row--sub"),
                        children: (0, u.jsxs)("dl", {
                            className: Lt("order-form__max-order"),
                            children: [(0, u.jsx)("dt", {
                                children: n(0 === t ? "page.trade.msg130" : "page.trade.msg131")
                            }), (0, u.jsx)("dd", {
                                children: 0 === t ? (0, u.jsx)(Bt, {}) : (0, u.jsx)(Rt, {})
                            })]
                        })
                    })
                })),
                Ut = n(79755),
                qt = c().bind(Zt),
                Gt = (at = {}, (0, je.Z)(at, C.Eo, {
                    maxLength: 9
                }), (0, je.Z)(at, C.B4, {
                    maxLength: 2
                }), (0, je.Z)(at, C.NE, {
                    maxLength: 7
                }), at),
                Ht = (0, l.Pi)((function(e) {
                    var t = e.wchUprc,
                        n = e.prc,
                        i = (0, r.G2)(),
                        s = i.localeService.locale,
                        c = i.modalService.showModal,
                        l = i.sessionService.language,
                        d = i.coinService,
                        m = d.getCoin,
                        p = d.getMarket,
                        f = d.subscribeTicker,
                        h = d.getClosePriceInfo,
                        g = d.getTicker,
                        x = d.getIntroUnitPrice,
                        b = i.service,
                        v = b.tradeInfo,
                        y = b.buySell,
                        j = b.buyInfo,
                        N = b.sellInfo,
                        k = b.needCheckPrice,
                        w = b.firstOrderPrice,
                        T = b.setNeedCheckPrice,
                        P = b.setBuySellData,
                        I = b.fnCalcMinMax,
                        D = b.fnPriceUnitFixed,
                        O = i.developService.fnGetDataId,
                        Z = (0, a.useRef)(O()),
                        A = (0, S.PZ)().getInstance,
                        F = (0, a.useRef)({
                            inputFocus: !1,
                            isNeedCheckPrice: k
                        }),
                        M = (0, a.useState)(""),
                        R = (0, o.Z)(M, 2),
                        B = R[0],
                        L = R[1],
                        E = (0, a.useRef)({
                            min: "0",
                            max: "999999999",
                            init: !0
                        }),
                        U = (0, a.useRef)(!1),
                        q = 0 === y,
                        G = (0, a.useMemo)((function() {
                            var e = q ? j.price : N.price;
                            return t && (e = q ? j.wchUprc : N.wchUprc), F.current.isNeedCheckPrice = k, e
                        }), [y, j.wchUprc, N.wchUprc, j.price, N.price]),
                        H = (0, a.useCallback)((function() {
                            var e, t, i;
                            n ? e = q ? j.wchUprc : N.wchUprc : e = null !== (t = null === (i = g()) || void 0 === i ? void 0 : i.visibleClosePrice) && void 0 !== t ? t : "0";
                            if (!E.current.init || Number(e)) {
                                var r = I(e, m.coinType, p.crncCd, !0),
                                    o = r.min,
                                    a = r.max;
                                E.current = {
                                    min: o,
                                    max: a
                                }
                            } else E.current = {
                                min: "0",
                                max: "999999999"
                            }
                        }), [m.coinType, p.crncCd, y, j.wchUprc, N.wchUprc]),
                        Q = (0, a.useCallback)((function(e, n) {
                            var i = e,
                                r = A(i),
                                o = A(E.current.min),
                                a = A(E.current.max);
                            switch (n) {
                                case _.e9D.onBlur:
                                case _.e9D.onChange:
                                    r.isGreaterThan(a) ? (i = a.toFixed(x(a.toFixed(), m.coinType, p.crncCd).lang), U.current || (U.current = !0, c(ne.DzK, {
                                        message: s(t ? "page.trade.msg189" : "page.trade.msg183", {
                                            num: a.toFormat(x(a.toFixed(), m.coinType, p.crncCd).lang),
                                            type: (0, C.c0)(p.marketSymbol)
                                        }),
                                        closeCb: function() {
                                            U.current = !1
                                        }
                                    }))) : r.isLessThan(o) ? (i = o.toFixed(x(o.toFixed(), m.coinType, p.crncCd).lang), U.current || (U.current = !0, c(ne.DzK, {
                                        message: s(t ? "page.trade.msg188" : "page.trade.msg182", {
                                            num: o.toFormat(x(o.toFixed(), m.coinType, p.crncCd).lang),
                                            type: (0, C.c0)(p.marketSymbol)
                                        }),
                                        closeCb: function() {
                                            U.current = !1
                                        }
                                    }))) : i = r.toFixed(x(r.toFixed(), m.coinType, p.crncCd).lang)
                            }
                            return i && "0" !== i ? i.replace(/[,]/g, "") : "0"
                        }), [l, p.crncCd]),
                        K = (0, a.useCallback)((function(e, n) {
                            if (F.current.inputFocus) {
                                var i = e;
                                if (!i) {
                                    var r = w[q ? "ask" : "bid"];
                                    i = r || (0, C.uR)(h(m.coinType, p.crncCd).visiblePrice)
                                }
                                var o = (0, je.Z)({}, t ? "wchUprc" : "price", i);
                                L(e), P(o)
                            } else if (n) {
                                var a = D(Q(e, _.e9D.onChange)),
                                    s = (0, je.Z)({}, t ? "wchUprc" : "price", A(a).isZero() ? "0" : a);
                                P(s)
                            } else if (F.current.isNeedCheckPrice) {
                                var c, l = x(e, m.coinType, p.crncCd).unitPrice,
                                    d = A(e),
                                    u = d.modulo(l),
                                    f = d.minus(u),
                                    g = A(x(u.toFixed(), m.coinType, p.crncCd).unitPrice).minus(l);
                                c = g.isGreaterThan(0) ? f.plus(g) : f, T(!1);
                                var b = D(Q(c.toFixed(), _.e9D.onChange)),
                                    v = (0, je.Z)({}, t ? "wchUprc" : "price", A(b).isZero() ? "0" : b);
                                P(v)
                            }
                        }), [w, m.coinType, p.crncCd, q, t]),
                        Y = (0, a.useCallback)((function() {
                            F.current.inputFocus = !1;
                            var e = (0, je.Z)({}, t ? "wchUprc" : "price", Q(t ? q ? j.wchUprc : N.wchUprc : q ? j.price : N.price, _.e9D.onBlur));
                            P(e)
                        }), [q, j.wchUprc, N.wchUprc, j.price, N.price, p.crncCd]),
                        V = (0, a.useCallback)((function() {
                            F.current.inputFocus = !0, L(G)
                        }), [G]);
                    return (0, a.useEffect)((function() {
                        var e;
                        return n || (e = f(m.coinType, p.crncCd, H, Z.current)),
                            function() {
                                e && e(), F.current.isNeedCheckPrice = !1
                            }
                    }), [m.coinType, p.crncCd]), (0, a.useEffect)((function() {
                        n || (E.current = (0, pe.Z)((0, pe.Z)({}, E.current), {}, {
                            init: !0
                        }), H())
                    }), [v, m.coinType, p.crncCd]), (0, a.useEffect)((function() {
                        n && H()
                    }), [v, j.wchUprc, N.wchUprc]), (0, u.jsxs)("div", (0, pe.Z)((0, pe.Z)({
                        className: qt("order-form__row")
                    }, Z.current), {}, {
                        children: [(0, u.jsx)("h4", {
                            className: qt("order-form__title"),
                            children: s(t ? "page.trade.msg140" : "page.trade.msg129")
                        }), (0, u.jsx)("div", {
                            className: qt("order-form__box"),
                            children: (0, u.jsx)(Ut.ZP, {
                                type: "price",
                                inputTitle: s(t ? "page.trade.msg140" : "page.trade.msg129"),
                                renderType: Ut.Ve.BuySell,
                                coinType: m.coinType,
                                crncCd: p.crncCd,
                                theme: Ut.Qk.BuySell,
                                validate: -1 === x(F.current.inputFocus ? B : G, m.coinType, p.crncCd).unitPrice.indexOf(".") ? Ut.zZ.Price : Ut.zZ.PriceFloat,
                                unitPrice: F.current.inputFocus ? B : G,
                                value: F.current.inputFocus ? B : G,
                                onChange: K,
                                onBlur: Y,
                                onFocus: V,
                                maxLength: Gt[p.crncCd].maxLength,
                                getIntroUnitPrice: x
                            })
                        })]
                    }))
                })),
                Qt = c().bind(Zt),
                Kt = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.service,
                        n = t.buySell,
                        i = t.buyInfo,
                        o = t.sellInfo,
                        s = t.orderType,
                        c = t.setBuySellData,
                        l = e.gaService.fnGASendEvent,
                        d = e.localeService.locale,
                        _ = (0, a.useCallback)((function(e) {
                            l("\uc8fc\ubb38", "\uc77c\ubc18\uc8fc\ubb38", "\ube44\uc728\uc120\ud0dd", null, {
                                ep_button_detail: "".concat(e, "%")
                            }), c({
                                per: e
                            })
                        }), [n, s, i.per, o.per]);
                    return (0, u.jsxs)("div", {
                        className: Qt("order-form-qty-per"),
                        children: [(0, u.jsxs)(g.ZP, {
                            className: Qt("order-form-qty-per__button"),
                            onClick: function() {
                                return _("10")
                            },
                            children: ["10", d("page.trade.msg265")]
                        }), (0, u.jsxs)(g.ZP, {
                            className: Qt("order-form-qty-per__button"),
                            onClick: function() {
                                return _("25")
                            },
                            children: ["25", d("page.trade.msg265")]
                        }), (0, u.jsxs)(g.ZP, {
                            className: Qt("order-form-qty-per__button"),
                            onClick: function() {
                                return _("50")
                            },
                            children: ["50", d("page.trade.msg265")]
                        }), (0, u.jsxs)(g.ZP, {
                            className: Qt("order-form-qty-per__button"),
                            onClick: function() {
                                return _("75")
                            },
                            children: ["75", d("page.trade.msg265")]
                        }), (0, u.jsx)(g.ZP, {
                            className: Qt("order-form-qty-per__button"),
                            onClick: function() {
                                return _("100")
                            },
                            children: d("page.trade.msg277")
                        })]
                    })
                })),
                Yt = c().bind(Zt),
                Vt = (0, l.Pi)((function() {
                    var e, t = (0, r.G2)(),
                        n = t.service,
                        i = n.orderType,
                        s = n.buySell,
                        c = n.setBuySellData,
                        l = n.buyInfo,
                        d = n.sellInfo,
                        m = n.qtyMinMax,
                        p = t.coinService,
                        f = p.getCoin,
                        h = p.getMarket,
                        g = p.getIntroTradeInfo,
                        x = t.localeService.locale,
                        b = (0, S.PZ)().getInstance,
                        v = (0, a.useRef)({
                            inputFocus: !1
                        }),
                        y = (0, a.useState)(""),
                        j = (0, o.Z)(y, 2),
                        N = j[0],
                        k = j[1],
                        w = 0 === s,
                        T = (0, a.useMemo)((function() {
                            return w ? l.qty : d.qty
                        }), [s, l.qty, d.qty]),
                        P = function(e, t) {
                            var n = e;
                            switch (t) {
                                case _.e9D.onBlur:
                                    var r = b(e),
                                        o = m.minQty,
                                        a = m.sminQty;
                                    if (e && r.isGreaterThan(0)) {
                                        if (1 === i && r.isLessThan(a)) return a;
                                        if (1 !== i && r.isLessThan(o)) return o
                                    }
                                    return n;
                                case _.e9D.onInput:
                                default:
                                    return n
                            }
                        },
                        I = (0, a.useCallback)((function(e) {
                            var t = P(e, _.e9D.onInput);
                            v.current.inputFocus && k(t), c({
                                qty: t
                            }, !v.current.inputFocus)
                        }), [s]),
                        D = (0, a.useCallback)((function() {
                            v.current.inputFocus = !1;
                            var e = P(w ? l.qty : d.qty, _.e9D.onBlur);
                            c({
                                qty: b(e).toFixed()
                            })
                        }), [s, l, d]),
                        O = (0, a.useCallback)((function() {
                            v.current.inputFocus = !0, k(T)
                        }), [T]),
                        Z = g(f.coinType, h.crncCd).orderQtyUnit,
                        A = null !== (e = (0, C.JQ)(Z)) && void 0 !== e ? e : 8;
                    return (0, u.jsxs)("div", {
                        className: Yt("order-form__row"),
                        children: [(0, u.jsx)("h4", {
                            className: Yt("order-form__title"),
                            children: x("page.trade.msg132")
                        }), (0, u.jsx)("div", {
                            className: Yt("order-form__box"),
                            children: (0, u.jsx)(At.ZP, {
                                type: At.nc.Number,
                                className: Yt(1 !== i ? "" : "order-form__input--left"),
                                validate: At.zZ.PriceFloat,
                                maxDecimal: A,
                                maxLength: 15,
                                renderType: At.Ve.BuySell,
                                placeholder: 1 === i ? x("page.trade.msg195", {
                                    qty: b(m.sminQty).toFormat(),
                                    coin: f.coinSymbol
                                }) : "".concat(x("page.trade.msg133"), " ").concat(b(m.minQty).toFormat()),
                                subLabel: 1 !== i && f.coinSymbol,
                                value: v.current.inputFocus ? N : T,
                                onChange: I,
                                onBlur: D,
                                onFocus: O
                            })
                        })]
                    })
                })),
                Wt = c().bind(Zt),
                zt = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.service,
                        n = t.buySell,
                        i = t.buyInfo,
                        s = t.sellInfo,
                        c = t.orderType,
                        l = e.coinService,
                        d = l.getCoin,
                        _ = l.getMarket,
                        m = l.fnToKrw,
                        p = l.subscribeTicker,
                        f = e.localeService.locale,
                        h = e.sessionService.language,
                        g = e.developService.fnGetDataId,
                        x = (0, a.useRef)(g()),
                        b = 0 === n,
                        v = _.crncCd === C.Eo,
                        y = b ? i : s,
                        j = (0, a.useState)(""),
                        N = (0, o.Z)(j, 2),
                        k = N[0],
                        S = N[1],
                        w = (0, a.useCallback)((function() {
                            1 === n && S(v ? "" : m(d.coinType, _.crncCd, s.amt))
                        }), [h, d.coinType, _.crncCd, n, s.amt]);
                    return (0, a.useEffect)((function() {
                        var e;
                        return w(), v || (e = p(_.crncCd, C.Eo, w, x.current)),
                            function() {
                                e && e()
                            }
                    }), [d.coinType, _.crncCd, w]), (0, u.jsx)("div", (0, pe.Z)((0, pe.Z)({
                        className: Wt("order-form__row")
                    }, x.current), {}, {
                        children: b ? (0, u.jsxs)(u.Fragment, {
                            children: [(0, u.jsx)("h4", {
                                className: Wt("order-form__title"),
                                children: f("page.trade.msg138")
                            }), (0, u.jsx)("div", {
                                className: Wt("order-form__box"),
                                children: (0, u.jsxs)("p", {
                                    className: Wt("order-form-simple-result"),
                                    children: [(0, u.jsx)("strong", {
                                        children: "" === y.qty || "0" === y.qty ? "0" : new($())(y.qty).toFormat(8, $().ROUND_DOWN)
                                    }), (0, u.jsx)("span", {
                                        className: Wt("order-form-simple-result__unit"),
                                        children: (0, C.c0)(d.coinSymbol)
                                    })]
                                })
                            })]
                        }) : (0, u.jsxs)(u.Fragment, {
                            children: [(0, u.jsx)("h4", {
                                className: Wt("order-form__title", "order-form__title--top"),
                                children: f("page.trade.msg139")
                            }), (0, u.jsx)("div", {
                                className: Wt("order-form__box"),
                                children: (0, u.jsxs)("p", {
                                    className: Wt("order-form-simple-result"),
                                    children: [(0, u.jsx)("strong", {
                                        children: "" === y.amt || "0" === y.amt ? "0" : new($())(y.amt).toFormat(v ? 0 : 8, $().ROUND_DOWN)
                                    }), (0, u.jsx)("span", {
                                        className: Wt("order-form-simple-result__unit"),
                                        children: v ? (0, C.c0)(_.marketSymbol, h) : _.marketSymbol
                                    }), 1 === c && k && (0, u.jsx)("span", {
                                        className: Wt("order-form-simple-result__trans"),
                                        children: "".concat(k, " ").concat(f("page.trade.msg136"))
                                    })]
                                })
                            })]
                        })
                    }))
                })),
                Xt = c().bind(Zt),
                Jt = (0, l.Pi)((function(e) {
                    var t = e.avgBuyAmt,
                        n = (0, r.G2)(),
                        i = n.service,
                        s = i.buySell,
                        c = i.firstOrderPrice,
                        l = i.setBuySellData,
                        d = i.fnCalcPricePer,
                        _ = n.localeService.locale,
                        m = n.modalService.showModal,
                        p = (0, S.PZ)().getInstance,
                        f = (0, a.useState)(!1),
                        h = (0, o.Z)(f, 2),
                        g = h[0],
                        x = h[1],
                        b = (0, a.useState)("0"),
                        v = (0, o.Z)(b, 2),
                        y = v[0],
                        j = v[1],
                        N = (0, a.useState)("0"),
                        k = (0, o.Z)(N, 2),
                        C = k[0],
                        w = k[1],
                        T = (0, a.useMemo)((function() {
                            return p(t)
                        }), [t]),
                        P = (0, a.useCallback)((function(e) {
                            if (!T.isZero()) {
                                var t;
                                t = "0" === e.target.value ? 1.05 : .95;
                                var n = d(t, T.toFixed());
                                l({
                                    wchUprc: n,
                                    price: n
                                }), w(e.target.value)
                            }
                        }), [t, s]),
                        I = (0, a.useCallback)((function(e) {
                            if (T.isZero()) return m(ne.DzK, {
                                message: _("toast.trade.msg10")
                            }), null;
                            if ("0" === e.target.value) {
                                var t = 0 === s ? c.ask : c.bid;
                                l({
                                    wchUprc: t,
                                    price: t
                                })
                            } else w("0"), P({
                                target: {
                                    value: "0"
                                }
                            });
                            j(e.target.value)
                        }), [s, c, P, t]);
                    return (0, a.useEffect)((function() {
                        g || 1 !== s || "0" === y || (I({
                            target: {
                                value: y
                            }
                        }), P({
                            target: {
                                value: C
                            }
                        })), x(1 === s)
                    }), [s, g, y, C]), g ? (0, u.jsxs)(u.Fragment, {
                        children: [(0, u.jsxs)("div", {
                            className: Xt("order-form__row"),
                            children: [(0, u.jsx)("h4", {
                                className: Xt("order-form__title"),
                                children: _("page.trade.msg143")
                            }), (0, u.jsx)("div", {
                                className: Xt("order-form__box"),
                                children: (0, u.jsxs)("div", {
                                    className: Xt("order-form-radio"),
                                    children: [(0, u.jsxs)("div", {
                                        className: Xt("order-form-radio__inner"),
                                        children: [(0, u.jsx)("input", {
                                            type: "radio",
                                            name: "rdoAutoCond",
                                            id: "rdoAutoCond0",
                                            className: "blind",
                                            value: "0",
                                            onChange: I,
                                            checked: "0" === y
                                        }), (0, u.jsxs)("label", {
                                            htmlFor: "rdoAutoCond0",
                                            className: Xt("order-form-radio__label"),
                                            children: [(0, u.jsx)("span", {
                                                className: Xt("order-form-radio__icon")
                                            }), _("page.trade.msg145")]
                                        })]
                                    }), (0, u.jsxs)("div", {
                                        className: Xt("order-form-radio__inner"),
                                        children: [(0, u.jsx)("input", {
                                            type: "radio",
                                            name: "rdoAutoCond",
                                            id: "rdoAutoCond1",
                                            className: "blind",
                                            value: "1",
                                            onChange: I,
                                            checked: "1" === y
                                        }), (0, u.jsxs)("label", {
                                            htmlFor: "rdoAutoCond1",
                                            className: Xt("order-form-radio__label"),
                                            children: [(0, u.jsx)("span", {
                                                className: Xt("order-form-radio__icon")
                                            }), _("page.trade.msg146")]
                                        })]
                                    })]
                                })
                            })]
                        }), (0, u.jsxs)("div", {
                            className: Xt("order-form__row"),
                            children: [(0, u.jsx)("h4", {
                                className: Xt("order-form__title"),
                                children: _("page.trade.msg144")
                            }), (0, u.jsx)("div", {
                                className: Xt("order-form__box"),
                                children: (0, u.jsxs)("div", {
                                    className: Xt("order-form-radio"),
                                    children: [(0, u.jsxs)("div", {
                                        className: Xt("order-form-radio__inner"),
                                        children: [(0, u.jsx)("input", {
                                            type: "radio",
                                            name: "rdoAutoType",
                                            id: "rdoAutoType0",
                                            className: "blind",
                                            value: "0",
                                            onChange: P,
                                            checked: "0" === C,
                                            disabled: "1" !== y
                                        }), (0, u.jsxs)("label", {
                                            htmlFor: "rdoAutoType0",
                                            className: Xt("order-form-radio__label"),
                                            children: [(0, u.jsx)("span", {
                                                className: Xt("order-form-radio__icon")
                                            }), _("page.trade.msg147")]
                                        })]
                                    }), (0, u.jsxs)("div", {
                                        className: Xt("order-form-radio__inner"),
                                        children: [(0, u.jsx)("input", {
                                            type: "radio",
                                            name: "rdoAutoType",
                                            id: "rdoAutoType1",
                                            className: "blind",
                                            value: "1",
                                            onChange: P,
                                            checked: "1" === C,
                                            disabled: "1" !== y
                                        }), (0, u.jsxs)("label", {
                                            htmlFor: "rdoAutoType1",
                                            className: Xt("order-form-radio__label"),
                                            children: [(0, u.jsx)("span", {
                                                className: Xt("order-form-radio__icon")
                                            }), _("page.trade.msg148")]
                                        }), (0, u.jsxs)("div", {
                                            className: Xt("order-form-tip"),
                                            children: [(0, u.jsx)("span", {
                                                className: Xt("order-form-tip__button"),
                                                children: (0, u.jsx)("span", {
                                                    className: "blind",
                                                    children: "\uc790\uc138\ud788"
                                                })
                                            }), (0, u.jsxs)("div", {
                                                className: Xt("order-form-tip-layer"),
                                                children: [(0, u.jsx)("strong", {
                                                    className: Xt("order-form-tip-layer__title"),
                                                    children: _("page.trade.msg149")
                                                }), (0, u.jsx)("p", {
                                                    className: Xt("order-form-tip-layer__text"),
                                                    children: _("page.trade.msg150")
                                                }), (0, u.jsx)("strong", {
                                                    className: Xt("order-form-tip-layer__title"),
                                                    children: _("page.trade.msg151")
                                                }), (0, u.jsx)("p", {
                                                    className: Xt("order-form-tip-layer__text"),
                                                    children: _("page.trade.msg152")
                                                })]
                                            })]
                                        })]
                                    })]
                                })
                            })]
                        })]
                    }) : null
                })),
                $t = n(51029),
                en = c().bind(Zt),
                tn = (0, l.Pi)((function(e) {
                    var t = e.avgBuyAmt,
                        n = (0, r.G2)(),
                        i = n.service,
                        s = i.fnCalcPricePer,
                        c = i.setBuySellData,
                        l = i.buyInfo,
                        d = i.sellInfo,
                        _ = i.setNeedCheckPrice,
                        m = n.localeService.locale,
                        p = (0, S.PZ)().getInstance,
                        f = (0, a.useMemo)((function() {
                            return p(t)
                        }), [t]),
                        h = (0, a.useRef)((0, L.Z)(C.d8.map((function(e) {
                            return {
                                key: e.label,
                                value: e.value
                            }
                        })))),
                        g = (0, a.useState)(),
                        x = (0, o.Z)(g, 2),
                        b = x[0],
                        v = x[1],
                        y = (0, a.useRef)(!1),
                        j = (0, a.useCallback)((function(e) {
                            y.current = !0, f.isZero() || (_(!0), c({
                                wchUprc: s(e, f.toFixed())
                            })), v(e)
                        }), [t]);
                    return (0, a.useEffect)((function() {
                        y.current ? y.current && (y.current = !1) : v(void 0)
                    }), [l.wchUprc, d.wchUprc]), (0, u.jsx)($t.ZP, {
                        value: b,
                        className: en("order-form__select"),
                        onChange: j,
                        data: h.current,
                        disabled: f.isZero(),
                        placeholder: m("page.trade.msg142")
                    })
                })),
                nn = c().bind(Zt),
                rn = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.assetService.getCoinAsset,
                        n = e.coinService.getCoin,
                        i = e.localeService.locale,
                        o = (0, a.useMemo)((function() {
                            var e;
                            return null === (e = t(n.coinType)) || void 0 === e ? void 0 : e.avgBuyAmt
                        }), [n.coinType, t(n.coinType)]);
                    return (0, u.jsxs)(u.Fragment, {
                        children: [(0, u.jsx)(Jt, {
                            avgBuyAmt: o
                        }), (0, u.jsxs)("div", {
                            className: nn("order-form__row", "order-form__row--select"),
                            children: [(0, u.jsx)(Ht, {
                                wchUprc: !0
                            }), (0, u.jsx)(tn, {
                                avgBuyAmt: o
                            })]
                        }), (0, u.jsx)("p", {
                            className: nn("order-form__select-amount"),
                            children: i("page.trade.msg141", {
                                avgBuyAmt: o ? new($())(o).toFormat() : "-",
                                tagEm: (0, u.jsx)("em", {})
                            })
                        })]
                    })
                })),
                on = c().bind({
                    "modal--agree-auto-detail": "AgreeAutoDetailModal_modal--agree-auto-detail__aTcAg",
                    "agree-auto-detail-modal": "AgreeAutoDetailModal_agree-auto-detail-modal__Oz603",
                    "agree-auto-detail-modal__title": "AgreeAutoDetailModal_agree-auto-detail-modal__title__Hrnxe",
                    "agree-auto-detail-modal__list-title": "AgreeAutoDetailModal_agree-auto-detail-modal__list-title__omvjx",
                    "agree-auto-detail-modal-list": "AgreeAutoDetailModal_agree-auto-detail-modal-list__Eijwe",
                    "agree-auto-detail-modal-bottom": "AgreeAutoDetailModal_agree-auto-detail-modal-bottom__S5xi7",
                    "agree-auto-detail-modal-bottom__button": "AgreeAutoDetailModal_agree-auto-detail-modal-bottom__button__QbEnn"
                }),
                an = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.modalService,
                        n = t.visible,
                        i = t.hideModal,
                        o = e.localeService.locale;
                    return (0, u.jsx)(ee.ZP, {
                        visible: n(ne.khI),
                        closeByBg: !1,
                        customModalClass: on("modal--agree-auto-detail"),
                        children: (0, u.jsxs)("div", {
                            className: on("agree-auto-detail-modal"),
                            children: [(0, u.jsx)("h3", {
                                className: on("agree-auto-detail-modal__title"),
                                children: o("pop.trade.agreeAutoDetail.msg001")
                            }), (0, u.jsx)("h4", {
                                className: on("agree-auto-detail-modal__list-title"),
                                children: o("pop.trade.agreeAutoDetail.msg002")
                            }), (0, u.jsxs)("ul", {
                                className: on("agree-auto-detail-modal-list"),
                                children: [(0, u.jsx)("li", {
                                    children: o("pop.trade.agreeAutoDetail.msg003")
                                }), (0, u.jsx)("li", {
                                    children: o("pop.trade.agreeAutoDetail.msg004")
                                }), (0, u.jsx)("li", {
                                    children: o("pop.trade.agreeAutoDetail.msg005")
                                }), (0, u.jsx)("li", {
                                    children: o("pop.trade.agreeAutoDetail.msg006")
                                }), (0, u.jsx)("li", {
                                    children: o("pop.trade.agreeAutoDetail.msg007")
                                }), (0, u.jsx)("li", {
                                    children: o("pop.trade.agreeAutoDetail.msg008")
                                }), (0, u.jsx)("li", {
                                    children: o("pop.trade.agreeAutoDetail.msg009")
                                })]
                            }), (0, u.jsx)("h4", {
                                className: on("agree-auto-detail-modal__list-title"),
                                children: o("pop.trade.agreeAutoDetail.msg010")
                            }), (0, u.jsxs)("ul", {
                                className: on("agree-auto-detail-modal-list"),
                                children: [(0, u.jsx)("li", {
                                    children: o("pop.trade.agreeAutoDetail.msg011")
                                }), (0, u.jsx)("li", {
                                    children: o("pop.trade.agreeAutoDetail.msg012")
                                }), (0, u.jsx)("li", {
                                    children: o("pop.trade.agreeAutoDetail.msg013")
                                }), (0, u.jsx)("li", {
                                    children: o("pop.trade.agreeAutoDetail.msg014")
                                })]
                            }), (0, u.jsx)("div", {
                                className: on("agree-auto-detail-modal-bottom"),
                                children: (0, u.jsx)(g.ZP, {
                                    className: on("agree-auto-detail-modal-bottom__button"),
                                    onClick: function() {
                                        return i(ne.khI)
                                    },
                                    children: o("pop.trade.agreeAutoDetail.msg015")
                                })
                            })]
                        })
                    })
                })),
                sn = JSON.parse('{"pop.trade.agreeAutoDetail.msg001":"Precautions for Stop-Limit","pop.trade.agreeAutoDetail.msg002":"1. Information on Stop-Limit","pop.trade.agreeAutoDetail.msg003":"Stop-limit is a service that automatically takes an order when the current price reaches the stop price after setting the conditions for stop price, order price, and order quantity.","pop.trade.agreeAutoDetail.msg004":"Stop-limit can be set only within the available order amount (qty.), and the available order amount (qty.) will be deducted immediately at the time of stop-limit setting.","pop.trade.agreeAutoDetail.msg005":"If the set order\u2019s current price does not reach the stop price, this order will be displayed as \\"Watching.\\"","pop.trade.agreeAutoDetail.msg006":"Stop-limit cases that are in watch or outstanding state can be checked in the outstanding list until it is concluded, and it can be canceled.","pop.trade.agreeAutoDetail.msg007":"Up to 25 \\"watching\\" orders can be created per account for all virtual assets.","pop.trade.agreeAutoDetail.msg008":"There is no expiration date for the set stop-limits, and therefore, watching will continue until the order is received.","pop.trade.agreeAutoDetail.msg009":"Regardless of whether the conditions for detection are reached, the commission rate at the time of generating orders is applied to the automatic order.","pop.trade.agreeAutoDetail.msg010":"2. Precautions","pop.trade.agreeAutoDetail.msg011":"Stop-limit may not be received if there is a rapid drop in market prices, or there is an abnormal increase in trade volume.","pop.trade.agreeAutoDetail.msg012":"If hard fork or swap occurs for the asset, stop-limit may be canceled limited to the target asset.","pop.trade.agreeAutoDetail.msg013":"If service is suspended due to system maintenance, etc., stop-limit may be canceled. Therefore, you should regularly check your stop-limit conditions, orders, conclusion, etc.","pop.trade.agreeAutoDetail.msg014":"If it is determined that it is more beneficial to stop the service instead of continuing services due to total or partial malfunctions in the system, or errors in specific parts (operating equipment, program, market price), all orders can be stopped.","pop.trade.agreeAutoDetail.msg015":"Confirm"}'),
                cn = JSON.parse('{"pop.trade.agreeAutoDetail.msg001":"\uc790\ub3d9 \uc8fc\ubb38 \uc8fc\uc758\uc0ac\ud56d","pop.trade.agreeAutoDetail.msg002":"1. \uc790\ub3d9 \uc8fc\ubb38 \uc548\ub0b4","pop.trade.agreeAutoDetail.msg003":"\uc790\ub3d9 \uc8fc\ubb38\uc740 \uac10\uc2dc \uac00\uaca9, \uc8fc\ubb38 \uac00\uaca9, \uc8fc\ubb38 \uc218\ub7c9\uc758 \uc870\uac74\uc744 \uc124\uc815\ud55c \ud6c4 \ud604\uc7ac \uac00\uaca9\uc774 \uac10\uc2dc \uac00\uaca9\uc5d0 \ub3c4\ub2ec \uc2dc \uc790\ub3d9\uc73c\ub85c \uc8fc\ubb38\uc774 \uc811\uc218\ub418\ub294 \uc11c\ube44\uc2a4\uc785\ub2c8\ub2e4.","pop.trade.agreeAutoDetail.msg004":"\uc790\ub3d9 \uc8fc\ubb38\uc740 \uc8fc\ubb38\uac00\ub2a5 \uae08\uc561(\uc218\ub7c9) \uc774\ub0b4\ub85c\ub9cc \uc124\uc815\uc774 \uac00\ub2a5\ud558\uba70, \uc790\ub3d9 \uc8fc\ubb38 \uc124\uc815 \uc2dc\uc810\uc5d0 \uc8fc\ubb38\uac00\ub2a5 \uae08\uc561(\uc218\ub7c9)\uc774 \uc989\uc2dc \ucc28\uac10\ub429\ub2c8\ub2e4.","pop.trade.agreeAutoDetail.msg005":"\uc124\uc815\ub41c \uc8fc\ubb38\uc758 \ud604\uc7ac \uac00\uaca9\uc774 \uac10\uc2dc \uac00\uaca9\uc5d0 \ub3c4\ub2ec\ud558\uc9c0 \ubabb\ud588\uc744 \uacbd\uc6b0, \ud574\ub2f9 \uc8fc\ubb38\uc740 \\"\uac10\uc2dc \uc911\\" \uc0c1\ud0dc\ub85c \ub178\ucd9c\ub429\ub2c8\ub2e4.","pop.trade.agreeAutoDetail.msg006":"\uac10\uc2dc \uc911 \ub610\ub294 \ubbf8\uccb4\uacb0 \uc0c1\ud0dc\uc758 \uc790\ub3d9 \uc8fc\ubb38\uac74\uc740 \uccb4\uacb0\uc774 \uc644\ub8cc\ub418\uae30 \uc804\uae4c\uc9c0 \ubbf8\uccb4\uacb0 \ub0b4\uc5ed\uc5d0\uc11c \ud655\uc778\ud558\uc2e4 \uc218 \uc788\uc73c\uba70, \ucde8\uc18c\uac00 \uac00\ub2a5\ud569\ub2c8\ub2e4.","pop.trade.agreeAutoDetail.msg007":"\\"\uac10\uc2dc \uc911\\"\uc0c1\ud0dc\uc758 \uc8fc\ubb38\uc740 \uc804\uccb4 \uac00\uc0c1\uc790\uc0b0\uc744 \ub300\uc0c1\uc73c\ub85c \uacc4\uc815\ub2f9 \ucd5c\ub300 25\uac1c\uae4c\uc9c0 \uc0dd\uc131 \uac00\ub2a5\ud569\ub2c8\ub2e4.","pop.trade.agreeAutoDetail.msg008":"\uc124\uc815\ub41c \uc790\ub3d9\uc8fc\ubb38\uc740 \uac10\uc2dc\uc5d0 \ub300\ud55c \uc720\ud6a8\uae30\uac04\uc774 \uc5c6\uc73c\ubbc0\ub85c \uc8fc\ubb38\uc811\uc218 \uc804\uae4c\uc9c0 \uac10\uc2dc \uc0c1\ud0dc\uac00 \uc9c0\uc18d\ub429\ub2c8\ub2e4.","pop.trade.agreeAutoDetail.msg009":"\uc790\ub3d9 \uc8fc\ubb38\uc740 \uac10\uc2dc \uc870\uac74 \ub3c4\ub2ec \uc5ec\ubd80\uc640 \uad00\uacc4\uc5c6\uc774, \uc8fc\ubb38 \uc0dd\uc131 \uc2dc\uc810\uc758 \uc218\uc218\ub8cc\uc728\uc774 \uc801\uc6a9\ub429\ub2c8\ub2e4.","pop.trade.agreeAutoDetail.msg010":"2. \uc8fc\uc758\uc0ac\ud56d","pop.trade.agreeAutoDetail.msg011":"\uc2dc\uc138 \uae09\ub4f1\ub77d \ubc0f \ube44\uc815\uc0c1\uc801\uc778 \uac70\ub798\ub7c9 \ud3ed\ub4f1 \uc2dc \uc790\ub3d9 \uc8fc\ubb38\uc774 \uc811\uc218\ub418\uc9c0 \uc54a\uc744 \uc218 \uc788\uc2b5\ub2c8\ub2e4.","pop.trade.agreeAutoDetail.msg012":"\uc790\uc0b0\uc758 \ud558\ub4dc\ud3ec\ud06c, \uc2a4\uc651\uc774 \ubc1c\uc0dd\ud560 \uacbd\uc6b0\uc5d0\ub294 \ub300\uc0c1 \uc790\uc0b0\uc5d0 \ud55c\ud558\uc5ec \uc790\ub3d9 \uc8fc\ubb38\uc774 \ucde8\uc18c\ub420 \uc218 \uc788\uc2b5\ub2c8\ub2e4.","pop.trade.agreeAutoDetail.msg013":"\ub2f9\uc0ac \uc2dc\uc2a4\ud15c \uc810\uac80 \ub4f1\uc73c\ub85c \uc778\ud55c \uc11c\ube44\uc2a4 \uc911\ub2e8\uc774 \ubc1c\uc0dd\ud560 \uacbd\uc6b0\uc5d0\ub294 \uc790\ub3d9 \uc8fc\ubb38\uc774 \ucde8\uc18c\ub420 \uc218 \uc788\uc2b5\ub2c8\ub2e4. \ub530\ub77c\uc11c \uc790\ub3d9\uc8fc\ubb38 \uc870\uac74, \uc8fc\ubb38 \ubc0f \uccb4\uacb0 \uc5ec\ubd80\ub97c \uc218\uc2dc\ub85c \ud655\uc778\ud558\uc154\uc57c \ud569\ub2c8\ub2e4.","pop.trade.agreeAutoDetail.msg014":"\uc2dc\uc2a4\ud15c \uc804\uccb4 \ud639\uc740 \ubd80\ubd84\uc7a5\uc560, \ud2b9\uc815\ubd80\ubd84\uc758 \uc624\ub958(\uc6b4\uc601\uc7a5\ube44, \ud504\ub85c\uadf8\ub7a8, \uc2dc\uc138) \ub4f1\uc73c\ub85c \uc11c\ube44\uc2a4 \uc9c0\uc18d\ubcf4\ub2e4 \uc11c\ube44\uc2a4 \uc911\uc9c0\uac00 \ud544\uc694\ud558\ub2e4\uace0 \ud310\ub2e8\ub418\ub294 \uacbd\uc6b0 \uc804\uccb4 \uc8fc\ubb38\uc5d0 \uad00\ud558\uc5ec \uc815\uc9c0\uc2dc\ud0ac \uc218 \uc788\uc2b5\ub2c8\ub2e4.","pop.trade.agreeAutoDetail.msg015":"\ud655\uc778"}'),
                ln = (0, r.FU)(an, {
                    ko: cn,
                    en: sn
                }),
                dn = n(52160),
                _n = c().bind({
                    "agree-auto-trade-modal": "AgreeAutoTradeModal_agree-auto-trade-modal__+ODUH",
                    "agree-auto-trade-modal-header": "AgreeAutoTradeModal_agree-auto-trade-modal-header__dKjwv",
                    "agree-auto-trade-modal-header__title": "AgreeAutoTradeModal_agree-auto-trade-modal-header__title__8syZc",
                    "agree-auto-trade-modal-header__text": "AgreeAutoTradeModal_agree-auto-trade-modal-header__text__L6OlE",
                    "agree-auto-trade-modal-step": "AgreeAutoTradeModal_agree-auto-trade-modal-step__VnHEJ",
                    "agree-auto-trade-modal-step__item": "AgreeAutoTradeModal_agree-auto-trade-modal-step__item__idvin",
                    "agree-auto-trade-modal-step__ico-arrow": "AgreeAutoTradeModal_agree-auto-trade-modal-step__ico-arrow__x24Tk",
                    "agree-auto-trade-modal-step__number": "AgreeAutoTradeModal_agree-auto-trade-modal-step__number__MZi+4",
                    "agree-auto-trade-modal-step__text": "AgreeAutoTradeModal_agree-auto-trade-modal-step__text__mc2Hi",
                    "agree-auto-trade-modal-notice": "AgreeAutoTradeModal_agree-auto-trade-modal-notice__NRlQf",
                    "agree-auto-trade-modal-notice__title": "AgreeAutoTradeModal_agree-auto-trade-modal-notice__title__0boFg",
                    "agree-auto-trade-modal-notice__link": "AgreeAutoTradeModal_agree-auto-trade-modal-notice__link__AY+mp",
                    "agree-auto-trade-modal-notice__box": "AgreeAutoTradeModal_agree-auto-trade-modal-notice__box__GDQaD",
                    "agree-auto-trade-modal-notice__list-title": "AgreeAutoTradeModal_agree-auto-trade-modal-notice__list-title__55A5D",
                    "agree-auto-trade-modal-notice__list": "AgreeAutoTradeModal_agree-auto-trade-modal-notice__list__O+LvQ",
                    "agree-auto-trade-modal-check": "AgreeAutoTradeModal_agree-auto-trade-modal-check__yKHWV",
                    "agree-auto-trade-modal-bottom": "AgreeAutoTradeModal_agree-auto-trade-modal-bottom__oGHUh",
                    "agree-auto-trade-modal-bottom__button": "AgreeAutoTradeModal_agree-auto-trade-modal-bottom__button__sNjeq"
                }),
                mn = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.service.buySell,
                        n = e.modalService,
                        i = n.visible,
                        s = n.showModal,
                        c = n.hideModal,
                        l = e.sessionService,
                        _ = l.login,
                        m = l.setUserInfo,
                        p = e.localeService.locale,
                        f = e.httpService.put,
                        h = (0, a.useState)(!1),
                        x = (0, o.Z)(h, 2),
                        b = x[0],
                        v = x[1],
                        y = (0, d.s0)(),
                        j = (0, d.TH)().pathname,
                        N = (0, a.useCallback)((function() {
                            return v(!1)
                        }), []),
                        k = (0, a.useCallback)((function() {
                            c(ne.srg)
                        }), []),
                        C = (0, a.useCallback)((function() {
                            _ ? b ? f("/v1/customersupport/agree-rsvt-trade-warn-set", {
                                tradeWarnYn: !0
                            }).then((function(e) {
                                200 === e.status && (s(ne.DzK, {
                                    message: (0, u.jsxs)(u.Fragment, {
                                        children: [T()().format("YYYY-MM-DD hh : mm : ss"), (0, u.jsx)("br", {}), p("pop.trade.agreeAuto.msg026")]
                                    }),
                                    modalBtn: {
                                        feature: dn.y_.CUSTOM,
                                        callback: k
                                    }
                                }), m({
                                    agreeToRsvtTradeNotice: !0
                                }))
                            })) : s(ne.DzK, {
                                message: p("pop.trade.agreeAuto.msg025")
                            }) : s(ne.DzK, {
                                message: p("pop.trade.agreeAuto.msg024"),
                                modalBtn: {
                                    feature: dn.y_.CUSTOM,
                                    callback: function() {
                                        window.privateParams = {
                                            buySell: t
                                        }, y("/login", {
                                            state: {
                                                from: {
                                                    pathname: j
                                                },
                                                replace: !0
                                            }
                                        })
                                    }
                                }
                            })
                        }), [_, b, k, j, t]);
                    return (0, a.useEffect)((function() {
                        return function() {
                            c(ne.srg), c(ne.khI)
                        }
                    }), []), (0, u.jsxs)(u.Fragment, {
                        children: [(0, u.jsx)(ee.ZP, {
                            visible: i(ne.srg),
                            onClose: N,
                            children: (0, u.jsxs)("div", {
                                className: _n("agree-auto-trade-modal"),
                                children: [(0, u.jsxs)("div", {
                                    className: _n("agree-auto-trade-modal-header"),
                                    children: [(0, u.jsx)("h3", {
                                        className: _n("agree-auto-trade-modal-header__title"),
                                        children: p("pop.trade.agreeAuto.msg001")
                                    }), (0, u.jsx)("p", {
                                        className: _n("agree-auto-trade-modal-header__text"),
                                        children: p("pop.trade.agreeAuto.msg002")
                                    })]
                                }), (0, u.jsxs)("ol", {
                                    className: _n("agree-auto-trade-modal-step"),
                                    children: [(0, u.jsxs)("li", {
                                        className: _n("agree-auto-trade-modal-step__item"),
                                        children: [(0, u.jsx)("strong", {
                                            className: _n("agree-auto-trade-modal-step__number"),
                                            children: "STEP 1"
                                        }), (0, u.jsx)("p", {
                                            className: _n("agree-auto-trade-modal-step__text"),
                                            children: p("pop.trade.agreeAuto.msg003", {
                                                tag: (0, u.jsx)("br", {})
                                            })
                                        })]
                                    }), (0, u.jsxs)("li", {
                                        className: _n("agree-auto-trade-modal-step__item"),
                                        children: [(0, u.jsx)("span", {
                                            className: _n("agree-auto-trade-modal-step__ico-arrow")
                                        }), (0, u.jsx)("strong", {
                                            className: _n("agree-auto-trade-modal-step__number"),
                                            children: "STEP 2"
                                        }), (0, u.jsx)("p", {
                                            className: _n("agree-auto-trade-modal-step__text"),
                                            children: p("pop.trade.agreeAuto.msg004")
                                        })]
                                    }), (0, u.jsxs)("li", {
                                        className: _n("agree-auto-trade-modal-step__item"),
                                        children: [(0, u.jsx)("span", {
                                            className: _n("agree-auto-trade-modal-step__ico-arrow")
                                        }), (0, u.jsx)("strong", {
                                            className: _n("agree-auto-trade-modal-step__number"),
                                            children: "STEP 3"
                                        }), (0, u.jsx)("p", {
                                            className: _n("agree-auto-trade-modal-step__text"),
                                            children: p("pop.trade.agreeAuto.msg005")
                                        })]
                                    })]
                                }), (0, u.jsxs)("div", {
                                    className: _n("agree-auto-trade-modal-notice"),
                                    children: [(0, u.jsx)("h4", {
                                        className: _n("agree-auto-trade-modal-notice__title"),
                                        children: p("pop.trade.agreeAuto.msg006")
                                    }), (0, u.jsx)(g.ZP, {
                                        className: _n("agree-auto-trade-modal-notice__link"),
                                        onClick: function() {
                                            return s(ne.khI)
                                        },
                                        children: p("pop.trade.agreeAuto.msg007")
                                    }), (0, u.jsxs)("div", {
                                        className: _n("agree-auto-trade-modal-notice__box"),
                                        children: [(0, u.jsx)("h5", {
                                            className: _n("agree-auto-trade-modal-notice__list-title"),
                                            children: p("pop.trade.agreeAuto.msg008")
                                        }), (0, u.jsxs)("ul", {
                                            className: _n("agree-auto-trade-modal-notice__list"),
                                            children: [(0, u.jsx)("li", {
                                                children: p("pop.trade.agreeAuto.msg009")
                                            }), (0, u.jsx)("li", {
                                                children: p("pop.trade.agreeAuto.msg010")
                                            }), (0, u.jsx)("li", {
                                                children: p("pop.trade.agreeAuto.msg011")
                                            }), (0, u.jsx)("li", {
                                                children: p("pop.trade.agreeAuto.msg012")
                                            }), (0, u.jsx)("li", {
                                                children: p("pop.trade.agreeAuto.msg013")
                                            }), (0, u.jsx)("li", {
                                                children: p("pop.trade.agreeAuto.msg014")
                                            }), (0, u.jsx)("li", {
                                                children: p("pop.trade.agreeAuto.msg015")
                                            })]
                                        }), (0, u.jsx)("h5", {
                                            className: _n("agree-auto-trade-modal-notice__list-title"),
                                            children: p("pop.trade.agreeAuto.msg016")
                                        }), (0, u.jsxs)("ul", {
                                            className: _n("agree-auto-trade-modal-notice__list"),
                                            children: [(0, u.jsx)("li", {
                                                children: p("pop.trade.agreeAuto.msg017")
                                            }), (0, u.jsx)("li", {
                                                children: p("pop.trade.agreeAuto.msg018")
                                            }), (0, u.jsx)("li", {
                                                children: p("pop.trade.agreeAuto.msg019")
                                            }), (0, u.jsx)("li", {
                                                children: p("pop.trade.agreeAuto.msg020")
                                            })]
                                        })]
                                    }), (0, u.jsx)(U.ZP, {
                                        className: _n("agree-auto-trade-modal-check"),
                                        checked: b,
                                        label: p("pop.trade.agreeAuto.msg021"),
                                        onChange: function(e) {
                                            return v(e.target.checked)
                                        }
                                    }), (0, u.jsxs)("div", {
                                        className: _n("agree-auto-trade-modal-bottom"),
                                        children: [(0, u.jsx)(g.ZP, {
                                            className: _n("agree-auto-trade-modal-bottom__button"),
                                            onClick: C,
                                            children: p("pop.trade.agreeAuto.msg022")
                                        }), (0, u.jsx)(g.ZP, {
                                            className: _n("agree-auto-trade-modal-bottom__button"),
                                            onClick: k,
                                            children: p("pop.trade.agreeAuto.msg023")
                                        })]
                                    })]
                                })]
                            })
                        }), (0, u.jsx)(ln, {})]
                    })
                })),
                un = JSON.parse('{"pop.trade.agreeAuto.msg001":"Stop-Limit","pop.trade.agreeAuto.msg002":"Automatically takes an order when the reaches the stop price after setting the conditions.","pop.trade.agreeAuto.msg003":"Setting the conditions {{tag/}} for price","pop.trade.agreeAuto.msg004":"save the conditions , start to Watching","pop.trade.agreeAuto.msg005":"takes an order when the reach the stop price","pop.trade.agreeAuto.msg006":"Precautions for Stop-Limit","pop.trade.agreeAuto.msg007":"See All","pop.trade.agreeAuto.msg008":"1. Information on Stop-Limit","pop.trade.agreeAuto.msg009":"Stop-limit is a service that automatically takes an order when the current price reaches the stop price after setting the conditions for stop price, order price, and order quantity.","pop.trade.agreeAuto.msg010":"Stop-limit can be set only within the available order amount (qty.), and the available order amount (qty.) will be deducted immediately at the time of stop-limit setting.","pop.trade.agreeAuto.msg011":"If the set order\u2019s current price does not reach the stop price, this order will be displayed as \\"Watching.\\"","pop.trade.agreeAuto.msg012":"Stop-limit cases that are in watch or outstanding state can be checked in the outstanding list until it is concluded, and it can be canceled.","pop.trade.agreeAuto.msg013":"Up to 25 \\"watching\\" orders can be created per account for all virtual assets.","pop.trade.agreeAuto.msg014":"There is no expiration date for the set stop-limits, and therefore, watching will continue until the order is received.","pop.trade.agreeAuto.msg015":"Regardless of whether the conditions for detection are reached, the commission rate at the time of generating orders is applied to the automatic order.","pop.trade.agreeAuto.msg016":"2. Precautions","pop.trade.agreeAuto.msg017":"Stop-limit may not be received if there is a rapid drop in market prices, or there is an abnormal increase in trade volume.","pop.trade.agreeAuto.msg018":"If hard fork or swap occurs for the asset, stop-limit may be canceled limited to the target asset.","pop.trade.agreeAuto.msg019":"If service is suspended due to system maintenance, etc., stop-limit may be canceled. Therefore, you should regularly check your stop-limit conditions, orders, conclusion, etc.","pop.trade.agreeAuto.msg020":"If it is determined that it is more beneficial to stop the service instead of continuing services due to total or partial malfunctions in the system, or errors in specific parts (operating equipment, program, market price), all orders can be stopped.","pop.trade.agreeAuto.msg021":"I agree to terms of service.","pop.trade.agreeAuto.msg022":"Confirm","pop.trade.agreeAuto.msg023":"Cancel","pop.trade.agreeAuto.msg024":"\ub85c\uadf8\uc778 \ud6c4 \uc774\uc6a9 \uac00\ub2a5\ud569\ub2c8\ub2e4.","pop.trade.agreeAuto.msg025":"\uc774\uc6a9\ub3d9\uc758\ub97c \uc120\ud0dd\ud574 \uc8fc\uc138\uc694.","pop.trade.agreeAuto.msg026":"\uc790\ub3d9\uc8fc\ubb38 \uc774\uc6a9\uc5d0 \ub3d9\uc758\ud558\uc168\uc2b5\ub2c8\ub2e4."}'),
                pn = JSON.parse('{"pop.trade.agreeAuto.msg001":"\uc790\ub3d9 \uc8fc\ubb38 (Stop-Limit)","pop.trade.agreeAuto.msg002":"\uc124\uc815\ud55c \uac10\uc2dc \uac00\uaca9 \ub3c4\ub2ec \uc2dc \uc6d0\ud558\ub294 \uc8fc\ubb38\uc774 \uc811\uc218\ub429\ub2c8\ub2e4.","pop.trade.agreeAuto.msg003":"\uac10\uc2dc\uac00\uaca9, \uc8fc\ubb38 \uac00\uaca9\ub4f1 {{tag/}} \uc870\uac74\uc124\uc815","pop.trade.agreeAuto.msg004":"\uc870\uac74 \uc800\uc7a5 \ubc0f \uac10\uc2dc\uc2dc\uc791","pop.trade.agreeAuto.msg005":"\uac10\uc2dc\uac00\uaca9 \ub3c4\ub2ec \uc8fc\ubb38\uc811\uc218","pop.trade.agreeAuto.msg006":"\uc790\ub3d9 \uc8fc\ubb38 \uc8fc\uc758\uc0ac\ud56d","pop.trade.agreeAuto.msg007":"\uc804\uccb4\ubcf4\uae30","pop.trade.agreeAuto.msg008":"1. \uc790\ub3d9 \uc8fc\ubb38 \uc548\ub0b4","pop.trade.agreeAuto.msg009":"\uc790\ub3d9 \uc8fc\ubb38\uc740 \uac10\uc2dc \uac00\uaca9, \uc8fc\ubb38 \uac00\uaca9, \uc8fc\ubb38 \uc218\ub7c9\uc758 \uc870\uac74\uc744 \uc124\uc815\ud55c \ud6c4 \ud604\uc7ac \uac00\uaca9\uc774 \uac10\uc2dc \uac00\uaca9\uc5d0 \ub3c4\ub2ec \uc2dc \uc790\ub3d9\uc73c\ub85c \uc8fc\ubb38\uc774 \uc811\uc218\ub418\ub294 \uc11c\ube44\uc2a4\uc785\ub2c8\ub2e4.","pop.trade.agreeAuto.msg010":"\uc790\ub3d9 \uc8fc\ubb38\uc740 \uc8fc\ubb38\uac00\ub2a5 \uae08\uc561(\uc218\ub7c9) \uc774\ub0b4\ub85c\ub9cc \uc124\uc815\uc774 \uac00\ub2a5\ud558\uba70, \uc790\ub3d9 \uc8fc\ubb38 \uc124\uc815 \uc2dc\uc810\uc5d0 \uc8fc\ubb38\uac00\ub2a5 \uae08\uc561(\uc218\ub7c9)\uc774 \uc989\uc2dc \ucc28\uac10\ub429\ub2c8\ub2e4.","pop.trade.agreeAuto.msg011":"\uc124\uc815\ub41c \uc8fc\ubb38\uc758 \ud604\uc7ac \uac00\uaca9\uc774 \uac10\uc2dc \uac00\uaca9\uc5d0 \ub3c4\ub2ec\ud558\uc9c0 \ubabb\ud588\uc744 \uacbd\uc6b0, \ud574\ub2f9 \uc8fc\ubb38\uc740 \\"\uac10\uc2dc \uc911\\" \uc0c1\ud0dc\ub85c \ub178\ucd9c\ub429\ub2c8\ub2e4.","pop.trade.agreeAuto.msg012":"\uac10\uc2dc \uc911 \ub610\ub294 \ubbf8\uccb4\uacb0 \uc0c1\ud0dc\uc758 \uc790\ub3d9 \uc8fc\ubb38\uac74\uc740 \uccb4\uacb0\uc774 \uc644\ub8cc\ub418\uae30 \uc804\uae4c\uc9c0 \ubbf8\uccb4\uacb0 \ub0b4\uc5ed\uc5d0\uc11c \ud655\uc778\ud558\uc2e4 \uc218 \uc788\uc73c\uba70, \ucde8\uc18c\uac00 \uac00\ub2a5\ud569\ub2c8\ub2e4.","pop.trade.agreeAuto.msg013":"\\"\uac10\uc2dc \uc911\\"\uc0c1\ud0dc\uc758 \uc8fc\ubb38\uc740 \uc804\uccb4 \uac00\uc0c1\uc790\uc0b0\uc744 \ub300\uc0c1\uc73c\ub85c \uacc4\uc815\ub2f9 \ucd5c\ub300 25\uac1c\uae4c\uc9c0 \uc0dd\uc131 \uac00\ub2a5\ud569\ub2c8\ub2e4.","pop.trade.agreeAuto.msg014":"\uc124\uc815\ub41c \uc790\ub3d9\uc8fc\ubb38\uc740 \uac10\uc2dc\uc5d0 \ub300\ud55c \uc720\ud6a8\uae30\uac04\uc774 \uc5c6\uc73c\ubbc0\ub85c \uc8fc\ubb38\uc811\uc218 \uc804\uae4c\uc9c0 \uac10\uc2dc \uc0c1\ud0dc\uac00 \uc9c0\uc18d\ub429\ub2c8\ub2e4.","pop.trade.agreeAuto.msg015":"\uc790\ub3d9 \uc8fc\ubb38\uc740 \uac10\uc2dc \uc870\uac74 \ub3c4\ub2ec \uc5ec\ubd80\uc640 \uad00\uacc4\uc5c6\uc774, \uc8fc\ubb38 \uc0dd\uc131 \uc2dc\uc810\uc758 \uc218\uc218\ub8cc\uc728\uc774 \uc801\uc6a9\ub429\ub2c8\ub2e4.","pop.trade.agreeAuto.msg016":"2. \uc8fc\uc758\uc0ac\ud56d","pop.trade.agreeAuto.msg017":"\uc2dc\uc138 \uae09\ub4f1\ub77d \ubc0f \ube44\uc815\uc0c1\uc801\uc778 \uac70\ub798\ub7c9 \ud3ed\ub4f1 \uc2dc \uc790\ub3d9 \uc8fc\ubb38\uc774 \uc811\uc218\ub418\uc9c0 \uc54a\uc744 \uc218 \uc788\uc2b5\ub2c8\ub2e4.","pop.trade.agreeAuto.msg018":"\uc790\uc0b0\uc758 \ud558\ub4dc\ud3ec\ud06c, \uc2a4\uc651\uc774 \ubc1c\uc0dd\ud560 \uacbd\uc6b0\uc5d0\ub294 \ub300\uc0c1 \uc790\uc0b0\uc5d0 \ud55c\ud558\uc5ec \uc790\ub3d9 \uc8fc\ubb38\uc774 \ucde8\uc18c\ub420 \uc218 \uc788\uc2b5\ub2c8\ub2e4.","pop.trade.agreeAuto.msg019":"\ub2f9\uc0ac \uc2dc\uc2a4\ud15c \uc810\uac80 \ub4f1\uc73c\ub85c \uc778\ud55c \uc11c\ube44\uc2a4 \uc911\ub2e8\uc774 \ubc1c\uc0dd\ud560 \uacbd\uc6b0\uc5d0\ub294 \uc790\ub3d9 \uc8fc\ubb38\uc774 \ucde8\uc18c\ub420 \uc218 \uc788\uc2b5\ub2c8\ub2e4. \ub530\ub77c\uc11c \uc790\ub3d9\uc8fc\ubb38 \uc870\uac74, \uc8fc\ubb38 \ubc0f \uccb4\uacb0 \uc5ec\ubd80\ub97c \uc218\uc2dc\ub85c \ud655\uc778\ud558\uc154\uc57c \ud569\ub2c8\ub2e4.","pop.trade.agreeAuto.msg020":"\uc2dc\uc2a4\ud15c \uc804\uccb4 \ud639\uc740 \ubd80\ubd84\uc7a5\uc560, \ud2b9\uc815\ubd80\ubd84\uc758 \uc624\ub958(\uc6b4\uc601\uc7a5\ube44, \ud504\ub85c\uadf8\ub7a8, \uc2dc\uc138) \ub4f1\uc73c\ub85c \uc11c\ube44\uc2a4 \uc9c0\uc18d\ubcf4\ub2e4 \uc11c\ube44\uc2a4 \uc911\uc9c0\uac00 \ud544\uc694\ud558\ub2e4\uace0 \ud310\ub2e8\ub418\ub294 \uacbd\uc6b0 \uc804\uccb4 \uc8fc\ubb38\uc5d0 \uad00\ud558\uc5ec \uc815\uc9c0\uc2dc\ud0ac \uc218 \uc788\uc2b5\ub2c8\ub2e4.","pop.trade.agreeAuto.msg021":"\ub0b4\uc6a9\uc744 \ud655\uc778\ud588\uc73c\uba70, \uc774\uc6a9\uc5d0 \ub3d9\uc758\ud569\ub2c8\ub2e4.","pop.trade.agreeAuto.msg022":"\ud655\uc778","pop.trade.agreeAuto.msg023":"\ucde8\uc18c","pop.trade.agreeAuto.msg024":"\ub85c\uadf8\uc778 \ud6c4 \uc774\uc6a9 \uac00\ub2a5\ud569\ub2c8\ub2e4.","pop.trade.agreeAuto.msg025":"\uc774\uc6a9\ub3d9\uc758\ub97c \uc120\ud0dd\ud574 \uc8fc\uc138\uc694.","pop.trade.agreeAuto.msg026":"\uc790\ub3d9\uc8fc\ubb38 \uc774\uc6a9\uc5d0 \ub3d9\uc758\ud558\uc168\uc2b5\ub2c8\ub2e4."}'),
                fn = (0, r.FU)(mn, {
                    ko: pn,
                    en: un
                }),
                hn = c().bind(Zt),
                gn = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.service,
                        n = t.buySell,
                        i = t.orderType,
                        o = e.coinService.getMarket;
                    return (0, u.jsxs)("div", {
                        className: hn("order-form"),
                        children: [2 === i && (0, u.jsxs)(u.Fragment, {
                            children: [(0, u.jsx)(Ht, {}), (0, u.jsx)(Et, {}), (0, u.jsx)(Vt, {}), (0, u.jsx)(Kt, {}), (0, u.jsx)("div", {
                                className: hn("order-form__line")
                            }), (0, u.jsx)(Mt, {})]
                        }), 1 === i && (0, u.jsxs)(u.Fragment, {
                            children: [0 === n ? (0, u.jsx)(Mt, {}) : (0, u.jsx)(Vt, {}), (0, u.jsx)(Kt, {}), (0, u.jsx)("div", {
                                className: hn("order-form__line")
                            }), (0, u.jsx)(zt, {})]
                        }), 3 === i && (0, u.jsxs)(u.Fragment, {
                            children: [o.crncCd === C.Eo ? (0, u.jsx)(rn, {}) : (0, u.jsx)(Ht, {
                                wchUprc: !0
                            }), (0, u.jsx)(Ht, {
                                prc: !0
                            }), (0, u.jsx)(Vt, {}), (0, u.jsx)(Kt, {}), (0, u.jsx)("div", {
                                className: hn("order-form__line")
                            }), (0, u.jsx)(Mt, {})]
                        }), (0, u.jsx)(fn, {})]
                    })
                })),
                xn = c().bind(Je),
                bn = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.localeService.locale,
                        n = e.service,
                        i = n.orderType,
                        o = n.setOrderType,
                        s = e.sessionService,
                        c = s.login,
                        l = s.getUserInfo,
                        d = e.modalService.showModal,
                        _ = (0, a.useCallback)((function(e) {
                            var t = Number(e.target.value);
                            if (3 === t && (!c || !l || !l.agreeToRsvtTradeNotice)) return o(2), void d(ne.srg);
                            o(t, !0)
                        }), [o, c, l, null === l || void 0 === l ? void 0 : l.agreeToRsvtTradeNotice]);
                    return (0, u.jsxs)("div", {
                        className: xn("buy-sell-tab__row"),
                        children: [(0, u.jsx)("h4", {
                            className: xn("buy-sell-tab__title"),
                            children: t("page.trade.msg127")
                        }), (0, u.jsx)("div", {
                            className: xn("buy-sell-tab__box"),
                            children: (0, u.jsxs)("div", {
                                className: xn("buy-sell-tab-radio"),
                                children: [(0, u.jsxs)("div", {
                                    className: xn("buy-sell-tab-radio__inner"),
                                    children: [(0, u.jsx)("input", {
                                        type: "radio",
                                        id: "buy-sell-tab-2",
                                        name: "orderType",
                                        value: 2,
                                        onChange: _,
                                        className: "blind",
                                        checked: 2 === i
                                    }), (0, u.jsxs)("label", {
                                        htmlFor: "buy-sell-tab-2",
                                        className: xn("buy-sell-tab-radio__label"),
                                        children: [(0, u.jsx)("span", {
                                            className: xn("buy-sell-tab-radio__icon")
                                        }), t("page.trade.msg124")]
                                    })]
                                }), (0, u.jsxs)("div", {
                                    className: xn("buy-sell-tab-radio__inner"),
                                    children: [(0, u.jsx)("input", {
                                        type: "radio",
                                        id: "buy-sell-tab-1",
                                        name: "orderType",
                                        value: 1,
                                        onChange: _,
                                        className: "blind",
                                        checked: 1 === i
                                    }), (0, u.jsxs)("label", {
                                        htmlFor: "buy-sell-tab-1",
                                        className: xn("buy-sell-tab-radio__label"),
                                        children: [(0, u.jsx)("span", {
                                            className: xn("buy-sell-tab-radio__icon")
                                        }), t("page.trade.msg125")]
                                    })]
                                }), (0, u.jsxs)("div", {
                                    className: xn("buy-sell-tab-radio__inner"),
                                    children: [(0, u.jsx)("input", {
                                        type: "radio",
                                        id: "buy-sell-tab-3",
                                        name: "orderType",
                                        value: 3,
                                        onChange: _,
                                        className: "blind",
                                        checked: 3 === i
                                    }), (0, u.jsxs)("label", {
                                        htmlFor: "buy-sell-tab-3",
                                        className: xn("buy-sell-tab-radio__label"),
                                        children: [(0, u.jsx)("span", {
                                            className: xn("buy-sell-tab-radio__icon")
                                        }), t("page.trade.msg126")]
                                    }), (0, u.jsxs)("div", {
                                        className: xn("buy-sell-tab-tip"),
                                        children: [(0, u.jsx)("span", {
                                            className: xn("buy-sell-tab-tip__button"),
                                            children: (0, u.jsx)("span", {
                                                className: "blind",
                                                children: "\uc790\uc138\ud788"
                                            })
                                        }), (0, u.jsxs)("div", {
                                            className: xn("buy-sell-tab-tip-layer"),
                                            children: [(0, u.jsxs)("ul", {
                                                className: xn("buy-sell-tab-tip-layer__list"),
                                                children: [(0, u.jsx)("li", {
                                                    children: t("page.trade.msg153", {
                                                        tagBr: (0, u.jsx)("br", {})
                                                    })
                                                }), (0, u.jsx)("li", {
                                                    children: t("page.trade.msg278", {
                                                        tagBr: (0, u.jsx)("br", {})
                                                    })
                                                })]
                                            }), (0, u.jsx)("strong", {
                                                className: xn("buy-sell-tab-tip-layer__title", "buy-sell-tab-tip-layer__title--red"),
                                                children: t("page.trade.msg154")
                                            }), (0, u.jsx)("p", {
                                                className: xn("buy-sell-tab-tip-layer__text"),
                                                children: t("page.trade.msg155")
                                            }), (0, u.jsx)("strong", {
                                                className: xn("buy-sell-tab-tip-layer__title", "buy-sell-tab-tip-layer__title--blue"),
                                                children: t("page.trade.msg156")
                                            }), (0, u.jsx)("p", {
                                                className: xn("buy-sell-tab-tip-layer__text"),
                                                children: t("page.trade.msg157", {
                                                    tagBr: (0, u.jsx)("br", {})
                                                })
                                            })]
                                        })]
                                    })]
                                })]
                            })
                        })]
                    })
                })),
                vn = c().bind({
                    "simple-notice": "SimpleNotice_simple-notice__sfQyo"
                }),
                yn = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.localeService.locale,
                        n = e.sessionService,
                        i = n.fnRateMillionPrice,
                        s = n.language,
                        c = e.coinService,
                        l = c.getCoin,
                        d = c.getMarket,
                        m = c.getIntroTradeInfo,
                        p = e.service,
                        f = p.buySell,
                        h = p.orderType,
                        g = (0, S.PZ)().getInstance,
                        x = (0, a.useState)(""),
                        b = (0, o.Z)(x, 2),
                        v = b[0],
                        y = b[1];
                    return (0, a.useEffect)((function() {
                        if (1 === h)
                            if (d.crncCd === C.Eo && s === _.DfJ.ko) {
                                var e = m(l.coinType, d.crncCd).marketMaxOrderValue,
                                    t = i(e, d, g(e).isLessThanOrEqualTo(1e8)),
                                    n = t.price,
                                    r = t.unit;
                                y("".concat(n).concat(r).concat((0, C.c0)(d.crncCd)))
                            } else {
                                var o = m(l.coinType, d.crncCd).marketMaxOrderValue;
                                y("".concat(g(o).toFormat()).concat(d.marketSymbol))
                            }
                    }), [h, s, l.coinType, d.crncCd]), 1 === h ? (0, u.jsxs)("ul", {
                        className: vn("simple-notice"),
                        children: [0 === f ? (0, u.jsxs)(u.Fragment, {
                            children: [(0, u.jsx)("li", {
                                children: t("page.trade.msg166")
                            }), (0, u.jsx)("li", {
                                children: t("page.trade.msg167")
                            })]
                        }) : (0, u.jsxs)(u.Fragment, {
                            children: [(0, u.jsx)("li", {
                                children: t("page.trade.msg168")
                            }), (0, u.jsx)("li", {
                                children: t("page.trade.msg169")
                            })]
                        }), (0, u.jsx)("li", {
                            children: t("page.trade.msg170", {
                                text: v
                            })
                        })]
                    }) : null
                })),
                jn = c().bind({
                    warning: "Warnning_warning__1DcCd",
                    warning__title: "Warnning_warning__title__Z56kv",
                    "warning-notice": "Warnning_warning-notice__AkgsC",
                    "warning-notice__item": "Warnning_warning-notice__item__+tZyn",
                    "warning-notice__end-date": "Warnning_warning-notice__end-date__OlwoB"
                }),
                Nn = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.coinService.getCoin,
                        n = e.localeService.locale;
                    return t.infoOnMarket && (t.infoOnMarket.isInvestment || "0" !== t.infoOnMarket.closeExceptedDate) ? (0, u.jsx)("div", {
                        className: jn("warning"),
                        children: "0" === t.infoOnMarket.closeExceptedDate ? (0, u.jsxs)(u.Fragment, {
                            children: [(0, u.jsx)("h3", {
                                className: jn("warning__title"),
                                children: n("page.trade.msg163")
                            }), (0, u.jsxs)("ul", {
                                className: jn("warning-notice"),
                                children: [(0, u.jsx)("li", {
                                    className: jn("warning-notice__item"),
                                    children: n("page.trade.msg164")
                                }), (0, u.jsx)("li", {
                                    className: jn("warning-notice__item"),
                                    children: n("page.trade.msg165")
                                })]
                            })]
                        }) : (0, u.jsxs)(u.Fragment, {
                            children: [(0, u.jsx)("h3", {
                                className: jn("warning__title"),
                                children: n("page.trade.msg211")
                            }), (0, u.jsx)("ul", {
                                className: jn("warning-notice"),
                                children: (0, u.jsxs)("li", {
                                    className: jn("warning-notice__item"),
                                    children: [(0, u.jsx)("strong", {
                                        className: jn("warning-notice__end-date"),
                                        children: T()(t.infoOnMarket.closeExceptedDate).format("YYYY-MM-DD HH:mm")
                                    }), n("page.trade.msg212", {
                                        tag: (0, u.jsx)("br", {})
                                    })]
                                })
                            })]
                        })
                    }) : null
                })),
                kn = c().bind(Je),
                Cn = function() {
                    return (0, u.jsxs)("div", {
                        className: kn("buy-sell-tab"),
                        children: [(0, u.jsx)(bn, {}), (0, u.jsxs)("div", {
                            className: kn("buy-sell-tab__row"),
                            children: [(0, u.jsx)(et, {}), (0, u.jsx)(Ot, {})]
                        }), (0, u.jsx)(gn, {}), (0, u.jsx)(wt, {}), (0, u.jsx)(Nn, {}), (0, u.jsx)(ft, {}), (0, u.jsx)(yn, {})]
                    })
                },
                Sn = (0, a.memo)(Cn),
                wn = c().bind({
                    orders: "Orders_orders__Rov3S",
                    "orders-tab": "Orders_orders-tab__tsCWS",
                    "orders-tab__item": "Orders_orders-tab__item__Oqc05",
                    "orders-tab__button": "Orders_orders-tab__button__bSVbC"
                }),
                Tn = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.service,
                        n = t.buySell,
                        i = t.setBuySell,
                        o = e.coinService.getCoin,
                        s = e.localeService.locale,
                        c = (0, a.useCallback)((function(e) {
                            i(e, !0)
                        }), []);
                    return (0, u.jsxs)("div", {
                        className: wn("orders"),
                        children: [(0, u.jsxs)("ul", {
                            className: wn("orders-tab"),
                            role: "tablist",
                            children: [(0, u.jsx)("li", {
                                className: wn("orders-tab__item"),
                                role: "tab",
                                "aria-selected": 0 === n,
                                children: (0, u.jsx)(g.ZP, {
                                    className: wn("orders-tab__button"),
                                    onClick: function() {
                                        return c(0)
                                    },
                                    children: s("page.trade.msg122", {
                                        symbol: o.coinSymbol
                                    })
                                })
                            }), (0, u.jsx)("li", {
                                className: wn("orders-tab__item"),
                                role: "tab",
                                "aria-selected": 1 === n,
                                children: (0, u.jsx)(g.ZP, {
                                    className: wn("orders-tab__button"),
                                    onClick: function() {
                                        return c(1)
                                    },
                                    children: s("page.trade.msg123", {
                                        symbol: o.coinSymbol
                                    })
                                })
                            })]
                        }), (0, u.jsxs)("h2", {
                            className: "blind",
                            children: [0 === n && s("page.trade.msg122", {
                                symbol: o.coinSymbol
                            }), 1 === n && s("page.trade.msg123", {
                                symbol: o.coinSymbol
                            })]
                        }), (0, u.jsx)(Sn, {})]
                    })
                })),
                Pn = c().bind({
                    "trade-asset": "TradeAsset_trade-asset__4y0mB",
                    "trade-asset__title": "TradeAsset_trade-asset__title__Z+Ynb",
                    "trade-asset__box-wrap": "TradeAsset_trade-asset__box-wrap__5uGss",
                    "trade-asset__box": "TradeAsset_trade-asset__box__zJrmq",
                    "trade-asset__button": "TradeAsset_trade-asset__button__kg2xT",
                    "trade-asset-info": "TradeAsset_trade-asset-info__W77xs",
                    "trade-asset-info__col": "TradeAsset_trade-asset-info__col__NBMul"
                }),
                In = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.coinService,
                        n = t.getCoin,
                        i = t.getMarket,
                        s = e.assetService,
                        c = s.getAssets,
                        l = s.getCoinAsset,
                        d = e.sessionService,
                        _ = d.login,
                        m = d.language,
                        p = e.localeService.locale,
                        f = (0, S.PZ)().getInstance,
                        h = (0, a.useState)({
                            coinAvailable: "0",
                            coinUseWait: "0",
                            marketAvailable: "0",
                            marketUseWait: "0"
                        }),
                        x = (0, o.Z)(h, 2),
                        b = x[0],
                        v = x[1],
                        y = i.crncCd === C.Eo;
                    return (0, a.useEffect)((function() {
                        if (_ && c) {
                            var e = l(i.crncCd),
                                t = l(n.coinType);
                            v((0, pe.Z)((0, pe.Z)({}, b), {}, {
                                coinAvailable: (null === t || void 0 === t ? void 0 : t.available) || "0",
                                marketAvailable: (null === e || void 0 === e ? void 0 : e.available) || "0",
                                coinUseWait: f(null === t || void 0 === t ? void 0 : t.useWait).plus(f((null === t || void 0 === t ? void 0 : t.outAmt) || 0)).toFixed(),
                                marketUseWait: y ? f(null === e || void 0 === e ? void 0 : e.coinBalance).minus(f(null === e || void 0 === e ? void 0 : e.available)).toFixed() : (null === e || void 0 === e ? void 0 : e.useWait) || "0"
                            }))
                        } else v({
                            coinAvailable: "0",
                            coinUseWait: "0",
                            marketAvailable: "0",
                            marketUseWait: "0"
                        })
                    }), [_, l(i.crncCd), l(n.coinType)]), (0, u.jsxs)("div", {
                        className: Pn("trade-asset"),
                        children: [(0, u.jsx)("h2", {
                            className: Pn("trade-asset__title"),
                            children: p("page.trade.msg018")
                        }), (0, u.jsxs)("div", {
                            className: Pn("trade-asset__box-wrap"),
                            children: [(0, u.jsxs)("div", {
                                className: Pn("trade-asset__box"),
                                children: [(0, u.jsxs)("dl", {
                                    className: Pn("trade-asset-info"),
                                    children: [(0, u.jsxs)("div", {
                                        className: Pn("trade-asset-info__col"),
                                        children: [(0, u.jsx)("dt", {
                                            children: p("page.trade.msg019", {
                                                symbol: n.coinSymbol
                                            })
                                        }), (0, u.jsxs)("dd", {
                                            children: [f(b.coinAvailable).toFormat(), n.coinSymbol]
                                        })]
                                    }), (0, u.jsxs)("div", {
                                        className: Pn("trade-asset-info__col"),
                                        children: [(0, u.jsx)("dt", {
                                            children: p("page.trade.msg020")
                                        }), (0, u.jsxs)("dd", {
                                            children: [f(b.coinUseWait).toFormat(), n.coinSymbol]
                                        })]
                                    })]
                                }), (0, u.jsx)(g.ZP, {
                                    className: Pn("trade-asset__button"),
                                    to: "/inout/deposit/".concat(n.coinSymbol),
                                    children: p("page.trade.msg021", {
                                        tag: (0, u.jsx)("span", {}),
                                        symbol: n.coinSymbol
                                    })
                                })]
                            }), (0, u.jsxs)("div", {
                                className: Pn("trade-asset__box"),
                                children: [(0, u.jsxs)("dl", {
                                    className: Pn("trade-asset-info"),
                                    children: [(0, u.jsxs)("div", {
                                        className: Pn("trade-asset-info__col"),
                                        children: [(0, u.jsx)("dt", {
                                            children: p("page.trade.msg019", {
                                                symbol: (0, C.c0)(i.marketSymbol, m, !0)
                                            })
                                        }), (0, u.jsxs)("dd", {
                                            children: [f(b.marketAvailable).toFormat(), (0, C.c0)(i.marketSymbol, m)]
                                        })]
                                    }), (0, u.jsxs)("div", {
                                        className: Pn("trade-asset-info__col"),
                                        children: [(0, u.jsx)("dt", {
                                            children: p("page.trade.msg020")
                                        }), (0, u.jsxs)("dd", {
                                            children: [f(b.marketUseWait).toFormat(), (0, C.c0)(i.marketSymbol, m)]
                                        })]
                                    })]
                                }), (0, u.jsx)(g.ZP, {
                                    className: Pn("trade-asset__button"),
                                    to: y ? "/inout/deposit/KRW" : "/inout/deposit/".concat(i.marketSymbol),
                                    children: p("page.trade.msg021", {
                                        tag: (0, u.jsx)("span", {}),
                                        symbol: (0, C.c0)(i.marketSymbol, m, !0)
                                    })
                                })]
                            })]
                        })]
                    })
                })),
                Dn = n(61879),
                On = n(11792),
                Zn = n(99527),
                An = n(64266),
                Fn = c().bind({
                    "trade-setting": "TradeSetting_trade-setting__dl56p",
                    "trade-setting__button": "TradeSetting_trade-setting__button__-vdIa",
                    "trade-setting-layer": "TradeSetting_trade-setting-layer__GzB+H",
                    "trade-setting-layer__close": "TradeSetting_trade-setting-layer__close__Njl+Z",
                    "trade-setting-layer-check": "TradeSetting_trade-setting-layer-check__vGUoo",
                    "trade-setting-layer-check__item-text": "TradeSetting_trade-setting-layer-check__item-text__mLwDE",
                    "trade-setting-layer-check__item": "TradeSetting_trade-setting-layer-check__item__KJBLN",
                    "trade-setting-layer-toggle": "TradeSetting_trade-setting-layer-toggle__qKONn",
                    "trade-setting-layer-toggle__item": "TradeSetting_trade-setting-layer-toggle__item__OEAJk",
                    "trade-setting-layer-toggle__item-text": "TradeSetting_trade-setting-layer-toggle__item-text__VfOIT"
                }),
                Mn = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.sessionService,
                        n = t.setCustom,
                        i = t.getCustom,
                        s = e.localeService.locale,
                        c = e.gaService.fnGASendEvent,
                        l = (0, a.useState)(!1),
                        d = (0, o.Z)(l, 2),
                        m = d[0],
                        p = d[1],
                        f = (0, a.useMemo)((function() {
                            return i(_._28.orderBookColor) ? _.FTO.rate : _.FTO.askbid
                        }), [i(_._28.orderBookColor)]);
                    return (0, u.jsxs)(u.Fragment, {
                        children: [(0, u.jsx)(Zn.q, {
                            defer: !1,
                            children: (0, u.jsx)("html", {
                                "data-theme": i(_._28.theme) ? _.XYX.dark : _.XYX.light,
                                "data-orderBookColor": f
                            })
                        }), (0, u.jsxs)("div", {
                            className: Fn("trade-setting"),
                            children: [(0, u.jsx)(g.ZP, {
                                className: Fn("trade-setting__button"),
                                "aria-haspopup": "dialog",
                                "aria-expanded": m,
                                "aria-controls": "tradeSettingLayer",
                                onClick: function() {
                                    return p(!m)
                                },
                                children: s("page.trade.msg008")
                            }), (0, u.jsxs)("div", {
                                id: "tradeSettingLayer",
                                className: Fn("trade-setting-layer"),
                                "aria-hidden": !m,
                                role: "dialog",
                                children: [(0, u.jsx)(g.ZP, {
                                    className: Fn("trade-setting-layer__close"),
                                    onClick: function() {
                                        return p(!1)
                                    },
                                    "aria-label": "\ub2eb\uae30"
                                }), (0, u.jsxs)("ul", {
                                    className: Fn("trade-setting-layer-check"),
                                    children: [(0, u.jsxs)("li", {
                                        className: Fn("trade-setting-layer-check__item"),
                                        children: [(0, u.jsx)("strong", {
                                            className: Fn("trade-setting-layer-check__item-text"),
                                            children: s("page.trade.msg009")
                                        }), (0, u.jsx)(U.ZP, {
                                            label: s("page.trade.msg009"),
                                            onChange: function(e) {
                                                c("\uc8fc\ubb38", "\uac70\ub798\ud654\uba74\uc124\uc815", "\ub098\uc774\ud2b8\ubaa8\ub4dc_".concat(e.target.checked ? "on" : "off")), n(_._28.theme, e.target.checked)
                                            },
                                            type: U.Su.Switch,
                                            size: U.VD.Size18,
                                            checked: i(_._28.theme)
                                        })]
                                    }), (0, u.jsxs)("li", {
                                        className: Fn("trade-setting-layer-check__item"),
                                        children: [(0, u.jsx)("strong", {
                                            className: Fn("trade-setting-layer-check__item-text"),
                                            children: s("page.trade.msg010")
                                        }), (0, u.jsx)(U.ZP, {
                                            label: s("page.trade.msg010"),
                                            onChange: function(e) {
                                                c("\uc8fc\ubb38", "\uac70\ub798\ud654\uba74\uc124\uc815", "\ucc28\ud2b8\ub178\ucd9c_".concat(e.target.checked ? "on" : "off")), n(_._28.hideChart, !e.target.checked)
                                            },
                                            type: U.Su.Switch,
                                            size: U.VD.Size18,
                                            checked: !i(_._28.hideChart)
                                        })]
                                    }), (0, u.jsxs)("li", {
                                        className: Fn("trade-setting-layer-check__item"),
                                        children: [(0, u.jsx)("strong", {
                                            className: Fn("trade-setting-layer-check__item-text"),
                                            children: s("page.trade.msg011")
                                        }), (0, u.jsx)(U.ZP, {
                                            label: s("page.trade.msg011"),
                                            onChange: function(e) {
                                                c("\uc8fc\ubb38", "\uac70\ub798\ud654\uba74\uc124\uc815", "\uc8fc\ubb38\ud655\uc778\ucc3d\ubcf4\uae30_".concat(e.target.checked ? "on" : "off")), n(_._28.hideOrderConfirm, !e.target.checked)
                                            },
                                            type: U.Su.Switch,
                                            size: U.VD.Size18,
                                            checked: !i(_._28.hideOrderConfirm)
                                        })]
                                    })]
                                }), (0, u.jsx)("ul", {
                                    className: Fn("trade-setting-layer-toggle"),
                                    children: (0, u.jsxs)("li", {
                                        className: Fn("trade-setting-layer-toggle__item"),
                                        children: [(0, u.jsx)("strong", {
                                            className: Fn("trade-setting-layer-toggle__item-text"),
                                            children: s("page.trade.msg012")
                                        }), (0, u.jsx)(An.Z, {
                                            toggleType: An.n.Default,
                                            onChange: function(e) {
                                                n(_._28.orderBookColor, e === _.FTO.rate)
                                            },
                                            value: f,
                                            options: [{
                                                label: s("page.trade.msg013"),
                                                value: _.FTO.rate
                                            }, {
                                                label: s("page.trade.msg014"),
                                                value: _.FTO.askbid
                                            }]
                                        })]
                                    })
                                })]
                            })]
                        })]
                    })
                })),
                Rn = c().bind({
                    "trade-header": "TradeHeader_trade-header__Nh6-v",
                    "trade-header__box": "TradeHeader_trade-header__box__o2MRG",
                    "trade-header__title-box": "TradeHeader_trade-header__title-box__yreDe",
                    "title-header__title": "TradeHeader_title-header__title__NzhFs",
                    "title-header__title-sub": "TradeHeader_title-header__title-sub__09hMx",
                    "trade-header__auto-trading-button": "TradeHeader_trade-header__auto-trading-button__23pDE"
                }),
                Bn = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.coinService,
                        n = t.getCoin,
                        i = t.getMarket,
                        s = t.getAjaxTradeInfoCoin,
                        c = e.sessionService.language,
                        l = e.localeService.locale,
                        d = (0, a.useState)(""),
                        m = (0, o.Z)(d, 2),
                        p = m[0],
                        f = m[1];
                    return (0, a.useEffect)((function() {
                        (0, On.Z)((0, Dn.Z)().mark((function e() {
                            var t;
                            return (0, Dn.Z)().wrap((function(e) {
                                for (;;) switch (e.prev = e.next) {
                                    case 0:
                                        return e.next = 2, s(n.coinType, i.crncCd);
                                    case 2:
                                        (t = e.sent) && 200 === t.status && t.data && f(t.data.description);
                                    case 4:
                                    case "end":
                                        return e.stop()
                                }
                            }), e)
                        })))()
                    }), [n.coinType, n.coinSymbol, i.crncCd]), (0, u.jsxs)("div", {
                        className: Rn("trade-header"),
                        children: [(0, u.jsxs)("div", {
                            className: Rn("trade-header__box"),
                            children: [(0, u.jsxs)("div", {
                                className: Rn("trade-header__title-box"),
                                children: [(0, u.jsx)("h1", {
                                    className: Rn("title-header__title"),
                                    children: c === _.DfJ.ko ? n.coinName : n.coinNameEn
                                }), (0, u.jsxs)("span", {
                                    className: Rn("title-header__title-sub"),
                                    children: [n.coinSymbol, " / ", i.marketSymbol]
                                })]
                            }), (0, u.jsx)("div", {
                                className: "blind",
                                children: p
                            })]
                        }), (0, u.jsxs)("div", {
                            className: Rn("trade-header__box"),
                            children: [(0, u.jsx)(g.ZP, {
                                className: Rn("trade-header__auto-trading-button"),
                                to: "/legacy/trade/auto_trading/".concat(n.coinSymbol, "_").concat(i.marketSymbol),
                                children: l("page.trade.msg007")
                            }), (0, u.jsx)(Mn, {})]
                        })]
                    })
                })),
                Ln = n(45329),
                En = n(80262),
                Un = c().bind({
                    "confirm-guide-lending-modal__content": "ConfirmGuideLendingModal_confirm-guide-lending-modal__content__Mbf8J",
                    "confirm-guide-lending-modal__title": "ConfirmGuideLendingModal_confirm-guide-lending-modal__title__RB+B+",
                    "confirm-guide-lending-modal__paragraph": "ConfirmGuideLendingModal_confirm-guide-lending-modal__paragraph__Z3ejD",
                    "confirm-guide-lending-modal__tip": "ConfirmGuideLendingModal_confirm-guide-lending-modal__tip__KySJg",
                    "confirm-guide-lending-modal__next": "ConfirmGuideLendingModal_confirm-guide-lending-modal__next__fDAf+"
                }),
                qn = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.localeService.locale,
                        n = e.modalService,
                        i = n.visible,
                        o = n.hideModal,
                        a = e.httpService.post,
                        s = (0, d.s0)(),
                        c = i(ne.rmV),
                        l = c && c.params;
                    return (0, u.jsxs)(ee.ZP, {
                        visible: c,
                        type: ee.y7.Notice,
                        hideButton: !0,
                        className: Un("confirm-guide-lending-modal"),
                        children: [(0, u.jsxs)("div", {
                            className: Un("confirm-guide-lending-modal__content"),
                            children: [(0, u.jsxs)("h2", {
                                className: Un("confirm-guide-lending-modal__title"),
                                children: [l && (null === l || void 0 === l ? void 0 : l.bIsBuy) && t("page.trade.msg270"), l && !1 === (null === l || void 0 === l ? void 0 : l.bIsBuy) && t("page.trade.msg272")]
                            }), (0, u.jsxs)("p", {
                                className: Un("confirm-guide-lending-modal__paragraph"),
                                children: [l && (null === l || void 0 === l ? void 0 : l.bIsBuy) && t("page.trade.msg273", {
                                    tagBr: (0, u.jsx)("br", {})
                                }), l && !1 === (null === l || void 0 === l ? void 0 : l.bIsBuy) && t("page.trade.msg274", {
                                    tagBr: (0, u.jsx)("br", {})
                                }), (0, u.jsx)("span", {
                                    className: Un("confirm-guide-lending-modal__tip"),
                                    children: t("page.trade.msg271")
                                })]
                            })]
                        }), (0, u.jsx)(te.XY, {
                            className: Un("confirm-guide-lending-modal__next"),
                            modalBtn: {
                                text: t("comm.msg002"),
                                feature: te.p0.CLOSE,
                                customStyle: {
                                    type: g.PD.DefaultNew,
                                    color: g.n5.Secondary,
                                    size: g.VA.ExtraLarge
                                }
                            },
                            modalBtn1: {
                                text: t("page.trade.msg276"),
                                feature: te.p0.CUSTOM,
                                customStyle: {
                                    type: g.PD.DefaultNew,
                                    color: g.n5.Primary,
                                    size: g.VA.ExtraLarge
                                },
                                callback: function() {
                                    s("/lending")
                                }
                            },
                            onClose: function() {
                                return o(ne.rmV)
                            }
                        }), (0, u.jsx)(En.Z, {
                            text: t("page.trade.msg275"),
                            onClick: function() {
                                o(ne.rmV), a("/v1/lending/promote/popup", null, !0).catch((function(e) {
                                    console.error("Request failed", e)
                                }))
                            }
                        })]
                    })
                })),
                Gn = {
                    "coin-info-premium": "PremiumCoinInfo_coin-info-premium__wFGjP",
                    "coin-info-premium-inner": "PremiumCoinInfo_coin-info-premium-inner__OmBTv",
                    "coin-info-premium__head": "PremiumCoinInfo_coin-info-premium__head__QPmjv",
                    "coin-info-premium__head-title": "PremiumCoinInfo_coin-info-premium__head-title__kWSdJ",
                    "coin-info-premium__head-title-img": "PremiumCoinInfo_coin-info-premium__head-title-img__fl04g",
                    "coin-info-premium__head-button-close": "PremiumCoinInfo_coin-info-premium__head-button-close__2vFI0",
                    "coin-info-premium__content": "PremiumCoinInfo_coin-info-premium__content__xcZmA",
                    "coin-info-premium-box": "PremiumCoinInfo_coin-info-premium-box__g306f",
                    distribution__title: "PremiumCoinInfo_distribution__title__36LTC",
                    "distribution__top-area": "PremiumCoinInfo_distribution__top-area__y32iv",
                    "distribution__ticker-box": "PremiumCoinInfo_distribution__ticker-box__HFSbw",
                    fadeIn: "PremiumCoinInfo_fadeIn__j6aQt",
                    distribution__ticker: "PremiumCoinInfo_distribution__ticker__dHyVQ",
                    "distribution__ticker-symbol": "PremiumCoinInfo_distribution__ticker-symbol__yZ-QN",
                    "distribution__top-area-ticker-change-rate": "PremiumCoinInfo_distribution__top-area-ticker-change-rate__jgZt+",
                    "distribution__bottom-area": "PremiumCoinInfo_distribution__bottom-area__DR3rz",
                    "distribution__list-item": "PremiumCoinInfo_distribution__list-item__lclmD",
                    "distribution__list-item-title": "PremiumCoinInfo_distribution__list-item-title__KdV2g",
                    "distribution__list-item-value": "PremiumCoinInfo_distribution__list-item-value__0ppaX",
                    "distribution__list-item-value--type-desc": "PremiumCoinInfo_distribution__list-item-value--type-desc__2Q7Nc",
                    "distribution__list-item-value--type-asc": "PremiumCoinInfo_distribution__list-item-value--type-asc__TWBiS",
                    "distribution__list-item-value--type-data-none": "PremiumCoinInfo_distribution__list-item-value--type-data-none__0EyaQ",
                    "distribution__list-item-unit": "PremiumCoinInfo_distribution__list-item-unit__b1RFZ",
                    "coin-info-premium__tooltip-box": "PremiumCoinInfo_coin-info-premium__tooltip-box__jYd3o",
                    "coin-info-premium__tooltip-button": "PremiumCoinInfo_coin-info-premium__tooltip-button__AMEYH",
                    "coin-info-premium__tooltip-button--active": "PremiumCoinInfo_coin-info-premium__tooltip-button--active__IkjSd",
                    "coin-info-premium__tooltip": "PremiumCoinInfo_coin-info-premium__tooltip__IOtiy",
                    distribution__tooltip: "PremiumCoinInfo_distribution__tooltip__m9-02",
                    "tops-impact__tooltip": "PremiumCoinInfo_tops-impact__tooltip__b-3M9",
                    "info-tips__tooltip": "PremiumCoinInfo_info-tips__tooltip__XqEXJ",
                    "stop-tips__tooltip": "PremiumCoinInfo_stop-tips__tooltip__b4TgU",
                    investment__tooltip: "PremiumCoinInfo_investment__tooltip__o8HVQ",
                    "coin-info-premium__tooltip-inner": "PremiumCoinInfo_coin-info-premium__tooltip-inner__izjA4",
                    "coin-info-premium__tooltip-title": "PremiumCoinInfo_coin-info-premium__tooltip-title__915L0",
                    "coin-info-premium__tooltip-text": "PremiumCoinInfo_coin-info-premium__tooltip-text__P8ESX",
                    "coin-info-premium__tooltip-list-text": "PremiumCoinInfo_coin-info-premium__tooltip-list-text__DjrJQ",
                    "coin-info-premium-caution": "PremiumCoinInfo_coin-info-premium-caution__cmCKh",
                    "coin-info-premium-caution__paragraph": "PremiumCoinInfo_coin-info-premium-caution__paragraph__BDg+i",
                    "related-info": "PremiumCoinInfo_related-info__ommd9",
                    "related-info__item": "PremiumCoinInfo_related-info__item__U7lgY",
                    "related-info__item-title": "PremiumCoinInfo_related-info__item-title__MkNNy",
                    "related-info__item-title-coin-symbol": "PremiumCoinInfo_related-info__item-title-coin-symbol__cyh4+",
                    "related-info__item-value": "PremiumCoinInfo_related-info__item-value__f1FGz",
                    "tops-impact__title": "PremiumCoinInfo_tops-impact__title__dYCtk",
                    "tops-impact__graph-box": "PremiumCoinInfo_tops-impact__graph-box__bjq5Y",
                    "tops-impact__graph-item": "PremiumCoinInfo_tops-impact__graph-item__t6RY5",
                    "tops-impact__graph-item-svg": "PremiumCoinInfo_tops-impact__graph-item-svg__lrmeR",
                    "tops-impact__graph-item-bg": "PremiumCoinInfo_tops-impact__graph-item-bg__Ed++k",
                    "tops-impact__graph-item-bar": "PremiumCoinInfo_tops-impact__graph-item-bar__4ZCHu",
                    "tops-impact__graph-item--color-red": "PremiumCoinInfo_tops-impact__graph-item--color-red__ntJdK",
                    "tops-impact__graph-item--color-gray": "PremiumCoinInfo_tops-impact__graph-item--color-gray__JAW-C",
                    "tops-impact__graph-item-text-value-num": "PremiumCoinInfo_tops-impact__graph-item-text-value-num__JzXZf",
                    "tops-impact__graph-item-text-value-unit": "PremiumCoinInfo_tops-impact__graph-item-text-value-unit__WDmJQ",
                    "tops-impact__graph-item-text": "PremiumCoinInfo_tops-impact__graph-item-text__wJJE9",
                    "tops-impact__graph-item-text--fade-in": "PremiumCoinInfo_tops-impact__graph-item-text--fade-in__krId0",
                    "tops-impact__graph-item-text-value": "PremiumCoinInfo_tops-impact__graph-item-text-value__vWWLF",
                    "tops-impact__graph-item-text-name": "PremiumCoinInfo_tops-impact__graph-item-text-name__J990m",
                    "tops-impact__graph-no-data": "PremiumCoinInfo_tops-impact__graph-no-data__KZw1Q",
                    "tops-impact__graph-no-data-text": "PremiumCoinInfo_tops-impact__graph-no-data-text__D6VKN",
                    "rising-rate__title": "PremiumCoinInfo_rising-rate__title__JFgy1",
                    "rising-rate__list": "PremiumCoinInfo_rising-rate__list__hxJYL",
                    "rising-rate__list-item": "PremiumCoinInfo_rising-rate__list-item__efKzT",
                    "rising-rate__list-item-period": "PremiumCoinInfo_rising-rate__list-item-period__j2yOD",
                    "rising-rate__list-item-rise-rate": "PremiumCoinInfo_rising-rate__list-item-rise-rate__qF5pZ",
                    "rising-rate__list-item-rise-rate--type-desc": "PremiumCoinInfo_rising-rate__list-item-rise-rate--type-desc__-nw-I",
                    "rising-rate__list-item-rise-rate--type-asc": "PremiumCoinInfo_rising-rate__list-item-rise-rate--type-asc__QZ5Bg",
                    "project-news": "PremiumCoinInfo_project-news__juWAp",
                    "project-news__title": "PremiumCoinInfo_project-news__title__GDjlS",
                    "project-news__source": "PremiumCoinInfo_project-news__source__nRCnz",
                    "project-news__source-img": "PremiumCoinInfo_project-news__source-img__A+eQn",
                    "project-news__info": "PremiumCoinInfo_project-news__info__dyMOE",
                    "project-news__info-list-item": "PremiumCoinInfo_project-news__info-list-item__QnH4c",
                    "twitter-retweet": "PremiumCoinInfo_twitter-retweet__npKUz",
                    "twitter-profile__link": "PremiumCoinInfo_twitter-profile__link__Q3uQp",
                    "twitter-profile__img": "PremiumCoinInfo_twitter-profile__img__V4WTQ",
                    "twitter-profile__name": "PremiumCoinInfo_twitter-profile__name__2wsQa",
                    "twitter-content": "PremiumCoinInfo_twitter-content__Cbqif",
                    "twitter-content__link": "PremiumCoinInfo_twitter-content__link__uU8J-",
                    "twitter-text": "PremiumCoinInfo_twitter-text__OD5Wx",
                    "twitter-image": "PremiumCoinInfo_twitter-image__ubTKQ",
                    "twitter-image-thumbnail": "PremiumCoinInfo_twitter-image-thumbnail__2g+d1",
                    "twitter-image__inner": "PremiumCoinInfo_twitter-image__inner__fGLka",
                    "twitter-image__image": "PremiumCoinInfo_twitter-image__image__df3zY",
                    "twitter-upload": "PremiumCoinInfo_twitter-upload__QD3jj",
                    investment__title: "PremiumCoinInfo_investment__title__56ULR",
                    "hold-rate": "PremiumCoinInfo_hold-rate__GWuQO",
                    "hold-rate__title-box": "PremiumCoinInfo_hold-rate__title-box__CNH68",
                    "hold-rate__title": "PremiumCoinInfo_hold-rate__title__Yh8zo",
                    "hold-rate__graph": "PremiumCoinInfo_hold-rate__graph__MiPOY",
                    "invest-trends": "PremiumCoinInfo_invest-trends__rKxSf",
                    "invest-trends__title-box": "PremiumCoinInfo_invest-trends__title-box__urMh8",
                    "invest-trends__title": "PremiumCoinInfo_invest-trends__title__tCKUF",
                    "invest-trends__title-sub-text": "PremiumCoinInfo_invest-trends__title-sub-text__VR+v5",
                    "invest-trends__graph": "PremiumCoinInfo_invest-trends__graph__inKAo",
                    "invest-trends-notice": "PremiumCoinInfo_invest-trends-notice__YuVAZ",
                    "invest-trends-notice__text": "PremiumCoinInfo_invest-trends-notice__text__IQC2h",
                    "trade-rate": "PremiumCoinInfo_trade-rate__NgcI9",
                    "trade-rate__title-box": "PremiumCoinInfo_trade-rate__title-box__T3e2w",
                    "trade-rate__title": "PremiumCoinInfo_trade-rate__title__aEJYp",
                    "trade-rate__title-sub-text": "PremiumCoinInfo_trade-rate__title-sub-text__lD0Pu",
                    "trade-rate-chart": "PremiumCoinInfo_trade-rate-chart__KEhjg",
                    "trade-rate-chart__bar-box": "PremiumCoinInfo_trade-rate-chart__bar-box__jtUb7",
                    "trade-rate-chart__bar": "PremiumCoinInfo_trade-rate-chart__bar__2cPD-",
                    "trade-rate-chart__bar--sell": "PremiumCoinInfo_trade-rate-chart__bar--sell__1TD3F",
                    "trade-rate-chart__bar--buy": "PremiumCoinInfo_trade-rate-chart__bar--buy__iizp4",
                    "trade-rate-chart__bar-point": "PremiumCoinInfo_trade-rate-chart__bar-point__8o7v0",
                    "trade-rate-chart__label-box": "PremiumCoinInfo_trade-rate-chart__label-box__GQ+kC",
                    "trade-rate-chart__label": "PremiumCoinInfo_trade-rate-chart__label__LcObF",
                    "trade-rate-chart__label--sell": "PremiumCoinInfo_trade-rate-chart__label--sell__wio29",
                    "trade-rate-chart__label--buy": "PremiumCoinInfo_trade-rate-chart__label--buy__emUSX",
                    "trade-rate-chart__label-num-box": "PremiumCoinInfo_trade-rate-chart__label-num-box__-co1b",
                    "trade-rate-notice": "PremiumCoinInfo_trade-rate-notice__h9LZf",
                    "trade-rate-notice__text": "PremiumCoinInfo_trade-rate-notice__text__RNVad",
                    investment__link: "PremiumCoinInfo_investment__link__+HImW",
                    "coin-info-premium__head-tab": "PremiumCoinInfo_coin-info-premium__head-tab__6zCM3",
                    "coin-info-tab": "PremiumCoinInfo_coin-info-tab__Lm7k+",
                    "coin-info-tab__item": "PremiumCoinInfo_coin-info-tab__item__4cwoD",
                    "coin-info-tab__button-text": "PremiumCoinInfo_coin-info-tab__button-text__ziyvW",
                    "coin-info-tab__button--img": "PremiumCoinInfo_coin-info-tab__button--img__3F3xn",
                    "coin-info-tab__button": "PremiumCoinInfo_coin-info-tab__button__i2Md5",
                    "coin-info-box": "PremiumCoinInfo_coin-info-box__q53gP",
                    "coin-info__title-box": "PremiumCoinInfo_coin-info__title-box__sU7Ot",
                    "coin-info__title": "PremiumCoinInfo_coin-info__title__ELNLq",
                    "coin-info__title-button": "PremiumCoinInfo_coin-info__title-button__pAE2O",
                    "coin-info__title-sub-text": "PremiumCoinInfo_coin-info__title-sub-text__ZhBhu",
                    "coin-info__info": "PremiumCoinInfo_coin-info__info__lhNPR",
                    "coin-info__info-inner": "PremiumCoinInfo_coin-info__info-inner__b9hjT",
                    "coin-info__info-inner--border": "PremiumCoinInfo_coin-info__info-inner--border__kLEk2",
                    "coin-info__info-title": "PremiumCoinInfo_coin-info__info-title__KQWT+",
                    "coin-info__info-text": "PremiumCoinInfo_coin-info__info-text__v6gGh",
                    "coin-info__info-text--flexible": "PremiumCoinInfo_coin-info__info-text--flexible__+c+FD",
                    "coin-info__tip-button": "PremiumCoinInfo_coin-info__tip-button__TXZRn",
                    "coin-info__tip-button--active": "PremiumCoinInfo_coin-info__tip-button--active__Ux8RE",
                    "coin-info-tip-layer": "PremiumCoinInfo_coin-info-tip-layer__p-8Uu",
                    "coin-info-tip-layer--active": "PremiumCoinInfo_coin-info-tip-layer--active__iCKAO",
                    "coin-info-tip-layer__text": "PremiumCoinInfo_coin-info-tip-layer__text__VNk-R",
                    "coin-info__info-text-unit": "PremiumCoinInfo_coin-info__info-text-unit__cOiER",
                    "coin-info__auto-scale-box": "PremiumCoinInfo_coin-info__auto-scale-box__5cLwg",
                    "coin-info__auto-scale": "PremiumCoinInfo_coin-info__auto-scale__arXXR",
                    "coin-info__link-box": "PremiumCoinInfo_coin-info__link-box__CWdcU",
                    "coin-info__link": "PremiumCoinInfo_coin-info__link__mFgfy",
                    "coin-info__text": "PremiumCoinInfo_coin-info__text__+wi28",
                    "coin-info__table": "PremiumCoinInfo_coin-info__table__R8ntT",
                    "coin-info__table-box": "PremiumCoinInfo_coin-info__table-box__mAzlC",
                    "coin-info__table-text-dot": "PremiumCoinInfo_coin-info__table-text-dot__eByug",
                    "coin-info__table-text-dot--status-normal": "PremiumCoinInfo_coin-info__table-text-dot--status-normal__P+nnU",
                    "coin-info__table-text-dot--status-stop": "PremiumCoinInfo_coin-info__table-text-dot--status-stop__45iu8",
                    "coin-info__info-guide": "PremiumCoinInfo_coin-info__info-guide__0SA0t",
                    "coin-distribution-skeleton__head": "PremiumCoinInfo_coin-distribution-skeleton__head__LCn7Z",
                    "coin-distribution-skeleton__head-shape-01": "PremiumCoinInfo_coin-distribution-skeleton__head-shape-01__rjoaO",
                    "coin-distribution-skeleton__head-shape-02": "PremiumCoinInfo_coin-distribution-skeleton__head-shape-02__2nPSR",
                    "coin-distribution-skeleton__head-shape-03": "PremiumCoinInfo_coin-distribution-skeleton__head-shape-03__lqTUr",
                    "coin-distribution-skeleton__body": "PremiumCoinInfo_coin-distribution-skeleton__body__1NwNW",
                    "coin-distribution-skeleton__body-shape-01": "PremiumCoinInfo_coin-distribution-skeleton__body-shape-01__5lSJU",
                    "coin-distribution-skeleton__body-shape-02": "PremiumCoinInfo_coin-distribution-skeleton__body-shape-02__MAvT5",
                    "coin-related-skeleton": "PremiumCoinInfo_coin-related-skeleton__6lv5-",
                    "coin-related-skeleton__card": "PremiumCoinInfo_coin-related-skeleton__card__7sbYR",
                    "coin-related-skeleton__shape-01": "PremiumCoinInfo_coin-related-skeleton__shape-01__01ct+",
                    "coin-related-skeleton__shape-02": "PremiumCoinInfo_coin-related-skeleton__shape-02__rYHzE",
                    "coin-impact-skeleton": "PremiumCoinInfo_coin-impact-skeleton__fJL-1",
                    "coin-impact-skeleton__body": "PremiumCoinInfo_coin-impact-skeleton__body__CcZpm",
                    "coin-impact-skeleton__shape-01": "PremiumCoinInfo_coin-impact-skeleton__shape-01__S-MHU",
                    "coin-impact-skeleton__shape-02": "PremiumCoinInfo_coin-impact-skeleton__shape-02__Z+mbK",
                    "coin-impact-skeleton__shape-03": "PremiumCoinInfo_coin-impact-skeleton__shape-03__LNVPq",
                    "coin-impact-skeleton__donut-outer": "PremiumCoinInfo_coin-impact-skeleton__donut-outer__Yxgt6",
                    "coin-impact-skeleton__donut-inner": "PremiumCoinInfo_coin-impact-skeleton__donut-inner__vBW2e",
                    "coin-impact-skeleton__donut-01": "PremiumCoinInfo_coin-impact-skeleton__donut-01__ymWDY",
                    "coin-investment-skeleton": "PremiumCoinInfo_coin-investment-skeleton__GgiRc",
                    "coin-investment-skeleton__shape-01": "PremiumCoinInfo_coin-investment-skeleton__shape-01__XMooS",
                    "coin-investment-skeleton__body": "PremiumCoinInfo_coin-investment-skeleton__body__gnwx4",
                    "coin-investment-skeleton__body-card": "PremiumCoinInfo_coin-investment-skeleton__body-card__HmDGg",
                    "coin-investment-skeleton__shape-02": "PremiumCoinInfo_coin-investment-skeleton__shape-02__WCYMA",
                    "coin-investment-skeleton__shape-03": "PremiumCoinInfo_coin-investment-skeleton__shape-03__vwwLa",
                    "coin-investment-skeleton__graph": "PremiumCoinInfo_coin-investment-skeleton__graph__wLiyF",
                    "coin-investment-skeleton__graph-title": "PremiumCoinInfo_coin-investment-skeleton__graph-title__aVq4K",
                    "coin-investment-skeleton__graph-body": "PremiumCoinInfo_coin-investment-skeleton__graph-body__QACCw",
                    "coin-investment-skeleton__graph-value": "PremiumCoinInfo_coin-investment-skeleton__graph-value__f5Hmx",
                    "coin-investment-skeleton__shape-04": "PremiumCoinInfo_coin-investment-skeleton__shape-04__Z8ycx",
                    "coin-investment-skeleton__shape-05": "PremiumCoinInfo_coin-investment-skeleton__shape-05__7tYxB",
                    "coin-investment-skeleton__shape-06": "PremiumCoinInfo_coin-investment-skeleton__shape-06__SCrlj",
                    "coin-investment-skeleton__shape-07": "PremiumCoinInfo_coin-investment-skeleton__shape-07__i4Y1-",
                    "coin-investment-skeleton__more": "PremiumCoinInfo_coin-investment-skeleton__more__z3IAe",
                    "coin-investment-skeleton__shape-08": "PremiumCoinInfo_coin-investment-skeleton__shape-08__7AvdQ",
                    "coin-rising-skeleton": "PremiumCoinInfo_coin-rising-skeleton__9OqUy",
                    "coin-rising-skeleton__shape-01": "PremiumCoinInfo_coin-rising-skeleton__shape-01__EiBFU",
                    "coin-rising-skeleton__table-head": "PremiumCoinInfo_coin-rising-skeleton__table-head__XCiVl",
                    "coin-rising-skeleton__table-body-card": "PremiumCoinInfo_coin-rising-skeleton__table-body-card__66OC0",
                    "coin-rising-skeleton__table-head-cell--align-right": "PremiumCoinInfo_coin-rising-skeleton__table-head-cell--align-right__2wh-t",
                    "coin-rising-skeleton__table-body-cell--align-right": "PremiumCoinInfo_coin-rising-skeleton__table-body-cell--align-right__WeAO4",
                    "coin-rising-skeleton__table-body": "PremiumCoinInfo_coin-rising-skeleton__table-body__l0Iw-",
                    "coin-rising-skeleton__shape-02": "PremiumCoinInfo_coin-rising-skeleton__shape-02__ep3oT",
                    "coin-rising-skeleton__shape-03": "PremiumCoinInfo_coin-rising-skeleton__shape-03__OMZOq",
                    "coin-rising-skeleton__shape-04": "PremiumCoinInfo_coin-rising-skeleton__shape-04__T50JL",
                    "coin-rising-skeleton__shape-05": "PremiumCoinInfo_coin-rising-skeleton__shape-05__vSgqq",
                    "coin-rising-skeleton__shape-06": "PremiumCoinInfo_coin-rising-skeleton__shape-06__RWjqC",
                    "coin-rising-skeleton__shape-07": "PremiumCoinInfo_coin-rising-skeleton__shape-07__OXOIT",
                    "coin-info-skeleton__title": "PremiumCoinInfo_coin-info-skeleton__title__-gABq",
                    "coin-info-skeleton__shape-01": "PremiumCoinInfo_coin-info-skeleton__shape-01__9WTWy",
                    "coin-info-skeleton__shape-02": "PremiumCoinInfo_coin-info-skeleton__shape-02__U0pyY",
                    "coin-info-skeleton__list": "PremiumCoinInfo_coin-info-skeleton__list__wvni6",
                    "coin-info-skeleton__list-card": "PremiumCoinInfo_coin-info-skeleton__list-card__tt8bN",
                    "coin-info-skeleton__list-cell--align-right": "PremiumCoinInfo_coin-info-skeleton__list-cell--align-right__2H4Wm",
                    "coin-info-skeleton__shape-03": "PremiumCoinInfo_coin-info-skeleton__shape-03__sq8sU",
                    "coin-info-skeleton__shape-04": "PremiumCoinInfo_coin-info-skeleton__shape-04__E12hq",
                    "coin-info-skeleton__shape-05": "PremiumCoinInfo_coin-info-skeleton__shape-05__y1w+w",
                    "coin-info-skeleton__shape-06": "PremiumCoinInfo_coin-info-skeleton__shape-06__-l4ZN",
                    "coin-info-skeleton__go": "PremiumCoinInfo_coin-info-skeleton__go__67eoD",
                    "coin-info-skeleton__go-content": "PremiumCoinInfo_coin-info-skeleton__go-content__UsMjh",
                    "coin-info-skeleton__shape-07": "PremiumCoinInfo_coin-info-skeleton__shape-07__bP3gZ",
                    "coin-info-skeleton__shape-08": "PremiumCoinInfo_coin-info-skeleton__shape-08__A25IK",
                    "coin-price-skeleton__shape-01": "PremiumCoinInfo_coin-price-skeleton__shape-01__+BGMr",
                    "coin-price-skeleton__content": "PremiumCoinInfo_coin-price-skeleton__content__C6GUa",
                    "coin-price-skeleton__list": "PremiumCoinInfo_coin-price-skeleton__list__2-JvD",
                    "coin-price-skeleton__list--type-margin-top": "PremiumCoinInfo_coin-price-skeleton__list--type-margin-top__UOlnL",
                    "coin-price-skeleton__list-card": "PremiumCoinInfo_coin-price-skeleton__list-card__uQy3D",
                    "coin-price-skeleton__shape-02": "PremiumCoinInfo_coin-price-skeleton__shape-02__R06Rl",
                    "coin-price-skeleton__shape-03": "PremiumCoinInfo_coin-price-skeleton__shape-03__-6xjs",
                    "coin-price-skeleton__shape-04": "PremiumCoinInfo_coin-price-skeleton__shape-04__CUbYr",
                    "coin-transaction-skeleton": "PremiumCoinInfo_coin-transaction-skeleton__dBbKM",
                    "coin-transaction-skeleton__shape-01": "PremiumCoinInfo_coin-transaction-skeleton__shape-01__TqTD-",
                    "coin-transaction-skeleton__content": "PremiumCoinInfo_coin-transaction-skeleton__content__+Rt-B",
                    "coin-transaction-skeleton__list": "PremiumCoinInfo_coin-transaction-skeleton__list__JdVD1",
                    "coin-transaction-skeleton__list-card": "PremiumCoinInfo_coin-transaction-skeleton__list-card__Yn6l4",
                    "coin-transaction-skeleton__list-content": "PremiumCoinInfo_coin-transaction-skeleton__list-content__E--N6",
                    "coin-transaction-skeleton__shape-02": "PremiumCoinInfo_coin-transaction-skeleton__shape-02__6QyEK",
                    "coin-transaction-skeleton__shape-03": "PremiumCoinInfo_coin-transaction-skeleton__shape-03__PJ5nm",
                    "coin-guide-skeleton__shape-01": "PremiumCoinInfo_coin-guide-skeleton__shape-01__5ZKUG",
                    "coin-guide-skeleton__content": "PremiumCoinInfo_coin-guide-skeleton__content__KlmKx",
                    "coin-guide-skeleton__shape-02": "PremiumCoinInfo_coin-guide-skeleton__shape-02__MMnHu",
                    "coin-guide-skeleton__shape-03": "PremiumCoinInfo_coin-guide-skeleton__shape-03__Yggeo",
                    "coin-news-skeleton": "PremiumCoinInfo_coin-news-skeleton__TCO5m",
                    "coin-news-skeleton__shape-01": "PremiumCoinInfo_coin-news-skeleton__shape-01__BUUNJ",
                    "coin-news-skeleton__content": "PremiumCoinInfo_coin-news-skeleton__content__S0kGj",
                    "coin-news-skeleton__list": "PremiumCoinInfo_coin-news-skeleton__list__hHqme",
                    "coin-news-skeleton__shape-02": "PremiumCoinInfo_coin-news-skeleton__shape-02__TSHU5",
                    "coin-news-skeleton__shape-03": "PremiumCoinInfo_coin-news-skeleton__shape-03__oNEPy",
                    "coin-project-skeleton__shape-01": "PremiumCoinInfo_coin-project-skeleton__shape-01__MbsvS",
                    "coin-project-skeleton__head": "PremiumCoinInfo_coin-project-skeleton__head__lhLJy",
                    "coin-project-skeleton__shape-02": "PremiumCoinInfo_coin-project-skeleton__shape-02__kKr6W",
                    "coin-project-skeleton__shape-03": "PremiumCoinInfo_coin-project-skeleton__shape-03__ZreuH",
                    "coin-project-skeleton__body": "PremiumCoinInfo_coin-project-skeleton__body__AGgVr",
                    "coin-project-skeleton__shape-04": "PremiumCoinInfo_coin-project-skeleton__shape-04__erMVL",
                    "coin-project-skeleton__shape-05": "PremiumCoinInfo_coin-project-skeleton__shape-05__sOmWx",
                    "coin-project-skeleton__shape-06": "PremiumCoinInfo_coin-project-skeleton__shape-06__i+Q4F",
                    "coin-project-skeleton__shape-07": "PremiumCoinInfo_coin-project-skeleton__shape-07__-US8A"
                },
                Hn = n(39045),
                Qn = JSON.parse('{"page.coinInfoGuide.msg001":"\ubcf8 \ub370\uc774\ud130\ub294 \ucf54\uc778\ub9c8\ucf13\ucea1\uc5d0\uc11c \uc81c\uacf5\ub41c \ub370\uc774\ud130\ub85c \uc2e4\uc81c\uc720\ud1b5\ub7c9\uacfc \ucc28\uc774\uac00 \uc788\uc744 \uc218 \uc788\uc2b5\ub2c8\ub2e4.","page.coinInfoGuide.msg002":"\ubcf8 \uc815\ubcf4\ub294 \uac00\uc0c1\uc790\uc0b0 \uac70\ub798\uc9c0\uc6d0 \ubaa8\ubc94 \uc0ac\ub840\uc5d0 \ub530\ub77c \uc815\uae30 \uc5c5\ub370\uc774\ud2b8\ub418\ub098, \uc2dc\uc810 \ucc28\uc774\ub85c \ucd5c\uc2e0 \uc815\ubcf4\uc640 \uc0c1\uc774\ud560 \uc218 \uc788\uc73c\ubbc0\ub85c \uc774\uc6a9\uc5d0 \ucc38\uace0 \ubc14\ub78d\ub2c8\ub2e4."}'),
                Kn = JSON.parse('{"page.coinInfoGuide.msg001":"\ubcf8 \ub370\uc774\ud130\ub294 \ucf54\uc778\ub9c8\ucf13\ucea1\uc5d0\uc11c \uc81c\uacf5\ub41c \ub370\uc774\ud130\ub85c \uc2e4\uc81c\uc720\ud1b5\ub7c9\uacfc \ucc28\uc774\uac00 \uc788\uc744 \uc218 \uc788\uc2b5\ub2c8\ub2e4.","page.coinInfoGuide.msg002":"\ubcf8 \uc815\ubcf4\ub294 \uac00\uc0c1\uc790\uc0b0 \uac70\ub798\uc9c0\uc6d0 \ubaa8\ubc94 \uc0ac\ub840\uc5d0 \ub530\ub77c \uc815\uae30 \uc5c5\ub370\uc774\ud2b8\ub418\ub098, \uc2dc\uc810 \ucc28\uc774\ub85c \ucd5c\uc2e0 \uc815\ubcf4\uc640 \uc0c1\uc774\ud560 \uc218 \uc788\uc73c\ubbc0\ub85c \uc774\uc6a9\uc5d0 \ucc38\uace0 \ubc14\ub78d\ub2c8\ub2e4."}'),
                Yn = n(4908),
                Vn = n(35302),
                Wn = n.n(Vn),
                zn = n(91966),
                Xn = n(23042),
                Jn = n(38619),
                $n = function() {
                    var e = (0, r.G2)(),
                        t = e.httpService.get,
                        n = e.HOST_PUBLIC_INFO,
                        i = function() {
                            var e = (0, On.Z)((0, Dn.Z)().mark((function e() {
                                return (0, Dn.Z)().wrap((function(e) {
                                    for (;;) switch (e.prev = e.next) {
                                        case 0:
                                            return e.abrupt("return", t("/v1/comn/server-time"));
                                        case 1:
                                        case "end":
                                            return e.stop()
                                    }
                                }), e)
                            })));
                            return function() {
                                return e.apply(this, arguments)
                            }
                        }(),
                        o = function() {
                            var e = (0, On.Z)((0, Dn.Z)().mark((function e(n) {
                                var i, r = arguments;
                                return (0, Dn.Z)().wrap((function(e) {
                                    for (;;) switch (e.prev = e.next) {
                                        case 0:
                                            return i = r.length > 1 && void 0 !== r[1] ? r[1] : C.Eo, e.abrupt("return", t("/v1/trade/accumulation/deposit/".concat(n, "-").concat(i), null, !1, null, !0));
                                        case 2:
                                        case "end":
                                            return e.stop()
                                    }
                                }), e)
                            })));
                            return function(t) {
                                return e.apply(this, arguments)
                            }
                        }(),
                        a = function() {
                            var e = (0, On.Z)((0, Dn.Z)().mark((function e(n) {
                                var i, r = arguments;
                                return (0, Dn.Z)().wrap((function(e) {
                                    for (;;) switch (e.prev = e.next) {
                                        case 0:
                                            return i = r.length > 1 && void 0 !== r[1] ? r[1] : C.Eo, e.abrupt("return", t("/v1/trade/purity/deposit/".concat(n, "-").concat(i)));
                                        case 2:
                                        case "end":
                                            return e.stop()
                                    }
                                }), e)
                            })));
                            return function(t) {
                                return e.apply(this, arguments)
                            }
                        }(),
                        s = function() {
                            var e = (0, On.Z)((0, Dn.Z)().mark((function e(n) {
                                return (0, Dn.Z)().wrap((function(e) {
                                    for (;;) switch (e.prev = e.next) {
                                        case 0:
                                            return e.abrupt("return", t("/v1/trade/holders/".concat(n), null, !1, null, !0));
                                        case 1:
                                        case "end":
                                            return e.stop()
                                    }
                                }), e)
                            })));
                            return function(t) {
                                return e.apply(this, arguments)
                            }
                        }(),
                        c = function() {
                            var e = (0, On.Z)((0, Dn.Z)().mark((function e(n) {
                                return (0, Dn.Z)().wrap((function(e) {
                                    for (;;) switch (e.prev = e.next) {
                                        case 0:
                                            return e.abrupt("return", t("/v1/trade/top/holder/share/".concat(n), null, !1, null, !0));
                                        case 1:
                                        case "end":
                                            return e.stop()
                                    }
                                }), e)
                            })));
                            return function(t) {
                                return e.apply(this, arguments)
                            }
                        }(),
                        l = function() {
                            var e = (0, On.Z)((0, Dn.Z)().mark((function e(n) {
                                return (0, Dn.Z)().wrap((function(e) {
                                    for (;;) switch (e.prev = e.next) {
                                        case 0:
                                            return e.abrupt("return", t("/v1/trade/top/trader/share/".concat(n), null, !1, null, !0));
                                        case 1:
                                        case "end":
                                            return e.stop()
                                    }
                                }), e)
                            })));
                            return function(t) {
                                return e.apply(this, arguments)
                            }
                        }(),
                        d = function() {
                            var e = (0, On.Z)((0, Dn.Z)().mark((function e(n, i) {
                                var r;
                                return (0, Dn.Z)().wrap((function(e) {
                                    for (;;) switch (e.prev = e.next) {
                                        case 0:
                                            return r = [6, 29, 89], e.abrupt("return", t("/v1/trade/contract/amount/last/".concat(n, "-").concat(i, "?").concat(Jn.stringify({
                                                day: Object.values(r)
                                            })), null, !1, null, !0));
                                        case 2:
                                        case "end":
                                            return e.stop()
                                    }
                                }), e)
                            })));
                            return function(t, n) {
                                return e.apply(this, arguments)
                            }
                        }(),
                        _ = function() {
                            var e = (0, On.Z)((0, Dn.Z)().mark((function e(t) {
                                return (0, Dn.Z)().wrap((function(e) {
                                    for (;;) switch (e.prev = e.next) {
                                        case 0:
                                            return e.abrupt("return", Fe.Z.get("".concat(n, "/v1/tweets/").concat(t)));
                                        case 1:
                                        case "end":
                                            return e.stop()
                                    }
                                }), e)
                            })));
                            return function(t) {
                                return e.apply(this, arguments)
                            }
                        }(),
                        m = function() {
                            var e = (0, On.Z)((0, Dn.Z)().mark((function e(n, i, r) {
                                return (0, Dn.Z)().wrap((function(e) {
                                    for (;;) switch (e.prev = e.next) {
                                        case 0:
                                            return e.abrupt("return", t("/v2/trade/info-coin/".concat(n, "-").concat(i), {
                                                lang: r
                                            }, !1, null, !0));
                                        case 1:
                                        case "end":
                                            return e.stop()
                                    }
                                }), e)
                            })));
                            return function(t, n, i) {
                                return e.apply(this, arguments)
                            }
                        }(),
                        u = function() {
                            var e = (0, On.Z)((0, Dn.Z)().mark((function e(n) {
                                return (0, Dn.Z)().wrap((function(e) {
                                    for (;;) switch (e.prev = e.next) {
                                        case 0:
                                            return e.abrupt("return", t("/v1/coin-inout/info/".concat(n), null, !1, null, !0));
                                        case 1:
                                        case "end":
                                            return e.stop()
                                    }
                                }), e)
                            })));
                            return function(t) {
                                return e.apply(this, arguments)
                            }
                        }();
                    return {
                        getServerTime: i,
                        getV1AccumulationDepositQuery: function(e) {
                            var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : C.Eo;
                            return (0, Xn.C)({
                                queryKey: ["v1", "trade", "accumulation", "deposit", "".concat(e, "-").concat(t)],
                                queryFn: function() {
                                    return o(e, t)
                                }
                            })
                        },
                        getV1AmountOfDeposit: a,
                        getV1AmountOfDepositQuery: function(e) {
                            var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : C.Eo;
                            return (0, Xn.C)({
                                queryKey: ["trade", "purity", "deposit", e, t],
                                queryFn: function() {
                                    return a(e, t)
                                }
                            })
                        },
                        getV1TopHolderRateQuery: function(e) {
                            return (0, Xn.C)({
                                queryKey: ["/v1", "trade", "top", "holder", "share", "".concat(e)],
                                queryFn: function() {
                                    return c(e)
                                }
                            })
                        },
                        getV1TopTradeRateQuery: function(e) {
                            return (0, Xn.C)({
                                queryKey: ["/v1", "trade", "top", "trader", "share", "".concat(e)],
                                queryFn: function() {
                                    return l(e)
                                }
                            })
                        },
                        getV1ContractAmountLastQuery: function(e, t) {
                            return (0, Xn.C)({
                                queryKey: ["v1", "trade", "contract", "amount", "last", "".concat(e, "-").concat(t)],
                                queryFn: function() {
                                    return d(e, t)
                                }
                            })
                        },
                        getV1HoldersCountQuery: function(e) {
                            return (0, Xn.C)({
                                queryKey: ["v1", "trade", "holders", e],
                                queryFn: function() {
                                    return s(e)
                                }
                            })
                        },
                        getV1CoinTweets: _,
                        getV1CoinTweetsQuery: function(e) {
                            return (0, Xn.C)({
                                queryKey: ["v1", "tweets", e],
                                queryFn: function() {
                                    return _(e)
                                }
                            })
                        },
                        getV2TradeCoinInfo: m,
                        getV2TradeCoinInfoQuery: function() {
                            for (var e = arguments.length, t = new Array(e), n = 0; n < e; n++) t[n] = arguments[n];
                            return (0, Xn.C)({
                                queryKey: ["v2", "trade", "info-coin", t],
                                queryFn: function() {
                                    return m.apply(void 0, t)
                                }
                            })
                        },
                        getV1CoinInoutInfo: u,
                        getV1CoinInoutInfoQuery: function(e) {
                            return (0, Xn.C)({
                                queryKey: ["v1", "coin-inout", "info", e],
                                queryFn: function() {
                                    return u(e)
                                }
                            })
                        }
                    }
                },
                ei = function() {
                    var e = (0, r.G2)(),
                        t = e.coinInfoService.getInfo,
                        n = e.sessionService.language,
                        i = $n().getV2TradeCoinInfoQuery;
                    return (0, zn.k)((0, pe.Z)((0, pe.Z)({}, i(t.coin.coinType, t.market.crncCd, "en" === n ? "english" : "korean")), {}, {
                        select: function(e) {
                            if (200 === e.status) return e.data
                        }
                    })).data
                },
                ti = c().bind(Gn),
                ni = (0, l.Pi)((function() {
                    var e, t = (0, r.G2)().localeService.locale,
                        n = ei();
                    return n ? (0, u.jsxs)("div", {
                        className: ti("coin-info-box"),
                        children: [(0, u.jsx)("h4", {
                            className: ti("coin-info__title"),
                            children: t("page.coinInfo.msg030")
                        }), (0, u.jsx)("p", {
                            className: ti("coin-info__text"),
                            dangerouslySetInnerHTML: {
                                __html: (e = n.description, Wn().sanitize(e).replace(/\n/g, "<br/>"))
                            }
                        })]
                    }) : null
                })),
                ii = function() {
                    return (0, u.jsx)("div", {
                        className: ti("coin-info-box"),
                        children: (0, u.jsxs)("div", {
                            className: ti("coin-guide-skeleton"),
                            children: [(0, u.jsx)(Yn.pD, {
                                className: ti("coin-guide-skeleton__shape-01")
                            }), (0, u.jsxs)("div", {
                                className: ti("coin-guide-skeleton__content"),
                                children: [(0, u.jsx)(Yn.pD, {
                                    className: ti("coin-guide-skeleton__shape-02")
                                }), (0, u.jsx)(Yn.pD, {
                                    className: ti("coin-guide-skeleton__shape-02")
                                }), (0, u.jsx)(Yn.pD, {
                                    className: ti("coin-guide-skeleton__shape-03")
                                })]
                            })]
                        })
                    })
                },
                ri = (0, l.Pi)((function() {
                    var e = (0, r.G2)().localeService.locale;
                    return (0, u.jsxs)("div", {
                        className: ti("coin-info-box"),
                        children: [(0, u.jsx)("h4", {
                            className: ti("coin-info__title"),
                            children: e("page.coinInfo.msg030")
                        }), (0, u.jsx)(k, {
                            caseStyle: i.coinInfo
                        })]
                    })
                })),
                oi = function() {
                    return (0, u.jsx)(Yn.nR, {
                        fallback: (0, u.jsx)(ri, {}),
                        children: (0, u.jsx)(a.Suspense, {
                            fallback: (0, u.jsx)(ii, {}),
                            children: (0, u.jsx)(ni, {})
                        })
                    })
                },
                ai = c().bind(Gn),
                si = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.localeService.locale,
                        n = e.coinInfoService,
                        i = n.getInfo.coin.coinSymbol,
                        o = n.tooltips,
                        a = n.setTooltips,
                        s = (0, S.PZ)().getInstance,
                        c = ei(),
                        l = function(e) {
                            a(e)
                        };
                    if (!c) return null;
                    var d = c.websiteUrl || c.manual || c.whitePaper || c.koWhitePaper,
                        m = [{
                            link: c.websiteUrl,
                            text: t("page.coinInfo.msg007")
                        }, {
                            link: c.manual,
                            text: t("page.coinInfo.msg008")
                        }, {
                            link: c.whitePaper,
                            text: t("page.coinInfo.msg009")
                        }, {
                            link: c.koWhitePaper,
                            text: t("page.coinInfo.msg010")
                        }];
                    return (0, u.jsxs)("div", {
                        className: ai("coin-info-box"),
                        children: [(0, u.jsxs)("div", {
                            className: ai("coin-info__title-box"),
                            children: [(0, u.jsxs)("h4", {
                                className: ai("coin-info__title"),
                                children: [t("page.coinInfo.msg001"), (0, u.jsxs)("div", {
                                    className: ai("coin-info-premium__tooltip-box"),
                                    children: [(0, u.jsx)(g.ZP, {
                                        className: ai("coin-info-premium__tooltip-button", {
                                            "coin-info-premium__tooltip-button--active": o.infoTips
                                        }),
                                        onClick: function() {
                                            return l({
                                                infoTips: !o.infoTips
                                            })
                                        },
                                        onBlur: function() {
                                            return l({
                                                infoTips: !1
                                            })
                                        }
                                    }), o.infoTips && (0, u.jsxs)("div", {
                                        className: ai("coin-info-premium__tooltip", "info-tips__tooltip"),
                                        children: [(0, u.jsxs)("div", {
                                            className: ai("coin-info-premium__tooltip-inner"),
                                            children: [(0, u.jsx)("strong", {
                                                className: ai("coin-info-premium__tooltip-title"),
                                                children: t("page.coinInfo.msg003")
                                            }), (0, u.jsx)("p", {
                                                className: ai("coin-info-premium__tooltip-text"),
                                                children: t("page.coinInfo.msg021")
                                            })]
                                        }), (0, u.jsxs)("div", {
                                            className: ai("coin-info-premium__tooltip-inner"),
                                            children: [(0, u.jsx)("strong", {
                                                className: ai("coin-info-premium__tooltip-title"),
                                                children: t("page.coinInfo.msg004")
                                            }), (0, u.jsx)("p", {
                                                className: ai("coin-info-premium__tooltip-text"),
                                                children: t("page.coinInfo.msg022", {
                                                    tagBr: (0, u.jsx)("br", {})
                                                })
                                            })]
                                        }), (0, u.jsxs)("div", {
                                            className: ai("coin-info-premium__tooltip-inner"),
                                            children: [(0, u.jsx)("strong", {
                                                className: ai("coin-info-premium__tooltip-title"),
                                                children: t("page.coinInfo.msg005")
                                            }), (0, u.jsx)("p", {
                                                className: ai("coin-info-premium__tooltip-text"),
                                                children: t("page.coinInfo.msg023", {
                                                    tagBr: (0, u.jsx)("br", {})
                                                })
                                            })]
                                        })]
                                    })]
                                })]
                            }), (0, u.jsxs)("span", {
                                className: ai("coin-info__title-sub-text"),
                                children: [t("page.coinInfo.msg002"), " ", t("page.coinInfo.msg035", {
                                    date: c.marketStdDatetime
                                })]
                            })]
                        }), (0, u.jsxs)("dl", {
                            className: ai("coin-info__info"),
                            children: [(0, u.jsxs)("div", {
                                className: ai("coin-info__info-inner"),
                                children: [(0, u.jsxs)("dt", {
                                    className: ai("coin-info__info-title"),
                                    children: [t("page.coinInfo.msg003"), (null === c || void 0 === c ? void 0 : c.totalIssueType) === _.Dqv.MANUAL && (0, u.jsx)(g.ZP, {
                                        className: ai("coin-info__tip-button", {
                                            "coin-info__tip-button--active": o.totalIssueTips
                                        }),
                                        onClick: function() {
                                            return l({
                                                totalIssueTips: !o.totalIssueTips
                                            })
                                        },
                                        onBlur: function() {
                                            return l({
                                                totalIssueTips: !1
                                            })
                                        },
                                        children: (0, u.jsx)("span", {
                                            className: "blind",
                                            children: "\uc720\uc758\uc0ac\ud56d"
                                        })
                                    }), (0, u.jsx)("div", {
                                        className: ai("coin-info-tip-layer", {
                                            "coin-info-tip-layer--active": o.totalIssueTips
                                        }),
                                        children: (0, u.jsx)("p", {
                                            className: ai("coin-info-tip-layer__text"),
                                            children: t("page.coinInfo.msg036")
                                        })
                                    })]
                                }), (0, u.jsxs)("dd", {
                                    className: ai("coin-info__info-text", "coin-info__info-text--flexible"),
                                    children: [(0, u.jsx)("strong", {
                                        className: ai("coin-info__auto-scale-box"),
                                        children: (0, u.jsx)(P.Z, {
                                            className: ai("coin-info__auto-scale"),
                                            children: c.totalIssueQty || "-"
                                        })
                                    }), (0, u.jsx)("span", {
                                        className: ai("coin-info__info-text-unit"),
                                        children: c.totalIssueType !== _.Dqv.UNLIMITED && c.totalIssueQty && i
                                    })]
                                })]
                            }), (0, u.jsxs)("div", {
                                className: ai("coin-info__info-inner"),
                                children: [(0, u.jsxs)("dt", {
                                    className: ai("coin-info__info-title"),
                                    children: [t("page.coinInfo.msg004"), (null === c || void 0 === c ? void 0 : c.marketCapUsdType) === _.Dqv.MANUAL && (0, u.jsx)(g.ZP, {
                                        className: ai("coin-info__tip-button", {
                                            "coin-info__tip-button--active": o.marketCapUsdTips
                                        }),
                                        onClick: function() {
                                            return l({
                                                marketCapUsdTips: !o.marketCapUsdTips
                                            })
                                        },
                                        onBlur: function() {
                                            return l({
                                                marketCapUsdTips: !1
                                            })
                                        },
                                        children: (0, u.jsx)("span", {
                                            className: "blind",
                                            children: "\uc720\uc758\uc0ac\ud56d"
                                        })
                                    }), (0, u.jsx)("div", {
                                        className: ai("coin-info-tip-layer", {
                                            "coin-info-tip-layer--active": o.marketCapUsdTips
                                        }),
                                        children: (0, u.jsx)("p", {
                                            className: ai("coin-info-tip-layer__text"),
                                            children: t("page.coinInfo.msg036")
                                        })
                                    })]
                                }), (0, u.jsx)("dd", {
                                    className: ai("coin-info__info-text"),
                                    children: c.marketCapUsd || "-"
                                })]
                            }), (0, u.jsxs)("div", {
                                className: ai("coin-info__info-inner"),
                                children: [(0, u.jsxs)("dt", {
                                    className: ai("coin-info__info-title"),
                                    children: [t("page.coinInfo.msg005"), (null === c || void 0 === c ? void 0 : c.marketTotalSupplyType) === _.Dqv.MANUAL && (0, u.jsx)(g.ZP, {
                                        className: ai("coin-info__tip-button", {
                                            "coin-info__tip-button--active": o.marketTotalSupplyTips
                                        }),
                                        onClick: function() {
                                            return l({
                                                marketTotalSupplyTips: !o.marketTotalSupplyTips
                                            })
                                        },
                                        onBlur: function() {
                                            return l({
                                                marketTotalSupplyTips: !1
                                            })
                                        },
                                        children: (0, u.jsx)("span", {
                                            className: "blind",
                                            children: "\uc720\uc758\uc0ac\ud56d"
                                        })
                                    }), (0, u.jsx)("div", {
                                        className: ai("coin-info-tip-layer", {
                                            "coin-info-tip-layer--active": o.marketTotalSupplyTips
                                        }),
                                        children: (0, u.jsx)("p", {
                                            className: ai("coin-info-tip-layer__text"),
                                            children: t("page.coinInfo.msg036")
                                        })
                                    })]
                                }), (0, u.jsxs)("dd", {
                                    className: ai("coin-info__info-text", "coin-info__info-text--flexible"),
                                    children: [(0, u.jsx)("strong", {
                                        className: ai("coin-info__auto-scale-box"),
                                        children: (0, u.jsx)(P.Z, {
                                            className: ai("coin-info__auto-scale"),
                                            children: c.marketAvailableSupply ? s(c.marketAvailableSupply).toFormat() : "-"
                                        })
                                    }), (0, u.jsx)("span", {
                                        className: ai("coin-info__info-text-unit"),
                                        children: c.marketAvailableSupply ? i : ""
                                    })]
                                })]
                            }), (0, u.jsxs)("div", {
                                className: ai("coin-info__info-inner"),
                                children: [(0, u.jsx)("dt", {
                                    className: "blind",
                                    children: "CMC \uc720\ud1b5\ub7c9 \uc548\ub0b4"
                                }), (0, u.jsx)("dd", {
                                    className: ai("coin-info__info-guide"),
                                    children: t("page.coinInfoGuide.msg001")
                                })]
                            }), d && (0, u.jsxs)(u.Fragment, {
                                children: [(0, u.jsxs)("div", {
                                    className: ai("coin-info__info-inner", "coin-info__info-inner--border"),
                                    children: [(0, u.jsx)("dt", {
                                        className: ai("coin-info__info-title"),
                                        children: t("page.coinInfo.msg006")
                                    }), (0, u.jsx)("dd", {
                                        className: ai("coin-info__info-text", "coin-info__info-text--flexible"),
                                        children: (0, u.jsx)("div", {
                                            className: ai("coin-info__link-box"),
                                            children: m.map((function(e) {
                                                return e.link ? (0, u.jsx)(g.ZP, {
                                                    target: "_blank",
                                                    href: e.link,
                                                    className: ai("coin-info__link"),
                                                    children: e.text
                                                }, e.text) : null
                                            }))
                                        })
                                    })]
                                }), (0, u.jsxs)("div", {
                                    className: ai("coin-info__info-inner"),
                                    children: [(0, u.jsx)("dt", {
                                        className: "blind",
                                        children: "\uac00\uc0c1\uc790\uc0b0 \uc815\ubcf4 \ubc14\ub85c\uac00\uae30 \uc548\ub0b4"
                                    }), (0, u.jsx)("dd", {
                                        className: ai("coin-info__info-guide"),
                                        children: t("page.coinInfoGuide.msg002")
                                    })]
                                })]
                            })]
                        })]
                    })
                })),
                ci = function() {
                    return (0, u.jsx)("div", {
                        className: ai("coin-info-box"),
                        children: (0, u.jsxs)("div", {
                            className: ai("coin-info-skeleton"),
                            children: [(0, u.jsxs)("div", {
                                className: ai("coin-info-skeleton__title"),
                                children: [(0, u.jsx)(Yn.pD, {
                                    className: ai("coin-info-skeleton__shape-01")
                                }), (0, u.jsx)(Yn.pD, {
                                    className: ai("coin-info-skeleton__shape-02")
                                })]
                            }), (0, u.jsxs)("div", {
                                className: ai("coin-info-skeleton__list"),
                                children: [(0, u.jsxs)("div", {
                                    className: ai("coin-info-skeleton__list-card"),
                                    children: [(0, u.jsx)("div", {
                                        className: ai("coin-info-skeleton__list-cell"),
                                        children: (0, u.jsx)(Yn.pD, {
                                            className: ai("coin-info-skeleton__shape-03")
                                        })
                                    }), (0, u.jsx)("div", {
                                        className: ai("coin-info-skeleton__list-cell", "coin-info-skeleton__list-cell--align-right"),
                                        children: (0, u.jsx)(Yn.pD, {
                                            className: ai("coin-info-skeleton__shape-04")
                                        })
                                    })]
                                }), (0, u.jsxs)("div", {
                                    className: ai("coin-info-skeleton__list-card"),
                                    children: [(0, u.jsx)("div", {
                                        className: ai("coin-info-skeleton__list-cell"),
                                        children: (0, u.jsx)(Yn.pD, {
                                            className: ai("coin-info-skeleton__shape-03")
                                        })
                                    }), (0, u.jsx)("div", {
                                        className: ai("coin-info-skeleton__list-cell", "coin-info-skeleton__list-cell--align-right"),
                                        children: (0, u.jsx)(Yn.pD, {
                                            className: ai("coin-info-skeleton__shape-05")
                                        })
                                    })]
                                }), (0, u.jsxs)("div", {
                                    className: ai("coin-info-skeleton__list-card"),
                                    children: [(0, u.jsx)("div", {
                                        className: ai("coin-info-skeleton__list-cell"),
                                        children: (0, u.jsx)(Yn.pD, {
                                            className: ai("coin-info-skeleton__shape-03")
                                        })
                                    }), (0, u.jsx)("div", {
                                        className: ai("coin-info-skeleton__list-cell", "coin-info-skeleton__list-cell--align-right"),
                                        children: (0, u.jsx)(Yn.pD, {
                                            className: ai("coin-info-skeleton__shape-05")
                                        })
                                    })]
                                })]
                            }), (0, u.jsx)(Yn.pD, {
                                className: ai("coin-info-skeleton__shape-06")
                            }), (0, u.jsxs)("div", {
                                className: ai("coin-info-skeleton__go"),
                                children: [(0, u.jsx)(Yn.pD, {
                                    className: ai("coin-info-skeleton__shape-07")
                                }), (0, u.jsxs)("div", {
                                    className: ai("coin-info-skeleton__go-content"),
                                    children: [(0, u.jsx)(Yn.pD, {
                                        className: ai("coin-info-skeleton__shape-08")
                                    }), (0, u.jsx)(Yn.pD, {
                                        className: ai("coin-info-skeleton__shape-08")
                                    })]
                                })]
                            })]
                        })
                    })
                },
                li = (0, l.Pi)((function() {
                    var e = (0, r.G2)().localeService.locale;
                    return (0, u.jsxs)("div", {
                        className: ai("coin-info-box"),
                        children: [(0, u.jsx)("h4", {
                            className: ai("coin-info__title"),
                            children: e("page.coinInfo.msg001")
                        }), (0, u.jsx)(k, {
                            caseStyle: i.coinInfo
                        })]
                    })
                })),
                di = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.coinInfoService.getInfo,
                        n = e.sessionService.language,
                        i = t.coin.coinType,
                        o = t.market.crncCd,
                        s = $n().getV2TradeCoinInfoQuery;
                    return (0, u.jsx)(Yn.nR, {
                        queryKey: s(i, o, n === _.DfJ.ko ? "korean" : "english").queryKey,
                        fallback: (0, u.jsx)(li, {}),
                        children: (0, u.jsx)(a.Suspense, {
                            fallback: (0, u.jsx)(ci, {}),
                            children: (0, u.jsx)(si, {})
                        })
                    })
                })),
                _i = c().bind(Gn),
                mi = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.localeService.locale,
                        n = e.coinService.getIntroUnitPrice,
                        i = e.coinInfoService.getInfo,
                        o = e.sessionService.language,
                        s = i.market.marketSymbol,
                        c = (0, S.PZ)(),
                        l = c.getInstance,
                        d = c.ROUNDING_MODE,
                        _ = ei(),
                        m = (0, a.useMemo)((function() {
                            return "".concat("KRW" === s ? "" : " ").concat((0, C.c0)(s, o))
                        }), [s, o]),
                        p = function(e, t) {
                            var r = "0" === e ? "-" : l(e).toFormat(n(e, i.coin.coinType, i.market.crncCd).lang);
                            return (0, u.jsxs)(u.Fragment, {
                                children: [t ? l(e).toFormat() : r, "-" !== r && m]
                            })
                        },
                        f = (0, a.useMemo)((function() {
                            return l(null === _ || void 0 === _ ? void 0 : _.yesterdayUnitTraded).isGreaterThan(s === C.NE ? 1e3 : 1e4) ? 0 : 3
                        }), [_, s]);
                    return _ ? (0, u.jsxs)("div", {
                        className: _i("coin-info-box"),
                        children: [(0, u.jsx)("h4", {
                            className: _i("coin-info__title"),
                            children: t("page.coinInfo.msg011")
                        }), (0, u.jsxs)("dl", {
                            className: _i("coin-info__info"),
                            children: [(0, u.jsxs)("div", {
                                className: _i("coin-info__info-inner"),
                                children: [(0, u.jsx)("dt", {
                                    className: _i("coin-info__info-title"),
                                    children: t("page.coinInfo.msg012")
                                }), (0, u.jsxs)("dd", {
                                    className: _i("coin-info__info-text", "coin-info__info-text--flexible"),
                                    children: [(0, u.jsx)("strong", {
                                        className: _i("coin-info__auto-scale-box"),
                                        children: (0, u.jsx)(P.Z, {
                                            className: _i("coin-info__auto-scale"),
                                            children: l(_.yesterdayUnitTraded).toFormat(f, d.ROUND_HALF_UP)
                                        })
                                    }), (0, u.jsx)("span", {
                                        className: _i("coin-info__info-text-unit"),
                                        children: _.scoinType
                                    })]
                                })]
                            }), (0, u.jsxs)("div", {
                                className: _i("coin-info__info-inner"),
                                children: [(0, u.jsx)("dt", {
                                    className: _i("coin-info__info-title"),
                                    children: t("page.coinInfo.msg013")
                                }), (0, u.jsx)("dd", {
                                    className: _i("coin-info__info-text"),
                                    children: function(e) {
                                        var t = "0" === e ? "0" : l(e).toFormat(0, d.ROUND_HALF_UP);
                                        return "".concat(t).concat(m)
                                    }(_.yesterdayTradeCost)
                                })]
                            }), (0, u.jsxs)("div", {
                                className: _i("coin-info__info-inner"),
                                children: [(0, u.jsx)("dt", {
                                    className: _i("coin-info__info-title"),
                                    children: t("page.coinInfo.msg014")
                                }), (0, u.jsx)("dd", {
                                    className: _i("coin-info__info-text"),
                                    children: p(_.yesterdayPrice, !0)
                                })]
                            }), (0, u.jsxs)("div", {
                                className: _i("coin-info__info-inner", "coin-info__info-inner--border"),
                                children: [(0, u.jsx)("dt", {
                                    className: _i("coin-info__info-title"),
                                    children: t("page.coinInfo.msg015")
                                }), (0, u.jsx)("dd", {
                                    className: _i("coin-info__info-text"),
                                    children: p(_.yesterdayMaxPrice, !0)
                                })]
                            }), (0, u.jsxs)("div", {
                                className: _i("coin-info__info-inner"),
                                children: [(0, u.jsx)("dt", {
                                    className: _i("coin-info__info-title"),
                                    children: t("page.coinInfo.msg016")
                                }), (0, u.jsx)("dd", {
                                    className: _i("coin-info__info-text"),
                                    children: p(_.yesterdayMinPrice, !0)
                                })]
                            }), (0, u.jsxs)("div", {
                                className: _i("coin-info__info-inner", "coin-info__info-inner--border"),
                                children: [(0, u.jsx)("dt", {
                                    className: _i("coin-info__info-title"),
                                    children: t("page.coinInfo.msg017")
                                }), (0, u.jsx)("dd", {
                                    className: _i("coin-info__info-text"),
                                    children: p(_.yearMaxPrice, !0)
                                })]
                            }), (0, u.jsxs)("div", {
                                className: _i("coin-info__info-inner"),
                                children: [(0, u.jsx)("dt", {
                                    className: _i("coin-info__info-title"),
                                    children: t("page.coinInfo.msg018")
                                }), (0, u.jsx)("dd", {
                                    className: _i("coin-info__info-text"),
                                    children: p(_.yearMinPrice, !0)
                                })]
                            }), (0, u.jsxs)("div", {
                                className: _i("coin-info__info-inner", "coin-info__info-inner--border"),
                                children: [(0, u.jsx)("dt", {
                                    className: _i("coin-info__info-title"),
                                    children: t("page.coinInfo.msg019")
                                }), (0, u.jsx)("dd", {
                                    className: _i("coin-info__info-text"),
                                    children: (0, u.jsx)(P.Z, {
                                        className: _i("coin-info__auto-scale"),
                                        children: p(_.quotationUnit, !0)
                                    })
                                })]
                            }), (0, u.jsxs)("div", {
                                className: _i("coin-info__info-inner"),
                                children: [(0, u.jsx)("dt", {
                                    className: _i("coin-info__info-title"),
                                    children: t("page.coinInfo.msg020")
                                }), (0, u.jsxs)("dd", {
                                    className: _i("coin-info__info-text"),
                                    children: [_.tradingUnit, (0, u.jsx)("span", {
                                        className: _i("coin-info__info-text-unit"),
                                        children: _.scoinType
                                    })]
                                })]
                            })]
                        })]
                    }) : null
                })),
                ui = function() {
                    return (0, u.jsx)("div", {
                        className: _i("coin-info-box"),
                        children: (0, u.jsxs)("div", {
                            className: _i("coin-price-skeleton"),
                            children: [(0, u.jsx)(Yn.pD, {
                                className: _i("coin-price-skeleton__shape-01")
                            }), (0, u.jsxs)("div", {
                                className: _i("coin-price-skeleton__content"),
                                children: [(0, u.jsxs)("div", {
                                    className: _i("coin-price-skeleton__list"),
                                    children: [(0, u.jsxs)("div", {
                                        className: _i("coin-price-skeleton__list-card"),
                                        children: [(0, u.jsx)(Yn.pD, {
                                            className: _i("coin-price-skeleton__shape-02")
                                        }), (0, u.jsx)(Yn.pD, {
                                            className: _i("coin-price-skeleton__shape-03")
                                        })]
                                    }), (0, u.jsxs)("div", {
                                        className: _i("coin-price-skeleton__list-card"),
                                        children: [(0, u.jsx)(Yn.pD, {
                                            className: _i("coin-price-skeleton__shape-02")
                                        }), (0, u.jsx)(Yn.pD, {
                                            className: _i("coin-price-skeleton__shape-04")
                                        })]
                                    }), (0, u.jsxs)("div", {
                                        className: _i("coin-price-skeleton__list-card"),
                                        children: [(0, u.jsx)(Yn.pD, {
                                            className: _i("coin-price-skeleton__shape-02")
                                        }), (0, u.jsx)(Yn.pD, {
                                            className: _i("coin-price-skeleton__shape-04")
                                        })]
                                    })]
                                }), (0, u.jsxs)("div", {
                                    className: _i("coin-price-skeleton__list", "coin-price-skeleton__list--type-margin-top"),
                                    children: [(0, u.jsxs)("div", {
                                        className: _i("coin-price-skeleton__list-card"),
                                        children: [(0, u.jsx)(Yn.pD, {
                                            className: _i("coin-price-skeleton__shape-02")
                                        }), (0, u.jsx)(Yn.pD, {
                                            className: _i("coin-price-skeleton__shape-04")
                                        })]
                                    }), (0, u.jsxs)("div", {
                                        className: _i("coin-price-skeleton__list-card"),
                                        children: [(0, u.jsx)(Yn.pD, {
                                            className: _i("coin-price-skeleton__shape-02")
                                        }), (0, u.jsx)(Yn.pD, {
                                            className: _i("coin-price-skeleton__shape-04")
                                        })]
                                    })]
                                }), (0, u.jsxs)("div", {
                                    className: _i("coin-price-skeleton__list", "coin-price-skeleton__list--type-margin-top"),
                                    children: [(0, u.jsxs)("div", {
                                        className: _i("coin-price-skeleton__list-card"),
                                        children: [(0, u.jsx)(Yn.pD, {
                                            className: _i("coin-price-skeleton__shape-02")
                                        }), (0, u.jsx)(Yn.pD, {
                                            className: _i("coin-price-skeleton__shape-03")
                                        })]
                                    }), (0, u.jsxs)("div", {
                                        className: _i("coin-price-skeleton__list-card"),
                                        children: [(0, u.jsx)(Yn.pD, {
                                            className: _i("coin-price-skeleton__shape-02")
                                        }), (0, u.jsx)(Yn.pD, {
                                            className: _i("coin-price-skeleton__shape-04")
                                        })]
                                    })]
                                }), (0, u.jsxs)("div", {
                                    className: _i("coin-price-skeleton__list", "coin-price-skeleton__list--type-margin-top"),
                                    children: [(0, u.jsxs)("div", {
                                        className: _i("coin-price-skeleton__list-card"),
                                        children: [(0, u.jsx)(Yn.pD, {
                                            className: _i("coin-price-skeleton__shape-02")
                                        }), (0, u.jsx)(Yn.pD, {
                                            className: _i("coin-price-skeleton__shape-03")
                                        })]
                                    }), (0, u.jsxs)("div", {
                                        className: _i("coin-price-skeleton__list-card"),
                                        children: [(0, u.jsx)(Yn.pD, {
                                            className: _i("coin-price-skeleton__shape-02")
                                        }), (0, u.jsx)(Yn.pD, {
                                            className: _i("coin-price-skeleton__shape-04")
                                        })]
                                    })]
                                })]
                            })]
                        })
                    })
                },
                pi = (0, l.Pi)((function() {
                    var e = (0, r.G2)().localeService.locale;
                    return (0, u.jsxs)("div", {
                        className: _i("coin-info-box"),
                        children: [(0, u.jsx)("h4", {
                            className: _i("coin-info__title"),
                            children: e("page.coinInfo.msg011")
                        }), (0, u.jsx)(k, {
                            caseStyle: i.coinInfo
                        })]
                    })
                })),
                fi = (0, l.Pi)((function() {
                    return (0, u.jsx)(Yn.nR, {
                        fallback: (0, u.jsx)(pi, {}),
                        children: (0, u.jsx)(a.Suspense, {
                            fallback: (0, u.jsx)(ui, {}),
                            children: (0, u.jsx)(mi, {})
                        })
                    })
                })),
                hi = c().bind(Gn),
                gi = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.localeService.locale,
                        n = e.coinInfoService,
                        i = n.getInfo,
                        o = n.tooltips,
                        a = n.setTooltips,
                        s = $n().getV1CoinInoutInfoQuery,
                        c = (0, zn.k)((0, pe.Z)((0, pe.Z)({}, s(i.coin.coinType)), {}, {
                            select: function(e) {
                                if (200 === e.status) return e.data
                            }
                        })).data,
                        l = function(e, t) {
                            var n = "".concat(e, "-").concat(t);
                            a((0, je.Z)({
                                infoTips: !1
                            }, n, !o[n]))
                        },
                        d = function(e, t) {
                            var n = "".concat(e, "-").concat(t);
                            a((0, je.Z)({
                                infoTips: !1
                            }, n, !1))
                        };
                    return (0, u.jsxs)("div", {
                        className: hi("coin-info-box"),
                        children: [(0, u.jsx)("h4", {
                            className: hi("coin-info__title"),
                            children: t("page.coinInfo.msg024")
                        }), (0, u.jsx)("div", {
                            className: hi("coin-info__table-box"),
                            children: (0, u.jsxs)("table", {
                                className: hi("coin-info__table"),
                                children: [(0, u.jsx)("caption", {
                                    children: (0, u.jsx)("span", {
                                        className: "blind",
                                        children: "\uc785\ucd9c\uae08 \ud604\ud669 \ud45c"
                                    })
                                }), (0, u.jsxs)("colgroup", {
                                    children: [(0, u.jsx)("col", {
                                        style: {
                                            width: "auto"
                                        }
                                    }), (0, u.jsx)("col", {
                                        style: {
                                            width: "66px"
                                        }
                                    }), (0, u.jsx)("col", {
                                        style: {
                                            width: "66px"
                                        }
                                    })]
                                }), (0, u.jsx)("thead", {
                                    children: (0, u.jsxs)("tr", {
                                        children: [(0, u.jsx)("th", {
                                            scope: "col",
                                            children: t("page.coinInfo.msg025")
                                        }), (0, u.jsx)("th", {
                                            scope: "col",
                                            children: t("page.coinInfo.msg026")
                                        }), (0, u.jsx)("th", {
                                            scope: "col",
                                            children: t("page.coinInfo.msg027")
                                        })]
                                    })
                                }), (0, u.jsx)("tbody", {
                                    children: null === c || void 0 === c ? void 0 : c.networkInfoList.map((function(e) {
                                        return (0, u.jsxs)("tr", {
                                            children: [(0, u.jsx)("td", {
                                                children: e.networkName
                                            }), (0, u.jsx)("td", {
                                                children: e.isDepositAvailable ? (0, u.jsx)("span", {
                                                    className: hi("coin-info__table-text-dot", "coin-info__table-text-dot--status-normal"),
                                                    children: t("page.coinInfo.msg028")
                                                }) : (0, u.jsxs)(u.Fragment, {
                                                    children: [(0, u.jsxs)(g.ZP, {
                                                        className: hi("coin-info__table-text-dot", "coin-info__table-text-dot--status-stop", {
                                                            "coin-info-premium__tooltip-button--active": o["deposit-".concat(e.networkKey)]
                                                        }),
                                                        onClick: function() {
                                                            return l("deposit", e.networkKey)
                                                        },
                                                        onBlur: function() {
                                                            return d("deposit", e.networkKey)
                                                        },
                                                        children: [t("page.coinInfo.msg029"), (0, u.jsx)("span", {
                                                            className: "blind",
                                                            children: "\uc911\ub2e8 \uc815\ubcf4 \ud234\ud301 \uc5f4\uae30"
                                                        })]
                                                    }), o["deposit-".concat(e.networkKey)] && (0, u.jsxs)("div", {
                                                        className: hi("coin-info-premium__tooltip", "stop-tips__tooltip"),
                                                        children: [(0, u.jsxs)("div", {
                                                            className: hi("coin-info-premium__tooltip-inner"),
                                                            children: [(0, u.jsx)("strong", {
                                                                className: hi("coin-info-premium__tooltip-title"),
                                                                children: t("page.coinInfo.msg031")
                                                            }), (0, u.jsx)("p", {
                                                                className: hi("coin-info-premium__tooltip-text"),
                                                                children: e.suspensionReason
                                                            })]
                                                        }), (0, u.jsxs)("div", {
                                                            className: hi("coin-info-premium__tooltip-inner"),
                                                            children: [(0, u.jsx)("strong", {
                                                                className: hi("coin-info-premium__tooltip-title"),
                                                                children: t("page.coinInfo.msg033")
                                                            }), (0, u.jsx)("p", {
                                                                className: hi("coin-info-premium__tooltip-text"),
                                                                children: e.depositWithdrawNote
                                                            })]
                                                        })]
                                                    })]
                                                })
                                            }), (0, u.jsx)("td", {
                                                children: e.isWithdrawAvailable ? (0, u.jsx)("span", {
                                                    className: hi("coin-info__table-text-dot", "coin-info__table-text-dot--status-normal"),
                                                    children: t("page.coinInfo.msg028")
                                                }) : (0, u.jsxs)(u.Fragment, {
                                                    children: [(0, u.jsxs)(g.ZP, {
                                                        className: hi("coin-info__table-text-dot", "coin-info__table-text-dot--status-stop", {
                                                            "coin-info-premium__tooltip-button--active": o["withdraw-".concat(e.networkKey)]
                                                        }),
                                                        onClick: function() {
                                                            return l("withdraw", e.networkKey)
                                                        },
                                                        onBlur: function() {
                                                            return d("withdraw", e.networkKey)
                                                        },
                                                        children: [t("page.coinInfo.msg029"), (0, u.jsx)("span", {
                                                            className: "blind",
                                                            children: "\uc911\ub2e8 \uc815\ubcf4 \ud234\ud301 \uc5f4\uae30"
                                                        })]
                                                    }), o["withdraw-".concat(e.networkKey)] && (0, u.jsxs)("div", {
                                                        className: hi("coin-info-premium__tooltip", "stop-tips__tooltip"),
                                                        children: [(0, u.jsxs)("div", {
                                                            className: hi("coin-info-premium__tooltip-inner"),
                                                            children: [(0, u.jsx)("strong", {
                                                                className: hi("coin-info-premium__tooltip-title"),
                                                                children: t("page.coinInfo.msg031")
                                                            }), (0, u.jsx)("p", {
                                                                className: hi("coin-info-premium__tooltip-text"),
                                                                children: e.suspensionReason
                                                            })]
                                                        }), (0, u.jsxs)("div", {
                                                            className: hi("coin-info-premium__tooltip-inner"),
                                                            children: [(0, u.jsx)("strong", {
                                                                className: hi("coin-info-premium__tooltip-title"),
                                                                children: t("page.coinInfo.msg033")
                                                            }), (0, u.jsx)("p", {
                                                                className: hi("coin-info-premium__tooltip-text"),
                                                                children: e.depositWithdrawNote
                                                            })]
                                                        })]
                                                    })]
                                                })
                                            })]
                                        }, e.networkKey)
                                    }))
                                })]
                            })
                        })]
                    })
                })),
                xi = function() {
                    return (0, u.jsx)("div", {
                        className: hi("coin-info-box"),
                        children: (0, u.jsxs)("div", {
                            className: hi("coin-transaction-skeleton"),
                            children: [(0, u.jsx)(Yn.pD, {
                                className: hi("coin-transaction-skeleton__shape-01")
                            }), (0, u.jsx)("div", {
                                className: hi("coin-transaction-skeleton__content"),
                                children: (0, u.jsxs)("div", {
                                    className: hi("coin-transaction-skeleton__list"),
                                    children: [(0, u.jsxs)("div", {
                                        className: hi("coin-transaction-skeleton__list-card"),
                                        children: [(0, u.jsx)(Yn.pD, {
                                            className: hi("coin-transaction-skeleton__shape-02")
                                        }), (0, u.jsxs)("div", {
                                            className: hi("coin-transaction-skeleton__list-content"),
                                            children: [(0, u.jsx)(Yn.pD, {
                                                className: hi("coin-transaction-skeleton__shape-03")
                                            }), (0, u.jsx)(Yn.pD, {
                                                className: hi("coin-transaction-skeleton__shape-03")
                                            })]
                                        })]
                                    }), (0, u.jsxs)("div", {
                                        className: hi("coin-transaction-skeleton__list-card"),
                                        children: [(0, u.jsx)(Yn.pD, {
                                            className: hi("coin-transaction-skeleton__shape-02")
                                        }), (0, u.jsxs)("div", {
                                            className: hi("coin-transaction-skeleton__list-content"),
                                            children: [(0, u.jsx)(Yn.pD, {
                                                className: hi("coin-transaction-skeleton__shape-03")
                                            }), (0, u.jsx)(Yn.pD, {
                                                className: hi("coin-transaction-skeleton__shape-03")
                                            })]
                                        })]
                                    })]
                                })
                            })]
                        })
                    })
                },
                bi = (0, l.Pi)((function() {
                    var e = (0, r.G2)().localeService.locale;
                    return (0, u.jsxs)("div", {
                        className: hi("coin-info-box"),
                        children: [(0, u.jsx)("h4", {
                            className: hi("coin-info__title"),
                            children: e("page.coinInfo.msg024")
                        }), (0, u.jsx)(k, {
                            caseStyle: i.coinInfo
                        })]
                    })
                })),
                vi = (0, l.Pi)((function() {
                    var e = (0, r.G2)().coinInfoService.getInfo,
                        t = $n().getV1CoinInoutInfoQuery;
                    return (0, u.jsx)(Yn.nR, {
                        queryKey: t(e.coin.coinType).queryKey,
                        fallback: (0, u.jsx)(bi, {}),
                        children: (0, u.jsx)(a.Suspense, {
                            fallback: (0, u.jsx)(xi, {}),
                            children: (0, u.jsx)(gi, {})
                        })
                    })
                })),
                yi = (n(32676), c().bind(Gn)),
                ji = "video",
                Ni = function(e) {
                    var t = e.tweetInfo,
                        n = (0, a.useState)(100),
                        i = (0, o.Z)(n, 2),
                        r = i[0],
                        s = i[1],
                        c = (0, a.useRef)(null);
                    return (0, a.useEffect)((function() {
                        var e, t = function() {
                            if (c.current) {
                                var e = c.current,
                                    t = e.complete,
                                    n = e.naturalHeight,
                                    i = e.height,
                                    r = e.width;
                                t && 0 !== n && s(i / r * 100)
                            }
                        };
                        return null === (e = c.current) || void 0 === e || e.addEventListener("load", t, {
                                once: !0
                            }),
                            function() {
                                var e;
                                null === (e = c.current) || void 0 === e || e.removeEventListener("load", t)
                            }
                    }), [t.mediaUrl]), (0, u.jsx)("div", {
                        className: yi("twitter-image", {
                            "twitter-image-thumbnail": t.mediaType === ji
                        }),
                        children: (0, u.jsx)("div", {
                            className: yi("twitter-image__inner"),
                            style: {
                                paddingBottom: "".concat(r, "%")
                            },
                            children: (0, u.jsx)("img", {
                                ref: c,
                                className: yi("twitter-image__image"),
                                src: t.mediaUrl,
                                alt: "\ucee8\ud150\uce20 \uc774\ubbf8\uc9c0"
                            })
                        })
                    })
                },
                ki = c().bind(Gn),
                Ci = function(e) {
                    var t = e.tweetText,
                        n = e.entities,
                        i = (0, a.useMemo)((function() {
                            return (0, le.Z)(n) ? [] : Object.keys(n).reduce((function(e, t) {
                                var i = n[t].map((function(e) {
                                    return (0, pe.Z)((0, pe.Z)({}, e), {}, {
                                        type: t
                                    })
                                }));
                                return e.concat(i)
                            }), []).sort((function(e, t) {
                                return e.start - t.start
                            }))
                        }), []),
                        r = (0, a.useCallback)((function(e, t, n, i) {
                            (0, L.Z)(e).slice(t, n).join("").split("\n").forEach((function(e, t, n) {
                                i.push(e), t < n.length - 1 && i.push((0, u.jsx)("br", {}, "".concat(t, "-").concat(e)))
                            }))
                        }), []);
                    return (0, u.jsx)("div", {
                        className: ki("twitter-text"),
                        children: function() {
                            var e = [],
                                n = 0;
                            return i.forEach((function(i, o) {
                                var a = i.start,
                                    s = i.end,
                                    c = i.type,
                                    l = i.mediaUrlExists;
                                a > n && r(t, n, a, e);
                                var d = l ? "" : "urls" === c ? i.displayUrl : (0, L.Z)(t).slice(a, s).join("");
                                d && e.push((0, u.jsx)("span", {
                                    children: d
                                }, "".concat(c, "-").concat(o))), n = s
                            })), r(t, n, t.length + 1, e), e
                        }()
                    })
                },
                Si = c().bind(Gn),
                wi = (0, l.Pi)((function(e) {
                    var t, n, i = e.tweet,
                        o = e.accountName,
                        s = e.userName,
                        c = e.profileImageUrl,
                        l = (0, r.G2)().localeService.locale,
                        d = "https://twitter.com",
                        _ = (0, a.useMemo)((function() {
                            return !(0, le.Z)(i.referencedTweet) && "retweeted" === i.referencedTweet.referencedTweetType
                        }), [i]);
                    return (0, u.jsx)("li", {
                        className: Si("project-news__info-list-item"),
                        children: (0, u.jsxs)("div", {
                            className: Si("twitter"),
                            children: [_ && (0, u.jsxs)("span", {
                                className: Si("twitter-retweet"),
                                children: [o, " ", l("page.trade.msg237")]
                            }), (0, u.jsx)("strong", {
                                className: Si("twitter-profile"),
                                children: (0, u.jsxs)(g.ZP, {
                                    href: _ ? "".concat(d, "/").concat(s, "/status/").concat(i.tweetId) : "".concat(d, "/").concat(s),
                                    className: Si("twitter-profile__link"),
                                    children: [(0, u.jsx)("span", {
                                        className: "blind",
                                        children: "\ud574\ub2f9 \ud2b8\uc704\ud130\ub85c \uc774\ub3d9"
                                    }), (0, u.jsx)("span", {
                                        className: Si("twitter-profile__img"),
                                        style: {
                                            backgroundImage: "url(".concat(_ ? null === (t = i.referencedTweet) || void 0 === t ? void 0 : t.referencedTweetProfileImageUrl : c, ")")
                                        },
                                        children: (0, u.jsx)("span", {
                                            className: "blind",
                                            children: "\ud504\ub85c\ud544 \uc774\ubbf8\uc9c0"
                                        })
                                    }), (0, u.jsx)("span", {
                                        className: Si("twitter-profile__name"),
                                        children: _ ? null === (n = i.referencedTweet) || void 0 === n ? void 0 : n.referencedTweetAccountName : o
                                    })]
                                })
                            }), (0, u.jsxs)("div", {
                                className: Si("twitter-content"),
                                children: [(0, u.jsx)(g.ZP, {
                                    href: "".concat(d, "/").concat(s, "/status/").concat(i.tweetId),
                                    className: Si("twitter-content__link"),
                                    children: (0, u.jsx)("span", {
                                        className: "blind",
                                        children: "\ucee8\ud150\uce20 \uac8c\uc2dc\uae00\ub85c \uc774\ub3d9"
                                    })
                                }), (0, u.jsx)(Ci, {
                                    tweetText: i.tweet,
                                    entities: i.entities
                                }), i.mediaType && (0, u.jsx)(Ni, {
                                    tweetInfo: i
                                })]
                            }), (0, u.jsxs)("div", {
                                className: Si("twitter-upload"),
                                children: [(0, u.jsx)("span", {
                                    className: Si("twitter-upload__time"),
                                    children: T()(1e3 * i.createTweetDtm).locale("ko").format("a hh:mm")
                                }), (0, u.jsx)("i", {
                                    children: "\xb7"
                                }), (0, u.jsx)("span", {
                                    className: Si("twitter-upload__date"),
                                    children: T()(1e3 * i.createTweetDtm).format("YYYY\ub144 M\uc6d4 D\uc77c")
                                })]
                            })]
                        })
                    })
                })),
                Ti = c().bind(Gn),
                Pi = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.localeService.locale,
                        n = e.coinInfoService.getInfo,
                        i = $n().getV1CoinTweetsQuery,
                        o = (0, zn.k)((0, pe.Z)((0, pe.Z)({}, i(n.coin.coinSymbol)), {}, {
                            select: function(e) {
                                if (200 === e.status) return e.data.data
                            }
                        })).data;
                    return o && 0 !== o.tweetInfos.length ? (0, u.jsx)("div", {
                        className: Ti("coin-info-box"),
                        children: (0, u.jsxs)("div", {
                            className: Ti("project-news"),
                            children: [(0, u.jsx)("h4", {
                                className: Ti("project-news__title"),
                                children: t("page.trade.msg239")
                            }), (0, u.jsxs)("span", {
                                className: Ti("project-news__source"),
                                children: [t("page.trade.msg236"), (0, u.jsx)("span", {
                                    className: Ti("project-news__source-img"),
                                    children: (0, u.jsx)("span", {
                                        className: "blind",
                                        children: "\ucd9c\ucc98 \uc544\uc774\ucf58"
                                    })
                                })]
                            }), (0, u.jsx)("div", {
                                className: Ti("project-news__info"),
                                children: (0, u.jsx)("ul", {
                                    className: Ti("project-news__info-list"),
                                    children: null === o || void 0 === o ? void 0 : o.tweetInfos.map((function(e) {
                                        return (0, a.createElement)(wi, (0, pe.Z)((0, pe.Z)({}, o), {}, {
                                            tweet: e,
                                            key: e.tweetId
                                        }))
                                    }))
                                })
                            })]
                        })
                    }) : null
                })),
                Ii = function() {
                    return (0, u.jsx)("div", {
                        className: Ti("coin-info-box"),
                        children: (0, u.jsxs)("div", {
                            className: Ti("coin-project-skeleton"),
                            children: [(0, u.jsx)(Yn.pD, {
                                className: Ti("coin-project-skeleton__shape-01")
                            }), (0, u.jsxs)("div", {
                                className: Ti("coin-project-skeleton__head"),
                                children: [(0, u.jsx)(Yn.pD, {
                                    className: Ti("coin-project-skeleton__shape-02")
                                }), (0, u.jsx)(Yn.pD, {
                                    className: Ti("coin-project-skeleton__shape-03")
                                })]
                            }), (0, u.jsxs)("div", {
                                className: Ti("coin-project-skeleton__body"),
                                children: [(0, u.jsx)(Yn.pD, {
                                    className: Ti("coin-project-skeleton__shape-04")
                                }), (0, u.jsx)(Yn.pD, {
                                    className: Ti("coin-project-skeleton__shape-04")
                                }), (0, u.jsx)(Yn.pD, {
                                    className: Ti("coin-project-skeleton__shape-05")
                                })]
                            }), (0, u.jsx)(Yn.pD, {
                                className: Ti("coin-project-skeleton__shape-06")
                            }), (0, u.jsx)(Yn.pD, {
                                className: Ti("coin-project-skeleton__shape-07")
                            })]
                        })
                    })
                },
                Di = (0, l.Pi)((function() {
                    var e = (0, r.G2)().coinInfoService.getInfo,
                        t = $n().getV1CoinTweetsQuery;
                    return (0, u.jsx)(Yn.nR, {
                        queryKey: t(e.coin.coinSymbol).queryKey,
                        fallback: null,
                        children: (0, u.jsx)(a.Suspense, {
                            fallback: (0, u.jsx)(Ii, {}),
                            children: (0, u.jsx)(Pi, {})
                        })
                    })
                })),
                Oi = c().bind(Gn),
                Zi = (0, l.Pi)((function() {
                    return (0, u.jsxs)("div", {
                        className: Oi("coin-info-premium__content", "cm-gray-scroll"),
                        children: [(0, u.jsx)(di, {}), (0, u.jsx)(fi, {}), (0, u.jsx)(vi, {}), (0, u.jsx)(oi, {}), (0, u.jsx)(Di, {})]
                    })
                })),
                Ai = (0, r.FU)(Zi, {
                    ko: Kn,
                    en: Qn
                }),
                Fi = c().bind(Gn),
                Mi = (0, l.Pi)((function() {
                    var e = (0, r.G2)().localeService.locale;
                    return (0, u.jsx)("div", {
                        className: Fi("coin-info-premium-caution"),
                        children: (0, u.jsx)("p", {
                            className: Fi("coin-info-premium-caution__paragraph"),
                            children: e("page.trade.msg238", {
                                tagBr: (0, u.jsx)("br", {})
                            })
                        })
                    })
                })),
                Ri = n(22486),
                Bi = c().bind(Gn),
                Li = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.localeService.locale,
                        n = e.sessionService.language,
                        i = e.coinService,
                        s = i.getTicker,
                        c = i.fnToKrw,
                        l = i.subscribeTicker,
                        d = e.coinInfoService,
                        m = d.getInfo,
                        p = d.fnConvertUnitUnderMillion,
                        f = d.tooltips,
                        h = d.setTooltips,
                        x = e.developService.fnGetDataId,
                        b = (0, a.useRef)(x()),
                        v = (0, a.useRef)(null),
                        y = m.coin.coinType,
                        j = m.market.crncCd,
                        N = $n().getV1AmountOfDepositQuery,
                        k = (0, zn.k)((0, pe.Z)((0, pe.Z)({}, N(y, j)), {}, {
                            select: function(e) {
                                if (200 === e.status) return e.data
                            },
                            refetchInterval: function(e) {
                                if (e.state.data) {
                                    var t = e.state.data.data,
                                        n = t.serverTime,
                                        i = t.timestamp,
                                        r = T()(i).add(60, "second").diff(T()(n), "millisecond");
                                    return r > 0 ? r : 6e4
                                }
                                return 6e4
                            }
                        })).data,
                        S = (0, a.useMemo)((function() {
                            return s(y, null === k || void 0 === k ? void 0 : k.mktType)
                        }), [k, y, j]),
                        w = (0, a.useCallback)((function(e) {
                            return e ? (null === k || void 0 === k ? void 0 : k.mktType) === C.Eo ? e : c(y, j, e).replace(/,/gi, "") : 0
                        }), [y, j, k]),
                        P = (0, a.useState)(S && k ? p(new($())(w(S.visibleClosePrice)).multipliedBy(k.purityDeposit).toFixed(), {
                            isKrw: n === _.DfJ.ko,
                            isAbb: !1
                        }, $().ROUND_DOWN) : "-"),
                        I = (0, o.Z)(P, 2),
                        D = I[0],
                        O = I[1],
                        Z = function() {
                            h({
                                pureDeposit: !f.pureDeposit,
                                topsImpact: !1
                            })
                        };
                    return (0, a.useEffect)((function() {
                        k && O(p(new($())(w(null === S || void 0 === S ? void 0 : S.visibleClosePrice)).multipliedBy(k.purityDeposit).toFixed(), {
                            isKrw: n === _.DfJ.ko,
                            isAbb: !1
                        }, $().ROUND_DOWN))
                    }), [k, n]), (0, a.useEffect)((function() {
                        return f.pureDeposit && v.current && v.current.addEventListener("click", Z),
                            function() {
                                v.current && v.current.removeEventListener("click", Z)
                            }
                    }), [f.pureDeposit]), (0, a.useEffect)((function() {
                        if (k && k.coinType === y) {
                            var e = l(y, k.mktType, (function(e) {
                                O(p(new($())(w(e.visibleClosePrice)).multipliedBy(k.purityDeposit).toFixed(), {
                                    isKrw: n === _.DfJ.ko,
                                    isAbb: !1
                                }, $().ROUND_DOWN) || "-")
                            }), b.current);
                            return function() {
                                e && e()
                            }
                        }
                    }), [y, j]), (0, u.jsxs)("div", (0, pe.Z)((0, pe.Z)({
                        className: Bi("distribution__list-item")
                    }, b.current), {}, {
                        children: [(0, u.jsxs)("dt", {
                            className: Bi("distribution__list-item-title"),
                            children: [t("page.trade.msg216"), (0, u.jsxs)("span", {
                                className: Bi("coin-info-premium__tooltip-box"),
                                children: [(0, u.jsx)(g.ZP, {
                                    className: Bi("coin-info-premium__tooltip-button", {
                                        "coin-info-premium__tooltip-button--active": f.pureDeposit
                                    }),
                                    onClick: Z,
                                    onBlur: function() {
                                        h({
                                            pureDeposit: !1,
                                            topsImpact: !1
                                        })
                                    }
                                }), f.pureDeposit && (0, u.jsxs)("span", {
                                    className: "".concat(Bi("coin-info-premium__tooltip", "distribution__tooltip"), " coin-info-premium__tooltip"),
                                    ref: v,
                                    children: [(0, u.jsx)("strong", {
                                        className: Bi("coin-info-premium__tooltip-title"),
                                        children: t("page.trade.msg216")
                                    }), (0, u.jsx)("span", {
                                        className: Bi("coin-info-premium__tooltip-text"),
                                        children: t("page.trade.msg217")
                                    })]
                                })]
                            })]
                        }), "-" === D ? (0, u.jsx)("dd", {
                            className: Bi("distribution__list-item-value", "distribution__list-item-value--type-data-none"),
                            children: "-"
                        }) : (0, u.jsxs)("dd", {
                            className: Bi("distribution__list-item-value"),
                            children: [D, (0, u.jsx)("span", {
                                className: Bi("distribution__list-item-unit"),
                                children: t("common.msg09")
                            })]
                        })]
                    }))
                })),
                Ei = c().bind(Gn),
                Ui = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.sessionService,
                        n = t.language,
                        i = t.getRate,
                        s = e.localeService.locale,
                        c = e.coinService,
                        l = c.getTicker,
                        d = c.subscribeTicker,
                        m = c.fnToKrw,
                        p = e.coinInfoService.getInfo,
                        f = e.developService.fnGetDataId,
                        h = (0, a.useRef)(f()),
                        g = (0, S.PZ)(),
                        x = g.getInstance,
                        b = g.ROUNDING_MODE,
                        v = p.coin,
                        y = v.coinType,
                        j = v.coinSymbol,
                        N = p.market.crncCd,
                        k = $n().getV1AccumulationDepositQuery,
                        w = (0, zn.k)((0, pe.Z)((0, pe.Z)({}, k(y, N)), {}, {
                            select: function(e) {
                                if (200 === e.status) return e.data
                            },
                            refetchInterval: function(e) {
                                if (e.state.data) {
                                    var t = e.state.data.data,
                                        n = t.serverTime,
                                        i = t.timestamp,
                                        r = T()(i).add(600, "second").diff(T()(n), "millisecond");
                                    return r > 0 ? r : 1e4
                                }
                                return 1e4
                            }
                        })).data,
                        P = n === _.DfJ.ko,
                        I = function(e, t) {
                            var n = x(e);
                            if (!t) return i(n.toFixed(), 2);
                            var r, o = 1e8;
                            if (n.isGreaterThanOrEqualTo(o)) {
                                var a = n.dividedToIntegerBy(o),
                                    c = n.modulo(o).dividedToIntegerBy(1e4);
                                a.isZero() || c.isZero() ? a.isZero() || (r = "".concat(a.toFormat()).concat(s("page.trade.msg232"))) : r = "".concat(a.toFormat()).concat(s("page.trade.msg232"), " ").concat(c.toFormat()).concat(s("page.trade.msg233"))
                            } else r = n.toFormat(0, b.ROUND_DOWN);
                            return r
                        },
                        D = (0, a.useRef)(0),
                        O = (0, a.useMemo)((function() {
                            return l(y, null === w || void 0 === w ? void 0 : w.mktType)
                        }), [y, N, w]),
                        Z = (0, a.useCallback)((function(e) {
                            var t = x(e);
                            return t.isZero() ? "0" : (null === w || void 0 === w ? void 0 : w.mktType) === C.Eo ? t.toFixed() : m(y, N, t.toFixed(), !1, !1)
                        }), [y, N, w]),
                        A = (0, a.useState)(x((null === w || void 0 === w ? void 0 : w.accumulationDepositAmt.split("").map((function(e) {
                            return Math.abs(Number(e) - 5)
                        })).join("")) || "").toFormat()),
                        F = (0, o.Z)(A, 2),
                        M = F[0],
                        R = F[1],
                        B = (0, a.useState)(O && w ? I(x(Z(O.visibleClosePrice)).multipliedBy(w.accumulationDepositAmt).toFixed(), P) : "-"),
                        L = (0, o.Z)(B, 2),
                        E = L[0],
                        U = L[1];
                    return (0, a.useEffect)((function() {
                        if (w && w.coinType === y) {
                            var e = d(y, w.mktType, (function(e) {
                                x(e.closePrice).isEqualTo("0") ? U("0") : U(I(x(Z(e.visibleClosePrice)).multipliedBy(w.accumulationDepositAmt).toFixed(), P))
                            }), h.current);
                            return function() {
                                e && e()
                            }
                        }
                    }), [y, N]), (0, a.useEffect)((function() {
                        if (w && w.coinType === y) return R(x((null === w || void 0 === w ? void 0 : w.accumulationDepositAmt.split("").map((function(e) {
                                return Math.abs(Number(e) - 5)
                            })).join("")) || "").toFormat()), D.current = window.setTimeout((function() {
                                R(x(w.accumulationDepositAmt).toFormat())
                            }), 100), x(null === O || void 0 === O ? void 0 : O.closePrice).isEqualTo("0") ? U("0") : U(O && w ? I(x(Z(O.visibleClosePrice)).multipliedBy(w.accumulationDepositAmt).toFixed(), P) : "-"),
                            function() {
                                window.clearTimeout(D.current), R("")
                            }
                    }), [y, N, w, n]), (0, u.jsx)("div", (0, pe.Z)((0, pe.Z)({
                        className: Ei("coin-info-premium-box")
                    }, h.current), {}, {
                        children: (0, u.jsxs)("div", {
                            className: Ei("distribution"),
                            children: [(0, u.jsx)("h4", {
                                className: Ei("distribution__title"),
                                children: s("page.trade.msg214")
                            }), (0, u.jsxs)("div", {
                                className: Ei("distribution__top-area"),
                                children: [(0, u.jsxs)("div", {
                                    className: Ei("distribution__ticker-box"),
                                    children: ["-" === M ? (0, u.jsx)("span", {
                                        className: Ei("distribution__ticker"),
                                        children: "-"
                                    }) : (0, u.jsx)(Ri.v, {
                                        textClassName: Ei("distribution__ticker"),
                                        duration: "0.3s",
                                        children: M
                                    }), (0, u.jsx)("span", {
                                        className: Ei("distribution__ticker-symbol"),
                                        children: j
                                    })]
                                }), (0, u.jsxs)("span", {
                                    className: Ei("distribution__top-area-ticker-change-rate"),
                                    children: ["\u2248", E, s("common.msg09")]
                                })]
                            }), (0, u.jsx)("div", {
                                className: Ei("distribution__bottom-area"),
                                children: (0, u.jsxs)("dl", {
                                    className: Ei("distribution__list"),
                                    children: [(0, u.jsxs)("div", {
                                        className: Ei("distribution__list-item"),
                                        children: [(0, u.jsx)("dt", {
                                            className: Ei("distribution__list-item-title"),
                                            children: s("page.trade.msg215")
                                        }), w && "-" !== w.depositChangeRate ? (0, u.jsxs)("dd", {
                                            className: Ei("distribution__list-item-value", {
                                                "distribution__list-item-value--type-asc": x(w.depositChangeRate).isGreaterThan(0),
                                                "distribution__list-item-value--type-desc": x(w.depositChangeRate).isLessThan(0),
                                                "distribution__list-item-value--type-data-none": x(w.depositChangeRate).isEqualTo(0)
                                            }),
                                            children: [w && x(w.depositChangeRate).isGreaterThan(0) && "+", null === w || void 0 === w ? void 0 : w.depositChangeRate, (0, u.jsx)("span", {
                                                className: Ei("distribution__list-item-unit"),
                                                children: "%"
                                            })]
                                        }) : (0, u.jsx)("dd", {
                                            className: Ei("distribution__list-item-value", "distribution__list-item-value--type-data-none"),
                                            children: "-"
                                        })]
                                    }), (0, u.jsx)(Li, {})]
                                })
                            })]
                        })
                    }))
                })),
                qi = function() {
                    return (0, u.jsx)("div", {
                        className: Ei("coin-info-premium-box"),
                        children: (0, u.jsxs)("div", {
                            className: Ei("coin-distribution-skeleton"),
                            children: [(0, u.jsxs)("div", {
                                className: Ei("coin-distribution-skeleton__head"),
                                children: [(0, u.jsx)(Yn.pD, {
                                    className: Ei("coin-distribution-skeleton__head-shape-01")
                                }), (0, u.jsx)(Yn.pD, {
                                    className: Ei("coin-distribution-skeleton__head-shape-02")
                                }), (0, u.jsx)(Yn.pD, {
                                    className: Ei("coin-distribution-skeleton__head-shape-03")
                                })]
                            }), (0, u.jsxs)("div", {
                                className: Ei("coin-distribution-skeleton__body"),
                                children: [(0, u.jsx)(Yn.pD, {
                                    className: Ei("coin-distribution-skeleton__body-shape-01")
                                }), (0, u.jsx)(Yn.pD, {
                                    className: Ei("coin-distribution-skeleton__body-shape-02")
                                }), (0, u.jsx)(Yn.pD, {
                                    className: Ei("coin-distribution-skeleton__body-shape-01")
                                }), (0, u.jsx)(Yn.pD, {
                                    className: Ei("coin-distribution-skeleton__body-shape-02")
                                })]
                            })]
                        })
                    })
                },
                Gi = (0, l.Pi)((function() {
                    var e = (0, r.G2)().localeService.locale;
                    return (0, u.jsxs)("div", {
                        className: Ei("coin-info-premium-box"),
                        children: [(0, u.jsx)("h4", {
                            className: Ei("distribution__title"),
                            children: e("page.trade.msg214")
                        }), (0, u.jsx)(k, {
                            caseStyle: i.coinInfo
                        })]
                    })
                })),
                Hi = (0, l.Pi)((function() {
                    var e = (0, r.G2)().coinInfoService.getInfo,
                        t = e.coin.coinType,
                        n = e.market.crncCd,
                        i = $n().getV1AccumulationDepositQuery;
                    return (0, u.jsx)(Yn.nR, {
                        queryKey: i(t, n).queryKey,
                        fallback: (0, u.jsx)(Gi, {}),
                        children: (0, u.jsx)(a.Suspense, {
                            fallback: (0, u.jsx)(qi, {}),
                            children: (0, u.jsx)(Ui, {})
                        })
                    })
                })),
                Qi = n(93966),
                Ki = function(e, t) {
                    var n = (0, r.G2)().httpService.get,
                        i = ["trade", "info", e];
                    return (0, zn.k)({
                        queryKey: i,
                        queryFn: function() {
                            return n("v1/trade/indicator/wealthy/stat", {
                                coinType: e
                            }, !0, null, !0).then((function(e) {
                                var t = e.data,
                                    n = e.message,
                                    i = e.code;
                                if (200 !== e.status) throw new _.v5T(n, i);
                                return t
                            }))
                        },
                        select: function(e) {
                            return e ? !Object.keys(e).some((function(t) {
                                return !(0, le.Z)(e[t]) && !(0, Qi.Z)(e[t])
                            })) ? null : t ? {
                                Hold: e.holdPercentList ? e.holdPercentList.map((function(e) {
                                    return {
                                        dataX: e.standardDate,
                                        dataY: e.percent
                                    }
                                })) : null,
                                Invest: e.investList ? e.investList.map((function(e) {
                                    var t = e.standardDate,
                                        n = e.tradeAmount;
                                    return {
                                        dataX: t,
                                        dataY: parseInt(n, 10)
                                    }
                                })) : null,
                                BuySell: e.buySellState || null
                            }[t] : e : null
                        }
                    })
                },
                Yi = n(10592),
                Vi = "var(--G-bithumb-new-trade_buy_01)",
                Wi = "var(--G-bithumb-new-gray_01)",
                zi = "var(--G-bithumb-new-gray_05)",
                Xi = (0, l.Pi)((function(e) {
                    var t = e.data,
                        n = e.isVisible,
                        i = (0, r.G2)().localeService.locale,
                        o = (0, a.useRef)(null),
                        s = (0, a.useRef)({
                            chartWidth: 279,
                            chartLeftPadding: 8,
                            chartHeight: 129,
                            areaWidth: 279,
                            areaHeight: 207,
                            tooltipHeight: 20
                        });
                    return (0, a.useEffect)((function() {
                        if (n) {
                            var e = function(e) {
                                    return function(t, n) {
                                        if (t.dataY !== n.dataY) return t.dataY - n.dataY;
                                        var i = T()(t.dataX),
                                            r = T()(n.dataX);
                                        return e ? i.isSame(r) ? 0 : i.isAfter(r) ? 1 : -1 : i.isSame(r) ? 0 : i.isAfter(r) ? -1 : 1
                                    }
                                },
                                r = Yi.g_G(t, e(!1)),
                                a = Yi.Xae(t, e(!0));
                            Yi.Ys(o.current).selectAll("*").remove();
                            var c = Yi.Ys(o.current).attr("width", s.current.areaWidth).attr("height", s.current.areaHeight),
                                l = c.append("linearGradient").attr("id", "lineGradient").attr("x1", "0").attr("y1", "100%").attr("x2", "0").attr("y2", "0");
                            l.append("stop").attr("offset", "0%").attr("stop-color", Vi).attr("stop-opacity", "0"), l.append("stop").attr("offset", "100%").attr("stop-color", Vi).attr("stop-opacity", "0.15");
                            var d = s.current.chartWidth - 2 * s.current.chartLeftPadding,
                                _ = Yi.KYF().domain(Yi.Wem(t, (function(e) {
                                    return new Date(e.dataX)
                                }))).range([s.current.chartLeftPadding, s.current.chartWidth - s.current.chartLeftPadding]),
                                m = Yi.BYU().domain([0, 100]).range([s.current.chartHeight + s.current.tooltipHeight, s.current.tooltipHeight]),
                                u = c.append("g").attr("class", "xAxis").call(Yi.LLu(_).ticks(7).tickFormat((function(e) {
                                    return i("page.trade.msg268", {
                                        date: new Date(e).getDate()
                                    })
                                })).tickSizeOuter(0)).call((function(e) {
                                    return e.select(".domain").remove()
                                })).call((function(e) {
                                    return e.selectAll(".tick line").remove()
                                })).call((function(e) {
                                    return e.selectAll(".tick text").attr("dx", "0").attr("dy", "0").attr("font-size", "11px").attr("line-height", "17px").attr("fill", zi)
                                })).call((function(e) {
                                    return e.select(".tick:nth-child(1) text").attr("dx", "5")
                                })).call((function(e) {
                                    return e.select(".tick:nth-child(7) text").attr("dx", "-5")
                                }));
                            u.style("transform", "translate(0, ".concat(s.current.areaHeight - u.node().getBBox().height, "px)")), c.append("g").attr("class", "grid").call(Yi.y4O(m).tickValues([0, 25, 50, 75, 100]).tickSizeOuter(0).tickSize(-s.current.chartWidth).tickFormat("")).call((function(e) {
                                return e.select(".domain").remove()
                            })).call((function(e) {
                                return e.select(".tick:nth-child(1)").attr("opacity", 0)
                            })).call((function(e) {
                                return e.select(".tick:nth-child(2)").attr("stroke", Wi).attr("stroke-dasharray", "2 3").attr("opacity", .05)
                            })).call((function(e) {
                                return e.select(".tick:nth-child(3)").attr("stroke", Wi).attr("stroke-dasharray", "0").attr("opacity", .05)
                            })).call((function(e) {
                                return e.select(".tick:nth-child(4)").attr("stroke", Wi).attr("stroke-dasharray", "2 3").attr("opacity", .05)
                            })).call((function(e) {
                                return e.select(".tick:nth-child(5)").attr("opacity", 0)
                            })).call((function(e) {
                                return e.select(".tick:nth-child(6)").attr("opacity", 0)
                            }));
                            var p = Yi.jvg().x((function(e) {
                                    return _(new Date(e.dataX))
                                })).y((function(e) {
                                    return m(e.dataY)
                                })).curve(Yi.FdL),
                                f = c.append("path").attr("fill", "none").attr("stroke", Vi).attr("stroke-width", 2.4).attr("stroke-linecap", "round").attr("d", p(t)),
                                h = f.node().getTotalLength();
                            f.attr("stroke-dasharray", h).attr("stroke-dashoffset", h).style("opacity", 0).transition().duration(300).style("opacity", 1).duration(1400).attr("stroke-dashoffset", 0);
                            var g = Yi.SOn().x((function(e) {
                                    return _(new Date(e.dataX))
                                })).y0(m(0)).y1((function(e) {
                                    return m(e.dataY)
                                })).curve(Yi.FdL),
                                x = c.append("g");
                            x.append("mask").attr("id", "gridChartClip").append("rect").attr("fill", "white").attr("width", 0).attr("height", "100%").transition().duration(1400).attr("width", s.current.chartWidth), x.append("path").attr("d", g(t)).attr("mask", "url(#gridChartClip)").style("fill", "url(#lineGradient)").style("filter", "blur(2px)");
                            var b = function(e, n) {
                                return function(i) {
                                    var r = i.append("circle").attr("fill", Vi).attr("r", 3),
                                        o = i.append("text").text(e).attr("font-size", "13px").attr("line-height", "16px").attr("font-weight", "600").attr("fill", Vi).attr("dy", "5px"),
                                        a = o.node().getBBox().width;
                                    0 === n ? (r.style("transform", "translate(2px, 0)"), o.attr("dx", "8px")) : d - _(new Date(t[n].dataX)) < a ? (r.style("transform", "translate(-2px, 0)"), o.attr("dx", "-".concat(a + 8, "px"))) : (r.style("transform", "translate(0, 0)"), o.attr("dx", "6px"))
                                }
                            };
                            if (c.append("g").attr("class", "point").call(b("".concat(i("page.trade.msg266", {
                                    percent: t[a].dataY
                                })), a)).style("opacity", 0).style("transform", "translate(".concat(_(new Date(t[a].dataX)), "px,").concat(m(t[a].dataY) - 9, "px)")).transition().delay(900).duration(400).style("opacity", 1).style("transform", "translate(".concat(_(new Date(t[a].dataX)), "px,").concat(m(t[a].dataY) - 14, "px)")), r !== a) c.append("g").attr("class", "point").call(b("".concat(i("page.trade.msg267", {
                                percent: t[r].dataY
                            })), r)).style("opacity", 0).style("transform", "translate(".concat(_(new Date(t[r].dataX)), "px,").concat(m(t[r].dataY) + 19, "px)")).transition().delay(900).duration(400).style("opacity", 1).style("transform", "translate(".concat(_(new Date(t[r].dataX)), "px,").concat(m(t[r].dataY) + 14, "px)"))
                        }
                    }), [t, n]), (0, u.jsx)("svg", {
                        ref: o
                    })
                })),
                Ji = c().bind(Gn),
                $i = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.localeService.locale,
                        n = e.coinService.getCoin,
                        i = Ki(n.coinType, "Hold").data,
                        s = (0, a.useState)(!1),
                        c = (0, o.Z)(s, 2),
                        l = c[0],
                        d = c[1],
                        _ = (0, S.nX)(),
                        m = _.visible,
                        p = _.setTarget,
                        f = (0, a.useRef)(null);
                    (0, a.useEffect)((function() {
                        p(f.current)
                    }), []), (0, a.useEffect)((function() {
                        !l && m && d(m)
                    }), [m]);
                    var h = !i || (0, Qi.Z)(i);
                    return (0, u.jsxs)("div", {
                        className: Ji("hold-rate"),
                        children: [(0, u.jsx)("div", {
                            className: Ji("hold-rate__title-box"),
                            children: (0, u.jsx)("strong", {
                                className: Ji("hold-rate__title"),
                                children: t("page.trade.msg252")
                            })
                        }), (0, u.jsx)("div", {
                            className: Ji("hold-rate__graph"),
                            ref: f,
                            children: h ? (0, u.jsx)("div", {
                                className: Ji("invest-trends-notice"),
                                children: (0, u.jsx)("p", {
                                    className: Ji("invest-trends-notice__text"),
                                    children: t("page.trade.msg261")
                                })
                            }) : (0, u.jsx)("div", {
                                className: Ji("hold-rate-chart"),
                                children: (0, u.jsx)(Xi, {
                                    data: i,
                                    isVisible: l
                                })
                            })
                        })]
                    })
                })),
                er = "var(--G-bithumb-new-trade_buy_01)",
                tr = "var(--G-bithumb-new-trade_sell_01)",
                nr = "var(--G-bithumb-new-gray_04)",
                ir = "var(--G-bithumb-new-gray_02)",
                rr = "var(--G-bithumb-new-gray_05)",
                or = "var(--G-bithumb-new-line_01)",
                ar = "var(--G-bithumb-new-line_02)",
                sr = "var(--G-bithumb-new-line_03)",
                cr = function() {
                    var e = (0, S.PZ)().getInstance,
                        t = function(e) {
                            return e.abs().isLessThanOrEqualTo(999)
                        };
                    return {
                        priceFormatter: function(n) {
                            var i = e(n);
                            if (t(i)) return "0";
                            var r = i.isGreaterThan(0) ? "+" : "",
                                o = i.dividedBy(C.Az).toFormat(function(e) {
                                    return e.abs().isGreaterThanOrEqualTo(C.hL) ? 0 : e.abs().isGreaterThanOrEqualTo(1e4) ? 2 : 3
                                }(i));
                            return "".concat(r).concat(e(o).toFormat())
                        },
                        getBarColor: function(n) {
                            var i = e(n);
                            return t(i) ? nr : i.isGreaterThanOrEqualTo(0) ? er : tr
                        },
                        getTooltipPriceColor: function(n) {
                            var i = e(n);
                            return t(i) ? rr : i.isGreaterThanOrEqualTo(0) ? er : tr
                        },
                        getValueForScale: function(n) {
                            var i = e(n);
                            return t(i) ? 0 : n
                        }
                    }
                },
                lr = (0, l.Pi)((function(e) {
                    var t = e.data,
                        n = e.isVisible,
                        i = (0, r.G2)().localeService.locale,
                        s = cr(),
                        c = s.priceFormatter,
                        l = s.getTooltipPriceColor,
                        d = s.getBarColor,
                        _ = s.getValueForScale,
                        m = (0, a.useRef)(null),
                        p = (0, a.useRef)({
                            tooltipWidth: 59,
                            tooltipHeight: 47,
                            chartMarginTop: 8,
                            chartMarginBottom: 16,
                            chartWidth: 279,
                            chartHeight: 136,
                            balloonArrowWidth: 8,
                            balloonArrowHeight: 5
                        }),
                        f = {
                            top: p.current.tooltipHeight + p.current.chartMarginTop,
                            right: 0,
                            bottom: p.current.chartMarginBottom + 17,
                            left: 0
                        },
                        h = p.current.tooltipHeight + p.current.chartMarginTop + p.current.chartHeight + p.current.chartMarginBottom;
                    return (0, a.useEffect)((function() {
                        if (n) {
                            Yi.Ys(m.current).selectAll("*").remove();
                            var e = Yi.Xae(t, (function(e, t) {
                                    var n = _(e.dataY),
                                        i = _(t.dataY);
                                    if (n !== i) return n - i;
                                    var r = T()(e.dataX),
                                        o = T()(t.dataX);
                                    return r.isSame(o) ? 0 : r.isAfter(o) ? 1 : -1
                                })),
                                r = Yi.Wem(t.map((function(e) {
                                    var t = e.dataX;
                                    return new Date(t)
                                }))),
                                a = (0, o.Z)(r, 2),
                                s = a[0],
                                u = a[1],
                                g = Math.max.apply(Math, (0, L.Z)(t.map((function(e) {
                                    var t = e.dataY;
                                    return Math.abs(t)
                                })))),
                                x = Yi.Ys(m.current).attr("width", p.current.chartWidth + f.left + f.right).attr("height", p.current.chartHeight + f.top + f.bottom),
                                b = Yi.tiA().domain(t.map((function(e) {
                                    return e.dataX
                                }))).range([0, p.current.chartWidth]).paddingOuter(.1).paddingInner(.3),
                                v = Yi.BYU().domain([-1 * g, g]).range([p.current.chartHeight, 0]),
                                y = x.append("g").style("opacity", 0),
                                j = x.append("g").attr("transform", "translate(".concat(f.left, ", ").concat(f.top, ")")),
                                N = g / 2;
                            j.append("g").attr("class", "middle-line").call(Yi.y4O(v).tickValues([-1 * N, 0, N]).tickSizeOuter(0).tickSize(-p.current.chartWidth).tickFormat("")).call((function(e) {
                                return e.select(".domain").remove()
                            })).call((function(e) {
                                return e.selectAll(".tick line").attr("stroke", or).attr("stroke-dasharray", "2 3")
                            })).call((function(e) {
                                return e.select(".tick:nth-child(2) line").attr("stroke", or).attr("stroke-dasharray", "0")
                            }));
                            var k = x.append("g").style("transform", "translate(0, ".concat(h, "px)")).call(Yi.LLu(b).ticks(7).tickFormat((function(e) {
                                    return i("page.trade.msg268", {
                                        date: new Date(e).getDate()
                                    })
                                })).tickSizeOuter(0)).call((function(e) {
                                    return e.select(".domain").remove()
                                })).call((function(e) {
                                    return e.selectAll(".tick line").remove()
                                })).call((function(e) {
                                    return e.selectAll(".tick text").attr("dx", "0").attr("dy", "6px").attr("font-size", "11px").attr("line-height", "17px").attr("fill", rr)
                                })),
                                C = function(e) {
                                    return function(t) {
                                        var n = b(t.dataX),
                                            i = v(e ? 0 : _(t.dataY)),
                                            r = v(0),
                                            o = Math.max(Math.abs(i - r), 1),
                                            a = b.bandwidth(),
                                            s = Math.min(o, 6);
                                        return t.dataY >= 0 ? "M".concat(n, " ").concat(r, " \n\t\t\t\t\t\t\t\t\t   \t\t\tv -").concat(o - s, " \t \n\t\t\t\ta ").concat(s, " ").concat(s, " 0, 0, 1, ").concat(s, " -").concat(s, "\n\t\t\t\t\t\t\t\t\t   \t\t\th ").concat(a - 2 * s, "\n\t\t\t\t\t\t\t\t\t   \t\ta ").concat(s, " ").concat(s, " 0, 0, 1, ").concat(s, " ").concat(s, "\n\t\t\t\t\t\t\t\t\t   \t\t\tv ").concat(o - s, " \n\t\t\t\t\t\t\t\t\t   \t\t\tZ") : "M".concat(n, " ").concat(r, " \n\t\t\t\t\t\t\t\t\t   \t\t\tv ").concat(o - s, " \t \n\t\t\t\ta ").concat(s, " ").concat(s, " 0, 0, 0, ").concat(s, " ").concat(s, "\n\t\t\t\t\t\t\t\t\t   \t\t\th ").concat(a - 2 * s, "\n\t\t\t\t\t\t\t\t\t   \t\ta ").concat(s, " ").concat(s, " 0, 0, 0, ").concat(s, " -").concat(s, "\n\t\t\t\t\t\t\t\t\t   \t\t\tv -").concat(o - s, " \n\t\t\t\t\t\t\t\t\t   \t\t\tZ")
                                    }
                                },
                                S = function(e, t, n) {
                                    if (!p.current.currentSelectedX || p.current.currentSelectedX !== t.dataX) {
                                        y.selectAll("*").remove();
                                        var r = new Date(t.dataX),
                                            o = b(t.dataX) + b.bandwidth() / 2,
                                            a = y.append("g").call((function(e) {
                                                return e.append("text").append("tspan").text(i("page.trade.msg269", {
                                                    month: r.getMonth() + 1,
                                                    date: r.getDate()
                                                })).attr("font-size", "11px").attr("fill", ir).attr("dy", "1.5em")
                                            })).call((function(e) {
                                                return e.append("text").append("tspan").text(c(t.dataY)).attr("fill", l(t.dataY)).attr("font-size", "13px").attr("font-weight", "600").attr("x", "0").attr("dy", "2.5em")
                                            })),
                                            d = a.node().getBBox().width + 16,
                                            _ = p.current.tooltipHeight - p.current.balloonArrowHeight,
                                            m = (b.bandwidth() - p.current.balloonArrowWidth) / 2,
                                            f = function(e, t) {
                                                return e.getFullYear() === t.getFullYear() && e.getMonth() === t.getMonth() && e.getDate() === t.getDate()
                                            },
                                            h = f(new Date(t.dataX), s) ? {
                                                balloonLeftSide: m,
                                                balloonRightSide: d - p.current.balloonArrowWidth - m
                                            } : f(new Date(t.dataX), u) ? {
                                                balloonRightSide: m,
                                                balloonLeftSide: d - p.current.balloonArrowWidth - m
                                            } : {
                                                balloonLeftSide: (d - p.current.balloonArrowWidth) / 2,
                                                balloonRightSide: (d - p.current.balloonArrowWidth) / 2
                                            },
                                            g = h.balloonLeftSide,
                                            x = h.balloonRightSide;
                                        y.append("path").attr("fill", "none").attr("stroke", ar).attr("stroke-width", "1").attr("d", (function() {
                                            return "M ".concat(o, " ").concat(_ + p.current.balloonArrowHeight, "\n\t\t\t\t\t\tl -").concat(p.current.balloonArrowWidth / 2, " -").concat(p.current.balloonArrowHeight, "\n\t\t\t\t\t\th -").concat(g - 8, "\n\t\t\t\t\t\ta ").concat(8, " ").concat(8, "  0, 0, 1, -").concat(8, " -").concat(8, "\n\t\t\t\t\t\tv -").concat(_ - 16, "\n\t\t\t\t\t\ta ").concat(8, " ").concat(8, "  0, 0, 1, ").concat(8, " -").concat(8, "\n\t\t\t\t\t\th ").concat(d - 16, "\n\t\t\t\t\t\ta ").concat(8, " ").concat(8, "  0, 0, 1, ").concat(8, " ").concat(8, "\n\t\t\t\t\t\tv ").concat(_ - 16, "\n\t\t\t\t\t\ta ").concat(8, " ").concat(8, "  0, 0, 1, -").concat(8, " ").concat(8, "\n\t\t\t\t\t\th -").concat(x - 8, "\n\t\t\t\t\t\tZ\n\t\t\t\t\t")
                                        })), y.append("path").attr("fill", "none").attr("stroke", sr).attr("stroke-dasharray", "2 3").attr("d", (function() {
                                            return "M ".concat(o, " ").concat(p.current.tooltipHeight, " l ", 0, " ").concat(v(0) + p.current.chartMarginTop)
                                        })).style("opacity", 0).transition().delay(n ? 500 : 0).duration(n ? 200 : 0).style("opacity", 1), y.style("opacity", 0).style("transform", "translate(0, 5px)").transition().delay(n ? 500 : 0).duration(n ? 400 : 0).style("opacity", 1).style("transform", "translate(0, 0)");
                                        var j = o - g - p.current.balloonArrowWidth / 2 + 8;
                                        a.style("transform", "translate(".concat(j, "px, 0)")), k.selectAll("g").call((function(e) {
                                            return e.selectAll("text").attr("font-weight", "400").attr("fill", rr)
                                        })).filter((function(e) {
                                            return e === t.dataX
                                        })).call((function(e) {
                                            return e.selectAll("text").attr("font-weight", "500").attr("fill", ir)
                                        })), p.current.currentSelectedX = t.dataX
                                    }
                                };
                            j.append("g").selectAll().data(t).enter().append("path").attr("fill", (function(e) {
                                return d(e.dataY)
                            })).attr("d", C(!0)).transition().duration(600).attr("d", C(!1)), j.append("g").selectAll().data(t).enter().append("rect").attr("x", (function(e) {
                                return b(e.dataX)
                            })).attr("width", b.bandwidth()).attr("height", 2 * v(0)).attr("fill", "transparent").on("click", S), S(0, t[e], !0)
                        }
                    }), [t, n]), (0, u.jsx)("svg", {
                        ref: m
                    })
                })),
                dr = c().bind(Gn),
                _r = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.localeService.locale,
                        n = e.coinService.getCoin,
                        i = Ki(n.coinType, "Invest").data,
                        s = (0, a.useRef)(null),
                        c = (0, a.useState)(!1),
                        l = (0, o.Z)(c, 2),
                        d = l[0],
                        _ = l[1],
                        m = (0, S.nX)(),
                        p = m.visible,
                        f = m.setTarget,
                        h = (0, a.useMemo)((function() {
                            return !i || 0 === i.length
                        }), [i]);
                    return (0, a.useEffect)((function() {
                        f(s.current)
                    }), []), (0, a.useEffect)((function() {
                        !d && p && _(p)
                    }), [p]), (0, u.jsxs)("div", {
                        className: dr("invest-trends"),
                        children: [(0, u.jsxs)("div", {
                            className: dr("invest-trends__title-box"),
                            children: [(0, u.jsx)("strong", {
                                className: dr("invest-trends__title"),
                                children: t("page.trade.msg254")
                            }), (0, u.jsx)("span", {
                                className: dr("invest-trends__title-sub-text"),
                                children: t("page.trade.msg259")
                            })]
                        }), (0, u.jsx)("div", {
                            className: dr("invest-trends__graph"),
                            ref: s,
                            children: h ? (0, u.jsx)("div", {
                                className: dr("invest-trends-notice"),
                                children: (0, u.jsx)("p", {
                                    className: dr("invest-trends-notice__text"),
                                    children: t("page.trade.msg261")
                                })
                            }) : (0, u.jsx)("div", {
                                className: dr("invest-trends-chart"),
                                children: (0, u.jsx)(lr, {
                                    data: i,
                                    isVisible: d
                                })
                            })
                        })]
                    })
                })),
                mr = c().bind(Gn),
                ur = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.localeService.locale,
                        n = e.coinService.getCoin,
                        i = Ki(n.coinType, "BuySell").data,
                        s = (0, a.useRef)(null),
                        c = (0, a.useState)(!1),
                        l = (0, o.Z)(c, 2),
                        d = l[0],
                        _ = l[1],
                        m = (0, S.nX)(),
                        p = m.visible,
                        f = m.setTarget,
                        h = (0, a.useMemo)((function() {
                            if (!d || !i) return {
                                buyRate: 0,
                                sellRate: 0
                            };
                            var e = T()(i.standardDatetime);
                            return {
                                buyRate: i.buyPercent,
                                sellRate: 100 - i.buyPercent,
                                standardHour: e.format("HH"),
                                standardMin: e.format("mm")
                            }
                        }), [i, d]),
                        g = h.buyRate,
                        x = h.sellRate,
                        b = h.standardHour,
                        v = h.standardMin,
                        y = (0, a.useMemo)((function() {
                            return i ? 100 === i.buyPercent ? "buy" : 0 === i.buyPercent ? "sell" : "none" : ""
                        }), [i]);
                    return (0, a.useEffect)((function() {
                        f(s.current)
                    }), []), (0, a.useEffect)((function() {
                        !d && p && _(p)
                    }), [p]), (0, u.jsxs)("div", {
                        className: mr("trade-rate"),
                        children: [(0, u.jsxs)("div", {
                            className: mr("trade-rate__title-box"),
                            children: [(0, u.jsx)("strong", {
                                className: mr("trade-rate__title"),
                                children: t("page.trade.msg256")
                            }), b && v && (0, u.jsx)("span", {
                                className: mr("trade-rate__title-sub-text"),
                                children: t("page.trade.msg260", {
                                    hour: b,
                                    minute: v
                                })
                            })]
                        }), (0, u.jsx)("div", {
                            className: mr("trade-rate__graph"),
                            ref: s,
                            children: i ? (0, u.jsxs)("div", {
                                className: mr("trade-rate-chart"),
                                "data-full-flag": y,
                                children: [(0, u.jsxs)("div", {
                                    className: mr("trade-rate-chart__bar-box"),
                                    children: [(0, u.jsx)("div", {
                                        className: mr("trade-rate-chart__bar", "trade-rate-chart__bar--sell"),
                                        style: {
                                            width: "".concat(x, "%")
                                        }
                                    }), (0, u.jsx)("span", {
                                        className: mr("trade-rate-chart__bar-point")
                                    }), (0, u.jsx)("div", {
                                        className: mr("trade-rate-chart__bar", "trade-rate-chart__bar--buy"),
                                        style: {
                                            width: "".concat(g, "%")
                                        }
                                    })]
                                }), (0, u.jsxs)("div", {
                                    className: mr("trade-rate-chart__label-box"),
                                    children: [(0, u.jsxs)("span", {
                                        className: mr("trade-rate-chart__label", "trade-rate-chart__label--sell"),
                                        children: [t("page.trade.msg263"), (0, u.jsx)("span", {
                                            className: mr("trade-rate-chart__label-num-box"),
                                            children: (0, u.jsx)(Ri.v, {
                                                textClassName: mr("trade-rate-chart__label-num"),
                                                duration: "0.5s",
                                                children: x
                                            })
                                        }), t("page.trade.msg265")]
                                    }), (0, u.jsxs)("span", {
                                        className: mr("trade-rate-chart__label", "trade-rate-chart__label--buy"),
                                        children: [t("page.trade.msg264"), (0, u.jsx)("span", {
                                            className: mr("trade-rate-chart__label-num-box"),
                                            children: (0, u.jsx)(Ri.v, {
                                                textClassName: mr("trade-rate-chart__label-num"),
                                                duration: "0.5s",
                                                children: g
                                            })
                                        }), t("page.trade.msg265")]
                                    })]
                                })]
                            }) : (0, u.jsx)("div", {
                                className: mr("trade-rate-notice"),
                                children: (0, u.jsx)("p", {
                                    className: mr("trade-rate-notice__text"),
                                    children: t("page.trade.msg261")
                                })
                            })
                        })]
                    })
                })),
                pr = c().bind(Gn),
                fr = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.localeService.locale,
                        n = e.coinInfoService,
                        i = n.tooltips,
                        o = n.getInfo,
                        s = n.setTooltips,
                        c = (0, a.useRef)(null),
                        l = Ki(o.coin.coinType).data,
                        d = function(e) {
                            s((0, pe.Z)((0, pe.Z)({}, i), {}, {
                                investment: e
                            }))
                        };
                    return l ? (0, u.jsxs)("div", {
                        className: pr("coin-info-premium-box"),
                        children: [(0, u.jsxs)("h4", {
                            className: pr("investment__title"),
                            children: [t("page.trade.msg249", {
                                coinSymbol: o.coin.coinSymbol
                            }), (0, u.jsxs)("div", {
                                className: pr("coin-info-premium__tooltip-box"),
                                children: [(0, u.jsx)(g.ZP, {
                                    className: pr("coin-info-premium__tooltip-button", {
                                        "coin-info-premium__tooltip-button--active": i.investment
                                    }),
                                    "aria-selected": i.investment,
                                    onClick: function() {
                                        return d(!i.investment)
                                    },
                                    onBlur: function() {
                                        return d(!1)
                                    }
                                }), i.investment && (0, u.jsxs)("div", {
                                    className: pr("coin-info-premium__tooltip", "investment__tooltip"),
                                    ref: c,
                                    children: [(0, u.jsxs)("div", {
                                        className: pr("coin-info-premium__tooltip-inner"),
                                        children: [(0, u.jsx)("strong", {
                                            className: pr("coin-info-premium__tooltip-title"),
                                            children: t("page.trade.msg250")
                                        }), (0, u.jsx)("p", {
                                            className: pr("coin-info-premium__tooltip-text"),
                                            children: t("page.trade.msg251", {
                                                tagBr: (0, u.jsx)("br", {})
                                            })
                                        })]
                                    }), (0, u.jsxs)("div", {
                                        className: pr("coin-info-premium__tooltip-inner"),
                                        children: [(0, u.jsx)("strong", {
                                            className: pr("coin-info-premium__tooltip-title"),
                                            children: t("page.trade.msg252")
                                        }), (0, u.jsx)("p", {
                                            className: pr("coin-info-premium__tooltip-text"),
                                            children: t("page.trade.msg253", {
                                                tagBr: (0, u.jsx)("br", {})
                                            })
                                        })]
                                    }), (0, u.jsxs)("div", {
                                        className: pr("coin-info-premium__tooltip-inner"),
                                        children: [(0, u.jsx)("strong", {
                                            className: pr("coin-info-premium__tooltip-title"),
                                            children: t("page.trade.msg254")
                                        }), (0, u.jsx)("p", {
                                            className: pr("coin-info-premium__tooltip-text"),
                                            children: t("page.trade.msg255", {
                                                tagBr: (0, u.jsx)("br", {})
                                            })
                                        })]
                                    }), (0, u.jsxs)("div", {
                                        className: pr("coin-info-premium__tooltip-inner"),
                                        children: [(0, u.jsx)("strong", {
                                            className: pr("coin-info-premium__tooltip-title"),
                                            children: t("page.trade.msg256")
                                        }), (0, u.jsxs)("ul", {
                                            className: pr("coin-info-premium__tooltip-list"),
                                            children: [(0, u.jsx)("li", {
                                                className: pr("coin-info-premium__tooltip-list-text"),
                                                children: t("page.trade.msg257", {
                                                    hour: 6
                                                })
                                            }), (0, u.jsx)("li", {
                                                className: pr("coin-info-premium__tooltip-list-text"),
                                                children: t("page.trade.msg258", {
                                                    hour: 1
                                                })
                                            })]
                                        })]
                                    })]
                                })]
                            })]
                        }), (0, u.jsx)($i, {}), (0, u.jsx)(_r, {}), (0, u.jsx)(ur, {}), (0, u.jsx)(g.ZP, {
                            className: pr("investment__link"),
                            to: "/insight",
                            children: t("page.trade.msg262")
                        })]
                    }) : null
                })),
                hr = function() {
                    return (0, u.jsx)("div", {
                        className: pr("coin-info-premium-box"),
                        children: (0, u.jsxs)("div", {
                            className: pr("coin-investment-skeleton"),
                            children: [(0, u.jsx)(Yn.pD, {
                                className: pr("coin-investment-skeleton__shape-01")
                            }), (0, u.jsxs)("div", {
                                className: pr("coin-investment-skeleton__body"),
                                children: [(0, u.jsxs)("div", {
                                    className: pr("coin-investment-skeleton__body-card"),
                                    children: [(0, u.jsx)(Yn.pD, {
                                        className: pr("coin-investment-skeleton__shape-02")
                                    }), (0, u.jsx)(Yn.pD, {
                                        className: pr("coin-investment-skeleton__shape-03")
                                    })]
                                }), (0, u.jsxs)("div", {
                                    className: pr("coin-investment-skeleton__body-card"),
                                    children: [(0, u.jsx)(Yn.pD, {
                                        className: pr("coin-investment-skeleton__shape-02")
                                    }), (0, u.jsx)(Yn.pD, {
                                        className: pr("coin-investment-skeleton__shape-03")
                                    })]
                                })]
                            }), (0, u.jsxs)("div", {
                                className: pr("coin-investment-skeleton__graph"),
                                children: [(0, u.jsxs)("div", {
                                    className: pr("coin-investment-skeleton__graph-title"),
                                    children: [(0, u.jsx)(Yn.pD, {
                                        className: pr("coin-investment-skeleton__shape-04")
                                    }), (0, u.jsx)(Yn.pD, {
                                        className: pr("coin-investment-skeleton__shape-04")
                                    })]
                                }), (0, u.jsxs)("div", {
                                    className: pr("coin-investment-skeleton__graph-body"),
                                    children: [(0, u.jsx)(Yn.pD, {
                                        className: pr("coin-investment-skeleton__shape-05")
                                    }), (0, u.jsx)(Yn.pD, {
                                        className: pr("coin-investment-skeleton__shape-06")
                                    }), (0, u.jsx)(Yn.pD, {
                                        className: pr("coin-investment-skeleton__shape-05")
                                    })]
                                }), (0, u.jsxs)("div", {
                                    className: pr("coin-investment-skeleton__graph-value"),
                                    children: [(0, u.jsx)(Yn.pD, {
                                        className: pr("coin-investment-skeleton__shape-07")
                                    }), (0, u.jsx)(Yn.pD, {
                                        className: pr("coin-investment-skeleton__shape-07")
                                    })]
                                })]
                            }), (0, u.jsx)("div", {
                                className: pr("coin-investment-skeleton__more"),
                                children: (0, u.jsx)(Yn.pD, {
                                    className: pr("coin-investment-skeleton__shape-08")
                                })
                            })]
                        })
                    })
                },
                gr = (0, l.Pi)((function() {
                    var e = (0, r.G2)().coinInfoService.getInfo.coin.coinType;
                    return (0, u.jsx)(Yn.nR, {
                        queryKey: ["trade", "info", e],
                        fallback: null,
                        children: (0, u.jsx)(a.Suspense, {
                            fallback: (0, u.jsx)(hr, {}),
                            children: (0, u.jsx)(fr, {})
                        })
                    })
                })),
                xr = c().bind(Gn),
                br = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.sessionService.language,
                        n = e.coinService,
                        i = n.getTicker,
                        s = n.subscribeTicker,
                        c = n.getIntroUnitPrice,
                        l = e.localeService.locale,
                        d = e.coinInfoService,
                        m = d.getInfo,
                        p = d.fnConvertUnitUnderMillion,
                        f = e.developService.fnGetDataId,
                        h = (0, a.useRef)(f()),
                        g = (0, S.PZ)(),
                        x = g.getInstance,
                        b = g.ROUNDING_MODE,
                        v = i(m.coin.coinType, m.market.crncCd),
                        y = m.coin,
                        j = y.coinType,
                        N = y.coinSymbol,
                        k = m.market.crncCd,
                        w = (0, a.useRef)(0),
                        P = $n().getV1HoldersCountQuery,
                        I = (0, zn.k)((0, pe.Z)((0, pe.Z)({}, P(j)), {}, {
                            select: function(e) {
                                if (200 === e.status) return e.data
                            },
                            refetchInterval: function(e) {
                                if (e.state.data) {
                                    var t = e.state.data.data,
                                        n = t.serverTime,
                                        i = t.timestamp,
                                        r = T()(i).add(600, "second").diff(T()(n), "millisecond");
                                    return r > 0 ? r : 1e4
                                }
                                return 1e4
                            }
                        })).data,
                        D = t === _.DfJ.ko,
                        O = function(e) {
                            if (!m.coin.infoOnMarket || !m.coin.infoOnMarket.canTrade) return "-";
                            if (m.market.crncCd === C.Eo) return p(e, {
                                isKrw: D
                            });
                            var n = i(k, C.Eo),
                                r = x(null === n || void 0 === n ? void 0 : n.closePrice),
                                o = x(e).multipliedBy(r),
                                a = o.toFixed(c(o.toFixed(), j, C.Eo).lang, b.ROUND_DOWN);
                            return p(a, {
                                isKrw: t === _.DfJ.ko,
                                isAbb: !1
                            })
                        },
                        Z = (0, a.useState)(O(null === v || void 0 === v ? void 0 : v.value24H)),
                        A = (0, o.Z)(Z, 2),
                        F = A[0],
                        M = A[1];
                    return (0, a.useEffect)((function() {
                        M(O(null === v || void 0 === v ? void 0 : v.value24H));
                        var e = s(j, k, (function(e) {
                            M((function() {
                                return O(null === e || void 0 === e ? void 0 : e.value24H)
                            }))
                        }), h.current);
                        return function() {
                            window.clearTimeout(w.current), e()
                        }
                    }), [j, k, t]), (0, u.jsx)("div", (0, pe.Z)((0, pe.Z)({
                        className: xr("coin-info-premium-box")
                    }, h.current), {}, {
                        children: (0, u.jsxs)("div", {
                            className: xr("related-info"),
                            children: [(0, u.jsxs)("div", {
                                className: xr("related-info__item"),
                                children: [(0, u.jsxs)("span", {
                                    className: xr("related-info__item-title"),
                                    children: [(0, u.jsx)("span", {
                                        className: xr("related-info__item-title-coin-symbol"),
                                        children: N
                                    }), l("page.trade.msg218")]
                                }), (0, u.jsxs)("span", {
                                    className: xr("related-info__item-value"),
                                    children: [x(null === I || void 0 === I ? void 0 : I.numberOfHolders).toFormat(), l("page.trade.msg219")]
                                })]
                            }), (0, u.jsxs)("div", {
                                className: xr("related-info__item"),
                                children: [(0, u.jsx)("span", {
                                    className: xr("related-info__item-title"),
                                    children: l("page.trade.msg220")
                                }), (0, u.jsx)("span", {
                                    className: xr("related-info__item-value"),
                                    children: F
                                })]
                            })]
                        })
                    }))
                })),
                vr = function() {
                    return (0, u.jsx)("div", {
                        className: xr("coin-info-premium-box"),
                        children: (0, u.jsxs)("div", {
                            className: xr("coin-related-skeleton"),
                            children: [(0, u.jsxs)("div", {
                                className: xr("coin-related-skeleton__card"),
                                children: [(0, u.jsx)(Yn.pD, {
                                    className: xr("coin-related-skeleton__shape-01")
                                }), (0, u.jsx)(Yn.pD, {
                                    className: xr("coin-related-skeleton__shape-02")
                                })]
                            }), (0, u.jsxs)("div", {
                                className: xr("coin-related-skeleton__card"),
                                children: [(0, u.jsx)(Yn.pD, {
                                    className: xr("coin-related-skeleton__shape-01")
                                }), (0, u.jsx)(Yn.pD, {
                                    className: xr("coin-related-skeleton__shape-02")
                                })]
                            })]
                        })
                    })
                },
                yr = (0, l.Pi)((function() {
                    var e = (0, r.G2)().localeService.locale;
                    return (0, u.jsx)("div", {
                        className: xr("coin-info-premium-box"),
                        children: (0, u.jsxs)("div", {
                            className: xr("related-info"),
                            children: [(0, u.jsxs)("div", {
                                className: xr("related-info__item"),
                                children: [(0, u.jsx)("span", {
                                    className: xr("related-info__item-title"),
                                    children: e("page.trade.msg218")
                                }), (0, u.jsxs)("span", {
                                    className: xr("related-info__item-value"),
                                    children: ["-", e("page.trade.msg219")]
                                })]
                            }), (0, u.jsxs)("div", {
                                className: xr("related-info__item"),
                                children: [(0, u.jsx)("span", {
                                    className: xr("related-info__item-title"),
                                    children: e("page.trade.msg220")
                                }), (0, u.jsxs)("span", {
                                    className: xr("related-info__item-value"),
                                    children: ["-", e("page.trade.msg232")]
                                })]
                            })]
                        })
                    })
                })),
                jr = (0, l.Pi)((function() {
                    var e = (0, r.G2)().coinInfoService.getInfo.coin.coinType,
                        t = $n().getV1HoldersCountQuery;
                    return (0, u.jsx)(Yn.nR, {
                        queryKey: t(e).queryKey,
                        fallback: (0, u.jsx)(yr, {}),
                        children: (0, u.jsx)(a.Suspense, {
                            fallback: (0, u.jsx)(vr, {}),
                            children: (0, u.jsx)(br, {})
                        })
                    })
                })),
                Nr = c().bind(Gn),
                kr = (0, l.Pi)((function(e) {
                    var t = e.closePrice,
                        n = (0, r.G2)(),
                        i = n.coinService,
                        s = i.subscribeTicker,
                        c = i.getTicker,
                        l = n.coinInfoService.getInfo,
                        d = n.developService.fnGetDataId,
                        _ = (0, a.useRef)(d()),
                        m = l.coin.coinType,
                        p = l.market.crncCd,
                        f = c(m, p),
                        h = (0, a.useCallback)(C.KJ, [m]),
                        g = (0, a.useState)(h(null === f || void 0 === f ? void 0 : f.closePrice, t, $().ROUND_DOWN)),
                        x = (0, o.Z)(g, 2),
                        b = x[0],
                        v = x[1];
                    return (0, a.useEffect)((function() {
                        if ("-1" !== t) {
                            var e = s(m, p, (function(e) {
                                v(h(e.closePrice, t, $().ROUND_DOWN))
                            }), _.current);
                            return function() {
                                e && e()
                            }
                        }
                    }), [m, p]), "-1" === t ? (0, u.jsx)("span", (0, pe.Z)((0, pe.Z)({
                        className: Nr("rising-rate__list-item-rise-rate")
                    }, _.current), {}, {
                        children: "-"
                    })) : (0, u.jsxs)("span", (0, pe.Z)((0, pe.Z)({
                        className: Nr("rising-rate__list-item-rise-rate", {
                            "rising-rate__list-item-rise-rate--type-desc": new($())(b.data).isLessThan(0),
                            "rising-rate__list-item-rise-rate--type-asc": new($())(b.data).isGreaterThan(0)
                        })
                    }, _.current), {}, {
                        children: [new($())(b.data).isGreaterThan(0) ? "+" : new($())(b.data).isLessThan(0) && "-", b.view, "%"]
                    }))
                })),
                Cr = c().bind(Gn),
                Sr = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.localeService.locale,
                        n = e.coinInfoService.getInfo,
                        i = n.coin,
                        a = n.market,
                        s = $n().getV1ContractAmountLastQuery,
                        c = (0, zn.k)((0, pe.Z)((0, pe.Z)({}, s(i.coinType, a.crncCd)), {}, {
                            select: function(e) {
                                if (200 === e.status) return Object.entries(e.data.contAmtLastMap)
                            }
                        })).data,
                        l = function(e) {
                            switch (e) {
                                case "6":
                                default:
                                    return t("page.trade.msg228");
                                case "29":
                                    return t("page.trade.msg229");
                                case "89":
                                    return t("page.trade.msg231")
                            }
                        };
                    return (0, u.jsx)("div", {
                        className: Cr("coin-info-premium-box"),
                        children: (0, u.jsxs)("div", {
                            className: Cr("rising-rate"),
                            children: [(0, u.jsx)("h4", {
                                className: Cr("rising-rate__title"),
                                children: t("page.trade.msg227")
                            }), (0, u.jsx)("ul", {
                                className: Cr("rising-rate__list"),
                                children: null === c || void 0 === c ? void 0 : c.map((function(e) {
                                    var t = (0, o.Z)(e, 2),
                                        n = t[0],
                                        i = t[1];
                                    return (0, u.jsxs)("li", {
                                        className: Cr("rising-rate__list-item"),
                                        children: [(0, u.jsx)("span", {
                                            className: Cr("rising-rate__list-item-period"),
                                            children: l(n)
                                        }), (0, u.jsx)(kr, {
                                            closePrice: i
                                        })]
                                    }, n)
                                }))
                            })]
                        })
                    })
                })),
                wr = function() {
                    return (0, u.jsx)("div", {
                        className: Cr("coin-info-premium-box"),
                        children: (0, u.jsxs)("div", {
                            className: Cr("coin-rising-skeleton"),
                            children: [(0, u.jsx)(Yn.pD, {
                                className: Cr("coin-rising-skeleton__shape-01")
                            }), (0, u.jsxs)("div", {
                                className: Cr("coin-rising-skeleton__table-head"),
                                children: [(0, u.jsx)("div", {
                                    className: Cr("coin-rising-skeleton__table-head-cell"),
                                    children: (0, u.jsx)(Yn.pD, {
                                        className: Cr("coin-rising-skeleton__shape-02")
                                    })
                                }), (0, u.jsx)("div", {
                                    className: Cr("coin-rising-skeleton__table-head-cell", "coin-rising-skeleton__table-head-cell--align-right"),
                                    children: (0, u.jsx)(Yn.pD, {
                                        className: Cr("coin-rising-skeleton__shape-03")
                                    })
                                }), (0, u.jsx)("div", {
                                    className: Cr("coin-rising-skeleton__table-head-cell", "coin-rising-skeleton__table-head-cell--align-right"),
                                    children: (0, u.jsx)(Yn.pD, {
                                        className: Cr("coin-rising-skeleton__shape-04")
                                    })
                                })]
                            }), (0, u.jsxs)("div", {
                                className: Cr("coin-rising-skeleton__table-body"),
                                children: [(0, u.jsxs)("div", {
                                    className: Cr("coin-rising-skeleton__table-body-card"),
                                    children: [(0, u.jsx)("div", {
                                        className: Cr("coin-rising-skeleton__table-body-cell"),
                                        children: (0, u.jsx)(Yn.pD, {
                                            className: Cr("coin-rising-skeleton__shape-05")
                                        })
                                    }), (0, u.jsx)("div", {
                                        className: Cr("coin-rising-skeleton__table-body-cell", "coin-rising-skeleton__table-body-cell--align-right"),
                                        children: (0, u.jsx)(Yn.pD, {
                                            className: Cr("coin-rising-skeleton__shape-06")
                                        })
                                    }), (0, u.jsx)("div", {
                                        className: Cr("coin-rising-skeleton__table-body-cell", "coin-rising-skeleton__table-body-cell--align-right"),
                                        children: (0, u.jsx)(Yn.pD, {
                                            className: Cr("coin-rising-skeleton__shape-07")
                                        })
                                    })]
                                }), (0, u.jsxs)("div", {
                                    className: Cr("coin-rising-skeleton__table-body-card"),
                                    children: [(0, u.jsx)("div", {
                                        className: Cr("coin-rising-skeleton__table-body-cell"),
                                        children: (0, u.jsx)(Yn.pD, {
                                            className: Cr("coin-rising-skeleton__shape-05")
                                        })
                                    }), (0, u.jsx)("div", {
                                        className: Cr("coin-rising-skeleton__table-body-cell", "coin-rising-skeleton__table-body-cell--align-right"),
                                        children: (0, u.jsx)(Yn.pD, {
                                            className: Cr("coin-rising-skeleton__shape-06")
                                        })
                                    }), (0, u.jsx)("div", {
                                        className: Cr("coin-rising-skeleton__table-body-cell", "coin-rising-skeleton__table-body-cell--align-right"),
                                        children: (0, u.jsx)(Yn.pD, {
                                            className: Cr("coin-rising-skeleton__shape-07")
                                        })
                                    })]
                                }), (0, u.jsxs)("div", {
                                    className: Cr("coin-rising-skeleton__table-body-card"),
                                    children: [(0, u.jsx)("div", {
                                        className: Cr("coin-rising-skeleton__table-body-cell"),
                                        children: (0, u.jsx)(Yn.pD, {
                                            className: Cr("coin-rising-skeleton__shape-05")
                                        })
                                    }), (0, u.jsx)("div", {
                                        className: Cr("coin-rising-skeleton__table-body-cell", "coin-rising-skeleton__table-body-cell--align-right"),
                                        children: (0, u.jsx)(Yn.pD, {
                                            className: Cr("coin-rising-skeleton__shape-06")
                                        })
                                    }), (0, u.jsx)("div", {
                                        className: Cr("coin-rising-skeleton__table-body-cell", "coin-rising-skeleton__table-body-cell--align-right"),
                                        children: (0, u.jsx)(Yn.pD, {
                                            className: Cr("coin-rising-skeleton__shape-07")
                                        })
                                    })]
                                })]
                            })]
                        })
                    })
                },
                Tr = (0, l.Pi)((function() {
                    var e = (0, r.G2)().localeService.locale;
                    return (0, u.jsxs)("div", {
                        className: Cr("coin-info-premium-box"),
                        children: [(0, u.jsx)("h4", {
                            className: Cr("rising-rate__title"),
                            children: e("page.trade.msg227")
                        }), (0, u.jsx)(k, {
                            caseStyle: i.coinInfo
                        })]
                    })
                })),
                Pr = (0, l.Pi)((function() {
                    var e = (0, r.G2)().coinInfoService.getInfo,
                        t = e.coin,
                        n = e.market,
                        i = $n().getV1ContractAmountLastQuery;
                    return (0, u.jsx)(Yn.nR, {
                        queryKey: i(t.coinType, n.crncCd).queryKey,
                        fallback: (0, u.jsx)(Tr, {}),
                        children: (0, u.jsx)(a.Suspense, {
                            fallback: (0, u.jsx)(wr, {}),
                            children: (0, u.jsx)(Sr, {})
                        })
                    })
                })),
                Ir = n(31915),
                Dr = c().bind(Gn),
                Or = (0, l.Pi)((function(e) {
                    var t = e.visible,
                        n = e.isAct,
                        i = e.topsHoldRate,
                        s = (0, r.G2)().localeService.locale,
                        c = (0, a.useRef)(null),
                        l = (0, a.useRef)({
                            timer: 0,
                            isAct: n
                        }),
                        d = (0, a.useState)((null === i || void 0 === i ? void 0 : i.holdingPercentage.split("").map((function(e) {
                            return String(Math.abs(Number(e) - 5))
                        }))) || ["0"]),
                        _ = (0, o.Z)(d, 2),
                        m = _[0],
                        p = _[1];
                    return (0, a.useEffect)((function() {
                        return t && !n && (l.current.isAct = !0, p((null === i || void 0 === i ? void 0 : i.holdingPercentage.split("")) || ["0"]), l.current.timer = window.setTimeout((function() {
                                c.current && (c.current.style.strokeDashoffset = "".concat(360 - 3.6 * Number(null === i || void 0 === i ? void 0 : i.holdingPercentage)))
                            }), 300)),
                            function() {
                                window.clearTimeout(l.current.timer)
                            }
                    }), [t, i]), (0, u.jsxs)("div", {
                        className: Dr("tops-impact__graph-item", "tops-impact__graph-item--color-red"),
                        children: [(0, u.jsxs)("svg", {
                            className: Dr("tops-impact__graph-item-svg"),
                            width: "124",
                            height: "124",
                            viewBox: "0 0 124 124",
                            children: [(0, u.jsx)("circle", {
                                className: Dr("tops-impact__graph-item-bg"),
                                cx: "62",
                                cy: "62",
                                r: "57"
                            }), (0, u.jsx)("circle", {
                                ref: c,
                                className: Dr("tops-impact__graph-item-bar"),
                                cx: "62",
                                cy: "62",
                                r: "57"
                            })]
                        }), (0, u.jsxs)("div", {
                            className: Dr("tops-impact__graph-item-text", {
                                "tops-impact__graph-item-text--fade-in": l.current.isAct
                            }),
                            children: [(0, u.jsxs)("strong", {
                                className: Dr("tops-impact__graph-item-text-value"),
                                children: [m[0] && (0, u.jsx)(Ri.v, {
                                    textClassName: Dr("tops-impact__graph-item-text-value-num"),
                                    duration: "0.3s",
                                    children: m[0]
                                }), m[1] && (0, u.jsx)(Ri.v, {
                                    textClassName: Dr("tops-impact__graph-item-text-value-num"),
                                    duration: "0.6s",
                                    children: m[1]
                                }), m[2] && (0, u.jsx)(Ri.v, {
                                    textClassName: Dr("tops-impact__graph-item-text-value-num"),
                                    duration: "0.8s",
                                    children: m[2]
                                }), (0, u.jsx)("span", {
                                    className: Dr("tops-impact__graph-item-text-value-unit"),
                                    children: "%"
                                })]
                            }), (0, u.jsx)("span", {
                                className: Dr("tops-impact__graph-item-text-name"),
                                children: s("page.trade.msg222")
                            })]
                        })]
                    })
                })),
                Zr = c().bind(Gn),
                Ar = (0, l.Pi)((function(e) {
                    var t = e.visible,
                        n = e.isAct,
                        i = e.topsTradeRate,
                        s = (0, r.G2)().localeService.locale,
                        c = (0, a.useRef)(null),
                        l = (0, a.useRef)({
                            timer: 0,
                            isAct: n
                        }),
                        d = (0, a.useState)((null === i || void 0 === i ? void 0 : i.tradingPercentage.split("").map((function(e) {
                            return String(Math.abs(Number(e) - 5))
                        }))) || ["0"]),
                        _ = (0, o.Z)(d, 2),
                        m = _[0],
                        p = _[1];
                    return (0, a.useEffect)((function() {
                        return t && !n && (l.current.isAct = !0, p((null === i || void 0 === i ? void 0 : i.tradingPercentage.split("")) || ["0"]), l.current.timer = window.setTimeout((function() {
                                c.current && (c.current.style.strokeDashoffset = "".concat(360 - 3.6 * Number(null === i || void 0 === i ? void 0 : i.tradingPercentage)))
                            }), 300)),
                            function() {
                                window.clearTimeout(l.current.timer)
                            }
                    }), [t, i]), (0, u.jsxs)("div", {
                        className: Zr("tops-impact__graph-item", "tops-impact__graph-item--color-gray"),
                        children: [(0, u.jsxs)("svg", {
                            className: Zr("tops-impact__graph-item-svg"),
                            width: "124",
                            height: "124",
                            viewBox: "0 0 124 124",
                            children: [(0, u.jsx)("circle", {
                                className: Zr("tops-impact__graph-item-bg"),
                                cx: "62",
                                cy: "62",
                                r: "57"
                            }), (0, u.jsx)("circle", {
                                ref: c,
                                className: Zr("tops-impact__graph-item-bar"),
                                cx: "62",
                                cy: "62",
                                r: "57"
                            })]
                        }), (0, u.jsxs)("div", {
                            className: Zr("tops-impact__graph-item-text", {
                                "tops-impact__graph-item-text--fade-in": l.current.isAct
                            }),
                            children: [(0, u.jsxs)("strong", {
                                className: Zr("tops-impact__graph-item-text-value"),
                                children: [m[0] && (0, u.jsx)(Ri.v, {
                                    textClassName: Zr("tops-impact__graph-item-text-value-num"),
                                    duration: "0.3s",
                                    children: m[0]
                                }), m[1] && (0, u.jsx)(Ri.v, {
                                    textClassName: Zr("tops-impact__graph-item-text-value-num"),
                                    duration: "0.6s",
                                    children: m[1]
                                }), m[2] && (0, u.jsx)(Ri.v, {
                                    textClassName: Zr("tops-impact__graph-item-text-value-num"),
                                    duration: "0.8s",
                                    children: m[2]
                                }), (0, u.jsx)("span", {
                                    className: Zr("tops-impact__graph-item-text-value-unit"),
                                    children: "%"
                                })]
                            }), (0, u.jsx)("span", {
                                className: Zr("tops-impact__graph-item-text-name"),
                                children: s("page.trade.msg224")
                            })]
                        })]
                    })
                })),
                Fr = c().bind(Gn),
                Mr = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.localeService.locale,
                        n = e.coinInfoService,
                        i = n.tooltips,
                        o = n.setTooltips,
                        s = n.getInfo,
                        c = (0, a.useRef)(null),
                        l = (0, a.useRef)({
                            hold: !1,
                            trade: !1
                        }),
                        d = s.coin.coinType,
                        _ = (0, S.nX)({
                            threshold: .5
                        }),
                        m = _.visible,
                        p = _.setTarget,
                        f = $n(),
                        h = f.getV1TopHolderRateQuery,
                        x = f.getV1TopTradeRateQuery,
                        b = (0, zn.k)((0, pe.Z)((0, pe.Z)({}, h(d)), {}, {
                            select: function(e) {
                                if (200 === e.status) return (0, pe.Z)((0, pe.Z)({}, e.data), {}, {
                                    hasHoldingPercentage: "-" !== e.data.holdingPercentage
                                })
                            },
                            refetchInterval: function(e) {
                                if (e.state.data) {
                                    var t = e.state.data.data,
                                        n = t.serverTime,
                                        i = t.timestamp,
                                        r = T()(i).add(600, "second").diff(T()(n), "millisecond");
                                    return r > 0 ? r : 1e4
                                }
                                return 1e4
                            }
                        })).data,
                        v = (0, zn.k)((0, pe.Z)((0, pe.Z)({}, x(d)), {}, {
                            select: function(e) {
                                if (200 === e.status) return (0, pe.Z)((0, pe.Z)({}, e.data), {}, {
                                    hasTradePercentage: "-" !== e.data.tradingPercentage
                                })
                            },
                            refetchInterval: function(e) {
                                if (e.state.data) {
                                    var t = e.state.data.data,
                                        n = t.serverTime,
                                        i = t.timestamp,
                                        r = T()(i).add(600, "second").diff(T()(n), "millisecond");
                                    return r > 0 ? r : 1e4
                                }
                                return 1e4
                            }
                        })).data,
                        y = function() {
                            o({
                                pureDeposit: !1,
                                topsImpact: !i.topsImpact
                            })
                        };
                    return (0, a.useEffect)((function() {
                        l.current.trade = !1
                    }), [v]), (0, a.useEffect)((function() {
                        l.current.hold = !1
                    }), [b]), (0, a.useEffect)((function() {
                        return function() {
                            l.current = {
                                hold: !1,
                                trade: !1
                            }
                        }
                    }), []), (0, a.useEffect)((function() {
                        return i.topsImpact && c.current && c.current.addEventListener("click", y),
                            function() {
                                c.current && c.current.removeEventListener("click", y)
                            }
                    }), [i.topsImpact]), (0, u.jsx)("div", {
                        ref: p,
                        className: Fr("coin-info-premium-box"),
                        children: (0, u.jsxs)("div", {
                            className: Fr("tops-impact"),
                            children: [(0, u.jsxs)("h4", {
                                className: Fr("tops-impact__title"),
                                children: [t("page.trade.msg221"), (0, u.jsxs)("div", {
                                    className: Fr("coin-info-premium__tooltip-box"),
                                    children: [(0, u.jsx)(g.ZP, {
                                        className: Fr("coin-info-premium__tooltip-button", {
                                            "coin-info-premium__tooltip-button--active": i.topsImpact
                                        }),
                                        onClick: y,
                                        onBlur: function() {
                                            o({
                                                pureDeposit: !1,
                                                topsImpact: !1
                                            })
                                        }
                                    }), i.topsImpact && (0, u.jsxs)("div", {
                                        className: Fr("coin-info-premium__tooltip", "tops-impact__tooltip"),
                                        ref: c,
                                        children: [(0, u.jsxs)("div", {
                                            className: Fr("coin-info-premium__tooltip-inner"),
                                            children: [(0, u.jsx)("strong", {
                                                className: Fr("coin-info-premium__tooltip-title"),
                                                children: t("page.trade.msg222")
                                            }), (0, u.jsx)("p", {
                                                className: Fr("coin-info-premium__tooltip-text"),
                                                children: t("page.trade.msg223", {
                                                    tagBr: (0, u.jsx)("br", {})
                                                })
                                            })]
                                        }), (0, u.jsxs)("div", {
                                            className: Fr("coin-info-premium__tooltip-inner"),
                                            children: [(0, u.jsx)("strong", {
                                                className: Fr("coin-info-premium__tooltip-title"),
                                                children: t("page.trade.msg224")
                                            }), (0, u.jsx)("p", {
                                                className: Fr("coin-info-premium__tooltip-text"),
                                                children: t("page.trade.msg225", {
                                                    tagBr: (0, u.jsx)("br", {})
                                                })
                                            })]
                                        })]
                                    })]
                                })]
                            }), null !== b && void 0 !== b && b.hasHoldingPercentage && null !== v && void 0 !== v && v.hasTradePercentage ? (0, u.jsxs)("div", {
                                className: Fr("tops-impact__graph-box"),
                                children: [(0, u.jsx)(Or, {
                                    topsHoldRate: b,
                                    visible: m,
                                    isAct: l.current.hold
                                }), (0, u.jsx)(Ar, {
                                    topsTradeRate: v,
                                    visible: m,
                                    isAct: l.current.trade
                                })]
                            }) : (0, u.jsx)("div", {
                                className: Fr("tops-impact__graph-no-data"),
                                children: (0, u.jsx)("p", {
                                    className: Fr("tops-impact__graph-no-data-text"),
                                    children: t("page.trade.msg226")
                                })
                            })]
                        })
                    })
                })),
                Rr = function() {
                    return (0, u.jsx)("div", {
                        className: Fr("coin-info-premium-box"),
                        children: (0, u.jsxs)("div", {
                            className: Fr("coin-impact-skeleton"),
                            children: [(0, u.jsx)(Yn.pD, {
                                className: Fr("coin-impact-skeleton__shape-01")
                            }), (0, u.jsxs)("div", {
                                className: Fr("coin-impact-skeleton__body"),
                                children: [(0, u.jsx)("div", {
                                    className: Fr("coin-impact-skeleton__donut-outer"),
                                    children: (0, u.jsx)(Yn.pD, {
                                        type: Yn.S0.DonutCircle,
                                        className: Fr("coin-impact-skeleton__donut-01"),
                                        children: (0, u.jsxs)("div", {
                                            className: Fr("coin-impact-skeleton__donut-inner"),
                                            children: [(0, u.jsx)(Yn.pD, {
                                                className: Fr("coin-impact-skeleton__shape-02")
                                            }), (0, u.jsx)(Yn.pD, {
                                                className: Fr("coin-impact-skeleton__shape-03")
                                            })]
                                        })
                                    })
                                }), (0, u.jsx)("div", {
                                    className: Fr("coin-impact-skeleton__donut-outer"),
                                    children: (0, u.jsx)(Yn.pD, {
                                        type: Yn.S0.DonutCircle,
                                        donutSize: 124,
                                        donutThickness: 10,
                                        className: Fr("coin-impact-skeleton__donut-01"),
                                        children: (0, u.jsxs)("div", {
                                            className: Fr("coin-impact-skeleton__donut-inner"),
                                            children: [(0, u.jsx)(Yn.pD, {
                                                className: Fr("coin-impact-skeleton__shape-02")
                                            }), (0, u.jsx)(Yn.pD, {
                                                className: Fr("coin-impact-skeleton__shape-03")
                                            })]
                                        })
                                    })
                                })]
                            })]
                        })
                    })
                },
                Br = (0, l.Pi)((function() {
                    var e = (0, r.G2)().coinInfoService.getInfo.coin.coinType,
                        t = (0, Ir.NL)(),
                        n = $n(),
                        i = n.getV1TopHolderRateQuery,
                        o = n.getV1TopTradeRateQuery;
                    return (0, u.jsx)(Yn.nR, {
                        onReset: function() {
                            var n = t.getQueryCache().find({
                                queryKey: i(e).queryKey
                            });
                            "error" === (null === n || void 0 === n ? void 0 : n.state.status) && t.resetQueries({
                                queryKey: i(e).queryKey
                            });
                            var r = t.getQueryCache().find({
                                queryKey: o(e).queryKey
                            });
                            "error" === (null === r || void 0 === r ? void 0 : r.state.status) && t.resetQueries({
                                queryKey: o(e).queryKey
                            })
                        },
                        queryKey: i(e).queryKey,
                        fallback: null,
                        children: (0, u.jsx)(a.Suspense, {
                            fallback: (0, u.jsx)(Rr, {}),
                            children: (0, u.jsx)(Mr, {})
                        })
                    })
                })),
                Lr = c().bind(Gn),
                Er = (0, l.Pi)((function() {
                    var e = (0, r.G2)().coinInfoService.getInfo;
                    return (0, u.jsxs)("div", {
                        className: Lr("coin-info-premium__content", "cm-gray-scroll"),
                        children: [(0, u.jsx)(Hi, {}), (0, u.jsx)(jr, {}), (0, u.jsx)(Br, {}), e.market.crncCd === C.Eo && (0, u.jsx)(gr, {}), (0, u.jsx)(Pr, {}), (0, u.jsx)(Mi, {})]
                    })
                })),
                Ur = c().bind(Gn),
                qr = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.coinInfoService,
                        n = t.getInfo,
                        i = t.setOpenPremium,
                        s = t.openPremium,
                        c = t.setDragEvent,
                        l = t.setTooltips,
                        d = e.sessionService.language,
                        m = e.localeService.locale,
                        p = n.coin,
                        f = p.coinType,
                        h = p.coinName,
                        x = p.coinSymbol,
                        b = n.market.crncCd,
                        v = {
                            posX: 48,
                            posY: 48
                        },
                        y = (0, a.useState)(v),
                        j = (0, o.Z)(y, 2),
                        N = j[0],
                        k = j[1],
                        C = (0, a.useState)(_.yxo.TRADE_INFO),
                        S = (0, o.Z)(C, 2),
                        w = S[0],
                        T = S[1],
                        P = (0, a.useRef)(null);
                    (0, a.useEffect)((function() {
                        return function() {
                            i(!1)
                        }
                    }), [f, b]), (0, a.useEffect)((function() {
                        return s || k(v),
                            function() {
                                k(v), T(_.yxo.TRADE_INFO), l({
                                    pureDeposit: !1,
                                    topsImpact: !1,
                                    infoTips: !1
                                })
                            }
                    }), [s, d]);
                    var I = (0, a.useCallback)((function() {
                            if (P.current) {
                                var e = P.current.getBoundingClientRect(),
                                    t = e.width,
                                    n = e.height;
                                return {
                                    min: {
                                        width: t,
                                        height: n
                                    },
                                    max: {
                                        width: window.innerWidth - t,
                                        height: window.innerHeight - 96 - n
                                    }
                                }
                            }
                            return {
                                min: {
                                    width: 360,
                                    height: 700
                                },
                                max: {
                                    width: window.innerWidth - 360,
                                    height: window.innerHeight - 96 - 700
                                }
                            }
                        }), [P.current]),
                        D = function(e, t, n) {
                            return e < t ? t : e > n ? n : e
                        };
                    return s ? (0, u.jsx)("div", {
                        ref: P,
                        className: Ur("coin-info-premium"),
                        style: {
                            left: "".concat(N.posX, "px"),
                            bottom: "".concat(N.posY, "px")
                        },
                        children: (0, u.jsxs)("div", {
                            className: Ur("coin-info-premium-inner"),
                            children: [(0, u.jsxs)("div", {
                                className: Ur("coin-info-premium__head"),
                                onMouseDown: function(e) {
                                    e.stopPropagation();
                                    var t = e.pageX,
                                        n = e.pageY;
                                    c(!0);
                                    var i = I(),
                                        r = function(e) {
                                            if (P.current) {
                                                var r = e.pageX - t,
                                                    o = e.pageY - n;
                                                k({
                                                    posX: D(N.posX + r, i.min.width / 2 * -1, i.max.width + i.min.width / 2),
                                                    posY: D(N.posY - o, -1 * (i.min.height - 72), i.max.height)
                                                })
                                            }
                                        };
                                    document.addEventListener("mousemove", r, {
                                        passive: !0
                                    }), document.addEventListener("mouseup", (function() {
                                        c(!1), document.removeEventListener("mousemove", r)
                                    }), {
                                        once: !0
                                    })
                                },
                                children: [(0, u.jsxs)("h3", {
                                    className: Ur("coin-info-premium__head-title"),
                                    children: [(0, u.jsx)(Hn.Z, {
                                        className: Ur("coin-info-premium__head-title-img"),
                                        coinSymbol: x
                                    }), h]
                                }), (0, u.jsx)(g.ZP, {
                                    className: Ur("coin-info-premium__head-button-close"),
                                    onClick: function() {
                                        i(!1)
                                    }
                                })]
                            }), (0, u.jsx)("div", {
                                className: Ur("coin-info-premium__head-tab"),
                                children: (0, u.jsxs)("ul", {
                                    className: Ur("coin-info-tab"),
                                    role: "tablist",
                                    children: [(0, u.jsx)("li", {
                                        className: Ur("coin-info-tab__item"),
                                        role: "tab",
                                        "aria-selected": w === _.yxo.TRADE_INFO,
                                        children: (0, u.jsx)(g.ZP, {
                                            className: Ur("coin-info-tab__button", "coin-info-tab__button--img"),
                                            onClick: function() {
                                                return T(_.yxo.TRADE_INFO)
                                            },
                                            children: (0, u.jsx)("span", {
                                                className: Ur("coin-info-tab__button-text"),
                                                children: m("page.trade.msg280")
                                            })
                                        })
                                    }), (0, u.jsx)("li", {
                                        className: Ur("coin-info-tab__item"),
                                        role: "tab",
                                        "aria-selected": w === _.yxo.GENERAL_INFO,
                                        children: (0, u.jsx)(g.ZP, {
                                            className: Ur("coin-info-tab__button"),
                                            onClick: function() {
                                                return T(_.yxo.GENERAL_INFO)
                                            },
                                            children: (0, u.jsx)("span", {
                                                className: Ur("coin-info-tab__button-text"),
                                                children: m("page.trade.msg279")
                                            })
                                        })
                                    })]
                                })
                            }), w === _.yxo.TRADE_INFO && (0, u.jsx)(Er, {}), w === _.yxo.GENERAL_INFO && (0, u.jsx)(Ai, {})]
                        })
                    }) : null
                })),
                Gr = c().bind({
                    "trade-banner": "TradeBanner_trade-banner__JCNJm",
                    "trade-banner__inner": "TradeBanner_trade-banner__inner__OSYwR",
                    "trade-banner__paragraph": "TradeBanner_trade-banner__paragraph__UzrvQ",
                    "trade-banner__paragraph--type-warning": "TradeBanner_trade-banner__paragraph--type-warning__wKYV3",
                    "trade-banner__paragraph-text": "TradeBanner_trade-banner__paragraph-text__6RIxX",
                    "trade-banner__important": "TradeBanner_trade-banner__important__R6Z1n",
                    "trade-banner__fill-red": "TradeBanner_trade-banner__fill-red__PStTc"
                }),
                Hr = (0, l.Pi)((function(e) {
                    var t, n = e.className,
                        i = (0, r.G2)(),
                        o = i.coinService,
                        s = o.getCoin,
                        c = o.getMarket,
                        l = i.localeService.locale,
                        d = i.sessionService.language,
                        m = (0, a.useMemo)((function() {
                            var e;
                            return null === (e = s.infoOnMarket) || void 0 === e ? void 0 : e.closeExceptedDate
                        }), [s, s.infoOnMarket, null === (t = s.infoOnMarket) || void 0 === t ? void 0 : t.closeExceptedDate]),
                        p = (0, a.useMemo)((function() {
                            var e, t;
                            return t = d === _.DfJ.ko ? "YYYY\ub144 MM\uc6d4 DD\uc77c(ddd) HH:mm" : "YYYY-MM-DD HH:mm", T()(null === (e = s.infoOnMarket) || void 0 === e ? void 0 : e.closeExceptedDate).locale(d === _.DfJ.ko ? "ko" : "en").format(t)
                        }), [m, d]),
                        f = (0, a.useMemo)((function() {
                            var e = "";
                            return e = d === _.DfJ.ko ? s.coinName : s.coinNameEn, "".concat(e, "(").concat(s.coinSymbol, "/").concat(c.marketSymbol, ")")
                        }), [d, s, c]);
                    return m && "0" !== m ? (0, u.jsx)("div", {
                        className: Gr("trade-banner", n),
                        children: (0, u.jsx)("div", {
                            className: Gr("trade-banner__inner"),
                            children: (0, u.jsx)("p", {
                                className: Gr("trade-banner__paragraph", "trade-banner__paragraph--type-warning"),
                                children: (0, u.jsx)("span", {
                                    className: Gr("trade-banner__paragraph-text"),
                                    children: l("page.trade.msg240", {
                                        coin: f,
                                        date: p,
                                        tagEm: (0, u.jsx)("em", {
                                            className: Gr("trade-banner__important")
                                        }),
                                        tagSpan: (0, u.jsx)("span", {
                                            className: Gr("trade-banner__fill-red")
                                        })
                                    })
                                })
                            })
                        })
                    }) : null
                })),
                Qr = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.sessionService.language,
                        n = e.coinService,
                        i = n.getCoin,
                        s = n.getMarket,
                        c = n.getClosePriceInfo,
                        l = n.subscribeTicker,
                        d = e.localeService.locale,
                        m = e.developService.fnGetDataId,
                        p = (0, a.useRef)(m()),
                        f = (0, a.useState)(c(i.coinType, s.crncCd).visiblePrice),
                        h = (0, o.Z)(f, 2),
                        g = h[0],
                        x = h[1],
                        b = t === _.DfJ.ko ? i.coinName : i.coinNameEn,
                        v = "".concat(i.coinSymbol, "/").concat(s.marketSymbol),
                        y = d("comm.title.msg01", {
                            coinName: "".concat(b, "-").concat(v)
                        }),
                        j = d("comm.desc.msg01", {
                            coinName: "".concat(v, "-").concat(b)
                        }),
                        N = navigator.userAgent.indexOf("Prerender") > -1 ? y : "".concat(g, " ").concat(i.coinSymbol, "/").concat(s.marketSymbol);
                    return (0, a.useEffect)((function() {
                        var e = function() {
                                return x((function() {
                                    return c(i.coinType, s.crncCd).visiblePrice
                                }))
                            },
                            t = l(i.coinType, s.crncCd, e, p.current);
                        return e(),
                            function() {
                                return t()
                            }
                    }), [i.coinType, s.crncCd]), (0, u.jsxs)(Zn.q, {
                        defer: !1,
                        children: [(0, u.jsx)("title", (0, pe.Z)((0, pe.Z)({}, p.current), {}, {
                            children: N
                        })), (0, u.jsx)("meta", {
                            name: "description",
                            content: j
                        }), (0, u.jsx)("meta", {
                            property: "og:title",
                            content: y
                        }), (0, u.jsx)("meta", {
                            property: "og:description",
                            content: j
                        }), (0, u.jsx)("meta", {
                            name: "twitter:title",
                            content: y
                        }), (0, u.jsx)("meta", {
                            name: "twitter:description",
                            content: j
                        })]
                    })
                })),
                Kr = n(98280),
                Yr = c().bind({
                    "news-item": "NewsItem_news-item__TinVv",
                    "news-item__button": "NewsItem_news-item__button__bqXS+",
                    "news-item__title": "NewsItem_news-item__title__yyX9F",
                    "news-item--minimum": "NewsItem_news-item--minimum__XJPKc",
                    "news-item__date": "NewsItem_news-item__date__j1fnA"
                }),
                Vr = T()(),
                Wr = (0, l.Pi)((function(e) {
                    var t = e.newsUrl,
                        n = e.categoryNames,
                        i = e.title,
                        o = e.date,
                        a = (0, r.G2)().gaService.fnGASendEvent,
                        s = T()(o).isSame(Vr, "year");
                    return (0, u.jsxs)("div", {
                        className: Yr("news-item", {
                            "news-item--minimum": !s
                        }),
                        children: [(0, u.jsx)(g.ZP, {
                            href: t,
                            onClick: function() {
                                a("\uc8fc\ubb38", "\uc0c1\ub2e8\ub760\ubc30\ub108", "\uae30\uc0ac\ud074\ub9ad", null, {
                                    ep_button_detail: i
                                })
                            },
                            className: Yr("news-item__button"),
                            children: (0, u.jsxs)("span", {
                                className: Yr("news-item__title"),
                                children: [n && (0, u.jsx)("em", {
                                    children: n
                                }), i]
                            })
                        }), (0, u.jsx)("span", {
                            className: Yr("news-item__date"),
                            children: o
                        })]
                    })
                })),
                zr = function() {
                    var e = (0, r.G2)(),
                        t = e.sessionService.language,
                        n = e.localeService.locale,
                        i = e.coinService.getCoin,
                        s = e.service.tradeNewsData,
                        c = (0, a.useState)(null),
                        l = (0, o.Z)(c, 2),
                        d = l[0],
                        _ = l[1],
                        m = (0, a.useState)(!1),
                        u = (0, o.Z)(m, 2),
                        p = u[0],
                        f = u[1],
                        h = (0, a.useState)(null),
                        g = (0, o.Z)(h, 2),
                        x = g[0],
                        b = g[1],
                        v = (0, a.useState)(null),
                        y = (0, o.Z)(v, 2),
                        j = y[0],
                        N = y[1],
                        k = (0, a.useState)(0),
                        C = (0, o.Z)(k, 2),
                        S = C[0],
                        w = C[1],
                        T = {
                            slidesPerView: 1,
                            loop: !0,
                            direction: "vertical",
                            autoplay: {
                                delay: 2e3
                            },
                            speed: 1500,
                            height: 20,
                            onSwiper: _,
                            allowTouchMove: !1
                        },
                        P = function() {
                            var e, t;
                            f(!1), s && (b(null === s || void 0 === s ? void 0 : s.content), w(null === s || void 0 === s ? void 0 : s.numberOfElements), N((e = null === s || void 0 === s ? void 0 : s.content, t = 2, null === e || void 0 === e ? void 0 : e.reduce((function(e, n, i) {
                                var r = Math.floor(i / t),
                                    o = (0, L.Z)(e);
                                return o[r] || (o[r] = []), o[r] = [].concat((0, L.Z)(o[r]), [n]), o
                            }), []))))
                        };
                    return (0, a.useEffect)((function() {
                        var e, t;
                        !d || p || (0, Qi.Z)(x) || "undefined" !== typeof d.autoplay && (d.slideTo(1, 0), S >= 3 ? null === d || void 0 === d || null === (e = d.autoplay) || void 0 === e || e.start() : null === d || void 0 === d || null === (t = d.autoplay) || void 0 === t || t.stop())
                    }), [d, x, p, i.coinType, S]), (0, a.useEffect)((function() {
                        P()
                    }), [i.coinType, s]), {
                        locale: n,
                        language: t,
                        swiper: d,
                        swiperSettings: T,
                        isDropdown: p,
                        totalElements: S,
                        tradeNews: x,
                        tradeNewsConvertData: j,
                        convertToCategoryNames: function(e) {
                            if ((0, Qi.Z)(e)) return "";
                            var t = null === e || void 0 === e ? void 0 : e.reduce((function(e, t) {
                                return "".concat(e, "[").concat(t, "]")
                            }), "").trim();
                            return "".concat(t, " ")
                        },
                        handleClickDropdown: function() {
                            f(!p)
                        }
                    }
                },
                Xr = c().bind({
                    "trade-news-wrap": "TradeNews_trade-news-wrap__Hi6q8",
                    "trade-news": "TradeNews_trade-news__88g9+",
                    "trade-news__title": "TradeNews_trade-news__title__QcN-F",
                    "trade-news-list": "TradeNews_trade-news-list__ufh68",
                    "trade-news-list__item": "TradeNews_trade-news-list__item__7xQHx",
                    "trade-news__button-dropdown": "TradeNews_trade-news__button-dropdown__H0pwv",
                    "trade-news-wrap--dropdown": "TradeNews_trade-news-wrap--dropdown__ymwI7"
                }),
                Jr = (0, l.Pi)((function(e) {
                    var t = e.containerClass,
                        n = zr(),
                        i = n.locale,
                        r = n.language,
                        o = n.swiperSettings,
                        a = n.isDropdown,
                        s = n.totalElements,
                        c = n.tradeNews,
                        l = n.tradeNewsConvertData,
                        d = n.convertToCategoryNames,
                        _ = n.handleClickDropdown;
                    return (0, Qi.Z)(c) ? (0, u.jsx)(u.Fragment, {}) : (0, u.jsx)("div", {
                        className: t,
                        children: (0, u.jsx)("div", {
                            className: Xr("trade-news-wrap", {
                                "trade-news-wrap--dropdown": a
                            }),
                            children: (0, u.jsxs)("div", {
                                className: Xr("trade-news"),
                                children: [(0, u.jsx)("strong", {
                                    className: Xr("trade-news__title"),
                                    children: i("page.trade.msg244")
                                }), a ? (0, u.jsx)("ul", {
                                    className: Xr("trade-news-list"),
                                    children: null === c || void 0 === c ? void 0 : c.map((function(e) {
                                        return (0, u.jsx)("li", {
                                            className: Xr("trade-news-list__item"),
                                            children: (0, u.jsx)(Wr, {
                                                newsUrl: e.newsUrl,
                                                categoryNames: d(e.categoryNames),
                                                title: e.title,
                                                date: (0, C.A7)(e.registerDateTime, r)
                                            })
                                        }, e.id)
                                    }))
                                }) : (0, u.jsx)(Kr.tq, (0, pe.Z)((0, pe.Z)({
                                    className: "".concat(Xr("trade-news-list"), " swiper-trade-news")
                                }, o), {}, {
                                    children: null === l || void 0 === l ? void 0 : l.map((function(e, t) {
                                        var n, i, o, a, s, c, l, _;
                                        return (0, u.jsxs)(Kr.o5, {
                                            children: [(0, u.jsx)("div", {
                                                className: Xr("trade-news-list__item"),
                                                children: (0, u.jsx)(Wr, {
                                                    newsUrl: null === (n = e[0]) || void 0 === n ? void 0 : n.newsUrl,
                                                    categoryNames: d(null === (i = e[0]) || void 0 === i ? void 0 : i.categoryNames),
                                                    title: null === (o = e[0]) || void 0 === o ? void 0 : o.title,
                                                    date: (0, C.A7)(null === (a = e[0]) || void 0 === a ? void 0 : a.registerDateTime, r)
                                                })
                                            }), e[1] && (0, u.jsx)("div", {
                                                className: Xr("trade-news-list__item"),
                                                children: (0, u.jsx)(Wr, {
                                                    newsUrl: null === (s = e[1]) || void 0 === s ? void 0 : s.newsUrl,
                                                    categoryNames: d(null === (c = e[1]) || void 0 === c ? void 0 : c.categoryNames),
                                                    title: null === (l = e[1]) || void 0 === l ? void 0 : l.title,
                                                    date: (0, C.A7)(null === (_ = e[1]) || void 0 === _ ? void 0 : _.registerDateTime, r)
                                                })
                                            })]
                                        }, t)
                                    }))
                                })), s > 2 && (0, u.jsx)(g.ZP, {
                                    onClick: _,
                                    className: Xr("trade-news__button-dropdown")
                                })]
                            })
                        })
                    })
                })),
                $r = c().bind({
                    "trade-snack-bar": "TradeSnackBar_trade-snack-bar__r4OyV",
                    "trade-snack-bar__inner": "TradeSnackBar_trade-snack-bar__inner__Fyf3O",
                    "trade-snack-bar__text": "TradeSnackBar_trade-snack-bar__text__yEz61",
                    "trade-snack-bar__button": "TradeSnackBar_trade-snack-bar__button__n7m45",
                    "trade-snack-bar__button-text": "TradeSnackBar_trade-snack-bar__button-text__g9Fgw"
                }),
                eo = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.localeService.locale,
                        n = e.coinService.getMarket,
                        i = e.service,
                        s = i.setIsOpenSnackBar,
                        c = i.getIsOpenSnackBar,
                        l = (0, d.s0)(),
                        _ = (0, a.useRef)(null),
                        m = (0, a.useRef)(!0),
                        p = (0, a.useRef)(0),
                        f = (0, a.useState)(!1),
                        h = (0, o.Z)(f, 2),
                        x = h[0],
                        b = h[1],
                        v = n.crncCd;
                    (0, a.useEffect)((function() {
                        c ? (b(!0), m.current = !1, p.current = window.setTimeout((function() {
                            m.current = !0
                        }), 50)) : (b(!1), m.current = !0)
                    }), [c]), (0, a.useEffect)((function() {
                        var e = function(e) {
                            var t;
                            !m.current || null !== (t = _.current) && void 0 !== t && t.contains(e.target) || (b(!1), s(!1), p.current && window.clearTimeout(p.current))
                        };
                        return x && document.addEventListener("click", e),
                            function() {
                                x && (document.removeEventListener("click", e), p.current && window.clearTimeout(p.current))
                            }
                    }), [x]);
                    return (0, u.jsx)(u.Fragment, {
                        children: x && (0, u.jsx)("div", {
                            className: $r("trade-snack-bar"),
                            children: (0, u.jsxs)("div", {
                                className: $r("trade-snack-bar__inner"),
                                children: [(0, u.jsx)("p", {
                                    className: $r("trade-snack-bar__text"),
                                    children: t("snackBar.trade.msg01")
                                }), (0, u.jsx)(g.ZP, {
                                    className: $r("trade-snack-bar__button"),
                                    onClick: function() {
                                        b(!1), s(!1), v === C.Eo ? l("/inout/deposit/KRW") : l("/trade/order/".concat(n.marketSymbol, "-KRW"))
                                    },
                                    children: (0, u.jsx)("span", {
                                        className: $r("trade-snack-bar__button-text"),
                                        children: t(v === C.Eo ? "snackBar.trade.msg02" : "snackBar.trade.msg03")
                                    })
                                })]
                            })
                        })
                    })
                })),
                to = c().bind({
                    trade: "Trade_trade__bR9g+",
                    dimmed: "Trade_dimmed__1ZX2W",
                    trade__content: "Trade_trade__content__uBwvc",
                    trade__news: "Trade_trade__news__XSKQl",
                    trade__aside: "Trade_trade__aside__Vh1Sl",
                    trade__main: "Trade_trade__main__xec99",
                    trade__section: "Trade_trade__section__kkBQ7",
                    "trade__main-warning": "Trade_trade__main-warning__BpZpR"
                }),
                no = (0, l.Pi)((function() {
                    var e = (0, r.G2)(),
                        t = e.service,
                        n = t.init,
                        i = t.destroy,
                        s = t.setFirstOrderSetting,
                        c = e.socketService.resetWssOldEventKeys,
                        l = e.coinService,
                        _ = l.getCoinInfo,
                        m = l.getMarketInfo,
                        p = l.setCoinMarket,
                        h = e.modalService,
                        g = h.showModal,
                        b = h.hideModal,
                        v = e.sessionService.getAjaxUserAgreement,
                        y = e.routeService.listen,
                        j = e.localeService.locale,
                        N = e.coinInfoService.dragEvent,
                        k = (0, d.UO)(),
                        C = (0, d.s0)(),
                        S = (0, d.TH)(),
                        w = (0, a.useState)(!1),
                        T = (0, o.Z)(w, 2),
                        P = T[0],
                        I = T[1],
                        D = (0, a.useCallback)((function(e, t) {
                            if (!e) return C("/", {
                                replace: !0
                            });
                            if (s(!1), e) {
                                var n = e.split("-") || ["", ""],
                                    i = (0, o.Z)(n, 2),
                                    r = i[0],
                                    a = void 0 === r ? "" : r,
                                    c = i[1],
                                    l = void 0 === c ? "" : c,
                                    d = _(a).coinType,
                                    u = m(l).crncCd;
                                d && u ? p(d, u) : (p("BTC", "KRW"), setTimeout((function() {
                                    C("/trade/order/BTC-KRW", {
                                        replace: !0
                                    }), g(ne.DzK, {
                                        message: j("page.trade.msg001")
                                    })
                                }), 0)), t && t()
                            }
                        }), [_, m]);
                    return (0, a.useLayoutEffect)((function() {
                        D(k.coin, (function() {
                            return I(!0)
                        }));
                        var e = y((function(e) {
                            if (e.location.pathname.indexOf("/trade/order") > -1) {
                                var t = /[0-9a-zA-Z]{1,}-[0-9a-zA-Z]{2,}$/gi.exec(e.location.pathname);
                                b(ne.DzK), t && D(t[0])
                            }
                        }));
                        return function() {
                            e()
                        }
                    }), []), (0, a.useEffect)((function() {
                        return c("stream"), n(),
                            function() {
                                i(), p("BTC", "KRW")
                            }
                    }), []), (0, a.useEffect)((function() {
                        P && v()
                    }), [S.pathname, P]), P ? (0, u.jsxs)(u.Fragment, {
                        children: [(0, u.jsx)(Qr, {}), (0, u.jsxs)("div", {
                            className: to("trade", {
                                dimmed: N
                            }),
                            children: [(0, u.jsx)(Jr, {
                                containerClass: to("trade__news")
                            }), (0, u.jsxs)("div", {
                                className: to("trade__content"),
                                children: [(0, u.jsxs)("div", {
                                    className: to("trade__aside"),
                                    children: [(0, u.jsx)(Ln.Z, {}), (0, u.jsx)(eo, {})]
                                }), (0, u.jsxs)("div", {
                                    className: to("trade__main"),
                                    children: [(0, u.jsx)(Bn, {}), (0, u.jsx)(In, {}), (0, u.jsx)(Hr, {
                                        className: to("trade__main-warning")
                                    }), (0, u.jsx)(f, {}), (0, u.jsxs)("div", {
                                        className: to("trade__section"),
                                        children: [(0, u.jsx)(De, {}), (0, u.jsx)(Xe, {}), (0, u.jsx)(Tn, {})]
                                    }), (0, u.jsx)(ce, {})]
                                }), (0, u.jsx)(qr, {}), (0, u.jsx)(qn, {})]
                            })]
                        })]
                    }) : (0, u.jsx)("div", {
                        className: "index-loading",
                        children: (0, u.jsx)(x.gb, {})
                    })
                })),
                io = n(17208),
                ro = n(79311),
                oo = n(78083),
                ao = n(18694),
                so = function() {
                    function e(t) {
                        var n = this;
                        (0, ro.Z)(this, e), this.coinService = void 0, this.sessionService = void 0, this.dragEvent = !1, this.openPremium = !1, this.tooltips = {
                            pureDeposit: !1,
                            topsImpact: !1,
                            infoTips: !1,
                            depositNetwork: !1,
                            withdrawNetwork: !1,
                            totalIssueTips: !1,
                            marketCapUsdTips: !1,
                            marketTotalSupplyTips: !1
                        }, this.serverTime = {
                            serverTime: ""
                        }, this.twitterData = void 0, this.setDragEvent = function(e) {
                            n.dragEvent = e
                        }, this.setOpenPremium = function(e) {
                            n.openPremium = e
                        }, this.setTooltips = function(e) {
                            n.tooltips = e
                        }, this.setTwitterData = function(e) {
                            n.twitterData = e
                        }, this.fnConvertUnitUnderMillion = function() {
                            var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : "0",
                                t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {
                                    isKrw: !0,
                                    isAbb: !1
                                },
                                i = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : C.ok.ROUND_DOWN,
                                r = arguments.length > 3 ? arguments[3] : void 0,
                                o = C.DX.getInstance,
                                a = n.sessionService.getRate,
                                s = o(e);
                            if (!t.isKrw) return a(s.toFixed(), 2);
                            var c = 1e8,
                                l = s.abs(),
                                d = s.isLessThan(0),
                                _ = d ? "-" : "";
                            return l.isGreaterThanOrEqualTo(1e4) && l.isLessThan(c) ? _ += "".concat(l.dividedToIntegerBy(1e4).toFormat(0, i), "\ub9cc").concat(r || "") : l.isLessThan(1e4) ? _ += l.toFormat(0, i) + (r || "") : _ += (0, C.Pm)(l.toFixed(), t, void 0, {
                                point: 1,
                                fixed: !1,
                                rounding: i
                            }), _
                        }, this.coinService = t.coinService, this.sessionService = t.sessionService, (0, ao.rC)(this, {
                            dragEvent: ao.LO,
                            openPremium: ao.LO,
                            tooltips: ao.LO,
                            serverTime: ao.LO,
                            twitterData: ao.LO,
                            setDragEvent: ao.aD,
                            setOpenPremium: ao.aD,
                            setTooltips: ao.aD,
                            getInfo: ao.Fl,
                            setTwitterData: ao.aD
                        })
                    }
                    return (0, oo.Z)(e, [{
                        key: "getInfo",
                        get: function() {
                            return {
                                coin: this.coinService.getCoin,
                                market: this.coinService.getMarket
                            }
                        }
                    }]), e
                }(),
                co = so,
                lo = n(19447),
                _o = n(15971),
                mo = function() {
                    function e(t) {
                        var n, i, r = this;
                        (0, ro.Z)(this, e), this.eventEmitter = void 0, this.routeService = void 0, this.httpService = void 0, this.socketService = void 0, this.assetService = void 0, this.modalService = void 0, this.toastService = void 0, this.localeService = void 0, this.coinService = void 0, this.sessionService = void 0, this.gaService = void 0, this.coinMarketReactionDisposer = void 0, this.value24hTimeout = 0, this.tickerListDisposer = null, this.value24hDisposer = null, this.executionDisposer = null, this.lendingList = [], this.hostPubInfo = void 0, this.hostObserver = void 0, this.hostExchange = void 0, this.firstOrderPrice = {
                            ask: "",
                            bid: ""
                        }, this.buySell = 0, this.orderType = 2, this.tradeInfo = {
                            hasCoupon: "N",
                            makerFeeRate: 0,
                            takerFeeRate: 0,
                            minFeeAmt: .01,
                            crncCd: "",
                            coinType: "",
                            tradeFeeRate: "0",
                            tradeFeeFree: "N",
                            lowerLimitRate: "0",
                            upperLimitRate: "0",
                            limitMaxOrderAmt: "0",
                            limitMinOrderAmt: "0",
                            limitMaxOrderQty: "0",
                            limitMinOrderQty: "0",
                            marketMaxOrderAmt: "0",
                            marketMinOrderAmt: "0",
                            tradeFeeRateMaker: "0",
                            tradeFeeRateTaker: "0"
                        }, this.qtyMinMax = {
                            minQty: "0",
                            maxQty: "0",
                            sminQty: "0",
                            smaxQty: "0"
                        }, this.buyInfo = {
                            price: "",
                            qty: "",
                            amt: "",
                            realAmt: "",
                            per: "0",
                            wchUprc: ""
                        }, this.sellInfo = {
                            price: "",
                            qty: "",
                            amt: "",
                            realAmt: "",
                            per: "0",
                            wchUprc: ""
                        }, this.firstOrderSetting = !1, this.needCheckPrice = !1, this.isOpenSnackBar = !1, this.tradeNewsData = null, this.unitPrice = {
                            price: "0",
                            unit: 1
                        }, this.gaSend = function(e, t) {
                            var n = r.coinService,
                                i = n.getCoin,
                                o = n.getMarket,
                                a = "",
                                s = "",
                                c = "";
                            t ? (a = t.price, s = t.quantity, c = t.amount) : 0 === r.buySell ? (a = r.buyInfo.price, s = r.buyInfo.qty, c = r.buyInfo.amt) : (a = r.sellInfo.price, s = r.sellInfo.qty, c = r.sellInfo.amt), r.gaService.fnGAOrder({
                                eventArea: e,
                                buySell: r.buySell,
                                type: r.orderType,
                                hasCoupon: r.tradeInfo.hasCoupon,
                                price: a,
                                quantity: s,
                                amount: c,
                                orderId: (null === t || void 0 === t ? void 0 : t.orderId) || "",
                                getCoin: i,
                                getMarket: o
                            })
                        }, this.setNeedCheckPrice = function(e) {
                            r.needCheckPrice = e
                        }, this.setFirstOrderSetting = function(e) {
                            r.firstOrderSetting = e
                        }, this.setFirstOrderPrice = function(e, t) {
                            r.firstOrderPrice = e, r.resetBuySellValues(t)
                        }, this.setBuySell = function(e, t) {
                            r.buySell = e, t && r.resetBuySellValues()
                        }, this.setOrderType = function(e, t) {
                            r.orderType = e, t && r.resetBuySellValues()
                        }, this.setTradeInfo = function(e) {
                            r.tradeInfo = (0, pe.Z)((0, pe.Z)({}, r.tradeInfo), e), r.setQtyMinMax()
                        }, this.setQtyMinMax = function() {
                            var e = C.DX.getInstance,
                                t = r.coinService,
                                n = t.getCoin,
                                i = t.getMarket,
                                o = t.getIntroTradeInfo,
                                a = t.getIntroUnitPrice,
                                s = o(n.coinType, i.crncCd),
                                c = s.orderQtyUnit,
                                l = s.limitMinOrderValue,
                                d = s.limitMaxOrderValue,
                                _ = s.marketMinOrderValue,
                                m = s.marketMaxOrderValue,
                                u = e(c),
                                p = e(l),
                                f = e(d),
                                h = e(_),
                                g = e(m);
                            if (!(p.isZero() || f.isZero() || u.isZero())) {
                                var x, b, v = 0 === r.buySell,
                                    y = (v ? r.buyInfo : r.sellInfo).price,
                                    j = v ? r.firstOrderPrice.ask : r.firstOrderPrice.bid,
                                    N = "0",
                                    k = "0",
                                    S = "0",
                                    w = "0";
                                "" === y || e(y).isEqualTo(0) || (N = p.div(y).toFixed(), x = a(k = f.div(y).toFixed(), n.coinType, i.crncCd).lang), "" === j || e(j).isEqualTo(0) || (S = h.div(j).toFixed(), b = a(w = g.div(j).toFixed(), n.coinType, i.crncCd).lang), N = e(N).toFixed((0, C.JQ)(c), C.ok.ROUND_UP), k = e(k).toFixed(x, C.ok.ROUND_FLOOR), S = e(S).toFixed((0, C.JQ)(c), C.ok.ROUND_UP), w = e(w).toFixed(b, C.ok.ROUND_FLOOR), u.isGreaterThan(N) && (N = c), u.isGreaterThan(S) && (S = c), r.qtyMinMax = (0, pe.Z)((0, pe.Z)({}, r.qtyMinMax), {}, {
                                    minQty: N,
                                    maxQty: k,
                                    sminQty: S,
                                    smaxQty: w
                                })
                            }
                        }, this.setBuySellData = function(e) {
                            var t = arguments.length > 1 && void 0 !== arguments[1] && arguments[1];
                            r.buySell ? r.setSells(e, t) : r.setBuys(e, t)
                        }, this.setBuys = function(e, t) {
                            var n = e;
                            t || (n = (0, pe.Z)((0, pe.Z)({}, e), r.reCalcBuySell(e))), r.buyInfo = (0, pe.Z)((0, pe.Z)({}, r.buyInfo), n), r.setQtyMinMax()
                        }, this.setSells = function(e, t) {
                            var n = e;
                            t || (n = (0, pe.Z)((0, pe.Z)({}, e), r.reCalcBuySell(e))), r.sellInfo = (0, pe.Z)((0, pe.Z)({}, r.sellInfo), n), r.setQtyMinMax()
                        }, this.setIsOpenSnackBar = function(e) {
                            r.isOpenSnackBar = e
                        }, this.setTradeNewsData = function(e) {
                            r.tradeNewsData = e
                        }, this.setLendingList = function(e) {
                            r.lendingList = e
                        }, this.setUnitPrice = function(e) {
                            r.unitPrice = e
                        }, this.init = function() {
                            r.changeCoinMarket(), r.coinMarketReactionDisposer = (0, ao.U5)((function() {
                                return [r.coinService.getCoin.coinType, r.coinService.getMarket.crncCd, r.sessionService.login]
                            }), r.changeCoinMarket), r.tickerListDisposer = r.coinService.subscribeTickerList(r.fnHandleTickerListChange), r.executionDisposer = r.socketService.subscribeExecution((function(e) {
                                var t = r.localeService.locale,
                                    n = r.coinService,
                                    i = n.getCoinInfo,
                                    o = n.getMarketInfo,
                                    a = [];
                                if (e.msgCode) {
                                    var s = e.msgCode;
                                    "trade.order.msg28" !== e.msgCode && ("trade.order.msg46" === e.msgCode && (s = t("toast.trade.msg02")), a.push(s), r.toastService.addToast(a.join("")))
                                } else if (e.buySellTypeCd.indexOf("R") > -1) {
                                    var c = "R2" === e.buySellTypeCd ? "toast-element__trade-icon--buy" : "toast-element__trade-icon--sell";
                                    a.push('<span class="toast-element__trade-icon '.concat(c, '">\n\t\t\t\t\t\t\t\t').concat(i(e.coinType).coinSymbol, "/").concat(o(e.crncCd).marketSymbol, '\n\t\t\t\t\t\t\t</span>\n\t\t\t\t\t\t\t<span class="toast-element__trade-info">').concat(t("toast.trade.msg03"), "</span>")), r.toastService.addToast(a.join(""))
                                } else {
                                    if (e.buySellTypeCd) {
                                        var l = "2" === e.buySellTypeCd,
                                            d = l ? "toast-element__trade-icon--buy" : "toast-element__trade-icon--sell",
                                            _ = t(l ? "toast.trade.msg06" : "toast.trade.msg07");
                                        a.push('<span class="toast-element__trade-icon '.concat(d, '">\n\t\t\t\t\t\t\t\t\t').concat(i(e.coinType).coinSymbol, "/\n\t\t\t\t\t\t\t\t\t").concat(o(e.crncCd).marketSymbol, "\n\t\t\t\t\t\t\t\t\t").concat(_, "\n\t\t\t\t\t\t\t\t</span>"))
                                    }
                                    var m = new($())(e.contQty || 0),
                                        u = (0, C.JQ)(r.coinService.getIntroTradeInfo(e.coinType, e.crncCd).orderQtyUnit) || 8;
                                    m.isZero() || a.push('<span class="toast-element__trade-info">'.concat(t("toast.trade.msg04"), " ").concat(m.decimalPlaces(u, C.ok.ROUND_DOWN).toFormat(), "</span>"));
                                    var p = new($())(e.contUprc || 0);
                                    p.isZero() || a.push('<span class="toast-element__trade-info">'.concat(t("toast.trade.msg05"), " ").concat(p.toFormat(), "</span>")), r.toastService.addToast(a.join(""))
                                }
                            })), r.coinService.getAjaxValue24hAll("ALL"), r.value24hDisposer = r.coinService.subscribeValue24hAll((function() {
                                clearTimeout(r.value24hTimeout), r.value24hTimeout = window.setTimeout((function() {
                                    r.coinService.getAjaxValue24hAll("ALL")
                                }), 1e4)
                            }))
                        }, this.destroy = function() {
                            clearTimeout(r.value24hTimeout), r.tickerListDisposer && r.tickerListDisposer(), r.executionDisposer && r.executionDisposer(), r.value24hDisposer && r.value24hDisposer(), r.coinMarketReactionDisposer && r.coinMarketReactionDisposer()
                        }, this.fnHandleTickerListChange = function() {
                            var e = window.location.pathname.match(/(\w){1,}-(\w){3,}/);
                            if (r.socketService.resetWssOldEventKeys("stream"), window.location.pathname.includes("trade") && e) {
                                var t = r.coinService.getCoin.coinSymbol;
                                if (t !== e[0].replace(/-\w{3,}/, "")) return void r.routeService.replace("/trade/order/".concat(t, "-").concat(r.coinService.getMarket.marketSymbol))
                            }
                            r.subscribeSocket()
                        }, this.subscribeSocket = function() {
                            r.socketService.subscribe((0, C.Lj)({
                                secondsTicker: !0,
                                transaction: !0,
                                login: r.sessionService.login,
                                orderBook: {
                                    arcUnit: C.s8.indexOf(r.unitPrice.unit)
                                }
                            }), r.coinService.getWsCoinInfos())
                        }, this.fnClosePrice = function() {
                            var e = r.coinService.getTicker();
                            return e ? r.fnPriceUnitFixed(e.closePrice) : ""
                        }, this.fnPriceUnitFixed = function(e) {
                            var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : r.coinService.getCoin.coinType,
                                n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : r.coinService.getMarket.crncCd,
                                i = r.coinService.getIntroUnitPrice;
                            return new($())(e).toFixed(i(e, t, n).lang, $().ROUND_DOWN)
                        }, this.resetBuySellValues = function(e) {
                            var t = r.fnClosePrice(),
                                n = r.firstOrderPrice.ask && !new($())(r.firstOrderPrice.ask).isZero() ? r.firstOrderPrice.ask : "",
                                i = r.firstOrderPrice.bid && !new($())(r.firstOrderPrice.bid).isZero() ? r.firstOrderPrice.bid : "";
                            if (e) {
                                if (r.buyInfo.price || r.sellInfo.price || !t) return;
                                n || (n = t), i || (i = t), r.setFirstOrderSetting(!0)
                            }
                            var o = {
                                buy: {
                                    price: !n && r.firstOrderSetting ? t : n,
                                    wchUprc: !n && r.firstOrderSetting ? t : n
                                },
                                sell: {
                                    price: !i && r.firstOrderSetting ? t : i,
                                    wchUprc: !i && r.firstOrderSetting ? t : i
                                }
                            };
                            if (!e) {
                                var a = {
                                    price: "",
                                    qty: "",
                                    amt: "",
                                    per: "0",
                                    wchUprc: "",
                                    realAmt: ""
                                };
                                o.buy = (0, pe.Z)((0, pe.Z)({}, a), o.buy), o.sell = (0, pe.Z)((0, pe.Z)({}, a), o.sell)
                            }
                            r.setBuys(o.buy, !0), r.setSells(o.sell, !0), r.setQtyMinMax()
                        }, this.changeCoinMarket = function() {
                            var e = r.sessionService.login;
                            window.privateParams ? delete window.privateParams : r.setOrderType(2), r.setFirstOrderPrice({
                                ask: "",
                                bid: ""
                            }, !1), r.resetBuySellValues(), Promise.all([new Promise((function(e) {
                                r.httpService.get("/v2/trade/crnc-coin-info/".concat(r.coinService.getCoin.coinType, "-").concat(r.coinService.getMarket.crncCd)).then((function(e) {
                                    if (200 === e.status) {
                                        var t = (0, _o.Z)(e.data, ["limitMaxOrderQty", "limitMinOrderQty", "limitMinOrderAmt", "limitMaxOrderAmt", "marketMinOrderAmt", "marketMaxOrderAmt"]);
                                        r.setTradeInfo(t)
                                    }
                                })).finally(e)
                            })), new Promise((function(e) {
                                r.httpService.get("/v1/trade/member-trade-fee-rate/".concat(r.coinService.getCoin.coinType, "-").concat(r.coinService.getMarket.crncCd)).then((function(e) {
                                    200 === e.status && r.setTradeInfo(e.data)
                                })).finally(e)
                            })), new Promise((function(t) {
                                e ? r.assetService.getAjaxAssetChangeList([r.coinService.getCoin.coinType, r.coinService.getMarket.crncCd], t) : t()
                            })), new Promise((function(e) {
                                r.httpService.get("".concat(r.hostPubInfo, "/v1/coin-news"), {
                                    coinType: r.coinService.getCoin.coinType,
                                    majorNewsYn: "N",
                                    page: 0,
                                    size: 10
                                }, !0).then((function(e) {
                                    200 === e.status && r.setTradeNewsData(e.data)
                                })).finally(e)
                            }))]).then((function() {
                                r.subscribeSocket()
                            })), Fe.Z.post("".concat(r.hostExchange, "/v1/trade/indicator/popular-searching-coin"), {
                                crncCd: r.coinService.getMarket.crncCd,
                                coinType: r.coinService.getCoin.coinType
                            }, {
                                headers: {
                                    "Content-Type": "application/json;charset=UTF-8"
                                }
                            })
                        }, this.orderConfirm = function(e) {
                            var t = C.DX.getInstance,
                                n = r.coinService,
                                i = n.getCoin,
                                o = n.getMarket,
                                a = n.getTicker,
                                s = n.fnAvailableToKrw,
                                c = n.getIntroTradeInfo,
                                l = n.getIntroUnitPrice,
                                d = r.modalService,
                                m = d.showModal,
                                u = d.hideModal,
                                p = r.assetService.getCoinAsset,
                                f = r.localeService.locale,
                                h = r.sessionService.getCustom,
                                g = c(i.coinType, o.crncCd),
                                x = g.limitMinOrderValue,
                                b = g.limitMaxOrderValue,
                                v = g.marketMinOrderValue,
                                y = g.marketMaxOrderValue,
                                j = t(v),
                                N = t(y),
                                k = t(b),
                                S = t(x),
                                w = 0 === r.buySell,
                                T = w ? r.buyInfo : r.sellInfo,
                                P = !1,
                                I = !1;
                            if (w) {
                                var D = p(o.crncCd),
                                    O = t(null === D || void 0 === D ? void 0 : D.available).dp(o.crncCd === C.Eo ? 0 : 8);
                                O.isZero() ? (o.crncCd === C.Eo || o.crncCd === C.B4 ? r.setIsOpenSnackBar(!0) : m(ne.DzK, {
                                    message: f("page.trade.msg176")
                                }), P = !0) : (O.isLessThan(T.amt) || O.isLessThan(T.realAmt)) && (o.crncCd === C.Eo || o.crncCd === C.B4 ? r.setIsOpenSnackBar(!0) : m(ne.DzK, {
                                    message: f("page.trade.msg177")
                                }), P = !0)
                            } else {
                                var Z = p(i.coinType);
                                t(null === Z || void 0 === Z ? void 0 : Z.available).dp(8).isLessThan(T.qty) && (m(ne.DzK, {
                                    message: f("page.trade.msg178")
                                }), P = !0)
                            }
                            if (!P) {
                                if (1 === r.orderType)(w || "" !== T.qty && Number(T.qty)) && (!w || "" !== T.amt && Number(T.amt)) ? w || Number(T.amt) || !Number(T.qty) || r.firstOrderPrice.bid ? j.isGreaterThan(T.amt) ? (m(ne.DzK, {
                                    message: f("page.trade.msg180", {
                                        price: j.toFormat(),
                                        type: (0, C.c0)(o.marketSymbol)
                                    })
                                }), P = !0) : N.isLessThan(T.amt) && (m(ne.DzK, {
                                    message: f("page.trade.msg181", {
                                        price: N.toFormat(),
                                        type: (0, C.c0)(o.marketSymbol)
                                    })
                                }), P = !0) : (m(ne.DzK, {
                                    message: f("page.trade.msg179")
                                }), P = !0) : I = !0;
                                else if (2 === r.orderType)
                                    if ("" !== T.qty && Number(T.qty)) {
                                        var A, F, M = r.fnCalcMinMax(null !== (A = null === (F = a()) || void 0 === F ? void 0 : F.visibleClosePrice) && void 0 !== A ? A : "0", i.coinType, o.crncCd, !0),
                                            R = M.min,
                                            B = M.max,
                                            L = t(T.price && "" !== T.price ? T.price : "0");
                                        L.isZero() || !L.isLessThan(R.replace(/,/g, "")) || t(R.replace(/,/g, "")).isZero() ? L.isZero() || !L.isGreaterThan(B.replace(/,/g, "")) || t(B.replace(/,/g, "")).isZero() || (m(ne.DzK, {
                                            message: f("page.trade.msg183", {
                                                num: B,
                                                type: (0, C.c0)(o.marketSymbol)
                                            })
                                        }), P = !0) : (m(ne.DzK, {
                                            message: f("page.trade.msg182", {
                                                num: R,
                                                type: (0, C.c0)(o.marketSymbol)
                                            })
                                        }), P = !0)
                                    } else I = !0;
                                else if ("" !== T.qty && Number(T.qty)) {
                                    var E, U = t(null === (E = a()) || void 0 === E ? void 0 : E.closePrice);
                                    if (U.isZero()) m(ne.DzK, {
                                        message: f("page.trade.msg187")
                                    }), P = !0;
                                    else if (Number(T.wchUprc) && "" !== T.wchUprc) {
                                        if (U.isEqualTo(T.wchUprc)) m(ne.DzK, {
                                            message: f("page.trade.msg185")
                                        }), P = !0;
                                        else if (o.crncCd === C.Eo) {
                                            var q = l(T.wchUprc, i.coinType, o.crncCd).unitPrice,
                                                G = q.split("."),
                                                H = T.wchUprc.split(".");
                                            if (-1 !== q.indexOf(".")) H.length > 1 && G[1].length < H[1].length && (m(ne.DzK, {
                                                message: f("page.trade.msg186", {
                                                    num: t(q).toFormat(),
                                                    type: (0, C.c0)(o.marketSymbol)
                                                })
                                            }), P = !0);
                                            else if (t(T.wchUprc).modulo(q).isGreaterThan(0)) m(ne.DzK, {
                                                message: f("page.trade.msg186", {
                                                    num: t(q).toFormat(),
                                                    type: (0, C.c0)(o.marketSymbol)
                                                })
                                            }), P = !0;
                                            else {
                                                var Q, K, Y = r.fnCalcMinMax(null !== (Q = null === (K = a()) || void 0 === K ? void 0 : K.visibleClosePrice) && void 0 !== Q ? Q : "0", i.coinType, o.crncCd, !0),
                                                    V = Y.min,
                                                    W = Y.max,
                                                    z = t(T.wchUprc);
                                                z.isLessThan(V.replace(/,/g, "")) ? (m(ne.DzK, {
                                                    message: f("page.trade.msg188", {
                                                        num: V,
                                                        type: (0, C.c0)(o.marketSymbol)
                                                    })
                                                }), P = !0) : z.isGreaterThan(W.replace(/,/g, "")) && (m(ne.DzK, {
                                                    message: f("page.trade.msg189", {
                                                        num: W,
                                                        type: (0, C.c0)(o.marketSymbol)
                                                    })
                                                }), P = !0)
                                            }
                                        }
                                    } else m(ne.DzK, {
                                        message: f("page.trade.msg184")
                                    }), P = !0;
                                    if (!P) {
                                        var X = r.fnCalcMinMax(T.wchUprc, i.coinType, o.crncCd, !0),
                                            J = X.min,
                                            $ = X.max,
                                            ee = t(T.price && "" !== T.price ? T.price : "0");
                                        !ee.isZero() && ee.isLessThan(J.replace(/,/g, "")) ? (m(ne.DzK, {
                                            message: f("page.trade.msg182", {
                                                num: J,
                                                type: (0, C.c0)(o.marketSymbol)
                                            })
                                        }), P = !0) : !ee.isZero() && ee.isGreaterThan($.replace(/,/g, "")) && (m(ne.DzK, {
                                            message: f("page.trade.msg183", {
                                                num: $,
                                                type: (0, C.c0)(o.marketSymbol)
                                            })
                                        }), P = !0)
                                    }
                                } else I = !0;
                                if (1 !== r.orderType && !P && !I) {
                                    var te = l(T.price, i.coinType, o.crncCd).unitPrice,
                                        ie = te.split("."),
                                        re = T.price.split("."); - 1 !== te.indexOf(".") && re.length > 1 && ie[1].length < re[1].length || t(T.price).modulo(te).isGreaterThan(0) ? (m(ne.DzK, {
                                        message: f("page.trade.msg190", {
                                            num: t(te).toFormat(),
                                            type: (0, C.c0)(o.marketSymbol)
                                        })
                                    }), P = !0) : k.isLessThan(T.amt) || k.isLessThan(T.realAmt) ? (m(ne.DzK, {
                                        message: f("page.trade.msg191", {
                                            num: k.toFormat(),
                                            type: (0, C.c0)(o.marketSymbol)
                                        })
                                    }), P = !0) : S.isGreaterThan(T.amt) && (m(ne.DzK, {
                                        message: f("page.trade.msg192", {
                                            num: S.toFormat(),
                                            type: (0, C.c0)(o.marketSymbol)
                                        })
                                    }), P = !0)
                                }
                            }
                            if (I && !P) {
                                var oe = w && 1 === r.orderType ? f("page.trade.msg193") : f("page.trade.msg194");
                                m(ne.DzK, {
                                    message: oe
                                }), P = !0
                            }
                            if (P) e && e(P);
                            else {
                                var ae = function() {
                                        h(_._28.hideOrderConfirm) ? (r.setOrders(), e && e(P)) : m(ne.ZM, {
                                            okCallback: function() {
                                                r.setOrders(), u(ne.ZM)
                                            },
                                            cancelCallback: function() {
                                                e && e(P), u(ne.ZM)
                                            }
                                        }), r.gaSend("info")
                                    },
                                    se = T.amt;
                                o.crncCd !== C.Eo && (se = s(se, o.crncCd, C.Eo, !1, !1)), t(se).isGreaterThanOrEqualTo(5e8) ? m(ne.DzK, {
                                    message: f("page.trade.msg213"),
                                    modalBtn: {
                                        text: f("button.msg08"),
                                        callback: function() {
                                            e && e(P)
                                        }
                                    },
                                    modalBtn1: {
                                        text: f("button.msg11"),
                                        feature: dn.y_.CUSTOM,
                                        callback: ae
                                    }
                                }) : ae()
                            }
                        }, this.setOrders = function() {
                            var e = r.coinService,
                                t = e.getCoin,
                                n = e.getMarket,
                                i = r.httpService.post,
                                o = r.localeService.locale,
                                a = 0 === r.buySell,
                                s = a ? r.buyInfo : r.sellInfo,
                                c = {
                                    tradeTypeCd: a ? "2" : "1",
                                    coinType: t.coinType,
                                    crncCd: n.crncCd,
                                    orderPttnCd: r.orderType.toString(),
                                    unitPrice: s.price,
                                    orderQty: s.qty || "0",
                                    orderAmt: s.amt,
                                    wchPrice: "",
                                    orderType: "1"
                                };
                            1 === r.orderType ? (c.unitPrice = "0", c.orderQty = a ? "" : s.qty, c.orderAmt = a ? s.amt : "0") : 3 === r.orderType && (c.orderAmt = "", c.orderQty = s.qty, c.orderPttnCd = "2", c.wchPrice = s.wchUprc, c.orderType = "2"), i("/v1/trade/orders/", c, !0).then((function(e) {
                                if (200 === e.status) {
                                    r.resetBuySellValues();
                                    var i = "2" === e.data.tradeTypeCd,
                                        a = i ? "toast-element__trade-icon--buy" : "toast-element__trade-icon--sell",
                                        s = o(i ? "toast.trade.msg06" : "toast.trade.msg07"),
                                        l = [];
                                    3 === r.orderType ? l.push('<span class="toast-element__trade-icon '.concat(a, '">\n\t\t\t\t\t\t\t').concat(o("toast.trade.msg08"), '\n\t\t\t\t\t\t</span>\n\t\t\t\t\t\t<span class="toast-element__trade-info">\n\t\t\t\t\t\t\t').concat(s, " ").concat(o("toast.trade.msg09"), "\n\t\t\t\t\t\t</span>")) : l.push('<span class="toast-element__trade-icon '.concat(a, '">\n\t\t\t\t\t\t\t').concat(t.coinSymbol, "/\n\t\t\t\t\t\t\t").concat(n.marketSymbol, "\n\t\t\t\t\t\t\t").concat(s, '\n\t\t\t\t\t\t</span>\n\t\t\t\t\t\t<span class="toast-element__trade-info">\n\t\t\t\t\t\t\t').concat(o("toast.trade.msg03"), "\n\t\t\t\t\t\t</span>")), r.toastService.addToast(l.join("")), r.socketService.fnReadyState("wp")[0] || r.eventEmitter.emit(_.W1q, {
                                        type: _.OJD.orderSuccess
                                    }), (0, C.M2)("addToCart", {
                                        marketType: n.crncCd === C.Eo,
                                        sCoinType: t.coinSymbol,
                                        isKakaoPixel: !!t.infoOnMarket && t.infoOnMarket.isKakaoPixel
                                    }), r.gaSend("complete", {
                                        price: c.unitPrice || "",
                                        quantity: c.orderQty || "",
                                        amount: c.orderAmt || "",
                                        orderId: e.data.orderNo
                                    });
                                    try {
                                        window.fbq && window.fbq("track", "Purchase")
                                    } catch (d) {
                                        console.log(d)
                                    }
                                } else {
                                    if (500 === e.status && ("member.fail.00012" === e.code || "member.fail.00200" === e.code || e.code.startsWith("cust.fail"))) return;
                                    "trade.validation.00008" !== e.code || n.crncCd !== C.Eo && n.crncCd !== C.B4 ? r.modalService.showModal(ne.DzK, {
                                        message: e.message
                                    }) : r.setIsOpenSnackBar(!0)
                                }
                            }))
                        }, this.subscribeTradeStatus = function(e) {
                            return r.eventEmitter.on(_.W1q, e),
                                function() {
                                    return r.eventEmitter.off(_.W1q, e)
                                }
                        }, this.reCalcBuySell = function(e) {
                            var t = 0 === r.buySell,
                                n = t ? r.buyInfo : r.sellInfo,
                                i = {};
                            if (e.hasOwnProperty("price")) i = r.fnCalcAmt(e.price, void 0, t);
                            else if (e.hasOwnProperty("qty"))(i = r.fnCalcAmt(void 0, e.qty, t)).per = r.fnReCalcPer(i.amt, t);
                            else if (e.hasOwnProperty("per")) {
                                var o = e.per || "0";
                                (i = r.fnCalcPer(o, void 0, t)).per = o
                            } else e.hasOwnProperty("amt") && ((i = 1 === r.orderType ? {
                                price: n.price,
                                qty: r.fnCalcQty(e.amt, n.price),
                                amt: e.amt
                            } : r.fnCalcAmt(void 0, r.fnCalcQty(e.amt, n.price, $().ROUND_UP), t)).per = r.fnReCalcPer(i.amt, t));
                            return i.price && i.qty ? i.realAmt = new($())(i.price).multipliedBy(i.qty).toFixed() : i.realAmt = "", (0, pe.Z)((0, pe.Z)((0, pe.Z)({}, n), e), i)
                        }, this.fnCalcAmt = function(e, t, n) {
                            var i = e || (n ? r.buyInfo.price : r.sellInfo.price) || "0",
                                o = null !== t && void 0 !== t ? t : n ? r.buyInfo.qty : r.sellInfo.qty,
                                a = o ? "0" : "";
                            if (i && "0" !== i && o && "0" !== o) {
                                var s = r.coinService,
                                    c = s.getCoin,
                                    l = s.getMarket,
                                    d = (0, s.getIntroTradeInfo)(c.coinType, l.crncCd).orderAmtDecimalPlace;
                                a = new($())(o).multipliedBy(i).toFixed(Number(d), $().ROUND_HALF_UP)
                            }
                            return {
                                price: i,
                                qty: o,
                                amt: a
                            }
                        }, this.fnReCalcPer = function(e, t, n) {
                            var i, o, a = e || (t ? r.buyInfo.amt : r.sellInfo.amt) || "0",
                                s = n || (t ? r.buyInfo.price : r.sellInfo.price) || "0";
                            if ("0" === a) return "0";
                            var c = C.DX.getInstance,
                                l = r.assetService.getCoinAsset,
                                d = r.coinService,
                                _ = d.getMarket,
                                m = d.getCoin,
                                u = (0, d.getIntroTradeInfo)(m.coinType, _.crncCd).orderAmtDecimalPlace,
                                p = t ? c(null === (i = l(_.crncCd)) || void 0 === i ? void 0 : i.available) : c(null === (o = l(m.coinType)) || void 0 === o ? void 0 : o.available).multipliedBy(s).decimalPlaces(Number(u), C.ok.ROUND_HALF_UP);
                            return p.isZero() ? "0" : c(a).multipliedBy(c(1).plus(r.tradeInfo.tradeFeeRate)).div(p).multipliedBy(100).plus(c(_.minFeeAmt).multipliedBy(100)).toFixed(0, C.ok.ROUND_FLOOR)
                        }, this.fnCalcPer = function(e, t, n) {
                            var i, o = r.assetService.getCoinAsset,
                                a = r.coinService,
                                s = a.getCoin,
                                c = a.getMarket,
                                l = a.getCoinInfo,
                                d = a.getIntroTradeInfo,
                                _ = 1 === r.orderType;
                            if ("0" === e || void 0 === n) return {
                                qty: "",
                                amt: "",
                                feeAmt: ""
                            };
                            var m = t;
                            m || (m = (n ? r.buyInfo.price : r.sellInfo.price) || "0");
                            var u = _ && new($())(m).isZero() ? new($())((null === (i = l(s.coinType, c.crncCd).infoOnMarket) || void 0 === i ? void 0 : i.listedPrice) || 0) : new($())(m),
                                p = d(s.coinType, c.crncCd),
                                f = p.limitMaxOrderValue,
                                h = p.marketMaxOrderValue,
                                g = p.orderQtyUnit,
                                x = p.orderAmtDecimalPlace,
                                b = Number(x),
                                v = (0, C.JQ)(g),
                                y = {
                                    qty: "",
                                    amt: "",
                                    feeAmt: ""
                                },
                                j = r.tradeInfo.tradeFeeRate,
                                N = r.qtyMinMax,
                                k = N.minQty,
                                S = N.sminQty;
                            if (n) {
                                var w = o(c.crncCd),
                                    T = new($())((null === w || void 0 === w ? void 0 : w.available) || 0).multipliedBy(new($())(e).div(100)),
                                    P = (!_ && T.isGreaterThan(f) ? new($())(f) : T).minus(new($())(c.minFeeAmt).multipliedBy(100)).div(new($())(1).plus(j));
                                if (P.isLessThan(0) && (P = new($())(0)), y.feeAmt = (_ && P.isGreaterThan(h) ? new($())(h) : P).toFixed(b, $().ROUND_DOWN), _ || u.isZero()) {
                                    if (_) {
                                        var I = new($())(h).div(u),
                                            D = new($())(y.feeAmt).div(u);
                                        D.isEqualTo(0) ? D = new($())(0) : D.isLessThan(S) ? D = new($())(S) : D.isGreaterThan(I) && (D = I), y.qty = D.toFixed(v, $().ROUND_DOWN)
                                    }
                                } else {
                                    var O = new($())(f).div(u),
                                        Z = u.multipliedBy(new($())(1).plus(j)),
                                        A = T.minus(new($())(c.minFeeAmt).multipliedBy(100)).div(Z);
                                    T.isEqualTo(0) ? A = new($())(0) : A.isLessThan(k) ? A = new($())(k) : A.isGreaterThan(O) && (A = O), y.qty = A.toFixed(v, $().ROUND_DOWN)
                                }
                            } else {
                                var F = o(s.coinType),
                                    M = (null === F || void 0 === F ? void 0 : F.available) || 0;
                                if (_ || u.isZero()) {
                                    if (_) {
                                        var R = new($())(h).div(u),
                                            B = new($())(M).multipliedBy(new($())(e).div(100));
                                        M ? B.isGreaterThan(0) && B.isLessThan(S) ? B = new($())(S) : B.isGreaterThan(R) && (B = R) : B = new($())(0), y.qty = B.toFixed(v, $().ROUND_DOWN)
                                    }
                                } else {
                                    var L = new($())(M).multipliedBy(new($())(e).div(100)),
                                        E = new($())(f).div(u);
                                    M ? L.isGreaterThan(0) && L.isLessThan(k) ? L = new($())(k) : L.isGreaterThan(E) && (L = E) : L = new($())(0), y.qty = L.toFixed(v, $().ROUND_DOWN)
                                }
                            }
                            return y.qty && !new($())(y.qty).isZero() ? y.amt = u.multipliedBy(y.qty).toFixed(b, $().ROUND_HALF_UP) : y.amt = "0", y
                        }, this.fnCalcQty = function(e, t, n) {
                            var i = C.DX.getInstance,
                                o = i(e).div(t || 0);
                            if (o.isZero() || o.isNaN() || !o.isFinite()) return "0";
                            var a = r.coinService.getCoin.coinType,
                                s = r.coinService.getMarket.crncCd,
                                c = r.coinService.getIntroTradeInfo(a, s).orderQtyUnit,
                                l = (0, C.JQ)(c),
                                d = o.dp(l, null !== n && void 0 !== n ? n : C.ok.ROUND_DOWN),
                                _ = 1 === r.orderType,
                                m = r.qtyMinMax.minQty;
                            return !_ && d.isLessThan(m) && (d = i(m)), d.toFixed()
                        }, this.fnCalcMinMax = function(e, t, n, i) {
                            var o = r.coinService.getIntroUnitPrice,
                                a = C.DX.getInstance,
                                s = {
                                    min: "0",
                                    max: "0",
                                    unitPrice: "0"
                                };
                            if (!e) return s;
                            var c = a(e),
                                l = r.tradeInfo,
                                d = l.lowerLimitRate,
                                _ = l.upperLimitRate,
                                m = d || "90",
                                u = c.multipliedBy(a(1).minus(a(m).div(100))),
                                p = o(u.toFixed(), t, n).unitPrice,
                                f = u.modulo(p);
                            if (f.isZero()) s.min = i ? u.toFormat() : u.toFixed();
                            else {
                                var h;
                                if (u.isInteger()) h = u.plus(p).minus(f);
                                else {
                                    var g = o(e, t, n).unitPrice;
                                    h = u.plus(g).minus(u.modulo(g))
                                }
                                s.min = i ? h.toFormat() : h.toFixed()
                            }
                            var x = _ || "300",
                                b = c.multipliedBy(a(1).plus(a(x).div(100))),
                                v = o(b.toFixed(), t, n).unitPrice,
                                y = b.modulo(v);
                            if (y.isZero()) s.max = i ? b.toFormat() : b.toFixed();
                            else {
                                var j = b.minus(y);
                                s.max = i ? j.toFormat() : j.toFixed()
                            }
                            return s
                        }, this.fnCalcPricePer = function(e, t) {
                            var n, i = C.DX.getInstance,
                                o = r.coinService,
                                a = o.getCoin,
                                s = o.getMarket,
                                c = o.getIntroUnitPrice,
                                l = 0 === r.buySell,
                                d = r.fnCalcMinMax(t, a.coinType, s.crncCd, !0),
                                _ = d.min,
                                m = d.max,
                                u = i(t).multipliedBy(e),
                                p = c(u.toFixed(), a.coinType, s.crncCd).unitPrice,
                                f = i(u).modulo(p);
                            (n = (u = f.isGreaterThan(0) ? l ? u.minus(f).plus(p) : u.minus(f) : u.div(p).multipliedBy(p)).isLessThan(_) ? _ : u.isGreaterThan(m) ? m : u.toFixed(), s.crncCd === C.Eo) && (i(c(u.toFixed(), a.coinType, s.crncCd).lang).isGreaterThan(0) || (n = i(n).toFixed(0, C.ok.ROUND_HALF_UP)));
                            return n
                        }, this.eventEmitter = new lo.EventEmitter, this.routeService = t.routeService, this.httpService = t.httpService, this.socketService = t.socketService, this.assetService = t.assetService, this.modalService = t.modalService, this.toastService = t.toastService, this.localeService = t.localeService, this.gaService = t.gaService, this.coinService = t.coinService, this.sessionService = t.sessionService, (0, ao.rC)(this, {
                            firstOrderPrice: ao.LO,
                            setFirstOrderPrice: ao.aD,
                            buySell: ao.LO,
                            setBuySell: ao.aD,
                            orderType: ao.LO,
                            setOrderType: ao.aD,
                            tradeInfo: ao.LO,
                            setTradeInfo: ao.aD,
                            qtyMinMax: ao.LO,
                            setQtyMinMax: ao.aD,
                            buyInfo: ao.LO,
                            sellInfo: ao.LO,
                            setBuys: ao.aD,
                            setSells: ao.aD,
                            firstOrderSetting: ao.LO,
                            setFirstOrderSetting: ao.aD,
                            needCheckPrice: ao.LO,
                            setNeedCheckPrice: ao.aD,
                            isOpenSnackBar: ao.LO,
                            setIsOpenSnackBar: ao.aD,
                            getIsOpenSnackBar: ao.Fl,
                            tradeNewsData: ao.LO,
                            setTradeNewsData: ao.aD,
                            lendingList: ao.LO,
                            setLendingList: ao.aD,
                            unitPrice: ao.LO,
                            setUnitPrice: ao.aD
                        }), this.hostPubInfo = t.HOST_PUBLIC_INFO, this.hostObserver = t.HOST_OBSERVER, this.hostExchange = t.HOST_EXCHANGE, this.buySell = null !== (n = null === (i = window.privateParams) || void 0 === i ? void 0 : i.buySell) && void 0 !== n ? n : 0
                    }
                    return (0, oo.Z)(e, [{
                        key: "getIsOpenSnackBar",
                        get: function() {
                            return this.isOpenSnackBar
                        }
                    }]), e
                }(),
                uo = mo,
                po = (0, r.FU)(no, {
                    service: uo,
                    coinInfoService: co,
                    lendingService: io.ZP
                })
        },
        29048: function(e, t, n) {
            n.d(t, {
                Vp: function() {
                    return i
                },
                yE: function() {
                    return o
                },
                Vy: function() {
                    return a.V
                },
                E_: function() {
                    return l.E
                },
                MR: function() {
                    return c
                }
            });
            var i = function() {
                    return {
                        LISTING_SUPPORT: "https://listing.bithumb.com",
                        API_DOCS: "https://apidocs.bithumb.com",
                        BITHUMB_CORP: "https://bithumbcorp.com/ko/",
                        BITHUMB_CORP_EN: "https://bithumbcorp.com/en/",
                        SAFE_BITHUMB: "https://safebithumb.com/",
                        PRIVACY_BITHUMB: "https://privacy.bithumb.com/"
                    }
                },
                r = n(46140),
                o = function() {
                    var e = (0, r.G2)().HOST_FEED;
                    return {
                        NOTICE: "".concat(e, "/notice"),
                        MANUAL: "".concat(e, "/manual"),
                        REPORT: "".concat(e, "/report"),
                        TREND: "".concat(e, "/trend"),
                        PRESS: "".concat(e, "/press"),
                        INVESTMENT_WARNING: "".concat(e, "/notice/1640868")
                    }
                },
                a = n(1746),
                s = n(51762),
                c = function() {
                    var e = (0, r.G2)().httpService.get;
                    return (0, s.a)({
                        queryKey: ["main", "ranking"],
                        queryFn: function() {
                            return e("/promotion/v1/rank-contest/competitions/ongoing-count", null, !0).then((function(e) {
                                return 200 === e.status ? e.data.ongoingCompetitionCount : 0
                            }))
                        }
                    })
                },
                l = n(73233)
        },
        1746: function(e, t, n) {
            n.d(t, {
                V: function() {
                    return i
                }
            });
            var i = function() {
                return {
                    TRADE_ORDER: "/trade/order/BTC-KRW",
                    TRADE_STATUS: "/trade/status/BTC-KRW",
                    ASSET_MY: "/assets/my",
                    ASSETS_BALANCE: "/assets/balance",
                    TRADE_HISTORY: "/history/trade",
                    ORDER_HISTORY: "/history/order",
                    INOUT_HISTORY: "/history/inout",
                    AUTO_TRADING_HISTORY: "/history/auto-trading",
                    LENDING_UPTURN_HISTORY: "/lending/upturn/history",
                    INOUT_MAIN: "/inout/deposit/KRW",
                    ADDRESS_BOOK: "/inout/address",
                    LIMIT_RAISE: "/inout/limit/request",
                    INSIGHT: "/insight",
                    TRANSFER: "/transfer",
                    VOUCHER: "/voucher",
                    FEE_COUPON: "/fee-coupon",
                    B_POINT: "/b-point",
                    MEMBERSHIP: "/membership",
                    STAKING: "/staking",
                    LENDING: "/lending",
                    RANKING: "/ranking",
                    AUTO_TRADING: "/legacy/trade/auto_trading/BTC_KRW",
                    AUTOMATIC_SALE: "/automatic-sale",
                    EVENT: "/feed/event",
                    EVENT_COUPON: "/legacy/additional_service/coupon",
                    BULLET_BOARD: "/legacy/customer_support/question_list",
                    PROOF_CENTER: "/legacy/customer_support/proof",
                    ISSUANCE_CENTER: "/issuance",
                    INFO_SERVICE: "/legacy/customer_support/info",
                    INFO_FEE: "/info/fee/trade",
                    INFO_INOUT_CONDITION: "/info/inout-condition",
                    INFO_DAILY: "/daily-prices",
                    TERM_INFO: "/terms/info-terms",
                    TERM_API: "/terms/info-api",
                    TERM_CUSTOMER: "/terms/info-customer",
                    TERM_IMG_INFO: "/terms/info-image-information",
                    ARBITRAGE_HISTORY: "/arbitrage/history"
                }
            }
        },
        73233: function(e, t, n) {
            n.d(t, {
                E: function() {
                    return o
                }
            });
            var i = n(46140),
                r = n(51762),
                o = function() {
                    var e = (0, i.G2)(),
                        t = e.httpService.get,
                        n = e.HOST_FEED_API;
                    return (0, r.a)({
                        queryKey: ["main", "notice"],
                        queryFn: function() {
                            return t("".concat(n, "/v2/customer-support/articles/notices/recent?count=").concat(5), null, !1, {
                                hideNetworkModal: !0
                            }).then((function(e) {
                                return 200 === e.status ? e.data : []
                            }))
                        }
                    })
                }
        },
        5642: function(e, t, n) {
            var i = n(61879),
                r = n(11792),
                o = n(78083),
                a = n(79311),
                s = n(26958),
                c = n.n(s),
                l = n(83178),
                d = n.n(l),
                _ = n(18694);
            c().extend(d());
            var m = "9999-01-01",
                u = (0, o.Z)((function e(t) {
                    var n = this;
                    (0, a.Z)(this, e), this.httpService = void 0, this.animationFrameId = null, this.serverSyncTimeoutId = void 0, this.isRunning = !1, this.willSupportSellTrading = !1, this.willSupportBuyTrading = !1, this.coinSymbol = "", this.marketSymbol = "", this.coinName = "", this.isListedNew = !1, this.syncedServerTime = null, this.lastSyncTimestamp = performance.now(), this.sellOpenTimeLeft = 0, this.buyOpenTimeLeft = 0, this.sellOpenDate = "", this.buyOpenDate = "", this.fetchServerTime = (0, r.Z)((0, i.Z)().mark((function e() {
                        var t, r, o, a, s, l;
                        return (0, i.Z)().wrap((function(e) {
                            for (;;) switch (e.prev = e.next) {
                                case 0:
                                    return e.prev = 0, t = performance.now(), e.next = 4, n.httpService.get("/v1/comn/server-time");
                                case 4:
                                    r = e.sent, o = performance.now(), 200 === r.status && (a = c()(r.data.serverTime), l = o - (s = (o - t) / 2), (0, _.z)((function() {
                                        n.syncedServerTime = a.add(s, "milliseconds"), n.lastSyncTimestamp = l, n.updateTimeLeft()
                                    }))), e.next = 12;
                                    break;
                                case 9:
                                    e.prev = 9, e.t0 = e.catch(0), console.error("Failed to fetch server time:", e.t0);
                                case 12:
                                case "end":
                                    return e.stop()
                            }
                        }), e, null, [
                            [0, 9]
                        ])
                    }))), this.updateTimeLeft = (0, _.aD)((function() {
                        if (n.syncedServerTime) {
                            var e = performance.now() - n.lastSyncTimestamp,
                                t = n.syncedServerTime.add(e, "milliseconds");
                            n.buyOpenTimeLeft = Math.max(c()(n.buyOpenDate).diff(t, "milliseconds"), 0), n.sellOpenTimeLeft = Math.max(c()(n.sellOpenDate).diff(t, "milliseconds"), 0), n.buyOpenTimeLeft <= 0 && n.sellOpenTimeLeft <= 0 && n.clearIntervals()
                        }
                    })), this.updateCount = function() {
                        if (n.syncedServerTime && !n.isRunning) {
                            n.isRunning = !0;
                            var e = performance.now();
                            n.animationFrameId = requestAnimationFrame((function t() {
                                var i = performance.now(),
                                    r = i - e;
                                r >= 200 && ((0, _.z)((function() {
                                    return n.updateTimeLeft()
                                })), e = i - r % 200), n.buyOpenTimeLeft > 0 || n.sellOpenTimeLeft > 0 ? n.animationFrameId = requestAnimationFrame(t) : n.clear()
                            }))
                        }
                    }, this.clearIntervals = function() {
                        n.animationFrameId && (cancelAnimationFrame(n.animationFrameId), n.animationFrameId = null), n.serverSyncTimeoutId && (clearTimeout(n.serverSyncTimeoutId), n.serverSyncTimeoutId = void 0), n.isRunning = !1
                    }, this.clear = function() {
                        n.clearIntervals(), n.syncedServerTime = null, n.lastSyncTimestamp = 0, n.sellOpenTimeLeft = 0, n.buyOpenTimeLeft = 0, n.coinSymbol = "", n.marketSymbol = "", n.coinName = "", n.isListedNew = !1, n.sellOpenDate = "", n.buyOpenDate = "", n.isRunning = !1
                    }, this.syncServerTime = (0, r.Z)((0, i.Z)().mark((function e() {
                        return (0, i.Z)().wrap((function(e) {
                            for (;;) switch (e.prev = e.next) {
                                case 0:
                                    return e.next = 2, n.fetchServerTime();
                                case 2:
                                    n.serverSyncTimeoutId = setTimeout(n.syncServerTime, 6e4);
                                case 3:
                                case "end":
                                    return e.stop()
                            }
                        }), e)
                    }))), this.startCountdown = function() {
                        var e = (0, r.Z)((0, i.Z)().mark((function e(t) {
                            var r, o, a, s, l, d;
                            return (0, i.Z)().wrap((function(e) {
                                for (;;) switch (e.prev = e.next) {
                                    case 0:
                                        if (r = t.coinSymbol, o = t.marketSymbol, a = t.coinName, s = t.isListedNew, l = t.sellOpenDate, d = t.buyOpenDate, r && o) {
                                            e.next = 3;
                                            break
                                        }
                                        return e.abrupt("return");
                                    case 3:
                                        return n.clear(), n.coinSymbol = r, n.marketSymbol = o, n.coinName = a, n.isListedNew = s, n.sellOpenDate = l, n.buyOpenDate = d, e.next = 12, n.fetchServerTime();
                                    case 12:
                                        n.updateTimeLeft(), (0, _.z)((function() {
                                            n.willSupportSellTrading = c()(l).format("YYYY-MM-DD") === m, n.willSupportBuyTrading = c()(d).format("YYYY-MM-DD") === m
                                        })), n.updateCount(), clearTimeout(n.serverSyncTimeoutId), n.serverSyncTimeoutId = setTimeout(n.syncServerTime, 6e4);
                                    case 17:
                                    case "end":
                                        return e.stop()
                                }
                            }), e)
                        })));
                        return function(t) {
                            return e.apply(this, arguments)
                        }
                    }(), this.httpService = t.httpService, (0, _.rC)(this, {
                        willSupportSellTrading: _.LO,
                        willSupportBuyTrading: _.LO,
                        coinSymbol: _.LO,
                        marketSymbol: _.LO,
                        coinName: _.LO,
                        isListedNew: _.LO,
                        syncedServerTime: _.LO,
                        lastSyncTimestamp: _.LO,
                        sellOpenTimeLeft: _.LO,
                        buyOpenTimeLeft: _.LO,
                        sellOpenDate: _.LO,
                        buyOpenDate: _.LO,
                        startCountdown: _.aD,
                        clearIntervals: _.aD,
                        clear: _.aD
                    })
                }));
            t.Z = u
        }
    }
]);