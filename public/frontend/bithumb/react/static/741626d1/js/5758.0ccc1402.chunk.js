(self.webpackChunk_bithumb_pc = self.webpackChunk_bithumb_pc || []).push([
    [5758], {
        22486: function(n, t, r) {
            "use strict";
            r.d(t, {
                v: function() {
                    return v
                }
            });
            var e = r(9967);

            function u() {
                return u = Object.assign || function(n) {
                    for (var t = 1; t < arguments.length; t++) {
                        var r = arguments[t];
                        for (var e in r) Object.prototype.hasOwnProperty.call(r, e) && (n[e] = r[e])
                    }
                    return n
                }, u.apply(this, arguments)
            }
            var i = function(n) {
                    return void 0 === n && (n = ""), (n + "").split("")
                },
                a = [].concat(function(n) {
                    return Array.from({
                        length: n
                    }, (function(n, t) {
                        return t
                    }))
                }(10).map((function(n) {
                    return n + ""
                })), [",", "."]),
                o = function(n) {
                    return !isNaN(parseInt(n, 10))
                },
                l = {
                    visibility: "hidden"
                },
                s = {
                    overflow: "hidden",
                    display: "inline-block",
                    position: "relative"
                },
                c = {
                    position: "absolute",
                    left: "0",
                    top: "0",
                    bottom: "0",
                    right: "0"
                },
                f = {
                    position: "absolute",
                    left: "0",
                    zIndex: 10
                },
                d = function(n) {
                    var t = n.children,
                        r = n.measureMap,
                        i = n.rotateItems,
                        a = n.className,
                        o = n.currentClassName,
                        d = n.hiddenClassName,
                        v = n.duration,
                        h = i.indexOf(t),
                        m = r[t],
                        p = m.height,
                        _ = m.width;
                    return e.createElement("span", {
                        className: a,
                        style: u({}, s, {
                            width: _ + "px"
                        })
                    }, e.createElement("span", {
                        className: a,
                        style: l
                    }, t), e.createElement("span", {
                        style: u({}, c, {
                            transition: "transform " + v,
                            transform: "translateY(" + p * h * -1 + "px)"
                        })
                    }, i.map((function(n, r) {
                        return e.createElement("span", {
                            key: n + r,
                            className: [a, n === t ? o : d].join(" "),
                            style: u({}, f, {
                                top: r * p
                            })
                        }, n)
                    }))))
                },
                v = function(n) {
                    var t, r = n.children,
                        u = n.textClassName,
                        l = n.currentClassName,
                        s = void 0 === l ? "currentTicker" : l,
                        c = n.hiddenClassName,
                        f = void 0 === c ? "hiddenTicker" : c,
                        v = n.duration,
                        h = void 0 === v ? ".5s" : v,
                        m = (0, e.useRef)({}),
                        p = e.Children.map(r, (function(n) {
                            return "string" === typeof n || "number" === typeof n ? i("" + n) : n.props && n.props.rotateItems
                        })).flat(),
                        _ = void 0 !== p.find((function(n) {
                            return o(n)
                        })),
                        Z = (t = [].concat(_ ? a : [], p)).filter((function(n, r) {
                            return t.indexOf(n) === r
                        }));
                    return Object.keys(m.current).length !== Z.length && Z.forEach((function(n) {
                        m.current[n] = function(n, t) {
                            var r = document.createElement("span");
                            r.textContent = t, r.className = n, r.style.opacity = "0", r.style.pointerEvents = "none", r.style.position = "absolute", document.body.appendChild(r);
                            var e = r.offsetHeight,
                                u = r.offsetWidth;
                            return document.body.removeChild(r), {
                                height: e,
                                width: u
                            }
                        }(u, n)
                    })), e.createElement(e.Fragment, null, e.Children.map(r, (function(n) {
                        return "string" === typeof n || "number" === typeof n ? i("" + n).map((function(n, t) {
                            var r = o(n) ? a : [n];
                            return e.createElement(d, {
                                key: t,
                                duration: h,
                                currentClassName: s,
                                hiddenClassName: f,
                                className: u,
                                rotateItems: r,
                                measureMap: m.current
                            }, n)
                        })) : e.cloneElement(n, {
                            duration: h,
                            className: u,
                            measureMap: m.current,
                            currentClassName: s,
                            hiddenClassName: f
                        })
                    })))
                }
        },
        32676: function(n, t, r) {
            n.exports = function(n) {
                "use strict";

                function t(n) {
                    return n && "object" == typeof n && "default" in n ? n : {
                        default: n
                    }
                }
                var r = t(n),
                    e = {
                        name: "ko",
                        weekdays: "\uc77c\uc694\uc77c_\uc6d4\uc694\uc77c_\ud654\uc694\uc77c_\uc218\uc694\uc77c_\ubaa9\uc694\uc77c_\uae08\uc694\uc77c_\ud1a0\uc694\uc77c".split("_"),
                        weekdaysShort: "\uc77c_\uc6d4_\ud654_\uc218_\ubaa9_\uae08_\ud1a0".split("_"),
                        weekdaysMin: "\uc77c_\uc6d4_\ud654_\uc218_\ubaa9_\uae08_\ud1a0".split("_"),
                        months: "1\uc6d4_2\uc6d4_3\uc6d4_4\uc6d4_5\uc6d4_6\uc6d4_7\uc6d4_8\uc6d4_9\uc6d4_10\uc6d4_11\uc6d4_12\uc6d4".split("_"),
                        monthsShort: "1\uc6d4_2\uc6d4_3\uc6d4_4\uc6d4_5\uc6d4_6\uc6d4_7\uc6d4_8\uc6d4_9\uc6d4_10\uc6d4_11\uc6d4_12\uc6d4".split("_"),
                        ordinal: function(n) {
                            return n
                        },
                        formats: {
                            LT: "A h:mm",
                            LTS: "A h:mm:ss",
                            L: "YYYY.MM.DD.",
                            LL: "YYYY\ub144 MMMM D\uc77c",
                            LLL: "YYYY\ub144 MMMM D\uc77c A h:mm",
                            LLLL: "YYYY\ub144 MMMM D\uc77c dddd A h:mm",
                            l: "YYYY.MM.DD.",
                            ll: "YYYY\ub144 MMMM D\uc77c",
                            lll: "YYYY\ub144 MMMM D\uc77c A h:mm",
                            llll: "YYYY\ub144 MMMM D\uc77c dddd A h:mm"
                        },
                        meridiem: function(n) {
                            return n < 12 ? "\uc624\uc804" : "\uc624\ud6c4"
                        },
                        relativeTime: {
                            future: "%s \ud6c4",
                            past: "%s \uc804",
                            s: "\uba87 \ucd08",
                            m: "1\ubd84",
                            mm: "%d\ubd84",
                            h: "\ud55c \uc2dc\uac04",
                            hh: "%d\uc2dc\uac04",
                            d: "\ud558\ub8e8",
                            dd: "%d\uc77c",
                            M: "\ud55c \ub2ec",
                            MM: "%d\ub2ec",
                            y: "\uc77c \ub144",
                            yy: "%d\ub144"
                        }
                    };
                return r.default.locale(e, null, !0), e
            }(r(26958))
        },
        23042: function(n, t, r) {
            "use strict";

            function e(n) {
                return n
            }
            r.d(t, {
                C: function() {
                    return e
                }
            })
        },
        91966: function(n, t, r) {
            "use strict";
            r.d(t, {
                k: function() {
                    return o
                }
            });
            var e = r(96094),
                u = r(57496),
                i = r(53379),
                a = r(87933);

            function o(n, t) {
                return (0, i.r)((0, e.Z)((0, e.Z)({}, n), {}, {
                    enabled: !0,
                    suspense: !0,
                    throwOnError: a.Ct
                }), u.z, t)
            }
        },
        61752: function(n, t, r) {
            "use strict";
            var e = r(99710),
                u = r(10734),
                i = r(65976);
            t.Z = function(n, t) {
                return null == n ? n : (0, e.Z)(n, (0, u.Z)(t), i.Z)
            }
        },
        56358: function(n, t) {
            "use strict";
            t.Z = function(n) {
                return null === n
            }
        },
        15971: function(n, t, r) {
            "use strict";
            r.d(t, {
                Z: function() {
                    return _
                }
            });
            var e = r(2955),
                u = r(56137),
                i = r(9846);
            var a = function(n) {
                    var t = null == n ? 0 : n.length;
                    return t ? n[t - 1] : void 0
                },
                o = r(20186);
            var l = function(n, t, r) {
                var e = -1,
                    u = n.length;
                t < 0 && (t = -t > u ? 0 : u + t), (r = r > u ? u : r) < 0 && (r += u), u = t > r ? 0 : r - t >>> 0, t >>>= 0;
                for (var i = Array(u); ++e < u;) i[e] = n[e + t];
                return i
            };
            var s = function(n, t) {
                    return t.length < 2 ? n : (0, o.Z)(n, l(t, 0, -1))
                },
                c = r(5521);
            var f = function(n, t) {
                    return t = (0, i.Z)(t, n), null == (n = s(n, t)) || delete n[(0, c.Z)(a(t))]
                },
                d = r(55191),
                v = r(88172);
            var h = function(n) {
                    return (0, v.Z)(n) ? void 0 : n
                },
                m = r(72767),
                p = r(80375),
                _ = (0, m.Z)((function(n, t) {
                    var r = {};
                    if (null == n) return r;
                    var a = !1;
                    t = (0, e.Z)(t, (function(t) {
                        return t = (0, i.Z)(t, n), a || (a = t.length > 1), t
                    })), (0, d.Z)(n, (0, p.Z)(n), r), a && (r = (0, u.Z)(r, 7, h));
                    for (var o = t.length; o--;) f(r, t[o]);
                    return r
                }))
        },
        64001: function(n, t, r) {
            "use strict";
            r.d(t, {
                Z: function() {
                    return _
                }
            });
            var e = r(2955),
                u = r(20186),
                i = r(12819),
                a = r(97555),
                o = r(70956);
            var l = function(n, t) {
                var r = -1,
                    e = (0, o.Z)(n) ? Array(n.length) : [];
                return (0, a.Z)(n, (function(n, u, i) {
                    e[++r] = t(n, u, i)
                })), e
            };
            var s = function(n, t) {
                    var r = n.length;
                    for (n.sort(t); r--;) n[r] = n[r].value;
                    return n
                },
                c = r(47485),
                f = r(60447);
            var d = function(n, t) {
                if (n !== t) {
                    var r = void 0 !== n,
                        e = null === n,
                        u = n === n,
                        i = (0, f.Z)(n),
                        a = void 0 !== t,
                        o = null === t,
                        l = t === t,
                        s = (0, f.Z)(t);
                    if (!o && !s && !i && n > t || i && a && l && !o && !s || e && a && l || !r && l || !u) return 1;
                    if (!e && !i && !s && n < t || s && r && u && !e && !i || o && r && u || !a && u || !l) return -1
                }
                return 0
            };
            var v = function(n, t, r) {
                    for (var e = -1, u = n.criteria, i = t.criteria, a = u.length, o = r.length; ++e < a;) {
                        var l = d(u[e], i[e]);
                        if (l) return e >= o ? l : l * ("desc" == r[e] ? -1 : 1)
                    }
                    return n.index - t.index
                },
                h = r(61740),
                m = r(47368);
            var p = function(n, t, r) {
                t = t.length ? (0, e.Z)(t, (function(n) {
                    return (0, m.Z)(n) ? function(t) {
                        return (0, u.Z)(t, 1 === n.length ? n[0] : n)
                    } : n
                })) : [h.Z];
                var a = -1;
                t = (0, e.Z)(t, (0, c.Z)(i.Z));
                var o = l(n, (function(n, r, u) {
                    return {
                        criteria: (0, e.Z)(t, (function(t) {
                            return t(n)
                        })),
                        index: ++a,
                        value: n
                    }
                }));
                return s(o, (function(n, t) {
                    return v(n, t, r)
                }))
            };
            var _ = function(n, t, r, e) {
                return null == n ? [] : ((0, m.Z)(t) || (t = null == t ? [] : [t]), r = e ? void 0 : r, (0, m.Z)(r) || (r = null == r ? [] : [r]), p(n, t, r))
            }
        },
        68144: function(n, t, r) {
            "use strict";
            r.d(t, {
                Z: function() {
                    return _
                }
            });
            var e = r(12819),
                u = r(19456),
                i = r(53578);
            var a = function(n) {
                return n !== n
            };
            var o = function(n, t, r) {
                for (var e = r - 1, u = n.length; ++e < u;)
                    if (n[e] === t) return e;
                return -1
            };
            var l = function(n, t, r) {
                return t === t ? o(n, t, r) : (0, i.Z)(n, a, r)
            };
            var s = function(n, t) {
                return !!(null == n ? 0 : n.length) && l(n, t, 0) > -1
            };
            var c = function(n, t, r) {
                    for (var e = -1, u = null == n ? 0 : n.length; ++e < u;)
                        if (r(t, n[e])) return !0;
                    return !1
                },
                f = r(84539),
                d = r(27877);
            var v = function() {},
                h = r(86624),
                m = d.Z && 1 / (0, h.Z)(new d.Z([, -0]))[1] == 1 / 0 ? function(n) {
                    return new d.Z(n)
                } : v;
            var p = function(n, t, r) {
                var e = -1,
                    i = s,
                    a = n.length,
                    o = !0,
                    l = [],
                    d = l;
                if (r) o = !1, i = c;
                else if (a >= 200) {
                    var v = t ? null : m(n);
                    if (v) return (0, h.Z)(v);
                    o = !1, i = f.Z, d = new u.Z
                } else d = t ? [] : l;
                n: for (; ++e < a;) {
                    var p = n[e],
                        _ = t ? t(p) : p;
                    if (p = r || 0 !== p ? p : 0, o && _ === _) {
                        for (var Z = d.length; Z--;)
                            if (d[Z] === _) continue n;
                        t && d.push(_), l.push(p)
                    } else i(d, _, r) || (d !== l && d.push(_), l.push(p))
                }
                return l
            };
            var _ = function(n, t) {
                return n && n.length ? p(n, (0, e.Z)(t, 2)) : []
            }
        }
    }
]);