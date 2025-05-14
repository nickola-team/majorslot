"use strict";
(self.webpackChunk_bithumb_pc = self.webpackChunk_bithumb_pc || []).push([
    [7208], {
        17208: function(e, t, i) {
            i.d(t, {
                $V: function() {
                    return v
                }
            });
            var n, a = i(96094),
                o = i(38153),
                s = i(40242),
                r = i(29721),
                l = i(79311),
                c = i(78083),
                d = (i(9967), i(50611)),
                p = i(34626),
                u = i(84841),
                g = i.n(u),
                f = i(59682),
                m = i(96226),
                O = i(18694),
                _ = i(38619),
                S = i(52160),
                y = i(15822),
                v = (i(24103), Object.freeze({
                    C0100: 0,
                    C0101: 8,
                    C0102: 8,
                    C0106: 6
                })),
                E = Object.freeze(["C0101", "C0102", "C0106"]),
                h = Object.freeze({
                    dailyFeePercent: "0",
                    basicFeePercent: "0",
                    maxLimitAmt: "0",
                    financialBalanceQty: "0",
                    closedSoldOut: !1,
                    minDeposit: "0",
                    minLimitQty: "0",
                    minRate: "0",
                    dailyRemainCnt: 0,
                    memberLimit: "0",
                    depositRate: "0",
                    fixedClosePrice: 0,
                    autoRepaymentQuote: "0"
                }),
                N = Object.freeze({
                    deposit: "",
                    lendingQty: "",
                    rate: 80,
                    servicePeriod: "3"
                }),
                I = Object.freeze({
                    AVAILABLE_LIST: "/v1/lending/available-coin",
                    AVAILABLE_LIST_HISTORY: "/v1/lending/available-coin/history",
                    ADD_DEPOSIT: "/v1/lending/add-deposit",
                    REPAYMENT: "/v1/lending/repayment",
                    UPTURN_BASIC_INFO: "/v1/lending/upswing/info",
                    UPTURN_APPLICATION_INFO: "/v1/lending/upswing/application-info",
                    UPTURN_APPLICATION_CONFIRM: "/v1/lending/upswing/application",
                    UPTURN_APPLICATION: "/v1/lending/upswing/application",
                    DOWNTURN_BASIC_INFO: "/v1/lending/downswing/info",
                    DOWNTURN_APPLICATION_INFO: "/v1/lending/downswing/application-info",
                    DOWNTURN_APPLICATION_CONFIRM: "/v1/lending/downswing/application",
                    DOWNTURN_APPLICATION: "/v1/lending/downswing/application",
                    LENDING_HISTORY: "/v1/lending/history"
                });
            ! function(e) {
                e[e.ONLY_CONFIRM = 0] = "ONLY_CONFIRM", e[e.MOVE_DORMANT_MEMBER = 1] = "MOVE_DORMANT_MEMBER", e[e.MOVE_EMAIL_CHANGE = 2] = "MOVE_EMAIL_CHANGE", e[e.DETAIL_HISTORY_REFRESH = 3] = "DETAIL_HISTORY_REFRESH", e[e.MOVE_MAIN = 4] = "MOVE_MAIN", e[e.CHANGE_COIN = 5] = "CHANGE_COIN", e[e.MOVE_LENDING_HOME = 6] = "MOVE_LENDING_HOME", e[e.MOVE_DEPOSIT_KRW = 7] = "MOVE_DEPOSIT_KRW", e[e.MOVE_LOGIN = 8] = "MOVE_LOGIN", e[e.APPLY_REFRESH = 9] = "APPLY_REFRESH", e[e.SET_DEFAULT = 10] = "SET_DEFAULT", e[e.STEP_CONDITIONAL_DEFAULT_SET_OR_REFRESH = 11] = "STEP_CONDITIONAL_DEFAULT_SET_OR_REFRESH"
            }(n || (n = {}));
            var A = Object.freeze({
                    "products.valid.0001": n.ONLY_CONFIRM,
                    "products.lending.fail.00019": n.ONLY_CONFIRM,
                    "products.member.valid.fail.00030": n.MOVE_DORMANT_MEMBER,
                    "products.member.valid.fail.00031": n.MOVE_EMAIL_CHANGE,
                    "products.member.valid.fail.00035": n.MOVE_MAIN,
                    "products.member.valid.fail.00038": n.MOVE_MAIN,
                    "products.lending.fail.00018": n.DETAIL_HISTORY_REFRESH,
                    "member.fail.00200": n.MOVE_MAIN,
                    "products.lending.fail.00020": n.MOVE_MAIN,
                    "products.lending.fail.00022": n.MOVE_MAIN,
                    "products.lending.fail.00001": n.MOVE_LENDING_HOME,
                    "products.lending.fail.00002": n.CHANGE_COIN,
                    "products.lending.fail.00003": n.CHANGE_COIN,
                    "products.lending.fail.00021": n.CHANGE_COIN,
                    "products.lending.fail.00023": n.CHANGE_COIN,
                    "products.lending.fail.00004": n.MOVE_LENDING_HOME,
                    "products.lending.fail.00006": n.MOVE_LENDING_HOME,
                    "products.lending.fail.00007": n.MOVE_LENDING_HOME,
                    "products.lending.fail.00008": n.MOVE_LENDING_HOME,
                    "products.lending.fail.00011": n.MOVE_LENDING_HOME,
                    "products.account.fail.00001": n.MOVE_DEPOSIT_KRW,
                    "member.fail.00012": n.MOVE_LOGIN,
                    "products.system.0000": n.ONLY_CONFIRM,
                    "products.system.0001": n.ONLY_CONFIRM,
                    "products.db.0001": n.ONLY_CONFIRM,
                    "products.fail.400.001": n.ONLY_CONFIRM,
                    "products.member.valid.fail.00032": n.MOVE_MAIN,
                    "products.member.valid.fail.00033": n.MOVE_MAIN,
                    "products.member.valid.fail.00034": n.MOVE_MAIN,
                    "products.member.valid.fail.00036": n.MOVE_MAIN,
                    "products.member.valid.fail.00037": n.MOVE_MAIN,
                    "products.lending.fail.00005": n.MOVE_MAIN,
                    "products.lending.fail.00009": n.MOVE_LENDING_HOME,
                    "products.lending.fail.00012": n.STEP_CONDITIONAL_DEFAULT_SET_OR_REFRESH,
                    "products.lending.fail.00013": n.SET_DEFAULT,
                    "products.lending.fail.00017": n.SET_DEFAULT,
                    "products.lending.fail.00024": n.SET_DEFAULT,
                    "products.lending.fail.00025": n.SET_DEFAULT,
                    "products.lending.fail.00014": n.APPLY_REFRESH,
                    "products.lending.fail.00015": n.ONLY_CONFIRM,
                    "products.lending.fail.00016": n.STEP_CONDITIONAL_DEFAULT_SET_OR_REFRESH,
                    "products.lending.fail.00010": n.STEP_CONDITIONAL_DEFAULT_SET_OR_REFRESH,
                    "products.fail.fail.400.002": n.MOVE_LENDING_HOME,
                    "products.fail.404.001": n.MOVE_LENDING_HOME,
                    "products.fail.500.001": n.MOVE_LENDING_HOME
                }),
                L = function() {
                    function e(t) {
                        var i, c = this;
                        (0, l.Z)(this, e), this.routeService = void 0, this.httpService = void 0, this.assetService = void 0, this.socketService = void 0, this.sessionService = void 0, this.modalService = void 0, this.localeService = void 0, this.coinService = void 0, this.gaService = void 0, this.decimalPoint = 8, this.isReady = !1, this.coinType = localStorage.getItem("lendingUpturnLastCoinType") || "C0101", this.turn = p.Rsb.Up, this.lendingList = [], this.lendingInfo = h, this.applicationInfo = N, this.open = "00", this.detailInfo = {
                            data: null,
                            isUp: !0
                        }, this.snapshot = {
                            isSnapshot: !1,
                            closePrice: -1
                        }, this.applyAsset = "0", this.cloneCoinPriorityOrder = E, this.initApplyCb = [], this.applyStep = 0, this.historyState = (i = {}, (0, r.Z)(i, p.BJ1.P, "page.lending.lendingHistory.msg008"), (0, r.Z)(i, p.BJ1.R02, "page.lending.lendingHistory.msg009"), (0, r.Z)(i, p.BJ1.R03, "page.lending.lendingHistory.msg010"), (0, r.Z)(i, p.BJ1.R04, "page.lending.lendingHistory.msg042"), (0, r.Z)(i, p.BJ1.R06, "page.lending.lendingHistory.msg010"), (0, r.Z)(i, p.BJ1.I02, "page.lending.lendingHistory.msg008"), (0, r.Z)(i, p.BJ1.I03, "page.lending.lendingHistory.msg008"), (0, r.Z)(i, p.BJ1.I04, "page.lending.lendingHistory.msg008"), (0, r.Z)(i, p.BJ1.I06, "page.lending.lendingHistory.msg008"), (0, r.Z)(i, p.BJ1.F02, "page.lending.lendingHistory.msg043"), (0, r.Z)(i, p.BJ1.F03, "page.lending.lendingHistory.msg011"), (0, r.Z)(i, p.BJ1.F04, "page.lending.lendingHistory.msg012"), (0, r.Z)(i, p.BJ1.F05, "page.lending.lendingHistory.msg013"), (0, r.Z)(i, p.BJ1.F06, "page.lending.lendingHistory.msg011"), (0, r.Z)(i, p.BJ1.C, "page.lending.lendingHistory.msg067"), (0, r.Z)(i, p.BJ1.W, "page.lending.lendingHistory.msg068"), i), this.setReady = function(e) {
                            c.isReady = e
                        }, this.setApplyAsset = function(e) {
                            c.applyAsset = e
                        }, this.setCoinType = function(e) {
                            window.location.pathname.includes("trade") || localStorage.setItem(c.turn === p.Rsb.Up ? "lendingUpturnLastCoinType" : "lendingDownturnLastCoinType", e), c.coinType = e
                        }, this.setLendingList = function(e) {
                            c.lendingList = e
                        }, this.setTurn = function(e) {
                            c.turn = e
                        }, this.setSnapshot = function(e) {
                            var t, i = c.snapshot.closePrice;
                            e !== c.snapshot.isSnapshot && (i = e ? Number(null === (t = c.coinService.getTicker(c.coinType, c.getMarketType)) || void 0 === t ? void 0 : t.closePrice) || 0 : -1);
                            c.snapshot = {
                                isSnapshot: e,
                                closePrice: i
                            }
                        }, this.privateSetApplicationInfo = function(e) {
                            c.applicationInfo = e ? (0, m.Z)((0, f.Z)(c.applicationInfo), e) : N
                        }, this.setApplicationInfo = function(e, t) {
                            if (e)
                                if (c.applicationInfo.deposit || "deposit" in e) {
                                    var i = (0, f.Z)(e);
                                    "rate" in i && (i.lendingQty = c.fnRateToQty(i.rate, i.deposit || c.applicationInfo.deposit)), "lendingQty" in i && (i.rate = c.fnSetLendingRate(i.lendingQty, i.deposit || c.applicationInfo.deposit), t && (i.lendingQty = new(g())(i.lendingQty).toFixed(c.decimalPoint, g().ROUND_DOWN).toString())), c.privateSetApplicationInfo(i)
                                } else if ("servicePeriod" in e) {
                                var n = (0, f.Z)(e);
                                c.privateSetApplicationInfo(n)
                            } else c.privateSetApplicationInfo({
                                deposit: "",
                                lendingQty: new(g())(0).toFixed(c.decimalPoint, g().ROUND_DOWN).toString(),
                                rate: 0
                            });
                            else c.privateSetApplicationInfo()
                        }, this.setLendingInfo = function(e) {
                            var t;
                            c.lendingInfo = {
                                dailyFeePercent: e.dailyFeePercent,
                                basicFeePercent: e.basicFeePercent,
                                maxLimitAmt: e.maxLimitAmt,
                                closedSoldOut: e.closedSoldOut,
                                minDeposit: e.minDeposit,
                                minLimitQty: e.minLimitQty,
                                minRate: e.minRate,
                                financialBalanceQty: e.financialBalanceQty,
                                dailyRemainCnt: e && "dailyRemainCnt" in e ? e.dailyRemainCnt : 0,
                                memberLimit: e && "memberLimitAmt" in e ? e.memberLimitAmt : e && "memberLimitQty" in e ? e.memberLimitQty : e && "memberLimit" in e ? e.memberLimit : "0",
                                depositRate: Number(e.minRate) ? new(g())(1).div(e.minRate).toString() : e.minRate,
                                fixedClosePrice: Number(null === (t = c.coinService.getTicker(c.coinType, c.getMarketType)) || void 0 === t ? void 0 : t.closePrice) || 0,
                                autoRepaymentQuote: e.autoRepaymentQuote || ""
                            }
                        }, this.setOpen = function(e) {
                            c.open = e
                        }, this.setApplyStep = function(e) {
                            c.applyStep = e ? 0 : c.applyStep + 1
                        }, this.getAjaxAssetBalance = function(e, t) {
                            var i = arguments.length > 2 && void 0 !== arguments[2] && arguments[2];
                            return c.assetService.getAjaxAssetBalance(e, (function(e) {
                                i ? c.initApplyCb.push((function() {
                                    return c.setApplyAsset(e.restrictedOutAvailable)
                                })) : c.setApplyAsset(e.restrictedOutAvailable)
                            }), t)
                        }, this.initApply = function(e, t) {
                            var i = arguments.length > 2 && void 0 !== arguments[2] && arguments[2];
                            c.initApplyCb = [], c.turn !== t && (c.cloneCoinPriorityOrder = (0, s.Z)(new Set(c.lendingList.map((function(e) {
                                return e.coinType
                            }))))), c.setTurn(t), c.setApplyStep(!0);
                            var n = e;
                            if (n) n && c.lendingList.length && !c.lendingList.filter((function(e) {
                                return e.coinType === n
                            })).length && (n = c.lendingList[0].coinType);
                            else if (c.lendingList.length && c.lendingList[0].coinType) n = c.lendingList[0].coinType;
                            else {
                                var a = (0, o.Z)(E, 1);
                                n = a[0]
                            }
                            Promise.all([new Promise((function(e) {
                                return c.getAjaxAssetBalance(t === p.Rsb.Up ? n : c.getMarketType, e, !0)
                            })), new Promise((function(e) {
                                return c.ajaxLendingApplyInfo(n, e, !0, i)
                            }))]).then((function(e) {
                                200 === Number(e[1]) && (c.setCoinType(n), c.initApplyCb.forEach((function(e) {
                                    e()
                                })), c.fnUserChangeForm(!1), c.updateApplicationInfoDefault(), c.subscribeSocket(), c.isReady && c.gaService.fnGASendEvent("\ube57\uc378_".concat(t === p.Rsb.Up ? "\uc0c1\uc2b9\uc7a5" : "\ud558\ub77d\uc7a5", "\ub80c\ub529"), "\uc11c\ube44\uc2a4\uc2e0\uccad", "\uc790\uc0b0\uc120\ud0dd_".concat(c.coinService.getCoinInfo(c.coinType).coinName)), c.setReady(!0))
                            }))
                        }, this.initDetail = function(e, t, i) {
                            var n = [new Promise((function(t) {
                                return c.ajaxLendingDetailHistory(e.lendingSeq, void 0, t)
                            }))];
                            c.getHistoryStateCategory(e.stateCd) === p.J$5.Ing && n.push(new Promise((function(i) {
                                return c.assetService.getAjaxAssetChangeList([t ? e.coinType : c.getMarketType], i)
                            }))), Promise.all(n).then((function() {
                                var i;
                                c.detailInfo.isUp = t, c.modalService.showModal(y.hfn, c.detailInfo), c.detailInfo.data && c.gaService.fnGASendEvent("\ube57\uc378_".concat(t ? "\uc0c1\uc2b9\uc7a5" : "\ud558\ub77d\uc7a5", "\ub80c\ub529"), "\uc774\uc6a9\ub0b4\uc5ed", "".concat(c.coinService.getCoinInfo(e.coinType).coinName, "_\uc0c1\uc138\ubcf4\uae30_").concat((i = {}, (0, r.Z)(i, p.BJ1.P, "\uc774\uc6a9 \uc911"), (0, r.Z)(i, p.BJ1.R02, "\ubd80\ubd84 \ub9cc\uae30 \uc0c1\ud658"), (0, r.Z)(i, p.BJ1.R03, "\ubd80\ubd84 \uc911\ub3c4 \uc0c1\ud658"), (0, r.Z)(i, p.BJ1.R04, "\ubd80\ubd84 \uc790\ub3d9 \uc0c1\ud658"), (0, r.Z)(i, p.BJ1.I02, "\uc774\uc6a9 \uc911"), (0, r.Z)(i, p.BJ1.I03, "\uc774\uc6a9 \uc911"), (0, r.Z)(i, p.BJ1.I04, "\uc774\uc6a9 \uc911"), (0, r.Z)(i, p.BJ1.F02, "\ub9cc\uae30 \uc0c1\ud658"), (0, r.Z)(i, p.BJ1.F03, "\uc911\ub3c4 \uc0c1\ud658"), (0, r.Z)(i, p.BJ1.F04, "\uc790\ub3d9 \uc0c1\ud658"), (0, r.Z)(i, p.BJ1.F05, "\uad00\ub9ac\uc790 \uc0c1\ud658"), i)[c.detailInfo.data.stateCd]))
                            })).finally((function() {
                                i()
                            }))
                        }, this.subscribeSocket = function() {
                            c.socketService.subscribe((0, d.Lj)({
                                login: c.sessionService.login
                            }))
                        }, this.getAjaxLendingAvailableList = function(e) {
                            var t = window.location.pathname.includes("trade");
                            return c.httpService.get("".concat(I.AVAILABLE_LIST), null, t).then((function(i) {
                                200 === i.status ? (c.setLendingList(i.data), c.cloneCoinPriorityOrder = (0, s.Z)(new Set(i.data.map((function(e) {
                                    return e.coinType
                                })))), e && e(i.data)) : t || 200 === i.status || c.fnAjaxErr(i)
                            }))
                        }, this.getAjaxLendingAvailableListHistory = function(e) {
                            return c.httpService.get(I.AVAILABLE_LIST_HISTORY, void 0, !0).then((function(t) {
                                200 === t.status ? e(t.data) : c.fnAjaxErr(t)
                            }))
                        }, this.getAjaxUpturnBasicInfo = function(e) {
                            c.httpService.get(I.UPTURN_BASIC_INFO, void 0, !0).then((function(t) {
                                200 === t.status ? e(t.data) : c.fnAjaxErr(t)
                            }))
                        }, this.getAjaxDownturnBasicInfo = function(e) {
                            c.httpService.get(I.DOWNTURN_BASIC_INFO, void 0, !0).then((function(t) {
                                200 === t.status ? e(t.data) : c.fnAjaxErr(t)
                            }))
                        }, this.ajaxLendingApplyInfo = function(e, t) {
                            var i = arguments.length > 2 && void 0 !== arguments[2] && arguments[2],
                                n = arguments.length > 3 && void 0 !== arguments[3] && arguments[3],
                                o = c.turn === p.Rsb.Up,
                                s = o ? I.UPTURN_APPLICATION_INFO : I.DOWNTURN_APPLICATION_INFO,
                                r = {
                                    coinType: e
                                };
                            return c.httpService.get("".concat(s, "?").concat(_.stringify(r)), null, !0).then((function(o) {
                                200 === o.status ? (i ? c.initApplyCb.push((function() {
                                    return c.setLendingInfo(o.data)
                                })) : c.setLendingInfo(o.data), o.data.closedSoldOut && c.modalService.showModal(y.DzK, {
                                    title: c.localeService.locale("comp.lendingApplication.msg130", {
                                        max: new(g())(o.data.maxLimitAmt).toFormat(),
                                        symbol: c.coinService.getCoinInfo(e).coinSymbol
                                    }),
                                    message: c.localeService.locale("comp.lendingApplication.msg131"),
                                    modalBtn: {
                                        text: c.localeService.locale("button.msg11"),
                                        feature: S.y_.CUSTOM
                                    }
                                })) : c.fnAjaxErr((0, a.Z)((0, a.Z)({}, o), r), void 0, n ? "coinChange" : void 0), t && t(o.status)
                            }))
                        }, this.ajaxLendingHistory = function(e, t) {
                            e.stateCds && e.stateCds[0] === p.BJ1.P ? e.stateCds = e.stateCds.concat([p.BJ1.I02, p.BJ1.I03, p.BJ1.I04, p.BJ1.I06]) : e.stateCds && e.stateCds[0] === p.BJ1.F03 ? e.stateCds = e.stateCds.concat([p.BJ1.F06]) : e.stateCds && e.stateCds[0] === p.BJ1.R03 && (e.stateCds = e.stateCds.concat([p.BJ1.R06])), c.httpService.get("".concat(I.LENDING_HISTORY, "?").concat(_.stringify(e)), void 0, !0).then((function(i) {
                                200 === i.status ? t && t.success && t.success(i.data) : c.fnAjaxErr((0, a.Z)((0, a.Z)({}, i), e))
                            })).finally((function() {
                                t && t.finally && t.finally()
                            }))
                        }, this.ajaxLendingDetailHistory = function(e, t, i) {
                            return c.httpService.get("".concat(I.LENDING_HISTORY, "/").concat(e), void 0, !0).then((function(i) {
                                200 === i.status ? (c.detailInfo.data = i.data, t && t(i.data)) : c.fnAjaxErr((0, a.Z)((0, a.Z)({}, i), {
                                    lendingSeq: e
                                }))
                            })).finally((function() {
                                i && i()
                            }))
                        }, this.ajaxLendingDepositAdd = function(e, t) {
                            var i = {
                                lendingSeq: e,
                                deposit: t
                            };
                            c.httpService.post(I.ADD_DEPOSIT, i, !0).then((function(t) {
                                var n = function() {
                                    return c.ajaxLendingDetailHistory(e, (function(e) {
                                        c.modalService.updateParams(y.hfn, {
                                            isUp: c.detailInfo.isUp,
                                            data: e,
                                            updateDate: (new Date).toString()
                                        })
                                    }))
                                };
                                200 === t.status ? c.modalService.showModal(y.DzK, {
                                    message: c.localeService.locale("pop.lending.lendingHistory.msg049"),
                                    modalBtn: {
                                        text: c.localeService.locale("button.msg11"),
                                        feature: S.y_.CUSTOM,
                                        callback: n
                                    }
                                }) : c.fnAjaxErr((0, a.Z)((0, a.Z)({}, t), i), n)
                            }))
                        }, this.ajaxLendingRepayment = function(e, t, i, n) {
                            var o = {
                                lendingSeq: e
                            };
                            return c.httpService.post(I.REPAYMENT, o).then((function(e) {
                                200 === e.status ? (c.modalService.showModal(y.DzK, {
                                    message: e.message,
                                    modalBtn: {
                                        feature: S.y_.CUSTOM,
                                        callback: function() {
                                            t(), c.modalService.hideModal(y.hfn)
                                        }
                                    }
                                }), c.gaService.fnGASendEvent("\ube57\uc378_".concat(i ? "\uc0c1\uc2b9\uc7a5" : "\ud558\ub77d\uc7a5", "\ub80c\ub529"), "\uc774\uc6a9\ub0b4\uc5ed", "".concat(c.coinService.getCoinInfo(n).coinName, "_\uc0c1\ud658\ud558\uae30"))) : c.fnAjaxErr((0, a.Z)((0, a.Z)({}, e), o))
                            }))
                        }, this.fnAjaxErr = function(e, t, i) {
                            var a = e.code,
                                o = e.message;
                            if (!a.startsWith("cust.fail")) {
                                var s = c.modalService.showModal,
                                    r = c.localeService.locale,
                                    l = window.location.pathname,
                                    p = l.includes("trade"),
                                    u = function(e) {
                                        p ? c.setOpen("00") : e(), t && t()
                                    };
                                switch (A[e.code]) {
                                    case n.MOVE_DORMANT_MEMBER:
                                        s(y.DzK, {
                                            message: o,
                                            modalBtn: {
                                                text: r("button.msg11"),
                                                feature: S.y_.CUSTOM,
                                                callback: function() {
                                                    c.routeService.replace("/legacy/member_operation/dormant_member")
                                                }
                                            }
                                        });
                                        break;
                                    case n.MOVE_EMAIL_CHANGE:
                                        s(y.DzK, {
                                            message: o,
                                            modalBtn: {
                                                text: r("button.msg11"),
                                                feature: S.y_.CUSTOM,
                                                callback: function() {
                                                    c.routeService.replace("/legacy/member_operation/trans_email_change")
                                                }
                                            }
                                        });
                                        break;
                                    case n.DETAIL_HISTORY_REFRESH:
                                        s(y.DzK, {
                                            message: o,
                                            modalBtn: {
                                                text: r("button.msg11"),
                                                feature: S.y_.CUSTOM,
                                                callback: function() {
                                                    t && t()
                                                }
                                            }
                                        });
                                        break;
                                    case n.CHANGE_COIN:
                                        s(y.DzK, {
                                            message: o,
                                            modalBtn: {
                                                text: r("button.msg11"),
                                                feature: S.y_.CUSTOM,
                                                callback: function() {
                                                    u((function() {
                                                        if ("coinChange" !== i)
                                                            if (c.cloneCoinPriorityOrder = c.cloneCoinPriorityOrder.filter((function(t) {
                                                                    return t !== e.coinType
                                                                })), c.cloneCoinPriorityOrder.length > 0) {
                                                                var t = c.cloneCoinPriorityOrder[0];
                                                                (!c.isReady || c.isReady && t !== c.coinType) && c.fnChangeCoin(t)
                                                            } else c.routeService.replace("/lending")
                                                    }))
                                                }
                                            }
                                        });
                                        break;
                                    case n.MOVE_MAIN:
                                        s(y.DzK, {
                                            message: o,
                                            modalBtn: {
                                                text: r("button.msg11"),
                                                feature: S.y_.CUSTOM,
                                                callback: function() {
                                                    (0, d.KU)() ? window.history.back(): u((function() {
                                                        c.routeService.replace("/")
                                                    }))
                                                }
                                            }
                                        });
                                        break;
                                    case n.MOVE_LENDING_HOME:
                                        s(y.DzK, {
                                            message: o,
                                            modalBtn: {
                                                text: r("button.msg11"),
                                                feature: S.y_.CUSTOM,
                                                callback: function() {
                                                    (0, d.KU)() ? window.history.back(): u((function() {
                                                        c.routeService.replace("/lending")
                                                    }))
                                                }
                                            }
                                        });
                                        break;
                                    case n.MOVE_DEPOSIT_KRW:
                                        s(y.DzK, {
                                            message: o,
                                            modalBtn: {
                                                text: r("button.msg08"),
                                                feature: S.y_.CUSTOM,
                                                callback: function() {
                                                    u((function() {
                                                        c.routeService.replace("/lending")
                                                    }))
                                                }
                                            },
                                            modalBtn1: {
                                                text: r("button.msg60"),
                                                feature: S.y_.CUSTOM,
                                                callback: function() {
                                                    c.routeService.replace("/inout/deposit/KRW")
                                                }
                                            }
                                        });
                                        break;
                                    case n.MOVE_LOGIN:
                                        s(y.DzK, {
                                            message: o,
                                            modalBtn: {
                                                text: r("button.msg11"),
                                                feature: S.y_.CUSTOM,
                                                callback: function() {
                                                    u((function() {
                                                        c.routeService.replace("/login", {
                                                            from: {
                                                                pathname: l
                                                            },
                                                            replace: !0
                                                        })
                                                    }))
                                                }
                                            }
                                        });
                                        break;
                                    case n.APPLY_REFRESH:
                                    case n.SET_DEFAULT:
                                    case n.STEP_CONDITIONAL_DEFAULT_SET_OR_REFRESH:
                                        s(y.DzK, {
                                            message: o,
                                            modalBtn: {
                                                text: r("button.msg11"),
                                                feature: S.y_.CUSTOM,
                                                callback: function() {
                                                    c.fnChangeCoin(c.coinType)
                                                }
                                            }
                                        });
                                        break;
                                    default:
                                        s(y.DzK, {
                                            message: o,
                                            modalBtn: {
                                                text: r("button.msg11"),
                                                feature: S.y_.CUSTOM
                                            }
                                        })
                                }
                            }
                        }, this.fnChangeCoin = function(e) {
                            var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : c.turn,
                                i = arguments.length > 2 && void 0 !== arguments[2] && arguments[2];
                            c.lendingList && c.lendingList.length ? c.initApply(e, t, i) : c.getAjaxLendingAvailableList((function() {
                                c.initApply(e, t, i)
                            }))
                        }, this.fnSetLendingRate = function(e, t) {
                            var i, n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : c.getClosePrice,
                                a = arguments.length > 3 && void 0 !== arguments[3] && arguments[3];
                            return Number(e) && Number(t) ? c.turn === p.Rsb.Down ? (i = Number(new(g())(e).multipliedBy(n).dividedBy(t).multipliedBy(100).toFixed(0, g().ROUND_HALF_UP)), a ? i : (new(g())(i).isLessThan(0) && (i = 0), i > 80 ? 80 : i)) : (i = Number(new(g())(e).dividedBy(t).multipliedBy(100).toFixed(0, g().ROUND_HALF_UP)), new(g())(i).isLessThan(0) && (i = 0), i) : 0
                        }, this.fnRateToQty = function(e) {
                            var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : "0",
                                i = new(g())(e).dividedBy(100).decimalPlaces(2);
                            return c.turn === p.Rsb.Down ? new(g())(t).dividedBy(c.getClosePrice).multipliedBy(i).toFixed(c.decimalPoint, g().ROUND_DOWN) : new(g())(t).multipliedBy(i).toFixed(c.decimalPoint, g().ROUND_DOWN)
                        }, this.updateApplicationInfoDefault = function() {
                            if (!c.snapshot.isSnapshot) {
                                var e = c.turn === p.Rsb.Up,
                                    t = e ? c.coinType : c.getMarketType,
                                    i = e ? c.decimalPoint : 0,
                                    n = e ? c.applyAsset : c.sRestrictedAssetView(t),
                                    a = c.fnDeposit(n, i, e),
                                    o = new(g())(a).isGreaterThan(0) ? a : "";
                                c.setApplicationInfo({
                                    deposit: o,
                                    lendingQty: new(g())(c.fnQtyMinMax(n).max).toFixed(c.decimalPoint, g().ROUND_DOWN).toString()
                                }, !0)
                            }
                        }, this.fnDepositMinMax = function(e) {
                            var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : c.turn === p.Rsb.Up,
                                i = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : c.getClosePrice,
                                n = c.lendingInfo,
                                a = n.depositRate,
                                o = n.minDeposit,
                                s = n.minLimitQty,
                                r = t ? new(g())(o).toFixed(c.decimalPoint, g().ROUND_UP) : new(g())(s).multipliedBy(i).multipliedBy(a).toFixed(0, g().ROUND_UP),
                                l = new(g())(e).isLessThan(r) ? -1 : Number(e);
                            return {
                                min: Number(r),
                                max: l
                            }
                        }, this.fnDeposit = function(e, t, i) {
                            var n = c.lendingInfo,
                                a = n.financialBalanceQty,
                                o = n.depositRate,
                                s = n.memberLimit,
                                r = c.fnDepositMinMax(e, i).min;
                            if (new(g())(e).isLessThan(r)) return -1;
                            var l = new(g())(s).multipliedBy(o).toNumber(),
                                d = i ? new(g())(a).multipliedBy(o).toNumber() : new(g())(a).multipliedBy(c.getClosePrice).multipliedBy(o).toNumber(),
                                p = Math.min(l, d, Number(e));
                            return new(g())(p).toFixed(t, g().ROUND_DOWN).toString()
                        }, this.fnQtyMinMax = function(e) {
                            var t, i, n, a = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : c.turn === p.Rsb.Up,
                                o = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : c.getClosePrice,
                                s = c.lendingInfo,
                                r = s.minLimitQty,
                                l = s.minRate,
                                d = s.memberLimit,
                                u = s.financialBalanceQty,
                                f = c.applicationInfo.deposit || "0";
                            new(g())(e).isLessThan(c.fnDepositMinMax(e, a, o).min) ? t = -1 : (a ? (i = new(g())(f).multipliedBy(l).toNumber(), n = Number(d)) : (i = new(g())(f).div(o).multipliedBy(l).toNumber(), n = new(g())(d).div(o).toNumber()), t = Math.min(i, n, Number(u)));
                            return {
                                min: Number(r),
                                max: Number(new(g())(t).toFixed(c.decimalPoint, g().ROUND_DOWN))
                            }
                        }, this.fnUserChangeForm = function(e) {
                            c.setSnapshot(e)
                        }, this.getHistoryStateCategory = function(e) {
                            switch (!0) {
                                case e.indexOf("R") > -1:
                                    return p.J$5.Piece;
                                case e.indexOf("F") > -1:
                                    return p.J$5.End;
                                default:
                                    return p.J$5.Ing
                            }
                        }, this.getHistoryState = function(e) {
                            return c.localeService.locale(c.historyState[e])
                        }, this.routeService = t.routeService, this.httpService = t.httpService, this.assetService = t.assetService, this.socketService = t.socketService, this.sessionService = t.sessionService, this.modalService = t.modalService, this.localeService = t.localeService, this.coinService = t.coinService, this.gaService = t.gaService, (0, O.rC)(this, {
                            isReady: O.LO,
                            setReady: O.aD,
                            applyAsset: O.LO,
                            setApplyAsset: O.aD,
                            sRestrictedAssetView: O.Fl,
                            coinType: O.LO,
                            setCoinType: O.aD,
                            getMarketType: O.Fl,
                            lendingList: O.LO,
                            setLendingList: O.aD,
                            turn: O.LO,
                            setTurn: O.aD,
                            lendingInfo: O.LO,
                            setLendingInfo: O.aD,
                            applicationInfo: O.LO,
                            privateSetApplicationInfo: O.aD,
                            open: O.LO,
                            setOpen: O.aD,
                            snapshot: O.LO,
                            setSnapshot: O.aD,
                            applyStep: O.LO,
                            setApplyStep: O.aD,
                            decimalPoint: O.LO
                        })
                    }
                    return (0, c.Z)(e, [{
                        key: "sRestrictedAssetView",
                        get: function() {
                            var e = this;
                            return function() {
                                var t = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : e.coinType;
                                return new(g())(e.applyAsset || "0").toFixed(t === e.getMarketType ? 0 : e.decimalPoint, g().ROUND_DOWN).toString()
                            }
                        }
                    }, {
                        key: "getMarketType",
                        get: function() {
                            return this.coinService.getMarket.crncCd
                        }
                    }, {
                        key: "getClosePrice",
                        get: function() {
                            var e;
                            return this.snapshot.isSnapshot ? this.snapshot.closePrice : (null === (e = this.coinService.getTicker(this.coinType, this.getMarketType)) || void 0 === e ? void 0 : e.closePrice) || 0
                        }
                    }]), e
                }();
            t.ZP = L
        }
    }
]);