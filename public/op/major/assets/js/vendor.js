if (function(t, e) {
        "use strict";
        "object" == typeof module && "object" == typeof module.exports ? module.exports = t.document ? e(t, !0) : function(t) {
            if (!t.document) throw new Error("jQuery requires a window with a document");
            return e(t)
        } : e(t)
    }("undefined" != typeof window ? window : this, function(t, e) {
        "use strict";

        function n(t, e, n) {
            e = e || ot;
            var i, r = e.createElement("script");
            if (r.text = t, n)
                for (i in _t) n[i] && (r[i] = n[i]);
            e.head.appendChild(r).parentNode.removeChild(r)
        }

        function i(t) {
            return null == t ? t + "" : "object" == typeof t || "function" == typeof t ? dt[ft.call(t)] || "object" : typeof t
        }

        function r(t) {
            var e = !!t && "length" in t && t.length,
                n = i(t);
            return !yt(t) && !wt(t) && ("array" === n || 0 === e || "number" == typeof e && e > 0 && e - 1 in t)
        }

        function s(t, e) {
            return t.nodeName && t.nodeName.toLowerCase() === e.toLowerCase()
        }

        function o(t, e, n) {
            return yt(e) ? xt.grep(t, function(t, i) {
                return !!e.call(t, i, t) !== n
            }) : e.nodeType ? xt.grep(t, function(t) {
                return t === e !== n
            }) : "string" != typeof e ? xt.grep(t, function(t) {
                return ct.call(e, t) > -1 !== n
            }) : xt.filter(e, t, n)
        }

        function a(t, e) {
            for (;
                (t = t[e]) && 1 !== t.nodeType;);
            return t
        }

        function l(t) {
            var e = {};
            return xt.each(t.match(At) || [], function(t, n) {
                e[n] = !0
            }), e
        }

        function h(t) {
            return t
        }

        function u(t) {
            throw t
        }

        function c(t, e, n, i) {
            var r;
            try {
                t && yt(r = t.promise) ? r.call(t).done(e).fail(n) : t && yt(r = t.then) ? r.call(t, e, n) : e.apply(void 0, [t].slice(i))
            } catch (t) {
                n.apply(void 0, [t])
            }
        }

        function d() {
            ot.removeEventListener("DOMContentLoaded", d), t.removeEventListener("load", d), xt.ready()
        }

        function f(t, e) {
            return e.toUpperCase()
        }

        function p(t) {
            return t.replace(jt, "ms-").replace(Lt, f)
        }

        function g() {
            this.expando = xt.expando + g.uid++
        }

        function m(t) {
            return "true" === t || "false" !== t && ("null" === t ? null : t === +t + "" ? +t : Ft.test(t) ? JSON.parse(t) : t)
        }

        function v(t, e, n) {
            var i;
            if (void 0 === n && 1 === t.nodeType)
                if (i = "data-" + e.replace(Ut, "-$&").toLowerCase(), n = t.getAttribute(i), "string" == typeof n) {
                    try {
                        n = m(n)
                    } catch (r) {}
                    Yt.set(t, e, n)
                } else n = void 0;
            return n
        }

        function y(t, e, n, i) {
            var r, s, o = 20,
                a = i ? function() {
                    return i.cur()
                } : function() {
                    return xt.css(t, e, "")
                },
                l = a(),
                h = n && n[3] || (xt.cssNumber[e] ? "" : "px"),
                u = (xt.cssNumber[e] || "px" !== h && +l) && Wt.exec(xt.css(t, e));
            if (u && u[3] !== h) {
                for (l /= 2, h = h || u[3], u = +l || 1; o--;) xt.style(t, e, u + h), (1 - s) * (1 - (s = a() / l || .5)) <= 0 && (o = 0), u /= s;
                u = 2 * u, xt.style(t, e, u + h), n = n || []
            }
            return n && (u = +u || +l || 0, r = n[1] ? u + (n[1] + 1) * n[2] : +n[2], i && (i.unit = h, i.start = u, i.end = r)), r
        }

        function w(t) {
            var e, n = t.ownerDocument,
                i = t.nodeName,
                r = Zt[i];
            return r ? r : (e = n.body.appendChild(n.createElement(i)), r = xt.css(e, "display"), e.parentNode.removeChild(e), "none" === r && (r = "block"), Zt[i] = r, r)
        }

        function _(t, e) {
            for (var n, i, r = [], s = 0, o = t.length; s < o; s++) i = t[s], i.style && (n = i.style.display, e ? ("none" === n && (r[s] = Ht.get(i, "display") || null, r[s] || (i.style.display = "")), "" === i.style.display && Gt(i) && (r[s] = w(i))) : "none" !== n && (r[s] = "none", Ht.set(i, "display", n)));
            for (s = 0; s < o; s++) null != r[s] && (t[s].style.display = r[s]);
            return t
        }

        function b(t, e) {
            var n;
            return n = "undefined" != typeof t.getElementsByTagName ? t.getElementsByTagName(e || "*") : "undefined" != typeof t.querySelectorAll ? t.querySelectorAll(e || "*") : [], void 0 === e || e && s(t, e) ? xt.merge([t], n) : n
        }

        function x(t, e) {
            for (var n = 0, i = t.length; n < i; n++) Ht.set(t[n], "globalEval", !e || Ht.get(e[n], "globalEval"))
        }

        function C(t, e, n, r, s) {
            for (var o, a, l, h, u, c, d = e.createDocumentFragment(), f = [], p = 0, g = t.length; p < g; p++)
                if (o = t[p], o || 0 === o)
                    if ("object" === i(o)) xt.merge(f, o.nodeType ? [o] : o);
                    else if (ee.test(o)) {
                for (a = a || d.appendChild(e.createElement("div")), l = (Kt.exec(o) || ["", ""])[1].toLowerCase(), h = te[l] || te._default, a.innerHTML = h[1] + xt.htmlPrefilter(o) + h[2], c = h[0]; c--;) a = a.lastChild;
                xt.merge(f, a.childNodes), a = d.firstChild, a.textContent = ""
            } else f.push(e.createTextNode(o));
            for (d.textContent = "", p = 0; o = f[p++];)
                if (r && xt.inArray(o, r) > -1) s && s.push(o);
                else if (u = xt.contains(o.ownerDocument, o), a = b(d.appendChild(o), "script"), u && x(a), n)
                for (c = 0; o = a[c++];) Xt.test(o.type || "") && n.push(o);
            return d
        }

        function S() {
            return !0
        }

        function D() {
            return !1
        }

        function E() {
            try {
                return ot.activeElement
            } catch (t) {}
        }

        function k(t, e, n, i, r, s) {
            var o, a;
            if ("object" == typeof e) {
                "string" != typeof n && (i = i || n, n = void 0);
                for (a in e) k(t, a, n, i, e[a], s);
                return t
            }
            if (null == i && null == r ? (r = n, i = n = void 0) : null == r && ("string" == typeof n ? (r = i, i = void 0) : (r = i, i = n, n = void 0)), r === !1) r = D;
            else if (!r) return t;
            return 1 === s && (o = r, r = function(t) {
                return xt().off(t), o.apply(this, arguments)
            }, r.guid = o.guid || (o.guid = xt.guid++)), t.each(function() {
                xt.event.add(this, e, r, i, n)
            })
        }

        function T(t, e) {
            return s(t, "table") && s(11 !== e.nodeType ? e : e.firstChild, "tr") ? xt(t).children("tbody")[0] || t : t
        }

        function O(t) {
            return t.type = (null !== t.getAttribute("type")) + "/" + t.type, t
        }

        function M(t) {
            return "true/" === (t.type || "").slice(0, 5) ? t.type = t.type.slice(5) : t.removeAttribute("type"), t
        }

        function R(t, e) {
            var n, i, r, s, o, a, l, h;
            if (1 === e.nodeType) {
                if (Ht.hasData(t) && (s = Ht.access(t), o = Ht.set(e, s), h = s.events)) {
                    delete o.handle, o.events = {};
                    for (r in h)
                        for (n = 0, i = h[r].length; n < i; n++) xt.event.add(e, r, h[r][n])
                }
                Yt.hasData(t) && (a = Yt.access(t), l = xt.extend({}, a), Yt.set(e, l))
            }
        }

        function $(t, e) {
            var n = e.nodeName.toLowerCase();
            "input" === n && Jt.test(t.type) ? e.checked = t.checked : "input" !== n && "textarea" !== n || (e.defaultValue = t.defaultValue)
        }

        function P(t, e, i, r) {
            e = ht.apply([], e);
            var s, o, a, l, h, u, c = 0,
                d = t.length,
                f = d - 1,
                p = e[0],
                g = yt(p);
            if (g || d > 1 && "string" == typeof p && !vt.checkClone && le.test(p)) return t.each(function(n) {
                var s = t.eq(n);
                g && (e[0] = p.call(this, n, s.html())), P(s, e, i, r)
            });
            if (d && (s = C(e, t[0].ownerDocument, !1, t, r), o = s.firstChild, 1 === s.childNodes.length && (s = o), o || r)) {
                for (a = xt.map(b(s, "script"), O), l = a.length; c < d; c++) h = s, c !== f && (h = xt.clone(h, !0, !0), l && xt.merge(a, b(h, "script"))), i.call(t[c], h, c);
                if (l)
                    for (u = a[a.length - 1].ownerDocument, xt.map(a, M), c = 0; c < l; c++) h = a[c], Xt.test(h.type || "") && !Ht.access(h, "globalEval") && xt.contains(u, h) && (h.src && "module" !== (h.type || "").toLowerCase() ? xt._evalUrl && xt._evalUrl(h.src) : n(h.textContent.replace(he, ""), u, h))
            }
            return t
        }

        function A(t, e, n) {
            for (var i, r = e ? xt.filter(e, t) : t, s = 0; null != (i = r[s]); s++) n || 1 !== i.nodeType || xt.cleanData(b(i)), i.parentNode && (n && xt.contains(i.ownerDocument, i) && x(b(i, "script")), i.parentNode.removeChild(i));
            return t
        }

        function V(t, e, n) {
            var i, r, s, o, a = t.style;
            return n = n || ce(t), n && (o = n.getPropertyValue(e) || n[e], "" !== o || xt.contains(t.ownerDocument, t) || (o = xt.style(t, e)), !vt.pixelBoxStyles() && ue.test(o) && de.test(e) && (i = a.width, r = a.minWidth, s = a.maxWidth, a.minWidth = a.maxWidth = a.width = o, o = n.width, a.width = i, a.minWidth = r, a.maxWidth = s)), void 0 !== o ? o + "" : o
        }

        function N(t, e) {
            return {
                get: function() {
                    return t() ? void delete this.get : (this.get = e).apply(this, arguments)
                }
            }
        }

        function I(t) {
            if (t in ye) return t;
            for (var e = t[0].toUpperCase() + t.slice(1), n = ve.length; n--;)
                if (t = ve[n] + e, t in ye) return t
        }

        function j(t) {
            var e = xt.cssProps[t];
            return e || (e = xt.cssProps[t] = I(t) || t), e
        }

        function L(t, e, n) {
            var i = Wt.exec(e);
            return i ? Math.max(0, i[2] - (n || 0)) + (i[3] || "px") : e
        }

        function B(t, e, n, i, r, s) {
            var o = "width" === e ? 1 : 0,
                a = 0,
                l = 0;
            if (n === (i ? "border" : "content")) return 0;
            for (; o < 4; o += 2) "margin" === n && (l += xt.css(t, n + zt[o], !0, r)), i ? ("content" === n && (l -= xt.css(t, "padding" + zt[o], !0, r)), "margin" !== n && (l -= xt.css(t, "border" + zt[o] + "Width", !0, r))) : (l += xt.css(t, "padding" + zt[o], !0, r), "padding" !== n ? l += xt.css(t, "border" + zt[o] + "Width", !0, r) : a += xt.css(t, "border" + zt[o] + "Width", !0, r));
            return !i && s >= 0 && (l += Math.max(0, Math.ceil(t["offset" + e[0].toUpperCase() + e.slice(1)] - s - l - a - .5))), l
        }

        function H(t, e, n) {
            var i = ce(t),
                r = V(t, e, i),
                s = "border-box" === xt.css(t, "boxSizing", !1, i),
                o = s;
            if (ue.test(r)) {
                if (!n) return r;
                r = "auto"
            }
            return o = o && (vt.boxSizingReliable() || r === t.style[e]), ("auto" === r || !parseFloat(r) && "inline" === xt.css(t, "display", !1, i)) && (r = t["offset" + e[0].toUpperCase() + e.slice(1)], o = !0), r = parseFloat(r) || 0, r + B(t, e, n || (s ? "border" : "content"), o, i, r) + "px"
        }

        function Y(t, e, n, i, r) {
            return new Y.prototype.init(t, e, n, i, r)
        }

        function F() {
            _e && (ot.hidden === !1 && t.requestAnimationFrame ? t.requestAnimationFrame(F) : t.setTimeout(F, xt.fx.interval), xt.fx.tick())
        }

        function U() {
            return t.setTimeout(function() {
                we = void 0
            }), we = Date.now()
        }

        function q(t, e) {
            var n, i = 0,
                r = {
                    height: t
                };
            for (e = e ? 1 : 0; i < 4; i += 2 - e) n = zt[i], r["margin" + n] = r["padding" + n] = t;
            return e && (r.opacity = r.width = t), r
        }

        function W(t, e, n) {
            for (var i, r = (Q.tweeners[e] || []).concat(Q.tweeners["*"]), s = 0, o = r.length; s < o; s++)
                if (i = r[s].call(n, e, t)) return i
        }

        function z(t, e, n) {
            var i, r, s, o, a, l, h, u, c = "width" in e || "height" in e,
                d = this,
                f = {},
                p = t.style,
                g = t.nodeType && Gt(t),
                m = Ht.get(t, "fxshow");
            n.queue || (o = xt._queueHooks(t, "fx"), null == o.unqueued && (o.unqueued = 0, a = o.empty.fire, o.empty.fire = function() {
                o.unqueued || a()
            }), o.unqueued++, d.always(function() {
                d.always(function() {
                    o.unqueued--, xt.queue(t, "fx").length || o.empty.fire()
                })
            }));
            for (i in e)
                if (r = e[i], be.test(r)) {
                    if (delete e[i], s = s || "toggle" === r, r === (g ? "hide" : "show")) {
                        if ("show" !== r || !m || void 0 === m[i]) continue;
                        g = !0
                    }
                    f[i] = m && m[i] || xt.style(t, i)
                }
            if (l = !xt.isEmptyObject(e), l || !xt.isEmptyObject(f)) {
                c && 1 === t.nodeType && (n.overflow = [p.overflow, p.overflowX, p.overflowY], h = m && m.display, null == h && (h = Ht.get(t, "display")), u = xt.css(t, "display"), "none" === u && (h ? u = h : (_([t], !0), h = t.style.display || h, u = xt.css(t, "display"), _([t]))), ("inline" === u || "inline-block" === u && null != h) && "none" === xt.css(t, "float") && (l || (d.done(function() {
                    p.display = h
                }), null == h && (u = p.display, h = "none" === u ? "" : u)), p.display = "inline-block")), n.overflow && (p.overflow = "hidden", d.always(function() {
                    p.overflow = n.overflow[0], p.overflowX = n.overflow[1], p.overflowY = n.overflow[2]
                })), l = !1;
                for (i in f) l || (m ? "hidden" in m && (g = m.hidden) : m = Ht.access(t, "fxshow", {
                    display: h
                }), s && (m.hidden = !g), g && _([t], !0), d.done(function() {
                    g || _([t]), Ht.remove(t, "fxshow");
                    for (i in f) xt.style(t, i, f[i])
                })), l = W(g ? m[i] : 0, i, d), i in m || (m[i] = l.start, g && (l.end = l.start, l.start = 0))
            }
        }

        function G(t, e) {
            var n, i, r, s, o;
            for (n in t)
                if (i = p(n), r = e[i], s = t[n], Array.isArray(s) && (r = s[1], s = t[n] = s[0]), n !== i && (t[i] = s, delete t[n]), o = xt.cssHooks[i], o && "expand" in o) {
                    s = o.expand(s), delete t[i];
                    for (n in s) n in t || (t[n] = s[n], e[n] = r)
                } else e[i] = r
        }

        function Q(t, e, n) {
            var i, r, s = 0,
                o = Q.prefilters.length,
                a = xt.Deferred().always(function() {
                    delete l.elem
                }),
                l = function() {
                    if (r) return !1;
                    for (var e = we || U(), n = Math.max(0, h.startTime + h.duration - e), i = n / h.duration || 0, s = 1 - i, o = 0, l = h.tweens.length; o < l; o++) h.tweens[o].run(s);
                    return a.notifyWith(t, [h, s, n]), s < 1 && l ? n : (l || a.notifyWith(t, [h, 1, 0]), a.resolveWith(t, [h]), !1)
                },
                h = a.promise({
                    elem: t,
                    props: xt.extend({}, e),
                    opts: xt.extend(!0, {
                        specialEasing: {},
                        easing: xt.easing._default
                    }, n),
                    originalProperties: e,
                    originalOptions: n,
                    startTime: we || U(),
                    duration: n.duration,
                    tweens: [],
                    createTween: function(e, n) {
                        var i = xt.Tween(t, h.opts, e, n, h.opts.specialEasing[e] || h.opts.easing);
                        return h.tweens.push(i), i
                    },
                    stop: function(e) {
                        var n = 0,
                            i = e ? h.tweens.length : 0;
                        if (r) return this;
                        for (r = !0; n < i; n++) h.tweens[n].run(1);
                        return e ? (a.notifyWith(t, [h, 1, 0]), a.resolveWith(t, [h, e])) : a.rejectWith(t, [h, e]), this
                    }
                }),
                u = h.props;
            for (G(u, h.opts.specialEasing); s < o; s++)
                if (i = Q.prefilters[s].call(h, t, u, h.opts)) return yt(i.stop) && (xt._queueHooks(h.elem, h.opts.queue).stop = i.stop.bind(i)), i;
            return xt.map(u, W, h), yt(h.opts.start) && h.opts.start.call(t, h), h.progress(h.opts.progress).done(h.opts.done, h.opts.complete).fail(h.opts.fail).always(h.opts.always), xt.fx.timer(xt.extend(l, {
                elem: t,
                anim: h,
                queue: h.opts.queue
            })), h
        }

        function Z(t) {
            var e = t.match(At) || [];
            return e.join(" ")
        }

        function J(t) {
            return t.getAttribute && t.getAttribute("class") || ""
        }

        function K(t) {
            return Array.isArray(t) ? t : "string" == typeof t ? t.match(At) || [] : []
        }

        function X(t, e, n, r) {
            var s;
            if (Array.isArray(e)) xt.each(e, function(e, i) {
                n || Pe.test(t) ? r(t, i) : X(t + "[" + ("object" == typeof i && null != i ? e : "") + "]", i, n, r)
            });
            else if (n || "object" !== i(e)) r(t, e);
            else
                for (s in e) X(t + "[" + s + "]", e[s], n, r)
        }

        function tt(t) {
            return function(e, n) {
                "string" != typeof e && (n = e, e = "*");
                var i, r = 0,
                    s = e.toLowerCase().match(At) || [];
                if (yt(n))
                    for (; i = s[r++];) "+" === i[0] ? (i = i.slice(1) || "*", (t[i] = t[i] || []).unshift(n)) : (t[i] = t[i] || []).push(n)
            }
        }

        function et(t, e, n, i) {
            function r(a) {
                var l;
                return s[a] = !0, xt.each(t[a] || [], function(t, a) {
                    var h = a(e, n, i);
                    return "string" != typeof h || o || s[h] ? o ? !(l = h) : void 0 : (e.dataTypes.unshift(h), r(h), !1)
                }), l
            }
            var s = {},
                o = t === qe;
            return r(e.dataTypes[0]) || !s["*"] && r("*")
        }

        function nt(t, e) {
            var n, i, r = xt.ajaxSettings.flatOptions || {};
            for (n in e) void 0 !== e[n] && ((r[n] ? t : i || (i = {}))[n] = e[n]);
            return i && xt.extend(!0, t, i), t
        }

        function it(t, e, n) {
            for (var i, r, s, o, a = t.contents, l = t.dataTypes;
                "*" === l[0];) l.shift(), void 0 === i && (i = t.mimeType || e.getResponseHeader("Content-Type"));
            if (i)
                for (r in a)
                    if (a[r] && a[r].test(i)) {
                        l.unshift(r);
                        break
                    }
            if (l[0] in n) s = l[0];
            else {
                for (r in n) {
                    if (!l[0] || t.converters[r + " " + l[0]]) {
                        s = r;
                        break
                    }
                    o || (o = r)
                }
                s = s || o
            }
            if (s) return s !== l[0] && l.unshift(s), n[s]
        }

        function rt(t, e, n, i) {
            var r, s, o, a, l, h = {},
                u = t.dataTypes.slice();
            if (u[1])
                for (o in t.converters) h[o.toLowerCase()] = t.converters[o];
            for (s = u.shift(); s;)
                if (t.responseFields[s] && (n[t.responseFields[s]] = e), !l && i && t.dataFilter && (e = t.dataFilter(e, t.dataType)), l = s, s = u.shift())
                    if ("*" === s) s = l;
                    else if ("*" !== l && l !== s) {
                if (o = h[l + " " + s] || h["* " + s], !o)
                    for (r in h)
                        if (a = r.split(" "), a[1] === s && (o = h[l + " " + a[0]] || h["* " + a[0]])) {
                            o === !0 ? o = h[r] : h[r] !== !0 && (s = a[0], u.unshift(a[1]));
                            break
                        }
                if (o !== !0)
                    if (o && t["throws"]) e = o(e);
                    else try {
                        e = o(e)
                    } catch (c) {
                        return {
                            state: "parsererror",
                            error: o ? c : "No conversion from " + l + " to " + s
                        }
                    }
            }
            return {
                state: "success",
                data: e
            }
        }
        var st = [],
            ot = t.document,
            at = Object.getPrototypeOf,
            lt = st.slice,
            ht = st.concat,
            ut = st.push,
            ct = st.indexOf,
            dt = {},
            ft = dt.toString,
            pt = dt.hasOwnProperty,
            gt = pt.toString,
            mt = gt.call(Object),
            vt = {},
            yt = function(t) {
                return "function" == typeof t && "number" != typeof t.nodeType
            },
            wt = function(t) {
                return null != t && t === t.window
            },
            _t = {
                type: !0,
                src: !0,
                noModule: !0
            },
            bt = "3.3.1",
            xt = function(t, e) {
                return new xt.fn.init(t, e)
            },
            Ct = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;
        xt.fn = xt.prototype = {
            jquery: bt,
            constructor: xt,
            length: 0,
            toArray: function() {
                return lt.call(this)
            },
            get: function(t) {
                return null == t ? lt.call(this) : t < 0 ? this[t + this.length] : this[t]
            },
            pushStack: function(t) {
                var e = xt.merge(this.constructor(), t);
                return e.prevObject = this, e
            },
            each: function(t) {
                return xt.each(this, t)
            },
            map: function(t) {
                return this.pushStack(xt.map(this, function(e, n) {
                    return t.call(e, n, e)
                }))
            },
            slice: function() {
                return this.pushStack(lt.apply(this, arguments))
            },
            first: function() {
                return this.eq(0)
            },
            last: function() {
                return this.eq(-1)
            },
            eq: function(t) {
                var e = this.length,
                    n = +t + (t < 0 ? e : 0);
                return this.pushStack(n >= 0 && n < e ? [this[n]] : [])
            },
            end: function() {
                return this.prevObject || this.constructor()
            },
            push: ut,
            sort: st.sort,
            splice: st.splice
        }, xt.extend = xt.fn.extend = function() {
            var t, e, n, i, r, s, o = arguments[0] || {},
                a = 1,
                l = arguments.length,
                h = !1;
            for ("boolean" == typeof o && (h = o, o = arguments[a] || {}, a++), "object" == typeof o || yt(o) || (o = {}), a === l && (o = this, a--); a < l; a++)
                if (null != (t = arguments[a]))
                    for (e in t) n = o[e], i = t[e], o !== i && (h && i && (xt.isPlainObject(i) || (r = Array.isArray(i))) ? (r ? (r = !1, s = n && Array.isArray(n) ? n : []) : s = n && xt.isPlainObject(n) ? n : {}, o[e] = xt.extend(h, s, i)) : void 0 !== i && (o[e] = i));
            return o
        }, xt.extend({
            expando: "jQuery" + (bt + Math.random()).replace(/\D/g, ""),
            isReady: !0,
            error: function(t) {
                throw new Error(t)
            },
            noop: function() {},
            isPlainObject: function(t) {
                var e, n;
                return !(!t || "[object Object]" !== ft.call(t)) && (!(e = at(t)) || (n = pt.call(e, "constructor") && e.constructor, "function" == typeof n && gt.call(n) === mt))
            },
            isEmptyObject: function(t) {
                var e;
                for (e in t) return !1;
                return !0
            },
            globalEval: function(t) {
                n(t)
            },
            each: function(t, e) {
                var n, i = 0;
                if (r(t))
                    for (n = t.length; i < n && e.call(t[i], i, t[i]) !== !1; i++);
                else
                    for (i in t)
                        if (e.call(t[i], i, t[i]) === !1) break;
                return t
            },
            trim: function(t) {
                return null == t ? "" : (t + "").replace(Ct, "")
            },
            makeArray: function(t, e) {
                var n = e || [];
                return null != t && (r(Object(t)) ? xt.merge(n, "string" == typeof t ? [t] : t) : ut.call(n, t)), n
            },
            inArray: function(t, e, n) {
                return null == e ? -1 : ct.call(e, t, n)
            },
            merge: function(t, e) {
                for (var n = +e.length, i = 0, r = t.length; i < n; i++) t[r++] = e[i];
                return t.length = r, t
            },
            grep: function(t, e, n) {
                for (var i, r = [], s = 0, o = t.length, a = !n; s < o; s++) i = !e(t[s], s), i !== a && r.push(t[s]);
                return r
            },
            map: function(t, e, n) {
                var i, s, o = 0,
                    a = [];
                if (r(t))
                    for (i = t.length; o < i; o++) s = e(t[o], o, n), null != s && a.push(s);
                else
                    for (o in t) s = e(t[o], o, n), null != s && a.push(s);
                return ht.apply([], a)
            },
            guid: 1,
            support: vt
        }), "function" == typeof Symbol && (xt.fn[Symbol.iterator] = st[Symbol.iterator]), xt.each("Boolean Number String Function Array Date RegExp Object Error Symbol".split(" "), function(t, e) {
            dt["[object " + e + "]"] = e.toLowerCase()
        });
        var St = function(t) {
            function e(t, e, n, i) {
                var r, s, o, a, l, h, u, d = e && e.ownerDocument,
                    p = e ? e.nodeType : 9;
                if (n = n || [], "string" != typeof t || !t || 1 !== p && 9 !== p && 11 !== p) return n;
                if (!i && ((e ? e.ownerDocument || e : H) !== P && $(e), e = e || P, V)) {
                    if (11 !== p && (l = vt.exec(t)))
                        if (r = l[1]) {
                            if (9 === p) {
                                if (!(o = e.getElementById(r))) return n;
                                if (o.id === r) return n.push(o), n
                            } else if (d && (o = d.getElementById(r)) && L(e, o) && o.id === r) return n.push(o), n
                        } else {
                            if (l[2]) return K.apply(n, e.getElementsByTagName(t)), n;
                            if ((r = l[3]) && x.getElementsByClassName && e.getElementsByClassName) return K.apply(n, e.getElementsByClassName(r)), n
                        }
                    if (x.qsa && !W[t + " "] && (!N || !N.test(t))) {
                        if (1 !== p) d = e, u = t;
                        else if ("object" !== e.nodeName.toLowerCase()) {
                            for ((a = e.getAttribute("id")) ? a = a.replace(bt, xt) : e.setAttribute("id", a = B), h = E(t), s = h.length; s--;) h[s] = "#" + a + " " + f(h[s]);
                            u = h.join(","), d = yt.test(t) && c(e.parentNode) || e
                        }
                        if (u) try {
                            return K.apply(n, d.querySelectorAll(u)), n
                        } catch (g) {} finally {
                            a === B && e.removeAttribute("id")
                        }
                    }
                }
                return T(t.replace(at, "$1"), e, n, i)
            }

            function n() {
                function t(n, i) {
                    return e.push(n + " ") > C.cacheLength && delete t[e.shift()], t[n + " "] = i
                }
                var e = [];
                return t
            }

            function i(t) {
                return t[B] = !0, t
            }

            function r(t) {
                var e = P.createElement("fieldset");
                try {
                    return !!t(e)
                } catch (n) {
                    return !1
                } finally {
                    e.parentNode && e.parentNode.removeChild(e), e = null
                }
            }

            function s(t, e) {
                for (var n = t.split("|"), i = n.length; i--;) C.attrHandle[n[i]] = e
            }

            function o(t, e) {
                var n = e && t,
                    i = n && 1 === t.nodeType && 1 === e.nodeType && t.sourceIndex - e.sourceIndex;
                if (i) return i;
                if (n)
                    for (; n = n.nextSibling;)
                        if (n === e) return -1;
                return t ? 1 : -1
            }

            function a(t) {
                return function(e) {
                    var n = e.nodeName.toLowerCase();
                    return "input" === n && e.type === t
                }
            }

            function l(t) {
                return function(e) {
                    var n = e.nodeName.toLowerCase();
                    return ("input" === n || "button" === n) && e.type === t
                }
            }

            function h(t) {
                return function(e) {
                    return "form" in e ? e.parentNode && e.disabled === !1 ? "label" in e ? "label" in e.parentNode ? e.parentNode.disabled === t : e.disabled === t : e.isDisabled === t || e.isDisabled !== !t && St(e) === t : e.disabled === t : "label" in e && e.disabled === t
                }
            }

            function u(t) {
                return i(function(e) {
                    return e = +e, i(function(n, i) {
                        for (var r, s = t([], n.length, e), o = s.length; o--;) n[r = s[o]] && (n[r] = !(i[r] = n[r]))
                    })
                })
            }

            function c(t) {
                return t && "undefined" != typeof t.getElementsByTagName && t
            }

            function d() {}

            function f(t) {
                for (var e = 0, n = t.length, i = ""; e < n; e++) i += t[e].value;
                return i
            }

            function p(t, e, n) {
                var i = e.dir,
                    r = e.next,
                    s = r || i,
                    o = n && "parentNode" === s,
                    a = F++;
                return e.first ? function(e, n, r) {
                    for (; e = e[i];)
                        if (1 === e.nodeType || o) return t(e, n, r);
                    return !1
                } : function(e, n, l) {
                    var h, u, c, d = [Y, a];
                    if (l) {
                        for (; e = e[i];)
                            if ((1 === e.nodeType || o) && t(e, n, l)) return !0
                    } else
                        for (; e = e[i];)
                            if (1 === e.nodeType || o)
                                if (c = e[B] || (e[B] = {}), u = c[e.uniqueID] || (c[e.uniqueID] = {}), r && r === e.nodeName.toLowerCase()) e = e[i] || e;
                                else {
                                    if ((h = u[s]) && h[0] === Y && h[1] === a) return d[2] = h[2];
                                    if (u[s] = d, d[2] = t(e, n, l)) return !0
                                } return !1
                }
            }

            function g(t) {
                return t.length > 1 ? function(e, n, i) {
                    for (var r = t.length; r--;)
                        if (!t[r](e, n, i)) return !1;
                    return !0
                } : t[0]
            }

            function m(t, n, i) {
                for (var r = 0, s = n.length; r < s; r++) e(t, n[r], i);
                return i
            }

            function v(t, e, n, i, r) {
                for (var s, o = [], a = 0, l = t.length, h = null != e; a < l; a++)(s = t[a]) && (n && !n(s, i, r) || (o.push(s), h && e.push(a)));
                return o
            }

            function y(t, e, n, r, s, o) {
                return r && !r[B] && (r = y(r)), s && !s[B] && (s = y(s, o)), i(function(i, o, a, l) {
                    var h, u, c, d = [],
                        f = [],
                        p = o.length,
                        g = i || m(e || "*", a.nodeType ? [a] : a, []),
                        y = !t || !i && e ? g : v(g, d, t, a, l),
                        w = n ? s || (i ? t : p || r) ? [] : o : y;
                    if (n && n(y, w, a, l), r)
                        for (h = v(w, f), r(h, [], a, l), u = h.length; u--;)(c = h[u]) && (w[f[u]] = !(y[f[u]] = c));
                    if (i) {
                        if (s || t) {
                            if (s) {
                                for (h = [], u = w.length; u--;)(c = w[u]) && h.push(y[u] = c);
                                s(null, w = [], h, l)
                            }
                            for (u = w.length; u--;)(c = w[u]) && (h = s ? tt(i, c) : d[u]) > -1 && (i[h] = !(o[h] = c))
                        }
                    } else w = v(w === o ? w.splice(p, w.length) : w), s ? s(null, o, w, l) : K.apply(o, w)
                })
            }

            function w(t) {
                for (var e, n, i, r = t.length, s = C.relative[t[0].type], o = s || C.relative[" "], a = s ? 1 : 0, l = p(function(t) {
                        return t === e
                    }, o, !0), h = p(function(t) {
                        return tt(e, t) > -1
                    }, o, !0), u = [function(t, n, i) {
                        var r = !s && (i || n !== O) || ((e = n).nodeType ? l(t, n, i) : h(t, n, i));
                        return e = null, r
                    }]; a < r; a++)
                    if (n = C.relative[t[a].type]) u = [p(g(u), n)];
                    else {
                        if (n = C.filter[t[a].type].apply(null, t[a].matches), n[B]) {
                            for (i = ++a; i < r && !C.relative[t[i].type]; i++);
                            return y(a > 1 && g(u), a > 1 && f(t.slice(0, a - 1).concat({
                                value: " " === t[a - 2].type ? "*" : ""
                            })).replace(at, "$1"), n, a < i && w(t.slice(a, i)), i < r && w(t = t.slice(i)), i < r && f(t))
                        }
                        u.push(n)
                    }
                return g(u)
            }

            function _(t, n) {
                var r = n.length > 0,
                    s = t.length > 0,
                    o = function(i, o, a, l, h) {
                        var u, c, d, f = 0,
                            p = "0",
                            g = i && [],
                            m = [],
                            y = O,
                            w = i || s && C.find.TAG("*", h),
                            _ = Y += null == y ? 1 : Math.random() || .1,
                            b = w.length;
                        for (h && (O = o === P || o || h); p !== b && null != (u = w[p]); p++) {
                            if (s && u) {
                                for (c = 0, o || u.ownerDocument === P || ($(u), a = !V); d = t[c++];)
                                    if (d(u, o || P, a)) {
                                        l.push(u);
                                        break
                                    }
                                h && (Y = _)
                            }
                            r && ((u = !d && u) && f--, i && g.push(u))
                        }
                        if (f += p, r && p !== f) {
                            for (c = 0; d = n[c++];) d(g, m, o, a);
                            if (i) {
                                if (f > 0)
                                    for (; p--;) g[p] || m[p] || (m[p] = Z.call(l));
                                m = v(m)
                            }
                            K.apply(l, m), h && !i && m.length > 0 && f + n.length > 1 && e.uniqueSort(l)
                        }
                        return h && (Y = _, O = y), g
                    };
                return r ? i(o) : o
            }
            var b, x, C, S, D, E, k, T, O, M, R, $, P, A, V, N, I, j, L, B = "sizzle" + 1 * new Date,
                H = t.document,
                Y = 0,
                F = 0,
                U = n(),
                q = n(),
                W = n(),
                z = function(t, e) {
                    return t === e && (R = !0), 0
                },
                G = {}.hasOwnProperty,
                Q = [],
                Z = Q.pop,
                J = Q.push,
                K = Q.push,
                X = Q.slice,
                tt = function(t, e) {
                    for (var n = 0, i = t.length; n < i; n++)
                        if (t[n] === e) return n;
                    return -1
                },
                et = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",
                nt = "[\\x20\\t\\r\\n\\f]",
                it = "(?:\\\\.|[\\w-]|[^\0-\\xa0])+",
                rt = "\\[" + nt + "*(" + it + ")(?:" + nt + "*([*^$|!~]?=)" + nt + "*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|(" + it + "))|)" + nt + "*\\]",
                st = ":(" + it + ")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|" + rt + ")*)|.*)\\)|)",
                ot = new RegExp(nt + "+", "g"),
                at = new RegExp("^" + nt + "+|((?:^|[^\\\\])(?:\\\\.)*)" + nt + "+$", "g"),
                lt = new RegExp("^" + nt + "*," + nt + "*"),
                ht = new RegExp("^" + nt + "*([>+~]|" + nt + ")" + nt + "*"),
                ut = new RegExp("=" + nt + "*([^\\]'\"]*?)" + nt + "*\\]", "g"),
                ct = new RegExp(st),
                dt = new RegExp("^" + it + "$"),
                ft = {
                    ID: new RegExp("^#(" + it + ")"),
                    CLASS: new RegExp("^\\.(" + it + ")"),
                    TAG: new RegExp("^(" + it + "|[*])"),
                    ATTR: new RegExp("^" + rt),
                    PSEUDO: new RegExp("^" + st),
                    CHILD: new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + nt + "*(even|odd|(([+-]|)(\\d*)n|)" + nt + "*(?:([+-]|)" + nt + "*(\\d+)|))" + nt + "*\\)|)", "i"),
                    bool: new RegExp("^(?:" + et + ")$", "i"),
                    needsContext: new RegExp("^" + nt + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + nt + "*((?:-\\d)?\\d*)" + nt + "*\\)|)(?=[^-]|$)", "i")
                },
                pt = /^(?:input|select|textarea|button)$/i,
                gt = /^h\d$/i,
                mt = /^[^{]+\{\s*\[native \w/,
                vt = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,
                yt = /[+~]/,
                wt = new RegExp("\\\\([\\da-f]{1,6}" + nt + "?|(" + nt + ")|.)", "ig"),
                _t = function(t, e, n) {
                    var i = "0x" + e - 65536;
                    return i !== i || n ? e : i < 0 ? String.fromCharCode(i + 65536) : String.fromCharCode(i >> 10 | 55296, 1023 & i | 56320)
                },
                bt = /([\0-\x1f\x7f]|^-?\d)|^-$|[^\0-\x1f\x7f-\uFFFF\w-]/g,
                xt = function(t, e) {
                    return e ? "\0" === t ? "�" : t.slice(0, -1) + "\\" + t.charCodeAt(t.length - 1).toString(16) + " " : "\\" + t
                },
                Ct = function() {
                    $()
                },
                St = p(function(t) {
                    return t.disabled === !0 && ("form" in t || "label" in t)
                }, {
                    dir: "parentNode",
                    next: "legend"
                });
            try {
                K.apply(Q = X.call(H.childNodes), H.childNodes), Q[H.childNodes.length].nodeType
            } catch (Dt) {
                K = {
                    apply: Q.length ? function(t, e) {
                        J.apply(t, X.call(e))
                    } : function(t, e) {
                        for (var n = t.length, i = 0; t[n++] = e[i++];);
                        t.length = n - 1
                    }
                }
            }
            x = e.support = {}, D = e.isXML = function(t) {
                var e = t && (t.ownerDocument || t).documentElement;
                return !!e && "HTML" !== e.nodeName
            }, $ = e.setDocument = function(t) {
                var e, n, i = t ? t.ownerDocument || t : H;
                return i !== P && 9 === i.nodeType && i.documentElement ? (P = i, A = P.documentElement, V = !D(P), H !== P && (n = P.defaultView) && n.top !== n && (n.addEventListener ? n.addEventListener("unload", Ct, !1) : n.attachEvent && n.attachEvent("onunload", Ct)), x.attributes = r(function(t) {
                    return t.className = "i", !t.getAttribute("className")
                }), x.getElementsByTagName = r(function(t) {
                    return t.appendChild(P.createComment("")), !t.getElementsByTagName("*").length
                }), x.getElementsByClassName = mt.test(P.getElementsByClassName), x.getById = r(function(t) {
                    return A.appendChild(t).id = B, !P.getElementsByName || !P.getElementsByName(B).length
                }), x.getById ? (C.filter.ID = function(t) {
                    var e = t.replace(wt, _t);
                    return function(t) {
                        return t.getAttribute("id") === e
                    }
                }, C.find.ID = function(t, e) {
                    if ("undefined" != typeof e.getElementById && V) {
                        var n = e.getElementById(t);
                        return n ? [n] : []
                    }
                }) : (C.filter.ID = function(t) {
                    var e = t.replace(wt, _t);
                    return function(t) {
                        var n = "undefined" != typeof t.getAttributeNode && t.getAttributeNode("id");
                        return n && n.value === e
                    }
                }, C.find.ID = function(t, e) {
                    if ("undefined" != typeof e.getElementById && V) {
                        var n, i, r, s = e.getElementById(t);
                        if (s) {
                            if (n = s.getAttributeNode("id"), n && n.value === t) return [s];
                            for (r = e.getElementsByName(t), i = 0; s = r[i++];)
                                if (n = s.getAttributeNode("id"), n && n.value === t) return [s]
                        }
                        return []
                    }
                }), C.find.TAG = x.getElementsByTagName ? function(t, e) {
                    return "undefined" != typeof e.getElementsByTagName ? e.getElementsByTagName(t) : x.qsa ? e.querySelectorAll(t) : void 0
                } : function(t, e) {
                    var n, i = [],
                        r = 0,
                        s = e.getElementsByTagName(t);
                    if ("*" === t) {
                        for (; n = s[r++];) 1 === n.nodeType && i.push(n);
                        return i
                    }
                    return s
                }, C.find.CLASS = x.getElementsByClassName && function(t, e) {
                    if ("undefined" != typeof e.getElementsByClassName && V) return e.getElementsByClassName(t)
                }, I = [], N = [], (x.qsa = mt.test(P.querySelectorAll)) && (r(function(t) {
                    A.appendChild(t).innerHTML = "<a id='" + B + "'></a><select id='" + B + "-\r\\' msallowcapture=''><option selected=''></option></select>", t.querySelectorAll("[msallowcapture^='']").length && N.push("[*^$]=" + nt + "*(?:''|\"\")"), t.querySelectorAll("[selected]").length || N.push("\\[" + nt + "*(?:value|" + et + ")"), t.querySelectorAll("[id~=" + B + "-]").length || N.push("~="), t.querySelectorAll(":checked").length || N.push(":checked"), t.querySelectorAll("a#" + B + "+*").length || N.push(".#.+[+~]")
                }), r(function(t) {
                    t.innerHTML = "<a href='' disabled='disabled'></a><select disabled='disabled'><option/></select>";
                    var e = P.createElement("input");
                    e.setAttribute("type", "hidden"), t.appendChild(e).setAttribute("name", "D"), t.querySelectorAll("[name=d]").length && N.push("name" + nt + "*[*^$|!~]?="), 2 !== t.querySelectorAll(":enabled").length && N.push(":enabled", ":disabled"), A.appendChild(t).disabled = !0, 2 !== t.querySelectorAll(":disabled").length && N.push(":enabled", ":disabled"), t.querySelectorAll("*,:x"), N.push(",.*:")
                })), (x.matchesSelector = mt.test(j = A.matches || A.webkitMatchesSelector || A.mozMatchesSelector || A.oMatchesSelector || A.msMatchesSelector)) && r(function(t) {
                    x.disconnectedMatch = j.call(t, "*"), j.call(t, "[s!='']:x"), I.push("!=", st)
                }), N = N.length && new RegExp(N.join("|")), I = I.length && new RegExp(I.join("|")), e = mt.test(A.compareDocumentPosition), L = e || mt.test(A.contains) ? function(t, e) {
                    var n = 9 === t.nodeType ? t.documentElement : t,
                        i = e && e.parentNode;
                    return t === i || !(!i || 1 !== i.nodeType || !(n.contains ? n.contains(i) : t.compareDocumentPosition && 16 & t.compareDocumentPosition(i)))
                } : function(t, e) {
                    if (e)
                        for (; e = e.parentNode;)
                            if (e === t) return !0;
                    return !1
                }, z = e ? function(t, e) {
                    if (t === e) return R = !0, 0;
                    var n = !t.compareDocumentPosition - !e.compareDocumentPosition;
                    return n ? n : (n = (t.ownerDocument || t) === (e.ownerDocument || e) ? t.compareDocumentPosition(e) : 1, 1 & n || !x.sortDetached && e.compareDocumentPosition(t) === n ? t === P || t.ownerDocument === H && L(H, t) ? -1 : e === P || e.ownerDocument === H && L(H, e) ? 1 : M ? tt(M, t) - tt(M, e) : 0 : 4 & n ? -1 : 1)
                } : function(t, e) {
                    if (t === e) return R = !0, 0;
                    var n, i = 0,
                        r = t.parentNode,
                        s = e.parentNode,
                        a = [t],
                        l = [e];
                    if (!r || !s) return t === P ? -1 : e === P ? 1 : r ? -1 : s ? 1 : M ? tt(M, t) - tt(M, e) : 0;
                    if (r === s) return o(t, e);
                    for (n = t; n = n.parentNode;) a.unshift(n);
                    for (n = e; n = n.parentNode;) l.unshift(n);
                    for (; a[i] === l[i];) i++;
                    return i ? o(a[i], l[i]) : a[i] === H ? -1 : l[i] === H ? 1 : 0
                }, P) : P
            }, e.matches = function(t, n) {
                return e(t, null, null, n)
            }, e.matchesSelector = function(t, n) {
                if ((t.ownerDocument || t) !== P && $(t), n = n.replace(ut, "='$1']"), x.matchesSelector && V && !W[n + " "] && (!I || !I.test(n)) && (!N || !N.test(n))) try {
                    var i = j.call(t, n);
                    if (i || x.disconnectedMatch || t.document && 11 !== t.document.nodeType) return i
                } catch (r) {}
                return e(n, P, null, [t]).length > 0
            }, e.contains = function(t, e) {
                return (t.ownerDocument || t) !== P && $(t), L(t, e)
            }, e.attr = function(t, e) {
                (t.ownerDocument || t) !== P && $(t);
                var n = C.attrHandle[e.toLowerCase()],
                    i = n && G.call(C.attrHandle, e.toLowerCase()) ? n(t, e, !V) : void 0;
                return void 0 !== i ? i : x.attributes || !V ? t.getAttribute(e) : (i = t.getAttributeNode(e)) && i.specified ? i.value : null
            }, e.escape = function(t) {
                return (t + "").replace(bt, xt)
            }, e.error = function(t) {
                throw new Error("Syntax error, unrecognized expression: " + t)
            }, e.uniqueSort = function(t) {
                var e, n = [],
                    i = 0,
                    r = 0;
                if (R = !x.detectDuplicates, M = !x.sortStable && t.slice(0), t.sort(z), R) {
                    for (; e = t[r++];) e === t[r] && (i = n.push(r));
                    for (; i--;) t.splice(n[i], 1)
                }
                return M = null, t
            }, S = e.getText = function(t) {
                var e, n = "",
                    i = 0,
                    r = t.nodeType;
                if (r) {
                    if (1 === r || 9 === r || 11 === r) {
                        if ("string" == typeof t.textContent) return t.textContent;
                        for (t = t.firstChild; t; t = t.nextSibling) n += S(t)
                    } else if (3 === r || 4 === r) return t.nodeValue
                } else
                    for (; e = t[i++];) n += S(e);
                return n
            }, C = e.selectors = {
                cacheLength: 50,
                createPseudo: i,
                match: ft,
                attrHandle: {},
                find: {},
                relative: {
                    ">": {
                        dir: "parentNode",
                        first: !0
                    },
                    " ": {
                        dir: "parentNode"
                    },
                    "+": {
                        dir: "previousSibling",
                        first: !0
                    },
                    "~": {
                        dir: "previousSibling"
                    }
                },
                preFilter: {
                    ATTR: function(t) {
                        return t[1] = t[1].replace(wt, _t), t[3] = (t[3] || t[4] || t[5] || "").replace(wt, _t), "~=" === t[2] && (t[3] = " " + t[3] + " "), t.slice(0, 4)
                    },
                    CHILD: function(t) {
                        return t[1] = t[1].toLowerCase(), "nth" === t[1].slice(0, 3) ? (t[3] || e.error(t[0]), t[4] = +(t[4] ? t[5] + (t[6] || 1) : 2 * ("even" === t[3] || "odd" === t[3])), t[5] = +(t[7] + t[8] || "odd" === t[3])) : t[3] && e.error(t[0]), t
                    },
                    PSEUDO: function(t) {
                        var e, n = !t[6] && t[2];
                        return ft.CHILD.test(t[0]) ? null : (t[3] ? t[2] = t[4] || t[5] || "" : n && ct.test(n) && (e = E(n, !0)) && (e = n.indexOf(")", n.length - e) - n.length) && (t[0] = t[0].slice(0, e), t[2] = n.slice(0, e)), t.slice(0, 3))
                    }
                },
                filter: {
                    TAG: function(t) {
                        var e = t.replace(wt, _t).toLowerCase();
                        return "*" === t ? function() {
                            return !0
                        } : function(t) {
                            return t.nodeName && t.nodeName.toLowerCase() === e
                        }
                    },
                    CLASS: function(t) {
                        var e = U[t + " "];
                        return e || (e = new RegExp("(^|" + nt + ")" + t + "(" + nt + "|$)")) && U(t, function(t) {
                            return e.test("string" == typeof t.className && t.className || "undefined" != typeof t.getAttribute && t.getAttribute("class") || "")
                        })
                    },
                    ATTR: function(t, n, i) {
                        return function(r) {
                            var s = e.attr(r, t);
                            return null == s ? "!=" === n : !n || (s += "", "=" === n ? s === i : "!=" === n ? s !== i : "^=" === n ? i && 0 === s.indexOf(i) : "*=" === n ? i && s.indexOf(i) > -1 : "$=" === n ? i && s.slice(-i.length) === i : "~=" === n ? (" " + s.replace(ot, " ") + " ").indexOf(i) > -1 : "|=" === n && (s === i || s.slice(0, i.length + 1) === i + "-"))
                        }
                    },
                    CHILD: function(t, e, n, i, r) {
                        var s = "nth" !== t.slice(0, 3),
                            o = "last" !== t.slice(-4),
                            a = "of-type" === e;
                        return 1 === i && 0 === r ? function(t) {
                            return !!t.parentNode
                        } : function(e, n, l) {
                            var h, u, c, d, f, p, g = s !== o ? "nextSibling" : "previousSibling",
                                m = e.parentNode,
                                v = a && e.nodeName.toLowerCase(),
                                y = !l && !a,
                                w = !1;
                            if (m) {
                                if (s) {
                                    for (; g;) {
                                        for (d = e; d = d[g];)
                                            if (a ? d.nodeName.toLowerCase() === v : 1 === d.nodeType) return !1;
                                        p = g = "only" === t && !p && "nextSibling"
                                    }
                                    return !0
                                }
                                if (p = [o ? m.firstChild : m.lastChild], o && y) {
                                    for (d = m, c = d[B] || (d[B] = {}), u = c[d.uniqueID] || (c[d.uniqueID] = {}), h = u[t] || [], f = h[0] === Y && h[1], w = f && h[2], d = f && m.childNodes[f]; d = ++f && d && d[g] || (w = f = 0) || p.pop();)
                                        if (1 === d.nodeType && ++w && d === e) {
                                            u[t] = [Y, f, w];
                                            break
                                        }
                                } else if (y && (d = e, c = d[B] || (d[B] = {}), u = c[d.uniqueID] || (c[d.uniqueID] = {}), h = u[t] || [], f = h[0] === Y && h[1], w = f), w === !1)
                                    for (;
                                        (d = ++f && d && d[g] || (w = f = 0) || p.pop()) && ((a ? d.nodeName.toLowerCase() !== v : 1 !== d.nodeType) || !++w || (y && (c = d[B] || (d[B] = {}), u = c[d.uniqueID] || (c[d.uniqueID] = {}), u[t] = [Y, w]), d !== e)););
                                return w -= r, w === i || w % i === 0 && w / i >= 0
                            }
                        }
                    },
                    PSEUDO: function(t, n) {
                        var r, s = C.pseudos[t] || C.setFilters[t.toLowerCase()] || e.error("unsupported pseudo: " + t);
                        return s[B] ? s(n) : s.length > 1 ? (r = [t, t, "", n], C.setFilters.hasOwnProperty(t.toLowerCase()) ? i(function(t, e) {
                            for (var i, r = s(t, n), o = r.length; o--;) i = tt(t, r[o]), t[i] = !(e[i] = r[o])
                        }) : function(t) {
                            return s(t, 0, r)
                        }) : s
                    }
                },
                pseudos: {
                    not: i(function(t) {
                        var e = [],
                            n = [],
                            r = k(t.replace(at, "$1"));
                        return r[B] ? i(function(t, e, n, i) {
                            for (var s, o = r(t, null, i, []), a = t.length; a--;)(s = o[a]) && (t[a] = !(e[a] = s))
                        }) : function(t, i, s) {
                            return e[0] = t, r(e, null, s, n), e[0] = null, !n.pop()
                        }
                    }),
                    has: i(function(t) {
                        return function(n) {
                            return e(t, n).length > 0
                        }
                    }),
                    contains: i(function(t) {
                        return t = t.replace(wt, _t),
                            function(e) {
                                return (e.textContent || e.innerText || S(e)).indexOf(t) > -1
                            }
                    }),
                    lang: i(function(t) {
                        return dt.test(t || "") || e.error("unsupported lang: " + t), t = t.replace(wt, _t).toLowerCase(),
                            function(e) {
                                var n;
                                do
                                    if (n = V ? e.lang : e.getAttribute("xml:lang") || e.getAttribute("lang")) return n = n.toLowerCase(), n === t || 0 === n.indexOf(t + "-"); while ((e = e.parentNode) && 1 === e.nodeType);
                                return !1
                            }
                    }),
                    target: function(e) {
                        var n = t.location && t.location.hash;
                        return n && n.slice(1) === e.id
                    },
                    root: function(t) {
                        return t === A
                    },
                    focus: function(t) {
                        return t === P.activeElement && (!P.hasFocus || P.hasFocus()) && !!(t.type || t.href || ~t.tabIndex)
                    },
                    enabled: h(!1),
                    disabled: h(!0),
                    checked: function(t) {
                        var e = t.nodeName.toLowerCase();
                        return "input" === e && !!t.checked || "option" === e && !!t.selected
                    },
                    selected: function(t) {
                        return t.parentNode && t.parentNode.selectedIndex, t.selected === !0
                    },
                    empty: function(t) {
                        for (t = t.firstChild; t; t = t.nextSibling)
                            if (t.nodeType < 6) return !1;
                        return !0
                    },
                    parent: function(t) {
                        return !C.pseudos.empty(t)
                    },
                    header: function(t) {
                        return gt.test(t.nodeName)
                    },
                    input: function(t) {
                        return pt.test(t.nodeName)
                    },
                    button: function(t) {
                        var e = t.nodeName.toLowerCase();
                        return "input" === e && "button" === t.type || "button" === e
                    },
                    text: function(t) {
                        var e;
                        return "input" === t.nodeName.toLowerCase() && "text" === t.type && (null == (e = t.getAttribute("type")) || "text" === e.toLowerCase())
                    },
                    first: u(function() {
                        return [0]
                    }),
                    last: u(function(t, e) {
                        return [e - 1]
                    }),
                    eq: u(function(t, e, n) {
                        return [n < 0 ? n + e : n]
                    }),
                    even: u(function(t, e) {
                        for (var n = 0; n < e; n += 2) t.push(n);
                        return t
                    }),
                    odd: u(function(t, e) {
                        for (var n = 1; n < e; n += 2) t.push(n);
                        return t
                    }),
                    lt: u(function(t, e, n) {
                        for (var i = n < 0 ? n + e : n; --i >= 0;) t.push(i);
                        return t
                    }),
                    gt: u(function(t, e, n) {
                        for (var i = n < 0 ? n + e : n; ++i < e;) t.push(i);
                        return t
                    })
                }
            }, C.pseudos.nth = C.pseudos.eq;
            for (b in {
                    radio: !0,
                    checkbox: !0,
                    file: !0,
                    password: !0,
                    image: !0
                }) C.pseudos[b] = a(b);
            for (b in {
                    submit: !0,
                    reset: !0
                }) C.pseudos[b] = l(b);
            return d.prototype = C.filters = C.pseudos, C.setFilters = new d, E = e.tokenize = function(t, n) {
                var i, r, s, o, a, l, h, u = q[t + " "];
                if (u) return n ? 0 : u.slice(0);
                for (a = t, l = [], h = C.preFilter; a;) {
                    i && !(r = lt.exec(a)) || (r && (a = a.slice(r[0].length) || a), l.push(s = [])), i = !1, (r = ht.exec(a)) && (i = r.shift(), s.push({
                        value: i,
                        type: r[0].replace(at, " ")
                    }), a = a.slice(i.length));
                    for (o in C.filter) !(r = ft[o].exec(a)) || h[o] && !(r = h[o](r)) || (i = r.shift(), s.push({
                        value: i,
                        type: o,
                        matches: r
                    }), a = a.slice(i.length));
                    if (!i) break
                }
                return n ? a.length : a ? e.error(t) : q(t, l).slice(0)
            }, k = e.compile = function(t, e) {
                var n, i = [],
                    r = [],
                    s = W[t + " "];
                if (!s) {
                    for (e || (e = E(t)), n = e.length; n--;) s = w(e[n]), s[B] ? i.push(s) : r.push(s);
                    s = W(t, _(r, i)), s.selector = t
                }
                return s
            }, T = e.select = function(t, e, n, i) {
                var r, s, o, a, l, h = "function" == typeof t && t,
                    u = !i && E(t = h.selector || t);
                if (n = n || [], 1 === u.length) {
                    if (s = u[0] = u[0].slice(0), s.length > 2 && "ID" === (o = s[0]).type && 9 === e.nodeType && V && C.relative[s[1].type]) {
                        if (e = (C.find.ID(o.matches[0].replace(wt, _t), e) || [])[0], !e) return n;
                        h && (e = e.parentNode), t = t.slice(s.shift().value.length)
                    }
                    for (r = ft.needsContext.test(t) ? 0 : s.length; r-- && (o = s[r], !C.relative[a = o.type]);)
                        if ((l = C.find[a]) && (i = l(o.matches[0].replace(wt, _t), yt.test(s[0].type) && c(e.parentNode) || e))) {
                            if (s.splice(r, 1), t = i.length && f(s), !t) return K.apply(n, i), n;
                            break
                        }
                }
                return (h || k(t, u))(i, e, !V, n, !e || yt.test(t) && c(e.parentNode) || e), n
            }, x.sortStable = B.split("").sort(z).join("") === B, x.detectDuplicates = !!R, $(), x.sortDetached = r(function(t) {
                return 1 & t.compareDocumentPosition(P.createElement("fieldset"))
            }), r(function(t) {
                return t.innerHTML = "<a href='#'></a>", "#" === t.firstChild.getAttribute("href")
            }) || s("type|href|height|width", function(t, e, n) {
                if (!n) return t.getAttribute(e, "type" === e.toLowerCase() ? 1 : 2)
            }), x.attributes && r(function(t) {
                return t.innerHTML = "<input/>", t.firstChild.setAttribute("value", ""), "" === t.firstChild.getAttribute("value")
            }) || s("value", function(t, e, n) {
                if (!n && "input" === t.nodeName.toLowerCase()) return t.defaultValue
            }), r(function(t) {
                return null == t.getAttribute("disabled")
            }) || s(et, function(t, e, n) {
                var i;
                if (!n) return t[e] === !0 ? e.toLowerCase() : (i = t.getAttributeNode(e)) && i.specified ? i.value : null
            }), e
        }(t);
        xt.find = St, xt.expr = St.selectors, xt.expr[":"] = xt.expr.pseudos, xt.uniqueSort = xt.unique = St.uniqueSort, xt.text = St.getText, xt.isXMLDoc = St.isXML, xt.contains = St.contains, xt.escapeSelector = St.escape;
        var Dt = function(t, e, n) {
                for (var i = [], r = void 0 !== n;
                    (t = t[e]) && 9 !== t.nodeType;)
                    if (1 === t.nodeType) {
                        if (r && xt(t).is(n)) break;
                        i.push(t)
                    }
                return i
            },
            Et = function(t, e) {
                for (var n = []; t; t = t.nextSibling) 1 === t.nodeType && t !== e && n.push(t);
                return n
            },
            kt = xt.expr.match.needsContext,
            Tt = /^<([a-z][^\/\0>:\x20\t\r\n\f]*)[\x20\t\r\n\f]*\/?>(?:<\/\1>|)$/i;
        xt.filter = function(t, e, n) {
            var i = e[0];
            return n && (t = ":not(" + t + ")"), 1 === e.length && 1 === i.nodeType ? xt.find.matchesSelector(i, t) ? [i] : [] : xt.find.matches(t, xt.grep(e, function(t) {
                return 1 === t.nodeType
            }))
        }, xt.fn.extend({
            find: function(t) {
                var e, n, i = this.length,
                    r = this;
                if ("string" != typeof t) return this.pushStack(xt(t).filter(function() {
                    for (e = 0; e < i; e++)
                        if (xt.contains(r[e], this)) return !0
                }));
                for (n = this.pushStack([]), e = 0; e < i; e++) xt.find(t, r[e], n);
                return i > 1 ? xt.uniqueSort(n) : n
            },
            filter: function(t) {
                return this.pushStack(o(this, t || [], !1))
            },
            not: function(t) {
                return this.pushStack(o(this, t || [], !0))
            },
            is: function(t) {
                return !!o(this, "string" == typeof t && kt.test(t) ? xt(t) : t || [], !1).length
            }
        });
        var Ot, Mt = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]+))$/,
            Rt = xt.fn.init = function(t, e, n) {
                var i, r;
                if (!t) return this;
                if (n = n || Ot, "string" == typeof t) {
                    if (i = "<" === t[0] && ">" === t[t.length - 1] && t.length >= 3 ? [null, t, null] : Mt.exec(t), !i || !i[1] && e) return !e || e.jquery ? (e || n).find(t) : this.constructor(e).find(t);
                    if (i[1]) {
                        if (e = e instanceof xt ? e[0] : e, xt.merge(this, xt.parseHTML(i[1], e && e.nodeType ? e.ownerDocument || e : ot, !0)), Tt.test(i[1]) && xt.isPlainObject(e))
                            for (i in e) yt(this[i]) ? this[i](e[i]) : this.attr(i, e[i]);
                        return this
                    }
                    return r = ot.getElementById(i[2]), r && (this[0] = r, this.length = 1), this
                }
                return t.nodeType ? (this[0] = t, this.length = 1, this) : yt(t) ? void 0 !== n.ready ? n.ready(t) : t(xt) : xt.makeArray(t, this)
            };
        Rt.prototype = xt.fn, Ot = xt(ot);
        var $t = /^(?:parents|prev(?:Until|All))/,
            Pt = {
                children: !0,
                contents: !0,
                next: !0,
                prev: !0
            };
        xt.fn.extend({
            has: function(t) {
                var e = xt(t, this),
                    n = e.length;
                return this.filter(function() {
                    for (var t = 0; t < n; t++)
                        if (xt.contains(this, e[t])) return !0
                })
            },
            closest: function(t, e) {
                var n, i = 0,
                    r = this.length,
                    s = [],
                    o = "string" != typeof t && xt(t);
                if (!kt.test(t))
                    for (; i < r; i++)
                        for (n = this[i]; n && n !== e; n = n.parentNode)
                            if (n.nodeType < 11 && (o ? o.index(n) > -1 : 1 === n.nodeType && xt.find.matchesSelector(n, t))) {
                                s.push(n);
                                break
                            }
                return this.pushStack(s.length > 1 ? xt.uniqueSort(s) : s)
            },
            index: function(t) {
                return t ? "string" == typeof t ? ct.call(xt(t), this[0]) : ct.call(this, t.jquery ? t[0] : t) : this[0] && this[0].parentNode ? this.first().prevAll().length : -1
            },
            add: function(t, e) {
                return this.pushStack(xt.uniqueSort(xt.merge(this.get(), xt(t, e))))
            },
            addBack: function(t) {
                return this.add(null == t ? this.prevObject : this.prevObject.filter(t))
            }
        }), xt.each({
            parent: function(t) {
                var e = t.parentNode;
                return e && 11 !== e.nodeType ? e : null
            },
            parents: function(t) {
                return Dt(t, "parentNode")
            },
            parentsUntil: function(t, e, n) {
                return Dt(t, "parentNode", n)
            },
            next: function(t) {
                return a(t, "nextSibling")
            },
            prev: function(t) {
                return a(t, "previousSibling")
            },
            nextAll: function(t) {
                return Dt(t, "nextSibling")
            },
            prevAll: function(t) {
                return Dt(t, "previousSibling")
            },
            nextUntil: function(t, e, n) {
                return Dt(t, "nextSibling", n)
            },
            prevUntil: function(t, e, n) {
                return Dt(t, "previousSibling", n)
            },
            siblings: function(t) {
                return Et((t.parentNode || {}).firstChild, t)
            },
            children: function(t) {
                return Et(t.firstChild)
            },
            contents: function(t) {
                return s(t, "iframe") ? t.contentDocument : (s(t, "template") && (t = t.content || t), xt.merge([], t.childNodes))
            }
        }, function(t, e) {
            xt.fn[t] = function(n, i) {
                var r = xt.map(this, e, n);
                return "Until" !== t.slice(-5) && (i = n), i && "string" == typeof i && (r = xt.filter(i, r)), this.length > 1 && (Pt[t] || xt.uniqueSort(r), $t.test(t) && r.reverse()), this.pushStack(r)
            }
        });
        var At = /[^\x20\t\r\n\f]+/g;
        xt.Callbacks = function(t) {
            t = "string" == typeof t ? l(t) : xt.extend({}, t);
            var e, n, r, s, o = [],
                a = [],
                h = -1,
                u = function() {
                    for (s = s || t.once, r = e = !0; a.length; h = -1)
                        for (n = a.shift(); ++h < o.length;) o[h].apply(n[0], n[1]) === !1 && t.stopOnFalse && (h = o.length, n = !1);
                    t.memory || (n = !1), e = !1, s && (o = n ? [] : "")
                },
                c = {
                    add: function() {
                        return o && (n && !e && (h = o.length - 1, a.push(n)), function r(e) {
                            xt.each(e, function(e, n) {
                                yt(n) ? t.unique && c.has(n) || o.push(n) : n && n.length && "string" !== i(n) && r(n)
                            })
                        }(arguments), n && !e && u()), this
                    },
                    remove: function() {
                        return xt.each(arguments, function(t, e) {
                            for (var n;
                                (n = xt.inArray(e, o, n)) > -1;) o.splice(n, 1), n <= h && h--
                        }), this
                    },
                    has: function(t) {
                        return t ? xt.inArray(t, o) > -1 : o.length > 0
                    },
                    empty: function() {
                        return o && (o = []), this
                    },
                    disable: function() {
                        return s = a = [], o = n = "", this
                    },
                    disabled: function() {
                        return !o
                    },
                    lock: function() {
                        return s = a = [], n || e || (o = n = ""), this
                    },
                    locked: function() {
                        return !!s
                    },
                    fireWith: function(t, n) {
                        return s || (n = n || [], n = [t, n.slice ? n.slice() : n], a.push(n), e || u()), this
                    },
                    fire: function() {
                        return c.fireWith(this, arguments), this
                    },
                    fired: function() {
                        return !!r
                    }
                };
            return c
        }, xt.extend({
            Deferred: function(e) {
                var n = [
                        ["notify", "progress", xt.Callbacks("memory"), xt.Callbacks("memory"), 2],
                        ["resolve", "done", xt.Callbacks("once memory"), xt.Callbacks("once memory"), 0, "resolved"],
                        ["reject", "fail", xt.Callbacks("once memory"), xt.Callbacks("once memory"), 1, "rejected"]
                    ],
                    i = "pending",
                    r = {
                        state: function() {
                            return i
                        },
                        always: function() {
                            return s.done(arguments).fail(arguments), this
                        },
                        "catch": function(t) {
                            return r.then(null, t)
                        },
                        pipe: function() {
                            var t = arguments;
                            return xt.Deferred(function(e) {
                                xt.each(n, function(n, i) {
                                    var r = yt(t[i[4]]) && t[i[4]];
                                    s[i[1]](function() {
                                        var t = r && r.apply(this, arguments);
                                        t && yt(t.promise) ? t.promise().progress(e.notify).done(e.resolve).fail(e.reject) : e[i[0] + "With"](this, r ? [t] : arguments)
                                    })
                                }), t = null
                            }).promise()
                        },
                        then: function(e, i, r) {
                            function s(e, n, i, r) {
                                return function() {
                                    var a = this,
                                        l = arguments,
                                        c = function() {
                                            var t, c;
                                            if (!(e < o)) {
                                                if (t = i.apply(a, l), t === n.promise()) throw new TypeError("Thenable self-resolution");
                                                c = t && ("object" == typeof t || "function" == typeof t) && t.then, yt(c) ? r ? c.call(t, s(o, n, h, r), s(o, n, u, r)) : (o++, c.call(t, s(o, n, h, r), s(o, n, u, r), s(o, n, h, n.notifyWith))) : (i !== h && (a = void 0, l = [t]), (r || n.resolveWith)(a, l))
                                            }
                                        },
                                        d = r ? c : function() {
                                            try {
                                                c()
                                            } catch (t) {
                                                xt.Deferred.exceptionHook && xt.Deferred.exceptionHook(t, d.stackTrace), e + 1 >= o && (i !== u && (a = void 0, l = [t]), n.rejectWith(a, l))
                                            }
                                        };
                                    e ? d() : (xt.Deferred.getStackHook && (d.stackTrace = xt.Deferred.getStackHook()), t.setTimeout(d))
                                }
                            }
                            var o = 0;
                            return xt.Deferred(function(t) {
                                n[0][3].add(s(0, t, yt(r) ? r : h, t.notifyWith)), n[1][3].add(s(0, t, yt(e) ? e : h)), n[2][3].add(s(0, t, yt(i) ? i : u))
                            }).promise()
                        },
                        promise: function(t) {
                            return null != t ? xt.extend(t, r) : r
                        }
                    },
                    s = {};
                return xt.each(n, function(t, e) {
                    var o = e[2],
                        a = e[5];
                    r[e[1]] = o.add, a && o.add(function() {
                        i = a
                    }, n[3 - t][2].disable, n[3 - t][3].disable, n[0][2].lock, n[0][3].lock), o.add(e[3].fire), s[e[0]] = function() {
                        return s[e[0] + "With"](this === s ? void 0 : this, arguments), this
                    }, s[e[0] + "With"] = o.fireWith
                }), r.promise(s), e && e.call(s, s), s
            },
            when: function(t) {
                var e = arguments.length,
                    n = e,
                    i = Array(n),
                    r = lt.call(arguments),
                    s = xt.Deferred(),
                    o = function(t) {
                        return function(n) {
                            i[t] = this, r[t] = arguments.length > 1 ? lt.call(arguments) : n, --e || s.resolveWith(i, r)
                        }
                    };
                if (e <= 1 && (c(t, s.done(o(n)).resolve, s.reject, !e), "pending" === s.state() || yt(r[n] && r[n].then))) return s.then();
                for (; n--;) c(r[n], o(n), s.reject);
                return s.promise()
            }
        });
        var Vt = /^(Eval|Internal|Range|Reference|Syntax|Type|URI)Error$/;
        xt.Deferred.exceptionHook = function(e, n) {
            t.console && t.console.warn && e && Vt.test(e.name) && t.console.warn("jQuery.Deferred exception: " + e.message, e.stack, n)
        }, xt.readyException = function(e) {
            t.setTimeout(function() {
                throw e
            })
        };
        var Nt = xt.Deferred();
        xt.fn.ready = function(t) {
            return Nt.then(t)["catch"](function(t) {
                xt.readyException(t)
            }), this
        }, xt.extend({
            isReady: !1,
            readyWait: 1,
            ready: function(t) {
                (t === !0 ? --xt.readyWait : xt.isReady) || (xt.isReady = !0, t !== !0 && --xt.readyWait > 0 || Nt.resolveWith(ot, [xt]))
            }
        }), xt.ready.then = Nt.then, "complete" === ot.readyState || "loading" !== ot.readyState && !ot.documentElement.doScroll ? t.setTimeout(xt.ready) : (ot.addEventListener("DOMContentLoaded", d), t.addEventListener("load", d));
        var It = function(t, e, n, r, s, o, a) {
                var l = 0,
                    h = t.length,
                    u = null == n;
                if ("object" === i(n)) {
                    s = !0;
                    for (l in n) It(t, e, l, n[l], !0, o, a)
                } else if (void 0 !== r && (s = !0, yt(r) || (a = !0), u && (a ? (e.call(t, r), e = null) : (u = e, e = function(t, e, n) {
                        return u.call(xt(t), n)
                    })), e))
                    for (; l < h; l++) e(t[l], n, a ? r : r.call(t[l], l, e(t[l], n)));
                return s ? t : u ? e.call(t) : h ? e(t[0], n) : o
            },
            jt = /^-ms-/,
            Lt = /-([a-z])/g,
            Bt = function(t) {
                return 1 === t.nodeType || 9 === t.nodeType || !+t.nodeType
            };
        g.uid = 1, g.prototype = {
            cache: function(t) {
                var e = t[this.expando];
                return e || (e = {}, Bt(t) && (t.nodeType ? t[this.expando] = e : Object.defineProperty(t, this.expando, {
                    value: e,
                    configurable: !0
                }))), e
            },
            set: function(t, e, n) {
                var i, r = this.cache(t);
                if ("string" == typeof e) r[p(e)] = n;
                else
                    for (i in e) r[p(i)] = e[i];
                return r
            },
            get: function(t, e) {
                return void 0 === e ? this.cache(t) : t[this.expando] && t[this.expando][p(e)]
            },
            access: function(t, e, n) {
                return void 0 === e || e && "string" == typeof e && void 0 === n ? this.get(t, e) : (this.set(t, e, n), void 0 !== n ? n : e)
            },
            remove: function(t, e) {
                var n, i = t[this.expando];
                if (void 0 !== i) {
                    if (void 0 !== e) {
                        Array.isArray(e) ? e = e.map(p) : (e = p(e), e = e in i ? [e] : e.match(At) || []), n = e.length;
                        for (; n--;) delete i[e[n]]
                    }(void 0 === e || xt.isEmptyObject(i)) && (t.nodeType ? t[this.expando] = void 0 : delete t[this.expando])
                }
            },
            hasData: function(t) {
                var e = t[this.expando];
                return void 0 !== e && !xt.isEmptyObject(e)
            }
        };
        var Ht = new g,
            Yt = new g,
            Ft = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/,
            Ut = /[A-Z]/g;
        xt.extend({
            hasData: function(t) {
                return Yt.hasData(t) || Ht.hasData(t)
            },
            data: function(t, e, n) {
                return Yt.access(t, e, n)
            },
            removeData: function(t, e) {
                Yt.remove(t, e)
            },
            _data: function(t, e, n) {
                return Ht.access(t, e, n)
            },
            _removeData: function(t, e) {
                Ht.remove(t, e)
            }
        }), xt.fn.extend({
            data: function(t, e) {
                var n, i, r, s = this[0],
                    o = s && s.attributes;
                if (void 0 === t) {
                    if (this.length && (r = Yt.get(s), 1 === s.nodeType && !Ht.get(s, "hasDataAttrs"))) {
                        for (n = o.length; n--;) o[n] && (i = o[n].name, 0 === i.indexOf("data-") && (i = p(i.slice(5)), v(s, i, r[i])));
                        Ht.set(s, "hasDataAttrs", !0)
                    }
                    return r
                }
                return "object" == typeof t ? this.each(function() {
                    Yt.set(this, t)
                }) : It(this, function(e) {
                    var n;
                    if (s && void 0 === e) {
                        if (n = Yt.get(s, t), void 0 !== n) return n;
                        if (n = v(s, t), void 0 !== n) return n
                    } else this.each(function() {
                        Yt.set(this, t, e)
                    })
                }, null, e, arguments.length > 1, null, !0)
            },
            removeData: function(t) {
                return this.each(function() {
                    Yt.remove(this, t)
                })
            }
        }), xt.extend({
            queue: function(t, e, n) {
                var i;
                if (t) return e = (e || "fx") + "queue", i = Ht.get(t, e), n && (!i || Array.isArray(n) ? i = Ht.access(t, e, xt.makeArray(n)) : i.push(n)), i || []
            },
            dequeue: function(t, e) {
                e = e || "fx";
                var n = xt.queue(t, e),
                    i = n.length,
                    r = n.shift(),
                    s = xt._queueHooks(t, e),
                    o = function() {
                        xt.dequeue(t, e)
                    };
                "inprogress" === r && (r = n.shift(), i--), r && ("fx" === e && n.unshift("inprogress"), delete s.stop, r.call(t, o, s)), !i && s && s.empty.fire()
            },
            _queueHooks: function(t, e) {
                var n = e + "queueHooks";
                return Ht.get(t, n) || Ht.access(t, n, {
                    empty: xt.Callbacks("once memory").add(function() {
                        Ht.remove(t, [e + "queue", n])
                    })
                })
            }
        }), xt.fn.extend({
            queue: function(t, e) {
                var n = 2;
                return "string" != typeof t && (e = t, t = "fx", n--), arguments.length < n ? xt.queue(this[0], t) : void 0 === e ? this : this.each(function() {
                    var n = xt.queue(this, t, e);
                    xt._queueHooks(this, t), "fx" === t && "inprogress" !== n[0] && xt.dequeue(this, t)
                })
            },
            dequeue: function(t) {
                return this.each(function() {
                    xt.dequeue(this, t)
                })
            },
            clearQueue: function(t) {
                return this.queue(t || "fx", [])
            },
            promise: function(t, e) {
                var n, i = 1,
                    r = xt.Deferred(),
                    s = this,
                    o = this.length,
                    a = function() {
                        --i || r.resolveWith(s, [s])
                    };
                for ("string" != typeof t && (e = t, t = void 0), t = t || "fx"; o--;) n = Ht.get(s[o], t + "queueHooks"), n && n.empty && (i++, n.empty.add(a));
                return a(), r.promise(e)
            }
        });
        var qt = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,
            Wt = new RegExp("^(?:([+-])=|)(" + qt + ")([a-z%]*)$", "i"),
            zt = ["Top", "Right", "Bottom", "Left"],
            Gt = function(t, e) {
                return t = e || t, "none" === t.style.display || "" === t.style.display && xt.contains(t.ownerDocument, t) && "none" === xt.css(t, "display")
            },
            Qt = function(t, e, n, i) {
                var r, s, o = {};
                for (s in e) o[s] = t.style[s], t.style[s] = e[s];
                r = n.apply(t, i || []);
                for (s in e) t.style[s] = o[s];
                return r
            },
            Zt = {};
        xt.fn.extend({
            show: function() {
                return _(this, !0)
            },
            hide: function() {
                return _(this)
            },
            toggle: function(t) {
                return "boolean" == typeof t ? t ? this.show() : this.hide() : this.each(function() {
                    Gt(this) ? xt(this).show() : xt(this).hide()
                })
            }
        });
        var Jt = /^(?:checkbox|radio)$/i,
            Kt = /<([a-z][^\/\0>\x20\t\r\n\f]+)/i,
            Xt = /^$|^module$|\/(?:java|ecma)script/i,
            te = {
                option: [1, "<select multiple='multiple'>", "</select>"],
                thead: [1, "<table>", "</table>"],
                col: [2, "<table><colgroup>", "</colgroup></table>"],
                tr: [2, "<table><tbody>", "</tbody></table>"],
                td: [3, "<table><tbody><tr>", "</tr></tbody></table>"],
                _default: [0, "", ""]
            };
        te.optgroup = te.option, te.tbody = te.tfoot = te.colgroup = te.caption = te.thead, te.th = te.td;
        var ee = /<|&#?\w+;/;
        ! function() {
            var t = ot.createDocumentFragment(),
                e = t.appendChild(ot.createElement("div")),
                n = ot.createElement("input");
            n.setAttribute("type", "radio"), n.setAttribute("checked", "checked"), n.setAttribute("name", "t"), e.appendChild(n), vt.checkClone = e.cloneNode(!0).cloneNode(!0).lastChild.checked, e.innerHTML = "<textarea>x</textarea>", vt.noCloneChecked = !!e.cloneNode(!0).lastChild.defaultValue
        }();
        var ne = ot.documentElement,
            ie = /^key/,
            re = /^(?:mouse|pointer|contextmenu|drag|drop)|click/,
            se = /^([^.]*)(?:\.(.+)|)/;
        xt.event = {
            global: {},
            add: function(t, e, n, i, r) {
                var s, o, a, l, h, u, c, d, f, p, g, m = Ht.get(t);
                if (m)
                    for (n.handler && (s = n, n = s.handler, r = s.selector), r && xt.find.matchesSelector(ne, r), n.guid || (n.guid = xt.guid++), (l = m.events) || (l = m.events = {}), (o = m.handle) || (o = m.handle = function(e) {
                            return "undefined" != typeof xt && xt.event.triggered !== e.type ? xt.event.dispatch.apply(t, arguments) : void 0
                        }), e = (e || "").match(At) || [""], h = e.length; h--;) a = se.exec(e[h]) || [], f = g = a[1], p = (a[2] || "").split(".").sort(), f && (c = xt.event.special[f] || {}, f = (r ? c.delegateType : c.bindType) || f, c = xt.event.special[f] || {}, u = xt.extend({
                        type: f,
                        origType: g,
                        data: i,
                        handler: n,
                        guid: n.guid,
                        selector: r,
                        needsContext: r && xt.expr.match.needsContext.test(r),
                        namespace: p.join(".")
                    }, s), (d = l[f]) || (d = l[f] = [], d.delegateCount = 0, c.setup && c.setup.call(t, i, p, o) !== !1 || t.addEventListener && t.addEventListener(f, o)), c.add && (c.add.call(t, u), u.handler.guid || (u.handler.guid = n.guid)), r ? d.splice(d.delegateCount++, 0, u) : d.push(u), xt.event.global[f] = !0)
            },
            remove: function(t, e, n, i, r) {
                var s, o, a, l, h, u, c, d, f, p, g, m = Ht.hasData(t) && Ht.get(t);
                if (m && (l = m.events)) {
                    for (e = (e || "").match(At) || [""], h = e.length; h--;)
                        if (a = se.exec(e[h]) || [], f = g = a[1], p = (a[2] || "").split(".").sort(), f) {
                            for (c = xt.event.special[f] || {}, f = (i ? c.delegateType : c.bindType) || f, d = l[f] || [], a = a[2] && new RegExp("(^|\\.)" + p.join("\\.(?:.*\\.|)") + "(\\.|$)"), o = s = d.length; s--;) u = d[s], !r && g !== u.origType || n && n.guid !== u.guid || a && !a.test(u.namespace) || i && i !== u.selector && ("**" !== i || !u.selector) || (d.splice(s, 1), u.selector && d.delegateCount--, c.remove && c.remove.call(t, u));
                            o && !d.length && (c.teardown && c.teardown.call(t, p, m.handle) !== !1 || xt.removeEvent(t, f, m.handle), delete l[f])
                        } else
                            for (f in l) xt.event.remove(t, f + e[h], n, i, !0);
                    xt.isEmptyObject(l) && Ht.remove(t, "handle events")
                }
            },
            dispatch: function(t) {
                var e, n, i, r, s, o, a = xt.event.fix(t),
                    l = new Array(arguments.length),
                    h = (Ht.get(this, "events") || {})[a.type] || [],
                    u = xt.event.special[a.type] || {};
                for (l[0] = a, e = 1; e < arguments.length; e++) l[e] = arguments[e];
                if (a.delegateTarget = this, !u.preDispatch || u.preDispatch.call(this, a) !== !1) {
                    for (o = xt.event.handlers.call(this, a, h), e = 0;
                        (r = o[e++]) && !a.isPropagationStopped();)
                        for (a.currentTarget = r.elem, n = 0;
                            (s = r.handlers[n++]) && !a.isImmediatePropagationStopped();) a.rnamespace && !a.rnamespace.test(s.namespace) || (a.handleObj = s, a.data = s.data, i = ((xt.event.special[s.origType] || {}).handle || s.handler).apply(r.elem, l), void 0 !== i && (a.result = i) === !1 && (a.preventDefault(), a.stopPropagation()));
                    return u.postDispatch && u.postDispatch.call(this, a), a.result
                }
            },
            handlers: function(t, e) {
                var n, i, r, s, o, a = [],
                    l = e.delegateCount,
                    h = t.target;
                if (l && h.nodeType && !("click" === t.type && t.button >= 1))
                    for (; h !== this; h = h.parentNode || this)
                        if (1 === h.nodeType && ("click" !== t.type || h.disabled !== !0)) {
                            for (s = [], o = {}, n = 0; n < l; n++) i = e[n], r = i.selector + " ", void 0 === o[r] && (o[r] = i.needsContext ? xt(r, this).index(h) > -1 : xt.find(r, this, null, [h]).length), o[r] && s.push(i);
                            s.length && a.push({
                                elem: h,
                                handlers: s
                            })
                        }
                return h = this, l < e.length && a.push({
                    elem: h,
                    handlers: e.slice(l)
                }), a
            },
            addProp: function(t, e) {
                Object.defineProperty(xt.Event.prototype, t, {
                    enumerable: !0,
                    configurable: !0,
                    get: yt(e) ? function() {
                        if (this.originalEvent) return e(this.originalEvent)
                    } : function() {
                        if (this.originalEvent) return this.originalEvent[t]
                    },
                    set: function(e) {
                        Object.defineProperty(this, t, {
                            enumerable: !0,
                            configurable: !0,
                            writable: !0,
                            value: e
                        })
                    }
                })
            },
            fix: function(t) {
                return t[xt.expando] ? t : new xt.Event(t)
            },
            special: {
                load: {
                    noBubble: !0
                },
                focus: {
                    trigger: function() {
                        if (this !== E() && this.focus) return this.focus(), !1
                    },
                    delegateType: "focusin"
                },
                blur: {
                    trigger: function() {
                        if (this === E() && this.blur) return this.blur(), !1
                    },
                    delegateType: "focusout"
                },
                click: {
                    trigger: function() {
                        if ("checkbox" === this.type && this.click && s(this, "input")) return this.click(), !1
                    },
                    _default: function(t) {
                        return s(t.target, "a")
                    }
                },
                beforeunload: {
                    postDispatch: function(t) {
                        void 0 !== t.result && t.originalEvent && (t.originalEvent.returnValue = t.result)
                    }
                }
            }
        }, xt.removeEvent = function(t, e, n) {
            t.removeEventListener && t.removeEventListener(e, n)
        }, xt.Event = function(t, e) {
            return this instanceof xt.Event ? (t && t.type ? (this.originalEvent = t, this.type = t.type, this.isDefaultPrevented = t.defaultPrevented || void 0 === t.defaultPrevented && t.returnValue === !1 ? S : D, this.target = t.target && 3 === t.target.nodeType ? t.target.parentNode : t.target, this.currentTarget = t.currentTarget, this.relatedTarget = t.relatedTarget) : this.type = t, e && xt.extend(this, e), this.timeStamp = t && t.timeStamp || Date.now(), void(this[xt.expando] = !0)) : new xt.Event(t, e)
        }, xt.Event.prototype = {
            constructor: xt.Event,
            isDefaultPrevented: D,
            isPropagationStopped: D,
            isImmediatePropagationStopped: D,
            isSimulated: !1,
            preventDefault: function() {
                var t = this.originalEvent;
                this.isDefaultPrevented = S, t && !this.isSimulated && t.preventDefault()
            },
            stopPropagation: function() {
                var t = this.originalEvent;
                this.isPropagationStopped = S, t && !this.isSimulated && t.stopPropagation()
            },
            stopImmediatePropagation: function() {
                var t = this.originalEvent;
                this.isImmediatePropagationStopped = S, t && !this.isSimulated && t.stopImmediatePropagation(), this.stopPropagation()
            }
        }, xt.each({
            altKey: !0,
            bubbles: !0,
            cancelable: !0,
            changedTouches: !0,
            ctrlKey: !0,
            detail: !0,
            eventPhase: !0,
            metaKey: !0,
            pageX: !0,
            pageY: !0,
            shiftKey: !0,
            view: !0,
            "char": !0,
            charCode: !0,
            key: !0,
            keyCode: !0,
            button: !0,
            buttons: !0,
            clientX: !0,
            clientY: !0,
            offsetX: !0,
            offsetY: !0,
            pointerId: !0,
            pointerType: !0,
            screenX: !0,
            screenY: !0,
            targetTouches: !0,
            toElement: !0,
            touches: !0,
            which: function(t) {
                var e = t.button;
                return null == t.which && ie.test(t.type) ? null != t.charCode ? t.charCode : t.keyCode : !t.which && void 0 !== e && re.test(t.type) ? 1 & e ? 1 : 2 & e ? 3 : 4 & e ? 2 : 0 : t.which
            }
        }, xt.event.addProp), xt.each({
            mouseenter: "mouseover",
            mouseleave: "mouseout",
            pointerenter: "pointerover",
            pointerleave: "pointerout"
        }, function(t, e) {
            xt.event.special[t] = {
                delegateType: e,
                bindType: e,
                handle: function(t) {
                    var n, i = this,
                        r = t.relatedTarget,
                        s = t.handleObj;
                    return r && (r === i || xt.contains(i, r)) || (t.type = s.origType, n = s.handler.apply(this, arguments), t.type = e), n
                }
            }
        }), xt.fn.extend({
            on: function(t, e, n, i) {
                return k(this, t, e, n, i)
            },
            one: function(t, e, n, i) {
                return k(this, t, e, n, i, 1)
            },
            off: function(t, e, n) {
                var i, r;
                if (t && t.preventDefault && t.handleObj) return i = t.handleObj, xt(t.delegateTarget).off(i.namespace ? i.origType + "." + i.namespace : i.origType, i.selector, i.handler), this;
                if ("object" == typeof t) {
                    for (r in t) this.off(r, e, t[r]);
                    return this
                }
                return e !== !1 && "function" != typeof e || (n = e, e = void 0), n === !1 && (n = D), this.each(function() {
                    xt.event.remove(this, t, n, e)
                })
            }
        });
        var oe = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([a-z][^\/\0>\x20\t\r\n\f]*)[^>]*)\/>/gi,
            ae = /<script|<style|<link/i,
            le = /checked\s*(?:[^=]|=\s*.checked.)/i,
            he = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g;
        xt.extend({
            htmlPrefilter: function(t) {
                return t.replace(oe, "<$1></$2>")
            },
            clone: function(t, e, n) {
                var i, r, s, o, a = t.cloneNode(!0),
                    l = xt.contains(t.ownerDocument, t);
                if (!(vt.noCloneChecked || 1 !== t.nodeType && 11 !== t.nodeType || xt.isXMLDoc(t)))
                    for (o = b(a), s = b(t), i = 0, r = s.length; i < r; i++) $(s[i], o[i]);
                if (e)
                    if (n)
                        for (s = s || b(t), o = o || b(a), i = 0, r = s.length; i < r; i++) R(s[i], o[i]);
                    else R(t, a);
                return o = b(a, "script"), o.length > 0 && x(o, !l && b(t, "script")), a
            },
            cleanData: function(t) {
                for (var e, n, i, r = xt.event.special, s = 0; void 0 !== (n = t[s]); s++)
                    if (Bt(n)) {
                        if (e = n[Ht.expando]) {
                            if (e.events)
                                for (i in e.events) r[i] ? xt.event.remove(n, i) : xt.removeEvent(n, i, e.handle);
                            n[Ht.expando] = void 0
                        }
                        n[Yt.expando] && (n[Yt.expando] = void 0)
                    }
            }
        }), xt.fn.extend({
            detach: function(t) {
                return A(this, t, !0)
            },
            remove: function(t) {
                return A(this, t)
            },
            text: function(t) {
                return It(this, function(t) {
                    return void 0 === t ? xt.text(this) : this.empty().each(function() {
                        1 !== this.nodeType && 11 !== this.nodeType && 9 !== this.nodeType || (this.textContent = t)
                    })
                }, null, t, arguments.length)
            },
            append: function() {
                return P(this, arguments, function(t) {
                    if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                        var e = T(this, t);
                        e.appendChild(t)
                    }
                })
            },
            prepend: function() {
                return P(this, arguments, function(t) {
                    if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                        var e = T(this, t);
                        e.insertBefore(t, e.firstChild)
                    }
                })
            },
            before: function() {
                return P(this, arguments, function(t) {
                    this.parentNode && this.parentNode.insertBefore(t, this)
                })
            },
            after: function() {
                return P(this, arguments, function(t) {
                    this.parentNode && this.parentNode.insertBefore(t, this.nextSibling)
                })
            },
            empty: function() {
                for (var t, e = 0; null != (t = this[e]); e++) 1 === t.nodeType && (xt.cleanData(b(t, !1)), t.textContent = "");
                return this
            },
            clone: function(t, e) {
                return t = null != t && t, e = null == e ? t : e, this.map(function() {
                    return xt.clone(this, t, e)
                })
            },
            html: function(t) {
                return It(this, function(t) {
                    var e = this[0] || {},
                        n = 0,
                        i = this.length;
                    if (void 0 === t && 1 === e.nodeType) return e.innerHTML;
                    if ("string" == typeof t && !ae.test(t) && !te[(Kt.exec(t) || ["", ""])[1].toLowerCase()]) {
                        t = xt.htmlPrefilter(t);
                        try {
                            for (; n < i; n++) e = this[n] || {}, 1 === e.nodeType && (xt.cleanData(b(e, !1)), e.innerHTML = t);
                            e = 0
                        } catch (r) {}
                    }
                    e && this.empty().append(t)
                }, null, t, arguments.length)
            },
            replaceWith: function() {
                var t = [];
                return P(this, arguments, function(e) {
                    var n = this.parentNode;
                    xt.inArray(this, t) < 0 && (xt.cleanData(b(this)), n && n.replaceChild(e, this))
                }, t)
            }
        }), xt.each({
            appendTo: "append",
            prependTo: "prepend",
            insertBefore: "before",
            insertAfter: "after",
            replaceAll: "replaceWith"
        }, function(t, e) {
            xt.fn[t] = function(t) {
                for (var n, i = [], r = xt(t), s = r.length - 1, o = 0; o <= s; o++) n = o === s ? this : this.clone(!0), xt(r[o])[e](n), ut.apply(i, n.get());
                return this.pushStack(i)
            }
        });
        var ue = new RegExp("^(" + qt + ")(?!px)[a-z%]+$", "i"),
            ce = function(e) {
                var n = e.ownerDocument.defaultView;
                return n && n.opener || (n = t), n.getComputedStyle(e)
            },
            de = new RegExp(zt.join("|"), "i");
        ! function() {
            function e() {
                if (h) {
                    l.style.cssText = "position:absolute;left:-11111px;width:60px;margin-top:1px;padding:0;border:0", h.style.cssText = "position:relative;display:block;box-sizing:border-box;overflow:scroll;margin:auto;border:1px;padding:1px;width:60%;top:1%", ne.appendChild(l).appendChild(h);
                    var e = t.getComputedStyle(h);
                    i = "1%" !== e.top, a = 12 === n(e.marginLeft), h.style.right = "60%", o = 36 === n(e.right), r = 36 === n(e.width), h.style.position = "absolute", s = 36 === h.offsetWidth || "absolute", ne.removeChild(l), h = null
                }
            }

            function n(t) {
                return Math.round(parseFloat(t))
            }
            var i, r, s, o, a, l = ot.createElement("div"),
                h = ot.createElement("div");
            h.style && (h.style.backgroundClip = "content-box", h.cloneNode(!0).style.backgroundClip = "", vt.clearCloneStyle = "content-box" === h.style.backgroundClip, xt.extend(vt, {
                boxSizingReliable: function() {
                    return e(), r
                },
                pixelBoxStyles: function() {
                    return e(), o
                },
                pixelPosition: function() {
                    return e(), i
                },
                reliableMarginLeft: function() {
                    return e(), a
                },
                scrollboxSize: function() {
                    return e(), s
                }
            }))
        }();
        var fe = /^(none|table(?!-c[ea]).+)/,
            pe = /^--/,
            ge = {
                position: "absolute",
                visibility: "hidden",
                display: "block"
            },
            me = {
                letterSpacing: "0",
                fontWeight: "400"
            },
            ve = ["Webkit", "Moz", "ms"],
            ye = ot.createElement("div").style;
        xt.extend({
            cssHooks: {
                opacity: {
                    get: function(t, e) {
                        if (e) {
                            var n = V(t, "opacity");
                            return "" === n ? "1" : n
                        }
                    }
                }
            },
            cssNumber: {
                animationIterationCount: !0,
                columnCount: !0,
                fillOpacity: !0,
                flexGrow: !0,
                flexShrink: !0,
                fontWeight: !0,
                lineHeight: !0,
                opacity: !0,
                order: !0,
                orphans: !0,
                widows: !0,
                zIndex: !0,
                zoom: !0
            },
            cssProps: {},
            style: function(t, e, n, i) {
                if (t && 3 !== t.nodeType && 8 !== t.nodeType && t.style) {
                    var r, s, o, a = p(e),
                        l = pe.test(e),
                        h = t.style;
                    return l || (e = j(a)), o = xt.cssHooks[e] || xt.cssHooks[a], void 0 === n ? o && "get" in o && void 0 !== (r = o.get(t, !1, i)) ? r : h[e] : (s = typeof n, "string" === s && (r = Wt.exec(n)) && r[1] && (n = y(t, e, r), s = "number"), null != n && n === n && ("number" === s && (n += r && r[3] || (xt.cssNumber[a] ? "" : "px")), vt.clearCloneStyle || "" !== n || 0 !== e.indexOf("background") || (h[e] = "inherit"), o && "set" in o && void 0 === (n = o.set(t, n, i)) || (l ? h.setProperty(e, n) : h[e] = n)), void 0)
                }
            },
            css: function(t, e, n, i) {
                var r, s, o, a = p(e),
                    l = pe.test(e);
                return l || (e = j(a)), o = xt.cssHooks[e] || xt.cssHooks[a], o && "get" in o && (r = o.get(t, !0, n)), void 0 === r && (r = V(t, e, i)), "normal" === r && e in me && (r = me[e]), "" === n || n ? (s = parseFloat(r), n === !0 || isFinite(s) ? s || 0 : r) : r
            }
        }), xt.each(["height", "width"], function(t, e) {
            xt.cssHooks[e] = {
                get: function(t, n, i) {
                    if (n) return !fe.test(xt.css(t, "display")) || t.getClientRects().length && t.getBoundingClientRect().width ? H(t, e, i) : Qt(t, ge, function() {
                        return H(t, e, i)
                    })
                },
                set: function(t, n, i) {
                    var r, s = ce(t),
                        o = "border-box" === xt.css(t, "boxSizing", !1, s),
                        a = i && B(t, e, i, o, s);
                    return o && vt.scrollboxSize() === s.position && (a -= Math.ceil(t["offset" + e[0].toUpperCase() + e.slice(1)] - parseFloat(s[e]) - B(t, e, "border", !1, s) - .5)), a && (r = Wt.exec(n)) && "px" !== (r[3] || "px") && (t.style[e] = n, n = xt.css(t, e)), L(t, n, a)
                }
            }
        }), xt.cssHooks.marginLeft = N(vt.reliableMarginLeft, function(t, e) {
            if (e) return (parseFloat(V(t, "marginLeft")) || t.getBoundingClientRect().left - Qt(t, {
                marginLeft: 0
            }, function() {
                return t.getBoundingClientRect().left
            })) + "px"
        }), xt.each({
            margin: "",
            padding: "",
            border: "Width"
        }, function(t, e) {
            xt.cssHooks[t + e] = {
                expand: function(n) {
                    for (var i = 0, r = {}, s = "string" == typeof n ? n.split(" ") : [n]; i < 4; i++) r[t + zt[i] + e] = s[i] || s[i - 2] || s[0];
                    return r
                }
            }, "margin" !== t && (xt.cssHooks[t + e].set = L)
        }), xt.fn.extend({
            css: function(t, e) {
                return It(this, function(t, e, n) {
                    var i, r, s = {},
                        o = 0;
                    if (Array.isArray(e)) {
                        for (i = ce(t), r = e.length; o < r; o++) s[e[o]] = xt.css(t, e[o], !1, i);
                        return s
                    }
                    return void 0 !== n ? xt.style(t, e, n) : xt.css(t, e)
                }, t, e, arguments.length > 1)
            }
        }), xt.Tween = Y, Y.prototype = {
            constructor: Y,
            init: function(t, e, n, i, r, s) {
                this.elem = t, this.prop = n, this.easing = r || xt.easing._default, this.options = e, this.start = this.now = this.cur(), this.end = i, this.unit = s || (xt.cssNumber[n] ? "" : "px")
            },
            cur: function() {
                var t = Y.propHooks[this.prop];
                return t && t.get ? t.get(this) : Y.propHooks._default.get(this)
            },
            run: function(t) {
                var e, n = Y.propHooks[this.prop];
                return this.options.duration ? this.pos = e = xt.easing[this.easing](t, this.options.duration * t, 0, 1, this.options.duration) : this.pos = e = t, this.now = (this.end - this.start) * e + this.start, this.options.step && this.options.step.call(this.elem, this.now, this), n && n.set ? n.set(this) : Y.propHooks._default.set(this), this
            }
        }, Y.prototype.init.prototype = Y.prototype, Y.propHooks = {
            _default: {
                get: function(t) {
                    var e;
                    return 1 !== t.elem.nodeType || null != t.elem[t.prop] && null == t.elem.style[t.prop] ? t.elem[t.prop] : (e = xt.css(t.elem, t.prop, ""), e && "auto" !== e ? e : 0)
                },
                set: function(t) {
                    xt.fx.step[t.prop] ? xt.fx.step[t.prop](t) : 1 !== t.elem.nodeType || null == t.elem.style[xt.cssProps[t.prop]] && !xt.cssHooks[t.prop] ? t.elem[t.prop] = t.now : xt.style(t.elem, t.prop, t.now + t.unit)
                }
            }
        }, Y.propHooks.scrollTop = Y.propHooks.scrollLeft = {
            set: function(t) {
                t.elem.nodeType && t.elem.parentNode && (t.elem[t.prop] = t.now)
            }
        }, xt.easing = {
            linear: function(t) {
                return t
            },
            swing: function(t) {
                return .5 - Math.cos(t * Math.PI) / 2
            },
            _default: "swing"
        }, xt.fx = Y.prototype.init, xt.fx.step = {};
        var we, _e, be = /^(?:toggle|show|hide)$/,
            xe = /queueHooks$/;
        xt.Animation = xt.extend(Q, {
                tweeners: {
                    "*": [function(t, e) {
                        var n = this.createTween(t, e);
                        return y(n.elem, t, Wt.exec(e), n), n
                    }]
                },
                tweener: function(t, e) {
                    yt(t) ? (e = t, t = ["*"]) : t = t.match(At);
                    for (var n, i = 0, r = t.length; i < r; i++) n = t[i], Q.tweeners[n] = Q.tweeners[n] || [], Q.tweeners[n].unshift(e)
                },
                prefilters: [z],
                prefilter: function(t, e) {
                    e ? Q.prefilters.unshift(t) : Q.prefilters.push(t)
                }
            }), xt.speed = function(t, e, n) {
                var i = t && "object" == typeof t ? xt.extend({}, t) : {
                    complete: n || !n && e || yt(t) && t,
                    duration: t,
                    easing: n && e || e && !yt(e) && e
                };
                return xt.fx.off ? i.duration = 0 : "number" != typeof i.duration && (i.duration in xt.fx.speeds ? i.duration = xt.fx.speeds[i.duration] : i.duration = xt.fx.speeds._default), null != i.queue && i.queue !== !0 || (i.queue = "fx"), i.old = i.complete, i.complete = function() {
                    yt(i.old) && i.old.call(this), i.queue && xt.dequeue(this, i.queue)
                }, i
            }, xt.fn.extend({
                fadeTo: function(t, e, n, i) {
                    return this.filter(Gt).css("opacity", 0).show().end().animate({
                        opacity: e
                    }, t, n, i)
                },
                animate: function(t, e, n, i) {
                    var r = xt.isEmptyObject(t),
                        s = xt.speed(e, n, i),
                        o = function() {
                            var e = Q(this, xt.extend({}, t), s);
                            (r || Ht.get(this, "finish")) && e.stop(!0)
                        };
                    return o.finish = o, r || s.queue === !1 ? this.each(o) : this.queue(s.queue, o)
                },
                stop: function(t, e, n) {
                    var i = function(t) {
                        var e = t.stop;
                        delete t.stop, e(n)
                    };
                    return "string" != typeof t && (n = e, e = t, t = void 0), e && t !== !1 && this.queue(t || "fx", []), this.each(function() {
                        var e = !0,
                            r = null != t && t + "queueHooks",
                            s = xt.timers,
                            o = Ht.get(this);
                        if (r) o[r] && o[r].stop && i(o[r]);
                        else
                            for (r in o) o[r] && o[r].stop && xe.test(r) && i(o[r]);
                        for (r = s.length; r--;) s[r].elem !== this || null != t && s[r].queue !== t || (s[r].anim.stop(n),
                            e = !1, s.splice(r, 1));
                        !e && n || xt.dequeue(this, t)
                    })
                },
                finish: function(t) {
                    return t !== !1 && (t = t || "fx"), this.each(function() {
                        var e, n = Ht.get(this),
                            i = n[t + "queue"],
                            r = n[t + "queueHooks"],
                            s = xt.timers,
                            o = i ? i.length : 0;
                        for (n.finish = !0, xt.queue(this, t, []), r && r.stop && r.stop.call(this, !0), e = s.length; e--;) s[e].elem === this && s[e].queue === t && (s[e].anim.stop(!0), s.splice(e, 1));
                        for (e = 0; e < o; e++) i[e] && i[e].finish && i[e].finish.call(this);
                        delete n.finish
                    })
                }
            }), xt.each(["toggle", "show", "hide"], function(t, e) {
                var n = xt.fn[e];
                xt.fn[e] = function(t, i, r) {
                    return null == t || "boolean" == typeof t ? n.apply(this, arguments) : this.animate(q(e, !0), t, i, r)
                }
            }), xt.each({
                slideDown: q("show"),
                slideUp: q("hide"),
                slideToggle: q("toggle"),
                fadeIn: {
                    opacity: "show"
                },
                fadeOut: {
                    opacity: "hide"
                },
                fadeToggle: {
                    opacity: "toggle"
                }
            }, function(t, e) {
                xt.fn[t] = function(t, n, i) {
                    return this.animate(e, t, n, i)
                }
            }), xt.timers = [], xt.fx.tick = function() {
                var t, e = 0,
                    n = xt.timers;
                for (we = Date.now(); e < n.length; e++) t = n[e], t() || n[e] !== t || n.splice(e--, 1);
                n.length || xt.fx.stop(), we = void 0
            }, xt.fx.timer = function(t) {
                xt.timers.push(t), xt.fx.start()
            }, xt.fx.interval = 13, xt.fx.start = function() {
                _e || (_e = !0, F())
            }, xt.fx.stop = function() {
                _e = null
            }, xt.fx.speeds = {
                slow: 600,
                fast: 200,
                _default: 400
            }, xt.fn.delay = function(e, n) {
                return e = xt.fx ? xt.fx.speeds[e] || e : e, n = n || "fx", this.queue(n, function(n, i) {
                    var r = t.setTimeout(n, e);
                    i.stop = function() {
                        t.clearTimeout(r)
                    }
                })
            },
            function() {
                var t = ot.createElement("input"),
                    e = ot.createElement("select"),
                    n = e.appendChild(ot.createElement("option"));
                t.type = "checkbox", vt.checkOn = "" !== t.value, vt.optSelected = n.selected, t = ot.createElement("input"), t.value = "t", t.type = "radio", vt.radioValue = "t" === t.value
            }();
        var Ce, Se = xt.expr.attrHandle;
        xt.fn.extend({
            attr: function(t, e) {
                return It(this, xt.attr, t, e, arguments.length > 1)
            },
            removeAttr: function(t) {
                return this.each(function() {
                    xt.removeAttr(this, t)
                })
            }
        }), xt.extend({
            attr: function(t, e, n) {
                var i, r, s = t.nodeType;
                if (3 !== s && 8 !== s && 2 !== s) return "undefined" == typeof t.getAttribute ? xt.prop(t, e, n) : (1 === s && xt.isXMLDoc(t) || (r = xt.attrHooks[e.toLowerCase()] || (xt.expr.match.bool.test(e) ? Ce : void 0)), void 0 !== n ? null === n ? void xt.removeAttr(t, e) : r && "set" in r && void 0 !== (i = r.set(t, n, e)) ? i : (t.setAttribute(e, n + ""), n) : r && "get" in r && null !== (i = r.get(t, e)) ? i : (i = xt.find.attr(t, e), null == i ? void 0 : i))
            },
            attrHooks: {
                type: {
                    set: function(t, e) {
                        if (!vt.radioValue && "radio" === e && s(t, "input")) {
                            var n = t.value;
                            return t.setAttribute("type", e), n && (t.value = n), e
                        }
                    }
                }
            },
            removeAttr: function(t, e) {
                var n, i = 0,
                    r = e && e.match(At);
                if (r && 1 === t.nodeType)
                    for (; n = r[i++];) t.removeAttribute(n)
            }
        }), Ce = {
            set: function(t, e, n) {
                return e === !1 ? xt.removeAttr(t, n) : t.setAttribute(n, n), n
            }
        }, xt.each(xt.expr.match.bool.source.match(/\w+/g), function(t, e) {
            var n = Se[e] || xt.find.attr;
            Se[e] = function(t, e, i) {
                var r, s, o = e.toLowerCase();
                return i || (s = Se[o], Se[o] = r, r = null != n(t, e, i) ? o : null, Se[o] = s), r
            }
        });
        var De = /^(?:input|select|textarea|button)$/i,
            Ee = /^(?:a|area)$/i;
        xt.fn.extend({
            prop: function(t, e) {
                return It(this, xt.prop, t, e, arguments.length > 1)
            },
            removeProp: function(t) {
                return this.each(function() {
                    delete this[xt.propFix[t] || t]
                })
            }
        }), xt.extend({
            prop: function(t, e, n) {
                var i, r, s = t.nodeType;
                if (3 !== s && 8 !== s && 2 !== s) return 1 === s && xt.isXMLDoc(t) || (e = xt.propFix[e] || e, r = xt.propHooks[e]), void 0 !== n ? r && "set" in r && void 0 !== (i = r.set(t, n, e)) ? i : t[e] = n : r && "get" in r && null !== (i = r.get(t, e)) ? i : t[e]
            },
            propHooks: {
                tabIndex: {
                    get: function(t) {
                        var e = xt.find.attr(t, "tabindex");
                        return e ? parseInt(e, 10) : De.test(t.nodeName) || Ee.test(t.nodeName) && t.href ? 0 : -1
                    }
                }
            },
            propFix: {
                "for": "htmlFor",
                "class": "className"
            }
        }), vt.optSelected || (xt.propHooks.selected = {
            get: function(t) {
                var e = t.parentNode;
                return e && e.parentNode && e.parentNode.selectedIndex, null
            },
            set: function(t) {
                var e = t.parentNode;
                e && (e.selectedIndex, e.parentNode && e.parentNode.selectedIndex)
            }
        }), xt.each(["tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable"], function() {
            xt.propFix[this.toLowerCase()] = this
        }), xt.fn.extend({
            addClass: function(t) {
                var e, n, i, r, s, o, a, l = 0;
                if (yt(t)) return this.each(function(e) {
                    xt(this).addClass(t.call(this, e, J(this)))
                });
                if (e = K(t), e.length)
                    for (; n = this[l++];)
                        if (r = J(n), i = 1 === n.nodeType && " " + Z(r) + " ") {
                            for (o = 0; s = e[o++];) i.indexOf(" " + s + " ") < 0 && (i += s + " ");
                            a = Z(i), r !== a && n.setAttribute("class", a)
                        }
                return this
            },
            removeClass: function(t) {
                var e, n, i, r, s, o, a, l = 0;
                if (yt(t)) return this.each(function(e) {
                    xt(this).removeClass(t.call(this, e, J(this)))
                });
                if (!arguments.length) return this.attr("class", "");
                if (e = K(t), e.length)
                    for (; n = this[l++];)
                        if (r = J(n), i = 1 === n.nodeType && " " + Z(r) + " ") {
                            for (o = 0; s = e[o++];)
                                for (; i.indexOf(" " + s + " ") > -1;) i = i.replace(" " + s + " ", " ");
                            a = Z(i), r !== a && n.setAttribute("class", a)
                        }
                return this
            },
            toggleClass: function(t, e) {
                var n = typeof t,
                    i = "string" === n || Array.isArray(t);
                return "boolean" == typeof e && i ? e ? this.addClass(t) : this.removeClass(t) : yt(t) ? this.each(function(n) {
                    xt(this).toggleClass(t.call(this, n, J(this), e), e)
                }) : this.each(function() {
                    var e, r, s, o;
                    if (i)
                        for (r = 0, s = xt(this), o = K(t); e = o[r++];) s.hasClass(e) ? s.removeClass(e) : s.addClass(e);
                    else void 0 !== t && "boolean" !== n || (e = J(this), e && Ht.set(this, "__className__", e), this.setAttribute && this.setAttribute("class", e || t === !1 ? "" : Ht.get(this, "__className__") || ""))
                })
            },
            hasClass: function(t) {
                var e, n, i = 0;
                for (e = " " + t + " "; n = this[i++];)
                    if (1 === n.nodeType && (" " + Z(J(n)) + " ").indexOf(e) > -1) return !0;
                return !1
            }
        });
        var ke = /\r/g;
        xt.fn.extend({
            val: function(t) {
                var e, n, i, r = this[0]; {
                    if (arguments.length) return i = yt(t), this.each(function(n) {
                        var r;
                        1 === this.nodeType && (r = i ? t.call(this, n, xt(this).val()) : t, null == r ? r = "" : "number" == typeof r ? r += "" : Array.isArray(r) && (r = xt.map(r, function(t) {
                            return null == t ? "" : t + ""
                        })), e = xt.valHooks[this.type] || xt.valHooks[this.nodeName.toLowerCase()], e && "set" in e && void 0 !== e.set(this, r, "value") || (this.value = r))
                    });
                    if (r) return e = xt.valHooks[r.type] || xt.valHooks[r.nodeName.toLowerCase()], e && "get" in e && void 0 !== (n = e.get(r, "value")) ? n : (n = r.value, "string" == typeof n ? n.replace(ke, "") : null == n ? "" : n)
                }
            }
        }), xt.extend({
            valHooks: {
                option: {
                    get: function(t) {
                        var e = xt.find.attr(t, "value");
                        return null != e ? e : Z(xt.text(t))
                    }
                },
                select: {
                    get: function(t) {
                        var e, n, i, r = t.options,
                            o = t.selectedIndex,
                            a = "select-one" === t.type,
                            l = a ? null : [],
                            h = a ? o + 1 : r.length;
                        for (i = o < 0 ? h : a ? o : 0; i < h; i++)
                            if (n = r[i], (n.selected || i === o) && !n.disabled && (!n.parentNode.disabled || !s(n.parentNode, "optgroup"))) {
                                if (e = xt(n).val(), a) return e;
                                l.push(e)
                            }
                        return l
                    },
                    set: function(t, e) {
                        for (var n, i, r = t.options, s = xt.makeArray(e), o = r.length; o--;) i = r[o], (i.selected = xt.inArray(xt.valHooks.option.get(i), s) > -1) && (n = !0);
                        return n || (t.selectedIndex = -1), s
                    }
                }
            }
        }), xt.each(["radio", "checkbox"], function() {
            xt.valHooks[this] = {
                set: function(t, e) {
                    if (Array.isArray(e)) return t.checked = xt.inArray(xt(t).val(), e) > -1
                }
            }, vt.checkOn || (xt.valHooks[this].get = function(t) {
                return null === t.getAttribute("value") ? "on" : t.value
            })
        }), vt.focusin = "onfocusin" in t;
        var Te = /^(?:focusinfocus|focusoutblur)$/,
            Oe = function(t) {
                t.stopPropagation()
            };
        xt.extend(xt.event, {
            trigger: function(e, n, i, r) {
                var s, o, a, l, h, u, c, d, f = [i || ot],
                    p = pt.call(e, "type") ? e.type : e,
                    g = pt.call(e, "namespace") ? e.namespace.split(".") : [];
                if (o = d = a = i = i || ot, 3 !== i.nodeType && 8 !== i.nodeType && !Te.test(p + xt.event.triggered) && (p.indexOf(".") > -1 && (g = p.split("."), p = g.shift(), g.sort()), h = p.indexOf(":") < 0 && "on" + p, e = e[xt.expando] ? e : new xt.Event(p, "object" == typeof e && e), e.isTrigger = r ? 2 : 3, e.namespace = g.join("."), e.rnamespace = e.namespace ? new RegExp("(^|\\.)" + g.join("\\.(?:.*\\.|)") + "(\\.|$)") : null, e.result = void 0, e.target || (e.target = i), n = null == n ? [e] : xt.makeArray(n, [e]), c = xt.event.special[p] || {}, r || !c.trigger || c.trigger.apply(i, n) !== !1)) {
                    if (!r && !c.noBubble && !wt(i)) {
                        for (l = c.delegateType || p, Te.test(l + p) || (o = o.parentNode); o; o = o.parentNode) f.push(o), a = o;
                        a === (i.ownerDocument || ot) && f.push(a.defaultView || a.parentWindow || t)
                    }
                    for (s = 0;
                        (o = f[s++]) && !e.isPropagationStopped();) d = o, e.type = s > 1 ? l : c.bindType || p, u = (Ht.get(o, "events") || {})[e.type] && Ht.get(o, "handle"), u && u.apply(o, n), u = h && o[h], u && u.apply && Bt(o) && (e.result = u.apply(o, n), e.result === !1 && e.preventDefault());
                    return e.type = p, r || e.isDefaultPrevented() || c._default && c._default.apply(f.pop(), n) !== !1 || !Bt(i) || h && yt(i[p]) && !wt(i) && (a = i[h], a && (i[h] = null), xt.event.triggered = p, e.isPropagationStopped() && d.addEventListener(p, Oe), i[p](), e.isPropagationStopped() && d.removeEventListener(p, Oe), xt.event.triggered = void 0, a && (i[h] = a)), e.result
                }
            },
            simulate: function(t, e, n) {
                var i = xt.extend(new xt.Event, n, {
                    type: t,
                    isSimulated: !0
                });
                xt.event.trigger(i, null, e)
            }
        }), xt.fn.extend({
            trigger: function(t, e) {
                return this.each(function() {
                    xt.event.trigger(t, e, this)
                })
            },
            triggerHandler: function(t, e) {
                var n = this[0];
                if (n) return xt.event.trigger(t, e, n, !0)
            }
        }), vt.focusin || xt.each({
            focus: "focusin",
            blur: "focusout"
        }, function(t, e) {
            var n = function(t) {
                xt.event.simulate(e, t.target, xt.event.fix(t))
            };
            xt.event.special[e] = {
                setup: function() {
                    var i = this.ownerDocument || this,
                        r = Ht.access(i, e);
                    r || i.addEventListener(t, n, !0), Ht.access(i, e, (r || 0) + 1)
                },
                teardown: function() {
                    var i = this.ownerDocument || this,
                        r = Ht.access(i, e) - 1;
                    r ? Ht.access(i, e, r) : (i.removeEventListener(t, n, !0), Ht.remove(i, e))
                }
            }
        });
        var Me = t.location,
            Re = Date.now(),
            $e = /\?/;
        xt.parseXML = function(e) {
            var n;
            if (!e || "string" != typeof e) return null;
            try {
                n = (new t.DOMParser).parseFromString(e, "text/xml")
            } catch (i) {
                n = void 0
            }
            return n && !n.getElementsByTagName("parsererror").length || xt.error("Invalid XML: " + e), n
        };
        var Pe = /\[\]$/,
            Ae = /\r?\n/g,
            Ve = /^(?:submit|button|image|reset|file)$/i,
            Ne = /^(?:input|select|textarea|keygen)/i;
        xt.param = function(t, e) {
            var n, i = [],
                r = function(t, e) {
                    var n = yt(e) ? e() : e;
                    i[i.length] = encodeURIComponent(t) + "=" + encodeURIComponent(null == n ? "" : n)
                };
            if (Array.isArray(t) || t.jquery && !xt.isPlainObject(t)) xt.each(t, function() {
                r(this.name, this.value)
            });
            else
                for (n in t) X(n, t[n], e, r);
            return i.join("&")
        }, xt.fn.extend({
            serialize: function() {
                return xt.param(this.serializeArray())
            },
            serializeArray: function() {
                return this.map(function() {
                    var t = xt.prop(this, "elements");
                    return t ? xt.makeArray(t) : this
                }).filter(function() {
                    var t = this.type;
                    return this.name && !xt(this).is(":disabled") && Ne.test(this.nodeName) && !Ve.test(t) && (this.checked || !Jt.test(t))
                }).map(function(t, e) {
                    var n = xt(this).val();
                    return null == n ? null : Array.isArray(n) ? xt.map(n, function(t) {
                        return {
                            name: e.name,
                            value: t.replace(Ae, "\r\n")
                        }
                    }) : {
                        name: e.name,
                        value: n.replace(Ae, "\r\n")
                    }
                }).get()
            }
        });
        var Ie = /%20/g,
            je = /#.*$/,
            Le = /([?&])_=[^&]*/,
            Be = /^(.*?):[ \t]*([^\r\n]*)$/gm,
            He = /^(?:about|app|app-storage|.+-extension|file|res|widget):$/,
            Ye = /^(?:GET|HEAD)$/,
            Fe = /^\/\//,
            Ue = {},
            qe = {},
            We = "*/".concat("*"),
            ze = ot.createElement("a");
        ze.href = Me.href, xt.extend({
            active: 0,
            lastModified: {},
            etag: {},
            ajaxSettings: {
                url: Me.href,
                type: "GET",
                isLocal: He.test(Me.protocol),
                global: !0,
                processData: !0,
                async: !0,
                contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                accepts: {
                    "*": We,
                    text: "text/plain",
                    html: "text/html",
                    xml: "application/xml, text/xml",
                    json: "application/json, text/javascript"
                },
                contents: {
                    xml: /\bxml\b/,
                    html: /\bhtml/,
                    json: /\bjson\b/
                },
                responseFields: {
                    xml: "responseXML",
                    text: "responseText",
                    json: "responseJSON"
                },
                converters: {
                    "* text": String,
                    "text html": !0,
                    "text json": JSON.parse,
                    "text xml": xt.parseXML
                },
                flatOptions: {
                    url: !0,
                    context: !0
                }
            },
            ajaxSetup: function(t, e) {
                return e ? nt(nt(t, xt.ajaxSettings), e) : nt(xt.ajaxSettings, t)
            },
            ajaxPrefilter: tt(Ue),
            ajaxTransport: tt(qe),
            ajax: function(e, n) {
                function i(e, n, i, a) {
                    var h, d, f, _, b, x = n;
                    u || (u = !0, l && t.clearTimeout(l), r = void 0, o = a || "", C.readyState = e > 0 ? 4 : 0, h = e >= 200 && e < 300 || 304 === e, i && (_ = it(p, C, i)), _ = rt(p, _, C, h), h ? (p.ifModified && (b = C.getResponseHeader("Last-Modified"), b && (xt.lastModified[s] = b), b = C.getResponseHeader("etag"), b && (xt.etag[s] = b)), 204 === e || "HEAD" === p.type ? x = "nocontent" : 304 === e ? x = "notmodified" : (x = _.state, d = _.data, f = _.error, h = !f)) : (f = x, !e && x || (x = "error", e < 0 && (e = 0))), C.status = e, C.statusText = (n || x) + "", h ? v.resolveWith(g, [d, x, C]) : v.rejectWith(g, [C, x, f]), C.statusCode(w), w = void 0, c && m.trigger(h ? "ajaxSuccess" : "ajaxError", [C, p, h ? d : f]), y.fireWith(g, [C, x]), c && (m.trigger("ajaxComplete", [C, p]), --xt.active || xt.event.trigger("ajaxStop")))
                }
                "object" == typeof e && (n = e, e = void 0), n = n || {};
                var r, s, o, a, l, h, u, c, d, f, p = xt.ajaxSetup({}, n),
                    g = p.context || p,
                    m = p.context && (g.nodeType || g.jquery) ? xt(g) : xt.event,
                    v = xt.Deferred(),
                    y = xt.Callbacks("once memory"),
                    w = p.statusCode || {},
                    _ = {},
                    b = {},
                    x = "canceled",
                    C = {
                        readyState: 0,
                        getResponseHeader: function(t) {
                            var e;
                            if (u) {
                                if (!a)
                                    for (a = {}; e = Be.exec(o);) a[e[1].toLowerCase()] = e[2];
                                e = a[t.toLowerCase()]
                            }
                            return null == e ? null : e
                        },
                        getAllResponseHeaders: function() {
                            return u ? o : null
                        },
                        setRequestHeader: function(t, e) {
                            return null == u && (t = b[t.toLowerCase()] = b[t.toLowerCase()] || t, _[t] = e), this
                        },
                        overrideMimeType: function(t) {
                            return null == u && (p.mimeType = t), this
                        },
                        statusCode: function(t) {
                            var e;
                            if (t)
                                if (u) C.always(t[C.status]);
                                else
                                    for (e in t) w[e] = [w[e], t[e]];
                            return this
                        },
                        abort: function(t) {
                            var e = t || x;
                            return r && r.abort(e), i(0, e), this
                        }
                    };
                if (v.promise(C), p.url = ((e || p.url || Me.href) + "").replace(Fe, Me.protocol + "//"), p.type = n.method || n.type || p.method || p.type, p.dataTypes = (p.dataType || "*").toLowerCase().match(At) || [""], null == p.crossDomain) {
                    h = ot.createElement("a");
                    try {
                        h.href = p.url, h.href = h.href, p.crossDomain = ze.protocol + "//" + ze.host != h.protocol + "//" + h.host
                    } catch (S) {
                        p.crossDomain = !0
                    }
                }
                if (p.data && p.processData && "string" != typeof p.data && (p.data = xt.param(p.data, p.traditional)), et(Ue, p, n, C), u) return C;
                c = xt.event && p.global, c && 0 === xt.active++ && xt.event.trigger("ajaxStart"), p.type = p.type.toUpperCase(), p.hasContent = !Ye.test(p.type), s = p.url.replace(je, ""), p.hasContent ? p.data && p.processData && 0 === (p.contentType || "").indexOf("application/x-www-form-urlencoded") && (p.data = p.data.replace(Ie, "+")) : (f = p.url.slice(s.length), p.data && (p.processData || "string" == typeof p.data) && (s += ($e.test(s) ? "&" : "?") + p.data, delete p.data), p.cache === !1 && (s = s.replace(Le, "$1"), f = ($e.test(s) ? "&" : "?") + "_=" + Re++ + f), p.url = s + f), p.ifModified && (xt.lastModified[s] && C.setRequestHeader("If-Modified-Since", xt.lastModified[s]), xt.etag[s] && C.setRequestHeader("If-None-Match", xt.etag[s])), (p.data && p.hasContent && p.contentType !== !1 || n.contentType) && C.setRequestHeader("Content-Type", p.contentType), C.setRequestHeader("Accept", p.dataTypes[0] && p.accepts[p.dataTypes[0]] ? p.accepts[p.dataTypes[0]] + ("*" !== p.dataTypes[0] ? ", " + We + "; q=0.01" : "") : p.accepts["*"]);
                for (d in p.headers) C.setRequestHeader(d, p.headers[d]);
                if (p.beforeSend && (p.beforeSend.call(g, C, p) === !1 || u)) return C.abort();
                if (x = "abort", y.add(p.complete), C.done(p.success), C.fail(p.error), r = et(qe, p, n, C)) {
                    if (C.readyState = 1, c && m.trigger("ajaxSend", [C, p]), u) return C;
                    p.async && p.timeout > 0 && (l = t.setTimeout(function() {
                        C.abort("timeout")
                    }, p.timeout));
                    try {
                        u = !1, r.send(_, i)
                    } catch (S) {
                        if (u) throw S;
                        i(-1, S)
                    }
                } else i(-1, "No Transport");
                return C
            },
            getJSON: function(t, e, n) {
                return xt.get(t, e, n, "json")
            },
            getScript: function(t, e) {
                return xt.get(t, void 0, e, "script")
            }
        }), xt.each(["get", "post"], function(t, e) {
            xt[e] = function(t, n, i, r) {
                return yt(n) && (r = r || i, i = n, n = void 0), xt.ajax(xt.extend({
                    url: t,
                    type: e,
                    dataType: r,
                    data: n,
                    success: i
                }, xt.isPlainObject(t) && t))
            }
        }), xt._evalUrl = function(t) {
            return xt.ajax({
                url: t,
                type: "GET",
                dataType: "script",
                cache: !0,
                async: !1,
                global: !1,
                "throws": !0
            })
        }, xt.fn.extend({
            wrapAll: function(t) {
                var e;
                return this[0] && (yt(t) && (t = t.call(this[0])), e = xt(t, this[0].ownerDocument).eq(0).clone(!0), this[0].parentNode && e.insertBefore(this[0]), e.map(function() {
                    for (var t = this; t.firstElementChild;) t = t.firstElementChild;
                    return t
                }).append(this)), this
            },
            wrapInner: function(t) {
                return yt(t) ? this.each(function(e) {
                    xt(this).wrapInner(t.call(this, e))
                }) : this.each(function() {
                    var e = xt(this),
                        n = e.contents();
                    n.length ? n.wrapAll(t) : e.append(t)
                })
            },
            wrap: function(t) {
                var e = yt(t);
                return this.each(function(n) {
                    xt(this).wrapAll(e ? t.call(this, n) : t)
                })
            },
            unwrap: function(t) {
                return this.parent(t).not("body").each(function() {
                    xt(this).replaceWith(this.childNodes)
                }), this
            }
        }), xt.expr.pseudos.hidden = function(t) {
            return !xt.expr.pseudos.visible(t)
        }, xt.expr.pseudos.visible = function(t) {
            return !!(t.offsetWidth || t.offsetHeight || t.getClientRects().length)
        }, xt.ajaxSettings.xhr = function() {
            try {
                return new t.XMLHttpRequest
            } catch (e) {}
        };
        var Ge = {
                0: 200,
                1223: 204
            },
            Qe = xt.ajaxSettings.xhr();
        vt.cors = !!Qe && "withCredentials" in Qe, vt.ajax = Qe = !!Qe, xt.ajaxTransport(function(e) {
            var n, i;
            if (vt.cors || Qe && !e.crossDomain) return {
                send: function(r, s) {
                    var o, a = e.xhr();
                    if (a.open(e.type, e.url, e.async, e.username, e.password), e.xhrFields)
                        for (o in e.xhrFields) a[o] = e.xhrFields[o];
                    e.mimeType && a.overrideMimeType && a.overrideMimeType(e.mimeType), e.crossDomain || r["X-Requested-With"] || (r["X-Requested-With"] = "XMLHttpRequest");
                    for (o in r) a.setRequestHeader(o, r[o]);
                    n = function(t) {
                        return function() {
                            n && (n = i = a.onload = a.onerror = a.onabort = a.ontimeout = a.onreadystatechange = null, "abort" === t ? a.abort() : "error" === t ? "number" != typeof a.status ? s(0, "error") : s(a.status, a.statusText) : s(Ge[a.status] || a.status, a.statusText, "text" !== (a.responseType || "text") || "string" != typeof a.responseText ? {
                                binary: a.response
                            } : {
                                text: a.responseText
                            }, a.getAllResponseHeaders()))
                        }
                    }, a.onload = n(), i = a.onerror = a.ontimeout = n("error"), void 0 !== a.onabort ? a.onabort = i : a.onreadystatechange = function() {
                        4 === a.readyState && t.setTimeout(function() {
                            n && i()
                        })
                    }, n = n("abort");
                    try {
                        a.send(e.hasContent && e.data || null)
                    } catch (l) {
                        if (n) throw l
                    }
                },
                abort: function() {
                    n && n()
                }
            }
        }), xt.ajaxPrefilter(function(t) {
            t.crossDomain && (t.contents.script = !1)
        }), xt.ajaxSetup({
            accepts: {
                script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"
            },
            contents: {
                script: /\b(?:java|ecma)script\b/
            },
            converters: {
                "text script": function(t) {
                    return xt.globalEval(t), t
                }
            }
        }), xt.ajaxPrefilter("script", function(t) {
            void 0 === t.cache && (t.cache = !1), t.crossDomain && (t.type = "GET")
        }), xt.ajaxTransport("script", function(t) {
            if (t.crossDomain) {
                var e, n;
                return {
                    send: function(i, r) {
                        e = xt("<script>").prop({
                            charset: t.scriptCharset,
                            src: t.url
                        }).on("load error", n = function(t) {
                            e.remove(), n = null, t && r("error" === t.type ? 404 : 200, t.type)
                        }), ot.head.appendChild(e[0])
                    },
                    abort: function() {
                        n && n()
                    }
                }
            }
        });
        var Ze = [],
            Je = /(=)\?(?=&|$)|\?\?/;
        xt.ajaxSetup({
            jsonp: "callback",
            jsonpCallback: function() {
                var t = Ze.pop() || xt.expando + "_" + Re++;
                return this[t] = !0, t
            }
        }), xt.ajaxPrefilter("json jsonp", function(e, n, i) {
            var r, s, o, a = e.jsonp !== !1 && (Je.test(e.url) ? "url" : "string" == typeof e.data && 0 === (e.contentType || "").indexOf("application/x-www-form-urlencoded") && Je.test(e.data) && "data");
            if (a || "jsonp" === e.dataTypes[0]) return r = e.jsonpCallback = yt(e.jsonpCallback) ? e.jsonpCallback() : e.jsonpCallback, a ? e[a] = e[a].replace(Je, "$1" + r) : e.jsonp !== !1 && (e.url += ($e.test(e.url) ? "&" : "?") + e.jsonp + "=" + r), e.converters["script json"] = function() {
                return o || xt.error(r + " was not called"), o[0]
            }, e.dataTypes[0] = "json", s = t[r], t[r] = function() {
                o = arguments
            }, i.always(function() {
                void 0 === s ? xt(t).removeProp(r) : t[r] = s, e[r] && (e.jsonpCallback = n.jsonpCallback, Ze.push(r)), o && yt(s) && s(o[0]), o = s = void 0
            }), "script"
        }), vt.createHTMLDocument = function() {
            var t = ot.implementation.createHTMLDocument("").body;
            return t.innerHTML = "<form></form><form></form>", 2 === t.childNodes.length
        }(), xt.parseHTML = function(t, e, n) {
            if ("string" != typeof t) return [];
            "boolean" == typeof e && (n = e, e = !1);
            var i, r, s;
            return e || (vt.createHTMLDocument ? (e = ot.implementation.createHTMLDocument(""), i = e.createElement("base"), i.href = ot.location.href, e.head.appendChild(i)) : e = ot), r = Tt.exec(t), s = !n && [], r ? [e.createElement(r[1])] : (r = C([t], e, s), s && s.length && xt(s).remove(), xt.merge([], r.childNodes))
        }, xt.fn.load = function(t, e, n) {
            var i, r, s, o = this,
                a = t.indexOf(" ");
            return a > -1 && (i = Z(t.slice(a)), t = t.slice(0, a)), yt(e) ? (n = e, e = void 0) : e && "object" == typeof e && (r = "POST"), o.length > 0 && xt.ajax({
                url: t,
                type: r || "GET",
                dataType: "html",
                data: e
            }).done(function(t) {
                s = arguments, o.html(i ? xt("<div>").append(xt.parseHTML(t)).find(i) : t)
            }).always(n && function(t, e) {
                o.each(function() {
                    n.apply(this, s || [t.responseText, e, t])
                })
            }), this
        }, xt.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"], function(t, e) {
            xt.fn[e] = function(t) {
                return this.on(e, t)
            }
        }), xt.expr.pseudos.animated = function(t) {
            return xt.grep(xt.timers, function(e) {
                return t === e.elem
            }).length
        }, xt.offset = {
            setOffset: function(t, e, n) {
                var i, r, s, o, a, l, h, u = xt.css(t, "position"),
                    c = xt(t),
                    d = {};
                "static" === u && (t.style.position = "relative"), a = c.offset(), s = xt.css(t, "top"), l = xt.css(t, "left"), h = ("absolute" === u || "fixed" === u) && (s + l).indexOf("auto") > -1, h ? (i = c.position(), o = i.top, r = i.left) : (o = parseFloat(s) || 0, r = parseFloat(l) || 0), yt(e) && (e = e.call(t, n, xt.extend({}, a))), null != e.top && (d.top = e.top - a.top + o), null != e.left && (d.left = e.left - a.left + r), "using" in e ? e.using.call(t, d) : c.css(d)
            }
        }, xt.fn.extend({
            offset: function(t) {
                if (arguments.length) return void 0 === t ? this : this.each(function(e) {
                    xt.offset.setOffset(this, t, e)
                });
                var e, n, i = this[0];
                if (i) return i.getClientRects().length ? (e = i.getBoundingClientRect(), n = i.ownerDocument.defaultView, {
                    top: e.top + n.pageYOffset,
                    left: e.left + n.pageXOffset
                }) : {
                    top: 0,
                    left: 0
                }
            },
            position: function() {
                if (this[0]) {
                    var t, e, n, i = this[0],
                        r = {
                            top: 0,
                            left: 0
                        };
                    if ("fixed" === xt.css(i, "position")) e = i.getBoundingClientRect();
                    else {
                        for (e = this.offset(), n = i.ownerDocument, t = i.offsetParent || n.documentElement; t && (t === n.body || t === n.documentElement) && "static" === xt.css(t, "position");) t = t.parentNode;
                        t && t !== i && 1 === t.nodeType && (r = xt(t).offset(), r.top += xt.css(t, "borderTopWidth", !0), r.left += xt.css(t, "borderLeftWidth", !0))
                    }
                    return {
                        top: e.top - r.top - xt.css(i, "marginTop", !0),
                        left: e.left - r.left - xt.css(i, "marginLeft", !0)
                    }
                }
            },
            offsetParent: function() {
                return this.map(function() {
                    for (var t = this.offsetParent; t && "static" === xt.css(t, "position");) t = t.offsetParent;
                    return t || ne
                })
            }
        }), xt.each({
            scrollLeft: "pageXOffset",
            scrollTop: "pageYOffset"
        }, function(t, e) {
            var n = "pageYOffset" === e;
            xt.fn[t] = function(i) {
                return It(this, function(t, i, r) {
                    var s;
                    return wt(t) ? s = t : 9 === t.nodeType && (s = t.defaultView), void 0 === r ? s ? s[e] : t[i] : void(s ? s.scrollTo(n ? s.pageXOffset : r, n ? r : s.pageYOffset) : t[i] = r)
                }, t, i, arguments.length)
            }
        }), xt.each(["top", "left"], function(t, e) {
            xt.cssHooks[e] = N(vt.pixelPosition, function(t, n) {
                if (n) return n = V(t, e), ue.test(n) ? xt(t).position()[e] + "px" : n
            })
        }), xt.each({
            Height: "height",
            Width: "width"
        }, function(t, e) {
            xt.each({
                padding: "inner" + t,
                content: e,
                "": "outer" + t
            }, function(n, i) {
                xt.fn[i] = function(r, s) {
                    var o = arguments.length && (n || "boolean" != typeof r),
                        a = n || (r === !0 || s === !0 ? "margin" : "border");
                    return It(this, function(e, n, r) {
                        var s;
                        return wt(e) ? 0 === i.indexOf("outer") ? e["inner" + t] : e.document.documentElement["client" + t] : 9 === e.nodeType ? (s = e.documentElement, Math.max(e.body["scroll" + t], s["scroll" + t], e.body["offset" + t], s["offset" + t], s["client" + t])) : void 0 === r ? xt.css(e, n, a) : xt.style(e, n, r, a)
                    }, e, o ? r : void 0, o)
                }
            })
        }), xt.each("blur focus focusin focusout resize scroll click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup contextmenu".split(" "), function(t, e) {
            xt.fn[e] = function(t, n) {
                return arguments.length > 0 ? this.on(e, null, t, n) : this.trigger(e)
            }
        }), xt.fn.extend({
            hover: function(t, e) {
                return this.mouseenter(t).mouseleave(e || t)
            }
        }), xt.fn.extend({
            bind: function(t, e, n) {
                return this.on(t, null, e, n)
            },
            unbind: function(t, e) {
                return this.off(t, null, e)
            },
            delegate: function(t, e, n, i) {
                return this.on(e, t, n, i)
            },
            undelegate: function(t, e, n) {
                return 1 === arguments.length ? this.off(t, "**") : this.off(e, t || "**", n)
            }
        }), xt.proxy = function(t, e) {
            var n, i, r;
            if ("string" == typeof e && (n = t[e], e = t, t = n), yt(t)) return i = lt.call(arguments, 2), r = function() {
                return t.apply(e || this, i.concat(lt.call(arguments)))
            }, r.guid = t.guid = t.guid || xt.guid++, r
        }, xt.holdReady = function(t) {
            t ? xt.readyWait++ : xt.ready(!0)
        }, xt.isArray = Array.isArray, xt.parseJSON = JSON.parse, xt.nodeName = s, xt.isFunction = yt, xt.isWindow = wt, xt.camelCase = p, xt.type = i, xt.now = Date.now, xt.isNumeric = function(t) {
            var e = xt.type(t);
            return ("number" === e || "string" === e) && !isNaN(t - parseFloat(t))
        }, "function" == typeof define && define.amd && define("jquery", [], function() {
            return xt
        });
        var Ke = t.jQuery,
            Xe = t.$;
        return xt.noConflict = function(e) {
            return t.$ === xt && (t.$ = Xe), e && t.jQuery === xt && (t.jQuery = Ke), xt
        }, e || (t.jQuery = t.$ = xt), xt
    }), function() {
        function t(t) {
            function e(e, n, i, r, s, o) {
                for (; s >= 0 && s < o; s += t) {
                    var a = r ? r[s] : s;
                    i = n(i, e[a], a, e)
                }
                return i
            }
            return function(n, i, r, s) {
                i = w(i, s, 4);
                var o = !E(n) && y.keys(n),
                    a = (o || n).length,
                    l = t > 0 ? 0 : a - 1;
                return arguments.length < 3 && (r = n[o ? o[l] : l], l += t), e(n, i, r, o, l, a)
            }
        }

        function e(t) {
            return function(e, n, i) {
                n = _(n, i);
                for (var r = D(e), s = t > 0 ? 0 : r - 1; s >= 0 && s < r; s += t)
                    if (n(e[s], s, e)) return s;
                return -1
            }
        }

        function n(t, e, n) {
            return function(i, r, s) {
                var o = 0,
                    a = D(i);
                if ("number" == typeof s) t > 0 ? o = s >= 0 ? s : Math.max(s + a, o) : a = s >= 0 ? Math.min(s + 1, a) : s + a + 1;
                else if (n && s && a) return s = n(i, r), i[s] === r ? s : -1;
                if (r !== r) return s = e(u.call(i, o, a), y.isNaN), s >= 0 ? s + o : -1;
                for (s = t > 0 ? o : a - 1; s >= 0 && s < a; s += t)
                    if (i[s] === r) return s;
                return -1
            }
        }

        function i(t, e) {
            var n = R.length,
                i = t.constructor,
                r = y.isFunction(i) && i.prototype || a,
                s = "constructor";
            for (y.has(t, s) && !y.contains(e, s) && e.push(s); n--;) s = R[n], s in t && t[s] !== r[s] && !y.contains(e, s) && e.push(s)
        }
        var r = this,
            s = r._,
            o = Array.prototype,
            a = Object.prototype,
            l = Function.prototype,
            h = o.push,
            u = o.slice,
            c = a.toString,
            d = a.hasOwnProperty,
            f = Array.isArray,
            p = Object.keys,
            g = l.bind,
            m = Object.create,
            v = function() {},
            y = function(t) {
                return t instanceof y ? t : this instanceof y ? void(this._wrapped = t) : new y(t)
            };
        "undefined" != typeof exports ? ("undefined" != typeof module && module.exports && (exports = module.exports = y), exports._ = y) : r._ = y, y.VERSION = "1.8.3";
        var w = function(t, e, n) {
                if (void 0 === e) return t;
                switch (null == n ? 3 : n) {
                    case 1:
                        return function(n) {
                            return t.call(e, n)
                        };
                    case 2:
                        return function(n, i) {
                            return t.call(e, n, i)
                        };
                    case 3:
                        return function(n, i, r) {
                            return t.call(e, n, i, r)
                        };
                    case 4:
                        return function(n, i, r, s) {
                            return t.call(e, n, i, r, s)
                        }
                }
                return function() {
                    return t.apply(e, arguments)
                }
            },
            _ = function(t, e, n) {
                return null == t ? y.identity : y.isFunction(t) ? w(t, e, n) : y.isObject(t) ? y.matcher(t) : y.property(t)
            };
        y.iteratee = function(t, e) {
            return _(t, e, 1 / 0)
        };
        var b = function(t, e) {
                return function(n) {
                    var i = arguments.length;
                    if (i < 2 || null == n) return n;
                    for (var r = 1; r < i; r++)
                        for (var s = arguments[r], o = t(s), a = o.length, l = 0; l < a; l++) {
                            var h = o[l];
                            e && void 0 !== n[h] || (n[h] = s[h])
                        }
                    return n
                }
            },
            x = function(t) {
                if (!y.isObject(t)) return {};
                if (m) return m(t);
                v.prototype = t;
                var e = new v;
                return v.prototype = null, e
            },
            C = function(t) {
                return function(e) {
                    return null == e ? void 0 : e[t]
                }
            },
            S = Math.pow(2, 53) - 1,
            D = C("length"),
            E = function(t) {
                var e = D(t);
                return "number" == typeof e && e >= 0 && e <= S
            };
        y.each = y.forEach = function(t, e, n) {
            e = w(e, n);
            var i, r;
            if (E(t))
                for (i = 0, r = t.length; i < r; i++) e(t[i], i, t);
            else {
                var s = y.keys(t);
                for (i = 0, r = s.length; i < r; i++) e(t[s[i]], s[i], t)
            }
            return t
        }, y.map = y.collect = function(t, e, n) {
            e = _(e, n);
            for (var i = !E(t) && y.keys(t), r = (i || t).length, s = Array(r), o = 0; o < r; o++) {
                var a = i ? i[o] : o;
                s[o] = e(t[a], a, t)
            }
            return s
        }, y.reduce = y.foldl = y.inject = t(1), y.reduceRight = y.foldr = t(-1), y.find = y.detect = function(t, e, n) {
            var i;
            if (i = E(t) ? y.findIndex(t, e, n) : y.findKey(t, e, n), void 0 !== i && i !== -1) return t[i]
        }, y.filter = y.select = function(t, e, n) {
            var i = [];
            return e = _(e, n), y.each(t, function(t, n, r) {
                e(t, n, r) && i.push(t)
            }), i
        }, y.reject = function(t, e, n) {
            return y.filter(t, y.negate(_(e)), n)
        }, y.every = y.all = function(t, e, n) {
            e = _(e, n);
            for (var i = !E(t) && y.keys(t), r = (i || t).length, s = 0; s < r; s++) {
                var o = i ? i[s] : s;
                if (!e(t[o], o, t)) return !1
            }
            return !0
        }, y.some = y.any = function(t, e, n) {
            e = _(e, n);
            for (var i = !E(t) && y.keys(t), r = (i || t).length, s = 0; s < r; s++) {
                var o = i ? i[s] : s;
                if (e(t[o], o, t)) return !0
            }
            return !1
        }, y.contains = y.includes = y.include = function(t, e, n, i) {
            return E(t) || (t = y.values(t)), ("number" != typeof n || i) && (n = 0), y.indexOf(t, e, n) >= 0
        }, y.invoke = function(t, e) {
            var n = u.call(arguments, 2),
                i = y.isFunction(e);
            return y.map(t, function(t) {
                var r = i ? e : t[e];
                return null == r ? r : r.apply(t, n)
            })
        }, y.pluck = function(t, e) {
            return y.map(t, y.property(e))
        }, y.where = function(t, e) {
            return y.filter(t, y.matcher(e))
        }, y.findWhere = function(t, e) {
            return y.find(t, y.matcher(e))
        }, y.max = function(t, e, n) {
            var i, r, s = -(1 / 0),
                o = -(1 / 0);
            if (null == e && null != t) {
                t = E(t) ? t : y.values(t);
                for (var a = 0, l = t.length; a < l; a++) i = t[a], i > s && (s = i)
            } else e = _(e, n), y.each(t, function(t, n, i) {
                r = e(t, n, i), (r > o || r === -(1 / 0) && s === -(1 / 0)) && (s = t, o = r)
            });
            return s
        }, y.min = function(t, e, n) {
            var i, r, s = 1 / 0,
                o = 1 / 0;
            if (null == e && null != t) {
                t = E(t) ? t : y.values(t);
                for (var a = 0, l = t.length; a < l; a++) i = t[a], i < s && (s = i)
            } else e = _(e, n), y.each(t, function(t, n, i) {
                r = e(t, n, i), (r < o || r === 1 / 0 && s === 1 / 0) && (s = t, o = r)
            });
            return s
        }, y.shuffle = function(t) {
            for (var e, n = E(t) ? t : y.values(t), i = n.length, r = Array(i), s = 0; s < i; s++) e = y.random(0, s), e !== s && (r[s] = r[e]), r[e] = n[s];
            return r
        }, y.sample = function(t, e, n) {
            return null == e || n ? (E(t) || (t = y.values(t)), t[y.random(t.length - 1)]) : y.shuffle(t).slice(0, Math.max(0, e))
        }, y.sortBy = function(t, e, n) {
            return e = _(e, n), y.pluck(y.map(t, function(t, n, i) {
                return {
                    value: t,
                    index: n,
                    criteria: e(t, n, i)
                }
            }).sort(function(t, e) {
                var n = t.criteria,
                    i = e.criteria;
                if (n !== i) {
                    if (n > i || void 0 === n) return 1;
                    if (n < i || void 0 === i) return -1
                }
                return t.index - e.index
            }), "value")
        };
        var k = function(t) {
            return function(e, n, i) {
                var r = {};
                return n = _(n, i), y.each(e, function(i, s) {
                    var o = n(i, s, e);
                    t(r, i, o)
                }), r
            }
        };
        y.groupBy = k(function(t, e, n) {
            y.has(t, n) ? t[n].push(e) : t[n] = [e]
        }), y.indexBy = k(function(t, e, n) {
            t[n] = e
        }), y.countBy = k(function(t, e, n) {
            y.has(t, n) ? t[n]++ : t[n] = 1
        }), y.toArray = function(t) {
            return t ? y.isArray(t) ? u.call(t) : E(t) ? y.map(t, y.identity) : y.values(t) : []
        }, y.size = function(t) {
            return null == t ? 0 : E(t) ? t.length : y.keys(t).length
        }, y.partition = function(t, e, n) {
            e = _(e, n);
            var i = [],
                r = [];
            return y.each(t, function(t, n, s) {
                (e(t, n, s) ? i : r).push(t)
            }), [i, r]
        }, y.first = y.head = y.take = function(t, e, n) {
            if (null != t) return null == e || n ? t[0] : y.initial(t, t.length - e)
        }, y.initial = function(t, e, n) {
            return u.call(t, 0, Math.max(0, t.length - (null == e || n ? 1 : e)))
        }, y.last = function(t, e, n) {
            if (null != t) return null == e || n ? t[t.length - 1] : y.rest(t, Math.max(0, t.length - e))
        }, y.rest = y.tail = y.drop = function(t, e, n) {
            return u.call(t, null == e || n ? 1 : e)
        }, y.compact = function(t) {
            return y.filter(t, y.identity)
        };
        var T = function(t, e, n, i) {
            for (var r = [], s = 0, o = i || 0, a = D(t); o < a; o++) {
                var l = t[o];
                if (E(l) && (y.isArray(l) || y.isArguments(l))) {
                    e || (l = T(l, e, n));
                    var h = 0,
                        u = l.length;
                    for (r.length += u; h < u;) r[s++] = l[h++]
                } else n || (r[s++] = l)
            }
            return r
        };
        y.flatten = function(t, e) {
            return T(t, e, !1)
        }, y.without = function(t) {
            return y.difference(t, u.call(arguments, 1))
        }, y.uniq = y.unique = function(t, e, n, i) {
            y.isBoolean(e) || (i = n, n = e, e = !1), null != n && (n = _(n, i));
            for (var r = [], s = [], o = 0, a = D(t); o < a; o++) {
                var l = t[o],
                    h = n ? n(l, o, t) : l;
                e ? (o && s === h || r.push(l), s = h) : n ? y.contains(s, h) || (s.push(h), r.push(l)) : y.contains(r, l) || r.push(l)
            }
            return r
        }, y.union = function() {
            return y.uniq(T(arguments, !0, !0))
        }, y.intersection = function(t) {
            for (var e = [], n = arguments.length, i = 0, r = D(t); i < r; i++) {
                var s = t[i];
                if (!y.contains(e, s)) {
                    for (var o = 1; o < n && y.contains(arguments[o], s); o++);
                    o === n && e.push(s)
                }
            }
            return e
        }, y.difference = function(t) {
            var e = T(arguments, !0, !0, 1);
            return y.filter(t, function(t) {
                return !y.contains(e, t)
            })
        }, y.zip = function() {
            return y.unzip(arguments)
        }, y.unzip = function(t) {
            for (var e = t && y.max(t, D).length || 0, n = Array(e), i = 0; i < e; i++) n[i] = y.pluck(t, i);
            return n
        }, y.object = function(t, e) {
            for (var n = {}, i = 0, r = D(t); i < r; i++) e ? n[t[i]] = e[i] : n[t[i][0]] = t[i][1];
            return n
        }, y.findIndex = e(1), y.findLastIndex = e(-1), y.sortedIndex = function(t, e, n, i) {
            n = _(n, i, 1);
            for (var r = n(e), s = 0, o = D(t); s < o;) {
                var a = Math.floor((s + o) / 2);
                n(t[a]) < r ? s = a + 1 : o = a
            }
            return s
        }, y.indexOf = n(1, y.findIndex, y.sortedIndex), y.lastIndexOf = n(-1, y.findLastIndex), y.range = function(t, e, n) {
            null == e && (e = t || 0, t = 0), n = n || 1;
            for (var i = Math.max(Math.ceil((e - t) / n), 0), r = Array(i), s = 0; s < i; s++, t += n) r[s] = t;
            return r
        };
        var O = function(t, e, n, i, r) {
            if (!(i instanceof e)) return t.apply(n, r);
            var s = x(t.prototype),
                o = t.apply(s, r);
            return y.isObject(o) ? o : s
        };
        y.bind = function(t, e) {
            if (g && t.bind === g) return g.apply(t, u.call(arguments, 1));
            if (!y.isFunction(t)) throw new TypeError("Bind must be called on a function");
            var n = u.call(arguments, 2),
                i = function() {
                    return O(t, i, e, this, n.concat(u.call(arguments)))
                };
            return i
        }, y.partial = function(t) {
            var e = u.call(arguments, 1),
                n = function() {
                    for (var i = 0, r = e.length, s = Array(r), o = 0; o < r; o++) s[o] = e[o] === y ? arguments[i++] : e[o];
                    for (; i < arguments.length;) s.push(arguments[i++]);
                    return O(t, n, this, this, s)
                };
            return n
        }, y.bindAll = function(t) {
            var e, n, i = arguments.length;
            if (i <= 1) throw new Error("bindAll must be passed function names");
            for (e = 1; e < i; e++) n = arguments[e], t[n] = y.bind(t[n], t);
            return t
        }, y.memoize = function(t, e) {
            var n = function(i) {
                var r = n.cache,
                    s = "" + (e ? e.apply(this, arguments) : i);
                return y.has(r, s) || (r[s] = t.apply(this, arguments)), r[s]
            };
            return n.cache = {}, n
        }, y.delay = function(t, e) {
            var n = u.call(arguments, 2);
            return setTimeout(function() {
                return t.apply(null, n)
            }, e)
        }, y.defer = y.partial(y.delay, y, 1), y.throttle = function(t, e, n) {
            var i, r, s, o = null,
                a = 0;
            n || (n = {});
            var l = function() {
                a = n.leading === !1 ? 0 : y.now(), o = null, s = t.apply(i, r), o || (i = r = null)
            };
            return function() {
                var h = y.now();
                a || n.leading !== !1 || (a = h);
                var u = e - (h - a);
                return i = this, r = arguments, u <= 0 || u > e ? (o && (clearTimeout(o), o = null), a = h, s = t.apply(i, r), o || (i = r = null)) : o || n.trailing === !1 || (o = setTimeout(l, u)),
                    s
            }
        }, y.debounce = function(t, e, n) {
            var i, r, s, o, a, l = function() {
                var h = y.now() - o;
                h < e && h >= 0 ? i = setTimeout(l, e - h) : (i = null, n || (a = t.apply(s, r), i || (s = r = null)))
            };
            return function() {
                s = this, r = arguments, o = y.now();
                var h = n && !i;
                return i || (i = setTimeout(l, e)), h && (a = t.apply(s, r), s = r = null), a
            }
        }, y.wrap = function(t, e) {
            return y.partial(e, t)
        }, y.negate = function(t) {
            return function() {
                return !t.apply(this, arguments)
            }
        }, y.compose = function() {
            var t = arguments,
                e = t.length - 1;
            return function() {
                for (var n = e, i = t[e].apply(this, arguments); n--;) i = t[n].call(this, i);
                return i
            }
        }, y.after = function(t, e) {
            return function() {
                if (--t < 1) return e.apply(this, arguments)
            }
        }, y.before = function(t, e) {
            var n;
            return function() {
                return --t > 0 && (n = e.apply(this, arguments)), t <= 1 && (e = null), n
            }
        }, y.once = y.partial(y.before, 2);
        var M = !{
                toString: null
            }.propertyIsEnumerable("toString"),
            R = ["valueOf", "isPrototypeOf", "toString", "propertyIsEnumerable", "hasOwnProperty", "toLocaleString"];
        y.keys = function(t) {
            if (!y.isObject(t)) return [];
            if (p) return p(t);
            var e = [];
            for (var n in t) y.has(t, n) && e.push(n);
            return M && i(t, e), e
        }, y.allKeys = function(t) {
            if (!y.isObject(t)) return [];
            var e = [];
            for (var n in t) e.push(n);
            return M && i(t, e), e
        }, y.values = function(t) {
            for (var e = y.keys(t), n = e.length, i = Array(n), r = 0; r < n; r++) i[r] = t[e[r]];
            return i
        }, y.mapObject = function(t, e, n) {
            e = _(e, n);
            for (var i, r = y.keys(t), s = r.length, o = {}, a = 0; a < s; a++) i = r[a], o[i] = e(t[i], i, t);
            return o
        }, y.pairs = function(t) {
            for (var e = y.keys(t), n = e.length, i = Array(n), r = 0; r < n; r++) i[r] = [e[r], t[e[r]]];
            return i
        }, y.invert = function(t) {
            for (var e = {}, n = y.keys(t), i = 0, r = n.length; i < r; i++) e[t[n[i]]] = n[i];
            return e
        }, y.functions = y.methods = function(t) {
            var e = [];
            for (var n in t) y.isFunction(t[n]) && e.push(n);
            return e.sort()
        }, y.extend = b(y.allKeys), y.extendOwn = y.assign = b(y.keys), y.findKey = function(t, e, n) {
            e = _(e, n);
            for (var i, r = y.keys(t), s = 0, o = r.length; s < o; s++)
                if (i = r[s], e(t[i], i, t)) return i
        }, y.pick = function(t, e, n) {
            var i, r, s = {},
                o = t;
            if (null == o) return s;
            y.isFunction(e) ? (r = y.allKeys(o), i = w(e, n)) : (r = T(arguments, !1, !1, 1), i = function(t, e, n) {
                return e in n
            }, o = Object(o));
            for (var a = 0, l = r.length; a < l; a++) {
                var h = r[a],
                    u = o[h];
                i(u, h, o) && (s[h] = u)
            }
            return s
        }, y.omit = function(t, e, n) {
            if (y.isFunction(e)) e = y.negate(e);
            else {
                var i = y.map(T(arguments, !1, !1, 1), String);
                e = function(t, e) {
                    return !y.contains(i, e)
                }
            }
            return y.pick(t, e, n)
        }, y.defaults = b(y.allKeys, !0), y.create = function(t, e) {
            var n = x(t);
            return e && y.extendOwn(n, e), n
        }, y.clone = function(t) {
            return y.isObject(t) ? y.isArray(t) ? t.slice() : y.extend({}, t) : t
        }, y.tap = function(t, e) {
            return e(t), t
        }, y.isMatch = function(t, e) {
            var n = y.keys(e),
                i = n.length;
            if (null == t) return !i;
            for (var r = Object(t), s = 0; s < i; s++) {
                var o = n[s];
                if (e[o] !== r[o] || !(o in r)) return !1
            }
            return !0
        };
        var $ = function(t, e, n, i) {
            if (t === e) return 0 !== t || 1 / t === 1 / e;
            if (null == t || null == e) return t === e;
            t instanceof y && (t = t._wrapped), e instanceof y && (e = e._wrapped);
            var r = c.call(t);
            if (r !== c.call(e)) return !1;
            switch (r) {
                case "[object RegExp]":
                case "[object String]":
                    return "" + t == "" + e;
                case "[object Number]":
                    return +t !== +t ? +e !== +e : 0 === +t ? 1 / +t === 1 / e : +t === +e;
                case "[object Date]":
                case "[object Boolean]":
                    return +t === +e
            }
            var s = "[object Array]" === r;
            if (!s) {
                if ("object" != typeof t || "object" != typeof e) return !1;
                var o = t.constructor,
                    a = e.constructor;
                if (o !== a && !(y.isFunction(o) && o instanceof o && y.isFunction(a) && a instanceof a) && "constructor" in t && "constructor" in e) return !1
            }
            n = n || [], i = i || [];
            for (var l = n.length; l--;)
                if (n[l] === t) return i[l] === e;
            if (n.push(t), i.push(e), s) {
                if (l = t.length, l !== e.length) return !1;
                for (; l--;)
                    if (!$(t[l], e[l], n, i)) return !1
            } else {
                var h, u = y.keys(t);
                if (l = u.length, y.keys(e).length !== l) return !1;
                for (; l--;)
                    if (h = u[l], !y.has(e, h) || !$(t[h], e[h], n, i)) return !1
            }
            return n.pop(), i.pop(), !0
        };
        y.isEqual = function(t, e) {
            return $(t, e)
        }, y.isEmpty = function(t) {
            return null == t || (E(t) && (y.isArray(t) || y.isString(t) || y.isArguments(t)) ? 0 === t.length : 0 === y.keys(t).length)
        }, y.isElement = function(t) {
            return !(!t || 1 !== t.nodeType)
        }, y.isArray = f || function(t) {
            return "[object Array]" === c.call(t)
        }, y.isObject = function(t) {
            var e = typeof t;
            return "function" === e || "object" === e && !!t
        }, y.each(["Arguments", "Function", "String", "Number", "Date", "RegExp", "Error"], function(t) {
            y["is" + t] = function(e) {
                return c.call(e) === "[object " + t + "]"
            }
        }), y.isArguments(arguments) || (y.isArguments = function(t) {
            return y.has(t, "callee")
        }), "function" != typeof /./ && "object" != typeof Int8Array && (y.isFunction = function(t) {
            return "function" == typeof t || !1
        }), y.isFinite = function(t) {
            return isFinite(t) && !isNaN(parseFloat(t))
        }, y.isNaN = function(t) {
            return y.isNumber(t) && t !== +t
        }, y.isBoolean = function(t) {
            return t === !0 || t === !1 || "[object Boolean]" === c.call(t)
        }, y.isNull = function(t) {
            return null === t
        }, y.isUndefined = function(t) {
            return void 0 === t
        }, y.has = function(t, e) {
            return null != t && d.call(t, e)
        }, y.noConflict = function() {
            return r._ = s, this
        }, y.identity = function(t) {
            return t
        }, y.constant = function(t) {
            return function() {
                return t
            }
        }, y.noop = function() {}, y.property = C, y.propertyOf = function(t) {
            return null == t ? function() {} : function(e) {
                return t[e]
            }
        }, y.matcher = y.matches = function(t) {
            return t = y.extendOwn({}, t),
                function(e) {
                    return y.isMatch(e, t)
                }
        }, y.times = function(t, e, n) {
            var i = Array(Math.max(0, t));
            e = w(e, n, 1);
            for (var r = 0; r < t; r++) i[r] = e(r);
            return i
        }, y.random = function(t, e) {
            return null == e && (e = t, t = 0), t + Math.floor(Math.random() * (e - t + 1))
        }, y.now = Date.now || function() {
            return (new Date).getTime()
        };
        var P = {
                "&": "&amp;",
                "<": "&lt;",
                ">": "&gt;",
                '"': "&quot;",
                "'": "&#x27;",
                "`": "&#x60;"
            },
            A = y.invert(P),
            V = function(t) {
                var e = function(e) {
                        return t[e]
                    },
                    n = "(?:" + y.keys(t).join("|") + ")",
                    i = RegExp(n),
                    r = RegExp(n, "g");
                return function(t) {
                    return t = null == t ? "" : "" + t, i.test(t) ? t.replace(r, e) : t
                }
            };
        y.escape = V(P), y.unescape = V(A), y.result = function(t, e, n) {
            var i = null == t ? void 0 : t[e];
            return void 0 === i && (i = n), y.isFunction(i) ? i.call(t) : i
        };
        var N = 0;
        y.uniqueId = function(t) {
            var e = ++N + "";
            return t ? t + e : e
        }, y.templateSettings = {
            evaluate: /<%([\s\S]+?)%>/g,
            interpolate: /<%=([\s\S]+?)%>/g,
            escape: /<%-([\s\S]+?)%>/g
        };
        var I = /(.)^/,
            j = {
                "'": "'",
                "\\": "\\",
                "\r": "r",
                "\n": "n",
                "\u2028": "u2028",
                "\u2029": "u2029"
            },
            L = /\\|'|\r|\n|\u2028|\u2029/g,
            B = function(t) {
                return "\\" + j[t]
            };
        y.template = function(t, e, n) {
            !e && n && (e = n), e = y.defaults({}, e, y.templateSettings);
            var i = RegExp([(e.escape || I).source, (e.interpolate || I).source, (e.evaluate || I).source].join("|") + "|$", "g"),
                r = 0,
                s = "__p+='";
            t.replace(i, function(e, n, i, o, a) {
                return s += t.slice(r, a).replace(L, B), r = a + e.length, n ? s += "'+\n((__t=(" + n + "))==null?'':_.escape(__t))+\n'" : i ? s += "'+\n((__t=(" + i + "))==null?'':__t)+\n'" : o && (s += "';\n" + o + "\n__p+='"), e
            }), s += "';\n", e.variable || (s = "with(obj||{}){\n" + s + "}\n"), s = "var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};\n" + s + "return __p;\n";
            try {
                var o = new Function(e.variable || "obj", "_", s)
            } catch (a) {
                throw a.source = s, a
            }
            var l = function(t) {
                    return o.call(this, t, y)
                },
                h = e.variable || "obj";
            return l.source = "function(" + h + "){\n" + s + "}", l
        }, y.chain = function(t) {
            var e = y(t);
            return e._chain = !0, e
        };
        var H = function(t, e) {
            return t._chain ? y(e).chain() : e
        };
        y.mixin = function(t) {
            y.each(y.functions(t), function(e) {
                var n = y[e] = t[e];
                y.prototype[e] = function() {
                    var t = [this._wrapped];
                    return h.apply(t, arguments), H(this, n.apply(y, t))
                }
            })
        }, y.mixin(y), y.each(["pop", "push", "reverse", "shift", "sort", "splice", "unshift"], function(t) {
            var e = o[t];
            y.prototype[t] = function() {
                var n = this._wrapped;
                return e.apply(n, arguments), "shift" !== t && "splice" !== t || 0 !== n.length || delete n[0], H(this, n)
            }
        }), y.each(["concat", "join", "slice"], function(t) {
            var e = o[t];
            y.prototype[t] = function() {
                return H(this, e.apply(this._wrapped, arguments))
            }
        }), y.prototype.value = function() {
            return this._wrapped
        }, y.prototype.valueOf = y.prototype.toJSON = y.prototype.value, y.prototype.toString = function() {
            return "" + this._wrapped
        }, "function" == typeof define && define.amd && define("underscore", [], function() {
            return y
        })
    }.call(this), function(t) {
        var e = "object" == typeof self && self.self === self && self || "object" == typeof global && global.global === global && global;
        if ("function" == typeof define && define.amd) define(["underscore", "jquery", "exports"], function(n, i, r) {
            e.Backbone = t(e, r, n, i)
        });
        else if ("undefined" != typeof exports) {
            var n, i = require("underscore");
            try {
                n = require("jquery")
            } catch (r) {}
            t(e, exports, i, n)
        } else e.Backbone = t(e, {}, e._, e.jQuery || e.Zepto || e.ender || e.$)
    }(function(t, e, n, i) {
        var r = t.Backbone,
            s = Array.prototype.slice;
        e.VERSION = "1.3.3", e.$ = i, e.noConflict = function() {
            return t.Backbone = r, this
        }, e.emulateHTTP = !1, e.emulateJSON = !1;
        var o = function(t, e, i) {
                switch (t) {
                    case 1:
                        return function() {
                            return n[e](this[i])
                        };
                    case 2:
                        return function(t) {
                            return n[e](this[i], t)
                        };
                    case 3:
                        return function(t, r) {
                            return n[e](this[i], l(t, this), r)
                        };
                    case 4:
                        return function(t, r, s) {
                            return n[e](this[i], l(t, this), r, s)
                        };
                    default:
                        return function() {
                            var t = s.call(arguments);
                            return t.unshift(this[i]), n[e].apply(n, t)
                        }
                }
            },
            a = function(t, e, i) {
                n.each(e, function(e, r) {
                    n[r] && (t.prototype[r] = o(e, r, i))
                })
            },
            l = function(t, e) {
                return n.isFunction(t) ? t : n.isObject(t) && !e._isModel(t) ? h(t) : n.isString(t) ? function(e) {
                    return e.get(t)
                } : t
            },
            h = function(t) {
                var e = n.matches(t);
                return function(t) {
                    return e(t.attributes)
                }
            },
            u = e.Events = {},
            c = /\s+/,
            d = function(t, e, i, r, s) {
                var o, a = 0;
                if (i && "object" == typeof i) {
                    void 0 !== r && "context" in s && void 0 === s.context && (s.context = r);
                    for (o = n.keys(i); a < o.length; a++) e = d(t, e, o[a], i[o[a]], s)
                } else if (i && c.test(i))
                    for (o = i.split(c); a < o.length; a++) e = t(e, o[a], r, s);
                else e = t(e, i, r, s);
                return e
            };
        u.on = function(t, e, n) {
            return f(this, t, e, n)
        };
        var f = function(t, e, n, i, r) {
            if (t._events = d(p, t._events || {}, e, n, {
                    context: i,
                    ctx: t,
                    listening: r
                }), r) {
                var s = t._listeners || (t._listeners = {});
                s[r.id] = r
            }
            return t
        };
        u.listenTo = function(t, e, i) {
            if (!t) return this;
            var r = t._listenId || (t._listenId = n.uniqueId("l")),
                s = this._listeningTo || (this._listeningTo = {}),
                o = s[r];
            if (!o) {
                var a = this._listenId || (this._listenId = n.uniqueId("l"));
                o = s[r] = {
                    obj: t,
                    objId: r,
                    id: a,
                    listeningTo: s,
                    count: 0
                }
            }
            return f(t, e, i, this, o), this
        };
        var p = function(t, e, n, i) {
            if (n) {
                var r = t[e] || (t[e] = []),
                    s = i.context,
                    o = i.ctx,
                    a = i.listening;
                a && a.count++, r.push({
                    callback: n,
                    context: s,
                    ctx: s || o,
                    listening: a
                })
            }
            return t
        };
        u.off = function(t, e, n) {
            return this._events ? (this._events = d(g, this._events, t, e, {
                context: n,
                listeners: this._listeners
            }), this) : this
        }, u.stopListening = function(t, e, i) {
            var r = this._listeningTo;
            if (!r) return this;
            for (var s = t ? [t._listenId] : n.keys(r), o = 0; o < s.length; o++) {
                var a = r[s[o]];
                if (!a) break;
                a.obj.off(e, i, this)
            }
            return this
        };
        var g = function(t, e, i, r) {
            if (t) {
                var s, o = 0,
                    a = r.context,
                    l = r.listeners;
                if (e || i || a) {
                    for (var h = e ? [e] : n.keys(t); o < h.length; o++) {
                        e = h[o];
                        var u = t[e];
                        if (!u) break;
                        for (var c = [], d = 0; d < u.length; d++) {
                            var f = u[d];
                            i && i !== f.callback && i !== f.callback._callback || a && a !== f.context ? c.push(f) : (s = f.listening, s && 0 === --s.count && (delete l[s.id], delete s.listeningTo[s.objId]))
                        }
                        c.length ? t[e] = c : delete t[e]
                    }
                    return t
                }
                for (var p = n.keys(l); o < p.length; o++) s = l[p[o]], delete l[s.id], delete s.listeningTo[s.objId]
            }
        };
        u.once = function(t, e, i) {
            var r = d(m, {}, t, e, n.bind(this.off, this));
            return "string" == typeof t && null == i && (e = void 0), this.on(r, e, i)
        }, u.listenToOnce = function(t, e, i) {
            var r = d(m, {}, e, i, n.bind(this.stopListening, this, t));
            return this.listenTo(t, r)
        };
        var m = function(t, e, i, r) {
            if (i) {
                var s = t[e] = n.once(function() {
                    r(e, s), i.apply(this, arguments)
                });
                s._callback = i
            }
            return t
        };
        u.trigger = function(t) {
            if (!this._events) return this;
            for (var e = Math.max(0, arguments.length - 1), n = Array(e), i = 0; i < e; i++) n[i] = arguments[i + 1];
            return d(v, this._events, t, void 0, n), this
        };
        var v = function(t, e, n, i) {
                if (t) {
                    var r = t[e],
                        s = t.all;
                    r && s && (s = s.slice()), r && y(r, i), s && y(s, [e].concat(i))
                }
                return t
            },
            y = function(t, e) {
                var n, i = -1,
                    r = t.length,
                    s = e[0],
                    o = e[1],
                    a = e[2];
                switch (e.length) {
                    case 0:
                        for (; ++i < r;)(n = t[i]).callback.call(n.ctx);
                        return;
                    case 1:
                        for (; ++i < r;)(n = t[i]).callback.call(n.ctx, s);
                        return;
                    case 2:
                        for (; ++i < r;)(n = t[i]).callback.call(n.ctx, s, o);
                        return;
                    case 3:
                        for (; ++i < r;)(n = t[i]).callback.call(n.ctx, s, o, a);
                        return;
                    default:
                        for (; ++i < r;)(n = t[i]).callback.apply(n.ctx, e);
                        return
                }
            };
        u.bind = u.on, u.unbind = u.off, n.extend(e, u);
        var w = e.Model = function(t, e) {
            var i = t || {};
            e || (e = {}), this.cid = n.uniqueId(this.cidPrefix), this.attributes = {}, e.collection && (this.collection = e.collection), e.parse && (i = this.parse(i, e) || {});
            var r = n.result(this, "defaults");
            i = n.defaults(n.extend({}, r, i), r), this.set(i, e), this.changed = {}, this.initialize.apply(this, arguments)
        };
        n.extend(w.prototype, u, {
            changed: null,
            validationError: null,
            idAttribute: "id",
            cidPrefix: "c",
            initialize: function() {},
            toJSON: function(t) {
                return n.clone(this.attributes)
            },
            sync: function() {
                return e.sync.apply(this, arguments)
            },
            get: function(t) {
                return this.attributes[t]
            },
            escape: function(t) {
                return n.escape(this.get(t))
            },
            has: function(t) {
                return null != this.get(t)
            },
            matches: function(t) {
                return !!n.iteratee(t, this)(this.attributes)
            },
            set: function(t, e, i) {
                if (null == t) return this;
                var r;
                if ("object" == typeof t ? (r = t, i = e) : (r = {})[t] = e, i || (i = {}), !this._validate(r, i)) return !1;
                var s = i.unset,
                    o = i.silent,
                    a = [],
                    l = this._changing;
                this._changing = !0, l || (this._previousAttributes = n.clone(this.attributes), this.changed = {});
                var h = this.attributes,
                    u = this.changed,
                    c = this._previousAttributes;
                for (var d in r) e = r[d], n.isEqual(h[d], e) || a.push(d), n.isEqual(c[d], e) ? delete u[d] : u[d] = e, s ? delete h[d] : h[d] = e;
                if (this.idAttribute in r && (this.id = this.get(this.idAttribute)), !o) {
                    a.length && (this._pending = i);
                    for (var f = 0; f < a.length; f++) this.trigger("change:" + a[f], this, h[a[f]], i)
                }
                if (l) return this;
                if (!o)
                    for (; this._pending;) i = this._pending, this._pending = !1, this.trigger("change", this, i);
                return this._pending = !1, this._changing = !1, this
            },
            unset: function(t, e) {
                return this.set(t, void 0, n.extend({}, e, {
                    unset: !0
                }))
            },
            clear: function(t) {
                var e = {};
                for (var i in this.attributes) e[i] = void 0;
                return this.set(e, n.extend({}, t, {
                    unset: !0
                }))
            },
            hasChanged: function(t) {
                return null == t ? !n.isEmpty(this.changed) : n.has(this.changed, t)
            },
            changedAttributes: function(t) {
                if (!t) return !!this.hasChanged() && n.clone(this.changed);
                var e = this._changing ? this._previousAttributes : this.attributes,
                    i = {};
                for (var r in t) {
                    var s = t[r];
                    n.isEqual(e[r], s) || (i[r] = s)
                }
                return !!n.size(i) && i
            },
            previous: function(t) {
                return null != t && this._previousAttributes ? this._previousAttributes[t] : null
            },
            previousAttributes: function() {
                return n.clone(this._previousAttributes)
            },
            fetch: function(t) {
                t = n.extend({
                    parse: !0
                }, t);
                var e = this,
                    i = t.success;
                return t.success = function(n) {
                    var r = t.parse ? e.parse(n, t) : n;
                    return !!e.set(r, t) && (i && i.call(t.context, e, n, t), void e.trigger("sync", e, n, t))
                }, H(this, t), this.sync("read", this, t)
            },
            save: function(t, e, i) {
                var r;
                null == t || "object" == typeof t ? (r = t, i = e) : (r = {})[t] = e, i = n.extend({
                    validate: !0,
                    parse: !0
                }, i);
                var s = i.wait;
                if (r && !s) {
                    if (!this.set(r, i)) return !1
                } else if (!this._validate(r, i)) return !1;
                var o = this,
                    a = i.success,
                    l = this.attributes;
                i.success = function(t) {
                    o.attributes = l;
                    var e = i.parse ? o.parse(t, i) : t;
                    return s && (e = n.extend({}, r, e)), !(e && !o.set(e, i)) && (a && a.call(i.context, o, t, i), void o.trigger("sync", o, t, i))
                }, H(this, i), r && s && (this.attributes = n.extend({}, l, r));
                var h = this.isNew() ? "create" : i.patch ? "patch" : "update";
                "patch" !== h || i.attrs || (i.attrs = r);
                var u = this.sync(h, this, i);
                return this.attributes = l, u
            },
            destroy: function(t) {
                t = t ? n.clone(t) : {};
                var e = this,
                    i = t.success,
                    r = t.wait,
                    s = function() {
                        e.stopListening(), e.trigger("destroy", e, e.collection, t)
                    };
                t.success = function(n) {
                    r && s(), i && i.call(t.context, e, n, t), e.isNew() || e.trigger("sync", e, n, t)
                };
                var o = !1;
                return this.isNew() ? n.defer(t.success) : (H(this, t), o = this.sync("delete", this, t)), r || s(), o
            },
            url: function() {
                var t = n.result(this, "urlRoot") || n.result(this.collection, "url") || B();
                if (this.isNew()) return t;
                var e = this.get(this.idAttribute);
                return t.replace(/[^\/]$/, "$&/") + encodeURIComponent(e)
            },
            parse: function(t, e) {
                return t
            },
            clone: function() {
                return new this.constructor(this.attributes)
            },
            isNew: function() {
                return !this.has(this.idAttribute)
            },
            isValid: function(t) {
                return this._validate({}, n.extend({}, t, {
                    validate: !0
                }))
            },
            _validate: function(t, e) {
                if (!e.validate || !this.validate) return !0;
                t = n.extend({}, this.attributes, t);
                var i = this.validationError = this.validate(t, e) || null;
                return !i || (this.trigger("invalid", this, i, n.extend(e, {
                    validationError: i
                })), !1)
            }
        });
        var _ = {
            keys: 1,
            values: 1,
            pairs: 1,
            invert: 1,
            pick: 0,
            omit: 0,
            chain: 1,
            isEmpty: 1
        };
        a(w, _, "attributes");
        var b = e.Collection = function(t, e) {
                e || (e = {}), e.model && (this.model = e.model), void 0 !== e.comparator && (this.comparator = e.comparator), this._reset(), this.initialize.apply(this, arguments), t && this.reset(t, n.extend({
                    silent: !0
                }, e))
            },
            x = {
                add: !0,
                remove: !0,
                merge: !0
            },
            C = {
                add: !0,
                remove: !1
            },
            S = function(t, e, n) {
                n = Math.min(Math.max(n, 0), t.length);
                var i, r = Array(t.length - n),
                    s = e.length;
                for (i = 0; i < r.length; i++) r[i] = t[i + n];
                for (i = 0; i < s; i++) t[i + n] = e[i];
                for (i = 0; i < r.length; i++) t[i + s + n] = r[i]
            };
        n.extend(b.prototype, u, {
            model: w,
            initialize: function() {},
            toJSON: function(t) {
                return this.map(function(e) {
                    return e.toJSON(t)
                })
            },
            sync: function() {
                return e.sync.apply(this, arguments)
            },
            add: function(t, e) {
                return this.set(t, n.extend({
                    merge: !1
                }, e, C))
            },
            remove: function(t, e) {
                e = n.extend({}, e);
                var i = !n.isArray(t);
                t = i ? [t] : t.slice();
                var r = this._removeModels(t, e);
                return !e.silent && r.length && (e.changes = {
                    added: [],
                    merged: [],
                    removed: r
                }, this.trigger("update", this, e)), i ? r[0] : r
            },
            set: function(t, e) {
                if (null != t) {
                    e = n.extend({}, x, e), e.parse && !this._isModel(t) && (t = this.parse(t, e) || []);
                    var i = !n.isArray(t);
                    t = i ? [t] : t.slice();
                    var r = e.at;
                    null != r && (r = +r), r > this.length && (r = this.length), r < 0 && (r += this.length + 1);
                    var s, o, a = [],
                        l = [],
                        h = [],
                        u = [],
                        c = {},
                        d = e.add,
                        f = e.merge,
                        p = e.remove,
                        g = !1,
                        m = this.comparator && null == r && e.sort !== !1,
                        v = n.isString(this.comparator) ? this.comparator : null;
                    for (o = 0; o < t.length; o++) {
                        s = t[o];
                        var y = this.get(s);
                        if (y) {
                            if (f && s !== y) {
                                var w = this._isModel(s) ? s.attributes : s;
                                e.parse && (w = y.parse(w, e)), y.set(w, e), h.push(y), m && !g && (g = y.hasChanged(v))
                            }
                            c[y.cid] || (c[y.cid] = !0, a.push(y)), t[o] = y
                        } else d && (s = t[o] = this._prepareModel(s, e), s && (l.push(s), this._addReference(s, e), c[s.cid] = !0, a.push(s)))
                    }
                    if (p) {
                        for (o = 0; o < this.length; o++) s = this.models[o], c[s.cid] || u.push(s);
                        u.length && this._removeModels(u, e)
                    }
                    var _ = !1,
                        b = !m && d && p;
                    if (a.length && b ? (_ = this.length !== a.length || n.some(this.models, function(t, e) {
                            return t !== a[e]
                        }), this.models.length = 0, S(this.models, a, 0), this.length = this.models.length) : l.length && (m && (g = !0), S(this.models, l, null == r ? this.length : r), this.length = this.models.length), g && this.sort({
                            silent: !0
                        }), !e.silent) {
                        for (o = 0; o < l.length; o++) null != r && (e.index = r + o), s = l[o], s.trigger("add", s, this, e);
                        (g || _) && this.trigger("sort", this, e), (l.length || u.length || h.length) && (e.changes = {
                            added: l,
                            removed: u,
                            merged: h
                        }, this.trigger("update", this, e))
                    }
                    return i ? t[0] : t
                }
            },
            reset: function(t, e) {
                e = e ? n.clone(e) : {};
                for (var i = 0; i < this.models.length; i++) this._removeReference(this.models[i], e);
                return e.previousModels = this.models, this._reset(), t = this.add(t, n.extend({
                    silent: !0
                }, e)), e.silent || this.trigger("reset", this, e), t
            },
            push: function(t, e) {
                return this.add(t, n.extend({
                    at: this.length
                }, e))
            },
            pop: function(t) {
                var e = this.at(this.length - 1);
                return this.remove(e, t)
            },
            unshift: function(t, e) {
                return this.add(t, n.extend({
                    at: 0
                }, e))
            },
            shift: function(t) {
                var e = this.at(0);
                return this.remove(e, t)
            },
            slice: function() {
                return s.apply(this.models, arguments)
            },
            get: function(t) {
                if (null != t) return this._byId[t] || this._byId[this.modelId(t.attributes || t)] || t.cid && this._byId[t.cid]
            },
            has: function(t) {
                return null != this.get(t)
            },
            at: function(t) {
                return t < 0 && (t += this.length), this.models[t]
            },
            where: function(t, e) {
                return this[e ? "find" : "filter"](t)
            },
            findWhere: function(t) {
                return this.where(t, !0)
            },
            sort: function(t) {
                var e = this.comparator;
                if (!e) throw new Error("Cannot sort a set without a comparator");
                t || (t = {});
                var i = e.length;
                return n.isFunction(e) && (e = n.bind(e, this)), 1 === i || n.isString(e) ? this.models = this.sortBy(e) : this.models.sort(e), t.silent || this.trigger("sort", this, t), this
            },
            pluck: function(t) {
                return this.map(t + "")
            },
            fetch: function(t) {
                t = n.extend({
                    parse: !0
                }, t);
                var e = t.success,
                    i = this;
                return t.success = function(n) {
                    var r = t.reset ? "reset" : "set";
                    i[r](n, t), e && e.call(t.context, i, n, t), i.trigger("sync", i, n, t)
                }, H(this, t), this.sync("read", this, t)
            },
            create: function(t, e) {
                e = e ? n.clone(e) : {};
                var i = e.wait;
                if (t = this._prepareModel(t, e), !t) return !1;
                i || this.add(t, e);
                var r = this,
                    s = e.success;
                return e.success = function(t, e, n) {
                    i && r.add(t, n), s && s.call(n.context, t, e, n)
                }, t.save(null, e), t
            },
            parse: function(t, e) {
                return t
            },
            clone: function() {
                return new this.constructor(this.models, {
                    model: this.model,
                    comparator: this.comparator
                })
            },
            modelId: function(t) {
                return t[this.model.prototype.idAttribute || "id"]
            },
            _reset: function() {
                this.length = 0, this.models = [], this._byId = {}
            },
            _prepareModel: function(t, e) {
                if (this._isModel(t)) return t.collection || (t.collection = this), t;
                e = e ? n.clone(e) : {}, e.collection = this;
                var i = new this.model(t, e);
                return i.validationError ? (this.trigger("invalid", this, i.validationError, e), !1) : i
            },
            _removeModels: function(t, e) {
                for (var n = [], i = 0; i < t.length; i++) {
                    var r = this.get(t[i]);
                    if (r) {
                        var s = this.indexOf(r);
                        this.models.splice(s, 1), this.length--, delete this._byId[r.cid];
                        var o = this.modelId(r.attributes);
                        null != o && delete this._byId[o], e.silent || (e.index = s, r.trigger("remove", r, this, e)), n.push(r), this._removeReference(r, e)
                    }
                }
                return n
            },
            _isModel: function(t) {
                return t instanceof w
            },
            _addReference: function(t, e) {
                this._byId[t.cid] = t;
                var n = this.modelId(t.attributes);
                null != n && (this._byId[n] = t), t.on("all", this._onModelEvent, this)
            },
            _removeReference: function(t, e) {
                delete this._byId[t.cid];
                var n = this.modelId(t.attributes);
                null != n && delete this._byId[n], this === t.collection && delete t.collection, t.off("all", this._onModelEvent, this)
            },
            _onModelEvent: function(t, e, n, i) {
                if (e) {
                    if (("add" === t || "remove" === t) && n !== this) return;
                    if ("destroy" === t && this.remove(e, i), "change" === t) {
                        var r = this.modelId(e.previousAttributes()),
                            s = this.modelId(e.attributes);
                        r !== s && (null != r && delete this._byId[r], null != s && (this._byId[s] = e))
                    }
                }
                this.trigger.apply(this, arguments)
            }
        });
        var D = {
            forEach: 3,
            each: 3,
            map: 3,
            collect: 3,
            reduce: 0,
            foldl: 0,
            inject: 0,
            reduceRight: 0,
            foldr: 0,
            find: 3,
            detect: 3,
            filter: 3,
            select: 3,
            reject: 3,
            every: 3,
            all: 3,
            some: 3,
            any: 3,
            include: 3,
            includes: 3,
            contains: 3,
            invoke: 0,
            max: 3,
            min: 3,
            toArray: 1,
            size: 1,
            first: 3,
            head: 3,
            take: 3,
            initial: 3,
            rest: 3,
            tail: 3,
            drop: 3,
            last: 3,
            without: 0,
            difference: 0,
            indexOf: 3,
            shuffle: 1,
            lastIndexOf: 3,
            isEmpty: 1,
            chain: 1,
            sample: 3,
            partition: 3,
            groupBy: 3,
            countBy: 3,
            sortBy: 3,
            indexBy: 3,
            findIndex: 3,
            findLastIndex: 3
        };
        a(b, D, "models");
        var E = e.View = function(t) {
                this.cid = n.uniqueId("view"), n.extend(this, n.pick(t, T)), this._ensureElement(), this.initialize.apply(this, arguments)
            },
            k = /^(\S+)\s*(.*)$/,
            T = ["model", "collection", "el", "id", "attributes", "className", "tagName", "events"];
        n.extend(E.prototype, u, {
            tagName: "div",
            $: function(t) {
                return this.$el.find(t)
            },
            initialize: function() {},
            render: function() {
                return this
            },
            remove: function() {
                return this._removeElement(), this.stopListening(), this
            },
            _removeElement: function() {
                this.$el.remove()
            },
            setElement: function(t) {
                return this.undelegateEvents(), this._setElement(t), this.delegateEvents(), this
            },
            _setElement: function(t) {
                this.$el = t instanceof e.$ ? t : e.$(t), this.el = this.$el[0]
            },
            delegateEvents: function(t) {
                if (t || (t = n.result(this, "events")), !t) return this;
                this.undelegateEvents();
                for (var e in t) {
                    var i = t[e];
                    if (n.isFunction(i) || (i = this[i]), i) {
                        var r = e.match(k);
                        this.delegate(r[1], r[2], n.bind(i, this))
                    }
                }
                return this
            },
            delegate: function(t, e, n) {
                return this.$el.on(t + ".delegateEvents" + this.cid, e, n), this
            },
            undelegateEvents: function() {
                return this.$el && this.$el.off(".delegateEvents" + this.cid), this
            },
            undelegate: function(t, e, n) {
                return this.$el.off(t + ".delegateEvents" + this.cid, e, n), this
            },
            _createElement: function(t) {
                return document.createElement(t)
            },
            _ensureElement: function() {
                if (this.el) this.setElement(n.result(this, "el"));
                else {
                    var t = n.extend({}, n.result(this, "attributes"));
                    this.id && (t.id = n.result(this, "id")), this.className && (t["class"] = n.result(this, "className")), this.setElement(this._createElement(n.result(this, "tagName"))), this._setAttributes(t)
                }
            },
            _setAttributes: function(t) {
                this.$el.attr(t)
            }
        }), e.sync = function(t, i, r) {
            var s = O[t];
            n.defaults(r || (r = {}), {
                emulateHTTP: e.emulateHTTP,
                emulateJSON: e.emulateJSON
            });
            var o = {
                type: s,
                dataType: "json"
            };
            if (r.url || (o.url = n.result(i, "url") || B()), null != r.data || !i || "create" !== t && "update" !== t && "patch" !== t || (o.contentType = "application/json", o.data = JSON.stringify(r.attrs || i.toJSON(r))), r.emulateJSON && (o.contentType = "application/x-www-form-urlencoded", o.data = o.data ? {
                    model: o.data
                } : {}), r.emulateHTTP && ("PUT" === s || "DELETE" === s || "PATCH" === s)) {
                o.type = "POST", r.emulateJSON && (o.data._method = s);
                var a = r.beforeSend;
                r.beforeSend = function(t) {
                    if (t.setRequestHeader("X-HTTP-Method-Override", s), a) return a.apply(this, arguments)
                }
            }
            "GET" === o.type || r.emulateJSON || (o.processData = !1);
            var l = r.error;
            r.error = function(t, e, n) {
                r.textStatus = e, r.errorThrown = n, l && l.call(r.context, t, e, n)
            };
            var h = r.xhr = e.ajax(n.extend(o, r));
            return i.trigger("request", i, h, r), h
        };
        var O = {
            create: "POST",
            update: "PUT",
            patch: "PATCH",
            "delete": "DELETE",
            read: "GET"
        };
        e.ajax = function() {
            return e.$.ajax.apply(e.$, arguments)
        };
        var M = e.Router = function(t) {
                t || (t = {}), t.routes && (this.routes = t.routes), this._bindRoutes(), this.initialize.apply(this, arguments)
            },
            R = /\((.*?)\)/g,
            $ = /(\(\?)?:\w+/g,
            P = /\*\w+/g,
            A = /[\-{}\[\]+?.,\\\^$|#\s]/g;
        n.extend(M.prototype, u, {
            initialize: function() {},
            route: function(t, i, r) {
                n.isRegExp(t) || (t = this._routeToRegExp(t)), n.isFunction(i) && (r = i, i = ""), r || (r = this[i]);
                var s = this;
                return e.history.route(t, function(n) {
                    var o = s._extractParameters(t, n);
                    s.execute(r, o, i) !== !1 && (s.trigger.apply(s, ["route:" + i].concat(o)), s.trigger("route", i, o), e.history.trigger("route", s, i, o))
                }), this
            },
            execute: function(t, e, n) {
                t && t.apply(this, e)
            },
            navigate: function(t, n) {
                return e.history.navigate(t, n), this
            },
            _bindRoutes: function() {
                if (this.routes) {
                    this.routes = n.result(this, "routes");
                    for (var t, e = n.keys(this.routes); null != (t = e.pop());) this.route(t, this.routes[t])
                }
            },
            _routeToRegExp: function(t) {
                return t = t.replace(A, "\\$&").replace(R, "(?:$1)?").replace($, function(t, e) {
                    return e ? t : "([^/?]+)"
                }).replace(P, "([^?]*?)"), new RegExp("^" + t + "(?:\\?([\\s\\S]*))?$")
            },
            _extractParameters: function(t, e) {
                var i = t.exec(e).slice(1);
                return n.map(i, function(t, e) {
                    return e === i.length - 1 ? t || null : t ? decodeURIComponent(t) : null
                })
            }
        });
        var V = e.History = function() {
                this.handlers = [], this.checkUrl = n.bind(this.checkUrl, this), "undefined" != typeof window && (this.location = window.location, this.history = window.history)
            },
            N = /^[#\/]|\s+$/g,
            I = /^\/+|\/+$/g,
            j = /#.*$/;
        V.started = !1, n.extend(V.prototype, u, {
            interval: 50,
            atRoot: function() {
                var t = this.location.pathname.replace(/[^\/]$/, "$&/");
                return t === this.root && !this.getSearch()
            },
            matchRoot: function() {
                var t = this.decodeFragment(this.location.pathname),
                    e = t.slice(0, this.root.length - 1) + "/";
                return e === this.root
            },
            decodeFragment: function(t) {
                return decodeURI(t.replace(/%25/g, "%2525"))
            },
            getSearch: function() {
                var t = this.location.href.replace(/#.*/, "").match(/\?.+/);
                return t ? t[0] : ""
            },
            getHash: function(t) {
                var e = (t || this).location.href.match(/#(.*)$/);
                return e ? e[1] : ""
            },
            getPath: function() {
                var t = this.decodeFragment(this.location.pathname + this.getSearch()).slice(this.root.length - 1);
                return "/" === t.charAt(0) ? t.slice(1) : t
            },
            getFragment: function(t) {
                return null == t && (t = this._usePushState || !this._wantsHashChange ? this.getPath() : this.getHash()), t.replace(N, "")
            },
            start: function(t) {
                if (V.started) throw new Error("Backbone.history has already been started");
                if (V.started = !0, this.options = n.extend({
                        root: "/"
                    }, this.options, t), this.root = this.options.root, this._wantsHashChange = this.options.hashChange !== !1, this._hasHashChange = "onhashchange" in window && (void 0 === document.documentMode || document.documentMode > 7), this._useHashChange = this._wantsHashChange && this._hasHashChange, this._wantsPushState = !!this.options.pushState, this._hasPushState = !(!this.history || !this.history.pushState), this._usePushState = this._wantsPushState && this._hasPushState, this.fragment = this.getFragment(), this.root = ("/" + this.root + "/").replace(I, "/"), this._wantsHashChange && this._wantsPushState) {
                    if (!this._hasPushState && !this.atRoot()) {
                        var e = this.root.slice(0, -1) || "/";
                        return this.location.replace(e + "#" + this.getPath()), !0
                    }
                    this._hasPushState && this.atRoot() && this.navigate(this.getHash(), {
                        replace: !0
                    })
                }
                if (!this._hasHashChange && this._wantsHashChange && !this._usePushState) {
                    this.iframe = document.createElement("iframe"), this.iframe.src = "javascript:0", this.iframe.style.display = "none", this.iframe.tabIndex = -1;
                    var i = document.body,
                        r = i.insertBefore(this.iframe, i.firstChild).contentWindow;
                    r.document.open(), r.document.close(), r.location.hash = "#" + this.fragment
                }
                var s = window.addEventListener || function(t, e) {
                    return attachEvent("on" + t, e)
                };
                if (this._usePushState ? s("popstate", this.checkUrl, !1) : this._useHashChange && !this.iframe ? s("hashchange", this.checkUrl, !1) : this._wantsHashChange && (this._checkUrlInterval = setInterval(this.checkUrl, this.interval)), !this.options.silent) return this.loadUrl()
            },
            stop: function() {
                var t = window.removeEventListener || function(t, e) {
                    return detachEvent("on" + t, e)
                };
                this._usePushState ? t("popstate", this.checkUrl, !1) : this._useHashChange && !this.iframe && t("hashchange", this.checkUrl, !1), this.iframe && (document.body.removeChild(this.iframe), this.iframe = null), this._checkUrlInterval && clearInterval(this._checkUrlInterval), V.started = !1
            },
            route: function(t, e) {
                this.handlers.unshift({
                    route: t,
                    callback: e
                })
            },
            checkUrl: function(t) {
                var e = this.getFragment();
                return e === this.fragment && this.iframe && (e = this.getHash(this.iframe.contentWindow)), e !== this.fragment && (this.iframe && this.navigate(e), void this.loadUrl())
            },
            loadUrl: function(t) {
                return !!this.matchRoot() && (t = this.fragment = this.getFragment(t), n.some(this.handlers, function(e) {
                    if (e.route.test(t)) return e.callback(t), !0
                }))
            },
            navigate: function(t, e) {
                if (!V.started) return !1;
                e && e !== !0 || (e = {
                    trigger: !!e
                }), t = this.getFragment(t || "");
                var n = this.root;
                "" !== t && "?" !== t.charAt(0) || (n = n.slice(0, -1) || "/");
                var i = n + t;
                if (t = this.decodeFragment(t.replace(j, "")), this.fragment !== t) {
                    if (this.fragment = t, this._usePushState) this.history[e.replace ? "replaceState" : "pushState"]({}, document.title, i);
                    else {
                        if (!this._wantsHashChange) return this.location.assign(i);
                        if (this._updateHash(this.location, t, e.replace), this.iframe && t !== this.getHash(this.iframe.contentWindow)) {
                            var r = this.iframe.contentWindow;
                            e.replace || (r.document.open(), r.document.close()), this._updateHash(r.location, t, e.replace)
                        }
                    }
                    return e.trigger ? this.loadUrl(t) : void 0
                }
            },
            _updateHash: function(t, e, n) {
                if (n) {
                    var i = t.href.replace(/(javascript:|#).*$/, "");
                    t.replace(i + "#" + e)
                } else t.hash = "#" + e
            }
        }), e.history = new V;
        var L = function(t, e) {
            var i, r = this;
            return i = t && n.has(t, "constructor") ? t.constructor : function() {
                return r.apply(this, arguments)
            }, n.extend(i, r, e), i.prototype = n.create(r.prototype, t), i.prototype.constructor = i, i.__super__ = r.prototype, i
        };
        w.extend = b.extend = M.extend = E.extend = V.extend = L;
        var B = function() {
                throw new Error('A "url" property or function must be specified')
            },
            H = function(t, e) {
                var n = e.error;
                e.error = function(i) {
                    n && n.call(e.context, t, i, e), t.trigger("error", t, i, e)
                }
            };
        return e
    }), function(t, e) {
        "object" == typeof exports && "undefined" != typeof module ? module.exports = e(require("underscore"), require("backbone")) : "function" == typeof define && define.amd ? define(["underscore", "backbone"], e) : (t.Backbone = t.Backbone || {}, t.Backbone.Radio = e(t._, t.Backbone))
    }(this, function(t, e) {
        "use strict";

        function n(t, e, n, i) {
            var r = t[e];
            if (!(n && n !== r.callback && n !== r.callback._callback || i && i !== r.context)) return delete t[e], !0
        }

        function i(e, i, r, s) {
            e || (e = {});
            for (var o = i ? [i] : t.keys(e), a = !1, l = 0, h = o.length; l < h; l++) i = o[l], e[i] && n(e, i, r, s) && (a = !0);
            return a
        }

        function r(e) {
            return u[e] || (u[e] = t.bind(l.log, l, e))
        }

        function s(e) {
            return t.isFunction(e) ? e : function() {
                return e
            }
        }
        t = "default" in t ? t["default"] : t, e = "default" in e ? e["default"] : e;
        var o = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(t) {
                return typeof t
            } : function(t) {
                return t && "function" == typeof Symbol && t.constructor === Symbol ? "symbol" : typeof t
            },
            a = e.Radio,
            l = e.Radio = {};
        l.VERSION = "2.0.0", l.noConflict = function() {
            return e.Radio = a, this
        }, l.DEBUG = !1, l._debugText = function(t, e, n) {
            return t + (n ? " on the " + n + " channel" : "") + ': "' + e + '"'
        }, l.debugLog = function(t, e, n) {
            l.DEBUG && console && console.warn && console.warn(l._debugText(t, e, n))
        };
        var h = /\s+/;
        l._eventsApi = function(e, n, i, r) {
            if (!i) return !1;
            var s = {};
            if ("object" === ("undefined" == typeof i ? "undefined" : o(i))) {
                for (var a in i) {
                    var l = e[n].apply(e, [a, i[a]].concat(r));
                    h.test(a) ? t.extend(s, l) : s[a] = l
                }
                return s
            }
            if (h.test(i)) {
                for (var u = i.split(h), c = 0, d = u.length; c < d; c++) s[u[c]] = e[n].apply(e, [u[c]].concat(r));
                return s
            }
            return !1
        }, l._callHandler = function(t, e, n) {
            var i = n[0],
                r = n[1],
                s = n[2];
            switch (n.length) {
                case 0:
                    return t.call(e);
                case 1:
                    return t.call(e, i);
                case 2:
                    return t.call(e, i, r);
                case 3:
                    return t.call(e, i, r, s);
                default:
                    return t.apply(e, n)
            }
        };
        var u = {};
        t.extend(l, {
            log: function(e, n) {
                if ("undefined" != typeof console) {
                    var i = t.toArray(arguments).slice(2);
                    console.log("[" + e + '] "' + n + '"', i)
                }
            },
            tuneIn: function(t) {
                var e = l.channel(t);
                return e._tunedIn = !0, e.on("all", r(t)), this
            },
            tuneOut: function(t) {
                var e = l.channel(t);
                return e._tunedIn = !1, e.off("all", r(t)), delete u[t], this
            }
        }), l.Requests = {
            request: function(e) {
                var n = t.toArray(arguments).slice(1),
                    i = l._eventsApi(this, "request", e, n);
                if (i) return i;
                var r = this.channelName,
                    s = this._requests;
                if (r && this._tunedIn && l.log.apply(this, [r, e].concat(n)), s && (s[e] || s["default"])) {
                    var o = s[e] || s["default"];
                    return n = s[e] ? n : arguments, l._callHandler(o.callback, o.context, n)
                }
                l.debugLog("An unhandled request was fired", e, r)
            },
            reply: function(t, e, n) {
                return l._eventsApi(this, "reply", t, [e, n]) ? this : (this._requests || (this._requests = {}), this._requests[t] && l.debugLog("A request was overwritten", t, this.channelName), this._requests[t] = {
                    callback: s(e),
                    context: n || this
                }, this)
            },
            replyOnce: function(e, n, i) {
                if (l._eventsApi(this, "replyOnce", e, [n, i])) return this;
                var r = this,
                    o = t.once(function() {
                        return r.stopReplying(e), s(n).apply(this, arguments)
                    });
                return this.reply(e, o, i)
            },
            stopReplying: function(t, e, n) {
                return l._eventsApi(this, "stopReplying", t) ? this : (t || e || n ? i(this._requests, t, e, n) || l.debugLog("Attempted to remove the unregistered request", t, this.channelName) : delete this._requests, this)
            }
        }, l._channels = {}, l.channel = function(t) {
            if (!t) throw new Error("You must provide a name for the channel.");
            return l._channels[t] ? l._channels[t] : l._channels[t] = new l.Channel(t)
        }, l.Channel = function(t) {
            this.channelName = t
        }, t.extend(l.Channel.prototype, e.Events, l.Requests, {
            reset: function() {
                return this.off(), this.stopListening(), this.stopReplying(), this
            }
        });
        var c, d, f = [e.Events, l.Requests];
        return t.each(f, function(e) {
            t.each(e, function(e, n) {
                l[n] = function(e) {
                    return d = t.toArray(arguments).slice(1), c = this.channel(e), c[n].apply(c, d)
                }
            })
        }), l.reset = function(e) {
            var n = e ? [this._channels[e]] : this._channels;
            t.each(n, function(t) {
                t.reset()
            })
        }, l
    }), function(t) {
        if ("object" == typeof exports && "function" == typeof require) module.exports = t(require("underscore"), require("backbone"));
        else if ("function" == typeof define && define.amd) define(["underscore", "backbone"], t);
        else if ("undefined" != typeof _ && "undefined" != typeof Backbone) {
            var e = Backbone.PageableCollection,
                n = t(_, Backbone);
            Backbone.PageableCollection.noConflict = function() {
                return Backbone.PageableCollection = e, n
            }
        }
    }(function(t, e) {
        "use strict";

        function n(e, n) {
            if (!t.isNumber(e) || t.isNaN(e) || !t.isFinite(e) || ~~e !== e) throw new TypeError("`" + n + "` must be a finite integer");
            return e
        }

        function i(t) {
            for (var e, n, i, r, s = {}, o = decodeURIComponent, a = t.split("&"), l = 0, h = a.length; l < h; l++) {
                var u = a[l];
                e = u.split("="), n = e[0], i = e[1], null == i && (i = !0), n = o(n), i = o(i), r = s[n], p(r) ? r.push(i) : r ? s[n] = [r, i] : s[n] = i
            }
            return s
        }

        function r(t, e, n) {
            var i = t._events[e];
            if (i && i.length) {
                var r = i[i.length - 1],
                    s = r.callback;
                r.callback = function() {
                    try {
                        s.apply(this, arguments), n()
                    } catch (t) {
                        throw t
                    } finally {
                        r.callback = s
                    }
                }
            } else n()
        }
        var s = t.extend,
            o = t.omit,
            a = t.clone,
            l = t.each,
            h = t.pick,
            u = t.includes,
            c = t.isEmpty,
            d = t.pairs || t.toPairs,
            f = t.invert,
            p = t.isArray,
            g = t.isFunction,
            m = t.isObject,
            v = t.keys,
            y = t.isUndefined,
            w = Math.ceil,
            _ = Math.floor,
            b = Math.max,
            x = e.Collection.prototype,
            C = /[\s'"]/g,
            S = /[<>\s'"]/g,
            D = e.PageableCollection = e.Collection.extend({
                state: {
                    firstPage: 1,
                    lastPage: null,
                    currentPage: null,
                    pageSize: 25,
                    totalPages: null,
                    totalRecords: null,
                    sortKey: null,
                    order: -1
                },
                mode: "server",
                queryParams: {
                    currentPage: "page",
                    pageSize: "per_page",
                    totalPages: "total_pages",
                    totalRecords: "total_entries",
                    sortKey: "sort_by",
                    order: "order",
                    directions: {
                        "-1": "asc",
                        1: "desc"
                    }
                },
                constructor: function(t, e) {
                    x.constructor.apply(this, arguments), e = e || {};
                    var n = this.mode = e.mode || this.mode || E.mode,
                        i = s({}, E.queryParams, this.queryParams, e.queryParams || {});
                    i.directions = s({}, E.queryParams.directions, this.queryParams.directions, i.directions), this.queryParams = i;
                    var r = this.state = s({}, E.state, this.state, e.state);
                    r.currentPage = null == r.currentPage ? r.firstPage : r.currentPage, p(t) || (t = t ? [t] : []), t = t.slice(), "server" == n || null != r.totalRecords || c(t) || (r.totalRecords = this.length), this.switchMode(n, s({
                        fetch: !1,
                        resetState: !1,
                        models: t
                    }, e));
                    var o = e.comparator;
                    if (r.sortKey && !o && this.setSorting(r.sortKey, r.order, e), "server" != n) {
                        var l = this.fullCollection;
                        o && e.full && (this.comparator = null, l.comparator = o), e.full && l.sort(), c(t) || this.getPage(r.currentPage)
                    }
                    this._initState = a(this.state)
                },
                _makeFullCollection: function(t, n) {
                    var i, r, s, o = ["url", "model", "sync", "comparator"],
                        a = this.constructor.prototype,
                        l = {};
                    for (i = 0, r = o.length; i < r; i++) s = o[i], y(a[s]) || (l[s] = a[s]);
                    var h = new(e.Collection.extend(l))(t, n);
                    for (i = 0, r = o.length; i < r; i++) s = o[i], this[s] !== a[s] && (h[s] = this[s]);
                    return h
                },
                _makeCollectionEventHandler: function(t, e) {
                    return function(n, i, o, h) {
                        var u = t._handlers;
                        l(v(u), function(n) {
                            var i = u[n];
                            t.off(n, i), e.off(n, i)
                        });
                        var c = a(t.state),
                            d = c.firstPage,
                            f = 0 === d ? c.currentPage : c.currentPage - 1,
                            p = c.pageSize,
                            g = f * p,
                            m = g + p;
                        if ("add" == n) {
                            var _, b, x, C, h = h || {};
                            if (o == e) b = e.indexOf(i), b >= g && b < m && (C = t, _ = x = b - g);
                            else {
                                _ = t.indexOf(i), b = g + _, C = e;
                                var x = y(h.at) ? b : h.at + g
                            }
                            if (h.onRemove || (++c.totalRecords, delete h.onRemove), t.state = t._checkState(c), C) {
                                C.add(i, s({}, h, {
                                    at: x
                                }));
                                var S = _ >= p ? i : !y(h.at) && x < m && t.length > p ? t.at(p) : null;
                                S && r(o, n, function() {
                                    t.remove(S, {
                                        onAdd: !0
                                    })
                                })
                            }
                            h.silent || t.trigger("pageable:state:change", t.state)
                        }
                        if ("remove" == n) {
                            if (h.onAdd) delete h.onAdd;
                            else {
                                if (--c.totalRecords) {
                                    var D = c.totalPages = w(c.totalRecords / p);
                                    c.lastPage = 0 === d ? D - 1 : D || d, c.currentPage > D && (c.currentPage = c.lastPage)
                                } else c.totalRecords = null, c.totalPages = null;
                                t.state = t._checkState(c);
                                var E, k = h.index;
                                o == t ? ((E = e.at(m)) ? r(t, n, function() {
                                    t.push(E, {
                                        onRemove: !0
                                    })
                                }) : !t.length && c.totalRecords && t.reset(e.models.slice(g - p, m - p), s({}, h, {
                                    parse: !1
                                })), e.remove(i)) : k >= g && k < m && ((E = e.at(m - 1)) && r(t, n, function() {
                                    t.push(E, {
                                        onRemove: !0
                                    })
                                }), t.remove(i), !t.length && c.totalRecords && t.reset(e.models.slice(g - p, m - p), s({}, h, {
                                    parse: !1
                                })))
                            }
                            h.silent || t.trigger("pageable:state:change", t.state)
                        }
                        if ("reset" == n) {
                            if (h = o, o = i, o == t && null == h.from && null == h.to) {
                                var T = e.models.slice(0, g),
                                    O = e.models.slice(g + t.models.length);
                                e.reset(T.concat(t.models).concat(O), h)
                            } else o == e && ((c.totalRecords = e.models.length) || (c.totalRecords = null, c.totalPages = null), "client" == t.mode && (d = c.lastPage = c.currentPage = c.firstPage, f = 0 === d ? c.currentPage : c.currentPage - 1, g = f * p, m = g + p), t.state = t._checkState(c), t.reset(e.models.slice(g, m), s({}, h, {
                                parse: !1
                            })));
                            h.silent || t.trigger("pageable:state:change", t.state)
                        }
                        "sort" == n && (h = o, o = i, o === e && t.reset(e.models.slice(g, m), s({}, h, {
                            parse: !1
                        }))), l(v(u), function(n) {
                            var i = u[n];
                            l([t, e], function(t) {
                                t.on(n, i);
                                var e = t._events[n] || [];
                                e.unshift(e.pop())
                            })
                        })
                    }
                },
                _checkState: function(t) {
                    var e = this.mode,
                        i = this.links,
                        r = t.totalRecords,
                        s = t.pageSize,
                        o = t.currentPage,
                        a = t.firstPage,
                        l = t.totalPages;
                    if (null != r && null != s && null != o && null != a && ("infinite" != e || i)) {
                        if (r = n(r, "totalRecords"), s = n(s, "pageSize"), o = n(o, "currentPage"), a = n(a, "firstPage"), s < 1) throw new RangeError("`pageSize` must be >= 1");
                        if (l = t.totalPages = w(r / s), a < 0 || a > 1) throw new RangeError("`firstPage must be 0 or 1`");
                        if (t.lastPage = 0 === a ? b(0, l - 1) : l || a, "infinite" == e) {
                            if (!i[o]) throw new RangeError("No link found for page " + o)
                        } else if (o < a || l > 0 && (a ? o > l : o >= l)) throw new RangeError("`currentPage` must be firstPage <= currentPage " + (a ? "<" : "<=") + " totalPages if " + a + "-based. Got " + o + ".")
                    }
                    return t
                },
                setPageSize: function(t, e) {
                    t = n(t, "pageSize"), e = e || {
                        first: !1
                    };
                    var i = this.state,
                        r = w(i.totalRecords / t),
                        a = r ? b(i.firstPage, _(r * i.currentPage / i.totalPages)) : i.firstPage;
                    return i = this.state = this._checkState(s({}, i, {
                        pageSize: t,
                        currentPage: e.first ? i.firstPage : a,
                        totalPages: r
                    })), this.getPage(i.currentPage, o(e, ["first"]))
                },
                switchMode: function(e, n) {
                    if (!u(["server", "client", "infinite"], e)) throw new TypeError('`mode` must be one of "server", "client" or "infinite"');
                    n = n || {
                        fetch: !0,
                        resetState: !0
                    };
                    var i = this.state = n.resetState ? a(this._initState) : this._checkState(s({}, this.state));
                    this.mode = e;
                    var r, h = this,
                        c = this.fullCollection,
                        d = this._handlers = this._handlers || {};
                    if ("server" == e || c) "server" == e && c && (l(v(d), function(t) {
                        r = d[t], h.off(t, r), c.off(t, r)
                    }), delete this._handlers, this._fullComparator = c.comparator, delete this.fullCollection);
                    else {
                        c = this._makeFullCollection(n.models || [], n), c.pageableCollection = this, this.fullCollection = c;
                        var f = this._makeCollectionEventHandler(this, c);
                        l(["add", "remove", "reset", "sort"], function(e) {
                            d[e] = r = t.bind(f, {}, e), h.on(e, r), c.on(e, r)
                        }), c.comparator = this._fullComparator
                    }
                    if ("infinite" == e)
                        for (var p = this.links = {}, g = i.firstPage, m = w(i.totalRecords / i.pageSize), y = 0 === g ? b(0, m - 1) : m || g, _ = i.firstPage; _ <= y; _++) p[_] = this.url;
                    else this.links && delete this.links;
                    return n.silent || this.trigger("pageable:state:change", i), n.fetch ? this.fetch(o(n, "fetch", "resetState")) : this
                },
                hasPreviousPage: function() {
                    var t = this.state,
                        e = t.currentPage;
                    return "infinite" != this.mode ? e > t.firstPage : !!this.links[e - 1]
                },
                hasNextPage: function() {
                    var t = this.state,
                        e = this.state.currentPage;
                    return "infinite" != this.mode ? e < t.lastPage : !!this.links[e + 1]
                },
                getFirstPage: function(t) {
                    return this.getPage("first", t)
                },
                getPreviousPage: function(t) {
                    return this.getPage("prev", t)
                },
                getNextPage: function(t) {
                    return this.getPage("next", t)
                },
                getLastPage: function(t) {
                    return this.getPage("last", t)
                },
                getPage: function(t, e) {
                    var i = this.mode,
                        r = this.fullCollection;
                    e = e || {
                        fetch: !1
                    };
                    var a = this.state,
                        l = a.firstPage,
                        h = a.currentPage,
                        u = a.lastPage,
                        d = a.pageSize,
                        f = t;
                    switch (t) {
                        case "first":
                            f = l;
                            break;
                        case "prev":
                            f = h - 1;
                            break;
                        case "next":
                            f = h + 1;
                            break;
                        case "last":
                            f = u;
                            break;
                        default:
                            f = n(t, "index")
                    }
                    this.state = this._checkState(s({}, a, {
                        currentPage: f
                    })), e.silent || this.trigger("pageable:state:change", this.state), e.from = h, e.to = f;
                    var p = (0 === l ? f : f - 1) * d,
                        g = r && r.length ? r.models.slice(p, p + d) : [];
                    return "client" != i && ("infinite" != i || c(g)) || e.fetch ? ("infinite" == i && (e.url = this.links[f]), this.fetch(o(e, "fetch"))) : (this.reset(g, o(e, "fetch")), this)
                },
                getPageByOffset: function(t, e) {
                    if (t < 0) throw new RangeError("`offset must be > 0`");
                    t = n(t, "offset");
                    var i = _(t / this.state.pageSize);
                    return 0 !== this.state.firstPage && i++, i > this.state.lastPage && (i = this.state.lastPage), this.getPage(i, e)
                },
                sync: function(t, n, i) {
                    var r = this;
                    if ("infinite" == r.mode) {
                        var o = i.success,
                            a = r.state.currentPage;
                        i.success = function(t, e, n) {
                            var l = r.links,
                                h = r.parseLinks(t, s({
                                    xhr: n
                                }, i));
                            h.first && (l[r.state.firstPage] = h.first), h.prev && (l[a - 1] = h.prev), h.next && (l[a + 1] = h.next), o && o(t, e, n)
                        }
                    }
                    return (x.sync || e.sync).call(r, t, n, i)
                },
                parseLinks: function(t, e) {
                    var n = {},
                        i = e.xhr.getResponseHeader("Link");
                    if (i) {
                        var r = ["first", "prev", "next"];
                        l(i.split(","), function(t) {
                            var e = t.split(";"),
                                i = e[0].replace(S, ""),
                                s = e.slice(1);
                            l(s, function(t) {
                                var e = t.split("="),
                                    s = e[0].replace(C, ""),
                                    o = e[1].replace(C, "");
                                "rel" == s && u(r, o) && (n[o] = i)
                            })
                        })
                    }
                    return n
                },
                parse: function(t, e) {
                    var n = this.parseState(t, a(this.queryParams), a(this.state), e);
                    return n && (this.state = this._checkState(s({}, this.state, n)), (e || {}).silent || this.trigger("pageable:state:change", this.state)), this.parseRecords(t, e)
                },
                parseState: function(e, n, i, r) {
                    if (e && 2 === e.length && m(e[0]) && p(e[1])) {
                        var s = a(i),
                            h = e[0];
                        return l(d(o(n, "directions")), function(e) {
                            var n = e[0],
                                i = e[1],
                                r = h[i];
                            y(r) || t.isNull(r) || (s[n] = h[i])
                        }), h.order && (s.order = 1 * f(n.directions)[h.order]), s
                    }
                },
                parseRecords: function(t, e) {
                    return t && 2 === t.length && m(t[0]) && p(t[1]) ? t[1] : t
                },
                fetch: function(e) {
                    e = e || {};
                    var n = this._checkState(this.state),
                        r = this.mode;
                    "infinite" != r || e.url || (e.url = this.links[n.currentPage]);
                    var a = e.data || {},
                        u = e.url || this.url || "";
                    g(u) && (u = u.call(this));
                    var c = u.indexOf("?");
                    c != -1 && (s(a, i(u.slice(c + 1))), u = u.slice(0, c)), e.url = u, e.data = a;
                    var f = "client" == this.mode ? h(this.queryParams, "sortKey") : o(h(this.queryParams, v(E.queryParams)), "order", "directions", "totalPages", "totalRecords");
                    l(f, function(e, i) {
                        e = g(e) ? e.call(this) : e, null != n[i] && null != e && t.isUndefined(a[e]) && (a[e] = n[i])
                    }, this);
                    var m = g(this.queryParams.sortKey) ? this.queryParams.sortKey.call(this) : this.queryParams.sortKey,
                        w = g(this.queryParams.order) ? this.queryParams.order.call(this) : this.queryParams.order;
                    if (null != m && null != n.sortKey && null != w && null != n.order)
                        if (p(n.order)) {
                            a[w] = [];
                            for (var _ = 0; _ < n.order.length; _++) a[w].push(this.queryParams.directions[n.order[_]])
                        } else a[w] = this.queryParams.directions[n.order + ""];
                    for (var b = d(o(this.queryParams, v(E.queryParams))), _ = 0; _ < b.length; _++) {
                        var C = b[_],
                            S = C[1];
                        S = g(S) ? S.call(this) : S, null != S && (a[C[0]] = S)
                    }
                    if ("server" != r) {
                        var D = this,
                            k = this.fullCollection,
                            T = e.success;
                        return e.success = function(t, n, i) {
                            i = i || {}, y(e.silent) ? delete i.silent : i.silent = e.silent;
                            var o = t.models;
                            "client" == r ? k.reset(o, i) : (k.add(o, s({
                                at: k.length
                            }, s(i, {
                                parse: !1
                            }))), D.trigger("reset", D, i)), T && T(t, n, i)
                        }, x.fetch.call(this, s({}, e, {
                            silent: !0
                        }))
                    }
                    return x.fetch.call(this, e)
                },
                _makeComparator: function(t, e, n) {
                    var i = this.state;
                    if (t = t || i.sortKey, e = e || i.order, t && e) return n || (n = function(t, e) {
                            return t.get(e)
                        }),
                        function(i, r) {
                            var s, o = n(i, t),
                                a = n(r, t);
                            return 1 === e && (s = o, o = a, a = s), o === a ? 0 : o < a ? -1 : 1
                        }
                },
                setSorting: function(t, e, n) {
                    var i = this.state;
                    i.sortKey = t, i.order = e = e || i.order;
                    var r = this.fullCollection,
                        o = !1,
                        a = !1;
                    t || (o = a = !0);
                    var l = this.mode;
                    n = s({
                        side: "client" == l ? l : "server",
                        full: !0
                    }, n);
                    var h = this._makeComparator(t, e, n.sortValue),
                        u = n.full,
                        c = n.side;
                    return "client" == c ? u ? (r && (r.comparator = h), o = !0) : (this.comparator = h, a = !0) : "server" != c || u || (this.comparator = h), o && (this.comparator = null), a && r && (r.comparator = null), this
                }
            }),
            E = D.prototype;
        return D
    }), function(t, e) {
        "object" == typeof exports && "undefined" != typeof module ? module.exports = e(require("backbone"), require("underscore"), require("backbone.radio")) : "function" == typeof define && define.amd ? define(["backbone", "underscore", "backbone.radio"], e) : t.Marionette = e(t.Backbone, t._, t.Backbone.Radio)
    }(this, function(t, e, n) {
        "use strict";

        function i(t, e, n) {
            return n.toUpperCase()
        }

        function r(t) {
            for (var n = arguments.length, i = Array(n > 1 ? n - 1 : 0), r = 1; r < n; r++) i[r - 1] = arguments[r];
            var s = Q(t),
                o = W.call(this, s),
                a = void 0;
            return e.isFunction(o) && (a = o.apply(this, i)), this.trigger.apply(this, arguments), a
        }

        function s(t) {
            for (var n = arguments.length, i = Array(n > 1 ? n - 1 : 0), s = 1; s < n; s++) i[s - 1] = arguments[s];
            return e.isFunction(t.triggerMethod) ? t.triggerMethod.apply(t, i) : r.apply(t, i)
        }

        function o(t, n, i) {
            t._getImmediateChildren && e.each(t._getImmediateChildren(), function(t) {
                i(t) && s(t, n, t)
            })
        }

        function a(t) {
            return !t._isAttached
        }

        function l(t) {
            return !!a(t) && (t._isAttached = !0, !0)
        }

        function h(t) {
            return t._isAttached
        }

        function u(t) {
            return !!h(t) && (t._isAttached = !1, !0)
        }

        function c(t) {
            t._isAttached && t._isRendered && s(t, "dom:refresh", t)
        }

        function d(t) {
            t._isAttached && t._isRendered && s(t, "dom:remove", t)
        }

        function f() {
            o(this, "before:attach", a)
        }

        function p() {
            o(this, "attach", l), c(this)
        }

        function g() {
            o(this, "before:detach", h), d(this)
        }

        function m() {
            o(this, "detach", u)
        }

        function v() {
            d(this)
        }

        function y() {
            c(this)
        }

        function w(t) {
            t._areViewEventsMonitored || t.monitorViewEvents === !1 || (t._areViewEventsMonitored = !0, t.on({
                "before:attach": f,
                attach: p,
                "before:detach": g,
                detach: m,
                "before:render": v,
                render: y
            }))
        }

        function _(t, n, i, r, s) {
            var o = r.split(/\s+/);
            o.length > 1 && F("Multiple handlers for a single event are deprecated. If needed, use a single handler to call multiple methods."), e.each(o, function(e) {
                var r = t[e];
                if (!r) throw new J('Method "' + e + '" was configured as an event handler, but does not exist.');
                t[s](n, i, r)
            })
        }

        function b(t, n, i, r) {
            if (!e.isObject(i)) throw new J({
                message: "Bindings must be an object.",
                url: "marionette.functions.html#marionettebindevents"
            });
            e.each(i, function(i, s) {
                return e.isString(i) ? void _(t, n, s, i, r) : void t[r](n, s, i)
            })
        }

        function x(t, e) {
            return t && e ? (b(this, t, e, "listenTo"), this) : this
        }

        function C(t, e) {
            return t ? e ? (b(this, t, e, "stopListening"), this) : (this.stopListening(t), this) : this
        }

        function S(t, n, i, r) {
            if (!e.isObject(i)) throw new J({
                message: "Bindings must be an object.",
                url: "marionette.functions.html#marionettebindrequests"
            });
            var s = z.call(t, i);
            n[r](s, t)
        }

        function D(t, e) {
            return t && e ? (S(this, t, e, "reply"), this) : this
        }

        function E(t, e) {
            return t ? e ? (S(this, t, e, "stopReplying"), this) : (t.stopReplying(null, null, this), this) : this
        }

        function k(t, n) {
            return t.behaviorClass ? t.behaviorClass : e.isFunction(t) ? t : e.isFunction(Yt.Behaviors.behaviorsLookup) ? Yt.Behaviors.behaviorsLookup(t, n)[n] : Yt.Behaviors.behaviorsLookup[n]
        }

        function T(t, n) {
            return e.chain(n).map(function(n, i) {
                var r = k(n, i),
                    s = n === r ? {} : n,
                    o = new r(s, t),
                    a = T(t, e.result(o, "behaviors"));
                return [o].concat(a)
            }).flatten().value()
        }

        function O(t) {
            return !!ht[t]
        }

        function M(t, e) {
            return ht[t] = e
        }

        function R(t, n) {
            e.isString(n) && (n = {
                event: n
            });
            var i = n.event,
                r = !!n.preventDefault;
            O("triggersPreventDefault") && (r = n.preventDefault !== !1);
            var s = !!n.stopPropagation;
            return O("triggersStopPropagation") && (s = n.stopPropagation !== !1),
                function(e) {
                    r && e.preventDefault(), s && e.stopPropagation(), t.triggerMethod(i, t, e)
                }
        }

        function $(e) {
            return e instanceof t.$ ? e : t.$(e)
        }

        function P(t) {
            return this.prototype.Dom = e.extend({}, this.prototype.Dom, t), this
        }

        function A(t) {
            t._isRendered || (t.supportsRenderLifecycle || s(t, "before:render", t), t.render(), t.supportsRenderLifecycle || (t._isRendered = !0, s(t, "render", t)))
        }

        function V(t) {
            if (t.destroy) return void t.destroy();
            t.supportsDestroyLifecycle || s(t, "before:destroy", t);
            var e = t._isAttached && !t._shouldDisableEvents;
            e && s(t, "before:detach", t), t.remove(), e && (t._isAttached = !1, s(t, "detach", t)), t._isDestroyed = !0, t.supportsDestroyLifecycle || s(t, "destroy", t)
        }

        function N(t, n) {
            var i = e.extend({}, n);
            if (e.isString(t)) return e.extend(i, {
                el: t
            }), I(i);
            if (e.isFunction(t)) return e.extend(i, {
                regionClass: t
            }), I(i);
            if (e.isObject(t)) return t.selector && F("The selector option on a Region definition object is deprecated. Use el to pass a selector string"), e.extend(i, {
                el: t.selector
            }, t), I(i);
            throw new J({
                message: "Improper region configuration type.",
                url: "marionette.region.html#region-configuration-types"
            })
        }

        function I(t) {
            var n = t.regionClass,
                i = e.omit(t, "regionClass");
            return new n(i)
        }

        function j(t, e) {
            return e.model && e.model.get(t)
        }

        function L() {
            throw new J({
                message: "You must define where your behaviors are stored.",
                url: "marionette.behaviors.md#behaviorslookup"
            })
        }
        t = t && t.hasOwnProperty("default") ? t["default"] : t, e = e && e.hasOwnProperty("default") ? e["default"] : e, n = n && n.hasOwnProperty("default") ? n["default"] : n;
        var B = "3.5.1",
            H = function(t) {
                return function(e) {
                    for (var n = arguments.length, i = Array(n > 1 ? n - 1 : 0), r = 1; r < n; r++) i[r - 1] = arguments[r];
                    return t.apply(e, i)
                }
            },
            Y = t.Model.extend,
            F = function Ft(t, n) {
                e.isObject(t) && (t = t.prev + " is going to be removed in the future. Please use " + t.next + " instead." + (t.url ? " See: " + t.url : "")), Yt.DEV_MODE && (void 0 !== n && n || Ft._cache[t] || (Ft._warn("Deprecation warning: " + t), Ft._cache[t] = !0))
            };
        F._console = "undefined" != typeof console ? console : {}, F._warn = function() {
            var t = F._console.warn || F._console.log || e.noop;
            return t.apply(F._console, arguments)
        }, F._cache = {};
        var U = function(t) {
                return document.documentElement.contains(t && t.parentNode)
            },
            q = function(t, n) {
                var i = this;
                t && e.each(n, function(e) {
                    var n = t[e];
                    void 0 !== n && (i[e] = n)
                })
            },
            W = function(t) {
                if (t) return this.options && void 0 !== this.options[t] ? this.options[t] : this[t]
            },
            z = function(t) {
                var n = this;
                return e.reduce(t, function(t, i, r) {
                    return e.isFunction(i) || (i = n[i]), i && (t[r] = i), t
                }, {})
            },
            G = /(^|:)(\w)/gi,
            Q = e.memoize(function(t) {
                return "on" + t.replace(G, i)
            }),
            Z = ["description", "fileName", "lineNumber", "name", "message", "number"],
            J = Y.call(Error, {
                urlRoot: "http://marionettejs.com/docs/v" + B + "/",
                constructor: function(t, n) {
                    e.isObject(t) ? (n = t, t = n.message) : n || (n = {});
                    var i = Error.call(this, t);
                    e.extend(this, e.pick(i, Z), e.pick(n, Z)), this.captureStackTrace(), n.url && (this.url = this.urlRoot + n.url)
                },
                captureStackTrace: function() {
                    Error.captureStackTrace && Error.captureStackTrace(this, J)
                },
                toString: function() {
                    return this.name + ": " + this.message + (this.url ? " See: " + this.url : "")
                }
            });
        J.extend = Y;
        var K = function(t) {
                this.options = e.extend({}, e.result(this, "options"), t)
            },
            X = {
                normalizeMethods: z,
                _setOptions: K,
                mergeOptions: q,
                getOption: W,
                bindEvents: x,
                unbindEvents: C
            },
            tt = {
                _initRadio: function() {
                    var t = e.result(this, "channelName");
                    if (t) {
                        if (!n) throw new J({
                            name: "BackboneRadioMissing",
                            message: 'The dependency "backbone.radio" is missing.'
                        });
                        var i = this._channel = n.channel(t),
                            r = e.result(this, "radioEvents");
                        this.bindEvents(i, r);
                        var s = e.result(this, "radioRequests");
                        this.bindRequests(i, s), this.on("destroy", this._destroyRadio)
                    }
                },
                _destroyRadio: function() {
                    this._channel.stopReplying(null, null, this)
                },
                getChannel: function() {
                    return this._channel
                },
                bindEvents: x,
                unbindEvents: C,
                bindRequests: D,
                unbindRequests: E
            },
            et = ["channelName", "radioEvents", "radioRequests"],
            nt = function(t) {
                this.hasOwnProperty("options") || this._setOptions(t), this.mergeOptions(t, et), this._setCid(), this._initRadio(), this.initialize.apply(this, arguments)
            };
        nt.extend = Y, e.extend(nt.prototype, t.Events, X, tt, {
            cidPrefix: "mno",
            _isDestroyed: !1,
            isDestroyed: function() {
                return this._isDestroyed
            },
            initialize: function() {},
            _setCid: function() {
                this.cid || (this.cid = e.uniqueId(this.cidPrefix))
            },
            destroy: function() {
                if (this._isDestroyed) return this;
                for (var t = arguments.length, e = Array(t), n = 0; n < t; n++) e[n] = arguments[n];
                return this.triggerMethod.apply(this, ["before:destroy", this].concat(e)), this._isDestroyed = !0, this.triggerMethod.apply(this, ["destroy", this].concat(e)), this.stopListening(), this
            },
            triggerMethod: r
        });
        var it = function(t) {
            this.templateId = t
        };
        e.extend(it, {
            templateCaches: {},
            get: function(t, e) {
                var n = this.templateCaches[t];
                return n || (n = new it(t), this.templateCaches[t] = n), n.load(e)
            },
            clear: function() {
                for (var t = void 0, e = arguments.length, n = Array(e), i = 0; i < e; i++) n[i] = arguments[i];
                var r = n.length;
                if (r > 0)
                    for (t = 0; t < r; t++) delete this.templateCaches[n[t]];
                else this.templateCaches = {}
            }
        }), e.extend(it.prototype, {
            load: function(t) {
                if (this.compiledTemplate) return this.compiledTemplate;
                var e = this.loadTemplate(this.templateId, t);
                return this.compiledTemplate = this.compileTemplate(e, t), this.compiledTemplate
            },
            loadTemplate: function(e, n) {
                var i = t.$(e);
                if (!i.length) throw new J({
                    name: "NoTemplateError",
                    message: 'Could not find template: "' + e + '"'
                });
                return i.html()
            },
            compileTemplate: function(t, n) {
                return e.template(t, n)
            }
        });
        var rt = e.invokeMap || e.invoke,
            st = {
                _initBehaviors: function() {
                    this._behaviors = this._getBehaviors()
                },
                _getBehaviors: function() {
                    var t = e.result(this, "behaviors");
                    return e.isObject(t) ? T(this, t) : {}
                },
                _getBehaviorTriggers: function() {
                    var t = rt(this._behaviors, "getTriggers");
                    return e.reduce(t, function(t, n) {
                        return e.extend(t, n)
                    }, {})
                },
                _getBehaviorEvents: function() {
                    var t = rt(this._behaviors, "getEvents");
                    return e.reduce(t, function(t, n) {
                        return e.extend(t, n)
                    }, {})
                },
                _proxyBehaviorViewProperties: function() {
                    rt(this._behaviors, "proxyViewProperties")
                },
                _delegateBehaviorEntityEvents: function() {
                    rt(this._behaviors, "delegateEntityEvents")
                },
                _undelegateBehaviorEntityEvents: function() {
                    rt(this._behaviors, "undelegateEntityEvents")
                },
                _destroyBehaviors: function() {
                    for (var t = arguments.length, e = Array(t), n = 0; n < t; n++) e[n] = arguments[n];
                    rt.apply(void 0, [this._behaviors, "destroy"].concat(e))
                },
                _removeBehavior: function(t) {
                    this._isDestroyed || (this.undelegate(".trig" + t.cid + " ." + t.cid), this._behaviors = e.without(this._behaviors, t))
                },
                _bindBehaviorUIElements: function() {
                    rt(this._behaviors, "bindUIElements")
                },
                _unbindBehaviorUIElements: function() {
                    rt(this._behaviors, "unbindUIElements")
                },
                _triggerEventOnBehaviors: function() {
                    for (var t = this._behaviors, e = 0, n = t && t.length; e < n; e++) r.apply(t[e], arguments)
                }
            },
            ot = {
                _delegateEntityEvents: function(t, n) {
                    var i = e.result(this, "modelEvents");
                    i && (C.call(this, t, i), x.call(this, t, i));
                    var r = e.result(this, "collectionEvents");
                    r && (C.call(this, n, r), x.call(this, n, r))
                },
                _undelegateEntityEvents: function(t, n) {
                    var i = e.result(this, "modelEvents");
                    C.call(this, t, i);
                    var r = e.result(this, "collectionEvents");
                    C.call(this, n, r)
                }
            },
            at = /^(\S+)\s*(.*)$/,
            lt = function(t, e) {
                var n = t.match(at);
                return n[1] + "." + e + " " + n[2]
            },
            ht = {
                childViewEventPrefix: !0,
                triggersStopPropagation: !0,
                triggersPreventDefault: !0
            },
            ut = {
                _getViewTriggers: function(t, n) {
                    var i = this;
                    return e.reduce(n, function(e, n, r) {
                        return r = lt(r, "trig" + i.cid), e[r] = R(t, n), e
                    }, {})
                }
            },
            ct = function(t, n) {
                return e.reduce(t, function(t, e, i) {
                    var r = dt(i, n);
                    return t[r] = e, t
                }, {})
            },
            dt = function(t, e) {
                return t.replace(/@ui\.[a-zA-Z-_$0-9]*/g, function(t) {
                    return e[t.slice(4)]
                })
            },
            ft = function Ut(t, n, i) {
                return e.each(t, function(r, s) {
                    e.isString(r) ? t[s] = dt(r, n) : e.isObject(r) && e.isArray(i) && (e.extend(r, Ut(e.pick(r, i), n)), e.each(i, function(t) {
                        var i = r[t];
                        e.isString(i) && (r[t] = dt(i, n))
                    }))
                }), t
            },
            pt = {
                normalizeUIKeys: function(t) {
                    var e = this._getUIBindings();
                    return ct(t, e)
                },
                normalizeUIString: function(t) {
                    var e = this._getUIBindings();
                    return dt(t, e)
                },
                normalizeUIValues: function(t, e) {
                    var n = this._getUIBindings();
                    return ft(t, n, e)
                },
                _getUIBindings: function() {
                    var t = e.result(this, "_uiBindings"),
                        n = e.result(this, "ui");
                    return t || n
                },
                _bindUIElements: function() {
                    var t = this;
                    if (this.ui) {
                        this._uiBindings || (this._uiBindings = this.ui);
                        var n = e.result(this, "_uiBindings");
                        this._ui = {}, e.each(n, function(e, n) {
                            t._ui[n] = t.$(e)
                        }), this.ui = this._ui
                    }
                },
                _unbindUIElements: function() {
                    var t = this;
                    this.ui && this._uiBindings && (e.each(this.ui, function(e, n) {
                        delete t.ui[n]
                    }), this.ui = this._uiBindings, delete this._uiBindings, delete this._ui)
                },
                _getUI: function(t) {
                    return this._ui[t]
                }
            },
            gt = {
                createBuffer: function() {
                    return document.createDocumentFragment()
                },
                getEl: function(t) {
                    return $(t)
                },
                findEl: function(t, e) {
                    var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : $(t);
                    return n.find(e)
                },
                hasEl: function(t, e) {
                    return t.contains(e && e.parentNode)
                },
                detachEl: function(t) {
                    var e = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : $(t);
                    e.detach()
                },
                replaceEl: function(t, e) {
                    if (t !== e) {
                        var n = e.parentNode;
                        n && n.replaceChild(t, e)
                    }
                },
                swapEl: function(t, e) {
                    if (t !== e) {
                        var n = t.parentNode,
                            i = e.parentNode;
                        if (n && i) {
                            var r = t.nextSibling,
                                s = e.nextSibling;
                            n.insertBefore(e, r), i.insertBefore(t, s)
                        }
                    }
                },
                setContents: function(t, e) {
                    var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : $(t);
                    n.html(e)
                },
                appendContents: function(t, e) {
                    var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : {},
                        i = n._$el,
                        r = void 0 === i ? $(t) : i,
                        s = n._$contents,
                        o = void 0 === s ? $(e) : s;
                    r.append(o)
                },
                hasContents: function(t) {
                    return !!t && t.hasChildNodes()
                },
                detachContents: function(t) {
                    var e = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : $(t);
                    e.contents().detach()
                }
            },
            mt = {
                Dom: gt,
                supportsRenderLifecycle: !0,
                supportsDestroyLifecycle: !0,
                _isDestroyed: !1,
                isDestroyed: function() {
                    return !!this._isDestroyed
                },
                _isRendered: !1,
                isRendered: function() {
                    return !!this._isRendered
                },
                _isAttached: !1,
                isAttached: function() {
                    return !!this._isAttached
                },
                delegateEvents: function(n) {
                    this._proxyBehaviorViewProperties(), this._buildEventProxies();
                    var i = this._getEvents(n);
                    "undefined" == typeof n && (this.events = i);
                    var r = e.extend({}, this._getBehaviorEvents(), i, this._getBehaviorTriggers(), this.getTriggers());
                    return t.View.prototype.delegateEvents.call(this, r), this
                },
                _getEvents: function(t) {
                    var n = t || this.events;
                    return e.isFunction(n) ? this.normalizeUIKeys(n.call(this)) : this.normalizeUIKeys(n)
                },
                getTriggers: function() {
                    if (this.triggers) {
                        var t = this.normalizeUIKeys(e.result(this, "triggers"));
                        return this._getViewTriggers(this, t)
                    }
                },
                delegateEntityEvents: function() {
                    return this._delegateEntityEvents(this.model, this.collection), this._delegateBehaviorEntityEvents(), this
                },
                undelegateEntityEvents: function() {
                    return this._undelegateEntityEvents(this.model, this.collection), this._undelegateBehaviorEntityEvents(), this
                },
                destroy: function() {
                    if (this._isDestroyed) return this;
                    for (var t = this._isAttached && !this._shouldDisableEvents, e = arguments.length, n = Array(e), i = 0; i < e; i++) n[i] = arguments[i];
                    return this.triggerMethod.apply(this, ["before:destroy", this].concat(n)), t && this.triggerMethod("before:detach", this), this.unbindUIElements(), this._removeElement(), t && (this._isAttached = !1, this.triggerMethod("detach", this)), this._removeChildren(), this._isDestroyed = !0, this._isRendered = !1, this._destroyBehaviors.apply(this, n), this.triggerMethod.apply(this, ["destroy", this].concat(n)), this.stopListening(), this
                },
                _removeElement: function() {
                    this.$el.off().removeData(), this.Dom.detachEl(this.el, this.$el)
                },
                bindUIElements: function() {
                    return this._bindUIElements(), this._bindBehaviorUIElements(), this
                },
                unbindUIElements: function() {
                    return this._unbindUIElements(), this._unbindBehaviorUIElements(), this
                },
                getUI: function(t) {
                    return this._getUI(t)
                },
                childViewEventPrefix: function() {
                    return !!O("childViewEventPrefix") && "childview"
                },
                triggerMethod: function() {
                    var t = r.apply(this, arguments);
                    return this._triggerEventOnBehaviors.apply(this, arguments), t
                },
                _buildEventProxies: function() {
                    this._childViewEvents = e.result(this, "childViewEvents"), this._childViewTriggers = e.result(this, "childViewTriggers")
                },
                _proxyChildViewEvents: function(t) {
                    this.listenTo(t, "all", this._childViewEventHandler)
                },
                _childViewEventHandler: function(t) {
                    for (var n = this.normalizeMethods(this._childViewEvents), i = arguments.length, r = Array(i > 1 ? i - 1 : 0), s = 1; s < i; s++) r[s - 1] = arguments[s];
                    "undefined" != typeof n && e.isFunction(n[t]) && n[t].apply(this, r);
                    var o = this._childViewTriggers;
                    o && e.isString(o[t]) && this.triggerMethod.apply(this, [o[t]].concat(r));
                    var a = e.result(this, "childViewEventPrefix");
                    if (a !== !1) {
                        var l = a + ":" + t;
                        this.triggerMethod.apply(this, [l].concat(r))
                    }
                }
            };
        e.extend(mt, st, X, ot, ut, pt);
        var vt = ["allowMissingEl", "parentEl", "replaceElement"],
            yt = nt.extend({
                Dom: gt,
                cidPrefix: "mnr",
                replaceElement: !1,
                _isReplaced: !1,
                _isSwappingView: !1,
                constructor: function(e) {
                    if (this._setOptions(e), this.mergeOptions(e, vt), this._initEl = this.el = this.getOption("el"), this.el = this.el instanceof t.$ ? this.el[0] : this.el, !this.el) throw new J({
                        name: "NoElError",
                        message: 'An "el" must be specified for a region.'
                    });
                    this.$el = this.getEl(this.el), nt.call(this, e)
                },
                show: function(t, e) {
                    if (this._ensureElement(e)) return t = this._getView(t, e), t === this.currentView ? this : (this._isSwappingView = !!this.currentView, this.triggerMethod("before:show", this, t, e), t._isAttached || this.empty(e), this._setupChildView(t), this.currentView = t, A(t), this._attachView(t, e), this.triggerMethod("show", this, t, e), this._isSwappingView = !1, this)
                },
                _setupChildView: function(t) {
                    w(t), this._proxyChildViewEvents(t), t.on("destroy", this._empty, this)
                },
                _proxyChildViewEvents: function(t) {
                    var e = this._parentView;
                    e && e._proxyChildViewEvents(t)
                },
                _shouldDisableMonitoring: function() {
                    return this._parentView && this._parentView.monitorViewEvents === !1
                },
                _attachView: function(t) {
                    var n = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {},
                        i = !t._isAttached && U(this.el) && !this._shouldDisableMonitoring(),
                        r = "undefined" == typeof n.replaceElement ? !!e.result(this, "replaceElement") : !!n.replaceElement;
                    i && s(t, "before:attach", t), r ? this._replaceEl(t) : this.attachHtml(t), i && (t._isAttached = !0, s(t, "attach", t))
                },
                _ensureElement: function() {
                    var t = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : {};
                    if (e.isObject(this.el) || (this.$el = this.getEl(this.el), this.el = this.$el[0], this.$el = this.Dom.getEl(this.el)), !this.$el || 0 === this.$el.length) {
                        var n = "undefined" == typeof t.allowMissingEl ? !!e.result(this, "allowMissingEl") : !!t.allowMissingEl;
                        if (n) return !1;
                        throw new J('An "el" must exist in DOM for this region ' + this.cid)
                    }
                    return !0
                },
                _getView: function(e) {
                    if (!e) throw new J({
                        name: "ViewNotValid",
                        message: "The view passed is undefined and therefore invalid. You must pass a view instance to show."
                    });
                    if (e._isDestroyed) throw new J({
                        name: "ViewDestroyedError",
                        message: 'View (cid: "' + e.cid + '") has already been destroyed and cannot be used.'
                    });
                    if (e instanceof t.View) return e;
                    var n = this._getViewOptions(e);
                    return new Ct(n)
                },
                _getViewOptions: function(t) {
                    if (e.isFunction(t)) return {
                        template: t
                    };
                    if (e.isObject(t)) return t;
                    var n = function() {
                        return t
                    };
                    return {
                        template: n
                    }
                },
                getEl: function(t) {
                    var n = e.result(this, "parentEl");
                    return n && e.isString(t) ? this.Dom.findEl(n, t) : this.Dom.getEl(t)
                },
                _replaceEl: function(t) {
                    this._restoreEl(), t.on("before:destroy", this._restoreEl, this), this.Dom.replaceEl(t.el, this.el), this._isReplaced = !0
                },
                _restoreEl: function() {
                    if (this._isReplaced) {
                        var t = this.currentView;
                        t && (this._detachView(t), this._isReplaced = !1)
                    }
                },
                isReplaced: function() {
                    return !!this._isReplaced
                },
                isSwappingView: function() {
                    return !!this._isSwappingView
                },
                attachHtml: function(t) {
                    this.Dom.appendContents(this.el, t.el, {
                        _$el: this.$el,
                        _$contents: t.$el
                    })
                },
                empty: function() {
                    var t = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : {
                            allowMissingEl: !0
                        },
                        e = this.currentView;
                    if (!e) return this._ensureElement(t) && this.detachHtml(), this;
                    var n = !t.preventDestroy;
                    return n || F("The preventDestroy option is deprecated. Use Region#detachView"), this._empty(e, n), this
                },
                _empty: function(t, e) {
                    t.off("destroy", this._empty, this), this.triggerMethod("before:empty", this, t), this._restoreEl(), delete this.currentView, t._isDestroyed || (e ? this.removeView(t) : this._detachView(t), this._stopChildViewEvents(t)), this.triggerMethod("empty", this, t)
                },
                _stopChildViewEvents: function(t) {
                    var e = this._parentView;
                    e && this._parentView.stopListening(t)
                },
                destroyView: function(t) {
                    return t._isDestroyed ? t : (t._shouldDisableEvents = this._shouldDisableMonitoring(), V(t), t)
                },
                removeView: function(t) {
                    this.destroyView(t)
                },
                detachView: function() {
                    var t = this.currentView;
                    if (t) return this._empty(t), t
                },
                _detachView: function(t) {
                    var e = t._isAttached && !this._shouldDisableMonitoring(),
                        n = this._isReplaced;
                    e && s(t, "before:detach", t), n ? this.Dom.replaceEl(this.el, t.el) : this.detachHtml(), e && (t._isAttached = !1, s(t, "detach", t))
                },
                detachHtml: function() {
                    this.Dom.detachContents(this.el, this.$el)
                },
                hasView: function() {
                    return !!this.currentView
                },
                reset: function(t) {
                    return this.empty(t), this.$el && (this.el = this._initEl), delete this.$el, this
                },
                destroy: function(t) {
                    return this._isDestroyed ? this : (this.reset(t), this._name && this._parentView._removeReferences(this._name), delete this._parentView, delete this._name, nt.prototype.destroy.apply(this, arguments))
                }
            }, {
                setDomApi: P
            }),
            wt = function(t, e) {
                return t instanceof yt ? t : N(t, e)
            },
            _t = {
                regionClass: yt,
                _initRegions: function() {
                    this.regions = this.regions || {}, this._regions = {}, this.addRegions(e.result(this, "regions"))
                },
                _reInitRegions: function() {
                    rt(this._regions, "reset")
                },
                addRegion: function(t, e) {
                    var n = {};
                    return n[t] = e, this.addRegions(n)[t]
                },
                addRegions: function(t) {
                    if (!e.isEmpty(t)) return t = this.normalizeUIValues(t, ["selector", "el"]), this.regions = e.extend({}, this.regions, t), this._addRegions(t)
                },
                _addRegions: function(t) {
                    var n = this,
                        i = {
                            regionClass: this.regionClass,
                            parentEl: e.partial(e.result, this, "el")
                        };
                    return e.reduce(t, function(t, e, r) {
                        return t[r] = wt(e, i), n._addRegion(t[r], r), t
                    }, {})
                },
                _addRegion: function(t, e) {
                    this.triggerMethod("before:add:region", this, e, t), t._parentView = this, t._name = e, this._regions[e] = t, this.triggerMethod("add:region", this, e, t)
                },
                removeRegion: function(t) {
                    var e = this._regions[t];
                    return this._removeRegion(e, t), e
                },
                removeRegions: function() {
                    var t = this._getRegions();
                    return e.each(this._regions, e.bind(this._removeRegion, this)), t
                },
                _removeRegion: function(t, e) {
                    this.triggerMethod("before:remove:region", this, e, t), t.destroy(), this.triggerMethod("remove:region", this, e, t)
                },
                _removeReferences: function(t) {
                    delete this.regions[t], delete this._regions[t]
                },
                emptyRegions: function() {
                    var t = this.getRegions();
                    return rt(t, "empty"), t
                },
                hasRegion: function(t) {
                    return !!this.getRegion(t)
                },
                getRegion: function(t) {
                    return this._isRendered || this.render(), this._regions[t]
                },
                _getRegions: function() {
                    return e.clone(this._regions)
                },
                getRegions: function() {
                    return this._isRendered || this.render(), this._getRegions()
                },
                showChildView: function(t, e) {
                    for (var n = this.getRegion(t), i = arguments.length, r = Array(i > 2 ? i - 2 : 0), s = 2; s < i; s++) r[s - 2] = arguments[s];
                    return n.show.apply(n, [e].concat(r))
                },
                detachChildView: function(t) {
                    return this.getRegion(t).detachView()
                },
                getChildView: function(t) {
                    return this.getRegion(t).currentView
                }
            },
            bt = {
                render: function(t, n) {
                    if (!t) throw new J({
                        name: "TemplateNotFoundError",
                        message: "Cannot render the template since its false, null or undefined."
                    });
                    var i = e.isFunction(t) ? t : it.get(t);
                    return i(n)
                }
            },
            xt = ["behaviors", "childViewEventPrefix", "childViewEvents", "childViewTriggers", "collectionEvents", "events", "modelEvents", "regionClass", "regions", "template", "templateContext", "triggers", "ui"],
            Ct = t.View.extend({
                constructor: function(n) {
                    this.render = e.bind(this.render, this), this._setOptions(n), this.mergeOptions(n, xt), w(this), this._initBehaviors(), this._initRegions();
                    var i = Array.prototype.slice.call(arguments);
                    i[0] = this.options, t.View.prototype.constructor.apply(this, i), this.delegateEntityEvents(), this._triggerEventOnBehaviors("initialize", this)
                },
                serializeData: function() {
                    return this.model || this.collection ? this.model ? this.serializeModel() : {
                        items: this.serializeCollection()
                    } : {}
                },
                serializeModel: function() {
                    return this.model ? e.clone(this.model.attributes) : {}
                },
                serializeCollection: function() {
                    return this.collection ? this.collection.map(function(t) {
                        return e.clone(t.attributes)
                    }) : {}
                },
                setElement: function() {
                    return t.View.prototype.setElement.apply(this, arguments), this._isRendered = this.Dom.hasContents(this.el), this._isAttached = U(this.el), this._isRendered && this.bindUIElements(), this
                },
                render: function() {
                    return this._isDestroyed ? this : (this.triggerMethod("before:render", this), this._isRendered && this._reInitRegions(), this._renderTemplate(), this.bindUIElements(), this._isRendered = !0, this.triggerMethod("render", this), this)
                },
                _renderTemplate: function() {
                    var t = this.getTemplate();
                    if (t === !1) return void F("template:false is deprecated.  Use _.noop.");
                    var e = this.mixinTemplateContext(this.serializeData()),
                        n = this._renderHtml(t, e);
                    this.attachElContent(n)
                },
                _renderHtml: function(t, e) {
                    return bt.render(t, e, this)
                },
                getTemplate: function() {
                    return this.template
                },
                mixinTemplateContext: function() {
                    var t = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : {},
                        n = e.result(this, "templateContext");
                    return e.extend(t, n)
                },
                attachElContent: function(t) {
                    return this.Dom.setContents(this.el, t, this.$el), this
                },
                _removeChildren: function() {
                    this.removeRegions()
                },
                _getImmediateChildren: function() {
                    return e.chain(this._getRegions()).map("currentView").compact().value()
                }
            }, {
                setRenderer: function(t) {
                    return this.prototype._renderHtml = t, this
                },
                setDomApi: P
            });
        e.extend(Ct.prototype, mt, _t);
        var St = ["forEach", "each", "map", "find", "detect", "filter", "select", "reject", "every", "all", "some", "any", "include", "contains", "invoke", "toArray", "first", "initial", "rest", "last", "without", "isEmpty", "pluck", "reduce", "partition"],
            Dt = function(t, n) {
                e.each(St, function(i) {
                    t[i] = function() {
                        var t = e.result(this, n),
                            r = Array.prototype.slice.call(arguments);
                        return e[i].apply(e, [t].concat(r))
                    }
                })
            },
            Et = function(t) {
                this._views = {}, this._indexByModel = {}, this._indexByCustom = {}, this._updateLength(), e.each(t, e.bind(this.add, this))
            };
        Dt(Et.prototype, "_getViews"), e.extend(Et.prototype, {
            _getViews: function() {
                return e.values(this._views)
            },
            add: function(t, e) {
                return this._add(t, e)._updateLength()
            },
            _add: function(t, e) {
                var n = t.cid;
                return this._views[n] = t, t.model && (this._indexByModel[t.model.cid] = n), e && (this._indexByCustom[e] = n), this
            },
            findByModel: function(t) {
                return this.findByModelCid(t.cid)
            },
            findByModelCid: function(t) {
                var e = this._indexByModel[t];
                return this.findByCid(e)
            },
            findByCustom: function(t) {
                var e = this._indexByCustom[t];
                return this.findByCid(e)
            },
            findByIndex: function(t) {
                return e.values(this._views)[t]
            },
            findByCid: function(t) {
                return this._views[t]
            },
            remove: function(t) {
                return this._remove(t)._updateLength()
            },
            _remove: function(t) {
                var n = t.cid;
                return t.model && delete this._indexByModel[t.model.cid], e.some(this._indexByCustom, e.bind(function(t, e) {
                    if (t === n) return delete this._indexByCustom[e], !0
                }, this)), delete this._views[n], this
            },
            _updateLength: function() {
                return this.length = e.size(this._views), this
            }
        });
        var kt = ["behaviors", "childView", "childViewEventPrefix", "childViewEvents", "childViewOptions", "childViewTriggers", "collectionEvents", "events", "filter", "emptyView", "emptyViewOptions", "modelEvents", "reorderOnSort", "sort", "triggers", "ui", "viewComparator"],
            Tt = t.View.extend({
                sort: !0,
                constructor: function(n) {
                    this.render = e.bind(this.render, this), this._setOptions(n), this.mergeOptions(n, kt), w(this), this._initBehaviors(), this.once("render", this._initialEvents), this._initChildViewStorage(), this._bufferedChildren = [];
                    var i = Array.prototype.slice.call(arguments);
                    i[0] = this.options, t.View.prototype.constructor.apply(this, i), this.delegateEntityEvents(), this._triggerEventOnBehaviors("initialize", this)
                },
                _startBuffering: function() {
                    this._isBuffering = !0
                },
                _endBuffering: function() {
                    var t = this._isAttached && this.monitorViewEvents !== !1,
                        n = t ? this._getImmediateChildren() : [];
                    this._isBuffering = !1, e.each(n, function(t) {
                        s(t, "before:attach", t)
                    }), this.attachBuffer(this, this._createBuffer()), e.each(n, function(t) {
                        t._isAttached = !0, s(t, "attach", t)
                    }), this._bufferedChildren = []
                },
                _getImmediateChildren: function() {
                    return e.values(this.children._views)
                },
                _initialEvents: function() {
                    this.collection && (this.listenTo(this.collection, "add", this._onCollectionAdd), this.listenTo(this.collection, "update", this._onCollectionUpdate), this.listenTo(this.collection, "reset", this.render), this.sort && this.listenTo(this.collection, "sort", this._sortViews))
                },
                _onCollectionAdd: function(t, n, i) {
                    var r = void 0 !== i.at && (i.index || n.indexOf(t));
                    (this.filter || r === !1) && (r = e.indexOf(this._filteredSortedModels(r), t)), this._shouldAddChild(t, r) && (this._destroyEmptyView(), this._addChild(t, r))
                },
                _onCollectionUpdate: function(t, e) {
                    var n = e.changes;
                    this._removeChildModels(n.removed)
                },
                _removeChildModels: function(t) {
                    var e = this._getRemovedViews(t);
                    e.length && (this.children._updateLength(), this._updateIndices(e, !1), this.isEmpty() && this._showEmptyView())
                },
                _getRemovedViews: function(t) {
                    var n = this;
                    return e.reduce(t, function(t, e) {
                        var i = e && n.children.findByModel(e);
                        return !i || i._isDestroyed ? t : (n._removeChildView(i), t.push(i), t)
                    }, [])
                },
                _removeChildView: function(t) {
                    this.triggerMethod("before:remove:child", this, t), this.children._remove(t), t._shouldDisableEvents = this.monitorViewEvents === !1, V(t), this.stopListening(t), this.triggerMethod("remove:child", this, t)
                },
                setElement: function() {
                    return t.View.prototype.setElement.apply(this, arguments), this._isAttached = U(this.el), this
                },
                render: function() {
                    return this._isDestroyed ? this : (this.triggerMethod("before:render", this), this._renderChildren(), this._isRendered = !0, this.triggerMethod("render", this), this)
                },
                setFilter: function(t) {
                    var e = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {},
                        n = e.preventRender,
                        i = this._isRendered && !this._isDestroyed,
                        r = this.filter !== t,
                        s = i && r && !n;
                    if (s) {
                        var o = this._filteredSortedModels();
                        this.filter = t;
                        var a = this._filteredSortedModels();
                        this._applyModelDeltas(a, o)
                    } else this.filter = t;
                    return this
                },
                removeFilter: function(t) {
                    return this.setFilter(null, t)
                },
                _applyModelDeltas: function(t, n) {
                    var i = this,
                        r = {};
                    e.each(t, function(t, e) {
                        var n = !i.children.findByModel(t);
                        n && i._onCollectionAdd(t, i.collection, {
                            at: e
                        }), r[t.cid] = !0
                    });
                    var s = e.filter(n, function(t) {
                        return !r[t.cid] && i.children.findByModel(t)
                    });
                    this._removeChildModels(s)
                },
                reorder: function() {
                    var t = this,
                        n = this.children,
                        i = this._filteredSortedModels();
                    if (!i.length && this._showingEmptyView) return this;
                    var r = e.some(i, function(t) {
                        return !n.findByModel(t)
                    });
                    if (r) this.render();
                    else {
                        var s = [],
                            o = e.reduce(this.children._views, function(t, n) {
                                var r = e.indexOf(i, n.model);
                                return r === -1 ? (s.push(n.model), t) : (n._index = r, t[r] = n.el, t)
                            }, new Array(i.length));
                        this.triggerMethod("before:reorder", this);
                        var a = this.Dom.createBuffer();
                        e.each(o, function(e) {
                            t.Dom.appendContents(a, e)
                        }), this._appendReorderedChildren(a), this._removeChildModels(s), this.triggerMethod("reorder", this)
                    }
                    return this
                },
                resortView: function() {
                    return this.reorderOnSort ? this.reorder() : this._renderChildren(), this
                },
                _sortViews: function() {
                    var t = this,
                        n = this._filteredSortedModels(),
                        i = e.find(n, function(e, n) {
                            var i = t.children.findByModel(e);
                            return !i || i._index !== n
                        });
                    i && this.resortView()
                },
                _emptyViewIndex: -1,
                _appendReorderedChildren: function(t) {
                    this.Dom.appendContents(this.el, t, {
                        _$el: this.$el
                    })
                },
                _renderChildren: function() {
                    this._isRendered && (this._destroyEmptyView(), this._destroyChildren());
                    var t = this._filteredSortedModels();
                    this.isEmpty({
                        processedModels: t
                    }) ? this._showEmptyView() : (this.triggerMethod("before:render:children", this), this._startBuffering(), this._showCollection(t), this._endBuffering(), this.triggerMethod("render:children", this))
                },
                _createView: function(t, e) {
                    var n = this._getChildView(t),
                        i = this._getChildViewOptions(t, e),
                        r = this.buildChildView(t, n, i);
                    return r
                },
                _setupChildView: function(t, e) {
                    w(t), this._proxyChildViewEvents(t), this.sort && (t._index = e)
                },
                _showCollection: function(t) {
                    e.each(t, e.bind(this._addChild, this)), this.children._updateLength()
                },
                _filteredSortedModels: function(t) {
                    if (!this.collection || !this.collection.length) return [];
                    var e = this.getViewComparator(),
                        n = this.collection.models;
                    if (t = Math.min(Math.max(t, 0), n.length - 1), e) {
                        var i = void 0;
                        t && (i = n[t], n = n.slice(0, t).concat(n.slice(t + 1))), n = this._sortModelsBy(n, e), i && n.splice(t, 0, i)
                    }
                    return n = this._filterModels(n)
                },
                getViewComparator: function() {
                    return this.viewComparator
                },
                _filterModels: function(t) {
                    var n = this;
                    return this.filter && (t = e.filter(t, function(t, e) {
                        return n._shouldAddChild(t, e)
                    })), t
                },
                _sortModelsBy: function(t, n) {
                    return "string" == typeof n ? e.sortBy(t, function(t) {
                        return t.get(n)
                    }) : 1 === n.length ? e.sortBy(t, e.bind(n, this)) : e.clone(t).sort(e.bind(n, this))
                },
                _showEmptyView: function() {
                    var n = this._getEmptyView();
                    if (n && !this._showingEmptyView) {
                        this._showingEmptyView = !0;
                        var i = new t.Model,
                            r = this.emptyViewOptions || this.childViewOptions;
                        e.isFunction(r) && (r = r.call(this, i, this._emptyViewIndex));
                        var s = this.buildChildView(i, n, r);
                        this.triggerMethod("before:render:empty", this, s), this.addChildView(s, 0), this.triggerMethod("render:empty", this, s)
                    }
                },
                _destroyEmptyView: function() {
                    this._showingEmptyView && (this.triggerMethod("before:remove:empty", this), this._destroyChildren(), delete this._showingEmptyView, this.triggerMethod("remove:empty", this))
                },
                _getEmptyView: function() {
                    var t = this.emptyView;
                    if (t) return this._getView(t)
                },
                _getChildView: function(t) {
                    var e = this.childView;
                    if (!e) throw new J({
                        name: "NoChildViewError",
                        message: 'A "childView" must be specified'
                    });
                    if (e = this._getView(e, t), !e) throw new J({
                        name: "InvalidChildViewError",
                        message: '"childView" must be a view class or a function that returns a view class'
                    });
                    return e
                },
                _getView: function(n, i) {
                    return n.prototype instanceof t.View || n === t.View ? n : e.isFunction(n) ? n.call(this, i) : void 0
                },
                _addChild: function(t, e) {
                    var n = this._createView(t, e);
                    return this.addChildView(n, e), n
                },
                _getChildViewOptions: function(t, n) {
                    return e.isFunction(this.childViewOptions) ? this.childViewOptions(t, n) : this.childViewOptions
                },
                addChildView: function(t, e) {
                    return this.triggerMethod("before:add:child", this, t), this._setupChildView(t, e), this._isBuffering ? this.children._add(t) : (this._updateIndices(t, !0), this.children.add(t)), A(t), this._attachView(t, e), this.triggerMethod("add:child", this, t), t
                },
                _updateIndices: function(t, n) {
                    if (this.sort) {
                        if (!n) return void e.each(e.sortBy(this.children._views, "_index"), function(t, e) {
                            t._index = e
                        });
                        var i = e.isArray(t) ? e.max(t, "_index") : t;
                        e.isObject(i) && e.each(this.children._views, function(t) {
                            t._index >= i._index && (t._index += 1)
                        })
                    }
                },
                _attachView: function(t, e) {
                    var n = !t._isAttached && !this._isBuffering && this._isAttached && this.monitorViewEvents !== !1;
                    n && s(t, "before:attach", t), this.attachHtml(this, t, e), n && (t._isAttached = !0, s(t, "attach", t))
                },
                buildChildView: function(t, n, i) {
                    var r = e.extend({
                        model: t
                    }, i);
                    return new n(r)
                },
                removeChildView: function(t) {
                    return !t || t._isDestroyed ? t : (this._removeChildView(t), this.children._updateLength(), this._updateIndices(t, !1), t)
                },
                isEmpty: function(t) {
                    var n = void 0;
                    return e.result(t, "processedModels") ? n = t.processedModels : (n = this.collection ? this.collection.models : [], n = this._filterModels(n)), 0 === n.length
                },
                attachBuffer: function(t, e) {
                    this.Dom.appendContents(t.el, e, {
                        _$el: t.$el
                    })
                },
                _createBuffer: function() {
                    var t = this,
                        n = this.Dom.createBuffer();
                    return e.each(this._bufferedChildren, function(e) {
                        t.Dom.appendContents(n, e.el, {
                            _$contents: e.$el
                        })
                    }), n
                },
                attachHtml: function(t, e, n) {
                    t._isBuffering ? t._bufferedChildren.splice(n, 0, e) : t._insertBefore(e, n) || t._insertAfter(e)
                },
                _insertBefore: function(t, n) {
                    var i = void 0,
                        r = this.sort && n < this.children.length - 1;
                    return r && (i = e.find(this.children._views, function(t) {
                        return t._index === n + 1
                    })), !!i && (this.beforeEl(i.el, t.el), !0)
                },
                beforeEl: function(t, e) {
                    this.$(t).before(e)
                },
                _insertAfter: function(t) {
                    this.Dom.appendContents(this.el, t.el, {
                        _$el: this.$el,
                        _$contents: t.$el
                    })
                },
                _initChildViewStorage: function() {
                    this.children = new Et
                },
                _removeChildren: function() {
                    this._destroyChildren()
                },
                _destroyChildren: function(t) {
                    this.children.length && (this.triggerMethod("before:destroy:children", this), e.each(this.children._views, e.bind(this._removeChildView, this)), this.children._updateLength(), this.triggerMethod("destroy:children", this))
                },
                _shouldAddChild: function(t, n) {
                    var i = this.filter;
                    return !e.isFunction(i) || i.call(this, t, n, this.collection)
                }
            }, {
                setDomApi: P
            });
        e.extend(Tt.prototype, mt);
        var Ot = function() {
            this._init()
        };
        Dt(Ot.prototype, "_views"), e.extend(Ot.prototype, {
            _init: function() {
                this._views = [], this._viewsByCid = {}, this._indexByModel = {}, this._updateLength()
            },
            _add: function(t) {
                var e = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : this._views.length,
                    n = t.cid;
                this._viewsByCid[n] = t, t.model && (this._indexByModel[t.model.cid] = n), this._views.splice(e, 0, t), this._updateLength()
            },
            _sort: function(t, n) {
                return "string" == typeof t ? (t = e.partial(j, t), this._sortBy(t)) : 1 === t.length ? this._sortBy(e.bind(t, n)) : this._views.sort(e.bind(t, n))
            },
            _sortBy: function(t) {
                var n = e.sortBy(this._views, t);
                return this._set(n), n
            },
            _set: function(t) {
                this._views.length = 0, this._views.push.apply(this._views, t.slice(0)), this._updateLength()
            },
            _swap: function(t, e) {
                var n = this.findIndexByView(t),
                    i = this.findIndexByView(e);
                if (n !== -1 && i !== -1) {
                    var r = this._views[n];
                    this._views[n] = this._views[i], this._views[i] = r
                }
            },
            findByModel: function(t) {
                return this.findByModelCid(t.cid)
            },
            findByModelCid: function(t) {
                var e = this._indexByModel[t];
                return this.findByCid(e)
            },
            findByIndex: function(t) {
                return this._views[t]
            },
            findIndexByView: function(t) {
                return this._views.indexOf(t)
            },
            findByCid: function(t) {
                return this._viewsByCid[t]
            },
            hasView: function(t) {
                return !!this.findByCid(t.cid)
            },
            _remove: function(t) {
                if (this._viewsByCid[t.cid]) {
                    t.model && delete this._indexByModel[t.model.cid], delete this._viewsByCid[t.cid];
                    var e = this.findIndexByView(t);
                    this._views.splice(e, 1), this._updateLength()
                }
            },
            _updateLength: function() {
                this.length = this._views.length
            }
        });
        var Mt = ["behaviors", "childView", "childViewEventPrefix", "childViewEvents", "childViewOptions", "childViewTriggers", "collectionEvents", "emptyView", "emptyViewOptions", "events", "modelEvents", "sortWithCollection", "triggers", "ui", "viewComparator", "viewFilter"],
            Rt = t.View.extend({
                sortWithCollection: !0,
                constructor: function(e) {
                    this._setOptions(e), this.mergeOptions(e, Mt), w(this), this.once("render", this._initialEvents), this._initChildViewStorage(), this._initBehaviors();
                    var n = Array.prototype.slice.call(arguments);
                    n[0] = this.options, t.View.prototype.constructor.apply(this, n), this.getEmptyRegion(), this.delegateEntityEvents(), this._triggerEventOnBehaviors("initialize", this)
                },
                _initChildViewStorage: function() {
                    this.children = new Ot
                },
                getEmptyRegion: function() {
                    return this._emptyRegion && !this._emptyRegion.isDestroyed() ? this._emptyRegion : (this._emptyRegion = new yt({
                        el: this.el,
                        replaceElement: !1
                    }), this._emptyRegion._parentView = this, this._emptyRegion)
                },
                _initialEvents: function() {
                    this.listenTo(this.collection, {
                        sort: this._onCollectionSort,
                        reset: this._onCollectionReset,
                        update: this._onCollectionUpdate
                    })
                },
                _onCollectionSort: function(t, e) {
                    var n = e.add,
                        i = e.merge,
                        r = e.remove;
                    this.sortWithCollection && this.viewComparator !== !1 && (n || r || i || this.sort())
                },
                _onCollectionReset: function() {
                    this.render()
                },
                _onCollectionUpdate: function(t, e) {
                    var n = e.changes,
                        i = n.removed.length && this._removeChildModels(n.removed);
                    this._addedViews = n.added.length && this._addChildModels(n.added), this._detachChildren(i), this._showChildren(), this._removeChildViews(i)
                },
                _removeChildModels: function(t) {
                    var n = this;
                    return e.reduce(t, function(t, e) {
                        var i = n._removeChildModel(e);
                        return i && t.push(i), t
                    }, [])
                },
                _removeChildModel: function(t) {
                    var e = this.children.findByModel(t);
                    return e && this._removeChild(e), e
                },
                _removeChild: function(t) {
                    this.triggerMethod("before:remove:child", this, t), this.children._remove(t), this.triggerMethod("remove:child", this, t)
                },
                _addChildModels: function(t) {
                    return e.map(t, e.bind(this._addChildModel, this))
                },
                _addChildModel: function(t) {
                    var e = this._createChildView(t);
                    return this._addChild(e), e
                },
                _createChildView: function(t) {
                    var e = this._getChildView(t),
                        n = this._getChildViewOptions(t),
                        i = this.buildChildView(t, e, n);
                    return i
                },
                _addChild: function(t, e) {
                    this.triggerMethod("before:add:child", this, t), this._setupChildView(t), this.children._add(t, e), this.triggerMethod("add:child", this, t)
                },
                _getChildView: function(t) {
                    var e = this.childView;
                    if (!e) throw new J({
                        name: "NoChildViewError",
                        message: 'A "childView" must be specified'
                    });
                    if (e = this._getView(e, t), !e) throw new J({
                        name: "InvalidChildViewError",
                        message: '"childView" must be a view class or a function that returns a view class'
                    });
                    return e
                },
                _getView: function(n, i) {
                    return n.prototype instanceof t.View || n === t.View ? n : e.isFunction(n) ? n.call(this, i) : void 0
                },
                _getChildViewOptions: function(t) {
                    return e.isFunction(this.childViewOptions) ? this.childViewOptions(t) : this.childViewOptions
                },
                buildChildView: function(t, n, i) {
                    var r = e.extend({
                        model: t
                    }, i);
                    return new n(r)
                },
                _setupChildView: function(t) {
                    w(t), t.on("destroy", this.removeChildView, this), this._proxyChildViewEvents(t)
                },
                _getImmediateChildren: function() {
                    return this.children._views
                },
                setElement: function() {
                    return t.View.prototype.setElement.apply(this, arguments), this._isAttached = U(this.el), this
                },
                render: function() {
                    return this._isDestroyed ? this : (this.triggerMethod("before:render", this), this._destroyChildren(), this.children._init(), this.collection && this._addChildModels(this.collection.models), this._showChildren(), this._isRendered = !0, this.triggerMethod("render", this), this)
                },
                sort: function() {
                    return this._isDestroyed ? this : this.children.length ? (this._showChildren(), this) : this
                },
                _showChildren: function() {
                    return this.isEmpty() ? void this._showEmptyView() : (this._sortChildren(), void this.filter())
                },
                isEmpty: function(t) {
                    return t || !this.children.length
                },
                _showEmptyView: function() {
                    var t = this._getEmptyView();
                    if (t) {
                        var e = this._getEmptyViewOptions(),
                            n = this.getEmptyRegion();
                        n.show(new t(e))
                    }
                },
                _getEmptyView: function() {
                    var t = this.emptyView;
                    if (t) return this._getView(t)
                },
                _destroyEmptyView: function() {
                    var t = this.getEmptyRegion();
                    t.hasView() && t.empty()
                },
                _getEmptyViewOptions: function() {
                    var t = this.emptyViewOptions || this.childViewOptions;
                    return e.isFunction(t) ? t.call(this) : t
                },
                _sortChildren: function() {
                    var t = this.getComparator();
                    t && (delete this._addedViews, this.triggerMethod("before:sort", this), this.children._sort(t, this), this.triggerMethod("sort", this))
                },
                setComparator: function(t) {
                    var e = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {},
                        n = e.preventRender,
                        i = this.viewComparator !== t,
                        r = i && !n;
                    return this.viewComparator = t, r && this.sort(), this
                },
                removeComparator: function(t) {
                    return this.setComparator(null, t)
                },
                getComparator: function() {
                    return this.viewComparator ? this.viewComparator : !(!this.sortWithCollection || this.viewComparator === !1 || !this.collection) && this._viewComparator
                },
                _viewComparator: function(t) {
                    return this.collection.indexOf(t.model)
                },
                filter: function() {
                    if (this._isDestroyed) return this;
                    if (!this.children.length) return this;
                    var t = this._filterChildren();
                    return this._renderChildren(t), this
                },
                _filterChildren: function() {
                    var t = this,
                        n = this._getFilter(),
                        i = this._addedViews;
                    if (delete this._addedViews, !n) return i ? i : this.children._views;
                    this.triggerMethod("before:filter", this);
                    var r = [],
                        s = [];
                    return e.each(this.children._views, function(e, i, o) {
                        (n.call(t, e, i, o) ? r : s).push(e)
                    }), this._detachChildren(s), this.triggerMethod("filter", this, r, s), r
                },
                _getFilter: function() {
                    var t = this.getFilter();
                    if (!t) return !1;
                    if (e.isFunction(t)) return t;
                    if (e.isObject(t)) {
                        var n = e.matches(t);
                        return function(t) {
                            return n(t.model && t.model.attributes)
                        }
                    }
                    if (e.isString(t)) return function(e) {
                        return e.model && e.model.get(t)
                    };
                    throw new J({
                        name: "InvalidViewFilterError",
                        message: '"viewFilter" must be a function, predicate object literal, a string indicating a model attribute, or falsy'
                    })
                },
                getFilter: function() {
                    return this.viewFilter
                },
                setFilter: function(t) {
                    var e = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {},
                        n = e.preventRender,
                        i = this.viewFilter !== t,
                        r = i && !n;
                    return this.viewFilter = t, r && this.filter(), this
                },
                removeFilter: function(t) {
                    return this.setFilter(null, t)
                },
                _detachChildren: function(t) {
                    e.each(t, e.bind(this._detachChildView, this))
                },
                _detachChildView: function(t) {
                    var e = t._isAttached && this.monitorViewEvents !== !1;
                    e && s(t, "before:detach", t), this.detachHtml(t), e && (t._isAttached = !1, s(t, "detach", t))
                },
                detachHtml: function(t) {
                    this.Dom.detachEl(t.el, t.$el)
                },
                _renderChildren: function(t) {
                    if (this.isEmpty(!t.length)) return void this._showEmptyView();
                    this._destroyEmptyView(), this.triggerMethod("before:render:children", this, t);
                    var e = this._getBuffer(t);
                    this._attachChildren(e, t), this.triggerMethod("render:children", this, t)
                },
                _attachChildren: function(t, n) {
                    var i = this._isAttached && this.monitorViewEvents !== !1;
                    n = i ? n : [], e.each(n, function(t) {
                        t._isAttached || s(t, "before:attach", t)
                    }), this.attachHtml(t), e.each(n, function(t) {
                        t._isAttached || (t._isAttached = !0, s(t, "attach", t))
                    })
                },
                _getBuffer: function(t) {
                    var n = this,
                        i = this.Dom.createBuffer();
                    return e.each(t, function(t) {
                        A(t), n.Dom.appendContents(i, t.el, {
                            _$contents: t.$el
                        })
                    }), i
                },
                attachHtml: function(t) {
                    this.Dom.appendContents(this.el, t, {
                        _$el: this.$el
                    })
                },
                swapChildViews: function(t, e) {
                    if (!this.children.hasView(t) || !this.children.hasView(e)) throw new J({
                        name: "ChildSwapError",
                        message: "Both views must be children of the collection view"
                    });
                    return this.children._swap(t, e), this.Dom.swapEl(t.el, e.el), this.Dom.hasEl(this.el, t.el) !== this.Dom.hasEl(this.el, e.el) && this.filter(), this
                },
                addChildView: function(t, e) {
                    return !t || t._isDestroyed ? t : ((!e || e >= this.children.length) && (this._addedViews = [t]), this._addChild(t, e), this._showChildren(), t)
                },
                detachChildView: function(t) {
                    return this.removeChildView(t, {
                        shouldDetach: !0
                    }), t
                },
                removeChildView: function(t, e) {
                    return t ? (this._removeChildView(t, e), this._removeChild(t), this.isEmpty() && this._showEmptyView(), t) : t
                },
                _removeChildViews: function(t) {
                    e.each(t, e.bind(this._removeChildView, this))
                },
                _removeChildView: function(t) {
                    var e = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {},
                        n = e.shouldDetach;
                    t.off("destroy", this.removeChildView, this), n ? this._detachChildView(t) : this._destroyChildView(t), this.stopListening(t)
                },
                _destroyChildView: function(t) {
                    t._isDestroyed || (t._shouldDisableEvents = this.monitorViewEvents === !1, V(t))
                },
                _removeChildren: function() {
                    this._destroyChildren();
                    var t = this.getEmptyRegion();
                    t.destroy(), delete this._addedViews
                },
                _destroyChildren: function() {
                    this.children && this.children.length && (this.triggerMethod("before:destroy:children", this), this.monitorViewEvents === !1 && this.Dom.detachContents(this.el, this.$el), e.each(this.children._views, e.bind(this._removeChildView, this)), this.triggerMethod("destroy:children", this))
                }
            }, {
                setDomApi: P
            });
        e.extend(Rt.prototype, mt);
        var $t = ["childViewContainer", "template", "templateContext"],
            Pt = Tt.extend({
                constructor: function(t) {
                    F("CompositeView is deprecated. Convert to View at your earliest convenience"), this.mergeOptions(t, $t), Tt.prototype.constructor.apply(this, arguments)
                },
                _initialEvents: function() {
                    this.collection && (this.listenTo(this.collection, "add", this._onCollectionAdd), this.listenTo(this.collection, "update", this._onCollectionUpdate), this.listenTo(this.collection, "reset", this.renderChildren), this.sort && this.listenTo(this.collection, "sort", this._sortViews))
                },
                _getChildView: function(t) {
                    var e = this.childView;
                    if (!e) return this.constructor;
                    if (e = this._getView(e, t), !e) throw new J({
                        name: "InvalidChildViewError",
                        message: '"childView" must be a view class or a function that returns a view class'
                    });
                    return e
                },
                serializeData: function() {
                    return this.serializeModel()
                },
                render: function() {
                    return this._isDestroyed ? this : (this._isRendering = !0, this.resetChildViewContainer(), this.triggerMethod("before:render", this), this._renderTemplate(), this.bindUIElements(), this.renderChildren(), this._isRendering = !1, this._isRendered = !0, this.triggerMethod("render", this), this)
                },
                renderChildren: function() {
                    (this._isRendered || this._isRendering) && Tt.prototype._renderChildren.call(this)
                },
                attachBuffer: function(t, e) {
                    var n = this.getChildViewContainer(t);
                    this.Dom.appendContents(n[0], e, {
                        _$el: n
                    })
                },
                _insertAfter: function(t) {
                    var e = this.getChildViewContainer(this, t);
                    this.Dom.appendContents(e[0], t.el, {
                        _$el: e,
                        _$contents: t.$el
                    })
                },
                _appendReorderedChildren: function(t) {
                    var e = this.getChildViewContainer(this);
                    this.Dom.appendContents(e[0], t, {
                        _$el: e
                    })
                },
                getChildViewContainer: function(t, n) {
                    if (t.$childViewContainer) return t.$childViewContainer;
                    var i = void 0,
                        r = t.childViewContainer;
                    if (r) {
                        var s = e.result(t, "childViewContainer");
                        if (i = "@" === s.charAt(0) && t.ui ? t.ui[s.substr(4)] : this.$(s), i.length <= 0) throw new J({
                            name: "ChildViewContainerMissingError",
                            message: 'The specified "childViewContainer" was not found: ' + t.childViewContainer
                        })
                    } else i = t.$el;
                    return t.$childViewContainer = i, i
                },
                resetChildViewContainer: function() {
                    this.$childViewContainer && (this.$childViewContainer = void 0)
                }
            }),
            At = e.pick(Ct.prototype, "serializeModel", "getTemplate", "_renderTemplate", "_renderHtml", "mixinTemplateContext", "attachElContent");
        e.extend(Pt.prototype, At);
        var Vt = ["collectionEvents", "events", "modelEvents", "triggers", "ui"],
            Nt = nt.extend({
                cidPrefix: "mnb",
                constructor: function(t, n) {
                    this.view = n, this.defaults && F("Behavior defaults are deprecated. For similar functionality set options on the Behavior class."), this.defaults = e.clone(e.result(this, "defaults", {})), this._setOptions(e.extend({}, this.defaults, t)), this.mergeOptions(this.options, Vt), this.ui = e.extend({}, e.result(this, "ui"), e.result(n, "ui")), nt.apply(this, arguments)
                },
                $: function() {
                    return this.view.$.apply(this.view, arguments)
                },
                destroy: function() {
                    return this.stopListening(), this.view._removeBehavior(this), this
                },
                proxyViewProperties: function() {
                    return this.$el = this.view.$el, this.el = this.view.el, this
                },
                bindUIElements: function() {
                    return this._bindUIElements(), this
                },
                unbindUIElements: function() {
                    return this._unbindUIElements(), this
                },
                getUI: function(t) {
                    return this._getUI(t)
                },
                delegateEntityEvents: function() {
                    return this._delegateEntityEvents(this.view.model, this.view.collection), this
                },
                undelegateEntityEvents: function() {
                    return this._undelegateEntityEvents(this.view.model, this.view.collection), this
                },
                getEvents: function() {
                    var t = this,
                        n = this.normalizeUIKeys(e.result(this, "events"));
                    return e.reduce(n, function(n, i, r) {
                        return e.isFunction(i) || (i = t[i]), i ? (r = lt(r, t.cid), n[r] = e.bind(i, t), n) : n
                    }, {})
                },
                getTriggers: function() {
                    if (this.triggers) {
                        var t = this.normalizeUIKeys(e.result(this, "triggers"));
                        return this._getViewTriggers(this.view, t)
                    }
                }
            });
        e.extend(Nt.prototype, ot, ut, pt);
        var It = ["region", "regionClass"],
            jt = nt.extend({
                cidPrefix: "mna",
                constructor: function(t) {
                    this._setOptions(t), this.mergeOptions(t, It), this._initRegion(), nt.prototype.constructor.apply(this, arguments)
                },
                regionClass: yt,
                _initRegion: function() {
                    var t = this.region;
                    if (t) {
                        var e = {
                            regionClass: this.regionClass
                        };
                        this._region = wt(t, e)
                    }
                },
                getRegion: function() {
                    return this._region
                },
                showView: function(t) {
                    for (var e = this.getRegion(), n = arguments.length, i = Array(n > 1 ? n - 1 : 0), r = 1; r < n; r++) i[r - 1] = arguments[r];
                    return e.show.apply(e, [t].concat(i))
                },
                getView: function() {
                    return this.getRegion().currentView
                },
                start: function(t) {
                    return this.triggerMethod("before:start", this, t), this.triggerMethod("start", this, t), this
                }
            }),
            Lt = ["appRoutes", "controller"],
            Bt = t.Router.extend({
                constructor: function(e) {
                    this._setOptions(e), this.mergeOptions(e, Lt), t.Router.apply(this, arguments);
                    var n = this.appRoutes,
                        i = this._getController();
                    this.processAppRoutes(i, n), this.on("route", this._processOnRoute, this)
                },
                appRoute: function(t, e) {
                    var n = this._getController();
                    return this._addAppRoute(n, t, e), this
                },
                _processOnRoute: function(t, n) {
                    if (e.isFunction(this.onRoute)) {
                        var i = e.invert(this.appRoutes)[t];
                        this.onRoute(t, i, n)
                    }
                },
                processAppRoutes: function(t, n) {
                    var i = this;
                    if (!n) return this;
                    var r = e.keys(n).reverse();
                    return e.each(r, function(e) {
                        i._addAppRoute(t, e, n[e])
                    }), this
                },
                _getController: function() {
                    return this.controller
                },
                _addAppRoute: function(t, n, i) {
                    var r = t[i];
                    if (!r) throw new J('Method "' + i + '" was not found on the controller');
                    this.route(n, i, e.bind(r, t))
                },
                triggerMethod: r
            });
        e.extend(Bt.prototype, X);
        var Ht = t.Marionette,
            Yt = t.Marionette = {};
        return Yt.noConflict = function() {
            return t.Marionette = Ht, this
        }, Yt.bindEvents = H(x), Yt.unbindEvents = H(C), Yt.bindRequests = H(D), Yt.unbindRequests = H(E), Yt.mergeOptions = H(q), Yt.getOption = H(W), Yt.normalizeMethods = H(z), Yt.extend = Y, Yt.isNodeAttached = U, Yt.deprecate = F, Yt.triggerMethod = H(r), Yt.triggerMethodOn = s, Yt.isEnabled = O, Yt.setEnabled = M, Yt.monitorViewEvents = w, Yt.Behaviors = {}, Yt.Behaviors.behaviorsLookup = L, Yt.Application = jt, Yt.AppRouter = Bt, Yt.Renderer = bt, Yt.TemplateCache = it, Yt.View = Ct, Yt.CollectionView = Tt, Yt.NextCollectionView = Rt, Yt.CompositeView = Pt, Yt.Behavior = Nt, Yt.Region = yt, Yt.Error = J, Yt.Object = nt, Yt.DEV_MODE = !1, Yt.FEATURES = ht, Yt.VERSION = B, Yt.DomApi = gt, Yt.setDomApi = function(t) {
            Tt.setDomApi(t), Pt.setDomApi(t), Rt.setDomApi(t), yt.setDomApi(t), Ct.setDomApi(t)
        }, Yt
    }), this && this.Marionette && (this.Mn = this.Marionette), function(t, e) {
        "object" == typeof exports && "undefined" != typeof module ? module.exports = e() : "function" == typeof define && define.amd ? define(e) : t.moment = e()
    }(this, function() {
        "use strict";

        function t() {
            return ki.apply(null, arguments)
        }

        function e(t) {
            ki = t
        }

        function n(t) {
            return t instanceof Array || "[object Array]" === Object.prototype.toString.call(t);
        }

        function i(t) {
            return null != t && "[object Object]" === Object.prototype.toString.call(t)
        }

        function r(t) {
            if (Object.getOwnPropertyNames) return 0 === Object.getOwnPropertyNames(t).length;
            var e;
            for (e in t)
                if (t.hasOwnProperty(e)) return !1;
            return !0
        }

        function s(t) {
            return void 0 === t
        }

        function o(t) {
            return "number" == typeof t || "[object Number]" === Object.prototype.toString.call(t)
        }

        function a(t) {
            return t instanceof Date || "[object Date]" === Object.prototype.toString.call(t)
        }

        function l(t, e) {
            var n, i = [];
            for (n = 0; n < t.length; ++n) i.push(e(t[n], n));
            return i
        }

        function h(t, e) {
            return Object.prototype.hasOwnProperty.call(t, e)
        }

        function u(t, e) {
            for (var n in e) h(e, n) && (t[n] = e[n]);
            return h(e, "toString") && (t.toString = e.toString), h(e, "valueOf") && (t.valueOf = e.valueOf), t
        }

        function c(t, e, n, i) {
            return Se(t, e, n, i, !0).utc()
        }

        function d() {
            return {
                empty: !1,
                unusedTokens: [],
                unusedInput: [],
                overflow: -2,
                charsLeftOver: 0,
                nullInput: !1,
                invalidMonth: null,
                invalidFormat: !1,
                userInvalidated: !1,
                iso: !1,
                parsedDateParts: [],
                meridiem: null,
                rfc2822: !1,
                weekdayMismatch: !1
            }
        }

        function f(t) {
            return null == t._pf && (t._pf = d()), t._pf
        }

        function p(t) {
            if (null == t._isValid) {
                var e = f(t),
                    n = Ti.call(e.parsedDateParts, function(t) {
                        return null != t
                    }),
                    i = !isNaN(t._d.getTime()) && e.overflow < 0 && !e.empty && !e.invalidMonth && !e.invalidWeekday && !e.weekdayMismatch && !e.nullInput && !e.invalidFormat && !e.userInvalidated && (!e.meridiem || e.meridiem && n);
                if (t._strict && (i = i && 0 === e.charsLeftOver && 0 === e.unusedTokens.length && void 0 === e.bigHour), null != Object.isFrozen && Object.isFrozen(t)) return i;
                t._isValid = i
            }
            return t._isValid
        }

        function g(t) {
            var e = c(NaN);
            return null != t ? u(f(e), t) : f(e).userInvalidated = !0, e
        }

        function m(t, e) {
            var n, i, r;
            if (s(e._isAMomentObject) || (t._isAMomentObject = e._isAMomentObject), s(e._i) || (t._i = e._i), s(e._f) || (t._f = e._f), s(e._l) || (t._l = e._l), s(e._strict) || (t._strict = e._strict), s(e._tzm) || (t._tzm = e._tzm), s(e._isUTC) || (t._isUTC = e._isUTC), s(e._offset) || (t._offset = e._offset), s(e._pf) || (t._pf = f(e)), s(e._locale) || (t._locale = e._locale), Oi.length > 0)
                for (n = 0; n < Oi.length; n++) i = Oi[n], r = e[i], s(r) || (t[i] = r);
            return t
        }

        function v(e) {
            m(this, e), this._d = new Date(null != e._d ? e._d.getTime() : NaN), this.isValid() || (this._d = new Date(NaN)), Mi === !1 && (Mi = !0, t.updateOffset(this), Mi = !1)
        }

        function y(t) {
            return t instanceof v || null != t && null != t._isAMomentObject
        }

        function w(t) {
            return t < 0 ? Math.ceil(t) || 0 : Math.floor(t)
        }

        function _(t) {
            var e = +t,
                n = 0;
            return 0 !== e && isFinite(e) && (n = w(e)), n
        }

        function b(t, e, n) {
            var i, r = Math.min(t.length, e.length),
                s = Math.abs(t.length - e.length),
                o = 0;
            for (i = 0; i < r; i++)(n && t[i] !== e[i] || !n && _(t[i]) !== _(e[i])) && o++;
            return o + s
        }

        function x(e) {
            t.suppressDeprecationWarnings === !1 && "undefined" != typeof console && console.warn && console.warn("Deprecation warning: " + e)
        }

        function C(e, n) {
            var i = !0;
            return u(function() {
                if (null != t.deprecationHandler && t.deprecationHandler(null, e), i) {
                    for (var r, s = [], o = 0; o < arguments.length; o++) {
                        if (r = "", "object" == typeof arguments[o]) {
                            r += "\n[" + o + "] ";
                            for (var a in arguments[0]) r += a + ": " + arguments[0][a] + ", ";
                            r = r.slice(0, -2)
                        } else r = arguments[o];
                        s.push(r)
                    }
                    x(e + "\nArguments: " + Array.prototype.slice.call(s).join("") + "\n" + (new Error).stack), i = !1
                }
                return n.apply(this, arguments)
            }, n)
        }

        function S(e, n) {
            null != t.deprecationHandler && t.deprecationHandler(e, n), Ri[e] || (x(n), Ri[e] = !0)
        }

        function D(t) {
            return t instanceof Function || "[object Function]" === Object.prototype.toString.call(t)
        }

        function E(t) {
            var e, n;
            for (n in t) e = t[n], D(e) ? this[n] = e : this["_" + n] = e;
            this._config = t, this._dayOfMonthOrdinalParseLenient = new RegExp((this._dayOfMonthOrdinalParse.source || this._ordinalParse.source) + "|" + /\d{1,2}/.source)
        }

        function k(t, e) {
            var n, r = u({}, t);
            for (n in e) h(e, n) && (i(t[n]) && i(e[n]) ? (r[n] = {}, u(r[n], t[n]), u(r[n], e[n])) : null != e[n] ? r[n] = e[n] : delete r[n]);
            for (n in t) h(t, n) && !h(e, n) && i(t[n]) && (r[n] = u({}, r[n]));
            return r
        }

        function T(t) {
            null != t && this.set(t)
        }

        function O(t, e, n) {
            var i = this._calendar[t] || this._calendar.sameElse;
            return D(i) ? i.call(e, n) : i
        }

        function M(t) {
            var e = this._longDateFormat[t],
                n = this._longDateFormat[t.toUpperCase()];
            return e || !n ? e : (this._longDateFormat[t] = n.replace(/MMMM|MM|DD|dddd/g, function(t) {
                return t.slice(1)
            }), this._longDateFormat[t])
        }

        function R() {
            return this._invalidDate
        }

        function $(t) {
            return this._ordinal.replace("%d", t)
        }

        function P(t, e, n, i) {
            var r = this._relativeTime[n];
            return D(r) ? r(t, e, n, i) : r.replace(/%d/i, t)
        }

        function A(t, e) {
            var n = this._relativeTime[t > 0 ? "future" : "past"];
            return D(n) ? n(e) : n.replace(/%s/i, e)
        }

        function V(t, e) {
            var n = t.toLowerCase();
            Li[n] = Li[n + "s"] = Li[e] = t
        }

        function N(t) {
            return "string" == typeof t ? Li[t] || Li[t.toLowerCase()] : void 0
        }

        function I(t) {
            var e, n, i = {};
            for (n in t) h(t, n) && (e = N(n), e && (i[e] = t[n]));
            return i
        }

        function j(t, e) {
            Bi[t] = e
        }

        function L(t) {
            var e = [];
            for (var n in t) e.push({
                unit: n,
                priority: Bi[n]
            });
            return e.sort(function(t, e) {
                return t.priority - e.priority
            }), e
        }

        function B(t, e, n) {
            var i = "" + Math.abs(t),
                r = e - i.length,
                s = t >= 0;
            return (s ? n ? "+" : "" : "-") + Math.pow(10, Math.max(0, r)).toString().substr(1) + i
        }

        function H(t, e, n, i) {
            var r = i;
            "string" == typeof i && (r = function() {
                return this[i]()
            }), t && (Ui[t] = r), e && (Ui[e[0]] = function() {
                return B(r.apply(this, arguments), e[1], e[2])
            }), n && (Ui[n] = function() {
                return this.localeData().ordinal(r.apply(this, arguments), t)
            })
        }

        function Y(t) {
            return t.match(/\[[\s\S]/) ? t.replace(/^\[|\]$/g, "") : t.replace(/\\/g, "")
        }

        function F(t) {
            var e, n, i = t.match(Hi);
            for (e = 0, n = i.length; e < n; e++) Ui[i[e]] ? i[e] = Ui[i[e]] : i[e] = Y(i[e]);
            return function(e) {
                var r, s = "";
                for (r = 0; r < n; r++) s += D(i[r]) ? i[r].call(e, t) : i[r];
                return s
            }
        }

        function U(t, e) {
            return t.isValid() ? (e = q(e, t.localeData()), Fi[e] = Fi[e] || F(e), Fi[e](t)) : t.localeData().invalidDate()
        }

        function q(t, e) {
            function n(t) {
                return e.longDateFormat(t) || t
            }
            var i = 5;
            for (Yi.lastIndex = 0; i >= 0 && Yi.test(t);) t = t.replace(Yi, n), Yi.lastIndex = 0, i -= 1;
            return t
        }

        function W(t, e, n) {
            lr[t] = D(e) ? e : function(t, i) {
                return t && n ? n : e
            }
        }

        function z(t, e) {
            return h(lr, t) ? lr[t](e._strict, e._locale) : new RegExp(G(t))
        }

        function G(t) {
            return Q(t.replace("\\", "").replace(/\\(\[)|\\(\])|\[([^\]\[]*)\]|\\(.)/g, function(t, e, n, i, r) {
                return e || n || i || r
            }))
        }

        function Q(t) {
            return t.replace(/[-\/\\^$*+?.()|[\]{}]/g, "\\$&")
        }

        function Z(t, e) {
            var n, i = e;
            for ("string" == typeof t && (t = [t]), o(e) && (i = function(t, n) {
                    n[e] = _(t)
                }), n = 0; n < t.length; n++) hr[t[n]] = i
        }

        function J(t, e) {
            Z(t, function(t, n, i, r) {
                i._w = i._w || {}, e(t, i._w, i, r)
            })
        }

        function K(t, e, n) {
            null != e && h(hr, t) && hr[t](e, n._a, n, t)
        }

        function X(t) {
            return tt(t) ? 366 : 365
        }

        function tt(t) {
            return t % 4 === 0 && t % 100 !== 0 || t % 400 === 0
        }

        function et() {
            return tt(this.year())
        }

        function nt(e, n) {
            return function(i) {
                return null != i ? (rt(this, e, i), t.updateOffset(this, n), this) : it(this, e)
            }
        }

        function it(t, e) {
            return t.isValid() ? t._d["get" + (t._isUTC ? "UTC" : "") + e]() : NaN
        }

        function rt(t, e, n) {
            t.isValid() && !isNaN(n) && ("FullYear" === e && tt(t.year()) && 1 === t.month() && 29 === t.date() ? t._d["set" + (t._isUTC ? "UTC" : "") + e](n, t.month(), lt(n, t.month())) : t._d["set" + (t._isUTC ? "UTC" : "") + e](n))
        }

        function st(t) {
            return t = N(t), D(this[t]) ? this[t]() : this
        }

        function ot(t, e) {
            if ("object" == typeof t) {
                t = I(t);
                for (var n = L(t), i = 0; i < n.length; i++) this[n[i].unit](t[n[i].unit])
            } else if (t = N(t), D(this[t])) return this[t](e);
            return this
        }

        function at(t, e) {
            return (t % e + e) % e
        }

        function lt(t, e) {
            if (isNaN(t) || isNaN(e)) return NaN;
            var n = at(e, 12);
            return t += (e - n) / 12, 1 === n ? tt(t) ? 29 : 28 : 31 - n % 7 % 2
        }

        function ht(t, e) {
            return t ? n(this._months) ? this._months[t.month()] : this._months[(this._months.isFormat || br).test(e) ? "format" : "standalone"][t.month()] : n(this._months) ? this._months : this._months.standalone
        }

        function ut(t, e) {
            return t ? n(this._monthsShort) ? this._monthsShort[t.month()] : this._monthsShort[br.test(e) ? "format" : "standalone"][t.month()] : n(this._monthsShort) ? this._monthsShort : this._monthsShort.standalone
        }

        function ct(t, e, n) {
            var i, r, s, o = t.toLocaleLowerCase();
            if (!this._monthsParse)
                for (this._monthsParse = [], this._longMonthsParse = [], this._shortMonthsParse = [], i = 0; i < 12; ++i) s = c([2e3, i]), this._shortMonthsParse[i] = this.monthsShort(s, "").toLocaleLowerCase(), this._longMonthsParse[i] = this.months(s, "").toLocaleLowerCase();
            return n ? "MMM" === e ? (r = wr.call(this._shortMonthsParse, o), r !== -1 ? r : null) : (r = wr.call(this._longMonthsParse, o), r !== -1 ? r : null) : "MMM" === e ? (r = wr.call(this._shortMonthsParse, o), r !== -1 ? r : (r = wr.call(this._longMonthsParse, o), r !== -1 ? r : null)) : (r = wr.call(this._longMonthsParse, o), r !== -1 ? r : (r = wr.call(this._shortMonthsParse, o), r !== -1 ? r : null))
        }

        function dt(t, e, n) {
            var i, r, s;
            if (this._monthsParseExact) return ct.call(this, t, e, n);
            for (this._monthsParse || (this._monthsParse = [], this._longMonthsParse = [], this._shortMonthsParse = []), i = 0; i < 12; i++) {
                if (r = c([2e3, i]), n && !this._longMonthsParse[i] && (this._longMonthsParse[i] = new RegExp("^" + this.months(r, "").replace(".", "") + "$", "i"), this._shortMonthsParse[i] = new RegExp("^" + this.monthsShort(r, "").replace(".", "") + "$", "i")), n || this._monthsParse[i] || (s = "^" + this.months(r, "") + "|^" + this.monthsShort(r, ""), this._monthsParse[i] = new RegExp(s.replace(".", ""), "i")), n && "MMMM" === e && this._longMonthsParse[i].test(t)) return i;
                if (n && "MMM" === e && this._shortMonthsParse[i].test(t)) return i;
                if (!n && this._monthsParse[i].test(t)) return i
            }
        }

        function ft(t, e) {
            var n;
            if (!t.isValid()) return t;
            if ("string" == typeof e)
                if (/^\d+$/.test(e)) e = _(e);
                else if (e = t.localeData().monthsParse(e), !o(e)) return t;
            return n = Math.min(t.date(), lt(t.year(), e)), t._d["set" + (t._isUTC ? "UTC" : "") + "Month"](e, n), t
        }

        function pt(e) {
            return null != e ? (ft(this, e), t.updateOffset(this, !0), this) : it(this, "Month")
        }

        function gt() {
            return lt(this.year(), this.month())
        }

        function mt(t) {
            return this._monthsParseExact ? (h(this, "_monthsRegex") || yt.call(this), t ? this._monthsShortStrictRegex : this._monthsShortRegex) : (h(this, "_monthsShortRegex") || (this._monthsShortRegex = Sr), this._monthsShortStrictRegex && t ? this._monthsShortStrictRegex : this._monthsShortRegex)
        }

        function vt(t) {
            return this._monthsParseExact ? (h(this, "_monthsRegex") || yt.call(this), t ? this._monthsStrictRegex : this._monthsRegex) : (h(this, "_monthsRegex") || (this._monthsRegex = Dr), this._monthsStrictRegex && t ? this._monthsStrictRegex : this._monthsRegex)
        }

        function yt() {
            function t(t, e) {
                return e.length - t.length
            }
            var e, n, i = [],
                r = [],
                s = [];
            for (e = 0; e < 12; e++) n = c([2e3, e]), i.push(this.monthsShort(n, "")), r.push(this.months(n, "")), s.push(this.months(n, "")), s.push(this.monthsShort(n, ""));
            for (i.sort(t), r.sort(t), s.sort(t), e = 0; e < 12; e++) i[e] = Q(i[e]), r[e] = Q(r[e]);
            for (e = 0; e < 24; e++) s[e] = Q(s[e]);
            this._monthsRegex = new RegExp("^(" + s.join("|") + ")", "i"), this._monthsShortRegex = this._monthsRegex, this._monthsStrictRegex = new RegExp("^(" + r.join("|") + ")", "i"), this._monthsShortStrictRegex = new RegExp("^(" + i.join("|") + ")", "i")
        }

        function wt(t, e, n, i, r, s, o) {
            var a = new Date(t, e, n, i, r, s, o);
            return t < 100 && t >= 0 && isFinite(a.getFullYear()) && a.setFullYear(t), a
        }

        function _t(t) {
            var e = new Date(Date.UTC.apply(null, arguments));
            return t < 100 && t >= 0 && isFinite(e.getUTCFullYear()) && e.setUTCFullYear(t), e
        }

        function bt(t, e, n) {
            var i = 7 + e - n,
                r = (7 + _t(t, 0, i).getUTCDay() - e) % 7;
            return -r + i - 1
        }

        function xt(t, e, n, i, r) {
            var s, o, a = (7 + n - i) % 7,
                l = bt(t, i, r),
                h = 1 + 7 * (e - 1) + a + l;
            return h <= 0 ? (s = t - 1, o = X(s) + h) : h > X(t) ? (s = t + 1, o = h - X(t)) : (s = t, o = h), {
                year: s,
                dayOfYear: o
            }
        }

        function Ct(t, e, n) {
            var i, r, s = bt(t.year(), e, n),
                o = Math.floor((t.dayOfYear() - s - 1) / 7) + 1;
            return o < 1 ? (r = t.year() - 1, i = o + St(r, e, n)) : o > St(t.year(), e, n) ? (i = o - St(t.year(), e, n), r = t.year() + 1) : (r = t.year(), i = o), {
                week: i,
                year: r
            }
        }

        function St(t, e, n) {
            var i = bt(t, e, n),
                r = bt(t + 1, e, n);
            return (X(t) - i + r) / 7
        }

        function Dt(t) {
            return Ct(t, this._week.dow, this._week.doy).week
        }

        function Et() {
            return this._week.dow
        }

        function kt() {
            return this._week.doy
        }

        function Tt(t) {
            var e = this.localeData().week(this);
            return null == t ? e : this.add(7 * (t - e), "d")
        }

        function Ot(t) {
            var e = Ct(this, 1, 4).week;
            return null == t ? e : this.add(7 * (t - e), "d")
        }

        function Mt(t, e) {
            return "string" != typeof t ? t : isNaN(t) ? (t = e.weekdaysParse(t), "number" == typeof t ? t : null) : parseInt(t, 10)
        }

        function Rt(t, e) {
            return "string" == typeof t ? e.weekdaysParse(t) % 7 || 7 : isNaN(t) ? null : t
        }

        function $t(t, e) {
            return t ? n(this._weekdays) ? this._weekdays[t.day()] : this._weekdays[this._weekdays.isFormat.test(e) ? "format" : "standalone"][t.day()] : n(this._weekdays) ? this._weekdays : this._weekdays.standalone
        }

        function Pt(t) {
            return t ? this._weekdaysShort[t.day()] : this._weekdaysShort
        }

        function At(t) {
            return t ? this._weekdaysMin[t.day()] : this._weekdaysMin
        }

        function Vt(t, e, n) {
            var i, r, s, o = t.toLocaleLowerCase();
            if (!this._weekdaysParse)
                for (this._weekdaysParse = [], this._shortWeekdaysParse = [], this._minWeekdaysParse = [], i = 0; i < 7; ++i) s = c([2e3, 1]).day(i), this._minWeekdaysParse[i] = this.weekdaysMin(s, "").toLocaleLowerCase(), this._shortWeekdaysParse[i] = this.weekdaysShort(s, "").toLocaleLowerCase(), this._weekdaysParse[i] = this.weekdays(s, "").toLocaleLowerCase();
            return n ? "dddd" === e ? (r = wr.call(this._weekdaysParse, o), r !== -1 ? r : null) : "ddd" === e ? (r = wr.call(this._shortWeekdaysParse, o), r !== -1 ? r : null) : (r = wr.call(this._minWeekdaysParse, o), r !== -1 ? r : null) : "dddd" === e ? (r = wr.call(this._weekdaysParse, o), r !== -1 ? r : (r = wr.call(this._shortWeekdaysParse, o), r !== -1 ? r : (r = wr.call(this._minWeekdaysParse, o), r !== -1 ? r : null))) : "ddd" === e ? (r = wr.call(this._shortWeekdaysParse, o), r !== -1 ? r : (r = wr.call(this._weekdaysParse, o), r !== -1 ? r : (r = wr.call(this._minWeekdaysParse, o), r !== -1 ? r : null))) : (r = wr.call(this._minWeekdaysParse, o), r !== -1 ? r : (r = wr.call(this._weekdaysParse, o), r !== -1 ? r : (r = wr.call(this._shortWeekdaysParse, o), r !== -1 ? r : null)))
        }

        function Nt(t, e, n) {
            var i, r, s;
            if (this._weekdaysParseExact) return Vt.call(this, t, e, n);
            for (this._weekdaysParse || (this._weekdaysParse = [], this._minWeekdaysParse = [], this._shortWeekdaysParse = [], this._fullWeekdaysParse = []), i = 0; i < 7; i++) {
                if (r = c([2e3, 1]).day(i), n && !this._fullWeekdaysParse[i] && (this._fullWeekdaysParse[i] = new RegExp("^" + this.weekdays(r, "").replace(".", ".?") + "$", "i"), this._shortWeekdaysParse[i] = new RegExp("^" + this.weekdaysShort(r, "").replace(".", ".?") + "$", "i"), this._minWeekdaysParse[i] = new RegExp("^" + this.weekdaysMin(r, "").replace(".", ".?") + "$", "i")), this._weekdaysParse[i] || (s = "^" + this.weekdays(r, "") + "|^" + this.weekdaysShort(r, "") + "|^" + this.weekdaysMin(r, ""), this._weekdaysParse[i] = new RegExp(s.replace(".", ""), "i")), n && "dddd" === e && this._fullWeekdaysParse[i].test(t)) return i;
                if (n && "ddd" === e && this._shortWeekdaysParse[i].test(t)) return i;
                if (n && "dd" === e && this._minWeekdaysParse[i].test(t)) return i;
                if (!n && this._weekdaysParse[i].test(t)) return i
            }
        }

        function It(t) {
            if (!this.isValid()) return null != t ? this : NaN;
            var e = this._isUTC ? this._d.getUTCDay() : this._d.getDay();
            return null != t ? (t = Mt(t, this.localeData()), this.add(t - e, "d")) : e
        }

        function jt(t) {
            if (!this.isValid()) return null != t ? this : NaN;
            var e = (this.day() + 7 - this.localeData()._week.dow) % 7;
            return null == t ? e : this.add(t - e, "d")
        }

        function Lt(t) {
            if (!this.isValid()) return null != t ? this : NaN;
            if (null != t) {
                var e = Rt(t, this.localeData());
                return this.day(this.day() % 7 ? e : e - 7)
            }
            return this.day() || 7
        }

        function Bt(t) {
            return this._weekdaysParseExact ? (h(this, "_weekdaysRegex") || Ft.call(this), t ? this._weekdaysStrictRegex : this._weekdaysRegex) : (h(this, "_weekdaysRegex") || (this._weekdaysRegex = Mr), this._weekdaysStrictRegex && t ? this._weekdaysStrictRegex : this._weekdaysRegex)
        }

        function Ht(t) {
            return this._weekdaysParseExact ? (h(this, "_weekdaysRegex") || Ft.call(this), t ? this._weekdaysShortStrictRegex : this._weekdaysShortRegex) : (h(this, "_weekdaysShortRegex") || (this._weekdaysShortRegex = Rr), this._weekdaysShortStrictRegex && t ? this._weekdaysShortStrictRegex : this._weekdaysShortRegex)
        }

        function Yt(t) {
            return this._weekdaysParseExact ? (h(this, "_weekdaysRegex") || Ft.call(this), t ? this._weekdaysMinStrictRegex : this._weekdaysMinRegex) : (h(this, "_weekdaysMinRegex") || (this._weekdaysMinRegex = $r), this._weekdaysMinStrictRegex && t ? this._weekdaysMinStrictRegex : this._weekdaysMinRegex)
        }

        function Ft() {
            function t(t, e) {
                return e.length - t.length
            }
            var e, n, i, r, s, o = [],
                a = [],
                l = [],
                h = [];
            for (e = 0; e < 7; e++) n = c([2e3, 1]).day(e), i = this.weekdaysMin(n, ""), r = this.weekdaysShort(n, ""), s = this.weekdays(n, ""), o.push(i), a.push(r), l.push(s), h.push(i), h.push(r), h.push(s);
            for (o.sort(t), a.sort(t), l.sort(t), h.sort(t), e = 0; e < 7; e++) a[e] = Q(a[e]), l[e] = Q(l[e]), h[e] = Q(h[e]);
            this._weekdaysRegex = new RegExp("^(" + h.join("|") + ")", "i"), this._weekdaysShortRegex = this._weekdaysRegex, this._weekdaysMinRegex = this._weekdaysRegex, this._weekdaysStrictRegex = new RegExp("^(" + l.join("|") + ")", "i"), this._weekdaysShortStrictRegex = new RegExp("^(" + a.join("|") + ")", "i"), this._weekdaysMinStrictRegex = new RegExp("^(" + o.join("|") + ")", "i")
        }

        function Ut() {
            return this.hours() % 12 || 12
        }

        function qt() {
            return this.hours() || 24
        }

        function Wt(t, e) {
            H(t, 0, 0, function() {
                return this.localeData().meridiem(this.hours(), this.minutes(), e)
            })
        }

        function zt(t, e) {
            return e._meridiemParse
        }

        function Gt(t) {
            return "p" === (t + "").toLowerCase().charAt(0)
        }

        function Qt(t, e, n) {
            return t > 11 ? n ? "pm" : "PM" : n ? "am" : "AM"
        }

        function Zt(t) {
            return t ? t.toLowerCase().replace("_", "-") : t
        }

        function Jt(t) {
            for (var e, n, i, r, s = 0; s < t.length;) {
                for (r = Zt(t[s]).split("-"), e = r.length, n = Zt(t[s + 1]), n = n ? n.split("-") : null; e > 0;) {
                    if (i = Kt(r.slice(0, e).join("-"))) return i;
                    if (n && n.length >= e && b(r, n, !0) >= e - 1) break;
                    e--
                }
                s++
            }
            return null
        }

        function Kt(t) {
            var e = null;
            if (!Ir[t] && "undefined" != typeof module && module && module.exports) try {
                e = Pr._abbr;
                var n = require;
                n("./locale/" + t), Xt(e)
            } catch (i) {}
            return Ir[t]
        }

        function Xt(t, e) {
            var n;
            return t && (n = s(e) ? ne(t) : te(t, e), n && (Pr = n)), Pr._abbr
        }

        function te(t, e) {
            if (null !== e) {
                var n = Nr;
                if (e.abbr = t, null != Ir[t]) S("defineLocaleOverride", "use moment.updateLocale(localeName, config) to change an existing locale. moment.defineLocale(localeName, config) should only be used for creating a new locale See http://momentjs.com/guides/#/warnings/define-locale/ for more info."), n = Ir[t]._config;
                else if (null != e.parentLocale) {
                    if (null == Ir[e.parentLocale]) return jr[e.parentLocale] || (jr[e.parentLocale] = []), jr[e.parentLocale].push({
                        name: t,
                        config: e
                    }), null;
                    n = Ir[e.parentLocale]._config
                }
                return Ir[t] = new T(k(n, e)), jr[t] && jr[t].forEach(function(t) {
                    te(t.name, t.config)
                }), Xt(t), Ir[t]
            }
            return delete Ir[t], null
        }

        function ee(t, e) {
            if (null != e) {
                var n, i, r = Nr;
                i = Kt(t), null != i && (r = i._config), e = k(r, e), n = new T(e), n.parentLocale = Ir[t], Ir[t] = n, Xt(t)
            } else null != Ir[t] && (null != Ir[t].parentLocale ? Ir[t] = Ir[t].parentLocale : null != Ir[t] && delete Ir[t]);
            return Ir[t]
        }

        function ne(t) {
            var e;
            if (t && t._locale && t._locale._abbr && (t = t._locale._abbr), !t) return Pr;
            if (!n(t)) {
                if (e = Kt(t)) return e;
                t = [t]
            }
            return Jt(t)
        }

        function ie() {
            return $i(Ir)
        }

        function re(t) {
            var e, n = t._a;
            return n && f(t).overflow === -2 && (e = n[cr] < 0 || n[cr] > 11 ? cr : n[dr] < 1 || n[dr] > lt(n[ur], n[cr]) ? dr : n[fr] < 0 || n[fr] > 24 || 24 === n[fr] && (0 !== n[pr] || 0 !== n[gr] || 0 !== n[mr]) ? fr : n[pr] < 0 || n[pr] > 59 ? pr : n[gr] < 0 || n[gr] > 59 ? gr : n[mr] < 0 || n[mr] > 999 ? mr : -1, f(t)._overflowDayOfYear && (e < ur || e > dr) && (e = dr), f(t)._overflowWeeks && e === -1 && (e = vr), f(t)._overflowWeekday && e === -1 && (e = yr), f(t).overflow = e), t
        }

        function se(t, e, n) {
            return null != t ? t : null != e ? e : n
        }

        function oe(e) {
            var n = new Date(t.now());
            return e._useUTC ? [n.getUTCFullYear(), n.getUTCMonth(), n.getUTCDate()] : [n.getFullYear(), n.getMonth(), n.getDate()]
        }

        function ae(t) {
            var e, n, i, r, s, o = [];
            if (!t._d) {
                for (i = oe(t), t._w && null == t._a[dr] && null == t._a[cr] && le(t), null != t._dayOfYear && (s = se(t._a[ur], i[ur]), (t._dayOfYear > X(s) || 0 === t._dayOfYear) && (f(t)._overflowDayOfYear = !0), n = _t(s, 0, t._dayOfYear), t._a[cr] = n.getUTCMonth(), t._a[dr] = n.getUTCDate()), e = 0; e < 3 && null == t._a[e]; ++e) t._a[e] = o[e] = i[e];
                for (; e < 7; e++) t._a[e] = o[e] = null == t._a[e] ? 2 === e ? 1 : 0 : t._a[e];
                24 === t._a[fr] && 0 === t._a[pr] && 0 === t._a[gr] && 0 === t._a[mr] && (t._nextDay = !0, t._a[fr] = 0), t._d = (t._useUTC ? _t : wt).apply(null, o), r = t._useUTC ? t._d.getUTCDay() : t._d.getDay(), null != t._tzm && t._d.setUTCMinutes(t._d.getUTCMinutes() - t._tzm), t._nextDay && (t._a[fr] = 24), t._w && "undefined" != typeof t._w.d && t._w.d !== r && (f(t).weekdayMismatch = !0)
            }
        }

        function le(t) {
            var e, n, i, r, s, o, a, l;
            if (e = t._w, null != e.GG || null != e.W || null != e.E) s = 1, o = 4, n = se(e.GG, t._a[ur], Ct(De(), 1, 4).year), i = se(e.W, 1), r = se(e.E, 1), (r < 1 || r > 7) && (l = !0);
            else {
                s = t._locale._week.dow, o = t._locale._week.doy;
                var h = Ct(De(), s, o);
                n = se(e.gg, t._a[ur], h.year), i = se(e.w, h.week), null != e.d ? (r = e.d, (r < 0 || r > 6) && (l = !0)) : null != e.e ? (r = e.e + s, (e.e < 0 || e.e > 6) && (l = !0)) : r = s
            }
            i < 1 || i > St(n, s, o) ? f(t)._overflowWeeks = !0 : null != l ? f(t)._overflowWeekday = !0 : (a = xt(n, i, r, s, o), t._a[ur] = a.year, t._dayOfYear = a.dayOfYear)
        }

        function he(t) {
            var e, n, i, r, s, o, a = t._i,
                l = Lr.exec(a) || Br.exec(a);
            if (l) {
                for (f(t).iso = !0, e = 0, n = Yr.length; e < n; e++)
                    if (Yr[e][1].exec(l[1])) {
                        r = Yr[e][0], i = Yr[e][2] !== !1;
                        break
                    }
                if (null == r) return void(t._isValid = !1);
                if (l[3]) {
                    for (e = 0, n = Fr.length; e < n; e++)
                        if (Fr[e][1].exec(l[3])) {
                            s = (l[2] || " ") + Fr[e][0];
                            break
                        }
                    if (null == s) return void(t._isValid = !1)
                }
                if (!i && null != s) return void(t._isValid = !1);
                if (l[4]) {
                    if (!Hr.exec(l[4])) return void(t._isValid = !1);
                    o = "Z"
                }
                t._f = r + (s || "") + (o || ""), ve(t)
            } else t._isValid = !1
        }

        function ue(t, e, n, i, r, s) {
            var o = [ce(t), Cr.indexOf(e), parseInt(n, 10), parseInt(i, 10), parseInt(r, 10)];
            return s && o.push(parseInt(s, 10)), o
        }

        function ce(t) {
            var e = parseInt(t, 10);
            return e <= 49 ? 2e3 + e : e <= 999 ? 1900 + e : e
        }

        function de(t) {
            return t.replace(/\([^)]*\)|[\n\t]/g, " ").replace(/(\s\s+)/g, " ").trim()
        }

        function fe(t, e, n) {
            if (t) {
                var i = Tr.indexOf(t),
                    r = new Date(e[0], e[1], e[2]).getDay();
                if (i !== r) return f(n).weekdayMismatch = !0, n._isValid = !1, !1
            }
            return !0
        }

        function pe(t, e, n) {
            if (t) return Wr[t];
            if (e) return 0;
            var i = parseInt(n, 10),
                r = i % 100,
                s = (i - r) / 100;
            return 60 * s + r
        }

        function ge(t) {
            var e = qr.exec(de(t._i));
            if (e) {
                var n = ue(e[4], e[3], e[2], e[5], e[6], e[7]);
                if (!fe(e[1], n, t)) return;
                t._a = n, t._tzm = pe(e[8], e[9], e[10]), t._d = _t.apply(null, t._a), t._d.setUTCMinutes(t._d.getUTCMinutes() - t._tzm), f(t).rfc2822 = !0
            } else t._isValid = !1
        }

        function me(e) {
            var n = Ur.exec(e._i);
            return null !== n ? void(e._d = new Date((+n[1]))) : (he(e), void(e._isValid === !1 && (delete e._isValid, ge(e), e._isValid === !1 && (delete e._isValid, t.createFromInputFallback(e)))))
        }

        function ve(e) {
            if (e._f === t.ISO_8601) return void he(e);
            if (e._f === t.RFC_2822) return void ge(e);
            e._a = [], f(e).empty = !0;
            var n, i, r, s, o, a = "" + e._i,
                l = a.length,
                h = 0;
            for (r = q(e._f, e._locale).match(Hi) || [], n = 0; n < r.length; n++) s = r[n], i = (a.match(z(s, e)) || [])[0], i && (o = a.substr(0, a.indexOf(i)), o.length > 0 && f(e).unusedInput.push(o), a = a.slice(a.indexOf(i) + i.length), h += i.length), Ui[s] ? (i ? f(e).empty = !1 : f(e).unusedTokens.push(s), K(s, i, e)) : e._strict && !i && f(e).unusedTokens.push(s);
            f(e).charsLeftOver = l - h, a.length > 0 && f(e).unusedInput.push(a), e._a[fr] <= 12 && f(e).bigHour === !0 && e._a[fr] > 0 && (f(e).bigHour = void 0), f(e).parsedDateParts = e._a.slice(0), f(e).meridiem = e._meridiem, e._a[fr] = ye(e._locale, e._a[fr], e._meridiem), ae(e), re(e)
        }

        function ye(t, e, n) {
            var i;
            return null == n ? e : null != t.meridiemHour ? t.meridiemHour(e, n) : null != t.isPM ? (i = t.isPM(n), i && e < 12 && (e += 12), i || 12 !== e || (e = 0), e) : e
        }

        function we(t) {
            var e, n, i, r, s;
            if (0 === t._f.length) return f(t).invalidFormat = !0, void(t._d = new Date(NaN));
            for (r = 0; r < t._f.length; r++) s = 0, e = m({}, t), null != t._useUTC && (e._useUTC = t._useUTC), e._f = t._f[r], ve(e), p(e) && (s += f(e).charsLeftOver, s += 10 * f(e).unusedTokens.length, f(e).score = s, (null == i || s < i) && (i = s, n = e));
            u(t, n || e)
        }

        function _e(t) {
            if (!t._d) {
                var e = I(t._i);
                t._a = l([e.year, e.month, e.day || e.date, e.hour, e.minute, e.second, e.millisecond], function(t) {
                    return t && parseInt(t, 10)
                }), ae(t)
            }
        }

        function be(t) {
            var e = new v(re(xe(t)));
            return e._nextDay && (e.add(1, "d"), e._nextDay = void 0), e
        }

        function xe(t) {
            var e = t._i,
                i = t._f;
            return t._locale = t._locale || ne(t._l), null === e || void 0 === i && "" === e ? g({
                nullInput: !0
            }) : ("string" == typeof e && (t._i = e = t._locale.preparse(e)), y(e) ? new v(re(e)) : (a(e) ? t._d = e : n(i) ? we(t) : i ? ve(t) : Ce(t), p(t) || (t._d = null), t))
        }

        function Ce(e) {
            var r = e._i;
            s(r) ? e._d = new Date(t.now()) : a(r) ? e._d = new Date(r.valueOf()) : "string" == typeof r ? me(e) : n(r) ? (e._a = l(r.slice(0), function(t) {
                return parseInt(t, 10)
            }), ae(e)) : i(r) ? _e(e) : o(r) ? e._d = new Date(r) : t.createFromInputFallback(e)
        }

        function Se(t, e, s, o, a) {
            var l = {};
            return s !== !0 && s !== !1 || (o = s, s = void 0), (i(t) && r(t) || n(t) && 0 === t.length) && (t = void 0), l._isAMomentObject = !0, l._useUTC = l._isUTC = a, l._l = s, l._i = t, l._f = e, l._strict = o, be(l)
        }

        function De(t, e, n, i) {
            return Se(t, e, n, i, !1)
        }

        function Ee(t, e) {
            var i, r;
            if (1 === e.length && n(e[0]) && (e = e[0]), !e.length) return De();
            for (i = e[0], r = 1; r < e.length; ++r) e[r].isValid() && !e[r][t](i) || (i = e[r]);
            return i
        }

        function ke() {
            var t = [].slice.call(arguments, 0);
            return Ee("isBefore", t)
        }

        function Te() {
            var t = [].slice.call(arguments, 0);
            return Ee("isAfter", t)
        }

        function Oe(t) {
            for (var e in t)
                if (wr.call(Zr, e) === -1 || null != t[e] && isNaN(t[e])) return !1;
            for (var n = !1, i = 0; i < Zr.length; ++i)
                if (t[Zr[i]]) {
                    if (n) return !1;
                    parseFloat(t[Zr[i]]) !== _(t[Zr[i]]) && (n = !0)
                }
            return !0
        }

        function Me() {
            return this._isValid
        }

        function Re() {
            return Ze(NaN)
        }

        function $e(t) {
            var e = I(t),
                n = e.year || 0,
                i = e.quarter || 0,
                r = e.month || 0,
                s = e.week || 0,
                o = e.day || 0,
                a = e.hour || 0,
                l = e.minute || 0,
                h = e.second || 0,
                u = e.millisecond || 0;
            this._isValid = Oe(e), this._milliseconds = +u + 1e3 * h + 6e4 * l + 1e3 * a * 60 * 60, this._days = +o + 7 * s, this._months = +r + 3 * i + 12 * n, this._data = {}, this._locale = ne(), this._bubble()
        }

        function Pe(t) {
            return t instanceof $e
        }

        function Ae(t) {
            return t < 0 ? Math.round(-1 * t) * -1 : Math.round(t)
        }

        function Ve(t, e) {
            H(t, 0, 0, function() {
                var t = this.utcOffset(),
                    n = "+";
                return t < 0 && (t = -t, n = "-"), n + B(~~(t / 60), 2) + e + B(~~t % 60, 2)
            })
        }

        function Ne(t, e) {
            var n = (e || "").match(t);
            if (null === n) return null;
            var i = n[n.length - 1] || [],
                r = (i + "").match(Jr) || ["-", 0, 0],
                s = +(60 * r[1]) + _(r[2]);
            return 0 === s ? 0 : "+" === r[0] ? s : -s
        }

        function Ie(e, n) {
            var i, r;
            return n._isUTC ? (i = n.clone(), r = (y(e) || a(e) ? e.valueOf() : De(e).valueOf()) - i.valueOf(), i._d.setTime(i._d.valueOf() + r), t.updateOffset(i, !1), i) : De(e).local()
        }

        function je(t) {
            return 15 * -Math.round(t._d.getTimezoneOffset() / 15)
        }

        function Le(e, n, i) {
            var r, s = this._offset || 0;
            if (!this.isValid()) return null != e ? this : NaN;
            if (null != e) {
                if ("string" == typeof e) {
                    if (e = Ne(sr, e), null === e) return this
                } else Math.abs(e) < 16 && !i && (e = 60 * e);
                return !this._isUTC && n && (r = je(this)), this._offset = e, this._isUTC = !0, null != r && this.add(r, "m"), s !== e && (!n || this._changeInProgress ? en(this, Ze(e - s, "m"), 1, !1) : this._changeInProgress || (this._changeInProgress = !0, t.updateOffset(this, !0), this._changeInProgress = null)), this
            }
            return this._isUTC ? s : je(this)
        }

        function Be(t, e) {
            return null != t ? ("string" != typeof t && (t = -t), this.utcOffset(t, e), this) : -this.utcOffset()
        }

        function He(t) {
            return this.utcOffset(0, t)
        }

        function Ye(t) {
            return this._isUTC && (this.utcOffset(0, t), this._isUTC = !1, t && this.subtract(je(this), "m")), this
        }

        function Fe() {
            if (null != this._tzm) this.utcOffset(this._tzm, !1, !0);
            else if ("string" == typeof this._i) {
                var t = Ne(rr, this._i);
                null != t ? this.utcOffset(t) : this.utcOffset(0, !0)
            }
            return this
        }

        function Ue(t) {
            return !!this.isValid() && (t = t ? De(t).utcOffset() : 0, (this.utcOffset() - t) % 60 === 0)
        }

        function qe() {
            return this.utcOffset() > this.clone().month(0).utcOffset() || this.utcOffset() > this.clone().month(5).utcOffset()
        }

        function We() {
            if (!s(this._isDSTShifted)) return this._isDSTShifted;
            var t = {};
            if (m(t, this), t = xe(t), t._a) {
                var e = t._isUTC ? c(t._a) : De(t._a);
                this._isDSTShifted = this.isValid() && b(t._a, e.toArray()) > 0
            } else this._isDSTShifted = !1;
            return this._isDSTShifted
        }

        function ze() {
            return !!this.isValid() && !this._isUTC
        }

        function Ge() {
            return !!this.isValid() && this._isUTC
        }

        function Qe() {
            return !!this.isValid() && (this._isUTC && 0 === this._offset)
        }

        function Ze(t, e) {
            var n, i, r, s = t,
                a = null;
            return Pe(t) ? s = {
                ms: t._milliseconds,
                d: t._days,
                M: t._months
            } : o(t) ? (s = {}, e ? s[e] = t : s.milliseconds = t) : (a = Kr.exec(t)) ? (n = "-" === a[1] ? -1 : 1, s = {
                y: 0,
                d: _(a[dr]) * n,
                h: _(a[fr]) * n,
                m: _(a[pr]) * n,
                s: _(a[gr]) * n,
                ms: _(Ae(1e3 * a[mr])) * n
            }) : (a = Xr.exec(t)) ? (n = "-" === a[1] ? -1 : ("+" === a[1], 1), s = {
                y: Je(a[2], n),
                M: Je(a[3], n),
                w: Je(a[4], n),
                d: Je(a[5], n),
                h: Je(a[6], n),
                m: Je(a[7], n),
                s: Je(a[8], n)
            }) : null == s ? s = {} : "object" == typeof s && ("from" in s || "to" in s) && (r = Xe(De(s.from), De(s.to)), s = {}, s.ms = r.milliseconds, s.M = r.months), i = new $e(s), Pe(t) && h(t, "_locale") && (i._locale = t._locale), i
        }

        function Je(t, e) {
            var n = t && parseFloat(t.replace(",", "."));
            return (isNaN(n) ? 0 : n) * e
        }

        function Ke(t, e) {
            var n = {
                milliseconds: 0,
                months: 0
            };
            return n.months = e.month() - t.month() + 12 * (e.year() - t.year()), t.clone().add(n.months, "M").isAfter(e) && --n.months, n.milliseconds = +e - +t.clone().add(n.months, "M"), n
        }

        function Xe(t, e) {
            var n;
            return t.isValid() && e.isValid() ? (e = Ie(e, t), t.isBefore(e) ? n = Ke(t, e) : (n = Ke(e, t), n.milliseconds = -n.milliseconds, n.months = -n.months), n) : {
                milliseconds: 0,
                months: 0
            }
        }

        function tn(t, e) {
            return function(n, i) {
                var r, s;
                return null === i || isNaN(+i) || (S(e, "moment()." + e + "(period, number) is deprecated. Please use moment()." + e + "(number, period). See http://momentjs.com/guides/#/warnings/add-inverted-param/ for more info."), s = n, n = i, i = s), n = "string" == typeof n ? +n : n, r = Ze(n, i), en(this, r, t), this
            }
        }

        function en(e, n, i, r) {
            var s = n._milliseconds,
                o = Ae(n._days),
                a = Ae(n._months);
            e.isValid() && (r = null == r || r, a && ft(e, it(e, "Month") + a * i), o && rt(e, "Date", it(e, "Date") + o * i), s && e._d.setTime(e._d.valueOf() + s * i), r && t.updateOffset(e, o || a))
        }

        function nn(t, e) {
            var n = t.diff(e, "days", !0);
            return n < -6 ? "sameElse" : n < -1 ? "lastWeek" : n < 0 ? "lastDay" : n < 1 ? "sameDay" : n < 2 ? "nextDay" : n < 7 ? "nextWeek" : "sameElse"
        }

        function rn(e, n) {
            var i = e || De(),
                r = Ie(i, this).startOf("day"),
                s = t.calendarFormat(this, r) || "sameElse",
                o = n && (D(n[s]) ? n[s].call(this, i) : n[s]);
            return this.format(o || this.localeData().calendar(s, this, De(i)))
        }

        function sn() {
            return new v(this)
        }

        function on(t, e) {
            var n = y(t) ? t : De(t);
            return !(!this.isValid() || !n.isValid()) && (e = N(s(e) ? "millisecond" : e), "millisecond" === e ? this.valueOf() > n.valueOf() : n.valueOf() < this.clone().startOf(e).valueOf())
        }

        function an(t, e) {
            var n = y(t) ? t : De(t);
            return !(!this.isValid() || !n.isValid()) && (e = N(s(e) ? "millisecond" : e), "millisecond" === e ? this.valueOf() < n.valueOf() : this.clone().endOf(e).valueOf() < n.valueOf())
        }

        function ln(t, e, n, i) {
            return i = i || "()", ("(" === i[0] ? this.isAfter(t, n) : !this.isBefore(t, n)) && (")" === i[1] ? this.isBefore(e, n) : !this.isAfter(e, n))
        }

        function hn(t, e) {
            var n, i = y(t) ? t : De(t);
            return !(!this.isValid() || !i.isValid()) && (e = N(e || "millisecond"), "millisecond" === e ? this.valueOf() === i.valueOf() : (n = i.valueOf(), this.clone().startOf(e).valueOf() <= n && n <= this.clone().endOf(e).valueOf()))
        }

        function un(t, e) {
            return this.isSame(t, e) || this.isAfter(t, e)
        }

        function cn(t, e) {
            return this.isSame(t, e) || this.isBefore(t, e)
        }

        function dn(t, e, n) {
            var i, r, s;
            if (!this.isValid()) return NaN;
            if (i = Ie(t, this), !i.isValid()) return NaN;
            switch (r = 6e4 * (i.utcOffset() - this.utcOffset()), e = N(e)) {
                case "year":
                    s = fn(this, i) / 12;
                    break;
                case "month":
                    s = fn(this, i);
                    break;
                case "quarter":
                    s = fn(this, i) / 3;
                    break;
                case "second":
                    s = (this - i) / 1e3;
                    break;
                case "minute":
                    s = (this - i) / 6e4;
                    break;
                case "hour":
                    s = (this - i) / 36e5;
                    break;
                case "day":
                    s = (this - i - r) / 864e5;
                    break;
                case "week":
                    s = (this - i - r) / 6048e5;
                    break;
                default:
                    s = this - i
            }
            return n ? s : w(s)
        }

        function fn(t, e) {
            var n, i, r = 12 * (e.year() - t.year()) + (e.month() - t.month()),
                s = t.clone().add(r, "months");
            return e - s < 0 ? (n = t.clone().add(r - 1, "months"), i = (e - s) / (s - n)) : (n = t.clone().add(r + 1, "months"), i = (e - s) / (n - s)), -(r + i) || 0
        }

        function pn() {
            return this.clone().locale("en").format("ddd MMM DD YYYY HH:mm:ss [GMT]ZZ")
        }

        function gn(t) {
            if (!this.isValid()) return null;
            var e = t !== !0,
                n = e ? this.clone().utc() : this;
            return n.year() < 0 || n.year() > 9999 ? U(n, e ? "YYYYYY-MM-DD[T]HH:mm:ss.SSS[Z]" : "YYYYYY-MM-DD[T]HH:mm:ss.SSSZ") : D(Date.prototype.toISOString) ? e ? this.toDate().toISOString() : new Date(this._d.valueOf()).toISOString().replace("Z", U(n, "Z")) : U(n, e ? "YYYY-MM-DD[T]HH:mm:ss.SSS[Z]" : "YYYY-MM-DD[T]HH:mm:ss.SSSZ")
        }

        function mn() {
            if (!this.isValid()) return "moment.invalid(/* " + this._i + " */)";
            var t = "moment",
                e = "";
            this.isLocal() || (t = 0 === this.utcOffset() ? "moment.utc" : "moment.parseZone", e = "Z");
            var n = "[" + t + '("]',
                i = 0 <= this.year() && this.year() <= 9999 ? "YYYY" : "YYYYYY",
                r = "-MM-DD[T]HH:mm:ss.SSS",
                s = e + '[")]';
            return this.format(n + i + r + s)
        }

        function vn(e) {
            e || (e = this.isUtc() ? t.defaultFormatUtc : t.defaultFormat);
            var n = U(this, e);
            return this.localeData().postformat(n)
        }

        function yn(t, e) {
            return this.isValid() && (y(t) && t.isValid() || De(t).isValid()) ? Ze({
                to: this,
                from: t
            }).locale(this.locale()).humanize(!e) : this.localeData().invalidDate()
        }

        function wn(t) {
            return this.from(De(), t)
        }

        function _n(t, e) {
            return this.isValid() && (y(t) && t.isValid() || De(t).isValid()) ? Ze({
                from: this,
                to: t
            }).locale(this.locale()).humanize(!e) : this.localeData().invalidDate()
        }

        function bn(t) {
            return this.to(De(), t)
        }

        function xn(t) {
            var e;
            return void 0 === t ? this._locale._abbr : (e = ne(t), null != e && (this._locale = e), this)
        }

        function Cn() {
            return this._locale
        }

        function Sn(t) {
            switch (t = N(t)) {
                case "year":
                    this.month(0);
                case "quarter":
                case "month":
                    this.date(1);
                case "week":
                case "isoWeek":
                case "day":
                case "date":
                    this.hours(0);
                case "hour":
                    this.minutes(0);
                case "minute":
                    this.seconds(0);
                case "second":
                    this.milliseconds(0)
            }
            return "week" === t && this.weekday(0), "isoWeek" === t && this.isoWeekday(1), "quarter" === t && this.month(3 * Math.floor(this.month() / 3)), this
        }

        function Dn(t) {
            return t = N(t), void 0 === t || "millisecond" === t ? this : ("date" === t && (t = "day"), this.startOf(t).add(1, "isoWeek" === t ? "week" : t).subtract(1, "ms"))
        }

        function En() {
            return this._d.valueOf() - 6e4 * (this._offset || 0)
        }

        function kn() {
            return Math.floor(this.valueOf() / 1e3)
        }

        function Tn() {
            return new Date(this.valueOf())
        }

        function On() {
            var t = this;
            return [t.year(), t.month(), t.date(), t.hour(), t.minute(), t.second(), t.millisecond()];
        }

        function Mn() {
            var t = this;
            return {
                years: t.year(),
                months: t.month(),
                date: t.date(),
                hours: t.hours(),
                minutes: t.minutes(),
                seconds: t.seconds(),
                milliseconds: t.milliseconds()
            }
        }

        function Rn() {
            return this.isValid() ? this.toISOString() : null
        }

        function $n() {
            return p(this)
        }

        function Pn() {
            return u({}, f(this))
        }

        function An() {
            return f(this).overflow
        }

        function Vn() {
            return {
                input: this._i,
                format: this._f,
                locale: this._locale,
                isUTC: this._isUTC,
                strict: this._strict
            }
        }

        function Nn(t, e) {
            H(0, [t, t.length], 0, e)
        }

        function In(t) {
            return Hn.call(this, t, this.week(), this.weekday(), this.localeData()._week.dow, this.localeData()._week.doy)
        }

        function jn(t) {
            return Hn.call(this, t, this.isoWeek(), this.isoWeekday(), 1, 4)
        }

        function Ln() {
            return St(this.year(), 1, 4)
        }

        function Bn() {
            var t = this.localeData()._week;
            return St(this.year(), t.dow, t.doy)
        }

        function Hn(t, e, n, i, r) {
            var s;
            return null == t ? Ct(this, i, r).year : (s = St(t, i, r), e > s && (e = s), Yn.call(this, t, e, n, i, r))
        }

        function Yn(t, e, n, i, r) {
            var s = xt(t, e, n, i, r),
                o = _t(s.year, 0, s.dayOfYear);
            return this.year(o.getUTCFullYear()), this.month(o.getUTCMonth()), this.date(o.getUTCDate()), this
        }

        function Fn(t) {
            return null == t ? Math.ceil((this.month() + 1) / 3) : this.month(3 * (t - 1) + this.month() % 3)
        }

        function Un(t) {
            var e = Math.round((this.clone().startOf("day") - this.clone().startOf("year")) / 864e5) + 1;
            return null == t ? e : this.add(t - e, "d")
        }

        function qn(t, e) {
            e[mr] = _(1e3 * ("0." + t))
        }

        function Wn() {
            return this._isUTC ? "UTC" : ""
        }

        function zn() {
            return this._isUTC ? "Coordinated Universal Time" : ""
        }

        function Gn(t) {
            return De(1e3 * t)
        }

        function Qn() {
            return De.apply(null, arguments).parseZone()
        }

        function Zn(t) {
            return t
        }

        function Jn(t, e, n, i) {
            var r = ne(),
                s = c().set(i, e);
            return r[n](s, t)
        }

        function Kn(t, e, n) {
            if (o(t) && (e = t, t = void 0), t = t || "", null != e) return Jn(t, e, n, "month");
            var i, r = [];
            for (i = 0; i < 12; i++) r[i] = Jn(t, i, n, "month");
            return r
        }

        function Xn(t, e, n, i) {
            "boolean" == typeof t ? (o(e) && (n = e, e = void 0), e = e || "") : (e = t, n = e, t = !1, o(e) && (n = e, e = void 0), e = e || "");
            var r = ne(),
                s = t ? r._week.dow : 0;
            if (null != n) return Jn(e, (n + s) % 7, i, "day");
            var a, l = [];
            for (a = 0; a < 7; a++) l[a] = Jn(e, (a + s) % 7, i, "day");
            return l
        }

        function ti(t, e) {
            return Kn(t, e, "months")
        }

        function ei(t, e) {
            return Kn(t, e, "monthsShort")
        }

        function ni(t, e, n) {
            return Xn(t, e, n, "weekdays")
        }

        function ii(t, e, n) {
            return Xn(t, e, n, "weekdaysShort")
        }

        function ri(t, e, n) {
            return Xn(t, e, n, "weekdaysMin")
        }

        function si() {
            var t = this._data;
            return this._milliseconds = us(this._milliseconds), this._days = us(this._days), this._months = us(this._months), t.milliseconds = us(t.milliseconds), t.seconds = us(t.seconds), t.minutes = us(t.minutes), t.hours = us(t.hours), t.months = us(t.months), t.years = us(t.years), this
        }

        function oi(t, e, n, i) {
            var r = Ze(e, n);
            return t._milliseconds += i * r._milliseconds, t._days += i * r._days, t._months += i * r._months, t._bubble()
        }

        function ai(t, e) {
            return oi(this, t, e, 1)
        }

        function li(t, e) {
            return oi(this, t, e, -1)
        }

        function hi(t) {
            return t < 0 ? Math.floor(t) : Math.ceil(t)
        }

        function ui() {
            var t, e, n, i, r, s = this._milliseconds,
                o = this._days,
                a = this._months,
                l = this._data;
            return s >= 0 && o >= 0 && a >= 0 || s <= 0 && o <= 0 && a <= 0 || (s += 864e5 * hi(di(a) + o), o = 0, a = 0), l.milliseconds = s % 1e3, t = w(s / 1e3), l.seconds = t % 60, e = w(t / 60), l.minutes = e % 60, n = w(e / 60), l.hours = n % 24, o += w(n / 24), r = w(ci(o)), a += r, o -= hi(di(r)), i = w(a / 12), a %= 12, l.days = o, l.months = a, l.years = i, this
        }

        function ci(t) {
            return 4800 * t / 146097
        }

        function di(t) {
            return 146097 * t / 4800
        }

        function fi(t) {
            if (!this.isValid()) return NaN;
            var e, n, i = this._milliseconds;
            if (t = N(t), "month" === t || "year" === t) return e = this._days + i / 864e5, n = this._months + ci(e), "month" === t ? n : n / 12;
            switch (e = this._days + Math.round(di(this._months)), t) {
                case "week":
                    return e / 7 + i / 6048e5;
                case "day":
                    return e + i / 864e5;
                case "hour":
                    return 24 * e + i / 36e5;
                case "minute":
                    return 1440 * e + i / 6e4;
                case "second":
                    return 86400 * e + i / 1e3;
                case "millisecond":
                    return Math.floor(864e5 * e) + i;
                default:
                    throw new Error("Unknown unit " + t)
            }
        }

        function pi() {
            return this.isValid() ? this._milliseconds + 864e5 * this._days + this._months % 12 * 2592e6 + 31536e6 * _(this._months / 12) : NaN
        }

        function gi(t) {
            return function() {
                return this.as(t)
            }
        }

        function mi() {
            return Ze(this)
        }

        function vi(t) {
            return t = N(t), this.isValid() ? this[t + "s"]() : NaN
        }

        function yi(t) {
            return function() {
                return this.isValid() ? this._data[t] : NaN
            }
        }

        function wi() {
            return w(this.days() / 7)
        }

        function _i(t, e, n, i, r) {
            return r.relativeTime(e || 1, !!n, t, i)
        }

        function bi(t, e, n) {
            var i = Ze(t).abs(),
                r = Es(i.as("s")),
                s = Es(i.as("m")),
                o = Es(i.as("h")),
                a = Es(i.as("d")),
                l = Es(i.as("M")),
                h = Es(i.as("y")),
                u = r <= ks.ss && ["s", r] || r < ks.s && ["ss", r] || s <= 1 && ["m"] || s < ks.m && ["mm", s] || o <= 1 && ["h"] || o < ks.h && ["hh", o] || a <= 1 && ["d"] || a < ks.d && ["dd", a] || l <= 1 && ["M"] || l < ks.M && ["MM", l] || h <= 1 && ["y"] || ["yy", h];
            return u[2] = e, u[3] = +t > 0, u[4] = n, _i.apply(null, u)
        }

        function xi(t) {
            return void 0 === t ? Es : "function" == typeof t && (Es = t, !0)
        }

        function Ci(t, e) {
            return void 0 !== ks[t] && (void 0 === e ? ks[t] : (ks[t] = e, "s" === t && (ks.ss = e - 1), !0))
        }

        function Si(t) {
            if (!this.isValid()) return this.localeData().invalidDate();
            var e = this.localeData(),
                n = bi(this, !t, e);
            return t && (n = e.pastFuture(+this, n)), e.postformat(n)
        }

        function Di(t) {
            return (t > 0) - (t < 0) || +t
        }

        function Ei() {
            if (!this.isValid()) return this.localeData().invalidDate();
            var t, e, n, i = Ts(this._milliseconds) / 1e3,
                r = Ts(this._days),
                s = Ts(this._months);
            t = w(i / 60), e = w(t / 60), i %= 60, t %= 60, n = w(s / 12), s %= 12;
            var o = n,
                a = s,
                l = r,
                h = e,
                u = t,
                c = i ? i.toFixed(3).replace(/\.?0+$/, "") : "",
                d = this.asSeconds();
            if (!d) return "P0D";
            var f = d < 0 ? "-" : "",
                p = Di(this._months) !== Di(d) ? "-" : "",
                g = Di(this._days) !== Di(d) ? "-" : "",
                m = Di(this._milliseconds) !== Di(d) ? "-" : "";
            return f + "P" + (o ? p + o + "Y" : "") + (a ? p + a + "M" : "") + (l ? g + l + "D" : "") + (h || u || c ? "T" : "") + (h ? m + h + "H" : "") + (u ? m + u + "M" : "") + (c ? m + c + "S" : "")
        }
        var ki, Ti;
        Ti = Array.prototype.some ? Array.prototype.some : function(t) {
            for (var e = Object(this), n = e.length >>> 0, i = 0; i < n; i++)
                if (i in e && t.call(this, e[i], i, e)) return !0;
            return !1
        };
        var Oi = t.momentProperties = [],
            Mi = !1,
            Ri = {};
        t.suppressDeprecationWarnings = !1, t.deprecationHandler = null;
        var $i;
        $i = Object.keys ? Object.keys : function(t) {
            var e, n = [];
            for (e in t) h(t, e) && n.push(e);
            return n
        };
        var Pi = {
                sameDay: "[Today at] LT",
                nextDay: "[Tomorrow at] LT",
                nextWeek: "dddd [at] LT",
                lastDay: "[Yesterday at] LT",
                lastWeek: "[Last] dddd [at] LT",
                sameElse: "L"
            },
            Ai = {
                LTS: "h:mm:ss A",
                LT: "h:mm A",
                L: "MM/DD/YYYY",
                LL: "MMMM D, YYYY",
                LLL: "MMMM D, YYYY h:mm A",
                LLLL: "dddd, MMMM D, YYYY h:mm A"
            },
            Vi = "Invalid date",
            Ni = "%d",
            Ii = /\d{1,2}/,
            ji = {
                future: "in %s",
                past: "%s ago",
                s: "a few seconds",
                ss: "%d seconds",
                m: "a minute",
                mm: "%d minutes",
                h: "an hour",
                hh: "%d hours",
                d: "a day",
                dd: "%d days",
                M: "a month",
                MM: "%d months",
                y: "a year",
                yy: "%d years"
            },
            Li = {},
            Bi = {},
            Hi = /(\[[^\[]*\])|(\\)?([Hh]mm(ss)?|Mo|MM?M?M?|Do|DDDo|DD?D?D?|ddd?d?|do?|w[o|w]?|W[o|W]?|Qo?|YYYYYY|YYYYY|YYYY|YY|gg(ggg?)?|GG(GGG?)?|e|E|a|A|hh?|HH?|kk?|mm?|ss?|S{1,9}|x|X|zz?|ZZ?|.)/g,
            Yi = /(\[[^\[]*\])|(\\)?(LTS|LT|LL?L?L?|l{1,4})/g,
            Fi = {},
            Ui = {},
            qi = /\d/,
            Wi = /\d\d/,
            zi = /\d{3}/,
            Gi = /\d{4}/,
            Qi = /[+-]?\d{6}/,
            Zi = /\d\d?/,
            Ji = /\d\d\d\d?/,
            Ki = /\d\d\d\d\d\d?/,
            Xi = /\d{1,3}/,
            tr = /\d{1,4}/,
            er = /[+-]?\d{1,6}/,
            nr = /\d+/,
            ir = /[+-]?\d+/,
            rr = /Z|[+-]\d\d:?\d\d/gi,
            sr = /Z|[+-]\d\d(?::?\d\d)?/gi,
            or = /[+-]?\d+(\.\d{1,3})?/,
            ar = /[0-9]{0,256}['a-z\u00A0-\u05FF\u0700-\uD7FF\uF900-\uFDCF\uFDF0-\uFF07\uFF10-\uFFEF]{1,256}|[\u0600-\u06FF\/]{1,256}(\s*?[\u0600-\u06FF]{1,256}){1,2}/i,
            lr = {},
            hr = {},
            ur = 0,
            cr = 1,
            dr = 2,
            fr = 3,
            pr = 4,
            gr = 5,
            mr = 6,
            vr = 7,
            yr = 8;
        H("Y", 0, 0, function() {
            var t = this.year();
            return t <= 9999 ? "" + t : "+" + t
        }), H(0, ["YY", 2], 0, function() {
            return this.year() % 100
        }), H(0, ["YYYY", 4], 0, "year"), H(0, ["YYYYY", 5], 0, "year"), H(0, ["YYYYYY", 6, !0], 0, "year"), V("year", "y"), j("year", 1), W("Y", ir), W("YY", Zi, Wi), W("YYYY", tr, Gi), W("YYYYY", er, Qi), W("YYYYYY", er, Qi), Z(["YYYYY", "YYYYYY"], ur), Z("YYYY", function(e, n) {
            n[ur] = 2 === e.length ? t.parseTwoDigitYear(e) : _(e)
        }), Z("YY", function(e, n) {
            n[ur] = t.parseTwoDigitYear(e)
        }), Z("Y", function(t, e) {
            e[ur] = parseInt(t, 10)
        }), t.parseTwoDigitYear = function(t) {
            return _(t) + (_(t) > 68 ? 1900 : 2e3)
        };
        var wr, _r = nt("FullYear", !0);
        wr = Array.prototype.indexOf ? Array.prototype.indexOf : function(t) {
            var e;
            for (e = 0; e < this.length; ++e)
                if (this[e] === t) return e;
            return -1
        }, H("M", ["MM", 2], "Mo", function() {
            return this.month() + 1
        }), H("MMM", 0, 0, function(t) {
            return this.localeData().monthsShort(this, t)
        }), H("MMMM", 0, 0, function(t) {
            return this.localeData().months(this, t)
        }), V("month", "M"), j("month", 8), W("M", Zi), W("MM", Zi, Wi), W("MMM", function(t, e) {
            return e.monthsShortRegex(t)
        }), W("MMMM", function(t, e) {
            return e.monthsRegex(t)
        }), Z(["M", "MM"], function(t, e) {
            e[cr] = _(t) - 1
        }), Z(["MMM", "MMMM"], function(t, e, n, i) {
            var r = n._locale.monthsParse(t, i, n._strict);
            null != r ? e[cr] = r : f(n).invalidMonth = t
        });
        var br = /D[oD]?(\[[^\[\]]*\]|\s)+MMMM?/,
            xr = "January_February_March_April_May_June_July_August_September_October_November_December".split("_"),
            Cr = "Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec".split("_"),
            Sr = ar,
            Dr = ar;
        H("w", ["ww", 2], "wo", "week"), H("W", ["WW", 2], "Wo", "isoWeek"), V("week", "w"), V("isoWeek", "W"), j("week", 5), j("isoWeek", 5), W("w", Zi), W("ww", Zi, Wi), W("W", Zi), W("WW", Zi, Wi), J(["w", "ww", "W", "WW"], function(t, e, n, i) {
            e[i.substr(0, 1)] = _(t)
        });
        var Er = {
            dow: 0,
            doy: 6
        };
        H("d", 0, "do", "day"), H("dd", 0, 0, function(t) {
            return this.localeData().weekdaysMin(this, t)
        }), H("ddd", 0, 0, function(t) {
            return this.localeData().weekdaysShort(this, t)
        }), H("dddd", 0, 0, function(t) {
            return this.localeData().weekdays(this, t)
        }), H("e", 0, 0, "weekday"), H("E", 0, 0, "isoWeekday"), V("day", "d"), V("weekday", "e"), V("isoWeekday", "E"), j("day", 11), j("weekday", 11), j("isoWeekday", 11), W("d", Zi), W("e", Zi), W("E", Zi), W("dd", function(t, e) {
            return e.weekdaysMinRegex(t)
        }), W("ddd", function(t, e) {
            return e.weekdaysShortRegex(t)
        }), W("dddd", function(t, e) {
            return e.weekdaysRegex(t)
        }), J(["dd", "ddd", "dddd"], function(t, e, n, i) {
            var r = n._locale.weekdaysParse(t, i, n._strict);
            null != r ? e.d = r : f(n).invalidWeekday = t
        }), J(["d", "e", "E"], function(t, e, n, i) {
            e[i] = _(t)
        });
        var kr = "Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday".split("_"),
            Tr = "Sun_Mon_Tue_Wed_Thu_Fri_Sat".split("_"),
            Or = "Su_Mo_Tu_We_Th_Fr_Sa".split("_"),
            Mr = ar,
            Rr = ar,
            $r = ar;
        H("H", ["HH", 2], 0, "hour"), H("h", ["hh", 2], 0, Ut), H("k", ["kk", 2], 0, qt), H("hmm", 0, 0, function() {
            return "" + Ut.apply(this) + B(this.minutes(), 2)
        }), H("hmmss", 0, 0, function() {
            return "" + Ut.apply(this) + B(this.minutes(), 2) + B(this.seconds(), 2)
        }), H("Hmm", 0, 0, function() {
            return "" + this.hours() + B(this.minutes(), 2)
        }), H("Hmmss", 0, 0, function() {
            return "" + this.hours() + B(this.minutes(), 2) + B(this.seconds(), 2)
        }), Wt("a", !0), Wt("A", !1), V("hour", "h"), j("hour", 13), W("a", zt), W("A", zt), W("H", Zi), W("h", Zi), W("k", Zi), W("HH", Zi, Wi), W("hh", Zi, Wi), W("kk", Zi, Wi), W("hmm", Ji), W("hmmss", Ki), W("Hmm", Ji), W("Hmmss", Ki), Z(["H", "HH"], fr), Z(["k", "kk"], function(t, e, n) {
            var i = _(t);
            e[fr] = 24 === i ? 0 : i
        }), Z(["a", "A"], function(t, e, n) {
            n._isPm = n._locale.isPM(t), n._meridiem = t
        }), Z(["h", "hh"], function(t, e, n) {
            e[fr] = _(t), f(n).bigHour = !0
        }), Z("hmm", function(t, e, n) {
            var i = t.length - 2;
            e[fr] = _(t.substr(0, i)), e[pr] = _(t.substr(i)), f(n).bigHour = !0
        }), Z("hmmss", function(t, e, n) {
            var i = t.length - 4,
                r = t.length - 2;
            e[fr] = _(t.substr(0, i)), e[pr] = _(t.substr(i, 2)), e[gr] = _(t.substr(r)), f(n).bigHour = !0
        }), Z("Hmm", function(t, e, n) {
            var i = t.length - 2;
            e[fr] = _(t.substr(0, i)), e[pr] = _(t.substr(i))
        }), Z("Hmmss", function(t, e, n) {
            var i = t.length - 4,
                r = t.length - 2;
            e[fr] = _(t.substr(0, i)), e[pr] = _(t.substr(i, 2)), e[gr] = _(t.substr(r))
        });
        var Pr, Ar = /[ap]\.?m?\.?/i,
            Vr = nt("Hours", !0),
            Nr = {
                calendar: Pi,
                longDateFormat: Ai,
                invalidDate: Vi,
                ordinal: Ni,
                dayOfMonthOrdinalParse: Ii,
                relativeTime: ji,
                months: xr,
                monthsShort: Cr,
                week: Er,
                weekdays: kr,
                weekdaysMin: Or,
                weekdaysShort: Tr,
                meridiemParse: Ar
            },
            Ir = {},
            jr = {},
            Lr = /^\s*((?:[+-]\d{6}|\d{4})-(?:\d\d-\d\d|W\d\d-\d|W\d\d|\d\d\d|\d\d))(?:(T| )(\d\d(?::\d\d(?::\d\d(?:[.,]\d+)?)?)?)([\+\-]\d\d(?::?\d\d)?|\s*Z)?)?$/,
            Br = /^\s*((?:[+-]\d{6}|\d{4})(?:\d\d\d\d|W\d\d\d|W\d\d|\d\d\d|\d\d))(?:(T| )(\d\d(?:\d\d(?:\d\d(?:[.,]\d+)?)?)?)([\+\-]\d\d(?::?\d\d)?|\s*Z)?)?$/,
            Hr = /Z|[+-]\d\d(?::?\d\d)?/,
            Yr = [
                ["YYYYYY-MM-DD", /[+-]\d{6}-\d\d-\d\d/],
                ["YYYY-MM-DD", /\d{4}-\d\d-\d\d/],
                ["GGGG-[W]WW-E", /\d{4}-W\d\d-\d/],
                ["GGGG-[W]WW", /\d{4}-W\d\d/, !1],
                ["YYYY-DDD", /\d{4}-\d{3}/],
                ["YYYY-MM", /\d{4}-\d\d/, !1],
                ["YYYYYYMMDD", /[+-]\d{10}/],
                ["YYYYMMDD", /\d{8}/],
                ["GGGG[W]WWE", /\d{4}W\d{3}/],
                ["GGGG[W]WW", /\d{4}W\d{2}/, !1],
                ["YYYYDDD", /\d{7}/]
            ],
            Fr = [
                ["HH:mm:ss.SSSS", /\d\d:\d\d:\d\d\.\d+/],
                ["HH:mm:ss,SSSS", /\d\d:\d\d:\d\d,\d+/],
                ["HH:mm:ss", /\d\d:\d\d:\d\d/],
                ["HH:mm", /\d\d:\d\d/],
                ["HHmmss.SSSS", /\d\d\d\d\d\d\.\d+/],
                ["HHmmss,SSSS", /\d\d\d\d\d\d,\d+/],
                ["HHmmss", /\d\d\d\d\d\d/],
                ["HHmm", /\d\d\d\d/],
                ["HH", /\d\d/]
            ],
            Ur = /^\/?Date\((\-?\d+)/i,
            qr = /^(?:(Mon|Tue|Wed|Thu|Fri|Sat|Sun),?\s)?(\d{1,2})\s(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s(\d{2,4})\s(\d\d):(\d\d)(?::(\d\d))?\s(?:(UT|GMT|[ECMP][SD]T)|([Zz])|([+-]\d{4}))$/,
            Wr = {
                UT: 0,
                GMT: 0,
                EDT: -240,
                EST: -300,
                CDT: -300,
                CST: -360,
                MDT: -360,
                MST: -420,
                PDT: -420,
                PST: -480
            };
        t.createFromInputFallback = C("value provided is not in a recognized RFC2822 or ISO format. moment construction falls back to js Date(), which is not reliable across all browsers and versions. Non RFC2822/ISO date formats are discouraged and will be removed in an upcoming major release. Please refer to http://momentjs.com/guides/#/warnings/js-date/ for more info.", function(t) {
            t._d = new Date(t._i + (t._useUTC ? " UTC" : ""))
        }), t.ISO_8601 = function() {}, t.RFC_2822 = function() {};
        var zr = C("moment().min is deprecated, use moment.max instead. http://momentjs.com/guides/#/warnings/min-max/", function() {
                var t = De.apply(null, arguments);
                return this.isValid() && t.isValid() ? t < this ? this : t : g()
            }),
            Gr = C("moment().max is deprecated, use moment.min instead. http://momentjs.com/guides/#/warnings/min-max/", function() {
                var t = De.apply(null, arguments);
                return this.isValid() && t.isValid() ? t > this ? this : t : g()
            }),
            Qr = function() {
                return Date.now ? Date.now() : +new Date
            },
            Zr = ["year", "quarter", "month", "week", "day", "hour", "minute", "second", "millisecond"];
        Ve("Z", ":"), Ve("ZZ", ""), W("Z", sr), W("ZZ", sr), Z(["Z", "ZZ"], function(t, e, n) {
            n._useUTC = !0, n._tzm = Ne(sr, t)
        });
        var Jr = /([\+\-]|\d\d)/gi;
        t.updateOffset = function() {};
        var Kr = /^(\-|\+)?(?:(\d*)[. ])?(\d+)\:(\d+)(?:\:(\d+)(\.\d*)?)?$/,
            Xr = /^(-|\+)?P(?:([-+]?[0-9,.]*)Y)?(?:([-+]?[0-9,.]*)M)?(?:([-+]?[0-9,.]*)W)?(?:([-+]?[0-9,.]*)D)?(?:T(?:([-+]?[0-9,.]*)H)?(?:([-+]?[0-9,.]*)M)?(?:([-+]?[0-9,.]*)S)?)?$/;
        Ze.fn = $e.prototype, Ze.invalid = Re;
        var ts = tn(1, "add"),
            es = tn(-1, "subtract");
        t.defaultFormat = "YYYY-MM-DDTHH:mm:ssZ", t.defaultFormatUtc = "YYYY-MM-DDTHH:mm:ss[Z]";
        var ns = C("moment().lang() is deprecated. Instead, use moment().localeData() to get the language configuration. Use moment().locale() to change languages.", function(t) {
            return void 0 === t ? this.localeData() : this.locale(t)
        });
        H(0, ["gg", 2], 0, function() {
            return this.weekYear() % 100
        }), H(0, ["GG", 2], 0, function() {
            return this.isoWeekYear() % 100
        }), Nn("gggg", "weekYear"), Nn("ggggg", "weekYear"), Nn("GGGG", "isoWeekYear"), Nn("GGGGG", "isoWeekYear"), V("weekYear", "gg"), V("isoWeekYear", "GG"), j("weekYear", 1), j("isoWeekYear", 1), W("G", ir), W("g", ir), W("GG", Zi, Wi), W("gg", Zi, Wi), W("GGGG", tr, Gi), W("gggg", tr, Gi), W("GGGGG", er, Qi), W("ggggg", er, Qi), J(["gggg", "ggggg", "GGGG", "GGGGG"], function(t, e, n, i) {
            e[i.substr(0, 2)] = _(t)
        }), J(["gg", "GG"], function(e, n, i, r) {
            n[r] = t.parseTwoDigitYear(e)
        }), H("Q", 0, "Qo", "quarter"), V("quarter", "Q"), j("quarter", 7), W("Q", qi), Z("Q", function(t, e) {
            e[cr] = 3 * (_(t) - 1)
        }), H("D", ["DD", 2], "Do", "date"), V("date", "D"), j("date", 9), W("D", Zi), W("DD", Zi, Wi), W("Do", function(t, e) {
            return t ? e._dayOfMonthOrdinalParse || e._ordinalParse : e._dayOfMonthOrdinalParseLenient
        }), Z(["D", "DD"], dr), Z("Do", function(t, e) {
            e[dr] = _(t.match(Zi)[0])
        });
        var is = nt("Date", !0);
        H("DDD", ["DDDD", 3], "DDDo", "dayOfYear"), V("dayOfYear", "DDD"), j("dayOfYear", 4), W("DDD", Xi), W("DDDD", zi), Z(["DDD", "DDDD"], function(t, e, n) {
            n._dayOfYear = _(t)
        }), H("m", ["mm", 2], 0, "minute"), V("minute", "m"), j("minute", 14), W("m", Zi), W("mm", Zi, Wi), Z(["m", "mm"], pr);
        var rs = nt("Minutes", !1);
        H("s", ["ss", 2], 0, "second"), V("second", "s"), j("second", 15), W("s", Zi), W("ss", Zi, Wi), Z(["s", "ss"], gr);
        var ss = nt("Seconds", !1);
        H("S", 0, 0, function() {
            return ~~(this.millisecond() / 100)
        }), H(0, ["SS", 2], 0, function() {
            return ~~(this.millisecond() / 10)
        }), H(0, ["SSS", 3], 0, "millisecond"), H(0, ["SSSS", 4], 0, function() {
            return 10 * this.millisecond()
        }), H(0, ["SSSSS", 5], 0, function() {
            return 100 * this.millisecond()
        }), H(0, ["SSSSSS", 6], 0, function() {
            return 1e3 * this.millisecond()
        }), H(0, ["SSSSSSS", 7], 0, function() {
            return 1e4 * this.millisecond()
        }), H(0, ["SSSSSSSS", 8], 0, function() {
            return 1e5 * this.millisecond()
        }), H(0, ["SSSSSSSSS", 9], 0, function() {
            return 1e6 * this.millisecond()
        }), V("millisecond", "ms"), j("millisecond", 16), W("S", Xi, qi), W("SS", Xi, Wi), W("SSS", Xi, zi);
        var os;
        for (os = "SSSS"; os.length <= 9; os += "S") W(os, nr);
        for (os = "S"; os.length <= 9; os += "S") Z(os, qn);
        var as = nt("Milliseconds", !1);
        H("z", 0, 0, "zoneAbbr"), H("zz", 0, 0, "zoneName");
        var ls = v.prototype;
        ls.add = ts, ls.calendar = rn, ls.clone = sn, ls.diff = dn, ls.endOf = Dn, ls.format = vn, ls.from = yn, ls.fromNow = wn, ls.to = _n, ls.toNow = bn, ls.get = st, ls.invalidAt = An, ls.isAfter = on, ls.isBefore = an, ls.isBetween = ln, ls.isSame = hn, ls.isSameOrAfter = un, ls.isSameOrBefore = cn, ls.isValid = $n, ls.lang = ns, ls.locale = xn, ls.localeData = Cn, ls.max = Gr, ls.min = zr, ls.parsingFlags = Pn, ls.set = ot, ls.startOf = Sn, ls.subtract = es, ls.toArray = On, ls.toObject = Mn, ls.toDate = Tn, ls.toISOString = gn, ls.inspect = mn, ls.toJSON = Rn, ls.toString = pn, ls.unix = kn, ls.valueOf = En, ls.creationData = Vn, ls.year = _r, ls.isLeapYear = et, ls.weekYear = In, ls.isoWeekYear = jn, ls.quarter = ls.quarters = Fn, ls.month = pt, ls.daysInMonth = gt, ls.week = ls.weeks = Tt, ls.isoWeek = ls.isoWeeks = Ot, ls.weeksInYear = Bn, ls.isoWeeksInYear = Ln, ls.date = is, ls.day = ls.days = It, ls.weekday = jt, ls.isoWeekday = Lt, ls.dayOfYear = Un, ls.hour = ls.hours = Vr, ls.minute = ls.minutes = rs, ls.second = ls.seconds = ss, ls.millisecond = ls.milliseconds = as, ls.utcOffset = Le, ls.utc = He, ls.local = Ye, ls.parseZone = Fe, ls.hasAlignedHourOffset = Ue, ls.isDST = qe, ls.isLocal = ze, ls.isUtcOffset = Ge, ls.isUtc = Qe, ls.isUTC = Qe, ls.zoneAbbr = Wn, ls.zoneName = zn, ls.dates = C("dates accessor is deprecated. Use date instead.", is), ls.months = C("months accessor is deprecated. Use month instead", pt), ls.years = C("years accessor is deprecated. Use year instead", _r), ls.zone = C("moment().zone is deprecated, use moment().utcOffset instead. http://momentjs.com/guides/#/warnings/zone/", Be), ls.isDSTShifted = C("isDSTShifted is deprecated. See http://momentjs.com/guides/#/warnings/dst-shifted/ for more information", We);
        var hs = T.prototype;
        hs.calendar = O, hs.longDateFormat = M, hs.invalidDate = R, hs.ordinal = $, hs.preparse = Zn, hs.postformat = Zn, hs.relativeTime = P, hs.pastFuture = A, hs.set = E, hs.months = ht, hs.monthsShort = ut, hs.monthsParse = dt, hs.monthsRegex = vt, hs.monthsShortRegex = mt, hs.week = Dt, hs.firstDayOfYear = kt, hs.firstDayOfWeek = Et, hs.weekdays = $t, hs.weekdaysMin = At, hs.weekdaysShort = Pt, hs.weekdaysParse = Nt, hs.weekdaysRegex = Bt, hs.weekdaysShortRegex = Ht, hs.weekdaysMinRegex = Yt, hs.isPM = Gt, hs.meridiem = Qt, Xt("en", {
            dayOfMonthOrdinalParse: /\d{1,2}(th|st|nd|rd)/,
            ordinal: function(t) {
                var e = t % 10,
                    n = 1 === _(t % 100 / 10) ? "th" : 1 === e ? "st" : 2 === e ? "nd" : 3 === e ? "rd" : "th";
                return t + n
            }
        }), t.lang = C("moment.lang is deprecated. Use moment.locale instead.", Xt), t.langData = C("moment.langData is deprecated. Use moment.localeData instead.", ne);
        var us = Math.abs,
            cs = gi("ms"),
            ds = gi("s"),
            fs = gi("m"),
            ps = gi("h"),
            gs = gi("d"),
            ms = gi("w"),
            vs = gi("M"),
            ys = gi("y"),
            ws = yi("milliseconds"),
            _s = yi("seconds"),
            bs = yi("minutes"),
            xs = yi("hours"),
            Cs = yi("days"),
            Ss = yi("months"),
            Ds = yi("years"),
            Es = Math.round,
            ks = {
                ss: 44,
                s: 45,
                m: 45,
                h: 22,
                d: 26,
                M: 11
            },
            Ts = Math.abs,
            Os = $e.prototype;
        return Os.isValid = Me, Os.abs = si, Os.add = ai, Os.subtract = li, Os.as = fi, Os.asMilliseconds = cs, Os.asSeconds = ds, Os.asMinutes = fs, Os.asHours = ps, Os.asDays = gs, Os.asWeeks = ms, Os.asMonths = vs, Os.asYears = ys, Os.valueOf = pi, Os._bubble = ui, Os.clone = mi, Os.get = vi, Os.milliseconds = ws, Os.seconds = _s, Os.minutes = bs, Os.hours = xs, Os.days = Cs, Os.weeks = wi, Os.months = Ss, Os.years = Ds, Os.humanize = Si, Os.toISOString = Ei, Os.toString = Ei, Os.toJSON = Ei, Os.locale = xn, Os.localeData = Cn, Os.toIsoString = C("toIsoString() is deprecated. Please use toISOString() instead (notice the capitals)", Ei), Os.lang = ns, H("X", 0, 0, "unix"), H("x", 0, 0, "valueOf"), W("x", ir), W("X", or), Z("X", function(t, e, n) {
            n._d = new Date(1e3 * parseFloat(t, 10))
        }), Z("x", function(t, e, n) {
            n._d = new Date(_(t))
        }), t.version = "2.20.1", e(De), t.fn = ls, t.min = ke, t.max = Te, t.now = Qr, t.utc = c, t.unix = Gn, t.months = ti, t.isDate = a, t.locale = Xt, t.invalid = g, t.duration = Ze, t.isMoment = y, t.weekdays = ni, t.parseZone = Qn, t.localeData = ne, t.isDuration = Pe, t.monthsShort = ei, t.weekdaysMin = ri, t.defineLocale = te, t.updateLocale = ee, t.locales = ie, t.weekdaysShort = ii, t.normalizeUnits = N, t.relativeTimeRounding = xi, t.relativeTimeThreshold = Ci, t.calendarFormat = nn, t.prototype = ls, t.HTML5_FMT = {
            DATETIME_LOCAL: "YYYY-MM-DDTHH:mm",
            DATETIME_LOCAL_SECONDS: "YYYY-MM-DDTHH:mm:ss",
            DATETIME_LOCAL_MS: "YYYY-MM-DDTHH:mm:ss.SSS",
            DATE: "YYYY-MM-DD",
            TIME: "HH:mm",
            TIME_SECONDS: "HH:mm:ss",
            TIME_MS: "HH:mm:ss.SSS",
            WEEK: "YYYY-[W]WW",
            MONTH: "YYYY-MM"
        }, t
    }), "undefined" == typeof jQuery) throw new Error("Bootstrap's JavaScript requires jQuery");
if (+ function(t) {
        "use strict";
        var e = t.fn.jquery.split(" ")[0].split(".");
        if (e[0] < 2 && e[1] < 9 || 1 == e[0] && 9 == e[1] && e[2] < 1 || e[0] > 3) throw new Error("Bootstrap's JavaScript requires jQuery version 1.9.1 or higher, but lower than version 4")
    }(jQuery), + function(t) {
        "use strict";

        function e() {
            var t = document.createElement("bootstrap"),
                e = {
                    WebkitTransition: "webkitTransitionEnd",
                    MozTransition: "transitionend",
                    OTransition: "oTransitionEnd otransitionend",
                    transition: "transitionend"
                };
            for (var n in e)
                if (void 0 !== t.style[n]) return {
                    end: e[n]
                };
            return !1
        }
        t.fn.emulateTransitionEnd = function(e) {
            var n = !1,
                i = this;
            t(this).one("bsTransitionEnd", function() {
                n = !0
            });
            var r = function() {
                n || t(i).trigger(t.support.transition.end)
            };
            return setTimeout(r, e), this
        }, t(function() {
            t.support.transition = e(), t.support.transition && (t.event.special.bsTransitionEnd = {
                bindType: t.support.transition.end,
                delegateType: t.support.transition.end,
                handle: function(e) {
                    if (t(e.target).is(this)) return e.handleObj.handler.apply(this, arguments)
                }
            })
        })
    }(jQuery), + function(t) {
        "use strict";

        function e(e) {
            return this.each(function() {
                var n = t(this),
                    r = n.data("bs.alert");
                r || n.data("bs.alert", r = new i(this)), "string" == typeof e && r[e].call(n)
            })
        }
        var n = '[data-dismiss="alert"]',
            i = function(e) {
                t(e).on("click", n, this.close)
            };
        i.VERSION = "3.3.7", i.TRANSITION_DURATION = 150, i.prototype.close = function(e) {
            function n() {
                o.detach().trigger("closed.bs.alert").remove()
            }
            var r = t(this),
                s = r.attr("data-target");
            s || (s = r.attr("href"), s = s && s.replace(/.*(?=#[^\s]*$)/, ""));
            var o = t("#" === s ? [] : s);
            e && e.preventDefault(), o.length || (o = r.closest(".alert")), o.trigger(e = t.Event("close.bs.alert")), e.isDefaultPrevented() || (o.removeClass("in"), t.support.transition && o.hasClass("fade") ? o.one("bsTransitionEnd", n).emulateTransitionEnd(i.TRANSITION_DURATION) : n())
        };
        var r = t.fn.alert;
        t.fn.alert = e, t.fn.alert.Constructor = i, t.fn.alert.noConflict = function() {
            return t.fn.alert = r, this
        }, t(document).on("click.bs.alert.data-api", n, i.prototype.close)
    }(jQuery), + function(t) {
        "use strict";

        function e(e) {
            return this.each(function() {
                var i = t(this),
                    r = i.data("bs.button"),
                    s = "object" == typeof e && e;
                r || i.data("bs.button", r = new n(this, s)), "toggle" == e ? r.toggle() : e && r.setState(e)
            })
        }
        var n = function(e, i) {
            this.$element = t(e), this.options = t.extend({}, n.DEFAULTS, i), this.isLoading = !1
        };
        n.VERSION = "3.3.7", n.DEFAULTS = {
            loadingText: "loading..."
        }, n.prototype.setState = function(e) {
            var n = "disabled",
                i = this.$element,
                r = i.is("input") ? "val" : "html",
                s = i.data();
            e += "Text", null == s.resetText && i.data("resetText", i[r]()), setTimeout(t.proxy(function() {
                i[r](null == s[e] ? this.options[e] : s[e]), "loadingText" == e ? (this.isLoading = !0, i.addClass(n).attr(n, n).prop(n, !0)) : this.isLoading && (this.isLoading = !1, i.removeClass(n).removeAttr(n).prop(n, !1))
            }, this), 0)
        }, n.prototype.toggle = function() {
            var t = !0,
                e = this.$element.closest('[data-toggle="buttons"]');
            if (e.length) {
                var n = this.$element.find("input");
                "radio" == n.prop("type") ? (n.prop("checked") && (t = !1), e.find(".active").removeClass("active"), this.$element.addClass("active")) : "checkbox" == n.prop("type") && (n.prop("checked") !== this.$element.hasClass("active") && (t = !1), this.$element.toggleClass("active")), n.prop("checked", this.$element.hasClass("active")), t && n.trigger("change")
            } else this.$element.attr("aria-pressed", !this.$element.hasClass("active")), this.$element.toggleClass("active")
        };
        var i = t.fn.button;
        t.fn.button = e, t.fn.button.Constructor = n, t.fn.button.noConflict = function() {
            return t.fn.button = i, this
        }, t(document).on("click.bs.button.data-api", '[data-toggle^="button"]', function(n) {
            var i = t(n.target).closest(".btn");
            e.call(i, "toggle"), t(n.target).is('input[type="radio"], input[type="checkbox"]') || (n.preventDefault(), i.is("input,button") ? i.trigger("focus") : i.find("input:visible,button:visible").first().trigger("focus"))
        }).on("focus.bs.button.data-api blur.bs.button.data-api", '[data-toggle^="button"]', function(e) {
            t(e.target).closest(".btn").toggleClass("focus", /^focus(in)?$/.test(e.type))
        })
    }(jQuery), + function(t) {
        "use strict";

        function e(e) {
            return this.each(function() {
                var i = t(this),
                    r = i.data("bs.carousel"),
                    s = t.extend({}, n.DEFAULTS, i.data(), "object" == typeof e && e),
                    o = "string" == typeof e ? e : s.slide;
                r || i.data("bs.carousel", r = new n(this, s)), "number" == typeof e ? r.to(e) : o ? r[o]() : s.interval && r.pause().cycle()
            })
        }
        var n = function(e, n) {
            this.$element = t(e), this.$indicators = this.$element.find(".carousel-indicators"), this.options = n, this.paused = null, this.sliding = null, this.interval = null, this.$active = null, this.$items = null, this.options.keyboard && this.$element.on("keydown.bs.carousel", t.proxy(this.keydown, this)), "hover" == this.options.pause && !("ontouchstart" in document.documentElement) && this.$element.on("mouseenter.bs.carousel", t.proxy(this.pause, this)).on("mouseleave.bs.carousel", t.proxy(this.cycle, this))
        };
        n.VERSION = "3.3.7", n.TRANSITION_DURATION = 600, n.DEFAULTS = {
            interval: 5e3,
            pause: "hover",
            wrap: !0,
            keyboard: !0
        }, n.prototype.keydown = function(t) {
            if (!/input|textarea/i.test(t.target.tagName)) {
                switch (t.which) {
                    case 37:
                        this.prev();
                        break;
                    case 39:
                        this.next();
                        break;
                    default:
                        return
                }
                t.preventDefault()
            }
        }, n.prototype.cycle = function(e) {
            return e || (this.paused = !1), this.interval && clearInterval(this.interval), this.options.interval && !this.paused && (this.interval = setInterval(t.proxy(this.next, this), this.options.interval)), this
        }, n.prototype.getItemIndex = function(t) {
            return this.$items = t.parent().children(".item"), this.$items.index(t || this.$active)
        }, n.prototype.getItemForDirection = function(t, e) {
            var n = this.getItemIndex(e),
                i = "prev" == t && 0 === n || "next" == t && n == this.$items.length - 1;
            if (i && !this.options.wrap) return e;
            var r = "prev" == t ? -1 : 1,
                s = (n + r) % this.$items.length;
            return this.$items.eq(s)
        }, n.prototype.to = function(t) {
            var e = this,
                n = this.getItemIndex(this.$active = this.$element.find(".item.active"));
            if (!(t > this.$items.length - 1 || t < 0)) return this.sliding ? this.$element.one("slid.bs.carousel", function() {
                e.to(t)
            }) : n == t ? this.pause().cycle() : this.slide(t > n ? "next" : "prev", this.$items.eq(t))
        }, n.prototype.pause = function(e) {
            return e || (this.paused = !0), this.$element.find(".next, .prev").length && t.support.transition && (this.$element.trigger(t.support.transition.end), this.cycle(!0)), this.interval = clearInterval(this.interval), this
        }, n.prototype.next = function() {
            if (!this.sliding) return this.slide("next")
        }, n.prototype.prev = function() {
            if (!this.sliding) return this.slide("prev")
        }, n.prototype.slide = function(e, i) {
            var r = this.$element.find(".item.active"),
                s = i || this.getItemForDirection(e, r),
                o = this.interval,
                a = "next" == e ? "left" : "right",
                l = this;
            if (s.hasClass("active")) return this.sliding = !1;
            var h = s[0],
                u = t.Event("slide.bs.carousel", {
                    relatedTarget: h,
                    direction: a
                });
            if (this.$element.trigger(u), !u.isDefaultPrevented()) {
                if (this.sliding = !0, o && this.pause(), this.$indicators.length) {
                    this.$indicators.find(".active").removeClass("active");
                    var c = t(this.$indicators.children()[this.getItemIndex(s)]);
                    c && c.addClass("active")
                }
                var d = t.Event("slid.bs.carousel", {
                    relatedTarget: h,
                    direction: a
                });
                return t.support.transition && this.$element.hasClass("slide") ? (s.addClass(e), s[0].offsetWidth, r.addClass(a), s.addClass(a), r.one("bsTransitionEnd", function() {
                    s.removeClass([e, a].join(" ")).addClass("active"), r.removeClass(["active", a].join(" ")), l.sliding = !1, setTimeout(function() {
                        l.$element.trigger(d)
                    }, 0)
                }).emulateTransitionEnd(n.TRANSITION_DURATION)) : (r.removeClass("active"), s.addClass("active"), this.sliding = !1, this.$element.trigger(d)), o && this.cycle(), this
            }
        };
        var i = t.fn.carousel;
        t.fn.carousel = e, t.fn.carousel.Constructor = n, t.fn.carousel.noConflict = function() {
            return t.fn.carousel = i, this
        };
        var r = function(n) {
            var i, r = t(this),
                s = t(r.attr("data-target") || (i = r.attr("href")) && i.replace(/.*(?=#[^\s]+$)/, ""));
            if (s.hasClass("carousel")) {
                var o = t.extend({}, s.data(), r.data()),
                    a = r.attr("data-slide-to");
                a && (o.interval = !1), e.call(s, o), a && s.data("bs.carousel").to(a), n.preventDefault()
            }
        };
        t(document).on("click.bs.carousel.data-api", "[data-slide]", r).on("click.bs.carousel.data-api", "[data-slide-to]", r), t(window).on("load", function() {
            t('[data-ride="carousel"]').each(function() {
                var n = t(this);
                e.call(n, n.data())
            })
        })
    }(jQuery), + function(t) {
        "use strict";

        function e(e) {
            var n, i = e.attr("data-target") || (n = e.attr("href")) && n.replace(/.*(?=#[^\s]+$)/, "");
            return t(i)
        }

        function n(e) {
            return this.each(function() {
                var n = t(this),
                    r = n.data("bs.collapse"),
                    s = t.extend({}, i.DEFAULTS, n.data(), "object" == typeof e && e);
                !r && s.toggle && /show|hide/.test(e) && (s.toggle = !1), r || n.data("bs.collapse", r = new i(this, s)), "string" == typeof e && r[e]()
            })
        }
        var i = function(e, n) {
            this.$element = t(e), this.options = t.extend({}, i.DEFAULTS, n), this.$trigger = t('[data-toggle="collapse"][href="#' + e.id + '"],[data-toggle="collapse"][data-target="#' + e.id + '"]'), this.transitioning = null, this.options.parent ? this.$parent = this.getParent() : this.addAriaAndCollapsedClass(this.$element, this.$trigger), this.options.toggle && this.toggle()
        };
        i.VERSION = "3.3.7", i.TRANSITION_DURATION = 350, i.DEFAULTS = {
            toggle: !0
        }, i.prototype.dimension = function() {
            var t = this.$element.hasClass("width");
            return t ? "width" : "height"
        }, i.prototype.show = function() {
            if (!this.transitioning && !this.$element.hasClass("in")) {
                var e, r = this.$parent && this.$parent.children(".panel").children(".in, .collapsing");
                if (!(r && r.length && (e = r.data("bs.collapse"), e && e.transitioning))) {
                    var s = t.Event("show.bs.collapse");
                    if (this.$element.trigger(s), !s.isDefaultPrevented()) {
                        r && r.length && (n.call(r, "hide"), e || r.data("bs.collapse", null));
                        var o = this.dimension();
                        this.$element.removeClass("collapse").addClass("collapsing")[o](0).attr("aria-expanded", !0), this.$trigger.removeClass("collapsed").attr("aria-expanded", !0), this.transitioning = 1;
                        var a = function() {
                            this.$element.removeClass("collapsing").addClass("collapse in")[o](""), this.transitioning = 0, this.$element.trigger("shown.bs.collapse")
                        };
                        if (!t.support.transition) return a.call(this);
                        var l = t.camelCase(["scroll", o].join("-"));
                        this.$element.one("bsTransitionEnd", t.proxy(a, this)).emulateTransitionEnd(i.TRANSITION_DURATION)[o](this.$element[0][l])
                    }
                }
            }
        }, i.prototype.hide = function() {
            if (!this.transitioning && this.$element.hasClass("in")) {
                var e = t.Event("hide.bs.collapse");
                if (this.$element.trigger(e), !e.isDefaultPrevented()) {
                    var n = this.dimension();
                    this.$element[n](this.$element[n]())[0].offsetHeight, this.$element.addClass("collapsing").removeClass("collapse in").attr("aria-expanded", !1), this.$trigger.addClass("collapsed").attr("aria-expanded", !1), this.transitioning = 1;
                    var r = function() {
                        this.transitioning = 0, this.$element.removeClass("collapsing").addClass("collapse").trigger("hidden.bs.collapse")
                    };
                    return t.support.transition ? void this.$element[n](0).one("bsTransitionEnd", t.proxy(r, this)).emulateTransitionEnd(i.TRANSITION_DURATION) : r.call(this)
                }
            }
        }, i.prototype.toggle = function() {
            this[this.$element.hasClass("in") ? "hide" : "show"]()
        }, i.prototype.getParent = function() {
            return t(this.options.parent).find('[data-toggle="collapse"][data-parent="' + this.options.parent + '"]').each(t.proxy(function(n, i) {
                var r = t(i);
                this.addAriaAndCollapsedClass(e(r), r)
            }, this)).end()
        }, i.prototype.addAriaAndCollapsedClass = function(t, e) {
            var n = t.hasClass("in");
            t.attr("aria-expanded", n), e.toggleClass("collapsed", !n).attr("aria-expanded", n)
        };
        var r = t.fn.collapse;
        t.fn.collapse = n, t.fn.collapse.Constructor = i, t.fn.collapse.noConflict = function() {
            return t.fn.collapse = r, this
        }, t(document).on("click.bs.collapse.data-api", '[data-toggle="collapse"]', function(i) {
            var r = t(this);
            r.attr("data-target") || i.preventDefault();
            var s = e(r),
                o = s.data("bs.collapse"),
                a = o ? "toggle" : r.data();
            n.call(s, a)
        })
    }(jQuery), + function(t) {
        "use strict";

        function e(e) {
            var n = e.attr("data-target");
            n || (n = e.attr("href"), n = n && /#[A-Za-z]/.test(n) && n.replace(/.*(?=#[^\s]*$)/, ""));
            var i = n && t(n);
            return i && i.length ? i : e.parent()
        }

        function n(n) {
            n && 3 === n.which || (t(r).remove(), t(s).each(function() {
                var i = t(this),
                    r = e(i),
                    s = {
                        relatedTarget: this
                    };
                r.hasClass("open") && (n && "click" == n.type && /input|textarea/i.test(n.target.tagName) && t.contains(r[0], n.target) || (r.trigger(n = t.Event("hide.bs.dropdown", s)),
                    n.isDefaultPrevented() || (i.attr("aria-expanded", "false"), r.removeClass("open").trigger(t.Event("hidden.bs.dropdown", s)))))
            }))
        }

        function i(e) {
            return this.each(function() {
                var n = t(this),
                    i = n.data("bs.dropdown");
                i || n.data("bs.dropdown", i = new o(this)), "string" == typeof e && i[e].call(n)
            })
        }
        var r = ".dropdown-backdrop",
            s = '[data-toggle="dropdown"]',
            o = function(e) {
                t(e).on("click.bs.dropdown", this.toggle)
            };
        o.VERSION = "3.3.7", o.prototype.toggle = function(i) {
            var r = t(this);
            if (!r.is(".disabled, :disabled")) {
                var s = e(r),
                    o = s.hasClass("open");
                if (n(), !o) {
                    "ontouchstart" in document.documentElement && !s.closest(".navbar-nav").length && t(document.createElement("div")).addClass("dropdown-backdrop").insertAfter(t(this)).on("click", n);
                    var a = {
                        relatedTarget: this
                    };
                    if (s.trigger(i = t.Event("show.bs.dropdown", a)), i.isDefaultPrevented()) return;
                    r.trigger("focus").attr("aria-expanded", "true"), s.toggleClass("open").trigger(t.Event("shown.bs.dropdown", a))
                }
                return !1
            }
        }, o.prototype.keydown = function(n) {
            if (/(38|40|27|32)/.test(n.which) && !/input|textarea/i.test(n.target.tagName)) {
                var i = t(this);
                if (n.preventDefault(), n.stopPropagation(), !i.is(".disabled, :disabled")) {
                    var r = e(i),
                        o = r.hasClass("open");
                    if (!o && 27 != n.which || o && 27 == n.which) return 27 == n.which && r.find(s).trigger("focus"), i.trigger("click");
                    var a = " li:not(.disabled):visible a",
                        l = r.find(".dropdown-menu" + a);
                    if (l.length) {
                        var h = l.index(n.target);
                        38 == n.which && h > 0 && h--, 40 == n.which && h < l.length - 1 && h++, ~h || (h = 0), l.eq(h).trigger("focus")
                    }
                }
            }
        };
        var a = t.fn.dropdown;
        t.fn.dropdown = i, t.fn.dropdown.Constructor = o, t.fn.dropdown.noConflict = function() {
            return t.fn.dropdown = a, this
        }, t(document).on("click.bs.dropdown.data-api", n).on("click.bs.dropdown.data-api", ".dropdown form", function(t) {
            t.stopPropagation()
        }).on("click.bs.dropdown.data-api", s, o.prototype.toggle).on("keydown.bs.dropdown.data-api", s, o.prototype.keydown).on("keydown.bs.dropdown.data-api", ".dropdown-menu", o.prototype.keydown)
    }(jQuery), + function(t) {
        "use strict";

        function e(e, i) {
            return this.each(function() {
                var r = t(this),
                    s = r.data("bs.modal"),
                    o = t.extend({}, n.DEFAULTS, r.data(), "object" == typeof e && e);
                s || r.data("bs.modal", s = new n(this, o)), "string" == typeof e ? s[e](i) : o.show && s.show(i)
            })
        }
        var n = function(e, n) {
            this.options = n, this.$body = t(document.body), this.$element = t(e), this.$dialog = this.$element.find(".modal-dialog"), this.$backdrop = null, this.isShown = null, this.originalBodyPad = null, this.scrollbarWidth = 0, this.ignoreBackdropClick = !1, this.options.remote && this.$element.find(".modal-content").load(this.options.remote, t.proxy(function() {
                this.$element.trigger("loaded.bs.modal")
            }, this))
        };
        n.VERSION = "3.3.7", n.TRANSITION_DURATION = 300, n.BACKDROP_TRANSITION_DURATION = 150, n.DEFAULTS = {
            backdrop: !0,
            keyboard: !0,
            show: !0
        }, n.prototype.toggle = function(t) {
            return this.isShown ? this.hide() : this.show(t)
        }, n.prototype.show = function(e) {
            var i = this,
                r = t.Event("show.bs.modal", {
                    relatedTarget: e
                });
            this.$element.trigger(r), this.isShown || r.isDefaultPrevented() || (this.isShown = !0, this.checkScrollbar(), this.setScrollbar(), this.$body.addClass("modal-open"), this.escape(), this.resize(), this.$element.on("click.dismiss.bs.modal", '[data-dismiss="modal"]', t.proxy(this.hide, this)), this.$dialog.on("mousedown.dismiss.bs.modal", function() {
                i.$element.one("mouseup.dismiss.bs.modal", function(e) {
                    t(e.target).is(i.$element) && (i.ignoreBackdropClick = !0)
                })
            }), this.backdrop(function() {
                var r = t.support.transition && i.$element.hasClass("fade");
                i.$element.parent().length || i.$element.appendTo(i.$body), i.$element.show().scrollTop(0), i.adjustDialog(), r && i.$element[0].offsetWidth, i.$element.addClass("in"), i.enforceFocus();
                var s = t.Event("shown.bs.modal", {
                    relatedTarget: e
                });
                r ? i.$dialog.one("bsTransitionEnd", function() {
                    i.$element.trigger("focus").trigger(s)
                }).emulateTransitionEnd(n.TRANSITION_DURATION) : i.$element.trigger("focus").trigger(s)
            }))
        }, n.prototype.hide = function(e) {
            e && e.preventDefault(), e = t.Event("hide.bs.modal"), this.$element.trigger(e), this.isShown && !e.isDefaultPrevented() && (this.isShown = !1, this.escape(), this.resize(), t(document).off("focusin.bs.modal"), this.$element.removeClass("in").off("click.dismiss.bs.modal").off("mouseup.dismiss.bs.modal"), this.$dialog.off("mousedown.dismiss.bs.modal"), t.support.transition && this.$element.hasClass("fade") ? this.$element.one("bsTransitionEnd", t.proxy(this.hideModal, this)).emulateTransitionEnd(n.TRANSITION_DURATION) : this.hideModal())
        }, n.prototype.enforceFocus = function() {
            t(document).off("focusin.bs.modal").on("focusin.bs.modal", t.proxy(function(t) {
                document === t.target || this.$element[0] === t.target || this.$element.has(t.target).length || this.$element.trigger("focus")
            }, this))
        }, n.prototype.escape = function() {
            this.isShown && this.options.keyboard ? this.$element.on("keydown.dismiss.bs.modal", t.proxy(function(t) {
                27 == t.which && this.hide()
            }, this)) : this.isShown || this.$element.off("keydown.dismiss.bs.modal")
        }, n.prototype.resize = function() {
            this.isShown ? t(window).on("resize.bs.modal", t.proxy(this.handleUpdate, this)) : t(window).off("resize.bs.modal")
        }, n.prototype.hideModal = function() {
            var t = this;
            this.$element.hide(), this.backdrop(function() {
                t.$body.removeClass("modal-open"), t.resetAdjustments(), t.resetScrollbar(), t.$element.trigger("hidden.bs.modal")
            })
        }, n.prototype.removeBackdrop = function() {
            this.$backdrop && this.$backdrop.remove(), this.$backdrop = null
        }, n.prototype.backdrop = function(e) {
            var i = this,
                r = this.$element.hasClass("fade") ? "fade" : "";
            if (this.isShown && this.options.backdrop) {
                var s = t.support.transition && r;
                if (this.$backdrop = t(document.createElement("div")).addClass("modal-backdrop " + r).appendTo(this.$body), this.$element.on("click.dismiss.bs.modal", t.proxy(function(t) {
                        return this.ignoreBackdropClick ? void(this.ignoreBackdropClick = !1) : void(t.target === t.currentTarget && ("static" == this.options.backdrop ? this.$element[0].focus() : this.hide()))
                    }, this)), s && this.$backdrop[0].offsetWidth, this.$backdrop.addClass("in"), !e) return;
                s ? this.$backdrop.one("bsTransitionEnd", e).emulateTransitionEnd(n.BACKDROP_TRANSITION_DURATION) : e()
            } else if (!this.isShown && this.$backdrop) {
                this.$backdrop.removeClass("in");
                var o = function() {
                    i.removeBackdrop(), e && e()
                };
                t.support.transition && this.$element.hasClass("fade") ? this.$backdrop.one("bsTransitionEnd", o).emulateTransitionEnd(n.BACKDROP_TRANSITION_DURATION) : o()
            } else e && e()
        }, n.prototype.handleUpdate = function() {
            this.adjustDialog()
        }, n.prototype.adjustDialog = function() {
            var t = this.$element[0].scrollHeight > document.documentElement.clientHeight;
            this.$element.css({
                paddingLeft: !this.bodyIsOverflowing && t ? this.scrollbarWidth : "",
                paddingRight: this.bodyIsOverflowing && !t ? this.scrollbarWidth : ""
            })
        }, n.prototype.resetAdjustments = function() {
            this.$element.css({
                paddingLeft: "",
                paddingRight: ""
            })
        }, n.prototype.checkScrollbar = function() {
            var t = window.innerWidth;
            if (!t) {
                var e = document.documentElement.getBoundingClientRect();
                t = e.right - Math.abs(e.left)
            }
            this.bodyIsOverflowing = document.body.clientWidth < t, this.scrollbarWidth = this.measureScrollbar()
        }, n.prototype.setScrollbar = function() {
            var t = parseInt(this.$body.css("padding-right") || 0, 10);
            this.originalBodyPad = document.body.style.paddingRight || "", this.bodyIsOverflowing && this.$body.css("padding-right", t + this.scrollbarWidth)
        }, n.prototype.resetScrollbar = function() {
            this.$body.css("padding-right", this.originalBodyPad)
        }, n.prototype.measureScrollbar = function() {
            var t = document.createElement("div");
            t.className = "modal-scrollbar-measure", this.$body.append(t);
            var e = t.offsetWidth - t.clientWidth;
            return this.$body[0].removeChild(t), e
        };
        var i = t.fn.modal;
        t.fn.modal = e, t.fn.modal.Constructor = n, t.fn.modal.noConflict = function() {
            return t.fn.modal = i, this
        }, t(document).on("click.bs.modal.data-api", '[data-toggle="modal"]', function(n) {
            var i = t(this),
                r = i.attr("href"),
                s = t(i.attr("data-target") || r && r.replace(/.*(?=#[^\s]+$)/, "")),
                o = s.data("bs.modal") ? "toggle" : t.extend({
                    remote: !/#/.test(r) && r
                }, s.data(), i.data());
            i.is("a") && n.preventDefault(), s.one("show.bs.modal", function(t) {
                t.isDefaultPrevented() || s.one("hidden.bs.modal", function() {
                    i.is(":visible") && i.trigger("focus")
                })
            }), e.call(s, o, this)
        })
    }(jQuery), + function(t) {
        "use strict";

        function e(e) {
            return this.each(function() {
                var i = t(this),
                    r = i.data("bs.tooltip"),
                    s = "object" == typeof e && e;
                !r && /destroy|hide/.test(e) || (r || i.data("bs.tooltip", r = new n(this, s)), "string" == typeof e && r[e]())
            })
        }
        var n = function(t, e) {
            this.type = null, this.options = null, this.enabled = null, this.timeout = null, this.hoverState = null, this.$element = null, this.inState = null, this.init("tooltip", t, e)
        };
        n.VERSION = "3.3.7", n.TRANSITION_DURATION = 150, n.DEFAULTS = {
            animation: !0,
            placement: "top",
            selector: !1,
            template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
            trigger: "hover focus",
            title: "",
            delay: 0,
            html: !1,
            container: !1,
            viewport: {
                selector: "body",
                padding: 0
            }
        }, n.prototype.init = function(e, n, i) {
            if (this.enabled = !0, this.type = e, this.$element = t(n), this.options = this.getOptions(i), this.$viewport = this.options.viewport && t(t.isFunction(this.options.viewport) ? this.options.viewport.call(this, this.$element) : this.options.viewport.selector || this.options.viewport), this.inState = {
                    click: !1,
                    hover: !1,
                    focus: !1
                }, this.$element[0] instanceof document.constructor && !this.options.selector) throw new Error("`selector` option must be specified when initializing " + this.type + " on the window.document object!");
            for (var r = this.options.trigger.split(" "), s = r.length; s--;) {
                var o = r[s];
                if ("click" == o) this.$element.on("click." + this.type, this.options.selector, t.proxy(this.toggle, this));
                else if ("manual" != o) {
                    var a = "hover" == o ? "mouseenter" : "focusin",
                        l = "hover" == o ? "mouseleave" : "focusout";
                    this.$element.on(a + "." + this.type, this.options.selector, t.proxy(this.enter, this)), this.$element.on(l + "." + this.type, this.options.selector, t.proxy(this.leave, this))
                }
            }
            this.options.selector ? this._options = t.extend({}, this.options, {
                trigger: "manual",
                selector: ""
            }) : this.fixTitle()
        }, n.prototype.getDefaults = function() {
            return n.DEFAULTS
        }, n.prototype.getOptions = function(e) {
            return e = t.extend({}, this.getDefaults(), this.$element.data(), e), e.delay && "number" == typeof e.delay && (e.delay = {
                show: e.delay,
                hide: e.delay
            }), e
        }, n.prototype.getDelegateOptions = function() {
            var e = {},
                n = this.getDefaults();
            return this._options && t.each(this._options, function(t, i) {
                n[t] != i && (e[t] = i)
            }), e
        }, n.prototype.enter = function(e) {
            var n = e instanceof this.constructor ? e : t(e.currentTarget).data("bs." + this.type);
            return n || (n = new this.constructor(e.currentTarget, this.getDelegateOptions()), t(e.currentTarget).data("bs." + this.type, n)), e instanceof t.Event && (n.inState["focusin" == e.type ? "focus" : "hover"] = !0), n.tip().hasClass("in") || "in" == n.hoverState ? void(n.hoverState = "in") : (clearTimeout(n.timeout), n.hoverState = "in", n.options.delay && n.options.delay.show ? void(n.timeout = setTimeout(function() {
                "in" == n.hoverState && n.show()
            }, n.options.delay.show)) : n.show())
        }, n.prototype.isInStateTrue = function() {
            for (var t in this.inState)
                if (this.inState[t]) return !0;
            return !1
        }, n.prototype.leave = function(e) {
            var n = e instanceof this.constructor ? e : t(e.currentTarget).data("bs." + this.type);
            if (n || (n = new this.constructor(e.currentTarget, this.getDelegateOptions()), t(e.currentTarget).data("bs." + this.type, n)), e instanceof t.Event && (n.inState["focusout" == e.type ? "focus" : "hover"] = !1), !n.isInStateTrue()) return clearTimeout(n.timeout), n.hoverState = "out", n.options.delay && n.options.delay.hide ? void(n.timeout = setTimeout(function() {
                "out" == n.hoverState && n.hide()
            }, n.options.delay.hide)) : n.hide()
        }, n.prototype.show = function() {
            var e = t.Event("show.bs." + this.type);
            if (this.hasContent() && this.enabled) {
                this.$element.trigger(e);
                var i = t.contains(this.$element[0].ownerDocument.documentElement, this.$element[0]);
                if (e.isDefaultPrevented() || !i) return;
                var r = this,
                    s = this.tip(),
                    o = this.getUID(this.type);
                this.setContent(), s.attr("id", o), this.$element.attr("aria-describedby", o), this.options.animation && s.addClass("fade");
                var a = "function" == typeof this.options.placement ? this.options.placement.call(this, s[0], this.$element[0]) : this.options.placement,
                    l = /\s?auto?\s?/i,
                    h = l.test(a);
                h && (a = a.replace(l, "") || "top"), s.detach().css({
                    top: 0,
                    left: 0,
                    display: "block"
                }).addClass(a).data("bs." + this.type, this), this.options.container ? s.appendTo(this.options.container) : s.insertAfter(this.$element), this.$element.trigger("inserted.bs." + this.type);
                var u = this.getPosition(),
                    c = s[0].offsetWidth,
                    d = s[0].offsetHeight;
                if (h) {
                    var f = a,
                        p = this.getPosition(this.$viewport);
                    a = "bottom" == a && u.bottom + d > p.bottom ? "top" : "top" == a && u.top - d < p.top ? "bottom" : "right" == a && u.right + c > p.width ? "left" : "left" == a && u.left - c < p.left ? "right" : a, s.removeClass(f).addClass(a)
                }
                var g = this.getCalculatedOffset(a, u, c, d);
                this.applyPlacement(g, a);
                var m = function() {
                    var t = r.hoverState;
                    r.$element.trigger("shown.bs." + r.type), r.hoverState = null, "out" == t && r.leave(r)
                };
                t.support.transition && this.$tip.hasClass("fade") ? s.one("bsTransitionEnd", m).emulateTransitionEnd(n.TRANSITION_DURATION) : m()
            }
        }, n.prototype.applyPlacement = function(e, n) {
            var i = this.tip(),
                r = i[0].offsetWidth,
                s = i[0].offsetHeight,
                o = parseInt(i.css("margin-top"), 10),
                a = parseInt(i.css("margin-left"), 10);
            isNaN(o) && (o = 0), isNaN(a) && (a = 0), e.top += o, e.left += a, t.offset.setOffset(i[0], t.extend({
                using: function(t) {
                    i.css({
                        top: Math.round(t.top),
                        left: Math.round(t.left)
                    })
                }
            }, e), 0), i.addClass("in");
            var l = i[0].offsetWidth,
                h = i[0].offsetHeight;
            "top" == n && h != s && (e.top = e.top + s - h);
            var u = this.getViewportAdjustedDelta(n, e, l, h);
            u.left ? e.left += u.left : e.top += u.top;
            var c = /top|bottom/.test(n),
                d = c ? 2 * u.left - r + l : 2 * u.top - s + h,
                f = c ? "offsetWidth" : "offsetHeight";
            i.offset(e), this.replaceArrow(d, i[0][f], c)
        }, n.prototype.replaceArrow = function(t, e, n) {
            this.arrow().css(n ? "left" : "top", 50 * (1 - t / e) + "%").css(n ? "top" : "left", "")
        }, n.prototype.setContent = function() {
            var t = this.tip(),
                e = this.getTitle();
            t.find(".tooltip-inner")[this.options.html ? "html" : "text"](e), t.removeClass("fade in top bottom left right")
        }, n.prototype.hide = function(e) {
            function i() {
                "in" != r.hoverState && s.detach(), r.$element && r.$element.removeAttr("aria-describedby").trigger("hidden.bs." + r.type), e && e()
            }
            var r = this,
                s = t(this.$tip),
                o = t.Event("hide.bs." + this.type);
            if (this.$element.trigger(o), !o.isDefaultPrevented()) return s.removeClass("in"), t.support.transition && s.hasClass("fade") ? s.one("bsTransitionEnd", i).emulateTransitionEnd(n.TRANSITION_DURATION) : i(), this.hoverState = null, this
        }, n.prototype.fixTitle = function() {
            var t = this.$element;
            (t.attr("title") || "string" != typeof t.attr("data-original-title")) && t.attr("data-original-title", t.attr("title") || "").attr("title", "")
        }, n.prototype.hasContent = function() {
            return this.getTitle()
        }, n.prototype.getPosition = function(e) {
            e = e || this.$element;
            var n = e[0],
                i = "BODY" == n.tagName,
                r = n.getBoundingClientRect();
            null == r.width && (r = t.extend({}, r, {
                width: r.right - r.left,
                height: r.bottom - r.top
            }));
            var s = window.SVGElement && n instanceof window.SVGElement,
                o = i ? {
                    top: 0,
                    left: 0
                } : s ? null : e.offset(),
                a = {
                    scroll: i ? document.documentElement.scrollTop || document.body.scrollTop : e.scrollTop()
                },
                l = i ? {
                    width: t(window).width(),
                    height: t(window).height()
                } : null;
            return t.extend({}, r, a, l, o)
        }, n.prototype.getCalculatedOffset = function(t, e, n, i) {
            return "bottom" == t ? {
                top: e.top + e.height,
                left: e.left + e.width / 2 - n / 2
            } : "top" == t ? {
                top: e.top - i,
                left: e.left + e.width / 2 - n / 2
            } : "left" == t ? {
                top: e.top + e.height / 2 - i / 2,
                left: e.left - n
            } : {
                top: e.top + e.height / 2 - i / 2,
                left: e.left + e.width
            }
        }, n.prototype.getViewportAdjustedDelta = function(t, e, n, i) {
            var r = {
                top: 0,
                left: 0
            };
            if (!this.$viewport) return r;
            var s = this.options.viewport && this.options.viewport.padding || 0,
                o = this.getPosition(this.$viewport);
            if (/right|left/.test(t)) {
                var a = e.top - s - o.scroll,
                    l = e.top + s - o.scroll + i;
                a < o.top ? r.top = o.top - a : l > o.top + o.height && (r.top = o.top + o.height - l)
            } else {
                var h = e.left - s,
                    u = e.left + s + n;
                h < o.left ? r.left = o.left - h : u > o.right && (r.left = o.left + o.width - u)
            }
            return r
        }, n.prototype.getTitle = function() {
            var t, e = this.$element,
                n = this.options;
            return t = e.attr("data-original-title") || ("function" == typeof n.title ? n.title.call(e[0]) : n.title)
        }, n.prototype.getUID = function(t) {
            do t += ~~(1e6 * Math.random()); while (document.getElementById(t));
            return t
        }, n.prototype.tip = function() {
            if (!this.$tip && (this.$tip = t(this.options.template), 1 != this.$tip.length)) throw new Error(this.type + " `template` option must consist of exactly 1 top-level element!");
            return this.$tip
        }, n.prototype.arrow = function() {
            return this.$arrow = this.$arrow || this.tip().find(".tooltip-arrow")
        }, n.prototype.enable = function() {
            this.enabled = !0
        }, n.prototype.disable = function() {
            this.enabled = !1
        }, n.prototype.toggleEnabled = function() {
            this.enabled = !this.enabled
        }, n.prototype.toggle = function(e) {
            var n = this;
            e && (n = t(e.currentTarget).data("bs." + this.type), n || (n = new this.constructor(e.currentTarget, this.getDelegateOptions()), t(e.currentTarget).data("bs." + this.type, n))), e ? (n.inState.click = !n.inState.click, n.isInStateTrue() ? n.enter(n) : n.leave(n)) : n.tip().hasClass("in") ? n.leave(n) : n.enter(n)
        }, n.prototype.destroy = function() {
            var t = this;
            clearTimeout(this.timeout), this.hide(function() {
                t.$element.off("." + t.type).removeData("bs." + t.type), t.$tip && t.$tip.detach(), t.$tip = null, t.$arrow = null, t.$viewport = null, t.$element = null
            })
        };
        var i = t.fn.tooltip;
        t.fn.tooltip = e, t.fn.tooltip.Constructor = n, t.fn.tooltip.noConflict = function() {
            return t.fn.tooltip = i, this
        }
    }(jQuery), + function(t) {
        "use strict";

        function e(e) {
            return this.each(function() {
                var i = t(this),
                    r = i.data("bs.popover"),
                    s = "object" == typeof e && e;
                !r && /destroy|hide/.test(e) || (r || i.data("bs.popover", r = new n(this, s)), "string" == typeof e && r[e]())
            })
        }
        var n = function(t, e) {
            this.init("popover", t, e)
        };
        if (!t.fn.tooltip) throw new Error("Popover requires tooltip.js");
        n.VERSION = "3.3.7", n.DEFAULTS = t.extend({}, t.fn.tooltip.Constructor.DEFAULTS, {
            placement: "right",
            trigger: "click",
            content: "",
            template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
        }), n.prototype = t.extend({}, t.fn.tooltip.Constructor.prototype), n.prototype.constructor = n, n.prototype.getDefaults = function() {
            return n.DEFAULTS
        }, n.prototype.setContent = function() {
            var t = this.tip(),
                e = this.getTitle(),
                n = this.getContent();
            t.find(".popover-title")[this.options.html ? "html" : "text"](e), t.find(".popover-content").children().detach().end()[this.options.html ? "string" == typeof n ? "html" : "append" : "text"](n), t.removeClass("fade top bottom left right in"), t.find(".popover-title").html() || t.find(".popover-title").hide()
        }, n.prototype.hasContent = function() {
            return this.getTitle() || this.getContent()
        }, n.prototype.getContent = function() {
            var t = this.$element,
                e = this.options;
            return t.attr("data-content") || ("function" == typeof e.content ? e.content.call(t[0]) : e.content)
        }, n.prototype.arrow = function() {
            return this.$arrow = this.$arrow || this.tip().find(".arrow")
        };
        var i = t.fn.popover;
        t.fn.popover = e, t.fn.popover.Constructor = n, t.fn.popover.noConflict = function() {
            return t.fn.popover = i, this
        }
    }(jQuery), + function(t) {
        "use strict";

        function e(n, i) {
            this.$body = t(document.body), this.$scrollElement = t(t(n).is(document.body) ? window : n), this.options = t.extend({}, e.DEFAULTS, i), this.selector = (this.options.target || "") + " .nav li > a", this.offsets = [], this.targets = [], this.activeTarget = null, this.scrollHeight = 0, this.$scrollElement.on("scroll.bs.scrollspy", t.proxy(this.process, this)), this.refresh(), this.process()
        }

        function n(n) {
            return this.each(function() {
                var i = t(this),
                    r = i.data("bs.scrollspy"),
                    s = "object" == typeof n && n;
                r || i.data("bs.scrollspy", r = new e(this, s)), "string" == typeof n && r[n]()
            })
        }
        e.VERSION = "3.3.7", e.DEFAULTS = {
            offset: 10
        }, e.prototype.getScrollHeight = function() {
            return this.$scrollElement[0].scrollHeight || Math.max(this.$body[0].scrollHeight, document.documentElement.scrollHeight)
        }, e.prototype.refresh = function() {
            var e = this,
                n = "offset",
                i = 0;
            this.offsets = [], this.targets = [], this.scrollHeight = this.getScrollHeight(), t.isWindow(this.$scrollElement[0]) || (n = "position", i = this.$scrollElement.scrollTop()), this.$body.find(this.selector).map(function() {
                var e = t(this),
                    r = e.data("target") || e.attr("href"),
                    s = /^#./.test(r) && t(r);
                return s && s.length && s.is(":visible") && [
                    [s[n]().top + i, r]
                ] || null
            }).sort(function(t, e) {
                return t[0] - e[0]
            }).each(function() {
                e.offsets.push(this[0]), e.targets.push(this[1])
            })
        }, e.prototype.process = function() {
            var t, e = this.$scrollElement.scrollTop() + this.options.offset,
                n = this.getScrollHeight(),
                i = this.options.offset + n - this.$scrollElement.height(),
                r = this.offsets,
                s = this.targets,
                o = this.activeTarget;
            if (this.scrollHeight != n && this.refresh(), e >= i) return o != (t = s[s.length - 1]) && this.activate(t);
            if (o && e < r[0]) return this.activeTarget = null, this.clear();
            for (t = r.length; t--;) o != s[t] && e >= r[t] && (void 0 === r[t + 1] || e < r[t + 1]) && this.activate(s[t])
        }, e.prototype.activate = function(e) {
            this.activeTarget = e, this.clear();
            var n = this.selector + '[data-target="' + e + '"],' + this.selector + '[href="' + e + '"]',
                i = t(n).parents("li").addClass("active");
            i.parent(".dropdown-menu").length && (i = i.closest("li.dropdown").addClass("active")), i.trigger("activate.bs.scrollspy")
        }, e.prototype.clear = function() {
            t(this.selector).parentsUntil(this.options.target, ".active").removeClass("active")
        };
        var i = t.fn.scrollspy;
        t.fn.scrollspy = n, t.fn.scrollspy.Constructor = e, t.fn.scrollspy.noConflict = function() {
            return t.fn.scrollspy = i, this
        }, t(window).on("load.bs.scrollspy.data-api", function() {
            t('[data-spy="scroll"]').each(function() {
                var e = t(this);
                n.call(e, e.data())
            })
        })
    }(jQuery), + function(t) {
        "use strict";

        function e(e) {
            return this.each(function() {
                var i = t(this),
                    r = i.data("bs.tab");
                r || i.data("bs.tab", r = new n(this)), "string" == typeof e && r[e]()
            })
        }
        var n = function(e) {
            this.element = t(e)
        };
        n.VERSION = "3.3.7", n.TRANSITION_DURATION = 150, n.prototype.show = function() {
            var e = this.element,
                n = e.closest("ul:not(.dropdown-menu)"),
                i = e.data("target");
            if (i || (i = e.attr("href"), i = i && i.replace(/.*(?=#[^\s]*$)/, "")), !e.parent("li").hasClass("active")) {
                var r = n.find(".active:last a"),
                    s = t.Event("hide.bs.tab", {
                        relatedTarget: e[0]
                    }),
                    o = t.Event("show.bs.tab", {
                        relatedTarget: r[0]
                    });
                if (r.trigger(s), e.trigger(o), !o.isDefaultPrevented() && !s.isDefaultPrevented()) {
                    var a = t(i);
                    this.activate(e.closest("li"), n), this.activate(a, a.parent(), function() {
                        r.trigger({
                            type: "hidden.bs.tab",
                            relatedTarget: e[0]
                        }), e.trigger({
                            type: "shown.bs.tab",
                            relatedTarget: r[0]
                        })
                    })
                }
            }
        }, n.prototype.activate = function(e, i, r) {
            function s() {
                o.removeClass("active").find("> .dropdown-menu > .active").removeClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded", !1), e.addClass("active").find('[data-toggle="tab"]').attr("aria-expanded", !0), a ? (e[0].offsetWidth, e.addClass("in")) : e.removeClass("fade"), e.parent(".dropdown-menu").length && e.closest("li.dropdown").addClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded", !0), r && r()
            }
            var o = i.find("> .active"),
                a = r && t.support.transition && (o.length && o.hasClass("fade") || !!i.find("> .fade").length);
            o.length && a ? o.one("bsTransitionEnd", s).emulateTransitionEnd(n.TRANSITION_DURATION) : s(), o.removeClass("in")
        };
        var i = t.fn.tab;
        t.fn.tab = e, t.fn.tab.Constructor = n, t.fn.tab.noConflict = function() {
            return t.fn.tab = i, this
        };
        var r = function(n) {
            n.preventDefault(), e.call(t(this), "show")
        };
        t(document).on("click.bs.tab.data-api", '[data-toggle="tab"]', r).on("click.bs.tab.data-api", '[data-toggle="pill"]', r)
    }(jQuery), + function(t) {
        "use strict";

        function e(e) {
            return this.each(function() {
                var i = t(this),
                    r = i.data("bs.affix"),
                    s = "object" == typeof e && e;
                r || i.data("bs.affix", r = new n(this, s)), "string" == typeof e && r[e]()
            })
        }
        var n = function(e, i) {
            this.options = t.extend({}, n.DEFAULTS, i), this.$target = t(this.options.target).on("scroll.bs.affix.data-api", t.proxy(this.checkPosition, this)).on("click.bs.affix.data-api", t.proxy(this.checkPositionWithEventLoop, this)), this.$element = t(e), this.affixed = null, this.unpin = null, this.pinnedOffset = null, this.checkPosition()
        };
        n.VERSION = "3.3.7", n.RESET = "affix affix-top affix-bottom", n.DEFAULTS = {
            offset: 0,
            target: window
        }, n.prototype.getState = function(t, e, n, i) {
            var r = this.$target.scrollTop(),
                s = this.$element.offset(),
                o = this.$target.height();
            if (null != n && "top" == this.affixed) return r < n && "top";
            if ("bottom" == this.affixed) return null != n ? !(r + this.unpin <= s.top) && "bottom" : !(r + o <= t - i) && "bottom";
            var a = null == this.affixed,
                l = a ? r : s.top,
                h = a ? o : e;
            return null != n && r <= n ? "top" : null != i && l + h >= t - i && "bottom"
        }, n.prototype.getPinnedOffset = function() {
            if (this.pinnedOffset) return this.pinnedOffset;
            this.$element.removeClass(n.RESET).addClass("affix");
            var t = this.$target.scrollTop(),
                e = this.$element.offset();
            return this.pinnedOffset = e.top - t
        }, n.prototype.checkPositionWithEventLoop = function() {
            setTimeout(t.proxy(this.checkPosition, this), 1)
        }, n.prototype.checkPosition = function() {
            if (this.$element.is(":visible")) {
                var e = this.$element.height(),
                    i = this.options.offset,
                    r = i.top,
                    s = i.bottom,
                    o = Math.max(t(document).height(), t(document.body).height());
                "object" != typeof i && (s = r = i), "function" == typeof r && (r = i.top(this.$element)), "function" == typeof s && (s = i.bottom(this.$element));
                var a = this.getState(o, e, r, s);
                if (this.affixed != a) {
                    null != this.unpin && this.$element.css("top", "");
                    var l = "affix" + (a ? "-" + a : ""),
                        h = t.Event(l + ".bs.affix");
                    if (this.$element.trigger(h), h.isDefaultPrevented()) return;
                    this.affixed = a, this.unpin = "bottom" == a ? this.getPinnedOffset() : null, this.$element.removeClass(n.RESET).addClass(l).trigger(l.replace("affix", "affixed") + ".bs.affix")
                }
                "bottom" == a && this.$element.offset({
                    top: o - e - s
                })
            }
        };
        var i = t.fn.affix;
        t.fn.affix = e, t.fn.affix.Constructor = n, t.fn.affix.noConflict = function() {
            return t.fn.affix = i, this
        }, t(window).on("load", function() {
            t('[data-spy="affix"]').each(function() {
                var n = t(this),
                    i = n.data();
                i.offset = i.offset || {}, null != i.offsetBottom && (i.offset.bottom = i.offsetBottom), null != i.offsetTop && (i.offset.top = i.offsetTop), e.call(n, i)
            })
        })
    }(jQuery), "undefined" == typeof jQuery) throw new Error("AdminLTE requires jQuery"); + function(t) {
    "use strict";

    function e(e) {
        return this.each(function() {
            var r = t(this),
                o = r.data(n);
            if (!o) {
                var a = t.extend({}, i, r.data(), "object" == typeof e && e);
                r.data(n, o = new s(r, a))
            }
            if ("string" == typeof o) {
                if ("undefined" == typeof o[e]) throw new Error("No method named " + e);
                o[e]()
            }
        })
    }
    var n = "lte.boxrefresh",
        i = {
            source: "",
            params: {},
            trigger: ".refresh-btn",
            content: ".box-body",
            loadInContent: !0,
            responseType: "",
            overlayTemplate: '<div class="overlay"><div class="fa fa-refresh fa-spin"></div></div>',
            onLoadStart: function() {},
            onLoadDone: function(t) {
                return t
            }
        },
        r = {
            data: '[data-widget="box-refresh"]'
        },
        s = function(e, n) {
            if (this.element = e, this.options = n, this.$overlay = t(n.overlay), "" === n.source) throw new Error("Source url was not defined. Please specify a url in your BoxRefresh source option.");
            this._setUpListeners(), this.load()
        };
    s.prototype.load = function() {
        this._addOverlay(), this.options.onLoadStart.call(t(this)), t.get(this.options.source, this.options.params, function(e) {
            this.options.loadInContent && t(this.options.content).html(e), this.options.onLoadDone.call(t(this), e), this._removeOverlay()
        }.bind(this), "" !== this.options.responseType && this.options.responseType)
    }, s.prototype._setUpListeners = function() {
        t(this.element).on("click", r.trigger, function(t) {
            t && t.preventDefault(), this.load()
        }.bind(this))
    }, s.prototype._addOverlay = function() {
        t(this.element).append(this.$overlay)
    }, s.prototype._removeOverlay = function() {
        t(this.element).remove(this.$overlay)
    };
    var o = t.fn.boxRefresh;
    t.fn.boxRefresh = e, t.fn.boxRefresh.Constructor = s, t.fn.boxRefresh.noConflict = function() {
        return t.fn.boxRefresh = o, this
    }, t(window).on("load", function() {
        t(r.data).each(function() {
            e.call(t(this))
        })
    })
}(jQuery), + function(t) {
    "use strict";

    function e(e) {
        return this.each(function() {
            var r = t(this),
                s = r.data(n);
            if (!s) {
                var o = t.extend({}, i, r.data(), "object" == typeof e && e);
                r.data(n, s = new a(r, o))
            }
            if ("string" == typeof e) {
                if ("undefined" == typeof s[e]) throw new Error("No method named " + e);
                s[e]()
            }
        })
    }
    var n = "lte.boxwidget",
        i = {
            animationSpeed: 500,
            collapseTrigger: '[data-widget="collapse"]',
            removeTrigger: '[data-widget="remove"]',
            collapseIcon: "fa-minus",
            expandIcon: "fa-plus",
            removeIcon: "fa-times"
        },
        r = {
            data: ".box",
            collapsed: ".collapsed-box",
            header: ".box-header",
            body: ".box-body",
            footer: ".box-footer",
            tools: ".box-tools"
        },
        s = {
            collapsed: "collapsed-box"
        },
        o = {
            collapsed: "collapsed.boxwidget",
            expanded: "expanded.boxwidget",
            removed: "removed.boxwidget"
        },
        a = function(t, e) {
            this.element = t, this.options = e, this._setUpListeners()
        };
    a.prototype.toggle = function() {
        var e = !t(this.element).is(r.collapsed);
        e ? this.collapse() : this.expand()
    }, a.prototype.expand = function() {
        var e = t.Event(o.expanded),
            n = this.options.collapseIcon,
            i = this.options.expandIcon;
        t(this.element).removeClass(s.collapsed), t(this.element).children(r.header + ", " + r.body + ", " + r.footer).children(r.tools).find("." + i).removeClass(i).addClass(n), t(this.element).children(r.body + ", " + r.footer).slideDown(this.options.animationSpeed, function() {
            t(this.element).trigger(e)
        }.bind(this))
    }, a.prototype.collapse = function() {
        var e = t.Event(o.collapsed),
            n = this.options.collapseIcon,
            i = this.options.expandIcon;
        t(this.element).children(r.header + ", " + r.body + ", " + r.footer).children(r.tools).find("." + n).removeClass(n).addClass(i), t(this.element).children(r.body + ", " + r.footer).slideUp(this.options.animationSpeed, function() {
            t(this.element).addClass(s.collapsed), t(this.element).trigger(e)
        }.bind(this))
    }, a.prototype.remove = function() {
        var e = t.Event(o.removed);
        t(this.element).slideUp(this.options.animationSpeed, function() {
            t(this.element).trigger(e), t(this.element).remove()
        }.bind(this))
    }, a.prototype._setUpListeners = function() {
        var e = this;
        t(this.element).on("click", this.options.collapseTrigger, function(n) {
            return n && n.preventDefault(), e.toggle(t(this)), !1
        }), t(this.element).on("click", this.options.removeTrigger, function(n) {
            return n && n.preventDefault(), e.remove(t(this)), !1
        })
    };
    var l = t.fn.boxWidget;
    t.fn.boxWidget = e, t.fn.boxWidget.Constructor = a, t.fn.boxWidget.noConflict = function() {
        return t.fn.boxWidget = l, this
    }, t(window).on("load", function() {
        t(r.data).each(function() {
            e.call(t(this))
        })
    })
}(jQuery), + function(t) {
    "use strict";

    function e(e) {
        return this.each(function() {
            var r = t(this),
                s = r.data(n);
            if (!s) {
                var o = t.extend({}, i, r.data(), "object" == typeof e && e);
                r.data(n, s = new a(r, o))
            }
            "string" == typeof e && s.toggle()
        })
    }
    var n = "lte.controlsidebar",
        i = {
            slide: !0
        },
        r = {
            sidebar: ".control-sidebar",
            data: '[data-toggle="control-sidebar"]',
            open: ".control-sidebar-open",
            bg: ".control-sidebar-bg",
            wrapper: ".wrapper",
            content: ".content-wrapper",
            boxed: ".layout-boxed"
        },
        s = {
            open: "control-sidebar-open",
            fixed: "fixed"
        },
        o = {
            collapsed: "collapsed.controlsidebar",
            expanded: "expanded.controlsidebar"
        },
        a = function(t, e) {
            this.element = t, this.options = e, this.hasBindedResize = !1, this.init()
        };
    a.prototype.init = function() {
        t(this.element).is(r.data) || t(this).on("click", this.toggle), this.fix(), t(window).resize(function() {
            this.fix()
        }.bind(this))
    }, a.prototype.toggle = function(e) {
        e && e.preventDefault(), this.fix(), t(r.sidebar).is(r.open) || t("body").is(r.open) ? this.collapse() : this.expand()
    }, a.prototype.expand = function() {
        this.options.slide ? t(r.sidebar).addClass(s.open) : t("body").addClass(s.open), t(this.element).trigger(t.Event(o.expanded))
    }, a.prototype.collapse = function() {
        t("body, " + r.sidebar).removeClass(s.open), t(this.element).trigger(t.Event(o.collapsed))
    }, a.prototype.fix = function() {
        t("body").is(r.boxed) && this._fixForBoxed(t(r.bg))
    }, a.prototype._fixForBoxed = function(e) {
        e.css({
            position: "absolute",
            height: t(r.wrapper).height()
        })
    };
    var l = t.fn.controlSidebar;
    t.fn.controlSidebar = e, t.fn.controlSidebar.Constructor = a, t.fn.controlSidebar.noConflict = function() {
        return t.fn.controlSidebar = l, this
    }, t(document).on("click", r.data, function(n) {
        n && n.preventDefault(), e.call(t(this), "toggle")
    })
}(jQuery), + function(t) {
    "use strict";

    function e(e) {
        return this.each(function() {
            var i = t(this),
                r = i.data(n);
            r || i.data(n, r = new s(i)), "string" == typeof e && r.toggle(i)
        })
    }
    var n = "lte.directchat",
        i = {
            data: '[data-widget="chat-pane-toggle"]',
            box: ".direct-chat"
        },
        r = {
            open: "direct-chat-contacts-open"
        },
        s = function(t) {
            this.element = t
        };
    s.prototype.toggle = function(t) {
        t.parents(i.box).first().toggleClass(r.open)
    };
    var o = t.fn.directChat;
    t.fn.directChat = e, t.fn.directChat.Constructor = s, t.fn.directChat.noConflict = function() {
        return t.fn.directChat = o, this
    }, t(document).on("click", i.data, function(n) {
        n && n.preventDefault(), e.call(t(this), "toggle")
    })
}(jQuery), + function(t) {
    "use strict";

    function e(e) {
        return this.each(function() {
            var r = t(this),
                s = r.data(n);
            if (!s) {
                var a = t.extend({}, i, r.data(), "object" == typeof e && e);
                r.data(n, s = new o(a))
            }
            if ("string" == typeof e) {
                if ("undefined" == typeof s[e]) throw new Error("No method named " + e);
                s[e]()
            }
        })
    }
    var n = "lte.layout",
        i = {
            slimscroll: !0,
            resetHeight: !0
        },
        r = {
            wrapper: ".wrapper",
            contentWrapper: ".content-wrapper",
            layoutBoxed: ".layout-boxed",
            mainFooter: ".main-footer",
            mainHeader: ".main-header",
            sidebar: ".sidebar",
            controlSidebar: ".control-sidebar",
            fixed: ".fixed",
            sidebarMenu: ".sidebar-menu",
            logo: ".main-header .logo"
        },
        s = {
            fixed: "fixed",
            holdTransition: "hold-transition"
        },
        o = function(t) {
            this.options = t, this.bindedResize = !1, this.activate()
        };
    o.prototype.activate = function() {
        this.fix(), this.fixSidebar(), t("body").removeClass(s.holdTransition), this.options.resetHeight && t("body, html, " + r.wrapper).css({
            height: "auto",
            "min-height": "100%"
        }), this.bindedResize || (t(window).resize(function() {
            this.fix(), this.fixSidebar(), t(r.logo + ", " + r.sidebar).one("webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend", function() {
                this.fix(), this.fixSidebar()
            }.bind(this))
        }.bind(this)), this.bindedResize = !0), t(r.sidebarMenu).on("expanded.tree", function() {
            this.fix(), this.fixSidebar()
        }.bind(this)), t(r.sidebarMenu).on("collapsed.tree", function() {
            this.fix(), this.fixSidebar()
        }.bind(this))
    }, o.prototype.fix = function() {
        t(r.layoutBoxed + " > " + r.wrapper).css("overflow", "hidden");
        var e = t(r.mainFooter).outerHeight() || 0,
            n = t(r.mainHeader).outerHeight() + e,
            i = t(window).height(),
            o = t(r.sidebar).height() || 0;
        if (t("body").hasClass(s.fixed)) t(r.contentWrapper).css("min-height", i - e);
        else {
            var a;
            i >= o ? (t(r.contentWrapper).css("min-height", i - n), a = i - n) : (t(r.contentWrapper).css("min-height", o), a = o);
            var l = t(r.controlSidebar);
            "undefined" != typeof l && l.height() > a && t(r.contentWrapper).css("min-height", l.height())
        }
    }, o.prototype.fixSidebar = function() {
        return t("body").hasClass(s.fixed) ? void(this.options.slimscroll && "undefined" != typeof t.fn.slimScroll && t(r.sidebar).slimScroll({
            height: t(window).height() - t(r.mainHeader).height() + "px"
        })) : void("undefined" != typeof t.fn.slimScroll && t(r.sidebar).slimScroll({
            destroy: !0
        }).height("auto"))
    };
    var a = t.fn.layout;
    t.fn.layout = e, t.fn.layout.Constuctor = o, t.fn.layout.noConflict = function() {
        return t.fn.layout = a, this
    }, t(window).on("load", function() {
        e.call(t("body"))
    })
}(jQuery), + function(t) {
    "use strict";

    function e(e) {
        return this.each(function() {
            var r = t(this),
                s = r.data(n);
            if (!s) {
                var o = t.extend({}, i, r.data(), "object" == typeof e && e);
                r.data(n, s = new a(o))
            }
            "toggle" === e && s.toggle()
        })
    }
    var n = "lte.pushmenu",
        i = {
            collapseScreenSize: 767,
            expandOnHover: !1,
            expandTransitionDelay: 200
        },
        r = {
            collapsed: ".sidebar-collapse",
            open: ".sidebar-open",
            mainSidebar: ".main-sidebar",
            contentWrapper: ".content-wrapper",
            searchInput: ".sidebar-form .form-control",
            button: '[data-toggle="push-menu"]',
            mini: ".sidebar-mini",
            expanded: ".sidebar-expanded-on-hover",
            layoutFixed: ".fixed"
        },
        s = {
            collapsed: "sidebar-collapse",
            open: "sidebar-open",
            mini: "sidebar-mini",
            expanded: "sidebar-expanded-on-hover",
            expandFeature: "sidebar-mini-expand-feature",
            layoutFixed: "fixed"
        },
        o = {
            expanded: "expanded.pushMenu",
            collapsed: "collapsed.pushMenu"
        },
        a = function(t) {
            this.options = t, this.init()
        };
    a.prototype.init = function() {
        this.options.expandOnHover && (this.expandOnHover(), t("body").addClass(s.expandFeature)), t(r.contentWrapper).click(function() {
            t(window).width() <= this.options.collapseScreenSize && t("body").hasClass(s.open) && this.close()
        }.bind(this)), t(r.searchInput).click(function(t) {
            t.stopPropagation()
        })
    }, a.prototype.toggle = function() {
        var e = t(window).width(),
            n = !t("body").hasClass(s.collapsed);
        e <= this.options.collapseScreenSize && (n = t("body").hasClass(s.open)), n ? this.close() : this.open()
    }, a.prototype.open = function() {
        var e = t(window).width();
        e > this.options.collapseScreenSize ? t("body").removeClass(s.collapsed).trigger(t.Event(o.expanded)) : t("body").addClass(s.open).trigger(t.Event(o.expanded))
    }, a.prototype.close = function() {
        var e = t(window).width();
        e > this.options.collapseScreenSize ? t("body").addClass(s.collapsed).trigger(t.Event(o.collapsed)) : t("body").removeClass(s.open + " " + s.collapsed).trigger(t.Event(o.collapsed))
    }, a.prototype.expandOnHover = function() {
        t(r.mainSidebar).hover(function() {
            t("body").is(r.mini + r.collapsed) && t(window).width() > this.options.collapseScreenSize && this.expand()
        }.bind(this), function() {
            t("body").is(r.expanded) && this.collapse()
        }.bind(this))
    }, a.prototype.expand = function() {
        setTimeout(function() {
            t("body").removeClass(s.collapsed).addClass(s.expanded)
        }, this.options.expandTransitionDelay)
    }, a.prototype.collapse = function() {
        setTimeout(function() {
            t("body").removeClass(s.expanded).addClass(s.collapsed)
        }, this.options.expandTransitionDelay)
    };
    var l = t.fn.pushMenu;
    t.fn.pushMenu = e, t.fn.pushMenu.Constructor = a, t.fn.pushMenu.noConflict = function() {
        return t.fn.pushMenu = l, this
    }, t(document).on("click", r.button, function(n) {
        n.preventDefault(), e.call(t(this), "toggle")
    }), t(window).on("load", function() {
        e.call(t(r.button))
    })
}(jQuery), + function(t) {
    "use strict";

    function e(e) {
        return this.each(function() {
            var r = t(this),
                s = r.data(n);
            if (!s) {
                var a = t.extend({}, i, r.data(), "object" == typeof e && e);
                r.data(n, s = new o(r, a))
            }
            if ("string" == typeof s) {
                if ("undefined" == typeof s[e]) throw new Error("No method named " + e);
                s[e]()
            }
        })
    }
    var n = "lte.todolist",
        i = {
            onCheck: function(t) {
                return t
            },
            onUnCheck: function(t) {
                return t
            }
        },
        r = {
            data: '[data-widget="todo-list"]'
        },
        s = {
            done: "done"
        },
        o = function(t, e) {
            this.element = t, this.options = e, this._setUpListeners()
        };
    o.prototype.toggle = function(t) {
        return t.parents(r.li).first().toggleClass(s.done), t.prop("checked") ? void this.check(t) : void this.unCheck(t)
    }, o.prototype.check = function(t) {
        this.options.onCheck.call(t)
    }, o.prototype.unCheck = function(t) {
        this.options.onUnCheck.call(t)
    }, o.prototype._setUpListeners = function() {
        var e = this;
        t(this.element).on("change ifChanged", "input:checkbox", function() {
            e.toggle(t(this))
        })
    };
    var a = t.fn.todoList;
    t.fn.todoList = e, t.fn.todoList.Constructor = o, t.fn.todoList.noConflict = function() {
        return t.fn.todoList = a, this
    }, t(window).on("load", function() {
        t(r.data).each(function() {
            e.call(t(this))
        })
    })
}(jQuery), + function(t) {
    "use strict";

    function e(e) {
        return this.each(function() {
            var r = t(this),
                s = r.data(n);
            if (!s) {
                var o = t.extend({}, i, r.data(), "object" == typeof e && e);
                r.data(n, new a(r, o))
            }
        })
    }
    var n = "lte.tree",
        i = {
            animationSpeed: 200,
            accordion: !0,
            followLink: !1,
            trigger: ".treeview a"
        },
        r = {
            tree: ".tree",
            treeview: ".treeview",
            treeviewMenu: ".treeview-menu",
            open: ".menu-open, .active",
            li: "li",
            data: '[data-widget="tree"]',
            active: ".active"
        },
        s = {
            open: "menu-open",
            tree: "tree"
        },
        o = {
            collapsed: "collapsed.tree",
            expanded: "expanded.tree"
        },
        a = function(e, n) {
            this.element = e, this.options = n, t(this.element).addClass(s.tree), t(r.treeview + r.active, this.element).addClass(s.open), this._setUpListeners()
        };
    a.prototype.toggle = function(t, e) {
        var n = t.next(r.treeviewMenu),
            i = t.parent(),
            o = i.hasClass(s.open);
        i.is(r.treeview) && (this.options.followLink && "#" !== t.attr("href") || e.preventDefault(), o ? this.collapse(n, i) : this.expand(n, i))
    }, a.prototype.expand = function(e, n) {
        var i = t.Event(o.expanded);
        if (this.options.accordion) {
            var a = n.siblings(r.open),
                l = a.children(r.treeviewMenu);
            this.collapse(l, a)
        }
        n.addClass(s.open), e.slideDown(this.options.animationSpeed, function() {
            t(this.element).trigger(i)
        }.bind(this))
    }, a.prototype.collapse = function(e, n) {
        var i = t.Event(o.collapsed);
        e.find(r.open).removeClass(s.open), n.removeClass(s.open), e.slideUp(this.options.animationSpeed, function() {
            e.find(r.open + " > " + r.treeview).slideUp(), t(this.element).trigger(i)
        }.bind(this))
    }, a.prototype._setUpListeners = function() {
        var e = this;
        t(this.element).on("click", this.options.trigger, function(n) {
            e.toggle(t(this), n)
        })
    };
    var l = t.fn.tree;
    t.fn.tree = e, t.fn.tree.Constructor = a, t.fn.tree.noConflict = function() {
        return t.fn.tree = l, this
    }, t(window).on("load", function() {
        t(r.data).each(function() {
            e.call(t(this))
        })
    })
}(jQuery),
function(t, e) {
    if ("function" == typeof define && define.amd) define(["moment", "jquery", "exports"], function(n, i, r) {
        t.gapicker = e(t, r, n, i)
    });
    else if ("undefined" != typeof exports) {
        var n = require("moment"),
            i = require("jquery");
        e(t, exports, n, i)
    } else t.gapicker = e(t, {}, t.moment || n, t.jQuery || t.$)
}(this, function(t, e, n, i) {
    var r = function(t, e) {
        this.defaults.utcOffset = n().utcOffset(), this.options = e = i.extend({}, this.defaults, e || {}), this.startDate = null, this.endDate = null, this.selectedUtcOffset = null, this.minDate = this._parseDate(e.minDate), this.maxDate = this._parseDate(e.maxDate), this.isShowing = !1, this.activeDate = "start", this.callback = function() {}, "function" == typeof e.callback && (this.callback = e.callback), this.$el = i(t), this.$el.is("input") ? this.$input = this.$el : this.$input = this.$el.find("input"), this.$parentEl = i(this.options.attachTo), this.$container = i(this.template).appendTo(this.$parentEl), this.$startDateInput = this.$container.find(".range.start [name=_date]"), this.$startHourSelect = this.$container.find(".range.start [name=_hour]"), this.$startMinuteSelect = this.$container.find(".range.start [name=_minute]"), this.$endDateInput = this.$container.find(".range.end [name=_date]"), this.$endHourSelect = this.$container.find(".range.end [name=_hour]"), this.$endMinuteSelect = this.$container.find(".range.end [name=_minute]"), this.$offsetSelect = this.$container.find(".range.offset [name=offset]"), this.$container.find(".label-from").html(e.fromLabel), this.$container.find(".label-to").html(e.toLabel), this.$container.find(".label-offset").html(e.offsetLabel), this.$container.find(".apply-btn").html(e.applyLabel), this.$container.find(".cancel-btn").html(e.cancelLabel), this.calendars = [];
        for (var r, s = this.$container.find(".calendars"), o = 0; o < e.calendarsCount; o++) r = i('<div class="calendar"></div>').appendTo(s), this.calendars[o] = {
            $el: r
        };
        for (var a, l, h, u, c = this.$container.find(".range.custom"), d = i("<table>").appendTo(c), f = 2, p = Math.round(e.customRanges.length / f), g = 0; g < p; g++) {
            a = i("<tr>").appendTo(d);
            for (var m = 0; m < f; m++) l = i("<td>").appendTo(a), u = e.customRanges[g * f + m], u && (h = i('<a data-range="' + u[0] + '">' + u[1] + "</a>"), h.appendTo(l))
        }
        if (e.showTime) {
            var v, y = this._rangeArray(0, 24, e.hourStep),
                w = this._rangeArray(0, 60, e.minuteStep),
                _ = "",
                b = "";
            for (var x in y) v = y[x] < 10 ? "0" + y[x] : y[x], _ += '<option value="' + y[x] + '">' + v + "</option>";
            y.indexOf(23) < 0 && (_ += '<option value="23">23</option>'), this.$startHourSelect.html(_), this.$endHourSelect.html(_);
            for (var C in w) v = w[C] < 10 ? "0" + w[C] : w[C], b += '<option value="' + w[C] + '">' + v + "</option>";
            w.indexOf(59) < 0 && (b += '<option value="59">59</option>'), this.$startMinuteSelect.html(b), this.$endMinuteSelect.html(b), this.$container.addClass("with-time")
        }
        if (e.showOffsets) {
            var S, D, E, k, T;
            for (D = 0; D <= 24; D++) S = D - 12, E = S >= -12 && S < -9 ? "" + S : S >= -9 && S < 0 ? "-0" + S * -1 : S >= 0 && S < 10 ? "+0" + S : "+" + S, T = 60 * S === this.options.utcOffset ? "selected" : "", k += '<option value="' + 60 * S + '"' + T + ">UTC " + E + "</option>";
            this.$offsetSelect.html(k)
        }
        if (e.allowNulls && this.$container.addClass("with-nulls"), 0 !== e.weekStart)
            for (var O = e.weekStart; O > 0;) e.daysOfWeek.push(e.daysOfWeek.shift()), O--;
        this.$container.find(".calendar").on("click.gapicker", ".prev-month", i.proxy(this.onPrevBtnClick, this)).on("click.gapicker", ".next-month", i.proxy(this.onNextBtnClick, this)).on("click.gapicker", "td.available", i.proxy(this.onDateClick, this)), this.$container.find(".range").on("keyup.gapicker", "[name=_date]", i.proxy(this.onRangeInputChange, this)).on("change.gapicker", "[name=_hour]", i.proxy(this.onRangeInputChange, this)).on("change.gapicker", "[name=_minute]", i.proxy(this.onRangeInputChange, this)).on("click.gapicker", "[name]", i.proxy(this.onRangeInputFocus, this)).on("click.gapicker", ".clear-btn", i.proxy(this.onClearBtnClick, this)), this.$container.find(".range.end").on("keyup.gapicker", "[name=_date]", i.proxy(this.onRangeInputChange, this)).on("change.gapicker", "[name=_hour]", i.proxy(this.onRangeInputChange, this)).on("change.gapicker", "[name=_minute]", i.proxy(this.onRangeInputChange, this)).on("click.gapicker", "[name]", i.proxy(this.onRangeInputFocus, this)).on("click.gapicker", ".clear-btn", i.proxy(this.onClearBtnClick, this)), this.$container.find(".range.custom").on("click.gapicker", "a", i.proxy(this.onCustomRangeClick, this)), this.$container.find(".buttons").on("click.gapicker", ".apply-btn", i.proxy(this.onApplyBtnClick, this)).on("click.gapicker", ".cancel-btn", i.proxy(this.onCancelBtnClick, this)), this.$input && this.$input.on({
            "click.gapicker": i.proxy(this.show, this),
            "focus.gapicker": i.proxy(this.show, this),
            "keyup.gapicker": i.proxy(this.onElementChange, this)
        }), this.$el.is("input") || this.$el.on("click.gapicker", i.proxy(this.toggle, this)), i(document).on("mousedown.gapicker", i.proxy(this.onOutsideClick, this)).on("touchend.gapicker", i.proxy(this.onOutsideClick, this)).on("click.gapicker", "[data-toggle=dropdown]", i.proxy(this.onOutsideClick, this)).on("focusin.gapicker", i.proxy(this.onOutsideClick, this)), this.setStartDate(e.startDate), this.setEndDate(e.endDate), this._updateAttachedInput()
    };
    r.prototype = {
        defaults: {
            attachTo: "body",
            startDate: null,
            endDate: null,
            utcOffset: null,
            showOffsets: !0,
            minDate: null,
            maxDate: null,
            callback: null,
            showTime: !0,
            calendarsCount: 3,
            allowNulls: !0,
            position: "bottom right",
            format: "YYYY-MM-DD HH:mm",
            dateFormat: "YYYY-MM-DD",
            separator: " - ",
            applyLabel: "Apply",
            cancelLabel: "Cancel",
            fromLabel: "From: ",
            toLabel: "To: ",
            offsetLabel: "",
            hourStep: 1,
            minuteStep: 10,
            daysOfWeek: n.weekdaysMin(),
            monthNames: n.monthsShort(),
            weekStart: n.localeData().firstDayOfWeek(),
            customRanges: [
                ["1d", "Today"],
                ["1w", "This Week"],
                ["1m", "This Month"],
                ["3m", "Last 3 Months"]
            ]
        },
        template: ['<div class="gapicker">', '    <div class="calendars"></div>', '    <div class="controls">', '        <div class="range start">', '            <div class="label-from"></div>', '            <input type="text" name="_date" value="" />', '            <div class="timepicker">', '                <select name="_hour"></select>', '                <span class="label-time">&nbsp;:&nbsp;</span>', '                <select name="_minute"></select>', "            </div>", '            <div class="clear-btn">&times;</div>', "        </div>", '        <div class="range end">', '            <div class="label-to"></div>', '            <input type="text" name="_date" value="" />', '            <div class="timepicker">', '                <select name="_hour"></select>', '                <span class="label-time">&nbsp;:&nbsp;</span>', '                <select name="_minute"></select>', "            </div>", '            <div class="clear-btn">&times;</div>', "        </div>", '        <div class="range offset">', '            <div class="label-offset"></div>', '            <select name="offset">', "            </select>", "        </div>", '        <div class="range custom"></div>', '        <div class="buttons">', '            <div class="apply-btn"></div> ', '            <div class="cancel-btn"></div>', "        </div>", "    </div>", "</div>"].join(""),
        show: function(t) {
            if (t && t.stopImmediatePropagation(), !this.isShowing) return this.isShowing = !0, this._setStartDate(this.oldStartDate ? this.oldStartDate.clone() : null), this._setEndDate(this.oldEndDate ? this.oldEndDate.clone() : null), this.refresh(), this.$container.show(), this.move(), this.activeDate = "start", this._highlightRangeInputs(), this.$el.trigger("gapicker.show", this), this
        },
        hide: function(t) {
            if (t && t.stopImmediatePropagation(), this.isShowing) return this.isShowing = !1, this._updateAttachedInput(), this.$container.hide(), this.$el.trigger("gapicker.hide", this), this
        },
        toggle: function(t) {
            return t && t.stopImmediatePropagation(), this.isShowing ? this.hide() : this.show(), this
        },
        setStartDate: function(t) {
            var e = this._setStartDate(t);
            return e && (this.oldStartDate = this.startDate ? this.startDate.clone() : null, this.isShowing && this.refresh()), this
        },
        setEndDate: function(t) {
            var e = this._setEndDate(t);
            return e && (this.oldEndDate = this.endDate ? this.endDate.clone() : null, this.isShowing && this.refresh()), this
        },
        clearStartDate: function() {
            return this.setStartDate(null), this
        },
        clearEndDate: function() {
            return this.setEndDate(null), this
        },
        refresh: function() {
            return this._guessCalendarsRange(), this._renderCalendars(), this._updateRangeInputs(), this._highlightRangeInputs(), this.selectedUtcOffset && this.$offsetSelect.val(this.selectedUtcOffset), this
        },
        destroy: function() {
            return this.$container.remove(), i(document).off(".gapicker"), this.$el.off(".gapicker"), this.$input && this.$input.off(".gapicker"), this.$el.removeData(), this
        },
        _setStartDate: function(t) {
            if (!t) return this._clearStartDate();
            var e = this._parseDate(t),
                n = this.options,
                i = 0,
                r = 0;
            return this._isDateValid(e) ? (n.showTime && (i = Math.floor(e.hour() / n.hourStep) * n.hourStep, r = Math.floor(e.minute() / n.minuteStep) * n.minuteStep), e = e.startOf("day"), e.hour(i), e.minute(r), this.minDate && e.isBefore(this.minDate) && (e = this.minDate.clone()), this.maxDate && e.isAfter(this.maxDate) && (e = this.maxDate.clone()), this.startDate = e, this.$startDateInput.removeClass("has-errors"), !0) : (this.$startDateInput.addClass("has-errors"), !1)
        },
        _setEndDate: function(t) {
            if (!t) return this._clearEndDate();
            var e = this._parseDate(t),
                n = this.options,
                i = 23,
                r = 59;
            return this._isDateValid(e) ? (n.showTime && (i = Math.round(e.hour() / n.hourStep) * n.hourStep, r = Math.round(e.minute() / n.minuteStep) * n.minuteStep, 60 === r && (r = 59)), e = e.endOf("day"), e.hour(i), e.minute(r), this.startDate && e.isBefore(this.startDate) && (e = this.startDate.clone()), this.maxDate && e.isAfter(this.maxDate) && (e = this.maxDate.clone()), this.endDate = e, this.$endDateInput.removeClass("has-errors"), !0) : (this.$endDateInput.addClass("has-errors"), !1)
        },
        _clearStartDate: function() {
            return this._isDateValid(null) ? (this.$startDateInput.removeClass("has-errors"), this.startDate = null, !0) : (this.$startDateInput.addClass("has-errors"), !1)
        },
        _clearEndDate: function() {
            return this._isDateValid(null) ? (this.$endDateInput.removeClass("has-errors"), this.endDate = null, !0) : (this.$endDateInput.addClass("has-errors"), !1)
        },
        _parseDate: function(t) {
            if (!t) return null;
            var e;
            return "string" == typeof t ? (e = n.parseZone(t, this.options.format).utcOffset(), t = n(t, this.options.format).utcOffset(e, !0)) : t = n(t.clone ? t.clone() : new Date(t)), t.utcOffset(this.options.utcOffset)
        },
        _rangeArray: function(t, e, n) {
            for (var i = [], r = t; r < e;) i.push(r), r += n;
            return i
        },
        _guessCalendarsRange: function() {
            var t, e, i, r, s, o = this.options;
            if (t = this.calendars.every(function(t) {
                    return !!t.month
                }, this), !(t && (i = !this.startDate || this.calendars.some(function(t) {
                    return this.startDate.format("YYYY-MM") === t.month.format("YYYY-MM")
                }, this), e = !this.endDate || this.calendars.some(function(t) {
                    return this.endDate.format("YYYY-MM") === t.month.format("YYYY-MM")
                }, this), !this.startDate && !this.endDate || i && e))) {
                this.startDate || this.endDate ? this.endDate ? (s = this.endDate.clone(), r = s.clone().subtract(o.calendarsCount - 1, "month")) : r = this.startDate.clone() : (s = (this.maxDate || n().utcOffset(o.utcOffset)).clone(), r = s.clone().subtract(o.calendarsCount - 1, "month"));
                for (var a = 0; a < o.calendarsCount; a++) this.calendars[a].month = r.clone().add(a, "month")
            }
        },
        _updateAttachedInput: function() {
            if (this.$input) {
                var t, e, n = this.options;
                this.oldStartDate || this.oldEndDate ? this.oldStartDate ? this.oldEndDate ? (t = this.oldStartDate ? this.oldStartDate.format(n.format) : "", e = this.oldEndDate ? this.oldEndDate.format(n.format) : "", this.$input.val(t + n.separator + e), this.$input.trigger("change")) : (t = this.oldStartDate ? this.oldStartDate.format(n.format) : "", this.$input.val(n.fromLabel + t), this.$input.trigger("change")) : (e = this.oldEndDate ? this.oldEndDate.format(n.format) : "", this.$input.val(n.toLabel + e), this.$input.trigger("change")) : (this.$input.val(""), this.$input.trigger("change"))
            }
        },
        _renderCalendars: function() {
            for (var t in this.calendars) this._renderCalendar(this.calendars[t])
        },
        _renderCalendar: function(t) {
            var e = this.options,
                r = t.$el,
                s = t.month.month(),
                o = t.month.year(),
                a = t.month.clone().startOf("month"),
                l = n([o, s, t.month.daysInMonth()]),
                h = i("<table>"),
                u = i("<thead>").appendTo(h),
                c = i("<tbody>").appendTo(h),
                d = i("<tr>").appendTo(u);
            !this.minDate || this.minDate.isBefore(a) ? d.append('<th class="prev-month"></th>') : d.append("<th></th>"), d.append('<th colspan="5" class="month">' + e.monthNames[s] + " " + o + "</th>"), !this.maxDate || this.maxDate.isAfter(l) ? d.append('<th class="next-month"></th>') : d.append("<th></th>");
            var f = i("<tr>").appendTo(u);
            i.each(e.daysOfWeek, function(t, e) {
                f.append("<th>" + e + "</th>")
            });
            for (var p, g, m = a.clone().subtract(a.day() - e.weekStart + 1, "day"), v = n().utcOffset(e.utcOffset), y = 0; y < 6; y++)
                for (var w, _ = i("<tr>").appendTo(c), b = 0; b < 7; b++) {
                    p = m.add(1, "day").clone(), g = [], p.isSame(v, "day") && g.push("today"), p.isoWeekday() > 5 && g.push("weekend"), p.month() !== s && g.push("off"), p.isSame(this.startDate, "day") && g.push("active", "start-date"), p.isSame(this.endDate, "day") && g.push("active", "end-date");
                    var x = this.startDate || this.minDate,
                        C = this.endDate || this.maxDate;
                    x && !p.isSameOrAfter(x, "day") || C && !p.isSameOrBefore(C, "day") || g.push("in-range"), this.minDate && p.isBefore(this.minDate, "day") || this.maxDate && p.isAfter(this.maxDate, "day") ? g.push("disabled") : g.push("available"), w = i("<td>" + p.date() + "</td>").appendTo(_), w.addClass(g.join(" ")), w.data("date", p)
                }
            r.html(h)
        },
        _updateRangeInputs: function() {
            var t = this.options;
            this.startDate ? (this.$startDateInput.val(this.startDate.format(t.dateFormat)), this.$startHourSelect.val(this.startDate.hour()), this.$startMinuteSelect.val(this.startDate.minute())) : (this.$startDateInput.val(""), this.$startHourSelect.val(0), this.$startMinuteSelect.val(0)), this.endDate ? (this.$endDateInput.val(this.endDate.format(t.dateFormat)), this.$endHourSelect.val(this.endDate.hour()), this.$endMinuteSelect.val(this.endDate.minute())) : (this.$endDateInput.val(""), this.$endHourSelect.val(0), this.$endMinuteSelect.val(0))
        },
        _highlightRangeInputs: function() {
            "start" === this.activeDate ? (this.$endDateInput.removeClass("active"), this.$startDateInput.addClass("active")) : (this.$endDateInput.addClass("active"), this.$startDateInput.removeClass("active"))
        },
        _isDateValid: function(t) {
            return !t && this.options.allowNulls || t && t.isValid()
        },
        _parseCustomRange: function(t) {
            var e = new RegExp("^([0-9]+)([dwm]{1})$"),
                n = {
                    d: "day",
                    w: "week",
                    m: "month"
                },
                i = t.match(e);
            return {
                count: parseInt(i[1]) - 1,
                period: n[i[2]]
            }
        },
        onPrevBtnClick: function() {
            for (var t in this.calendars) this.calendars[t].month.subtract(1, "month");
            this._renderCalendars()
        },
        onNextBtnClick: function() {
            for (var t in this.calendars) this.calendars[t].month.add(1, "month");
            this._renderCalendars()
        },
        onDateClick: function(t) {
            var e = i(t.target);
            if (e.hasClass("available")) {
                var n = e.data("date");
                "start" === this.activeDate ? (this._setStartDate(n.clone().startOf("day")), this.endDate && n.isAfter(this.endDate) && this._setEndDate(n.clone().endOf("day")), this.activeDate = "end") : (this.startDate && n.isBefore(this.startDate) && this._setStartDate(n.clone().startOf("day")), this._setEndDate(n.clone().endOf("day")), this.activeDate = "start"), this._updateRangeInputs(), this._highlightRangeInputs(), this._renderCalendars()
            }
        },
        onRangeInputChange: function(t) {
            var e = this.options,
                r = this.$startDateInput.val(),
                s = this.$endDateInput.val(),
                o = i(t.target).closest(".range.end").length > 0;
            r = n(r, e.dateFormat).utcOffset(e.utcOffset, !0), r.hour(this.$startHourSelect.val()), r.minute(this.$startMinuteSelect.val()), s = n(s, e.dateFormat).utcOffset(e.utcOffset, !0), s.hour(this.$endHourSelect.val()), s.minute(this.$endMinuteSelect.val()), this._isDateValid(r) && this._isDateValid(s) && o && s && r && s.isBefore(r) && (r = s.clone()), this._setStartDate(r), this._setEndDate(s), this._guessCalendarsRange(), this._renderCalendars()
        },
        onRangeInputFocus: function(t) {
            var e = i(t.target).closest(".range.end").length > 0;
            e ? this.activeDate = "end" : this.activeDate = "start", this._highlightRangeInputs()
        },
        onClearBtnClick: function(t) {
            t.preventDefault();
            var e = i(t.target).closest(".range.end").length > 0;
            e ? this._clearEndDate(null) : this._clearStartDate(null), this._updateRangeInputs(), this._renderCalendars()
        },
        onCustomRangeClick: function(t) {
            t.preventDefault();
            var e = this.options,
                r = this._parseCustomRange(i(t.target).data("range")),
                s = n().utcOffset(e.utcOffset).endOf("day"),
                o = s.clone().subtract(r.count, r.period).startOf(r.period);
            this._setStartDate(o), this._setEndDate(s), this.refresh()
        },
        onApplyBtnClick: function(t) {
            t.preventDefault(), this.oldStartDate = this.startDate ? this.startDate.clone() : null, this.oldEndDate = this.endDate ? this.endDate.clone() : null, this.selectedUtcOffset = this.$offsetSelect.val();
            var e = parseInt(this.selectedUtcOffset),
                n = this.startDate ? this.startDate.utcOffset(e, !0) : null,
                i = this.endDate ? this.endDate.utcOffset(e, !0) : null;
            this.callback(n, i, e), this.isShowing && this.refresh(), this.$el.trigger("gapicker.apply", this), this.hide()
        },
        onCancelBtnClick: function(t) {
            t.preventDefault(), this.$el.trigger("gapicker.cancel", this), this.hide()
        },
        onElementChange: function(t) {
            var e, i = this.options,
                r = this.$input.val();
            if (r.startsWith(i.fromLabel) ? (e = r.replace(i.fromLabel, ""), e = n(e, i.format).utcOffset(i.utcOffset, !0), this._setStartDate(e), this._setEndDate(null)) : r.startsWith(i.toLabel) ? (this._setStartDate(null), e = r.replace(i.toLabel, ""), e = n(e, i.format).utcOffset(i.utcOffset, !0), this._setEndDate(e)) : (r = r.split(i.separator), 2 === r.length ? (e = r[0].trim(), e = n(e, i.format).utcOffset(i.utcOffset, !0), this._setStartDate(e), e = r[1].trim(), e = n(e, i.format).utcOffset(i.utcOffset, !0), this._setEndDate(e)) : (this._setStartDate(null), this._setEndDate(null))), this.refresh(), 9 === t.keyCode || 13 === t.keyCode) {
                this.oldStartDate = this.startDate ? this.startDate.clone() : null, this.oldEndDate = this.endDate ? this.endDate.clone() : null, this.selectedUtcOffset = this.$offsetSelect.val();
                var s = parseInt(this.selectedUtcOffset),
                    o = this.startDate ? this.startDate.utcOffset(s, !0) : null,
                    a = this.endDate ? this.endDate.utcOffset(s, !0) : null;
                return this.callback(o, a, s), void this.hide()
            }
        },
        onOutsideClick: function(t) {
            var e = i(t.target);
            "focusin" === t.type || e.closest(this.$el).length || e.closest(this.$container).length || this.hide()
        },
        move: function() {
            var t = "bottom",
                e = "center";
            this.options.position.includes("top") && (t = "top"), this.options.position.includes("right") ? e = "right" : this.options.position.includes("left") && (e = "left");
            var n;
            n = this.$parentEl.is("body") ? {
                top: 0,
                left: 0,
                right: i(window).width()
            } : {
                top: this.$parentEl.offset().top - this.$parentEl.scrollTop(),
                left: this.$parentEl.offset().left - this.$parentEl.scrollLeft(),
                right: this.$parentEl[0].clientWidth + this.$parentEl.offset().left
            };
            var r, s, o;
            r = "top" === t ? this.$el.offset().top - this.$container.outerHeight() - n.top : this.$el.offset().top + this.$el.outerHeight() - n.top, "left" === e ? (o = n.right - this.$el.offset().left - this.$el.outerWidth(), s = "auto", o - n.right < this.$container.outerWidth() && (o = n.right - this.$container.outerWidth(), s = 0), o < 0 && (o = "auto", s = 0), this.$container.css({
                top: r,
                right: o,
                left: s
            })) : "center" === e ? (s = this.$el.offset().left - n.left + this.$el.outerWidth() / 2 - this.$container.outerWidth() / 2, s + this.$container.outerWidth() > n.right && (s = n.right - this.$container.outerWidth()), this.$container.css({
                top: r,
                left: s,
                right: "auto"
            }), this.$container.offset().left < 0 && this.$container.css({
                right: "auto",
                left: 9
            })) : (s = this.$el.offset().left - n.left, s + this.$container.outerWidth() > n.right && (s = n.right - this.$container.outerWidth()), this.$container.css({
                top: r,
                left: s,
                right: "auto"
            }), this.$container.offset().left + this.$container.outerWidth() > i(window).width() && this.$container.css({
                left: "auto",
                right: 0
            }))
        }
    }, i.fn.gapicker = function(t, e) {
        return this.each(function() {
            var n = i(this);
            n.data("gapicker") && n.data("gapicker").remove(), n.data("gapicker", new r(n, t, e))
        }), this
    }
}),
function(t, e) {
    "object" == typeof exports && "object" == typeof module ? module.exports = e(require("underscore"), require("backbone.marionette"), require("jquery"), require("backbone.paginator"), require("backbone")) : "function" == typeof define && define.amd ? define("MaGrid", ["underscore", "backbone.marionette", "jquery", "backbone.paginator", "backbone"], e) : "object" == typeof exports ? exports.MaGrid = e(require("underscore"), require("backbone.marionette"), require("jquery"), require("backbone.paginator"), require("backbone")) : t.MaGrid = e(t._, t.Marionette, t.jQuery, t.Backbone.PageableCollection, t.Backbone)
}("undefined" != typeof self ? self : this, function(t, e, n, i, r) {
    return function(t) {
        function e(i) {
            if (n[i]) return n[i].exports;
            var r = n[i] = {
                i: i,
                l: !1,
                exports: {}
            };
            return t[i].call(r.exports, r, r.exports, e), r.l = !0, r.exports
        }
        var n = {};
        return e.m = t, e.c = n, e.d = function(t, n, i) {
            e.o(t, n) || Object.defineProperty(t, n, {
                configurable: !1,
                enumerable: !0,
                get: i
            })
        }, e.n = function(t) {
            var n = t && t.__esModule ? function() {
                return t["default"]
            } : function() {
                return t
            };
            return e.d(n, "a", n), n
        }, e.o = function(t, e) {
            return Object.prototype.hasOwnProperty.call(t, e)
        }, e.p = "", e(e.s = 12)
    }([function(e, n) {
        e.exports = t
    }, function(t, n) {
        t.exports = e
    }, function(t, e) {
        t.exports = n
    }, function(t, e) {
        t.exports = i
    }, function(t, e) {
        t.exports = r
    }, function(t, e, n) {
        var i = n(0),
            r = n(2),
            s = n(1);
        t.exports = s.View.extend({
            tagName: "thead",
            childViewEventPrefix: "header",
            sortableClass: "sortable",
            sortableAscClass: "sortable asc",
            sortableDescClass: "sortable desc",
            template: i.template(["<tr>", "<% for(i in columns) { %>", "<% if(!columns[i].isHidden) { %>", '<th class="<%= columns[i].className %>" data-region="<%= columns[i].headerRegion %>">', "   <div>", "       <%= displayValue(columns[i]) %>", "   </div>", "</th>", "<% } %>", "<% } %>", "</tr>"].join("\n")),
            events: {
                "click th": "onHeaderClick"
            },
            initialize: function() {
                this.columns = this.getOption("columns"), this.listenToOnce(this, "render", i.bind(function() {
                    this.listenTo(this.columns, "add", this.render), this.listenTo(this.columns, "remove", this.render), this.listenTo(this.columns, "sort", this.render), this.listenTo(this.columns, "change:isHidden", this.render), this.listenTo(this.columns, "change:direction", this.updateColumnSorting)
                }, this))
            },
            templateContext: function() {
                return {
                    columns: this.columns.toJSON(),
                    displayValue: function(t) {
                        return t.header.prototype instanceof s.View ? "" : t.header
                    }
                }
            },
            onRender: function() {
                this.columns.each(i.bind(function(t) {
                    var e = t.get("headerRegion"),
                        n = t.get("header");
                    n.prototype instanceof s.View && (this.hasRegion(e) || this.addRegion(e, '[data-region="' + e + '"]'), this.showChildView(e, new n({
                        collection: this.collection
                    }), {
                        allowMissingEl: !0
                    })), this.updateColumnSorting(t)
                }, this))
            },
            onHeaderClick: function(t) {
                var e = r(t.currentTarget),
                    n = this.columns.findWhere({
                        headerRegion: e.data("region")
                    });
                n.get("sortable") && this.triggerMethod("header:sort", this, n)
            },
            updateColumnSorting: function(t) {
                if (t.get("sortable")) {
                    var e, n = t.get("headerRegion"),
                        i = this.$('[data-region="' + n + '"]'),
                        r = this.getOption("sortableClass"),
                        s = this.getOption("sortableAscClass"),
                        o = this.getOption("sortableDescClass"),
                        a = t.get("direction");
                    e = "asc" === a ? s : "desc" === a ? o : r, i.removeClass(r).removeClass(s).removeClass(o).addClass(e)
                }
            }
        })
    }, function(t, e, n) {
        var i = n(0),
            r = (n(2), n(1));
        t.exports = r.View.extend({
            tagName: "tfoot",
            childViewEventPrefix: "footer",
            template: i.template(["<tr>", "<% for(i in columns) { %>", "<% if(!columns[i].isHidden) { %>", '<th class="<%= columns[i].className %>" data-region="<%= columns[i].footerRegion %>">', "   <div>", "       <%= displayValue(columns[i]) %>", "   </div>", "</th>", "<% } %>", "<% } %>", "</tr>"].join("\n")),
            initialize: function() {
                this.columns = this.getOption("columns"), this.listenToOnce(this, "render", i.bind(function() {
                    this.listenTo(this.columns, "add", this.render), this.listenTo(this.columns, "remove", this.render), this.listenTo(this.columns, "sort", this.render), this.listenTo(this.columns, "change:isHidden", this.render), this.listenTo(this.collection, "all", this.render)
                }, this))
            },
            templateContext: function() {
                return {
                    columns: this.columns.toJSON(),
                    displayValue: function(t) {
                        return t.footer.prototype instanceof r.View ? "" : t.footer
                    }
                }
            },
            onRender: function() {
                this.columns.each(i.bind(function(t) {
                    var e = t.get("footerRegion"),
                        n = t.get("footer");
                    n.prototype instanceof r.View && (this.hasRegion(e) || this.addRegion(e, '[data-region="' + e + '"]'), this.showChildView(e, new n({
                        collection: this.collection
                    })))
                }, this))
            }
        })
    }, function(t, e, n) {
        var i = n(0),
            r = n(1);
        t.exports = r.CollectionView.extend({
            tagName: "tbody",
            childViewEventPrefix: "row",
            columns: null,
            async: !1,
            childViewOptions: function() {
                return {
                    columns: this.getOption("columns")
                }
            },
            emptyViewOptions: function() {
                return {
                    columns: this.getOption("columns")
                }
            },
            addChildView: function(t, e) {
                var n = parseInt(this.getOption("async"));
                return n && n < e ? (this.triggerMethod("before:add:child", this, t), this._setupChildView(t, e), this._isBuffering ? this.children._add(t) : (this._updateIndices(t, !0), this.children.add(t)), setTimeout(i.bind(function() {
                    t._isRendered || (t.supportsRenderLifecycle || r.triggerMethodOn(t, "before:render", t), t.render(), t.supportsRenderLifecycle || (t._isRendered = !0, r.triggerMethodOn(t, "render", t)))
                }, this), 1), this._attachView(t, e), this.triggerMethod("add:child", this, t), t) : void r.CollectionView.prototype.addChildView.apply(this, [t, e])
            },
            _startBuffering: function() {
                var t = parseInt(this.getOption("async"));
                t ? this._isBuffering = !1 : r.CollectionView.prototype._startBuffering.apply(this, arguments)
            }
        })
    }, function(t, e, n) {
        var i = n(0),
            r = (n(2), n(1));
        t.exports = r.View.extend({
            tagName: "div",
            pageText: "per page",
            pageSizes: [10, 50, 100, 500],
            template: i.template(["<select>", "<% for(var i in pageSizes) { %>", '<option <% if(pageSizes[i] == currentSize) { %> selected<% }%>  value="<%= pageSizes[i] %>">', "    <%= pageSizes[i] %> <%= pageText %>", "</option>", "<% }%>", "</select>"].join("")),
            templateContext: function() {
                var t = this.getOption("pageText"),
                    e = this.collection.state.pageSize,
                    n = this.getOption("pageSizes");
                return i.indexOf(n, e) < 0 && (n.push(e), n.sort(function(t, e) {
                    return t - e
                })), {
                    pageText: t,
                    pageSizes: n,
                    currentSize: e
                }
            },
            events: {
                "change select": function(t) {
                    t.preventDefault();
                    var e = parseInt(this.$("select").val());
                    this.triggerMethod("size:change", this, e)
                }
            }
        })
    }, function(t, e, n) {
        var i = n(0),
            r = n(2),
            s = n(1);
        t.exports = s.View.extend({
            tagName: "div",
            windowSize: 3,
            currentPageClass: "mg-current",
            availablePageClass: "mg-available",
            disabledPageClass: "mg-disabled",
            template: i.template(["<ul>", "<% for(i in pages) { %>", '<li class="<%= pages[i].className %>" >', '   <a href="#" title="<%= pages[i].pageNum %>" ', '      <% if(!pages[i].isDisabled && !pages[i].isCurrent) { %>data-page="<%= pages[i].pageNum %>"<% } %>>', "       <%= pages[i].label %>", "   </a>", "</li>", "<% } %>", "</ul>"].join("\n")),
            templateContext: function() {
                var t = this._recalculatePages(),
                    e = this.getOption("currentPageClass"),
                    n = this.getOption("availablePageClass"),
                    r = this.getOption("disabledPageClass");
                return i.each(t, function(t) {
                    t.isCurrent ? t.className = e : t.isDisabled ? t.className = r : t.className = n
                }), {
                    pages: t
                }
            },
            events: {
                "click a": function(t) {
                    t.preventDefault();
                    var e = parseInt(r(t.currentTarget).data("page"));
                    i.isFinite(e) && this.triggerMethod("page:change", this, e)
                }
            },
            initialize: function() {
                this.pages = this._recalculatePages(), this._recalculatePages(), this.listenToOnce(this, "render", i.bind(function() {
                    this.listenTo(this.collection, "add", this.render), this.listenTo(this.collection, "remove", this.render), this.listenTo(this.collection, "reset", this.render), this.listenTo(this.collection, "sync", this.render)
                }, this))
            },
            _recalculatePages: function() {
                var t = this.collection,
                    e = this.getOption("windowSize"),
                    n = t.state,
                    i = Math.max(1, n.firstPage),
                    r = Math.max(1, n.firstPage, n.lastPage),
                    s = Math.max(n.currentPage, n.firstPage),
                    o = [{
                        isCurrent: !1,
                        label: "<<",
                        isDisabled: 1 === s,
                        pageNum: 1
                    }],
                    a = this._calculateWindow(i, s, r, e),
                    l = [{
                        isCurrent: !1,
                        label: ">>",
                        isDisabled: s === r,
                        pageNum: r
                    }];
                return o.concat(a).concat(l)
            },
            _calculateWindow: function(t, e, n, i) {
                var r, s, o = [{
                    isCurrent: !0,
                    label: e + "",
                    isDisabled: !1,
                    pageNum: e
                }];
                for (r = 1; r <= i && !(o.length >= i) && (s = e + r, !(s <= n && (o.push({
                        isCurrent: !1,
                        label: s + "",
                        isDisabled: e === n,
                        pageNum: s
                    }), o.length >= i))) && (s = e - r, !(s >= t && (o.splice(0, 0, {
                        isCurrent: !1,
                        label: s + "",
                        isDisabled: e === t,
                        pageNum: s
                    }), o.length >= i))); r++);
                return o
            }
        })
    }, function(t, e, n) {
        var i = n(0),
            r = n(1);
        t.exports = r.View.extend({
            tagName: "tr",
            childViewEventPrefix: "cell",
            columns: null,
            template: i.template(["<% for(i in columns) { %>", "<% if(!columns[i].isHidden) { %>", '<td class="<%= columns[i].className %>" data-region="<%= columns[i].cellRegion %>">', "   <%= displayValue(columns[i]) %>", "</td>", "<% } %>", "<% } %>"].join("\n")),
            initialize: function() {
                this.columns = this.getOption("columns"), this.listenToOnce(this, "render", i.bind(function() {
                    this.listenTo(this.columns, "add", this.render), this.listenTo(this.columns, "remove", this.render), this.listenTo(this.columns, "sort", this.render), this.listenTo(this.columns, "reset", this.render), this.listenTo(this.columns, "change:isHidden", this.render), this.listenTo(this.model, "change", this.render)
                }, this))
            },
            templateContext: function() {
                var t = this.model;
                return {
                    columns: this.columns.toJSON(),
                    displayValue: function(e) {
                        return e.cell.prototype instanceof r.View ? "" : e.cell(t.get(e.attr), t)
                    }
                }
            },
            onRender: function() {
                this.columns.each(i.bind(function(t) {
                    var e = t.get("cellRegion"),
                        n = t.get("cell");
                    n.prototype instanceof r.View && (this.hasRegion(e) || this.addRegion(e, '[data-region="' + e + '"]'), this.showChildView(e, new n({
                        model: this.model,
                        column: t
                    }), {
                        allowMissingEl: !0
                    }))
                }, this))
            }
        })
    }, function(t, e, n) {
        var i = n(0),
            r = n(1);
        t.exports = r.View.extend({
            tagName: "tr",
            className: "mg-empty",
            emptyText: "No data",
            columns: null,
            template: i.template('<td colspan="<%= colspan %>"><%= emptyText %></td>'),
            templateContext: function() {
                return {
                    emptyText: this.getOption("emptyText"),
                    colspan: this.getOption("columns").filter(function(t) {
                        return !t.get("isHidden")
                    }).length
                }
            },
            initialize: function() {
                this.listenToOnce(this, "render", i.bind(function() {
                    this.listenTo(this.getOption("columns"), "add", this.render), this.listenTo(this.getOption("columns"), "remove", this.render), this.listenTo(this.getOption("columns"), "reset", this.render)
                }, this))
            }
        })
    }, function(t, e, n) {
        var i = (n(0), n(4)),
            r = n(2);
        i.$ = i.$ || r;
        var s = n(13),
            o = n(5),
            a = n(7),
            l = n(6),
            h = n(10),
            u = n(11),
            c = n(9),
            d = n(8),
            f = n(14),
            p = n(15);
        t.exports = {
            GridView: s,
            HeaderView: o,
            BodyView: a,
            FooterView: l,
            DataRowView: h,
            EmptyRowView: u,
            PaginatorView: c,
            SizerView: d,
            ServerCollection: f,
            SequentCollection: p
        }
    }, function(t, e, n) {
        var i = n(0),
            r = n(4),
            s = n(1),
            o = n(3),
            a = n(5),
            l = n(6),
            h = n(7),
            u = n(8),
            c = n(9),
            d = n(10),
            f = n(11);
        t.exports = s.View.extend({
            className: "mg-container",
            async: !1,
            pageSizes: [10, 50, 100, 500],
            paginatorWindowSize: 4,
            headerView: a,
            footerView: l,
            bodyView: h,
            dataRowView: d,
            emptyRowView: f,
            paginatorView: c,
            sizerView: u,
            tagName: "div",
            childViewEventPrefix: "magrid",
            childViewEvents: {
                "header:sort": "onHeaderSort",
                "size:change": "onSizeChange",
                "page:change": "onPageChange"
            },
            template: i.template(['<div class="mg-table">', "    <table>", '       <thead data-region="header"></thead>', '       <tbody data-region="body"></tbody>', '       <tfoot data-region="footer"></tfoot>', "   </table>", "</div>", '<div class="mg-sizer" data-region="sizer"></div>', '<div class="mg-paginator" data-region="paginator"></div>'].join("")),
            templateContext: function() {
                return {
                    tableClassName: this.getOption("tableClassName"),
                    sizerClassName: this.getOption("sizerClassName"),
                    paginatorClassName: this.getOption("paginatorClassName"),
                    overlayClassName: this.getOption("overlayClassName"),
                    overlayText: this.getOption("overlayText")
                }
            },
            ui: {
                header: '[data-region="header"]',
                body: '[data-region="body"]',
                footer: '[data-region="footer"]',
                sizer: '[data-region="sizer"]',
                paginator: '[data-region="paginator"]',
                overlay: '[data-region="overlay"]'
            },
            regions: {
                header: {
                    el: "@ui.header",
                    replaceElement: !0
                },
                body: {
                    el: "@ui.body",
                    replaceElement: !0
                },
                footer: {
                    el: "@ui.footer",
                    replaceElement: !0
                },
                sizer: "@ui.sizer",
                paginator: "@ui.paginator"
            },
            _defaultComparator: function(t, e) {
                return t > e ? 1 : t < e ? -1 : 0
            },
            initialize: function() {
                this._columns = this.columns, this.columns = new r.Collection(this._parseColumns(this._columns)), this._initialSort()
            },
            _initialSort: function() {
                var t, e, n = !0;
                if (this.collection instanceof o) {
                    var i = this.collection.state;
                    e = i.order, t = i.sortKey, n = !1
                }
                t = t || this.getOption("orderBy") || this.getOption("order_by"), e = e || this.getOption("direction");
                var r = {
                    "-1": "asc",
                    1: "desc"
                };
                e = r[e] || e;
                var s;
                this.columns.each(function(n) {
                    n.get("orderBy") === t ? (s = n, n.set("direction", e)) : n.get("direction") && n.set("direction", null)
                }), n && s && this._sortCollection(t, e, s.get("comparator"))
            },
            onRender: function() {
                this._renderHeader(), this._renderBody(), this._renderFooter(), this._renderSizer(), this._renderPaginator()
            },
            onHeaderSort: function(t, e) {
                if (e.get("sortable")) {
                    var n = e.get("orderBy"),
                        i = e.get("direction");
                    i = "asc" === i ? "desc" : "desc" === i ? "asc" : e.get("firstDirection"), this.columns.each(function(t) {
                        t === e ? t.set("direction", i) : t.get("direction") && t.set("direction", null)
                    }), this._sortCollection(n, i, e.get("comparator"))
                }
            },
            onSizeChange: function(t, e) {
                var n = this.collection,
                    i = n.state;
                return i && 1 === i.currentPage && 0 === n.length ? void(i.pageSize = e) : void this.collection.setPageSize(e, {
                    first: !0,
                    reset: !0
                })
            },
            onPageChange: function(t, e) {
                this.collection.getPage(e, {
                    reset: !0
                })
            },
            _renderHeader: function() {
                var t = this.getOption("headerView");
                this.showChildView("header", new t({
                    collection: this.collection,
                    columns: this.columns
                }))
            },
            _renderFooter: function() {
                var t = this.getOption("footerView");
                null != t && this.showChildView("footer", new t({
                    collection: this.collection,
                    columns: this.columns
                }))
            },
            _renderBody: function() {
                var t = this.getOption("bodyView"),
                    e = this.getOption("dataRowView"),
                    n = this.getOption("emptyRowView");
                this.showChildView("body", new t({
                    collection: this.collection,
                    columns: this.columns,
                    childView: e,
                    emptyView: n,
                    async: this.getOption("async")
                }))
            },
            _renderSizer: function() {
                var t = this.getOption("sizerView");
                this.collection instanceof o && t && this.showChildView("sizer", new t({
                    collection: this.collection,
                    pageSizes: this.getOption("pageSizes")
                }))
            },
            _renderPaginator: function() {
                var t = this.getOption("paginatorView");
                this.collection instanceof o && t && this.showChildView("paginator", new t({
                    collection: this.collection,
                    windowSize: this.getOption("paginatorWindowSize")
                }))
            },
            _sortCollection: function(t, e, n) {
                this.collection instanceof o ? this._sortPageableCollection(t, e, n) : this._sortBackboneCollection(t, e, n)
            },
            _sortPageableCollection: function(t, e, n) {
                if (!i.isString(t)) throw TypeError("String comparator required for pageable collection!");
                if (n) throw TypeError("Custom comparator is not supported for pageable collection!");
                if (this.collection.setSorting(t, "asc" === e ? -1 : 1), "server" === this.collection.mode) this.collection.getFirstPage({
                    reset: !0
                });
                else {
                    if ("client" !== this.collection.mode) throw TypeError("Infinite mode not supported!");
                    this.collection.fullCollection.sort()
                }
            },
            _sortBackboneCollection: function(t, e, n) {
                i.isString(t) ? (n = n || this._defaultComparator, this.collection.comparator = function(i, r) {
                    var s = n(i.get(t), r.get(t));
                    return "desc" === e ? s * -1 : s
                }) : this.collection.comparator = t, this.collection.sort()
            },
            _parseColumns: function(t) {
                return t = t || this.getOption("columns"), i.map(t, function(t, e) {
                    return i.defaults(t, {
                        cell: i.identity,
                        cellRegion: "cell_" + e,
                        header: "",
                        headerRegion: "header_" + e,
                        footer: "",
                        footerRegion: "footer_" + e,
                        orderBy: t.order_by || t.attr,
                        direction: "",
                        sortable: !1,
                        firstDirection: t.first_direction || "asc",
                        isHidden: !1,
                        className: ""
                    })
                })
            }
        })
    }, function(t, e, n) {
        var i = n(0),
            r = n(3);
        t.exports = r.extend({
            state: {
                firstPage: 1,
                currentPage: 1,
                pageSize: 20
            },
            queryParams: {
                currentPage: null,
                totalPages: null,
                totalRecords: null,
                sortKey: "order_by",
                order: "direction",
                directions: {
                    "-1": "asc",
                    1: "desc"
                },
                pageSize: "limit",
                offset: function() {
                    return (this.state.currentPage - 1) * this.state.pageSize
                }
            },
            initialize: function(t, e) {
                i.isUndefined(this.url) && e && e.url && (this.url = e.url)
            },
            parseRecords: function(t) {
                return t.items
            },
            parseState: function(t, e, n, r) {
                var s = parseInt(t.total);
                return i.isFinite(s) || (s = (n.currentPage - 1) * n.pageSize + t.items.length, t.items.length !== n.pageSize && t.items.length || (s += 1)), {
                    totalRecords: s
                }
            },
            switchMode: function(t, e) {
                if ("server" !== t) throw new TypeError("Only server mode allowed!");
                r.prototype.switchMode.apply(this, arguments)
            }
        })
    }, function(t, e, n) {
        var i = n(0),
            r = n(3);
        t.exports = r.extend({
            state: {
                firstPage: 1,
                currentPage: 1,
                pageSize: 20,
                fetch_state: ""
            },
            queryParams: {
                currentPage: null,
                totalPages: null,
                totalRecords: null,
                sortKey: "order_by",
                order: "direction",
                directions: {
                    "-1": "asc",
                    1: "desc"
                },
                pageSize: "fetch_size",
                fetch_state: function() {
                    if (!i.has(this.pages, this.state.currentPage)) throw TypeError("Page is unavailable under sequent mode!");
                    return this.pages[this.state.currentPage]
                }
            },
            pages: {
                1: ""
            },
            initialize: function(t, e) {
                i.isUndefined(this.url) && e && e.url && (this.url = e.url)
            },
            parseRecords: function(t) {
                return t.items
            },
            parseState: function(t, e, n, r) {
                var s = t.fetch_state,
                    o = n.currentPage + 1,
                    a = n.totalPages || 1;
                return s && (this.pages[o] = s, a = i.keys(this.pages).length), {
                    totalRecords: a * n.pageSize
                }
            },
            switchMode: function(t, e) {
                if ("server" !== t) throw new TypeError("Only server mode allowed!");
                r.prototype.switchMode.apply(this, arguments)
            },
            setPageSize: function() {
                this._resetState(), r.prototype.setPageSize.apply(this, arguments)
            },
            setSorting: function() {
                this._resetState(), r.prototype.setSorting.apply(this, arguments)
            },
            fetch: function(t) {
                var e = i.mapObject(this.queryParams, function(t, e) {
                    return i.isFunction(t) ? t.apply(this) : t
                }, this);
                return e = i.omit(e, i.keys(this.__proto__.queryParams)), i.isEqual(e, this.computedState) || (this.computedState = e, this._resetState(), t = i.extend({}, t, {
                    from: 1,
                    to: 1
                })), r.prototype.fetch.apply(this, [t])
            },
            _resetState: function() {
                this.state.currentPage = 1, this.state.totalPages = 1, this.state.totalRecords = null, this.state.lastPage = 1, this.pages = {
                    1: ""
                }
            }
        })
    }])
}),
function(t) {
    "use strict";

    function e(t) {
        function u(t, e) {
            var n, i, r, o, a, l, h, d, p = this;
            if (!(p instanceof u)) return new u(t, e);
            if (null == e) {
                if (t instanceof u) return p.s = t.s, p.e = t.e, void(p.c = (t = t.c) ? t.slice() : t);
                if (l = "number" == typeof t, l && 0 * t == 0) {
                    if (p.s = 1 / t < 0 ? (t = -t, -1) : 1, t === ~~t) {
                        for (o = 0, a = t; a >= 10; a /= 10, o++);
                        return p.e = o, void(p.c = [t])
                    }
                    d = t + ""
                } else {
                    if (!c.test(d = t + "")) return T(p, d, l);
                    p.s = 45 == d.charCodeAt(0) ? (d = d.slice(1), -1) : 1
                }(o = d.indexOf(".")) > -1 && (d = d.replace(".", "")), (a = d.search(/e/i)) > 0 ? (o < 0 && (o = a), o += +d.slice(a + 1), d = d.substring(0, a)) : o < 0 && (o = d.length)
            } else {
                if (s(e, 2, H.length, "Base"), d = t + "", 10 == e) return p = new u(t instanceof u ? t : d), D(p, R + p.e + 1, $);
                if (l = "number" == typeof t) {
                    if (0 * t != 0) return T(p, d, l, e);
                    if (p.s = 1 / t < 0 ? (d = d.slice(1), -1) : 1, u.DEBUG && d.replace(/^0\.0*|\./, "").length > 15) throw Error(g + t);
                    l = !1
                } else p.s = 45 === d.charCodeAt(0) ? (d = d.slice(1), -1) : 1;
                for (n = H.slice(0, e), o = a = 0, h = d.length; a < h; a++)
                    if (n.indexOf(i = d.charAt(a)) < 0) {
                        if ("." == i) {
                            if (a > o) {
                                o = h;
                                continue
                            }
                        } else if (!r && (d == d.toUpperCase() && (d = d.toLowerCase()) || d == d.toLowerCase() && (d = d.toUpperCase()))) {
                            r = !0, a = -1, o = 0;
                            continue
                        }
                        return T(p, t + "", l, e)
                    }
                d = k(d, e, 10, p.s), (o = d.indexOf(".")) > -1 ? d = d.replace(".", "") : o = d.length
            }
            for (a = 0; 48 === d.charCodeAt(a); a++);
            for (h = d.length; 48 === d.charCodeAt(--h););
            if (d = d.slice(a, ++h)) {
                if (h -= a, l && u.DEBUG && h > 15 && (t > y || t !== f(t))) throw Error(g + p.s * t);
                if (o = o - a - 1, o > N) p.c = p.e = null;
                else if (o < V) p.c = [p.e = 0];
                else {
                    if (p.e = o, p.c = [], a = (o + 1) % v, o < 0 && (a += v), a < h) {
                        for (a && p.c.push(+d.slice(0, a)), h -= v; a < h;) p.c.push(+d.slice(a, a += v));
                        d = d.slice(a), a = v - d.length
                    } else a -= h;
                    for (; a--; d += "0");
                    p.c.push(+d)
                }
            } else p.c = [p.e = 0]
        }

        function x(t, e, n, r) {
            var o, a, c, d, f;
            if (null == n ? n = $ : s(n, 0, 8), !t.c) return t.toString();
            if (o = t.c[0], c = t.e, null == e) f = i(t.c), f = 1 == r || 2 == r && c <= P ? l(f, c) : h(f, c, "0");
            else if (t = D(new u(t), e, n), a = t.e, f = i(t.c), d = f.length, 1 == r || 2 == r && (e <= a || a <= P)) {
                for (; d < e; f += "0", d++);
                f = l(f, a)
            } else if (e -= c, f = h(f, a, "0"), a + 1 > d) {
                if (--e > 0)
                    for (f += "."; e--; f += "0");
            } else if (e += a - d, e > 0)
                for (a + 1 == d && (f += "."); e--; f += "0");
            return t.s < 0 && o ? "-" + f : f
        }

        function C(t, e) {
            var n, i, r = 0;
            for (o(t[0]) && (t = t[0]), n = new u(t[0]); ++r < t.length;) {
                if (i = new u(t[r]), !i.s) {
                    n = i;
                    break
                }
                e.call(n, i) && (n = i)
            }
            return n
        }

        function S(t, e, n) {
            for (var i = 1, r = e.length; !e[--r]; e.pop());
            for (r = e[0]; r >= 10; r /= 10, i++);
            return (n = i + n * v - 1) > N ? t.c = t.e = null : n < V ? t.c = [t.e = 0] : (t.e = n, t.c = e), t
        }

        function D(t, e, n, i) {
            var r, s, o, a, l, h, u, c = t.c,
                p = w;
            if (c) {
                t: {
                    for (r = 1, a = c[0]; a >= 10; a /= 10, r++);
                    if (s = e - r, s < 0) s += v,
                    o = e,
                    l = c[h = 0],
                    u = l / p[r - o - 1] % 10 | 0;
                    else if (h = d((s + 1) / v), h >= c.length) {
                        if (!i) break t;
                        for (; c.length <= h; c.push(0));
                        l = u = 0, r = 1, s %= v, o = s - v + 1
                    } else {
                        for (l = a = c[h], r = 1; a >= 10; a /= 10, r++);
                        s %= v, o = s - v + r, u = o < 0 ? 0 : l / p[r - o - 1] % 10 | 0
                    }
                    if (i = i || e < 0 || null != c[h + 1] || (o < 0 ? l : l % p[r - o - 1]), i = n < 4 ? (u || i) && (0 == n || n == (t.s < 0 ? 3 : 2)) : u > 5 || 5 == u && (4 == n || i || 6 == n && (s > 0 ? o > 0 ? l / p[r - o] : 0 : c[h - 1]) % 10 & 1 || n == (t.s < 0 ? 8 : 7)), e < 1 || !c[0]) return c.length = 0, i ? (e -= t.e + 1, c[0] = p[(v - e % v) % v], t.e = -e || 0) : c[0] = t.e = 0, t;
                    if (0 == s ? (c.length = h, a = 1, h--) : (c.length = h + 1, a = p[v - s], c[h] = o > 0 ? f(l / p[r - o] % p[o]) * a : 0), i)
                        for (;;) {
                            if (0 == h) {
                                for (s = 1, o = c[0]; o >= 10; o /= 10, s++);
                                for (o = c[0] += a, a = 1; o >= 10; o /= 10, a++);
                                s != a && (t.e++, c[0] == m && (c[0] = 1));
                                break
                            }
                            if (c[h] += a, c[h] != m) break;
                            c[h--] = 0, a = 1
                        }
                    for (s = c.length; 0 === c[--s]; c.pop());
                }
                t.e > N ? t.c = t.e = null : t.e < V && (t.c = [t.e = 0])
            }
            return t
        }
        var E, k, T, O = u.prototype = {
                constructor: u,
                toString: null,
                valueOf: null
            },
            M = new u(1),
            R = 20,
            $ = 4,
            P = -7,
            A = 21,
            V = -1e7,
            N = 1e7,
            I = !1,
            j = 1,
            L = 0,
            B = {
                decimalSeparator: ".",
                groupSeparator: ",",
                groupSize: 3,
                secondaryGroupSize: 0,
                fractionGroupSeparator: " ",
                fractionGroupSize: 0
            },
            H = "0123456789abcdefghijklmnopqrstuvwxyz";
        return u.clone = e, u.ROUND_UP = 0, u.ROUND_DOWN = 1, u.ROUND_CEIL = 2, u.ROUND_FLOOR = 3, u.ROUND_HALF_UP = 4, u.ROUND_HALF_DOWN = 5, u.ROUND_HALF_EVEN = 6, u.ROUND_HALF_CEIL = 7, u.ROUND_HALF_FLOOR = 8, u.EUCLID = 9, u.config = u.set = function(t) {
            var e, n;
            if (null != t) {
                if ("object" != typeof t) throw Error(p + "Object expected: " + t);
                if (t.hasOwnProperty(e = "DECIMAL_PLACES") && (n = t[e], s(n, 0, b, e), R = n), t.hasOwnProperty(e = "ROUNDING_MODE") && (n = t[e], s(n, 0, 8, e), $ = n), t.hasOwnProperty(e = "EXPONENTIAL_AT") && (n = t[e], o(n) ? (s(n[0], -b, 0, e), s(n[1], 0, b, e), P = n[0], A = n[1]) : (s(n, -b, b, e), P = -(A = n < 0 ? -n : n))), t.hasOwnProperty(e = "RANGE"))
                    if (n = t[e], o(n)) s(n[0], -b, -1, e), s(n[1], 1, b, e), V = n[0], N = n[1];
                    else {
                        if (s(n, -b, b, e), !n) throw Error(p + e + " cannot be zero: " + n);
                        V = -(N = n < 0 ? -n : n)
                    }
                if (t.hasOwnProperty(e = "CRYPTO")) {
                    if (n = t[e], n !== !!n) throw Error(p + e + " not true or false: " + n);
                    if (n) {
                        if ("undefined" == typeof crypto || !crypto || !crypto.getRandomValues && !crypto.randomBytes) throw I = !n, Error(p + "crypto unavailable");
                        I = n
                    } else I = n
                }
                if (t.hasOwnProperty(e = "MODULO_MODE") && (n = t[e], s(n, 0, 9, e), j = n), t.hasOwnProperty(e = "POW_PRECISION") && (n = t[e], s(n, 0, b, e), L = n), t.hasOwnProperty(e = "FORMAT")) {
                    if (n = t[e], "object" != typeof n) throw Error(p + e + " not an object: " + n);
                    B = n
                }
                if (t.hasOwnProperty(e = "ALPHABET")) {
                    if (n = t[e], "string" != typeof n || /^.$|\.|(.).*\1/.test(n)) throw Error(p + e + " invalid: " + n);
                    H = n
                }
            }
            return {
                DECIMAL_PLACES: R,
                ROUNDING_MODE: $,
                EXPONENTIAL_AT: [P, A],
                RANGE: [V, N],
                CRYPTO: I,
                MODULO_MODE: j,
                POW_PRECISION: L,
                FORMAT: B,
                ALPHABET: H
            }
        }, u.isBigNumber = function(t) {
            return t instanceof u || t && t._isBigNumber === !0 || !1
        }, u.maximum = u.max = function() {
            return C(arguments, O.lt)
        }, u.minimum = u.min = function() {
            return C(arguments, O.gt)
        }, u.random = function() {
            var t = 9007199254740992,
                e = Math.random() * t & 2097151 ? function() {
                    return f(Math.random() * t)
                } : function() {
                    return 8388608 * (1073741824 * Math.random() | 0) + (8388608 * Math.random() | 0)
                };
            return function(t) {
                var n, i, r, o, a, l = 0,
                    h = [],
                    c = new u(M);
                if (null == t ? t = R : s(t, 0, b), o = d(t / v), I)
                    if (crypto.getRandomValues) {
                        for (n = crypto.getRandomValues(new Uint32Array(o *= 2)); l < o;) a = 131072 * n[l] + (n[l + 1] >>> 11), a >= 9e15 ? (i = crypto.getRandomValues(new Uint32Array(2)), n[l] = i[0], n[l + 1] = i[1]) : (h.push(a % 1e14), l += 2);
                        l = o / 2
                    } else {
                        if (!crypto.randomBytes) throw I = !1, Error(p + "crypto unavailable");
                        for (n = crypto.randomBytes(o *= 7); l < o;) a = 281474976710656 * (31 & n[l]) + 1099511627776 * n[l + 1] + 4294967296 * n[l + 2] + 16777216 * n[l + 3] + (n[l + 4] << 16) + (n[l + 5] << 8) + n[l + 6], a >= 9e15 ? crypto.randomBytes(7).copy(n, l) : (h.push(a % 1e14), l += 7);
                        l = o / 7
                    }
                if (!I)
                    for (; l < o;) a = e(), a < 9e15 && (h[l++] = a % 1e14);
                for (o = h[--l], t %= v, o && t && (a = w[v - t], h[l] = f(o / a) * a); 0 === h[l]; h.pop(), l--);
                if (l < 0) h = [r = 0];
                else {
                    for (r = -1; 0 === h[0]; h.splice(0, 1), r -= v);
                    for (l = 1, a = h[0]; a >= 10; a /= 10, l++);
                    l < v && (r -= v - l)
                }
                return c.e = r, c.c = h, c
            }
        }(), k = function() {
            function t(t, e, n, i) {
                for (var r, s, o = [0], a = 0, l = t.length; a < l;) {
                    for (s = o.length; s--; o[s] *= e);
                    for (o[0] += i.indexOf(t.charAt(a++)), r = 0; r < o.length; r++) o[r] > n - 1 && (null == o[r + 1] && (o[r + 1] = 0), o[r + 1] += o[r] / n | 0, o[r] %= n)
                }
                return o.reverse()
            }
            var e = "0123456789";
            return function(n, r, s, o, a) {
                var l, c, d, f, p, g, m, v, y = n.indexOf("."),
                    w = R,
                    _ = $;
                for (y >= 0 && (f = L, L = 0, n = n.replace(".", ""), v = new u(r), g = v.pow(n.length - y), L = f, v.c = t(h(i(g.c), g.e, "0"), 10, s, e), v.e = v.c.length), m = t(n, r, s, a ? (l = H, e) : (l = e, H)), d = f = m.length; 0 == m[--f]; m.pop());
                if (!m[0]) return l.charAt(0);
                if (y < 0 ? --d : (g.c = m, g.e = d, g.s = o, g = E(g, v, w, _, s), m = g.c, p = g.r, d = g.e), c = d + w + 1, y = m[c], f = s / 2, p = p || c < 0 || null != m[c + 1], p = _ < 4 ? (null != y || p) && (0 == _ || _ == (g.s < 0 ? 3 : 2)) : y > f || y == f && (4 == _ || p || 6 == _ && 1 & m[c - 1] || _ == (g.s < 0 ? 8 : 7)), c < 1 || !m[0]) n = p ? h(l.charAt(1), -w, l.charAt(0)) : l.charAt(0);
                else {
                    if (m.length = c, p)
                        for (--s; ++m[--c] > s;) m[c] = 0, c || (++d, m = [1].concat(m));
                    for (f = m.length; !m[--f];);
                    for (y = 0, n = ""; y <= f; n += l.charAt(m[y++]));
                    n = h(n, d, l.charAt(0))
                }
                return n
            }
        }(), E = function() {
            function t(t, e, n) {
                var i, r, s, o, a = 0,
                    l = t.length,
                    h = e % _,
                    u = e / _ | 0;
                for (t = t.slice(); l--;) s = t[l] % _, o = t[l] / _ | 0, i = u * s + o * h, r = h * s + i % _ * _ + a, a = (r / n | 0) + (i / _ | 0) + u * o, t[l] = r % n;
                return a && (t = [a].concat(t)), t
            }

            function e(t, e, n, i) {
                var r, s;
                if (n != i) s = n > i ? 1 : -1;
                else
                    for (r = s = 0; r < n; r++)
                        if (t[r] != e[r]) {
                            s = t[r] > e[r] ? 1 : -1;
                            break
                        } return s
            }

            function i(t, e, n, i) {
                for (var r = 0; n--;) t[n] -= r, r = t[n] < e[n] ? 1 : 0, t[n] = r * i + t[n] - e[n];
                for (; !t[0] && t.length > 1; t.splice(0, 1));
            }
            return function(r, s, o, a, l) {
                var h, c, d, p, g, y, w, _, b, x, C, S, E, k, T, O, M, R = r.s == s.s ? 1 : -1,
                    $ = r.c,
                    P = s.c;
                if (!($ && $[0] && P && P[0])) return new u(r.s && s.s && ($ ? !P || $[0] != P[0] : P) ? $ && 0 == $[0] || !P ? 0 * R : R / 0 : NaN);
                for (_ = new u(R), b = _.c = [], c = r.e - s.e, R = o + c + 1, l || (l = m, c = n(r.e / v) - n(s.e / v), R = R / v | 0), d = 0; P[d] == ($[d] || 0); d++);
                if (P[d] > ($[d] || 0) && c--, R < 0) b.push(1), p = !0;
                else {
                    for (k = $.length, O = P.length, d = 0, R += 2, g = f(l / (P[0] + 1)), g > 1 && (P = t(P, g, l), $ = t($, g, l), O = P.length, k = $.length), E = O, x = $.slice(0, O), C = x.length; C < O; x[C++] = 0);
                    M = P.slice(), M = [0].concat(M), T = P[0], P[1] >= l / 2 && T++;
                    do {
                        if (g = 0, h = e(P, x, O, C), h < 0) {
                            if (S = x[0], O != C && (S = S * l + (x[1] || 0)), g = f(S / T), g > 1)
                                for (g >= l && (g = l - 1), y = t(P, g, l), w = y.length, C = x.length; 1 == e(y, x, w, C);) g--, i(y, O < w ? M : P, w, l), w = y.length, h = 1;
                            else 0 == g && (h = g = 1), y = P.slice(), w = y.length;
                            if (w < C && (y = [0].concat(y)), i(x, y, C, l), C = x.length, h == -1)
                                for (; e(P, x, O, C) < 1;) g++, i(x, O < C ? M : P, C, l), C = x.length
                        } else 0 === h && (g++, x = [0]);
                        b[d++] = g, x[0] ? x[C++] = $[E] || 0 : (x = [$[E]], C = 1)
                    } while ((E++ < k || null != x[0]) && R--);
                    p = null != x[0], b[0] || b.splice(0, 1)
                }
                if (l == m) {
                    for (d = 1, R = b[0]; R >= 10; R /= 10, d++);
                    D(_, o + (_.e = d + c * v - 1) + 1, a, p)
                } else _.e = c, _.r = +p;
                return _
            }
        }(), T = function() {
            var t = /^(-?)0([xbo])(?=\w[\w.]*$)/i,
                e = /^([^.]+)\.$/,
                n = /^\.([^.]+)$/,
                i = /^-?(Infinity|NaN)$/,
                r = /^\s*\+(?=[\w.])|^\s+|\s+$/g;
            return function(s, o, a, l) {
                var h, c = a ? o : o.replace(r, "");
                if (i.test(c)) s.s = isNaN(c) ? null : c < 0 ? -1 : 1, s.c = s.e = null;
                else {
                    if (!a && (c = c.replace(t, function(t, e, n) {
                            return h = "x" == (n = n.toLowerCase()) ? 16 : "b" == n ? 2 : 8, l && l != h ? t : e
                        }), l && (h = l, c = c.replace(e, "$1").replace(n, "0.$1")), o != c)) return new u(c, h);
                    if (u.DEBUG) throw Error(p + "Not a" + (l ? " base " + l : "") + " number: " + o);
                    s.c = s.e = s.s = null
                }
            }
        }(), O.absoluteValue = O.abs = function() {
            var t = new u(this);
            return t.s < 0 && (t.s = 1), t
        }, O.comparedTo = function(t, e) {
            return r(this, new u(t, e))
        }, O.decimalPlaces = O.dp = function(t, e) {
            var i, r, o, a = this;
            if (null != t) return s(t, 0, b), null == e ? e = $ : s(e, 0, 8), D(new u(a), t + a.e + 1, e);
            if (!(i = a.c)) return null;
            if (r = ((o = i.length - 1) - n(this.e / v)) * v, o = i[o])
                for (; o % 10 == 0; o /= 10, r--);
            return r < 0 && (r = 0), r
        }, O.dividedBy = O.div = function(t, e) {
            return E(this, new u(t, e), R, $)
        }, O.dividedToIntegerBy = O.idiv = function(t, e) {
            return E(this, new u(t, e), 0, 1)
        }, O.exponentiatedBy = O.pow = function(t, e) {
            var n, i, r, s, o, l, h, c, g = this;
            if (t = new u(t), t.c && !t.isInteger()) throw Error(p + "Exponent not an integer: " + t);
            if (null != e && (e = new u(e)), o = t.e > 14, !g.c || !g.c[0] || 1 == g.c[0] && !g.e && 1 == g.c.length || !t.c || !t.c[0]) return c = new u(Math.pow(+g.valueOf(), o ? 2 - a(t) : +t)), e ? c.mod(e) : c;
            if (l = t.s < 0, e) {
                if (e.c ? !e.c[0] : !e.s) return new u(NaN);
                i = !l && g.isInteger() && e.isInteger(), i && (g = g.mod(e))
            } else {
                if (t.e > 9 && (g.e > 0 || g.e < -1 || (0 == g.e ? g.c[0] > 1 || o && g.c[1] >= 24e7 : g.c[0] < 8e13 || o && g.c[0] <= 9999975e7))) return r = g.s < 0 && a(t) ? -0 : 0, g.e > -1 && (r = 1 / r), new u(l ? 1 / r : r);
                L && (r = d(L / v + 2))
            }
            for (o ? (n = new u(.5), h = a(t)) : h = t % 2, l && (t.s = 1), c = new u(M);;) {
                if (h) {
                    if (c = c.times(g), !c.c) break;
                    r ? c.c.length > r && (c.c.length = r) : i && (c = c.mod(e))
                }
                if (o) {
                    if (t = t.times(n), D(t, t.e + 1, 1), !t.c[0]) break;
                    o = t.e > 14, h = a(t)
                } else {
                    if (t = f(t / 2), !t) break;
                    h = t % 2
                }
                g = g.times(g), r ? g.c && g.c.length > r && (g.c.length = r) : i && (g = g.mod(e))
            }
            return i ? c : (l && (c = M.div(c)), e ? c.mod(e) : r ? D(c, L, $, s) : c)
        }, O.integerValue = function(t) {
            var e = new u(this);
            return null == t ? t = $ : s(t, 0, 8), D(e, e.e + 1, t)
        }, O.isEqualTo = O.eq = function(t, e) {
            return 0 === r(this, new u(t, e))
        }, O.isFinite = function() {
            return !!this.c
        }, O.isGreaterThan = O.gt = function(t, e) {
            return r(this, new u(t, e)) > 0
        }, O.isGreaterThanOrEqualTo = O.gte = function(t, e) {
            return 1 === (e = r(this, new u(t, e))) || 0 === e
        }, O.isInteger = function() {
            return !!this.c && n(this.e / v) > this.c.length - 2
        }, O.isLessThan = O.lt = function(t, e) {
            return r(this, new u(t, e)) < 0
        }, O.isLessThanOrEqualTo = O.lte = function(t, e) {
            return (e = r(this, new u(t, e))) === -1 || 0 === e
        }, O.isNaN = function() {
            return !this.s
        }, O.isNegative = function() {
            return this.s < 0
        }, O.isPositive = function() {
            return this.s > 0
        }, O.isZero = function() {
            return !!this.c && 0 == this.c[0]
        }, O.minus = function(t, e) {
            var i, r, s, o, a = this,
                l = a.s;
            if (t = new u(t, e), e = t.s, !l || !e) return new u(NaN);
            if (l != e) return t.s = -e, a.plus(t);
            var h = a.e / v,
                c = t.e / v,
                d = a.c,
                f = t.c;
            if (!h || !c) {
                if (!d || !f) return d ? (t.s = -e, t) : new u(f ? a : NaN);
                if (!d[0] || !f[0]) return f[0] ? (t.s = -e, t) : new u(d[0] ? a : 3 == $ ? -0 : 0)
            }
            if (h = n(h), c = n(c), d = d.slice(), l = h - c) {
                for ((o = l < 0) ? (l = -l, s = d) : (c = h, s = f), s.reverse(), e = l; e--; s.push(0));
                s.reverse()
            } else
                for (r = (o = (l = d.length) < (e = f.length)) ? l : e, l = e = 0; e < r; e++)
                    if (d[e] != f[e]) {
                        o = d[e] < f[e];
                        break
                    } if (o && (s = d, d = f, f = s, t.s = -t.s), e = (r = f.length) - (i = d.length), e > 0)
                for (; e--; d[i++] = 0);
            for (e = m - 1; r > l;) {
                if (d[--r] < f[r]) {
                    for (i = r; i && !d[--i]; d[i] = e);
                    --d[i], d[r] += m
                }
                d[r] -= f[r]
            }
            for (; 0 == d[0]; d.splice(0, 1), --c);
            return d[0] ? S(t, d, c) : (t.s = 3 == $ ? -1 : 1, t.c = [t.e = 0], t)
        }, O.modulo = O.mod = function(t, e) {
            var n, i, r = this;
            return t = new u(t, e), !r.c || !t.s || t.c && !t.c[0] ? new u(NaN) : !t.c || r.c && !r.c[0] ? new u(r) : (9 == j ? (i = t.s, t.s = 1, n = E(r, t, 0, 3), t.s = i, n.s *= i) : n = E(r, t, 0, j), t = r.minus(n.times(t)), t.c[0] || 1 != j || (t.s = r.s), t)
        }, O.multipliedBy = O.times = function(t, e) {
            var i, r, s, o, a, l, h, c, d, f, p, g, y, w, b, x = this,
                C = x.c,
                D = (t = new u(t, e)).c;
            if (!(C && D && C[0] && D[0])) return !x.s || !t.s || C && !C[0] && !D || D && !D[0] && !C ? t.c = t.e = t.s = null : (t.s *= x.s, C && D ? (t.c = [0], t.e = 0) : t.c = t.e = null), t;
            for (r = n(x.e / v) + n(t.e / v), t.s *= x.s, h = C.length, f = D.length, h < f && (y = C, C = D, D = y, s = h, h = f, f = s), s = h + f, y = []; s--; y.push(0));
            for (w = m, b = _, s = f; --s >= 0;) {
                for (i = 0, p = D[s] % b, g = D[s] / b | 0, a = h, o = s + a; o > s;) c = C[--a] % b, d = C[a] / b | 0, l = g * c + d * p, c = p * c + l % b * b + y[o] + i, i = (c / w | 0) + (l / b | 0) + g * d, y[o--] = c % w;
                y[o] = i
            }
            return i ? ++r : y.splice(0, 1), S(t, y, r)
        }, O.negated = function() {
            var t = new u(this);
            return t.s = -t.s || null, t
        }, O.plus = function(t, e) {
            var i, r = this,
                s = r.s;
            if (t = new u(t, e), e = t.s, !s || !e) return new u(NaN);
            if (s != e) return t.s = -e, r.minus(t);
            var o = r.e / v,
                a = t.e / v,
                l = r.c,
                h = t.c;
            if (!o || !a) {
                if (!l || !h) return new u(s / 0);
                if (!l[0] || !h[0]) return h[0] ? t : new u(l[0] ? r : 0 * s)
            }
            if (o = n(o), a = n(a), l = l.slice(), s = o - a) {
                for (s > 0 ? (a = o, i = h) : (s = -s, i = l), i.reverse(); s--; i.push(0));
                i.reverse()
            }
            for (s = l.length, e = h.length, s - e < 0 && (i = h, h = l, l = i, e = s), s = 0; e;) s = (l[--e] = l[e] + h[e] + s) / m | 0, l[e] = m === l[e] ? 0 : l[e] % m;
            return s && (l = [s].concat(l), ++a), S(t, l, a)
        }, O.precision = O.sd = function(t, e) {
            var n, i, r, o = this;
            if (null != t && t !== !!t) return s(t, 1, b), null == e ? e = $ : s(e, 0, 8), D(new u(o), t, e);
            if (!(n = o.c)) return null;
            if (r = n.length - 1, i = r * v + 1, r = n[r]) {
                for (; r % 10 == 0; r /= 10, i--);
                for (r = n[0]; r >= 10; r /= 10, i++);
            }
            return t && o.e + 1 > i && (i = o.e + 1), i
        }, O.shiftedBy = function(t) {
            return s(t, -y, y), this.times("1e" + t)
        }, O.squareRoot = O.sqrt = function() {
            var t, e, r, s, o, a = this,
                l = a.c,
                h = a.s,
                c = a.e,
                d = R + 4,
                f = new u("0.5");
            if (1 !== h || !l || !l[0]) return new u(!h || h < 0 && (!l || l[0]) ? NaN : l ? a : 1 / 0);
            if (h = Math.sqrt(+a), 0 == h || h == 1 / 0 ? (e = i(l), (e.length + c) % 2 == 0 && (e += "0"), h = Math.sqrt(e), c = n((c + 1) / 2) - (c < 0 || c % 2), h == 1 / 0 ? e = "1e" + c : (e = h.toExponential(), e = e.slice(0, e.indexOf("e") + 1) + c), r = new u(e)) : r = new u(h + ""), r.c[0])
                for (c = r.e, h = c + d, h < 3 && (h = 0);;)
                    if (o = r, r = f.times(o.plus(E(a, o, d, 1))), i(o.c).slice(0, h) === (e = i(r.c)).slice(0, h)) {
                        if (r.e < c && --h, e = e.slice(h - 3, h + 1), "9999" != e && (s || "4999" != e)) {
                            +e && (+e.slice(1) || "5" != e.charAt(0)) || (D(r, r.e + R + 2, 1), t = !r.times(r).eq(a));
                            break
                        }
                        if (!s && (D(o, o.e + R + 2, 0), o.times(o).eq(a))) {
                            r = o;
                            break
                        }
                        d += 4, h += 4, s = 1
                    }
            return D(r, r.e + R + 1, $, t)
        }, O.toExponential = function(t, e) {
            return null != t && (s(t, 0, b), t++), x(this, t, e, 1)
        }, O.toFixed = function(t, e) {
            return null != t && (s(t, 0, b), t = t + this.e + 1), x(this, t, e)
        }, O.toFormat = function(t, e) {
            var n = this.toFixed(t, e);
            if (this.c) {
                var i, r = n.split("."),
                    s = +B.groupSize,
                    o = +B.secondaryGroupSize,
                    a = B.groupSeparator,
                    l = r[0],
                    h = r[1],
                    u = this.s < 0,
                    c = u ? l.slice(1) : l,
                    d = c.length;
                if (o && (i = s, s = o, o = i, d -= i), s > 0 && d > 0) {
                    for (i = d % s || s, l = c.substr(0, i); i < d; i += s) l += a + c.substr(i, s);
                    o > 0 && (l += a + c.slice(i)), u && (l = "-" + l)
                }
                n = h ? l + B.decimalSeparator + ((o = +B.fractionGroupSize) ? h.replace(new RegExp("\\d{" + o + "}\\B", "g"), "$&" + B.fractionGroupSeparator) : h) : l
            }
            return n
        }, O.toFraction = function(t) {
            var e, n, r, s, o, a, l, h, c, d, f, g, m = this,
                y = m.c;
            if (null != t && (h = new u(t), !h.isInteger() && (h.c || 1 !== h.s) || h.lt(M))) throw Error(p + "Argument " + (h.isInteger() ? "out of range: " : "not an integer: ") + t);
            if (!y) return m.toString();
            for (n = new u(M), d = r = new u(M), s = c = new u(M), g = i(y), a = n.e = g.length - m.e - 1, n.c[0] = w[(l = a % v) < 0 ? v + l : l], t = !t || h.comparedTo(n) > 0 ? a > 0 ? n : d : h, l = N, N = 1 / 0, h = new u(g), c.c[0] = 0; f = E(h, n, 0, 1), o = r.plus(f.times(s)), 1 != o.comparedTo(t);) r = s, s = o, d = c.plus(f.times(o = d)), c = o, n = h.minus(f.times(o = n)), h = o;
            return o = E(t.minus(r), s, 0, 1), c = c.plus(o.times(d)), r = r.plus(o.times(s)), c.s = d.s = m.s, a *= 2, e = E(d, s, a, $).minus(m).abs().comparedTo(E(c, r, a, $).minus(m).abs()) < 1 ? [d.toString(), s.toString()] : [c.toString(), r.toString()], N = l, e
        }, O.toNumber = function() {
            return +this
        }, O.toPrecision = function(t, e) {
            return null != t && s(t, 1, b), x(this, t, e, 2)
        }, O.toString = function(t) {
            var e, n = this,
                r = n.s,
                o = n.e;
            return null === o ? r ? (e = "Infinity", r < 0 && (e = "-" + e)) : e = "NaN" : (e = i(n.c), null == t ? e = o <= P || o >= A ? l(e, o) : h(e, o, "0") : (s(t, 2, H.length, "Base"), e = k(h(e, o, "0"), 10, t, r, !0)), r < 0 && n.c[0] && (e = "-" + e)), e
        }, O.valueOf = O.toJSON = function() {
            var t, e = this,
                n = e.e;
            return null === n ? e.toString() : (t = i(e.c), t = n <= P || n >= A ? l(t, n) : h(t, n, "0"), e.s < 0 ? "-" + t : t)
        }, O._isBigNumber = !0, null != t && u.set(t), u
    }

    function n(t) {
        var e = 0 | t;
        return t > 0 || t === e ? e : e - 1
    }

    function i(t) {
        for (var e, n, i = 1, r = t.length, s = t[0] + ""; i < r;) {
            for (e = t[i++] + "", n = v - e.length; n--; e = "0" + e);
            s += e
        }
        for (r = s.length; 48 === s.charCodeAt(--r););
        return s.slice(0, r + 1 || 1)
    }

    function r(t, e) {
        var n, i, r = t.c,
            s = e.c,
            o = t.s,
            a = e.s,
            l = t.e,
            h = e.e;
        if (!o || !a) return null;
        if (n = r && !r[0], i = s && !s[0], n || i) return n ? i ? 0 : -a : o;
        if (o != a) return o;
        if (n = o < 0, i = l == h, !r || !s) return i ? 0 : !r ^ n ? 1 : -1;
        if (!i) return l > h ^ n ? 1 : -1;
        for (a = (l = r.length) < (h = s.length) ? l : h, o = 0; o < a; o++)
            if (r[o] != s[o]) return r[o] > s[o] ^ n ? 1 : -1;
        return l == h ? 0 : l > h ^ n ? 1 : -1
    }

    function s(t, e, n, i) {
        if (t < e || t > n || t !== (t < 0 ? d(t) : f(t))) throw Error(p + (i || "Argument") + ("number" == typeof t ? t < e || t > n ? " out of range: " : " not an integer: " : " not a primitive number: ") + t)
    }

    function o(t) {
        return "[object Array]" == Object.prototype.toString.call(t)
    }

    function a(t) {
        var e = t.c.length - 1;
        return n(t.e / v) == e && t.c[e] % 2 != 0
    }

    function l(t, e) {
        return (t.length > 1 ? t.charAt(0) + "." + t.slice(1) : t) + (e < 0 ? "e" : "e+") + e
    }

    function h(t, e, n) {
        var i, r;
        if (e < 0) {
            for (r = n + "."; ++e; r += n);
            t = r + t
        } else if (i = t.length, ++e > i) {
            for (r = n, e -= i; --e; r += n);
            t += r
        } else e < i && (t = t.slice(0, e) + "." + t.slice(e));
        return t
    }
    var u, c = /^-?(?:\d+(?:\.\d*)?|\.\d+)(?:e[+-]?\d+)?$/i,
        d = Math.ceil,
        f = Math.floor,
        p = "[BigNumber Error] ",
        g = p + "Number primitive has more than 15 significant digits: ",
        m = 1e14,
        v = 14,
        y = 9007199254740991,
        w = [1, 10, 100, 1e3, 1e4, 1e5, 1e6, 1e7, 1e8, 1e9, 1e10, 1e11, 1e12, 1e13],
        _ = 1e7,
        b = 1e9;
    u = e(), u["default"] = u.BigNumber = u, "function" == typeof define && define.amd ? define(function() {
        return u
    }) : "undefined" != typeof module && module.exports ? module.exports = u : (t || (t = "undefined" != typeof self && self ? self : window), t.BigNumber = u)
}(this);