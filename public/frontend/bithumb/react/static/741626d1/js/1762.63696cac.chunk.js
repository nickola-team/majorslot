"use strict";
(self.webpackChunk_bithumb_pc = self.webpackChunk_bithumb_pc || []).push([
    [1762], {
        87933: function(t, e, i) {
            i.d(e, {
                Ct: function() {
                    return n
                },
                Fb: function() {
                    return s
                },
                SB: function() {
                    return r
                },
                j8: function() {
                    return a
                }
            });
            var n = function(t, e) {
                    return "undefined" === typeof e.state.data
                },
                s = function(t) {
                    t.suspense && "number" !== typeof t.staleTime && (t.staleTime = 1e3)
                },
                r = function(t, e) {
                    return (null === t || void 0 === t ? void 0 : t.suspense) && e.isPending
                },
                a = function(t, e, i) {
                    return e.fetchOptimistic(t).catch((function() {
                        i.clearReset()
                    }))
                }
        },
        53379: function(t, e, i) {
            i.d(e, {
                r: function() {
                    return d
                }
            });
            var n = i(38153),
                s = i(9967),
                r = i(27633);

            function a() {
                var t = !1;
                return {
                    clearReset: function() {
                        t = !1
                    },
                    reset: function() {
                        t = !0
                    },
                    isReset: function() {
                        return t
                    }
                }
            }
            var o = s.createContext(a()),
                u = i(31915),
                h = s.createContext(!1),
                l = (h.Provider, i(92856)),
                c = i(87933);

            function d(t, e, i) {
                var a = (0, u.NL)(i),
                    d = s.useContext(h),
                    f = s.useContext(o),
                    Z = a.defaultQueryOptions(t);
                Z._optimisticResults = d ? "isRestoring" : "optimistic", (0, c.Fb)(Z),
                    function(t, e) {
                        (t.suspense || t.throwOnError) && (e.isReset() || (t.retryOnMount = !1))
                    }(Z, f),
                    function(t) {
                        s.useEffect((function() {
                            t.clearReset()
                        }), [t])
                    }(f);
                var p = s.useState((function() {
                        return new e(a, Z)
                    })),
                    v = (0, n.Z)(p, 1)[0],
                    y = v.getOptimisticResult(Z);
                if (s.useSyncExternalStore(s.useCallback((function(t) {
                        var e = d ? function() {} : v.subscribe(r.V.batchCalls(t));
                        return v.updateResult(), e
                    }), [v, d]), (function() {
                        return v.getCurrentResult()
                    }), (function() {
                        return v.getCurrentResult()
                    })), s.useEffect((function() {
                        v.setOptions(Z, {
                            listeners: !1
                        })
                    }), [Z, v]), (0, c.SB)(Z, y)) throw (0, c.j8)(Z, v, f);
                if (function(t) {
                        var e = t.result,
                            i = t.errorResetBoundary,
                            n = t.throwOnError,
                            s = t.query;
                        return e.isError && !i.isReset() && !e.isFetching && s && (0, l.L)(n, [e.error, s])
                    }({
                        result: y,
                        errorResetBoundary: f,
                        throwOnError: Z.throwOnError,
                        query: a.getQueryCache().get(Z.queryHash)
                    })) throw y.error;
                return Z.notifyOnChangeProps ? y : v.trackResult(y)
            }
        },
        51762: function(t, e, i) {
            i.d(e, {
                a: function() {
                    return r
                }
            });
            var n = i(57496),
                s = i(53379);

            function r(t, e) {
                return (0, s.r)(t, n.z, e)
            }
        },
        92856: function(t, e, i) {
            i.d(e, {
                L: function() {
                    return s
                }
            });
            var n = i(40242);

            function s(t, e) {
                return "function" === typeof t ? t.apply(void 0, (0, n.Z)(e)) : !!t
            }
        },
        57496: function(t, e, i) {
            i.d(e, {
                z: function() {
                    return x
                }
            });
            var n, s, r, a, o, u, h, l, c, d, f, Z, p, v, y, b, w, k, R, g, O, C, S, m = i(96094),
                W = i(79311),
                U = i(78083),
                M = i(47093),
                E = i(22263),
                F = i(25755),
                A = i(12874),
                L = i(41955),
                P = i(77119),
                Q = i(55813),
                T = i(37645),
                D = i(63036),
                q = i(27633),
                I = i(79547),
                j = i(23742),
                _ = i(67553),
                x = (n = new WeakMap, s = new WeakMap, r = new WeakMap, a = new WeakMap, o = new WeakMap, u = new WeakMap, h = new WeakMap, l = new WeakMap, c = new WeakMap, d = new WeakMap, f = new WeakMap, Z = new WeakMap, p = new WeakMap, v = new WeakMap, y = new WeakSet, b = new WeakSet, w = new WeakSet, k = new WeakSet, R = new WeakSet, g = new WeakSet, O = new WeakSet, C = new WeakSet, S = new WeakSet, function(t) {
                    (0, E.Z)(i, t);
                    var e = (0, F.Z)(i);

                    function i(t, m) {
                        var U;
                        return (0, W.Z)(this, i), U = e.call(this), (0, A.Z)((0, M.Z)(U), S), (0, A.Z)((0, M.Z)(U), C), (0, A.Z)((0, M.Z)(U), O), (0, A.Z)((0, M.Z)(U), g), (0, A.Z)((0, M.Z)(U), R), (0, A.Z)((0, M.Z)(U), k), (0, A.Z)((0, M.Z)(U), w), (0, A.Z)((0, M.Z)(U), b), (0, A.Z)((0, M.Z)(U), y), (0, L.Z)((0, M.Z)(U), n, {
                            writable: !0,
                            value: void 0
                        }), (0, L.Z)((0, M.Z)(U), s, {
                            writable: !0,
                            value: void 0
                        }), (0, L.Z)((0, M.Z)(U), r, {
                            writable: !0,
                            value: void 0
                        }), (0, L.Z)((0, M.Z)(U), a, {
                            writable: !0,
                            value: void 0
                        }), (0, L.Z)((0, M.Z)(U), o, {
                            writable: !0,
                            value: void 0
                        }), (0, L.Z)((0, M.Z)(U), u, {
                            writable: !0,
                            value: void 0
                        }), (0, L.Z)((0, M.Z)(U), h, {
                            writable: !0,
                            value: void 0
                        }), (0, L.Z)((0, M.Z)(U), l, {
                            writable: !0,
                            value: void 0
                        }), (0, L.Z)((0, M.Z)(U), c, {
                            writable: !0,
                            value: void 0
                        }), (0, L.Z)((0, M.Z)(U), d, {
                            writable: !0,
                            value: void 0
                        }), (0, L.Z)((0, M.Z)(U), f, {
                            writable: !0,
                            value: void 0
                        }), (0, L.Z)((0, M.Z)(U), Z, {
                            writable: !0,
                            value: void 0
                        }), (0, L.Z)((0, M.Z)(U), p, {
                            writable: !0,
                            value: void 0
                        }), (0, L.Z)((0, M.Z)(U), v, {
                            writable: !0,
                            value: new Set
                        }), U.options = m, (0, T.Z)((0, M.Z)(U), n, t), (0, T.Z)((0, M.Z)(U), h, null), U.bindMethods(), U.setOptions(m), U
                    }
                    return (0, U.Z)(i, [{
                        key: "bindMethods",
                        value: function() {
                            this.refetch = this.refetch.bind(this)
                        }
                    }, {
                        key: "onSubscribe",
                        value: function() {
                            1 === this.listeners.size && ((0, Q.Z)(this, s).addObserver(this), Y((0, Q.Z)(this, s), this.options) ? (0, P.Z)(this, y, B).call(this) : this.updateResult(), (0, P.Z)(this, R, N).call(this))
                        }
                    }, {
                        key: "onUnsubscribe",
                        value: function() {
                            this.hasListeners() || this.destroy()
                        }
                    }, {
                        key: "shouldFetchOnReconnect",
                        value: function() {
                            return $((0, Q.Z)(this, s), this.options, this.options.refetchOnReconnect)
                        }
                    }, {
                        key: "shouldFetchOnWindowFocus",
                        value: function() {
                            return $((0, Q.Z)(this, s), this.options, this.options.refetchOnWindowFocus)
                        }
                    }, {
                        key: "destroy",
                        value: function() {
                            this.listeners = new Set, (0, P.Z)(this, g, H).call(this), (0, P.Z)(this, O, G).call(this), (0, Q.Z)(this, s).removeObserver(this)
                        }
                    }, {
                        key: "setOptions",
                        value: function(t, e) {
                            var i = this.options,
                                r = (0, Q.Z)(this, s);
                            if (this.options = (0, Q.Z)(this, n).defaultQueryOptions(t), (0, D.VS)(i, this.options) || (0, Q.Z)(this, n).getQueryCache().notify({
                                    type: "observerOptionsUpdated",
                                    query: (0, Q.Z)(this, s),
                                    observer: this
                                }), "undefined" !== typeof this.options.enabled && "boolean" !== typeof this.options.enabled) throw new Error("Expected enabled to be a boolean");
                            this.options.queryKey || (this.options.queryKey = i.queryKey), (0, P.Z)(this, C, J).call(this);
                            var a = this.hasListeners();
                            a && tt((0, Q.Z)(this, s), r, this.options, i) && (0, P.Z)(this, y, B).call(this), this.updateResult(e), !a || (0, Q.Z)(this, s) === r && this.options.enabled === i.enabled && this.options.staleTime === i.staleTime || (0, P.Z)(this, b, K).call(this);
                            var o = (0, P.Z)(this, w, V).call(this);
                            !a || (0, Q.Z)(this, s) === r && this.options.enabled === i.enabled && o === (0, Q.Z)(this, p) || (0, P.Z)(this, k, z).call(this, o)
                        }
                    }, {
                        key: "getOptimisticResult",
                        value: function(t) {
                            var e = (0, Q.Z)(this, n).getQueryCache().build((0, Q.Z)(this, n), t),
                                i = this.createResult(e, t);
                            return function(t, e) {
                                if (!(0, D.VS)(t.getCurrentResult(), e)) return !0;
                                return !1
                            }(this, i) && ((0, T.Z)(this, a, i), (0, T.Z)(this, u, this.options), (0, T.Z)(this, o, (0, Q.Z)(this, s).state)), i
                        }
                    }, {
                        key: "getCurrentResult",
                        value: function() {
                            return (0, Q.Z)(this, a)
                        }
                    }, {
                        key: "trackResult",
                        value: function(t) {
                            var e = this,
                                i = {};
                            return Object.keys(t).forEach((function(n) {
                                Object.defineProperty(i, n, {
                                    configurable: !1,
                                    enumerable: !0,
                                    get: function() {
                                        return (0, Q.Z)(e, v).add(n), t[n]
                                    }
                                })
                            })), i
                        }
                    }, {
                        key: "getCurrentQuery",
                        value: function() {
                            return (0, Q.Z)(this, s)
                        }
                    }, {
                        key: "refetch",
                        value: function() {
                            var t = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : {},
                                e = Object.assign({}, t);
                            return this.fetch((0, m.Z)({}, e))
                        }
                    }, {
                        key: "fetchOptimistic",
                        value: function(t) {
                            var e = this,
                                i = (0, Q.Z)(this, n).defaultQueryOptions(t),
                                s = (0, Q.Z)(this, n).getQueryCache().build((0, Q.Z)(this, n), i);
                            return s.isFetchingOptimistic = !0, s.fetch().then((function() {
                                return e.createResult(s, i)
                            }))
                        }
                    }, {
                        key: "fetch",
                        value: function(t) {
                            var e, i = this;
                            return (0, P.Z)(this, y, B).call(this, (0, m.Z)((0, m.Z)({}, t), {}, {
                                cancelRefetch: null === (e = t.cancelRefetch) || void 0 === e || e
                            })).then((function() {
                                return i.updateResult(), (0, Q.Z)(i, a)
                            }))
                        }
                    }, {
                        key: "createResult",
                        value: function(t, e) {
                            var i, n = (0, Q.Z)(this, s),
                                f = this.options,
                                Z = (0, Q.Z)(this, a),
                                p = (0, Q.Z)(this, o),
                                v = (0, Q.Z)(this, u),
                                y = t !== n ? t.state : (0, Q.Z)(this, r),
                                b = t.state,
                                w = b.error,
                                k = b.errorUpdatedAt,
                                R = b.fetchStatus,
                                g = b.status,
                                O = !1;
                            if (e._optimisticResults) {
                                var C = this.hasListeners(),
                                    S = !C && Y(t, e),
                                    m = C && tt(t, n, e, f);
                                (S || m) && (R = (0, _.Kw)(t.options.networkMode) ? "fetching" : "paused", b.dataUpdatedAt || (g = "pending")), "isRestoring" === e._optimisticResults && (R = "idle")
                            }
                            if (e.select && "undefined" !== typeof b.data)
                                if (Z && b.data === (null === p || void 0 === p ? void 0 : p.data) && e.select === (0, Q.Z)(this, l)) i = (0, Q.Z)(this, c);
                                else try {
                                    (0, T.Z)(this, l, e.select), i = e.select(b.data), i = (0, D.oE)(null === Z || void 0 === Z ? void 0 : Z.data, i, e), (0, T.Z)(this, c, i), (0, T.Z)(this, h, null)
                                } catch (L) {
                                    (0, T.Z)(this, h, L)
                                } else i = b.data;
                            if ("undefined" !== typeof e.placeholderData && "undefined" === typeof i && "pending" === g) {
                                var W, U;
                                if (null !== Z && void 0 !== Z && Z.isPlaceholderData && e.placeholderData === (null === v || void 0 === v ? void 0 : v.placeholderData)) W = Z.data;
                                else if (W = "function" === typeof e.placeholderData ? e.placeholderData(null === (U = (0, Q.Z)(this, d)) || void 0 === U ? void 0 : U.state.data, (0, Q.Z)(this, d)) : e.placeholderData, e.select && "undefined" !== typeof W) try {
                                    W = e.select(W), (0, T.Z)(this, h, null)
                                } catch (L) {
                                    (0, T.Z)(this, h, L)
                                }
                                "undefined" !== typeof W && (g = "success", i = (0, D.oE)(null === Z || void 0 === Z ? void 0 : Z.data, W, e), O = !0)
                            }(0, Q.Z)(this, h) && (w = (0, Q.Z)(this, h), i = (0, Q.Z)(this, c), k = Date.now(), g = "error");
                            var M = "fetching" === R,
                                E = "pending" === g,
                                F = "error" === g,
                                A = E && M;
                            return {
                                status: g,
                                fetchStatus: R,
                                isPending: E,
                                isSuccess: "success" === g,
                                isError: F,
                                isInitialLoading: A,
                                isLoading: A,
                                data: i,
                                dataUpdatedAt: b.dataUpdatedAt,
                                error: w,
                                errorUpdatedAt: k,
                                failureCount: b.fetchFailureCount,
                                failureReason: b.fetchFailureReason,
                                errorUpdateCount: b.errorUpdateCount,
                                isFetched: b.dataUpdateCount > 0 || b.errorUpdateCount > 0,
                                isFetchedAfterMount: b.dataUpdateCount > y.dataUpdateCount || b.errorUpdateCount > y.errorUpdateCount,
                                isFetching: M,
                                isRefetching: M && !E,
                                isLoadingError: F && 0 === b.dataUpdatedAt,
                                isPaused: "paused" === R,
                                isPlaceholderData: O,
                                isRefetchError: F && 0 !== b.dataUpdatedAt,
                                isStale: et(t, e),
                                refetch: this.refetch
                            }
                        }
                    }, {
                        key: "updateResult",
                        value: function(t) {
                            var e = this,
                                i = (0, Q.Z)(this, a),
                                n = this.createResult((0, Q.Z)(this, s), this.options);
                            if ((0, T.Z)(this, o, (0, Q.Z)(this, s).state), (0, T.Z)(this, u, this.options), void 0 !== (0, Q.Z)(this, o).data && (0, T.Z)(this, d, (0, Q.Z)(this, s)), !(0, D.VS)(n, i)) {
                                (0, T.Z)(this, a, n);
                                var r = {};
                                !1 !== (null === t || void 0 === t ? void 0 : t.listeners) && function() {
                                    if (!i) return !0;
                                    var t = e.options.notifyOnChangeProps,
                                        n = "function" === typeof t ? t() : t;
                                    if ("all" === n || !n && !(0, Q.Z)(e, v).size) return !0;
                                    var s = new Set(null !== n && void 0 !== n ? n : (0, Q.Z)(e, v));
                                    return e.options.throwOnError && s.add("error"), Object.keys((0, Q.Z)(e, a)).some((function(t) {
                                        var n = t;
                                        return (0, Q.Z)(e, a)[n] !== i[n] && s.has(n)
                                    }))
                                }() && (r.listeners = !0), (0, P.Z)(this, S, X).call(this, (0, m.Z)((0, m.Z)({}, r), t))
                            }
                        }
                    }, {
                        key: "onQueryUpdate",
                        value: function() {
                            this.updateResult(), this.hasListeners() && (0, P.Z)(this, R, N).call(this)
                        }
                    }]), i
                }(j.l));

            function B(t) {
                (0, P.Z)(this, C, J).call(this);
                var e = (0, Q.Z)(this, s).fetch(this.options, t);
                return null !== t && void 0 !== t && t.throwOnError || (e = e.catch(D.ZT)), e
            }

            function K() {
                var t = this;
                if ((0, P.Z)(this, g, H).call(this), !D.sk && !(0, Q.Z)(this, a).isStale && (0, D.PN)(this.options.staleTime)) {
                    var e = (0, D.Kp)((0, Q.Z)(this, a).dataUpdatedAt, this.options.staleTime) + 1;
                    (0, T.Z)(this, f, setTimeout((function() {
                        (0, Q.Z)(t, a).isStale || t.updateResult()
                    }), e))
                }
            }

            function V() {
                var t;
                return null !== (t = "function" === typeof this.options.refetchInterval ? this.options.refetchInterval((0, Q.Z)(this, s)) : this.options.refetchInterval) && void 0 !== t && t
            }

            function z(t) {
                var e = this;
                (0, P.Z)(this, O, G).call(this), (0, T.Z)(this, p, t), !D.sk && !1 !== this.options.enabled && (0, D.PN)((0, Q.Z)(this, p)) && 0 !== (0, Q.Z)(this, p) && (0, T.Z)(this, Z, setInterval((function() {
                    (e.options.refetchIntervalInBackground || I.j.isFocused()) && (0, P.Z)(e, y, B).call(e)
                }), (0, Q.Z)(this, p)))
            }

            function N() {
                (0, P.Z)(this, b, K).call(this), (0, P.Z)(this, k, z).call(this, (0, P.Z)(this, w, V).call(this))
            }

            function H() {
                (0, Q.Z)(this, f) && (clearTimeout((0, Q.Z)(this, f)), (0, T.Z)(this, f, void 0))
            }

            function G() {
                (0, Q.Z)(this, Z) && (clearInterval((0, Q.Z)(this, Z)), (0, T.Z)(this, Z, void 0))
            }

            function J() {
                var t = (0, Q.Z)(this, n).getQueryCache().build((0, Q.Z)(this, n), this.options);
                if (t !== (0, Q.Z)(this, s)) {
                    var e = (0, Q.Z)(this, s);
                    (0, T.Z)(this, s, t), (0, T.Z)(this, r, t.state), this.hasListeners() && (null === e || void 0 === e || e.removeObserver(this), t.addObserver(this))
                }
            }

            function X(t) {
                var e = this;
                q.V.batch((function() {
                    t.listeners && e.listeners.forEach((function(t) {
                        t((0, Q.Z)(e, a))
                    })), (0, Q.Z)(e, n).getQueryCache().notify({
                        query: (0, Q.Z)(e, s),
                        type: "observerResultsUpdated"
                    })
                }))
            }

            function Y(t, e) {
                return function(t, e) {
                    return !1 !== e.enabled && !t.state.dataUpdatedAt && !("error" === t.state.status && !1 === e.retryOnMount)
                }(t, e) || t.state.dataUpdatedAt > 0 && $(t, e, e.refetchOnMount)
            }

            function $(t, e, i) {
                if (!1 !== e.enabled) {
                    var n = "function" === typeof i ? i(t) : i;
                    return "always" === n || !1 !== n && et(t, e)
                }
                return !1
            }

            function tt(t, e, i, n) {
                return !1 !== i.enabled && (t !== e || !1 === n.enabled) && (!i.suspense || "error" !== t.state.status) && et(t, i)
            }

            function et(t, e) {
                return t.isStaleByTime(e.staleTime)
            }
        }
    }
]);