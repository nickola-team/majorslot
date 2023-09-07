/*! For license information please see main.cd227cfa.chunk.js.LICENSE.txt */
(this.webpackJsonppanda = this.webpackJsonppanda || []).push([
    [0], {
        251: function(n) {
            n.exports = JSON.parse('{"name":"panda","version":"2.0.0","private":true,"dependencies":{"@testing-library/jest-dom":"^4.2.4","@testing-library/react":"^9.3.2","@testing-library/user-event":"^7.1.2","axios":"^0.19.2","classnames":"^2.2.6","current-device":"^0.10.1","i18next":"^22.0.2","immutable":"^4.0.0-rc.12","lodash":"^4.17.15","react":"^16.12.0","react-dom":"^16.12.0","react-i18next":"^12.0.0","react-icons":"^3.8.0","react-scripts":"3.4.0","react-spinners-kit":"^1.9.1","styled-components":"^4.4.1"},"scripts":{"start":"react-scripts start","build":"react-scripts build","test":"react-scripts test","eject":"react-scripts eject"},"eslintConfig":{"extends":"react-app"},"browserslist":{"production":[">0.2%","not dead","not op_mini all","ie 11"],"development":["last 1 chrome version","last 1 firefox version","last 1 safari version","ie 11"]}}')
        },
        262: function(n, t, e) {
            n.exports = e(515)
        },
        515: function(n, t, e) {
            "use strict";
            e.r(t);
            e(263), e(280);
            var a = e(1),
                o = e.n(a),
                r = e(249),
                i = e.n(r),
                s = e(250),
                c = e(155),
                l = e(29),
                d = e(21),
                p = e(251),
                m = e(13),
                g = e(28),
                f = e.n(g),
                h = e(42),
                u = e.n(h),
                b = e(252),
                v = e(18),
                x = e(517),
                w = e(159),
                y = e(93),
                k = {
                    feedback: "\u73a9\u5bb6\u53cd\u9988",
                    rateThisGame: "\u4e3a\u8fd9\u6b3e\u6e38\u620f\u8bc4\u8bc4\u5206\u5427",
                    rate: "\u8bf7\u7ed9\u8bc4\u5206",
                    howToPlay: "\u6e38\u620f\u73a9\u6cd5",
                    visual: "\u89c6\u89c9\u8868\u73b0",
                    feeling: "\u4f53\u9a8c\u611f\u53d7",
                    selectTheType: "\u8bf7\u9009\u62e9\u53cd\u9988\u7c7b\u578b(\u53ef\u590d\u9009)",
                    gamePlay: "\u73a9\u6cd5",
                    art: "\u7f8e\u672f",
                    experience: "\u4f53\u9a8c",
                    bug: "Bug",
                    others: "\u5176\u4ed6",
                    send: "\u9001\u51fa",
                    elaborate: "\u53ef\u4ee5\u8be6\u7ec6\u8bf4\u8bf4\u5417?",
                    thanks: "\u611f\u8c22\u60a8\u63d0\u4f9b\u7684\u53cd\u9988",
                    betterExperience: "\u6211\u4eec\u5c06\u63d0\u4f9b\u66f4\u597d\u7684\u4f53\u9a8c\u7ed9\u60a8",
                    wrong: "\u54ce\u5440\uff01\u51fa\u9519\u5566\uff01",
                    somethingWrong: "\u597d\u50cf\u51fa\u4e86\u70b9\u95ee\u9898\uff0c\u8bf7\u518d\u91cd\u65b0\u9001\u51fa",
                    sendAgain: "\u70b9\u51fb\u91cd\u9001",
                    star1: "\u6709\u5f85\u6539\u8fdb",
                    star2: "\u4e00\u822c\u822c\u5427",
                    star3: "\u8fd8\u7b97\u6ee1\u610f",
                    star4: "\u70b9\u8d5e\u68d2\u68d2\u54d2",
                    star5: "\u5389\u5bb3\u4e86\uff0c\u7b80\u76f4\u725b\u903c"
                },
                E = {
                    "zh-cn": {
                        translation: k
                    },
                    cn: {
                        translation: k
                    },
                    en: {
                        translation: {
                            feedback: "Feedback",
                            rateThisGame: "Rate this game",
                            rate: "Rate it please",
                            howToPlay: "How to Play",
                            visual: "Visual performance",
                            feeling: "Experience and feeling",
                            selectTheType: "Select the type (multiple choices)",
                            gamePlay: "Gameplay",
                            art: "Art",
                            experience: "Experience",
                            bug: "Bug",
                            others: "Others",
                            send: "Send",
                            elaborate: "Can you elaborate on that?",
                            thanks: "Thank you for your feedback",
                            betterExperience: "We will provide you with better experience",
                            wrong: "Oops! An error occurred!",
                            somethingWrong: "There seems to be something wrong. Please send it again",
                            sendAgain: "Send Again",
                            star1: "To be improved",
                            star2: "Just so so",
                            star3: "Not bad",
                            star4: "Great",
                            star5: "Awesome"
                        }
                    },
                    th: {
                        translation: {
                            feedback: "\u0e02\u0e49\u0e2d\u0e40\u0e2a\u0e19\u0e2d\u0e41\u0e19\u0e30",
                            rateThisGame: "\u0e21\u0e32\u0e43\u0e2b\u0e49\u0e04\u0e30\u0e41\u0e19\u0e19\u0e40\u0e01\u0e21\u0e19\u0e35\u0e49\u0e01\u0e31\u0e19\u0e40\u0e16\u0e2d\u0e30",
                            rate: "\u0e42\u0e1b\u0e23\u0e14\u0e43\u0e2b\u0e49\u0e04\u0e30\u0e41\u0e19\u0e19",
                            howToPlay: "\u0e01\u0e15\u0e34\u0e01\u0e32\u0e01\u0e32\u0e23\u0e40\u0e25\u0e48\u0e19\u0e40\u0e01\u0e21",
                            visual: "\u0e1b\u0e23\u0e30\u0e2a\u0e34\u0e17\u0e18\u0e34\u0e20\u0e32\u0e1e\u0e02\u0e2d\u0e07\u0e20\u0e32\u0e1e",
                            feeling: "\u0e04\u0e27\u0e32\u0e21\u0e23\u0e39\u0e49\u0e2a\u0e36\u0e01\u0e43\u0e19\u0e01\u0e32\u0e23\u0e40\u0e25\u0e48\u0e19",
                            selectTheType: "\u0e40\u0e25\u0e37\u0e2d\u0e01\u0e1b\u0e23\u0e30\u0e40\u0e20\u0e17 (\u0e2b\u0e25\u0e32\u0e22\u0e2d\u0e31\u0e19)",
                            gamePlay: "\u0e14\u0e49\u0e32\u0e19\u0e01\u0e15\u0e34\u0e01\u0e32",
                            art: "\u0e28\u0e34\u0e25\u0e1b\u0e30",
                            experience: "\u0e04\u0e27\u0e32\u0e21\u0e23\u0e39\u0e49\u0e2a\u0e36\u0e01",
                            bug: "Bug",
                            others: "\u0e14\u0e49\u0e32\u0e19\u0e2d\u0e37\u0e48\u0e19\u0e46",
                            send: "\u0e2a\u0e48\u0e07",
                            elaborate: "\u0e23\u0e1a\u0e01\u0e27\u0e19\u0e2d\u0e18\u0e34\u0e1a\u0e32\u0e22\u0e40\u0e1e\u0e34\u0e48\u0e21\u0e40\u0e15\u0e34\u0e21",
                            thanks: "\u0e02\u0e2d\u0e1a\u0e04\u0e38\u0e13\u0e2a\u0e33\u0e2b\u0e23\u0e31\u0e1a\u0e04\u0e27\u0e32\u0e21\u0e04\u0e34\u0e14\u0e40\u0e2b\u0e47\u0e19",
                            betterExperience: "\u0e17\u0e32\u0e07\u0e40\u0e23\u0e32\u0e08\u0e30\u0e19\u0e33\u0e01\u0e25\u0e31\u0e1a\u0e44\u0e1b\u0e1b\u0e23\u0e31\u0e1a\u0e1b\u0e23\u0e38\u0e07\u0e40\u0e1e\u0e37\u0e48\u0e2d\u0e21\u0e2d\u0e1a\u0e1b\u0e23\u0e30\u0e2a\u0e1a\u0e01\u0e32\u0e23\u0e13\u0e4c\u0e17\u0e35\u0e48\u0e14\u0e35\u0e01\u0e27\u0e48\u0e32\u0e43\u0e2b\u0e49\u0e01\u0e31\u0e1a\u0e04\u0e38\u0e13",
                            wrong: "\u0e2d\u0e38\u0e4a\u0e22! \u0e21\u0e35\u0e2d\u0e30\u0e44\u0e23\u0e1a\u0e32\u0e07\u0e2d\u0e22\u0e48\u0e32\u0e07\u0e1c\u0e34\u0e14\u0e1b\u0e01\u0e15\u0e34!",
                            somethingWrong: "\u0e14\u0e39\u0e40\u0e2b\u0e21\u0e37\u0e2d\u0e19\u0e08\u0e30\u0e21\u0e35\u0e1b\u0e31\u0e0d\u0e2b\u0e32\u0e1c\u0e34\u0e14\u0e1b\u0e01\u0e15\u0e34\u0e1a\u0e32\u0e07\u0e2d\u0e22\u0e48\u0e32\u0e07\u0e40\u0e01\u0e34\u0e14\u0e02\u0e36\u0e49\u0e19 \u0e01\u0e23\u0e38\u0e13\u0e32\u0e2a\u0e48\u0e07\u0e43\u0e2b\u0e21\u0e48\u0e2d\u0e35\u0e01\u0e04\u0e23\u0e31\u0e49\u0e07",
                            sendAgain: "\u0e04\u0e25\u0e34\u0e01\u0e40\u0e1e\u0e37\u0e48\u0e2d\u0e2a\u0e48\u0e07\u0e43\u0e2b\u0e21\u0e48",
                            star1: "\u0e1b\u0e23\u0e31\u0e1a\u0e1b\u0e23\u0e38\u0e07",
                            star2: "\u0e1b\u0e32\u0e19\u0e01\u0e25\u0e32\u0e07",
                            star3: "\u0e1e\u0e2d\u0e44\u0e14\u0e49",
                            star4: "\u0e40\u0e23\u0e34\u0e48\u0e14",
                            star5: "\u0e2a\u0e38\u0e14\u0e1b\u0e31\u0e07\u0e21\u0e32\u0e01"
                        }
                    },
                    vn: {
                        translation: {
                            feedback: "Feedbacks",
                            rateThisGame: "H\xe3y feedback cho game n\xe0y nh\xe9 !",
                            rate: "H\xe3y cho \u0111i\u1ec3m",
                            howToPlay: "C\xe1ch ch\u01a1i",
                            visual: "Tr\u1ef1c quan",
                            feeling: "Tr\u1ea3i nghi\u1ec7m",
                            selectTheType: "Lo\u1ea1i feedback (c\xf3 th\u1ec3 ch\u1ecdn nhi\u1ec1u)",
                            gamePlay: "Gameplay",
                            art: "Thi\u1ebft k\u1ebf",
                            experience: "Tr\u1ea3i nghi\u1ec7m",
                            bug: "Bug",
                            others: "Kh\xe1c",
                            send: "G\u1eedi \u0111i",
                            elaborate: "C\xf3 th\u1ec3 n\xf3i chi ti\u1ebft h\u01a1n kh\xf4ng?",
                            thanks: "C\u1ea3m \u01a1n feedback c\u1ee7a b\u1ea1n!",
                            betterExperience: "Ch\xfang t\xf4i s\u1ebd mang \u0111\u1ebfn cho b\u1ea1n tr\u1ea3i nghi\u1ec7m t\u1ed1t h\u01a1n ! ",
                            wrong: "Ai da~ X\u1ea3y ra l\u1ed7i r\u1ed3i !",
                            somethingWrong: "D\u01b0\u1eddng nh\u01b0 c\xf3 v\u1ea5n \u0111\u1ec1 r\u1ed3i, h\xe3y g\u1eedi l\u1ea1i nha",
                            sendAgain: "B\u1ea5m \u0111\u1ec3 g\u1eedi l\u1ea1i",
                            star1: "c\u1ea7n c\u1ea3i thi\u1ec7n nha",
                            star2: "c\u0169ng th\u01b0\u1eddng th\xf4i",
                            star3: "kh\xe1 l\xe0 okay",
                            star4: "c\u0169ng th\xedch \u0111\xf3",
                            star5: "game x\u1ecbn \u0111\u1ea5y!"
                        }
                    }
                };
            w.a.use(y.e).init({
                resources: E,
                lng: "cn",
                fallbackLng: "en",
                interpolation: {
                    escapeValue: !1
                }
            });
            var z, S, O, j, N, T, H, C, L, A, F, Y = w.a,
                M = m.default.div(z || (z = Object(d.a)(["\n  position: relative;\n  width:100%;\n  padding-bottom:120%;\n  .leftleg-kit {\n    position: absolute;\n    bottom:37%;\n    right:10%;\n    width:50%;\n    padding-bottom:37%;\n    background:url('/op/cq9feedback/images/L_thigh.png')no-repeat;\n    background-size:100%;\n    z-index:8;\n  };\n  .rightleg-kit {\n    position: absolute;\n    left:18%;\n    bottom:4%;\n    width:63%;\n    padding-bottom: 58%;\n    background:url('/op/cq9feedback/images/R_leg.png')no-repeat;\n    background-size:100%;\n    z-index:7;\n  };\n  .leftleg-calf-kit {\n    @keyframes calf {\n      0% { transform: rotate(0deg) }\n      50% {transform: rotate(8deg) }\n      100% { transform: rotate(0deg) }\n    };\n    @keyframes feet {\n      0% { transform: translate(-45%,45%)rotate(-10deg) }\n      50% {transform: translate(-45%,45%)rotate(0deg) }\n      100% { transform: translate(-45%,45%)rotate(-10deg) }\n    };\n    position: absolute;\n    left:11%;\n    bottom:27%;\n    width:42%;\n    padding-bottom:49%; \n    z-index:6;\n    transform-origin:top right;\n    animation:calf 2.5s infinite;\n    &:before {\n      content:'';\n      position: absolute;\n      top:0;\n      left:0;\n      width:100%;\n      height:100%;\n      z-index:2;\n      background:url('/op/cq9feedback/images/L_lower_leg.png')no-repeat;\n      background-size:100%;\n    }\n    &:after {\n      content:'';\n      position: absolute;\n      bottom:0;\n      left:0;\n      width:52%;\n      padding-bottom:31%;\n      background:url('/op/cq9feedback/images/L_foot.png')no-repeat;\n      background-size:100%;\n      transform: translate(-45%,45%)rotate(-10deg);\n      z-index:1;\n      transform-origin:top right;\n      animation:feet 2.5s infinite;\n    }\n  };\n  .notebook-kit {\n    @keyframes notebook {\n      0% { transform:translate(5%,15%)rotate(-10deg) }\n      100% { transform:translate(0%,0%)rotate(0deg) }\n    };\n    position: absolute;\n    top:14%;\n    left:25%;\n    width:55%;\n    padding-bottom:40%;\n    background:url('/op/cq9feedback/images/notebook.png')no-repeat;\n    background-size:100%;\n    transform-origin:center;\n    animation:notebook 2s;\n    z-index:5;\n  };\n  .hand-kit {\n    @keyframes hand {\n      5% { transform:rotate(15deg)}\n      10% { transform:rotate(0deg)}\n      15% { transform:rotate(15deg)}\n      20% { transform:rotate(0deg)}\n      25% { transform:rotate(15deg)}\n      30% { transform:rotate(0deg)}\n      35% { transform:rotate(15deg)}\n      50% { transform:rotate(0deg)}\n      100% { transform:rotate(0deg)}\n    };\n    position: absolute;\n    top:35%;\n    right:22%;\n    width:14%;\n    padding-bottom:10%;\n    background:url('/op/cq9feedback/images/hand.png')no-repeat;\n    background-size:100%;\n    transform-origin:center right;\n    animation:hand 3s 1s infinite linear;\n    z-index:8;\n  };\n  .face-kit {\n    @keyframes face {\n      0% { transform: rotate(0deg)}\n      10% { transform: rotate(-12deg)}\n      100% { transform: rotate(0deg)}\n    };\n    position: absolute;\n    top:10%;\n    right:16%;\n    width:20%;\n    padding-bottom:17%;\n    background:url('/op/cq9feedback/images/face.png')no-repeat;\n    background-size:100%;\n    transform-origin:center bottom;\n    animation: face 5s 3s infinite;\n    z-index:8;\n  };\n  .body-kit {\n    position: absolute;\n    top:18%;\n    right:0%;\n    width:40%;\n    padding-bottom:40%;\n    background:url('/op/cq9feedback/images/body.png')no-repeat;\n    background-size:100%;\n    z-index:7;\n  };\n  .hair-kit {\n    @keyframes hair {\n      0% { transform:rotate(0deg);}\n      20% { transform:rotate(6deg);}\n      100% { transform:rotate(0deg);}\n    };\n    position: absolute;\n    top:0.5%;\n    right:0%;\n    width:40%;\n    padding-bottom:40%;\n    background:url('/op/cq9feedback/images/hair.png')no-repeat;\n    background-size:100%;\n    transform-origin:top left;\n    animation: hair 5s 3s infinite;\n    z-index:1;\n  };\n"]))),
                P = function() {
                    return o.a.createElement(M, null, o.a.createElement("div", {
                        className: "hair-kit"
                    }), o.a.createElement("div", {
                        className: "body-kit"
                    }), o.a.createElement("div", {
                        className: "face-kit"
                    }), o.a.createElement("div", {
                        className: "hand-kit"
                    }), o.a.createElement("div", {
                        className: "notebook-kit"
                    }), o.a.createElement("div", {
                        className: "leftleg-calf-kit"
                    }), o.a.createElement("div", {
                        className: "rightleg-kit"
                    }), o.a.createElement("div", {
                        className: "leftleg-kit"
                    }))
                },
                _ = e(116),
                I = m.default.div(O || (O = Object(d.a)(["\n  width: 100%;\n  display: flex;\n  align-items: flex-start;\n  justify-content: center;\n  @media (min-width: 768px) {\n    width: 80%;\n    margin: 0 auto;\n  }\n"]))),
                X = m.default.div(j || (j = Object(d.a)(["\n  position: relative;\n  width: 20%;\n  padding-bottom: 20%;\n  height: 100%;\n"]))),
                D = m.default.div((function(n) {
                    var t = n.index;
                    return "\n  @keyframes transitionScale {\n    0%{ transform:translate(-50%,-50%)scale(0.5,0.5) }\n    100%{ transform:translate(-50%,-50%)scale(1,1) }\n  };\n  ".concat(function() {
                        for (var n = "", t = 1; t < 6; t += 1) n += "\n      @keyframes transitionScaleActive-".concat(t, " {\n        0%{ transform:translate(-50%,-50%)scale(0,0) }\n        50%{ transform:translate(-50%,-50%)scale(").concat(1 + .1 * t, ",").concat(1 + .1 * t, ") }\n        100%{ transform:translate(-50%,-50%)scale(1,1) }\n      };\n    ");
                        return Object(m.css)(S || (S = Object(d.a)(["\n    ", "\n  "])), n)
                    }(), ";\n  position: absolute;\n  top:50%;\n  left:50%;\n  transform:translate(-50%,-50%);\n  width:100%;\n  height:100%;\n  max-width:80px;\n  max-height:80px;\n  background:url('/op/cq9feedback/images/ic_star_sprite.png')no-repeat;\n  background-size:100%;\n  background-position-y:100%;\n  -webkit-tap-highlight-color: transparent;\n  animation:transitionScale .3s;\n  &.active-").concat(t + 1, " {\n    background-position-y:0%;\n    animation:transitionScaleActive-").concat(t + 1, " .3s;\n  }\n")
                })),
                B = m.default.div(N || (N = Object(d.a)(["\n  font-family: sans-serif;\n  font-size: 18px;\n  font-weight: bold;\n  padding-bottom: 10px;\n  color: #3e4356;\n  letter-spacing: ", ";\n  text-align: center;\n  @media (min-width: 568px) {\n    padding-bottom: 5px;\n    font-size: 20px;\n  }\n"])), (function(n) {
                    return "cn" === n.lang ? "1.5px" : "0"
                })),
                G = m.default.div(T || (T = Object(d.a)(["\n  padding-top: 10px;\n  font-size: 16px;\n  font-style: italic;\n  color: #949d92;\n  text-align: center;\n  @media (min-width: 568px) {\n    padding-top: 5px;\n    font-size: 18px;\n  }\n"]))),
                R = function(n) {
                    var t, e = n.listKey,
                        a = void 0 === e ? Number() : e,
                        r = n.id,
                        i = void 0 === r ? Number() : r,
                        s = n.title,
                        c = void 0 === s ? "" : s,
                        l = n.rateScoreKey,
                        d = void 0 === l ? {} : l,
                        p = n.setRateScore,
                        m = void 0 === p ? u.a : p,
                        g = n.lang,
                        h = d.rate ? d.rate : 0,
                        b = Object(x.a)().t;
                    switch (h) {
                        case 0:
                            t = b("rate");
                            break;
                        case 1:
                            t = b("star1");
                            break;
                        case 2:
                            t = b("star2");
                            break;
                        case 3:
                            t = b("star3");
                            break;
                        case 4:
                            t = b("star4");
                            break;
                        case 5:
                            t = b("star5");
                            break;
                        default:
                            t = ""
                    }
                    return o.a.createElement(o.a.Fragment, null, o.a.createElement(B, {
                        lang: g
                    }, c), o.a.createElement(I, null, [1, 2, 3, 4, 5].map((function(n, t) {
                        var e = "active-".concat(t + 1);
                        return o.a.createElement(X, {
                            key: t
                        }, o.a.createElement(D, {
                            index: t,
                            className: f()(Object(_.a)({}, e, !(n > h))),
                            onClick: function() {
                                m((function(n) {
                                    return n.set(a, {
                                        id: i,
                                        rate: t + 1
                                    })
                                }))
                            }
                        }))
                    }))), o.a.createElement(G, null, t))
                },
                J = e(259),
                W = e(258),
                U = Object(v.b)({
                    mobileStraight: 110,
                    mobileStraightCover: 110 * 1.1,
                    mobileHorizontal: 120,
                    mobileHorizontalCover: 138,
                    ipadHorizontal: 150,
                    ipadHorizontalCover: 180,
                    ipadProHorizontal: 175,
                    ipadProHorizontalCover: 210,
                    animeSecond: 2.5
                }),
                K = Object(v.b)({
                    StyledApp: {
                        keyframes: "\n      @keyframes keepFlash {\n        0% { opacity:0; }\n        50% { opacity:1; }\n        100% { opacity:0; }\n      };\n      @keyframes containerAnimeForSendMsg {\n        35% {\n          height:".concat(1.45 * U.get("mobileStraight"), "px; \n          transform:translateX(-50%)translateY(").concat(1.45 * U.get("mobileStraight") / 2, "px);\n          bottom:50%; \n        }\n        50% {\n          height:").concat(1.45 * U.get("mobileStraight"), "px; \n          transform:translateX(-50%)translateY(").concat(1.45 * U.get("mobileStraight") / 2, "px);\n          bottom:50%; \n        }\n        100% { \n          height:0px;\n          transform:translateX(-50%)translateY(").concat(1.45 * U.get("mobileStraight") / 2, "px); \n          bottom:50%; \n        }\n      };\n    "),
                        rwd: {
                            mobileHorizontal: "\n        @keyframes containerAnimeForSendMsg {\n          35% {\n            height:".concat(1.45 * U.get("mobileHorizontal"), "px; \n            transform:translateX(-50%)translateY(").concat(1.45 * U.get("mobileHorizontal") / 2, "px);\n            bottom:50%; \n          }\n          50% {\n            height:").concat(1.45 * U.get("mobileHorizontal"), "px; \n            transform:translateX(-50%)translateY(").concat(1.45 * U.get("mobileHorizontal") / 2, "px);\n            bottom:50%; \n          }\n          100% { \n            height:0px;\n            transform:translateX(-50%)translateY(").concat(1.45 * U.get("mobileHorizontal") / 2, "px); \n            bottom:50%; \n          }\n        };\n      "),
                            ipadHorizontal: "\n        @keyframes containerAnimeForSendMsg {\n          35% {\n            height:".concat(1.45 * U.get("ipadHorizontal"), "px; \n            transform:translateX(-50%)translateY(").concat(1.45 * U.get("ipadHorizontal") / 2, "px);\n            bottom:50%; \n          }\n          50% {\n            height:").concat(1.45 * U.get("ipadHorizontal"), "px; \n            transform:translateX(-50%)translateY(").concat(1.45 * U.get("ipadHorizontal") / 2, "px);\n            bottom:50%; \n          }\n          100% { \n            height:0px;\n            transform:translateX(-50%)translateY(").concat(1.45 * U.get("ipadHorizontal") / 2, "px); \n            bottom:50%; \n          }\n        };\n      "),
                            ipadProHorizontal: "\n        @keyframes containerAnimeForSendMsg {\n          35% {\n            height:".concat(1.45 * U.get("ipadProHorizontal"), "px; \n            transform:translateX(-50%)translateY(").concat(1.45 * U.get("ipadProHorizontal") / 2, "px);\n            bottom:50%; \n          }\n          50% {\n            height:").concat(1.45 * U.get("ipadProHorizontal"), "px; \n            transform:translateX(-50%)translateY(").concat(1.45 * U.get("ipadProHorizontal") / 2, "px);\n            bottom:50%; \n          }\n          100% { \n            height:0px;\n            transform:translateX(-50%)translateY(").concat(1.45 * U.get("ipadProHorizontal") / 2, "px); \n            bottom:50%; \n          }\n        };\n      ")
                        },
                        name: {
                            keepFlash: "keepFlash",
                            container: "containerAnimeForSendMsg"
                        }
                    },
                    EnvelopeTop: {
                        keyframes: "\n      @keyframes EnvelopeTopAnimeForSendMsg {\n        50% { transform:translateY(100%);bottom:50%; }\n        100% { transform:translateY(100%);bottom:50%; }\n      };\n      @keyframes TickAnimeForSendMsg {\n        75% { opacity:0 }\n        100% { opacity:1 } \n      };\n      @keyframes shadowAAnimeForSendMsg {\n        75% { opacity:0 }\n        100% { opacity:1 }\n      };\n      @keyframes shadowBAnimeForSendMsg {\n        0% { opacity:0 }\n        50% { opacity:1 }\n        100% { opacity:1 }\n      };\n    ",
                        name: {
                            main: "EnvelopeTopAnimeForSendMsg",
                            Tick: "TickAnimeForSendMsg",
                            shadowA: "shadowAAnimeForSendMsg",
                            shadowB: "shadowBAnimeForSendMsg"
                        }
                    },
                    EnvelopeBottom: {
                        keyframes: "\n      @keyframes EnvelopeBottomAnimeForSendMsg {\n        0% { transform:translateY(0%); bottom:0; }\n        50% {  transform:translateY(50%); bottom:50%; }\n        100% { transform:translateY(50%); bottom:50%; }\n      };\n    ",
                        name: {
                            main: "EnvelopeBottomAnimeForSendMsg"
                        }
                    },
                    EnvelopeCover: {
                        keyframes: "\n      @keyframes EnvelopeCoverAnimeForSendMsg {\n        50% { \n          border-radius: 0 0 0 0;\n          transform:translateY(".concat(U.get("mobileStraightCover") - 1.68 * U.get("mobileStraight") / 2, "px)rotateX(180deg); \n          bottom:50%; \n          z-index:1;\n        }\n        100% { \n          border-radius: 0 0 0 0;\n          transform:translateY(").concat(U.get("mobileStraightCover") - 1.68 * U.get("mobileStraight") / 2, "px)rotateX(0deg); \n          bottom:50%;\n          z-index:5;\n        }\n      };\n      @keyframes EnvelopeCoverTextAnimeForSendMsg {\n        75% { opacity:0; }\n        100% { opacity:1; }\n      };\n      // RWD\n    "),
                        rwd: {
                            mobileHorizontal: "\n        @keyframes EnvelopeCoverAnimeForSendMsg {\n          50% { \n            border-radius: 0 0 0 0;\n            transform:translateY(".concat(U.get("mobileHorizontalCover") - 1.68 * U.get("mobileHorizontal") / 2, "px)rotateX(180deg); \n            bottom:50%;\n            z-index:1\n          }\n          100% { \n            border-radius: 0 0 0 0;\n            transform:translateY(").concat(U.get("mobileHorizontalCover") - 1.68 * U.get("mobileHorizontal") / 2, "px)rotateX(0deg); \n            bottom:50%;\n            z-index:5;\n          }\n        };\n      "),
                            ipadHorizontal: "\n        @keyframes EnvelopeCoverAnimeForSendMsg {\n          50% { \n            border-radius: 0 0 0 0;\n            transform:translateY(".concat(U.get("ipadHorizontalCover") - 1.68 * U.get("ipadHorizontal") / 2, "px)rotateX(180deg); \n            bottom:50%;\n            z-index:1\n          }\n          100% { \n            border-radius: 0 0 0 0;\n            transform:translateY(").concat(U.get("ipadHorizontalCover") - 1.68 * U.get("ipadHorizontal") / 2, "px)rotateX(0deg); \n            bottom:50%;\n            z-index:5;\n          }\n        };\n      "),
                            ipadProHorizontal: "\n        @keyframes EnvelopeCoverAnimeForSendMsg {\n          50% { \n            border-radius: 0 0 0 0;\n            transform:translateY(".concat(U.get("ipadProHorizontalCover") - 1.68 * U.get("ipadProHorizontal") / 2, "px)rotateX(180deg); \n            bottom:50%;\n            z-index:1\n          }\n          100% { \n            border-radius: 0 0 0 0;\n            transform:translateY(").concat(U.get("ipadProHorizontalCover") - 1.68 * U.get("ipadProHorizontal") / 2, "px)rotateX(0deg); \n            bottom:50%;\n            z-index:5;\n          }\n        };\n      ")
                        },
                        name: {
                            main: "EnvelopeCoverAnimeForSendMsg",
                            text: "EnvelopeCoverTextAnimeForSendMsg"
                        }
                    }
                }),
                q = m.default.div((function(n) {
                    var t = n.EnvelopeData;
                    return "\n  margin-top:5px;\n  border-radius: 20px;\n  box-sizing:border-box;\n  padding:0 5%;\n  padding-bottom:".concat(.8 * t.get("mobileStraight"), "px;\n  background-color: #83a473;\n  .title {\n    text-align:center;\n    font-size:18px;\n    color:#fff;\n    padding:20px 0;\n  }\n  .textare-area {\n    position: relative;\n    box-sizing:border-box;\n    margin-top:5px;\n    padding:12px 12px 30px 12px;\n    border-radius: 15px;\n    border:1px solid #e2e2e2;\n    background-color:#fff;\n    textarea {\n      font-family: Arial;\n      width:100%;\n      padding:0;\n      margin:0;\n      min-height:65px;\n      font-size:16px;\n      color:#5a564b;\n      outline:none;\n      border:none;\n      -webkit-tap-highlight-color: transparent;\n      line-height:1.2;\n      &::placeholder {\n        font-size:16px;\n        color:#9f9f9f;\n      }\n    }\n    .limitTip {\n      position: absolute;\n      right:12px;\n      bottom:12px;\n      font-size:16px;\n      color:#9f9f9f;\n    }\n  }\n  /* \u6a6b\u677f rwd mobile iPhone 5SE*/\n  @media (min-width: 568px) {\n    padding-bottom:").concat(.8 * t.get("mobileHorizontal"), "px;\n  };\n  @media (min-width: 768px) and (min-height: 1024px) {\n    padding-bottom:").concat(.8 * t.get("mobileHorizontal"), "px;\n  };\n  @media (min-width: 1024px) {\n    padding-bottom:").concat(.85 * t.get("ipadHorizontal"), "px;\n  };\n  @media (min-width: 1366px) {\n    padding-bottom:").concat(.85 * t.get("ipadProHorizontal"), "px;\n  };\n")
                })),
                V = m.default.ul(H || (H = Object(d.a)(["\n  @keyframes scaleIn {\n    0% { opacity:0;transform:scale(0); }\n    100% { opacity:1;transform:scale(1); }\n  }\n  width: 100%;\n  display:flex;\n  flex-wrap: wrap;\n  li {\n    width:31.33333%;\n    margin-right:3%;\n    margin-bottom:8px;\n    border-radius:50px;\n    display:flex;\n    align-items:center;\n    justify-content:center;\n    padding:8px 0;\n    background-color:#67825b;\n    transition:.2s;\n    span {\n      color:#fff;\n      font-size:16px;\n    }\n    &:nth-child(3n) {\n      margin-right:0;\n    }\n    svg {\n      width:22px;\n      height:22px;\n      fill:#fff;\n      margin-left:4px;\n    }\n    &.check {\n      background-color:#fff;\n      animation:scaleIn .2s;\n      span {\n        color:#597c52;\n      }\n      svg {\n        stroke: #597c52;\n        stroke-width:3px;\n      }\n    }\n  }\n  @media (min-width: 568px) {\n    li {\n      width:23.5%;\n      margin-right:2%;\n      &:nth-child(3n) {\n      margin-right:2%;\n    }\n      &:nth-child(4n) {\n        margin-right:0;\n      }\n    }\n  }\n"]))),
                $ = function(n) {
                    var t = n.tagList,
                        e = void 0 === t ? Object(v.a)() : t,
                        a = n.textareaLen,
                        r = void 0 === a ? Number() : a,
                        i = n.textareaEl,
                        s = void 0 === i ? "" : i,
                        c = n.setTagList,
                        l = void 0 === c ? u.a : c,
                        d = n.setTextareaLen,
                        p = void 0 === d ? u.a : d,
                        m = n.setTagCheckd,
                        g = void 0 === m ? u.a : m,
                        h = n.t;
                    return o.a.createElement(q, {
                        EnvelopeData: U
                    }, o.a.createElement("div", {
                        className: "title"
                    }, h("selectTheType")), o.a.createElement(V, null, e.filter((function(n) {
                        return 6 !== n.get("id")
                    })).map((function(n, t) {
                        return o.a.createElement("li", {
                            key: t,
                            className: f()({
                                check: n.get("status")
                            }),
                            onClick: function() {
                                l((function(e) {
                                    return e.setIn([t, "status"], !n.get("status"))
                                })), g((function(t) {
                                    return ~t.indexOf(n.get("id")) ? t.filter((function(t) {
                                        return t !== n.get("id")
                                    })) : t.push(n.get("id"))
                                }))
                            }
                        }, o.a.createElement("span", null, n.get("name")), n.get("status") ? o.a.createElement(J.a, null) : o.a.createElement(W.a, null))
                    }))), o.a.createElement("div", {
                        className: "textare-area"
                    }, o.a.createElement("textarea", {
                        ref: s,
                        maxLength: 100,
                        placeholder: h("elaborate"),
                        onChange: function(n) {
                            n.target.value = n.target.value.replace(/^\s+/g, ""), p(String(s.current.value.length))
                        }
                    }), o.a.createElement("div", {
                        className: "limitTip"
                    }, r, "/", 100)))
                },
                Q = "rgba(157, 229, 253, 0.59)",
                Z = "rgba(46, 46, 150, 0.5)",
                nn = "#ffffff26",
                tn = "#9796f126",
                en = "#645DF6",
                an = m.default.div(L || (L = Object(d.a)(["\n  @keyframes shineMe {\n    from{ height: 3px; }\n    to{ height: 100%;}\n  }\n  @keyframes shineMe2 {\n    from{ opacity: .3; }\n    to{ opacity: .9; }\n  }\n  overflow:hidden;\n  width:100%;\n  height:100%;\n  background:linear-gradient(#2E2E96,#040849);\n  .wrapper{\n    width: 80%;\n    height: 100%;\n    margin: 0 auto;\n    position: relative;\n    background: radial-gradient(circle, ", ", transparent 40%);\n  }\n  .stars{\n    width:100%;\n    height:100%;\n    .star{ \n      position: absolute;\n      width: 10px;\n      height: 10px;\n      &::before, &::after{\n        content: '';\n        position: absolute;\n        top: 50%;\n        left: 50%;\n        width: 3px;\n        height: 3px;\n        background: #fff;\n        border-radius: 50%;\n        animation: shineMe 1s ease-in-out infinite alternate;\n        transform-origin: middle;\n        box-shadow: 0 0 6px 3px ", ";\n      }\n      &::before{ transform: translate(-50%, -50%); }\n      &::after{ transform: translate(-50%, -50%)rotate(90deg);}\n      &:nth-child(1){\n        top: 10%;\n        left: 3%;\n        &::before, &::after{ animation-delay: 1s; }\n      }\n      &:nth-child(2){\n        top: 20%;\n        left: 23%;\n        transform: scale(1.4);\n      }\n      &:nth-child(3){\n        top: 15%;\n        right: 7%;\n        transform: scale(.7);\n      }\n      &:nth-child(4){\n        top: 9%;\n        left: 59%;\n        transform: scale(1.5);\n        &::before, &::after{ animation-delay: 1.5s; }\n      }\n      &:nth-child(5){\n        top: 19%;\n        left: 69%;\n        &::before, &::after{ animation-delay: 1s; }\n      }\n      &:nth-child(6){\n        top: 49%;\n        left: 0;\n        transform: scale(.7);\n        &::before, &::after{ animation-delay: 1s; }\n      }\n      &:nth-child(7){\n        top: 40%;\n        right: 15%;\n        transform: scale(.7);\n      }\n    }\n  }\n  .stars2{\n    width:100%;\n    height:100%;\n    .star{ \n      position: absolute;\n      width: 10px;\n      height: 10px;\n      animation: swing2 8s linear infinite;\n      &::before{\n        content: '';\n        position: absolute;\n        top: 50%;\n        left: 50%;\n        width: 3px;\n        height: 3px;\n        background: ", ";\n        border-radius: 50%;\n        animation: shineMe2 2s ease-in-out infinite alternate;\n        transform: translate(-50%, -50%);\n        transform-origin: middle;\n        box-shadow: 0 0 6px 3px ", ";\n        opacity: .1;\n      }\n      &:nth-child(1){\n        top: 20%;\n        right: 3%;\n      }\n      &:nth-child(2){\n        top: 30%;\n        left: 6%;\n      }\n      &:nth-child(3){\n        top: 3%;\n        left: 32%;\n      }\n      &:nth-child(4){\n        top: 9%;\n        left: 69%;\n      }\n      &:nth-child(5){\n        top: 40%;\n        right: 9%;\n      }\n      &:nth-child(6){\n        top: 50%;\n        left: 25%;\n      }\n    }\n  }\n  /* Meteor Stars */\n  @keyframes shoot {\n    0% {transform: translate(500px, -500px);}\n    100% {transform: translate(-500px, 500px);}\n  }\n  .meteor-stars {\n    position: absolute;\n    top:0%;\n    left: 50%;\n    width:100%;\n    height:100%;\n    z-index: 2;\n  }\n  .meteor-head {\n    position: relative;\n    width: 0; \n    height: 0; \n    border-top: 3px solid transparent;\n    border-bottom: 3px solid transparent;\n    border-right: 9px solid #fff;\n    transform: rotate(-45deg);\n  }\n  .meteor-trail {\n    position: relative;\n    width: 185px;\n    height: 5px;\n    margin-top: -73px;\n    margin-left: -22px;\n    background: linear-gradient(310deg, rgba(0,0,0,0), rgba(255,255,255,0.3));\n    transform: rotate(-45deg);\n  }\n  .meteor:first-of-type {\n    margin-top: -350px;\n    margin-left: 400px;\n    animation: shoot 2s linear infinite;\n    animation-delay: 0.2s;\n  }\n  .meteor:nth-of-type(2) {\n    margin-top: 10px;\n    margin-left: 360px;\n    animation: shoot 2.5s linear infinite;\n    animation-delay: 0.4s;\n  }\n  .meteor:nth-of-type(3) {\n    margin-top: 70px;\n    margin-left: 350px;\n    animation: shoot 2.5s linear infinite;\n    animation-delay: 0.2s;\n  }\n  .meteor:nth-of-type(4) {\n    margin-top: 70px;\n    margin-left: 390px;\n    animation: shoot 2s linear infinite;\n    animation-delay: 0.2s;\n  }\n  .meteor:nth-of-type(5) {\n    margin-top: -160px;\n    margin-left: 280px;\n    animation: shoot 1.5s linear infinite;\n    animation-delay: 0.4s;\n  }\n  /* Moon */\n  .moon {\n    position: absolute;\n    top:30%;\n    left:50%;\n    transform:translate(-50%,-50%);\n    z-index: 2;\n    width:120px;\n    height:120px;\n    border-radius:100%;\n    background:#fff;\n    box-shadow:0px 20px 50px ", "; \n    -webkit-tap-highlight-color:transparent;\n    .face {\n      position:absolute;\n      left: 23px;\n      top: 45px;\n      width: 75px;\n      height: 38px;\n      opacity:0;\n      transition: opacity 0.3s ease-in-out;\n    }\n    &:hover { \n      cursor: pointer;\n      .face { opacity:1; }\n    }\n    &::before {\n      content:'';\n      position:absolute;\n      top: 40px;\n      left: 82px;\n      width:30px;\n      height:30px;\n      border-radius:100%;\n      background:linear-gradient(to bottom, rgba(0,0,0,0.05), transparent 40%);\t\n      transform:rotate(-60deg);\n    }\n    &::after {\n      content:'';\n      position:absolute;\n      top: 80px;\n      left: 25px;\n      width:50px;\n      height:50px;\n      border-radius:100px;\n      background:linear-gradient(to bottom, rgba(0,0,0,0.05), transparent 40%);\t\n      transform:rotate(-20deg);\n    }\n    ul li {\n      list-style:none;\n      background:#fff;\n      background:radial-gradient(circle, #fff 0%, transparent 90%);\n      position:absolute;\n      border-radius:100%;\n      opacity:0.2;\n      transform:scale(1);\n      transition:all 0.5s ease;\n    }\n    li:nth-child(1) {\n      width:120%;\n      height:120%;\n      left:-10px;\n      top:-10px;\n    }\n    li:nth-child(2) {\n      width:140%;\n      height:140%;\n      left:-22.5px;\n      top:-22.5px;\n    }\n    li:nth-child(3) {\n      width:160%;\n      height:160%;\n      left:-35px;\n      top:-35px;\n    }\n  }\n  /* words */\n  @keyframes bobbing{\n    0%{ transform:translateY(0px) rotate(5deg);}\n    100%{ transform:translateY(-10px) rotate(-5deg);}\n  }\n  .words {\n    position:absolute;\n    left:50%;\n    bottom:30%;\n    transform:translateX(-50%);\n    color:rgba(255,255,255,1);\n    font-size: 1.6rem;\n    line-height:0.75;\n    white-space:nowrap;\n    z-index:9;\n    span{\n      animation:bobbing 4s ease-in-out infinite alternate;\n      display:inline-block;\n      transform-origin:50% 100%;\n      padding: 0 2px;\n      ", "\n    }\n  }\n  /* ocean & wave */\n  @keyframes wave {\n    0% { margin-left:0; }\n    100% { margin-left:-1600px; }\n  }\n  @keyframes swell {\n    0%,100% { transform: translate3d(0,-25px,0); }\n    50% {transform: translate3d(0,5px,0); }\n  }\n  .ocean { \n    position:absolute;\n    left:50%;\n    bottom:0;\n    transform:translateX(-50%);\n    width:125%;\n    height:150px;\n    .content {\n      position: relative;\n      width: 100%;\n      height: 100%;\n    }\n  }\n  .wave {\n    position: absolute;\n    top:0;\n    width: 6400px;\n    height: 100%;\n    transform: translate3d(0, 0, 0);\n    background: url('/op/cq9feedback/images/wave.svg')repeat-x; \n    animation: wave 7s cubic-bezier( 0.36, 0.45, 0.63, 0.53) infinite;\n  }\n  .wave:nth-of-type(2) {\n    animation: wave 7s cubic-bezier( 0.36, 0.45, 0.63, 0.53) -.125s infinite, swell 7s ease -1.25s infinite;\n    opacity: 1;\n  }\n"])), Z, nn, en, tn, Q, function() {
                    for (var n = "", t = 0; t < 20; t += 1) n += "&:nth-of-type(".concat(t, "){ animation-delay: ").concat(t / -1.5, "s;} ");
                    return Object(m.css)(C || (C = Object(d.a)(["", ""])), n)
                }()),
                on = function() {
                    var n = function() {
                        for (var n = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : Number, t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : String, e = [], a = 0; a < n; a++) e.push(o.a.createElement("div", {
                            key: a,
                            className: t
                        }));
                        return e
                    };
                    return o.a.createElement(an, null, o.a.createElement("div", {
                        className: "wrapper",
                        onClick: function() {}
                    }, o.a.createElement("div", {
                        className: "stars"
                    }, n(7, "star")), o.a.createElement("div", {
                        className: "stars2"
                    }, n(6, "star")), o.a.createElement("div", {
                        className: "meteor-stars"
                    }, function() {
                        for (var n = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : Number, t = [], e = 0; e < n; e++) t.push(o.a.createElement("div", {
                            key: e,
                            className: "meteor"
                        }, o.a.createElement("div", {
                            className: "meteor-head"
                        }), o.a.createElement("div", {
                            className: "meteor-trail"
                        })));
                        return t
                    }(5)), o.a.createElement("div", {
                        className: "moon",
                        onClick: function() {}
                    }, o.a.createElement("img", {
                        className: "face",
                        src: "/op/cq9feedback/images/moon_face.png",
                        alt: ""
                    }), o.a.createElement("ul", null, o.a.createElement("li", null), o.a.createElement("li", null), o.a.createElement("li", null))), o.a.createElement("div", {
                        className: "words"
                    }, function() {
                        var n = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : [];
                        return n.map((function(n, t) {
                            return o.a.createElement("span", {
                                key: t
                            }, n)
                        }))
                    }(["\u53cd", "\u994b", "\u7cfb", "\u7d71", "\u7dad", "\u8b77", "\u4e2d", "..."])), o.a.createElement("div", {
                        className: "ocean"
                    }, o.a.createElement("div", {
                        className: "content"
                    }, o.a.createElement("div", {
                        className: "wave"
                    }), o.a.createElement("div", {
                        className: "wave"
                    })))))
                },
                rn = e(118),
                sn = m.default.div((function(n) {
                    var t = n.EnvelopeData;
                    return "\n  ".concat(K.getIn(["EnvelopeTop", "keyframes"]), "\n  position: absolute;\n  width:100%;\n  left:0;\n  bottom:0;\n  z-index:99;\n  &.animeForSendMsg {\n    animation: \n      ").concat(K.getIn(["EnvelopeTop", "name", "main"]), " \n      ").concat(t.get("animeSecond"), "s forwards;\n    .envelopeTop-container {\n      &:after { animation: \n        ").concat(K.getIn(["EnvelopeTop", "name", "shadowB"]), " \n        ").concat(t.get("animeSecond"), "s forwards;\n      }\n    }\n    .send-btn { opacity:0 }\n  }\n  .envelopeTop-container {\n    position: relative;\n    width:100%;\n    height:").concat(t.get("mobileStraight"), "px;\n    border-radius:0 0 20px 20px;\n    overflow:hidden;\n    z-index:1;\n    &:after {\n      content:'';\n      opacity:0;\n      position: absolute;\n      left:0;\n      bottom:0;\n      width:100%;\n      height:40%;\n      background:url('/op/cq9feedback/images/ic_UponLetter_shadow.png')no-repeat bottom center;\n      background-size:cover;\n      z-index:3;\n    }\n    .part-style {\n      position: absolute;\n      left:0;\n      bottom:0;\n      width:100%;\n      height:100%;\n      background:url('/op/cq9feedback/images/ic_UponLetter.png')no-repeat top center;\n      background-size:cover;\n      z-index:1;\n    }\n    .send-btn {\n      position: absolute;\n      width:50%;\n      z-index:99;\n      left: 50%;\n      bottom:15%;\n      transform:translateX(-50%);\n      font-size:18px;\n      color:#ced4e0;\n      background-color:#b6b7c1;\n      padding:12px 0px;\n      border-radius:50px;\n      border:0;\n      outline:none;\n      box-shadow: 0 3px 10px 0 rgb(0, 0, 0, 0);\n      transition:.2s;\n      &.active {\n        background-color: #4053c8;\n        color:#fff;\n        box-shadow: 0 3px 10px 0 rgb(0, 0, 0, 0.7);\n      }\n    }\n  }\n  .send-spinner {\n    position: absolute;\n    z-index:99;\n    left: 50%;\n    bottom:20%;\n    transform:translateX(-50%);\n  }\n  /* mobile \u6a6b\u677f */\n  @media (min-width: 568px){\n    .envelopeTop-container {\n      height:").concat(t.get("mobileHorizontal"), "px;\n      .send-btn {\n        width:35%;\n      }\n    }\n  }\n  /* ipad */\n  @media (min-width: 768px){\n    .envelopeTop-container {\n      .send-btn {\n        width:30%;\n      }\n    }\n  }\n  @media (min-width: 1024px){\n    .envelopeTop-container {\n      height:").concat(t.get("ipadHorizontal"), "px;\n      .send-btn {\n        width:25%;\n      }\n    }\n  }\n  @media (min-width: 1366px){\n    .envelopeTop-container {\n      height:").concat(t.get("ipadProHorizontal"), "px;\n      .send-btn {\n        width:25%;\n      }\n    }\n  }\n")
                })),
                cn = function(n) {
                    var t = n.runState,
                        e = void 0 !== t && t,
                        r = n.EnvelopeData,
                        i = void 0 === r ? Object(v.b)() : r,
                        s = n.formStatus,
                        c = void 0 === s || s,
                        d = n.apiIsSend,
                        p = void 0 !== d && d,
                        m = n.netState,
                        g = void 0 === m || m,
                        h = n.sendApi,
                        b = void 0 === h ? u.a : h,
                        x = n.reInit,
                        w = void 0 === x ? u.a : x,
                        y = n.t,
                        k = Object(a.useState)(!1),
                        E = Object(l.a)(k, 2),
                        z = E[0],
                        S = E[1];
                    return o.a.createElement(sn, {
                        className: f()({
                            animeForSendMsg: e
                        }),
                        EnvelopeData: i
                    }, o.a.createElement("div", {
                        className: "envelopeTop-container"
                    }, o.a.createElement("div", {
                        className: "part-style"
                    }), g ? o.a.createElement("button", {
                        className: f()("send-btn", {
                            active: !c
                        }),
                        disabled: c || p,
                        onClick: function() {
                            b()
                        }
                    }, y("send")) : o.a.createElement(o.a.Fragment, null, z ? o.a.createElement("div", {
                        className: "send-spinner"
                    }, o.a.createElement(rn.ClassicSpinner, {
                        size: 36,
                        color: "#2bc57e"
                    })) : o.a.createElement("button", {
                        disabled: z,
                        className: "send-btn active",
                        onClick: function() {
                            w(),
                                function() {
                                    S(!0);
                                    var n = setTimeout((function() {
                                        return S(!1), clearTimeout(n)
                                    }), [2e3])
                                }()
                        }
                    }, y("sendAgain")))))
                },
                ln = m.default.div((function(n) {
                    var t = n.EnvelopeData;
                    return "\n  position: absolute;\n  display:none;\n  width:100%;\n  left:0;\n  bottom:0;\n  z-index:99;\n  // tick\n  &:before {\n    content:'';\n    opacity:0\n    position: absolute;\n    left:50%;\n    top:50%;\n    width:35%;\n    padding-bottom:35%;\n    transform:translate(-50%,-55%);\n    background:url('/op/cq9feedback/images/ic_check.png')no-repeat center;\n    background-size:115%;\n    z-index:2;\n  }\n  &.animeForSendMsg {\n    display:block;\n    transform:translateY(100%);bottom:50%;\n    animation: \n      ".concat(K.getIn(["EnvelopeTop", "name", "main"]), " \n      ").concat(t.get("animeSecond"), "s forwards;\n    &:before {\n      animation: \n        ").concat(K.getIn(["EnvelopeTop", "name", "Tick"]), " \n        ").concat(t.get("animeSecond"), "s .5s forwards;\n    }\n    .envelopeTop-container {\n      &:before { animation: \n        ").concat(K.getIn(["EnvelopeTop", "name", "shadowA"]), " \n        ").concat(t.get("animeSecond"), "s .5s forwards;\n      }\n    }\n  }\n  .envelopeTop-container {\n    position: relative;\n    width:100%;\n    height:").concat(t.get("mobileStraight"), "px;\n    border-radius:0 0 20px 20px;\n    overflow:hidden;\n    z-index:1;\n    &:before {\n      content:'';\n      opacity:0;\n      position: absolute;\n      left:0;\n      bottom:0;\n      width:100%;\n      height:100%;\n      background:url('/op/cq9feedback/images/ic_UponLetter_shadow.png')no-repeat top center;\n      background-size:cover;\n      z-index:2;\n    }\n  }\n  /* mobile \u6a6b\u677f */\n  @media (min-width: 568px){\n    &:before {\n      width:20%;\n      padding-bottom:20%;\n    }\n    .envelopeTop-container {\n      height:").concat(t.get("mobileHorizontal"), "px;\n    }\n  }\n  /* ipad */\n  @media (min-width: 768px){\n    &:before {\n      width:15%;\n      padding-bottom:15%;\n      transform:translate(-50%,-50%);\n    }\n  }\n  @media (min-width: 1024px){\n    .envelopeTop-container {\n      height:").concat(t.get("ipadHorizontal"), "px;\n    }\n  }\n  @media (min-width: 1366px){\n    .envelopeTop-container {\n      height:").concat(t.get("ipadProHorizontal"), "px;\n    }\n  }\n")
                })),
                dn = function(n) {
                    var t = n.runState,
                        e = void 0 !== t && t,
                        a = n.EnvelopeData,
                        r = void 0 === a ? Object(v.b)() : a;
                    return o.a.createElement(ln, {
                        className: f()({
                            animeForSendMsg: e
                        }),
                        EnvelopeData: r
                    }, o.a.createElement("div", {
                        className: "envelopeTop-container"
                    }))
                },
                pn = m.default.div((function(n) {
                    var t = n.EnvelopeData;
                    return "\n  ".concat(K.getIn(["EnvelopeBottom", "keyframes"]), "\n  position: absolute;\n  width:100%;\n  height:").concat(1.65 * t.get("mobileStraight"), "px;\n  left:0;\n  bottom:0;\n  z-index:2;\n  &.animeForSendMsg {\n    animation: \n    ").concat(K.getIn(["EnvelopeBottom", "name", "main"]), " \n    ").concat(t.get("animeSecond"), "s forwards;\n  }\n  .envelopeBottom-container {\n    position: relative;\n    width:100%;\n    height:100%;\n    background:#ffba5f;\n    border-radius:0 0 20px 20px;\n  }\n  @media (min-width: 568px){\n    height:").concat(1.65 * t.get("mobileHorizontal"), "px;\n  }\n  @media (min-width: 1024px){\n    height:").concat(1.65 * t.get("ipadHorizontal"), "px;\n  }\n  @media (min-width: 1366px){\n    height:").concat(1.65 * t.get("ipadProHorizontal"), "px;\n  }\n")
                })),
                mn = function(n) {
                    var t = n.runState,
                        e = void 0 !== t && t,
                        a = n.EnvelopeData,
                        r = void 0 === a ? Object(v.b)() : a;
                    return o.a.createElement(pn, {
                        className: f()({
                            animeForSendMsg: e
                        }),
                        EnvelopeData: r
                    }, o.a.createElement("div", {
                        className: "envelopeBottom-container"
                    }))
                },
                gn = m.default.div((function(n) {
                    var t = n.EnvelopeData,
                        e = n.lang;
                    return "\n  ".concat(K.getIn(["EnvelopeCover", "keyframes"]), "\n  position: absolute;\n  width:100%;\n  height:").concat(t.get("mobileStraightCover"), "px;\n  left:0;\n  bottom:0;\n  z-index:1;\n  transform-origin:top center;\n  transform:translateY(").concat(t.get("mobileStraightCover"), "px)rotateX(180deg);\n  border-radius: 20px 20px 0 0;\n  overflow: hidden;\n  &.animeForSendMsg {\n    animation: \n      ").concat(K.getIn(["EnvelopeCover", "name", "main"]), "\n      ").concat(t.get("animeSecond"), "s forwards;\n    .envelopeCover-container {\n      .text {\n        animation: \n          ").concat(K.getIn(["EnvelopeCover", "name", "text"]), "\n          ").concat(t.get("animeSecond"), "s .5s forwards;\n      }\n    }\n  }\n  .envelopeCover-container {\n    position: relative;\n    width:100%;\n    height:100%;\n    overflow:hidden;\n    background:url('/op/cq9feedback/images/ic_LetterCover.png')no-repeat bottom center;\n    background-size:cover;\n    .text {\n      opacity:0;\n      position: absolute;\n      top: 50%;\n      left: 50%;\n      transform: translate(-50%, -50%);\n      text-align: center;\n      .mainTitle {\n        position: relative;\n        font-family: ").concat("cn" === e ? "MFMoDeng" : "Arial", ";\n        font-weight: 100;\n        font-size:26px;\n        letter-spacing: ").concat("cn" === e ? "2px" : "0", ";\n        color:#ffffff;\n        margin-bottom: 8px;\n        white-space: nowrap;\n      }\n      .subtitle {\n        font-size:16px;\n        color:#d5daf5;\n      }\n    }\n  }\n  @media (min-width: 568px){\n    ").concat(K.getIn(["EnvelopeCover", "rwd", "mobileHorizontal"]), "\n    height:").concat(t.get("mobileHorizontalCover"), "px;\n    transform:translateY(").concat(t.get("mobileHorizontalCover"), "px)rotateX(180deg);\n  }\n  @media (min-width: 1024px){\n    ").concat(K.getIn(["EnvelopeCover", "rwd", "ipadHorizontal"]), "\n    height:").concat(t.get("ipadHorizontalCover"), "px;\n    transform:translateY(").concat(t.get("ipadHorizontalCover"), "px)rotateX(180deg);\n    .envelopeCover-container {\n      .text {\n        .mainTitle {\n          font-size: 30px;\n          &:before, &:after  { font-size: 20px; }\n        }\n      }\n    }\n  }\n  @media (min-width: 1366px){\n    ").concat(K.getIn(["EnvelopeCover", "rwd", "ipadProHorizontal"]), "\n    height:").concat(t.get("ipadProHorizontalCover"), "px;\n    transform:translateY(").concat(t.get("ipadProHorizontalCover"), "px)rotateX(180deg);\n  }\n")
                })),
                fn = function(n) {
                    var t = n.runState,
                        e = void 0 !== t && t,
                        a = n.EnvelopeData,
                        r = void 0 === a ? Object(v.b)() : a,
                        i = n.t,
                        s = n.lang;
                    return o.a.createElement(gn, {
                        className: f()({
                            animeForSendMsg: e
                        }),
                        EnvelopeData: r,
                        lang: s
                    }, o.a.createElement("div", {
                        className: "envelopeCover-container"
                    }, o.a.createElement("div", {
                        className: "text"
                    }, o.a.createElement("div", {
                        className: "mainTitle"
                    }, i("thanks")))))
                },
                hn = m.default.div(A || (A = Object(d.a)(["\n  @keyframes popupIn {\n    0% {\n      opacity:0;\n      top:45px;\n      right:2%;\n    };\n    100% {\n      opacity:1;\n      top:15px;\n      right:12%;\n    };\n  };\n  @keyframes loop-DialogRight {\n    0% { transform:translateY(0%) };\n    50% { transform:translateY(12.5%) };\n    100% { transform:translateY(0%) };\n  };\n  position: absolute;\n  top:15px;\n  right:12%;\n  width:32%;\n  border-radius:10px;\n  background:rgba(255,255,255,0.1);\n  box-sizing:border-box;\n  animation:popupIn 1s;\n  &.loop {\n    animation:loop-DialogRight 3.5s infinite;\n  }\n  .dialog-content {\n    position: relative;\n    width:100%;\n    padding-bottom:35%;\n    .run-bar {\n      @keyframes load {\n        0% { width:0; };\n        100% { width:100%; };\n      };\n      position: absolute;\n      top:50%;\n      left:50%;\n      width:72.5%;\n      height:2px;\n      transform:translate(-50%,-50%);\n      &:before {\n        content:'';\n        position: absolute;\n        width:0;\n        height:100%;\n        background:rgba(255,255,255,0.2);\n        border-radius:10px;\n      }\n      &:nth-child(1) {\n        top:30%;\n        &:before {\n          animation:load 1s 0.8s forwards;\n        }\n      }\n      &:nth-child(2) {\n        &:before {\n          animation:load 1s 1.8s forwards;\n        }\n      }\n      &:nth-child(3) {\n        top:70%;\n        &:before {\n          animation:load 1s 2.6s forwards;\n        }\n      }\n    } \n    .bubble {\n      position: absolute;\n      bottom:0;\n      left:5%;\n      width:15%;\n      padding-bottom:15%;\n      transform:translateY(70%);\n      background:url('/op/cq9feedback/images/bubble.png')no-repeat;\n      background-size:100%;\n    } \n  }\n  @media (min-width: 768px) {\n    width:25%;\n  };\n  @media (min-width: 1024px) {\n    width:21%;\n  }\n"]))),
                un = function(n) {
                    var t = n.status;
                    return o.a.createElement(hn, {
                        className: f()({
                            loop: t
                        })
                    }, o.a.createElement("div", {
                        className: "dialog-content"
                    }, o.a.createElement("div", {
                        className: "run-bar"
                    }), o.a.createElement("div", {
                        className: "run-bar"
                    }), o.a.createElement("div", {
                        className: "run-bar"
                    }), o.a.createElement("div", {
                        className: "bubble"
                    })))
                },
                bn = m.default.div(F || (F = Object(d.a)(["\n  @keyframes loop-DialogLeft {\n    0% { transform:translateY(-47.5%) };\n    50% { transform:translateY(-60%) };\n    100% { transform:translateY(-47.5%) };\n  };\n  position: absolute;\n  opacity:0;\n  top:0;\n  left:0;\n  border-radius:0 10px 10px 0;\n  width:25%;\n  padding-bottom:16%;\n  transition:.3s;\n  background:rgba(255,255,255,0.1);\n  transform:translateY(-47.5%);\n  &:before {\n    content:'';\n    position: absolute;\n    bottom:0;\n    right:10%;\n    width:20%;\n    padding-bottom:20%;\n    transform:translateY(60%)rotateY(180deg);\n    background:url('/op/cq9feedback/images/bubble.png')no-repeat;\n    background-size:100%;\n  };\n  &.run {\n    animation:loop-DialogLeft 3.5s infinite;\n  }\n  &.open { opacity:1; }\n  @media (min-width: 568px) {\n    width:20%;\n  };\n  @media (min-width: 768px) {\n    width:15%;\n    padding-bottom:14%;\n  };\n"]))),
                vn = function(n) {
                    var t = n.status,
                        e = n.delay;
                    return o.a.createElement(bn, {
                        className: f()({
                            open: e
                        }, {
                            run: t
                        })
                    })
                },
                xn = e(119),
                wn = e.n(xn),
                yn = "/api",
                kn = "687cd06de7ac3343c1b45a6b92f4983791ac5c9582bf0a5ae1fac108e96a05d0";
            wn.a.defaults.withCredentials = !0;
            var En, zn = function(n) {
                return wn.a.get("".concat(yn, "/frontend/feedback/init"), {
                    headers: {
                        token: kn
                    },
                    params: n
                })
            };

            function Sn() {
                Sn = function() {
                    return n
                };
                var n = {},
                    t = Object.prototype,
                    e = t.hasOwnProperty,
                    a = Object.defineProperty || function(n, t, e) {
                        n[t] = e.value
                    },
                    o = "function" == typeof Symbol ? Symbol : {},
                    r = o.iterator || "@@iterator",
                    i = o.asyncIterator || "@@asyncIterator",
                    s = o.toStringTag || "@@toStringTag";

                function c(n, t, e) {
                    return Object.defineProperty(n, t, {
                        value: e,
                        enumerable: !0,
                        configurable: !0,
                        writable: !0
                    }), n[t]
                }
                try {
                    c({}, "")
                } catch (N) {
                    c = function(n, t, e) {
                        return n[t] = e
                    }
                }

                function l(n, t, e, o) {
                    var r = t && t.prototype instanceof m ? t : m,
                        i = Object.create(r.prototype),
                        s = new S(o || []);
                    return a(i, "_invoke", {
                        value: y(n, e, s)
                    }), i
                }

                function d(n, t, e) {
                    try {
                        return {
                            type: "normal",
                            arg: n.call(t, e)
                        }
                    } catch (N) {
                        return {
                            type: "throw",
                            arg: N
                        }
                    }
                }
                n.wrap = l;
                var p = {};

                function m() {}

                function g() {}

                function f() {}
                var h = {};
                c(h, r, (function() {
                    return this
                }));
                var u = Object.getPrototypeOf,
                    b = u && u(u(O([])));
                b && b !== t && e.call(b, r) && (h = b);
                var v = f.prototype = m.prototype = Object.create(h);

                function x(n) {
                    ["next", "throw", "return"].forEach((function(t) {
                        c(n, t, (function(n) {
                            return this._invoke(t, n)
                        }))
                    }))
                }

                function w(n, t) {
                    var o;
                    a(this, "_invoke", {
                        value: function(a, r) {
                            function i() {
                                return new t((function(o, i) {
                                    ! function a(o, r, i, s) {
                                        var c = d(n[o], n, r);
                                        if ("throw" !== c.type) {
                                            var l = c.arg,
                                                p = l.value;
                                            return p && "object" == typeof p && e.call(p, "__await") ? t.resolve(p.__await).then((function(n) {
                                                a("next", n, i, s)
                                            }), (function(n) {
                                                a("throw", n, i, s)
                                            })) : t.resolve(p).then((function(n) {
                                                l.value = n, i(l)
                                            }), (function(n) {
                                                return a("throw", n, i, s)
                                            }))
                                        }
                                        s(c.arg)
                                    }(a, r, o, i)
                                }))
                            }
                            return o = o ? o.then(i, i) : i()
                        }
                    })
                }

                function y(n, t, e) {
                    var a = "suspendedStart";
                    return function(o, r) {
                        if ("executing" === a) throw new Error("Generator is already running");
                        if ("completed" === a) {
                            if ("throw" === o) throw r;
                            return j()
                        }
                        for (e.method = o, e.arg = r;;) {
                            var i = e.delegate;
                            if (i) {
                                var s = k(i, e);
                                if (s) {
                                    if (s === p) continue;
                                    return s
                                }
                            }
                            if ("next" === e.method) e.sent = e._sent = e.arg;
                            else if ("throw" === e.method) {
                                if ("suspendedStart" === a) throw a = "completed", e.arg;
                                e.dispatchException(e.arg)
                            } else "return" === e.method && e.abrupt("return", e.arg);
                            a = "executing";
                            var c = d(n, t, e);
                            if ("normal" === c.type) {
                                if (a = e.done ? "completed" : "suspendedYield", c.arg === p) continue;
                                return {
                                    value: c.arg,
                                    done: e.done
                                }
                            }
                            "throw" === c.type && (a = "completed", e.method = "throw", e.arg = c.arg)
                        }
                    }
                }

                function k(n, t) {
                    var e = n.iterator[t.method];
                    if (void 0 === e) {
                        if (t.delegate = null, "throw" === t.method) {
                            if (n.iterator.return && (t.method = "return", t.arg = void 0, k(n, t), "throw" === t.method)) return p;
                            t.method = "throw", t.arg = new TypeError("The iterator does not provide a 'throw' method")
                        }
                        return p
                    }
                    var a = d(e, n.iterator, t.arg);
                    if ("throw" === a.type) return t.method = "throw", t.arg = a.arg, t.delegate = null, p;
                    var o = a.arg;
                    return o ? o.done ? (t[n.resultName] = o.value, t.next = n.nextLoc, "return" !== t.method && (t.method = "next", t.arg = void 0), t.delegate = null, p) : o : (t.method = "throw", t.arg = new TypeError("iterator result is not an object"), t.delegate = null, p)
                }

                function E(n) {
                    var t = {
                        tryLoc: n[0]
                    };
                    1 in n && (t.catchLoc = n[1]), 2 in n && (t.finallyLoc = n[2], t.afterLoc = n[3]), this.tryEntries.push(t)
                }

                function z(n) {
                    var t = n.completion || {};
                    t.type = "normal", delete t.arg, n.completion = t
                }

                function S(n) {
                    this.tryEntries = [{
                        tryLoc: "root"
                    }], n.forEach(E, this), this.reset(!0)
                }

                function O(n) {
                    if (n) {
                        var t = n[r];
                        if (t) return t.call(n);
                        if ("function" == typeof n.next) return n;
                        if (!isNaN(n.length)) {
                            var a = -1,
                                o = function t() {
                                    for (; ++a < n.length;)
                                        if (e.call(n, a)) return t.value = n[a], t.done = !1, t;
                                    return t.value = void 0, t.done = !0, t
                                };
                            return o.next = o
                        }
                    }
                    return {
                        next: j
                    }
                }

                function j() {
                    return {
                        value: void 0,
                        done: !0
                    }
                }
                return g.prototype = f, a(v, "constructor", {
                    value: f,
                    configurable: !0
                }), a(f, "constructor", {
                    value: g,
                    configurable: !0
                }), g.displayName = c(f, s, "GeneratorFunction"), n.isGeneratorFunction = function(n) {
                    var t = "function" == typeof n && n.constructor;
                    return !!t && (t === g || "GeneratorFunction" === (t.displayName || t.name))
                }, n.mark = function(n) {
                    return Object.setPrototypeOf ? Object.setPrototypeOf(n, f) : (n.__proto__ = f, c(n, s, "GeneratorFunction")), n.prototype = Object.create(v), n
                }, n.awrap = function(n) {
                    return {
                        __await: n
                    }
                }, x(w.prototype), c(w.prototype, i, (function() {
                    return this
                })), n.AsyncIterator = w, n.async = function(t, e, a, o, r) {
                    void 0 === r && (r = Promise);
                    var i = new w(l(t, e, a, o), r);
                    return n.isGeneratorFunction(e) ? i : i.next().then((function(n) {
                        return n.done ? n.value : i.next()
                    }))
                }, x(v), c(v, s, "Generator"), c(v, r, (function() {
                    return this
                })), c(v, "toString", (function() {
                    return "[object Generator]"
                })), n.keys = function(n) {
                    var t = Object(n),
                        e = [];
                    for (var a in t) e.push(a);
                    return e.reverse(),
                        function n() {
                            for (; e.length;) {
                                var a = e.pop();
                                if (a in t) return n.value = a, n.done = !1, n
                            }
                            return n.done = !0, n
                        }
                }, n.values = O, S.prototype = {
                    constructor: S,
                    reset: function(n) {
                        if (this.prev = 0, this.next = 0, this.sent = this._sent = void 0, this.done = !1, this.delegate = null, this.method = "next", this.arg = void 0, this.tryEntries.forEach(z), !n)
                            for (var t in this) "t" === t.charAt(0) && e.call(this, t) && !isNaN(+t.slice(1)) && (this[t] = void 0)
                    },
                    stop: function() {
                        this.done = !0;
                        var n = this.tryEntries[0].completion;
                        if ("throw" === n.type) throw n.arg;
                        return this.rval
                    },
                    dispatchException: function(n) {
                        if (this.done) throw n;
                        var t = this;

                        function a(e, a) {
                            return i.type = "throw", i.arg = n, t.next = e, a && (t.method = "next", t.arg = void 0), !!a
                        }
                        for (var o = this.tryEntries.length - 1; o >= 0; --o) {
                            var r = this.tryEntries[o],
                                i = r.completion;
                            if ("root" === r.tryLoc) return a("end");
                            if (r.tryLoc <= this.prev) {
                                var s = e.call(r, "catchLoc"),
                                    c = e.call(r, "finallyLoc");
                                if (s && c) {
                                    if (this.prev < r.catchLoc) return a(r.catchLoc, !0);
                                    if (this.prev < r.finallyLoc) return a(r.finallyLoc)
                                } else if (s) {
                                    if (this.prev < r.catchLoc) return a(r.catchLoc, !0)
                                } else {
                                    if (!c) throw new Error("try statement without catch or finally");
                                    if (this.prev < r.finallyLoc) return a(r.finallyLoc)
                                }
                            }
                        }
                    },
                    abrupt: function(n, t) {
                        for (var a = this.tryEntries.length - 1; a >= 0; --a) {
                            var o = this.tryEntries[a];
                            if (o.tryLoc <= this.prev && e.call(o, "finallyLoc") && this.prev < o.finallyLoc) {
                                var r = o;
                                break
                            }
                        }
                        r && ("break" === n || "continue" === n) && r.tryLoc <= t && t <= r.finallyLoc && (r = null);
                        var i = r ? r.completion : {};
                        return i.type = n, i.arg = t, r ? (this.method = "next", this.next = r.finallyLoc, p) : this.complete(i)
                    },
                    complete: function(n, t) {
                        if ("throw" === n.type) throw n.arg;
                        return "break" === n.type || "continue" === n.type ? this.next = n.arg : "return" === n.type ? (this.rval = this.arg = n.arg, this.method = "return", this.next = "end") : "normal" === n.type && t && (this.next = t), p
                    },
                    finish: function(n) {
                        for (var t = this.tryEntries.length - 1; t >= 0; --t) {
                            var e = this.tryEntries[t];
                            if (e.finallyLoc === n) return this.complete(e.completion, e.afterLoc), z(e), p
                        }
                    },
                    catch: function(n) {
                        for (var t = this.tryEntries.length - 1; t >= 0; --t) {
                            var e = this.tryEntries[t];
                            if (e.tryLoc === n) {
                                var a = e.completion;
                                if ("throw" === a.type) {
                                    var o = a.arg;
                                    z(e)
                                }
                                return o
                            }
                        }
                        throw new Error("illegal catch attempt")
                    },
                    delegateYield: function(n, t, e) {
                        return this.delegate = {
                            iterator: O(n),
                            resultName: t,
                            nextLoc: e
                        }, "next" === this.method && (this.arg = void 0), p
                    }
                }, n
            }
            var On = m.default.div((function(n) {
                    var t = n.mediaType;
                    return "\n  ".concat(t ? "max-width: 600px;" : "", "\n  user-select: none;\n  position: relative;\n  transform: scale(0.85);\n  height:88vh;\n  margin:6vh auto;\n  /* \u6a6b\u677f rwd mobile iPhone 5SE*/\n  @media (min-width: 568px) {\n    transform: scale(0.8);\n    height:100vh;\n    margin:0 auto;\n    width:80%;\n  }\n  @media (min-width: 768px) and (min-height: 1024px) {\n    height:62.5vh;\n    margin:18.75vh auto;\n  }\n  @media (min-width: 1024px) {\n    height:70vh;\n    margin:15vh auto;\n  }\n  @media (min-width: 1024px) and (min-height: 1366px) {\n    height:52.5vh;\n    margin:23.75vh auto;\n  }\n  @media (min-width: 1366px) {\n    height:70vh;\n    margin:15vh auto;\n  }\n")
                })),
                jn = m.default.div((function(n) {
                    var t = n.textareaIsOpen,
                        e = n.EnvelopeData,
                        a = n.lang;
                    return "\n  ".concat(K.getIn(["StyledApp", "keyframes"]), "\n  @font-face {\n    font-family: \"MFMoDeng\";\n    src: url('/op/cq9feedback/fonts/MFMoDeng.ttf')format('truetype');\n  };\n  @font-face {\n    font-family: \"TengXiangJiaLiCuYuanJian\";\n    src: url('/op/cq9feedback/fonts/TengXiangJiaLiCuYuanJian.ttf')format('truetype');\n  };\n  @font-face {\n    font-family: \"Hiragino-Sans-GB\";\n    src: url('/op/cq9feedback/fonts/Hiragino-Sans-GB.ttc'),\n         url('/op/cq9feedback/fonts/Hiragino-Sans-GB.otf')format(\"opentype\");\n  };\n  position: relative;\n  transform: scale(1);\n  height:100%;\n  z-index:2;\n  min-width:320px;\n  background:transparent;\n  font-family: ").concat("cn" === a ? "Hiragino-Sans-GB" : "Arial", ";\n  .bg-falsh {\n    position: absolute;\n    bottom:5px;\n    left:50%;\n    transform:translateX(-50%);\n    width:95%;\n    height:100%;\n    z-index:3;\n    animation: \n      ").concat(K.getIn(["StyledApp", "name", "keepFlash"]), " \n      1.5s infinite\n    &.animeForSendMsg {\n      display:none;\n    }\n    &::before {\n      content:'';\n      position: absolute;\n      top:0;\n      left:0;\n      width:100%;\n      padding-bottom: 32.5%;\n      background:url('/op/cq9feedback/images/ic_letter_bg_flash.png')no-repeat top;\n      background-size:100%;\n      transform:translateY(-82.5%);\n    }\n  }\n  .container {\n    position: absolute;\n    bottom:5px;\n    left:50%;\n    transform:translateX(-50%);\n    width:95%;\n    height:100%;\n    background-color: rgba(255,255,255,0.3);\n    box-sizing:border-box;\n    border-radius: 30px;\n    border:1px solid rgba(255,255,255,0.5);\n    padding:5%;\n    z-index:4;\n    overflow:hidden;\n    box-shadow:0 0 10px rgba(0, 0, 0, 0.4);\n    &.animeForSendMsg {\n      animation: \n      ").concat(K.getIn(["StyledApp", "name", "container"]), " \n      ").concat(e.get("animeSecond"), "s forwards;\n    }\n    .info-content {   \n      width:100%;\n      height:100%;\n      border-radius: 20px;\n      overflow:auto;\n      background-color: #f7f1f0;\n      &::-webkit-scrollbar {\n        display:none;\n      }\n      .part-of-regular {\n        position: relative;\n        box-sizing:border-box;\n        overflow:hidden;\n        padding:12% 8% 0 8%;\n        padding-bottom:").concat(t ? 0 : .8 * e.get("mobileStraight"), "px;\n      }\n      .background {\n        position: absolute;\n        overflow: hidden;\n        border-radius: 20px 20px 0 0;\n        top:0;\n        left:0;\n        width:100%;\n        height:150px;\n        background-image:linear-gradient(to bottom, #618957 50%, #97b090);\n        z-index: 1;\n        .bg-content {\n          position: relative;\n          width:100%;\n          height:100%;\n        }\n      }\n      .information {\n        position: relative;\n        top:0;\n        width:100%;\n        z-index: 2;\n      }\n      .text-area {\n        box-sizing:border-box;\n        padding-left:15px;\n        margin-bottom:15px;\n        .title {\n          font-size:24px;\n          font-family: ").concat("cn" === a ? "TengXiangJiaLiCuYuanJian" : "Arial", ";\n          color:#fff;\n          letter-spacing:").concat("cn" === a ? "1.2px" : "0", ";\n          margin-bottom:40px;          \n        }\n        .subtitle {\n          font-size:16px;\n          color:#fff;\n          letter-spacing:1.2px;\n        }\n      }\n      .loading-area {\n        position: relative;\n        width:100%;\n        height:100%;\n        .spinner {\n          position: absolute;\n          top:50%;\n          left:50%;\n          transform: translate(-50%,-50%);\n        }\n      }\n      .error-area {\n        margin-top:12vh;\n        text-align:center;\n        .error-title {\n          font-size:32px;\n          font-family: \"MFMoDeng\";\n          color:#5a564c;\n          letter-spacing: 1.2px;\n        }\n        .error-img {\n          margin:0 auto;\n          width:75%;\n          padding-bottom:65%;\n          background:url('/op/cq9feedback/images/ic_error.png')no-repeat center;\n          background-size:100%;\n        }\n        .error-subtitle {\n          font-size:16px;\n          color: #5a564b;\n        }\n      }\n      .start-list {\n        position: relative;\n        width:100%;\n        padding:15px;\n        box-sizing: border-box;\n        margin:0 auto 15px auto;\n        background:#fff;\n        border-radius:10px;\n        box-shadow: 0px 5px 15px 0 rgba(0, 0, 0, 0.1);\n      }\n    }\n  }\n  /* \u6a6b\u677f rwd mobile iPhone 5SE*/\n  @media (min-width: 568px) {\n    ").concat(K.getIn(["StyledApp", "rwd", "mobileHorizontal"]), "\n    .container {\n      padding:3%;\n      .info-content {\n        .part-of-regular {\n          padding-bottom:").concat(t ? 0 : .8 * e.get("mobileHorizontal"), "px;\n        }\n        .text-area {\n          .title {\n            font-size:30px;\n          }\n          .subtitle {\n            font-size:16px;\n          }\n        }\n        .start-list {\n          padding:20px;\n        }\n        .error-area {\n          // margin-top:3vh;\n          .error-img {\n            width: 60%;\n            padding-bottom: 50%;\n          }\n        }\n      }\n    }\n  }\n  @media (min-width: 569px) {\n    .container {\n      .info-content {\n        .background {\n          height:200px;\n        }\n      }\n    }\n  }\n  @media (min-width: 768px) and (min-height: 1024px) {\n    .container {\n      bottom:10px;\n      padding:2%;\n      .info-content {\n        .part-of-regular {\n          padding:10% 5% 0 5%;\n          padding-bottom:").concat(t ? 0 : .8 * e.get("mobileHorizontal"), "px;\n        }\n        .error-area {\n          .error-subtitle {\n            font-size:18px;\n          }\n        }\n      }\n    }\n  }\n  @media (min-width: 1024px) {\n    ").concat(K.getIn(["StyledApp", "rwd", "ipadHorizontal"]), "\n    .container {\n      .info-content {\n        .part-of-regular {\n          padding:10% 8% 0 8%;\n          padding-bottom:").concat(t ? 0 : .85 * e.get("ipadHorizontal"), "px;\n        }\n        .background {\n          height:225px;\n        }\n      }\n    }\n  }\n  @media (min-width: 1366px) {\n    ").concat(K.getIn(["StyledApp", "rwd", "ipadProHorizontal"]), "\n    .container {\n      .info-content {\n        .part-of-regular {\n          padding-bottom:").concat(t ? 0 : .85 * e.get("ipadProHorizontal"), "px;\n        }\n        .background {\n          height:265px;\n        }\n      }\n    }\n  }\n")
                })),
                Nn = m.default.div(En || (En = Object(d.a)(["\n  position: absolute;\n  top: 0;\n  right: 0;\n  width: 35%;\n  z-index: 99;\n  transform: translateY(-62.5%);\n  @media (min-width: 768px) {\n    width: 22%;\n  }\n"]))),
                Tn = function() {
                    var n = Object(a.useRef)(null),
                        t = Object(a.useRef)(null),
                        e = Object(a.useState)(Number()),
                        r = Object(l.a)(e, 2),
                        i = r[0],
                        d = r[1],
                        m = Object(a.useState)(Object(v.a)()),
                        g = Object(l.a)(m, 2),
                        h = g[0],
                        w = g[1],
                        y = Object(a.useState)(Object(v.a)()),
                        k = Object(l.a)(y, 2),
                        z = k[0],
                        S = k[1],
                        O = Object(a.useState)(Object(v.a)()),
                        j = Object(l.a)(O, 2),
                        N = j[0],
                        T = j[1],
                        H = Object(a.useState)(Object(v.a)()),
                        C = Object(l.a)(H, 2),
                        L = C[0],
                        A = C[1],
                        F = Object(a.useState)(!1),
                        M = Object(l.a)(F, 2),
                        _ = M[0],
                        I = M[1],
                        X = Object(a.useState)(!0),
                        D = Object(l.a)(X, 2),
                        B = D[0],
                        G = D[1],
                        J = Object(a.useState)(!1),
                        W = Object(l.a)(J, 2),
                        K = W[0],
                        q = W[1],
                        V = Object(a.useState)(!1),
                        Q = Object(l.a)(V, 2),
                        Z = Q[0],
                        nn = Q[1],
                        tn = Object(a.useState)(!1),
                        en = Object(l.a)(tn, 2),
                        an = en[0],
                        sn = en[1],
                        ln = Object(a.useState)({
                            run: !1,
                            delay: !1
                        }),
                        pn = Object(l.a)(ln, 2),
                        gn = pn[0],
                        hn = pn[1],
                        bn = Object(a.useState)(!1),
                        xn = Object(l.a)(bn, 2),
                        En = xn[0],
                        Tn = xn[1],
                        Hn = function() {
                            var n = function(n) {
                                    var t = window.location.search.split("?"),
                                        e = t.slice(1, t.length).join("");
                                    return new URLSearchParams(e).get(n)
                                }("language"),
                                t = Object.keys(E);
                            return n ? "zh-cn" === n ? "cn" : t.includes(n) ? n : "en" : "en"
                        }(),
                        Cn = Object(x.a)().t,
                        Ln = function() {
                            var n = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : Object(v.a)(),
                                t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : Object(v.a)(),
                                e = n.size !== t.size;
                            return !~n.indexOf(void 0) && !e
                        }(N, h),
                        An = !an || L.size > 0,
                        Fn = !(Ln && (!an || i > 0) && An && N.size > 0),
                        Yn = new URL(window.location).searchParams.get("token") ? new URL(window.location).searchParams.get("token") : "",
                        Mn = function(n) {
                            1311006 === n.data.error_code ? q(!0) : q(!1);
                            var t = Object.keys(n.data.result).length,
                                e = n.data.result.white,
                                a = n.data.result.rate ? Object(v.c)(n.data.result.rate) : Object(v.a)(),
                                o = n.data.result.tag ? n.data.result.tag : [];
                            G(t > 0);
                            sn(!e), T(Object(v.a)()), A(Object(v.a)()), d(Number()), w(a), S(function(n) {
                                var t = [];
                                return n.map((function(n) {
                                    return t.push(Object(c.a)(Object(c.a)({}, n), {}, {
                                        status: !1
                                    }))
                                })), Object(v.c)(t)
                            }(o)), Tn(!0)
                        },
                        Pn = function() {
                            var n = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : u.a,
                                t = arguments.length > 1 ? arguments[1] : void 0,
                                e = {
                                    token: Yn,
                                    language: Hn
                                };
                            return zn(e).then((function(e) {
                                return Mn(e), n(t)
                            }), (function(n) {
                                return G(!1)
                            }))
                        };
                    return Object(a.useEffect)((function() {
                        zn({
                            token: Yn,
                            language: Hn
                        }).then((function(n) {
                            return Mn(n)
                        }), (function(n) {
                            return G(!1)
                        }))
                    }), [Hn, Yn]), Object(a.useEffect)((function() {
                        !_ && nn(!1)
                    }), [_]), Object(a.useEffect)((function() {
                        var n = function(n) {
                            return new Promise((function(t) {
                                setTimeout(t, n)
                            }))
                        };
                        (function() {
                            var t = Object(s.a)(Sn().mark((function t() {
                                return Sn().wrap((function(t) {
                                    for (;;) switch (t.prev = t.next) {
                                        case 0:
                                            return t.next = 2, n(1e3);
                                        case 2:
                                            return hn({
                                                run: !0,
                                                delay: !1
                                            }), t.next = 5, n(2600);
                                        case 5:
                                            hn({
                                                run: !0,
                                                delay: !0
                                            });
                                        case 6:
                                        case "end":
                                            return t.stop()
                                    }
                                }), t)
                            })));
                            return function() {
                                return t.apply(this, arguments)
                            }
                        })()()
                    }), [hn]), Object(a.useEffect)((function() {
                        Y.changeLanguage(Hn)
                    }), [Hn]), o.a.createElement(On, {
                        "data-version": p.version,
                        mediaType: b.a.desktop()
                    }, o.a.createElement(fn, {
                        runState: _,
                        EnvelopeData: U,
                        t: Cn,
                        lang: Hn
                    }), o.a.createElement(dn, {
                        runState: _,
                        EnvelopeData: U
                    }), o.a.createElement(jn, {
                        textareaIsOpen: an,
                        EnvelopeData: U,
                        lang: Hn
                    }, o.a.createElement("div", {
                        className: f()("container", {
                            animeForSendMsg: _
                        })
                    }, B ? o.a.createElement("div", {
                        className: "info-content",
                        ref: n
                    }, En ? o.a.createElement(o.a.Fragment, null, o.a.createElement("div", {
                        className: "part-of-regular"
                    }, o.a.createElement("div", {
                        className: "background"
                    }, o.a.createElement("div", {
                        className: "bg-content"
                    }, o.a.createElement(un, {
                        status: gn.run
                    }), o.a.createElement(vn, {
                        status: gn.run,
                        delay: gn.delay
                    }))), o.a.createElement("div", {
                        className: "information"
                    }, o.a.createElement("div", {
                        className: "text-area"
                    }, o.a.createElement("div", {
                        className: "title"
                    }, Cn("feedback"))), h.map((function(n, t) {
                        return o.a.createElement("div", {
                            key: t,
                            className: "start-list"
                        }, 0 === t && o.a.createElement(Nn, null, o.a.createElement(P, null)), o.a.createElement(R, {
                            listKey: t,
                            id: n.get("id"),
                            title: n.getIn(["title", Hn]) || n.get("name"),
                            rateScoreKey: N.get(t),
                            setRateScore: T,
                            lang: Hn
                        }))
                    })))), an && o.a.createElement($, {
                        tagList: z,
                        textareaLen: i,
                        textareaEl: t,
                        setTagList: S,
                        setTextareaLen: d,
                        setTagCheckd: A,
                        t: Cn
                    })) : o.a.createElement("div", {
                        className: "loading-area"
                    }, o.a.createElement("div", {
                        className: "spinner"
                    }, o.a.createElement(rn.ClassicSpinner, {
                        size: 45,
                        color: "#2bc57e"
                    })))) : o.a.createElement("div", {
                        className: "info-content"
                    }, K ? o.a.createElement(on, null) : o.a.createElement("div", {
                        className: "error-area"
                    }, o.a.createElement("div", {
                        className: "error-title"
                    }, Cn("wrong")), o.a.createElement("div", {
                        className: "error-img"
                    })))), o.a.createElement(mn, {
                        runState: _,
                        EnvelopeData: U
                    }), o.a.createElement(cn, {
                        runState: _,
                        EnvelopeData: U,
                        formStatus: Fn,
                        apiIsSend: Z,
                        netState: B,
                        sendApi: function() {
                            nn(!0),
                                function(n) {
                                    return wn.a.post("".concat(yn, "/frontend/feedback/create"), n, {
                                        headers: {
                                            token: kn
                                        }
                                    })
                                }({
                                    content: (null === t.current ? "" : t.current.value).trim(),
                                    rate: N.toJS(),
                                    tag: L.toJS(),
                                    token: Yn
                                }).then((function(e) {
                                    1311006 === e.data.error_code ? q(!0) : q(!1);
                                    var a = e.data.error_msg;
                                    "SUCCESS" === a ? function() {
                                        var e = 2 * U.get("animeSecond") * 1e3;
                                        I(!0), null !== n.current && (n.current.scrollTop = 0), B && null !== t.current && (t.current.value = "");
                                        var a = setTimeout((function() {
                                            Tn(!1), Pn(I(), !1)
                                        }), e)
                                    }() : (console.log(a), G(!1))
                                })).catch((function(n) {
                                    G(!1)
                                }))
                        },
                        reInit: Pn,
                        t: Cn
                    })))
                };
            Boolean("localhost" === window.location.hostname || "[::1]" === window.location.hostname || window.location.hostname.match(/^127(?:\.(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)){3}$/));
            i.a.render(o.a.createElement(Tn, null), document.getElementById("root")), "serviceWorker" in navigator && navigator.serviceWorker.ready.then((function(n) {
                n.unregister()
            }))
        }
    },
    [
        [262, 1, 2]
    ]
]);