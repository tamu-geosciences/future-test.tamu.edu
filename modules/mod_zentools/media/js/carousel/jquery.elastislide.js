(function (e, t, n) {
    t.fn.touchwipe = function (e) {
        var n = {
            min_move_x: 20,
            min_move_y: 20,
            wipeLeft: function () {},
            wipeRight: function () {},
            wipeUp: function () {},
            wipeDown: function () {},
            preventDefaultEvents: !0
        };
        e && t.extend(n, e);
        this.each(function () {
            function i() {
                this.removeEventListener("touchmove", s);
                e = null;
                r = !1
            }
            function s(s) {
                n.preventDefaultEvents && s.preventDefault();
                if (r) {
                    var o = s.touches[0].pageX,
                        u = s.touches[0].pageY,
                        a = e - o,
                        f = t - u;
                    if (Math.abs(a) >= n.min_move_x) {
                        i();
                        a > 0 ? n.wipeLeft() : n.wipeRight()
                    } else if (Math.abs(f) >= n.min_move_y) {
                        i();
                        f > 0 ? n.wipeDown() : n.wipeUp()
                    }
                }
            }
            function o(n) {
                if (n.touches.length == 1) {
                    e = n.touches[0].pageX;
                    t = n.touches[0].pageY;
                    r = !0;
                    this.addEventListener("touchmove", s, !1)
                }
            }
            var e, t, r = !1;
            "ontouchstart" in document.documentElement && this.addEventListener("touchstart", o, !1)
        });
        return this
    };
    t.elastislide = function (e, n) {
        this.$el = t(n);
        this._init(e)
    };
    t.elastislide.defaults = {
        speed: 450,
        easing: "",
        imageW: 190,
        margin: 3,
        border: 2,
        minItems: 1,
        current: 0,
        onClick: function () {
            return !1
        }
    };
    t.elastislide.prototype = {
        _init: function (e) {
            this.options = t.extend(!0, {}, t.elastislide.defaults, e);
            this.$slider = this.$el.find("ul");
            this.$items = this.$slider.children("li");
            this.itemsCount = this.$items.length;
            this.$esCarousel = this.$slider.parent();
            this._validateOptions();
            this._configure();
            this._addControls();
            this._initEvents();
            this.$slider.show();
            this._slideToCurrent(!1)
        },
        _validateOptions: function () {
            this.options.speed < 0 && (this.options.speed = 450);
            this.options.margin < 0 && (this.options.margin = 4);
            this.options.border < 0 && (this.options.border = 1);
            if (this.options.minItems < 1 || this.options.minItems > this.itemsCount) this.options.minItems = 1;
            this.options.current > this.itemsCount - 1 && (this.options.current = 0)
        },
        _configure: function () {
            this.current = this.options.current;
            this.visibleWidth = this.$esCarousel.width();
            if (this.visibleWidth < this.options.minItems * (this.options.imageW + 2 * this.options.border) + (this.options.minItems - 1) * this.options.margin) {
                this._setDim((this.visibleWidth - (this.options.minItems - 1) * this.options.margin) / this.options.minItems);
                this._setCurrentValues();
                this.fitCount = this.options.minItems
            } else {
                this._setDim();
                this._setCurrentValues()
            }
            this.$slider.css({
                width: this.sliderW + 24
            })
        },
        _setDim: function (e) {
            this.$items.css({
                marginRight: this.options.margin,
                width: e ? e : this.options.imageW + 2 * this.options.border
            }).children("a").css({
                borderWidth: this.options.border
            })
        },
        _setCurrentValues: function () {
            this.itemW = this.$items.outerWidth(!0);
            this.sliderW = this.itemW * this.itemsCount;
            this.visibleWidth = this.$esCarousel.width();
            this.fitCount = Math.floor(this.visibleWidth / this.itemW)
        },
        _addControls: function () {
            this.$navNext = t('<span class="es-nav-next"><i class="icon-right-open"></i><span>Next</span></span>');
            this.$navPrev = t('<span class="es-nav-prev"><i class="icon-left-open"></i><span>Previous</span></span>');
            t('<div class="es-nav"/>').append(this.$navPrev).append(this.$navNext).appendTo(this.$el)
        },
        _toggleControls: function (e, t) {
            e && t ? t === 1 ? e === "right" ? this.$navNext.show() : this.$navPrev.show() : e === "right" ? this.$navNext.hide() : this.$navPrev.hide() : (this.current === this.itemsCount - 1 || this.fitCount >= this.itemsCount) && this.$navNext.hide()
        },
        _initEvents: function () {
            var n = this;
            t(e).bind("resize.elastislide", function (e) {
                refresh();
            });
            this.$navNext.bind("click.elastislide", function (e) {
                n._slide("right")
            });
            this.$navPrev.bind("click.elastislide", function (e) {
                n._slide("left")
            });
            this.$items.bind("click.elastislide", function (e) {
                n.options.onClick(t(this));
                return !1
            });
            n.$slider.touchwipe({
                wipeLeft: function () {
                    n._slide("right")
                },
                wipeRight: function () {
                    n._slide("left")
                }
            })
        },
        _slide: function (e, r, i, s) {
            if (this.$slider.is(":animated")) return !1;
            var o = parseFloat(this.$slider.css("margin-left"));
            if (r === n) {
                var u = this.fitCount * this.itemW,
                    r;
                if (u < 0) return !1;
                if (e === "right" && this.sliderW - (Math.abs(o) + u) < this.visibleWidth) {
                    u = this.sliderW - (Math.abs(o) + this.visibleWidth) - this.options.margin;
                    this._toggleControls("right", -1);
                    this._toggleControls("left", 1)
                } else if (e === "left" && Math.abs(o) - u < 0) {
                    u = Math.abs(o);
                    this._toggleControls("left", -1);
                    this._toggleControls("right", 1)
                } else {
                    var a;
                    e === "right" ? a = Math.abs(o) + this.options.margin + Math.abs(u) : a = Math.abs(o) - this.options.margin - Math.abs(u);
                    a > 0 ? this._toggleControls("left", 1) : this._toggleControls("left", -1);
                    a < this.sliderW - this.visibleWidth ? this._toggleControls("right", 1) : this._toggleControls("right", -1)
                }
                e === "right" ? r = "-=" + u : r = "+=" + u
            } else {
                var a = Math.abs(r);
                if (Math.max(this.sliderW, this.visibleWidth) - a < this.visibleWidth) {
                    r = -(Math.max(this.sliderW, this.visibleWidth) - this.visibleWidth);
                    r !== 0 && (r += this.options.margin);
                    this._toggleControls("right", -1);
                    a = Math.abs(r)
                }
                a > 0 ? this._toggleControls("left", 1) : this._toggleControls("left", -1);
                Math.max(this.sliderW, this.visibleWidth) - this.visibleWidth > a + this.options.margin ? this._toggleControls("right", 1) : this._toggleControls("right", -1)
            }
            t.fn.applyStyle = i === n ? t.fn.animate : t.fn.css;
            var f = {
                marginLeft: r
            }, l = this;
            this.$slider.applyStyle(f, t.extend(!0, [], {
                duration: this.options.speed,
                easing: this.options.easing,
                complete: function () {
                    s && s.call()
                }
            }))
        },
        _slideToCurrent: function (e) {
            var t = this.current * this.itemW;
            this._slide("", -t, e)
        },
        add: function (e, t) {
            this.$items = this.$items.add(e);
            this.itemsCount = this.$items.length;
            this._setDim();
            this._setCurrentValues();
            this.$slider.css({
                width: this.sliderW
            });
            this._slideToCurrent();
            t && t.call(e)
        },
        destroy: function (e) {
            this._destroy(e)
        },
        _destroy: function (n) {
            this.$el.unbind(".elastislide").removeData("elastislide");
            t(e).unbind(".elastislide");
            n && n.call()
        },
        refresh: function(n) {
            var n = this;

            n._setCurrentValues();
            if (n.visibleWidth < n.options.minItems * (n.options.imageW + 2 * n.options.border) + (n.options.minItems - 1) * n.options.margin) {
                n._setDim((n.visibleWidth - (n.options.minItems - 1) * n.options.margin) / n.options.minItems);
                n._setCurrentValues();
                n.fitCount = n.options.minItems
            } else {
                n._setDim();
                n._setCurrentValues()
            }
            n.$slider.css({
                width: n.sliderW + 10
            });
            clearTimeout(n.resetTimeout);
            n.resetTimeout = setTimeout(function () {
                n._slideToCurrent()
            }, 200);
        }
    };
    var r = function (e) {
        this.console && console.error(e)
    };
    t.fn.elastislide = function (e) {
        if (typeof e == "string") {
            var n = Array.prototype.slice.call(arguments, 1);
            this.each(function () {
                var i = t.data(this, "elastislide");
                if (!i) {
                    r("cannot call methods on elastislide prior to initialization; attempted to call method '" + e + "'");
                    return
                }
                if (!t.isFunction(i[e]) || e.charAt(0) === "_") {
                    r("no such method '" + e + "' for elastislide instance");
                    return
                }
                i[e].apply(i, n)
            })
        } else this.each(function () {
            var n = t.data(this, "elastislide");
            n || t.data(this, "elastislide", new t.elastislide(e, this))
        });
        return this
    }
})(window, jQuery);
