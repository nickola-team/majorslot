/*
 *
 * Copyright (c) 2006-2014 Sam Collett (http://www.texotela.co.uk)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Version 1.4.1
 * Demo: http://www.texotela.co.uk/code/jquery/numeric/
 *
 */
(function(factory) {
    if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    } else {
        factory(window.jQuery);
    }
}(function($) {
    $.fn.numeric = function(config, callback) {
        if (typeof config === "boolean") {
            config = {
                decimal: config,
                negative: true,
                decimalPlaces: -1
            }
        }
        config = config || {};
        if (typeof config.negative == "undefined") {
            config.negative = true
        }
        var decimal = config.decimal === false ? "" : config.decimal || ".";
        var negative = config.negative === true ? true : false;
        var decimalPlaces = typeof config.decimalPlaces == "undefined" ? -1 : config.decimalPlaces;
        callback = typeof callback == "function" ? callback : function() {};
        return this.data("numeric.decimal", decimal).data("numeric.negative", negative).data("numeric.callback", callback).data("numeric.decimalPlaces", decimalPlaces).keypress($.fn.numeric.keypress).keyup($.fn.numeric.keyup).blur($.fn.numeric.blur)
    };
    $.fn.numeric.keypress = function(e) {
        var decimal = $.data(this, "numeric.decimal");
        var negative = $.data(this, "numeric.negative");
        var decimalPlaces = $.data(this, "numeric.decimalPlaces");
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if (key == 13 && this.nodeName.toLowerCase() == "input") {
            return true
        } else if (key == 13) {
            return false
        }
        var allow = false;
        if (e.ctrlKey && key == 97 || e.ctrlKey && key == 65) {
            return true
        }
        if (e.ctrlKey && key == 120 || e.ctrlKey && key == 88) {
            return true
        }
        if (e.ctrlKey && key == 99 || e.ctrlKey && key == 67) {
            return true
        }
        if (e.ctrlKey && key == 122 || e.ctrlKey && key == 90) {
            return true
        }
        if (e.ctrlKey && key == 118 || e.ctrlKey && key == 86 || e.shiftKey && key == 45) {
            return true
        }
        if (key < 48 || key > 57) {
            var value = $(this).val();
            if ($.inArray("-", value.split("")) !== 0 && negative && key == 45 && (value.length === 0 || parseInt($.fn.getSelectionStart(this), 10) === 0)) {
                return true
            }
            if (decimal && key == decimal.charCodeAt(0) && $.inArray(decimal, value.split("")) != -1) {
                allow = false
            }
            if (key != 8 && key != 9 && key != 13 && key != 35 && key != 36 && key != 37 && key != 39 && key != 46) {
                allow = false
            } else {
                if (typeof e.charCode != "undefined") {
                    if (e.keyCode == e.which && e.which !== 0) {
                        allow = true;
                        if (e.which == 46) {
                            allow = false
                        }
                    } else if (e.keyCode !== 0 && e.charCode === 0 && e.which === 0) {
                        allow = true
                    }
                }
            }
            if (decimal && key == decimal.charCodeAt(0)) {
                if ($.inArray(decimal, value.split("")) == -1) {
                    allow = true
                } else {
                    allow = false
                }
            }
        } else {
            allow = true;
            if (decimal && decimalPlaces > 0) {
                var dot = $.inArray(decimal, $(this).val().split(""));
                if (dot >= 0 && $(this).val().length > dot + decimalPlaces) {
                    allow = false
                }
            }
        }
        return allow
    };
    $.fn.numeric.keyup = function(e) {
        var val = $(this).val();
        if (val && val.length > 0) {
            var carat = $.fn.getSelectionStart(this);
            var selectionEnd = $.fn.getSelectionEnd(this);
            var decimal = $.data(this, "numeric.decimal");
            var negative = $.data(this, "numeric.negative");
            var decimalPlaces = $.data(this, "numeric.decimalPlaces");
            if (decimal !== "" && decimal !== null) {
                var dot = $.inArray(decimal, val.split(""));
                if (dot === 0) {
                    this.value = "0" + val;
                    carat++;
                    selectionEnd++
                }
                if (dot == 1 && val.charAt(0) == "-") {
                    this.value = "-0" + val.substring(1);
                    carat++;
                    selectionEnd++
                }
                val = this.value
            }
            var validChars = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, "-", decimal];
            var length = val.length;
            for (var i = length - 1; i >= 0; i--) {
                var ch = val.charAt(i);
                if (i !== 0 && ch == "-") {
                    val = val.substring(0, i) + val.substring(i + 1)
                } else if (i === 0 && !negative && ch == "-") {
                    val = val.substring(1)
                }
                var validChar = false;
                for (var j = 0; j < validChars.length; j++) {
                    if (ch == validChars[j]) {
                        validChar = true;
                        break
                    }
                }
                if (!validChar || ch == " ") {
                    val = val.substring(0, i) + val.substring(i + 1)
                }
            }
            var firstDecimal = $.inArray(decimal, val.split(""));
            if (firstDecimal > 0) {
                for (var k = length - 1; k > firstDecimal; k--) {
                    var chch = val.charAt(k);
                    if (chch == decimal) {
                        val = val.substring(0, k) + val.substring(k + 1)
                    }
                }
            }
            if (decimal && decimalPlaces > 0) {
                var dot = $.inArray(decimal, val.split(""));
                if (dot >= 0) {
                    val = val.substring(0, dot + decimalPlaces + 1);
                    selectionEnd = Math.min(val.length, selectionEnd)
                }
            }
            this.value = val;
            $.fn.setSelection(this, [carat, selectionEnd])
        }
    };
    $.fn.numeric.blur = function() {
        var decimal = $.data(this, "numeric.decimal");
        var callback = $.data(this, "numeric.callback");
        var negative = $.data(this, "numeric.negative");
        var val = this.value;
        if (val !== "") {
            var re = new RegExp("^" + (negative ? "-?" : "") + "\\d+$|^" + (negative ? "-?" : "") + "\\d*" + decimal + "\\d+$");
            if (!re.exec(val)) {
                callback.apply(this)
            }
        }
    };
    $.fn.removeNumeric = function() {
        return this.data("numeric.decimal", null).data("numeric.negative", null).data("numeric.callback", null).data("numeric.decimalPlaces", null).unbind("keypress", $.fn.numeric.keypress).unbind("keyup", $.fn.numeric.keyup).unbind("blur", $.fn.numeric.blur)
    };
    $.fn.getSelectionStart = function(o) {
        if (o.type === "number") {
            return undefined
        } else if (o.createTextRange && document.selection) {
            var r = document.selection.createRange().duplicate();
            r.moveEnd("character", o.value.length);
            if (r.text == "") return o.value.length;
            return Math.max(0, o.value.lastIndexOf(r.text))
        } else {
            try {
                return o.selectionStart
            } catch (e) {
                return 0
            }
        }
    };
    $.fn.getSelectionEnd = function(o) {
        if (o.type === "number") {
            return undefined
        } else if (o.createTextRange && document.selection) {
            var r = document.selection.createRange().duplicate();
            r.moveStart("character", -o.value.length);
            return r.text.length
        } else return o.selectionEnd
    };
    $.fn.setSelection = function(o, p) {
        if (typeof p == "number") {
            p = [p, p]
        }
        if (p && p.constructor == Array && p.length == 2) {
            if (o.type === "number") {
                o.focus()
            } else if (o.createTextRange) {
                var r = o.createTextRange();
                r.collapse(true);
                r.moveStart("character", p[0]);
                r.moveEnd("character", p[1] - p[0]);
                r.select()
            } else {
                o.focus();
                try {
                    if (o.setSelectionRange) {
                        o.setSelectionRange(p[0], p[1])
                    }
                } catch (e) {}
            }
        }
    }
}));