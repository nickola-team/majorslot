<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="icon" href="/op/cq9feedback/favicon.ico" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="description" content="Web site created using create-react-app" />
    <link rel="manifest" href="/op/cq9feedback/manifest.json" />
    <style>
        a,
        abbr,
        acronym,
        address,
        applet,
        article,
        aside,
        audio,
        b,
        big,
        blockquote,
        body,
        canvas,
        caption,
        center,
        cite,
        code,
        dd,
        del,
        details,
        dfn,
        div,
        dl,
        dt,
        em,
        embed,
        fieldset,
        figcaption,
        figure,
        footer,
        form,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        header,
        hgroup,
        html,
        i,
        iframe,
        img,
        ins,
        kbd,
        label,
        legend,
        li,
        mark,
        menu,
        nav,
        object,
        ol,
        output,
        p,
        pre,
        q,
        ruby,
        s,
        samp,
        section,
        small,
        span,
        strike,
        strong,
        sub,
        summary,
        sup,
        table,
        tbody,
        td,
        tfoot,
        th,
        thead,
        time,
        tr,
        tt,
        u,
        ul,
        var,
        video {
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 100%;
            font: inherit;
            vertical-align: baseline
        }

        article,
        aside,
        details,
        figcaption,
        figure,
        footer,
        header,
        hgroup,
        menu,
        nav,
        section {
            display: block
        }

        ol,
        ul {
            list-style: none
        }

        blockquote,
        q {
            quotes: none
        }

        blockquote:after,
        blockquote:before,
        q:after,
        q:before {
            content: '';
            content: none
        }

        table {
            border-collapse: collapse;
            border-spacing: 0
        }
    </style>
    <title>游戏反馈系统</title>
</head>

<body style="position:fixed;top:50%;left:0;width:100%;height:100vh;transform:translateY(-50%);line-height:1;background:rgba(0,0,0,.7)">
    <div id="root"></div>
    <script>
        ! function(e) {
            function r(r) {
                for (var n, a, l = r[0], p = r[1], f = r[2], c = 0, s = []; c < l.length; c++) a = l[c], Object.prototype.hasOwnProperty.call(o, a) && o[a] && s.push(o[a][0]), o[a] = 0;
                for (n in p) Object.prototype.hasOwnProperty.call(p, n) && (e[n] = p[n]);
                for (i && i(r); s.length;) s.shift()();
                return u.push.apply(u, f || []), t()
            }

            function t() {
                for (var e, r = 0; r < u.length; r++) {
                    for (var t = u[r], n = !0, l = 1; l < t.length; l++) {
                        var p = t[l];
                        0 !== o[p] && (n = !1)
                    }
                    n && (u.splice(r--, 1), e = a(a.s = t[0]))
                }
                return e
            }
            var n = {},
                o = {
                    1: 0
                },
                u = [];

            function a(r) {
                if (n[r]) return n[r].exports;
                var t = n[r] = {
                    i: r,
                    l: !1,
                    exports: {}
                };
                return e[r].call(t.exports, t, t.exports, a), t.l = !0, t.exports
            }
            a.m = e, a.c = n, a.d = function(e, r, t) {
                a.o(e, r) || Object.defineProperty(e, r, {
                    enumerable: !0,
                    get: t
                })
            }, a.r = function(e) {
                "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, {
                    value: "Module"
                }), Object.defineProperty(e, "__esModule", {
                    value: !0
                })
            }, a.t = function(e, r) {
                if (1 & r && (e = a(e)), 8 & r) return e;
                if (4 & r && "object" == typeof e && e && e.__esModule) return e;
                var t = Object.create(null);
                if (a.r(t), Object.defineProperty(t, "default", {
                        enumerable: !0,
                        value: e
                    }), 2 & r && "string" != typeof e)
                    for (var n in e) a.d(t, n, function(r) {
                        return e[r]
                    }.bind(null, n));
                return t
            }, a.n = function(e) {
                var r = e && e.__esModule ? function() {
                    return e.default
                } : function() {
                    return e
                };
                return a.d(r, "a", r), r
            }, a.o = function(e, r) {
                return Object.prototype.hasOwnProperty.call(e, r)
            }, a.p = "/";
            var l = this.webpackJsonppanda = this.webpackJsonppanda || [],
                p = l.push.bind(l);
            l.push = r, l = l.slice();
            for (var f = 0; f < l.length; f++) r(l[f]);
            var i = p;
            t()
        }([])
    </script>
    <script src="/op/cq9feedback/static/js/2.69c4890a.chunk.js"></script>
    <script src="/op/cq9feedback/static/js/main.cd227cfa.chunk.js"></script>
</body>

</html>