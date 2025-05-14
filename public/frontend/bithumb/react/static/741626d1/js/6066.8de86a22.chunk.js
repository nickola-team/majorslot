"use strict";
(self.webpackChunk_bithumb_pc = self.webpackChunk_bithumb_pc || []).push([
    [6066], {
        96838: function(e, t, n) {
            n.r(t), n.d(t, {
                default: function() {
                    return Xe
                }
            });
            var i = n(9967),
                a = n(46140),
                o = n(93953),
                r = n(38153),
                s = n(50611),
                c = n(34626),
                u = n(88212),
                l = n.n(u),
                _ = n(38619),
                d = n(76546),
                E = n(60006),
                m = n(45149),
                f = n(35302),
                g = n.n(f),
                h = n(15822),
                p = n(24103),
                T = l().bind({
                    "modal-alert": "BlockAccount_modal-alert__Q4skr",
                    "modal-alert-content": "BlockAccount_modal-alert-content__sIcSI",
                    "modal-alert-content__title": "BlockAccount_modal-alert-content__title__aip0g",
                    "modal-alert-content__text-box": "BlockAccount_modal-alert-content__text-box__iBe8W",
                    "modal-alert-content__text": "BlockAccount_modal-alert-content__text__2n0Mt",
                    "modal-alert-bottom": "BlockAccount_modal-alert-bottom__8mwys"
                }),
                O = (0, o.Pi)((function() {
                    var e, t, n, i = (0, a.G2)(),
                        o = i.service.setAjaxResetCancel,
                        s = i.modalService,
                        c = s.hideModal,
                        u = s.showModal,
                        l = s.visible,
                        _ = i.localeService.locale,
                        f = i.gaService.fnGASendEvent,
                        O = (0, d.s0)(),
                        S = l(h.o1X),
                        R = function() {
                            S && ("member.fail.00018" === S.params.code ? S.params.resetFormCb && S.params.resetFormCb() : S.params.closeModalCb && S.params.closeModalCb(), c(h.o1X))
                        },
                        v = function() {
                            if (S) {
                                switch (S.params.code) {
                                    case "member.fail.00018":
                                        f("\ub85c\uadf8\uc778", "\ub85c\uadf8\uc778\uc2e4\ud328", "\ube44\ubc00\ubc88\ud638\uc7ac\uc124\uc815"), O("/legacy/customer_support/reception/traPw");
                                        break;
                                    case "member.fail.00056":
                                        f("\ub85c\uadf8\uc778", "\ub85c\uadf8\uc778\uc2e4\ud328", "\ube44\ubc00\ubc88\ud638\uc7ac\uc124\uc815"), O("/member/find-id");
                                        break;
                                    case "member.fail.00064":
                                        O("/legacy/member_operation/login_security");
                                        break;
                                    case "member.fail.00065":
                                        O("/legacy/member_operation/change_password");
                                        break;
                                    case "member.fail.00001":
                                    case "member.fail.00004":
                                    case "member.fail.00008":
                                    case "member.fail.00058":
                                        S.params.resetFormCb && S.params.resetFormCb();
                                        break;
                                    case "member.fail.00047":
                                    case "member.fail.00048":
                                    case "member.fail.00049":
                                        var e = S.params,
                                            t = e.userId,
                                            n = e.phoneNo;
                                        o({
                                            userId: t,
                                            phoneNo: n
                                        }).then((function(e) {
                                            200 === e.status && u(h.DzK, {
                                                message: _("login.block.msg09")
                                            })
                                        }))
                                }
                                c(h.o1X)
                            }
                        };
                    if (S) {
                        if (t = S.params.message, n = (0, p.jsx)(E.ZP, {
                                type: E.PD.DefaultNew,
                                color: E.n5.Primary,
                                size: E.VA.Large,
                                onClick: v,
                                children: _("button.msg11")
                            }), "string" === typeof t && t.indexOf("||") > -1) {
                            var x = t.split("||"),
                                A = (0, r.Z)(x, 2);
                            e = A[0], t = A[1]
                        }
                        switch (S.params.code) {
                            case "member.fail.00018":
                            case "member.fail.00056":
                                n = (0, p.jsxs)(p.Fragment, {
                                    children: [(0, p.jsx)(E.ZP, {
                                        type: E.PD.DefaultNew,
                                        color: E.n5.Secondary,
                                        size: E.VA.Large,
                                        onClick: R,
                                        children: _("button.msg32")
                                    }), (0, p.jsx)(E.ZP, {
                                        type: E.PD.DefaultNew,
                                        color: E.n5.Primary,
                                        size: E.VA.Large,
                                        onClick: v,
                                        children: _("button.msg52")
                                    })]
                                });
                                break;
                            case "member.fail.00052":
                                break;
                            case "N":
                                e = _("login.block.msg02"), t = _("login.block.msg10", {
                                    tag: (0, p.jsx)("br", {})
                                });
                                break;
                            case "member.fail.00001":
                            case "member.fail.00004":
                                e = _("login.block.msg04");
                                break;
                            case "member.fail.00047":
                                e = _("login.block.msg05"), n = (0, p.jsx)(p.Fragment, {
                                    children: (0, p.jsx)(E.ZP, {
                                        type: E.PD.DefaultNew,
                                        color: E.n5.Primary,
                                        size: E.VA.Large,
                                        onClick: R,
                                        children: _("button.msg11")
                                    })
                                });
                                break;
                            case "member.fail.00048":
                            case "member.fail.00049":
                                e = _("login.block.msg06"), n = (0, p.jsx)(p.Fragment, {
                                    children: (0, p.jsx)(E.ZP, {
                                        type: E.PD.DefaultNew,
                                        color: E.n5.Primary,
                                        size: E.VA.Large,
                                        onClick: R,
                                        children: _("button.msg11")
                                    })
                                });
                                break;
                            case "member.fail.00064":
                                e = _("login.block.msg07"), t = _("login.block.msg12", {
                                    tag: (0, p.jsx)("br", {})
                                }), n = (0, p.jsxs)(p.Fragment, {
                                    children: [(0, p.jsx)(E.ZP, {
                                        type: E.PD.DefaultNew,
                                        color: E.n5.Secondary,
                                        size: E.VA.Large,
                                        onClick: R,
                                        children: _("button.msg08")
                                    }), (0, p.jsx)(E.ZP, {
                                        type: E.PD.DefaultNew,
                                        color: E.n5.Primary,
                                        size: E.VA.Large,
                                        onClick: v,
                                        children: _("button.msg11")
                                    })]
                                });
                                break;
                            case "member.fail.00065":
                                e = _("login.block.msg08"), t = _("login.block.msg13", {
                                    tag: (0, p.jsx)("br", {})
                                }), n = (0, p.jsxs)(p.Fragment, {
                                    children: [(0, p.jsx)(E.ZP, {
                                        type: E.PD.DefaultNew,
                                        color: E.n5.Secondary,
                                        size: E.VA.Large,
                                        onClick: R,
                                        children: _("button.msg08")
                                    }), (0, p.jsx)(E.ZP, {
                                        type: E.PD.DefaultNew,
                                        color: E.n5.Primary,
                                        size: E.VA.Large,
                                        onClick: v,
                                        children: _("button.msg11")
                                    })]
                                });
                                break;
                            case "member.fail.00008":
                                e = _("login.block.msg14")
                        }
                    }
                    return (0, p.jsxs)(m.ZP, {
                        visible: S,
                        type: m.y7.Alert,
                        className: T("modal-alert"),
                        hideButton: !0,
                        closeFunc: R,
                        children: [(0, p.jsxs)("div", {
                            className: T("modal-alert-content"),
                            children: [e && (0, p.jsx)("h3", {
                                className: T("modal-alert-content__title"),
                                dangerouslySetInnerHTML: {
                                    __html: g().sanitize(e).replace(/\\n/gi, "<br/>")
                                }
                            }), (0, p.jsx)("div", {
                                className: T("modal-alert-content__text-box"),
                                children: "string" === typeof t ? (0, p.jsx)("p", {
                                    className: T("modal-alert-content__text"),
                                    dangerouslySetInnerHTML: {
                                        __html: g().sanitize(t).replace(/\\n/gi, "<br/>")
                                    }
                                }) : (0, p.jsx)("p", {
                                    className: T("modal-alert-content__text"),
                                    children: t
                                })
                            })]
                        }), (0, p.jsx)("div", {
                            className: T("modal-alert-bottom"),
                            children: n
                        })]
                    })
                })),
                S = n.p + "static/741626d1/media/img-block-foreign-ip-modal.24e330bc0285d2c52803.webp",
                R = l().bind({
                    "block-foreign-ip-modal__content": "BlockForeignIp_block-foreign-ip-modal__content__X9LI3",
                    "block-foreign-ip-modal__img": "BlockForeignIp_block-foreign-ip-modal__img__izw5I",
                    "block-foreign-ip-modal__button-wrap": "BlockForeignIp_block-foreign-ip-modal__button-wrap__if7md",
                    "block-foreign-ip-modal__button": "BlockForeignIp_block-foreign-ip-modal__button__naeAe"
                }),
                v = (0, o.Pi)((function() {
                    var e = (0, a.G2)(),
                        t = e.modalService,
                        n = t.visible,
                        i = t.hideModal,
                        o = e.localeService.locale,
                        r = (0, d.s0)(),
                        s = n(h.IEu);
                    return (0, p.jsxs)(m.ZP, {
                        visible: s,
                        hideButton: !0,
                        type: m.y7.Notice,
                        className: R("block-foreign-ip-modal"),
                        children: [(0, p.jsx)("div", {
                            className: R("block-foreign-ip-modal__content"),
                            children: (0, p.jsx)("img", {
                                className: R("block-foreign-ip-modal__img"),
                                src: S,
                                alt: "\ud574\uc678 IP \ucc28\ub2e8 \uc548\ub0b4. \ud574\uc678\uc5d0\uc11c \ub85c\uadf8\uc778 \uc2dc\ub3c4\uac00 \uac10\uc9c0\ub418\uc5b4 \ub85c\uadf8\uc544\uc6c3 \ucc98\ub9ac\ub418\uc5c8\uc2b5\ub2c8\ub2e4. \ubcf8\uc778\uc774 \ub85c\uadf8\uc778 \ud55c \uac83\uc774 \uc544\ub2d0 \uacbd\uc6b0, \uc989\uc2dc \ube44\ubc00\ubc88\ud638\ub97c \ubcc0\uacbd\ud558\uc2dc\uac70\ub098 \uace0\uac1d \uc0c1\ub2f4\uc13c\ud130 1661-5566(365\uc77c 24\uc2dc\uac04 \uc6b4\uc601)\ub85c \uc11c\ube44\uc2a4 \ubb38\uc758\ud574 \uc8fc\uc2dc\uae30 \ubc14\ub78d\ub2c8\ub2e4."
                            })
                        }), (0, p.jsx)("div", {
                            className: R("block-foreign-ip-modal__button-wrap"),
                            children: (0, p.jsx)(E.ZP, {
                                className: R("block-foreign-ip-modal__button"),
                                type: E.PD.DefaultNew,
                                color: E.n5.Primary,
                                size: E.VA.ExtraLarge,
                                onClick: function() {
                                    r("/main"), i(h.IEu)
                                },
                                children: o("button.msg11")
                            })
                        })]
                    })
                })),
                x = n(96094),
                A = n(35490),
                C = n(48124),
                N = n(7028),
                b = n.n(N),
                I = n(34999),
                y = n(30544),
                $ = n.n(y);

            function P() {
                return P = Object.assign || function(e) {
                    for (var t = 1; t < arguments.length; t++) {
                        var n = arguments[t];
                        for (var i in n) Object.prototype.hasOwnProperty.call(n, i) && (e[i] = n[i])
                    }
                    return e
                }, P.apply(this, arguments)
            }

            function L(e) {
                if (void 0 === e) throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
                return e
            }
            var w = function(e) {
                var t, n;

                function a() {
                    var t;
                    return (t = e.call(this) || this).handleExpired = t.handleExpired.bind(L(t)), t.handleErrored = t.handleErrored.bind(L(t)), t.handleChange = t.handleChange.bind(L(t)), t.handleRecaptchaRef = t.handleRecaptchaRef.bind(L(t)), t
                }
                n = e, (t = a).prototype = Object.create(n.prototype), t.prototype.constructor = t, t.__proto__ = n;
                var o = a.prototype;
                return o.getValue = function() {
                    return this.props.grecaptcha && void 0 !== this._widgetId ? this.props.grecaptcha.getResponse(this._widgetId) : null
                }, o.getWidgetId = function() {
                    return this.props.grecaptcha && void 0 !== this._widgetId ? this._widgetId : null
                }, o.execute = function() {
                    var e = this.props.grecaptcha;
                    if (e && void 0 !== this._widgetId) return e.execute(this._widgetId);
                    this._executeRequested = !0
                }, o.executeAsync = function() {
                    var e = this;
                    return new Promise((function(t, n) {
                        e.executionResolve = t, e.executionReject = n, e.execute()
                    }))
                }, o.reset = function() {
                    this.props.grecaptcha && void 0 !== this._widgetId && this.props.grecaptcha.reset(this._widgetId)
                }, o.handleExpired = function() {
                    this.props.onExpired ? this.props.onExpired() : this.handleChange(null)
                }, o.handleErrored = function() {
                    this.props.onErrored && this.props.onErrored(), this.executionReject && (this.executionReject(), delete this.executionResolve, delete this.executionReject)
                }, o.handleChange = function(e) {
                    this.props.onChange && this.props.onChange(e), this.executionResolve && (this.executionResolve(e), delete this.executionReject, delete this.executionResolve)
                }, o.explicitRender = function() {
                    if (this.props.grecaptcha && this.props.grecaptcha.render && void 0 === this._widgetId) {
                        var e = document.createElement("div");
                        this._widgetId = this.props.grecaptcha.render(e, {
                            sitekey: this.props.sitekey,
                            callback: this.handleChange,
                            theme: this.props.theme,
                            type: this.props.type,
                            tabindex: this.props.tabindex,
                            "expired-callback": this.handleExpired,
                            "error-callback": this.handleErrored,
                            size: this.props.size,
                            stoken: this.props.stoken,
                            hl: this.props.hl,
                            badge: this.props.badge
                        }), this.captcha.appendChild(e)
                    }
                    this._executeRequested && this.props.grecaptcha && void 0 !== this._widgetId && (this._executeRequested = !1, this.execute())
                }, o.componentDidMount = function() {
                    this.explicitRender()
                }, o.componentDidUpdate = function() {
                    this.explicitRender()
                }, o.componentWillUnmount = function() {
                    void 0 !== this._widgetId && (this.delayOfCaptchaIframeRemoving(), this.reset())
                }, o.delayOfCaptchaIframeRemoving = function() {
                    var e = document.createElement("div");
                    for (document.body.appendChild(e), e.style.display = "none"; this.captcha.firstChild;) e.appendChild(this.captcha.firstChild);
                    setTimeout((function() {
                        document.body.removeChild(e)
                    }), 5e3)
                }, o.handleRecaptchaRef = function(e) {
                    this.captcha = e
                }, o.render = function() {
                    var e = this.props,
                        t = (e.sitekey, e.onChange, e.theme, e.type, e.tabindex, e.onExpired, e.onErrored, e.size, e.stoken, e.grecaptcha, e.badge, e.hl, function(e, t) {
                            if (null == e) return {};
                            var n, i, a = {},
                                o = Object.keys(e);
                            for (i = 0; i < o.length; i++) n = o[i], t.indexOf(n) >= 0 || (a[n] = e[n]);
                            return a
                        }(e, ["sitekey", "onChange", "theme", "type", "tabindex", "onExpired", "onErrored", "size", "stoken", "grecaptcha", "badge", "hl"]));
                    return i.createElement("div", P({}, t, {
                        ref: this.handleRecaptchaRef
                    }))
                }, a
            }(i.Component);
            w.displayName = "ReCAPTCHA", w.propTypes = {
                sitekey: $().string.isRequired,
                onChange: $().func,
                grecaptcha: $().object,
                theme: $().oneOf(["dark", "light"]),
                type: $().oneOf(["image", "audio"]),
                tabindex: $().number,
                onExpired: $().func,
                onErrored: $().func,
                size: $().oneOf(["compact", "normal", "invisible"]),
                stoken: $().string,
                hl: $().string,
                badge: $().oneOf(["bottomright", "bottomleft", "inline"])
            }, w.defaultProps = {
                onChange: function() {},
                theme: "light",
                type: "image",
                tabindex: 0,
                size: "normal",
                badge: "bottomright"
            };
            var M = n(77862),
                k = n.n(M);

            function D() {
                return D = Object.assign || function(e) {
                    for (var t = 1; t < arguments.length; t++) {
                        var n = arguments[t];
                        for (var i in n) Object.prototype.hasOwnProperty.call(n, i) && (e[i] = n[i])
                    }
                    return e
                }, D.apply(this, arguments)
            }
            var X = {},
                j = 0;
            var F = "onloadcallback";
            var U, K, G, B = (U = function() {
                    return "https://" + (("undefined" !== typeof window && window.recaptchaOptions || {}).useRecaptchaNet ? "recaptcha.net" : "www.google.com") + "/recaptcha/api.js?onload=" + F + "&render=explicit"
                }, K = (K = {
                    callbackName: F,
                    globalName: "grecaptcha"
                }) || {}, function(e) {
                    var t = e.displayName || e.name || "Component",
                        n = function(t) {
                            var n, a;

                            function o(e, n) {
                                var i;
                                return (i = t.call(this, e, n) || this).state = {}, i.__scriptURL = "", i
                            }
                            a = t, (n = o).prototype = Object.create(a.prototype), n.prototype.constructor = n, n.__proto__ = a;
                            var r = o.prototype;
                            return r.asyncScriptLoaderGetScriptLoaderID = function() {
                                return this.__scriptLoaderID || (this.__scriptLoaderID = "async-script-loader-" + j++), this.__scriptLoaderID
                            }, r.setupScriptURL = function() {
                                return this.__scriptURL = "function" === typeof U ? U() : U, this.__scriptURL
                            }, r.asyncScriptLoaderHandleLoad = function(e) {
                                var t = this;
                                this.setState(e, (function() {
                                    return t.props.asyncScriptOnLoad && t.props.asyncScriptOnLoad(t.state)
                                }))
                            }, r.asyncScriptLoaderTriggerOnScriptLoaded = function() {
                                var e = X[this.__scriptURL];
                                if (!e || !e.loaded) throw new Error("Script is not loaded.");
                                for (var t in e.observers) e.observers[t](e);
                                delete window[K.callbackName]
                            }, r.componentDidMount = function() {
                                var e = this,
                                    t = this.setupScriptURL(),
                                    n = this.asyncScriptLoaderGetScriptLoaderID(),
                                    i = K,
                                    a = i.globalName,
                                    o = i.callbackName,
                                    r = i.scriptId;
                                if (a && "undefined" !== typeof window[a] && (X[t] = {
                                        loaded: !0,
                                        observers: {}
                                    }), X[t]) {
                                    var s = X[t];
                                    return s && (s.loaded || s.errored) ? void this.asyncScriptLoaderHandleLoad(s) : void(s.observers[n] = function(t) {
                                        return e.asyncScriptLoaderHandleLoad(t)
                                    })
                                }
                                var c = {};
                                c[n] = function(t) {
                                    return e.asyncScriptLoaderHandleLoad(t)
                                }, X[t] = {
                                    loaded: !1,
                                    observers: c
                                };
                                var u = document.createElement("script");
                                for (var l in u.src = t, u.async = !0, K.attributes) u.setAttribute(l, K.attributes[l]);
                                r && (u.id = r);
                                var _ = function(e) {
                                    if (X[t]) {
                                        var n = X[t].observers;
                                        for (var i in n) e(n[i]) && delete n[i]
                                    }
                                };
                                o && "undefined" !== typeof window && (window[o] = function() {
                                    return e.asyncScriptLoaderTriggerOnScriptLoaded()
                                }), u.onload = function() {
                                    var e = X[t];
                                    e && (e.loaded = !0, _((function(t) {
                                        return !o && (t(e), !0)
                                    })))
                                }, u.onerror = function() {
                                    var e = X[t];
                                    e && (e.errored = !0, _((function(t) {
                                        return t(e), !0
                                    })))
                                }, document.body.appendChild(u)
                            }, r.componentWillUnmount = function() {
                                var e = this.__scriptURL;
                                if (!0 === K.removeOnUnmount)
                                    for (var t = document.getElementsByTagName("script"), n = 0; n < t.length; n += 1) t[n].src.indexOf(e) > -1 && t[n].parentNode && t[n].parentNode.removeChild(t[n]);
                                var i = X[e];
                                i && (delete i.observers[this.asyncScriptLoaderGetScriptLoaderID()], !0 === K.removeOnUnmount && delete X[e])
                            }, r.render = function() {
                                var t = K.globalName,
                                    n = this.props,
                                    a = (n.asyncScriptOnLoad, n.forwardedRef),
                                    o = function(e, t) {
                                        if (null == e) return {};
                                        var n, i, a = {},
                                            o = Object.keys(e);
                                        for (i = 0; i < o.length; i++) n = o[i], t.indexOf(n) >= 0 || (a[n] = e[n]);
                                        return a
                                    }(n, ["asyncScriptOnLoad", "forwardedRef"]);
                                return t && "undefined" !== typeof window && (o[t] = "undefined" !== typeof window[t] ? window[t] : void 0), o.ref = a, (0, i.createElement)(e, o)
                            }, o
                        }(i.Component),
                        a = (0, i.forwardRef)((function(e, t) {
                            return (0, i.createElement)(n, D({}, e, {
                                forwardedRef: t
                            }))
                        }));
                    return a.displayName = "AsyncScriptLoader(" + t + ")", a.propTypes = {
                        asyncScriptOnLoad: $().func
                    }, k()(a, e)
                })(w),
                z = B,
                W = (0, o.Pi)((function(e) {
                    var t = e.onCaptchaChange,
                        n = (0, a.G2)().sessionService.language,
                        o = (0, I.D9)(n, n),
                        r = (0, i.useRef)(null);
                    return (0, i.useEffect)((function() {
                        if (o !== n && r.current) {
                            var e, t, i, a, s = null === (e = r.current) || void 0 === e ? void 0 : e.querySelector("iframe");
                            if (!s) return;
                            if ((null === (t = s.getAttribute("src")) || void 0 === t || null === (i = t.match(/hl=(.*?)&/)) || void 0 === i ? void 0 : i.pop()) !== n) s.setAttribute("src", (null !== (a = s.getAttribute("src")) && void 0 !== a ? a : "").replace(/hl=(.*?)&/, "hl=".concat(n, "&")))
                        }
                    }), [n]), (0, p.jsx)("div", {
                        ref: r,
                        children: (0, p.jsx)(z, {
                            sitekey: "6LeLIAkTAAAAAFUAvlnnXJZfN7_qn5RYnrGyVX2M",
                            onChange: t,
                            hl: n
                        })
                    })
                })),
                V = n(20105),
                Z = n(98236),
                Y = function(e, t) {
                    return {
                        getInputValue: function(n) {
                            Z.z.ACTIVE ? V.$ASTX2.getE2EText(t, (function(t, n) {
                                0 === n ? e(t) : Z.z.errorAbort(n)
                            })) : e(n)
                        },
                        getInputValues: function(t) {
                            for (var n = [], i = document.getElementsByTagName("input"), a = 0; a < i.length; a++) {
                                var o = i[a];
                                o && o.getAttribute("data-e2e_type") && n.push(o)
                            }
                            Z.z.ACTIVE ? V.$ASTX2.getE2ETextS(n, (function(t, n) {
                                if (0 === n) {
                                    for (var i = document.getElementsByTagName("input"), a = [], o = 0; o < i.length; o++) {
                                        var r = i[o];
                                        "22" === r.getAttribute("data-e2e_type") && (r.value = t[r.name], a.push(r.value)), V.$ASTX2.clearE2EText(document.getElementById(i[o].id))
                                    }
                                    e(a)
                                } else Z.z.errorAbort(n)
                            })) : e(t)
                        },
                        setTarget: function() {
                            "Y" === s.pR.get("astxExecution") ? V.$ASTX2.init((function() {
                                V.$ASTX2.initNonE2E(), Z.z.ACTIVE = !0
                            }), (function() {
                                V.$ASTX2.uninitNonE2E(), Z.z.ACTIVE = !1
                            })) : (Z.z.ACTIVE = !1, V.$ASTX2.uninitNonE2E())
                        },
                        clearText: function() {
                            return Z.z.ACTIVE && V.$ASTX2.clearE2EText(t)
                        }
                    }
                },
                H = ["id", "type", "loginType", "nationCode", "intnlPhoneNo"];
            ! function(e) {
                e.Unchecked = "UNCHECKED", e.Expired = "EXPIRED", e.Empty = "EMPTY"
            }(G || (G = {}));
            var q = "ID",
                J = "PW",
                Q = l().bind({
                    "email-login": "Email_email-login__8dh4q",
                    "email-login--active": "Email_email-login--active__3H6y3",
                    "email-login__input-row": "Email_email-login__input-row__DtUPe",
                    "email-login__button": "Email_email-login__button__iNWDk",
                    captcha: "Email_captcha__fYujw",
                    "captcha-google": "Email_captcha-google__z9dmz",
                    "captcha-google__error-text": "Email_captcha-google__error-text__fDevJ",
                    "captcha-custom": "Email_captcha-custom__D3xcL",
                    "captcha-custom__text": "Email_captcha-custom__text__VDX3S",
                    "captcha-custom-box": "Email_captcha-custom-box__1eXBx",
                    "captcha-custom-box__img": "Email_captcha-custom-box__img__FOskM",
                    "captcha-custom-box__button": "Email_captcha-custom-box__button__ZwCjN",
                    "captcha--custom__error-text": "Email_captcha--custom__error-text__z1tZW"
                }),
                ee = (0, o.Pi)((function(e) {
                    var t, n = e.initialId,
                        o = e.active,
                        c = e.loginStepCb,
                        u = (0, a.G2)(),
                        l = u.service,
                        _ = l.getOnSecure,
                        m = l.getAjaxCaptchaInfo,
                        f = l.getAjaxEmailLogin,
                        g = u.modalService.showModal,
                        T = u.sessionService.setUserInfo,
                        O = u.localeService.locale,
                        S = u.gaService.fnGASendEvent,
                        R = (0, d.TH)().state,
                        v = (0, i.useState)(""),
                        N = (0, r.Z)(v, 2),
                        I = N[0],
                        y = N[1],
                        $ = (0, i.useState)(""),
                        P = (0, r.Z)($, 2),
                        L = P[0],
                        w = P[1],
                        M = (0, i.useState)(null),
                        k = (0, r.Z)(M, 2),
                        D = k[0],
                        X = k[1],
                        j = (0, i.useState)(""),
                        F = (0, r.Z)(j, 2),
                        U = F[0],
                        K = F[1],
                        B = (0, i.useState)(null),
                        z = (0, r.Z)(B, 2),
                        V = z[0],
                        Z = z[1],
                        ee = (0, i.useState)(!1),
                        te = (0, r.Z)(ee, 2),
                        ne = te[0],
                        ie = te[1],
                        ae = (0, i.useState)(""),
                        oe = (0, r.Z)(ae, 2),
                        re = oe[0],
                        se = oe[1],
                        ce = (0, i.useState)(),
                        ue = (0, r.Z)(ce, 2),
                        le = ue[0],
                        _e = ue[1],
                        de = (0, i.useRef)(),
                        Ee = (0, i.useRef)(),
                        me = (0, i.useRef)(null),
                        fe = (0, i.useRef)(),
                        ge = (0, i.useRef)({
                            allowClick: !0,
                            captchaTimeout: void 0,
                            resizeTimeout: void 0,
                            initAfterSuccessTimeout: 0,
                            retryCaptchaTimeout: void 0,
                            captchaResponseErrorCount: 0
                        }),
                        he = function(e) {
                            switch (e) {
                                case G.Unchecked:
                                    return O("login.email.msg04");
                                case G.Expired:
                                    return O("login.email.msg06");
                                case G.Empty:
                                    return O("login.email.msg05");
                                default:
                                    return null
                            }
                        },
                        pe = function e(t) {
                            window.clearTimeout(ge.current.captchaTimeout), ge.current.captchaTimeout = window.setTimeout((function() {
                                t ? e(t - 1) : (Z(G.Expired), ie(!0))
                            }), 1e3)
                        },
                        Te = function e() {
                            m().then((function(t) {
                                if (200 === t.status) {
                                    var n = t.data,
                                        i = n.loginFailCount,
                                        a = n.isUseGoogle,
                                        o = n.bithumbCaptcha;
                                    if (i > 0)
                                        if (a)
                                            if (X("g-recaptcha-response"), ie(!1), se(""), Z(null), "g-recaptcha-response" === D) {
                                                var r = window.grecaptcha;
                                                r && r.reset()
                                            } else window.clearTimeout(ge.current.resizeTimeout), ge.current.resizeTimeout = window.setTimeout((function() {
                                                return window.dispatchEvent(new Event("resize"))
                                            }));
                                    else o && (pe(o.expireSeconds), X(o.captchaKey), K(o.captchaImg), ie(!1), se(""), Z(null));
                                    else X(null), ie(!1), se(""), Z(null);
                                    ge.current.captchaResponseErrorCount = 0
                                } else {
                                    var c = (0, s.Pp)(ge.current.captchaResponseErrorCount++);
                                    ge.current.retryCaptchaTimeout = window.setTimeout((function() {
                                        e()
                                    }), c)
                                }
                            }))
                        },
                        Oe = function(e) {
                            e ? (se(e), Z(null)) : se(e)
                        },
                        Se = function(e) {
                            "Enter" === e.key && (e.preventDefault(), Re() && xe(e))
                        },
                        Re = function() {
                            return s.xK.test(I) && L.length > 7
                        },
                        ve = function() {
                            ge.current.allowClick = !0
                        },
                        xe = function(e) {
                            if (e.preventDefault(), ge.current.allowClick) {
                                ge.current.allowClick = !1;
                                var t = L;
                                if (D && "" === re) {
                                    var n;
                                    if ("g-recaptcha-response" === D) Z(G.Unchecked);
                                    else null === (n = fe.current) || void 0 === n || n.onBlur(), !ne && Z(G.Empty);
                                    return ge.current.allowClick = !0
                                }
                                if (V) return ge.current.allowClick = !0;
                                Ae.getInputValue(t)
                            }
                        },
                        Ae = Y((function(e) {
                            f({
                                userId: I,
                                password: b()(e).toString(),
                                captchaKey: D,
                                captchaAnswer: re
                            }).then((function(e) {
                                if (window.clearTimeout(ge.current.initAfterSuccessTimeout), 200 !== e.status) switch (Ae.clearText(), Te(), e.code) {
                                    case "member.fail.00008":
                                    case "member.fail.00018":
                                    case "member.fail.00056":
                                    case "member.fail.00052":
                                    case "member.fail.00047":
                                    case "member.fail.00048":
                                    case "member.fail.00049":
                                    case "member.fail.00001":
                                    case "member.fail.00004":
                                    case "member.fail.00058":
                                        g(h.o1X, {
                                            code: e.code,
                                            message: e.message,
                                            userId: I,
                                            resetFormCb: Ce,
                                            closeModalCb: ve
                                        });
                                        break;
                                    case "member.fail.00102":
                                        g(h.IEu);
                                        break;
                                    default:
                                        g(h.DzK, {
                                            message: e.message,
                                            closeCb: ve
                                        })
                                } else ge.current.allowClick = !0, "N" === e.data.status ? (g(h.o1X, {
                                    code: "N"
                                }), Ce()) : (T(e.data), c(2), ge.current.initAfterSuccessTimeout = window.setTimeout(Te, 200), S("\ub85c\uadf8\uc778", "\ub85c\uadf8\uc778", "\ub85c\uadf8\uc778_1\ucc28"))
                            }))
                        }), null === (t = Ee.current) || void 0 === t ? void 0 : t.$input.current),
                        Ce = function() {
                            y(""), w(""), se(""), ge.current.allowClick = !0
                        };
                    return (0, i.useEffect)((function() {
                        if (_e(I ? J : q), null !== R && void 0 !== R && R.id) {
                            var e = R,
                                t = (e.id, e.type, e.loginType, e.nationCode, e.intnlPhoneNo, (0, A.Z)(e, H));
                            window.history.replaceState((0, x.Z)({}, t), "")
                        }
                        return function() {
                            window.clearTimeout(ge.current.captchaTimeout), window.clearTimeout(ge.current.resizeTimeout), window.clearTimeout(ge.current.initAfterSuccessTimeout), window.clearTimeout(ge.current.retryCaptchaTimeout)
                        }
                    }), []), (0, i.useLayoutEffect)((function() {
                        y(n)
                    }), [n]), (0, i.useEffect)((function() {
                        o && Te()
                    }), [o]), (0, i.useEffect)((function() {
                        Ae.setTarget(), _ && w("")
                    }), [_]), (0, p.jsxs)("form", {
                        className: Q("email-login", {
                            "email-login--active": o
                        }),
                        onSubmit: xe,
                        children: [(0, p.jsx)("div", {
                            className: Q("email-login__input-row"),
                            children: (0, p.jsx)(C.ZP, {
                                placeholder: O("login.email.msg12"),
                                validate: C.zZ.Email,
                                value: I,
                                onChange: y,
                                label: O("login.email.msg01"),
                                renderType: C.Ve.Functional,
                                ref: function(e) {
                                    return de.current = e
                                },
                                reset: !0,
                                tabIndex: 1,
                                type: "text",
                                onKeyDown: function(e) {
                                    return "Enter" === e.key && e.preventDefault()
                                },
                                setFocus: le === q
                            })
                        }), (0, p.jsx)("div", {
                            className: Q("email-login__input-row"),
                            children: (0, p.jsx)(C.ZP, {
                                placeholder: O("login.email.msg10"),
                                type: C.nc.Password,
                                onChange: w,
                                value: L,
                                renderType: C.Ve.Functional,
                                label: O("login.email.msg02"),
                                ref: function(e) {
                                    return Ee.current = e
                                },
                                reset: !0,
                                tabIndex: 2,
                                onKeyDown: Se,
                                e2eType: "22",
                                maxLength: 64,
                                autoComplete: "off",
                                setFocus: le === J
                            })
                        }), D && (0, p.jsx)("div", {
                            className: Q("captcha"),
                            ref: me,
                            children: "g-recaptcha-response" === D ? (0, p.jsxs)("div", {
                                className: Q("captcha-google"),
                                children: [(0, p.jsx)(W, {
                                    onCaptchaChange: Oe
                                }), V && (0, p.jsx)("p", {
                                    className: Q("captcha-google__error-text"),
                                    children: he(V)
                                })]
                            }) : (0, p.jsxs)("div", {
                                className: Q("captcha-custom"),
                                children: [(0, p.jsx)("p", {
                                    className: Q("captcha-custom__text"),
                                    children: O("login.email.msg11")
                                }), (0, p.jsxs)("div", {
                                    className: Q("captcha-custom-box"),
                                    children: [(0, p.jsx)("div", {
                                        className: Q("captcha-custom-box__img"),
                                        children: (0, p.jsx)("img", {
                                            src: "data:image/png;base64,".concat(U),
                                            width: 197,
                                            height: 54,
                                            alt: "bithumb captcha"
                                        })
                                    }), (0, p.jsx)("button", {
                                        type: "button",
                                        className: Q("captcha-custom-box__button"),
                                        onClick: Te,
                                        children: O("button.msg36")
                                    })]
                                }), (0, p.jsx)(C.ZP, {
                                    renderType: C.Ve.Captcha,
                                    value: re,
                                    onChange: Oe,
                                    readOnly: ne,
                                    placeholder: O("login.email.msg09"),
                                    validFunc: function(e) {
                                        return "" === e && (Z(G.Empty), !0)
                                    },
                                    ref: function(e) {
                                        return fe.current = e
                                    },
                                    maxLength: 6,
                                    onKeyDown: Se
                                }), V && (0, p.jsx)("p", {
                                    className: Q("captcha--custom__error-text"),
                                    children: he(V)
                                })]
                            })
                        }), (0, p.jsx)("div", {
                            className: Q("email-login__button"),
                            children: (0, p.jsx)(E.ZP, {
                                className: Q({
                                    disabled: !Re()
                                }),
                                type: E.PD.DefaultNew,
                                color: E.n5.Primary,
                                size: E.VA.ExtraLarge,
                                disabled: !Re(),
                                children: O("login.msg01")
                            })
                        })]
                    })
                })),
                te = n(42863),
                ne = n(87700),
                ie = n(63163),
                ae = n(52160),
                oe = n(68837),
                re = ["id", "type", "loginType", "nationCode", "intnlPhoneNo"],
                se = l().bind({
                    "phone-login": "Phone_phone-login__DR2F-",
                    "phone-login--active": "Phone_phone-login--active__lyp-0",
                    "phone-login__input-title": "Phone_phone-login__input-title__N47fl",
                    "phone-login__input-row": "Phone_phone-login__input-row__0kBP2",
                    "phone-login__input-row--phone": "Phone_phone-login__input-row--phone__hHeDD",
                    "phone-login__nation-select": "Phone_phone-login__nation-select__6E74A",
                    "phone-login__phone-num": "Phone_phone-login__phone-num__qNg30",
                    "phone-login-confirm": "Phone_phone-login-confirm__DfDxO",
                    "phone-login-confirm__request-time": "Phone_phone-login-confirm__request-time__7KH-w",
                    "phone-login__confirm-time": "Phone_phone-login__confirm-time__ZhLgf",
                    "phone-login__button": "Phone_phone-login__button__6GjH5"
                }),
                ce = function(e, t) {
                    return "KOR" === e && t.length > 0 && "0" !== t.charAt(0) ? "0" + t : t
                },
                ue = (0, o.Pi)((function(e) {
                    var t, n, o, s = e.initialId,
                        u = e.active,
                        l = e.loginStepCb,
                        _ = e.loginTypeCb,
                        m = (0, a.G2)(),
                        f = m.localeService.locale,
                        g = m.modalService,
                        T = g.showModal,
                        O = g.visible,
                        S = g.updateParams,
                        R = m.sessionService,
                        v = R.setUserInfo,
                        N = R.setLogin,
                        b = m.httpService.post,
                        y = m.gaService.fnGASendEvent,
                        $ = m.service,
                        P = $.getAjaxSmsAuth,
                        L = $.getAjaxCellPhoneLogin,
                        w = (0, d.TH)().state,
                        M = (0, i.useRef)({
                            selectedCountry: {
                                nationCode: null !== (t = null === w || void 0 === w ? void 0 : w.nationCode) && void 0 !== t ? t : "KOR",
                                intnlPhoneNo: null !== (n = null === w || void 0 === w ? void 0 : w.intnlPhoneNo) && void 0 !== n ? n : "82"
                            },
                            allowClick: !0,
                            smsCheck: !1,
                            smsToken: ""
                        }),
                        k = (0, d.s0)(),
                        D = (0, i.useState)(""),
                        X = (0, r.Z)(D, 2),
                        j = X[0],
                        F = X[1],
                        U = (0, i.useState)(""),
                        K = (0, r.Z)(U, 2),
                        G = K[0],
                        B = K[1],
                        z = (0, i.useState)(!0),
                        W = (0, r.Z)(z, 2),
                        V = W[0],
                        Z = W[1],
                        H = (0, i.useRef)(),
                        q = (0, i.useRef)(),
                        J = (0, I.pg)(c.GLW.BEFORE_REQ),
                        Q = (0, r.Z)(J, 2),
                        ee = Q[0],
                        ue = Q[1],
                        le = (0, I.J7)(6e4, 1e3, "mm:ss", !1);
                    (0, i.useEffect)((function() {
                        0 === le.time && (ue(c.GLW.BEFORE_REREQ), le.setAction(!1))
                    }), [le.time]), (0, i.useEffect)((function() {
                        de.setTarget()
                    }), []), (0, i.useEffect)((function() {
                        if (null !== w && void 0 !== w && w.id) {
                            var e = w,
                                t = (e.id, e.type, e.loginType, e.nationCode, e.intnlPhoneNo, (0, A.Z)(e, re));
                            window.history.replaceState((0, x.Z)({}, t), "")
                        }
                    }), []);
                    var _e = function() {
                            if (M.current.allowClick) {
                                var e = G;
                                M.current.allowClick = !1, de.getInputValue(e)
                            }
                        },
                        de = Y((function(e) {
                            L({
                                authNo: e,
                                nationCode: M.current.selectedCountry.nationCode,
                                phoneNo: ce(M.current.selectedCountry.nationCode, j),
                                smsToken: M.current.smsToken
                            }).then((function(e) {
                                if (M.current.allowClick = !0, 200 !== e.status) switch (e.code) {
                                    case "member.fail.00008":
                                    case "member.fail.00018":
                                    case "member.fail.00052":
                                    case "member.fail.00001":
                                    case "member.fail.00004":
                                    case "member.fail.00058":
                                        T(h.o1X, {
                                            code: e.code,
                                            message: e.message,
                                            phoneNo: j,
                                            closeCb: function() {
                                                return _(0)
                                            }
                                        }), Ee(0);
                                        break;
                                    case "member.fail.00047":
                                    case "member.fail.00048":
                                    case "member.fail.00049":
                                        T(h.o1X, {
                                            code: e.code,
                                            message: e.message,
                                            phoneNo: j,
                                            closeCb: function() {
                                                return _(0)
                                            }
                                        });
                                        break;
                                    case "member.fail.00059":
                                    case "member.fail.00045":
                                        Ee(1), T(h.DzK, {
                                            message: e.message
                                        });
                                        break;
                                    case "member.fail.00009":
                                        Ee(2), T(h.DzK, {
                                            message: e.message
                                        });
                                        break;
                                    case "member.fail.00102":
                                        T(h.IEu);
                                        break;
                                    default:
                                        T(h.DzK, {
                                            message: e.message
                                        })
                                } else if (v(e.data), "06" === e.data.secondAuthMethod) {
                                    b("/oauth/token", {
                                        grant_type: "password",
                                        authNo: G,
                                        tempAccessToken: e.data.tempAccessToken,
                                        certPttnCd: e.data.secondAuthMethod
                                    }).then((function(e) {
                                        if (200 === e.status) {
                                            var t = e.data;
                                            t && (! function(e) {
                                                v(e);
                                                var t = window.returnUrl || window.returnUrl1;
                                                t ? k("/legacy".concat(t)) : N(!0)
                                            }(t), y("\ub85c\uadf8\uc778", "\ub85c\uadf8\uc778", "\ub85c\uadf8\uc778_\ud734\ub300\ud3f0\ubcf8\uc778\uc778\uc99d"))
                                        }
                                    }))
                                } else l(2)
                            }))
                        }), null === (o = q.current) || void 0 === o ? void 0 : o.$input.current),
                        Ee = function(e) {
                            switch (B(""), e) {
                                case 0:
                                    F(""), ue(c.GLW.BEFORE_REQ), Z(!0), le.setAction(!1);
                                    break;
                                case 1:
                                    Z(!0), ue(c.GLW.BEFORE_REQ), le.setAction(!1)
                            }
                        },
                        me = function(e) {
                            switch (e) {
                                case "confirm":
                                    return j.length > 2 && ee !== c.GLW.IN_REQ;
                                case "login":
                                    return 6 === G.length
                            }
                        };
                    return (0, i.useLayoutEffect)((function() {
                        F(s)
                    }), [s]), (0, p.jsxs)("div", {
                        className: se("phone-login", {
                            "phone-login--active": u
                        }),
                        children: [(0, p.jsx)("h3", {
                            className: se("phone-login__input-title"),
                            children: f("login.phone.msg01")
                        }), (0, p.jsxs)("div", {
                            className: se("phone-login__input-row--phone"),
                            children: [(0, p.jsx)("div", {
                                className: se("phone-login__nation-select"),
                                children: (0, p.jsx)(ne.Z, {
                                    type: ie.K.Number,
                                    modal: !1,
                                    onChange: function(e) {
                                        M.current.selectedCountry.nationCode = e.nationCode, M.current.selectedCountry.intnlPhoneNo = e.intnlPhoneNo
                                    },
                                    value: M.current.selectedCountry.nationCode
                                })
                            }), (0, p.jsx)("div", {
                                className: se("phone-login__phone-num"),
                                children: (0, p.jsx)(C.ZP, {
                                    type: te.nc.Tel,
                                    validate: te.zZ.Tel,
                                    inputTitle: f("login.phone.msg01"),
                                    placeholder: f("login.phone.msg02"),
                                    maxLength: 15,
                                    onChange: F,
                                    value: j,
                                    zeroStart: !0,
                                    renderType: te.Ve.Functional,
                                    className: "functional-input--type-button",
                                    reset: !0,
                                    ref: function(e) {
                                        return H.current = e
                                    },
                                    tabIndex: 3
                                })
                            }), (0, p.jsx)("div", {
                                className: se("phone-login-confirm"),
                                children: (0, p.jsx)(E.ZP, {
                                    className: se({
                                        disabled: !me("confirm")
                                    }),
                                    type: E.PD.Outline,
                                    size: E.VA.Large,
                                    onClick: function() {
                                        M.current.allowClick && (M.current.allowClick = !1, ee === c.GLW.BEFORE_REREQ && ue(c.GLW.EXPIRED), M.current.smsCheck = !0, P({
                                            nationCode: M.current.selectedCountry.nationCode,
                                            phoneNo: ce(M.current.selectedCountry.nationCode, j),
                                            ptype: "L",
                                            smsToken: ""
                                        }).then((function(e) {
                                            M.current.allowClick = !0, 200 === e.status ? (T(h.DzK, {
                                                message: f("login.phone.msg09"),
                                                modalBtn: {
                                                    feature: ae.y_.CUSTOM,
                                                    callback: function() {
                                                        var e, t;
                                                        return null === (e = q.current) || void 0 === e || null === (t = e.$input.current) || void 0 === t ? void 0 : t.focus()
                                                    }
                                                }
                                            }), M.current.smsToken = e.data.smsToken, Z(!1), le.setAction(!0), ue(c.GLW.IN_REQ)) : T(h.DzK, {
                                                message: f("login.phone.msg08")
                                            })
                                        })))
                                    },
                                    disabled: !me("confirm"),
                                    children: ee > c.GLW.BEFORE_REQ ? (0, p.jsxs)(p.Fragment, {
                                        children: [f("button.msg43"), (0, p.jsx)("span", {
                                            className: se("phone-login-confirm__request-time"),
                                            children: ee === c.GLW.IN_REQ && le.formattedTime
                                        })]
                                    }) : (0, p.jsx)(p.Fragment, {
                                        children: f("button.msg06")
                                    })
                                })
                            })]
                        }), (0, p.jsx)("div", {
                            className: se("phone-login__input-row"),
                            children: (0, p.jsx)(C.ZP, {
                                type: te.nc.Number,
                                validate: te.zZ.Number,
                                maxLength: 6,
                                onChange: B,
                                value: G,
                                zeroStart: !0,
                                disabled: V,
                                placeholder: f("login.phone.msg06"),
                                subLabelTemplate: ee >= c.GLW.BEFORE_REREQ ? (0, p.jsx)("strong", {
                                    className: se("phone-login__confirm-time"),
                                    children: f("login.msg05", {
                                        tag: (0, p.jsx)(oe.Z, {
                                            initialValue: 18e4,
                                            intervalTime: 1e3,
                                            format: "m:ss",
                                            finishCb: function() {
                                                O(h.DzK) ? S(h.DzK, {
                                                    message: f("login.phone.msg10"),
                                                    modalBtn: {
                                                        text: f("button.msg11"),
                                                        feature: ae.y_.CUSTOM,
                                                        callback: function() {
                                                            return Ee(1)
                                                        }
                                                    }
                                                }) : T(h.DzK, {
                                                    message: f("login.phone.msg10"),
                                                    modalBtn: {
                                                        text: f("button.msg11"),
                                                        feature: ae.y_.CUSTOM,
                                                        callback: function() {
                                                            return Ee(1)
                                                        }
                                                    }
                                                })
                                            }
                                        })
                                    })
                                }) : void 0,
                                label: f("login.phone.msg03"),
                                renderType: te.Ve.Functional,
                                reset: !0,
                                ref: function(e) {
                                    return q.current = e
                                },
                                tabIndex: 4,
                                onKeyDown: function(e) {
                                    "Enter" === e.key && me("login") && _e()
                                },
                                e2eType: "22"
                            })
                        }), (0, p.jsx)("div", {
                            className: se("phone-login__button"),
                            children: (0, p.jsx)(E.ZP, {
                                className: se({
                                    disabled: !me("login")
                                }),
                                type: E.PD.DefaultNew,
                                color: E.n5.Primary,
                                size: E.VA.ExtraLarge,
                                onClick: _e,
                                disabled: !me("login"),
                                children: f("button.msg01")
                            })
                        })]
                    })
                })),
                le = {
                    auth: "SecondAuth_auth__E1gtp",
                    auth__title: "SecondAuth_auth__title__pwaqK",
                    auth__notice: "SecondAuth_auth__notice__hGjbG",
                    "auth__notice--error": "SecondAuth_auth__notice--error__sd-Z7",
                    "auth__phone-verify": "SecondAuth_auth__phone-verify__ggXFs",
                    "auth__input-row": "SecondAuth_auth__input-row__+CjP9",
                    auth__button: "SecondAuth_auth__button__NQzZl",
                    auth__alternative: "SecondAuth_auth__alternative__Ww8R0"
                },
                _e = n(6283),
                de = n(6814),
                Ee = l().bind({
                    "alternative-auth": "AlternativeAuth_alternative-auth__NN60F",
                    "alternative-auth__button": "AlternativeAuth_alternative-auth__button__PMHK7"
                }),
                me = (0, o.Pi)((function(e) {
                    var t = e.secondAuthMethod,
                        n = e.fnAjaxAuthLogin,
                        i = (0, de.ZP)(de.R5.LOGIN, de.dM.EMERGENCY_LOGIN).openKCBWindow,
                        o = (0, a.G2)(),
                        r = o.modalService.showModal,
                        s = o.localeService.locale;
                    return (0, p.jsx)("div", {
                        className: Ee("alternative-auth"),
                        children: (0, p.jsx)(E.ZP, {
                            className: Ee("alternative-auth__button"),
                            onClick: function() {
                                r(h.DzK, {
                                    message: s("page.login.auth.msg008", {
                                        tag: (0, p.jsx)("br", {})
                                    }),
                                    modalBtn: {
                                        text: s("button.msg32"),
                                        feature: ae.y_.CLOSE
                                    },
                                    modalBtn1: {
                                        text: s("button.msg101"),
                                        feature: ae.y_.CUSTOM,
                                        callback: function() {
                                            i((function() {
                                                n(void 0, !0)
                                            }))
                                        }
                                    }
                                })
                            },
                            children: s("02" === t ? "page.login.auth.msg011" : "page.login.auth.msg010")
                        })
                    })
                })),
                fe = l().bind(le),
                ge = (0, o.Pi)((function(e) {
                    var t = e.authValue,
                        n = e.setAuthValue,
                        o = e.fnAjaxAuthLogin,
                        s = (0, a.G2)().localeService.locale,
                        c = (0, i.useState)(null),
                        u = (0, r.Z)(c, 2),
                        l = u[0],
                        _ = u[1],
                        d = (0, i.useRef)(null);
                    (0, i.useEffect)((function() {
                        d.current && d.current.focus()
                    }), []);
                    var m = function() {
                            return 6 === t.length
                        },
                        f = function() {
                            o((function(e) {
                                _(e.message)
                            }))
                        };
                    return (0, p.jsxs)(p.Fragment, {
                        children: [(0, p.jsx)("h2", {
                            className: fe("auth__title"),
                            children: s("page.login.auth.msg002")
                        }), l ? (0, p.jsx)("p", {
                            className: fe("auth__notice", "auth__notice--error"),
                            children: s("page.login.auth.msg003")
                        }) : (0, p.jsx)("p", {
                            className: fe("auth__notice"),
                            children: s("page.login.auth.msg004", {
                                tag: (0, p.jsx)("br", {})
                            })
                        }), (0, p.jsx)("div", {
                            className: fe("auth__input-row"),
                            children: (0, p.jsx)(_e.ZP, {
                                type: _e.nc.Number,
                                inputTitle: s("page.login.auth.msg020"),
                                align: _e.sC.Center,
                                renderType: _e.Ve.Functional,
                                reset: !1,
                                validate: _e.zZ.Number,
                                value: t,
                                onChange: n,
                                maxLength: 6,
                                zeroStart: !0,
                                onKeyDown: function(e) {
                                    m() && "Enter" === e.key && f()
                                },
                                ref: d,
                                status: l ? _e.Wm.Error : null
                            })
                        }), (0, p.jsx)("div", {
                            className: fe("auth__button"),
                            children: (0, p.jsx)(E.ZP, {
                                type: E.PD.DefaultNew,
                                color: E.n5.Primary,
                                size: E.VA.ExtraLarge,
                                onClick: f,
                                disabled: !m(),
                                className: fe({
                                    disabled: !m()
                                }),
                                children: s("button.msg11")
                            })
                        }), (0, p.jsx)("div", {
                            className: fe("auth__alternative"),
                            children: (0, p.jsx)(me, {
                                secondAuthMethod: "02",
                                fnAjaxAuthLogin: o
                            })
                        })]
                    })
                })),
                he = n(21742),
                pe = l().bind(le),
                Te = (0, o.Pi)((function(e) {
                    var t = e.authValue,
                        n = e.setAuthValue,
                        o = e.fnAjaxAuthLogin,
                        s = (0, a.G2)(),
                        u = s.localeService.locale,
                        l = s.modalService.showModal,
                        _ = (0, i.useState)(he.K2.BEFORE_CHECK),
                        d = (0, r.Z)(_, 2),
                        m = d[0],
                        f = d[1],
                        g = (0, i.useState)(c.GLW.BEFORE_REQ),
                        T = (0, r.Z)(g, 2),
                        O = T[0],
                        S = T[1],
                        R = (0, i.useState)(null),
                        v = (0, r.Z)(R, 2),
                        x = v[0],
                        A = v[1],
                        C = function() {
                            return O > c.GLW.EXPIRED && 6 === t.length && m === he.K2.VALID
                        },
                        N = function(e) {
                            S(e || c.GLW.BEFORE_REQ), f(he.K2.BEFORE_CHECK), n(""), A(null)
                        },
                        b = function() {
                            o((function(e) {
                                return function(e) {
                                    switch (e.code) {
                                        case "member.fail.00086":
                                            N(c.GLW.EXPIRED), l(h.DzK, {
                                                message: e.message
                                            });
                                            break;
                                        case "member.fail.00085":
                                            S(c.GLW.EXPIRED), N(c.GLW.BLOCKED), l(h.DzK, {
                                                message: e.message
                                            });
                                            break;
                                        default:
                                            A(e.message), f(he.K2.INVALID)
                                    }
                                }(e)
                            }))
                        };
                    return (0, p.jsxs)(p.Fragment, {
                        children: [(0, p.jsx)("h2", {
                            className: pe("auth__title"),
                            children: u("page.login.auth.msg001")
                        }), (0, p.jsx)("p", {
                            className: pe("auth__notice"),
                            children: u("page.login.auth.msg019")
                        }), (0, p.jsx)("div", {
                            className: pe("auth__phone-verify"),
                            children: (0, p.jsx)(he.ZP, {
                                serviceType: he.dG.LOGIN,
                                authValue: t,
                                setAuthValue: n,
                                setSmsStatus: S,
                                smsStatus: O,
                                isValidAuth: m,
                                setIsValidAuth: f,
                                handleKeyPress: function(e) {
                                    C() && "Enter" === e.key && b()
                                },
                                authErrorMsg: x,
                                setAuthErrorMsg: A
                            })
                        }), (0, p.jsx)("div", {
                            className: pe("auth__button"),
                            children: (0, p.jsx)(E.ZP, {
                                type: E.PD.DefaultNew,
                                color: E.n5.Primary,
                                size: E.VA.ExtraLarge,
                                onClick: b,
                                disabled: !C(),
                                className: pe({
                                    disabled: !C()
                                }),
                                children: u("button.msg11")
                            })
                        }), (0, p.jsx)("div", {
                            className: pe("auth__alternative"),
                            children: (0, p.jsx)(me, {
                                secondAuthMethod: "05",
                                fnAjaxAuthLogin: o
                            })
                        })]
                    })
                })),
                Oe = l().bind(le),
                Se = (0, o.Pi)((function(e) {
                    var t, n = e.loginStepCb,
                        o = (0, a.G2)(),
                        s = o.sessionService,
                        c = s.getUserInfo,
                        u = s.setLogin,
                        l = s.setUserInfo,
                        _ = o.httpService.post,
                        E = o.modalService.showModal,
                        m = o.gaService.fnGASendEvent,
                        f = (0, d.s0)(),
                        g = (0, i.useRef)(null !== (t = null === c || void 0 === c ? void 0 : c.secondAuthMethod) && void 0 !== t ? t : "05"),
                        T = (0, i.useState)(""),
                        O = (0, r.Z)(T, 2),
                        S = O[0],
                        R = O[1],
                        v = function(e, t) {
                            _("/oauth/token", {
                                grant_type: "password",
                                authNo: t ? "000000" : S,
                                tempAccessToken: c && c.tempAccessToken,
                                certPttnCd: t ? "07" : g.current
                            }, !0).then((function(t) {
                                if (200 !== t.status) switch (t.code) {
                                    case "member.fail.00006":
                                        E(h.DzK, {
                                            message: t.message
                                        });
                                        break;
                                    case "member.fail.00078":
                                        E(h.DzK, {
                                            message: t.message,
                                            modalBtn: {
                                                feature: ae.y_.CUSTOM,
                                                callback: function() {
                                                    n && n(1)
                                                }
                                            }
                                        });
                                        break;
                                    case "member.fail.00102":
                                        E(h.IEu);
                                        break;
                                    default:
                                        e && e(t)
                                } else {
                                    var i = t.data;
                                    i && (! function(e) {
                                        l(e);
                                        var t = window.returnUrl || window.returnUrl1;
                                        t ? f("/legacy".concat(t), {
                                            replace: !0
                                        }) : u(!0)
                                    }(i), m("\ub85c\uadf8\uc778", "\ub85c\uadf8\uc778", "\ub85c\uadf8\uc778_2\ucc28"))
                                }
                            }))
                        };
                    return (0, p.jsx)("div", {
                        className: Oe("auth"),
                        children: "02" === g.current ? (0, p.jsx)(ge, {
                            authValue: S,
                            setAuthValue: R,
                            fnAjaxAuthLogin: v
                        }) : (0, p.jsx)(Te, {
                            authValue: S,
                            setAuthValue: R,
                            fnAjaxAuthLogin: v
                        })
                    })
                })),
                Re = n(61879),
                ve = n(11792),
                xe = n(36296),
                Ae = n(52751),
                Ce = n(93493),
                Ne = l().bind({
                    "astx-loading": "AstxLoading_astx-loading__xpuzd",
                    "astx-loading__title": "AstxLoading_astx-loading__title__d0QHi",
                    "astx-loading__text": "AstxLoading_astx-loading__text__oD+4B",
                    "astx-loading__loading-box": "AstxLoading_astx-loading__loading-box__3R-51"
                }),
                be = (0, o.Pi)((function() {
                    var e = (0, a.G2)(),
                        t = e.modalService.visible,
                        n = e.localeService.locale;
                    return (0, p.jsx)(m.ZP, {
                        visible: t(h.iNs),
                        type: m.y7.Alert,
                        hideButton: !0,
                        children: (0, p.jsxs)("div", {
                            id: "loadingASTx",
                            className: Ne("astx-loading"),
                            children: [(0, p.jsx)("h3", {
                                className: Ne("astx-loading__title"),
                                children: n("astx.loading.msg01", {
                                    tag: (0, p.jsx)("br", {})
                                })
                            }), (0, p.jsx)("p", {
                                className: Ne("astx-loading__text"),
                                children: n("astx.loading.msg02", {
                                    tag: (0, p.jsx)("br", {})
                                })
                            }), (0, p.jsx)("div", {
                                className: Ne("astx-loading__loading-box"),
                                children: (0, p.jsx)(Ce.k_, {
                                    type: Ce.G0.Horizontal
                                })
                            })]
                        })
                    })
                })),
                Ie = l().bind({
                    "security-program": "SecurityProgram_security-program__FaaIo",
                    "security-program__checkbox": "SecurityProgram_security-program__checkbox__V7aET"
                }),
                ye = function() {
                    var e = (0, i.useState)(!1),
                        t = (0, r.Z)(e, 2),
                        o = t[0],
                        c = t[1],
                        u = (0, i.useRef)(),
                        l = (0, a.G2)(),
                        _ = l.service.setOnSecure,
                        E = l.modalService,
                        m = E.showModal,
                        f = E.hideModal,
                        g = l.localeService.locale,
                        T = l.httpService.get,
                        O = (0, d.s0)(),
                        S = (0, i.useState)(!1),
                        R = (0, r.Z)(S, 2),
                        v = R[0],
                        x = R[1],
                        A = (0, i.useRef)({
                            bIsKo: !1,
                            bOldie: !1
                        });
                    (0, i.useEffect)((function() {
                        C(), "Y" === s.pR.get("astxExecution") ? (Z.z.ACTIVE = !0, c(!0), _(!0), N()) : (Z.z.ACTIVE = !1, c(!1), _(!1))
                    }), []);
                    var C = function() {
                            var e = (0, ve.Z)((0, Re.Z)().mark((function e() {
                                var t, n, i;
                                return (0, Re.Z)().wrap((function(e) {
                                    for (;;) switch (e.prev = e.next) {
                                        case 0:
                                            if (t = (0, Ae.qY)()) {
                                                e.next = 3;
                                                break
                                            }
                                            return e.abrupt("return");
                                        case 3:
                                            if (!(n = navigator.language || navigator.languages[0]) || 0 !== n.indexOf("ko")) {
                                                e.next = 8;
                                                break
                                            }
                                            A.current.bIsKo = !0, e.next = 13;
                                            break;
                                        case 8:
                                            if (n) {
                                                e.next = 13;
                                                break
                                            }
                                            return e.next = 11, T("/v1/members/login/browser/language").then((function(e) {
                                                return 200 !== e.status ? "" : e.data.headerAcceptLanguage
                                            }));
                                        case 11:
                                            0 === e.sent.indexOf("ko") && (A.current.bIsKo = !0);
                                        case 13:
                                            "" !== (i = t.version) && "ie" === i && Number(i) < 9 && (A.current.bOldie = !0), A.current.bIsKo && t.os && t.os.indexOf("Window") > -1 && !A.current.bOldie ? x(!0) : (s.pR.remove("astxExecution"), x(!1));
                                        case 16:
                                        case "end":
                                            return e.stop()
                                    }
                                }), e)
                            })));
                            return function() {
                                return e.apply(this, arguments)
                            }
                        }(),
                        N = function() {
                            window.location.href.indexOf("ahnlab") > -1 ? I() : b()
                        },
                        b = function() {
                            m(h.iNs), u.current = window.setTimeout((function() {
                                f(h.iNs), window.clearTimeout(u.current)
                            }), 4e3), Promise.resolve().then(n.bind(n, 20105)).then((function(e) {
                                var t = e.$ASTX2,
                                    n = e.$ASTX2_CONST;
                                t.init((function() {
                                    t.initNonE2E()
                                }), (function() {
                                    var e = t.getLastError();
                                    s.pR.set("astxExecution", "N", 9999, "/"), e === n.ERROR_NOTINST ? (m(h.DzK, {
                                        message: g("security.msg02", {
                                            tag: (0, p.jsx)("br", {})
                                        }),
                                        closeCb: function() {
                                            return c(!1)
                                        },
                                        modalBtn: {
                                            text: g("button.msg32"),
                                            feature: ae.y_.CLOSE
                                        },
                                        modalBtn1: {
                                            text: g("button.msg11"),
                                            feature: ae.y_.CUSTOM,
                                            callback: y
                                        }
                                    }), f(h.iNs), Z.z.ACTIVE = !1) : Z.z.errorAbort(e)
                                }))
                            }))
                        },
                        I = function() {
                            Promise.resolve().then(n.bind(n, 20105)).then((function(e) {
                                e.$ASTX2.init((function() {
                                    m(h.DzK, {
                                        message: g("security.msg01"),
                                        modalBtn: {
                                            text: g("button.msg11"),
                                            feature: ae.y_.CLOSE
                                        }
                                    })
                                }))
                            }))
                        },
                        y = function() {
                            O("/legacy/customer_support/ahnlab")
                        };
                    return (0, p.jsx)(p.Fragment, {
                        children: v && (0, p.jsxs)("div", {
                            className: Ie("security-program"),
                            children: [(0, p.jsx)(xe.ZP, {
                                label: g("security.msg03"),
                                type: xe.Su.ToggleButton,
                                onChange: function() {
                                    c((function(e) {
                                        return !e
                                    }));
                                    var e = s.pR.get("astxExecution");
                                    o ? (s.pR.set("astxExecution", "N", 9999, "/"), Z.z.ACTIVE = !1) : "Y" === e ? s.pR.set("astxExecution", "N", 9999, "/") : (s.pR.set("astxExecution", "Y", 9999, "/"), Z.z.ACTIVE = !0, N()), _(Z.z.ACTIVE)
                                },
                                checked: o,
                                className: Ie("security-program__checkbox")
                            }), (0, p.jsx)(be, {})]
                        })
                    })
                },
                $e = l().bind({
                    login: "Login_login__jJiFM",
                    "login-inner": "Login_login-inner__7baKk",
                    "login-box": "Login_login-box__CCfEx",
                    "login-box__title": "Login_login-box__title__puLxQ",
                    "login-box__notice": "Login_login-box__notice__gUhhQ",
                    "login-box-safe-link": "Login_login-box-safe-link__jGaQP",
                    "login-box-safe-link__text": "Login_login-box-safe-link__text__iZWga",
                    "login-box-safe-link__text-green": "Login_login-box-safe-link__text-green__qzilU",
                    "login-box-toggle": "Login_login-box-toggle__msFxl",
                    "login-box-toggle__button": "Login_login-box-toggle__button__gBfTc",
                    "login-box-link": "Login_login-box-link__0elZW",
                    "login-box-link__button": "Login_login-box-link__button__Vqr5f",
                    "email-login-box": "Login_email-login-box__osFqJ",
                    active: "Login_active__BwVQb",
                    "phone-login-box": "Login_phone-login-box__wjb-N"
                }),
                Pe = (0, o.Pi)((function() {
                    var e = (0, a.G2)(),
                        t = e.localeService.locale,
                        n = e.sessionService.getUserInfo,
                        o = e.gaService.fnGASendEvent,
                        u = (0, d.TH)(),
                        l = u.search,
                        m = u.state,
                        f = (0, i.useState)(c.EzU.EMAIL),
                        g = (0, r.Z)(f, 2),
                        h = g[0],
                        T = g[1],
                        S = (0, i.useState)(1),
                        R = (0, r.Z)(S, 2),
                        x = R[0],
                        A = R[1],
                        C = (0, i.useState)(""),
                        N = (0, r.Z)(C, 2),
                        b = N[0],
                        I = N[1];
                    return (0, i.useLayoutEffect)((function() {
                        var e = function() {
                            var e = function(e) {
                                    return !!(e && "object" === typeof e && "loginType" in e && "id" in e && e.id) && !(0 !== e.loginType && 1 !== e.loginType && !e.id)
                                },
                                t = window.sessionStorage.getItem("loginInfo");
                            if (t) {
                                var n = (0, s.vt)(t);
                                return n ? (window.sessionStorage.removeItem("loginInfo"), {
                                    loginType: n && n.loginType === c.EzU.PHONE ? c.EzU.PHONE : c.EzU.EMAIL,
                                    id: n.id || ""
                                }) : {
                                    loginType: c.EzU.EMAIL,
                                    id: ""
                                }
                            }
                            return {
                                loginType: e(m) ? m.loginType : c.EzU.EMAIL,
                                id: e(m) ? m.id : ""
                            }
                        }();
                        T(e.loginType), I(e.id)
                    }), []), (0, i.useEffect)((function() {
                        if ("" !== l) {
                            var e = _.parse(l).reurl;
                            if (e)
                                if ("legacy" === e && "" !== document.referrer) {
                                    var t = window.location.hostname;
                                    window.returnUrl1 = document.referrer.substr(document.referrer.indexOf(t) + t.length)
                                } else window.returnUrl = e
                        }
                    }), []), (0, p.jsxs)("div", {
                        className: $e("login"),
                        children: [(0, p.jsx)("div", {
                            className: $e("login-inner"),
                            children: n && n.tempAccessToken && 2 === x ? (0, p.jsx)(Se, {
                                loginStepCb: A
                            }) : (0, p.jsxs)(p.Fragment, {
                                children: [(0, p.jsxs)("div", {
                                    className: $e("login-box"),
                                    children: [(0, p.jsx)("h2", {
                                        className: $e("login-box__title"),
                                        children: t("login.msg01")
                                    }), (0, p.jsx)("p", {
                                        className: $e("login-box__notice"),
                                        children: t("login.msg04")
                                    }), (0, p.jsx)("div", {
                                        className: $e("login-box-safe-link"),
                                        children: (0, p.jsxs)("p", {
                                            className: $e("login-box-safe-link__text"),
                                            children: [(0, p.jsx)("span", {
                                                className: $e("login-box-safe-link__text-green"),
                                                children: "https://"
                                            }), "www.bithumb.com"]
                                        })
                                    }), (0, p.jsx)("div", {
                                        className: $e("login-box-toggle"),
                                        children: h === c.EzU.EMAIL ? (0, p.jsx)(E.ZP, {
                                            className: $e("login-box-toggle__button"),
                                            onClick: function() {
                                                return T(1)
                                            },
                                            children: t("login.msg02")
                                        }) : (0, p.jsx)(E.ZP, {
                                            className: $e("login-box-toggle__button"),
                                            onClick: function() {
                                                return T(0)
                                            },
                                            children: t("login.msg03")
                                        })
                                    }), (0, p.jsx)(ee, {
                                        initialId: b,
                                        active: h === c.EzU.EMAIL,
                                        loginStepCb: A
                                    }), (0, p.jsx)(ue, {
                                        initialId: b,
                                        active: h === c.EzU.PHONE,
                                        loginStepCb: A,
                                        loginTypeCb: T
                                    }), (0, p.jsxs)("div", {
                                        className: $e("login-box-link"),
                                        children: [(0, p.jsx)(E.ZP, {
                                            className: $e("login-box-link__button"),
                                            to: "/member/find-id",
                                            onClick: function() {
                                                o("\ub85c\uadf8\uc778", "\uc544\uc774\ub514 \u2219 \ube44\ubc00\ubc88\ud638 \ucc3e\uae30", "\uc544\uc774\ub514 \u2219 \ube44\ubc00\ubc88\ud638 \ucc3e\uae30")
                                            },
                                            "data-testid": "find-id-button",
                                            children: t("button.msg03")
                                        }), (0, p.jsx)(E.ZP, {
                                            className: $e("login-box-link__button"),
                                            to: "/legacy/member_operation/join",
                                            onClick: function() {
                                                o("\ub85c\uadf8\uc778", "\ud68c\uc6d0\uac00\uc785", "\ud68c\uc6d0\uac00\uc785")
                                            },
                                            "data-testid": "sign-up-button",
                                            children: t("button.msg04")
                                        })]
                                    })]
                                }), (0, p.jsx)(ye, {})]
                            })
                        }), (0, p.jsx)(O, {}), (0, p.jsx)(v, {})]
                    })
                })),
                Le = n(24214),
                we = n(79311),
                Me = n(78083),
                ke = n(18694),
                De = function() {
                    function e(t) {
                        var n = this;
                        (0, we.Z)(this, e), this.httpService = void 0, this.onSecure = !1, this.setOnSecure = function(e) {
                            n.onSecure = e
                        }, this.getAjaxSmsAuth = function(e) {
                            return n.httpService.post("/v1/members/sms-auth", e, !0)
                        }, this.getAjaxCellPhoneLogin = function(e) {
                            return n.httpService.post("/v1/members/login/app/cellphone", e, !0)
                        }, this.setAjaxResetCancel = function(e) {
                            return n.httpService.post("/v1/members/reset-cancel", e)
                        }, this.getAjaxEmailLogin = function(e) {
                            return n.httpService.post("/v1/members/login/web/email", e, !0)
                        }, this.getAjaxCaptchaInfo = function() {
                            return n.httpService.get("/v1/members/login/captcha")
                        }, this.httpService = t.httpService, (0, ke.rC)(this, {
                            onSecure: ke.LO,
                            getOnSecure: ke.Fl,
                            setOnSecure: ke.aD
                        })
                    }
                    return (0, Me.Z)(e, [{
                        key: "getOnSecure",
                        get: function() {
                            return this.onSecure
                        }
                    }]), e
                }(),
                Xe = (0, a.FU)((0, o.Pi)((function() {
                    var e = (0, a.G2)().sessionService.login;
                    return (0, p.jsx)(Le.Z, {
                        auth: {
                            allow: e,
                            reverse: !0
                        },
                        children: (0, p.jsx)(Pe, {})
                    })
                })), {
                    service: De
                })
        },
        20105: function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {
            __webpack_require__.r(__webpack_exports__), __webpack_require__.d(__webpack_exports__, {
                $ASTX2: function() {
                    return $ASTX2
                },
                $ASTX2_COMM: function() {
                    return $ASTX2_COMM
                },
                $ASTX2_CONST: function() {
                    return $ASTX2_CONST
                },
                $ASTX2_E2E: function() {
                    return $ASTX2_E2E
                },
                $ASTX2_MLi: function() {
                    return $ASTX2_MLi
                },
                $_astxu: function() {
                    return $_astxu
                }
            });
            var _astx2_custom__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(98236),
                $ASTX2_CONST = {
                    ERROR_SUCCESS: 0,
                    ERROR_FAILED: 101,
                    ERROR_NOINIT: 102,
                    ERROR_NOTINST: 103,
                    ERROR_NOTSUPPORTED: 104,
                    ERROR_NOCONNECT: 105,
                    ERROR_NCK: 106,
                    ERROR_ERR: 107,
                    ERROR_NSP: 108,
                    ERROR_PARAM: 109,
                    ERROR_EXCESS: 110,
                    ERROR_NEEDUPDATE: 111,
                    OPTION_NONE2E: 1,
                    OPTION_AUTOFOCUS: 2,
                    OPTION_AUTOSETTEXT: 4,
                    OPTION_E2EFORM: 8,
                    OPTION_E2EFORM_NOENC: 16,
                    OPTION_E2EFORM_ALLTRIP: 32,
                    OPTION_E2EFORM_ONLY: 64,
                    OPTION_E2EFORM_TAGSET: 128,
                    OPTION_NONE2E_ALG: 256,
                    OPTION_NOSPTDUMMY: 512,
                    OPTION_FIXEDTYPE: 1024,
                    PROTECT_AK: 1,
                    PROTECT_FW: 2,
                    SERVICE_AK: 1,
                    DEBUG_NOALIVE: 1,
                    LOCAL_INIT_HTTP: 1,
                    INTERVAL_ALIVE: 5e3,
                    E2ETYPE_EXCLUDE: -1,
                    E2ETYPE_NONE: 0,
                    E2ETYPE_CERT1: 1,
                    E2ETYPE_CERT2: 2,
                    E2ETYPE_SDK: 11,
                    E2ETYPE_PLAIN1: 21,
                    E2ETYPE_PLAIN2: 22,
                    PAGEID: "",
                    BROWSER_TYPE: "",
                    BROWSER_VER: "",
                    E2EFORM_TAIL: !0,
                    E2EFORM_INIT: "_e2e_forminit",
                    E2EFORM_TAG1: "_e2e_1__",
                    E2EFORM_TAG2: "_e2e_2__",
                    E2EFORM_TAG1_PWD: "_e2e_1_pwd__",
                    E2EFORM_TAG2_PWD: "_e2e_2_pwd__",
                    getErrno: function(e) {
                        return "NCK" == e ? this.ERROR_NCK : "ERR" == e ? this.ERROR_ERR : "NSP" == e ? this.ERROR_NSP : this.ERROR_FAILED
                    },
                    _get_browser_version: function(e) {
                        var t = 0;
                        if (0 <= e.indexOf("Edge/") && 0 < (t = parseInt(e.split("Edge/")[1]))) return "EG" + t;
                        if (0 <= e.indexOf("MSIE")) {
                            if (0 < (t = parseInt(e.split("MSIE")[1]))) return "IE" + t
                        } else if (0 <= e.indexOf("Trident") && (0 <= e.indexOf("rv:") ? t = parseInt(e.split("rv:")[1]) : 0 <= e.indexOf("IE") && (t = parseInt(e.split("IE")[1])), 0 < t)) return "IE" + t;
                        return 0 <= e.indexOf("OPR/") && 0 < (t = parseInt(e.split("OPR/")[1])) ? "OP" + t : 0 <= e.indexOf("Firefox/") && 0 < (t = parseInt(e.split("Firefox/")[1])) ? "FF" + t : 0 <= e.indexOf("Chrome/") && 0 < (t = parseInt(e.split("Chrome/")[1])) ? "CR" + t : 0 <= e.indexOf("AppleWebKit") && 0 <= e.indexOf("Version/") && 0 < (t = parseInt(e.split("Version/")[1])) ? "SF" + t : "OT0"
                    },
                    init: function() {
                        this.PAGEID = (new Date).getTime(), this.PAGEID -= Math.floor(100 * Math.random()), this.BROWSER_VER = this._get_browser_version(navigator.userAgent), this.BROWSER_TYPE = this.BROWSER_VER.substring(0, 2)
                    }
                };
            $ASTX2_CONST.init();
            var $ASTX2_COMM = {
                    mLocalServerURL: "",
                    mErrorAbortFlag: !1,
                    mDegugFlags: 0,
                    setDegugFlags: function(e) {
                        this.mDegugFlags |= e
                    },
                    getDegugFlags: function() {
                        return this.mDegugFlags
                    },
                    isEnable: function() {
                        return 0 < this.mLocalServerURL.length
                    },
                    uninit: function() {
                        this.mLocalServerURL = ""
                    },
                    setLocalFlags: function(e, t, n) {
                        null == n && (n = 3650), $_astxu.setCookie("astx2_" + e, t, n, "/")
                    },
                    getLocalFlags: function(e) {
                        return e = $_astxu.getCookie("astx2_" + e), parseInt(e) || 0
                    },
                    errorAbort: function(e) {
                        1 != this.mErrorAbortFlag && (this.mErrorAbortFlag = !0, _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.errorAbort(e))
                    }
                },
                $_astxu = {
                    mJsonpSequence: 0,
                    jsonQstr: function(e) {
                        var t, n = [];
                        for (t in e) e.hasOwnProperty(t) && n.push(encodeURIComponent(t) + "=" + encodeURIComponent(e[t]));
                        return n.join("&")
                    },
                    jsonParse: function jsonParse(a) {
                        return "object" == typeof window.JSON && "function" == typeof window.JSON.parse ? window.JSON.parse(a) : "function" == typeof jQuery && "function" == typeof jQuery.parseJSON ? jQuery.parseJSON(a) : eval("(" + a + ")")
                    },
                    _send_jsonp_ajax: function(e) {
                        try {
                            jQuery.ajax({
                                url: e.src,
                                timeout: e.timeout,
                                cache: !1,
                                crossDomain: !0,
                                dataType: "jsonp",
                                contentType: "application/javascript",
                                success: function(t, n, i) {
                                    e.onSuccess(t)
                                },
                                error: function(t, n, i) {
                                    e.onFailure()
                                },
                                complete: function(e, t) {}
                            })
                        } catch (t) {
                            e.onFailure()
                        }
                    },
                    _send_jsonp_dom: function(e) {
                        var t = document.createElement("script");
                        t.type = "text/javascript", t.id = e.callback, t.async = e.async, t.src = e.src;
                        var n = document.getElementsByTagName("body")[0];
                        if (null != n || null != (n = document.getElementsByTagName("head")[0])) {
                            var i = window.setTimeout((function() {
                                window[e.callback] = function() {};
                                try {
                                    var t = document.getElementById(e.callback);
                                    t && n.removeChild(t)
                                } catch (i) {}
                                e.onFailure()
                            }), e.timeout);
                            window[e.callback] = function(t) {
                                window.clearTimeout(i);
                                try {
                                    var a = document.getElementById(e.callback);
                                    a && n.removeChild(a)
                                } catch (o) {}
                                e.onSuccess(t)
                            }, n.appendChild(t)
                        }
                    },
                    sendJsonp: function(e) {
                        if (null == e.data && (e.data = {}), null == e.callback && (e.callback = "jsonpCallback" + $_astxu.rnd()), null == e.timeout && (e.timeout = 5e3), null == e.async && (e.async = !0), null == e.seq && (e.seq = !1), null == e.onSuccess && (e.onSuccess = function(e) {}), null == e.onFailure && (e.onFailure = function() {}), e.src = 1 == $ASTX2.mUseJQuery ? e.url + "?v=3" : e.url + "?v=2&callback=" + e.callback, 1 == e.seq) {
                            var t = ++this.mJsonpSequence;
                            e.src += "&seq=" + t, 2147483647 <= this.mJsonpSequence && (this.mJsonpSequence = 0)
                        }
                        0 < (t = this.jsonQstr(e.data)).length && (e.src += "&" + t), 1 == $ASTX2.mUseJQuery ? this._send_jsonp_ajax(e) : this._send_jsonp_dom(e)
                    },
                    _get_xhr_object: function() {
                        if ("undefined" !== typeof XMLHttpRequest) try {
                            return new XMLHttpRequest
                        } catch (e) {}
                        try {
                            return new ActiveXObject("Microsoft.XMLHTTP")
                        } catch (t) {}
                        return null
                    },
                    sendAjax: function(e) {
                        var t = this._get_xhr_object();
                        if (null == t) $_astxu.msg("[sendAjax] _get_xhr_object failed");
                        else {
                            null == e.data && (e.data = {}), null == e.type && (e.type = "POST"), null == e.async && (e.async = !0), null == e.onSuccess && (e.onSuccess = function(e) {}), null == e.onFailure && (e.onFailure = function(e, t) {}), t.open(e.type, e.url + "?rnd=" + this.rnd(), e.async), t.onreadystatechange = function() {
                                if (4 == t.readyState)
                                    if (200 == t.status) {
                                        var n = $_astxu.jsonParse(t.responseText);
                                        e.onSuccess(n)
                                    } else e.onFailure("state4", t.status)
                            };
                            var n = this.jsonQstr(e.data);
                            t.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"), t.send(n)
                        }
                    },
                    isACK: function(e) {
                        return !(!e || "ACK" != e)
                    },
                    isWinOS: function() {
                        return -1 != navigator.platform.indexOf("Win32") || -1 != navigator.platform.indexOf("Win64")
                    },
                    isMacOS: function() {
                        return -1 != navigator.platform.indexOf("Mac")
                    },
                    isLinuxOS: function() {
                        return -1 != navigator.platform.indexOf("Linux")
                    },
                    isMacLinuxOS: function() {
                        return 1 == this.isMacOS() || 1 == this.isLinuxOS()
                    },
                    hasFocused: function() {
                        var e = !1;
                        try {
                            e = document.hasFocus()
                        } catch (t) {}
                        return e
                    },
                    addEvent: function(e, t, n) {
                        e.addEventListener ? e.addEventListener(t, n, !1) : e.attachEvent("on" + t, n)
                    },
                    removeEvent: function(e, t, n) {
                        e.removeEventListener ? e.removeEventListener(t, n) : e.detachEvent("on" + t, n)
                    },
                    getCreatedFormValue: function(e, t, n) {
                        null == n && (n = !1), n = 1 == n ? document.getElementsByTagName("input") : e.getElementsByTagName("input");
                        for (var i = 0; i < n.length; i++) {
                            var a = n[i];
                            if (a && this.getnc(a.name) == t) return a
                        }
                        return (n = document.createElement("input")).name = t, n.type = "hidden", "function" === typeof _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.onCreatedFormValue && _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.onCreatedFormValue(n, t), e.appendChild(n), n
                    },
                    getHostPath: function() {
                        return window.location.protocol + "//" + window.location.hostname + (window.location.port ? ":" + window.location.port : "")
                    },
                    getnc: function(e, t) {
                        return null == t && (t = ""), null == e ? t : e
                    },
                    getint: function(e, t) {
                        return null == t && (t = 0), parseInt(e) || t
                    },
                    rnd: function() {
                        return (new Date).getTime() + new String(Math.floor(100 * Math.random()))
                    },
                    getKeyCode: function(e) {
                        var t = null;
                        return e && (t = e.keyCode ? e.keyCode : e.which), t
                    },
                    xorEncode: function(e, t) {
                        for (var n = "", i = new String(e), a = 0; a < i.length; a++) n += String.fromCharCode(t ^ i.charCodeAt(a));
                        return n
                    },
                    xorDecode: function(e, t) {
                        for (var n = "", i = new String(e), a = 0; a < i.length; a++) n += String.fromCharCode(t ^ i.charCodeAt(a));
                        return n
                    },
                    ltrim: function(e, t) {
                        for (t = this.getnc(t, " "); e.substring(0, 1) == t;) e = e.substring(1, e.length);
                        return e
                    },
                    rtrim: function(e, t) {
                        for (t = this.getnc(t, " "); e.substring(e.length - 1, e.length) == t;) e = e.substring(0, e.length - 1);
                        return e
                    },
                    alltrim: function(e, t) {
                        return this.ltrim(this.rtrim(e, t), t)
                    },
                    set_cookie: function(e, t, n, i, a, o) {
                        var r = new Date;
                        r.setTime(r.getTime()), r = new Date(r.getTime() + n), e = e + "=" + escape(t) + (n ? ";expires=" + r.toGMTString() : "") + (i ? ";path=" + i : "") + (a ? ";domain=" + a : "") + (o ? ";secure" : ""), document.cookie = e
                    },
                    setCookie: function(e, t, n, i, a, o) {
                        var r = null;
                        n && (r = 864e5 * n), this.set_cookie(e, t, r, i, a, o)
                    },
                    getCookie: function(e) {
                        try {
                            if (null == document.cookie || "" == document.cookie) return "";
                            for (var t = document.cookie.split(";"), n = 0; n < t.length; n++) {
                                var i = this.alltrim(this.getnc(t[n]));
                                if (e == this.alltrim(this.getnc(i.split("=")[0]))) return this.alltrim(this.getnc(i.split("=")[1]))
                            }
                        } catch (a) {}
                        return ""
                    },
                    setInputFocus: function() {
                        try {
                            for (var e = document.getElementsByTagName("input"), t = 0; t < e.length; t++) {
                                var n = e[t];
                                if (n && "hidden" != n.type) {
                                    n.focus();
                                    break
                                }
                            }
                        } catch (i) {}
                    },
                    log: function(e) {
                        "object" === typeof debuger && "function" === typeof debuger.write ? debuger.write(e) : "object" === typeof window.console && "function" === typeof console.log && console.log(e)
                    },
                    msg: function(e) {
                        this.log(e)
                    }
                },
                $ASTX2_E2E = function() {
                    var e = null,
                        t = function(e) {
                            function t(e) {
                                var t = e.target;
                                return null == t && (t = e.srcElement), t
                            }

                            function n(e) {
                                if (null == e) return null;
                                var t = e.getAttribute("e2e_inputid");
                                if (null == t || 0 >= t) return null;
                                var n = e.getAttribute("data-e2e_type");
                                if (null == n || 0 >= n) return null;
                                var i = e.getAttribute("e2e_inputtype");
                                (null == i || 0 >= i) && (i = "");
                                var a = {};
                                return a.e2e_inputid = t, a.e2e_type = n, a.e2e_inputtype = i, a.name = $_astxu.getnc(e.name), a.form = e.form ? $_astxu.getnc(e.form.name) : "", a
                            }

                            function i(e) {
                                var t = n(e);
                                return null == t ? null : (t.type = $_astxu.getnc(e.type), t.maxlength = $_astxu.getnc(e.getAttribute("maxlength")), t.txtmsk = $_astxu.getnc(e.getAttribute("e2e_txtmsk")), t)
                            }

                            function a(e, t) {
                                e.getAttribute("data-e2e_type") != $ASTX2_CONST.E2ETYPE_PLAIN1 && (e.readOnly = t)
                            }

                            function o(e, t, n, i, a) {
                                1 == P && r(e, t, n, i, a)
                            }

                            function r(t, n, i, a, o) {
                                var r = {};
                                if (r.pageid = $ASTX2_CONST.PAGEID, null != n)
                                    for (var s in n) n.hasOwnProperty(s) && (r[s] = n[s]);
                                e.send_e2e_cmd(t, r, i, a, o)
                            }

                            function s(e, t) {
                                return 0 == $ASTX2_CONST.E2EFORM_TAIL ? t + e : e + t
                            }

                            function c(e, t) {
                                return "password" == e.type ? t == $ASTX2_CONST.E2ETYPE_CERT2 ? s(e.name, $ASTX2_CONST.E2EFORM_TAG2_PWD) : s(e.name, $ASTX2_CONST.E2EFORM_TAG1_PWD) : t == $ASTX2_CONST.E2ETYPE_CERT2 ? s(e.name, $ASTX2_CONST.E2EFORM_TAG2) : s(e.name, $ASTX2_CONST.E2EFORM_TAG1)
                            }

                            function u() {
                                if (0 != F)
                                    for (var e = document.getElementsByTagName("form"), t = 0; t < e.length; t++) {
                                        var n = e[t];
                                        if (null != n) {
                                            var i = !1;
                                            if (N & $ASTX2_CONST.OPTION_E2EFORM_TAGSET)(null == (a = n.getAttribute(b.e2eform_tagset)) || 0 >= a) && (i = !0);
                                            else if (N & $ASTX2_CONST.OPTION_E2EFORM_ONLY) {
                                                if (null == n) a = !1;
                                                else
                                                    for (var a = !1, o = n.getElementsByTagName("input"), r = 0; r < o.length; r++) {
                                                        var c = o[r];
                                                        if (null != c && (null != (c = c.getAttribute("data-e2e_type")) && 0 < c)) {
                                                            a = !0;
                                                            break
                                                        }
                                                    }
                                                0 == a && (i = !0)
                                            }
                                            1 != i && ((i = $_astxu.getCreatedFormValue(n, s($ASTX2_CONST.E2EFORM_INIT, $ASTX2_CONST.E2EFORM_TAG1))) && (i.value = U), (i = $_astxu.getCreatedFormValue(n, s($ASTX2_CONST.E2EFORM_INIT, $ASTX2_CONST.E2EFORM_TAG2))) && (i.value = K))
                                        }
                                    }
                            }

                            function l() {
                                $_astxu.sendAjax({
                                    url: _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.getURL(_astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.URL_GET_CERT),
                                    onSuccess: function(e) {
                                        var t = $_astxu.getnc(e ? e.result : "");
                                        0 == $_astxu.isACK(t) ? $_astxu.msg("[_init_e2eform] result=" + t) : $ASTX2.set_cert((function() {
                                            ! function(e) {
                                                var t = 0;
                                                N & $ASTX2_CONST.OPTION_E2EFORM_NOENC && (t = 1), e = {
                                                    ver: $_astxu.getint(e.ver),
                                                    alg: $_astxu.getint(e.alg),
                                                    svr: $_astxu.getnc(e.svr),
                                                    norsa: $_astxu.getint(e.norsa),
                                                    uniq: $_astxu.getnc(e.uniq),
                                                    utime: $_astxu.getint(e.utime),
                                                    ncert: $_astxu.getnc(e.ncert),
                                                    pageid: $ASTX2_CONST.PAGEID,
                                                    noenc: t
                                                }, $_astxu.sendJsonp({
                                                    url: $ASTX2_COMM.mLocalServerURL + "/e2e_forminit",
                                                    data: e,
                                                    onSuccess: function(e) {
                                                        var t = $_astxu.getnc(e ? e.result : "");
                                                        0 == $_astxu.isACK(t) ? $_astxu.msg("[e2e_forminit] result=" + t) : (F = !0, U = $_astxu.getnc(e.e2e_form1), K = $_astxu.getnc(e.e2e_form2), u())
                                                    },
                                                    onFailure: function() {
                                                        $_astxu.msg("[e2e_forminit] failure.")
                                                    }
                                                })
                                            }(e)
                                        }), (function() {
                                            $_astxu.msg("[_init_e2eform] set_cert() failure")
                                        }), e)
                                    },
                                    onFailure: function(e, t) {
                                        $_astxu.msg("[_init_e2eform] failure")
                                    }
                                })
                            }

                            function _(e) {
                                var t = $_astxu.getHostPath() + $_astxu.getnc(window.location.pathname),
                                    n = 0;
                                N & $ASTX2_CONST.OPTION_NOSPTDUMMY && (n = 1), r("e2e_start", {
                                    browser: $ASTX2_CONST.BROWSER_VER,
                                    ver: e.ver,
                                    svr: e.svr,
                                    valg: e.valg,
                                    url: t,
                                    custcode: $,
                                    nospt: n
                                }, 5e3, (function(e) {
                                    var t = $_astxu.getnc(e ? e.result : "");
                                    if ($_astxu.isACK(t)) {
                                        P = 1, L = $_astxu.getint(e.call_settext), w = $_astxu.getint(e.call_gettext), M = $_astxu.getint(e.vm_env), k = $_astxu.getint(e.ak_drv);
                                        try {
                                            var n = document.activeElement;
                                            n && m(n, !1)
                                        } catch (a) {}
                                        if (N & $ASTX2_CONST.OPTION_E2EFORM && (3 > $_astxu.getint(e.stsvr) ? $ASTX2_COMM.errorAbort($ASTX2_CONST.ERROR_NEEDUPDATE) : l()), N & $ASTX2_CONST.OPTION_AUTOSETTEXT)
                                            for (var i in X) null != (e = X[i]) && (0 >= $_astxu.getnc(e.value).length || p(e))
                                    } else P = 2
                                }), (function() {
                                    P = 2
                                }))
                            }

                            function d(e) {
                                if (0 == $_astxu.hasFocused()) return !1;
                                if (0 == P) return !0;
                                if (1 == P) {
                                    if (!1 === e) return;
                                    try {
                                        var t = document.activeElement;
                                        t && m(t, !1)
                                    } catch (n) {}
                                    return !0
                                }
                                return P = 0, N & $ASTX2_CONST.OPTION_NONE2E ? (e = 0, N & $ASTX2_CONST.OPTION_NONE2E_ALG && (e = $ASTX2.mOptionStrings.none2e_alg), _({
                                    ver: 1,
                                    svr: "_none2e",
                                    valg: e
                                })) : $_astxu.sendAjax({
                                    url: _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.getURL(_astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.URL_GET_INIT),
                                    onSuccess: function(e) {
                                        var t = $_astxu.getnc(e ? e.result : "");
                                        $_astxu.isACK(t) ? _(e) : P = -1
                                    },
                                    onFailure: function(e, t) {
                                        P = -1
                                    }
                                }), !0
                            }

                            function E(e) {
                                if (null != e && (e.getAttribute("data-e2e_type") != $ASTX2_CONST.E2ETYPE_PLAIN1 || 0 != w)) {
                                    try {
                                        if (e != document.activeElement) return
                                    } catch (t) {}
                                    "number" == typeof e.selectionStart ? e.selectionStart = e.selectionEnd = e.value.length : "undefined" != typeof e.createTextRange && ((e = e.createTextRange()).collapse(!1), e.select())
                                }
                            }

                            function m(e, t) {
                                if (D = !1, N & $ASTX2_CONST.OPTION_FIXEDTYPE && e) {
                                    var n = e.getAttribute("e2e_inputid");
                                    null != n && 0 < n && j[n] != e.type && (e.type = j[n])
                                }
                                null != (n = i(e)) && (!0 === t && d(!1), o("e2e_focus", n, null, (function(t) {
                                    a(e, !1), window.setTimeout((function() {
                                        E(e)
                                    }), 1)
                                }), (function() {})))
                            }

                            function f(e) {
                                var n = t(e);
                                null != n && ("function" === typeof _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.onHandlerPreFocus && _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.onHandlerPreFocus(n, e), m(n, !0))
                            }

                            function g(e) {
                                if (null != (e = t(e))) {
                                    var r = n(e);
                                    if (null != r) {
                                        try {
                                            if (document.hasFocus() && e === document.activeElement) return
                                        } catch (i) {}
                                        a(e, !0), o("e2e_blur", r)
                                    }
                                }
                            }

                            function h(e) {
                                var n = t(e);
                                null != n && window.setTimeout((function() {
                                    E(n)
                                }), 1)
                            }

                            function p(e, t) {
                                var n = i(e);
                                if (null != n) {
                                    var a = $_astxu.getnc(e.value);
                                    n.text = a, N & $ASTX2_CONST.OPTION_E2EFORM && (n.e2eform = 1), N & $ASTX2_CONST.OPTION_E2EFORM_NOENC && (n.noenc = 1), o("e2e_settext", n, null, (function(i) {
                                        var a = $_astxu.getnc(i ? i.result : "");
                                        if (0 == $_astxu.isACK(a)) t && t($ASTX2_CONST.ERROR_NCK);
                                        else {
                                            if (N & $ASTX2_CONST.OPTION_E2EFORM) {
                                                a = c(e, n.e2e_type);
                                                var o = !1;
                                                N & $ASTX2_CONST.OPTION_E2EFORM_ALLTRIP && (o = !0), (a = $_astxu.getCreatedFormValue(e.form, a, o)) && (a.value = $_astxu.getnc(i.e2e_data))
                                            }
                                            t && t($ASTX2_CONST.ERROR_SUCCESS)
                                        }
                                    }), (function() {
                                        t && t($ASTX2_CONST.ERROR_FAILED)
                                    }))
                                }
                            }

                            function T(e) {
                                "function" === typeof e.stopPropagation && e.stopPropagation(), "function" === typeof e.preventDefault ? e.preventDefault() : e.returnValue = !1
                            }

                            function O(e) {
                                var n = t(e);
                                if (null != n) {
                                    var a = i(n);
                                    if (null != a) {
                                        E(n);
                                        var r = $_astxu.getKeyCode(e);
                                        if (17 == r && (D = !0), 1 == D) return 86 == r && o("e2e_clear", a, null, (function(e) {
                                            n.value = ""
                                        }), (function() {})), T(e), !1;
                                        if (1 == (16 == r || 17 == r || 18 == r)) return !0;
                                        var s = !0;
                                        return N & $ASTX2_CONST.OPTION_E2EFORM && (a.e2e_type != $ASTX2_CONST.E2ETYPE_CERT1 && a.e2e_type != $ASTX2_CONST.E2ETYPE_CERT2 || function(e, t) {
                                            N & $ASTX2_CONST.OPTION_E2EFORM_NOENC && (t.noenc = 1), o("e2e_formget", t, null, (function(n) {
                                                var i = $_astxu.getnc(n ? n.result : "");
                                                if (0 == $_astxu.isACK(i)) $_astxu.msg("[e2e_formget] " + t.name + ",result=" + i);
                                                else if (null != (i = e.form)) {
                                                    var a = c(e, t.e2e_type),
                                                        o = !1;
                                                    N & $ASTX2_CONST.OPTION_E2EFORM_ALLTRIP && (o = !0), (i = $_astxu.getCreatedFormValue(i, a, o)) && (i.value = $_astxu.getnc(n.e2e_data))
                                                }
                                            }), (function() {}))
                                        }(n, a)), "function" === typeof _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.onHandlerKeyDown && 0 == _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.onHandlerKeyDown(n, e) && (T(e), s = !1), 1 != w || "text" != n.type && a.e2e_type != $ASTX2_CONST.E2ETYPE_PLAIN1 || 0 != M || (1 == s && function(e, t, n) {
                                            o("e2e_gettext", t, null, (function(t) {
                                                var n = $_astxu.getnc(t ? t.result : "");
                                                $_astxu.isACK(n) && (t = $_astxu.getnc(t.text), e.value = t)
                                            }), (function() {}))
                                        }(n, a), 0 == (8 == r || 9 == r || 13 == r || 20 == r) && (T(e), s = !1)), s
                                    }
                                }
                            }

                            function S(e) {
                                var n = t(e);
                                null != n && (17 == $_astxu.getKeyCode(e) && (D = !1), 1 == L && p(n), window.setTimeout((function() {
                                    E(n)
                                }), 1))
                            }

                            function R(e) {
                                var n = t(e);
                                null != n && window.setTimeout((function() {
                                    E(n)
                                }), 1)
                            }

                            function v(e) {
                                if ("IE" == $ASTX2_CONST.BROWSER_TYPE || "EG" == $ASTX2_CONST.BROWSER_TYPE) {
                                    var i = t(e);
                                    null != i && "" != i.value && setTimeout((function() {
                                        if ("" == i.value) {
                                            var e = n(i);
                                            null != e && o("e2e_clear", e)
                                        }
                                    }), 1)
                                }
                            }

                            function x(e) {
                                var n = t(e);
                                null != n && ("number" == typeof n.selectionStart ? (n.selectionStart < n.value.length || n.selectionEnd < n.value.length) && window.setTimeout((function() {
                                    E(n)
                                }), 1) : "undefined" != typeof n.createTextRange && document.selection && ((e = document.selection.createRange()).moveStart("character", -n.value.length), e.text.length <= n.value.length && window.setTimeout((function() {
                                    E(n)
                                }), 1)))
                            }

                            function A(e) {}

                            function C(e) {
                                o("e2e_unload")
                            }
                            var N = 0,
                                b = [],
                                I = 0,
                                y = !1,
                                $ = 0,
                                P = -1,
                                L = 0,
                                w = 0,
                                M = 0,
                                k = 1,
                                D = !1,
                                X = [],
                                j = [],
                                F = !1,
                                U = "",
                                K = "";
                            this.setOption = function(e) {
                                N |= e
                            }, this.setOptionStrings = function(e) {
                                b = e
                            }, this.attach = function(e, t) {
                                null != e && ($ = e), N & $ASTX2_CONST.OPTION_AUTOSETTEXT && (X = []), N & $ASTX2_CONST.OPTION_E2EFORM && u();
                                for (var n = document.getElementsByTagName("input"), i = 0; i < n.length; i++) {
                                    var a = n[i];
                                    null != a && this.addObject(a)
                                }
                                r("e2e_init", null, null, (function(e) {
                                    d(), $_astxu.addEvent(window, "blur", A), $_astxu.addEvent(window, "unload", C), $_astxu.addEvent(window, "beforeunload", C), t && t(!0)
                                }), (function() {
                                    t && t(!1)
                                })), y = !0
                            }, this.addObject = function(e) {
                                if (null != e) {
                                    var t = e.getAttribute("e2e_inputid");
                                    if (!(null != t && 0 < t)) {
                                        var i = e.getAttribute("data-e2e_type");
                                        if (null == i || 0 >= i)
                                            if ("true" == (i = e.getAttribute("e2e")) || "on" == i) i = $ASTX2_CONST.E2ETYPE_CERT1, e.setAttribute("data-e2e_type", i);
                                            else {
                                                i = $ASTX2_CONST.E2ETYPE_NONE;
                                                try {
                                                    if ("function" === typeof _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.isE2EObject && 1 == _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.isE2EObject(e) && (i = $ASTX2_CONST.E2ETYPE_CERT1, e.setAttribute("data-e2e_type", i)), "function" === typeof _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.getE2Etype) {
                                                        var o = _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.getE2Etype(e);
                                                        o && (i = o, e.setAttribute("data-e2e_type", i))
                                                    }
                                                } catch (n) {}
                                                if (i == $ASTX2_CONST.E2ETYPE_NONE) return
                                            }
                                        t = ++I, e.setAttribute("e2e_inputid", t), o = !0, N & $ASTX2_CONST.OPTION_AUTOSETTEXT && 0 < e.value.length && (o = !1, X.push(e)), 1 == o && (e.value = ""), e.setAttribute("autocomplete", "off"), a(e, !0), j[t] = e.type, $_astxu.addEvent(e, "focus", f), $_astxu.addEvent(e, "blur", g), $_astxu.addEvent(e, "click", h), $_astxu.addEvent(e, "keyup", S), $_astxu.addEvent(e, "keydown", O), $_astxu.addEvent(e, "select", x), $_astxu.addEvent(e, "mouseup", v), $_astxu.addEvent(e, "mousedown", R), N & $ASTX2_CONST.OPTION_E2EFORM && (i == $ASTX2_CONST.E2ETYPE_CERT1 || i == $ASTX2_CONST.E2ETYPE_CERT2) && (null != (t = e.form) && (e = c(e, i), i = !1, N & $ASTX2_CONST.OPTION_E2EFORM_ALLTRIP && (i = !0), $_astxu.getCreatedFormValue(t, e, i)))
                                    }
                                }
                            }, this.subObject = function(e) {
                                var t = n(e);
                                if (null != t) {
                                    $_astxu.removeEvent(e, "focus", f), $_astxu.removeEvent(e, "blur", g), $_astxu.removeEvent(e, "click", h), $_astxu.removeEvent(e, "keyup", S), $_astxu.removeEvent(e, "keydown", O), $_astxu.removeEvent(e, "select", x), $_astxu.removeEvent(e, "mouseup", v), $_astxu.removeEvent(e, "mousedown", R);
                                    try {
                                        e == document.activeElement && o("e2e_blur", t)
                                    } catch (i) {}
                                    e.value = "", e.setAttribute("e2e_inputid", -1), e.setAttribute("data-e2e_type", -1), a(e, !1)
                                }
                            }, this.getE2EHash = function(e, t) {
                                var i = n(e);
                                null == i ? t(null, $ASTX2_CONST.ERROR_PARAM) : o("e2e_gethash", i, null, (function(e) {
                                    var n = $_astxu.getnc(e ? e.result : "");
                                    $_astxu.isACK(n) ? t($_astxu.getnc(e.hash), $ASTX2_CONST.ERROR_SUCCESS) : t(null, $ASTX2_CONST.ERROR_NCK)
                                }), (function() {
                                    t(null, $ASTX2_CONST.ERROR_FAILED)
                                }))
                            }, this.getEncText = function(e, t, i) {
                                var a = n(e);
                                a.customcode = $, a.random = t, null == a ? i(null, null, $ASTX2_CONST.ERROR_PARAM) : o("sdk_getenctext", a, null, (function(e) {
                                    var t = $_astxu.getnc(e ? e.result : "");
                                    $_astxu.isACK(t) ? i(a.name, $_astxu.getnc(e.getenctext), $ASTX2_CONST.ERROR_SUCCESS) : i(null, null, $ASTX2_CONST.ERROR_NCK)
                                }), (function() {
                                    i(null, null, $ASTX2_CONST.ERROR_FAILED)
                                }))
                            }, this.getE2EText = function(e, t) {
                                var i = n(e);
                                null == i || i.e2e_type != $ASTX2_CONST.E2ETYPE_PLAIN2 ? t(null, null, $ASTX2_CONST.ERROR_PARAM) : o("e2e_gettext", i, null, (function(e) {
                                    var n = $_astxu.getnc(e ? e.result : "");
                                    $_astxu.isACK(n) ? t(i.name, $_astxu.getnc(e.text), $ASTX2_CONST.ERROR_SUCCESS) : t(null, null, $ASTX2_CONST.ERROR_NCK)
                                }), (function() {
                                    t(null, null, $ASTX2_CONST.ERROR_FAILED)
                                }))
                            }, this.setE2EText = function(e, t) {
                                p(e, t)
                            }, this.clearE2EText = function(e, t) {
                                var i = n(e);
                                null != i && o("e2e_clear", i, null, (function(n) {
                                    if (n = $_astxu.getnc(n ? n.result : ""), 0 == $_astxu.isACK(n)) t && t($ASTX2_CONST.ERROR_NCK);
                                    else {
                                        if (e.value = "", N & $ASTX2_CONST.OPTION_E2EFORM) {
                                            n = c(e, i.e2e_type);
                                            var a = !1;
                                            N & $ASTX2_CONST.OPTION_E2EFORM_ALLTRIP && (a = !0), (n = $_astxu.getCreatedFormValue(e.form, n, a)) && (n.value = "")
                                        }
                                        t && t($ASTX2_CONST.ERROR_SUCCESS)
                                    }
                                }), (function() {
                                    t && t($ASTX2_CONST.ERROR_FAILED)
                                }))
                            }, this.dettach = function() {
                                o("e2e_uninit"), y = !1
                            }, this.isAttached = function() {
                                return y
                            }, this.isStarted = function() {
                                return 1 == P
                            }, this.checkService = function(e) {
                                var t = {};
                                if (t.service = $ASTX2_CONST.SERVICE_AK, t.result = $ASTX2_CONST.ERROR_SUCCESS, 0 >= P) {
                                    var n = this;
                                    window.setTimeout((function() {
                                        n.checkService(e)
                                    }), 300)
                                } else(1 == P && 0 == k || 2 == P) && (t.result = $ASTX2_CONST.ERROR_FAILED), e(t)
                            }, this.getE2EelmsForm = function(e, t, i, a) {
                                for (var o = document.getElementsByTagName("input"), r = 0; r < o.length; r++) {
                                    var s = o[r];
                                    if (null != s && (null != (s = n(s)) && s.e2e_type == a)) {
                                        var c = !1;
                                        (null == e || $_astxu.getnc(e.name) == s.form) && (c = !0), 0 != c && (t.push(s.e2e_inputid), i.push(s.name))
                                    }
                                }
                            }, this.getE2EelmsID = function(e, t, i, a) {
                                for (var o = document.getElementsByTagName("input"), r = 0; r < o.length; r++) {
                                    if (null != (u = o[r])) {
                                        var s = n(u);
                                        if (null != s && s.e2e_type == a) {
                                            for (var c = !1, u = $_astxu.getnc(u.id).toLowerCase(), l = 0; l < e.length; l++)
                                                if (e[l].toLowerCase() == u) {
                                                    c = !0;
                                                    break
                                                }
                                            0 != c && (t.push(s.e2e_inputid), i.push(s.name))
                                        }
                                    }
                                }
                            }, this.resetE2Evalues = function(e, t) {
                                for (var i = document.getElementsByTagName("input"), a = 0; a < i.length; a++) {
                                    var o = i[a];
                                    if (null != o) {
                                        var r = n(o);
                                        null != r && r.form == e && r.e2e_type != $ASTX2_CONST.E2ETYPE_PLAIN1 && r.e2e_type != $ASTX2_CONST.E2ETYPE_PLAIN2 && (o.value = t)
                                    }
                                }
                            }, this.getE2Eattribute = function(e) {
                                return n(e)
                            }
                        };
                    return {
                        getInstance: function(n) {
                            return null === e && (e = new t(n)), e
                        }
                    }
                }(),
                $ASTX2 = {
                    mUseJQuery: !0,
                    mOption: 0,
                    mOptionStrings: [],
                    mE2EInst: null,
                    mLastError: $ASTX2_CONST.ERROR_SUCCESS,
                    send_e2e_cmd: function(e, t, n, i, a, o) {
                        if (0 != $ASTX2_COMM.isEnable()) {
                            var r = this;
                            null == o && (o = 1);
                            var s = !1;
                            "e2e_focus" != e && "e2e_blur" != e && "e2e_gettext" != e && "e2e_settext" != e || (s = !0), $_astxu.sendJsonp({
                                url: $ASTX2_COMM.mLocalServerURL + "/" + e,
                                data: t,
                                timeout: n || 5e3,
                                async: !1,
                                seq: s,
                                onSuccess: function(e) {
                                    i && i(e)
                                },
                                onFailure: function() {
                                    3 > o ? r.send_e2e_cmd(e, t, n, i, a, o + 1) : a && a()
                                }
                            })
                        }
                    },
                    _send_alive: function() {
                        var e = {
                            pageid: $ASTX2_CONST.PAGEID,
                            focus: $_astxu.hasFocused() ? 1 : 0
                        };
                        $_astxu.sendJsonp({
                            url: $ASTX2_COMM.mLocalServerURL + "/alive",
                            data: e
                        })
                    },
                    send_alive_run: function() {
                        if (!($ASTX2_COMM.getDegugFlags() & $ASTX2_CONST.DEBUG_NOALIVE)) {
                            var e = this;
                            setInterval((function() {
                                e._send_alive()
                            }), $ASTX2_CONST.INTERVAL_ALIVE)
                        }
                    },
                    _hello_local_server: function(e, t, n, i, a) {
                        var o = this;
                        if (i >= n.length) o.setLastError($ASTX2_CONST.ERROR_NOTINST), t();
                        else {
                            var r = n[i++],
                                s = "https://" + (1 == $_astxu.isMacOS() ? "lx.astxsvc.com" : "127.0.0.1") + ":" + r + "/ASTX2";
                            $_astxu.sendJsonp({
                                url: s + "/hello",
                                timeout: a,
                                onSuccess: function(n) {
                                    n = $_astxu.getnc(n ? n.result : ""), $_astxu.isACK(n) ? ($ASTX2_COMM.mLocalServerURL = s, e()) : (o.setLastError($ASTX2_CONST.ERROR_NOTINST), t())
                                },
                                onFailure: function() {
                                    o._hello_local_server(e, t, n, i, a)
                                }
                            })
                        }
                    },
                    setOption: function(e) {
                        if (null != e) {
                            !0 === e.autofocus && (this.mOption |= $ASTX2_CONST.OPTION_AUTOFOCUS), !0 === e.e2eform && (this.mOption |= $ASTX2_CONST.OPTION_E2EFORM), !1 === e.e2eform_enc && (this.mOption |= $ASTX2_CONST.OPTION_E2EFORM_NOENC), !0 === e.e2eform_alltrip && (this.mOption |= $ASTX2_CONST.OPTION_E2EFORM_ALLTRIP), !0 === e.e2eform_only && (this.mOption |= $ASTX2_CONST.OPTION_E2EFORM_ONLY), !0 === e.autosettext && (this.mOption |= $ASTX2_CONST.OPTION_AUTOSETTEXT), !0 === e.nospt && (this.mOption |= $ASTX2_CONST.OPTION_NOSPTDUMMY), !0 === e.fixedtype && (this.mOption |= $ASTX2_CONST.OPTION_FIXEDTYPE);
                            var t = $_astxu.getnc(e.e2eform_tagset);
                            0 < t.length && (this.mOption |= $ASTX2_CONST.OPTION_E2EFORM_TAGSET, this.mOptionStrings.e2eform_tagset = t), 0 < (t = $_astxu.getint(e.none2e_alg)) && (this.mOption |= $ASTX2_CONST.OPTION_NONE2E_ALG, this.mOptionStrings.none2e_alg = t)
                        }
                    },
                    _get_custom_value: function() {
                        "undefined" !== typeof _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.mUseJQuery && ($ASTX2.mUseJQuery = _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.mUseJQuery), "undefined" !== typeof _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.E2EFORM_TAIL && ($ASTX2_CONST.E2EFORM_TAIL = _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.E2EFORM_TAIL), "undefined" !== typeof _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.E2EFORM_INIT && ($ASTX2_CONST.E2EFORM_INIT = _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.E2EFORM_INIT), "undefined" !== typeof _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.E2EFORM_TAG1 && ($ASTX2_CONST.E2EFORM_TAG1 = _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.E2EFORM_TAG1), "undefined" !== typeof _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.E2EFORM_TAG2 && ($ASTX2_CONST.E2EFORM_TAG2 = _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.E2EFORM_TAG2), "undefined" !== typeof _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.E2EFORM_TAG1_PWD && ($ASTX2_CONST.E2EFORM_TAG1_PWD = _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.E2EFORM_TAG1_PWD), "undefined" !== typeof _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.E2EFORM_TAG2_PWD && ($ASTX2_CONST.E2EFORM_TAG2_PWD = _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.E2EFORM_TAG2_PWD)
                    },
                    init: function(e, t, n) {
                        function i() {
                            if (e(), 1 == $_astxu.isMacLinuxOS()) {
                                a.send_alive_run();
                                try {
                                    $ASTX2_MLi.init()
                                } catch (t) {}
                            }
                        }
                        if (this.setLastError($ASTX2_CONST.ERROR_SUCCESS), 1 == $ASTX2_COMM.isEnable()) e();
                        else if ($ASTX2_COMM.getLocalFlags("init_http") == $ASTX2_CONST.LOCAL_INIT_HTTP) this.init_http(e, t, n);
                        else {
                            this._get_custom_value(), 1 == $ASTX2.mUseJQuery && "undefined" === typeof jQuery && ($ASTX2.mUseJQuery = !1);
                            var a = this;
                            this.mE2EInst = $ASTX2_E2E.getInstance(this), this.mE2EInst.setOption(this.mOption), this.mE2EInst.setOptionStrings(this.mOptionStrings);
                            var o = [55920, 55920, 55920, 55921, 55922];
                            null == n && (n = 750), setTimeout((function() {
                                a._hello_local_server(i, t, o, 0, n)
                            }), 200)
                        }
                    },
                    _hello_local_server_http: function(e, t, n, i, a) {
                        var o = this;
                        if (i >= n.length) o.setLastError($ASTX2_CONST.ERROR_NOTINST), t();
                        else {
                            var r = n[i++],
                                s = $_astxu.getHostPath() + $_astxu.getnc(window.location.pathname);
                            r = "http://127.0.0.1:" + r + "/ASTX2/hello?rnd=" + $_astxu.rnd() + "&url=" + s;
                            (s = new Image).style.display = "none", s.onload = function() {
                                e()
                            }, s.onerror = function() {
                                o._hello_local_server_http(e, t, n, i, a)
                            }, s.src = r
                        }
                    },
                    init_http: function(e, t, n) {
                        function i() {
                            e()
                        }
                        this.setLastError($ASTX2_CONST.ERROR_SUCCESS);
                        var a = [55910, 55910, 55911, 55912],
                            o = this;
                        null == n && (n = 750), setTimeout((function() {
                            o._hello_local_server_http(i, t, a, 0, n)
                        }), 200)
                    },
                    _check_local_server_chk_stamp: function(e, t, n, i) {
                        var a = this;
                        $_astxu.sendAjax({
                            url: _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.getURL(_astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.URL_CHK_STAMP),
                            data: i,
                            onSuccess: function(n) {
                                n = $_astxu.getnc(n ? n.result : ""), $_astxu.isACK(n) ? e() : (a.setLastError($ASTX2_CONST.ERROR_NCK), t())
                            },
                            onFailure: function(e, n) {
                                t()
                            }
                        })
                    },
                    _check_local_server: function(e, t, n, i) {
                        var a = this;
                        null == i && (i = 1), 3 < i ? (a.setLastError($ASTX2_CONST.ERROR_NOCONNECT), t()) : $_astxu.sendJsonp({
                            url: $ASTX2_COMM.mLocalServerURL + "/check",
                            data: {
                                method: n,
                                url: _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.getURL(_astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.URL_GET_STAMP),
                                rnd: $_astxu.rnd()
                            },
                            onSuccess: function(o) {
                                var r = $_astxu.getnc(o ? o.result : "");
                                $_astxu.isACK(r) ? a._check_local_server_chk_stamp(e, t, n, o) : a._check_local_server(e, t, n, i + 1)
                            },
                            onFailure: function() {
                                a.setLastError($ASTX2_CONST.ERROR_FAILED), a._check_local_server(e, t, n, i + 1)
                            }
                        })
                    },
                    checkServer: function(e, t, n) {
                        this.setLastError($ASTX2_CONST.ERROR_SUCCESS), 0 == $ASTX2_COMM.isEnable() ? (this.setLastError($ASTX2_CONST.ERROR_NOINIT), t()) : ((0 == $_astxu.isWinOS() || null == n) && (n = 1), this._check_local_server(e, t, n))
                    },
                    _e2e_enable: function() {
                        return 0 == $_astxu.isWinOS() ? (this.setLastError($ASTX2_CONST.ERROR_NOTSUPPORTED), !1) : 0 != $ASTX2_COMM.isEnable() || (this.setLastError($ASTX2_CONST.ERROR_NOINIT), !1)
                    },
                    resetE2E: function(e, t) {
                        if (this.setLastError($ASTX2_CONST.ERROR_SUCCESS), 0 == this._e2e_enable()) return !1;
                        var n = this;
                        return window.setTimeout((function() {
                            n.mE2EInst.attach(e, t)
                        }), 100), !0
                    },
                    initE2E: function(e, t) {
                        return this.mOption & $ASTX2_CONST.OPTION_AUTOFOCUS && $_astxu.setInputFocus(), this.resetE2E(e, t)
                    },
                    resetNonE2E: function(e, t) {
                        return this.initNonE2E(e, t)
                    },
                    initNonE2E: function(e, t) {
                        var n;
                        return null === (n = this.mE2EInst) || void 0 === n || n.setOption($ASTX2_CONST.OPTION_NONE2E), this.mOption & $ASTX2_CONST.OPTION_AUTOFOCUS && $_astxu.setInputFocus(), this.resetE2E(e, t)
                    },
                    uninitE2E: function() {
                        1 == $_astxu.isWinOS() && 1 == $ASTX2_COMM.isEnable() && this.mE2EInst.dettach()
                    },
                    uninitNonE2E: function() {
                        this.uninitE2E()
                    },
                    set_cert: function(e, t, n, i) {
                        null == i && (i = 1);
                        var a = new String($_astxu.getnc(n["cert" + i]));
                        if (0 == a.length) 0 < i ? e() : t();
                        else {
                            var o = this;
                            $_astxu.sendJsonp({
                                url: $ASTX2_COMM.mLocalServerURL + "/set_cert",
                                data: {
                                    step: i,
                                    cert: a,
                                    pageid: $ASTX2_CONST.PAGEID
                                },
                                onSuccess: function(a) {
                                    setTimeout((function() {
                                        o.set_cert(e, t, n, i + 1)
                                    }), 250)
                                },
                                onFailure: function() {
                                    t()
                                }
                            })
                        }
                    },
                    _e2edata_get: function(e, t, n, i) {
                        var a = this;
                        e = {
                            ver: $_astxu.getint(i.ver),
                            alg: $_astxu.getint(i.alg),
                            svr: $_astxu.getnc(i.svr),
                            norsa: $_astxu.getint(i.norsa),
                            uniq: $_astxu.getnc(i.uniq),
                            utime: $_astxu.getint(i.utime),
                            ncert: $_astxu.getnc(i.ncert),
                            pageid: $ASTX2_CONST.PAGEID,
                            ids1: e.ids1,
                            names1: e.names1,
                            ids2: e.ids2,
                            names2: e.names2
                        }, $_astxu.sendJsonp({
                            url: $ASTX2_COMM.mLocalServerURL + "/get_data",
                            data: e,
                            timeout: 1e4,
                            onSuccess: function(e) {
                                var i = $_astxu.getnc(e ? e.result : "");
                                $_astxu.isACK(i) ? t(e) : (a.setLastError($ASTX2_CONST.ERROR_NCK), n())
                            },
                            onFailure: function() {
                                a.setLastError($ASTX2_CONST.ERROR_FAILED), n()
                            }
                        })
                    },
                    _e2edata: function(e, t, n) {
                        var i = this;
                        $_astxu.sendAjax({
                            url: _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.getURL(_astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.URL_GET_CERT),
                            onSuccess: function(a) {
                                var o = $_astxu.getnc(a ? a.result : "");
                                0 == $_astxu.isACK(o) ? (i.setLastError($ASTX2_CONST.ERROR_NCK), n()) : i.set_cert((function() {
                                    i._e2edata_get(e, t, n, a)
                                }), (function() {
                                    i.setLastError($ASTX2_CONST.ERROR_NOCONNECT), n()
                                }), a)
                            },
                            onFailure: function(e, t) {
                                i.setLastError($ASTX2_CONST.ERROR_FAILED), n()
                            }
                        })
                    },
                    getE2EData: function(e, t, n) {
                        if (this.setLastError($ASTX2_CONST.ERROR_SUCCESS), 0 == this._e2e_enable()) n();
                        else {
                            var i = [],
                                a = [],
                                o = [],
                                r = [];
                            this.mE2EInst.getE2EelmsForm(e, i, a, 1), this.mE2EInst.getE2EelmsForm(e, o, r, 2), e = {
                                ids1: i.join(","),
                                names1: a.join(","),
                                ids2: o.join(","),
                                names2: r.join(",")
                            }, this._e2edata(e, t, n)
                        }
                    },
                    getE2EDataRetry: function(e, t, n, i, a) {
                        if (null == i && (i = 3), null == a && (a = 1e3), 0 >= i) this.setLastError($ASTX2_CONST.ERROR_EXCESS), n();
                        else {
                            var o = this;
                            this.getE2EData(e, (function(e) {
                                t(e)
                            }), (function() {
                                o.getLastError() == $ASTX2_CONST.ERROR_NOINIT ? window.setTimeout((function() {
                                    o.getE2EDataRetry(e, t, n, i - 1, a)
                                }), a) : n()
                            }))
                        }
                    },
                    getE2EDataIDs: function(e, t, n) {
                        if (this.setLastError($ASTX2_CONST.ERROR_SUCCESS), 0 == this._e2e_enable()) n();
                        else {
                            null == e && (that.setLastError($ASTX2_CONST.ERROR_PARAM), n());
                            var i = [],
                                a = [],
                                o = [],
                                r = [];
                            this.mE2EInst.getE2EelmsID(e, i, a, 1), this.mE2EInst.getE2EelmsID(e, o, r, 2), e = {
                                ids1: i.join(","),
                                names1: a.join(","),
                                ids2: o.join(","),
                                names2: r.join(",")
                            }, this._e2edata(e, t, n)
                        }
                    },
                    setE2EData: function(e, t, n, i) {
                        null == n && (n = !0), null == i && (i = "");
                        var a = $_astxu.getnc(t.e2e_data1);
                        0 < a.length && ($_astxu.getCreatedFormValue(e, "e2e_data1").value = a), 0 < (t = $_astxu.getnc(t.e2e_data2)).length && ($_astxu.getCreatedFormValue(e, "e2e_data2").value = t), 1 == n && this.mE2EInst.resetE2Evalues($_astxu.getnc(e.name), i)
                    },
                    getE2EPageID: function() {
                        return $ASTX2_CONST.PAGEID
                    },
                    getE2EInputID: function(e) {
                        return null == (e = this.mE2EInst.getE2Eattribute(e)) ? null : e.e2e_inputid
                    },
                    getE2EHash: function(e, t) {
                        this.mE2EInst.getE2EHash(e, (function(e, n) {
                            t(e, n)
                        }))
                    },
                    getEncText: function(e, t, n) {
                        this.mE2EInst.getEncText(e, t, (function(e, t, i) {
                            n(t, i)
                        }))
                    },
                    getE2EText: function(e, t) {
                        this.mE2EInst.getE2EText(e, (function(e, n, i) {
                            t(n, i)
                        }))
                    },
                    getE2ETextS: function(e, t) {
                        for (var n = 0, i = 0, a = [], o = 0; o < e.length; o++) {
                            var r = e[o];
                            null != r && (null != (r = r.getAttribute("data-e2e_type")) && r == $ASTX2_CONST.E2ETYPE_PLAIN2 && n++)
                        }
                        if (0 == n || n != e.length) t(a, $ASTX2_CONST.ERROR_PARAM);
                        else
                            for (o = 0; o < e.length; o++) this.mE2EInst.getE2EText(e[o], (function(e, o, r) {
                                i >= n || (0 == r ? a[e] = o : i = n, ++i >= n && t(a, r))
                            }))
                    },
                    setE2EText: function(e, t) {
                        this.mE2EInst.setE2EText(e, t)
                    },
                    clearE2EText: function(e, t) {
                        this.mE2EInst.clearE2EText(e, t)
                    },
                    clearE2EForm: function(e) {
                        for (var t = document.getElementsByTagName("input"), n = 0; n < t.length; n++) {
                            var i = t[n];
                            null != i && i.form.name == e && 0 != ("text" == i.type || "password" == i.type) && null != i.getAttribute("data-e2e_type") && this.mE2EInst.clearE2EText(i)
                        }
                    },
                    addE2EObject: function(e, t) {
                        null == t && (t = $ASTX2_CONST.E2ETYPE_CERT1), !e || "text" != e.type && "password" != e.type || (e.setAttribute("data-e2e_type", t), this.mE2EInst.addObject(e))
                    },
                    subE2EObject: function(e) {
                        e && this.mE2EInst.subObject(e)
                    },
                    setE2EAllExceptInputs: function() {
                        for (var e = document.getElementsByTagName("input"), t = 0; t < e.length; t++) {
                            var n = e[t];
                            null != n && 0 != ("text" == n.type || "password" == n.type) && null == n.getAttribute("data-e2e_type") && n.setAttribute("data-e2e_type", $ASTX2_CONST.E2ETYPE_NONE)
                        }
                    },
                    killFocusE2EAllInputs: function() {
                        for (var e = document.getElementsByTagName("input"), t = 0; t < e.length; t++) {
                            var n = e[t];
                            null != n && 0 != ("text" == n.type || "password" == n.type) && 0 < n.getAttribute("data-e2e_type") && n.blur()
                        }
                    },
                    _pclogdata_get: function(e, t, n, i) {
                        var a = this;
                        n = {
                            ver: $_astxu.getint(n.ver),
                            alg: $_astxu.getint(n.alg),
                            svr: $_astxu.getnc(n.svr),
                            norsa: $_astxu.getint(n.norsa),
                            uniq: $_astxu.getnc(n.uniq),
                            utime: $_astxu.getint(n.utime),
                            nlog: $_astxu.getnc(n.nlog),
                            ipaddr: $_astxu.getnc(n.ipaddr),
                            browser: $ASTX2_CONST.BROWSER_VER,
                            pageid: $ASTX2_CONST.PAGEID,
                            opt: i
                        }, $_astxu.sendJsonp({
                            url: $ASTX2_COMM.mLocalServerURL + "/get_pclog",
                            data: n,
                            timeout: 1e4,
                            onSuccess: function(n) {
                                var i = $_astxu.getnc(n ? n.result : "");
                                $_astxu.isACK(i) ? e(n) : (a.setLastError($ASTX2_CONST.ERROR_NCK), t())
                            },
                            onFailure: function() {
                                a.setLastError($ASTX2_CONST.ERROR_FAILED), t()
                            }
                        })
                    },
                    _pclogdata: function(e, t, n) {
                        var i = this;
                        $_astxu.sendAjax({
                            url: _astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.getURL(_astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.URL_GET_CERT),
                            data: {
                                pclog: 1
                            },
                            onSuccess: function(a) {
                                var o = $_astxu.getnc(a ? a.result : "");
                                0 == $_astxu.isACK(o) ? (i.setLastError($ASTX2_CONST.ERROR_NCK), t()) : i.set_cert((function() {
                                    i._pclogdata_get(e, t, a, n)
                                }), (function() {
                                    i.setLastError($ASTX2_CONST.ERROR_NOCONNECT), t()
                                }), a)
                            },
                            onFailure: function(e, n) {
                                i.setLastError($ASTX2_CONST.ERROR_FAILED), t()
                            }
                        })
                    },
                    getPCLOGData: function(e, t, n, i) {
                        null == i && (i = ""), this.setLastError($ASTX2_CONST.ERROR_SUCCESS), 0 == $ASTX2_COMM.isEnable() ? (this.setLastError($ASTX2_CONST.ERROR_NOINIT), n()) : this._pclogdata(t, n, i)
                    },
                    getPCLOGDataRetry: function(e, t, n, i, a) {
                        if (null == i && (i = 3), null == a && (a = 1e3), 0 >= i) this.setLastError($ASTX2_CONST.ERROR_EXCESS), n();
                        else {
                            var o = this;
                            this.getPCLOGData(e, (function(e) {
                                t(e)
                            }), (function() {
                                o.getLastError() == $ASTX2_CONST.ERROR_NOINIT ? window.setTimeout((function() {
                                    o.getPCLOGDataRetry(e, t, n, i - 1, a)
                                }), a) : n()
                            }))
                        }
                    },
                    setPCLOGData: function(e, t) {
                        var n = $_astxu.getnc(t.pclog_data);
                        0 < n.length && ($_astxu.getCreatedFormValue(e, "pclog_data").value = n)
                    },
                    isVmEnv: function(e) {
                        0 == $ASTX2_COMM.isEnable() ? e(null, $ASTX2_CONST.ERROR_NOINIT) : $_astxu.sendJsonp({
                            url: $ASTX2_COMM.mLocalServerURL + "/is_vm_env",
                            onSuccess: function(t) {
                                var n = $_astxu.getnc(t ? t.result : "");
                                $_astxu.isACK(n) ? e(t.vm_env, $ASTX2_CONST.ERROR_SUCCESS) : e(null, $ASTX2_CONST.ERROR_NCK)
                            },
                            onFailure: function() {
                                e(null, $ASTX2_CONST.ERROR_FAILED)
                            }
                        })
                    },
                    isRemoteEnv: function(e) {
                        0 == $ASTX2_COMM.isEnable() ? e(null, $ASTX2_CONST.ERROR_NOINIT) : $_astxu.sendJsonp({
                            url: $ASTX2_COMM.mLocalServerURL + "/is_remote_env",
                            onSuccess: function(t) {
                                var n = $_astxu.getnc(t ? t.result : "");
                                $_astxu.isACK(n) ? e(t.remote_env, $ASTX2_CONST.ERROR_SUCCESS) : e(null, $ASTX2_CONST.ERROR_NCK)
                            },
                            onFailure: function() {
                                e(null, $ASTX2_CONST.ERROR_FAILED)
                            }
                        })
                    },
                    isVmRemoteEnv: function(e) {
                        0 == $ASTX2_COMM.isEnable() ? e(null, $ASTX2_CONST.ERROR_NOINIT) : $_astxu.sendJsonp({
                            url: $ASTX2_COMM.mLocalServerURL + "/is_vm_remote_env",
                            onSuccess: function(t) {
                                var n = $_astxu.getnc(t ? t.result : "");
                                $_astxu.isACK(n) ? e(t.vm_remote_env, $ASTX2_CONST.ERROR_SUCCESS) : e(null, $ASTX2_CONST.ERROR_NCK)
                            },
                            onFailure: function() {
                                e(null, $ASTX2_CONST.ERROR_FAILED)
                            }
                        })
                    },
                    checkService: function(e, t) {
                        e == $ASTX2_CONST.SERVICE_AK && this.mE2EInst.checkService(t)
                    },
                    setProtect: function(e, t) {
                        if (0 == $ASTX2_COMM.isEnable()) t && t($ASTX2_CONST.ERROR_NOINIT);
                        else {
                            var n = {
                                customerid: $_astxu.getnc(_astx2_custom__WEBPACK_IMPORTED_MODULE_0__.z.mCustomerID),
                                ak: e & $ASTX2_CONST.PROTECT_AK ? 1 : -1,
                                fw: e & $ASTX2_CONST.PROTECT_FW ? 1 : -1
                            };
                            $_astxu.sendJsonp({
                                url: $ASTX2_COMM.mLocalServerURL + "/set_protect",
                                data: n,
                                onSuccess: function(e) {
                                    e = $_astxu.getnc(e ? e.result : ""), $_astxu.isACK(e) ? t && t($ASTX2_CONST.ERROR_SUCCESS) : t && t($ASTX2_CONST.ERROR_NCK)
                                },
                                onFailure: function() {
                                    t && t($ASTX2_CONST.ERROR_FAILED)
                                }
                            })
                        }
                    },
                    uninit: function() {
                        this.uninitE2E(), $ASTX2_COMM.uninit()
                    },
                    setLastError: function(e) {
                        this.mLastError = e
                    },
                    getLastError: function() {
                        return this.mLastError
                    }
                },
                $ASTX2_MLi = {
                    init: function() {
                        setTimeout((function() {
                            var e = {
                                pageid: $ASTX2_CONST.PAGEID,
                                focus: $_astxu.hasFocused() ? 1 : 0
                            };
                            $_astxu.sendJsonp({
                                url: $ASTX2_COMM.mLocalServerURL + "/initForML",
                                timeout: 1e3,
                                data: e
                            })
                        }), 200)
                    }
                }
        },
        98236: function(e, t, n) {
            n.d(t, {
                z: function() {
                    return i
                }
            });
            var i = {
                mUseJQuery: !1,
                URL_GET_INIT: 101,
                URL_GET_CERT: 102,
                URL_GET_STAMP: 103,
                URL_CHK_STAMP: 104,
                ACTIVE: !1,
                getURL: function(e) {
                    var t = "",
                        n = window.location.protocol + "//" + window.location.hostname;
                    switch (n += window.location.port ? ":" + window.location.port : "", e) {
                        case this.URL_GET_INIT:
                            t = n + "/AOS2/astx2/do_get_init.jsp";
                            break;
                        case this.URL_GET_CERT:
                            t = n + "/AOS2/astx2/do_get_cert.jsp";
                            break;
                        case this.URL_CHK_STAMP:
                            t = n + "/AOS2/astx2/do_get_stamp.jsp"
                    }
                    return t
                },
                isE2EObject: function() {
                    return !1
                },
                getE2Etype: function() {
                    return null
                },
                onHandlerKeyDown: function() {
                    return !0
                },
                getErrorMessage: function(e) {
                    var t = "[ASTx] ";
                    return Promise.resolve().then(n.bind(n, 20105)).then((function(n) {
                        var i = n.$ASTX2_CONST;
                        switch (e) {
                            case i.ERROR_FAILED:
                                t += "\ub0b4\ubd80 \uc624\ub958\uac00 \ubc1c\uc0dd\ud558\uc600\uc2b5\ub2c8\ub2e4.";
                                break;
                            case i.ERROR_NOINIT:
                                t += "\ucd08\uae30\ud654\uac00 \ud544\uc694\ud569\ub2c8\ub2e4.";
                                break;
                            case i.ERROR_NOTINST:
                                t += "\uc124\uce58\ub418\uc5b4 \uc788\uc9c0 \uc54a\uc2b5\ub2c8\ub2e4.";
                                break;
                            case i.ERROR_NOTSUPPORTED:
                                t += "\uc9c0\uc6d0\ud558\uc9c0 \uc54a\ub294 OS\uc785\ub2c8\ub2e4.";
                                break;
                            case i.ERROR_NOCONNECT:
                                t += "\uc11c\ubc84(Web) \ud1b5\uc2e0 \uc2e4\ud328\uc785\ub2c8\ub2e4.";
                                break;
                            case i.ERROR_NCK:
                                t += "\uc11c\ubc84(Local) \uc751\ub2f5 \uc2e4\ud328\uc785\ub2c8\ub2e4.";
                                break;
                            case i.ERROR_ERR:
                                t += "\uc11c\ubc84(Local) \ub0b4\ubd80 \uc624\ub958\uc785\ub2c8\ub2e4.";
                                break;
                            case i.ERROR_NSP:
                                t += "\uc9c0\uc6d0\ud558\uc9c0 \ub418\uc9c0 \uc54a\ub294 \ud658\uacbd\uc785\ub2c8\ub2e4.";
                                break;
                            case i.ERROR_PARAM:
                                t += "\uc798\ubabb\ub41c \uc778\uc790\uc785\ub2c8\ub2e4.";
                                break;
                            case i.ERROR_EXCESS:
                                t += "\uc7ac\uc2dc\ub3c4 \ud68c\uc218\ub97c \ucd08\uacfc \ud558\uc600\uc2b5\ub2c8\ub2e4.";
                                break;
                            case i.ERROR_NEEDUPDATE:
                                t += "\uc81c\ud488 \uc5c5\ub370\uc774\ud2b8\uac00 \ud544\uc694\ud569\ub2c8\ub2e4.";
                                break;
                            default:
                                t += "errno=" + e
                        }
                    })), t
                },
                errorAbort: function(e) {
                    alert(this.getErrorMessage(e))
                }
            }
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
        6814: function(e, t, n) {
            n.d(t, {
                R5: function() {
                    return i
                },
                dM: function() {
                    return a
                }
            });
            var i, a, o = n(61879),
                r = n(96094),
                s = n(11792),
                c = n(38153),
                u = n(9967),
                l = n(46140),
                _ = n(34999),
                d = n(76546),
                E = n(52160),
                m = n(15822),
                f = n(1746);
            ! function(e) {
                e.LOGIN = "login", e.CERTIFY = "certify", e.RENAME = "rename", e.GUEST = "guest"
            }(i || (i = {})),
            function(e) {
                e.PASSWORD_CAMPAIGN = "passwordCampaign", e.EMERGENCY_LOGIN = "emergencyLogin", e.RETRIEVE_ASSET = "retrieveAsset", e.ACCOUNT_CANCEL = "withdrawalWireCancel", e.WITHDRAW_OF_PRE_REGISTRATION = "withdrawOfPreRegistration", e.DEPOSIT = "withdrawalWireDeposit", e.WITHDRAW = "withdraw", e.GUEST_PASSWORD = "guestPassword", e.LIMIT_RAISE_APPLIED = "limitRaiseApplied", e.GUEST_ACCOUNT = "guestAccount"
            }(a || (a = {}));
            t.ZP = function(e, t) {
                var n = (0, l.G2)(),
                    a = n.localeService.locale,
                    g = n.httpService.get,
                    h = n.modalService.showModal,
                    p = n.sessionService,
                    T = p.getUserInfo,
                    O = p.login,
                    S = n.envType,
                    R = (0, d.s0)(),
                    v = (0, f.V)(),
                    x = (0, u.useRef)({
                        isCert: !1,
                        message: "",
                        errorCode: ""
                    }),
                    A = (0, u.useRef)(null),
                    C = (0, u.useState)(!1),
                    N = (0, c.Z)(C, 2),
                    b = N[0],
                    I = N[1],
                    y = (0, u.useState)(),
                    $ = (0, c.Z)(y, 2),
                    P = $[0],
                    L = $[1],
                    w = function() {
                        var n = (0, s.Z)((0, o.Z)().mark((function n() {
                            var s, c, u;
                            return (0, o.Z)().wrap((function(n) {
                                for (;;) switch (n.prev = n.next) {
                                    case 0:
                                        return s = function() {
                                            switch (e) {
                                                case i.LOGIN:
                                                    return {
                                                        requestUrl: "/v1/certification/kcb/prelogin/check",
                                                        params: {
                                                            requestWork: t,
                                                            memNo: null === T || void 0 === T ? void 0 : T.memNo
                                                        }
                                                    };
                                                case i.GUEST:
                                                    return {
                                                        requestUrl: "/member/v1/certification/kcb/guest/check",
                                                        params: {
                                                            requestWork: t
                                                        }
                                                    };
                                                default:
                                                    return {
                                                        requestUrl: "/v1/member/phone-cert/check",
                                                        params: (0, r.Z)({
                                                            serviceType: e,
                                                            requestWork: t
                                                        }, x.current.oParams)
                                                    }
                                            }
                                        }(), c = s.requestUrl, u = s.params, n.next = 3, g(c, u).then((function(e) {
                                            if (200 !== e.status) return !1;
                                            var t = e.data,
                                                n = t.certified,
                                                i = t.expireTime,
                                                o = t.message,
                                                r = t.certNum;
                                            x.current.errorCode = n, x.current.certNum = r, i > 0 && "cert.success.00001" === n ? x.current.isCert = !0 : "cert.fail.00004" === n ? (x.current.isCert = !1, x.current.message = "") : (x.current.message = "retrieve.asset.fail.00001" === n ? a(O ? "pop.findMyAsset.msg009" : "pop.findMyAsset.msg007") : o, x.current.isCert = !1)
                                        }));
                                    case 3:
                                        return n.abrupt("return", x.current);
                                    case 4:
                                    case "end":
                                        return n.stop()
                                }
                            }), n)
                        })));
                        return function() {
                            return n.apply(this, arguments)
                        }
                    }(),
                    M = (0, _.Yz)((function() {
                        A.current && A.current.closed && (A.current = null, I(!0), w().then((function(e) {
                            var t = e.isCert,
                                n = e.message,
                                i = e.errorCode,
                                o = e.certNum;
                            if (P)
                                if (t) P.successCb(o);
                                else if (n) {
                                ! function(e, t) {
                                    switch (!0) {
                                        case ["rename.fail.00001", "rename.fail.00005", "rename.fail.00006"].includes(e):
                                            h(m.DzK, {
                                                message: t,
                                                modalBtn: {
                                                    feature: E.y_.CUSTOM,
                                                    callback: function() {
                                                        return R("/legacy/customer_support/proof")
                                                    }
                                                }
                                            });
                                            break;
                                        case "retrieve.asset.fail.00001" === e:
                                            h(m.DzK, O ? {
                                                message: t,
                                                modalBtn: {
                                                    text: a("comm.msg007"),
                                                    feature: E.y_.CUSTOM,
                                                    callback: function() {
                                                        R("/")
                                                    }
                                                },
                                                modalBtn1: {
                                                    text: a("pop.findMyAsset.msg010"),
                                                    feature: E.y_.CUSTOM,
                                                    callback: function() {
                                                        R(v.ASSET_MY)
                                                    }
                                                }
                                            } : {
                                                message: t,
                                                modalBtn: {
                                                    text: a("pop.findMyAsset.msg008"),
                                                    feature: E.y_.CUSTOM,
                                                    callback: function() {
                                                        R("/login")
                                                    }
                                                }
                                            });
                                            break;
                                        case "rename.fail.00008" === e:
                                        case "kyc.fail.00030" === e:
                                            h(m.DzK, {
                                                message: t,
                                                modalBtn: {
                                                    feature: E.y_.CUSTOM,
                                                    callback: function() {
                                                        return P && P.successCb()
                                                    }
                                                }
                                            });
                                            break;
                                        default:
                                            h(m.DzK, {
                                                message: t
                                            })
                                    }
                                }(i, n.replace(/\n/g, "<br/>")), "kyc.fail.00030" !== i && P.failureCb && P.failureCb()
                            } else P.failureCb && P.failureCb()
                        })))
                    }), b ? null : 1e3);
                return {
                    checkCert: w,
                    openKCBWindow: function(n, a, o, s) {
                        if ("LOCAL" === S || "PROD" !== S && "test" === window.testEnv) return n();
                        if (A.current) A.current.focus();
                        else {
                            ! function(e, t, n) {
                                var i, a, o, r, s = void 0 !== window.screenLeft ? window.screenLeft : 0,
                                    c = void 0 !== window.screenTop ? window.screenTop : 0,
                                    u = ((null !== (i = window.innerWidth) && void 0 !== i ? i : null !== (a = document.documentElement.clientWidth) && void 0 !== a ? a : screen.width) - t) / 2 + s,
                                    l = ((null !== (o = window.innerHeight) && void 0 !== o ? o : null !== (r = document.documentElement.clientHeight) && void 0 !== r ? r : screen.height) - n) / 2 + c;
                                A.current = window.open(e, "_blank", "width=".concat(t, ",height=").concat(n, ",left=").concat(u, ",top=").concat(l))
                            }(void 0, 480, 880);
                            var c = function() {
                                    switch (e) {
                                        case i.LOGIN:
                                            return "/v1/certification/kcb/prelogin";
                                        case i.GUEST:
                                            return "/member/v1/certification/kcb/guest";
                                        default:
                                            return "/v1/member/certKcb/kcb/".concat(e || "certify")
                                    }
                                }(),
                                u = e === i.LOGIN ? (0, r.Z)({
                                    requestWork: t,
                                    memNo: null === T || void 0 === T ? void 0 : T.memNo
                                }, s) : (0, r.Z)({
                                    requestWork: t
                                }, s);
                            g(c, u, !0).then((function(e) {
                                if (!e) return "";
                                A.current && (I(!1), x.current.oParams = s, A.current.location.href = URL.createObjectURL(new Blob([e], {
                                    type: "text/html"
                                }))), L({
                                    successCb: n,
                                    failureCb: a
                                }), M.setAction(!0)
                            }))
                        }
                    }
                }
            }
        }
    }
]);