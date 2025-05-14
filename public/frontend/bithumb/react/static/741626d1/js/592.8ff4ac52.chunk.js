"use strict";
(self.webpackChunk_bithumb_pc = self.webpackChunk_bithumb_pc || []).push([
    [592], {
        10592: function(t, n, e) {
            e.d(n, {
                Nb1: function() {
                    return na
                },
                SOn: function() {
                    return ca
                },
                LLu: function() {
                    return M
                },
                y4O: function() {
                    return b
                },
                YFb: function() {
                    return i
                },
                $0Z: function() {
                    return ga
                },
                FdL: function() {
                    return Ma
                },
                m2c: function() {
                    return Qe
                },
                Wem: function() {
                    return o
                },
                Xae: function() {
                    return h
                },
                g_G: function() {
                    return s
                },
                jvg: function() {
                    return aa
                },
                Fp7: function() {
                    return a
                },
                VV$: function() {
                    return c
                },
                ve8: function() {
                    return fa
                },
                cx$: function() {
                    return Ao
                },
                tiA: function() {
                    return rr
                },
                BYU: function() {
                    return zr
                },
                Xf: function() {
                    return bo
                },
                KYF: function() {
                    return To
                },
                Ys: function() {
                    return ko
                }
            });

            function r(t, n) {
                return t < n ? -1 : t > n ? 1 : t >= n ? 0 : NaN
            }

            function i(t) {
                var n = t,
                    e = t;

                function i(t, n, r, i) {
                    for (null == r && (r = 0), null == i && (i = t.length); r < i;) {
                        var u = r + i >>> 1;
                        e(t[u], n) < 0 ? r = u + 1 : i = u
                    }
                    return r
                }
                return 1 === t.length && (n = function(n, e) {
                    return t(n) - e
                }, e = function(t) {
                    return function(n, e) {
                        return r(t(n), e)
                    }
                }(t)), {
                    left: i,
                    center: function(t, e, r, u) {
                        null == r && (r = 0), null == u && (u = t.length);
                        var o = i(t, e, r, u - 1);
                        return o > r && n(t[o - 1], e) > -n(t[o], e) ? o - 1 : o
                    },
                    right: function(t, n, r, i) {
                        for (null == r && (r = 0), null == i && (i = t.length); r < i;) {
                            var u = r + i >>> 1;
                            e(t[u], n) > 0 ? i = u : r = u + 1
                        }
                        return r
                    }
                }
            }
            var u = e(26597);

            function o(t, n) {
                var e, r;
                if (void 0 === n) {
                    var i, o = (0, u.Z)(t);
                    try {
                        for (o.s(); !(i = o.n()).done;) {
                            var a = i.value;
                            null != a && (void 0 === e ? a >= a && (e = r = a) : (e > a && (e = a), r < a && (r = a)))
                        }
                    } catch (h) {
                        o.e(h)
                    } finally {
                        o.f()
                    }
                } else {
                    var c, l = -1,
                        s = (0, u.Z)(t);
                    try {
                        for (s.s(); !(c = s.n()).done;) {
                            var f = c.value;
                            null != (f = n(f, ++l, t)) && (void 0 === e ? f >= f && (e = r = f) : (e > f && (e = f), r < f && (r = f)))
                        }
                    } catch (h) {
                        s.e(h)
                    } finally {
                        s.f()
                    }
                }
                return [e, r]
            }

            function a(t, n) {
                var e;
                if (void 0 === n) {
                    var r, i = (0, u.Z)(t);
                    try {
                        for (i.s(); !(r = i.n()).done;) {
                            var o = r.value;
                            null != o && (e < o || void 0 === e && o >= o) && (e = o)
                        }
                    } catch (f) {
                        i.e(f)
                    } finally {
                        i.f()
                    }
                } else {
                    var a, c = -1,
                        l = (0, u.Z)(t);
                    try {
                        for (l.s(); !(a = l.n()).done;) {
                            var s = a.value;
                            null != (s = n(s, ++c, t)) && (e < s || void 0 === e && s >= s) && (e = s)
                        }
                    } catch (f) {
                        l.e(f)
                    } finally {
                        l.f()
                    }
                }
                return e
            }

            function c(t, n) {
                var e;
                if (void 0 === n) {
                    var r, i = (0, u.Z)(t);
                    try {
                        for (i.s(); !(r = i.n()).done;) {
                            var o = r.value;
                            null != o && (e > o || void 0 === e && o >= o) && (e = o)
                        }
                    } catch (f) {
                        i.e(f)
                    } finally {
                        i.f()
                    }
                } else {
                    var a, c = -1,
                        l = (0, u.Z)(t);
                    try {
                        for (l.s(); !(a = l.n()).done;) {
                            var s = a.value;
                            null != (s = n(s, ++c, t)) && (e > s || void 0 === e && s >= s) && (e = s)
                        }
                    } catch (f) {
                        l.e(f)
                    } finally {
                        l.f()
                    }
                }
                return e
            }

            function l(t, n) {
                var e, r = -1,
                    i = -1;
                if (void 0 === n) {
                    var o, a = (0, u.Z)(t);
                    try {
                        for (a.s(); !(o = a.n()).done;) {
                            var c = o.value;
                            ++i, null != c && (e > c || void 0 === e && c >= c) && (e = c, r = i)
                        }
                    } catch (h) {
                        a.e(h)
                    } finally {
                        a.f()
                    }
                } else {
                    var l, s = (0, u.Z)(t);
                    try {
                        for (s.s(); !(l = s.n()).done;) {
                            var f = l.value;
                            null != (f = n(f, ++i, t)) && (e > f || void 0 === e && f >= f) && (e = f, r = i)
                        }
                    } catch (h) {
                        s.e(h)
                    } finally {
                        s.f()
                    }
                }
                return r
            }

            function s(t) {
                var n, e = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : r;
                if (1 === e.length) return l(t, e);
                var i, o = -1,
                    a = -1,
                    c = (0, u.Z)(t);
                try {
                    for (c.s(); !(i = c.n()).done;) {
                        var s = i.value;
                        ++a, (o < 0 ? 0 === e(s, s) : e(s, n) < 0) && (n = s, o = a)
                    }
                } catch (f) {
                    c.e(f)
                } finally {
                    c.f()
                }
                return o
            }

            function f(t, n) {
                var e, r = -1,
                    i = -1;
                if (void 0 === n) {
                    var o, a = (0, u.Z)(t);
                    try {
                        for (a.s(); !(o = a.n()).done;) {
                            var c = o.value;
                            ++i, null != c && (e < c || void 0 === e && c >= c) && (e = c, r = i)
                        }
                    } catch (h) {
                        a.e(h)
                    } finally {
                        a.f()
                    }
                } else {
                    var l, s = (0, u.Z)(t);
                    try {
                        for (s.s(); !(l = s.n()).done;) {
                            var f = l.value;
                            null != (f = n(f, ++i, t)) && (e < f || void 0 === e && f >= f) && (e = f, r = i)
                        }
                    } catch (h) {
                        s.e(h)
                    } finally {
                        s.f()
                    }
                }
                return r
            }

            function h(t) {
                var n, e = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : r;
                if (1 === e.length) return f(t, e);
                var i, o = -1,
                    a = -1,
                    c = (0, u.Z)(t);
                try {
                    for (c.s(); !(i = c.n()).done;) {
                        var l = i.value;
                        ++a, (o < 0 ? 0 === e(l, l) : e(l, n) > 0) && (n = l, o = a)
                    }
                } catch (s) {
                    c.e(s)
                } finally {
                    c.f()
                }
                return o
            }
            var p = Array.prototype.slice;

            function g(t) {
                return t
            }
            var y = 1e-6;

            function v(t) {
                return "translate(" + t + ",0)"
            }

            function d(t) {
                return "translate(0," + t + ")"
            }

            function _(t) {
                return function(n) {
                    return +t(n)
                }
            }

            function m(t, n) {
                return n = Math.max(0, t.bandwidth() - 2 * n) / 2, t.round() && (n = Math.round(n)),
                    function(e) {
                        return +t(e) + n
                    }
            }

            function w() {
                return !this.__axis
            }

            function x(t, n) {
                var e = [],
                    r = null,
                    i = null,
                    u = 6,
                    o = 6,
                    a = 3,
                    c = "undefined" !== typeof window && window.devicePixelRatio > 1 ? 0 : .5,
                    l = 1 === t || 4 === t ? -1 : 1,
                    s = 4 === t || 2 === t ? "x" : "y",
                    f = 1 === t || 3 === t ? v : d;

                function h(h) {
                    var p = null == r ? n.ticks ? n.ticks.apply(n, e) : n.domain() : r,
                        v = null == i ? n.tickFormat ? n.tickFormat.apply(n, e) : g : i,
                        d = Math.max(u, 0) + a,
                        x = n.range(),
                        M = +x[0] + c,
                        b = +x[x.length - 1] + c,
                        T = (n.bandwidth ? m : _)(n.copy(), c),
                        A = h.selection ? h.selection() : h,
                        k = A.selectAll(".domain").data([null]),
                        C = A.selectAll(".tick").data(p, n).order(),
                        N = C.exit(),
                        S = C.enter().append("g").attr("class", "tick"),
                        D = C.select("line"),
                        U = C.select("text");
                    k = k.merge(k.enter().insert("path", ".tick").attr("class", "domain").attr("stroke", "currentColor")), C = C.merge(S), D = D.merge(S.append("line").attr("stroke", "currentColor").attr(s + "2", l * u)), U = U.merge(S.append("text").attr("fill", "currentColor").attr(s, l * d).attr("dy", 1 === t ? "0em" : 3 === t ? "0.71em" : "0.32em")), h !== A && (k = k.transition(h), C = C.transition(h), D = D.transition(h), U = U.transition(h), N = N.transition(h).attr("opacity", y).attr("transform", (function(t) {
                        return isFinite(t = T(t)) ? f(t + c) : this.getAttribute("transform")
                    })), S.attr("opacity", y).attr("transform", (function(t) {
                        var n = this.parentNode.__axis;
                        return f((n && isFinite(n = n(t)) ? n : T(t)) + c)
                    }))), N.remove(), k.attr("d", 4 === t || 2 === t ? o ? "M" + l * o + "," + M + "H" + c + "V" + b + "H" + l * o : "M" + c + "," + M + "V" + b : o ? "M" + M + "," + l * o + "V" + c + "H" + b + "V" + l * o : "M" + M + "," + c + "H" + b), C.attr("opacity", 1).attr("transform", (function(t) {
                        return f(T(t) + c)
                    })), D.attr(s + "2", l * u), U.attr(s, l * d).text(v), A.filter(w).attr("fill", "none").attr("font-size", 10).attr("font-family", "sans-serif").attr("text-anchor", 2 === t ? "start" : 4 === t ? "end" : "middle"), A.each((function() {
                        this.__axis = T
                    }))
                }
                return h.scale = function(t) {
                    return arguments.length ? (n = t, h) : n
                }, h.ticks = function() {
                    return e = p.call(arguments), h
                }, h.tickArguments = function(t) {
                    return arguments.length ? (e = null == t ? [] : p.call(t), h) : e.slice()
                }, h.tickValues = function(t) {
                    return arguments.length ? (r = null == t ? null : p.call(t), h) : r && r.slice()
                }, h.tickFormat = function(t) {
                    return arguments.length ? (i = t, h) : i
                }, h.tickSize = function(t) {
                    return arguments.length ? (u = o = +t, h) : u
                }, h.tickSizeInner = function(t) {
                    return arguments.length ? (u = +t, h) : u
                }, h.tickSizeOuter = function(t) {
                    return arguments.length ? (o = +t, h) : o
                }, h.tickPadding = function(t) {
                    return arguments.length ? (a = +t, h) : a
                }, h.offset = function(t) {
                    return arguments.length ? (c = +t, h) : c
                }, h
            }

            function M(t) {
                return x(3, t)
            }

            function b(t) {
                return x(4, t)
            }
            var T = e(29721);

            function A() {}

            function k(t) {
                return null == t ? A : function() {
                    return this.querySelector(t)
                }
            }

            function C(t) {
                return "object" === typeof t && "length" in t ? t : Array.from(t)
            }

            function N() {
                return []
            }

            function S(t) {
                return null == t ? N : function() {
                    return this.querySelectorAll(t)
                }
            }

            function D(t) {
                return function() {
                    return this.matches(t)
                }
            }

            function U(t) {
                return function(n) {
                    return n.matches(t)
                }
            }
            var E = Array.prototype.find;

            function Y() {
                return this.firstElementChild
            }
            var F = Array.prototype.filter;

            function H() {
                return this.children
            }

            function L(t) {
                return new Array(t.length)
            }

            function Z(t, n) {
                this.ownerDocument = t.ownerDocument, this.namespaceURI = t.namespaceURI, this._next = null, this._parent = t, this.__data__ = n
            }

            function P(t) {
                return function() {
                    return t
                }
            }

            function X(t, n, e, r, i, u) {
                for (var o, a = 0, c = n.length, l = u.length; a < l; ++a)(o = n[a]) ? (o.__data__ = u[a], r[a] = o) : e[a] = new Z(t, u[a]);
                for (; a < c; ++a)(o = n[a]) && (i[a] = o)
            }

            function q(t, n, e, r, i, u, o) {
                var a, c, l, s = new Map,
                    f = n.length,
                    h = u.length,
                    p = new Array(f);
                for (a = 0; a < f; ++a)(c = n[a]) && (p[a] = l = o.call(c, c.__data__, a, n) + "", s.has(l) ? i[a] = c : s.set(l, c));
                for (a = 0; a < h; ++a) l = o.call(t, u[a], a, u) + "", (c = s.get(l)) ? (r[a] = c, c.__data__ = u[a], s.delete(l)) : e[a] = new Z(t, u[a]);
                for (a = 0; a < f; ++a)(c = n[a]) && s.get(p[a]) === c && (i[a] = c)
            }

            function O(t) {
                return t.__data__
            }

            function j(t, n) {
                return t < n ? -1 : t > n ? 1 : t >= n ? 0 : NaN
            }
            Z.prototype = {
                constructor: Z,
                appendChild: function(t) {
                    return this._parent.insertBefore(t, this._next)
                },
                insertBefore: function(t, n) {
                    return this._parent.insertBefore(t, n)
                },
                querySelector: function(t) {
                    return this._parent.querySelector(t)
                },
                querySelectorAll: function(t) {
                    return this._parent.querySelectorAll(t)
                }
            };
            var V = "http://www.w3.org/1999/xhtml",
                z = {
                    svg: "http://www.w3.org/2000/svg",
                    xhtml: V,
                    xlink: "http://www.w3.org/1999/xlink",
                    xml: "http://www.w3.org/XML/1998/namespace",
                    xmlns: "http://www.w3.org/2000/xmlns/"
                };

            function R(t) {
                var n = t += "",
                    e = n.indexOf(":");
                return e >= 0 && "xmlns" !== (n = t.slice(0, e)) && (t = t.slice(e + 1)), z.hasOwnProperty(n) ? {
                    space: z[n],
                    local: t
                } : t
            }

            function I(t) {
                return function() {
                    this.removeAttribute(t)
                }
            }

            function $(t) {
                return function() {
                    this.removeAttributeNS(t.space, t.local)
                }
            }

            function B(t, n) {
                return function() {
                    this.setAttribute(t, n)
                }
            }

            function W(t, n) {
                return function() {
                    this.setAttributeNS(t.space, t.local, n)
                }
            }

            function G(t, n) {
                return function() {
                    var e = n.apply(this, arguments);
                    null == e ? this.removeAttribute(t) : this.setAttribute(t, e)
                }
            }

            function Q(t, n) {
                return function() {
                    var e = n.apply(this, arguments);
                    null == e ? this.removeAttributeNS(t.space, t.local) : this.setAttributeNS(t.space, t.local, e)
                }
            }

            function J(t) {
                return t.ownerDocument && t.ownerDocument.defaultView || t.document && t || t.defaultView
            }

            function K(t) {
                return function() {
                    this.style.removeProperty(t)
                }
            }

            function tt(t, n, e) {
                return function() {
                    this.style.setProperty(t, n, e)
                }
            }

            function nt(t, n, e) {
                return function() {
                    var r = n.apply(this, arguments);
                    null == r ? this.style.removeProperty(t) : this.style.setProperty(t, r, e)
                }
            }

            function et(t, n) {
                return t.style.getPropertyValue(n) || J(t).getComputedStyle(t, null).getPropertyValue(n)
            }

            function rt(t) {
                return function() {
                    delete this[t]
                }
            }

            function it(t, n) {
                return function() {
                    this[t] = n
                }
            }

            function ut(t, n) {
                return function() {
                    var e = n.apply(this, arguments);
                    null == e ? delete this[t] : this[t] = e
                }
            }

            function ot(t) {
                return t.trim().split(/^|\s+/)
            }

            function at(t) {
                return t.classList || new ct(t)
            }

            function ct(t) {
                this._node = t, this._names = ot(t.getAttribute("class") || "")
            }

            function lt(t, n) {
                for (var e = at(t), r = -1, i = n.length; ++r < i;) e.add(n[r])
            }

            function st(t, n) {
                for (var e = at(t), r = -1, i = n.length; ++r < i;) e.remove(n[r])
            }

            function ft(t) {
                return function() {
                    lt(this, t)
                }
            }

            function ht(t) {
                return function() {
                    st(this, t)
                }
            }

            function pt(t, n) {
                return function() {
                    (n.apply(this, arguments) ? lt : st)(this, t)
                }
            }

            function gt() {
                this.textContent = ""
            }

            function yt(t) {
                return function() {
                    this.textContent = t
                }
            }

            function vt(t) {
                return function() {
                    var n = t.apply(this, arguments);
                    this.textContent = null == n ? "" : n
                }
            }

            function dt() {
                this.innerHTML = ""
            }

            function _t(t) {
                return function() {
                    this.innerHTML = t
                }
            }

            function mt(t) {
                return function() {
                    var n = t.apply(this, arguments);
                    this.innerHTML = null == n ? "" : n
                }
            }

            function wt() {
                this.nextSibling && this.parentNode.appendChild(this)
            }

            function xt() {
                this.previousSibling && this.parentNode.insertBefore(this, this.parentNode.firstChild)
            }

            function Mt(t) {
                return function() {
                    var n = this.ownerDocument,
                        e = this.namespaceURI;
                    return e === V && n.documentElement.namespaceURI === V ? n.createElement(t) : n.createElementNS(e, t)
                }
            }

            function bt(t) {
                return function() {
                    return this.ownerDocument.createElementNS(t.space, t.local)
                }
            }

            function Tt(t) {
                var n = R(t);
                return (n.local ? bt : Mt)(n)
            }

            function At() {
                return null
            }

            function kt() {
                var t = this.parentNode;
                t && t.removeChild(this)
            }

            function Ct() {
                var t = this.cloneNode(!1),
                    n = this.parentNode;
                return n ? n.insertBefore(t, this.nextSibling) : t
            }

            function Nt() {
                var t = this.cloneNode(!0),
                    n = this.parentNode;
                return n ? n.insertBefore(t, this.nextSibling) : t
            }

            function St(t) {
                return t.trim().split(/^|\s+/).map((function(t) {
                    var n = "",
                        e = t.indexOf(".");
                    return e >= 0 && (n = t.slice(e + 1), t = t.slice(0, e)), {
                        type: t,
                        name: n
                    }
                }))
            }

            function Dt(t) {
                return function() {
                    var n = this.__on;
                    if (n) {
                        for (var e, r = 0, i = -1, u = n.length; r < u; ++r) e = n[r], t.type && e.type !== t.type || e.name !== t.name ? n[++i] = e : this.removeEventListener(e.type, e.listener, e.options);
                        ++i ? n.length = i : delete this.__on
                    }
                }
            }

            function Ut(t, n, e) {
                return function() {
                    var r, i = this.__on,
                        u = function(t) {
                            return function(n) {
                                t.call(this, n, this.__data__)
                            }
                        }(n);
                    if (i)
                        for (var o = 0, a = i.length; o < a; ++o)
                            if ((r = i[o]).type === t.type && r.name === t.name) return this.removeEventListener(r.type, r.listener, r.options), this.addEventListener(r.type, r.listener = u, r.options = e), void(r.value = n);
                    this.addEventListener(t.type, u, e), r = {
                        type: t.type,
                        name: t.name,
                        value: n,
                        listener: u,
                        options: e
                    }, i ? i.push(r) : this.__on = [r]
                }
            }

            function Et(t, n, e) {
                var r = J(t),
                    i = r.CustomEvent;
                "function" === typeof i ? i = new i(n, e) : (i = r.document.createEvent("Event"), e ? (i.initEvent(n, e.bubbles, e.cancelable), i.detail = e.detail) : i.initEvent(n, !1, !1)), t.dispatchEvent(i)
            }

            function Yt(t, n) {
                return function() {
                    return Et(this, t, n)
                }
            }

            function Ft(t, n) {
                return function() {
                    return Et(this, t, n.apply(this, arguments))
                }
            }
            ct.prototype = {
                add: function(t) {
                    this._names.indexOf(t) < 0 && (this._names.push(t), this._node.setAttribute("class", this._names.join(" ")))
                },
                remove: function(t) {
                    var n = this._names.indexOf(t);
                    n >= 0 && (this._names.splice(n, 1), this._node.setAttribute("class", this._names.join(" ")))
                },
                contains: function(t) {
                    return this._names.indexOf(t) >= 0
                }
            };
            var Ht = e(61879),
                Lt = (0, Ht.Z)().mark(Zt);

            function Zt() {
                var t, n, e, r, i, u, o;
                return (0, Ht.Z)().wrap((function(a) {
                    for (;;) switch (a.prev = a.next) {
                        case 0:
                            t = this._groups, n = 0, e = t.length;
                        case 1:
                            if (!(n < e)) {
                                a.next = 13;
                                break
                            }
                            r = t[n], i = 0, u = r.length;
                        case 3:
                            if (!(i < u)) {
                                a.next = 10;
                                break
                            }
                            if (!(o = r[i])) {
                                a.next = 7;
                                break
                            }
                            return a.next = 7, o;
                        case 7:
                            ++i, a.next = 3;
                            break;
                        case 10:
                            ++n, a.next = 1;
                            break;
                        case 13:
                        case "end":
                            return a.stop()
                    }
                }), Lt, this)
            }
            var Pt = [null];

            function Xt(t, n) {
                this._groups = t, this._parents = n
            }

            function qt() {
                return new Xt([
                    [document.documentElement]
                ], Pt)
            }
            Xt.prototype = qt.prototype = (0, T.Z)({
                constructor: Xt,
                select: function(t) {
                    "function" !== typeof t && (t = k(t));
                    for (var n = this._groups, e = n.length, r = new Array(e), i = 0; i < e; ++i)
                        for (var u, o, a = n[i], c = a.length, l = r[i] = new Array(c), s = 0; s < c; ++s)(u = a[s]) && (o = t.call(u, u.__data__, s, a)) && ("__data__" in u && (o.__data__ = u.__data__), l[s] = o);
                    return new Xt(r, this._parents)
                },
                selectAll: function(t) {
                    t = "function" === typeof t ? function(t) {
                        return function() {
                            var n = t.apply(this, arguments);
                            return null == n ? [] : C(n)
                        }
                    }(t) : S(t);
                    for (var n = this._groups, e = n.length, r = [], i = [], u = 0; u < e; ++u)
                        for (var o, a = n[u], c = a.length, l = 0; l < c; ++l)(o = a[l]) && (r.push(t.call(o, o.__data__, l, a)), i.push(o));
                    return new Xt(r, i)
                },
                selectChild: function(t) {
                    return this.select(null == t ? Y : function(t) {
                        return function() {
                            return E.call(this.children, t)
                        }
                    }("function" === typeof t ? t : U(t)))
                },
                selectChildren: function(t) {
                    return this.selectAll(null == t ? H : function(t) {
                        return function() {
                            return F.call(this.children, t)
                        }
                    }("function" === typeof t ? t : U(t)))
                },
                filter: function(t) {
                    "function" !== typeof t && (t = D(t));
                    for (var n = this._groups, e = n.length, r = new Array(e), i = 0; i < e; ++i)
                        for (var u, o = n[i], a = o.length, c = r[i] = [], l = 0; l < a; ++l)(u = o[l]) && t.call(u, u.__data__, l, o) && c.push(u);
                    return new Xt(r, this._parents)
                },
                data: function(t, n) {
                    if (!arguments.length) return Array.from(this, O);
                    var e = n ? q : X,
                        r = this._parents,
                        i = this._groups;
                    "function" !== typeof t && (t = P(t));
                    for (var u = i.length, o = new Array(u), a = new Array(u), c = new Array(u), l = 0; l < u; ++l) {
                        var s = r[l],
                            f = i[l],
                            h = f.length,
                            p = C(t.call(s, s && s.__data__, l, r)),
                            g = p.length,
                            y = a[l] = new Array(g),
                            v = o[l] = new Array(g),
                            d = c[l] = new Array(h);
                        e(s, f, y, v, d, p, n);
                        for (var _, m, w = 0, x = 0; w < g; ++w)
                            if (_ = y[w]) {
                                for (w >= x && (x = w + 1); !(m = v[x]) && ++x < g;);
                                _._next = m || null
                            }
                    }
                    return (o = new Xt(o, r))._enter = a, o._exit = c, o
                },
                enter: function() {
                    return new Xt(this._enter || this._groups.map(L), this._parents)
                },
                exit: function() {
                    return new Xt(this._exit || this._groups.map(L), this._parents)
                },
                join: function(t, n, e) {
                    var r = this.enter(),
                        i = this,
                        u = this.exit();
                    return r = "function" === typeof t ? t(r) : r.append(t + ""), null != n && (i = n(i)), null == e ? u.remove() : e(u), r && i ? r.merge(i).order() : i
                },
                merge: function(t) {
                    if (!(t instanceof Xt)) throw new Error("invalid merge");
                    for (var n = this._groups, e = t._groups, r = n.length, i = e.length, u = Math.min(r, i), o = new Array(r), a = 0; a < u; ++a)
                        for (var c, l = n[a], s = e[a], f = l.length, h = o[a] = new Array(f), p = 0; p < f; ++p)(c = l[p] || s[p]) && (h[p] = c);
                    for (; a < r; ++a) o[a] = n[a];
                    return new Xt(o, this._parents)
                },
                selection: function() {
                    return this
                },
                order: function() {
                    for (var t = this._groups, n = -1, e = t.length; ++n < e;)
                        for (var r, i = t[n], u = i.length - 1, o = i[u]; --u >= 0;)(r = i[u]) && (o && 4 ^ r.compareDocumentPosition(o) && o.parentNode.insertBefore(r, o), o = r);
                    return this
                },
                sort: function(t) {
                    function n(n, e) {
                        return n && e ? t(n.__data__, e.__data__) : !n - !e
                    }
                    t || (t = j);
                    for (var e = this._groups, r = e.length, i = new Array(r), u = 0; u < r; ++u) {
                        for (var o, a = e[u], c = a.length, l = i[u] = new Array(c), s = 0; s < c; ++s)(o = a[s]) && (l[s] = o);
                        l.sort(n)
                    }
                    return new Xt(i, this._parents).order()
                },
                call: function() {
                    var t = arguments[0];
                    return arguments[0] = this, t.apply(null, arguments), this
                },
                nodes: function() {
                    return Array.from(this)
                },
                node: function() {
                    for (var t = this._groups, n = 0, e = t.length; n < e; ++n)
                        for (var r = t[n], i = 0, u = r.length; i < u; ++i) {
                            var o = r[i];
                            if (o) return o
                        }
                    return null
                },
                size: function() {
                    var t, n = 0,
                        e = (0, u.Z)(this);
                    try {
                        for (e.s(); !(t = e.n()).done;) {
                            t.value;
                            ++n
                        }
                    } catch (r) {
                        e.e(r)
                    } finally {
                        e.f()
                    }
                    return n
                },
                empty: function() {
                    return !this.node()
                },
                each: function(t) {
                    for (var n = this._groups, e = 0, r = n.length; e < r; ++e)
                        for (var i, u = n[e], o = 0, a = u.length; o < a; ++o)(i = u[o]) && t.call(i, i.__data__, o, u);
                    return this
                },
                attr: function(t, n) {
                    var e = R(t);
                    if (arguments.length < 2) {
                        var r = this.node();
                        return e.local ? r.getAttributeNS(e.space, e.local) : r.getAttribute(e)
                    }
                    return this.each((null == n ? e.local ? $ : I : "function" === typeof n ? e.local ? Q : G : e.local ? W : B)(e, n))
                },
                style: function(t, n, e) {
                    return arguments.length > 1 ? this.each((null == n ? K : "function" === typeof n ? nt : tt)(t, n, null == e ? "" : e)) : et(this.node(), t)
                },
                property: function(t, n) {
                    return arguments.length > 1 ? this.each((null == n ? rt : "function" === typeof n ? ut : it)(t, n)) : this.node()[t]
                },
                classed: function(t, n) {
                    var e = ot(t + "");
                    if (arguments.length < 2) {
                        for (var r = at(this.node()), i = -1, u = e.length; ++i < u;)
                            if (!r.contains(e[i])) return !1;
                        return !0
                    }
                    return this.each(("function" === typeof n ? pt : n ? ft : ht)(e, n))
                },
                text: function(t) {
                    return arguments.length ? this.each(null == t ? gt : ("function" === typeof t ? vt : yt)(t)) : this.node().textContent
                },
                html: function(t) {
                    return arguments.length ? this.each(null == t ? dt : ("function" === typeof t ? mt : _t)(t)) : this.node().innerHTML
                },
                raise: function() {
                    return this.each(wt)
                },
                lower: function() {
                    return this.each(xt)
                },
                append: function(t) {
                    var n = "function" === typeof t ? t : Tt(t);
                    return this.select((function() {
                        return this.appendChild(n.apply(this, arguments))
                    }))
                },
                insert: function(t, n) {
                    var e = "function" === typeof t ? t : Tt(t),
                        r = null == n ? At : "function" === typeof n ? n : k(n);
                    return this.select((function() {
                        return this.insertBefore(e.apply(this, arguments), r.apply(this, arguments) || null)
                    }))
                },
                remove: function() {
                    return this.each(kt)
                },
                clone: function(t) {
                    return this.select(t ? Nt : Ct)
                },
                datum: function(t) {
                    return arguments.length ? this.property("__data__", t) : this.node().__data__
                },
                on: function(t, n, e) {
                    var r, i, u = St(t + ""),
                        o = u.length;
                    if (!(arguments.length < 2)) {
                        for (a = n ? Ut : Dt, r = 0; r < o; ++r) this.each(a(u[r], n, e));
                        return this
                    }
                    var a = this.node().__on;
                    if (a)
                        for (var c, l = 0, s = a.length; l < s; ++l)
                            for (r = 0, c = a[l]; r < o; ++r)
                                if ((i = u[r]).type === c.type && i.name === c.name) return c.value
                },
                dispatch: function(t, n) {
                    return this.each(("function" === typeof n ? Ft : Yt)(t, n))
                }
            }, Symbol.iterator, Zt);
            var Ot = qt,
                jt = {
                    value: function() {}
                };

            function Vt() {
                for (var t, n = 0, e = arguments.length, r = {}; n < e; ++n) {
                    if (!(t = arguments[n] + "") || t in r || /[\s.]/.test(t)) throw new Error("illegal type: " + t);
                    r[t] = []
                }
                return new zt(r)
            }

            function zt(t) {
                this._ = t
            }

            function Rt(t, n) {
                return t.trim().split(/^|\s+/).map((function(t) {
                    var e = "",
                        r = t.indexOf(".");
                    if (r >= 0 && (e = t.slice(r + 1), t = t.slice(0, r)), t && !n.hasOwnProperty(t)) throw new Error("unknown type: " + t);
                    return {
                        type: t,
                        name: e
                    }
                }))
            }

            function It(t, n) {
                for (var e, r = 0, i = t.length; r < i; ++r)
                    if ((e = t[r]).name === n) return e.value
            }

            function $t(t, n, e) {
                for (var r = 0, i = t.length; r < i; ++r)
                    if (t[r].name === n) {
                        t[r] = jt, t = t.slice(0, r).concat(t.slice(r + 1));
                        break
                    }
                return null != e && t.push({
                    name: n,
                    value: e
                }), t
            }
            zt.prototype = Vt.prototype = {
                constructor: zt,
                on: function(t, n) {
                    var e, r = this._,
                        i = Rt(t + "", r),
                        u = -1,
                        o = i.length;
                    if (!(arguments.length < 2)) {
                        if (null != n && "function" !== typeof n) throw new Error("invalid callback: " + n);
                        for (; ++u < o;)
                            if (e = (t = i[u]).type) r[e] = $t(r[e], t.name, n);
                            else if (null == n)
                            for (e in r) r[e] = $t(r[e], t.name, null);
                        return this
                    }
                    for (; ++u < o;)
                        if ((e = (t = i[u]).type) && (e = It(r[e], t.name))) return e
                },
                copy: function() {
                    var t = {},
                        n = this._;
                    for (var e in n) t[e] = n[e].slice();
                    return new zt(t)
                },
                call: function(t, n) {
                    if ((e = arguments.length - 2) > 0)
                        for (var e, r, i = new Array(e), u = 0; u < e; ++u) i[u] = arguments[u + 2];
                    if (!this._.hasOwnProperty(t)) throw new Error("unknown type: " + t);
                    for (u = 0, e = (r = this._[t]).length; u < e; ++u) r[u].value.apply(n, i)
                },
                apply: function(t, n, e) {
                    if (!this._.hasOwnProperty(t)) throw new Error("unknown type: " + t);
                    for (var r = this._[t], i = 0, u = r.length; i < u; ++i) r[i].value.apply(n, e)
                }
            };
            var Bt, Wt, Gt = Vt,
                Qt = 0,
                Jt = 0,
                Kt = 0,
                tn = 0,
                nn = 0,
                en = 0,
                rn = "object" === typeof performance && performance.now ? performance : Date,
                un = "object" === typeof window && window.requestAnimationFrame ? window.requestAnimationFrame.bind(window) : function(t) {
                    setTimeout(t, 17)
                };

            function on() {
                return nn || (un(an), nn = rn.now() + en)
            }

            function an() {
                nn = 0
            }

            function cn() {
                this._call = this._time = this._next = null
            }

            function ln(t, n, e) {
                var r = new cn;
                return r.restart(t, n, e), r
            }

            function sn() {
                nn = (tn = rn.now()) + en, Qt = Jt = 0;
                try {
                    ! function() {
                        on(), ++Qt;
                        for (var t, n = Bt; n;)(t = nn - n._time) >= 0 && n._call.call(null, t), n = n._next;
                        --Qt
                    }()
                } finally {
                    Qt = 0,
                        function() {
                            var t, n, e = Bt,
                                r = 1 / 0;
                            for (; e;) e._call ? (r > e._time && (r = e._time), t = e, e = e._next) : (n = e._next, e._next = null, e = t ? t._next = n : Bt = n);
                            Wt = t, hn(r)
                        }(), nn = 0
                }
            }

            function fn() {
                var t = rn.now(),
                    n = t - tn;
                n > 1e3 && (en -= n, tn = t)
            }

            function hn(t) {
                Qt || (Jt && (Jt = clearTimeout(Jt)), t - nn > 24 ? (t < 1 / 0 && (Jt = setTimeout(sn, t - rn.now() - en)), Kt && (Kt = clearInterval(Kt))) : (Kt || (tn = rn.now(), Kt = setInterval(fn, 1e3)), Qt = 1, un(sn)))
            }

            function pn(t, n, e) {
                var r = new cn;
                return n = null == n ? 0 : +n, r.restart((function(e) {
                    r.stop(), t(e + n)
                }), n, e), r
            }
            cn.prototype = ln.prototype = {
                constructor: cn,
                restart: function(t, n, e) {
                    if ("function" !== typeof t) throw new TypeError("callback is not a function");
                    e = (null == e ? on() : +e) + (null == n ? 0 : +n), this._next || Wt === this || (Wt ? Wt._next = this : Bt = this, Wt = this), this._call = t, this._time = e, hn()
                },
                stop: function() {
                    this._call && (this._call = null, this._time = 1 / 0, hn())
                }
            };
            var gn = Gt("start", "end", "cancel", "interrupt"),
                yn = [];

            function vn(t, n, e, r, i, u) {
                var o = t.__transition;
                if (o) {
                    if (e in o) return
                } else t.__transition = {};
                ! function(t, n, e) {
                    var r, i = t.__transition;

                    function u(t) {
                        e.state = 1, e.timer.restart(o, e.delay, e.time), e.delay <= t && o(t - e.delay)
                    }

                    function o(u) {
                        var l, s, f, h;
                        if (1 !== e.state) return c();
                        for (l in i)
                            if ((h = i[l]).name === e.name) {
                                if (3 === h.state) return pn(o);
                                4 === h.state ? (h.state = 6, h.timer.stop(), h.on.call("interrupt", t, t.__data__, h.index, h.group), delete i[l]) : +l < n && (h.state = 6, h.timer.stop(), h.on.call("cancel", t, t.__data__, h.index, h.group), delete i[l])
                            }
                        if (pn((function() {
                                3 === e.state && (e.state = 4, e.timer.restart(a, e.delay, e.time), a(u))
                            })), e.state = 2, e.on.call("start", t, t.__data__, e.index, e.group), 2 === e.state) {
                            for (e.state = 3, r = new Array(f = e.tween.length), l = 0, s = -1; l < f; ++l)(h = e.tween[l].value.call(t, t.__data__, e.index, e.group)) && (r[++s] = h);
                            r.length = s + 1
                        }
                    }

                    function a(n) {
                        for (var i = n < e.duration ? e.ease.call(null, n / e.duration) : (e.timer.restart(c), e.state = 5, 1), u = -1, o = r.length; ++u < o;) r[u].call(t, i);
                        5 === e.state && (e.on.call("end", t, t.__data__, e.index, e.group), c())
                    }

                    function c() {
                        for (var r in e.state = 6, e.timer.stop(), delete i[n], i) return;
                        delete t.__transition
                    }
                    i[n] = e, e.timer = ln(u, 0, e.time)
                }(t, e, {
                    name: n,
                    index: r,
                    group: i,
                    on: gn,
                    tween: yn,
                    time: u.time,
                    delay: u.delay,
                    duration: u.duration,
                    ease: u.ease,
                    timer: null,
                    state: 0
                })
            }

            function dn(t, n) {
                var e = mn(t, n);
                if (e.state > 0) throw new Error("too late; already scheduled");
                return e
            }

            function _n(t, n) {
                var e = mn(t, n);
                if (e.state > 3) throw new Error("too late; already running");
                return e
            }

            function mn(t, n) {
                var e = t.__transition;
                if (!e || !(e = e[n])) throw new Error("transition not found");
                return e
            }

            function wn(t, n) {
                return t = +t, n = +n,
                    function(e) {
                        return t * (1 - e) + n * e
                    }
            }
            var xn, Mn = 180 / Math.PI,
                bn = {
                    translateX: 0,
                    translateY: 0,
                    rotate: 0,
                    skewX: 0,
                    scaleX: 1,
                    scaleY: 1
                };

            function Tn(t, n, e, r, i, u) {
                var o, a, c;
                return (o = Math.sqrt(t * t + n * n)) && (t /= o, n /= o), (c = t * e + n * r) && (e -= t * c, r -= n * c), (a = Math.sqrt(e * e + r * r)) && (e /= a, r /= a, c /= a), t * r < n * e && (t = -t, n = -n, c = -c, o = -o), {
                    translateX: i,
                    translateY: u,
                    rotate: Math.atan2(n, t) * Mn,
                    skewX: Math.atan(c) * Mn,
                    scaleX: o,
                    scaleY: a
                }
            }

            function An(t, n, e, r) {
                function i(t) {
                    return t.length ? t.pop() + " " : ""
                }
                return function(u, o) {
                    var a = [],
                        c = [];
                    return u = t(u), o = t(o),
                        function(t, r, i, u, o, a) {
                            if (t !== i || r !== u) {
                                var c = o.push("translate(", null, n, null, e);
                                a.push({
                                    i: c - 4,
                                    x: wn(t, i)
                                }, {
                                    i: c - 2,
                                    x: wn(r, u)
                                })
                            } else(i || u) && o.push("translate(" + i + n + u + e)
                        }(u.translateX, u.translateY, o.translateX, o.translateY, a, c),
                        function(t, n, e, u) {
                            t !== n ? (t - n > 180 ? n += 360 : n - t > 180 && (t += 360), u.push({
                                i: e.push(i(e) + "rotate(", null, r) - 2,
                                x: wn(t, n)
                            })) : n && e.push(i(e) + "rotate(" + n + r)
                        }(u.rotate, o.rotate, a, c),
                        function(t, n, e, u) {
                            t !== n ? u.push({
                                i: e.push(i(e) + "skewX(", null, r) - 2,
                                x: wn(t, n)
                            }) : n && e.push(i(e) + "skewX(" + n + r)
                        }(u.skewX, o.skewX, a, c),
                        function(t, n, e, r, u, o) {
                            if (t !== e || n !== r) {
                                var a = u.push(i(u) + "scale(", null, ",", null, ")");
                                o.push({
                                    i: a - 4,
                                    x: wn(t, e)
                                }, {
                                    i: a - 2,
                                    x: wn(n, r)
                                })
                            } else 1 === e && 1 === r || u.push(i(u) + "scale(" + e + "," + r + ")")
                        }(u.scaleX, u.scaleY, o.scaleX, o.scaleY, a, c), u = o = null,
                        function(t) {
                            for (var n, e = -1, r = c.length; ++e < r;) a[(n = c[e]).i] = n.x(t);
                            return a.join("")
                        }
                }
            }
            var kn = An((function(t) {
                    var n = new("function" === typeof DOMMatrix ? DOMMatrix : WebKitCSSMatrix)(t + "");
                    return n.isIdentity ? bn : Tn(n.a, n.b, n.c, n.d, n.e, n.f)
                }), "px, ", "px)", "deg)"),
                Cn = An((function(t) {
                    return null == t ? bn : (xn || (xn = document.createElementNS("http://www.w3.org/2000/svg", "g")), xn.setAttribute("transform", t), (t = xn.transform.baseVal.consolidate()) ? Tn((t = t.matrix).a, t.b, t.c, t.d, t.e, t.f) : bn)
                }), ", ", ")", ")");

            function Nn(t, n) {
                var e, r;
                return function() {
                    var i = _n(this, t),
                        u = i.tween;
                    if (u !== e)
                        for (var o = 0, a = (r = e = u).length; o < a; ++o)
                            if (r[o].name === n) {
                                (r = r.slice()).splice(o, 1);
                                break
                            }
                    i.tween = r
                }
            }

            function Sn(t, n, e) {
                var r, i;
                if ("function" !== typeof e) throw new Error;
                return function() {
                    var u = _n(this, t),
                        o = u.tween;
                    if (o !== r) {
                        i = (r = o).slice();
                        for (var a = {
                                name: n,
                                value: e
                            }, c = 0, l = i.length; c < l; ++c)
                            if (i[c].name === n) {
                                i[c] = a;
                                break
                            }
                        c === l && i.push(a)
                    }
                    u.tween = i
                }
            }

            function Dn(t, n, e) {
                var r = t._id;
                return t.each((function() {
                        var t = _n(this, r);
                        (t.value || (t.value = {}))[n] = e.apply(this, arguments)
                    })),
                    function(t) {
                        return mn(t, r).value[n]
                    }
            }

            function Un(t, n, e) {
                t.prototype = n.prototype = e, e.constructor = t
            }

            function En(t, n) {
                var e = Object.create(t.prototype);
                for (var r in n) e[r] = n[r];
                return e
            }

            function Yn() {}
            var Fn = .7,
                Hn = 1 / Fn,
                Ln = "\\s*([+-]?\\d+)\\s*",
                Zn = "\\s*([+-]?\\d*\\.?\\d+(?:[eE][+-]?\\d+)?)\\s*",
                Pn = "\\s*([+-]?\\d*\\.?\\d+(?:[eE][+-]?\\d+)?)%\\s*",
                Xn = /^#([0-9a-f]{3,8})$/,
                qn = new RegExp("^rgb\\(" + [Ln, Ln, Ln] + "\\)$"),
                On = new RegExp("^rgb\\(" + [Pn, Pn, Pn] + "\\)$"),
                jn = new RegExp("^rgba\\(" + [Ln, Ln, Ln, Zn] + "\\)$"),
                Vn = new RegExp("^rgba\\(" + [Pn, Pn, Pn, Zn] + "\\)$"),
                zn = new RegExp("^hsl\\(" + [Zn, Pn, Pn] + "\\)$"),
                Rn = new RegExp("^hsla\\(" + [Zn, Pn, Pn, Zn] + "\\)$"),
                In = {
                    aliceblue: 15792383,
                    antiquewhite: 16444375,
                    aqua: 65535,
                    aquamarine: 8388564,
                    azure: 15794175,
                    beige: 16119260,
                    bisque: 16770244,
                    black: 0,
                    blanchedalmond: 16772045,
                    blue: 255,
                    blueviolet: 9055202,
                    brown: 10824234,
                    burlywood: 14596231,
                    cadetblue: 6266528,
                    chartreuse: 8388352,
                    chocolate: 13789470,
                    coral: 16744272,
                    cornflowerblue: 6591981,
                    cornsilk: 16775388,
                    crimson: 14423100,
                    cyan: 65535,
                    darkblue: 139,
                    darkcyan: 35723,
                    darkgoldenrod: 12092939,
                    darkgray: 11119017,
                    darkgreen: 25600,
                    darkgrey: 11119017,
                    darkkhaki: 12433259,
                    darkmagenta: 9109643,
                    darkolivegreen: 5597999,
                    darkorange: 16747520,
                    darkorchid: 10040012,
                    darkred: 9109504,
                    darksalmon: 15308410,
                    darkseagreen: 9419919,
                    darkslateblue: 4734347,
                    darkslategray: 3100495,
                    darkslategrey: 3100495,
                    darkturquoise: 52945,
                    darkviolet: 9699539,
                    deeppink: 16716947,
                    deepskyblue: 49151,
                    dimgray: 6908265,
                    dimgrey: 6908265,
                    dodgerblue: 2003199,
                    firebrick: 11674146,
                    floralwhite: 16775920,
                    forestgreen: 2263842,
                    fuchsia: 16711935,
                    gainsboro: 14474460,
                    ghostwhite: 16316671,
                    gold: 16766720,
                    goldenrod: 14329120,
                    gray: 8421504,
                    green: 32768,
                    greenyellow: 11403055,
                    grey: 8421504,
                    honeydew: 15794160,
                    hotpink: 16738740,
                    indianred: 13458524,
                    indigo: 4915330,
                    ivory: 16777200,
                    khaki: 15787660,
                    lavender: 15132410,
                    lavenderblush: 16773365,
                    lawngreen: 8190976,
                    lemonchiffon: 16775885,
                    lightblue: 11393254,
                    lightcoral: 15761536,
                    lightcyan: 14745599,
                    lightgoldenrodyellow: 16448210,
                    lightgray: 13882323,
                    lightgreen: 9498256,
                    lightgrey: 13882323,
                    lightpink: 16758465,
                    lightsalmon: 16752762,
                    lightseagreen: 2142890,
                    lightskyblue: 8900346,
                    lightslategray: 7833753,
                    lightslategrey: 7833753,
                    lightsteelblue: 11584734,
                    lightyellow: 16777184,
                    lime: 65280,
                    limegreen: 3329330,
                    linen: 16445670,
                    magenta: 16711935,
                    maroon: 8388608,
                    mediumaquamarine: 6737322,
                    mediumblue: 205,
                    mediumorchid: 12211667,
                    mediumpurple: 9662683,
                    mediumseagreen: 3978097,
                    mediumslateblue: 8087790,
                    mediumspringgreen: 64154,
                    mediumturquoise: 4772300,
                    mediumvioletred: 13047173,
                    midnightblue: 1644912,
                    mintcream: 16121850,
                    mistyrose: 16770273,
                    moccasin: 16770229,
                    navajowhite: 16768685,
                    navy: 128,
                    oldlace: 16643558,
                    olive: 8421376,
                    olivedrab: 7048739,
                    orange: 16753920,
                    orangered: 16729344,
                    orchid: 14315734,
                    palegoldenrod: 15657130,
                    palegreen: 10025880,
                    paleturquoise: 11529966,
                    palevioletred: 14381203,
                    papayawhip: 16773077,
                    peachpuff: 16767673,
                    peru: 13468991,
                    pink: 16761035,
                    plum: 14524637,
                    powderblue: 11591910,
                    purple: 8388736,
                    rebeccapurple: 6697881,
                    red: 16711680,
                    rosybrown: 12357519,
                    royalblue: 4286945,
                    saddlebrown: 9127187,
                    salmon: 16416882,
                    sandybrown: 16032864,
                    seagreen: 3050327,
                    seashell: 16774638,
                    sienna: 10506797,
                    silver: 12632256,
                    skyblue: 8900331,
                    slateblue: 6970061,
                    slategray: 7372944,
                    slategrey: 7372944,
                    snow: 16775930,
                    springgreen: 65407,
                    steelblue: 4620980,
                    tan: 13808780,
                    teal: 32896,
                    thistle: 14204888,
                    tomato: 16737095,
                    turquoise: 4251856,
                    violet: 15631086,
                    wheat: 16113331,
                    white: 16777215,
                    whitesmoke: 16119285,
                    yellow: 16776960,
                    yellowgreen: 10145074
                };

            function $n() {
                return this.rgb().formatHex()
            }

            function Bn() {
                return this.rgb().formatRgb()
            }

            function Wn(t) {
                var n, e;
                return t = (t + "").trim().toLowerCase(), (n = Xn.exec(t)) ? (e = n[1].length, n = parseInt(n[1], 16), 6 === e ? Gn(n) : 3 === e ? new te(n >> 8 & 15 | n >> 4 & 240, n >> 4 & 15 | 240 & n, (15 & n) << 4 | 15 & n, 1) : 8 === e ? Qn(n >> 24 & 255, n >> 16 & 255, n >> 8 & 255, (255 & n) / 255) : 4 === e ? Qn(n >> 12 & 15 | n >> 8 & 240, n >> 8 & 15 | n >> 4 & 240, n >> 4 & 15 | 240 & n, ((15 & n) << 4 | 15 & n) / 255) : null) : (n = qn.exec(t)) ? new te(n[1], n[2], n[3], 1) : (n = On.exec(t)) ? new te(255 * n[1] / 100, 255 * n[2] / 100, 255 * n[3] / 100, 1) : (n = jn.exec(t)) ? Qn(n[1], n[2], n[3], n[4]) : (n = Vn.exec(t)) ? Qn(255 * n[1] / 100, 255 * n[2] / 100, 255 * n[3] / 100, n[4]) : (n = zn.exec(t)) ? ie(n[1], n[2] / 100, n[3] / 100, 1) : (n = Rn.exec(t)) ? ie(n[1], n[2] / 100, n[3] / 100, n[4]) : In.hasOwnProperty(t) ? Gn(In[t]) : "transparent" === t ? new te(NaN, NaN, NaN, 0) : null
            }

            function Gn(t) {
                return new te(t >> 16 & 255, t >> 8 & 255, 255 & t, 1)
            }

            function Qn(t, n, e, r) {
                return r <= 0 && (t = n = e = NaN), new te(t, n, e, r)
            }

            function Jn(t) {
                return t instanceof Yn || (t = Wn(t)), t ? new te((t = t.rgb()).r, t.g, t.b, t.opacity) : new te
            }

            function Kn(t, n, e, r) {
                return 1 === arguments.length ? Jn(t) : new te(t, n, e, null == r ? 1 : r)
            }

            function te(t, n, e, r) {
                this.r = +t, this.g = +n, this.b = +e, this.opacity = +r
            }

            function ne() {
                return "#" + re(this.r) + re(this.g) + re(this.b)
            }

            function ee() {
                var t = this.opacity;
                return (1 === (t = isNaN(t) ? 1 : Math.max(0, Math.min(1, t))) ? "rgb(" : "rgba(") + Math.max(0, Math.min(255, Math.round(this.r) || 0)) + ", " + Math.max(0, Math.min(255, Math.round(this.g) || 0)) + ", " + Math.max(0, Math.min(255, Math.round(this.b) || 0)) + (1 === t ? ")" : ", " + t + ")")
            }

            function re(t) {
                return ((t = Math.max(0, Math.min(255, Math.round(t) || 0))) < 16 ? "0" : "") + t.toString(16)
            }

            function ie(t, n, e, r) {
                return r <= 0 ? t = n = e = NaN : e <= 0 || e >= 1 ? t = n = NaN : n <= 0 && (t = NaN), new oe(t, n, e, r)
            }

            function ue(t) {
                if (t instanceof oe) return new oe(t.h, t.s, t.l, t.opacity);
                if (t instanceof Yn || (t = Wn(t)), !t) return new oe;
                if (t instanceof oe) return t;
                var n = (t = t.rgb()).r / 255,
                    e = t.g / 255,
                    r = t.b / 255,
                    i = Math.min(n, e, r),
                    u = Math.max(n, e, r),
                    o = NaN,
                    a = u - i,
                    c = (u + i) / 2;
                return a ? (o = n === u ? (e - r) / a + 6 * (e < r) : e === u ? (r - n) / a + 2 : (n - e) / a + 4, a /= c < .5 ? u + i : 2 - u - i, o *= 60) : a = c > 0 && c < 1 ? 0 : o, new oe(o, a, c, t.opacity)
            }

            function oe(t, n, e, r) {
                this.h = +t, this.s = +n, this.l = +e, this.opacity = +r
            }

            function ae(t, n, e) {
                return 255 * (t < 60 ? n + (e - n) * t / 60 : t < 180 ? e : t < 240 ? n + (e - n) * (240 - t) / 60 : n)
            }

            function ce(t, n, e, r, i) {
                var u = t * t,
                    o = u * t;
                return ((1 - 3 * t + 3 * u - o) * n + (4 - 6 * u + 3 * o) * e + (1 + 3 * t + 3 * u - 3 * o) * r + o * i) / 6
            }
            Un(Yn, Wn, {
                copy: function(t) {
                    return Object.assign(new this.constructor, this, t)
                },
                displayable: function() {
                    return this.rgb().displayable()
                },
                hex: $n,
                formatHex: $n,
                formatHsl: function() {
                    return ue(this).formatHsl()
                },
                formatRgb: Bn,
                toString: Bn
            }), Un(te, Kn, En(Yn, {
                brighter: function(t) {
                    return t = null == t ? Hn : Math.pow(Hn, t), new te(this.r * t, this.g * t, this.b * t, this.opacity)
                },
                darker: function(t) {
                    return t = null == t ? Fn : Math.pow(Fn, t), new te(this.r * t, this.g * t, this.b * t, this.opacity)
                },
                rgb: function() {
                    return this
                },
                displayable: function() {
                    return -.5 <= this.r && this.r < 255.5 && -.5 <= this.g && this.g < 255.5 && -.5 <= this.b && this.b < 255.5 && 0 <= this.opacity && this.opacity <= 1
                },
                hex: ne,
                formatHex: ne,
                formatRgb: ee,
                toString: ee
            })), Un(oe, (function(t, n, e, r) {
                return 1 === arguments.length ? ue(t) : new oe(t, n, e, null == r ? 1 : r)
            }), En(Yn, {
                brighter: function(t) {
                    return t = null == t ? Hn : Math.pow(Hn, t), new oe(this.h, this.s, this.l * t, this.opacity)
                },
                darker: function(t) {
                    return t = null == t ? Fn : Math.pow(Fn, t), new oe(this.h, this.s, this.l * t, this.opacity)
                },
                rgb: function() {
                    var t = this.h % 360 + 360 * (this.h < 0),
                        n = isNaN(t) || isNaN(this.s) ? 0 : this.s,
                        e = this.l,
                        r = e + (e < .5 ? e : 1 - e) * n,
                        i = 2 * e - r;
                    return new te(ae(t >= 240 ? t - 240 : t + 120, i, r), ae(t, i, r), ae(t < 120 ? t + 240 : t - 120, i, r), this.opacity)
                },
                displayable: function() {
                    return (0 <= this.s && this.s <= 1 || isNaN(this.s)) && 0 <= this.l && this.l <= 1 && 0 <= this.opacity && this.opacity <= 1
                },
                formatHsl: function() {
                    var t = this.opacity;
                    return (1 === (t = isNaN(t) ? 1 : Math.max(0, Math.min(1, t))) ? "hsl(" : "hsla(") + (this.h || 0) + ", " + 100 * (this.s || 0) + "%, " + 100 * (this.l || 0) + "%" + (1 === t ? ")" : ", " + t + ")")
                }
            }));
            var le = function(t) {
                return function() {
                    return t
                }
            };

            function se(t, n) {
                return function(e) {
                    return t + e * n
                }
            }

            function fe(t) {
                return 1 === (t = +t) ? he : function(n, e) {
                    return e - n ? function(t, n, e) {
                        return t = Math.pow(t, e), n = Math.pow(n, e) - t, e = 1 / e,
                            function(r) {
                                return Math.pow(t + r * n, e)
                            }
                    }(n, e, t) : le(isNaN(n) ? e : n)
                }
            }

            function he(t, n) {
                var e = n - t;
                return e ? se(t, e) : le(isNaN(t) ? n : t)
            }
            var pe = function t(n) {
                var e = fe(n);

                function r(t, n) {
                    var r = e((t = Kn(t)).r, (n = Kn(n)).r),
                        i = e(t.g, n.g),
                        u = e(t.b, n.b),
                        o = he(t.opacity, n.opacity);
                    return function(n) {
                        return t.r = r(n), t.g = i(n), t.b = u(n), t.opacity = o(n), t + ""
                    }
                }
                return r.gamma = t, r
            }(1);

            function ge(t) {
                return function(n) {
                    var e, r, i = n.length,
                        u = new Array(i),
                        o = new Array(i),
                        a = new Array(i);
                    for (e = 0; e < i; ++e) r = Kn(n[e]), u[e] = r.r || 0, o[e] = r.g || 0, a[e] = r.b || 0;
                    return u = t(u), o = t(o), a = t(a), r.opacity = 1,
                        function(t) {
                            return r.r = u(t), r.g = o(t), r.b = a(t), r + ""
                        }
                }
            }
            ge((function(t) {
                var n = t.length - 1;
                return function(e) {
                    var r = e <= 0 ? e = 0 : e >= 1 ? (e = 1, n - 1) : Math.floor(e * n),
                        i = t[r],
                        u = t[r + 1],
                        o = r > 0 ? t[r - 1] : 2 * i - u,
                        a = r < n - 1 ? t[r + 2] : 2 * u - i;
                    return ce((e - r / n) * n, o, i, u, a)
                }
            })), ge((function(t) {
                var n = t.length;
                return function(e) {
                    var r = Math.floor(((e %= 1) < 0 ? ++e : e) * n),
                        i = t[(r + n - 1) % n],
                        u = t[r % n],
                        o = t[(r + 1) % n],
                        a = t[(r + 2) % n];
                    return ce((e - r / n) * n, i, u, o, a)
                }
            }));
            var ye = /[-+]?(?:\d+\.?\d*|\.?\d+)(?:[eE][-+]?\d+)?/g,
                ve = new RegExp(ye.source, "g");

            function de(t, n) {
                var e, r, i, u = ye.lastIndex = ve.lastIndex = 0,
                    o = -1,
                    a = [],
                    c = [];
                for (t += "", n += "";
                    (e = ye.exec(t)) && (r = ve.exec(n));)(i = r.index) > u && (i = n.slice(u, i), a[o] ? a[o] += i : a[++o] = i), (e = e[0]) === (r = r[0]) ? a[o] ? a[o] += r : a[++o] = r : (a[++o] = null, c.push({
                    i: o,
                    x: wn(e, r)
                })), u = ve.lastIndex;
                return u < n.length && (i = n.slice(u), a[o] ? a[o] += i : a[++o] = i), a.length < 2 ? c[0] ? function(t) {
                    return function(n) {
                        return t(n) + ""
                    }
                }(c[0].x) : function(t) {
                    return function() {
                        return t
                    }
                }(n) : (n = c.length, function(t) {
                    for (var e, r = 0; r < n; ++r) a[(e = c[r]).i] = e.x(t);
                    return a.join("")
                })
            }

            function _e(t, n) {
                var e;
                return ("number" === typeof n ? wn : n instanceof Wn ? pe : (e = Wn(n)) ? (n = e, pe) : de)(t, n)
            }

            function me(t) {
                return function() {
                    this.removeAttribute(t)
                }
            }

            function we(t) {
                return function() {
                    this.removeAttributeNS(t.space, t.local)
                }
            }

            function xe(t, n, e) {
                var r, i, u = e + "";
                return function() {
                    var o = this.getAttribute(t);
                    return o === u ? null : o === r ? i : i = n(r = o, e)
                }
            }

            function Me(t, n, e) {
                var r, i, u = e + "";
                return function() {
                    var o = this.getAttributeNS(t.space, t.local);
                    return o === u ? null : o === r ? i : i = n(r = o, e)
                }
            }

            function be(t, n, e) {
                var r, i, u;
                return function() {
                    var o, a, c = e(this);
                    if (null != c) return (o = this.getAttribute(t)) === (a = c + "") ? null : o === r && a === i ? u : (i = a, u = n(r = o, c));
                    this.removeAttribute(t)
                }
            }

            function Te(t, n, e) {
                var r, i, u;
                return function() {
                    var o, a, c = e(this);
                    if (null != c) return (o = this.getAttributeNS(t.space, t.local)) === (a = c + "") ? null : o === r && a === i ? u : (i = a, u = n(r = o, c));
                    this.removeAttributeNS(t.space, t.local)
                }
            }

            function Ae(t, n) {
                return function(e) {
                    this.setAttribute(t, n.call(this, e))
                }
            }

            function ke(t, n) {
                return function(e) {
                    this.setAttributeNS(t.space, t.local, n.call(this, e))
                }
            }

            function Ce(t, n) {
                var e, r;

                function i() {
                    var i = n.apply(this, arguments);
                    return i !== r && (e = (r = i) && ke(t, i)), e
                }
                return i._value = n, i
            }

            function Ne(t, n) {
                var e, r;

                function i() {
                    var i = n.apply(this, arguments);
                    return i !== r && (e = (r = i) && Ae(t, i)), e
                }
                return i._value = n, i
            }

            function Se(t, n) {
                return function() {
                    dn(this, t).delay = +n.apply(this, arguments)
                }
            }

            function De(t, n) {
                return n = +n,
                    function() {
                        dn(this, t).delay = n
                    }
            }

            function Ue(t, n) {
                return function() {
                    _n(this, t).duration = +n.apply(this, arguments)
                }
            }

            function Ee(t, n) {
                return n = +n,
                    function() {
                        _n(this, t).duration = n
                    }
            }

            function Ye(t, n) {
                if ("function" !== typeof n) throw new Error;
                return function() {
                    _n(this, t).ease = n
                }
            }

            function Fe(t, n, e) {
                var r, i, u = function(t) {
                    return (t + "").trim().split(/^|\s+/).every((function(t) {
                        var n = t.indexOf(".");
                        return n >= 0 && (t = t.slice(0, n)), !t || "start" === t
                    }))
                }(n) ? dn : _n;
                return function() {
                    var o = u(this, t),
                        a = o.on;
                    a !== r && (i = (r = a).copy()).on(n, e), o.on = i
                }
            }
            var He = Ot.prototype.constructor;

            function Le(t) {
                return function() {
                    this.style.removeProperty(t)
                }
            }

            function Ze(t, n, e) {
                return function(r) {
                    this.style.setProperty(t, n.call(this, r), e)
                }
            }

            function Pe(t, n, e) {
                var r, i;

                function u() {
                    var u = n.apply(this, arguments);
                    return u !== i && (r = (i = u) && Ze(t, u, e)), r
                }
                return u._value = n, u
            }

            function Xe(t) {
                return function(n) {
                    this.textContent = t.call(this, n)
                }
            }

            function qe(t) {
                var n, e;

                function r() {
                    var r = t.apply(this, arguments);
                    return r !== e && (n = (e = r) && Xe(r)), n
                }
                return r._value = t, r
            }
            var Oe = 0;

            function je(t, n, e, r) {
                this._groups = t, this._parents = n, this._name = e, this._id = r
            }

            function Ve() {
                return ++Oe
            }
            var ze = Ot.prototype;
            je.prototype = function(t) {
                return Ot().transition(t)
            }.prototype = (0, T.Z)({
                constructor: je,
                select: function(t) {
                    var n = this._name,
                        e = this._id;
                    "function" !== typeof t && (t = k(t));
                    for (var r = this._groups, i = r.length, u = new Array(i), o = 0; o < i; ++o)
                        for (var a, c, l = r[o], s = l.length, f = u[o] = new Array(s), h = 0; h < s; ++h)(a = l[h]) && (c = t.call(a, a.__data__, h, l)) && ("__data__" in a && (c.__data__ = a.__data__), f[h] = c, vn(f[h], n, e, h, f, mn(a, e)));
                    return new je(u, this._parents, n, e)
                },
                selectAll: function(t) {
                    var n = this._name,
                        e = this._id;
                    "function" !== typeof t && (t = S(t));
                    for (var r = this._groups, i = r.length, u = [], o = [], a = 0; a < i; ++a)
                        for (var c, l = r[a], s = l.length, f = 0; f < s; ++f)
                            if (c = l[f]) {
                                for (var h, p = t.call(c, c.__data__, f, l), g = mn(c, e), y = 0, v = p.length; y < v; ++y)(h = p[y]) && vn(h, n, e, y, p, g);
                                u.push(p), o.push(c)
                            }
                    return new je(u, o, n, e)
                },
                filter: function(t) {
                    "function" !== typeof t && (t = D(t));
                    for (var n = this._groups, e = n.length, r = new Array(e), i = 0; i < e; ++i)
                        for (var u, o = n[i], a = o.length, c = r[i] = [], l = 0; l < a; ++l)(u = o[l]) && t.call(u, u.__data__, l, o) && c.push(u);
                    return new je(r, this._parents, this._name, this._id)
                },
                merge: function(t) {
                    if (t._id !== this._id) throw new Error;
                    for (var n = this._groups, e = t._groups, r = n.length, i = e.length, u = Math.min(r, i), o = new Array(r), a = 0; a < u; ++a)
                        for (var c, l = n[a], s = e[a], f = l.length, h = o[a] = new Array(f), p = 0; p < f; ++p)(c = l[p] || s[p]) && (h[p] = c);
                    for (; a < r; ++a) o[a] = n[a];
                    return new je(o, this._parents, this._name, this._id)
                },
                selection: function() {
                    return new He(this._groups, this._parents)
                },
                transition: function() {
                    for (var t = this._name, n = this._id, e = Ve(), r = this._groups, i = r.length, u = 0; u < i; ++u)
                        for (var o, a = r[u], c = a.length, l = 0; l < c; ++l)
                            if (o = a[l]) {
                                var s = mn(o, n);
                                vn(o, t, e, l, a, {
                                    time: s.time + s.delay + s.duration,
                                    delay: 0,
                                    duration: s.duration,
                                    ease: s.ease
                                })
                            }
                    return new je(r, this._parents, t, e)
                },
                call: ze.call,
                nodes: ze.nodes,
                node: ze.node,
                size: ze.size,
                empty: ze.empty,
                each: ze.each,
                on: function(t, n) {
                    var e = this._id;
                    return arguments.length < 2 ? mn(this.node(), e).on.on(t) : this.each(Fe(e, t, n))
                },
                attr: function(t, n) {
                    var e = R(t),
                        r = "transform" === e ? Cn : _e;
                    return this.attrTween(t, "function" === typeof n ? (e.local ? Te : be)(e, r, Dn(this, "attr." + t, n)) : null == n ? (e.local ? we : me)(e) : (e.local ? Me : xe)(e, r, n))
                },
                attrTween: function(t, n) {
                    var e = "attr." + t;
                    if (arguments.length < 2) return (e = this.tween(e)) && e._value;
                    if (null == n) return this.tween(e, null);
                    if ("function" !== typeof n) throw new Error;
                    var r = R(t);
                    return this.tween(e, (r.local ? Ce : Ne)(r, n))
                },
                style: function(t, n, e) {
                    var r = "transform" === (t += "") ? kn : _e;
                    return null == n ? this.styleTween(t, function(t, n) {
                        var e, r, i;
                        return function() {
                            var u = et(this, t),
                                o = (this.style.removeProperty(t), et(this, t));
                            return u === o ? null : u === e && o === r ? i : i = n(e = u, r = o)
                        }
                    }(t, r)).on("end.style." + t, Le(t)) : "function" === typeof n ? this.styleTween(t, function(t, n, e) {
                        var r, i, u;
                        return function() {
                            var o = et(this, t),
                                a = e(this),
                                c = a + "";
                            return null == a && (this.style.removeProperty(t), c = a = et(this, t)), o === c ? null : o === r && c === i ? u : (i = c, u = n(r = o, a))
                        }
                    }(t, r, Dn(this, "style." + t, n))).each(function(t, n) {
                        var e, r, i, u, o = "style." + n,
                            a = "end." + o;
                        return function() {
                            var c = _n(this, t),
                                l = c.on,
                                s = null == c.value[o] ? u || (u = Le(n)) : void 0;
                            l === e && i === s || (r = (e = l).copy()).on(a, i = s), c.on = r
                        }
                    }(this._id, t)) : this.styleTween(t, function(t, n, e) {
                        var r, i, u = e + "";
                        return function() {
                            var o = et(this, t);
                            return o === u ? null : o === r ? i : i = n(r = o, e)
                        }
                    }(t, r, n), e).on("end.style." + t, null)
                },
                styleTween: function(t, n, e) {
                    var r = "style." + (t += "");
                    if (arguments.length < 2) return (r = this.tween(r)) && r._value;
                    if (null == n) return this.tween(r, null);
                    if ("function" !== typeof n) throw new Error;
                    return this.tween(r, Pe(t, n, null == e ? "" : e))
                },
                text: function(t) {
                    return this.tween("text", "function" === typeof t ? function(t) {
                        return function() {
                            var n = t(this);
                            this.textContent = null == n ? "" : n
                        }
                    }(Dn(this, "text", t)) : function(t) {
                        return function() {
                            this.textContent = t
                        }
                    }(null == t ? "" : t + ""))
                },
                textTween: function(t) {
                    var n = "text";
                    if (arguments.length < 1) return (n = this.tween(n)) && n._value;
                    if (null == t) return this.tween(n, null);
                    if ("function" !== typeof t) throw new Error;
                    return this.tween(n, qe(t))
                },
                remove: function() {
                    return this.on("end.remove", function(t) {
                        return function() {
                            var n = this.parentNode;
                            for (var e in this.__transition)
                                if (+e !== t) return;
                            n && n.removeChild(this)
                        }
                    }(this._id))
                },
                tween: function(t, n) {
                    var e = this._id;
                    if (t += "", arguments.length < 2) {
                        for (var r, i = mn(this.node(), e).tween, u = 0, o = i.length; u < o; ++u)
                            if ((r = i[u]).name === t) return r.value;
                        return null
                    }
                    return this.each((null == n ? Nn : Sn)(e, t, n))
                },
                delay: function(t) {
                    var n = this._id;
                    return arguments.length ? this.each(("function" === typeof t ? Se : De)(n, t)) : mn(this.node(), n).delay
                },
                duration: function(t) {
                    var n = this._id;
                    return arguments.length ? this.each(("function" === typeof t ? Ue : Ee)(n, t)) : mn(this.node(), n).duration
                },
                ease: function(t) {
                    var n = this._id;
                    return arguments.length ? this.each(Ye(n, t)) : mn(this.node(), n).ease
                },
                easeVarying: function(t) {
                    if ("function" !== typeof t) throw new Error;
                    return this.each(function(t, n) {
                        return function() {
                            var e = n.apply(this, arguments);
                            if ("function" !== typeof e) throw new Error;
                            _n(this, t).ease = e
                        }
                    }(this._id, t))
                },
                end: function() {
                    var t, n, e = this,
                        r = e._id,
                        i = e.size();
                    return new Promise((function(u, o) {
                        var a = {
                                value: o
                            },
                            c = {
                                value: function() {
                                    0 === --i && u()
                                }
                            };
                        e.each((function() {
                            var e = _n(this, r),
                                i = e.on;
                            i !== t && ((n = (t = i).copy())._.cancel.push(a), n._.interrupt.push(a), n._.end.push(c)), e.on = n
                        })), 0 === i && u()
                    }))
                }
            }, Symbol.iterator, ze[Symbol.iterator]);
            var Re = {
                time: null,
                delay: 0,
                duration: 250,
                ease: function(t) {
                    return ((t *= 2) <= 1 ? t * t * t : (t -= 2) * t * t + 2) / 2
                }
            };

            function Ie(t, n) {
                for (var e; !(e = t.__transition) || !(e = e[n]);)
                    if (!(t = t.parentNode)) throw new Error("transition ".concat(n, " not found"));
                return e
            }
            Ot.prototype.interrupt = function(t) {
                return this.each((function() {
                    ! function(t, n) {
                        var e, r, i, u = t.__transition,
                            o = !0;
                        if (u) {
                            for (i in n = null == n ? null : n + "", u)(e = u[i]).name === n ? (r = e.state > 2 && e.state < 5, e.state = 6, e.timer.stop(), e.on.call(r ? "interrupt" : "cancel", t, t.__data__, e.index, e.group), delete u[i]) : o = !1;
                            o && delete t.__transition
                        }
                    }(this, t)
                }))
            }, Ot.prototype.transition = function(t) {
                var n, e;
                t instanceof je ? (n = t._id, t = t._name) : (n = Ve(), (e = Re).time = on(), t = null == t ? null : t + "");
                for (var r = this._groups, i = r.length, u = 0; u < i; ++u)
                    for (var o, a = r[u], c = a.length, l = 0; l < c; ++l)(o = a[l]) && vn(o, t, n, l, a, e || Ie(o, n));
                return new je(r, this._parents, t, n)
            };
            Math.abs, Math.max, Math.min;

            function $e(t) {
                return [+t[0], +t[1]]
            }

            function Be(t) {
                return [$e(t[0]), $e(t[1])]
            }["w", "e"].map(We), ["n", "s"].map(We), ["n", "w", "e", "s", "nw", "ne", "sw", "se"].map(We);

            function We(t) {
                return {
                    type: t
                }
            }
            var Ge = Math.PI;

            function Qe(t) {
                return (1 - Math.cos(Ge * t)) / 2
            }
            var Je = e(38153);

            function Ke(t, n, e) {
                t = +t, n = +n, e = (i = arguments.length) < 2 ? (n = t, t = 0, 1) : i < 3 ? 1 : +e;
                for (var r = -1, i = 0 | Math.max(0, Math.ceil((n - t) / e)), u = new Array(i); ++r < i;) u[r] = t + r * e;
                return u
            }

            function tr(t, n) {
                switch (arguments.length) {
                    case 0:
                        break;
                    case 1:
                        this.range(t);
                        break;
                    default:
                        this.range(n).domain(t)
                }
                return this
            }
            var nr = Symbol("implicit");

            function er() {
                var t = new Map,
                    n = [],
                    e = [],
                    r = nr;

                function i(i) {
                    var u = i + "",
                        o = t.get(u);
                    if (!o) {
                        if (r !== nr) return r;
                        t.set(u, o = n.push(i))
                    }
                    return e[(o - 1) % e.length]
                }
                return i.domain = function(e) {
                    if (!arguments.length) return n.slice();
                    n = [], t = new Map;
                    var r, o = (0, u.Z)(e);
                    try {
                        for (o.s(); !(r = o.n()).done;) {
                            var a = r.value,
                                c = a + "";
                            t.has(c) || t.set(c, n.push(a))
                        }
                    } catch (l) {
                        o.e(l)
                    } finally {
                        o.f()
                    }
                    return i
                }, i.range = function(t) {
                    return arguments.length ? (e = Array.from(t), i) : e.slice()
                }, i.unknown = function(t) {
                    return arguments.length ? (r = t, i) : r
                }, i.copy = function() {
                    return er(n, e).unknown(r)
                }, tr.apply(i, arguments), i
            }

            function rr() {
                var t, n, e = er().unknown(void 0),
                    r = e.domain,
                    i = e.range,
                    u = 0,
                    o = 1,
                    a = !1,
                    c = 0,
                    l = 0,
                    s = .5;

                function f() {
                    var e = r().length,
                        f = o < u,
                        h = f ? o : u,
                        p = f ? u : o;
                    t = (p - h) / Math.max(1, e - c + 2 * l), a && (t = Math.floor(t)), h += (p - h - t * (e - c)) * s, n = t * (1 - c), a && (h = Math.round(h), n = Math.round(n));
                    var g = Ke(e).map((function(n) {
                        return h + t * n
                    }));
                    return i(f ? g.reverse() : g)
                }
                return delete e.unknown, e.domain = function(t) {
                    return arguments.length ? (r(t), f()) : r()
                }, e.range = function(t) {
                    var n;
                    return arguments.length ? (n = (0, Je.Z)(t, 2), u = n[0], o = n[1], u = +u, o = +o, f()) : [u, o]
                }, e.rangeRound = function(t) {
                    var n;
                    return n = (0, Je.Z)(t, 2), u = n[0], o = n[1], u = +u, o = +o, a = !0, f()
                }, e.bandwidth = function() {
                    return n
                }, e.step = function() {
                    return t
                }, e.round = function(t) {
                    return arguments.length ? (a = !!t, f()) : a
                }, e.padding = function(t) {
                    return arguments.length ? (c = Math.min(1, l = +t), f()) : c
                }, e.paddingInner = function(t) {
                    return arguments.length ? (c = Math.min(1, t), f()) : c
                }, e.paddingOuter = function(t) {
                    return arguments.length ? (l = +t, f()) : l
                }, e.align = function(t) {
                    return arguments.length ? (s = Math.max(0, Math.min(1, t)), f()) : s
                }, e.copy = function() {
                    return rr(r(), [u, o]).round(a).paddingInner(c).paddingOuter(l).align(s)
                }, tr.apply(f(), arguments)
            }
            var ir = Math.sqrt(50),
                ur = Math.sqrt(10),
                or = Math.sqrt(2);

            function ar(t, n, e) {
                var r = (n - t) / Math.max(0, e),
                    i = Math.floor(Math.log(r) / Math.LN10),
                    u = r / Math.pow(10, i);
                return i >= 0 ? (u >= ir ? 10 : u >= ur ? 5 : u >= or ? 2 : 1) * Math.pow(10, i) : -Math.pow(10, -i) / (u >= ir ? 10 : u >= ur ? 5 : u >= or ? 2 : 1)
            }

            function cr(t, n, e) {
                var r = Math.abs(n - t) / Math.max(0, e),
                    i = Math.pow(10, Math.floor(Math.log(r) / Math.LN10)),
                    u = r / i;
                return u >= ir ? i *= 10 : u >= ur ? i *= 5 : u >= or && (i *= 2), n < t ? -i : i
            }
            var lr = i(r),
                sr = lr.right,
                fr = (lr.left, i((function(t) {
                    return null === t ? NaN : +t
                })).center, sr);

            function hr(t, n) {
                var e, r = n ? n.length : 0,
                    i = t ? Math.min(r, t.length) : 0,
                    u = new Array(i),
                    o = new Array(r);
                for (e = 0; e < i; ++e) u[e] = vr(t[e], n[e]);
                for (; e < r; ++e) o[e] = n[e];
                return function(t) {
                    for (e = 0; e < i; ++e) o[e] = u[e](t);
                    return o
                }
            }

            function pr(t, n) {
                var e = new Date;
                return t = +t, n = +n,
                    function(r) {
                        return e.setTime(t * (1 - r) + n * r), e
                    }
            }

            function gr(t, n) {
                var e, r = {},
                    i = {};
                for (e in null !== t && "object" === typeof t || (t = {}), null !== n && "object" === typeof n || (n = {}), n) e in t ? r[e] = vr(t[e], n[e]) : i[e] = n[e];
                return function(t) {
                    for (e in r) i[e] = r[e](t);
                    return i
                }
            }

            function yr(t, n) {
                n || (n = []);
                var e, r = t ? Math.min(n.length, t.length) : 0,
                    i = n.slice();
                return function(u) {
                    for (e = 0; e < r; ++e) i[e] = t[e] * (1 - u) + n[e] * u;
                    return i
                }
            }

            function vr(t, n) {
                var e, r, i = typeof n;
                return null == n || "boolean" === i ? le(n) : ("number" === i ? wn : "string" === i ? (e = Wn(n)) ? (n = e, pe) : de : n instanceof Wn ? pe : n instanceof Date ? pr : (r = n, !ArrayBuffer.isView(r) || r instanceof DataView ? Array.isArray(n) ? hr : "function" !== typeof n.valueOf && "function" !== typeof n.toString || isNaN(n) ? gr : wn : yr))(t, n)
            }

            function dr(t, n) {
                return t = +t, n = +n,
                    function(e) {
                        return Math.round(t * (1 - e) + n * e)
                    }
            }

            function _r(t) {
                return +t
            }
            var mr = [0, 1];

            function wr(t) {
                return t
            }

            function xr(t, n) {
                return (n -= t = +t) ? function(e) {
                    return (e - t) / n
                } : (e = isNaN(n) ? NaN : .5, function() {
                    return e
                });
                var e
            }

            function Mr(t, n, e) {
                var r = t[0],
                    i = t[1],
                    u = n[0],
                    o = n[1];
                return i < r ? (r = xr(i, r), u = e(o, u)) : (r = xr(r, i), u = e(u, o)),
                    function(t) {
                        return u(r(t))
                    }
            }

            function br(t, n, e) {
                var r = Math.min(t.length, n.length) - 1,
                    i = new Array(r),
                    u = new Array(r),
                    o = -1;
                for (t[r] < t[0] && (t = t.slice().reverse(), n = n.slice().reverse()); ++o < r;) i[o] = xr(t[o], t[o + 1]), u[o] = e(n[o], n[o + 1]);
                return function(n) {
                    var e = fr(t, n, 1, r) - 1;
                    return u[e](i[e](n))
                }
            }

            function Tr(t, n) {
                return n.domain(t.domain()).range(t.range()).interpolate(t.interpolate()).clamp(t.clamp()).unknown(t.unknown())
            }

            function Ar() {
                var t, n, e, r, i, u, o = mr,
                    a = mr,
                    c = vr,
                    l = wr;

                function s() {
                    var t = Math.min(o.length, a.length);
                    return l !== wr && (l = function(t, n) {
                        var e;
                        return t > n && (e = t, t = n, n = e),
                            function(e) {
                                return Math.max(t, Math.min(n, e))
                            }
                    }(o[0], o[t - 1])), r = t > 2 ? br : Mr, i = u = null, f
                }

                function f(n) {
                    return null == n || isNaN(n = +n) ? e : (i || (i = r(o.map(t), a, c)))(t(l(n)))
                }
                return f.invert = function(e) {
                        return l(n((u || (u = r(a, o.map(t), wn)))(e)))
                    }, f.domain = function(t) {
                        return arguments.length ? (o = Array.from(t, _r), s()) : o.slice()
                    }, f.range = function(t) {
                        return arguments.length ? (a = Array.from(t), s()) : a.slice()
                    }, f.rangeRound = function(t) {
                        return a = Array.from(t), c = dr, s()
                    }, f.clamp = function(t) {
                        return arguments.length ? (l = !!t || wr, s()) : l !== wr
                    }, f.interpolate = function(t) {
                        return arguments.length ? (c = t, s()) : c
                    }, f.unknown = function(t) {
                        return arguments.length ? (e = t, f) : e
                    },
                    function(e, r) {
                        return t = e, n = r, s()
                    }
            }

            function kr() {
                return Ar()(wr, wr)
            }
            var Cr, Nr = /^(?:(.)?([<>=^]))?([+\-( ])?([$#])?(0)?(\d+)?(,)?(\.\d+)?(~)?([a-z%])?$/i;

            function Sr(t) {
                if (!(n = Nr.exec(t))) throw new Error("invalid format: " + t);
                var n;
                return new Dr({
                    fill: n[1],
                    align: n[2],
                    sign: n[3],
                    symbol: n[4],
                    zero: n[5],
                    width: n[6],
                    comma: n[7],
                    precision: n[8] && n[8].slice(1),
                    trim: n[9],
                    type: n[10]
                })
            }

            function Dr(t) {
                this.fill = void 0 === t.fill ? " " : t.fill + "", this.align = void 0 === t.align ? ">" : t.align + "", this.sign = void 0 === t.sign ? "-" : t.sign + "", this.symbol = void 0 === t.symbol ? "" : t.symbol + "", this.zero = !!t.zero, this.width = void 0 === t.width ? void 0 : +t.width, this.comma = !!t.comma, this.precision = void 0 === t.precision ? void 0 : +t.precision, this.trim = !!t.trim, this.type = void 0 === t.type ? "" : t.type + ""
            }

            function Ur(t, n) {
                if ((e = (t = n ? t.toExponential(n - 1) : t.toExponential()).indexOf("e")) < 0) return null;
                var e, r = t.slice(0, e);
                return [r.length > 1 ? r[0] + r.slice(2) : r, +t.slice(e + 1)]
            }

            function Er(t) {
                return (t = Ur(Math.abs(t))) ? t[1] : NaN
            }

            function Yr(t, n) {
                var e = Ur(t, n);
                if (!e) return t + "";
                var r = e[0],
                    i = e[1];
                return i < 0 ? "0." + new Array(-i).join("0") + r : r.length > i + 1 ? r.slice(0, i + 1) + "." + r.slice(i + 1) : r + new Array(i - r.length + 2).join("0")
            }
            Sr.prototype = Dr.prototype, Dr.prototype.toString = function() {
                return this.fill + this.align + this.sign + this.symbol + (this.zero ? "0" : "") + (void 0 === this.width ? "" : Math.max(1, 0 | this.width)) + (this.comma ? "," : "") + (void 0 === this.precision ? "" : "." + Math.max(0, 0 | this.precision)) + (this.trim ? "~" : "") + this.type
            };
            var Fr = {
                "%": function(t, n) {
                    return (100 * t).toFixed(n)
                },
                b: function(t) {
                    return Math.round(t).toString(2)
                },
                c: function(t) {
                    return t + ""
                },
                d: function(t) {
                    return Math.abs(t = Math.round(t)) >= 1e21 ? t.toLocaleString("en").replace(/,/g, "") : t.toString(10)
                },
                e: function(t, n) {
                    return t.toExponential(n)
                },
                f: function(t, n) {
                    return t.toFixed(n)
                },
                g: function(t, n) {
                    return t.toPrecision(n)
                },
                o: function(t) {
                    return Math.round(t).toString(8)
                },
                p: function(t, n) {
                    return Yr(100 * t, n)
                },
                r: Yr,
                s: function(t, n) {
                    var e = Ur(t, n);
                    if (!e) return t + "";
                    var r = e[0],
                        i = e[1],
                        u = i - (Cr = 3 * Math.max(-8, Math.min(8, Math.floor(i / 3)))) + 1,
                        o = r.length;
                    return u === o ? r : u > o ? r + new Array(u - o + 1).join("0") : u > 0 ? r.slice(0, u) + "." + r.slice(u) : "0." + new Array(1 - u).join("0") + Ur(t, Math.max(0, n + u - 1))[0]
                },
                X: function(t) {
                    return Math.round(t).toString(16).toUpperCase()
                },
                x: function(t) {
                    return Math.round(t).toString(16)
                }
            };

            function Hr(t) {
                return t
            }
            var Lr, Zr, Pr, Xr = Array.prototype.map,
                qr = ["y", "z", "a", "f", "p", "n", "\xb5", "m", "", "k", "M", "G", "T", "P", "E", "Z", "Y"];

            function Or(t) {
                var n, e, r = void 0 === t.grouping || void 0 === t.thousands ? Hr : (n = Xr.call(t.grouping, Number), e = t.thousands + "", function(t, r) {
                        for (var i = t.length, u = [], o = 0, a = n[0], c = 0; i > 0 && a > 0 && (c + a + 1 > r && (a = Math.max(1, r - c)), u.push(t.substring(i -= a, i + a)), !((c += a + 1) > r));) a = n[o = (o + 1) % n.length];
                        return u.reverse().join(e)
                    }),
                    i = void 0 === t.currency ? "" : t.currency[0] + "",
                    u = void 0 === t.currency ? "" : t.currency[1] + "",
                    o = void 0 === t.decimal ? "." : t.decimal + "",
                    a = void 0 === t.numerals ? Hr : function(t) {
                        return function(n) {
                            return n.replace(/[0-9]/g, (function(n) {
                                return t[+n]
                            }))
                        }
                    }(Xr.call(t.numerals, String)),
                    c = void 0 === t.percent ? "%" : t.percent + "",
                    l = void 0 === t.minus ? "\u2212" : t.minus + "",
                    s = void 0 === t.nan ? "NaN" : t.nan + "";

                function f(t) {
                    var n = (t = Sr(t)).fill,
                        e = t.align,
                        f = t.sign,
                        h = t.symbol,
                        p = t.zero,
                        g = t.width,
                        y = t.comma,
                        v = t.precision,
                        d = t.trim,
                        _ = t.type;
                    "n" === _ ? (y = !0, _ = "g") : Fr[_] || (void 0 === v && (v = 12), d = !0, _ = "g"), (p || "0" === n && "=" === e) && (p = !0, n = "0", e = "=");
                    var m = "$" === h ? i : "#" === h && /[boxX]/.test(_) ? "0" + _.toLowerCase() : "",
                        w = "$" === h ? u : /[%p]/.test(_) ? c : "",
                        x = Fr[_],
                        M = /[defgprs%]/.test(_);

                    function b(t) {
                        var i, u, c, h = m,
                            b = w;
                        if ("c" === _) b = x(t) + b, t = "";
                        else {
                            var T = (t = +t) < 0 || 1 / t < 0;
                            if (t = isNaN(t) ? s : x(Math.abs(t), v), d && (t = function(t) {
                                    t: for (var n, e = t.length, r = 1, i = -1; r < e; ++r) switch (t[r]) {
                                        case ".":
                                            i = n = r;
                                            break;
                                        case "0":
                                            0 === i && (i = r), n = r;
                                            break;
                                        default:
                                            if (!+t[r]) break t;
                                            i > 0 && (i = 0)
                                    }
                                    return i > 0 ? t.slice(0, i) + t.slice(n + 1) : t
                                }(t)), T && 0 === +t && "+" !== f && (T = !1), h = (T ? "(" === f ? f : l : "-" === f || "(" === f ? "" : f) + h, b = ("s" === _ ? qr[8 + Cr / 3] : "") + b + (T && "(" === f ? ")" : ""), M)
                                for (i = -1, u = t.length; ++i < u;)
                                    if (48 > (c = t.charCodeAt(i)) || c > 57) {
                                        b = (46 === c ? o + t.slice(i + 1) : t.slice(i)) + b, t = t.slice(0, i);
                                        break
                                    }
                        }
                        y && !p && (t = r(t, 1 / 0));
                        var A = h.length + t.length + b.length,
                            k = A < g ? new Array(g - A + 1).join(n) : "";
                        switch (y && p && (t = r(k + t, k.length ? g - b.length : 1 / 0), k = ""), e) {
                            case "<":
                                t = h + t + b + k;
                                break;
                            case "=":
                                t = h + k + t + b;
                                break;
                            case "^":
                                t = k.slice(0, A = k.length >> 1) + h + t + b + k.slice(A);
                                break;
                            default:
                                t = k + h + t + b
                        }
                        return a(t)
                    }
                    return v = void 0 === v ? 6 : /[gprs]/.test(_) ? Math.max(1, Math.min(21, v)) : Math.max(0, Math.min(20, v)), b.toString = function() {
                        return t + ""
                    }, b
                }
                return {
                    format: f,
                    formatPrefix: function(t, n) {
                        var e = f(((t = Sr(t)).type = "f", t)),
                            r = 3 * Math.max(-8, Math.min(8, Math.floor(Er(n) / 3))),
                            i = Math.pow(10, -r),
                            u = qr[8 + r / 3];
                        return function(t) {
                            return e(i * t) + u
                        }
                    }
                }
            }

            function jr(t, n, e, r) {
                var i, u = cr(t, n, e);
                switch ((r = Sr(null == r ? ",f" : r)).type) {
                    case "s":
                        var o = Math.max(Math.abs(t), Math.abs(n));
                        return null != r.precision || isNaN(i = function(t, n) {
                            return Math.max(0, 3 * Math.max(-8, Math.min(8, Math.floor(Er(n) / 3))) - Er(Math.abs(t)))
                        }(u, o)) || (r.precision = i), Pr(r, o);
                    case "":
                    case "e":
                    case "g":
                    case "p":
                    case "r":
                        null != r.precision || isNaN(i = function(t, n) {
                            return t = Math.abs(t), n = Math.abs(n) - t, Math.max(0, Er(n) - Er(t)) + 1
                        }(u, Math.max(Math.abs(t), Math.abs(n)))) || (r.precision = i - ("e" === r.type));
                        break;
                    case "f":
                    case "%":
                        null != r.precision || isNaN(i = function(t) {
                            return Math.max(0, -Er(Math.abs(t)))
                        }(u)) || (r.precision = i - 2 * ("%" === r.type))
                }
                return Zr(r)
            }

            function Vr(t) {
                var n = t.domain;
                return t.ticks = function(t) {
                    var e = n();
                    return function(t, n, e) {
                        var r, i, u, o, a = -1;
                        if (e = +e, (t = +t) === (n = +n) && e > 0) return [t];
                        if ((r = n < t) && (i = t, t = n, n = i), 0 === (o = ar(t, n, e)) || !isFinite(o)) return [];
                        if (o > 0) {
                            var c = Math.round(t / o),
                                l = Math.round(n / o);
                            for (c * o < t && ++c, l * o > n && --l, u = new Array(i = l - c + 1); ++a < i;) u[a] = (c + a) * o
                        } else {
                            o = -o;
                            var s = Math.round(t * o),
                                f = Math.round(n * o);
                            for (s / o < t && ++s, f / o > n && --f, u = new Array(i = f - s + 1); ++a < i;) u[a] = (s + a) / o
                        }
                        return r && u.reverse(), u
                    }(e[0], e[e.length - 1], null == t ? 10 : t)
                }, t.tickFormat = function(t, e) {
                    var r = n();
                    return jr(r[0], r[r.length - 1], null == t ? 10 : t, e)
                }, t.nice = function(e) {
                    null == e && (e = 10);
                    var r, i, u = n(),
                        o = 0,
                        a = u.length - 1,
                        c = u[o],
                        l = u[a],
                        s = 10;
                    for (l < c && (i = c, c = l, l = i, i = o, o = a, a = i); s-- > 0;) {
                        if ((i = ar(c, l, e)) === r) return u[o] = c, u[a] = l, n(u);
                        if (i > 0) c = Math.floor(c / i) * i, l = Math.ceil(l / i) * i;
                        else {
                            if (!(i < 0)) break;
                            c = Math.ceil(c * i) / i, l = Math.floor(l * i) / i
                        }
                        r = i
                    }
                    return t
                }, t
            }

            function zr() {
                var t = kr();
                return t.copy = function() {
                    return Tr(t, zr())
                }, tr.apply(t, arguments), Vr(t)
            }
            Lr = Or({
                thousands: ",",
                grouping: [3],
                currency: ["$", ""]
            }), Zr = Lr.format, Pr = Lr.formatPrefix;
            var Rr = 1e3,
                Ir = 6e4,
                $r = 36e5,
                Br = 864e5,
                Wr = 6048e5,
                Gr = 2592e6,
                Qr = 31536e6,
                Jr = new Date,
                Kr = new Date;

            function ti(t, n, e, r) {
                function i(n) {
                    return t(n = 0 === arguments.length ? new Date : new Date(+n)), n
                }
                return i.floor = function(n) {
                    return t(n = new Date(+n)), n
                }, i.ceil = function(e) {
                    return t(e = new Date(e - 1)), n(e, 1), t(e), e
                }, i.round = function(t) {
                    var n = i(t),
                        e = i.ceil(t);
                    return t - n < e - t ? n : e
                }, i.offset = function(t, e) {
                    return n(t = new Date(+t), null == e ? 1 : Math.floor(e)), t
                }, i.range = function(e, r, u) {
                    var o, a = [];
                    if (e = i.ceil(e), u = null == u ? 1 : Math.floor(u), !(e < r) || !(u > 0)) return a;
                    do {
                        a.push(o = new Date(+e)), n(e, u), t(e)
                    } while (o < e && e < r);
                    return a
                }, i.filter = function(e) {
                    return ti((function(n) {
                        if (n >= n)
                            for (; t(n), !e(n);) n.setTime(n - 1)
                    }), (function(t, r) {
                        if (t >= t)
                            if (r < 0)
                                for (; ++r <= 0;)
                                    for (; n(t, -1), !e(t););
                            else
                                for (; --r >= 0;)
                                    for (; n(t, 1), !e(t););
                    }))
                }, e && (i.count = function(n, r) {
                    return Jr.setTime(+n), Kr.setTime(+r), t(Jr), t(Kr), Math.floor(e(Jr, Kr))
                }, i.every = function(t) {
                    return t = Math.floor(t), isFinite(t) && t > 0 ? t > 1 ? i.filter(r ? function(n) {
                        return r(n) % t === 0
                    } : function(n) {
                        return i.count(0, n) % t === 0
                    }) : i : null
                }), i
            }
            var ni = ti((function() {}), (function(t, n) {
                t.setTime(+t + n)
            }), (function(t, n) {
                return n - t
            }));
            ni.every = function(t) {
                return t = Math.floor(t), isFinite(t) && t > 0 ? t > 1 ? ti((function(n) {
                    n.setTime(Math.floor(n / t) * t)
                }), (function(n, e) {
                    n.setTime(+n + e * t)
                }), (function(n, e) {
                    return (e - n) / t
                })) : ni : null
            };
            var ei = ni,
                ri = (ni.range, ti((function(t) {
                    t.setTime(t - t.getMilliseconds())
                }), (function(t, n) {
                    t.setTime(+t + n * Rr)
                }), (function(t, n) {
                    return (n - t) / Rr
                }), (function(t) {
                    return t.getUTCSeconds()
                }))),
                ii = ri,
                ui = (ri.range, ti((function(t) {
                    t.setTime(t - t.getMilliseconds() - t.getSeconds() * Rr)
                }), (function(t, n) {
                    t.setTime(+t + n * Ir)
                }), (function(t, n) {
                    return (n - t) / Ir
                }), (function(t) {
                    return t.getMinutes()
                }))),
                oi = ui,
                ai = (ui.range, ti((function(t) {
                    t.setTime(t - t.getMilliseconds() - t.getSeconds() * Rr - t.getMinutes() * Ir)
                }), (function(t, n) {
                    t.setTime(+t + n * $r)
                }), (function(t, n) {
                    return (n - t) / $r
                }), (function(t) {
                    return t.getHours()
                }))),
                ci = ai,
                li = (ai.range, ti((function(t) {
                    return t.setHours(0, 0, 0, 0)
                }), (function(t, n) {
                    return t.setDate(t.getDate() + n)
                }), (function(t, n) {
                    return (n - t - (n.getTimezoneOffset() - t.getTimezoneOffset()) * Ir) / Br
                }), (function(t) {
                    return t.getDate() - 1
                }))),
                si = li;
            li.range;

            function fi(t) {
                return ti((function(n) {
                    n.setDate(n.getDate() - (n.getDay() + 7 - t) % 7), n.setHours(0, 0, 0, 0)
                }), (function(t, n) {
                    t.setDate(t.getDate() + 7 * n)
                }), (function(t, n) {
                    return (n - t - (n.getTimezoneOffset() - t.getTimezoneOffset()) * Ir) / Wr
                }))
            }
            var hi = fi(0),
                pi = fi(1),
                gi = fi(2),
                yi = fi(3),
                vi = fi(4),
                di = fi(5),
                _i = fi(6),
                mi = (hi.range, pi.range, gi.range, yi.range, vi.range, di.range, _i.range, ti((function(t) {
                    t.setDate(1), t.setHours(0, 0, 0, 0)
                }), (function(t, n) {
                    t.setMonth(t.getMonth() + n)
                }), (function(t, n) {
                    return n.getMonth() - t.getMonth() + 12 * (n.getFullYear() - t.getFullYear())
                }), (function(t) {
                    return t.getMonth()
                }))),
                wi = mi,
                xi = (mi.range, ti((function(t) {
                    t.setMonth(0, 1), t.setHours(0, 0, 0, 0)
                }), (function(t, n) {
                    t.setFullYear(t.getFullYear() + n)
                }), (function(t, n) {
                    return n.getFullYear() - t.getFullYear()
                }), (function(t) {
                    return t.getFullYear()
                })));
            xi.every = function(t) {
                return isFinite(t = Math.floor(t)) && t > 0 ? ti((function(n) {
                    n.setFullYear(Math.floor(n.getFullYear() / t) * t), n.setMonth(0, 1), n.setHours(0, 0, 0, 0)
                }), (function(n, e) {
                    n.setFullYear(n.getFullYear() + e * t)
                })) : null
            };
            var Mi = xi,
                bi = (xi.range, ti((function(t) {
                    t.setUTCSeconds(0, 0)
                }), (function(t, n) {
                    t.setTime(+t + n * Ir)
                }), (function(t, n) {
                    return (n - t) / Ir
                }), (function(t) {
                    return t.getUTCMinutes()
                }))),
                Ti = bi,
                Ai = (bi.range, ti((function(t) {
                    t.setUTCMinutes(0, 0, 0)
                }), (function(t, n) {
                    t.setTime(+t + n * $r)
                }), (function(t, n) {
                    return (n - t) / $r
                }), (function(t) {
                    return t.getUTCHours()
                }))),
                ki = Ai,
                Ci = (Ai.range, ti((function(t) {
                    t.setUTCHours(0, 0, 0, 0)
                }), (function(t, n) {
                    t.setUTCDate(t.getUTCDate() + n)
                }), (function(t, n) {
                    return (n - t) / Br
                }), (function(t) {
                    return t.getUTCDate() - 1
                }))),
                Ni = Ci;
            Ci.range;

            function Si(t) {
                return ti((function(n) {
                    n.setUTCDate(n.getUTCDate() - (n.getUTCDay() + 7 - t) % 7), n.setUTCHours(0, 0, 0, 0)
                }), (function(t, n) {
                    t.setUTCDate(t.getUTCDate() + 7 * n)
                }), (function(t, n) {
                    return (n - t) / Wr
                }))
            }
            var Di = Si(0),
                Ui = Si(1),
                Ei = Si(2),
                Yi = Si(3),
                Fi = Si(4),
                Hi = Si(5),
                Li = Si(6),
                Zi = (Di.range, Ui.range, Ei.range, Yi.range, Fi.range, Hi.range, Li.range, ti((function(t) {
                    t.setUTCDate(1), t.setUTCHours(0, 0, 0, 0)
                }), (function(t, n) {
                    t.setUTCMonth(t.getUTCMonth() + n)
                }), (function(t, n) {
                    return n.getUTCMonth() - t.getUTCMonth() + 12 * (n.getUTCFullYear() - t.getUTCFullYear())
                }), (function(t) {
                    return t.getUTCMonth()
                }))),
                Pi = Zi,
                Xi = (Zi.range, ti((function(t) {
                    t.setUTCMonth(0, 1), t.setUTCHours(0, 0, 0, 0)
                }), (function(t, n) {
                    t.setUTCFullYear(t.getUTCFullYear() + n)
                }), (function(t, n) {
                    return n.getUTCFullYear() - t.getUTCFullYear()
                }), (function(t) {
                    return t.getUTCFullYear()
                })));
            Xi.every = function(t) {
                return isFinite(t = Math.floor(t)) && t > 0 ? ti((function(n) {
                    n.setUTCFullYear(Math.floor(n.getUTCFullYear() / t) * t), n.setUTCMonth(0, 1), n.setUTCHours(0, 0, 0, 0)
                }), (function(n, e) {
                    n.setUTCFullYear(n.getUTCFullYear() + e * t)
                })) : null
            };
            var qi = Xi;
            Xi.range;

            function Oi(t, n, e, r, u, o) {
                var a = [
                    [ii, 1, Rr],
                    [ii, 5, 5e3],
                    [ii, 15, 15e3],
                    [ii, 30, 3e4],
                    [o, 1, Ir],
                    [o, 5, 3e5],
                    [o, 15, 9e5],
                    [o, 30, 18e5],
                    [u, 1, $r],
                    [u, 3, 108e5],
                    [u, 6, 216e5],
                    [u, 12, 432e5],
                    [r, 1, Br],
                    [r, 2, 1728e5],
                    [e, 1, Wr],
                    [n, 1, Gr],
                    [n, 3, 7776e6],
                    [t, 1, Qr]
                ];

                function c(n, e, r) {
                    var u = Math.abs(e - n) / r,
                        o = i((function(t) {
                            return (0, Je.Z)(t, 3)[2]
                        })).right(a, u);
                    if (o === a.length) return t.every(cr(n / Qr, e / Qr, r));
                    if (0 === o) return ei.every(Math.max(cr(n, e, r), 1));
                    var c = (0, Je.Z)(a[u / a[o - 1][2] < a[o][2] / u ? o - 1 : o], 2),
                        l = c[0],
                        s = c[1];
                    return l.every(s)
                }
                return [function(t, n, e) {
                    var r = n < t;
                    if (r) {
                        var i = [n, t];
                        t = i[0], n = i[1]
                    }
                    var u = e && "function" === typeof e.range ? e : c(t, n, e),
                        o = u ? u.range(t, +n + 1) : [];
                    return r ? o.reverse() : o
                }, c]
            }
            var ji = Oi(qi, Pi, Di, Ni, ki, Ti),
                Vi = (0, Je.Z)(ji, 2),
                zi = Vi[0],
                Ri = Vi[1],
                Ii = Oi(Mi, wi, hi, si, ci, oi),
                $i = (0, Je.Z)(Ii, 2),
                Bi = $i[0],
                Wi = $i[1];

            function Gi(t) {
                if (0 <= t.y && t.y < 100) {
                    var n = new Date(-1, t.m, t.d, t.H, t.M, t.S, t.L);
                    return n.setFullYear(t.y), n
                }
                return new Date(t.y, t.m, t.d, t.H, t.M, t.S, t.L)
            }

            function Qi(t) {
                if (0 <= t.y && t.y < 100) {
                    var n = new Date(Date.UTC(-1, t.m, t.d, t.H, t.M, t.S, t.L));
                    return n.setUTCFullYear(t.y), n
                }
                return new Date(Date.UTC(t.y, t.m, t.d, t.H, t.M, t.S, t.L))
            }

            function Ji(t, n, e) {
                return {
                    y: t,
                    m: n,
                    d: e,
                    H: 0,
                    M: 0,
                    S: 0,
                    L: 0
                }
            }
            var Ki, tu, nu, eu = {
                    "-": "",
                    _: " ",
                    0: "0"
                },
                ru = /^\s*\d+/,
                iu = /^%/,
                uu = /[\\^$*+?|[\]().{}]/g;

            function ou(t, n, e) {
                var r = t < 0 ? "-" : "",
                    i = (r ? -t : t) + "",
                    u = i.length;
                return r + (u < e ? new Array(e - u + 1).join(n) + i : i)
            }

            function au(t) {
                return t.replace(uu, "\\$&")
            }

            function cu(t) {
                return new RegExp("^(?:" + t.map(au).join("|") + ")", "i")
            }

            function lu(t) {
                return new Map(t.map((function(t, n) {
                    return [t.toLowerCase(), n]
                })))
            }

            function su(t, n, e) {
                var r = ru.exec(n.slice(e, e + 1));
                return r ? (t.w = +r[0], e + r[0].length) : -1
            }

            function fu(t, n, e) {
                var r = ru.exec(n.slice(e, e + 1));
                return r ? (t.u = +r[0], e + r[0].length) : -1
            }

            function hu(t, n, e) {
                var r = ru.exec(n.slice(e, e + 2));
                return r ? (t.U = +r[0], e + r[0].length) : -1
            }

            function pu(t, n, e) {
                var r = ru.exec(n.slice(e, e + 2));
                return r ? (t.V = +r[0], e + r[0].length) : -1
            }

            function gu(t, n, e) {
                var r = ru.exec(n.slice(e, e + 2));
                return r ? (t.W = +r[0], e + r[0].length) : -1
            }

            function yu(t, n, e) {
                var r = ru.exec(n.slice(e, e + 4));
                return r ? (t.y = +r[0], e + r[0].length) : -1
            }

            function vu(t, n, e) {
                var r = ru.exec(n.slice(e, e + 2));
                return r ? (t.y = +r[0] + (+r[0] > 68 ? 1900 : 2e3), e + r[0].length) : -1
            }

            function du(t, n, e) {
                var r = /^(Z)|([+-]\d\d)(?::?(\d\d))?/.exec(n.slice(e, e + 6));
                return r ? (t.Z = r[1] ? 0 : -(r[2] + (r[3] || "00")), e + r[0].length) : -1
            }

            function _u(t, n, e) {
                var r = ru.exec(n.slice(e, e + 1));
                return r ? (t.q = 3 * r[0] - 3, e + r[0].length) : -1
            }

            function mu(t, n, e) {
                var r = ru.exec(n.slice(e, e + 2));
                return r ? (t.m = r[0] - 1, e + r[0].length) : -1
            }

            function wu(t, n, e) {
                var r = ru.exec(n.slice(e, e + 2));
                return r ? (t.d = +r[0], e + r[0].length) : -1
            }

            function xu(t, n, e) {
                var r = ru.exec(n.slice(e, e + 3));
                return r ? (t.m = 0, t.d = +r[0], e + r[0].length) : -1
            }

            function Mu(t, n, e) {
                var r = ru.exec(n.slice(e, e + 2));
                return r ? (t.H = +r[0], e + r[0].length) : -1
            }

            function bu(t, n, e) {
                var r = ru.exec(n.slice(e, e + 2));
                return r ? (t.M = +r[0], e + r[0].length) : -1
            }

            function Tu(t, n, e) {
                var r = ru.exec(n.slice(e, e + 2));
                return r ? (t.S = +r[0], e + r[0].length) : -1
            }

            function Au(t, n, e) {
                var r = ru.exec(n.slice(e, e + 3));
                return r ? (t.L = +r[0], e + r[0].length) : -1
            }

            function ku(t, n, e) {
                var r = ru.exec(n.slice(e, e + 6));
                return r ? (t.L = Math.floor(r[0] / 1e3), e + r[0].length) : -1
            }

            function Cu(t, n, e) {
                var r = iu.exec(n.slice(e, e + 1));
                return r ? e + r[0].length : -1
            }

            function Nu(t, n, e) {
                var r = ru.exec(n.slice(e));
                return r ? (t.Q = +r[0], e + r[0].length) : -1
            }

            function Su(t, n, e) {
                var r = ru.exec(n.slice(e));
                return r ? (t.s = +r[0], e + r[0].length) : -1
            }

            function Du(t, n) {
                return ou(t.getDate(), n, 2)
            }

            function Uu(t, n) {
                return ou(t.getHours(), n, 2)
            }

            function Eu(t, n) {
                return ou(t.getHours() % 12 || 12, n, 2)
            }

            function Yu(t, n) {
                return ou(1 + si.count(Mi(t), t), n, 3)
            }

            function Fu(t, n) {
                return ou(t.getMilliseconds(), n, 3)
            }

            function Hu(t, n) {
                return Fu(t, n) + "000"
            }

            function Lu(t, n) {
                return ou(t.getMonth() + 1, n, 2)
            }

            function Zu(t, n) {
                return ou(t.getMinutes(), n, 2)
            }

            function Pu(t, n) {
                return ou(t.getSeconds(), n, 2)
            }

            function Xu(t) {
                var n = t.getDay();
                return 0 === n ? 7 : n
            }

            function qu(t, n) {
                return ou(hi.count(Mi(t) - 1, t), n, 2)
            }

            function Ou(t) {
                var n = t.getDay();
                return n >= 4 || 0 === n ? vi(t) : vi.ceil(t)
            }

            function ju(t, n) {
                return t = Ou(t), ou(vi.count(Mi(t), t) + (4 === Mi(t).getDay()), n, 2)
            }

            function Vu(t) {
                return t.getDay()
            }

            function zu(t, n) {
                return ou(pi.count(Mi(t) - 1, t), n, 2)
            }

            function Ru(t, n) {
                return ou(t.getFullYear() % 100, n, 2)
            }

            function Iu(t, n) {
                return ou((t = Ou(t)).getFullYear() % 100, n, 2)
            }

            function $u(t, n) {
                return ou(t.getFullYear() % 1e4, n, 4)
            }

            function Bu(t, n) {
                var e = t.getDay();
                return ou((t = e >= 4 || 0 === e ? vi(t) : vi.ceil(t)).getFullYear() % 1e4, n, 4)
            }

            function Wu(t) {
                var n = t.getTimezoneOffset();
                return (n > 0 ? "-" : (n *= -1, "+")) + ou(n / 60 | 0, "0", 2) + ou(n % 60, "0", 2)
            }

            function Gu(t, n) {
                return ou(t.getUTCDate(), n, 2)
            }

            function Qu(t, n) {
                return ou(t.getUTCHours(), n, 2)
            }

            function Ju(t, n) {
                return ou(t.getUTCHours() % 12 || 12, n, 2)
            }

            function Ku(t, n) {
                return ou(1 + Ni.count(qi(t), t), n, 3)
            }

            function to(t, n) {
                return ou(t.getUTCMilliseconds(), n, 3)
            }

            function no(t, n) {
                return to(t, n) + "000"
            }

            function eo(t, n) {
                return ou(t.getUTCMonth() + 1, n, 2)
            }

            function ro(t, n) {
                return ou(t.getUTCMinutes(), n, 2)
            }

            function io(t, n) {
                return ou(t.getUTCSeconds(), n, 2)
            }

            function uo(t) {
                var n = t.getUTCDay();
                return 0 === n ? 7 : n
            }

            function oo(t, n) {
                return ou(Di.count(qi(t) - 1, t), n, 2)
            }

            function ao(t) {
                var n = t.getUTCDay();
                return n >= 4 || 0 === n ? Fi(t) : Fi.ceil(t)
            }

            function co(t, n) {
                return t = ao(t), ou(Fi.count(qi(t), t) + (4 === qi(t).getUTCDay()), n, 2)
            }

            function lo(t) {
                return t.getUTCDay()
            }

            function so(t, n) {
                return ou(Ui.count(qi(t) - 1, t), n, 2)
            }

            function fo(t, n) {
                return ou(t.getUTCFullYear() % 100, n, 2)
            }

            function ho(t, n) {
                return ou((t = ao(t)).getUTCFullYear() % 100, n, 2)
            }

            function po(t, n) {
                return ou(t.getUTCFullYear() % 1e4, n, 4)
            }

            function go(t, n) {
                var e = t.getUTCDay();
                return ou((t = e >= 4 || 0 === e ? Fi(t) : Fi.ceil(t)).getUTCFullYear() % 1e4, n, 4)
            }

            function yo() {
                return "+0000"
            }

            function vo() {
                return "%"
            }

            function _o(t) {
                return +t
            }

            function mo(t) {
                return Math.floor(+t / 1e3)
            }

            function wo(t) {
                return new Date(t)
            }

            function xo(t) {
                return t instanceof Date ? +t : +new Date(+t)
            }

            function Mo(t, n, e, r, i, u, o, a, c, l) {
                var s = kr(),
                    f = s.invert,
                    h = s.domain,
                    p = l(".%L"),
                    g = l(":%S"),
                    y = l("%I:%M"),
                    v = l("%I %p"),
                    d = l("%a %d"),
                    _ = l("%b %d"),
                    m = l("%B"),
                    w = l("%Y");

                function x(t) {
                    return (c(t) < t ? p : a(t) < t ? g : o(t) < t ? y : u(t) < t ? v : r(t) < t ? i(t) < t ? d : _ : e(t) < t ? m : w)(t)
                }
                return s.invert = function(t) {
                    return new Date(f(t))
                }, s.domain = function(t) {
                    return arguments.length ? h(Array.from(t, xo)) : h().map(wo)
                }, s.ticks = function(n) {
                    var e = h();
                    return t(e[0], e[e.length - 1], null == n ? 10 : n)
                }, s.tickFormat = function(t, n) {
                    return null == n ? x : l(n)
                }, s.nice = function(t) {
                    var e = h();
                    return t && "function" === typeof t.range || (t = n(e[0], e[e.length - 1], null == t ? 10 : t)), t ? h(function(t, n) {
                        var e, r = 0,
                            i = (t = t.slice()).length - 1,
                            u = t[r],
                            o = t[i];
                        return o < u && (e = r, r = i, i = e, e = u, u = o, o = e), t[r] = n.floor(u), t[i] = n.ceil(o), t
                    }(e, t)) : s
                }, s.copy = function() {
                    return Tr(s, Mo(t, n, e, r, i, u, o, a, c, l))
                }, s
            }

            function bo() {
                return tr.apply(Mo(Bi, Wi, Mi, wi, hi, si, ci, oi, ii, tu).domain([new Date(2e3, 0, 1), new Date(2e3, 0, 2)]), arguments)
            }

            function To() {
                return tr.apply(Mo(zi, Ri, qi, Pi, Di, Ni, ki, Ti, ii, nu).domain([Date.UTC(2e3, 0, 1), Date.UTC(2e3, 0, 2)]), arguments)
            }

            function Ao(t, n) {
                if (t = function(t) {
                        for (var n; n = t.sourceEvent;) t = n;
                        return t
                    }(t), void 0 === n && (n = t.currentTarget), n) {
                    var e = n.ownerSVGElement || n;
                    if (e.createSVGPoint) {
                        var r = e.createSVGPoint();
                        return r.x = t.clientX, r.y = t.clientY, [(r = r.matrixTransform(n.getScreenCTM().inverse())).x, r.y]
                    }
                    if (n.getBoundingClientRect) {
                        var i = n.getBoundingClientRect();
                        return [t.clientX - i.left - n.clientLeft, t.clientY - i.top - n.clientTop]
                    }
                }
                return [t.pageX, t.pageY]
            }

            function ko(t) {
                return "string" === typeof t ? new Xt([
                    [document.querySelector(t)]
                ], [document.documentElement]) : new Xt([
                    [t]
                ], Pt)
            }! function(t) {
                Ki = function(t) {
                    var n = t.dateTime,
                        e = t.date,
                        r = t.time,
                        i = t.periods,
                        u = t.days,
                        o = t.shortDays,
                        a = t.months,
                        c = t.shortMonths,
                        l = cu(i),
                        s = lu(i),
                        f = cu(u),
                        h = lu(u),
                        p = cu(o),
                        g = lu(o),
                        y = cu(a),
                        v = lu(a),
                        d = cu(c),
                        _ = lu(c),
                        m = {
                            a: function(t) {
                                return o[t.getDay()]
                            },
                            A: function(t) {
                                return u[t.getDay()]
                            },
                            b: function(t) {
                                return c[t.getMonth()]
                            },
                            B: function(t) {
                                return a[t.getMonth()]
                            },
                            c: null,
                            d: Du,
                            e: Du,
                            f: Hu,
                            g: Iu,
                            G: Bu,
                            H: Uu,
                            I: Eu,
                            j: Yu,
                            L: Fu,
                            m: Lu,
                            M: Zu,
                            p: function(t) {
                                return i[+(t.getHours() >= 12)]
                            },
                            q: function(t) {
                                return 1 + ~~(t.getMonth() / 3)
                            },
                            Q: _o,
                            s: mo,
                            S: Pu,
                            u: Xu,
                            U: qu,
                            V: ju,
                            w: Vu,
                            W: zu,
                            x: null,
                            X: null,
                            y: Ru,
                            Y: $u,
                            Z: Wu,
                            "%": vo
                        },
                        w = {
                            a: function(t) {
                                return o[t.getUTCDay()]
                            },
                            A: function(t) {
                                return u[t.getUTCDay()]
                            },
                            b: function(t) {
                                return c[t.getUTCMonth()]
                            },
                            B: function(t) {
                                return a[t.getUTCMonth()]
                            },
                            c: null,
                            d: Gu,
                            e: Gu,
                            f: no,
                            g: ho,
                            G: go,
                            H: Qu,
                            I: Ju,
                            j: Ku,
                            L: to,
                            m: eo,
                            M: ro,
                            p: function(t) {
                                return i[+(t.getUTCHours() >= 12)]
                            },
                            q: function(t) {
                                return 1 + ~~(t.getUTCMonth() / 3)
                            },
                            Q: _o,
                            s: mo,
                            S: io,
                            u: uo,
                            U: oo,
                            V: co,
                            w: lo,
                            W: so,
                            x: null,
                            X: null,
                            y: fo,
                            Y: po,
                            Z: yo,
                            "%": vo
                        },
                        x = {
                            a: function(t, n, e) {
                                var r = p.exec(n.slice(e));
                                return r ? (t.w = g.get(r[0].toLowerCase()), e + r[0].length) : -1
                            },
                            A: function(t, n, e) {
                                var r = f.exec(n.slice(e));
                                return r ? (t.w = h.get(r[0].toLowerCase()), e + r[0].length) : -1
                            },
                            b: function(t, n, e) {
                                var r = d.exec(n.slice(e));
                                return r ? (t.m = _.get(r[0].toLowerCase()), e + r[0].length) : -1
                            },
                            B: function(t, n, e) {
                                var r = y.exec(n.slice(e));
                                return r ? (t.m = v.get(r[0].toLowerCase()), e + r[0].length) : -1
                            },
                            c: function(t, e, r) {
                                return T(t, n, e, r)
                            },
                            d: wu,
                            e: wu,
                            f: ku,
                            g: vu,
                            G: yu,
                            H: Mu,
                            I: Mu,
                            j: xu,
                            L: Au,
                            m: mu,
                            M: bu,
                            p: function(t, n, e) {
                                var r = l.exec(n.slice(e));
                                return r ? (t.p = s.get(r[0].toLowerCase()), e + r[0].length) : -1
                            },
                            q: _u,
                            Q: Nu,
                            s: Su,
                            S: Tu,
                            u: fu,
                            U: hu,
                            V: pu,
                            w: su,
                            W: gu,
                            x: function(t, n, r) {
                                return T(t, e, n, r)
                            },
                            X: function(t, n, e) {
                                return T(t, r, n, e)
                            },
                            y: vu,
                            Y: yu,
                            Z: du,
                            "%": Cu
                        };

                    function M(t, n) {
                        return function(e) {
                            var r, i, u, o = [],
                                a = -1,
                                c = 0,
                                l = t.length;
                            for (e instanceof Date || (e = new Date(+e)); ++a < l;) 37 === t.charCodeAt(a) && (o.push(t.slice(c, a)), null != (i = eu[r = t.charAt(++a)]) ? r = t.charAt(++a) : i = "e" === r ? " " : "0", (u = n[r]) && (r = u(e, i)), o.push(r), c = a + 1);
                            return o.push(t.slice(c, a)), o.join("")
                        }
                    }

                    function b(t, n) {
                        return function(e) {
                            var r, i, u = Ji(1900, void 0, 1);
                            if (T(u, t, e += "", 0) != e.length) return null;
                            if ("Q" in u) return new Date(u.Q);
                            if ("s" in u) return new Date(1e3 * u.s + ("L" in u ? u.L : 0));
                            if (n && !("Z" in u) && (u.Z = 0), "p" in u && (u.H = u.H % 12 + 12 * u.p), void 0 === u.m && (u.m = "q" in u ? u.q : 0), "V" in u) {
                                if (u.V < 1 || u.V > 53) return null;
                                "w" in u || (u.w = 1), "Z" in u ? (i = (r = Qi(Ji(u.y, 0, 1))).getUTCDay(), r = i > 4 || 0 === i ? Ui.ceil(r) : Ui(r), r = Ni.offset(r, 7 * (u.V - 1)), u.y = r.getUTCFullYear(), u.m = r.getUTCMonth(), u.d = r.getUTCDate() + (u.w + 6) % 7) : (i = (r = Gi(Ji(u.y, 0, 1))).getDay(), r = i > 4 || 0 === i ? pi.ceil(r) : pi(r), r = si.offset(r, 7 * (u.V - 1)), u.y = r.getFullYear(), u.m = r.getMonth(), u.d = r.getDate() + (u.w + 6) % 7)
                            } else("W" in u || "U" in u) && ("w" in u || (u.w = "u" in u ? u.u % 7 : "W" in u ? 1 : 0), i = "Z" in u ? Qi(Ji(u.y, 0, 1)).getUTCDay() : Gi(Ji(u.y, 0, 1)).getDay(), u.m = 0, u.d = "W" in u ? (u.w + 6) % 7 + 7 * u.W - (i + 5) % 7 : u.w + 7 * u.U - (i + 6) % 7);
                            return "Z" in u ? (u.H += u.Z / 100 | 0, u.M += u.Z % 100, Qi(u)) : Gi(u)
                        }
                    }

                    function T(t, n, e, r) {
                        for (var i, u, o = 0, a = n.length, c = e.length; o < a;) {
                            if (r >= c) return -1;
                            if (37 === (i = n.charCodeAt(o++))) {
                                if (i = n.charAt(o++), !(u = x[i in eu ? n.charAt(o++) : i]) || (r = u(t, e, r)) < 0) return -1
                            } else if (i != e.charCodeAt(r++)) return -1
                        }
                        return r
                    }
                    return m.x = M(e, m), m.X = M(r, m), m.c = M(n, m), w.x = M(e, w), w.X = M(r, w), w.c = M(n, w), {
                        format: function(t) {
                            var n = M(t += "", m);
                            return n.toString = function() {
                                return t
                            }, n
                        },
                        parse: function(t) {
                            var n = b(t += "", !1);
                            return n.toString = function() {
                                return t
                            }, n
                        },
                        utcFormat: function(t) {
                            var n = M(t += "", w);
                            return n.toString = function() {
                                return t
                            }, n
                        },
                        utcParse: function(t) {
                            var n = b(t += "", !0);
                            return n.toString = function() {
                                return t
                            }, n
                        }
                    }
                }(t), tu = Ki.format, Ki.parse, nu = Ki.utcFormat, Ki.utcParse
            }({
                dateTime: "%x, %X",
                date: "%-m/%-d/%Y",
                time: "%-I:%M:%S %p",
                periods: ["AM", "PM"],
                days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                shortDays: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                shortMonths: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
            });
            var Co = Math.PI,
                No = 2 * Co,
                So = 1e-6,
                Do = No - So;

            function Uo() {
                this._x0 = this._y0 = this._x1 = this._y1 = null, this._ = ""
            }

            function Eo() {
                return new Uo
            }
            Uo.prototype = Eo.prototype = {
                constructor: Uo,
                moveTo: function(t, n) {
                    this._ += "M" + (this._x0 = this._x1 = +t) + "," + (this._y0 = this._y1 = +n)
                },
                closePath: function() {
                    null !== this._x1 && (this._x1 = this._x0, this._y1 = this._y0, this._ += "Z")
                },
                lineTo: function(t, n) {
                    this._ += "L" + (this._x1 = +t) + "," + (this._y1 = +n)
                },
                quadraticCurveTo: function(t, n, e, r) {
                    this._ += "Q" + +t + "," + +n + "," + (this._x1 = +e) + "," + (this._y1 = +r)
                },
                bezierCurveTo: function(t, n, e, r, i, u) {
                    this._ += "C" + +t + "," + +n + "," + +e + "," + +r + "," + (this._x1 = +i) + "," + (this._y1 = +u)
                },
                arcTo: function(t, n, e, r, i) {
                    t = +t, n = +n, e = +e, r = +r, i = +i;
                    var u = this._x1,
                        o = this._y1,
                        a = e - t,
                        c = r - n,
                        l = u - t,
                        s = o - n,
                        f = l * l + s * s;
                    if (i < 0) throw new Error("negative radius: " + i);
                    if (null === this._x1) this._ += "M" + (this._x1 = t) + "," + (this._y1 = n);
                    else if (f > So)
                        if (Math.abs(s * a - c * l) > So && i) {
                            var h = e - u,
                                p = r - o,
                                g = a * a + c * c,
                                y = h * h + p * p,
                                v = Math.sqrt(g),
                                d = Math.sqrt(f),
                                _ = i * Math.tan((Co - Math.acos((g + f - y) / (2 * v * d))) / 2),
                                m = _ / d,
                                w = _ / v;
                            Math.abs(m - 1) > So && (this._ += "L" + (t + m * l) + "," + (n + m * s)), this._ += "A" + i + "," + i + ",0,0," + +(s * h > l * p) + "," + (this._x1 = t + w * a) + "," + (this._y1 = n + w * c)
                        } else this._ += "L" + (this._x1 = t) + "," + (this._y1 = n);
                    else;
                },
                arc: function(t, n, e, r, i, u) {
                    t = +t, n = +n, u = !!u;
                    var o = (e = +e) * Math.cos(r),
                        a = e * Math.sin(r),
                        c = t + o,
                        l = n + a,
                        s = 1 ^ u,
                        f = u ? r - i : i - r;
                    if (e < 0) throw new Error("negative radius: " + e);
                    null === this._x1 ? this._ += "M" + c + "," + l : (Math.abs(this._x1 - c) > So || Math.abs(this._y1 - l) > So) && (this._ += "L" + c + "," + l), e && (f < 0 && (f = f % No + No), f > Do ? this._ += "A" + e + "," + e + ",0,1," + s + "," + (t - o) + "," + (n - a) + "A" + e + "," + e + ",0,1," + s + "," + (this._x1 = c) + "," + (this._y1 = l) : f > So && (this._ += "A" + e + "," + e + ",0," + +(f >= Co) + "," + s + "," + (this._x1 = t + e * Math.cos(i)) + "," + (this._y1 = n + e * Math.sin(i))))
                },
                rect: function(t, n, e, r) {
                    this._ += "M" + (this._x0 = this._x1 = +t) + "," + (this._y0 = this._y1 = +n) + "h" + +e + "v" + +r + "h" + -e + "Z"
                },
                toString: function() {
                    return this._
                }
            };
            var Yo = Eo;

            function Fo(t) {
                return function() {
                    return t
                }
            }
            var Ho = Math.abs,
                Lo = Math.atan2,
                Zo = Math.cos,
                Po = Math.max,
                Xo = Math.min,
                qo = Math.sin,
                Oo = Math.sqrt,
                jo = 1e-12,
                Vo = Math.PI,
                zo = Vo / 2,
                Ro = 2 * Vo;

            function Io(t) {
                return t > 1 ? 0 : t < -1 ? Vo : Math.acos(t)
            }

            function $o(t) {
                return t >= 1 ? zo : t <= -1 ? -zo : Math.asin(t)
            }

            function Bo(t) {
                return t.innerRadius
            }

            function Wo(t) {
                return t.outerRadius
            }

            function Go(t) {
                return t.startAngle
            }

            function Qo(t) {
                return t.endAngle
            }

            function Jo(t) {
                return t && t.padAngle
            }

            function Ko(t, n, e, r, i, u, o, a) {
                var c = e - t,
                    l = r - n,
                    s = o - i,
                    f = a - u,
                    h = f * c - s * l;
                if (!(h * h < jo)) return [t + (h = (s * (n - u) - f * (t - i)) / h) * c, n + h * l]
            }

            function ta(t, n, e, r, i, u, o) {
                var a = t - e,
                    c = n - r,
                    l = (o ? u : -u) / Oo(a * a + c * c),
                    s = l * c,
                    f = -l * a,
                    h = t + s,
                    p = n + f,
                    g = e + s,
                    y = r + f,
                    v = (h + g) / 2,
                    d = (p + y) / 2,
                    _ = g - h,
                    m = y - p,
                    w = _ * _ + m * m,
                    x = i - u,
                    M = h * y - g * p,
                    b = (m < 0 ? -1 : 1) * Oo(Po(0, x * x * w - M * M)),
                    T = (M * m - _ * b) / w,
                    A = (-M * _ - m * b) / w,
                    k = (M * m + _ * b) / w,
                    C = (-M * _ + m * b) / w,
                    N = T - v,
                    S = A - d,
                    D = k - v,
                    U = C - d;
                return N * N + S * S > D * D + U * U && (T = k, A = C), {
                    cx: T,
                    cy: A,
                    x01: -s,
                    y01: -f,
                    x11: T * (i / x - 1),
                    y11: A * (i / x - 1)
                }
            }

            function na() {
                var t = Bo,
                    n = Wo,
                    e = Fo(0),
                    r = null,
                    i = Go,
                    u = Qo,
                    o = Jo,
                    a = null;

                function c() {
                    var c, l, s = +t.apply(this, arguments),
                        f = +n.apply(this, arguments),
                        h = i.apply(this, arguments) - zo,
                        p = u.apply(this, arguments) - zo,
                        g = Ho(p - h),
                        y = p > h;
                    if (a || (a = c = Yo()), f < s && (l = f, f = s, s = l), f > jo)
                        if (g > Ro - jo) a.moveTo(f * Zo(h), f * qo(h)), a.arc(0, 0, f, h, p, !y), s > jo && (a.moveTo(s * Zo(p), s * qo(p)), a.arc(0, 0, s, p, h, y));
                        else {
                            var v, d, _ = h,
                                m = p,
                                w = h,
                                x = p,
                                M = g,
                                b = g,
                                T = o.apply(this, arguments) / 2,
                                A = T > jo && (r ? +r.apply(this, arguments) : Oo(s * s + f * f)),
                                k = Xo(Ho(f - s) / 2, +e.apply(this, arguments)),
                                C = k,
                                N = k;
                            if (A > jo) {
                                var S = $o(A / s * qo(T)),
                                    D = $o(A / f * qo(T));
                                (M -= 2 * S) > jo ? (w += S *= y ? 1 : -1, x -= S) : (M = 0, w = x = (h + p) / 2), (b -= 2 * D) > jo ? (_ += D *= y ? 1 : -1, m -= D) : (b = 0, _ = m = (h + p) / 2)
                            }
                            var U = f * Zo(_),
                                E = f * qo(_),
                                Y = s * Zo(x),
                                F = s * qo(x);
                            if (k > jo) {
                                var H, L = f * Zo(m),
                                    Z = f * qo(m),
                                    P = s * Zo(w),
                                    X = s * qo(w);
                                if (g < Vo && (H = Ko(U, E, P, X, L, Z, Y, F))) {
                                    var q = U - H[0],
                                        O = E - H[1],
                                        j = L - H[0],
                                        V = Z - H[1],
                                        z = 1 / qo(Io((q * j + O * V) / (Oo(q * q + O * O) * Oo(j * j + V * V))) / 2),
                                        R = Oo(H[0] * H[0] + H[1] * H[1]);
                                    C = Xo(k, (s - R) / (z - 1)), N = Xo(k, (f - R) / (z + 1))
                                }
                            }
                            b > jo ? N > jo ? (v = ta(P, X, U, E, f, N, y), d = ta(L, Z, Y, F, f, N, y), a.moveTo(v.cx + v.x01, v.cy + v.y01), N < k ? a.arc(v.cx, v.cy, N, Lo(v.y01, v.x01), Lo(d.y01, d.x01), !y) : (a.arc(v.cx, v.cy, N, Lo(v.y01, v.x01), Lo(v.y11, v.x11), !y), a.arc(0, 0, f, Lo(v.cy + v.y11, v.cx + v.x11), Lo(d.cy + d.y11, d.cx + d.x11), !y), a.arc(d.cx, d.cy, N, Lo(d.y11, d.x11), Lo(d.y01, d.x01), !y))) : (a.moveTo(U, E), a.arc(0, 0, f, _, m, !y)) : a.moveTo(U, E), s > jo && M > jo ? C > jo ? (v = ta(Y, F, L, Z, s, -C, y), d = ta(U, E, P, X, s, -C, y), a.lineTo(v.cx + v.x01, v.cy + v.y01), C < k ? a.arc(v.cx, v.cy, C, Lo(v.y01, v.x01), Lo(d.y01, d.x01), !y) : (a.arc(v.cx, v.cy, C, Lo(v.y01, v.x01), Lo(v.y11, v.x11), !y), a.arc(0, 0, s, Lo(v.cy + v.y11, v.cx + v.x11), Lo(d.cy + d.y11, d.cx + d.x11), y), a.arc(d.cx, d.cy, C, Lo(d.y11, d.x11), Lo(d.y01, d.x01), !y))) : a.arc(0, 0, s, x, w, y) : a.lineTo(Y, F)
                        }
                    else a.moveTo(0, 0);
                    if (a.closePath(), c) return a = null, c + "" || null
                }
                return c.centroid = function() {
                    var e = (+t.apply(this, arguments) + +n.apply(this, arguments)) / 2,
                        r = (+i.apply(this, arguments) + +u.apply(this, arguments)) / 2 - Vo / 2;
                    return [Zo(r) * e, qo(r) * e]
                }, c.innerRadius = function(n) {
                    return arguments.length ? (t = "function" === typeof n ? n : Fo(+n), c) : t
                }, c.outerRadius = function(t) {
                    return arguments.length ? (n = "function" === typeof t ? t : Fo(+t), c) : n
                }, c.cornerRadius = function(t) {
                    return arguments.length ? (e = "function" === typeof t ? t : Fo(+t), c) : e
                }, c.padRadius = function(t) {
                    return arguments.length ? (r = null == t ? null : "function" === typeof t ? t : Fo(+t), c) : r
                }, c.startAngle = function(t) {
                    return arguments.length ? (i = "function" === typeof t ? t : Fo(+t), c) : i
                }, c.endAngle = function(t) {
                    return arguments.length ? (u = "function" === typeof t ? t : Fo(+t), c) : u
                }, c.padAngle = function(t) {
                    return arguments.length ? (o = "function" === typeof t ? t : Fo(+t), c) : o
                }, c.context = function(t) {
                    return arguments.length ? (a = null == t ? null : t, c) : a
                }, c
            }
            Array.prototype.slice;

            function ea(t) {
                return "object" === typeof t && "length" in t ? t : Array.from(t)
            }

            function ra(t) {
                this._context = t
            }

            function ia(t) {
                return new ra(t)
            }

            function ua(t) {
                return t[0]
            }

            function oa(t) {
                return t[1]
            }

            function aa(t, n) {
                var e = Fo(!0),
                    r = null,
                    i = ia,
                    u = null;

                function o(o) {
                    var a, c, l, s = (o = ea(o)).length,
                        f = !1;
                    for (null == r && (u = i(l = Yo())), a = 0; a <= s; ++a) !(a < s && e(c = o[a], a, o)) === f && ((f = !f) ? u.lineStart() : u.lineEnd()), f && u.point(+t(c, a, o), +n(c, a, o));
                    if (l) return u = null, l + "" || null
                }
                return t = "function" === typeof t ? t : void 0 === t ? ua : Fo(t), n = "function" === typeof n ? n : void 0 === n ? oa : Fo(n), o.x = function(n) {
                    return arguments.length ? (t = "function" === typeof n ? n : Fo(+n), o) : t
                }, o.y = function(t) {
                    return arguments.length ? (n = "function" === typeof t ? t : Fo(+t), o) : n
                }, o.defined = function(t) {
                    return arguments.length ? (e = "function" === typeof t ? t : Fo(!!t), o) : e
                }, o.curve = function(t) {
                    return arguments.length ? (i = t, null != r && (u = i(r)), o) : i
                }, o.context = function(t) {
                    return arguments.length ? (null == t ? r = u = null : u = i(r = t), o) : r
                }, o
            }

            function ca(t, n, e) {
                var r = null,
                    i = Fo(!0),
                    u = null,
                    o = ia,
                    a = null;

                function c(c) {
                    var l, s, f, h, p, g = (c = ea(c)).length,
                        y = !1,
                        v = new Array(g),
                        d = new Array(g);
                    for (null == u && (a = o(p = Yo())), l = 0; l <= g; ++l) {
                        if (!(l < g && i(h = c[l], l, c)) === y)
                            if (y = !y) s = l, a.areaStart(), a.lineStart();
                            else {
                                for (a.lineEnd(), a.lineStart(), f = l - 1; f >= s; --f) a.point(v[f], d[f]);
                                a.lineEnd(), a.areaEnd()
                            }
                        y && (v[l] = +t(h, l, c), d[l] = +n(h, l, c), a.point(r ? +r(h, l, c) : v[l], e ? +e(h, l, c) : d[l]))
                    }
                    if (p) return a = null, p + "" || null
                }

                function l() {
                    return aa().defined(i).curve(o).context(u)
                }
                return t = "function" === typeof t ? t : void 0 === t ? ua : Fo(+t), n = "function" === typeof n ? n : Fo(void 0 === n ? 0 : +n), e = "function" === typeof e ? e : void 0 === e ? oa : Fo(+e), c.x = function(n) {
                    return arguments.length ? (t = "function" === typeof n ? n : Fo(+n), r = null, c) : t
                }, c.x0 = function(n) {
                    return arguments.length ? (t = "function" === typeof n ? n : Fo(+n), c) : t
                }, c.x1 = function(t) {
                    return arguments.length ? (r = null == t ? null : "function" === typeof t ? t : Fo(+t), c) : r
                }, c.y = function(t) {
                    return arguments.length ? (n = "function" === typeof t ? t : Fo(+t), e = null, c) : n
                }, c.y0 = function(t) {
                    return arguments.length ? (n = "function" === typeof t ? t : Fo(+t), c) : n
                }, c.y1 = function(t) {
                    return arguments.length ? (e = null == t ? null : "function" === typeof t ? t : Fo(+t), c) : e
                }, c.lineX0 = c.lineY0 = function() {
                    return l().x(t).y(n)
                }, c.lineY1 = function() {
                    return l().x(t).y(e)
                }, c.lineX1 = function() {
                    return l().x(r).y(n)
                }, c.defined = function(t) {
                    return arguments.length ? (i = "function" === typeof t ? t : Fo(!!t), c) : i
                }, c.curve = function(t) {
                    return arguments.length ? (o = t, null != u && (a = o(u)), c) : o
                }, c.context = function(t) {
                    return arguments.length ? (null == t ? u = a = null : a = o(u = t), c) : u
                }, c
            }

            function la(t, n) {
                return n < t ? -1 : n > t ? 1 : n >= t ? 0 : NaN
            }

            function sa(t) {
                return t
            }

            function fa() {
                var t = sa,
                    n = la,
                    e = null,
                    r = Fo(0),
                    i = Fo(Ro),
                    u = Fo(0);

                function o(o) {
                    var a, c, l, s, f, h = (o = ea(o)).length,
                        p = 0,
                        g = new Array(h),
                        y = new Array(h),
                        v = +r.apply(this, arguments),
                        d = Math.min(Ro, Math.max(-Ro, i.apply(this, arguments) - v)),
                        _ = Math.min(Math.abs(d) / h, u.apply(this, arguments)),
                        m = _ * (d < 0 ? -1 : 1);
                    for (a = 0; a < h; ++a)(f = y[g[a] = a] = +t(o[a], a, o)) > 0 && (p += f);
                    for (null != n ? g.sort((function(t, e) {
                            return n(y[t], y[e])
                        })) : null != e && g.sort((function(t, n) {
                            return e(o[t], o[n])
                        })), a = 0, l = p ? (d - h * m) / p : 0; a < h; ++a, v = s) c = g[a], s = v + ((f = y[c]) > 0 ? f * l : 0) + m, y[c] = {
                        data: o[c],
                        index: a,
                        value: f,
                        startAngle: v,
                        endAngle: s,
                        padAngle: _
                    };
                    return y
                }
                return o.value = function(n) {
                    return arguments.length ? (t = "function" === typeof n ? n : Fo(+n), o) : t
                }, o.sortValues = function(t) {
                    return arguments.length ? (n = t, e = null, o) : n
                }, o.sort = function(t) {
                    return arguments.length ? (e = t, n = null, o) : e
                }, o.startAngle = function(t) {
                    return arguments.length ? (r = "function" === typeof t ? t : Fo(+t), o) : r
                }, o.endAngle = function(t) {
                    return arguments.length ? (i = "function" === typeof t ? t : Fo(+t), o) : i
                }, o.padAngle = function(t) {
                    return arguments.length ? (u = "function" === typeof t ? t : Fo(+t), o) : u
                }, o
            }

            function ha(t, n, e) {
                t._context.bezierCurveTo((2 * t._x0 + t._x1) / 3, (2 * t._y0 + t._y1) / 3, (t._x0 + 2 * t._x1) / 3, (t._y0 + 2 * t._y1) / 3, (t._x0 + 4 * t._x1 + n) / 6, (t._y0 + 4 * t._y1 + e) / 6)
            }

            function pa(t) {
                this._context = t
            }

            function ga(t) {
                return new pa(t)
            }

            function ya(t) {
                return t < 0 ? -1 : 1
            }

            function va(t, n, e) {
                var r = t._x1 - t._x0,
                    i = n - t._x1,
                    u = (t._y1 - t._y0) / (r || i < 0 && -0),
                    o = (e - t._y1) / (i || r < 0 && -0),
                    a = (u * i + o * r) / (r + i);
                return (ya(u) + ya(o)) * Math.min(Math.abs(u), Math.abs(o), .5 * Math.abs(a)) || 0
            }

            function da(t, n) {
                var e = t._x1 - t._x0;
                return e ? (3 * (t._y1 - t._y0) / e - n) / 2 : n
            }

            function _a(t, n, e) {
                var r = t._x0,
                    i = t._y0,
                    u = t._x1,
                    o = t._y1,
                    a = (u - r) / 3;
                t._context.bezierCurveTo(r + a, i + a * n, u - a, o - a * e, u, o)
            }

            function ma(t) {
                this._context = t
            }

            function wa(t) {
                this._context = new xa(t)
            }

            function xa(t) {
                this._context = t
            }

            function Ma(t) {
                return new ma(t)
            }

            function ba(t, n, e) {
                this.k = t, this.x = n, this.y = e
            }
            ra.prototype = {
                areaStart: function() {
                    this._line = 0
                },
                areaEnd: function() {
                    this._line = NaN
                },
                lineStart: function() {
                    this._point = 0
                },
                lineEnd: function() {
                    (this._line || 0 !== this._line && 1 === this._point) && this._context.closePath(), this._line = 1 - this._line
                },
                point: function(t, n) {
                    switch (t = +t, n = +n, this._point) {
                        case 0:
                            this._point = 1, this._line ? this._context.lineTo(t, n) : this._context.moveTo(t, n);
                            break;
                        case 1:
                            this._point = 2;
                        default:
                            this._context.lineTo(t, n)
                    }
                }
            }, pa.prototype = {
                areaStart: function() {
                    this._line = 0
                },
                areaEnd: function() {
                    this._line = NaN
                },
                lineStart: function() {
                    this._x0 = this._x1 = this._y0 = this._y1 = NaN, this._point = 0
                },
                lineEnd: function() {
                    switch (this._point) {
                        case 3:
                            ha(this, this._x1, this._y1);
                        case 2:
                            this._context.lineTo(this._x1, this._y1)
                    }(this._line || 0 !== this._line && 1 === this._point) && this._context.closePath(), this._line = 1 - this._line
                },
                point: function(t, n) {
                    switch (t = +t, n = +n, this._point) {
                        case 0:
                            this._point = 1, this._line ? this._context.lineTo(t, n) : this._context.moveTo(t, n);
                            break;
                        case 1:
                            this._point = 2;
                            break;
                        case 2:
                            this._point = 3, this._context.lineTo((5 * this._x0 + this._x1) / 6, (5 * this._y0 + this._y1) / 6);
                        default:
                            ha(this, t, n)
                    }
                    this._x0 = this._x1, this._x1 = t, this._y0 = this._y1, this._y1 = n
                }
            }, ma.prototype = {
                areaStart: function() {
                    this._line = 0
                },
                areaEnd: function() {
                    this._line = NaN
                },
                lineStart: function() {
                    this._x0 = this._x1 = this._y0 = this._y1 = this._t0 = NaN, this._point = 0
                },
                lineEnd: function() {
                    switch (this._point) {
                        case 2:
                            this._context.lineTo(this._x1, this._y1);
                            break;
                        case 3:
                            _a(this, this._t0, da(this, this._t0))
                    }(this._line || 0 !== this._line && 1 === this._point) && this._context.closePath(), this._line = 1 - this._line
                },
                point: function(t, n) {
                    var e = NaN;
                    if (n = +n, (t = +t) !== this._x1 || n !== this._y1) {
                        switch (this._point) {
                            case 0:
                                this._point = 1, this._line ? this._context.lineTo(t, n) : this._context.moveTo(t, n);
                                break;
                            case 1:
                                this._point = 2;
                                break;
                            case 2:
                                this._point = 3, _a(this, da(this, e = va(this, t, n)), e);
                                break;
                            default:
                                _a(this, this._t0, e = va(this, t, n))
                        }
                        this._x0 = this._x1, this._x1 = t, this._y0 = this._y1, this._y1 = n, this._t0 = e
                    }
                }
            }, (wa.prototype = Object.create(ma.prototype)).point = function(t, n) {
                ma.prototype.point.call(this, n, t)
            }, xa.prototype = {
                moveTo: function(t, n) {
                    this._context.moveTo(n, t)
                },
                closePath: function() {
                    this._context.closePath()
                },
                lineTo: function(t, n) {
                    this._context.lineTo(n, t)
                },
                bezierCurveTo: function(t, n, e, r, i, u) {
                    this._context.bezierCurveTo(n, t, r, e, u, i)
                }
            }, ba.prototype = {
                constructor: ba,
                scale: function(t) {
                    return 1 === t ? this : new ba(this.k * t, this.x, this.y)
                },
                translate: function(t, n) {
                    return 0 === t & 0 === n ? this : new ba(this.k, this.x + this.k * t, this.y + this.k * n)
                },
                apply: function(t) {
                    return [t[0] * this.k + this.x, t[1] * this.k + this.y]
                },
                applyX: function(t) {
                    return t * this.k + this.x
                },
                applyY: function(t) {
                    return t * this.k + this.y
                },
                invert: function(t) {
                    return [(t[0] - this.x) / this.k, (t[1] - this.y) / this.k]
                },
                invertX: function(t) {
                    return (t - this.x) / this.k
                },
                invertY: function(t) {
                    return (t - this.y) / this.k
                },
                rescaleX: function(t) {
                    return t.copy().domain(t.range().map(this.invertX, this).map(t.invert, t))
                },
                rescaleY: function(t) {
                    return t.copy().domain(t.range().map(this.invertY, this).map(t.invert, t))
                },
                toString: function() {
                    return "translate(" + this.x + "," + this.y + ") scale(" + this.k + ")"
                }
            };
            new ba(1, 0, 0);
            ba.prototype
        }
    }
]);