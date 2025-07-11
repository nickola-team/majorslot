/*! For license information please see 7329.a476bb76.chunk.js.LICENSE.txt */
"use strict";
(self.webpackChunk_bithumb_pc = self.webpackChunk_bithumb_pc || []).push([
    [7329], {
        37329: function(e, t, n) {
            n.d(t, {
                ZP: function() {
                    return P
                },
                tv: function() {
                    return k
                }
            });
            var r, i, a = n(38153),
                o = n(79311),
                s = n(78083),
                u = n(26597),
                l = n(9967),
                h = Object.defineProperty,
                c = Object.getOwnPropertySymbols,
                f = Object.prototype.hasOwnProperty,
                v = Object.prototype.propertyIsEnumerable,
                d = function(e, t, n) {
                    return t in e ? h(e, t, {
                        enumerable: !0,
                        configurable: !0,
                        writable: !0,
                        value: n
                    }) : e[t] = n
                },
                g = function(e, t) {
                    for (var n in t || (t = {})) f.call(t, n) && d(e, n, t[n]);
                    if (c) {
                        var r, i = (0, u.Z)(c(t));
                        try {
                            for (i.s(); !(r = i.n()).done;) {
                                n = r.value;
                                v.call(t, n) && d(e, n, t[n])
                            }
                        } catch (a) {
                            i.e(a)
                        } finally {
                            i.f()
                        }
                    }
                    return e
                },
                m = function(e, t) {
                    var n = {};
                    for (var r in e) f.call(e, r) && t.indexOf(r) < 0 && (n[r] = e[r]);
                    if (null != e && c) {
                        var i, a = (0, u.Z)(c(e));
                        try {
                            for (a.s(); !(i = a.n()).done;) {
                                r = i.value;
                                t.indexOf(r) < 0 && v.call(e, r) && (n[r] = e[r])
                            }
                        } catch (o) {
                            a.e(o)
                        } finally {
                            a.f()
                        }
                    }
                    return n
                };
            ! function(e) {
                var t = function() {
                        function t(e, n, r, i) {
                            if ((0, o.Z)(this, t), this.version = e, this.errorCorrectionLevel = n, this.modules = [], this.isFunction = [], e < t.MIN_VERSION || e > t.MAX_VERSION) throw new RangeError("Version value out of range");
                            if (i < -1 || i > 7) throw new RangeError("Mask value out of range");
                            this.size = 4 * e + 17;
                            for (var s = [], u = 0; u < this.size; u++) s.push(!1);
                            for (var l = 0; l < this.size; l++) this.modules.push(s.slice()), this.isFunction.push(s.slice());
                            this.drawFunctionPatterns();
                            var h = this.addEccAndInterleave(r);
                            if (this.drawCodewords(h), -1 == i)
                                for (var c = 1e9, f = 0; f < 8; f++) {
                                    this.applyMask(f), this.drawFormatBits(f);
                                    var v = this.getPenaltyScore();
                                    v < c && (i = f, c = v), this.applyMask(f)
                                }
                            a(0 <= i && i <= 7), this.mask = i, this.applyMask(i), this.drawFormatBits(i), this.isFunction = []
                        }
                        return (0, s.Z)(t, [{
                            key: "getModule",
                            value: function(e, t) {
                                return 0 <= e && e < this.size && 0 <= t && t < this.size && this.modules[t][e]
                            }
                        }, {
                            key: "getModules",
                            value: function() {
                                return this.modules
                            }
                        }, {
                            key: "drawFunctionPatterns",
                            value: function() {
                                for (var e = 0; e < this.size; e++) this.setFunctionModule(6, e, e % 2 == 0), this.setFunctionModule(e, 6, e % 2 == 0);
                                this.drawFinderPattern(3, 3), this.drawFinderPattern(this.size - 4, 3), this.drawFinderPattern(3, this.size - 4);
                                for (var t = this.getAlignmentPatternPositions(), n = t.length, r = 0; r < n; r++)
                                    for (var i = 0; i < n; i++) 0 == r && 0 == i || 0 == r && i == n - 1 || r == n - 1 && 0 == i || this.drawAlignmentPattern(t[r], t[i]);
                                this.drawFormatBits(0), this.drawVersion()
                            }
                        }, {
                            key: "drawFormatBits",
                            value: function(e) {
                                for (var t = this.errorCorrectionLevel.formatBits << 3 | e, n = t, r = 0; r < 10; r++) n = n << 1 ^ 1335 * (n >>> 9);
                                var o = 21522 ^ (t << 10 | n);
                                a(o >>> 15 == 0);
                                for (var s = 0; s <= 5; s++) this.setFunctionModule(8, s, i(o, s));
                                this.setFunctionModule(8, 7, i(o, 6)), this.setFunctionModule(8, 8, i(o, 7)), this.setFunctionModule(7, 8, i(o, 8));
                                for (var u = 9; u < 15; u++) this.setFunctionModule(14 - u, 8, i(o, u));
                                for (var l = 0; l < 8; l++) this.setFunctionModule(this.size - 1 - l, 8, i(o, l));
                                for (var h = 8; h < 15; h++) this.setFunctionModule(8, this.size - 15 + h, i(o, h));
                                this.setFunctionModule(8, this.size - 8, !0)
                            }
                        }, {
                            key: "drawVersion",
                            value: function() {
                                if (!(this.version < 7)) {
                                    for (var e = this.version, t = 0; t < 12; t++) e = e << 1 ^ 7973 * (e >>> 11);
                                    var n = this.version << 12 | e;
                                    a(n >>> 18 == 0);
                                    for (var r = 0; r < 18; r++) {
                                        var o = i(n, r),
                                            s = this.size - 11 + r % 3,
                                            u = Math.floor(r / 3);
                                        this.setFunctionModule(s, u, o), this.setFunctionModule(u, s, o)
                                    }
                                }
                            }
                        }, {
                            key: "drawFinderPattern",
                            value: function(e, t) {
                                for (var n = -4; n <= 4; n++)
                                    for (var r = -4; r <= 4; r++) {
                                        var i = Math.max(Math.abs(r), Math.abs(n)),
                                            a = e + r,
                                            o = t + n;
                                        0 <= a && a < this.size && 0 <= o && o < this.size && this.setFunctionModule(a, o, 2 != i && 4 != i)
                                    }
                            }
                        }, {
                            key: "drawAlignmentPattern",
                            value: function(e, t) {
                                for (var n = -2; n <= 2; n++)
                                    for (var r = -2; r <= 2; r++) this.setFunctionModule(e + r, t + n, 1 != Math.max(Math.abs(r), Math.abs(n)))
                            }
                        }, {
                            key: "setFunctionModule",
                            value: function(e, t, n) {
                                this.modules[t][e] = n, this.isFunction[t][e] = !0
                            }
                        }, {
                            key: "addEccAndInterleave",
                            value: function(e) {
                                var n = this.version,
                                    r = this.errorCorrectionLevel;
                                if (e.length != t.getNumDataCodewords(n, r)) throw new RangeError("Invalid argument");
                                for (var i = t.NUM_ERROR_CORRECTION_BLOCKS[r.ordinal][n], o = t.ECC_CODEWORDS_PER_BLOCK[r.ordinal][n], s = Math.floor(t.getNumRawDataModules(n) / 8), u = i - s % i, l = Math.floor(s / i), h = [], c = t.reedSolomonComputeDivisor(o), f = 0, v = 0; f < i; f++) {
                                    var d = e.slice(v, v + l - o + (f < u ? 0 : 1));
                                    v += d.length;
                                    var g = t.reedSolomonComputeRemainder(d, c);
                                    f < u && d.push(0), h.push(d.concat(g))
                                }
                                for (var m = [], E = function(e) {
                                        h.forEach((function(t, n) {
                                            (e != l - o || n >= u) && m.push(t[e])
                                        }))
                                    }, y = 0; y < h[0].length; y++) E(y);
                                return a(m.length == s), m
                            }
                        }, {
                            key: "drawCodewords",
                            value: function(e) {
                                if (e.length != Math.floor(t.getNumRawDataModules(this.version) / 8)) throw new RangeError("Invalid argument");
                                for (var n = 0, r = this.size - 1; r >= 1; r -= 2) {
                                    6 == r && (r = 5);
                                    for (var o = 0; o < this.size; o++)
                                        for (var s = 0; s < 2; s++) {
                                            var u = r - s,
                                                l = 0 == (r + 1 & 2) ? this.size - 1 - o : o;
                                            !this.isFunction[l][u] && n < 8 * e.length && (this.modules[l][u] = i(e[n >>> 3], 7 - (7 & n)), n++)
                                        }
                                }
                                a(n == 8 * e.length)
                            }
                        }, {
                            key: "applyMask",
                            value: function(e) {
                                if (e < 0 || e > 7) throw new RangeError("Mask value out of range");
                                for (var t = 0; t < this.size; t++)
                                    for (var n = 0; n < this.size; n++) {
                                        var r = void 0;
                                        switch (e) {
                                            case 0:
                                                r = (n + t) % 2 == 0;
                                                break;
                                            case 1:
                                                r = t % 2 == 0;
                                                break;
                                            case 2:
                                                r = n % 3 == 0;
                                                break;
                                            case 3:
                                                r = (n + t) % 3 == 0;
                                                break;
                                            case 4:
                                                r = (Math.floor(n / 3) + Math.floor(t / 2)) % 2 == 0;
                                                break;
                                            case 5:
                                                r = n * t % 2 + n * t % 3 == 0;
                                                break;
                                            case 6:
                                                r = (n * t % 2 + n * t % 3) % 2 == 0;
                                                break;
                                            case 7:
                                                r = ((n + t) % 2 + n * t % 3) % 2 == 0;
                                                break;
                                            default:
                                                throw new Error("Unreachable")
                                        }!this.isFunction[t][n] && r && (this.modules[t][n] = !this.modules[t][n])
                                    }
                            }
                        }, {
                            key: "getPenaltyScore",
                            value: function() {
                                for (var e = 0, n = 0; n < this.size; n++) {
                                    for (var r = !1, i = 0, o = [0, 0, 0, 0, 0, 0, 0], s = 0; s < this.size; s++) this.modules[n][s] == r ? 5 == ++i ? e += t.PENALTY_N1 : i > 5 && e++ : (this.finderPenaltyAddHistory(i, o), r || (e += this.finderPenaltyCountPatterns(o) * t.PENALTY_N3), r = this.modules[n][s], i = 1);
                                    e += this.finderPenaltyTerminateAndCount(r, i, o) * t.PENALTY_N3
                                }
                                for (var l = 0; l < this.size; l++) {
                                    for (var h = !1, c = 0, f = [0, 0, 0, 0, 0, 0, 0], v = 0; v < this.size; v++) this.modules[v][l] == h ? 5 == ++c ? e += t.PENALTY_N1 : c > 5 && e++ : (this.finderPenaltyAddHistory(c, f), h || (e += this.finderPenaltyCountPatterns(f) * t.PENALTY_N3), h = this.modules[v][l], c = 1);
                                    e += this.finderPenaltyTerminateAndCount(h, c, f) * t.PENALTY_N3
                                }
                                for (var d = 0; d < this.size - 1; d++)
                                    for (var g = 0; g < this.size - 1; g++) {
                                        var m = this.modules[d][g];
                                        m == this.modules[d][g + 1] && m == this.modules[d + 1][g] && m == this.modules[d + 1][g + 1] && (e += t.PENALTY_N2)
                                    }
                                var E, y = 0,
                                    M = (0, u.Z)(this.modules);
                                try {
                                    for (M.s(); !(E = M.n()).done;) {
                                        y = E.value.reduce((function(e, t) {
                                            return e + (t ? 1 : 0)
                                        }), y)
                                    }
                                } catch (R) {
                                    M.e(R)
                                } finally {
                                    M.f()
                                }
                                var w = this.size * this.size,
                                    C = Math.ceil(Math.abs(20 * y - 10 * w) / w) - 1;
                                return a(0 <= C && C <= 9), a(0 <= (e += C * t.PENALTY_N4) && e <= 2568888), e
                            }
                        }, {
                            key: "getAlignmentPatternPositions",
                            value: function() {
                                if (1 == this.version) return [];
                                for (var e = Math.floor(this.version / 7) + 2, t = 32 == this.version ? 26 : 2 * Math.ceil((4 * this.version + 4) / (2 * e - 2)), n = [6], r = this.size - 7; n.length < e; r -= t) n.splice(1, 0, r);
                                return n
                            }
                        }, {
                            key: "finderPenaltyCountPatterns",
                            value: function(e) {
                                var t = e[1];
                                a(t <= 3 * this.size);
                                var n = t > 0 && e[2] == t && e[3] == 3 * t && e[4] == t && e[5] == t;
                                return (n && e[0] >= 4 * t && e[6] >= t ? 1 : 0) + (n && e[6] >= 4 * t && e[0] >= t ? 1 : 0)
                            }
                        }, {
                            key: "finderPenaltyTerminateAndCount",
                            value: function(e, t, n) {
                                return e && (this.finderPenaltyAddHistory(t, n), t = 0), t += this.size, this.finderPenaltyAddHistory(t, n), this.finderPenaltyCountPatterns(n)
                            }
                        }, {
                            key: "finderPenaltyAddHistory",
                            value: function(e, t) {
                                0 == t[0] && (e += this.size), t.pop(), t.unshift(e)
                            }
                        }], [{
                            key: "encodeText",
                            value: function(n, r) {
                                var i = e.QrSegment.makeSegments(n);
                                return t.encodeSegments(i, r)
                            }
                        }, {
                            key: "encodeBinary",
                            value: function(n, r) {
                                var i = e.QrSegment.makeBytes(n);
                                return t.encodeSegments([i], r)
                            }
                        }, {
                            key: "encodeSegments",
                            value: function(e, n) {
                                var i, o, s = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : 1,
                                    h = arguments.length > 3 && void 0 !== arguments[3] ? arguments[3] : 40,
                                    c = arguments.length > 4 && void 0 !== arguments[4] ? arguments[4] : -1,
                                    f = !(arguments.length > 5 && void 0 !== arguments[5]) || arguments[5];
                                if (!(t.MIN_VERSION <= s && s <= h && h <= t.MAX_VERSION) || c < -1 || c > 7) throw new RangeError("Invalid value");
                                for (i = s;; i++) {
                                    var v = 8 * t.getNumDataCodewords(i, n),
                                        d = l.getTotalBits(e, i);
                                    if (d <= v) {
                                        o = d;
                                        break
                                    }
                                    if (i >= h) throw new RangeError("Data too long")
                                }
                                for (var g = 0, m = [t.Ecc.MEDIUM, t.Ecc.QUARTILE, t.Ecc.HIGH]; g < m.length; g++) {
                                    var E = m[g];
                                    f && o <= 8 * t.getNumDataCodewords(i, E) && (n = E)
                                }
                                var y, M = [],
                                    w = (0, u.Z)(e);
                                try {
                                    for (w.s(); !(y = w.n()).done;) {
                                        var C = y.value;
                                        r(C.mode.modeBits, 4, M), r(C.numChars, C.mode.numCharCountBits(i), M);
                                        var R, A = (0, u.Z)(C.getData());
                                        try {
                                            for (A.s(); !(R = A.n()).done;) {
                                                var p = R.value;
                                                M.push(p)
                                            }
                                        } catch (I) {
                                            A.e(I)
                                        } finally {
                                            A.f()
                                        }
                                    }
                                } catch (I) {
                                    w.e(I)
                                } finally {
                                    w.f()
                                }
                                a(M.length == o);
                                var N = 8 * t.getNumDataCodewords(i, n);
                                a(M.length <= N), r(0, Math.min(4, N - M.length), M), r(0, (8 - M.length % 8) % 8, M), a(M.length % 8 == 0);
                                for (var k = 236; M.length < N; k ^= 253) r(k, 8, M);
                                for (var P = []; 8 * P.length < M.length;) P.push(0);
                                return M.forEach((function(e, t) {
                                    return P[t >>> 3] |= e << 7 - (7 & t)
                                })), new t(i, n, P, c)
                            }
                        }, {
                            key: "getNumRawDataModules",
                            value: function(e) {
                                if (e < t.MIN_VERSION || e > t.MAX_VERSION) throw new RangeError("Version number out of range");
                                var n = (16 * e + 128) * e + 64;
                                if (e >= 2) {
                                    var r = Math.floor(e / 7) + 2;
                                    n -= (25 * r - 10) * r - 55, e >= 7 && (n -= 36)
                                }
                                return a(208 <= n && n <= 29648), n
                            }
                        }, {
                            key: "getNumDataCodewords",
                            value: function(e, n) {
                                return Math.floor(t.getNumRawDataModules(e) / 8) - t.ECC_CODEWORDS_PER_BLOCK[n.ordinal][e] * t.NUM_ERROR_CORRECTION_BLOCKS[n.ordinal][e]
                            }
                        }, {
                            key: "reedSolomonComputeDivisor",
                            value: function(e) {
                                if (e < 1 || e > 255) throw new RangeError("Degree out of range");
                                for (var n = [], r = 0; r < e - 1; r++) n.push(0);
                                n.push(1);
                                for (var i = 1, a = 0; a < e; a++) {
                                    for (var o = 0; o < n.length; o++) n[o] = t.reedSolomonMultiply(n[o], i), o + 1 < n.length && (n[o] ^= n[o + 1]);
                                    i = t.reedSolomonMultiply(i, 2)
                                }
                                return n
                            }
                        }, {
                            key: "reedSolomonComputeRemainder",
                            value: function(e, n) {
                                var r, i = n.map((function(e) {
                                        return 0
                                    })),
                                    a = (0, u.Z)(e);
                                try {
                                    var o = function() {
                                        var e = r.value ^ i.shift();
                                        i.push(0), n.forEach((function(n, r) {
                                            return i[r] ^= t.reedSolomonMultiply(n, e)
                                        }))
                                    };
                                    for (a.s(); !(r = a.n()).done;) o()
                                } catch (s) {
                                    a.e(s)
                                } finally {
                                    a.f()
                                }
                                return i
                            }
                        }, {
                            key: "reedSolomonMultiply",
                            value: function(e, t) {
                                if (e >>> 8 != 0 || t >>> 8 != 0) throw new RangeError("Byte out of range");
                                for (var n = 0, r = 7; r >= 0; r--) n = n << 1 ^ 285 * (n >>> 7), n ^= (t >>> r & 1) * e;
                                return a(n >>> 8 == 0), n
                            }
                        }]), t
                    }(),
                    n = t;

                function r(e, t, n) {
                    if (t < 0 || t > 31 || e >>> t != 0) throw new RangeError("Value out of range");
                    for (var r = t - 1; r >= 0; r--) n.push(e >>> r & 1)
                }

                function i(e, t) {
                    return 0 != (e >>> t & 1)
                }

                function a(e) {
                    if (!e) throw new Error("Assertion error")
                }
                n.MIN_VERSION = 1, n.MAX_VERSION = 40, n.PENALTY_N1 = 3, n.PENALTY_N2 = 3, n.PENALTY_N3 = 40, n.PENALTY_N4 = 10, n.ECC_CODEWORDS_PER_BLOCK = [
                    [-1, 7, 10, 15, 20, 26, 18, 20, 24, 30, 18, 20, 24, 26, 30, 22, 24, 28, 30, 28, 28, 28, 28, 30, 30, 26, 28, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30],
                    [-1, 10, 16, 26, 18, 24, 16, 18, 22, 22, 26, 30, 22, 22, 24, 24, 28, 28, 26, 26, 26, 26, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28],
                    [-1, 13, 22, 18, 26, 18, 24, 18, 22, 20, 24, 28, 26, 24, 20, 30, 24, 28, 28, 26, 30, 28, 30, 30, 30, 30, 28, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30],
                    [-1, 17, 28, 22, 16, 22, 28, 26, 26, 24, 28, 24, 28, 22, 24, 24, 30, 28, 28, 26, 28, 30, 24, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30]
                ], n.NUM_ERROR_CORRECTION_BLOCKS = [
                    [-1, 1, 1, 1, 1, 1, 2, 2, 2, 2, 4, 4, 4, 4, 4, 6, 6, 6, 6, 7, 8, 8, 9, 9, 10, 12, 12, 12, 13, 14, 15, 16, 17, 18, 19, 19, 20, 21, 22, 24, 25],
                    [-1, 1, 1, 1, 2, 2, 4, 4, 4, 5, 5, 5, 8, 9, 9, 10, 10, 11, 13, 14, 16, 17, 17, 18, 20, 21, 23, 25, 26, 28, 29, 31, 33, 35, 37, 38, 40, 43, 45, 47, 49],
                    [-1, 1, 1, 2, 2, 4, 4, 6, 6, 8, 8, 8, 10, 12, 16, 12, 17, 16, 18, 21, 20, 23, 23, 25, 27, 29, 34, 34, 35, 38, 40, 43, 45, 48, 51, 53, 56, 59, 62, 65, 68],
                    [-1, 1, 1, 2, 4, 4, 4, 5, 6, 8, 8, 11, 11, 16, 16, 18, 16, 19, 21, 25, 25, 25, 34, 30, 32, 35, 37, 40, 42, 45, 48, 51, 54, 57, 60, 63, 66, 70, 74, 77, 81]
                ], e.QrCode = n;
                var l = function() {
                    function e(t, n, r) {
                        if ((0, o.Z)(this, e), this.mode = t, this.numChars = n, this.bitData = r, n < 0) throw new RangeError("Invalid argument");
                        this.bitData = r.slice()
                    }
                    return (0, s.Z)(e, [{
                        key: "getData",
                        value: function() {
                            return this.bitData.slice()
                        }
                    }], [{
                        key: "makeBytes",
                        value: function(t) {
                            var n, i = [],
                                a = (0, u.Z)(t);
                            try {
                                for (a.s(); !(n = a.n()).done;) {
                                    r(n.value, 8, i)
                                }
                            } catch (o) {
                                a.e(o)
                            } finally {
                                a.f()
                            }
                            return new e(e.Mode.BYTE, t.length, i)
                        }
                    }, {
                        key: "makeNumeric",
                        value: function(t) {
                            if (!e.isNumeric(t)) throw new RangeError("String contains non-numeric characters");
                            for (var n = [], i = 0; i < t.length;) {
                                var a = Math.min(t.length - i, 3);
                                r(parseInt(t.substr(i, a), 10), 3 * a + 1, n), i += a
                            }
                            return new e(e.Mode.NUMERIC, t.length, n)
                        }
                    }, {
                        key: "makeAlphanumeric",
                        value: function(t) {
                            if (!e.isAlphanumeric(t)) throw new RangeError("String contains unencodable characters in alphanumeric mode");
                            var n, i = [];
                            for (n = 0; n + 2 <= t.length; n += 2) {
                                var a = 45 * e.ALPHANUMERIC_CHARSET.indexOf(t.charAt(n));
                                r(a += e.ALPHANUMERIC_CHARSET.indexOf(t.charAt(n + 1)), 11, i)
                            }
                            return n < t.length && r(e.ALPHANUMERIC_CHARSET.indexOf(t.charAt(n)), 6, i), new e(e.Mode.ALPHANUMERIC, t.length, i)
                        }
                    }, {
                        key: "makeSegments",
                        value: function(t) {
                            return "" == t ? [] : e.isNumeric(t) ? [e.makeNumeric(t)] : e.isAlphanumeric(t) ? [e.makeAlphanumeric(t)] : [e.makeBytes(e.toUtf8ByteArray(t))]
                        }
                    }, {
                        key: "makeEci",
                        value: function(t) {
                            var n = [];
                            if (t < 0) throw new RangeError("ECI assignment value out of range");
                            if (t < 128) r(t, 8, n);
                            else if (t < 16384) r(2, 2, n), r(t, 14, n);
                            else {
                                if (!(t < 1e6)) throw new RangeError("ECI assignment value out of range");
                                r(6, 3, n), r(t, 21, n)
                            }
                            return new e(e.Mode.ECI, 0, n)
                        }
                    }, {
                        key: "isNumeric",
                        value: function(t) {
                            return e.NUMERIC_REGEX.test(t)
                        }
                    }, {
                        key: "isAlphanumeric",
                        value: function(t) {
                            return e.ALPHANUMERIC_REGEX.test(t)
                        }
                    }, {
                        key: "getTotalBits",
                        value: function(e, t) {
                            var n, r = 0,
                                i = (0, u.Z)(e);
                            try {
                                for (i.s(); !(n = i.n()).done;) {
                                    var a = n.value,
                                        o = a.mode.numCharCountBits(t);
                                    if (a.numChars >= 1 << o) return 1 / 0;
                                    r += 4 + o + a.bitData.length
                                }
                            } catch (s) {
                                i.e(s)
                            } finally {
                                i.f()
                            }
                            return r
                        }
                    }, {
                        key: "toUtf8ByteArray",
                        value: function(e) {
                            e = encodeURI(e);
                            for (var t = [], n = 0; n < e.length; n++) "%" != e.charAt(n) ? t.push(e.charCodeAt(n)) : (t.push(parseInt(e.substr(n + 1, 2), 16)), n += 2);
                            return t
                        }
                    }]), e
                }();
                l.NUMERIC_REGEX = /^[0-9]*$/, l.ALPHANUMERIC_REGEX = /^[A-Z0-9 $%*+.\/:-]*$/, l.ALPHANUMERIC_CHARSET = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ $%*+-./:", e.QrSegment = l
            }(r || (r = {})),
            function(e) {
                var t = (0, s.Z)((function e(t, n) {
                        (0, o.Z)(this, e), this.ordinal = t, this.formatBits = n
                    })),
                    n = t;
                n.LOW = new t(0, 1), n.MEDIUM = new t(1, 0), n.QUARTILE = new t(2, 3), n.HIGH = new t(3, 2), e.Ecc = n
            }((i = r || (r = {})).QrCode || (i.QrCode = {})),
            function(e) {
                ! function(e) {
                    var t = function() {
                            function e(t, n) {
                                (0, o.Z)(this, e), this.modeBits = t, this.numBitsCharCount = n
                            }
                            return (0, s.Z)(e, [{
                                key: "numCharCountBits",
                                value: function(e) {
                                    return this.numBitsCharCount[Math.floor((e + 7) / 17)]
                                }
                            }]), e
                        }(),
                        n = t;
                    n.NUMERIC = new t(1, [10, 12, 14]), n.ALPHANUMERIC = new t(2, [9, 11, 13]), n.BYTE = new t(4, [8, 16, 16]), n.KANJI = new t(8, [8, 10, 12]), n.ECI = new t(7, [0, 0, 0]), e.Mode = n
                }(e.QrSegment || (e.QrSegment = {}))
            }(r || (r = {}));
            var E = r,
                y = {
                    L: E.QrCode.Ecc.LOW,
                    M: E.QrCode.Ecc.MEDIUM,
                    Q: E.QrCode.Ecc.QUARTILE,
                    H: E.QrCode.Ecc.HIGH
                },
                M = "#FFFFFF",
                w = "#000000";

            function C(e) {
                var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : 0,
                    n = [];
                return e.forEach((function(e, r) {
                    var i = null;
                    e.forEach((function(a, o) {
                        if (!a && null !== i) return n.push("M".concat(i + t, " ").concat(r + t, "h").concat(o - i, "v1H").concat(i + t, "z")), void(i = null);
                        if (o !== e.length - 1) a && null === i && (i = o);
                        else {
                            if (!a) return;
                            null === i ? n.push("M".concat(o + t, ",").concat(r + t, " h1v1H").concat(o + t, "z")) : n.push("M".concat(i + t, ",").concat(r + t, " h").concat(o + 1 - i, "v1H").concat(i + t, "z"))
                        }
                    }))
                })), n.join("")
            }

            function R(e, t) {
                return e.slice().map((function(e, n) {
                    return n < t.y || n >= t.y + t.h ? e : e.map((function(e, n) {
                        return (n < t.x || n >= t.x + t.w) && e
                    }))
                }))
            }

            function A(e, t, n, r) {
                if (null == r) return null;
                var i = n ? 4 : 0,
                    a = e.length + 2 * i,
                    o = Math.floor(.1 * t),
                    s = a / t,
                    u = (r.width || o) * s,
                    l = (r.height || o) * s,
                    h = null == r.x ? e.length / 2 - u / 2 : r.x * s,
                    c = null == r.y ? e.length / 2 - l / 2 : r.y * s,
                    f = null;
                if (r.excavate) {
                    var v = Math.floor(h),
                        d = Math.floor(c);
                    f = {
                        x: v,
                        y: d,
                        w: Math.ceil(u + h - v),
                        h: Math.ceil(l + c - d)
                    }
                }
                return {
                    x: h,
                    y: c,
                    h: l,
                    w: u,
                    excavation: f
                }
            }
            var p = function() {
                try {
                    (new Path2D).addPath(new Path2D)
                } catch (e) {
                    return !1
                }
                return !0
            }();

            function N(e) {
                var t = e,
                    n = t.value,
                    r = t.size,
                    i = void 0 === r ? 128 : r,
                    o = t.level,
                    s = void 0 === o ? "L" : o,
                    u = t.bgColor,
                    h = void 0 === u ? M : u,
                    c = t.fgColor,
                    f = void 0 === c ? w : c,
                    v = t.includeMargin,
                    d = void 0 !== v && v,
                    N = t.style,
                    k = t.imageSettings,
                    P = m(t, ["value", "size", "level", "bgColor", "fgColor", "includeMargin", "style", "imageSettings"]),
                    I = null == k ? void 0 : k.src,
                    _ = (0, l.useRef)(null),
                    S = (0, l.useRef)(null),
                    O = (0, l.useState)(!1),
                    b = (0, a.Z)(O, 2),
                    z = (b[0], b[1]);
                (0, l.useEffect)((function() {
                    if (null != _.current) {
                        var e = _.current,
                            t = e.getContext("2d");
                        if (!t) return;
                        var r = E.QrCode.encodeText(n, y[s]).getModules(),
                            a = d ? 4 : 0,
                            o = r.length + 2 * a,
                            u = A(r, i, d, k),
                            l = S.current,
                            c = null != u && null !== l && l.complete && 0 !== l.naturalHeight && 0 !== l.naturalWidth;
                        c && null != u.excavation && (r = R(r, u.excavation));
                        var v = window.devicePixelRatio || 1;
                        e.height = e.width = i * v;
                        var g = i / o * v;
                        t.scale(g, g), t.fillStyle = h, t.fillRect(0, 0, o, o), t.fillStyle = f, p ? t.fill(new Path2D(C(r, a))) : r.forEach((function(e, n) {
                            e.forEach((function(e, r) {
                                e && t.fillRect(r + a, n + a, 1, 1)
                            }))
                        })), c && t.drawImage(l, u.x + a, u.y + a, u.w, u.h)
                    }
                })), (0, l.useEffect)((function() {
                    z(!1)
                }), [I]);
                var F = g({
                        height: i,
                        width: i
                    }, N),
                    L = null;
                return null != I && (L = l.createElement("img", {
                    src: I,
                    key: I,
                    style: {
                        display: "none"
                    },
                    onLoad: function() {
                        z(!0)
                    },
                    ref: S
                })), l.createElement(l.Fragment, null, l.createElement("canvas", g({
                    style: F,
                    height: i,
                    width: i,
                    ref: _
                }, P)), L)
            }

            function k(e) {
                var t = e,
                    n = t.value,
                    r = t.size,
                    i = void 0 === r ? 128 : r,
                    a = t.level,
                    o = void 0 === a ? "L" : a,
                    s = t.bgColor,
                    u = void 0 === s ? M : s,
                    h = t.fgColor,
                    c = void 0 === h ? w : h,
                    f = t.includeMargin,
                    v = void 0 !== f && f,
                    d = t.imageSettings,
                    p = m(t, ["value", "size", "level", "bgColor", "fgColor", "includeMargin", "imageSettings"]),
                    N = E.QrCode.encodeText(n, y[o]).getModules(),
                    k = v ? 4 : 0,
                    P = N.length + 2 * k,
                    I = A(N, i, v, d),
                    _ = null;
                null != d && null != I && (null != I.excavation && (N = R(N, I.excavation)), _ = l.createElement("image", {
                    xlinkHref: d.src,
                    height: I.h,
                    width: I.w,
                    x: I.x + k,
                    y: I.y + k,
                    preserveAspectRatio: "none"
                }));
                var S = C(N, k);
                return l.createElement("svg", g({
                    height: i,
                    width: i,
                    viewBox: "0 0 ".concat(P, " ").concat(P)
                }, p), l.createElement("path", {
                    fill: u,
                    d: "M0,0 h".concat(P, "v").concat(P, "H0z"),
                    shapeRendering: "crispEdges"
                }), l.createElement("path", {
                    fill: c,
                    d: S,
                    shapeRendering: "crispEdges"
                }), _)
            }
            var P = function(e) {
                var t = e,
                    n = t.renderAs,
                    r = m(t, ["renderAs"]);
                return "svg" === n ? l.createElement(k, g({}, r)) : l.createElement(N, g({}, r))
            }
        }
    }
]);