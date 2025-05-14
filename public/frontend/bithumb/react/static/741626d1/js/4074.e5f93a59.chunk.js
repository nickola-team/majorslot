"use strict";
(self.webpackChunk_bithumb_pc = self.webpackChunk_bithumb_pc || []).push([
    [4074], {
        73202: function(e, t, a) {
            a.d(t, {
                s: function() {
                    return r
                }
            });
            var n = a(42877),
                i = a(10229);

            function r(e, t, a, r, s) {
                var o = [];
                if (!t) return o;
                var l = function(e) {
                    o.indexOf(e) < 0 && o.push(e)
                };
                if (a && r) {
                    var d = r.map(s),
                        c = a.map(s);
                    d.join("") !== c.join("") && l("children"), r.length !== a.length && l("children")
                }
                return n.O.filter((function(e) {
                    return "_" === e[0]
                })).map((function(e) {
                    return e.replace(/_/, "")
                })).forEach((function(a) {
                    if (a in e && a in t)
                        if ((0, i.Kn)(e[a]) && (0, i.Kn)(t[a])) {
                            var n = Object.keys(e[a]),
                                r = Object.keys(t[a]);
                            n.length !== r.length ? l(a) : (n.forEach((function(n) {
                                e[a][n] !== t[a][n] && l(a)
                            })), r.forEach((function(n) {
                                e[a][n] !== t[a][n] && l(a)
                            })))
                        } else e[a] !== t[a] && l(a)
                })), o
            }
        },
        56081: function(e, t, a) {
            a.d(t, {
                Q: function() {
                    return s
                }
            });
            var n = a(44041),
                i = a(10229),
                r = a(42877);

            function s(e, t) {
                void 0 === e && (e = {}), void 0 === t && (t = !0);
                var a = {
                        on: {}
                    },
                    s = {},
                    o = {};
                (0, i.l7)(a, n.ZP.defaults), (0, i.l7)(a, n.ZP.extendedDefaults), a._emitClasses = !0, a.init = !1;
                var l = {},
                    d = r.O.map((function(e) {
                        return e.replace(/_/, "")
                    })),
                    c = Object.assign({}, e);
                return Object.keys(c).forEach((function(n) {
                    "undefined" !== typeof e[n] && (d.indexOf(n) >= 0 ? (0, i.Kn)(e[n]) ? (a[n] = {}, o[n] = {}, (0, i.l7)(a[n], e[n]), (0, i.l7)(o[n], e[n])) : (a[n] = e[n], o[n] = e[n]) : 0 === n.search(/on[A-Z]/) && "function" === typeof e[n] ? t ? s["".concat(n[2].toLowerCase()).concat(n.substr(3))] = e[n] : a.on["".concat(n[2].toLowerCase()).concat(n.substr(3))] = e[n] : l[n] = e[n])
                })), ["navigation", "pagination", "scrollbar"].forEach((function(e) {
                    !0 === a[e] && (a[e] = {}), !1 === a[e] && delete a[e]
                })), {
                    params: a,
                    passedParams: o,
                    rest: l,
                    events: s
                }
            }
        },
        99732: function(e, t, a) {
            a.d(t, {
                x: function() {
                    return i
                }
            });
            var n = a(10229);

            function i(e, t) {
                var a = e.el,
                    i = e.nextEl,
                    r = e.prevEl,
                    s = e.paginationEl,
                    o = e.scrollbarEl,
                    l = e.swiper;
                (0, n.d7)(t) && i && r && (l.params.navigation.nextEl = i, l.originalParams.navigation.nextEl = i, l.params.navigation.prevEl = r, l.originalParams.navigation.prevEl = r), (0, n.fw)(t) && s && (l.params.pagination.el = s, l.originalParams.pagination.el = s), (0, n.XE)(t) && o && (l.params.scrollbar.el = o, l.originalParams.scrollbar.el = o), l.init(a)
            }
        },
        42877: function(e, t, a) {
            a.d(t, {
                O: function() {
                    return n
                }
            });
            var n = ["modules", "init", "_direction", "touchEventsTarget", "initialSlide", "_speed", "cssMode", "updateOnWindowResize", "resizeObserver", "nested", "focusableElements", "_enabled", "_width", "_height", "preventInteractionOnTransition", "userAgent", "url", "_edgeSwipeDetection", "_edgeSwipeThreshold", "_freeMode", "_autoHeight", "setWrapperSize", "virtualTranslate", "_effect", "breakpoints", "_spaceBetween", "_slidesPerView", "maxBackfaceHiddenSlides", "_grid", "_slidesPerGroup", "_slidesPerGroupSkip", "_slidesPerGroupAuto", "_centeredSlides", "_centeredSlidesBounds", "_slidesOffsetBefore", "_slidesOffsetAfter", "normalizeSlideIndex", "_centerInsufficientSlides", "_watchOverflow", "roundLengths", "touchRatio", "touchAngle", "simulateTouch", "_shortSwipes", "_longSwipes", "longSwipesRatio", "longSwipesMs", "_followFinger", "allowTouchMove", "_threshold", "touchMoveStopPropagation", "touchStartPreventDefault", "touchStartForcePreventDefault", "touchReleaseOnEdges", "uniqueNavElements", "_resistance", "_resistanceRatio", "_watchSlidesProgress", "_grabCursor", "preventClicks", "preventClicksPropagation", "_slideToClickedSlide", "_preloadImages", "updateOnImagesReady", "_loop", "_loopAdditionalSlides", "_loopedSlides", "_loopedSlidesLimit", "_loopFillGroupWithBlank", "loopPreventsSlide", "_rewind", "_allowSlidePrev", "_allowSlideNext", "_swipeHandler", "_noSwiping", "noSwipingClass", "noSwipingSelector", "passiveListeners", "containerModifierClass", "slideClass", "slideBlankClass", "slideActiveClass", "slideDuplicateActiveClass", "slideVisibleClass", "slideDuplicateClass", "slideNextClass", "slideDuplicateNextClass", "slidePrevClass", "slideDuplicatePrevClass", "wrapperClass", "runCallbacksOnInit", "observer", "observeParents", "observeSlideChildren", "a11y", "_autoplay", "_controller", "coverflowEffect", "cubeEffect", "fadeEffect", "flipEffect", "creativeEffect", "cardsEffect", "hashNavigation", "history", "keyboard", "lazy", "mousewheel", "_navigation", "_pagination", "parallax", "_scrollbar", "_thumbs", "virtual", "zoom"]
        },
        53985: function(e, t, a) {
            a.d(t, {
                v: function() {
                    return n
                }
            });
            var n = function(e) {
                !e || e.destroyed || !e.params.virtual || e.params.virtual && !e.params.virtual.enabled || (e.updateSlides(), e.updateProgress(), e.updateSlidesClasses(), e.lazy && e.params.lazy.enabled && e.lazy.load(), e.parallax && e.params.parallax && e.params.parallax.enabled && e.parallax.setTranslate())
            }
        },
        40525: function(e, t, a) {
            a.d(t, {
                Z: function() {
                    return i
                }
            });
            var n = a(10229);

            function i(e) {
                var t, a, i, r, s, o = e.swiper,
                    l = e.slides,
                    d = e.passedParams,
                    c = e.changedParams,
                    u = e.nextEl,
                    p = e.prevEl,
                    f = e.scrollbarEl,
                    v = e.paginationEl,
                    h = c.filter((function(e) {
                        return "children" !== e && "direction" !== e
                    })),
                    m = o.params,
                    g = o.pagination,
                    w = o.navigation,
                    b = o.scrollbar,
                    y = o.virtual,
                    T = o.thumbs;
                c.includes("thumbs") && d.thumbs && d.thumbs.swiper && m.thumbs && !m.thumbs.swiper && (t = !0), c.includes("controller") && d.controller && d.controller.control && m.controller && !m.controller.control && (a = !0), c.includes("pagination") && d.pagination && (d.pagination.el || v) && (m.pagination || !1 === m.pagination) && g && !g.el && (i = !0), c.includes("scrollbar") && d.scrollbar && (d.scrollbar.el || f) && (m.scrollbar || !1 === m.scrollbar) && b && !b.el && (r = !0), c.includes("navigation") && d.navigation && (d.navigation.prevEl || p) && (d.navigation.nextEl || u) && (m.navigation || !1 === m.navigation) && w && !w.prevEl && !w.nextEl && (s = !0);
                (h.forEach((function(e) {
                    if ((0, n.Kn)(m[e]) && (0, n.Kn)(d[e]))(0, n.l7)(m[e], d[e]);
                    else {
                        var t = d[e];
                        !0 !== t && !1 !== t || "navigation" !== e && "pagination" !== e && "scrollbar" !== e ? m[e] = d[e] : !1 === t && o[a = e] && (o[a].destroy(), "navigation" === a ? (m[a].prevEl = void 0, m[a].nextEl = void 0, o[a].prevEl = void 0, o[a].nextEl = void 0) : (m[a].el = void 0, o[a].el = void 0))
                    }
                    var a
                })), h.includes("controller") && !a && o.controller && o.controller.control && m.controller && m.controller.control && (o.controller.control = m.controller.control), c.includes("children") && l && y && m.virtual.enabled ? (y.slides = l, y.update(!0)) : c.includes("children") && o.lazy && o.params.lazy.enabled && o.lazy.load(), t) && (T.init() && T.update(!0));
                a && (o.controller.control = m.controller.control), i && (v && (m.pagination.el = v), g.init(), g.render(), g.update()), r && (f && (m.scrollbar.el = f), b.init(), b.updateSize(), b.setTranslate()), s && (u && (m.navigation.nextEl = u), p && (m.navigation.prevEl = p), w.init(), w.update()), c.includes("allowSlideNext") && (o.allowSlideNext = d.allowSlideNext), c.includes("allowSlidePrev") && (o.allowSlidePrev = d.allowSlidePrev), c.includes("direction") && o.changeDirection(d.direction, !1), o.update()
            }
        },
        10229: function(e, t, a) {
            function n(e) {
                return "object" === typeof e && null !== e && e.constructor && "Object" === Object.prototype.toString.call(e).slice(8, -1)
            }

            function i(e, t) {
                var a = ["__proto__", "constructor", "prototype"];
                Object.keys(t).filter((function(e) {
                    return a.indexOf(e) < 0
                })).forEach((function(a) {
                    "undefined" === typeof e[a] ? e[a] = t[a] : n(t[a]) && n(e[a]) && Object.keys(t[a]).length > 0 ? t[a].__swiper__ ? e[a] = t[a] : i(e[a], t[a]) : e[a] = t[a]
                }))
            }

            function r(e) {
                return void 0 === e && (e = {}), e.navigation && "undefined" === typeof e.navigation.nextEl && "undefined" === typeof e.navigation.prevEl
            }

            function s(e) {
                return void 0 === e && (e = {}), e.pagination && "undefined" === typeof e.pagination.el
            }

            function o(e) {
                return void 0 === e && (e = {}), e.scrollbar && "undefined" === typeof e.scrollbar.el
            }

            function l(e) {
                void 0 === e && (e = "");
                var t = e.split(" ").map((function(e) {
                        return e.trim()
                    })).filter((function(e) {
                        return !!e
                    })),
                    a = [];
                return t.forEach((function(e) {
                    a.indexOf(e) < 0 && a.push(e)
                })), a.join(" ")
            }
            a.d(t, {
                Kn: function() {
                    return n
                },
                XE: function() {
                    return o
                },
                d7: function() {
                    return r
                },
                fw: function() {
                    return s
                },
                kI: function() {
                    return l
                },
                l7: function() {
                    return i
                }
            })
        },
        44041: function(e, t, a) {
            a.d(t, {
                pt: function() {
                    return he
                },
                xW: function() {
                    return we
                },
                Rv: function() {
                    return me
                },
                Gk: function() {
                    return ce
                },
                W_: function() {
                    return pe
                },
                tl: function() {
                    return ve
                },
                ZP: function() {
                    return de
                }
            });
            var n = a(40242),
                i = a(79311),
                r = a(78083);

            function s(e) {
                return null !== e && "object" === typeof e && "constructor" in e && e.constructor === Object
            }

            function o() {
                var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : {},
                    t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {};
                Object.keys(t).forEach((function(a) {
                    "undefined" === typeof e[a] ? e[a] = t[a] : s(t[a]) && s(e[a]) && Object.keys(t[a]).length > 0 && o(e[a], t[a])
                }))
            }
            var l = {
                body: {},
                addEventListener: function() {},
                removeEventListener: function() {},
                activeElement: {
                    blur: function() {},
                    nodeName: ""
                },
                querySelector: function() {
                    return null
                },
                querySelectorAll: function() {
                    return []
                },
                getElementById: function() {
                    return null
                },
                createEvent: function() {
                    return {
                        initEvent: function() {}
                    }
                },
                createElement: function() {
                    return {
                        children: [],
                        childNodes: [],
                        style: {},
                        setAttribute: function() {},
                        getElementsByTagName: function() {
                            return []
                        }
                    }
                },
                createElementNS: function() {
                    return {}
                },
                importNode: function() {
                    return null
                },
                location: {
                    hash: "",
                    host: "",
                    hostname: "",
                    href: "",
                    origin: "",
                    pathname: "",
                    protocol: "",
                    search: ""
                }
            };

            function d() {
                var e = "undefined" !== typeof document ? document : {};
                return o(e, l), e
            }
            var c = {
                document: l,
                navigator: {
                    userAgent: ""
                },
                location: {
                    hash: "",
                    host: "",
                    hostname: "",
                    href: "",
                    origin: "",
                    pathname: "",
                    protocol: "",
                    search: ""
                },
                history: {
                    replaceState: function() {},
                    pushState: function() {},
                    go: function() {},
                    back: function() {}
                },
                CustomEvent: function() {
                    return this
                },
                addEventListener: function() {},
                removeEventListener: function() {},
                getComputedStyle: function() {
                    return {
                        getPropertyValue: function() {
                            return ""
                        }
                    }
                },
                Image: function() {},
                Date: function() {},
                screen: {},
                setTimeout: function() {},
                clearTimeout: function() {},
                matchMedia: function() {
                    return {}
                },
                requestAnimationFrame: function(e) {
                    return "undefined" === typeof setTimeout ? (e(), null) : setTimeout(e, 0)
                },
                cancelAnimationFrame: function(e) {
                    "undefined" !== typeof setTimeout && clearTimeout(e)
                }
            };

            function u() {
                var e = "undefined" !== typeof window ? window : {};
                return o(e, c), e
            }
            var p = a(35509),
                f = a(47093),
                v = a(22263),
                h = a(25755);
            var m = function(e) {
                (0, v.Z)(a, e);
                var t = (0, h.Z)(a);

                function a(e) {
                    var r;
                    return (0, i.Z)(this, a), "number" === typeof e ? r = t.call(this, e) : (r = t.call.apply(t, [this].concat((0, n.Z)(e || []))), function(e) {
                        var t = e.__proto__;
                        Object.defineProperty(e, "__proto__", {
                            get: function() {
                                return t
                            },
                            set: function(e) {
                                t.__proto__ = e
                            }
                        })
                    }((0, f.Z)(r))), (0, p.Z)(r)
                }
                return (0, r.Z)(a)
            }((0, a(3422).Z)(Array));

            function g() {
                var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : [],
                    t = [];
                return e.forEach((function(e) {
                    Array.isArray(e) ? t.push.apply(t, (0, n.Z)(g(e))) : t.push(e)
                })), t
            }

            function w(e, t) {
                return Array.prototype.filter.call(e, t)
            }

            function b(e, t) {
                var a = u(),
                    n = d(),
                    i = [];
                if (!t && e instanceof m) return e;
                if (!e) return new m(i);
                if ("string" === typeof e) {
                    var r = e.trim();
                    if (r.indexOf("<") >= 0 && r.indexOf(">") >= 0) {
                        var s = "div";
                        0 === r.indexOf("<li") && (s = "ul"), 0 === r.indexOf("<tr") && (s = "tbody"), 0 !== r.indexOf("<td") && 0 !== r.indexOf("<th") || (s = "tr"), 0 === r.indexOf("<tbody") && (s = "table"), 0 === r.indexOf("<option") && (s = "select");
                        var o = n.createElement(s);
                        o.innerHTML = r;
                        for (var l = 0; l < o.childNodes.length; l += 1) i.push(o.childNodes[l])
                    } else i = function(e, t) {
                        if ("string" !== typeof e) return [e];
                        for (var a = [], n = t.querySelectorAll(e), i = 0; i < n.length; i += 1) a.push(n[i]);
                        return a
                    }(e.trim(), t || n)
                } else if (e.nodeType || e === a || e === n) i.push(e);
                else if (Array.isArray(e)) {
                    if (e instanceof m) return e;
                    i = e
                }
                return new m(function(e) {
                    for (var t = [], a = 0; a < e.length; a += 1) - 1 === t.indexOf(e[a]) && t.push(e[a]);
                    return t
                }(i))
            }
            b.fn = m.prototype;
            var y = "resize scroll".split(" ");

            function T(e) {
                return function() {
                    for (var t = arguments.length, a = new Array(t), n = 0; n < t; n++) a[n] = arguments[n];
                    if ("undefined" === typeof a[0]) {
                        for (var i = 0; i < this.length; i += 1) y.indexOf(e) < 0 && (e in this[i] ? this[i][e]() : b(this[i]).trigger(e));
                        return this
                    }
                    return this.on.apply(this, [e].concat(a))
                }
            }
            T("click"), T("blur"), T("focus"), T("focusin"), T("focusout"), T("keyup"), T("keydown"), T("keypress"), T("submit"), T("change"), T("mousedown"), T("mousemove"), T("mouseup"), T("mouseenter"), T("mouseleave"), T("mouseout"), T("mouseover"), T("touchstart"), T("touchend"), T("touchmove"), T("resize"), T("scroll");
            var C = {
                addClass: function() {
                    for (var e = arguments.length, t = new Array(e), a = 0; a < e; a++) t[a] = arguments[a];
                    var i = g(t.map((function(e) {
                        return e.split(" ")
                    })));
                    return this.forEach((function(e) {
                        var t;
                        (t = e.classList).add.apply(t, (0, n.Z)(i))
                    })), this
                },
                removeClass: function() {
                    for (var e = arguments.length, t = new Array(e), a = 0; a < e; a++) t[a] = arguments[a];
                    var i = g(t.map((function(e) {
                        return e.split(" ")
                    })));
                    return this.forEach((function(e) {
                        var t;
                        (t = e.classList).remove.apply(t, (0, n.Z)(i))
                    })), this
                },
                hasClass: function() {
                    for (var e = arguments.length, t = new Array(e), a = 0; a < e; a++) t[a] = arguments[a];
                    var n = g(t.map((function(e) {
                        return e.split(" ")
                    })));
                    return w(this, (function(e) {
                        return n.filter((function(t) {
                            return e.classList.contains(t)
                        })).length > 0
                    })).length > 0
                },
                toggleClass: function() {
                    for (var e = arguments.length, t = new Array(e), a = 0; a < e; a++) t[a] = arguments[a];
                    var n = g(t.map((function(e) {
                        return e.split(" ")
                    })));
                    this.forEach((function(e) {
                        n.forEach((function(t) {
                            e.classList.toggle(t)
                        }))
                    }))
                },
                attr: function(e, t) {
                    if (1 === arguments.length && "string" === typeof e) return this[0] ? this[0].getAttribute(e) : void 0;
                    for (var a = 0; a < this.length; a += 1)
                        if (2 === arguments.length) this[a].setAttribute(e, t);
                        else
                            for (var n in e) this[a][n] = e[n], this[a].setAttribute(n, e[n]);
                    return this
                },
                removeAttr: function(e) {
                    for (var t = 0; t < this.length; t += 1) this[t].removeAttribute(e);
                    return this
                },
                transform: function(e) {
                    for (var t = 0; t < this.length; t += 1) this[t].style.transform = e;
                    return this
                },
                transition: function(e) {
                    for (var t = 0; t < this.length; t += 1) this[t].style.transitionDuration = "string" !== typeof e ? "".concat(e, "ms") : e;
                    return this
                },
                on: function() {
                    for (var e = arguments.length, t = new Array(e), a = 0; a < e; a++) t[a] = arguments[a];
                    var n = t[0],
                        i = t[1],
                        r = t[2],
                        s = t[3];

                    function o(e) {
                        var t = e.target;
                        if (t) {
                            var a = e.target.dom7EventData || [];
                            if (a.indexOf(e) < 0 && a.unshift(e), b(t).is(i)) r.apply(t, a);
                            else
                                for (var n = b(t).parents(), s = 0; s < n.length; s += 1) b(n[s]).is(i) && r.apply(n[s], a)
                        }
                    }

                    function l(e) {
                        var t = e && e.target && e.target.dom7EventData || [];
                        t.indexOf(e) < 0 && t.unshift(e), r.apply(this, t)
                    }
                    "function" === typeof t[1] && (n = t[0], r = t[1], s = t[2], i = void 0), s || (s = !1);
                    for (var d, c = n.split(" "), u = 0; u < this.length; u += 1) {
                        var p = this[u];
                        if (i)
                            for (d = 0; d < c.length; d += 1) {
                                var f = c[d];
                                p.dom7LiveListeners || (p.dom7LiveListeners = {}), p.dom7LiveListeners[f] || (p.dom7LiveListeners[f] = []), p.dom7LiveListeners[f].push({
                                    listener: r,
                                    proxyListener: o
                                }), p.addEventListener(f, o, s)
                            } else
                                for (d = 0; d < c.length; d += 1) {
                                    var v = c[d];
                                    p.dom7Listeners || (p.dom7Listeners = {}), p.dom7Listeners[v] || (p.dom7Listeners[v] = []), p.dom7Listeners[v].push({
                                        listener: r,
                                        proxyListener: l
                                    }), p.addEventListener(v, l, s)
                                }
                    }
                    return this
                },
                off: function() {
                    for (var e = arguments.length, t = new Array(e), a = 0; a < e; a++) t[a] = arguments[a];
                    var n = t[0],
                        i = t[1],
                        r = t[2],
                        s = t[3];
                    "function" === typeof t[1] && (n = t[0], r = t[1], s = t[2], i = void 0), s || (s = !1);
                    for (var o = n.split(" "), l = 0; l < o.length; l += 1)
                        for (var d = o[l], c = 0; c < this.length; c += 1) {
                            var u = this[c],
                                p = void 0;
                            if (!i && u.dom7Listeners ? p = u.dom7Listeners[d] : i && u.dom7LiveListeners && (p = u.dom7LiveListeners[d]), p && p.length)
                                for (var f = p.length - 1; f >= 0; f -= 1) {
                                    var v = p[f];
                                    r && v.listener === r || r && v.listener && v.listener.dom7proxy && v.listener.dom7proxy === r ? (u.removeEventListener(d, v.proxyListener, s), p.splice(f, 1)) : r || (u.removeEventListener(d, v.proxyListener, s), p.splice(f, 1))
                                }
                        }
                    return this
                },
                trigger: function() {
                    for (var e = u(), t = arguments.length, a = new Array(t), n = 0; n < t; n++) a[n] = arguments[n];
                    for (var i = a[0].split(" "), r = a[1], s = 0; s < i.length; s += 1)
                        for (var o = i[s], l = 0; l < this.length; l += 1) {
                            var d = this[l];
                            if (e.CustomEvent) {
                                var c = new e.CustomEvent(o, {
                                    detail: r,
                                    bubbles: !0,
                                    cancelable: !0
                                });
                                d.dom7EventData = a.filter((function(e, t) {
                                    return t > 0
                                })), d.dispatchEvent(c), d.dom7EventData = [], delete d.dom7EventData
                            }
                        }
                    return this
                },
                transitionEnd: function(e) {
                    var t = this;
                    return e && t.on("transitionend", (function a(n) {
                        n.target === this && (e.call(this, n), t.off("transitionend", a))
                    })), this
                },
                outerWidth: function(e) {
                    if (this.length > 0) {
                        if (e) {
                            var t = this.styles();
                            return this[0].offsetWidth + parseFloat(t.getPropertyValue("margin-right")) + parseFloat(t.getPropertyValue("margin-left"))
                        }
                        return this[0].offsetWidth
                    }
                    return null
                },
                outerHeight: function(e) {
                    if (this.length > 0) {
                        if (e) {
                            var t = this.styles();
                            return this[0].offsetHeight + parseFloat(t.getPropertyValue("margin-top")) + parseFloat(t.getPropertyValue("margin-bottom"))
                        }
                        return this[0].offsetHeight
                    }
                    return null
                },
                styles: function() {
                    var e = u();
                    return this[0] ? e.getComputedStyle(this[0], null) : {}
                },
                offset: function() {
                    if (this.length > 0) {
                        var e = u(),
                            t = d(),
                            a = this[0],
                            n = a.getBoundingClientRect(),
                            i = t.body,
                            r = a.clientTop || i.clientTop || 0,
                            s = a.clientLeft || i.clientLeft || 0,
                            o = a === e ? e.scrollY : a.scrollTop,
                            l = a === e ? e.scrollX : a.scrollLeft;
                        return {
                            top: n.top + o - r,
                            left: n.left + l - s
                        }
                    }
                    return null
                },
                css: function(e, t) {
                    var a, n = u();
                    if (1 === arguments.length) {
                        if ("string" !== typeof e) {
                            for (a = 0; a < this.length; a += 1)
                                for (var i in e) this[a].style[i] = e[i];
                            return this
                        }
                        if (this[0]) return n.getComputedStyle(this[0], null).getPropertyValue(e)
                    }
                    if (2 === arguments.length && "string" === typeof e) {
                        for (a = 0; a < this.length; a += 1) this[a].style[e] = t;
                        return this
                    }
                    return this
                },
                each: function(e) {
                    return e ? (this.forEach((function(t, a) {
                        e.apply(t, [t, a])
                    })), this) : this
                },
                html: function(e) {
                    if ("undefined" === typeof e) return this[0] ? this[0].innerHTML : null;
                    for (var t = 0; t < this.length; t += 1) this[t].innerHTML = e;
                    return this
                },
                text: function(e) {
                    if ("undefined" === typeof e) return this[0] ? this[0].textContent.trim() : null;
                    for (var t = 0; t < this.length; t += 1) this[t].textContent = e;
                    return this
                },
                is: function(e) {
                    var t, a, n = u(),
                        i = d(),
                        r = this[0];
                    if (!r || "undefined" === typeof e) return !1;
                    if ("string" === typeof e) {
                        if (r.matches) return r.matches(e);
                        if (r.webkitMatchesSelector) return r.webkitMatchesSelector(e);
                        if (r.msMatchesSelector) return r.msMatchesSelector(e);
                        for (t = b(e), a = 0; a < t.length; a += 1)
                            if (t[a] === r) return !0;
                        return !1
                    }
                    if (e === i) return r === i;
                    if (e === n) return r === n;
                    if (e.nodeType || e instanceof m) {
                        for (t = e.nodeType ? [e] : e, a = 0; a < t.length; a += 1)
                            if (t[a] === r) return !0;
                        return !1
                    }
                    return !1
                },
                index: function() {
                    var e, t = this[0];
                    if (t) {
                        for (e = 0; null !== (t = t.previousSibling);) 1 === t.nodeType && (e += 1);
                        return e
                    }
                },
                eq: function(e) {
                    if ("undefined" === typeof e) return this;
                    var t = this.length;
                    if (e > t - 1) return b([]);
                    if (e < 0) {
                        var a = t + e;
                        return b(a < 0 ? [] : [this[a]])
                    }
                    return b([this[e]])
                },
                append: function() {
                    for (var e, t = d(), a = 0; a < arguments.length; a += 1) {
                        e = a < 0 || arguments.length <= a ? void 0 : arguments[a];
                        for (var n = 0; n < this.length; n += 1)
                            if ("string" === typeof e) {
                                var i = t.createElement("div");
                                for (i.innerHTML = e; i.firstChild;) this[n].appendChild(i.firstChild)
                            } else if (e instanceof m)
                            for (var r = 0; r < e.length; r += 1) this[n].appendChild(e[r]);
                        else this[n].appendChild(e)
                    }
                    return this
                },
                prepend: function(e) {
                    var t, a, n = d();
                    for (t = 0; t < this.length; t += 1)
                        if ("string" === typeof e) {
                            var i = n.createElement("div");
                            for (i.innerHTML = e, a = i.childNodes.length - 1; a >= 0; a -= 1) this[t].insertBefore(i.childNodes[a], this[t].childNodes[0])
                        } else if (e instanceof m)
                        for (a = 0; a < e.length; a += 1) this[t].insertBefore(e[a], this[t].childNodes[0]);
                    else this[t].insertBefore(e, this[t].childNodes[0]);
                    return this
                },
                next: function(e) {
                    return this.length > 0 ? e ? this[0].nextElementSibling && b(this[0].nextElementSibling).is(e) ? b([this[0].nextElementSibling]) : b([]) : this[0].nextElementSibling ? b([this[0].nextElementSibling]) : b([]) : b([])
                },
                nextAll: function(e) {
                    var t = [],
                        a = this[0];
                    if (!a) return b([]);
                    for (; a.nextElementSibling;) {
                        var n = a.nextElementSibling;
                        e ? b(n).is(e) && t.push(n) : t.push(n), a = n
                    }
                    return b(t)
                },
                prev: function(e) {
                    if (this.length > 0) {
                        var t = this[0];
                        return e ? t.previousElementSibling && b(t.previousElementSibling).is(e) ? b([t.previousElementSibling]) : b([]) : t.previousElementSibling ? b([t.previousElementSibling]) : b([])
                    }
                    return b([])
                },
                prevAll: function(e) {
                    var t = [],
                        a = this[0];
                    if (!a) return b([]);
                    for (; a.previousElementSibling;) {
                        var n = a.previousElementSibling;
                        e ? b(n).is(e) && t.push(n) : t.push(n), a = n
                    }
                    return b(t)
                },
                parent: function(e) {
                    for (var t = [], a = 0; a < this.length; a += 1) null !== this[a].parentNode && (e ? b(this[a].parentNode).is(e) && t.push(this[a].parentNode) : t.push(this[a].parentNode));
                    return b(t)
                },
                parents: function(e) {
                    for (var t = [], a = 0; a < this.length; a += 1)
                        for (var n = this[a].parentNode; n;) e ? b(n).is(e) && t.push(n) : t.push(n), n = n.parentNode;
                    return b(t)
                },
                closest: function(e) {
                    var t = this;
                    return "undefined" === typeof e ? b([]) : (t.is(e) || (t = t.parents(e).eq(0)), t)
                },
                find: function(e) {
                    for (var t = [], a = 0; a < this.length; a += 1)
                        for (var n = this[a].querySelectorAll(e), i = 0; i < n.length; i += 1) t.push(n[i]);
                    return b(t)
                },
                children: function(e) {
                    for (var t = [], a = 0; a < this.length; a += 1)
                        for (var n = this[a].children, i = 0; i < n.length; i += 1) e && !b(n[i]).is(e) || t.push(n[i]);
                    return b(t)
                },
                filter: function(e) {
                    return b(w(this, e))
                },
                remove: function() {
                    for (var e = 0; e < this.length; e += 1) this[e].parentNode && this[e].parentNode.removeChild(this[e]);
                    return this
                }
            };
            Object.keys(C).forEach((function(e) {
                Object.defineProperty(b.fn, e, {
                    value: C[e],
                    writable: !0
                })
            }));
            var S, E, x, M = b,
                k = a(29721);

            function P(e, t) {
                return void 0 === t && (t = 0), setTimeout(e, t)
            }

            function O() {
                return Date.now()
            }

            function L(e, t) {
                void 0 === t && (t = "x");
                var a, n, i, r = u(),
                    s = function(e) {
                        var t, a = u();
                        return a.getComputedStyle && (t = a.getComputedStyle(e, null)), !t && e.currentStyle && (t = e.currentStyle), t || (t = e.style), t
                    }(e);
                return r.WebKitCSSMatrix ? ((n = s.transform || s.webkitTransform).split(",").length > 6 && (n = n.split(", ").map((function(e) {
                    return e.replace(",", ".")
                })).join(", ")), i = new r.WebKitCSSMatrix("none" === n ? "" : n)) : a = (i = s.MozTransform || s.OTransform || s.MsTransform || s.msTransform || s.transform || s.getPropertyValue("transform").replace("translate(", "matrix(1, 0, 0, 1,")).toString().split(","), "x" === t && (n = r.WebKitCSSMatrix ? i.m41 : 16 === a.length ? parseFloat(a[12]) : parseFloat(a[4])), "y" === t && (n = r.WebKitCSSMatrix ? i.m42 : 16 === a.length ? parseFloat(a[13]) : parseFloat(a[5])), n || 0
            }

            function _(e) {
                return "object" === typeof e && null !== e && e.constructor && "Object" === Object.prototype.toString.call(e).slice(8, -1)
            }

            function A(e) {
                return "undefined" !== typeof window && "undefined" !== typeof window.HTMLElement ? e instanceof HTMLElement : e && (1 === e.nodeType || 11 === e.nodeType)
            }

            function z() {
                for (var e = Object(arguments.length <= 0 ? void 0 : arguments[0]), t = ["__proto__", "constructor", "prototype"], a = 1; a < arguments.length; a += 1) {
                    var n = a < 0 || arguments.length <= a ? void 0 : arguments[a];
                    if (void 0 !== n && null !== n && !A(n))
                        for (var i = Object.keys(Object(n)).filter((function(e) {
                                return t.indexOf(e) < 0
                            })), r = 0, s = i.length; r < s; r += 1) {
                            var o = i[r],
                                l = Object.getOwnPropertyDescriptor(n, o);
                            void 0 !== l && l.enumerable && (_(e[o]) && _(n[o]) ? n[o].__swiper__ ? e[o] = n[o] : z(e[o], n[o]) : !_(e[o]) && _(n[o]) ? (e[o] = {}, n[o].__swiper__ ? e[o] = n[o] : z(e[o], n[o])) : e[o] = n[o])
                        }
                }
                return e
            }

            function I(e, t, a) {
                e.style.setProperty(t, a)
            }

            function D(e) {
                var t, a = e.swiper,
                    n = e.targetPosition,
                    i = e.side,
                    r = u(),
                    s = -a.translate,
                    o = null,
                    l = a.params.speed;
                a.wrapperEl.style.scrollSnapType = "none", r.cancelAnimationFrame(a.cssModeFrameID);
                var d = n > s ? "next" : "prev",
                    c = function(e, t) {
                        return "next" === d && e >= t || "prev" === d && e <= t
                    };
                ! function e() {
                    t = (new Date).getTime(), null === o && (o = t);
                    var d = Math.max(Math.min((t - o) / l, 1), 0),
                        u = .5 - Math.cos(d * Math.PI) / 2,
                        p = s + u * (n - s);
                    if (c(p, n) && (p = n), a.wrapperEl.scrollTo((0, k.Z)({}, i, p)), c(p, n)) return a.wrapperEl.style.overflow = "hidden", a.wrapperEl.style.scrollSnapType = "", setTimeout((function() {
                        a.wrapperEl.style.overflow = "", a.wrapperEl.scrollTo((0, k.Z)({}, i, p))
                    })), void r.cancelAnimationFrame(a.cssModeFrameID);
                    a.cssModeFrameID = r.requestAnimationFrame(e)
                }()
            }

            function B() {
                return S || (S = function() {
                    var e = u(),
                        t = d();
                    return {
                        smoothScroll: t.documentElement && "scrollBehavior" in t.documentElement.style,
                        touch: !!("ontouchstart" in e || e.DocumentTouch && t instanceof e.DocumentTouch),
                        passiveListener: function() {
                            var t = !1;
                            try {
                                var a = Object.defineProperty({}, "passive", {
                                    get: function() {
                                        t = !0
                                    }
                                });
                                e.addEventListener("testPassiveListener", null, a)
                            } catch (n) {}
                            return t
                        }(),
                        gestures: "ongesturestart" in e
                    }
                }()), S
            }

            function G(e) {
                return void 0 === e && (e = {}), E || (E = function(e) {
                    var t = (void 0 === e ? {} : e).userAgent,
                        a = B(),
                        n = u(),
                        i = n.navigator.platform,
                        r = t || n.navigator.userAgent,
                        s = {
                            ios: !1,
                            android: !1
                        },
                        o = n.screen.width,
                        l = n.screen.height,
                        d = r.match(/(Android);?[\s\/]+([\d.]+)?/),
                        c = r.match(/(iPad).*OS\s([\d_]+)/),
                        p = r.match(/(iPod)(.*OS\s([\d_]+))?/),
                        f = !c && r.match(/(iPhone\sOS|iOS)\s([\d_]+)/),
                        v = "Win32" === i,
                        h = "MacIntel" === i;
                    return !c && h && a.touch && ["1024x1366", "1366x1024", "834x1194", "1194x834", "834x1112", "1112x834", "768x1024", "1024x768", "820x1180", "1180x820", "810x1080", "1080x810"].indexOf("".concat(o, "x").concat(l)) >= 0 && ((c = r.match(/(Version)\/([\d.]+)/)) || (c = [0, 1, "13_0_0"]), h = !1), d && !v && (s.os = "android", s.android = !0), (c || f || p) && (s.os = "ios", s.ios = !0), s
                }(e)), E
            }

            function N() {
                return x || (x = function() {
                    var e = u();
                    return {
                        isSafari: function() {
                            var t = e.navigator.userAgent.toLowerCase();
                            return t.indexOf("safari") >= 0 && t.indexOf("chrome") < 0 && t.indexOf("android") < 0
                        }(),
                        isWebView: /(iPhone|iPod|iPad).*AppleWebKit(?!.*Safari)/i.test(e.navigator.userAgent)
                    }
                }()), x
            }
            var $ = {
                on: function(e, t, a) {
                    var n = this;
                    if (!n.eventsListeners || n.destroyed) return n;
                    if ("function" !== typeof t) return n;
                    var i = a ? "unshift" : "push";
                    return e.split(" ").forEach((function(e) {
                        n.eventsListeners[e] || (n.eventsListeners[e] = []), n.eventsListeners[e][i](t)
                    })), n
                },
                once: function(e, t, a) {
                    var n = this;
                    if (!n.eventsListeners || n.destroyed) return n;
                    if ("function" !== typeof t) return n;

                    function i() {
                        n.off(e, i), i.__emitterProxy && delete i.__emitterProxy;
                        for (var a = arguments.length, r = new Array(a), s = 0; s < a; s++) r[s] = arguments[s];
                        t.apply(n, r)
                    }
                    return i.__emitterProxy = t, n.on(e, i, a)
                },
                onAny: function(e, t) {
                    var a = this;
                    if (!a.eventsListeners || a.destroyed) return a;
                    if ("function" !== typeof e) return a;
                    var n = t ? "unshift" : "push";
                    return a.eventsAnyListeners.indexOf(e) < 0 && a.eventsAnyListeners[n](e), a
                },
                offAny: function(e) {
                    var t = this;
                    if (!t.eventsListeners || t.destroyed) return t;
                    if (!t.eventsAnyListeners) return t;
                    var a = t.eventsAnyListeners.indexOf(e);
                    return a >= 0 && t.eventsAnyListeners.splice(a, 1), t
                },
                off: function(e, t) {
                    var a = this;
                    return !a.eventsListeners || a.destroyed ? a : a.eventsListeners ? (e.split(" ").forEach((function(e) {
                        "undefined" === typeof t ? a.eventsListeners[e] = [] : a.eventsListeners[e] && a.eventsListeners[e].forEach((function(n, i) {
                            (n === t || n.__emitterProxy && n.__emitterProxy === t) && a.eventsListeners[e].splice(i, 1)
                        }))
                    })), a) : a
                },
                emit: function() {
                    var e, t, a, i = this;
                    if (!i.eventsListeners || i.destroyed) return i;
                    if (!i.eventsListeners) return i;
                    for (var r = arguments.length, s = new Array(r), o = 0; o < r; o++) s[o] = arguments[o];
                    "string" === typeof s[0] || Array.isArray(s[0]) ? (e = s[0], t = s.slice(1, s.length), a = i) : (e = s[0].events, t = s[0].data, a = s[0].context || i), t.unshift(a);
                    var l = Array.isArray(e) ? e : e.split(" ");
                    return l.forEach((function(e) {
                        i.eventsAnyListeners && i.eventsAnyListeners.length && i.eventsAnyListeners.forEach((function(i) {
                            i.apply(a, [e].concat((0, n.Z)(t)))
                        })), i.eventsListeners && i.eventsListeners[e] && i.eventsListeners[e].forEach((function(e) {
                            e.apply(a, t)
                        }))
                    })), i
                }
            };
            var j = {
                updateSize: function() {
                    var e, t, a = this,
                        n = a.$el;
                    e = "undefined" !== typeof a.params.width && null !== a.params.width ? a.params.width : n[0].clientWidth, t = "undefined" !== typeof a.params.height && null !== a.params.height ? a.params.height : n[0].clientHeight, 0 === e && a.isHorizontal() || 0 === t && a.isVertical() || (e = e - parseInt(n.css("padding-left") || 0, 10) - parseInt(n.css("padding-right") || 0, 10), t = t - parseInt(n.css("padding-top") || 0, 10) - parseInt(n.css("padding-bottom") || 0, 10), Number.isNaN(e) && (e = 0), Number.isNaN(t) && (t = 0), Object.assign(a, {
                        width: e,
                        height: t,
                        size: a.isHorizontal() ? e : t
                    }))
                },
                updateSlides: function() {
                    var e = this;

                    function t(t) {
                        return e.isHorizontal() ? t : {
                            width: "height",
                            "margin-top": "margin-left",
                            "margin-bottom ": "margin-right",
                            "margin-left": "margin-top",
                            "margin-right": "margin-bottom",
                            "padding-left": "padding-top",
                            "padding-right": "padding-bottom",
                            marginRight: "marginBottom"
                        }[t]
                    }

                    function a(e, a) {
                        return parseFloat(e.getPropertyValue(t(a)) || 0)
                    }
                    var n = e.params,
                        i = e.$wrapperEl,
                        r = e.size,
                        s = e.rtlTranslate,
                        o = e.wrongRTL,
                        l = e.virtual && n.virtual.enabled,
                        d = l ? e.virtual.slides.length : e.slides.length,
                        c = i.children(".".concat(e.params.slideClass)),
                        u = l ? e.virtual.slides.length : c.length,
                        p = [],
                        f = [],
                        v = [],
                        h = n.slidesOffsetBefore;
                    "function" === typeof h && (h = n.slidesOffsetBefore.call(e));
                    var m = n.slidesOffsetAfter;
                    "function" === typeof m && (m = n.slidesOffsetAfter.call(e));
                    var g = e.snapGrid.length,
                        w = e.slidesGrid.length,
                        b = n.spaceBetween,
                        y = -h,
                        T = 0,
                        C = 0;
                    if ("undefined" !== typeof r) {
                        "string" === typeof b && b.indexOf("%") >= 0 && (b = parseFloat(b.replace("%", "")) / 100 * r), e.virtualSize = -b, s ? c.css({
                            marginLeft: "",
                            marginBottom: "",
                            marginTop: ""
                        }) : c.css({
                            marginRight: "",
                            marginBottom: "",
                            marginTop: ""
                        }), n.centeredSlides && n.cssMode && (I(e.wrapperEl, "--swiper-centered-offset-before", ""), I(e.wrapperEl, "--swiper-centered-offset-after", ""));
                        var S, E = n.grid && n.grid.rows > 1 && e.grid;
                        E && e.grid.initSlides(u);
                        for (var x = "auto" === n.slidesPerView && n.breakpoints && Object.keys(n.breakpoints).filter((function(e) {
                                return "undefined" !== typeof n.breakpoints[e].slidesPerView
                            })).length > 0, M = 0; M < u; M += 1) {
                            S = 0;
                            var P = c.eq(M);
                            if (E && e.grid.updateSlide(M, P, u, t), "none" !== P.css("display")) {
                                if ("auto" === n.slidesPerView) {
                                    x && (c[M].style[t("width")] = "");
                                    var O = getComputedStyle(P[0]),
                                        L = P[0].style.transform,
                                        _ = P[0].style.webkitTransform;
                                    if (L && (P[0].style.transform = "none"), _ && (P[0].style.webkitTransform = "none"), n.roundLengths) S = e.isHorizontal() ? P.outerWidth(!0) : P.outerHeight(!0);
                                    else {
                                        var A = a(O, "width"),
                                            z = a(O, "padding-left"),
                                            D = a(O, "padding-right"),
                                            B = a(O, "margin-left"),
                                            G = a(O, "margin-right"),
                                            N = O.getPropertyValue("box-sizing");
                                        if (N && "border-box" === N) S = A + B + G;
                                        else {
                                            var $ = P[0],
                                                j = $.clientWidth;
                                            S = A + z + D + B + G + ($.offsetWidth - j)
                                        }
                                    }
                                    L && (P[0].style.transform = L), _ && (P[0].style.webkitTransform = _), n.roundLengths && (S = Math.floor(S))
                                } else S = (r - (n.slidesPerView - 1) * b) / n.slidesPerView, n.roundLengths && (S = Math.floor(S)), c[M] && (c[M].style[t("width")] = "".concat(S, "px"));
                                c[M] && (c[M].swiperSlideSize = S), v.push(S), n.centeredSlides ? (y = y + S / 2 + T / 2 + b, 0 === T && 0 !== M && (y = y - r / 2 - b), 0 === M && (y = y - r / 2 - b), Math.abs(y) < .001 && (y = 0), n.roundLengths && (y = Math.floor(y)), C % n.slidesPerGroup === 0 && p.push(y), f.push(y)) : (n.roundLengths && (y = Math.floor(y)), (C - Math.min(e.params.slidesPerGroupSkip, C)) % e.params.slidesPerGroup === 0 && p.push(y), f.push(y), y = y + S + b), e.virtualSize += S + b, T = S, C += 1
                            }
                        }
                        if (e.virtualSize = Math.max(e.virtualSize, r) + m, s && o && ("slide" === n.effect || "coverflow" === n.effect) && i.css({
                                width: "".concat(e.virtualSize + n.spaceBetween, "px")
                            }), n.setWrapperSize && i.css((0, k.Z)({}, t("width"), "".concat(e.virtualSize + n.spaceBetween, "px"))), E && e.grid.updateWrapperSize(S, p, t), !n.centeredSlides) {
                            for (var F = [], H = 0; H < p.length; H += 1) {
                                var R = p[H];
                                n.roundLengths && (R = Math.floor(R)), p[H] <= e.virtualSize - r && F.push(R)
                            }
                            p = F, Math.floor(e.virtualSize - r) - Math.floor(p[p.length - 1]) > 1 && p.push(e.virtualSize - r)
                        }
                        if (0 === p.length && (p = [0]), 0 !== n.spaceBetween) {
                            var V = e.isHorizontal() && s ? "marginLeft" : t("marginRight");
                            c.filter((function(e, t) {
                                return !n.cssMode || t !== c.length - 1
                            })).css((0, k.Z)({}, V, "".concat(b, "px")))
                        }
                        if (n.centeredSlides && n.centeredSlidesBounds) {
                            var W = 0;
                            v.forEach((function(e) {
                                W += e + (n.spaceBetween ? n.spaceBetween : 0)
                            }));
                            var q = (W -= n.spaceBetween) - r;
                            p = p.map((function(e) {
                                return e < 0 ? -h : e > q ? q + m : e
                            }))
                        }
                        if (n.centerInsufficientSlides) {
                            var X = 0;
                            if (v.forEach((function(e) {
                                    X += e + (n.spaceBetween ? n.spaceBetween : 0)
                                })), (X -= n.spaceBetween) < r) {
                                var Y = (r - X) / 2;
                                p.forEach((function(e, t) {
                                    p[t] = e - Y
                                })), f.forEach((function(e, t) {
                                    f[t] = e + Y
                                }))
                            }
                        }
                        if (Object.assign(e, {
                                slides: c,
                                snapGrid: p,
                                slidesGrid: f,
                                slidesSizesGrid: v
                            }), n.centeredSlides && n.cssMode && !n.centeredSlidesBounds) {
                            I(e.wrapperEl, "--swiper-centered-offset-before", "".concat(-p[0], "px")), I(e.wrapperEl, "--swiper-centered-offset-after", "".concat(e.size / 2 - v[v.length - 1] / 2, "px"));
                            var Z = -e.snapGrid[0],
                                K = -e.slidesGrid[0];
                            e.snapGrid = e.snapGrid.map((function(e) {
                                return e + Z
                            })), e.slidesGrid = e.slidesGrid.map((function(e) {
                                return e + K
                            }))
                        }
                        if (u !== d && e.emit("slidesLengthChange"), p.length !== g && (e.params.watchOverflow && e.checkOverflow(), e.emit("snapGridLengthChange")), f.length !== w && e.emit("slidesGridLengthChange"), n.watchSlidesProgress && e.updateSlidesOffset(), !l && !n.cssMode && ("slide" === n.effect || "fade" === n.effect)) {
                            var U = "".concat(n.containerModifierClass, "backface-hidden"),
                                Q = e.$el.hasClass(U);
                            u <= n.maxBackfaceHiddenSlides ? Q || e.$el.addClass(U) : Q && e.$el.removeClass(U)
                        }
                    }
                },
                updateAutoHeight: function(e) {
                    var t, a = this,
                        n = [],
                        i = a.virtual && a.params.virtual.enabled,
                        r = 0;
                    "number" === typeof e ? a.setTransition(e) : !0 === e && a.setTransition(a.params.speed);
                    var s = function(e) {
                        return i ? a.slides.filter((function(t) {
                            return parseInt(t.getAttribute("data-swiper-slide-index"), 10) === e
                        }))[0] : a.slides.eq(e)[0]
                    };
                    if ("auto" !== a.params.slidesPerView && a.params.slidesPerView > 1)
                        if (a.params.centeredSlides)(a.visibleSlides || M([])).each((function(e) {
                            n.push(e)
                        }));
                        else
                            for (t = 0; t < Math.ceil(a.params.slidesPerView); t += 1) {
                                var o = a.activeIndex + t;
                                if (o > a.slides.length && !i) break;
                                n.push(s(o))
                            } else n.push(s(a.activeIndex));
                    for (t = 0; t < n.length; t += 1)
                        if ("undefined" !== typeof n[t]) {
                            var l = n[t].offsetHeight;
                            r = l > r ? l : r
                        }(r || 0 === r) && a.$wrapperEl.css("height", "".concat(r, "px"))
                },
                updateSlidesOffset: function() {
                    for (var e = this.slides, t = 0; t < e.length; t += 1) e[t].swiperSlideOffset = this.isHorizontal() ? e[t].offsetLeft : e[t].offsetTop
                },
                updateSlidesProgress: function(e) {
                    void 0 === e && (e = this && this.translate || 0);
                    var t = this,
                        a = t.params,
                        n = t.slides,
                        i = t.rtlTranslate,
                        r = t.snapGrid;
                    if (0 !== n.length) {
                        "undefined" === typeof n[0].swiperSlideOffset && t.updateSlidesOffset();
                        var s = -e;
                        i && (s = e), n.removeClass(a.slideVisibleClass), t.visibleSlidesIndexes = [], t.visibleSlides = [];
                        for (var o = 0; o < n.length; o += 1) {
                            var l = n[o],
                                d = l.swiperSlideOffset;
                            a.cssMode && a.centeredSlides && (d -= n[0].swiperSlideOffset);
                            var c = (s + (a.centeredSlides ? t.minTranslate() : 0) - d) / (l.swiperSlideSize + a.spaceBetween),
                                u = (s - r[0] + (a.centeredSlides ? t.minTranslate() : 0) - d) / (l.swiperSlideSize + a.spaceBetween),
                                p = -(s - d),
                                f = p + t.slidesSizesGrid[o];
                            (p >= 0 && p < t.size - 1 || f > 1 && f <= t.size || p <= 0 && f >= t.size) && (t.visibleSlides.push(l), t.visibleSlidesIndexes.push(o), n.eq(o).addClass(a.slideVisibleClass)), l.progress = i ? -c : c, l.originalProgress = i ? -u : u
                        }
                        t.visibleSlides = M(t.visibleSlides)
                    }
                },
                updateProgress: function(e) {
                    var t = this;
                    if ("undefined" === typeof e) {
                        var a = t.rtlTranslate ? -1 : 1;
                        e = t && t.translate && t.translate * a || 0
                    }
                    var n = t.params,
                        i = t.maxTranslate() - t.minTranslate(),
                        r = t.progress,
                        s = t.isBeginning,
                        o = t.isEnd,
                        l = s,
                        d = o;
                    0 === i ? (r = 0, s = !0, o = !0) : (s = (r = (e - t.minTranslate()) / i) <= 0, o = r >= 1), Object.assign(t, {
                        progress: r,
                        isBeginning: s,
                        isEnd: o
                    }), (n.watchSlidesProgress || n.centeredSlides && n.autoHeight) && t.updateSlidesProgress(e), s && !l && t.emit("reachBeginning toEdge"), o && !d && t.emit("reachEnd toEdge"), (l && !s || d && !o) && t.emit("fromEdge"), t.emit("progress", r)
                },
                updateSlidesClasses: function() {
                    var e, t = this,
                        a = t.slides,
                        n = t.params,
                        i = t.$wrapperEl,
                        r = t.activeIndex,
                        s = t.realIndex,
                        o = t.virtual && n.virtual.enabled;
                    a.removeClass("".concat(n.slideActiveClass, " ").concat(n.slideNextClass, " ").concat(n.slidePrevClass, " ").concat(n.slideDuplicateActiveClass, " ").concat(n.slideDuplicateNextClass, " ").concat(n.slideDuplicatePrevClass)), (e = o ? t.$wrapperEl.find(".".concat(n.slideClass, '[data-swiper-slide-index="').concat(r, '"]')) : a.eq(r)).addClass(n.slideActiveClass), n.loop && (e.hasClass(n.slideDuplicateClass) ? i.children(".".concat(n.slideClass, ":not(.").concat(n.slideDuplicateClass, ')[data-swiper-slide-index="').concat(s, '"]')).addClass(n.slideDuplicateActiveClass) : i.children(".".concat(n.slideClass, ".").concat(n.slideDuplicateClass, '[data-swiper-slide-index="').concat(s, '"]')).addClass(n.slideDuplicateActiveClass));
                    var l = e.nextAll(".".concat(n.slideClass)).eq(0).addClass(n.slideNextClass);
                    n.loop && 0 === l.length && (l = a.eq(0)).addClass(n.slideNextClass);
                    var d = e.prevAll(".".concat(n.slideClass)).eq(0).addClass(n.slidePrevClass);
                    n.loop && 0 === d.length && (d = a.eq(-1)).addClass(n.slidePrevClass), n.loop && (l.hasClass(n.slideDuplicateClass) ? i.children(".".concat(n.slideClass, ":not(.").concat(n.slideDuplicateClass, ')[data-swiper-slide-index="').concat(l.attr("data-swiper-slide-index"), '"]')).addClass(n.slideDuplicateNextClass) : i.children(".".concat(n.slideClass, ".").concat(n.slideDuplicateClass, '[data-swiper-slide-index="').concat(l.attr("data-swiper-slide-index"), '"]')).addClass(n.slideDuplicateNextClass), d.hasClass(n.slideDuplicateClass) ? i.children(".".concat(n.slideClass, ":not(.").concat(n.slideDuplicateClass, ')[data-swiper-slide-index="').concat(d.attr("data-swiper-slide-index"), '"]')).addClass(n.slideDuplicatePrevClass) : i.children(".".concat(n.slideClass, ".").concat(n.slideDuplicateClass, '[data-swiper-slide-index="').concat(d.attr("data-swiper-slide-index"), '"]')).addClass(n.slideDuplicatePrevClass)), t.emitSlidesClasses()
                },
                updateActiveIndex: function(e) {
                    var t, a = this,
                        n = a.rtlTranslate ? a.translate : -a.translate,
                        i = a.slidesGrid,
                        r = a.snapGrid,
                        s = a.params,
                        o = a.activeIndex,
                        l = a.realIndex,
                        d = a.snapIndex,
                        c = e;
                    if ("undefined" === typeof c) {
                        for (var u = 0; u < i.length; u += 1) "undefined" !== typeof i[u + 1] ? n >= i[u] && n < i[u + 1] - (i[u + 1] - i[u]) / 2 ? c = u : n >= i[u] && n < i[u + 1] && (c = u + 1) : n >= i[u] && (c = u);
                        s.normalizeSlideIndex && (c < 0 || "undefined" === typeof c) && (c = 0)
                    }
                    if (r.indexOf(n) >= 0) t = r.indexOf(n);
                    else {
                        var p = Math.min(s.slidesPerGroupSkip, c);
                        t = p + Math.floor((c - p) / s.slidesPerGroup)
                    }
                    if (t >= r.length && (t = r.length - 1), c !== o) {
                        var f = parseInt(a.slides.eq(c).attr("data-swiper-slide-index") || c, 10);
                        Object.assign(a, {
                            snapIndex: t,
                            realIndex: f,
                            previousIndex: o,
                            activeIndex: c
                        }), a.emit("activeIndexChange"), a.emit("snapIndexChange"), l !== f && a.emit("realIndexChange"), (a.initialized || a.params.runCallbacksOnInit) && a.emit("slideChange")
                    } else t !== d && (a.snapIndex = t, a.emit("snapIndexChange"))
                },
                updateClickedSlide: function(e) {
                    var t, a = this,
                        n = a.params,
                        i = M(e).closest(".".concat(n.slideClass))[0],
                        r = !1;
                    if (i)
                        for (var s = 0; s < a.slides.length; s += 1)
                            if (a.slides[s] === i) {
                                r = !0, t = s;
                                break
                            }
                    if (!i || !r) return a.clickedSlide = void 0, void(a.clickedIndex = void 0);
                    a.clickedSlide = i, a.virtual && a.params.virtual.enabled ? a.clickedIndex = parseInt(M(i).attr("data-swiper-slide-index"), 10) : a.clickedIndex = t, n.slideToClickedSlide && void 0 !== a.clickedIndex && a.clickedIndex !== a.activeIndex && a.slideToClickedSlide()
                }
            };
            var F = {
                getTranslate: function(e) {
                    void 0 === e && (e = this.isHorizontal() ? "x" : "y");
                    var t = this,
                        a = t.params,
                        n = t.rtlTranslate,
                        i = t.translate,
                        r = t.$wrapperEl;
                    if (a.virtualTranslate) return n ? -i : i;
                    if (a.cssMode) return i;
                    var s = L(r[0], e);
                    return n && (s = -s), s || 0
                },
                setTranslate: function(e, t) {
                    var a = this,
                        n = a.rtlTranslate,
                        i = a.params,
                        r = a.$wrapperEl,
                        s = a.wrapperEl,
                        o = a.progress,
                        l = 0,
                        d = 0;
                    a.isHorizontal() ? l = n ? -e : e : d = e, i.roundLengths && (l = Math.floor(l), d = Math.floor(d)), i.cssMode ? s[a.isHorizontal() ? "scrollLeft" : "scrollTop"] = a.isHorizontal() ? -l : -d : i.virtualTranslate || r.transform("translate3d(".concat(l, "px, ").concat(d, "px, ").concat(0, "px)")), a.previousTranslate = a.translate, a.translate = a.isHorizontal() ? l : d;
                    var c = a.maxTranslate() - a.minTranslate();
                    (0 === c ? 0 : (e - a.minTranslate()) / c) !== o && a.updateProgress(e), a.emit("setTranslate", a.translate, t)
                },
                minTranslate: function() {
                    return -this.snapGrid[0]
                },
                maxTranslate: function() {
                    return -this.snapGrid[this.snapGrid.length - 1]
                },
                translateTo: function(e, t, a, n, i) {
                    void 0 === e && (e = 0), void 0 === t && (t = this.params.speed), void 0 === a && (a = !0), void 0 === n && (n = !0);
                    var r = this,
                        s = r.params,
                        o = r.wrapperEl;
                    if (r.animating && s.preventInteractionOnTransition) return !1;
                    var l, d = r.minTranslate(),
                        c = r.maxTranslate();
                    if (l = n && e > d ? d : n && e < c ? c : e, r.updateProgress(l), s.cssMode) {
                        var u = r.isHorizontal();
                        if (0 === t) o[u ? "scrollLeft" : "scrollTop"] = -l;
                        else {
                            var p;
                            if (!r.support.smoothScroll) return D({
                                swiper: r,
                                targetPosition: -l,
                                side: u ? "left" : "top"
                            }), !0;
                            o.scrollTo((p = {}, (0, k.Z)(p, u ? "left" : "top", -l), (0, k.Z)(p, "behavior", "smooth"), p))
                        }
                        return !0
                    }
                    return 0 === t ? (r.setTransition(0), r.setTranslate(l), a && (r.emit("beforeTransitionStart", t, i), r.emit("transitionEnd"))) : (r.setTransition(t), r.setTranslate(l), a && (r.emit("beforeTransitionStart", t, i), r.emit("transitionStart")), r.animating || (r.animating = !0, r.onTranslateToWrapperTransitionEnd || (r.onTranslateToWrapperTransitionEnd = function(e) {
                        r && !r.destroyed && e.target === this && (r.$wrapperEl[0].removeEventListener("transitionend", r.onTranslateToWrapperTransitionEnd), r.$wrapperEl[0].removeEventListener("webkitTransitionEnd", r.onTranslateToWrapperTransitionEnd), r.onTranslateToWrapperTransitionEnd = null, delete r.onTranslateToWrapperTransitionEnd, a && r.emit("transitionEnd"))
                    }), r.$wrapperEl[0].addEventListener("transitionend", r.onTranslateToWrapperTransitionEnd), r.$wrapperEl[0].addEventListener("webkitTransitionEnd", r.onTranslateToWrapperTransitionEnd))), !0
                }
            };

            function H(e) {
                var t = e.swiper,
                    a = e.runCallbacks,
                    n = e.direction,
                    i = e.step,
                    r = t.activeIndex,
                    s = t.previousIndex,
                    o = n;
                if (o || (o = r > s ? "next" : r < s ? "prev" : "reset"), t.emit("transition".concat(i)), a && r !== s) {
                    if ("reset" === o) return void t.emit("slideResetTransition".concat(i));
                    t.emit("slideChangeTransition".concat(i)), "next" === o ? t.emit("slideNextTransition".concat(i)) : t.emit("slidePrevTransition".concat(i))
                }
            }
            var R = {
                slideTo: function(e, t, a, n, i) {
                    if (void 0 === e && (e = 0), void 0 === t && (t = this.params.speed), void 0 === a && (a = !0), "number" !== typeof e && "string" !== typeof e) throw new Error("The 'index' argument cannot have type other than 'number' or 'string'. [".concat(typeof e, "] given."));
                    if ("string" === typeof e) {
                        var r = parseInt(e, 10);
                        if (!isFinite(r)) throw new Error("The passed-in 'index' (string) couldn't be converted to 'number'. [".concat(e, "] given."));
                        e = r
                    }
                    var s = this,
                        o = e;
                    o < 0 && (o = 0);
                    var l = s.params,
                        d = s.snapGrid,
                        c = s.slidesGrid,
                        u = s.previousIndex,
                        p = s.activeIndex,
                        f = s.rtlTranslate,
                        v = s.wrapperEl,
                        h = s.enabled;
                    if (s.animating && l.preventInteractionOnTransition || !h && !n && !i) return !1;
                    var m = Math.min(s.params.slidesPerGroupSkip, o),
                        g = m + Math.floor((o - m) / s.params.slidesPerGroup);
                    g >= d.length && (g = d.length - 1), (p || l.initialSlide || 0) === (u || 0) && a && s.emit("beforeSlideChangeStart");
                    var w, b = -d[g];
                    if (s.updateProgress(b), l.normalizeSlideIndex)
                        for (var y = 0; y < c.length; y += 1) {
                            var T = -Math.floor(100 * b),
                                C = Math.floor(100 * c[y]),
                                S = Math.floor(100 * c[y + 1]);
                            "undefined" !== typeof c[y + 1] ? T >= C && T < S - (S - C) / 2 ? o = y : T >= C && T < S && (o = y + 1) : T >= C && (o = y)
                        }
                    if (s.initialized && o !== p) {
                        if (!s.allowSlideNext && b < s.translate && b < s.minTranslate()) return !1;
                        if (!s.allowSlidePrev && b > s.translate && b > s.maxTranslate() && (p || 0) !== o) return !1
                    }
                    if (w = o > p ? "next" : o < p ? "prev" : "reset", f && -b === s.translate || !f && b === s.translate) return s.updateActiveIndex(o), l.autoHeight && s.updateAutoHeight(), s.updateSlidesClasses(), "slide" !== l.effect && s.setTranslate(b), "reset" !== w && (s.transitionStart(a, w), s.transitionEnd(a, w)), !1;
                    if (l.cssMode) {
                        var E = s.isHorizontal(),
                            x = f ? b : -b;
                        if (0 === t) {
                            var M = s.virtual && s.params.virtual.enabled;
                            M && (s.wrapperEl.style.scrollSnapType = "none", s._immediateVirtual = !0), v[E ? "scrollLeft" : "scrollTop"] = x, M && requestAnimationFrame((function() {
                                s.wrapperEl.style.scrollSnapType = "", s._swiperImmediateVirtual = !1
                            }))
                        } else {
                            var P;
                            if (!s.support.smoothScroll) return D({
                                swiper: s,
                                targetPosition: x,
                                side: E ? "left" : "top"
                            }), !0;
                            v.scrollTo((P = {}, (0, k.Z)(P, E ? "left" : "top", x), (0, k.Z)(P, "behavior", "smooth"), P))
                        }
                        return !0
                    }
                    return s.setTransition(t), s.setTranslate(b), s.updateActiveIndex(o), s.updateSlidesClasses(), s.emit("beforeTransitionStart", t, n), s.transitionStart(a, w), 0 === t ? s.transitionEnd(a, w) : s.animating || (s.animating = !0, s.onSlideToWrapperTransitionEnd || (s.onSlideToWrapperTransitionEnd = function(e) {
                        s && !s.destroyed && e.target === this && (s.$wrapperEl[0].removeEventListener("transitionend", s.onSlideToWrapperTransitionEnd), s.$wrapperEl[0].removeEventListener("webkitTransitionEnd", s.onSlideToWrapperTransitionEnd), s.onSlideToWrapperTransitionEnd = null, delete s.onSlideToWrapperTransitionEnd, s.transitionEnd(a, w))
                    }), s.$wrapperEl[0].addEventListener("transitionend", s.onSlideToWrapperTransitionEnd), s.$wrapperEl[0].addEventListener("webkitTransitionEnd", s.onSlideToWrapperTransitionEnd)), !0
                },
                slideToLoop: function(e, t, a, n) {
                    if (void 0 === e && (e = 0), void 0 === t && (t = this.params.speed), void 0 === a && (a = !0), "string" === typeof e) {
                        var i = parseInt(e, 10);
                        if (!isFinite(i)) throw new Error("The passed-in 'index' (string) couldn't be converted to 'number'. [".concat(e, "] given."));
                        e = i
                    }
                    var r = this,
                        s = e;
                    return r.params.loop && (s += r.loopedSlides), r.slideTo(s, t, a, n)
                },
                slideNext: function(e, t, a) {
                    void 0 === e && (e = this.params.speed), void 0 === t && (t = !0);
                    var n = this,
                        i = n.animating,
                        r = n.enabled,
                        s = n.params;
                    if (!r) return n;
                    var o = s.slidesPerGroup;
                    "auto" === s.slidesPerView && 1 === s.slidesPerGroup && s.slidesPerGroupAuto && (o = Math.max(n.slidesPerViewDynamic("current", !0), 1));
                    var l = n.activeIndex < s.slidesPerGroupSkip ? 1 : o;
                    if (s.loop) {
                        if (i && s.loopPreventsSlide) return !1;
                        n.loopFix(), n._clientLeft = n.$wrapperEl[0].clientLeft
                    }
                    return s.rewind && n.isEnd ? n.slideTo(0, e, t, a) : n.slideTo(n.activeIndex + l, e, t, a)
                },
                slidePrev: function(e, t, a) {
                    void 0 === e && (e = this.params.speed), void 0 === t && (t = !0);
                    var n = this,
                        i = n.params,
                        r = n.animating,
                        s = n.snapGrid,
                        o = n.slidesGrid,
                        l = n.rtlTranslate;
                    if (!n.enabled) return n;
                    if (i.loop) {
                        if (r && i.loopPreventsSlide) return !1;
                        n.loopFix(), n._clientLeft = n.$wrapperEl[0].clientLeft
                    }

                    function d(e) {
                        return e < 0 ? -Math.floor(Math.abs(e)) : Math.floor(e)
                    }
                    var c, u = d(l ? n.translate : -n.translate),
                        p = s.map((function(e) {
                            return d(e)
                        })),
                        f = s[p.indexOf(u) - 1];
                    "undefined" === typeof f && i.cssMode && (s.forEach((function(e, t) {
                        u >= e && (c = t)
                    })), "undefined" !== typeof c && (f = s[c > 0 ? c - 1 : c]));
                    var v = 0;
                    if ("undefined" !== typeof f && ((v = o.indexOf(f)) < 0 && (v = n.activeIndex - 1), "auto" === i.slidesPerView && 1 === i.slidesPerGroup && i.slidesPerGroupAuto && (v = v - n.slidesPerViewDynamic("previous", !0) + 1, v = Math.max(v, 0))), i.rewind && n.isBeginning) {
                        var h = n.params.virtual && n.params.virtual.enabled && n.virtual ? n.virtual.slides.length - 1 : n.slides.length - 1;
                        return n.slideTo(h, e, t, a)
                    }
                    return n.slideTo(v, e, t, a)
                },
                slideReset: function(e, t, a) {
                    return void 0 === e && (e = this.params.speed), void 0 === t && (t = !0), this.slideTo(this.activeIndex, e, t, a)
                },
                slideToClosest: function(e, t, a, n) {
                    void 0 === e && (e = this.params.speed), void 0 === t && (t = !0), void 0 === n && (n = .5);
                    var i = this,
                        r = i.activeIndex,
                        s = Math.min(i.params.slidesPerGroupSkip, r),
                        o = s + Math.floor((r - s) / i.params.slidesPerGroup),
                        l = i.rtlTranslate ? i.translate : -i.translate;
                    if (l >= i.snapGrid[o]) {
                        var d = i.snapGrid[o];
                        l - d > (i.snapGrid[o + 1] - d) * n && (r += i.params.slidesPerGroup)
                    } else {
                        var c = i.snapGrid[o - 1];
                        l - c <= (i.snapGrid[o] - c) * n && (r -= i.params.slidesPerGroup)
                    }
                    return r = Math.max(r, 0), r = Math.min(r, i.slidesGrid.length - 1), i.slideTo(r, e, t, a)
                },
                slideToClickedSlide: function() {
                    var e, t = this,
                        a = t.params,
                        n = t.$wrapperEl,
                        i = "auto" === a.slidesPerView ? t.slidesPerViewDynamic() : a.slidesPerView,
                        r = t.clickedIndex;
                    if (a.loop) {
                        if (t.animating) return;
                        e = parseInt(M(t.clickedSlide).attr("data-swiper-slide-index"), 10), a.centeredSlides ? r < t.loopedSlides - i / 2 || r > t.slides.length - t.loopedSlides + i / 2 ? (t.loopFix(), r = n.children(".".concat(a.slideClass, '[data-swiper-slide-index="').concat(e, '"]:not(.').concat(a.slideDuplicateClass, ")")).eq(0).index(), P((function() {
                            t.slideTo(r)
                        }))) : t.slideTo(r) : r > t.slides.length - i ? (t.loopFix(), r = n.children(".".concat(a.slideClass, '[data-swiper-slide-index="').concat(e, '"]:not(.').concat(a.slideDuplicateClass, ")")).eq(0).index(), P((function() {
                            t.slideTo(r)
                        }))) : t.slideTo(r)
                    } else t.slideTo(r)
                }
            };
            var V = {
                loopCreate: function() {
                    var e = this,
                        t = d(),
                        a = e.params,
                        n = e.$wrapperEl,
                        i = n.children().length > 0 ? M(n.children()[0].parentNode) : n;
                    i.children(".".concat(a.slideClass, ".").concat(a.slideDuplicateClass)).remove();
                    var r = i.children(".".concat(a.slideClass));
                    if (a.loopFillGroupWithBlank) {
                        var s = a.slidesPerGroup - r.length % a.slidesPerGroup;
                        if (s !== a.slidesPerGroup) {
                            for (var o = 0; o < s; o += 1) {
                                var l = M(t.createElement("div")).addClass("".concat(a.slideClass, " ").concat(a.slideBlankClass));
                                i.append(l)
                            }
                            r = i.children(".".concat(a.slideClass))
                        }
                    }
                    "auto" !== a.slidesPerView || a.loopedSlides || (a.loopedSlides = r.length), e.loopedSlides = Math.ceil(parseFloat(a.loopedSlides || a.slidesPerView, 10)), e.loopedSlides += a.loopAdditionalSlides, e.loopedSlides > r.length && e.params.loopedSlidesLimit && (e.loopedSlides = r.length);
                    var c = [],
                        u = [];
                    r.each((function(e, t) {
                        M(e).attr("data-swiper-slide-index", t)
                    }));
                    for (var p = 0; p < e.loopedSlides; p += 1) {
                        var f = p - Math.floor(p / r.length) * r.length;
                        u.push(r.eq(f)[0]), c.unshift(r.eq(r.length - f - 1)[0])
                    }
                    for (var v = 0; v < u.length; v += 1) i.append(M(u[v].cloneNode(!0)).addClass(a.slideDuplicateClass));
                    for (var h = c.length - 1; h >= 0; h -= 1) i.prepend(M(c[h].cloneNode(!0)).addClass(a.slideDuplicateClass))
                },
                loopFix: function() {
                    var e = this;
                    e.emit("beforeLoopFix");
                    var t, a = e.activeIndex,
                        n = e.slides,
                        i = e.loopedSlides,
                        r = e.allowSlidePrev,
                        s = e.allowSlideNext,
                        o = e.snapGrid,
                        l = e.rtlTranslate;
                    e.allowSlidePrev = !0, e.allowSlideNext = !0;
                    var d = -o[a] - e.getTranslate();
                    if (a < i) t = n.length - 3 * i + a, t += i, e.slideTo(t, 0, !1, !0) && 0 !== d && e.setTranslate((l ? -e.translate : e.translate) - d);
                    else if (a >= n.length - i) {
                        t = -n.length + a + i, t += i, e.slideTo(t, 0, !1, !0) && 0 !== d && e.setTranslate((l ? -e.translate : e.translate) - d)
                    }
                    e.allowSlidePrev = r, e.allowSlideNext = s, e.emit("loopFix")
                },
                loopDestroy: function() {
                    var e = this,
                        t = e.$wrapperEl,
                        a = e.params,
                        n = e.slides;
                    t.children(".".concat(a.slideClass, ".").concat(a.slideDuplicateClass, ",.").concat(a.slideClass, ".").concat(a.slideBlankClass)).remove(), n.removeAttr("data-swiper-slide-index")
                }
            };

            function W(e) {
                var t = this,
                    a = d(),
                    n = u(),
                    i = t.touchEventsData,
                    r = t.params,
                    s = t.touches;
                if (t.enabled && (!t.animating || !r.preventInteractionOnTransition)) {
                    !t.animating && r.cssMode && r.loop && t.loopFix();
                    var o = e;
                    o.originalEvent && (o = o.originalEvent);
                    var l = M(o.target);
                    if (("wrapper" !== r.touchEventsTarget || l.closest(t.wrapperEl).length) && (i.isTouchEvent = "touchstart" === o.type, (i.isTouchEvent || !("which" in o) || 3 !== o.which) && !(!i.isTouchEvent && "button" in o && o.button > 0) && (!i.isTouched || !i.isMoved))) {
                        !!r.noSwipingClass && "" !== r.noSwipingClass && o.target && o.target.shadowRoot && e.path && e.path[0] && (l = M(e.path[0]));
                        var c = r.noSwipingSelector ? r.noSwipingSelector : ".".concat(r.noSwipingClass),
                            p = !(!o.target || !o.target.shadowRoot);
                        if (r.noSwiping && (p ? function(e, t) {
                                return void 0 === t && (t = this),
                                    function t(a) {
                                        if (!a || a === d() || a === u()) return null;
                                        a.assignedSlot && (a = a.assignedSlot);
                                        var n = a.closest(e);
                                        return n || a.getRootNode ? n || t(a.getRootNode().host) : null
                                    }(t)
                            }(c, l[0]) : l.closest(c)[0])) t.allowClick = !0;
                        else if (!r.swipeHandler || l.closest(r.swipeHandler)[0]) {
                            s.currentX = "touchstart" === o.type ? o.targetTouches[0].pageX : o.pageX, s.currentY = "touchstart" === o.type ? o.targetTouches[0].pageY : o.pageY;
                            var f = s.currentX,
                                v = s.currentY,
                                h = r.edgeSwipeDetection || r.iOSEdgeSwipeDetection,
                                m = r.edgeSwipeThreshold || r.iOSEdgeSwipeThreshold;
                            if (h && (f <= m || f >= n.innerWidth - m)) {
                                if ("prevent" !== h) return;
                                e.preventDefault()
                            }
                            if (Object.assign(i, {
                                    isTouched: !0,
                                    isMoved: !1,
                                    allowTouchCallbacks: !0,
                                    isScrolling: void 0,
                                    startMoving: void 0
                                }), s.startX = f, s.startY = v, i.touchStartTime = O(), t.allowClick = !0, t.updateSize(), t.swipeDirection = void 0, r.threshold > 0 && (i.allowThresholdMove = !1), "touchstart" !== o.type) {
                                var g = !0;
                                l.is(i.focusableElements) && (g = !1, "SELECT" === l[0].nodeName && (i.isTouched = !1)), a.activeElement && M(a.activeElement).is(i.focusableElements) && a.activeElement !== l[0] && a.activeElement.blur();
                                var w = g && t.allowTouchMove && r.touchStartPreventDefault;
                                !r.touchStartForcePreventDefault && !w || l[0].isContentEditable || o.preventDefault()
                            }
                            t.params.freeMode && t.params.freeMode.enabled && t.freeMode && t.animating && !r.cssMode && t.freeMode.onTouchStart(), t.emit("touchStart", o)
                        }
                    }
                }
            }

            function q(e) {
                var t = d(),
                    a = this,
                    n = a.touchEventsData,
                    i = a.params,
                    r = a.touches,
                    s = a.rtlTranslate;
                if (a.enabled) {
                    var o = e;
                    if (o.originalEvent && (o = o.originalEvent), n.isTouched) {
                        if (!n.isTouchEvent || "touchmove" === o.type) {
                            var l = "touchmove" === o.type && o.targetTouches && (o.targetTouches[0] || o.changedTouches[0]),
                                c = "touchmove" === o.type ? l.pageX : o.pageX,
                                u = "touchmove" === o.type ? l.pageY : o.pageY;
                            if (o.preventedByNestedSwiper) return r.startX = c, void(r.startY = u);
                            if (!a.allowTouchMove) return M(o.target).is(n.focusableElements) || (a.allowClick = !1), void(n.isTouched && (Object.assign(r, {
                                startX: c,
                                startY: u,
                                currentX: c,
                                currentY: u
                            }), n.touchStartTime = O()));
                            if (n.isTouchEvent && i.touchReleaseOnEdges && !i.loop)
                                if (a.isVertical()) {
                                    if (u < r.startY && a.translate <= a.maxTranslate() || u > r.startY && a.translate >= a.minTranslate()) return n.isTouched = !1, void(n.isMoved = !1)
                                } else if (c < r.startX && a.translate <= a.maxTranslate() || c > r.startX && a.translate >= a.minTranslate()) return;
                            if (n.isTouchEvent && t.activeElement && o.target === t.activeElement && M(o.target).is(n.focusableElements)) return n.isMoved = !0, void(a.allowClick = !1);
                            if (n.allowTouchCallbacks && a.emit("touchMove", o), !(o.targetTouches && o.targetTouches.length > 1)) {
                                r.currentX = c, r.currentY = u;
                                var p = r.currentX - r.startX,
                                    f = r.currentY - r.startY;
                                if (!(a.params.threshold && Math.sqrt(Math.pow(p, 2) + Math.pow(f, 2)) < a.params.threshold)) {
                                    var v;
                                    if ("undefined" === typeof n.isScrolling) a.isHorizontal() && r.currentY === r.startY || a.isVertical() && r.currentX === r.startX ? n.isScrolling = !1 : p * p + f * f >= 25 && (v = 180 * Math.atan2(Math.abs(f), Math.abs(p)) / Math.PI, n.isScrolling = a.isHorizontal() ? v > i.touchAngle : 90 - v > i.touchAngle);
                                    if (n.isScrolling && a.emit("touchMoveOpposite", o), "undefined" === typeof n.startMoving && (r.currentX === r.startX && r.currentY === r.startY || (n.startMoving = !0)), n.isScrolling) n.isTouched = !1;
                                    else if (n.startMoving) {
                                        a.allowClick = !1, !i.cssMode && o.cancelable && o.preventDefault(), i.touchMoveStopPropagation && !i.nested && o.stopPropagation(), n.isMoved || (i.loop && !i.cssMode && a.loopFix(), n.startTranslate = a.getTranslate(), a.setTransition(0), a.animating && a.$wrapperEl.trigger("webkitTransitionEnd transitionend"), n.allowMomentumBounce = !1, !i.grabCursor || !0 !== a.allowSlideNext && !0 !== a.allowSlidePrev || a.setGrabCursor(!0), a.emit("sliderFirstMove", o)), a.emit("sliderMove", o), n.isMoved = !0;
                                        var h = a.isHorizontal() ? p : f;
                                        r.diff = h, h *= i.touchRatio, s && (h = -h), a.swipeDirection = h > 0 ? "prev" : "next", n.currentTranslate = h + n.startTranslate;
                                        var m = !0,
                                            g = i.resistanceRatio;
                                        if (i.touchReleaseOnEdges && (g = 0), h > 0 && n.currentTranslate > a.minTranslate() ? (m = !1, i.resistance && (n.currentTranslate = a.minTranslate() - 1 + Math.pow(-a.minTranslate() + n.startTranslate + h, g))) : h < 0 && n.currentTranslate < a.maxTranslate() && (m = !1, i.resistance && (n.currentTranslate = a.maxTranslate() + 1 - Math.pow(a.maxTranslate() - n.startTranslate - h, g))), m && (o.preventedByNestedSwiper = !0), !a.allowSlideNext && "next" === a.swipeDirection && n.currentTranslate < n.startTranslate && (n.currentTranslate = n.startTranslate), !a.allowSlidePrev && "prev" === a.swipeDirection && n.currentTranslate > n.startTranslate && (n.currentTranslate = n.startTranslate), a.allowSlidePrev || a.allowSlideNext || (n.currentTranslate = n.startTranslate), i.threshold > 0) {
                                            if (!(Math.abs(h) > i.threshold || n.allowThresholdMove)) return void(n.currentTranslate = n.startTranslate);
                                            if (!n.allowThresholdMove) return n.allowThresholdMove = !0, r.startX = r.currentX, r.startY = r.currentY, n.currentTranslate = n.startTranslate, void(r.diff = a.isHorizontal() ? r.currentX - r.startX : r.currentY - r.startY)
                                        }
                                        i.followFinger && !i.cssMode && ((i.freeMode && i.freeMode.enabled && a.freeMode || i.watchSlidesProgress) && (a.updateActiveIndex(), a.updateSlidesClasses()), a.params.freeMode && i.freeMode.enabled && a.freeMode && a.freeMode.onTouchMove(), a.updateProgress(n.currentTranslate), a.setTranslate(n.currentTranslate))
                                    }
                                }
                            }
                        }
                    } else n.startMoving && n.isScrolling && a.emit("touchMoveOpposite", o)
                }
            }

            function X(e) {
                var t = this,
                    a = t.touchEventsData,
                    n = t.params,
                    i = t.touches,
                    r = t.rtlTranslate,
                    s = t.slidesGrid;
                if (t.enabled) {
                    var o = e;
                    if (o.originalEvent && (o = o.originalEvent), a.allowTouchCallbacks && t.emit("touchEnd", o), a.allowTouchCallbacks = !1, !a.isTouched) return a.isMoved && n.grabCursor && t.setGrabCursor(!1), a.isMoved = !1, void(a.startMoving = !1);
                    n.grabCursor && a.isMoved && a.isTouched && (!0 === t.allowSlideNext || !0 === t.allowSlidePrev) && t.setGrabCursor(!1);
                    var l, d = O(),
                        c = d - a.touchStartTime;
                    if (t.allowClick) {
                        var u = o.path || o.composedPath && o.composedPath();
                        t.updateClickedSlide(u && u[0] || o.target), t.emit("tap click", o), c < 300 && d - a.lastClickTime < 300 && t.emit("doubleTap doubleClick", o)
                    }
                    if (a.lastClickTime = O(), P((function() {
                            t.destroyed || (t.allowClick = !0)
                        })), !a.isTouched || !a.isMoved || !t.swipeDirection || 0 === i.diff || a.currentTranslate === a.startTranslate) return a.isTouched = !1, a.isMoved = !1, void(a.startMoving = !1);
                    if (a.isTouched = !1, a.isMoved = !1, a.startMoving = !1, l = n.followFinger ? r ? t.translate : -t.translate : -a.currentTranslate, !n.cssMode)
                        if (t.params.freeMode && n.freeMode.enabled) t.freeMode.onTouchEnd({
                            currentPos: l
                        });
                        else {
                            for (var p = 0, f = t.slidesSizesGrid[0], v = 0; v < s.length; v += v < n.slidesPerGroupSkip ? 1 : n.slidesPerGroup) {
                                var h = v < n.slidesPerGroupSkip - 1 ? 1 : n.slidesPerGroup;
                                "undefined" !== typeof s[v + h] ? l >= s[v] && l < s[v + h] && (p = v, f = s[v + h] - s[v]) : l >= s[v] && (p = v, f = s[s.length - 1] - s[s.length - 2])
                            }
                            var m = null,
                                g = null;
                            n.rewind && (t.isBeginning ? g = t.params.virtual && t.params.virtual.enabled && t.virtual ? t.virtual.slides.length - 1 : t.slides.length - 1 : t.isEnd && (m = 0));
                            var w = (l - s[p]) / f,
                                b = p < n.slidesPerGroupSkip - 1 ? 1 : n.slidesPerGroup;
                            if (c > n.longSwipesMs) {
                                if (!n.longSwipes) return void t.slideTo(t.activeIndex);
                                "next" === t.swipeDirection && (w >= n.longSwipesRatio ? t.slideTo(n.rewind && t.isEnd ? m : p + b) : t.slideTo(p)), "prev" === t.swipeDirection && (w > 1 - n.longSwipesRatio ? t.slideTo(p + b) : null !== g && w < 0 && Math.abs(w) > n.longSwipesRatio ? t.slideTo(g) : t.slideTo(p))
                            } else {
                                if (!n.shortSwipes) return void t.slideTo(t.activeIndex);
                                t.navigation && (o.target === t.navigation.nextEl || o.target === t.navigation.prevEl) ? o.target === t.navigation.nextEl ? t.slideTo(p + b) : t.slideTo(p) : ("next" === t.swipeDirection && t.slideTo(null !== m ? m : p + b), "prev" === t.swipeDirection && t.slideTo(null !== g ? g : p))
                            }
                        }
                }
            }

            function Y() {
                var e = this,
                    t = e.params,
                    a = e.el;
                if (!a || 0 !== a.offsetWidth) {
                    t.breakpoints && e.setBreakpoint();
                    var n = e.allowSlideNext,
                        i = e.allowSlidePrev,
                        r = e.snapGrid;
                    e.allowSlideNext = !0, e.allowSlidePrev = !0, e.updateSize(), e.updateSlides(), e.updateSlidesClasses(), ("auto" === t.slidesPerView || t.slidesPerView > 1) && e.isEnd && !e.isBeginning && !e.params.centeredSlides ? e.slideTo(e.slides.length - 1, 0, !1, !0) : e.slideTo(e.activeIndex, 0, !1, !0), e.autoplay && e.autoplay.running && e.autoplay.paused && e.autoplay.run(), e.allowSlidePrev = i, e.allowSlideNext = n, e.params.watchOverflow && r !== e.snapGrid && e.checkOverflow()
                }
            }

            function Z(e) {
                var t = this;
                t.enabled && (t.allowClick || (t.params.preventClicks && e.preventDefault(), t.params.preventClicksPropagation && t.animating && (e.stopPropagation(), e.stopImmediatePropagation())))
            }

            function K() {
                var e = this,
                    t = e.wrapperEl,
                    a = e.rtlTranslate;
                if (e.enabled) {
                    e.previousTranslate = e.translate, e.isHorizontal() ? e.translate = -t.scrollLeft : e.translate = -t.scrollTop, 0 === e.translate && (e.translate = 0), e.updateActiveIndex(), e.updateSlidesClasses();
                    var n = e.maxTranslate() - e.minTranslate();
                    (0 === n ? 0 : (e.translate - e.minTranslate()) / n) !== e.progress && e.updateProgress(a ? -e.translate : e.translate), e.emit("setTranslate", e.translate, !1)
                }
            }
            var U = !1;

            function Q() {}
            var J = function(e, t) {
                var a = d(),
                    n = e.params,
                    i = e.touchEvents,
                    r = e.el,
                    s = e.wrapperEl,
                    o = e.device,
                    l = e.support,
                    c = !!n.nested,
                    u = "on" === t ? "addEventListener" : "removeEventListener",
                    p = t;
                if (l.touch) {
                    var f = !("touchstart" !== i.start || !l.passiveListener || !n.passiveListeners) && {
                        passive: !0,
                        capture: !1
                    };
                    r[u](i.start, e.onTouchStart, f), r[u](i.move, e.onTouchMove, l.passiveListener ? {
                        passive: !1,
                        capture: c
                    } : c), r[u](i.end, e.onTouchEnd, f), i.cancel && r[u](i.cancel, e.onTouchEnd, f)
                } else r[u](i.start, e.onTouchStart, !1), a[u](i.move, e.onTouchMove, c), a[u](i.end, e.onTouchEnd, !1);
                (n.preventClicks || n.preventClicksPropagation) && r[u]("click", e.onClick, !0), n.cssMode && s[u]("scroll", e.onScroll), n.updateOnWindowResize ? e[p](o.ios || o.android ? "resize orientationchange observerUpdate" : "resize observerUpdate", Y, !0) : e[p]("observerUpdate", Y, !0)
            };
            var ee = {
                    attachEvents: function() {
                        var e = this,
                            t = d(),
                            a = e.params,
                            n = e.support;
                        e.onTouchStart = W.bind(e), e.onTouchMove = q.bind(e), e.onTouchEnd = X.bind(e), a.cssMode && (e.onScroll = K.bind(e)), e.onClick = Z.bind(e), n.touch && !U && (t.addEventListener("touchstart", Q), U = !0), J(e, "on")
                    },
                    detachEvents: function() {
                        J(this, "off")
                    }
                },
                te = function(e, t) {
                    return e.grid && t.grid && t.grid.rows > 1
                };
            var ae = {
                setBreakpoint: function() {
                    var e = this,
                        t = e.activeIndex,
                        a = e.initialized,
                        n = e.loopedSlides,
                        i = void 0 === n ? 0 : n,
                        r = e.params,
                        s = e.$el,
                        o = r.breakpoints;
                    if (o && (!o || 0 !== Object.keys(o).length)) {
                        var l = e.getBreakpoint(o, e.params.breakpointsBase, e.el);
                        if (l && e.currentBreakpoint !== l) {
                            var d = (l in o ? o[l] : void 0) || e.originalParams,
                                c = te(e, r),
                                u = te(e, d),
                                p = r.enabled;
                            c && !u ? (s.removeClass("".concat(r.containerModifierClass, "grid ").concat(r.containerModifierClass, "grid-column")), e.emitContainerClasses()) : !c && u && (s.addClass("".concat(r.containerModifierClass, "grid")), (d.grid.fill && "column" === d.grid.fill || !d.grid.fill && "column" === r.grid.fill) && s.addClass("".concat(r.containerModifierClass, "grid-column")), e.emitContainerClasses()), ["navigation", "pagination", "scrollbar"].forEach((function(t) {
                                var a = r[t] && r[t].enabled,
                                    n = d[t] && d[t].enabled;
                                a && !n && e[t].disable(), !a && n && e[t].enable()
                            }));
                            var f = d.direction && d.direction !== r.direction,
                                v = r.loop && (d.slidesPerView !== r.slidesPerView || f);
                            f && a && e.changeDirection(), z(e.params, d);
                            var h = e.params.enabled;
                            Object.assign(e, {
                                allowTouchMove: e.params.allowTouchMove,
                                allowSlideNext: e.params.allowSlideNext,
                                allowSlidePrev: e.params.allowSlidePrev
                            }), p && !h ? e.disable() : !p && h && e.enable(), e.currentBreakpoint = l, e.emit("_beforeBreakpoint", d), v && a && (e.loopDestroy(), e.loopCreate(), e.updateSlides(), e.slideTo(t - i + e.loopedSlides, 0, !1)), e.emit("breakpoint", d)
                        }
                    }
                },
                getBreakpoint: function(e, t, a) {
                    if (void 0 === t && (t = "window"), e && ("container" !== t || a)) {
                        var n = !1,
                            i = u(),
                            r = "window" === t ? i.innerHeight : a.clientHeight,
                            s = Object.keys(e).map((function(e) {
                                if ("string" === typeof e && 0 === e.indexOf("@")) {
                                    var t = parseFloat(e.substr(1));
                                    return {
                                        value: r * t,
                                        point: e
                                    }
                                }
                                return {
                                    value: e,
                                    point: e
                                }
                            }));
                        s.sort((function(e, t) {
                            return parseInt(e.value, 10) - parseInt(t.value, 10)
                        }));
                        for (var o = 0; o < s.length; o += 1) {
                            var l = s[o],
                                d = l.point,
                                c = l.value;
                            "window" === t ? i.matchMedia("(min-width: ".concat(c, "px)")).matches && (n = d) : c <= a.clientWidth && (n = d)
                        }
                        return n || "max"
                    }
                }
            };
            var ne = {
                addClasses: function() {
                    var e = this,
                        t = e.classNames,
                        a = e.params,
                        i = e.rtl,
                        r = e.$el,
                        s = e.device,
                        o = e.support,
                        l = function(e, t) {
                            var a = [];
                            return e.forEach((function(e) {
                                "object" === typeof e ? Object.keys(e).forEach((function(n) {
                                    e[n] && a.push(t + n)
                                })) : "string" === typeof e && a.push(t + e)
                            })), a
                        }(["initialized", a.direction, {
                            "pointer-events": !o.touch
                        }, {
                            "free-mode": e.params.freeMode && a.freeMode.enabled
                        }, {
                            autoheight: a.autoHeight
                        }, {
                            rtl: i
                        }, {
                            grid: a.grid && a.grid.rows > 1
                        }, {
                            "grid-column": a.grid && a.grid.rows > 1 && "column" === a.grid.fill
                        }, {
                            android: s.android
                        }, {
                            ios: s.ios
                        }, {
                            "css-mode": a.cssMode
                        }, {
                            centered: a.cssMode && a.centeredSlides
                        }, {
                            "watch-progress": a.watchSlidesProgress
                        }], a.containerModifierClass);
                    t.push.apply(t, (0, n.Z)(l)), r.addClass((0, n.Z)(t).join(" ")), e.emitContainerClasses()
                },
                removeClasses: function() {
                    var e = this,
                        t = e.$el,
                        a = e.classNames;
                    t.removeClass(a.join(" ")), e.emitContainerClasses()
                }
            };
            var ie = {
                init: !0,
                direction: "horizontal",
                touchEventsTarget: "wrapper",
                initialSlide: 0,
                speed: 300,
                cssMode: !1,
                updateOnWindowResize: !0,
                resizeObserver: !0,
                nested: !1,
                createElements: !1,
                enabled: !0,
                focusableElements: "input, select, option, textarea, button, video, label",
                width: null,
                height: null,
                preventInteractionOnTransition: !1,
                userAgent: null,
                url: null,
                edgeSwipeDetection: !1,
                edgeSwipeThreshold: 20,
                autoHeight: !1,
                setWrapperSize: !1,
                virtualTranslate: !1,
                effect: "slide",
                breakpoints: void 0,
                breakpointsBase: "window",
                spaceBetween: 0,
                slidesPerView: 1,
                slidesPerGroup: 1,
                slidesPerGroupSkip: 0,
                slidesPerGroupAuto: !1,
                centeredSlides: !1,
                centeredSlidesBounds: !1,
                slidesOffsetBefore: 0,
                slidesOffsetAfter: 0,
                normalizeSlideIndex: !0,
                centerInsufficientSlides: !1,
                watchOverflow: !0,
                roundLengths: !1,
                touchRatio: 1,
                touchAngle: 45,
                simulateTouch: !0,
                shortSwipes: !0,
                longSwipes: !0,
                longSwipesRatio: .5,
                longSwipesMs: 300,
                followFinger: !0,
                allowTouchMove: !0,
                threshold: 0,
                touchMoveStopPropagation: !1,
                touchStartPreventDefault: !0,
                touchStartForcePreventDefault: !1,
                touchReleaseOnEdges: !1,
                uniqueNavElements: !0,
                resistance: !0,
                resistanceRatio: .85,
                watchSlidesProgress: !1,
                grabCursor: !1,
                preventClicks: !0,
                preventClicksPropagation: !0,
                slideToClickedSlide: !1,
                preloadImages: !0,
                updateOnImagesReady: !0,
                loop: !1,
                loopAdditionalSlides: 0,
                loopedSlides: null,
                loopedSlidesLimit: !0,
                loopFillGroupWithBlank: !1,
                loopPreventsSlide: !0,
                rewind: !1,
                allowSlidePrev: !0,
                allowSlideNext: !0,
                swipeHandler: null,
                noSwiping: !0,
                noSwipingClass: "swiper-no-swiping",
                noSwipingSelector: null,
                passiveListeners: !0,
                maxBackfaceHiddenSlides: 10,
                containerModifierClass: "swiper-",
                slideClass: "swiper-slide",
                slideBlankClass: "swiper-slide-invisible-blank",
                slideActiveClass: "swiper-slide-active",
                slideDuplicateActiveClass: "swiper-slide-duplicate-active",
                slideVisibleClass: "swiper-slide-visible",
                slideDuplicateClass: "swiper-slide-duplicate",
                slideNextClass: "swiper-slide-next",
                slideDuplicateNextClass: "swiper-slide-duplicate-next",
                slidePrevClass: "swiper-slide-prev",
                slideDuplicatePrevClass: "swiper-slide-duplicate-prev",
                wrapperClass: "swiper-wrapper",
                runCallbacksOnInit: !0,
                _emitClasses: !1
            };

            function re(e, t) {
                return function(a) {
                    void 0 === a && (a = {});
                    var n = Object.keys(a)[0],
                        i = a[n];
                    "object" === typeof i && null !== i ? (["navigation", "pagination", "scrollbar"].indexOf(n) >= 0 && !0 === e[n] && (e[n] = {
                        auto: !0
                    }), n in e && "enabled" in i ? (!0 === e[n] && (e[n] = {
                        enabled: !0
                    }), "object" !== typeof e[n] || "enabled" in e[n] || (e[n].enabled = !0), e[n] || (e[n] = {
                        enabled: !1
                    }), z(t, a)) : z(t, a)) : z(t, a)
                }
            }
            var se = {
                    eventsEmitter: $,
                    update: j,
                    translate: F,
                    transition: {
                        setTransition: function(e, t) {
                            var a = this;
                            a.params.cssMode || a.$wrapperEl.transition(e), a.emit("setTransition", e, t)
                        },
                        transitionStart: function(e, t) {
                            void 0 === e && (e = !0);
                            var a = this,
                                n = a.params;
                            n.cssMode || (n.autoHeight && a.updateAutoHeight(), H({
                                swiper: a,
                                runCallbacks: e,
                                direction: t,
                                step: "Start"
                            }))
                        },
                        transitionEnd: function(e, t) {
                            void 0 === e && (e = !0);
                            var a = this,
                                n = a.params;
                            a.animating = !1, n.cssMode || (a.setTransition(0), H({
                                swiper: a,
                                runCallbacks: e,
                                direction: t,
                                step: "End"
                            }))
                        }
                    },
                    slide: R,
                    loop: V,
                    grabCursor: {
                        setGrabCursor: function(e) {
                            var t = this;
                            if (!(t.support.touch || !t.params.simulateTouch || t.params.watchOverflow && t.isLocked || t.params.cssMode)) {
                                var a = "container" === t.params.touchEventsTarget ? t.el : t.wrapperEl;
                                a.style.cursor = "move", a.style.cursor = e ? "grabbing" : "grab"
                            }
                        },
                        unsetGrabCursor: function() {
                            var e = this;
                            e.support.touch || e.params.watchOverflow && e.isLocked || e.params.cssMode || (e["container" === e.params.touchEventsTarget ? "el" : "wrapperEl"].style.cursor = "")
                        }
                    },
                    events: ee,
                    breakpoints: ae,
                    checkOverflow: {
                        checkOverflow: function() {
                            var e = this,
                                t = e.isLocked,
                                a = e.params,
                                n = a.slidesOffsetBefore;
                            if (n) {
                                var i = e.slides.length - 1,
                                    r = e.slidesGrid[i] + e.slidesSizesGrid[i] + 2 * n;
                                e.isLocked = e.size > r
                            } else e.isLocked = 1 === e.snapGrid.length;
                            !0 === a.allowSlideNext && (e.allowSlideNext = !e.isLocked), !0 === a.allowSlidePrev && (e.allowSlidePrev = !e.isLocked), t && t !== e.isLocked && (e.isEnd = !1), t !== e.isLocked && e.emit(e.isLocked ? "lock" : "unlock")
                        }
                    },
                    classes: ne,
                    images: {
                        loadImage: function(e, t, a, n, i, r) {
                            var s, o = u();

                            function l() {
                                r && r()
                            }
                            M(e).parent("picture")[0] || e.complete && i ? l() : t ? ((s = new o.Image).onload = l, s.onerror = l, n && (s.sizes = n), a && (s.srcset = a), t && (s.src = t)) : l()
                        },
                        preloadImages: function() {
                            var e = this;

                            function t() {
                                "undefined" !== typeof e && null !== e && e && !e.destroyed && (void 0 !== e.imagesLoaded && (e.imagesLoaded += 1), e.imagesLoaded === e.imagesToLoad.length && (e.params.updateOnImagesReady && e.update(), e.emit("imagesReady")))
                            }
                            e.imagesToLoad = e.$el.find("img");
                            for (var a = 0; a < e.imagesToLoad.length; a += 1) {
                                var n = e.imagesToLoad[a];
                                e.loadImage(n, n.currentSrc || n.getAttribute("src"), n.srcset || n.getAttribute("srcset"), n.sizes || n.getAttribute("sizes"), !0, t)
                            }
                        }
                    }
                },
                oe = {},
                le = function() {
                    function e() {
                        var t, a;
                        (0, i.Z)(this, e);
                        for (var r = arguments.length, s = new Array(r), o = 0; o < r; o++) s[o] = arguments[o];
                        if (1 === s.length && s[0].constructor && "Object" === Object.prototype.toString.call(s[0]).slice(8, -1) ? a = s[0] : (t = s[0], a = s[1]), a || (a = {}), a = z({}, a), t && !a.el && (a.el = t), a.el && M(a.el).length > 1) {
                            var l = [];
                            return M(a.el).each((function(t) {
                                var n = z({}, a, {
                                    el: t
                                });
                                l.push(new e(n))
                            })), l
                        }
                        var d, c = this;
                        (c.__swiper__ = !0, c.support = B(), c.device = G({
                            userAgent: a.userAgent
                        }), c.browser = N(), c.eventsListeners = {}, c.eventsAnyListeners = [], c.modules = (0, n.Z)(c.__modules__), a.modules && Array.isArray(a.modules)) && (d = c.modules).push.apply(d, (0, n.Z)(a.modules));
                        var u = {};
                        c.modules.forEach((function(e) {
                            e({
                                swiper: c,
                                extendParams: re(a, u),
                                on: c.on.bind(c),
                                once: c.once.bind(c),
                                off: c.off.bind(c),
                                emit: c.emit.bind(c)
                            })
                        }));
                        var p = z({}, ie, u);
                        return c.params = z({}, p, oe, a), c.originalParams = z({}, c.params), c.passedParams = z({}, a), c.params && c.params.on && Object.keys(c.params.on).forEach((function(e) {
                            c.on(e, c.params.on[e])
                        })), c.params && c.params.onAny && c.onAny(c.params.onAny), c.$ = M, Object.assign(c, {
                            enabled: c.params.enabled,
                            el: t,
                            classNames: [],
                            slides: M(),
                            slidesGrid: [],
                            snapGrid: [],
                            slidesSizesGrid: [],
                            isHorizontal: function() {
                                return "horizontal" === c.params.direction
                            },
                            isVertical: function() {
                                return "vertical" === c.params.direction
                            },
                            activeIndex: 0,
                            realIndex: 0,
                            isBeginning: !0,
                            isEnd: !1,
                            translate: 0,
                            previousTranslate: 0,
                            progress: 0,
                            velocity: 0,
                            animating: !1,
                            allowSlideNext: c.params.allowSlideNext,
                            allowSlidePrev: c.params.allowSlidePrev,
                            touchEvents: function() {
                                var e = ["touchstart", "touchmove", "touchend", "touchcancel"],
                                    t = ["pointerdown", "pointermove", "pointerup"];
                                return c.touchEventsTouch = {
                                    start: e[0],
                                    move: e[1],
                                    end: e[2],
                                    cancel: e[3]
                                }, c.touchEventsDesktop = {
                                    start: t[0],
                                    move: t[1],
                                    end: t[2]
                                }, c.support.touch || !c.params.simulateTouch ? c.touchEventsTouch : c.touchEventsDesktop
                            }(),
                            touchEventsData: {
                                isTouched: void 0,
                                isMoved: void 0,
                                allowTouchCallbacks: void 0,
                                touchStartTime: void 0,
                                isScrolling: void 0,
                                currentTranslate: void 0,
                                startTranslate: void 0,
                                allowThresholdMove: void 0,
                                focusableElements: c.params.focusableElements,
                                lastClickTime: O(),
                                clickTimeout: void 0,
                                velocities: [],
                                allowMomentumBounce: void 0,
                                isTouchEvent: void 0,
                                startMoving: void 0
                            },
                            allowClick: !0,
                            allowTouchMove: c.params.allowTouchMove,
                            touches: {
                                startX: 0,
                                startY: 0,
                                currentX: 0,
                                currentY: 0,
                                diff: 0
                            },
                            imagesToLoad: [],
                            imagesLoaded: 0
                        }), c.emit("_swiper"), c.params.init && c.init(), c
                    }
                    return (0, r.Z)(e, [{
                        key: "enable",
                        value: function() {
                            var e = this;
                            e.enabled || (e.enabled = !0, e.params.grabCursor && e.setGrabCursor(), e.emit("enable"))
                        }
                    }, {
                        key: "disable",
                        value: function() {
                            var e = this;
                            e.enabled && (e.enabled = !1, e.params.grabCursor && e.unsetGrabCursor(), e.emit("disable"))
                        }
                    }, {
                        key: "setProgress",
                        value: function(e, t) {
                            var a = this;
                            e = Math.min(Math.max(e, 0), 1);
                            var n = a.minTranslate(),
                                i = (a.maxTranslate() - n) * e + n;
                            a.translateTo(i, "undefined" === typeof t ? 0 : t), a.updateActiveIndex(), a.updateSlidesClasses()
                        }
                    }, {
                        key: "emitContainerClasses",
                        value: function() {
                            var e = this;
                            if (e.params._emitClasses && e.el) {
                                var t = e.el.className.split(" ").filter((function(t) {
                                    return 0 === t.indexOf("swiper") || 0 === t.indexOf(e.params.containerModifierClass)
                                }));
                                e.emit("_containerClasses", t.join(" "))
                            }
                        }
                    }, {
                        key: "getSlideClasses",
                        value: function(e) {
                            var t = this;
                            return t.destroyed ? "" : e.className.split(" ").filter((function(e) {
                                return 0 === e.indexOf("swiper-slide") || 0 === e.indexOf(t.params.slideClass)
                            })).join(" ")
                        }
                    }, {
                        key: "emitSlidesClasses",
                        value: function() {
                            var e = this;
                            if (e.params._emitClasses && e.el) {
                                var t = [];
                                e.slides.each((function(a) {
                                    var n = e.getSlideClasses(a);
                                    t.push({
                                        slideEl: a,
                                        classNames: n
                                    }), e.emit("_slideClass", a, n)
                                })), e.emit("_slideClasses", t)
                            }
                        }
                    }, {
                        key: "slidesPerViewDynamic",
                        value: function(e, t) {
                            void 0 === e && (e = "current"), void 0 === t && (t = !1);
                            var a = this,
                                n = a.params,
                                i = a.slides,
                                r = a.slidesGrid,
                                s = a.slidesSizesGrid,
                                o = a.size,
                                l = a.activeIndex,
                                d = 1;
                            if (n.centeredSlides) {
                                for (var c, u = i[l].swiperSlideSize, p = l + 1; p < i.length; p += 1) i[p] && !c && (d += 1, (u += i[p].swiperSlideSize) > o && (c = !0));
                                for (var f = l - 1; f >= 0; f -= 1) i[f] && !c && (d += 1, (u += i[f].swiperSlideSize) > o && (c = !0))
                            } else if ("current" === e)
                                for (var v = l + 1; v < i.length; v += 1) {
                                    (t ? r[v] + s[v] - r[l] < o : r[v] - r[l] < o) && (d += 1)
                                } else
                                    for (var h = l - 1; h >= 0; h -= 1) {
                                        r[l] - r[h] < o && (d += 1)
                                    }
                            return d
                        }
                    }, {
                        key: "update",
                        value: function() {
                            var e = this;
                            if (e && !e.destroyed) {
                                var t = e.snapGrid,
                                    a = e.params;
                                a.breakpoints && e.setBreakpoint(), e.updateSize(), e.updateSlides(), e.updateProgress(), e.updateSlidesClasses(), e.params.freeMode && e.params.freeMode.enabled ? (n(), e.params.autoHeight && e.updateAutoHeight()) : (("auto" === e.params.slidesPerView || e.params.slidesPerView > 1) && e.isEnd && !e.params.centeredSlides ? e.slideTo(e.slides.length - 1, 0, !1, !0) : e.slideTo(e.activeIndex, 0, !1, !0)) || n(), a.watchOverflow && t !== e.snapGrid && e.checkOverflow(), e.emit("update")
                            }

                            function n() {
                                var t = e.rtlTranslate ? -1 * e.translate : e.translate,
                                    a = Math.min(Math.max(t, e.maxTranslate()), e.minTranslate());
                                e.setTranslate(a), e.updateActiveIndex(), e.updateSlidesClasses()
                            }
                        }
                    }, {
                        key: "changeDirection",
                        value: function(e, t) {
                            void 0 === t && (t = !0);
                            var a = this,
                                n = a.params.direction;
                            return e || (e = "horizontal" === n ? "vertical" : "horizontal"), e === n || "horizontal" !== e && "vertical" !== e || (a.$el.removeClass("".concat(a.params.containerModifierClass).concat(n)).addClass("".concat(a.params.containerModifierClass).concat(e)), a.emitContainerClasses(), a.params.direction = e, a.slides.each((function(t) {
                                "vertical" === e ? t.style.width = "" : t.style.height = ""
                            })), a.emit("changeDirection"), t && a.update()), a
                        }
                    }, {
                        key: "changeLanguageDirection",
                        value: function(e) {
                            var t = this;
                            t.rtl && "rtl" === e || !t.rtl && "ltr" === e || (t.rtl = "rtl" === e, t.rtlTranslate = "horizontal" === t.params.direction && t.rtl, t.rtl ? (t.$el.addClass("".concat(t.params.containerModifierClass, "rtl")), t.el.dir = "rtl") : (t.$el.removeClass("".concat(t.params.containerModifierClass, "rtl")), t.el.dir = "ltr"), t.update())
                        }
                    }, {
                        key: "mount",
                        value: function(e) {
                            var t = this;
                            if (t.mounted) return !0;
                            var a = M(e || t.params.el);
                            if (!(e = a[0])) return !1;
                            e.swiper = t;
                            var n = function() {
                                    return ".".concat((t.params.wrapperClass || "").trim().split(" ").join("."))
                                },
                                i = function() {
                                    if (e && e.shadowRoot && e.shadowRoot.querySelector) {
                                        var t = M(e.shadowRoot.querySelector(n()));
                                        return t.children = function(e) {
                                            return a.children(e)
                                        }, t
                                    }
                                    return a.children ? a.children(n()) : M(a).children(n())
                                }();
                            if (0 === i.length && t.params.createElements) {
                                var r = d().createElement("div");
                                i = M(r), r.className = t.params.wrapperClass, a.append(r), a.children(".".concat(t.params.slideClass)).each((function(e) {
                                    i.append(e)
                                }))
                            }
                            return Object.assign(t, {
                                $el: a,
                                el: e,
                                $wrapperEl: i,
                                wrapperEl: i[0],
                                mounted: !0,
                                rtl: "rtl" === e.dir.toLowerCase() || "rtl" === a.css("direction"),
                                rtlTranslate: "horizontal" === t.params.direction && ("rtl" === e.dir.toLowerCase() || "rtl" === a.css("direction")),
                                wrongRTL: "-webkit-box" === i.css("display")
                            }), !0
                        }
                    }, {
                        key: "init",
                        value: function(e) {
                            var t = this;
                            return t.initialized || !1 === t.mount(e) || (t.emit("beforeInit"), t.params.breakpoints && t.setBreakpoint(), t.addClasses(), t.params.loop && t.loopCreate(), t.updateSize(), t.updateSlides(), t.params.watchOverflow && t.checkOverflow(), t.params.grabCursor && t.enabled && t.setGrabCursor(), t.params.preloadImages && t.preloadImages(), t.params.loop ? t.slideTo(t.params.initialSlide + t.loopedSlides, 0, t.params.runCallbacksOnInit, !1, !0) : t.slideTo(t.params.initialSlide, 0, t.params.runCallbacksOnInit, !1, !0), t.attachEvents(), t.initialized = !0, t.emit("init"), t.emit("afterInit")), t
                        }
                    }, {
                        key: "destroy",
                        value: function(e, t) {
                            void 0 === e && (e = !0), void 0 === t && (t = !0);
                            var a = this,
                                n = a.params,
                                i = a.$el,
                                r = a.$wrapperEl,
                                s = a.slides;
                            return "undefined" === typeof a.params || a.destroyed || (a.emit("beforeDestroy"), a.initialized = !1, a.detachEvents(), n.loop && a.loopDestroy(), t && (a.removeClasses(), i.removeAttr("style"), r.removeAttr("style"), s && s.length && s.removeClass([n.slideVisibleClass, n.slideActiveClass, n.slideNextClass, n.slidePrevClass].join(" ")).removeAttr("style").removeAttr("data-swiper-slide-index")), a.emit("destroy"), Object.keys(a.eventsListeners).forEach((function(e) {
                                a.off(e)
                            })), !1 !== e && (a.$el[0].swiper = null, function(e) {
                                var t = e;
                                Object.keys(t).forEach((function(e) {
                                    try {
                                        t[e] = null
                                    } catch (a) {}
                                    try {
                                        delete t[e]
                                    } catch (a) {}
                                }))
                            }(a)), a.destroyed = !0), null
                        }
                    }], [{
                        key: "extendDefaults",
                        value: function(e) {
                            z(oe, e)
                        }
                    }, {
                        key: "extendedDefaults",
                        get: function() {
                            return oe
                        }
                    }, {
                        key: "defaults",
                        get: function() {
                            return ie
                        }
                    }, {
                        key: "installModule",
                        value: function(t) {
                            e.prototype.__modules__ || (e.prototype.__modules__ = []);
                            var a = e.prototype.__modules__;
                            "function" === typeof t && a.indexOf(t) < 0 && a.push(t)
                        }
                    }, {
                        key: "use",
                        value: function(t) {
                            return Array.isArray(t) ? (t.forEach((function(t) {
                                return e.installModule(t)
                            })), e) : (e.installModule(t), e)
                        }
                    }]), e
                }();
            Object.keys(se).forEach((function(e) {
                Object.keys(se[e]).forEach((function(t) {
                    le.prototype[t] = se[e][t]
                }))
            })), le.use([function(e) {
                var t = e.swiper,
                    a = e.on,
                    n = e.emit,
                    i = u(),
                    r = null,
                    s = null,
                    o = function() {
                        t && !t.destroyed && t.initialized && (n("beforeResize"), n("resize"))
                    },
                    l = function() {
                        t && !t.destroyed && t.initialized && n("orientationchange")
                    };
                a("init", (function() {
                    t.params.resizeObserver && "undefined" !== typeof i.ResizeObserver ? t && !t.destroyed && t.initialized && (r = new ResizeObserver((function(e) {
                        s = i.requestAnimationFrame((function() {
                            var a = t.width,
                                n = t.height,
                                i = a,
                                r = n;
                            e.forEach((function(e) {
                                var a = e.contentBoxSize,
                                    n = e.contentRect,
                                    s = e.target;
                                s && s !== t.el || (i = n ? n.width : (a[0] || a).inlineSize, r = n ? n.height : (a[0] || a).blockSize)
                            })), i === a && r === n || o()
                        }))
                    })), r.observe(t.el)) : (i.addEventListener("resize", o), i.addEventListener("orientationchange", l))
                })), a("destroy", (function() {
                    s && i.cancelAnimationFrame(s), r && r.unobserve && t.el && (r.unobserve(t.el), r = null), i.removeEventListener("resize", o), i.removeEventListener("orientationchange", l)
                }))
            }, function(e) {
                var t = e.swiper,
                    a = e.extendParams,
                    n = e.on,
                    i = e.emit,
                    r = [],
                    s = u(),
                    o = function(e, t) {
                        void 0 === t && (t = {});
                        var a = new(s.MutationObserver || s.WebkitMutationObserver)((function(e) {
                            if (1 !== e.length) {
                                var t = function() {
                                    i("observerUpdate", e[0])
                                };
                                s.requestAnimationFrame ? s.requestAnimationFrame(t) : s.setTimeout(t, 0)
                            } else i("observerUpdate", e[0])
                        }));
                        a.observe(e, {
                            attributes: "undefined" === typeof t.attributes || t.attributes,
                            childList: "undefined" === typeof t.childList || t.childList,
                            characterData: "undefined" === typeof t.characterData || t.characterData
                        }), r.push(a)
                    };
                a({
                    observer: !1,
                    observeParents: !1,
                    observeSlideChildren: !1
                }), n("init", (function() {
                    if (t.params.observer) {
                        if (t.params.observeParents)
                            for (var e = t.$el.parents(), a = 0; a < e.length; a += 1) o(e[a]);
                        o(t.$el[0], {
                            childList: t.params.observeSlideChildren
                        }), o(t.$wrapperEl[0], {
                            attributes: !1
                        })
                    }
                })), n("destroy", (function() {
                    r.forEach((function(e) {
                        e.disconnect()
                    })), r.splice(0, r.length)
                }))
            }]);
            var de = le;

            function ce(e) {
                var t, a = e.swiper,
                    n = e.extendParams,
                    i = e.on,
                    r = e.emit,
                    s = u();
                n({
                    mousewheel: {
                        enabled: !1,
                        releaseOnEdges: !1,
                        invert: !1,
                        forceToAxis: !1,
                        sensitivity: 1,
                        eventsTarget: "container",
                        thresholdDelta: null,
                        thresholdTime: null
                    }
                }), a.mousewheel = {
                    enabled: !1
                };
                var o, l = O(),
                    d = [];

                function c() {
                    a.enabled && (a.mouseEntered = !0)
                }

                function p() {
                    a.enabled && (a.mouseEntered = !1)
                }

                function f(e) {
                    return !(a.params.mousewheel.thresholdDelta && e.delta < a.params.mousewheel.thresholdDelta) && (!(a.params.mousewheel.thresholdTime && O() - l < a.params.mousewheel.thresholdTime) && (e.delta >= 6 && O() - l < 60 || (e.direction < 0 ? a.isEnd && !a.params.loop || a.animating || (a.slideNext(), r("scroll", e.raw)) : a.isBeginning && !a.params.loop || a.animating || (a.slidePrev(), r("scroll", e.raw)), l = (new s.Date).getTime(), !1)))
                }

                function v(e) {
                    var n = e;
                    if (a.enabled) {
                        var i = a.params.mousewheel;
                        a.params.cssMode && n.preventDefault();
                        var s = a.$el;
                        if ("container" !== a.params.mousewheel.eventsTarget && (s = M(a.params.mousewheel.eventsTarget)), !a.mouseEntered && !s[0].contains(n.target) && !i.releaseOnEdges) return !0;
                        n.originalEvent && (n = n.originalEvent);
                        var l = 0,
                            c = a.rtlTranslate ? -1 : 1,
                            u = function(e) {
                                var t = 0,
                                    a = 0,
                                    n = 0,
                                    i = 0;
                                return "detail" in e && (a = e.detail), "wheelDelta" in e && (a = -e.wheelDelta / 120), "wheelDeltaY" in e && (a = -e.wheelDeltaY / 120), "wheelDeltaX" in e && (t = -e.wheelDeltaX / 120), "axis" in e && e.axis === e.HORIZONTAL_AXIS && (t = a, a = 0), n = 10 * t, i = 10 * a, "deltaY" in e && (i = e.deltaY), "deltaX" in e && (n = e.deltaX), e.shiftKey && !n && (n = i, i = 0), (n || i) && e.deltaMode && (1 === e.deltaMode ? (n *= 40, i *= 40) : (n *= 800, i *= 800)), n && !t && (t = n < 1 ? -1 : 1), i && !a && (a = i < 1 ? -1 : 1), {
                                    spinX: t,
                                    spinY: a,
                                    pixelX: n,
                                    pixelY: i
                                }
                            }(n);
                        if (i.forceToAxis)
                            if (a.isHorizontal()) {
                                if (!(Math.abs(u.pixelX) > Math.abs(u.pixelY))) return !0;
                                l = -u.pixelX * c
                            } else {
                                if (!(Math.abs(u.pixelY) > Math.abs(u.pixelX))) return !0;
                                l = -u.pixelY
                            }
                        else l = Math.abs(u.pixelX) > Math.abs(u.pixelY) ? -u.pixelX * c : -u.pixelY;
                        if (0 === l) return !0;
                        i.invert && (l = -l);
                        var p = a.getTranslate() + l * i.sensitivity;
                        if (p >= a.minTranslate() && (p = a.minTranslate()), p <= a.maxTranslate() && (p = a.maxTranslate()), (!!a.params.loop || !(p === a.minTranslate() || p === a.maxTranslate())) && a.params.nested && n.stopPropagation(), a.params.freeMode && a.params.freeMode.enabled) {
                            var v = {
                                    time: O(),
                                    delta: Math.abs(l),
                                    direction: Math.sign(l)
                                },
                                h = o && v.time < o.time + 500 && v.delta <= o.delta && v.direction === o.direction;
                            if (!h) {
                                o = void 0, a.params.loop && a.loopFix();
                                var m = a.getTranslate() + l * i.sensitivity,
                                    g = a.isBeginning,
                                    w = a.isEnd;
                                if (m >= a.minTranslate() && (m = a.minTranslate()), m <= a.maxTranslate() && (m = a.maxTranslate()), a.setTransition(0), a.setTranslate(m), a.updateProgress(), a.updateActiveIndex(), a.updateSlidesClasses(), (!g && a.isBeginning || !w && a.isEnd) && a.updateSlidesClasses(), a.params.freeMode.sticky) {
                                    clearTimeout(t), t = void 0, d.length >= 15 && d.shift();
                                    var b = d.length ? d[d.length - 1] : void 0,
                                        y = d[0];
                                    if (d.push(v), b && (v.delta > b.delta || v.direction !== b.direction)) d.splice(0);
                                    else if (d.length >= 15 && v.time - y.time < 500 && y.delta - v.delta >= 1 && v.delta <= 6) {
                                        var T = l > 0 ? .8 : .2;
                                        o = v, d.splice(0), t = P((function() {
                                            a.slideToClosest(a.params.speed, !0, void 0, T)
                                        }), 0)
                                    }
                                    t || (t = P((function() {
                                        o = v, d.splice(0), a.slideToClosest(a.params.speed, !0, void 0, .5)
                                    }), 500))
                                }
                                if (h || r("scroll", n), a.params.autoplay && a.params.autoplayDisableOnInteraction && a.autoplay.stop(), m === a.minTranslate() || m === a.maxTranslate()) return !0
                            }
                        } else {
                            var C = {
                                time: O(),
                                delta: Math.abs(l),
                                direction: Math.sign(l),
                                raw: e
                            };
                            d.length >= 2 && d.shift();
                            var S = d.length ? d[d.length - 1] : void 0;
                            if (d.push(C), S ? (C.direction !== S.direction || C.delta > S.delta || C.time > S.time + 150) && f(C) : f(C), function(e) {
                                    var t = a.params.mousewheel;
                                    if (e.direction < 0) {
                                        if (a.isEnd && !a.params.loop && t.releaseOnEdges) return !0
                                    } else if (a.isBeginning && !a.params.loop && t.releaseOnEdges) return !0;
                                    return !1
                                }(C)) return !0
                        }
                        return n.preventDefault ? n.preventDefault() : n.returnValue = !1, !1
                    }
                }

                function h(e) {
                    var t = a.$el;
                    "container" !== a.params.mousewheel.eventsTarget && (t = M(a.params.mousewheel.eventsTarget)), t[e]("mouseenter", c), t[e]("mouseleave", p), t[e]("wheel", v)
                }

                function m() {
                    return a.params.cssMode ? (a.wrapperEl.removeEventListener("wheel", v), !0) : !a.mousewheel.enabled && (h("on"), a.mousewheel.enabled = !0, !0)
                }

                function g() {
                    return a.params.cssMode ? (a.wrapperEl.addEventListener(event, v), !0) : !!a.mousewheel.enabled && (h("off"), a.mousewheel.enabled = !1, !0)
                }
                i("init", (function() {
                    !a.params.mousewheel.enabled && a.params.cssMode && g(), a.params.mousewheel.enabled && m()
                })), i("destroy", (function() {
                    a.params.cssMode && m(), a.mousewheel.enabled && g()
                })), Object.assign(a.mousewheel, {
                    enable: m,
                    disable: g
                })
            }

            function ue(e, t, a, n) {
                var i = d();
                return e.params.createElements && Object.keys(n).forEach((function(r) {
                    if (!a[r] && !0 === a.auto) {
                        var s = e.$el.children(".".concat(n[r]))[0];
                        s || ((s = i.createElement("div")).className = n[r], e.$el.append(s)), a[r] = s, t[r] = s
                    }
                })), a
            }

            function pe(e) {
                var t = e.swiper,
                    a = e.extendParams,
                    n = e.on,
                    i = e.emit;

                function r(e) {
                    var a;
                    return e && (a = M(e), t.params.uniqueNavElements && "string" === typeof e && a.length > 1 && 1 === t.$el.find(e).length && (a = t.$el.find(e))), a
                }

                function s(e, a) {
                    var n = t.params.navigation;
                    e && e.length > 0 && (e[a ? "addClass" : "removeClass"](n.disabledClass), e[0] && "BUTTON" === e[0].tagName && (e[0].disabled = a), t.params.watchOverflow && t.enabled && e[t.isLocked ? "addClass" : "removeClass"](n.lockClass))
                }

                function o() {
                    if (!t.params.loop) {
                        var e = t.navigation,
                            a = e.$nextEl;
                        s(e.$prevEl, t.isBeginning && !t.params.rewind), s(a, t.isEnd && !t.params.rewind)
                    }
                }

                function l(e) {
                    e.preventDefault(), (!t.isBeginning || t.params.loop || t.params.rewind) && (t.slidePrev(), i("navigationPrev"))
                }

                function d(e) {
                    e.preventDefault(), (!t.isEnd || t.params.loop || t.params.rewind) && (t.slideNext(), i("navigationNext"))
                }

                function c() {
                    var e = t.params.navigation;
                    if (t.params.navigation = ue(t, t.originalParams.navigation, t.params.navigation, {
                            nextEl: "swiper-button-next",
                            prevEl: "swiper-button-prev"
                        }), e.nextEl || e.prevEl) {
                        var a = r(e.nextEl),
                            n = r(e.prevEl);
                        a && a.length > 0 && a.on("click", d), n && n.length > 0 && n.on("click", l), Object.assign(t.navigation, {
                            $nextEl: a,
                            nextEl: a && a[0],
                            $prevEl: n,
                            prevEl: n && n[0]
                        }), t.enabled || (a && a.addClass(e.lockClass), n && n.addClass(e.lockClass))
                    }
                }

                function u() {
                    var e = t.navigation,
                        a = e.$nextEl,
                        n = e.$prevEl;
                    a && a.length && (a.off("click", d), a.removeClass(t.params.navigation.disabledClass)), n && n.length && (n.off("click", l), n.removeClass(t.params.navigation.disabledClass))
                }
                a({
                    navigation: {
                        nextEl: null,
                        prevEl: null,
                        hideOnClick: !1,
                        disabledClass: "swiper-button-disabled",
                        hiddenClass: "swiper-button-hidden",
                        lockClass: "swiper-button-lock",
                        navigationDisabledClass: "swiper-navigation-disabled"
                    }
                }), t.navigation = {
                    nextEl: null,
                    $nextEl: null,
                    prevEl: null,
                    $prevEl: null
                }, n("init", (function() {
                    !1 === t.params.navigation.enabled ? p() : (c(), o())
                })), n("toEdge fromEdge lock unlock", (function() {
                    o()
                })), n("destroy", (function() {
                    u()
                })), n("enable disable", (function() {
                    var e = t.navigation,
                        a = e.$nextEl,
                        n = e.$prevEl;
                    a && a[t.enabled ? "removeClass" : "addClass"](t.params.navigation.lockClass), n && n[t.enabled ? "removeClass" : "addClass"](t.params.navigation.lockClass)
                })), n("click", (function(e, a) {
                    var n = t.navigation,
                        r = n.$nextEl,
                        s = n.$prevEl,
                        o = a.target;
                    if (t.params.navigation.hideOnClick && !M(o).is(s) && !M(o).is(r)) {
                        if (t.pagination && t.params.pagination && t.params.pagination.clickable && (t.pagination.el === o || t.pagination.el.contains(o))) return;
                        var l;
                        r ? l = r.hasClass(t.params.navigation.hiddenClass) : s && (l = s.hasClass(t.params.navigation.hiddenClass)), i(!0 === l ? "navigationShow" : "navigationHide"), r && r.toggleClass(t.params.navigation.hiddenClass), s && s.toggleClass(t.params.navigation.hiddenClass)
                    }
                }));
                var p = function() {
                    t.$el.addClass(t.params.navigation.navigationDisabledClass), u()
                };
                Object.assign(t.navigation, {
                    enable: function() {
                        t.$el.removeClass(t.params.navigation.navigationDisabledClass), c(), o()
                    },
                    disable: p,
                    update: o,
                    init: c,
                    destroy: u
                })
            }

            function fe(e) {
                return void 0 === e && (e = ""), ".".concat(e.trim().replace(/([\.:!\/])/g, "\\$1").replace(/ /g, "."))
            }

            function ve(e) {
                var t, a = e.swiper,
                    n = e.extendParams,
                    i = e.on,
                    r = e.emit,
                    s = "swiper-pagination";
                n({
                    pagination: {
                        el: null,
                        bulletElement: "span",
                        clickable: !1,
                        hideOnClick: !1,
                        renderBullet: null,
                        renderProgressbar: null,
                        renderFraction: null,
                        renderCustom: null,
                        progressbarOpposite: !1,
                        type: "bullets",
                        dynamicBullets: !1,
                        dynamicMainBullets: 1,
                        formatFractionCurrent: function(e) {
                            return e
                        },
                        formatFractionTotal: function(e) {
                            return e
                        },
                        bulletClass: "".concat(s, "-bullet"),
                        bulletActiveClass: "".concat(s, "-bullet-active"),
                        modifierClass: "".concat(s, "-"),
                        currentClass: "".concat(s, "-current"),
                        totalClass: "".concat(s, "-total"),
                        hiddenClass: "".concat(s, "-hidden"),
                        progressbarFillClass: "".concat(s, "-progressbar-fill"),
                        progressbarOppositeClass: "".concat(s, "-progressbar-opposite"),
                        clickableClass: "".concat(s, "-clickable"),
                        lockClass: "".concat(s, "-lock"),
                        horizontalClass: "".concat(s, "-horizontal"),
                        verticalClass: "".concat(s, "-vertical"),
                        paginationDisabledClass: "".concat(s, "-disabled")
                    }
                }), a.pagination = {
                    el: null,
                    $el: null,
                    bullets: []
                };
                var o = 0;

                function l() {
                    return !a.params.pagination.el || !a.pagination.el || !a.pagination.$el || 0 === a.pagination.$el.length
                }

                function d(e, t) {
                    var n = a.params.pagination.bulletActiveClass;
                    e[t]().addClass("".concat(n, "-").concat(t))[t]().addClass("".concat(n, "-").concat(t, "-").concat(t))
                }

                function c() {
                    var e = a.rtl,
                        n = a.params.pagination;
                    if (!l()) {
                        var i, s = a.virtual && a.params.virtual.enabled ? a.virtual.slides.length : a.slides.length,
                            c = a.pagination.$el,
                            u = a.params.loop ? Math.ceil((s - 2 * a.loopedSlides) / a.params.slidesPerGroup) : a.snapGrid.length;
                        if (a.params.loop ? ((i = Math.ceil((a.activeIndex - a.loopedSlides) / a.params.slidesPerGroup)) > s - 1 - 2 * a.loopedSlides && (i -= s - 2 * a.loopedSlides), i > u - 1 && (i -= u), i < 0 && "bullets" !== a.params.paginationType && (i = u + i)) : i = "undefined" !== typeof a.snapIndex ? a.snapIndex : a.activeIndex || 0, "bullets" === n.type && a.pagination.bullets && a.pagination.bullets.length > 0) {
                            var p, f, v, h = a.pagination.bullets;
                            if (n.dynamicBullets && (t = h.eq(0)[a.isHorizontal() ? "outerWidth" : "outerHeight"](!0), c.css(a.isHorizontal() ? "width" : "height", "".concat(t * (n.dynamicMainBullets + 4), "px")), n.dynamicMainBullets > 1 && void 0 !== a.previousIndex && ((o += i - (a.previousIndex - a.loopedSlides || 0)) > n.dynamicMainBullets - 1 ? o = n.dynamicMainBullets - 1 : o < 0 && (o = 0)), p = Math.max(i - o, 0), v = ((f = p + (Math.min(h.length, n.dynamicMainBullets) - 1)) + p) / 2), h.removeClass(["", "-next", "-next-next", "-prev", "-prev-prev", "-main"].map((function(e) {
                                    return "".concat(n.bulletActiveClass).concat(e)
                                })).join(" ")), c.length > 1) h.each((function(e) {
                                var t = M(e),
                                    a = t.index();
                                a === i && t.addClass(n.bulletActiveClass), n.dynamicBullets && (a >= p && a <= f && t.addClass("".concat(n.bulletActiveClass, "-main")), a === p && d(t, "prev"), a === f && d(t, "next"))
                            }));
                            else {
                                var m = h.eq(i),
                                    g = m.index();
                                if (m.addClass(n.bulletActiveClass), n.dynamicBullets) {
                                    for (var w = h.eq(p), b = h.eq(f), y = p; y <= f; y += 1) h.eq(y).addClass("".concat(n.bulletActiveClass, "-main"));
                                    if (a.params.loop)
                                        if (g >= h.length) {
                                            for (var T = n.dynamicMainBullets; T >= 0; T -= 1) h.eq(h.length - T).addClass("".concat(n.bulletActiveClass, "-main"));
                                            h.eq(h.length - n.dynamicMainBullets - 1).addClass("".concat(n.bulletActiveClass, "-prev"))
                                        } else d(w, "prev"), d(b, "next");
                                    else d(w, "prev"), d(b, "next")
                                }
                            }
                            if (n.dynamicBullets) {
                                var C = Math.min(h.length, n.dynamicMainBullets + 4),
                                    S = (t * C - t) / 2 - v * t,
                                    E = e ? "right" : "left";
                                h.css(a.isHorizontal() ? E : "top", "".concat(S, "px"))
                            }
                        }
                        if ("fraction" === n.type && (c.find(fe(n.currentClass)).text(n.formatFractionCurrent(i + 1)), c.find(fe(n.totalClass)).text(n.formatFractionTotal(u))), "progressbar" === n.type) {
                            var x;
                            x = n.progressbarOpposite ? a.isHorizontal() ? "vertical" : "horizontal" : a.isHorizontal() ? "horizontal" : "vertical";
                            var k = (i + 1) / u,
                                P = 1,
                                O = 1;
                            "horizontal" === x ? P = k : O = k, c.find(fe(n.progressbarFillClass)).transform("translate3d(0,0,0) scaleX(".concat(P, ") scaleY(").concat(O, ")")).transition(a.params.speed)
                        }
                        "custom" === n.type && n.renderCustom ? (c.html(n.renderCustom(a, i + 1, u)), r("paginationRender", c[0])) : r("paginationUpdate", c[0]), a.params.watchOverflow && a.enabled && c[a.isLocked ? "addClass" : "removeClass"](n.lockClass)
                    }
                }

                function u() {
                    var e = a.params.pagination;
                    if (!l()) {
                        var t = a.virtual && a.params.virtual.enabled ? a.virtual.slides.length : a.slides.length,
                            n = a.pagination.$el,
                            i = "";
                        if ("bullets" === e.type) {
                            var s = a.params.loop ? Math.ceil((t - 2 * a.loopedSlides) / a.params.slidesPerGroup) : a.snapGrid.length;
                            a.params.freeMode && a.params.freeMode.enabled && !a.params.loop && s > t && (s = t);
                            for (var o = 0; o < s; o += 1) e.renderBullet ? i += e.renderBullet.call(a, o, e.bulletClass) : i += "<".concat(e.bulletElement, ' class="').concat(e.bulletClass, '"></').concat(e.bulletElement, ">");
                            n.html(i), a.pagination.bullets = n.find(fe(e.bulletClass))
                        }
                        "fraction" === e.type && (i = e.renderFraction ? e.renderFraction.call(a, e.currentClass, e.totalClass) : '<span class="'.concat(e.currentClass, '"></span>') + " / " + '<span class="'.concat(e.totalClass, '"></span>'), n.html(i)), "progressbar" === e.type && (i = e.renderProgressbar ? e.renderProgressbar.call(a, e.progressbarFillClass) : '<span class="'.concat(e.progressbarFillClass, '"></span>'), n.html(i)), "custom" !== e.type && r("paginationRender", a.pagination.$el[0])
                    }
                }

                function p() {
                    a.params.pagination = ue(a, a.originalParams.pagination, a.params.pagination, {
                        el: "swiper-pagination"
                    });
                    var e = a.params.pagination;
                    if (e.el) {
                        var t = M(e.el);
                        0 !== t.length && (a.params.uniqueNavElements && "string" === typeof e.el && t.length > 1 && (t = a.$el.find(e.el)).length > 1 && (t = t.filter((function(e) {
                            return M(e).parents(".swiper")[0] === a.el
                        }))), "bullets" === e.type && e.clickable && t.addClass(e.clickableClass), t.addClass(e.modifierClass + e.type), t.addClass(a.isHorizontal() ? e.horizontalClass : e.verticalClass), "bullets" === e.type && e.dynamicBullets && (t.addClass("".concat(e.modifierClass).concat(e.type, "-dynamic")), o = 0, e.dynamicMainBullets < 1 && (e.dynamicMainBullets = 1)), "progressbar" === e.type && e.progressbarOpposite && t.addClass(e.progressbarOppositeClass), e.clickable && t.on("click", fe(e.bulletClass), (function(e) {
                            e.preventDefault();
                            var t = M(this).index() * a.params.slidesPerGroup;
                            a.params.loop && (t += a.loopedSlides), a.slideTo(t)
                        })), Object.assign(a.pagination, {
                            $el: t,
                            el: t[0]
                        }), a.enabled || t.addClass(e.lockClass))
                    }
                }

                function f() {
                    var e = a.params.pagination;
                    if (!l()) {
                        var t = a.pagination.$el;
                        t.removeClass(e.hiddenClass), t.removeClass(e.modifierClass + e.type), t.removeClass(a.isHorizontal() ? e.horizontalClass : e.verticalClass), a.pagination.bullets && a.pagination.bullets.removeClass && a.pagination.bullets.removeClass(e.bulletActiveClass), e.clickable && t.off("click", fe(e.bulletClass))
                    }
                }
                i("init", (function() {
                    !1 === a.params.pagination.enabled ? v() : (p(), u(), c())
                })), i("activeIndexChange", (function() {
                    (a.params.loop || "undefined" === typeof a.snapIndex) && c()
                })), i("snapIndexChange", (function() {
                    a.params.loop || c()
                })), i("slidesLengthChange", (function() {
                    a.params.loop && (u(), c())
                })), i("snapGridLengthChange", (function() {
                    a.params.loop || (u(), c())
                })), i("destroy", (function() {
                    f()
                })), i("enable disable", (function() {
                    var e = a.pagination.$el;
                    e && e[a.enabled ? "removeClass" : "addClass"](a.params.pagination.lockClass)
                })), i("lock unlock", (function() {
                    c()
                })), i("click", (function(e, t) {
                    var n = t.target,
                        i = a.pagination.$el;
                    if (a.params.pagination.el && a.params.pagination.hideOnClick && i && i.length > 0 && !M(n).hasClass(a.params.pagination.bulletClass)) {
                        if (a.navigation && (a.navigation.nextEl && n === a.navigation.nextEl || a.navigation.prevEl && n === a.navigation.prevEl)) return;
                        var s = i.hasClass(a.params.pagination.hiddenClass);
                        r(!0 === s ? "paginationShow" : "paginationHide"), i.toggleClass(a.params.pagination.hiddenClass)
                    }
                }));
                var v = function() {
                    a.$el.addClass(a.params.pagination.paginationDisabledClass), a.pagination.$el && a.pagination.$el.addClass(a.params.pagination.paginationDisabledClass), f()
                };
                Object.assign(a.pagination, {
                    enable: function() {
                        a.$el.removeClass(a.params.pagination.paginationDisabledClass), a.pagination.$el && a.pagination.$el.removeClass(a.params.pagination.paginationDisabledClass), p(), u(), c()
                    },
                    disable: v,
                    render: u,
                    update: c,
                    init: p,
                    destroy: f
                })
            }

            function he(e) {
                var t, a = e.swiper,
                    n = e.extendParams,
                    i = e.on,
                    r = e.emit;

                function s() {
                    if (!a.size) return a.autoplay.running = !1, void(a.autoplay.paused = !1);
                    var e = a.slides.eq(a.activeIndex),
                        n = a.params.autoplay.delay;
                    e.attr("data-swiper-autoplay") && (n = e.attr("data-swiper-autoplay") || a.params.autoplay.delay), clearTimeout(t), t = P((function() {
                        var e;
                        a.params.autoplay.reverseDirection ? a.params.loop ? (a.loopFix(), e = a.slidePrev(a.params.speed, !0, !0), r("autoplay")) : a.isBeginning ? a.params.autoplay.stopOnLastSlide ? l() : (e = a.slideTo(a.slides.length - 1, a.params.speed, !0, !0), r("autoplay")) : (e = a.slidePrev(a.params.speed, !0, !0), r("autoplay")) : a.params.loop ? (a.loopFix(), e = a.slideNext(a.params.speed, !0, !0), r("autoplay")) : a.isEnd ? a.params.autoplay.stopOnLastSlide ? l() : (e = a.slideTo(0, a.params.speed, !0, !0), r("autoplay")) : (e = a.slideNext(a.params.speed, !0, !0), r("autoplay")), (a.params.cssMode && a.autoplay.running || !1 === e) && s()
                    }), n)
                }

                function o() {
                    return "undefined" === typeof t && (!a.autoplay.running && (a.autoplay.running = !0, r("autoplayStart"), s(), !0))
                }

                function l() {
                    return !!a.autoplay.running && ("undefined" !== typeof t && (t && (clearTimeout(t), t = void 0), a.autoplay.running = !1, r("autoplayStop"), !0))
                }

                function c(e) {
                    a.autoplay.running && (a.autoplay.paused || (t && clearTimeout(t), a.autoplay.paused = !0, 0 !== e && a.params.autoplay.waitForTransition ? ["transitionend", "webkitTransitionEnd"].forEach((function(e) {
                        a.$wrapperEl[0].addEventListener(e, p)
                    })) : (a.autoplay.paused = !1, s())))
                }

                function u() {
                    var e = d();
                    "hidden" === e.visibilityState && a.autoplay.running && c(), "visible" === e.visibilityState && a.autoplay.paused && (s(), a.autoplay.paused = !1)
                }

                function p(e) {
                    a && !a.destroyed && a.$wrapperEl && e.target === a.$wrapperEl[0] && (["transitionend", "webkitTransitionEnd"].forEach((function(e) {
                        a.$wrapperEl[0].removeEventListener(e, p)
                    })), a.autoplay.paused = !1, a.autoplay.running ? s() : l())
                }

                function f() {
                    a.params.autoplay.disableOnInteraction ? l() : (r("autoplayPause"), c()), ["transitionend", "webkitTransitionEnd"].forEach((function(e) {
                        a.$wrapperEl[0].removeEventListener(e, p)
                    }))
                }

                function v() {
                    a.params.autoplay.disableOnInteraction || (a.autoplay.paused = !1, r("autoplayResume"), s())
                }
                a.autoplay = {
                    running: !1,
                    paused: !1
                }, n({
                    autoplay: {
                        enabled: !1,
                        delay: 3e3,
                        waitForTransition: !0,
                        disableOnInteraction: !0,
                        stopOnLastSlide: !1,
                        reverseDirection: !1,
                        pauseOnMouseEnter: !1
                    }
                }), i("init", (function() {
                    a.params.autoplay.enabled && (o(), d().addEventListener("visibilitychange", u), a.params.autoplay.pauseOnMouseEnter && (a.$el.on("mouseenter", f), a.$el.on("mouseleave", v)))
                })), i("beforeTransitionStart", (function(e, t, n) {
                    a.autoplay.running && (n || !a.params.autoplay.disableOnInteraction ? a.autoplay.pause(t) : l())
                })), i("sliderFirstMove", (function() {
                    a.autoplay.running && (a.params.autoplay.disableOnInteraction ? l() : c())
                })), i("touchEnd", (function() {
                    a.params.cssMode && a.autoplay.paused && !a.params.autoplay.disableOnInteraction && s()
                })), i("destroy", (function() {
                    a.$el.off("mouseenter", f), a.$el.off("mouseleave", v), a.autoplay.running && l(), d().removeEventListener("visibilitychange", u)
                })), Object.assign(a.autoplay, {
                    pause: c,
                    run: s,
                    start: o,
                    stop: l
                })
            }

            function me(e) {
                var t = e.swiper,
                    a = e.extendParams,
                    n = e.emit,
                    i = e.once;
                a({
                    freeMode: {
                        enabled: !1,
                        momentum: !0,
                        momentumRatio: 1,
                        momentumBounce: !0,
                        momentumBounceRatio: 1,
                        momentumVelocityRatio: 1,
                        sticky: !1,
                        minimumVelocity: .02
                    }
                }), Object.assign(t, {
                    freeMode: {
                        onTouchStart: function() {
                            var e = t.getTranslate();
                            t.setTranslate(e), t.setTransition(0), t.touchEventsData.velocities.length = 0, t.freeMode.onTouchEnd({
                                currentPos: t.rtl ? t.translate : -t.translate
                            })
                        },
                        onTouchMove: function() {
                            var e = t.touchEventsData,
                                a = t.touches;
                            0 === e.velocities.length && e.velocities.push({
                                position: a[t.isHorizontal() ? "startX" : "startY"],
                                time: e.touchStartTime
                            }), e.velocities.push({
                                position: a[t.isHorizontal() ? "currentX" : "currentY"],
                                time: O()
                            })
                        },
                        onTouchEnd: function(e) {
                            var a = e.currentPos,
                                r = t.params,
                                s = t.$wrapperEl,
                                o = t.rtlTranslate,
                                l = t.snapGrid,
                                d = t.touchEventsData,
                                c = O() - d.touchStartTime;
                            if (a < -t.minTranslate()) t.slideTo(t.activeIndex);
                            else if (a > -t.maxTranslate()) t.slides.length < l.length ? t.slideTo(l.length - 1) : t.slideTo(t.slides.length - 1);
                            else {
                                if (r.freeMode.momentum) {
                                    if (d.velocities.length > 1) {
                                        var u = d.velocities.pop(),
                                            p = d.velocities.pop(),
                                            f = u.position - p.position,
                                            v = u.time - p.time;
                                        t.velocity = f / v, t.velocity /= 2, Math.abs(t.velocity) < r.freeMode.minimumVelocity && (t.velocity = 0), (v > 150 || O() - u.time > 300) && (t.velocity = 0)
                                    } else t.velocity = 0;
                                    t.velocity *= r.freeMode.momentumVelocityRatio, d.velocities.length = 0;
                                    var h = 1e3 * r.freeMode.momentumRatio,
                                        m = t.velocity * h,
                                        g = t.translate + m;
                                    o && (g = -g);
                                    var w, b, y = !1,
                                        T = 20 * Math.abs(t.velocity) * r.freeMode.momentumBounceRatio;
                                    if (g < t.maxTranslate()) r.freeMode.momentumBounce ? (g + t.maxTranslate() < -T && (g = t.maxTranslate() - T), w = t.maxTranslate(), y = !0, d.allowMomentumBounce = !0) : g = t.maxTranslate(), r.loop && r.centeredSlides && (b = !0);
                                    else if (g > t.minTranslate()) r.freeMode.momentumBounce ? (g - t.minTranslate() > T && (g = t.minTranslate() + T), w = t.minTranslate(), y = !0, d.allowMomentumBounce = !0) : g = t.minTranslate(), r.loop && r.centeredSlides && (b = !0);
                                    else if (r.freeMode.sticky) {
                                        for (var C, S = 0; S < l.length; S += 1)
                                            if (l[S] > -g) {
                                                C = S;
                                                break
                                            }
                                        g = -(g = Math.abs(l[C] - g) < Math.abs(l[C - 1] - g) || "next" === t.swipeDirection ? l[C] : l[C - 1])
                                    }
                                    if (b && i("transitionEnd", (function() {
                                            t.loopFix()
                                        })), 0 !== t.velocity) {
                                        if (h = o ? Math.abs((-g - t.translate) / t.velocity) : Math.abs((g - t.translate) / t.velocity), r.freeMode.sticky) {
                                            var E = Math.abs((o ? -g : g) - t.translate),
                                                x = t.slidesSizesGrid[t.activeIndex];
                                            h = E < x ? r.speed : E < 2 * x ? 1.5 * r.speed : 2.5 * r.speed
                                        }
                                    } else if (r.freeMode.sticky) return void t.slideToClosest();
                                    r.freeMode.momentumBounce && y ? (t.updateProgress(w), t.setTransition(h), t.setTranslate(g), t.transitionStart(!0, t.swipeDirection), t.animating = !0, s.transitionEnd((function() {
                                        t && !t.destroyed && d.allowMomentumBounce && (n("momentumBounce"), t.setTransition(r.speed), setTimeout((function() {
                                            t.setTranslate(w), s.transitionEnd((function() {
                                                t && !t.destroyed && t.transitionEnd()
                                            }))
                                        }), 0))
                                    }))) : t.velocity ? (n("_freeModeNoMomentumRelease"), t.updateProgress(g), t.setTransition(h), t.setTranslate(g), t.transitionStart(!0, t.swipeDirection), t.animating || (t.animating = !0, s.transitionEnd((function() {
                                        t && !t.destroyed && t.transitionEnd()
                                    })))) : t.updateProgress(g), t.updateActiveIndex(), t.updateSlidesClasses()
                                } else {
                                    if (r.freeMode.sticky) return void t.slideToClosest();
                                    r.freeMode && n("_freeModeNoMomentumRelease")
                                }(!r.freeMode.momentum || c >= r.longSwipesMs) && (t.updateProgress(), t.updateActiveIndex(), t.updateSlidesClasses())
                            }
                        }
                    }
                })
            }

            function ge(e, t) {
                return e.transformEl ? t.find(e.transformEl).css({
                    "backface-visibility": "hidden",
                    "-webkit-backface-visibility": "hidden"
                }) : t
            }

            function we(e) {
                var t = e.swiper,
                    a = e.extendParams,
                    n = e.on;
                a({
                    fadeEffect: {
                        crossFade: !1,
                        transformEl: null
                    }
                });
                ! function(e) {
                    var t, a = e.effect,
                        n = e.swiper,
                        i = e.on,
                        r = e.setTranslate,
                        s = e.setTransition,
                        o = e.overwriteParams,
                        l = e.perspective,
                        d = e.recreateShadows,
                        c = e.getEffectParams;
                    i("beforeInit", (function() {
                        if (n.params.effect === a) {
                            n.classNames.push("".concat(n.params.containerModifierClass).concat(a)), l && l() && n.classNames.push("".concat(n.params.containerModifierClass, "3d"));
                            var e = o ? o() : {};
                            Object.assign(n.params, e), Object.assign(n.originalParams, e)
                        }
                    })), i("setTranslate", (function() {
                        n.params.effect === a && r()
                    })), i("setTransition", (function(e, t) {
                        n.params.effect === a && s(t)
                    })), i("transitionEnd", (function() {
                        if (n.params.effect === a && d) {
                            if (!c || !c().slideShadows) return;
                            n.slides.each((function(e) {
                                n.$(e).find(".swiper-slide-shadow-top, .swiper-slide-shadow-right, .swiper-slide-shadow-bottom, .swiper-slide-shadow-left").remove()
                            })), d()
                        }
                    })), i("virtualUpdate", (function() {
                        n.params.effect === a && (n.slides.length || (t = !0), requestAnimationFrame((function() {
                            t && n.slides && n.slides.length && (r(), t = !1)
                        })))
                    }))
                }({
                    effect: "fade",
                    swiper: t,
                    on: n,
                    setTranslate: function() {
                        for (var e = t.slides, a = t.params.fadeEffect, n = 0; n < e.length; n += 1) {
                            var i = t.slides.eq(n),
                                r = -i[0].swiperSlideOffset;
                            t.params.virtualTranslate || (r -= t.translate);
                            var s = 0;
                            t.isHorizontal() || (s = r, r = 0);
                            var o = t.params.fadeEffect.crossFade ? Math.max(1 - Math.abs(i[0].progress), 0) : 1 + Math.min(Math.max(i[0].progress, -1), 0);
                            ge(a, i).css({
                                opacity: o
                            }).transform("translate3d(".concat(r, "px, ").concat(s, "px, 0px)"))
                        }
                    },
                    setTransition: function(e) {
                        var a = t.params.fadeEffect.transformEl;
                        (a ? t.slides.find(a) : t.slides).transition(e),
                            function(e) {
                                var t = e.swiper,
                                    a = e.duration,
                                    n = e.transformEl,
                                    i = e.allSlides,
                                    r = t.slides,
                                    s = t.activeIndex,
                                    o = t.$wrapperEl;
                                if (t.params.virtualTranslate && 0 !== a) {
                                    var l = !1;
                                    (i ? n ? r.find(n) : r : n ? r.eq(s).find(n) : r.eq(s)).transitionEnd((function() {
                                        if (!l && t && !t.destroyed) {
                                            l = !0, t.animating = !1;
                                            for (var e = ["webkitTransitionEnd", "transitionend"], a = 0; a < e.length; a += 1) o.trigger(e[a])
                                        }
                                    }))
                                }
                            }({
                                swiper: t,
                                duration: e,
                                transformEl: a,
                                allSlides: !0
                            })
                    },
                    overwriteParams: function() {
                        return {
                            slidesPerView: 1,
                            slidesPerGroup: 1,
                            watchSlidesProgress: !0,
                            spaceBetween: 0,
                            virtualTranslate: !t.params.cssMode
                        }
                    }
                })
            }
        }
    }
]);