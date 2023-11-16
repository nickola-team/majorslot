(() => {
    var He = Object.create;
    var Ee = Object.defineProperty;
    var Se = Object.getOwnPropertyDescriptor;
    var We = Object.getOwnPropertyNames;
    var je = Object.getPrototypeOf,
        Je = Object.prototype.hasOwnProperty;
    var be = ($, p) => () => (p || $((p = {
        exports: {}
    }).exports, p), p.exports);
    var Ze = ($, p, H, W) => {
        if (p && typeof p == "object" || typeof p == "function")
            for (let s of We(p)) !Je.call($, s) && s !== H && Ee($, s, {
                get: () => p[s],
                enumerable: !(W = Se(p, s)) || W.enumerable
            });
        return $
    };
    var Wt = ($, p, H) => (H = $ != null ? He(je($)) : {}, Ze(p || !$ || !$.__esModule ? Ee(H, "default", {
        value: $,
        enumerable: !0
    }) : H, $));
    var Be = be((Ce, jt) => {
        (function($) {
            var p = "0.5.4",
                H = "hasOwnProperty",
                W = /[\.\/]/,
                s = /\s*,\s*/,
                C = "*",
                S = function(u, r) {
                    return u - r
                },
                Y, R, w = {
                    n: {}
                },
                L = function() {
                    for (var u = 0, r = this.length; u < r; u++)
                        if (typeof this[u] < "u") return this[u]
                },
                O = function() {
                    for (var u = this.length; --u;)
                        if (typeof this[u] < "u") return this[u]
                },
                h = Object.prototype.toString,
                l = String,
                v = Array.isArray || function(u) {
                    return u instanceof Array || h.call(u) == "[object Array]"
                },
                c = function(u, r) {
                    var d = R,
                        x = Array.prototype.slice.call(arguments, 2),
                        P = c.listeners(u),
                        b = 0,
                        m, e = [],
                        o = {},
                        n = [],
                        f = Y;
                    n.firstDefined = L, n.lastDefined = O, Y = u, R = 0;
                    for (var F = 0, I = P.length; F < I; F++) "zIndex" in P[F] && (e.push(P[F].zIndex), P[F].zIndex < 0 && (o[P[F].zIndex] = P[F]));
                    for (e.sort(S); e[b] < 0;)
                        if (m = o[e[b++]], n.push(m.apply(r, x)), R) return R = d, n;
                    for (F = 0; F < I; F++)
                        if (m = P[F], "zIndex" in m)
                            if (m.zIndex == e[b]) {
                                if (n.push(m.apply(r, x)), R) break;
                                do
                                    if (b++, m = o[e[b]], m && n.push(m.apply(r, x)), R) break; while (m)
                            } else o[m.zIndex] = m;
                    else if (n.push(m.apply(r, x)), R) break;
                    return R = d, Y = f, n
                };
            c._events = w, c.listeners = function(u) {
                var r = v(u) ? u : u.split(W),
                    d = w,
                    x, P, b, m, e, o, n, f, F = [d],
                    I = [];
                for (m = 0, e = r.length; m < e; m++) {
                    for (f = [], o = 0, n = F.length; o < n; o++)
                        for (d = F[o].n, P = [d[r[m]], d[C]], b = 2; b--;) x = P[b], x && (f.push(x), I = I.concat(x.f || []));
                    F = f
                }
                return I
            }, c.separator = function(u) {
                u ? (u = l(u).replace(/(?=[\.\^\]\[\-])/g, "\\"), u = "[" + u + "]", W = new RegExp(u)) : W = /[\.\/]/
            }, c.on = function(u, r) {
                if (typeof r != "function") return function() {};
                for (var d = v(u) ? v(u[0]) ? u : [u] : l(u).split(s), x = 0, P = d.length; x < P; x++)(function(b) {
                    for (var m = v(b) ? b : l(b).split(W), e = w, o, n = 0, f = m.length; n < f; n++) e = e.n, e = e.hasOwnProperty(m[n]) && e[m[n]] || (e[m[n]] = {
                        n: {}
                    });
                    for (e.f = e.f || [], n = 0, f = e.f.length; n < f; n++)
                        if (e.f[n] == r) {
                            o = !0;
                            break
                        }!o && e.f.push(r)
                })(d[x]);
                return function(b) {
                    +b == +b && (r.zIndex = +b)
                }
            }, c.f = function(u) {
                var r = [].slice.call(arguments, 1);
                return function() {
                    c.apply(null, [u, null].concat(r).concat([].slice.call(arguments, 0)))
                }
            }, c.stop = function() {
                R = 1
            }, c.nt = function(u) {
                var r = v(Y) ? Y.join(".") : Y;
                return u ? new RegExp("(?:\\.|\\/|^)" + u + "(?:\\.|\\/|$)").test(r) : r
            }, c.nts = function() {
                return v(Y) ? Y : Y.split(W)
            }, c.off = c.unbind = function(u, r) {
                if (!u) {
                    c._events = w = {
                        n: {}
                    };
                    return
                }
                var d = v(u) ? v(u[0]) ? u : [u] : l(u).split(s);
                if (d.length > 1) {
                    for (var m = 0, e = d.length; m < e; m++) c.off(d[m], r);
                    return
                }
                d = v(u) ? u : l(u).split(W);
                var x, P, b, m, e, o, n, f = [w],
                    F = [];
                for (m = 0, e = d.length; m < e; m++)
                    for (o = 0; o < f.length; o += b.length - 2) {
                        if (b = [o, 1], x = f[o].n, d[m] != C) x[d[m]] && (b.push(x[d[m]]), F.unshift({
                            n: x,
                            name: d[m]
                        }));
                        else
                            for (P in x) x[H](P) && (b.push(x[P]), F.unshift({
                                n: x,
                                name: P
                            }));
                        f.splice.apply(f, b)
                    }
                for (m = 0, e = f.length; m < e; m++)
                    for (x = f[m]; x.n;) {
                        if (r) {
                            if (x.f) {
                                for (o = 0, n = x.f.length; o < n; o++)
                                    if (x.f[o] == r) {
                                        x.f.splice(o, 1);
                                        break
                                    }!x.f.length && delete x.f
                            }
                            for (P in x.n)
                                if (x.n[H](P) && x.n[P].f) {
                                    var I = x.n[P].f;
                                    for (o = 0, n = I.length; o < n; o++)
                                        if (I[o] == r) {
                                            I.splice(o, 1);
                                            break
                                        }!I.length && delete x.n[P].f
                                }
                        } else {
                            delete x.f;
                            for (P in x.n) x.n[H](P) && x.n[P].f && delete x.n[P].f
                        }
                        x = x.n
                    }
                t: for (m = 0, e = F.length; m < e; m++) {
                    x = F[m];
                    for (P in x.n[x.name].f) continue t;
                    for (P in x.n[x.name].n) continue t;
                    delete x.n[x.name]
                }
            }, c.once = function(u, r) {
                var d = function() {
                    return c.off(u, d), r.apply(this, arguments)
                };
                return c.on(u, d)
            }, c.version = p, c.toString = function() {
                return "You are running Eve " + p
            }, $.eve = c, typeof jt < "u" && jt.exports ? jt.exports = c : typeof define == "function" && define.amd ? define("eve", [], function() {
                return c
            }) : $.eve = c
        })(typeof window < "u" ? window : Ce)
    });
    var It = be((Jt, qt) => {
        (function($) {
            var p = "0.5.0",
                H = "hasOwnProperty",
                W = /[\.\/]/,
                s = /\s*,\s*/,
                C = "*",
                S = function() {},
                Y = function(u, r) {
                    return u - r
                },
                R, w, L = {
                    n: {}
                },
                O = function() {
                    for (var u = 0, r = this.length; u < r; u++)
                        if (typeof this[u] < "u") return this[u]
                },
                h = function() {
                    for (var u = this.length; --u;)
                        if (typeof this[u] < "u") return this[u]
                },
                l = Object.prototype.toString,
                v = String,
                c = Array.isArray || function(u) {
                    return u instanceof Array || l.call(u) == "[object Array]"
                };
            eve = function(u, r) {
                var d = L,
                    x = w,
                    P = Array.prototype.slice.call(arguments, 2),
                    b = eve.listeners(u),
                    m = 0,
                    e = !1,
                    o, n = [],
                    f = {},
                    F = [],
                    I = R,
                    j = [];
                F.firstDefined = O, F.lastDefined = h, R = u, w = 0;
                for (var U = 0, tt = b.length; U < tt; U++) "zIndex" in b[U] && (n.push(b[U].zIndex), b[U].zIndex < 0 && (f[b[U].zIndex] = b[U]));
                for (n.sort(Y); n[m] < 0;)
                    if (o = f[n[m++]], F.push(o.apply(r, P)), w) return w = x, F;
                for (U = 0; U < tt; U++)
                    if (o = b[U], "zIndex" in o)
                        if (o.zIndex == n[m]) {
                            if (F.push(o.apply(r, P)), w) break;
                            do
                                if (m++, o = f[n[m]], o && F.push(o.apply(r, P)), w) break; while (o)
                        } else f[o.zIndex] = o;
                else if (F.push(o.apply(r, P)), w) break;
                return w = x, R = I, F
            }, eve._events = L, eve.listeners = function(u) {
                var r = c(u) ? u : u.split(W),
                    d = L,
                    x, P, b, m, e, o, n, f, F = [d],
                    I = [];
                for (m = 0, e = r.length; m < e; m++) {
                    for (f = [], o = 0, n = F.length; o < n; o++)
                        for (d = F[o].n, P = [d[r[m]], d[C]], b = 2; b--;) x = P[b], x && (f.push(x), I = I.concat(x.f || []));
                    F = f
                }
                return I
            }, eve.separator = function(u) {
                u ? (u = v(u).replace(/(?=[\.\^\]\[\-])/g, "\\"), u = "[" + u + "]", W = new RegExp(u)) : W = /[\.\/]/
            }, eve.on = function(u, r) {
                if (typeof r != "function") return function() {};
                for (var d = c(u) ? c(u[0]) ? u : [u] : v(u).split(s), x = 0, P = d.length; x < P; x++)(function(b) {
                    for (var m = c(b) ? b : v(b).split(W), e = L, o, n = 0, f = m.length; n < f; n++) e = e.n, e = e.hasOwnProperty(m[n]) && e[m[n]] || (e[m[n]] = {
                        n: {}
                    });
                    for (e.f = e.f || [], n = 0, f = e.f.length; n < f; n++)
                        if (e.f[n] == r) {
                            o = !0;
                            break
                        }!o && e.f.push(r)
                })(d[x]);
                return function(b) {
                    +b == +b && (r.zIndex = +b)
                }
            }, eve.f = function(u) {
                var r = [].slice.call(arguments, 1);
                return function() {
                    eve.apply(null, [u, null].concat(r).concat([].slice.call(arguments, 0)))
                }
            }, eve.stop = function() {
                w = 1
            }, eve.nt = function(u) {
                var r = c(R) ? R.join(".") : R;
                return u ? new RegExp("(?:\\.|\\/|^)" + u + "(?:\\.|\\/|$)").test(r) : r
            }, eve.nts = function() {
                return c(R) ? R : R.split(W)
            }, eve.off = eve.unbind = function(u, r) {
                if (!u) {
                    eve._events = L = {
                        n: {}
                    };
                    return
                }
                var d = c(u) ? c(u[0]) ? u : [u] : v(u).split(s);
                if (d.length > 1) {
                    for (var m = 0, e = d.length; m < e; m++) eve.off(d[m], r);
                    return
                }
                d = c(u) ? u : v(u).split(W);
                var x, P, b, m, e, o, n, f = [L],
                    F = [];
                for (m = 0, e = d.length; m < e; m++)
                    for (o = 0; o < f.length; o += b.length - 2) {
                        if (b = [o, 1], x = f[o].n, d[m] != C) x[d[m]] && (b.push(x[d[m]]), F.unshift({
                            n: x,
                            name: d[m]
                        }));
                        else
                            for (P in x) x[H](P) && (b.push(x[P]), F.unshift({
                                n: x,
                                name: P
                            }));
                        f.splice.apply(f, b)
                    }
                for (m = 0, e = f.length; m < e; m++)
                    for (x = f[m]; x.n;) {
                        if (r) {
                            if (x.f) {
                                for (o = 0, n = x.f.length; o < n; o++)
                                    if (x.f[o] == r) {
                                        x.f.splice(o, 1);
                                        break
                                    }!x.f.length && delete x.f
                            }
                            for (P in x.n)
                                if (x.n[H](P) && x.n[P].f) {
                                    var I = x.n[P].f;
                                    for (o = 0, n = I.length; o < n; o++)
                                        if (I[o] == r) {
                                            I.splice(o, 1);
                                            break
                                        }!I.length && delete x.n[P].f
                                }
                        } else {
                            delete x.f;
                            for (P in x.n) x.n[H](P) && x.n[P].f && delete x.n[P].f
                        }
                        x = x.n
                    }
                t: for (m = 0, e = F.length; m < e; m++) {
                    x = F[m];
                    for (P in x.n[x.name].f) continue t;
                    for (P in x.n[x.name].n) continue t;
                    delete x.n[x.name]
                }
            }, eve.once = function(u, r) {
                var d = function() {
                    return eve.off(u, d), r.apply(this, arguments)
                };
                return eve.on(u, d)
            }, eve.version = p, eve.toString = function() {
                return "You are running Eve " + p
            }, typeof qt < "u" && qt.exports ? qt.exports = eve : typeof define == "function" && define.amd ? define("eve", [], function() {
                return eve
            }) : $.eve = eve
        })(Jt);
        (function($, p) {
            if (typeof define == "function" && define.amd) define(["eve"], function(W) {
                return p($, W)
            });
            else if (typeof Jt < "u") {
                var H = Be();
                qt.exports = p($, H)
            } else p($, $.eve)
        })(window || Jt, function($, p) {
            var H = function(s) {
                    var C = {},
                        S = $.requestAnimationFrame || $.webkitRequestAnimationFrame || $.mozRequestAnimationFrame || $.oRequestAnimationFrame || $.msRequestAnimationFrame || function(e) {
                            return setTimeout(e, 16, new Date().getTime()), !0
                        },
                        Y, R = Array.isArray || function(e) {
                            return e instanceof Array || Object.prototype.toString.call(e) == "[object Array]"
                        },
                        w = 0,
                        L = "M" + (+new Date).toString(36),
                        O = function() {
                            return L + (w++).toString(36)
                        },
                        h = function(e, o, n, f) {
                            if (R(e)) {
                                res = [];
                                for (var F = 0, I = e.length; F < I; F++) res[F] = h(e[F], o, n[F], f);
                                return res
                            }
                            var j = (n - e) / (f - o);
                            return function(U) {
                                return e + j * (U - o)
                            }
                        },
                        l = Date.now || function() {
                            return +new Date
                        },
                        v = function(e) {
                            var o = this;
                            if (e == null) return o.s;
                            var n = o.s - e;
                            o.b += o.dur * n, o.B += o.dur * n, o.s = e
                        },
                        c = function(e) {
                            var o = this;
                            if (e == null) return o.spd;
                            o.spd = e
                        },
                        u = function(e) {
                            var o = this;
                            if (e == null) return o.dur;
                            o.s = o.s * e / o.dur, o.dur = e
                        },
                        r = function() {
                            var e = this;
                            delete C[e.id], e.update(), s("mina.stop." + e.id, e)
                        },
                        d = function() {
                            var e = this;
                            e.pdif || (delete C[e.id], e.update(), e.pdif = e.get() - e.b)
                        },
                        x = function() {
                            var e = this;
                            e.pdif && (e.b = e.get() - e.pdif, delete e.pdif, C[e.id] = e, b())
                        },
                        P = function() {
                            var e = this,
                                o;
                            if (R(e.start)) {
                                o = [];
                                for (var n = 0, f = e.start.length; n < f; n++) o[n] = +e.start[n] + (e.end[n] - e.start[n]) * e.easing(e.s)
                            } else o = +e.start + (e.end - e.start) * e.easing(e.s);
                            e.set(o)
                        },
                        b = function(e) {
                            if (!e) {
                                Y || (Y = S(b));
                                return
                            }
                            var o = 0;
                            for (var n in C)
                                if (C.hasOwnProperty(n)) {
                                    var f = C[n],
                                        F = f.get(),
                                        I;
                                    o++, f.s = (F - f.b) / (f.dur / f.spd), f.s >= 1 && (delete C[n], f.s = 1, o--, function(j) {
                                        setTimeout(function() {
                                            s("mina.finish." + j.id, j)
                                        })
                                    }(f)), f.update()
                                }
                            Y = o ? S(b) : !1
                        },
                        m = function(e, o, n, f, F, I, j) {
                            var U = {
                                id: O(),
                                start: e,
                                end: o,
                                b: n,
                                s: 0,
                                dur: f - n,
                                spd: 1,
                                get: F,
                                set: I,
                                easing: j || m.linear,
                                status: v,
                                speed: c,
                                duration: u,
                                stop: r,
                                pause: d,
                                resume: x,
                                update: P
                            };
                            C[U.id] = U;
                            var tt = 0,
                                rt;
                            for (rt in C)
                                if (C.hasOwnProperty(rt) && (tt++, tt == 2)) break;
                            return tt == 1 && b(), U
                        };
                    return m.time = l, m.getById = function(e) {
                        return C[e] || null
                    }, m.linear = function(e) {
                        return e
                    }, m.easeout = function(e) {
                        return Math.pow(e, 1.7)
                    }, m.easein = function(e) {
                        return Math.pow(e, .48)
                    }, m.easeinout = function(e) {
                        if (e == 1) return 1;
                        if (e == 0) return 0;
                        var o = .48 - e / 1.04,
                            n = Math.sqrt(.1734 + o * o),
                            f = n - o,
                            F = Math.pow(Math.abs(f), 1 / 3) * (f < 0 ? -1 : 1),
                            I = -n - o,
                            j = Math.pow(Math.abs(I), 1 / 3) * (I < 0 ? -1 : 1),
                            U = F + j + .5;
                        return (1 - U) * 3 * U * U + U * U * U
                    }, m.backin = function(e) {
                        if (e == 1) return 1;
                        var o = 1.70158;
                        return e * e * ((o + 1) * e - o)
                    }, m.backout = function(e) {
                        if (e == 0) return 0;
                        e = e - 1;
                        var o = 1.70158;
                        return e * e * ((o + 1) * e + o) + 1
                    }, m.elastic = function(e) {
                        return e == !!e ? e : Math.pow(2, -10 * e) * Math.sin((e - .075) * (2 * Math.PI) / .3) + 1
                    }, m.bounce = function(e) {
                        var o = 7.5625,
                            n = 2.75,
                            f;
                        return e < 1 / n ? f = o * e * e : e < 2 / n ? (e -= 1.5 / n, f = o * e * e + .75) : e < 2.5 / n ? (e -= 2.25 / n, f = o * e * e + .9375) : (e -= 2.625 / n, f = o * e * e + .984375), f
                    }, $.mina = m, m
                }(typeof p > "u" ? function() {} : p),
                W = function(s) {
                    C.version = "0.5.1";

                    function C(t, i) {
                        if (t) {
                            if (t.nodeType) return M(t);
                            if (ut(t, "array") && C.set) return C.set.apply(C, t);
                            if (t instanceof N) return t;
                            if (i == null) return t = S.doc.querySelector(String(t)), M(t)
                        }
                        return t = t == null ? "100%" : t, i = i == null ? "100%" : i, new J(t, i)
                    }
                    C.toString = function() {
                        return "Snap v" + this.version
                    }, C._ = {};
                    var S = {
                        win: s.window,
                        doc: s.window.document
                    };
                    C._.glob = S;
                    var Y = "hasOwnProperty",
                        R = String,
                        w = parseFloat,
                        L = parseInt,
                        O = Math,
                        h = O.max,
                        l = O.min,
                        v = O.abs,
                        c = O.pow,
                        u = O.PI,
                        r = O.round,
                        d = "",
                        x = " ",
                        P = Object.prototype.toString,
                        b = /^url\(['"]?([^\)]+?)['"]?\)$/i,
                        m = /^\s*((#[a-f\d]{6})|(#[a-f\d]{3})|rgba?\(\s*([\d\.]+%?\s*,\s*[\d\.]+%?\s*,\s*[\d\.]+%?(?:\s*,\s*[\d\.]+%?)?)\s*\)|hsba?\(\s*([\d\.]+(?:deg|\xb0|%)?\s*,\s*[\d\.]+%?\s*,\s*[\d\.]+(?:%?\s*,\s*[\d\.]+)?%?)\s*\)|hsla?\(\s*([\d\.]+(?:deg|\xb0|%)?\s*,\s*[\d\.]+%?\s*,\s*[\d\.]+(?:%?\s*,\s*[\d\.]+)?%?)\s*\))\s*$/i,
                        e = /^(?:cubic-)?bezier\(([^,]+),([^,]+),([^,]+),([^\)]+)\)/,
                        o = C._.separator = /[,\s]+/,
                        n = /[\s]/g,
                        f = /[\s]*,[\s]*/,
                        F = {
                            hs: 1,
                            rg: 1
                        },
                        I = /([a-z])[\s,]*((-?\d*\.?\d*(?:e[\-+]?\d+)?[\s]*,?[\s]*)+)/ig,
                        j = /([rstm])[\s,]*((-?\d*\.?\d*(?:e[\-+]?\d+)?[\s]*,?[\s]*)+)/ig,
                        U = /(-?\d*\.?\d*(?:e[\-+]?\d+)?)[\s]*,?[\s]*/ig,
                        tt = 0,
                        rt = "S" + (+new Date).toString(36),
                        ot = function(t) {
                            return (t && t.type ? t.type : d) + rt + (tt++).toString(36)
                        },
                        at = "http://www.w3.org/1999/xlink",
                        ct = "http://www.w3.org/2000/svg",
                        it = {},
                        vt = C.url = function(t) {
                            return "url('#" + t + "')"
                        };

                    function ht(t, i) {
                        if (i) {
                            if (t == "#text" && (t = S.doc.createTextNode(i.text || i["#text"] || "")), t == "#comment" && (t = S.doc.createComment(i.text || i["#text"] || "")), typeof t == "string" && (t = ht(t)), typeof i == "string") return t.nodeType == 1 ? i.substring(0, 6) == "xlink:" ? t.getAttributeNS(at, i.substring(6)) : i.substring(0, 4) == "xml:" ? t.getAttributeNS(ct, i.substring(4)) : t.getAttribute(i) : i == "text" ? t.nodeValue : null;
                            if (t.nodeType == 1) {
                                for (var a in i)
                                    if (i[Y](a)) {
                                        var E = R(i[a]);
                                        E ? a.substring(0, 6) == "xlink:" ? t.setAttributeNS(at, a.substring(6), E) : a.substring(0, 4) == "xml:" ? t.setAttributeNS(ct, a.substring(4), E) : t.setAttribute(a, E) : t.removeAttribute(a)
                                    }
                            } else "text" in i && (t.nodeValue = i.text)
                        } else t = S.doc.createElementNS(ct, t);
                        return t
                    }
                    C._.$ = ht, C._.id = ot;

                    function dt(t) {
                        for (var i = t.attributes, a, E = {}, A = 0; A < i.length; A++) i[A].namespaceURI == at ? a = "xlink:" : a = "", a += i[A].name, E[a] = i[A].textContent;
                        return E
                    }

                    function ut(t, i) {
                        return i = R.prototype.toLowerCase.call(i), i == "finite" ? isFinite(t) : i == "array" && (t instanceof Array || Array.isArray && Array.isArray(t)) ? !0 : i == "null" && t === null || i == typeof t && t !== null || i == "object" && t === Object(t) || P.call(t).slice(8, -1).toLowerCase() == i
                    }
                    C.format = function() {
                        var t = /\{([^\}]+)\}/g,
                            i = /(?:(?:^|\.)(.+?)(?=\[|\.|$|\()|\[('|")(.+?)\2\])(\(\))?/g,
                            a = function(E, A, q) {
                                var T = q;
                                return A.replace(i, function(z, k, V, et, nt) {
                                    k = k || et, T && (k in T && (T = T[k]), typeof T == "function" && nt && (T = T()))
                                }), T = (T == null || T == q ? E : T) + "", T
                            };
                        return function(E, A) {
                            return R(E).replace(t, function(q, T) {
                                return a(q, T, A)
                            })
                        }
                    }();

                    function _t(t) {
                        if (typeof t == "function" || Object(t) !== t) return t;
                        var i = new t.constructor;
                        for (var a in t) t[Y](a) && (i[a] = _t(t[a]));
                        return i
                    }
                    C._.clone = _t;

                    function Lt(t, i) {
                        for (var a = 0, E = t.length; a < E; a++)
                            if (t[a] === i) return t.push(t.splice(a, 1)[0])
                    }

                    function Ft(t, i, a) {
                        function E() {
                            var A = Array.prototype.slice.call(arguments, 0),
                                q = A.join("\u2400"),
                                T = E.cache = E.cache || {},
                                z = E.count = E.count || [];
                            return T[Y](q) ? (Lt(z, q), a ? a(T[q]) : T[q]) : (z.length >= 1e3 && delete T[z.shift()], z.push(q), T[q] = t.apply(i, A), a ? a(T[q]) : T[q])
                        }
                        return E
                    }
                    C._.cacher = Ft;

                    function At(t, i, a, E, A, q) {
                        if (A == null) {
                            var T = t - a,
                                z = i - E;
                            return !T && !z ? 0 : (180 + O.atan2(-z, -T) * 180 / u + 360) % 360
                        } else return At(t, i, A, q) - At(a, E, A, q)
                    }

                    function Et(t) {
                        return t % 360 * u / 180
                    }

                    function bt(t) {
                        return t * 180 / u % 360
                    }

                    function kt() {
                        return this.x + x + this.y
                    }

                    function ce() {
                        return this.x + x + this.y + x + this.width + " \xD7 " + this.height
                    }
                    C.rad = Et, C.deg = bt, C.sin = function(t) {
                        return O.sin(C.rad(t))
                    }, C.tan = function(t) {
                        return O.tan(C.rad(t))
                    }, C.cos = function(t) {
                        return O.cos(C.rad(t))
                    }, C.asin = function(t) {
                        return C.deg(O.asin(t))
                    }, C.acos = function(t) {
                        return C.deg(O.acos(t))
                    }, C.atan = function(t) {
                        return C.deg(O.atan(t))
                    }, C.atan2 = function(t) {
                        return C.deg(O.atan2(t))
                    }, C.angle = At, C.len = function(t, i, a, E) {
                        return Math.sqrt(C.len2(t, i, a, E))
                    }, C.len2 = function(t, i, a, E) {
                        return (t - a) * (t - a) + (i - E) * (i - E)
                    }, C.closestPoint = function(t, i, a) {
                        function E($t) {
                            var Xt = $t.x - i,
                                Ht = $t.y - a;
                            return Xt * Xt + Ht * Ht
                        }
                        for (var A = t.node, q = A.getTotalLength(), T = q / A.pathSegList.numberOfItems * .125, z, k, V = 1 / 0, et, nt = 0, lt; nt <= q; nt += T)(lt = E(et = A.getPointAtLength(nt))) < V && (z = et, k = nt, V = lt);
                        for (T *= .5; T > .5;) {
                            var ft, st, pt, zt, Vt, Yt;
                            (pt = k - T) >= 0 && (Vt = E(ft = A.getPointAtLength(pt))) < V ? (z = ft, k = pt, V = Vt) : (zt = k + T) <= q && (Yt = E(st = A.getPointAtLength(zt))) < V ? (z = st, k = zt, V = Yt) : T *= .5
                        }
                        return z = {
                            x: z.x,
                            y: z.y,
                            length: k,
                            distance: Math.sqrt(V)
                        }, z
                    }, C.is = ut, C.snapTo = function(t, i, a) {
                        if (a = ut(a, "finite") ? a : 10, ut(t, "array")) {
                            for (var E = t.length; E--;)
                                if (v(t[E] - i) <= a) return t[E]
                        } else {
                            t = +t;
                            var A = i % t;
                            if (A < a) return i - A;
                            if (A > t - a) return i - A + t
                        }
                        return i
                    }, C.getRGB = Ft(function(t) {
                        if (!t || (t = R(t)).indexOf("-") + 1) return {
                            r: -1,
                            g: -1,
                            b: -1,
                            hex: "none",
                            error: 1,
                            toString: yt
                        };
                        if (t == "none") return {
                            r: -1,
                            g: -1,
                            b: -1,
                            hex: "none",
                            toString: yt
                        };
                        if (!(F[Y](t.toLowerCase().substring(0, 2)) || t.charAt() == "#") && (t = Mt(t)), !t) return {
                            r: -1,
                            g: -1,
                            b: -1,
                            hex: "none",
                            error: 1,
                            toString: yt
                        };
                        var i, a, E, A, q, T, z, k = t.match(m);
                        return k ? (k[2] && (A = L(k[2].substring(5), 16), E = L(k[2].substring(3, 5), 16), a = L(k[2].substring(1, 3), 16)), k[3] && (A = L((T = k[3].charAt(3)) + T, 16), E = L((T = k[3].charAt(2)) + T, 16), a = L((T = k[3].charAt(1)) + T, 16)), k[4] && (z = k[4].split(f), a = w(z[0]), z[0].slice(-1) == "%" && (a *= 2.55), E = w(z[1]), z[1].slice(-1) == "%" && (E *= 2.55), A = w(z[2]), z[2].slice(-1) == "%" && (A *= 2.55), k[1].toLowerCase().slice(0, 4) == "rgba" && (q = w(z[3])), z[3] && z[3].slice(-1) == "%" && (q /= 100)), k[5] ? (z = k[5].split(f), a = w(z[0]), z[0].slice(-1) == "%" && (a /= 100), E = w(z[1]), z[1].slice(-1) == "%" && (E /= 100), A = w(z[2]), z[2].slice(-1) == "%" && (A /= 100), (z[0].slice(-3) == "deg" || z[0].slice(-1) == "\xB0") && (a /= 360), k[1].toLowerCase().slice(0, 4) == "hsba" && (q = w(z[3])), z[3] && z[3].slice(-1) == "%" && (q /= 100), C.hsb2rgb(a, E, A, q)) : k[6] ? (z = k[6].split(f), a = w(z[0]), z[0].slice(-1) == "%" && (a /= 100), E = w(z[1]), z[1].slice(-1) == "%" && (E /= 100), A = w(z[2]), z[2].slice(-1) == "%" && (A /= 100), (z[0].slice(-3) == "deg" || z[0].slice(-1) == "\xB0") && (a /= 360), k[1].toLowerCase().slice(0, 4) == "hsla" && (q = w(z[3])), z[3] && z[3].slice(-1) == "%" && (q /= 100), C.hsl2rgb(a, E, A, q)) : (a = l(O.round(a), 255), E = l(O.round(E), 255), A = l(O.round(A), 255), q = l(h(q, 0), 1), k = {
                            r: a,
                            g: E,
                            b: A,
                            toString: yt
                        }, k.hex = "#" + (16777216 | A | E << 8 | a << 16).toString(16).slice(1), k.opacity = ut(q, "finite") ? q : 1, k)) : {
                            r: -1,
                            g: -1,
                            b: -1,
                            hex: "none",
                            error: 1,
                            toString: yt
                        }
                    }, C), C.hsb = Ft(function(t, i, a) {
                        return C.hsb2rgb(t, i, a).hex
                    }), C.hsl = Ft(function(t, i, a) {
                        return C.hsl2rgb(t, i, a).hex
                    }), C.rgb = Ft(function(t, i, a, E) {
                        if (ut(E, "finite")) {
                            var A = O.round;
                            return "rgba(" + [A(t), A(i), A(a), +E.toFixed(2)] + ")"
                        }
                        return "#" + (16777216 | a | i << 8 | t << 16).toString(16).slice(1)
                    });
                    var Mt = function(t) {
                            var i = S.doc.getElementsByTagName("head")[0] || S.doc.getElementsByTagName("svg")[0],
                                a = "rgb(255, 0, 0)";
                            return Mt = Ft(function(E) {
                                if (E.toLowerCase() == "red") return a;
                                i.style.color = a, i.style.color = E;
                                var A = S.doc.defaultView.getComputedStyle(i, d).getPropertyValue("color");
                                return A == a ? null : A
                            }), Mt(t)
                        },
                        Rt = function() {
                            return "hsb(" + [this.h, this.s, this.b] + ")"
                        },
                        Tt = function() {
                            return "hsl(" + [this.h, this.s, this.l] + ")"
                        },
                        yt = function() {
                            return this.opacity == 1 || this.opacity == null ? this.hex : "rgba(" + [this.r, this.g, this.b, this.opacity] + ")"
                        },
                        Ot = function(t, i, a) {
                            if (i == null && ut(t, "object") && "r" in t && "g" in t && "b" in t && (a = t.b, i = t.g, t = t.r), i == null && ut(t, string)) {
                                var E = C.getRGB(t);
                                t = E.r, i = E.g, a = E.b
                            }
                            return (t > 1 || i > 1 || a > 1) && (t /= 255, i /= 255, a /= 255), [t, i, a]
                        },
                        ee = function(t, i, a, E) {
                            t = O.round(t * 255), i = O.round(i * 255), a = O.round(a * 255);
                            var A = {
                                r: t,
                                g: i,
                                b: a,
                                opacity: ut(E, "finite") ? E : 1,
                                hex: C.rgb(t, i, a),
                                toString: yt
                            };
                            return ut(E, "finite") && (A.opacity = E), A
                        };
                    C.color = function(t) {
                        var i;
                        return ut(t, "object") && "h" in t && "s" in t && "b" in t ? (i = C.hsb2rgb(t), t.r = i.r, t.g = i.g, t.b = i.b, t.opacity = 1, t.hex = i.hex) : ut(t, "object") && "h" in t && "s" in t && "l" in t ? (i = C.hsl2rgb(t), t.r = i.r, t.g = i.g, t.b = i.b, t.opacity = 1, t.hex = i.hex) : (ut(t, "string") && (t = C.getRGB(t)), ut(t, "object") && "r" in t && "g" in t && "b" in t && !("error" in t) ? (i = C.rgb2hsl(t), t.h = i.h, t.s = i.s, t.l = i.l, i = C.rgb2hsb(t), t.v = i.b) : (t = {
                            hex: "none"
                        }, t.r = t.g = t.b = t.h = t.s = t.v = t.l = -1, t.error = 1)), t.toString = yt, t
                    }, C.hsb2rgb = function(t, i, a, E) {
                        ut(t, "object") && "h" in t && "s" in t && "b" in t && (a = t.b, i = t.s, E = t.o, t = t.h), t *= 360;
                        var A, q, T, z, k;
                        return t = t % 360 / 60, k = a * i, z = k * (1 - v(t % 2 - 1)), A = q = T = a - k, t = ~~t, A += [k, z, 0, 0, z, k][t], q += [z, k, k, z, 0, 0][t], T += [0, 0, z, k, k, z][t], ee(A, q, T, E)
                    }, C.hsl2rgb = function(t, i, a, E) {
                        ut(t, "object") && "h" in t && "s" in t && "l" in t && (a = t.l, i = t.s, t = t.h), (t > 1 || i > 1 || a > 1) && (t /= 360, i /= 100, a /= 100), t *= 360;
                        var A, q, T, z, k;
                        return t = t % 360 / 60, k = 2 * i * (a < .5 ? a : 1 - a), z = k * (1 - v(t % 2 - 1)), A = q = T = a - k / 2, t = ~~t, A += [k, z, 0, 0, z, k][t], q += [z, k, k, z, 0, 0][t], T += [0, 0, z, k, k, z][t], ee(A, q, T, E)
                    }, C.rgb2hsb = function(t, i, a) {
                        a = Ot(t, i, a), t = a[0], i = a[1], a = a[2];
                        var E, A, q, T;
                        return q = h(t, i, a), T = q - l(t, i, a), E = T == 0 ? null : q == t ? (i - a) / T : q == i ? (a - t) / T + 2 : (t - i) / T + 4, E = (E + 360) % 6 * 60 / 360, A = T == 0 ? 0 : T / q, {
                            h: E,
                            s: A,
                            b: q,
                            toString: Rt
                        }
                    }, C.rgb2hsl = function(t, i, a) {
                        a = Ot(t, i, a), t = a[0], i = a[1], a = a[2];
                        var E, A, q, T, z, k;
                        return T = h(t, i, a), z = l(t, i, a), k = T - z, E = k == 0 ? null : T == t ? (i - a) / k : T == i ? (a - t) / k + 2 : (t - i) / k + 4, E = (E + 360) % 6 * 60 / 360, q = (T + z) / 2, A = k == 0 ? 0 : q < .5 ? k / (2 * q) : k / (2 - 2 * q), {
                            h: E,
                            s: A,
                            l: q,
                            toString: Tt
                        }
                    }, C.parsePathString = function(t) {
                        if (!t) return null;
                        var i = C.path(t);
                        if (i.arr) return C.path.clone(i.arr);
                        var a = {
                                a: 7,
                                c: 6,
                                o: 2,
                                h: 1,
                                l: 2,
                                m: 2,
                                r: 4,
                                q: 4,
                                s: 4,
                                t: 2,
                                v: 1,
                                u: 3,
                                z: 0
                            },
                            E = [];
                        return ut(t, "array") && ut(t[0], "array") && (E = C.path.clone(t)), E.length || R(t).replace(I, function(A, q, T) {
                            var z = [],
                                k = q.toLowerCase();
                            if (T.replace(U, function(V, et) {
                                    et && z.push(+et)
                                }), k == "m" && z.length > 2 && (E.push([q].concat(z.splice(0, 2))), k = "l", q = q == "m" ? "l" : "L"), k == "o" && z.length == 1 && E.push([q, z[0]]), k == "r") E.push([q].concat(z));
                            else
                                for (; z.length >= a[k] && (E.push([q].concat(z.splice(0, a[k]))), !!a[k]););
                        }), E.toString = C.path.toString, i.arr = C.path.clone(E), E
                    };
                    var Gt = C.parseTransformString = function(t) {
                        if (!t) return null;
                        var i = {
                                r: 3,
                                s: 4,
                                t: 2,
                                m: 6
                            },
                            a = [];
                        return ut(t, "array") && ut(t[0], "array") && (a = C.path.clone(t)), a.length || R(t).replace(j, function(E, A, q) {
                            var T = [],
                                z = A.toLowerCase();
                            q.replace(U, function(k, V) {
                                V && T.push(+V)
                            }), a.push([A].concat(T))
                        }), a.toString = C.path.toString, a
                    };

                    function wt(t) {
                        var i = [];
                        return t = t.replace(/(?:^|\s)(\w+)\(([^)]+)\)/g, function(a, E, A) {
                            return A = A.split(/\s*,\s*|\s+/), E == "rotate" && A.length == 1 && A.push(0, 0), E == "scale" && (A.length > 2 ? A = A.slice(0, 2) : A.length == 2 && A.push(0, 0), A.length == 1 && A.push(A[0], 0, 0)), E == "skewX" ? i.push(["m", 1, 0, O.tan(Et(A[0])), 1, 0, 0]) : E == "skewY" ? i.push(["m", 1, O.tan(Et(A[0])), 0, 1, 0, 0]) : i.push([E.charAt(0)].concat(A)), a
                        }), i
                    }
                    C._.svgTransform2string = wt, C._.rgTransform = /^[a-z][\s]*-?\.?\d/i;

                    function ne(t, i) {
                        var a = Gt(t),
                            E = new C.Matrix;
                        if (a)
                            for (var A = 0, q = a.length; A < q; A++) {
                                var T = a[A],
                                    z = T.length,
                                    k = R(T[0]).toLowerCase(),
                                    V = T[0] != k,
                                    et = V ? E.invert() : 0,
                                    nt, lt, ft, st, pt;
                                k == "t" && z == 2 ? E.translate(T[1], 0) : k == "t" && z == 3 ? V ? (nt = et.x(0, 0), lt = et.y(0, 0), ft = et.x(T[1], T[2]), st = et.y(T[1], T[2]), E.translate(ft - nt, st - lt)) : E.translate(T[1], T[2]) : k == "r" ? z == 2 ? (pt = pt || i, E.rotate(T[1], pt.x + pt.width / 2, pt.y + pt.height / 2)) : z == 4 && (V ? (ft = et.x(T[2], T[3]), st = et.y(T[2], T[3]), E.rotate(T[1], ft, st)) : E.rotate(T[1], T[2], T[3])) : k == "s" ? z == 2 || z == 3 ? (pt = pt || i, E.scale(T[1], T[z - 1], pt.x + pt.width / 2, pt.y + pt.height / 2)) : z == 4 ? V ? (ft = et.x(T[2], T[3]), st = et.y(T[2], T[3]), E.scale(T[1], T[1], ft, st)) : E.scale(T[1], T[1], T[2], T[3]) : z == 5 && (V ? (ft = et.x(T[3], T[4]), st = et.y(T[3], T[4]), E.scale(T[1], T[2], ft, st)) : E.scale(T[1], T[2], T[3], T[4])) : k == "m" && z == 7 && E.add(T[1], T[2], T[3], T[4], T[5], T[6])
                            }
                        return E
                    }
                    C._.transform2matrix = ne, C._unit2px = D;
                    var re = S.doc.contains || S.doc.compareDocumentPosition ? function(t, i) {
                        var a = t.nodeType == 9 ? t.documentElement : t,
                            E = i && i.parentNode;
                        return t == E || !!(E && E.nodeType == 1 && (a.contains ? a.contains(E) : t.compareDocumentPosition && t.compareDocumentPosition(E) & 16))
                    } : function(t, i) {
                        if (i) {
                            for (; i;)
                                if (i = i.parentNode, i == t) return !0
                        }
                        return !1
                    };

                    function y(t) {
                        var i = t.node.ownerSVGElement && M(t.node.ownerSVGElement) || t.node.parentNode && M(t.node.parentNode) || C.select("svg") || C(0, 0),
                            a = i.select("defs"),
                            E = a == null ? !1 : a.node;
                        return E || (E = X("defs", i.node).node), E
                    }

                    function B(t) {
                        return t.node.ownerSVGElement && M(t.node.ownerSVGElement) || C.select("svg")
                    }
                    C._.getSomeDefs = y, C._.getSomeSVG = B;

                    function D(t, i, a) {
                        var E = B(t).node,
                            A = {},
                            q = E.querySelector(".svg---mgr");
                        q || (q = ht("rect"), ht(q, {
                            x: -9e9,
                            y: -9e9,
                            width: 10,
                            height: 10,
                            class: "svg---mgr",
                            fill: "none"
                        }), E.appendChild(q));

                        function T(V) {
                            if (V == null) return d;
                            if (V == +V) return V;
                            ht(q, {
                                width: V
                            });
                            try {
                                return q.getBBox().width
                            } catch (et) {
                                return 0
                            }
                        }

                        function z(V) {
                            if (V == null) return d;
                            if (V == +V) return V;
                            ht(q, {
                                height: V
                            });
                            try {
                                return q.getBBox().height
                            } catch (et) {
                                return 0
                            }
                        }

                        function k(V, et) {
                            i == null ? A[V] = et(t.attr(V) || 0) : V == i && (A = et(a == null ? t.attr(V) || 0 : a))
                        }
                        switch (t.type) {
                            case "rect":
                                k("rx", T), k("ry", z);
                            case "image":
                                k("width", T), k("height", z);
                            case "text":
                                k("x", T), k("y", z);
                                break;
                            case "circle":
                                k("cx", T), k("cy", z), k("r", T);
                                break;
                            case "ellipse":
                                k("cx", T), k("cy", z), k("rx", T), k("ry", z);
                                break;
                            case "line":
                                k("x1", T), k("x2", T), k("y1", z), k("y2", z);
                                break;
                            case "marker":
                                k("refX", T), k("markerWidth", T), k("refY", z), k("markerHeight", z);
                                break;
                            case "radialGradient":
                                k("fx", T), k("fy", z);
                                break;
                            case "tspan":
                                k("dx", T), k("dy", z);
                                break;
                            default:
                                k(i, T)
                        }
                        return E.removeChild(q), A
                    }
                    C.select = function(t) {
                        return t = R(t).replace(/([^\\]):/g, "$1\\:"), M(S.doc.querySelector(t))
                    }, C.selectAll = function(t) {
                        for (var i = S.doc.querySelectorAll(t), a = (C.set || Array)(), E = 0; E < i.length; E++) a.push(M(i[E]));
                        return a
                    };

                    function _(t) {
                        ut(t, "array") || (t = Array.prototype.slice.call(arguments, 0));
                        for (var i = 0, a = 0, E = this.node; this[i];) delete this[i++];
                        for (i = 0; i < t.length; i++) t[i].type == "set" ? t[i].forEach(function(q) {
                            E.appendChild(q.node)
                        }) : E.appendChild(t[i].node);
                        var A = E.childNodes;
                        for (i = 0; i < A.length; i++) this[a++] = M(A[i]);
                        return this
                    }
                    setInterval(function() {
                        for (var t in it)
                            if (it[Y](t)) {
                                var i = it[t],
                                    a = i.node;
                                (i.type != "svg" && !a.ownerSVGElement || i.type == "svg" && (!a.parentNode || "ownerSVGElement" in a.parentNode && !a.ownerSVGElement)) && delete it[t]
                            }
                    }, 1e4);

                    function N(t) {
                        if (t.snap in it) return it[t.snap];
                        var i;
                        try {
                            i = t.ownerSVGElement
                        } catch (A) {}
                        this.node = t, i && (this.paper = new J(i)), this.type = t.tagName || t.nodeName;
                        var a = this.id = ot(this);
                        if (this.anims = {}, this._ = {
                                transform: []
                            }, t.snap = a, it[a] = this, this.type == "g" && (this.add = _), this.type in {
                                g: 1,
                                mask: 1,
                                pattern: 1,
                                symbol: 1
                            })
                            for (var E in J.prototype) J.prototype[Y](E) && (this[E] = J.prototype[E])
                    }
                    N.prototype.attr = function(t, i) {
                        var a = this,
                            E = a.node;
                        if (!t) {
                            if (E.nodeType != 1) return {
                                text: E.nodeValue
                            };
                            for (var A = E.attributes, q = {}, T = 0, z = A.length; T < z; T++) q[A[T].nodeName] = A[T].nodeValue;
                            return q
                        }
                        if (ut(t, "string"))
                            if (arguments.length > 1) {
                                var k = {};
                                k[t] = i, t = k
                            } else return p("snap.util.getattr." + t, a).firstDefined();
                        for (var V in t) t[Y](V) && p("snap.util.attr." + V, a, t[V]);
                        return a
                    }, C.parse = function(t) {
                        var i = S.doc.createDocumentFragment(),
                            a = !0,
                            E = S.doc.createElement("div");
                        if (t = R(t), t.match(/^\s*<\s*svg(?:\s|>)/) || (t = "<svg>" + t + "</svg>", a = !1), E.innerHTML = t, t = E.getElementsByTagName("svg")[0], t)
                            if (a) i = t;
                            else
                                for (; t.firstChild;) i.appendChild(t.firstChild);
                        return new G(i)
                    };

                    function G(t) {
                        this.node = t
                    }
                    C.fragment = function() {
                        for (var t = Array.prototype.slice.call(arguments, 0), i = S.doc.createDocumentFragment(), a = 0, E = t.length; a < E; a++) {
                            var A = t[a];
                            A.node && A.node.nodeType && i.appendChild(A.node), A.nodeType && i.appendChild(A), typeof A == "string" && i.appendChild(C.parse(A).node)
                        }
                        return new G(i)
                    };

                    function X(t, i) {
                        var a = ht(t);
                        i.appendChild(a);
                        var E = M(a);
                        return E
                    }

                    function J(t, i) {
                        var a, E, A, q = J.prototype;
                        if (t && t.tagName && t.tagName.toLowerCase() == "svg") {
                            if (t.snap in it) return it[t.snap];
                            var T = t.ownerDocument;
                            a = new N(t), E = t.getElementsByTagName("desc")[0], A = t.getElementsByTagName("defs")[0], E || (E = ht("desc"), E.appendChild(T.createTextNode("Created with Snap")), a.node.appendChild(E)), A || (A = ht("defs"), a.node.appendChild(A)), a.defs = A;
                            for (var z in q) q[Y](z) && (a[z] = q[z]);
                            a.paper = a.root = a
                        } else a = X("svg", S.doc.body), ht(a.node, {
                            height: i,
                            version: 1.1,
                            width: t,
                            xmlns: ct
                        });
                        return a
                    }

                    function M(t) {
                        return !t || t instanceof N || t instanceof G ? t : t.tagName && t.tagName.toLowerCase() == "svg" ? new J(t) : t.tagName && t.tagName.toLowerCase() == "object" && t.type == "image/svg+xml" ? new J(t.contentDocument.getElementsByTagName("svg")[0]) : new N(t)
                    }
                    C._.make = X, C._.wrap = M, J.prototype.el = function(t, i) {
                        var a = X(t, this.node);
                        return i && a.attr(i), a
                    }, N.prototype.children = function() {
                        for (var t = [], i = this.node.childNodes, a = 0, E = i.length; a < E; a++) t[a] = C(i[a]);
                        return t
                    };

                    function Q(t, i) {
                        for (var a = 0, E = t.length; a < E; a++) {
                            var A = {
                                    type: t[a].type,
                                    attr: t[a].attr()
                                },
                                q = t[a].children();
                            i.push(A), q.length && Q(q, A.childNodes = [])
                        }
                    }
                    N.prototype.toJSON = function() {
                        var t = [];
                        return Q([this], t), t[0]
                    }, p.on("snap.util.getattr", function() {
                        var t = p.nt();
                        t = t.substring(t.lastIndexOf(".") + 1);
                        var i = t.replace(/[A-Z]/g, function(a) {
                            return "-" + a.toLowerCase()
                        });
                        return K[Y](i) ? this.node.ownerDocument.defaultView.getComputedStyle(this.node, null).getPropertyValue(i) : ht(this.node, t)
                    });
                    var K = {
                        "alignment-baseline": 0,
                        "baseline-shift": 0,
                        clip: 0,
                        "clip-path": 0,
                        "clip-rule": 0,
                        color: 0,
                        "color-interpolation": 0,
                        "color-interpolation-filters": 0,
                        "color-profile": 0,
                        "color-rendering": 0,
                        cursor: 0,
                        direction: 0,
                        display: 0,
                        "dominant-baseline": 0,
                        "enable-background": 0,
                        fill: 0,
                        "fill-opacity": 0,
                        "fill-rule": 0,
                        filter: 0,
                        "flood-color": 0,
                        "flood-opacity": 0,
                        font: 0,
                        "font-family": 0,
                        "font-size": 0,
                        "font-size-adjust": 0,
                        "font-stretch": 0,
                        "font-style": 0,
                        "font-variant": 0,
                        "font-weight": 0,
                        "glyph-orientation-horizontal": 0,
                        "glyph-orientation-vertical": 0,
                        "image-rendering": 0,
                        kerning: 0,
                        "letter-spacing": 0,
                        "lighting-color": 0,
                        marker: 0,
                        "marker-end": 0,
                        "marker-mid": 0,
                        "marker-start": 0,
                        mask: 0,
                        opacity: 0,
                        overflow: 0,
                        "pointer-events": 0,
                        "shape-rendering": 0,
                        "stop-color": 0,
                        "stop-opacity": 0,
                        stroke: 0,
                        "stroke-dasharray": 0,
                        "stroke-dashoffset": 0,
                        "stroke-linecap": 0,
                        "stroke-linejoin": 0,
                        "stroke-miterlimit": 0,
                        "stroke-opacity": 0,
                        "stroke-width": 0,
                        "text-anchor": 0,
                        "text-decoration": 0,
                        "text-rendering": 0,
                        "unicode-bidi": 0,
                        visibility: 0,
                        "word-spacing": 0,
                        "writing-mode": 0
                    };
                    p.on("snap.util.attr", function(t) {
                            var i = p.nt(),
                                a = {};
                            i = i.substring(i.lastIndexOf(".") + 1), a[i] = t;
                            var E = i.replace(/-(\w)/gi, function(q, T) {
                                    return T.toUpperCase()
                                }),
                                A = i.replace(/[A-Z]/g, function(q) {
                                    return "-" + q.toLowerCase()
                                });
                            K[Y](A) ? this.node.style[E] = t == null ? d : t : ht(this.node, a)
                        }),
                        function(t) {}(J.prototype), C.ajax = function(t, i, a, E) {
                            var A = new XMLHttpRequest,
                                q = ot();
                            if (A) {
                                if (ut(i, "function")) E = a, a = i, i = null;
                                else if (ut(i, "object")) {
                                    var T = [];
                                    for (var z in i) i.hasOwnProperty(z) && T.push(encodeURIComponent(z) + "=" + encodeURIComponent(i[z]));
                                    i = T.join("&")
                                }
                                return A.open(i ? "POST" : "GET", t, !0), i && (A.setRequestHeader("X-Requested-With", "XMLHttpRequest"), A.setRequestHeader("Content-type", "application/x-www-form-urlencoded")), a && (p.once("snap.ajax." + q + ".0", a), p.once("snap.ajax." + q + ".200", a), p.once("snap.ajax." + q + ".304", a)), A.onreadystatechange = function() {
                                    A.readyState == 4 && p("snap.ajax." + q + "." + A.status, E, A)
                                }, A.readyState == 4 || A.send(i), A
                            }
                        }, C.load = function(t, i, a) {
                            C.ajax(t, function(E) {
                                var A = C.parse(E.responseText);
                                a ? i.call(a, A) : i(A)
                            })
                        };
                    var Z = function(t) {
                        var i = t.getBoundingClientRect(),
                            a = t.ownerDocument,
                            E = a.body,
                            A = a.documentElement,
                            q = A.clientTop || E.clientTop || 0,
                            T = A.clientLeft || E.clientLeft || 0,
                            z = i.top + (g.win.pageYOffset || A.scrollTop || E.scrollTop) - q,
                            k = i.left + (g.win.pageXOffset || A.scrollLeft || E.scrollLeft) - T;
                        return {
                            y: z,
                            x: k
                        }
                    };
                    return C.getElementByPoint = function(t, i) {
                        var a = this,
                            E = a.canvas,
                            A = S.doc.elementFromPoint(t, i);
                        if (S.win.opera && A.tagName == "svg") {
                            var q = Z(A),
                                T = A.createSVGRect();
                            T.x = t - q.x, T.y = i - q.y, T.width = T.height = 1;
                            var z = A.getIntersectionList(T, null);
                            z.length && (A = z[z.length - 1])
                        }
                        return A ? M(A) : null
                    }, C.plugin = function(t) {
                        t(C, N, J, S, G)
                    }, S.win.Snap = C, C
                }($ || this);
            return W.plugin(function(s, C, S, Y, R) {
                var w = C.prototype,
                    L = s.is,
                    O = String,
                    h = s._unit2px,
                    l = s._.$,
                    v = s._.make,
                    c = s._.getSomeDefs,
                    u = "hasOwnProperty",
                    r = s._.wrap;
                w.getBBox = function(e) {
                    if (this.type == "tspan") return s._.box(this.node.getClientRects().item(0));
                    if (!s.Matrix || !s.path) return this.node.getBBox();
                    var o = this,
                        n = new s.Matrix;
                    if (o.removed) return s._.box();
                    for (; o.type == "use";)
                        if (e || (n = n.add(o.transform().localMatrix.translate(o.attr("x") || 0, o.attr("y") || 0))), o.original) o = o.original;
                        else {
                            var f = o.attr("xlink:href");
                            o = o.original = o.node.ownerDocument.getElementById(f.substring(f.indexOf("#") + 1))
                        }
                    var F = o._,
                        I = s.path.get[o.type] || s.path.get.deflt;
                    try {
                        return e ? (F.bboxwt = I ? s.path.getBBox(o.realPath = I(o)) : s._.box(o.node.getBBox()), s._.box(F.bboxwt)) : (o.realPath = I(o), o.matrix = o.transform().localMatrix, F.bbox = s.path.getBBox(s.path.map(o.realPath, n.add(o.matrix))), s._.box(F.bbox))
                    } catch (j) {
                        return s._.box()
                    }
                };
                var d = function() {
                    return this.string
                };

                function x(e, o) {
                    if (o == null) {
                        var n = !0;
                        if (e.type == "linearGradient" || e.type == "radialGradient" ? o = e.node.getAttribute("gradientTransform") : e.type == "pattern" ? o = e.node.getAttribute("patternTransform") : o = e.node.getAttribute("transform"), !o) return new s.Matrix;
                        o = s._.svgTransform2string(o)
                    } else s._.rgTransform.test(o) ? o = O(o).replace(/\.{3}|\u2026/g, e._.transform || "") : o = s._.svgTransform2string(o), L(o, "array") && (o = s.path ? s.path.toString.call(o) : O(o)), e._.transform = o;
                    var f = s._.transform2matrix(o, e.getBBox(1));
                    if (n) return f;
                    e.matrix = f
                }
                w.transform = function(e) {
                    var o = this._;
                    if (e == null) {
                        for (var n = this, f = new s.Matrix(this.node.getCTM()), F = x(this), I = [F], j = new s.Matrix, U, tt = F.toTransformString(), rt = O(F) == O(this.matrix) ? O(o.transform) : tt; n.type != "svg" && (n = n.parent());) I.push(x(n));
                        for (U = I.length; U--;) j.add(I[U]);
                        return {
                            string: rt,
                            globalMatrix: f,
                            totalMatrix: j,
                            localMatrix: F,
                            diffMatrix: f.clone().add(F.invert()),
                            global: f.toTransformString(),
                            total: j.toTransformString(),
                            local: tt,
                            toString: d
                        }
                    }
                    return e instanceof s.Matrix ? (this.matrix = e, this._.transform = e.toTransformString()) : x(this, e), this.node && (this.type == "linearGradient" || this.type == "radialGradient" ? l(this.node, {
                        gradientTransform: this.matrix
                    }) : this.type == "pattern" ? l(this.node, {
                        patternTransform: this.matrix
                    }) : l(this.node, {
                        transform: this.matrix
                    })), this
                }, w.parent = function() {
                    return r(this.node.parentNode)
                }, w.append = w.add = function(e) {
                    if (e) {
                        if (e.type == "set") {
                            var o = this;
                            return e.forEach(function(n) {
                                o.add(n)
                            }), this
                        }
                        e = r(e), this.node.appendChild(e.node), e.paper = this.paper
                    }
                    return this
                }, w.appendTo = function(e) {
                    return e && (e = r(e), e.append(this)), this
                }, w.prepend = function(e) {
                    if (e) {
                        if (e.type == "set") {
                            var o = this,
                                n;
                            return e.forEach(function(F) {
                                n ? n.after(F) : o.prepend(F), n = F
                            }), this
                        }
                        e = r(e);
                        var f = e.parent();
                        this.node.insertBefore(e.node, this.node.firstChild), this.add && this.add(), e.paper = this.paper, this.parent() && this.parent().add(), f && f.add()
                    }
                    return this
                }, w.prependTo = function(e) {
                    return e = r(e), e.prepend(this), this
                }, w.before = function(e) {
                    if (e.type == "set") {
                        var o = this;
                        return e.forEach(function(f) {
                            var F = f.parent();
                            o.node.parentNode.insertBefore(f.node, o.node), F && F.add()
                        }), this.parent().add(), this
                    }
                    e = r(e);
                    var n = e.parent();
                    return this.node.parentNode.insertBefore(e.node, this.node), this.parent() && this.parent().add(), n && n.add(), e.paper = this.paper, this
                }, w.after = function(e) {
                    e = r(e);
                    var o = e.parent();
                    return this.node.nextSibling ? this.node.parentNode.insertBefore(e.node, this.node.nextSibling) : this.node.parentNode.appendChild(e.node), this.parent() && this.parent().add(), o && o.add(), e.paper = this.paper, this
                }, w.insertBefore = function(e) {
                    e = r(e);
                    var o = this.parent();
                    return e.node.parentNode.insertBefore(this.node, e.node), this.paper = e.paper, o && o.add(), e.parent() && e.parent().add(), this
                }, w.insertAfter = function(e) {
                    e = r(e);
                    var o = this.parent();
                    return e.node.parentNode.insertBefore(this.node, e.node.nextSibling), this.paper = e.paper, o && o.add(), e.parent() && e.parent().add(), this
                }, w.remove = function() {
                    var e = this.parent();
                    return this.node.parentNode && this.node.parentNode.removeChild(this.node), delete this.paper, this.removed = !0, e && e.add(), this
                }, w.select = function(e) {
                    return r(this.node.querySelector(e))
                }, w.selectAll = function(e) {
                    for (var o = this.node.querySelectorAll(e), n = (s.set || Array)(), f = 0; f < o.length; f++) n.push(r(o[f]));
                    return n
                }, w.asPX = function(e, o) {
                    return o == null && (o = this.attr(e)), +h(this, e, o)
                }, w.use = function() {
                    var e, o = this.node.id;
                    return o || (o = this.id, l(this.node, {
                        id: o
                    })), this.type == "linearGradient" || this.type == "radialGradient" || this.type == "pattern" ? e = v(this.type, this.node.parentNode) : e = v("use", this.node.parentNode), l(e.node, {
                        "xlink:href": "#" + o
                    }), e.original = this, e
                };

                function P(e) {
                    var o = e.selectAll("*"),
                        n, f = /^\s*url\(("|'|)(.*)\1\)\s*$/,
                        F = [],
                        I = {};

                    function j(vt, ht) {
                        var dt = l(vt.node, ht);
                        if (dt = dt && dt.match(f), dt = dt && dt[2], dt && dt.charAt() == "#") dt = dt.substring(1);
                        else return;
                        dt && (I[dt] = (I[dt] || []).concat(function(ut) {
                            var _t = {};
                            _t[ht] = s.url(ut), l(vt.node, _t)
                        }))
                    }

                    function U(vt) {
                        var ht = l(vt.node, "xlink:href");
                        if (ht && ht.charAt() == "#") ht = ht.substring(1);
                        else return;
                        ht && (I[ht] = (I[ht] || []).concat(function(dt) {
                            vt.attr("xlink:href", "#" + dt)
                        }))
                    }
                    for (var tt = 0, rt = o.length; tt < rt; tt++) {
                        n = o[tt], j(n, "fill"), j(n, "stroke"), j(n, "filter"), j(n, "mask"), j(n, "clip-path"), U(n);
                        var ot = l(n.node, "id");
                        ot && (l(n.node, {
                            id: n.id
                        }), F.push({
                            old: ot,
                            id: n.id
                        }))
                    }
                    for (tt = 0, rt = F.length; tt < rt; tt++) {
                        var at = I[F[tt].old];
                        if (at)
                            for (var ct = 0, it = at.length; ct < it; ct++) at[ct](F[tt].id)
                    }
                }
                w.clone = function() {
                    var e = r(this.node.cloneNode(!0));
                    return l(e.node, "id") && l(e.node, {
                        id: e.id
                    }), P(e), e.insertAfter(this), e
                }, w.toDefs = function() {
                    var e = c(this);
                    return e.appendChild(this.node), this
                }, w.pattern = w.toPattern = function(e, o, n, f) {
                    var F = v("pattern", c(this));
                    return e == null && (e = this.getBBox()), L(e, "object") && "x" in e && (o = e.y, n = e.width, f = e.height, e = e.x), l(F.node, {
                        x: e,
                        y: o,
                        width: n,
                        height: f,
                        patternUnits: "userSpaceOnUse",
                        id: F.id,
                        viewBox: [e, o, n, f].join(" ")
                    }), F.node.appendChild(this.node), F
                }, w.marker = function(e, o, n, f, F, I) {
                    var j = v("marker", c(this));
                    return e == null && (e = this.getBBox()), L(e, "object") && "x" in e && (o = e.y, n = e.width, f = e.height, F = e.refX || e.cx, I = e.refY || e.cy, e = e.x), l(j.node, {
                        viewBox: [e, o, n, f].join(" "),
                        markerWidth: n,
                        markerHeight: f,
                        orient: "auto",
                        refX: F || 0,
                        refY: I || 0,
                        id: j.id
                    }), j.node.appendChild(this.node), j
                };
                var b = {};
                w.data = function(e, o) {
                    var n = b[this.id] = b[this.id] || {};
                    if (arguments.length == 0) return p("snap.data.get." + this.id, this, n, null), n;
                    if (arguments.length == 1) {
                        if (s.is(e, "object")) {
                            for (var f in e) e[u](f) && this.data(f, e[f]);
                            return this
                        }
                        return p("snap.data.get." + this.id, this, n[e], e), n[e]
                    }
                    return n[e] = o, p("snap.data.set." + this.id, this, o, e), this
                }, w.removeData = function(e) {
                    return e == null ? b[this.id] = {} : b[this.id] && delete b[this.id][e], this
                }, w.outerSVG = w.toString = m(1), w.innerSVG = m();

                function m(e) {
                    return function() {
                        var o = e ? "<" + this.type : "",
                            n = this.node.attributes,
                            f = this.node.childNodes;
                        if (e)
                            for (var F = 0, I = n.length; F < I; F++) o += " " + n[F].name + '="' + n[F].value.replace(/"/g, '\\"') + '"';
                        if (f.length) {
                            for (e && (o += ">"), F = 0, I = f.length; F < I; F++) f[F].nodeType == 3 ? o += f[F].nodeValue : f[F].nodeType == 1 && (o += r(f[F]).toString());
                            e && (o += "</" + this.type + ">")
                        } else e && (o += "/>");
                        return o
                    }
                }
                w.toDataURL = function() {
                    if ($ && $.btoa) {
                        var e = this.getBBox(),
                            o = s.format('<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="{width}" height="{height}" viewBox="{x} {y} {width} {height}">{contents}</svg>', {
                                x: +e.x.toFixed(3),
                                y: +e.y.toFixed(3),
                                width: +e.width.toFixed(3),
                                height: +e.height.toFixed(3),
                                contents: this.outerSVG()
                            });
                        return "data:image/svg+xml;base64," + btoa(unescape(encodeURIComponent(o)))
                    }
                }, R.prototype.select = w.select, R.prototype.selectAll = w.selectAll
            }), W.plugin(function(s, C, S, Y, R) {
                var w = C.prototype,
                    L = s.is,
                    O = String,
                    h = "hasOwnProperty";

                function l(c, u, r) {
                    return function(d) {
                        var x = d.slice(c, u);
                        return x.length == 1 && (x = x[0]), r ? r(x) : x
                    }
                }
                var v = function(c, u, r, d) {
                    typeof r == "function" && !r.length && (d = r, r = H.linear), this.attr = c, this.dur = u, r && (this.easing = r), d && (this.callback = d)
                };
                s._.Animation = v, s.animation = function(c, u, r, d) {
                    return new v(c, u, r, d)
                }, w.inAnim = function() {
                    var c = this,
                        u = [];
                    for (var r in c.anims) c.anims[h](r) && function(d) {
                        u.push({
                            anim: new v(d._attrs, d.dur, d.easing, d._callback),
                            mina: d,
                            curStatus: d.status(),
                            status: function(x) {
                                return d.status(x)
                            },
                            stop: function() {
                                d.stop()
                            }
                        })
                    }(c.anims[r]);
                    return u
                }, s.animate = function(c, u, r, d, x, P) {
                    typeof x == "function" && !x.length && (P = x, x = H.linear);
                    var b = H.time(),
                        m = H(c, u, b, b + d, H.time, r, x);
                    return P && p.once("mina.finish." + m.id, P), m
                }, w.stop = function() {
                    for (var c = this.inAnim(), u = 0, r = c.length; u < r; u++) c[u].stop();
                    return this
                }, w.animate = function(c, u, r, d) {
                    typeof r == "function" && !r.length && (d = r, r = H.linear), c instanceof v && (d = c.callback, r = c.easing, u = c.dur, c = c.attr);
                    var x = [],
                        P = [],
                        b = {},
                        m, e, o, n, f = this;
                    for (var F in c)
                        if (c[h](F)) {
                            f.equal ? (n = f.equal(F, O(c[F])), m = n.from, e = n.to, o = n.f) : (m = +f.attr(F), e = +c[F]);
                            var I = L(m, "array") ? m.length : 1;
                            b[F] = l(x.length, x.length + I, o), x = x.concat(m), P = P.concat(e)
                        }
                    var j = H.time(),
                        U = H(x, P, j, j + u, H.time, function(tt) {
                            var rt = {};
                            for (var ot in b) b[h](ot) && (rt[ot] = b[ot](tt));
                            f.attr(rt)
                        }, r);
                    return f.anims[U.id] = U, U._attrs = c, U._callback = d, p("snap.animcreated." + f.id, U), p.once("mina.finish." + U.id, function() {
                        p.off("mina.*." + U.id), delete f.anims[U.id], d && d.call(f)
                    }), p.once("mina.stop." + U.id, function() {
                        p.off("mina.*." + U.id), delete f.anims[U.id]
                    }), f
                }
            }), W.plugin(function(s, C, S, Y, R) {
                var w = Object.prototype.toString,
                    L = String,
                    O = Math,
                    h = "";

                function l(v, c, u, r, d, x) {
                    if (c == null && w.call(v) == "[object SVGMatrix]") {
                        this.a = v.a, this.b = v.b, this.c = v.c, this.d = v.d, this.e = v.e, this.f = v.f;
                        return
                    }
                    v != null ? (this.a = +v, this.b = +c, this.c = +u, this.d = +r, this.e = +d, this.f = +x) : (this.a = 1, this.b = 0, this.c = 0, this.d = 1, this.e = 0, this.f = 0)
                }(function(v) {
                    v.add = function(r, d, x, P, b, m) {
                        if (r && r instanceof l) return this.add(r.a, r.b, r.c, r.d, r.e, r.f);
                        var e = r * this.a + d * this.c,
                            o = r * this.b + d * this.d;
                        return this.e += b * this.a + m * this.c, this.f += b * this.b + m * this.d, this.c = x * this.a + P * this.c, this.d = x * this.b + P * this.d, this.a = e, this.b = o, this
                    }, l.prototype.multLeft = function(r, d, x, P, b, m) {
                        if (r && r instanceof l) return this.multLeft(r.a, r.b, r.c, r.d, r.e, r.f);
                        var e = r * this.a + x * this.b,
                            o = r * this.c + x * this.d,
                            n = r * this.e + x * this.f + b;
                        return this.b = d * this.a + P * this.b, this.d = d * this.c + P * this.d, this.f = d * this.e + P * this.f + m, this.a = e, this.c = o, this.e = n, this
                    }, v.invert = function() {
                        var r = this,
                            d = r.a * r.d - r.b * r.c;
                        return new l(r.d / d, -r.b / d, -r.c / d, r.a / d, (r.c * r.f - r.d * r.e) / d, (r.b * r.e - r.a * r.f) / d)
                    }, v.clone = function() {
                        return new l(this.a, this.b, this.c, this.d, this.e, this.f)
                    }, v.translate = function(r, d) {
                        return this.e += r * this.a + d * this.c, this.f += r * this.b + d * this.d, this
                    }, v.scale = function(r, d, x, P) {
                        return d == null && (d = r), (x || P) && this.translate(x, P), this.a *= r, this.b *= r, this.c *= d, this.d *= d, (x || P) && this.translate(-x, -P), this
                    }, v.rotate = function(r, d, x) {
                        r = s.rad(r), d = d || 0, x = x || 0;
                        var P = +O.cos(r).toFixed(9),
                            b = +O.sin(r).toFixed(9);
                        return this.add(P, b, -b, P, d, x), this.add(1, 0, 0, 1, -d, -x)
                    }, v.skewX = function(r) {
                        return this.skew(r, 0)
                    }, v.skewY = function(r) {
                        return this.skew(0, r)
                    }, v.skew = function(r, d) {
                        r = r || 0, d = d || 0, r = s.rad(r), d = s.rad(d);
                        var x = O.tan(r).toFixed(9),
                            P = O.tan(d).toFixed(9);
                        return this.add(1, P, x, 1, 0, 0)
                    }, v.x = function(r, d) {
                        return r * this.a + d * this.c + this.e
                    }, v.y = function(r, d) {
                        return r * this.b + d * this.d + this.f
                    }, v.get = function(r) {
                        return +this[L.fromCharCode(97 + r)].toFixed(4)
                    }, v.toString = function() {
                        return "matrix(" + [this.get(0), this.get(1), this.get(2), this.get(3), this.get(4), this.get(5)].join() + ")"
                    }, v.offset = function() {
                        return [this.e.toFixed(4), this.f.toFixed(4)]
                    };

                    function c(r) {
                        return r[0] * r[0] + r[1] * r[1]
                    }

                    function u(r) {
                        var d = O.sqrt(c(r));
                        r[0] && (r[0] /= d), r[1] && (r[1] /= d)
                    }
                    v.determinant = function() {
                        return this.a * this.d - this.b * this.c
                    }, v.split = function() {
                        var r = {};
                        r.dx = this.e, r.dy = this.f;
                        var d = [
                            [this.a, this.b],
                            [this.c, this.d]
                        ];
                        r.scalex = O.sqrt(c(d[0])), u(d[0]), r.shear = d[0][0] * d[1][0] + d[0][1] * d[1][1], d[1] = [d[1][0] - d[0][0] * r.shear, d[1][1] - d[0][1] * r.shear], r.scaley = O.sqrt(c(d[1])), u(d[1]), r.shear /= r.scaley, this.determinant() < 0 && (r.scalex = -r.scalex);
                        var x = d[0][1],
                            P = d[1][1];
                        return P < 0 ? (r.rotate = s.deg(O.acos(P)), x < 0 && (r.rotate = 360 - r.rotate)) : r.rotate = s.deg(O.asin(x)), r.isSimple = !+r.shear.toFixed(9) && (r.scalex.toFixed(9) == r.scaley.toFixed(9) || !r.rotate), r.isSuperSimple = !+r.shear.toFixed(9) && r.scalex.toFixed(9) == r.scaley.toFixed(9) && !r.rotate, r.noRotation = !+r.shear.toFixed(9) && !r.rotate, r
                    }, v.toTransformString = function(r) {
                        var d = r || this.split();
                        return +d.shear.toFixed(9) ? "m" + [this.get(0), this.get(1), this.get(2), this.get(3), this.get(4), this.get(5)] : (d.scalex = +d.scalex.toFixed(4), d.scaley = +d.scaley.toFixed(4), d.rotate = +d.rotate.toFixed(4), (d.dx || d.dy ? "t" + [+d.dx.toFixed(4), +d.dy.toFixed(4)] : h) + (d.rotate ? "r" + [+d.rotate.toFixed(4), 0, 0] : h) + (d.scalex != 1 || d.scaley != 1 ? "s" + [d.scalex, d.scaley, 0, 0] : h))
                    }
                })(l.prototype), s.Matrix = l, s.matrix = function(v, c, u, r, d, x) {
                    return new l(v, c, u, r, d, x)
                }
            }), W.plugin(function(s, C, S, Y, R) {
                var w = "hasOwnProperty",
                    L = s._.make,
                    O = s._.wrap,
                    h = s.is,
                    l = s._.getSomeDefs,
                    v = /^url\((['"]?)([^)]+)\1\)$/,
                    c = s._.$,
                    u = s.url,
                    r = String,
                    d = s._.separator,
                    x = "";
                s.deurl = function(n) {
                        var f = String(n).match(v);
                        return f ? f[2] : n
                    }, p.on("snap.util.attr.mask", function(n) {
                        if (n instanceof C || n instanceof R) {
                            if (p.stop(), n instanceof R && n.node.childNodes.length == 1 && (n = n.node.firstChild, l(this).appendChild(n), n = O(n)), n.type == "mask") var f = n;
                            else f = L("mask", l(this)), f.node.appendChild(n.node);
                            !f.node.id && c(f.node, {
                                id: f.id
                            }), c(this.node, {
                                mask: u(f.id)
                            })
                        }
                    }),
                    function(n) {
                        p.on("snap.util.attr.clip", n), p.on("snap.util.attr.clip-path", n), p.on("snap.util.attr.clipPath", n)
                    }(function(n) {
                        if (n instanceof C || n instanceof R) {
                            p.stop();
                            for (var f, F = n.node; F;) {
                                if (F.nodeName === "clipPath") {
                                    f = new C(F);
                                    break
                                }
                                if (F.nodeName === "svg") {
                                    f = void 0;
                                    break
                                }
                                F = F.parentNode
                            }
                            f || (f = L("clipPath", l(this)), f.node.appendChild(n.node), !f.node.id && c(f.node, {
                                id: f.id
                            })), c(this.node, {
                                "clip-path": u(f.node.id || f.id)
                            })
                        }
                    });

                function P(n) {
                    return function(f) {
                        if (p.stop(), f instanceof R && f.node.childNodes.length == 1 && (f.node.firstChild.tagName == "radialGradient" || f.node.firstChild.tagName == "linearGradient" || f.node.firstChild.tagName == "pattern") && (f = f.node.firstChild, l(this).appendChild(f), f = O(f)), f instanceof C)
                            if (f.type == "radialGradient" || f.type == "linearGradient" || f.type == "pattern") {
                                f.node.id || c(f.node, {
                                    id: f.id
                                });
                                var F = u(f.node.id)
                            } else F = f.attr(n);
                        else if (F = s.color(f), F.error) {
                            var I = s(l(this).ownerSVGElement).gradient(f);
                            I ? (I.node.id || c(I.node, {
                                id: I.id
                            }), F = u(I.node.id)) : F = f
                        } else F = r(F);
                        var j = {};
                        j[n] = F, c(this.node, j), this.node.style[n] = x
                    }
                }
                p.on("snap.util.attr.fill", P("fill")), p.on("snap.util.attr.stroke", P("stroke"));
                var b = /^([lr])(?:\(([^)]*)\))?(.*)$/i;
                p.on("snap.util.grad.parse", function(f) {
                    f = r(f);
                    var F = f.match(b);
                    if (!F) return null;
                    var I = F[1],
                        j = F[2],
                        U = F[3];
                    j = j.split(/\s*,\s*/).map(function(it) {
                        return +it == it ? +it : it
                    }), j.length == 1 && j[0] == 0 && (j = []), U = U.split("-"), U = U.map(function(it) {
                        it = it.split(":");
                        var vt = {
                            color: it[0]
                        };
                        return it[1] && (vt.offset = parseFloat(it[1])), vt
                    });
                    var tt = U.length,
                        rt = 0,
                        ot = 0;

                    function at(it, vt) {
                        for (var ht = (vt - rt) / (it - ot), dt = ot; dt < it; dt++) U[dt].offset = +(+rt + ht * (dt - ot)).toFixed(2);
                        ot = it, rt = vt
                    }
                    tt--;
                    for (var ct = 0; ct < tt; ct++) "offset" in U[ct] && at(ct, U[ct].offset);
                    return U[tt].offset = U[tt].offset || 100, at(tt, U[tt].offset), {
                        type: I,
                        params: j,
                        stops: U
                    }
                }), p.on("snap.util.attr.d", function(n) {
                    p.stop(), h(n, "array") && h(n[0], "array") && (n = s.path.toString.call(n)), n = r(n), n.match(/[ruo]/i) && (n = s.path.toAbsolute(n)), c(this.node, {
                        d: n
                    })
                })(-1), p.on("snap.util.attr.#text", function(n) {
                    p.stop(), n = r(n);
                    for (var f = Y.doc.createTextNode(n); this.node.firstChild;) this.node.removeChild(this.node.firstChild);
                    this.node.appendChild(f)
                })(-1), p.on("snap.util.attr.path", function(n) {
                    p.stop(), this.attr({
                        d: n
                    })
                })(-1), p.on("snap.util.attr.class", function(n) {
                    p.stop(), this.node.className.baseVal = n
                })(-1), p.on("snap.util.attr.viewBox", function(n) {
                    var f;
                    h(n, "object") && "x" in n ? f = [n.x, n.y, n.width, n.height].join(" ") : h(n, "array") ? f = n.join(" ") : f = n, c(this.node, {
                        viewBox: f
                    }), p.stop()
                })(-1), p.on("snap.util.attr.transform", function(n) {
                    this.transform(n), p.stop()
                })(-1), p.on("snap.util.attr.r", function(n) {
                    this.type == "rect" && (p.stop(), c(this.node, {
                        rx: n,
                        ry: n
                    }))
                })(-1), p.on("snap.util.attr.textpath", function(n) {
                    if (p.stop(), this.type == "text") {
                        var f, F, I;
                        if (!n && this.textPath) {
                            for (F = this.textPath; F.node.firstChild;) this.node.appendChild(F.node.firstChild);
                            F.remove(), delete this.textPath;
                            return
                        }
                        if (h(n, "string")) {
                            var j = l(this),
                                U = O(j.parentNode).path(n);
                            j.appendChild(U.node), f = U.id, U.attr({
                                id: f
                            })
                        } else n = O(n), n instanceof C && (f = n.attr("id"), f || (f = n.id, n.attr({
                            id: f
                        })));
                        if (f)
                            if (F = this.textPath, I = this.node, F) F.attr({
                                "xlink:href": "#" + f
                            });
                            else {
                                for (F = c("textPath", {
                                        "xlink:href": "#" + f
                                    }); I.firstChild;) F.appendChild(I.firstChild);
                                I.appendChild(F), this.textPath = O(F)
                            }
                    }
                })(-1), p.on("snap.util.attr.text", function(n) {
                    if (this.type == "text") {
                        for (var f = 0, F = this.node, I = function(U) {
                                var tt = c("tspan");
                                if (h(U, "array"))
                                    for (var rt = 0; rt < U.length; rt++) tt.appendChild(I(U[rt]));
                                else tt.appendChild(Y.doc.createTextNode(U));
                                return tt.normalize && tt.normalize(), tt
                            }; F.firstChild;) F.removeChild(F.firstChild);
                        for (var j = I(n); j.firstChild;) F.appendChild(j.firstChild)
                    }
                    p.stop()
                })(-1);

                function m(n) {
                    p.stop(), n == +n && (n += "px"), this.node.style.fontSize = n
                }
                p.on("snap.util.attr.fontSize", m)(-1), p.on("snap.util.attr.font-size", m)(-1), p.on("snap.util.getattr.transform", function() {
                        return p.stop(), this.transform()
                    })(-1), p.on("snap.util.getattr.textpath", function() {
                        return p.stop(), this.textPath
                    })(-1),
                    function() {
                        function n(F) {
                            return function() {
                                p.stop();
                                var I = Y.doc.defaultView.getComputedStyle(this.node, null).getPropertyValue("marker-" + F);
                                return I == "none" ? I : s(Y.doc.getElementById(I.match(v)[1]))
                            }
                        }

                        function f(F) {
                            return function(I) {
                                p.stop();
                                var j = "marker" + F.charAt(0).toUpperCase() + F.substring(1);
                                if (I == "" || !I) {
                                    this.node.style[j] = "none";
                                    return
                                }
                                if (I.type == "marker") {
                                    var U = I.node.id;
                                    U || c(I.node, {
                                        id: I.id
                                    }), this.node.style[j] = u(U);
                                    return
                                }
                            }
                        }
                        p.on("snap.util.getattr.marker-end", n("end"))(-1), p.on("snap.util.getattr.markerEnd", n("end"))(-1), p.on("snap.util.getattr.marker-start", n("start"))(-1), p.on("snap.util.getattr.markerStart", n("start"))(-1), p.on("snap.util.getattr.marker-mid", n("mid"))(-1), p.on("snap.util.getattr.markerMid", n("mid"))(-1), p.on("snap.util.attr.marker-end", f("end"))(-1), p.on("snap.util.attr.markerEnd", f("end"))(-1), p.on("snap.util.attr.marker-start", f("start"))(-1), p.on("snap.util.attr.markerStart", f("start"))(-1), p.on("snap.util.attr.marker-mid", f("mid"))(-1), p.on("snap.util.attr.markerMid", f("mid"))(-1)
                    }(), p.on("snap.util.getattr.r", function() {
                        if (this.type == "rect" && c(this.node, "rx") == c(this.node, "ry")) return p.stop(), c(this.node, "rx")
                    })(-1);

                function e(n) {
                    for (var f = [], F = n.childNodes, I = 0, j = F.length; I < j; I++) {
                        var U = F[I];
                        U.nodeType == 3 && f.push(U.nodeValue), U.tagName == "tspan" && (U.childNodes.length == 1 && U.firstChild.nodeType == 3 ? f.push(U.firstChild.nodeValue) : f.push(e(U)))
                    }
                    return f
                }
                p.on("snap.util.getattr.text", function() {
                    if (this.type == "text" || this.type == "tspan") {
                        p.stop();
                        var n = e(this.node);
                        return n.length == 1 ? n[0] : n
                    }
                })(-1), p.on("snap.util.getattr.#text", function() {
                    return this.node.textContent
                })(-1), p.on("snap.util.getattr.fill", function(n) {
                    if (!n) {
                        p.stop();
                        var f = p("snap.util.getattr.fill", this, !0).firstDefined();
                        return s(s.deurl(f)) || f
                    }
                })(-1), p.on("snap.util.getattr.stroke", function(n) {
                    if (!n) {
                        p.stop();
                        var f = p("snap.util.getattr.stroke", this, !0).firstDefined();
                        return s(s.deurl(f)) || f
                    }
                })(-1), p.on("snap.util.getattr.viewBox", function() {
                    p.stop();
                    var n = c(this.node, "viewBox");
                    if (n) return n = n.split(d), s._.box(+n[0], +n[1], +n[2], +n[3])
                })(-1), p.on("snap.util.getattr.points", function() {
                    var n = c(this.node, "points");
                    if (p.stop(), n) return n.split(d)
                })(-1), p.on("snap.util.getattr.path", function() {
                    var n = c(this.node, "d");
                    return p.stop(), n
                })(-1), p.on("snap.util.getattr.class", function() {
                    return this.node.className.baseVal
                })(-1);

                function o() {
                    return p.stop(), this.node.style.fontSize
                }
                p.on("snap.util.getattr.fontSize", o)(-1), p.on("snap.util.getattr.font-size", o)(-1)
            }), W.plugin(function(s, C, S, Y, R) {
                var w = /\S+/g,
                    L = /[\t\r\n\f]/g,
                    O = /(^\s+|\s+$)/g,
                    h = String,
                    l = C.prototype;
                l.addClass = function(v) {
                    var c = h(v || "").match(w) || [],
                        u = this.node,
                        r = u.className.baseVal,
                        d = r.match(w) || [],
                        x, P, b, m;
                    if (c.length) {
                        for (x = 0; b = c[x++];) P = d.indexOf(b), ~P || d.push(b);
                        m = d.join(" "), r != m && (u.className.baseVal = m)
                    }
                    return this
                }, l.removeClass = function(v) {
                    var c = h(v || "").match(w) || [],
                        u = this.node,
                        r = u.className.baseVal,
                        d = r.match(w) || [],
                        x, P, b, m;
                    if (d.length) {
                        for (x = 0; b = c[x++];) P = d.indexOf(b), ~P && d.splice(P, 1);
                        m = d.join(" "), r != m && (u.className.baseVal = m)
                    }
                    return this
                }, l.hasClass = function(v) {
                    var c = this.node,
                        u = c.className.baseVal,
                        r = u.match(w) || [];
                    return !!~r.indexOf(v)
                }, l.toggleClass = function(v, c) {
                    if (c != null) return c ? this.addClass(v) : this.removeClass(v);
                    var u = (v || "").match(w) || [],
                        r = this.node,
                        d = r.className.baseVal,
                        x = d.match(w) || [],
                        P, b, m, e;
                    for (P = 0; m = u[P++];) b = x.indexOf(m), ~b ? x.splice(b, 1) : x.push(m);
                    return e = x.join(" "), d != e && (r.className.baseVal = e), this
                }
            }), W.plugin(function(s, C, S, Y, R) {
                var w = {
                        "+": function(c, u) {
                            return c + u
                        },
                        "-": function(c, u) {
                            return c - u
                        },
                        "/": function(c, u) {
                            return c / u
                        },
                        "*": function(c, u) {
                            return c * u
                        }
                    },
                    L = String,
                    O = /[a-z]+$/i,
                    h = /^\s*([+\-\/*])\s*=\s*([\d.eE+\-]+)\s*([^\d\s]+)?\s*$/;

                function l(c) {
                    return c
                }

                function v(c) {
                    return function(u) {
                        return +u.toFixed(3) + c
                    }
                }
                p.on("snap.util.attr", function(c) {
                    var u = L(c).match(h);
                    if (u) {
                        var r = p.nt(),
                            d = r.substring(r.lastIndexOf(".") + 1),
                            x = this.attr(d),
                            P = {};
                        p.stop();
                        var b = u[3] || "",
                            m = x.match(O),
                            e = w[u[1]];
                        if (m && m == b ? c = e(parseFloat(x), +u[2]) : (x = this.asPX(d), c = e(this.asPX(d), this.asPX(d, u[2] + b))), isNaN(x) || isNaN(c)) return;
                        P[d] = c, this.attr(P)
                    }
                })(-10), p.on("snap.util.equal", function(c, u) {
                    var r, d, x = L(this.attr(c) || ""),
                        P = this,
                        b = L(u).match(h);
                    if (b) {
                        p.stop();
                        var m = b[3] || "",
                            e = x.match(O),
                            o = w[b[1]];
                        return e && e == m ? {
                            from: parseFloat(x),
                            to: o(parseFloat(x), +b[2]),
                            f: v(e)
                        } : (x = this.asPX(c), {
                            from: x,
                            to: o(x, this.asPX(c, b[2] + m)),
                            f: l
                        })
                    }
                })(-10)
            }), W.plugin(function(s, C, S, Y, R) {
                var w = S.prototype,
                    L = s.is;
                w.rect = function(h, l, v, c, u, r) {
                    var d;
                    return r == null && (r = u), L(h, "object") && h == "[object Object]" ? d = h : h != null && (d = {
                        x: h,
                        y: l,
                        width: v,
                        height: c
                    }, u != null && (d.rx = u, d.ry = r)), this.el("rect", d)
                }, w.circle = function(h, l, v) {
                    var c;
                    return L(h, "object") && h == "[object Object]" ? c = h : h != null && (c = {
                        cx: h,
                        cy: l,
                        r: v
                    }), this.el("circle", c)
                };
                var O = function() {
                    function h() {
                        this.parentNode.removeChild(this)
                    }
                    return function(l, v) {
                        var c = Y.doc.createElement("img"),
                            u = Y.doc.body;
                        c.style.cssText = "position:absolute;left:-9999em;top:-9999em", c.onload = function() {
                            v.call(c), c.onload = c.onerror = null, u.removeChild(c)
                        }, c.onerror = h, u.appendChild(c), c.src = l
                    }
                }();
                w.image = function(h, l, v, c, u) {
                        var r = this.el("image");
                        if (L(h, "object") && "src" in h) r.attr(h);
                        else if (h != null) {
                            var d = {
                                "xlink:href": h,
                                preserveAspectRatio: "none"
                            };
                            l != null && v != null && (d.x = l, d.y = v), c != null && u != null ? (d.width = c, d.height = u) : O(h, function() {
                                s._.$(r.node, {
                                    width: this.offsetWidth,
                                    height: this.offsetHeight
                                })
                            }), s._.$(r.node, d)
                        }
                        return r
                    }, w.ellipse = function(h, l, v, c) {
                        var u;
                        return L(h, "object") && h == "[object Object]" ? u = h : h != null && (u = {
                            cx: h,
                            cy: l,
                            rx: v,
                            ry: c
                        }), this.el("ellipse", u)
                    }, w.path = function(h) {
                        var l;
                        return L(h, "object") && !L(h, "array") ? l = h : h && (l = {
                            d: h
                        }), this.el("path", l)
                    }, w.group = w.g = function(h) {
                        var l, v = this.el("g");
                        return arguments.length == 1 && h && !h.type ? v.attr(h) : arguments.length && v.add(Array.prototype.slice.call(arguments, 0)), v
                    }, w.svg = function(h, l, v, c, u, r, d, x) {
                        var P = {};
                        return L(h, "object") && l == null ? P = h : (h != null && (P.x = h), l != null && (P.y = l), v != null && (P.width = v), c != null && (P.height = c), u != null && r != null && d != null && x != null && (P.viewBox = [u, r, d, x])), this.el("svg", P)
                    }, w.mask = function(h) {
                        var l, v = this.el("mask");
                        return arguments.length == 1 && h && !h.type ? v.attr(h) : arguments.length && v.add(Array.prototype.slice.call(arguments, 0)), v
                    }, w.ptrn = function(h, l, v, c, u, r, d, x) {
                        if (L(h, "object")) var P = h;
                        else P = {
                            patternUnits: "userSpaceOnUse"
                        }, h && (P.x = h), l && (P.y = l), v != null && (P.width = v), c != null && (P.height = c), u != null && r != null && d != null && x != null ? P.viewBox = [u, r, d, x] : P.viewBox = [h || 0, l || 0, v || 0, c || 0];
                        return this.el("pattern", P)
                    }, w.use = function(h) {
                        return h != null ? (h instanceof C && (h.attr("id") || h.attr({
                            id: s._.id(h)
                        }), h = h.attr("id")), String(h).charAt() == "#" && (h = h.substring(1)), this.el("use", {
                            "xlink:href": "#" + h
                        })) : C.prototype.use.call(this)
                    }, w.symbol = function(h, l, v, c) {
                        var u = {};
                        return h != null && l != null && v != null && c != null && (u.viewBox = [h, l, v, c]), this.el("symbol", u)
                    }, w.text = function(h, l, v) {
                        var c = {};
                        return L(h, "object") ? c = h : h != null && (c = {
                            x: h,
                            y: l,
                            text: v || ""
                        }), this.el("text", c)
                    }, w.line = function(h, l, v, c) {
                        var u = {};
                        return L(h, "object") ? u = h : h != null && (u = {
                            x1: h,
                            x2: v,
                            y1: l,
                            y2: c
                        }), this.el("line", u)
                    }, w.polyline = function(h) {
                        arguments.length > 1 && (h = Array.prototype.slice.call(arguments, 0));
                        var l = {};
                        return L(h, "object") && !L(h, "array") ? l = h : h != null && (l = {
                            points: h
                        }), this.el("polyline", l)
                    }, w.polygon = function(h) {
                        arguments.length > 1 && (h = Array.prototype.slice.call(arguments, 0));
                        var l = {};
                        return L(h, "object") && !L(h, "array") ? l = h : h != null && (l = {
                            points: h
                        }), this.el("polygon", l)
                    },
                    function() {
                        var h = s._.$;

                        function l() {
                            return this.selectAll("stop")
                        }

                        function v(P, b) {
                            var m = h("stop"),
                                e = {
                                    offset: +b + "%"
                                };
                            P = s.color(P), e["stop-color"] = P.hex, P.opacity < 1 && (e["stop-opacity"] = P.opacity), h(m, e);
                            for (var o = this.stops(), n, f = 0; f < o.length; f++) {
                                var F = parseFloat(o[f].attr("offset"));
                                if (F > b) {
                                    this.node.insertBefore(m, o[f].node), n = !0;
                                    break
                                }
                            }
                            return n || this.node.appendChild(m), this
                        }

                        function c() {
                            if (this.type == "linearGradient") {
                                var P = h(this.node, "x1") || 0,
                                    b = h(this.node, "x2") || 1,
                                    m = h(this.node, "y1") || 0,
                                    e = h(this.node, "y2") || 0;
                                return s._.box(P, m, math.abs(b - P), math.abs(e - m))
                            } else {
                                var o = this.node.cx || .5,
                                    n = this.node.cy || .5,
                                    f = this.node.r || 0;
                                return s._.box(o - f, n - f, f * 2, f * 2)
                            }
                        }

                        function u(P) {
                            var b = P,
                                m = this.stops();
                            if (typeof P == "string" && (b = p("snap.util.grad.parse", null, "l(0,0,0,1)" + P).firstDefined().stops), !!s.is(b, "array")) {
                                for (var e = 0; e < m.length; e++)
                                    if (b[e]) {
                                        var o = s.color(b[e].color),
                                            n = {
                                                offset: b[e].offset + "%"
                                            };
                                        n["stop-color"] = o.hex, o.opacity < 1 && (n["stop-opacity"] = o.opacity), m[e].attr(n)
                                    } else m[e].remove();
                                for (e = m.length; e < b.length; e++) this.addStop(b[e].color, b[e].offset);
                                return this
                            }
                        }

                        function r(P, b) {
                            var m = p("snap.util.grad.parse", null, b).firstDefined(),
                                e;
                            if (!m) return null;
                            m.params.unshift(P), m.type.toLowerCase() == "l" ? e = d.apply(0, m.params) : e = x.apply(0, m.params), m.type != m.type.toLowerCase() && h(e.node, {
                                gradientUnits: "userSpaceOnUse"
                            });
                            for (var o = m.stops, n = o.length, f = 0; f < n; f++) {
                                var F = o[f];
                                e.addStop(F.color, F.offset)
                            }
                            return e
                        }

                        function d(P, b, m, e, o) {
                            var n = s._.make("linearGradient", P);
                            return n.stops = l, n.addStop = v, n.getBBox = c, n.setStops = u, b != null && h(n.node, {
                                x1: b,
                                y1: m,
                                x2: e,
                                y2: o
                            }), n
                        }

                        function x(P, b, m, e, o, n) {
                            var f = s._.make("radialGradient", P);
                            return f.stops = l, f.addStop = v, f.getBBox = c, b != null && h(f.node, {
                                cx: b,
                                cy: m,
                                r: e
                            }), o != null && n != null && h(f.node, {
                                fx: o,
                                fy: n
                            }), f
                        }
                        w.gradient = function(P) {
                            return r(this.defs, P)
                        }, w.gradientLinear = function(P, b, m, e) {
                            return d(this.defs, P, b, m, e)
                        }, w.gradientRadial = function(P, b, m, e, o) {
                            return x(this.defs, P, b, m, e, o)
                        }, w.toString = function() {
                            var P = this.node.ownerDocument,
                                b = P.createDocumentFragment(),
                                m = P.createElement("div"),
                                e = this.node.cloneNode(!0),
                                o;
                            return b.appendChild(m), m.appendChild(e), s._.$(e, {
                                xmlns: "http://www.w3.org/2000/svg"
                            }), o = m.innerHTML, b.removeChild(b.firstChild), o
                        }, w.toDataURL = function() {
                            if ($ && $.btoa) return "data:image/svg+xml;base64," + btoa(unescape(encodeURIComponent(this)))
                        }, w.clear = function() {
                            for (var P = this.node.firstChild, b; P;) b = P.nextSibling, P.tagName != "defs" ? P.parentNode.removeChild(P) : w.clear.call({
                                node: P
                            }), P = b
                        }
                    }()
            }), W.plugin(function(s, C, S, Y) {
                var R = C.prototype,
                    w = s.is,
                    L = s._.clone,
                    O = "hasOwnProperty",
                    h = /,?([a-z]),?/gi,
                    l = parseFloat,
                    v = Math,
                    c = v.PI,
                    u = v.min,
                    r = v.max,
                    d = v.pow,
                    x = v.abs;

                function P(y) {
                    var B = P.ps = P.ps || {};
                    return B[y] ? B[y].sleep = 100 : B[y] = {
                        sleep: 100
                    }, setTimeout(function() {
                        for (var D in B) B[O](D) && D != y && (B[D].sleep--, !B[D].sleep && delete B[D])
                    }), B[y]
                }

                function b(y, B, D, _) {
                    return y == null && (y = B = D = _ = 0), B == null && (B = y.y, D = y.width, _ = y.height, y = y.x), {
                        x: y,
                        y: B,
                        width: D,
                        w: D,
                        height: _,
                        h: _,
                        x2: y + D,
                        y2: B + _,
                        cx: y + D / 2,
                        cy: B + _ / 2,
                        r1: v.min(D, _) / 2,
                        r2: v.max(D, _) / 2,
                        r0: v.sqrt(D * D + _ * _) / 2,
                        path: Et(y, B, D, _),
                        vb: [y, B, D, _].join(" ")
                    }
                }

                function m() {
                    return this.join(",").replace(h, "$1")
                }

                function e(y) {
                    var B = L(y);
                    return B.toString = m, B
                }

                function o(y, B, D, _, N, G, X, J, M) {
                    return M == null ? at(y, B, D, _, N, G, X, J) : j(y, B, D, _, N, G, X, J, ct(y, B, D, _, N, G, X, J, M))
                }

                function n(y, B) {
                    function D(_) {
                        return +(+_).toFixed(3)
                    }
                    return s._.cacher(function(_, N, G) {
                        _ instanceof C && (_ = _.attr("d")), _ = wt(_);
                        for (var X, J, M, Q, K = "", Z = {}, t, i = 0, a = 0, E = _.length; a < E; a++) {
                            if (M = _[a], M[0] == "M") X = +M[1], J = +M[2];
                            else {
                                if (Q = o(X, J, M[1], M[2], M[3], M[4], M[5], M[6]), i + Q > N) {
                                    if (B && !Z.start) {
                                        if (t = o(X, J, M[1], M[2], M[3], M[4], M[5], M[6], N - i), K += ["C" + D(t.start.x), D(t.start.y), D(t.m.x), D(t.m.y), D(t.x), D(t.y)], G) return K;
                                        Z.start = K, K = ["M" + D(t.x), D(t.y) + "C" + D(t.n.x), D(t.n.y), D(t.end.x), D(t.end.y), D(M[5]), D(M[6])].join(), i += Q, X = +M[5], J = +M[6];
                                        continue
                                    }
                                    if (!y && !B) return t = o(X, J, M[1], M[2], M[3], M[4], M[5], M[6], N - i), t
                                }
                                i += Q, X = +M[5], J = +M[6]
                            }
                            K += M.shift() + M
                        }
                        return Z.end = K, t = y ? i : B ? Z : j(X, J, M[0], M[1], M[2], M[3], M[4], M[5], 1), t
                    }, null, s._.clone)
                }
                var f = n(1),
                    F = n(),
                    I = n(0, 1);

                function j(y, B, D, _, N, G, X, J, M) {
                    var Q = 1 - M,
                        K = d(Q, 3),
                        Z = d(Q, 2),
                        t = M * M,
                        i = t * M,
                        a = K * y + Z * 3 * M * D + Q * 3 * M * M * N + i * X,
                        E = K * B + Z * 3 * M * _ + Q * 3 * M * M * G + i * J,
                        A = y + 2 * M * (D - y) + t * (N - 2 * D + y),
                        q = B + 2 * M * (_ - B) + t * (G - 2 * _ + B),
                        T = D + 2 * M * (N - D) + t * (X - 2 * N + D),
                        z = _ + 2 * M * (G - _) + t * (J - 2 * G + _),
                        k = Q * y + M * D,
                        V = Q * B + M * _,
                        et = Q * N + M * X,
                        nt = Q * G + M * J,
                        lt = 90 - v.atan2(A - T, q - z) * 180 / c;
                    return {
                        x: a,
                        y: E,
                        m: {
                            x: A,
                            y: q
                        },
                        n: {
                            x: T,
                            y: z
                        },
                        start: {
                            x: k,
                            y: V
                        },
                        end: {
                            x: et,
                            y: nt
                        },
                        alpha: lt
                    }
                }

                function U(y, B, D, _, N, G, X, J) {
                    s.is(y, "array") || (y = [y, B, D, _, N, G, X, J]);
                    var M = Gt.apply(null, y);
                    return b(M.min.x, M.min.y, M.max.x - M.min.x, M.max.y - M.min.y)
                }

                function tt(y, B, D) {
                    return B >= y.x && B <= y.x + y.width && D >= y.y && D <= y.y + y.height
                }

                function rt(y, B) {
                    return y = b(y), B = b(B), tt(B, y.x, y.y) || tt(B, y.x2, y.y) || tt(B, y.x, y.y2) || tt(B, y.x2, y.y2) || tt(y, B.x, B.y) || tt(y, B.x2, B.y) || tt(y, B.x, B.y2) || tt(y, B.x2, B.y2) || (y.x < B.x2 && y.x > B.x || B.x < y.x2 && B.x > y.x) && (y.y < B.y2 && y.y > B.y || B.y < y.y2 && B.y > y.y)
                }

                function ot(y, B, D, _, N) {
                    var G = -3 * B + 9 * D - 9 * _ + 3 * N,
                        X = y * G + 6 * B - 12 * D + 6 * _;
                    return y * X - 3 * B + 3 * D
                }

                function at(y, B, D, _, N, G, X, J, M) {
                    M == null && (M = 1), M = M > 1 ? 1 : M < 0 ? 0 : M;
                    for (var Q = M / 2, K = 12, Z = [-.1252, .1252, -.3678, .3678, -.5873, .5873, -.7699, .7699, -.9041, .9041, -.9816, .9816], t = [.2491, .2491, .2335, .2335, .2032, .2032, .1601, .1601, .1069, .1069, .0472, .0472], i = 0, a = 0; a < K; a++) {
                        var E = Q * Z[a] + Q,
                            A = ot(E, y, D, N, X),
                            q = ot(E, B, _, G, J),
                            T = A * A + q * q;
                        i += t[a] * v.sqrt(T)
                    }
                    return Q * i
                }

                function ct(y, B, D, _, N, G, X, J, M) {
                    if (!(M < 0 || at(y, B, D, _, N, G, X, J) < M)) {
                        var Q = 1,
                            K = Q / 2,
                            Z = Q - K,
                            t, i = .01;
                        for (t = at(y, B, D, _, N, G, X, J, Z); x(t - M) > i;) K /= 2, Z += (t < M ? 1 : -1) * K, t = at(y, B, D, _, N, G, X, J, Z);
                        return Z
                    }
                }

                function it(y, B, D, _, N, G, X, J) {
                    if (!(r(y, D) < u(N, X) || u(y, D) > r(N, X) || r(B, _) < u(G, J) || u(B, _) > r(G, J))) {
                        var M = (y * _ - B * D) * (N - X) - (y - D) * (N * J - G * X),
                            Q = (y * _ - B * D) * (G - J) - (B - _) * (N * J - G * X),
                            K = (y - D) * (G - J) - (B - _) * (N - X);
                        if (K) {
                            var Z = M / K,
                                t = Q / K,
                                i = +Z.toFixed(2),
                                a = +t.toFixed(2);
                            if (!(i < +u(y, D).toFixed(2) || i > +r(y, D).toFixed(2) || i < +u(N, X).toFixed(2) || i > +r(N, X).toFixed(2) || a < +u(B, _).toFixed(2) || a > +r(B, _).toFixed(2) || a < +u(G, J).toFixed(2) || a > +r(G, J).toFixed(2))) return {
                                x: Z,
                                y: t
                            }
                        }
                    }
                }

                function vt(y, B) {
                    return dt(y, B)
                }

                function ht(y, B) {
                    return dt(y, B, 1)
                }

                function dt(y, B, D) {
                    var _ = U(y),
                        N = U(B);
                    if (!rt(_, N)) return D ? 0 : [];
                    for (var G = at.apply(0, y), X = at.apply(0, B), J = ~~(G / 8), M = ~~(X / 8), Q = [], K = [], Z = {}, t = D ? 0 : [], i = 0; i < J + 1; i++) {
                        var a = j.apply(0, y.concat(i / J));
                        Q.push({
                            x: a.x,
                            y: a.y,
                            t: i / J
                        })
                    }
                    for (i = 0; i < M + 1; i++) a = j.apply(0, B.concat(i / M)), K.push({
                        x: a.x,
                        y: a.y,
                        t: i / M
                    });
                    for (i = 0; i < J; i++)
                        for (var E = 0; E < M; E++) {
                            var A = Q[i],
                                q = Q[i + 1],
                                T = K[E],
                                z = K[E + 1],
                                k = x(q.x - A.x) < .001 ? "y" : "x",
                                V = x(z.x - T.x) < .001 ? "y" : "x",
                                et = it(A.x, A.y, q.x, q.y, T.x, T.y, z.x, z.y);
                            if (et) {
                                if (Z[et.x.toFixed(4)] == et.y.toFixed(4)) continue;
                                Z[et.x.toFixed(4)] = et.y.toFixed(4);
                                var nt = A.t + x((et[k] - A[k]) / (q[k] - A[k])) * (q.t - A.t),
                                    lt = T.t + x((et[V] - T[V]) / (z[V] - T[V])) * (z.t - T.t);
                                nt >= 0 && nt <= 1 && lt >= 0 && lt <= 1 && (D ? t++ : t.push({
                                    x: et.x,
                                    y: et.y,
                                    t1: nt,
                                    t2: lt
                                }))
                            }
                        }
                    return t
                }

                function ut(y, B) {
                    return Lt(y, B)
                }

                function _t(y, B) {
                    return Lt(y, B, 1)
                }

                function Lt(y, B, D) {
                    y = wt(y), B = wt(B);
                    for (var _, N, G, X, J, M, Q, K, Z, t, i = D ? 0 : [], a = 0, E = y.length; a < E; a++) {
                        var A = y[a];
                        if (A[0] == "M") _ = J = A[1], N = M = A[2];
                        else {
                            A[0] == "C" ? (Z = [_, N].concat(A.slice(1)), _ = Z[6], N = Z[7]) : (Z = [_, N, _, N, J, M, J, M], _ = J, N = M);
                            for (var q = 0, T = B.length; q < T; q++) {
                                var z = B[q];
                                if (z[0] == "M") G = Q = z[1], X = K = z[2];
                                else {
                                    z[0] == "C" ? (t = [G, X].concat(z.slice(1)), G = t[6], X = t[7]) : (t = [G, X, G, X, Q, K, Q, K], G = Q, X = K);
                                    var k = dt(Z, t, D);
                                    if (D) i += k;
                                    else {
                                        for (var V = 0, et = k.length; V < et; V++) k[V].segment1 = a, k[V].segment2 = q, k[V].bez1 = Z, k[V].bez2 = t;
                                        i = i.concat(k)
                                    }
                                }
                            }
                        }
                    }
                    return i
                }

                function Ft(y, B, D) {
                    var _ = At(y);
                    return tt(_, B, D) && Lt(y, [
                        ["M", B, D],
                        ["H", _.x2 + 10]
                    ], 1) % 2 == 1
                }

                function At(y) {
                    var B = P(y);
                    if (B.bbox) return L(B.bbox);
                    if (!y) return b();
                    y = wt(y);
                    for (var D = 0, _ = 0, N = [], G = [], X, J = 0, M = y.length; J < M; J++)
                        if (X = y[J], X[0] == "M") D = X[1], _ = X[2], N.push(D), G.push(_);
                        else {
                            var Q = Gt(D, _, X[1], X[2], X[3], X[4], X[5], X[6]);
                            N = N.concat(Q.min.x, Q.max.x), G = G.concat(Q.min.y, Q.max.y), D = X[5], _ = X[6]
                        }
                    var K = u.apply(0, N),
                        Z = u.apply(0, G),
                        t = r.apply(0, N),
                        i = r.apply(0, G),
                        a = b(K, Z, t - K, i - Z);
                    return B.bbox = L(a), a
                }

                function Et(y, B, D, _, N) {
                    if (N) return [
                        ["M", +y + +N, B],
                        ["l", D - N * 2, 0],
                        ["a", N, N, 0, 0, 1, N, N],
                        ["l", 0, _ - N * 2],
                        ["a", N, N, 0, 0, 1, -N, N],
                        ["l", N * 2 - D, 0],
                        ["a", N, N, 0, 0, 1, -N, -N],
                        ["l", 0, N * 2 - _],
                        ["a", N, N, 0, 0, 1, N, -N],
                        ["z"]
                    ];
                    var G = [
                        ["M", y, B],
                        ["l", D, 0],
                        ["l", 0, _],
                        ["l", -D, 0],
                        ["z"]
                    ];
                    return G.toString = m, G
                }

                function bt(y, B, D, _, N) {
                    if (N == null && _ == null && (_ = D), y = +y, B = +B, D = +D, _ = +_, N != null) var G = Math.PI / 180,
                        X = y + D * Math.cos(-_ * G),
                        J = y + D * Math.cos(-N * G),
                        M = B + D * Math.sin(-_ * G),
                        Q = B + D * Math.sin(-N * G),
                        K = [
                            ["M", X, M],
                            ["A", D, D, 0, +(N - _ > 180), 0, J, Q]
                        ];
                    else K = [
                        ["M", y, B],
                        ["m", 0, -_],
                        ["a", D, _, 0, 1, 1, 0, 2 * _],
                        ["a", D, _, 0, 1, 1, 0, -2 * _],
                        ["z"]
                    ];
                    return K.toString = m, K
                }
                var kt = s._unit2px,
                    ce = {
                        path: function(y) {
                            return y.attr("path")
                        },
                        circle: function(y) {
                            var B = kt(y);
                            return bt(B.cx, B.cy, B.r)
                        },
                        ellipse: function(y) {
                            var B = kt(y);
                            return bt(B.cx || 0, B.cy || 0, B.rx, B.ry)
                        },
                        rect: function(y) {
                            var B = kt(y);
                            return Et(B.x || 0, B.y || 0, B.width, B.height, B.rx, B.ry)
                        },
                        image: function(y) {
                            var B = kt(y);
                            return Et(B.x || 0, B.y || 0, B.width, B.height)
                        },
                        line: function(y) {
                            return "M" + [y.attr("x1") || 0, y.attr("y1") || 0, y.attr("x2"), y.attr("y2")]
                        },
                        polyline: function(y) {
                            return "M" + y.attr("points")
                        },
                        polygon: function(y) {
                            return "M" + y.attr("points") + "z"
                        },
                        deflt: function(y) {
                            var B = y.node.getBBox();
                            return Et(B.x, B.y, B.width, B.height)
                        }
                    };

                function Mt(y) {
                    var B = P(y),
                        D = String.prototype.toLowerCase;
                    if (B.rel) return e(B.rel);
                    (!s.is(y, "array") || !s.is(y && y[0], "array")) && (y = s.parsePathString(y));
                    var _ = [],
                        N = 0,
                        G = 0,
                        X = 0,
                        J = 0,
                        M = 0;
                    y[0][0] == "M" && (N = y[0][1], G = y[0][2], X = N, J = G, M++, _.push(["M", N, G]));
                    for (var Q = M, K = y.length; Q < K; Q++) {
                        var Z = _[Q] = [],
                            t = y[Q];
                        if (t[0] != D.call(t[0])) switch (Z[0] = D.call(t[0]), Z[0]) {
                            case "a":
                                Z[1] = t[1], Z[2] = t[2], Z[3] = t[3], Z[4] = t[4], Z[5] = t[5], Z[6] = +(t[6] - N).toFixed(3), Z[7] = +(t[7] - G).toFixed(3);
                                break;
                            case "v":
                                Z[1] = +(t[1] - G).toFixed(3);
                                break;
                            case "m":
                                X = t[1], J = t[2];
                            default:
                                for (var i = 1, a = t.length; i < a; i++) Z[i] = +(t[i] - (i % 2 ? N : G)).toFixed(3)
                        } else {
                            Z = _[Q] = [], t[0] == "m" && (X = t[1] + N, J = t[2] + G);
                            for (var E = 0, A = t.length; E < A; E++) _[Q][E] = t[E]
                        }
                        var q = _[Q].length;
                        switch (_[Q][0]) {
                            case "z":
                                N = X, G = J;
                                break;
                            case "h":
                                N += +_[Q][q - 1];
                                break;
                            case "v":
                                G += +_[Q][q - 1];
                                break;
                            default:
                                N += +_[Q][q - 2], G += +_[Q][q - 1]
                        }
                    }
                    return _.toString = m, B.rel = e(_), _
                }

                function Rt(y) {
                    var B = P(y);
                    if (B.abs) return e(B.abs);
                    if ((!w(y, "array") || !w(y && y[0], "array")) && (y = s.parsePathString(y)), !y || !y.length) return [
                        ["M", 0, 0]
                    ];
                    var D = [],
                        _ = 0,
                        N = 0,
                        G = 0,
                        X = 0,
                        J = 0,
                        M;
                    y[0][0] == "M" && (_ = +y[0][1], N = +y[0][2], G = _, X = N, J++, D[0] = ["M", _, N]);
                    for (var Q = y.length == 3 && y[0][0] == "M" && y[1][0].toUpperCase() == "R" && y[2][0].toUpperCase() == "Z", K, Z, t = J, i = y.length; t < i; t++) {
                        if (D.push(K = []), Z = y[t], M = Z[0], M != M.toUpperCase()) switch (K[0] = M.toUpperCase(), K[0]) {
                                case "A":
                                    K[1] = Z[1], K[2] = Z[2], K[3] = Z[3], K[4] = Z[4], K[5] = Z[5], K[6] = +Z[6] + _, K[7] = +Z[7] + N;
                                    break;
                                case "V":
                                    K[1] = +Z[1] + N;
                                    break;
                                case "H":
                                    K[1] = +Z[1] + _;
                                    break;
                                case "R":
                                    for (var a = [_, N].concat(Z.slice(1)), E = 2, A = a.length; E < A; E++) a[E] = +a[E] + _, a[++E] = +a[E] + N;
                                    D.pop(), D = D.concat(re(a, Q));
                                    break;
                                case "O":
                                    D.pop(), a = bt(_, N, Z[1], Z[2]), a.push(a[0]), D = D.concat(a);
                                    break;
                                case "U":
                                    D.pop(), D = D.concat(bt(_, N, Z[1], Z[2], Z[3])), K = ["U"].concat(D[D.length - 1].slice(-2));
                                    break;
                                case "M":
                                    G = +Z[1] + _, X = +Z[2] + N;
                                default:
                                    for (E = 1, A = Z.length; E < A; E++) K[E] = +Z[E] + (E % 2 ? _ : N)
                            } else if (M == "R") a = [_, N].concat(Z.slice(1)), D.pop(), D = D.concat(re(a, Q)), K = ["R"].concat(Z.slice(-2));
                            else if (M == "O") D.pop(), a = bt(_, N, Z[1], Z[2]), a.push(a[0]), D = D.concat(a);
                        else if (M == "U") D.pop(), D = D.concat(bt(_, N, Z[1], Z[2], Z[3])), K = ["U"].concat(D[D.length - 1].slice(-2));
                        else
                            for (var q = 0, T = Z.length; q < T; q++) K[q] = Z[q];
                        if (M = M.toUpperCase(), M != "O") switch (K[0]) {
                            case "Z":
                                _ = +G, N = +X;
                                break;
                            case "H":
                                _ = K[1];
                                break;
                            case "V":
                                N = K[1];
                                break;
                            case "M":
                                G = K[K.length - 2], X = K[K.length - 1];
                            default:
                                _ = K[K.length - 2], N = K[K.length - 1]
                        }
                    }
                    return D.toString = m, B.abs = e(D), D
                }

                function Tt(y, B, D, _) {
                    return [y, B, D, _, D, _]
                }

                function yt(y, B, D, _, N, G) {
                    var X = .3333333333333333,
                        J = 2 / 3;
                    return [X * y + J * D, X * B + J * _, X * N + J * D, X * G + J * _, N, G]
                }

                function Ot(y, B, D, _, N, G, X, J, M, Q) {
                    var K = c * 120 / 180,
                        Z = c / 180 * (+N || 0),
                        t = [],
                        i, a = s._.cacher(function(xe, Fe, St) {
                            var $e = xe * v.cos(St) - Fe * v.sin(St),
                                Xe = xe * v.sin(St) + Fe * v.cos(St);
                            return {
                                x: $e,
                                y: Xe
                            }
                        });
                    if (!D || !_) return [y, B, J, M, J, M];
                    if (Q) ft = Q[0], st = Q[1], nt = Q[2], lt = Q[3];
                    else {
                        i = a(y, B, -Z), y = i.x, B = i.y, i = a(J, M, -Z), J = i.x, M = i.y;
                        var E = v.cos(c / 180 * N),
                            A = v.sin(c / 180 * N),
                            q = (y - J) / 2,
                            T = (B - M) / 2,
                            z = q * q / (D * D) + T * T / (_ * _);
                        z > 1 && (z = v.sqrt(z), D = z * D, _ = z * _);
                        var k = D * D,
                            V = _ * _,
                            et = (G == X ? -1 : 1) * v.sqrt(x((k * V - k * T * T - V * q * q) / (k * T * T + V * q * q))),
                            nt = et * D * T / _ + (y + J) / 2,
                            lt = et * -_ * q / D + (B + M) / 2,
                            ft = v.asin(((B - lt) / _).toFixed(9)),
                            st = v.asin(((M - lt) / _).toFixed(9));
                        ft = y < nt ? c - ft : ft, st = J < nt ? c - st : st, ft < 0 && (ft = c * 2 + ft), st < 0 && (st = c * 2 + st), X && ft > st && (ft = ft - c * 2), !X && st > ft && (st = st - c * 2)
                    }
                    var pt = st - ft;
                    if (x(pt) > K) {
                        var zt = st,
                            Vt = J,
                            Yt = M;
                        st = ft + K * (X && st > ft ? 1 : -1), J = nt + D * v.cos(st), M = lt + _ * v.sin(st), t = Ot(J, M, D, _, N, 0, X, Vt, Yt, [st, zt, nt, lt])
                    }
                    pt = st - ft;
                    var $t = v.cos(ft),
                        Xt = v.sin(ft),
                        Ht = v.cos(st),
                        Ve = v.sin(st),
                        he = v.tan(pt / 4),
                        de = 4 / 3 * D * he,
                        ge = 4 / 3 * _ * he,
                        pe = [y, B],
                        Pt = [y + de * Xt, B - ge * $t],
                        me = [J + de * Ve, M - ge * Ht],
                        ve = [J, M];
                    if (Pt[0] = 2 * pe[0] - Pt[0], Pt[1] = 2 * pe[1] - Pt[1], Q) return [Pt, me, ve].concat(t);
                    t = [Pt, me, ve].concat(t).join().split(",");
                    for (var ye = [], Ct = 0, Ye = t.length; Ct < Ye; Ct++) ye[Ct] = Ct % 2 ? a(t[Ct - 1], t[Ct], Z).y : a(t[Ct], t[Ct + 1], Z).x;
                    return ye
                }

                function ee(y, B, D, _, N, G, X, J, M) {
                    var Q = 1 - M;
                    return {
                        x: d(Q, 3) * y + d(Q, 2) * 3 * M * D + Q * 3 * M * M * N + d(M, 3) * X,
                        y: d(Q, 3) * B + d(Q, 2) * 3 * M * _ + Q * 3 * M * M * G + d(M, 3) * J
                    }
                }

                function Gt(y, B, D, _, N, G, X, J) {
                    for (var M = [], Q = [
                            [],
                            []
                        ], K, Z, t, i, a, E, A, q, T = 0; T < 2; ++T) {
                        if (T == 0 ? (Z = 6 * y - 12 * D + 6 * N, K = -3 * y + 9 * D - 9 * N + 3 * X, t = 3 * D - 3 * y) : (Z = 6 * B - 12 * _ + 6 * G, K = -3 * B + 9 * _ - 9 * G + 3 * J, t = 3 * _ - 3 * B), x(K) < 1e-12) {
                            if (x(Z) < 1e-12) continue;
                            i = -t / Z, 0 < i && i < 1 && M.push(i);
                            continue
                        }
                        A = Z * Z - 4 * t * K, q = v.sqrt(A), !(A < 0) && (a = (-Z + q) / (2 * K), 0 < a && a < 1 && M.push(a), E = (-Z - q) / (2 * K), 0 < E && E < 1 && M.push(E))
                    }
                    for (var z, k, V = M.length, et = V, nt; V--;) i = M[V], nt = 1 - i, Q[0][V] = nt * nt * nt * y + 3 * nt * nt * i * D + 3 * nt * i * i * N + i * i * i * X, Q[1][V] = nt * nt * nt * B + 3 * nt * nt * i * _ + 3 * nt * i * i * G + i * i * i * J;
                    return Q[0][et] = y, Q[1][et] = B, Q[0][et + 1] = X, Q[1][et + 1] = J, Q[0].length = Q[1].length = et + 2, {
                        min: {
                            x: u.apply(0, Q[0]),
                            y: u.apply(0, Q[1])
                        },
                        max: {
                            x: r.apply(0, Q[0]),
                            y: r.apply(0, Q[1])
                        }
                    }
                }

                function wt(y, B) {
                    var D = !B && P(y);
                    if (!B && D.curve) return e(D.curve);
                    for (var _ = Rt(y), N = B && Rt(B), G = {
                            x: 0,
                            y: 0,
                            bx: 0,
                            by: 0,
                            X: 0,
                            Y: 0,
                            qx: null,
                            qy: null
                        }, X = {
                            x: 0,
                            y: 0,
                            bx: 0,
                            by: 0,
                            X: 0,
                            Y: 0,
                            qx: null,
                            qy: null
                        }, J = function(k, V, et) {
                            var nt, lt;
                            if (!k) return ["C", V.x, V.y, V.x, V.y, V.x, V.y];
                            switch (!(k[0] in {
                                T: 1,
                                Q: 1
                            }) && (V.qx = V.qy = null), k[0]) {
                                case "M":
                                    V.X = k[1], V.Y = k[2];
                                    break;
                                case "A":
                                    k = ["C"].concat(Ot.apply(0, [V.x, V.y].concat(k.slice(1))));
                                    break;
                                case "S":
                                    et == "C" || et == "S" ? (nt = V.x * 2 - V.bx, lt = V.y * 2 - V.by) : (nt = V.x, lt = V.y), k = ["C", nt, lt].concat(k.slice(1));
                                    break;
                                case "T":
                                    et == "Q" || et == "T" ? (V.qx = V.x * 2 - V.qx, V.qy = V.y * 2 - V.qy) : (V.qx = V.x, V.qy = V.y), k = ["C"].concat(yt(V.x, V.y, V.qx, V.qy, k[1], k[2]));
                                    break;
                                case "Q":
                                    V.qx = k[1], V.qy = k[2], k = ["C"].concat(yt(V.x, V.y, k[1], k[2], k[3], k[4]));
                                    break;
                                case "L":
                                    k = ["C"].concat(Tt(V.x, V.y, k[1], k[2]));
                                    break;
                                case "H":
                                    k = ["C"].concat(Tt(V.x, V.y, k[1], V.y));
                                    break;
                                case "V":
                                    k = ["C"].concat(Tt(V.x, V.y, V.x, k[1]));
                                    break;
                                case "Z":
                                    k = ["C"].concat(Tt(V.x, V.y, V.X, V.Y));
                                    break
                            }
                            return k
                        }, M = function(k, V) {
                            if (k[V].length > 7) {
                                k[V].shift();
                                for (var et = k[V]; et.length;) K[V] = "A", N && (Z[V] = "A"), k.splice(V++, 0, ["C"].concat(et.splice(0, 6)));
                                k.splice(V, 1), E = r(_.length, N && N.length || 0)
                            }
                        }, Q = function(k, V, et, nt, lt) {
                            k && V && k[lt][0] == "M" && V[lt][0] != "M" && (V.splice(lt, 0, ["M", nt.x, nt.y]), et.bx = 0, et.by = 0, et.x = k[lt][1], et.y = k[lt][2], E = r(_.length, N && N.length || 0))
                        }, K = [], Z = [], t = "", i = "", a = 0, E = r(_.length, N && N.length || 0); a < E; a++) {
                        _[a] && (t = _[a][0]), t != "C" && (K[a] = t, a && (i = K[a - 1])), _[a] = J(_[a], G, i), K[a] != "A" && t == "C" && (K[a] = "C"), M(_, a), N && (N[a] && (t = N[a][0]), t != "C" && (Z[a] = t, a && (i = Z[a - 1])), N[a] = J(N[a], X, i), Z[a] != "A" && t == "C" && (Z[a] = "C"), M(N, a)), Q(_, N, G, X, a), Q(N, _, X, G, a);
                        var A = _[a],
                            q = N && N[a],
                            T = A.length,
                            z = N && q.length;
                        G.x = A[T - 2], G.y = A[T - 1], G.bx = l(A[T - 4]) || G.x, G.by = l(A[T - 3]) || G.y, X.bx = N && (l(q[z - 4]) || X.x), X.by = N && (l(q[z - 3]) || X.y), X.x = N && q[z - 2], X.y = N && q[z - 1]
                    }
                    return N || (D.curve = e(_)), N ? [_, N] : _
                }

                function ne(y, B) {
                    if (!B) return y;
                    var D, _, N, G, X, J, M;
                    for (y = wt(y), N = 0, X = y.length; N < X; N++)
                        for (M = y[N], G = 1, J = M.length; G < J; G += 2) D = B.x(M[G], M[G + 1]), _ = B.y(M[G], M[G + 1]), M[G] = D, M[G + 1] = _;
                    return y
                }

                function re(y, B) {
                    for (var D = [], _ = 0, N = y.length; N - 2 * !B > _; _ += 2) {
                        var G = [{
                            x: +y[_ - 2],
                            y: +y[_ - 1]
                        }, {
                            x: +y[_],
                            y: +y[_ + 1]
                        }, {
                            x: +y[_ + 2],
                            y: +y[_ + 3]
                        }, {
                            x: +y[_ + 4],
                            y: +y[_ + 5]
                        }];
                        B ? _ ? N - 4 == _ ? G[3] = {
                            x: +y[0],
                            y: +y[1]
                        } : N - 2 == _ && (G[2] = {
                            x: +y[0],
                            y: +y[1]
                        }, G[3] = {
                            x: +y[2],
                            y: +y[3]
                        }) : G[0] = {
                            x: +y[N - 2],
                            y: +y[N - 1]
                        } : N - 4 == _ ? G[3] = G[2] : _ || (G[0] = {
                            x: +y[_],
                            y: +y[_ + 1]
                        }), D.push(["C", (-G[0].x + 6 * G[1].x + G[2].x) / 6, (-G[0].y + 6 * G[1].y + G[2].y) / 6, (G[1].x + 6 * G[2].x - G[3].x) / 6, (G[1].y + 6 * G[2].y - G[3].y) / 6, G[2].x, G[2].y])
                    }
                    return D
                }
                s.path = P, s.path.getTotalLength = f, s.path.getPointAtLength = F, s.path.getSubpath = function(y, B, D) {
                    if (this.getTotalLength(y) - D < 1e-6) return I(y, B).end;
                    var _ = I(y, D, 1);
                    return B ? I(_, B).end : _
                }, R.getTotalLength = function() {
                    if (this.node.getTotalLength) return this.node.getTotalLength()
                }, R.getPointAtLength = function(y) {
                    return F(this.attr("d"), y)
                }, R.getSubpath = function(y, B) {
                    return s.path.getSubpath(this.attr("d"), y, B)
                }, s._.box = b, s.path.findDotsAtSegment = j, s.path.bezierBBox = U, s.path.isPointInsideBBox = tt, s.closest = function(y, B, D, _) {
                    for (var N = 100, G = b(y - N / 2, B - N / 2, N, N), X = [], J = D[0].hasOwnProperty("x") ? function(E) {
                            return {
                                x: D[E].x,
                                y: D[E].y
                            }
                        } : function(E) {
                            return {
                                x: D[E],
                                y: _[E]
                            }
                        }, M = 0; N <= 1e6 && !M;) {
                        for (var Q = 0, K = D.length; Q < K; Q++) {
                            var Z = J(Q);
                            if (tt(G, Z.x, Z.y)) {
                                M++, X.push(Z);
                                break
                            }
                        }
                        M || (N *= 2, G = b(y - N / 2, B - N / 2, N, N))
                    }
                    if (N != 1e6) {
                        var t = 1 / 0,
                            i;
                        for (Q = 0, K = X.length; Q < K; Q++) {
                            var a = s.len(y, B, X[Q].x, X[Q].y);
                            t > a && (t = a, X[Q].len = a, i = X[Q])
                        }
                        return i
                    }
                }, s.path.isBBoxIntersect = rt, s.path.intersection = ut, s.path.intersectionNumber = _t, s.path.isPointInside = Ft, s.path.getBBox = At, s.path.get = ce, s.path.toRelative = Mt, s.path.toAbsolute = Rt, s.path.toCubic = wt, s.path.map = ne, s.path.toString = m, s.path.clone = e
            }), W.plugin(function(s, C, S, Y) {
                var R = Math.max,
                    w = Math.min,
                    L = function(h) {
                        if (this.items = [], this.bindings = {}, this.length = 0, this.type = "set", h)
                            for (var l = 0, v = h.length; l < v; l++) h[l] && (this[this.items.length] = this.items[this.items.length] = h[l], this.length++)
                    },
                    O = L.prototype;
                O.push = function() {
                    for (var h, l, v = 0, c = arguments.length; v < c; v++) h = arguments[v], h && (l = this.items.length, this[l] = this.items[l] = h, this.length++);
                    return this
                }, O.pop = function() {
                    return this.length && delete this[this.length--], this.items.pop()
                }, O.forEach = function(h, l) {
                    for (var v = 0, c = this.items.length; v < c; v++)
                        if (h.call(l, this.items[v], v) === !1) return this;
                    return this
                }, O.animate = function(h, l, v, c) {
                    typeof v == "function" && !v.length && (c = v, v = H.linear), h instanceof s._.Animation && (c = h.callback, v = h.easing, l = v.dur, h = h.attr);
                    var u = arguments;
                    if (s.is(h, "array") && s.is(u[u.length - 1], "array")) var r = !0;
                    var d, x = function() {
                            d ? this.b = d : d = this.b
                        },
                        P = 0,
                        b = this,
                        m = c && function() {
                            ++P == b.length && c.call(this)
                        };
                    return this.forEach(function(e, o) {
                        p.once("snap.animcreated." + e.id, x), r ? u[o] && e.animate.apply(e, u[o]) : e.animate(h, l, v, m)
                    })
                }, O.remove = function() {
                    for (; this.length;) this.pop().remove();
                    return this
                }, O.bind = function(h, l, v) {
                    var c = {};
                    if (typeof l == "function") this.bindings[h] = l;
                    else {
                        var u = v || h;
                        this.bindings[h] = function(r) {
                            c[u] = r, l.attr(c)
                        }
                    }
                    return this
                }, O.attr = function(h) {
                    var l = {};
                    for (var v in h) this.bindings[v] ? this.bindings[v](h[v]) : l[v] = h[v];
                    for (var c = 0, u = this.items.length; c < u; c++) this.items[c].attr(l);
                    return this
                }, O.clear = function() {
                    for (; this.length;) this.pop()
                }, O.splice = function(h, l, v) {
                    h = h < 0 ? R(this.length + h, 0) : h, l = R(0, w(this.length - h, l));
                    var c = [],
                        u = [],
                        r = [],
                        d;
                    for (d = 2; d < arguments.length; d++) r.push(arguments[d]);
                    for (d = 0; d < l; d++) u.push(this[h + d]);
                    for (; d < this.length - h; d++) c.push(this[h + d]);
                    var x = r.length;
                    for (d = 0; d < x + c.length; d++) this.items[h + d] = this[h + d] = d < x ? r[d] : c[d - x];
                    for (d = this.items.length = this.length -= l - x; this[d];) delete this[d++];
                    return new L(u)
                }, O.exclude = function(h) {
                    for (var l = 0, v = this.length; l < v; l++)
                        if (this[l] == h) return this.splice(l, 1), !0;
                    return !1
                }, O.insertAfter = function(h) {
                    for (var l = this.items.length; l--;) this.items[l].insertAfter(h);
                    return this
                }, O.getBBox = function() {
                    for (var h = [], l = [], v = [], c = [], u = this.items.length; u--;)
                        if (!this.items[u].removed) {
                            var r = this.items[u].getBBox();
                            h.push(r.x), l.push(r.y), v.push(r.x + r.width), c.push(r.y + r.height)
                        }
                    return h = w.apply(0, h), l = w.apply(0, l), v = R.apply(0, v), c = R.apply(0, c), {
                        x: h,
                        y: l,
                        x2: v,
                        y2: c,
                        width: v - h,
                        height: c - l,
                        cx: h + (v - h) / 2,
                        cy: l + (c - l) / 2
                    }
                }, O.clone = function(h) {
                    h = new L;
                    for (var l = 0, v = this.items.length; l < v; l++) h.push(this.items[l].clone());
                    return h
                }, O.toString = function() {
                    return "Snap\u2018s set"
                }, O.type = "set", s.Set = L, s.set = function() {
                    var h = new L;
                    return arguments.length && h.push.apply(h, Array.prototype.slice.call(arguments, 0)), h
                }
            }), W.plugin(function(s, C, S, Y) {
                var R = {},
                    w = /[%a-z]+$/i,
                    L = String;
                R.stroke = R.fill = "colour";

                function O(b) {
                    var m = b[0];
                    switch (m.toLowerCase()) {
                        case "t":
                            return [m, 0, 0];
                        case "m":
                            return [m, 1, 0, 0, 1, 0, 0];
                        case "r":
                            return b.length == 4 ? [m, 0, b[2], b[3]] : [m, 0];
                        case "s":
                            return b.length == 5 ? [m, 1, 1, b[3], b[4]] : b.length == 3 ? [m, 1, 1] : [m, 1]
                    }
                }

                function h(b, m, e) {
                    b = b || new s.Matrix, m = m || new s.Matrix, b = s.parseTransformString(b.toTransformString()) || [], m = s.parseTransformString(m.toTransformString()) || [];
                    for (var o = Math.max(b.length, m.length), n = [], f = [], F = 0, I, j, U, tt; F < o; F++) {
                        if (U = b[F] || O(m[F]), tt = m[F] || O(U), U[0] != tt[0] || U[0].toLowerCase() == "r" && (U[2] != tt[2] || U[3] != tt[3]) || U[0].toLowerCase() == "s" && (U[3] != tt[3] || U[4] != tt[4])) {
                            b = s._.transform2matrix(b, e()), m = s._.transform2matrix(m, e()), n = [
                                ["m", b.a, b.b, b.c, b.d, b.e, b.f]
                            ], f = [
                                ["m", m.a, m.b, m.c, m.d, m.e, m.f]
                            ];
                            break
                        }
                        for (n[F] = [], f[F] = [], I = 0, j = Math.max(U.length, tt.length); I < j; I++) I in U && (n[F][I] = U[I]), I in tt && (f[F][I] = tt[I])
                    }
                    return {
                        from: d(n),
                        to: d(f),
                        f: r(n)
                    }
                }

                function l(b) {
                    return b
                }

                function v(b) {
                    return function(m) {
                        return +m.toFixed(3) + b
                    }
                }

                function c(b) {
                    return b.join(" ")
                }

                function u(b) {
                    return s.rgb(b[0], b[1], b[2], b[3])
                }

                function r(b) {
                    var m = 0,
                        e, o, n, f, F, I, j = [];
                    for (e = 0, o = b.length; e < o; e++) {
                        for (F = "[", I = ['"' + b[e][0] + '"'], n = 1, f = b[e].length; n < f; n++) I[n] = "val[" + m++ + "]";
                        F += I + "]", j[e] = F
                    }
                    return Function("val", "return Snap.path.toString.call([" + j + "])")
                }

                function d(b) {
                    for (var m = [], e = 0, o = b.length; e < o; e++)
                        for (var n = 1, f = b[e].length; n < f; n++) m.push(b[e][n]);
                    return m
                }

                function x(b) {
                    return isFinite(b)
                }

                function P(b, m) {
                    return !s.is(b, "array") || !s.is(m, "array") ? !1 : b.toString() == m.toString()
                }
                C.prototype.equal = function(b, m) {
                    return p("snap.util.equal", this, b, m).firstDefined()
                }, p.on("snap.util.equal", function(b, m) {
                    var e, o, n = L(this.attr(b) || ""),
                        f = this;
                    if (R[b] == "colour") return e = s.color(n), o = s.color(m), {
                        from: [e.r, e.g, e.b, e.opacity],
                        to: [o.r, o.g, o.b, o.opacity],
                        f: u
                    };
                    if (b == "viewBox") return e = this.attr(b).vb.split(" ").map(Number), o = m.split(" ").map(Number), {
                        from: e,
                        to: o,
                        f: c
                    };
                    if (b == "transform" || b == "gradientTransform" || b == "patternTransform") return typeof m == "string" && (m = L(m).replace(/\.{3}|\u2026/g, n)), n = this.matrix, s._.rgTransform.test(m) ? m = s._.transform2matrix(m, this.getBBox()) : m = s._.transform2matrix(s._.svgTransform2string(m), this.getBBox()), h(n, m, function() {
                        return f.getBBox(1)
                    });
                    if (b == "d" || b == "path") return e = s.path.toCubic(n, m), {
                        from: d(e[0]),
                        to: d(e[1]),
                        f: r(e[0])
                    };
                    if (b == "points") return e = L(n).split(s._.separator), o = L(m).split(s._.separator), {
                        from: e,
                        to: o,
                        f: function(j) {
                            return j
                        }
                    };
                    if (x(n) && x(m)) return {
                        from: parseFloat(n),
                        to: parseFloat(m),
                        f: l
                    };
                    var F = n.match(w),
                        I = L(m).match(w);
                    return F && P(F, I) ? {
                        from: parseFloat(n),
                        to: parseFloat(m),
                        f: v(F)
                    } : {
                        from: this.asPX(b),
                        to: this.asPX(b, m),
                        f: l
                    }
                })
            }), W.plugin(function(s, C, S, Y) {
                for (var R = C.prototype, w = "hasOwnProperty", L = ("createTouch" in Y.doc), O = ["click", "dblclick", "mousedown", "mousemove", "mouseout", "mouseover", "mouseup", "touchstart", "touchmove", "touchend", "touchcancel"], h = {
                        mousedown: "touchstart",
                        mousemove: "touchmove",
                        mouseup: "touchend"
                    }, l = function(o, n) {
                        var f = o == "y" ? "scrollTop" : "scrollLeft",
                            F = n && n.node ? n.node.ownerDocument : Y.doc;
                        return F[f in F.documentElement ? "documentElement" : "body"][f]
                    }, v = function() {
                        this.returnValue = !1
                    }, c = function() {
                        return this.originalEvent.preventDefault()
                    }, u = function() {
                        this.cancelBubble = !0
                    }, r = function() {
                        return this.originalEvent.stopPropagation()
                    }, d = function(o, n, f, F) {
                        var I = L && h[n] ? h[n] : n,
                            j = function(U) {
                                var tt = l("y", F),
                                    rt = l("x", F);
                                if (L && h[w](n)) {
                                    for (var ot = 0, at = U.targetTouches && U.targetTouches.length; ot < at; ot++)
                                        if (U.targetTouches[ot].target == o || o.contains(U.targetTouches[ot].target)) {
                                            var ct = U;
                                            U = U.targetTouches[ot], U.originalEvent = ct, U.preventDefault = c, U.stopPropagation = r;
                                            break
                                        }
                                }
                                var it = U.clientX + rt,
                                    vt = U.clientY + tt;
                                return f.call(F, U, it, vt)
                            };
                        return n !== I && o.addEventListener(n, j, !1), o.addEventListener(I, j, !1),
                            function() {
                                return n !== I && o.removeEventListener(n, j, !1), o.removeEventListener(I, j, !1), !0
                            }
                    }, x = [], P = function(o) {
                        for (var n = o.clientX, f = o.clientY, F = l("y"), I = l("x"), j, U = x.length; U--;) {
                            if (j = x[U], L) {
                                for (var tt = o.touches && o.touches.length, rt; tt--;)
                                    if (rt = o.touches[tt], rt.identifier == j.el._drag.id || j.el.node.contains(rt.target)) {
                                        n = rt.clientX, f = rt.clientY, (o.originalEvent ? o.originalEvent : o).preventDefault();
                                        break
                                    }
                            } else o.preventDefault();
                            var ot = j.el.node,
                                at, ct = ot.nextSibling,
                                it = ot.parentNode,
                                vt = ot.style.display;
                            n += I, f += F, p("snap.drag.move." + j.el.id, j.move_scope || j.el, n - j.el._drag.x, f - j.el._drag.y, n, f, o)
                        }
                    }, b = function(o) {
                        s.unmousemove(P).unmouseup(b);
                        for (var n = x.length, f; n--;) f = x[n], f.el._drag = {}, p("snap.drag.end." + f.el.id, f.end_scope || f.start_scope || f.move_scope || f.el, o), p.off("snap.drag.*." + f.el.id);
                        x = []
                    }, m = O.length; m--;)(function(o) {
                    s[o] = R[o] = function(n, f) {
                        if (s.is(n, "function")) this.events = this.events || [], this.events.push({
                            name: o,
                            f: n,
                            unbind: d(this.node || document, o, n, f || this)
                        });
                        else
                            for (var F = 0, I = this.events.length; F < I; F++)
                                if (this.events[F].name == o) try {
                                    this.events[F].f.call(this)
                                } catch (j) {}
                        return this
                    }, s["un" + o] = R["un" + o] = function(n) {
                        for (var f = this.events || [], F = f.length; F--;)
                            if (f[F].name == o && (f[F].f == n || !n)) return f[F].unbind(), f.splice(F, 1), !f.length && delete this.events, this;
                        return this
                    }
                })(O[m]);
                R.hover = function(o, n, f, F) {
                    return this.mouseover(o, f).mouseout(n, F || f)
                }, R.unhover = function(o, n) {
                    return this.unmouseover(o).unmouseout(n)
                };
                var e = [];
                R.drag = function(o, n, f, F, I, j) {
                    var U = this;
                    if (!arguments.length) {
                        var tt;
                        return U.drag(function(at, ct) {
                            this.attr({
                                transform: tt + (tt ? "T" : "t") + [at, ct]
                            })
                        }, function() {
                            tt = this.transform().local
                        })
                    }

                    function rt(at, ct, it) {
                        (at.originalEvent || at).preventDefault(), U._drag.x = ct, U._drag.y = it, U._drag.id = at.identifier, !x.length && s.mousemove(P).mouseup(b), x.push({
                            el: U,
                            move_scope: F,
                            start_scope: I,
                            end_scope: j
                        }), n && p.on("snap.drag.start." + U.id, n), o && p.on("snap.drag.move." + U.id, o), f && p.on("snap.drag.end." + U.id, f), p("snap.drag.start." + U.id, I || F || U, ct, it, at)
                    }

                    function ot(at, ct, it) {
                        p("snap.draginit." + U.id, U, at, ct, it)
                    }
                    return p.on("snap.draginit." + U.id, rt), U._drag = {}, e.push({
                        el: U,
                        start: rt,
                        init: ot
                    }), U.mousedown(ot), U
                }, R.undrag = function() {
                    for (var o = e.length; o--;) e[o].el == this && (this.unmousedown(e[o].init), e.splice(o, 1), p.unbind("snap.drag.*." + this.id), p.unbind("snap.draginit." + this.id));
                    return !e.length && s.unmousemove(P).unmouseup(b), this
                }
            }), W.plugin(function(s, C, S, Y) {
                var R = C.prototype,
                    w = S.prototype,
                    L = /^\s*url\((.+)\)/,
                    O = String,
                    h = s._.$;
                s.filter = {}, w.filter = function(l) {
                    var v = this;
                    v.type != "svg" && (v = v.paper);
                    var c = s.parse(O(l)),
                        u = s._.id(),
                        r = v.node.offsetWidth,
                        d = v.node.offsetHeight,
                        x = h("filter");
                    return h(x, {
                        id: u,
                        filterUnits: "userSpaceOnUse"
                    }), x.appendChild(c.node), v.defs.appendChild(x), new C(x)
                }, p.on("snap.util.getattr.filter", function() {
                    p.stop();
                    var l = h(this.node, "filter");
                    if (l) {
                        var v = O(l).match(L);
                        return v && s.select(v[1])
                    }
                }), p.on("snap.util.attr.filter", function(l) {
                    if (l instanceof C && l.type == "filter") {
                        p.stop();
                        var v = l.node.id;
                        v || (h(l.node, {
                            id: l.id
                        }), v = l.id), h(this.node, {
                            filter: s.url(v)
                        })
                    }(!l || l == "none") && (p.stop(), this.node.removeAttribute("filter"))
                }), s.filter.blur = function(l, v) {
                    l == null && (l = 2);
                    var c = v == null ? l : [l, v];
                    return s.format('<feGaussianBlur stdDeviation="{def}"/>', {
                        def: c
                    })
                }, s.filter.blur.toString = function() {
                    return this()
                }, s.filter.shadow = function(l, v, c, u, r) {
                    return r == null && (u == null ? (r = c, c = 4, u = "#000") : (r = u, u = c, c = 4)), c == null && (c = 4), r == null && (r = 1), l == null && (l = 0, v = 2), v == null && (v = l), u = s.color(u), s.format('<feGaussianBlur in="SourceAlpha" stdDeviation="{blur}"/><feOffset dx="{dx}" dy="{dy}" result="offsetblur"/><feFlood flood-color="{color}"/><feComposite in2="offsetblur" operator="in"/><feComponentTransfer><feFuncA type="linear" slope="{opacity}"/></feComponentTransfer><feMerge><feMergeNode/><feMergeNode in="SourceGraphic"/></feMerge>', {
                        color: u,
                        dx: l,
                        dy: v,
                        blur: c,
                        opacity: r
                    })
                }, s.filter.shadow.toString = function() {
                    return this()
                }, s.filter.grayscale = function(l) {
                    return l == null && (l = 1), s.format('<feColorMatrix type="matrix" values="{a} {b} {c} 0 0 {d} {e} {f} 0 0 {g} {b} {h} 0 0 0 0 0 1 0"/>', {
                        a: .2126 + .7874 * (1 - l),
                        b: .7152 - .7152 * (1 - l),
                        c: .0722 - .0722 * (1 - l),
                        d: .2126 - .2126 * (1 - l),
                        e: .7152 + .2848 * (1 - l),
                        f: .0722 - .0722 * (1 - l),
                        g: .2126 - .2126 * (1 - l),
                        h: .0722 + .9278 * (1 - l)
                    })
                }, s.filter.grayscale.toString = function() {
                    return this()
                }, s.filter.sepia = function(l) {
                    return l == null && (l = 1), s.format('<feColorMatrix type="matrix" values="{a} {b} {c} 0 0 {d} {e} {f} 0 0 {g} {h} {i} 0 0 0 0 0 1 0"/>', {
                        a: .393 + .607 * (1 - l),
                        b: .769 - .769 * (1 - l),
                        c: .189 - .189 * (1 - l),
                        d: .349 - .349 * (1 - l),
                        e: .686 + .314 * (1 - l),
                        f: .168 - .168 * (1 - l),
                        g: .272 - .272 * (1 - l),
                        h: .534 - .534 * (1 - l),
                        i: .131 + .869 * (1 - l)
                    })
                }, s.filter.sepia.toString = function() {
                    return this()
                }, s.filter.saturate = function(l) {
                    return l == null && (l = 1), s.format('<feColorMatrix type="saturate" values="{amount}"/>', {
                        amount: 1 - l
                    })
                }, s.filter.saturate.toString = function() {
                    return this()
                }, s.filter.hueRotate = function(l) {
                    return l = l || 0, s.format('<feColorMatrix type="hueRotate" values="{angle}"/>', {
                        angle: l
                    })
                }, s.filter.hueRotate.toString = function() {
                    return this()
                }, s.filter.invert = function(l) {
                    return l == null && (l = 1), s.format('<feComponentTransfer><feFuncR type="table" tableValues="{amount} {amount2}"/><feFuncG type="table" tableValues="{amount} {amount2}"/><feFuncB type="table" tableValues="{amount} {amount2}"/></feComponentTransfer>', {
                        amount: l,
                        amount2: 1 - l
                    })
                }, s.filter.invert.toString = function() {
                    return this()
                }, s.filter.brightness = function(l) {
                    return l == null && (l = 1), s.format('<feComponentTransfer><feFuncR type="linear" slope="{amount}"/><feFuncG type="linear" slope="{amount}"/><feFuncB type="linear" slope="{amount}"/></feComponentTransfer>', {
                        amount: l
                    })
                }, s.filter.brightness.toString = function() {
                    return this()
                }, s.filter.contrast = function(l) {
                    return l == null && (l = 1), s.format('<feComponentTransfer><feFuncR type="linear" slope="{amount}" intercept="{amount2}"/><feFuncG type="linear" slope="{amount}" intercept="{amount2}"/><feFuncB type="linear" slope="{amount}" intercept="{amount2}"/></feComponentTransfer>', {
                        amount: l,
                        amount2: .5 - l / 2
                    })
                }, s.filter.contrast.toString = function() {
                    return this()
                }
            }), W.plugin(function(s, C, S, Y, R) {
                var w = s._.box,
                    L = s.is,
                    O = /^[^a-z]*([tbmlrc])/i,
                    h = function() {
                        return "T" + this.dx + "," + this.dy
                    };
                C.prototype.getAlign = function(l, v) {
                    v == null && L(l, "string") && (v = l, l = null), l = l || this.paper;
                    var c = l.getBBox ? l.getBBox() : w(l),
                        u = this.getBBox(),
                        r = {};
                    switch (v = v && v.match(O), v = v ? v[1].toLowerCase() : "c", v) {
                        case "t":
                            r.dx = 0, r.dy = c.y - u.y;
                            break;
                        case "b":
                            r.dx = 0, r.dy = c.y2 - u.y2;
                            break;
                        case "m":
                            r.dx = 0, r.dy = c.cy - u.cy;
                            break;
                        case "l":
                            r.dx = c.x - u.x, r.dy = 0;
                            break;
                        case "r":
                            r.dx = c.x2 - u.x2, r.dy = 0;
                            break;
                        default:
                            r.dx = c.cx - u.cx, r.dy = 0;
                            break
                    }
                    return r.toString = h, r
                }, C.prototype.align = function(l, v) {
                    return this.transform("..." + this.getAlign(l, v))
                }
            }), W.plugin(function(s, C, S, Y) {
                var R = "#ffebee#ffcdd2#ef9a9a#e57373#ef5350#f44336#e53935#d32f2f#c62828#b71c1c#ff8a80#ff5252#ff1744#d50000",
                    w = "#FCE4EC#F8BBD0#F48FB1#F06292#EC407A#E91E63#D81B60#C2185B#AD1457#880E4F#FF80AB#FF4081#F50057#C51162",
                    L = "#F3E5F5#E1BEE7#CE93D8#BA68C8#AB47BC#9C27B0#8E24AA#7B1FA2#6A1B9A#4A148C#EA80FC#E040FB#D500F9#AA00FF",
                    O = "#EDE7F6#D1C4E9#B39DDB#9575CD#7E57C2#673AB7#5E35B1#512DA8#4527A0#311B92#B388FF#7C4DFF#651FFF#6200EA",
                    h = "#E8EAF6#C5CAE9#9FA8DA#7986CB#5C6BC0#3F51B5#3949AB#303F9F#283593#1A237E#8C9EFF#536DFE#3D5AFE#304FFE",
                    l = "#E3F2FD#BBDEFB#90CAF9#64B5F6#64B5F6#2196F3#1E88E5#1976D2#1565C0#0D47A1#82B1FF#448AFF#2979FF#2962FF",
                    v = "#E1F5FE#B3E5FC#81D4FA#4FC3F7#29B6F6#03A9F4#039BE5#0288D1#0277BD#01579B#80D8FF#40C4FF#00B0FF#0091EA",
                    c = "#E0F7FA#B2EBF2#80DEEA#4DD0E1#26C6DA#00BCD4#00ACC1#0097A7#00838F#006064#84FFFF#18FFFF#00E5FF#00B8D4",
                    u = "#E0F2F1#B2DFDB#80CBC4#4DB6AC#26A69A#009688#00897B#00796B#00695C#004D40#A7FFEB#64FFDA#1DE9B6#00BFA5",
                    r = "#E8F5E9#C8E6C9#A5D6A7#81C784#66BB6A#4CAF50#43A047#388E3C#2E7D32#1B5E20#B9F6CA#69F0AE#00E676#00C853",
                    d = "#F1F8E9#DCEDC8#C5E1A5#AED581#9CCC65#8BC34A#7CB342#689F38#558B2F#33691E#CCFF90#B2FF59#76FF03#64DD17",
                    x = "#F9FBE7#F0F4C3#E6EE9C#DCE775#D4E157#CDDC39#C0CA33#AFB42B#9E9D24#827717#F4FF81#EEFF41#C6FF00#AEEA00",
                    P = "#FFFDE7#FFF9C4#FFF59D#FFF176#FFEE58#FFEB3B#FDD835#FBC02D#F9A825#F57F17#FFFF8D#FFFF00#FFEA00#FFD600",
                    b = "#FFF8E1#FFECB3#FFE082#FFD54F#FFCA28#FFC107#FFB300#FFA000#FF8F00#FF6F00#FFE57F#FFD740#FFC400#FFAB00",
                    m = "#FFF3E0#FFE0B2#FFCC80#FFB74D#FFA726#FF9800#FB8C00#F57C00#EF6C00#E65100#FFD180#FFAB40#FF9100#FF6D00",
                    e = "#FBE9E7#FFCCBC#FFAB91#FF8A65#FF7043#FF5722#F4511E#E64A19#D84315#BF360C#FF9E80#FF6E40#FF3D00#DD2C00",
                    o = "#EFEBE9#D7CCC8#BCAAA4#A1887F#8D6E63#795548#6D4C41#5D4037#4E342E#3E2723",
                    n = "#FAFAFA#F5F5F5#EEEEEE#E0E0E0#BDBDBD#9E9E9E#757575#616161#424242#212121",
                    f = "#ECEFF1#CFD8DC#B0BEC5#90A4AE#78909C#607D8B#546E7A#455A64#37474F#263238";
                s.mui = {}, s.flat = {};

                function F(I) {
                    I = I.split(/(?=#)/);
                    var j = new String(I[5]);
                    return j[50] = I[0], j[100] = I[1], j[200] = I[2], j[300] = I[3], j[400] = I[4], j[500] = I[5], j[600] = I[6], j[700] = I[7], j[800] = I[8], j[900] = I[9], I[10] && (j.A100 = I[10], j.A200 = I[11], j.A400 = I[12], j.A700 = I[13]), j
                }
                s.mui.red = F(R), s.mui.pink = F(w), s.mui.purple = F(L), s.mui.deeppurple = F(O), s.mui.indigo = F(h), s.mui.blue = F(l), s.mui.lightblue = F(v), s.mui.cyan = F(c), s.mui.teal = F(u), s.mui.green = F(r), s.mui.lightgreen = F(d), s.mui.lime = F(x), s.mui.yellow = F(P), s.mui.amber = F(b), s.mui.orange = F(m), s.mui.deeporange = F(e), s.mui.brown = F(o), s.mui.grey = F(n), s.mui.bluegrey = F(f), s.flat.turquoise = "#1abc9c", s.flat.greensea = "#16a085", s.flat.sunflower = "#f1c40f", s.flat.orange = "#f39c12", s.flat.emerland = "#2ecc71", s.flat.nephritis = "#27ae60", s.flat.carrot = "#e67e22", s.flat.pumpkin = "#d35400", s.flat.peterriver = "#3498db", s.flat.belizehole = "#2980b9", s.flat.alizarin = "#e74c3c", s.flat.pomegranate = "#c0392b", s.flat.amethyst = "#9b59b6", s.flat.wisteria = "#8e44ad", s.flat.clouds = "#ecf0f1", s.flat.silver = "#bdc3c7", s.flat.wetasphalt = "#34495e", s.flat.midnightblue = "#2c3e50", s.flat.concrete = "#95a5a6", s.flat.asbestos = "#7f8c8d", s.importMUIColors = function() {
                    for (var I in s.mui) s.mui.hasOwnProperty(I) && ($[I] = s.mui[I])
                }
            }), W
        })
    });
    var we = {
        game: {
            symbols: {
                3: {
                    values: [100, 50, 20],
                    txt: ["major", "minor", "mini"]
                }
            },
            styles: {
                value: {
                    "font-size": "50%",
                    "font-family": '"Arial Black", Gadget, sans-serif',
                    "background-color": "white",
                    fill: "black",
                    "font-weight": "bold",
                    "text-anchor": "middle",
                    "dominant-baseline": "central"
                }
            }
        },
        background: {
            spins: {
                spin: "#B8860B",
                freespin_init: "#4682B4",
                freespin_stop: "#4682B4",
                bonus_init: "#6B8E23",
                bonus_stop: "#6B8E23",
                init: "#B8860B",
                restore: "#B8860B"
            },
            freespins: {
                freespin: "#4682B4",
                freespin_init: "#4682B4",
                freespin_stop: "#4682B4",
                bonus_init: "#6B8E23",
                bonus_stop: "#6B8E23",
                init: "#4682B4",
                restore: "#4682B4"
            },
            bonus: {
                respin: "#6B8E23",
                bonus_init: "#6B8E23",
                bonus_stop: "#6B8E23",
                init: "#6B8E23",
                restore: "#6B8E23"
            },
            default: "#4682B4"
        },
        text: {
            spins: {
                freespin_init: "FS count: ",
                freespin_stop: "Free spins End",
                bonus_init: "Bonus game start",
                bonus_stop: "Bonus game End"
            },
            freespins: {
                freespin_init: "FS count: ",
                freespin_stop: "Free spins End",
                bonus_init: "Bonus game start",
                bonus_stop: "Bonus game End"
            },
            bonus: {
                bonus_init: "Bonus game start",
                bonus_stop: "Bonus game End"
            },
            default: ""
        },
        styles: {
            STYLE_PROGRESS: {
                fill: "#f00"
            },
            STYLE_PANEL: {
                fill: "#666"
            },
            STYLE_PANEL_TEXT: {
                "alignment-baseline": "middle",
                "font-family": "monospace",
                "font-weight": "bold",
                "font-size": "0.6em",
                fill: "#fff"
            },
            STYLE_LINE_GROUP: {
                transform: "matrix(1, 0, 0, 1, 0.5, 0.5)",
                "font-size": "1em"
            },
            STYLE_LINE_SYMBOL: {
                stroke: "#46ff1c",
                "stroke-width": 2,
                "fill-opacity": 0
            },
            STYLE_LINE_INNER: {
                stroke: "#D54E4A",
                "stroke-width": 3,
                "stroke-linejoin": "round",
                "stroke-linecap": "round",
                "fill-opacity": 0
            },
            STYLE_LINE_OUTER: {
                stroke: "#ff0000",
                "stroke-width": 4,
                "stroke-linejoin": "round",
                "stroke-linecap": "round",
                "fill-opacity": 0
            },
            STYLE_TEXT_CENTER: {
                "text-anchor": "middle",
                fill: "#fff",
                "font-size": "1em"
            },
            STYLE_TEXT_CENTER_RED: {
                stroke: "#fff",
                "text-anchor": "middle",
                fill: "#0000",
                "font-size": "1.2em"
            },
            STYLE_TEXT_BONUS: {
                "text-anchor": "middle",
                fill: "#fff",
                "font-size": "0.5em"
            },
            STYLE_TEXT_BONUS_WIN: {
                "text-anchor": "middle",
                fill: "#eec411",
                "font-size": "0.5em"
            },
            STYLE_TEXT_AWARD: {
                "text-anchor": "middle",
                fill: "#000000",
                "font-size": "1.5em"
            }
        }
    };
    var _e = Wt(It());

    function Qe($, p, H) {
        let W = `${p}`.replace("/\\/{2,}/g", "/");
        return W.endsWith("/") ? W + `assets/${$}?${H}` : W + `/assets/${$}?${H}`
    }

    function Ke($) {
        let {
            context: p,
            action: H
        } = $;
        return H == "freespin" ? `FS(${p.power.freespin.left})` : H
    }

    function tn($) {
        return (($ || 0) / 100).toFixed(2)
    }
    var gt = {
        url: Qe,
        labelLeft: Ke,
        coins: tn
    };
    var en = "#6B8E23";

    function nn($) {
        let {
            width: p,
            height: H,
            config: W,
            details: s,
            revision: C,
            panelFont: S,
            panelHeight: Y
        } = $, {
            url: R,
            coins: w
        } = gt, {
            styles: L
        } = W, O = (0, _e.default)(p, H);
        O.rect(0, 0, p, H).attr({
            fill: en
        });
        let h = O.group(),
            l = s.send_transaction ? w(s.round_win) : "Manual Mode",
            v = R("prezent.png", $.url, C);
        O.image(v, p / 2.5, H / 4, p / 5, H / 4), h.text(p / 2, H * .75, l).attr(L.STYLE_TEXT_AWARD), h.rect(0, 0, "100%", Y).attr(L.STYLE_PANEL);
        let c = Y / 2 + 1,
            u = L.STYLE_PANEL_TEXT;
        return u["font-size"] = S, h.text("2%", c, "Award").attr(u), O.node
    }
    var Ae = {
        create: nn
    };
    var Te = Wt(It()),
        rn = "Error!",
        on = "middle",
        sn = "#f00";

    function an($) {
        let {
            width: p,
            height: H
        } = $, W = (0, Te.default)(p, H);
        return W.group().text(p / 2, H / 2, rn).attr({
            textAnchor: on,
            fill: sn
        }), W.node
    }
    var Pe = {
        create: an
    };
    var De = Wt(It()),
        fn = "No Renderer",
        un = "middle";

    function ln($) {
        let {
            width: p,
            height: H
        } = $, W = (0, De.default)(p, H);
        return W.group().text(p / 2, H / 2, fn).attr({
            textAnchor: un
        }), W.node
    }
    var Ne = {
        create: ln
    };
    var Le = Wt(It());

    function cn($) {
        let {
            width: p,
            height: H,
            config: W
        } = $, s = (0, Le.default)(p, H);

        function C(R) {
            let {
                action: w,
                context: L
            } = R, O = L.current, h = W.background[O][w] || W.background.default;
            s.rect(0, 0, p, H).attr({
                fill: h
            })
        }

        function S() {
            let R = s.group();
            return R.attr({
                width: p,
                height: H
            }), R
        }

        function Y(R) {
            return s.click(R)
        }
        return {
            instance: s,
            background: C,
            group: S,
            click: Y
        }
    }
    var ke = {
        create: cn
    };

    function hn($, p, [H, W]) {
        let s = $.group(),
            {
                context: C,
                cellWidth: S,
                cellOffsetX: Y,
                cellHeight: R,
                cellOffsetY: w,
                config: L,
                width: O
            } = p,
            {
                power: h,
                last_args: l
            } = C,
            {
                hold: v,
                coin: c,
                board: u
            } = h;
        if (c) {
            let r = c[H][W],
                d = v ? v[H][W] : u[H][W],
                x = L.game.symbols[d];
            if (r && x) {
                let n = function(I) {
                        if (typeof r == "number") {
                            let {
                                values: j,
                                txt: U
                            } = x, tt = I / (l.bet_per_line * l.lines), rt = j.findIndex(ot => tt >= ot);
                            return rt >= 0 ? U[rt] : (I / 100).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                        } else return I
                    },
                    P = (S + Y) * H - Y,
                    b = (R + w) * W + R / 3,
                    m = S + 2 * Y,
                    e = P + m / 2,
                    o = (R + w) * W + R / 2;
                s.rect(P, b, m, R / 3).attr({
                    fill: "white"
                });
                let f = n(r),
                    F = L.game.styles.value;
                typeof r == "number" && (r > 1e6 ? L.game.styles.value["font-size"] = O > 200 ? "34%" : "19%" : r > 1e3 ? L.game.styles.value["font-size"] = O > 200 ? "48%" : "27%" : L.game.styles.value["font-size"] = O > 200 ? "70%" : "40%"), s.text(e, o, f).attr(F)
            }
        }
        return {
            group: s
        }
    }
    var Me = {
        create: hn
    };

    function dn($, p, H) {
        let W = $.group(),
            {
                cellWidth: s,
                cellOffsetX: C,
                cellHeight: S,
                cellOffsetY: Y,
                url: R,
                revision: w
            } = p,
            L = [],
            O = [];
        for (let c = 0; c < H.length; c++) {
            L[c] = [], O[c] = [];
            for (let u = 0; u < H[c].length; u++) {
                let r = gt.url(H[c][u].toLowerCase() + ".png", R, w),
                    d = W.image(r, (s + C) * c, (S + Y) * u, s, S),
                    x = Me.create(W, p, [c, u]).group;
                L[c].push(d), O[c].push(x)
            }
        }

        function h(c, u) {
            for (let r = 0; r < c.length; r++) {
                let [d, x] = c[r];
                L[d][x].attr({
                    opacity: u
                }), O[d][x].attr({
                    opacity: u
                })
            }
        }

        function l(c) {
            for (let u = 0; u < H.length; u++)
                for (let r = 0; r < H[u].length; r++) L[u][r].attr({
                    opacity: c
                }), O[u][r].attr({
                    opacity: c
                })
        }
        return {
            group: W,
            opacity: {
                all: l,
                pos: h
            }
        }
    }
    var Dt = {
        create: dn
    };

    function gn($, p, H) {
        let W = $.group(),
            {
                width: s,
                height: C,
                progressHeight: S
            } = p,
            Y = p.config.styles,
            R = s / H,
            w = W.rect(0, C - S, R, 3);
        w.attr(Y.STYLE_PROGRESS);

        function L(O) {
            w.animate({
                width: R * (O + 1)
            }, 100)
        }
        return {
            group: W,
            next: L
        }
    }
    var Bt = {
        create: gn
    };

    function pn($, p, H = "", W = "") {
        let s = gt.labelLeft(p) + W,
            {
                panelFont: C,
                panelHeight: S,
                config: Y
            } = p,
            {
                styles: R
            } = Y;
        $.rect(0, 0, "100%", S).attr(R.STYLE_PANEL);
        let w = S / 2 + 1,
            L = R.STYLE_PANEL_TEXT;
        L["font-size"] = C, $.text("2%", w, s).attr(L);
        let O = $.text("98%", w, H);
        return O.attr(L), O.attr({
            textAnchor: "end"
        }), {
            group: $
        }
    }
    var mt = {
        create: pn
    };

    function mn({
        snap: $,
        data: p,
        board: H
    }) {
        function W(s) {
            let {
                context: C
            } = p, S = $.group(), {
                win: Y
            } = C.power, R = `all/${s.length}`, w = `, Win:${gt.coins(Y)}`;
            mt.create(S, p, R, w);
            for (let L = 0; L < s.length; L++) s[L].all && s[L].all(S);
            return H.opacity.all(1), S
        }
        return {
            next: W
        }
    }
    var Zt = {
        create: mn
    };

    function vn($, p, H) {
        let {
            cellWidth: W,
            cellOffsetX: s,
            cellHeight: C,
            cellOffsetY: S
        } = p, Y = p.config.styles, R = $.group();
        R.attr(Y.STYLE_LINE_GROUP);
        for (let w = 0; w < H.length; ++w) {
            let [L, O] = H[w];
            R.rect(L * (W + s), O * (C + S), W + s, C + S).attr(Y.STYLE_LINE_SYMBOL)
        }
        return {
            group: R
        }
    }
    var xt = {
        draw: vn
    };

    function yn({
        snap: $,
        data: p,
        event: H,
        board: W
    }) {
        function s(S) {
            let {
                symbols: Y,
                position: R
            } = H, {
                transform: w
            } = p, L = S.group(), O = [...Y.map(({
                position: h
            }) => h), R];
            return xt.draw(L, p, O).group.transform(w), L
        }

        function C() {
            let {
                symbols: S,
                win: Y,
                position: R
            } = H, {
                transform: w
            } = p, L = $.group(), O = "boost-" + S.length, h = ", Win:" + gt.coins(Y), l = [...S.map(({
                position: v
            }) => v), R];
            return W.opacity.pos(l, 1), mt.create(L, p, O, h), xt.draw(L, p, l).group.transform(w), L
        }
        return {
            next: C,
            all: s
        }
    }
    var Qt = {
        create: yn
    };

    function xn({
        snap: $,
        data: p,
        event: H,
        board: W
    }) {
        function s() {
            let {
                win: C
            } = H, {
                styles: S
            } = p.config, Y = $.group(), R = "BoostWin", w = ", Win:" + gt.coins(C);
            W.opacity.all(0), mt.create(Y, p, R, w);
            let L = "Boost Win";
            return Y.text("50%", "56%", L).attr(S.STYLE_TEXT_CENTER_RED), Y
        }
        return {
            next: s
        }
    }
    var Kt = {
        create: xn
    };

    function Fn({
        snap: $,
        data: p,
        event: H,
        board: W
    }) {
        function s() {
            let {
                win: C
            } = H, {
                styles: S
            } = p.config, Y = $.group(), R = "Jackpot", w = ", Win:" + gt.coins(C);
            W.opacity.all(0), mt.create(Y, p, R, w);
            let L = "Jackpot !";
            return Y.text("50%", "56%", L).attr(S.STYLE_TEXT_CENTER_RED), Y
        }
        return {
            next: s
        }
    }
    var Re = {
        create: Fn
    };

    function En({
        snap: $,
        data: p,
        board: H
    }) {
        function W() {
            let {
                context: s
            } = p, C = $.group();
            return H.opacity.all(1), mt.create(C, p, "", `, Win:${gt.coins(s.power.win)}`), C
        }
        return {
            all: W
        }
    }
    var Ut = {
        create: En
    };
    var ie = {
        PayHoldBoostEvent: Qt,
        BoostWin: Kt,
        PayHoldJackpotEvent: Re,
        HoldMysteryEvent: Ut
    };

    function bn($, p) {
        let {
            context: H,
            transform: W
        } = p, {
            events: s,
            hold: C
        } = H.power, S = Dt.create($, p, C);
        S.group.transform(W);
        let Y, R = s.filter(L => ie[L.name]),
            w = [];
        for (let L = 0; L < R.length; L++) {
            let O = R[L],
                h = ie[O.name].create({
                    snap: $,
                    data: p,
                    board: S,
                    event: O
                });
            if (w.push([h]), O.name == "PayHoldBoostEvent") {
                let l = ie.BoostWin.create({
                    snap: $,
                    data: p,
                    board: S,
                    event: O
                });
                w.push([l])
            }
        }
        if (w.length) {
            let l = function() {
                    Y && Y.remove(), O.next(h);
                    let [v, c] = w[h];
                    Y = v.next(c), h = h + 1 == w.length ? h = 0 : h + 1
                },
                L = Zt.create({
                    snap: $,
                    data: p,
                    board: S
                });
            w.length > 1 ? (w.unshift([L, w.map(([v]) => v)]), $.click(l)) : w.splice(0, 1, [L, w.map(([v]) => v)]);
            let O = Bt.create($, p, w.length),
                h = 0;
            l()
        } else Bt.create($, p, 1), Y = Ut.create({
            snap: $,
            data: p,
            board: S
        }).all();
        return Y
    }
    var Oe = bn;

    function wn($, p) {
        let {
            context: H,
            transform: W
        } = p, s = $.group(), C = H.current == "bonus" ? H.power.hold : H.power.board, S = Dt.create($, p, C);
        return mt.create(s, p, "", ", Win:" + gt.coins(0)), S.group.transform(W), Bt.create($, p, 1), s
    }
    var oe = wn;

    function Cn({
        snap: $,
        data: p,
        event: H,
        board: W
    }) {
        function s() {
            let {
                count: C
            } = H, {
                styles: S
            } = p.config, Y = $.group();
            W.opacity.all(0), mt.create(Y, p);
            let R = `+${C} Freespins`;
            return Y.text("50%", "56%", R).attr(S.STYLE_TEXT_CENTER_RED), Y
        }
        return {
            next: s
        }
    }
    var ze = {
        create: Cn
    };

    function Bn({
        snap: $,
        data: p,
        event: H,
        board: W
    }) {
        function s(S) {
            let {
                positions: Y
            } = H, {
                transform: R
            } = p, w = S.group(), L = Y;
            return xt.draw(w, p, L).group.transform(R), w
        }

        function C() {
            let {
                positions: S
            } = H, {
                transform: Y
            } = p, R = $.group(), w = "bonus-" + S.length, L = ", Win:" + gt.coins(0), O = S;
            return W.opacity.all(.5), W.opacity.pos(O, 1), mt.create(R, p, w, L), xt.draw(R, p, O).group.transform(Y), R
        }
        return {
            all: s,
            next: C
        }
    }
    var qe = {
        create: Bn
    };

    function _n($, p, H) {
        let {
            cellWidth: W,
            cellHeight: s
        } = p, C = p.config.styles, S = $.group();
        $.group().attr(C.STYLE_LINE_GROUP);
        let {
            positions: Y
        } = H, R = [];
        for (let L = 0; L < Y.length; ++L) R.push(0);
        for (let L = 0; L < Y.length; ++L) Y[L][0] == L && (R[L] = Y[L][1]);
        let w = `M${W/2},${R[0]*s+s/2} `;
        for (let L = 0; L < R.length; ++L) w += `L${L*W+W/2},${R[L]*s+s/2} `;
        return S.path(w).attr(C.STYLE_LINE_INNER), S.path(w).attr(C.STYLE_LINE_OUTER), {
            group: S
        }
    }
    var se = {
        draw: _n
    };

    function An({
        snap: $,
        data: p,
        board: H,
        event: W
    }) {
        function s(Y) {
            let {
                transform: R
            } = p, w = Y.group(), {
                paths: L
            } = W, O = S(L);
            xt.draw(w, p, O).group.transform(R);
            for (let h = 0; h < L.length; h++) L[h].line != "scatter" && se.draw(w, p, L[h]).group.transform(R);
            return w
        }

        function C(Y) {
            let {
                transform: R
            } = p, w = $.group(), {
                paths: L
            } = W, {
                positions: O,
                win: h
            } = L[Y], l = `${Y+1}/${L.length}`, v = ", Win:" + gt.coins(h);
            return H.opacity.all(.5), H.opacity.pos(O, 1), mt.create(w, p, l, v), xt.draw(w, p, O).group.transform(R), L[Y].line != "scatter" && se.draw(w, p, L[Y]).group.transform(R), w
        }

        function S(Y) {
            let R = [];
            for (let w = 0; w < Y.length; w++)
                for (let L = 0; L < Y[w].positions.length; L++) {
                    let [O, h] = Y[w].positions[L];
                    R.some(([l, v]) => l == O && v == h) || R.push([O, h])
                }
            return R
        }
        return {
            all: s,
            next: C
        }
    }
    var Ie = {
        create: An
    };
    var ae = {
        PayTableEvent: Ie,
        PayHoldBoostEvent: Qt,
        FreespinAddEvent: ze,
        HoldWinEvent: qe,
        BoostWin: Kt
    };

    function Tn($, p) {
        let {
            context: H,
            transform: W
        } = p, {
            events: s
        } = H.power, C = Dt.create($, p, H.power.board);
        C.group.transform(W);
        let S, Y = s.filter(w => ae[w.name]),
            R = [];
        for (let w = 0; w < Y.length; w++) {
            let L = Y[w],
                O = ae[L.name].create({
                    snap: $,
                    data: p,
                    board: C,
                    event: L
                });
            if (L.name == "PayTableEvent")
                for (let h = 0; h < L.paths.length; h++) R.push([O, h]);
            else if (R.push([O]), L.name == "PayHoldBoostEvent") {
                let h = ae.BoostWin.create({
                    snap: $,
                    data: p,
                    board: C,
                    event: L
                });
                R.push([h])
            }
        }
        if (R.length) {
            let h = function() {
                    S && S.remove(), L.next(O);
                    let [l, v] = R[O];
                    S = l.next(v), O = O + 1 == R.length ? O = 0 : O + 1
                },
                w = Zt.create({
                    snap: $,
                    data: p,
                    board: C
                });
            R.length > 1 ? (R.unshift([w, R.map(([l]) => l)]), $.click(h)) : R.splice(0, 1, [w, R.map(([l]) => l)]);
            let L = Bt.create($, p, R.length),
                O = 0;
            h()
        } else Bt.create($, p, 1), S = Ut.create({
            snap: $,
            data: p,
            board: C
        }).all();
        return S
    }
    var fe = Tn;

    function Pn($, p, H = "") {
        let {
            context: W,
            config: s,
            action: C
        } = p, {
            styles: S,
            text: Y
        } = s, R = $.group();
        mt.create(R, p);
        let w = W.current,
            L = Y[w][C] + H || Y.default;
        return $.instance.text("50%", "56%", L).attr(S.STYLE_TEXT_CENTER_RED), R
    }
    var Nt = Pn;

    function Dn($, p) {
        let H = p.context.power.freespin.count.toString();
        Nt($, p, H)
    }
    var Ue = Dn;
    var Nn = {
        freespin_init: Ue,
        freespin_stop: Nt,
        bonus_init: Nt,
        bonus_stop: Nt,
        spin: fe,
        freespin: fe,
        respin: Oe,
        init: oe,
        restore: oe
    };

    function Ln($) {
        let {
            url: p,
            config: H,
            width: W,
            height: s,
            revision: C,
            action: S,
            panelFont: Y,
            panelHeight: R
        } = $, {
            context: w
        } = $.details, {
            power: L
        } = w, O = L.board.length, h = L.board[0].length, l = 3, c = (s - (R + l + 1)) / h - 2, u = (W - c * O) / 2 + 2, r = c, d = `T${u},${R+1}`, b = {
            width: W,
            height: s,
            context: w,
            panelFont: Y,
            panelHeight: R,
            boardMarginLeft: u,
            progressHeight: l,
            url: p,
            cellWidth: c,
            cellHeight: r,
            cellOffsetX: -1,
            cellOffsetY: -1,
            revision: C,
            config: H,
            transform: d,
            action: S
        }, m = ke.create(b), {
            instance: e
        } = m;
        return m.background(b), Nn[S](m, b), e.node
    }
    var te = {
        create: Ln
    };
    var ue = {
        error: Pe,
        spins: te,
        freespins: te,
        bonus: te,
        none: Ne,
        award: Ae
    };

    function kn($, p) {
        let H = $.replace(/$\/+/g, "");

        function W(s, C) {
            let {
                state: S,
                last_action: Y
            } = C;
            s.style.userSelect = "none", s.style.webkitUserSelect = "none";
            let R = s.clientHeight,
                w = s.clientWidth,
                L = R > 150 ? "1em" : "0.7em",
                O = R > 150 ? 20 : 12,
                h = {
                    action: Y,
                    url: H,
                    config: we,
                    width: w,
                    height: R,
                    details: C,
                    revision: p,
                    panelFont: L,
                    panelHeight: O
                };
            try {
                let l = (ue[S] || ue.none).create(h);
                l.style.cursor = "pointer", s.appendChild(l)
            } catch (l) {
                s.appendChild(ue.error.create(h))
            }
        }
        return {
            draw: W
        }
    }
    var Ge = {
        create: kn
    };
    var le = class {
        constructor(p, H) {
            this.drawer = Ge.create(p, H)
        }
        draw(p, H) {
            this.drawer.draw(p, H)
        }
    };
    window.Drawer = le;
})();