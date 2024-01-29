function querySt(n) {
    let i = window.location.search.substring(1),
        t = i.split("&");
    for (let i = 0; i < t.length; i++) {
        let r = t[i].split("=");
        if (r[0] === n) return r[1]
    }
}! function (n, t) {
    "use strict";
    "object" == typeof module && "object" == typeof module.exports ? module.exports = n.document ? t(n, !0) : function (n) {
        if (!n.document) throw new Error("jQuery requires a window with a document");
        return t(n)
    } : t(n)
}("undefined" != typeof window ? window : this, function (n, t) {
    "use strict";

    function kr(n, t, i) {
        var r, e, u = (i = i || f).createElement("script");
        if (u.text = n, t)
            for (r in oe)(e = t[r] || t.getAttribute && t.getAttribute(r)) && u.setAttribute(r, e);
        i.head.appendChild(u).parentNode.removeChild(u)
    }

    function ut(n) {
        return null == n ? n + "" : "object" == typeof n || "function" == typeof n ? ri[wr.call(n)] || "object" : typeof n
    }

    function pi(n) {
        var t = !!n && "length" in n && n.length,
            i = ut(n);
        return !u(n) && !rt(n) && ("array" === i || 0 === t || "number" == typeof t && 0 < t && t - 1 in n)
    }

    function c(n, t) {
        return n.nodeName && n.nodeName.toLowerCase() === t.toLowerCase()
    }

    function bi(n, t, r) {
        return u(t) ? i.grep(n, function (n, i) {
            return !!t.call(n, i, n) !== r
        }) : t.nodeType ? i.grep(n, function (n) {
            return n === t !== r
        }) : "string" != typeof t ? i.grep(n, function (n) {
            return -1 < ii.call(t, n) !== r
        }) : i.filter(t, n, r)
    }

    function fu(n, t) {
        while ((n = n[t]) && 1 !== n.nodeType);
        return n
    }

    function et(n) {
        return n
    }

    function fi(n) {
        throw n;
    }

    function eu(n, t, i, r) {
        var f;
        try {
            n && u(f = n.promise) ? f.call(n).done(t).fail(i) : n && u(f = n.then) ? f.call(n, t, i) : t.apply(void 0, [n].slice(r))
        } catch (n) {
            i.apply(void 0, [n])
        }
    }

    function oi() {
        f.removeEventListener("DOMContentLoaded", oi);
        n.removeEventListener("load", oi);
        i.ready()
    }

    function ce(n, t) {
        return t.toUpperCase()
    }

    function y(n) {
        return n.replace(se, "ms-").replace(he, ce)
    }

    function bt() {
        this.expando = i.expando + bt.uid++
    }

    function su(n, t, i) {
        var u, r;
        if (void 0 === i && 1 === n.nodeType)
            if (u = "data-" + t.replace(ae, "-$&").toLowerCase(), "string" == typeof (i = n.getAttribute(u))) {
                try {
                    i = "true" === (r = i) || "false" !== r && ("null" === r ? null : r === +r + "" ? +r : le.test(r) ? JSON.parse(r) : r)
                } catch (n) {}
                o.set(n, t, i)
            } else i = void 0;
        return i
    }

    function cu(n, t, r, u) {
        var s, h, c = 20,
            l = u ? function () {
                return u.cur()
            } : function () {
                return i.css(n, t, "")
            },
            o = l(),
            e = r && r[3] || (i.cssNumber[t] ? "" : "px"),
            f = n.nodeType && (i.cssNumber[t] || "px" !== e && +o) && kt.exec(i.css(n, t));
        if (f && f[3] !== e) {
            for (o /= 2, e = e || f[3], f = +o || 1; c--;) i.style(n, t, f + e), (1 - h) * (1 - (h = l() / o || .5)) <= 0 && (c = 0), f /= h;
            f *= 2;
            i.style(n, t, f + e);
            r = r || []
        }
        return r && (f = +f || +o || 0, s = r[1] ? f + (r[1] + 1) * r[2] : +r[2], u && (u.unit = e, u.start = f, u.end = s)), s
    }

    function ht(n, t) {
        for (var h, f, a, s, c, l, e, o = [], u = 0, v = n.length; u < v; u++)(f = n[u]).style && (h = f.style.display, t ? ("none" === h && (o[u] = r.get(f, "display") || null, o[u] || (f.style.display = "")), "" === f.style.display && dt(f) && (o[u] = (e = c = s = void 0, c = (a = f).ownerDocument, l = a.nodeName, (e = ki[l]) || (s = c.body.appendChild(c.createElement(l)), e = i.css(s, "display"), s.parentNode.removeChild(s), "none" === e && (e = "block"), ki[l] = e)))) : "none" !== h && (o[u] = "none", r.set(f, "display", h)));
        for (u = 0; u < v; u++) null != o[u] && (n[u].style.display = o[u]);
        return n
    }

    function s(n, t) {
        var r;
        return r = "undefined" != typeof n.getElementsByTagName ? n.getElementsByTagName(t || "*") : "undefined" != typeof n.querySelectorAll ? n.querySelectorAll(t || "*") : [], void 0 === t || t && c(n, t) ? i.merge([n], r) : r
    }

    function di(n, t) {
        for (var i = 0, u = n.length; i < u; i++) r.set(n[i], "globalEval", !t || r.get(t[i], "globalEval"))
    }

    function yu(n, t, r, u, f) {
        for (var e, o, p, a, w, v, c = t.createDocumentFragment(), y = [], l = 0, b = n.length; l < b; l++)
            if ((e = n[l]) || 0 === e)
                if ("object" === ut(e)) i.merge(y, e.nodeType ? [e] : e);
                else if (vu.test(e)) {
            for (o = o || c.appendChild(t.createElement("div")), p = (lu.exec(e) || ["", ""])[1].toLowerCase(), a = h[p] || h._default, o.innerHTML = a[1] + i.htmlPrefilter(e) + a[2], v = a[0]; v--;) o = o.lastChild;
            i.merge(y, o.childNodes);
            (o = c.firstChild).textContent = ""
        } else y.push(t.createTextNode(e));
        for (c.textContent = "", l = 0; e = y[l++];)
            if (u && -1 < i.inArray(e, u)) f && f.push(e);
            else if (w = st(e), o = s(c.appendChild(e), "script"), w && di(o), r)
            for (v = 0; e = o[v++];) au.test(e.type || "") && r.push(e);
        return c
    }

    function ct() {
        return !0
    }

    function lt() {
        return !1
    }

    function ye(n, t) {
        return n === function () {
            try {
                return f.activeElement
            } catch (n) {}
        }() == ("focus" === t)
    }

    function nr(n, t, r, u, f, e) {
        var o, s;
        if ("object" == typeof t) {
            for (s in "string" != typeof r && (u = u || r, r = void 0), t) nr(n, s, r, u, t[s], e);
            return n
        }
        if (null == u && null == f ? (f = r, u = r = void 0) : null == f && ("string" == typeof r ? (f = u, u = void 0) : (f = u, u = r, r = void 0)), !1 === f) f = lt;
        else if (!f) return n;
        return 1 === e && (o = f, (f = function (n) {
            return i().off(n), o.apply(this, arguments)
        }).guid = o.guid || (o.guid = i.guid++)), n.each(function () {
            i.event.add(this, t, f, u, r)
        })
    }

    function hi(n, t, u) {
        u ? (r.set(n, t, !1), i.event.add(n, t, {
            namespace: !1,
            handler: function (n) {
                var o, e, f = r.get(this, t);
                if (1 & n.isTrigger && this[t]) {
                    if (f.length)(i.event.special[t] || {}).delegateType && n.stopPropagation();
                    else if (f = k.call(arguments), r.set(this, t, f), o = u(this, t), this[t](), f !== (e = r.get(this, t)) || o ? r.set(this, t, !1) : e = {}, f !== e) return n.stopImmediatePropagation(), n.preventDefault(), e && e.value
                } else f.length && (r.set(this, t, {
                    value: i.event.trigger(i.extend(f[0], i.Event.prototype), f.slice(1), this)
                }), n.stopImmediatePropagation())
            }
        })) : void 0 === r.get(n, t) && i.event.add(n, t, ct)
    }

    function pu(n, t) {
        return c(n, "table") && c(11 !== t.nodeType ? t : t.firstChild, "tr") && i(n).children("tbody")[0] || n
    }

    function ke(n) {
        return n.type = (null !== n.getAttribute("type")) + "/" + n.type, n
    }

    function de(n) {
        return "true/" === (n.type || "").slice(0, 5) ? n.type = n.type.slice(5) : n.removeAttribute("type"), n
    }

    function wu(n, t) {
        var u, s, f, h, c, e;
        if (1 === t.nodeType) {
            if (r.hasData(n) && (e = r.get(n).events))
                for (f in r.remove(t, "handle events"), e)
                    for (u = 0, s = e[f].length; u < s; u++) i.event.add(t, f, e[f][u]);
            o.hasData(n) && (h = o.access(n), c = i.extend({}, h), o.set(t, c))
        }
    }

    function at(n, t, f, o) {
        t = pr(t);
        var a, b, l, v, h, y, c = 0,
            p = n.length,
            d = p - 1,
            w = t[0],
            k = u(w);
        if (k || 1 < p && "string" == typeof w && !e.checkClone && we.test(w)) return n.each(function (i) {
            var r = n.eq(i);
            k && (t[0] = w.call(this, i, r.html()));
            at(r, t, f, o)
        });
        if (p && (b = (a = yu(t, n[0].ownerDocument, !1, n, o)).firstChild, 1 === a.childNodes.length && (a = b), b || o)) {
            for (v = (l = i.map(s(a, "script"), ke)).length; c < p; c++) h = a, c !== d && (h = i.clone(h, !0, !0), v && i.merge(l, s(h, "script"))), f.call(n[c], h, c);
            if (v)
                for (y = l[l.length - 1].ownerDocument, i.map(l, de), c = 0; c < v; c++) h = l[c], au.test(h.type || "") && !r.access(h, "globalEval") && i.contains(y, h) && (h.src && "module" !== (h.type || "").toLowerCase() ? i._evalUrl && !h.noModule && i._evalUrl(h.src, {
                    nonce: h.nonce || h.getAttribute("nonce")
                }, y) : kr(h.textContent.replace(be, ""), h, y))
        }
        return n
    }

    function bu(n, t, r) {
        for (var u, e = t ? i.filter(t, n) : n, f = 0; null != (u = e[f]); f++) r || 1 !== u.nodeType || i.cleanData(s(u)), u.parentNode && (r && st(u) && di(s(u, "script")), u.parentNode.removeChild(u));
        return n
    }

    function ni(n, t, r) {
        var o, s, h, f, u = n.style;
        return (r = r || ci(n)) && ("" !== (f = r.getPropertyValue(t) || r[t]) || st(n) || (f = i.style(n, t)), !e.pixelBoxStyles() && tr.test(f) && ge.test(t) && (o = u.width, s = u.minWidth, h = u.maxWidth, u.minWidth = u.maxWidth = u.width = f, f = r.width, u.width = o, u.minWidth = s, u.maxWidth = h)), void 0 !== f ? f + "" : f
    }

    function du(n, t) {
        return {
            get: function () {
                if (!n()) return (this.get = t).apply(this, arguments);
                delete this.get
            }
        }
    }

    function ir(n) {
        var t = i.cssProps[n] || tf[n];
        return t || (n in nf ? n : tf[n] = function (n) {
            for (var i = n[0].toUpperCase() + n.slice(1), t = gu.length; t--;)
                if ((n = gu[t] + i) in nf) return n
        }(n) || n)
    }

    function ff(n, t, i) {
        var r = kt.exec(t);
        return r ? Math.max(0, r[2] - (i || 0)) + (r[3] || "px") : t
    }

    function rr(n, t, r, u, f, e) {
        var o = "width" === t ? 1 : 0,
            h = 0,
            s = 0;
        if (r === (u ? "border" : "content")) return 0;
        for (; o < 4; o += 2) "margin" === r && (s += i.css(n, r + b[o], !0, f)), u ? ("content" === r && (s -= i.css(n, "padding" + b[o], !0, f)), "margin" !== r && (s -= i.css(n, "border" + b[o] + "Width", !0, f))) : (s += i.css(n, "padding" + b[o], !0, f), "padding" !== r ? s += i.css(n, "border" + b[o] + "Width", !0, f) : h += i.css(n, "border" + b[o] + "Width", !0, f));
        return !u && 0 <= e && (s += Math.max(0, Math.ceil(n["offset" + t[0].toUpperCase() + t.slice(1)] - e - s - h - .5)) || 0), s
    }

    function ef(n, t, r) {
        var f = ci(n),
            o = (!e.boxSizingReliable() || r) && "border-box" === i.css(n, "boxSizing", !1, f),
            s = o,
            u = ni(n, t, f),
            h = "offset" + t[0].toUpperCase() + t.slice(1);
        if (tr.test(u)) {
            if (!r) return u;
            u = "auto"
        }
        return (!e.boxSizingReliable() && o || !e.reliableTrDimensions() && c(n, "tr") || "auto" === u || !parseFloat(u) && "inline" === i.css(n, "display", !1, f)) && n.getClientRects().length && (o = "border-box" === i.css(n, "boxSizing", !1, f), (s = h in n) && (u = n[h])), (u = parseFloat(u) || 0) + rr(n, t, r || (o ? "border" : "content"), s, f, u) + "px"
    }

    function a(n, t, i, r, u) {
        return new a.prototype.init(n, t, i, r, u)
    }

    function ur() {
        li && (!1 === f.hidden && n.requestAnimationFrame ? n.requestAnimationFrame(ur) : n.setTimeout(ur, i.fx.interval), i.fx.tick())
    }

    function cf() {
        return n.setTimeout(function () {
            vt = void 0
        }), vt = Date.now()
    }

    function ai(n, t) {
        var u, r = 0,
            i = {
                height: n
            };
        for (t = t ? 1 : 0; r < 4; r += 2 - t) i["margin" + (u = b[r])] = i["padding" + u] = n;
        return t && (i.opacity = i.width = n), i
    }

    function lf(n, t, i) {
        for (var u, f = (v.tweeners[t] || []).concat(v.tweeners["*"]), r = 0, e = f.length; r < e; r++)
            if (u = f[r].call(i, t, n)) return u
    }

    function v(n, t, r) {
        var o, s, h = 0,
            a = v.prefilters.length,
            e = i.Deferred().always(function () {
                delete l.elem
            }),
            l = function () {
                if (s) return !1;
                for (var o = vt || cf(), t = Math.max(0, f.startTime + f.duration - o), i = 1 - (t / f.duration || 0), r = 0, u = f.tweens.length; r < u; r++) f.tweens[r].run(i);
                return e.notifyWith(n, [f, i, t]), i < 1 && u ? t : (u || e.notifyWith(n, [f, 1, 0]), e.resolveWith(n, [f]), !1)
            },
            f = e.promise({
                elem: n,
                props: i.extend({}, t),
                opts: i.extend(!0, {
                    specialEasing: {},
                    easing: i.easing._default
                }, r),
                originalProperties: t,
                originalOptions: r,
                startTime: vt || cf(),
                duration: r.duration,
                tweens: [],
                createTween: function (t, r) {
                    var u = i.Tween(n, f.opts, t, r, f.opts.specialEasing[t] || f.opts.easing);
                    return f.tweens.push(u), u
                },
                stop: function (t) {
                    var i = 0,
                        r = t ? f.tweens.length : 0;
                    if (s) return this;
                    for (s = !0; i < r; i++) f.tweens[i].run(1);
                    return t ? (e.notifyWith(n, [f, 1, 0]), e.resolveWith(n, [f, t])) : e.rejectWith(n, [f, t]), this
                }
            }),
            c = f.props;
        for (! function (n, t) {
                var r, f, e, u, o;
                for (r in n)
                    if (e = t[f = y(r)], u = n[r], Array.isArray(u) && (e = u[1], u = n[r] = u[0]), r !== f && (n[f] = u, delete n[r]), (o = i.cssHooks[f]) && "expand" in o)
                        for (r in u = o.expand(u), delete n[f], u) r in n || (n[r] = u[r], t[r] = e);
                    else t[f] = e
            }(c, f.opts.specialEasing); h < a; h++)
            if (o = v.prefilters[h].call(f, n, c, f.opts)) return u(o.stop) && (i._queueHooks(f.elem, f.opts.queue).stop = o.stop.bind(o)), o;
        return i.map(c, lf, f), u(f.opts.start) && f.opts.start.call(n, f), f.progress(f.opts.progress).done(f.opts.done, f.opts.complete).fail(f.opts.fail).always(f.opts.always), i.fx.timer(i.extend(l, {
            elem: n,
            anim: f,
            queue: f.opts.queue
        })), f
    }

    function tt(n) {
        return (n.match(l) || []).join(" ")
    }

    function it(n) {
        return n.getAttribute && n.getAttribute("class") || ""
    }

    function fr(n) {
        return Array.isArray(n) ? n : "string" == typeof n && n.match(l) || []
    }

    function hr(n, t, r, u) {
        var f;
        if (Array.isArray(t)) i.each(t, function (t, i) {
            r || io.test(n) ? u(n, i) : hr(n + "[" + ("object" == typeof i && null != i ? t : "") + "]", i, r, u)
        });
        else if (r || "object" !== ut(t)) u(n, t);
        else
            for (f in t) hr(n + "[" + f + "]", t[f], r, u)
    }

    function gf(n) {
        return function (t, i) {
            "string" != typeof t && (i = t, t = "*");
            var r, f = 0,
                e = t.toLowerCase().match(l) || [];
            if (u(i))
                while (r = e[f++]) "+" === r[0] ? (r = r.slice(1) || "*", (n[r] = n[r] || []).unshift(i)) : (n[r] = n[r] || []).push(i)
        }
    }

    function ne(n, t, r, u) {
        function e(s) {
            var h;
            return f[s] = !0, i.each(n[s] || [], function (n, i) {
                var s = i(t, r, u);
                return "string" != typeof s || o || f[s] ? o ? !(h = s) : void 0 : (t.dataTypes.unshift(s), e(s), !1)
            }), h
        }
        var f = {},
            o = n === cr;
        return e(t.dataTypes[0]) || !f["*"] && e("*")
    }

    function ar(n, t) {
        var r, u, f = i.ajaxSettings.flatOptions || {};
        for (r in t) void 0 !== t[r] && ((f[r] ? n : u || (u = {}))[r] = t[r]);
        return u && i.extend(!0, n, u), n
    }
    var p = [],
        yr = Object.getPrototypeOf,
        k = p.slice,
        pr = p.flat ? function (n) {
            return p.flat.call(n)
        } : function (n) {
            return p.concat.apply([], n)
        },
        yi = p.push,
        ii = p.indexOf,
        ri = {},
        wr = ri.toString,
        ui = ri.hasOwnProperty,
        br = ui.toString,
        ee = br.call(Object),
        e = {},
        u = function (n) {
            return "function" == typeof n && "number" != typeof n.nodeType && "function" != typeof n.item
        },
        rt = function (n) {
            return null != n && n === n.window
        },
        f = n.document,
        oe = {
            type: !0,
            src: !0,
            nonce: !0,
            noModule: !0
        },
        dr = "3.6.0",
        i = function (n, t) {
            return new i.fn.init(n, t)
        },
        d, wi, tu, iu, ru, uu, l, ou, ei, ot, dt, ki, h, vu, gi, vt, li, yt, of , sf, hf, af, pt, vf, yf, pf, er, or, te, wt, ie, vr, vi, re, ue, fe;
    i.fn = i.prototype = {
        jquery: dr,
        constructor: i,
        length: 0,
        toArray: function () {
            return k.call(this)
        },
        get: function (n) {
            return null == n ? k.call(this) : n < 0 ? this[n + this.length] : this[n]
        },
        pushStack: function (n) {
            var t = i.merge(this.constructor(), n);
            return t.prevObject = this, t
        },
        each: function (n) {
            return i.each(this, n)
        },
        map: function (n) {
            return this.pushStack(i.map(this, function (t, i) {
                return n.call(t, i, t)
            }))
        },
        slice: function () {
            return this.pushStack(k.apply(this, arguments))
        },
        first: function () {
            return this.eq(0)
        },
        last: function () {
            return this.eq(-1)
        },
        even: function () {
            return this.pushStack(i.grep(this, function (n, t) {
                return (t + 1) % 2
            }))
        },
        odd: function () {
            return this.pushStack(i.grep(this, function (n, t) {
                return t % 2
            }))
        },
        eq: function (n) {
            var i = this.length,
                t = +n + (n < 0 ? i : 0);
            return this.pushStack(0 <= t && t < i ? [this[t]] : [])
        },
        end: function () {
            return this.prevObject || this.constructor()
        },
        push: yi,
        sort: p.sort,
        splice: p.splice
    };
    i.extend = i.fn.extend = function () {
        var s, f, e, t, o, c, n = arguments[0] || {},
            r = 1,
            l = arguments.length,
            h = !1;
        for ("boolean" == typeof n && (h = n, n = arguments[r] || {}, r++), "object" == typeof n || u(n) || (n = {}), r === l && (n = this, r--); r < l; r++)
            if (null != (s = arguments[r]))
                for (f in s) t = s[f], "__proto__" !== f && n !== t && (h && t && (i.isPlainObject(t) || (o = Array.isArray(t))) ? (e = n[f], c = o && !Array.isArray(e) ? [] : o || i.isPlainObject(e) ? e : {}, o = !1, n[f] = i.extend(h, c, t)) : void 0 !== t && (n[f] = t));
        return n
    };
    i.extend({
        expando: "jQuery" + (dr + Math.random()).replace(/\D/g, ""),
        isReady: !0,
        error: function (n) {
            throw new Error(n);
        },
        noop: function () {},
        isPlainObject: function (n) {
            var t, i;
            return !(!n || "[object Object]" !== wr.call(n)) && (!(t = yr(n)) || "function" == typeof (i = ui.call(t, "constructor") && t.constructor) && br.call(i) === ee)
        },
        isEmptyObject: function (n) {
            for (var t in n) return !1;
            return !0
        },
        globalEval: function (n, t, i) {
            kr(n, {
                nonce: t && t.nonce
            }, i)
        },
        each: function (n, t) {
            var r, i = 0;
            if (pi(n)) {
                for (r = n.length; i < r; i++)
                    if (!1 === t.call(n[i], i, n[i])) break
            } else
                for (i in n)
                    if (!1 === t.call(n[i], i, n[i])) break;
            return n
        },
        makeArray: function (n, t) {
            var r = t || [];
            return null != n && (pi(Object(n)) ? i.merge(r, "string" == typeof n ? [n] : n) : yi.call(r, n)), r
        },
        inArray: function (n, t, i) {
            return null == t ? -1 : ii.call(t, n, i)
        },
        merge: function (n, t) {
            for (var u = +t.length, i = 0, r = n.length; i < u; i++) n[r++] = t[i];
            return n.length = r, n
        },
        grep: function (n, t, i) {
            for (var u = [], r = 0, f = n.length, e = !i; r < f; r++) !t(n[r], r) !== e && u.push(n[r]);
            return u
        },
        map: function (n, t, i) {
            var e, u, r = 0,
                f = [];
            if (pi(n))
                for (e = n.length; r < e; r++) null != (u = t(n[r], r, i)) && f.push(u);
            else
                for (r in n) null != (u = t(n[r], r, i)) && f.push(u);
            return pr(f)
        },
        guid: 1,
        support: e
    });
    "function" == typeof Symbol && (i.fn[Symbol.iterator] = p[Symbol.iterator]);
    i.each("Boolean Number String Function Array Date RegExp Object Error Symbol".split(" "), function (n, t) {
        ri["[object " + t + "]"] = t.toLowerCase()
    });
    d = function (n) {
        function u(n, t, r, u) {
            var s, y, c, l, p, w, d, v = t && t.ownerDocument,
                a = t ? t.nodeType : 9;
            if (r = r || [], "string" != typeof n || !n || 1 !== a && 9 !== a && 11 !== a) return r;
            if (!u && (b(t), t = t || i, h)) {
                if (11 !== a && (p = ar.exec(n)))
                    if (s = p[1]) {
                        if (9 === a) {
                            if (!(c = t.getElementById(s))) return r;
                            if (c.id === s) return r.push(c), r
                        } else if (v && (c = v.getElementById(s)) && et(t, c) && c.id === s) return r.push(c), r
                    } else {
                        if (p[2]) return k.apply(r, t.getElementsByTagName(n)), r;
                        if ((s = p[3]) && f.getElementsByClassName && t.getElementsByClassName) return k.apply(r, t.getElementsByClassName(s)), r
                    } if (f.qsa && !lt[n + " "] && (!o || !o.test(n)) && (1 !== a || "object" !== t.nodeName.toLowerCase())) {
                    if (d = n, v = t, 1 === a && (er.test(n) || yi.test(n))) {
                        for ((v = ti.test(n) && ri(t.parentNode) || t) === t && f.scope || ((l = t.getAttribute("id")) ? l = l.replace(pi, wi) : t.setAttribute("id", l = e)), y = (w = ft(n)).length; y--;) w[y] = (l ? "#" + l : ":scope") + " " + pt(w[y]);
                        d = w.join(",")
                    }
                    try {
                        return k.apply(r, v.querySelectorAll(d)), r
                    } catch (t) {
                        lt(n, !0)
                    } finally {
                        l === e && t.removeAttribute("id")
                    }
                }
            }
            return si(n.replace(at, "$1"), t, r, u)
        }

        function yt() {
            var n = [];
            return function i(r, u) {
                return n.push(r + " ") > t.cacheLength && delete i[n.shift()], i[r + " "] = u
            }
        }

        function l(n) {
            return n[e] = !0, n
        }

        function a(n) {
            var t = i.createElement("fieldset");
            try {
                return !!n(t)
            } catch (n) {
                return !1
            } finally {
                t.parentNode && t.parentNode.removeChild(t);
                t = null
            }
        }

        function ii(n, i) {
            for (var r = n.split("|"), u = r.length; u--;) t.attrHandle[r[u]] = i
        }

        function ki(n, t) {
            var i = t && n,
                r = i && 1 === n.nodeType && 1 === t.nodeType && n.sourceIndex - t.sourceIndex;
            if (r) return r;
            if (i)
                while (i = i.nextSibling)
                    if (i === t) return -1;
            return n ? 1 : -1
        }

        function yr(n) {
            return function (t) {
                return "input" === t.nodeName.toLowerCase() && t.type === n
            }
        }

        function pr(n) {
            return function (t) {
                var i = t.nodeName.toLowerCase();
                return ("input" === i || "button" === i) && t.type === n
            }
        }

        function di(n) {
            return function (t) {
                return "form" in t ? t.parentNode && !1 === t.disabled ? "label" in t ? "label" in t.parentNode ? t.parentNode.disabled === n : t.disabled === n : t.isDisabled === n || t.isDisabled !== !n && vr(t) === n : t.disabled === n : "label" in t && t.disabled === n
            }
        }

        function it(n) {
            return l(function (t) {
                return t = +t, l(function (i, r) {
                    for (var u, f = n([], i.length, t), e = f.length; e--;) i[u = f[e]] && (i[u] = !(r[u] = i[u]))
                })
            })
        }

        function ri(n) {
            return n && "undefined" != typeof n.getElementsByTagName && n
        }

        function gi() {}

        function pt(n) {
            for (var t = 0, r = n.length, i = ""; t < r; t++) i += n[t].value;
            return i
        }

        function wt(n, t, i) {
            var r = t.dir,
                u = t.next,
                f = u || r,
                o = i && "parentNode" === f,
                s = nr++;
            return t.first ? function (t, i, u) {
                while (t = t[r])
                    if (1 === t.nodeType || o) return n(t, i, u);
                return !1
            } : function (t, i, h) {
                var c, l, a, y = [v, s];
                if (h) {
                    while (t = t[r])
                        if ((1 === t.nodeType || o) && n(t, i, h)) return !0
                } else
                    while (t = t[r])
                        if (1 === t.nodeType || o)
                            if (l = (a = t[e] || (t[e] = {}))[t.uniqueID] || (a[t.uniqueID] = {}), u && u === t.nodeName.toLowerCase()) t = t[r] || t;
                            else {
                                if ((c = l[f]) && c[0] === v && c[1] === s) return y[2] = c[2];
                                if ((l[f] = y)[2] = n(t, i, h)) return !0
                            } return !1
            }
        }

        function ui(n) {
            return 1 < n.length ? function (t, i, r) {
                for (var u = n.length; u--;)
                    if (!n[u](t, i, r)) return !1;
                return !0
            } : n[0]
        }

        function bt(n, t, i, r, u) {
            for (var e, o = [], f = 0, s = n.length, h = null != t; f < s; f++)(e = n[f]) && (i && !i(e, r, u) || (o.push(e), h && t.push(f)));
            return o
        }

        function fi(n, t, i, r, f, o) {
            return r && !r[e] && (r = fi(r)), f && !f[e] && (f = fi(f, o)), l(function (e, o, s, h) {
                var a, l, v, w = [],
                    p = [],
                    b = o.length,
                    d = e || function (n, t, i) {
                        for (var r = 0, f = t.length; r < f; r++) u(n, t[r], i);
                        return i
                    }(t || "*", s.nodeType ? [s] : s, []),
                    y = !n || !e && t ? d : bt(d, w, n, s, h),
                    c = i ? f || (e ? n : b || r) ? [] : o : y;
                if (i && i(y, c, s, h), r)
                    for (a = bt(c, p), r(a, [], s, h), l = a.length; l--;)(v = a[l]) && (c[p[l]] = !(y[p[l]] = v));
                if (e) {
                    if (f || n) {
                        if (f) {
                            for (a = [], l = c.length; l--;)(v = c[l]) && a.push(y[l] = v);
                            f(null, c = [], a, h)
                        }
                        for (l = c.length; l--;)(v = c[l]) && -1 < (a = f ? nt(e, v) : w[l]) && (e[a] = !(o[a] = v))
                    }
                } else c = bt(c === o ? c.splice(b, c.length) : c), f ? f(null, o, c, h) : k.apply(o, c)
            })
        }

        function ei(n) {
            for (var o, u, r, s = n.length, h = t.relative[n[0].type], c = h || t.relative[" "], i = h ? 1 : 0, l = wt(function (n) {
                    return n === o
                }, c, !0), a = wt(function (n) {
                    return -1 < nt(o, n)
                }, c, !0), f = [function (n, t, i) {
                    var r = !h && (i || t !== ht) || ((o = t).nodeType ? l(n, t, i) : a(n, t, i));
                    return o = null, r
                }]; i < s; i++)
                if (u = t.relative[n[i].type]) f = [wt(ui(f), u)];
                else {
                    if ((u = t.filter[n[i].type].apply(null, n[i].matches))[e]) {
                        for (r = ++i; r < s; r++)
                            if (t.relative[n[r].type]) break;
                        return fi(1 < i && ui(f), 1 < i && pt(n.slice(0, i - 1).concat({
                            value: " " === n[i - 2].type ? "*" : ""
                        })).replace(at, "$1"), u, i < r && ei(n.slice(i, r)), r < s && ei(n = n.slice(r)), r < s && pt(n))
                    }
                    f.push(u)
                } return ui(f)
        }
        var rt, f, t, st, oi, ft, kt, si, ht, w, ut, b, i, s, h, o, d, ct, et, e = "sizzle" + 1 * new Date,
            c = n.document,
            v = 0,
            nr = 0,
            hi = yt(),
            ci = yt(),
            li = yt(),
            lt = yt(),
            dt = function (n, t) {
                return n === t && (ut = !0), 0
            },
            tr = {}.hasOwnProperty,
            g = [],
            ir = g.pop,
            rr = g.push,
            k = g.push,
            ai = g.slice,
            nt = function (n, t) {
                for (var i = 0, r = n.length; i < r; i++)
                    if (n[i] === t) return i;
                return -1
            },
            gt = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",
            r = "[\\x20\\t\\r\\n\\f]",
            tt = "(?:\\\\[\\da-fA-F]{1,6}" + r + "?|\\\\[^\\r\\n\\f]|[\\w-]|[^\0-\\x7f])+",
            vi = "\\[" + r + "*(" + tt + ")(?:" + r + "*([*^$|!~]?=)" + r + "*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|(" + tt + "))|)" + r + "*\\]",
            ni = ":(" + tt + ")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|" + vi + ")*)|.*)\\)|)",
            ur = new RegExp(r + "+", "g"),
            at = new RegExp("^" + r + "+|((?:^|[^\\\\])(?:\\\\.)*)" + r + "+$", "g"),
            fr = new RegExp("^" + r + "*," + r + "*"),
            yi = new RegExp("^" + r + "*([>+~]|" + r + ")" + r + "*"),
            er = new RegExp(r + "|>"),
            or = new RegExp(ni),
            sr = new RegExp("^" + tt + "$"),
            vt = {
                ID: new RegExp("^#(" + tt + ")"),
                CLASS: new RegExp("^\\.(" + tt + ")"),
                TAG: new RegExp("^(" + tt + "|[*])"),
                ATTR: new RegExp("^" + vi),
                PSEUDO: new RegExp("^" + ni),
                CHILD: new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + r + "*(even|odd|(([+-]|)(\\d*)n|)" + r + "*(?:([+-]|)" + r + "*(\\d+)|))" + r + "*\\)|)", "i"),
                bool: new RegExp("^(?:" + gt + ")$", "i"),
                needsContext: new RegExp("^" + r + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + r + "*((?:-\\d)?\\d*)" + r + "*\\)|)(?=[^-]|$)", "i")
            },
            hr = /HTML$/i,
            cr = /^(?:input|select|textarea|button)$/i,
            lr = /^h\d$/i,
            ot = /^[^{]+\{\s*\[native \w/,
            ar = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,
            ti = /[+~]/,
            y = new RegExp("\\\\[\\da-fA-F]{1,6}" + r + "?|\\\\([^\\r\\n\\f])", "g"),
            p = function (n, t) {
                var i = "0x" + n.slice(1) - 65536;
                return t || (i < 0 ? String.fromCharCode(i + 65536) : String.fromCharCode(i >> 10 | 55296, 1023 & i | 56320))
            },
            pi = /([\0-\x1f\x7f]|^-?\d)|^-$|[^\0-\x1f\x7f-\uFFFF\w-]/g,
            wi = function (n, t) {
                return t ? "\0" === n ? "�" : n.slice(0, -1) + "\\" + n.charCodeAt(n.length - 1).toString(16) + " " : "\\" + n
            },
            bi = function () {
                b()
            },
            vr = wt(function (n) {
                return !0 === n.disabled && "fieldset" === n.nodeName.toLowerCase()
            }, {
                dir: "parentNode",
                next: "legend"
            });
        try {
            k.apply(g = ai.call(c.childNodes), c.childNodes);
            g[c.childNodes.length].nodeType
        } catch (rt) {
            k = {
                apply: g.length ? function (n, t) {
                    rr.apply(n, ai.call(t))
                } : function (n, t) {
                    for (var i = n.length, r = 0; n[i++] = t[r++];);
                    n.length = i - 1
                }
            }
        }
        for (rt in f = u.support = {}, oi = u.isXML = function (n) {
                var i = n && n.namespaceURI,
                    t = n && (n.ownerDocument || n).documentElement;
                return !hr.test(i || t && t.nodeName || "HTML")
            }, b = u.setDocument = function (n) {
                var v, u, l = n ? n.ownerDocument || n : c;
                return l != i && 9 === l.nodeType && l.documentElement && (s = (i = l).documentElement, h = !oi(i), c != i && (u = i.defaultView) && u.top !== u && (u.addEventListener ? u.addEventListener("unload", bi, !1) : u.attachEvent && u.attachEvent("onunload", bi)), f.scope = a(function (n) {
                    return s.appendChild(n).appendChild(i.createElement("div")), "undefined" != typeof n.querySelectorAll && !n.querySelectorAll(":scope fieldset div").length
                }), f.attributes = a(function (n) {
                    return n.className = "i", !n.getAttribute("className")
                }), f.getElementsByTagName = a(function (n) {
                    return n.appendChild(i.createComment("")), !n.getElementsByTagName("*").length
                }), f.getElementsByClassName = ot.test(i.getElementsByClassName), f.getById = a(function (n) {
                    return s.appendChild(n).id = e, !i.getElementsByName || !i.getElementsByName(e).length
                }), f.getById ? (t.filter.ID = function (n) {
                    var t = n.replace(y, p);
                    return function (n) {
                        return n.getAttribute("id") === t
                    }
                }, t.find.ID = function (n, t) {
                    if ("undefined" != typeof t.getElementById && h) {
                        var i = t.getElementById(n);
                        return i ? [i] : []
                    }
                }) : (t.filter.ID = function (n) {
                    var t = n.replace(y, p);
                    return function (n) {
                        var i = "undefined" != typeof n.getAttributeNode && n.getAttributeNode("id");
                        return i && i.value === t
                    }
                }, t.find.ID = function (n, t) {
                    if ("undefined" != typeof t.getElementById && h) {
                        var r, u, f, i = t.getElementById(n);
                        if (i) {
                            if ((r = i.getAttributeNode("id")) && r.value === n) return [i];
                            for (f = t.getElementsByName(n), u = 0; i = f[u++];)
                                if ((r = i.getAttributeNode("id")) && r.value === n) return [i]
                        }
                        return []
                    }
                }), t.find.TAG = f.getElementsByTagName ? function (n, t) {
                    return "undefined" != typeof t.getElementsByTagName ? t.getElementsByTagName(n) : f.qsa ? t.querySelectorAll(n) : void 0
                } : function (n, t) {
                    var i, r = [],
                        f = 0,
                        u = t.getElementsByTagName(n);
                    if ("*" === n) {
                        while (i = u[f++]) 1 === i.nodeType && r.push(i);
                        return r
                    }
                    return u
                }, t.find.CLASS = f.getElementsByClassName && function (n, t) {
                    if ("undefined" != typeof t.getElementsByClassName && h) return t.getElementsByClassName(n)
                }, d = [], o = [], (f.qsa = ot.test(i.querySelectorAll)) && (a(function (n) {
                    var t;
                    s.appendChild(n).innerHTML = "<a id='" + e + "'><\/a><select id='" + e + "-\r\\' msallowcapture=''><option selected=''><\/option><\/select>";
                    n.querySelectorAll("[msallowcapture^='']").length && o.push("[*^$]=" + r + "*(?:''|\"\")");
                    n.querySelectorAll("[selected]").length || o.push("\\[" + r + "*(?:value|" + gt + ")");
                    n.querySelectorAll("[id~=" + e + "-]").length || o.push("~=");
                    (t = i.createElement("input")).setAttribute("name", "");
                    n.appendChild(t);
                    n.querySelectorAll("[name='']").length || o.push("\\[" + r + "*name" + r + "*=" + r + "*(?:''|\"\")");
                    n.querySelectorAll(":checked").length || o.push(":checked");
                    n.querySelectorAll("a#" + e + "+*").length || o.push(".#.+[+~]");
                    n.querySelectorAll("\\\f");
                    o.push("[\\r\\n\\f]")
                }), a(function (n) {
                    n.innerHTML = "<a href='' disabled='disabled'><\/a><select disabled='disabled'><option/><\/select>";
                    var t = i.createElement("input");
                    t.setAttribute("type", "hidden");
                    n.appendChild(t).setAttribute("name", "D");
                    n.querySelectorAll("[name=d]").length && o.push("name" + r + "*[*^$|!~]?=");
                    2 !== n.querySelectorAll(":enabled").length && o.push(":enabled", ":disabled");
                    s.appendChild(n).disabled = !0;
                    2 !== n.querySelectorAll(":disabled").length && o.push(":enabled", ":disabled");
                    n.querySelectorAll("*,:x");
                    o.push(",.*:")
                })), (f.matchesSelector = ot.test(ct = s.matches || s.webkitMatchesSelector || s.mozMatchesSelector || s.oMatchesSelector || s.msMatchesSelector)) && a(function (n) {
                    f.disconnectedMatch = ct.call(n, "*");
                    ct.call(n, "[s!='']:x");
                    d.push("!=", ni)
                }), o = o.length && new RegExp(o.join("|")), d = d.length && new RegExp(d.join("|")), v = ot.test(s.compareDocumentPosition), et = v || ot.test(s.contains) ? function (n, t) {
                    var r = 9 === n.nodeType ? n.documentElement : n,
                        i = t && t.parentNode;
                    return n === i || !(!i || 1 !== i.nodeType || !(r.contains ? r.contains(i) : n.compareDocumentPosition && 16 & n.compareDocumentPosition(i)))
                } : function (n, t) {
                    if (t)
                        while (t = t.parentNode)
                            if (t === n) return !0;
                    return !1
                }, dt = v ? function (n, t) {
                    if (n === t) return ut = !0, 0;
                    var r = !n.compareDocumentPosition - !t.compareDocumentPosition;
                    return r || (1 & (r = (n.ownerDocument || n) == (t.ownerDocument || t) ? n.compareDocumentPosition(t) : 1) || !f.sortDetached && t.compareDocumentPosition(n) === r ? n == i || n.ownerDocument == c && et(c, n) ? -1 : t == i || t.ownerDocument == c && et(c, t) ? 1 : w ? nt(w, n) - nt(w, t) : 0 : 4 & r ? -1 : 1)
                } : function (n, t) {
                    if (n === t) return ut = !0, 0;
                    var r, u = 0,
                        o = n.parentNode,
                        s = t.parentNode,
                        f = [n],
                        e = [t];
                    if (!o || !s) return n == i ? -1 : t == i ? 1 : o ? -1 : s ? 1 : w ? nt(w, n) - nt(w, t) : 0;
                    if (o === s) return ki(n, t);
                    for (r = n; r = r.parentNode;) f.unshift(r);
                    for (r = t; r = r.parentNode;) e.unshift(r);
                    while (f[u] === e[u]) u++;
                    return u ? ki(f[u], e[u]) : f[u] == c ? -1 : e[u] == c ? 1 : 0
                }), i
            }, u.matches = function (n, t) {
                return u(n, null, null, t)
            }, u.matchesSelector = function (n, t) {
                if (b(n), f.matchesSelector && h && !lt[t + " "] && (!d || !d.test(t)) && (!o || !o.test(t))) try {
                    var r = ct.call(n, t);
                    if (r || f.disconnectedMatch || n.document && 11 !== n.document.nodeType) return r
                } catch (n) {
                    lt(t, !0)
                }
                return 0 < u(t, i, null, [n]).length
            }, u.contains = function (n, t) {
                return (n.ownerDocument || n) != i && b(n), et(n, t)
            }, u.attr = function (n, r) {
                (n.ownerDocument || n) != i && b(n);
                var e = t.attrHandle[r.toLowerCase()],
                    u = e && tr.call(t.attrHandle, r.toLowerCase()) ? e(n, r, !h) : void 0;
                return void 0 !== u ? u : f.attributes || !h ? n.getAttribute(r) : (u = n.getAttributeNode(r)) && u.specified ? u.value : null
            }, u.escape = function (n) {
                return (n + "").replace(pi, wi)
            }, u.error = function (n) {
                throw new Error("Syntax error, unrecognized expression: " + n);
            }, u.uniqueSort = function (n) {
                var r, u = [],
                    t = 0,
                    i = 0;
                if (ut = !f.detectDuplicates, w = !f.sortStable && n.slice(0), n.sort(dt), ut) {
                    while (r = n[i++]) r === n[i] && (t = u.push(i));
                    while (t--) n.splice(u[t], 1)
                }
                return w = null, n
            }, st = u.getText = function (n) {
                var r, i = "",
                    u = 0,
                    t = n.nodeType;
                if (t) {
                    if (1 === t || 9 === t || 11 === t) {
                        if ("string" == typeof n.textContent) return n.textContent;
                        for (n = n.firstChild; n; n = n.nextSibling) i += st(n)
                    } else if (3 === t || 4 === t) return n.nodeValue
                } else
                    while (r = n[u++]) i += st(r);
                return i
            }, (t = u.selectors = {
                cacheLength: 50,
                createPseudo: l,
                match: vt,
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
                    ATTR: function (n) {
                        return n[1] = n[1].replace(y, p), n[3] = (n[3] || n[4] || n[5] || "").replace(y, p), "~=" === n[2] && (n[3] = " " + n[3] + " "), n.slice(0, 4)
                    },
                    CHILD: function (n) {
                        return n[1] = n[1].toLowerCase(), "nth" === n[1].slice(0, 3) ? (n[3] || u.error(n[0]), n[4] = +(n[4] ? n[5] + (n[6] || 1) : 2 * ("even" === n[3] || "odd" === n[3])), n[5] = +(n[7] + n[8] || "odd" === n[3])) : n[3] && u.error(n[0]), n
                    },
                    PSEUDO: function (n) {
                        var i, t = !n[6] && n[2];
                        return vt.CHILD.test(n[0]) ? null : (n[3] ? n[2] = n[4] || n[5] || "" : t && or.test(t) && (i = ft(t, !0)) && (i = t.indexOf(")", t.length - i) - t.length) && (n[0] = n[0].slice(0, i), n[2] = t.slice(0, i)), n.slice(0, 3))
                    }
                },
                filter: {
                    TAG: function (n) {
                        var t = n.replace(y, p).toLowerCase();
                        return "*" === n ? function () {
                            return !0
                        } : function (n) {
                            return n.nodeName && n.nodeName.toLowerCase() === t
                        }
                    },
                    CLASS: function (n) {
                        var t = hi[n + " "];
                        return t || (t = new RegExp("(^|" + r + ")" + n + "(" + r + "|$)")) && hi(n, function (n) {
                            return t.test("string" == typeof n.className && n.className || "undefined" != typeof n.getAttribute && n.getAttribute("class") || "")
                        })
                    },
                    ATTR: function (n, t, i) {
                        return function (r) {
                            var f = u.attr(r, n);
                            return null == f ? "!=" === t : !t || (f += "", "=" === t ? f === i : "!=" === t ? f !== i : "^=" === t ? i && 0 === f.indexOf(i) : "*=" === t ? i && -1 < f.indexOf(i) : "$=" === t ? i && f.slice(-i.length) === i : "~=" === t ? -1 < (" " + f.replace(ur, " ") + " ").indexOf(i) : "|=" === t && (f === i || f.slice(0, i.length + 1) === i + "-"))
                        }
                    },
                    CHILD: function (n, t, i, r, u) {
                        var s = "nth" !== n.slice(0, 3),
                            o = "last" !== n.slice(-4),
                            f = "of-type" === t;
                        return 1 === r && 0 === u ? function (n) {
                            return !!n.parentNode
                        } : function (t, i, h) {
                            var p, d, y, c, a, w, b = s !== o ? "nextSibling" : "previousSibling",
                                k = t.parentNode,
                                nt = f && t.nodeName.toLowerCase(),
                                g = !h && !f,
                                l = !1;
                            if (k) {
                                if (s) {
                                    while (b) {
                                        for (c = t; c = c[b];)
                                            if (f ? c.nodeName.toLowerCase() === nt : 1 === c.nodeType) return !1;
                                        w = b = "only" === n && !w && "nextSibling"
                                    }
                                    return !0
                                }
                                if (w = [o ? k.firstChild : k.lastChild], o && g) {
                                    for (l = (a = (p = (d = (y = (c = k)[e] || (c[e] = {}))[c.uniqueID] || (y[c.uniqueID] = {}))[n] || [])[0] === v && p[1]) && p[2], c = a && k.childNodes[a]; c = ++a && c && c[b] || (l = a = 0) || w.pop();)
                                        if (1 === c.nodeType && ++l && c === t) {
                                            d[n] = [v, a, l];
                                            break
                                        }
                                } else if (g && (l = a = (p = (d = (y = (c = t)[e] || (c[e] = {}))[c.uniqueID] || (y[c.uniqueID] = {}))[n] || [])[0] === v && p[1]), !1 === l)
                                    while (c = ++a && c && c[b] || (l = a = 0) || w.pop())
                                        if ((f ? c.nodeName.toLowerCase() === nt : 1 === c.nodeType) && ++l && (g && ((d = (y = c[e] || (c[e] = {}))[c.uniqueID] || (y[c.uniqueID] = {}))[n] = [v, l]), c === t)) break;
                                return (l -= u) === r || l % r == 0 && 0 <= l / r
                            }
                        }
                    },
                    PSEUDO: function (n, i) {
                        var f, r = t.pseudos[n] || t.setFilters[n.toLowerCase()] || u.error("unsupported pseudo: " + n);
                        return r[e] ? r(i) : 1 < r.length ? (f = [n, n, "", i], t.setFilters.hasOwnProperty(n.toLowerCase()) ? l(function (n, t) {
                            for (var e, u = r(n, i), f = u.length; f--;) n[e = nt(n, u[f])] = !(t[e] = u[f])
                        }) : function (n) {
                            return r(n, 0, f)
                        }) : r
                    }
                },
                pseudos: {
                    not: l(function (n) {
                        var t = [],
                            r = [],
                            i = kt(n.replace(at, "$1"));
                        return i[e] ? l(function (n, t, r, u) {
                            for (var e, o = i(n, null, u, []), f = n.length; f--;)(e = o[f]) && (n[f] = !(t[f] = e))
                        }) : function (n, u, f) {
                            return t[0] = n, i(t, null, f, r), t[0] = null, !r.pop()
                        }
                    }),
                    has: l(function (n) {
                        return function (t) {
                            return 0 < u(n, t).length
                        }
                    }),
                    contains: l(function (n) {
                        return n = n.replace(y, p),
                            function (t) {
                                return -1 < (t.textContent || st(t)).indexOf(n)
                            }
                    }),
                    lang: l(function (n) {
                        return sr.test(n || "") || u.error("unsupported lang: " + n), n = n.replace(y, p).toLowerCase(),
                            function (t) {
                                var i;
                                do
                                    if (i = h ? t.lang : t.getAttribute("xml:lang") || t.getAttribute("lang")) return (i = i.toLowerCase()) === n || 0 === i.indexOf(n + "-"); while ((t = t.parentNode) && 1 === t.nodeType);
                                return !1
                            }
                    }),
                    target: function (t) {
                        var i = n.location && n.location.hash;
                        return i && i.slice(1) === t.id
                    },
                    root: function (n) {
                        return n === s
                    },
                    focus: function (n) {
                        return n === i.activeElement && (!i.hasFocus || i.hasFocus()) && !!(n.type || n.href || ~n.tabIndex)
                    },
                    enabled: di(!1),
                    disabled: di(!0),
                    checked: function (n) {
                        var t = n.nodeName.toLowerCase();
                        return "input" === t && !!n.checked || "option" === t && !!n.selected
                    },
                    selected: function (n) {
                        return n.parentNode && n.parentNode.selectedIndex, !0 === n.selected
                    },
                    empty: function (n) {
                        for (n = n.firstChild; n; n = n.nextSibling)
                            if (n.nodeType < 6) return !1;
                        return !0
                    },
                    parent: function (n) {
                        return !t.pseudos.empty(n)
                    },
                    header: function (n) {
                        return lr.test(n.nodeName)
                    },
                    input: function (n) {
                        return cr.test(n.nodeName)
                    },
                    button: function (n) {
                        var t = n.nodeName.toLowerCase();
                        return "input" === t && "button" === n.type || "button" === t
                    },
                    text: function (n) {
                        var t;
                        return "input" === n.nodeName.toLowerCase() && "text" === n.type && (null == (t = n.getAttribute("type")) || "text" === t.toLowerCase())
                    },
                    first: it(function () {
                        return [0]
                    }),
                    last: it(function (n, t) {
                        return [t - 1]
                    }),
                    eq: it(function (n, t, i) {
                        return [i < 0 ? i + t : i]
                    }),
                    even: it(function (n, t) {
                        for (var i = 0; i < t; i += 2) n.push(i);
                        return n
                    }),
                    odd: it(function (n, t) {
                        for (var i = 1; i < t; i += 2) n.push(i);
                        return n
                    }),
                    lt: it(function (n, t, i) {
                        for (var r = i < 0 ? i + t : t < i ? t : i; 0 <= --r;) n.push(r);
                        return n
                    }),
                    gt: it(function (n, t, i) {
                        for (var r = i < 0 ? i + t : i; ++r < t;) n.push(r);
                        return n
                    })
                }
            }).pseudos.nth = t.pseudos.eq, {
                radio: !0,
                checkbox: !0,
                file: !0,
                password: !0,
                image: !0
            }) t.pseudos[rt] = yr(rt);
        for (rt in {
                submit: !0,
                reset: !0
            }) t.pseudos[rt] = pr(rt);
        return gi.prototype = t.filters = t.pseudos, t.setFilters = new gi, ft = u.tokenize = function (n, i) {
            var e, f, s, o, r, h, c, l = ci[n + " "];
            if (l) return i ? 0 : l.slice(0);
            for (r = n, h = [], c = t.preFilter; r;) {
                for (o in e && !(f = fr.exec(r)) || (f && (r = r.slice(f[0].length) || r), h.push(s = [])), e = !1, (f = yi.exec(r)) && (e = f.shift(), s.push({
                        value: e,
                        type: f[0].replace(at, " ")
                    }), r = r.slice(e.length)), t.filter)(f = vt[o].exec(r)) && (!c[o] || (f = c[o](f))) && (e = f.shift(), s.push({
                    value: e,
                    type: o,
                    matches: f
                }), r = r.slice(e.length));
                if (!e) break
            }
            return i ? r.length : r ? u.error(n) : ci(n, h).slice(0)
        }, kt = u.compile = function (n, r) {
            var s, c, a, o, y, p, w = [],
                d = [],
                f = li[n + " "];
            if (!f) {
                for (r || (r = ft(n)), s = r.length; s--;)(f = ei(r[s]))[e] ? w.push(f) : d.push(f);
                (f = li(n, (c = d, o = 0 < (a = w).length, y = 0 < c.length, p = function (n, r, f, e, s) {
                    var l, nt, d, g = 0,
                        p = "0",
                        tt = n && [],
                        w = [],
                        it = ht,
                        rt = n || y && t.find.TAG("*", s),
                        ut = v += null == it ? 1 : Math.random() || .1,
                        ft = rt.length;
                    for (s && (ht = r == i || r || s); p !== ft && null != (l = rt[p]); p++) {
                        if (y && l) {
                            for (nt = 0, r || l.ownerDocument == i || (b(l), f = !h); d = c[nt++];)
                                if (d(l, r || i, f)) {
                                    e.push(l);
                                    break
                                } s && (v = ut)
                        }
                        o && ((l = !d && l) && g--, n && tt.push(l))
                    }
                    if (g += p, o && p !== g) {
                        for (nt = 0; d = a[nt++];) d(tt, w, r, f);
                        if (n) {
                            if (0 < g)
                                while (p--) tt[p] || w[p] || (w[p] = ir.call(e));
                            w = bt(w)
                        }
                        k.apply(e, w);
                        s && !n && 0 < w.length && 1 < g + a.length && u.uniqueSort(e)
                    }
                    return s && (v = ut, ht = it), tt
                }, o ? l(p) : p))).selector = n
            }
            return f
        }, si = u.select = function (n, i, r, u) {
            var o, f, e, l, a, c = "function" == typeof n && n,
                s = !u && ft(n = c.selector || n);
            if (r = r || [], 1 === s.length) {
                if (2 < (f = s[0] = s[0].slice(0)).length && "ID" === (e = f[0]).type && 9 === i.nodeType && h && t.relative[f[1].type]) {
                    if (!(i = (t.find.ID(e.matches[0].replace(y, p), i) || [])[0])) return r;
                    c && (i = i.parentNode);
                    n = n.slice(f.shift().value.length)
                }
                for (o = vt.needsContext.test(n) ? 0 : f.length; o--;) {
                    if (e = f[o], t.relative[l = e.type]) break;
                    if ((a = t.find[l]) && (u = a(e.matches[0].replace(y, p), ti.test(f[0].type) && ri(i.parentNode) || i))) {
                        if (f.splice(o, 1), !(n = u.length && pt(f))) return k.apply(r, u), r;
                        break
                    }
                }
            }
            return (c || kt(n, s))(u, i, !h, r, !i || ti.test(n) && ri(i.parentNode) || i), r
        }, f.sortStable = e.split("").sort(dt).join("") === e, f.detectDuplicates = !!ut, b(), f.sortDetached = a(function (n) {
            return 1 & n.compareDocumentPosition(i.createElement("fieldset"))
        }), a(function (n) {
            return n.innerHTML = "<a href='#'><\/a>", "#" === n.firstChild.getAttribute("href")
        }) || ii("type|href|height|width", function (n, t, i) {
            if (!i) return n.getAttribute(t, "type" === t.toLowerCase() ? 1 : 2)
        }), f.attributes && a(function (n) {
            return n.innerHTML = "<input/>", n.firstChild.setAttribute("value", ""), "" === n.firstChild.getAttribute("value")
        }) || ii("value", function (n, t, i) {
            if (!i && "input" === n.nodeName.toLowerCase()) return n.defaultValue
        }), a(function (n) {
            return null == n.getAttribute("disabled")
        }) || ii(gt, function (n, t, i) {
            var r;
            if (!i) return !0 === n[t] ? t.toLowerCase() : (r = n.getAttributeNode(t)) && r.specified ? r.value : null
        }), u
    }(n);
    i.find = d;
    i.expr = d.selectors;
    i.expr[":"] = i.expr.pseudos;
    i.uniqueSort = i.unique = d.uniqueSort;
    i.text = d.getText;
    i.isXMLDoc = d.isXML;
    i.contains = d.contains;
    i.escapeSelector = d.escape;
    var ft = function (n, t, r) {
            for (var u = [], f = void 0 !== r;
                (n = n[t]) && 9 !== n.nodeType;)
                if (1 === n.nodeType) {
                    if (f && i(n).is(r)) break;
                    u.push(n)
                } return u
        },
        gr = function (n, t) {
            for (var i = []; n; n = n.nextSibling) 1 === n.nodeType && n !== t && i.push(n);
            return i
        },
        nu = i.expr.match.needsContext;
    wi = /^<([a-z][^\/\0>:\x20\t\r\n\f]*)[\x20\t\r\n\f]*\/?>(?:<\/\1>|)$/i;
    i.filter = function (n, t, r) {
        var u = t[0];
        return r && (n = ":not(" + n + ")"), 1 === t.length && 1 === u.nodeType ? i.find.matchesSelector(u, n) ? [u] : [] : i.find.matches(n, i.grep(t, function (n) {
            return 1 === n.nodeType
        }))
    };
    i.fn.extend({
        find: function (n) {
            var t, r, u = this.length,
                f = this;
            if ("string" != typeof n) return this.pushStack(i(n).filter(function () {
                for (t = 0; t < u; t++)
                    if (i.contains(f[t], this)) return !0
            }));
            for (r = this.pushStack([]), t = 0; t < u; t++) i.find(n, f[t], r);
            return 1 < u ? i.uniqueSort(r) : r
        },
        filter: function (n) {
            return this.pushStack(bi(this, n || [], !1))
        },
        not: function (n) {
            return this.pushStack(bi(this, n || [], !0))
        },
        is: function (n) {
            return !!bi(this, "string" == typeof n && nu.test(n) ? i(n) : n || [], !1).length
        }
    });
    iu = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]+))$/;
    (i.fn.init = function (n, t, r) {
        var e, o;
        if (!n) return this;
        if (r = r || tu, "string" == typeof n) {
            if (!(e = "<" === n[0] && ">" === n[n.length - 1] && 3 <= n.length ? [null, n, null] : iu.exec(n)) || !e[1] && t) return !t || t.jquery ? (t || r).find(n) : this.constructor(t).find(n);
            if (e[1]) {
                if (t = t instanceof i ? t[0] : t, i.merge(this, i.parseHTML(e[1], t && t.nodeType ? t.ownerDocument || t : f, !0)), wi.test(e[1]) && i.isPlainObject(t))
                    for (e in t) u(this[e]) ? this[e](t[e]) : this.attr(e, t[e]);
                return this
            }
            return (o = f.getElementById(e[2])) && (this[0] = o, this.length = 1), this
        }
        return n.nodeType ? (this[0] = n, this.length = 1, this) : u(n) ? void 0 !== r.ready ? r.ready(n) : n(i) : i.makeArray(n, this)
    }).prototype = i.fn;
    tu = i(f);
    ru = /^(?:parents|prev(?:Until|All))/;
    uu = {
        children: !0,
        contents: !0,
        next: !0,
        prev: !0
    };
    i.fn.extend({
        has: function (n) {
            var t = i(n, this),
                r = t.length;
            return this.filter(function () {
                for (var n = 0; n < r; n++)
                    if (i.contains(this, t[n])) return !0
            })
        },
        closest: function (n, t) {
            var r, f = 0,
                o = this.length,
                u = [],
                e = "string" != typeof n && i(n);
            if (!nu.test(n))
                for (; f < o; f++)
                    for (r = this[f]; r && r !== t; r = r.parentNode)
                        if (r.nodeType < 11 && (e ? -1 < e.index(r) : 1 === r.nodeType && i.find.matchesSelector(r, n))) {
                            u.push(r);
                            break
                        } return this.pushStack(1 < u.length ? i.uniqueSort(u) : u)
        },
        index: function (n) {
            return n ? "string" == typeof n ? ii.call(i(n), this[0]) : ii.call(this, n.jquery ? n[0] : n) : this[0] && this[0].parentNode ? this.first().prevAll().length : -1
        },
        add: function (n, t) {
            return this.pushStack(i.uniqueSort(i.merge(this.get(), i(n, t))))
        },
        addBack: function (n) {
            return this.add(null == n ? this.prevObject : this.prevObject.filter(n))
        }
    });
    i.each({
        parent: function (n) {
            var t = n.parentNode;
            return t && 11 !== t.nodeType ? t : null
        },
        parents: function (n) {
            return ft(n, "parentNode")
        },
        parentsUntil: function (n, t, i) {
            return ft(n, "parentNode", i)
        },
        next: function (n) {
            return fu(n, "nextSibling")
        },
        prev: function (n) {
            return fu(n, "previousSibling")
        },
        nextAll: function (n) {
            return ft(n, "nextSibling")
        },
        prevAll: function (n) {
            return ft(n, "previousSibling")
        },
        nextUntil: function (n, t, i) {
            return ft(n, "nextSibling", i)
        },
        prevUntil: function (n, t, i) {
            return ft(n, "previousSibling", i)
        },
        siblings: function (n) {
            return gr((n.parentNode || {}).firstChild, n)
        },
        children: function (n) {
            return gr(n.firstChild)
        },
        contents: function (n) {
            return null != n.contentDocument && yr(n.contentDocument) ? n.contentDocument : (c(n, "template") && (n = n.content || n), i.merge([], n.childNodes))
        }
    }, function (n, t) {
        i.fn[n] = function (r, u) {
            var f = i.map(this, t, r);
            return "Until" !== n.slice(-5) && (u = r), u && "string" == typeof u && (f = i.filter(u, f)), 1 < this.length && (uu[n] || i.uniqueSort(f), ru.test(n) && f.reverse()), this.pushStack(f)
        }
    });
    l = /[^\x20\t\r\n\f]+/g;
    i.Callbacks = function (n) {
        var a, h;
        n = "string" == typeof n ? (a = n, h = {}, i.each(a.match(l) || [], function (n, t) {
            h[t] = !0
        }), h) : i.extend({}, n);
        var o, r, v, f, t = [],
            s = [],
            e = -1,
            y = function () {
                for (f = f || n.once, v = o = !0; s.length; e = -1)
                    for (r = s.shift(); ++e < t.length;) !1 === t[e].apply(r[0], r[1]) && n.stopOnFalse && (e = t.length, r = !1);
                n.memory || (r = !1);
                o = !1;
                f && (t = r ? [] : "")
            },
            c = {
                add: function () {
                    return t && (r && !o && (e = t.length - 1, s.push(r)), function f(r) {
                        i.each(r, function (i, r) {
                            u(r) ? n.unique && c.has(r) || t.push(r) : r && r.length && "string" !== ut(r) && f(r)
                        })
                    }(arguments), r && !o && y()), this
                },
                remove: function () {
                    return i.each(arguments, function (n, r) {
                        for (var u; - 1 < (u = i.inArray(r, t, u));) t.splice(u, 1), u <= e && e--
                    }), this
                },
                has: function (n) {
                    return n ? -1 < i.inArray(n, t) : 0 < t.length
                },
                empty: function () {
                    return t && (t = []), this
                },
                disable: function () {
                    return f = s = [], t = r = "", this
                },
                disabled: function () {
                    return !t
                },
                lock: function () {
                    return f = s = [], r || o || (t = r = ""), this
                },
                locked: function () {
                    return !!f
                },
                fireWith: function (n, t) {
                    return f || (t = [n, (t = t || []).slice ? t.slice() : t], s.push(t), o || y()), this
                },
                fire: function () {
                    return c.fireWith(this, arguments), this
                },
                fired: function () {
                    return !!v
                }
            };
        return c
    };
    i.extend({
        Deferred: function (t) {
            var f = [
                    ["notify", "progress", i.Callbacks("memory"), i.Callbacks("memory"), 2],
                    ["resolve", "done", i.Callbacks("once memory"), i.Callbacks("once memory"), 0, "resolved"],
                    ["reject", "fail", i.Callbacks("once memory"), i.Callbacks("once memory"), 1, "rejected"]
                ],
                o = "pending",
                e = {
                    state: function () {
                        return o
                    },
                    always: function () {
                        return r.done(arguments).fail(arguments), this
                    },
                    "catch": function (n) {
                        return e.then(null, n)
                    },
                    pipe: function () {
                        var n = arguments;
                        return i.Deferred(function (t) {
                            i.each(f, function (i, f) {
                                var e = u(n[f[4]]) && n[f[4]];
                                r[f[1]](function () {
                                    var n = e && e.apply(this, arguments);
                                    n && u(n.promise) ? n.promise().progress(t.notify).done(t.resolve).fail(t.reject) : t[f[0] + "With"](this, e ? [n] : arguments)
                                })
                            });
                            n = null
                        }).promise()
                    },
                    then: function (t, r, e) {
                        function s(t, r, f, e) {
                            return function () {
                                var h = this,
                                    c = arguments,
                                    l = function () {
                                        var n, i;
                                        if (!(t < o)) {
                                            if ((n = f.apply(h, c)) === r.promise()) throw new TypeError("Thenable self-resolution");
                                            i = n && ("object" == typeof n || "function" == typeof n) && n.then;
                                            u(i) ? e ? i.call(n, s(o, r, et, e), s(o, r, fi, e)) : (o++, i.call(n, s(o, r, et, e), s(o, r, fi, e), s(o, r, et, r.notifyWith))) : (f !== et && (h = void 0, c = [n]), (e || r.resolveWith)(h, c))
                                        }
                                    },
                                    a = e ? l : function () {
                                        try {
                                            l()
                                        } catch (l) {
                                            i.Deferred.exceptionHook && i.Deferred.exceptionHook(l, a.stackTrace);
                                            o <= t + 1 && (f !== fi && (h = void 0, c = [l]), r.rejectWith(h, c))
                                        }
                                    };
                                t ? a() : (i.Deferred.getStackHook && (a.stackTrace = i.Deferred.getStackHook()), n.setTimeout(a))
                            }
                        }
                        var o = 0;
                        return i.Deferred(function (n) {
                            f[0][3].add(s(0, n, u(e) ? e : et, n.notifyWith));
                            f[1][3].add(s(0, n, u(t) ? t : et));
                            f[2][3].add(s(0, n, u(r) ? r : fi))
                        }).promise()
                    },
                    promise: function (n) {
                        return null != n ? i.extend(n, e) : e
                    }
                },
                r = {};
            return i.each(f, function (n, t) {
                var i = t[2],
                    u = t[5];
                e[t[1]] = i.add;
                u && i.add(function () {
                    o = u
                }, f[3 - n][2].disable, f[3 - n][3].disable, f[0][2].lock, f[0][3].lock);
                i.add(t[3].fire);
                r[t[0]] = function () {
                    return r[t[0] + "With"](this === r ? void 0 : this, arguments), this
                };
                r[t[0] + "With"] = i.fireWith
            }), e.promise(r), t && t.call(r, r), r
        },
        when: function (n) {
            var e = arguments.length,
                t = e,
                o = Array(t),
                f = k.call(arguments),
                r = i.Deferred(),
                s = function (n) {
                    return function (t) {
                        o[n] = this;
                        f[n] = 1 < arguments.length ? k.call(arguments) : t;
                        --e || r.resolveWith(o, f)
                    }
                };
            if (e <= 1 && (eu(n, r.done(s(t)).resolve, r.reject, !e), "pending" === r.state() || u(f[t] && f[t].then))) return r.then();
            while (t--) eu(f[t], s(t), r.reject);
            return r.promise()
        }
    });
    ou = /^(Eval|Internal|Range|Reference|Syntax|Type|URI)Error$/;
    i.Deferred.exceptionHook = function (t, i) {
        n.console && n.console.warn && t && ou.test(t.name) && n.console.warn("jQuery.Deferred exception: " + t.message, t.stack, i)
    };
    i.readyException = function (t) {
        n.setTimeout(function () {
            throw t;
        })
    };
    ei = i.Deferred();
    i.fn.ready = function (n) {
        return ei.then(n)["catch"](function (n) {
            i.readyException(n)
        }), this
    };
    i.extend({
        isReady: !1,
        readyWait: 1,
        ready: function (n) {
            (!0 === n ? --i.readyWait : i.isReady) || (i.isReady = !0) !== n && 0 < --i.readyWait || ei.resolveWith(f, [i])
        }
    });
    i.ready.then = ei.then;
    "complete" === f.readyState || "loading" !== f.readyState && !f.documentElement.doScroll ? n.setTimeout(i.ready) : (f.addEventListener("DOMContentLoaded", oi), n.addEventListener("load", oi));
    var w = function (n, t, r, f, e, o, s) {
            var h = 0,
                l = n.length,
                c = null == r;
            if ("object" === ut(r))
                for (h in e = !0, r) w(n, t, h, r[h], !0, o, s);
            else if (void 0 !== f && (e = !0, u(f) || (s = !0), c && (s ? (t.call(n, f), t = null) : (c = t, t = function (n, t, r) {
                    return c.call(i(n), r)
                })), t))
                for (; h < l; h++) t(n[h], r, s ? f : f.call(n[h], h, t(n[h], r)));
            return e ? n : c ? t.call(n) : l ? t(n[0], r) : o
        },
        se = /^-ms-/,
        he = /-([a-z])/g;
    ot = function (n) {
        return 1 === n.nodeType || 9 === n.nodeType || !+n.nodeType
    };
    bt.uid = 1;
    bt.prototype = {
        cache: function (n) {
            var t = n[this.expando];
            return t || (t = {}, ot(n) && (n.nodeType ? n[this.expando] = t : Object.defineProperty(n, this.expando, {
                value: t,
                configurable: !0
            }))), t
        },
        set: function (n, t, i) {
            var r, u = this.cache(n);
            if ("string" == typeof t) u[y(t)] = i;
            else
                for (r in t) u[y(r)] = t[r];
            return u
        },
        get: function (n, t) {
            return void 0 === t ? this.cache(n) : n[this.expando] && n[this.expando][y(t)]
        },
        access: function (n, t, i) {
            return void 0 === t || t && "string" == typeof t && void 0 === i ? this.get(n, t) : (this.set(n, t, i), void 0 !== i ? i : t)
        },
        remove: function (n, t) {
            var u, r = n[this.expando];
            if (void 0 !== r) {
                if (void 0 !== t)
                    for (u = (t = Array.isArray(t) ? t.map(y) : (t = y(t)) in r ? [t] : t.match(l) || []).length; u--;) delete r[t[u]];
                (void 0 === t || i.isEmptyObject(r)) && (n.nodeType ? n[this.expando] = void 0 : delete n[this.expando])
            }
        },
        hasData: function (n) {
            var t = n[this.expando];
            return void 0 !== t && !i.isEmptyObject(t)
        }
    };
    var r = new bt,
        o = new bt,
        le = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/,
        ae = /[A-Z]/g;
    i.extend({
        hasData: function (n) {
            return o.hasData(n) || r.hasData(n)
        },
        data: function (n, t, i) {
            return o.access(n, t, i)
        },
        removeData: function (n, t) {
            o.remove(n, t)
        },
        _data: function (n, t, i) {
            return r.access(n, t, i)
        },
        _removeData: function (n, t) {
            r.remove(n, t)
        }
    });
    i.fn.extend({
        data: function (n, t) {
            var f, u, e, i = this[0],
                s = i && i.attributes;
            if (void 0 === n) {
                if (this.length && (e = o.get(i), 1 === i.nodeType && !r.get(i, "hasDataAttrs"))) {
                    for (f = s.length; f--;) s[f] && 0 === (u = s[f].name).indexOf("data-") && (u = y(u.slice(5)), su(i, u, e[u]));
                    r.set(i, "hasDataAttrs", !0)
                }
                return e
            }
            return "object" == typeof n ? this.each(function () {
                o.set(this, n)
            }) : w(this, function (t) {
                var r;
                if (i && void 0 === t) return void 0 !== (r = o.get(i, n)) ? r : void 0 !== (r = su(i, n)) ? r : void 0;
                this.each(function () {
                    o.set(this, n, t)
                })
            }, null, t, 1 < arguments.length, null, !0)
        },
        removeData: function (n) {
            return this.each(function () {
                o.remove(this, n)
            })
        }
    });
    i.extend({
        queue: function (n, t, u) {
            var f;
            if (n) return t = (t || "fx") + "queue", f = r.get(n, t), u && (!f || Array.isArray(u) ? f = r.access(n, t, i.makeArray(u)) : f.push(u)), f || []
        },
        dequeue: function (n, t) {
            t = t || "fx";
            var r = i.queue(n, t),
                e = r.length,
                u = r.shift(),
                f = i._queueHooks(n, t);
            "inprogress" === u && (u = r.shift(), e--);
            u && ("fx" === t && r.unshift("inprogress"), delete f.stop, u.call(n, function () {
                i.dequeue(n, t)
            }, f));
            !e && f && f.empty.fire()
        },
        _queueHooks: function (n, t) {
            var u = t + "queueHooks";
            return r.get(n, u) || r.access(n, u, {
                empty: i.Callbacks("once memory").add(function () {
                    r.remove(n, [t + "queue", u])
                })
            })
        }
    });
    i.fn.extend({
        queue: function (n, t) {
            var r = 2;
            return "string" != typeof n && (t = n, n = "fx", r--), arguments.length < r ? i.queue(this[0], n) : void 0 === t ? this : this.each(function () {
                var r = i.queue(this, n, t);
                i._queueHooks(this, n);
                "fx" === n && "inprogress" !== r[0] && i.dequeue(this, n)
            })
        },
        dequeue: function (n) {
            return this.each(function () {
                i.dequeue(this, n)
            })
        },
        clearQueue: function (n) {
            return this.queue(n || "fx", [])
        },
        promise: function (n, t) {
            var u, e = 1,
                o = i.Deferred(),
                f = this,
                s = this.length,
                h = function () {
                    --e || o.resolveWith(f, [f])
                };
            for ("string" != typeof n && (t = n, n = void 0), n = n || "fx"; s--;)(u = r.get(f[s], n + "queueHooks")) && u.empty && (e++, u.empty.add(h));
            return h(), o.promise(t)
        }
    });
    var hu = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,
        kt = new RegExp("^(?:([+-])=|)(" + hu + ")([a-z%]*)$", "i"),
        b = ["Top", "Right", "Bottom", "Left"],
        g = f.documentElement,
        st = function (n) {
            return i.contains(n.ownerDocument, n)
        },
        ve = {
            composed: !0
        };
    g.getRootNode && (st = function (n) {
        return i.contains(n.ownerDocument, n) || n.getRootNode(ve) === n.ownerDocument
    });
    dt = function (n, t) {
        return "none" === (n = t || n).style.display || "" === n.style.display && st(n) && "none" === i.css(n, "display")
    };
    ki = {};
    i.fn.extend({
        show: function () {
            return ht(this, !0)
        },
        hide: function () {
            return ht(this)
        },
        toggle: function (n) {
            return "boolean" == typeof n ? n ? this.show() : this.hide() : this.each(function () {
                dt(this) ? i(this).show() : i(this).hide()
            })
        }
    });
    var nt, si, gt = /^(?:checkbox|radio)$/i,
        lu = /<([a-z][^\/\0>\x20\t\r\n\f]*)/i,
        au = /^$|^module$|\/(?:java|ecma)script/i;
    nt = f.createDocumentFragment().appendChild(f.createElement("div"));
    (si = f.createElement("input")).setAttribute("type", "radio");
    si.setAttribute("checked", "checked");
    si.setAttribute("name", "t");
    nt.appendChild(si);
    e.checkClone = nt.cloneNode(!0).cloneNode(!0).lastChild.checked;
    nt.innerHTML = "<textarea>x<\/textarea>";
    e.noCloneChecked = !!nt.cloneNode(!0).lastChild.defaultValue;
    nt.innerHTML = "<option><\/option>";
    e.option = !!nt.lastChild;
    h = {
        thead: [1, "<table>", "<\/table>"],
        col: [2, "<table><colgroup>", "<\/colgroup><\/table>"],
        tr: [2, "<table><tbody>", "<\/tbody><\/table>"],
        td: [3, "<table><tbody><tr>", "<\/tr><\/tbody><\/table>"],
        _default: [0, "", ""]
    };
    h.tbody = h.tfoot = h.colgroup = h.caption = h.thead;
    h.th = h.td;
    e.option || (h.optgroup = h.option = [1, "<select multiple='multiple'>", "<\/select>"]);
    vu = /<|&#?\w+;/;
    gi = /^([^.]*)(?:\.(.+)|)/;
    i.event = {
        global: {},
        add: function (n, t, u, f, e) {
            var p, a, k, v, w, h, s, c, o, b, d, y = r.get(n);
            if (ot(n))
                for (u.handler && (u = (p = u).handler, e = p.selector), e && i.find.matchesSelector(g, e), u.guid || (u.guid = i.guid++), (v = y.events) || (v = y.events = Object.create(null)), (a = y.handle) || (a = y.handle = function (t) {
                        if ("undefined" != typeof i && i.event.triggered !== t.type) return i.event.dispatch.apply(n, arguments)
                    }), w = (t = (t || "").match(l) || [""]).length; w--;) o = d = (k = gi.exec(t[w]) || [])[1], b = (k[2] || "").split(".").sort(), o && (s = i.event.special[o] || {}, o = (e ? s.delegateType : s.bindType) || o, s = i.event.special[o] || {}, h = i.extend({
                    type: o,
                    origType: d,
                    data: f,
                    handler: u,
                    guid: u.guid,
                    selector: e,
                    needsContext: e && i.expr.match.needsContext.test(e),
                    namespace: b.join(".")
                }, p), (c = v[o]) || ((c = v[o] = []).delegateCount = 0, s.setup && !1 !== s.setup.call(n, f, b, a) || n.addEventListener && n.addEventListener(o, a)), s.add && (s.add.call(n, h), h.handler.guid || (h.handler.guid = u.guid)), e ? c.splice(c.delegateCount++, 0, h) : c.push(h), i.event.global[o] = !0)
        },
        remove: function (n, t, u, f, e) {
            var y, k, c, v, p, s, h, a, o, b, d, w = r.hasData(n) && r.get(n);
            if (w && (v = w.events)) {
                for (p = (t = (t || "").match(l) || [""]).length; p--;)
                    if (o = d = (c = gi.exec(t[p]) || [])[1], b = (c[2] || "").split(".").sort(), o) {
                        for (h = i.event.special[o] || {}, a = v[o = (f ? h.delegateType : h.bindType) || o] || [], c = c[2] && new RegExp("(^|\\.)" + b.join("\\.(?:.*\\.|)") + "(\\.|$)"), k = y = a.length; y--;) s = a[y], !e && d !== s.origType || u && u.guid !== s.guid || c && !c.test(s.namespace) || f && f !== s.selector && ("**" !== f || !s.selector) || (a.splice(y, 1), s.selector && a.delegateCount--, h.remove && h.remove.call(n, s));
                        k && !a.length && (h.teardown && !1 !== h.teardown.call(n, b, w.handle) || i.removeEvent(n, o, w.handle), delete v[o])
                    } else
                        for (o in v) i.event.remove(n, o + t[p], u, f, !0);
                i.isEmptyObject(v) && r.remove(n, "handle events")
            }
        },
        dispatch: function (n) {
            var u, h, c, e, f, l, s = new Array(arguments.length),
                t = i.event.fix(n),
                a = (r.get(this, "events") || Object.create(null))[t.type] || [],
                o = i.event.special[t.type] || {};
            for (s[0] = t, u = 1; u < arguments.length; u++) s[u] = arguments[u];
            if (t.delegateTarget = this, !o.preDispatch || !1 !== o.preDispatch.call(this, t)) {
                for (l = i.event.handlers.call(this, t, a), u = 0;
                    (e = l[u++]) && !t.isPropagationStopped();)
                    for (t.currentTarget = e.elem, h = 0;
                        (f = e.handlers[h++]) && !t.isImmediatePropagationStopped();) t.rnamespace && !1 !== f.namespace && !t.rnamespace.test(f.namespace) || (t.handleObj = f, t.data = f.data, void 0 !== (c = ((i.event.special[f.origType] || {}).handle || f.handler).apply(e.elem, s)) && !1 === (t.result = c) && (t.preventDefault(), t.stopPropagation()));
                return o.postDispatch && o.postDispatch.call(this, t), t.result
            }
        },
        handlers: function (n, t) {
            var f, h, u, e, o, c = [],
                s = t.delegateCount,
                r = n.target;
            if (s && r.nodeType && !("click" === n.type && 1 <= n.button))
                for (; r !== this; r = r.parentNode || this)
                    if (1 === r.nodeType && ("click" !== n.type || !0 !== r.disabled)) {
                        for (e = [], o = {}, f = 0; f < s; f++) void 0 === o[u = (h = t[f]).selector + " "] && (o[u] = h.needsContext ? -1 < i(u, this).index(r) : i.find(u, this, null, [r]).length), o[u] && e.push(h);
                        e.length && c.push({
                            elem: r,
                            handlers: e
                        })
                    } return r = this, s < t.length && c.push({
                elem: r,
                handlers: t.slice(s)
            }), c
        },
        addProp: function (n, t) {
            Object.defineProperty(i.Event.prototype, n, {
                enumerable: !0,
                configurable: !0,
                get: u(t) ? function () {
                    if (this.originalEvent) return t(this.originalEvent)
                } : function () {
                    if (this.originalEvent) return this.originalEvent[n]
                },
                set: function (t) {
                    Object.defineProperty(this, n, {
                        enumerable: !0,
                        configurable: !0,
                        writable: !0,
                        value: t
                    })
                }
            })
        },
        fix: function (n) {
            return n[i.expando] ? n : new i.Event(n)
        },
        special: {
            load: {
                noBubble: !0
            },
            click: {
                setup: function (n) {
                    var t = this || n;
                    return gt.test(t.type) && t.click && c(t, "input") && hi(t, "click", ct), !1
                },
                trigger: function (n) {
                    var t = this || n;
                    return gt.test(t.type) && t.click && c(t, "input") && hi(t, "click"), !0
                },
                _default: function (n) {
                    var t = n.target;
                    return gt.test(t.type) && t.click && c(t, "input") && r.get(t, "click") || c(t, "a")
                }
            },
            beforeunload: {
                postDispatch: function (n) {
                    void 0 !== n.result && n.originalEvent && (n.originalEvent.returnValue = n.result)
                }
            }
        }
    };
    i.removeEvent = function (n, t, i) {
        n.removeEventListener && n.removeEventListener(t, i)
    };
    i.Event = function (n, t) {
        if (!(this instanceof i.Event)) return new i.Event(n, t);
        n && n.type ? (this.originalEvent = n, this.type = n.type, this.isDefaultPrevented = n.defaultPrevented || void 0 === n.defaultPrevented && !1 === n.returnValue ? ct : lt, this.target = n.target && 3 === n.target.nodeType ? n.target.parentNode : n.target, this.currentTarget = n.currentTarget, this.relatedTarget = n.relatedTarget) : this.type = n;
        t && i.extend(this, t);
        this.timeStamp = n && n.timeStamp || Date.now();
        this[i.expando] = !0
    };
    i.Event.prototype = {
        constructor: i.Event,
        isDefaultPrevented: lt,
        isPropagationStopped: lt,
        isImmediatePropagationStopped: lt,
        isSimulated: !1,
        preventDefault: function () {
            var n = this.originalEvent;
            this.isDefaultPrevented = ct;
            n && !this.isSimulated && n.preventDefault()
        },
        stopPropagation: function () {
            var n = this.originalEvent;
            this.isPropagationStopped = ct;
            n && !this.isSimulated && n.stopPropagation()
        },
        stopImmediatePropagation: function () {
            var n = this.originalEvent;
            this.isImmediatePropagationStopped = ct;
            n && !this.isSimulated && n.stopImmediatePropagation();
            this.stopPropagation()
        }
    };
    i.each({
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
        char: !0,
        code: !0,
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
        which: !0
    }, i.event.addProp);
    i.each({
        focus: "focusin",
        blur: "focusout"
    }, function (n, t) {
        i.event.special[n] = {
            setup: function () {
                return hi(this, n, ye), !1
            },
            trigger: function () {
                return hi(this, n), !0
            },
            _default: function () {
                return !0
            },
            delegateType: t
        }
    });
    i.each({
        mouseenter: "mouseover",
        mouseleave: "mouseout",
        pointerenter: "pointerover",
        pointerleave: "pointerout"
    }, function (n, t) {
        i.event.special[n] = {
            delegateType: t,
            bindType: t,
            handle: function (n) {
                var u, r = n.relatedTarget,
                    f = n.handleObj;
                return r && (r === this || i.contains(this, r)) || (n.type = f.origType, u = f.handler.apply(this, arguments), n.type = t), u
            }
        }
    });
    i.fn.extend({
        on: function (n, t, i, r) {
            return nr(this, n, t, i, r)
        },
        one: function (n, t, i, r) {
            return nr(this, n, t, i, r, 1)
        },
        off: function (n, t, r) {
            var u, f;
            if (n && n.preventDefault && n.handleObj) return u = n.handleObj, i(n.delegateTarget).off(u.namespace ? u.origType + "." + u.namespace : u.origType, u.selector, u.handler), this;
            if ("object" == typeof n) {
                for (f in n) this.off(f, t, n[f]);
                return this
            }
            return !1 !== t && "function" != typeof t || (r = t, t = void 0), !1 === r && (r = lt), this.each(function () {
                i.event.remove(this, n, r, t)
            })
        }
    });
    var pe = /<script|<style|<link/i,
        we = /checked\s*(?:[^=]|=\s*.checked.)/i,
        be = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g;
    i.extend({
        htmlPrefilter: function (n) {
            return n
        },
        clone: function (n, t, r) {
            var u, c, o, f, l, a, v, h = n.cloneNode(!0),
                y = st(n);
            if (!(e.noCloneChecked || 1 !== n.nodeType && 11 !== n.nodeType || i.isXMLDoc(n)))
                for (f = s(h), u = 0, c = (o = s(n)).length; u < c; u++) l = o[u], a = f[u], void 0, "input" === (v = a.nodeName.toLowerCase()) && gt.test(l.type) ? a.checked = l.checked : "input" !== v && "textarea" !== v || (a.defaultValue = l.defaultValue);
            if (t)
                if (r)
                    for (o = o || s(n), f = f || s(h), u = 0, c = o.length; u < c; u++) wu(o[u], f[u]);
                else wu(n, h);
            return 0 < (f = s(h, "script")).length && di(f, !y && s(n, "script")), h
        },
        cleanData: function (n) {
            for (var u, t, f, s = i.event.special, e = 0; void 0 !== (t = n[e]); e++)
                if (ot(t)) {
                    if (u = t[r.expando]) {
                        if (u.events)
                            for (f in u.events) s[f] ? i.event.remove(t, f) : i.removeEvent(t, f, u.handle);
                        t[r.expando] = void 0
                    }
                    t[o.expando] && (t[o.expando] = void 0)
                }
        }
    });
    i.fn.extend({
        detach: function (n) {
            return bu(this, n, !0)
        },
        remove: function (n) {
            return bu(this, n)
        },
        text: function (n) {
            return w(this, function (n) {
                return void 0 === n ? i.text(this) : this.empty().each(function () {
                    1 !== this.nodeType && 11 !== this.nodeType && 9 !== this.nodeType || (this.textContent = n)
                })
            }, null, n, arguments.length)
        },
        append: function () {
            return at(this, arguments, function (n) {
                1 !== this.nodeType && 11 !== this.nodeType && 9 !== this.nodeType || pu(this, n).appendChild(n)
            })
        },
        prepend: function () {
            return at(this, arguments, function (n) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                    var t = pu(this, n);
                    t.insertBefore(n, t.firstChild)
                }
            })
        },
        before: function () {
            return at(this, arguments, function (n) {
                this.parentNode && this.parentNode.insertBefore(n, this)
            })
        },
        after: function () {
            return at(this, arguments, function (n) {
                this.parentNode && this.parentNode.insertBefore(n, this.nextSibling)
            })
        },
        empty: function () {
            for (var n, t = 0; null != (n = this[t]); t++) 1 === n.nodeType && (i.cleanData(s(n, !1)), n.textContent = "");
            return this
        },
        clone: function (n, t) {
            return n = null != n && n, t = null == t ? n : t, this.map(function () {
                return i.clone(this, n, t)
            })
        },
        html: function (n) {
            return w(this, function (n) {
                var t = this[0] || {},
                    r = 0,
                    u = this.length;
                if (void 0 === n && 1 === t.nodeType) return t.innerHTML;
                if ("string" == typeof n && !pe.test(n) && !h[(lu.exec(n) || ["", ""])[1].toLowerCase()]) {
                    n = i.htmlPrefilter(n);
                    try {
                        for (; r < u; r++) 1 === (t = this[r] || {}).nodeType && (i.cleanData(s(t, !1)), t.innerHTML = n);
                        t = 0
                    } catch (n) {}
                }
                t && this.empty().append(n)
            }, null, n, arguments.length)
        },
        replaceWith: function () {
            var n = [];
            return at(this, arguments, function (t) {
                var r = this.parentNode;
                i.inArray(this, n) < 0 && (i.cleanData(s(this)), r && r.replaceChild(t, this))
            }, n)
        }
    });
    i.each({
        appendTo: "append",
        prependTo: "prepend",
        insertBefore: "before",
        insertAfter: "after",
        replaceAll: "replaceWith"
    }, function (n, t) {
        i.fn[n] = function (n) {
            for (var u, f = [], e = i(n), o = e.length - 1, r = 0; r <= o; r++) u = r === o ? this : this.clone(!0), i(e[r])[t](u), yi.apply(f, u.get());
            return this.pushStack(f)
        }
    });
    var tr = new RegExp("^(" + hu + ")(?!px)[a-z%]+$", "i"),
        ci = function (t) {
            var i = t.ownerDocument.defaultView;
            return i && i.opener || (i = n), i.getComputedStyle(t)
        },
        ku = function (n, t, i) {
            var u, r, f = {};
            for (r in t) f[r] = n.style[r], n.style[r] = t[r];
            for (r in u = i.call(n), t) n.style[r] = f[r];
            return u
        },
        ge = new RegExp(b.join("|"), "i");
    ! function () {
        function r() {
            if (t) {
                s.style.cssText = "position:absolute;left:-11111px;width:60px;margin-top:1px;padding:0;border:0";
                t.style.cssText = "position:relative;display:block;box-sizing:border-box;overflow:scroll;margin:auto;border:1px;padding:1px;width:60%;top:1%";
                g.appendChild(s).appendChild(t);
                var i = n.getComputedStyle(t);
                h = "1%" !== i.top;
                v = 12 === u(i.marginLeft);
                t.style.right = "60%";
                a = 36 === u(i.right);
                c = 36 === u(i.width);
                t.style.position = "absolute";
                l = 12 === u(t.offsetWidth / 3);
                g.removeChild(s);
                t = null
            }
        }

        function u(n) {
            return Math.round(parseFloat(n))
        }
        var h, c, l, a, o, v, s = f.createElement("div"),
            t = f.createElement("div");
        t.style && (t.style.backgroundClip = "content-box", t.cloneNode(!0).style.backgroundClip = "", e.clearCloneStyle = "content-box" === t.style.backgroundClip, i.extend(e, {
            boxSizingReliable: function () {
                return r(), c
            },
            pixelBoxStyles: function () {
                return r(), a
            },
            pixelPosition: function () {
                return r(), h
            },
            reliableMarginLeft: function () {
                return r(), v
            },
            scrollboxSize: function () {
                return r(), l
            },
            reliableTrDimensions: function () {
                var i, t, r, u;
                return null == o && (i = f.createElement("table"), t = f.createElement("tr"), r = f.createElement("div"), i.style.cssText = "position:absolute;left:-11111px;border-collapse:separate", t.style.cssText = "border:1px solid", t.style.height = "1px", r.style.height = "9px", r.style.display = "block", g.appendChild(i).appendChild(t).appendChild(r), u = n.getComputedStyle(t), o = parseInt(u.height, 10) + parseInt(u.borderTopWidth, 10) + parseInt(u.borderBottomWidth, 10) === t.offsetHeight, g.removeChild(i)), o
            }
        }))
    }();
    var gu = ["Webkit", "Moz", "ms"],
        nf = f.createElement("div").style,
        tf = {};
    var no = /^(none|table(?!-c[ea]).+)/,
        rf = /^--/,
        to = {
            position: "absolute",
            visibility: "hidden",
            display: "block"
        },
        uf = {
            letterSpacing: "0",
            fontWeight: "400"
        };
    i.extend({
        cssHooks: {
            opacity: {
                get: function (n, t) {
                    if (t) {
                        var i = ni(n, "opacity");
                        return "" === i ? "1" : i
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
            gridArea: !0,
            gridColumn: !0,
            gridColumnEnd: !0,
            gridColumnStart: !0,
            gridRow: !0,
            gridRowEnd: !0,
            gridRowStart: !0,
            lineHeight: !0,
            opacity: !0,
            order: !0,
            orphans: !0,
            widows: !0,
            zIndex: !0,
            zoom: !0
        },
        cssProps: {},
        style: function (n, t, r, u) {
            if (n && 3 !== n.nodeType && 8 !== n.nodeType && n.style) {
                var f, h, o, c = y(t),
                    l = rf.test(t),
                    s = n.style;
                if (l || (t = ir(c)), o = i.cssHooks[t] || i.cssHooks[c], void 0 === r) return o && "get" in o && void 0 !== (f = o.get(n, !1, u)) ? f : s[t];
                "string" == (h = typeof r) && (f = kt.exec(r)) && f[1] && (r = cu(n, t, f), h = "number");
                null != r && r == r && ("number" !== h || l || (r += f && f[3] || (i.cssNumber[c] ? "" : "px")), e.clearCloneStyle || "" !== r || 0 !== t.indexOf("background") || (s[t] = "inherit"), o && "set" in o && void 0 === (r = o.set(n, r, u)) || (l ? s.setProperty(t, r) : s[t] = r))
            }
        },
        css: function (n, t, r, u) {
            var f, e, o, s = y(t);
            return rf.test(t) || (t = ir(s)), (o = i.cssHooks[t] || i.cssHooks[s]) && "get" in o && (f = o.get(n, !0, r)), void 0 === f && (f = ni(n, t, u)), "normal" === f && t in uf && (f = uf[t]), "" === r || r ? (e = parseFloat(f), !0 === r || isFinite(e) ? e || 0 : f) : f
        }
    });
    i.each(["height", "width"], function (n, t) {
        i.cssHooks[t] = {
            get: function (n, r, u) {
                if (r) return !no.test(i.css(n, "display")) || n.getClientRects().length && n.getBoundingClientRect().width ? ef(n, t, u) : ku(n, to, function () {
                    return ef(n, t, u)
                })
            },
            set: function (n, r, u) {
                var s, f = ci(n),
                    h = !e.scrollboxSize() && "absolute" === f.position,
                    c = (h || u) && "border-box" === i.css(n, "boxSizing", !1, f),
                    o = u ? rr(n, t, u, c, f) : 0;
                return c && h && (o -= Math.ceil(n["offset" + t[0].toUpperCase() + t.slice(1)] - parseFloat(f[t]) - rr(n, t, "border", !1, f) - .5)), o && (s = kt.exec(r)) && "px" !== (s[3] || "px") && (n.style[t] = r, r = i.css(n, t)), ff(0, r, o)
            }
        }
    });
    i.cssHooks.marginLeft = du(e.reliableMarginLeft, function (n, t) {
        if (t) return (parseFloat(ni(n, "marginLeft")) || n.getBoundingClientRect().left - ku(n, {
            marginLeft: 0
        }, function () {
            return n.getBoundingClientRect().left
        })) + "px"
    });
    i.each({
        margin: "",
        padding: "",
        border: "Width"
    }, function (n, t) {
        i.cssHooks[n + t] = {
            expand: function (i) {
                for (var r = 0, f = {}, u = "string" == typeof i ? i.split(" ") : [i]; r < 4; r++) f[n + b[r] + t] = u[r] || u[r - 2] || u[0];
                return f
            }
        };
        "margin" !== n && (i.cssHooks[n + t].set = ff)
    });
    i.fn.extend({
        css: function (n, t) {
            return w(this, function (n, t, r) {
                var f, e, o = {},
                    u = 0;
                if (Array.isArray(t)) {
                    for (f = ci(n), e = t.length; u < e; u++) o[t[u]] = i.css(n, t[u], !1, f);
                    return o
                }
                return void 0 !== r ? i.style(n, t, r) : i.css(n, t)
            }, n, t, 1 < arguments.length)
        }
    });
    ((i.Tween = a).prototype = {
        constructor: a,
        init: function (n, t, r, u, f, e) {
            this.elem = n;
            this.prop = r;
            this.easing = f || i.easing._default;
            this.options = t;
            this.start = this.now = this.cur();
            this.end = u;
            this.unit = e || (i.cssNumber[r] ? "" : "px")
        },
        cur: function () {
            var n = a.propHooks[this.prop];
            return n && n.get ? n.get(this) : a.propHooks._default.get(this)
        },
        run: function (n) {
            var t, r = a.propHooks[this.prop];
            return this.pos = this.options.duration ? t = i.easing[this.easing](n, this.options.duration * n, 0, 1, this.options.duration) : t = n, this.now = (this.end - this.start) * t + this.start, this.options.step && this.options.step.call(this.elem, this.now, this), r && r.set ? r.set(this) : a.propHooks._default.set(this), this
        }
    }).init.prototype = a.prototype;
    (a.propHooks = {
        _default: {
            get: function (n) {
                var t;
                return 1 !== n.elem.nodeType || null != n.elem[n.prop] && null == n.elem.style[n.prop] ? n.elem[n.prop] : (t = i.css(n.elem, n.prop, "")) && "auto" !== t ? t : 0
            },
            set: function (n) {
                i.fx.step[n.prop] ? i.fx.step[n.prop](n) : 1 !== n.elem.nodeType || !i.cssHooks[n.prop] && null == n.elem.style[ir(n.prop)] ? n.elem[n.prop] = n.now : i.style(n.elem, n.prop, n.now + n.unit)
            }
        }
    }).scrollTop = a.propHooks.scrollLeft = {
        set: function (n) {
            n.elem.nodeType && n.elem.parentNode && (n.elem[n.prop] = n.now)
        }
    };
    i.easing = {
        linear: function (n) {
            return n
        },
        swing: function (n) {
            return .5 - Math.cos(n * Math.PI) / 2
        },
        _default: "swing"
    };
    i.fx = a.prototype.init;
    i.fx.step = {};
    sf = /^(?:toggle|show|hide)$/;
    hf = /queueHooks$/;
    i.Animation = i.extend(v, {
        tweeners: {
            "*": [function (n, t) {
                var i = this.createTween(n, t);
                return cu(i.elem, n, kt.exec(t), i), i
            }]
        },
        tweener: function (n, t) {
            u(n) ? (t = n, n = ["*"]) : n = n.match(l);
            for (var i, r = 0, f = n.length; r < f; r++) i = n[r], v.tweeners[i] = v.tweeners[i] || [], v.tweeners[i].unshift(t)
        },
        prefilters: [function (n, t, u) {
            var f, y, w, c, b, h, o, l, k = "width" in t || "height" in t,
                v = this,
                p = {},
                s = n.style,
                a = n.nodeType && dt(n),
                e = r.get(n, "fxshow");
            for (f in u.queue || (null == (c = i._queueHooks(n, "fx")).unqueued && (c.unqueued = 0, b = c.empty.fire, c.empty.fire = function () {
                    c.unqueued || b()
                }), c.unqueued++, v.always(function () {
                    v.always(function () {
                        c.unqueued--;
                        i.queue(n, "fx").length || c.empty.fire()
                    })
                })), t)
                if (y = t[f], sf.test(y)) {
                    if (delete t[f], w = w || "toggle" === y, y === (a ? "hide" : "show")) {
                        if ("show" !== y || !e || void 0 === e[f]) continue;
                        a = !0
                    }
                    p[f] = e && e[f] || i.style(n, f)
                } if ((h = !i.isEmptyObject(t)) || !i.isEmptyObject(p))
                for (f in k && 1 === n.nodeType && (u.overflow = [s.overflow, s.overflowX, s.overflowY], null == (o = e && e.display) && (o = r.get(n, "display")), "none" === (l = i.css(n, "display")) && (o ? l = o : (ht([n], !0), o = n.style.display || o, l = i.css(n, "display"), ht([n]))), ("inline" === l || "inline-block" === l && null != o) && "none" === i.css(n, "float") && (h || (v.done(function () {
                        s.display = o
                    }), null == o && (l = s.display, o = "none" === l ? "" : l)), s.display = "inline-block")), u.overflow && (s.overflow = "hidden", v.always(function () {
                        s.overflow = u.overflow[0];
                        s.overflowX = u.overflow[1];
                        s.overflowY = u.overflow[2]
                    })), h = !1, p) h || (e ? "hidden" in e && (a = e.hidden) : e = r.access(n, "fxshow", {
                    display: o
                }), w && (e.hidden = !a), a && ht([n], !0), v.done(function () {
                    for (f in a || ht([n]), r.remove(n, "fxshow"), p) i.style(n, f, p[f])
                })), h = lf(a ? e[f] : 0, f, v), f in e || (e[f] = h.start, a && (h.end = h.start, h.start = 0))
        }],
        prefilter: function (n, t) {
            t ? v.prefilters.unshift(n) : v.prefilters.push(n)
        }
    });
    i.speed = function (n, t, r) {
        var f = n && "object" == typeof n ? i.extend({}, n) : {
            complete: r || !r && t || u(n) && n,
            duration: n,
            easing: r && t || t && !u(t) && t
        };
        return i.fx.off ? f.duration = 0 : "number" != typeof f.duration && (f.duration = f.duration in i.fx.speeds ? i.fx.speeds[f.duration] : i.fx.speeds._default), null != f.queue && !0 !== f.queue || (f.queue = "fx"), f.old = f.complete, f.complete = function () {
            u(f.old) && f.old.call(this);
            f.queue && i.dequeue(this, f.queue)
        }, f
    };
    i.fn.extend({
        fadeTo: function (n, t, i, r) {
            return this.filter(dt).css("opacity", 0).show().end().animate({
                opacity: t
            }, n, i, r)
        },
        animate: function (n, t, u, f) {
            var s = i.isEmptyObject(n),
                o = i.speed(t, u, f),
                e = function () {
                    var t = v(this, i.extend({}, n), o);
                    (s || r.get(this, "finish")) && t.stop(!0)
                };
            return e.finish = e, s || !1 === o.queue ? this.each(e) : this.queue(o.queue, e)
        },
        stop: function (n, t, u) {
            var f = function (n) {
                var t = n.stop;
                delete n.stop;
                t(u)
            };
            return "string" != typeof n && (u = t, t = n, n = void 0), t && this.queue(n || "fx", []), this.each(function () {
                var s = !0,
                    t = null != n && n + "queueHooks",
                    o = i.timers,
                    e = r.get(this);
                if (t) e[t] && e[t].stop && f(e[t]);
                else
                    for (t in e) e[t] && e[t].stop && hf.test(t) && f(e[t]);
                for (t = o.length; t--;) o[t].elem !== this || null != n && o[t].queue !== n || (o[t].anim.stop(u), s = !1, o.splice(t, 1));
                !s && u || i.dequeue(this, n)
            })
        },
        finish: function (n) {
            return !1 !== n && (n = n || "fx"), this.each(function () {
                var t, e = r.get(this),
                    u = e[n + "queue"],
                    o = e[n + "queueHooks"],
                    f = i.timers,
                    s = u ? u.length : 0;
                for (e.finish = !0, i.queue(this, n, []), o && o.stop && o.stop.call(this, !0), t = f.length; t--;) f[t].elem === this && f[t].queue === n && (f[t].anim.stop(!0), f.splice(t, 1));
                for (t = 0; t < s; t++) u[t] && u[t].finish && u[t].finish.call(this);
                delete e.finish
            })
        }
    });
    i.each(["toggle", "show", "hide"], function (n, t) {
        var r = i.fn[t];
        i.fn[t] = function (n, i, u) {
            return null == n || "boolean" == typeof n ? r.apply(this, arguments) : this.animate(ai(t, !0), n, i, u)
        }
    });
    i.each({
        slideDown: ai("show"),
        slideUp: ai("hide"),
        slideToggle: ai("toggle"),
        fadeIn: {
            opacity: "show"
        },
        fadeOut: {
            opacity: "hide"
        },
        fadeToggle: {
            opacity: "toggle"
        }
    }, function (n, t) {
        i.fn[n] = function (n, i, r) {
            return this.animate(t, n, i, r)
        }
    });
    i.timers = [];
    i.fx.tick = function () {
        var r, n = 0,
            t = i.timers;
        for (vt = Date.now(); n < t.length; n++)(r = t[n])() || t[n] !== r || t.splice(n--, 1);
        t.length || i.fx.stop();
        vt = void 0
    };
    i.fx.timer = function (n) {
        i.timers.push(n);
        i.fx.start()
    };
    i.fx.interval = 13;
    i.fx.start = function () {
        li || (li = !0, ur())
    };
    i.fx.stop = function () {
        li = null
    };
    i.fx.speeds = {
        slow: 600,
        fast: 200,
        _default: 400
    };
    i.fn.delay = function (t, r) {
        return t = i.fx && i.fx.speeds[t] || t, r = r || "fx", this.queue(r, function (i, r) {
            var u = n.setTimeout(i, t);
            r.stop = function () {
                n.clearTimeout(u)
            }
        })
    };
    yt = f.createElement("input"); of = f.createElement("select").appendChild(f.createElement("option"));
    yt.type = "checkbox";
    e.checkOn = "" !== yt.value;
    e.optSelected = of .selected;
    (yt = f.createElement("input")).value = "t";
    yt.type = "radio";
    e.radioValue = "t" === yt.value;
    pt = i.expr.attrHandle;
    i.fn.extend({
        attr: function (n, t) {
            return w(this, i.attr, n, t, 1 < arguments.length)
        },
        removeAttr: function (n) {
            return this.each(function () {
                i.removeAttr(this, n)
            })
        }
    });
    i.extend({
        attr: function (n, t, r) {
            var f, u, e = n.nodeType;
            if (3 !== e && 8 !== e && 2 !== e) return "undefined" == typeof n.getAttribute ? i.prop(n, t, r) : (1 === e && i.isXMLDoc(n) || (u = i.attrHooks[t.toLowerCase()] || (i.expr.match.bool.test(t) ? af : void 0)), void 0 !== r ? null === r ? void i.removeAttr(n, t) : u && "set" in u && void 0 !== (f = u.set(n, r, t)) ? f : (n.setAttribute(t, r + ""), r) : u && "get" in u && null !== (f = u.get(n, t)) ? f : null == (f = i.find.attr(n, t)) ? void 0 : f)
        },
        attrHooks: {
            type: {
                set: function (n, t) {
                    if (!e.radioValue && "radio" === t && c(n, "input")) {
                        var i = n.value;
                        return n.setAttribute("type", t), i && (n.value = i), t
                    }
                }
            }
        },
        removeAttr: function (n, t) {
            var i, u = 0,
                r = t && t.match(l);
            if (r && 1 === n.nodeType)
                while (i = r[u++]) n.removeAttribute(i)
        }
    });
    af = {
        set: function (n, t, r) {
            return !1 === t ? i.removeAttr(n, r) : n.setAttribute(r, r), r
        }
    };
    i.each(i.expr.match.bool.source.match(/\w+/g), function (n, t) {
        var r = pt[t] || i.find.attr;
        pt[t] = function (n, t, i) {
            var f, e, u = t.toLowerCase();
            return i || (e = pt[u], pt[u] = f, f = null != r(n, t, i) ? u : null, pt[u] = e), f
        }
    });
    vf = /^(?:input|select|textarea|button)$/i;
    yf = /^(?:a|area)$/i;
    i.fn.extend({
        prop: function (n, t) {
            return w(this, i.prop, n, t, 1 < arguments.length)
        },
        removeProp: function (n) {
            return this.each(function () {
                delete this[i.propFix[n] || n]
            })
        }
    });
    i.extend({
        prop: function (n, t, r) {
            var f, u, e = n.nodeType;
            if (3 !== e && 8 !== e && 2 !== e) return 1 === e && i.isXMLDoc(n) || (t = i.propFix[t] || t, u = i.propHooks[t]), void 0 !== r ? u && "set" in u && void 0 !== (f = u.set(n, r, t)) ? f : n[t] = r : u && "get" in u && null !== (f = u.get(n, t)) ? f : n[t]
        },
        propHooks: {
            tabIndex: {
                get: function (n) {
                    var t = i.find.attr(n, "tabindex");
                    return t ? parseInt(t, 10) : vf.test(n.nodeName) || yf.test(n.nodeName) && n.href ? 0 : -1
                }
            }
        },
        propFix: {
            "for": "htmlFor",
            "class": "className"
        }
    });
    e.optSelected || (i.propHooks.selected = {
        get: function (n) {
            var t = n.parentNode;
            return t && t.parentNode && t.parentNode.selectedIndex, null
        },
        set: function (n) {
            var t = n.parentNode;
            t && (t.selectedIndex, t.parentNode && t.parentNode.selectedIndex)
        }
    });
    i.each(["tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable"], function () {
        i.propFix[this.toLowerCase()] = this
    });
    i.fn.extend({
        addClass: function (n) {
            var o, t, r, f, e, s, h, c = 0;
            if (u(n)) return this.each(function (t) {
                i(this).addClass(n.call(this, t, it(this)))
            });
            if ((o = fr(n)).length)
                while (t = this[c++])
                    if (f = it(t), r = 1 === t.nodeType && " " + tt(f) + " ") {
                        for (s = 0; e = o[s++];) r.indexOf(" " + e + " ") < 0 && (r += e + " ");
                        f !== (h = tt(r)) && t.setAttribute("class", h)
                    } return this
        },
        removeClass: function (n) {
            var o, r, t, f, e, s, h, c = 0;
            if (u(n)) return this.each(function (t) {
                i(this).removeClass(n.call(this, t, it(this)))
            });
            if (!arguments.length) return this.attr("class", "");
            if ((o = fr(n)).length)
                while (r = this[c++])
                    if (f = it(r), t = 1 === r.nodeType && " " + tt(f) + " ") {
                        for (s = 0; e = o[s++];)
                            while (-1 < t.indexOf(" " + e + " ")) t = t.replace(" " + e + " ", " ");
                        f !== (h = tt(t)) && r.setAttribute("class", h)
                    } return this
        },
        toggleClass: function (n, t) {
            var f = typeof n,
                e = "string" === f || Array.isArray(n);
            return "boolean" == typeof t && e ? t ? this.addClass(n) : this.removeClass(n) : u(n) ? this.each(function (r) {
                i(this).toggleClass(n.call(this, r, it(this), t), t)
            }) : this.each(function () {
                var t, o, u, s;
                if (e)
                    for (o = 0, u = i(this), s = fr(n); t = s[o++];) u.hasClass(t) ? u.removeClass(t) : u.addClass(t);
                else void 0 !== n && "boolean" !== f || ((t = it(this)) && r.set(this, "__className__", t), this.setAttribute && this.setAttribute("class", t || !1 === n ? "" : r.get(this, "__className__") || ""))
            })
        },
        hasClass: function (n) {
            for (var t, r = 0, i = " " + n + " "; t = this[r++];)
                if (1 === t.nodeType && -1 < (" " + tt(it(t)) + " ").indexOf(i)) return !0;
            return !1
        }
    });
    pf = /\r/g;
    i.fn.extend({
        val: function (n) {
            var t, r, e, f = this[0];
            return arguments.length ? (e = u(n), this.each(function (r) {
                var u;
                1 === this.nodeType && (null == (u = e ? n.call(this, r, i(this).val()) : n) ? u = "" : "number" == typeof u ? u += "" : Array.isArray(u) && (u = i.map(u, function (n) {
                    return null == n ? "" : n + ""
                })), (t = i.valHooks[this.type] || i.valHooks[this.nodeName.toLowerCase()]) && "set" in t && void 0 !== t.set(this, u, "value") || (this.value = u))
            })) : f ? (t = i.valHooks[f.type] || i.valHooks[f.nodeName.toLowerCase()]) && "get" in t && void 0 !== (r = t.get(f, "value")) ? r : "string" == typeof (r = f.value) ? r.replace(pf, "") : null == r ? "" : r : void 0
        }
    });
    i.extend({
        valHooks: {
            option: {
                get: function (n) {
                    var t = i.find.attr(n, "value");
                    return null != t ? t : tt(i.text(n))
                }
            },
            select: {
                get: function (n) {
                    for (var e, t, o = n.options, u = n.selectedIndex, f = "select-one" === n.type, s = f ? null : [], h = f ? u + 1 : o.length, r = u < 0 ? h : f ? u : 0; r < h; r++)
                        if (((t = o[r]).selected || r === u) && !t.disabled && (!t.parentNode.disabled || !c(t.parentNode, "optgroup"))) {
                            if (e = i(t).val(), f) return e;
                            s.push(e)
                        } return s
                },
                set: function (n, t) {
                    for (var r, u, f = n.options, e = i.makeArray(t), o = f.length; o--;)((u = f[o]).selected = -1 < i.inArray(i.valHooks.option.get(u), e)) && (r = !0);
                    return r || (n.selectedIndex = -1), e
                }
            }
        }
    });
    i.each(["radio", "checkbox"], function () {
        i.valHooks[this] = {
            set: function (n, t) {
                if (Array.isArray(t)) return n.checked = -1 < i.inArray(i(n).val(), t)
            }
        };
        e.checkOn || (i.valHooks[this].get = function (n) {
            return null === n.getAttribute("value") ? "on" : n.value
        })
    });
    e.focusin = "onfocusin" in n;
    er = /^(?:focusinfocus|focusoutblur)$/;
    or = function (n) {
        n.stopPropagation()
    };
    i.extend(i.event, {
        trigger: function (t, e, o, s) {
            var k, c, l, d, v, y, a, p, w = [o || f],
                h = ui.call(t, "type") ? t.type : t,
                b = ui.call(t, "namespace") ? t.namespace.split(".") : [];
            if (c = p = l = o = o || f, 3 !== o.nodeType && 8 !== o.nodeType && !er.test(h + i.event.triggered) && (-1 < h.indexOf(".") && (h = (b = h.split(".")).shift(), b.sort()), v = h.indexOf(":") < 0 && "on" + h, (t = t[i.expando] ? t : new i.Event(h, "object" == typeof t && t)).isTrigger = s ? 2 : 3, t.namespace = b.join("."), t.rnamespace = t.namespace ? new RegExp("(^|\\.)" + b.join("\\.(?:.*\\.|)") + "(\\.|$)") : null, t.result = void 0, t.target || (t.target = o), e = null == e ? [t] : i.makeArray(e, [t]), a = i.event.special[h] || {}, s || !a.trigger || !1 !== a.trigger.apply(o, e))) {
                if (!s && !a.noBubble && !rt(o)) {
                    for (d = a.delegateType || h, er.test(d + h) || (c = c.parentNode); c; c = c.parentNode) w.push(c), l = c;
                    l === (o.ownerDocument || f) && w.push(l.defaultView || l.parentWindow || n)
                }
                for (k = 0;
                    (c = w[k++]) && !t.isPropagationStopped();) p = c, t.type = 1 < k ? d : a.bindType || h, (y = (r.get(c, "events") || Object.create(null))[t.type] && r.get(c, "handle")) && y.apply(c, e), (y = v && c[v]) && y.apply && ot(c) && (t.result = y.apply(c, e), !1 === t.result && t.preventDefault());
                return t.type = h, s || t.isDefaultPrevented() || a._default && !1 !== a._default.apply(w.pop(), e) || !ot(o) || v && u(o[h]) && !rt(o) && ((l = o[v]) && (o[v] = null), i.event.triggered = h, t.isPropagationStopped() && p.addEventListener(h, or), o[h](), t.isPropagationStopped() && p.removeEventListener(h, or), i.event.triggered = void 0, l && (o[v] = l)), t.result
            }
        },
        simulate: function (n, t, r) {
            var u = i.extend(new i.Event, r, {
                type: n,
                isSimulated: !0
            });
            i.event.trigger(u, null, t)
        }
    });
    i.fn.extend({
        trigger: function (n, t) {
            return this.each(function () {
                i.event.trigger(n, t, this)
            })
        },
        triggerHandler: function (n, t) {
            var r = this[0];
            if (r) return i.event.trigger(n, t, r, !0)
        }
    });
    e.focusin || i.each({
        focus: "focusin",
        blur: "focusout"
    }, function (n, t) {
        var u = function (n) {
            i.event.simulate(t, n.target, i.event.fix(n))
        };
        i.event.special[t] = {
            setup: function () {
                var i = this.ownerDocument || this.document || this,
                    f = r.access(i, t);
                f || i.addEventListener(n, u, !0);
                r.access(i, t, (f || 0) + 1)
            },
            teardown: function () {
                var i = this.ownerDocument || this.document || this,
                    f = r.access(i, t) - 1;
                f ? r.access(i, t, f) : (i.removeEventListener(n, u, !0), r.remove(i, t))
            }
        }
    });
    var ti = n.location,
        wf = {
            guid: Date.now()
        },
        sr = /\?/;
    i.parseXML = function (t) {
        var r, u;
        if (!t || "string" != typeof t) return null;
        try {
            r = (new n.DOMParser).parseFromString(t, "text/xml")
        } catch (t) {}
        return u = r && r.getElementsByTagName("parsererror")[0], r && !u || i.error("Invalid XML: " + (u ? i.map(u.childNodes, function (n) {
            return n.textContent
        }).join("\n") : t)), r
    };
    var io = /\[\]$/,
        bf = /\r?\n/g,
        ro = /^(?:submit|button|image|reset|file)$/i,
        uo = /^(?:input|select|textarea|keygen)/i;
    i.param = function (n, t) {
        var r, f = [],
            e = function (n, t) {
                var i = u(t) ? t() : t;
                f[f.length] = encodeURIComponent(n) + "=" + encodeURIComponent(null == i ? "" : i)
            };
        if (null == n) return "";
        if (Array.isArray(n) || n.jquery && !i.isPlainObject(n)) i.each(n, function () {
            e(this.name, this.value)
        });
        else
            for (r in n) hr(r, n[r], t, e);
        return f.join("&")
    };
    i.fn.extend({
        serialize: function () {
            return i.param(this.serializeArray())
        },
        serializeArray: function () {
            return this.map(function () {
                var n = i.prop(this, "elements");
                return n ? i.makeArray(n) : this
            }).filter(function () {
                var n = this.type;
                return this.name && !i(this).is(":disabled") && uo.test(this.nodeName) && !ro.test(n) && (this.checked || !gt.test(n))
            }).map(function (n, t) {
                var r = i(this).val();
                return null == r ? null : Array.isArray(r) ? i.map(r, function (n) {
                    return {
                        name: t.name,
                        value: n.replace(bf, "\r\n")
                    }
                }) : {
                    name: t.name,
                    value: r.replace(bf, "\r\n")
                }
            }).get()
        }
    });
    var fo = /%20/g,
        eo = /#.*$/,
        oo = /([?&])_=[^&]*/,
        so = /^(.*?):[ \t]*([^\r\n]*)$/gm,
        ho = /^(?:GET|HEAD)$/,
        co = /^\/\//,
        kf = {},
        cr = {},
        df = "*/".concat("*"),
        lr = f.createElement("a");
    return lr.href = ti.href, i.extend({
        active: 0,
        lastModified: {},
        etag: {},
        ajaxSettings: {
            url: ti.href,
            type: "GET",
            isLocal: /^(?:about|app|app-storage|.+-extension|file|res|widget):$/.test(ti.protocol),
            global: !0,
            processData: !0,
            async: !0,
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            accepts: {
                "*": df,
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
                "text xml": i.parseXML
            },
            flatOptions: {
                url: !0,
                context: !0
            }
        },
        ajaxSetup: function (n, t) {
            return t ? ar(ar(n, i.ajaxSettings), t) : ar(i.ajaxSettings, n)
        },
        ajaxPrefilter: gf(kf),
        ajaxTransport: gf(cr),
        ajax: function (t, r) {
            function b(t, r, f, c) {
                var v, rt, b, p, g, l = r;
                s || (s = !0, d && n.clearTimeout(d), a = void 0, k = c || "", e.readyState = 0 < t ? 4 : 0, v = 200 <= t && t < 300 || 304 === t, f && (p = function (n, t, i) {
                    for (var e, u, f, o, s = n.contents, r = n.dataTypes;
                        "*" === r[0];) r.shift(), void 0 === e && (e = n.mimeType || t.getResponseHeader("Content-Type"));
                    if (e)
                        for (u in s)
                            if (s[u] && s[u].test(e)) {
                                r.unshift(u);
                                break
                            } if (r[0] in i) f = r[0];
                    else {
                        for (u in i) {
                            if (!r[0] || n.converters[u + " " + r[0]]) {
                                f = u;
                                break
                            }
                            o || (o = u)
                        }
                        f = f || o
                    }
                    if (f) return f !== r[0] && r.unshift(f), i[f]
                }(u, e, f)), !v && -1 < i.inArray("script", u.dataTypes) && i.inArray("json", u.dataTypes) < 0 && (u.converters["text script"] = function () {}), p = function (n, t, i, r) {
                    var h, u, f, s, e, o = {},
                        c = n.dataTypes.slice();
                    if (c[1])
                        for (f in n.converters) o[f.toLowerCase()] = n.converters[f];
                    for (u = c.shift(); u;)
                        if (n.responseFields[u] && (i[n.responseFields[u]] = t), !e && r && n.dataFilter && (t = n.dataFilter(t, n.dataType)), e = u, u = c.shift())
                            if ("*" === u) u = e;
                            else if ("*" !== e && e !== u) {
                        if (!(f = o[e + " " + u] || o["* " + u]))
                            for (h in o)
                                if ((s = h.split(" "))[1] === u && (f = o[e + " " + s[0]] || o["* " + s[0]])) {
                                    !0 === f ? f = o[h] : !0 !== o[h] && (u = s[0], c.unshift(s[1]));
                                    break
                                } if (!0 !== f)
                            if (f && n.throws) t = f(t);
                            else try {
                                t = f(t)
                            } catch (n) {
                                return {
                                    state: "parsererror",
                                    error: f ? n : "No conversion from " + e + " to " + u
                                }
                            }
                    }
                    return {
                        state: "success",
                        data: t
                    }
                }(u, p, e, v), v ? (u.ifModified && ((g = e.getResponseHeader("Last-Modified")) && (i.lastModified[o] = g), (g = e.getResponseHeader("etag")) && (i.etag[o] = g)), 204 === t || "HEAD" === u.type ? l = "nocontent" : 304 === t ? l = "notmodified" : (l = p.state, rt = p.data, v = !(b = p.error))) : (b = l, !t && l || (l = "error", t < 0 && (t = 0))), e.status = t, e.statusText = (r || l) + "", v ? tt.resolveWith(h, [rt, l, e]) : tt.rejectWith(h, [e, l, b]), e.statusCode(w), w = void 0, y && nt.trigger(v ? "ajaxSuccess" : "ajaxError", [e, u, v ? rt : b]), it.fireWith(h, [e, l]), y && (nt.trigger("ajaxComplete", [e, u]), --i.active || i.event.trigger("ajaxStop")))
            }
            "object" == typeof t && (r = t, t = void 0);
            r = r || {};
            var a, o, k, v, d, c, s, y, g, p, u = i.ajaxSetup({}, r),
                h = u.context || u,
                nt = u.context && (h.nodeType || h.jquery) ? i(h) : i.event,
                tt = i.Deferred(),
                it = i.Callbacks("once memory"),
                w = u.statusCode || {},
                rt = {},
                ut = {},
                ft = "canceled",
                e = {
                    readyState: 0,
                    getResponseHeader: function (n) {
                        var t;
                        if (s) {
                            if (!v)
                                for (v = {}; t = so.exec(k);) v[t[1].toLowerCase() + " "] = (v[t[1].toLowerCase() + " "] || []).concat(t[2]);
                            t = v[n.toLowerCase() + " "]
                        }
                        return null == t ? null : t.join(", ")
                    },
                    getAllResponseHeaders: function () {
                        return s ? k : null
                    },
                    setRequestHeader: function (n, t) {
                        return null == s && (n = ut[n.toLowerCase()] = ut[n.toLowerCase()] || n, rt[n] = t), this
                    },
                    overrideMimeType: function (n) {
                        return null == s && (u.mimeType = n), this
                    },
                    statusCode: function (n) {
                        var t;
                        if (n)
                            if (s) e.always(n[e.status]);
                            else
                                for (t in n) w[t] = [w[t], n[t]];
                        return this
                    },
                    abort: function (n) {
                        var t = n || ft;
                        return a && a.abort(t), b(0, t), this
                    }
                };
            if (tt.promise(e), u.url = ((t || u.url || ti.href) + "").replace(co, ti.protocol + "//"), u.type = r.method || r.type || u.method || u.type, u.dataTypes = (u.dataType || "*").toLowerCase().match(l) || [""], null == u.crossDomain) {
                c = f.createElement("a");
                try {
                    c.href = u.url;
                    c.href = c.href;
                    u.crossDomain = lr.protocol + "//" + lr.host != c.protocol + "//" + c.host
                } catch (t) {
                    u.crossDomain = !0
                }
            }
            if (u.data && u.processData && "string" != typeof u.data && (u.data = i.param(u.data, u.traditional)), ne(kf, u, r, e), s) return e;
            for (g in (y = i.event && u.global) && 0 == i.active++ && i.event.trigger("ajaxStart"), u.type = u.type.toUpperCase(), u.hasContent = !ho.test(u.type), o = u.url.replace(eo, ""), u.hasContent ? u.data && u.processData && 0 === (u.contentType || "").indexOf("application/x-www-form-urlencoded") && (u.data = u.data.replace(fo, "+")) : (p = u.url.slice(o.length), u.data && (u.processData || "string" == typeof u.data) && (o += (sr.test(o) ? "&" : "?") + u.data, delete u.data), !1 === u.cache && (o = o.replace(oo, "$1"), p = (sr.test(o) ? "&" : "?") + "_=" + wf.guid++ + p), u.url = o + p), u.ifModified && (i.lastModified[o] && e.setRequestHeader("If-Modified-Since", i.lastModified[o]), i.etag[o] && e.setRequestHeader("If-None-Match", i.etag[o])), (u.data && u.hasContent && !1 !== u.contentType || r.contentType) && e.setRequestHeader("Content-Type", u.contentType), e.setRequestHeader("Accept", u.dataTypes[0] && u.accepts[u.dataTypes[0]] ? u.accepts[u.dataTypes[0]] + ("*" !== u.dataTypes[0] ? ", " + df + "; q=0.01" : "") : u.accepts["*"]), u.headers) e.setRequestHeader(g, u.headers[g]);
            if (u.beforeSend && (!1 === u.beforeSend.call(h, e, u) || s)) return e.abort();
            if (ft = "abort", it.add(u.complete), e.done(u.success), e.fail(u.error), a = ne(cr, u, r, e)) {
                if (e.readyState = 1, y && nt.trigger("ajaxSend", [e, u]), s) return e;
                u.async && 0 < u.timeout && (d = n.setTimeout(function () {
                    e.abort("timeout")
                }, u.timeout));
                try {
                    s = !1;
                    a.send(rt, b)
                } catch (t) {
                    if (s) throw t;
                    b(-1, t)
                }
            } else b(-1, "No Transport");
            return e
        },
        getJSON: function (n, t, r) {
            return i.get(n, t, r, "json")
        },
        getScript: function (n, t) {
            return i.get(n, void 0, t, "script")
        }
    }), i.each(["get", "post"], function (n, t) {
        i[t] = function (n, r, f, e) {
            return u(r) && (e = e || f, f = r, r = void 0), i.ajax(i.extend({
                url: n,
                type: t,
                dataType: e,
                data: r,
                success: f
            }, i.isPlainObject(n) && n))
        }
    }), i.ajaxPrefilter(function (n) {
        for (var t in n.headers) "content-type" === t.toLowerCase() && (n.contentType = n.headers[t] || "")
    }), i._evalUrl = function (n, t, r) {
        return i.ajax({
            url: n,
            type: "GET",
            dataType: "script",
            cache: !0,
            async: !1,
            global: !1,
            converters: {
                "text script": function () {}
            },
            dataFilter: function (n) {
                i.globalEval(n, t, r)
            }
        })
    }, i.fn.extend({
        wrapAll: function (n) {
            var t;
            return this[0] && (u(n) && (n = n.call(this[0])), t = i(n, this[0].ownerDocument).eq(0).clone(!0), this[0].parentNode && t.insertBefore(this[0]), t.map(function () {
                for (var n = this; n.firstElementChild;) n = n.firstElementChild;
                return n
            }).append(this)), this
        },
        wrapInner: function (n) {
            return u(n) ? this.each(function (t) {
                i(this).wrapInner(n.call(this, t))
            }) : this.each(function () {
                var t = i(this),
                    r = t.contents();
                r.length ? r.wrapAll(n) : t.append(n)
            })
        },
        wrap: function (n) {
            var t = u(n);
            return this.each(function (r) {
                i(this).wrapAll(t ? n.call(this, r) : n)
            })
        },
        unwrap: function (n) {
            return this.parent(n).not("body").each(function () {
                i(this).replaceWith(this.childNodes)
            }), this
        }
    }), i.expr.pseudos.hidden = function (n) {
        return !i.expr.pseudos.visible(n)
    }, i.expr.pseudos.visible = function (n) {
        return !!(n.offsetWidth || n.offsetHeight || n.getClientRects().length)
    }, i.ajaxSettings.xhr = function () {
        try {
            return new n.XMLHttpRequest
        } catch (t) {}
    }, te = {
        0: 200,
        1223: 204
    }, wt = i.ajaxSettings.xhr(), e.cors = !!wt && "withCredentials" in wt, e.ajax = wt = !!wt, i.ajaxTransport(function (t) {
        var i, r;
        if (e.cors || wt && !t.crossDomain) return {
            send: function (u, f) {
                var o, e = t.xhr();
                if (e.open(t.type, t.url, t.async, t.username, t.password), t.xhrFields)
                    for (o in t.xhrFields) e[o] = t.xhrFields[o];
                for (o in t.mimeType && e.overrideMimeType && e.overrideMimeType(t.mimeType), t.crossDomain || u["X-Requested-With"] || (u["X-Requested-With"] = "XMLHttpRequest"), u) e.setRequestHeader(o, u[o]);
                i = function (n) {
                    return function () {
                        i && (i = r = e.onload = e.onerror = e.onabort = e.ontimeout = e.onreadystatechange = null, "abort" === n ? e.abort() : "error" === n ? "number" != typeof e.status ? f(0, "error") : f(e.status, e.statusText) : f(te[e.status] || e.status, e.statusText, "text" !== (e.responseType || "text") || "string" != typeof e.responseText ? {
                            binary: e.response
                        } : {
                            text: e.responseText
                        }, e.getAllResponseHeaders()))
                    }
                };
                e.onload = i();
                r = e.onerror = e.ontimeout = i("error");
                void 0 !== e.onabort ? e.onabort = r : e.onreadystatechange = function () {
                    4 === e.readyState && n.setTimeout(function () {
                        i && r()
                    })
                };
                i = i("abort");
                try {
                    e.send(t.hasContent && t.data || null)
                } catch (u) {
                    if (i) throw u;
                }
            },
            abort: function () {
                i && i()
            }
        }
    }), i.ajaxPrefilter(function (n) {
        n.crossDomain && (n.contents.script = !1)
    }), i.ajaxSetup({
        accepts: {
            script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"
        },
        contents: {
            script: /\b(?:java|ecma)script\b/
        },
        converters: {
            "text script": function (n) {
                return i.globalEval(n), n
            }
        }
    }), i.ajaxPrefilter("script", function (n) {
        void 0 === n.cache && (n.cache = !1);
        n.crossDomain && (n.type = "GET")
    }), i.ajaxTransport("script", function (n) {
        var r, t;
        if (n.crossDomain || n.scriptAttrs) return {
            send: function (u, e) {
                r = i("<script>").attr(n.scriptAttrs || {}).prop({
                    charset: n.scriptCharset,
                    src: n.url
                }).on("load error", t = function (n) {
                    r.remove();
                    t = null;
                    n && e("error" === n.type ? 404 : 200, n.type)
                });
                f.head.appendChild(r[0])
            },
            abort: function () {
                t && t()
            }
        }
    }), vr = [], vi = /(=)\?(?=&|$)|\?\?/, i.ajaxSetup({
        jsonp: "callback",
        jsonpCallback: function () {
            var n = vr.pop() || i.expando + "_" + wf.guid++;
            return this[n] = !0, n
        }
    }), i.ajaxPrefilter("json jsonp", function (t, r, f) {
        var e, o, s, h = !1 !== t.jsonp && (vi.test(t.url) ? "url" : "string" == typeof t.data && 0 === (t.contentType || "").indexOf("application/x-www-form-urlencoded") && vi.test(t.data) && "data");
        if (h || "jsonp" === t.dataTypes[0]) return e = t.jsonpCallback = u(t.jsonpCallback) ? t.jsonpCallback() : t.jsonpCallback, h ? t[h] = t[h].replace(vi, "$1" + e) : !1 !== t.jsonp && (t.url += (sr.test(t.url) ? "&" : "?") + t.jsonp + "=" + e), t.converters["script json"] = function () {
            return s || i.error(e + " was not called"), s[0]
        }, t.dataTypes[0] = "json", o = n[e], n[e] = function () {
            s = arguments
        }, f.always(function () {
            void 0 === o ? i(n).removeProp(e) : n[e] = o;
            t[e] && (t.jsonpCallback = r.jsonpCallback, vr.push(e));
            s && u(o) && o(s[0]);
            s = o = void 0
        }), "script"
    }), e.createHTMLDocument = ((ie = f.implementation.createHTMLDocument("").body).innerHTML = "<form><\/form><form><\/form>", 2 === ie.childNodes.length), i.parseHTML = function (n, t, r) {
        return "string" != typeof n ? [] : ("boolean" == typeof t && (r = t, t = !1), t || (e.createHTMLDocument ? ((s = (t = f.implementation.createHTMLDocument("")).createElement("base")).href = f.location.href, t.head.appendChild(s)) : t = f), u = !r && [], (o = wi.exec(n)) ? [t.createElement(o[1])] : (o = yu([n], t, u), u && u.length && i(u).remove(), i.merge([], o.childNodes)));
        var s, o, u
    }, i.fn.load = function (n, t, r) {
        var f, s, h, e = this,
            o = n.indexOf(" ");
        return -1 < o && (f = tt(n.slice(o)), n = n.slice(0, o)), u(t) ? (r = t, t = void 0) : t && "object" == typeof t && (s = "POST"), 0 < e.length && i.ajax({
            url: n,
            type: s || "GET",
            dataType: "html",
            data: t
        }).done(function (n) {
            h = arguments;
            e.html(f ? i("<div>").append(i.parseHTML(n)).find(f) : n)
        }).always(r && function (n, t) {
            e.each(function () {
                r.apply(this, h || [n.responseText, t, n])
            })
        }), this
    }, i.expr.pseudos.animated = function (n) {
        return i.grep(i.timers, function (t) {
            return n === t.elem
        }).length
    }, i.offset = {
        setOffset: function (n, t, r) {
            var v, o, s, h, f, c, l = i.css(n, "position"),
                a = i(n),
                e = {};
            "static" === l && (n.style.position = "relative");
            f = a.offset();
            s = i.css(n, "top");
            c = i.css(n, "left");
            ("absolute" === l || "fixed" === l) && -1 < (s + c).indexOf("auto") ? (h = (v = a.position()).top, o = v.left) : (h = parseFloat(s) || 0, o = parseFloat(c) || 0);
            u(t) && (t = t.call(n, r, i.extend({}, f)));
            null != t.top && (e.top = t.top - f.top + h);
            null != t.left && (e.left = t.left - f.left + o);
            "using" in t ? t.using.call(n, e) : a.css(e)
        }
    }, i.fn.extend({
        offset: function (n) {
            if (arguments.length) return void 0 === n ? this : this.each(function (t) {
                i.offset.setOffset(this, n, t)
            });
            var r, u, t = this[0];
            if (t) return t.getClientRects().length ? (r = t.getBoundingClientRect(), u = t.ownerDocument.defaultView, {
                top: r.top + u.pageYOffset,
                left: r.left + u.pageXOffset
            }) : {
                top: 0,
                left: 0
            }
        },
        position: function () {
            if (this[0]) {
                var n, r, u, t = this[0],
                    f = {
                        top: 0,
                        left: 0
                    };
                if ("fixed" === i.css(t, "position")) r = t.getBoundingClientRect();
                else {
                    for (r = this.offset(), u = t.ownerDocument, n = t.offsetParent || u.documentElement; n && (n === u.body || n === u.documentElement) && "static" === i.css(n, "position");) n = n.parentNode;
                    n && n !== t && 1 === n.nodeType && ((f = i(n).offset()).top += i.css(n, "borderTopWidth", !0), f.left += i.css(n, "borderLeftWidth", !0))
                }
                return {
                    top: r.top - f.top - i.css(t, "marginTop", !0),
                    left: r.left - f.left - i.css(t, "marginLeft", !0)
                }
            }
        },
        offsetParent: function () {
            return this.map(function () {
                for (var n = this.offsetParent; n && "static" === i.css(n, "position");) n = n.offsetParent;
                return n || g
            })
        }
    }), i.each({
        scrollLeft: "pageXOffset",
        scrollTop: "pageYOffset"
    }, function (n, t) {
        var r = "pageYOffset" === t;
        i.fn[n] = function (i) {
            return w(this, function (n, i, u) {
                var f;
                if (rt(n) ? f = n : 9 === n.nodeType && (f = n.defaultView), void 0 === u) return f ? f[t] : n[i];
                f ? f.scrollTo(r ? f.pageXOffset : u, r ? u : f.pageYOffset) : n[i] = u
            }, n, i, arguments.length)
        }
    }), i.each(["top", "left"], function (n, t) {
        i.cssHooks[t] = du(e.pixelPosition, function (n, r) {
            if (r) return r = ni(n, t), tr.test(r) ? i(n).position()[t] + "px" : r
        })
    }), i.each({
        Height: "height",
        Width: "width"
    }, function (n, t) {
        i.each({
            padding: "inner" + n,
            content: t,
            "": "outer" + n
        }, function (r, u) {
            i.fn[u] = function (f, e) {
                var o = arguments.length && (r || "boolean" != typeof f),
                    s = r || (!0 === f || !0 === e ? "margin" : "border");
                return w(this, function (t, r, f) {
                    var e;
                    return rt(t) ? 0 === u.indexOf("outer") ? t["inner" + n] : t.document.documentElement["client" + n] : 9 === t.nodeType ? (e = t.documentElement, Math.max(t.body["scroll" + n], e["scroll" + n], t.body["offset" + n], e["offset" + n], e["client" + n])) : void 0 === f ? i.css(t, r, s) : i.style(t, r, f, s)
                }, t, o ? f : void 0, o)
            }
        })
    }), i.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"], function (n, t) {
        i.fn[t] = function (n) {
            return this.on(t, n)
        }
    }), i.fn.extend({
        bind: function (n, t, i) {
            return this.on(n, null, t, i)
        },
        unbind: function (n, t) {
            return this.off(n, null, t)
        },
        delegate: function (n, t, i, r) {
            return this.on(t, n, i, r)
        },
        undelegate: function (n, t, i) {
            return 1 === arguments.length ? this.off(n, "**") : this.off(t, n || "**", i)
        },
        hover: function (n, t) {
            return this.mouseenter(n).mouseleave(t || n)
        }
    }), i.each("blur focus focusin focusout resize scroll click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup contextmenu".split(" "), function (n, t) {
        i.fn[t] = function (n, i) {
            return 0 < arguments.length ? this.on(t, null, n, i) : this.trigger(t)
        }
    }), re = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, i.proxy = function (n, t) {
        var r, f, e;
        if ("string" == typeof t && (r = n[t], t = n, n = r), u(n)) return f = k.call(arguments, 2), (e = function () {
            return n.apply(t || this, f.concat(k.call(arguments)))
        }).guid = n.guid = n.guid || i.guid++, e
    }, i.holdReady = function (n) {
        n ? i.readyWait++ : i.ready(!0)
    }, i.isArray = Array.isArray, i.parseJSON = JSON.parse, i.nodeName = c, i.isFunction = u, i.isWindow = rt, i.camelCase = y, i.type = ut, i.now = Date.now, i.isNumeric = function (n) {
        var t = i.type(n);
        return ("number" === t || "string" === t) && !isNaN(n - parseFloat(n))
    }, i.trim = function (n) {
        return null == n ? "" : (n + "").replace(re, "")
    }, "function" == typeof define && define.amd && define("jquery", [], function () {
        return i
    }), ue = n.jQuery, fe = n.$, i.noConflict = function (t) {
        return n.$ === i && (n.$ = fe), t && n.jQuery === i && (n.jQuery = ue), i
    }, "undefined" == typeof t && (n.jQuery = n.$ = i), i
}),
function (n) {
    "use strict";

    function a() {
        function n(t) {
            var r = this;
            if (!(r instanceof n)) return t === i ? a() : new n(t);
            if (t instanceof n) r.s = t.s, r.e = t.e, r.c = t.c.slice();
            else {
                if (typeof t != "string") {
                    if (n.strict === !0) throw TypeError(f + "number");
                    t = t === 0 && 1 / t < 0 ? "-0" : String(t)
                }
                g(r, t)
            }
            r.constructor = n
        }
        return n.prototype = t, n.DP = v, n.RM = y, n.NE = p, n.PE = w, n.strict = b, n.roundDown = 0, n.roundHalfUp = 1, n.roundHalfEven = 2, n.roundUp = 3, n
    }

    function g(n, t) {
        var r, i, u;
        if (!d.test(t)) throw Error(f + "number");
        for (n.s = t.charAt(0) == "-" ? (t = t.slice(1), -1) : 1, (r = t.indexOf(".")) > -1 && (t = t.replace(".", "")), (i = t.search(/e/i)) > 0 ? (r < 0 && (r = i), r += +t.slice(i + 1), t = t.substring(0, i)) : r < 0 && (r = t.length), u = t.length, i = 0; i < u && t.charAt(i) == "0";) ++i;
        if (i == u) n.c = [n.e = 0];
        else {
            for (; u > 0 && t.charAt(--u) == "0";);
            for (n.e = r - i - 1, n.c = [], r = 0; i <= u;) n.c[r++] = +t.charAt(i++)
        }
        return n
    }

    function e(n, t, r, u) {
        var f = n.c;
        if (r === i && (r = n.constructor.RM), r !== 0 && r !== 1 && r !== 2 && r !== 3) throw Error(k);
        if (t < 1) u = r === 3 && (u || !!f[0]) || t === 0 && (r === 1 && f[0] >= 5 || r === 2 && (f[0] > 5 || f[0] === 5 && (u || f[1] !== i))), f.length = 1, u ? (n.e = n.e - t + 1, f[0] = 1) : f[0] = n.e = 0;
        else if (t < f.length) {
            if (u = r === 1 && f[t] >= 5 || r === 2 && (f[t] > 5 || f[t] === 5 && (u || f[t + 1] !== i || f[t - 1] & 1)) || r === 3 && (u || !!f[0]), f.length = t--, u)
                for (; ++f[t] > 9;) f[t] = 0, t-- || (++n.e, f.unshift(1));
            for (t = f.length; !f[--t];) f.pop()
        }
        return n
    }

    function o(n, t, i) {
        var u = n.e,
            r = n.c.join(""),
            f = r.length;
        if (t) r = r.charAt(0) + (f > 1 ? "." + r.slice(1) : "") + (u < 0 ? "e" : "e+") + u;
        else if (u < 0) {
            for (; ++u;) r = "0" + r;
            r = "0." + r
        } else if (u > 0)
            if (++u > f)
                for (u -= f; u--;) r += "0";
            else u < f && (r = r.slice(0, u) + "." + r.slice(u));
        else f > 1 && (r = r.charAt(0) + "." + r.slice(1));
        return n.s < 0 && i ? "-" + r : r
    }
    var r, v = 20,
        y = 1,
        u = 1e6,
        c = 1e6,
        p = -7,
        w = 21,
        b = !1,
        s = "[big.js] ",
        f = s + "Invalid ",
        h = f + "decimal places",
        k = f + "rounding mode",
        l = s + "Division by zero",
        t = {},
        i = void 0,
        d = /^-?(\d+(\.\d*)?|\.\d+)(e[+-]?\d+)?$/i;
    t.abs = function () {
        var n = new this.constructor(this);
        return n.s = 1, n
    };
    t.cmp = function (n) {
        var e, o = this,
            u = o.c,
            f = (n = new o.constructor(n)).c,
            t = o.s,
            s = n.s,
            i = o.e,
            r = n.e;
        if (!u[0] || !f[0]) return u[0] ? t : f[0] ? -s : 0;
        if (t != s) return t;
        if (e = t < 0, i != r) return i > r ^ e ? 1 : -1;
        for (s = (i = u.length) < (r = f.length) ? i : r, t = -1; ++t < s;)
            if (u[t] != f[t]) return u[t] > f[t] ^ e ? 1 : -1;
        return i == r ? 0 : i > r ^ e ? 1 : -1
    };
    t.div = function (n) {
        var b = this,
            d = b.constructor,
            v = b.c,
            s = (n = new d(n)).c,
            k = b.s == n.s ? 1 : -1,
            y = d.DP;
        if (y !== ~~y || y < 0 || y > u) throw Error(h);
        if (!s[0]) throw Error(l);
        if (!v[0]) return n.s = k, n.c = [n.e = 0], n;
        var o, g, p, c, f, rt = s.slice(),
            nt = o = s.length,
            ut = v.length,
            t = v.slice(0, o),
            r = t.length,
            a = n,
            tt = a.c = [],
            it = 0,
            w = y + (a.e = b.e - n.e) + 1;
        for (a.s = k, k = w < 0 ? 0 : w, rt.unshift(0); r++ < o;) t.push(0);
        do {
            for (p = 0; p < 10; p++) {
                if (o != (r = t.length)) c = o > r ? 1 : -1;
                else
                    for (f = -1, c = 0; ++f < o;)
                        if (s[f] != t[f]) {
                            c = s[f] > t[f] ? 1 : -1;
                            break
                        } if (c < 0) {
                    for (g = r == o ? s : rt; r;) {
                        if (t[--r] < g[r]) {
                            for (f = r; f && !t[--f];) t[f] = 9;
                            --t[f];
                            t[r] += 10
                        }
                        t[r] -= g[r]
                    }
                    for (; !t[0];) t.shift()
                } else break
            }
            tt[it++] = c ? p : ++p;
            t[0] && c ? t[r] = v[nt] || 0 : t = [v[nt]]
        } while ((nt++ < ut || t[0] !== i) && k--);
        return tt[0] || it == 1 || (tt.shift(), a.e--, w--), it > w && e(a, w, d.RM, t[0] !== i), a
    };
    t.eq = function (n) {
        return this.cmp(n) === 0
    };
    t.gt = function (n) {
        return this.cmp(n) > 0
    };
    t.gte = function (n) {
        return this.cmp(n) > -1
    };
    t.lt = function (n) {
        return this.cmp(n) < 0
    };
    t.lte = function (n) {
        return this.cmp(n) < 1
    };
    t.minus = t.sub = function (n) {
        var f, u, o, c, s = this,
            l = s.constructor,
            e = s.s,
            i = (n = new l(n)).s;
        if (e != i) return n.s = -i, s.plus(n);
        var t = s.c.slice(),
            a = s.e,
            r = n.c,
            h = n.e;
        if (!t[0] || !r[0]) return r[0] ? n.s = -i : t[0] ? n = new l(s) : n.s = 1, n;
        if (e = a - h) {
            for ((c = e < 0) ? (e = -e, o = t) : (h = a, o = r), o.reverse(), i = e; i--;) o.push(0);
            o.reverse()
        } else
            for (u = ((c = t.length < r.length) ? t : r).length, e = i = 0; i < u; i++)
                if (t[i] != r[i]) {
                    c = t[i] < r[i];
                    break
                } if (c && (o = t, t = r, r = o, n.s = -n.s), (i = (u = r.length) - (f = t.length)) > 0)
            for (; i--;) t[f++] = 0;
        for (i = f; u > e;) {
            if (t[--u] < r[u]) {
                for (f = u; f && !t[--f];) t[f] = 9;
                --t[f];
                t[u] += 10
            }
            t[u] -= r[u]
        }
        for (; t[--i] === 0;) t.pop();
        for (; t[0] === 0;) t.shift(), --h;
        return t[0] || (n.s = 1, t = [h = 0]), n.c = t, n.e = h, n
    };
    t.mod = function (n) {
        var f, t = this,
            i = t.constructor,
            r = t.s,
            u = (n = new i(n)).s;
        if (!n.c[0]) throw Error(l);
        return (t.s = n.s = 1, f = n.cmp(t) == 1, t.s = r, n.s = u, f) ? new i(t) : (r = i.DP, u = i.RM, i.DP = i.RM = 0, t = t.div(n), i.DP = r, i.RM = u, this.minus(t.times(n)))
    };
    t.plus = t.add = function (n) {
        var i, e, u, f = this,
            s = f.constructor;
        if (n = new s(n), f.s != n.s) return n.s = -n.s, f.minus(n);
        var h = f.e,
            t = f.c,
            o = n.e,
            r = n.c;
        if (!t[0] || !r[0]) return r[0] || (t[0] ? n = new s(f) : n.s = f.s), n;
        if (t = t.slice(), i = h - o) {
            for (i > 0 ? (o = h, u = r) : (i = -i, u = t), u.reverse(); i--;) u.push(0);
            u.reverse()
        }
        for (t.length - r.length < 0 && (u = r, r = t, t = u), i = r.length, e = 0; i; t[i] %= 10) e = (t[--i] = t[i] + r[i] + e) / 10 | 0;
        for (e && (t.unshift(e), ++o), i = t.length; t[--i] === 0;) t.pop();
        return n.c = t, n.e = o, n
    };
    t.pow = function (n) {
        var t = this,
            r = new t.constructor("1"),
            i = r,
            u = n < 0;
        if (n !== ~~n || n < -c || n > c) throw Error(f + "exponent");
        for (u && (n = -n);;) {
            if (n & 1 && (i = i.times(t)), n >>= 1, !n) break;
            t = t.times(t)
        }
        return u ? r.div(i) : i
    };
    t.prec = function (n, t) {
        if (n !== ~~n || n < 1 || n > u) throw Error(f + "precision");
        return e(new this.constructor(this), n, t)
    };
    t.round = function (n, t) {
        if (n === i) n = 0;
        else if (n !== ~~n || n < -u || n > u) throw Error(h);
        return e(new this.constructor(this), n + this.e + 1, t)
    };
    t.sqrt = function () {
        var i, f, o, r = this,
            u = r.constructor,
            n = r.s,
            t = r.e,
            h = new u("0.5");
        if (!r.c[0]) return new u(r);
        if (n < 0) throw Error(s + "No square root");
        n = Math.sqrt(r + "");
        n === 0 || n === 1 / 0 ? (f = r.c.join(""), f.length + t & 1 || (f += "0"), n = Math.sqrt(f), t = ((t + 1) / 2 | 0) - (t < 0 || t & 1), i = new u((n == 1 / 0 ? "5e" : (n = n.toExponential()).slice(0, n.indexOf("e") + 1)) + t)) : i = new u(n + "");
        t = i.e + (u.DP += 4);
        do o = i, i = h.times(o.plus(r.div(o))); while (o.c.slice(0, t).join("") !== i.c.slice(0, t).join(""));
        return e(i, (u.DP -= 4) + i.e + 1, u.RM)
    };
    t.times = t.mul = function (n) {
        var i, s = this,
            h = s.constructor,
            f = s.c,
            e = (n = new h(n)).c,
            o = f.length,
            t = e.length,
            u = s.e,
            r = n.e;
        if (n.s = s.s == n.s ? 1 : -1, !f[0] || !e[0]) return n.c = [n.e = 0], n;
        for (n.e = u + r, o < t && (i = f, f = e, e = i, r = o, o = t, t = r), i = new Array(r = o + t); r--;) i[r] = 0;
        for (u = t; u--;) {
            for (t = 0, r = o + u; r > u;) t = i[r] + e[u] * f[r - u - 1] + t, i[r--] = t % 10, t = t / 10 | 0;
            i[r] = t
        }
        for (t ? ++n.e : i.shift(), u = i.length; !i[--u];) i.pop();
        return n.c = i, n
    };
    t.toExponential = function (n, t) {
        var r = this,
            f = r.c[0];
        if (n !== i) {
            if (n !== ~~n || n < 0 || n > u) throw Error(h);
            for (r = e(new r.constructor(r), ++n, t); r.c.length < n;) r.c.push(0)
        }
        return o(r, !0, !!f)
    };
    t.toFixed = function (n, t) {
        var r = this,
            f = r.c[0];
        if (n !== i) {
            if (n !== ~~n || n < 0 || n > u) throw Error(h);
            for (r = e(new r.constructor(r), n + r.e + 1, t), n = n + r.e + 1; r.c.length < n;) r.c.push(0)
        }
        return o(r, !1, !!f)
    };
    t.toJSON = t.toString = function () {
        var n = this,
            t = n.constructor;
        return o(n, n.e <= t.NE || n.e >= t.PE, !!n.c[0])
    };
    t.toNumber = function () {
        var n = Number(o(this, !0, !0));
        if (this.constructor.strict === !0 && !this.eq(n.toString())) throw Error(s + "Imprecise conversion");
        return n
    };
    t.toPrecision = function (n, t) {
        var r = this,
            s = r.constructor,
            h = r.c[0];
        if (n !== i) {
            if (n !== ~~n || n < 1 || n > u) throw Error(f + "precision");
            for (r = e(new s(r), n, t); r.c.length < n;) r.c.push(0)
        }
        return o(r, n <= r.e || r.e <= s.NE || r.e >= s.PE, !!h)
    };
    t.valueOf = function () {
        var n = this,
            t = n.constructor;
        if (t.strict === !0) throw Error(s + "valueOf disallowed");
        return o(n, n.e <= t.NE || n.e >= t.PE, !0)
    };
    r = a();
    r["default"] = r.Big = r;
    typeof define == "function" && define.amd ? define(function () {
        return r
    }) : typeof module != "undefined" && module.exports ? module.exports = r : n.Big = r
}(this);
! function (n, t) {
    "object" == typeof exports && "object" == typeof module ? module.exports = t() : "function" == typeof define && define.amd ? define([], t) : "object" == typeof exports ? exports.Handlebars = t() : n.Handlebars = t()
}(this, function () {
    return function (n) {
        function t(r) {
            if (i[r]) return i[r].exports;
            var u = i[r] = {
                exports: {},
                id: r,
                loaded: !1
            };
            return n[r].call(u.exports, u, u.exports, t), u.loaded = !0, u.exports
        }
        var i = {};
        return t.m = n, t.c = i, t.p = "", t(0)
    }([function (n, t, i) {
        "use strict";

        function o() {
            var n = k();
            return n.compile = function (t, i) {
                return e.compile(t, i, n)
            }, n.precompile = function (t, i) {
                return e.precompile(t, i, n)
            }, n.AST = l["default"], n.Compiler = e.Compiler, n.JavaScriptCompiler = v["default"], n.Parser = f.parser, n.parse = f.parse, n.parseWithoutProcessing = f.parseWithoutProcessing, n
        }
        var u = i(1)["default"];
        t.__esModule = !0;
        var s = i(2),
            h = u(s),
            c = i(45),
            l = u(c),
            f = i(46),
            e = i(51),
            a = i(52),
            v = u(a),
            y = i(49),
            p = u(y),
            w = i(44),
            b = u(w),
            k = h["default"].create,
            r = o();
        r.create = o;
        b["default"](r);
        r.Visitor = p["default"];
        r["default"] = r;
        t["default"] = r;
        n.exports = t["default"]
    }, function (n, t) {
        "use strict";
        t["default"] = function (n) {
            return n && n.__esModule ? n : {
                "default": n
            }
        };
        t.__esModule = !0
    }, function (n, t, i) {
        "use strict";

        function o() {
            var n = new s.HandlebarsEnvironment;
            return e.extend(n, s), n.SafeString = a["default"], n.Exception = y["default"], n.Utils = e, n.escapeExpression = e.escapeExpression, n.VM = h, n.template = function (t) {
                return h.template(t, n)
            }, n
        }
        var u = i(3)["default"],
            f = i(1)["default"];
        t.__esModule = !0;
        var c = i(4),
            s = u(c),
            l = i(37),
            a = f(l),
            v = i(6),
            y = f(v),
            p = i(5),
            e = u(p),
            w = i(38),
            h = u(w),
            b = i(44),
            k = f(b),
            r = o();
        r.create = o;
        k["default"](r);
        r["default"] = r;
        t["default"] = r;
        n.exports = t["default"]
    }, function (n, t) {
        "use strict";
        t["default"] = function (n) {
            var t, i;
            if (n && n.__esModule) return n;
            if (t = {}, null != n)
                for (i in n) Object.prototype.hasOwnProperty.call(n, i) && (t[i] = n[i]);
            return t["default"] = n, t
        };
        t.__esModule = !0
    }, function (n, t, i) {
        "use strict";

        function e(n, t, i) {
            this.helpers = n || {};
            this.partials = t || {};
            this.decorators = i || {};
            y.registerDefaultHelpers(this);
            p.registerDefaultDecorators(this)
        }
        var s = i(1)["default"],
            h, c, l, f, a;
        t.__esModule = !0;
        t.HandlebarsEnvironment = e;
        var r = i(5),
            v = i(6),
            o = s(v),
            y = i(10),
            p = i(30),
            w = i(32),
            u = s(w),
            b = i(33);
        t.VERSION = "4.7.7";
        h = 8;
        t.COMPILER_REVISION = h;
        c = 7;
        t.LAST_COMPATIBLE_COMPILER_REVISION = c;
        l = {
            1: "<= 1.0.rc.2",
            2: "== 1.0.0-rc.3",
            3: "== 1.0.0-rc.4",
            4: "== 1.x.x",
            5: "== 2.0.0-alpha.x",
            6: ">= 2.0.0-beta.1",
            7: ">= 4.0.0 <4.3.0",
            8: ">= 4.3.0"
        };
        t.REVISION_CHANGES = l;
        f = "[object Object]";
        e.prototype = {
            constructor: e,
            logger: u["default"],
            log: u["default"].log,
            registerHelper: function (n, t) {
                if (r.toString.call(n) === f) {
                    if (t) throw new o["default"]("Arg not supported with multiple helpers");
                    r.extend(this.helpers, n)
                } else this.helpers[n] = t
            },
            unregisterHelper: function (n) {
                delete this.helpers[n]
            },
            registerPartial: function (n, t) {
                if (r.toString.call(n) === f) r.extend(this.partials, n);
                else {
                    if ("undefined" == typeof t) throw new o["default"]('Attempting to register a partial called "' + n + '" as undefined');
                    this.partials[n] = t
                }
            },
            unregisterPartial: function (n) {
                delete this.partials[n]
            },
            registerDecorator: function (n, t) {
                if (r.toString.call(n) === f) {
                    if (t) throw new o["default"]("Arg not supported with multiple decorators");
                    r.extend(this.decorators, n)
                } else this.decorators[n] = t
            },
            unregisterDecorator: function (n) {
                delete this.decorators[n]
            },
            resetLoggedPropertyAccesses: function () {
                b.resetLoggedProperties()
            }
        };
        a = u["default"].log;
        t.log = a;
        t.createFrame = r.createFrame;
        t.logger = u["default"]
    }, function (n, t) {
        "use strict";

        function e(n) {
            return v[n]
        }

        function f(n) {
            for (var i, t = 1; t < arguments.length; t++)
                for (i in arguments[t]) Object.prototype.hasOwnProperty.call(arguments[t], i) && (n[i] = arguments[t][i]);
            return n
        }

        function o(n, t) {
            for (var i = 0, r = n.length; i < r; i++)
                if (n[i] === t) return i;
            return -1
        }

        function s(n) {
            if ("string" != typeof n) {
                if (n && n.toHTML) return n.toHTML();
                if (null == n) return "";
                if (!n) return n + "";
                n = "" + n
            }
            return p.test(n) ? n.replace(y, e) : n
        }

        function h(n) {
            return !n && 0 !== n || !(!u(n) || 0 !== n.length)
        }

        function c(n) {
            var t = f({}, n);
            return t._parent = n, t
        }

        function l(n, t) {
            return n.path = t, n
        }

        function a(n, t) {
            return (n ? n + "." : "") + t
        }
        var i, u;
        t.__esModule = !0;
        t.extend = f;
        t.indexOf = o;
        t.escapeExpression = s;
        t.isEmpty = h;
        t.createFrame = c;
        t.blockParams = l;
        t.appendContextPath = a;
        var v = {
                "&": "&amp;",
                "<": "&lt;",
                ">": "&gt;",
                '"': "&quot;",
                "'": "&#x27;",
                "`": "&#x60;",
                "=": "&#x3D;"
            },
            y = /[&<>"'`=]/g,
            p = /[&<>"'`=]/,
            r = Object.prototype.toString;
        t.toString = r;
        i = function (n) {
            return "function" == typeof n
        };
        i(/x/) && (t.isFunction = i = function (n) {
            return "function" == typeof n && "[object Function]" === r.call(n)
        });
        t.isFunction = i;
        u = Array.isArray || function (n) {
            return !(!n || "object" != typeof n) && "[object Array]" === r.call(n)
        };
        t.isArray = u
    }, function (n, t, i) {
        "use strict";

        function u(n, t) {
            var i = t && t.loc,
                s = void 0,
                c = void 0,
                o = void 0,
                h = void 0,
                l, e;
            for (i && (s = i.start.line, c = i.end.line, o = i.start.column, h = i.end.column, n += " - " + s + ":" + o), l = Error.prototype.constructor.call(this, n), e = 0; e < r.length; e++) this[r[e]] = l[r[e]];
            Error.captureStackTrace && Error.captureStackTrace(this, u);
            try {
                i && (this.lineNumber = s, this.endLineNumber = c, f ? (Object.defineProperty(this, "column", {
                    value: o,
                    enumerable: !0
                }), Object.defineProperty(this, "endColumn", {
                    value: h,
                    enumerable: !0
                })) : (this.column = o, this.endColumn = h))
            } catch (a) {}
        }
        var f = i(7)["default"],
            r;
        t.__esModule = !0;
        r = ["description", "fileName", "lineNumber", "endLineNumber", "message", "name", "number", "stack"];
        u.prototype = new Error;
        t["default"] = u;
        n.exports = t["default"]
    }, function (n, t, i) {
        n.exports = {
            "default": i(8),
            __esModule: !0
        }
    }, function (n, t, i) {
        var r = i(9);
        n.exports = function (n, t, i) {
            return r.setDesc(n, t, i)
        }
    }, function (n) {
        var t = Object;
        n.exports = {
            create: t.create,
            getProto: t.getPrototypeOf,
            isEnum: {}.propertyIsEnumerable,
            getDesc: t.getOwnPropertyDescriptor,
            setDesc: t.defineProperty,
            setDescs: t.defineProperties,
            getKeys: t.keys,
            getNames: t.getOwnPropertyNames,
            getSymbols: t.getOwnPropertySymbols,
            each: [].forEach
        }
    }, function (n, t, i) {
        "use strict";

        function u(n) {
            o["default"](n);
            h["default"](n);
            l["default"](n);
            v["default"](n);
            p["default"](n);
            b["default"](n);
            d["default"](n)
        }

        function f(n, t, i) {
            n.helpers[t] && (n.hooks[t] = n.helpers[t], i || delete n.helpers[t])
        }
        var r = i(1)["default"];
        t.__esModule = !0;
        t.registerDefaultHelpers = u;
        t.moveHelperToHooks = f;
        var e = i(11),
            o = r(e),
            s = i(12),
            h = r(s),
            c = i(25),
            l = r(c),
            a = i(26),
            v = r(a),
            y = i(27),
            p = r(y),
            w = i(28),
            b = r(w),
            k = i(29),
            d = r(k)
    }, function (n, t, i) {
        "use strict";
        t.__esModule = !0;
        var r = i(5);
        t["default"] = function (n) {
            n.registerHelper("blockHelperMissing", function (t, i) {
                var f = i.inverse,
                    e = i.fn,
                    u;
                return t === !0 ? e(this) : t === !1 || null == t ? f(this) : r.isArray(t) ? t.length > 0 ? (i.ids && (i.ids = [i.name]), n.helpers.each(t, i)) : f(this) : (i.data && i.ids && (u = r.createFrame(i.data), u.contextPath = r.appendContextPath(i.data.contextPath, i.name), i = {
                    data: u
                }), e(t, i))
            })
        };
        n.exports = t["default"]
    }, function (n, t, i) {
        (function (r) {
            "use strict";
            var f = i(13)["default"],
                e = i(1)["default"];
            t.__esModule = !0;
            var u = i(5),
                o = i(6),
                s = e(o);
            t["default"] = function (n) {
                n.registerHelper("each", function (n, t) {
                    function o(t, i, r) {
                        e && (e.key = t, e.index = i, e.first = 0 === i, e.last = !!r, h && (e.contextPath = h + t));
                        l += p(n[t], {
                            data: e,
                            blockParams: u.blockParams([n[t], t], [h + t, null])
                        })
                    }
                    var c;
                    if (!t) throw new s["default"]("Must pass iterator to #each");
                    var p = t.fn,
                        w = t.inverse,
                        i = 0,
                        l = "",
                        e = void 0,
                        h = void 0;
                    if (t.data && t.ids && (h = u.appendContextPath(t.data.contextPath, t.ids[0]) + "."), u.isFunction(n) && (n = n.call(this)), t.data && (e = u.createFrame(t.data)), n && "object" == typeof n)
                        if (u.isArray(n))
                            for (c = n.length; i < c; i++) i in n && o(i, i, i === n.length - 1);
                        else if (r.Symbol && n[r.Symbol.iterator]) {
                        for (var v = [], y = n[r.Symbol.iterator](), a = y.next(); !a.done; a = y.next()) v.push(a.value);
                        for (n = v, c = n.length; i < c; i++) o(i, i, i === n.length - 1)
                    } else ! function () {
                        var t = void 0;
                        f(n).forEach(function (n) {
                            void 0 !== t && o(t, i - 1);
                            t = n;
                            i++
                        });
                        void 0 !== t && o(t, i - 1, !0)
                    }();
                    return 0 === i && (l = w(this)), l
                })
            };
            n.exports = t["default"]
        }).call(t, function () {
            return this
        }())
    }, function (n, t, i) {
        n.exports = {
            "default": i(14),
            __esModule: !0
        }
    }, function (n, t, i) {
        i(15);
        n.exports = i(21).Object.keys
    }, function (n, t, i) {
        var r = i(16);
        i(18)("keys", function (n) {
            return function (t) {
                return n(r(t))
            }
        })
    }, function (n, t, i) {
        var r = i(17);
        n.exports = function (n) {
            return Object(r(n))
        }
    }, function (n) {
        n.exports = function (n) {
            if (void 0 == n) throw TypeError("Can't call method on  " + n);
            return n
        }
    }, function (n, t, i) {
        var r = i(19),
            u = i(21),
            f = i(24);
        n.exports = function (n, t) {
            var i = (u.Object || {})[n] || Object[n],
                e = {};
            e[n] = t(i);
            r(r.S + r.F * f(function () {
                i(1)
            }), "Object", e)
        }
    }, function (n, t, i) {
        var f = i(20),
            e = i(21),
            o = i(22),
            u = "prototype",
            r = function (n, t, i) {
                var s, l, h, p = n & r.F,
                    a = n & r.G,
                    w = n & r.S,
                    y = n & r.P,
                    b = n & r.B,
                    k = n & r.W,
                    v = a ? e : e[t] || (e[t] = {}),
                    c = a ? f : w ? f[t] : (f[t] || {})[u];
                a && (i = t);
                for (s in i) l = !p && c && s in c, l && s in v || (h = l ? c[s] : i[s], v[s] = a && "function" != typeof c[s] ? i[s] : b && l ? o(h, f) : k && c[s] == h ? function (n) {
                    var t = function (t) {
                        return this instanceof n ? new n(t) : n(t)
                    };
                    return t[u] = n[u], t
                }(h) : y && "function" == typeof h ? o(Function.call, h) : h, y && ((v[u] || (v[u] = {}))[s] = h))
            };
        r.F = 1;
        r.G = 2;
        r.S = 4;
        r.P = 8;
        r.B = 16;
        r.W = 32;
        n.exports = r
    }, function (n) {
        var t = n.exports = "undefined" != typeof window && window.Math == Math ? window : "undefined" != typeof self && self.Math == Math ? self : Function("return this")();
        "number" == typeof __g && (__g = t)
    }, function (n) {
        var t = n.exports = {
            version: "1.2.6"
        };
        "number" == typeof __e && (__e = t)
    }, function (n, t, i) {
        var r = i(23);
        n.exports = function (n, t, i) {
            if (r(n), void 0 === t) return n;
            switch (i) {
                case 1:
                    return function (i) {
                        return n.call(t, i)
                    };
                case 2:
                    return function (i, r) {
                        return n.call(t, i, r)
                    };
                case 3:
                    return function (i, r, u) {
                        return n.call(t, i, r, u)
                    }
            }
            return function () {
                return n.apply(t, arguments)
            }
        }
    }, function (n) {
        n.exports = function (n) {
            if ("function" != typeof n) throw TypeError(n + " is not a function!");
            return n
        }
    }, function (n) {
        n.exports = function (n) {
            try {
                return !!n()
            } catch (t) {
                return !0
            }
        }
    }, function (n, t, i) {
        "use strict";
        var f = i(1)["default"],
            r, u;
        t.__esModule = !0;
        r = i(6);
        u = f(r);
        t["default"] = function (n) {
            n.registerHelper("helperMissing", function () {
                if (1 !== arguments.length) throw new u["default"]('Missing helper: "' + arguments[arguments.length - 1].name + '"');
            })
        };
        n.exports = t["default"]
    }, function (n, t, i) {
        "use strict";
        var f = i(1)["default"];
        t.__esModule = !0;
        var r = i(5),
            e = i(6),
            u = f(e);
        t["default"] = function (n) {
            n.registerHelper("if", function (n, t) {
                if (2 != arguments.length) throw new u["default"]("#if requires exactly one argument");
                return r.isFunction(n) && (n = n.call(this)), !t.hash.includeZero && !n || r.isEmpty(n) ? t.inverse(this) : t.fn(this)
            });
            n.registerHelper("unless", function (t, i) {
                if (2 != arguments.length) throw new u["default"]("#unless requires exactly one argument");
                return n.helpers["if"].call(this, t, {
                    fn: i.inverse,
                    inverse: i.fn,
                    hash: i.hash
                })
            })
        };
        n.exports = t["default"]
    }, function (n, t) {
        "use strict";
        t.__esModule = !0;
        t["default"] = function (n) {
            n.registerHelper("log", function () {
                for (var i, r = [void 0], t = arguments[arguments.length - 1], u = 0; u < arguments.length - 1; u++) r.push(arguments[u]);
                i = 1;
                null != t.hash.level ? i = t.hash.level : t.data && null != t.data.level && (i = t.data.level);
                r[0] = i;
                n.log.apply(n, r)
            })
        };
        n.exports = t["default"]
    }, function (n, t) {
        "use strict";
        t.__esModule = !0;
        t["default"] = function (n) {
            n.registerHelper("lookup", function (n, t, i) {
                return n ? i.lookupProperty(n, t) : n
            })
        };
        n.exports = t["default"]
    }, function (n, t, i) {
        "use strict";
        var u = i(1)["default"];
        t.__esModule = !0;
        var r = i(5),
            f = i(6),
            e = u(f);
        t["default"] = function (n) {
            n.registerHelper("with", function (n, t) {
                var u, i;
                if (2 != arguments.length) throw new e["default"]("#with requires exactly one argument");
                return (r.isFunction(n) && (n = n.call(this)), u = t.fn, r.isEmpty(n)) ? t.inverse(this) : (i = t.data, t.data && t.ids && (i = r.createFrame(t.data), i.contextPath = r.appendContextPath(t.data.contextPath, t.ids[0])), u(n, {
                    data: i,
                    blockParams: r.blockParams([n], [i && i.contextPath])
                }))
            })
        };
        n.exports = t["default"]
    }, function (n, t, i) {
        "use strict";

        function f(n) {
            u["default"](n)
        }
        var e = i(1)["default"],
            r, u;
        t.__esModule = !0;
        t.registerDefaultDecorators = f;
        r = i(31);
        u = e(r)
    }, function (n, t, i) {
        "use strict";
        t.__esModule = !0;
        var r = i(5);
        t["default"] = function (n) {
            n.registerDecorator("inline", function (n, t, i, u) {
                var f = n;
                return t.partials || (t.partials = {}, f = function (u, f) {
                    var e = i.partials,
                        o;
                    return i.partials = r.extend({}, e, t.partials), o = n(u, f), i.partials = e, o
                }), t.partials[u.args[0]] = u.fn, f
            })
        };
        n.exports = t["default"]
    }, function (n, t, i) {
        "use strict";
        t.__esModule = !0;
        var u = i(5),
            r = {
                methodMap: ["debug", "info", "warn", "error"],
                level: "info",
                lookupLevel: function (n) {
                    if ("string" == typeof n) {
                        var t = u.indexOf(r.methodMap, n.toLowerCase());
                        n = t >= 0 ? t : parseInt(n, 10)
                    }
                    return n
                },
                log: function (n) {
                    var t;
                    if (n = r.lookupLevel(n), "undefined" != typeof console && r.lookupLevel(r.level) <= n) {
                        t = r.methodMap[n];
                        console[t] || (t = "log");
                        for (var u = arguments.length, f = Array(u > 1 ? u - 1 : 0), i = 1; i < u; i++) f[i - 1] = arguments[i];
                        console[t].apply(console, f)
                    }
                }
            };
        t["default"] = r;
        n.exports = t["default"]
    }, function (n, t, i) {
        "use strict";

        function o(n) {
            var t = u(null),
                i;
            return t.constructor = !1, t.__defineGetter__ = !1, t.__defineSetter__ = !1, t.__lookupGetter__ = !1, i = u(null), i.__proto__ = !1, {
                properties: {
                    whitelist: e.createNewLookupObject(i, n.allowedProtoProperties),
                    defaultValue: n.allowProtoPropertiesByDefault
                },
                methods: {
                    whitelist: e.createNewLookupObject(t, n.allowedProtoMethods),
                    defaultValue: n.allowProtoMethodsByDefault
                }
            }
        }

        function s(n, t, i) {
            return "function" == typeof n ? f(t.methods, i) : f(t.properties, i)
        }

        function f(n, t) {
            return void 0 !== n.whitelist[t] ? n.whitelist[t] === !0 : void 0 !== n.defaultValue ? n.defaultValue : (h(t), !1)
        }

        function h(n) {
            r[n] !== !0 && (r[n] = !0, y.log("error", 'Handlebars: Access has been denied to resolve the property "' + n + '" because it is not an "own property" of its parent.\nYou can add a runtime option to disable the check or this warning:\nSee https://handlebarsjs.com/api-reference/runtime-options.html#options-to-control-prototype-access for details'))
        }

        function c() {
            l(r).forEach(function (n) {
                delete r[n]
            })
        }
        var u = i(34)["default"],
            l = i(13)["default"],
            a = i(3)["default"];
        t.__esModule = !0;
        t.createProtoAccessControl = o;
        t.resultIsAllowed = s;
        t.resetLoggedProperties = c;
        var e = i(36),
            v = i(32),
            y = a(v),
            r = u(null)
    }, function (n, t, i) {
        n.exports = {
            "default": i(35),
            __esModule: !0
        }
    }, function (n, t, i) {
        var r = i(9);
        n.exports = function (n, t) {
            return r.create(n, t)
        }
    }, function (n, t, i) {
        "use strict";

        function u() {
            for (var t = arguments.length, i = Array(t), n = 0; n < t; n++) i[n] = arguments[n];
            return r.extend.apply(void 0, [f(null)].concat(i))
        }
        var f = i(34)["default"],
            r;
        t.__esModule = !0;
        t.createNewLookupObject = u;
        r = i(5)
    }, function (n, t) {
        "use strict";

        function i(n) {
            this.string = n
        }
        t.__esModule = !0;
        i.prototype.toString = i.prototype.toHTML = function () {
            return "" + this.string
        };
        t["default"] = i;
        n.exports = t["default"]
    }, function (n, t, i) {
        "use strict";

        function l(n) {
            var t = n && n[0] || 1,
                e = f.COMPILER_REVISION,
                i, r;
            if (!(t >= f.LAST_COMPATIBLE_COMPILER_REVISION && t <= f.COMPILER_REVISION)) {
                if (t < f.LAST_COMPATIBLE_COMPILER_REVISION) {
                    i = f.REVISION_CHANGES[e];
                    r = f.REVISION_CHANGES[t];
                    throw new u["default"]("Template was precompiled with an older version of Handlebars than the current runtime. Please update your precompiler to a newer version (" + i + ") or downgrade your runtime to an older version (" + r + ").");
                }
                throw new u["default"]("Template was precompiled with a newer version of Handlebars than the current runtime. Please update your runtime to a newer version (" + n[1] + ").");
            }
        }

        function a(n, t) {
            function o(i, f, e) {
                var c, o;
                if (e.hash && (f = r.extend({}, f, e.hash), e.ids && (e.ids[0] = !0)), i = t.VM.resolvePartial.call(this, i, f, e), c = r.extend({}, e, {
                        hooks: this.hooks,
                        protoAccessControl: this.protoAccessControl
                    }), o = t.VM.invokePartial.call(this, i, f, c), null == o && t.compile && (e.partials[e.name] = t.compile(i, n.compilerOptions, t), o = e.partials[e.name](f, c)), null != o) {
                    if (e.indent) {
                        for (var h = o.split("\n"), s = 0, l = h.length; s < l && (h[s] || s + 1 !== l); s++) h[s] = e.indent + h[s];
                        o = h.join("\n")
                    }
                    return o
                }
                throw new u["default"]("The partial " + e.name + " could not be compiled when running in runtime-only mode");
            }

            function f(t) {
                function h(t) {
                    return "" + n.main(i, t, i.helpers, i.partials, u, o, e)
                }
                var r = arguments.length <= 1 || void 0 === arguments[1] ? {} : arguments[1],
                    u = r.data,
                    e, o;
                return f._setup(r), !r.partial && n.useData && (u = p(t, u)), e = void 0, o = n.useBlockParams ? [] : void 0, n.useDepths && (e = r.depths ? t != r.depths[0] ? [t].concat(r.depths) : r.depths : [t]), (h = s(n.main, h, i, r.depths || [], u, o))(t, r)
            }
            if (!t) throw new u["default"]("No environment passed to template");
            if (!n || !n.main) throw new u["default"]("Unknown template object: " + typeof n);
            n.main.decorator = n.main_d;
            t.VM.checkRevision(n.compiler);
            var l = n.compiler && 7 === n.compiler[0],
                i = {
                    strict: function (n, t, r) {
                        if (!(n && t in n)) throw new u["default"]('"' + t + '" not defined in ' + n, {
                            loc: r
                        });
                        return i.lookupProperty(n, t)
                    },
                    lookupProperty: function (n, t) {
                        var r = n[t];
                        return null == r ? r : Object.prototype.hasOwnProperty.call(n, t) ? r : c.resultIsAllowed(r, i.protoAccessControl, t) ? r : void 0
                    },
                    lookup: function (n, t) {
                        for (var f, u = n.length, r = 0; r < u; r++)
                            if (f = n[r] && i.lookupProperty(n[r], t), null != f) return n[r][t]
                    },
                    lambda: function (n, t) {
                        return "function" == typeof n ? n.call(t) : n
                    },
                    escapeExpression: r.escapeExpression,
                    invokePartial: o,
                    fn: function (t) {
                        var i = n[t];
                        return i.decorator = n[t + "_d"], i
                    },
                    programs: [],
                    program: function (n, t, i, r, u) {
                        var f = this.programs[n],
                            o = this.fn(n);
                        return t || u || r || i ? f = e(this, n, o, t, i, r, u) : f || (f = this.programs[n] = e(this, n, o)), f
                    },
                    data: function (n, t) {
                        for (; n && t--;) n = n._parent;
                        return n
                    },
                    mergeIfNeeded: function (n, t) {
                        var i = n || t;
                        return n && t && n !== t && (i = r.extend({}, t, n)), i
                    },
                    nullContext: k({}),
                    noop: t.VM.noop,
                    compilerInfo: n.compiler
                };
            return f.isTop = !0, f._setup = function (u) {
                var f, e;
                u.partial ? (i.protoAccessControl = u.protoAccessControl, i.helpers = u.helpers, i.partials = u.partials, i.decorators = u.decorators, i.hooks = u.hooks) : (f = r.extend({}, t.helpers, u.helpers), w(f, i), i.helpers = f, n.usePartial && (i.partials = i.mergeIfNeeded(u.partials, t.partials)), (n.usePartial || n.useDecorators) && (i.decorators = r.extend({}, t.decorators, u.decorators)), i.hooks = {}, i.protoAccessControl = c.createProtoAccessControl(u), e = u.allowCallsToHelperMissing || l, h.moveHelperToHooks(i, "helperMissing", e), h.moveHelperToHooks(i, "blockHelperMissing", e))
            }, f._child = function (t, r, f, o) {
                if (n.useBlockParams && !f) throw new u["default"]("must pass block params");
                if (n.useDepths && !o) throw new u["default"]("must pass parent depths");
                return e(i, t, n[t], r, 0, f, o)
            }, f
        }

        function e(n, t, i, r, u, f, e) {
            function o(t) {
                var u = arguments.length <= 1 || void 0 === arguments[1] ? {} : arguments[1],
                    o = e;
                return !e || t == e[0] || t === n.nullContext && null === e[0] || (o = [t].concat(e)), i(n, t, n.helpers, n.partials, u.data || r, f && [u.blockParams].concat(f), o)
            }
            return o = s(i, o, n, e, r, f), o.program = t, o.depth = e ? e.length : 0, o.blockParams = u || 0, o
        }

        function v(n, t, i) {
            return n ? n.call || i.name || (i.name = n, n = i.partials[n]) : n = "@partial-block" === i.name ? i.data["partial-block"] : i.partials[i.name], n
        }

        function y(n, t, i) {
            var s = i.data && i.data["partial-block"],
                e;
            if (i.partial = !0, i.ids && (i.data.contextPath = i.ids[0] || i.data.contextPath), e = void 0, i.fn && i.fn !== o && ! function () {
                    i.data = f.createFrame(i.data);
                    var n = i.fn;
                    e = i.data["partial-block"] = function (t) {
                        var i = arguments.length <= 1 || void 0 === arguments[1] ? {} : arguments[1];
                        return i.data = f.createFrame(i.data), i.data["partial-block"] = s, n(t, i)
                    };
                    n.partials && (i.partials = r.extend({}, i.partials, n.partials))
                }(), void 0 === n && e && (n = e), void 0 === n) throw new u["default"]("The partial " + i.name + " could not be found");
            if (n instanceof Function) return n(t, i)
        }

        function o() {
            return ""
        }

        function p(n, t) {
            return t && "root" in t || (t = t ? f.createFrame(t) : {}, t.root = n), t
        }

        function s(n, t, i, u, f, e) {
            if (n.decorator) {
                var o = {};
                t = n.decorator(t, o, i, u && u[0], f, e, u);
                r.extend(t, o)
            }
            return t
        }

        function w(n, t) {
            d(n).forEach(function (i) {
                var r = n[i];
                n[i] = b(r, t)
            })
        }

        function b(n, t) {
            var i = t.lookupProperty;
            return rt.wrapHelper(n, function (n) {
                return r.extend({
                    lookupProperty: i
                }, n)
            })
        }
        var k = i(39)["default"],
            d = i(13)["default"],
            g = i(3)["default"],
            nt = i(1)["default"];
        t.__esModule = !0;
        t.checkRevision = l;
        t.template = a;
        t.wrapProgram = e;
        t.resolvePartial = v;
        t.invokePartial = y;
        t.noop = o;
        var tt = i(5),
            r = g(tt),
            it = i(6),
            u = nt(it),
            f = i(4),
            h = i(10),
            rt = i(43),
            c = i(33)
    }, function (n, t, i) {
        n.exports = {
            "default": i(40),
            __esModule: !0
        }
    }, function (n, t, i) {
        i(41);
        n.exports = i(21).Object.seal
    }, function (n, t, i) {
        var r = i(42);
        i(18)("seal", function (n) {
            return function (t) {
                return n && r(t) ? n(t) : t
            }
        })
    }, function (n) {
        n.exports = function (n) {
            return "object" == typeof n ? null !== n : "function" == typeof n
        }
    }, function (n, t) {
        "use strict";

        function i(n, t) {
            if ("function" != typeof n) return n;
            return function () {
                var i = arguments[arguments.length - 1];
                return arguments[arguments.length - 1] = t(i), n.apply(this, arguments)
            }
        }
        t.__esModule = !0;
        t.wrapHelper = i
    }, function (n, t) {
        (function (i) {
            "use strict";
            t.__esModule = !0;
            t["default"] = function (n) {
                var t = "undefined" != typeof i ? i : window,
                    r = t.Handlebars;
                n.noConflict = function () {
                    return t.Handlebars === n && (t.Handlebars = r), n
                }
            };
            n.exports = t["default"]
        }).call(t, function () {
            return this
        }())
    }, function (n, t) {
        "use strict";
        t.__esModule = !0;
        var i = {
            helpers: {
                helperExpression: function (n) {
                    return "SubExpression" === n.type || ("MustacheStatement" === n.type || "BlockStatement" === n.type) && !!(n.params && n.params.length || n.hash)
                },
                scopedId: function (n) {
                    return /^\.|this\b/.test(n.original)
                },
                simpleId: function (n) {
                    return 1 === n.parts.length && !i.helpers.scopedId(n) && !n.depth
                }
            }
        };
        t["default"] = i;
        n.exports = t["default"]
    }, function (n, t, i) {
        "use strict";

        function f(n, t) {
            if ("Program" === n.type) return n;
            u["default"].yy = r;
            r.locInfo = function (n) {
                return new r.SourceLocation(t && t.srcName, n)
            };
            return u["default"].parse(n)
        }

        function o(n, t) {
            var i = f(n, t),
                r = new l["default"](t);
            return r.accept(i)
        }
        var e = i(1)["default"],
            s = i(3)["default"],
            r;
        t.__esModule = !0;
        t.parseWithoutProcessing = f;
        t.parse = o;
        var h = i(47),
            u = e(h),
            c = i(48),
            l = e(c),
            a = i(50),
            v = s(a),
            y = i(5);
        t.parser = u["default"];
        r = {};
        y.extend(r, v)
    }, function (n, t) {
        "use strict";
        t.__esModule = !0;
        var i = function () {
            function n() {
                this.yy = {}
            }
            var t = {
                    trace: function () {},
                    yy: {},
                    symbols_: {
                        error: 2,
                        root: 3,
                        program: 4,
                        EOF: 5,
                        program_repetition0: 6,
                        statement: 7,
                        mustache: 8,
                        block: 9,
                        rawBlock: 10,
                        partial: 11,
                        partialBlock: 12,
                        content: 13,
                        COMMENT: 14,
                        CONTENT: 15,
                        openRawBlock: 16,
                        rawBlock_repetition0: 17,
                        END_RAW_BLOCK: 18,
                        OPEN_RAW_BLOCK: 19,
                        helperName: 20,
                        openRawBlock_repetition0: 21,
                        openRawBlock_option0: 22,
                        CLOSE_RAW_BLOCK: 23,
                        openBlock: 24,
                        block_option0: 25,
                        closeBlock: 26,
                        openInverse: 27,
                        block_option1: 28,
                        OPEN_BLOCK: 29,
                        openBlock_repetition0: 30,
                        openBlock_option0: 31,
                        openBlock_option1: 32,
                        CLOSE: 33,
                        OPEN_INVERSE: 34,
                        openInverse_repetition0: 35,
                        openInverse_option0: 36,
                        openInverse_option1: 37,
                        openInverseChain: 38,
                        OPEN_INVERSE_CHAIN: 39,
                        openInverseChain_repetition0: 40,
                        openInverseChain_option0: 41,
                        openInverseChain_option1: 42,
                        inverseAndProgram: 43,
                        INVERSE: 44,
                        inverseChain: 45,
                        inverseChain_option0: 46,
                        OPEN_ENDBLOCK: 47,
                        OPEN: 48,
                        mustache_repetition0: 49,
                        mustache_option0: 50,
                        OPEN_UNESCAPED: 51,
                        mustache_repetition1: 52,
                        mustache_option1: 53,
                        CLOSE_UNESCAPED: 54,
                        OPEN_PARTIAL: 55,
                        partialName: 56,
                        partial_repetition0: 57,
                        partial_option0: 58,
                        openPartialBlock: 59,
                        OPEN_PARTIAL_BLOCK: 60,
                        openPartialBlock_repetition0: 61,
                        openPartialBlock_option0: 62,
                        param: 63,
                        sexpr: 64,
                        OPEN_SEXPR: 65,
                        sexpr_repetition0: 66,
                        sexpr_option0: 67,
                        CLOSE_SEXPR: 68,
                        hash: 69,
                        hash_repetition_plus0: 70,
                        hashSegment: 71,
                        ID: 72,
                        EQUALS: 73,
                        blockParams: 74,
                        OPEN_BLOCK_PARAMS: 75,
                        blockParams_repetition_plus0: 76,
                        CLOSE_BLOCK_PARAMS: 77,
                        path: 78,
                        dataName: 79,
                        STRING: 80,
                        NUMBER: 81,
                        BOOLEAN: 82,
                        UNDEFINED: 83,
                        NULL: 84,
                        DATA: 85,
                        pathSegments: 86,
                        SEP: 87,
                        $accept: 0,
                        $end: 1
                    },
                    terminals_: {
                        2: "error",
                        5: "EOF",
                        14: "COMMENT",
                        15: "CONTENT",
                        18: "END_RAW_BLOCK",
                        19: "OPEN_RAW_BLOCK",
                        23: "CLOSE_RAW_BLOCK",
                        29: "OPEN_BLOCK",
                        33: "CLOSE",
                        34: "OPEN_INVERSE",
                        39: "OPEN_INVERSE_CHAIN",
                        44: "INVERSE",
                        47: "OPEN_ENDBLOCK",
                        48: "OPEN",
                        51: "OPEN_UNESCAPED",
                        54: "CLOSE_UNESCAPED",
                        55: "OPEN_PARTIAL",
                        60: "OPEN_PARTIAL_BLOCK",
                        65: "OPEN_SEXPR",
                        68: "CLOSE_SEXPR",
                        72: "ID",
                        73: "EQUALS",
                        75: "OPEN_BLOCK_PARAMS",
                        77: "CLOSE_BLOCK_PARAMS",
                        80: "STRING",
                        81: "NUMBER",
                        82: "BOOLEAN",
                        83: "UNDEFINED",
                        84: "NULL",
                        85: "DATA",
                        87: "SEP"
                    },
                    productions_: [0, [3, 2],
                        [4, 1],
                        [7, 1],
                        [7, 1],
                        [7, 1],
                        [7, 1],
                        [7, 1],
                        [7, 1],
                        [7, 1],
                        [13, 1],
                        [10, 3],
                        [16, 5],
                        [9, 4],
                        [9, 4],
                        [24, 6],
                        [27, 6],
                        [38, 6],
                        [43, 2],
                        [45, 3],
                        [45, 1],
                        [26, 3],
                        [8, 5],
                        [8, 5],
                        [11, 5],
                        [12, 3],
                        [59, 5],
                        [63, 1],
                        [63, 1],
                        [64, 5],
                        [69, 1],
                        [71, 3],
                        [74, 3],
                        [20, 1],
                        [20, 1],
                        [20, 1],
                        [20, 1],
                        [20, 1],
                        [20, 1],
                        [20, 1],
                        [56, 1],
                        [56, 1],
                        [79, 2],
                        [78, 1],
                        [86, 3],
                        [86, 1],
                        [6, 0],
                        [6, 2],
                        [17, 0],
                        [17, 2],
                        [21, 0],
                        [21, 2],
                        [22, 0],
                        [22, 1],
                        [25, 0],
                        [25, 1],
                        [28, 0],
                        [28, 1],
                        [30, 0],
                        [30, 2],
                        [31, 0],
                        [31, 1],
                        [32, 0],
                        [32, 1],
                        [35, 0],
                        [35, 2],
                        [36, 0],
                        [36, 1],
                        [37, 0],
                        [37, 1],
                        [40, 0],
                        [40, 2],
                        [41, 0],
                        [41, 1],
                        [42, 0],
                        [42, 1],
                        [46, 0],
                        [46, 1],
                        [49, 0],
                        [49, 2],
                        [50, 0],
                        [50, 1],
                        [52, 0],
                        [52, 2],
                        [53, 0],
                        [53, 1],
                        [57, 0],
                        [57, 2],
                        [58, 0],
                        [58, 1],
                        [61, 0],
                        [61, 2],
                        [62, 0],
                        [62, 1],
                        [66, 0],
                        [66, 2],
                        [67, 0],
                        [67, 1],
                        [70, 1],
                        [70, 2],
                        [76, 1],
                        [76, 2]
                    ],
                    performAction: function (n, t, i, r, u, f) {
                        var e = f.length - 1,
                            s, o;
                        switch (u) {
                            case 1:
                                return f[e - 1];
                            case 2:
                                this.$ = r.prepareProgram(f[e]);
                                break;
                            case 3:
                                this.$ = f[e];
                                break;
                            case 4:
                                this.$ = f[e];
                                break;
                            case 5:
                                this.$ = f[e];
                                break;
                            case 6:
                                this.$ = f[e];
                                break;
                            case 7:
                                this.$ = f[e];
                                break;
                            case 8:
                                this.$ = f[e];
                                break;
                            case 9:
                                this.$ = {
                                    type: "CommentStatement",
                                    value: r.stripComment(f[e]),
                                    strip: r.stripFlags(f[e], f[e]),
                                    loc: r.locInfo(this._$)
                                };
                                break;
                            case 10:
                                this.$ = {
                                    type: "ContentStatement",
                                    original: f[e],
                                    value: f[e],
                                    loc: r.locInfo(this._$)
                                };
                                break;
                            case 11:
                                this.$ = r.prepareRawBlock(f[e - 2], f[e - 1], f[e], this._$);
                                break;
                            case 12:
                                this.$ = {
                                    path: f[e - 3],
                                    params: f[e - 2],
                                    hash: f[e - 1]
                                };
                                break;
                            case 13:
                                this.$ = r.prepareBlock(f[e - 3], f[e - 2], f[e - 1], f[e], !1, this._$);
                                break;
                            case 14:
                                this.$ = r.prepareBlock(f[e - 3], f[e - 2], f[e - 1], f[e], !0, this._$);
                                break;
                            case 15:
                                this.$ = {
                                    open: f[e - 5],
                                    path: f[e - 4],
                                    params: f[e - 3],
                                    hash: f[e - 2],
                                    blockParams: f[e - 1],
                                    strip: r.stripFlags(f[e - 5], f[e])
                                };
                                break;
                            case 16:
                                this.$ = {
                                    path: f[e - 4],
                                    params: f[e - 3],
                                    hash: f[e - 2],
                                    blockParams: f[e - 1],
                                    strip: r.stripFlags(f[e - 5], f[e])
                                };
                                break;
                            case 17:
                                this.$ = {
                                    path: f[e - 4],
                                    params: f[e - 3],
                                    hash: f[e - 2],
                                    blockParams: f[e - 1],
                                    strip: r.stripFlags(f[e - 5], f[e])
                                };
                                break;
                            case 18:
                                this.$ = {
                                    strip: r.stripFlags(f[e - 1], f[e - 1]),
                                    program: f[e]
                                };
                                break;
                            case 19:
                                s = r.prepareBlock(f[e - 2], f[e - 1], f[e], f[e], !1, this._$);
                                o = r.prepareProgram([s], f[e - 1].loc);
                                o.chained = !0;
                                this.$ = {
                                    strip: f[e - 2].strip,
                                    program: o,
                                    chain: !0
                                };
                                break;
                            case 20:
                                this.$ = f[e];
                                break;
                            case 21:
                                this.$ = {
                                    path: f[e - 1],
                                    strip: r.stripFlags(f[e - 2], f[e])
                                };
                                break;
                            case 22:
                                this.$ = r.prepareMustache(f[e - 3], f[e - 2], f[e - 1], f[e - 4], r.stripFlags(f[e - 4], f[e]), this._$);
                                break;
                            case 23:
                                this.$ = r.prepareMustache(f[e - 3], f[e - 2], f[e - 1], f[e - 4], r.stripFlags(f[e - 4], f[e]), this._$);
                                break;
                            case 24:
                                this.$ = {
                                    type: "PartialStatement",
                                    name: f[e - 3],
                                    params: f[e - 2],
                                    hash: f[e - 1],
                                    indent: "",
                                    strip: r.stripFlags(f[e - 4], f[e]),
                                    loc: r.locInfo(this._$)
                                };
                                break;
                            case 25:
                                this.$ = r.preparePartialBlock(f[e - 2], f[e - 1], f[e], this._$);
                                break;
                            case 26:
                                this.$ = {
                                    path: f[e - 3],
                                    params: f[e - 2],
                                    hash: f[e - 1],
                                    strip: r.stripFlags(f[e - 4], f[e])
                                };
                                break;
                            case 27:
                                this.$ = f[e];
                                break;
                            case 28:
                                this.$ = f[e];
                                break;
                            case 29:
                                this.$ = {
                                    type: "SubExpression",
                                    path: f[e - 3],
                                    params: f[e - 2],
                                    hash: f[e - 1],
                                    loc: r.locInfo(this._$)
                                };
                                break;
                            case 30:
                                this.$ = {
                                    type: "Hash",
                                    pairs: f[e],
                                    loc: r.locInfo(this._$)
                                };
                                break;
                            case 31:
                                this.$ = {
                                    type: "HashPair",
                                    key: r.id(f[e - 2]),
                                    value: f[e],
                                    loc: r.locInfo(this._$)
                                };
                                break;
                            case 32:
                                this.$ = r.id(f[e - 1]);
                                break;
                            case 33:
                                this.$ = f[e];
                                break;
                            case 34:
                                this.$ = f[e];
                                break;
                            case 35:
                                this.$ = {
                                    type: "StringLiteral",
                                    value: f[e],
                                    original: f[e],
                                    loc: r.locInfo(this._$)
                                };
                                break;
                            case 36:
                                this.$ = {
                                    type: "NumberLiteral",
                                    value: Number(f[e]),
                                    original: Number(f[e]),
                                    loc: r.locInfo(this._$)
                                };
                                break;
                            case 37:
                                this.$ = {
                                    type: "BooleanLiteral",
                                    value: "true" === f[e],
                                    original: "true" === f[e],
                                    loc: r.locInfo(this._$)
                                };
                                break;
                            case 38:
                                this.$ = {
                                    type: "UndefinedLiteral",
                                    original: void 0,
                                    value: void 0,
                                    loc: r.locInfo(this._$)
                                };
                                break;
                            case 39:
                                this.$ = {
                                    type: "NullLiteral",
                                    original: null,
                                    value: null,
                                    loc: r.locInfo(this._$)
                                };
                                break;
                            case 40:
                                this.$ = f[e];
                                break;
                            case 41:
                                this.$ = f[e];
                                break;
                            case 42:
                                this.$ = r.preparePath(!0, f[e], this._$);
                                break;
                            case 43:
                                this.$ = r.preparePath(!1, f[e], this._$);
                                break;
                            case 44:
                                f[e - 2].push({
                                    part: r.id(f[e]),
                                    original: f[e],
                                    separator: f[e - 1]
                                });
                                this.$ = f[e - 2];
                                break;
                            case 45:
                                this.$ = [{
                                    part: r.id(f[e]),
                                    original: f[e]
                                }];
                                break;
                            case 46:
                                this.$ = [];
                                break;
                            case 47:
                                f[e - 1].push(f[e]);
                                break;
                            case 48:
                                this.$ = [];
                                break;
                            case 49:
                                f[e - 1].push(f[e]);
                                break;
                            case 50:
                                this.$ = [];
                                break;
                            case 51:
                                f[e - 1].push(f[e]);
                                break;
                            case 58:
                                this.$ = [];
                                break;
                            case 59:
                                f[e - 1].push(f[e]);
                                break;
                            case 64:
                                this.$ = [];
                                break;
                            case 65:
                                f[e - 1].push(f[e]);
                                break;
                            case 70:
                                this.$ = [];
                                break;
                            case 71:
                                f[e - 1].push(f[e]);
                                break;
                            case 78:
                                this.$ = [];
                                break;
                            case 79:
                                f[e - 1].push(f[e]);
                                break;
                            case 82:
                                this.$ = [];
                                break;
                            case 83:
                                f[e - 1].push(f[e]);
                                break;
                            case 86:
                                this.$ = [];
                                break;
                            case 87:
                                f[e - 1].push(f[e]);
                                break;
                            case 90:
                                this.$ = [];
                                break;
                            case 91:
                                f[e - 1].push(f[e]);
                                break;
                            case 94:
                                this.$ = [];
                                break;
                            case 95:
                                f[e - 1].push(f[e]);
                                break;
                            case 98:
                                this.$ = [f[e]];
                                break;
                            case 99:
                                f[e - 1].push(f[e]);
                                break;
                            case 100:
                                this.$ = [f[e]];
                                break;
                            case 101:
                                f[e - 1].push(f[e])
                        }
                    },
                    table: [{
                        3: 1,
                        4: 2,
                        5: [2, 46],
                        6: 3,
                        14: [2, 46],
                        15: [2, 46],
                        19: [2, 46],
                        29: [2, 46],
                        34: [2, 46],
                        48: [2, 46],
                        51: [2, 46],
                        55: [2, 46],
                        60: [2, 46]
                    }, {
                        1: [3]
                    }, {
                        5: [1, 4]
                    }, {
                        5: [2, 2],
                        7: 5,
                        8: 6,
                        9: 7,
                        10: 8,
                        11: 9,
                        12: 10,
                        13: 11,
                        14: [1, 12],
                        15: [1, 20],
                        16: 17,
                        19: [1, 23],
                        24: 15,
                        27: 16,
                        29: [1, 21],
                        34: [1, 22],
                        39: [2, 2],
                        44: [2, 2],
                        47: [2, 2],
                        48: [1, 13],
                        51: [1, 14],
                        55: [1, 18],
                        59: 19,
                        60: [1, 24]
                    }, {
                        1: [2, 1]
                    }, {
                        5: [2, 47],
                        14: [2, 47],
                        15: [2, 47],
                        19: [2, 47],
                        29: [2, 47],
                        34: [2, 47],
                        39: [2, 47],
                        44: [2, 47],
                        47: [2, 47],
                        48: [2, 47],
                        51: [2, 47],
                        55: [2, 47],
                        60: [2, 47]
                    }, {
                        5: [2, 3],
                        14: [2, 3],
                        15: [2, 3],
                        19: [2, 3],
                        29: [2, 3],
                        34: [2, 3],
                        39: [2, 3],
                        44: [2, 3],
                        47: [2, 3],
                        48: [2, 3],
                        51: [2, 3],
                        55: [2, 3],
                        60: [2, 3]
                    }, {
                        5: [2, 4],
                        14: [2, 4],
                        15: [2, 4],
                        19: [2, 4],
                        29: [2, 4],
                        34: [2, 4],
                        39: [2, 4],
                        44: [2, 4],
                        47: [2, 4],
                        48: [2, 4],
                        51: [2, 4],
                        55: [2, 4],
                        60: [2, 4]
                    }, {
                        5: [2, 5],
                        14: [2, 5],
                        15: [2, 5],
                        19: [2, 5],
                        29: [2, 5],
                        34: [2, 5],
                        39: [2, 5],
                        44: [2, 5],
                        47: [2, 5],
                        48: [2, 5],
                        51: [2, 5],
                        55: [2, 5],
                        60: [2, 5]
                    }, {
                        5: [2, 6],
                        14: [2, 6],
                        15: [2, 6],
                        19: [2, 6],
                        29: [2, 6],
                        34: [2, 6],
                        39: [2, 6],
                        44: [2, 6],
                        47: [2, 6],
                        48: [2, 6],
                        51: [2, 6],
                        55: [2, 6],
                        60: [2, 6]
                    }, {
                        5: [2, 7],
                        14: [2, 7],
                        15: [2, 7],
                        19: [2, 7],
                        29: [2, 7],
                        34: [2, 7],
                        39: [2, 7],
                        44: [2, 7],
                        47: [2, 7],
                        48: [2, 7],
                        51: [2, 7],
                        55: [2, 7],
                        60: [2, 7]
                    }, {
                        5: [2, 8],
                        14: [2, 8],
                        15: [2, 8],
                        19: [2, 8],
                        29: [2, 8],
                        34: [2, 8],
                        39: [2, 8],
                        44: [2, 8],
                        47: [2, 8],
                        48: [2, 8],
                        51: [2, 8],
                        55: [2, 8],
                        60: [2, 8]
                    }, {
                        5: [2, 9],
                        14: [2, 9],
                        15: [2, 9],
                        19: [2, 9],
                        29: [2, 9],
                        34: [2, 9],
                        39: [2, 9],
                        44: [2, 9],
                        47: [2, 9],
                        48: [2, 9],
                        51: [2, 9],
                        55: [2, 9],
                        60: [2, 9]
                    }, {
                        20: 25,
                        72: [1, 35],
                        78: 26,
                        79: 27,
                        80: [1, 28],
                        81: [1, 29],
                        82: [1, 30],
                        83: [1, 31],
                        84: [1, 32],
                        85: [1, 34],
                        86: 33
                    }, {
                        20: 36,
                        72: [1, 35],
                        78: 26,
                        79: 27,
                        80: [1, 28],
                        81: [1, 29],
                        82: [1, 30],
                        83: [1, 31],
                        84: [1, 32],
                        85: [1, 34],
                        86: 33
                    }, {
                        4: 37,
                        6: 3,
                        14: [2, 46],
                        15: [2, 46],
                        19: [2, 46],
                        29: [2, 46],
                        34: [2, 46],
                        39: [2, 46],
                        44: [2, 46],
                        47: [2, 46],
                        48: [2, 46],
                        51: [2, 46],
                        55: [2, 46],
                        60: [2, 46]
                    }, {
                        4: 38,
                        6: 3,
                        14: [2, 46],
                        15: [2, 46],
                        19: [2, 46],
                        29: [2, 46],
                        34: [2, 46],
                        44: [2, 46],
                        47: [2, 46],
                        48: [2, 46],
                        51: [2, 46],
                        55: [2, 46],
                        60: [2, 46]
                    }, {
                        15: [2, 48],
                        17: 39,
                        18: [2, 48]
                    }, {
                        20: 41,
                        56: 40,
                        64: 42,
                        65: [1, 43],
                        72: [1, 35],
                        78: 26,
                        79: 27,
                        80: [1, 28],
                        81: [1, 29],
                        82: [1, 30],
                        83: [1, 31],
                        84: [1, 32],
                        85: [1, 34],
                        86: 33
                    }, {
                        4: 44,
                        6: 3,
                        14: [2, 46],
                        15: [2, 46],
                        19: [2, 46],
                        29: [2, 46],
                        34: [2, 46],
                        47: [2, 46],
                        48: [2, 46],
                        51: [2, 46],
                        55: [2, 46],
                        60: [2, 46]
                    }, {
                        5: [2, 10],
                        14: [2, 10],
                        15: [2, 10],
                        18: [2, 10],
                        19: [2, 10],
                        29: [2, 10],
                        34: [2, 10],
                        39: [2, 10],
                        44: [2, 10],
                        47: [2, 10],
                        48: [2, 10],
                        51: [2, 10],
                        55: [2, 10],
                        60: [2, 10]
                    }, {
                        20: 45,
                        72: [1, 35],
                        78: 26,
                        79: 27,
                        80: [1, 28],
                        81: [1, 29],
                        82: [1, 30],
                        83: [1, 31],
                        84: [1, 32],
                        85: [1, 34],
                        86: 33
                    }, {
                        20: 46,
                        72: [1, 35],
                        78: 26,
                        79: 27,
                        80: [1, 28],
                        81: [1, 29],
                        82: [1, 30],
                        83: [1, 31],
                        84: [1, 32],
                        85: [1, 34],
                        86: 33
                    }, {
                        20: 47,
                        72: [1, 35],
                        78: 26,
                        79: 27,
                        80: [1, 28],
                        81: [1, 29],
                        82: [1, 30],
                        83: [1, 31],
                        84: [1, 32],
                        85: [1, 34],
                        86: 33
                    }, {
                        20: 41,
                        56: 48,
                        64: 42,
                        65: [1, 43],
                        72: [1, 35],
                        78: 26,
                        79: 27,
                        80: [1, 28],
                        81: [1, 29],
                        82: [1, 30],
                        83: [1, 31],
                        84: [1, 32],
                        85: [1, 34],
                        86: 33
                    }, {
                        33: [2, 78],
                        49: 49,
                        65: [2, 78],
                        72: [2, 78],
                        80: [2, 78],
                        81: [2, 78],
                        82: [2, 78],
                        83: [2, 78],
                        84: [2, 78],
                        85: [2, 78]
                    }, {
                        23: [2, 33],
                        33: [2, 33],
                        54: [2, 33],
                        65: [2, 33],
                        68: [2, 33],
                        72: [2, 33],
                        75: [2, 33],
                        80: [2, 33],
                        81: [2, 33],
                        82: [2, 33],
                        83: [2, 33],
                        84: [2, 33],
                        85: [2, 33]
                    }, {
                        23: [2, 34],
                        33: [2, 34],
                        54: [2, 34],
                        65: [2, 34],
                        68: [2, 34],
                        72: [2, 34],
                        75: [2, 34],
                        80: [2, 34],
                        81: [2, 34],
                        82: [2, 34],
                        83: [2, 34],
                        84: [2, 34],
                        85: [2, 34]
                    }, {
                        23: [2, 35],
                        33: [2, 35],
                        54: [2, 35],
                        65: [2, 35],
                        68: [2, 35],
                        72: [2, 35],
                        75: [2, 35],
                        80: [2, 35],
                        81: [2, 35],
                        82: [2, 35],
                        83: [2, 35],
                        84: [2, 35],
                        85: [2, 35]
                    }, {
                        23: [2, 36],
                        33: [2, 36],
                        54: [2, 36],
                        65: [2, 36],
                        68: [2, 36],
                        72: [2, 36],
                        75: [2, 36],
                        80: [2, 36],
                        81: [2, 36],
                        82: [2, 36],
                        83: [2, 36],
                        84: [2, 36],
                        85: [2, 36]
                    }, {
                        23: [2, 37],
                        33: [2, 37],
                        54: [2, 37],
                        65: [2, 37],
                        68: [2, 37],
                        72: [2, 37],
                        75: [2, 37],
                        80: [2, 37],
                        81: [2, 37],
                        82: [2, 37],
                        83: [2, 37],
                        84: [2, 37],
                        85: [2, 37]
                    }, {
                        23: [2, 38],
                        33: [2, 38],
                        54: [2, 38],
                        65: [2, 38],
                        68: [2, 38],
                        72: [2, 38],
                        75: [2, 38],
                        80: [2, 38],
                        81: [2, 38],
                        82: [2, 38],
                        83: [2, 38],
                        84: [2, 38],
                        85: [2, 38]
                    }, {
                        23: [2, 39],
                        33: [2, 39],
                        54: [2, 39],
                        65: [2, 39],
                        68: [2, 39],
                        72: [2, 39],
                        75: [2, 39],
                        80: [2, 39],
                        81: [2, 39],
                        82: [2, 39],
                        83: [2, 39],
                        84: [2, 39],
                        85: [2, 39]
                    }, {
                        23: [2, 43],
                        33: [2, 43],
                        54: [2, 43],
                        65: [2, 43],
                        68: [2, 43],
                        72: [2, 43],
                        75: [2, 43],
                        80: [2, 43],
                        81: [2, 43],
                        82: [2, 43],
                        83: [2, 43],
                        84: [2, 43],
                        85: [2, 43],
                        87: [1, 50]
                    }, {
                        72: [1, 35],
                        86: 51
                    }, {
                        23: [2, 45],
                        33: [2, 45],
                        54: [2, 45],
                        65: [2, 45],
                        68: [2, 45],
                        72: [2, 45],
                        75: [2, 45],
                        80: [2, 45],
                        81: [2, 45],
                        82: [2, 45],
                        83: [2, 45],
                        84: [2, 45],
                        85: [2, 45],
                        87: [2, 45]
                    }, {
                        52: 52,
                        54: [2, 82],
                        65: [2, 82],
                        72: [2, 82],
                        80: [2, 82],
                        81: [2, 82],
                        82: [2, 82],
                        83: [2, 82],
                        84: [2, 82],
                        85: [2, 82]
                    }, {
                        25: 53,
                        38: 55,
                        39: [1, 57],
                        43: 56,
                        44: [1, 58],
                        45: 54,
                        47: [2, 54]
                    }, {
                        28: 59,
                        43: 60,
                        44: [1, 58],
                        47: [2, 56]
                    }, {
                        13: 62,
                        15: [1, 20],
                        18: [1, 61]
                    }, {
                        33: [2, 86],
                        57: 63,
                        65: [2, 86],
                        72: [2, 86],
                        80: [2, 86],
                        81: [2, 86],
                        82: [2, 86],
                        83: [2, 86],
                        84: [2, 86],
                        85: [2, 86]
                    }, {
                        33: [2, 40],
                        65: [2, 40],
                        72: [2, 40],
                        80: [2, 40],
                        81: [2, 40],
                        82: [2, 40],
                        83: [2, 40],
                        84: [2, 40],
                        85: [2, 40]
                    }, {
                        33: [2, 41],
                        65: [2, 41],
                        72: [2, 41],
                        80: [2, 41],
                        81: [2, 41],
                        82: [2, 41],
                        83: [2, 41],
                        84: [2, 41],
                        85: [2, 41]
                    }, {
                        20: 64,
                        72: [1, 35],
                        78: 26,
                        79: 27,
                        80: [1, 28],
                        81: [1, 29],
                        82: [1, 30],
                        83: [1, 31],
                        84: [1, 32],
                        85: [1, 34],
                        86: 33
                    }, {
                        26: 65,
                        47: [1, 66]
                    }, {
                        30: 67,
                        33: [2, 58],
                        65: [2, 58],
                        72: [2, 58],
                        75: [2, 58],
                        80: [2, 58],
                        81: [2, 58],
                        82: [2, 58],
                        83: [2, 58],
                        84: [2, 58],
                        85: [2, 58]
                    }, {
                        33: [2, 64],
                        35: 68,
                        65: [2, 64],
                        72: [2, 64],
                        75: [2, 64],
                        80: [2, 64],
                        81: [2, 64],
                        82: [2, 64],
                        83: [2, 64],
                        84: [2, 64],
                        85: [2, 64]
                    }, {
                        21: 69,
                        23: [2, 50],
                        65: [2, 50],
                        72: [2, 50],
                        80: [2, 50],
                        81: [2, 50],
                        82: [2, 50],
                        83: [2, 50],
                        84: [2, 50],
                        85: [2, 50]
                    }, {
                        33: [2, 90],
                        61: 70,
                        65: [2, 90],
                        72: [2, 90],
                        80: [2, 90],
                        81: [2, 90],
                        82: [2, 90],
                        83: [2, 90],
                        84: [2, 90],
                        85: [2, 90]
                    }, {
                        20: 74,
                        33: [2, 80],
                        50: 71,
                        63: 72,
                        64: 75,
                        65: [1, 43],
                        69: 73,
                        70: 76,
                        71: 77,
                        72: [1, 78],
                        78: 26,
                        79: 27,
                        80: [1, 28],
                        81: [1, 29],
                        82: [1, 30],
                        83: [1, 31],
                        84: [1, 32],
                        85: [1, 34],
                        86: 33
                    }, {
                        72: [1, 79]
                    }, {
                        23: [2, 42],
                        33: [2, 42],
                        54: [2, 42],
                        65: [2, 42],
                        68: [2, 42],
                        72: [2, 42],
                        75: [2, 42],
                        80: [2, 42],
                        81: [2, 42],
                        82: [2, 42],
                        83: [2, 42],
                        84: [2, 42],
                        85: [2, 42],
                        87: [1, 50]
                    }, {
                        20: 74,
                        53: 80,
                        54: [2, 84],
                        63: 81,
                        64: 75,
                        65: [1, 43],
                        69: 82,
                        70: 76,
                        71: 77,
                        72: [1, 78],
                        78: 26,
                        79: 27,
                        80: [1, 28],
                        81: [1, 29],
                        82: [1, 30],
                        83: [1, 31],
                        84: [1, 32],
                        85: [1, 34],
                        86: 33
                    }, {
                        26: 83,
                        47: [1, 66]
                    }, {
                        47: [2, 55]
                    }, {
                        4: 84,
                        6: 3,
                        14: [2, 46],
                        15: [2, 46],
                        19: [2, 46],
                        29: [2, 46],
                        34: [2, 46],
                        39: [2, 46],
                        44: [2, 46],
                        47: [2, 46],
                        48: [2, 46],
                        51: [2, 46],
                        55: [2, 46],
                        60: [2, 46]
                    }, {
                        47: [2, 20]
                    }, {
                        20: 85,
                        72: [1, 35],
                        78: 26,
                        79: 27,
                        80: [1, 28],
                        81: [1, 29],
                        82: [1, 30],
                        83: [1, 31],
                        84: [1, 32],
                        85: [1, 34],
                        86: 33
                    }, {
                        4: 86,
                        6: 3,
                        14: [2, 46],
                        15: [2, 46],
                        19: [2, 46],
                        29: [2, 46],
                        34: [2, 46],
                        47: [2, 46],
                        48: [2, 46],
                        51: [2, 46],
                        55: [2, 46],
                        60: [2, 46]
                    }, {
                        26: 87,
                        47: [1, 66]
                    }, {
                        47: [2, 57]
                    }, {
                        5: [2, 11],
                        14: [2, 11],
                        15: [2, 11],
                        19: [2, 11],
                        29: [2, 11],
                        34: [2, 11],
                        39: [2, 11],
                        44: [2, 11],
                        47: [2, 11],
                        48: [2, 11],
                        51: [2, 11],
                        55: [2, 11],
                        60: [2, 11]
                    }, {
                        15: [2, 49],
                        18: [2, 49]
                    }, {
                        20: 74,
                        33: [2, 88],
                        58: 88,
                        63: 89,
                        64: 75,
                        65: [1, 43],
                        69: 90,
                        70: 76,
                        71: 77,
                        72: [1, 78],
                        78: 26,
                        79: 27,
                        80: [1, 28],
                        81: [1, 29],
                        82: [1, 30],
                        83: [1, 31],
                        84: [1, 32],
                        85: [1, 34],
                        86: 33
                    }, {
                        65: [2, 94],
                        66: 91,
                        68: [2, 94],
                        72: [2, 94],
                        80: [2, 94],
                        81: [2, 94],
                        82: [2, 94],
                        83: [2, 94],
                        84: [2, 94],
                        85: [2, 94]
                    }, {
                        5: [2, 25],
                        14: [2, 25],
                        15: [2, 25],
                        19: [2, 25],
                        29: [2, 25],
                        34: [2, 25],
                        39: [2, 25],
                        44: [2, 25],
                        47: [2, 25],
                        48: [2, 25],
                        51: [2, 25],
                        55: [2, 25],
                        60: [2, 25]
                    }, {
                        20: 92,
                        72: [1, 35],
                        78: 26,
                        79: 27,
                        80: [1, 28],
                        81: [1, 29],
                        82: [1, 30],
                        83: [1, 31],
                        84: [1, 32],
                        85: [1, 34],
                        86: 33
                    }, {
                        20: 74,
                        31: 93,
                        33: [2, 60],
                        63: 94,
                        64: 75,
                        65: [1, 43],
                        69: 95,
                        70: 76,
                        71: 77,
                        72: [1, 78],
                        75: [2, 60],
                        78: 26,
                        79: 27,
                        80: [1, 28],
                        81: [1, 29],
                        82: [1, 30],
                        83: [1, 31],
                        84: [1, 32],
                        85: [1, 34],
                        86: 33
                    }, {
                        20: 74,
                        33: [2, 66],
                        36: 96,
                        63: 97,
                        64: 75,
                        65: [1, 43],
                        69: 98,
                        70: 76,
                        71: 77,
                        72: [1, 78],
                        75: [2, 66],
                        78: 26,
                        79: 27,
                        80: [1, 28],
                        81: [1, 29],
                        82: [1, 30],
                        83: [1, 31],
                        84: [1, 32],
                        85: [1, 34],
                        86: 33
                    }, {
                        20: 74,
                        22: 99,
                        23: [2, 52],
                        63: 100,
                        64: 75,
                        65: [1, 43],
                        69: 101,
                        70: 76,
                        71: 77,
                        72: [1, 78],
                        78: 26,
                        79: 27,
                        80: [1, 28],
                        81: [1, 29],
                        82: [1, 30],
                        83: [1, 31],
                        84: [1, 32],
                        85: [1, 34],
                        86: 33
                    }, {
                        20: 74,
                        33: [2, 92],
                        62: 102,
                        63: 103,
                        64: 75,
                        65: [1, 43],
                        69: 104,
                        70: 76,
                        71: 77,
                        72: [1, 78],
                        78: 26,
                        79: 27,
                        80: [1, 28],
                        81: [1, 29],
                        82: [1, 30],
                        83: [1, 31],
                        84: [1, 32],
                        85: [1, 34],
                        86: 33
                    }, {
                        33: [1, 105]
                    }, {
                        33: [2, 79],
                        65: [2, 79],
                        72: [2, 79],
                        80: [2, 79],
                        81: [2, 79],
                        82: [2, 79],
                        83: [2, 79],
                        84: [2, 79],
                        85: [2, 79]
                    }, {
                        33: [2, 81]
                    }, {
                        23: [2, 27],
                        33: [2, 27],
                        54: [2, 27],
                        65: [2, 27],
                        68: [2, 27],
                        72: [2, 27],
                        75: [2, 27],
                        80: [2, 27],
                        81: [2, 27],
                        82: [2, 27],
                        83: [2, 27],
                        84: [2, 27],
                        85: [2, 27]
                    }, {
                        23: [2, 28],
                        33: [2, 28],
                        54: [2, 28],
                        65: [2, 28],
                        68: [2, 28],
                        72: [2, 28],
                        75: [2, 28],
                        80: [2, 28],
                        81: [2, 28],
                        82: [2, 28],
                        83: [2, 28],
                        84: [2, 28],
                        85: [2, 28]
                    }, {
                        23: [2, 30],
                        33: [2, 30],
                        54: [2, 30],
                        68: [2, 30],
                        71: 106,
                        72: [1, 107],
                        75: [2, 30]
                    }, {
                        23: [2, 98],
                        33: [2, 98],
                        54: [2, 98],
                        68: [2, 98],
                        72: [2, 98],
                        75: [2, 98]
                    }, {
                        23: [2, 45],
                        33: [2, 45],
                        54: [2, 45],
                        65: [2, 45],
                        68: [2, 45],
                        72: [2, 45],
                        73: [1, 108],
                        75: [2, 45],
                        80: [2, 45],
                        81: [2, 45],
                        82: [2, 45],
                        83: [2, 45],
                        84: [2, 45],
                        85: [2, 45],
                        87: [2, 45]
                    }, {
                        23: [2, 44],
                        33: [2, 44],
                        54: [2, 44],
                        65: [2, 44],
                        68: [2, 44],
                        72: [2, 44],
                        75: [2, 44],
                        80: [2, 44],
                        81: [2, 44],
                        82: [2, 44],
                        83: [2, 44],
                        84: [2, 44],
                        85: [2, 44],
                        87: [2, 44]
                    }, {
                        54: [1, 109]
                    }, {
                        54: [2, 83],
                        65: [2, 83],
                        72: [2, 83],
                        80: [2, 83],
                        81: [2, 83],
                        82: [2, 83],
                        83: [2, 83],
                        84: [2, 83],
                        85: [2, 83]
                    }, {
                        54: [2, 85]
                    }, {
                        5: [2, 13],
                        14: [2, 13],
                        15: [2, 13],
                        19: [2, 13],
                        29: [2, 13],
                        34: [2, 13],
                        39: [2, 13],
                        44: [2, 13],
                        47: [2, 13],
                        48: [2, 13],
                        51: [2, 13],
                        55: [2, 13],
                        60: [2, 13]
                    }, {
                        38: 55,
                        39: [1, 57],
                        43: 56,
                        44: [1, 58],
                        45: 111,
                        46: 110,
                        47: [2, 76]
                    }, {
                        33: [2, 70],
                        40: 112,
                        65: [2, 70],
                        72: [2, 70],
                        75: [2, 70],
                        80: [2, 70],
                        81: [2, 70],
                        82: [2, 70],
                        83: [2, 70],
                        84: [2, 70],
                        85: [2, 70]
                    }, {
                        47: [2, 18]
                    }, {
                        5: [2, 14],
                        14: [2, 14],
                        15: [2, 14],
                        19: [2, 14],
                        29: [2, 14],
                        34: [2, 14],
                        39: [2, 14],
                        44: [2, 14],
                        47: [2, 14],
                        48: [2, 14],
                        51: [2, 14],
                        55: [2, 14],
                        60: [2, 14]
                    }, {
                        33: [1, 113]
                    }, {
                        33: [2, 87],
                        65: [2, 87],
                        72: [2, 87],
                        80: [2, 87],
                        81: [2, 87],
                        82: [2, 87],
                        83: [2, 87],
                        84: [2, 87],
                        85: [2, 87]
                    }, {
                        33: [2, 89]
                    }, {
                        20: 74,
                        63: 115,
                        64: 75,
                        65: [1, 43],
                        67: 114,
                        68: [2, 96],
                        69: 116,
                        70: 76,
                        71: 77,
                        72: [1, 78],
                        78: 26,
                        79: 27,
                        80: [1, 28],
                        81: [1, 29],
                        82: [1, 30],
                        83: [1, 31],
                        84: [1, 32],
                        85: [1, 34],
                        86: 33
                    }, {
                        33: [1, 117]
                    }, {
                        32: 118,
                        33: [2, 62],
                        74: 119,
                        75: [1, 120]
                    }, {
                        33: [2, 59],
                        65: [2, 59],
                        72: [2, 59],
                        75: [2, 59],
                        80: [2, 59],
                        81: [2, 59],
                        82: [2, 59],
                        83: [2, 59],
                        84: [2, 59],
                        85: [2, 59]
                    }, {
                        33: [2, 61],
                        75: [2, 61]
                    }, {
                        33: [2, 68],
                        37: 121,
                        74: 122,
                        75: [1, 120]
                    }, {
                        33: [2, 65],
                        65: [2, 65],
                        72: [2, 65],
                        75: [2, 65],
                        80: [2, 65],
                        81: [2, 65],
                        82: [2, 65],
                        83: [2, 65],
                        84: [2, 65],
                        85: [2, 65]
                    }, {
                        33: [2, 67],
                        75: [2, 67]
                    }, {
                        23: [1, 123]
                    }, {
                        23: [2, 51],
                        65: [2, 51],
                        72: [2, 51],
                        80: [2, 51],
                        81: [2, 51],
                        82: [2, 51],
                        83: [2, 51],
                        84: [2, 51],
                        85: [2, 51]
                    }, {
                        23: [2, 53]
                    }, {
                        33: [1, 124]
                    }, {
                        33: [2, 91],
                        65: [2, 91],
                        72: [2, 91],
                        80: [2, 91],
                        81: [2, 91],
                        82: [2, 91],
                        83: [2, 91],
                        84: [2, 91],
                        85: [2, 91]
                    }, {
                        33: [2, 93]
                    }, {
                        5: [2, 22],
                        14: [2, 22],
                        15: [2, 22],
                        19: [2, 22],
                        29: [2, 22],
                        34: [2, 22],
                        39: [2, 22],
                        44: [2, 22],
                        47: [2, 22],
                        48: [2, 22],
                        51: [2, 22],
                        55: [2, 22],
                        60: [2, 22]
                    }, {
                        23: [2, 99],
                        33: [2, 99],
                        54: [2, 99],
                        68: [2, 99],
                        72: [2, 99],
                        75: [2, 99]
                    }, {
                        73: [1, 108]
                    }, {
                        20: 74,
                        63: 125,
                        64: 75,
                        65: [1, 43],
                        72: [1, 35],
                        78: 26,
                        79: 27,
                        80: [1, 28],
                        81: [1, 29],
                        82: [1, 30],
                        83: [1, 31],
                        84: [1, 32],
                        85: [1, 34],
                        86: 33
                    }, {
                        5: [2, 23],
                        14: [2, 23],
                        15: [2, 23],
                        19: [2, 23],
                        29: [2, 23],
                        34: [2, 23],
                        39: [2, 23],
                        44: [2, 23],
                        47: [2, 23],
                        48: [2, 23],
                        51: [2, 23],
                        55: [2, 23],
                        60: [2, 23]
                    }, {
                        47: [2, 19]
                    }, {
                        47: [2, 77]
                    }, {
                        20: 74,
                        33: [2, 72],
                        41: 126,
                        63: 127,
                        64: 75,
                        65: [1, 43],
                        69: 128,
                        70: 76,
                        71: 77,
                        72: [1, 78],
                        75: [2, 72],
                        78: 26,
                        79: 27,
                        80: [1, 28],
                        81: [1, 29],
                        82: [1, 30],
                        83: [1, 31],
                        84: [1, 32],
                        85: [1, 34],
                        86: 33
                    }, {
                        5: [2, 24],
                        14: [2, 24],
                        15: [2, 24],
                        19: [2, 24],
                        29: [2, 24],
                        34: [2, 24],
                        39: [2, 24],
                        44: [2, 24],
                        47: [2, 24],
                        48: [2, 24],
                        51: [2, 24],
                        55: [2, 24],
                        60: [2, 24]
                    }, {
                        68: [1, 129]
                    }, {
                        65: [2, 95],
                        68: [2, 95],
                        72: [2, 95],
                        80: [2, 95],
                        81: [2, 95],
                        82: [2, 95],
                        83: [2, 95],
                        84: [2, 95],
                        85: [2, 95]
                    }, {
                        68: [2, 97]
                    }, {
                        5: [2, 21],
                        14: [2, 21],
                        15: [2, 21],
                        19: [2, 21],
                        29: [2, 21],
                        34: [2, 21],
                        39: [2, 21],
                        44: [2, 21],
                        47: [2, 21],
                        48: [2, 21],
                        51: [2, 21],
                        55: [2, 21],
                        60: [2, 21]
                    }, {
                        33: [1, 130]
                    }, {
                        33: [2, 63]
                    }, {
                        72: [1, 132],
                        76: 131
                    }, {
                        33: [1, 133]
                    }, {
                        33: [2, 69]
                    }, {
                        15: [2, 12],
                        18: [2, 12]
                    }, {
                        14: [2, 26],
                        15: [2, 26],
                        19: [2, 26],
                        29: [2, 26],
                        34: [2, 26],
                        47: [2, 26],
                        48: [2, 26],
                        51: [2, 26],
                        55: [2, 26],
                        60: [2, 26]
                    }, {
                        23: [2, 31],
                        33: [2, 31],
                        54: [2, 31],
                        68: [2, 31],
                        72: [2, 31],
                        75: [2, 31]
                    }, {
                        33: [2, 74],
                        42: 134,
                        74: 135,
                        75: [1, 120]
                    }, {
                        33: [2, 71],
                        65: [2, 71],
                        72: [2, 71],
                        75: [2, 71],
                        80: [2, 71],
                        81: [2, 71],
                        82: [2, 71],
                        83: [2, 71],
                        84: [2, 71],
                        85: [2, 71]
                    }, {
                        33: [2, 73],
                        75: [2, 73]
                    }, {
                        23: [2, 29],
                        33: [2, 29],
                        54: [2, 29],
                        65: [2, 29],
                        68: [2, 29],
                        72: [2, 29],
                        75: [2, 29],
                        80: [2, 29],
                        81: [2, 29],
                        82: [2, 29],
                        83: [2, 29],
                        84: [2, 29],
                        85: [2, 29]
                    }, {
                        14: [2, 15],
                        15: [2, 15],
                        19: [2, 15],
                        29: [2, 15],
                        34: [2, 15],
                        39: [2, 15],
                        44: [2, 15],
                        47: [2, 15],
                        48: [2, 15],
                        51: [2, 15],
                        55: [2, 15],
                        60: [2, 15]
                    }, {
                        72: [1, 137],
                        77: [1, 136]
                    }, {
                        72: [2, 100],
                        77: [2, 100]
                    }, {
                        14: [2, 16],
                        15: [2, 16],
                        19: [2, 16],
                        29: [2, 16],
                        34: [2, 16],
                        44: [2, 16],
                        47: [2, 16],
                        48: [2, 16],
                        51: [2, 16],
                        55: [2, 16],
                        60: [2, 16]
                    }, {
                        33: [1, 138]
                    }, {
                        33: [2, 75]
                    }, {
                        33: [2, 32]
                    }, {
                        72: [2, 101],
                        77: [2, 101]
                    }, {
                        14: [2, 17],
                        15: [2, 17],
                        19: [2, 17],
                        29: [2, 17],
                        34: [2, 17],
                        39: [2, 17],
                        44: [2, 17],
                        47: [2, 17],
                        48: [2, 17],
                        51: [2, 17],
                        55: [2, 17],
                        60: [2, 17]
                    }],
                    defaultActions: {
                        4: [2, 1],
                        54: [2, 55],
                        56: [2, 20],
                        60: [2, 57],
                        73: [2, 81],
                        82: [2, 85],
                        86: [2, 18],
                        90: [2, 89],
                        101: [2, 53],
                        104: [2, 93],
                        110: [2, 19],
                        111: [2, 77],
                        116: [2, 97],
                        119: [2, 63],
                        122: [2, 69],
                        135: [2, 75],
                        136: [2, 32]
                    },
                    parseError: function (n) {
                        throw new Error(n);
                    },
                    parse: function (n) {
                        function it() {
                            var n;
                            return n = k.lexer.lex() || 1, "number" != typeof n && (n = k.symbols_[n] || n), n
                        }
                        var k = this,
                            r = [0],
                            e = [null],
                            t = [],
                            h = this.table,
                            d = "",
                            c = 0,
                            g = 0,
                            y = 0,
                            l, nt, i, p, o, u, w, a, f, tt, v, s, b;
                        for (this.lexer.setInput(n), this.lexer.yy = this.yy, this.yy.lexer = this.lexer, this.yy.parser = this, "undefined" == typeof this.lexer.yylloc && (this.lexer.yylloc = {}), l = this.lexer.yylloc, t.push(l), nt = this.lexer.options && this.lexer.options.ranges, "function" == typeof this.yy.parseError && (this.parseError = this.yy.parseError), s = {};;) {
                            if ((o = r[r.length - 1], this.defaultActions[o] ? u = this.defaultActions[o] : (null !== i && "undefined" != typeof i || (i = it()), u = h[o] && h[o][i]), "undefined" == typeof u || !u.length || !u[0]) && (b = "", !y)) {
                                v = [];
                                for (a in h[o]) this.terminals_[a] && a > 2 && v.push("'" + this.terminals_[a] + "'");
                                b = this.lexer.showPosition ? "Parse error on line " + (c + 1) + ":\n" + this.lexer.showPosition() + "\nExpecting " + v.join(", ") + ", got '" + (this.terminals_[i] || i) + "'" : "Parse error on line " + (c + 1) + ": Unexpected " + (1 == i ? "end of input" : "'" + (this.terminals_[i] || i) + "'");
                                this.parseError(b, {
                                    text: this.lexer.match,
                                    token: this.terminals_[i] || i,
                                    line: this.lexer.yylineno,
                                    loc: l,
                                    expected: v
                                })
                            }
                            if (u[0] instanceof Array && u.length > 1) throw new Error("Parse Error: multiple actions possible at state: " + o + ", token: " + i);
                            switch (u[0]) {
                                case 1:
                                    r.push(i);
                                    e.push(this.lexer.yytext);
                                    t.push(this.lexer.yylloc);
                                    r.push(u[1]);
                                    i = null;
                                    p ? (i = p, p = null) : (g = this.lexer.yyleng, d = this.lexer.yytext, c = this.lexer.yylineno, l = this.lexer.yylloc, y > 0 && y--);
                                    break;
                                case 2:
                                    if (f = this.productions_[u[1]][1], s.$ = e[e.length - f], s._$ = {
                                            first_line: t[t.length - (f || 1)].first_line,
                                            last_line: t[t.length - 1].last_line,
                                            first_column: t[t.length - (f || 1)].first_column,
                                            last_column: t[t.length - 1].last_column
                                        }, nt && (s._$.range = [t[t.length - (f || 1)].range[0], t[t.length - 1].range[1]]), w = this.performAction.call(s, d, g, c, this.yy, u[1], e, t), "undefined" != typeof w) return w;
                                    f && (r = r.slice(0, -2 * f), e = e.slice(0, -1 * f), t = t.slice(0, -1 * f));
                                    r.push(this.productions_[u[1]][0]);
                                    e.push(s.$);
                                    t.push(s._$);
                                    tt = h[r[r.length - 2]][r[r.length - 1]];
                                    r.push(tt);
                                    break;
                                case 3:
                                    return !0
                            }
                        }
                        return !0
                    }
                },
                i = function () {
                    var n = {
                        EOF: 1,
                        parseError: function (n, t) {
                            if (!this.yy.parser) throw new Error(n);
                            this.yy.parser.parseError(n, t)
                        },
                        setInput: function (n) {
                            return this._input = n, this._more = this._less = this.done = !1, this.yylineno = this.yyleng = 0, this.yytext = this.matched = this.match = "", this.conditionStack = ["INITIAL"], this.yylloc = {
                                first_line: 1,
                                first_column: 0,
                                last_line: 1,
                                last_column: 0
                            }, this.options.ranges && (this.yylloc.range = [0, 0]), this.offset = 0, this
                        },
                        input: function () {
                            var n = this._input[0],
                                t;
                            return this.yytext += n, this.yyleng++, this.offset++, this.match += n, this.matched += n, t = n.match(/(?:\r\n?|\n).*/g), t ? (this.yylineno++, this.yylloc.last_line++) : this.yylloc.last_column++, this.options.ranges && this.yylloc.range[1]++, this._input = this._input.slice(1), n
                        },
                        unput: function (n) {
                            var i = n.length,
                                t = n.split(/(?:\r\n?|\n)/g),
                                r, u;
                            return this._input = n + this._input, this.yytext = this.yytext.substr(0, this.yytext.length - i - 1), this.offset -= i, r = this.match.split(/(?:\r\n?|\n)/g), this.match = this.match.substr(0, this.match.length - 1), this.matched = this.matched.substr(0, this.matched.length - 1), t.length - 1 && (this.yylineno -= t.length - 1), u = this.yylloc.range, this.yylloc = {
                                first_line: this.yylloc.first_line,
                                last_line: this.yylineno + 1,
                                first_column: this.yylloc.first_column,
                                last_column: t ? (t.length === r.length ? this.yylloc.first_column : 0) + r[r.length - t.length].length - t[0].length : this.yylloc.first_column - i
                            }, this.options.ranges && (this.yylloc.range = [u[0], u[0] + this.yyleng - i]), this
                        },
                        more: function () {
                            return this._more = !0, this
                        },
                        less: function (n) {
                            this.unput(this.match.slice(n))
                        },
                        pastInput: function () {
                            var n = this.matched.substr(0, this.matched.length - this.match.length);
                            return (n.length > 20 ? "..." : "") + n.substr(-20).replace(/\n/g, "")
                        },
                        upcomingInput: function () {
                            var n = this.match;
                            return n.length < 20 && (n += this._input.substr(0, 20 - n.length)), (n.substr(0, 20) + (n.length > 20 ? "..." : "")).replace(/\n/g, "")
                        },
                        showPosition: function () {
                            var n = this.pastInput(),
                                t = new Array(n.length + 1).join("-");
                            return n + this.upcomingInput() + "\n" + t + "^"
                        },
                        next: function () {
                            var f, n, r, e, t, u, i;
                            if (this.done) return this.EOF;
                            for (this._input || (this.done = !0), this._more || (this.yytext = "", this.match = ""), u = this._currentRules(), i = 0; i < u.length && (r = this._input.match(this.rules[u[i]]), !r || n && !(r[0].length > n[0].length) || (n = r, e = i, this.options.flex)); i++);
                            return n ? (t = n[0].match(/(?:\r\n?|\n).*/g), t && (this.yylineno += t.length), this.yylloc = {
                                first_line: this.yylloc.last_line,
                                last_line: this.yylineno + 1,
                                first_column: this.yylloc.last_column,
                                last_column: t ? t[t.length - 1].length - t[t.length - 1].match(/\r?\n?/)[0].length : this.yylloc.last_column + n[0].length
                            }, this.yytext += n[0], this.match += n[0], this.matches = n, this.yyleng = this.yytext.length, this.options.ranges && (this.yylloc.range = [this.offset, this.offset += this.yyleng]), this._more = !1, this._input = this._input.slice(n[0].length), this.matched += n[0], f = this.performAction.call(this, this.yy, this, u[e], this.conditionStack[this.conditionStack.length - 1]), this.done && this._input && (this.done = !1), f ? f : void 0) : "" === this._input ? this.EOF : this.parseError("Lexical error on line " + (this.yylineno + 1) + ". Unrecognized text.\n" + this.showPosition(), {
                                text: "",
                                token: null,
                                line: this.yylineno
                            })
                        },
                        lex: function () {
                            var n = this.next();
                            return "undefined" != typeof n ? n : this.lex()
                        },
                        begin: function (n) {
                            this.conditionStack.push(n)
                        },
                        popState: function () {
                            return this.conditionStack.pop()
                        },
                        _currentRules: function () {
                            return this.conditions[this.conditionStack[this.conditionStack.length - 1]].rules
                        },
                        topState: function () {
                            return this.conditionStack[this.conditionStack.length - 2]
                        },
                        pushState: function (n) {
                            this.begin(n)
                        }
                    };
                    return n.options = {}, n.performAction = function (n, t, i) {
                        function r(n, i) {
                            return t.yytext = t.yytext.substring(n, t.yyleng - i + n)
                        }
                        switch (i) {
                            case 0:
                                if ("\\\\" === t.yytext.slice(-2) ? (r(0, 1), this.begin("mu")) : "\\" === t.yytext.slice(-1) ? (r(0, 1), this.begin("emu")) : this.begin("mu"), t.yytext) return 15;
                                break;
                            case 1:
                                return 15;
                            case 2:
                                return this.popState(), 15;
                            case 3:
                                return this.begin("raw"), 15;
                            case 4:
                                return this.popState(), "raw" === this.conditionStack[this.conditionStack.length - 1] ? 15 : (r(5, 9), "END_RAW_BLOCK");
                            case 5:
                                return 15;
                            case 6:
                                return this.popState(), 14;
                            case 7:
                                return 65;
                            case 8:
                                return 68;
                            case 9:
                                return 19;
                            case 10:
                                return this.popState(), this.begin("raw"), 23;
                            case 11:
                                return 55;
                            case 12:
                                return 60;
                            case 13:
                                return 29;
                            case 14:
                                return 47;
                            case 15:
                                return this.popState(), 44;
                            case 16:
                                return this.popState(), 44;
                            case 17:
                                return 34;
                            case 18:
                                return 39;
                            case 19:
                                return 51;
                            case 20:
                                return 48;
                            case 21:
                                this.unput(t.yytext);
                                this.popState();
                                this.begin("com");
                                break;
                            case 22:
                                return this.popState(), 14;
                            case 23:
                                return 48;
                            case 24:
                                return 73;
                            case 25:
                                return 72;
                            case 26:
                                return 72;
                            case 27:
                                return 87;
                            case 29:
                                return this.popState(), 54;
                            case 30:
                                return this.popState(), 33;
                            case 31:
                                return t.yytext = r(1, 2).replace(/\\"/g, '"'), 80;
                            case 32:
                                return t.yytext = r(1, 2).replace(/\\'/g, "'"), 80;
                            case 33:
                                return 85;
                            case 34:
                                return 82;
                            case 35:
                                return 82;
                            case 36:
                                return 83;
                            case 37:
                                return 84;
                            case 38:
                                return 81;
                            case 39:
                                return 75;
                            case 40:
                                return 77;
                            case 41:
                                return 72;
                            case 42:
                                return t.yytext = t.yytext.replace(/\\([\\\]])/g, "$1"), 72;
                            case 43:
                                return "INVALID";
                            case 44:
                                return 5
                        }
                    }, n.rules = [/^(?:[^\x00]*?(?=(\{\{)))/, /^(?:[^\x00]+)/, /^(?:[^\x00]{2,}?(?=(\{\{|\\\{\{|\\\\\{\{|$)))/, /^(?:\{\{\{\{(?=[^\/]))/, /^(?:\{\{\{\{\/[^\s!"#%-,\.\/;->@\[-\^`\{-~]+(?=[=}\s\/.])\}\}\}\})/, /^(?:[^\x00]+?(?=(\{\{\{\{)))/, /^(?:[\s\S]*?--(~)?\}\})/, /^(?:\()/, /^(?:\))/, /^(?:\{\{\{\{)/, /^(?:\}\}\}\})/, /^(?:\{\{(~)?>)/, /^(?:\{\{(~)?#>)/, /^(?:\{\{(~)?#\*?)/, /^(?:\{\{(~)?\/)/, /^(?:\{\{(~)?\^\s*(~)?\}\})/, /^(?:\{\{(~)?\s*else\s*(~)?\}\})/, /^(?:\{\{(~)?\^)/, /^(?:\{\{(~)?\s*else\b)/, /^(?:\{\{(~)?\{)/, /^(?:\{\{(~)?&)/, /^(?:\{\{(~)?!--)/, /^(?:\{\{(~)?![\s\S]*?\}\})/, /^(?:\{\{(~)?\*?)/, /^(?:=)/, /^(?:\.\.)/, /^(?:\.(?=([=~}\s\/.)|])))/, /^(?:[\/.])/, /^(?:\s+)/, /^(?:\}(~)?\}\})/, /^(?:(~)?\}\})/, /^(?:"(\\["]|[^"])*")/, /^(?:'(\\[']|[^'])*')/, /^(?:@)/, /^(?:true(?=([~}\s)])))/, /^(?:false(?=([~}\s)])))/, /^(?:undefined(?=([~}\s)])))/, /^(?:null(?=([~}\s)])))/, /^(?:-?[0-9]+(?:\.[0-9]+)?(?=([~}\s)])))/, /^(?:as\s+\|)/, /^(?:\|)/, /^(?:([^\s!"#%-,\.\/;->@\[-\^`\{-~]+(?=([=~}\s\/.)|]))))/, /^(?:\[(\\\]|[^\]])*\])/, /^(?:.)/, /^(?:$)/], n.conditions = {
                        mu: {
                            rules: [7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44],
                            inclusive: !1
                        },
                        emu: {
                            rules: [2],
                            inclusive: !1
                        },
                        com: {
                            rules: [6],
                            inclusive: !1
                        },
                        raw: {
                            rules: [3, 4, 5],
                            inclusive: !1
                        },
                        INITIAL: {
                            rules: [0, 1, 44],
                            inclusive: !0
                        }
                    }, n
                }();
            return t.lexer = i, n.prototype = t, t.Parser = n, new n
        }();
        t["default"] = i;
        n.exports = t["default"]
    }, function (n, t, i) {
        "use strict";

        function r() {
            var n = arguments.length <= 0 || void 0 === arguments[0] ? {} : arguments[0];
            this.options = n
        }

        function e(n, t, i) {
            void 0 === t && (t = n.length);
            var r = n[t - 1],
                u = n[t - 2];
            return r ? "ContentStatement" === r.type ? (u || !i ? /\r?\n\s*?$/ : /(^|\r?\n)\s*?$/).test(r.original) : void 0 : i
        }

        function o(n, t, i) {
            void 0 === t && (t = -1);
            var r = n[t + 1],
                u = n[t + 2];
            return r ? "ContentStatement" === r.type ? (u || !i ? /^\s*?\r?\n/ : /^\s*?(\r?\n|$)/).test(r.original) : void 0 : i
        }

        function f(n, t, i) {
            var r = n[null == t ? 0 : t + 1],
                u;
            r && "ContentStatement" === r.type && (i || !r.rightStripped) && (u = r.value, r.value = r.value.replace(i ? /^\s+/ : /^[ \t]*\r?\n?/, ""), r.rightStripped = r.value !== u)
        }

        function u(n, t, i) {
            var r = n[null == t ? n.length - 1 : t - 1],
                u;
            if (r && "ContentStatement" === r.type && (i || !r.leftStripped)) return u = r.value, r.value = r.value.replace(i ? /\s+$/ : /[ \t]+$/, ""), r.leftStripped = r.value !== u, r.leftStripped
        }
        var c = i(1)["default"],
            s, h;
        t.__esModule = !0;
        s = i(49);
        h = c(s);
        r.prototype = new h["default"];
        r.prototype.Program = function (n) {
            var h = !this.options.ignoreStandalone,
                c = !this.isRootSeen,
                r, s;
            this.isRootSeen = !0;
            for (var i = n.body, t = 0, v = i.length; t < v; t++)
                if (r = i[t], s = this.accept(r), s) {
                    var l = e(i, t, c),
                        a = o(i, t, c),
                        y = s.openStandalone && l,
                        p = s.closeStandalone && a,
                        w = s.inlineStandalone && l && a;
                    s.close && f(i, t, !0);
                    s.open && u(i, t, !0);
                    h && w && (f(i, t), u(i, t) && "PartialStatement" === r.type && (r.indent = /([ \t]+$)/.exec(i[t - 1].original)[1]));
                    h && y && (f((r.program || r.inverse).body), u(i, t));
                    h && p && (f(i, t), u((r.inverse || r.program).body))
                } return n
        };
        r.prototype.BlockStatement = r.prototype.DecoratorBlock = r.prototype.PartialBlockStatement = function (n) {
            var c, h;
            this.accept(n.program);
            this.accept(n.inverse);
            var t = n.program || n.inverse,
                i = n.program && n.inverse,
                r = i,
                s = i;
            if (i && i.chained)
                for (r = i.body[0].program; s.chained;) s = s.body[s.body.length - 1].program;
            return c = {
                open: n.openStrip.open,
                close: n.closeStrip.close,
                openStandalone: o(t.body),
                closeStandalone: e((r || t).body)
            }, (n.openStrip.close && f(t.body, null, !0), i) ? (h = n.inverseStrip, h.open && u(t.body, null, !0), h.close && f(r.body, null, !0), n.closeStrip.open && u(s.body, null, !0), !this.options.ignoreStandalone && e(t.body) && o(r.body) && (u(t.body), f(r.body))) : n.closeStrip.open && u(t.body, null, !0), c
        };
        r.prototype.Decorator = r.prototype.MustacheStatement = function (n) {
            return n.strip
        };
        r.prototype.PartialStatement = r.prototype.CommentStatement = function (n) {
            var t = n.strip || {};
            return {
                inlineStandalone: !0,
                open: t.open,
                close: t.close
            }
        };
        t["default"] = r;
        n.exports = t["default"]
    }, function (n, t, i) {
        "use strict";

        function r() {
            this.parents = []
        }

        function u(n) {
            this.acceptRequired(n, "path");
            this.acceptArray(n.params);
            this.acceptKey(n, "hash")
        }

        function e(n) {
            u.call(this, n);
            this.acceptKey(n, "program");
            this.acceptKey(n, "inverse")
        }

        function o(n) {
            this.acceptRequired(n, "name");
            this.acceptArray(n.params);
            this.acceptKey(n, "hash")
        }
        var h = i(1)["default"],
            s, f;
        t.__esModule = !0;
        s = i(6);
        f = h(s);
        r.prototype = {
            constructor: r,
            mutating: !1,
            acceptKey: function (n, t) {
                var i = this.accept(n[t]);
                if (this.mutating) {
                    if (i && !r.prototype[i.type]) throw new f["default"]('Unexpected node type "' + i.type + '" found when accepting ' + t + " on " + n.type);
                    n[t] = i
                }
            },
            acceptRequired: function (n, t) {
                if (this.acceptKey(n, t), !n[t]) throw new f["default"](n.type + " requires " + t);
            },
            acceptArray: function (n) {
                for (var t = 0, i = n.length; t < i; t++) this.acceptKey(n, t), n[t] || (n.splice(t, 1), t--, i--)
            },
            accept: function (n) {
                if (n) {
                    if (!this[n.type]) throw new f["default"]("Unknown type: " + n.type, n);
                    this.current && this.parents.unshift(this.current);
                    this.current = n;
                    var t = this[n.type](n);
                    return this.current = this.parents.shift(), !this.mutating || t ? t : t !== !1 ? n : void 0
                }
            },
            Program: function (n) {
                this.acceptArray(n.body)
            },
            MustacheStatement: u,
            Decorator: u,
            BlockStatement: e,
            DecoratorBlock: e,
            PartialStatement: o,
            PartialBlockStatement: function (n) {
                o.call(this, n);
                this.acceptKey(n, "program")
            },
            ContentStatement: function () {},
            CommentStatement: function () {},
            SubExpression: u,
            PathExpression: function () {},
            StringLiteral: function () {},
            NumberLiteral: function () {},
            BooleanLiteral: function () {},
            UndefinedLiteral: function () {},
            NullLiteral: function () {},
            Hash: function (n) {
                this.acceptArray(n.pairs)
            },
            HashPair: function (n) {
                this.acceptRequired(n, "value")
            }
        };
        t["default"] = r;
        n.exports = t["default"]
    }, function (n, t, i) {
        "use strict";

        function u(n, t) {
            if (t = t.path ? t.path.original : t, n.path.original !== t) {
                var i = {
                    loc: n.path.loc
                };
                throw new r["default"](n.path.original + " doesn't match " + t, i);
            }
        }

        function e(n, t) {
            this.source = n;
            this.start = {
                line: t.first_line,
                column: t.first_column
            };
            this.end = {
                line: t.last_line,
                column: t.last_column
            }
        }

        function o(n) {
            return /^\[.*\]$/.test(n) ? n.substring(1, n.length - 1) : n
        }

        function s(n, t) {
            return {
                open: "~" === n.charAt(2),
                close: "~" === t.charAt(t.length - 3)
            }
        }

        function h(n) {
            return n.replace(/^\{\{~?!-?-?/, "").replace(/-?-?~?\}\}$/, "")
        }

        function c(n, t, i) {
            var u, h;
            i = this.locInfo(i);
            for (var e = n ? "@" : "", o = [], s = 0, f = 0, c = t.length; f < c; f++)
                if (u = t[f].part, h = t[f].original !== u, e += (t[f].separator || "") + u, h || ".." !== u && "." !== u && "this" !== u) o.push(u);
                else {
                    if (o.length > 0) throw new r["default"]("Invalid path: " + e, {
                        loc: i
                    });
                    ".." === u && s++
                } return {
                type: "PathExpression",
                data: n,
                depth: s,
                parts: o,
                original: e,
                loc: i
            }
        }

        function l(n, t, i, r, u, f) {
            var e = r.charAt(3) || r.charAt(2),
                o = "{" !== e && "&" !== e,
                s = /\*/.test(r);
            return {
                type: s ? "Decorator" : "MustacheStatement",
                path: n,
                params: t,
                hash: i,
                escaped: o,
                strip: u,
                loc: this.locInfo(f)
            }
        }

        function a(n, t, i, r) {
            u(n, i);
            r = this.locInfo(r);
            var f = {
                type: "Program",
                body: t,
                strip: {},
                loc: r
            };
            return {
                type: "BlockStatement",
                path: n.path,
                params: n.params,
                hash: n.hash,
                program: f,
                openStrip: {},
                inverseStrip: {},
                closeStrip: {},
                loc: r
            }
        }

        function v(n, t, i, f, e, o) {
            var h, s, c;
            if (f && f.path && u(n, f), h = /\*/.test(n.open), t.blockParams = n.blockParams, s = void 0, c = void 0, i) {
                if (h) throw new r["default"]("Unexpected inverse block on decorator", i);
                i.chain && (i.program.body[0].closeStrip = f.strip);
                c = i.strip;
                s = i.program
            }
            return e && (e = s, s = t, t = e), {
                type: h ? "DecoratorBlock" : "BlockStatement",
                path: n.path,
                params: n.params,
                hash: n.hash,
                program: t,
                inverse: s,
                openStrip: n.strip,
                inverseStrip: c,
                closeStrip: f && f.strip,
                loc: this.locInfo(o)
            }
        }

        function y(n, t) {
            if (!t && n.length) {
                var i = n[0].loc,
                    r = n[n.length - 1].loc;
                i && r && (t = {
                    source: i.source,
                    start: {
                        line: i.start.line,
                        column: i.start.column
                    },
                    end: {
                        line: r.end.line,
                        column: r.end.column
                    }
                })
            }
            return {
                type: "Program",
                body: n,
                strip: {},
                loc: t
            }
        }

        function p(n, t, i, r) {
            return u(n, i), {
                type: "PartialBlockStatement",
                name: n.path,
                params: n.params,
                hash: n.hash,
                program: t,
                openStrip: n.strip,
                closeStrip: i && i.strip,
                loc: this.locInfo(r)
            }
        }
        var w = i(1)["default"],
            f, r;
        t.__esModule = !0;
        t.SourceLocation = e;
        t.id = o;
        t.stripFlags = s;
        t.stripComment = h;
        t.preparePath = c;
        t.prepareMustache = l;
        t.prepareRawBlock = a;
        t.prepareBlock = v;
        t.prepareProgram = y;
        t.preparePartialBlock = p;
        f = i(6);
        r = w(f)
    }, function (n, t, i) {
        "use strict";

        function e() {}

        function c(n, t, i) {
            if (null == n || "string" != typeof n && "Program" !== n.type) throw new r["default"]("You must pass a string or Handlebars AST to Handlebars.precompile. You passed " + n);
            t = t || {};
            "data" in t || (t.data = !0);
            t.compat && (t.useDepths = !0);
            var u = i.parse(n, t),
                f = (new i.Compiler).compile(u, t);
            return (new i.JavaScriptCompiler).compile(f, t)
        }

        function l(n, t, i) {
            function e() {
                var r = i.parse(n, t),
                    u = (new i.Compiler).compile(r, t),
                    f = (new i.JavaScriptCompiler).compile(u, t, void 0, !0);
                return i.template(f)
            }

            function o(n, t) {
                return f || (f = e()), f.call(this, n, t)
            }
            if (void 0 === t && (t = {}), null == n || "string" != typeof n && "Program" !== n.type) throw new r["default"]("You must pass a string or Handlebars AST to Handlebars.compile. You passed " + n);
            t = u.extend({}, t);
            "data" in t || (t.data = !0);
            t.compat && (t.useDepths = !0);
            var f = void 0;
            return o._setup = function (n) {
                return f || (f = e()), f._setup(n)
            }, o._child = function (n, t, i, r) {
                return f || (f = e()), f._child(n, t, i, r)
            }, o
        }

        function o(n, t) {
            if (n === t) return !0;
            if (u.isArray(n) && u.isArray(t) && n.length === t.length) {
                for (var i = 0; i < n.length; i++)
                    if (!o(n[i], t[i])) return !1;
                return !0
            }
        }

        function s(n) {
            if (!n.path.parts) {
                var t = n.path;
                n.path = {
                    type: "PathExpression",
                    data: !1,
                    depth: 0,
                    parts: [t.original + ""],
                    original: t.original + "",
                    loc: t.loc
                }
            }
        }
        var a = i(34)["default"],
            h = i(1)["default"];
        t.__esModule = !0;
        t.Compiler = e;
        t.precompile = c;
        t.compile = l;
        var v = i(6),
            r = h(v),
            u = i(5),
            y = i(45),
            f = h(y),
            p = [].slice;
        e.prototype = {
            compiler: e,
            equals: function (n) {
                var i = this.opcodes.length,
                    r, u, t;
                if (n.opcodes.length !== i) return !1;
                for (t = 0; t < i; t++)
                    if (r = this.opcodes[t], u = n.opcodes[t], r.opcode !== u.opcode || !o(r.args, u.args)) return !1;
                for (i = this.children.length, t = 0; t < i; t++)
                    if (!this.children[t].equals(n.children[t])) return !1;
                return !0
            },
            guid: 0,
            compile: function (n, t) {
                return this.sourceNode = [], this.opcodes = [], this.children = [], this.options = t, this.stringParams = t.stringParams, this.trackIds = t.trackIds, t.blockParams = t.blockParams || [], t.knownHelpers = u.extend(a(null), {
                    helperMissing: !0,
                    blockHelperMissing: !0,
                    each: !0,
                    "if": !0,
                    unless: !0,
                    "with": !0,
                    log: !0,
                    lookup: !0
                }, t.knownHelpers), this.accept(n)
            },
            compileProgram: function (n) {
                var r = new this.compiler,
                    t = r.compile(n, this.options),
                    i = this.guid++;
                return this.usePartial = this.usePartial || t.usePartial, this.children[i] = t, this.useDepths = this.useDepths || t.useDepths, i
            },
            accept: function (n) {
                if (!this[n.type]) throw new r["default"]("Unknown type: " + n.type, n);
                this.sourceNode.unshift(n);
                var t = this[n.type](n);
                return this.sourceNode.shift(), t
            },
            Program: function (n) {
                this.options.blockParams.unshift(n.blockParams);
                for (var i = n.body, r = i.length, t = 0; t < r; t++) this.accept(i[t]);
                return this.options.blockParams.shift(), this.isSimple = 1 === r, this.blockParams = n.blockParams ? n.blockParams.length : 0, this
            },
            BlockStatement: function (n) {
                var t, i, r;
                s(n);
                t = n.program;
                i = n.inverse;
                t = t && this.compileProgram(t);
                i = i && this.compileProgram(i);
                r = this.classifySexpr(n);
                "helper" === r ? this.helperSexpr(n, t, i) : "simple" === r ? (this.simpleSexpr(n), this.opcode("pushProgram", t), this.opcode("pushProgram", i), this.opcode("emptyHash"), this.opcode("blockValue", n.path.original)) : (this.ambiguousSexpr(n, t, i), this.opcode("pushProgram", t), this.opcode("pushProgram", i), this.opcode("emptyHash"), this.opcode("ambiguousBlockValue"));
                this.opcode("append")
            },
            DecoratorBlock: function (n) {
                var t = n.program && this.compileProgram(n.program),
                    i = this.setupFullMustacheParams(n, t, void 0),
                    r = n.path;
                this.useDecorators = !0;
                this.opcode("registerDecorator", i.length, r.original)
            },
            PartialStatement: function (n) {
                var u, t, e, f, i;
                if (this.usePartial = !0, u = n.program, u && (u = this.compileProgram(n.program)), t = n.params, t.length > 1) throw new r["default"]("Unsupported number of partial arguments: " + t.length, n);
                t.length || (this.options.explicitPartialContext ? this.opcode("pushLiteral", "undefined") : t.push({
                    type: "PathExpression",
                    parts: [],
                    depth: 0
                }));
                e = n.name.original;
                f = "SubExpression" === n.name.type;
                f && this.accept(n.name);
                this.setupFullMustacheParams(n, u, void 0, !0);
                i = n.indent || "";
                this.options.preventIndent && i && (this.opcode("appendContent", i), i = "");
                this.opcode("invokePartial", f, e, i);
                this.opcode("append")
            },
            PartialBlockStatement: function (n) {
                this.PartialStatement(n)
            },
            MustacheStatement: function (n) {
                this.SubExpression(n);
                n.escaped && !this.options.noEscape ? this.opcode("appendEscaped") : this.opcode("append")
            },
            Decorator: function (n) {
                this.DecoratorBlock(n)
            },
            ContentStatement: function (n) {
                n.value && this.opcode("appendContent", n.value)
            },
            CommentStatement: function () {},
            SubExpression: function (n) {
                s(n);
                var t = this.classifySexpr(n);
                "simple" === t ? this.simpleSexpr(n) : "helper" === t ? this.helperSexpr(n) : this.ambiguousSexpr(n)
            },
            ambiguousSexpr: function (n, t, i) {
                var r = n.path,
                    u = r.parts[0],
                    f = null != t || null != i;
                this.opcode("getContext", r.depth);
                this.opcode("pushProgram", t);
                this.opcode("pushProgram", i);
                r.strict = !0;
                this.accept(r);
                this.opcode("invokeAmbiguous", u, f)
            },
            simpleSexpr: function (n) {
                var t = n.path;
                t.strict = !0;
                this.accept(t);
                this.opcode("resolvePossibleLambda")
            },
            helperSexpr: function (n, t, i) {
                var o = this.setupFullMustacheParams(n, t, i),
                    u = n.path,
                    e = u.parts[0];
                if (this.options.knownHelpers[e]) this.opcode("invokeKnownHelper", o.length, e);
                else {
                    if (this.options.knownHelpersOnly) throw new r["default"]("You specified knownHelpersOnly, but used the unknown helper " + e, n);
                    u.strict = !0;
                    u.falsy = !0;
                    this.accept(u);
                    this.opcode("invokeHelper", o.length, u.original, f["default"].helpers.simpleId(u))
                }
            },
            PathExpression: function (n) {
                this.addDepth(n.depth);
                this.opcode("getContext", n.depth);
                var t = n.parts[0],
                    i = f["default"].helpers.scopedId(n),
                    r = !n.depth && !i && this.blockParamIndex(t);
                r ? this.opcode("lookupBlockParam", r, n.parts) : t ? n.data ? (this.options.data = !0, this.opcode("lookupData", n.depth, n.parts, n.strict)) : this.opcode("lookupOnContext", n.parts, n.falsy, n.strict, i) : this.opcode("pushContext")
            },
            StringLiteral: function (n) {
                this.opcode("pushString", n.value)
            },
            NumberLiteral: function (n) {
                this.opcode("pushLiteral", n.value)
            },
            BooleanLiteral: function (n) {
                this.opcode("pushLiteral", n.value)
            },
            UndefinedLiteral: function () {
                this.opcode("pushLiteral", "undefined")
            },
            NullLiteral: function () {
                this.opcode("pushLiteral", "null")
            },
            Hash: function (n) {
                var i = n.pairs,
                    t = 0,
                    r = i.length;
                for (this.opcode("pushHash"); t < r; t++) this.pushParam(i[t].value);
                for (; t--;) this.opcode("assignToHash", i[t].key);
                this.opcode("popHash")
            },
            opcode: function (n) {
                this.opcodes.push({
                    opcode: n,
                    args: p.call(arguments, 1),
                    loc: this.sourceNode[0].loc
                })
            },
            addDepth: function (n) {
                n && (this.useDepths = !0)
            },
            classifySexpr: function (n) {
                var u = f["default"].helpers.simpleId(n.path),
                    e = u && !!this.blockParamIndex(n.path.parts[0]),
                    t = !e && f["default"].helpers.helperExpression(n),
                    i = !e && (t || u),
                    o, r;
                return i && !t && (o = n.path.parts[0], r = this.options, r.knownHelpers[o] ? t = !0 : r.knownHelpersOnly && (i = !1)), t ? "helper" : i ? "ambiguous" : "simple"
            },
            pushParams: function (n) {
                for (var t = 0, i = n.length; t < i; t++) this.pushParam(n[t])
            },
            pushParam: function (n) {
                var t = null != n.value ? n.value : n.original || "",
                    i, r;
                this.stringParams ? (t.replace && (t = t.replace(/^(\.?\.\/)*/g, "").replace(/\//g, ".")), n.depth && this.addDepth(n.depth), this.opcode("getContext", n.depth || 0), this.opcode("pushStringParam", t, n.type), "SubExpression" === n.type && this.accept(n)) : (this.trackIds && (i = void 0, (!n.parts || f["default"].helpers.scopedId(n) || n.depth || (i = this.blockParamIndex(n.parts[0])), i) ? (r = n.parts.slice(1).join("."), this.opcode("pushId", "BlockParam", i, r)) : (t = n.original || t, t.replace && (t = t.replace(/^this(?:\.|$)/, "").replace(/^\.\//, "").replace(/^\.$/, "")), this.opcode("pushId", n.type, t))), this.accept(n))
            },
            setupFullMustacheParams: function (n, t, i, r) {
                var u = n.params;
                return this.pushParams(u), this.opcode("pushProgram", t), this.opcode("pushProgram", i), n.hash ? this.accept(n.hash) : this.opcode("emptyHash", r), u
            },
            blockParamIndex: function (n) {
                for (var i, r, t = 0, f = this.options.blockParams.length; t < f; t++)
                    if (i = this.options.blockParams[t], r = i && u.indexOf(i, n), i && r >= 0) return [t, r]
            }
        }
    }, function (n, t, i) {
        "use strict";

        function r(n) {
            this.value = n
        }

        function u() {}

        function h(n, t, i, r) {
            var u = t.popStack(),
                f = 0,
                e = i.length;
            for (n && e--; f < e; f++) u = t.nameLookup(u, i[f], r);
            return n ? [t.aliasable("container.strict"), "(", u, ", ", t.quotedString(i[f]), ", ", JSON.stringify(t.source.currentLocation), " )"] : u
        }
        var c = i(13)["default"],
            e = i(1)["default"];
        t.__esModule = !0;
        var o = i(4),
            l = i(6),
            f = e(l),
            a = i(5),
            v = i(53),
            s = e(v);
        u.prototype = {
                nameLookup: function (n, t) {
                    return this.internalNameLookup(n, t)
                },
                depthedLookup: function (n) {
                    return [this.aliasable("container.lookup"), "(depths, ", JSON.stringify(n), ")"]
                },
                compilerInfo: function () {
                    var n = o.COMPILER_REVISION,
                        t = o.REVISION_CHANGES[n];
                    return [n, t]
                },
                appendToBuffer: function (n, t, i) {
                    return a.isArray(n) || (n = [n]), n = this.source.wrap(n, t), this.environment.isSimple ? ["return ", n, ";"] : i ? ["buffer += ", n, ";"] : (n.appendToBuffer = !0, n)
                },
                initializeBuffer: function () {
                    return this.quotedString("")
                },
                internalNameLookup: function (n, t) {
                    return this.lookupPropertyFunctionIsUsed = !0, ["lookupProperty(", n, ",", JSON.stringify(t), ")"]
                },
                lookupPropertyFunctionIsUsed: !1,
                compile: function (n, t, i, r) {
                    var c, u;
                    this.environment = n;
                    this.options = t;
                    this.stringParams = this.options.stringParams;
                    this.trackIds = this.options.trackIds;
                    this.precompile = !r;
                    this.name = this.environment.name;
                    this.isChild = !!i;
                    this.context = i || {
                        decorators: [],
                        programs: [],
                        environments: []
                    };
                    this.preamble();
                    this.stackSlot = 0;
                    this.stackVars = [];
                    this.aliases = {};
                    this.registers = {
                        list: []
                    };
                    this.hashes = [];
                    this.compileStack = [];
                    this.inlineStack = [];
                    this.blockParams = [];
                    this.compileChildren(n, t);
                    this.useDepths = this.useDepths || n.useDepths || n.useDecorators || this.options.compat;
                    this.useBlockParams = this.useBlockParams || n.useBlockParams;
                    for (var a = n.opcodes, o = void 0, h = void 0, e = void 0, s = void 0, e = 0, s = a.length; e < s; e++) o = a[e], this.source.currentLocation = o.loc, h = h || o.loc, this[o.opcode].apply(this, o.args);
                    if (this.source.currentLocation = h, this.pushSource(""), this.stackSlot || this.inlineStack.length || this.compileStack.length) throw new f["default"]("Compile completed with content left on stack");
                    if (this.decorators.isEmpty() ? this.decorators = void 0 : (this.useDecorators = !0, this.decorators.prepend(["var decorators = container.decorators, ", this.lookupPropertyFunctionVarDeclaration(), ";\n"]), this.decorators.push("return fn;"), r ? this.decorators = Function.apply(this, ["fn", "props", "container", "depth0", "data", "blockParams", "depths", this.decorators.merge()]) : (this.decorators.prepend("function(fn, props, container, depth0, data, blockParams, depths) {\n"), this.decorators.push("}\n"), this.decorators = this.decorators.merge())), c = this.createFunctionContext(r), this.isChild) return c;
                    u = {
                        compiler: this.compilerInfo(),
                        main: c
                    };
                    this.decorators && (u.main_d = this.decorators, u.useDecorators = !0);
                    var v = this.context,
                        l = v.programs,
                        y = v.decorators;
                    for (e = 0, s = l.length; e < s; e++) l[e] && (u[e] = l[e], y[e] && (u[e + "_d"] = y[e], u.useDecorators = !0));
                    return this.environment.usePartial && (u.usePartial = !0), this.options.data && (u.useData = !0), this.useDepths && (u.useDepths = !0), this.useBlockParams && (u.useBlockParams = !0), this.options.compat && (u.compat = !0), r ? u.compilerOptions = this.options : (u.compiler = JSON.stringify(u.compiler), this.source.currentLocation = {
                        start: {
                            line: 1,
                            column: 0
                        }
                    }, u = this.objectLiteral(u), t.srcName ? (u = u.toStringWithSourceMap({
                        file: t.destName
                    }), u.map = u.map && u.map.toString()) : u = u.toString()), u
                },
                preamble: function () {
                    this.lastContext = 0;
                    this.source = new s["default"](this.options.srcName);
                    this.decorators = new s["default"](this.options.srcName)
                },
                createFunctionContext: function (n) {
                    var e = this,
                        i = "",
                        f = this.stackVars.concat(this.registers.list),
                        r, t, u;
                    return f.length > 0 && (i += ", " + f.join(", ")), r = 0, c(this.aliases).forEach(function (n) {
                        var t = e.aliases[n];
                        t.children && t.referenceCount > 1 && (i += ", alias" + ++r + "=" + n, t.children[0] = "alias" + r)
                    }), this.lookupPropertyFunctionIsUsed && (i += ", " + this.lookupPropertyFunctionVarDeclaration()), t = ["container", "depth0", "helpers", "partials", "data"], (this.useBlockParams || this.useDepths) && t.push("blockParams"), this.useDepths && t.push("depths"), u = this.mergeSource(i), n ? (t.push(u), Function.apply(this, t)) : this.source.wrap(["function(", t.join(","), ") {\n  ", u, "}"])
                },
                mergeSource: function (n) {
                    var e = this.environment.isSimple,
                        f = !this.forceBuffer,
                        r = void 0,
                        u = void 0,
                        t = void 0,
                        i = void 0;
                    return this.source.each(function (n) {
                        n.appendToBuffer ? (t ? n.prepend("  + ") : t = n, i = n) : (t && (u ? t.prepend("buffer += ") : r = !0, i.add(";"), t = i = void 0), u = !0, e || (f = !1))
                    }), f ? t ? (t.prepend("return "), i.add(";")) : u || this.source.push('return "";') : (n += ", buffer = " + (r ? "" : this.initializeBuffer()), t ? (t.prepend("return buffer + "), i.add(";")) : this.source.push("return buffer;")), n && this.source.prepend("var " + n.substring(2) + (r ? "" : ";\n")), this.source.merge()
                },
                lookupPropertyFunctionVarDeclaration: function () {
                    return "\n      lookupProperty = container.lookupProperty || function(parent, propertyName) {\n        if (Object.prototype.hasOwnProperty.call(parent, propertyName)) {\n          return parent[propertyName];\n        }\n        return undefined\n    }\n    ".trim()
                },
                blockValue: function (n) {
                    var r = this.aliasable("container.hooks.blockHelperMissing"),
                        t = [this.contextName(0)],
                        i;
                    this.setupHelperArgs(n, 0, t);
                    i = this.popStack();
                    t.splice(1, 0, i);
                    this.push(this.source.functionCall(r, "call", t))
                },
                ambiguousBlockValue: function () {
                    var i = this.aliasable("container.hooks.blockHelperMissing"),
                        n = [this.contextName(0)],
                        t;
                    this.setupHelperArgs("", 0, n, !0);
                    this.flushInline();
                    t = this.topStack();
                    n.splice(1, 0, t);
                    this.pushSource(["if (!", this.lastHelper, ") { ", t, " = ", this.source.functionCall(i, "call", n), "}"])
                },
                appendContent: function (n) {
                    this.pendingContent ? n = this.pendingContent + n : this.pendingLocation = this.source.currentLocation;
                    this.pendingContent = n
                },
                append: function () {
                    if (this.isInline()) this.replaceStack(function (n) {
                        return [" != null ? ", n, ' : ""']
                    }), this.pushSource(this.appendToBuffer(this.popStack()));
                    else {
                        var n = this.popStack();
                        this.pushSource(["if (", n, " != null) { ", this.appendToBuffer(n, void 0, !0), " }"]);
                        this.environment.isSimple && this.pushSource(["else { ", this.appendToBuffer("''", void 0, !0), " }"])
                    }
                },
                appendEscaped: function () {
                    this.pushSource(this.appendToBuffer([this.aliasable("container.escapeExpression"), "(", this.popStack(), ")"]))
                },
                getContext: function (n) {
                    this.lastContext = n
                },
                pushContext: function () {
                    this.pushStackLiteral(this.contextName(this.lastContext))
                },
                lookupOnContext: function (n, t, i, r) {
                    var u = 0;
                    r || !this.options.compat || this.lastContext ? this.pushContext() : this.push(this.depthedLookup(n[u++]));
                    this.resolvePath("context", n, u, t, i)
                },
                lookupBlockParam: function (n, t) {
                    this.useBlockParams = !0;
                    this.push(["blockParams[", n[0], "][", n[1], "]"]);
                    this.resolvePath("context", t, 1)
                },
                lookupData: function (n, t, i) {
                    n ? this.pushStackLiteral("container.data(data, " + n + ")") : this.pushStackLiteral("data");
                    this.resolvePath("data", t, 0, !0, i)
                },
                resolvePath: function (n, t, i, r, u) {
                    var e = this,
                        f;
                    if (this.options.strict || this.options.assumeObjects) return void this.push(h(this.options.strict && u, this, t, n));
                    for (f = t.length; i < f; i++) this.replaceStack(function (u) {
                        var f = e.nameLookup(u, t[i], n);
                        return r ? [" && ", f] : [" != null ? ", f, " : ", u]
                    })
                },
                resolvePossibleLambda: function () {
                    this.push([this.aliasable("container.lambda"), "(", this.popStack(), ", ", this.contextName(0), ")"])
                },
                pushStringParam: function (n, t) {
                    this.pushContext();
                    this.pushString(t);
                    "SubExpression" !== t && ("string" == typeof n ? this.pushString(n) : this.pushStackLiteral(n))
                },
                emptyHash: function (n) {
                    this.trackIds && this.push("{}");
                    this.stringParams && (this.push("{}"), this.push("{}"));
                    this.pushStackLiteral(n ? "undefined" : "{}")
                },
                pushHash: function () {
                    this.hash && this.hashes.push(this.hash);
                    this.hash = {
                        values: {},
                        types: [],
                        contexts: [],
                        ids: []
                    }
                },
                popHash: function () {
                    var n = this.hash;
                    this.hash = this.hashes.pop();
                    this.trackIds && this.push(this.objectLiteral(n.ids));
                    this.stringParams && (this.push(this.objectLiteral(n.contexts)), this.push(this.objectLiteral(n.types)));
                    this.push(this.objectLiteral(n.values))
                },
                pushString: function (n) {
                    this.pushStackLiteral(this.quotedString(n))
                },
                pushLiteral: function (n) {
                    this.pushStackLiteral(n)
                },
                pushProgram: function (n) {
                    null != n ? this.pushStackLiteral(this.programExpression(n)) : this.pushStackLiteral(null)
                },
                registerDecorator: function (n, t) {
                    var i = this.nameLookup("decorators", t, "decorator"),
                        r = this.setupHelperArgs(t, n);
                    this.decorators.push(["fn = ", this.decorators.functionCall(i, "", ["fn", "props", "container", r]), " || fn;"])
                },
                invokeHelper: function (n, t, i) {
                    var o = this.popStack(),
                        u = this.setupHelper(n, t),
                        r = [],
                        f, e;
                    i && r.push(u.name);
                    r.push(o);
                    this.options.strict || r.push(this.aliasable("container.hooks.helperMissing"));
                    f = ["(", this.itemsSeparatedBy(r, "||"), ")"];
                    e = this.source.functionCall(f, "call", u.callParams);
                    this.push(e)
                },
                itemsSeparatedBy: function (n, t) {
                    var r = [],
                        i;
                    for (r.push(n[0]), i = 1; i < n.length; i++) r.push(t, n[i]);
                    return r
                },
                invokeKnownHelper: function (n, t) {
                    var i = this.setupHelper(n, t);
                    this.push(this.source.functionCall(i.name, "call", i.callParams))
                },
                invokeAmbiguous: function (n, t) {
                    var u;
                    this.useRegister("helper");
                    u = this.popStack();
                    this.emptyHash();
                    var i = this.setupHelper(0, n, t),
                        f = this.lastHelper = this.nameLookup("helpers", n, "helper"),
                        r = ["(", "(helper = ", f, " || ", u, ")"];
                    this.options.strict || (r[0] = "(helper = ", r.push(" != null ? helper : ", this.aliasable("container.hooks.helperMissing")));
                    this.push(["(", r, i.paramsInit ? ["),(", i.paramsInit] : [], "),", "(typeof helper === ", this.aliasable('"function"'), " ? ", this.source.functionCall("helper", "call", i.callParams), " : helper))"])
                },
                invokePartial: function (n, t, i) {
                    var u = [],
                        r = this.setupParams(t, 1, u);
                    n && (t = this.popStack(), delete r.name);
                    i && (r.indent = JSON.stringify(i));
                    r.helpers = "helpers";
                    r.partials = "partials";
                    r.decorators = "container.decorators";
                    n ? u.unshift(t) : u.unshift(this.nameLookup("partials", t, "partial"));
                    this.options.compat && (r.depths = "depths");
                    r = this.objectLiteral(r);
                    u.push(r);
                    this.push(this.source.functionCall("container.invokePartial", "", u))
                },
                assignToHash: function (n) {
                    var f = this.popStack(),
                        i = void 0,
                        r = void 0,
                        u = void 0,
                        t;
                    this.trackIds && (u = this.popStack());
                    this.stringParams && (r = this.popStack(), i = this.popStack());
                    t = this.hash;
                    i && (t.contexts[n] = i);
                    r && (t.types[n] = r);
                    u && (t.ids[n] = u);
                    t.values[n] = f
                },
                pushId: function (n, t, i) {
                    "BlockParam" === n ? this.pushStackLiteral("blockParams[" + t[0] + "].path[" + t[1] + "]" + (i ? " + " + JSON.stringify("." + i) : "")) : "PathExpression" === n ? this.pushString(t) : "SubExpression" === n ? this.pushStackLiteral("true") : this.pushStackLiteral("null")
                },
                compiler: u,
                compileChildren: function (n, t) {
                    for (var r, u, o = n.children, i = void 0, f = void 0, e = 0, s = o.length; e < s; e++) i = o[e], f = new this.compiler, r = this.matchExistingProgram(i), null == r ? (this.context.programs.push(""), u = this.context.programs.length, i.index = u, i.name = "program" + u, this.context.programs[u] = f.compile(i, t, this.context, !this.precompile), this.context.decorators[u] = f.decorators, this.context.environments[u] = i, this.useDepths = this.useDepths || f.useDepths, this.useBlockParams = this.useBlockParams || f.useBlockParams, i.useDepths = this.useDepths, i.useBlockParams = this.useBlockParams) : (i.index = r.index, i.name = "program" + r.index, this.useDepths = this.useDepths || r.useDepths, this.useBlockParams = this.useBlockParams || r.useBlockParams)
                },
                matchExistingProgram: function (n) {
                    for (var i, t = 0, r = this.context.environments.length; t < r; t++)
                        if (i = this.context.environments[t], i && i.equals(n)) return i
                },
                programExpression: function (n) {
                    var i = this.environment.children[n],
                        t = [i.index, "data", i.blockParams];
                    return (this.useBlockParams || this.useDepths) && t.push("blockParams"), this.useDepths && t.push("depths"), "container.program(" + t.join(", ") + ")"
                },
                useRegister: function (n) {
                    this.registers[n] || (this.registers[n] = !0, this.registers.list.push(n))
                },
                push: function (n) {
                    return n instanceof r || (n = this.source.wrap(n)), this.inlineStack.push(n), n
                },
                pushStackLiteral: function (n) {
                    this.push(new r(n))
                },
                pushSource: function (n) {
                    this.pendingContent && (this.source.push(this.appendToBuffer(this.source.quotedString(this.pendingContent), this.pendingLocation)), this.pendingContent = void 0);
                    n && this.source.push(n)
                },
                replaceStack: function (n) {
                    var u = ["("],
                        t = void 0,
                        e = void 0,
                        o = void 0,
                        i, s, h;
                    if (!this.isInline()) throw new f["default"]("replaceStack on non-inline");
                    i = this.popStack(!0);
                    i instanceof r ? (t = [i.value], u = ["(", t], o = !0) : (e = !0, s = this.incrStack(), u = ["((", this.push(s), " = ", i, ")"], t = this.topStack());
                    h = n.call(this, t);
                    o || this.popStack();
                    e && this.stackSlot--;
                    this.push(u.concat(h, ")"))
                },
                incrStack: function () {
                    return this.stackSlot++, this.stackSlot > this.stackVars.length && this.stackVars.push("stack" + this.stackSlot), this.topStackName()
                },
                topStackName: function () {
                    return "stack" + this.stackSlot
                },
                flushInline: function () {
                    var u = this.inlineStack,
                        n, f, t, i;
                    for (this.inlineStack = [], n = 0, f = u.length; n < f; n++) t = u[n], t instanceof r ? this.compileStack.push(t) : (i = this.incrStack(), this.pushSource([i, " = ", t, ";"]), this.compileStack.push(i))
                },
                isInline: function () {
                    return this.inlineStack.length
                },
                popStack: function (n) {
                    var i = this.isInline(),
                        t = (i ? this.inlineStack : this.compileStack).pop();
                    if (!n && t instanceof r) return t.value;
                    if (!i) {
                        if (!this.stackSlot) throw new f["default"]("Invalid stack pop");
                        this.stackSlot--
                    }
                    return t
                },
                topStack: function () {
                    var t = this.isInline() ? this.inlineStack : this.compileStack,
                        n = t[t.length - 1];
                    return n instanceof r ? n.value : n
                },
                contextName: function (n) {
                    return this.useDepths && n ? "depths[" + n + "]" : "depth" + n
                },
                quotedString: function (n) {
                    return this.source.quotedString(n)
                },
                objectLiteral: function (n) {
                    return this.source.objectLiteral(n)
                },
                aliasable: function (n) {
                    var t = this.aliases[n];
                    return t ? (t.referenceCount++, t) : (t = this.aliases[n] = this.source.wrap(n), t.aliasable = !0, t.referenceCount = 1, t)
                },
                setupHelper: function (n, t, i) {
                    var r = [],
                        u = this.setupHelperArgs(t, n, r, i),
                        f = this.nameLookup("helpers", t, "helper"),
                        e = this.aliasable(this.contextName(0) + " != null ? " + this.contextName(0) + " : (container.nullContext || {})");
                    return {
                        params: r,
                        paramsInit: u,
                        name: f,
                        callParams: [e].concat(r)
                    }
                },
                setupParams: function (n, t, i) {
                    var r = {},
                        o = [],
                        s = [],
                        h = [],
                        c = !i,
                        l = void 0,
                        f, e, u;
                    for (c && (i = []), r.name = this.quotedString(n), r.hash = this.popStack(), this.trackIds && (r.hashIds = this.popStack()), this.stringParams && (r.hashTypes = this.popStack(), r.hashContexts = this.popStack()), f = this.popStack(), e = this.popStack(), (e || f) && (r.fn = e || "container.noop", r.inverse = f || "container.noop"), u = t; u--;) l = this.popStack(), i[u] = l, this.trackIds && (h[u] = this.popStack()), this.stringParams && (s[u] = this.popStack(), o[u] = this.popStack());
                    return c && (r.args = this.source.generateArray(i)), this.trackIds && (r.ids = this.source.generateArray(h)), this.stringParams && (r.types = this.source.generateArray(s), r.contexts = this.source.generateArray(o)), this.options.data && (r.data = "data"), this.useBlockParams && (r.blockParams = "blockParams"), r
                },
                setupHelperArgs: function (n, t, i, r) {
                    var u = this.setupParams(n, t, i);
                    return u.loc = JSON.stringify(this.source.currentLocation), u = this.objectLiteral(u), r ? (this.useRegister("options"), i.push("options"), ["options=", u]) : i ? (i.push(u), "") : u
                }
            },
            function () {
                for (var t = "break else new var case finally return void catch for switch while continue function this with default if throw delete in try do instanceof typeof abstract enum int short boolean export interface static byte extends long super char final native synchronized class float package throws const goto private transient debugger implements protected volatile double import public let yield await null true false".split(" "), i = u.RESERVED_WORDS = {}, n = 0, r = t.length; n < r; n++) i[t[n]] = !0
            }();
        u.isValidJavaScriptVariableName = function (n) {
            return !u.RESERVED_WORDS[n] && /^[a-zA-Z_$][0-9a-zA-Z_$]*$/.test(n)
        };
        t["default"] = u;
        n.exports = t["default"]
    }, function (n, t, i) {
        "use strict";

        function f(n, t, i) {
            if (u.isArray(n)) {
                for (var f = [], r = 0, e = n.length; r < e; r++) f.push(t.wrap(n[r], i));
                return f
            }
            return "boolean" == typeof n || "number" == typeof n ? n + "" : n
        }

        function e(n) {
            this.srcFile = n;
            this.source = []
        }
        var o = i(13)["default"],
            u, r;
        t.__esModule = !0;
        u = i(5);
        r = void 0;
        try {} catch (s) {}
        r || (r = function (n, t, i, r) {
            this.src = "";
            r && this.add(r)
        }, r.prototype = {
            add: function (n) {
                u.isArray(n) && (n = n.join(""));
                this.src += n
            },
            prepend: function (n) {
                u.isArray(n) && (n = n.join(""));
                this.src = n + this.src
            },
            toStringWithSourceMap: function () {
                return {
                    code: this.toString()
                }
            },
            toString: function () {
                return this.src
            }
        });
        e.prototype = {
            isEmpty: function () {
                return !this.source.length
            },
            prepend: function (n, t) {
                this.source.unshift(this.wrap(n, t))
            },
            push: function (n, t) {
                this.source.push(this.wrap(n, t))
            },
            merge: function () {
                var n = this.empty();
                return this.each(function (t) {
                    n.add(["  ", t, "\n"])
                }), n
            },
            each: function (n) {
                for (var t = 0, i = this.source.length; t < i; t++) n(this.source[t])
            },
            empty: function () {
                var n = this.currentLocation || {
                    start: {}
                };
                return new r(n.start.line, n.start.column, this.srcFile)
            },
            wrap: function (n) {
                var t = arguments.length <= 1 || void 0 === arguments[1] ? this.currentLocation || {
                    start: {}
                } : arguments[1];
                return n instanceof r ? n : (n = f(n, this, t), new r(t.start.line, t.start.column, this.srcFile, n))
            },
            functionCall: function (n, t, i) {
                return i = this.generateList(i), this.wrap([n, t ? "." + t + "(" : "(", i, ")"])
            },
            quotedString: function (n) {
                return '"' + (n + "").replace(/\\/g, "\\\\").replace(/"/g, '\\"').replace(/\n/g, "\\n").replace(/\r/g, "\\r").replace(/\u2028/g, "\\u2028").replace(/\u2029/g, "\\u2029") + '"'
            },
            objectLiteral: function (n) {
                var i = this,
                    r = [],
                    t;
                return o(n).forEach(function (t) {
                    var u = f(n[t], i);
                    "undefined" !== u && r.push([i.quotedString(t), ":", u])
                }), t = this.generateList(r), t.prepend("{"), t.add("}"), t
            },
            generateList: function (n) {
                for (var i = this.empty(), t = 0, r = n.length; t < r; t++) t && i.add(","), i.add(f(n[t], this));
                return i
            },
            generateArray: function (n) {
                var t = this.generateList(n);
                return t.prepend("["), t.add("]"), t
            }
        };
        t["default"] = e;
        n.exports = t["default"]
    }])
});
! function (n, t) {
    "object" == typeof exports && "object" == typeof module ? module.exports = t() : "function" == typeof define && define.amd ? define([], t) : "object" == typeof exports ? exports.Raphael = t() : n.Raphael = t()
}(window, function () {
    return function (n) {
        function t(r) {
            if (i[r]) return i[r].exports;
            var u = i[r] = {
                i: r,
                l: !1,
                exports: {}
            };
            return n[r].call(u.exports, u, u.exports, t), u.l = !0, u.exports
        }
        var i = {};
        return t.m = n, t.c = i, t.d = function (n, i, r) {
            t.o(n, i) || Object.defineProperty(n, i, {
                enumerable: !0,
                get: r
            })
        }, t.r = function (n) {
            "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(n, Symbol.toStringTag, {
                value: "Module"
            });
            Object.defineProperty(n, "__esModule", {
                value: !0
            })
        }, t.t = function (n, i) {
            var r, u;
            if ((1 & i && (n = t(n)), 8 & i) || 4 & i && "object" == typeof n && n && n.__esModule) return n;
            if (r = Object.create(null), t.r(r), Object.defineProperty(r, "default", {
                    enumerable: !0,
                    value: n
                }), 2 & i && "string" != typeof n)
                for (u in n) t.d(r, u, function (t) {
                    return n[t]
                }.bind(null, u));
            return r
        }, t.n = function (n) {
            var i = n && n.__esModule ? function () {
                return n.default
            } : function () {
                return n
            };
            return t.d(i, "a", i), i
        }, t.o = function (n, t) {
            return Object.prototype.hasOwnProperty.call(n, t)
        }, t.p = "", t(t.s = 1)
    }([function (n, t, i) {
        var r, u;
        r = [i(2)];
        void 0 === (u = function (n) {
            function t(i) {
                var r, u;
                return t.is(i, "function") ? hi ? i() : n.on("raphael.DOMload", i) : t.is(i, nt) ? t._engine.create[a](t, i.splice(0, 3 + t.is(i[0], y))).add(i) : (r = Array.prototype.slice.call(arguments, 0), t.is(r[r.length - 1], "function")) ? (u = r.pop(), hi ? u.call(t._engine.create[a](t, r)) : n.on("raphael.DOMload", function () {
                    u.call(t._engine.create[a](t, r))
                })) : t._engine.create[a](t, arguments)
            }

            function pt(n) {
                var i, t;
                if ("function" == typeof n || Object(n) !== n) return n;
                i = new n.constructor;
                for (t in n) n[l](t) && (i[t] = pt(n[t]));
                return i
            }

            function tt(n, t, i) {
                return function r() {
                    var o = Array.prototype.slice.call(arguments, 0),
                        u = o.join("␀"),
                        f = r.cache = r.cache || {},
                        e = r.count = r.count || [];
                    return f[l](u) ? (function (n, t) {
                        for (var i = 0, r = n.length; i < r; i++)
                            if (n[i] === t) return n.push(n.splice(i, 1)[0])
                    }(e, u), i ? i(f[u]) : f[u]) : (e.length >= 1e3 && delete f[e.shift()], e.push(u), f[u] = n[a](t, o), i ? i(f[u]) : f[u])
                }
            }

            function ui() {
                return this.hex
            }

            function pr(n, t) {
                for (var i, f = [], r = 0, u = n.length; u - 2 * !t > r; r += 2) i = [{
                    x: +n[r - 2],
                    y: +n[r - 1]
                }, {
                    x: +n[r],
                    y: +n[r + 1]
                }, {
                    x: +n[r + 2],
                    y: +n[r + 3]
                }, {
                    x: +n[r + 4],
                    y: +n[r + 5]
                }], t ? r ? u - 4 == r ? i[3] = {
                    x: +n[0],
                    y: +n[1]
                } : u - 2 == r && (i[2] = {
                    x: +n[0],
                    y: +n[1]
                }, i[3] = {
                    x: +n[2],
                    y: +n[3]
                }) : i[0] = {
                    x: +n[u - 2],
                    y: +n[u - 1]
                } : u - 4 == r ? i[3] = i[2] : r || (i[0] = {
                    x: +n[r],
                    y: +n[r + 1]
                }), f.push(["C", (-i[0].x + 6 * i[1].x + i[2].x) / 6, (-i[0].y + 6 * i[1].y + i[2].y) / 6, (i[1].x + 6 * i[2].x - i[3].x) / 6, (i[1].y + 6 * i[2].y - i[3].y) / 6, i[2].x, i[2].y]);
                return f
            }

            function wr(n, t, i, r, u) {
                return n * (n * (-3 * t + 9 * i - 9 * r + 3 * u) + 6 * t - 12 * i + 6 * r) - 3 * t + 3 * i
            }

            function at(n, t, i, u, f, e, o, s, h) {
                null == h && (h = 1);
                for (var l = (h = h > 1 ? 1 : h < 0 ? 0 : h) / 2, w = [-.1252, .1252, -.3678, .3678, -.5873, .5873, -.7699, .7699, -.9041, .9041, -.9816, .9816], b = [.2491, .2491, .2335, .2335, .2032, .2032, .1601, .1601, .1069, .1069, .0472, .0472], a = 0, c = 0; c < 12; c++) {
                    var v = l * w[c] + l,
                        y = wr(v, n, i, f, o),
                        p = wr(v, t, u, e, s),
                        k = y * y + p * p;
                    a += b[c] * r.sqrt(k)
                }
                return l * a
            }

            function of (n, t, i, r, u, f, o, s) {
                var h;
                if (!(e(n, i) < c(u, o) || c(n, i) > e(u, o) || e(t, r) < c(f, s) || c(t, r) > e(f, s)) && (h = (n - i) * (f - s) - (t - r) * (u - o), h)) {
                    var v = ((n * r - t * i) * (u - o) - (n - i) * (u * s - f * o)) / h,
                        y = ((n * r - t * i) * (f - s) - (t - r) * (u * s - f * o)) / h,
                        l = +v.toFixed(2),
                        a = +y.toFixed(2);
                    if (!(l < +c(n, i).toFixed(2) || l > +e(n, i).toFixed(2) || l < +c(u, o).toFixed(2) || l > +e(u, o).toFixed(2) || a < +c(t, r).toFixed(2) || a > +e(t, r).toFixed(2) || a < +c(f, s).toFixed(2) || a > +e(f, s).toFixed(2))) return {
                        x: v,
                        y: y
                    }
                }
            }

            function sf(n, i, r) {
                var ut = t.bezierBBox(n),
                    ft = t.bezierBBox(i),
                    h, l, d, g;
                if (!t.isBBoxIntersect(ut, ft)) return r ? 0 : [];
                for (var et = at.apply(0, n), ot = at.apply(0, i), p = e(~~(et / 5), 1), w = e(~~(ot / 5), 1), nt = [], tt = [], rt = {}, it = r ? 0 : [], u = 0; u < p + 1; u++) h = t.findDotsAtSegment.apply(t, n.concat(u / p)), nt.push({
                    x: h.x,
                    y: h.y,
                    t: u / p
                });
                for (u = 0; u < w + 1; u++) h = t.findDotsAtSegment.apply(t, i.concat(u / w)), tt.push({
                    x: h.x,
                    y: h.y,
                    t: u / w
                });
                for (u = 0; u < p; u++)
                    for (l = 0; l < w; l++) {
                        var o = nt[u],
                            a = nt[u + 1],
                            s = tt[l],
                            y = tt[l + 1],
                            b = v(a.x - o.x) < .001 ? "y" : "x",
                            k = v(y.x - s.x) < .001 ? "y" : "x",
                            f = of (o.x, o.y, a.x, a.y, s.x, s.y, y.x, y.y);
                        if (f) {
                            if (rt[f.x.toFixed(4)] == f.y.toFixed(4)) continue;
                            rt[f.x.toFixed(4)] = f.y.toFixed(4);
                            d = o.t + v((f[b] - o[b]) / (a[b] - o[b])) * (a.t - o.t);
                            g = s.t + v((f[k] - s[k]) / (y[k] - s[k])) * (y.t - s.t);
                            d >= 0 && d <= 1.001 && g >= 0 && g <= 1.001 && (r ? it++ : it.push({
                                x: f.x,
                                y: f.y,
                                t1: c(d, 1),
                                t2: c(g, 1)
                            }))
                        }
                    }
                return it
            }

            function wi(n, i, r) {
                var a, y, nt, v, u, f, tt;
                n = t._path2curve(n);
                i = t._path2curve(i);
                for (var e, o, s, h, p, w, b, k, c, l, d = r ? 0 : [], g = 0, it = n.length; g < it; g++)
                    if (a = n[g], "M" == a[0]) e = p = a[1], o = w = a[2];
                    else
                        for ("C" == a[0] ? (c = [e, o].concat(a.slice(1)), e = c[6], o = c[7]) : (c = [e, o, e, o, p, w, p, w], e = p, o = w), y = 0, nt = i.length; y < nt; y++)
                            if (v = i[y], "M" == v[0]) s = b = v[1], h = k = v[2];
                            else if ("C" == v[0] ? (l = [s, h].concat(v.slice(1)), s = l[6], h = l[7]) : (l = [s, h, s, h, b, k, b, k], s = b, h = k), u = sf(c, l, r), r) d += u;
                else {
                    for (f = 0, tt = u.length; f < tt; f++) u[f].segment1 = g, u[f].segment2 = y, u[f].bez1 = c, u[f].bez2 = l;
                    d = d.concat(u)
                }
                return d
            }

            function st(n, t, i, r, u, f) {
                null != n ? (this.a = +n, this.b = +t, this.c = +i, this.d = +r, this.e = +u, this.f = +f) : (this.a = 1, this.b = 0, this.c = 0, this.d = 1, this.e = 0, this.f = 0)
            }

            function uu() {
                return this.x + yt + this.y + yt + this.width + " × " + this.height
            }

            function bf(n, t, i, r, u, f) {
                function l(n) {
                    return ((h * n + o) * n + e) * n
                }
                var e = 3 * t,
                    o = 3 * (r - t) - e,
                    h = 1 - e - o,
                    s = 3 * i,
                    c = 3 * (u - i) - s,
                    a = 1 - s - c;
                return function (n, t) {
                    var i = function (n, t) {
                        for (var r, u, f, c, i = n, s = 0; s < 8; s++) {
                            if (f = l(i) - n, v(f) < t) return i;
                            if (v(c = (3 * h * i + 2 * o) * i + e) < 1e-6) break;
                            i -= f / c
                        }
                        if (u = 1, (i = n) < (r = 0)) return r;
                        if (i > u) return u;
                        for (; r < u;) {
                            if (f = l(i), v(f - n) < t) return i;
                            n > f ? r = i : u = i;
                            i = (u - r) / 2 + r
                        }
                        return i
                    }(n, t);
                    return ((a * i + c) * i + s) * i
                }(n, 1 / (200 * f))
            }

            function ut(n, t) {
                var i = [],
                    u = {},
                    r;
                if (this.ms = t, this.times = 1, n) {
                    for (r in n) n[l](r) && (u[s(r)] = n[r], i.push(s(r)));
                    i.sort(rf)
                }
                this.anim = u;
                this.top = i[i.length - 1];
                this.percents = i
            }

            function bt(i, r, f, e, h, c) {
                var nt, v, ft, a, at, kt, ii, tt, vt, dt, yt, d, ut, ht, ct, gt, et, lt;
                f = s(f);
                var it, ot, pt, ni, bt, ti, b = i.ms,
                    p = {},
                    g = {},
                    k = {};
                if (e) {
                    for (v = 0, ft = u.length; v < ft; v++)
                        if (nt = u[v], nt.el.id == r.id && nt.anim == i) {
                            nt.percent != f ? (u.splice(v, 1), pt = 1) : ot = nt;
                            r.attr(nt.totalOrigin);
                            break
                        }
                } else e = +g;
                for (v = 0, ft = i.percents.length; v < ft; v++) {
                    if (i.percents[v] == f || i.percents[v] > e * i.top) {
                        f = i.percents[v];
                        bt = i.percents[v - 1] || 0;
                        b = b / i.top * (f - bt);
                        ni = i.percents[v + 1];
                        it = i.anim[f];
                        break
                    }
                    e && r.attr(i.anim[i.percents[v]])
                }
                if (it) {
                    if (ot) ot.initstatus = e, ot.start = new Date - ot.ms * e;
                    else {
                        for (a in it)
                            if (it[l](a) && (ai[l](a) || r.paper.customAttributes[l](a))) switch (p[a] = r.attr(a), null == p[a] && (p[a] = ku[a]), g[a] = it[a], ai[a]) {
                                case y:
                                    k[a] = (g[a] - p[a]) / b;
                                    break;
                                case "colour":
                                    p[a] = t.getRGB(p[a]);
                                    at = t.getRGB(g[a]);
                                    k[a] = {
                                        r: (at.r - p[a].r) / b,
                                        g: (at.g - p[a].g) / b,
                                        b: (at.b - p[a].b) / b
                                    };
                                    break;
                                case "path":
                                    for (kt = wt(p[a], g[a]), ii = kt[1], p[a] = kt[0], k[a] = [], v = 0, ft = p[a].length; v < ft; v++)
                                        for (k[a][v] = [0], tt = 1, vt = p[a][v].length; tt < vt; tt++) k[a][v][tt] = (ii[v][tt] - p[a][v][tt]) / b;
                                    break;
                                case "transform":
                                    if (dt = r._, yt = lf(dt[a], g[a]), yt)
                                        for (p[a] = yt.from, g[a] = yt.to, k[a] = [], k[a].real = !0, v = 0, ft = p[a].length; v < ft; v++)
                                            for (k[a][v] = [p[a][v][0]], tt = 1, vt = p[a][v].length; tt < vt; tt++) k[a][v][tt] = (g[a][v][tt] - p[a][v][tt]) / b;
                                    else d = r.matrix || new st, ut = {
                                        _: {
                                            transform: dt.transform
                                        },
                                        getBBox: function () {
                                            return r.getBBox(1)
                                        }
                                    }, p[a] = [d.a, d.b, d.c, d.d, d.e, d.f], nu(ut, g[a]), g[a] = ut._.transform, k[a] = [(ut.matrix.a - d.a) / b, (ut.matrix.b - d.b) / b, (ut.matrix.c - d.c) / b, (ut.matrix.d - d.d) / b, (ut.matrix.e - d.e) / b, (ut.matrix.f - d.f) / b];
                                    break;
                                case "csv":
                                    if (ht = w(it[a])[rt](ci), ct = w(p[a])[rt](ci), "clip-rect" == a)
                                        for (p[a] = ct, k[a] = [], v = ct.length; v--;) k[a][v] = (ht[v] - p[a][v]) / b;
                                    g[a] = ht;
                                    break;
                                default:
                                    for (ht = [][o](it[a]), ct = [][o](p[a]), k[a] = [], v = r.paper.customAttributes[a].length; v--;) k[a][v] = ((ht[v] || 0) - (ct[v] || 0)) / b
                            }
                        if (gt = it.easing, et = t.easing_formulas[gt], et || ((et = w(gt).match(bu)) && 5 == et.length ? (lt = et, et = function (n) {
                                return bf(n, +lt[1], +lt[2], +lt[3], +lt[4], b)
                            }) : et = uf), nt = {
                                anim: i,
                                percent: f,
                                timestamp: ti = it.start || i.start || +new Date,
                                start: ti + (i.del || 0),
                                status: 0,
                                initstatus: e || 0,
                                stop: !1,
                                ms: b,
                                easing: et,
                                from: p,
                                diff: k,
                                to: g,
                                el: r,
                                callback: it.callback,
                                prev: bt,
                                next: ni,
                                repeat: c || i.times,
                                origin: r.attr(),
                                totalOrigin: h
                            }, u.push(nt), e && !ot && !pt && (nt.stop = !0, nt.start = new Date - b * e, 1 == u.length)) return ir();
                        pt && (nt.start = new Date - nt.ms * e);
                        1 == u.length && ou(ir)
                    }
                    n("raphael.anim.start." + r.id, r, i)
                }
            }

            function su(n) {
                for (var t = 0; t < u.length; t++) u[t].el.paper == n && u.splice(t--, 1)
            }
            var pi, ii, cr, lr, et, ht, d, hu, cu, ct, lu, vt, p, si;
            t.version = "2.3.0";
            t.eve = n;
            var hi, h, ci = /[, ]+/,
                au = {
                    circle: 1,
                    rect: 1,
                    path: 1,
                    ellipse: 1,
                    text: 1,
                    image: 1
                },
                vu = /\{(\d+)\}/g,
                l = "hasOwnProperty",
                i = {
                    doc: document,
                    win: window
                },
                ur = {
                    was: Object.prototype[l].call(i.win, "Raphael"),
                    is: i.win.Raphael
                },
                fr = function () {
                    this.ca = this.customAttributes = {}
                },
                a = "apply",
                o = "concat",
                kt = "ontouchstart" in window || window.TouchEvent || window.DocumentTouch && document instanceof DocumentTouch,
                k = "",
                yt = " ",
                w = String,
                rt = "split",
                er = "click dblclick mousedown mousemove mouseout mouseover mouseup touchstart touchmove touchend touchcancel" [rt](yt),
                dt = {
                    mousedown: "touchstart",
                    mousemove: "touchmove",
                    mouseup: "touchend"
                },
                gt = w.prototype.toLowerCase,
                r = Math,
                e = r.max,
                c = r.min,
                v = r.abs,
                g = r.pow,
                b = r.PI,
                y = "number",
                nt = "array",
                yu = Object.prototype.toString,
                pu = (t._ISURL = /^url\(['"]?(.+?)['"]?\)$/i, /^\s*((#[a-f\d]{6})|(#[a-f\d]{3})|rgba?\(\s*([\d\.]+%?\s*,\s*[\d\.]+%?\s*,\s*[\d\.]+%?(?:\s*,\s*[\d\.]+%?)?)\s*\)|hsba?\(\s*([\d\.]+(?:deg|\xb0|%)?\s*,\s*[\d\.]+%?\s*,\s*[\d\.]+(?:%?\s*,\s*[\d\.]+)?)%?\s*\)|hsla?\(\s*([\d\.]+(?:deg|\xb0|%)?\s*,\s*[\d\.]+%?\s*,\s*[\d\.]+(?:%?\s*,\s*[\d\.]+)?)%?\s*\))\s*$/i),
                wu = {
                    NaN: 1,
                    Infinity: 1,
                    "-Infinity": 1
                },
                bu = /^(?:cubic-)?bezier\(([^,]+),([^,]+),([^,]+),([^\)]+)\)/,
                li = r.round,
                s = parseFloat,
                ft = parseInt,
                or = w.prototype.toUpperCase,
                ku = t._availableAttrs = {
                    "arrow-end": "none",
                    "arrow-start": "none",
                    blur: 0,
                    "clip-rect": "0 0 1e9 1e9",
                    cursor: "default",
                    cx: 0,
                    cy: 0,
                    fill: "#fff",
                    "fill-opacity": 1,
                    font: '10px "Arial"',
                    "font-family": '"Arial"',
                    "font-size": "10",
                    "font-style": "normal",
                    "font-weight": 400,
                    gradient: 0,
                    height: 0,
                    href: "http://raphaeljs.com/",
                    "letter-spacing": 0,
                    opacity: 1,
                    path: "M0,0",
                    r: 0,
                    rx: 0,
                    ry: 0,
                    src: "",
                    stroke: "#000",
                    "stroke-dasharray": "",
                    "stroke-linecap": "butt",
                    "stroke-linejoin": "butt",
                    "stroke-miterlimit": 0,
                    "stroke-opacity": 1,
                    "stroke-width": 1,
                    target: "_blank",
                    "text-anchor": "middle",
                    title: "Raphael",
                    transform: "",
                    width: 0,
                    x: 0,
                    y: 0,
                    "class": ""
                },
                ai = t._availableAnimAttrs = {
                    blur: y,
                    "clip-rect": "csv",
                    cx: y,
                    cy: y,
                    fill: "colour",
                    "fill-opacity": y,
                    "font-size": y,
                    height: y,
                    opacity: y,
                    path: "path",
                    r: y,
                    rx: y,
                    ry: y,
                    stroke: "colour",
                    "stroke-opacity": y,
                    "stroke-width": y,
                    transform: "transform",
                    width: y,
                    x: y,
                    y: y
                },
                vi = /[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*,[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*/,
                du = {
                    hs: 1,
                    rg: 1
                },
                gu = /,?([achlmqrstvxz]),?/gi,
                nf = /([achlmrqstvz])[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029,]*((-?\d*\.?\d*(?:e[\-+]?\d+)?[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*,?[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*)+)/gi,
                tf = /([rstm])[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029,]*((-?\d*\.?\d*(?:e[\-+]?\d+)?[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*,?[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*)+)/gi,
                sr = /(-?\d*\.?\d*(?:e[\-+]?\d+)?)[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*,?[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*/gi,
                lt = (t._radial_gradient = /^r(?:\(([^,]+?)[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*,[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*([^\)]+?)\))?/, {}),
                rf = function (n, t) {
                    return s(n) - s(t)
                },
                uf = function (n) {
                    return n
                },
                ni = t._rectPath = function (n, t, i, r, u) {
                    return u ? [
                        ["M", n + u, t],
                        ["l", i - 2 * u, 0],
                        ["a", u, u, 0, 0, 1, u, u],
                        ["l", 0, r - 2 * u],
                        ["a", u, u, 0, 0, 1, -u, u],
                        ["l", 2 * u - i, 0],
                        ["a", u, u, 0, 0, 1, -u, -u],
                        ["l", 0, 2 * u - r],
                        ["a", u, u, 0, 0, 1, u, -u],
                        ["z"]
                    ] : [
                        ["M", n, t],
                        ["l", i, 0],
                        ["l", 0, r],
                        ["l", -i, 0],
                        ["z"]
                    ]
                },
                hr = function (n, t, i, r) {
                    return null == r && (r = i), [
                        ["M", n, t],
                        ["m", 0, -r],
                        ["a", i, r, 0, 1, 1, 0, 2 * r],
                        ["a", i, r, 0, 1, 1, 0, -2 * r],
                        ["z"]
                    ]
                },
                ti = t._getPath = {
                    path: function (n) {
                        return n.attr("path")
                    },
                    circle: function (n) {
                        var t = n.attrs;
                        return hr(t.cx, t.cy, t.r)
                    },
                    ellipse: function (n) {
                        var t = n.attrs;
                        return hr(t.cx, t.cy, t.rx, t.ry)
                    },
                    rect: function (n) {
                        var t = n.attrs;
                        return ni(t.x, t.y, t.width, t.height, t.r)
                    },
                    image: function (n) {
                        var t = n.attrs;
                        return ni(t.x, t.y, t.width, t.height)
                    },
                    text: function (n) {
                        var t = n._getBBox();
                        return ni(t.x, t.y, t.width, t.height)
                    },
                    set: function (n) {
                        var t = n._getBBox();
                        return ni(t.x, t.y, t.width, t.height)
                    }
                },
                yi = t.mapPath = function (n, t) {
                    if (!t) return n;
                    for (var f, e, i, s, r, u = 0, o = (n = wt(n)).length; u < o; u++)
                        for (i = 1, s = (r = n[u]).length; i < s; i += 2) f = t.x(r[i], r[i + 1]), e = t.y(r[i], r[i + 1]), r[i] = f, r[i + 1] = e;
                    return n
                };
            if (t._g = i, t.type = i.win.SVGAngle || i.doc.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#BasicStructure", "1.1") ? "SVG" : "VML", "VML" == t.type) {
                if (ii = i.doc.createElement("div"), ii.innerHTML = '<v:shape adj="1"/>', (pi = ii.firstChild).style.behavior = "url(#default#VML)", !pi || "object" != typeof pi.adj) return t.type = k;
                ii = null
            }
            t.svg = !(t.vml = "VML" == t.type);
            t._Paper = fr;
            t.fn = h = fr.prototype = t.prototype;
            t._id = 0;
            t.is = function (n, t) {
                return "finite" == (t = gt.call(t)) ? !wu[l](+n) : "array" == t ? n instanceof Array : "null" == t && null === n || t == typeof n && null !== n || "object" == t && n === Object(n) || "array" == t && Array.isArray && Array.isArray(n) || yu.call(n).slice(8, -1).toLowerCase() == t
            };
            t.angle = function (n, i, u, f, e, o) {
                if (null == e) {
                    var s = n - u,
                        h = i - f;
                    return s || h ? (180 + 180 * r.atan2(-h, -s) / b + 360) % 360 : 0
                }
                return t.angle(n, i, e, o) - t.angle(u, f, e, o)
            };
            t.rad = function (n) {
                return n % 360 * b / 180
            };
            t.deg = function (n) {
                return Math.round(180 * n / b % 360 * 1e3) / 1e3
            };
            t.snapTo = function (n, i, r) {
                var f, u;
                if (r = t.is(r, "finite") ? r : 10, t.is(n, nt)) {
                    for (f = n.length; f--;)
                        if (v(n[f] - i) <= r) return n[f]
                } else {
                    if (u = i % (n = +n), u < r) return i - u;
                    if (u > n - r) return i - u + n
                }
                return i
            };
            t.createUUID = (cr = /[xy]/g, lr = function (n) {
                var t = 16 * r.random() | 0;
                return ("x" == n ? t : 3 & t | 8).toString(16)
            }, function () {
                return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(cr, lr).toUpperCase()
            });
            t.setWindow = function (r) {
                n("raphael.setWindow", t, i.win, r);
                i.win = r;
                i.doc = i.win.document;
                t._engine.initWin && t._engine.initWin(i.win)
            };
            var ri = function (n) {
                    var u, e, f, o, r;
                    if (t.vml) {
                        e = /^\s+|\s+$/g;
                        try {
                            f = new ActiveXObject("htmlfile");
                            f.write("<body>");
                            f.close();
                            u = f.body
                        } catch (n) {
                            u = createPopup().document.body
                        }
                        o = u.createTextRange();
                        ri = tt(function (n) {
                            try {
                                u.style.color = w(n).replace(e, k);
                                var t = o.queryCommandValue("ForeColor");
                                return "#" + ("000000" + (t = (255 & t) << 16 | 65280 & t | (16711680 & t) >>> 16).toString(16)).slice(-6)
                            } catch (n) {
                                return "none"
                            }
                        })
                    } else r = i.doc.createElement("i"), r.title = "Raphaël Colour Picker", r.style.display = "none", i.doc.body.appendChild(r), ri = tt(function (n) {
                        return r.style.color = n, i.doc.defaultView.getComputedStyle(r, k).getPropertyValue("color")
                    });
                    return ri(n)
                },
                ff = function () {
                    return "hsb(" + [this.h, this.s, this.b] + ")"
                },
                ef = function () {
                    return "hsl(" + [this.h, this.s, this.l] + ")"
                },
                ar = function () {
                    return this.hex
                },
                vr = function (n, i, r) {
                    if (null == i && t.is(n, "object") && "r" in n && "g" in n && "b" in n && (r = n.b, i = n.g, n = n.r), null == i && t.is(n, "string")) {
                        var u = t.getRGB(n);
                        n = u.r;
                        i = u.g;
                        r = u.b
                    }
                    return (n > 1 || i > 1 || r > 1) && (n /= 255, i /= 255, r /= 255), [n, i, r]
                },
                yr = function (n, i, r, u) {
                    var f = {
                        r: n *= 255,
                        g: i *= 255,
                        b: r *= 255,
                        hex: t.rgb(n, i, r),
                        toString: ar
                    };
                    return t.is(u, "finite") && (f.opacity = u), f
                };
            t.color = function (n) {
                var i;
                return t.is(n, "object") && "h" in n && "s" in n && "b" in n ? (i = t.hsb2rgb(n), n.r = i.r, n.g = i.g, n.b = i.b, n.hex = i.hex) : t.is(n, "object") && "h" in n && "s" in n && "l" in n ? (i = t.hsl2rgb(n), n.r = i.r, n.g = i.g, n.b = i.b, n.hex = i.hex) : (t.is(n, "string") && (n = t.getRGB(n)), t.is(n, "object") && "r" in n && "g" in n && "b" in n ? (i = t.rgb2hsl(n), n.h = i.h, n.s = i.s, n.l = i.l, i = t.rgb2hsb(n), n.v = i.b) : (n = {
                    hex: "none"
                }).r = n.g = n.b = n.h = n.s = n.v = n.l = -1), n.toString = ar, n
            };
            t.hsb2rgb = function (n, t, i, r) {
                var e, o, s, f, u;
                return this.is(n, "object") && "h" in n && "s" in n && "b" in n && (i = n.b, t = n.s, r = n.o, n = n.h), f = (u = i * t) * (1 - v((n = (n *= 360) % 360 / 60) % 2 - 1)), e = o = s = i - u, yr(e += [u, f, 0, 0, f, u][n = ~~n], o += [f, u, u, f, 0, 0][n], s += [0, 0, f, u, u, f][n], r)
            };
            t.hsl2rgb = function (n, t, i, r) {
                var e, o, s, f, u;
                return this.is(n, "object") && "h" in n && "s" in n && "l" in n && (i = n.l, t = n.s, n = n.h), (n > 1 || t > 1 || i > 1) && (n /= 360, t /= 100, i /= 100), f = (u = 2 * t * (i < .5 ? i : 1 - i)) * (1 - v((n = (n *= 360) % 360 / 60) % 2 - 1)), e = o = s = i - u / 2, yr(e += [u, f, 0, 0, f, u][n = ~~n], o += [f, u, u, f, 0, 0][n], s += [0, 0, f, u, u, f][n], r)
            };
            t.rgb2hsb = function (n, t, i) {
                var u, r;
                return n = (i = vr(n, t, i))[0], t = i[1], i = i[2], {
                    h: ((0 == (r = (u = e(n, t, i)) - c(n, t, i)) ? null : u == n ? (t - i) / r : u == t ? (i - n) / r + 2 : (n - t) / r + 4) + 360) % 6 / 6,
                    s: 0 == r ? 0 : r / u,
                    b: u,
                    toString: ff
                }
            };
            t.rgb2hsl = function (n, t, i) {
                var u, f, o, r;
                return n = (i = vr(n, t, i))[0], t = i[1], i = i[2], u = ((f = e(n, t, i)) + (o = c(n, t, i))) / 2, {
                    h: ((0 == (r = f - o) ? null : f == n ? (t - i) / r : f == t ? (i - n) / r + 2 : (n - t) / r + 4) + 360) % 6 / 6,
                    s: 0 == r ? 0 : u < .5 ? r / (2 * u) : r / (2 - 2 * u),
                    l: u,
                    toString: ef
                }
            };
            t._path2string = function () {
                return this.join(",").replace(gu, "$1")
            };
            t._preload = function (n, t) {
                var r = i.doc.createElement("img");
                r.style.cssText = "position:absolute;left:-9999em;top:-9999em";
                r.onload = function () {
                    t.call(this);
                    this.onload = null;
                    i.doc.body.removeChild(this)
                };
                r.onerror = function () {
                    i.doc.body.removeChild(this)
                };
                i.doc.body.appendChild(r);
                r.src = n
            };
            t.getRGB = tt(function (n) {
                if (!n || (n = w(n)).indexOf("-") + 1) return {
                    r: -1,
                    g: -1,
                    b: -1,
                    hex: "none",
                    error: 1,
                    toString: ui
                };
                if ("none" == n) return {
                    r: -1,
                    g: -1,
                    b: -1,
                    hex: "none",
                    toString: ui
                };
                du[l](n.toLowerCase().substring(0, 2)) || "#" == n.charAt() || (n = ri(n));
                var u, f, e, o, h, i, r = n.match(pu);
                return r ? (r[2] && (e = ft(r[2].substring(5), 16), f = ft(r[2].substring(3, 5), 16), u = ft(r[2].substring(1, 3), 16)), r[3] && (e = ft((h = r[3].charAt(3)) + h, 16), f = ft((h = r[3].charAt(2)) + h, 16), u = ft((h = r[3].charAt(1)) + h, 16)), r[4] && (i = r[4][rt](vi), u = s(i[0]), "%" == i[0].slice(-1) && (u *= 2.55), f = s(i[1]), "%" == i[1].slice(-1) && (f *= 2.55), e = s(i[2]), "%" == i[2].slice(-1) && (e *= 2.55), "rgba" == r[1].toLowerCase().slice(0, 4) && (o = s(i[3])), i[3] && "%" == i[3].slice(-1) && (o /= 100)), r[5] ? (i = r[5][rt](vi), u = s(i[0]), "%" == i[0].slice(-1) && (u *= 2.55), f = s(i[1]), "%" == i[1].slice(-1) && (f *= 2.55), e = s(i[2]), "%" == i[2].slice(-1) && (e *= 2.55), ("deg" == i[0].slice(-3) || "°" == i[0].slice(-1)) && (u /= 360), "hsba" == r[1].toLowerCase().slice(0, 4) && (o = s(i[3])), i[3] && "%" == i[3].slice(-1) && (o /= 100), t.hsb2rgb(u, f, e, o)) : r[6] ? (i = r[6][rt](vi), u = s(i[0]), "%" == i[0].slice(-1) && (u *= 2.55), f = s(i[1]), "%" == i[1].slice(-1) && (f *= 2.55), e = s(i[2]), "%" == i[2].slice(-1) && (e *= 2.55), ("deg" == i[0].slice(-3) || "°" == i[0].slice(-1)) && (u /= 360), "hsla" == r[1].toLowerCase().slice(0, 4) && (o = s(i[3])), i[3] && "%" == i[3].slice(-1) && (o /= 100), t.hsl2rgb(u, f, e, o)) : ((r = {
                    r: u,
                    g: f,
                    b: e,
                    toString: ui
                }).hex = "#" + (16777216 | e | f << 8 | u << 16).toString(16).slice(1), t.is(o, "finite") && (r.opacity = o), r)) : {
                    r: -1,
                    g: -1,
                    b: -1,
                    hex: "none",
                    error: 1,
                    toString: ui
                }
            }, t);
            t.hsb = tt(function (n, i, r) {
                return t.hsb2rgb(n, i, r).hex
            });
            t.hsl = tt(function (n, i, r) {
                return t.hsl2rgb(n, i, r).hex
            });
            t.rgb = tt(function (n, t, i) {
                function r(n) {
                    return n + .5 | 0
                }
                return "#" + (16777216 | r(i) | r(t) << 8 | r(n) << 16).toString(16).slice(1)
            });
            t.getColor = function (n) {
                var t = this.getColor.start = this.getColor.start || {
                        h: 0,
                        s: 1,
                        b: n || .75
                    },
                    i = this.hsb2rgb(t.h, t.s, t.b);
                return t.h += .075, t.h > 1 && (t.h = 0, t.s -= .2, t.s <= 0 && (this.getColor.start = {
                    h: 0,
                    s: 1,
                    b: t.b
                })), i.hex
            };
            t.getColor.reset = function () {
                delete this.start
            };
            t.parsePathString = function (n) {
                var r, u, i;
                return n ? (r = et(n), r.arr) ? it(r.arr) : (u = {
                    a: 7,
                    c: 6,
                    h: 1,
                    l: 2,
                    m: 2,
                    r: 4,
                    q: 4,
                    s: 4,
                    t: 2,
                    v: 1,
                    z: 0
                }, i = [], t.is(n, nt) && t.is(n[0], nt) && (i = it(n)), i.length || w(n).replace(nf, function (n, t, r) {
                    var f = [],
                        e = t.toLowerCase();
                    if (r.replace(sr, function (n, t) {
                            t && f.push(+t)
                        }), "m" == e && f.length > 2 && (i.push([t][o](f.splice(0, 2))), e = "l", t = "m" == t ? "l" : "L"), "r" == e) i.push([t][o](f));
                    else
                        for (; f.length >= u[e] && (i.push([t][o](f.splice(0, u[e]))), u[e]););
                }), i.toString = t._path2string, r.arr = it(i), i) : null
            };
            t.parseTransformString = tt(function (n) {
                if (!n) return null;
                var i = [];
                return t.is(n, nt) && t.is(n[0], nt) && (i = it(n)), i.length || w(n).replace(tf, function (n, t, r) {
                    var u = [];
                    gt.call(t);
                    r.replace(sr, function (n, t) {
                        t && u.push(+t)
                    });
                    i.push([t][o](u))
                }), i.toString = t._path2string, i
            }, this, function (n) {
                var r, t, u, i;
                if (!n) return n;
                for (r = [], t = 0; t < n.length; t++) {
                    for (u = [], i = 0; i < n[t].length; i++) u.push(n[t][i]);
                    r.push(u)
                }
                return r
            });
            et = function (n) {
                var t = et.ps = et.ps || {};
                return t[n] ? t[n].sleep = 100 : t[n] = {
                    sleep: 100
                }, setTimeout(function () {
                    for (var i in t) t[l](i) && i != n && (t[i].sleep--, !t[i].sleep && delete t[i])
                }), t[n]
            };
            t.findDotsAtSegment = function (n, t, i, u, f, e, o, s, h) {
                var c = 1 - h,
                    w = g(c, 3),
                    k = g(c, 2),
                    l = h * h,
                    d = l * h,
                    tt = w * n + 3 * k * h * i + 3 * c * h * h * f + d * o,
                    it = w * t + 3 * k * h * u + 3 * c * h * h * e + d * s,
                    a = n + 2 * h * (i - n) + l * (f - 2 * i + n),
                    v = t + 2 * h * (u - t) + l * (e - 2 * u + t),
                    y = i + 2 * h * (f - i) + l * (o - 2 * f + i),
                    p = u + 2 * h * (e - u) + l * (s - 2 * e + u),
                    rt = c * n + h * i,
                    ut = c * t + h * u,
                    ft = c * f + h * o,
                    et = c * e + h * s,
                    nt = 90 - 180 * r.atan2(a - y, v - p) / b;
                return (a > y || v < p) && (nt += 180), {
                    x: tt,
                    y: it,
                    m: {
                        x: a,
                        y: v
                    },
                    n: {
                        x: y,
                        y: p
                    },
                    start: {
                        x: rt,
                        y: ut
                    },
                    end: {
                        x: ft,
                        y: et
                    },
                    alpha: nt
                }
            };
            t.bezierBBox = function (n, i, r, u, f, e, o, s) {
                t.is(n, "array") || (n = [n, i, r, u, f, e, o, s]);
                var h = gr.apply(null, n);
                return {
                    x: h.min.x,
                    y: h.min.y,
                    x2: h.max.x,
                    y2: h.max.y,
                    width: h.max.x - h.min.x,
                    height: h.max.y - h.min.y
                }
            };
            t.isPointInsideBBox = function (n, t, i) {
                return t >= n.x && t <= n.x2 && i >= n.y && i <= n.y2
            };
            t.isBBoxIntersect = function (n, i) {
                var r = t.isPointInsideBBox;
                return r(i, n.x, n.y) || r(i, n.x2, n.y) || r(i, n.x, n.y2) || r(i, n.x2, n.y2) || r(n, i.x, i.y) || r(n, i.x2, i.y) || r(n, i.x, i.y2) || r(n, i.x2, i.y2) || (n.x < i.x2 && n.x > i.x || i.x < n.x2 && i.x > n.x) && (n.y < i.y2 && n.y > i.y || i.y < n.y2 && i.y > n.y)
            };
            t.pathIntersection = function (n, t) {
                return wi(n, t)
            };
            t.pathIntersectionNumber = function (n, t) {
                return wi(n, t, 1)
            };
            t.isPointInsidePath = function (n, i, r) {
                var u = t.pathBBox(n);
                return t.isPointInsideBBox(u, i, r) && wi(n, [
                    ["M", i, r],
                    ["H", u.x2 + 10]
                ], 1) % 2 == 1
            };
            t._removedFactory = function (t) {
                return function () {
                    n("raphael.log", null, "Raphaël: you are calling to method “" + t + "” of removed object", t)
                }
            };
            var bi = t.pathBBox = function (n) {
                    var h = et(n),
                        u;
                    if (h.bbox) return pt(h.bbox);
                    if (!n) return {
                        x: 0,
                        y: 0,
                        width: 0,
                        height: 0,
                        x2: 0,
                        y2: 0
                    };
                    for (var t, f = 0, s = 0, i = [], r = [], l = 0, g = (n = wt(n)).length; l < g; l++) "M" == (t = n[l])[0] ? (f = t[1], s = t[2], i.push(f), r.push(s)) : (u = gr(f, s, t[1], t[2], t[3], t[4], t[5], t[6]), i = i[o](u.min.x, u.max.x), r = r[o](u.min.y, u.max.y), f = t[5], s = t[6]);
                    var v = c[a](0, i),
                        y = c[a](0, r),
                        p = e[a](0, i),
                        w = e[a](0, r),
                        b = p - v,
                        k = w - y,
                        d = {
                            x: v,
                            y: y,
                            x2: p,
                            y2: w,
                            width: b,
                            height: k,
                            cx: v + b / 2,
                            cy: y + k / 2
                        };
                    return h.bbox = pt(d), d
                },
                it = function (n) {
                    var i = pt(n);
                    return i.toString = t._path2string, i
                },
                hf = t._pathToRelative = function (n) {
                    var v = et(n),
                        u, p, f, i, s, w, h, b, c;
                    if (v.rel) return it(v.rel);
                    t.is(n, nt) && t.is(n && n[0], nt) || (n = t.parsePathString(n));
                    var r = [],
                        o = 0,
                        e = 0,
                        l = 0,
                        a = 0,
                        y = 0;
                    for ("M" == n[0][0] && (l = o = n[0][1], a = e = n[0][2], y++, r.push(["M", o, e])), u = y, p = n.length; u < p; u++) {
                        if (f = r[u] = [], i = n[u], i[0] != gt.call(i[0])) switch (f[0] = gt.call(i[0]), f[0]) {
                            case "a":
                                f[1] = i[1];
                                f[2] = i[2];
                                f[3] = i[3];
                                f[4] = i[4];
                                f[5] = i[5];
                                f[6] = +(i[6] - o).toFixed(3);
                                f[7] = +(i[7] - e).toFixed(3);
                                break;
                            case "v":
                                f[1] = +(i[1] - e).toFixed(3);
                                break;
                            case "m":
                                l = i[1];
                                a = i[2];
                            default:
                                for (s = 1, w = i.length; s < w; s++) f[s] = +(i[s] - (s % 2 ? o : e)).toFixed(3)
                        } else
                            for (f = r[u] = [], "m" == i[0] && (l = i[1] + o, a = i[2] + e), h = 0, b = i.length; h < b; h++) r[u][h] = i[h];
                        c = r[u].length;
                        switch (r[u][0]) {
                            case "z":
                                o = l;
                                e = a;
                                break;
                            case "h":
                                o += +r[u][c - 1];
                                break;
                            case "v":
                                e += +r[u][c - 1];
                                break;
                            default:
                                o += +r[u][c - 2];
                                e += +r[u][c - 1]
                        }
                    }
                    return r.toString = t._path2string, v.rel = it(r), r
                },
                br = t._pathToAbsolute = function (n) {
                    var v = et(n),
                        c, k;
                    if (v.abs) return it(v.abs);
                    if (t.is(n, nt) && t.is(n && n[0], nt) || (n = t.parsePathString(n)), !n || !n.length) return [
                        ["M", 0, 0]
                    ];
                    var s = [],
                        u = 0,
                        f = 0,
                        l = 0,
                        a = 0,
                        w = 0;
                    "M" == n[0][0] && (l = u = +n[0][1], a = f = +n[0][2], w++, s[0] = ["M", u, f]);
                    for (var i, r, b = 3 == n.length && "M" == n[0][0] && "R" == n[1][0].toUpperCase() && "Z" == n[2][0].toUpperCase(), y = w, d = n.length; y < d; y++) {
                        if (s.push(i = []), (r = n[y])[0] != or.call(r[0])) switch (i[0] = or.call(r[0]), i[0]) {
                                case "A":
                                    i[1] = r[1];
                                    i[2] = r[2];
                                    i[3] = r[3];
                                    i[4] = r[4];
                                    i[5] = r[5];
                                    i[6] = +(r[6] + u);
                                    i[7] = +(r[7] + f);
                                    break;
                                case "V":
                                    i[1] = +r[1] + f;
                                    break;
                                case "H":
                                    i[1] = +r[1] + u;
                                    break;
                                case "R":
                                    for (var h = [u, f][o](r.slice(1)), e = 2, p = h.length; e < p; e++) h[e] = +h[e] + u, h[++e] = +h[e] + f;
                                    s.pop();
                                    s = s[o](pr(h, b));
                                    break;
                                case "M":
                                    l = +r[1] + u;
                                    a = +r[2] + f;
                                default:
                                    for (e = 1, p = r.length; e < p; e++) i[e] = +r[e] + (e % 2 ? u : f)
                            } else if ("R" == r[0]) h = [u, f][o](r.slice(1)), s.pop(), s = s[o](pr(h, b)), i = ["R"][o](r.slice(-2));
                            else
                                for (c = 0, k = r.length; c < k; c++) i[c] = r[c];
                        switch (i[0]) {
                            case "Z":
                                u = l;
                                f = a;
                                break;
                            case "H":
                                u = i[1];
                                break;
                            case "V":
                                f = i[1];
                                break;
                            case "M":
                                l = i[i.length - 2];
                                a = i[i.length - 1];
                            default:
                                u = i[i.length - 2];
                                f = i[i.length - 1]
                        }
                    }
                    return s.toString = t._path2string, v.abs = it(s), s
                },
                fi = function (n, t, i, r) {
                    return [n, t, i, r, i, r]
                },
                kr = function (n, t, i, r, u, f) {
                    return [1 / 3 * n + 2 / 3 * i, 1 / 3 * t + 2 / 3 * r, 1 / 3 * u + 2 / 3 * i, 1 / 3 * f + 2 / 3 * r, u, f]
                },
                dr = function (n, t, i, u, f, e, s, h, c, l) {
                    var ut, lt = 120 * b / 180,
                        ft = b / 180 * (+f || 0),
                        p = [],
                        et = tt(function (n, t, i) {
                            return {
                                x: n * r.cos(i) - t * r.sin(i),
                                y: n * r.sin(i) + t * r.cos(i)
                            }
                        }),
                        st;
                    if (l) y = l[0], a = l[1], nt = l[2], it = l[3];
                    else {
                        n = (ut = et(n, t, -ft)).x;
                        t = ut.y;
                        h = (ut = et(h, c, -ft)).x;
                        c = ut.y;
                        r.cos(b / 180 * f);
                        r.sin(b / 180 * f);
                        var k = (n - h) / 2,
                            d = (t - c) / 2,
                            ot = k * k / (i * i) + d * d / (u * u);
                        ot > 1 && (i *= ot = r.sqrt(ot), u *= ot);
                        var ht = i * i,
                            ct = u * u,
                            at = (e == s ? -1 : 1) * r.sqrt(v((ht * ct - ht * d * d - ct * k * k) / (ht * d * d + ct * k * k))),
                            nt = at * i * d / u + (n + h) / 2,
                            it = at * -u * k / i + (t + c) / 2,
                            y = r.asin(((t - it) / u).toFixed(9)),
                            a = r.asin(((c - it) / u).toFixed(9));
                        (y = n < nt ? b - y : y) < 0 && (y = 2 * b + y);
                        (a = h < nt ? b - a : a) < 0 && (a = 2 * b + a);
                        s && y > a && (y -= 2 * b);
                        !s && a > y && (a -= 2 * b)
                    }
                    if (st = a - y, v(st) > lt) {
                        var gt = a,
                            ni = h,
                            ti = c;
                        a = y + lt * (s && a > y ? 1 : -1);
                        h = nt + i * r.cos(a);
                        c = it + u * r.sin(a);
                        p = dr(h, c, i, u, f, 0, s, ni, ti, [a, gt, nt, it])
                    }
                    st = a - y;
                    var ii = r.cos(y),
                        ri = r.sin(y),
                        ui = r.cos(a),
                        fi = r.sin(a),
                        vt = r.tan(st / 4),
                        yt = 4 / 3 * i * vt,
                        pt = 4 / 3 * u * vt,
                        wt = [n, t],
                        g = [n + yt * ri, t - pt * ii],
                        bt = [h + yt * fi, c - pt * ui],
                        kt = [h, c];
                    if (g[0] = 2 * wt[0] - g[0], g[1] = 2 * wt[1] - g[1], l) return [g, bt, kt][o](p);
                    for (var dt = [], w = 0, ei = (p = [g, bt, kt][o](p).join()[rt](",")).length; w < ei; w++) dt[w] = w % 2 ? et(p[w - 1], p[w], ft).y : et(p[w], p[w + 1], ft).x;
                    return dt
                },
                ei = function (n, t, i, r, u, f, e, o, s) {
                    var h = 1 - s;
                    return {
                        x: g(h, 3) * n + 3 * g(h, 2) * s * i + 3 * h * s * s * u + g(s, 3) * e,
                        y: g(h, 3) * t + 3 * g(h, 2) * s * r + 3 * h * s * s * f + g(s, 3) * o
                    }
                },
                gr = tt(function (n, t, i, u, f, o, s, h) {
                    var l, b = f - 2 * i + n - (s - 2 * f + i),
                        y = 2 * (i - n) - 2 * (f - i),
                        g = n - i,
                        p = (-y + r.sqrt(y * y - 4 * b * g)) / 2 / b,
                        w = (-y - r.sqrt(y * y - 4 * b * g)) / 2 / b,
                        k = [t, h],
                        d = [n, s];
                    return v(p) > "1e12" && (p = .5), v(w) > "1e12" && (w = .5), p > 0 && p < 1 && (l = ei(n, t, i, u, f, o, s, h, p), d.push(l.x), k.push(l.y)), w > 0 && w < 1 && (l = ei(n, t, i, u, f, o, s, h, w), d.push(l.x), k.push(l.y)), b = o - 2 * u + t - (h - 2 * o + u), g = t - u, p = (-(y = 2 * (u - t) - 2 * (o - u)) + r.sqrt(y * y - 4 * b * g)) / 2 / b, w = (-y - r.sqrt(y * y - 4 * b * g)) / 2 / b, v(p) > "1e12" && (p = .5), v(w) > "1e12" && (w = .5), p > 0 && p < 1 && (l = ei(n, t, i, u, f, o, s, h, p), d.push(l.x), k.push(l.y)), w > 0 && w < 1 && (l = ei(n, t, i, u, f, o, s, h, w), d.push(l.x), k.push(l.y)), {
                        min: {
                            x: c[a](0, d),
                            y: c[a](0, k)
                        },
                        max: {
                            x: e[a](0, d),
                            y: e[a](0, k)
                        }
                    }
                }),
                wt = t._path2curve = tt(function (n, t) {
                    var d = !t && et(n);
                    if (!t && d.curve) return it(d.curve);
                    for (var u = br(n), r = t && br(t), f = {
                            x: 0,
                            y: 0,
                            bx: 0,
                            by: 0,
                            X: 0,
                            Y: 0,
                            qx: null,
                            qy: null
                        }, h = {
                            x: 0,
                            y: 0,
                            bx: 0,
                            by: 0,
                            X: 0,
                            Y: 0,
                            qx: null,
                            qy: null
                        }, nt = function (n, t, i) {
                            var r, u;
                            if (!n) return ["C", t.x, t.y, t.x, t.y, t.x, t.y];
                            switch (!(n[0] in {
                                T: 1,
                                Q: 1
                            }) && (t.qx = t.qy = null), n[0]) {
                                case "M":
                                    t.X = n[1];
                                    t.Y = n[2];
                                    break;
                                case "A":
                                    n = ["C"][o](dr[a](0, [t.x, t.y][o](n.slice(1))));
                                    break;
                                case "S":
                                    "C" == i || "S" == i ? (r = 2 * t.x - t.bx, u = 2 * t.y - t.by) : (r = t.x, u = t.y);
                                    n = ["C", r, u][o](n.slice(1));
                                    break;
                                case "T":
                                    "Q" == i || "T" == i ? (t.qx = 2 * t.x - t.qx, t.qy = 2 * t.y - t.qy) : (t.qx = t.x, t.qy = t.y);
                                    n = ["C"][o](kr(t.x, t.y, t.qx, t.qy, n[1], n[2]));
                                    break;
                                case "Q":
                                    t.qx = n[1];
                                    t.qy = n[2];
                                    n = ["C"][o](kr(t.x, t.y, n[1], n[2], n[3], n[4]));
                                    break;
                                case "L":
                                    n = ["C"][o](fi(t.x, t.y, n[1], n[2]));
                                    break;
                                case "H":
                                    n = ["C"][o](fi(t.x, t.y, n[1], t.y));
                                    break;
                                case "V":
                                    n = ["C"][o](fi(t.x, t.y, t.x, n[1]));
                                    break;
                                case "Z":
                                    n = ["C"][o](fi(t.x, t.y, t.X, t.Y))
                            }
                            return n
                        }, tt = function (n, t) {
                            if (n[t].length > 7) {
                                n[t].shift();
                                for (var i = n[t]; i.length;) l[t] = "A", r && (v[t] = "A"), n.splice(t++, 0, ["C"][o](i.splice(0, 6)));
                                n.splice(t, 1);
                                g = e(u.length, r && r.length || 0)
                            }
                        }, rt = function (n, t, i, f, o) {
                            n && t && "M" == n[o][0] && "M" != t[o][0] && (t.splice(o, 0, ["M", f.x, f.y]), i.bx = 0, i.by = 0, i.x = n[o][1], i.y = n[o][2], g = e(u.length, r && r.length || 0))
                        }, l = [], v = [], c = "", w = "", i = 0, g = e(u.length, r && r.length || 0); i < g; i++) {
                        u[i] && (c = u[i][0]);
                        "C" != c && (l[i] = c, i && (w = l[i - 1]));
                        u[i] = nt(u[i], f, w);
                        "A" != l[i] && "C" == c && (l[i] = "C");
                        tt(u, i);
                        r && (r[i] && (c = r[i][0]), "C" != c && (v[i] = c, i && (w = v[i - 1])), r[i] = nt(r[i], h, w), "A" != v[i] && "C" == c && (v[i] = "C"), tt(r, i));
                        rt(u, r, f, h, i);
                        rt(r, u, h, f, i);
                        var y = u[i],
                            p = r && r[i],
                            b = y.length,
                            k = r && p.length;
                        f.x = y[b - 2];
                        f.y = y[b - 1];
                        f.bx = s(y[b - 4]) || f.x;
                        f.by = s(y[b - 3]) || f.y;
                        h.bx = r && (s(p[k - 4]) || h.x);
                        h.by = r && (s(p[k - 3]) || h.y);
                        h.x = r && p[k - 2];
                        h.y = r && p[k - 1]
                    }
                    return r || (d.curve = it(u)), r ? [u, r] : u
                }, null, it),
                oi = (t._parseDots = tt(function (n) {
                    for (var r, h, l, u = [], i = 0, e = n.length; i < e; i++) {
                        if (r = {}, h = n[i].match(/^([^:]*):?([\d\.]*)/), r.color = t.getRGB(h[1]), r.color.error) return null;
                        r.opacity = r.color.opacity;
                        r.color = r.color.hex;
                        h[2] && (r.offset = h[2] + "%");
                        u.push(r)
                    }
                    for (i = 1, e = u.length - 1; i < e; i++)
                        if (!u[i].offset) {
                            for (var c = s(u[i - 1].offset || 0), o = 0, f = i + 1; f < e; f++)
                                if (u[f].offset) {
                                    o = u[f].offset;
                                    break
                                } for (o || (o = 100, f = e), l = ((o = s(o)) - c) / (f - i + 1); i < f; i++) c += l, u[i].offset = c + "%"
                        } return u
                }), t._tear = function (n, t) {
                    n == t.top && (t.top = n.prev);
                    n == t.bottom && (t.bottom = n.next);
                    n.next && (n.next.prev = n.prev);
                    n.prev && (n.prev.next = n.next)
                }),
                cf = (t._tofront = function (n, t) {
                    t.top !== n && (oi(n, t), n.next = null, n.prev = t.top, t.top.next = n, t.top = n)
                }, t._toback = function (n, t) {
                    t.bottom !== n && (oi(n, t), n.next = t.bottom, n.prev = null, t.bottom.prev = n, t.bottom = n)
                }, t._insertafter = function (n, t, i) {
                    oi(n, i);
                    t == i.top && (i.top = n);
                    t.next && (t.next.prev = n);
                    n.next = t.next;
                    n.prev = t;
                    t.next = n
                }, t._insertbefore = function (n, t, i) {
                    oi(n, i);
                    t == i.bottom && (i.bottom = n);
                    t.prev && (t.prev.next = n);
                    n.prev = t.prev;
                    t.prev = n;
                    n.next = t
                }, t.toMatrix = function (n, t) {
                    var r = bi(n),
                        i = {
                            _: {
                                transform: k
                            },
                            getBBox: function () {
                                return r
                            }
                        };
                    return nu(i, t), i.matrix
                }),
                nu = (t.transformPath = function (n, t) {
                    return yi(n, cf(n, t))
                }, t._extractTransform = function (n, i) {
                    var b, tt;
                    if (null == i) return n._.transform;
                    i = w(i).replace(/\.{3}|\u2026/g, n._.transform || k);
                    var g, nt, a = t.parseTransformString(i),
                        v = 0,
                        y = 1,
                        p = 1,
                        e = n._,
                        u = new st;
                    if (e.transform = a || [], a)
                        for (b = 0, tt = a.length; b < tt; b++) {
                            var it, rt, h, c, f, r = a[b],
                                o = r.length,
                                l = w(r[0]).toLowerCase(),
                                d = r[0] != l,
                                s = d ? u.invert() : 0;
                            "t" == l && 3 == o ? d ? (it = s.x(0, 0), rt = s.y(0, 0), h = s.x(r[1], r[2]), c = s.y(r[1], r[2]), u.translate(h - it, c - rt)) : u.translate(r[1], r[2]) : "r" == l ? 2 == o ? (f = f || n.getBBox(1), u.rotate(r[1], f.x + f.width / 2, f.y + f.height / 2), v += r[1]) : 4 == o && (d ? (h = s.x(r[2], r[3]), c = s.y(r[2], r[3]), u.rotate(r[1], h, c)) : u.rotate(r[1], r[2], r[3]), v += r[1]) : "s" == l ? 2 == o || 3 == o ? (f = f || n.getBBox(1), u.scale(r[1], r[o - 1], f.x + f.width / 2, f.y + f.height / 2), y *= r[1], p *= r[o - 1]) : 5 == o && (d ? (h = s.x(r[3], r[4]), c = s.y(r[3], r[4]), u.scale(r[1], r[2], h, c)) : u.scale(r[1], r[2], r[3], r[4]), y *= r[1], p *= r[2]) : "m" == l && 7 == o && u.add(r[1], r[2], r[3], r[4], r[5], r[6]);
                            e.dirtyT = 1;
                            n.matrix = u
                        }
                    n.matrix = u;
                    e.sx = y;
                    e.sy = p;
                    e.deg = v;
                    e.dx = g = u.e;
                    e.dy = nt = u.f;
                    1 == y && 1 == p && !v && e.bbox ? (e.bbox.x += +g, e.bbox.y += +nt) : e.dirtyT = 1
                }),
                tu = function (n) {
                    var t = n[0];
                    switch (t.toLowerCase()) {
                        case "t":
                            return [t, 0, 0];
                        case "m":
                            return [t, 1, 0, 0, 1, 0, 0];
                        case "r":
                            return 4 == n.length ? [t, 0, n[2], n[3]] : [t, 0];
                        case "s":
                            return 5 == n.length ? [t, 1, 1, n[3], n[4]] : 3 == n.length ? [t, 1, 1] : [t, 1]
                    }
                },
                lf = t._equaliseTransform = function (n, i) {
                    i = w(i).replace(/\.{3}|\u2026/g, n);
                    n = t.parseTransformString(n) || [];
                    i = t.parseTransformString(i) || [];
                    for (var u, c, r, f, l = e(n.length, i.length), s = [], h = [], o = 0; o < l; o++) {
                        if (r = n[o] || tu(i[o]), f = i[o] || tu(r), r[0] != f[0] || "r" == r[0].toLowerCase() && (r[2] != f[2] || r[3] != f[3]) || "s" == r[0].toLowerCase() && (r[3] != f[3] || r[4] != f[4])) return;
                        for (s[o] = [], h[o] = [], u = 0, c = e(r.length, f.length); u < c; u++) u in r && (s[o][u] = r[u]), u in f && (h[o][u] = f[u])
                    }
                    return {
                        from: s,
                        to: h
                    }
                };
            t._getContainer = function (n, r, u, f) {
                var e;
                if (null != (e = null != f || t.is(n, "object") ? n : i.doc.getElementById(n))) return e.tagName ? null == r ? {
                    container: e,
                    width: e.style.pixelWidth || e.offsetWidth,
                    height: e.style.pixelHeight || e.offsetHeight
                } : {
                    container: e,
                    width: r,
                    height: u
                } : {
                    container: 1,
                    x: n,
                    y: r,
                    width: u,
                    height: f
                }
            };
            t.pathToRelative = hf;
            t._engine = {};
            t.path2curve = wt;
            t.matrix = function (n, t, i, r, u, f) {
                    return new st(n, t, i, r, u, f)
                },
                function (n) {
                    function i(n) {
                        return n[0] * n[0] + n[1] * n[1]
                    }

                    function u(n) {
                        var t = r.sqrt(i(n));
                        n[0] && (n[0] /= t);
                        n[1] && (n[1] /= t)
                    }
                    n.add = function (n, t, i, r, u, f) {
                        var o, s, h, c, e = [
                                [],
                                [],
                                []
                            ],
                            a = [
                                [this.a, this.c, this.e],
                                [this.b, this.d, this.f],
                                [0, 0, 1]
                            ],
                            l = [
                                [n, i, u],
                                [t, r, f],
                                [0, 0, 1]
                            ];
                        for (n && n instanceof st && (l = [
                                [n.a, n.c, n.e],
                                [n.b, n.d, n.f],
                                [0, 0, 1]
                            ]), o = 0; o < 3; o++)
                            for (s = 0; s < 3; s++) {
                                for (c = 0, h = 0; h < 3; h++) c += a[o][h] * l[h][s];
                                e[o][s] = c
                            }
                        this.a = e[0][0];
                        this.b = e[1][0];
                        this.c = e[0][1];
                        this.d = e[1][1];
                        this.e = e[0][2];
                        this.f = e[1][2]
                    };
                    n.invert = function () {
                        var n = this,
                            t = n.a * n.d - n.b * n.c;
                        return new st(n.d / t, -n.b / t, -n.c / t, n.a / t, (n.c * n.f - n.d * n.e) / t, (n.b * n.e - n.a * n.f) / t)
                    };
                    n.clone = function () {
                        return new st(this.a, this.b, this.c, this.d, this.e, this.f)
                    };
                    n.translate = function (n, t) {
                        this.add(1, 0, 0, 1, n, t)
                    };
                    n.scale = function (n, t, i, r) {
                        null == t && (t = n);
                        (i || r) && this.add(1, 0, 0, 1, i, r);
                        this.add(n, 0, 0, t, 0, 0);
                        (i || r) && this.add(1, 0, 0, 1, -i, -r)
                    };
                    n.rotate = function (n, i, u) {
                        n = t.rad(n);
                        i = i || 0;
                        u = u || 0;
                        var f = +r.cos(n).toFixed(9),
                            e = +r.sin(n).toFixed(9);
                        this.add(f, e, -e, f, i, u);
                        this.add(1, 0, 0, 1, -i, -u)
                    };
                    n.x = function (n, t) {
                        return n * this.a + t * this.c + this.e
                    };
                    n.y = function (n, t) {
                        return n * this.b + t * this.d + this.f
                    };
                    n.get = function (n) {
                        return +this[w.fromCharCode(97 + n)].toFixed(4)
                    };
                    n.toString = function () {
                        return t.svg ? "matrix(" + [this.get(0), this.get(1), this.get(2), this.get(3), this.get(4), this.get(5)].join() + ")" : [this.get(0), this.get(2), this.get(1), this.get(3), 0, 0].join()
                    };
                    n.toFilter = function () {
                        return "progid:DXImageTransform.Microsoft.Matrix(M11=" + this.get(0) + ", M12=" + this.get(2) + ", M21=" + this.get(1) + ", M22=" + this.get(3) + ", Dx=" + this.get(4) + ", Dy=" + this.get(5) + ", sizingmethod='auto expand')"
                    };
                    n.offset = function () {
                        return [this.e.toFixed(4), this.f.toFixed(4)]
                    };
                    n.split = function () {
                        var n = {},
                            f, e, o;
                        return n.dx = this.e, n.dy = this.f, f = [
                            [this.a, this.c],
                            [this.b, this.d]
                        ], n.scalex = r.sqrt(i(f[0])), u(f[0]), n.shear = f[0][0] * f[1][0] + f[0][1] * f[1][1], f[1] = [f[1][0] - f[0][0] * n.shear, f[1][1] - f[0][1] * n.shear], n.scaley = r.sqrt(i(f[1])), u(f[1]), n.shear /= n.scaley, e = -f[0][1], o = f[1][1], o < 0 ? (n.rotate = t.deg(r.acos(o)), e < 0 && (n.rotate = 360 - n.rotate)) : n.rotate = t.deg(r.asin(e)), n.isSimple = !(+n.shear.toFixed(9) || n.scalex.toFixed(9) != n.scaley.toFixed(9) && n.rotate), n.isSuperSimple = !+n.shear.toFixed(9) && n.scalex.toFixed(9) == n.scaley.toFixed(9) && !n.rotate, n.noRotation = !+n.shear.toFixed(9) && !n.rotate, n
                    };
                    n.toTransformString = function (n) {
                        var t = n || this[rt]();
                        return t.isSimple ? (t.scalex = +t.scalex.toFixed(4), t.scaley = +t.scaley.toFixed(4), t.rotate = +t.rotate.toFixed(4), (t.dx || t.dy ? "t" + [t.dx, t.dy] : k) + (1 != t.scalex || 1 != t.scaley ? "s" + [t.scalex, t.scaley, 0, 0] : k) + (t.rotate ? "r" + [t.rotate, 0, 0] : k)) : "m" + [this.get(0), this.get(1), this.get(2), this.get(3), this.get(4), this.get(5)]
                    }
                }(st.prototype);
            for (var af = function () {
                    this.returnValue = !1
                }, vf = function () {
                    return this.originalEvent.preventDefault()
                }, yf = function () {
                    this.cancelBubble = !0
                }, pf = function () {
                    return this.originalEvent.stopPropagation()
                }, iu = function (n) {
                    var t = i.doc.documentElement.scrollTop || i.doc.body.scrollTop,
                        r = i.doc.documentElement.scrollLeft || i.doc.body.scrollLeft;
                    return {
                        x: n.clientX + r,
                        y: n.clientY + t
                    }
                }, wf = i.doc.addEventListener ? function (n, t, i, r) {
                    var f = function (n) {
                            var t = iu(n);
                            return i.call(r, n, t.x, t.y)
                        },
                        u;
                    return (n.addEventListener(t, f, !1), kt && dt[t]) && (u = function (t) {
                            for (var f = iu(t), e = t, u = 0, o = t.targetTouches && t.targetTouches.length; u < o; u++)
                                if (t.targetTouches[u].target == n) {
                                    (t = t.targetTouches[u]).originalEvent = e;
                                    t.preventDefault = vf;
                                    t.stopPropagation = pf;
                                    break
                                } return i.call(r, t, f.x, f.y)
                        }, n.addEventListener(dt[t], u, !1)),
                        function () {
                            return n.removeEventListener(t, f, !1), kt && dt[t] && n.removeEventListener(dt[t], u, !1), !0
                        }
                } : i.doc.attachEvent ? function (n, t, r, u) {
                    var f = function (n) {
                        n = n || i.win.event;
                        var t = i.doc.documentElement.scrollTop || i.doc.body.scrollTop,
                            f = i.doc.documentElement.scrollLeft || i.doc.body.scrollLeft,
                            e = n.clientX + f,
                            o = n.clientY + t;
                        return n.preventDefault = n.preventDefault || af, n.stopPropagation = n.stopPropagation || yf, r.call(u, n, e, o)
                    };
                    return n.attachEvent("on" + t, f),
                        function () {
                            return n.detachEvent("on" + t, f), !0
                        }
                } : void 0, ot = [], ki = function (t) {
                    for (var o, s, r, f = t.clientX, e = t.clientY, v = i.doc.documentElement.scrollTop || i.doc.body.scrollTop, y = i.doc.documentElement.scrollLeft || i.doc.body.scrollLeft, l = ot.length; l--;) {
                        if (r = ot[l], kt && t.touches) {
                            for (s = t.touches.length; s--;)
                                if ((o = t.touches[s]).identifier == r.el._drag.id) {
                                    f = o.clientX;
                                    e = o.clientY;
                                    (t.originalEvent ? t.originalEvent : t).preventDefault();
                                    break
                                }
                        } else t.preventDefault();
                        var h, u = r.el.node,
                            a = u.nextSibling,
                            c = u.parentNode,
                            p = u.style.display;
                        i.win.opera && c.removeChild(u);
                        u.style.display = "none";
                        h = r.el.paper.getElementByPoint(f, e);
                        u.style.display = p;
                        i.win.opera && (a ? c.insertBefore(u, a) : c.appendChild(u));
                        h && n("raphael.drag.over." + r.el.id, r.el, h);
                        f += y;
                        e += v;
                        n("raphael.drag.move." + r.el.id, r.move_scope || r.el, f - r.el._drag.x, e - r.el._drag.y, f, e, t)
                    }
                }, di = function (i) {
                    t.unmousemove(ki).unmouseup(di);
                    for (var r, u = ot.length; u--;)(r = ot[u]).el._drag = {}, n("raphael.drag.end." + r.el.id, r.end_scope || r.start_scope || r.move_scope || r.el, i);
                    ot = []
                }, f = t.el = {}, ru = er.length; ru--;) ! function (n) {
                t[n] = f[n] = function (r, u) {
                    return t.is(r, "function") && (this.events = this.events || [], this.events.push({
                        name: n,
                        f: r,
                        unbind: wf(this.shape || this.node || i.doc, n, r, u || this)
                    })), this
                };
                t["un" + n] = f["un" + n] = function (i) {
                    for (var r = this.events || [], u = r.length; u--;) r[u].name == n && (t.is(i, "undefined") || r[u].f == i) && (r[u].unbind(), r.splice(u, 1), !r.length && delete this.events);
                    return this
                }
            }(er[ru]);
            f.data = function (i, r) {
                var u = lt[this.id] = lt[this.id] || {},
                    f;
                if (0 == arguments.length) return u;
                if (1 == arguments.length) {
                    if (t.is(i, "object")) {
                        for (f in i) i[l](f) && this.data(f, i[f]);
                        return this
                    }
                    return n("raphael.data.get." + this.id, this, u[i], i), u[i]
                }
                return u[i] = r, n("raphael.data.set." + this.id, this, r, i), this
            };
            f.removeData = function (n) {
                return null == n ? delete lt[this.id] : lt[this.id] && delete lt[this.id][n], this
            };
            f.getData = function () {
                return pt(lt[this.id] || {})
            };
            f.hover = function (n, t, i, r) {
                return this.mouseover(n, i).mouseout(t, r || i)
            };
            f.unhover = function (n, t) {
                return this.unmouseover(n).unmouseout(t)
            };
            ht = [];
            f.drag = function (r, u, f, e, o, s) {
                function h(h) {
                    var c, l;
                    (h.originalEvent || h).preventDefault();
                    var a = h.clientX,
                        v = h.clientY,
                        y = i.doc.documentElement.scrollTop || i.doc.body.scrollTop,
                        p = i.doc.documentElement.scrollLeft || i.doc.body.scrollLeft;
                    if (this._drag.id = h.identifier, kt && h.touches)
                        for (l = h.touches.length; l--;)
                            if (c = h.touches[l], this._drag.id = c.identifier, c.identifier == this._drag.id) {
                                a = c.clientX;
                                v = c.clientY;
                                break
                            } this._drag.x = a + p;
                    this._drag.y = v + y;
                    !ot.length && t.mousemove(ki).mouseup(di);
                    ot.push({
                        el: this,
                        move_scope: e,
                        start_scope: o,
                        end_scope: s
                    });
                    u && n.on("raphael.drag.start." + this.id, u);
                    r && n.on("raphael.drag.move." + this.id, r);
                    f && n.on("raphael.drag.end." + this.id, f);
                    n("raphael.drag.start." + this.id, o || e || this, this._drag.x, this._drag.y, h)
                }
                return this._drag = {}, ht.push({
                    el: this,
                    start: h
                }), this.mousedown(h), this
            };
            f.onDragOver = function (t) {
                t ? n.on("raphael.drag.over." + this.id, t) : n.unbind("raphael.drag.over." + this.id)
            };
            f.undrag = function () {
                for (var i = ht.length; i--;) ht[i].el == this && (this.unmousedown(ht[i].start), ht.splice(i, 1), n.unbind("raphael.drag.*." + this.id));
                ht.length || t.unmousemove(ki).unmouseup(di);
                ot = []
            };
            h.circle = function (n, i, r) {
                var u = t._engine.circle(this, n || 0, i || 0, r || 0);
                return this.__set__ && this.__set__.push(u), u
            };
            h.rect = function (n, i, r, u, f) {
                var e = t._engine.rect(this, n || 0, i || 0, r || 0, u || 0, f || 0);
                return this.__set__ && this.__set__.push(e), e
            };
            h.ellipse = function (n, i, r, u) {
                var f = t._engine.ellipse(this, n || 0, i || 0, r || 0, u || 0);
                return this.__set__ && this.__set__.push(f), f
            };
            h.path = function (n) {
                !n || t.is(n, "string") || t.is(n[0], nt) || (n += k);
                var i = t._engine.path(t.format[a](t, arguments), this);
                return this.__set__ && this.__set__.push(i), i
            };
            h.image = function (n, i, r, u, f) {
                var e = t._engine.image(this, n || "about:blank", i || 0, r || 0, u || 0, f || 0);
                return this.__set__ && this.__set__.push(e), e
            };
            h.text = function (n, i, r) {
                var u = t._engine.text(this, n || 0, i || 0, w(r));
                return this.__set__ && this.__set__.push(u), u
            };
            h.set = function (n) {
                t.is(n, "array") || (n = Array.prototype.splice.call(arguments, 0, arguments.length));
                var i = new vt(n);
                return this.__set__ && this.__set__.push(i), i.paper = this, i.type = "set", i
            };
            h.setStart = function (n) {
                this.__set__ = n || this.set()
            };
            h.setFinish = function () {
                var n = this.__set__;
                return delete this.__set__, n
            };
            h.getSize = function () {
                var n = this.canvas.parentNode;
                return {
                    width: n.offsetWidth,
                    height: n.offsetHeight
                }
            };
            h.setSize = function (n, i) {
                return t._engine.setSize.call(this, n, i)
            };
            h.setViewBox = function (n, i, r, u, f) {
                return t._engine.setViewBox.call(this, n, i, r, u, f)
            };
            h.top = h.bottom = null;
            h.raphael = t;
            h.getElementByPoint = function (n, t) {
                var a, h, c, f, e, v, y, o = this.canvas,
                    r = i.doc.elementFromPoint(n, t),
                    l, u, s;
                if (i.win.opera && "svg" == r.tagName && (l = (h = (a = o).getBoundingClientRect(), c = a.ownerDocument, f = c.body, e = c.documentElement, v = e.clientTop || f.clientTop || 0, y = e.clientLeft || f.clientLeft || 0, {
                        y: h.top + (i.win.pageYOffset || e.scrollTop || f.scrollTop) - v,
                        x: h.left + (i.win.pageXOffset || e.scrollLeft || f.scrollLeft) - y
                    }), u = o.createSVGRect(), u.x = n - l.x, u.y = t - l.y, u.width = u.height = 1, s = o.getIntersectionList(u, null), s.length && (r = s[s.length - 1])), !r) return null;
                for (; r.parentNode && r != o.parentNode && !r.raphael;) r = r.parentNode;
                return r == this.canvas.parentNode && (r = o), r = r && r.raphael ? this.getById(r.raphaelid) : null
            };
            h.getElementsByBBox = function (n) {
                var i = this.set();
                return this.forEach(function (r) {
                    t.isBBoxIntersect(r.getBBox(), n) && i.push(r)
                }), i
            };
            h.getById = function (n) {
                for (var t = this.bottom; t;) {
                    if (t.id == n) return t;
                    t = t.next
                }
                return null
            };
            h.forEach = function (n, t) {
                for (var i = this.bottom; i;) {
                    if (!1 === n.call(t, i)) return this;
                    i = i.next
                }
                return this
            };
            h.getElementsByPoint = function (n, t) {
                var i = this.set();
                return this.forEach(function (r) {
                    r.isPointInside(n, t) && i.push(r)
                }), i
            };
            f.isPointInside = function (n, i) {
                var r = this.realPath = ti[this.type](this);
                return this.attr("transform") && this.attr("transform").length && (r = t.transformPath(r, this.attr("transform"))), t.isPointInsidePath(r, n, i)
            };
            f.getBBox = function (n) {
                if (this.removed) return {};
                var t = this._;
                return n ? (!t.dirty && t.bboxwt || (this.realPath = ti[this.type](this), t.bboxwt = bi(this.realPath), t.bboxwt.toString = uu, t.dirty = 0), t.bboxwt) : ((t.dirty || t.dirtyT || !t.bbox) && (!t.dirty && this.realPath || (t.bboxwt = 0, this.realPath = ti[this.type](this)), t.bbox = bi(yi(this.realPath, this.matrix)), t.bbox.toString = uu, t.dirty = t.dirtyT = 0), t.bbox)
            };
            f.clone = function () {
                if (this.removed) return null;
                var n = this.paper[this.type]().attr(this.attr());
                return this.__set__ && this.__set__.push(n), n
            };
            f.glow = function (n) {
                var r;
                if ("text" == this.type) return null;
                var t = {
                        width: ((n = n || {}).width || 10) + (+this.attr("stroke-width") || 1),
                        fill: n.fill || !1,
                        opacity: null == n.opacity ? .5 : n.opacity,
                        offsetx: n.offsetx || 0,
                        offsety: n.offsety || 0,
                        color: n.color || "#000"
                    },
                    u = t.width / 2,
                    f = this.paper,
                    e = f.set(),
                    i = this.realPath || ti[this.type](this);
                for (i = this.matrix ? yi(i, this.matrix) : i, r = 1; r < u + 1; r++) e.push(f.path(i).attr({
                    stroke: t.color,
                    fill: t.fill ? t.color : "none",
                    "stroke-linejoin": "round",
                    "stroke-linecap": "round",
                    "stroke-width": +(t.width / u * r).toFixed(3),
                    opacity: +(t.opacity / u).toFixed(3)
                }));
                return e.insertBefore(this).translate(t.offsetx, t.offsety)
            };
            var gi = function (n, i, r, u, f, e, o, s, h) {
                    return null == h ? at(n, i, r, u, f, e, o, s) : t.findDotsAtSegment(n, i, r, u, f, e, o, s, function (n, t, i, r, u, f, e, o, s) {
                        if (!(s < 0 || at(n, t, i, r, u, f, e, o) < s)) {
                            for (var l = .5, c = 1 - l, h = at(n, t, i, r, u, f, e, o, c); v(h - s) > .01;) h = at(n, t, i, r, u, f, e, o, c += (h < s ? 1 : -1) * (l /= 2));
                            return c
                        }
                    }(n, i, r, u, f, e, o, s, h))
                },
                nr = function (n, i) {
                    return function (r, u, f) {
                        for (var s, h, e, v, o, c = "", a = {}, l = 0, y = 0, p = (r = wt(r)).length; y < p; y++) {
                            if ("M" == (e = r[y])[0]) s = +e[1], h = +e[2];
                            else {
                                if (l + (v = gi(s, h, e[1], e[2], e[3], e[4], e[5], e[6])) > u) {
                                    if (i && !a.start) {
                                        if (c += ["C" + (o = gi(s, h, e[1], e[2], e[3], e[4], e[5], e[6], u - l)).start.x, o.start.y, o.m.x, o.m.y, o.x, o.y], f) return c;
                                        a.start = c;
                                        c = ["M" + o.x, o.y + "C" + o.n.x, o.n.y, o.end.x, o.end.y, e[5], e[6]].join();
                                        l += v;
                                        s = +e[5];
                                        h = +e[6];
                                        continue
                                    }
                                    if (!n && !i) return {
                                        x: (o = gi(s, h, e[1], e[2], e[3], e[4], e[5], e[6], u - l)).x,
                                        y: o.y,
                                        alpha: o.alpha
                                    }
                                }
                                l += v;
                                s = +e[5];
                                h = +e[6]
                            }
                            c += e.shift() + e
                        }
                        return a.end = c, (o = n ? l : i ? a : t.findDotsAtSegment(s, h, e[0], e[1], e[2], e[3], e[4], e[5], 1)).alpha && (o = {
                            x: o.x,
                            y: o.y,
                            alpha: o.alpha
                        }), o
                    }
                },
                fu = nr(1),
                eu = nr(),
                tr = nr(0, 1);
            t.getTotalLength = fu;
            t.getPointAtLength = eu;
            t.getSubpath = function (n, t, i) {
                if (this.getTotalLength(n) - i < 1e-6) return tr(n, t).end;
                var r = tr(n, i, 1);
                return t ? tr(r, t).end : r
            };
            f.getTotalLength = function () {
                var n = this.getPath();
                if (n) return this.node.getTotalLength ? this.node.getTotalLength() : fu(n)
            };
            f.getPointAtLength = function (n) {
                var t = this.getPath();
                if (t) return eu(t, n)
            };
            f.getPath = function () {
                var n, i = t._getPath[this.type];
                if ("text" != this.type && "set" != this.type) return i && (n = i(this)), n
            };
            f.getSubpath = function (n, i) {
                var r = this.getPath();
                if (r) return t.getSubpath(r, n, i)
            };
            d = t.easing_formulas = {
                linear: function (n) {
                    return n
                },
                "<": function (n) {
                    return g(n, 1.7)
                },
                ">": function (n) {
                    return g(n, .48)
                },
                "<>": function (n) {
                    var i = .48 - n / 1.04,
                        u = r.sqrt(.1734 + i * i),
                        f = u - i,
                        e = -u - i,
                        t = g(v(f), 1 / 3) * (f < 0 ? -1 : 1) + g(v(e), 1 / 3) * (e < 0 ? -1 : 1) + .5;
                    return 3 * (1 - t) * t * t + t * t * t
                },
                backIn: function (n) {
                    var t = 1.70158;
                    return n * n * ((t + 1) * n - t)
                },
                backOut: function (n) {
                    var t = 1.70158;
                    return (n -= 1) * n * ((t + 1) * n + t) + 1
                },
                elastic: function (n) {
                    return n == !!n ? n : g(2, -10 * n) * r.sin(2 * b * (n - .075) / .3) + 1
                },
                bounce: function (n) {
                    var i = 7.5625,
                        t = 2.75;
                    return n < 1 / t ? i * n * n : n < 2 / t ? i * (n -= 1.5 / t) * n + .75 : n < 2.5 / t ? i * (n -= 2.25 / t) * n + .9375 : i * (n -= 2.625 / t) * n + .984375
                }
            };
            d.easeIn = d["ease-in"] = d["<"];
            d.easeOut = d["ease-out"] = d[">"];
            d.easeInOut = d["ease-in-out"] = d["<>"];
            d["back-in"] = d.backIn;
            d["back-out"] = d.backOut;
            var u = [],
                ou = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || function (n) {
                    setTimeout(n, 16)
                },
                ir = function () {
                    for (var i, v, r, f, g, c, nt, p, ft, it = +new Date, w = 0; w < u.length; w++)
                        if (i = u[w], !i.el.removed && !i.paused) {
                            var e, d, k = it - i.start,
                                h = i.ms,
                                et = i.easing,
                                s = i.from,
                                a = i.diff,
                                tt = i.to,
                                b = (i.t, i.el),
                                rt = {},
                                ut = {};
                            if (i.initstatus ? (k = (i.initstatus * i.anim.top - i.prev) / (i.percent - i.prev) * h, i.status = i.initstatus, delete i.initstatus, i.stop && u.splice(w--, 1)) : i.status = (i.prev + (i.percent - i.prev) * (k / h)) / i.anim.top, !(k < 0))
                                if (k < h) {
                                    v = et(k / h);
                                    for (r in s)
                                        if (s[l](r)) {
                                            switch (ai[r]) {
                                                case y:
                                                    e = +s[r] + v * h * a[r];
                                                    break;
                                                case "colour":
                                                    e = "rgb(" + [rr(li(s[r].r + v * h * a[r].r)), rr(li(s[r].g + v * h * a[r].g)), rr(li(s[r].b + v * h * a[r].b))].join(",") + ")";
                                                    break;
                                                case "path":
                                                    for (e = [], f = 0, g = s[r].length; f < g; f++) {
                                                        for (e[f] = [s[r][f][0]], c = 1, nt = s[r][f].length; c < nt; c++) e[f][c] = +s[r][f][c] + v * h * a[r][f][c];
                                                        e[f] = e[f].join(yt)
                                                    }
                                                    e = e.join(yt);
                                                    break;
                                                case "transform":
                                                    if (a[r].real)
                                                        for (e = [], f = 0, g = s[r].length; f < g; f++)
                                                            for (e[f] = [s[r][f][0]], c = 1, nt = s[r][f].length; c < nt; c++) e[f][c] = s[r][f][c] + v * h * a[r][f][c];
                                                    else p = function (n) {
                                                        return +s[r][n] + v * h * a[r][n]
                                                    }, e = [
                                                        ["m", p(0), p(1), p(2), p(3), p(4), p(5)]
                                                    ];
                                                    break;
                                                case "csv":
                                                    if ("clip-rect" == r)
                                                        for (e = [], f = 4; f--;) e[f] = +s[r][f] + v * h * a[r][f];
                                                    break;
                                                default:
                                                    for (ft = [][o](s[r]), e = [], f = b.paper.customAttributes[r].length; f--;) e[f] = +ft[f] + v * h * a[r][f]
                                            }
                                            rt[r] = e
                                        } b.attr(rt),
                                        function (t, i, r) {
                                            setTimeout(function () {
                                                n("raphael.anim.frame." + t, i, r)
                                            })
                                        }(b.id, b, i.anim)
                                } else {
                                    if (function (i, r, u) {
                                            setTimeout(function () {
                                                n("raphael.anim.frame." + r.id, r, u);
                                                n("raphael.anim.finish." + r.id, r, u);
                                                t.is(i, "function") && i.call(r)
                                            })
                                        }(i.callback, b, i.anim), b.attr(tt), u.splice(w--, 1), i.repeat > 1 && !i.next) {
                                        for (d in tt) tt[l](d) && (ut[d] = i.totalOrigin[d]);
                                        i.el.attr(ut);
                                        bt(i.anim, i.el, i.anim.percents[0], null, i.totalOrigin, i.repeat - 1)
                                    }
                                    i.next && !i.stop && bt(i.anim, i.el, i.next, null, i.totalOrigin, i.repeat)
                                }
                        } u.length && ou(ir)
                },
                rr = function (n) {
                    return n > 255 ? 255 : n < 0 ? 0 : n
                };
            f.animateWith = function (n, i, r, f, e, o) {
                var h, s, c;
                if (this.removed) return o && o.call(this), this;
                for (h = r instanceof ut ? r : t.animation(r, f, e, o), bt(h, this, h.percents[0], null, this.attr()), s = 0, c = u.length; s < c; s++)
                    if (u[s].anim == i && u[s].el == n) {
                        u[c - 1].start = u[s].start;
                        break
                    } return this
            };
            f.onAnimation = function (t) {
                return t ? n.on("raphael.anim.frame." + this.id, t) : n.unbind("raphael.anim.frame." + this.id), this
            };
            ut.prototype.delay = function (n) {
                var t = new ut(this.anim, this.ms);
                return t.times = this.times, t.del = +n || 0, t
            };
            ut.prototype.repeat = function (n) {
                var t = new ut(this.anim, this.ms);
                return t.del = this.del, t.times = r.floor(e(n, 0)) || 1, t
            };
            t.animation = function (n, i, r, u) {
                var a, f, e, o, h, c;
                if (n instanceof ut) return n;
                !t.is(r, "function") && r || (u = u || r || null, r = null);
                n = Object(n);
                i = +i || 0;
                e = {};
                for (f in n) n[l](f) && s(f) != f && s(f) + "%" != f && (a = !0, e[f] = n[f]);
                if (a) return r && (e.easing = r), u && (e.callback = u), new ut({
                    100: e
                }, i);
                if (u) {
                    o = 0;
                    for (h in n) c = ft(h), n[l](h) && c > o && (o = c);
                    n[o += "%"].callback || (n[o].callback = u)
                }
                return new ut(n, i)
            };
            f.animate = function (n, i, r, u) {
                if (this.removed) return u && u.call(this), this;
                var f = n instanceof ut ? n : t.animation(n, i, r, u);
                return bt(f, this, f.percents[0], null, this.attr()), this
            };
            f.setTime = function (n, t) {
                return n && null != t && this.status(n, c(t, n.ms) / n.ms), this
            };
            f.status = function (n, t) {
                var f, i, e = [],
                    r = 0;
                if (null != t) return bt(n, this, -1, c(t, 1)), this;
                for (f = u.length; r < f; r++)
                    if ((i = u[r]).el.id == this.id && (!n || i.anim == n)) {
                        if (n) return i.status;
                        e.push({
                            anim: i.anim,
                            status: i.status
                        })
                    } return n ? 0 : e
            };
            f.pause = function (t) {
                for (var i = 0; i < u.length; i++) u[i].el.id != this.id || t && u[i].anim != t || !1 !== n("raphael.anim.pause." + this.id, this, u[i].anim) && (u[i].paused = !0);
                return this
            };
            f.resume = function (t) {
                for (var r, i = 0; i < u.length; i++) u[i].el.id != this.id || t && u[i].anim != t || (r = u[i], !1 !== n("raphael.anim.resume." + this.id, this, r.anim) && (delete r.paused, this.status(r.anim, r.status)));
                return this
            };
            f.stop = function (t) {
                for (var i = 0; i < u.length; i++) u[i].el.id != this.id || t && u[i].anim != t || !1 !== n("raphael.anim.stop." + this.id, this, u[i].anim) && u.splice(i--, 1);
                return this
            };
            n.on("raphael.remove", su);
            n.on("raphael.clear", su);
            f.toString = function () {
                return "Raphaël’s object"
            };
            vt = function (n) {
                if (this.items = [], this.length = 0, this.type = "set", n)
                    for (var t = 0, i = n.length; t < i; t++) n[t] && (n[t].constructor == f.constructor || n[t].constructor == vt) && (this[this.items.length] = this.items[this.items.length] = n[t], this.length++)
            };
            p = vt.prototype;
            for (si in p.push = function () {
                    for (var n, i, t = 0, r = arguments.length; t < r; t++)(n = arguments[t]) && (n.constructor == f.constructor || n.constructor == vt) && (this[i = this.items.length] = this.items[i] = n, this.length++);
                    return this
                }, p.pop = function () {
                    return this.length && delete this[this.length--], this.items.pop()
                }, p.forEach = function (n, t) {
                    for (var i = 0, r = this.items.length; i < r; i++)
                        if (!1 === n.call(t, this.items[i], i)) return this;
                    return this
                }, f) f[l](si) && (p[si] = function (n) {
                return function () {
                    var t = arguments;
                    return this.forEach(function (i) {
                        i[n][a](i, t)
                    })
                }
            }(si));
            return p.attr = function (n, i) {
                    var r, f, u, e;
                    if (n && t.is(n, nt) && t.is(n[0], "object"))
                        for (r = 0, f = n.length; r < f; r++) this.items[r].attr(n[r]);
                    else
                        for (u = 0, e = this.items.length; u < e; u++) this.items[u].attr(n, i);
                    return this
                }, p.clear = function () {
                    for (; this.length;) this.pop()
                }, p.splice = function (n, t) {
                    var r;
                    n = n < 0 ? e(this.length + n, 0) : n;
                    t = e(0, c(this.length - n, t));
                    for (var u = [], o = [], f = [], i = 2; i < arguments.length; i++) f.push(arguments[i]);
                    for (i = 0; i < t; i++) o.push(this[n + i]);
                    for (; i < this.length - n; i++) u.push(this[n + i]);
                    for (r = f.length, i = 0; i < r + u.length; i++) this.items[n + i] = this[n + i] = i < r ? f[i] : u[i - r];
                    for (i = this.items.length = this.length -= t - r; this[i];) delete this[i++];
                    return new vt(o)
                }, p.exclude = function (n) {
                    for (var t = 0, i = this.length; t < i; t++)
                        if (this[t] == n) return this.splice(t, 1), !0
                }, p.animate = function (n, i, r, u) {
                    var o;
                    (t.is(r, "function") || !r) && (u = r || null);
                    var h, s, e = this.items.length,
                        f = e,
                        c = this;
                    if (!e) return this;
                    for (u && (s = function () {
                            --e || u.call(c)
                        }), r = t.is(r, "string") ? r : s, o = t.animation(n, i, r, s), h = this.items[--f].animate(o); f--;) this.items[f] && !this.items[f].removed && this.items[f].animateWith(h, o, o), this.items[f] && !this.items[f].removed || e--;
                    return this
                }, p.insertAfter = function (n) {
                    for (var t = this.items.length; t--;) this.items[t].insertAfter(n);
                    return this
                }, p.getBBox = function () {
                    for (var n, t = [], i = [], r = [], u = [], f = this.items.length; f--;) this.items[f].removed || (n = this.items[f].getBBox(), t.push(n.x), i.push(n.y), r.push(n.x + n.width), u.push(n.y + n.height));
                    return {
                        x: t = c[a](0, t),
                        y: i = c[a](0, i),
                        x2: r = e[a](0, r),
                        y2: u = e[a](0, u),
                        width: r - t,
                        height: u - i
                    }
                }, p.clone = function (n) {
                    n = this.paper.set();
                    for (var t = 0, i = this.items.length; t < i; t++) n.push(this.items[t].clone());
                    return n
                }, p.toString = function () {
                    return "Raphaël‘s set"
                }, p.glow = function (n) {
                    var t = this.paper.set();
                    return this.forEach(function (i) {
                        var r = i.glow(n);
                        null != r && r.forEach(function (n) {
                            t.push(n)
                        })
                    }), t
                }, p.isPointInside = function (n, t) {
                    var i = !1;
                    return this.forEach(function (r) {
                        if (r.isPointInside(n, t)) return i = !0, !1
                    }), i
                }, t.registerFont = function (n) {
                    var i, u, f, r, t, e;
                    if (!n.face) return n;
                    this.fonts = this.fonts || {};
                    i = {
                        w: n.w,
                        face: {},
                        glyphs: {}
                    };
                    u = n.face["font-family"];
                    for (f in n.face) n.face[l](f) && (i.face[f] = n.face[f]);
                    if (this.fonts[u] ? this.fonts[u].push(i) : this.fonts[u] = [i], !n.svg)
                        for (r in i.face["units-per-em"] = ft(n.face["units-per-em"], 10), n.glyphs)
                            if (n.glyphs[l](r) && (t = n.glyphs[r], i.glyphs[r] = {
                                    w: t.w,
                                    k: {},
                                    d: t.d && "M" + t.d.replace(/[mlcxtrv]/g, function (n) {
                                        return {
                                            l: "L",
                                            c: "C",
                                            x: "z",
                                            t: "m",
                                            r: "l",
                                            v: "c"
                                        } [n] || "M"
                                    }) + "z"
                                }, t.k))
                                for (e in t.k) t[l](e) && (i.glyphs[r].k[e] = t.k[e]);
                    return n
                }, h.getFont = function (n, i, r, u) {
                    var e, f, h, o, s, c;
                    if (u = u || "normal", r = r || "normal", i = +i || {
                            normal: 400,
                            bold: 700,
                            lighter: 300,
                            bolder: 800
                        } [i] || 400, t.fonts) {
                        if (f = t.fonts[n], !f) {
                            h = new RegExp("(^|\\s)" + n.replace(/[^\w\d\s+!~.:_-]/g, k) + "(\\s|$)", "i");
                            for (o in t.fonts)
                                if (t.fonts[l](o) && h.test(o)) {
                                    f = t.fonts[o];
                                    break
                                }
                        }
                        if (f)
                            for (s = 0, c = f.length; s < c && ((e = f[s]).face["font-weight"] != i || e.face["font-style"] != r && e.face["font-style"] || e.face["font-stretch"] != u); s++);
                        return e
                    }
                }, h.print = function (n, i, r, u, f, o, s, h) {
                    var d, y;
                    o = o || "middle";
                    s = e(c(s || 0, 1), -1);
                    h = e(c(h || 1, 3), 1);
                    var l, v = w(r)[rt](k),
                        g = 0,
                        p = 0,
                        tt = k;
                    if (t.is(u, "string") && (u = this.getFont(u)), u) {
                        l = (f || 16) / u.face["units-per-em"];
                        for (var b = u.face.bbox[rt](ci), it = +b[0], nt = b[3] - b[1], ut = 0, ft = +b[1] + ("baseline" == o ? nt + +u.face.descent : nt / 2), a = 0, et = v.length; a < et; a++) "\n" == v[a] ? (g = 0, y = 0, p = 0, ut += nt * h) : (d = p && u.glyphs[v[a - 1]] || {}, y = u.glyphs[v[a]], g += p ? (d.w || u.w) + (d.k && d.k[v[a]] || 0) + u.w * s : 0, p = 1), y && y.d && (tt += t.transformPath(y.d, ["t", g * l, ut * l, "s", l, l, it, ft, "t", (n - it) / l, (i - ft) / l]))
                    }
                    return this.path(tt).attr({
                        fill: "#000",
                        stroke: "none"
                    })
                }, h.add = function (n) {
                    if (t.is(n, "array"))
                        for (var i, u = this.set(), r = 0, f = n.length; r < f; r++) i = n[r] || {}, au[l](i.type) && u.push(this[i.type]().attr(i));
                    return u
                }, t.format = function (n, i) {
                    var r = t.is(i, nt) ? [0][o](i) : arguments;
                    return n && t.is(n, "string") && r.length - 1 && (n = n.replace(vu, function (n, t) {
                        return null == r[++t] ? k : r[t]
                    })), n || k
                }, t.fullfill = (hu = /\{([^\}]+)\}/g, cu = /(?:(?:^|\.)(.+?)(?=\[|\.|$|\()|\[('|")(.+?)\2\])(\(\))?/g, function (n, t) {
                    return String(n).replace(hu, function (n, i) {
                        return function (n, t, i) {
                            var r = i;
                            return t.replace(cu, function (n, t, i, u, f) {
                                t = t || u;
                                r && (t in r && (r = r[t]), "function" == typeof r && f && (r = r()))
                            }), r = (null == r || r == i ? n : r) + ""
                        }(n, i, t)
                    })
                }), t.ninja = function () {
                    if (ur.was) i.win.Raphael = ur.is;
                    else {
                        window.Raphael = void 0;
                        try {
                            delete window.Raphael
                        } catch (n) {}
                    }
                    return t
                }, t.st = p, n.on("raphael.DOMload", function () {
                    hi = !0
                }), null == (ct = document).readyState && ct.addEventListener && (ct.addEventListener("DOMContentLoaded", lu = function () {
                    ct.removeEventListener("DOMContentLoaded", lu, !1);
                    ct.readyState = "complete"
                }, !1), ct.readyState = "loading"),
                function n() {
                    /in/.test(ct.readyState) ? setTimeout(n, 9) : t.eve("raphael.DOMload")
                }(), t
        }.apply(t, r)) || (n.exports = u)
    }, function (n, t, i) {
        var r, u;
        r = [i(0), i(3), i(4)];
        void 0 === (u = function (n) {
            return n
        }.apply(t, r)) || (n.exports = u)
    }, function (n, t) {
        var l, u, f, h, e, c, a, o, v, y, p, s, r, i;
        h = "hasOwnProperty";
        e = /[\.\/]/;
        c = /\s*,\s*/;
        a = function (n, t) {
            return n - t
        };
        o = {
            n: {}
        };
        v = function () {
            for (var n = 0, t = this.length; n < t; n++)
                if (void 0 !== this[n]) return this[n]
        };
        y = function () {
            for (var n = this.length; --n;)
                if (void 0 !== this[n]) return this[n]
        };
        p = Object.prototype.toString;
        s = String;
        r = Array.isArray || function (n) {
            return n instanceof Array || "[object Array]" == p.call(n)
        };
        (i = function (n, t) {
            var e, b = f,
                c = Array.prototype.slice.call(arguments, 2),
                s = i.listeners(n),
                l = 0,
                h = [],
                p = {},
                o = [],
                k = u,
                r, w;
            for (o.firstDefined = v, o.lastDefined = y, u = n, f = 0, r = 0, w = s.length; r < w; r++) "zIndex" in s[r] && (h.push(s[r].zIndex), s[r].zIndex < 0 && (p[s[r].zIndex] = s[r]));
            for (h.sort(a); h[l] < 0;)
                if (e = p[h[l++]], o.push(e.apply(t, c)), f) return f = b, o;
            for (r = 0; r < w; r++)
                if ("zIndex" in (e = s[r]))
                    if (e.zIndex == h[l]) {
                        if (o.push(e.apply(t, c)), f) break;
                        do
                            if ((e = p[h[++l]]) && o.push(e.apply(t, c)), f) break; while (e)
                    } else p[e.zIndex] = e;
            else if (o.push(e.apply(t, c)), f) break;
            return f = b, u = k, o
        })._events = o;
        i.listeners = function (n) {
            for (var u, a, f, i, y, s, p = r(n) ? n : n.split(e), h = o, c = [h], l = [], t = 0, v = p.length; t < v; t++) {
                for (s = [], i = 0, y = c.length; i < y; i++)
                    for (a = [(h = c[i].n)[p[t]], h["*"]], f = 2; f--;)(u = a[f]) && (s.push(u), l = l.concat(u.f || []));
                c = s
            }
            return l
        };
        i.separator = function (n) {
            n ? (n = "[" + (n = s(n).replace(/(?=[\.\^\]\[\-])/g, "\\")) + "]", e = new RegExp(n)) : e = /[\.\/]/
        };
        i.on = function (n, t) {
            if ("function" != typeof t) return function () {};
            for (var u = r(n) ? r(n[0]) ? n : [n] : s(n).split(c), i = 0, f = u.length; i < f; i++) ! function (n) {
                for (var c, f = r(n) ? n : s(n).split(e), i = o, u = 0, h = f.length; u < h; u++) i = (i = i.n).hasOwnProperty(f[u]) && i[f[u]] || (i[f[u]] = {
                    n: {}
                });
                for (i.f = i.f || [], u = 0, h = i.f.length; u < h; u++)
                    if (i.f[u] == t) {
                        c = !0;
                        break
                    } c || i.f.push(t)
            }(u[i]);
            return function (n) {
                +n == +n && (t.zIndex = +n)
            }
        };
        i.f = function (n) {
            var t = [].slice.call(arguments, 1);
            return function () {
                i.apply(null, [n, null].concat(t).concat([].slice.call(arguments, 0)))
            }
        };
        i.stop = function () {
            f = 1
        };
        i.nt = function (n) {
            var t = r(u) ? u.join(".") : u;
            return n ? new RegExp("(?:\\.|\\/|^)" + n + "(?:\\.|\\/|$)").test(t) : t
        };
        i.nts = function () {
            return r(u) ? u : u.split(e)
        };
        i.off = i.unbind = function (n, t) {
            var v, l, p, u, a, w, f, k, y, b;
            if (n)
                if (v = r(n) ? r(n[0]) ? n : [n] : s(n).split(c), v.length > 1)
                    for (l = 0, p = v.length; l < p; l++) i.off(v[l], t);
                else {
                    for (v = r(n) ? n : s(n).split(e), y = [o], l = 0, p = v.length; l < p; l++)
                        for (f = 0; f < y.length; f += w.length - 2) {
                            if (w = [f, 1], u = y[f].n, "*" != v[l]) u[v[l]] && w.push(u[v[l]]);
                            else
                                for (a in u) u[h](a) && w.push(u[a]);
                            y.splice.apply(y, w)
                        }
                    for (l = 0, p = y.length; l < p; l++)
                        for (u = y[l]; u.n;) {
                            if (t) {
                                if (u.f) {
                                    for (f = 0, k = u.f.length; f < k; f++)
                                        if (u.f[f] == t) {
                                            u.f.splice(f, 1);
                                            break
                                        } u.f.length || delete u.f
                                }
                                for (a in u.n)
                                    if (u.n[h](a) && u.n[a].f) {
                                        for (b = u.n[a].f, f = 0, k = b.length; f < k; f++)
                                            if (b[f] == t) {
                                                b.splice(f, 1);
                                                break
                                            } b.length || delete u.n[a].f
                                    }
                            } else
                                for (a in delete u.f, u.n) u.n[h](a) && u.n[a].f && delete u.n[a].f;
                            u = u.n
                        }
                }
            else i._events = o = {
                n: {}
            }
        };
        i.once = function (n, t) {
            var r = function () {
                return i.off(n, r), t.apply(this, arguments)
            };
            return i.on(n, r)
        };
        i.version = "0.5.0";
        i.toString = function () {
            return "You are running Eve 0.5.0"
        };
        n.exports ? n.exports = i : void 0 === (l = function () {
            return i
        }.apply(t, [])) || (n.exports = l)
    }, function (n, t, i) {
        var r, u;
        r = [i(0)];
        void 0 === (u = function (n) {
            var tt, y;
            if (!n || n.svg) {
                var i = "hasOwnProperty",
                    u = String,
                    f = parseFloat,
                    it = parseInt,
                    l = Math,
                    d = l.max,
                    p = l.abs,
                    g = l.pow,
                    a = /[, ]+/,
                    w = n.eve,
                    o = "",
                    b = " ",
                    v = "http://www.w3.org/1999/xlink",
                    ft = {
                        block: "M5,0 0,2.5 5,5z",
                        classic: "M5,0 0,2.5 5,5 3.5,3 3.5,2z",
                        diamond: "M2.5,0 5,2.5 2.5,5 0,2.5z",
                        open: "M6,1 1,3.5 6,6",
                        oval: "M2.5,0A2.5,2.5,0,0,1,2.5,5 2.5,2.5,0,0,1,2.5,0z"
                    },
                    e = {};
                n.toString = function () {
                    return "Your browser supports SVG.\nYou are running Raphaël " + this.version
                };
                var t = function (r, f) {
                        if (f)
                            for (var e in "string" == typeof r && (r = t(r)), f) f[i](e) && ("xlink:" == e.substring(0, 6) ? r.setAttributeNS(v, e.substring(6), u(f[e])) : r.setAttribute(e, u(f[e])));
                        else(r = n._g.doc.createElementNS("http://www.w3.org/2000/svg", r)).style && (r.style.webkitTapHighlightColor = "rgba(0,0,0,0)");
                        return r
                    },
                    rt = function (i, r) {
                        var w = "linear",
                            a = i.id + r,
                            b = .5,
                            c = .5,
                            tt = i.node,
                            it = i.paper,
                            k = tt.style,
                            v = n._g.doc.getElementById(a),
                            y, e, nt, h, s, rt;
                        if (!v) {
                            if (r = (r = u(r).replace(n._radial_gradient, function (n, t, i) {
                                    if (w = "radial", t && i) {
                                        b = f(t);
                                        var r = 2 * ((c = f(i)) > .5) - 1;
                                        g(b - .5, 2) + g(c - .5, 2) > .25 && (c = l.sqrt(.25 - g(b - .5, 2)) * r + .5) && .5 != c && (c = c.toFixed(5) - 1e-5 * r)
                                    }
                                    return o
                                })).split(/\s*\-\s*/), "linear" == w) {
                                if (y = r.shift(), y = -f(y), isNaN(y)) return null;
                                e = [0, 0, l.cos(n.rad(y)), l.sin(n.rad(y))];
                                nt = 1 / (d(p(e[2]), p(e[3])) || 1);
                                e[2] *= nt;
                                e[3] *= nt;
                                e[2] < 0 && (e[0] = -e[2], e[2] = 0);
                                e[3] < 0 && (e[1] = -e[3], e[3] = 0)
                            }
                            if (h = n._parseDots(r), !h) return null;
                            if (a = a.replace(/[\(\)\s,\xb0#]/g, "_"), i.gradient && a != i.gradient.id && (it.defs.removeChild(i.gradient), delete i.gradient), !i.gradient)
                                for (v = t(w + "Gradient", {
                                        id: a
                                    }), i.gradient = v, t(v, "radial" == w ? {
                                        fx: b,
                                        fy: c
                                    } : {
                                        x1: e[0],
                                        y1: e[1],
                                        x2: e[2],
                                        y2: e[3],
                                        gradientTransform: i.matrix.invert()
                                    }), it.defs.appendChild(v), s = 0, rt = h.length; s < rt; s++) v.appendChild(t("stop", {
                                    offset: h[s].offset ? h[s].offset : s ? "100%" : "0%",
                                    "stop-color": h[s].color || "#fff",
                                    "stop-opacity": isFinite(h[s].opacity) ? h[s].opacity : 1
                                }))
                        }
                        return t(tt, {
                            fill: et(a),
                            opacity: 1,
                            "fill-opacity": 1
                        }), k.fill = o, k.opacity = 1, k.fillOpacity = 1, 1
                    },
                    et = function (n) {
                        if ((i = document.documentMode) && (9 === i || 10 === i)) return "url('#" + n + "')";
                        var i, t = document.location;
                        return "url('" + (t.protocol + "//" + t.host + t.pathname + t.search) + "#" + n + "')"
                    },
                    k = function (n) {
                        var i = n.getBBox(1);
                        t(n.pattern, {
                            patternTransform: n.matrix.invert() + " translate(" + i.x + "," + i.y + ")"
                        })
                    },
                    s = function (r, f, s) {
                        var b, k, tt, g, it, rt;
                        if ("path" == r.type) {
                            for (var p, w, ut, et, c, ot = u(f).toLowerCase().split("-"), ht = r.paper, h = s ? "end" : "start", ct = r.node, l = r.attrs, d = l["stroke-width"], st = ot.length, a = "classic", v = 3, y = 3, nt = 5; st--;) switch (ot[st]) {
                                case "block":
                                case "classic":
                                case "oval":
                                case "diamond":
                                case "open":
                                case "none":
                                    a = ot[st];
                                    break;
                                case "wide":
                                    y = 5;
                                    break;
                                case "narrow":
                                    y = 2;
                                    break;
                                case "long":
                                    v = 5;
                                    break;
                                case "short":
                                    v = 2
                            }("open" == a ? (v += 2, y += 2, nt += 2, ut = 1, et = s ? 4 : 1, c = {
                                fill: "none",
                                stroke: l.stroke
                            }) : (et = ut = v / 2, c = {
                                fill: l.stroke,
                                stroke: "none"
                            }), r._.arrows ? s ? (r._.arrows.endPath && e[r._.arrows.endPath]--, r._.arrows.endMarker && e[r._.arrows.endMarker]--) : (r._.arrows.startPath && e[r._.arrows.startPath]--, r._.arrows.startMarker && e[r._.arrows.startMarker]--) : r._.arrows = {}, "none" != a) ? (b = "raphael-marker-" + a, k = "raphael-marker-" + h + a + v + y + "-obj" + r.id, n._g.doc.getElementById(b) ? e[b]++ : (ht.defs.appendChild(t(t("path"), {
                                "stroke-linecap": "round",
                                d: ft[a],
                                id: b
                            })), e[b] = 1), g = n._g.doc.getElementById(k), g ? (e[k]++, tt = g.getElementsByTagName("use")[0]) : (g = t(t("marker"), {
                                id: k,
                                markerHeight: y,
                                markerWidth: v,
                                orient: "auto",
                                refX: et,
                                refY: y / 2
                            }), tt = t(t("use"), {
                                "xlink:href": "#" + b,
                                transform: (s ? "rotate(180 " + v / 2 + " " + y / 2 + ") " : o) + "scale(" + v / nt + "," + y / nt + ")",
                                "stroke-width": (2 / (v / nt + y / nt)).toFixed(4)
                            }), g.appendChild(tt), ht.defs.appendChild(g), e[k] = 1), t(tt, c), it = ut * ("diamond" != a && "oval" != a), s ? (p = r._.arrows.startdx * d || 0, w = n.getTotalLength(l.path) - it * d) : (p = it * d, w = n.getTotalLength(l.path) - (r._.arrows.enddx * d || 0)), (c = {})["marker-" + h] = "url(#" + k + ")", (w || p) && (c.d = n.getSubpath(l.path, p, w)), t(ct, c), r._.arrows[h + "Path"] = b, r._.arrows[h + "Marker"] = k, r._.arrows[h + "dx"] = it, r._.arrows[h + "Type"] = a, r._.arrows[h + "String"] = f) : (s ? (p = r._.arrows.startdx * d || 0, w = n.getTotalLength(l.path) - p) : (p = 0, w = n.getTotalLength(l.path) - (r._.arrows.enddx * d || 0)), r._.arrows[h + "Path"] && t(ct, {
                                d: n.getSubpath(l.path, p, w)
                            }), delete r._.arrows[h + "Path"], delete r._.arrows[h + "Marker"], delete r._.arrows[h + "dx"], delete r._.arrows[h + "Type"], delete r._.arrows[h + "String"]);
                            for (c in e) e[i](c) && !e[c] && (rt = n._g.doc.getElementById(c), rt && rt.parentNode.removeChild(rt))
                        }
                    },
                    ot = {
                        "-": [3, 1],
                        ".": [1, 1],
                        "-.": [3, 1, 1, 1],
                        "-..": [3, 1, 1, 1, 1, 1],
                        ". ": [1, 3],
                        "- ": [4, 3],
                        "--": [8, 3],
                        "- .": [4, 3, 1, 3],
                        "--.": [8, 3, 1, 3],
                        "--..": [8, 3, 1, 3, 1, 3]
                    },
                    ut = function (n, i, r) {
                        if (i = ot[u(i).toLowerCase()]) {
                            for (var e = n.attrs["stroke-width"] || "1", s = {
                                    round: e,
                                    square: e,
                                    butt: 0
                                } [n.attrs["stroke-linecap"] || r["stroke-linecap"]] || 0, o = [], f = i.length; f--;) o[f] = i[f] * e + (f % 2 ? 1 : -1) * s;
                            t(n.node, {
                                "stroke-dasharray": o.join(",")
                            })
                        } else t(n.node, {
                            "stroke-dasharray": "none"
                        })
                    },
                    nt = function (r, f) {
                        var h = r.node,
                            c = r.attrs,
                            pt = h.style.visibility,
                            l, e, b, vt, g, et, nt, y, ot, at, ht, ct, lt, w, ft, tt, yt;
                        for (l in h.style.visibility = "hidden", f)
                            if (f[i](l)) {
                                if (!n._availableAttrs[i](l)) continue;
                                e = f[l];
                                switch (c[l] = e, l) {
                                    case "blur":
                                        r.blur(e);
                                        break;
                                    case "title":
                                        b = h.getElementsByTagName("title");
                                        b.length && (b = b[0]) ? b.firstChild.nodeValue = e : (b = t("title"), vt = n._g.doc.createTextNode(e), b.appendChild(vt), h.appendChild(b));
                                        break;
                                    case "href":
                                    case "target":
                                        g = h.parentNode;
                                        "a" != g.tagName.toLowerCase() && (et = t("a"), g.insertBefore(et, h), et.appendChild(h), g = et);
                                        "target" == l ? g.setAttributeNS(v, "show", "blank" == e ? "new" : e) : g.setAttributeNS(v, l, e);
                                        break;
                                    case "cursor":
                                        h.style.cursor = e;
                                        break;
                                    case "transform":
                                        r.transform(e);
                                        break;
                                    case "arrow-start":
                                        s(r, e);
                                        break;
                                    case "arrow-end":
                                        s(r, e, 1);
                                        break;
                                    case "clip-rect":
                                        nt = u(e).split(a);
                                        4 == nt.length && (r.clip && r.clip.parentNode.parentNode.removeChild(r.clip.parentNode), y = t("clipPath"), ot = t("rect"), y.id = n.createUUID(), t(ot, {
                                            x: nt[0],
                                            y: nt[1],
                                            width: nt[2],
                                            height: nt[3]
                                        }), y.appendChild(ot), r.paper.defs.appendChild(y), t(h, {
                                            "clip-path": "url(#" + y.id + ")"
                                        }), r.clip = ot);
                                        e || (at = h.getAttribute("clip-path"), at && (ht = n._g.doc.getElementById(at.replace(/(^url\(#|\)$)/g, o)), ht && ht.parentNode.removeChild(ht), t(h, {
                                            "clip-path": o
                                        }), delete r.clip));
                                        break;
                                    case "path":
                                        "path" == r.type && (t(h, {
                                            d: e ? c.path = n._pathToAbsolute(e) : "M0,0"
                                        }), r._.dirty = 1, r._.arrows && ("startString" in r._.arrows && s(r, r._.arrows.startString), "endString" in r._.arrows && s(r, r._.arrows.endString, 1)));
                                        break;
                                    case "width":
                                        if (h.setAttribute(l, e), r._.dirty = 1, !c.fx) break;
                                        l = "x";
                                        e = c.x;
                                    case "x":
                                        c.fx && (e = -c.x - (c.width || 0));
                                    case "rx":
                                        if ("rx" == l && "rect" == r.type) break;
                                    case "cx":
                                        h.setAttribute(l, e);
                                        r.pattern && k(r);
                                        r._.dirty = 1;
                                        break;
                                    case "height":
                                        if (h.setAttribute(l, e), r._.dirty = 1, !c.fy) break;
                                        l = "y";
                                        e = c.y;
                                    case "y":
                                        c.fy && (e = -c.y - (c.height || 0));
                                    case "ry":
                                        if ("ry" == l && "rect" == r.type) break;
                                    case "cy":
                                        h.setAttribute(l, e);
                                        r.pattern && k(r);
                                        r._.dirty = 1;
                                        break;
                                    case "r":
                                        "rect" == r.type ? t(h, {
                                            rx: e,
                                            ry: e
                                        }) : h.setAttribute(l, e);
                                        r._.dirty = 1;
                                        break;
                                    case "src":
                                        "image" == r.type && h.setAttributeNS(v, "href", e);
                                        break;
                                    case "stroke-width":
                                        1 == r._.sx && 1 == r._.sy || (e /= d(p(r._.sx), p(r._.sy)) || 1);
                                        h.setAttribute(l, e);
                                        c["stroke-dasharray"] && ut(r, c["stroke-dasharray"], f);
                                        r._.arrows && ("startString" in r._.arrows && s(r, r._.arrows.startString), "endString" in r._.arrows && s(r, r._.arrows.endString, 1));
                                        break;
                                    case "stroke-dasharray":
                                        ut(r, e, f);
                                        break;
                                    case "fill":
                                        if (ct = u(e).match(n._ISURL), ct) {
                                            y = t("pattern");
                                            lt = t("image");
                                            y.id = n.createUUID();
                                            t(y, {
                                                x: 0,
                                                y: 0,
                                                patternUnits: "userSpaceOnUse",
                                                height: 1,
                                                width: 1
                                            });
                                            t(lt, {
                                                x: 0,
                                                y: 0,
                                                "xlink:href": ct[1]
                                            });
                                            y.appendChild(lt),
                                                function (i) {
                                                    n._preload(ct[1], function () {
                                                        var n = this.offsetWidth,
                                                            r = this.offsetHeight;
                                                        t(i, {
                                                            width: n,
                                                            height: r
                                                        });
                                                        t(lt, {
                                                            width: n,
                                                            height: r
                                                        })
                                                    })
                                                }(y);
                                            r.paper.defs.appendChild(y);
                                            t(h, {
                                                fill: "url(#" + y.id + ")"
                                            });
                                            r.pattern = y;
                                            r.pattern && k(r);
                                            break
                                        }
                                        if (w = n.getRGB(e), w.error) {
                                            if (("circle" == r.type || "ellipse" == r.type || "r" != u(e).charAt()) && rt(r, e)) {
                                                ("opacity" in c || "fill-opacity" in c) && (ft = n._g.doc.getElementById(h.getAttribute("fill").replace(/^url\(#|\)$/g, o)), ft && (tt = ft.getElementsByTagName("stop"), t(tt[tt.length - 1], {
                                                    "stop-opacity": ("opacity" in c ? c.opacity : 1) * ("fill-opacity" in c ? c["fill-opacity"] : 1)
                                                })));
                                                c.gradient = e;
                                                c.fill = "none";
                                                break
                                            }
                                        } else delete f.gradient, delete c.gradient, !n.is(c.opacity, "undefined") && n.is(f.opacity, "undefined") && t(h, {
                                            opacity: c.opacity
                                        }), !n.is(c["fill-opacity"], "undefined") && n.is(f["fill-opacity"], "undefined") && t(h, {
                                            "fill-opacity": c["fill-opacity"]
                                        });
                                        w[i]("opacity") && t(h, {
                                            "fill-opacity": w.opacity > 1 ? w.opacity / 100 : w.opacity
                                        });
                                    case "stroke":
                                        w = n.getRGB(e);
                                        h.setAttribute(l, w.hex);
                                        "stroke" == l && w[i]("opacity") && t(h, {
                                            "stroke-opacity": w.opacity > 1 ? w.opacity / 100 : w.opacity
                                        });
                                        "stroke" == l && r._.arrows && ("startString" in r._.arrows && s(r, r._.arrows.startString), "endString" in r._.arrows && s(r, r._.arrows.endString, 1));
                                        break;
                                    case "gradient":
                                        ("circle" == r.type || "ellipse" == r.type || "r" != u(e).charAt()) && rt(r, e);
                                        break;
                                    case "opacity":
                                        c.gradient && !c[i]("stroke-opacity") && t(h, {
                                            "stroke-opacity": e > 1 ? e / 100 : e
                                        });
                                    case "fill-opacity":
                                        if (c.gradient) {
                                            (ft = n._g.doc.getElementById(h.getAttribute("fill").replace(/^url\(#|\)$/g, o))) && (tt = ft.getElementsByTagName("stop"), t(tt[tt.length - 1], {
                                                "stop-opacity": e
                                            }));
                                            break
                                        }
                                        default:
                                            "font-size" == l && (e = it(e, 10) + "px");
                                            yt = l.replace(/(\-.)/g, function (n) {
                                                return n.substring(1).toUpperCase()
                                            });
                                            h.style[yt] = e;
                                            r._.dirty = 1;
                                            h.setAttribute(l, e)
                                }
                            } st(r, f);
                        h.style.visibility = pt
                    },
                    st = function (r, f) {
                        var y, a;
                        if ("text" == r.type && (f[i]("text") || f[i]("font") || f[i]("font-size") || f[i]("x") || f[i]("y"))) {
                            var h = r.attrs,
                                s = r.node,
                                p = s.firstChild ? it(n._g.doc.defaultView.getComputedStyle(s.firstChild, o).getPropertyValue("font-size"), 10) : 10;
                            if (f[i]("text")) {
                                for (h.text = f.text; s.firstChild;) s.removeChild(s.firstChild);
                                for (var c, w = u(f.text).split("\n"), l = [], e = 0, v = w.length; e < v; e++) c = t("tspan"), e && t(c, {
                                    dy: 1.2 * p,
                                    x: h.x
                                }), c.appendChild(n._g.doc.createTextNode(w[e])), s.appendChild(c), l[e] = c
                            } else
                                for (e = 0, v = (l = s.getElementsByTagName("tspan")).length; e < v; e++) e ? t(l[e], {
                                    dy: 1.2 * p,
                                    x: h.x
                                }) : t(l[0], {
                                    dy: 0
                                });
                            t(s, {
                                x: h.x,
                                y: h.y
                            });
                            r._.dirty = 1;
                            y = r._getBBox();
                            a = h.y - (y.y + y.height / 2);
                            a && n.is(a, "finite") && t(l[0], {
                                dy: a
                            })
                        }
                    },
                    c = function (n) {
                        return n.parentNode && "a" === n.parentNode.tagName.toLowerCase() ? n.parentNode : n
                    },
                    h = function (t, i) {
                        this[0] = this.node = t;
                        t.raphael = !0;
                        this.id = ("0000" + (Math.random() * Math.pow(36, 5) << 0).toString(36)).slice(-5);
                        t.raphaelid = this.id;
                        this.matrix = n.matrix();
                        this.realPath = null;
                        this.paper = i;
                        this.attrs = this.attrs || {};
                        this._ = {
                            transform: [],
                            sx: 1,
                            sy: 1,
                            deg: 0,
                            dx: 0,
                            dy: 0,
                            dirty: 1
                        };
                        !i.bottom && (i.bottom = this);
                        this.prev = i.top;
                        i.top && (i.top.next = this);
                        i.top = this;
                        this.next = null
                    },
                    r = n.el;
                h.prototype = r;
                r.constructor = h;
                n._engine.path = function (n, i) {
                    var u = t("path"),
                        r;
                    return i.canvas && i.canvas.appendChild(u), r = new h(u, i), r.type = "path", nt(r, {
                        fill: "none",
                        stroke: "#000",
                        path: n
                    }), r
                };
                r.rotate = function (n, t, i) {
                    if (this.removed) return this;
                    if ((n = u(n).split(a)).length - 1 && (t = f(n[1]), i = f(n[2])), n = f(n[0]), null == i && (t = i), null == t || null == i) {
                        var r = this.getBBox(1);
                        t = r.x + r.width / 2;
                        i = r.y + r.height / 2
                    }
                    return this.transform(this._.transform.concat([
                        ["r", n, t, i]
                    ])), this
                };
                r.scale = function (n, t, i, r) {
                    if (this.removed) return this;
                    if ((n = u(n).split(a)).length - 1 && (t = f(n[1]), i = f(n[2]), r = f(n[3])), n = f(n[0]), null == t && (t = n), null == r && (i = r), null == i || null == r) var e = this.getBBox(1);
                    return i = null == i ? e.x + e.width / 2 : i, r = null == r ? e.y + e.height / 2 : r, this.transform(this._.transform.concat([
                        ["s", n, t, i, r]
                    ])), this
                };
                r.translate = function (n, t) {
                    return this.removed ? this : ((n = u(n).split(a)).length - 1 && (t = f(n[1])), n = f(n[0]) || 0, t = +t || 0, this.transform(this._.transform.concat([
                        ["t", n, t]
                    ])), this)
                };
                r.transform = function (r) {
                    var u = this._,
                        f;
                    return null == r ? u.transform : ((n._extractTransform(this, r), this.clip && t(this.clip, {
                        transform: this.matrix.invert()
                    }), this.pattern && k(this), this.node && t(this.node, {
                        transform: this.matrix
                    }), 1 != u.sx || 1 != u.sy) && (f = this.attrs[i]("stroke-width") ? this.attrs["stroke-width"] : 1, this.attr({
                        "stroke-width": f
                    })), this)
                };
                r.hide = function () {
                    return this.removed || (this.node.style.display = "none"), this
                };
                r.show = function () {
                    return this.removed || (this.node.style.display = ""), this
                };
                r.remove = function () {
                    var r = c(this.node),
                        t, i;
                    if (!this.removed && r.parentNode) {
                        t = this.paper;
                        for (i in t.__set__ && t.__set__.exclude(this), w.unbind("raphael.*.*." + this.id), this.gradient && t.defs.removeChild(this.gradient), n._tear(this, t), r.parentNode.removeChild(r), this.removeData(), this) this[i] = "function" == typeof this[i] ? n._removedFactory(i) : null;
                        this.removed = !0
                    }
                };
                r._getBBox = function () {
                    var r, n, i, t;
                    "none" == this.node.style.display && (this.show(), r = !0);
                    i = !1;
                    this.paper.canvas.parentElement ? n = this.paper.canvas.parentElement.style : this.paper.canvas.parentNode && (n = this.paper.canvas.parentNode.style);
                    n && "none" == n.display && (i = !0, n.display = "");
                    t = {};
                    try {
                        t = this.node.getBBox()
                    } catch (r) {
                        t = {
                            x: this.node.clientLeft,
                            y: this.node.clientTop,
                            width: this.node.clientWidth,
                            height: this.node.clientHeight
                        }
                    } finally {
                        t = t || {};
                        i && (n.display = "none")
                    }
                    return r && this.hide(), t
                };
                r.attr = function (t, r) {
                    var e, h, f, u, l, v;
                    if (this.removed) return this;
                    if (null == t) {
                        e = {};
                        for (h in this.attrs) this.attrs[i](h) && (e[h] = this.attrs[h]);
                        return e.gradient && "none" == e.fill && (e.fill = e.gradient) && delete e.gradient, e.transform = this._.transform, e
                    }
                    if (null == r && n.is(t, "string")) {
                        if ("fill" == t && "none" == this.attrs.fill && this.attrs.gradient) return this.attrs.gradient;
                        if ("transform" == t) return this._.transform;
                        for (var y = t.split(a), s = {}, o = 0, c = y.length; o < c; o++) s[t] = (t = y[o]) in this.attrs ? this.attrs[t] : n.is(this.paper.customAttributes[t], "function") ? this.paper.customAttributes[t].def : n._availableAttrs[t];
                        return c - 1 ? s : s[y[0]]
                    }
                    if (null == r && n.is(t, "array")) {
                        for (s = {}, o = 0, c = t.length; o < c; o++) s[t[o]] = this.attr(t[o]);
                        return s
                    }
                    null != r ? (f = {}, f[t] = r) : null != t && n.is(t, "object") && (f = t);
                    for (u in f) w("raphael.attr." + u + "." + this.id, this, f[u]);
                    for (u in this.paper.customAttributes)
                        if (this.paper.customAttributes[i](u) && f[i](u) && n.is(this.paper.customAttributes[u], "function")) {
                            l = this.paper.customAttributes[u].apply(this, [].concat(f[u]));
                            for (v in this.attrs[u] = f[u], l) l[i](v) && (f[v] = l[v])
                        } return nt(this, f), this
                };
                r.toFront = function () {
                    var t, i;
                    return this.removed ? this : (t = c(this.node), t.parentNode.appendChild(t), i = this.paper, i.top != this && n._tofront(this, i), this)
                };
                r.toBack = function () {
                    if (this.removed) return this;
                    var t = c(this.node),
                        i = t.parentNode;
                    return i.insertBefore(t, i.firstChild), n._toback(this, this.paper), this.paper, this
                };
                r.insertAfter = function (t) {
                    if (this.removed || !t) return this;
                    var r = c(this.node),
                        i = c(t.node || t[t.length - 1].node);
                    return i.nextSibling ? i.parentNode.insertBefore(r, i.nextSibling) : i.parentNode.appendChild(r), n._insertafter(this, t, this.paper), this
                };
                r.insertBefore = function (t) {
                    if (this.removed || !t) return this;
                    var r = c(this.node),
                        i = c(t.node || t[0].node);
                    return i.parentNode.insertBefore(r, i), n._insertbefore(this, t, this.paper), this
                };
                r.blur = function (i) {
                    var r = this,
                        u, f;
                    return 0 != +i ? (u = t("filter"), f = t("feGaussianBlur"), r.attrs.blur = i, u.id = n.createUUID(), t(f, {
                        stdDeviation: +i || 1.5
                    }), u.appendChild(f), r.paper.defs.appendChild(u), r._blur = u, t(r.node, {
                        filter: "url(#" + u.id + ")"
                    })) : (r._blur && (r._blur.parentNode.removeChild(r._blur), delete r._blur, delete r.attrs.blur), r.node.removeAttribute("filter")), r
                };
                n._engine.circle = function (n, i, r, u) {
                    var e = t("circle"),
                        f;
                    return n.canvas && n.canvas.appendChild(e), f = new h(e, n), f.attrs = {
                        cx: i,
                        cy: r,
                        r: u,
                        fill: "none",
                        stroke: "#000"
                    }, f.type = "circle", t(e, f.attrs), f
                };
                n._engine.rect = function (n, i, r, u, f, e) {
                    var s = t("rect"),
                        o;
                    return n.canvas && n.canvas.appendChild(s), o = new h(s, n), o.attrs = {
                        x: i,
                        y: r,
                        width: u,
                        height: f,
                        rx: e || 0,
                        ry: e || 0,
                        fill: "none",
                        stroke: "#000"
                    }, o.type = "rect", t(s, o.attrs), o
                };
                n._engine.ellipse = function (n, i, r, u, f) {
                    var o = t("ellipse"),
                        e;
                    return n.canvas && n.canvas.appendChild(o), e = new h(o, n), e.attrs = {
                        cx: i,
                        cy: r,
                        rx: u,
                        ry: f,
                        fill: "none",
                        stroke: "#000"
                    }, e.type = "ellipse", t(o, e.attrs), e
                };
                n._engine.image = function (n, i, r, u, f, e) {
                    var o = t("image"),
                        s;
                    return t(o, {
                        x: r,
                        y: u,
                        width: f,
                        height: e,
                        preserveAspectRatio: "none"
                    }), o.setAttributeNS(v, "href", i), n.canvas && n.canvas.appendChild(o), s = new h(o, n), s.attrs = {
                        x: r,
                        y: u,
                        width: f,
                        height: e,
                        src: i
                    }, s.type = "image", s
                };
                n._engine.text = function (i, r, u, f) {
                    var o = t("text"),
                        e;
                    return i.canvas && i.canvas.appendChild(o), e = new h(o, i), e.attrs = {
                        x: r,
                        y: u,
                        "text-anchor": "middle",
                        text: f,
                        "font-family": n._availableAttrs["font-family"],
                        "font-size": n._availableAttrs["font-size"],
                        stroke: "none",
                        fill: "#000"
                    }, e.type = "text", nt(e, e.attrs), e
                };
                n._engine.setSize = function (n, t) {
                    return this.width = n || this.width, this.height = t || this.height, this.canvas.setAttribute("width", this.width), this.canvas.setAttribute("height", this.height), this._viewBox && this.setViewBox.apply(this, this._viewBox), this
                };
                n._engine.create = function () {
                    var u = n._getContainer.apply(0, arguments),
                        i = u && u.container;
                    if (!i) throw new Error("SVG container not found.");
                    var h, f = u.x,
                        e = u.y,
                        o = u.width,
                        s = u.height,
                        r = t("svg"),
                        c = "overflow:hidden;";
                    return f = f || 0, e = e || 0, t(r, {
                        height: s = s || 342,
                        version: 1.1,
                        width: o = o || 512,
                        xmlns: "http://www.w3.org/2000/svg",
                        "xmlns:xlink": "http://www.w3.org/1999/xlink"
                    }), 1 == i ? (r.style.cssText = c + "position:absolute;left:" + f + "px;top:" + e + "px", n._g.doc.body.appendChild(r), h = 1) : (r.style.cssText = c + "position:relative", i.firstChild ? i.insertBefore(r, i.firstChild) : i.appendChild(r)), (i = new n._Paper).width = o, i.height = s, i.canvas = r, i.clear(), i._left = i._top = 0, h && (i.renderfix = function () {}), i.renderfix(), i
                };
                n._engine.setViewBox = function (n, i, r, u, f) {
                    w("raphael.setViewBox", this, this._viewBox, [n, i, r, u, f]);
                    var o, h, c = this.getSize(),
                        s = d(r / c.width, u / c.height),
                        e = this.top,
                        l = f ? "xMidYMid meet" : "xMinYMin";
                    for (null == n ? (this._vbSize && (s = 1), delete this._vbSize, o = "0 0 " + this.width + b + this.height) : (this._vbSize = s, o = n + b + i + b + r + b + u), t(this.canvas, {
                            viewBox: o,
                            preserveAspectRatio: l
                        }); s && e;) h = "stroke-width" in e.attrs ? e.attrs["stroke-width"] : 1, e.attr({
                        "stroke-width": h
                    }), e._.dirty = 1, e._.dirtyT = 1, e = e.prev;
                    return this._viewBox = [n, i, r, u, !!f], this
                };
                n.prototype.renderfix = function () {
                    var n, t = this.canvas,
                        u = t.style,
                        i, r;
                    try {
                        n = t.getScreenCTM() || t.createSVGMatrix()
                    } catch (u) {
                        n = t.createSVGMatrix()
                    }
                    i = -n.e % 1;
                    r = -n.f % 1;
                    (i || r) && (i && (this._left = (this._left + i) % 1, u.left = this._left + "px"), r && (this._top = (this._top + r) % 1, u.top = this._top + "px"))
                };
                n.prototype.clear = function () {
                    n.eve("raphael.clear", this);
                    for (var i = this.canvas; i.firstChild;) i.removeChild(i.firstChild);
                    this.bottom = this.top = null;
                    (this.desc = t("desc")).appendChild(n._g.doc.createTextNode("Created with Raphaël " + n.version));
                    i.appendChild(this.desc);
                    i.appendChild(this.defs = t("defs"))
                };
                n.prototype.remove = function () {
                    for (var t in w("raphael.remove", this), this.canvas.parentNode && this.canvas.parentNode.removeChild(this.canvas), this) this[t] = "function" == typeof this[t] ? n._removedFactory(t) : null
                };
                tt = n.st;
                for (y in r) r[i](y) && !tt[i](y) && (tt[y] = function (n) {
                    return function () {
                        var t = arguments;
                        return this.forEach(function (i) {
                            i[n].apply(i, t)
                        })
                    }
                }(y))
            }
        }.apply(t, r)) || (n.exports = u)
    }, function (n, t, i) {
        var r, u;
        r = [i(0)];
        void 0 === (u = function (n) {
            var b, v;
            if (!n || n.vml) {
                var h = "hasOwnProperty",
                    r = String,
                    f = parseFloat,
                    c = Math,
                    e = c.round,
                    k = c.max,
                    d = c.min,
                    y = c.abs,
                    l = /[, ]+/,
                    rt = n.eve,
                    o = " ",
                    u = "",
                    g = {
                        M: "m",
                        L: "l",
                        C: "c",
                        Z: "x",
                        m: "t",
                        l: "r",
                        c: "v",
                        z: "x"
                    },
                    ut = /([clmz]),?([^clmz]*)/gi,
                    ft = / progid:\S+Blur\([^\)]+\)/g,
                    et = /-?[^,\s-]+/g,
                    nt = "position:absolute;left:0;top:0;width:1px;height:1px;behavior:url(#default#VML)",
                    t = 21600,
                    ot = {
                        path: 1,
                        rect: 1,
                        image: 1
                    },
                    st = {
                        circle: 1,
                        ellipse: 1
                    },
                    tt = function (t, i, r) {
                        var u = n.matrix();
                        return u.rotate(-t, .5, .5), {
                            dx: u.x(i, r),
                            dy: u.y(i, r)
                        }
                    },
                    p = function (n, i, r, u, f, e) {
                        var a = n._,
                            b = n.matrix,
                            h = a.fillpos,
                            c = n.node,
                            v = c.style,
                            p = 1,
                            w = "",
                            k = t / i,
                            d = t / r,
                            l, s;
                        (v.visibility = "hidden", i && r) && ((c.coordsize = y(k) + o + y(d), v.rotation = e * (i * r < 0 ? -1 : 1), e) && (l = tt(e, u, f), u = l.dx, f = l.dy), (i < 0 && (w += "x"), r < 0 && (w += " y") && (p = -1), v.flip = w, c.coordorigin = u * -k + o + f * -d, h || a.fillsize) && (s = c.getElementsByTagName("fill"), s = s && s[0], c.removeChild(s), h && (l = tt(e, b.x(h[0], h[1]), b.y(h[0], h[1])), s.position = l.dx * p + o + l.dy * p), a.fillsize && (s.size = a.fillsize[0] * y(i) + o + a.fillsize[1] * y(r)), c.appendChild(s)), v.visibility = "visible")
                    };
                n.toString = function () {
                    return "Your browser doesn’t support SVG. Falling down to VML.\nYou are running Raphaël " + this.version
                };
                var s, it = function (n, t, i) {
                        for (var e, u = r(t).toLowerCase().split("-"), o = i ? "end" : "start", f = u.length, s = "classic", h = "medium", c = "medium"; f--;) switch (u[f]) {
                            case "block":
                            case "classic":
                            case "oval":
                            case "diamond":
                            case "open":
                            case "none":
                                s = u[f];
                                break;
                            case "wide":
                            case "narrow":
                                c = u[f];
                                break;
                            case "long":
                            case "short":
                                h = u[f]
                        }
                        e = n.node.getElementsByTagName("stroke")[0];
                        e[o + "arrow"] = s;
                        e[o + "arrowlength"] = h;
                        e[o + "arrowwidth"] = c
                    },
                    a = function (i, c) {
                        var wt, nt, at, ft, ct, y, bt, vt, tt, b, gt, ni, lt, ti, kt, yt, pt;
                        i.attrs = i.attrs || {};
                        var w = i.node,
                            a = i.attrs,
                            rt = w.style,
                            ri = ot[i.type] && (c.x != a.x || c.y != a.y || c.width != a.width || c.height != a.height || c.cx != a.cx || c.cy != a.cy || c.rx != a.rx || c.ry != a.ry || c.r != a.r),
                            si = st[i.type] && (a.cx != c.cx || a.cy != c.cy || a.r != c.r || a.rx != c.rx || a.ry != c.ry),
                            v = i;
                        for (wt in c) c[h](wt) && (a[wt] = c[wt]);
                        if (ri && (a.path = n._getPath[i.type](i), i._.dirty = 1), c.href && (w.href = c.href), c.title && (w.title = c.title), c.target && (w.target = c.target), c.cursor && (rt.cursor = c.cursor), "blur" in c && i.blur(c.blur), (c.path && "path" == i.type || ri) && (w.path = function (i) {
                                var l = /[ahqstv]/gi,
                                    a = n._pathToAbsolute,
                                    v, y, h, c, f, w, s, p;
                                if (r(i).match(l) && (a = n._path2curve), l = /[clmz]/g, a == n._pathToAbsolute && !r(i).match(l)) return r(i).replace(ut, function (n, i, r) {
                                    var u = [],
                                        o = "m" == i.toLowerCase(),
                                        f = g[i];
                                    return r.replace(et, function (n) {
                                        o && 2 == u.length && (f += u + g["m" == i ? "l" : "L"], u = []);
                                        u.push(e(n * t))
                                    }), f + u
                                });
                                for (c = a(i), v = [], f = 0, w = c.length; f < w; f++) {
                                    for (y = c[f], "z" == (h = c[f][0].toLowerCase()) && (h = "x"), s = 1, p = y.length; s < p; s++) h += e(y[s] * t) + (s != p - 1 ? "," : u);
                                    v.push(h)
                                }
                                return v.join(o)
                            }(~r(a.path).toLowerCase().indexOf("r") ? n._pathToAbsolute(a.path) : a.path), i._.dirty = 1, "image" == i.type && (i._.fillpos = [a.x, a.y], i._.fillsize = [a.width, a.height], p(i, 1, 1, 0, 0, 0))), "transform" in c && i.transform(c.transform), si) {
                            var dt = +a.cx,
                                ui = +a.cy,
                                fi = +a.rx || +a.r || 0,
                                ei = +a.ry || +a.r || 0;
                            w.path = n.format("ar{0},{1},{2},{3},{4},{1},{4},{1}x", e((dt - fi) * t), e((ui - ei) * t), e((dt + fi) * t), e((ui + ei) * t), e(dt * t));
                            i._.dirty = 1
                        }
                        if ("clip-rect" in c && (nt = r(c["clip-rect"]).split(l), 4 == nt.length && (nt[2] = +nt[2] + +nt[0], nt[3] = +nt[3] + +nt[1], at = w.clipRect || n._g.doc.createElement("div"), ft = at.style, ft.clip = n.format("rect({1}px {2}px {3}px {0}px)", nt), w.clipRect || (ft.position = "absolute", ft.top = 0, ft.left = 0, ft.width = i.paper.width + "px", ft.height = i.paper.height + "px", w.parentNode.insertBefore(at, w), at.appendChild(w), w.clipRect = at)), c["clip-rect"] || w.clipRect && (w.clipRect.style.clip = "auto")), i.textpath && (ct = i.textpath.style, c.font && (ct.font = c.font), c["font-family"] && (ct.fontFamily = '"' + c["font-family"].split(",")[0].replace(/^['"]+|['"]+$/g, u) + '"'), c["font-size"] && (ct.fontSize = c["font-size"]), c["font-weight"] && (ct.fontWeight = c["font-weight"]), c["font-style"] && (ct.fontStyle = c["font-style"])), ("arrow-start" in c && it(v, c["arrow-start"]), "arrow-end" in c && it(v, c["arrow-end"], 1), null != c.opacity || null != c.fill || null != c.src || null != c.stroke || null != c["stroke-width"] || null != c["stroke-opacity"] || null != c["fill-opacity"] || null != c["stroke-dasharray"] || null != c["stroke-miterlimit"] || null != c["stroke-linejoin"] || null != c["stroke-linecap"]) && (y = w.getElementsByTagName("fill"), (!(y = y && y[0]) && (y = s("fill")), "image" == i.type && c.src && (y.src = c.src), c.fill && (y.on = !0), null != y.on && "none" != c.fill && null !== c.fill || (y.on = !1), y.on && c.fill) && (bt = r(c.fill).match(n._ISURL), bt ? (y.parentNode == w && w.removeChild(y), y.rotate = !0, y.src = bt[1], y.type = "tile", vt = i.getBBox(1), y.position = vt.x + o + vt.y, i._.fillpos = [vt.x, vt.y], n._preload(bt[1], function () {
                                i._.fillsize = [this.offsetWidth, this.offsetHeight]
                            })) : (y.color = n.getRGB(c.fill).hex, y.src = u, y.type = "solid", n.getRGB(c.fill).error && (v.type in {
                                circle: 1,
                                ellipse: 1
                            } || "r" != r(c.fill).charAt()) && ht(v, c.fill, y) && (a.fill = "none", a.gradient = c.fill, y.rotate = !1))), ("fill-opacity" in c || "opacity" in c) && (tt = ((+a["fill-opacity"] + 1 || 2) - 1) * ((+a.opacity + 1 || 2) - 1) * ((+n.getRGB(c.fill).o + 1 || 2) - 1), tt = d(k(tt, 0), 1), y.opacity = tt, y.src && (y.color = "none")), w.appendChild(y), b = w.getElementsByTagName("stroke") && w.getElementsByTagName("stroke")[0], gt = !1, b || (gt = b = s("stroke")), (c.stroke && "none" != c.stroke || c["stroke-width"] || null != c["stroke-opacity"] || c["stroke-dasharray"] || c["stroke-miterlimit"] || c["stroke-linejoin"] || c["stroke-linecap"]) && (b.on = !0), ("none" == c.stroke || null === c.stroke || null == b.on || 0 == c.stroke || 0 == c["stroke-width"]) && (b.on = !1), ni = n.getRGB(c.stroke), b.on && c.stroke && (b.color = ni.hex), tt = ((+a["stroke-opacity"] + 1 || 2) - 1) * ((+a.opacity + 1 || 2) - 1) * ((+ni.o + 1 || 2) - 1), lt = .75 * (f(c["stroke-width"]) || 1), (tt = d(k(tt, 0), 1), null == c["stroke-width"] && (lt = a["stroke-width"]), c["stroke-width"] && (b.weight = lt), lt && lt < 1 && (tt *= lt) && (b.weight = 1), b.opacity = tt, c["stroke-linejoin"] && (b.joinstyle = c["stroke-linejoin"] || "miter"), b.miterlimit = c["stroke-miterlimit"] || 8, c["stroke-linecap"] && (b.endcap = "butt" == c["stroke-linecap"] ? "flat" : "square" == c["stroke-linecap"] ? "square" : "round"), "stroke-dasharray" in c) && (ti = {
                                "-": "shortdash",
                                ".": "shortdot",
                                "-.": "shortdashdot",
                                "-..": "shortdashdotdot",
                                ". ": "dot",
                                "- ": "dash",
                                "--": "longdash",
                                "- .": "dashdot",
                                "--.": "longdashdot",
                                "--..": "longdashdotdot"
                            }, b.dashstyle = ti[h](c["stroke-dasharray"]) ? ti[c["stroke-dasharray"]] : u), gt && w.appendChild(b)), "text" == v.type) {
                            v.paper.canvas.style.display = u;
                            kt = v.paper.span;
                            yt = a.font && a.font.match(/\d+(?:\.\d*)?(?=px)/);
                            rt = kt.style;
                            a.font && (rt.font = a.font);
                            a["font-family"] && (rt.fontFamily = a["font-family"]);
                            a["font-weight"] && (rt.fontWeight = a["font-weight"]);
                            a["font-style"] && (rt.fontStyle = a["font-style"]);
                            yt = f(a["font-size"] || yt && yt[0]) || 10;
                            rt.fontSize = 100 * yt + "px";
                            v.textpath.string && (kt.innerHTML = r(v.textpath.string).replace(/</g, "&#60;").replace(/&/g, "&#38;").replace(/\n/g, "<br>"));
                            pt = kt.getBoundingClientRect();
                            v.W = a.w = (pt.right - pt.left) / 100;
                            v.H = a.h = (pt.bottom - pt.top) / 100;
                            v.X = a.x;
                            v.Y = a.y + v.H / 2;
                            ("x" in c || "y" in c) && (v.path.v = n.format("m{0},{1}l{2},{1}", e(a.x * t), e(a.y * t), e(a.x * t) + 1));
                            for (var oi = ["x", "y", "text", "font", "font-family", "font-weight", "font-style", "font-size"], ii = 0, hi = oi.length; ii < hi; ii++)
                                if (oi[ii] in c) {
                                    v._.dirty = 1;
                                    break
                                } switch (a["text-anchor"]) {
                                case "start":
                                    v.textpath.style["v-text-align"] = "left";
                                    v.bbx = v.W / 2;
                                    break;
                                case "end":
                                    v.textpath.style["v-text-align"] = "right";
                                    v.bbx = -v.W / 2;
                                    break;
                                default:
                                    v.textpath.style["v-text-align"] = "center";
                                    v.bbx = 0
                            }
                            v.textpath.style["v-text-kern"] = !0
                        }
                    },
                    ht = function (t, i, e) {
                        var h, s;
                        t.attrs = t.attrs || {};
                        t.attrs;
                        var a = Math.pow,
                            v = "linear",
                            p = ".5 .5";
                        if ((t.attrs.gradient = i, i = (i = r(i).replace(n._radial_gradient, function (n, t, i) {
                                return v = "radial", t && i && (t = f(t), i = f(i), a(t - .5, 2) + a(i - .5, 2) > .25 && (i = c.sqrt(.25 - a(t - .5, 2)) * (2 * (i > .5) - 1) + .5), p = t + o + i), u
                            })).split(/\s*\-\s*/), "linear" == v) && (h = i.shift(), h = -f(h), isNaN(h)) || (s = n._parseDots(i), !s)) return null;
                        if (t = t.shape || t.node, s.length) {
                            t.removeChild(e);
                            e.on = !0;
                            e.method = "none";
                            e.color = s[0].color;
                            e.color2 = s[s.length - 1].color;
                            for (var y = [], l = 0, w = s.length; l < w; l++) s[l].offset && y.push(s[l].offset + o + s[l].color);
                            e.colors = y.length ? y.join() : "0% " + e.color;
                            "radial" == v ? (e.type = "gradientTitle", e.focus = "100%", e.focussize = "0 0", e.focusposition = p, e.angle = 0) : (e.type = "gradient", e.angle = (270 - h) % 360);
                            t.appendChild(e)
                        }
                        return 1
                    },
                    w = function (t, i) {
                        this[0] = this.node = t;
                        t.raphael = !0;
                        this.id = n._oid++;
                        t.raphaelid = this.id;
                        this.X = 0;
                        this.Y = 0;
                        this.attrs = {};
                        this.paper = i;
                        this.matrix = n.matrix();
                        this._ = {
                            transform: [],
                            sx: 1,
                            sy: 1,
                            dx: 0,
                            dy: 0,
                            deg: 0,
                            dirty: 1,
                            dirtyT: 1
                        };
                        !i.bottom && (i.bottom = this);
                        this.prev = i.top;
                        i.top && (i.top.next = this);
                        i.top = this;
                        this.next = null
                    },
                    i = n.el;
                w.prototype = i;
                i.constructor = w;
                i.transform = function (i) {
                    var h, e, a;
                    if (null == i) return this._.transform;
                    e = this.paper._viewBoxShift;
                    a = e ? "s" + [e.scale, e.scale] + "-1-1t" + [e.dx, e.dy] : u;
                    e && (h = i = r(i).replace(/\.{3}|\u2026/g, this._.transform || u));
                    n._extractTransform(this, a + i);
                    var f, s = this.matrix.clone(),
                        c = this.skew,
                        l = this.node,
                        v = ~r(this.attrs.fill).indexOf("-"),
                        d = !r(this.attrs.fill).indexOf("url(");
                    if (s.translate(1, 1), d || v || "image" == this.type)
                        if (c.matrix = "1 0 0 1", c.offset = "0 0", f = s.split(), v && f.noRotation || !f.isSimple) {
                            l.style.filter = s.toFilter();
                            var y = this.getBBox(),
                                w = this.getBBox(1),
                                b = y.x - w.x,
                                k = y.y - w.y;
                            l.coordorigin = b * -t + o + k * -t;
                            p(this, 1, 1, b, k, 0)
                        } else l.style.filter = u, p(this, f.scalex, f.scaley, f.dx, f.dy, f.rotate);
                    else l.style.filter = u, c.matrix = r(s), c.offset = s.offset();
                    return null !== h && (this._.transform = h, n._extractTransform(this, h)), this
                };
                i.rotate = function (n, t, i) {
                    if (this.removed) return this;
                    if (null != n) {
                        if ((n = r(n).split(l)).length - 1 && (t = f(n[1]), i = f(n[2])), n = f(n[0]), null == i && (t = i), null == t || null == i) {
                            var u = this.getBBox(1);
                            t = u.x + u.width / 2;
                            i = u.y + u.height / 2
                        }
                        return this._.dirtyT = 1, this.transform(this._.transform.concat([
                            ["r", n, t, i]
                        ])), this
                    }
                };
                i.translate = function (n, t) {
                    return this.removed ? this : ((n = r(n).split(l)).length - 1 && (t = f(n[1])), n = f(n[0]) || 0, t = +t || 0, this._.bbox && (this._.bbox.x += n, this._.bbox.y += t), this.transform(this._.transform.concat([
                        ["t", n, t]
                    ])), this)
                };
                i.scale = function (n, t, i, u) {
                    if (this.removed) return this;
                    if ((n = r(n).split(l)).length - 1 && (t = f(n[1]), i = f(n[2]), u = f(n[3]), isNaN(i) && (i = null), isNaN(u) && (u = null)), n = f(n[0]), null == t && (t = n), null == u && (i = u), null == i || null == u) var e = this.getBBox(1);
                    return i = null == i ? e.x + e.width / 2 : i, u = null == u ? e.y + e.height / 2 : u, this.transform(this._.transform.concat([
                        ["s", n, t, i, u]
                    ])), this._.dirtyT = 1, this
                };
                i.hide = function () {
                    return !this.removed && (this.node.style.display = "none"), this
                };
                i.show = function () {
                    return !this.removed && (this.node.style.display = u), this
                };
                i.auxGetBBox = n.el.getBBox;
                i.getBBox = function () {
                    var t = this.auxGetBBox(),
                        n, i;
                    return this.paper && this.paper._viewBoxShift ? (n = {}, i = 1 / this.paper._viewBoxShift.scale, n.x = t.x - this.paper._viewBoxShift.dx, n.x *= i, n.y = t.y - this.paper._viewBoxShift.dy, n.y *= i, n.width = t.width * i, n.height = t.height * i, n.x2 = n.x + n.width, n.y2 = n.y + n.height, n) : t
                };
                i._getBBox = function () {
                    return this.removed ? {} : {
                        x: this.X + (this.bbx || 0) - this.W / 2,
                        y: this.Y - this.H,
                        width: this.W,
                        height: this.H
                    }
                };
                i.remove = function () {
                    if (!this.removed && this.node.parentNode) {
                        for (var t in this.paper.__set__ && this.paper.__set__.exclude(this), n.eve.unbind("raphael.*.*." + this.id), n._tear(this, this.paper), this.node.parentNode.removeChild(this.node), this.shape && this.shape.parentNode.removeChild(this.shape), this) this[t] = "function" == typeof this[t] ? n._removedFactory(t) : null;
                        this.removed = !0
                    }
                };
                i.attr = function (t, i) {
                    var f, s, r, u, v, y;
                    if (this.removed) return this;
                    if (null == t) {
                        f = {};
                        for (s in this.attrs) this.attrs[h](s) && (f[s] = this.attrs[s]);
                        return f.gradient && "none" == f.fill && (f.fill = f.gradient) && delete f.gradient, f.transform = this._.transform, f
                    }
                    if (null == i && n.is(t, "string")) {
                        if ("fill" == t && "none" == this.attrs.fill && this.attrs.gradient) return this.attrs.gradient;
                        for (var p = t.split(l), o = {}, e = 0, c = p.length; e < c; e++) o[t] = (t = p[e]) in this.attrs ? this.attrs[t] : n.is(this.paper.customAttributes[t], "function") ? this.paper.customAttributes[t].def : n._availableAttrs[t];
                        return c - 1 ? o : o[p[0]]
                    }
                    if (this.attrs && null == i && n.is(t, "array")) {
                        for (o = {}, e = 0, c = t.length; e < c; e++) o[t[e]] = this.attr(t[e]);
                        return o
                    }
                    for (u in null != i && ((r = {})[t] = i), null == i && n.is(t, "object") && (r = t), r) rt("raphael.attr." + u + "." + this.id, this, r[u]);
                    if (r) {
                        for (u in this.paper.customAttributes)
                            if (this.paper.customAttributes[h](u) && r[h](u) && n.is(this.paper.customAttributes[u], "function")) {
                                v = this.paper.customAttributes[u].apply(this, [].concat(r[u]));
                                for (y in this.attrs[u] = r[u], v) v[h](y) && (r[y] = v[y])
                            } r.text && "text" == this.type && (this.textpath.string = r.text);
                        a(this, r)
                    }
                    return this
                };
                i.toFront = function () {
                    return !this.removed && this.node.parentNode.appendChild(this.node), this.paper && this.paper.top != this && n._tofront(this, this.paper), this
                };
                i.toBack = function () {
                    return this.removed ? this : (this.node.parentNode.firstChild != this.node && (this.node.parentNode.insertBefore(this.node, this.node.parentNode.firstChild), n._toback(this, this.paper)), this)
                };
                i.insertAfter = function (t) {
                    return this.removed ? this : (t.constructor == n.st.constructor && (t = t[t.length - 1]), t.node.nextSibling ? t.node.parentNode.insertBefore(this.node, t.node.nextSibling) : t.node.parentNode.appendChild(this.node), n._insertafter(this, t, this.paper), this)
                };
                i.insertBefore = function (t) {
                    return this.removed ? this : (t.constructor == n.st.constructor && (t = t[0]), t.node.parentNode.insertBefore(this.node, t.node), n._insertbefore(this, t, this.paper), this)
                };
                i.blur = function (t) {
                    var i = this.node.runtimeStyle,
                        r = i.filter;
                    return r = r.replace(ft, u), 0 != +t ? (this.attrs.blur = t, i.filter = r + o + " progid:DXImageTransform.Microsoft.Blur(pixelradius=" + (+t || 1.5) + ")", i.margin = n.format("-{0}px 0 0 -{0}px", e(+t || 1.5))) : (i.filter = r, i.margin = 0, delete this.attrs.blur), this
                };
                n._engine.path = function (n, i) {
                    var f = s("shape"),
                        r, h, e;
                    return f.style.cssText = nt, f.coordsize = t + o + t, f.coordorigin = i.coordorigin, r = new w(f, i), h = {
                        fill: "none",
                        stroke: "#000"
                    }, n && (h.path = n), r.type = "path", r.path = [], r.Path = u, a(r, h), i.canvas && i.canvas.appendChild(f), e = s("skew"), e.on = !0, f.appendChild(e), r.skew = e, r.transform(u), r
                };
                n._engine.rect = function (t, i, r, u, f, e) {
                    var h = n._rectPath(i, r, u, f, e),
                        o = t.path(h),
                        s = o.attrs;
                    return o.X = s.x = i, o.Y = s.y = r, o.W = s.width = u, o.H = s.height = f, s.r = e, s.path = h, o.type = "rect", o
                };
                n._engine.ellipse = function (n, t, i, r, u) {
                    var f = n.path();
                    return f.attrs, f.X = t - r, f.Y = i - u, f.W = 2 * r, f.H = 2 * u, f.type = "ellipse", a(f, {
                        cx: t,
                        cy: i,
                        rx: r,
                        ry: u
                    }), f
                };
                n._engine.circle = function (n, t, i, r) {
                    var u = n.path();
                    return u.attrs, u.X = t - r, u.Y = i - r, u.W = u.H = 2 * r, u.type = "circle", a(u, {
                        cx: t,
                        cy: i,
                        r: r
                    }), u
                };
                n._engine.image = function (t, i, r, u, f, e) {
                    var l = n._rectPath(r, u, f, e),
                        o = t.path(l).attr({
                            stroke: "none"
                        }),
                        s = o.attrs,
                        c = o.node,
                        h = c.getElementsByTagName("fill")[0];
                    return s.src = i, o.X = s.x = r, o.Y = s.y = u, o.W = s.width = f, o.H = s.height = e, s.path = l, o.type = "image", h.parentNode == c && c.removeChild(h), h.rotate = !0, h.src = i, h.type = "tile", o._.fillpos = [r, u], o._.fillsize = [f, e], c.appendChild(h), p(o, 1, 1, 0, 0, 0), o
                };
                n._engine.text = function (i, f, h, c) {
                    var v = s("shape"),
                        y = s("path"),
                        p = s("textpath"),
                        l, k, b;
                    return f = f || 0, h = h || 0, c = c || "", y.v = n.format("m{0},{1}l{2},{1}", e(f * t), e(h * t), e(f * t) + 1), y.textpathok = !0, p.string = r(c), p.on = !0, v.style.cssText = nt, v.coordsize = t + o + t, v.coordorigin = "0 0", l = new w(v, i), k = {
                        fill: "#000",
                        stroke: "none",
                        font: n._availableAttrs.font,
                        text: c
                    }, l.shape = v, l.path = y, l.textpath = p, l.type = "text", l.attrs.text = r(c), l.attrs.x = f, l.attrs.y = h, l.attrs.w = 1, l.attrs.h = 1, a(l, k), v.appendChild(p), v.appendChild(y), i.canvas.appendChild(v), b = s("skew"), b.on = !0, v.appendChild(b), l.skew = b, l.transform(u), l
                };
                n._engine.setSize = function (t, i) {
                    var r = this.canvas.style;
                    return this.width = t, this.height = i, t == +t && (t += "px"), i == +i && (i += "px"), r.width = t, r.height = i, r.clip = "rect(0 " + t + " " + i + " 0)", this._viewBox && n._engine.setViewBox.apply(this, this._viewBox), this
                };
                n._engine.setViewBox = function (t, i, r, u, f) {
                    n.eve("raphael.setViewBox", this, this._viewBox, [t, i, r, u, f]);
                    var e, o, s = this.getSize(),
                        h = s.width,
                        c = s.height;
                    return f && (r * (e = c / u) < h && (t -= (h - r * e) / 2 / e), u * (o = h / r) < c && (i -= (c - u * o) / 2 / o)), this._viewBox = [t, i, r, u, !!f], this._viewBoxShift = {
                        dx: -t,
                        dy: -i,
                        scale: s
                    }, this.forEach(function (n) {
                        n.transform("...")
                    }), this
                };
                n._engine.initWin = function (n) {
                    var t = n.document;
                    t.styleSheets.length < 31 ? t.createStyleSheet().addRule(".rvml", "behavior:url(#default#VML)") : t.styleSheets[0].addRule(".rvml", "behavior:url(#default#VML)");
                    try {
                        t.namespaces.rvml || t.namespaces.add("rvml", "urn:schemas-microsoft-com:vml");
                        s = function (n) {
                            return t.createElement("<rvml:" + n + ' class="rvml">')
                        }
                    } catch (n) {
                        s = function (n) {
                            return t.createElement("<" + n + ' xmlns="urn:schemas-microsoft.com:vml" class="rvml">')
                        }
                    }
                };
                n._engine.initWin(n._g.win);
                n._engine.create = function () {
                    var f = n._getContainer.apply(0, arguments),
                        u = f.container,
                        i = f.height,
                        r = f.width,
                        h = f.x,
                        c = f.y;
                    if (!u) throw new Error("VML container not found.");
                    var t = new n._Paper,
                        e = t.canvas = n._g.doc.createElement("div"),
                        s = e.style;
                    return h = h || 0, c = c || 0, r = r || 512, i = i || 342, t.width = r, t.height = i, r == +r && (r += "px"), i == +i && (i += "px"), t.coordsize = 216e5 + o + 216e5, t.coordorigin = "0 0", t.span = n._g.doc.createElement("span"), t.span.style.cssText = "position:absolute;left:-9999em;top:-9999em;padding:0;margin:0;line-height:1;", e.appendChild(t.span), s.cssText = n.format("top:0;left:0;width:{0};height:{1};display:inline-block;position:relative;clip:rect(0 {0} {1} 0);overflow:hidden", r, i), 1 == u ? (n._g.doc.body.appendChild(e), s.left = h + "px", s.top = c + "px", s.position = "absolute") : u.firstChild ? u.insertBefore(e, u.firstChild) : u.appendChild(e), t.renderfix = function () {}, t
                };
                n.prototype.clear = function () {
                    n.eve("raphael.clear", this);
                    this.canvas.innerHTML = u;
                    this.span = n._g.doc.createElement("span");
                    this.span.style.cssText = "position:absolute;left:-9999em;top:-9999em;padding:0;margin:0;line-height:1;display:inline;";
                    this.canvas.appendChild(this.span);
                    this.bottom = this.top = null
                };
                n.prototype.remove = function () {
                    for (var t in n.eve("raphael.remove", this), this.canvas.parentNode.removeChild(this.canvas), this) this[t] = "function" == typeof this[t] ? n._removedFactory(t) : null;
                    return !0
                };
                b = n.st;
                for (v in i) i[h](v) && !b[h](v) && (b[v] = function (n) {
                    return function () {
                        var t = arguments;
                        return this.forEach(function (i) {
                            i[n].apply(i, t)
                        })
                    }
                }(v))
            }
        }.apply(t, r)) || (n.exports = u)
    }])
});
! function (n, t) {
    "object" == typeof exports && "undefined" != typeof module ? t(exports) : "function" == typeof define && define.amd ? define(["exports"], t) : t((n = n || self).window = n.window || {})
}(this, function (n) {
    "use strict";

    function ke(n, t) {
        n.prototype = Object.create(t.prototype);
        (n.prototype.constructor = n).__proto__ = t
    }

    function ht(n) {
        if (void 0 === n) throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
        return n
    }

    function l(n) {
        return "string" == typeof n
    }

    function s(n) {
        return "function" == typeof n
    }

    function ct(n) {
        return "number" == typeof n
    }

    function bu(n) {
        return void 0 === n
    }

    function lt(n) {
        return "object" == typeof n
    }

    function a(n) {
        return !1 !== n
    }

    function de() {
        return "undefined" != typeof window
    }

    function ge(n) {
        return s(n) || l(n)
    }

    function no(n) {
        return (ou = bi(n, k)) && ut
    }

    function ku(n, t) {
        return console.warn("Invalid property", n, "set to", t, "Missing plugin? gsap.registerPlugin()")
    }

    function dr(n, t) {
        return !t && console.warn(n)
    }

    function to(n, t) {
        return n && (k[n] = t) && ou && (ou[n] = t) || k
    }

    function li() {
        return 0
    }

    function du(n) {
        var r, t, i = n[0];
        if (lt(i) || s(i) || (n = [n]), !(r = (i._gsap || {}).harness)) {
            for (t = lu.length; t-- && !lu[t].targetTest(i););
            r = lu[t]
        }
        for (t = n.length; t--;) n[t] && (n[t]._gsap || (n[t]._gsap = new ue(n[t], r))) || n.splice(t, 1);
        return n
    }

    function ii(n) {
        return n._gsap || du(tt(n))[0]._gsap
    }

    function io(n, t, i) {
        return (i = n[t]) && s(i) ? n[t]() : bu(i) && n.getAttribute && n.getAttribute(t) || i
    }

    function p(n, t) {
        return (n = n.split(",")).forEach(t) || n
    }

    function t(n) {
        return Math.round(1e5 * n) / 1e5 || 0
    }

    function ic(n, t) {
        for (var r = t.length, i = 0; n.indexOf(t[i]) < 0 && ++i < r;);
        return i < r
    }

    function gr() {
        var t, n, i = pt.length,
            r = pt.slice(0);
        for (df = {}, t = pt.length = 0; t < i; t++)(n = r[t]) && n._lazy && (n.render(n._lazy[0], n._lazy[1], !0)._lazy = 0)
    }

    function ro(n, t, i, r) {
        pt.length && gr();
        n.render(t, i, r);
        pt.length && gr()
    }

    function uo(n) {
        var t = parseFloat(n);
        return (t || 0 === t) && (n + "").match(ls).length < 2 ? t : l(n) ? n.trim() : n
    }

    function fo(n) {
        return n
    }

    function g(n, t) {
        for (var i in t) i in n || (n[i] = t[i]);
        return n
    }

    function rc(n, t) {
        for (var i in t) i in n || "duration" === i || "ease" === i || (n[i] = t[i])
    }

    function gu(n, t) {
        for (var i in t) "__proto__" !== i && "constructor" !== i && "prototype" !== i && (n[i] = lt(t[i]) ? gu(n[i] || (n[i] = {}), t[i]) : t[i]);
        return n
    }

    function nf(n, t) {
        var i, r = {};
        for (i in n) i in t || (r[i] = n[i]);
        return r
    }

    function nu(n) {
        var t = n.parent || e,
            i = n.keyframes ? rc : g;
        if (a(n.inherit))
            for (; t;) i(n, t.vars.defaults), t = t.parent || t._dp;
        return n
    }

    function tu(n, t, i, r) {
        void 0 === i && (i = "_first");
        void 0 === r && (r = "_last");
        var u = t._prev,
            f = t._next;
        u ? u._next = f : n[i] === t && (n[i] = f);
        f ? f._prev = u : n[r] === t && (n[r] = u);
        t._next = t._prev = t.parent = null
    }

    function vt(n, t) {
        n.parent && (!t || n.parent.autoRemoveChildren) && n.parent.remove(n);
        n._act = 0
    }

    function ri(n, t) {
        if (n && (!t || t._end > n._dur || t._start < 0))
            for (var i = n; i;) i._dirty = 1, i = i.parent;
        return n
    }

    function eo(n) {
        return n._repeat ? ki(n._tTime, n = n.duration() + n._rDelay) * n : 0
    }

    function iu(n, t) {
        return (n - t._start) * t._ts + (0 <= t._ts ? 0 : t._dirty ? t.totalDuration() : t._tDur)
    }

    function tf(n) {
        return n._end = t(n._start + (n._tDur / Math.abs(n._ts || n._rts || u) || 0))
    }

    function oo(n, i) {
        var r = n._dp;
        return r && r.smoothChildTiming && n._ts && (n._start = t(r._time - (0 < n._ts ? i / n._ts : ((n._dirty ? n.totalDuration() : n._tDur) - i) / -n._ts)), tf(n), r._dirty || ri(r, n)), n
    }

    function so(n, t) {
        var i;
        if ((t._time || t._initted && !t._dur) && (i = iu(n.rawTime(), t), (!t._dur || lr(0, t.totalDuration(), i) - t._tTime > u) && t.render(i, !0)), ri(n, t)._dp && n._initted && n._time >= n._dur && n._ts) {
            if (n._dur < n.duration())
                for (i = n; i._dp;) 0 <= i.rawTime() && i.totalTime(i._tTime), i = i._dp;
            n._zTime = -u
        }
    }

    function ft(n, i, r, u) {
        return i.parent && vt(i), i._start = t((ct(r) ? r : r || n !== e ? nt(n, r, i) : n._time) + i._delay), i._end = t(i._start + (i.totalDuration() / Math.abs(i.timeScale()) || 0)),
            function (n, t, i, r, u) {
                void 0 === i && (i = "_first");
                void 0 === r && (r = "_last");
                var e, f = n[r];
                if (u)
                    for (e = t[u]; f && f[u] > e;) f = f._prev;
                f ? (t._next = f._next, f._next = t) : (t._next = n[i], n[i] = t);
                t._next ? t._next._prev = t : n[r] = t;
                t._prev = f;
                t.parent = t._dp = n
            }(n, i, "_first", "_last", n._sort ? "_start" : 0), te(i) || (n._recent = i), u || so(n, i), n
    }

    function ho(n, t) {
        return (k.ScrollTrigger || ku("scrollTrigger", t)) && k.ScrollTrigger.create(t, n)
    }

    function co(n, t, i, r) {
        return pc(n, t), n._initted ? !i && n._pt && (n._dur && !1 !== n.vars.lazy || !n._dur && n.vars.lazy) && fs !== rt.frame ? (pt.push(n), n._lazy = [t, r], 1) : void 0 : 1
    }

    function ai(n, i, r, u) {
        var f = n._repeat,
            e = t(i) || 0,
            o = n._tTime / n._tDur;
        return o && !u && (n._time *= e / n._dur), n._dur = e, n._tDur = f ? f < 0 ? 1e10 : t(e * (f + 1) + n._rDelay * f) : e, o && !u ? oo(n, n._tTime = n._tDur * o) : n.parent && tf(n), r || ri(n.parent, n), n
    }

    function lo(n) {
        return n instanceof h ? ri(n) : ai(n, n._dur)
    }

    function er(n, t, i) {
        var f, u, s = ct(t[1]),
            e = (s ? 2 : 1) + (n < 2 ? 0 : 1),
            r = t[e];
        if (s && (r.duration = t[1]), r.parent = i, n) {
            for (f = r, u = i; u && !("immediateRender" in f);) f = u.vars.defaults || {}, u = a(u.vars.inherit) && u.parent;
            r.immediateRender = a(f.immediateRender);
            n < 2 ? r.runBackwards = 1 : r.startAt = t[e - 1]
        }
        return new o(t[0], r, t[1 + e])
    }

    function yt(n, t) {
        return n || 0 === n ? t(n) : t
    }

    function w(n) {
        if ("string" != typeof n) return "";
        var t = hc.exec(n);
        return t ? n.substr(t.index + t[0].length) : ""
    }

    function ao(n, t) {
        return n && lt(n) && "length" in n && (!t && !n.length || n.length - 1 in n && lt(n[0])) && !n.nodeType && n !== et
    }

    function vo(n) {
        return n.sort(function () {
            return .5 - Math.random()
        })
    }

    function yo(n) {
        if (s(n)) return n;
        var r = lt(n) ? n : {
                each: n
            },
            u = gi(r.ease),
            i = r.from || 0,
            c = parseFloat(r.base) || 0,
            a = {},
            v = 0 < i && i < 1,
            e = isNaN(i) || v,
            f = r.axis,
            o = i,
            h = i;
        return l(i) ? o = h = {
                center: .5,
                edges: .5,
                end: 1
            } [i] || 0 : !v && e && (o = i[0], h = i[1]),
            function (n, s, l) {
                var it, rt, nt, tt, d, k, b, g, y, v = (l || r).length,
                    p = a[v];
                if (!p) {
                    if (!(y = "auto" === r.grid ? 0 : (r.grid || [1, ot])[1])) {
                        for (b = -ot; b < (b = l[y++].getBoundingClientRect().left) && y < v;);
                        y--
                    }
                    for (p = a[v] = [], it = e ? Math.min(y, v) * o - .5 : i % y, rt = e ? v * h / y - .5 : i / y | 0, g = ot, k = b = 0; k < v; k++) nt = k % y - it, tt = rt - (k / y | 0), p[k] = d = f ? Math.abs("y" === f ? tt : nt) : os(nt * nt + tt * tt), b < d && (b = d), d < g && (g = d);
                    "random" === i && vo(p);
                    p.max = b - g;
                    p.min = g;
                    p.v = v = (parseFloat(r.amount) || parseFloat(r.each) * (v < y ? v - 1 : f ? "y" === f ? v / y : y : Math.max(y, v / y)) || 0) * ("edges" === i ? -1 : 1);
                    p.b = v < 0 ? c - v : c;
                    p.u = w(r.amount || r.each) || 0;
                    u = u && v < 0 ? ys(u) : u
                }
                return v = (p[n] - p.min) / p.max || 0, t(p.b + (u ? u(v) : v) * p.v) + p.u
            }
    }

    function rf(n) {
        var t = n < 1 ? Math.pow(10, (n + "").length - 2) : 1;
        return function (i) {
            var r = Math.round(parseFloat(i) / n) * n * t;
            return (r - r % 1) / t + (ct(i) ? 0 : w(i))
        }
    }

    function po(n, t) {
        var r, i, u = b(n);
        return !u && lt(n) && (r = u = n.radius || ot, n.values ? (n = tt(n.values), (i = !ct(n[0])) && (r *= r)) : n = rf(n.increment)), yt(t, u ? s(n) ? function (t) {
            return i = n(t), Math.abs(i - t) <= r ? i : t
        } : function (t) {
            for (var e, s, h = parseFloat(i ? t.x : t), c = parseFloat(i ? t.y : 0), o = ot, u = 0, f = n.length; f--;)(e = i ? (e = n[f].x - h) * e + (s = n[f].y - c) * s : Math.abs(n[f] - h)) < o && (o = e, u = f);
            return u = !r || o <= r ? n[u] : t, i || u === t || ct(t) ? u : u + w(t)
        } : rf(n))
    }

    function wo(n, t, i, r) {
        return yt(b(n) ? !t : !0 === i ? !!(i = 0) : !r, function () {
            return b(n) ? n[~~(Math.random() * n.length)] : (i = i || 1e-5) && (r = i < 1 ? Math.pow(10, (i + "").length - 2) : 1) && Math.floor(Math.round((n - i / 2 + Math.random() * (t - n + .99 * i)) / i) * i * r) / r
        })
    }

    function bo(n, t, i) {
        return yt(i, function (i) {
            return n[~~t(i)]
        })
    }

    function ru(n) {
        for (var t, r, f, u, i = 0, e = ""; ~(t = n.indexOf("random(", i));) f = n.indexOf(")", t), u = "[" === n.charAt(t + 7), r = n.substr(t + 7, f - t - 7).match(u ? ls : wf), e += n.substr(i, t - i) + wo(u ? r : +r[0], u ? 0 : +r[1], +r[2] || 1e-5), i = f + 1;
        return e + n.substr(i, n.length - i)
    }

    function ko(n, t, i) {
        var u, r, f, e = n.labels,
            o = ot;
        for (u in e)(r = e[u] - t) < 0 == !!i && r && o > (r = Math.abs(r)) && (f = u, o = r);
        return f
    }

    function or(n) {
        return vt(n), n.scrollTrigger && n.scrollTrigger.kill(!1), n.progress() < 1 && it(n, "onInterrupt"), n
    }

    function uf(n, t, i) {
        return (6 * (n = n < 0 ? n + 1 : 1 < n ? n - 1 : n) < 1 ? t + (i - t) * n * 6 : n < .5 ? i : 3 * n < 2 ? t + (i - t) * (2 / 3 - n) * 6 : t) * f + .5 | 0
    }

    function go(n, t, i) {
        var e, u, o, s, c, h, l, v, a, y, r = n ? ct(n) ? [n >> 16, n >> 8 & f, n & f] : 0 : ar.black;
        if (!r) {
            if ("," === n.substr(-1) && (n = n.substr(0, n.length - 1)), ar[n]) r = ar[n];
            else if ("#" === n.charAt(0)) {
                if (n.length < 6 && (n = "#" + (e = n.charAt(1)) + e + (u = n.charAt(2)) + u + (o = n.charAt(3)) + o + (5 === n.length ? n.charAt(4) + n.charAt(4) : "")), 9 === n.length) return [(r = parseInt(n.substr(1, 6), 16)) >> 16, r >> 8 & f, r & f, parseInt(n.substr(7), 16) / 255];
                r = [(n = parseInt(n.substr(1), 16)) >> 16, n >> 8 & f, n & f]
            } else if ("hsl" === n.substr(0, 3))
                if (r = y = n.match(wf), t) {
                    if (~n.indexOf("=")) return r = n.match(hs), i && r.length < 4 && (r[3] = 1), r
                } else s = +r[0] % 360 / 360, c = r[1] / 100, e = 2 * (h = r[2] / 100) - (u = h <= .5 ? h * (c + 1) : h + c - h * c), 3 < r.length && (r[3] *= 1), r[0] = uf(s + 1 / 3, e, u), r[1] = uf(s, e, u), r[2] = uf(s - 1 / 3, e, u);
            else r = n.match(wf) || ar.transparent;
            r = r.map(Number)
        }
        return t && !y && (e = r[0] / f, u = r[1] / f, o = r[2] / f, h = ((l = Math.max(e, u, o)) + (v = Math.min(e, u, o))) / 2, l === v ? s = c = 0 : (a = l - v, c = .5 < h ? a / (2 - l - v) : a / (l + v), s = l === e ? (u - o) / a + (u < o ? 6 : 0) : l === u ? (o - e) / a + 2 : (e - u) / a + 4, s *= 60), r[0] = ~~(s + .5), r[1] = ~~(100 * c + .5), r[2] = ~~(100 * h + .5)), i && r.length < 4 && (r[3] = 1), r
    }

    function ns(n) {
        var t = [],
            i = [],
            r = -1;
        return n.split(wt).forEach(function (n) {
            var u = n.match(wi) || [];
            t.push.apply(t, u);
            i.push(r += u.length + 1)
        }), t.c = i, t
    }

    function ts(n, t, i) {
        var h, f, s, o, e = "",
            u = (n + e).match(wt),
            c = t ? "hsla(" : "rgba(",
            r = 0;
        if (!u) return n;
        if (u = u.map(function (n) {
                return (n = go(n, t, 1)) && c + (t ? n[0] + "," + n[1] + "%," + n[2] + "%," + n[3] : n.join(",")) + ")"
            }), i && (s = ns(n), (h = i.c).join(e) !== s.c.join(e)))
            for (o = (f = n.replace(wt, "1").split(wi)).length - 1; r < o; r++) e += f[r] + (~h.indexOf(r) ? u.shift() || c + "0,0,0,0)" : (s.length ? s : u.length ? u : i).shift());
        if (!f)
            for (o = (f = n.split(wt)).length - 1; r < o; r++) e += f[r] + u[r];
        return e + f[o]
    }

    function is(n) {
        var t, i = n.join(" ");
        if (wt.lastIndex = 0, wt.test(i)) return t = ac.test(i), n[1] = ts(n[1], t), n[0] = ts(n[0], t, ns(n[1])), !0
    }

    function uc(n) {
        var r = (n + "").split("("),
            t = i[r[0]];
        return t && 1 < r.length && t.config ? t.config.apply(null, ~n.indexOf("{") ? [function (n) {
            for (var u, t, i, e = {}, f = n.substr(1, n.length - 3).split(":"), o = f[0], r = 1, s = f.length; r < s; r++) t = f[r], u = r !== s - 1 ? t.lastIndexOf(",") : t.length, i = t.substr(0, u), e[o] = isNaN(i) ? i.replace(yc, "").trim() : +i, o = t.substr(u + 1).trim();
            return e
        }(r[1])] : function (n) {
            var i = n.indexOf("(") + 1,
                t = n.indexOf(")"),
                r = n.indexOf("(", i);
            return n.substring(i, ~r && r < t ? n.indexOf(")", t + 1) : t)
        }(n).split(",").map(uo)) : i._CE && vc.test(n) ? i._CE("", n) : t
    }

    function uu(n, t) {
        for (var r, i = n._first; i;) i instanceof h ? uu(i, t) : !i.vars.yoyoEase || i._yoyo && i._repeat || i._yoyo === t || (i.timeline ? uu(i.timeline, t) : (r = i._ease, i._ease = i._yEase, i._yEase = r, i._yoyo = t)), i = i._next
    }

    function ui(n, t, r, u) {
        void 0 === r && (r = function (n) {
            return 1 - t(1 - n)
        });
        void 0 === u && (u = function (n) {
            return n < .5 ? t(2 * n) / 2 : 1 - t(2 * (1 - n)) / 2
        });
        var e, f = {
            easeIn: t,
            easeOut: r,
            easeInOut: u
        };
        return p(n, function (n) {
            for (var t in i[n] = k[n] = f, i[e = n.toLowerCase()] = r, f) i[e + ("easeIn" === t ? ".in" : "easeOut" === t ? ".out" : ".inOut")] = i[n + "." + t] = f[t]
        }), f
    }

    function rs(n) {
        return function (t) {
            return t < .5 ? (1 - n(1 - 2 * t)) / 2 : .5 + n(2 * (t - .5)) / 2
        }
    }

    function fu(n, t, i) {
        function u(n) {
            return 1 === n ? 1 : f * Math.pow(2, -10 * n) * sc((n - o) * r) + 1
        }
        var f = 1 <= t ? t : 1,
            r = (i || (n ? .3 : .45)) / (t < 1 ? t : 1),
            o = r / pf * (Math.asin(1 / f) || 0),
            e = "out" === n ? u : "in" === n ? function (n) {
                return 1 - u(1 - n)
            } : rs(u);
        return r = pf / r, e.config = function (t, i) {
            return fu(n, t, i)
        }, e
    }

    function eu(n, t) {
        function i(n) {
            return n ? --n * n * ((t + 1) * n + t) + 1 : 0
        }
        void 0 === t && (t = 1.70158);
        var r = "out" === n ? i : "in" === n ? function (n) {
            return 1 - i(1 - n)
        } : rs(i);
        return r.config = function (t) {
            return eu(n, t)
        }, r
    }

    function re(n) {
        var t, u, i, f, r = af() - yf,
            e = !0 === n;
        if (hu < r && (cu += r - vf), (0 < (t = (i = (yf += r) - cu) - hr) || e) && (f = ++fi.frame, lf = i - 1e3 * fi.time, fi.time = i /= 1e3, hr += t + (sr <= t ? 4 : sr - t), u = 1), e || (sf = hf(re)), u)
            for (yi = 0; yi < ei.length; yi++) ei[yi](i, lf, f, n)
    }

    function ps(n) {
        return n < es ? cr * n * n : n < .72727272727272729 ? cr * Math.pow(n - 1.5 / 2.75, 2) + .75 : n < .90909090909090917 ? cr * (n -= 2.25 / 2.75) * n + .9375 : cr * Math.pow(n - 2.625 / 2.75, 2) + .984375
    }

    function ws(n) {
        this.vars = n;
        this._delay = +n.delay || 0;
        (this._repeat = n.repeat === 1 / 0 ? -2 : n.repeat || 0) && (this._rDelay = n.repeatDelay || 0, this._yoyo = !!n.yoyo || !!n.yoyoEase);
        this._ts = 1;
        ai(this, +n.duration, 1, 1);
        this.data = n.data;
        su || rt.wake()
    }

    function bs(n, t, i, r, u, f) {
        var e, h, c, o;
        if (d[n] && !1 !== (e = new d[n]).init(u, e.rawVars ? t[n] : function (n, t, i, r, u) {
                if (s(n) && (n = vr(n, u, t, i, r)), !lt(n) || n.style && n.nodeType || b(n) || ss(n)) return l(n) ? vr(n, u, t, i, r) : n;
                var f, e = {};
                for (f in n) e[f] = vr(n[f], u, t, i, r);
                return e
            }(t[n], r, u, f, i), i, r, f) && (i._pt = h = new y(i._pt, u, n, 0, 1, e.render, e, 0, e.priority), i !== vi))
            for (c = i._ptLookup[i._targets.indexOf(u)], o = e._props.length; o--;) c[e._props[o]] = h;
        return e
    }

    function kc(n, t, i) {
        return n.setAttribute(t, i)
    }

    function dc(n, t, i, r) {
        r.mSet(n, t, r.m.call(r.tween, i, r.mt), r)
    }

    function ih(n, t, i, r, u, f, e, o, s) {
        this.t = t;
        this.s = r;
        this.c = u;
        this.p = i;
        this.r = f || gs;
        this.d = e || this;
        this.set = o || ee;
        this.pr = s || 0;
        (this._next = n) && (n._prev = this)
    }

    function el(n, t) {
        for (var i = n._pt; i && i.p !== t && i.op !== t && i.fp !== t;) i = i._next;
        return i
    }

    function he(n, t) {
        return {
            name: n,
            rawVars: 1,
            init: function (n, i, r) {
                r._onInit = function (n) {
                    var r, u;
                    if (l(i) && (r = {}, p(i, function (n) {
                            return r[n] = 1
                        }), i = r), t) {
                        for (u in r = {}, i) r[u] = t(i[u]);
                        i = r
                    }! function (n, t) {
                        var r, u, i, f = n._targets;
                        for (r in t)
                            for (u = f.length; u--;)(i = (i = n._ptLookup[u][r]) && i.d) && (i._pt && (i = el(i, r)), i && i.modifier && i.modifier(t[r], n, f[u], r))
                    }(n, i)
                }
            }
        }
    }

    function rh(n, t) {
        return t.set(t.t, t.p, Math.round(1e4 * (t.s + t.c * n)) / 1e4 + t.u, t)
    }

    function ol(n, t) {
        return t.set(t.t, t.p, 1 === n ? t.e : Math.round(1e4 * (t.s + t.c * n)) / 1e4 + t.u, t)
    }

    function sl(n, t) {
        return t.set(t.t, t.p, n ? Math.round(1e4 * (t.s + t.c * n)) / 1e4 + t.u : t.b, t)
    }

    function hl(n, t) {
        var i = t.s + t.c * n;
        t.set(t.t, t.p, ~~(i + (i < 0 ? -.5 : .5)) + t.u, t)
    }

    function uh(n, t) {
        return t.set(t.t, t.p, n ? t.e : t.b, t)
    }

    function fh(n, t) {
        return t.set(t.t, t.p, 1 !== n ? t.b : t.e, t)
    }

    function cl(n, t, i) {
        return n.style[t] = i
    }

    function ll(n, t, i) {
        return n.style.setProperty(t, i)
    }

    function al(n, t, i) {
        return n._gsap[t] = i
    }

    function vl(n, t, i) {
        return n._gsap.scaleX = n._gsap.scaleY = i
    }

    function yl(n, t, i, r, u) {
        var f = n._gsap;
        f.scaleX = f.scaleY = i;
        f.renderTransform(u, f)
    }

    function pl(n, t, i, r, u) {
        var f = n._gsap;
        f[t] = i;
        f.renderTransform(u, f)
    }

    function ce(n, t) {
        var i = dt.createElementNS ? dt.createElementNS((t || "http://www.w3.org/1999/xhtml").replace(/^https/, "http"), n) : dt.createElement(n);
        return i.style ? i : dt.createElement(n)
    }

    function st(n, t, i) {
        var r = getComputedStyle(n);
        return r[t] || r.getPropertyValue(t.replace(ph, "-$1").toLowerCase()) || r.getPropertyValue(t) || !i && st(n, fr(t) || t, 1) || ""
    }

    function le() {
        (function () {
            return "undefined" != typeof window
        })() && window.document && (ah = window, dt = ah.document, ir = dt.documentElement, oi = ce("div") || {
            style: {}
        }, ce("div"), c = fr(c), ti = c + "Origin", oi.style.cssText = "border-width:0;line-height:0;position:absolute;padding:0", yh = !!fr("perspective"), pe = 1)
    }

    function au(n) {
        var t, i = ce("svg", this.ownerSVGElement && this.ownerSVGElement.getAttribute("xmlns") || "http://www.w3.org/2000/svg"),
            r = this.parentNode,
            u = this.nextSibling,
            f = this.style.cssText;
        if (ir.appendChild(i), i.appendChild(this), this.style.display = "block", n) try {
            t = this.getBBox();
            this._gsapBBox = this.getBBox;
            this.getBBox = au
        } catch (n) {} else this._gsapBBox && (t = this._gsapBBox());
        return r && (u ? r.insertBefore(this, u) : r.appendChild(this)), ir.removeChild(i), this.style.cssText = f, t
    }

    function eh(n, t) {
        for (var i = t.length; i--;)
            if (n.hasAttribute(t[i])) return n.getAttribute(t[i])
    }

    function oh(n) {
        var t;
        try {
            t = n.getBBox()
        } catch (i) {
            t = au.call(n, !0)
        }
        return t && (t.width || t.height) || n.getBBox === au || (t = au.call(n, !0)), !t || t.width || t.x || t.y ? t : {
            x: +eh(n, ["x", "cx", "x1"]) || 0,
            y: +eh(n, ["y", "cy", "y1"]) || 0,
            width: 0,
            height: 0
        }
    }

    function sh(n) {
        return !(!n.getCTM || n.parentNode && !n.ownerSVGElement || !oh(n))
    }

    function pr(n, t) {
        if (t) {
            var i = n.style;
            t in gt && t !== ti && (t = c);
            i.removeProperty ? ("ms" !== t.substr(0, 2) && "webkit" !== t.substr(0, 6) || (t = "-" + t), i.removeProperty(t.replace(ph, "-$1").toLowerCase())) : i.removeAttribute(t)
        }
    }

    function kt(n, t, i, r, u, f) {
        var e = new y(n._pt, t, i, 0, 1, f ? fh : uh);
        return (n._pt = e).b = r, e.e = u, n._props.push(i), e
    }

    function at(n, i, r, u) {
        var o, f, s, v, e = parseFloat(r) || 0,
            h = (r + "").trim().substr((e + "").length) || "px",
            a = oi.style,
            c = ka.test(i),
            w = "svg" === n.tagName.toLowerCase(),
            y = (w ? "client" : "offset") + (c ? "Width" : "Height"),
            p = "px" === u,
            l = "%" === u;
        return u === h || !e || bh[u] || bh[h] ? e : ("px" === h || p || (e = at(n, i, r, "px")), v = n.getCTM && sh(n), !l && "%" !== h || !gt[i] && !~i.indexOf("adius") ? (a[c ? "width" : "height"] = 100 + (p ? h : u), f = ~i.indexOf("adius") || "em" === u && n.appendChild && !w ? n : n.parentNode, v && (f = (n.ownerSVGElement || {}).parentNode), f && f !== dt && f.appendChild || (f = dt.body), (s = f._gsap) && l && s.width && c && s.time === rt.time ? t(e / s.width * 100) : (!l && "%" !== h || (a.position = st(n, "position")), f === n && (a.position = "static"), f.appendChild(oi), o = oi[y], f.removeChild(oi), a.position = "absolute", c && l && ((s = ii(f)).time = rt.time, s.width = f[y]), t(p ? o * e / 100 : o && e ? 100 / o * e : 0))) : (o = v ? n.getBBox()[c ? "width" : "height"] : n[y], t(l ? e / o * 100 : e / 100 * o)))
    }

    function tr(n, t, i, r) {
        var u;
        return pe || le(), t in ni && "transform" !== t && ~(t = ni[t]).indexOf(",") && (t = t.split(",")[0]), gt[t] && "transform" !== t ? (u = br(n, r), u = "transformOrigin" !== t ? u[t] : u.svg ? u.origin : yu(st(n, ti)) + " " + u.zOrigin + "px") : (u = n.style[t]) && "auto" !== u && !r && !~(u + "").indexOf("calc(") || (u = vu[t] && vu[t](n, t, i) || st(n, t) || io(n, t) || ("opacity" === t ? 1 : 0)), i && !~(u + "").trim().indexOf(" ") ? at(n, t, u, i) + i : u
    }

    function wl(n, t, i, r) {
        var l, a;
        i && "none" !== i || (l = fr(t, n, 1), a = l && st(n, l, 1), a && a !== i ? (t = l, i = a) : "borderColor" === t && (i = st(n, "borderTopColor")));
        var b, k, nt, s, o, p, e, w, h, u, d, g, f = new y(this._pt, n.style, t, 0, 1, nh),
            c = 0,
            tt = 0;
        if (f.b = i, f.e = r, i += "", "auto" == (r += "") && (n.style[t] = r, r = st(n, t) || r, n.style[t] = i), is(b = [i, r]), r = b[1], nt = (i = b[0]).match(wi) || [], (r.match(wi) || []).length) {
            for (; k = wi.exec(r);) e = k[0], h = r.substring(c, k.index), o ? o = (o + 1) % 5 : "rgba(" !== h.substr(-5) && "hsla(" !== h.substr(-5) || (o = 1), e !== (p = nt[tt++] || "") && (s = parseFloat(p) || 0, d = p.substr((s + "").length), (g = "=" === e.charAt(1) ? +(e.charAt(0) + "1") : 0) && (e = e.substr(2)), w = parseFloat(e), u = e.substr((w + "").length), c = wi.lastIndex - u.length, u || (u = u || v.units[t] || d, c === r.length && (r += u, f.e += u)), d !== u && (s = at(n, t, p, u) || 0), f._pt = {
                _next: f._pt,
                p: h || 1 === tt ? h : ",",
                s: s,
                c: g ? g * w : w - s,
                m: o && o < 4 || "zIndex" === t ? Math.round : 0
            });
            f.c = c < r.length ? r.substring(c, r.length) : ""
        } else f.r = "display" === t && "none" === r ? fh : uh;
        return cs.test(r) && (f.e = 0), this._pt = f
    }

    function bl(n) {
        var r = n.split(" "),
            t = r[0],
            i = r[1] || "50%";
        return "top" !== t && "bottom" !== t && "left" !== i && "right" !== i || (n = t, t = i, i = n), r[0] = kh[t] || t, r[1] = kh[i] || i, r.join(" ")
    }

    function kl(n, t) {
        if (t.tween && t.tween._time === t.tween._dur) {
            var r, f, e, i = t.t,
                s = i.style,
                u = t.u,
                o = i._gsap;
            if ("all" === u || !0 === u) s.cssText = "", f = 1;
            else
                for (e = (u = u.split(",")).length; - 1 < --e;) r = u[e], gt[r] && (f = 1, r = "transformOrigin" === r ? ti : c), pr(i, r);
            f && (pr(i, c), o && (o.svg && i.removeAttribute("transform"), br(i, 1), o.uncache = 1))
        }
    }

    function hh(n) {
        return "matrix(1, 0, 0, 1, 0, 0)" === n || "none" === n || !n
    }

    function ch(n) {
        var i = st(n, c);
        return hh(i) ? wr : i.substr(7).match(hs).map(t)
    }

    function ae(n, t) {
        var u, f, r, o, s = n._gsap || ii(n),
            e = n.style,
            i = ch(n);
        return s.svg && n.getAttribute("transform") ? "1,0,0,1,0,0" === (i = [(r = n.transform.baseVal.consolidate().matrix).a, r.b, r.c, r.d, r.e, r.f]).join(",") ? wr : i : (i !== wr || n.offsetParent || n === ir || s.svg || (r = e.display, e.display = "block", (u = n.parentNode) && n.offsetParent || (o = 1, f = n.nextSibling, ir.appendChild(n)), i = ch(n), r ? e.display = r : pr(n, "display"), o && (f ? u.insertBefore(n, f) : u ? u.appendChild(n) : ir.removeChild(n))), t && 6 < i.length ? [i[0], i[1], i[4], i[5], i[12], i[13]] : i)
    }

    function ve(n, t, i, r, u, f) {
        var y, h, d, e = n._gsap,
            c = u || ae(n, !0),
            g = e.xOrigin || 0,
            nt = e.yOrigin || 0,
            tt = e.xOffset || 0,
            it = e.yOffset || 0,
            p = c[0],
            w = c[1],
            b = c[2],
            k = c[3],
            l = c[4],
            a = c[5],
            v = t.split(" "),
            o = parseFloat(v[0]) || 0,
            s = parseFloat(v[1]) || 0;
        i ? c !== wr && (h = p * k - w * b) && (d = o * (-w / h) + s * (p / h) - (p * a - w * l) / h, o = o * (k / h) + s * (-b / h) + (b * a - k * l) / h, s = d) : (o = (y = oh(n)).x + (~v[0].indexOf("%") ? o / 100 * y.width : o), s = y.y + (~(v[1] || v[0]).indexOf("%") ? s / 100 * y.height : s));
        r || !1 !== r && e.smooth ? (l = o - g, a = s - nt, e.xOffset = tt + (l * p + a * b) - l, e.yOffset = it + (l * w + a * k) - a) : e.xOffset = e.yOffset = 0;
        e.xOrigin = o;
        e.yOrigin = s;
        e.smooth = !!r;
        e.origin = t;
        e.originIsAbsolute = !!i;
        n.style[ti] = "0px 0px";
        f && (kt(f, e, "xOrigin", g, o), kt(f, e, "yOrigin", nt, s), kt(f, e, "xOffset", tt, e.xOffset), kt(f, e, "yOffset", it, e.yOffset));
        n.setAttribute("data-svg-origin", o + " " + s)
    }

    function ye(n, i, r) {
        var u = w(i);
        return t(parseFloat(i) + parseFloat(at(n, "x", r + "px", u))) + u
    }

    function dl(n, t, i, r, u, f) {
        var h, s, o = 360,
            c = l(u),
            a = parseFloat(u) * (c && ~u.indexOf("rad") ? si : 1),
            e = f ? a * f : a - r,
            v = r + e + "deg";
        return c && ("short" === (h = u.split("_")[1]) && (e %= o) != e % 180 && (e += e < 0 ? o : -o), "cw" === h && e < 0 ? e = (e + 36e9) % o - ~~(e / o) * o : "ccw" === h && 0 < e && (e = (e - 36e9) % o - ~~(e / o) * o)), n._pt = s = new y(n._pt, t, i, r, e, ol), s.e = v, s.u = "deg", n._props.push(i), s
    }

    function lh(n, t) {
        for (var i in t) n[i] = t[i];
        return n
    }

    function gl(n, t, i) {
        var f, u, r, e, o, a, s, h = lh({}, i._gsap),
            l = i.style;
        for (u in h.svg ? (r = i.getAttribute("transform"), i.setAttribute("transform", ""), l[c] = t, f = br(i, 1), pr(i, c), i.setAttribute("transform", r)) : (r = getComputedStyle(i)[c], l[c] = t, f = br(i, 1), l[c] = r), gt)(r = h[u]) !== (e = f[u]) && "perspective,force3D,transformOrigin,svgOrigin".indexOf(u) < 0 && (o = w(r) !== (s = w(e)) ? at(i, u, r, s) : parseFloat(r), a = parseFloat(e), n._pt = new y(n._pt, f, u, o, a - o, rh), n._pt.u = s || 0, n._props.push(u));
        lh(f, h)
    }
    var ff, e, et, ef, of , ou, us, fs, vi, su, sf, hf, cf, fi, lf, yi, af, hu, vf, cu, yf, sr, hr, ei, cr, es, v = {
            autoSleep: 120,
            force3D: "auto",
            nullTargetWarn: 1,
            units: {
                lineHeight: ""
            }
        },
        pi = {
            duration: .5,
            overwrite: !1,
            delay: 0
        },
        ot = 1e8,
        u = 1 / ot,
        pf = 2 * Math.PI,
        fc = pf / 4,
        ec = 0,
        os = Math.sqrt,
        oc = Math.cos,
        sc = Math.sin,
        ss = "function" == typeof ArrayBuffer && ArrayBuffer.isView || function () {},
        b = Array.isArray,
        wf = /(?:-?\.?\d|\.)+/gi,
        hs = /[-+=.]*\d+[.e\-+]*\d*[e\-+]*\d*/g,
        wi = /[-+=.]*\d+[.e-]*\d*[a-z%]*/g,
        bf = /[-+=.]*\d+\.?\d*(?:e-|e\+)?\d*/gi,
        cs = /[+-]=-?[.\d]+/,
        ls = /[^,'"\[\]\s]+/gi,
        hc = /[\d.+\-=]+(?:e[-+]\d*)*/i,
        k = {},
        kf = {},
        pt = [],
        df = {},
        d = {},
        gf = {},
        as = 30,
        lu = [],
        ne = "",
        bi = function (n, t) {
            for (var i in t) n[i] = t[i];
            return n
        },
        ki = function (n, t) {
            var i = Math.floor(n /= t);
            return n && i === n ? i - 1 : i
        },
        te = function (n) {
            var t = n.data;
            return "isFromStart" === t || "isStart" === t
        },
        cc = {
            _start: 0,
            endTime: li,
            totalDuration: li
        },
        nt = function lc(n, t, i) {
            var u, r, o, e = n.labels,
                f = n._recent || cc,
                s = n.duration() >= ot ? f.endTime(!1) : n._dur;
            return l(t) && (isNaN(t) || t in e) ? (r = t.charAt(0), o = "%" === t.substr(-1), u = t.indexOf("="), "<" === r || ">" === r ? (0 <= u && (t = t.replace(/=/, "")), ("<" === r ? f._start : f.endTime(0 <= f._repeat)) + (parseFloat(t.substr(1)) || 0) * (o ? (u < 0 ? f : i).totalDuration() / 100 : 1)) : u < 0 ? (t in e || (e[t] = s), e[t]) : (r = parseFloat(t.charAt(u - 1) + t.substr(u + 1)), o && i && (r = r / 100 * (b(i) ? i[0] : i).totalDuration()), 1 < u ? lc(n, t.substr(0, u - 1), i) + r : s + r)) : null == t ? s : +t
        },
        lr = function (n, t, i) {
            return i < n ? n : t < i ? t : i
        },
        ie = [].slice,
        tt = function (n, t, i) {
            return !l(n) || i || !ef && di() ? b(n) ? function (n, t, i) {
                return void 0 === i && (i = []), n.forEach(function (n) {
                    return l(n) && !t || ao(n, 1) ? i.push.apply(i, tt(n)) : i.push(n)
                }) || i
            }(n, i) : ao(n) ? ie.call(n, 0) : n ? [n] : [] : ie.call((t || of ).querySelectorAll(n), 0)
        },
        vs = function (n, t, i, r, u) {
            var f = t - n,
                e = r - i;
            return yt(u, function (t) {
                return i + ((t - n) / f * e || 0)
            })
        },
        it = function (n, t, i) {
            var r, u, f = n.vars,
                e = f[t];
            if (e) return r = f[t + "Params"], u = f.callbackScope || n, i && pt.length && gr(), r ? e.apply(u, r) : e.call(u)
        },
        f = 255,
        ar = {
            aqua: [0, f, f],
            lime: [0, f, 0],
            silver: [192, 192, 192],
            black: [0, 0, 0],
            maroon: [128, 0, 0],
            teal: [0, 128, 128],
            blue: [0, 0, f],
            navy: [0, 0, 128],
            white: [f, f, f],
            olive: [128, 128, 0],
            yellow: [f, f, 0],
            orange: [f, 165, 0],
            gray: [128, 128, 128],
            purple: [128, 0, 128],
            green: [0, 128, 0],
            red: [f, 0, 0],
            pink: [f, 192, 203],
            cyan: [0, f, f],
            transparent: [f, f, f, 0]
        },
        wt = function () {
            var n, t = "(?:\\b(?:(?:rgb|rgba|hsl|hsla)\\(.+?\\))|\\B#(?:[0-9a-f]{3,4}){1,2}\\b";
            for (n in ar) t += "|" + n + "\\b";
            return new RegExp(t + ")", "gi")
        }(),
        ac = /hsl[a]?\(/,
        rt = (af = Date.now, hu = 500, vf = 33, cu = af(), yf = cu, hr = sr = 1e3 / 240, fi = {
            time: 0,
            frame: 0,
            tick: function () {
                re(!0)
            },
            deltaRatio: function (n) {
                return lf / (1e3 / (n || 60))
            },
            wake: function () {
                us && (!ef && de() && (et = ef = window, of = et.document || {}, k.gsap = ut, (et.gsapVersions || (et.gsapVersions = [])).push(ut.version), no(ou || et.GreenSockGlobals || !et.gsap && et || {}), cf = et.requestAnimationFrame), sf && fi.sleep(), hf = cf || function (n) {
                    return setTimeout(n, hr - 1e3 * fi.time + 1 | 0)
                }, su = 1, re(2))
            },
            sleep: function () {
                (cf ? et.cancelAnimationFrame : clearTimeout)(sf);
                su = 0;
                hf = li
            },
            lagSmoothing: function (n, t) {
                hu = n || 1e8;
                vf = Math.min(t, hu, 0)
            },
            fps: function (n) {
                sr = 1e3 / (n || 240);
                hr = 1e3 * fi.time + sr
            },
            add: function (n) {
                ei.indexOf(n) < 0 && ei.push(n);
                di()
            },
            remove: function (n) {
                var t;
                ~(t = ei.indexOf(n)) && ei.splice(t, 1) && t <= yi && yi--
            },
            _listeners: ei = []
        }),
        di = function () {
            return !su && rt.wake()
        },
        i = {},
        vc = /^[\d.\-M][\d.\-,\s]/,
        yc = /["']/g,
        ys = function (n) {
            return function (t) {
                return 1 - n(1 - t)
            }
        },
        gi = function (n, t) {
            return n && (s(n) ? n : i[n] || uc(n)) || t
        },
        r, ue, nr, h, yr, ut, nc, we, be, pu, wu, tc;
    p("Linear,Quad,Cubic,Quart,Quint,Strong", function (n, t) {
        var i = t < 5 ? t + 1 : t;
        ui(n + ",Power" + (i - 1), t ? function (n) {
            return Math.pow(n, i)
        } : function (n) {
            return n
        }, function (n) {
            return 1 - Math.pow(1 - n, i)
        }, function (n) {
            return n < .5 ? Math.pow(2 * n, i) / 2 : 1 - Math.pow(2 * (1 - n), i) / 2
        })
    });
    i.Linear.easeNone = i.none = i.Linear.easeIn;
    ui("Elastic", fu("in"), fu("out"), fu());
    cr = 7.5625;
    es = 1 / 2.75;
    ui("Bounce", function (n) {
        return 1 - ps(1 - n)
    }, ps);
    ui("Expo", function (n) {
        return n ? Math.pow(2, 10 * (n - 1)) : 0
    });
    ui("Circ", function (n) {
        return -(os(1 - n * n) - 1)
    });
    ui("Sine", function (n) {
        return 1 === n ? 1 : 1 - oc(n * fc)
    });
    ui("Back", eu("in"), eu("out"), eu());
    i.SteppedEase = i.steps = k.SteppedEase = {
        config: function (n, t) {
            void 0 === n && (n = 1);
            var i = 1 / n,
                r = n + (t ? 0 : 1),
                u = t ? 1 : 0;
            return function (n) {
                return ((r * lr(0, .99999999, n) | 0) + u) * i
            }
        }
    };
    pi.ease = i["quad.out"];
    p("onComplete,onUpdate,onStart,onRepeat,onReverseComplete,onInterrupt", function (n) {
        return ne += n + "," + n + "Params,"
    });
    ue = function (n, t) {
        this.id = ec++;
        (n._gsap = this).target = n;
        this.harness = t;
        this.get = t ? t.get : io;
        this.set = t ? t.getSetter : oe
    };
    nr = ((r = ws.prototype).delay = function (n) {
        return n || 0 === n ? (this.parent && this.parent.smoothChildTiming && this.startTime(this._start + n - this._delay), this._delay = n, this) : this._delay
    }, r.duration = function (n) {
        return arguments.length ? this.totalDuration(0 < this._repeat ? n + (n + this._rDelay) * this._repeat : n) : this.totalDuration() && this._dur
    }, r.totalDuration = function (n) {
        return arguments.length ? (this._dirty = 0, ai(this, this._repeat < 0 ? n : (n - this._repeat * this._rDelay) / (this._repeat + 1))) : this._tDur
    }, r.totalTime = function (n, t) {
        if (di(), !arguments.length) return this._tTime;
        var i = this._dp;
        if (i && i.smoothChildTiming && this._ts) {
            for (oo(this, n), !i._dp || i.parent || so(i, this); i.parent;) i.parent._time !== i._start + (0 <= i._ts ? i._tTime / i._ts : (i.totalDuration() - i._tTime) / -i._ts) && i.totalTime(i._tTime, !0), i = i.parent;
            !this.parent && this._dp.autoRemoveChildren && (0 < this._ts && n < this._tDur || this._ts < 0 && 0 < n || !this._tDur && !n) && ft(this._dp, this, this._start - this._delay)
        }
        return (this._tTime !== n || !this._dur && !t || this._initted && Math.abs(this._zTime) === u || !n && !this._initted && (this.add || this._ptLookup)) && (this._ts || (this._pTime = n), ro(this, n, t)), this
    }, r.time = function (n, t) {
        return arguments.length ? this.totalTime(Math.min(this.totalDuration(), n + eo(this)) % (this._dur + this._rDelay) || (n ? this._dur : 0), t) : this._time
    }, r.totalProgress = function (n, t) {
        return arguments.length ? this.totalTime(this.totalDuration() * n, t) : this.totalDuration() ? Math.min(1, this._tTime / this._tDur) : this.ratio
    }, r.progress = function (n, t) {
        return arguments.length ? this.totalTime(this.duration() * (!this._yoyo || 1 & this.iteration() ? n : 1 - n) + eo(this), t) : this.duration() ? Math.min(1, this._time / this._dur) : this.ratio
    }, r.iteration = function (n, t) {
        var i = this.duration() + this._rDelay;
        return arguments.length ? this.totalTime(this._time + (n - 1) * i, t) : this._repeat ? ki(this._tTime, i) + 1 : 1
    }, r.timeScale = function (n) {
        if (!arguments.length) return this._rts === -u ? 0 : this._rts;
        if (this._rts === n) return this;
        var t = this.parent && this._ts ? iu(this.parent._time, this) : this._tTime;
        return this._rts = +n || 0, this._ts = this._ps || n === -u ? 0 : this._rts,
            function (n) {
                for (var t = n.parent; t && t.parent;) t._dirty = 1, t.totalDuration(), t = t.parent;
                return n
            }(this.totalTime(lr(-this._delay, this._tDur, t), !0))
    }, r.paused = function (n) {
        return arguments.length ? (this._ps !== n && ((this._ps = n) ? (this._pTime = this._tTime || Math.max(-this._delay, this.rawTime()), this._ts = this._act = 0) : (di(), this._ts = this._rts, this.totalTime(this.parent && !this.parent.smoothChildTiming ? this.rawTime() : this._tTime || this._pTime, 1 === this.progress() && Math.abs(this._zTime) !== u && (this._tTime -= u)))), this) : this._ps
    }, r.startTime = function (n) {
        if (arguments.length) {
            this._start = n;
            var t = this.parent || this._dp;
            return !t || !t._sort && this.parent || ft(t, this, n - this._delay), this
        }
        return this._start
    }, r.endTime = function (n) {
        return this._start + (a(n) ? this.totalDuration() : this.duration()) / Math.abs(this._ts)
    }, r.rawTime = function (n) {
        var t = this.parent || this._dp;
        return t ? n && (!this._ts || this._repeat && this._time && this.totalProgress() < 1) ? this._tTime % (this._dur + this._rDelay) : this._ts ? iu(t.rawTime(n), this) : this._tTime : this._tTime
    }, r.globalTime = function (n) {
        for (var t = this, i = arguments.length ? n : t.rawTime(); t;) i = t._start + i / (t._ts || 1), t = t._dp;
        return i
    }, r.repeat = function (n) {
        return arguments.length ? (this._repeat = n === 1 / 0 ? -2 : n, lo(this)) : -2 === this._repeat ? 1 / 0 : this._repeat
    }, r.repeatDelay = function (n) {
        if (arguments.length) {
            var t = this._time;
            return this._rDelay = n, lo(this), t ? this.time(t) : this
        }
        return this._rDelay
    }, r.yoyo = function (n) {
        return arguments.length ? (this._yoyo = n, this) : this._yoyo
    }, r.seek = function (n, t) {
        return this.totalTime(nt(this, n), a(t))
    }, r.restart = function (n, t) {
        return this.play().totalTime(n ? -this._delay : 0, a(t))
    }, r.play = function (n, t) {
        return null != n && this.seek(n, t), this.reversed(!1).paused(!1)
    }, r.reverse = function (n, t) {
        return null != n && this.seek(n || this.totalDuration(), t), this.reversed(!0).paused(!1)
    }, r.pause = function (n, t) {
        return null != n && this.seek(n, t), this.paused(!0)
    }, r.resume = function () {
        return this.paused(!1)
    }, r.reversed = function (n) {
        return arguments.length ? (!!n !== this.reversed() && this.timeScale(-this._rts || (n ? -u : 0)), this) : this._rts < 0
    }, r.invalidate = function () {
        return this._initted = this._act = 0, this._zTime = -u, this
    }, r.isActive = function () {
        var t, n = this.parent || this._dp,
            i = this._start;
        return !(n && !(this._ts && this._initted && n.isActive() && (t = n.rawTime(!0)) >= i && t < this.endTime(!0) - u))
    }, r.eventCallback = function (n, t, i) {
        var r = this.vars;
        return 1 < arguments.length ? (t ? (r[n] = t, i && (r[n + "Params"] = i), "onUpdate" === n && (this._onUpdate = t)) : delete r[n], this) : r[n]
    }, r.then = function (n) {
        var t = this;
        return new Promise(function (i) {
            function u() {
                var n = t.then;
                t.then = null;
                s(r) && (r = r(t)) && (r.then || r === t) && (t.then = n);
                i(r);
                t.then = n
            }
            var r = s(n) ? n : fo;
            t._initted && 1 === t.totalProgress() && 0 <= t._ts || !t._tTime && t._ts < 0 ? u() : t._prom = u
        })
    }, r.kill = function () {
        or(this)
    }, ws);
    g(nr.prototype, {
        _time: 0,
        _start: 0,
        _end: 0,
        _tTime: 0,
        _tDur: 0,
        _dirty: 0,
        _repeat: 0,
        _yoyo: !1,
        parent: null,
        _initted: !1,
        _rDelay: 0,
        _ts: 1,
        _dp: 0,
        ratio: 0,
        _zTime: -u,
        _prom: 0,
        _ps: !1,
        _rts: 1
    });
    h = function (n) {
        function r(t, i) {
            var r;
            return void 0 === t && (t = {}), (r = n.call(this, t) || this).labels = {}, r.smoothChildTiming = !!t.smoothChildTiming, r.autoRemoveChildren = !!t.autoRemoveChildren, r._sort = a(t.sortChildren), e && ft(t.parent || e, ht(r), i), t.reversed && r.reverse(), t.paused && r.paused(!0), t.scrollTrigger && ho(ht(r), t.scrollTrigger), r
        }
        ke(r, n);
        var i = r.prototype;
        return i.to = function () {
            return er(0, arguments, this), this
        }, i.from = function () {
            return er(1, arguments, this), this
        }, i.fromTo = function () {
            return er(2, arguments, this), this
        }, i.set = function (n, t, i) {
            return t.duration = 0, t.parent = this, nu(t).repeatDelay || (t.repeat = 0), t.immediateRender = !!t.immediateRender, new o(n, t, nt(this, i), 1), this
        }, i.call = function (n, t, i) {
            return ft(this, o.delayedCall(0, n, t), i)
        }, i.staggerTo = function (n, t, i, r, u, f, e) {
            return i.duration = t, i.stagger = i.stagger || r, i.onComplete = f, i.onCompleteParams = e, i.parent = this, new o(n, i, nt(this, u)), this
        }, i.staggerFrom = function (n, t, i, r, u, f, e) {
            return i.runBackwards = 1, nu(i).immediateRender = a(i.immediateRender), this.staggerTo(n, t, i, r, u, f, e)
        }, i.staggerFromTo = function (n, t, i, r, u, f, e, o) {
            return r.startAt = i, nu(r).immediateRender = a(r.immediateRender), this.staggerTo(n, t, r, u, f, e, o)
        }, i.render = function (n, i, r) {
            var o, f, p, l, y, k, v, tt, rt, w, nt, d, s = this._time,
                a = this._dirty ? this.totalDuration() : this._tDur,
                c = this._dur,
                h = this !== e && a - u < n && 0 <= n ? a : n < u ? 0 : n,
                ut = this._zTime < 0 != n < 0 && (this._initted || !c),
                b, ft, g;
            if (h !== this._tTime || r || ut) {
                if (s !== this._time && c && (h += this._time - s, n += this._time - s), o = h, rt = this._start, k = !(tt = this._ts), ut && (c || (s = this._zTime), !n && i || (this._zTime = n)), this._repeat) {
                    if (nt = this._yoyo, y = c + this._rDelay, this._repeat < -1 && n < 0) return this.totalTime(100 * y + n, i, r);
                    if (o = t(h % y), h === a ? (l = this._repeat, o = c) : ((l = ~~(h / y)) && l === h / y && (o = c, l--), c < o && (o = c)), w = ki(this._tTime, y), !s && this._tTime && w !== l && (w = l), nt && 1 & l && (o = c - o, d = 1), l !== w && !this._lock) {
                        if ((b = nt && 1 & w, ft = b === (nt && 1 & l), l < w && (b = !b), s = b ? 0 : c, this._lock = 1, this.render(s || (d ? 0 : t(l * y)), i, !c)._lock = 0, this._tTime = h, !i && this.parent && it(this, "onRepeat"), this.vars.repeatRefresh && !d && (this.invalidate()._lock = 1), s && s !== this._time || k != !this._ts || this.vars.onRepeat && !this.parent && !this._act) || (c = this._dur, a = this._tDur, ft && (this._lock = 2, s = b ? c : -.0001, this.render(s, !0), this.vars.repeatRefresh && !d && this.invalidate()), this._lock = 0, !this._ts && !k)) return this;
                        uu(this, d)
                    }
                }
                if (this._hasPause && !this._forcing && this._lock < 2 && (v = function (n, t, i) {
                        var r;
                        if (t < i)
                            for (r = n._first; r && r._start <= i;) {
                                if (!r._dur && "isPause" === r.data && r._start > t) return r;
                                r = r._next
                            } else
                                for (r = n._last; r && r._start >= i;) {
                                    if (!r._dur && "isPause" === r.data && r._start < t) return r;
                                    r = r._prev
                                }
                    }(this, t(s), t(o))) && (h -= o - (o = v._start)), this._tTime = h, this._time = o, this._act = !tt, this._initted || (this._onUpdate = this.vars.onUpdate, this._initted = 1, this._zTime = n, s = 0), !s && o && !i && (it(this, "onStart"), this._tTime !== h)) return this;
                if (s <= o && 0 <= n)
                    for (f = this._first; f;) {
                        if (p = f._next, (f._act || o >= f._start) && f._ts && v !== f) {
                            if (f.parent !== this) return this.render(n, i, r);
                            if (f.render(0 < f._ts ? (o - f._start) * f._ts : (f._dirty ? f.totalDuration() : f._tDur) + (o - f._start) * f._ts, i, r), o !== this._time || !this._ts && !k) {
                                v = 0;
                                p && (h += this._zTime = -u);
                                break
                            }
                        }
                        f = p
                    } else
                        for (f = this._last, g = n < 0 ? n : o; f;) {
                            if (p = f._prev, (f._act || g <= f._end) && f._ts && v !== f) {
                                if (f.parent !== this) return this.render(n, i, r);
                                if (f.render(0 < f._ts ? (g - f._start) * f._ts : (f._dirty ? f.totalDuration() : f._tDur) + (g - f._start) * f._ts, i, r), o !== this._time || !this._ts && !k) {
                                    v = 0;
                                    p && (h += this._zTime = g ? -u : u);
                                    break
                                }
                            }
                            f = p
                        }
                if (v && !i && (this.pause(), v.render(s <= o ? 0 : -u)._zTime = s <= o ? 1 : -1, this._ts)) return this._start = rt, tf(this), this.render(n, i, r);
                this._onUpdate && !i && it(this, "onUpdate", !0);
                (h === a && a >= this.totalDuration() || !h && s) && (rt !== this._start && Math.abs(tt) === Math.abs(this._ts) || this._lock || (!n && c || !(h === a && 0 < this._ts || !h && this._ts < 0) || vt(this, 1), i || n < 0 && !s || !h && !s && a || (it(this, h === a && 0 <= n ? "onComplete" : "onReverseComplete", !0), !this._prom || h < a && 0 < this.timeScale() || this._prom())))
            }
            return this
        }, i.add = function (n, t) {
            var i = this;
            if (ct(t) || (t = nt(this, t, n)), !(n instanceof nr)) {
                if (b(n)) return n.forEach(function (n) {
                    return i.add(n, t)
                }), this;
                if (l(n)) return this.addLabel(n, t);
                if (!s(n)) return this;
                n = o.delayedCall(0, n)
            }
            return this !== n ? ft(this, n, t) : this
        }, i.getChildren = function (n, t, i, r) {
            void 0 === n && (n = !0);
            void 0 === t && (t = !0);
            void 0 === i && (i = !0);
            void 0 === r && (r = -ot);
            for (var f = [], u = this._first; u;) u._start >= r && (u instanceof o ? t && f.push(u) : (i && f.push(u), n && f.push.apply(f, u.getChildren(!0, t, i)))), u = u._next;
            return f
        }, i.getById = function (n) {
            for (var t = this.getChildren(1, 1, 1), i = t.length; i--;)
                if (t[i].vars.id === n) return t[i]
        }, i.remove = function (n) {
            return l(n) ? this.removeLabel(n) : s(n) ? this.killTweensOf(n) : (tu(this, n), n === this._recent && (this._recent = this._last), ri(this))
        }, i.totalTime = function (i, r) {
            return arguments.length ? (this._forcing = 1, !this._dp && this._ts && (this._start = t(rt.time - (0 < this._ts ? i / this._ts : (this.totalDuration() - i) / -this._ts))), n.prototype.totalTime.call(this, i, r), this._forcing = 0, this) : this._tTime
        }, i.addLabel = function (n, t) {
            return this.labels[n] = nt(this, t), this
        }, i.removeLabel = function (n) {
            return delete this.labels[n], this
        }, i.addPause = function (n, t, i) {
            var r = o.delayedCall(0, t || li, i);
            return r.data = "isPause", this._hasPause = 1, ft(this, r, nt(this, n))
        }, i.removePause = function (n) {
            var t = this._first;
            for (n = nt(this, n); t;) t._start === n && "isPause" === t.data && vt(t), t = t._next
        }, i.killTweensOf = function (n, t, i) {
            for (var r = this.getTweensOf(n, i), u = r.length; u--;) bt !== r[u] && r[u].kill(n, t);
            return this
        }, i.getTweensOf = function (n, t) {
            for (var u, r = [], f = tt(n), i = this._first, e = ct(t); i;) i instanceof o ? ic(i._targets, f) && (e ? (!bt || i._initted && i._ts) && i.globalTime(0) <= t && i.globalTime(i.totalDuration()) > t : !t || i.isActive()) && r.push(i) : (u = i.getTweensOf(f, t)).length && r.push.apply(r, u), i = i._next;
            return r
        }, i.tweenTo = function (n, t) {
            t = t || {};
            var s, i = this,
                e = nt(i, n),
                r = t.startAt,
                h = t.onStart,
                c = t.onStartParams,
                l = t.immediateRender,
                f = o.to(i, g({
                    ease: t.ease || "none",
                    lazy: !1,
                    immediateRender: !1,
                    time: e,
                    overwrite: "auto",
                    duration: t.duration || Math.abs((e - (r && "time" in r ? r.time : i._time)) / i.timeScale()) || u,
                    onStart: function () {
                        if (i.pause(), !s) {
                            var n = t.duration || Math.abs((e - (r && "time" in r ? r.time : i._time)) / i.timeScale());
                            f._dur !== n && ai(f, n, 0, 1).render(f._time, !0, !0);
                            s = 1
                        }
                        h && h.apply(f, c || [])
                    }
                }, t));
            return l ? f.render(0) : f
        }, i.tweenFromTo = function (n, t, i) {
            return this.tweenTo(t, g({
                startAt: {
                    time: nt(this, n)
                }
            }, i))
        }, i.recent = function () {
            return this._recent
        }, i.nextLabel = function (n) {
            return void 0 === n && (n = this._time), ko(this, nt(this, n))
        }, i.previousLabel = function (n) {
            return void 0 === n && (n = this._time), ko(this, nt(this, n), 1)
        }, i.currentLabel = function (n) {
            return arguments.length ? this.seek(n, !0) : this.previousLabel(this._time + u)
        }, i.shiftChildren = function (n, t, i) {
            void 0 === i && (i = 0);
            for (var u, r = this._first, f = this.labels; r;) r._start >= i && (r._start += n, r._end += n), r = r._next;
            if (t)
                for (u in f) f[u] >= i && (f[u] += n);
            return ri(this)
        }, i.invalidate = function () {
            var t = this._first;
            for (this._lock = 0; t;) t.invalidate(), t = t._next;
            return n.prototype.invalidate.call(this)
        }, i.clear = function (n) {
            void 0 === n && (n = !0);
            for (var i, t = this._first; t;) i = t._next, this.remove(t), t = i;
            return this._dp && (this._time = this._tTime = this._pTime = 0), n && (this.labels = {}), ri(this)
        }, i.totalDuration = function (n) {
            var s, r, f, u = 0,
                t = this,
                i = t._last,
                o = ot;
            if (arguments.length) return t.timeScale((t._repeat < 0 ? t.duration() : t.totalDuration()) / (t.reversed() ? -n : n));
            if (t._dirty) {
                for (f = t.parent; i;) s = i._prev, i._dirty && i.totalDuration(), o < (r = i._start) && t._sort && i._ts && !t._lock ? (t._lock = 1, ft(t, i, r - i._delay, 1)._lock = 0) : o = r, r < 0 && i._ts && (u -= r, (!f && !t._dp || f && f.smoothChildTiming) && (t._start += r / t._ts, t._time -= r, t._tTime -= r), t.shiftChildren(-r, !1, -Infinity), o = 0), i._end > u && i._ts && (u = i._end), i = s;
                ai(t, t === e && t._time > u ? t._time : u, 1, 1);
                t._dirty = 0
            }
            return t._tDur
        }, r.updateRoot = function (n) {
            if (e._ts && (ro(e, iu(n, e)), fs = rt.frame), rt.frame >= as) {
                as += v.autoSleep || 120;
                var t = e._first;
                if ((!t || !t._ts) && v.autoSleep && rt._listeners.length < 2) {
                    for (; t && !t._ts;) t = t._next;
                    t || rt.sleep()
                }
            }
        }, r
    }(nr);
    g(h.prototype, {
        _lock: 0,
        _hasPause: 0,
        _forcing: 0
    });
    var bt, fe = function (n, t, i, r, u, f, e, o, h) {
            s(r) && (r = r(u || 0, n, f));
            var c, p = n[t],
                a = "get" !== i ? i : s(p) ? h ? n[t.indexOf("set") || !s(n["get" + t.substr(3)]) ? t : "get" + t.substr(3)](h) : n[t]() : p,
                b = s(p) ? h ? gc : ds : ee;
            if (l(r) && (~r.indexOf("random(") && (r = ru(r)), "=" === r.charAt(1) && (!(c = parseFloat(a) + parseFloat(r.substr(2)) * ("-" === r.charAt(0) ? -1 : 1) + (w(a) || 0)) && 0 !== c || (r = c))), a !== r) return isNaN(a * r) || "" === r ? (p || t in n || ku(t, r), function (n, t, i, r, u, f, e) {
                var a, v, s, h, c, p, k, w, o = new y(this._pt, n, t, 0, 1, nh, null, u),
                    l = 0,
                    b = 0;
                for (o.b = i, o.e = r, i += "", (k = ~(r += "").indexOf("random(")) && (r = ru(r)), f && (f(w = [i, r], n, t), i = w[0], r = w[1]), v = i.match(bf) || []; a = bf.exec(r);) h = a[0], c = r.substring(l, a.index), s ? s = (s + 1) % 5 : "rgba(" === c.substr(-5) && (s = 1), h !== v[b++] && (p = parseFloat(v[b - 1]) || 0, o._pt = {
                    _next: o._pt,
                    p: c || 1 === b ? c : ",",
                    s: p,
                    c: "=" === h.charAt(1) ? parseFloat(h.substr(2)) * ("-" === h.charAt(0) ? -1 : 1) : parseFloat(h) - p,
                    m: s && s < 4 ? Math.round : 0
                }, l = bf.lastIndex);
                return o.c = l < r.length ? r.substring(l, r.length) : "", o.fp = e, (cs.test(r) || k) && (o.e = 0), this._pt = o
            }.call(this, n, t, a, r, b, o || v.stringFilter, h)) : (c = new y(this._pt, n, t, +a || 0, r - (a || 0), "boolean" == typeof p ? nl : gs, 0, b), h && (c.fp = h), e && c.modifier(e, this, n), this._pt = c)
        },
        pc = function wc(n, t) {
            var l, r, f, ot, c, st, ht, v, s, it, rt, k, at, i = n.vars,
                ut = i.ease,
                yt = i.startAt,
                p = i.immediateRender,
                w = i.lazy,
                wt = i.onUpdate,
                kt = i.onUpdateParams,
                dt = i.callbackScope,
                gt = i.runBackwards,
                b = i.yoyoEase,
                ni = i.keyframes,
                ft = i.autoRevert,
                et = n._dur,
                ct = n._startAt,
                h = n._targets,
                nt = n.parent,
                tt = nt && "nested" === nt.data ? nt.parent._targets : h,
                ti = "auto" === n._overwrite && !ff,
                lt = n.timeline;
            if (!lt || ni && ut || (ut = "none"), n._ease = gi(ut, pi.ease), n._yEase = b ? ys(gi(!0 === b ? ut : b, pi.ease)) : 0, b && n._yoyo && !n._repeat && (b = n._yEase, n._yEase = n._ease, n._ease = b), n._from = !lt && !!i.runBackwards, !lt) {
                if (k = (v = h[0] ? ii(h[0]).harness : 0) && i[v.prop], l = nf(i, kf), ct && ct.render(-1, !0).kill(), yt)
                    if (vt(n._startAt = o.set(h, g({
                            data: "isStart",
                            overwrite: !1,
                            parent: nt,
                            immediateRender: !0,
                            lazy: a(w),
                            startAt: null,
                            delay: 0,
                            onUpdate: wt,
                            onUpdateParams: kt,
                            callbackScope: dt,
                            stagger: 0
                        }, yt))), t < 0 && !p && !ft && n._startAt.render(-1, !0), p) {
                        if (0 < t && !ft && (n._startAt = 0), et && t <= 0) return void(t && (n._zTime = t))
                    } else !1 === ft && (n._startAt = 0);
                else if (gt && et)
                    if (ct) ft || (n._startAt = 0);
                    else if (t && (p = !1), f = g({
                        overwrite: !1,
                        data: "isFromStart",
                        lazy: p && a(w),
                        immediateRender: p,
                        stagger: 0,
                        parent: nt
                    }, l), k && (f[v.prop] = k), vt(n._startAt = o.set(h, f)), t < 0 && n._startAt.render(-1, !0), p) {
                    if (!t) return
                } else wc(n._startAt, u);
                for (n._pt = 0, w = et && a(w) || w && !et, r = 0; r < h.length; r++) {
                    if (ht = (c = h[r])._gsap || du(h)[r]._gsap, n._ptLookup[r] = it = {}, df[ht.id] && pt.length && gr(), rt = tt === h ? r : tt.indexOf(c), v && !1 !== (s = new v).init(c, k || l, n, rt, tt) && (n._pt = ot = new y(n._pt, c, s.name, 0, 1, s.render, s, 0, s.priority), s._props.forEach(function (n) {
                            it[n] = ot
                        }), s.priority && (st = 1)), !v || k)
                        for (f in l) d[f] && (s = bs(f, l, n, rt, c, tt)) ? s.priority && (st = 1) : it[f] = ot = fe.call(n, c, f, "get", l[f], rt, tt, 0, i.stringFilter);
                    n._op && n._op[r] && n.kill(c, n._op[r]);
                    ti && n._pt && (bt = n, e.killTweensOf(c, it, n.globalTime(0)), at = !n.parent, bt = 0);
                    n._pt && w && (df[ht.id] = 1)
                }
                st && th(n);
                n._onInit && n._onInit(n)
            }
            n._onUpdate = wt;
            n._initted = (!n._op || n._pt) && !at
        },
        vr = function (n, t, i, r, u) {
            return s(n) ? n.call(t, i, r, u) : l(n) && ~n.indexOf("random(") ? ru(n) : n
        },
        ks = ne + "repeat,repeatDelay,yoyo,repeatRefresh,yoyoEase",
        bc = (ks + ",id,stagger,delay,duration,paused,scrollTrigger").split(","),
        o = function (n) {
            function i(i, r, f, o) {
                var s;
                "number" == typeof r && (f.duration = r, r = f, f = null);
                var l, d, c, ot, k, rt, at, et, p = (s = n.call(this, o ? r : nu(r)) || this).vars,
                    nt = p.duration,
                    it = p.delay,
                    vt = p.immediateRender,
                    w = p.stagger,
                    yt = p.overwrite,
                    ut = p.keyframes,
                    kt = p.defaults,
                    pt = p.scrollTrigger,
                    wt = p.yoyoEase,
                    st = r.parent || e,
                    y = (b(i) || ss(i) ? ct(i[0]) : "length" in r) ? [i] : tt(i);
                if (s._targets = y.length ? du(y) : dr("GSAP target " + i + " not found. https://greensock.com", !v.nullTargetWarn) || [], s._ptLookup = [], s._overwrite = yt, ut || w || ge(nt) || ge(it)) {
                    if (r = s.vars, (l = s.timeline = new h({
                            data: "nested",
                            defaults: kt || {}
                        })).kill(), l.parent = l._dp = ht(s), l._start = 0, ut) g(l.vars.defaults, {
                        ease: "none"
                    }), w ? y.forEach(function (n, t) {
                        return ut.forEach(function (i, r) {
                            return l.to(n, i, r ? ">" : t * w)
                        })
                    }) : ut.forEach(function (n) {
                        return l.to(y, n, ">")
                    });
                    else {
                        if (ot = y.length, at = w ? yo(w) : li, lt(w))
                            for (k in w) ~ks.indexOf(k) && ((et = et || {})[k] = w[k]);
                        for (d = 0; d < ot; d++) {
                            for (k in c = {}, r) bc.indexOf(k) < 0 && (c[k] = r[k]);
                            c.stagger = 0;
                            wt && (c.yoyoEase = wt);
                            et && bi(c, et);
                            rt = y[d];
                            c.duration = +vr(nt, ht(s), d, rt, y);
                            c.delay = (+vr(it, ht(s), d, rt, y) || 0) - s._delay;
                            !w && 1 === ot && c.delay && (s._delay = it = c.delay, s._start += it, c.delay = 0);
                            l.to(rt, c, at(d, rt, y))
                        }
                        l.duration() ? nt = it = 0 : s.timeline = 0
                    }
                    nt || s.duration(nt = l.duration())
                } else s.timeline = 0;
                return !0 !== yt || ff || (bt = ht(s), e.killTweensOf(y), bt = 0), ft(st, ht(s), f), r.reversed && s.reverse(), r.paused && s.paused(!0), (vt || !nt && !ut && s._start === t(st._time) && a(vt) && function dt(n) {
                    return !n || n._ts && dt(n.parent)
                }(ht(s)) && "nested" !== st.data) && (s._tTime = -u, s.render(Math.max(0, -it))), pt && ho(ht(s), pt), s
            }
            ke(i, n);
            var r = i.prototype;
            return r.render = function (n, i, r) {
                var e, c, s, h, w, y, a, l, b, p = this._time,
                    v = this._tDur,
                    o = this._dur,
                    f = v - u < n && 0 <= n ? v : n < u ? 0 : n;
                if (o) {
                    if (f !== this._tTime || !n || r || !this._initted && this._tTime || this._startAt && this._zTime < 0 != n < 0) {
                        if (e = f, l = this.timeline, this._repeat) {
                            if (h = o + this._rDelay, this._repeat < -1 && n < 0) return this.totalTime(100 * h + n, i, r);
                            if (e = t(f % h), f === v ? (s = this._repeat, e = o) : ((s = ~~(f / h)) && s === f / h && (e = o, s--), o < e && (e = o)), (y = this._yoyo && 1 & s) && (b = this._yEase, e = o - e), w = ki(this._tTime, h), e === p && !r && this._initted) return this;
                            s !== w && (l && this._yEase && uu(l, y), !this.vars.repeatRefresh || y || this._lock || (this._lock = r = 1, this.render(t(h * s), !0).invalidate()._lock = 0))
                        }
                        if (!this._initted) {
                            if (co(this, n < 0 ? n : e, r, i)) return this._tTime = 0, this;
                            if (o !== this._dur) return this.render(n, i, r)
                        }
                        if (this._tTime = f, this._time = e, !this._act && this._ts && (this._act = 1, this._lazy = 0), this.ratio = a = (b || this._ease)(e / o), this._from && (this.ratio = a = 1 - a), e && !p && !i && (it(this, "onStart"), this._tTime !== f)) return this;
                        for (c = this._pt; c;) c.r(a, c.d), c = c._next;
                        l && l.render(n < 0 ? n : !e && y ? -u : l._dur * a, i, r) || this._startAt && (this._zTime = n);
                        this._onUpdate && !i && (n < 0 && this._startAt && this._startAt.render(n, !0, r), it(this, "onUpdate"));
                        this._repeat && s !== w && this.vars.onRepeat && !i && this.parent && it(this, "onRepeat");
                        f !== this._tDur && f || this._tTime !== f || (n < 0 && this._startAt && !this._onUpdate && this._startAt.render(n, !0, !0), !n && o || !(f === this._tDur && 0 < this._ts || !f && this._ts < 0) || vt(this, 1), i || n < 0 && !p || !f && !p || (it(this, f === v ? "onComplete" : "onReverseComplete", !0), !this._prom || f < v && 0 < this.timeScale() || this._prom()))
                    }
                } else ! function (n, t, i, r) {
                    var e, h, o, l = n.ratio,
                        f = t < 0 || !t && (!n._start && function a(n) {
                            var t = n.parent;
                            return t && t._ts && t._initted && !t._lock && (t.rawTime() < 0 || a(t))
                        }(n) && (n._initted || !te(n)) || (n._ts < 0 || n._dp._ts < 0) && !te(n)) ? 0 : 1,
                        c = n._rDelay,
                        s = 0;
                    if (c && n._repeat && (s = lr(0, n._tDur, t), h = ki(s, c), o = ki(n._tTime, c), n._yoyo && 1 & h && (f = 1 - f), h !== o && (l = 1 - f, n.vars.repeatRefresh && n._initted && n.invalidate())), f !== l || r || n._zTime === u || !t && n._zTime) {
                        if (!n._initted && co(n, t, r, i)) return;
                        for (o = n._zTime, n._zTime = t || (i ? u : 0), i = i || t && !o, n.ratio = f, n._from && (f = 1 - f), n._time = 0, n._tTime = s, e = n._pt; e;) e.r(f, e.d), e = e._next;
                        n._startAt && t < 0 && n._startAt.render(t, !0, !0);
                        n._onUpdate && !i && it(n, "onUpdate");
                        s && n._repeat && !i && n.parent && it(n, "onRepeat");
                        (t >= n._tDur || t < 0) && n.ratio === f && (f && vt(n, 1), i || (it(n, f ? "onComplete" : "onReverseComplete", !0), n._prom && n._prom()))
                    } else n._zTime || (n._zTime = t)
                }(this, n, i, r);
                return this
            }, r.targets = function () {
                return this._targets
            }, r.invalidate = function () {
                return this._pt = this._op = this._startAt = this._onUpdate = this._lazy = this.ratio = 0, this._ptLookup = [], this.timeline && this.timeline.invalidate(), n.prototype.invalidate.call(this)
            }, r.kill = function (n, t) {
                var h;
                if (void 0 === t && (t = "all"), !(n || t && "all" !== t)) return this._lazy = this._pt = 0, this.parent ? or(this) : this;
                if (this.timeline) return h = this.timeline.totalDuration(), this.timeline.killTweensOf(n, t, bt && !0 !== bt.vars.overwrite)._first || or(this), this.parent && h !== this.timeline.totalDuration() && ai(this, this._dur * this.timeline._tDur / h, 0, 1), this;
                var e, u, o, c, i, s, r, f = this._targets,
                    a = n ? tt(n) : f,
                    v = this._ptLookup,
                    y = this._pt;
                if ((!t || "all" === t) && function (n, t) {
                        for (var i = n.length, r = i === t.length; r && i-- && n[i] === t[i];);
                        return i < 0
                    }(f, a)) return "all" === t && (this._pt = 0), or(this);
                for (e = this._op = this._op || [], "all" !== t && (l(t) && (i = {}, p(t, function (n) {
                        return i[n] = 1
                    }), t = i), t = function (n, t) {
                        var i, r, u, e, o = n[0] ? ii(n[0]).harness : 0,
                            f = o && o.aliases;
                        if (!f) return t;
                        for (r in i = bi({}, t), f)
                            if (r in i)
                                for (u = (e = f[r].split(",")).length; u--;) i[e[u]] = i[r];
                        return i
                    }(f, t)), r = f.length; r--;)
                    if (~a.indexOf(f[r]))
                        for (i in u = v[r], "all" === t ? (e[r] = t, c = u, o = {}) : (o = e[r] = e[r] || {}, c = t), c)(s = u && u[i]) && ("kill" in s.d && !0 !== s.d.kill(i) || tu(this, s, "_pt"), delete u[i]), "all" !== o && (o[i] = 1);
                return this._initted && !this._pt && y && or(this), this
            }, i.to = function (n, t, r) {
                return new i(n, t, r)
            }, i.from = function () {
                return er(1, arguments)
            }, i.delayedCall = function (n, t, r, u) {
                return new i(t, 0, {
                    immediateRender: !1,
                    lazy: !1,
                    overwrite: !1,
                    delay: n,
                    onComplete: t,
                    onReverseComplete: t,
                    onCompleteParams: r,
                    onReverseCompleteParams: r,
                    callbackScope: u
                })
            }, i.fromTo = function () {
                return er(2, arguments)
            }, i.set = function (n, t) {
                return t.duration = 0, t.repeatDelay || (t.repeat = 0), new i(n, t)
            }, i.killTweensOf = function (n, t, i) {
                return e.killTweensOf(n, t, i)
            }, i
        }(nr);
    g(o.prototype, {
        _targets: [],
        _lazy: 0,
        _startAt: 0,
        _op: 0,
        _onInit: 0
    });
    p("staggerTo,staggerFrom,staggerFromTo", function (n) {
        o[n] = function () {
            var t = new h,
                i = ie.call(arguments, 0);
            return i.splice("staggerFromTo" === n ? 5 : 4, 0, 0), t[n].apply(t, i)
        }
    });
    var ee = function (n, t, i) {
            return n[t] = i
        },
        ds = function (n, t, i) {
            return n[t](i)
        },
        gc = function (n, t, i, r) {
            return n[t](r.fp, i)
        },
        oe = function (n, t) {
            return s(n[t]) ? ds : bu(n[t]) && n.setAttribute ? kc : ee
        },
        gs = function (n, t) {
            return t.set(t.t, t.p, Math.round(1e6 * (t.s + t.c * n)) / 1e6, t)
        },
        nl = function (n, t) {
            return t.set(t.t, t.p, !!(t.s + t.c * n), t)
        },
        nh = function (n, t) {
            var i = t._pt,
                r = "";
            if (!n && t.b) r = t.b;
            else if (1 === n && t.e) r = t.e;
            else {
                for (; i;) r = i.p + (i.m ? i.m(i.s + i.c * n) : Math.round(1e4 * (i.s + i.c * n)) / 1e4) + r, i = i._next;
                r += t.c
            }
            t.set(t.t, t.p, r, t)
        },
        se = function (n, t) {
            for (var i = t._pt; i;) i.r(n, i.d), i = i._next
        },
        tl = function (n, t, i, r) {
            for (var f, u = this._pt; u;) f = u._next, u.p === r && u.modifier(n, t, i), u = f
        },
        il = function (n) {
            for (var i, r, t = this._pt; t;) r = t._next, t.p === n && !t.op || t.op === n ? tu(this, t, "_pt") : t.dep || (i = 1), t = r;
            return !i
        },
        th = function (n) {
            for (var u, i, r, f, t = n._pt; t;) {
                for (u = t._next, i = r; i && i.pr > t.pr;) i = i._next;
                (t._prev = i ? i._prev : f) ? t._prev._next = t: r = t;
                (t._next = i) ? i._prev = t: f = t;
                t = u
            }
            n._pt = r
        },
        y = (ih.prototype.modifier = function (n, t, i) {
            this.mSet = this.mSet || this.set;
            this.set = dc;
            this.m = n;
            this.mt = i;
            this.tween = t
        }, ih);
    p(ne + "parent,duration,ease,delay,overwrite,runBackwards,startAt,yoyo,immediateRender,repeat,repeatDelay,data,paused,reversed,lazy,callbackScope,stringFilter,id,yoyoEase,stagger,inherit,repeatRefresh,keyframes,autoRevert,scrollTrigger", function (n) {
        return kf[n] = 1
    });
    k.TweenMax = k.TweenLite = o;
    k.TimelineLite = k.TimelineMax = h;
    e = new h({
        sortChildren: !1,
        defaults: pi,
        autoRemoveChildren: !0,
        id: "root",
        smoothChildTiming: !0
    });
    v.stringFilter = is;
    yr = {
        registerPlugin: function () {
            for (var t = arguments.length, i = new Array(t), n = 0; n < t; n++) i[n] = arguments[n];
            i.forEach(function (n) {
                return function (n) {
                    var t = (n = !n.name && n.default || n).name,
                        f = s(n),
                        i = t && !f && n.init ? function () {
                            this._props = []
                        } : n,
                        r = {
                            init: li,
                            render: se,
                            add: fe,
                            kill: il,
                            modifier: tl,
                            rawVars: 0
                        },
                        u = {
                            targetTest: 0,
                            get: 0,
                            getSetter: oe,
                            aliases: {},
                            register: 0
                        };
                    if (di(), n !== i) {
                        if (d[t]) return;
                        g(i, g(nf(n, r), u));
                        bi(i.prototype, bi(r, nf(n, u)));
                        d[i.prop = t] = i;
                        n.targetTest && (lu.push(i), kf[t] = 1);
                        t = ("css" === t ? "CSS" : t.charAt(0).toUpperCase() + t.substr(1)) + "Plugin"
                    }
                    to(t, i);
                    n.register && n.register(ut, i, y)
                }(n)
            })
        },
        timeline: function (n) {
            return new h(n)
        },
        getTweensOf: function (n, t) {
            return e.getTweensOf(n, t)
        },
        getProperty: function (n, t, i, r) {
            l(n) && (n = tt(n)[0]);
            var u = ii(n || {}).get,
                f = i ? fo : uo;
            return "native" === i && (i = ""), n ? t ? f((d[t] && d[t].get || u)(n, t, i, r)) : function (t, i, r) {
                return f((d[t] && d[t].get || u)(n, t, i, r))
            } : n
        },
        quickSetter: function (n, t, i) {
            var u, e;
            if (1 < (n = tt(n)).length) return u = n.map(function (n) {
                    return ut.quickSetter(n, t, i)
                }), e = u.length,
                function (n) {
                    for (var t = e; t--;) u[t](n)
                };
            n = n[0] || {};
            var f = d[t],
                r = ii(n),
                o = r.harness && (r.harness.aliases || {})[t] || t,
                s = f ? function (t) {
                    var r = new f;
                    vi._pt = 0;
                    r.init(n, i ? t + i : t, vi, 0, [n]);
                    r.render(1, r);
                    vi._pt && se(1, vi)
                } : r.set(n, o);
            return f ? s : function (t) {
                return s(n, o, i ? t + i : t, r, 1)
            }
        },
        isTweening: function (n) {
            return 0 < e.getTweensOf(n, !0).length
        },
        defaults: function (n) {
            return n && n.ease && (n.ease = gi(n.ease, pi.ease)), gu(pi, n || {})
        },
        config: function (n) {
            return gu(v, n || {})
        },
        registerEffect: function (n) {
            var t = n.name,
                i = n.effect,
                r = n.plugins,
                u = n.defaults,
                f = n.extendTimeline;
            (r || "").split(",").forEach(function (n) {
                return n && !d[n] && !k[n] && dr(t + " effect requires " + n + " plugin.")
            });
            gf[t] = function (n, t, r) {
                return i(tt(n), g(t || {}, u), r)
            };
            f && (h.prototype[t] = function (n, i, r) {
                return this.add(gf[t](n, lt(i) ? i : (r = i) && {}, this), r)
            })
        },
        registerEase: function (n, t) {
            i[n] = gi(t)
        },
        parseEase: function (n, t) {
            return arguments.length ? gi(n, t) : i
        },
        getById: function (n) {
            return e.getById(n)
        },
        exportRoot: function (n, t) {
            void 0 === n && (n = {});
            var i, u, r = new h(n);
            for (r.smoothChildTiming = a(n.smoothChildTiming), e.remove(r), r._dp = 0, r._time = r._tTime = e._time, i = e._first; i;) u = i._next, !t && !i._dur && i instanceof o && i.vars.onComplete === i._targets[0] || ft(r, i, i._start - i._delay), i = u;
            return ft(e, r, 0), r
        },
        utils: {
            wrap: function rl(n, t, i) {
                var r = t - n;
                return b(n) ? bo(n, rl(0, n.length), t) : yt(i, function (t) {
                    return (r + (t - n) % r) % r + n
                })
            },
            wrapYoyo: function ul(n, t, i) {
                var u = t - n,
                    r = 2 * u;
                return b(n) ? bo(n, ul(0, n.length - 1), t) : yt(i, function (t) {
                    return n + (u < (t = (r + (t - n) % r) % r || 0) ? r - t : t)
                })
            },
            distribute: yo,
            random: wo,
            snap: po,
            normalize: function (n, t, i) {
                return vs(n, t, 0, 1, i)
            },
            getUnit: w,
            clamp: function (n, t, i) {
                return yt(i, function (i) {
                    return lr(n, t, i)
                })
            },
            splitColor: go,
            toArray: tt,
            selector: function (n) {
                return n = tt(n)[0] || dr("Invalid scope") || {},
                    function (t) {
                        var i = n.current || n.nativeElement || n;
                        return tt(t, i.querySelectorAll ? i : i === n ? dr("Invalid scope") || of .createElement("div") : n)
                    }
            },
            mapRange: vs,
            pipe: function () {
                for (var t = arguments.length, i = new Array(t), n = 0; n < t; n++) i[n] = arguments[n];
                return function (n) {
                    return i.reduce(function (n, t) {
                        return t(n)
                    }, n)
                }
            },
            unitize: function (n, t) {
                return function (i) {
                    return n(parseFloat(i)) + (t || w(i))
                }
            },
            interpolate: function fl(n, t, i, r) {
                var e = isNaN(n + t) ? 0 : function (i) {
                        return (1 - i) * n + i * t
                    },
                    s, u, o, f, a, h, c;
                if (!e) {
                    if (h = l(n), c = {}, !0 === i && (r = 1) && (i = null), h) n = {
                        p: n
                    }, t = {
                        p: t
                    };
                    else if (b(n) && !b(t)) {
                        for (o = [], f = n.length, a = f - 2, u = 1; u < f; u++) o.push(fl(n[u - 1], n[u]));
                        f--;
                        e = function (n) {
                            n *= f;
                            var t = Math.min(a, ~~n);
                            return o[t](n - t)
                        };
                        i = t
                    } else r || (n = bi(b(n) ? [] : {}, n));
                    if (!o) {
                        for (s in t) fe.call(c, n, s, "get", t[s]);
                        e = function (t) {
                            return se(t, c) || (h ? n.p : n)
                        }
                    }
                }
                return yt(i, e)
            },
            shuffle: vo
        },
        install: no,
        effects: gf,
        ticker: rt,
        updateRoot: h.updateRoot,
        plugins: d,
        globalTimeline: e,
        core: {
            PropTween: y,
            globals: to,
            Tween: o,
            Timeline: h,
            Animation: nr,
            getCache: ii,
            _removeLinkedListItem: tu,
            suppressOverwrites: function (n) {
                return ff = n
            }
        }
    };
    p("to,from,fromTo,delayedCall,set,killTweensOf", function (n) {
        return yr[n] = o[n]
    });
    rt.add(h.updateRoot);
    vi = yr.to({}, {
        duration: 0
    });
    ut = yr.registerPlugin({
        name: "attr",
        init: function (n, t, i, r, u) {
            var f, e;
            for (f in t)(e = this.add(n, "setAttribute", (n.getAttribute(f) || 0) + "", t[f], r, u, 0, 0, f)) && (e.op = f), this._props.push(f)
        }
    }, {
        name: "endArray",
        init: function (n, t) {
            for (var i = t.length; i--;) this.add(n, i, n[i] || 0, t[i])
        }
    }, he("roundProps", rf), he("modifiers"), he("snap", po)) || yr;
    o.version = h.version = ut.version = "3.7.1";
    us = 1;
    de() && di();
    var ah, dt, ir, pe, oi, vh, yh, na = i.Power0,
        ta = i.Power1,
        ia = i.Power2,
        ra = i.Power3,
        ua = i.Power4,
        fa = i.Linear,
        ea = i.Quad,
        oa = i.Cubic,
        sa = i.Quart,
        ha = i.Quint,
        ca = i.Strong,
        la = i.Elastic,
        aa = i.Back,
        va = i.SteppedEase,
        ya = i.Bounce,
        pa = i.Sine,
        wa = i.Expo,
        ba = i.Circ,
        gt = {},
        si = 180 / Math.PI,
        rr = Math.PI / 180,
        ur = Math.atan2,
        ph = /([A-Z])/g,
        ka = /(?:left|right|width|margin|padding|x)/i,
        da = /[\s,\(]\S/,
        ni = {
            autoAlpha: "opacity,visibility",
            scale: "scaleX,scaleY",
            alpha: "opacity"
        },
        c = "transform",
        ti = c + "Origin",
        wh = "O,Moz,ms,Ms,Webkit".split(","),
        fr = function (n, t, i) {
            var u = (t || oi).style,
                r = 5;
            if (n in u && !i) return n;
            for (n = n.charAt(0).toUpperCase() + n.substr(1); r-- && !(wh[r] + n in u););
            return r < 0 ? null : (3 === r ? "ms" : 0 <= r ? wh[r] : "") + n
        },
        bh = {
            deg: 1,
            rad: 1,
            turn: 1
        },
        kh = {
            top: "0%",
            bottom: "100%",
            left: "0%",
            right: "100%",
            center: "50%"
        },
        vu = {
            clearProps: function (n, t, i, r, u) {
                if ("isFromStart" !== u.data) {
                    var f = n._pt = new y(n._pt, t, i, 0, 0, kl);
                    return f.u = r, f.pr = -10, f.tween = u, n._props.push(i), 1
                }
            }
        },
        wr = [1, 0, 0, 1, 0, 0],
        dh = {},
        br = function (n, i) {
            var r = n._gsap || new ue(n);
            if ("x" in r && !i && !r.uncache) return r;
            var d, g, pt, ft, rt, y, ut, et, p, kt, wt, at, vt, u, e, f, o, s, h, l, nt, k, w, a, ot, bt, ht, ct, tt, dt, b, it, gt = n.style,
                ni = r.scaleX < 0,
                lt = "deg",
                yt = st(n, ti) || "0";
            return d = g = pt = y = ut = et = p = kt = wt = 0, ft = rt = 1, r.svg = !(!n.getCTM || !sh(n)), u = ae(n, r.svg), r.svg && (a = (!r.uncache || "0px 0px" === yt) && !i && n.getAttribute("data-svg-origin"), ve(n, a || yt, !!a || r.originIsAbsolute, !1 !== r.smooth, u)), at = r.xOrigin || 0, vt = r.yOrigin || 0, u !== wr && (s = u[0], h = u[1], l = u[2], nt = u[3], d = k = u[4], g = w = u[5], 6 === u.length ? (ft = Math.sqrt(s * s + h * h), rt = Math.sqrt(nt * nt + l * l), y = s || h ? ur(h, s) * si : 0, (p = l || nt ? ur(l, nt) * si + y : 0) && (rt *= Math.abs(Math.cos(p * rr))), r.svg && (d -= at - (at * s + vt * l), g -= vt - (at * h + vt * nt))) : (it = u[6], dt = u[7], ht = u[8], ct = u[9], tt = u[10], b = u[11], d = u[12], g = u[13], pt = u[14], ut = (e = ur(it, tt)) * si, e && (a = k * (f = Math.cos(-e)) + ht * (o = Math.sin(-e)), ot = w * f + ct * o, bt = it * f + tt * o, ht = k * -o + ht * f, ct = w * -o + ct * f, tt = it * -o + tt * f, b = dt * -o + b * f, k = a, w = ot, it = bt), et = (e = ur(-l, tt)) * si, e && (f = Math.cos(-e), b = nt * (o = Math.sin(-e)) + b * f, s = a = s * f - ht * o, h = ot = h * f - ct * o, l = bt = l * f - tt * o), y = (e = ur(h, s)) * si, e && (a = s * (f = Math.cos(e)) + h * (o = Math.sin(e)), ot = k * f + w * o, h = h * f - s * o, w = w * f - k * o, s = a, k = ot), ut && 359.9 < Math.abs(ut) + Math.abs(y) && (ut = y = 0, et = 180 - et), ft = t(Math.sqrt(s * s + h * h + l * l)), rt = t(Math.sqrt(w * w + it * it)), e = ur(k, w), p = .0002 < Math.abs(e) ? e * si : 0, wt = b ? 1 / (b < 0 ? -b : b) : 0), r.svg && (a = n.getAttribute("transform"), r.forceCSS = n.setAttribute("transform", "") || !hh(st(n, c)), a && n.setAttribute("transform", a))), 90 < Math.abs(p) && Math.abs(p) < 270 && (ni ? (ft *= -1, p += y <= 0 ? 180 : -180, y += y <= 0 ? 180 : -180) : (rt *= -1, p += p <= 0 ? 180 : -180)), r.x = d - ((r.xPercent = d && (r.xPercent || (Math.round(n.offsetWidth / 2) === Math.round(-d) ? -50 : 0))) ? n.offsetWidth * r.xPercent / 100 : 0) + "px", r.y = g - ((r.yPercent = g && (r.yPercent || (Math.round(n.offsetHeight / 2) === Math.round(-g) ? -50 : 0))) ? n.offsetHeight * r.yPercent / 100 : 0) + "px", r.z = pt + "px", r.scaleX = t(ft), r.scaleY = t(rt), r.rotation = t(y) + lt, r.rotationX = t(ut) + lt, r.rotationY = t(et) + lt, r.skewX = p + lt, r.skewY = kt + lt, r.transformPerspective = wt + "px", (r.zOrigin = parseFloat(yt.split(" ")[2]) || 0) && (gt[ti] = yu(yt)), r.xOffset = r.yOffset = 0, r.force3D = v.force3D, r.renderTransform = r.svg ? nv : yh ? gh : ga, r.uncache = 0, r
        },
        yu = function (n) {
            return (n = n.split(" "))[0] + " " + n[1]
        },
        ga = function (n, t) {
            t.z = "0px";
            t.rotationY = t.rotationX = "0deg";
            t.force3D = 0;
            gh(n, t)
        },
        hi = "0deg",
        kr = "0px",
        ci = ") ",
        gh = function (n, t) {
            var i = t || this,
                y = i.xPercent,
                p = i.yPercent,
                u = i.x,
                f = i.y,
                e = i.z,
                w = i.rotation,
                h = i.rotationY,
                l = i.rotationX,
                b = i.skewX,
                k = i.skewY,
                d = i.scaleX,
                g = i.scaleY,
                nt = i.transformPerspective,
                tt = i.force3D,
                a = i.target,
                o = i.zOrigin,
                r = "",
                it = "auto" === tt && n && 1 !== n || !0 === tt;
            if (o && (l !== hi || h !== hi)) {
                var v, s = parseFloat(h) * rr,
                    rt = Math.sin(s),
                    ut = Math.cos(s);
                s = parseFloat(l) * rr;
                v = Math.cos(s);
                u = ye(a, u, rt * v * -o);
                f = ye(a, f, -Math.sin(s) * -o);
                e = ye(a, e, ut * v * -o + o)
            }
            nt !== kr && (r += "perspective(" + nt + ci);
            (y || p) && (r += "translate(" + y + "%, " + p + "%) ");
            !it && u === kr && f === kr && e === kr || (r += e !== kr || it ? "translate3d(" + u + ", " + f + ", " + e + ") " : "translate(" + u + ", " + f + ci);
            w !== hi && (r += "rotate(" + w + ci);
            h !== hi && (r += "rotateY(" + h + ci);
            l !== hi && (r += "rotateX(" + l + ci);
            b === hi && k === hi || (r += "skew(" + b + ", " + k + ci);
            1 === d && 1 === g || (r += "scale(" + d + ", " + g + ci);
            a.style[c] = r || "translate(0, 0)"
        },
        nv = function (n, i) {
            var s, h, l, a, r, u = i || this,
                tt = u.xPercent,
                it = u.yPercent,
                k = u.x,
                d = u.y,
                f = u.rotation,
                e = u.skewX,
                o = u.skewY,
                g = u.scaleX,
                nt = u.scaleY,
                p = u.target,
                w = u.xOrigin,
                b = u.yOrigin,
                rt = u.xOffset,
                ut = u.yOffset,
                ft = u.forceCSS,
                v = parseFloat(k),
                y = parseFloat(d);
            f = parseFloat(f);
            e = parseFloat(e);
            (o = parseFloat(o)) && (e += o = parseFloat(o), f += o);
            f || e ? (f *= rr, e *= rr, s = Math.cos(f) * g, h = Math.sin(f) * g, l = Math.sin(f - e) * -nt, a = Math.cos(f - e) * nt, e && (o *= rr, r = Math.tan(e - o), l *= r = Math.sqrt(1 + r * r), a *= r, o && (r = Math.tan(o), s *= r = Math.sqrt(1 + r * r), h *= r)), s = t(s), h = t(h), l = t(l), a = t(a)) : (s = g, a = nt, h = l = 0);
            (v && !~(k + "").indexOf("px") || y && !~(d + "").indexOf("px")) && (v = at(p, "x", k, "px"), y = at(p, "y", d, "px"));
            (w || b || rt || ut) && (v = t(v + w - (w * s + b * l) + rt), y = t(y + b - (w * h + b * a) + ut));
            (tt || it) && (r = p.getBBox(), v = t(v + tt / 100 * r.width), y = t(y + it / 100 * r.height));
            r = "matrix(" + s + "," + h + "," + l + "," + a + "," + v + "," + y + ")";
            p.setAttribute("transform", r);
            ft && (p.style[c] = r)
        };
    p("padding,margin,Width,Radius", function (n, t) {
        var i = "Right",
            r = "Bottom",
            u = "Left",
            f = (t < 3 ? ["Top", i, r, u] : ["Top" + u, "Top" + i, r + i, r + u]).map(function (i) {
                return t < 2 ? n + i : "border" + i + n
            });
        vu[1 < t ? "border" + n : n] = function (n, t, i, r, u) {
            var e, o;
            if (arguments.length < 4) return e = f.map(function (t) {
                return tr(n, t, i)
            }), 5 === (o = e.join(" ")).split(e[0]).length ? e[0] : o;
            e = (r + "").split(" ");
            o = {};
            f.forEach(function (n, t) {
                return o[n] = e[t] = e[t] || e[(t - 1) / 2 | 0]
            });
            n.init(t, o, u)
        }
    });
    pu = {
        name: "css",
        register: le,
        targetTest: function (n) {
            return n.style && n.nodeType
        },
        init: function (n, t, i, r, u) {
            var o, e, h, a, k, it, f, b, l, g, tt, ft, s, rt, et, ut = this._props,
                p = n.style,
                nt = i.vars.startAt;
            for (f in pe || le(), t)
                if ("autoRound" !== f && (e = t[f], !d[f] || !bs(f, t, i, r, n, u)))
                    if (k = typeof e, it = vu[f], "function" === k && (k = typeof (e = e.call(i, r, n, u))), "string" === k && ~e.indexOf("random(") && (e = ru(e)), it) it(this, n, f, e, i) && (et = 1);
                    else if ("--" === f.substr(0, 2)) o = (getComputedStyle(n).getPropertyValue(f) + "").trim(), e += "", wt.lastIndex = 0, wt.test(o) || (b = w(o), l = w(e)), l ? b !== l && (o = at(n, f, o, l) + l) : b && (e += b), this.add(p, "setProperty", o, e, r, u, 0, 0, f), ut.push(f);
            else if ("undefined" !== k) {
                if (nt && f in nt ? (o = "function" == typeof nt[f] ? nt[f].call(i, r, n, u) : nt[f], f in v.units && !w(o) && (o += v.units[f]), "=" === (o + "").charAt(1) && (o = tr(n, f))) : o = tr(n, f), a = parseFloat(o), (g = "string" === k && "=" === e.charAt(1) ? +(e.charAt(0) + "1") : 0) && (e = e.substr(2)), h = parseFloat(e), f in ni && ("autoAlpha" === f && (1 === a && "hidden" === tr(n, "visibility") && h && (a = 0), kt(this, p, "visibility", a ? "inherit" : "hidden", h ? "inherit" : "hidden", !h)), "scale" !== f && "transform" !== f && ~(f = ni[f]).indexOf(",") && (f = f.split(",")[0])), tt = f in gt)
                    if (ft || ((s = n._gsap).renderTransform && !t.parseTransform || br(n, t.parseTransform), rt = !1 !== t.smoothOrigin && s.smooth, (ft = this._pt = new y(this._pt, p, c, 0, 1, s.renderTransform, s, 0, -1)).dep = 1), "scale" === f) this._pt = new y(this._pt, s, "scaleY", s.scaleY, (g ? g * h : h - s.scaleY) || 0), ut.push("scaleY", f), f += "X";
                    else {
                        if ("transformOrigin" === f) {
                            e = bl(e);
                            s.svg ? ve(n, e, 0, rt, 0, this) : ((l = parseFloat(e.split(" ")[2]) || 0) !== s.zOrigin && kt(this, s, "zOrigin", s.zOrigin, l), kt(this, p, f, yu(o), yu(e)));
                            continue
                        }
                        if ("svgOrigin" === f) {
                            ve(n, e, 1, rt, 0, this);
                            continue
                        }
                        if (f in dh) {
                            dl(this, s, f, a, e, g);
                            continue
                        }
                        if ("smoothOrigin" === f) {
                            kt(this, s, "smooth", s.smooth, e);
                            continue
                        }
                        if ("force3D" === f) {
                            s[f] = e;
                            continue
                        }
                        if ("transform" === f) {
                            gl(this, e, n);
                            continue
                        }
                    }
                else f in p || (f = fr(f) || f);
                if (tt || (h || 0 === h) && (a || 0 === a) && !da.test(e) && f in p) h = h || 0, (b = (o + "").substr((a + "").length)) !== (l = w(e) || (f in v.units ? v.units[f] : b)) && (a = at(n, f, o, l)), this._pt = new y(this._pt, tt ? s : p, f, a, g ? g * h : h - a, tt || "px" !== l && "zIndex" !== f || !1 === t.autoRound ? rh : hl), this._pt.u = l || 0, b !== l && (this._pt.b = o, this._pt.r = sl);
                else if (f in p) wl.call(this, n, f, o, e);
                else {
                    if (!(f in n)) {
                        ku(f, e);
                        continue
                    }
                    this.add(n, f, o || n[f], e, r, u)
                }
                ut.push(f)
            }
            et && th(this)
        },
        get: tr,
        aliases: ni,
        getSetter: function (n, t, i) {
            var r = ni[t];
            return r && r.indexOf(",") < 0 && (t = r), t in gt && t !== ti && (n._gsap.x || tr(n, "x")) ? i && vh === i ? "scale" === t ? vl : al : (vh = i || {}) && ("scale" === t ? yl : pl) : n.style && !bu(n.style[t]) ? cl : ~t.indexOf("-") ? ll : oe(n, t)
        },
        core: {
            _removeProperty: pr,
            _getMatrix: ae
        }
    };
    ut.utils.checkPrefix = fr;
    be = p((nc = "x,y,z,scale,scaleX,scaleY,xPercent,yPercent") + "," + (we = "rotation,rotationX,rotationY,skewX,skewY") + ",transform,transformOrigin,svgOrigin,force3D,smoothOrigin,transformPerspective", function (n) {
        gt[n] = 1
    });
    p(we, function (n) {
        v.units[n] = "deg";
        dh[n] = 1
    });
    ni[be[13]] = nc + "," + we;
    p("0:translateX,1:translateY,2:translateZ,8:rotate,8:rotationZ,8:rotateZ,9:rotateX,10:rotateY", function (n) {
        var t = n.split(":");
        ni[t[1]] = be[t[0]]
    });
    p("x,y,z,top,right,bottom,left,width,height,fontSize,padding,margin,perspective", function (n) {
        v.units[n] = "px"
    });
    ut.registerPlugin(pu);
    wu = ut.registerPlugin(pu) || ut;
    tc = wu.core.Tween;
    n.Back = aa;
    n.Bounce = ya;
    n.CSSPlugin = pu;
    n.Circ = ba;
    n.Cubic = oa;
    n.Elastic = la;
    n.Expo = wa;
    n.Linear = fa;
    n.Power0 = na;
    n.Power1 = ta;
    n.Power2 = ia;
    n.Power3 = ra;
    n.Power4 = ua;
    n.Quad = ea;
    n.Quart = sa;
    n.Quint = ha;
    n.Sine = pa;
    n.SteppedEase = va;
    n.Strong = ca;
    n.TimelineLite = h;
    n.TimelineMax = h;
    n.TweenLite = o;
    n.TweenMax = tc;
    n.default = wu;
    n.gsap = wu;
    typeof window == "undefined" || window !== n ? Object.defineProperty(n, "__esModule", {
        value: !0
    }) : delete n.default
});
! function (n) {
    var t = 0,
        i = function n(i, r) {
            var e = this,
                h = this,
                f = !1,
                u, o, s;
            if (Array.isArray(i)) return !!i.length && i.map(function (t) {
                return new n(t, r)
            });
            u = {
                init: function () {
                    var n = this;
                    this.options = Object.assign({
                        duration: 600,
                        ariaEnabled: !0,
                        collapse: !0,
                        showMultiple: !1,
                        openOnInit: [],
                        elementClass: "ac",
                        triggerClass: "ac-trigger",
                        panelClass: "ac-panel",
                        activeClass: "is-active",
                        beforeOpen: function () {},
                        onOpen: function () {},
                        beforeClose: function () {},
                        onClose: function () {}
                    }, r);
                    var u = this.options,
                        f = u.elementClass,
                        e = u.openOnInit,
                        o = "string" == typeof i;
                    this.container = o ? document.querySelector(i) : i;
                    this.elements = Array.from(this.container.childNodes).filter(function (n) {
                        return n.classList && n.classList.contains(f)
                    });
                    this.firstElement = this.elements[0];
                    this.lastElement = this.elements[this.elements.length - 1];
                    this.currFocusedIdx = 0;
                    this.elements.map(function (i, r) {
                        return i.classList.add("js-enabled"), n.generateIDs(i), n.setARIA(i), n.setTransition(i), t++, e.includes(r) ? n.showElement(i, !1) : n.closeElement(i, !1)
                    });
                    h.attachEvents()
                },
                setTransition: function (n) {
                    var i = arguments.length > 1 && void 0 !== arguments[1] && arguments[1],
                        t = this.options,
                        r = t.duration,
                        u = t.panelClass,
                        f = n.querySelector(".".concat(u)),
                        e = o("transitionDuration");
                    f.style[e] = i ? null : "".concat(r, "ms")
                },
                generateIDs: function (n) {
                    var i = this.options,
                        r = i.triggerClass,
                        u = i.panelClass,
                        f = n.querySelector(".".concat(r)),
                        e = n.querySelector(".".concat(u));
                    n.setAttribute("id", "ac-".concat(t));
                    f.setAttribute("id", "ac-trigger-".concat(t));
                    e.setAttribute("id", "ac-panel-".concat(t))
                },
                removeIDs: function (n) {
                    var t = this.options,
                        i = t.triggerClass,
                        r = t.panelClass,
                        u = n.querySelector(".".concat(i)),
                        f = n.querySelector(".".concat(r));
                    n.removeAttribute("id");
                    u.removeAttribute("id");
                    f.removeAttribute("id")
                },
                setARIA: function (n) {
                    var r = this.options,
                        f = r.ariaEnabled,
                        e = r.triggerClass,
                        o = r.panelClass,
                        i, u;
                    f && (i = n.querySelector(".".concat(e)), u = n.querySelector(".".concat(o)), i.setAttribute("role", "button"), i.setAttribute("aria-controls", "ac-panel-".concat(t)), i.setAttribute("aria-disabled", !1), i.setAttribute("aria-expanded", !1), u.setAttribute("role", "region"), u.setAttribute("aria-labelledby", "ac-trigger-".concat(t)))
                },
                updateARIA: function (n, t) {
                    var u = t.ariaExpanded,
                        f = t.ariaDisabled,
                        r = this.options,
                        e = r.ariaEnabled,
                        o = r.triggerClass,
                        i;
                    e && (i = n.querySelector(".".concat(o)), i.setAttribute("aria-expanded", u), i.setAttribute("aria-disabled", f))
                },
                removeARIA: function (n) {
                    var i = this.options,
                        u = i.ariaEnabled,
                        f = i.triggerClass,
                        e = i.panelClass,
                        t, r;
                    u && (t = n.querySelector(".".concat(f)), r = n.querySelector(".".concat(e)), t.removeAttribute("role"), t.removeAttribute("aria-controls"), t.removeAttribute("aria-disabled"), t.removeAttribute("aria-expanded"), r.removeAttribute("role"), r.removeAttribute("aria-labelledby"))
                },
                focus: function (n, t) {
                    n.preventDefault();
                    var i = this.options.triggerClass;
                    t.querySelector(".".concat(i)).focus()
                },
                focusFirstElement: function (n) {
                    this.focus(n, this.firstElement);
                    this.currFocusedIdx = 0
                },
                focusLastElement: function (n) {
                    this.focus(n, this.lastElement);
                    this.currFocusedIdx = this.elements.length - 1
                },
                focusNextElement: function (n) {
                    var t = this.currFocusedIdx + 1;
                    if (t > this.elements.length - 1) return this.focusFirstElement(n);
                    this.focus(n, this.elements[t]);
                    this.currFocusedIdx = t
                },
                focusPrevElement: function (n) {
                    var t = this.currFocusedIdx - 1;
                    if (t < 0) return this.focusLastElement(n);
                    this.focus(n, this.elements[t]);
                    this.currFocusedIdx = t
                },
                showElement: function (n) {
                    var i = !(arguments.length > 1 && void 0 !== arguments[1]) || arguments[1],
                        t = this.options,
                        u = t.panelClass,
                        f = t.activeClass,
                        e = t.collapse,
                        o = t.beforeOpen,
                        r = n.querySelector(".".concat(u)),
                        s = r.scrollHeight;
                    n.classList.add(f);
                    i && o(n);
                    requestAnimationFrame(function () {
                        requestAnimationFrame(function () {
                            r.style.height = i ? "".concat(s, "px") : "auto"
                        })
                    });
                    this.updateARIA(n, {
                        ariaExpanded: !0,
                        ariaDisabled: !e
                    })
                },
                closeElement: function (n) {
                    var r = !(arguments.length > 1 && void 0 !== arguments[1]) || arguments[1],
                        i = this.options,
                        u = i.panelClass,
                        f = i.activeClass,
                        e = i.beforeClose,
                        t = n.querySelector(".".concat(u)),
                        o = t.scrollHeight;
                    n.classList.remove(f);
                    r ? (e(n), requestAnimationFrame(function () {
                        t.style.height = "".concat(o, "px");
                        requestAnimationFrame(function () {
                            t.style.height = 0
                        })
                    }), this.updateARIA(n, {
                        ariaExpanded: !1,
                        ariaDisabled: !1
                    })) : t.style.height = 0
                },
                toggleElement: function (n) {
                    var t = this.options,
                        r = t.activeClass,
                        u = t.collapse,
                        i = n.classList.contains(r);
                    if (!i || u) return i ? this.closeElement(n) : this.showElement(n)
                },
                closeElements: function () {
                    var n = this,
                        t = this.options,
                        i = t.activeClass;
                    t.showMultiple || this.elements.map(function (t, r) {
                        t.classList.contains(i) && r != n.currFocusedIdx && n.closeElement(t)
                    })
                },
                handleClick: function (n) {
                    var t = this,
                        i = n.currentTarget;
                    this.elements.map(function (r, u) {
                        r.contains(i) && "A" !== n.target.nodeName && (t.currFocusedIdx = u, t.closeElements(), t.focus(n, r), t.toggleElement(r))
                    })
                },
                handleKeydown: function (n) {
                    switch (n.keyCode) {
                        case 38:
                            return this.focusPrevElement(n);
                        case 40:
                            return this.focusNextElement(n);
                        case 36:
                            return this.focusFirstElement(n);
                        case 35:
                            return this.focusLastElement(n);
                        default:
                            return null
                    }
                },
                handleTransitionEnd: function (n) {
                    if ("height" === n.propertyName) {
                        var i = this.options,
                            u = i.onOpen,
                            f = i.onClose,
                            t = n.currentTarget,
                            e = parseInt(t.style.height),
                            r = this.elements.find(function (n) {
                                return n.contains(t)
                            });
                        e > 0 ? (t.style.height = "auto", u(r)) : f(r)
                    }
                }
            };
            this.attachEvents = function () {
                if (!f) {
                    var n = u.options,
                        t = n.triggerClass,
                        i = n.panelClass;
                    u.handleClick = u.handleClick.bind(u);
                    u.handleKeydown = u.handleKeydown.bind(u);
                    u.handleTransitionEnd = u.handleTransitionEnd.bind(u);
                    u.elements.map(function (n) {
                        var r = n.querySelector(".".concat(t)),
                            f = n.querySelector(".".concat(i));
                        r.addEventListener("click", u.handleClick);
                        r.addEventListener("keydown", u.handleKeydown);
                        f.addEventListener("webkitTransitionEnd", u.handleTransitionEnd);
                        f.addEventListener("transitionend", u.handleTransitionEnd)
                    });
                    f = !0
                }
            };
            this.detachEvents = function () {
                if (f) {
                    var n = u.options,
                        t = n.triggerClass,
                        i = n.panelClass;
                    u.elements.map(function (n) {
                        var r = n.querySelector(".".concat(t)),
                            f = n.querySelector(".".concat(i));
                        r.removeEventListener("click", u.handleClick);
                        r.removeEventListener("keydown", u.handleKeydown);
                        f.removeEventListener("webkitTransitionEnd", u.handleTransitionEnd);
                        f.removeEventListener("transitionend", u.handleTransitionEnd)
                    });
                    f = !1
                }
            };
            this.toggle = function (n) {
                var t = u.elements.find(function (t, i) {
                    return i === n
                });
                t && u.toggleElement(t)
            };
            this.open = function (n) {
                var t = u.elements.find(function (t, i) {
                    return i === n
                });
                t && u.showElement(t)
            };
            this.openAll = function () {
                u.elements.map(function (n) {
                    return u.showElement(n, !1)
                })
            };
            this.close = function (n) {
                var t = u.elements.find(function (t, i) {
                    return i === n
                });
                t && u.closeElement(t)
            };
            this.closeAll = function () {
                u.elements.map(function (n) {
                    return u.closeElement(n, !1)
                })
            };
            this.destroy = function () {
                e.detachEvents();
                e.openAll();
                u.elements.map(function (n) {
                    u.removeIDs(n);
                    u.removeARIA(n);
                    u.setTransition(n, !0)
                });
                f = !0
            };
            o = function (n) {
                return "string" == typeof document.documentElement.style[n] ? n : (n = s(n), n = "webkit".concat(n))
            };
            s = function (n) {
                return n.charAt(0).toUpperCase() + n.slice(1)
            };
            u.init()
        };
    "undefined" != typeof module && void 0 !== module.exports ? module.exports = i : n.Accordion = i
}(window);
String.format = function () {
    let n = arguments[0];
    for (let t = 0; t < arguments.length - 1; t++) {
        let i = new RegExp("\\{" + t + "\\}", "gm");
        n = n.replace(i, arguments[t + 1])
    }
    return n
};
var HttpUtility = function () {
    return {
        HtmlEncode: function (n) {
            return n.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;")
        },
        HtmlDecode: function (n) {
            return n.replace(/&amp;/g, "&").replace(/&lt;/g, "<").replace(/&gt;/g, ">").replace(/&quot;/g, '"')
        }
    }
}();
window.HistoryViewController = new function () {
    var n = {};
    this.getView = function (t) {
        return n[t]
    };
    this.registerView = function (t, i) {
        n[t] = i
    }
};
HistoryViewController.registerView("BaccaratTemplate", '<table class="table table-bordered table-condensed" id="BaccaratDetails" style="max-width:400px"><tr><td>{{tl \'winningresult\'}}<\/td><td colspan="2"><b>{{tl wt}}<\/b><\/td><\/tr><tr><td>{{tl \'baccarat_banker\'}}<\/td><td>{{bs}}<\/td><td>{{#if bc}} {{#each bc}} <span class="card c{{name}}"><\/span> {{/each}} {{/if}}<\/td><\/tr><tr><td>{{tl \'baccarat_player\'}}<\/td><td>{{ps}}<\/td><td>{{#if pc}} {{#each pc}} <span class="card c{{name}}"><\/span> {{/each}} {{/if}}<\/td><\/tr>{{#compare cm 0 operator=">"}}<tr><td>{{tl \'commission\'}}<\/td><td colspan="2">{{cm}}<\/td><\/tr>{{/compare}}<\/table>{{#if bl}}<table class="table table-condensed table-bordered" style="max-width:280px"><thead><tr><th>{{tl \'description\'}}<\/th><th class="currency">{{tl \'stake\'}}<\/th><th class="currency">{{tl \'payout\'}}<\/th><\/tr><\/thead><tbody>{{#each bl}}<tr><td>{{tl n}}<\/td><td class="currency">{{formatMoney s}}<\/td><td class="currency">{{formatMoney p}}<\/td><\/tr>{{/each}}<\/tbody><\/table>{{/if}}');
HistoryViewController.registerView("BlackjackTemplate", '<table class="table table-condensed table-bordered" style="max-width:400px">{{#if dlr}}<tr><td style="text-align:center"><b>{{tl \'dealer\'}}<\/b><\/td><td><b>{{tl \'dealer\'}}<\/b> ({{dlr.tot}})<br>{{#each dlr.cards}} <span class="card c{{name}}"><\/span> {{/each}}<\/td><\/tr>{{/if}} {{#if seats}} {{#each seats}}<tr><td style="text-align:center"><strong>{{tl "blackjack_seat"}} {{id}}<\/strong><br>{{#compare i true opeator="="}} <b style="color:green">{{tl \'Blackjack_Insured\'}}<\/b> {{/compare}} {{#compare ia 0 operator=">"}} ({{formatMoney ia}}) {{/compare}} {{#compare ip 0 operator=">"}}<br>{{tl \'payout\'}} {{formatMoney ip}} {{/compare}}<\/td><td><table border="0" width="100%" style="border:none" class="">{{#if hands}} {{#each hands }}<tr><td width="200px" valign="top">{{#compare @index 1 operator="=="}}<br>{{/compare}} {{#if cards}} <b>{{tl "Hand"}} {{Id}}<\/b> <span style="margin-bottom:5px">({{Total}})<\/span><br>{{#each cards}} <span class="card c{{name}}"><\/span> {{/each}} {{/if}}<\/td><\/tr><tr><td>{{tl \'stake\'}}: {{formatMoney Stake}} {{#compare Doubled true opeator="="}} <b style="color:orange">{{tl \'Blackjack_Doubled\'}}<\/b> {{/compare}}<br>{{#compare Payout -1 operator=">"}} <span>{{tl \'payout\'}}: {{formatMoney Payout}}<br><b style="font-size:13px">{{tlp Status \'Blackjack_\'}}<\/b> <\/span>{{/compare}} {{#compare Payout -1 operator="=="}} <span class="warn-color">{{tl \'gamestate_inprogress\'}} <\/span>{{/compare}}<\/td><\/tr>{{/each}} {{/if}}<\/table><\/td><\/tr>{{/each}} {{/if}}<\/table>');
HistoryViewController.registerView("CasinoPokerTemplate", '<table class="table table-condensed table-bordered" style="max-width:400px">{{#if ab}}<tr><td>{{tl \'poker_ante\'}}<\/td><td colspan="2">{{formatMoney ab}}<\/td><\/tr>{{/if}} {{#if rb}}<tr><td>{{tl raiseBetTitle}}<\/td><td colspan="2">{{formatMoney rb}}<\/td><\/tr>{{/if}}<tr><td><b>{{tl \'winningresult\'}}<\/b>:<\/td><td colspan="2"><b style="font-size:13px;text-transform:uppercase">{{tl finalstatus}}<\/b><\/td><\/tr>{{#if d.cards}}<tr><td width="120px"><b>{{tl \'dealer\'}}<\/b><br><\/td><td>{{#each d.cards}} <span class="card c{{name}}"><\/span> {{/each}}<\/td><td>{{tlu d.desc}}<\/td><\/tr>{{/if}} {{#if bc}}<tr><td width="120px"><b>{{tl \'poker_sharedcards\'}}<\/b><\/td><td>{{#each bc}} <span class="card c{{name}}"><\/span> {{/each}}<\/td><td><\/td><\/tr>{{/if}} {{#if p.cards}}<tr><td><b>{{tl \'baccarat_player\'}}<\/b><br>{{tl cl}}<\/td><td>{{#each p.cards}} <span class="card c{{name}}"><\/span> {{/each}}<\/td><td>{{tlu p.desc}}<\/td><\/tr>{{/if}}<\/table>');
HistoryViewController.registerView("CustomSlotDetailTemplate", '\n    {{#each customsubevents}}\n    <!-- SGLoonyBlox -->\n\n    {{#compare type "LoonyBoxCurCharacterSubEvent" operator="=="}}\n    <table>\n      <tbody>\n      <tr>\n        {{#each cidlist}}\n        <td style="padding-right: 5px;">\n          <div class="reelsymbol VideoSlots SGLoonyBlox BaseCharacter{{.}}"><\/div>\n        <\/td>\n        {{/each}}\n      <\/tr>\n      <\/tbody>\n    <\/table>\n    {{/compare}}\n\n    {{#compare type "LoonyBloxCharacterWalkSubEvent" operator="=="}}\n    <h3>{{tl \'mapfeature\'}}<\/h3>\n    <table>\n      <tbody>\n      {{#if char1WalkPath}}\n      <tr>\n        <td style="padding-right: 5px;">\n          <div class="reelsymbol VideoSlots SGLoonyBlox Car1"><\/div>\n        <\/td>\n        <td>{{char1WalkPath}}<\/td>\n      <\/tr>\n      {{/if}}\n      {{#if char2WalkPath}}\n      <tr>\n        <td style="padding-right: 5px;">\n          <div class="reelsymbol VideoSlots SGLoonyBlox Car2"><\/div>\n        <\/td>\n        <td>{{char2WalkPath}}<\/td>\n      <\/tr>\n      {{/if}}\n      {{#if char3WalkPath}}\n      <tr>\n        <td style="padding-right: 5px;">\n          <div class="reelsymbol VideoSlots SGLoonyBlox Car3"><\/div>\n        <\/td>\n        <td>{{char3WalkPath}}<\/td>\n      <\/tr>\n      {{/if}}\n      <\/tbody>\n    <\/table>\n    {{/compare}}\n\n\n    <!-- SGOceansCall -->\n\n    {{#compare type "OceansCall" operator="=="}}\n    <h3>{{tl \'symbolcollectioncounts\'}}<\/h3>\n    {{#getObjectValue data}}\n    {{#if wag}}\n    <table>\n      {{#repeat wag}}\n      <tr>\n        <td>\n          <div class="reelsymbol VideoSlots SGOceansCall idAnchor" style="opacity: 1"><\/div>\n        <\/td>\n        <td>\n          <div class="reelsymbol VideoSlots SGOceansCall idAnchor" style="opacity: 1"><\/div>\n        <\/td>\n        <td>\n          <div class="reelsymbol VideoSlots SGOceansCall idAnchor" style="opacity: 1"><\/div>\n        <\/td>\n        <td>\n          <span class="success-color">+3 {{tl \'freegames\'}}<\/span>\n        <\/td>\n      <\/tr>\n      {{/repeat}}\n    <\/table>\n    {{/if}}\n\n    {{#compare ac 0 operator=">"}}\n    <table>\n      <tr>\n        {{#repeat ac}}\n        <td>\n          <div class="reelsymbol VideoSlots SGOceansCall idAnchor" style="opacity: 1"><\/div>\n        <\/td>\n        {{/repeat}}\n        {{/compare}}\n      <\/tr>\n    <\/table>\n\n    {{/getObjectValue}}\n    {{/compare}}\n\n\n    <!-- SGDeadEscape -->\n\n    {{#compare type "DeadEscapeDuel" operator="=="}}\n\n    {{#getObjectValue data}}\n    {{#each di}}\n\n    {{tlTitleCase \'deadescape_reportduelno\'}}: {{math @index "+" 1}}\n    <table cellpadding="0" cellspacing="2" border="0">\n      <tr>\n        {{#each reel}}\n        {{setIndex @index}}\n        <td>{{#each this}}\n\n\n          {{#compareAnd @index ../../hy ../index ../../hx operator=\'==\'}}\n          <div class="reelsymbol ui-corner-all VideoSlots  {{../../../../../../../gamekeyname}} {{this}}" style="opacity: 1"><\/div>\n          {{else}}\n          {{#compareAnd @index ../../../zy ../../index ../../../zx operator=\'==\'}}\n          <div class="reelsymbol ui-corner-all VideoSlots  {{../../../../../../../../gamekeyname}} {{this}}" style="opacity: 1"><\/div>\n          {{else}}\n          <div class="reelsymbol ui-corner-all VideoSlots  {{../../../../../../../../gamekeyname}} {{this}}" style="opacity: 0.5"><\/div>\n          {{/compareAnd}}\n          {{/compareAnd}}\n\n          {{#compare @index ../../hy operator="=="}}\n          {{#compare ../../index ../../../hx operator="=="}}\n          {{else}}\n          {{/compare}}\n\n          {{else}}\n          {{/compare}}\n\n          {{/each}}\n        <\/td>\n        {{/each}}\n      <\/tr>\n    <\/table>\n    {{tlTitleCase \'deadescape_outcome\'}}\n    {{#compare do "humanWin" operator="=="}}{{tlTitleCase \'deadescape_humanwins\'}}{{/compare}}\n    {{#compare do "humanLose" operator="=="}}{{tlTitleCase \'deadescape_zombiewins\'}}{{/compare}}\n    <br/>\n    {{/each}}\n    {{/getObjectValue}}\n    {{/compare}}\n\n\n    <!-- SG5LuckyLions Pick -->\n\n    {{#compare type "5LLPick" operator="=="}}\n    <h3>{{tl \'slothistory_picktype_pick\'}}<\/h3>\n    <div>\n      {{#compare data "1" operator="=="}}\n      <div class="reelsymbol VideoSlots SG5LuckyLions idRedLionPick" style="opacity: 1"><\/div>\n      {{/compare}}\n      {{#compare data "2" operator="=="}}\n      <div class="reelsymbol VideoSlots SG5LuckyLions idPinkLionPick" style="opacity: 1"><\/div>\n      {{/compare}}\n      {{#compare data "3" operator="=="}}\n      <div class="reelsymbol VideoSlots SG5LuckyLions idYellowLionPick" style="opacity: 1"><\/div>\n      {{/compare}}\n      {{#compare data "4" operator="=="}}\n      <div class="reelsymbol VideoSlots SG5LuckyLions idVioletLionPick" style="opacity: 1"><\/div>\n      {{/compare}}\n      {{#compare data "5" operator="=="}}\n      <div class="reelsymbol VideoSlots SG5LuckyLions idGreenLionPick" style="opacity: 1"><\/div>\n      {{/compare}}\n    <\/div>\n    {{/compare}}\n\n\n    <!-- SG5LuckyLions +4 Symbols -->\n\n    {{#compare type "5LL" operator="=="}}\n    {{#getObjectValue data}}\n    <h3>{{tlup "ReelX" param0=ri }}<\/h3>\n    <table>\n      <tr>\n        <td>\n          + 4\n        <\/td>\n        <td>\n          {{#compare si "3" operator="=="}}\n          <div class="reelsymbol VideoSlots SG5LuckyLions idRedLion" style="opacity: 1"><\/div>\n          {{/compare}}\n          {{#compare si "4" operator="=="}}\n          <div class="reelsymbol VideoSlots SG5LuckyLions idPinkLion" style="opacity: 1"><\/div>\n          {{/compare}}\n          {{#compare si "5" operator="=="}}\n          <div class="reelsymbol VideoSlots SG5LuckyLions idYellowLion" style="opacity: 1"><\/div>\n          {{/compare}}\n          {{#compare si "6" operator="=="}}\n          <div class="reelsymbol VideoSlots SG5LuckyLions idVioletLion" style="opacity: 1"><\/div>\n          {{/compare}}\n          {{#compare si "7" operator="=="}}\n          <div class="reelsymbol VideoSlots SG5LuckyLions idGreenLion" style="opacity: 1"><\/div>\n          {{/compare}}\n        <\/td>\n      <\/tr>\n    <\/table>\n\n    {{/getObjectValue}}\n    {{/compare}}\n\n\n    <!-- SGScopa -->\n\n    {{#compare type "Scopa" operator="=="}}\n    <table>\n      <tbody>\n      <tr>\n        <td style="padding-right: 5px">{{tl "currentlocation"}}<\/td>\n        {{#compare cl "0" operator="=="}}\n        <td>{{tl "scopa_genoa"}}<\/td>\n        {{/compare}}\n        {{#compare cl "1" operator="=="}}\n        <td>{{tl "scopa_milan"}}<\/td>\n        {{/compare}}\n        {{#compare cl "2" operator="=="}}\n        <td>{{tl "scopa_naples"}}<\/td>\n        {{/compare}}\n        {{#compare cl "3" operator="=="}}\n        <td>{{tl "scopa_tuscany"}}<\/td>\n        {{/compare}}\n        {{#compare cl "4" operator="=="}}\n        <td>{{tl "scopa_venice"}}<\/td>\n        {{/compare}}\n      <\/tr>\n      <tr>\n        <td style="padding-right: 5px">{{tl "unlockedlocations"}}<\/td>\n        <td>\n          {{#compare ul "0" operator="=="}}\n          <span>{{tl "scopa_genoa"}}<\/span>\n          {{/compare}}\n          {{#compare ul "1" operator="=="}}\n          <span>{{tl "scopa_milan"}}<\/span>\n          {{/compare}}\n          {{#compare ul "2" operator="=="}}\n          <span>{{tl "scopa_naples"}}<\/span>\n          {{/compare}}\n          {{#compare ul "3" operator="=="}}\n          <span>{{tl "scopa_tuscany"}}<\/span>\n          {{/compare}}\n          {{#compare ul "4" operator="=="}}\n          <span>{{tl "scopa_venice"}}<\/span>\n          {{/compare}}\n          {{#compare ul "5" operator="=="}}\n          <span>{{tl "scopa_genoa"}} {{tl "scopa_milan"}} {{tl "scopa_naples"}} {{tl "scopa_tuscany"}} {{tl "scopa_venice"}}<\/span>\n          {{/compare}}\n        <\/td>\n      <\/tr>\n      {{#if pay}}\n      <tr>\n        <td style="padding-right: 5px">{{tl "scopapayout"}}<\/td>\n        <td>{{formatMoney pay}}<\/td>\n      <\/tr>\n      {{/if}}\n      <\/tbody>\n    <\/table>\n    {{/compare}}\n\n\n    <!-- SGSparta -->\n\n    {{#compare type "SpartaPickInfo" operator="=="}}\n    {{#getObjectValue data}}\n    <h3>{{tl \'slothistory_picktype_pick\'}}<\/h3>\n    <div class="SpartaShields">\n      <div style="left: 0; top: 0;"\n           class="SpartaShield">\n        {{#compare picklist.[0] 0 operator=">"}}\n        <div class="SpartaShieldText">{{picklist.[0]}}<\/div>\n        {{/compare}}\n      <\/div>\n      <div style="left: 48px; top: 0;"\n           class="SpartaShield">\n        {{#compare picklist.[1] 0 operator=">"}}\n        <div class="SpartaShieldText">{{picklist.[1]}}<\/div>\n        {{/compare}}\n      <\/div>\n      <div style="left: 96px; top: 0;"\n           class="SpartaShield">\n        {{#compare picklist.[2] 0 operator=">"}}\n        <div class="SpartaShieldText">{{picklist.[2]}}<\/div>\n        {{/compare}}\n      <\/div>\n      <div style="left: 0; top: 48px;"\n           class="SpartaShield">\n        {{#compare picklist.[3] 0 operator=">"}}\n        <div class="SpartaShieldText">{{picklist.[3]}}<\/div>\n        {{/compare}}\n      <\/div>\n      <div style="left: 48px; top: 48px;"\n           class="SpartaShield">\n        {{#compare picklist.[4] 0 operator=">"}}\n        <div class="SpartaShieldText">{{picklist.[4]}}<\/div>\n        {{/compare}}\n      <\/div>\n      <div style="left: 96px; top: 48px;"\n           class="SpartaShield">\n        {{#compare picklist.[5] 0 operator=">"}}\n        <div class="SpartaShieldText">{{picklist.[5]}}<\/div>\n        {{/compare}}\n      <\/div>\n    <\/div>\n    {{/getObjectValue}}\n    {{/compare}}\n\n\n    <!-- SGSantasVillage -->\n\n    {{#compare type "CharacterWalkSubEvent" operator="=="}}\n    <h3>{{tl \'mapfeature\'}}<\/h3>\n    <table>\n      <tbody>\n      <tr>\n        <td style="padding-right: 5px;">\n          <div class="reelsymbol VideoSlots SGSantasVillage idSantaMap"><\/div>\n        <\/td>\n        <td>{{walkPath}}<\/td>\n      <\/tr>\n      <\/tbody>\n    <\/table>\n    {{/compare}}\n\n\n    <!-- SGFortuneDogs -->\n\n    {{#compare type "FortuneDogsJPReport" operator="=="}}\n    <table class="table table-condensed" style="border: 2px solid">\n      <thead>\n      <tr>\n        <td>{{tl \'prizepottitle\'}}<\/td>\n        <td>{{tl \'atspinend\'}}<\/td>\n        <td>{{tl \'atspinend\'}}<\/td>\n      <\/tr>\n      <\/thead>\n      <tbody>\n      <tr>\n        <td>{{tl \'jackpotgrandonly\'}}<\/td>\n        <td>{{jp1_1}}<\/td>\n        <td>{{jp1_2}}<\/td>\n      <\/tr>\n      <tr>\n        <td>{{tl \'jackpotmajoronly\'}}<\/td>\n        <td>{{jp2_1}}<\/td>\n        <td>{{jp2_2}}<\/td>\n      <\/tr>\n      <tr>\n        <td>{{tl \'jackpotminoronly\'}}<\/td>\n        <td>{{jp3_1}}<\/td>\n        <td>{{jp3_2}}<\/td>\n      <\/tr>\n      <tr>\n        <td>{{tl \'jackpotminionly\'}}<\/td>\n        <td>{{jp4_1}}<\/td>\n        <td>{{jp4_2}}<\/td>\n      <\/tr>\n      <\/tbody>\n    <\/table>\n    {{/compare}}\n\n\n    <!-- SGRollingRoger -->\n\n    {{#compare type "RollingRogerAcornCounts" operator="=="}}\n    <h3>{{tl \'symbolcollectioncounts\'}}<\/h3>\n    <table>\n      <tbody>\n      <tr>\n        <td style="padding-right: 5px;">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGRollingRoger idAcorn"><\/div>\n        <\/td>\n        <td style="padding-right: 5px;">{{tl \'atspinstart\'}}<\/td>\n        <td>{{countBefore}}<\/td>\n      <\/tr>\n      <tr>\n        <td style="padding-right: 5px;">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGRollingRoger idAcorn"><\/div>\n        <\/td>\n        <td style="padding-right: 5px;">{{tl \'atspinend\'}}<\/td>\n        <td>{{countAfter}}<\/td>\n      <\/tr>\n      <\/tbody>\n    <\/table>\n    {{/compare}}\n\n\n    <!-- SGPumpkinPatch -->\n\n    {{#compare type "PumpkinPatchCornCounts" operator="=="}}\n    <h3>{{tl \'symbolcollectioncounts\'}}<\/h3>\n    <table>\n      <tbody>\n      <tr>\n        <td style="padding-right: 5px;">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGPumpkinPatch idCorn"><\/div>\n        <\/td>\n        <td style="padding-right: 5px;">{{tl \'atspinstart\'}}<\/td>\n        <td>{{countBefore}}<\/td>\n      <\/tr>\n      <tr>\n        <td style="padding-right: 5px;">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGPumpkinPatch idCorn"><\/div>\n        <\/td>\n        <td style="padding-right: 5px;">{{tl \'atspinend\'}}<\/td>\n        <td>{{countAfter}}<\/td>\n      <\/tr>\n      <\/tbody>\n    <\/table>\n    {{/compare}}\n\n\n    <!-- SGLondonHunter -->\n\n    {{#compare type "LondonHunterTanks" operator="=="}}\n    <table>\n      <tbody>\n      {{#ifnotundefined p1}}\n      <tr>\n        <td style="padding-right: 5px;">Multiplier Progress<\/td>\n        <td>{{{toPercentage p1 2}}}<\/td>\n      <\/tr>\n      {{/ifnotundefined}}\n\n      {{#ifnotundefined p2}}\n      <tr>\n        <td style="padding-right: 5px;">Wild Expansion Progress<\/td>\n        <td>{{{toPercentage p2 2}}}<\/td>\n      <\/tr>\n      {{/ifnotundefined}}\n\n      {{#ifnotundefined p3}}\n      <tr>\n        <td style="padding-right: 5px;">+2 Free Game Progress<\/td>\n        <td>{{{toPercentage p3 2}}}<\/td>\n      <\/tr>\n      {{/ifnotundefined}}\n\n      {{#ifnotundefined p4}}\n      <tr>\n        <td style="padding-right: 5px;">+1 Multiplier Progress<\/td>\n        <td>{{{toPercentage p4 2}}}<\/td>\n      <\/tr>\n      {{/ifnotundefined}}\n      <\/tbody>\n    <\/table>\n    {{/compare}}\n\n\n    <!-- SGMagicOak -->\n\n    {{#compare type "MagicOakWispCounts" operator="=="}}\n    <table>\n      <tbody>\n      <tr>\n        <td colspan="2">{{tl \'atspinstart\'}}<\/td>\n      <\/tr>\n      <tr>\n        <td style="padding-right: 5px;">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGMagicOak idWisp1"><\/div>\n        <\/td>\n        <td>{{w1c_s}}<\/td>\n      <\/tr>\n      <tr>\n        <td style="padding-right: 5px;">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGMagicOak idWisp2"><\/div>\n        <\/td>\n        <td>{{w2c_s}}<\/td>\n      <\/tr>\n\n      {{#ifdefined w1c_a}}\n      <tr>\n        <td colspan="2">{{tl \'afteraddingwisps\'}}<\/td>\n      <\/tr>\n      <tr>\n        <td style="padding-right: 5px;">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGMagicOak idWisp1"><\/div>\n        <\/td>\n        <td>{{w1c_a}}<\/td>\n      <\/tr>\n      <tr>\n        <td style="padding-right: 5px;">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGMagicOak idWisp2"><\/div>\n        <\/td>\n        <td>{{w2c_a}}<\/td>\n      <\/tr>\n      {{/ifdefined}}\n\n      {{#ifdefined w1c_e}}\n      <tr>\n        <td colspan="2">{{tl \'atspinend\'}}<\/td>\n      <\/tr>\n      <tr>\n        <td style="padding-right: 5px;">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGMagicOak idWisp1"><\/div>\n        <\/td>\n        <td>{{w1c_e}}<\/td>\n      <\/tr>\n      <tr>\n        <td style="padding-right: 5px;">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGMagicOak idWisp2"><\/div>\n        <\/td>\n        <td>{{w2c_e}}<\/td>\n      <\/tr>\n      {{/ifdefined}}\n\n      <\/tbody>\n    <\/table>\n    {{/compare}}\n\n\n    <!-- SGHappiestChristmasTree -->\n\n    {{#compare type "HappiestChristmasTreeBaubleCounts" operator="=="}}\n    <h3>{{tl \'symbolcollectioncounts\'}}<\/h3>\n    <table>\n      <tbody>\n      <tr>\n        <td style="padding-right: 5px;">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGHappiestChristmasTree idBauble1"><\/div>\n        <\/td>\n        <td>{{b1}}<\/td>\n      <\/tr>\n      <tr>\n        <td style="padding-right: 5px;">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGHappiestChristmasTree idBauble2"><\/div>\n        <\/td>\n        <td>{{b2}}<\/td>\n      <\/tr>\n      <tr>\n        <td style="padding-right: 5px;">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGHappiestChristmasTree idBauble3"><\/div>\n        <\/td>\n        <td>{{b3}}<\/td>\n      <\/tr>\n      <tr>\n        <td style="padding-right: 5px;">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGHappiestChristmasTree idBauble4"><\/div>\n        <\/td>\n        <td>{{b4}}<\/td>\n      <\/tr>\n      <\/tbody>\n    <\/table>\n    {{/compare}}\n\n\n    <!-- SGHappyApe -->\n\n    {{#compare type "HappyApe" operator="=="}}\n    {{#getObjectValue data}}\n\n    {{#ifdefinedor bt as}}\n    <div style="overflow:hidden">\n      {{#ifdefined bt}}\n      <div style="zoom:0.8;float:left;vertical-align:bottom" class="VideoSlots SGHappyApe idApeToss"><\/div>\n      {{/ifdefined}}\n      {{#ifdefined as}}\n      <div style="zoom:1;float:left;vertical-align:bottom" class="VideoSlots SGHappyApe idApeSmash"><\/div>\n      {{/ifdefined}}\n    <\/div>\n    {{/ifdefinedor}}\n    <table style="float:none">\n      <tr>\n        <td colspan="5" style="text-align:center">{{tl \'chestprizes\'}}<\/td>\n      <\/tr>\n      <tr>\n        {{#each cp}}\n        <td style="padding:4px;vertical-align:bottom">\n          {{#compare . -1 operator="=="}}\n          <div style="zoom: 0.8;" class="linesymbol VideoSlots SGHappyApe idChest"/>\n          {{/compare}}\n          {{#compare . 0 operator="=="}}\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGHappyApe idX2"/>\n          {{/compare}}\n          {{#compare . 1 operator="=="}}\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGHappyApe idX3"/>\n          {{/compare}}\n          {{#compare . 2 operator="=="}}\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGHappyApe idBothways"/>\n          {{/compare}}\n          {{#compare . 3 operator="=="}}\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGHappyApe idWild"/>\n          {{/compare}}\n          {{#compare . 4 operator="=="}}\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGHappyApe idScatter"/>\n          {{/compare}}\n        <\/td>\n        {{/each}}\n      <\/tr>\n    <\/table>\n\n    {{/getObjectValue}}\n    {{/compare}}\n\n\n    <!-- SGKnockoutFootball -->\n\n    {{#compare type "SoccerEvent" operator="=="}}\n    <table class="table table-condensed" style="border: 2px solid">\n      <tbody>\n      {{#ifdefined cupCountBefore}}\n      <tr>\n        <td style="padding-right: 5px;">{{tl \'knockoutfootball_history_cupcountatstart\'}}<\/td>\n        <td>{{cupCountBefore}}<\/td>\n      <\/tr>\n      {{/ifdefined}}\n      {{#ifdefined cupCountAfter}}\n      <tr>\n        <td style="padding-right: 5px;">{{tl \'knockoutfootball_history_cupcountatend\'}}<\/td>\n        <td>{{cupCountAfter}}<\/td>\n      <\/tr>\n      {{/ifdefined}}\n      {{#ifdefined wildCountBefore}}\n      <tr>\n        <td style="padding-right: 5px;">{{tl \'knockoutfootball_history_wildcountatstart\'}}<\/td>\n        <td>{{wildCountBefore}}<\/td>\n      <\/tr>\n      {{/ifdefined}}\n      {{#ifdefined wildCountAfter}}\n      <tr>\n        <td style="padding-right: 5px;">{{tl \'knockoutfootball_history_cupcountatend\'}}<\/td>\n        <td>{{wildCountAfter}}<\/td>\n      <\/tr>\n      {{/ifdefined}}\n      {{#ifdefined playerScore}}\n      <tr>\n        <td style="padding-right: 5px;">{{tl \'knockout_history_playerscore\'}}<\/td>\n        <td>{{playerScore}}<\/td>\n      <\/tr>\n      {{/ifdefined}}\n      {{#ifdefined opponentScore}}\n      <tr>\n        <td style="padding-right: 5px;">{{tl \'knockout_history_opponentscore\'}}<\/td>\n        <td>{{opponentScore}}<\/td>\n      <\/tr>\n      {{/ifdefined}}\n      {{#ifdefined matchTime}}\n      <tr>\n        <td style="padding-right: 5px;">{{tl \'knockout_history_matchtime\'}}<\/td>\n        <td>{{matchTime}}<\/td>\n      <\/tr>\n      {{/ifdefined}}\n      {{#ifdefined matchResult}}\n      <tr>\n        <td style="padding-right: 5px;">{{tl \'knockout_history_matchresult\'}}<\/td>\n        <td>{{tl matchResult}}<\/td>\n      <\/tr>\n      {{/ifdefined}}\n      {{#ifdefined tpBefore}}\n      <tr>\n        <td style="padding-right: 5px;">{{tl \'knockout_history_tournamentprogressatstart\'}}<\/td>\n        <td>{{tl tpBefore}}<\/td>\n      <\/tr>\n      {{/ifdefined}}\n      {{#ifdefined tpAfter}}\n      <tr>\n        <td style="padding-right: 5px;">{{tl \'knockout_history_tournamentprogressatend\'}}<\/td>\n        <td>{{tl tpAfter}}<\/td>\n      <\/tr>\n      {{/ifdefined}}\n      {{#ifdefined tpResult}}\n      <tr>\n        <td style="padding-right: 5px;">{{tl \'knockout_history_tournamentresult\'}}<\/td>\n        <td>{{tl tpResult}}<\/td>\n      <\/tr>\n      {{/ifdefined}}\n      <\/tbody>\n    <\/table>\n    {{/compare}}\n\n\n    <!-- SGPresto -->\n\n    {{#compare type "PrestoCountDown" operator="=="}}\n    <h3>{{tl \'illusioncountdown\'}} {{#if trick}}&nbsp;({{tl trick}}){{/if}}<\/h3>\n    <table>\n      <tbody>\n      <tr>\n        <td style="padding-right: 5px;">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGPresto idFeather"><\/div>\n        <\/td>\n        <td style="padding-right: 5px;">{{tl \'atspinstart\'}}<\/td>\n        <td>{{countBefore}}<\/td>\n      <\/tr>\n      <tr>\n        <td style="padding-right: 5px;">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGPresto idFeather"><\/div>\n        <\/td>\n        <td style="padding-right: 5px;">{{tl \'atspinend\'}}<\/td>\n        <td>{{countAfter}}<\/td>\n      <\/tr>\n      <\/tbody>\n    <\/table>\n    {{/compare}}\n\n\n    <!-- SGWildTrucks -->\n\n    {{#compare type "WildTruckBarCounter" operator="=="}}\n\n    {{countbefore}}\n    <h3>{{tl \'count\'}}<\/h3>\n    <table>\n      <tr>\n        <td>&nbsp<\/td>\n        <td style="padding:5px">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGWildTrucks idTruck1"><\/div>\n        <\/td>\n        <td style="padding:5px">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGWildTrucks idTruck2"><\/div>\n        <\/td>\n        <td style="padding:5px">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGWildTrucks idTruck3"><\/div>\n        <\/td>\n        <td style="padding:5px">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGWildTrucks idTruck4"><\/div>\n        <\/td>\n        <td style="padding:5px">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGWildTrucks idTruck5"><\/div>\n        <\/td>\n      <\/tr>\n      <tr>\n        <td style="padding-right: 5px;">{{tl \'atspinstart\'}}<\/td>\n        {{#each barcountbefore}}\n        <td style="text-align:center">{{.}}<\/td>\n        {{/each}}\n      <\/tr>\n      <tr>\n        <td style="padding-right: 5px;">{{tl \'atspinend\'}}<\/td>\n        {{#each barcountafter}}\n        <td style="text-align:center">{{.}}<\/td>\n        {{/each}}\n      <\/tr>\n    <\/table>\n    {{/compare}}\n\n\n    <!-- SGWizardsWantWar -->\n\n    {{#compare type "WizardsWantWarPickReportMessage" operator="=="}}\n    <h3>{{tl \'slothistory_picktype_pick\'}}<\/h3>\n    <div>\n      <div class="reelsymbol VideoSlots SGWizardsWantWar {{scatter}}" style="display: inline-block"><\/div>\n      <span>&nbsp<\/span>\n      <div class="reelsymbol VideoSlots SGWizardsWantWar {{wizard}}" style="display: inline-block"><\/div>\n    <\/div>\n    {{/compare}}\n\n    {{#compare type "WizardsWantWarProgressReportMessage" operator="=="}}\n    <h3>{{tl \'count\'}}<\/h3>\n    <table>\n      <thead>\n      <tr>\n        <th>&nbsp<\/th>\n        <th style="padding:5px">{{tl \'atspinstart\'}}<\/th>\n        <th style="padding:5px">{{tl \'atspinend\'}}<\/th>\n      <\/tr>\n      <\/thead>\n      <tbody>\n      <tr>\n        <td style="padding:5px">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGWizardsWantWar idWizard1"><\/div>\n        <\/td>\n        <td style="padding:5px">{{w1s}}<\/td>\n        <td style="padding:5px">{{w1e}}<\/td>\n      <\/tr>\n      <tr>\n        <td style="padding:5px">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGWizardsWantWar idWizard2"><\/div>\n        <\/td>\n        <td style="padding:5px">{{w2s}}<\/td>\n        <td style="padding:5px">{{w2e}}<\/td>\n      <\/tr>\n      <\/tbody>\n    <\/table>\n    {{/compare}}\n\n\n    <!-- SGNuwa -->\n\n    {{#compare type "NuwaCollect" operator="=="}}\n    <h3>{{tl \'symbolcollectioncounts\'}}<\/h3>\n    {{#getObjectValue data}}\n\n    <table>\n      <tr>\n        <td>\n          {{#compare lst.[0] true operator="=="}}\n          <div style="text-align: left; zoom: 0.6;" class="reelSymbol VideoSlots SGNuwa idEarthElement"/>\n          {{/compare}}\n        <\/td>\n        <td>\n          {{#compare lst.[0] false operator="=="}}\n          <div style="text-align: left; zoom: 0.6;" class="reelSymbol VideoSlots SGNuwa idEarthElement disabled"/>\n          {{/compare}}\n        <\/td>\n        <td>\n          {{#compare lst.[1] true operator="=="}}\n          <div style="text-align: left; zoom: 0.6;" class="reelSymbol VideoSlots SGNuwa idWaterElement"/>\n          {{/compare}}\n        <\/td>\n        <td>\n          {{#compare lst.[1] false operator="=="}}\n          <div style="text-align: left; zoom: 0.6;" class="reelSymbol VideoSlots SGNuwa idWaterElement disabled"/>\n          {{/compare}}\n        <\/td>\n        <td>\n          {{#compare lst.[2] true operator="=="}}\n          <div style="text-align: left; zoom: 0.6;" class="reelSymbol VideoSlots SGNuwa idFireElement"/>\n          {{/compare}}\n        <\/td>\n        <td>\n          {{#compare lst.[2] false operator="=="}}\n          <div style="text-align: left; zoom: 0.6;" class="reelSymbol VideoSlots SGNuwa idFireElement disabled"/>\n          {{/compare}}\n        <\/td>\n        <td>\n          {{#compare lst.[3] true operator="=="}}\n          <div style="text-align: left; zoom: 0.6;" class="reelSymbol VideoSlots SGNuwa idWindElement"/>\n          {{/compare}}\n        <\/td>\n        <td>\n          {{#compare lst.[3] false operator="=="}}\n          <div style="text-align: left; zoom: 0.6;" class="reelSymbol VideoSlots SGNuwa idWindElement disabled"/>\n          {{/compare}}\n        <\/td>\n        <td>\n          {{#compare lst.[4] true operator="=="}}\n          <div style="text-align: left; zoom: 0.6;" class="reelSymbol VideoSlots SGNuwa idThunderElement"/>\n          {{/compare}}\n        <\/td>\n        <td>\n          {{#compare lst.[4] false operator="=="}}\n          <div style="text-align: left; zoom: 0.6;" class="reelSymbol VideoSlots SGNuwa idThunderElement disabled"/>\n          {{/compare}}\n        <\/td>\n      <\/tr>\n      {{/getObjectValue}}\n    <\/table>\n    {{/compare}}\n\n\n    <!-- SGJump -->\n\n    {{#compare type "JumpSymbolCounts1" operator="=="}}\n    <h3>{{tl \'symbolcollectioncounts\'}}<\/h3>\n    <table class="table table-condensed">\n      <thead>\n      <tr>\n        <td>{{tl \'symbol\'}}<\/td>\n        <td>{{tl \'count\'}}<\/td>\n      <\/tr>\n      <\/thead>\n      <tbody>\n      <tr>\n        <td>\n          <div style="text-align: left; zoom: 0.6;" class="linesymbol VideoSlots SGJump idBell"/>\n        <\/td>\n        <td>{{sym1}}<\/td>\n      <\/tr>\n      <tr>\n        <td>\n          <div style="text-align: left; zoom: 0.6;" class="linesymbol VideoSlots SGJump idSeven"/>\n        <\/td>\n        <td>{{sym2}}<\/td>\n      <\/tr>\n      <tr>\n        <td>\n          <div style="text-align: left; zoom: 0.6;" class="linesymbol VideoSlots SGJump idWatermelon"/>\n        <\/td>\n        <td>{{sym3}}<\/td>\n      <\/tr>\n      <tr>\n        <td>\n          <div style="text-align: left; zoom: 0.6;" class="linesymbol VideoSlots SGJump idLemon"/>\n        <\/td>\n        <td>{{sym4}}<\/td>\n      <\/tr>\n      <tr>\n        <td>\n          <div style="text-align: left; zoom: 0.6;" class="linesymbol VideoSlots SGJump idPlum"/>\n        <\/td>\n        <td>{{sym5}}<\/td>\n      <\/tr>\n      <tr>\n        <td>\n          <div style="text-align: left; zoom: 0.6;" class="linesymbol VideoSlots SGJump idCherry"/>\n        <\/td>\n        <td>{{sym6}}<\/td>\n      <\/tr>\n      <\/tbody>\n    <\/table>\n    {{/compare}}\n\n\n    <!-- SGWickedWitch -->\n\n    {{#compare type "WickedWitchSymbolCounts" operator="=="}}\n    <h3>{{tl \'symbolcollectioncounts\'}}<\/h3>\n    <table class="table table-condensed">\n      <thead>\n      <tr>\n        <td>{{tl \'symbol\'}}<\/td>\n        <td>{{tl \'count\'}}<\/td>\n      <\/tr>\n      <\/thead>\n      <tbody>\n      <tr>\n        <td>\n          <div style="text-align: left; zoom: 0.6;" class="linesymbol VideoSlots SGWickedWitch idEyeBall"/>\n        <\/td>\n        <td>{{WW_numEyeBall}}<\/td>\n      <\/tr>\n      <tr>\n        <td>\n          <div style="text-align: left; zoom: 0.6;" class="linesymbol VideoSlots SGWickedWitch idFrog"/>\n        <\/td>\n        <td>{{WW_numFrog}}<\/td>\n      <\/tr>\n      <tr>\n        <td>\n          <div style="text-align: left; zoom: 0.6;" class="linesymbol VideoSlots SGWickedWitch idPoison"/>\n        <\/td>\n        <td>{{WW_numShrub}}<\/td>\n      <\/tr>\n      <tr>\n        <td>\n          <div style="text-align: left; zoom: 0.6;" class="linesymbol VideoSlots SGWickedWitch idMushroom"/>\n        <\/td>\n        <td>{{WW_numMushroom}}<\/td>\n      <\/tr>\n      <\/tbody>\n    <\/table>\n    {{/compare}}\n\n\n    <!-- SGHeySushi -->\n\n    {{#compare type "HeySushi" operator="=="}}\n    <h3>{{tl \'payoutbonustitle\'}}<\/h3>\n    <table>\n      <tbody>\n      {{#each bpayl}}\n      <tr>\n        <td style="padding:5px">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGHeySushi {{sid}}"/>\n        <\/td>\n        <td>{{formatMoney pay}}<\/td>\n      <\/tr>\n      {{/each}}\n      <\/tbody>\n    <\/table>\n    {{/compare}}\n\n\n    <!-- SGBeforeTimeRunsOUt -->\n\n    {{#compare type "BeforeTimeRunsOutGemCount" operator="=="}}\n    <h3>{{tl \'symbolcollectioncounts\'}}<\/h3>\n    <table>\n      <tbody>\n      <tr>\n        {{#repeat gemC}}\n        <td style="padding:5px">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGBeforeTimeRunsOut idGem{{math @index \'+\' 1}}"/>\n        <\/td>\n        {{/repeat}}\n      <\/tr>\n\n      <\/tbody>\n    <\/table>\n    {{/compare}}\n\n    <!-- SGCandyTower -->\n\n    {{#compare type "CandyTowerCounts" operator="=="}}\n    <h3>{{tl \'symbolcollectioncounts\'}}<\/h3>\n    <table>\n      <tbody>\n      <tr>\n        <td style="padding: 0 2px 0 0">\n          {{#compare redActive true operator="=="}}\n          <div style="zoom: 0.6;opacity:1" class="linesymbol VideoSlots SGCandyTower idRed"><\/div>\n          {{else}}\n          <div style="zoom: 0.6;opacity:0.5" class="linesymbol VideoSlots SGCandyTower idRed"><\/div>\n          {{/compare}}\n        <\/td>\n        <td style="padding: 0 2px;">\n          {{#compare greenActive true operator="=="}}\n          <div style="zoom: 0.6;opacity:1" class="linesymbol VideoSlots SGCandyTower idGreen"><\/div>\n          {{else}}\n          <div style="zoom: 0.6;opacity:0.5" class="linesymbol VideoSlots SGCandyTower idGreen"><\/div>\n          {{/compare}}\n        <\/td>\n        <td style="padding: 0 2px;">\n          {{#compare pinkActive true operator="=="}}\n          <div style="zoom: 0.6;opacity:1" class="linesymbol VideoSlots SGCandyTower idPink"><\/div>\n          {{else}}\n          <div style="zoom: 0.6;opacity:0.5" class="linesymbol VideoSlots SGCandyTower idPink"><\/div>\n          {{/compare}}\n        <\/td>\n        <td style="padding: 0 2px;">\n          {{#compare yellowActive true operator="=="}}\n          <div style="zoom: 0.6;opacity:1" class="linesymbol VideoSlots SGCandyTower idYellow"><\/div>\n          {{else}}\n          <div style="zoom: 0.6;opacity:0.5" class="linesymbol VideoSlots SGCandyTower idYellow"><\/div>\n          {{/compare}}\n        <\/td>\n        <td style="padding: 0 0 0 2px">\n          {{#compare blueActive true operator="=="}}\n          <div style="zoom: 0.6;opacity:1" class="linesymbol VideoSlots SGCandyTower idBlue"><\/div>\n          {{else}}\n          <div style="zoom: 0.6;opacity:0.5" class="linesymbol VideoSlots SGCandyTower idBlue"><\/div>\n          {{/compare}}\n        <\/td>\n      <\/tr>\n      <tr>\n        <td style="text-align: center">\n          {{redAfter}}\n        <\/td>\n        <td style="text-align: center">\n          {{greenAfter}}\n        <\/td>\n        <td style="text-align: center">\n          {{pinkAfter}}\n        <\/td>\n        <td style="text-align: center">\n          {{yellowAfter}}\n        <\/td>\n        <td style="text-align: center">\n          {{blueAfter}}\n        <\/td>\n      <\/tr>\n      <\/tbody>\n    <\/table>\n    {{/compare}}\n\n    {{#compare type "CandyTowerFG" operator="=="}}\n    <h3>{{tl \'activeboosts\'}}<\/h3>\n    <table>\n      <tbody>\n      <tr>\n        {{#compare al.[0] true operator="=="}}\n        <td>\n          <div style="zoom: 0.6;opacity:1" class="linesymbol VideoSlots SGCandyTower idRed"><\/div>\n        <\/td>\n        {{/compare}}\n        {{#compare al.[1] true operator="=="}}\n        <td>\n          <div style="zoom: 0.6;opacity:1" class="linesymbol VideoSlots SGCandyTower idGreen"><\/div>\n        <\/td>\n        {{/compare}}\n        {{#compare al.[2] true operator="=="}}\n        <td>\n          <div style="zoom: 0.6;opacity:1" class="linesymbol VideoSlots SGCandyTower idPink"><\/div>\n        <\/td>\n        {{/compare}}\n        {{#compare al.[3] true operator="=="}}\n        <td>\n          <div style="zoom: 0.6;opacity:1" class="linesymbol VideoSlots SGCandyTower idYellow"><\/div>\n        <\/td>\n        {{/compare}}\n        {{#compare al.[4] true operator="=="}}\n        <td>\n          <div style="zoom: 0.6;opacity:1" class="linesymbol VideoSlots SGCandyTower idBlue"><\/div>\n        <\/td>\n        {{/compare}}\n      <\/tr>\n    <\/table>\n    {{/compare}}\n\n    <!-- SGMarvelousFurlongs -->\n\n    {{#compare type "MarvelousFurlongsPick" operator="=="}}\n    <table>\n      <tbody>\n      <tr>\n        {{#compare hId 0 operator="=="}}\n        <td>\n          <div style="zoom: 0.6;opacity:1" class="linesymbol VideoSlots SGMarvelousFurlongs idBlueHorse"><\/div>\n        <\/td>\n        {{/compare}}\n        {{#compare hId 1 operator="=="}}\n        <td>\n          <div style="zoom: 0.6;opacity:1" class="linesymbol VideoSlots SGMarvelousFurlongs idPurpleHorse"><\/div>\n        <\/td>\n        {{/compare}}\n        {{#compare hId 2 operator="=="}}\n        <td>\n          <div style="zoom: 0.6;opacity:1" class="linesymbol VideoSlots SGMarvelousFurlongs idGreenHorse"><\/div>\n        <\/td>\n        {{/compare}}\n        {{#compare hId 3 operator="=="}}\n        <td>\n          <div style="zoom: 0.6;opacity:1" class="linesymbol VideoSlots SGMarvelousFurlongs idRedHorse"><\/div>\n        <\/td>\n        {{/compare}}\n      <\/tr>\n      <\/tbody>\n    <\/table>\n    {{/compare}}\n\n    {{#compare type "MarvelousFurlongsRacePosition" operator="=="}}\n    <h3>{{tl \'RacePosition\'}}<\/h3>\n    <table>\n      <tbody>\n      <tr>\n        {{#compare hId 0 operator="=="}}\n        <td>\n          <div style="zoom: 0.6;opacity:1" class="linesymbol VideoSlots SGMarvelousFurlongs idBlueHorse"><\/div>\n        <\/td>\n        {{/compare}}\n        {{#compare hId 1 operator="=="}}\n        <td>\n          <div style="zoom: 0.6;opacity:1" class="linesymbol VideoSlots SGMarvelousFurlongs idPurpleHorse"><\/div>\n        <\/td>\n        {{/compare}}\n        {{#compare hId 2 operator="=="}}\n        <td>\n          <div style="zoom: 0.6;opacity:1" class="linesymbol VideoSlots SGMarvelousFurlongs idGreenHorse"><\/div>\n        <\/td>\n        {{/compare}}\n        {{#compare hId 3 operator="=="}}\n        <td>\n          <div style="zoom: 0.6;opacity:1" class="linesymbol VideoSlots SGMarvelousFurlongs idRedHorse"><\/div>\n        <\/td>\n        {{/compare}}\n        <td style="padding-left:10px">\n          <b>{{pos}}<\/b>\n        <\/td>\n      <\/tr>\n      <\/tbody>\n    <\/table>\n    {{/compare}}\n\n    {{#compare type "MarvelousFurlongsBaseEvent" operator="=="}}\n    <h3>{{tl \'status\'}}<\/h3>\n    <table>\n      <tbody>\n      <tr>\n        <td><\/td>\n        <td>\n          {{{spinsToNextDay}}}\n        <\/td>\n      <\/tr>\n      <tr>\n        <td style="padding-right: 1em;">\n          {{tl \'CurDay\'}}\n        <\/td>\n        <td>\n          {{dtCurString}}\n        <\/td>\n      <\/tr>\n      {{#ifdefined dtNextString}}\n      <tr>\n        <td style="padding-right: 1em;">\n          {{tl \'NextRaceDay\'}}\n        <\/td>\n        <td>\n          {{dtNextString}}\n        <\/td>\n      <\/tr>\n      {{/ifdefined}}\n      <\/tbody>\n    <\/table>\n    {{/compare}}\n\n\n    {{#compare type "ProstGameReport" operator="=="}}\n    <h3>{{tl \'mapfeature\'}}<\/h3>      \n\n    <table>\n      <tbody>\n        <tr>\n           {{#each m0.l}}\n            <td style="padding-right:10px"><div style="zoom: 1;opacity:1;float:left" class="linesymbol VideoSlots SGProst idBuilding{{ht}}">\n            <\/td>\n           {{/each}}\n        <\/tr>\n           {{#each m0.l}}\n            <td style="padding-right:10px">\n              {{#if f}}\n                <div style="zoom: 0.5;opacity:1" class="linesymbol VideoSlots SGProst idFeatureBadge">\n              {{/if}}\n              {{#if cp}}\n                <div style="zoom: 0.5;opacity:1" class="linesymbol VideoSlots SGProst idCashPrizeBadge">\n              {{/if}}\n            <\/td>\n           {{/each}}\n        <tr>\n        <\/tr>\n        <tr><td><div style="zoom: 1;opacity:1;float:left" class="linesymbol VideoSlots SGProst idMapHans"><\/div><\/td><\/tr>\n        <tr>\n           {{#each m1.l}}\n            <td style="padding-right:10px"><div style="zoom: 1;opacity:1;float:left" class="linesymbol VideoSlots SGProst idBuilding{{ht}}">\n            <\/td>\n           {{/each}}\n        <\/tr>\n           {{#each m1.l}}\n            <td style="padding-right:10px">\n              {{#if f}}\n                <div style="zoom: 0.5;opacity:1" class="linesymbol VideoSlots SGProst idFeatureBadge">\n              {{/if}}\n              {{#if cp}}\n                <div style="zoom: 0.5;opacity:1" class="linesymbol VideoSlots SGProst idCashPrizeBadge">\n              {{/if}}\n            <\/td>\n           {{/each}}\n        <tr><td><div style="zoom: 1;opacity:1;float:left" class="linesymbol VideoSlots SGProst idMapHeidi"><\/div><\/td><\/tr>\n      <\/tbody>\n    <\/table>\n    {{/compare}}\n\n\n\n\n    {{#compare type "FlyGameReport" operator="=="}}\n    <h3>{{tl \'status\'}}<\/h3>\n    <table>\n      <tbody>\n      {{#ifdefined suc}}\n      <tr>\n        <td style="padding-right: 1em;">{{tl \'SpinsUntilNextCollection\'}}<\/td>\n        <td>\n          {{{suc}}}\n        <\/td>\n      <\/tr>\n      {{/ifdefined}}\n      {{#compare totalC 0 operator=">"}}\n      <tr>\n        <td style="padding-right: 1em;">\n          {{tl \'symbolcollectioncounts\'}}\n        <\/td>\n        <td>\n          {{#repeat rc}}\n          <div style="zoom: 0.6;opacity:1;float:left" class="linesymbol VideoSlots SGFly idColRed"><\/div>\n          {{/repeat}}\n          {{#repeat bc}}\n          <div style="zoom: 0.6;opacity:1;float:left" class="linesymbol VideoSlots SGFly idColBlue"><\/div>\n          {{/repeat}}\n          {{#repeat pc}}\n          <div style="zoom: 0.6;opacity:1;float:left" class="linesymbol VideoSlots SGFly idColPurple"><\/div>\n          {{/repeat}}\n          {{#repeat gc}}\n          <div style="zoom: 0.6;opacity:1;float:left" class="linesymbol VideoSlots SGFly idColGreen"><\/div>\n          {{/repeat}}\n        <\/td>\n      <\/tr>\n      {{/compare}}\n      <\/tbody>\n    <\/table>\n    {{/compare}}\n\n\n    {{#compare type "PrizesCollected" operator="=="}}\n    <h3>{{tl \'collectedprizes\'}}<\/h3>\n    {{#each people}}\n    <div style="padding-right: 1em; display: inline-block;">{{this}}<\/div>\n    {{/each}}\n    {{/compare}}\n\n    {{#compare type "NewYearsBashReport" operator="=="}}\n\n    {{countbefore}}\n    <h3>{{tl \'count\'}}<\/h3>\n    <table>\n      <tr>\n        <td>&nbsp<\/td>\n        <td style="padding:5px">\n             <div style="zoom: 0.6;" class="linesymbol VideoSlots SGNewYearsBash idDubai {{#compare countAfter.[0] 9 operator="<="}}disabled{{/compare}}">\n        <\/td>\n        <td style="padding:5px">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGNewYearsBash idSydney {{#compare countAfter.[1] 9 operator="<="}}disabled{{/compare}}"><\/div>\n        <\/td>\n        <td style="padding:5px">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGNewYearsBash idSanFrancisco {{#compare countAfter.[2] 9 operator="<="}}disabled{{/compare}}"><\/div>\n        <\/td>\n        <td style="padding:5px">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGNewYearsBash idNewYork {{#compare countAfter.[3] 9 operator="<="}}disabled{{/compare}}"><\/div>\n        <\/td>\n        <td style="padding:5px">\n          <div style="zoom: 0.6;" class="linesymbol VideoSlots SGNewYearsBash idLondon {{#compare countAfter.[4] 9 operator="<="}}disabled{{/compare}}"><\/div>\n        <\/td>\n      <\/tr>\n      <tr>\n        <td>&nbsp;<\/td>\n        {{#each countAfter}}\n        <td style="text-align:center">{{.}}<\/td>\n        {{/each}}\n      <\/tr>\n    <\/table>\n\n  {{/compare}}\n\n   {{#compare type "DiscoBeatsGameReport" operator="=="}}\n\n      <table>\n        <tr>        \n          <td><div style="zoom: 1;opacity:1" class="linesymbol VideoSlots SGDiscoBeats idPrize{{pId}}"><\/div><\/td>\n        <\/tr>\n      <\/table>\n  {{/compare}}\n\n    {{/each}}\n  ');
HistoryViewController.registerView("DragonTigerTemplate", '<table class="table table-condensed table-bordered" style="max-width:400px"><tr><td>{{tl \'dragontiger_dragon\'}}<br><span class="card c{{dc}}"><\/span><\/td><td>{{tl \'dragontiger_tiger\'}}<br><span class="card c{{tc}}"><\/span><\/td><\/tr><\/table>{{#if bet}}<br><table class="table table-condensed table-bordered" style="max-width:400px"><thead><tr><th>{{tl \'description\'}}<\/th><th class="currency">{{tl \'bettitle\'}}<\/th><th class="currency">{{tl \'payout\'}}<\/th><\/tr><\/thead><tbody>{{#each bet}}<tr><td>{{#if suitString}}<table><tr><td>{{tl groupTypeName}} -<\/td><td><span class="suit suit{{suitString}}"><\/span><\/td><\/tr><\/table>{{else}} {{# if groupTypeName }} {{tl groupTypeName}} - {{/if}} {{tl n}} {{/if}}<\/td><td class="currency">{{formatMoney b}}<\/td><td class="currency">{{formatMoney p}}<\/td><\/tr>{{/each}}<\/tbody><\/table>{{/if}}');
HistoryViewController.registerView("GambleTemplate", '\n      <table border="0" cellpadding="4" cellspacing="0" style="width: 450px">\n          <tr>\n              <td>\n                  {{tl \'gamble_dealercard\'}}\n              <\/td>\n              <td>\n                  <span class="card c{{d}}"/>\n              <\/td>\n              <td>\n                  {{tl \'gamble_playercard\'}}\n              <\/td>\n              <td>\n                  <span class="card c{{p}}"/>\n              <\/td>\n          <\/tr>\n      <\/table>\n  ');
HistoryViewController.registerView("GameDetailsContainer", '<div id="lblCheat">CHEAT WAS USED<\/div><table id="tblDetail" class="table table-condensed table-bordered" style="max-width:560px"><tbody><tr><td><span id="lblGameName"><\/span><\/td><td class="currency">{{tl \'gamenumber\'}} <span id="lblFriendlyId"><\/span><\/td><\/tr><tr><td>{{tl \'status\'}}<\/td><td class="currency"><span id="lblGameStatus"><\/span><\/td><\/tr><tr><td>{{tl \'currency\'}}<\/td><td class="currency"><span id="lblGameCurrency"><\/span><\/td><\/tr><tr class="slotBetDetails lines"><td><span id="lblLinesWaysDesc"><\/span><\/td><td class="currency"><span id="lblLinesWays"><\/span><\/td><\/tr><tr class="slotBetDetails"><td>{{tl \'coindenom\'}}<\/td><td class="currency"><span id="lblCoinDenom"><\/span><\/td><\/tr><tr class="slotBetDetails"><td>{{tl \'coins\'}}<\/td><td class="currency"><span id="lblCoins"><\/span><\/td><\/tr><tr class="slotBetDetails"><td>{{tl \'betlevel\'}}<\/td><td class="currency"><span id="lblBetLevel"><\/span><\/td><\/tr><tr><td>{{tl \'stakerealtitle\'}}<\/td><td class="currency"><span id="lblRealStake"><\/span><\/td><\/tr><tr class="bonusinfo"><td>{{tl \'stakebonustitle\'}}<\/td><td class="currency"><span id="lblBonusStake"><\/span><\/td><\/tr><tr><td>{{tl \'payoutrealtitle\'}}<\/td><td class="currency"><span id="lblRealPayout"><\/span><\/td><\/tr><tr class="bonusinfo"><td>{{tl \'payoutbonustitle\'}}<\/td><td class="currency"><span id="lblBonusPayout"><\/span><\/td><\/tr><tr class="bonusconvert"><td>{{tl \'bonusbalance\'}} &#187; {{tl \'realbalance\'}}<\/td><td class="currency"><span id="lblBonusToReal"><\/span><\/td><\/tr><tr><td>{{tl \'startdate\'}}<\/td><td class="currency"><span id="lblDtStarted"><\/span><\/td><\/tr><tr><td>{{tl \'enddate\'}}<\/td><td class="currency"><span id="lblDtCompleted"><\/span><\/td><\/tr><tr class="jackpotcontribution"><td>{{tl \'jackpotcontribution\'}}<\/td><td class="currency"><span id="lblJackpotContribution"><\/span><\/td><\/tr><tr class="balanceafter"><td>{{tl \'balance\'}}<\/td><td class="currency"><span id="lblBalanceAfter"><\/span><\/td><\/tr><tr class="playerusername"><td>{{tl \'username\'}}<\/td><td class="currency"><span id="lblPlayerUsername"><\/span><\/td><\/tr><tr class="srijSMResult"><td>sm_result<\/td><td><span id="lblSRIJSMResult" style="word-break:break-all;user-select:all;cursor:pointer"><\/span><\/td><\/tr><tr class="jurisCode"><td><span id="lblJurisCodeLabel"><\/span><\/td><td class="currency"><span id="lblJurisCode"><\/span><\/td><\/tr><\/tbody><\/table><div id="GameDetailsContent"><\/div>');
HistoryViewController.registerView("NiuNiuPokerTemplate", "<table class=\"table table-condensed table-bordered\" style=\"max-width:400px\"><tr><td>{{tl 'poker_ante'}}<\/td><td>{{formatMoney NiuNiuPokerDetails.AnteBet}}<\/td><\/tr><tr><td>{{tl 'payout'}}<\/td><td>{{formatMoney NiuNiuPokerDetails.TotalPayout}}<\/td><\/tr><tr><td>{{tl 'winningresult'}}<\/td><td><b style=\"font-size:13px;text-transform:uppercase\">{{tl NiuNiuPokerDetails.GameOutCome}}<\/b><\/td><\/tr>{{#if NiuNiuPokerDetails.PlayerCards}}<tr><td>{{tl 'baccarat_player'}}<\/td><td>{{#each NiuNiuPokerDetails.PlayerCards}} <span class=\"card c{{Name}}\"><\/span> {{/each}} {{tlu NiuNiuPokerDetails.PlayerHandDesc}}{{#if NiuNiuPokerDetails.PlayerPointValue}}&nbsp;{{NiuNiuPokerDetails.PlayerPointValue}}{{/if}}<\/td><\/tr>{{/if}} {{#if NiuNiuPokerDetails.PlayerCardLHS}}<tr><td>{{tl 'niuniu_leftcards'}}<\/td><td>{{#each NiuNiuPokerDetails.PlayerCardLHS}} <span class=\"card c{{Name}}\"><\/span> {{/each}}<\/td><\/tr>{{/if}} {{#if NiuNiuPokerDetails.PlayerCardRHS}}<tr><td><b>{{tl 'niuniu_rightcards'}}<\/b><\/td><td>{{#each NiuNiuPokerDetails.PlayerCardRHS}} <span class=\"card c{{Name}}\"><\/span> {{/each}}<\/td><\/tr>{{/if}} {{#if NiuNiuPokerDetails.DealerCards}}<tr><td><b>{{tl 'dealer'}}<\/b><\/td><td>{{#each NiuNiuPokerDetails.DealerCards}} <span class=\"card c{{Name}}\"><\/span> {{/each}} {{tlu NiuNiuPokerDetails.DealerHandDesc}}{{#if NiuNiuPokerDetails.DealerPointValue}}&nbsp;{{NiuNiuPokerDetails.DealerPointValue}}{{/if}}<\/td><\/tr>{{/if}} {{#if NiuNiuPokerDetails.DealerCardLHS}}<tr><td><b>{{tl 'niuniu_leftcards'}}<\/b><\/td><td>{{#each NiuNiuPokerDetails.DealerCardLHS}} <span class=\"card c{{Name}}\"><\/span> {{/each}}<\/td><\/tr>{{/if}} {{#if NiuNiuPokerDetails.DealerCardRHS}}<tr><td><b>{{tl 'niuniu_rightcards'}}<\/b><\/td><td>{{#each NiuNiuPokerDetails.DealerCardRHS}} <span class=\"card c{{Name}}\"><\/span> {{/each}}<\/td><\/tr>{{/if}}<\/table>");
HistoryViewController.registerView("PotSymbolListTemplate", '<table class="table table-bordered table-condensed"><tr>{{#each potlist}} {{setIndex @index}}<td><div class="VideoSlots {{../gamekeyname}} {{this}}"><\/div><\/td>{{/each}}<\/tr><\/table>');
HistoryViewController.registerView("RaiseItUpPokerTemplate", '\n\n    <table class="table table-condensed table-bordered" style="max-width: 400px">\n      {{#if abet}}\n        <tr>\n          <td>\n            {{tl \'poker_ante\'}}\n          <\/td>\n          <td>\n            {{formatMoney abet}}\n          <\/td>\n        <\/tr>\n      {{/if}}\n\n      <tr>\n        <td>\n          1\n        <\/td>\n        <td>\n          {{formatMoney b1}}\n        <\/td>\n      <\/tr>\n      <tr>\n        <td>\n          2\n        <\/td>\n        <td>\n          {{formatMoney b2}}\n        <\/td>\n      <\/tr>\n      <tr>\n        <td>\n          <b>{{tl \'winningresult\'}}<\/b>:\n        <\/td>\n        <td>\n          <b style="font-size: 13px; text-transform: uppercase">{{tl fs}}<\/b>\n        <\/td>\n      <\/tr>\n      <tr>\n        <td width="120px">\n          <b>{{tl \'Poker_SharedCards\'}}<\/b>\n        <\/td>\n        <td>\n          {{#each bc}}\n            <span class="card c{{.}}"><\/span>\n          {{/each}}\n        <\/td>\n      <\/tr>\n\n      {{#if ph}}\n        <tr>\n          <td>\n            <b>{{tl \'baccarat_player\'}}<\/b>\n          <\/td>\n          <td>\n            {{#each ph}}\n              <span class="card c{{.}}"><\/span>\n            {{/each}}\n\n            {{tlu fh}}\n          <\/td>\n        <\/tr>\n      {{/if}}\n\n    <\/table>\n  ');
HistoryViewController.registerView("RouletteTemplate", '<table class="table table-bordered table-condensed" style="max-width:200px"><tr><td style="line-height:25px">{{tl \'rouletteballdrop\'}}<\/td><td><span class="rouletteDrop-{{ballColour}}" style="font-size:25px;color:{};">{{r}}<\/span><\/td><\/tr><\/table>{{#if b}}<table class="table table-condensed table-bordered" style="max-width:400px"><thead><tr><th>{{tl \'description\'}}<\/th><th class="currency">{{tl \'stake\'}}<\/th><th class="currency">{{tl \'payout\'}}<\/th><\/tr><\/thead><tbody>{{#each b}}<tr><td>{{d}}<\/td><td class="currency">{{formatMoney s}}<\/td><td class="currency">{{formatMoney p}}<\/td><\/tr>{{/each}}<\/tbody><\/table>{{/if}}');
HistoryViewController.registerView("SicboTemplate", '<div style="margin-bottom:10px">{{tl \'winningresult\'}}: <span class="dice">{{d1}}<\/span> <span class="dice">{{d2}}<\/span> <span class="dice">{{d3}}<\/span><\/div>{{#if bet}}<table class="table table-condensed table-bordered" style="max-width:400px"><thead><tr><th>{{tl \'description\'}}<\/th><th class="currency">{{tl \'stake\'}}<\/th><th class="currency">{{tl \'payout\'}}<\/th><\/tr><\/thead><tbody>{{#each bet}}<tr><td>{{tlsicbo trans}}<\/td><td class="currency">{{formatMoney s}}<\/td><td class="currency">{{formatMoney p}}<\/td><\/tr>{{/each}}<\/tbody><\/table>{{/if}}');
HistoryViewController.registerView("SlotDetailTemplate", '{{#if hasSubEvents}}<div class="slotgrid table-container"><table class="table table=table-condensed nuslotdetail" style="min-width:150px"><thead><tr><th class="tdType">{{tl \'type\'}}<\/th><th class="tdMutliplier">{{tl \'multiplier\'}}<\/th>{{#if headers.symbol}}<th class="tdFeature">{{tl \'symbol\'}}<\/th>{{/if}} {{#if headers.payout}}<th class="tdPayout currency">{{tl \'payout\'}}<\/th>{{/if}} {{#if headers.payline}}<th class="tdPayline center">{{tlpPaylineHeader bettype gamekeyname}}<\/th>{{/if}} {{#if headers.freegames}}<th class="tdFreeGames center">{{tl \'freegames\'}}<\/th>{{/if}}<\/tr><\/thead><tbody>{{clearSum 0}} {{#each subevents}} {{setSum wincash}}<tr id="tr{{../eventno}}_{{@index}}" onmouseover="RowHover_In(\'tr{{../eventno}}_{{@index}}\', \'{{type}}\', \'{{../gamekeyname}}\')" onmouseout="RowHover_Out(\'tr{{../eventno}}_{{@index}}\', \'{{type}}\')"><td class="tdType">{{tlpLineType type \'SlotHistory_WinType_\' ../gamekeyname}}<\/td>{{#if ../headers.multiplier}} {{#if multiplier}}<td class="tdMultiplier">{{multiplier}}<\/td>{{else}}<td>&nbsp;<\/td>{{/if}} {{/if}} {{#if symbol}}<td class="tdFeature"><div style="zoom:.6" class="linesymbol VideoSlots {{../gamekeyname}} id{{symbol}}"><\/div><\/td>{{else}}<td>&nbsp;<\/td>{{/if}} {{#if ../headers.payout}}<td class="tdPayout currency">{{formatMoney wincash}}<\/td>{{/if}} {{#if ../headers.payline}}<td class="tdPayline center">{{tlpLineNo lineno ../gamekeyname}}<\/td>{{/if}} {{#if ../headers.freegames}}<td class="tdFreeGames center">{{showFreeGames winfreegames}}<\/td>{{/if}}<\/tr>{{/each}}<\/tbody><tfoot><tr><td class="tdType"><\/td>{{#if ../headers.payout}}<td class="tdPayout"><\/td>{{/if}} {{#if ../headers.symbol}}<td class="tdFeature"><\/td>{{/if}} {{#if ../headers.payout}}<td class="tdPayout currency"><b>{{getSum}}<\/b><\/td>{{/if}} {{#if ../headers.payline}}<td class="tdPayline"><\/td>{{/if}} {{#if ../headers.freegames}}<td class="tdFreeGames"><\/td>{{/if}}<\/tr><\/tfoot><\/table><\/div>{{/if}} {{#if noSubEvents}}<div style="font-weight:700;margin:1em 0"><span>{{tl \'nogameevents\'}}<\/span><\/div>{{/if}}');
HistoryViewController.registerView("SlotEventTemplate", '{{#each events}}<div data-id="{{@index}}" class="ac"><div class="ac-header ac-trigger">{{#unless hideEventLabel}} <span style="display:inline-block;min-width:70px">{{tl \'eventnumber\'}} {{getEventNo @index}} <\/span>{{/unless}} <span style="display:inline-block;min-width:90px"><b>{{game_mode_translate gamemode ../this}} {{#compare spinno 0 operator=">"}} {{spinno}} {{/compare}} <\/b><\/span>{{#if sceneno}} <span>{{sceneno}} <\/span>{{/if}} {{#compare wincash 0 operator=">"}} <span style="display:inline-block;min-width:75px">{{formatMoney wincash}} <\/span>{{/compare}} {{#compare winfreegames 0 operator=">"}} <span class="success-color">+ {{winfreegames}} {{freegame_tl @index ../events}} <\/span>{{/compare}}<\/div><div class="ac-panel"><div class="ac-panel-inner"><table style="width:100%"><tr><td style="min-width:140px;padding-right:10px;vertical-align:top" class="leftcol">{{#if multiplier}}<div style="color:red;font-weight:700">{{tl \'multiplier\'}}: {{multiplier}}<\/div>{{/if}} {{#if numways}}<div style="color:red;font-weight:700">{{tl \'ways\'}}: {{numways}}<\/div>{{/if}} {{#if numlines}}<div style="color:red;font-weight:700">{{tl \'lines\'}}: {{numlines}}<\/div>{{/if}} {{#if numwinlines}}<div style="color:red;font-weight:700">{{tl \'winlinecount\'}}: {{numwinlines}}<\/div>{{/if}} {{#if cascadeno}}<div>{{tl \'cascade\'}}: {{cascadeno}}<\/div>{{/if}}<div>{{tl \'type\'}}: {{tlp type \'SlotHistory_PickType_\' ../../../gamekeyname}}<\/div><div>{{tlTitleCase \'payout\'}}: {{formatMoney wincash}}<\/div>{{#compare winfreegames 0 operator=">"}}<div>{{tlTitleCase \'freegames\'}}: {{winfreegames}}<\/div>{{/compare}}<\/td><td class="rightcol" style="width:100%">{{#ifdefined potlist}}<div class="potlist" style="display:none"><div id="potlist{{@index}}"><\/div><hr><\/div>{{/ifdefined}} {{#ifdefined reelslist}}<div id="reelslist{{@index}}"><\/div>{{/ifdefined}} {{#ifdefined reels}}<div id="reels{{@index}}"><\/div>{{/ifdefined}} {{#ifdefined tumbleobjects}}<div id="tumblecanvas{{@index}}"><\/div>{{/ifdefined}}<div id="details{{@index}}"><\/div>{{#ifdefined customsubevents}}<div id="customdetails{{@index}}"><\/div>{{/ifdefined}}<\/td><\/tr><\/table><\/div><\/div><\/div>{{/each}}');
HistoryViewController.registerView("SlotPickDetailTemplate", "{{#each subevents}}<div style=\"padding-bottom:5px\"><b>{{pick_type_translate picktype}}<\/b> {{#if wincash}}<div>{{tl 'cashwon'}}: {{formatMoney wincash}}<\/div>{{/if}} {{#if winmultipliertype}}<div>{{tl 'multipliertype'}}: {{formatMoney winmultipliertype}}<\/div>{{/if}} {{#if winmultiplier}}<div>{{tl 'multiplierwon'}}: {{winmultiplier}}<\/div>{{/if}} {{#if winfreegames}}<div>{{tl 'freegameswon'}}: {{winfreegames}}<\/div>{{/if}}<\/div>{{/each}}");
HistoryViewController.registerView("ThreeCardPokerTemplate", '<table class="table table-condensed table-bordered" style="max-width:400px">{{#if d.cards}}<tr><td style=""><b>{{tl \'dealer\'}}<\/b><br><\/td><td>{{#each d.cards}} <span class="card c{{name}}"><\/span> {{/each}}<\/td><td>{{tlu d.desc}}<\/td><\/tr>{{/if}} {{#if p.cards}}<tr><td><b>{{tl \'baccarat_player\'}}<\/b><br><\/td><td>{{#each p.cards}} <span class="card c{{name}}"><\/span> {{/each}}<\/td><td>{{tlu p.desc}}<\/td><\/tr>{{/if}}<\/table><table class="table table-condensed table-bordered" style="max-width:400px">{{#if ares}}<tr><td>{{tl \'poker_ante\'}}<\/td><td class="currency">{{formatMoney abet}}<\/td><\/tr><tr><td>{{tl \'bettitle\'}}<\/td><td class="currency">{{formatMoney rbet}}<\/td><\/tr><tr><td>{{tl \'threecardpoker_antepayout\'}}<\/td><td class="currency">{{formatMoney apay}}<\/td><\/tr><tr><td>{{tl \'threecardpoker_antebonuspayout\'}}<\/td><td class="currency">{{formatMoney abpay}}<\/td><\/tr><tr><td><b>{{tl \'threecardpoker_antebetstatus\'}}<\/b><\/td><td><b style="font-size:13px;text-transform:uppercase">{{tl asta}}<\/b><\/td><\/tr>{{/if}} {{#if pres}}<tr><td>{{tl \'threecardpoker_pairplusbet\'}}<\/td><td class="currency">{{formatMoney ppb}}<\/td><\/tr><tr><td>{{tl \'threecardpoker_pairpluspayout\'}}<\/td><td class="currency">{{formatMoney pppay}}<\/td><\/tr><tr><td><b>{{tl \'threecardpoker_pairplusbetstatus\'}}<\/b><\/td><td><b style="font-size:13px;text-transform:uppercase">{{tl ppsta}}<\/b><\/td><\/tr>{{/if}}<\/table>');
HistoryViewController.registerView("VideoPokerTemplate", '\n      <table class="table table-condensed table-bordered" style="width: 250px">\n          <tr>\n              <td style="width: 150px">\n                  {{tl \'coins\'}}\n              <\/td>\n              <td class="currency">\n                  {{c}}\n              <\/td>\n          <\/tr>\n          <tr>\n              <td>\n                  {{tl \'stake_increment\'}}\n              <\/td>\n              <td class="currency">\n                  {{formatMoney si}}\n              <\/td>\n          <\/tr>\n      <\/table>\n\n      <table class="table table-condensed table-bordered" id="VideoPokerHands" style="width: 400px">\n          {{#if initialHand}}\n              <tr>\n                  <td>\n                      {{tl \'videopoker_initialhand\'}}\n                  <\/td>\n                  <td>\n                      {{#each initialHand}}\n                          <span class="card c{{name}}">&nbsp;<\/span>\n                      {{/each}}\n                  <\/td>\n              <\/tr>\n          {{/if}}\n\n          {{#compare handCount 1 operator="=="}}\n\n              {{#if true}}\n                  {{#if finalHand}}\n                      <tr>\n                          <td>\n                              {{tl \'videopoker_finalhand\'}}\n                          <\/td>\n                          <td>\n                              {{#each finalHand}}\n                                  <span class="card c{{name}}"><\/span> {{/each}}\n                          <\/td>\n                      <\/tr>\n                  {{/if}}\n              {{/if}}\n\n          {{/compare}}\n      <\/table>\n\n      {{#if hnds}}\n          <table class="table table-condensed" style="width: 400px">\n              <thead>\n              <tr>\n                  <th>\n                      #\n                  <\/th>\n                  <th>\n                      {{tl \'videopoker_finalhand\'}}\n                  <\/th>\n                  <th class="currency">\n                      {{tl \'payout\'}}\n                  <\/th>\n                  <th class="currency"><\/th>\n              <\/tr>\n              <\/thead>\n              <tbody>\n\n              {{#each hnds}}\n                  <tr>\n                      <td>{{hno}}<\/td>\n                      <td>\n                          {{#each hand}}\n                              <span class="card c{{name}}" style="width: 20px; height: 24px"><\/span>\n                          {{/each}}\n                      <\/td>\n                      <td class="currency">\n                          {{formatMoney p}}\n                      <\/td>\n                      <td class="currency">\n                          {{tl d}}\n                      <\/td>\n                  <\/tr>\n              {{/each}}\n\n              <\/tbody>\n          <\/table>\n      {{/if}}\n  ');
HistoryViewController.registerView("WarTemplate", '<table class="table table-condensed table-bordered" style="max-width:400px"><tr><td align="left" colspan="4"><b>{{tl \'winningresult\'}}<\/b>:<\/td><\/tr><tr><td width="120px"><b>{{tl \'dealer\'}}<\/b><br><\/td><td><span class="card c{{d1}}"><\/span> {{#if d2}} <span class="card c{{d2}}"><\/span> {{/if}}<\/td><\/tr><tr><td width="120px"><b>{{tl \'baccarat_player\'}}<\/b><br><\/td><td><span class="card c{{p1}}"><\/span> {{#if p2}} <span class="card c{{p2}}"><\/span> {{/if}}<\/td><\/tr><\/table><table class="table table-condensed table-bordered" style="max-width:200px">{{#if gres}}<tr><td>{{tl \'war_gamebet\'}}<\/td><td class="currency">{{formatMoney abet}}<\/td><\/tr><tr><td>{{tl \'war_gamepayout\'}}<\/td><td class="currency">{{formatMoney gpay}}<\/td><\/tr><tr><td>{{tl \'war_gamebetstatus\'}}<\/td><td>{{tlu asta}}<\/td><\/tr>{{/if}} {{#if tres}}<tr><td>{{tl \'war_tiebet\'}}<\/td><td class="currency">{{formatMoney tb}}<\/td><\/tr><tr><td>{{tl \'war_tiebetpayout\'}}<\/td><td class="currency">{{formatMoney tbpayout}}<\/td><\/tr><tr><td>{{tl \'war_tiebetstatus\'}}<\/td><td>{{tlu tbsta}}<\/td><\/tr>{{/if}}<\/table>'),
    function (n) {
        function ft(n) {
            this.ammount = "number" == typeof (n = void 0 === n ? 0 : n) ? n : Number(n.toString().replace(/[$,]/g, ""))
        }

        function gt(t) {
            var i = n.innerWidth / n.document.documentElement.clientWidth;
            (t && t.touches && 1 < t.touches.length || 1 != i) && (t.preventDefault(), t.stopImmediatePropagation())
        }

        function tt() {
            var e = document.querySelectorAll(".reeldisplay"),
                f, o, u, s, h;
            if (e)
                for (f = 0; f < e.length; ++f) {
                    o = e[f];
                    o.style.zoom = "1";
                    var l = o.clientWidth,
                        a = ni(e[f]),
                        r = l;
                    (r = a ? a.clientWidth - 16 : r) && l && 1 < (r = l / r) && (o.style.zoom = r = 1 / r)
                }
            if (u = document.querySelectorAll(".tumbledisplay"), u) {
                for (s = 0; s < u.length; ++s) u[s].childNodes[0].style.width = "0px";
                for (h = 0; h < u.length; ++h) {
                    var t = u[h],
                        i = t.parentNode,
                        c = t.childNodes[0],
                        n = i.innerWidth || i.clientWidth;
                    n && (t = c.preferredWidth, i = c.preferredHeight, t *= n = 1 < (n = (n - 16) / t) ? 1 : n, 360 < (i *= n) && (i *= n = 360 / i, t *= n), c.style.width = t + "px", c.style.height = i + "px")
                }
            }
        }

        function ni(n) {
            for (;;) {
                if (!n) return null;
                if (n.id && -1 !== n.id.indexOf("ui-accordion-") || n.className && -1 !== n.className.indexOf("ac-panel")) return n;
                n = n.parentNode
            }
        }

        function y() {
            var t = $("#outerWrapper"),
                n = $("#content");
            t.css("width", innerWidth);
            t.css("height", innerHeight);
            n.css("width", innerWidth - 10);
            void 0 !== n[0] && n.css("height", innerHeight - n[0].offsetTop - 5)
        }

        function p(n) {
            var t, n = /-?\d+/.exec(n);
            try {
                t = parseInt(n[0])
            } catch (n) {
                t = 0
            }
            return 0 === t ? "******" : new Intl.DateTimeFormat(o, {
                year: "numeric",
                month: "long",
                day: "numeric",
                hour: "2-digit",
                minute: "numeric",
                second: "numeric",
                timeZoneName: "short",
                hour12: !1
            }).format(t)
        }

        function ht(n, t) {
            return -1 !== n.indexOf(t, n.length - t.length)
        }

        function r(n, t) {
            if (void 0 === n) return "";
            var i = t = t || ot,
                t = n;
            try {
                ((t = new Intl.NumberFormat(o, {
                    minimumFractionDigits: i,
                    maximumFractionDigits: i,
                    style: "currency",
                    currency: l
                }).format(n)).endsWith(".00") || t.endsWith(",00") && 5 <= t.length) && (t = new Intl.NumberFormat(o, {
                    style: "currency",
                    currency: l,
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(n))
            } catch (t) {
                return n
            }
            return isNaN(t.replace(/,/g, "").replace(/./g, "")) ? n : t
        }

        function it(n) {
            return n.replace(/\w\S*/g, function (n) {
                return n.charAt(0).toUpperCase() + n.substr(1).toLowerCase()
            })
        }

        function ct(t) {
            return !t || "" === t ? "" : (t = t.toLowerCase(), n.HbL) && (t = n.HbL[t], t) ? t : ""
        }

        function w(n, t, i) {
            var u, e, f, r;
            if (!n) return "";
            if ("string" == typeof n || n instanceof String) {
                if (0 === (n = n.trim()).length) return "";
                n = "{" === n.charAt(0) ? JSON.parse(n) : {
                    tlkey: n
                }
            }
            if (u = n.tlkey, e = n.plist, 0 < (u = ct(u)).length) {
                if (e)
                    for (f = 0; f < e.length; ++f) {
                        if (r = e[f], r.tlkey) r = ct(r.tlkey);
                        else if (r.text) r = r.text;
                        else {
                            if (!r.img) continue;
                            r = '<span class="inlinesymbol reelsymbol VideoSlots ' + t + " " + r.img + '"><\/span>'
                        }
                        u = h(u, f, r = "<b>" + r + "<\/b>")
                    }
                u = ti(u);
                i && (u = "<" + i + ">" + u + "<\/" + i + ">")
            }
            return u
        }

        function ti(n) {
            for (var i, t = 0;;) {
                if (i = n.indexOf("{", t), -1 === i) break;
                if (-1 === (t = n.indexOf("}", i + 1))) break;
                n = n.substring(0, i) + n.substring(t + 1);
                t = 0
            }
            return n
        }

        function h(n, t, i) {
            for (var u, f, r = 0;;) {
                if (u = n.indexOf("{", r), -1 === u) break;
                if (-1 === (r = n.indexOf("}", u + 1))) break;
                f = n.substring(u + 1, r).trim();
                0 !== f.length && parseInt(f) === t && (n = n.substring(0, u) + i + n.substring(r + 1), r = 0)
            }
            return n
        }

        function t(val, upper, defval) {
            var rawVal, thisHbL, key, trykey, keyText;
            if (!val || !val.length) return defval || "";
            if (rawVal = val, val = val.replace(/ /g, "").replace(/,/g, "").replace(/-/g, "").replace(/\./g, "").replace(/'/g, "").replace("/", ""), thisHbL = n.HbL, key = val.toLowerCase(), thisHbL) keyText = thisHbL[key];
            else try {
                trykey = "HbL." + key;
                keyText = eval(trykey)
            } catch (e) {}
            return keyText && keyText.length ? upper ? keyText.toUpperCase() : keyText : defval || rawVal
        }

        function rt(n) {
            switch (n) {
                case 0:
                    n = "january";
                    break;
                case 1:
                    n = "february";
                    break;
                case 2:
                    n = "march";
                    break;
                case 3:
                    n = "april";
                    break;
                case 4:
                    n = "may";
                    break;
                case 5:
                    n = "june";
                    break;
                case 6:
                    n = "july";
                    break;
                case 7:
                    n = "august";
                    break;
                case 8:
                    n = "september";
                    break;
                case 9:
                    n = "october";
                    break;
                case 10:
                    n = "november";
                    break;
                case 11:
                    n = "december"
            }
            return t(n)
        }

        function lt(n, i, r, s, h) {
            s = s || "1";
            $("#GameInstanceContent").hide();
            $("#btnShowGameInstanceList").show();
            $("#btnShowGamesPlayedContent").hide();
            var c = $("#bsu").val();
            u.show();
            vi();
            3 === r ? $("#lblJurisCodeLabel").text("ADM Code") : $("#lblJurisCodeLabel").text("Game Code");
            y();
            2 !== i ? i ? ($("#extGameInfo").hide(), $.ajax({
                type: "POST",
                url: d + "/GetExternalGameDetailsIframe",
                data: "{'gameInstanceId' : '" + n + "','locale':'" + o + "','showUsername' : '" + c + "'}",
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (n) {
                    var t;
                    n.d && n.d.length && (n = (t = JSON.parse(n.d)).ExtFrame, ut(t.GameDetails, !0, s, h), f.show(), n && n.length && (n = '<iframe id="frmHistoryExt" runat="server" width="100%" height="650px" frameborder="0" enableviewstate="false" src="' + n + '" />', $("#extGameInfo").html(n), $("#extGameInfo").show()));
                    u.hide()
                }
            })) : $.ajax({
                type: "POST",
                url: d + "/GetGameDetails",
                data: "{'gameInstanceId' : '" + n + "','mode' : '" + g + "','showUsername' : '" + c + "'}",
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (n) {
                    n.d && n.d.length ? (ut(n = JSON.parse(n.d), !1, s, h), $("#lblGameState").html(t(n.GameState, !1))) : (f.show(), u.hide(), e.addClass("warn-color"), e.html("Game locked pending issue"))
                }
            }) : $.ajax({
                type: "get",
                url: "http://test.localhost/history",
                data: {
                    gameInstanceId: n,
                    type: "gameInstance"
                },
                dataType: "json",
                success: function (n) {
                    n.d && n.d.length && (ut(n = JSON.parse(n.d), !1, s, h), $("#lblGameState").html(t(n.GameState, !1)))
                }
            })
        }

        function at(n) {
            if (null === n) return null;
            if (void 0 !== n) {
                var t = "";
                switch (n.toLowerCase()) {
                    case "fold":
                        t = "CasinoPoker_Called_False";
                        break;
                    case "tie":
                        t = "Tie";
                        break;
                    case "dealer_does_not_qualify":
                        t = "TableGame_DealerDoesNotQualify";
                        break;
                    default:
                        t = "CasinoPoker_" + n.replace("_", "")
                }
                return t
            }
        }

        function b(n) {
            var t = "";
            if (void 0 === n || "" === n) return t;
            switch (n.toUpperCase()) {
                case "JACKS_OR_BETTER":
                    t = "pokerhand_jacksorbetter";
                    break;
                case "HIGH_CARD":
                    t = "pokerhand_highcard";
                    break;
                case "ONE_PAIR":
                    t = "pokerhand_1pair_fulltext";
                    break;
                case "TWO_PAIR":
                    t = "pokerhand_2pair_fulltext";
                    break;
                case "THREE_OF_A_KIND":
                    t = "pokerhand_3ofakind_fulltext";
                    break;
                case "STRAIGHT":
                    t = "pokerhand_straight";
                    break;
                case "FLUSH":
                    t = "pokerhand_flush";
                    break;
                case "FULL_HOUSE":
                    t = "pokerhand_fullhouse";
                    break;
                case "FOUR_OF_A_KIND":
                    t = "pokerhand_4ofakind_fulltext";
                    break;
                case "FOUR_OF_A_KIND_ACES_EIGHTS":
                    t = "PokerHand_4OfAKind_Aor8";
                    break;
                case "FOUR_OF_A_KIND_SEVENS":
                    t = "PokerHand_4OfAKind_7";
                    break;
                case "FOUR_OF_A_KIND_OTHERS":
                    t = "PokerHand_4OfAKind_Others";
                    break;
                case "STRAIGHT_FLUSH":
                    t = "pokerhand_straightflush";
                    break;
                case "FIVE_OF_A_KIND":
                    t = "PokerHand_FiveOfAKind";
                    break;
                case "WILD_ROYAL":
                    t = "PokerHand_WildRoyal";
                    break;
                case "KINGS_OR_BETTER":
                    t = "PokerHand_KingsOrBetter";
                    break;
                case "TENS_OR_BETTER":
                    t = "PokerHand_TensOrBetter";
                    break;
                case "FOUR_DEUCES_WITH_ACE":
                    t = "PokerHand_4Deuces_With Ace";
                    break;
                case "FOUR_DEUCES":
                    t = "PokerHand_4Deuces";
                    break;
                case "FIVE_OF_A_KIND_ACES":
                    t = "PokerHand_5OfAKind_Aces";
                    break;
                case "FIVE_OF_A_KIND_345":
                    t = "PokerHand_5OfAKind_345";
                    break;
                case "FIVE_OF_A_KIND_6K":
                    t = "PokerHand_5OfAKind_6toK";
                    break;
                case "FOUR_OF_A_KIND_ACES":
                    t = "PokerHand_4OfAKind_A";
                    break;
                case "FOUR_OF_A_KIND_2TO4S":
                    t = "PokerHand_4OfAKind_234";
                    break;
                case "FOUR_OF_A_KIND_5TOKS":
                    t = "PokerHand_4OfAKind_5toK";
                    break;
                case "FOUR_ACES_WITH_234":
                    t = "PokerHand_4OfAKind_AcesWith234";
                    break;
                case "FOUR_234_WITH_A234":
                    t = "PokerHand_4OfAKind_234WithA";
                    break;
                case "ROYAL_FLUSH_JACKPOT":
                case "ROYAL_FLUSH":
                    t = "pokerhand_royalflush";
                    break;
                default:
                    t = "pokerhand_" + n.replace("_", "")
            }
            return t
        }

        function ii(n) {
            return !0 === n ? "CasinoPoker_Called_True" : "CasinoPoker_Called_False"
        }

        function c(n) {
            var t = "";
            if (null == n) return t;
            switch (n.toUpperCase()) {
                case "FOLD":
                    t = "CasinoPoker_Called_False";
                    break;
                case "WIN":
                    t = "TableGame_Win";
                    break;
                case "LOSE":
                    t = "TableGame_Lose";
                    break;
                case "TIE":
                    t = "Tie";
                    break;
                case "DEALER_DOES_NOT_QUALIFY":
                    t = "TableGame_DealerDoesNotQualify";
                    break;
                case "SURRENDER":
                    t = "War_Surrender"
            }
            return t
        }

        function ri(n, t) {
            t.asta && (t.asta = c(t.asta));
            t.tbsta && (t.tbsta = c(t.tbsta))
        }

        function vt(n, i) {
            void 0 !== i.d && (i.d.desc = b(i.d.desc));
            i.p.desc = b(i.p.desc);
            i.cl = ii(i.cl);
            var r = n.GameKeyName.toLowerCase(),
                u = "";
            switch (r) {
                case "caribbeanholdem":
                    i.finalstatus = at(i.finalstatus, r);
                    u = "Poker_Call";
                    break;
                case "caribbeanstud":
                    i.finalstatus = at(i.finalstatus, r);
                    u = "BetTitle";
                    break;
                case "tgthreecardpoker":
                case "tgthreecardpokerdeluxe":
                    !0 === i.ares && (i.asta = c(i.asta, r));
                    !0 === i.pres && (i.ppsta = c(i.ppsta))
            }
            i.raiseBetTitle = t(u)
        }

        function ui(n) {
            var t = {},
                r, i, h, e;
            for (t.tie = {
                    betType: 3,
                    betGroup: 0,
                    betOrderId: 8
                }, t.dragon = {
                    betType: 1,
                    betGroup: 0,
                    betOrderId: 1
                }, t.dragonbig = {
                    betType: 4,
                    betGroup: 2,
                    betOrderId: 2
                }, t.dragonsmall = {
                    betType: 5,
                    betGroup: 2,
                    betOrderId: 3
                }, t.dragonsuitclubs = {
                    betType: 6,
                    betGroup: 2,
                    betOrderId: 4
                }, t.dragonsuithearts = {
                    betType: 7,
                    betGroup: 2,
                    betOrderId: 5
                }, t.dragonsuitspades = {
                    betType: 8,
                    betGroup: 2,
                    betOrderId: 6
                }, t.dragonsuitdiamonds = {
                    betType: 9,
                    betGroup: 2,
                    betOrderId: 7
                }, t.tiger = {
                    betType: 2,
                    betGroup: 0,
                    betOrderId: 9
                }, t.tigerbig = {
                    betType: 10,
                    betGroup: 3,
                    betOrderId: 10
                }, t.tigersmall = {
                    betType: 11,
                    betGroup: 3,
                    betOrderId: 11
                }, t.tigersuitclubs = {
                    betType: 12,
                    betGroup: 3,
                    betOrderId: 12
                }, t.tigersuithearts = {
                    betType: 13,
                    betGroup: 3,
                    betOrderId: 13
                }, t.tigersuitspades = {
                    betType: 14,
                    betGroup: 3,
                    betOrderId: 14
                }, t.tigersuitdiamonds = {
                    betType: 15,
                    betGroup: 3,
                    betOrderId: 15
                }, e = 0; e < n.bet.length; e++) {
                var u = n.bet[e],
                    o = t[f = u.n],
                    s = u.bs,
                    f = function (n) {
                        var t = "";
                        switch (n.toLowerCase()) {
                            case "tie":
                                t = "Tie";
                                break;
                            case "dragonbig":
                            case "tigerbig":
                                t = "SicBo_History_Big";
                                break;
                            case "dragonsmall":
                            case "tigersmall":
                                t = "SicBo_History_Small";
                                break;
                            case "dragon":
                                t = "DragonTiger_Dragon";
                                break;
                            case "tiger":
                                t = "DragonTiger_Tiger";
                                break;
                            default:
                                t = n.replace("_", "")
                        }
                        return t
                    }(f);
                (h = s).toLowerCase();
                s = "TableGame_" + h.replace("_", "");
                u.n = f;
                u.bs0 = s;
                u.suitString = (i = void 0, i = (r = o).betType, r = "", 7 === i || 13 === i ? r = "Hearts" : 6 === i || 12 === i ? r = "Clubs" : 8 === i || 14 === i ? r = "Spades" : 9 !== i && 15 !== i || (r = "Diamonds"), r);
                f = function (n) {
                    var t = "";
                    switch (n.betGroup) {
                        case 1:
                            t = "Tie";
                            break;
                        case 2:
                            t = "DragonTiger_Dragon";
                            break;
                        case 3:
                            t = "DragonTiger_Tiger"
                    }
                    return t
                }(o);
                u.orderId = o.betOrderId;
                u.groupTypeName = f
            }
            n.bet.sort(function (n, t) {
                return n.orderId - t.orderId
            })
        }

        function fi(n, t) {
            t.fh = b(t.fh);
            t.fs = c(t.fs)
        }

        function ei(n) {
            var t, i, r;
            if (n.seats)
                for (t = 0; t < n.seats.length; t++)
                    for (i = n.seats[t], i.id++, r = 0; r < i.hands.length; r++) i.hands[r].Id++
        }

        function oi(n) {
            function r(n) {
                return "tie" === n.toLowerCase() ? "Tie" : "Baccarat_" + n
            }
            var t, i;
            for (n.wt = r(n.wt), t = 0; t < n.bl.length; t++) i = n.bl[t], i.n = r(i.n)
        }

        function si(n) {
            for (var c, r, s, e, u = 0; u < n.b.length; u++) {
                var f = n.b[u],
                    o = f.d.split("_"),
                    i = "";
                switch (f.d.toLowerCase()) {
                    case "reds":
                        i = t("COLOR_RED");
                        break;
                    case "blacks":
                        i = t("COLOR_BLACK");
                        break;
                    case "evens":
                        i = t("Even");
                        break;
                    case "odds":
                        i = t("Odd");
                        break;
                    case "second_12":
                        i = t("Table_Roulette_2nd12");
                        break;
                    case "third_12":
                        i = t("Table_Roulette_3rd12");
                        break;
                    case "last_18":
                        i = t("Table_Roulette_19_to_36");
                        break;
                    default:
                        c = o[0].toLowerCase();
                        o.shift();
                        r = o.join(", ");
                        switch (c) {
                            case "first":
                                "12" === r ? i = t("Table_Roulette_1st12") : "18" === r && (i = t("Table_Roulette_1_to_18"));
                                break;
                            case "column":
                                i = h(i = t("Roulette_XColumn"), 0, r);
                                break;
                            case "straight":
                                i = t("Roulette_StraightUp") + " [" + r + "]";
                                break;
                            case "split":
                                i = t("Roulette_Split") + " [" + r + "]";
                                break;
                            case "street":
                                i = t("Roulette_Bet_Street") + " [" + r + "]";
                                break;
                            case "corner":
                                i = t("Roulette_Corner") + " [" + r + "]";
                                break;
                            case "line":
                                i = t("Roulette_DoubleStreet") + " [" + r + "]";
                                break;
                            default:
                                i = f.d
                        }
                }
                f.d = i.toUpperCase()
            }
            s = n.r;
            e = "yellow";
            0 === s ? e = "lime" : [1, 3, 5, 7, 9, 12, 14, 16, 18, 19, 21, 23, 25, 27, 30, 32, 34, 36].includes(s) && (e = "red");
            n.ballColor = e
        }

        function hi(n) {
            for (var i = 0; i < n.bet.length; i++) {
                var r = n.bet[i],
                    u = r.b,
                    t = "";
                switch (r.t) {
                    case 1:
                        t = "Small";
                        break;
                    case 2:
                        t = "Big";
                        break;
                    case 3:
                        t = "Double";
                        break;
                    case 4:
                        t = "Triple";
                        break;
                    case 5:
                        t = "AnyTriple";
                        break;
                    case 6:
                        t = "Total";
                        break;
                    case 7:
                        t = "Two_Combination";
                        break;
                    case 8:
                        t = "OneNumber"
                }
                "" !== u && (t += "_" + u);
                r.trans = t.toUpperCase()
            }
        }

        function ci(n, t) {
            function f(n) {
                for (var i = n.split(","), r = [], t = 0; t < i.length; t++) r.push({
                    name: i[t]
                });
                return r
            }
            var i = t.hnds,
                u, r;
            for (t.initialHand = f(i[0].crd), t.finalHand = f(i[i.length - 1].crd), u = 1; u < i.length; u++) r = i[u], r.hand = f(r.crd), r.d = b(r.d);
            i.shift();
            t.handCount = i.length
        }

        function ut(n, o, h, c) {
            var y, w, b, k, d, nt, g;
            if (f.show(), 1 === a && f.find("table:first").hide(), u.hide(), l = n.CurrencyCode, !n.GameState) return $("#lblGameStatus").html("No data from GameDetails()"), void console.warn("No data returned from GetGameDetails()");
            if (ot = n.CurrencyExponent, o = o || !1, $("#lblGameCurrency").html(n.CurrencyCode), $("#lblFriendlyId").html(n.FriendlyId), $("#lblGameName").html(t("GAMENAME_" + n.GameKeyName, !1, n.GameKeyName)), e.html(t(n.GameState, !1)), e.removeClass("warn-color"), e.removeClass("succcess-color"), "GAMESTATE_COMPLETED" === n.GameState.toUpperCase() || "GAMESTATE_3" === n.GameState.toUpperCase() ? e.addClass("success-color") : e.addClass("warn-color"), $("#lblRealStake").html(r(n.RealStake, n.CurrencyExponent)), $("#lblRealPayout").html(r(n.RealPayout, n.CurrencyExponent)), n.BonusStake && 0 < n.BonusStake ? ($(".bonusinfo").show(), $("#lblBonusStake").html(r(n.BonusStake, n.CurrencyExponent)), $("#lblBonusPayout").html(r(n.BonusPayout, n.CurrencyExponent))) : $(".bonusinfo").hide(), n.BonusToReal && 0 < n.BonusToReal ? ($(".bonusconvert").show(), $("#lblBonusToReal").html(r(n.BonusToReal, n.CurrencyExponent))) : $(".bonusconvert").hide(), n.JPContribution && 0 < n.JPContribution ? ($(".jackpotcontribution").show(), $("#lblJackpotContribution").html(r(n.JPContribution, 4))) : $(".jackpotcontribution").hide(), n.BalanceAfter ? ($(".balanceafter").show(), $("#lblBalanceAfter").html(r(n.BalanceAfter, n.CurrencyExponent))) : $(".balanceafter").hide(), n.Username ? ($(".playerusername").show(), $("#lblPlayerUsername").html(n.Username)) : $(".playerusername").hide(), $("#lblDtStarted").html(p(n.DtStarted)), n.DtCompleted ? $("#lblDtCompleted").html(p(n.DtCompleted)) : $("#lblDtCompleted").html("-"), $("#lblCheat").hide(), $(".srijSMResult").hide(), $(".jurisCode").hide(), $("#lblSRIJSMResult").hide(), void 0 !== n.IsCheat && !0 === n.IsCheat && $("#lblCheat").show(), n.JurisCode && ($(".jurisCode").show(), $("#lblJurisCode").text(n.JurisCode)), "GAMESTATE_4" !== n.GameState.toUpperCase()) {
                b = null;
                n.TableGameDetail && n.TableGameDetail.ReportInfo && (b = JSON.parse(n.TableGameDetail.ReportInfo), n.TGDetail = b);
                k = "";
                switch (n.GameType) {
                    case 2:
                        oi(b);
                        k = "BaccaratTemplate";
                        y = HistoryViewController.getView(k);
                        w = Handlebars.compile(y);
                        $("#GameDetailsContent").html(w(b));
                        break;
                    case 4:
                        ei(b);
                        k = "BlackjackTemplate";
                        y = HistoryViewController.getView(k);
                        w = Handlebars.compile(y);
                        o ? $("#GameDetailsContent").html("") : $("#GameDetailsContent").html(w(b));
                        break;
                    case 5:
                        si(b);
                        k = "RouletteTemplate";
                        y = HistoryViewController.getView(k);
                        w = Handlebars.compile(y);
                        $("#GameDetailsContent").html(w(b));
                        break;
                    case 6:
                        ci(n, b);
                        k = "VideoPokerTemplate";
                        y = HistoryViewController.getView(k);
                        w = Handlebars.compile(y);
                        o ? $("#GameDetailsContent").html("") : $("#GameDetailsContent").html(w(b));
                        break;
                    case 7:
                        k = "GambleTemplate";
                        y = HistoryViewController.getView(k);
                        w = Handlebars.compile(y);
                        $("#GameDetailsContent").html(w(b));
                        break;
                    case 8:
                        switch (n.GameKeyName.toLowerCase()) {
                            case "tgniuniupoker":
                                y = HistoryViewController.getView("NiuNiuPokerTemplate");
                                w = Handlebars.compile(y);
                                $("#GameDetailsContent").html(w(n));
                                break;
                            case "tgraiseituppoker":
                                fi(n, b);
                                k = "RaiseItUpPokerTemplate";
                                y = HistoryViewController.getView(k);
                                w = Handlebars.compile(y);
                                $("#GameDetailsContent").html(w(b));
                                break;
                            case "tgthreecardpokerdeluxe":
                            case "tgthreecardpoker":
                                vt(n, b);
                                k = "ThreeCardPokerTemplate";
                                y = HistoryViewController.getView(k);
                                w = Handlebars.compile(y);
                                $("#GameDetailsContent").html(w(b));
                                break;
                            case "caribbeanholdem":
                            case "caribbeanstud":
                                vt(n, b);
                                k = "CasinoPokerTemplate";
                                y = HistoryViewController.getView(k);
                                w = Handlebars.compile(y);
                                $("#GameDetailsContent").html(w(b));
                                break;
                            default:
                                alert("cannot find poker type " + n.GameKeyName)
                        }
                        break;
                    case 10:
                        y = $("#ClassicSlotTemplate").html();
                        w = Handlebars.compile(y);
                        $("#GameDetailsContent").html(w(n));
                        break;
                    case 15:
                        hi(b);
                        k = "SicboTemplate";
                        y = HistoryViewController.getView(k);
                        w = Handlebars.compile(y);
                        $("#GameDetailsContent").html(w(b));
                        break;
                    case 16:
                        ri(n, b);
                        k = "WarTemplate";
                        y = HistoryViewController.getView(k);
                        w = Handlebars.compile(y);
                        $("#GameDetailsContent").html(w(b));
                        break;
                    case 17:
                        ui(b);
                        k = "DragonTigerTemplate";
                        y = HistoryViewController.getView(k);
                        w = Handlebars.compile(y);
                        $("#GameDetailsContent").html(w(b))
                }
                if (11 === n.GameType) {
                    if (o) return;
                    if (d = n.GameKeyName, ai(d, h), 0 < $("#maxpaylimit").val() && $("#maxpayvalue").html(r($("#maxpaylimit").val(), n.CurrencyExponent)), null == n.VideoSlotGameDetails) return;
                    (i = JSON.parse(n.VideoSlotGameDetails.ReportInfo)).gamekeyname = d;
                    nt = i.bettype;
                    i.ischeat && $("#lblCheat").show();
                    pi(d, i);
                    $("#lblCoinDenom").html(r(i.coindenomination));
                    $("#lblLinesWays").html(i.paylinecount);
                    $("#lblBetLevel").html(i.betlevel);
                    i.bettype ? ($("#lblLinesWaysDesc").html(t(i.bettype)), "Grouped" === i.bettype ? ($("#lblLinesWaysDesc").html(t("groupedpays")), $("#lblLinesWays").html("")) : "HorizontalPays" === i.bettype ? $("#lblLinesWays").html("") : "HorizontalVerticalPays" === i.bettype ? $("#lblLinesWaysDesc").parent().parent().remove() : "RollingPays" === i.bettype ? $("#lblLinesWays").html("") : "BigReels" === i.bettype ? ($("#lblLinesWaysDesc").html(t("bigreelstitle")), $("#lblLinesWays").html("")) : "None" === i.bettype && $(".slotBetDetails.lines").remove(), "Ways" === i.bettype ? $("#lblCoins").html(i.numcoins) : "Lines" === i.bettype ? i.numcoins ? $("#lblCoins").html(i.numcoins) : $("#lblCoins").html(i.paylinecount) : i.numcoins && $("#lblCoins").html(i.numcoins)) : i.numcoins ? ($("#lblLinesWaysDesc").html(t("ways")), $("#lblCoins").html(i.numcoins)) : ($("#lblCoins").html(""), $("#lblLinesWaysDesc").html(t("lines")));
                    i && i.srij_sm_result && c && ($(".srijSMResult").show(), $("#lblSRIJSMResult").text(i.srij_sm_result), $("#lblSRIJSMResult").show());
                    y = HistoryViewController.getView("SlotEventTemplate");
                    w = Handlebars.compile(y);
                    1 === a && -1 !== s && null !== i.events && s < i.events.length && ((g = i.events[s]).hideEventLabel = !0, i.events = [g]);
                    g = w(i);
                    $("#slotAccordion").html(g);
                    v && (v.destroy(), v = null);
                    v = new Accordion($("#slotAccordion")[0], {
                        duration: .1,
                        openOnInit: [0],
                        ariaEnabled: !1,
                        collapse: !1,
                        onOpen: function (n) {
                            yt(i, nt, d, n);
                            tt();
                            gsap.to($("#content")[0], .5, {
                                scrollTop: n.offsetTop
                            })
                        }
                    });
                    yt(i, nt, d, null, 0);
                    $(".slotBetDetails").show()
                }
                tt()
            }
        }

        function li(n) {
            return n = n.getAttribute("data-id"), parseInt(n)
        }

        function yt(n, i, r, u, f) {
            var e, s, i, l, v, c, o, y, a, p, h;
            if (void 0 === f && (f = li(u)), e = n.events[f], !e._init) {
                if (e._init = !0, e.eventno = f, e.gamekeyname = r, e.bettype = i, i = !1, e.tumbleobjects && (ir($("#tumblecanvas" + f), e), i = !0), !i && e.reelslist && $("#reelslist" + f).html(gi(n, e)), !i && e.reels && $("#reels" + f).html(pt(n, e, null, !0)), e.potlist && ($(".potlist").show(), s = HistoryViewController.getView("PotSymbolListTemplate"), s = Handlebars.compile(s), $("#potlist" + f).html(s(e))), e.hasSubEvents = !(!e.subevents || !e.subevents.length), e.noSubEvents = !e.hasSubEvents, e.headers = {
                        type: !0,
                        multiplier: !0,
                        symbol: !1,
                        payout: !1,
                        payline: !1,
                        freegames: !1
                    }, e.eventno = f, e.subevents)
                    for (l = 0; l < e.subevents.length; l++) o = e.subevents[l], wi(r, e, o), o.type && (!o.type.startsWith("jackpot") || 2 <= (c = o.type.split("|")).length && (c = yi(v = c[1]), o.type = "RandomMultiBrandProgressive" === v ? c + "/ Mega - Contact support" : c + " / " + v), "group" === o.type && (o.type = t("groupedpay")), "badge" === o.type && (o.type = t("badgepay"))), o.multiplier && (e.headers.multiplier = !0), o.winfreegames && (e.headers.freegames = !0), o.lineno && (e.headers.payline = !0), o.symbol && (e.headers.symbol = !0), o.wincash && (e.headers.payout = !0);
                if ("spin" !== e.type && "nudge" !== e.type || (s = HistoryViewController.getView("SlotDetailTemplate"), s = Handlebars.compile(s), $("#details" + f).html(s(e))), "pick" !== e.type && "symbolpick" !== e.type || (h = HistoryViewController.getView("SlotPickDetailTemplate"), h = Handlebars.compile(h), $("#details" + f).html(h(e))), e.customsubevents) {
                    for (y = e.customsubevents, a = 0; a < y.length; ++a) p = y[a], p && bi(r, e, p);
                    h = HistoryViewController.getView("CustomSlotDetailTemplate");
                    h = Handlebars.compile(h);
                    $("#customdetails" + f).html(h(e))
                }
            }
        }

        function ai(n, t) {
            st.append("<link>");
            st.children(":last").attr({
                rel: "stylesheet",
                type: "text/css",
                href: "./gamereporting/styles/videoslots/" + n + ".css?bust=" + t
            })
        }

        function vi() {
            (f = $("#GameDetailsContainer")).empty();
            var n = HistoryViewController.getView("GameDetailsContainer"),
                n = Handlebars.compile(n);
            f.html(n());
            nt = $("#extGameInfo");
            e = $("#lblGameStatus");
            $("#GameNo").html("");
            f.hide();
            $("#GameDetailsContent").html("");
            $(".slotBetDetails").hide();
            nt.hide();
            nt.html("");
            $("#extGameInfoCss").html("");
            $("#lblStakePayout").html("")
        }

        function yi(n) {
            var i;
            switch (n) {
                case "RandomMultiBrandProgressive":
                    i = t("JackpotMega");
                    break;
                case "RandomGrand":
                    i = t("JackpotGrand");
                    break;
                case "RandomMajor":
                    i = t("JackpotMajor");
                    break;
                case "RandomMini":
                    i = t("JackpotMini");
                    break;
                case "RandomMinor":
                    i = t("JackpotMinor");
                    break;
                case "CoinSize":
                    i = t("JackpotCoin");
                    break;
                default:
                    i = n
            }
            return i
        }

        function pi(n, t) {
            if (t) {
                var r, u, i = t.events;
                switch (i && i.length && (r = i[0]), n) {
                    case "SGReturnToTheFeature":
                        di(u = i ? i[i.length - 1] : u);
                        break;
                    case "SGTotemTowers":
                    case "SGTabernaDeLosMuertos":
                    case "SGTabernaDeLosMuertosUltra":
                        t.paylinecount = "25 - 101";
                        break;
                    case "SGKnockoutFootballRush":
                        t.numcoins = 10;
                        break;
                    case "SGHeySushi":
                        r && (r.multiplier = 1)
                }
            }
        }

        function wi(n, t, i) {
            i && (t.multiplier || (t.multiplier = 1), t && 1 !== t.multiplier && -1 !== wt.indexOf(n) && (i.multiplier = i.multiplier / t.multiplier))
        }

        function bi(n, t, i) {
            var r;
            i && ("FlyGameReport" === i.type && (r = i.rc + i.bc + i.pc + i.gc, 0 < (i.totalC = r) || i.suc || (i.type = "FlyGameReportSKIPRENDER")), "MarvelousFurlongsBaseEvent" === i.type && (i.dtCurString = i.dtCurDay + " " + rt(i.dtCurMonth - 1), i.dtNextDay && i.dtNextMonth ? i.dtNextString = i.dtNextDay + " " + rt(i.dtNextMonth - 1) : i.spinsToNextDay = 4, i.spinsToNextDay = w({
                tlkey: "XSpinsToNextDay",
                plist: [{
                    text: i.spinsToNextDay
                }]
            })))
        }

        function ki(n, t, i) {
            var u, f, o, w, e, c, a, v, y, r;
            if ("SG188Football" === t)
                for (u = 0; u < n.length; ++u)
                    if (r = n[u])
                        for (f = 0; f < r.length; ++f) "idStadium" === r[f] && (r[f] = "id188");
            if ("SGWildTrucks" === t)
                for (u = 0; u < n.length; ++u)
                    if ((r = n[u]) && "idWild" === r[0])
                        for (r[0] = {
                                symbolId: "idWild",
                                vSpan: 3
                            }, f = 1; f < r.length; ++f) r[f] = null;
            if ("SGWealthInn" === t)
                for (u = 0; u < n.length; ++u)
                    if ((r = n[u]) && "idWildExpand" === r[0])
                        for (r[0] = {
                                symbolId: "idWildExpand",
                                vSpan: 3
                            }, f = 1; f < r.length; ++f) r[f] = null;
            if ("SGFaCaiShenDeluxe" === t)
                for (u = 0; u < n.length; ++u)
                    if (r = n[u])
                        for (f = 0; f < 5; ++f)
                            if ("idGod" === r[f]) {
                                r[f] = {
                                    symbolId: "idGodExpand",
                                    vSpan: 3
                                };
                                r[f + 1] = null;
                                r[f + 2] = null;
                                break
                            } if ("SGNaughtySanta" === t) {
                for (u = 0; u < n.length; ++u)(r = n[u]) && ("idSantaExpand1" === r[0] && (r[0] = {
                    symbolId: "idSantaExpand1",
                    vSpan: 4
                }), "idSantaExpand2" === r[0] && (r[0] = {
                    symbolId: "idSantaExpand2",
                    vSpan: 4
                }));
                for (u = 0; u < 5; ++u)
                    for (f = 0; f < 3; ++f) e = n[u][f], e && (e.symbolId || 0 === e.indexOf("idGirl") && (o = 1, ht(e, "-2") ? o = 2 : ht(e, "-2") && (o = 3), w = e.substring(0, e.indexOf("-")), 1 !== o && (n[u][f] = {
                        symbolId: e,
                        vSpan: o,
                        hSpan: o,
                        typeList: ["HIGHLIGHT"],
                        highlghtSymbolId: w
                    })))
            }
            if ("SGNuwa" === t)
                for (u = 0; u < n.length; ++u)
                    if ((r = n[u]) && 3 <= r.length && "idNuwa" === r[0] && "idNuwa" === r[1] && "idNuwa" === r[2])
                        for (r[0] = {
                                symbolId: "idNuwaE",
                                vSpan: 3
                            }, f = 1; f < r.length; ++f) r[f] = null;
            if ("SGLoonyBlox" === t)
                for (u = 0; u < n.length; ++u)
                    if ((r = n[u]) && 3 <= r.length && "idWild" === r[0] && "idWild" === r[1] && "idWild" === r[2])
                        for (r[0] = {
                                symbolId: "idWildExpand",
                                vSpan: 3
                            }, f = 1; f < r.length; ++f) r[f] = null;
            if ("SGColossalGems" === t && null != i)
                for (c = 0; c < i.l.length; c++) {
                    var p = i.l[c],
                        s = p.x,
                        l = p.y,
                        h = parseInt(p.s);
                    if (s <= n.length && l <= n[s].length) {
                        for (a = n[s][l], v = 0; v < h; v++)
                            for (y = 0; y < h; y++) n[s + v][l + y] = {
                                typeList: ["HIGHLIGHT"],
                                highlghtSymbolId: a
                            };
                        n[s][l] = {
                            symbolId: a + "_" + h.toString(),
                            vSpan: h,
                            hSpan: h,
                            typeList: ["HIGHLIGHT"],
                            highlghtSymbolId: a
                        }
                    }
                }
            if ("SGMysticFortuneDeluxe" === t)
                for (u = 0; u < n.length; ++u)
                    if ((r = n[u]) && "idWild" === r[0])
                        for (r[0] = {
                                symbolId: "idWild",
                                vSpan: 4
                            }, f = 1; f < r.length; ++f) r[f] = null;
            if ("SGLuckyDurian" === t)
                for (u = 0; u < n.length; ++u)
                    if (r = n[u], r && "idWild" === r[0] && r[0] === r[1])
                        for (r[0] = {
                                symbolId: "idWildExpand",
                                vSpan: 3
                            }, f = 1; f < r.length; ++f) r[f] = null;
            "SGMightyMedusa" === t && i && "f" === i && (n[2][1] = null, n[3][1] = null, n[2][2] = null, n[3][2] = null, n[2][1] = {
                symbolId: "idWildMedusaBig",
                vSpan: 2,
                hSpan: 2
            })
        }

        function di(n) {
            var r, t, i;
            if (n && "JACKPOT" === n.gamemode) {
                for (r = n.reels, t = 0; t < 5; ++t)
                    for (i = 0; i < 3; ++i)
                        if ("idGoldCasette" !== r[t][i]) return;
                n.multiplier = 10
            }
        }

        function gi(n, t) {
            if (!t) return "";
            for (var r = t.reelslist, f = t.gamekeyname, u = "", i = 0; i < r.length; ++i) u += w(r[i].tlheader, f, "h3"), u += pt(n, t, r[i].reels, !1);
            return u
        }

        function nr(n, t, i, r, u, f, e) {
            return "SGColossalGems" === n ? '<div class="HIGHLIGHT hide ' + ("VideoSlots " + n + " " + t) + (" overlay" + i.eventno + "_" + r + "-" + u) + '" style="' + ("display: hidden; margin: 0; padding: 0; position: absolute; left: " + f + "px; top: " + e + "px;") + '"><\/div>' : ""
        }

        function pt(n, t, i, u) {
            var h, it, p, l, rt, ut, d, ft, et, at, ot, v, vt, b, st, o, yt, pt;
            if (!t) return "";
            var ht = !1,
                g = null,
                nt = null;
            if (!i) {
                if (!(i = t.reels)) return "";
                g = t.reels00;
                nt = t.reels10;
                ht = !0
            }
            var a = t.gamekeyname,
                ct = t.meta,
                wt = t.reels_meta;
            ki(i, a, ct);
            var f = tr(a, i),
                s = "",
                tt = 0,
                y = 0,
                c = 5;
            for (c += f.paddingX0, h = 0; h < i.length; ++h)
                if (it = i[h], it) {
                    for (p = f.reelY[h] + 5, l = 0; l < it.length; ++l) {
                        var e = it[l],
                            k = e,
                            lt = 1;
                        if (e && e.symbolId && (k = e.symbolId, lt = e.vSpan || 1), rt = 1, e && e.symbolId && (k = e.symbolId, rt = e.hSpan || 1), ut = "display: inline-block; padding: 0; position: absolute; left: " + c + "px; top: " + p + "px;", g && g[h][l] && (s += '<div class="' + ("VideoSlots " + a + " " + g[h][l]) + '" style="' + ut + '"><\/div>'), k && k.length) {
                            if (d = "", "SGColossalGems" === a && (1 < lt || 1 < rt) ? !1 : !0)
                                for (ft = 0; ft < lt; ++ft)
                                    for (et = 0; et < rt; ++et) d = d + " td" + t.eventno + "_" + (h + et) + "-" + (l + ft);
                            s += '<div class="' + ("VideoSlots " + a + " " + k) + (d = u ? d : "") + '" style="' + ut + '"><\/div>'
                        }
                        if (e && e.typeList)
                            for (at = e.typeList || [], ot = 0; ot < at.length; ot++) "HIGHLIGHT" === at[ot] && (s += nr(a, e.highlghtSymbolId || "", t, h, l, c, p));
                        nt && nt[h][l] && (s += '<div class="' + ("VideoSlots " + a + " " + nt[h][l]) + '" style="' + ut + '"><\/div>');
                        p += f.symbolHeight;
                        y < (p += f.rowGap) && (y = p)
                    }
                    tt = c += f.symbolWidth;
                    c += f.reelGap
                } if (ht)
                for (c = 5, c += f.paddingX0, v = 0; v < i.length; ++v)
                    if (vt = i[v], vt) {
                        for (b = f.reelY[v] + 5, st = 0; st < vt.length; ++st) o = wt, yt = b, (o = o && o.length && (o = o[v]) && o.length ? o[st] : o) && o.cash_linebet && (yt = b + 9 * v), pt = c + f.symbolWidth / 2, o && o.cash_linebet && (s += '<div class="cash_linebet" style="' + ("display: inline-block; padding: 0; position: absolute; left: " + pt + "px; top: " + yt + "px;") + '">' + r(Big(o.cash_linebet).mul(n.linebet)) + "<\/div>"), b += f.symbolHeight, y < (b += f.rowGap) && (y = b);
                        tt = c += f.symbolWidth;
                        c += f.reelGap
                    } return tt += 5 + f.paddingX0 + f.paddingX1, y += 5, ct = s, s = "", ht && (s += w("finalreels", a, "h3")), s += '<div class="reeldisplay" style="display: inline-block; position: relative; width:' + tt + "px; height:" + y + 'px">', s += ct, s + "<\/div>"
        }

        function tr(n, t) {
            var i = dt,
                r, u;
            switch (i.paddingX0 = 0, i.paddingX1 = 0, i.reelY[0] = 0, i.reelY[1] = 0, i.reelY[2] = 0, i.reelY[3] = 0, i.reelY[4] = 0, i.reelY[5] = 0, i.reelY[6] = 0, i.reelY[7] = 0, i.reelY[8] = 0, i.reelY[9] = 0, i.symbolWidth = 48, i.symbolHeight = 48, i.reelGap = 4, i.rowGap = 0, n) {
                case "SGMightyMedusa":
                    i.symbolHeight = 39;
                    i.reelGap = 0;
                    break;
                case "SGMysticFortuneDeluxe":
                    i.symbolWidth = 75;
                    break;
                case "SGMarvelousFurlongs":
                    i.symbolWidth = 56;
                    break;
                case "SGChristmasGiftRush":
                    i.symbolWidth = 79;
                    break;
                case "SGTotemTowers":
                    i.symbolWidth = 45;
                    break;
                case "SGJellyfishFlowUltra":
                case "SGJellyfishFlow":
                    i.symbolWidth = 79;
                    break;
                case "SGWealthInn":
                    i.reelGap = 50;
                    i.paddingX0 = 24;
                    i.paddingX1 = 24;
                    break;
                case "SGKnockoutFootballRush":
                    i.symbolWidth = 84;
                    break;
                case "SGLoonyBlox":
                    i.symbolWidth = 32;
                    i.reelGap = 0;
                    i.rowGap = 0;
                    break;
                case "SGFaCaiShenDeluxe":
                    i.symbolWidth = 64;
                    i.reelGap = 0;
                    i.rowGap = 0;
                    break;
                case "SGNaughtySanta":
                    i.symbolWidth = 62;
                    i.reelGap = 0;
                    i.rowGap = 0;
                    break;
                case "SGScopa":
                    i.symbolWidth = 33;
                    i.reelY[0] = 49;
                    i.reelY[4] = 49;
                    i.reelGap = 1;
                    i.rowGap = 1;
                    break;
                case "SGWaysOfFortune":
                    i.symbolWidth = 54;
                    i.reelY[0] = 48;
                    i.reelY[1] = 48;
                    break;
                case "SGEgyptianDreamsDeluxe":
                    i.symbolWidth = 76;
                    break;
                case "SGJump":
                    if (i.symbolWidth = 68, t)
                        for (r = 0; r < 3; ++r) u = t[r], 2 === u.length ? i.reelY[r] = 48 : 3 === u.length && (i.reelY[r] = 24)
            }
            return i
        }

        function ir(n, t) {
            var b = t.gamekeyname,
                c = [],
                a = document.createElement("div"),
                p, y;
            a.className = "tumbledisplay";
            n.append(a);
            var n = Raphael(a),
                v = n.paper || n,
                n = v.canvas;
            n.preferredWidth = 1440;
            n.preferredHeight = 720;
            v.setViewBox(0, 0, 1440, 720, !0);
            var k = "./gamereporting/images/game/" + b + "/",
                h = (h = t.gamemode) && h.toLowerCase(),
                n = t.gamemode;
            for (n = (n = (n = (n = n.split("FREEGAMECASCADE").join("Free")).split("FREEGAME").join("Free")).split("MAINCASCADE").join("Main")).split("MAIN").join("Main"), v.image(n = k + n + "Bkg.jpg", 0, 0, 1440, 720), p = t.tumbleobjects, y = 0; y < p.length; ++y) {
                var s, f = p[y],
                    i = void 0,
                    r = 80,
                    u = 80,
                    w = 1,
                    e = .5,
                    o = .5,
                    d = f.type;
                switch (b) {
                    case "SGTechnoTumble":
                        switch (d) {
                            case 1:
                                i = "SymbolWild.png";
                                r = u = 120;
                                break;
                            case 2:
                                i = "SymbolScatter.png";
                                r = u = 120;
                                break;
                            case 3:
                                i = "SymbolBall1.png";
                                break;
                            case 4:
                                i = "SymbolBall2.png";
                                break;
                            case 5:
                                i = "SymbolBall3.png";
                                break;
                            case 6:
                                i = "SymbolBall1Badge.png";
                                break;
                            case 7:
                                i = "SymbolBall2Badge.png";
                                break;
                            case 8:
                                i = "SymbolBall3Badge.png"
                        }
                        break;
                    case "SGCalaverasExplosivas":
                        switch (w = .45, d) {
                            case 1:
                                i = "SymbolWild.png";
                                r = 292;
                                u = 306;
                                e = .50220608645898834;
                                o = .4556359459979421;
                                break;
                            case 8:
                                i = "SymbolWild_Badge.png";
                                r = 292;
                                u = 306;
                                e = .50220608645898834;
                                o = .4556359459979421;
                                break;
                            case 2:
                                i = "SymbolScatter.png";
                                r = 300;
                                u = 306;
                                e = .50142077271598862;
                                o = .47016932506671;
                                break;
                            case 3:
                                i = "SymbolHi1.png";
                                r = 229;
                                u = 306;
                                e = .50021653735309313;
                                o = .46242348447884213;
                                break;
                            case 9:
                                i = "SymbolHi1_Badge.png";
                                r = 229;
                                u = 305;
                                e = .50021653735309313;
                                o = .46242348447884213;
                                break;
                            case 4:
                                i = "SymbolHi2.png";
                                r = 225;
                                u = 307;
                                e = .50380213030367826;
                                o = .42475796034691332;
                                break;
                            case 10:
                                i = "SymbolHi2_Badge.png";
                                r = 233;
                                u = 307;
                                e = .50380213030367826;
                                o = .41475796034691331;
                                break;
                            case 5:
                                i = "SymbolHi3.png";
                                r = 211;
                                u = 306;
                                e = .49677322175303107;
                                o = .46848610532550078;
                                break;
                            case 11:
                                i = "SymbolHi3_Badge.png";
                                r = 215;
                                u = 330;
                                e = .49677322175303107;
                                o = .43848610532550081;
                                break;
                            case 6:
                                i = "SymbolHi4.png";
                                r = 230;
                                u = 306;
                                e = .50189249703105931;
                                o = .51721043219363683;
                                break;
                            case 12:
                                i = "SymbolHi4_Badge.png";
                                r = 230;
                                u = 306;
                                e = .50189249703105931;
                                o = .51721043219363683;
                                break;
                            case 7:
                                i = "SymbolHi5.png";
                                r = 210;
                                u = 306;
                                e = .50349466154676326;
                                o = .46913832649450254;
                                break;
                            case 13:
                                i = "SymbolHi5_Badge.png";
                                r = 210;
                                u = 306;
                                e = .50349466154676326;
                                o = .46913832649450254
                        }
                        break;
                    case "SGOrbsOfAtlantis":
                        var g = f.ri,
                            nt = f.si,
                            l = void 0;
                        "main" === h || "maincascade" === h ? l = bt.reels[g][nt] : "freegame" !== h && "freegamecascade" !== h || (l = kt.reels[g][nt]);
                        f.x = l.x + 240;
                        f.y = l.y;
                        f.r = l.r;
                        1 === f.type ? i = "SymbolWild.png" : 2 === f.type ? i = "SymbolScatter.png" : 3 === f.type ? i = "SymbolWhite.png" : 4 === f.type ? i = "SymbolYellow.png" : 5 === f.type ? i = "SymbolRed.png" : 6 === f.type ? i = "SymbolGreen.png" : 7 === f.type && (i = "SymbolBlue.png");
                        u = r = 50;
                        o = e = .5
                }
                i && ((s = v.image(i = k + i, -(r * e), -(u * o), r, u)).translate(f.x, f.y), s.scale(w, w, 0, 0), s.rotate(180 * f.r / Math.PI, 0, 0), s._f0 = s.matrix.f, s._data = f, c.push(s))
            }
            a._ctrl = {
                onRowHoverIn: function (n) {
                    for (var i, t, r, u, f, s, e = n.windows, h = n.entityids, l = gsap.timeline(), o = c.length - 1; 0 <= o; --o) {
                        if (i = c[o], t = i.node.transform.baseVal[0].matrix, gsap.killTweensOf(t), r = i._data, u = void 0, h) u = -1 !== h.indexOf(r.id);
                        else if (e)
                            for (f = 0; f < e.length; ++f)
                                if (s = e[f], r.ri === s[0] && r.si === s[1]) {
                                    u = !0;
                                    break
                                } u ? l.to(t, .25, {
                            f: i._f0,
                            ease: Sine.easeOut
                        }, 0) : l.to(t, .25, {
                            f: t.f - 1e3,
                            ease: Sine.easeOut
                        }, 0)
                    }
                },
                onRowHoverOut: function () {
                    for (var t, i, r = gsap.timeline(), n = c.length - 1; 0 <= n; --n) t = c[n], i = t.node.transform.baseVal[0].matrix, gsap.killTweensOf(i), r.to(i, .25, {
                        f: t._f0,
                        ease: Sine.easeOut
                    }, 0)
                }
            }
        }

        function rr(t) {
            for (var e = (t = t || n.location.search.substring(1)).split("&"), i = {}, f = 0; f < e.length; f++) {
                var u = e[f].split("="),
                    r = decodeURIComponent(u[0]),
                    u = decodeURIComponent(u[1]);
                void 0 === i[r] ? i[r] = decodeURIComponent(u) : "string" == typeof i[r] ? i[r] = [i[r], decodeURIComponent(u)] : i[r].push(decodeURIComponent(u))
            }
            return i
        }

        function ur() {
            u.hide();
            $("#lblStakePayout").html("");
            $("#slotAccordion").html("");
            $(".slotBetDetails").hide();
            $("#extGameInfo").hide();
            $("#btnShowGamesPlayedContent").show();
            $("#btnShowGameInstanceList").hide();
            $("#GameInstanceContent").show();
            f.hide();
            $(".dateSelect").show();
            y()
        }

        function fr(n, t) {
            var f, r, a, e, v, h;
            try {
                var u = n.substring(2, n.indexOf("_")),
                    y = n.substring(n.indexOf("_") + 1, n.length),
                    s = i.events[u].subevents[y],
                    c = $("#EventReelCanvas"),
                    l = $("#reels" + u),
                    o = $("#tumblecanvas" + u + " > .tumbledisplay");
                if (0 < o.length) return void((o = o[0])._ctrl && o._ctrl.onRowHoverIn(s));
                for ($(".VideoSlots", "#reels" + u).addClass("disabled"), "RogerMove" === t || "RogerMoveNoOnly" === t ? $(".VideoSlots > .RogerRoll").remove() : "Jellies" === t && $(".VideoSlots > .CakeValleyJellies").remove(), c.css({
                        height: l.height() + 50,
                        width: l.width()
                    }), f = s.windows, r = 0; r < f.length; r++) {
                    a = ".td" + u + "_" + f[r][0] + "-" + f[r][1];
                    e = $(a);
                    e.removeClass("disabled");
                    e.addClass("over");
                    v = ".overlay" + u + "_" + f[r][0] + "-" + f[r][1];
                    h = $(v);
                    switch (h.removeClass("hide"), h.removeClass("disabled"), t) {
                        case "RogerMove":
                            e.append('<div class="RogerRoll"><span>' + (r + 1) + "<\/span><\/div>");
                            break;
                        case "RogerMoveNoOnly":
                            e.append('<div class="RogerRoll NoOnly"><span>' + (r + 1) + "<\/span><\/div>");
                            break;
                        case "Jellies":
                            e.append('<div style="opacity: 0.75;" class="CakeValleyJellies reelsymbol ui-corner-all VideoSlots SGCakeValley id' + s.symbol + '"><\/div>')
                    }
                }
                c.show()
            } catch (n) {}
        }

        function er(n, t) {
            var i = n.substring(2, n.indexOf("_")),
                n = $("#tumblecanvas" + i + " > .tumbledisplay");
            0 < n.length ? (n = n[0])._ctrl && n._ctrl.onRowHoverOut() : ($(".VideoSlots", "#reels" + i).removeClass("disabled"), $(".VideoSlots", "#reels" + i).removeClass("over"), $(".HIGHLIGHT").addClass("hide"), $("#EventReelCanvas").empty().hide(), "RogerMove" === t || "RogerMoveNoOnly" === t ? $(".VideoSlots > .RogerRoll").remove() : "Jellies" === t && $(".VideoSlots > .CakeValleyJellies").remove())
        }

        function or(n) {
            o = n.locale || "en";
            d = n.url;
            l = n.currencyCode;
            g = n.viewType;
            a = n.hideBaseInfo;
            s = n.eventIndex;
            $("html").removeClass("view-bo view-client").addClass("view-" + g)
        }
        var wt = ["SGNuwa"],
            bt = {
                reels: [
                    [{
                        x: 444,
                        y: 165.92,
                        r: 0
                    }, {
                        x: 444,
                        y: 215.95,
                        r: 0
                    }, {
                        x: 444,
                        y: 265.96,
                        r: 0
                    }, {
                        x: 444,
                        y: 315.97,
                        r: 0
                    }, {
                        x: 444,
                        y: 365.97,
                        r: 0
                    }, {
                        x: 444,
                        y: 416.01,
                        r: 4e-5
                    }, {
                        x: 442.48,
                        y: 466.01,
                        r: .249107
                    }, {
                        x: 405.37,
                        y: 499.55,
                        r: 1.139512
                    }, {
                        x: 362.03,
                        y: 524.53,
                        r: 1.091076
                    }, {
                        x: 311.94,
                        y: 522.88,
                        r: 2.195693
                    }, {
                        x: 271.53,
                        y: 493.12,
                        r: 2.245294
                    }, {
                        x: 234.28,
                        y: 459.5,
                        r: 2.335831
                    }, {
                        x: 201.55,
                        y: 421.49,
                        r: 2.501663
                    }, {
                        x: 174.16,
                        y: 379.45,
                        r: 2.631165
                    }, {
                        x: 153.89,
                        y: 333.55,
                        r: 2.824324
                    }, {
                        x: 141.28,
                        y: 285.05,
                        r: 2.98907
                    }, {
                        x: 137.03,
                        y: 235.1,
                        r: 3.102661
                    }, {
                        x: 141.49,
                        y: 185.28,
                        r: 3.314599
                    }, {
                        x: 152.87,
                        y: 136.44,
                        r: 3.433824
                    }, {
                        x: 177.88,
                        y: 93.02,
                        r: 4.489956
                    }, {
                        x: 227.24,
                        y: 101.85,
                        r: -1.059752
                    }, {
                        x: 273.44,
                        y: 121.38,
                        r: -1.181328
                    }, {
                        x: 320.57,
                        y: 138.23,
                        r: -1.277086
                    }, {
                        x: 366.82,
                        y: 157.28,
                        r: -.981316
                    }, {
                        x: 375.4,
                        y: 206.58,
                        r: .038141
                    }, {
                        x: 375,
                        y: 256.69,
                        r: 0
                    }, {
                        x: 375,
                        y: 306.7,
                        r: 0
                    }, {
                        x: 375.04,
                        y: 356.72,
                        r: -.00419
                    }, {
                        x: 375.12,
                        y: 406.72,
                        r: -.001735
                    }, {
                        x: 345.08,
                        y: 446.84,
                        r: 1.304242
                    }, {
                        x: 298.49,
                        y: 428.65,
                        r: 2.420748
                    }, {
                        x: 266.59,
                        y: 390.15,
                        r: 2.477494
                    }, {
                        x: 238.27,
                        y: 348.74,
                        r: 2.604452
                    }, {
                        x: 217.73,
                        y: 303.11,
                        r: 2.829664
                    }, {
                        x: 207.84,
                        y: 254.06,
                        r: 3.079701
                    }, {
                        x: 214.54,
                        y: 204.34,
                        r: 4.013375
                    }, {
                        x: 263.48,
                        y: 194.02,
                        r: -1.253508
                    }, {
                        x: 302.91,
                        y: 224.81,
                        r: -.098714
                    }, {
                        x: 303,
                        y: 274.93,
                        r: 0
                    }, {
                        x: 303,
                        y: 325,
                        r: 0
                    }],
                    [{
                        x: 515,
                        y: 165.92,
                        r: 0
                    }, {
                        x: 515,
                        y: 215.95,
                        r: 0
                    }, {
                        x: 515,
                        y: 265.96,
                        r: 0
                    }, {
                        x: 515,
                        y: 315.97,
                        r: 0
                    }, {
                        x: 515,
                        y: 365.97,
                        r: 0
                    }, {
                        x: 515,
                        y: 416.01,
                        r: -32e-6
                    }, {
                        x: 516.51,
                        y: 466.01,
                        r: -.249161
                    }, {
                        x: 553.64,
                        y: 499.55,
                        r: -1.139525
                    }, {
                        x: 596.97,
                        y: 524.53,
                        r: -1.091149
                    }, {
                        x: 647.06,
                        y: 522.88,
                        r: 4.087489
                    }, {
                        x: 687.48,
                        y: 493.12,
                        r: 4.037879
                    }, {
                        x: 724.72,
                        y: 459.5,
                        r: 3.947333
                    }, {
                        x: 757.45,
                        y: 421.49,
                        r: 3.781502
                    }, {
                        x: 784.84,
                        y: 379.44,
                        r: 3.652
                    }, {
                        x: 805.11,
                        y: 333.55,
                        r: 3.458838
                    }, {
                        x: 817.72,
                        y: 285.05,
                        r: 3.294093
                    }, {
                        x: 821.97,
                        y: 235.1,
                        r: 3.180507
                    }, {
                        x: 817.51,
                        y: 185.28,
                        r: 2.968568
                    }, {
                        x: 806.13,
                        y: 136.44,
                        r: 2.849348
                    }, {
                        x: 781.12,
                        y: 93.01,
                        r: 1.792986
                    }, {
                        x: 731.76,
                        y: 101.85,
                        r: 1.059764
                    }, {
                        x: 685.56,
                        y: 121.38,
                        r: 1.181313
                    }, {
                        x: 638.43,
                        y: 138.23,
                        r: 1.277097
                    }, {
                        x: 592.17,
                        y: 157.28,
                        r: .981205
                    }, {
                        x: 583.6,
                        y: 206.59,
                        r: -.038133
                    }, {
                        x: 584,
                        y: 256.7,
                        r: 0
                    }, {
                        x: 584,
                        y: 306.7,
                        r: 0
                    }, {
                        x: 583.96,
                        y: 356.72,
                        r: .004191
                    }, {
                        x: 583.88,
                        y: 406.73,
                        r: .001718
                    }, {
                        x: 613.93,
                        y: 446.84,
                        r: -1.304322
                    }, {
                        x: 660.51,
                        y: 428.65,
                        r: 3.862442
                    }, {
                        x: 692.41,
                        y: 390.15,
                        r: 3.805683
                    }, {
                        x: 720.73,
                        y: 348.74,
                        r: 3.678727
                    }, {
                        x: 741.27,
                        y: 303.1,
                        r: 3.453518
                    }, {
                        x: 751.16,
                        y: 254.06,
                        r: 3.203478
                    }, {
                        x: 744.46,
                        y: 204.34,
                        r: 2.265687
                    }, {
                        x: 695.52,
                        y: 194.02,
                        r: 1.253514
                    }, {
                        x: 656.09,
                        y: 224.81,
                        r: .098712
                    }, {
                        x: 656,
                        y: 274.93,
                        r: 0
                    }, {
                        x: 656,
                        y: 325,
                        r: 0
                    }]
                ]
            },
            kt = {
                reels: [
                    [{
                        x: 323.7,
                        y: 136.8,
                        r: 1.858316
                    }, {
                        x: 275.87,
                        y: 121.81,
                        r: 1.967844
                    }, {
                        x: 228.85,
                        y: 104.55,
                        r: 1.962682
                    }, {
                        x: 182.57,
                        y: 85.41,
                        r: 1.440113
                    }, {
                        x: 156.11,
                        y: 127.84,
                        r: .287093
                    }, {
                        x: 146.7,
                        y: 177.03,
                        r: .079567
                    }, {
                        x: 142.97,
                        y: 226.96,
                        r: -.012202
                    }, {
                        x: 145.37,
                        y: 276.94,
                        r: -.150246
                    }, {
                        x: 154.92,
                        y: 326.05,
                        r: -.234451
                    }, {
                        x: 171.88,
                        y: 373.11,
                        r: -.440203
                    }, {
                        x: 196.43,
                        y: 416.71,
                        r: -.598448
                    }, {
                        x: 227.19,
                        y: 456.21,
                        r: -.783485
                    }, {
                        x: 263.89,
                        y: 490.24,
                        r: -.906814
                    }, {
                        x: 304.86,
                        y: 519.01,
                        r: -1.054924
                    }, {
                        x: 351.57,
                        y: 536.93,
                        r: 4.546774
                    }, {
                        x: 392.7,
                        y: 508.34,
                        r: 4.08567
                    }, {
                        x: 436.94,
                        y: 484.92,
                        r: 3.814817
                    }, {
                        x: 442.23,
                        y: 435.13,
                        r: 3.146721
                    }, {
                        x: 442.49,
                        y: 385.03,
                        r: 3.146721
                    }, {
                        x: 442.74,
                        y: 335.01,
                        r: 3.146721
                    }, {
                        x: 443,
                        y: 285,
                        r: 3.146721
                    }],
                    [{
                        x: 443,
                        y: 158.04,
                        r: 0
                    }, {
                        x: 443.06,
                        y: 208.05,
                        r: -.001057
                    }, {
                        x: 396.1,
                        y: 225.28,
                        r: 1.724848
                    }, {
                        x: 346.58,
                        y: 218.18,
                        r: 1.795087
                    }, {
                        x: 297.52,
                        y: 208.53,
                        r: 1.826636
                    }, {
                        x: 250.28,
                        y: 191.98,
                        r: 2.057797
                    }, {
                        x: 211.98,
                        y: 224.14,
                        r: -.000274
                    }, {
                        x: 217.24,
                        y: 273.89,
                        r: -.140255
                    }, {
                        x: 231.02,
                        y: 321.97,
                        r: -.363326
                    }, {
                        x: 252.18,
                        y: 367.29,
                        r: -.496994
                    }, {
                        x: 281.5,
                        y: 407.79,
                        r: -.717257
                    }, {
                        x: 318.44,
                        y: 441.61,
                        r: -.915037
                    }, {
                        x: 366.48,
                        y: 427.52,
                        r: 3.964644
                    }, {
                        x: 354.32,
                        y: 378.97,
                        r: 2.599813
                    }, {
                        x: 328.49,
                        y: 336.04,
                        r: 2.599813
                    }, {
                        x: 330,
                        y: 286,
                        r: 4.639645
                    }],
                    [{
                        x: 514,
                        y: 158.04,
                        r: 0
                    }, {
                        x: 514.18,
                        y: 208.04,
                        r: -.036091
                    }, {
                        x: 561.14,
                        y: 225.31,
                        r: 4.556124
                    }, {
                        x: 610.68,
                        y: 218.34,
                        r: 4.482477
                    }, {
                        x: 659.78,
                        y: 208.7,
                        r: 4.457147
                    }, {
                        x: 706.99,
                        y: 192.2,
                        r: 4.18406
                    }, {
                        x: 745.77,
                        y: 223.78,
                        r: -.019298
                    }, {
                        x: 740.69,
                        y: 273.65,
                        r: .154349
                    }, {
                        x: 726.96,
                        y: 321.76,
                        r: .358753
                    }, {
                        x: 705.87,
                        y: 367.21,
                        r: .496469
                    }, {
                        x: 676.52,
                        y: 407.72,
                        r: .726108
                    }, {
                        x: 639.45,
                        y: 441.44,
                        r: .963835
                    }, {
                        x: 591.29,
                        y: 427.69,
                        r: 2.376288
                    }, {
                        x: 603.75,
                        y: 379.2,
                        r: 3.688381
                    }, {
                        x: 629.09,
                        y: 336.02,
                        r: 3.664816
                    }, {
                        x: 627,
                        y: 286,
                        r: 1.595084
                    }],
                    [{
                        x: 635.22,
                        y: 136.55,
                        r: 4.423444
                    }, {
                        x: 683.05,
                        y: 121.93,
                        r: 4.366965
                    }, {
                        x: 729.89,
                        y: 104.26,
                        r: 4.34537
                    }, {
                        x: 776.32,
                        y: 85.62,
                        r: -1.461552
                    }, {
                        x: 801.93,
                        y: 128.64,
                        r: -.211938
                    }, {
                        x: 811.32,
                        y: 177.86,
                        r: -.097416
                    }, {
                        x: 815.03,
                        y: 227.82,
                        r: -.015337
                    }, {
                        x: 812.66,
                        y: 277.86,
                        r: .146156
                    }, {
                        x: 803.17,
                        y: 327.05,
                        r: .260534
                    }, {
                        x: 786.1,
                        y: 374.13,
                        r: .426266
                    }, {
                        x: 761.07,
                        y: 417.42,
                        r: .58502
                    }, {
                        x: 730.16,
                        y: 456.79,
                        r: .769605
                    }, {
                        x: 693.43,
                        y: 490.78,
                        r: .895791
                    }, {
                        x: 652.54,
                        y: 519.56,
                        r: 1.082228
                    }, {
                        x: 605.44,
                        y: 536.67,
                        r: 1.801162
                    }, {
                        x: 564.65,
                        y: 507.75,
                        r: 2.142671
                    }, {
                        x: 520.08,
                        y: 484.93,
                        r: 2.460275
                    }, {
                        x: 514,
                        y: 435.2,
                        r: 3.141593
                    }, {
                        x: 514,
                        y: 385.19,
                        r: 3.141593
                    }, {
                        x: 514,
                        y: 335.07,
                        r: 3.141593
                    }, {
                        x: 514,
                        y: 285,
                        r: 3.141593
                    }]
                ]
            },
            dt = {
                reelY: []
            },
            sr = rr(),
            k, u, et;
        String.prototype.reverse = function () {
            return this.split("").reverse().join("")
        };
        ft.prototype.valueOf = function () {
            return this.ammount
        };
        ft.prototype.toString = function () {
            if (isNaN(this.ammount)) return NaN.toString();
            var n = Math.floor(Math.abs(this.ammount)).toString(),
                t = Math.round(Math.abs(this.ammount) % 1 * 100).toString();
            return [this.ammount < 0 ? "- " : "", "", 4 < n.length ? n.reverse().match(/\d{1,3}/g).join(",").reverse() : n, ".", t < 10 ? "0" + t : t].join("")
        };
        k = !1;
        try {
            et = Object.defineProperty({}, "passive", {
                get: function () {
                    k = !0
                }
            });
            n.addEventListener("test", null, et)
        } catch (hr) {}
        var o = "en",
            d, l = "",
            g, a, s, ot = 2,
            st = $("head"),
            nt, f, e, v, i;
        n.addEventListener("touchstart", gt, !!k && {
            passive: !1
        });
        $(function () {
            var f = 0,
                i, e;
            Handlebars.registerHelper("getEventNo", function (n) {
                return (n + 1).toString()
            });
            Handlebars.registerHelper("math", function (n, t, i) {
                return {
                    "+": (n = parseFloat(n)) + (i = parseFloat(i)),
                    "-": n - i,
                    "*": n * i,
                    "/": n / i,
                    "%": n % i
                } [t]
            });
            Handlebars.registerHelper("repeat", function (n, t) {
                for (var r = "", i = -1; ++i < n;) r += t.fn(i, {
                    data: {
                        index: i
                    }
                });
                return r
            });
            Handlebars.registerHelper("ifCond", function (n, t, i, r) {
                switch (t) {
                    case "===":
                        return n === i ? r.fn(this) : r.inverse(this);
                    case "!==":
                        return n !== i ? r.fn(this) : r.inverse(this);
                    case "<":
                        return n < i ? r.fn(this) : r.inverse(this);
                    case "<=":
                        return n <= i ? r.fn(this) : r.inverse(this);
                    case ">":
                        return i < n ? r.fn(this) : r.inverse(this);
                    case ">=":
                        return i <= n ? r.fn(this) : r.inverse(this);
                    case "&&":
                        return n && i ? r.fn(this) : r.inverse(this);
                    case "||":
                        return n || i ? r.fn(this) : r.inverse(this);
                    default:
                        return r.inverse(this)
                }
            });
            Handlebars.registerHelper("getObjectValue", function (n, t) {
                return n = JSON.parse(n), t.fn(n)
            });
            Handlebars.registerHelper("setIndex", function (n) {
                this.index = Number(n)
            });
            Handlebars.registerHelper("clearSum", function () {
                f = 0
            });
            Handlebars.registerHelper("setSum", function (n) {
                isNaN(n) || (f += n)
            });
            Handlebars.registerHelper("getSum", function () {
                return r(f)
            });
            Handlebars.registerHelper("upper", function (n) {
                return n.toUpperCase()
            });
            Handlebars.registerHelper("tl", function (n) {
                return t(n, !1)
            });
            Handlebars.registerHelper("tlTitleCase", function (n) {
                return t(n, !1)
            });
            Handlebars.registerHelper("tlsicbo", function (n) {
                if (!n) return "";
                var r = n.replace(/[^+\-\d.]/g, ""),
                    i = t("Sicbo_History_" + (n = "_" === (n = n.replace(/\d/g, "")).substring(n.length - 1) ? n.substring(0, n.length - 1) : n), !1);
                return i === "Sicbo_History_" + n && (i = n), i += "two_combination" === n.toLowerCase() ? r.substring(0, 1) + " + " + r.substring(1) : r, i
            });
            Handlebars.registerHelper("tlp", function (n, i) {
                if (!n) return "";
                var r = t((i = "SlotHistory_PickType_" === (i = i || "") && "nudge" === n ? "" : i) + n, !1);
                return it(r = r === i + n ? n.replace("_", " ") : r)
            });
            Handlebars.registerHelper("tlpPaylineHeader", function (n, i) {
                return "HorizontalPays" === n || "SGChristmasGiftRush" === i ? t("row", !1) : "SGReturnToTheFeature" === i ? it(t("way", !1)) : t("payline", !1)
            });
            Handlebars.registerHelper("tlpLineNo", function (n, t) {
                return "SGChristmasGiftRush" === t && (1 === n ? n = 2 : 2 === n && (n = 1)), void 0 === n ? n : "" + n
            });
            Handlebars.registerHelper("tlpLineType", function (n, i, r) {
                if ("payline" === n) switch (r) {
                    case "SGPresto":
                    case "SGFourDivineBeasts":
                        n = "Way";
                        break;
                    case "SGChristmasGiftRush":
                        n = "Row";
                        i = ""
                }
                return n ? (r = t((i = (i = "scopapayout" === n ? "" : i) || "") + n, !1), r === i + n ? n.replace("_", " ") : r) : ""
            });
            Handlebars.registerHelper("game_mode_translate", function (n, i) {
                if (!n) return "";
                var r, u = i.gamekeyname;
                switch (n.toUpperCase()) {
                    case "MAINCASCADE":
                        r = "SlotHistory_GameMode_Main";
                        break;
                    case "FREEGAME":
                    case "FREEGAMECASCADE":
                    case "SUPERFREEGAMECASCADE":
                    case "LOONYBLOX_CHAR_1_FREEGAME":
                    case "LOONYBLOX_CHAR_2_FREEGAME":
                    case "LOONYBLOX_CHAR_3_FREEGAME":
                    case "LOONYBLOX_CHAR_1_2_FREEGAME":
                    case "LOONYBLOX_CHAR_1_3_FREEGAME":
                    case "LOONYBLOX_CHAR_2_3_FREEGAME":
                    case "LOONYBLOX_CHAR_1_2_3_FREEGAME":
                    case "BONUS":
                    case "FREEGAME0":
                    case "BONUS1":
                    case "BONUS2":
                    case "BONUS3":
                    case "BONUS4":
                    case "BONUS5":
                    case "SUPERFREEGAME":
                    case "FG_HANS":
                    case "FG_HEIDI":
                    case "FG_HANSHEIDI":
                        r = "SlotHistory_GameMode_FreeGame";
                        "SGDiscoBeats" === u && (r = "SlotHistory_GameMode_Spin");
                        break;
                    case "BONUS1RESPIN":
                    case "BONUS2RESPIN":
                    case "BONUS3RESPIN":
                    case "BONUS4RESPIN":
                    case "BONUS5RESPIN":
                    case "RESPIN":
                        r = "SlotHistory_GameMode_Respin";
                        break;
                    case "NUDGE":
                        r = "Nudge";
                        break;
                    case "GANGSTERS_PICKBOOZE":
                        r = "SlotHistory_PickType_GANGSTERS_PICKBOOZE";
                        break;
                    case "GANGSTERS_PICKWEAPON":
                        r = "SlotHistory_PickType_GANGSTERS_PICKWEAPON";
                        break;
                    case "GANGSTERS_PICKCASINO":
                        r = "SlotHistory_PickType_GANGSTERS_PICKCASINO";
                        break;
                    case "GANGSTERS_PICKSAFE":
                        r = "SlotHistory_PickType_GANGSTERS_PICKSAFE";
                        break;
                    case "JACKPOT":
                        if ("SGReturnToTheFeature" === u) return "MoneyReSpin";
                        break;
                    default:
                        r = "SlotHistory_GameMode_" + n
                }
                return i = t(r, !1), it(i = i === r ? n : i)
            });
            Handlebars.registerHelper("pick_type_translate", function (n) {
                var i, r;
                if (!n) return "";
                switch (n.toUpperCase()) {
                    case "GHOST/FREEGAMES":
                        i = "SlotHistory_PickType_GhostFreeGames";
                        break;
                    case "JACKPOTMINIONLY":
                    case "JACKPOTMINORONLY":
                    case "JACKPOTMAJORONLY":
                    case "JACKPOTGRANDONLY":
                        i = n;
                        break;
                    default:
                        i = "SlotHistory_PickType_" + n
                }
                return r = t(i, !1), r === i ? n : r
            });
            Handlebars.registerHelper("freegame_tl", function (n, i) {
                i || console.warn("No event specified");
                var r = "FreeGames";
                if (i && n < i.length - 1) switch (i[n + 1].gamemode.toUpperCase()) {
                    case "BONUS1RESPIN":
                    case "BONUS2RESPIN":
                    case "BONUS3RESPIN":
                    case "BONUS4RESPIN":
                    case "BONUS5RESPIN":
                    case "RESPIN":
                        r = "ReSpinsTitle";
                        break;
                    default:
                        r = "FreeGames"
                }
                return n = t(r, !1), n === r && (console.warn("Free game translation not found:" + r), n = r), n
            });
            Handlebars.registerHelper("tlu", function (n) {
                return t(n, !0)
            });
            Handlebars.registerHelper("tlup", function (n, i) {
                return n = t(n, !0), i = i.hash, void 0 !== i.param0 && (n = h(n, 0, i.param0)), n = void 0 !== i.param1 ? h(n, 1, i.param1) : n
            });
            Handlebars.registerHelper("tla", function (n, t, i) {
                return w(n, t, i)
            });
            Handlebars.registerHelper("tlmonth", function (n) {
                return rt(n)
            });
            Handlebars.registerHelper("toPercentage", function (n, t) {
                return (100 * (n = isNaN(n) ? 0 : n)).toFixed(t = t || 2) + "%"
            });
            Handlebars.registerHelper("showFreeGames", function (n) {
                if (0 < n) return $(".tdFreeGames").show(), n
            });
            Handlebars.registerHelper("formatMoney", function (n) {
                return r(n)
            });
            Handlebars.registerHelper("formatDate", function (n) {
                return p(n)
            });
            Handlebars.registerHelper("compareAnd", function (n, t, i, r, u) {
                if (n && n.length && (n = n.toString().replace(/,/g, "")), i && i.length && (i = i.toString().replace(/,/g, "")), arguments.length < 5) throw new Error("Handlerbars Helper 'compare' needs 2 parameters");
                var f = u.hash.operator || "==",
                    e = {
                        "==": function (n, t) {
                            return n == t
                        },
                        "===": function (n, t) {
                            return n === t
                        },
                        "!=": function (n, t) {
                            return n != t
                        },
                        "!==": function (n, t) {
                            return n !== t
                        },
                        "<": function (n, t) {
                            return n < t
                        },
                        ">": function (n, t) {
                            return t < n
                        },
                        "<=": function (n, t) {
                            return n <= t
                        },
                        ">=": function (n, t) {
                            return t <= n
                        },
                        "typeof": function (n, t) {
                            return typeof n == typeof t
                        }
                    };
                if (!e[f]) throw new Error("Handlerbars Helper 'compare' doesn't know the operator " + f);
                return e[f](n, t) && e[f](i, r) ? u.fn(this) : u.inverse(this)
            });
            Handlebars.registerHelper("ifdefined", function (n, t) {
                if (n) return t.fn(this)
            });
            Handlebars.registerHelper("ifdefinedor", function (n, t, i) {
                if (n || t) return i.fn(this)
            });
            Handlebars.registerHelper("ifnotundefined", function (n, t) {
                if (void 0 !== n) return t.fn(this)
            });
            Handlebars.registerHelper({
                eq: function (n, t) {
                    return n === t
                },
                ne: function (n, t) {
                    return n !== t
                },
                lt: function (n, t) {
                    return n < t
                },
                gt: function (n, t) {
                    return t < n
                },
                lte: function (n, t) {
                    return n <= t
                },
                gte: function (n, t) {
                    return t <= n
                },
                and: function () {
                    return Array.prototype.slice.call(arguments, 0, arguments.length - 1).every(Boolean)
                },
                or: function () {
                    return Array.prototype.slice.call(arguments, 0, arguments.length - 1).some(Boolean)
                },
                not: function (n) {
                    return !n
                },
                sum: function () {
                    return Array.prototype.slice.call(arguments, 0, arguments.length - 1).reduce(function (n, t) {
                        return n + t
                    })
                },
                sub: function () {
                    return Array.prototype.slice.call(arguments, 1, arguments.length - 1).reduce(function (n, t) {
                        return n - t
                    }, arguments[0])
                },
                mul: function () {
                    return Array.prototype.slice.call(arguments, 0, arguments.length - 1).reduce(function (n, t) {
                        return n * t
                    }, 1)
                },
                div: function () {
                    return Array.prototype.slice.call(arguments, 1, arguments.length - 1).reduce(function (n, t) {
                        return n / t
                    }, arguments[0])
                },
                mod: function (n, t) {
                    return n % t
                }
            });
            Handlebars.registerHelper("compare", function (n, t, i) {
                if (n && n.length && (n = n.toString().replace(/,/g, "")), arguments.length < 3) throw new Error("Handlerbars Helper 'compare' needs 2 parameters");
                var r = i.hash.operator || "==",
                    u = {
                        "==": function (n, t) {
                            return n == t
                        },
                        "===": function (n, t) {
                            return n === t
                        },
                        "!=": function (n, t) {
                            return n != t
                        },
                        "!==": function (n, t) {
                            return n !== t
                        },
                        "<": function (n, t) {
                            return n < t
                        },
                        ">": function (n, t) {
                            return t < n
                        },
                        "<=": function (n, t) {
                            return n <= t
                        },
                        ">=": function (n, t) {
                            return t <= n
                        },
                        "typeof": function (n, t) {
                            return typeof n == typeof t
                        }
                    };
                if (!u[r]) throw new Error("Handlerbars Helper 'compare' doesn't know the operator " + r);
                return u[r](n, t) ? i.fn(this) : i.inverse(this)
            });
            $(n).on("resize", function () {
                y();
                tt()
            });
            y();
            u = jQuery('<div id="loadercont"><div id="loader"><\/div><\/div>').css({
                position: "absolute",
                top: "10%",
                left: "50%",
                "margin-left": "-32px"
            }).appendTo("body").hide();
            jQuery().ajaxStart(function () {
                u.show()
            }).ajaxStop(function () {
                u.hide()
            }).ajaxError(function (n, t, i) {
                throw i;
            });
            i = $("#gid").val();
            e = $("#ext").val();
            i && i.length && lt(i, e);
            $("#events a").bind("click", function () {
                $(".events").hide();
                $("#event_" + this.id).show()
            })
        });
        n.BackToList = ur;
        n.RowHover_In = fr;
        n.RowHover_Out = er;
        n.translate = t;
        n.replaceParam = h;
        n.formatDate = p;
        n.formatMoney = r;
        n.getGameDetails = lt;
        n.initLocals = or;
        n.hideBaseInfo = a;
        n.eventIndex = s
    }(window)
