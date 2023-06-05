/******/
(function(modules) { // webpackBootstrap
    /******/ // The module cache
    /******/
    var installedModules = {};
    /******/
    /******/ // The require function
    /******/
    function __webpack_require__(moduleId) {
        /******/
        /******/ // Check if module is in cache
        /******/
        if (installedModules[moduleId]) {
            /******/
            return installedModules[moduleId].exports;
            /******/
        }
        /******/ // Create a new module (and put it into the cache)
        /******/
        var module = installedModules[moduleId] = {
            /******/
            i: moduleId,
            /******/
            l: false,
            /******/
            exports: {}
            /******/
        };
        /******/
        /******/ // Execute the module function
        /******/
        modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
        /******/
        /******/ // Flag the module as loaded
        /******/
        module.l = true;
        /******/
        /******/ // Return the exports of the module
        /******/
        return module.exports;
        /******/
    }
    /******/
    /******/
    /******/ // expose the modules object (__webpack_modules__)
    /******/
    __webpack_require__.m = modules;
    /******/
    /******/ // expose the module cache
    /******/
    __webpack_require__.c = installedModules;
    /******/
    /******/ // define getter function for harmony exports
    /******/
    __webpack_require__.d = function(exports, name, getter) {
        /******/
        if (!__webpack_require__.o(exports, name)) {
            /******/
            Object.defineProperty(exports, name, {
                /******/
                configurable: false,
                /******/
                enumerable: true,
                /******/
                get: getter
                /******/
            });
            /******/
        }
        /******/
    };
    /******/
    /******/ // getDefaultExport function for compatibility with non-harmony modules
    /******/
    __webpack_require__.n = function(module) {
        /******/
        var getter = module && module.__esModule ?
            /******/
            function getDefault() {
                return module['default'];
            } :
            /******/
            function getModuleExports() {
                return module;
            };
        /******/
        __webpack_require__.d(getter, 'a', getter);
        /******/
        return getter;
        /******/
    };
    /******/
    /******/ // Object.prototype.hasOwnProperty.call
    /******/
    __webpack_require__.o = function(object, property) {
        return Object.prototype.hasOwnProperty.call(object, property);
    };
    /******/
    /******/ // __webpack_public_path__
    /******/
    __webpack_require__.p = "";
    /******/
    /******/ // Load entry module and return exports
    /******/
    return __webpack_require__(__webpack_require__.s = 13);
    /******/
})
/************************************************************************/
/******/
([
    /* 0 */
    /***/
    (function(module, exports) {

        var locales = {
            en: {
                'All Currencies': 'All'
            },

            ru: {
                'All': 'Все',
                'All Currencies': 'Все',
                'Report Type': 'Тип Отчета',
                'Game': 'Игра',
                'Currency': 'Валюта',
                'Round': 'Раунд',
                'Rounds': 'Раунды',
                'Bet': 'Ставка',
                'Bets': 'Ставки',
                'Win': 'Выигрыш',
                'Wins': 'Выигрыш',
                'Profit': 'Прибыль',
                'Outcome': 'Результат',
                'Payout': 'Выплата',
                'Payout,%': 'Выплата,%',
                'Date': 'Дата',
                'Bal. Before': 'Бал. До',
                'Bal. After': 'Бал. После',
                'Game mode': 'Тип игры',
                'Date Range': 'Период',
                'Search': 'Поиск',
                'Player Games Results': 'Игры',
                'Total': 'Всего',
                'Player': 'Игрок',
                'Project': 'Проект',
                'Provider': 'Провайдер',
                'Info': 'Информация',

                'Transaction Results': 'Транзакции',
                'Round Results': 'Раунд',
                'player games': 'к играм',

                'Drawer': 'Визуализация',
                'Expand All': 'Показать все',
                'Hide All': 'Спрятать все',

                'Apply': 'Применить',
                'Cancel': 'Отмена',
                'From': 'От',
                'To': 'До',
                'Zone': 'Зона',
                'Su': 'Вс',
                'Mo': 'Пн',
                'Tu': 'Вт',
                'We': 'Ср',
                'Th': 'Чт',
                'Fr': 'Пт',
                'Sa': 'Сб',

                'Jan': 'Янв',
                'Feb': 'Фев',
                'Mar': 'Мар',
                'Apr': 'Апр',
                'May': 'Май',
                'Jun': 'Июн',
                'Jul': 'Июл',
                'Aug': 'Авг',
                'Sep': 'Сен',
                'Oct': 'Окт',
                'Nov': 'Ноя',
                'Dec': 'Дек',

                'Today': 'Сегодня',
                'This Week': '1 неделя',
                'This Month': '1 месяц',
                'Last 3 Months': '3 месяца',

                '{size} per page': '{size} на странице'

            },

            zh: {
                'All': '全部游戏',
                'All Currencies': '全部货币',
                'Report Type': '报表类型',
                'Game': '游戏',
                'Currency': '货币',
                'Round': '回合',
                'Rounds': '回合数量',
                'Bet': '赌注',
                'Bets': '总赌注',
                'Win': '奖金',
                'Wins': '总奖金',
                'Profit': '收益',
                'Outcome': '实际输赢',
                'Payout': '赔付率',
                'Payout,%': '赔付率,%',
                'Date': '日期',
                'Bal. Before': '下注前',
                'Bal. After': '下注后',
                'Game mode': '游戏模式',
                'Date Range': '日期',
                'Search': '搜索',
                'Player Games Results': '游戏记录',
                'Total': '总计',
                'Player': '玩家',
                'Project': '专案',
                'Provider': '开发商',
                'Info': '资讯',

                'REAL': '真钱',
                'FUN': '娱乐',
                'GGR': '总收益',
                'NGR': '游戏收益',
                'PROMO': '促销收益',

                'Transaction Results': '交易结果',
                'Round Results': '回合结果',
                'player games': '游戏记录',

                'Drawer': '图像记录',
                'Expand All': '全部展开',
                'Hide All': '全部隐藏',

                'Apply': '确定',
                'Cancel': '取消',
                'From': '从',
                'To': '到',
                'Zone': '时区',
                'Su': '日',
                'Mo': '一',
                'Tu': '二',
                'We': '三',
                'Th': '四',
                'Fr': '五',
                'Sa': '六',

                'Jan': '一月',
                'Feb': '二月',
                'Mar': '三月',
                'Apr': '四月',
                'May': '五月',
                'Jun': '六月',
                'Jul': '七月',
                'Aug': '八月',
                'Sep': '九月',
                'Oct': '十月',
                'Nov': '十一月',
                'Dec': '十二月',

                'Today': '今天',
                'This Week': '本周',
                'This Month': '本月',
                'Last 3 Months': '最近三个月',

                '{size} per page': '每页{size}个'

            },

            'zh-hant': {
                'All': '全部遊戲',
                'All Currencies': '全部貨幣',
                'Report Type': '報表類型',
                'Game': '遊戲',
                'Currency': '貨幣',
                'Round': '回合',
                'Rounds': '回合數量',
                'Bet': '賭注',
                'Bets': '總賭注',
                'Win': '獎金',
                'Wins': '總獎金',
                'Profit': '收益',
                'Outcome': '實際輸贏',
                'Payout': '賠付率',
                'Payout,%': '賠付率,%',
                'Date': '日期',
                'Bal. Before': '下注前',
                'Bal. After': '下注後',
                'Game mode': '遊戲模式',
                'Date Range': '日期',
                'Search': '搜索',
                'Player Games Results': '游戲記錄',
                'Total': '總計',
                'Player': '玩家',
                'Project': '專案',
                'Provider': '開發商',
                'Info': '資訊',

                'REAL': '真錢',
                'FUN': '娛樂',
                'GGR': '總收益',
                'NGR': '遊戲收益',
                'PROMO': '促銷收益',

                'Transaction Results': '交易結果',
                'Round Results': '回合結果',
                'player games': '游戲記錄',

                'Drawer': '圖像記錄',
                'Expand All': '全部展開',
                'Hide All': '全部隱藏',

                'Apply': '確定',
                'Cancel': '取消',
                'From': '從',
                'To': '到',
                'Zone': '時區',
                'Su': '日',
                'Mo': '一',
                'Tu': '二',
                'We': '三',
                'Th': '四',
                'Fr': '五',
                'Sa': '六',

                'Jan': '一月',
                'Feb': '二月',
                'Mar': '三月',
                'Apr': '四月',
                'May': '五月',
                'Jun': '六月',
                'Jul': '七月',
                'Aug': '八月',
                'Sep': '九月',
                'Oct': '十月',
                'Nov': '十一月',
                'Dec': '十二月',

                'Today': '今天',
                'This Week': '本週',
                'This Month': '本月',
                'Last 3 Months': '最近三個月',

                '{size} per page': '每頁{size}個'

            }
        };
        var defaultLang = 'en';
        var lang = '';
        var langs = [];

        module.exports = {
            /**
             * Sets locale. Also sets two secondary locales: two character locale and default locale
             * @param {string} l - main locale
             */
            set: function(l) {
                lang = (l || '').toLowerCase();
                langs = [lang, lang.substr(0, 2), defaultLang];
            },
            /**
             *
             * @param {string} text  - text to localize
             * @param {Object} [locales] - locale dictionary
             * @returns {string}
             */
            translate: function(text, locales) {
                locales = locales || this.locales;

                // search in main and secondary locales
                for (var i = 0; i < langs.length; ++i) {
                    var lang = langs[i];
                    var locale = locales[lang];
                    if (locale && (text in locale)) {
                        return locale[text];
                    }
                }
                return text;
            },
            locales: locales
        };


        /***/
    }),
    /* 1 */
    /***/
    (function(module, exports, __webpack_require__) {

        var Models = __webpack_require__(4);
        var Enums = __webpack_require__(9);

        var Storage = Backbone.Marionette.Object.extend({
            availableCurrencyTypes: null,
            availablePlatforms: null,
            availableGames: null,

            options: {
                api_url: null,
                drawer_url: null
            },

            initialize: function() {
                this.availableGames = new Models.GameCollection();
                this.availableModes = new Backbone.Collection([{
                        mode: Enums.Mode.REAL,
                        title: 'REAL'
                    },
                    {
                        mode: Enums.Mode.FUN,
                        title: 'FUN'
                    }
                ]);
                this.availablePlatforms = new Backbone.Collection([{
                        platform: Enums.Platform.MOBILE,
                        title: 'Mobile'
                    },
                    {
                        platform: Enums.Platform.DESKTOP,
                        title: 'Desktop'
                    }
                ]);
                this.reportTypes = new Backbone.Collection([{
                        uid: Enums.ReportType.GGR,
                        name: 'GGR'
                    },
                    {
                        uid: Enums.ReportType.NGR,
                        name: 'NGR'
                    },
                    {
                        uid: Enums.ReportType.PROMO,
                        name: 'PROMO'
                    }
                ])
            },

            fetchInitialData: function() {
                //return [];
                return [
                    this.availableGames.fetch()
                ];
            }
        });

        module.exports = new Storage(window._PROVIDER);


        /***/
    }),
    /* 2 */
    /***/
    (function(module, exports) {

        var Utils = {
            encodeQueryData: function(data) {
                var val, ret = [];
                for (var d in data) {
                    val = data[d];
                    if (val === undefined || val === null) {
                        val = '';
                    }
                    if (val && val._isAMomentObject) {
                        val = val.format('YYYYMMDDTHHmm');
                    }
                    ret.push(encodeURIComponent(d) + "=" + encodeURIComponent(val));
                }
                return ret.join("&");
            },

            parseQueryData: function(query) {
                var match,
                    pl = /\+/g, // Regex for replacing addition symbol with a space
                    search = /([^&=]+)=?([^&]*)/g,
                    decode = function(s) {
                        return decodeURIComponent(s.replace(pl, " "));
                    };

                var urlParams = {};
                while (match = search.exec(query || ''))
                    urlParams[decode(match[1])] = decode(match[2]);
                return urlParams;
            },

            parseHash: function() {
                var hash = window.location.hash.split('#')[1];
                if (hash) hash = hash.split('?')[0]; // legacy
                return this.parseQueryData(hash);
            },

            getCookie: function(name) {
                var cookieValue = null;
                if (document.cookie && document.cookie !== '') {
                    var cookies = document.cookie.split(';');
                    for (var i = 0; i < cookies.length; i++) {
                        var cookie = jQuery.trim(cookies[i]);
                        // Does this cookie string begin with the name we want?
                        if (cookie.substring(0, name.length + 1) === (name + '=')) {
                            cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                            break;
                        }
                    }
                }
                return cookieValue;
            },

            setCookie: function(name, val) {
                var expiration_date = new Date();
                var cookie_string = '';
                expiration_date.setFullYear(expiration_date.getFullYear() + 1);
                // Build the set-cookie string:
                cookie_string = name + "=" + val + "; path=/; expires=" + expiration_date.toGMTString();
                // Create/update the cookie:
                document.cookie = cookie_string;
            },

            csrfSafeMethod: function(method) {
                // these HTTP methods do not require CSRF protection
                return (/^(GET|HEAD|OPTIONS|TRACE)$/.test(method));
            },

            hereDoc: function(f) {
                return f.toString().
                replace(/^[^\/]+\/\*!?/, '').
                replace(/\*\/[^\/]+$/, '');
            },

            asMoney: function(x) {
                x = x.toFixed(2).toString();
                var pattern = /(-?\d+)(\d{3})/;
                while (pattern.test(x))
                    x = x.replace(pattern, "$1 $2");
                return x;
            },

            asNumber: function(x) {
                x = x.toString();
                var pattern = /(-?\d+)(\d{3})/;
                while (pattern.test(x))
                    x = x.replace(pattern, "$1 $2");
                return x;
            },
            toTZ: function(dt, format, tz) {
                // GMT+/GMT- are actually reversed for POSIX compatibility ...
                if (_.contains(tz, '-')) {
                    tz = tz.replace(/-/g, '+');
                } else if (_.contains(tz, '+')) {
                    tz = tz.replace(/\+/g, '-');
                }
                return dt ? moment.utc(dt).tz(tz).format(format) : '&mdash;';
            }
        };

        module.exports = Utils;


        /***/
    }),
    /* 3 */
    /***/
    (function(module, exports, __webpack_require__) {

        var Utils = __webpack_require__(2);
        var locales = __webpack_require__(0);


        var CustomCell = Marionette.View.extend({
            getAttrValue: function() {
                var column = this.getOption('column');
                return this.model.get(column.get('attr'));
            },
            templateContext: function() {
                return {
                    val: this.getAttrValue()
                }
            }
        });

        var MobileCell = CustomCell.extend({
            displayName: 'Val',
            showCurrency: false,
            currencyAttr: 'currency',
            showSpinner: false,
            template: _.template([
                '<span class="visible-xs-inline"><b><%= displayName %>: </b></span>',
                '<% if (showSpinner) { %>',
                '    <% if (val === null) { %>',
                '        <i class=\'fa fa-spin fa-refresh\'></i>',
                '    <% } else { %>',
                '        <%= val %>',
                '    <% } %>',
                '<% } else { %>',
                '    <%= _.isNull(val) ? "&mdash;" : val %>',
                '<% } %>',
                '',
                '<% if (val && showCurrency) { %>',
                '    <span class="visible-xs-inline"> <%= currencyField %></span>',
                '<% } %>'
            ].join('\n')),
            templateContext: function() {
                var val = this.getAttrValue(),
                    displayName = this.displayName,
                    showSpinner = this.showSpinner,
                    showCurrency = this.showCurrency,
                    currencyField = this.model.get(this.currencyAttr);

                if (val === 'LOCKED' || val === 'EXCEED' || val === '?') {
                    showCurrency = false;
                }
                return {
                    val: val,
                    displayName: locales.translate(displayName),
                    showSpinner: showSpinner,
                    showCurrency: showCurrency,
                    currencyField: currencyField
                }
            }
        });

        var FixedHeaderGridView = MaGrid.GridView.extend({
            collectionEvents: {
                sync: 'invalidateHeader',
                reset: 'invalidateHeader',
                add: 'invalidateHeader',
                remove: 'invalidateHeader'
            },

            initialize: function() {
                MaGrid.GridView.prototype.initialize.apply(this, arguments);

                // bind this to event handlers
                this.onScroll = _.bind(this.onScroll, this);
                this.onResize = _.bind(this.onResize, this);
            },
            onRender: function() {
                MaGrid.GridView.prototype.onRender.apply(this, arguments);

                this.$el.addClass('fixed-header');
                this.$el.addClass('table-stackable');
                this.table = this.$el.find('table');

                var $header_row = this.$el.find('thead tr'),
                    $cloned_row = $header_row.clone();
                $cloned_row.addClass('hidden-row');
                $header_row.after($cloned_row);

                // unsubscribe
                $(window).off('scroll', this.onScroll);
                $(window).off('resize', this.onResize);
                // subscribe. 'this' is already bound to handlers
                $(window).on('scroll', this.onScroll);
                $(window).on('resize', this.onResize);

                setTimeout(_.bind(this.invalidateHeader, this), 1);
            },
            onScroll: function() {
                var fixedHeader = this.$el;
                var offsetTop = fixedHeader.offset().top;
                if (window.scrollY > offsetTop) {
                    fixedHeader.addClass('scrolled');
                } else {
                    fixedHeader.removeClass('scrolled');
                }
            },

            onResize: function() {
                this.invalidateHeader();
            },

            invalidateHeader: function() {
                this.table.find('thead tr:first-child th>div').each(function() {
                    var $div = $(this),
                        $th = $div.parent(),
                        new_width = $th.width(),
                        old_width = $div.width();

                    if (new_width !== old_width) {
                        $div.css({
                            width: new_width
                        })
                    }
                })
            }


        });

        var DateTimeCell = CustomCell.extend({
            dateFormat: 'DD.MM.YYYY HH:mm:ss',
            templateContext: function() {
                return {
                    format: this.getOption('dateFormat'),
                    val: this.getAttrValue()
                };
            },
            template: _.template([
                "<%= val ? moment(val).utc().format(format).replace(' ', '&nbsp;') : '&mdash;' %>"
            ].join(''))
        });

        var PercentCell = CustomCell.extend({
            templateContext: function() {
                var val = this.getAttrValue();
                return {
                    val: (val || 0).toFixed(2)
                };
            },
            template: _.template([
                "<%= val %> %"
            ].join(''))
        });

        var FixedHeaderCellMultilineFooter = FixedHeaderGridView.extend({
            footerColumns: null,
            footerCollection: null,

            initialize: function() {
                FixedHeaderGridView.prototype.initialize.apply(this, arguments);
                this.footerColumns = new Backbone.Collection(this._parseColumns(this.footerColumns));
                this.footerCollection = this.getOption('footerCollection');
            },

            _renderFooter: function() {
                var FooterViewCls = this.getOption('footerView');
                if (!FooterViewCls) return;
                this.showChildView('footer', new FooterViewCls({
                    collection: this.footerCollection,
                    columns: this.footerColumns
                }));
            },

            footerView: MaGrid.BodyView.extend({
                tagName: 'tfoot',
                childViewEventPrefix: 'footer',

                childView: MaGrid.DataRowView,
                emptyView: MaGrid.EmptyRowView
            })
        });


        module.exports = {
            CustomCell: CustomCell,
            MobileCell: MobileCell,

            DateTimeCell: DateTimeCell,
            FixedHeaderGridView: FixedHeaderGridView,
            FixedHeaderCellMultilineFooter: FixedHeaderCellMultilineFooter,

            PercentCell: PercentCell
        };

        /***/
    }),
    /* 4 */
    /***/
    (function(module, exports, __webpack_require__) {

        var Model = __webpack_require__(16);

        var TotalsCollectionNoExceed = Model.TotalsCollection.extend({
            processSource: function(source) {
                return source.filter(function(model) {
                    return model.get('status') === 'OK';
                });
            }
        });

        var PageableCollection = Backbone.PageableCollection.extend({
            parseRecords: function(resp) {
                return resp.items;
            }
        });

        var GameModel = Backbone.Model.extend({
            idAttribute: 'uid'
        });

        var GameCollection = Backbone.Collection.extend({
            url: 'game/list',
            model: GameModel,

            comparator: function(modelA, modelB) {
                return modelA.get('name').localeCompare(modelB.get('name'));
            }
        });

        var CurrencyModel = Backbone.Model.extend();

        var CurrencyCollection = Backbone.Collection.extend({
            url: 'currency/list',
            model: CurrencyModel
        });

        var PlayerGameModel = Backbone.Model.extend({
            idAttribute: "_id",
            comparator: "game_id",
            url: 'playergame/aggregate',
            defaults: {
                rounds: null,
                bets: null,
                wins: null,
                profit: null,
                outcome: null,
                payout: null
            },
            getQueryParams: function() {
                return {
                    game_id: this.get('game_id'),
                    currency: this.get('currency'),
                    mode: this.get('mode')
                }
            },
            parse: function(resp) {
                if (resp.rounds == null) {
                    _.defer(_.bind(this.loadExtraData, this));
                }
                this.set('_id', [this.get('game_id'), this.get('mode'), this.get('currency')].join('_'));
                return Backbone.Model.prototype.parse.apply(this, arguments);

            },
            loadExtraData: function() {
                var data = _.extend({}, this.collection.lastFilters, this.getQueryParams());
                this.fetch({
                    data: data
                })
            }
        });

        var PlayerInfoModel = Backbone.Model.extend({
            url: 'player/get_info',
            defaults: {
                player_id: null,
                project_name: null,
                provider_name: null,
                brand: null
            },
        });

        function calculatePayout(win, bet) {
            if (bet === null || win === null) {
                // not loaded yet - show spinner
                return null;
            } else if (!bet || bet.isEqualTo(0)) {
                // zero bets - hide payout
                return undefined;
            } else {
                return win
                    .shiftedBy(2)
                    .dividedBy(bet)
                    .toFixed(2, BigNumber.ROUND_DOWN);
            }
        }

        // collection without pagination
        var PlayerGameCollection = Model.QueryParamsCollection.extend({
            url: 'playergame/list',
            model: PlayerGameModel
        });

        var PlayerGameTotalsCollection = Model.TotalsCollection.extend({
            groupBy: 'currency',

            nullIfNoValues: true,
            nullIfHasNulls: true,

            processSource: function(source) {
                return source.filter(function(model) {
                    return model.get('rounds') * 1 > 0 ||
                        model.get('bets') * 1 > 0 ||
                        model.get('wins') * 1 > 0;
                });
            },

            fields: [{
                name: 'rounds',
                value: 'sum'
            }, {
                name: 'bets',
                value: 'sum'
            }, {
                name: 'wins',
                value: 'sum'
            }, {
                name: 'profit',
                value: 'sum'
            }, {
                name: 'outcome',
                value: 'sum'
            }, {
                name: 'payout',
                value: function(models) {
                    var wins = this.sum(models, 'wins'),
                        bets = this.sum(models, 'bets');
                    return calculatePayout(wins, bets);
                }
            }]
        });


        var TransactionModel = Backbone.Model.extend({
            defaults: {
                is_bonus: null
            }
        });

        var TransactionCollection = MaGrid.SequentCollection.extend({
            url: 'transaction/list',
            model: TransactionModel
        });

        var TransactionTotalsCollection = TotalsCollectionNoExceed.extend({
            groupBy: 'currency',

            nullIfNoValues: true,
            nullIfHasNulls: false,

            fields: [{
                name: 'bet',
                value: 'sum'
            }, {
                name: 'win',
                value: 'sum'
            }, {
                name: 'profit',
                value: 'sum'
            }, {
                name: 'outcome',
                value: 'sum'
            }, {
                name: 'round_id',
                value: 'countUniq'
            }]
        });

        var RoundTransactionCollection = Model.QueryParamsCollection.extend({
            url: 'transaction/list',
            model: TransactionModel
        });

        var RoundTransactionTotalsCollection = TotalsCollectionNoExceed.extend({
            groupBy: 'currency',

            nullIfNoValues: true,
            nullIfHasNulls: false,

            fields: [{
                name: 'bets',
                source: 'bet',
                value: 'sum'
            }, {
                name: 'wins',
                source: 'win',
                value: 'sum'
            }, {
                name: 'profits',
                value: 'sum'
            }, {
                name: 'outcome',
                value: 'sum'
            }, {
                name: 'rounds',
                source: 'round_id',
                value: 'countUniq'
            }, {
                name: 'payout',
                value: function(models) {
                    var win = this.sum(models, 'win'),
                        bet = this.sum(models, 'bet');
                    return calculatePayout(win, bet);
                }
            }]
        });

        module.exports = {
            PlayerGameModel: PlayerGameModel,
            PlayerGameCollection: PlayerGameCollection,
            PlayerGameTotalsCollection: PlayerGameTotalsCollection,
            TransactionCollection: TransactionCollection,
            TransactionTotalsCollection: TransactionTotalsCollection,
            RoundTransactionCollection: RoundTransactionCollection,
            RoundTransactionTotalsCollection: RoundTransactionTotalsCollection,
            GameCollection: GameCollection,
            CurrencyCollection: CurrencyCollection,
            PlayerInfoModel: PlayerInfoModel
        };


        /***/
    }),
    /* 5 */
    /***/
    (function(module, exports, __webpack_require__) {

        var Enums = __webpack_require__(9);

        var defaults = {
            // all
            header: '1',
            totals: '1',
            // info is used by transactions + round, but needs to be here, so we can set in even on playergames
            info: '0',
            tz: moment().utcOffset(),
            lang: 'en',
            report_type: Enums.ReportType.GGR,

            // playergames
            realtime: '1',

            // transactions
            currency: '',
            per_page: 100,
            exceeds: '1',

            // round
            balance: '0',

            // playergames + transactions
            game_id: '',
            start_date: '',
            end_date: '',
            mode: Enums.Mode.REAL,

            // playergames + round

            // transactions + round
            round_id: '',
        };

        module.exports = defaults;


        /***/
    }),
    /* 6 */
    /***/
    (function(module, exports) {

        var FormBehavior = Mn.Behavior.extend({
            defaults: {
                submitEvent: 'form:submit',
                errorCls: 'has-error',
                form: 'form',
                submitBtn: '.submit-btn',
                fieldGroups: '.form-group',
                errorGroups: '.field-error',
                nonFieldError: '.form-error',
                validators: {}
            },

            ui: function() {
                return {
                    form: this.getOption('form'),
                    submitBtn: this.getOption('submitBtn'),
                    fieldGroups: this.getOption('fieldGroups'),
                    errorGroups: this.getOption('errorGroups'),
                    nonFieldError: this.getOption('nonFieldError')
                }
            },

            events: {
                'click @ui.submitBtn': 'onSubmitBtnClick',
                'keypress @ui.fieldGroups input': 'onKeyPress'
            },

            onKeyPress: function(e) {
                if (e.keyCode === 13) {
                    // enter pressed
                    e.stopImmediatePropagation();
                    e.preventDefault();
                    this.onSubmitBtnClick(e);
                }
            },

            getFormData: function() {
                return this.ui.form.serializeObject();
            },

            validate: function(data) {
                var validators = this.getOption('validators');
                var validator, field, msg, errors = {};

                for (field in validators) {
                    validator = validators[field];
                    msg = validator(data[field], data);
                    if (msg) {
                        errors[field] = [msg];
                    }
                }
                return errors;
            },

            showErrors: function(errors) {
                for (var field in errors) {
                    if (field === '__all__') {
                        this.ui.nonFieldError.html(errors[field].join('<br>'));
                        continue;
                    }

                    var input_selector = '[name="' + field + '"]';

                    var $field_group = this.ui.fieldGroups.has(input_selector);
                    $field_group.addClass(this.getOption('errorCls'));

                    var $error_container = $field_group.find(this.ui.errorGroups);
                    $error_container.html(errors[field].join('<br>'));
                }
            },

            clearErrors: function() {
                this.ui.fieldGroups.removeClass(this.getOption('errorCls'));
                this.ui.errorGroups.html('');
                this.ui.nonFieldError.html('');
            },

            onSubmitBtnClick: function(e) {
                if ($(e.currentTarget).attr('disabled')) {
                    return;
                }
                this.clearErrors();

                var data = this.getFormData();
                var errors = this.validate(data);

                if (!_.isEmpty(errors)) {
                    this.showErrors(errors);
                } else {
                    this.view.triggerMethod(this.getOption('submitEvent'), data);
                }
            }
        });


        module.exports = {
            FormBehavior: FormBehavior,
            required: function(val) {
                if (!val) {
                    return 'This field is required';
                }
            },
            json: function(val) {
                try {
                    JSON.parse(val);
                } catch (e) {
                    return 'Valid JSON is required';
                }
            }

        };

        /***/
    }),
    /* 7 */
    /***/
    (function(module, exports, __webpack_require__) {

        var locales = __webpack_require__(0);
        var GridUtils = __webpack_require__(3);

        var Cell = GridUtils.CustomCell.extend({
            showSpinner: true,
            template: _.template([
                '<% if (showSpinner) { %>',
                '    <% if (val === null) { %>',
                '        <i class=\'fa fa-spin fa-refresh\'></i>',
                '    <% } else { %>',
                '        <%= val %>',
                '    <% } %>',
                '<% } else { %>',
                '    <%= _.isNull(val) ? "&mdash;" : val %>',
                '<% } %>'
            ].join('\n')),
            templateContext: function() {
                var val = this.getAttrValue(),
                    showSpinner = this.showSpinner;

                return {
                    val: val,
                    showSpinner: showSpinner
                }
            }
        });

        var TotalsGridView = MaGrid.GridView.extend({
            columns: [{
                attr: 'currency',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Currency')
                    }
                }),
                className: 'text-center',
                cell: Cell
            }, {
                attr: 'rounds',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Rounds')
                    }
                }),
                className: 'text-right',
                cell: Cell
            }, {
                attr: 'bets',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Bets')
                    }
                }),
                className: 'text-right',
                cell: Cell
            }, {
                _id: 'wins',
                attr: 'wins',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Wins')
                    }
                }),
                className: 'text-right',
                cell: Cell
            }, {
                attr: 'outcome',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Outcome')
                    }
                }),
                className: 'text-right',
                cell: Cell
            }, {
                attr: 'payout',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Payout,%')
                    }
                }),
                className: 'text-right',
                cell: Cell
            }],
            footerView: null,
            sizerView: null,
            paginatorView: null
        });

        module.exports = TotalsGridView;


        /***/
    }),
    /* 8 */
    /***/
    (function(module, exports, __webpack_require__) {

        var Utils = __webpack_require__(2);
        var Network = {
            requiredHashParams: function() {
                var hash_params = Utils.parseHash();
                var params = {};
                if (hash_params.player_id != null) {
                    params.player_id = hash_params.player_id
                }
                if (hash_params.brand != null) {
                    params.brand = hash_params.brand
                }
                return params;
            },
            requiredApiParams: function() {
                var hash_params = Utils.parseHash();
                return {
                    'player_id': hash_params.player_id,
                    'brand': hash_params.brand
                };
            },
            setup: function(base) {
                var sync = function(method, model, options) {
                    var params = _.extend({
                        type: 'POST',
                        dataType: 'json',
                        contentType: 'application/json',
                        processData: false,
                        emulateHTTP: false,
                        emulateJSON: false,
                        data: {}
                    }, options);

                    params.data = _.extend(Network.requiredApiParams(), params.data);
                    /*params.data = _.omit(params.data, function (value, key, obj) {
                        return value === '';
                    });*/
                    model.lastFilters = params.data || params.attrs || model.toJSON(params);

                    var data = (params.data || params.attrs || model.toJSON(params) || '');
                    params.data = JSON.stringify(data) || undefined;
                    params.url = params.url || _.result(model, 'url') || _.noop();
                    params.url = base.url + params.url;

                    var error = params.error,
                        success = params.success;
                    params.error = function(xhr, textStatus, errorThrown) {
                        params.textStatus = textStatus;
                        params.errorThrown = errorThrown;
                        if (error) {
                            error.call(params.context, xhr, textStatus, errorThrown);
                        }
                        if (base.error) {
                            base.error.call(params.context, xhr, method, model, options);
                        }
                    };

                    params.success = function(xhr, textStatus, errorThrown) {
                        params.textStatus = textStatus;
                        params.errorThrown = errorThrown;
                        if (success) {
                            success.call(params.context, xhr, textStatus, errorThrown);
                        }
                        if (base.success) {
                            base.success.call(params.context, xhr, method, model, options);
                        }
                    };

                    var xhr = params.xhr = Backbone.ajax(params);
                    model.trigger('request', model, xhr, params);
                    return xhr;
                };
                return sync;
            }
        };
        module.exports = Network;


        /***/
    }),
    /* 9 */
    /***/
    (function(module, exports) {

        var Mode = {
            REAL: 'REAL',
            FUN: 'FUN'
        };

        var Platform = {
            MOBILE: 'mobile',
            DESKTOP: 'desktop'
        };

        var ReportType = {
            GGR: 'GGR',
            NGR: 'NGR',
            PROMO: 'PRM'
        };

        module.exports = {
            Mode: Mode,
            Platform: Platform,
            ReportType: ReportType
        };


        /***/
    }),
    /* 10 */
    /***/
    (function(module, exports) {

        var NestedViewBehavior = Mn.Behavior.extend({
            defaults: {
                backButtonLinkOptionName: 'go_back',
                backButton: '.goback'
            },
            ui: function() {
                return {
                    backButton: this.getOption('backButton')
                }
            },
            events: {
                'click @ui.backButton': 'onGoBackClick'
            },
            initialize: function() {
                this.options.backButtonLink = this.view.getOption(this.getOption('backButtonLinkOptionName'));
            },
            onRender: function() {
                if (this.getOption('backButtonLink')) {
                    this.ui.backButton.show();
                } else {
                    this.ui.backButton.hide();
                }
            },
            onGoBackClick: function() {
                Backbone.history.navigate(this.getOption('backButtonLink'), {
                    trigger: true
                });
            }
        });

        module.exports = {
            NestedViewBehavior: NestedViewBehavior
        };


        /***/
    }),
    /* 11 */
    /***/
    (function(module, exports, __webpack_require__) {

        var Storage = __webpack_require__(1);
        var locales = __webpack_require__(0);
        var Queue = __webpack_require__(22);

        var DrawerCell = Marionette.View.extend({
            className: 'drawer-cell',
            template: _.template([
                '<div class="drawer-cell-section drawer-cell-expand-btn btn btn-xs btn-default',
                '    <% if (!expandable) { %>disabled<% } %>">',
                '    <%- translate("Drawer") %>',
                '</div>',
            ].join('')),
            templateContext: function() {
                return {
                    translate: locales.translate.bind(locales),
                    expandable: this.expandable,
                    src: this.src
                }
            },
            iframeTemplate: _.template([
                '<div class="drawer-cell-section">',
                '    <iframe src="<%- src %>"></iframe>',
                '</div>',
                '<div class="drawer-cell-section text-center">',
                '    <div class="btn-group">',
                '        <a class="btn btn-xs btn-default" href="<%- src %>" target="_blank"><i class="fa fa-clone"></i></a>',
                '        <div class="drawer-cell-zoom-btn btn btn-xs btn-default"><i class="fa"></i></div>',
                '    </div>',
                '</div>',
            ].join('')),
            ui: {
                expandButton: '.drawer-cell-expand-btn',
                zoomButton: '.drawer-cell-zoom-btn',
            },
            triggers: {
                'click @ui.expandButton': 'toggle',
                'click @ui.zoomButton': 'zoom'
            },
            initialize: function() {
                this.expanded = false;
                this.zoomed = false;
                this.src = this.getSrc();
                this.expandable = !!this.src;
            },
            onBeforeRender: function() {
                this.$wrapper = $(this.iframeTemplate({
                    src: this.src
                }));
                this.$iframe = this.$wrapper.find('iframe');
                this.$zoomIcon = this.$wrapper.find('.drawer-cell-zoom-btn .fa');
            },
            onAttach: function() {
                this.expanded ? this.expand() : this.collapse();
                this.zoomed ? this.zoomIn() : this.zoomOut();
            },
            onBeforeDestroy: function() {
                Queue.q.remove(this.$iframe);
            },
            getSrc: function() {
                if (this.model.get('type') === 'ROLLBACK' || this.model.get('status') !== 'OK') {
                    // do not render drawer link for rollbacks and exceeds
                    return '';
                }
                return Storage.options.drawer_url + this.model.get('transaction_id');
            },
            onToggle: function() {
                if (this.expanded) {
                    this.collapse();
                } else {
                    this.expand();
                }
            },
            onZoom: function() {
                if (this.zoomed) {
                    this.zoomOut();
                } else {
                    this.zoomIn();
                }
            },

            collapse: function() {
                this.expanded = false;
                this.ui.expandButton.removeClass('active');
                this.$el.addClass('drawer-collapsed');
                this.$el.removeClass('drawer-expanded');

                Queue.q.remove(this.$iframe);
                this.$wrapper.remove();

                this.zoomOut();
            },

            expand: function() {
                if (!this.expandable) {
                    return;
                }
                this.expanded = true;
                this.ui.expandButton.addClass('active');
                this.$el.addClass('drawer-expanded');
                this.$el.removeClass('drawer-collapsed');

                Queue.q.add(this.$iframe, function() {
                    this.$el.append(this.$wrapper);
                }, this);
            },

            zoomIn: function() {
                this.zoomed = true;
                this.$zoomIcon.addClass('fa-search-minus');
                this.$zoomIcon.removeClass('fa-search-plus');
                this.$el.addClass('drawer-lg');
                this.$el.removeClass('drawer-sm');
            },

            zoomOut: function() {
                this.zoomed = false;
                this.$zoomIcon.addClass('fa-search-plus');
                this.$zoomIcon.removeClass('fa-search-minus');
                this.$el.addClass('drawer-sm');
                this.$el.removeClass('drawer-lg');
            }
        });

        var DrawerHeader = Marionette.View.extend({
            template: _.template([
                '<div class="drawer-header-expand-btn btn btn-default btn-xs"',
                '    ><%- translate(expanded ? "Hide All" : "Expand All") %></div'
            ].join('')),
            templateContext: function() {
                return {
                    expanded: this.expanded,
                    translate: locales.translate.bind(locales)
                }
            },
            ui: {
                expandButton: '.drawer-header-expand-btn'
            },
            triggers: {
                'click @ui.expandButton': 'toggle'
            },
            collectionEvents: {
                'sync': 'onSync'
            },
            initialize: function() {
                this.expanded = false;
            },
            onSync: function() {
                this.expanded = false;
                if (this.isRendered()) {
                    this.render();
                }
            },
            onToggle: function() {
                this.expanded = !this.expanded;
                this.render();
                if (this.expanded) {
                    this.triggerMethod('expand_all');
                } else {
                    this.triggerMethod('collapse_all');
                }
            }
        });

        module.exports = {
            DrawerCell: DrawerCell,
            DrawerHeader: DrawerHeader
        };


        /***/
    }),
    /* 12 */
    /***/
    (function(module, exports, __webpack_require__) {

        var locales = __webpack_require__(0);
        var GridUtils = __webpack_require__(3);

        var Cell = GridUtils.CustomCell.extend({
            showSpinner: false,
            template: _.template([
                '<% if (!_.isNull(val)) { %>',
                '<%- val %>',
                '<% } else if (showSpinner) { %>',
                '<i class="fa fa-spin fa-refresh"></i>',
                '<% } else { %>',
                '\u2014',
                '<% } %>'
            ].join('\n')),
            templateContext: function() {
                return {
                    val: this.getAttrValue(),
                    showSpinner: this.showSpinner
                }
            }
        });

        var PlayerInfoGridView = MaGrid.GridView.extend({
            columns: [{
                attr: 'player_id',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Player')
                    }
                }),
                className: 'text-center',
                cell: Cell
            }, {
                attr: 'project_name',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Project')
                    }
                }),
                className: 'text-center',
                cell: Cell.extend({
                    template: _.template([
                        '<% if (brand !== "*") { %>',
                        '<%- project_name %> - <%- brand %>',
                        '<% } else { %>',
                        '<%- project_name %>',
                        '<% } %>'
                    ].join(''))
                })
            }, {
                attr: 'provider_name',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Provider')
                    }
                }),
                className: 'text-center',
                cell: Cell
            }],
            footerView: null,
            sizerView: null,
            paginatorView: null
        });

        module.exports = PlayerInfoGridView;


        /***/
    }),
    /* 13 */
    /***/
    (function(module, exports, __webpack_require__) {

        __webpack_require__(14);

        var Network = __webpack_require__(8);
        var Controller = __webpack_require__(15);
        var Router = __webpack_require__(27);
        var Storage = __webpack_require__(1);

        var Layout = __webpack_require__(28);
        var ErrorModalView = __webpack_require__(30);
        var locales = __webpack_require__(0);
        var Utils = __webpack_require__(2);

        Backbone.sync = Network.setup({
            url: Storage.options.api_url,
            error: function(xhr, method, model, options) {
                var errorView = new ErrorModalView({
                    deferred: xhr
                });
                errorView.on('repeat:request', function() {
                    Backbone.sync(method, model, options);
                });
                errorView.render();
            }
        });


        var Application = Backbone.Marionette.Application.extend({
            region: 'body',

            onStart: function() {
                this.layout = new Layout();
                this.showView(this.layout);
                this.controller = new Controller({
                    layout: this.layout
                });
                this.router = new Router({
                    controller: this.controller
                });
                Backbone.history.start();
            }
        });

        var app = new Application();
        app.start();


        /***/
    }),
    /* 14 */
    /***/
    (function(module, exports) {

        $.fn.serializeObject = function() {
            var o = {};
            var $disabled = $(this).find(':disabled').removeAttr('disabled');
            var a = this.serializeArray();
            $.each(a, function() {
                if (o[this.name] !== undefined) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }
            });

            var checkboxes = this.find('input[type="checkbox"]');
            $.each(checkboxes, function() {
                if (this.name) {
                    if (o[this.name] === 'true' ||
                        o[this.name] === 'on' ||
                        o[this.name] === '1'
                    ) {
                        o[this.name] = true;
                    }
                    if (o[this.name] === 'false' ||
                        o[this.name] === 'off' ||
                        o[this.name] === '0'
                    ) {
                        o[this.name] = false;
                    }
                    if (o[this.name] === undefined) {
                        o[this.name] = false;
                    }
                }
            });
            var radioboxes = this.find('input[type="radio"]');
            $.each(radioboxes, function() {
                if (this.name) {
                    if (o[this.name] === 'true') {
                        o[this.name] = true;
                    }
                    if (o[this.name] === 'false') {
                        o[this.name] = false;
                    }
                }
            });
            $disabled.attr('disabled', 'disabled');
            return o;
        };


        _.extend(Backbone.History.prototype, {
            /* https://github.com/jashkenas/backbone/issues/3399 */
            getHash: function(window) {
                var match = (window || this).location.href.match(/#(.*)$/);
                return match ? this.decodeFragment(match[1].replace(/#.*$/, '')) : '';
            }
        });


        MaGrid.GridView.prototype.className = 'mg-container clearfix';
        MaGrid.GridView.prototype.template = _.template([
            '<div class="mg-table box-body no-padding">',
            '    <table class="table table-condensed table-hover">',
            '       <thead data-region="header"></thead>',
            '       <tbody data-region="body"></tbody>',
            '       <tfoot data-region="footer"></tfoot>',
            '   </table>',
            '</div>',
            '<div class="box-footer">',
            '   <div class="mg-sizer pull-left" data-region="sizer"></div>',
            '   <div class="mg-paginator pull-right" data-region="paginator"></div>',
            '</div>'
        ].join(''));

        MaGrid.PaginatorView.prototype.currentPageClass = 'active';
        MaGrid.PaginatorView.prototype.disabledPageClass = 'disabled';

        MaGrid.PaginatorView.prototype.template = _.template([
            '<ul class="pagination pagination-sm no-margin pull-right">',
            '<% for(i in pages) { %>',
            '<li class="<%= pages[i].className %>" >',
            '   <a href="#" title="<%= pages[i].pageNum %>" ',
            '      <% if(!pages[i].isDisabled && !pages[i].isCurrent) { %>data-page="<%= pages[i].pageNum %>"<% } %>>',
            '       <%= pages[i].label %>',
            '   </a>',
            '</li>',
            '<% } %>',
            '</ul>'
        ].join('\n'));

        Marionette.View.prototype.getOptionOrNull = function(attr) {
            var val = this.getOption(attr);
            if (val === '') {
                return null;
            }
            return val;
        };

        $(document).on('click', function(e) {
            $('[data-toggle="popover"],[data-original-title]').each(function() {
                //the 'is' for buttons that trigger popups
                //the 'has' for icons within a button that triggers a popup
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                    (($(this).popover('hide').data('bs.popover') || {}).inState || {}).click = false // fix for BS 3.3.6
                }

            });
        });

        /***/
    }),
    /* 15 */
    /***/
    (function(module, exports, __webpack_require__) {

        var Network = __webpack_require__(8);
        var Storage = __webpack_require__(1);
        var Utils = __webpack_require__(2);
        var PlayerGamesView = __webpack_require__(17);
        var TransactionsView = __webpack_require__(20);
        var RoundView = __webpack_require__(24);


        var Controller = Marionette.Object.extend({
            lastPlayerGamesState: '',

            initialize: function(options) {
                this.layout = options.layout;
                this.listenTo(this.layout, 'state:changed', this.onStateChanged);
            },

            onStateChanged: function(state) {
                var params = Network.requiredHashParams();
                _.extend(params, state);

                var new_hash = Utils.encodeQueryData(params);
                if (!params.show) {
                    this.lastPlayerGamesState = new_hash;
                }
                Backbone.history.navigate(new_hash, {
                    trigger: false,
                    replace: true
                });
            },

            index: function(hash) {
                var params = Utils.parseQueryData(hash);
                params.go_back = this.lastPlayerGamesState;

                this.layout.showOverlay();
                $.when.apply($, Storage.fetchInitialData()).then(_.bind(function() {
                    if (params.show === 'transactions') {
                        this.layout.show(new TransactionsView(params));
                    } else if (params.show === 'round') {
                        this.layout.show(new RoundView(params));
                    } else {
                        this.lastPlayerGamesState = hash;
                        this.layout.show(new PlayerGamesView(params));
                    }
                    this.layout.hideOverlay();
                }, this));
            }
        });

        module.exports = Controller;


        /***/
    }),
    /* 16 */
    /***/
    (function(module, exports) {

        var QueryParamsCollection = Backbone.Collection.extend({
            queryParams: {},

            initialize: function(models, options) {
                Backbone.Collection.prototype.initialize.apply(this, arguments);
                options = options || {};
                this.queryParams = _.extend({}, this.queryParams, options.queryParams);
            },

            fetch: function(options) {
                options = options || {};

                var data = options.data || {};

                var url = options.url || this.url || '';
                if (_.isFunction(url)) url = url.call(this);

                options.url = url;
                options.data = data;

                _.each(this.queryParams, function(value, k) {
                    if (_.isFunction(value)) value = value.call(this);
                    if (value == null) {
                        delete data[k];
                    } else {
                        data[k] = value;
                    }
                }, this);

                return Backbone.Collection.prototype.fetch.call(this, options);
            },

            parse: function(resp) {
                return resp.items;
            }
        });

        var TotalsCollection = Backbone.Collection.extend({
            // source collection
            source: null,
            // field collection will be grouped by
            groupBy: null,
            // show null if no values
            nullIfNoValues: false,
            // show null if some values are null
            nullIfHasNulls: false,
            // result fields
            /** @type {{name: string, source: string, value: string|Function}[]} */
            fields: [],

            initialize: function(models, options) {
                options = options || {};
                _.extend(this, options);

                _.forEach(['add', 'remove', 'reset', 'sync'], function(event) {
                    this.source.on(event, _.bind(this._update, this))
                }, this);
            },

            hasNulls: function(field, models) {
                return _.some(models, function(model) {
                    return model.get(field) === null;
                })
            },

            sum: function(models, field) {
                if (this.nullIfNoValues && !models.length) return null;
                if (this.nullIfHasNulls && this.hasNulls(field, models)) return null;

                return _.reduce(models, function(agg, model) {
                    var value = BigNumber(model.get(field));
                    if (value.isNaN()) return agg;
                    return agg.plus(value);
                }, BigNumber(0));
            },

            count: function(models, field) {
                if (this.nullIfNoValues && !models.length) return null;
                if (this.nullIfHasNulls && this.hasNulls(field, models)) return null;

                return _.reduce(models, function(agg, model) {
                    return model.has(field) ? (agg + 1) : agg;
                }, 0);
            },

            countUniq: function(models, field) {
                if (this.nullIfNoValues && !models.length) return null;
                if (this.nullIfHasNulls && this.hasNulls(field, models)) return null;

                return _.uniq(models, function(model) {
                    return model.get(field);
                }).length;
            },

            processSource: function(source) {
                return source;
            },

            _update: function() {
                var source = this.processSource(this.source);
                if (!(source instanceof Backbone.Collection)) {
                    source = new Backbone.Collection(source);
                }
                var groupBy = this.groupBy;
                var groups = source.groupBy(groupBy);

                var models = _.map(groups, function(group, groupName) {
                    var model = {};
                    model[groupBy] = groupName;

                    _.forEach(this.fields, function(field) {
                        var fieldName = field.name || field.source,
                            sourceField = field.source || field.name,
                            value = field.value;

                        if (_.isFunction(value)) {
                            model[fieldName] = value.call(this, group, sourceField);
                        } else {
                            model[fieldName] = this[value].call(this, group, sourceField);
                        }
                    }, this);

                    return model;
                }, this);

                this.reset(models);
            }
        });

        module.exports = {
            QueryParamsCollection: QueryParamsCollection,
            TotalsCollection: TotalsCollection
        };


        /***/
    }),
    /* 17 */
    /***/
    (function(module, exports, __webpack_require__) {

        var Storage = __webpack_require__(1);
        var Models = __webpack_require__(4);
        var defaults = __webpack_require__(5);
        var FormUtils = __webpack_require__(6);
        var locales = __webpack_require__(0);
        var Utils = __webpack_require__(2);

        var PlayerGamesGridView = __webpack_require__(18);
        var tpl = __webpack_require__(19);
        var TotalsGridView = __webpack_require__(7);


        var PlayerGamesView = Backbone.Marionette.View.extend({
            options: {
                game_id: defaults.game_id,
                tz: defaults.tz,
                mode: defaults.mode,
                report_type: defaults.report_type,
                start_date: defaults.start_date,
                end_date: defaults.end_date,
                header: defaults.header,
                totals: defaults.totals,
                info: defaults.info,
                realtime: defaults.realtime,
                lang: defaults.lang
            },
            template: tpl,
            templateContext: function() {
                return {
                    available_modes: Storage.availableModes.toJSON(),
                    report_types: Storage.reportTypes.toJSON(),
                    available_games: Storage.availableGames.toJSON(),
                    mode: this.getOption('mode'),
                    report_type: this.getOption('report_type'),
                    game_id: this.getOption('game_id'),
                    header: this.getOption('header'),
                    totals: this.getOption('totals'),
                    translate: locales.translate.bind(locales)
                }
            },
            ui: {
                listRegion: '.app-list-region',
                totalsRegion: '.app-totals-region',
                totalsWidget: '.app-totals-widget',
                overlay: '.overlay'
            },
            regions: {
                listRegion: '@ui.listRegion',
                totalsRegion: '@ui.totalsRegion'
            },
            collectionEvents: {
                update: 'onCollectionUpdate',
                request: 'onCollectionRequest',
                sync: 'onCollectionSync',
                error: 'onCollectionError'
            },
            childViewEvents: {
                'magrid:row:cell:show:transactions': 'onShowTransactions'
            },

            behaviors: [{
                behaviorClass: FormUtils.FormBehavior,
                submitEvent: 'search:form:submit'
            }],

            initialize: function() {
                locales.set(this.options.lang);
                this.options.tz = parseInt(this.options.tz);
                if (_.isNaN(this.options.tz)) {
                    this.options.tz = defaults.tz;
                }
                if (this.options.start_date) {
                    this.options.start_date = moment(this.options.start_date, 'YYYYMMDDTHHmm');
                    this.options.start_date = this.options.start_date.utcOffset(this.options.tz, true);
                } else {
                    this.options.start_date = null;
                }
                if (this.options.end_date) {
                    this.options.end_date = moment(this.options.end_date, 'YYYYMMDDTHHmm');
                    this.options.end_date = this.options.end_date.utcOffset(this.options.tz, true);
                } else {
                    this.options.end_date = null;
                }

                this.visibleCollection = new Backbone.Collection();
                this.loadingModel = new Backbone.Model();

                this.collection = new Models.PlayerGameCollection([], {
                    queryParams: {
                        game_id: _.bind(this.getOptionOrNull, this, 'game_id'),
                        mode: _.bind(this.getOption, this, 'mode'),
                        report_type: _.bind(this.getOptionOrNull, this, 'report_type'),
                        start_date: _.bind(this._encodeDate, this, 'start_date'),
                        end_date: _.bind(this._encodeDate, this, 'end_date'),
                        realtime: _.bind(this._getIsRealtime, this)
                    }
                });

                this.totalsCollection = new Models.PlayerGameTotalsCollection([], {
                    source: this.collection
                });
            },

            _getIsRealtime: function() {
                return !!+this.getOption('realtime');
            },

            renderGrid: function() {
                if (!this.getChildView('listRegion')) {
                    var gridView = new PlayerGamesGridView({
                        collection: this.visibleCollection,
                        footerCollection: this.totalsCollection
                    });
                    this.showChildView('listRegion', gridView);
                }
                this.collection.fetch();
            },

            renderTotalsGrid: function() {
                if (!this.getChildView('totalsRegion')) {
                    var gridView = new TotalsGridView({
                        collection: this.totalsCollection
                    });
                    this.showChildView('totalsRegion', gridView);
                    this.hideTotals();
                }
            },

            hideTotals: function() {
                this.ui.totalsWidget.hide();
            },

            showTotals: function() {
                this.ui.totalsWidget.show();
            },

            onRender: function() {
                setTimeout(_.bind(function() {
                    this.$('#gapicker').gapicker({
                        allowNulls: true,
                        showTime: true,
                        startDate: this.getOption('start_date'),
                        endDate: this.getOption('end_date'),
                        utcOffset: this.getOption('tz'),
                        calendarsCount: 2,
                        position: 'bottom center',
                        format: 'YYYY-MM-DD HH:mm:ss',
                        applyLabel: locales.translate('Apply'),
                        cancelLabel: locales.translate('Cancel'),
                        fromLabel: locales.translate('From') + ': ',
                        toLabel: locales.translate('To') + ': ',
                        offsetLabel: locales.translate('Zone') + ': ',
                        daysOfWeek: [
                            locales.translate('Su'),
                            locales.translate('Mo'),
                            locales.translate('Tu'),
                            locales.translate('We'),
                            locales.translate('Th'),
                            locales.translate('Fr'),
                            locales.translate('Sa')
                        ],

                        monthNames: [
                            locales.translate('Jan'),
                            locales.translate('Feb'),
                            locales.translate('Mar'),
                            locales.translate('Apr'),
                            locales.translate('May'),
                            locales.translate('Jun'),
                            locales.translate('Jul'),
                            locales.translate('Aug'),
                            locales.translate('Sep'),
                            locales.translate('Oct'),
                            locales.translate('Nov'),
                            locales.translate('Dec')
                        ],
                        customRanges: [
                            ['1d', locales.translate('Today')],
                            ['1w', locales.translate('This Week')],
                            ['1m', locales.translate('This Month')],
                            ['3m', locales.translate('Last 3 Months')]

                        ],
                        callback: _.bind(function(from, to, tz) {
                            this.options.start_date = from;
                            this.options.end_date = to;
                            this.options.tz = tz;

                        }, this)
                    });
                }, this), 1);

                this.renderGrid();
                this.renderTotalsGrid();
            },

            resetVisibleCollection: function() {
                this.modelRequests = 0;
                this.visibleCollection.reset();
            },

            updateVisibleCollection: function(inc) {
                this.modelRequests += inc;
                var models_to_show = this.collection.filter(function(m) {
                    return m.get('rounds') * 1 > 0 ||
                        m.get('bets') * 1 > 0 ||
                        m.get('wins') * 1 > 0;
                })
                if (this.modelRequests > 0) {
                    models_to_show.push(this.loadingModel)
                }
                this.visibleCollection.reset(models_to_show);
            },

            onCollectionRequest: function(modelOrCollection, xhr, options) {
                if (modelOrCollection instanceof Backbone.Model) {
                    this.updateVisibleCollection(1);
                } else {
                    this.ui.overlay.show();
                    this.saveState();
                    this.resetVisibleCollection();
                }
            },

            onCollectionSync: function(modelOrCollection) {
                if (modelOrCollection instanceof Backbone.Model) {
                    this.updateVisibleCollection(-1);
                } else {
                    this.ui.overlay.hide();
                }
            },

            onCollectionError: function(modelOrCollection) {
                if (modelOrCollection instanceof Backbone.Model) {
                    this.updateVisibleCollection(-1);
                } else {
                    this.ui.overlay.hide();
                }
            },

            onCollectionUpdate: function() {
                if (this.getOption('totals') === '0') {
                    this.hideTotals();
                } else {
                    this.showTotals();
                }
            },

            _encodeDate: function(key) {
                var date = this.getOption(key);
                return date ? date.format() : null;
            },

            saveState: function() {
                this.triggerMethod('state:changed', {
                    game_id: this.options.game_id,
                    tz: this.options.tz,
                    mode: this.options.mode,
                    report_type: this.options.report_type,
                    start_date: this.options.start_date,
                    end_date: this.options.end_date,
                    header: this.options.header,
                    totals: this.options.totals,
                    info: this.options.info,
                    lang: this.options.lang,
                    realtime: this.options.realtime
                });
            },

            onSearchFormSubmit: function(data) {
                _.extend(this.options, data);

                if (data.round_id) {
                    this.saveState();
                    return this.showRoundResult({
                        round_id: data.round_id,
                        balance: '1'
                    });
                }
                this.collection.fetch();
            },

            onShowTransactions: function(model) {
                return this.showTransactions({
                    game_id: model.get('game_id'),
                    currency: model.get('currency'),
                    mode: model.get('mode')
                });
            },

            showTransactions: function(data) {
                var params = {
                    game_id: this.options.game_id,
                    player_id: this.options.player_id,
                    mode: this.options.mode,
                    report_type: this.options.report_type,
                    start_date: this.options.start_date,
                    end_date: this.options.end_date,
                    tz: this.options.tz,
                    header: this.options.header,
                    totals: this.options.totals,
                    info: this.options.info,
                    lang: this.options.lang
                };
                if (this.options.brand !== null && this.options.brand !== undefined) {
                    params.brand = this.options.brand;
                }
                var href = '#show=transactions&' + Utils.encodeQueryData(
                    Object.assign(params, data));
                Backbone.history.navigate(href, {
                    trigger: true
                });
            },

            showRoundResult: function(data) {
                var params = {
                    player_id: this.options.player_id,
                    tz: this.options.tz,
                    header: this.options.header,
                    totals: this.options.totals,
                    info: this.options.info,
                    lang: this.options.lang
                };
                if (this.options.brand !== null && this.options.brand !== undefined) {
                    params.brand = this.options.brand;
                }
                var href = '#show=round&' + Utils.encodeQueryData(Object.assign(
                    params, data));
                Backbone.history.navigate(href, {
                    trigger: true
                });
            }
        });

        module.exports = PlayerGamesView;


        /***/
    }),
    /* 18 */
    /***/
    (function(module, exports, __webpack_require__) {

        var GridUtils = __webpack_require__(3);
        var locales = __webpack_require__(0);
        var Storage = __webpack_require__(1);

        var PlayerGamesGridView = GridUtils.FixedHeaderCellMultilineFooter.extend({
            columns: [{
                attr: 'game_id',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Game')
                    }
                }),
                cell: GridUtils.MobileCell.extend({
                    displayName: locales.translate('Game'),
                    className: 'sm-float-left',
                    events: {
                        'click a': 'onViewBtnClick'
                    },
                    onViewBtnClick: function(e) {
                        e.preventDefault();
                        this.triggerMethod('show:transactions', this.model);
                    },
                    templateContext: function() {
                        var context = GridUtils.MobileCell.prototype.templateContext.apply(this, arguments);

                        var gameID = this.getAttrValue();
                        var gameInfo = Storage.availableGames.findWhere({
                            id: gameID
                        });
                        var fullName;
                        if (gameInfo) {
                            var localizedName = locales.translate('title', gameInfo.get('i18n'));
                            var name = gameInfo.get('name');
                            fullName = name + ' (' + localizedName + ')';
                        } else {
                            fullName = this.model.get('game_name') || gameID || '';
                        }

                        context['val'] = '<a href="" class="table-link">' + fullName + '</a>';
                        return context;
                    }
                })
            }, {
                attr: 'currency',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Currency')
                    }
                }),
                className: 'text-center',
                sortable: false,
                cell: GridUtils.CustomCell.extend({
                    className: 'sm-float-left',
                    template: _.template([
                        '<span class="hidden-xs"><%= val %></span>',
                    ].join(''))
                })
            }, {
                attr: 'rounds',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Rounds')
                    }
                }),
                className: 'text-right',

                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-right',
                    showSpinner: true,
                    displayName: locales.translate('Rounds'),
                })
            }, {
                attr: 'bets',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Bets')
                    }
                }),
                className: 'text-right',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-right',
                    showCurrency: true,
                    showSpinner: true,
                    displayName: locales.translate('Bets'),
                })
            }, {
                _id: 'wins',
                attr: 'wins',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Wins')
                    }
                }),
                className: 'text-right',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-right',
                    showCurrency: true,
                    showSpinner: true,
                    displayName: locales.translate('Wins')
                })
            }, {
                attr: 'outcome',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Outcome')
                    }
                }),
                className: 'text-right',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-right',
                    showCurrency: true,
                    showSpinner: true,
                    displayName: locales.translate('Outcome')
                })
            }, {
                attr: 'payout',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Payout,%')
                    }
                }),
                className: 'text-right',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-right',
                    showSpinner: true,
                    displayName: locales.translate('Payout,%')
                })
            }],

            sizerView: MaGrid.SizerView.extend({
                template: _.template([
                    '<select class="form-control input-sm">',
                    '<% for(var i in pageSizes) { %>',
                    '<option <% if(pageSizes[i] == currentSize) { %> selected<% }%>  value="<%= pageSizes[i] %>">',
                    '    <%= pageText.replace("{size}", pageSizes[i]) %>',
                    '</option>',
                    '<% }%>',
                    '</select>'
                ].join('')),
                templateContext: function() {
                    var ctx = MaGrid.SizerView.prototype.templateContext.apply(this, arguments);
                    ctx.pageText = locales.translate('{size} per page');
                    return ctx;
                }
            }),

            footerColumns: [{
                attr: 'currency',
                className: 'text-bold',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-center',
                    showSpinner: true,
                    displayName: locales.translate('Total'),
                    template: _.template([
                        '<span class="visible-xs-inline">',
                        '<b><%= displayName %>: <%= _.isNull(val) ? "&mdash;" : val %>',
                        '</b></span>'
                    ].join(''))
                })
            }, {
                attr: 'currency',
                className: 'text-center text-bold',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-center',
                    showCurrency: false,
                    showSpinner: true,
                    displayName: locales.translate('Total'),
                    template: _.template([
                        '<span class="hidden-xs">',
                        '<% if (showSpinner) { %>',
                        '    <% if (val === null) { %>',
                        '        <i class=\'fa fa-spin fa-refresh\'></i>',
                        '    <% } else { %>',
                        '        <%= val %>',
                        '    <% } %>',
                        '<% } else { %>',
                        '    <%= _.isNull(val) ? "&mdash;" : val %>',
                        '<% } %>',
                        '</span>'
                    ].join(''))
                })
            }, {
                attr: 'rounds',
                className: 'text-right text-bold',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-right',
                    showSpinner: true,
                    displayName: locales.translate('Rounds')
                })
            }, {
                attr: 'bets',
                className: 'text-right text-bold',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-right',
                    showCurrency: true,
                    showSpinner: true,
                    displayName: locales.translate('Bets')
                })
            }, {
                _id: 'wins',
                attr: 'wins',
                className: 'text-right text-bold',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-right',
                    showCurrency: true,
                    showSpinner: true,
                    displayName: locales.translate('Wins')
                })
            }, {
                attr: 'outcome',
                className: 'text-right text-bold',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-right',
                    showCurrency: true,
                    showSpinner: true,
                    displayName: locales.translate('Outcome')
                })
            }, {
                attr: 'payout',
                className: 'text-right text-bold',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-right',
                    showCurrency: false,
                    showSpinner: true,
                    displayName: locales.translate('Payout,%')
                })
            }],
            bodyView: MaGrid.BodyView.extend({
                loadingView: Marionette.View.extend({
                    tagName: 'tr',
                    template: _.template([
                        '<td colspan="<%- cs %>" style="text-align: center;">',
                        '   <i class="fa fa-refresh fa-spin"></i>',
                        '</td>'
                    ].join('')),
                    templateContext: function() {
                        return {
                            cs: this.getOption('columns').length
                        }
                    }
                }),
                _getChildView: function(model) {
                    if (_.any(_.values(model.attributes))) {
                        return MaGrid.BodyView.prototype._getChildView.apply(this, arguments);
                    } else {
                        return this.loadingView
                    }

                }
            })
        });

        module.exports = PlayerGamesGridView;


        /***/
    }),
    /* 19 */
    /***/
    (function(module, exports) {

        module.exports = function(obj) {
            obj || (obj = {});
            var __t, __p = '',
                __e = _.escape,
                __j = Array.prototype.join;

            function print() {
                __p += __j.call(arguments, '')
            }
            with(obj) {
                __p += '<div class="box box-primary no-shadow">\n\n    <form class="box-header with-border" style="background-color: #e4e8ec;">\n        ';
                if (header !== '0') {;
                    __p += '\n            <div class="row">\n                <div class="col-xs-12 col-md-10">\n                    <div class="row">\n                        <div class="col-xs-6 col-sm-3 col-md-2">\n                            <div class="form-group">\n                                <label>' +
                        __e(translate('Report Type')) +
                        ':</label>\n                                <select class="form-control" name="report_type">\n                                    ';
                    for (var i in report_types) {;
                        __p += '\n                                        <option ';
                        if (report_types[i].uid == report_type) {;
                            __p += 'selected';
                        };
                        __p += '\n                                                value="' +
                            __e(report_types[i].uid) +
                            '">\n                                            ' +
                            __e(translate(report_types[i].name)) +
                            '\n                                        </option>\n                                    ';
                    };
                    __p += '\n                                </select>\n                                <div class="field-error"></div>\n                            </div>\n                        </div>\n                        <div class="col-xs-6 col-sm-3 col-md-2">\n                            <div class="form-group">\n                                <label>' +
                        __e(translate('Game mode')) +
                        ':</label>\n                                <select class="form-control" name="mode">\n                                    ';
                    for (var i in available_modes) {;
                        __p += '\n                                        <option ';
                        if (available_modes[i].mode == mode) {;
                            __p += 'selected';
                        };
                        __p += '\n                                                value="' +
                            __e(available_modes[i].mode) +
                            '">\n                                            ' +
                            __e(translate(available_modes[i].title)) +
                            '\n                                        </option>\n                                    ';
                    };
                    __p += '\n                                </select>\n                                <div class="field-error"></div>\n                            </div>\n                        </div>\n                        <div class="col-xs-12 col-sm-6 col-md-2">\n                            <div class="form-group">\n                                <label>' +
                        __e(translate('Game')) +
                        ':</label>\n                                <select class="form-control" name="game_id">\n                                    <option value="" selected>' +
                        __e(translate('All')) +
                        '</option>\n                                    ';
                    for (var i in available_games) {;
                        __p += '\n                                        ';
                        if (!available_games[i].was_played) continue;;
                        __p += '\n                                        <option ';
                        if (available_games[i].id == game_id) {;
                            __p += 'selected';
                        };
                        __p += '\n                                                value="' +
                            __e(available_games[i].id) +
                            '">\n                                            ' +
                            __e(available_games[i].name) +
                            '\n                                            (' +
                            __e(translate('title', available_games[i].i18n)) +
                            ')\n                                        </option>\n                                    ';
                    };
                    __p += '\n                                </select>\n                                <div class="field-error"></div>\n                            </div>\n                        </div>\n                        <div class="col-xs-12 col-sm-6 col-md-2">\n                            <div class="form-group">\n                                <label>' +
                        __e(translate('Round')) +
                        ':</label>\n                                <input type="text" class="form-control" name="round_id">\n                                <div class="field-error"></div>\n                            </div>\n                        </div>\n                        <div class="col-xs-12 col-sm-6 col-md-4">\n                            <div class="form-group">\n                                <label>' +
                        __e(translate('Date Range')) +
                        ':</label>\n                                <input type="text" class="form-control" id="gapicker" autocomplete="off">\n                                <div class="field-error"></div>\n                            </div>\n                        </div>\n                        <div class="col-xs-12 col-sm-12 visible-sm-block visible-xs-block">\n                            <div class="box-submit">\n                                <div class="btn btn-warning btn-block submit-btn">\n                                    <i class="fa fa-search"></i> ' +
                        __e(translate('Search')) +
                        '\n                                </div>\n                            </div>\n                        </div>\n                    </div>\n                </div>\n                <div class="hidden-xs hidden-sm col-md-2">\n                    <div class="row">\n                        <div class="col-xs-12">\n                            <div class="box-submit">\n                                <div class="btn btn-warning btn-block submit-btn">\n                                    <i class="fa fa-search"></i> ' +
                        __e(translate('Search')) +
                        '\n                                </div>\n                            </div>\n                        </div>\n                    </div>\n                </div>\n            </div>\n        ';
                };
                __p += '\n        <h4 class="horizontal-divider">\n            <i class="fa fa-bar-chart"></i> ' +
                    __e(translate('Player Games Results')) +
                    '\n        </h4>\n    </form>\n\n    <div class="box-body app-totals-widget" style="padding: 20px 0 0;">\n        <div class="box-header with-border" style="background-color: #e4e8ec;">\n            <label style="margin: 0;">' +
                    __e(translate('Total')) +
                    '</label>\n        </div>\n        <div class="box-body app-totals-region">\n        </div>\n    </div>\n\n    <div class="box-body app-list-region" style="padding: 20px 0;">\n    </div>\n    <div class="overlay" style="display: none;">\n        <i class="fa fa-refresh fa-spin"></i>\n    </div>\n</div>\n';

            }
            return __p
        };


        /***/
    }),
    /* 20 */
    /***/
    (function(module, exports, __webpack_require__) {

        var Storage = __webpack_require__(1);
        var Models = __webpack_require__(4);
        var defaults = __webpack_require__(5);
        var FormUtils = __webpack_require__(6);
        var locales = __webpack_require__(0);
        var View = __webpack_require__(10);

        var RoundsGridView = __webpack_require__(21);
        var tpl = __webpack_require__(23);
        var TotalsGridView = __webpack_require__(7);
        var PlayerInfoGridView = __webpack_require__(12);


        var TransactionsView = Backbone.Marionette.View.extend({
            options: {
                start_date: defaults.start_date,
                end_date: defaults.end_date,
                tz: defaults.tz,
                report_type: defaults.report_type,
                game_id: defaults.game_id,
                currency: defaults.currency,
                mode: defaults.mode,
                per_page: defaults.per_page,
                round_id: defaults.round_id,
                header: defaults.header,
                totals: defaults.totals,
                info: defaults.info,
                exceeds: defaults.exceeds,
                lang: defaults.lang
            },
            template: tpl,
            templateContext: function() {
                return {
                    report_types: Storage.reportTypes.toJSON(),
                    available_modes: Storage.availableModes.toJSON(),
                    available_games: Storage.availableGames.toJSON(),
                    report_type: this.getOption('report_type'),
                    mode: this.getOption('mode'),
                    game_id: this.getOption('game_id'),
                    header: this.getOption('header'),
                    totals: this.getOption('totals'),
                    showPlayerInfo: this.showPlayerInfo,
                    exceeds: this.getOption('exceeds'),
                    round_id: this.getOption('round_id'),
                    translate: locales.translate.bind(locales)
                }
            },
            ui: {
                listRegion: '.app-list-region',
                totalsRegion: '.app-totals-region',
                totalsWidget: '.app-totals-widget',
                playerInfoRegion: '.app-player-info-region',
                overlay: '.overlay'
            },
            regions: {
                listRegion: '@ui.listRegion',
                totalsRegion: '@ui.totalsRegion',
                playerInfoRegion: '@ui.playerInfoRegion'
            },
            collectionEvents: {
                update: 'onCollectionUpdate',
                request: 'onCollectionRequest',
                sync: 'onCollectionSync',
                error: 'onCollectionError'
            },

            behaviors: [{
                behaviorClass: FormUtils.FormBehavior,
                submitEvent: 'search:form:submit'
            }, {
                behaviorClass: View.NestedViewBehavior
            }],

            initialize: function() {
                locales.set(this.options.lang);
                this.options.tz = parseInt(this.options.tz);
                if (_.isNaN(this.options.tz)) {
                    this.options.tz = defaults.tz;
                }
                if (this.options.start_date) {
                    this.options.start_date = moment(this.options.start_date, 'YYYYMMDDTHHmm');
                    this.options.start_date = this.options.start_date.utcOffset(this.options.tz, true);
                } else {
                    this.options.start_date = null;
                }
                if (this.options.end_date) {
                    this.options.end_date = moment(this.options.end_date, 'YYYYMMDDTHHmm');
                    this.options.end_date = this.options.end_date.utcOffset(this.options.tz, true);
                } else {
                    this.options.end_date = null;
                }

                this.collection = new Models.TransactionCollection([], {
                    queryParams: {
                        start_date: _.bind(function() {
                            var sd = this.getOption('start_date');
                            return sd ? sd.format() : null;
                        }, this),
                        end_date: _.bind(function() {
                            var ed = this.getOption('end_date');
                            return ed ? ed.format() : null;
                        }, this),
                        game_id: _.bind(function() {
                            return this.getOption('game_id') || null;
                        }, this),
                        currency: _.bind(function() {
                            return this.getOption('currency') || null;
                        }, this),
                        mode: _.bind(this.getOption, this, 'mode'),
                        report_type: _.bind(this.getOptionOrNull, this, 'report_type'),
                        round_id: _.bind(this.getOptionOrNull, this, 'round_id'),
                        tz: _.bind(this.getOption, this, 'tz'),
                        exceeds: _.bind(function() {
                            var exceeds = this.getOption('exceeds');
                            return !!+exceeds;
                        }, this)
                    },
                    state: {
                        pageSize: this.getOption('per_page') * 1
                    }
                });
                this.subtotalsCollection = new Models.TransactionTotalsCollection([], {
                    source: this.collection
                });
                this.totalsModel = new Models.PlayerGameModel({
                    currency: this.getOption('currency')
                });

                this.showPlayerInfo = this.getOption('info') !== '0';
                if (this.showPlayerInfo) {
                    this.playerInfoModel = new Models.PlayerInfoModel();
                }
            },
            renderGrid: function() {
                if (!this.getChildView('listRegion')) {
                    var gridView = new RoundsGridView({
                        collection: this.collection,
                        footerCollection: this.subtotalsCollection
                    });
                    this.showChildView('listRegion', gridView);
                }
                this.collection.fetch();
            },

            renderTotalsGrid: function() {
                if (!this.getChildView('totalsRegion')) {
                    var gridView = new TotalsGridView({
                        collection: new Backbone.Collection([
                            this.totalsModel
                        ])
                    });
                    this.showChildView('totalsRegion', gridView);
                    this.hideTotals();
                }
                this.fetchTotals();
            },

            fetchTotals: function() {
                var start_date = this._encodeDate('start_date'),
                    end_date = this._encodeDate('end_date');
                this.totalsModel.fetch({
                    data: {
                        start_date: start_date,
                        end_date: end_date,
                        currency: this.getOption('currency'),
                        mode: this.getOption('mode'),
                        report_type: this.getOption('report_type'),
                        game_id: this.getOption('game_id'),
                        round_id: this.getOption('round_id') || null
                    }
                });
            },

            hideTotals: function() {
                this.ui.totalsWidget.hide();
            },

            showTotals: function() {
                this.ui.totalsWidget.show();
            },

            renderPlayerInfo: function() {
                if (!this.getChildView('playerInfoRegion')) {
                    var gridView = new PlayerInfoGridView({
                        collection: new Backbone.Collection([
                            this.playerInfoModel
                        ])
                    });
                    this.showChildView('playerInfoRegion', gridView);
                }
                this.fetchPlayerInfo();
            },

            fetchPlayerInfo: function() {
                this.playerInfoModel.fetch({
                    data: {
                        round_id: this.getOption('round_id') || null,
                        currency: this.getOption('currency'),
                        mode: this.getOption('mode'),
                        report_type: this.getOption('report_type'),
                        game_id: this.getOption('game_id')
                    }
                });
            },

            onRender: function() {
                setTimeout(_.bind(function() {
                    this.$('#gapicker').gapicker({
                        allowNulls: true,
                        showTime: true,
                        startDate: this.getOption('start_date'),
                        endDate: this.getOption('end_date'),
                        utcOffset: this.getOption('tz'),
                        calendarsCount: 2,
                        position: 'bottom center',
                        format: 'YYYY-MM-DD HH:mm:ss',
                        applyLabel: locales.translate('Apply'),
                        cancelLabel: locales.translate('Cancel'),
                        fromLabel: locales.translate('From') + ': ',
                        toLabel: locales.translate('To') + ': ',
                        offsetLabel: locales.translate('Zone') + ': ',
                        daysOfWeek: [
                            locales.translate('Su'),
                            locales.translate('Mo'),
                            locales.translate('Tu'),
                            locales.translate('We'),
                            locales.translate('Th'),
                            locales.translate('Fr'),
                            locales.translate('Sa')
                        ],

                        monthNames: [
                            locales.translate('Jan'),
                            locales.translate('Feb'),
                            locales.translate('Mar'),
                            locales.translate('Apr'),
                            locales.translate('May'),
                            locales.translate('Jun'),
                            locales.translate('Jul'),
                            locales.translate('Aug'),
                            locales.translate('Sep'),
                            locales.translate('Oct'),
                            locales.translate('Nov'),
                            locales.translate('Dec')
                        ],
                        customRanges: [
                            ['1d', locales.translate('Today')],
                            ['1w', locales.translate('This Week')],
                            ['1m', locales.translate('This Month')],
                            ['3m', locales.translate('Last 3 Months')]

                        ],
                        callback: _.bind(function(from, to, tz) {
                            this.options.start_date = from;
                            this.options.end_date = to;
                            this.options.tz = tz;

                        }, this)
                    });
                }, this), 1);

                this.renderGrid();
                this.renderTotalsGrid();

                if (this.showPlayerInfo) {
                    this.renderPlayerInfo();
                }
            },
            onCollectionSync: function() {
                this.ui.overlay.hide();
            },
            onCollectionError: function() {
                this.ui.overlay.hide();
            },
            onCollectionRequest: function() {
                this.ui.overlay.show();
                this.triggerMethod('state:changed', {
                    show: 'transactions',
                    game_id: this.options.game_id,
                    tz: this.options.tz,
                    start_date: this.options.start_date,
                    end_date: this.options.end_date,
                    per_page: this.options.per_page,
                    round_id: this.options.round_id,
                    currency: this.options.currency,
                    mode: this.options.mode,
                    report_type: this.options.report_type,
                    header: this.options.header,
                    totals: this.options.totals,
                    info: this.options.info,
                    exceeds: this.options.exceeds,
                    lang: this.options.lang
                });
            },

            onCollectionUpdate: function() {
                if (this.getOption('totals') === '0') {
                    this.hideTotals();
                } else {
                    this.showTotals();
                }

            },
            _encodeDate: function(key) {
                var date = this.getOption(key);
                return date ? date.format() : null;
            },

            onSearchFormSubmit: function(data) {
                _.extend(this.options, data);
                this.collection.fetch();
                this.fetchTotals();
                if (this.showPlayerInfo) {
                    this.fetchPlayerInfo();
                }
            }
        });

        module.exports = TransactionsView;


        /***/
    }),
    /* 21 */
    /***/
    (function(module, exports, __webpack_require__) {

        var GridUtils = __webpack_require__(3);
        var GridUtilsCustom = __webpack_require__(11);
        var Storage = __webpack_require__(1);
        var locales = __webpack_require__(0);

        var TransactionsGridView = GridUtils.FixedHeaderCellMultilineFooter.extend({
            childViewEvents: _.extend({}, GridUtils.FixedHeaderCellMultilineFooter.prototype.childViewEvents, {
                'header:expand_all': 'onHeaderExpand',
                'header:collapse_all': 'onHeaderCollapse',

                'row:cell:zoom': 'invalidateHeader',
                'row:cell:toggle': 'invalidateHeader',
                'header:toggle': 'invalidateHeader',
            }),

            onHeaderExpand: function() {
                this.getChildView('body').children.each(function(view) {
                    view.expand && view.expand();
                });
            },

            onHeaderCollapse: function() {
                this.getChildView('body').children.each(function(view) {
                    view.collapse && view.collapse();
                });
            },

            dataRowView: MaGrid.DataRowView.extend({
                expand: function() {
                    _.each(this.getRegions(), function(region) {
                        var view = region.currentView;
                        view && view.expand && view.expand();
                    }, this);
                },
                collapse: function() {
                    _.each(this.getRegions(), function(region) {
                        var view = region.currentView;
                        view && view.collapse && view.collapse();
                    }, this);
                }
            }),

            columns: [{
                header: 'ID',
                attr: 'transaction_id',
                className: 'uid-cell',
                cell: GridUtils.CustomCell.extend({
                    className: 'sm-float-left',
                    template: _.template([
                        '<span class="visible-xs-inline text-nowrap"><b>UID: </b><%= val %></span>',
                        '<div class="hidden-xs btn-xs btn-default badge"><i class="fa fa-info"></i></a>',
                    ].join('')),
                    onRender: function() {
                        this.$('div').popover({
                            trigger: 'click',
                            placement: 'right',
                            html: true,
                            content: (_.template([
                                '<%- transaction_id %>'
                            ].join('')))(this.model.toJSON())
                        })
                    }
                })
            }, {
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Date')
                    }
                }),
                attr: 'c_at',
                sortable: false,
                cell: GridUtils.MobileCell.extend({
                    displayName: locales.translate('Date'),
                    className: 'sm-float-left',
                    dateFormat: 'YYYY-MM-DD[&nbsp;]HH:mm:ss',
                    templateContext: function() {
                        var context = GridUtils.MobileCell.prototype.templateContext.apply(this, arguments);
                        var val = this.getAttrValue(),
                            tz = parseInt(this.model.collection.lastFilters.tz),
                            format = this.getOption('dateFormat'),
                            dt = moment(val).utc().utcOffset(tz);
                        context['val'] = dt ? dt.format(format).replace(' ', '&nbsp;') : '&mdash;';
                        return context;
                    }
                })
            }, {
                attr: 'currency',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Currency')
                    }
                }),
                sortable: false,
                className: 'text-center',
                cell: GridUtils.MobileCell.extend({
                    template: _.template([
                        '<span class="hidden-xs"><%= val %></span>'
                    ].join(''))
                })
            }, {
                sortable: false,
                attr: 'balance_before',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Bal. Before')
                    }
                }),
                className: 'text-right',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-right',
                    showCurrency: true,
                    displayName: locales.translate('Bal. Before'),
                })
            }, {
                sortable: false,
                attr: 'bet',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Bet')
                    }
                }),
                className: 'text-right',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-right',
                    showCurrency: true,
                    displayName: locales.translate('Bet'),
                    getAttrValue: function() {
                        var val = GridUtils.MobileCell.prototype.getAttrValue.apply(this);

                        if (this.model.get('type') === 'ROLLBACK') {
                            return '<i class="fa fa-rotate-left" title="Rollback"></i>&nbsp;' + val;
                        } else if (val != null && this.model.get('is_bonus')) {
                            return val + ' <i class="fa fa-gift"></i>';
                        } else {
                            return val;
                        }
                    }
                })
            }, {
                sortable: false,
                attr: 'win',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Win')
                    }
                }),
                className: 'text-right',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-right',
                    showCurrency: true,
                    displayName: locales.translate('Win'),
                    getAttrValue: function() {
                        if (this.model.get('status') === 'OK') {
                            var column = this.getOption('column');
                            return this.model.get(column.get('attr'));
                        } else {
                            return this.model.get('status');
                        }
                    }
                })
            }, {
                sortable: false,
                attr: 'outcome',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Outcome')
                    }
                }),
                className: 'text-right',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-right',
                    showCurrency: true,
                    showSpinner: true,
                    displayName: locales.translate('Outcome'),
                    getAttrValue: function() {
                        var status = this.model.get('status');
                        if (status === 'OK') {
                            var column = this.getOption('column');
                            return this.model.get(column.get('attr'));
                        } else if (status === 'EXCEED') {
                            return 0;
                        } else {
                            return '?';
                        }
                    }
                })
            }, {
                sortable: false,
                attr: 'balance_after',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Bal. After')
                    }
                }),
                className: 'text-right',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-right',
                    showCurrency: true,
                    displayName: locales.translate('Bal. After'),
                    getAttrValue: function() {
                        var status = this.model.get('status');
                        if (status === 'OK' || status === 'EXCEED') {
                            return GridUtils.MobileCell.prototype.getAttrValue.apply(this);
                        } else {
                            return '?';
                        }
                    }
                })

            }, {
                attr: 'round_id',
                sortable: false,
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Round')
                    }
                }),
                className: 'text-center',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left',
                    displayName: locales.translate('Round')
                })
            }, {
                attr: 'round_id',
                className: 'height-auto drawer-table-cell',
                header: GridUtilsCustom.DrawerHeader,
                cell: GridUtilsCustom.DrawerCell
            }],
            sizerView: MaGrid.SizerView.extend({
                template: _.template([
                    '<select class="form-control input-sm">',
                    '<% for(var i in pageSizes) { %>',
                    '<option <% if(pageSizes[i] == currentSize) { %> selected<% }%>  value="<%= pageSizes[i] %>">',
                    '    <%= pageText.replace("{size}", pageSizes[i]) %>',
                    '</option>',
                    '<% }%>',
                    '</select>'
                ].join('')),
                templateContext: function() {
                    var ctx = MaGrid.SizerView.prototype.templateContext.apply(this, arguments);
                    ctx.pageText = locales.translate('{size} per page');
                    return ctx;
                }
            }),

            footerColumns: [{
                attr: 'currency',
                className: 'text-bold',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-center',
                    showSpinner: true,
                    displayName: locales.translate('Total'),
                    template: _.template([
                        '<span class="visible-xs-inline">',
                        '<b><%= displayName %>: <%= _.isNull(val) ? "&mdash;" : val %>',
                        '</b></span>'
                    ].join(''))
                })
            }, {
                attr: 'currency',
                className: 'hidden-xs text-bold',
                cell: _.noop
            }, {
                attr: 'currency',
                className: 'text-center text-bold',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-center',
                    showCurrency: false,
                    showSpinner: true,
                    displayName: locales.translate('Total'),
                    template: _.template([
                        '<span class="hidden-xs">',
                        '<% if (showSpinner) { %>',
                        '    <% if (val === null) { %>',
                        '        <i class=\'fa fa-spin fa-refresh\'></i>',
                        '    <% } else { %>',
                        '        <%= val %>',
                        '    <% } %>',
                        '<% } else { %>',
                        '    <%= _.isNull(val) ? "&mdash;" : val %>',
                        '<% } %>',
                        '</span>'
                    ].join(''))
                })
            }, {
                attr: 'currency',
                className: 'hidden-xs text-bold',
                cell: _.noop
            }, {
                attr: 'bet',
                className: 'text-right text-bold',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-right',
                    showCurrency: true,
                    showSpinner: true,
                    displayName: locales.translate('Bet')
                })
            }, {
                attr: 'win',
                className: 'text-right text-bold',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-right',
                    showCurrency: true,
                    showSpinner: true,
                    displayName: locales.translate('Wins')
                })
            }, {
                attr: 'outcome',
                className: 'text-right text-bold',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-right',
                    showCurrency: true,
                    showSpinner: true,
                    displayName: locales.translate('Outcome')
                })
            }, {
                attr: 'currency',
                className: 'hidden-xs text-bold',
                cell: _.noop
            }, {
                attr: 'round_id',
                className: 'text-center text-bold',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-center',
                    showCurrency: false,
                    showSpinner: true,
                    displayName: locales.translate('Rounds')
                })
            }]
        });

        module.exports = TransactionsGridView;


        /***/
    }),
    /* 22 */
    /***/
    (function(module, exports) {

        function requestAnimationFrame(callback) {
            return window.requestAnimationFrame ?
                window.requestAnimationFrame(callback) :
                setTimeout(callback, 1);
        }

        function cancelAnimationFrame(id) {
            return window.cancelAnimationFrame ?
                window.cancelAnimationFrame(id) :
                clearTimeout(id);
        }

        var QueueItem = Marionette.Object.extend({
            initialize: function($el, loadFn, context) {
                this.$el = $el;
                this.loadFn = loadFn;
                this.context = context;

                this.destroy = _.bind(this.destroy, this);

                this._subscribe();
            },

            is: function($el) {
                return this.$el === $el;
            },

            load: function() {
                return this.loadFn.call(this.context, this.$el);
            },

            destroy: function(options) {
                this._unsubscribe();
                if (!options || !options.silent) {
                    this.triggerMethod('destroy', this);
                }
            },

            _subscribe: function() {
                this.$el.on('load', this.destroy);
                this.$el.on('error', this.destroy);
            },

            _unsubscribe: function() {
                this.$el.off('load', this.destroy);
                this.$el.off('error', this.destroy);
            },
        });

        var Queue = Marionette.Object.extend({
            queue: null,
            processing: null,
            concurrent: 2,
            initialize: function() {
                this.queue = [];
                this.processing = [];
                this.timeout = null;
                this._flush = _.bind(this._flush, this);
            },
            add: function($el, loadFn, context) {
                var item = new QueueItem($el, loadFn, context);
                item.on('destroy', this._onItemDestroy, this);
                this.queue.push(item);
                this._scheduleFlush();
            },
            remove: function($el) {
                this._removeElFromArray($el, this.queue);
                this._removeElFromArray($el, this.processing);

                this._scheduleFlush();
            },
            _flush: function() {
                this._unscheduleFlush();

                var queue = this.queue,
                    processing = this.processing,
                    concurrent = this.getOption('concurrent');

                if (!queue.length) return;
                if (processing.length >= concurrent) return;

                var item = queue.shift();
                processing.push(item);
                item.load();

                this._scheduleFlush();
            },
            _removeItemFromArray: function(item, array) {
                var i = array.indexOf(item);
                if (i !== -1) {
                    array.splice(i, 1);
                }
            },
            _removeElFromArray: function($el, array) {
                for (var i = array.length; i--;) {
                    var item = array[i];
                    if (item.is($el)) {
                        array.splice(i, 1);
                        item.destroy({
                            silent: true
                        });
                    }
                }
            },
            _onItemDestroy: function(item) {
                this._removeItemFromArray(item, this.queue);
                this._removeItemFromArray(item, this.processing);
                this._scheduleFlush();
            },
            _scheduleFlush: function() {
                if (this.timeout) return;
                this.timeout = requestAnimationFrame(this._flush);
            },
            _unscheduleFlush: function() {
                if (!this.timeout) return;
                cancelAnimationFrame(this.timeout);
                this.timeout = null;
            },
        });

        var q = new Queue();

        module.exports = {
            Queue: Queue,
            q: q
        };


        /***/
    }),
    /* 23 */
    /***/
    (function(module, exports) {

        module.exports = function(obj) {
            obj || (obj = {});
            var __t, __p = '',
                __e = _.escape,
                __j = Array.prototype.join;

            function print() {
                __p += __j.call(arguments, '')
            }
            with(obj) {
                __p += '<div class="box box-primary no-shadow">\n\n    <form class="box-header with-border" style="background-color: #e4e8ec;">\n        ';
                if (header !== '0') {;
                    __p += '\n            <div class="row">\n                <div class="col-xs-12 col-md-10">\n                    <div class="row">\n                        <div class="col-xs-6 col-sm-3 col-md-2">\n                            <div class="form-group">\n                                <label>' +
                        __e(translate('Report Type')) +
                        ':</label>\n                                <select class="form-control" name="report_type">\n                                    ';
                    for (var i in report_types) {;
                        __p += '\n                                        <option ';
                        if (report_types[i].uid == report_type) {;
                            __p += 'selected';
                        };
                        __p += '\n                                                value="' +
                            __e(report_types[i].uid) +
                            '">\n                                            ' +
                            __e(translate(report_types[i].name)) +
                            '\n                                        </option>\n                                    ';
                    };
                    __p += '\n                                </select>\n                                <div class="field-error"></div>\n                            </div>\n                        </div>\n                        <div class="col-xs-6 col-sm-3 col-md-2">\n                            <div class="form-group">\n                                <label>' +
                        __e(translate('Game mode')) +
                        ':</label>\n                                <select class="form-control" name="mode">\n                                    ';
                    for (var i in available_modes) {;
                        __p += '\n                                        <option ';
                        if (available_modes[i].mode == mode) {;
                            __p += 'selected';
                        };
                        __p += '\n                                                value="' +
                            __e(available_modes[i].mode) +
                            '">\n                                            ' +
                            __e(translate(available_modes[i].title)) +
                            '\n                                        </option>\n                                    ';
                    };
                    __p += '\n                                </select>\n                                <div class="field-error"></div>\n                            </div>\n                        </div>\n                        <div class="col-xs-12 col-sm-6 col-md-2">\n                            <div class="form-group">\n                                <label>' +
                        __e(translate('Game')) +
                        ':</label>\n                                <select class="form-control" name="game_id">\n                                    ';
                    for (var i in available_games) {;
                        __p += '\n                                        ';
                        if (!available_games[i].was_played) continue;;
                        __p += '\n                                        <option ';
                        if (available_games[i].id == game_id) {;
                            __p += 'selected';
                        };
                        __p += '\n                                                value="' +
                            __e(available_games[i].id) +
                            '">\n                                            ' +
                            __e(available_games[i].name) +
                            '\n                                            (' +
                            __e(translate('title', available_games[i].i18n)) +
                            ')\n                                        </option>\n                                    ';
                    };
                    __p += '\n                                </select>\n                                <div class="field-error"></div>\n                            </div>\n                        </div>\n                        <div class="col-xs-12 col-sm-6 col-md-2">\n                            <div class="form-group">\n                                <label>' +
                        __e(translate('Round')) +
                        ':</label>\n                                <input type="text" class="form-control" name="round_id" value="' +
                        __e(round_id) +
                        '">\n                                <div class="field-error"></div>\n                            </div>\n                        </div>\n                        <div class="col-xs-12 col-sm-6 col-md-4">\n                            <div class="form-group">\n                                <label>' +
                        __e(translate('Date Range')) +
                        ':</label>\n                                <input type="text" class="form-control" id="gapicker" autocomplete="off">\n                                <div class="field-error"></div>\n                            </div>\n                        </div>\n                        <div class="col-xs-12 col-sm-12 visible-sm-block visible-xs-block">\n                            <div class="box-submit">\n                                <div class="btn btn-warning btn-block submit-btn">\n                                    <i class="fa fa-search"></i> ' +
                        __e(translate('Search')) +
                        '\n                                </div>\n                            </div>\n                        </div>\n                    </div>\n                </div>\n                <div class="hidden-xs hidden-sm col-md-2">\n                    <div class="row">\n                        <div class="col-xs-12">\n                            <div class="box-submit">\n                                <div class="btn btn-warning btn-block submit-btn">\n                                    <i class="fa fa-search"></i> ' +
                        __e(translate('Search')) +
                        '\n                                </div>\n                            </div>\n                        </div>\n                    </div>\n                </div>\n            </div>\n        ';
                };
                __p += '\n\n        <h4 class="horizontal-divider">\n            <i class="fa fa-bar-chart"></i> ' +
                    __e(translate('Transaction Results')) +
                    '\n        </h4>\n\n        <a class="section goback" title="go back">\n            <i class="fa fa-backward"></i> ' +
                    __e(translate('player games')) +
                    '\n        </a>\n\n    </form>\n\n    ';
                if (showPlayerInfo) {;
                    __p += '\n        <div class="box-body app-player-info-widget" style="padding: 20px 0 0;">\n            <div class="box-header with-border" style="background-color: #e4e8ec;">\n                <label style="margin: 0;">' +
                        __e(translate('Info')) +
                        '</label>\n            </div>\n            <div class="box-body app-player-info-region">\n            </div>\n        </div>\n    ';
                };
                __p += '\n\n    <div class="box-body app-totals-widget" style="padding: 20px 0 0;">\n        <div class="box-header with-border" style="background-color: #e4e8ec;">\n            <label style="margin: 0;">' +
                    __e(translate('Total')) +
                    '</label>\n        </div>\n        <div class="box-body app-totals-region">\n        </div>\n    </div>\n\n    <div class="box-body app-list-region" style="padding: 20px 0;">\n    </div>\n    <div class="overlay" style="display: none;">\n        <i class="fa fa-refresh fa-spin"></i>\n    </div>\n</div>\n';

            }
            return __p
        };


        /***/
    }),
    /* 24 */
    /***/
    (function(module, exports, __webpack_require__) {

        var Models = __webpack_require__(4);
        var defaults = __webpack_require__(5);
        var FormUtils = __webpack_require__(6);
        var locales = __webpack_require__(0);
        var View = __webpack_require__(10);

        var RoundsGridView = __webpack_require__(25);
        var tpl = __webpack_require__(26);
        var TotalsGridView = __webpack_require__(7);
        var PlayerInfoGridView = __webpack_require__(12);


        var RoundView = Backbone.Marionette.View.extend({
            options: {
                tz: defaults.tz,
                round_id: defaults.round_id,
                header: defaults.header,
                totals: defaults.totals,
                info: defaults.info,
                balance: defaults.balance,
                lang: defaults.lang
            },
            template: tpl,
            templateContext: function() {
                return {
                    header: this.getOption('header'),
                    totals: this.getOption('totals'),
                    showPlayerInfo: this.showPlayerInfo,
                    round_id: this.getOption('round_id'),
                    translate: locales.translate.bind(locales)
                }
            },
            ui: {
                listRegion: '.app-list-region',
                totalsRegion: '.app-totals-region',
                totalsWidget: '.app-totals-widget',
                playerInfoRegion: '.app-player-info-region',
                overlay: '.overlay'
            },
            regions: {
                listRegion: '@ui.listRegion',
                totalsRegion: '@ui.totalsRegion',
                playerInfoRegion: '@ui.playerInfoRegion'
            },
            collectionEvents: {
                request: 'onCollectionRequest',
                sync: 'onCollectionSync',
                error: 'onCollectionError'
            },

            behaviors: [{
                behaviorClass: FormUtils.FormBehavior,
                submitEvent: 'search:form:submit'
            }, {
                behaviorClass: View.NestedViewBehavior
            }],

            initialize: function() {
                locales.set(this.options.lang);
                this.options.tz = parseInt(this.options.tz);
                if (_.isNaN(this.options.tz)) {
                    this.options.tz = defaults.tz;
                }

                this.collection = new Models.RoundTransactionCollection([], {
                    queryParams: {
                        round_id: _.bind(this.getOption, this, 'round_id'),
                        tz: _.bind(this.getOption, this, 'tz'),
                        // don't send player_id
                        player_id: undefined
                    },
                    comparator: 'c_at'
                });
                // totals require 'bets'
                this.totalsCollection = new Models.RoundTransactionTotalsCollection([], {
                    source: this.collection
                });
                // footer requires 'bet'
                this.footerTotalsCollection = new Models.TransactionTotalsCollection([], {
                    source: this.collection
                });

                this.showPlayerInfo = this.getOption('info') !== '0';
                if (this.showPlayerInfo) {
                    this.playerInfoModel = new Models.PlayerInfoModel();
                }
            },
            renderGrid: function() {
                if (!this.getChildView('listRegion')) {
                    var gridView = new RoundsGridView({
                        collection: this.collection,
                        footerCollection: this.footerTotalsCollection
                    });
                    this.showChildView('listRegion', gridView);
                }
                this.collection.fetch();
            },

            renderTotalsGrid: function() {
                if (!this.getChildView('totalsRegion')) {
                    var gridView = new TotalsGridView({
                        collection: this.totalsCollection
                    });
                    this.showChildView('totalsRegion', gridView);
                }
            },

            renderPlayerInfo: function() {
                if (!this.getChildView('playerInfoRegion')) {
                    var gridView = new PlayerInfoGridView({
                        collection: new Backbone.Collection([
                            this.playerInfoModel
                        ])
                    });
                    this.showChildView('playerInfoRegion', gridView);
                }
                this.fetchPlayerInfo();
            },

            fetchPlayerInfo: function() {
                this.playerInfoModel.fetch({
                    data: {
                        round_id: this.getOption('round_id')
                    }
                });
            },

            onRender: function() {
                this.renderGrid();
                if (this.getOption('totals') === '0') {
                    this.ui.totalsWidget.hide();
                } else {
                    this.renderTotalsGrid();
                    this.ui.totalsWidget.show();
                }

                if (this.showPlayerInfo) {
                    this.renderPlayerInfo();
                }

                if (this.getOption('balance') === '0') {
                    this.getChildView('listRegion').hideBalance();
                } else {
                    this.getChildView('listRegion').showBalance();
                }
            },
            onCollectionSync: function() {
                this.ui.overlay.hide();
            },
            onCollectionError: function() {
                this.ui.overlay.hide();
            },
            onCollectionRequest: function() {
                this.ui.overlay.show();
                this.triggerMethod('state:changed', {
                    show: 'round',
                    tz: this.options.tz,
                    round_id: this.options.round_id,
                    header: this.options.header,
                    totals: this.options.totals,
                    info: this.options.info,
                    balance: this.options.balance,
                    lang: this.options.lang
                });
            },

            onSearchFormSubmit: function(data) {
                _.extend(this.options, data);
                this.collection.fetch();
                if (this.showPlayerInfo) {
                    this.fetchPlayerInfo();
                }
            }
        });

        module.exports = RoundView;


        /***/
    }),
    /* 25 */
    /***/
    (function(module, exports, __webpack_require__) {

        var GridUtils = __webpack_require__(3);
        var GridUtilsCustom = __webpack_require__(11);
        var Storage = __webpack_require__(1);
        var locales = __webpack_require__(0);

        var RoundsGridView = GridUtils.FixedHeaderCellMultilineFooter.extend({
            childViewEvents: _.extend({}, GridUtils.FixedHeaderCellMultilineFooter.prototype.childViewEvents, {
                'header:expand_all': 'onHeaderExpand',
                'header:collapse_all': 'onHeaderCollapse',

                'row:cell:zoom': 'invalidateHeader',
                'row:cell:toggle': 'invalidateHeader',
                'header:toggle': 'invalidateHeader',
            }),

            onHeaderExpand: function() {
                this.getChildView('body').children.each(function(view) {
                    view.expand && view.expand();
                });
            },

            onHeaderCollapse: function() {
                this.getChildView('body').children.each(function(view) {
                    view.collapse && view.collapse();
                });
            },

            dataRowView: MaGrid.DataRowView.extend({
                expand: function() {
                    _.each(this.getRegions(), function(region) {
                        var view = region.currentView;
                        view && view.expand && view.expand();
                    }, this);
                },
                collapse: function() {
                    _.each(this.getRegions(), function(region) {
                        var view = region.currentView;
                        view && view.collapse && view.collapse();
                    }, this);
                }
            }),

            columns: [{
                header: 'ID',
                attr: 'transaction_id',
                className: 'uid-cell',
                cell: GridUtils.CustomCell.extend({
                    className: 'sm-float-left',
                    template: _.template([
                        '<span class="visible-xs-inline text-nowrap"><b>UID: </b><%= val %></span>',
                        '<div class="hidden-xs btn-xs btn-default badge"><i class="fa fa-info"></i></a>'
                    ].join('')),
                    onRender: function() {
                        this.$('div').popover({
                            trigger: 'click',
                            placement: 'right',
                            html: true,
                            content: (_.template([
                                '<%- transaction_id %>'
                            ].join('')))(this.model.toJSON())
                        })
                    }
                })
            }, {
                attr: 'game_id',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Game')
                    }
                }),
                sortable: false,
                cell: GridUtils.MobileCell.extend({
                    displayName: locales.translate('Game'),
                    className: 'sm-float-left',
                    templateContext: function() {
                        var context = GridUtils.MobileCell.prototype.templateContext.apply(this, arguments);

                        var gameID = this.getAttrValue();
                        var gameInfo = Storage.availableGames.findWhere({
                            id: gameID
                        });
                        var fullName;
                        if (gameInfo) {
                            var localizedName = locales.translate('title', gameInfo.get('i18n'));
                            var name = gameInfo.get('name');
                            fullName = name + ' (' + localizedName + ')';
                        } else {
                            fullName = this.model.get('game_name') || gameID || '';
                        }

                        context['val'] = fullName;
                        return context;
                    }
                })
            }, {
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Date')
                    }
                }),
                attr: 'c_at',
                sortable: false,
                cell: GridUtils.MobileCell.extend({
                    displayName: locales.translate('Date'),
                    className: 'sm-float-left',
                    dateFormat: 'YYYY-MM-DD[&nbsp;]HH:mm:ss',
                    templateContext: function() {
                        var context = GridUtils.MobileCell.prototype.templateContext.apply(this, arguments);
                        var val = this.getAttrValue(),
                            tz = parseInt(this.model.collection.lastFilters.tz),
                            format = this.getOption('dateFormat'),
                            dt = moment(val).utc().utcOffset(tz);
                        context['val'] = dt ? dt.format(format).replace(' ', '&nbsp;') : '&mdash;';
                        return context;
                    }
                })
            }, {
                attr: 'currency',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Currency')
                    }
                }),
                sortable: false,
                className: 'text-center',
                cell: GridUtils.MobileCell.extend({
                    template: _.template([
                        '<span class="hidden-xs"><%= val %></span>'
                    ].join(''))
                })
            }, {
                sortable: false,
                attr: 'balance_before',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Bal. Before')
                    }
                }),
                className: 'text-right',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-right',
                    showCurrency: true,
                    displayName: locales.translate('Bal. Before'),
                })
            }, {
                sortable: false,
                attr: 'bet',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Bet')
                    }
                }),
                className: 'text-right',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-right',
                    showCurrency: true,
                    displayName: locales.translate('Bet'),
                    getAttrValue: function() {
                        var val = GridUtils.MobileCell.prototype.getAttrValue.apply(this);

                        if (this.model.get('type') === 'ROLLBACK') {
                            return '<i class="fa fa-rotate-left" title="Rollback"></i>&nbsp;' + val;
                        } else if (val != null && this.model.get('is_bonus')) {
                            return val + ' <i class="fa fa-gift"></i>';
                        } else {
                            return val;
                        }
                    }
                })
            }, {
                sortable: false,
                attr: 'win',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Win')
                    }
                }),
                className: 'text-right',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-right',
                    showCurrency: true,
                    displayName: locales.translate('Win'),
                    getAttrValue: function() {
                        if (this.model.get('status') === 'OK') {
                            var column = this.getOption('column');
                            return this.model.get(column.get('attr'));
                        } else {
                            return this.model.get('status');
                        }
                    }
                })
            }, {
                sortable: false,
                attr: 'outcome',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Outcome')
                    }
                }),
                className: 'text-right',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-right',
                    showCurrency: true,
                    showSpinner: true,
                    displayName: locales.translate('Outcome'),
                    getAttrValue: function() {
                        var status = this.model.get('status');
                        if (status === 'OK') {
                            var column = this.getOption('column');
                            return this.model.get(column.get('attr'));
                        } else if (status === 'EXCEED') {
                            return 0;
                        } else {
                            return '?';
                        }
                    }
                })
            }, {
                sortable: false,
                attr: 'balance_after',
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Bal. After')
                    }
                }),
                className: 'text-right',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-right',
                    showCurrency: true,
                    displayName: locales.translate('Bal. After'),
                    getAttrValue: function() {
                        var status = this.model.get('status');
                        if (status === 'OK' || status === 'EXCEED') {
                            return GridUtils.MobileCell.prototype.getAttrValue.apply(this);
                        } else {
                            return '?';
                        }
                    }
                })

            }, {
                attr: 'round_id',
                sortable: false,
                header: Marionette.View.extend({
                    template: function() {
                        return locales.translate('Round')
                    }
                }),
                className: 'text-center',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left',
                    displayName: locales.translate('Round')
                })
            }, {
                attr: 'round_id',
                className: 'height-auto drawer-table-cell',
                header: GridUtilsCustom.DrawerHeader,
                cell: GridUtilsCustom.DrawerCell
            }],
            sizerView: MaGrid.SizerView.extend({
                template: _.template([
                    '<select class="form-control input-sm">',
                    '<% for(var i in pageSizes) { %>',
                    '<option <% if(pageSizes[i] == currentSize) { %> selected<% }%>  value="<%= pageSizes[i] %>">',
                    '    <%= pageText.replace("{size}", pageSizes[i]) %>',
                    '</option>',
                    '<% }%>',
                    '</select>'
                ].join('')),
                templateContext: function() {
                    var ctx = MaGrid.SizerView.prototype.templateContext.apply(this, arguments);
                    ctx.pageText = locales.translate('{size} per page');
                    return ctx;
                }
            }),

            footerColumns: [{
                attr: 'currency',
                className: 'text-bold',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-center',
                    showSpinner: true,
                    displayName: locales.translate('Total'),
                    template: _.template([
                        '<span class="visible-xs-inline">',
                        '<b><%= displayName %>: <%= _.isNull(val) ? "&mdash;" : val %>',
                        '</b></span>'
                    ].join(''))
                })
            }, {
                attr: 'currency',
                className: 'hidden-xs text-bold',
                cell: _.noop
            }, {
                attr: 'currency',
                className: 'hidden-xs text-bold',
                cell: _.noop
            }, {
                attr: 'currency',
                className: 'text-center text-bold',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-center',
                    showCurrency: false,
                    showSpinner: true,
                    displayName: locales.translate('Total'),
                    template: _.template([
                        '<span class="hidden-xs">',
                        '<% if (showSpinner) { %>',
                        '    <% if (val === null) { %>',
                        '        <i class=\'fa fa-spin fa-refresh\'></i>',
                        '    <% } else { %>',
                        '        <%= val %>',
                        '    <% } %>',
                        '<% } else { %>',
                        '    <%= _.isNull(val) ? "&mdash;" : val %>',
                        '<% } %>',
                        '</span>'
                    ].join(''))
                })
            }, {
                attr: 'balance_before',
                className: 'hidden-xs text-bold',
                cell: _.noop
            }, {
                attr: 'bet',
                className: 'text-right text-bold',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-right',
                    showCurrency: true,
                    showSpinner: true,
                    displayName: locales.translate('Bet')
                })
            }, {
                attr: 'win',
                className: 'text-right text-bold',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-right',
                    showCurrency: true,
                    showSpinner: true,
                    displayName: locales.translate('Wins')
                })
            }, {
                attr: 'outcome',
                className: 'text-right text-bold',
                cell: GridUtils.MobileCell.extend({
                    className: 'sm-float-left text-right',
                    showCurrency: true,
                    showSpinner: true,
                    displayName: locales.translate('Outcome')
                })
            }, {
                attr: 'balance_after',
                className: 'hidden-xs text-bold',
                cell: _.noop
            }, {
                attr: 'currency',
                className: 'hidden-xs text-bold',
                cell: _.noop
            }],

            showBalance: function() {
                this.columns.each(function(model) {
                    if (model.get('attr').indexOf('balance') === 0) {
                        model.set('isHidden', false, {
                            silent: true
                        });
                    }
                });
                this.footerColumns.each(function(model) {
                    if (model.get('attr').indexOf('balance') === 0) {
                        model.set('isHidden', false, {
                            silent: true
                        });
                    }
                });
                this.render();
            },

            hideBalance: function() {
                this.columns.each(function(model) {
                    if (model.get('attr').indexOf('balance') === 0) {
                        model.set('isHidden', true, {
                            silent: true
                        });
                    }
                });
                this.footerColumns.each(function(model) {
                    if (model.get('attr').indexOf('balance') === 0) {
                        model.set('isHidden', true, {
                            silent: true
                        });
                    }
                });
                this.render();
            }
        });

        module.exports = RoundsGridView;


        /***/
    }),
    /* 26 */
    /***/
    (function(module, exports) {

        module.exports = function(obj) {
            obj || (obj = {});
            var __t, __p = '',
                __e = _.escape,
                __j = Array.prototype.join;

            function print() {
                __p += __j.call(arguments, '')
            }
            with(obj) {
                __p += '<div class="box box-primary  no-shadow">\n\n    <form class="box-header with-border" style="background-color: #e4e8ec;">\n        ';
                if (header !== '0') {;
                    __p += '\n            <div class="row">\n                <div class="col-xs-6 col-sm-10 col-md-10">\n                    <div class="form-group">\n                        <label>' +
                        ((__t = (translate('Round'))) == null ? '' : __t) +
                        ':</label>\n                        <input type="text" class="form-control" name="round_id" value="' +
                        __e(round_id) +
                        '">\n                        <div class="field-error"></div>\n                    </div>\n                </div>\n                <div class="col-xs-6 col-sm-2 col-md-2">\n                    <div class="box-submit">\n                        <div class="btn btn-warning btn-block submit-btn">\n                            <i class="fa fa-search"></i> ' +
                        ((__t = (translate('Search'))) == null ? '' : __t) +
                        '\n                        </div>\n                    </div>\n                </div>\n            </div>\n        ';
                };
                __p += '\n\n        <h4 class="horizontal-divider">\n            <i class="fa fa-bar-chart"></i> ' +
                    ((__t = (translate('Round Results'))) == null ? '' : __t) +
                    '\n        </h4>\n\n            <a class="section goback" title="go back">\n                <i class="fa fa-backward"></i> ' +
                    __e(translate('player games')) +
                    '\n            </a>\n\n    </form>\n\n    ';
                if (showPlayerInfo) {;
                    __p += '\n        <div class="box-body app-player-info-widget" style="padding: 20px 0 0;">\n            <div class="box-header with-border" style="background-color: #e4e8ec;">\n                <label style="margin: 0;">' +
                        __e(translate('Info')) +
                        '</label>\n            </div>\n            <div class="box-body app-player-info-region">\n            </div>\n        </div>\n    ';
                };
                __p += '\n\n    <div class="box-body app-totals-widget" style="padding: 20px 0 0;">\n        <div class="box-header with-border" style="background-color: #e4e8ec;">\n            <label style="margin: 0;">' +
                    __e(translate('Total')) +
                    '</label>\n        </div>\n        <div class="box-body app-totals-region">\n        </div>\n    </div>\n\n    <div class="box-body app-list-region" style="padding: 20px 0;">\n    </div>\n    <div class="overlay" style="display: none;">\n        <i class="fa fa-refresh fa-spin"></i>\n    </div>\n</div>\n';

            }
            return __p
        };


        /***/
    }),
    /* 27 */
    /***/
    (function(module, exports) {

        var Router = Marionette.AppRouter.extend({
            appRoutes: {
                '(:params)': 'index'
            }
        });

        module.exports = Router;

        /***/
    }),
    /* 28 */
    /***/
    (function(module, exports, __webpack_require__) {

        var tpl = __webpack_require__(29);


        var ScrollToTopWidgetView = Backbone.Marionette.View.extend({
            template: _.template([
                '<div class="ui app-scroll-top"><center>',
                '<i class="fa fa-angle-double-up" style="font-size: 4em;"></i>',
                '</center></div>'
            ].join('')),

        });


        var LayoutView = Backbone.Marionette.View.extend({
            template: tpl,
            className: '',
            ui: {
                content: '.content',
                overlay: '.overlay',
                scrollTop: '.scroll-top'
            },
            regions: {
                content: '@ui.content',
                scrollTop: '@ui.scrollTop'
            },
            childViewEvents: {
                'state:changed': 'onChildStateChanged'
            },

            events: {
                'click @ui.scrollTop': 'onScrollTopClick'
            },
            initialize: function() {
                this.dimensions = {
                    width: 0,
                    height: 0
                };
            },

            onRender: function() {
                this.showChildView('scrollTop', new ScrollToTopWidgetView());
                setInterval(_.bind(this.resize, this), 200);
            },

            show: function(view) {
                this.showChildView('content', view);
            },

            resize: function() {
                this.$el.css({
                    width: ''
                });
                if (this.$el[0].scrollWidth > $(window).width()) {
                    this.$el.css({
                        width: this.$el[0].scrollWidth + 'px'
                    });
                }

                var $content = this.ui.content;
                var min_height = ($content.outerWidth() > 710) ? 300 : 800;
                var dimensions = {
                    width: $content.outerWidth(),
                    height: Math.max($content.outerHeight() + 20, min_height)
                };

                if (this.dimensions.width !== dimensions.width || this.dimensions.height !== dimensions.height) {
                    window.parent.postMessage(JSON.stringify({
                        event: 'handshistory:resize',
                        dimensions: dimensions
                    }), '*');
                    this.dimensions = dimensions;
                }

                var $body = $('body');
                if ($body.outerHeight() > $(window).height()) {
                    this.showScroller();
                } else {
                    this.hideScroller();
                }
            },
            hideScroller: function() {
                this.ui.scrollTop.hide();
            },
            showScroller: function() {
                this.ui.scrollTop.show();
            },
            onChildStateChanged: function(data) {
                $('html, body').animate({
                    scrollTop: 0
                }, 500);
                this.triggerMethod('state:changed', data);
            },
            onScrollTopClick: function() {
                $('html, body').animate({
                    scrollTop: 0
                }, 250);
            },
            hideOverlay: function() {
                this.ui.overlay.hide();
            },
            showOverlay: function() {
                this.ui.overlay.show();
            }
        });

        module.exports = LayoutView;


        /***/
    }),
    /* 29 */
    /***/
    (function(module, exports) {

        module.exports = function(obj) {
            obj || (obj = {});
            var __t, __p = '',
                __e = _.escape;
            with(obj) {
                __p += '<div class="wrapper overlay-wrapper">\n    <div class="content">\n    </div>\n    <div class="scroll-top"></div>\n    <div class="overlay" style="display: none;">\n        <i class="fa fa-refresh fa-spin"></i>\n    </div>\n</div>\n';

            }
            return __p
        };


        /***/
    }),
    /* 30 */
    /***/
    (function(module, exports, __webpack_require__) {

        var tpl = __webpack_require__(31);

        var ErrorModalView = Backbone.Marionette.View.extend({
            options: {
                validationErrorText: 'Improperly configured: ',
                permissionsErrorText: 'You are not permitted to access this page!',
                commonErrorText: 'Oops, something went wrong! Please, try later',
                text: 'Oops, something went wrong!',
                deffered: null
            },
            template: tpl,
            className: 'modal fade',
            templateContext: function() {
                var text, errors, showBtns,
                    deferred = this.getOption('deferred');

                if (deferred.status === 400) {
                    text = this.getOption('validationErrorText');
                    showBtns = false;
                    errors = deferred.responseJSON;
                } else if (deferred.status === 403) {
                    text = this.getOption('permissionsErrorText');
                    showBtns = false;
                } else {
                    text = this.getOption('commonErrorText');
                    showBtns = true;
                }
                return {
                    text: text,
                    showBtns: showBtns,
                    errors: errors
                }
            },
            ui: {
                errorBox: '.app-error-box',
                denyBtn: '.btn.deny',
                approveBtn: '.btn.approve'
            },
            events: {
                'click @ui.denyBtn': 'onDenyBtnClick',
                'click @ui.approveBtn': 'onApproveBtnClick'
            },
            initialize: function(options) {
                var oldOnError = window.onerror;
                window.onerror = _.bind(function(msg, url, line, col, error) {
                    if (oldOnError) oldOnError.apply(this, arguments);
                    this.hide();
                }, this);
            },
            hide: function() {
                try {
                    this.$el.modal('hide');
                } catch (e) {}
            },
            onRender: function() {
                $('body').append(this.$el);

                this.$el.on('hidden.bs.modal', _.bind(function() {
                    try {
                        this.off();
                        this.destroy();
                    } catch (e) {}

                }, this));
                this.$el.modal({
                    show: true,
                    closable: false
                });
            },
            onDenyBtnClick: function() {
                this.hide();
            },
            onApproveBtnClick: function() {
                this.triggerMethod('repeat:request');
                this.hide();
            }
        });
        module.exports = ErrorModalView;


        /***/
    }),
    /* 31 */
    /***/
    (function(module, exports) {

        module.exports = function(obj) {
            obj || (obj = {});
            var __t, __p = '',
                __e = _.escape,
                __j = Array.prototype.join;

            function print() {
                __p += __j.call(arguments, '')
            }
            with(obj) {
                __p += '\n<div class="modal-dialog">\n    <div class="modal-content">\n        <div class="modal-body">\n            <div class="app-error-box" >\n                ' +
                    ((__t = (text)) == null ? '' : __t) +
                    '\n                ';
                if (errors) {;
                    __p += '\n                <br><br>\n                ';
                    var ek = _.keys(errors);
                    for (var i in ek) {;
                        __p += '\n                    <b>' +
                            ((__t = (ek[i])) == null ? '' : __t) +
                            ': </b>' +
                            ((__t = (errors[ek[i]].join(' '))) == null ? '' : __t) +
                            '<br>\n                ';
                    };
                    __p += '\n                ';
                };
                __p += '\n            </div>\n        </div>\n        ';
                if (showBtns) {;
                    __p += '\n        <div class="modal-footer">\n             <div class="btn btn-danger deny">\n                Cancel\n            </div>\n            <div class="btn btn-default approve">\n                <i class="icon refresh"></i> Repeat\n            </div>\n        </div>\n        ';
                };
                __p += '\n    </div>\n</div>';

            }
            return __p
        };


        /***/
    })
    /******/
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAgYWY0NDE2ZTMzMThkOTE2MDdiZmQiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL2xpYi9sb2NhbGVzLmpzIiwid2VicGFjazovLy8uL3NyYy9qcy9hcHAvc3RvcmFnZS5qcyIsIndlYnBhY2s6Ly8vLi9zcmMvanMvbGliL3V0aWxzLmpzIiwid2VicGFjazovLy8uL3NyYy9qcy9saWIvZ3JpZC5qcyIsIndlYnBhY2s6Ly8vLi9zcmMvanMvYXBwL21vZGVscy5qcyIsIndlYnBhY2s6Ly8vLi9zcmMvanMvYXBwL2RlZmF1bHRzLmpzIiwid2VicGFjazovLy8uL3NyYy9qcy9saWIvZm9ybS5qcyIsIndlYnBhY2s6Ly8vLi9zcmMvanMvdmlld3MvdG90YWxzL3ZpZXcuanMiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL2FwcC9uZXR3b3JrLmpzIiwid2VicGFjazovLy8uL3NyYy9qcy9hcHAvZW51bXMuanMiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL2xpYi92aWV3LmpzIiwid2VicGFjazovLy8uL3NyYy9qcy9saWIvZ3JpZF9jdXN0b20uanMiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL3ZpZXdzL3BsYXllcl9pbmZvL3ZpZXcuanMiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL2FwcC5qcyIsIndlYnBhY2s6Ly8vLi9zcmMvanMvbGliL3BhdGNoZXMuanMiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL2FwcC9jb250cm9sbGVyLmpzIiwid2VicGFjazovLy8uL3NyYy9qcy9saWIvbW9kZWwuanMiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL3ZpZXdzL3BsYXllcmdhbWVzL3ZpZXcuanMiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL3ZpZXdzL3BsYXllcmdhbWVzL2dyaWQuanMiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL3ZpZXdzL3BsYXllcmdhbWVzL3ZpZXcuZWpzIiwid2VicGFjazovLy8uL3NyYy9qcy92aWV3cy90cmFuc2FjdGlvbnMvdmlldy5qcyIsIndlYnBhY2s6Ly8vLi9zcmMvanMvdmlld3MvdHJhbnNhY3Rpb25zL2dyaWQuanMiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL2xpYi9lbGVtZW50X3F1ZXVlLmpzIiwid2VicGFjazovLy8uL3NyYy9qcy92aWV3cy90cmFuc2FjdGlvbnMvdmlldy5lanMiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL3ZpZXdzL3JvdW5kL3ZpZXcuanMiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL3ZpZXdzL3JvdW5kL2dyaWQuanMiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL3ZpZXdzL3JvdW5kL3ZpZXcuZWpzIiwid2VicGFjazovLy8uL3NyYy9qcy9hcHAvcm91dGVyLmpzIiwid2VicGFjazovLy8uL3NyYy9qcy92aWV3cy9sYXlvdXQvdmlldy5qcyIsIndlYnBhY2s6Ly8vLi9zcmMvanMvdmlld3MvbGF5b3V0L3ZpZXcuZWpzIiwid2VicGFjazovLy8uL3NyYy9qcy92aWV3cy9lcnJvcl9tb2RhbC92aWV3LmpzIiwid2VicGFjazovLy8uL3NyYy9qcy92aWV3cy9lcnJvcl9tb2RhbC92aWV3LmVqcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiO1FBQUE7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7OztRQUdBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBLEtBQUs7UUFDTDtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBLDJCQUEyQiwwQkFBMEIsRUFBRTtRQUN2RCxpQ0FBaUMsZUFBZTtRQUNoRDtRQUNBO1FBQ0E7O1FBRUE7UUFDQSxzREFBc0QsK0RBQStEOztRQUVySDtRQUNBOztRQUVBO1FBQ0E7Ozs7Ozs7QUM3REE7QUFDQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUEsVUFBVSxLQUFLLGNBQWMsS0FBSzs7QUFFbEMsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxVQUFVLEtBQUssZ0JBQWdCLEtBQUs7O0FBRXBDLEtBQUs7O0FBRUw7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FB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Q0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQSxTQUFTO0FBQ1Q7OztBQUdBLENBQUM7O0FBRUQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQSx3RUFBd0UsYUFBYTtBQUNyRjtBQUNBLENBQUM7O0FBRUQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBLENBQUM7O0FBRUQ7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1QsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLEtBQUs7QUFDTCxDQUFDOzs7QUFHRDtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0EsRTs7Ozs7O0FDNUxBLFlBQVksbUJBQU8sQ0FBQyxFQUFXOztBQUUvQjtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBLENBQUM7O0FBRUQ7QUFDQTtBQUNBO0FBQ0E7QUFDQSxDQUFDOztBQUVEO0FBQ0E7QUFDQSxDQUFDOztBQUVEO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxDQUFDOztBQUVEOztBQUVBO0FBQ0E7QUFDQTtBQUNBLENBQUM7O0FBRUQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBLEtBQUs7QUFDTDtBQUNBLDhCQUE4QjtBQUM5QjtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0EsQ0FBQzs7QUFFRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTCxDQUFDOztBQUVEO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsQ0FBQzs7QUFFRDtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVCxLQUFLOztBQUVMO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTCxDQUFDOzs7QUFHRDtBQUNBO0FBQ0E7QUFDQTtBQUNBLENBQUM7O0FBRUQ7QUFDQTtBQUNBO0FBQ0EsQ0FBQzs7QUFFRDtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQSxLQUFLO0FBQ0wsQ0FBQzs7QUFFRDtBQUNBO0FBQ0E7QUFDQSxDQUFDOztBQUVEO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0wsQ0FBQzs7QUFFRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Ozs7Ozs7QUNuT0EsWUFBWSxtQkFBTyxDQUFDLENBQVM7O0FBRTdCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7Ozs7Ozs7QUNuQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTtBQUNBLEtBQUs7O0FBRUw7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQSxDQUFDOzs7QUFHRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTs7QUFFQSxFOzs7Ozs7QUM5R0EsY0FBYyxtQkFBTyxDQUFDLENBQWE7QUFDbkMsZ0JBQWdCLG1CQUFPLENBQUMsQ0FBVTs7QUFFbEM7QUFDQTtBQUNBO0FBQ0EsOEJBQThCO0FBQzlCLG1D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O0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTOztBQUVUO0FBQ0E7QUFDQSxTQUFTO0FBQ1QsS0FBSzs7QUFFTDtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBLEtBQUs7O0FBRUw7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBLGlCQUFpQjtBQUNqQixhQUFhO0FBQ2IsU0FBUzs7QUFFVDtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7O0FBRUw7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBLEtBQUs7O0FBRUw7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNULEtBQUs7O0FBRUw7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1QsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHlDQUF5QyxjQUFjO0FBQ3ZELEtBQUs7O0FBRUw7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHlDQUF5QyxjQUFjO0FBQ3ZEO0FBQ0EsQ0FBQzs7QUFFRDs7Ozs7OztBQzFVQSxnQkFBZ0IsbUJBQU8sQ0FBQyxDQUFVO0FBQ2xDLGNBQWMsbUJBQU8sQ0FBQyxDQUFhO0FBQ25DLGNBQWMsbUJBQU8sQ0FBQyxDQUFhOztBQUVuQztBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNULEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNULEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNULEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVCxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVCxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1QsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVCxLQUFLOztBQUVMO0FBQ0E7QUFDQTtBQUNBLHlDQUF5QztBQUN6Qyx5REFBeUQsZ0JBQWdCO0FBQ3pFLHdDQUF3QyxLQUFLO0FBQzdDO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsK0NBQStDLEtBQUs7QUFDcEQ7QUFDQTtBQUNBLEtBQUs7O0FBRUw7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esb0VBQW9FO0FBQ3BFO0FBQ0E7QUFDQSxTQUFTO0FBQ1QsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHNDQUFzQztBQUN0QywyQ0FBMkM7QUFDM0M7QUFDQSx5QkFBeUIsT0FBTztBQUNoQztBQUNBLHlCQUF5QjtBQUN6QixxQkFBcUIsT0FBTztBQUM1QixpREFBaUQ7QUFDakQscUJBQXFCO0FBQ3JCO0FBQ0E7QUFDQSxTQUFTO0FBQ1QsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVCxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1QsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1QsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNULEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVCxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQSxtRUFBbUU7QUFDbkU7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7O0FBRUE7QUFDQSxLQUFLO0FBQ0wsQ0FBQzs7QUFFRDs7Ozs7OztBQzNQQTtBQUNBLGdCQUFnQjtBQUNoQjtBQUNBLGtCQUFrQjtBQUNsQjtBQUNBLDZIQUE2SDtBQUM3SCxzQkFBc0I7QUFDdEI7QUFDQTtBQUNBO0FBQ0EsOEJBQThCO0FBQzlCO0FBQ0EsMENBQTBDO0FBQzFDO0FBQ0EsRUFBRTtBQUNGO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxFQUFFO0FBQ0Y7QUFDQTtBQUNBO0FBQ0EsZ0NBQWdDO0FBQ2hDO0FBQ0EsdUNBQXVDO0FBQ3ZDO0FBQ0EsRUFBRTtBQUNGO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxFQUFFO0FBQ0Y7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGdDQUFnQztBQUNoQztBQUNBLDhDQUE4QztBQUM5QztBQUNBLHdDQUF3QztBQUN4QztBQUNBLEVBQUU7QUFDRjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEVBQUU7QUFDRjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxFQUFFO0FBQ0Y7QUFDQTtBQUNBLHNHQUFzRyxpRkFBaUYsd0NBQXdDO0FBQy9OO0FBQ0EsNEtBQTRLLDhEQUE4RDs7QUFFMU87QUFDQTtBQUNBOzs7Ozs7O0FDekVBLGNBQWMsbUJBQU8sQ0FBQyxDQUFhO0FBQ25DLGFBQWEsbUJBQU8sQ0FBQyxDQUFZO0FBQ2pDLGVBQWUsbUJBQU8sQ0FBQyxDQUFjO0FBQ3JDLGdCQUFnQixtQkFBTyxDQUFDLENBQVU7QUFDbEMsY0FBYyxtQkFBTyxDQUFDLENBQWE7QUFDbkMsV0FBVyxtQkFBTyxDQUFDLEVBQVU7O0FBRTdCLHFCQUFxQixtQkFBTyxDQUFDLEVBQVE7QUFDckMsVUFBVSxtQkFBTyxDQUFDLEVBQVk7QUFDOUIscUJBQXFCLG1CQUFPLENBQUMsQ0FBbUI7QUFDaEQseUJBQXlCLG1CQUFPLENBQUMsRUFBd0I7OztBQUd6RDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7O0FBRUw7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQixhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBLFNBQVM7O0FBRVQ7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7O0FBRUw7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1QsS0FBSzs7QUFFTDtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1QsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBLGlCQUFpQjtBQUNqQixhQUFhO0FBQ2IsU0FBUzs7QUFFVDtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFD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O0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNULEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsMENBQTBDO0FBQzFDO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLDRFQUE0RSxhQUFhO0FBQ3pGO0FBQ0E7QUFDQSxTQUFTO0FBQ1QsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNULEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVCxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0Esb0ZBQW9GO0FBQ3BGLGlCQUFpQjtBQUNqQjtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1QsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNULEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1QsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FB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Q0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBLHlCQUF5QixtQkFBbUI7QUFDNUM7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEVBQUU7QUFDRjtBQUNBLEVBQUU7QUFDRjtBQUNBLGVBQWU7QUFDZjtBQUNBLEVBQUU7QUFDRjs7QUFFQTtBQUNBO0FBQ0EiLCJmaWxlIjoiYXBwLmpzIiwic291cmNlc0NvbnRlbnQiOlsiIFx0Ly8gVGhlIG1vZHVsZSBjYWNoZVxuIFx0dmFyIGluc3RhbGxlZE1vZHVsZXMgPSB7fTtcblxuIFx0Ly8gVGhlIHJlcXVpcmUgZnVuY3Rpb25cbiBcdGZ1bmN0aW9uIF9fd2VicGFja19yZXF1aXJlX18obW9kdWxlSWQpIHtcblxuIFx0XHQvLyBDaGVjayBpZiBtb2R1bGUgaXMgaW4gY2FjaGVcbiBcdFx0aWYoaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0pIHtcbiBcdFx0XHRyZXR1cm4gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0uZXhwb3J0cztcbiBcdFx0fVxuIFx0XHQvLyBDcmVhdGUgYSBuZXcgbW9kdWxlIChhbmQgcHV0IGl0IGludG8gdGhlIGNhY2hlKVxuIFx0XHR2YXIgbW9kdWxlID0gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0gPSB7XG4gXHRcdFx0aTogbW9kdWxlSWQsXG4gXHRcdFx0bDogZmFsc2UsXG4gXHRcdFx0ZXhwb3J0czoge31cbiBcdFx0fTtcblxuIFx0XHQvLyBFeGVjdXRlIHRoZSBtb2R1bGUgZnVuY3Rpb25cbiBcdFx0bW9kdWxlc1ttb2R1bGVJZF0uY2FsbChtb2R1bGUuZXhwb3J0cywgbW9kdWxlLCBtb2R1bGUuZXhwb3J0cywgX193ZWJwYWNrX3JlcXVpcmVfXyk7XG5cbiBcdFx0Ly8gRmxhZyB0aGUgbW9kdWxlIGFzIGxvYWRlZFxuIFx0XHRtb2R1bGUubCA9IHRydWU7XG5cbiBcdFx0Ly8gUmV0dXJuIHRoZSBleHBvcnRzIG9mIHRoZSBtb2R1bGVcbiBcdFx0cmV0dXJuIG1vZHVsZS5leHBvcnRzO1xuIFx0fVxuXG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlcyBvYmplY3QgKF9fd2VicGFja19tb2R1bGVzX18pXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm0gPSBtb2R1bGVzO1xuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZSBjYWNoZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5jID0gaW5zdGFsbGVkTW9kdWxlcztcblxuIFx0Ly8gZGVmaW5lIGdldHRlciBmdW5jdGlvbiBmb3IgaGFybW9ueSBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQgPSBmdW5jdGlvbihleHBvcnRzLCBuYW1lLCBnZXR0ZXIpIHtcbiBcdFx0aWYoIV9fd2VicGFja19yZXF1aXJlX18ubyhleHBvcnRzLCBuYW1lKSkge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBuYW1lLCB7XG4gXHRcdFx0XHRjb25maWd1cmFibGU6IGZhbHNlLFxuIFx0XHRcdFx0ZW51bWVyYWJsZTogdHJ1ZSxcbiBcdFx0XHRcdGdldDogZ2V0dGVyXG4gXHRcdFx0fSk7XG4gXHRcdH1cbiBcdH07XG5cbiBcdC8vIGdldERlZmF1bHRFeHBvcnQgZnVuY3Rpb24gZm9yIGNvbXBhdGliaWxpdHkgd2l0aCBub24taGFybW9ueSBtb2R1bGVzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm4gPSBmdW5jdGlvbihtb2R1bGUpIHtcbiBcdFx0dmFyIGdldHRlciA9IG1vZHVsZSAmJiBtb2R1bGUuX19lc01vZHVsZSA/XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0RGVmYXVsdCgpIHsgcmV0dXJuIG1vZHVsZVsnZGVmYXVsdCddOyB9IDpcbiBcdFx0XHRmdW5jdGlvbiBnZXRNb2R1bGVFeHBvcnRzKCkgeyByZXR1cm4gbW9kdWxlOyB9O1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQoZ2V0dGVyLCAnYScsIGdldHRlcik7XG4gXHRcdHJldHVybiBnZXR0ZXI7XG4gXHR9O1xuXG4gXHQvLyBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGxcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubyA9IGZ1bmN0aW9uKG9iamVjdCwgcHJvcGVydHkpIHsgcmV0dXJuIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbChvYmplY3QsIHByb3BlcnR5KTsgfTtcblxuIFx0Ly8gX193ZWJwYWNrX3B1YmxpY19wYXRoX19cbiBcdF9fd2VicGFja19yZXF1aXJlX18ucCA9IFwiXCI7XG5cbiBcdC8vIExvYWQgZW50cnkgbW9kdWxlIGFuZCByZXR1cm4gZXhwb3J0c1xuIFx0cmV0dXJuIF9fd2VicGFja19yZXF1aXJlX18oX193ZWJwYWNrX3JlcXVpcmVfXy5zID0gMTMpO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIHdlYnBhY2svYm9vdHN0cmFwIGFmNDQxNmUzMzE4ZDkxNjA3YmZkIiwidmFyIGxvY2FsZXMgPSB7XG4gICAgZW46IHtcbiAgICAgICAgJ0FsbCBDdXJyZW5jaWVzJzogJ0FsbCdcbiAgICB9LFxuXG4gICAgcnU6IHtcbiAgICAgICAgJ0FsbCc6ICfQktGB0LUnLFxuICAgICAgICAnQWxsIEN1cnJlbmNpZXMnOiAn0JLRgdC1JyxcbiAgICAgICAgJ1JlcG9ydCBUeXBlJzogJ9Ci0LjQvyDQntGC0YfQtdGC0LAnLFxuICAgICAgICAnR2FtZSc6ICfQmNCz0YDQsCcsXG4gICAgICAgICdDdXJyZW5jeSc6ICfQktCw0LvRjtGC0LAnLFxuICAgICAgICAnUm91bmQnOiAn0KDQsNGD0L3QtCcsXG4gICAgICAgICdSb3VuZHMnOiAn0KDQsNGD0L3QtNGLJyxcbiAgICAgICAgJ0JldCc6ICfQodGC0LDQstC60LAnLFxuICAgICAgICAnQmV0cyc6ICfQodGC0LDQstC60LgnLFxuICAgICAgICAnV2luJzogJ9CS0YvQuNCz0YDRi9GIJyxcbiAgICAgICAgJ1dpbnMnOiAn0JLRi9C40LPRgNGL0YgnLFxuICAgICAgICAnUHJvZml0JzogJ9Cf0YDQuNCx0YvQu9GMJyxcbiAgICAgICAgJ091dGNvbWUnOiAn0KDQtdC30YPQu9GM0YLQsNGCJyxcbiAgICAgICAgJ1BheW91dCc6ICfQktGL0L/Qu9Cw0YLQsCcsXG4gICAgICAgICdQYXlvdXQsJSc6ICfQktGL0L/Qu9Cw0YLQsCwlJyxcbiAgICAgICAgJ0RhdGUnOiAn0JTQsNGC0LAnLFxuICAgICAgICAnQmFsLiBCZWZvcmUnOiAn0JHQsNC7LiDQlNC+JyxcbiAgICAgICAgJ0JhbC4gQWZ0ZXInOiAn0JHQsNC7LiDQn9C+0YHQu9C1JyxcbiAgICAgICAgJ0dhbWUgbW9kZSc6ICfQotC40L8g0LjQs9GA0YsnLFxuICAgICAgICAnRGF0ZSBSYW5nZSc6ICfQn9C10YDQuNC+0LQnLFxuICAgICAgICAnU2VhcmNoJzogJ9Cf0L7QuNGB0LonLFxuICAgICAgICAnUGxheWVyIEdhbWVzIFJlc3VsdHMnOiAn0JjQs9GA0YsnLFxuICAgICAgICAnVG90YWwnOiAn0JLRgdC10LPQvicsXG4gICAgICAgICdQbGF5ZXInOiAn0JjQs9GA0L7QuicsXG4gICAgICAgICdQcm9qZWN0JzogJ9Cf0YDQvtC10LrRgicsXG4gICAgICAgICdQcm92aWRlcic6ICfQn9GA0L7QstCw0LnQtNC10YAnLFxuICAgICAgICAnSW5mbyc6ICfQmNC90YTQvtGA0LzQsNGG0LjRjycsXG5cbiAgICAgICAgJ1RyYW5zYWN0aW9uIFJlc3VsdHMnOiAn0KLRgNCw0L3Qt9Cw0LrRhtC40LgnLFxuICAgICAgICAnUm91bmQgUmVzdWx0cyc6ICfQoNCw0YPQvdC0JyxcbiAgICAgICAgJ3BsYXllciBnYW1lcyc6ICfQuiDQuNCz0YDQsNC8JyxcblxuICAgICAgICAnRHJhd2VyJzogJ9CS0LjQt9GD0LDQu9C40LfQsNGG0LjRjycsXG4gICAgICAgICdFeHBhbmQgQWxsJzogJ9Cf0L7QutCw0LfQsNGC0Ywg0LLRgdC1JyxcbiAgICAgICAgJ0hpZGUgQWxsJzogJ9Ch0L/RgNGP0YLQsNGC0Ywg0LLRgdC1JyxcblxuICAgICAgICAnQXBwbHknOiAn0J/RgNC40LzQtdC90LjRgtGMJyxcbiAgICAgICAgJ0NhbmNlbCc6ICfQntGC0LzQtdC90LAnLFxuICAgICAgICAnRnJvbSc6ICfQntGCJyxcbiAgICAgICAgJ1RvJzogJ9CU0L4nLFxuICAgICAgICAnWm9uZSc6ICfQl9C+0L3QsCcsXG4gICAgICAgICdTdSc6ICfQktGBJyxcbiAgICAgICAgJ01vJzogJ9Cf0L0nLFxuICAgICAgICAnVHUnOiAn0JLRgicsXG4gICAgICAgICdXZSc6ICfQodGAJyxcbiAgICAgICAgJ1RoJzogJ9Cn0YInLFxuICAgICAgICAnRnInOiAn0J/RgicsXG4gICAgICAgICdTYSc6ICfQodCxJyxcblxuICAgICAgICAnSmFuJzogJ9Cv0L3QsicsXG4gICAgICAgICdGZWInOiAn0KTQtdCyJyxcbiAgICAgICAgJ01hcic6ICfQnNCw0YAnLFxuICAgICAgICAnQXByJzogJ9CQ0L/RgCcsXG4gICAgICAgICdNYXknOiAn0JzQsNC5JyxcbiAgICAgICAgJ0p1bic6ICfQmNGO0L0nLFxuICAgICAgICAnSnVsJzogJ9CY0Y7QuycsXG4gICAgICAgICdBdWcnOiAn0JDQstCzJyxcbiAgICAgICAgJ1NlcCc6ICfQodC10L0nLFxuICAgICAgICAnT2N0JzogJ9Ce0LrRgicsXG4gICAgICAgICdOb3YnOiAn0J3QvtGPJyxcbiAgICAgICAgJ0RlYyc6ICfQlNC10LonLFxuXG4gICAgICAgICdUb2RheSc6ICfQodC10LPQvtC00L3RjycsXG4gICAgICAgICdUaGlzIFdlZWsnOiAnMSDQvdC10LTQtdC70Y8nLFxuICAgICAgICAnVGhpcyBNb250aCc6ICcxINC80LXRgdGP0YYnLFxuICAgICAgICAnTGFzdCAzIE1vbnRocyc6ICczINC80LXRgdGP0YbQsCcsXG5cbiAgICAgICAgJ3tzaXplfSBwZXIgcGFnZSc6ICd7c2l6ZX0g0L3QsCDRgdGC0YDQsNC90LjRhtC1J1xuXG4gICAgfSxcblxuICAgIHpoOiB7XG4gICAgICAgICdBbGwnOiAn5YWo6YOo5ri45oiPJyxcbiAgICAgICAgJ0FsbCBDdXJyZW5jaWVzJzogJ+WFqOmDqOi0p+W4gScsXG4gICAgICAgICdSZXBvcnQgVHlwZSc6ICfmiqXooajnsbvlnosnLFxuICAgICAgICAnR2FtZSc6ICfmuLjmiI8nLFxuICAgICAgICAnQ3VycmVuY3knOiAn6LSn5biBJyxcbiAgICAgICAgJ1JvdW5kJzogJ+WbnuWQiCcsXG4gICAgICAgICdSb3VuZHMnOiAn5Zue5ZCI5pWw6YePJyxcbiAgICAgICAgJ0JldCc6ICfotYzms6gnLFxuICAgICAgICAnQmV0cyc6ICfmgLvotYzms6gnLFxuICAgICAgICAnV2luJzogJ+WllumHkScsXG4gICAgICAgICdXaW5zJzogJ+aAu+WllumHkScsXG4gICAgICAgICdQcm9maXQnOiAn5pS255uKJyxcbiAgICAgICAgJ091dGNvbWUnOiAn5a6e6ZmF6L6T6LWiJyxcbiAgICAgICAgJ1BheW91dCc6ICfotZTku5jnjocnLFxuICAgICAgICAnUGF5b3V0LCUnOiAn6LWU5LuY546HLCUnLFxuICAgICAgICAnRGF0ZSc6ICfml6XmnJ8nLFxuICAgICAgICAnQmFsLiBCZWZvcmUnOiAn5LiL5rOo5YmNJyxcbiAgICAgICAgJ0JhbC4gQWZ0ZXInOiAn5LiL5rOo5ZCOJyxcbiAgICAgICAgJ0dhbWUgbW9kZSc6ICfmuLjmiI/mqKHlvI8nLFxuICAgICAgICAnRGF0ZSBSYW5nZSc6ICfml6XmnJ8nLFxuICAgICAgICAnU2VhcmNoJzogJ+aQnOe0oicsXG4gICAgICAgICdQbGF5ZXIgR2FtZXMgUmVzdWx0cyc6ICfmuLjmiI/orrDlvZUnLFxuICAgICAgICAnVG90YWwnOiAn5oC76K6hJyxcbiAgICAgICAgJ1BsYXllcic6ICfnjqnlrrYnLFxuICAgICAgICAnUHJvamVjdCc6ICfkuJPmoYgnLFxuICAgICAgICAnUHJvdmlkZXInOiAn5byA5Y+R5ZWGJyxcbiAgICAgICAgJ0luZm8nOiAn6LWE6K6vJyxcblxuICAgICAgICAnUkVBTCc6ICfnnJ/pkrEnLFxuICAgICAgICAnRlVOJzogJ+WoseS5kCcsXG4gICAgICAgICdHR1InOiAn5oC75pS255uKJyxcbiAgICAgICAgJ05HUic6ICfmuLjmiI/mlLbnm4onLFxuICAgICAgICAnUFJPTU8nOiAn5L+D6ZSA5pS255uKJyxcblxuICAgICAgICAnVHJhbnNhY3Rpb24gUmVzdWx0cyc6ICfkuqTmmJPnu5PmnpwnLFxuICAgICAgICAnUm91bmQgUmVzdWx0cyc6ICflm57lkIjnu5PmnpwnLFxuICAgICAgICAncGxheWVyIGdhbWVzJzogJ+a4uOaIj+iusOW9lScsXG5cbiAgICAgICAgJ0RyYXdlcic6ICflm77lg4/orrDlvZUnLFxuICAgICAgICAnRXhwYW5kIEFsbCc6ICflhajpg6jlsZXlvIAnLFxuICAgICAgICAnSGlkZSBBbGwnOiAn5YWo6YOo6ZqQ6JePJyxcblxuICAgICAgICAnQXBwbHknOiAn56Gu5a6aJyxcbiAgICAgICAgJ0NhbmNlbCc6ICflj5bmtognLFxuICAgICAgICAnRnJvbSc6ICfku44nLFxuICAgICAgICAnVG8nOiAn5YiwJyxcbiAgICAgICAgJ1pvbmUnOiAn5pe25Yy6JyxcbiAgICAgICAgJ1N1JzogJ+aXpScsXG4gICAgICAgICdNbyc6ICfkuIAnLFxuICAgICAgICAnVHUnOiAn5LqMJyxcbiAgICAgICAgJ1dlJzogJ+S4iScsXG4gICAgICAgICdUaCc6ICflm5snLFxuICAgICAgICAnRnInOiAn5LqUJyxcbiAgICAgICAgJ1NhJzogJ+WFrScsXG5cbiAgICAgICAgJ0phbic6ICfkuIDmnIgnLFxuICAgICAgICAnRmViJzogJ+S6jOaciCcsXG4gICAgICAgICdNYXInOiAn5LiJ5pyIJyxcbiAgICAgICAgJ0Fwcic6ICflm5vmnIgnLFxuICAgICAgICAnTWF5JzogJ+S6lOaciCcsXG4gICAgICAgICdKdW4nOiAn5YWt5pyIJyxcbiAgICAgICAgJ0p1bCc6ICfkuIPmnIgnLFxuICAgICAgICAnQXVnJzogJ+WFq+aciCcsXG4gICAgICAgICdTZXAnOiAn5Lmd5pyIJyxcbiAgICAgICAgJ09jdCc6ICfljYHmnIgnLFxuICAgICAgICAnTm92JzogJ+WNgeS4gOaciCcsXG4gICAgICAgICdEZWMnOiAn5Y2B5LqM5pyIJyxcblxuICAgICAgICAnVG9kYXknOiAn5LuK5aSpJyxcbiAgICAgICAgJ1RoaXMgV2Vlayc6ICfmnKzlkagnLFxuICAgICAgICAnVGhpcyBNb250aCc6ICfmnKzmnIgnLFxuICAgICAgICAnTGFzdCAzIE1vbnRocyc6ICfmnIDov5HkuInkuKrmnIgnLFxuXG4gICAgICAgICd7c2l6ZX0gcGVyIHBhZ2UnOiAn5q+P6aG1e3NpemV95LiqJ1xuXG4gICAgfSxcblxuICAgICd6aC1oYW50Jzoge1xuICAgICAgICAnQWxsJzogJ+WFqOmDqOmBiuaIsicsXG4gICAgICAgICdBbGwgQ3VycmVuY2llcyc6ICflhajpg6josqjluaMnLFxuICAgICAgICAnUmVwb3J0IFR5cGUnOiAn5aCx6KGo6aGe5Z6LJyxcbiAgICAgICAgJ0dhbWUnOiAn6YGK5oiyJyxcbiAgICAgICAgJ0N1cnJlbmN5JzogJ+iyqOW5oycsXG4gICAgICAgICdSb3VuZCc6ICflm57lkIgnLFxuICAgICAgICAnUm91bmRzJzogJ+WbnuWQiOaVuOmHjycsXG4gICAgICAgICdCZXQnOiAn6LOt5rOoJyxcbiAgICAgICAgJ0JldHMnOiAn57i96LOt5rOoJyxcbiAgICAgICAgJ1dpbic6ICfnjY7ph5EnLFxuICAgICAgICAnV2lucyc6ICfnuL3njY7ph5EnLFxuICAgICAgICAnUHJvZml0JzogJ+aUtuebiicsXG4gICAgICAgICdPdXRjb21lJzogJ+Wvpumam+i8uOi0jycsXG4gICAgICAgICdQYXlvdXQnOiAn6LOg5LuY546HJyxcbiAgICAgICAgJ1BheW91dCwlJzogJ+izoOS7mOeOhywlJyxcbiAgICAgICAgJ0RhdGUnOiAn5pel5pyfJyxcbiAgICAgICAgJ0JhbC4gQmVmb3JlJzogJ+S4i+azqOWJjScsXG4gICAgICAgICdCYWwuIEFmdGVyJzogJ+S4i+azqOW+jCcsXG4gICAgICAgICdHYW1lIG1vZGUnOiAn6YGK5oiy5qih5byPJyxcbiAgICAgICAgJ0RhdGUgUmFuZ2UnOiAn5pel5pyfJyxcbiAgICAgICAgJ1NlYXJjaCc6ICfmkJzntKInLFxuICAgICAgICAnUGxheWVyIEdhbWVzIFJlc3VsdHMnOiAn5ri45oiy6KiY6YyEJyxcbiAgICAgICAgJ1RvdGFsJzogJ+e4veioiCcsXG4gICAgICAgICdQbGF5ZXInOiAn546p5a62JyxcbiAgICAgICAgJ1Byb2plY3QnOiAn5bCI5qGIJyxcbiAgICAgICAgJ1Byb3ZpZGVyJzogJ+mWi+eZvOWVhicsXG4gICAgICAgICdJbmZvJzogJ+izh+ioiicsXG5cbiAgICAgICAgJ1JFQUwnOiAn55yf6YyiJyxcbiAgICAgICAgJ0ZVTic6ICflqJvmqIInLFxuICAgICAgICAnR0dSJzogJ+e4veaUtuebiicsXG4gICAgICAgICdOR1InOiAn6YGK5oiy5pS255uKJyxcbiAgICAgICAgJ1BST01PJzogJ+S/g+mKt+aUtuebiicsXG5cbiAgICAgICAgJ1RyYW5zYWN0aW9uIFJlc3VsdHMnOiAn5Lqk5piT57WQ5p6cJyxcbiAgICAgICAgJ1JvdW5kIFJlc3VsdHMnOiAn5Zue5ZCI57WQ5p6cJyxcbiAgICAgICAgJ3BsYXllciBnYW1lcyc6ICfmuLjmiLLoqJjpjIQnLFxuXG4gICAgICAgICdEcmF3ZXInOiAn5ZyW5YOP6KiY6YyEJyxcbiAgICAgICAgJ0V4cGFuZCBBbGwnOiAn5YWo6YOo5bGV6ZaLJyxcbiAgICAgICAgJ0hpZGUgQWxsJzogJ+WFqOmDqOmaseiXjycsXG5cbiAgICAgICAgJ0FwcGx5JzogJ+eiuuWumicsXG4gICAgICAgICdDYW5jZWwnOiAn5Y+W5raIJyxcbiAgICAgICAgJ0Zyb20nOiAn5b6eJyxcbiAgICAgICAgJ1RvJzogJ+WIsCcsXG4gICAgICAgICdab25lJzogJ+aZguWNgCcsXG4gICAgICAgICdTdSc6ICfml6UnLFxuICAgICAgICAnTW8nOiAn5LiAJyxcbiAgICAgICAgJ1R1JzogJ+S6jCcsXG4gICAgICAgICdXZSc6ICfkuIknLFxuICAgICAgICAnVGgnOiAn5ZubJyxcbiAgICAgICAgJ0ZyJzogJ+S6lCcsXG4gICAgICAgICdTYSc6ICflha0nLFxuXG4gICAgICAgICdKYW4nOiAn5LiA5pyIJyxcbiAgICAgICAgJ0ZlYic6ICfkuozmnIgnLFxuICAgICAgICAnTWFyJzogJ+S4ieaciCcsXG4gICAgICAgICdBcHInOiAn5Zub5pyIJyxcbiAgICAgICAgJ01heSc6ICfkupTmnIgnLFxuICAgICAgICAnSnVuJzogJ+WFreaciCcsXG4gICAgICAgICdKdWwnOiAn5LiD5pyIJyxcbiAgICAgICAgJ0F1Zyc6ICflhavmnIgnLFxuICAgICAgICAnU2VwJzogJ+S5neaciCcsXG4gICAgICAgICdPY3QnOiAn5Y2B5pyIJyxcbiAgICAgICAgJ05vdic6ICfljYHkuIDmnIgnLFxuICAgICAgICAnRGVjJzogJ+WNgeS6jOaciCcsXG5cbiAgICAgICAgJ1RvZGF5JzogJ+S7iuWkqScsXG4gICAgICAgICdUaGlzIFdlZWsnOiAn5pys6YCxJyxcbiAgICAgICAgJ1RoaXMgTW9udGgnOiAn5pys5pyIJyxcbiAgICAgICAgJ0xhc3QgMyBNb250aHMnOiAn5pyA6L+R5LiJ5YCL5pyIJyxcblxuICAgICAgICAne3NpemV9IHBlciBwYWdlJzogJ+avj+mggXtzaXplfeWAiydcblxuICAgIH1cbn07XG52YXIgZGVmYXVsdExhbmcgPSAnZW4nO1xudmFyIGxhbmcgPSAnJztcbnZhciBsYW5ncyA9IFtdO1xuXG5tb2R1bGUuZXhwb3J0cyA9IHtcbiAgICAvKipcbiAgICAgKiBTZXRzIGxvY2FsZS4gQWxzbyBzZXRzIHR3byBzZWNvbmRhcnkgbG9jYWxlczogdHdvIGNoYXJhY3RlciBsb2NhbGUgYW5kIGRlZmF1bHQgbG9jYWxlXG4gICAgICogQHBhcmFtIHtzdHJpbmd9IGwgLSBtYWluIGxvY2FsZVxuICAgICAqL1xuICAgIHNldDogZnVuY3Rpb24obCkge1xuICAgICAgICBsYW5nID0gKGwgfHwgJycpLnRvTG93ZXJDYXNlKCk7XG4gICAgICAgIGxhbmdzID0gW2xhbmcsIGxhbmcuc3Vic3RyKDAsIDIpLCBkZWZhdWx0TGFuZ107XG4gICAgfSxcbiAgICAvKipcbiAgICAgKlxuICAgICAqIEBwYXJhbSB7c3RyaW5nfSB0ZXh0ICAtIHRleHQgdG8gbG9jYWxpemVcbiAgICAgKiBAcGFyYW0ge09iamVjdH0gW2xvY2FsZXNdIC0gbG9jYWxlIGRpY3Rpb25hcnlcbiAgICAgKiBAcmV0dXJucyB7c3RyaW5nfVxuICAgICAqL1xuICAgIHRyYW5zbGF0ZTogZnVuY3Rpb24odGV4dCwgbG9jYWxlcykge1xuICAgICAgICBsb2NhbGVzID0gbG9jYWxlcyB8fCB0aGlzLmxvY2FsZXM7XG5cbiAgICAgICAgLy8gc2VhcmNoIGluIG1haW4gYW5kIHNlY29uZGFyeSBsb2NhbGVzXG4gICAgICAgIGZvciAodmFyIGkgPSAwOyBpIDwgbGFuZ3MubGVuZ3RoOyArK2kpIHtcbiAgICAgICAgICAgIHZhciBsYW5nID0gbGFuZ3NbaV07XG4gICAgICAgICAgICB2YXIgbG9jYWxlID0gbG9jYWxlc1tsYW5nXTtcbiAgICAgICAgICAgIGlmIChsb2NhbGUgJiYgKHRleHQgaW4gbG9jYWxlKSkge1xuICAgICAgICAgICAgICAgIHJldHVybiBsb2NhbGVbdGV4dF07XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIHRleHQ7XG4gICAgfSxcbiAgICBsb2NhbGVzOiBsb2NhbGVzXG59O1xuXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvanMvbGliL2xvY2FsZXMuanNcbi8vIG1vZHVsZSBpZCA9IDBcbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwidmFyIE1vZGVscyA9IHJlcXVpcmUoJ2FwcC9tb2RlbHMnKTtcbnZhciBFbnVtcyA9IHJlcXVpcmUoJy4vZW51bXMnKTtcblxudmFyIFN0b3JhZ2UgPSBCYWNrYm9uZS5NYXJpb25ldHRlLk9iamVjdC5leHRlbmQoe1xuICAgIGF2YWlsYWJsZUN1cnJlbmN5VHlwZXM6IG51bGwsXG4gICAgYXZhaWxhYmxlUGxhdGZvcm1zOiBudWxsLFxuICAgIGF2YWlsYWJsZUdhbWVzOiBudWxsLFxuXG4gICAgb3B0aW9uczoge1xuICAgICAgICBhcGlfdXJsOiBudWxsLFxuICAgICAgICBkcmF3ZXJfdXJsOiBudWxsXG4gICAgfSxcblxuICAgIGluaXRpYWxpemU6IGZ1bmN0aW9uKCkge1xuICAgICAgICB0aGlzLmF2YWlsYWJsZUdhbWVzID0gbmV3IE1vZGVscy5HYW1lQ29sbGVjdGlvbigpO1xuICAgICAgICB0aGlzLmF2YWlsYWJsZU1vZGVzID0gbmV3IEJhY2tib25lLkNvbGxlY3Rpb24oW1xuICAgICAgICAgICAge21vZGU6IEVudW1zLk1vZGUuUkVBTCwgdGl0bGU6ICdSRUFMJ30sXG4gICAgICAgICAgICB7bW9kZTogRW51bXMuTW9kZS5GVU4sIHRpdGxlOiAnRlVOJ31cbiAgICAgICAgXSk7XG4gICAgICAgIHRoaXMuYXZhaWxhYmxlUGxhdGZvcm1zID0gbmV3IEJhY2tib25lLkNvbGxlY3Rpb24oW1xuICAgICAgICAgICAge3BsYXRmb3JtOiBFbnVtcy5QbGF0Zm9ybS5NT0JJTEUsIHRpdGxlOiAnTW9iaWxlJ30sXG4gICAgICAgICAgICB7cGxhdGZvcm06IEVudW1zLlBsYXRmb3JtLkRFU0tUT1AsIHRpdGxlOiAnRGVza3RvcCd9XG4gICAgICAgIF0pO1xuICAgICAgICB0aGlzLnJlcG9ydFR5cGVzID0gbmV3IEJhY2tib25lLkNvbGxlY3Rpb24oW1xuICAgICAgICAgICAge3VpZDogRW51bXMuUmVwb3J0VHlwZS5HR1IsIG5hbWU6ICdHR1InfSxcbiAgICAgICAgICAgIHt1aWQ6IEVudW1zLlJlcG9ydFR5cGUuTkdSLCBuYW1lOiAnTkdSJ30sXG4gICAgICAgICAgICB7dWlkOiBFbnVtcy5SZXBvcnRUeXBlLlBST01PLCBuYW1lOiAnUFJPTU8nfVxuICAgICAgICBdKVxuICAgIH0sXG5cbiAgICBmZXRjaEluaXRpYWxEYXRhOiBmdW5jdGlvbigpIHtcbiAgICAgICAgLy9yZXR1cm4gW107XG4gICAgICAgIHJldHVybiBbXG4gICAgICAgICAgICB0aGlzLmF2YWlsYWJsZUdhbWVzLmZldGNoKClcbiAgICAgICAgXTtcbiAgICB9XG59KTtcblxubW9kdWxlLmV4cG9ydHMgPSBuZXcgU3RvcmFnZSh3aW5kb3cuX1BST1ZJREVSKTtcblxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vc3JjL2pzL2FwcC9zdG9yYWdlLmpzXG4vLyBtb2R1bGUgaWQgPSAxXG4vLyBtb2R1bGUgY2h1bmtzID0gMCIsInZhciBVdGlscyA9IHtcbiAgICBlbmNvZGVRdWVyeURhdGE6IGZ1bmN0aW9uIChkYXRhKSB7XG4gICAgICAgIHZhciB2YWwsIHJldCA9IFtdO1xuICAgICAgICBmb3IgKHZhciBkIGluIGRhdGEpIHtcbiAgICAgICAgICAgIHZhbCA9IGRhdGFbZF07XG4gICAgICAgICAgICBpZih2YWwgPT09IHVuZGVmaW5lZCB8fCB2YWwgPT09IG51bGwpIHtcbiAgICAgICAgICAgICAgICB2YWwgPSAnJztcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmKHZhbCAmJiB2YWwuX2lzQU1vbWVudE9iamVjdCkge1xuICAgICAgICAgICAgICAgIHZhbCA9IHZhbC5mb3JtYXQoJ1lZWVlNTUREVEhIbW0nKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHJldC5wdXNoKGVuY29kZVVSSUNvbXBvbmVudChkKSArIFwiPVwiICsgZW5jb2RlVVJJQ29tcG9uZW50KHZhbCkpO1xuICAgICAgICB9XG4gICAgICAgIHJldHVybiByZXQuam9pbihcIiZcIik7XG4gICAgfSxcblxuICAgIHBhcnNlUXVlcnlEYXRhOiBmdW5jdGlvbiAocXVlcnkpIHtcbiAgICAgICAgdmFyIG1hdGNoLFxuICAgICAgICAgICAgcGwgICAgID0gL1xcKy9nLCAgLy8gUmVnZXggZm9yIHJlcGxhY2luZyBhZGRpdGlvbiBzeW1ib2wgd2l0aCBhIHNwYWNlXG4gICAgICAgICAgICBzZWFyY2ggPSAvKFteJj1dKyk9PyhbXiZdKikvZyxcbiAgICAgICAgICAgIGRlY29kZSA9IGZ1bmN0aW9uIChzKSB7IHJldHVybiBkZWNvZGVVUklDb21wb25lbnQocy5yZXBsYWNlKHBsLCBcIiBcIikpOyB9O1xuXG4gICAgICAgIHZhciB1cmxQYXJhbXMgPSB7fTtcbiAgICAgICAgd2hpbGUgKG1hdGNoID0gc2VhcmNoLmV4ZWMocXVlcnkgfHwgJycpKVxuICAgICAgICAgICB1cmxQYXJhbXNbZGVjb2RlKG1hdGNoWzFdKV0gPSBkZWNvZGUobWF0Y2hbMl0pO1xuICAgICAgICByZXR1cm4gdXJsUGFyYW1zO1xuICAgIH0sXG5cbiAgICBwYXJzZUhhc2g6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdmFyIGhhc2ggPSB3aW5kb3cubG9jYXRpb24uaGFzaC5zcGxpdCgnIycpWzFdO1xuICAgICAgICBpZiAoaGFzaCkgaGFzaCA9IGhhc2guc3BsaXQoJz8nKVswXTsgLy8gbGVnYWN5XG4gICAgICAgIHJldHVybiB0aGlzLnBhcnNlUXVlcnlEYXRhKGhhc2gpO1xuICAgIH0sXG5cbiAgICBnZXRDb29raWU6IGZ1bmN0aW9uIChuYW1lKSB7XG4gICAgICAgIHZhciBjb29raWVWYWx1ZSA9IG51bGw7XG4gICAgICAgIGlmIChkb2N1bWVudC5jb29raWUgJiYgZG9jdW1lbnQuY29va2llICE9PSAnJykge1xuICAgICAgICAgICAgdmFyIGNvb2tpZXMgPSBkb2N1bWVudC5jb29raWUuc3BsaXQoJzsnKTtcbiAgICAgICAgICAgIGZvciAodmFyIGkgPSAwOyBpIDwgY29va2llcy5sZW5ndGg7IGkrKykge1xuICAgICAgICAgICAgICAgIHZhciBjb29raWUgPSBqUXVlcnkudHJpbShjb29raWVzW2ldKTtcbiAgICAgICAgICAgICAgICAvLyBEb2VzIHRoaXMgY29va2llIHN0cmluZyBiZWdpbiB3aXRoIHRoZSBuYW1lIHdlIHdhbnQ/XG4gICAgICAgICAgICAgICAgaWYgKGNvb2tpZS5zdWJzdHJpbmcoMCwgbmFtZS5sZW5ndGggKyAxKSA9PT0gKG5hbWUgKyAnPScpKSB7XG4gICAgICAgICAgICAgICAgICAgIGNvb2tpZVZhbHVlID0gZGVjb2RlVVJJQ29tcG9uZW50KGNvb2tpZS5zdWJzdHJpbmcobmFtZS5sZW5ndGggKyAxKSk7XG4gICAgICAgICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gY29va2llVmFsdWU7XG4gICAgfSxcblxuICAgIHNldENvb2tpZTogZnVuY3Rpb24gKG5hbWUsIHZhbCkge1xuICAgICAgICB2YXIgZXhwaXJhdGlvbl9kYXRlID0gbmV3IERhdGUoKTtcbiAgICAgICAgdmFyIGNvb2tpZV9zdHJpbmcgPSAnJztcbiAgICAgICAgZXhwaXJhdGlvbl9kYXRlLnNldEZ1bGxZZWFyKGV4cGlyYXRpb25fZGF0ZS5nZXRGdWxsWWVhcigpICsgMSk7XG4gICAgICAgIC8vIEJ1aWxkIHRoZSBzZXQtY29va2llIHN0cmluZzpcbiAgICAgICAgY29va2llX3N0cmluZyA9IG5hbWUgKyBcIj1cIiArIHZhbCArIFwiOyBwYXRoPS87IGV4cGlyZXM9XCIgKyBleHBpcmF0aW9uX2RhdGUudG9HTVRTdHJpbmcoKTtcbiAgICAgICAgLy8gQ3JlYXRlL3VwZGF0ZSB0aGUgY29va2llOlxuICAgICAgICBkb2N1bWVudC5jb29raWUgPSBjb29raWVfc3RyaW5nO1xuICAgIH0sXG5cbiAgICBjc3JmU2FmZU1ldGhvZDogZnVuY3Rpb24gKG1ldGhvZCkge1xuICAgICAgICAvLyB0aGVzZSBIVFRQIG1ldGhvZHMgZG8gbm90IHJlcXVpcmUgQ1NSRiBwcm90ZWN0aW9uXG4gICAgICAgIHJldHVybiAoL14oR0VUfEhFQUR8T1BUSU9OU3xUUkFDRSkkLy50ZXN0KG1ldGhvZCkpO1xuICAgIH0sXG5cbiAgICBoZXJlRG9jOiBmdW5jdGlvbiAoZikge1xuICAgICAgICByZXR1cm4gZi50b1N0cmluZygpLlxuICAgICAgICAgICAgcmVwbGFjZSgvXlteXFwvXStcXC9cXCohPy8sICcnKS5cbiAgICAgICAgICAgIHJlcGxhY2UoL1xcKlxcL1teXFwvXSskLywgJycpO1xuICAgIH0sXG5cbiAgICBhc01vbmV5OiBmdW5jdGlvbih4KSB7XG4gICAgICAgIHggPSB4LnRvRml4ZWQoMikudG9TdHJpbmcoKTtcbiAgICAgICAgdmFyIHBhdHRlcm4gPSAvKC0/XFxkKykoXFxkezN9KS87XG4gICAgICAgIHdoaWxlIChwYXR0ZXJuLnRlc3QoeCkpXG4gICAgICAgICAgICB4ID0geC5yZXBsYWNlKHBhdHRlcm4sIFwiJDEgJDJcIik7XG4gICAgICAgIHJldHVybiB4O1xuICAgIH0sXG5cbiAgICBhc051bWJlcjogZnVuY3Rpb24oeCkge1xuICAgICAgICB4ID0geC50b1N0cmluZygpO1xuICAgICAgICB2YXIgcGF0dGVybiA9IC8oLT9cXGQrKShcXGR7M30pLztcbiAgICAgICAgd2hpbGUgKHBhdHRlcm4udGVzdCh4KSlcbiAgICAgICAgICAgIHggPSB4LnJlcGxhY2UocGF0dGVybiwgXCIkMSAkMlwiKTtcbiAgICAgICAgcmV0dXJuIHg7XG4gICAgfSxcbiAgICB0b1RaOiBmdW5jdGlvbihkdCwgZm9ybWF0LCB0eikge1xuICAgICAgICAvLyBHTVQrL0dNVC0gYXJlIGFjdHVhbGx5IHJldmVyc2VkIGZvciBQT1NJWCBjb21wYXRpYmlsaXR5IC4uLlxuICAgICAgICBpZiAoXy5jb250YWlucyh0eiwgJy0nKSkge1xuICAgICAgICAgICB0eiA9ICB0ei5yZXBsYWNlKC8tL2csICcrJyk7XG4gICAgICAgIH0gZWxzZSBpZiAoXy5jb250YWlucyh0eiwgJysnKSkge1xuICAgICAgICAgICAgdHogPSAgdHoucmVwbGFjZSgvXFwrL2csICctJyk7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIGR0ID8gbW9tZW50LnV0YyhkdCkudHoodHopLmZvcm1hdChmb3JtYXQpIDogJyZtZGFzaDsnO1xuICAgIH1cbn07XG5cbm1vZHVsZS5leHBvcnRzID0gVXRpbHM7XG5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL3NyYy9qcy9saWIvdXRpbHMuanNcbi8vIG1vZHVsZSBpZCA9IDJcbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwidmFyIFV0aWxzID0gcmVxdWlyZSgnbGliL3V0aWxzJyk7XG52YXIgbG9jYWxlcyA9IHJlcXVpcmUoJ2xpYi9sb2NhbGVzJyk7XG5cblxudmFyIEN1c3RvbUNlbGwgPSBNYXJpb25ldHRlLlZpZXcuZXh0ZW5kKHtcbiAgICBnZXRBdHRyVmFsdWU6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdmFyIGNvbHVtbiA9IHRoaXMuZ2V0T3B0aW9uKCdjb2x1bW4nKTtcbiAgICAgICAgcmV0dXJuIHRoaXMubW9kZWwuZ2V0KGNvbHVtbi5nZXQoJ2F0dHInKSk7XG4gICAgfSxcbiAgICB0ZW1wbGF0ZUNvbnRleHQ6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgcmV0dXJuIHtcbiAgICAgICAgICAgIHZhbDogdGhpcy5nZXRBdHRyVmFsdWUoKVxuICAgICAgICB9XG4gICAgfVxufSk7XG5cbnZhciBNb2JpbGVDZWxsID0gQ3VzdG9tQ2VsbC5leHRlbmQoe1xuICAgIGRpc3BsYXlOYW1lOiAnVmFsJyxcbiAgICBzaG93Q3VycmVuY3k6IGZhbHNlLFxuICAgIGN1cnJlbmN5QXR0cjogJ2N1cnJlbmN5JyxcbiAgICBzaG93U3Bpbm5lcjogZmFsc2UsXG4gICAgdGVtcGxhdGU6IF8udGVtcGxhdGUoW1xuICAgICAgICAnPHNwYW4gY2xhc3M9XCJ2aXNpYmxlLXhzLWlubGluZVwiPjxiPjwlPSBkaXNwbGF5TmFtZSAlPjogPC9iPjwvc3Bhbj4nLFxuICAgICAgICAnPCUgaWYgKHNob3dTcGlubmVyKSB7ICU+JyxcbiAgICAgICAgJyAgICA8JSBpZiAodmFsID09PSBudWxsKSB7ICU+JyxcbiAgICAgICAgJyAgICAgICAgPGkgY2xhc3M9XFwnZmEgZmEtc3BpbiBmYS1yZWZyZXNoXFwnPjwvaT4nLFxuICAgICAgICAnICAgIDwlIH0gZWxzZSB7ICU+JyxcbiAgICAgICAgJyAgICAgICAgPCU9IHZhbCAlPicsXG4gICAgICAgICcgICAgPCUgfSAlPicsXG4gICAgICAgICc8JSB9IGVsc2UgeyAlPicsXG4gICAgICAgICcgICAgPCU9IF8uaXNOdWxsKHZhbCkgPyBcIiZtZGFzaDtcIiA6IHZhbCAlPicsXG4gICAgICAgICc8JSB9ICU+JyxcbiAgICAgICAgJycsXG4gICAgICAgICc8JSBpZiAodmFsICYmIHNob3dDdXJyZW5jeSkgeyAlPicsXG4gICAgICAgICcgICAgPHNwYW4gY2xhc3M9XCJ2aXNpYmxlLXhzLWlubGluZVwiPiA8JT0gY3VycmVuY3lGaWVsZCAlPjwvc3Bhbj4nLFxuICAgICAgICAnPCUgfSAlPidcbiAgICBdLmpvaW4oJ1xcbicpKSxcbiAgICB0ZW1wbGF0ZUNvbnRleHQ6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdmFyIHZhbCA9IHRoaXMuZ2V0QXR0clZhbHVlKCksXG4gICAgICAgICAgICBkaXNwbGF5TmFtZSA9IHRoaXMuZGlzcGxheU5hbWUsXG4gICAgICAgICAgICBzaG93U3Bpbm5lciA9IHRoaXMuc2hvd1NwaW5uZXIsXG4gICAgICAgICAgICBzaG93Q3VycmVuY3kgPSB0aGlzLnNob3dDdXJyZW5jeSxcbiAgICAgICAgICAgIGN1cnJlbmN5RmllbGQgPSB0aGlzLm1vZGVsLmdldCh0aGlzLmN1cnJlbmN5QXR0cik7XG5cbiAgICAgICAgaWYgKHZhbCA9PT0gJ0xPQ0tFRCcgfHwgdmFsID09PSAnRVhDRUVEJyB8fCB2YWwgPT09ICc/Jykge1xuICAgICAgICAgICAgc2hvd0N1cnJlbmN5ID0gZmFsc2U7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIHtcbiAgICAgICAgICAgIHZhbDogdmFsLFxuICAgICAgICAgICAgZGlzcGxheU5hbWU6IGxvY2FsZXMudHJhbnNsYXRlKGRpc3BsYXlOYW1lKSxcbiAgICAgICAgICAgIHNob3dTcGlubmVyOiBzaG93U3Bpbm5lcixcbiAgICAgICAgICAgIHNob3dDdXJyZW5jeTogc2hvd0N1cnJlbmN5LFxuICAgICAgICAgICAgY3VycmVuY3lGaWVsZDogY3VycmVuY3lGaWVsZFxuICAgICAgICB9XG4gICAgfVxufSk7XG5cbnZhciBGaXhlZEhlYWRlckdyaWRWaWV3ID0gTWFHcmlkLkdyaWRWaWV3LmV4dGVuZCh7XG4gICAgY29sbGVjdGlvbkV2ZW50czoge1xuICAgICAgICBzeW5jOiAnaW52YWxpZGF0ZUhlYWRlcicsXG4gICAgICAgIHJlc2V0OiAnaW52YWxpZGF0ZUhlYWRlcicsXG4gICAgICAgIGFkZDogJ2ludmFsaWRhdGVIZWFkZXInLFxuICAgICAgICByZW1vdmU6ICdpbnZhbGlkYXRlSGVhZGVyJ1xuICAgIH0sXG5cbiAgICBpbml0aWFsaXplOiBmdW5jdGlvbigpIHtcbiAgICAgICAgTWFHcmlkLkdyaWRWaWV3LnByb3RvdHlwZS5pbml0aWFsaXplLmFwcGx5KHRoaXMsIGFyZ3VtZW50cyk7XG5cbiAgICAgICAgLy8gYmluZCB0aGlzIHRvIGV2ZW50IGhhbmRsZXJzXG4gICAgICAgIHRoaXMub25TY3JvbGwgPSBfLmJpbmQodGhpcy5vblNjcm9sbCwgdGhpcyk7XG4gICAgICAgIHRoaXMub25SZXNpemUgPSBfLmJpbmQodGhpcy5vblJlc2l6ZSwgdGhpcyk7XG4gICAgfSxcbiAgICBvblJlbmRlcjogZnVuY3Rpb24gKCkge1xuICAgICAgICBNYUdyaWQuR3JpZFZpZXcucHJvdG90eXBlLm9uUmVuZGVyLmFwcGx5KHRoaXMsIGFyZ3VtZW50cyk7XG5cbiAgICAgICAgdGhpcy4kZWwuYWRkQ2xhc3MoJ2ZpeGVkLWhlYWRlcicpO1xuICAgICAgICB0aGlzLiRlbC5hZGRDbGFzcygndGFibGUtc3RhY2thYmxlJyk7XG4gICAgICAgIHRoaXMudGFibGUgPSB0aGlzLiRlbC5maW5kKCd0YWJsZScpO1xuXG4gICAgICAgIHZhciAkaGVhZGVyX3JvdyA9IHRoaXMuJGVsLmZpbmQoJ3RoZWFkIHRyJyksXG4gICAgICAgICAgICAkY2xvbmVkX3JvdyA9ICRoZWFkZXJfcm93LmNsb25lKCk7XG4gICAgICAgICRjbG9uZWRfcm93LmFkZENsYXNzKCdoaWRkZW4tcm93Jyk7XG4gICAgICAgICRoZWFkZXJfcm93LmFmdGVyKCRjbG9uZWRfcm93KTtcblxuICAgICAgICAvLyB1bnN1YnNjcmliZVxuICAgICAgICAkKHdpbmRvdykub2ZmKCdzY3JvbGwnLCB0aGlzLm9uU2Nyb2xsKTtcbiAgICAgICAgJCh3aW5kb3cpLm9mZigncmVzaXplJywgdGhpcy5vblJlc2l6ZSk7XG4gICAgICAgIC8vIHN1YnNjcmliZS4gJ3RoaXMnIGlzIGFscmVhZHkgYm91bmQgdG8gaGFuZGxlcnNcbiAgICAgICAgJCh3aW5kb3cpLm9uKCdzY3JvbGwnLCB0aGlzLm9uU2Nyb2xsKTtcbiAgICAgICAgJCh3aW5kb3cpLm9uKCdyZXNpemUnLCB0aGlzLm9uUmVzaXplKTtcblxuICAgICAgICBzZXRUaW1lb3V0KF8uYmluZCh0aGlzLmludmFsaWRhdGVIZWFkZXIsIHRoaXMpLCAxKTtcbiAgICB9LFxuICAgIG9uU2Nyb2xsOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHZhciBmaXhlZEhlYWRlciA9IHRoaXMuJGVsO1xuICAgICAgICB2YXIgb2Zmc2V0VG9wID0gZml4ZWRIZWFkZXIub2Zmc2V0KCkudG9wO1xuICAgICAgICBpZiAod2luZG93LnNjcm9sbFkgPiBvZmZzZXRUb3ApIHtcbiAgICAgICAgICAgIGZpeGVkSGVhZGVyLmFkZENsYXNzKCdzY3JvbGxlZCcpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgZml4ZWRIZWFkZXIucmVtb3ZlQ2xhc3MoJ3Njcm9sbGVkJyk7XG4gICAgICAgIH1cbiAgICB9LFxuXG4gICAgb25SZXNpemU6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdGhpcy5pbnZhbGlkYXRlSGVhZGVyKCk7XG4gICAgfSxcblxuICAgIGludmFsaWRhdGVIZWFkZXI6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdGhpcy50YWJsZS5maW5kKCd0aGVhZCB0cjpmaXJzdC1jaGlsZCB0aD5kaXYnKS5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciAkZGl2ID0gJCh0aGlzKSxcbiAgICAgICAgICAgICAgICAkdGggPSAkZGl2LnBhcmVudCgpLFxuICAgICAgICAgICAgICAgIG5ld193aWR0aCA9ICR0aC53aWR0aCgpLFxuICAgICAgICAgICAgICAgIG9sZF93aWR0aCA9ICRkaXYud2lkdGgoKTtcblxuICAgICAgICAgICAgaWYgKG5ld193aWR0aCAhPT0gb2xkX3dpZHRoKSB7XG4gICAgICAgICAgICAgICAgJGRpdi5jc3Moe1xuICAgICAgICAgICAgICAgICAgICB3aWR0aDogbmV3X3dpZHRoXG4gICAgICAgICAgICAgICAgfSlcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSlcbiAgICB9XG5cblxufSk7XG5cbnZhciBEYXRlVGltZUNlbGwgPSBDdXN0b21DZWxsLmV4dGVuZCh7XG4gICAgZGF0ZUZvcm1hdDogJ0RELk1NLllZWVkgSEg6bW06c3MnLFxuICAgIHRlbXBsYXRlQ29udGV4dDogZnVuY3Rpb24gKCkge1xuICAgICAgICByZXR1cm4ge1xuICAgICAgICAgICAgZm9ybWF0OiB0aGlzLmdldE9wdGlvbignZGF0ZUZvcm1hdCcpLFxuICAgICAgICAgICAgdmFsOiB0aGlzLmdldEF0dHJWYWx1ZSgpXG4gICAgICAgIH07XG4gICAgfSxcbiAgICB0ZW1wbGF0ZTogXy50ZW1wbGF0ZShbXG4gICAgICAgIFwiPCU9IHZhbCA/IG1vbWVudCh2YWwpLnV0YygpLmZvcm1hdChmb3JtYXQpLnJlcGxhY2UoJyAnLCAnJm5ic3A7JykgOiAnJm1kYXNoOycgJT5cIlxuICAgIF0uam9pbignJykpXG59KTtcblxudmFyIFBlcmNlbnRDZWxsID0gQ3VzdG9tQ2VsbC5leHRlbmQoe1xuICAgIHRlbXBsYXRlQ29udGV4dDogZnVuY3Rpb24gKCkge1xuICAgICAgICB2YXIgdmFsID0gdGhpcy5nZXRBdHRyVmFsdWUoKTtcbiAgICAgICAgcmV0dXJuIHtcbiAgICAgICAgICAgIHZhbDogKHZhbCB8fCAwKS50b0ZpeGVkKDIpXG4gICAgICAgIH07XG4gICAgfSxcbiAgICB0ZW1wbGF0ZTogXy50ZW1wbGF0ZShbXG4gICAgICAgIFwiPCU9IHZhbCAlPiAlXCJcbiAgICBdLmpvaW4oJycpKVxufSk7XG5cbnZhciBGaXhlZEhlYWRlckNlbGxNdWx0aWxpbmVGb290ZXIgPSBGaXhlZEhlYWRlckdyaWRWaWV3LmV4dGVuZCh7XG4gICAgZm9vdGVyQ29sdW1uczogbnVsbCxcbiAgICBmb290ZXJDb2xsZWN0aW9uOiBudWxsLFxuXG4gICAgaW5pdGlhbGl6ZTogZnVuY3Rpb24oKSB7XG4gICAgICAgIEZpeGVkSGVhZGVyR3JpZFZpZXcucHJvdG90eXBlLmluaXRpYWxpemUuYXBwbHkodGhpcywgYXJndW1lbnRzKTtcbiAgICAgICAgdGhpcy5mb290ZXJDb2x1bW5zID0gbmV3IEJhY2tib25lLkNvbGxlY3Rpb24odGhpcy5fcGFyc2VDb2x1bW5zKHRoaXMuZm9vdGVyQ29sdW1ucykpO1xuICAgICAgICB0aGlzLmZvb3RlckNvbGxlY3Rpb24gPSB0aGlzLmdldE9wdGlvbignZm9vdGVyQ29sbGVjdGlvbicpO1xuICAgIH0sXG5cbiAgICBfcmVuZGVyRm9vdGVyOiBmdW5jdGlvbigpIHtcbiAgICAgICAgdmFyIEZvb3RlclZpZXdDbHMgPSB0aGlzLmdldE9wdGlvbignZm9vdGVyVmlldycpO1xuICAgICAgICBpZiAoIUZvb3RlclZpZXdDbHMpIHJldHVybjtcbiAgICAgICAgdGhpcy5zaG93Q2hpbGRWaWV3KCdmb290ZXInLCBuZXcgRm9vdGVyVmlld0Nscyh7XG4gICAgICAgICAgICBjb2xsZWN0aW9uOiB0aGlzLmZvb3RlckNvbGxlY3Rpb24sXG4gICAgICAgICAgICBjb2x1bW5zOiB0aGlzLmZvb3RlckNvbHVtbnNcbiAgICAgICAgfSkpO1xuICAgIH0sXG5cbiAgICBmb290ZXJWaWV3OiBNYUdyaWQuQm9keVZpZXcuZXh0ZW5kKHtcbiAgICAgICAgdGFnTmFtZTogJ3Rmb290JyxcbiAgICAgICAgY2hpbGRWaWV3RXZlbnRQcmVmaXg6ICdmb290ZXInLFxuXG4gICAgICAgIGNoaWxkVmlldzogTWFHcmlkLkRhdGFSb3dWaWV3LFxuICAgICAgICBlbXB0eVZpZXc6IE1hR3JpZC5FbXB0eVJvd1ZpZXdcbiAgICB9KVxufSk7XG5cblxubW9kdWxlLmV4cG9ydHMgPSB7XG4gICAgQ3VzdG9tQ2VsbDogQ3VzdG9tQ2VsbCxcbiAgICBNb2JpbGVDZWxsOiBNb2JpbGVDZWxsLFxuXG4gICAgRGF0ZVRpbWVDZWxsOiBEYXRlVGltZUNlbGwsXG4gICAgRml4ZWRIZWFkZXJHcmlkVmlldzogRml4ZWRIZWFkZXJHcmlkVmlldyxcbiAgICBGaXhlZEhlYWRlckNlbGxNdWx0aWxpbmVGb290ZXI6IEZpeGVkSGVhZGVyQ2VsbE11bHRpbGluZUZvb3RlcixcblxuICAgIFBlcmNlbnRDZWxsOiBQZXJjZW50Q2VsbFxufTtcblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL3NyYy9qcy9saWIvZ3JpZC5qc1xuLy8gbW9kdWxlIGlkID0gM1xuLy8gbW9kdWxlIGNodW5rcyA9IDAiLCJ2YXIgTW9kZWwgPSByZXF1aXJlKCdsaWIvbW9kZWwnKTtcblxudmFyIFRvdGFsc0NvbGxlY3Rpb25Ob0V4Y2VlZCA9IE1vZGVsLlRvdGFsc0NvbGxlY3Rpb24uZXh0ZW5kKHtcbiAgICBwcm9jZXNzU291cmNlOiBmdW5jdGlvbihzb3VyY2UpIHtcbiAgICAgICAgcmV0dXJuIHNvdXJjZS5maWx0ZXIoZnVuY3Rpb24obW9kZWwpIHtcbiAgICAgICAgICAgIHJldHVybiBtb2RlbC5nZXQoJ3N0YXR1cycpID09PSAnT0snO1xuICAgICAgICB9KTtcbiAgICB9XG59KTtcblxudmFyIFBhZ2VhYmxlQ29sbGVjdGlvbiA9IEJhY2tib25lLlBhZ2VhYmxlQ29sbGVjdGlvbi5leHRlbmQoe1xuICAgIHBhcnNlUmVjb3JkczogZnVuY3Rpb24ocmVzcCkge1xuICAgICAgICByZXR1cm4gcmVzcC5pdGVtcztcbiAgICB9XG59KTtcblxudmFyIEdhbWVNb2RlbCA9IEJhY2tib25lLk1vZGVsLmV4dGVuZCh7XG4gICAgaWRBdHRyaWJ1dGU6ICd1aWQnXG59KTtcblxudmFyIEdhbWVDb2xsZWN0aW9uID0gQmFja2JvbmUuQ29sbGVjdGlvbi5leHRlbmQoe1xuICAgIHVybDogJ2dhbWUvbGlzdC8nLFxuICAgIG1vZGVsOiBHYW1lTW9kZWwsXG5cbiAgICBjb21wYXJhdG9yOiBmdW5jdGlvbihtb2RlbEEsIG1vZGVsQikge1xuICAgICAgICByZXR1cm4gbW9kZWxBLmdldCgnbmFtZScpLmxvY2FsZUNvbXBhcmUobW9kZWxCLmdldCgnbmFtZScpKTtcbiAgICB9XG59KTtcblxudmFyIEN1cnJlbmN5TW9kZWwgPSBCYWNrYm9uZS5Nb2RlbC5leHRlbmQoKTtcblxudmFyIEN1cnJlbmN5Q29sbGVjdGlvbiA9IEJhY2tib25lLkNvbGxlY3Rpb24uZXh0ZW5kKHtcbiAgICB1cmw6ICdjdXJyZW5jeS9saXN0LycsXG4gICAgbW9kZWw6IEN1cnJlbmN5TW9kZWxcbn0pO1xuXG52YXIgUGxheWVyR2FtZU1vZGVsID0gQmFja2JvbmUuTW9kZWwuZXh0ZW5kKHtcbiAgICBpZEF0dHJpYnV0ZTogXCJfaWRcIixcbiAgICBjb21wYXJhdG9yOiBcImdhbWVfaWRcIixcbiAgICB1cmw6ICdwbGF5ZXJnYW1lL2FnZ3JlZ2F0ZS8nLFxuICAgIGRlZmF1bHRzOiB7XG4gICAgICAgIHJvdW5kczogbnVsbCxcbiAgICAgICAgYmV0czogbnVsbCxcbiAgICAgICAgd2luczogbnVsbCxcbiAgICAgICAgcHJvZml0OiBudWxsLFxuICAgICAgICBvdXRjb21lOiBudWxsLFxuICAgICAgICBwYXlvdXQ6IG51bGxcbiAgICB9LFxuICAgIGdldFF1ZXJ5UGFyYW1zOiBmdW5jdGlvbigpIHtcbiAgICAgICAgcmV0dXJuIHtcbiAgICAgICAgICAgIGdhbWVfaWQ6IHRoaXMuZ2V0KCdnYW1lX2lkJyksXG4gICAgICAgICAgICBjdXJyZW5jeTogdGhpcy5nZXQoJ2N1cnJlbmN5JyksXG4gICAgICAgICAgICBtb2RlOiB0aGlzLmdldCgnbW9kZScpXG4gICAgICAgIH1cbiAgICB9LFxuICAgIHBhcnNlOiBmdW5jdGlvbihyZXNwKSB7XG4gICAgICAgIGlmIChyZXNwLnJvdW5kcyA9PSBudWxsKSB7XG4gICAgICAgICAgICBfLmRlZmVyKF8uYmluZCh0aGlzLmxvYWRFeHRyYURhdGEsIHRoaXMpKTtcbiAgICAgICAgfVxuICAgICAgICB0aGlzLnNldCgnX2lkJywgW3RoaXMuZ2V0KCdnYW1lX2lkJyksIHRoaXMuZ2V0KCdtb2RlJyksIHRoaXMuZ2V0KCdjdXJyZW5jeScpXS5qb2luKCdfJykpO1xuICAgICAgICByZXR1cm4gQmFja2JvbmUuTW9kZWwucHJvdG90eXBlLnBhcnNlLmFwcGx5KHRoaXMsIGFyZ3VtZW50cyk7XG5cbiAgICB9LFxuICAgIGxvYWRFeHRyYURhdGE6IGZ1bmN0aW9uKCkge1xuICAgICAgICB2YXIgZGF0YSA9IF8uZXh0ZW5kKHt9LCB0aGlzLmNvbGxlY3Rpb24ubGFzdEZpbHRlcnMsIHRoaXMuZ2V0UXVlcnlQYXJhbXMoKSk7XG4gICAgICAgIHRoaXMuZmV0Y2goe1xuICAgICAgICAgICAgZGF0YTogZGF0YVxuICAgICAgICB9KVxuICAgIH1cbn0pO1xuXG52YXIgUGxheWVySW5mb01vZGVsID0gQmFja2JvbmUuTW9kZWwuZXh0ZW5kKHtcbiAgICB1cmw6ICdwbGF5ZXIvZ2V0X2luZm8vJyxcbiAgICBkZWZhdWx0czoge1xuICAgICAgICBwbGF5ZXJfaWQ6IG51bGwsXG4gICAgICAgIHByb2plY3RfbmFtZTogbnVsbCxcbiAgICAgICAgcHJvdmlkZXJfbmFtZTogbnVsbCxcbiAgICAgICAgYnJhbmQ6IG51bGxcbiAgICB9LFxufSk7XG5cbmZ1bmN0aW9uIGNhbGN1bGF0ZVBheW91dCh3aW4sIGJldCkge1xuICAgIGlmIChiZXQgPT09IG51bGwgfHwgd2luID09PSBudWxsKSB7XG4gICAgICAgIC8vIG5vdCBsb2FkZWQgeWV0IC0gc2hvdyBzcGlubmVyXG4gICAgICAgIHJldHVybiBudWxsO1xuICAgIH0gZWxzZSBpZiAoIWJldCB8fCBiZXQuaXNFcXVhbFRvKDApKSB7XG4gICAgICAgIC8vIHplcm8gYmV0cyAtIGhpZGUgcGF5b3V0XG4gICAgICAgIHJldHVybiB1bmRlZmluZWQ7XG4gICAgfSBlbHNlIHtcbiAgICAgICAgcmV0dXJuIHdpblxuICAgICAgICAgICAgLnNoaWZ0ZWRCeSgyKVxuICAgICAgICAgICAgLmRpdmlkZWRCeShiZXQpXG4gICAgICAgICAgICAudG9GaXhlZCgyLCBCaWdOdW1iZXIuUk9VTkRfRE9XTik7XG4gICAgfVxufVxuXG4vLyBjb2xsZWN0aW9uIHdpdGhvdXQgcGFnaW5hdGlvblxudmFyIFBsYXllckdhbWVDb2xsZWN0aW9uID0gTW9kZWwuUXVlcnlQYXJhbXNDb2xsZWN0aW9uLmV4dGVuZCh7XG4gICAgdXJsOiAncGxheWVyZ2FtZS9saXN0LycsXG4gICAgbW9kZWw6IFBsYXllckdhbWVNb2RlbFxufSk7XG5cbnZhciBQbGF5ZXJHYW1lVG90YWxzQ29sbGVjdGlvbiA9IE1vZGVsLlRvdGFsc0NvbGxlY3Rpb24uZXh0ZW5kKHtcbiAgICBncm91cEJ5OiAnY3VycmVuY3knLFxuXG4gICAgbnVsbElmTm9WYWx1ZXM6IHRydWUsXG4gICAgbnVsbElmSGFzTnVsbHM6IHRydWUsXG5cbiAgICBwcm9jZXNzU291cmNlOiBmdW5jdGlvbihzb3VyY2UpIHtcbiAgICAgICAgcmV0dXJuIHNvdXJjZS5maWx0ZXIoZnVuY3Rpb24obW9kZWwpIHtcbiAgICAgICAgICAgIHJldHVybiBtb2RlbC5nZXQoJ3JvdW5kcycpICogMSA+IDAgfHxcbiAgICAgICAgICAgICAgICAgICBtb2RlbC5nZXQoJ2JldHMnKSAqIDEgPiAwIHx8XG4gICAgICAgICAgICAgICAgICAgbW9kZWwuZ2V0KCd3aW5zJykgKiAxID4gMDtcbiAgICAgICAgfSk7XG4gICAgfSxcblxuICAgIGZpZWxkczogW3tcbiAgICAgICAgbmFtZTogJ3JvdW5kcycsXG4gICAgICAgIHZhbHVlOiAnc3VtJ1xuICAgIH0sIHtcbiAgICAgICAgbmFtZTogJ2JldHMnLFxuICAgICAgICB2YWx1ZTogJ3N1bSdcbiAgICB9LCB7XG4gICAgICAgIG5hbWU6ICd3aW5zJyxcbiAgICAgICAgdmFsdWU6ICdzdW0nXG4gICAgfSwge1xuICAgICAgICBuYW1lOiAncHJvZml0JyxcbiAgICAgICAgdmFsdWU6ICdzdW0nXG4gICAgfSwge1xuICAgICAgICBuYW1lOiAnb3V0Y29tZScsXG4gICAgICAgIHZhbHVlOiAnc3VtJ1xuICAgIH0sIHtcbiAgICAgICAgbmFtZTogJ3BheW91dCcsXG4gICAgICAgIHZhbHVlOiBmdW5jdGlvbihtb2RlbHMpIHtcbiAgICAgICAgICAgIHZhciB3aW5zID0gdGhpcy5zdW0obW9kZWxzLCAnd2lucycpLFxuICAgICAgICAgICAgICAgIGJldHMgPSB0aGlzLnN1bShtb2RlbHMsICdiZXRzJyk7XG4gICAgICAgICAgICByZXR1cm4gY2FsY3VsYXRlUGF5b3V0KHdpbnMsIGJldHMpO1xuICAgICAgICB9XG4gICAgfV1cbn0pO1xuXG5cbnZhciBUcmFuc2FjdGlvbk1vZGVsID0gQmFja2JvbmUuTW9kZWwuZXh0ZW5kKHtcbiAgICBkZWZhdWx0czoge1xuICAgICAgICBpc19ib251czogbnVsbFxuICAgIH1cbn0pO1xuXG52YXIgVHJhbnNhY3Rpb25Db2xsZWN0aW9uID0gTWFHcmlkLlNlcXVlbnRDb2xsZWN0aW9uLmV4dGVuZCh7XG4gICAgdXJsOiAndHJhbnNhY3Rpb24vbGlzdC8nLFxuICAgIG1vZGVsOiBUcmFuc2FjdGlvbk1vZGVsXG59KTtcblxudmFyIFRyYW5zYWN0aW9uVG90YWxzQ29sbGVjdGlvbiA9IFRvdGFsc0NvbGxlY3Rpb25Ob0V4Y2VlZC5leHRlbmQoe1xuICAgIGdyb3VwQnk6ICdjdXJyZW5jeScsXG5cbiAgICBudWxsSWZOb1ZhbHVlczogdHJ1ZSxcbiAgICBudWxsSWZIYXNOdWxsczogZmFsc2UsXG5cbiAgICBmaWVsZHM6IFt7XG4gICAgICAgIG5hbWU6ICdiZXQnLFxuICAgICAgICB2YWx1ZTogJ3N1bSdcbiAgICB9LCB7XG4gICAgICAgIG5hbWU6ICd3aW4nLFxuICAgICAgICB2YWx1ZTogJ3N1bSdcbiAgICB9LCB7XG4gICAgICAgIG5hbWU6ICdwcm9maXQnLFxuICAgICAgICB2YWx1ZTogJ3N1bSdcbiAgICB9LCB7XG4gICAgICAgIG5hbWU6ICdvdXRjb21lJyxcbiAgICAgICAgdmFsdWU6ICdzdW0nXG4gICAgfSwge1xuICAgICAgICBuYW1lOiAncm91bmRfaWQnLFxuICAgICAgICB2YWx1ZTogJ2NvdW50VW5pcSdcbiAgICB9XVxufSk7XG5cbnZhciBSb3VuZFRyYW5zYWN0aW9uQ29sbGVjdGlvbiA9IE1vZGVsLlF1ZXJ5UGFyYW1zQ29sbGVjdGlvbi5leHRlbmQoe1xuICAgIHVybDogJ3RyYW5zYWN0aW9uL2xpc3QvJyxcbiAgICBtb2RlbDogVHJhbnNhY3Rpb25Nb2RlbFxufSk7XG5cbnZhciBSb3VuZFRyYW5zYWN0aW9uVG90YWxzQ29sbGVjdGlvbiA9IFRvdGFsc0NvbGxlY3Rpb25Ob0V4Y2VlZC5leHRlbmQoe1xuICAgIGdyb3VwQnk6ICdjdXJyZW5jeScsXG5cbiAgICBudWxsSWZOb1ZhbHVlczogdHJ1ZSxcbiAgICBudWxsSWZIYXNOdWxsczogZmFsc2UsXG5cbiAgICBmaWVsZHM6IFt7XG4gICAgICAgIG5hbWU6ICdiZXRzJyxcbiAgICAgICAgc291cmNlOiAnYmV0JyxcbiAgICAgICAgdmFsdWU6ICdzdW0nXG4gICAgfSwge1xuICAgICAgICBuYW1lOiAnd2lucycsXG4gICAgICAgIHNvdXJjZTogJ3dpbicsXG4gICAgICAgIHZhbHVlOiAnc3VtJ1xuICAgIH0sIHtcbiAgICAgICAgbmFtZTogJ3Byb2ZpdHMnLFxuICAgICAgICB2YWx1ZTogJ3N1bSdcbiAgICB9LCB7XG4gICAgICAgIG5hbWU6ICdvdXRjb21lJyxcbiAgICAgICAgdmFsdWU6ICdzdW0nXG4gICAgfSwge1xuICAgICAgICBuYW1lOiAncm91bmRzJyxcbiAgICAgICAgc291cmNlOiAncm91bmRfaWQnLFxuICAgICAgICB2YWx1ZTogJ2NvdW50VW5pcSdcbiAgICB9LCB7XG4gICAgICAgIG5hbWU6ICdwYXlvdXQnLFxuICAgICAgICB2YWx1ZTogZnVuY3Rpb24obW9kZWxzKSB7XG4gICAgICAgICAgICB2YXIgd2luID0gdGhpcy5zdW0obW9kZWxzLCAnd2luJyksXG4gICAgICAgICAgICAgICAgYmV0ID0gdGhpcy5zdW0obW9kZWxzLCAnYmV0Jyk7XG4gICAgICAgICAgICByZXR1cm4gY2FsY3VsYXRlUGF5b3V0KHdpbiwgYmV0KTtcbiAgICAgICAgfVxuICAgIH1dXG59KTtcblxubW9kdWxlLmV4cG9ydHMgPSB7XG4gICAgUGxheWVyR2FtZU1vZGVsOiBQbGF5ZXJHYW1lTW9kZWwsXG4gICAgUGxheWVyR2FtZUNvbGxlY3Rpb246IFBsYXllckdhbWVDb2xsZWN0aW9uLFxuICAgIFBsYXllckdhbWVUb3RhbHNDb2xsZWN0aW9uOiBQbGF5ZXJHYW1lVG90YWxzQ29sbGVjdGlvbixcbiAgICBUcmFuc2FjdGlvbkNvbGxlY3Rpb246IFRyYW5zYWN0aW9uQ29sbGVjdGlvbixcbiAgICBUcmFuc2FjdGlvblRvdGFsc0NvbGxlY3Rpb246IFRyYW5zYWN0aW9uVG90YWxzQ29sbGVjdGlvbixcbiAgICBSb3VuZFRyYW5zYWN0aW9uQ29sbGVjdGlvbjogUm91bmRUcmFuc2FjdGlvbkNvbGxlY3Rpb24sXG4gICAgUm91bmRUcmFuc2FjdGlvblRvdGFsc0NvbGxlY3Rpb246IFJvdW5kVHJhbnNhY3Rpb25Ub3RhbHNDb2xsZWN0aW9uLFxuICAgIEdhbWVDb2xsZWN0aW9uOiBHYW1lQ29sbGVjdGlvbixcbiAgICBDdXJyZW5jeUNvbGxlY3Rpb246IEN1cnJlbmN5Q29sbGVjdGlvbixcbiAgICBQbGF5ZXJJbmZvTW9kZWw6IFBsYXllckluZm9Nb2RlbFxufTtcblxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vc3JjL2pzL2FwcC9tb2RlbHMuanNcbi8vIG1vZHVsZSBpZCA9IDRcbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwidmFyIEVudW1zID0gcmVxdWlyZSgnLi9lbnVtcycpO1xuXG52YXIgZGVmYXVsdHMgPSB7XG4gICAgLy8gYWxsXG4gICAgaGVhZGVyOiAnMScsXG4gICAgdG90YWxzOiAnMScsXG4gICAgLy8gaW5mbyBpcyB1c2VkIGJ5IHRyYW5zYWN0aW9ucyArIHJvdW5kLCBidXQgbmVlZHMgdG8gYmUgaGVyZSwgc28gd2UgY2FuIHNldCBpbiBldmVuIG9uIHBsYXllcmdhbWVzXG4gICAgaW5mbzogJzAnLFxuICAgIHR6OiBtb21lbnQoKS51dGNPZmZzZXQoKSxcbiAgICBsYW5nOiAnZW4nLFxuICAgIHJlcG9ydF90eXBlOiBFbnVtcy5SZXBvcnRUeXBlLkdHUixcblxuICAgIC8vIHBsYXllcmdhbWVzXG4gICAgcmVhbHRpbWU6ICcxJyxcblxuICAgIC8vIHRyYW5zYWN0aW9uc1xuICAgIGN1cnJlbmN5OiAnJyxcbiAgICBwZXJfcGFnZTogMTAwLFxuICAgIGV4Y2VlZHM6ICcxJyxcblxuICAgIC8vIHJvdW5kXG4gICAgYmFsYW5jZTogJzAnLFxuXG4gICAgLy8gcGxheWVyZ2FtZXMgKyB0cmFuc2FjdGlvbnNcbiAgICBnYW1lX2lkOiAnJyxcbiAgICBzdGFydF9kYXRlOiAnJyxcbiAgICBlbmRfZGF0ZTogJycsXG4gICAgbW9kZTogRW51bXMuTW9kZS5SRUFMLFxuXG4gICAgLy8gcGxheWVyZ2FtZXMgKyByb3VuZFxuXG4gICAgLy8gdHJhbnNhY3Rpb25zICsgcm91bmRcbiAgICByb3VuZF9pZDogJycsXG59O1xuXG5tb2R1bGUuZXhwb3J0cyA9IGRlZmF1bHRzO1xuXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvanMvYXBwL2RlZmF1bHRzLmpzXG4vLyBtb2R1bGUgaWQgPSA1XG4vLyBtb2R1bGUgY2h1bmtzID0gMCIsInZhciBGb3JtQmVoYXZpb3IgPSBNbi5CZWhhdmlvci5leHRlbmQoe1xuICAgIGRlZmF1bHRzOiB7XG4gICAgICAgIHN1Ym1pdEV2ZW50OiAnZm9ybTpzdWJtaXQnLFxuICAgICAgICBlcnJvckNsczogJ2hhcy1lcnJvcicsXG4gICAgICAgIGZvcm06ICdmb3JtJyxcbiAgICAgICAgc3VibWl0QnRuOiAnLnN1Ym1pdC1idG4nLFxuICAgICAgICBmaWVsZEdyb3VwczogJy5mb3JtLWdyb3VwJyxcbiAgICAgICAgZXJyb3JHcm91cHM6ICcuZmllbGQtZXJyb3InLFxuICAgICAgICBub25GaWVsZEVycm9yOiAnLmZvcm0tZXJyb3InLFxuICAgICAgICB2YWxpZGF0b3JzOiB7fVxuICAgIH0sXG5cbiAgICB1aTogZnVuY3Rpb24oKSB7XG4gICAgICAgIHJldHVybiB7XG4gICAgICAgICAgICBmb3JtOiB0aGlzLmdldE9wdGlvbignZm9ybScpLFxuICAgICAgICAgICAgc3VibWl0QnRuOiB0aGlzLmdldE9wdGlvbignc3VibWl0QnRuJyksXG4gICAgICAgICAgICBmaWVsZEdyb3VwczogdGhpcy5nZXRPcHRpb24oJ2ZpZWxkR3JvdXBzJyksXG4gICAgICAgICAgICBlcnJvckdyb3VwczogdGhpcy5nZXRPcHRpb24oJ2Vycm9yR3JvdXBzJyksXG4gICAgICAgICAgICBub25GaWVsZEVycm9yOiB0aGlzLmdldE9wdGlvbignbm9uRmllbGRFcnJvcicpXG4gICAgICAgIH1cbiAgICB9LFxuXG4gICAgZXZlbnRzOiB7XG4gICAgICAgICdjbGljayBAdWkuc3VibWl0QnRuJzogJ29uU3VibWl0QnRuQ2xpY2snLFxuICAgICAgICAna2V5cHJlc3MgQHVpLmZpZWxkR3JvdXBzIGlucHV0JzogJ29uS2V5UHJlc3MnXG4gICAgfSxcblxuICAgIG9uS2V5UHJlc3M6IGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgaWYgKGUua2V5Q29kZSA9PT0gMTMpIHtcbiAgICAgICAgICAgIC8vIGVudGVyIHByZXNzZWRcbiAgICAgICAgICAgIGUuc3RvcEltbWVkaWF0ZVByb3BhZ2F0aW9uKCk7XG4gICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICB0aGlzLm9uU3VibWl0QnRuQ2xpY2soZSk7XG4gICAgICAgIH1cbiAgICB9LFxuXG4gICAgZ2V0Rm9ybURhdGE6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMudWkuZm9ybS5zZXJpYWxpemVPYmplY3QoKTtcbiAgICB9LFxuXG4gICAgdmFsaWRhdGU6IGZ1bmN0aW9uIChkYXRhKSB7XG4gICAgICAgIHZhciB2YWxpZGF0b3JzID0gdGhpcy5nZXRPcHRpb24oJ3ZhbGlkYXRvcnMnKTtcbiAgICAgICAgdmFyIHZhbGlkYXRvciwgZmllbGQsIG1zZywgZXJyb3JzID0ge307XG5cbiAgICAgICAgZm9yIChmaWVsZCBpbiB2YWxpZGF0b3JzKSB7XG4gICAgICAgICAgICB2YWxpZGF0b3IgPSB2YWxpZGF0b3JzW2ZpZWxkXTtcbiAgICAgICAgICAgIG1zZyA9IHZhbGlkYXRvcihkYXRhW2ZpZWxkXSwgZGF0YSk7XG4gICAgICAgICAgICBpZihtc2cpIHtcbiAgICAgICAgICAgICAgICBlcnJvcnNbZmllbGRdID0gW21zZ107XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIGVycm9ycztcbiAgICB9LFxuXG4gICAgc2hvd0Vycm9yczogZnVuY3Rpb24gKGVycm9ycykge1xuICAgICAgICBmb3IgKHZhciBmaWVsZCBpbiBlcnJvcnMpIHtcbiAgICAgICAgICAgIGlmIChmaWVsZCA9PT0gJ19fYWxsX18nKSB7XG4gICAgICAgICAgICAgICAgdGhpcy51aS5ub25GaWVsZEVycm9yLmh0bWwoZXJyb3JzW2ZpZWxkXS5qb2luKCc8YnI+JykpO1xuICAgICAgICAgICAgICAgIGNvbnRpbnVlO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB2YXIgaW5wdXRfc2VsZWN0b3IgPSAnW25hbWU9XCInICsgZmllbGQgKyAnXCJdJztcblxuICAgICAgICAgICAgdmFyICRmaWVsZF9ncm91cCA9IHRoaXMudWkuZmllbGRHcm91cHMuaGFzKGlucHV0X3NlbGVjdG9yKTtcbiAgICAgICAgICAgICRmaWVsZF9ncm91cC5hZGRDbGFzcyh0aGlzLmdldE9wdGlvbignZXJyb3JDbHMnKSk7XG5cbiAgICAgICAgICAgIHZhciAkZXJyb3JfY29udGFpbmVyID0gJGZpZWxkX2dyb3VwLmZpbmQodGhpcy51aS5lcnJvckdyb3Vwcyk7XG4gICAgICAgICAgICAkZXJyb3JfY29udGFpbmVyLmh0bWwoZXJyb3JzW2ZpZWxkXS5qb2luKCc8YnI+JykpO1xuICAgICAgICB9XG4gICAgfSxcblxuICAgIGNsZWFyRXJyb3JzOiBmdW5jdGlvbigpIHtcbiAgICAgICAgdGhpcy51aS5maWVsZEdyb3Vwcy5yZW1vdmVDbGFzcyh0aGlzLmdldE9wdGlvbignZXJyb3JDbHMnKSk7XG4gICAgICAgIHRoaXMudWkuZXJyb3JHcm91cHMuaHRtbCgnJyk7XG4gICAgICAgIHRoaXMudWkubm9uRmllbGRFcnJvci5odG1sKCcnKTtcbiAgICB9LFxuXG4gICAgb25TdWJtaXRCdG5DbGljazogZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgaWYgKCQoZS5jdXJyZW50VGFyZ2V0KS5hdHRyKCdkaXNhYmxlZCcpKSB7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cbiAgICAgICAgdGhpcy5jbGVhckVycm9ycygpO1xuXG4gICAgICAgIHZhciBkYXRhID0gdGhpcy5nZXRGb3JtRGF0YSgpO1xuICAgICAgICB2YXIgZXJyb3JzID0gdGhpcy52YWxpZGF0ZShkYXRhKTtcblxuICAgICAgICBpZighXy5pc0VtcHR5KGVycm9ycykpIHtcbiAgICAgICAgICAgIHRoaXMuc2hvd0Vycm9ycyhlcnJvcnMpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgdGhpcy52aWV3LnRyaWdnZXJNZXRob2QodGhpcy5nZXRPcHRpb24oJ3N1Ym1pdEV2ZW50JyksIGRhdGEpO1xuICAgICAgICB9XG4gICAgfVxufSk7XG5cblxubW9kdWxlLmV4cG9ydHMgPSB7XG4gICAgRm9ybUJlaGF2aW9yOiBGb3JtQmVoYXZpb3IsXG4gICAgcmVxdWlyZWQ6IGZ1bmN0aW9uKHZhbCkge1xuICAgICAgICBpZighdmFsKSB7XG4gICAgICAgICAgICByZXR1cm4gJ1RoaXMgZmllbGQgaXMgcmVxdWlyZWQnO1xuICAgICAgICB9XG4gICAgfSxcbiAgICBqc29uOiBmdW5jdGlvbih2YWwpIHtcbiAgICAgICAgdHJ5IHtcbiAgICAgICAgICAgIEpTT04ucGFyc2UodmFsKTtcbiAgICAgICAgfSBjYXRjaChlKSB7XG4gICAgICAgICAgICByZXR1cm4gJ1ZhbGlkIEpTT04gaXMgcmVxdWlyZWQnO1xuICAgICAgICB9XG4gICAgfVxuXG59O1xuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vc3JjL2pzL2xpYi9mb3JtLmpzXG4vLyBtb2R1bGUgaWQgPSA2XG4vLyBtb2R1bGUgY2h1bmtzID0gMCIsInZhciBsb2NhbGVzID0gcmVxdWlyZSgnbGliL2xvY2FsZXMnKTtcbnZhciBHcmlkVXRpbHMgPSByZXF1aXJlKCdsaWIvZ3JpZCcpO1xuXG52YXIgQ2VsbCA9IEdyaWRVdGlscy5DdXN0b21DZWxsLmV4dGVuZCh7XG4gICAgc2hvd1NwaW5uZXI6IHRydWUsXG4gICAgdGVtcGxhdGU6IF8udGVtcGxhdGUoW1xuICAgICAgICAnPCUgaWYgKHNob3dTcGlubmVyKSB7ICU+JyxcbiAgICAgICAgJyAgICA8JSBpZiAodmFsID09PSBudWxsKSB7ICU+JyxcbiAgICAgICAgJyAgICAgICAgPGkgY2xhc3M9XFwnZmEgZmEtc3BpbiBmYS1yZWZyZXNoXFwnPjwvaT4nLFxuICAgICAgICAnICAgIDwlIH0gZWxzZSB7ICU+JyxcbiAgICAgICAgJyAgICAgICAgPCU9IHZhbCAlPicsXG4gICAgICAgICcgICAgPCUgfSAlPicsXG4gICAgICAgICc8JSB9IGVsc2UgeyAlPicsXG4gICAgICAgICcgICAgPCU9IF8uaXNOdWxsKHZhbCkgPyBcIiZtZGFzaDtcIiA6IHZhbCAlPicsXG4gICAgICAgICc8JSB9ICU+J1xuICAgIF0uam9pbignXFxuJykpLFxuICAgIHRlbXBsYXRlQ29udGV4dDogZnVuY3Rpb24gKCkge1xuICAgICAgICB2YXIgdmFsID0gdGhpcy5nZXRBdHRyVmFsdWUoKSxcbiAgICAgICAgICAgIHNob3dTcGlubmVyID0gdGhpcy5zaG93U3Bpbm5lcjtcblxuICAgICAgICByZXR1cm4ge1xuICAgICAgICAgICAgdmFsOiB2YWwsXG4gICAgICAgICAgICBzaG93U3Bpbm5lcjogc2hvd1NwaW5uZXJcbiAgICAgICAgfVxuICAgIH1cbn0pO1xuXG52YXIgVG90YWxzR3JpZFZpZXcgPSBNYUdyaWQuR3JpZFZpZXcuZXh0ZW5kKHtcbiAgICBjb2x1bW5zOiBbe1xuICAgICAgICBhdHRyOiAnY3VycmVuY3knLFxuICAgICAgICBoZWFkZXI6IE1hcmlvbmV0dGUuVmlldy5leHRlbmQoe1xuICAgICAgICAgICAgdGVtcGxhdGU6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gbG9jYWxlcy50cmFuc2xhdGUoJ0N1cnJlbmN5JylcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSksXG4gICAgICAgIGNsYXNzTmFtZTogJ3RleHQtY2VudGVyJyxcbiAgICAgICAgY2VsbDogQ2VsbFxuICAgIH0sIHtcbiAgICAgICAgYXR0cjogJ3JvdW5kcycsXG4gICAgICAgIGhlYWRlcjogTWFyaW9uZXR0ZS5WaWV3LmV4dGVuZCh7XG4gICAgICAgICAgICB0ZW1wbGF0ZTogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHJldHVybiBsb2NhbGVzLnRyYW5zbGF0ZSgnUm91bmRzJylcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSksXG4gICAgICAgIGNsYXNzTmFtZTogJ3RleHQtcmlnaHQnLFxuICAgICAgICBjZWxsOiBDZWxsXG4gICAgfSwge1xuICAgICAgICBhdHRyOiAnYmV0cycsXG4gICAgICAgIGhlYWRlcjogTWFyaW9uZXR0ZS5WaWV3LmV4dGVuZCh7XG4gICAgICAgICAgICB0ZW1wbGF0ZTogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHJldHVybiBsb2NhbGVzLnRyYW5zbGF0ZSgnQmV0cycpXG4gICAgICAgICAgICB9XG4gICAgICAgIH0pLFxuICAgICAgICBjbGFzc05hbWU6ICd0ZXh0LXJpZ2h0JyxcbiAgICAgICAgY2VsbDogQ2VsbFxuICAgIH0sIHtcbiAgICAgICAgX2lkOiAnd2lucycsXG4gICAgICAgIGF0dHI6ICd3aW5zJyxcbiAgICAgICAgaGVhZGVyOiBNYXJpb25ldHRlLlZpZXcuZXh0ZW5kKHtcbiAgICAgICAgICAgIHRlbXBsYXRlOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGxvY2FsZXMudHJhbnNsYXRlKCdXaW5zJylcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSksXG4gICAgICAgIGNsYXNzTmFtZTogJ3RleHQtcmlnaHQnLFxuICAgICAgICBjZWxsOiBDZWxsXG4gICAgfSwgIHtcbiAgICAgICAgYXR0cjogJ291dGNvbWUnLFxuICAgICAgICBoZWFkZXI6IE1hcmlvbmV0dGUuVmlldy5leHRlbmQoe1xuICAgICAgICAgICAgdGVtcGxhdGU6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gbG9jYWxlcy50cmFuc2xhdGUoJ091dGNvbWUnKVxuICAgICAgICAgICAgfVxuICAgICAgICB9KSxcbiAgICAgICAgY2xhc3NOYW1lOiAndGV4dC1yaWdodCcsXG4gICAgICAgIGNlbGw6IENlbGxcbiAgICB9LCB7XG4gICAgICAgIGF0dHI6ICdwYXlvdXQnLFxuICAgICAgICBoZWFkZXI6IE1hcmlvbmV0dGUuVmlldy5leHRlbmQoe1xuICAgICAgICAgICAgdGVtcGxhdGU6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gbG9jYWxlcy50cmFuc2xhdGUoJ1BheW91dCwlJylcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSksXG4gICAgICAgIGNsYXNzTmFtZTogJ3RleHQtcmlnaHQnLFxuICAgICAgICBjZWxsOiBDZWxsXG4gICAgfV0sXG4gICAgZm9vdGVyVmlldzogbnVsbCxcbiAgICBzaXplclZpZXc6IG51bGwsXG4gICAgcGFnaW5hdG9yVmlldzogbnVsbFxufSk7XG5cbm1vZHVsZS5leHBvcnRzID0gVG90YWxzR3JpZFZpZXc7XG5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL3NyYy9qcy92aWV3cy90b3RhbHMvdmlldy5qc1xuLy8gbW9kdWxlIGlkID0gN1xuLy8gbW9kdWxlIGNodW5rcyA9IDAiLCJ2YXIgVXRpbHMgPSByZXF1aXJlKCdsaWIvdXRpbHMnKTtcbnZhciBOZXR3b3JrID0ge1xuICAgIHJlcXVpcmVkSGFzaFBhcmFtczogZnVuY3Rpb24gKCkge1xuICAgICAgICB2YXIgaGFzaF9wYXJhbXMgPSBVdGlscy5wYXJzZUhhc2goKTtcbiAgICAgICAgdmFyIHBhcmFtcyA9IHt9O1xuICAgICAgICBpZiAoaGFzaF9wYXJhbXMucGxheWVyX2lkICE9IG51bGwpIHtcbiAgICAgICAgICAgIHBhcmFtcy5wbGF5ZXJfaWQgPSBoYXNoX3BhcmFtcy5wbGF5ZXJfaWRcbiAgICAgICAgfVxuICAgICAgICBpZiAoaGFzaF9wYXJhbXMuYnJhbmQgIT0gbnVsbCkge1xuICAgICAgICAgICAgcGFyYW1zLmJyYW5kID0gaGFzaF9wYXJhbXMuYnJhbmRcbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gcGFyYW1zO1xuICAgIH0sXG4gICAgcmVxdWlyZWRBcGlQYXJhbXM6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdmFyIGhhc2hfcGFyYW1zID0gVXRpbHMucGFyc2VIYXNoKCk7XG4gICAgICAgIHJldHVybiB7XG4gICAgICAgICAgICAncGxheWVyX2lkJzogaGFzaF9wYXJhbXMucGxheWVyX2lkLFxuICAgICAgICAgICAgJ2JyYW5kJzogaGFzaF9wYXJhbXMuYnJhbmRcbiAgICAgICAgfTtcbiAgICB9LFxuICAgIHNldHVwOiBmdW5jdGlvbiAoYmFzZSkge1xuICAgICAgICB2YXIgc3luYyA9IGZ1bmN0aW9uIChtZXRob2QsIG1vZGVsLCBvcHRpb25zKSB7XG4gICAgICAgICAgICB2YXIgcGFyYW1zID0gXy5leHRlbmQoe1xuICAgICAgICAgICAgICAgIHR5cGU6ICdQT1NUJyxcbiAgICAgICAgICAgICAgICBkYXRhVHlwZTogJ2pzb24nLFxuICAgICAgICAgICAgICAgIGNvbnRlbnRUeXBlOiAnYXBwbGljYXRpb24vanNvbicsXG4gICAgICAgICAgICAgICAgcHJvY2Vzc0RhdGE6IGZhbHNlLFxuICAgICAgICAgICAgICAgIGVtdWxhdGVIVFRQOiBmYWxzZSxcbiAgICAgICAgICAgICAgICBlbXVsYXRlSlNPTjogZmFsc2UsXG4gICAgICAgICAgICAgICAgZGF0YToge31cbiAgICAgICAgICAgIH0sIG9wdGlvbnMpO1xuXG4gICAgICAgICAgICBwYXJhbXMuZGF0YSA9IF8uZXh0ZW5kKE5ldHdvcmsucmVxdWlyZWRBcGlQYXJhbXMoKSwgcGFyYW1zLmRhdGEpO1xuICAgICAgICAgICAgLypwYXJhbXMuZGF0YSA9IF8ub21pdChwYXJhbXMuZGF0YSwgZnVuY3Rpb24gKHZhbHVlLCBrZXksIG9iaikge1xuICAgICAgICAgICAgICAgIHJldHVybiB2YWx1ZSA9PT0gJyc7XG4gICAgICAgICAgICB9KTsqL1xuICAgICAgICAgICAgbW9kZWwubGFzdEZpbHRlcnMgPSBwYXJhbXMuZGF0YSB8fCBwYXJhbXMuYXR0cnMgfHwgbW9kZWwudG9KU09OKHBhcmFtcyk7XG5cbiAgICAgICAgICAgIHZhciBkYXRhID0gKHBhcmFtcy5kYXRhIHx8IHBhcmFtcy5hdHRycyB8fCBtb2RlbC50b0pTT04ocGFyYW1zKSB8fCAnJyk7XG4gICAgICAgICAgICBwYXJhbXMuZGF0YSA9IEpTT04uc3RyaW5naWZ5KGRhdGEpIHx8IHVuZGVmaW5lZDtcbiAgICAgICAgICAgIHBhcmFtcy51cmwgPSBwYXJhbXMudXJsIHx8IF8ucmVzdWx0KG1vZGVsLCAndXJsJykgfHwgXy5ub29wKCk7XG4gICAgICAgICAgICBwYXJhbXMudXJsID0gYmFzZS51cmwgKyBwYXJhbXMudXJsO1xuXG4gICAgICAgICAgICB2YXIgZXJyb3IgPSBwYXJhbXMuZXJyb3IsXG4gICAgICAgICAgICAgICAgc3VjY2VzcyA9IHBhcmFtcy5zdWNjZXNzO1xuICAgICAgICAgICAgcGFyYW1zLmVycm9yID0gZnVuY3Rpb24gKHhociwgdGV4dFN0YXR1cywgZXJyb3JUaHJvd24pIHtcbiAgICAgICAgICAgICAgICBwYXJhbXMudGV4dFN0YXR1cyA9IHRleHRTdGF0dXM7XG4gICAgICAgICAgICAgICAgcGFyYW1zLmVycm9yVGhyb3duID0gZXJyb3JUaHJvd247XG4gICAgICAgICAgICAgICAgaWYgKGVycm9yKSB7XG4gICAgICAgICAgICAgICAgICAgIGVycm9yLmNhbGwocGFyYW1zLmNvbnRleHQsIHhociwgdGV4dFN0YXR1cywgZXJyb3JUaHJvd24pO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBpZiAoYmFzZS5lcnJvcikge1xuICAgICAgICAgICAgICAgICAgICBiYXNlLmVycm9yLmNhbGwocGFyYW1zLmNvbnRleHQsIHhociwgbWV0aG9kLCBtb2RlbCwgb3B0aW9ucyk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgcGFyYW1zLnN1Y2Nlc3MgPSBmdW5jdGlvbiAoeGhyLCB0ZXh0U3RhdHVzLCBlcnJvclRocm93bikge1xuICAgICAgICAgICAgICAgIHBhcmFtcy50ZXh0U3RhdHVzID0gdGV4dFN0YXR1cztcbiAgICAgICAgICAgICAgICBwYXJhbXMuZXJyb3JUaHJvd24gPSBlcnJvclRocm93bjtcbiAgICAgICAgICAgICAgICBpZiAoc3VjY2Vzcykge1xuICAgICAgICAgICAgICAgICAgICBzdWNjZXNzLmNhbGwocGFyYW1zLmNvbnRleHQsIHhociwgdGV4dFN0YXR1cywgZXJyb3JUaHJvd24pO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBpZiAoYmFzZS5zdWNjZXNzKSB7XG4gICAgICAgICAgICAgICAgICAgIGJhc2Uuc3VjY2Vzcy5jYWxsKHBhcmFtcy5jb250ZXh0LCB4aHIsIG1ldGhvZCwgbW9kZWwsIG9wdGlvbnMpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgIHZhciB4aHIgPSBwYXJhbXMueGhyID0gQmFja2JvbmUuYWpheChwYXJhbXMpO1xuICAgICAgICAgICAgbW9kZWwudHJpZ2dlcigncmVxdWVzdCcsIG1vZGVsLCB4aHIsIHBhcmFtcyk7XG4gICAgICAgICAgICByZXR1cm4geGhyO1xuICAgICAgICB9O1xuICAgICAgICByZXR1cm4gc3luYztcbiAgICB9XG59O1xubW9kdWxlLmV4cG9ydHMgPSBOZXR3b3JrO1xuXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvanMvYXBwL25ldHdvcmsuanNcbi8vIG1vZHVsZSBpZCA9IDhcbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwidmFyIE1vZGUgPSB7XG4gICAgUkVBTDogJ1JFQUwnLFxuICAgIEZVTjogJ0ZVTidcbn07XG5cbnZhciBQbGF0Zm9ybSA9IHtcbiAgICBNT0JJTEU6ICdtb2JpbGUnLFxuICAgIERFU0tUT1A6ICdkZXNrdG9wJ1xufTtcblxudmFyIFJlcG9ydFR5cGUgPSB7XG4gICAgR0dSOiAnR0dSJyxcbiAgICBOR1I6ICdOR1InLFxuICAgIFBST01POiAnUFJNJ1xufTtcblxubW9kdWxlLmV4cG9ydHMgPSB7XG4gICAgTW9kZTogTW9kZSxcbiAgICBQbGF0Zm9ybTogUGxhdGZvcm0sXG4gICAgUmVwb3J0VHlwZTogUmVwb3J0VHlwZVxufTtcblxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vc3JjL2pzL2FwcC9lbnVtcy5qc1xuLy8gbW9kdWxlIGlkID0gOVxuLy8gbW9kdWxlIGNodW5rcyA9IDAiLCJ2YXIgTmVzdGVkVmlld0JlaGF2aW9yID0gTW4uQmVoYXZpb3IuZXh0ZW5kKHtcbiAgICBkZWZhdWx0czoge1xuICAgICAgICBiYWNrQnV0dG9uTGlua09wdGlvbk5hbWU6ICdnb19iYWNrJyxcbiAgICAgICAgYmFja0J1dHRvbjogJy5nb2JhY2snXG4gICAgfSxcbiAgICB1aTogZnVuY3Rpb24oKSB7XG4gICAgICAgIHJldHVybiB7XG4gICAgICAgICAgICBiYWNrQnV0dG9uOiB0aGlzLmdldE9wdGlvbignYmFja0J1dHRvbicpXG4gICAgICAgIH1cbiAgICB9LFxuICAgIGV2ZW50czoge1xuICAgICAgICAnY2xpY2sgQHVpLmJhY2tCdXR0b24nOiAnb25Hb0JhY2tDbGljaydcbiAgICB9LFxuICAgIGluaXRpYWxpemU6IGZ1bmN0aW9uKCkge1xuICAgICAgICB0aGlzLm9wdGlvbnMuYmFja0J1dHRvbkxpbmsgPSB0aGlzLnZpZXcuZ2V0T3B0aW9uKHRoaXMuZ2V0T3B0aW9uKCdiYWNrQnV0dG9uTGlua09wdGlvbk5hbWUnKSk7XG4gICAgfSxcbiAgICBvblJlbmRlcjogZnVuY3Rpb24oKSB7XG4gICAgICAgIGlmICh0aGlzLmdldE9wdGlvbignYmFja0J1dHRvbkxpbmsnKSkge1xuICAgICAgICAgICAgdGhpcy51aS5iYWNrQnV0dG9uLnNob3coKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIHRoaXMudWkuYmFja0J1dHRvbi5oaWRlKCk7XG4gICAgICAgIH1cbiAgICB9LFxuICAgIG9uR29CYWNrQ2xpY2s6IGZ1bmN0aW9uKCkge1xuICAgICAgICBCYWNrYm9uZS5oaXN0b3J5Lm5hdmlnYXRlKHRoaXMuZ2V0T3B0aW9uKCdiYWNrQnV0dG9uTGluaycpLCB7dHJpZ2dlcjogdHJ1ZX0pO1xuICAgIH1cbn0pO1xuXG5tb2R1bGUuZXhwb3J0cyA9IHtcbiAgICBOZXN0ZWRWaWV3QmVoYXZpb3I6IE5lc3RlZFZpZXdCZWhhdmlvclxufTtcblxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vc3JjL2pzL2xpYi92aWV3LmpzXG4vLyBtb2R1bGUgaWQgPSAxMFxuLy8gbW9kdWxlIGNodW5rcyA9IDAiLCJ2YXIgU3RvcmFnZSA9IHJlcXVpcmUoJ2FwcC9zdG9yYWdlJyk7XG52YXIgbG9jYWxlcyA9IHJlcXVpcmUoJ2xpYi9sb2NhbGVzJyk7XG52YXIgUXVldWUgPSByZXF1aXJlKCcuL2VsZW1lbnRfcXVldWUnKTtcblxudmFyIERyYXdlckNlbGwgPSBNYXJpb25ldHRlLlZpZXcuZXh0ZW5kKHtcbiAgICBjbGFzc05hbWU6ICdkcmF3ZXItY2VsbCcsXG4gICAgdGVtcGxhdGU6IF8udGVtcGxhdGUoW1xuICAgICAgICAnPGRpdiBjbGFzcz1cImRyYXdlci1jZWxsLXNlY3Rpb24gZHJhd2VyLWNlbGwtZXhwYW5kLWJ0biBidG4gYnRuLXhzIGJ0bi1kZWZhdWx0JyxcbiAgICAgICAgJyAgICA8JSBpZiAoIWV4cGFuZGFibGUpIHsgJT5kaXNhYmxlZDwlIH0gJT5cIj4nLFxuICAgICAgICAnICAgIDwlLSB0cmFuc2xhdGUoXCJEcmF3ZXJcIikgJT4nLFxuICAgICAgICAnPC9kaXY+JyxcbiAgICBdLmpvaW4oJycpKSxcbiAgICB0ZW1wbGF0ZUNvbnRleHQ6IGZ1bmN0aW9uKCkge1xuICAgICAgICByZXR1cm4ge1xuICAgICAgICAgICAgdHJhbnNsYXRlOiBsb2NhbGVzLnRyYW5zbGF0ZS5iaW5kKGxvY2FsZXMpLFxuICAgICAgICAgICAgZXhwYW5kYWJsZTogdGhpcy5leHBhbmRhYmxlLFxuICAgICAgICAgICAgc3JjOiB0aGlzLnNyY1xuICAgICAgICB9XG4gICAgfSxcbiAgICBpZnJhbWVUZW1wbGF0ZTogXy50ZW1wbGF0ZShbXG4gICAgICAgICc8ZGl2IGNsYXNzPVwiZHJhd2VyLWNlbGwtc2VjdGlvblwiPicsXG4gICAgICAgICcgICAgPGlmcmFtZSBzcmM9XCI8JS0gc3JjICU+XCI+PC9pZnJhbWU+JyxcbiAgICAgICAgJzwvZGl2PicsXG4gICAgICAgICc8ZGl2IGNsYXNzPVwiZHJhd2VyLWNlbGwtc2VjdGlvbiB0ZXh0LWNlbnRlclwiPicsXG4gICAgICAgICcgICAgPGRpdiBjbGFzcz1cImJ0bi1ncm91cFwiPicsXG4gICAgICAgICcgICAgICAgIDxhIGNsYXNzPVwiYnRuIGJ0bi14cyBidG4tZGVmYXVsdFwiIGhyZWY9XCI8JS0gc3JjICU+XCIgdGFyZ2V0PVwiX2JsYW5rXCI+PGkgY2xhc3M9XCJmYSBmYS1jbG9uZVwiPjwvaT48L2E+JyxcbiAgICAgICAgJyAgICAgICAgPGRpdiBjbGFzcz1cImRyYXdlci1jZWxsLXpvb20tYnRuIGJ0biBidG4teHMgYnRuLWRlZmF1bHRcIj48aSBjbGFzcz1cImZhXCI+PC9pPjwvZGl2PicsXG4gICAgICAgICcgICAgPC9kaXY+JyxcbiAgICAgICAgJzwvZGl2PicsXG4gICAgXS5qb2luKCcnKSksXG4gICAgdWk6IHtcbiAgICAgICAgZXhwYW5kQnV0dG9uOiAnLmRyYXdlci1jZWxsLWV4cGFuZC1idG4nLFxuICAgICAgICB6b29tQnV0dG9uOiAnLmRyYXdlci1jZWxsLXpvb20tYnRuJyxcbiAgICB9LFxuICAgIHRyaWdnZXJzOiB7XG4gICAgICAgICdjbGljayBAdWkuZXhwYW5kQnV0dG9uJzogJ3RvZ2dsZScsXG4gICAgICAgICdjbGljayBAdWkuem9vbUJ1dHRvbic6ICd6b29tJ1xuICAgIH0sXG4gICAgaW5pdGlhbGl6ZTogZnVuY3Rpb24oKSB7XG4gICAgICAgIHRoaXMuZXhwYW5kZWQgPSBmYWxzZTtcbiAgICAgICAgdGhpcy56b29tZWQgPSBmYWxzZTtcbiAgICAgICAgdGhpcy5zcmMgPSB0aGlzLmdldFNyYygpO1xuICAgICAgICB0aGlzLmV4cGFuZGFibGUgPSAhIXRoaXMuc3JjO1xuICAgIH0sXG4gICAgb25CZWZvcmVSZW5kZXI6IGZ1bmN0aW9uKCkge1xuICAgICAgICB0aGlzLiR3cmFwcGVyID0gJCh0aGlzLmlmcmFtZVRlbXBsYXRlKHtcbiAgICAgICAgICAgIHNyYzogdGhpcy5zcmNcbiAgICAgICAgfSkpO1xuICAgICAgICB0aGlzLiRpZnJhbWUgPSB0aGlzLiR3cmFwcGVyLmZpbmQoJ2lmcmFtZScpO1xuICAgICAgICB0aGlzLiR6b29tSWNvbiA9IHRoaXMuJHdyYXBwZXIuZmluZCgnLmRyYXdlci1jZWxsLXpvb20tYnRuIC5mYScpO1xuICAgIH0sXG4gICAgb25BdHRhY2g6IGZ1bmN0aW9uKCkge1xuICAgICAgICB0aGlzLmV4cGFuZGVkID8gdGhpcy5leHBhbmQoKSA6IHRoaXMuY29sbGFwc2UoKTtcbiAgICAgICAgdGhpcy56b29tZWQgPyB0aGlzLnpvb21JbigpIDogdGhpcy56b29tT3V0KCk7XG4gICAgfSxcbiAgICBvbkJlZm9yZURlc3Ryb3k6IGZ1bmN0aW9uKCkge1xuICAgICAgICBRdWV1ZS5xLnJlbW92ZSh0aGlzLiRpZnJhbWUpO1xuICAgIH0sXG4gICAgZ2V0U3JjOiBmdW5jdGlvbigpIHtcbiAgICAgICAgaWYgKHRoaXMubW9kZWwuZ2V0KCd0eXBlJykgPT09ICdST0xMQkFDSycgfHwgdGhpcy5tb2RlbC5nZXQoJ3N0YXR1cycpICE9PSAnT0snKSB7XG4gICAgICAgICAgICAvLyBkbyBub3QgcmVuZGVyIGRyYXdlciBsaW5rIGZvciByb2xsYmFja3MgYW5kIGV4Y2VlZHNcbiAgICAgICAgICAgIHJldHVybiAnJztcbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gU3RvcmFnZS5vcHRpb25zLmRyYXdlcl91cmwgKyB0aGlzLm1vZGVsLmdldCgndHJhbnNhY3Rpb25faWQnKTtcbiAgICB9LFxuICAgIG9uVG9nZ2xlOiBmdW5jdGlvbigpIHtcbiAgICAgICAgaWYgKHRoaXMuZXhwYW5kZWQpIHtcbiAgICAgICAgICAgIHRoaXMuY29sbGFwc2UoKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIHRoaXMuZXhwYW5kKCk7XG4gICAgICAgIH1cbiAgICB9LFxuICAgIG9uWm9vbTogZnVuY3Rpb24oKSB7XG4gICAgICAgIGlmICh0aGlzLnpvb21lZCkge1xuICAgICAgICAgICAgdGhpcy56b29tT3V0KCk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICB0aGlzLnpvb21JbigpO1xuICAgICAgICB9XG4gICAgfSxcblxuICAgIGNvbGxhcHNlOiBmdW5jdGlvbigpIHtcbiAgICAgICAgdGhpcy5leHBhbmRlZCA9IGZhbHNlO1xuICAgICAgICB0aGlzLnVpLmV4cGFuZEJ1dHRvbi5yZW1vdmVDbGFzcygnYWN0aXZlJyk7XG4gICAgICAgIHRoaXMuJGVsLmFkZENsYXNzKCdkcmF3ZXItY29sbGFwc2VkJyk7XG4gICAgICAgIHRoaXMuJGVsLnJlbW92ZUNsYXNzKCdkcmF3ZXItZXhwYW5kZWQnKTtcblxuICAgICAgICBRdWV1ZS5xLnJlbW92ZSh0aGlzLiRpZnJhbWUpO1xuICAgICAgICB0aGlzLiR3cmFwcGVyLnJlbW92ZSgpO1xuXG4gICAgICAgIHRoaXMuem9vbU91dCgpO1xuICAgIH0sXG5cbiAgICBleHBhbmQ6IGZ1bmN0aW9uKCkge1xuICAgICAgICBpZiAoIXRoaXMuZXhwYW5kYWJsZSkge1xuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG4gICAgICAgIHRoaXMuZXhwYW5kZWQgPSB0cnVlO1xuICAgICAgICB0aGlzLnVpLmV4cGFuZEJ1dHRvbi5hZGRDbGFzcygnYWN0aXZlJyk7XG4gICAgICAgIHRoaXMuJGVsLmFkZENsYXNzKCdkcmF3ZXItZXhwYW5kZWQnKTtcbiAgICAgICAgdGhpcy4kZWwucmVtb3ZlQ2xhc3MoJ2RyYXdlci1jb2xsYXBzZWQnKTtcblxuICAgICAgICBRdWV1ZS5xLmFkZCh0aGlzLiRpZnJhbWUsIGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgdGhpcy4kZWwuYXBwZW5kKHRoaXMuJHdyYXBwZXIpO1xuICAgICAgICB9LCB0aGlzKTtcbiAgICB9LFxuXG4gICAgem9vbUluOiBmdW5jdGlvbigpIHtcbiAgICAgICAgdGhpcy56b29tZWQgPSB0cnVlO1xuICAgICAgICB0aGlzLiR6b29tSWNvbi5hZGRDbGFzcygnZmEtc2VhcmNoLW1pbnVzJyk7XG4gICAgICAgIHRoaXMuJHpvb21JY29uLnJlbW92ZUNsYXNzKCdmYS1zZWFyY2gtcGx1cycpO1xuICAgICAgICB0aGlzLiRlbC5hZGRDbGFzcygnZHJhd2VyLWxnJyk7XG4gICAgICAgIHRoaXMuJGVsLnJlbW92ZUNsYXNzKCdkcmF3ZXItc20nKTtcbiAgICB9LFxuXG4gICAgem9vbU91dDogZnVuY3Rpb24oKSB7XG4gICAgICAgIHRoaXMuem9vbWVkID0gZmFsc2U7XG4gICAgICAgIHRoaXMuJHpvb21JY29uLmFkZENsYXNzKCdmYS1zZWFyY2gtcGx1cycpO1xuICAgICAgICB0aGlzLiR6b29tSWNvbi5yZW1vdmVDbGFzcygnZmEtc2VhcmNoLW1pbnVzJyk7XG4gICAgICAgIHRoaXMuJGVsLmFkZENsYXNzKCdkcmF3ZXItc20nKTtcbiAgICAgICAgdGhpcy4kZWwucmVtb3ZlQ2xhc3MoJ2RyYXdlci1sZycpO1xuICAgIH1cbn0pO1xuXG52YXIgRHJhd2VySGVhZGVyID0gTWFyaW9uZXR0ZS5WaWV3LmV4dGVuZCh7XG4gICAgdGVtcGxhdGU6IF8udGVtcGxhdGUoW1xuICAgICAgICAnPGRpdiBjbGFzcz1cImRyYXdlci1oZWFkZXItZXhwYW5kLWJ0biBidG4gYnRuLWRlZmF1bHQgYnRuLXhzXCInLFxuICAgICAgICAnICAgID48JS0gdHJhbnNsYXRlKGV4cGFuZGVkID8gXCJIaWRlIEFsbFwiIDogXCJFeHBhbmQgQWxsXCIpICU+PC9kaXYnXG4gICAgXS5qb2luKCcnKSksXG4gICAgdGVtcGxhdGVDb250ZXh0OiBmdW5jdGlvbigpIHtcbiAgICAgICAgcmV0dXJuIHtcbiAgICAgICAgICAgIGV4cGFuZGVkOiB0aGlzLmV4cGFuZGVkLFxuICAgICAgICAgICAgdHJhbnNsYXRlOiBsb2NhbGVzLnRyYW5zbGF0ZS5iaW5kKGxvY2FsZXMpXG4gICAgICAgIH1cbiAgICB9LFxuICAgIHVpOiB7XG4gICAgICAgIGV4cGFuZEJ1dHRvbjogJy5kcmF3ZXItaGVhZGVyLWV4cGFuZC1idG4nXG4gICAgfSxcbiAgICB0cmlnZ2Vyczoge1xuICAgICAgICAnY2xpY2sgQHVpLmV4cGFuZEJ1dHRvbic6ICd0b2dnbGUnXG4gICAgfSxcbiAgICBjb2xsZWN0aW9uRXZlbnRzOiB7XG4gICAgICAgICdzeW5jJzogJ29uU3luYydcbiAgICB9LFxuICAgIGluaXRpYWxpemU6IGZ1bmN0aW9uKCkge1xuICAgICAgICB0aGlzLmV4cGFuZGVkID0gZmFsc2U7XG4gICAgfSxcbiAgICBvblN5bmM6IGZ1bmN0aW9uKCkge1xuICAgICAgICB0aGlzLmV4cGFuZGVkID0gZmFsc2U7XG4gICAgICAgIGlmICh0aGlzLmlzUmVuZGVyZWQoKSkge1xuICAgICAgICAgICAgdGhpcy5yZW5kZXIoKTtcbiAgICAgICAgfVxuICAgIH0sXG4gICAgb25Ub2dnbGU6IGZ1bmN0aW9uKCkge1xuICAgICAgICB0aGlzLmV4cGFuZGVkID0gIXRoaXMuZXhwYW5kZWQ7XG4gICAgICAgIHRoaXMucmVuZGVyKCk7XG4gICAgICAgIGlmICh0aGlzLmV4cGFuZGVkKSB7XG4gICAgICAgICAgICB0aGlzLnRyaWdnZXJNZXRob2QoJ2V4cGFuZF9hbGwnKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIHRoaXMudHJpZ2dlck1ldGhvZCgnY29sbGFwc2VfYWxsJyk7XG4gICAgICAgIH1cbiAgICB9XG59KTtcblxubW9kdWxlLmV4cG9ydHMgPSB7XG4gICAgRHJhd2VyQ2VsbDogRHJhd2VyQ2VsbCxcbiAgICBEcmF3ZXJIZWFkZXI6IERyYXdlckhlYWRlclxufTtcblxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vc3JjL2pzL2xpYi9ncmlkX2N1c3RvbS5qc1xuLy8gbW9kdWxlIGlkID0gMTFcbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwidmFyIGxvY2FsZXMgPSByZXF1aXJlKCdsaWIvbG9jYWxlcycpO1xudmFyIEdyaWRVdGlscyA9IHJlcXVpcmUoJ2xpYi9ncmlkJyk7XG5cbnZhciBDZWxsID0gR3JpZFV0aWxzLkN1c3RvbUNlbGwuZXh0ZW5kKHtcbiAgICBzaG93U3Bpbm5lcjogZmFsc2UsXG4gICAgdGVtcGxhdGU6IF8udGVtcGxhdGUoW1xuICAgICAgICAnPCUgaWYgKCFfLmlzTnVsbCh2YWwpKSB7ICU+JyxcbiAgICAgICAgJzwlLSB2YWwgJT4nLFxuICAgICAgICAnPCUgfSBlbHNlIGlmIChzaG93U3Bpbm5lcikgeyAlPicsXG4gICAgICAgICc8aSBjbGFzcz1cImZhIGZhLXNwaW4gZmEtcmVmcmVzaFwiPjwvaT4nLFxuICAgICAgICAnPCUgfSBlbHNlIHsgJT4nLFxuICAgICAgICAnXFx1MjAxNCcsXG4gICAgICAgICc8JSB9ICU+J1xuICAgIF0uam9pbignXFxuJykpLFxuICAgIHRlbXBsYXRlQ29udGV4dDogZnVuY3Rpb24oKSB7XG4gICAgICAgIHJldHVybiB7XG4gICAgICAgICAgICB2YWw6IHRoaXMuZ2V0QXR0clZhbHVlKCksXG4gICAgICAgICAgICBzaG93U3Bpbm5lcjogdGhpcy5zaG93U3Bpbm5lclxuICAgICAgICB9XG4gICAgfVxufSk7XG5cbnZhciBQbGF5ZXJJbmZvR3JpZFZpZXcgPSBNYUdyaWQuR3JpZFZpZXcuZXh0ZW5kKHtcbiAgICBjb2x1bW5zOiBbe1xuICAgICAgICBhdHRyOiAncGxheWVyX2lkJyxcbiAgICAgICAgaGVhZGVyOiBNYXJpb25ldHRlLlZpZXcuZXh0ZW5kKHtcbiAgICAgICAgICAgIHRlbXBsYXRlOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gbG9jYWxlcy50cmFuc2xhdGUoJ1BsYXllcicpXG4gICAgICAgICAgICB9XG4gICAgICAgIH0pLFxuICAgICAgICBjbGFzc05hbWU6ICd0ZXh0LWNlbnRlcicsXG4gICAgICAgIGNlbGw6IENlbGxcbiAgICB9LCB7XG4gICAgICAgIGF0dHI6ICdwcm9qZWN0X25hbWUnLFxuICAgICAgICBoZWFkZXI6IE1hcmlvbmV0dGUuVmlldy5leHRlbmQoe1xuICAgICAgICAgICAgdGVtcGxhdGU6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgIHJldHVybiBsb2NhbGVzLnRyYW5zbGF0ZSgnUHJvamVjdCcpXG4gICAgICAgICAgICB9XG4gICAgICAgIH0pLFxuICAgICAgICBjbGFzc05hbWU6ICd0ZXh0LWNlbnRlcicsXG4gICAgICAgIGNlbGw6IENlbGwuZXh0ZW5kKHtcbiAgICAgICAgICAgIHRlbXBsYXRlOiBfLnRlbXBsYXRlKFtcbiAgICAgICAgICAgICAgICAnPCUgaWYgKGJyYW5kICE9PSBcIipcIikgeyAlPicsXG4gICAgICAgICAgICAgICAgJzwlLSBwcm9qZWN0X25hbWUgJT4gLSA8JS0gYnJhbmQgJT4nLFxuICAgICAgICAgICAgICAgICc8JSB9IGVsc2UgeyAlPicsXG4gICAgICAgICAgICAgICAgJzwlLSBwcm9qZWN0X25hbWUgJT4nLFxuICAgICAgICAgICAgICAgICc8JSB9ICU+J1xuICAgICAgICAgICAgXS5qb2luKCcnKSlcbiAgICAgICAgfSlcbiAgICB9LCB7XG4gICAgICAgIGF0dHI6ICdwcm92aWRlcl9uYW1lJyxcbiAgICAgICAgaGVhZGVyOiBNYXJpb25ldHRlLlZpZXcuZXh0ZW5kKHtcbiAgICAgICAgICAgIHRlbXBsYXRlOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gbG9jYWxlcy50cmFuc2xhdGUoJ1Byb3ZpZGVyJylcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSksXG4gICAgICAgIGNsYXNzTmFtZTogJ3RleHQtY2VudGVyJyxcbiAgICAgICAgY2VsbDogQ2VsbFxuICAgIH1dLFxuICAgIGZvb3RlclZpZXc6IG51bGwsXG4gICAgc2l6ZXJWaWV3OiBudWxsLFxuICAgIHBhZ2luYXRvclZpZXc6IG51bGxcbn0pO1xuXG5tb2R1bGUuZXhwb3J0cyA9IFBsYXllckluZm9HcmlkVmlldztcblxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vc3JjL2pzL3ZpZXdzL3BsYXllcl9pbmZvL3ZpZXcuanNcbi8vIG1vZHVsZSBpZCA9IDEyXG4vLyBtb2R1bGUgY2h1bmtzID0gMCIsInJlcXVpcmUoJ2xpYi9wYXRjaGVzJyk7XG5cbnZhciBOZXR3b3JrID0gcmVxdWlyZSgnYXBwL25ldHdvcmsnKTtcbnZhciBDb250cm9sbGVyID0gcmVxdWlyZSgnYXBwL2NvbnRyb2xsZXInKTtcbnZhciBSb3V0ZXIgPSByZXF1aXJlKCdhcHAvcm91dGVyJyk7XG52YXIgU3RvcmFnZSA9IHJlcXVpcmUoJ2FwcC9zdG9yYWdlJyk7XG5cbnZhciBMYXlvdXQgPSByZXF1aXJlKCd2aWV3cy9sYXlvdXQvdmlldycpO1xudmFyIEVycm9yTW9kYWxWaWV3ID0gcmVxdWlyZSgndmlld3MvZXJyb3JfbW9kYWwvdmlldycpO1xudmFyIGxvY2FsZXMgPSByZXF1aXJlKCdsaWIvbG9jYWxlcycpO1xudmFyIFV0aWxzID0gcmVxdWlyZSgnbGliL3V0aWxzJyk7XG5cbkJhY2tib25lLnN5bmMgPSBOZXR3b3JrLnNldHVwKHtcbiAgICB1cmw6IFN0b3JhZ2Uub3B0aW9ucy5hcGlfdXJsLFxuICAgIGVycm9yOiBmdW5jdGlvbiAoeGhyLCBtZXRob2QsIG1vZGVsLCBvcHRpb25zKSB7XG4gICAgICAgIHZhciBlcnJvclZpZXcgPSBuZXcgRXJyb3JNb2RhbFZpZXcoe1xuICAgICAgICAgICAgZGVmZXJyZWQ6IHhoclxuICAgICAgICB9KTtcbiAgICAgICAgZXJyb3JWaWV3Lm9uKCdyZXBlYXQ6cmVxdWVzdCcsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIEJhY2tib25lLnN5bmMobWV0aG9kLCBtb2RlbCwgb3B0aW9ucyk7XG4gICAgICAgIH0pO1xuICAgICAgICBlcnJvclZpZXcucmVuZGVyKCk7XG4gICAgfVxufSk7XG5cblxudmFyIEFwcGxpY2F0aW9uID0gQmFja2JvbmUuTWFyaW9uZXR0ZS5BcHBsaWNhdGlvbi5leHRlbmQoe1xuICAgIHJlZ2lvbjogJ2JvZHknLFxuXG4gICAgb25TdGFydDogZnVuY3Rpb24gKCkge1xuICAgICAgICB0aGlzLmxheW91dCA9IG5ldyBMYXlvdXQoKTtcbiAgICAgICAgdGhpcy5zaG93Vmlldyh0aGlzLmxheW91dCk7XG4gICAgICAgIHRoaXMuY29udHJvbGxlciA9IG5ldyBDb250cm9sbGVyKHtcbiAgICAgICAgICAgIGxheW91dDogdGhpcy5sYXlvdXRcbiAgICAgICAgfSk7XG4gICAgICAgIHRoaXMucm91dGVyID0gbmV3IFJvdXRlcih7XG4gICAgICAgICAgICBjb250cm9sbGVyOiB0aGlzLmNvbnRyb2xsZXJcbiAgICAgICAgfSk7XG4gICAgICAgIEJhY2tib25lLmhpc3Rvcnkuc3RhcnQoKTtcbiAgICB9XG59KTtcblxudmFyIGFwcCA9IG5ldyBBcHBsaWNhdGlvbigpO1xuYXBwLnN0YXJ0KCk7XG5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL3NyYy9qcy9hcHAuanNcbi8vIG1vZHVsZSBpZCA9IDEzXG4vLyBtb2R1bGUgY2h1bmtzID0gMCIsIiQuZm4uc2VyaWFsaXplT2JqZWN0ID0gZnVuY3Rpb24oKSB7XG4gICAgdmFyIG8gPSB7fTtcbiAgICB2YXIgJGRpc2FibGVkID0gJCh0aGlzKS5maW5kKCc6ZGlzYWJsZWQnKS5yZW1vdmVBdHRyKCdkaXNhYmxlZCcpO1xuICAgIHZhciBhID0gdGhpcy5zZXJpYWxpemVBcnJheSgpO1xuICAgICQuZWFjaChhLCBmdW5jdGlvbigpIHtcbiAgICAgICAgaWYgKG9bdGhpcy5uYW1lXSAhPT0gdW5kZWZpbmVkKSB7XG4gICAgICAgICAgICBpZiAoIW9bdGhpcy5uYW1lXS5wdXNoKSB7XG4gICAgICAgICAgICAgICAgb1t0aGlzLm5hbWVdID0gW29bdGhpcy5uYW1lXV07XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBvW3RoaXMubmFtZV0ucHVzaCh0aGlzLnZhbHVlIHx8ICcnKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIG9bdGhpcy5uYW1lXSA9IHRoaXMudmFsdWUgfHwgJyc7XG4gICAgICAgIH1cbiAgICB9KTtcblxuICAgIHZhciBjaGVja2JveGVzID0gdGhpcy5maW5kKCdpbnB1dFt0eXBlPVwiY2hlY2tib3hcIl0nKTtcbiAgICAkLmVhY2goY2hlY2tib3hlcywgZnVuY3Rpb24oKSB7XG4gICAgICAgIGlmICh0aGlzLm5hbWUpIHtcbiAgICAgICAgICBpZihvW3RoaXMubmFtZV0gPT09ICd0cnVlJyB8fFxuICAgICAgICAgICAgIG9bdGhpcy5uYW1lXSA9PT0gJ29uJyB8fFxuICAgICAgICAgICAgIG9bdGhpcy5uYW1lXSA9PT0gJzEnXG4gICAgICAgICAgKSB7XG4gICAgICAgICAgICAgIG9bdGhpcy5uYW1lXSA9IHRydWU7XG4gICAgICAgICAgfVxuICAgICAgICAgIGlmKG9bdGhpcy5uYW1lXSA9PT0gJ2ZhbHNlJyB8fFxuICAgICAgICAgICAgIG9bdGhpcy5uYW1lXSA9PT0gJ29mZicgfHxcbiAgICAgICAgICAgICBvW3RoaXMubmFtZV0gPT09ICcwJ1xuICAgICAgICAgICkge1xuICAgICAgICAgICAgICBvW3RoaXMubmFtZV0gPSBmYWxzZTtcbiAgICAgICAgICB9XG4gICAgICAgICAgaWYob1t0aGlzLm5hbWVdID09PSB1bmRlZmluZWQpIHtcbiAgICAgICAgICAgICAgb1t0aGlzLm5hbWVdID0gZmFsc2U7XG4gICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfSk7XG4gICAgdmFyIHJhZGlvYm94ZXMgPSB0aGlzLmZpbmQoJ2lucHV0W3R5cGU9XCJyYWRpb1wiXScpO1xuICAgICQuZWFjaChyYWRpb2JveGVzLCBmdW5jdGlvbigpIHtcbiAgICAgICAgaWYgKHRoaXMubmFtZSkge1xuICAgICAgICAgIGlmKG9bdGhpcy5uYW1lXSA9PT0gJ3RydWUnKSB7XG4gICAgICAgICAgICAgIG9bdGhpcy5uYW1lXSA9IHRydWU7XG4gICAgICAgICAgfVxuICAgICAgICAgIGlmKG9bdGhpcy5uYW1lXSA9PT0gJ2ZhbHNlJykge1xuICAgICAgICAgICAgICBvW3RoaXMubmFtZV0gPSBmYWxzZTtcbiAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9KTtcbiAgICAkZGlzYWJsZWQuYXR0cignZGlzYWJsZWQnLCdkaXNhYmxlZCcpO1xuICAgIHJldHVybiBvO1xufTtcblxuXG5fLmV4dGVuZChCYWNrYm9uZS5IaXN0b3J5LnByb3RvdHlwZSwge1xuICAgIC8qIGh0dHBzOi8vZ2l0aHViLmNvbS9qYXNoa2VuYXMvYmFja2JvbmUvaXNzdWVzLzMzOTkgKi9cbiAgICBnZXRIYXNoOiBmdW5jdGlvbiAod2luZG93KSB7XG4gICAgICAgIHZhciBtYXRjaCA9ICh3aW5kb3cgfHwgdGhpcykubG9jYXRpb24uaHJlZi5tYXRjaCgvIyguKikkLyk7XG4gICAgICAgIHJldHVybiBtYXRjaCA/IHRoaXMuZGVjb2RlRnJhZ21lbnQobWF0Y2hbMV0ucmVwbGFjZSgvIy4qJC8sICcnKSkgOiAnJztcbiAgICB9XG59KTtcblxuXG5NYUdyaWQuR3JpZFZpZXcucHJvdG90eXBlLmNsYXNzTmFtZSA9ICdtZy1jb250YWluZXIgY2xlYXJmaXgnO1xuTWFHcmlkLkdyaWRWaWV3LnByb3RvdHlwZS50ZW1wbGF0ZSA9ICBfLnRlbXBsYXRlKFtcbiAgICAnPGRpdiBjbGFzcz1cIm1nLXRhYmxlIGJveC1ib2R5IG5vLXBhZGRpbmdcIj4nLFxuICAgICcgICAgPHRhYmxlIGNsYXNzPVwidGFibGUgdGFibGUtY29uZGVuc2VkIHRhYmxlLWhvdmVyXCI+JyxcbiAgICAnICAgICAgIDx0aGVhZCBkYXRhLXJlZ2lvbj1cImhlYWRlclwiPjwvdGhlYWQ+JyxcbiAgICAnICAgICAgIDx0Ym9keSBkYXRhLXJlZ2lvbj1cImJvZHlcIj48L3Rib2R5PicsXG4gICAgJyAgICAgICA8dGZvb3QgZGF0YS1yZWdpb249XCJmb290ZXJcIj48L3Rmb290PicsXG4gICAgJyAgIDwvdGFibGU+JyxcbiAgICAnPC9kaXY+JyxcbiAgICAnPGRpdiBjbGFzcz1cImJveC1mb290ZXJcIj4nLFxuICAgICcgICA8ZGl2IGNsYXNzPVwibWctc2l6ZXIgcHVsbC1sZWZ0XCIgZGF0YS1yZWdpb249XCJzaXplclwiPjwvZGl2PicsXG4gICAgJyAgIDxkaXYgY2xhc3M9XCJtZy1wYWdpbmF0b3IgcHVsbC1yaWdodFwiIGRhdGEtcmVnaW9uPVwicGFnaW5hdG9yXCI+PC9kaXY+JyxcbiAgICAnPC9kaXY+J1xuXS5qb2luKCcnKSk7XG5cbk1hR3JpZC5QYWdpbmF0b3JWaWV3LnByb3RvdHlwZS5jdXJyZW50UGFnZUNsYXNzID0gJ2FjdGl2ZSc7XG5NYUdyaWQuUGFnaW5hdG9yVmlldy5wcm90b3R5cGUuZGlzYWJsZWRQYWdlQ2xhc3MgPSAnZGlzYWJsZWQnO1xuXG5NYUdyaWQuUGFnaW5hdG9yVmlldy5wcm90b3R5cGUudGVtcGxhdGUgPSBfLnRlbXBsYXRlKFtcbiAgICAnPHVsIGNsYXNzPVwicGFnaW5hdGlvbiBwYWdpbmF0aW9uLXNtIG5vLW1hcmdpbiBwdWxsLXJpZ2h0XCI+JyxcbiAgICAnPCUgZm9yKGkgaW4gcGFnZXMpIHsgJT4nLFxuICAgICc8bGkgY2xhc3M9XCI8JT0gcGFnZXNbaV0uY2xhc3NOYW1lICU+XCIgPicsXG4gICAgJyAgIDxhIGhyZWY9XCIjXCIgdGl0bGU9XCI8JT0gcGFnZXNbaV0ucGFnZU51bSAlPlwiICcsXG4gICAgJyAgICAgIDwlIGlmKCFwYWdlc1tpXS5pc0Rpc2FibGVkICYmICFwYWdlc1tpXS5pc0N1cnJlbnQpIHsgJT5kYXRhLXBhZ2U9XCI8JT0gcGFnZXNbaV0ucGFnZU51bSAlPlwiPCUgfSAlPj4nLFxuICAgICcgICAgICAgPCU9IHBhZ2VzW2ldLmxhYmVsICU+JyxcbiAgICAnICAgPC9hPicsXG4gICAgJzwvbGk+JyxcbiAgICAnPCUgfSAlPicsXG4gICAgJzwvdWw+J1xuXS5qb2luKCdcXG4nKSk7XG5cbk1hcmlvbmV0dGUuVmlldy5wcm90b3R5cGUuZ2V0T3B0aW9uT3JOdWxsID0gZnVuY3Rpb24oYXR0cikge1xuICAgIHZhciB2YWwgPSB0aGlzLmdldE9wdGlvbihhdHRyKTtcbiAgICBpZiAodmFsID09PSAnJykge1xuICAgICAgICByZXR1cm4gbnVsbDtcbiAgICB9XG4gICAgcmV0dXJuIHZhbDtcbn07XG5cbiQoZG9jdW1lbnQpLm9uKCdjbGljaycsIGZ1bmN0aW9uIChlKSB7XG4gICAgJCgnW2RhdGEtdG9nZ2xlPVwicG9wb3ZlclwiXSxbZGF0YS1vcmlnaW5hbC10aXRsZV0nKS5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgLy90aGUgJ2lzJyBmb3IgYnV0dG9ucyB0aGF0IHRyaWdnZXIgcG9wdXBzXG4gICAgICAgIC8vdGhlICdoYXMnIGZvciBpY29ucyB3aXRoaW4gYSBidXR0b24gdGhhdCB0cmlnZ2VycyBhIHBvcHVwXG4gICAgICAgIGlmICghJCh0aGlzKS5pcyhlLnRhcmdldCkgJiYgJCh0aGlzKS5oYXMoZS50YXJnZXQpLmxlbmd0aCA9PT0gMCAmJiAkKCcucG9wb3ZlcicpLmhhcyhlLnRhcmdldCkubGVuZ3RoID09PSAwKSB7XG4gICAgICAgICAgICAoKCQodGhpcykucG9wb3ZlcignaGlkZScpLmRhdGEoJ2JzLnBvcG92ZXInKXx8e30pLmluU3RhdGV8fHt9KS5jbGljayA9IGZhbHNlICAvLyBmaXggZm9yIEJTIDMuMy42XG4gICAgICAgIH1cblxuICAgIH0pO1xufSk7XG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvanMvbGliL3BhdGNoZXMuanNcbi8vIG1vZHVsZSBpZCA9IDE0XG4vLyBtb2R1bGUgY2h1bmtzID0gMCIsInZhciBOZXR3b3JrID0gcmVxdWlyZSgnLi9uZXR3b3JrJyk7XG52YXIgU3RvcmFnZSA9IHJlcXVpcmUoJy4vc3RvcmFnZScpO1xudmFyIFV0aWxzID0gcmVxdWlyZSgnbGliL3V0aWxzJyk7XG52YXIgUGxheWVyR2FtZXNWaWV3ID0gcmVxdWlyZSgndmlld3MvcGxheWVyZ2FtZXMvdmlldycpO1xudmFyIFRyYW5zYWN0aW9uc1ZpZXcgPSByZXF1aXJlKCd2aWV3cy90cmFuc2FjdGlvbnMvdmlldycpO1xudmFyIFJvdW5kVmlldyA9IHJlcXVpcmUoJ3ZpZXdzL3JvdW5kL3ZpZXcnKTtcblxuXG52YXIgQ29udHJvbGxlciA9IE1hcmlvbmV0dGUuT2JqZWN0LmV4dGVuZCh7XG4gICAgbGFzdFBsYXllckdhbWVzU3RhdGU6ICcnLFxuXG4gICAgaW5pdGlhbGl6ZTogZnVuY3Rpb24gKG9wdGlvbnMpIHtcbiAgICAgICAgdGhpcy5sYXlvdXQgPSBvcHRpb25zLmxheW91dDtcbiAgICAgICAgdGhpcy5saXN0ZW5Ubyh0aGlzLmxheW91dCwgJ3N0YXRlOmNoYW5nZWQnLCB0aGlzLm9uU3RhdGVDaGFuZ2VkKTtcbiAgICB9LFxuXG4gICAgb25TdGF0ZUNoYW5nZWQ6IGZ1bmN0aW9uIChzdGF0ZSkge1xuICAgICAgICB2YXIgcGFyYW1zID0gTmV0d29yay5yZXF1aXJlZEhhc2hQYXJhbXMoKTtcbiAgICAgICAgXy5leHRlbmQocGFyYW1zLCBzdGF0ZSk7XG5cbiAgICAgICAgdmFyIG5ld19oYXNoID0gVXRpbHMuZW5jb2RlUXVlcnlEYXRhKHBhcmFtcyk7XG4gICAgICAgIGlmICghcGFyYW1zLnNob3cpIHtcbiAgICAgICAgICAgIHRoaXMubGFzdFBsYXllckdhbWVzU3RhdGUgPSBuZXdfaGFzaDtcbiAgICAgICAgfVxuICAgICAgICBCYWNrYm9uZS5oaXN0b3J5Lm5hdmlnYXRlKG5ld19oYXNoLCB7dHJpZ2dlcjogZmFsc2UsIHJlcGxhY2U6IHRydWV9KTtcbiAgICB9LFxuXG4gICAgaW5kZXg6IGZ1bmN0aW9uIChoYXNoKSB7XG4gICAgICAgIHZhciBwYXJhbXMgPSBVdGlscy5wYXJzZVF1ZXJ5RGF0YShoYXNoKTtcbiAgICAgICAgcGFyYW1zLmdvX2JhY2sgPSB0aGlzLmxhc3RQbGF5ZXJHYW1lc1N0YXRlO1xuXG4gICAgICAgIHRoaXMubGF5b3V0LnNob3dPdmVybGF5KCk7XG4gICAgICAgICQud2hlbi5hcHBseSgkLCBTdG9yYWdlLmZldGNoSW5pdGlhbERhdGEoKSkudGhlbihfLmJpbmQoZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBpZiAocGFyYW1zLnNob3cgPT09ICd0cmFuc2FjdGlvbnMnKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5sYXlvdXQuc2hvdyhuZXcgVHJhbnNhY3Rpb25zVmlldyhwYXJhbXMpKTtcbiAgICAgICAgICAgIH0gZWxzZSBpZiAocGFyYW1zLnNob3cgPT09ICdyb3VuZCcpIHtcbiAgICAgICAgICAgICAgICB0aGlzLmxheW91dC5zaG93KG5ldyBSb3VuZFZpZXcocGFyYW1zKSk7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIHRoaXMubGFzdFBsYXllckdhbWVzU3RhdGUgPSBoYXNoO1xuICAgICAgICAgICAgICAgIHRoaXMubGF5b3V0LnNob3cobmV3IFBsYXllckdhbWVzVmlldyhwYXJhbXMpKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHRoaXMubGF5b3V0LmhpZGVPdmVybGF5KCk7XG4gICAgICAgIH0sIHRoaXMpKTtcbiAgICB9XG59KTtcblxubW9kdWxlLmV4cG9ydHMgPSBDb250cm9sbGVyO1xuXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvanMvYXBwL2NvbnRyb2xsZXIuanNcbi8vIG1vZHVsZSBpZCA9IDE1XG4vLyBtb2R1bGUgY2h1bmtzID0gMCIsInZhciBRdWVyeVBhcmFtc0NvbGxlY3Rpb24gPSBCYWNrYm9uZS5Db2xsZWN0aW9uLmV4dGVuZCh7XG4gICAgcXVlcnlQYXJhbXM6IHt9LFxuXG4gICAgaW5pdGlhbGl6ZTogZnVuY3Rpb24obW9kZWxzLCBvcHRpb25zKSB7XG4gICAgICAgIEJhY2tib25lLkNvbGxlY3Rpb24ucHJvdG90eXBlLmluaXRpYWxpemUuYXBwbHkodGhpcywgYXJndW1lbnRzKTtcbiAgICAgICAgb3B0aW9ucyA9IG9wdGlvbnMgfHwge307XG4gICAgICAgIHRoaXMucXVlcnlQYXJhbXMgPSBfLmV4dGVuZCh7fSwgdGhpcy5xdWVyeVBhcmFtcywgb3B0aW9ucy5xdWVyeVBhcmFtcyk7XG4gICAgfSxcblxuICAgIGZldGNoOiBmdW5jdGlvbihvcHRpb25zKSB7XG4gICAgICAgIG9wdGlvbnMgPSBvcHRpb25zIHx8IHt9O1xuXG4gICAgICAgIHZhciBkYXRhID0gb3B0aW9ucy5kYXRhIHx8IHt9O1xuXG4gICAgICAgIHZhciB1cmwgPSBvcHRpb25zLnVybCB8fCB0aGlzLnVybCB8fCAnJztcbiAgICAgICAgaWYgKF8uaXNGdW5jdGlvbih1cmwpKSB1cmwgPSB1cmwuY2FsbCh0aGlzKTtcblxuICAgICAgICBvcHRpb25zLnVybCA9IHVybDtcbiAgICAgICAgb3B0aW9ucy5kYXRhID0gZGF0YTtcblxuICAgICAgICBfLmVhY2godGhpcy5xdWVyeVBhcmFtcywgZnVuY3Rpb24odmFsdWUsIGspIHtcbiAgICAgICAgICAgIGlmIChfLmlzRnVuY3Rpb24odmFsdWUpKSB2YWx1ZSA9IHZhbHVlLmNhbGwodGhpcyk7XG4gICAgICAgICAgICBpZiAodmFsdWUgPT0gbnVsbCkge1xuICAgICAgICAgICAgICAgIGRlbGV0ZSBkYXRhW2tdO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICBkYXRhW2tdID0gdmFsdWU7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0sIHRoaXMpO1xuXG4gICAgICAgIHJldHVybiBCYWNrYm9uZS5Db2xsZWN0aW9uLnByb3RvdHlwZS5mZXRjaC5jYWxsKHRoaXMsIG9wdGlvbnMpO1xuICAgIH0sXG5cbiAgICBwYXJzZTogZnVuY3Rpb24ocmVzcCkge1xuICAgICAgICByZXR1cm4gcmVzcC5pdGVtcztcbiAgICB9XG59KTtcblxudmFyIFRvdGFsc0NvbGxlY3Rpb24gPSBCYWNrYm9uZS5Db2xsZWN0aW9uLmV4dGVuZCh7XG4gICAgLy8gc291cmNlIGNvbGxlY3Rpb25cbiAgICBzb3VyY2U6IG51bGwsXG4gICAgLy8gZmllbGQgY29sbGVjdGlvbiB3aWxsIGJlIGdyb3VwZWQgYnlcbiAgICBncm91cEJ5OiBudWxsLFxuICAgIC8vIHNob3cgbnVsbCBpZiBubyB2YWx1ZXNcbiAgICBudWxsSWZOb1ZhbHVlczogZmFsc2UsXG4gICAgLy8gc2hvdyBudWxsIGlmIHNvbWUgdmFsdWVzIGFyZSBudWxsXG4gICAgbnVsbElmSGFzTnVsbHM6IGZhbHNlLFxuICAgIC8vIHJlc3VsdCBmaWVsZHNcbiAgICAvKiogQHR5cGUge3tuYW1lOiBzdHJpbmcsIHNvdXJjZTogc3RyaW5nLCB2YWx1ZTogc3RyaW5nfEZ1bmN0aW9ufVtdfSAqL1xuICAgIGZpZWxkczogW10sXG5cbiAgICBpbml0aWFsaXplOiBmdW5jdGlvbihtb2RlbHMsIG9wdGlvbnMpIHtcbiAgICAgICAgb3B0aW9ucyA9IG9wdGlvbnMgfHwge307XG4gICAgICAgIF8uZXh0ZW5kKHRoaXMsIG9wdGlvbnMpO1xuXG4gICAgICAgIF8uZm9yRWFjaChbJ2FkZCcsICdyZW1vdmUnLCAncmVzZXQnLCAnc3luYyddLCBmdW5jdGlvbihldmVudCkge1xuICAgICAgICAgICAgdGhpcy5zb3VyY2Uub24oZXZlbnQsIF8uYmluZCh0aGlzLl91cGRhdGUsIHRoaXMpKVxuICAgICAgICB9LCB0aGlzKTtcbiAgICB9LFxuXG4gICAgaGFzTnVsbHM6IGZ1bmN0aW9uKGZpZWxkLCBtb2RlbHMpIHtcbiAgICAgICAgcmV0dXJuIF8uc29tZShtb2RlbHMsIGZ1bmN0aW9uKG1vZGVsKSB7XG4gICAgICAgICAgICByZXR1cm4gbW9kZWwuZ2V0KGZpZWxkKSA9PT0gbnVsbDtcbiAgICAgICAgfSlcbiAgICB9LFxuXG4gICAgc3VtOiBmdW5jdGlvbihtb2RlbHMsIGZpZWxkKSB7XG4gICAgICAgIGlmICh0aGlzLm51bGxJZk5vVmFsdWVzICYmICFtb2RlbHMubGVuZ3RoKSByZXR1cm4gbnVsbDtcbiAgICAgICAgaWYgKHRoaXMubnVsbElmSGFzTnVsbHMgJiYgdGhpcy5oYXNOdWxscyhmaWVsZCwgbW9kZWxzKSkgcmV0dXJuIG51bGw7XG5cbiAgICAgICAgcmV0dXJuIF8ucmVkdWNlKG1vZGVscywgZnVuY3Rpb24oYWdnLCBtb2RlbCkge1xuICAgICAgICAgICAgdmFyIHZhbHVlID0gQmlnTnVtYmVyKG1vZGVsLmdldChmaWVsZCkpO1xuICAgICAgICAgICAgaWYgKHZhbHVlLmlzTmFOKCkpIHJldHVybiBhZ2c7XG4gICAgICAgICAgICByZXR1cm4gYWdnLnBsdXModmFsdWUpO1xuICAgICAgICB9LCBCaWdOdW1iZXIoMCkpO1xuICAgIH0sXG5cbiAgICBjb3VudDogZnVuY3Rpb24obW9kZWxzLCBmaWVsZCkge1xuICAgICAgICBpZiAodGhpcy5udWxsSWZOb1ZhbHVlcyAmJiAhbW9kZWxzLmxlbmd0aCkgcmV0dXJuIG51bGw7XG4gICAgICAgIGlmICh0aGlzLm51bGxJZkhhc051bGxzICYmIHRoaXMuaGFzTnVsbHMoZmllbGQsIG1vZGVscykpIHJldHVybiBudWxsO1xuXG4gICAgICAgIHJldHVybiBfLnJlZHVjZShtb2RlbHMsIGZ1bmN0aW9uKGFnZywgbW9kZWwpIHtcbiAgICAgICAgICAgIHJldHVybiBtb2RlbC5oYXMoZmllbGQpID8gKGFnZyArIDEpIDogYWdnO1xuICAgICAgICB9LCAwKTtcbiAgICB9LFxuXG4gICAgY291bnRVbmlxOiBmdW5jdGlvbihtb2RlbHMsIGZpZWxkKSB7XG4gICAgICAgIGlmICh0aGlzLm51bGxJZk5vVmFsdWVzICYmICFtb2RlbHMubGVuZ3RoKSByZXR1cm4gbnVsbDtcbiAgICAgICAgaWYgKHRoaXMubnVsbElmSGFzTnVsbHMgJiYgdGhpcy5oYXNOdWxscyhmaWVsZCwgbW9kZWxzKSkgcmV0dXJuIG51bGw7XG5cbiAgICAgICAgcmV0dXJuIF8udW5pcShtb2RlbHMsIGZ1bmN0aW9uKG1vZGVsKSB7XG4gICAgICAgICAgICByZXR1cm4gbW9kZWwuZ2V0KGZpZWxkKTtcbiAgICAgICAgfSkubGVuZ3RoO1xuICAgIH0sXG5cbiAgICBwcm9jZXNzU291cmNlOiBmdW5jdGlvbihzb3VyY2UpIHtcbiAgICAgICAgcmV0dXJuIHNvdXJjZTtcbiAgICB9LFxuXG4gICAgX3VwZGF0ZTogZnVuY3Rpb24oKSB7XG4gICAgICAgIHZhciBzb3VyY2UgPSB0aGlzLnByb2Nlc3NTb3VyY2UodGhpcy5zb3VyY2UpO1xuICAgICAgICBpZiAoIShzb3VyY2UgaW5zdGFuY2VvZiBCYWNrYm9uZS5Db2xsZWN0aW9uKSkge1xuICAgICAgICAgICAgc291cmNlID0gbmV3IEJhY2tib25lLkNvbGxlY3Rpb24oc291cmNlKTtcbiAgICAgICAgfVxuICAgICAgICB2YXIgZ3JvdXBCeSA9IHRoaXMuZ3JvdXBCeTtcbiAgICAgICAgdmFyIGdyb3VwcyA9IHNvdXJjZS5ncm91cEJ5KGdyb3VwQnkpO1xuXG4gICAgICAgIHZhciBtb2RlbHMgPSBfLm1hcChncm91cHMsIGZ1bmN0aW9uKGdyb3VwLCBncm91cE5hbWUpIHtcbiAgICAgICAgICAgIHZhciBtb2RlbCA9IHt9O1xuICAgICAgICAgICAgbW9kZWxbZ3JvdXBCeV0gPSBncm91cE5hbWU7XG5cbiAgICAgICAgICAgIF8uZm9yRWFjaCh0aGlzLmZpZWxkcywgZnVuY3Rpb24oZmllbGQpIHtcbiAgICAgICAgICAgICAgICB2YXIgZmllbGROYW1lID0gZmllbGQubmFtZSB8fCBmaWVsZC5zb3VyY2UsXG4gICAgICAgICAgICAgICAgICAgIHNvdXJjZUZpZWxkID0gZmllbGQuc291cmNlIHx8IGZpZWxkLm5hbWUsXG4gICAgICAgICAgICAgICAgICAgIHZhbHVlID0gZmllbGQudmFsdWU7XG5cbiAgICAgICAgICAgICAgICBpZiAoXy5pc0Z1bmN0aW9uKHZhbHVlKSkge1xuICAgICAgICAgICAgICAgICAgICBtb2RlbFtmaWVsZE5hbWVdID0gdmFsdWUuY2FsbCh0aGlzLCBncm91cCwgc291cmNlRmllbGQpO1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIG1vZGVsW2ZpZWxkTmFtZV0gPSB0aGlzW3ZhbHVlXS5jYWxsKHRoaXMsIGdyb3VwLCBzb3VyY2VGaWVsZCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSwgdGhpcyk7XG5cbiAgICAgICAgICAgIHJldHVybiBtb2RlbDtcbiAgICAgICAgfSwgdGhpcyk7XG5cbiAgICAgICAgdGhpcy5yZXNldChtb2RlbHMpO1xuICAgIH1cbn0pO1xuXG5tb2R1bGUuZXhwb3J0cyA9IHtcbiAgICBRdWVyeVBhcmFtc0NvbGxlY3Rpb246IFF1ZXJ5UGFyYW1zQ29sbGVjdGlvbixcbiAgICBUb3RhbHNDb2xsZWN0aW9uOiBUb3RhbHNDb2xsZWN0aW9uXG59O1xuXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvanMvbGliL21vZGVsLmpzXG4vLyBtb2R1bGUgaWQgPSAxNlxuLy8gbW9kdWxlIGNodW5rcyA9IDAiLCJ2YXIgU3RvcmFnZSA9IHJlcXVpcmUoJ2FwcC9zdG9yYWdlJyk7XG52YXIgTW9kZWxzID0gcmVxdWlyZSgnYXBwL21vZGVscycpO1xudmFyIGRlZmF1bHRzID0gcmVxdWlyZSgnYXBwL2RlZmF1bHRzJyk7XG52YXIgRm9ybVV0aWxzID0gcmVxdWlyZSgnbGliL2Zvcm0nKTtcbnZhciBsb2NhbGVzID0gcmVxdWlyZSgnbGliL2xvY2FsZXMnKTtcbnZhciBVdGlscyA9IHJlcXVpcmUoJ2xpYi91dGlscycpO1xuXG52YXIgUGxheWVyR2FtZXNHcmlkVmlldyA9IHJlcXVpcmUoJy4vZ3JpZCcpO1xudmFyIHRwbCA9IHJlcXVpcmUoJy4vdmlldy5lanMnKTtcbnZhciBUb3RhbHNHcmlkVmlldyA9IHJlcXVpcmUoJ3ZpZXdzL3RvdGFscy92aWV3Jyk7XG5cblxudmFyIFBsYXllckdhbWVzVmlldyA9IEJhY2tib25lLk1hcmlvbmV0dGUuVmlldy5leHRlbmQoe1xuICAgIG9wdGlvbnM6IHtcbiAgICAgICAgZ2FtZV9pZDogZGVmYXVsdHMuZ2FtZV9pZCxcbiAgICAgICAgdHo6IGRlZmF1bHRzLnR6LFxuICAgICAgICBtb2RlOiBkZWZhdWx0cy5tb2RlLFxuICAgICAgICByZXBvcnRfdHlwZTogZGVmYXVsdHMucmVwb3J0X3R5cGUsXG4gICAgICAgIHN0YXJ0X2RhdGU6IGRlZmF1bHRzLnN0YXJ0X2RhdGUsXG4gICAgICAgIGVuZF9kYXRlOiBkZWZhdWx0cy5lbmRfZGF0ZSxcbiAgICAgICAgaGVhZGVyOiBkZWZhdWx0cy5oZWFkZXIsXG4gICAgICAgIHRvdGFsczogZGVmYXVsdHMudG90YWxzLFxuICAgICAgICBpbmZvOiBkZWZhdWx0cy5pbmZvLFxuICAgICAgICByZWFsdGltZTogZGVmYXVsdHMucmVhbHRpbWUsXG4gICAgICAgIGxhbmc6IGRlZmF1bHRzLmxhbmdcbiAgICB9LFxuICAgIHRlbXBsYXRlOiB0cGwsXG4gICAgdGVtcGxhdGVDb250ZXh0OiBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHJldHVybiB7XG4gICAgICAgICAgICBhdmFpbGFibGVfbW9kZXM6IFN0b3JhZ2UuYXZhaWxhYmxlTW9kZXMudG9KU09OKCksXG4gICAgICAgICAgICByZXBvcnRfdHlwZXM6IFN0b3JhZ2UucmVwb3J0VHlwZXMudG9KU09OKCksXG4gICAgICAgICAgICBhdmFpbGFibGVfZ2FtZXM6IFN0b3JhZ2UuYXZhaWxhYmxlR2FtZXMudG9KU09OKCksXG4gICAgICAgICAgICBtb2RlOiB0aGlzLmdldE9wdGlvbignbW9kZScpLFxuICAgICAgICAgICAgcmVwb3J0X3R5cGU6IHRoaXMuZ2V0T3B0aW9uKCdyZXBvcnRfdHlwZScpLFxuICAgICAgICAgICAgZ2FtZV9pZDogdGhpcy5nZXRPcHRpb24oJ2dhbWVfaWQnKSxcbiAgICAgICAgICAgIGhlYWRlcjogdGhpcy5nZXRPcHRpb24oJ2hlYWRlcicpLFxuICAgICAgICAgICAgdG90YWxzOiB0aGlzLmdldE9wdGlvbigndG90YWxzJyksXG4gICAgICAgICAgICB0cmFuc2xhdGU6IGxvY2FsZXMudHJhbnNsYXRlLmJpbmQobG9jYWxlcylcbiAgICAgICAgfVxuICAgIH0sXG4gICAgdWk6IHtcbiAgICAgICAgbGlzdFJlZ2lvbjogJy5hcHAtbGlzdC1yZWdpb24nLFxuICAgICAgICB0b3RhbHNSZWdpb246ICcuYXBwLXRvdGFscy1yZWdpb24nLFxuICAgICAgICB0b3RhbHNXaWRnZXQ6ICcuYXBwLXRvdGFscy13aWRnZXQnLFxuICAgICAgICBvdmVybGF5OiAnLm92ZXJsYXknXG4gICAgfSxcbiAgICByZWdpb25zOiB7XG4gICAgICAgIGxpc3RSZWdpb246ICdAdWkubGlzdFJlZ2lvbicsXG4gICAgICAgIHRvdGFsc1JlZ2lvbjogJ0B1aS50b3RhbHNSZWdpb24nXG4gICAgfSxcbiAgICBjb2xsZWN0aW9uRXZlbnRzOiB7XG4gICAgICAgIHVwZGF0ZTogJ29uQ29sbGVjdGlvblVwZGF0ZScsXG4gICAgICAgIHJlcXVlc3Q6ICdvbkNvbGxlY3Rpb25SZXF1ZXN0JyxcbiAgICAgICAgc3luYzogJ29uQ29sbGVjdGlvblN5bmMnLFxuICAgICAgICBlcnJvcjogJ29uQ29sbGVjdGlvbkVycm9yJ1xuICAgIH0sXG4gICAgY2hpbGRWaWV3RXZlbnRzOiB7XG4gICAgICAgICdtYWdyaWQ6cm93OmNlbGw6c2hvdzp0cmFuc2FjdGlvbnMnOiAnb25TaG93VHJhbnNhY3Rpb25zJ1xuICAgIH0sXG5cbiAgICBiZWhhdmlvcnM6IFt7XG4gICAgICAgIGJlaGF2aW9yQ2xhc3M6IEZvcm1VdGlscy5Gb3JtQmVoYXZpb3IsXG4gICAgICAgIHN1Ym1pdEV2ZW50OiAnc2VhcmNoOmZvcm06c3VibWl0J1xuICAgIH1dLFxuXG4gICAgaW5pdGlhbGl6ZTogZnVuY3Rpb24gKCkge1xuICAgICAgICBsb2NhbGVzLnNldCh0aGlzLm9wdGlvbnMubGFuZyk7XG4gICAgICAgIHRoaXMub3B0aW9ucy50eiA9IHBhcnNlSW50KHRoaXMub3B0aW9ucy50eik7XG4gICAgICAgIGlmIChfLmlzTmFOKHRoaXMub3B0aW9ucy50eikpIHtcbiAgICAgICAgICAgIHRoaXMub3B0aW9ucy50eiA9IGRlZmF1bHRzLnR6O1xuICAgICAgICB9XG4gICAgICAgIGlmKHRoaXMub3B0aW9ucy5zdGFydF9kYXRlKSB7XG4gICAgICAgICAgICB0aGlzLm9wdGlvbnMuc3RhcnRfZGF0ZSA9IG1vbWVudCh0aGlzLm9wdGlvbnMuc3RhcnRfZGF0ZSwgJ1lZWVlNTUREVEhIbW0nKTtcbiAgICAgICAgICAgIHRoaXMub3B0aW9ucy5zdGFydF9kYXRlID0gdGhpcy5vcHRpb25zLnN0YXJ0X2RhdGUudXRjT2Zmc2V0KHRoaXMub3B0aW9ucy50eiwgdHJ1ZSk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICB0aGlzLm9wdGlvbnMuc3RhcnRfZGF0ZSA9IG51bGw7XG4gICAgICAgIH1cbiAgICAgICAgaWYodGhpcy5vcHRpb25zLmVuZF9kYXRlKSB7XG4gICAgICAgICAgICB0aGlzLm9wdGlvbnMuZW5kX2RhdGUgPSBtb21lbnQodGhpcy5vcHRpb25zLmVuZF9kYXRlLCAnWVlZWU1NRERUSEhtbScpO1xuICAgICAgICAgICAgdGhpcy5vcHRpb25zLmVuZF9kYXRlID0gdGhpcy5vcHRpb25zLmVuZF9kYXRlLnV0Y09mZnNldCh0aGlzLm9wdGlvbnMudHosIHRydWUpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgdGhpcy5vcHRpb25zLmVuZF9kYXRlID0gbnVsbDtcbiAgICAgICAgfVxuXG4gICAgICAgIHRoaXMudmlzaWJsZUNvbGxlY3Rpb24gPSBuZXcgQmFja2JvbmUuQ29sbGVjdGlvbigpO1xuICAgICAgICB0aGlzLmxvYWRpbmdNb2RlbCA9IG5ldyBCYWNrYm9uZS5Nb2RlbCgpO1xuXG4gICAgICAgIHRoaXMuY29sbGVjdGlvbiA9IG5ldyBNb2RlbHMuUGxheWVyR2FtZUNvbGxlY3Rpb24oW10sIHtcbiAgICAgICAgICAgIHF1ZXJ5UGFyYW1zOiB7XG4gICAgICAgICAgICAgICAgZ2FtZV9pZDogXy5iaW5kKHRoaXMuZ2V0T3B0aW9uT3JOdWxsLCB0aGlzLCAnZ2FtZV9pZCcpLFxuICAgICAgICAgICAgICAgIG1vZGU6IF8uYmluZCh0aGlzLmdldE9wdGlvbiwgdGhpcywgJ21vZGUnKSxcbiAgICAgICAgICAgICAgICByZXBvcnRfdHlwZTogXy5iaW5kKHRoaXMuZ2V0T3B0aW9uT3JOdWxsLCB0aGlzLCAncmVwb3J0X3R5cGUnKSxcbiAgICAgICAgICAgICAgICBzdGFydF9kYXRlOiBfLmJpbmQodGhpcy5fZW5jb2RlRGF0ZSwgdGhpcywgJ3N0YXJ0X2RhdGUnKSxcbiAgICAgICAgICAgICAgICBlbmRfZGF0ZTogXy5iaW5kKHRoaXMuX2VuY29kZURhdGUsIHRoaXMsICdlbmRfZGF0ZScpLFxuICAgICAgICAgICAgICAgIHJlYWx0aW1lOiBfLmJpbmQodGhpcy5fZ2V0SXNSZWFsdGltZSwgdGhpcylcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG5cbiAgICAgICAgdGhpcy50b3RhbHNDb2xsZWN0aW9uID0gbmV3IE1vZGVscy5QbGF5ZXJHYW1lVG90YWxzQ29sbGVjdGlvbihbXSwge1xuICAgICAgICAgICAgc291cmNlOiB0aGlzLmNvbGxlY3Rpb25cbiAgICAgICAgfSk7XG4gICAgfSxcblxuICAgIF9nZXRJc1JlYWx0aW1lOiBmdW5jdGlvbigpIHtcbiAgICAgICAgcmV0dXJuICEhK3RoaXMuZ2V0T3B0aW9uKCdyZWFsdGltZScpO1xuICAgIH0sXG5cbiAgICByZW5kZXJHcmlkOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgIGlmICghdGhpcy5nZXRDaGlsZFZpZXcoJ2xpc3RSZWdpb24nKSkge1xuICAgICAgICAgICAgdmFyIGdyaWRWaWV3ID0gbmV3IFBsYXllckdhbWVzR3JpZFZpZXcoe1xuICAgICAgICAgICAgICAgIGNvbGxlY3Rpb246IHRoaXMudmlzaWJsZUNvbGxlY3Rpb24sXG4gICAgICAgICAgICAgICAgZm9vdGVyQ29sbGVjdGlvbjogdGhpcy50b3RhbHNDb2xsZWN0aW9uXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHRoaXMuc2hvd0NoaWxkVmlldygnbGlzdFJlZ2lvbicsIGdyaWRWaWV3KTtcbiAgICAgICAgfVxuICAgICAgICB0aGlzLmNvbGxlY3Rpb24uZmV0Y2goKTtcbiAgICB9LFxuXG4gICAgcmVuZGVyVG90YWxzR3JpZDogZnVuY3Rpb24oKSB7XG4gICAgICAgIGlmICghdGhpcy5nZXRDaGlsZFZpZXcoJ3RvdGFsc1JlZ2lvbicpKSB7XG4gICAgICAgICAgICB2YXIgZ3JpZFZpZXcgPSBuZXcgVG90YWxzR3JpZFZpZXcoe1xuICAgICAgICAgICAgICAgIGNvbGxlY3Rpb246IHRoaXMudG90YWxzQ29sbGVjdGlvblxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB0aGlzLnNob3dDaGlsZFZpZXcoJ3RvdGFsc1JlZ2lvbicsIGdyaWRWaWV3KTtcbiAgICAgICAgICAgIHRoaXMuaGlkZVRvdGFscygpO1xuICAgICAgICB9XG4gICAgfSxcblxuICAgIGhpZGVUb3RhbHM6IGZ1bmN0aW9uKCkge1xuICAgICAgICB0aGlzLnVpLnRvdGFsc1dpZGdldC5oaWRlKCk7XG4gICAgfSxcblxuICAgIHNob3dUb3RhbHM6IGZ1bmN0aW9uKCkge1xuICAgICAgICB0aGlzLnVpLnRvdGFsc1dpZGdldC5zaG93KCk7XG4gICAgfSxcblxuICAgIG9uUmVuZGVyOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHNldFRpbWVvdXQoXy5iaW5kKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHRoaXMuJCgnI2dhcGlja2VyJykuZ2FwaWNrZXIoe1xuICAgICAgICAgICAgICAgIGFsbG93TnVsbHM6IHRydWUsXG4gICAgICAgICAgICAgICAgc2hvd1RpbWU6IHRydWUsXG4gICAgICAgICAgICAgICAgc3RhcnREYXRlOiB0aGlzLmdldE9wdGlvbignc3RhcnRfZGF0ZScpLFxuICAgICAgICAgICAgICAgIGVuZERhdGU6IHRoaXMuZ2V0T3B0aW9uKCdlbmRfZGF0ZScpLFxuICAgICAgICAgICAgICAgIHV0Y09mZnNldDogdGhpcy5nZXRPcHRpb24oJ3R6JyksXG4gICAgICAgICAgICAgICAgY2FsZW5kYXJzQ291bnQ6IDIsXG4gICAgICAgICAgICAgICAgcG9zaXRpb246ICdib3R0b20gY2VudGVyJyxcbiAgICAgICAgICAgICAgICBmb3JtYXQ6ICdZWVlZLU1NLUREIEhIOm1tOnNzJyxcbiAgICAgICAgICAgICAgICBhcHBseUxhYmVsOiBsb2NhbGVzLnRyYW5zbGF0ZSgnQXBwbHknKSxcbiAgICAgICAgICAgICAgICBjYW5jZWxMYWJlbDogbG9jYWxlcy50cmFuc2xhdGUoJ0NhbmNlbCcpLFxuICAgICAgICAgICAgICAgIGZyb21MYWJlbDogbG9jYWxlcy50cmFuc2xhdGUoJ0Zyb20nKSArICc6ICcsXG4gICAgICAgICAgICAgICAgdG9MYWJlbDogbG9jYWxlcy50cmFuc2xhdGUoJ1RvJykgKyAnOiAnLFxuICAgICAgICAgICAgICAgIG9mZnNldExhYmVsOiBsb2NhbGVzLnRyYW5zbGF0ZSgnWm9uZScpICsgJzogJyxcbiAgICAgICAgICAgICAgICBkYXlzT2ZXZWVrOiBbXG4gICAgICAgICAgICAgICAgICAgIGxvY2FsZXMudHJhbnNsYXRlKCdTdScpLFxuICAgICAgICAgICAgICAgICAgICBsb2NhbGVzLnRyYW5zbGF0ZSgnTW8nKSxcbiAgICAgICAgICAgICAgICAgICAgbG9jYWxlcy50cmFuc2xhdGUoJ1R1JyksXG4gICAgICAgICAgICAgICAgICAgIGxvY2FsZXMudHJhbnNsYXRlKCdXZScpLFxuICAgICAgICAgICAgICAgICAgICBsb2NhbGVzLnRyYW5zbGF0ZSgnVGgnKSxcbiAgICAgICAgICAgICAgICAgICAgbG9jYWxlcy50cmFuc2xhdGUoJ0ZyJyksXG4gICAgICAgICAgICAgICAgICAgIGxvY2FsZXMudHJhbnNsYXRlKCdTYScpXG4gICAgICAgICAgICAgICAgICAgIF0sXG5cbiAgICAgICAgICAgICAgICBtb250aE5hbWVzOiBbXG4gICAgICAgICAgICAgICAgICAgIGxvY2FsZXMudHJhbnNsYXRlKCdKYW4nKSxcbiAgICAgICAgICAgICAgICAgICAgbG9jYWxlcy50cmFuc2xhdGUoJ0ZlYicpLFxuICAgICAgICAgICAgICAgICAgICBsb2NhbGVzLnRyYW5zbGF0ZSgnTWFyJyksXG4gICAgICAgICAgICAgICAgICAgIGxvY2FsZXMudHJhbnNsYXRlKCdBcHInKSxcbiAgICAgICAgICAgICAgICAgICAgbG9jYWxlcy50cmFuc2xhdGUoJ01heScpLFxuICAgICAgICAgICAgICAgICAgICBsb2NhbGVzLnRyYW5zbGF0ZSgnSnVuJyksXG4gICAgICAgICAgICAgICAgICAgIGxvY2FsZXMudHJhbnNsYXRlKCdKdWwnKSxcbiAgICAgICAgICAgICAgICAgICAgbG9jYWxlcy50cmFuc2xhdGUoJ0F1ZycpLFxuICAgICAgICAgICAgICAgICAgICBsb2NhbGVzLnRyYW5zbGF0ZSgnU2VwJyksXG4gICAgICAgICAgICAgICAgICAgIGxvY2FsZXMudHJhbnNsYXRlKCdPY3QnKSxcbiAgICAgICAgICAgICAgICAgICAgbG9jYWxlcy50cmFuc2xhdGUoJ05vdicpLFxuICAgICAgICAgICAgICAgICAgICBsb2NhbGVzLnRyYW5zbGF0ZSgnRGVjJylcbiAgICAgICAgICAgICAgICBdLFxuICAgICAgICAgICAgICAgIGN1c3RvbVJhbmdlczogW1xuICAgICAgICAgICAgICAgICAgICBbJzFkJywgbG9jYWxlcy50cmFuc2xhdGUoJ1RvZGF5JyldLFxuICAgICAgICAgICAgICAgICAgICBbJzF3JywgbG9jYWxlcy50cmFuc2xhdGUoJ1RoaXMgV2VlaycpXSxcbiAgICAgICAgICAgICAgICAgICAgWycxbScsIGxvY2FsZXMudHJhbnNsYXRlKCdUaGlzIE1vbnRoJyldLFxuICAgICAgICAgICAgICAgICAgICBbJzNtJywgbG9jYWxlcy50cmFuc2xhdGUoJ0xhc3QgMyBNb250aHMnKV1cblxuICAgICAgICAgICAgICAgIF0sXG4gICAgICAgICAgICAgICAgY2FsbGJhY2s6IF8uYmluZChmdW5jdGlvbihmcm9tLCB0bywgdHopIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5vcHRpb25zLnN0YXJ0X2RhdGUgPSBmcm9tO1xuICAgICAgICAgICAgICAgICAgICB0aGlzLm9wdGlvbnMuZW5kX2RhdGUgPSB0bztcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5vcHRpb25zLnR6ID0gdHo7XG5cbiAgICAgICAgICAgICAgICB9LCB0aGlzKVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH0sIHRoaXMpLCAxKTtcblxuICAgICAgICB0aGlzLnJlbmRlckdyaWQoKTtcbiAgICAgICAgdGhpcy5yZW5kZXJUb3RhbHNHcmlkKCk7XG4gICAgfSxcblxuICAgIHJlc2V0VmlzaWJsZUNvbGxlY3Rpb246IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdGhpcy5tb2RlbFJlcXVlc3RzID0gMDtcbiAgICAgICAgIHRoaXMudmlzaWJsZUNvbGxlY3Rpb24ucmVzZXQoKTtcbiAgICB9LFxuXG4gICAgdXBkYXRlVmlzaWJsZUNvbGxlY3Rpb246IGZ1bmN0aW9uIChpbmMpIHtcbiAgICAgICAgdGhpcy5tb2RlbFJlcXVlc3RzICs9IGluYztcbiAgICAgICAgdmFyIG1vZGVsc190b19zaG93ID0gdGhpcy5jb2xsZWN0aW9uLmZpbHRlcihmdW5jdGlvbihtKSB7XG4gICAgICAgICAgICByZXR1cm4gbS5nZXQoJ3JvdW5kcycpICogMSA+IDAgfHxcbiAgICAgICAgICAgICAgICAgICBtLmdldCgnYmV0cycpICogMSA+IDAgfHxcbiAgICAgICAgICAgICAgICAgICBtLmdldCgnd2lucycpICogMSA+IDA7XG4gICAgICAgIH0pXG4gICAgICAgIGlmKHRoaXMubW9kZWxSZXF1ZXN0cyAgPiAwICkge1xuICAgICAgICAgICAgbW9kZWxzX3RvX3Nob3cucHVzaCh0aGlzLmxvYWRpbmdNb2RlbClcbiAgICAgICAgfVxuICAgICAgICB0aGlzLnZpc2libGVDb2xsZWN0aW9uLnJlc2V0KG1vZGVsc190b19zaG93KTtcbiAgICB9LFxuXG4gICAgb25Db2xsZWN0aW9uUmVxdWVzdDogZnVuY3Rpb24gKG1vZGVsT3JDb2xsZWN0aW9uLCB4aHIsIG9wdGlvbnMpIHtcbiAgICAgICAgaWYgKG1vZGVsT3JDb2xsZWN0aW9uIGluc3RhbmNlb2YgQmFja2JvbmUuTW9kZWwpIHtcbiAgICAgICAgICAgIHRoaXMudXBkYXRlVmlzaWJsZUNvbGxlY3Rpb24oMSk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICB0aGlzLnVpLm92ZXJsYXkuc2hvdygpO1xuICAgICAgICAgICAgdGhpcy5zYXZlU3RhdGUoKTtcbiAgICAgICAgICAgIHRoaXMucmVzZXRWaXNpYmxlQ29sbGVjdGlvbigpO1xuICAgICAgICB9XG4gICAgfSxcblxuICAgIG9uQ29sbGVjdGlvblN5bmM6IGZ1bmN0aW9uIChtb2RlbE9yQ29sbGVjdGlvbikge1xuICAgICAgICBpZiAobW9kZWxPckNvbGxlY3Rpb24gaW5zdGFuY2VvZiBCYWNrYm9uZS5Nb2RlbCkge1xuICAgICAgICAgICAgdGhpcy51cGRhdGVWaXNpYmxlQ29sbGVjdGlvbigtMSk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICB0aGlzLnVpLm92ZXJsYXkuaGlkZSgpO1xuICAgICAgICB9XG4gICAgfSxcblxuICAgIG9uQ29sbGVjdGlvbkVycm9yOiBmdW5jdGlvbiAobW9kZWxPckNvbGxlY3Rpb24pIHtcbiAgICAgICAgaWYgKG1vZGVsT3JDb2xsZWN0aW9uIGluc3RhbmNlb2YgQmFja2JvbmUuTW9kZWwpIHtcbiAgICAgICAgICAgIHRoaXMudXBkYXRlVmlzaWJsZUNvbGxlY3Rpb24oLTEpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgdGhpcy51aS5vdmVybGF5LmhpZGUoKTtcbiAgICAgICAgfVxuICAgIH0sXG5cbiAgICBvbkNvbGxlY3Rpb25VcGRhdGU6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgaWYgKHRoaXMuZ2V0T3B0aW9uKCd0b3RhbHMnKSA9PT0gJzAnKSB7XG4gICAgICAgICAgICB0aGlzLmhpZGVUb3RhbHMoKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIHRoaXMuc2hvd1RvdGFscygpO1xuICAgICAgICB9XG4gICAgfSxcblxuICAgIF9lbmNvZGVEYXRlOiBmdW5jdGlvbihrZXkpIHtcbiAgICAgICAgdmFyIGRhdGUgPSB0aGlzLmdldE9wdGlvbihrZXkpO1xuICAgICAgICByZXR1cm4gZGF0ZSA/IGRhdGUuZm9ybWF0KCkgOiBudWxsO1xuICAgIH0sXG5cbiAgICBzYXZlU3RhdGU6IGZ1bmN0aW9uKCkge1xuICAgICAgICB0aGlzLnRyaWdnZXJNZXRob2QoJ3N0YXRlOmNoYW5nZWQnLCB7XG4gICAgICAgICAgICBnYW1lX2lkOiB0aGlzLm9wdGlvbnMuZ2FtZV9pZCxcbiAgICAgICAgICAgIHR6OiB0aGlzLm9wdGlvbnMudHosXG4gICAgICAgICAgICBtb2RlOiB0aGlzLm9wdGlvbnMubW9kZSxcbiAgICAgICAgICAgIHJlcG9ydF90eXBlOiB0aGlzLm9wdGlvbnMucmVwb3J0X3R5cGUsXG4gICAgICAgICAgICBzdGFydF9kYXRlOiB0aGlzLm9wdGlvbnMuc3RhcnRfZGF0ZSxcbiAgICAgICAgICAgIGVuZF9kYXRlOiB0aGlzLm9wdGlvbnMuZW5kX2RhdGUsXG4gICAgICAgICAgICBoZWFkZXI6IHRoaXMub3B0aW9ucy5oZWFkZXIsXG4gICAgICAgICAgICB0b3RhbHM6IHRoaXMub3B0aW9ucy50b3RhbHMsXG4gICAgICAgICAgICBpbmZvOiB0aGlzLm9wdGlvbnMuaW5mbyxcbiAgICAgICAgICAgIGxhbmc6IHRoaXMub3B0aW9ucy5sYW5nLFxuICAgICAgICAgICAgcmVhbHRpbWU6IHRoaXMub3B0aW9ucy5yZWFsdGltZVxuICAgICAgICB9KTtcbiAgICB9LFxuXG4gICAgb25TZWFyY2hGb3JtU3VibWl0OiBmdW5jdGlvbihkYXRhKSB7XG4gICAgICAgIF8uZXh0ZW5kKHRoaXMub3B0aW9ucywgZGF0YSk7XG5cbiAgICAgICAgaWYgKGRhdGEucm91bmRfaWQpIHtcbiAgICAgICAgICAgIHRoaXMuc2F2ZVN0YXRlKCk7XG4gICAgICAgICAgICByZXR1cm4gdGhpcy5zaG93Um91bmRSZXN1bHQoe1xuICAgICAgICAgICAgICAgIHJvdW5kX2lkOiBkYXRhLnJvdW5kX2lkLFxuICAgICAgICAgICAgICAgIGJhbGFuY2U6ICcxJ1xuICAgICAgICAgICAgfSk7XG4gICAgICAgIH1cbiAgICAgICAgdGhpcy5jb2xsZWN0aW9uLmZldGNoKCk7XG4gICAgfSxcblxuICAgIG9uU2hvd1RyYW5zYWN0aW9uczogZnVuY3Rpb24gKG1vZGVsKSB7XG4gICAgICAgIHJldHVybiB0aGlzLnNob3dUcmFuc2FjdGlvbnMoe1xuICAgICAgICAgICAgZ2FtZV9pZDogbW9kZWwuZ2V0KCdnYW1lX2lkJyksXG4gICAgICAgICAgICBjdXJyZW5jeTogbW9kZWwuZ2V0KCdjdXJyZW5jeScpLFxuICAgICAgICAgICAgbW9kZTogbW9kZWwuZ2V0KCdtb2RlJylcbiAgICAgICAgfSk7XG4gICAgfSxcblxuICAgIHNob3dUcmFuc2FjdGlvbnM6IGZ1bmN0aW9uKGRhdGEpIHtcbiAgICAgICAgdmFyIHBhcmFtcyA9IHtcbiAgICAgICAgICAgIGdhbWVfaWQ6IHRoaXMub3B0aW9ucy5nYW1lX2lkLFxuICAgICAgICAgICAgcGxheWVyX2lkOiB0aGlzLm9wdGlvbnMucGxheWVyX2lkLFxuICAgICAgICAgICAgbW9kZTogdGhpcy5vcHRpb25zLm1vZGUsXG4gICAgICAgICAgICByZXBvcnRfdHlwZTogdGhpcy5vcHRpb25zLnJlcG9ydF90eXBlLFxuICAgICAgICAgICAgc3RhcnRfZGF0ZTogdGhpcy5vcHRpb25zLnN0YXJ0X2RhdGUsXG4gICAgICAgICAgICBlbmRfZGF0ZTogdGhpcy5vcHRpb25zLmVuZF9kYXRlLFxuICAgICAgICAgICAgdHo6IHRoaXMub3B0aW9ucy50eixcbiAgICAgICAgICAgIGhlYWRlcjogdGhpcy5vcHRpb25zLmhlYWRlcixcbiAgICAgICAgICAgIHRvdGFsczogdGhpcy5vcHRpb25zLnRvdGFscyxcbiAgICAgICAgICAgIGluZm86IHRoaXMub3B0aW9ucy5pbmZvLFxuICAgICAgICAgICAgbGFuZzogdGhpcy5vcHRpb25zLmxhbmdcbiAgICAgICAgfTtcbiAgICAgICAgaWYgKHRoaXMub3B0aW9ucy5icmFuZCAhPT0gbnVsbCAmJiB0aGlzLm9wdGlvbnMuYnJhbmQgIT09IHVuZGVmaW5lZCkge1xuICAgICAgICAgICAgcGFyYW1zLmJyYW5kID0gdGhpcy5vcHRpb25zLmJyYW5kO1xuICAgICAgICB9XG4gICAgICAgIHZhciBocmVmID0gJyNzaG93PXRyYW5zYWN0aW9ucyYnICsgVXRpbHMuZW5jb2RlUXVlcnlEYXRhKFxuICAgICAgICAgICAgT2JqZWN0LmFzc2lnbihwYXJhbXMsIGRhdGEpKTtcbiAgICAgICAgQmFja2JvbmUuaGlzdG9yeS5uYXZpZ2F0ZShocmVmLCB7dHJpZ2dlcjogdHJ1ZX0pO1xuICAgIH0sXG5cbiAgICBzaG93Um91bmRSZXN1bHQ6IGZ1bmN0aW9uKGRhdGEpIHtcbiAgICAgICAgdmFyIHBhcmFtcyA9IHtcbiAgICAgICAgICAgIHBsYXllcl9pZDogdGhpcy5vcHRpb25zLnBsYXllcl9pZCxcbiAgICAgICAgICAgIHR6OiB0aGlzLm9wdGlvbnMudHosXG4gICAgICAgICAgICBoZWFkZXI6IHRoaXMub3B0aW9ucy5oZWFkZXIsXG4gICAgICAgICAgICB0b3RhbHM6IHRoaXMub3B0aW9ucy50b3RhbHMsXG4gICAgICAgICAgICBpbmZvOiB0aGlzLm9wdGlvbnMuaW5mbyxcbiAgICAgICAgICAgIGxhbmc6IHRoaXMub3B0aW9ucy5sYW5nXG4gICAgICAgIH07XG4gICAgICAgIGlmICh0aGlzLm9wdGlvbnMuYnJhbmQgIT09IG51bGwgJiYgdGhpcy5vcHRpb25zLmJyYW5kICE9PSB1bmRlZmluZWQpIHtcbiAgICAgICAgICAgIHBhcmFtcy5icmFuZCA9IHRoaXMub3B0aW9ucy5icmFuZDtcbiAgICAgICAgfVxuICAgICAgICB2YXIgaHJlZiA9ICcjc2hvdz1yb3VuZCYnICsgVXRpbHMuZW5jb2RlUXVlcnlEYXRhKE9iamVjdC5hc3NpZ24oXG4gICAgICAgICAgICBwYXJhbXMsIGRhdGEpKTtcbiAgICAgICAgQmFja2JvbmUuaGlzdG9yeS5uYXZpZ2F0ZShocmVmLCB7dHJpZ2dlcjogdHJ1ZX0pO1xuICAgIH1cbn0pO1xuXG5tb2R1bGUuZXhwb3J0cyA9IFBsYXllckdhbWVzVmlldztcblxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vc3JjL2pzL3ZpZXdzL3BsYXllcmdhbWVzL3ZpZXcuanNcbi8vIG1vZHVsZSBpZCA9IDE3XG4vLyBtb2R1bGUgY2h1bmtzID0gMCIsInZhciBHcmlkVXRpbHMgPSByZXF1aXJlKCdsaWIvZ3JpZCcpO1xudmFyIGxvY2FsZXMgPSByZXF1aXJlKCdsaWIvbG9jYWxlcycpO1xudmFyIFN0b3JhZ2UgPSByZXF1aXJlKCdhcHAvc3RvcmFnZScpO1xuXG52YXIgUGxheWVyR2FtZXNHcmlkVmlldyA9IEdyaWRVdGlscy5GaXhlZEhlYWRlckNlbGxNdWx0aWxpbmVGb290ZXIuZXh0ZW5kKHtcbiAgICBjb2x1bW5zOiBbe1xuICAgICAgICBhdHRyOiAnZ2FtZV9pZCcsXG4gICAgICAgIGhlYWRlcjogTWFyaW9uZXR0ZS5WaWV3LmV4dGVuZCh7XG4gICAgICAgICAgICB0ZW1wbGF0ZTogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHJldHVybiBsb2NhbGVzLnRyYW5zbGF0ZSgnR2FtZScpXG4gICAgICAgICAgICB9XG4gICAgICAgIH0pLFxuICAgICAgICBjZWxsOiBHcmlkVXRpbHMuTW9iaWxlQ2VsbC5leHRlbmQoe1xuICAgICAgICAgICAgZGlzcGxheU5hbWU6IGxvY2FsZXMudHJhbnNsYXRlKCdHYW1lJyksXG4gICAgICAgICAgICBjbGFzc05hbWU6ICdzbS1mbG9hdC1sZWZ0JyxcbiAgICAgICAgICAgIGV2ZW50czoge1xuICAgICAgICAgICAgICAgICdjbGljayBhJzogJ29uVmlld0J0bkNsaWNrJ1xuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIG9uVmlld0J0bkNsaWNrOiBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgICAgICB0aGlzLnRyaWdnZXJNZXRob2QoJ3Nob3c6dHJhbnNhY3Rpb25zJywgdGhpcy5tb2RlbCk7XG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgdGVtcGxhdGVDb250ZXh0OiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgdmFyIGNvbnRleHQgPSBHcmlkVXRpbHMuTW9iaWxlQ2VsbC5wcm90b3R5cGUudGVtcGxhdGVDb250ZXh0LmFwcGx5KHRoaXMsIGFyZ3VtZW50cyk7XG5cbiAgICAgICAgICAgICAgICB2YXIgZ2FtZUlEID0gdGhpcy5nZXRBdHRyVmFsdWUoKTtcbiAgICAgICAgICAgICAgICB2YXIgZ2FtZUluZm8gPSBTdG9yYWdlLmF2YWlsYWJsZUdhbWVzLmZpbmRXaGVyZSh7XG4gICAgICAgICAgICAgICAgICAgIGlkOiBnYW1lSURcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB2YXIgZnVsbE5hbWU7XG4gICAgICAgICAgICAgICAgaWYgKGdhbWVJbmZvKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciBsb2NhbGl6ZWROYW1lID0gbG9jYWxlcy50cmFuc2xhdGUoJ3RpdGxlJywgZ2FtZUluZm8uZ2V0KCdpMThuJykpO1xuICAgICAgICAgICAgICAgICAgICB2YXIgbmFtZSA9IGdhbWVJbmZvLmdldCgnbmFtZScpO1xuICAgICAgICAgICAgICAgICAgICBmdWxsTmFtZSA9IG5hbWUgKyAnICgnICsgbG9jYWxpemVkTmFtZSArICcpJztcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICBmdWxsTmFtZSA9IHRoaXMubW9kZWwuZ2V0KCdnYW1lX25hbWUnKSB8fCBnYW1lSUQgfHwgJyc7XG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgY29udGV4dFsndmFsJ10gPSAnPGEgaHJlZj1cIlwiIGNsYXNzPVwidGFibGUtbGlua1wiPicgKyBmdWxsTmFtZSArICc8L2E+JztcbiAgICAgICAgICAgICAgICByZXR1cm4gY29udGV4dDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSlcbiAgICB9LCB7XG4gICAgICAgIGF0dHI6ICdjdXJyZW5jeScsXG4gICAgICAgIGhlYWRlcjogTWFyaW9uZXR0ZS5WaWV3LmV4dGVuZCh7XG4gICAgICAgICAgICB0ZW1wbGF0ZTogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHJldHVybiBsb2NhbGVzLnRyYW5zbGF0ZSgnQ3VycmVuY3knKVxuICAgICAgICAgICAgfVxuICAgICAgICB9KSxcbiAgICAgICAgY2xhc3NOYW1lOiAndGV4dC1jZW50ZXInLFxuICAgICAgICBzb3J0YWJsZTogZmFsc2UsXG4gICAgICAgIGNlbGw6IEdyaWRVdGlscy5DdXN0b21DZWxsLmV4dGVuZCh7XG4gICAgICAgICAgICBjbGFzc05hbWU6ICdzbS1mbG9hdC1sZWZ0JyxcbiAgICAgICAgICAgIHRlbXBsYXRlOiBfLnRlbXBsYXRlKFtcbiAgICAgICAgICAgICAgICAnPHNwYW4gY2xhc3M9XCJoaWRkZW4teHNcIj48JT0gdmFsICU+PC9zcGFuPicsXG4gICAgICAgICAgICBdLmpvaW4oJycpKVxuICAgICAgICB9KVxuICAgIH0sIHtcbiAgICAgICAgYXR0cjogJ3JvdW5kcycsXG4gICAgICAgIGhlYWRlcjogTWFyaW9uZXR0ZS5WaWV3LmV4dGVuZCh7XG4gICAgICAgICAgICB0ZW1wbGF0ZTogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHJldHVybiBsb2NhbGVzLnRyYW5zbGF0ZSgnUm91bmRzJylcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSksXG4gICAgICAgIGNsYXNzTmFtZTogJ3RleHQtcmlnaHQnLFxuXG4gICAgICAgIGNlbGw6IEdyaWRVdGlscy5Nb2JpbGVDZWxsLmV4dGVuZCh7XG4gICAgICAgICAgICBjbGFzc05hbWU6ICdzbS1mbG9hdC1sZWZ0IHRleHQtcmlnaHQnLFxuICAgICAgICAgICAgc2hvd1NwaW5uZXI6IHRydWUsXG4gICAgICAgICAgICBkaXNwbGF5TmFtZTogbG9jYWxlcy50cmFuc2xhdGUoJ1JvdW5kcycpLFxuICAgICAgICB9KVxuICAgIH0sIHtcbiAgICAgICAgYXR0cjogJ2JldHMnLFxuICAgICAgICBoZWFkZXI6IE1hcmlvbmV0dGUuVmlldy5leHRlbmQoe1xuICAgICAgICAgICAgdGVtcGxhdGU6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gbG9jYWxlcy50cmFuc2xhdGUoJ0JldHMnKVxuICAgICAgICAgICAgfVxuICAgICAgICB9KSxcbiAgICAgICAgY2xhc3NOYW1lOiAndGV4dC1yaWdodCcsXG4gICAgICAgIGNlbGw6IEdyaWRVdGlscy5Nb2JpbGVDZWxsLmV4dGVuZCh7XG4gICAgICAgICAgICBjbGFzc05hbWU6ICdzbS1mbG9hdC1sZWZ0IHRleHQtcmlnaHQnLFxuICAgICAgICAgICAgc2hvd0N1cnJlbmN5OiB0cnVlLFxuICAgICAgICAgICAgc2hvd1NwaW5uZXI6IHRydWUsXG4gICAgICAgICAgICBkaXNwbGF5TmFtZTogbG9jYWxlcy50cmFuc2xhdGUoJ0JldHMnKSxcbiAgICAgICAgfSlcbiAgICB9LCB7XG4gICAgICAgIF9pZDogJ3dpbnMnLFxuICAgICAgICBhdHRyOiAnd2lucycsXG4gICAgICAgIGhlYWRlcjogTWFyaW9uZXR0ZS5WaWV3LmV4dGVuZCh7XG4gICAgICAgICAgICB0ZW1wbGF0ZTogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHJldHVybiBsb2NhbGVzLnRyYW5zbGF0ZSgnV2lucycpXG4gICAgICAgICAgICB9XG4gICAgICAgIH0pLFxuICAgICAgICBjbGFzc05hbWU6ICd0ZXh0LXJpZ2h0JyxcbiAgICAgICAgY2VsbDogR3JpZFV0aWxzLk1vYmlsZUNlbGwuZXh0ZW5kKHtcbiAgICAgICAgICAgIGNsYXNzTmFtZTogJ3NtLWZsb2F0LWxlZnQgdGV4dC1yaWdodCcsXG4gICAgICAgICAgICBzaG93Q3VycmVuY3k6IHRydWUsXG4gICAgICAgICAgICBzaG93U3Bpbm5lcjogdHJ1ZSxcbiAgICAgICAgICAgIGRpc3BsYXlOYW1lOiBsb2NhbGVzLnRyYW5zbGF0ZSgnV2lucycpXG4gICAgICAgIH0pXG4gICAgfSwgIHtcbiAgICAgICAgYXR0cjogJ291dGNvbWUnLFxuICAgICAgICBoZWFkZXI6IE1hcmlvbmV0dGUuVmlldy5leHRlbmQoe1xuICAgICAgICAgICAgdGVtcGxhdGU6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gbG9jYWxlcy50cmFuc2xhdGUoJ091dGNvbWUnKVxuICAgICAgICAgICAgfVxuICAgICAgICB9KSxcbiAgICAgICAgY2xhc3NOYW1lOiAndGV4dC1yaWdodCcsXG4gICAgICAgIGNlbGw6IEdyaWRVdGlscy5Nb2JpbGVDZWxsLmV4dGVuZCh7XG4gICAgICAgICAgICBjbGFzc05hbWU6ICdzbS1mbG9hdC1sZWZ0IHRleHQtcmlnaHQnLFxuICAgICAgICAgICAgc2hvd0N1cnJlbmN5OiB0cnVlLFxuICAgICAgICAgICAgc2hvd1NwaW5uZXI6IHRydWUsXG4gICAgICAgICAgICBkaXNwbGF5TmFtZTogbG9jYWxlcy50cmFuc2xhdGUoJ091dGNvbWUnKVxuICAgICAgICB9KVxuICAgIH0sIHtcbiAgICAgICAgYXR0cjogJ3BheW91dCcsXG4gICAgICAgIGhlYWRlcjogTWFyaW9uZXR0ZS5WaWV3LmV4dGVuZCh7XG4gICAgICAgICAgICB0ZW1wbGF0ZTogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHJldHVybiBsb2NhbGVzLnRyYW5zbGF0ZSgnUGF5b3V0LCUnKVxuICAgICAgICAgICAgfVxuICAgICAgICB9KSxcbiAgICAgICAgY2xhc3NOYW1lOiAndGV4dC1yaWdodCcsXG4gICAgICAgIGNlbGw6IEdyaWRVdGlscy5Nb2JpbGVDZWxsLmV4dGVuZCh7XG4gICAgICAgICAgICBjbGFzc05hbWU6ICdzbS1mbG9hdC1sZWZ0IHRleHQtcmlnaHQnLFxuICAgICAgICAgICAgc2hvd1NwaW5uZXI6IHRydWUsXG4gICAgICAgICAgICBkaXNwbGF5TmFtZTogbG9jYWxlcy50cmFuc2xhdGUoJ1BheW91dCwlJylcbiAgICAgICAgfSlcbiAgICB9XSxcblxuICAgIHNpemVyVmlldzogTWFHcmlkLlNpemVyVmlldy5leHRlbmQoe1xuICAgICAgICB0ZW1wbGF0ZTogXy50ZW1wbGF0ZShbXG4gICAgICAgICAgICAnPHNlbGVjdCBjbGFzcz1cImZvcm0tY29udHJvbCBpbnB1dC1zbVwiPicsXG4gICAgICAgICAgICAnPCUgZm9yKHZhciBpIGluIHBhZ2VTaXplcykgeyAlPicsXG4gICAgICAgICAgICAnPG9wdGlvbiA8JSBpZihwYWdlU2l6ZXNbaV0gPT0gY3VycmVudFNpemUpIHsgJT4gc2VsZWN0ZWQ8JSB9JT4gIHZhbHVlPVwiPCU9IHBhZ2VTaXplc1tpXSAlPlwiPicsXG4gICAgICAgICAgICAnICAgIDwlPSBwYWdlVGV4dC5yZXBsYWNlKFwie3NpemV9XCIsIHBhZ2VTaXplc1tpXSkgJT4nLFxuICAgICAgICAgICAgJzwvb3B0aW9uPicsXG4gICAgICAgICAgICAnPCUgfSU+JyxcbiAgICAgICAgICAgICc8L3NlbGVjdD4nXG4gICAgICAgIF0uam9pbignJykpLFxuICAgICAgICB0ZW1wbGF0ZUNvbnRleHQ6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciBjdHggPSBNYUdyaWQuU2l6ZXJWaWV3LnByb3RvdHlwZS50ZW1wbGF0ZUNvbnRleHQuYXBwbHkodGhpcywgYXJndW1lbnRzKTtcbiAgICAgICAgICAgIGN0eC5wYWdlVGV4dCA9IGxvY2FsZXMudHJhbnNsYXRlKCd7c2l6ZX0gcGVyIHBhZ2UnKTtcbiAgICAgICAgICAgIHJldHVybiBjdHg7XG4gICAgICAgIH1cbiAgICB9KSxcblxuICAgIGZvb3RlckNvbHVtbnM6IFt7XG4gICAgICAgIGF0dHI6ICdjdXJyZW5jeScsXG4gICAgICAgIGNsYXNzTmFtZTogJ3RleHQtYm9sZCcsXG4gICAgICAgIGNlbGw6IEdyaWRVdGlscy5Nb2JpbGVDZWxsLmV4dGVuZCh7XG4gICAgICAgICAgICBjbGFzc05hbWU6ICdzbS1mbG9hdC1sZWZ0IHRleHQtY2VudGVyJyxcbiAgICAgICAgICAgIHNob3dTcGlubmVyOiB0cnVlLFxuICAgICAgICAgICAgZGlzcGxheU5hbWU6IGxvY2FsZXMudHJhbnNsYXRlKCdUb3RhbCcpLFxuICAgICAgICAgICAgdGVtcGxhdGU6IF8udGVtcGxhdGUoW1xuICAgICAgICAgICAgICAgICc8c3BhbiBjbGFzcz1cInZpc2libGUteHMtaW5saW5lXCI+JyxcbiAgICAgICAgICAgICAgICAnPGI+PCU9IGRpc3BsYXlOYW1lICU+OiA8JT0gXy5pc051bGwodmFsKSA/IFwiJm1kYXNoO1wiIDogdmFsICU+JyxcbiAgICAgICAgICAgICAgICAnPC9iPjwvc3Bhbj4nXG4gICAgICAgICAgICBdLmpvaW4oJycpKVxuICAgICAgICB9KVxuICAgIH0sIHtcbiAgICAgICAgYXR0cjogJ2N1cnJlbmN5JyxcbiAgICAgICAgY2xhc3NOYW1lOiAndGV4dC1jZW50ZXIgdGV4dC1ib2xkJyxcbiAgICAgICAgY2VsbDogR3JpZFV0aWxzLk1vYmlsZUNlbGwuZXh0ZW5kKHtcbiAgICAgICAgICAgIGNsYXNzTmFtZTogJ3NtLWZsb2F0LWxlZnQgdGV4dC1jZW50ZXInLFxuICAgICAgICAgICAgc2hvd0N1cnJlbmN5OiBmYWxzZSxcbiAgICAgICAgICAgIHNob3dTcGlubmVyOiB0cnVlLFxuICAgICAgICAgICAgZGlzcGxheU5hbWU6IGxvY2FsZXMudHJhbnNsYXRlKCdUb3RhbCcpLFxuICAgICAgICAgICAgdGVtcGxhdGU6IF8udGVtcGxhdGUoW1xuICAgICAgICAgICAgICAgICc8c3BhbiBjbGFzcz1cImhpZGRlbi14c1wiPicsXG4gICAgICAgICAgICAgICAgJzwlIGlmIChzaG93U3Bpbm5lcikgeyAlPicsXG4gICAgICAgICAgICAgICAgJyAgICA8JSBpZiAodmFsID09PSBudWxsKSB7ICU+JyxcbiAgICAgICAgICAgICAgICAnICAgICAgICA8aSBjbGFzcz1cXCdmYSBmYS1zcGluIGZhLXJlZnJlc2hcXCc+PC9pPicsXG4gICAgICAgICAgICAgICAgJyAgICA8JSB9IGVsc2UgeyAlPicsXG4gICAgICAgICAgICAgICAgJyAgICAgICAgPCU9IHZhbCAlPicsXG4gICAgICAgICAgICAgICAgJyAgICA8JSB9ICU+JyxcbiAgICAgICAgICAgICAgICAnPCUgfSBlbHNlIHsgJT4nLFxuICAgICAgICAgICAgICAgICcgICAgPCU9IF8uaXNOdWxsKHZhbCkgPyBcIiZtZGFzaDtcIiA6IHZhbCAlPicsXG4gICAgICAgICAgICAgICAgJzwlIH0gJT4nLFxuICAgICAgICAgICAgICAgICc8L3NwYW4+J1xuICAgICAgICAgICAgXS5qb2luKCcnKSlcbiAgICAgICAgfSlcbiAgICB9LCB7XG4gICAgICAgIGF0dHI6ICdyb3VuZHMnLFxuICAgICAgICBjbGFzc05hbWU6ICd0ZXh0LXJpZ2h0IHRleHQtYm9sZCcsXG4gICAgICAgIGNlbGw6IEdyaWRVdGlscy5Nb2JpbGVDZWxsLmV4dGVuZCh7XG4gICAgICAgICAgICBjbGFzc05hbWU6ICdzbS1mbG9hdC1sZWZ0IHRleHQtcmlnaHQnLFxuICAgICAgICAgICAgc2hvd1NwaW5uZXI6IHRydWUsXG4gICAgICAgICAgICBkaXNwbGF5TmFtZTogbG9jYWxlcy50cmFuc2xhdGUoJ1JvdW5kcycpXG4gICAgICAgIH0pXG4gICAgfSwge1xuICAgICAgICBhdHRyOiAnYmV0cycsXG4gICAgICAgIGNsYXNzTmFtZTogJ3RleHQtcmlnaHQgdGV4dC1ib2xkJyxcbiAgICAgICAgY2VsbDogR3JpZFV0aWxzLk1vYmlsZUNlbGwuZXh0ZW5kKHtcbiAgICAgICAgICAgIGNsYXNzTmFtZTogJ3NtLWZsb2F0LWxlZnQgdGV4dC1yaWdodCcsXG4gICAgICAgICAgICBzaG93Q3VycmVuY3k6IHRydWUsXG4gICAgICAgICAgICBzaG93U3Bpbm5lcjogdHJ1ZSxcbiAgICAgICAgICAgIGRpc3BsYXlOYW1lOiBsb2NhbGVzLnRyYW5zbGF0ZSgnQmV0cycpXG4gICAgICAgIH0pXG4gICAgfSwge1xuICAgICAgICBfaWQ6ICd3aW5zJyxcbiAgICAgICAgYXR0cjogJ3dpbnMnLFxuICAgICAgICBjbGFzc05hbWU6ICd0ZXh0LXJpZ2h0IHRleHQtYm9sZCcsXG4gICAgICAgIGNlbGw6IEdyaWRVdGlscy5Nb2JpbGVDZWxsLmV4dGVuZCh7XG4gICAgICAgICAgICBjbGFzc05hbWU6ICdzbS1mbG9hdC1sZWZ0IHRleHQtcmlnaHQnLFxuICAgICAgICAgICAgc2hvd0N1cnJlbmN5OiB0cnVlLFxuICAgICAgICAgICAgc2hvd1NwaW5uZXI6IHRydWUsXG4gICAgICAgICAgICBkaXNwbGF5TmFtZTogbG9jYWxlcy50cmFuc2xhdGUoJ1dpbnMnKVxuICAgICAgICB9KVxuICAgIH0sICB7XG4gICAgICAgIGF0dHI6ICdvdXRjb21lJyxcbiAgICAgICAgY2xhc3NOYW1lOiAndGV4dC1yaWdodCB0ZXh0LWJvbGQnLFxuICAgICAgICBjZWxsOiBHcmlkVXRpbHMuTW9iaWxlQ2VsbC5leHRlbmQoe1xuICAgICAgICAgICAgY2xhc3NOYW1lOiAnc20tZmxvYXQtbGVmdCB0ZXh0LXJpZ2h0JyxcbiAgICAgICAgICAgIHNob3dDdXJyZW5jeTogdHJ1ZSxcbiAgICAgICAgICAgIHNob3dTcGlubmVyOiB0cnVlLFxuICAgICAgICAgICAgZGlzcGxheU5hbWU6IGxvY2FsZXMudHJhbnNsYXRlKCdPdXRjb21lJylcbiAgICAgICAgfSlcbiAgICB9LCB7XG4gICAgICAgIGF0dHI6ICdwYXlvdXQnLFxuICAgICAgICBjbGFzc05hbWU6ICd0ZXh0LXJpZ2h0IHRleHQtYm9sZCcsXG4gICAgICAgIGNlbGw6IEdyaWRVdGlscy5Nb2JpbGVDZWxsLmV4dGVuZCh7XG4gICAgICAgICAgICBjbGFzc05hbWU6ICdzbS1mbG9hdC1sZWZ0IHRleHQtcmlnaHQnLFxuICAgICAgICAgICAgc2hvd0N1cnJlbmN5OiBmYWxzZSxcbiAgICAgICAgICAgIHNob3dTcGlubmVyOiB0cnVlLFxuICAgICAgICAgICAgZGlzcGxheU5hbWU6IGxvY2FsZXMudHJhbnNsYXRlKCdQYXlvdXQsJScpXG4gICAgICAgIH0pXG4gICAgfV0sXG4gICAgYm9keVZpZXc6IE1hR3JpZC5Cb2R5Vmlldy5leHRlbmQoe1xuICAgICAgICBsb2FkaW5nVmlldzogTWFyaW9uZXR0ZS5WaWV3LmV4dGVuZCh7XG4gICAgICAgICAgICB0YWdOYW1lOiAndHInLFxuICAgICAgICAgICAgdGVtcGxhdGU6IF8udGVtcGxhdGUoW1xuICAgICAgICAgICAgICAgICc8dGQgY29sc3Bhbj1cIjwlLSBjcyAlPlwiIHN0eWxlPVwidGV4dC1hbGlnbjogY2VudGVyO1wiPicsXG4gICAgICAgICAgICAgICAgJyAgIDxpIGNsYXNzPVwiZmEgZmEtcmVmcmVzaCBmYS1zcGluXCI+PC9pPicsXG4gICAgICAgICAgICAgICAgJzwvdGQ+J10uam9pbignJykpLFxuICAgICAgICAgICAgdGVtcGxhdGVDb250ZXh0OiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4ge1xuICAgICAgICAgICAgICAgICAgICBjczogdGhpcy5nZXRPcHRpb24oJ2NvbHVtbnMnKS5sZW5ndGhcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pLFxuICAgICAgICBfZ2V0Q2hpbGRWaWV3OiBmdW5jdGlvbiAobW9kZWwpIHtcbiAgICAgICAgICAgIGlmKF8uYW55KF8udmFsdWVzKG1vZGVsLmF0dHJpYnV0ZXMpKSkge1xuICAgICAgICAgICAgICAgIHJldHVybiAgTWFHcmlkLkJvZHlWaWV3LnByb3RvdHlwZS5fZ2V0Q2hpbGRWaWV3LmFwcGx5KHRoaXMsIGFyZ3VtZW50cyk7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIHJldHVybiB0aGlzLmxvYWRpbmdWaWV3XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgfVxuICAgIH0pXG59KTtcblxubW9kdWxlLmV4cG9ydHMgPSBQbGF5ZXJHYW1lc0dyaWRWaWV3O1xuXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvanMvdmlld3MvcGxheWVyZ2FtZXMvZ3JpZC5qc1xuLy8gbW9kdWxlIGlkID0gMThcbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwibW9kdWxlLmV4cG9ydHMgPSBmdW5jdGlvbihvYmopIHtcbm9iaiB8fCAob2JqID0ge30pO1xudmFyIF9fdCwgX19wID0gJycsIF9fZSA9IF8uZXNjYXBlLCBfX2ogPSBBcnJheS5wcm90b3R5cGUuam9pbjtcbmZ1bmN0aW9uIHByaW50KCkgeyBfX3AgKz0gX19qLmNhbGwoYXJndW1lbnRzLCAnJykgfVxud2l0aCAob2JqKSB7XG5fX3AgKz0gJzxkaXYgY2xhc3M9XCJib3ggYm94LXByaW1hcnkgbm8tc2hhZG93XCI+XFxuXFxuICAgIDxmb3JtIGNsYXNzPVwiYm94LWhlYWRlciB3aXRoLWJvcmRlclwiIHN0eWxlPVwiYmFja2dyb3VuZC1jb2xvcjogI2U0ZThlYztcIj5cXG4gICAgICAgICc7XG4gaWYgKGhlYWRlciAhPT0gJzAnKSB7IDtcbl9fcCArPSAnXFxuICAgICAgICAgICAgPGRpdiBjbGFzcz1cInJvd1wiPlxcbiAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiY29sLXhzLTEyIGNvbC1tZC0xMFwiPlxcbiAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cInJvd1wiPlxcbiAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJjb2wteHMtNiBjb2wtc20tMyBjb2wtbWQtMlwiPlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiZm9ybS1ncm91cFwiPlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPicgK1xuX19lKCB0cmFuc2xhdGUoJ1JlcG9ydCBUeXBlJykgKSArXG4nOjwvbGFiZWw+XFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8c2VsZWN0IGNsYXNzPVwiZm9ybS1jb250cm9sXCIgbmFtZT1cInJlcG9ydF90eXBlXCI+XFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgJztcbiBmb3IgKHZhciBpIGluIHJlcG9ydF90eXBlcykgeyA7XG5fX3AgKz0gJ1xcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8b3B0aW9uICc7XG4gaWYgKHJlcG9ydF90eXBlc1tpXS51aWQgPT0gcmVwb3J0X3R5cGUpIHsgO1xuX19wICs9ICdzZWxlY3RlZCc7XG4gfSA7XG5fX3AgKz0gJ1xcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhbHVlPVwiJyArXG5fX2UoIHJlcG9ydF90eXBlc1tpXS51aWQgKSArXG4nXCI+XFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAnICtcbl9fZSggdHJhbnNsYXRlKHJlcG9ydF90eXBlc1tpXS5uYW1lKSApICtcbidcXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9vcHRpb24+XFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgJztcbiB9IDtcbl9fcCArPSAnXFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L3NlbGVjdD5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJmaWVsZC1lcnJvclwiPjwvZGl2PlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cXG4gICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cXG4gICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiY29sLXhzLTYgY29sLXNtLTMgY29sLW1kLTJcIj5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImZvcm0tZ3JvdXBcIj5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD4nICtcbl9fZSggdHJhbnNsYXRlKCdHYW1lIG1vZGUnKSApICtcbic6PC9sYWJlbD5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxzZWxlY3QgY2xhc3M9XCJmb3JtLWNvbnRyb2xcIiBuYW1lPVwibW9kZVwiPlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICc7XG4gZm9yKHZhciBpIGluIGF2YWlsYWJsZV9tb2RlcykgeyA7XG5fX3AgKz0gJ1xcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8b3B0aW9uICc7XG4gaWYgKGF2YWlsYWJsZV9tb2Rlc1tpXS5tb2RlID09IG1vZGUpIHsgO1xuX19wICs9ICdzZWxlY3RlZCc7XG4gfSA7XG5fX3AgKz0gJ1xcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhbHVlPVwiJyArXG5fX2UoIGF2YWlsYWJsZV9tb2Rlc1tpXS5tb2RlICkgK1xuJ1wiPlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgJyArXG5fX2UoIHRyYW5zbGF0ZShhdmFpbGFibGVfbW9kZXNbaV0udGl0bGUpICkgK1xuJ1xcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L29wdGlvbj5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAnO1xuIH0gO1xuX19wICs9ICdcXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvc2VsZWN0PlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImZpZWxkLWVycm9yXCI+PC9kaXY+XFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PlxcbiAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PlxcbiAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJjb2wteHMtMTIgY29sLXNtLTYgY29sLW1kLTJcIj5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImZvcm0tZ3JvdXBcIj5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD4nICtcbl9fZSggdHJhbnNsYXRlKCdHYW1lJykgKSArXG4nOjwvbGFiZWw+XFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8c2VsZWN0IGNsYXNzPVwiZm9ybS1jb250cm9sXCIgbmFtZT1cImdhbWVfaWRcIj5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8b3B0aW9uIHZhbHVlPVwiXCIgc2VsZWN0ZWQ+JyArXG5fX2UoIHRyYW5zbGF0ZSgnQWxsJykgKSArXG4nPC9vcHRpb24+XFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgJztcbiBmb3IodmFyIGkgaW4gYXZhaWxhYmxlX2dhbWVzKSB7IDtcbl9fcCArPSAnXFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICc7XG4gaWYgKCFhdmFpbGFibGVfZ2FtZXNbaV0ud2FzX3BsYXllZCkgY29udGludWU7IDtcbl9fcCArPSAnXFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxvcHRpb24gJztcbiBpZiAoYXZhaWxhYmxlX2dhbWVzW2ldLmlkID09IGdhbWVfaWQpIHsgO1xuX19wICs9ICdzZWxlY3RlZCc7XG4gfSA7XG5fX3AgKz0gJ1xcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhbHVlPVwiJyArXG5fX2UoIGF2YWlsYWJsZV9nYW1lc1tpXS5pZCApICtcbidcIj5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICcgK1xuX19lKCBhdmFpbGFibGVfZ2FtZXNbaV0ubmFtZSApICtcbidcXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICgnICtcbl9fZSggdHJhbnNsYXRlKCd0aXRsZScsIGF2YWlsYWJsZV9nYW1lc1tpXS5pMThuKSApICtcbicpXFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvb3B0aW9uPlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICc7XG4gfSA7XG5fX3AgKz0gJ1xcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9zZWxlY3Q+XFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiZmllbGQtZXJyb3JcIj48L2Rpdj5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+XFxuICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+XFxuICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImNvbC14cy0xMiBjb2wtc20tNiBjb2wtbWQtMlwiPlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiZm9ybS1ncm91cFwiPlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPicgK1xuX19lKCB0cmFuc2xhdGUoJ1JvdW5kJykgKSArXG4nOjwvbGFiZWw+XFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8aW5wdXQgdHlwZT1cInRleHRcIiBjbGFzcz1cImZvcm0tY29udHJvbFwiIG5hbWU9XCJyb3VuZF9pZFwiPlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImZpZWxkLWVycm9yXCI+PC9kaXY+XFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PlxcbiAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PlxcbiAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJjb2wteHMtMTIgY29sLXNtLTYgY29sLW1kLTRcIj5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImZvcm0tZ3JvdXBcIj5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD4nICtcbl9fZSggdHJhbnNsYXRlKCdEYXRlIFJhbmdlJykgKSArXG4nOjwvbGFiZWw+XFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8aW5wdXQgdHlwZT1cInRleHRcIiBjbGFzcz1cImZvcm0tY29udHJvbFwiIGlkPVwiZ2FwaWNrZXJcIiBhdXRvY29tcGxldGU9XCJvZmZcIj5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJmaWVsZC1lcnJvclwiPjwvZGl2PlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cXG4gICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cXG4gICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiY29sLXhzLTEyIGNvbC1zbS0xMiB2aXNpYmxlLXNtLWJsb2NrIHZpc2libGUteHMtYmxvY2tcIj5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImJveC1zdWJtaXRcIj5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJidG4gYnRuLXdhcm5pbmcgYnRuLWJsb2NrIHN1Ym1pdC1idG5cIj5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8aSBjbGFzcz1cImZhIGZhLXNlYXJjaFwiPjwvaT4gJyArXG5fX2UoIHRyYW5zbGF0ZSgnU2VhcmNoJykgKSArXG4nXFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+XFxuICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+XFxuICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cXG4gICAgICAgICAgICAgICAgPC9kaXY+XFxuICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJoaWRkZW4teHMgaGlkZGVuLXNtIGNvbC1tZC0yXCI+XFxuICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwicm93XCI+XFxuICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImNvbC14cy0xMlwiPlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiYm94LXN1Ym1pdFwiPlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImJ0biBidG4td2FybmluZyBidG4tYmxvY2sgc3VibWl0LWJ0blwiPlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpIGNsYXNzPVwiZmEgZmEtc2VhcmNoXCI+PC9pPiAnICtcbl9fZSggdHJhbnNsYXRlKCdTZWFyY2gnKSApICtcbidcXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cXG4gICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cXG4gICAgICAgICAgICAgICAgICAgIDwvZGl2PlxcbiAgICAgICAgICAgICAgICA8L2Rpdj5cXG4gICAgICAgICAgICA8L2Rpdj5cXG4gICAgICAgICc7XG4gfSA7XG5fX3AgKz0gJ1xcbiAgICAgICAgPGg0IGNsYXNzPVwiaG9yaXpvbnRhbC1kaXZpZGVyXCI+XFxuICAgICAgICAgICAgPGkgY2xhc3M9XCJmYSBmYS1iYXItY2hhcnRcIj48L2k+ICcgK1xuX19lKCB0cmFuc2xhdGUoJ1BsYXllciBHYW1lcyBSZXN1bHRzJykgKSArXG4nXFxuICAgICAgICA8L2g0PlxcbiAgICA8L2Zvcm0+XFxuXFxuICAgIDxkaXYgY2xhc3M9XCJib3gtYm9keSBhcHAtdG90YWxzLXdpZGdldFwiIHN0eWxlPVwicGFkZGluZzogMjBweCAwIDA7XCI+XFxuICAgICAgICA8ZGl2IGNsYXNzPVwiYm94LWhlYWRlciB3aXRoLWJvcmRlclwiIHN0eWxlPVwiYmFja2dyb3VuZC1jb2xvcjogI2U0ZThlYztcIj5cXG4gICAgICAgICAgICA8bGFiZWwgc3R5bGU9XCJtYXJnaW46IDA7XCI+JyArXG5fX2UoIHRyYW5zbGF0ZSgnVG90YWwnKSApICtcbic8L2xhYmVsPlxcbiAgICAgICAgPC9kaXY+XFxuICAgICAgICA8ZGl2IGNsYXNzPVwiYm94LWJvZHkgYXBwLXRvdGFscy1yZWdpb25cIj5cXG4gICAgICAgIDwvZGl2PlxcbiAgICA8L2Rpdj5cXG5cXG4gICAgPGRpdiBjbGFzcz1cImJveC1ib2R5IGFwcC1saXN0LXJlZ2lvblwiIHN0eWxlPVwicGFkZGluZzogMjBweCAwO1wiPlxcbiAgICA8L2Rpdj5cXG4gICAgPGRpdiBjbGFzcz1cIm92ZXJsYXlcIiBzdHlsZT1cImRpc3BsYXk6IG5vbmU7XCI+XFxuICAgICAgICA8aSBjbGFzcz1cImZhIGZhLXJlZnJlc2ggZmEtc3BpblwiPjwvaT5cXG4gICAgPC9kaXY+XFxuPC9kaXY+XFxuJztcblxufVxucmV0dXJuIF9fcFxufTtcblxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vc3JjL2pzL3ZpZXdzL3BsYXllcmdhbWVzL3ZpZXcuZWpzXG4vLyBtb2R1bGUgaWQgPSAxOVxuLy8gbW9kdWxlIGNodW5rcyA9IDAiLCJ2YXIgU3RvcmFnZSA9IHJlcXVpcmUoJ2FwcC9zdG9yYWdlJyk7XG52YXIgTW9kZWxzID0gcmVxdWlyZSgnYXBwL21vZGVscycpO1xudmFyIGRlZmF1bHRzID0gcmVxdWlyZSgnYXBwL2RlZmF1bHRzJyk7XG52YXIgRm9ybVV0aWxzID0gcmVxdWlyZSgnbGliL2Zvcm0nKTtcbnZhciBsb2NhbGVzID0gcmVxdWlyZSgnbGliL2xvY2FsZXMnKTtcbnZhciBWaWV3ID0gcmVxdWlyZSgnbGliL3ZpZXcnKTtcblxudmFyIFJvdW5kc0dyaWRWaWV3ID0gcmVxdWlyZSgnLi9ncmlkJyk7XG52YXIgdHBsID0gcmVxdWlyZSgnLi92aWV3LmVqcycpO1xudmFyIFRvdGFsc0dyaWRWaWV3ID0gcmVxdWlyZSgndmlld3MvdG90YWxzL3ZpZXcnKTtcbnZhciBQbGF5ZXJJbmZvR3JpZFZpZXcgPSByZXF1aXJlKCd2aWV3cy9wbGF5ZXJfaW5mby92aWV3Jyk7XG5cblxudmFyIFRyYW5zYWN0aW9uc1ZpZXcgPSBCYWNrYm9uZS5NYXJpb25ldHRlLlZpZXcuZXh0ZW5kKHtcbiAgICBvcHRpb25zOiB7XG4gICAgICAgIHN0YXJ0X2RhdGU6IGRlZmF1bHRzLnN0YXJ0X2RhdGUsXG4gICAgICAgIGVuZF9kYXRlOiBkZWZhdWx0cy5lbmRfZGF0ZSxcbiAgICAgICAgdHo6IGRlZmF1bHRzLnR6LFxuICAgICAgICByZXBvcnRfdHlwZTogZGVmYXVsdHMucmVwb3J0X3R5cGUsXG4gICAgICAgIGdhbWVfaWQ6IGRlZmF1bHRzLmdhbWVfaWQsXG4gICAgICAgIGN1cnJlbmN5OiBkZWZhdWx0cy5jdXJyZW5jeSxcbiAgICAgICAgbW9kZTogZGVmYXVsdHMubW9kZSxcbiAgICAgICAgcGVyX3BhZ2U6IGRlZmF1bHRzLnBlcl9wYWdlLFxuICAgICAgICByb3VuZF9pZDogZGVmYXVsdHMucm91bmRfaWQsXG4gICAgICAgIGhlYWRlcjogZGVmYXVsdHMuaGVhZGVyLFxuICAgICAgICB0b3RhbHM6IGRlZmF1bHRzLnRvdGFscyxcbiAgICAgICAgaW5mbzogZGVmYXVsdHMuaW5mbyxcbiAgICAgICAgZXhjZWVkczogZGVmYXVsdHMuZXhjZWVkcyxcbiAgICAgICAgbGFuZzogZGVmYXVsdHMubGFuZ1xuICAgIH0sXG4gICAgdGVtcGxhdGU6IHRwbCxcbiAgICB0ZW1wbGF0ZUNvbnRleHQ6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgcmV0dXJuIHtcbiAgICAgICAgICAgIHJlcG9ydF90eXBlczogU3RvcmFnZS5yZXBvcnRUeXBlcy50b0pTT04oKSxcbiAgICAgICAgICAgIGF2YWlsYWJsZV9tb2RlczogU3RvcmFnZS5hdmFpbGFibGVNb2Rlcy50b0pTT04oKSxcbiAgICAgICAgICAgIGF2YWlsYWJsZV9nYW1lczogU3RvcmFnZS5hdmFpbGFibGVHYW1lcy50b0pTT04oKSxcbiAgICAgICAgICAgIHJlcG9ydF90eXBlOiB0aGlzLmdldE9wdGlvbigncmVwb3J0X3R5cGUnKSxcbiAgICAgICAgICAgIG1vZGU6IHRoaXMuZ2V0T3B0aW9uKCdtb2RlJyksXG4gICAgICAgICAgICBnYW1lX2lkOiB0aGlzLmdldE9wdGlvbignZ2FtZV9pZCcpLFxuICAgICAgICAgICAgaGVhZGVyOiB0aGlzLmdldE9wdGlvbignaGVhZGVyJyksXG4gICAgICAgICAgICB0b3RhbHM6IHRoaXMuZ2V0T3B0aW9uKCd0b3RhbHMnKSxcbiAgICAgICAgICAgIHNob3dQbGF5ZXJJbmZvOiB0aGlzLnNob3dQbGF5ZXJJbmZvLFxuICAgICAgICAgICAgZXhjZWVkczogdGhpcy5nZXRPcHRpb24oJ2V4Y2VlZHMnKSxcbiAgICAgICAgICAgIHJvdW5kX2lkOiB0aGlzLmdldE9wdGlvbigncm91bmRfaWQnKSxcbiAgICAgICAgICAgIHRyYW5zbGF0ZTogbG9jYWxlcy50cmFuc2xhdGUuYmluZChsb2NhbGVzKVxuICAgICAgICB9XG4gICAgfSxcbiAgICB1aToge1xuICAgICAgICBsaXN0UmVnaW9uOiAnLmFwcC1saXN0LXJlZ2lvbicsXG4gICAgICAgIHRvdGFsc1JlZ2lvbjogJy5hcHAtdG90YWxzLXJlZ2lvbicsXG4gICAgICAgIHRvdGFsc1dpZGdldDogJy5hcHAtdG90YWxzLXdpZGdldCcsXG4gICAgICAgIHBsYXllckluZm9SZWdpb246ICcuYXBwLXBsYXllci1pbmZvLXJlZ2lvbicsXG4gICAgICAgIG92ZXJsYXk6ICcub3ZlcmxheSdcbiAgICB9LFxuICAgIHJlZ2lvbnM6IHtcbiAgICAgICAgbGlzdFJlZ2lvbjogJ0B1aS5saXN0UmVnaW9uJyxcbiAgICAgICAgdG90YWxzUmVnaW9uOiAnQHVpLnRvdGFsc1JlZ2lvbicsXG4gICAgICAgIHBsYXllckluZm9SZWdpb246ICdAdWkucGxheWVySW5mb1JlZ2lvbidcbiAgICB9LFxuICAgIGNvbGxlY3Rpb25FdmVudHM6IHtcbiAgICAgICAgdXBkYXRlOiAnb25Db2xsZWN0aW9uVXBkYXRlJyxcbiAgICAgICAgcmVxdWVzdDogJ29uQ29sbGVjdGlvblJlcXVlc3QnLFxuICAgICAgICBzeW5jOiAnb25Db2xsZWN0aW9uU3luYycsXG4gICAgICAgIGVycm9yOiAnb25Db2xsZWN0aW9uRXJyb3InXG4gICAgfSxcblxuICAgIGJlaGF2aW9yczogW3tcbiAgICAgICAgYmVoYXZpb3JDbGFzczogRm9ybVV0aWxzLkZvcm1CZWhhdmlvcixcbiAgICAgICAgc3VibWl0RXZlbnQ6ICdzZWFyY2g6Zm9ybTpzdWJtaXQnXG4gICAgfSwge1xuICAgICAgICBiZWhhdmlvckNsYXNzOiBWaWV3Lk5lc3RlZFZpZXdCZWhhdmlvclxuICAgIH1dLFxuXG4gICAgaW5pdGlhbGl6ZTogZnVuY3Rpb24gKCkge1xuICAgICAgICBsb2NhbGVzLnNldCh0aGlzLm9wdGlvbnMubGFuZyk7XG4gICAgICAgIHRoaXMub3B0aW9ucy50eiA9IHBhcnNlSW50KHRoaXMub3B0aW9ucy50eik7XG4gICAgICAgIGlmIChfLmlzTmFOKHRoaXMub3B0aW9ucy50eikpIHtcbiAgICAgICAgICAgIHRoaXMub3B0aW9ucy50eiA9IGRlZmF1bHRzLnR6O1xuICAgICAgICB9XG4gICAgICAgIGlmKHRoaXMub3B0aW9ucy5zdGFydF9kYXRlKSB7XG4gICAgICAgICAgICB0aGlzLm9wdGlvbnMuc3RhcnRfZGF0ZSA9IG1vbWVudCh0aGlzLm9wdGlvbnMuc3RhcnRfZGF0ZSwgJ1lZWVlNTUREVEhIbW0nKTtcbiAgICAgICAgICAgIHRoaXMub3B0aW9ucy5zdGFydF9kYXRlID0gdGhpcy5vcHRpb25zLnN0YXJ0X2RhdGUudXRjT2Zmc2V0KHRoaXMub3B0aW9ucy50eiwgdHJ1ZSk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICB0aGlzLm9wdGlvbnMuc3RhcnRfZGF0ZSA9IG51bGw7XG4gICAgICAgIH1cbiAgICAgICAgaWYodGhpcy5vcHRpb25zLmVuZF9kYXRlKSB7XG4gICAgICAgICAgICB0aGlzLm9wdGlvbnMuZW5kX2RhdGUgPSBtb21lbnQodGhpcy5vcHRpb25zLmVuZF9kYXRlLCAnWVlZWU1NRERUSEhtbScpO1xuICAgICAgICAgICAgdGhpcy5vcHRpb25zLmVuZF9kYXRlID0gdGhpcy5vcHRpb25zLmVuZF9kYXRlLnV0Y09mZnNldCh0aGlzLm9wdGlvbnMudHosIHRydWUpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgdGhpcy5vcHRpb25zLmVuZF9kYXRlID0gbnVsbDtcbiAgICAgICAgfVxuXG4gICAgICAgIHRoaXMuY29sbGVjdGlvbiA9IG5ldyBNb2RlbHMuVHJhbnNhY3Rpb25Db2xsZWN0aW9uKFtdLCB7XG4gICAgICAgICAgICBxdWVyeVBhcmFtczoge1xuICAgICAgICAgICAgICAgIHN0YXJ0X2RhdGU6IF8uYmluZChmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIHNkID0gdGhpcy5nZXRPcHRpb24oJ3N0YXJ0X2RhdGUnKTtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHNkID8gc2QuZm9ybWF0KCkgOiBudWxsO1xuICAgICAgICAgICAgICAgIH0sIHRoaXMpLFxuICAgICAgICAgICAgICAgIGVuZF9kYXRlOiBfLmJpbmQoZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciBlZCA9IHRoaXMuZ2V0T3B0aW9uKCdlbmRfZGF0ZScpO1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gZWQgPyBlZC5mb3JtYXQoKSA6IG51bGw7XG4gICAgICAgICAgICAgICAgfSwgdGhpcyksXG4gICAgICAgICAgICAgICAgZ2FtZV9pZDogXy5iaW5kKGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gdGhpcy5nZXRPcHRpb24oJ2dhbWVfaWQnKSB8fCBudWxsO1xuICAgICAgICAgICAgICAgIH0sIHRoaXMpLFxuICAgICAgICAgICAgICAgIGN1cnJlbmN5OiBfLmJpbmQoZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiB0aGlzLmdldE9wdGlvbignY3VycmVuY3knKSB8fCBudWxsO1xuICAgICAgICAgICAgICAgIH0sIHRoaXMpLFxuICAgICAgICAgICAgICAgIG1vZGU6IF8uYmluZCh0aGlzLmdldE9wdGlvbiwgdGhpcywgJ21vZGUnKSxcbiAgICAgICAgICAgICAgICByZXBvcnRfdHlwZTogXy5iaW5kKHRoaXMuZ2V0T3B0aW9uT3JOdWxsLCB0aGlzLCAncmVwb3J0X3R5cGUnKSxcbiAgICAgICAgICAgICAgICByb3VuZF9pZDogXy5iaW5kKHRoaXMuZ2V0T3B0aW9uT3JOdWxsLCB0aGlzLCAncm91bmRfaWQnKSxcbiAgICAgICAgICAgICAgICB0ejogXy5iaW5kKHRoaXMuZ2V0T3B0aW9uLCB0aGlzLCAndHonKSxcbiAgICAgICAgICAgICAgICBleGNlZWRzOiBfLmJpbmQoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICB2YXIgZXhjZWVkcyA9IHRoaXMuZ2V0T3B0aW9uKCdleGNlZWRzJyk7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiAhIStleGNlZWRzO1xuICAgICAgICAgICAgICAgIH0sIHRoaXMpXG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgc3RhdGU6IHtcbiAgICAgICAgICAgICAgICBwYWdlU2l6ZTogdGhpcy5nZXRPcHRpb24oJ3Blcl9wYWdlJykgKiAxXG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgICAgICB0aGlzLnN1YnRvdGFsc0NvbGxlY3Rpb24gPSBuZXcgTW9kZWxzLlRyYW5zYWN0aW9uVG90YWxzQ29sbGVjdGlvbihbXSwge1xuICAgICAgICAgICAgc291cmNlOiB0aGlzLmNvbGxlY3Rpb25cbiAgICAgICAgfSk7XG4gICAgICAgIHRoaXMudG90YWxzTW9kZWwgPSBuZXcgTW9kZWxzLlBsYXllckdhbWVNb2RlbCh7XG4gICAgICAgICAgICBjdXJyZW5jeTogdGhpcy5nZXRPcHRpb24oJ2N1cnJlbmN5JylcbiAgICAgICAgfSk7XG5cbiAgICAgICAgdGhpcy5zaG93UGxheWVySW5mbyA9IHRoaXMuZ2V0T3B0aW9uKCdpbmZvJykgIT09ICcwJztcbiAgICAgICAgaWYgKHRoaXMuc2hvd1BsYXllckluZm8pIHtcbiAgICAgICAgICAgIHRoaXMucGxheWVySW5mb01vZGVsID0gbmV3IE1vZGVscy5QbGF5ZXJJbmZvTW9kZWwoKTtcbiAgICAgICAgfVxuICAgIH0sXG4gICAgcmVuZGVyR3JpZDogZnVuY3Rpb24gKCkge1xuICAgICAgICBpZiAoIXRoaXMuZ2V0Q2hpbGRWaWV3KCdsaXN0UmVnaW9uJykpIHtcbiAgICAgICAgICAgIHZhciBncmlkVmlldyA9IG5ldyBSb3VuZHNHcmlkVmlldyh7XG4gICAgICAgICAgICAgICAgY29sbGVjdGlvbjogdGhpcy5jb2xsZWN0aW9uLFxuICAgICAgICAgICAgICAgIGZvb3RlckNvbGxlY3Rpb246IHRoaXMuc3VidG90YWxzQ29sbGVjdGlvblxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB0aGlzLnNob3dDaGlsZFZpZXcoJ2xpc3RSZWdpb24nLCBncmlkVmlldyk7XG4gICAgICAgIH1cbiAgICAgICAgdGhpcy5jb2xsZWN0aW9uLmZldGNoKCk7XG4gICAgfSxcblxuICAgIHJlbmRlclRvdGFsc0dyaWQ6IGZ1bmN0aW9uKCkge1xuICAgICAgICBpZiAoIXRoaXMuZ2V0Q2hpbGRWaWV3KCd0b3RhbHNSZWdpb24nKSkge1xuICAgICAgICAgICAgdmFyIGdyaWRWaWV3ID0gbmV3IFRvdGFsc0dyaWRWaWV3KHtcbiAgICAgICAgICAgICAgICBjb2xsZWN0aW9uOiBuZXcgQmFja2JvbmUuQ29sbGVjdGlvbihbXG4gICAgICAgICAgICAgICAgICAgIHRoaXMudG90YWxzTW9kZWxcbiAgICAgICAgICAgICAgICBdKVxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB0aGlzLnNob3dDaGlsZFZpZXcoJ3RvdGFsc1JlZ2lvbicsIGdyaWRWaWV3KTtcbiAgICAgICAgICAgIHRoaXMuaGlkZVRvdGFscygpO1xuICAgICAgICB9XG4gICAgICAgIHRoaXMuZmV0Y2hUb3RhbHMoKTtcbiAgICB9LFxuXG4gICAgZmV0Y2hUb3RhbHM6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdmFyIHN0YXJ0X2RhdGUgPSB0aGlzLl9lbmNvZGVEYXRlKCdzdGFydF9kYXRlJyksXG4gICAgICAgICAgICBlbmRfZGF0ZSA9IHRoaXMuX2VuY29kZURhdGUoJ2VuZF9kYXRlJyk7XG4gICAgICAgIHRoaXMudG90YWxzTW9kZWwuZmV0Y2goe1xuICAgICAgICAgICAgZGF0YToge1xuICAgICAgICAgICAgICAgIHN0YXJ0X2RhdGU6IHN0YXJ0X2RhdGUsXG4gICAgICAgICAgICAgICAgZW5kX2RhdGU6IGVuZF9kYXRlLFxuICAgICAgICAgICAgICAgIGN1cnJlbmN5OiB0aGlzLmdldE9wdGlvbignY3VycmVuY3knKSxcbiAgICAgICAgICAgICAgICBtb2RlOiB0aGlzLmdldE9wdGlvbignbW9kZScpLFxuICAgICAgICAgICAgICAgIHJlcG9ydF90eXBlOiB0aGlzLmdldE9wdGlvbigncmVwb3J0X3R5cGUnKSxcbiAgICAgICAgICAgICAgICBnYW1lX2lkOiB0aGlzLmdldE9wdGlvbignZ2FtZV9pZCcpLFxuICAgICAgICAgICAgICAgIHJvdW5kX2lkOiB0aGlzLmdldE9wdGlvbigncm91bmRfaWQnKSB8fCBudWxsXG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH0sXG5cbiAgICBoaWRlVG90YWxzOiBmdW5jdGlvbigpIHtcbiAgICAgICAgdGhpcy51aS50b3RhbHNXaWRnZXQuaGlkZSgpO1xuICAgIH0sXG5cbiAgICBzaG93VG90YWxzOiBmdW5jdGlvbigpIHtcbiAgICAgICAgdGhpcy51aS50b3RhbHNXaWRnZXQuc2hvdygpO1xuICAgIH0sXG5cbiAgICByZW5kZXJQbGF5ZXJJbmZvOiBmdW5jdGlvbigpIHtcbiAgICAgICAgaWYgKCF0aGlzLmdldENoaWxkVmlldygncGxheWVySW5mb1JlZ2lvbicpKSB7XG4gICAgICAgICAgICB2YXIgZ3JpZFZpZXcgPSBuZXcgUGxheWVySW5mb0dyaWRWaWV3KHtcbiAgICAgICAgICAgICAgICBjb2xsZWN0aW9uOiBuZXcgQmFja2JvbmUuQ29sbGVjdGlvbihbXG4gICAgICAgICAgICAgICAgICAgIHRoaXMucGxheWVySW5mb01vZGVsXG4gICAgICAgICAgICAgICAgXSlcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgdGhpcy5zaG93Q2hpbGRWaWV3KCdwbGF5ZXJJbmZvUmVnaW9uJywgZ3JpZFZpZXcpO1xuICAgICAgICB9XG4gICAgICAgIHRoaXMuZmV0Y2hQbGF5ZXJJbmZvKCk7XG4gICAgfSxcblxuICAgIGZldGNoUGxheWVySW5mbzogZnVuY3Rpb24oKSB7XG4gICAgICAgIHRoaXMucGxheWVySW5mb01vZGVsLmZldGNoKHtcbiAgICAgICAgICAgIGRhdGE6IHtcbiAgICAgICAgICAgICAgICByb3VuZF9pZDogdGhpcy5nZXRPcHRpb24oJ3JvdW5kX2lkJykgfHwgbnVsbCxcbiAgICAgICAgICAgICAgICBjdXJyZW5jeTogdGhpcy5nZXRPcHRpb24oJ2N1cnJlbmN5JyksXG4gICAgICAgICAgICAgICAgbW9kZTogdGhpcy5nZXRPcHRpb24oJ21vZGUnKSxcbiAgICAgICAgICAgICAgICByZXBvcnRfdHlwZTogdGhpcy5nZXRPcHRpb24oJ3JlcG9ydF90eXBlJyksXG4gICAgICAgICAgICAgICAgZ2FtZV9pZDogdGhpcy5nZXRPcHRpb24oJ2dhbWVfaWQnKVxuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9LFxuXG4gICAgb25SZW5kZXI6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgc2V0VGltZW91dChfLmJpbmQoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdGhpcy4kKCcjZ2FwaWNrZXInKS5nYXBpY2tlcih7XG4gICAgICAgICAgICAgICAgYWxsb3dOdWxsczogdHJ1ZSxcbiAgICAgICAgICAgICAgICBzaG93VGltZTogdHJ1ZSxcbiAgICAgICAgICAgICAgICBzdGFydERhdGU6IHRoaXMuZ2V0T3B0aW9uKCdzdGFydF9kYXRlJyksXG4gICAgICAgICAgICAgICAgZW5kRGF0ZTogdGhpcy5nZXRPcHRpb24oJ2VuZF9kYXRlJyksXG4gICAgICAgICAgICAgICAgdXRjT2Zmc2V0OiB0aGlzLmdldE9wdGlvbigndHonKSxcbiAgICAgICAgICAgICAgICBjYWxlbmRhcnNDb3VudDogMixcbiAgICAgICAgICAgICAgICBwb3NpdGlvbjogJ2JvdHRvbSBjZW50ZXInLFxuICAgICAgICAgICAgICAgIGZvcm1hdDogJ1lZWVktTU0tREQgSEg6bW06c3MnLFxuICAgICAgICAgICAgICAgIGFwcGx5TGFiZWw6IGxvY2FsZXMudHJhbnNsYXRlKCdBcHBseScpLFxuICAgICAgICAgICAgICAgIGNhbmNlbExhYmVsOiBsb2NhbGVzLnRyYW5zbGF0ZSgnQ2FuY2VsJyksXG4gICAgICAgICAgICAgICAgZnJvbUxhYmVsOiBsb2NhbGVzLnRyYW5zbGF0ZSgnRnJvbScpICsgJzogJyxcbiAgICAgICAgICAgICAgICB0b0xhYmVsOiBsb2NhbGVzLnRyYW5zbGF0ZSgnVG8nKSArICc6ICcsXG4gICAgICAgICAgICAgICAgb2Zmc2V0TGFiZWw6IGxvY2FsZXMudHJhbnNsYXRlKCdab25lJykgKyAnOiAnLFxuICAgICAgICAgICAgICAgIGRheXNPZldlZWs6IFtcbiAgICAgICAgICAgICAgICAgICAgbG9jYWxlcy50cmFuc2xhdGUoJ1N1JyksXG4gICAgICAgICAgICAgICAgICAgIGxvY2FsZXMudHJhbnNsYXRlKCdNbycpLFxuICAgICAgICAgICAgICAgICAgICBsb2NhbGVzLnRyYW5zbGF0ZSgnVHUnKSxcbiAgICAgICAgICAgICAgICAgICAgbG9jYWxlcy50cmFuc2xhdGUoJ1dlJyksXG4gICAgICAgICAgICAgICAgICAgIGxvY2FsZXMudHJhbnNsYXRlKCdUaCcpLFxuICAgICAgICAgICAgICAgICAgICBsb2NhbGVzLnRyYW5zbGF0ZSgnRnInKSxcbiAgICAgICAgICAgICAgICAgICAgbG9jYWxlcy50cmFuc2xhdGUoJ1NhJylcbiAgICAgICAgICAgICAgICAgICAgXSxcblxuICAgICAgICAgICAgICAgIG1vbnRoTmFtZXM6IFtcbiAgICAgICAgICAgICAgICAgICAgbG9jYWxlcy50cmFuc2xhdGUoJ0phbicpLFxuICAgICAgICAgICAgICAgICAgICBsb2NhbGVzLnRyYW5zbGF0ZSgnRmViJyksXG4gICAgICAgICAgICAgICAgICAgIGxvY2FsZXMudHJhbnNsYXRlKCdNYXInKSxcbiAgICAgICAgICAgICAgICAgICAgbG9jYWxlcy50cmFuc2xhdGUoJ0FwcicpLFxuICAgICAgICAgICAgICAgICAgICBsb2NhbGVzLnRyYW5zbGF0ZSgnTWF5JyksXG4gICAgICAgICAgICAgICAgICAgIGxvY2FsZXMudHJhbnNsYXRlKCdKdW4nKSxcbiAgICAgICAgICAgICAgICAgICAgbG9jYWxlcy50cmFuc2xhdGUoJ0p1bCcpLFxuICAgICAgICAgICAgICAgICAgICBsb2NhbGVzLnRyYW5zbGF0ZSgnQXVnJyksXG4gICAgICAgICAgICAgICAgICAgIGxvY2FsZXMudHJhbnNsYXRlKCdTZXAnKSxcbiAgICAgICAgICAgICAgICAgICAgbG9jYWxlcy50cmFuc2xhdGUoJ09jdCcpLFxuICAgICAgICAgICAgICAgICAgICBsb2NhbGVzLnRyYW5zbGF0ZSgnTm92JyksXG4gICAgICAgICAgICAgICAgICAgIGxvY2FsZXMudHJhbnNsYXRlKCdEZWMnKVxuICAgICAgICAgICAgICAgIF0sXG4gICAgICAgICAgICAgICAgY3VzdG9tUmFuZ2VzOiBbXG4gICAgICAgICAgICAgICAgICAgIFsnMWQnLCBsb2NhbGVzLnRyYW5zbGF0ZSgnVG9kYXknKV0sXG4gICAgICAgICAgICAgICAgICAgIFsnMXcnLCBsb2NhbGVzLnRyYW5zbGF0ZSgnVGhpcyBXZWVrJyldLFxuICAgICAgICAgICAgICAgICAgICBbJzFtJywgbG9jYWxlcy50cmFuc2xhdGUoJ1RoaXMgTW9udGgnKV0sXG4gICAgICAgICAgICAgICAgICAgIFsnM20nLCBsb2NhbGVzLnRyYW5zbGF0ZSgnTGFzdCAzIE1vbnRocycpXVxuXG4gICAgICAgICAgICAgICAgXSxcbiAgICAgICAgICAgICAgICBjYWxsYmFjazogXy5iaW5kKGZ1bmN0aW9uKGZyb20sIHRvLCB0eikge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLm9wdGlvbnMuc3RhcnRfZGF0ZSA9IGZyb207XG4gICAgICAgICAgICAgICAgICAgIHRoaXMub3B0aW9ucy5lbmRfZGF0ZSA9IHRvO1xuICAgICAgICAgICAgICAgICAgICB0aGlzLm9wdGlvbnMudHogPSB0ejtcblxuICAgICAgICAgICAgICAgIH0sIHRoaXMpXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfSwgdGhpcyksIDEpO1xuXG4gICAgICAgIHRoaXMucmVuZGVyR3JpZCgpO1xuICAgICAgICB0aGlzLnJlbmRlclRvdGFsc0dyaWQoKTtcblxuICAgICAgICBpZiAodGhpcy5zaG93UGxheWVySW5mbykge1xuICAgICAgICAgICAgdGhpcy5yZW5kZXJQbGF5ZXJJbmZvKCk7XG4gICAgICAgIH1cbiAgICB9LFxuICAgIG9uQ29sbGVjdGlvblN5bmM6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdGhpcy51aS5vdmVybGF5LmhpZGUoKTtcbiAgICB9LFxuICAgIG9uQ29sbGVjdGlvbkVycm9yOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHRoaXMudWkub3ZlcmxheS5oaWRlKCk7XG4gICAgfSxcbiAgICBvbkNvbGxlY3Rpb25SZXF1ZXN0OiBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHRoaXMudWkub3ZlcmxheS5zaG93KCk7XG4gICAgICAgIHRoaXMudHJpZ2dlck1ldGhvZCgnc3RhdGU6Y2hhbmdlZCcsIHtcbiAgICAgICAgICAgIHNob3c6ICd0cmFuc2FjdGlvbnMnLFxuICAgICAgICAgICAgZ2FtZV9pZDogdGhpcy5vcHRpb25zLmdhbWVfaWQsXG4gICAgICAgICAgICB0ejogdGhpcy5vcHRpb25zLnR6LFxuICAgICAgICAgICAgc3RhcnRfZGF0ZTogdGhpcy5vcHRpb25zLnN0YXJ0X2RhdGUsXG4gICAgICAgICAgICBlbmRfZGF0ZTogdGhpcy5vcHRpb25zLmVuZF9kYXRlLFxuICAgICAgICAgICAgcGVyX3BhZ2U6IHRoaXMub3B0aW9ucy5wZXJfcGFnZSxcbiAgICAgICAgICAgIHJvdW5kX2lkOiB0aGlzLm9wdGlvbnMucm91bmRfaWQsXG4gICAgICAgICAgICBjdXJyZW5jeTogdGhpcy5vcHRpb25zLmN1cnJlbmN5LFxuICAgICAgICAgICAgbW9kZTogdGhpcy5vcHRpb25zLm1vZGUsXG4gICAgICAgICAgICByZXBvcnRfdHlwZTogdGhpcy5vcHRpb25zLnJlcG9ydF90eXBlLFxuICAgICAgICAgICAgaGVhZGVyOiB0aGlzLm9wdGlvbnMuaGVhZGVyLFxuICAgICAgICAgICAgdG90YWxzOiB0aGlzLm9wdGlvbnMudG90YWxzLFxuICAgICAgICAgICAgaW5mbzogdGhpcy5vcHRpb25zLmluZm8sXG4gICAgICAgICAgICBleGNlZWRzOiB0aGlzLm9wdGlvbnMuZXhjZWVkcyxcbiAgICAgICAgICAgIGxhbmc6IHRoaXMub3B0aW9ucy5sYW5nXG4gICAgICAgIH0pO1xuICAgIH0sXG5cbiAgICBvbkNvbGxlY3Rpb25VcGRhdGU6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgaWYgKHRoaXMuZ2V0T3B0aW9uKCd0b3RhbHMnKSA9PT0gJzAnKSB7XG4gICAgICAgICAgICB0aGlzLmhpZGVUb3RhbHMoKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIHRoaXMuc2hvd1RvdGFscygpO1xuICAgICAgICB9XG5cbiAgICB9LFxuICAgIF9lbmNvZGVEYXRlOiBmdW5jdGlvbihrZXkpIHtcbiAgICAgICAgdmFyIGRhdGUgPSB0aGlzLmdldE9wdGlvbihrZXkpO1xuICAgICAgICByZXR1cm4gZGF0ZSA/IGRhdGUuZm9ybWF0KCkgOiBudWxsO1xuICAgIH0sXG5cbiAgICBvblNlYXJjaEZvcm1TdWJtaXQ6IGZ1bmN0aW9uKGRhdGEpIHtcbiAgICAgICAgXy5leHRlbmQodGhpcy5vcHRpb25zLCBkYXRhKTtcbiAgICAgICAgdGhpcy5jb2xsZWN0aW9uLmZldGNoKCk7XG4gICAgICAgIHRoaXMuZmV0Y2hUb3RhbHMoKTtcbiAgICAgICAgaWYgKHRoaXMuc2hvd1BsYXllckluZm8pIHtcbiAgICAgICAgICAgIHRoaXMuZmV0Y2hQbGF5ZXJJbmZvKCk7XG4gICAgICAgIH1cbiAgICB9XG59KTtcblxubW9kdWxlLmV4cG9ydHMgPSBUcmFuc2FjdGlvbnNWaWV3O1xuXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvanMvdmlld3MvdHJhbnNhY3Rpb25zL3ZpZXcuanNcbi8vIG1vZHVsZSBpZCA9IDIwXG4vLyBtb2R1bGUgY2h1bmtzID0gMCIsInZhciBHcmlkVXRpbHMgPSByZXF1aXJlKCdsaWIvZ3JpZCcpO1xudmFyIEdyaWRVdGlsc0N1c3RvbSA9IHJlcXVpcmUoJ2xpYi9ncmlkX2N1c3RvbScpO1xudmFyIFN0b3JhZ2UgPSByZXF1aXJlKCdhcHAvc3RvcmFnZScpO1xudmFyIGxvY2FsZXMgPSByZXF1aXJlKCdsaWIvbG9jYWxlcycpO1xuXG52YXIgVHJhbnNhY3Rpb25zR3JpZFZpZXcgPSBHcmlkVXRpbHMuRml4ZWRIZWFkZXJDZWxsTXVsdGlsaW5lRm9vdGVyLmV4dGVuZCh7XG4gICAgY2hpbGRWaWV3RXZlbnRzOiBfLmV4dGVuZCh7fSwgR3JpZFV0aWxzLkZpeGVkSGVhZGVyQ2VsbE11bHRpbGluZUZvb3Rlci5wcm90b3R5cGUuY2hpbGRWaWV3RXZlbnRzLCB7XG4gICAgICAgICdoZWFkZXI6ZXhwYW5kX2FsbCc6ICdvbkhlYWRlckV4cGFuZCcsXG4gICAgICAgICdoZWFkZXI6Y29sbGFwc2VfYWxsJzogJ29uSGVhZGVyQ29sbGFwc2UnLFxuXG4gICAgICAgICdyb3c6Y2VsbDp6b29tJzogJ2ludmFsaWRhdGVIZWFkZXInLFxuICAgICAgICAncm93OmNlbGw6dG9nZ2xlJzogJ2ludmFsaWRhdGVIZWFkZXInLFxuICAgICAgICAnaGVhZGVyOnRvZ2dsZSc6ICdpbnZhbGlkYXRlSGVhZGVyJyxcbiAgICB9KSxcblxuICAgIG9uSGVhZGVyRXhwYW5kOiBmdW5jdGlvbigpIHtcbiAgICAgICAgdGhpcy5nZXRDaGlsZFZpZXcoJ2JvZHknKS5jaGlsZHJlbi5lYWNoKGZ1bmN0aW9uKHZpZXcpIHtcbiAgICAgICAgICAgIHZpZXcuZXhwYW5kICYmIHZpZXcuZXhwYW5kKCk7XG4gICAgICAgIH0pO1xuICAgIH0sXG5cbiAgICBvbkhlYWRlckNvbGxhcHNlOiBmdW5jdGlvbigpIHtcbiAgICAgICAgdGhpcy5nZXRDaGlsZFZpZXcoJ2JvZHknKS5jaGlsZHJlbi5lYWNoKGZ1bmN0aW9uKHZpZXcpIHtcbiAgICAgICAgICAgIHZpZXcuY29sbGFwc2UgJiYgdmlldy5jb2xsYXBzZSgpO1xuICAgICAgICB9KTtcbiAgICB9LFxuXG4gICAgZGF0YVJvd1ZpZXc6IE1hR3JpZC5EYXRhUm93Vmlldy5leHRlbmQoe1xuICAgICAgICBleHBhbmQ6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXy5lYWNoKHRoaXMuZ2V0UmVnaW9ucygpLCBmdW5jdGlvbihyZWdpb24pIHtcbiAgICAgICAgICAgICAgICB2YXIgdmlldyA9IHJlZ2lvbi5jdXJyZW50VmlldztcbiAgICAgICAgICAgICAgICB2aWV3ICYmIHZpZXcuZXhwYW5kICYmIHZpZXcuZXhwYW5kKCk7XG4gICAgICAgICAgICB9LCB0aGlzKTtcbiAgICAgICAgfSxcbiAgICAgICAgY29sbGFwc2U6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgXy5lYWNoKHRoaXMuZ2V0UmVnaW9ucygpLCBmdW5jdGlvbihyZWdpb24pIHtcbiAgICAgICAgICAgICAgICB2YXIgdmlldyA9IHJlZ2lvbi5jdXJyZW50VmlldztcbiAgICAgICAgICAgICAgICB2aWV3ICYmIHZpZXcuY29sbGFwc2UgJiYgdmlldy5jb2xsYXBzZSgpO1xuICAgICAgICAgICAgfSwgdGhpcyk7XG4gICAgICAgIH1cbiAgICB9KSxcblxuICAgIGNvbHVtbnM6IFt7XG4gICAgICAgIGhlYWRlcjogJ0lEJyxcbiAgICAgICAgYXR0cjogJ3RyYW5zYWN0aW9uX2lkJyxcbiAgICAgICAgY2xhc3NOYW1lOiAndWlkLWNlbGwnLFxuICAgICAgICBjZWxsOiBHcmlkVXRpbHMuQ3VzdG9tQ2VsbC5leHRlbmQoe1xuICAgICAgICAgICAgY2xhc3NOYW1lOiAnc20tZmxvYXQtbGVmdCcsXG4gICAgICAgICAgICB0ZW1wbGF0ZTogXy50ZW1wbGF0ZShbXG4gICAgICAgICAgICAgICAgJzxzcGFuIGNsYXNzPVwidmlzaWJsZS14cy1pbmxpbmUgdGV4dC1ub3dyYXBcIj48Yj5VSUQ6IDwvYj48JT0gdmFsICU+PC9zcGFuPicsXG4gICAgICAgICAgICAgICAgJzxkaXYgY2xhc3M9XCJoaWRkZW4teHMgYnRuLXhzIGJ0bi1kZWZhdWx0IGJhZGdlXCI+PGkgY2xhc3M9XCJmYSBmYS1pbmZvXCI+PC9pPjwvYT4nLFxuICAgICAgICAgICAgXS5qb2luKCcnKSksXG4gICAgICAgICAgICBvblJlbmRlcjogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHRoaXMuJCgnZGl2JykucG9wb3Zlcih7XG4gICAgICAgICAgICAgICAgICAgIHRyaWdnZXI6ICdjbGljaycsXG4gICAgICAgICAgICAgICAgICAgIHBsYWNlbWVudDogJ3JpZ2h0JyxcbiAgICAgICAgICAgICAgICAgICAgaHRtbDogdHJ1ZSxcbiAgICAgICAgICAgICAgICAgICAgY29udGVudDogKF8udGVtcGxhdGUoW1xuICAgICAgICAgICAgICAgICAgICAgICAgJzwlLSB0cmFuc2FjdGlvbl9pZCAlPidcbiAgICAgICAgICAgICAgICAgICAgXS5qb2luKCcnKSkpKHRoaXMubW9kZWwudG9KU09OKCkpXG4gICAgICAgICAgICAgICAgfSlcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSlcbiAgICB9LCB7XG4gICAgICAgIGhlYWRlcjogTWFyaW9uZXR0ZS5WaWV3LmV4dGVuZCh7XG4gICAgICAgICAgICB0ZW1wbGF0ZTogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHJldHVybiBsb2NhbGVzLnRyYW5zbGF0ZSgnRGF0ZScpXG4gICAgICAgICAgICB9XG4gICAgICAgIH0pLFxuICAgICAgICBhdHRyOiAnY19hdCcsXG4gICAgICAgIHNvcnRhYmxlOiBmYWxzZSxcbiAgICAgICAgY2VsbDogR3JpZFV0aWxzLk1vYmlsZUNlbGwuZXh0ZW5kKHtcbiAgICAgICAgICAgIGRpc3BsYXlOYW1lOiBsb2NhbGVzLnRyYW5zbGF0ZSgnRGF0ZScpLFxuICAgICAgICAgICAgY2xhc3NOYW1lOiAnc20tZmxvYXQtbGVmdCcsXG4gICAgICAgICAgICBkYXRlRm9ybWF0OiAnWVlZWS1NTS1ERFsmbmJzcDtdSEg6bW06c3MnLFxuICAgICAgICAgICAgdGVtcGxhdGVDb250ZXh0OiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgdmFyIGNvbnRleHQgPSBHcmlkVXRpbHMuTW9iaWxlQ2VsbC5wcm90b3R5cGUudGVtcGxhdGVDb250ZXh0LmFwcGx5KHRoaXMsIGFyZ3VtZW50cyk7XG4gICAgICAgICAgICAgICAgdmFyIHZhbCA9IHRoaXMuZ2V0QXR0clZhbHVlKCksXG4gICAgICAgICAgICAgICAgICAgIHR6ID0gcGFyc2VJbnQodGhpcy5tb2RlbC5jb2xsZWN0aW9uLmxhc3RGaWx0ZXJzLnR6KSxcbiAgICAgICAgICAgICAgICAgICAgZm9ybWF0ID0gdGhpcy5nZXRPcHRpb24oJ2RhdGVGb3JtYXQnKSxcbiAgICAgICAgICAgICAgICAgICAgZHQgPSBtb21lbnQodmFsKS51dGMoKS51dGNPZmZzZXQodHopO1xuICAgICAgICAgICAgICAgIGNvbnRleHRbJ3ZhbCddID0gZHQgPyBkdC5mb3JtYXQoZm9ybWF0KS5yZXBsYWNlKCcgJywgJyZuYnNwOycpIDogJyZtZGFzaDsnO1xuICAgICAgICAgICAgICAgIHJldHVybiBjb250ZXh0O1xuICAgICAgICAgICAgfVxuICAgICAgICB9KVxuICAgIH0sIHtcbiAgICAgICAgYXR0cjogJ2N1cnJlbmN5JyxcbiAgICAgICAgaGVhZGVyOiBNYXJpb25ldHRlLlZpZXcuZXh0ZW5kKHtcbiAgICAgICAgICAgIHRlbXBsYXRlOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGxvY2FsZXMudHJhbnNsYXRlKCdDdXJyZW5jeScpXG4gICAgICAgICAgICB9XG4gICAgICAgIH0pLFxuICAgICAgICBzb3J0YWJsZTogZmFsc2UsXG4gICAgICAgIGNsYXNzTmFtZTogJ3RleHQtY2VudGVyJyxcbiAgICAgICAgY2VsbDogR3JpZFV0aWxzLk1vYmlsZUNlbGwuZXh0ZW5kKHtcbiAgICAgICAgICAgIHRlbXBsYXRlOiBfLnRlbXBsYXRlKFtcbiAgICAgICAgICAgICAgICAnPHNwYW4gY2xhc3M9XCJoaWRkZW4teHNcIj48JT0gdmFsICU+PC9zcGFuPidcbiAgICAgICAgICAgIF0uam9pbignJykpXG4gICAgICAgIH0pXG4gICAgfSwge1xuICAgICAgICBzb3J0YWJsZTogZmFsc2UsXG4gICAgICAgIGF0dHI6ICdiYWxhbmNlX2JlZm9yZScsXG4gICAgICAgIGhlYWRlcjogTWFyaW9uZXR0ZS5WaWV3LmV4dGVuZCh7XG4gICAgICAgICAgICB0ZW1wbGF0ZTogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHJldHVybiBsb2NhbGVzLnRyYW5zbGF0ZSgnQmFsLiBCZWZvcmUnKVxuICAgICAgICAgICAgfVxuICAgICAgICB9KSxcbiAgICAgICAgY2xhc3NOYW1lOiAndGV4dC1yaWdodCcsXG4gICAgICAgIGNlbGw6IEdyaWRVdGlscy5Nb2JpbGVDZWxsLmV4dGVuZCh7XG4gICAgICAgICAgICBjbGFzc05hbWU6ICdzbS1mbG9hdC1sZWZ0IHRleHQtcmlnaHQnLFxuICAgICAgICAgICAgc2hvd0N1cnJlbmN5OiB0cnVlLFxuICAgICAgICAgICAgZGlzcGxheU5hbWU6IGxvY2FsZXMudHJhbnNsYXRlKCdCYWwuIEJlZm9yZScpLFxuICAgICAgICB9KVxuICAgIH0sIHtcbiAgICAgICAgc29ydGFibGU6IGZhbHNlLFxuICAgICAgICBhdHRyOiAnYmV0JyxcbiAgICAgICAgaGVhZGVyOiBNYXJpb25ldHRlLlZpZXcuZXh0ZW5kKHtcbiAgICAgICAgICAgIHRlbXBsYXRlOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGxvY2FsZXMudHJhbnNsYXRlKCdCZXQnKVxuICAgICAgICAgICAgfVxuICAgICAgICB9KSxcbiAgICAgICAgY2xhc3NOYW1lOiAndGV4dC1yaWdodCcsXG4gICAgICAgIGNlbGw6IEdyaWRVdGlscy5Nb2JpbGVDZWxsLmV4dGVuZCh7XG4gICAgICAgICAgICBjbGFzc05hbWU6ICdzbS1mbG9hdC1sZWZ0IHRleHQtcmlnaHQnLFxuICAgICAgICAgICAgc2hvd0N1cnJlbmN5OiB0cnVlLFxuICAgICAgICAgICAgZGlzcGxheU5hbWU6IGxvY2FsZXMudHJhbnNsYXRlKCdCZXQnKSxcbiAgICAgICAgICAgIGdldEF0dHJWYWx1ZTogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHZhciB2YWwgPSBHcmlkVXRpbHMuTW9iaWxlQ2VsbC5wcm90b3R5cGUuZ2V0QXR0clZhbHVlLmFwcGx5KHRoaXMpO1xuXG4gICAgICAgICAgICAgICAgaWYgKHRoaXMubW9kZWwuZ2V0KCd0eXBlJykgPT09ICdST0xMQkFDSycpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuICc8aSBjbGFzcz1cImZhIGZhLXJvdGF0ZS1sZWZ0XCIgdGl0bGU9XCJSb2xsYmFja1wiPjwvaT4mbmJzcDsnICsgdmFsO1xuICAgICAgICAgICAgICAgIH0gZWxzZSBpZiAodmFsICE9IG51bGwgJiYgdGhpcy5tb2RlbC5nZXQoJ2lzX2JvbnVzJykpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHZhbCArICcgPGkgY2xhc3M9XCJmYSBmYS1naWZ0XCI+PC9pPic7XG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHZhbDtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pXG4gICAgfSwge1xuICAgICAgICBzb3J0YWJsZTogZmFsc2UsXG4gICAgICAgIGF0dHI6ICd3aW4nLFxuICAgICAgICBoZWFkZXI6IE1hcmlvbmV0dGUuVmlldy5leHRlbmQoe1xuICAgICAgICAgICAgdGVtcGxhdGU6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gbG9jYWxlcy50cmFuc2xhdGUoJ1dpbicpXG4gICAgICAgICAgICB9XG4gICAgICAgIH0pLFxuICAgICAgICBjbGFzc05hbWU6ICd0ZXh0LXJpZ2h0JyxcbiAgICAgICAgY2VsbDogR3JpZFV0aWxzLk1vYmlsZUNlbGwuZXh0ZW5kKHtcbiAgICAgICAgICAgIGNsYXNzTmFtZTogJ3NtLWZsb2F0LWxlZnQgdGV4dC1yaWdodCcsXG4gICAgICAgICAgICBzaG93Q3VycmVuY3k6IHRydWUsXG4gICAgICAgICAgICBkaXNwbGF5TmFtZTogbG9jYWxlcy50cmFuc2xhdGUoJ1dpbicpLFxuICAgICAgICAgICAgZ2V0QXR0clZhbHVlOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgaWYgKHRoaXMubW9kZWwuZ2V0KCdzdGF0dXMnKSA9PT0gJ09LJykge1xuICAgICAgICAgICAgICAgICAgICB2YXIgY29sdW1uID0gdGhpcy5nZXRPcHRpb24oJ2NvbHVtbicpO1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gdGhpcy5tb2RlbC5nZXQoY29sdW1uLmdldCgnYXR0cicpKTtcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gdGhpcy5tb2RlbC5nZXQoJ3N0YXR1cycpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfSlcbiAgICB9LCAge1xuICAgICAgICBzb3J0YWJsZTogZmFsc2UsXG4gICAgICAgIGF0dHI6ICdvdXRjb21lJyxcbiAgICAgICAgaGVhZGVyOiBNYXJpb25ldHRlLlZpZXcuZXh0ZW5kKHtcbiAgICAgICAgICAgIHRlbXBsYXRlOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGxvY2FsZXMudHJhbnNsYXRlKCdPdXRjb21lJylcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSksXG4gICAgICAgIGNsYXNzTmFtZTogJ3RleHQtcmlnaHQnLFxuICAgICAgICBjZWxsOiBHcmlkVXRpbHMuTW9iaWxlQ2VsbC5leHRlbmQoe1xuICAgICAgICAgICAgY2xhc3NOYW1lOiAnc20tZmxvYXQtbGVmdCB0ZXh0LXJpZ2h0JyxcbiAgICAgICAgICAgIHNob3dDdXJyZW5jeTogdHJ1ZSxcbiAgICAgICAgICAgIHNob3dTcGlubmVyOiB0cnVlLFxuICAgICAgICAgICAgZGlzcGxheU5hbWU6IGxvY2FsZXMudHJhbnNsYXRlKCdPdXRjb21lJyksXG4gICAgICAgICAgICBnZXRBdHRyVmFsdWU6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICB2YXIgc3RhdHVzID0gdGhpcy5tb2RlbC5nZXQoJ3N0YXR1cycpO1xuICAgICAgICAgICAgICAgIGlmIChzdGF0dXMgPT09ICdPSycpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGNvbHVtbiA9IHRoaXMuZ2V0T3B0aW9uKCdjb2x1bW4nKTtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHRoaXMubW9kZWwuZ2V0KGNvbHVtbi5nZXQoJ2F0dHInKSk7XG4gICAgICAgICAgICAgICAgfSBlbHNlIGlmIChzdGF0dXMgPT09ICdFWENFRUQnKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiAwO1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiAnPyc7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICB9KVxuICAgIH0sIHtcbiAgICAgICAgc29ydGFibGU6IGZhbHNlLFxuICAgICAgICBhdHRyOiAnYmFsYW5jZV9hZnRlcicsXG4gICAgICAgIGhlYWRlcjogTWFyaW9uZXR0ZS5WaWV3LmV4dGVuZCh7XG4gICAgICAgICAgICB0ZW1wbGF0ZTogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHJldHVybiBsb2NhbGVzLnRyYW5zbGF0ZSgnQmFsLiBBZnRlcicpXG4gICAgICAgICAgICB9XG4gICAgICAgIH0pLFxuICAgICAgICBjbGFzc05hbWU6ICd0ZXh0LXJpZ2h0JyxcbiAgICAgICAgY2VsbDogR3JpZFV0aWxzLk1vYmlsZUNlbGwuZXh0ZW5kKHtcbiAgICAgICAgICAgIGNsYXNzTmFtZTogJ3NtLWZsb2F0LWxlZnQgdGV4dC1yaWdodCcsXG4gICAgICAgICAgICBzaG93Q3VycmVuY3k6IHRydWUsXG4gICAgICAgICAgICBkaXNwbGF5TmFtZTogbG9jYWxlcy50cmFuc2xhdGUoJ0JhbC4gQWZ0ZXInKSxcbiAgICAgICAgICAgIGdldEF0dHJWYWx1ZTogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHZhciBzdGF0dXMgPSB0aGlzLm1vZGVsLmdldCgnc3RhdHVzJyk7XG4gICAgICAgICAgICAgICAgaWYgKHN0YXR1cyA9PT0gJ09LJyB8fCBzdGF0dXMgPT09ICdFWENFRUQnKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBHcmlkVXRpbHMuTW9iaWxlQ2VsbC5wcm90b3R5cGUuZ2V0QXR0clZhbHVlLmFwcGx5KHRoaXMpO1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiAnPyc7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICB9KVxuXG4gICAgfSwge1xuICAgICAgICBhdHRyOiAncm91bmRfaWQnLFxuICAgICAgICBzb3J0YWJsZTogZmFsc2UsXG4gICAgICAgIGhlYWRlcjogTWFyaW9uZXR0ZS5WaWV3LmV4dGVuZCh7XG4gICAgICAgICAgICB0ZW1wbGF0ZTogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHJldHVybiBsb2NhbGVzLnRyYW5zbGF0ZSgnUm91bmQnKVxuICAgICAgICAgICAgfVxuICAgICAgICB9KSxcbiAgICAgICAgY2xhc3NOYW1lOiAndGV4dC1jZW50ZXInLFxuICAgICAgICBjZWxsOiBHcmlkVXRpbHMuTW9iaWxlQ2VsbC5leHRlbmQoe1xuICAgICAgICAgICAgY2xhc3NOYW1lOiAnc20tZmxvYXQtbGVmdCcsXG4gICAgICAgICAgICBkaXNwbGF5TmFtZTogbG9jYWxlcy50cmFuc2xhdGUoJ1JvdW5kJylcbiAgICAgICAgfSlcbiAgICB9LCB7XG4gICAgICAgIGF0dHI6ICdyb3VuZF9pZCcsXG4gICAgICAgIGNsYXNzTmFtZTogJ2hlaWdodC1hdXRvIGRyYXdlci10YWJsZS1jZWxsJyxcbiAgICAgICAgaGVhZGVyOiBHcmlkVXRpbHNDdXN0b20uRHJhd2VySGVhZGVyLFxuICAgICAgICBjZWxsOiBHcmlkVXRpbHNDdXN0b20uRHJhd2VyQ2VsbFxuICAgIH1dLFxuICAgIHNpemVyVmlldzogTWFHcmlkLlNpemVyVmlldy5leHRlbmQoe1xuICAgICAgICB0ZW1wbGF0ZTogXy50ZW1wbGF0ZShbXG4gICAgICAgICAgICAnPHNlbGVjdCBjbGFzcz1cImZvcm0tY29udHJvbCBpbnB1dC1zbVwiPicsXG4gICAgICAgICAgICAnPCUgZm9yKHZhciBpIGluIHBhZ2VTaXplcykgeyAlPicsXG4gICAgICAgICAgICAnPG9wdGlvbiA8JSBpZihwYWdlU2l6ZXNbaV0gPT0gY3VycmVudFNpemUpIHsgJT4gc2VsZWN0ZWQ8JSB9JT4gIHZhbHVlPVwiPCU9IHBhZ2VTaXplc1tpXSAlPlwiPicsXG4gICAgICAgICAgICAnICAgIDwlPSBwYWdlVGV4dC5yZXBsYWNlKFwie3NpemV9XCIsIHBhZ2VTaXplc1tpXSkgJT4nLFxuICAgICAgICAgICAgJzwvb3B0aW9uPicsXG4gICAgICAgICAgICAnPCUgfSU+JyxcbiAgICAgICAgICAgICc8L3NlbGVjdD4nXG4gICAgICAgIF0uam9pbignJykpLFxuICAgICAgICB0ZW1wbGF0ZUNvbnRleHQ6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciBjdHggPSBNYUdyaWQuU2l6ZXJWaWV3LnByb3RvdHlwZS50ZW1wbGF0ZUNvbnRleHQuYXBwbHkodGhpcywgYXJndW1lbnRzKTtcbiAgICAgICAgICAgIGN0eC5wYWdlVGV4dCA9IGxvY2FsZXMudHJhbnNsYXRlKCd7c2l6ZX0gcGVyIHBhZ2UnKTtcbiAgICAgICAgICAgIHJldHVybiBjdHg7XG4gICAgICAgIH1cbiAgICB9KSxcblxuICAgIGZvb3RlckNvbHVtbnM6IFt7XG4gICAgICAgIGF0dHI6ICdjdXJyZW5jeScsXG4gICAgICAgIGNsYXNzTmFtZTogJ3RleHQtYm9sZCcsXG4gICAgICAgIGNlbGw6IEdyaWRVdGlscy5Nb2JpbGVDZWxsLmV4dGVuZCh7XG4gICAgICAgICAgICBjbGFzc05hbWU6ICdzbS1mbG9hdC1sZWZ0IHRleHQtY2VudGVyJyxcbiAgICAgICAgICAgIHNob3dTcGlubmVyOiB0cnVlLFxuICAgICAgICAgICAgZGlzcGxheU5hbWU6IGxvY2FsZXMudHJhbnNsYXRlKCdUb3RhbCcpLFxuICAgICAgICAgICAgdGVtcGxhdGU6IF8udGVtcGxhdGUoW1xuICAgICAgICAgICAgICAgICc8c3BhbiBjbGFzcz1cInZpc2libGUteHMtaW5saW5lXCI+JyxcbiAgICAgICAgICAgICAgICAnPGI+PCU9IGRpc3BsYXlOYW1lICU+OiA8JT0gXy5pc051bGwodmFsKSA/IFwiJm1kYXNoO1wiIDogdmFsICU+JyxcbiAgICAgICAgICAgICAgICAnPC9iPjwvc3Bhbj4nXG4gICAgICAgICAgICBdLmpvaW4oJycpKVxuICAgICAgICB9KVxuICAgIH0sIHtcbiAgICAgICAgYXR0cjogJ2N1cnJlbmN5JyxcbiAgICAgICAgY2xhc3NOYW1lOiAnaGlkZGVuLXhzIHRleHQtYm9sZCcsXG4gICAgICAgIGNlbGw6IF8ubm9vcFxuICAgIH0sIHtcbiAgICAgICAgYXR0cjogJ2N1cnJlbmN5JyxcbiAgICAgICAgY2xhc3NOYW1lOiAndGV4dC1jZW50ZXIgdGV4dC1ib2xkJyxcbiAgICAgICAgY2VsbDogR3JpZFV0aWxzLk1vYmlsZUNlbGwuZXh0ZW5kKHtcbiAgICAgICAgICAgIGNsYXNzTmFtZTogJ3NtLWZsb2F0LWxlZnQgdGV4dC1jZW50ZXInLFxuICAgICAgICAgICAgc2hvd0N1cnJlbmN5OiBmYWxzZSxcbiAgICAgICAgICAgIHNob3dTcGlubmVyOiB0cnVlLFxuICAgICAgICAgICAgZGlzcGxheU5hbWU6IGxvY2FsZXMudHJhbnNsYXRlKCdUb3RhbCcpLFxuICAgICAgICAgICAgdGVtcGxhdGU6IF8udGVtcGxhdGUoW1xuICAgICAgICAgICAgICAgICc8c3BhbiBjbGFzcz1cImhpZGRlbi14c1wiPicsXG4gICAgICAgICAgICAgICAgJzwlIGlmIChzaG93U3Bpbm5lcikgeyAlPicsXG4gICAgICAgICAgICAgICAgJyAgICA8JSBpZiAodmFsID09PSBudWxsKSB7ICU+JyxcbiAgICAgICAgICAgICAgICAnICAgICAgICA8aSBjbGFzcz1cXCdmYSBmYS1zcGluIGZhLXJlZnJlc2hcXCc+PC9pPicsXG4gICAgICAgICAgICAgICAgJyAgICA8JSB9IGVsc2UgeyAlPicsXG4gICAgICAgICAgICAgICAgJyAgICAgICAgPCU9IHZhbCAlPicsXG4gICAgICAgICAgICAgICAgJyAgICA8JSB9ICU+JyxcbiAgICAgICAgICAgICAgICAnPCUgfSBlbHNlIHsgJT4nLFxuICAgICAgICAgICAgICAgICcgICAgPCU9IF8uaXNOdWxsKHZhbCkgPyBcIiZtZGFzaDtcIiA6IHZhbCAlPicsXG4gICAgICAgICAgICAgICAgJzwlIH0gJT4nLFxuICAgICAgICAgICAgICAgICc8L3NwYW4+J1xuICAgICAgICAgICAgXS5qb2luKCcnKSlcbiAgICAgICAgfSlcbiAgICB9LCB7XG4gICAgICAgIGF0dHI6ICdjdXJyZW5jeScsXG4gICAgICAgIGNsYXNzTmFtZTogJ2hpZGRlbi14cyB0ZXh0LWJvbGQnLFxuICAgICAgICBjZWxsOiBfLm5vb3BcbiAgICB9LCB7XG4gICAgICAgIGF0dHI6ICdiZXQnLFxuICAgICAgICBjbGFzc05hbWU6ICd0ZXh0LXJpZ2h0IHRleHQtYm9sZCcsXG4gICAgICAgIGNlbGw6IEdyaWRVdGlscy5Nb2JpbGVDZWxsLmV4dGVuZCh7XG4gICAgICAgICAgICBjbGFzc05hbWU6ICdzbS1mbG9hdC1sZWZ0IHRleHQtcmlnaHQnLFxuICAgICAgICAgICAgc2hvd0N1cnJlbmN5OiB0cnVlLFxuICAgICAgICAgICAgc2hvd1NwaW5uZXI6IHRydWUsXG4gICAgICAgICAgICBkaXNwbGF5TmFtZTogbG9jYWxlcy50cmFuc2xhdGUoJ0JldCcpXG4gICAgICAgIH0pXG4gICAgfSwge1xuICAgICAgICBhdHRyOiAnd2luJyxcbiAgICAgICAgY2xhc3NOYW1lOiAndGV4dC1yaWdodCB0ZXh0LWJvbGQnLFxuICAgICAgICBjZWxsOiBHcmlkVXRpbHMuTW9iaWxlQ2VsbC5leHRlbmQoe1xuICAgICAgICAgICAgY2xhc3NOYW1lOiAnc20tZmxvYXQtbGVmdCB0ZXh0LXJpZ2h0JyxcbiAgICAgICAgICAgIHNob3dDdXJyZW5jeTogdHJ1ZSxcbiAgICAgICAgICAgIHNob3dTcGlubmVyOiB0cnVlLFxuICAgICAgICAgICAgZGlzcGxheU5hbWU6IGxvY2FsZXMudHJhbnNsYXRlKCdXaW5zJylcbiAgICAgICAgfSlcbiAgICB9LCAge1xuICAgICAgICBhdHRyOiAnb3V0Y29tZScsXG4gICAgICAgIGNsYXNzTmFtZTogJ3RleHQtcmlnaHQgdGV4dC1ib2xkJyxcbiAgICAgICAgY2VsbDogR3JpZFV0aWxzLk1vYmlsZUNlbGwuZXh0ZW5kKHtcbiAgICAgICAgICAgIGNsYXNzTmFtZTogJ3NtLWZsb2F0LWxlZnQgdGV4dC1yaWdodCcsXG4gICAgICAgICAgICBzaG93Q3VycmVuY3k6IHRydWUsXG4gICAgICAgICAgICBzaG93U3Bpbm5lcjogdHJ1ZSxcbiAgICAgICAgICAgIGRpc3BsYXlOYW1lOiBsb2NhbGVzLnRyYW5zbGF0ZSgnT3V0Y29tZScpXG4gICAgICAgIH0pXG4gICAgfSwge1xuICAgICAgICBhdHRyOiAnY3VycmVuY3knLFxuICAgICAgICBjbGFzc05hbWU6ICdoaWRkZW4teHMgdGV4dC1ib2xkJyxcbiAgICAgICAgY2VsbDogXy5ub29wXG4gICAgfSwge1xuICAgICAgICBhdHRyOiAncm91bmRfaWQnLFxuICAgICAgICBjbGFzc05hbWU6ICd0ZXh0LWNlbnRlciB0ZXh0LWJvbGQnLFxuICAgICAgICBjZWxsOiBHcmlkVXRpbHMuTW9iaWxlQ2VsbC5leHRlbmQoe1xuICAgICAgICAgICAgY2xhc3NOYW1lOiAnc20tZmxvYXQtbGVmdCB0ZXh0LWNlbnRlcicsXG4gICAgICAgICAgICBzaG93Q3VycmVuY3k6IGZhbHNlLFxuICAgICAgICAgICAgc2hvd1NwaW5uZXI6IHRydWUsXG4gICAgICAgICAgICBkaXNwbGF5TmFtZTogbG9jYWxlcy50cmFuc2xhdGUoJ1JvdW5kcycpXG4gICAgICAgIH0pXG4gICAgfV1cbn0pO1xuXG5tb2R1bGUuZXhwb3J0cyA9IFRyYW5zYWN0aW9uc0dyaWRWaWV3O1xuXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvanMvdmlld3MvdHJhbnNhY3Rpb25zL2dyaWQuanNcbi8vIG1vZHVsZSBpZCA9IDIxXG4vLyBtb2R1bGUgY2h1bmtzID0gMCIsImZ1bmN0aW9uIHJlcXVlc3RBbmltYXRpb25GcmFtZShjYWxsYmFjaykge1xuICAgIHJldHVybiB3aW5kb3cucmVxdWVzdEFuaW1hdGlvbkZyYW1lXG4gICAgICAgID8gd2luZG93LnJlcXVlc3RBbmltYXRpb25GcmFtZShjYWxsYmFjaylcbiAgICAgICAgOiBzZXRUaW1lb3V0KGNhbGxiYWNrLCAxKTtcbn1cblxuZnVuY3Rpb24gY2FuY2VsQW5pbWF0aW9uRnJhbWUoaWQpIHtcbiAgICByZXR1cm4gd2luZG93LmNhbmNlbEFuaW1hdGlvbkZyYW1lXG4gICAgICAgID8gd2luZG93LmNhbmNlbEFuaW1hdGlvbkZyYW1lKGlkKVxuICAgICAgICA6IGNsZWFyVGltZW91dChpZCk7XG59XG5cbnZhciBRdWV1ZUl0ZW0gPSBNYXJpb25ldHRlLk9iamVjdC5leHRlbmQoe1xuICAgIGluaXRpYWxpemU6IGZ1bmN0aW9uKCRlbCwgbG9hZEZuLCBjb250ZXh0KSB7XG4gICAgICAgIHRoaXMuJGVsID0gJGVsO1xuICAgICAgICB0aGlzLmxvYWRGbiA9IGxvYWRGbjtcbiAgICAgICAgdGhpcy5jb250ZXh0ID0gY29udGV4dDtcblxuICAgICAgICB0aGlzLmRlc3Ryb3kgPSBfLmJpbmQodGhpcy5kZXN0cm95LCB0aGlzKTtcblxuICAgICAgICB0aGlzLl9zdWJzY3JpYmUoKTtcbiAgICB9LFxuXG4gICAgaXM6IGZ1bmN0aW9uKCRlbCkge1xuICAgICAgICByZXR1cm4gdGhpcy4kZWwgPT09ICRlbDtcbiAgICB9LFxuXG4gICAgbG9hZDogZnVuY3Rpb24oKSB7XG4gICAgICAgIHJldHVybiB0aGlzLmxvYWRGbi5jYWxsKHRoaXMuY29udGV4dCwgdGhpcy4kZWwpO1xuICAgIH0sXG5cbiAgICBkZXN0cm95OiBmdW5jdGlvbihvcHRpb25zKSB7XG4gICAgICAgIHRoaXMuX3Vuc3Vic2NyaWJlKCk7XG4gICAgICAgIGlmICghb3B0aW9ucyB8fCAhb3B0aW9ucy5zaWxlbnQpIHtcbiAgICAgICAgICAgIHRoaXMudHJpZ2dlck1ldGhvZCgnZGVzdHJveScsIHRoaXMpO1xuICAgICAgICB9XG4gICAgfSxcblxuICAgIF9zdWJzY3JpYmU6IGZ1bmN0aW9uKCkge1xuICAgICAgICB0aGlzLiRlbC5vbignbG9hZCcsIHRoaXMuZGVzdHJveSk7XG4gICAgICAgIHRoaXMuJGVsLm9uKCdlcnJvcicsIHRoaXMuZGVzdHJveSk7XG4gICAgfSxcblxuICAgIF91bnN1YnNjcmliZTogZnVuY3Rpb24oKSB7XG4gICAgICAgIHRoaXMuJGVsLm9mZignbG9hZCcsIHRoaXMuZGVzdHJveSk7XG4gICAgICAgIHRoaXMuJGVsLm9mZignZXJyb3InLCB0aGlzLmRlc3Ryb3kpO1xuICAgIH0sXG59KTtcblxudmFyIFF1ZXVlID0gTWFyaW9uZXR0ZS5PYmplY3QuZXh0ZW5kKHtcbiAgICBxdWV1ZTogbnVsbCxcbiAgICBwcm9jZXNzaW5nOiBudWxsLFxuICAgIGNvbmN1cnJlbnQ6IDIsXG4gICAgaW5pdGlhbGl6ZTogZnVuY3Rpb24oKSB7XG4gICAgICAgIHRoaXMucXVldWUgPSBbXTtcbiAgICAgICAgdGhpcy5wcm9jZXNzaW5nID0gW107XG4gICAgICAgIHRoaXMudGltZW91dCA9IG51bGw7XG4gICAgICAgIHRoaXMuX2ZsdXNoID0gXy5iaW5kKHRoaXMuX2ZsdXNoLCB0aGlzKTtcbiAgICB9LFxuICAgIGFkZDogZnVuY3Rpb24oJGVsLCBsb2FkRm4sIGNvbnRleHQpIHtcbiAgICAgICAgdmFyIGl0ZW0gPSBuZXcgUXVldWVJdGVtKCRlbCwgbG9hZEZuLCBjb250ZXh0KTtcbiAgICAgICAgaXRlbS5vbignZGVzdHJveScsIHRoaXMuX29uSXRlbURlc3Ryb3ksIHRoaXMpO1xuICAgICAgICB0aGlzLnF1ZXVlLnB1c2goaXRlbSk7XG4gICAgICAgIHRoaXMuX3NjaGVkdWxlRmx1c2goKTtcbiAgICB9LFxuICAgIHJlbW92ZTogZnVuY3Rpb24oJGVsKSB7XG4gICAgICAgIHRoaXMuX3JlbW92ZUVsRnJvbUFycmF5KCRlbCwgdGhpcy5xdWV1ZSk7XG4gICAgICAgIHRoaXMuX3JlbW92ZUVsRnJvbUFycmF5KCRlbCwgdGhpcy5wcm9jZXNzaW5nKTtcblxuICAgICAgICB0aGlzLl9zY2hlZHVsZUZsdXNoKCk7XG4gICAgfSxcbiAgICBfZmx1c2g6IGZ1bmN0aW9uKCkge1xuICAgICAgICB0aGlzLl91bnNjaGVkdWxlRmx1c2goKTtcblxuICAgICAgICB2YXIgcXVldWUgPSB0aGlzLnF1ZXVlLFxuICAgICAgICAgICAgcHJvY2Vzc2luZyA9IHRoaXMucHJvY2Vzc2luZyxcbiAgICAgICAgICAgIGNvbmN1cnJlbnQgPSB0aGlzLmdldE9wdGlvbignY29uY3VycmVudCcpO1xuXG4gICAgICAgIGlmICghcXVldWUubGVuZ3RoKSByZXR1cm47XG4gICAgICAgIGlmIChwcm9jZXNzaW5nLmxlbmd0aCA+PSBjb25jdXJyZW50KSByZXR1cm47XG5cbiAgICAgICAgdmFyIGl0ZW0gPSBxdWV1ZS5zaGlmdCgpO1xuICAgICAgICBwcm9jZXNzaW5nLnB1c2goaXRlbSk7XG4gICAgICAgIGl0ZW0ubG9hZCgpO1xuXG4gICAgICAgIHRoaXMuX3NjaGVkdWxlRmx1c2goKTtcbiAgICB9LFxuICAgIF9yZW1vdmVJdGVtRnJvbUFycmF5OiBmdW5jdGlvbihpdGVtLCBhcnJheSkge1xuICAgICAgICB2YXIgaSA9IGFycmF5LmluZGV4T2YoaXRlbSk7XG4gICAgICAgIGlmIChpICE9PSAtMSkge1xuICAgICAgICAgICAgYXJyYXkuc3BsaWNlKGksIDEpO1xuICAgICAgICB9XG4gICAgfSxcbiAgICBfcmVtb3ZlRWxGcm9tQXJyYXk6IGZ1bmN0aW9uKCRlbCwgYXJyYXkpIHtcbiAgICAgICAgZm9yICh2YXIgaSA9IGFycmF5Lmxlbmd0aDsgaS0tOykge1xuICAgICAgICAgICAgdmFyIGl0ZW0gPSBhcnJheVtpXTtcbiAgICAgICAgICAgIGlmIChpdGVtLmlzKCRlbCkpIHtcbiAgICAgICAgICAgICAgICBhcnJheS5zcGxpY2UoaSwgMSk7XG4gICAgICAgICAgICAgICAgaXRlbS5kZXN0cm95KHtzaWxlbnQ6IHRydWV9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH0sXG4gICAgX29uSXRlbURlc3Ryb3k6IGZ1bmN0aW9uKGl0ZW0pIHtcbiAgICAgICAgdGhpcy5fcmVtb3ZlSXRlbUZyb21BcnJheShpdGVtLCB0aGlzLnF1ZXVlKTtcbiAgICAgICAgdGhpcy5fcmVtb3ZlSXRlbUZyb21BcnJheShpdGVtLCB0aGlzLnByb2Nlc3NpbmcpO1xuICAgICAgICB0aGlzLl9zY2hlZHVsZUZsdXNoKCk7XG4gICAgfSxcbiAgICBfc2NoZWR1bGVGbHVzaDogZnVuY3Rpb24oKSB7XG4gICAgICAgIGlmICh0aGlzLnRpbWVvdXQpIHJldHVybjtcbiAgICAgICAgdGhpcy50aW1lb3V0ID0gcmVxdWVzdEFuaW1hdGlvbkZyYW1lKHRoaXMuX2ZsdXNoKTtcbiAgICB9LFxuICAgIF91bnNjaGVkdWxlRmx1c2g6IGZ1bmN0aW9uKCkge1xuICAgICAgICBpZiAoIXRoaXMudGltZW91dCkgcmV0dXJuO1xuICAgICAgICBjYW5jZWxBbmltYXRpb25GcmFtZSh0aGlzLnRpbWVvdXQpO1xuICAgICAgICB0aGlzLnRpbWVvdXQgPSBudWxsO1xuICAgIH0sXG59KTtcblxudmFyIHEgPSBuZXcgUXVldWUoKTtcblxubW9kdWxlLmV4cG9ydHMgPSB7XG4gICAgUXVldWU6IFF1ZXVlLFxuICAgIHE6IHFcbn07XG5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL3NyYy9qcy9saWIvZWxlbWVudF9xdWV1ZS5qc1xuLy8gbW9kdWxlIGlkID0gMjJcbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwibW9kdWxlLmV4cG9ydHMgPSBmdW5jdGlvbihvYmopIHtcbm9iaiB8fCAob2JqID0ge30pO1xudmFyIF9fdCwgX19wID0gJycsIF9fZSA9IF8uZXNjYXBlLCBfX2ogPSBBcnJheS5wcm90b3R5cGUuam9pbjtcbmZ1bmN0aW9uIHByaW50KCkgeyBfX3AgKz0gX19qLmNhbGwoYXJndW1lbnRzLCAnJykgfVxud2l0aCAob2JqKSB7XG5fX3AgKz0gJzxkaXYgY2xhc3M9XCJib3ggYm94LXByaW1hcnkgbm8tc2hhZG93XCI+XFxuXFxuICAgIDxmb3JtIGNsYXNzPVwiYm94LWhlYWRlciB3aXRoLWJvcmRlclwiIHN0eWxlPVwiYmFja2dyb3VuZC1jb2xvcjogI2U0ZThlYztcIj5cXG4gICAgICAgICc7XG4gaWYgKGhlYWRlciAhPT0gJzAnKSB7IDtcbl9fcCArPSAnXFxuICAgICAgICAgICAgPGRpdiBjbGFzcz1cInJvd1wiPlxcbiAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiY29sLXhzLTEyIGNvbC1tZC0xMFwiPlxcbiAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cInJvd1wiPlxcbiAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJjb2wteHMtNiBjb2wtc20tMyBjb2wtbWQtMlwiPlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiZm9ybS1ncm91cFwiPlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPicgK1xuX19lKCB0cmFuc2xhdGUoJ1JlcG9ydCBUeXBlJykgKSArXG4nOjwvbGFiZWw+XFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8c2VsZWN0IGNsYXNzPVwiZm9ybS1jb250cm9sXCIgbmFtZT1cInJlcG9ydF90eXBlXCI+XFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgJztcbiBmb3IgKHZhciBpIGluIHJlcG9ydF90eXBlcykgeyA7XG5fX3AgKz0gJ1xcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8b3B0aW9uICc7XG4gaWYgKHJlcG9ydF90eXBlc1tpXS51aWQgPT0gcmVwb3J0X3R5cGUpIHsgO1xuX19wICs9ICdzZWxlY3RlZCc7XG4gfSA7XG5fX3AgKz0gJ1xcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhbHVlPVwiJyArXG5fX2UoIHJlcG9ydF90eXBlc1tpXS51aWQgKSArXG4nXCI+XFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAnICtcbl9fZSggdHJhbnNsYXRlKHJlcG9ydF90eXBlc1tpXS5uYW1lKSApICtcbidcXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9vcHRpb24+XFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgJztcbiB9IDtcbl9fcCArPSAnXFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L3NlbGVjdD5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJmaWVsZC1lcnJvclwiPjwvZGl2PlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cXG4gICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cXG4gICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiY29sLXhzLTYgY29sLXNtLTMgY29sLW1kLTJcIj5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImZvcm0tZ3JvdXBcIj5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD4nICtcbl9fZSggdHJhbnNsYXRlKCdHYW1lIG1vZGUnKSApICtcbic6PC9sYWJlbD5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxzZWxlY3QgY2xhc3M9XCJmb3JtLWNvbnRyb2xcIiBuYW1lPVwibW9kZVwiPlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICc7XG4gZm9yKHZhciBpIGluIGF2YWlsYWJsZV9tb2RlcykgeyA7XG5fX3AgKz0gJ1xcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8b3B0aW9uICc7XG4gaWYgKGF2YWlsYWJsZV9tb2Rlc1tpXS5tb2RlID09IG1vZGUpIHsgO1xuX19wICs9ICdzZWxlY3RlZCc7XG4gfSA7XG5fX3AgKz0gJ1xcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhbHVlPVwiJyArXG5fX2UoIGF2YWlsYWJsZV9tb2Rlc1tpXS5tb2RlICkgK1xuJ1wiPlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgJyArXG5fX2UoIHRyYW5zbGF0ZShhdmFpbGFibGVfbW9kZXNbaV0udGl0bGUpICkgK1xuJ1xcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L29wdGlvbj5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAnO1xuIH0gO1xuX19wICs9ICdcXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvc2VsZWN0PlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImZpZWxkLWVycm9yXCI+PC9kaXY+XFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PlxcbiAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PlxcbiAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJjb2wteHMtMTIgY29sLXNtLTYgY29sLW1kLTJcIj5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImZvcm0tZ3JvdXBcIj5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD4nICtcbl9fZSggdHJhbnNsYXRlKCdHYW1lJykgKSArXG4nOjwvbGFiZWw+XFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8c2VsZWN0IGNsYXNzPVwiZm9ybS1jb250cm9sXCIgbmFtZT1cImdhbWVfaWRcIj5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAnO1xuIGZvcih2YXIgaSBpbiBhdmFpbGFibGVfZ2FtZXMpIHsgO1xuX19wICs9ICdcXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgJztcbiBpZiAoIWF2YWlsYWJsZV9nYW1lc1tpXS53YXNfcGxheWVkKSBjb250aW51ZTsgO1xuX19wICs9ICdcXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPG9wdGlvbiAnO1xuIGlmIChhdmFpbGFibGVfZ2FtZXNbaV0uaWQgPT0gZ2FtZV9pZCkgeyA7XG5fX3AgKz0gJ3NlbGVjdGVkJztcbiB9IDtcbl9fcCArPSAnXFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFsdWU9XCInICtcbl9fZSggYXZhaWxhYmxlX2dhbWVzW2ldLmlkICkgK1xuJ1wiPlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgJyArXG5fX2UoIGF2YWlsYWJsZV9nYW1lc1tpXS5uYW1lICkgK1xuJ1xcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgKCcgK1xuX19lKCB0cmFuc2xhdGUoJ3RpdGxlJywgYXZhaWxhYmxlX2dhbWVzW2ldLmkxOG4pICkgK1xuJylcXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9vcHRpb24+XFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgJztcbiB9IDtcbl9fcCArPSAnXFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L3NlbGVjdD5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJmaWVsZC1lcnJvclwiPjwvZGl2PlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cXG4gICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cXG4gICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiY29sLXhzLTEyIGNvbC1zbS02IGNvbC1tZC0yXCI+XFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJmb3JtLWdyb3VwXCI+XFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGFiZWw+JyArXG5fX2UoIHRyYW5zbGF0ZSgnUm91bmQnKSApICtcbic6PC9sYWJlbD5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCB0eXBlPVwidGV4dFwiIGNsYXNzPVwiZm9ybS1jb250cm9sXCIgbmFtZT1cInJvdW5kX2lkXCIgdmFsdWU9XCInICtcbl9fZSggcm91bmRfaWQgKSArXG4nXCI+XFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiZmllbGQtZXJyb3JcIj48L2Rpdj5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+XFxuICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+XFxuICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImNvbC14cy0xMiBjb2wtc20tNiBjb2wtbWQtNFwiPlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiZm9ybS1ncm91cFwiPlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPicgK1xuX19lKCB0cmFuc2xhdGUoJ0RhdGUgUmFuZ2UnKSApICtcbic6PC9sYWJlbD5cXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCB0eXBlPVwidGV4dFwiIGNsYXNzPVwiZm9ybS1jb250cm9sXCIgaWQ9XCJnYXBpY2tlclwiIGF1dG9jb21wbGV0ZT1cIm9mZlwiPlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImZpZWxkLWVycm9yXCI+PC9kaXY+XFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PlxcbiAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PlxcbiAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJjb2wteHMtMTIgY29sLXNtLTEyIHZpc2libGUtc20tYmxvY2sgdmlzaWJsZS14cy1ibG9ja1wiPlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiYm94LXN1Ym1pdFwiPlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImJ0biBidG4td2FybmluZyBidG4tYmxvY2sgc3VibWl0LWJ0blwiPlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpIGNsYXNzPVwiZmEgZmEtc2VhcmNoXCI+PC9pPiAnICtcbl9fZSggdHJhbnNsYXRlKCdTZWFyY2gnKSApICtcbidcXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cXG4gICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cXG4gICAgICAgICAgICAgICAgICAgIDwvZGl2PlxcbiAgICAgICAgICAgICAgICA8L2Rpdj5cXG4gICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImhpZGRlbi14cyBoaWRkZW4tc20gY29sLW1kLTJcIj5cXG4gICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJyb3dcIj5cXG4gICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiY29sLXhzLTEyXCI+XFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJib3gtc3VibWl0XCI+XFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiYnRuIGJ0bi13YXJuaW5nIGJ0bi1ibG9jayBzdWJtaXQtYnRuXCI+XFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGkgY2xhc3M9XCJmYSBmYS1zZWFyY2hcIj48L2k+ICcgK1xuX19lKCB0cmFuc2xhdGUoJ1NlYXJjaCcpICkgK1xuJ1xcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+XFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PlxcbiAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PlxcbiAgICAgICAgICAgICAgICAgICAgPC9kaXY+XFxuICAgICAgICAgICAgICAgIDwvZGl2PlxcbiAgICAgICAgICAgIDwvZGl2PlxcbiAgICAgICAgJztcbiB9IDtcbl9fcCArPSAnXFxuXFxuICAgICAgICA8aDQgY2xhc3M9XCJob3Jpem9udGFsLWRpdmlkZXJcIj5cXG4gICAgICAgICAgICA8aSBjbGFzcz1cImZhIGZhLWJhci1jaGFydFwiPjwvaT4gJyArXG5fX2UoIHRyYW5zbGF0ZSgnVHJhbnNhY3Rpb24gUmVzdWx0cycpICkgK1xuJ1xcbiAgICAgICAgPC9oND5cXG5cXG4gICAgICAgIDxhIGNsYXNzPVwic2VjdGlvbiBnb2JhY2tcIiB0aXRsZT1cImdvIGJhY2tcIj5cXG4gICAgICAgICAgICA8aSBjbGFzcz1cImZhIGZhLWJhY2t3YXJkXCI+PC9pPiAnICtcbl9fZSggdHJhbnNsYXRlKCdwbGF5ZXIgZ2FtZXMnKSApICtcbidcXG4gICAgICAgIDwvYT5cXG5cXG4gICAgPC9mb3JtPlxcblxcbiAgICAnO1xuIGlmIChzaG93UGxheWVySW5mbykgeyA7XG5fX3AgKz0gJ1xcbiAgICAgICAgPGRpdiBjbGFzcz1cImJveC1ib2R5IGFwcC1wbGF5ZXItaW5mby13aWRnZXRcIiBzdHlsZT1cInBhZGRpbmc6IDIwcHggMCAwO1wiPlxcbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJib3gtaGVhZGVyIHdpdGgtYm9yZGVyXCIgc3R5bGU9XCJiYWNrZ3JvdW5kLWNvbG9yOiAjZTRlOGVjO1wiPlxcbiAgICAgICAgICAgICAgICA8bGFiZWwgc3R5bGU9XCJtYXJnaW46IDA7XCI+JyArXG5fX2UoIHRyYW5zbGF0ZSgnSW5mbycpICkgK1xuJzwvbGFiZWw+XFxuICAgICAgICAgICAgPC9kaXY+XFxuICAgICAgICAgICAgPGRpdiBjbGFzcz1cImJveC1ib2R5IGFwcC1wbGF5ZXItaW5mby1yZWdpb25cIj5cXG4gICAgICAgICAgICA8L2Rpdj5cXG4gICAgICAgIDwvZGl2PlxcbiAgICAnO1xuIH0gO1xuX19wICs9ICdcXG5cXG4gICAgPGRpdiBjbGFzcz1cImJveC1ib2R5IGFwcC10b3RhbHMtd2lkZ2V0XCIgc3R5bGU9XCJwYWRkaW5nOiAyMHB4IDAgMDtcIj5cXG4gICAgICAgIDxkaXYgY2xhc3M9XCJib3gtaGVhZGVyIHdpdGgtYm9yZGVyXCIgc3R5bGU9XCJiYWNrZ3JvdW5kLWNvbG9yOiAjZTRlOGVjO1wiPlxcbiAgICAgICAgICAgIDxsYWJlbCBzdHlsZT1cIm1hcmdpbjogMDtcIj4nICtcbl9fZSggdHJhbnNsYXRlKCdUb3RhbCcpICkgK1xuJzwvbGFiZWw+XFxuICAgICAgICA8L2Rpdj5cXG4gICAgICAgIDxkaXYgY2xhc3M9XCJib3gtYm9keSBhcHAtdG90YWxzLXJlZ2lvblwiPlxcbiAgICAgICAgPC9kaXY+XFxuICAgIDwvZGl2PlxcblxcbiAgICA8ZGl2IGNsYXNzPVwiYm94LWJvZHkgYXBwLWxpc3QtcmVnaW9uXCIgc3R5bGU9XCJwYWRkaW5nOiAyMHB4IDA7XCI+XFxuICAgIDwvZGl2PlxcbiAgICA8ZGl2IGNsYXNzPVwib3ZlcmxheVwiIHN0eWxlPVwiZGlzcGxheTogbm9uZTtcIj5cXG4gICAgICAgIDxpIGNsYXNzPVwiZmEgZmEtcmVmcmVzaCBmYS1zcGluXCI+PC9pPlxcbiAgICA8L2Rpdj5cXG48L2Rpdj5cXG4nO1xuXG59XG5yZXR1cm4gX19wXG59O1xuXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvanMvdmlld3MvdHJhbnNhY3Rpb25zL3ZpZXcuZWpzXG4vLyBtb2R1bGUgaWQgPSAyM1xuLy8gbW9kdWxlIGNodW5rcyA9IDAiLCJ2YXIgTW9kZWxzID0gcmVxdWlyZSgnYXBwL21vZGVscycpO1xudmFyIGRlZmF1bHRzID0gcmVxdWlyZSgnYXBwL2RlZmF1bHRzJyk7XG52YXIgRm9ybVV0aWxzID0gcmVxdWlyZSgnbGliL2Zvcm0nKTtcbnZhciBsb2NhbGVzID0gcmVxdWlyZSgnbGliL2xvY2FsZXMnKTtcbnZhciBWaWV3ID0gcmVxdWlyZSgnbGliL3ZpZXcnKTtcblxudmFyIFJvdW5kc0dyaWRWaWV3ID0gcmVxdWlyZSgnLi9ncmlkJyk7XG52YXIgdHBsID0gcmVxdWlyZSgnLi92aWV3LmVqcycpO1xudmFyIFRvdGFsc0dyaWRWaWV3ID0gcmVxdWlyZSgndmlld3MvdG90YWxzL3ZpZXcnKTtcbnZhciBQbGF5ZXJJbmZvR3JpZFZpZXcgPSByZXF1aXJlKCd2aWV3cy9wbGF5ZXJfaW5mby92aWV3Jyk7XG5cblxudmFyIFJvdW5kVmlldyA9IEJhY2tib25lLk1hcmlvbmV0dGUuVmlldy5leHRlbmQoe1xuICAgIG9wdGlvbnM6IHtcbiAgICAgICAgdHo6IGRlZmF1bHRzLnR6LFxuICAgICAgICByb3VuZF9pZDogZGVmYXVsdHMucm91bmRfaWQsXG4gICAgICAgIGhlYWRlcjogZGVmYXVsdHMuaGVhZGVyLFxuICAgICAgICB0b3RhbHM6IGRlZmF1bHRzLnRvdGFscyxcbiAgICAgICAgaW5mbzogZGVmYXVsdHMuaW5mbyxcbiAgICAgICAgYmFsYW5jZTogZGVmYXVsdHMuYmFsYW5jZSxcbiAgICAgICAgbGFuZzogZGVmYXVsdHMubGFuZ1xuICAgIH0sXG4gICAgdGVtcGxhdGU6IHRwbCxcbiAgICB0ZW1wbGF0ZUNvbnRleHQ6IGZ1bmN0aW9uKCkge1xuICAgICAgICByZXR1cm4ge1xuICAgICAgICAgICAgaGVhZGVyOiB0aGlzLmdldE9wdGlvbignaGVhZGVyJyksXG4gICAgICAgICAgICB0b3RhbHM6IHRoaXMuZ2V0T3B0aW9uKCd0b3RhbHMnKSxcbiAgICAgICAgICAgIHNob3dQbGF5ZXJJbmZvOiB0aGlzLnNob3dQbGF5ZXJJbmZvLFxuICAgICAgICAgICAgcm91bmRfaWQ6IHRoaXMuZ2V0T3B0aW9uKCdyb3VuZF9pZCcpLFxuICAgICAgICAgICAgdHJhbnNsYXRlOiBsb2NhbGVzLnRyYW5zbGF0ZS5iaW5kKGxvY2FsZXMpXG4gICAgICAgIH1cbiAgICB9LFxuICAgIHVpOiB7XG4gICAgICAgIGxpc3RSZWdpb246ICcuYXBwLWxpc3QtcmVnaW9uJyxcbiAgICAgICAgdG90YWxzUmVnaW9uOiAnLmFwcC10b3RhbHMtcmVnaW9uJyxcbiAgICAgICAgdG90YWxzV2lkZ2V0OiAnLmFwcC10b3RhbHMtd2lkZ2V0JyxcbiAgICAgICAgcGxheWVySW5mb1JlZ2lvbjogJy5hcHAtcGxheWVyLWluZm8tcmVnaW9uJyxcbiAgICAgICAgb3ZlcmxheTogJy5vdmVybGF5J1xuICAgIH0sXG4gICAgcmVnaW9uczoge1xuICAgICAgICBsaXN0UmVnaW9uOiAnQHVpLmxpc3RSZWdpb24nLFxuICAgICAgICB0b3RhbHNSZWdpb246ICdAdWkudG90YWxzUmVnaW9uJyxcbiAgICAgICAgcGxheWVySW5mb1JlZ2lvbjogJ0B1aS5wbGF5ZXJJbmZvUmVnaW9uJ1xuICAgIH0sXG4gICAgY29sbGVjdGlvbkV2ZW50czoge1xuICAgICAgICByZXF1ZXN0OiAnb25Db2xsZWN0aW9uUmVxdWVzdCcsXG4gICAgICAgIHN5bmM6ICdvbkNvbGxlY3Rpb25TeW5jJyxcbiAgICAgICAgZXJyb3I6ICdvbkNvbGxlY3Rpb25FcnJvcidcbiAgICB9LFxuXG4gICAgYmVoYXZpb3JzOiBbe1xuICAgICAgICBiZWhhdmlvckNsYXNzOiBGb3JtVXRpbHMuRm9ybUJlaGF2aW9yLFxuICAgICAgICBzdWJtaXRFdmVudDogJ3NlYXJjaDpmb3JtOnN1Ym1pdCdcbiAgICB9LCB7XG4gICAgICAgIGJlaGF2aW9yQ2xhc3M6IFZpZXcuTmVzdGVkVmlld0JlaGF2aW9yXG4gICAgfV0sXG5cbiAgICBpbml0aWFsaXplOiBmdW5jdGlvbigpIHtcbiAgICAgICAgbG9jYWxlcy5zZXQodGhpcy5vcHRpb25zLmxhbmcpO1xuICAgICAgICB0aGlzLm9wdGlvbnMudHogPSBwYXJzZUludCh0aGlzLm9wdGlvbnMudHopO1xuICAgICAgICBpZiAoXy5pc05hTih0aGlzLm9wdGlvbnMudHopKSB7XG4gICAgICAgICAgICB0aGlzLm9wdGlvbnMudHogPSBkZWZhdWx0cy50ejtcbiAgICAgICAgfVxuXG4gICAgICAgIHRoaXMuY29sbGVjdGlvbiA9IG5ldyBNb2RlbHMuUm91bmRUcmFuc2FjdGlvbkNvbGxlY3Rpb24oW10sIHtcbiAgICAgICAgICAgIHF1ZXJ5UGFyYW1zOiB7XG4gICAgICAgICAgICAgICAgcm91bmRfaWQ6IF8uYmluZCh0aGlzLmdldE9wdGlvbiwgdGhpcywgJ3JvdW5kX2lkJyksXG4gICAgICAgICAgICAgICAgdHo6IF8uYmluZCh0aGlzLmdldE9wdGlvbiwgdGhpcywgJ3R6JyksXG4gICAgICAgICAgICAgICAgLy8gZG9uJ3Qgc2VuZCBwbGF5ZXJfaWRcbiAgICAgICAgICAgICAgICBwbGF5ZXJfaWQ6IHVuZGVmaW5lZFxuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIGNvbXBhcmF0b3I6ICdjX2F0J1xuICAgICAgICB9KTtcbiAgICAgICAgLy8gdG90YWxzIHJlcXVpcmUgJ2JldHMnXG4gICAgICAgIHRoaXMudG90YWxzQ29sbGVjdGlvbiA9IG5ldyBNb2RlbHMuUm91bmRUcmFuc2FjdGlvblRvdGFsc0NvbGxlY3Rpb24oW10sIHtcbiAgICAgICAgICAgIHNvdXJjZTogdGhpcy5jb2xsZWN0aW9uXG4gICAgICAgIH0pO1xuICAgICAgICAvLyBmb290ZXIgcmVxdWlyZXMgJ2JldCdcbiAgICAgICAgdGhpcy5mb290ZXJUb3RhbHNDb2xsZWN0aW9uID0gbmV3IE1vZGVscy5UcmFuc2FjdGlvblRvdGFsc0NvbGxlY3Rpb24oW10sIHtcbiAgICAgICAgICAgIHNvdXJjZTogdGhpcy5jb2xsZWN0aW9uXG4gICAgICAgIH0pO1xuXG4gICAgICAgIHRoaXMuc2hvd1BsYXllckluZm8gPSB0aGlzLmdldE9wdGlvbignaW5mbycpICE9PSAnMCc7XG4gICAgICAgIGlmICh0aGlzLnNob3dQbGF5ZXJJbmZvKSB7XG4gICAgICAgICAgICB0aGlzLnBsYXllckluZm9Nb2RlbCA9IG5ldyBNb2RlbHMuUGxheWVySW5mb01vZGVsKCk7XG4gICAgICAgIH1cbiAgICB9LFxuICAgIHJlbmRlckdyaWQ6IGZ1bmN0aW9uKCkge1xuICAgICAgICBpZiAoIXRoaXMuZ2V0Q2hpbGRWaWV3KCdsaXN0UmVnaW9uJykpIHtcbiAgICAgICAgICAgIHZhciBncmlkVmlldyA9IG5ldyBSb3VuZHNHcmlkVmlldyh7XG4gICAgICAgICAgICAgICAgY29sbGVjdGlvbjogdGhpcy5jb2xsZWN0aW9uLFxuICAgICAgICAgICAgICAgIGZvb3RlckNvbGxlY3Rpb246IHRoaXMuZm9vdGVyVG90YWxzQ29sbGVjdGlvblxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB0aGlzLnNob3dDaGlsZFZpZXcoJ2xpc3RSZWdpb24nLCBncmlkVmlldyk7XG4gICAgICAgIH1cbiAgICAgICAgdGhpcy5jb2xsZWN0aW9uLmZldGNoKCk7XG4gICAgfSxcblxuICAgIHJlbmRlclRvdGFsc0dyaWQ6IGZ1bmN0aW9uKCkge1xuICAgICAgICBpZiAoIXRoaXMuZ2V0Q2hpbGRWaWV3KCd0b3RhbHNSZWdpb24nKSkge1xuICAgICAgICAgICAgdmFyIGdyaWRWaWV3ID0gbmV3IFRvdGFsc0dyaWRWaWV3KHtcbiAgICAgICAgICAgICAgICBjb2xsZWN0aW9uOiB0aGlzLnRvdGFsc0NvbGxlY3Rpb25cbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgdGhpcy5zaG93Q2hpbGRWaWV3KCd0b3RhbHNSZWdpb24nLCBncmlkVmlldyk7XG4gICAgICAgIH1cbiAgICB9LFxuXG4gICAgcmVuZGVyUGxheWVySW5mbzogZnVuY3Rpb24oKSB7XG4gICAgICAgIGlmICghdGhpcy5nZXRDaGlsZFZpZXcoJ3BsYXllckluZm9SZWdpb24nKSkge1xuICAgICAgICAgICAgdmFyIGdyaWRWaWV3ID0gbmV3IFBsYXllckluZm9HcmlkVmlldyh7XG4gICAgICAgICAgICAgICAgY29sbGVjdGlvbjogbmV3IEJhY2tib25lLkNvbGxlY3Rpb24oW1xuICAgICAgICAgICAgICAgICAgICB0aGlzLnBsYXllckluZm9Nb2RlbFxuICAgICAgICAgICAgICAgIF0pXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHRoaXMuc2hvd0NoaWxkVmlldygncGxheWVySW5mb1JlZ2lvbicsIGdyaWRWaWV3KTtcbiAgICAgICAgfVxuICAgICAgICB0aGlzLmZldGNoUGxheWVySW5mbygpO1xuICAgIH0sXG5cbiAgICBmZXRjaFBsYXllckluZm86IGZ1bmN0aW9uKCkge1xuICAgICAgICB0aGlzLnBsYXllckluZm9Nb2RlbC5mZXRjaCh7XG4gICAgICAgICAgICBkYXRhOiB7XG4gICAgICAgICAgICAgICAgcm91bmRfaWQ6IHRoaXMuZ2V0T3B0aW9uKCdyb3VuZF9pZCcpXG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH0sXG5cbiAgICBvblJlbmRlcjogZnVuY3Rpb24oKSB7XG4gICAgICAgIHRoaXMucmVuZGVyR3JpZCgpO1xuICAgICAgICBpZiAodGhpcy5nZXRPcHRpb24oJ3RvdGFscycpID09PSAnMCcpIHtcbiAgICAgICAgICAgIHRoaXMudWkudG90YWxzV2lkZ2V0LmhpZGUoKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIHRoaXMucmVuZGVyVG90YWxzR3JpZCgpO1xuICAgICAgICAgICAgdGhpcy51aS50b3RhbHNXaWRnZXQuc2hvdygpO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKHRoaXMuc2hvd1BsYXllckluZm8pIHtcbiAgICAgICAgICAgIHRoaXMucmVuZGVyUGxheWVySW5mbygpO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKHRoaXMuZ2V0T3B0aW9uKCdiYWxhbmNlJykgPT09ICcwJykge1xuICAgICAgICAgICAgdGhpcy5nZXRDaGlsZFZpZXcoJ2xpc3RSZWdpb24nKS5oaWRlQmFsYW5jZSgpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgdGhpcy5nZXRDaGlsZFZpZXcoJ2xpc3RSZWdpb24nKS5zaG93QmFsYW5jZSgpO1xuICAgICAgICB9XG4gICAgfSxcbiAgICBvbkNvbGxlY3Rpb25TeW5jOiBmdW5jdGlvbigpIHtcbiAgICAgICAgdGhpcy51aS5vdmVybGF5LmhpZGUoKTtcbiAgICB9LFxuICAgIG9uQ29sbGVjdGlvbkVycm9yOiBmdW5jdGlvbigpIHtcbiAgICAgICAgdGhpcy51aS5vdmVybGF5LmhpZGUoKTtcbiAgICB9LFxuICAgIG9uQ29sbGVjdGlvblJlcXVlc3Q6IGZ1bmN0aW9uKCkge1xuICAgICAgICB0aGlzLnVpLm92ZXJsYXkuc2hvdygpO1xuICAgICAgICB0aGlzLnRyaWdnZXJNZXRob2QoJ3N0YXRlOmNoYW5nZWQnLCB7XG4gICAgICAgICAgICBzaG93OiAncm91bmQnLFxuICAgICAgICAgICAgdHo6IHRoaXMub3B0aW9ucy50eixcbiAgICAgICAgICAgIHJvdW5kX2lkOiB0aGlzLm9wdGlvbnMucm91bmRfaWQsXG4gICAgICAgICAgICBoZWFkZXI6IHRoaXMub3B0aW9ucy5oZWFkZXIsXG4gICAgICAgICAgICB0b3RhbHM6IHRoaXMub3B0aW9ucy50b3RhbHMsXG4gICAgICAgICAgICBpbmZvOiB0aGlzLm9wdGlvbnMuaW5mbyxcbiAgICAgICAgICAgIGJhbGFuY2U6IHRoaXMub3B0aW9ucy5iYWxhbmNlLFxuICAgICAgICAgICAgbGFuZzogdGhpcy5vcHRpb25zLmxhbmdcbiAgICAgICAgfSk7XG4gICAgfSxcblxuICAgIG9uU2VhcmNoRm9ybVN1Ym1pdDogZnVuY3Rpb24oZGF0YSkge1xuICAgICAgICBfLmV4dGVuZCh0aGlzLm9wdGlvbnMsIGRhdGEpO1xuICAgICAgICB0aGlzLmNvbGxlY3Rpb24uZmV0Y2goKTtcbiAgICAgICAgaWYgKHRoaXMuc2hvd1BsYXllckluZm8pIHtcbiAgICAgICAgICAgIHRoaXMuZmV0Y2hQbGF5ZXJJbmZvKCk7XG4gICAgICAgIH1cbiAgICB9XG59KTtcblxubW9kdWxlLmV4cG9ydHMgPSBSb3VuZFZpZXc7XG5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL3NyYy9qcy92aWV3cy9yb3VuZC92aWV3LmpzXG4vLyBtb2R1bGUgaWQgPSAyNFxuLy8gbW9kdWxlIGNodW5rcyA9IDAiLCJ2YXIgR3JpZFV0aWxzID0gcmVxdWlyZSgnbGliL2dyaWQnKTtcbnZhciBHcmlkVXRpbHNDdXN0b20gPSByZXF1aXJlKCdsaWIvZ3JpZF9jdXN0b20nKTtcbnZhciBTdG9yYWdlID0gcmVxdWlyZSgnYXBwL3N0b3JhZ2UnKTtcbnZhciBsb2NhbGVzID0gcmVxdWlyZSgnbGliL2xvY2FsZXMnKTtcblxudmFyIFJvdW5kc0dyaWRWaWV3ID0gR3JpZFV0aWxzLkZpeGVkSGVhZGVyQ2VsbE11bHRpbGluZUZvb3Rlci5leHRlbmQoe1xuICAgIGNoaWxkVmlld0V2ZW50czogXy5leHRlbmQoe30sIEdyaWRVdGlscy5GaXhlZEhlYWRlckNlbGxNdWx0aWxpbmVGb290ZXIucHJvdG90eXBlLmNoaWxkVmlld0V2ZW50cywge1xuICAgICAgICAnaGVhZGVyOmV4cGFuZF9hbGwnOiAnb25IZWFkZXJFeHBhbmQnLFxuICAgICAgICAnaGVhZGVyOmNvbGxhcHNlX2FsbCc6ICdvbkhlYWRlckNvbGxhcHNlJyxcblxuICAgICAgICAncm93OmNlbGw6em9vbSc6ICdpbnZhbGlkYXRlSGVhZGVyJyxcbiAgICAgICAgJ3JvdzpjZWxsOnRvZ2dsZSc6ICdpbnZhbGlkYXRlSGVhZGVyJyxcbiAgICAgICAgJ2hlYWRlcjp0b2dnbGUnOiAnaW52YWxpZGF0ZUhlYWRlcicsXG4gICAgfSksXG5cbiAgICBvbkhlYWRlckV4cGFuZDogZnVuY3Rpb24oKSB7XG4gICAgICAgIHRoaXMuZ2V0Q2hpbGRWaWV3KCdib2R5JykuY2hpbGRyZW4uZWFjaChmdW5jdGlvbih2aWV3KSB7XG4gICAgICAgICAgICB2aWV3LmV4cGFuZCAmJiB2aWV3LmV4cGFuZCgpO1xuICAgICAgICB9KTtcbiAgICB9LFxuXG4gICAgb25IZWFkZXJDb2xsYXBzZTogZnVuY3Rpb24oKSB7XG4gICAgICAgIHRoaXMuZ2V0Q2hpbGRWaWV3KCdib2R5JykuY2hpbGRyZW4uZWFjaChmdW5jdGlvbih2aWV3KSB7XG4gICAgICAgICAgICB2aWV3LmNvbGxhcHNlICYmIHZpZXcuY29sbGFwc2UoKTtcbiAgICAgICAgfSk7XG4gICAgfSxcblxuICAgIGRhdGFSb3dWaWV3OiBNYUdyaWQuRGF0YVJvd1ZpZXcuZXh0ZW5kKHtcbiAgICAgICAgZXhwYW5kOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIF8uZWFjaCh0aGlzLmdldFJlZ2lvbnMoKSwgZnVuY3Rpb24ocmVnaW9uKSB7XG4gICAgICAgICAgICAgICAgdmFyIHZpZXcgPSByZWdpb24uY3VycmVudFZpZXc7XG4gICAgICAgICAgICAgICAgdmlldyAmJiB2aWV3LmV4cGFuZCAmJiB2aWV3LmV4cGFuZCgpO1xuICAgICAgICAgICAgfSwgdGhpcyk7XG4gICAgICAgIH0sXG4gICAgICAgIGNvbGxhcHNlOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIF8uZWFjaCh0aGlzLmdldFJlZ2lvbnMoKSwgZnVuY3Rpb24ocmVnaW9uKSB7XG4gICAgICAgICAgICAgICAgdmFyIHZpZXcgPSByZWdpb24uY3VycmVudFZpZXc7XG4gICAgICAgICAgICAgICAgdmlldyAmJiB2aWV3LmNvbGxhcHNlICYmIHZpZXcuY29sbGFwc2UoKTtcbiAgICAgICAgICAgIH0sIHRoaXMpO1xuICAgICAgICB9XG4gICAgfSksXG5cbiAgICBjb2x1bW5zOiBbe1xuICAgICAgICBoZWFkZXI6ICdJRCcsXG4gICAgICAgIGF0dHI6ICd0cmFuc2FjdGlvbl9pZCcsXG4gICAgICAgIGNsYXNzTmFtZTogJ3VpZC1jZWxsJyxcbiAgICAgICAgY2VsbDogR3JpZFV0aWxzLkN1c3RvbUNlbGwuZXh0ZW5kKHtcbiAgICAgICAgICAgIGNsYXNzTmFtZTogJ3NtLWZsb2F0LWxlZnQnLFxuICAgICAgICAgICAgdGVtcGxhdGU6IF8udGVtcGxhdGUoW1xuICAgICAgICAgICAgICAgICc8c3BhbiBjbGFzcz1cInZpc2libGUteHMtaW5saW5lIHRleHQtbm93cmFwXCI+PGI+VUlEOiA8L2I+PCU9IHZhbCAlPjwvc3Bhbj4nLFxuICAgICAgICAgICAgICAgICc8ZGl2IGNsYXNzPVwiaGlkZGVuLXhzIGJ0bi14cyBidG4tZGVmYXVsdCBiYWRnZVwiPjxpIGNsYXNzPVwiZmEgZmEtaW5mb1wiPjwvaT48L2E+J1xuICAgICAgICAgICAgXS5qb2luKCcnKSksXG4gICAgICAgICAgICBvblJlbmRlcjogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgdGhpcy4kKCdkaXYnKS5wb3BvdmVyKHtcbiAgICAgICAgICAgICAgICAgICAgdHJpZ2dlcjogJ2NsaWNrJyxcbiAgICAgICAgICAgICAgICAgICAgcGxhY2VtZW50OiAncmlnaHQnLFxuICAgICAgICAgICAgICAgICAgICBodG1sOiB0cnVlLFxuICAgICAgICAgICAgICAgICAgICBjb250ZW50OiAoXy50ZW1wbGF0ZShbXG4gICAgICAgICAgICAgICAgICAgICAgICAnPCUtIHRyYW5zYWN0aW9uX2lkICU+J1xuICAgICAgICAgICAgICAgICAgICBdLmpvaW4oJycpKSkodGhpcy5tb2RlbC50b0pTT04oKSlcbiAgICAgICAgICAgICAgICB9KVxuICAgICAgICAgICAgfVxuICAgICAgICB9KVxuICAgIH0sIHtcbiAgICAgICAgYXR0cjogJ2dhbWVfaWQnLFxuICAgICAgICBoZWFkZXI6IE1hcmlvbmV0dGUuVmlldy5leHRlbmQoe1xuICAgICAgICAgICAgdGVtcGxhdGU6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgIHJldHVybiBsb2NhbGVzLnRyYW5zbGF0ZSgnR2FtZScpXG4gICAgICAgICAgICB9XG4gICAgICAgIH0pLFxuICAgICAgICBzb3J0YWJsZTogZmFsc2UsXG4gICAgICAgIGNlbGw6IEdyaWRVdGlscy5Nb2JpbGVDZWxsLmV4dGVuZCh7XG4gICAgICAgICAgICBkaXNwbGF5TmFtZTogbG9jYWxlcy50cmFuc2xhdGUoJ0dhbWUnKSxcbiAgICAgICAgICAgIGNsYXNzTmFtZTogJ3NtLWZsb2F0LWxlZnQnLFxuICAgICAgICAgICAgdGVtcGxhdGVDb250ZXh0OiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgdmFyIGNvbnRleHQgPSBHcmlkVXRpbHMuTW9iaWxlQ2VsbC5wcm90b3R5cGUudGVtcGxhdGVDb250ZXh0LmFwcGx5KHRoaXMsIGFyZ3VtZW50cyk7XG5cbiAgICAgICAgICAgICAgICB2YXIgZ2FtZUlEID0gdGhpcy5nZXRBdHRyVmFsdWUoKTtcbiAgICAgICAgICAgICAgICB2YXIgZ2FtZUluZm8gPSBTdG9yYWdlLmF2YWlsYWJsZUdhbWVzLmZpbmRXaGVyZSh7XG4gICAgICAgICAgICAgICAgICAgIGlkOiBnYW1lSURcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB2YXIgZnVsbE5hbWU7XG4gICAgICAgICAgICAgICAgaWYgKGdhbWVJbmZvKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciBsb2NhbGl6ZWROYW1lID0gbG9jYWxlcy50cmFuc2xhdGUoJ3RpdGxlJywgZ2FtZUluZm8uZ2V0KCdpMThuJykpO1xuICAgICAgICAgICAgICAgICAgICB2YXIgbmFtZSA9IGdhbWVJbmZvLmdldCgnbmFtZScpO1xuICAgICAgICAgICAgICAgICAgICBmdWxsTmFtZSA9IG5hbWUgKyAnICgnICsgbG9jYWxpemVkTmFtZSArICcpJztcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICBmdWxsTmFtZSA9IHRoaXMubW9kZWwuZ2V0KCdnYW1lX25hbWUnKSB8fCBnYW1lSUQgfHwgJyc7XG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgY29udGV4dFsndmFsJ10gPSBmdWxsTmFtZTtcbiAgICAgICAgICAgICAgICByZXR1cm4gY29udGV4dDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSlcbiAgICB9LCB7XG4gICAgICAgIGhlYWRlcjogTWFyaW9uZXR0ZS5WaWV3LmV4dGVuZCh7XG4gICAgICAgICAgICB0ZW1wbGF0ZTogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGxvY2FsZXMudHJhbnNsYXRlKCdEYXRlJylcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSksXG4gICAgICAgIGF0dHI6ICdjX2F0JyxcbiAgICAgICAgc29ydGFibGU6IGZhbHNlLFxuICAgICAgICBjZWxsOiBHcmlkVXRpbHMuTW9iaWxlQ2VsbC5leHRlbmQoe1xuICAgICAgICAgICAgZGlzcGxheU5hbWU6IGxvY2FsZXMudHJhbnNsYXRlKCdEYXRlJyksXG4gICAgICAgICAgICBjbGFzc05hbWU6ICdzbS1mbG9hdC1sZWZ0JyxcbiAgICAgICAgICAgIGRhdGVGb3JtYXQ6ICdZWVlZLU1NLUREWyZuYnNwO11ISDptbTpzcycsXG4gICAgICAgICAgICB0ZW1wbGF0ZUNvbnRleHQ6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgIHZhciBjb250ZXh0ID0gR3JpZFV0aWxzLk1vYmlsZUNlbGwucHJvdG90eXBlLnRlbXBsYXRlQ29udGV4dC5hcHBseSh0aGlzLCBhcmd1bWVudHMpO1xuICAgICAgICAgICAgICAgIHZhciB2YWwgPSB0aGlzLmdldEF0dHJWYWx1ZSgpLFxuICAgICAgICAgICAgICAgICAgICB0eiA9IHBhcnNlSW50KHRoaXMubW9kZWwuY29sbGVjdGlvbi5sYXN0RmlsdGVycy50eiksXG4gICAgICAgICAgICAgICAgICAgIGZvcm1hdCA9IHRoaXMuZ2V0T3B0aW9uKCdkYXRlRm9ybWF0JyksXG4gICAgICAgICAgICAgICAgICAgIGR0ID0gbW9tZW50KHZhbCkudXRjKCkudXRjT2Zmc2V0KHR6KTtcbiAgICAgICAgICAgICAgICBjb250ZXh0Wyd2YWwnXSA9IGR0ID8gZHQuZm9ybWF0KGZvcm1hdCkucmVwbGFjZSgnICcsICcmbmJzcDsnKSA6ICcmbWRhc2g7JztcbiAgICAgICAgICAgICAgICByZXR1cm4gY29udGV4dDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSlcbiAgICB9LCB7XG4gICAgICAgIGF0dHI6ICdjdXJyZW5jeScsXG4gICAgICAgIGhlYWRlcjogTWFyaW9uZXR0ZS5WaWV3LmV4dGVuZCh7XG4gICAgICAgICAgICB0ZW1wbGF0ZTogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGxvY2FsZXMudHJhbnNsYXRlKCdDdXJyZW5jeScpXG4gICAgICAgICAgICB9XG4gICAgICAgIH0pLFxuICAgICAgICBzb3J0YWJsZTogZmFsc2UsXG4gICAgICAgIGNsYXNzTmFtZTogJ3RleHQtY2VudGVyJyxcbiAgICAgICAgY2VsbDogR3JpZFV0aWxzLk1vYmlsZUNlbGwuZXh0ZW5kKHtcbiAgICAgICAgICAgIHRlbXBsYXRlOiBfLnRlbXBsYXRlKFtcbiAgICAgICAgICAgICAgICAnPHNwYW4gY2xhc3M9XCJoaWRkZW4teHNcIj48JT0gdmFsICU+PC9zcGFuPidcbiAgICAgICAgICAgIF0uam9pbignJykpXG4gICAgICAgIH0pXG4gICAgfSwge1xuICAgICAgICBzb3J0YWJsZTogZmFsc2UsXG4gICAgICAgIGF0dHI6ICdiYWxhbmNlX2JlZm9yZScsXG4gICAgICAgIGhlYWRlcjogTWFyaW9uZXR0ZS5WaWV3LmV4dGVuZCh7XG4gICAgICAgICAgICB0ZW1wbGF0ZTogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGxvY2FsZXMudHJhbnNsYXRlKCdCYWwuIEJlZm9yZScpXG4gICAgICAgICAgICB9XG4gICAgICAgIH0pLFxuICAgICAgICBjbGFzc05hbWU6ICd0ZXh0LXJpZ2h0JyxcbiAgICAgICAgY2VsbDogR3JpZFV0aWxzLk1vYmlsZUNlbGwuZXh0ZW5kKHtcbiAgICAgICAgICAgIGNsYXNzTmFtZTogJ3NtLWZsb2F0LWxlZnQgdGV4dC1yaWdodCcsXG4gICAgICAgICAgICBzaG93Q3VycmVuY3k6IHRydWUsXG4gICAgICAgICAgICBkaXNwbGF5TmFtZTogbG9jYWxlcy50cmFuc2xhdGUoJ0JhbC4gQmVmb3JlJyksXG4gICAgICAgIH0pXG4gICAgfSwge1xuICAgICAgICBzb3J0YWJsZTogZmFsc2UsXG4gICAgICAgIGF0dHI6ICdiZXQnLFxuICAgICAgICBoZWFkZXI6IE1hcmlvbmV0dGUuVmlldy5leHRlbmQoe1xuICAgICAgICAgICAgdGVtcGxhdGU6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgIHJldHVybiBsb2NhbGVzLnRyYW5zbGF0ZSgnQmV0JylcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSksXG4gICAgICAgIGNsYXNzTmFtZTogJ3RleHQtcmlnaHQnLFxuICAgICAgICBjZWxsOiBHcmlkVXRpbHMuTW9iaWxlQ2VsbC5leHRlbmQoe1xuICAgICAgICAgICAgY2xhc3NOYW1lOiAnc20tZmxvYXQtbGVmdCB0ZXh0LXJpZ2h0JyxcbiAgICAgICAgICAgIHNob3dDdXJyZW5jeTogdHJ1ZSxcbiAgICAgICAgICAgIGRpc3BsYXlOYW1lOiBsb2NhbGVzLnRyYW5zbGF0ZSgnQmV0JyksXG4gICAgICAgICAgICBnZXRBdHRyVmFsdWU6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgIHZhciB2YWwgPSBHcmlkVXRpbHMuTW9iaWxlQ2VsbC5wcm90b3R5cGUuZ2V0QXR0clZhbHVlLmFwcGx5KHRoaXMpO1xuXG4gICAgICAgICAgICAgICAgaWYgKHRoaXMubW9kZWwuZ2V0KCd0eXBlJykgPT09ICdST0xMQkFDSycpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuICc8aSBjbGFzcz1cImZhIGZhLXJvdGF0ZS1sZWZ0XCIgdGl0bGU9XCJSb2xsYmFja1wiPjwvaT4mbmJzcDsnICsgdmFsO1xuICAgICAgICAgICAgICAgIH0gZWxzZSBpZiAodmFsICE9IG51bGwgJiYgdGhpcy5tb2RlbC5nZXQoJ2lzX2JvbnVzJykpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHZhbCArICcgPGkgY2xhc3M9XCJmYSBmYS1naWZ0XCI+PC9pPic7XG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHZhbDtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pXG4gICAgfSwge1xuICAgICAgICBzb3J0YWJsZTogZmFsc2UsXG4gICAgICAgIGF0dHI6ICd3aW4nLFxuICAgICAgICBoZWFkZXI6IE1hcmlvbmV0dGUuVmlldy5leHRlbmQoe1xuICAgICAgICAgICAgdGVtcGxhdGU6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgIHJldHVybiBsb2NhbGVzLnRyYW5zbGF0ZSgnV2luJylcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSksXG4gICAgICAgIGNsYXNzTmFtZTogJ3RleHQtcmlnaHQnLFxuICAgICAgICBjZWxsOiBHcmlkVXRpbHMuTW9iaWxlQ2VsbC5leHRlbmQoe1xuICAgICAgICAgICAgY2xhc3NOYW1lOiAnc20tZmxvYXQtbGVmdCB0ZXh0LXJpZ2h0JyxcbiAgICAgICAgICAgIHNob3dDdXJyZW5jeTogdHJ1ZSxcbiAgICAgICAgICAgIGRpc3BsYXlOYW1lOiBsb2NhbGVzLnRyYW5zbGF0ZSgnV2luJyksXG4gICAgICAgICAgICBnZXRBdHRyVmFsdWU6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgIGlmICh0aGlzLm1vZGVsLmdldCgnc3RhdHVzJykgPT09ICdPSycpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGNvbHVtbiA9IHRoaXMuZ2V0T3B0aW9uKCdjb2x1bW4nKTtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHRoaXMubW9kZWwuZ2V0KGNvbHVtbi5nZXQoJ2F0dHInKSk7XG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHRoaXMubW9kZWwuZ2V0KCdzdGF0dXMnKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pXG4gICAgfSwge1xuICAgICAgICBzb3J0YWJsZTogZmFsc2UsXG4gICAgICAgIGF0dHI6ICdvdXRjb21lJyxcbiAgICAgICAgaGVhZGVyOiBNYXJpb25ldHRlLlZpZXcuZXh0ZW5kKHtcbiAgICAgICAgICAgIHRlbXBsYXRlOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gbG9jYWxlcy50cmFuc2xhdGUoJ091dGNvbWUnKVxuICAgICAgICAgICAgfVxuICAgICAgICB9KSxcbiAgICAgICAgY2xhc3NOYW1lOiAndGV4dC1yaWdodCcsXG4gICAgICAgIGNlbGw6IEdyaWRVdGlscy5Nb2JpbGVDZWxsLmV4dGVuZCh7XG4gICAgICAgICAgICBjbGFzc05hbWU6ICdzbS1mbG9hdC1sZWZ0IHRleHQtcmlnaHQnLFxuICAgICAgICAgICAgc2hvd0N1cnJlbmN5OiB0cnVlLFxuICAgICAgICAgICAgc2hvd1NwaW5uZXI6IHRydWUsXG4gICAgICAgICAgICBkaXNwbGF5TmFtZTogbG9jYWxlcy50cmFuc2xhdGUoJ091dGNvbWUnKSxcbiAgICAgICAgICAgIGdldEF0dHJWYWx1ZTogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgdmFyIHN0YXR1cyA9IHRoaXMubW9kZWwuZ2V0KCdzdGF0dXMnKTtcbiAgICAgICAgICAgICAgICBpZiAoc3RhdHVzID09PSAnT0snKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciBjb2x1bW4gPSB0aGlzLmdldE9wdGlvbignY29sdW1uJyk7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiB0aGlzLm1vZGVsLmdldChjb2x1bW4uZ2V0KCdhdHRyJykpO1xuICAgICAgICAgICAgICAgIH0gZWxzZSBpZiAoc3RhdHVzID09PSAnRVhDRUVEJykge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gMDtcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gJz8nO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfSlcbiAgICB9LCB7XG4gICAgICAgIHNvcnRhYmxlOiBmYWxzZSxcbiAgICAgICAgYXR0cjogJ2JhbGFuY2VfYWZ0ZXInLFxuICAgICAgICBoZWFkZXI6IE1hcmlvbmV0dGUuVmlldy5leHRlbmQoe1xuICAgICAgICAgICAgdGVtcGxhdGU6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgIHJldHVybiBsb2NhbGVzLnRyYW5zbGF0ZSgnQmFsLiBBZnRlcicpXG4gICAgICAgICAgICB9XG4gICAgICAgIH0pLFxuICAgICAgICBjbGFzc05hbWU6ICd0ZXh0LXJpZ2h0JyxcbiAgICAgICAgY2VsbDogR3JpZFV0aWxzLk1vYmlsZUNlbGwuZXh0ZW5kKHtcbiAgICAgICAgICAgIGNsYXNzTmFtZTogJ3NtLWZsb2F0LWxlZnQgdGV4dC1yaWdodCcsXG4gICAgICAgICAgICBzaG93Q3VycmVuY3k6IHRydWUsXG4gICAgICAgICAgICBkaXNwbGF5TmFtZTogbG9jYWxlcy50cmFuc2xhdGUoJ0JhbC4gQWZ0ZXInKSxcbiAgICAgICAgICAgIGdldEF0dHJWYWx1ZTogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgdmFyIHN0YXR1cyA9IHRoaXMubW9kZWwuZ2V0KCdzdGF0dXMnKTtcbiAgICAgICAgICAgICAgICBpZiAoc3RhdHVzID09PSAnT0snIHx8IHN0YXR1cyA9PT0gJ0VYQ0VFRCcpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIEdyaWRVdGlscy5Nb2JpbGVDZWxsLnByb3RvdHlwZS5nZXRBdHRyVmFsdWUuYXBwbHkodGhpcyk7XG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuICc/JztcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pXG5cbiAgICB9LCB7XG4gICAgICAgIGF0dHI6ICdyb3VuZF9pZCcsXG4gICAgICAgIHNvcnRhYmxlOiBmYWxzZSxcbiAgICAgICAgaGVhZGVyOiBNYXJpb25ldHRlLlZpZXcuZXh0ZW5kKHtcbiAgICAgICAgICAgIHRlbXBsYXRlOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gbG9jYWxlcy50cmFuc2xhdGUoJ1JvdW5kJylcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSksXG4gICAgICAgIGNsYXNzTmFtZTogJ3RleHQtY2VudGVyJyxcbiAgICAgICAgY2VsbDogR3JpZFV0aWxzLk1vYmlsZUNlbGwuZXh0ZW5kKHtcbiAgICAgICAgICAgIGNsYXNzTmFtZTogJ3NtLWZsb2F0LWxlZnQnLFxuICAgICAgICAgICAgZGlzcGxheU5hbWU6IGxvY2FsZXMudHJhbnNsYXRlKCdSb3VuZCcpXG4gICAgICAgIH0pXG4gICAgfSwge1xuICAgICAgICBhdHRyOiAncm91bmRfaWQnLFxuICAgICAgICBjbGFzc05hbWU6ICdoZWlnaHQtYXV0byBkcmF3ZXItdGFibGUtY2VsbCcsXG4gICAgICAgIGhlYWRlcjogR3JpZFV0aWxzQ3VzdG9tLkRyYXdlckhlYWRlcixcbiAgICAgICAgY2VsbDogR3JpZFV0aWxzQ3VzdG9tLkRyYXdlckNlbGxcbiAgICB9XSxcbiAgICBzaXplclZpZXc6IE1hR3JpZC5TaXplclZpZXcuZXh0ZW5kKHtcbiAgICAgICAgdGVtcGxhdGU6IF8udGVtcGxhdGUoW1xuICAgICAgICAgICAgJzxzZWxlY3QgY2xhc3M9XCJmb3JtLWNvbnRyb2wgaW5wdXQtc21cIj4nLFxuICAgICAgICAgICAgJzwlIGZvcih2YXIgaSBpbiBwYWdlU2l6ZXMpIHsgJT4nLFxuICAgICAgICAgICAgJzxvcHRpb24gPCUgaWYocGFnZVNpemVzW2ldID09IGN1cnJlbnRTaXplKSB7ICU+IHNlbGVjdGVkPCUgfSU+ICB2YWx1ZT1cIjwlPSBwYWdlU2l6ZXNbaV0gJT5cIj4nLFxuICAgICAgICAgICAgJyAgICA8JT0gcGFnZVRleHQucmVwbGFjZShcIntzaXplfVwiLCBwYWdlU2l6ZXNbaV0pICU+JyxcbiAgICAgICAgICAgICc8L29wdGlvbj4nLFxuICAgICAgICAgICAgJzwlIH0lPicsXG4gICAgICAgICAgICAnPC9zZWxlY3Q+J1xuICAgICAgICBdLmpvaW4oJycpKSxcbiAgICAgICAgdGVtcGxhdGVDb250ZXh0OiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIHZhciBjdHggPSBNYUdyaWQuU2l6ZXJWaWV3LnByb3RvdHlwZS50ZW1wbGF0ZUNvbnRleHQuYXBwbHkodGhpcywgYXJndW1lbnRzKTtcbiAgICAgICAgICAgIGN0eC5wYWdlVGV4dCA9IGxvY2FsZXMudHJhbnNsYXRlKCd7c2l6ZX0gcGVyIHBhZ2UnKTtcbiAgICAgICAgICAgIHJldHVybiBjdHg7XG4gICAgICAgIH1cbiAgICB9KSxcblxuICAgIGZvb3RlckNvbHVtbnM6IFt7XG4gICAgICAgIGF0dHI6ICdjdXJyZW5jeScsXG4gICAgICAgIGNsYXNzTmFtZTogJ3RleHQtYm9sZCcsXG4gICAgICAgIGNlbGw6IEdyaWRVdGlscy5Nb2JpbGVDZWxsLmV4dGVuZCh7XG4gICAgICAgICAgICBjbGFzc05hbWU6ICdzbS1mbG9hdC1sZWZ0IHRleHQtY2VudGVyJyxcbiAgICAgICAgICAgIHNob3dTcGlubmVyOiB0cnVlLFxuICAgICAgICAgICAgZGlzcGxheU5hbWU6IGxvY2FsZXMudHJhbnNsYXRlKCdUb3RhbCcpLFxuICAgICAgICAgICAgdGVtcGxhdGU6IF8udGVtcGxhdGUoW1xuICAgICAgICAgICAgICAgICc8c3BhbiBjbGFzcz1cInZpc2libGUteHMtaW5saW5lXCI+JyxcbiAgICAgICAgICAgICAgICAnPGI+PCU9IGRpc3BsYXlOYW1lICU+OiA8JT0gXy5pc051bGwodmFsKSA/IFwiJm1kYXNoO1wiIDogdmFsICU+JyxcbiAgICAgICAgICAgICAgICAnPC9iPjwvc3Bhbj4nXG4gICAgICAgICAgICBdLmpvaW4oJycpKVxuICAgICAgICB9KVxuICAgIH0sIHtcbiAgICAgICAgYXR0cjogJ2N1cnJlbmN5JyxcbiAgICAgICAgY2xhc3NOYW1lOiAnaGlkZGVuLXhzIHRleHQtYm9sZCcsXG4gICAgICAgIGNlbGw6IF8ubm9vcFxuICAgIH0sIHtcbiAgICAgICAgYXR0cjogJ2N1cnJlbmN5JyxcbiAgICAgICAgY2xhc3NOYW1lOiAnaGlkZGVuLXhzIHRleHQtYm9sZCcsXG4gICAgICAgIGNlbGw6IF8ubm9vcFxuICAgIH0sIHtcbiAgICAgICAgYXR0cjogJ2N1cnJlbmN5JyxcbiAgICAgICAgY2xhc3NOYW1lOiAndGV4dC1jZW50ZXIgdGV4dC1ib2xkJyxcbiAgICAgICAgY2VsbDogR3JpZFV0aWxzLk1vYmlsZUNlbGwuZXh0ZW5kKHtcbiAgICAgICAgICAgIGNsYXNzTmFtZTogJ3NtLWZsb2F0LWxlZnQgdGV4dC1jZW50ZXInLFxuICAgICAgICAgICAgc2hvd0N1cnJlbmN5OiBmYWxzZSxcbiAgICAgICAgICAgIHNob3dTcGlubmVyOiB0cnVlLFxuICAgICAgICAgICAgZGlzcGxheU5hbWU6IGxvY2FsZXMudHJhbnNsYXRlKCdUb3RhbCcpLFxuICAgICAgICAgICAgdGVtcGxhdGU6IF8udGVtcGxhdGUoW1xuICAgICAgICAgICAgICAgICc8c3BhbiBjbGFzcz1cImhpZGRlbi14c1wiPicsXG4gICAgICAgICAgICAgICAgJzwlIGlmIChzaG93U3Bpbm5lcikgeyAlPicsXG4gICAgICAgICAgICAgICAgJyAgICA8JSBpZiAodmFsID09PSBudWxsKSB7ICU+JyxcbiAgICAgICAgICAgICAgICAnICAgICAgICA8aSBjbGFzcz1cXCdmYSBmYS1zcGluIGZhLXJlZnJlc2hcXCc+PC9pPicsXG4gICAgICAgICAgICAgICAgJyAgICA8JSB9IGVsc2UgeyAlPicsXG4gICAgICAgICAgICAgICAgJyAgICAgICAgPCU9IHZhbCAlPicsXG4gICAgICAgICAgICAgICAgJyAgICA8JSB9ICU+JyxcbiAgICAgICAgICAgICAgICAnPCUgfSBlbHNlIHsgJT4nLFxuICAgICAgICAgICAgICAgICcgICAgPCU9IF8uaXNOdWxsKHZhbCkgPyBcIiZtZGFzaDtcIiA6IHZhbCAlPicsXG4gICAgICAgICAgICAgICAgJzwlIH0gJT4nLFxuICAgICAgICAgICAgICAgICc8L3NwYW4+J1xuICAgICAgICAgICAgXS5qb2luKCcnKSlcbiAgICAgICAgfSlcbiAgICB9LCB7XG4gICAgICAgIGF0dHI6ICdiYWxhbmNlX2JlZm9yZScsXG4gICAgICAgIGNsYXNzTmFtZTogJ2hpZGRlbi14cyB0ZXh0LWJvbGQnLFxuICAgICAgICBjZWxsOiBfLm5vb3BcbiAgICB9LCB7XG4gICAgICAgIGF0dHI6ICdiZXQnLFxuICAgICAgICBjbGFzc05hbWU6ICd0ZXh0LXJpZ2h0IHRleHQtYm9sZCcsXG4gICAgICAgIGNlbGw6IEdyaWRVdGlscy5Nb2JpbGVDZWxsLmV4dGVuZCh7XG4gICAgICAgICAgICBjbGFzc05hbWU6ICdzbS1mbG9hdC1sZWZ0IHRleHQtcmlnaHQnLFxuICAgICAgICAgICAgc2hvd0N1cnJlbmN5OiB0cnVlLFxuICAgICAgICAgICAgc2hvd1NwaW5uZXI6IHRydWUsXG4gICAgICAgICAgICBkaXNwbGF5TmFtZTogbG9jYWxlcy50cmFuc2xhdGUoJ0JldCcpXG4gICAgICAgIH0pXG4gICAgfSwge1xuICAgICAgICBhdHRyOiAnd2luJyxcbiAgICAgICAgY2xhc3NOYW1lOiAndGV4dC1yaWdodCB0ZXh0LWJvbGQnLFxuICAgICAgICBjZWxsOiBHcmlkVXRpbHMuTW9iaWxlQ2VsbC5leHRlbmQoe1xuICAgICAgICAgICAgY2xhc3NOYW1lOiAnc20tZmxvYXQtbGVmdCB0ZXh0LXJpZ2h0JyxcbiAgICAgICAgICAgIHNob3dDdXJyZW5jeTogdHJ1ZSxcbiAgICAgICAgICAgIHNob3dTcGlubmVyOiB0cnVlLFxuICAgICAgICAgICAgZGlzcGxheU5hbWU6IGxvY2FsZXMudHJhbnNsYXRlKCdXaW5zJylcbiAgICAgICAgfSlcbiAgICB9LCB7XG4gICAgICAgIGF0dHI6ICdvdXRjb21lJyxcbiAgICAgICAgY2xhc3NOYW1lOiAndGV4dC1yaWdodCB0ZXh0LWJvbGQnLFxuICAgICAgICBjZWxsOiBHcmlkVXRpbHMuTW9iaWxlQ2VsbC5leHRlbmQoe1xuICAgICAgICAgICAgY2xhc3NOYW1lOiAnc20tZmxvYXQtbGVmdCB0ZXh0LXJpZ2h0JyxcbiAgICAgICAgICAgIHNob3dDdXJyZW5jeTogdHJ1ZSxcbiAgICAgICAgICAgIHNob3dTcGlubmVyOiB0cnVlLFxuICAgICAgICAgICAgZGlzcGxheU5hbWU6IGxvY2FsZXMudHJhbnNsYXRlKCdPdXRjb21lJylcbiAgICAgICAgfSlcbiAgICB9LCB7XG4gICAgICAgIGF0dHI6ICdiYWxhbmNlX2FmdGVyJyxcbiAgICAgICAgY2xhc3NOYW1lOiAnaGlkZGVuLXhzIHRleHQtYm9sZCcsXG4gICAgICAgIGNlbGw6IF8ubm9vcFxuICAgIH0sIHtcbiAgICAgICAgYXR0cjogJ2N1cnJlbmN5JyxcbiAgICAgICAgY2xhc3NOYW1lOiAnaGlkZGVuLXhzIHRleHQtYm9sZCcsXG4gICAgICAgIGNlbGw6IF8ubm9vcFxuICAgIH1dLFxuXG4gICAgc2hvd0JhbGFuY2U6IGZ1bmN0aW9uKCkge1xuICAgICAgICB0aGlzLmNvbHVtbnMuZWFjaChmdW5jdGlvbihtb2RlbCkge1xuICAgICAgICAgICAgaWYgKG1vZGVsLmdldCgnYXR0cicpLmluZGV4T2YoJ2JhbGFuY2UnKSA9PT0gMCkge1xuICAgICAgICAgICAgICAgIG1vZGVsLnNldCgnaXNIaWRkZW4nLCBmYWxzZSwge1xuICAgICAgICAgICAgICAgICAgICBzaWxlbnQ6IHRydWVcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgICAgIHRoaXMuZm9vdGVyQ29sdW1ucy5lYWNoKGZ1bmN0aW9uKG1vZGVsKSB7XG4gICAgICAgICAgICBpZiAobW9kZWwuZ2V0KCdhdHRyJykuaW5kZXhPZignYmFsYW5jZScpID09PSAwKSB7XG4gICAgICAgICAgICAgICAgbW9kZWwuc2V0KCdpc0hpZGRlbicsIGZhbHNlLCB7XG4gICAgICAgICAgICAgICAgICAgIHNpbGVudDogdHJ1ZVxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICAgICAgdGhpcy5yZW5kZXIoKTtcbiAgICB9LFxuXG4gICAgaGlkZUJhbGFuY2U6IGZ1bmN0aW9uKCkge1xuICAgICAgICB0aGlzLmNvbHVtbnMuZWFjaChmdW5jdGlvbihtb2RlbCkge1xuICAgICAgICAgICAgaWYgKG1vZGVsLmdldCgnYXR0cicpLmluZGV4T2YoJ2JhbGFuY2UnKSA9PT0gMCkge1xuICAgICAgICAgICAgICAgIG1vZGVsLnNldCgnaXNIaWRkZW4nLCB0cnVlLCB7XG4gICAgICAgICAgICAgICAgICAgIHNpbGVudDogdHJ1ZVxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICAgICAgdGhpcy5mb290ZXJDb2x1bW5zLmVhY2goZnVuY3Rpb24obW9kZWwpIHtcbiAgICAgICAgICAgIGlmIChtb2RlbC5nZXQoJ2F0dHInKS5pbmRleE9mKCdiYWxhbmNlJykgPT09IDApIHtcbiAgICAgICAgICAgICAgICBtb2RlbC5zZXQoJ2lzSGlkZGVuJywgdHJ1ZSwge1xuICAgICAgICAgICAgICAgICAgICBzaWxlbnQ6IHRydWVcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgICAgIHRoaXMucmVuZGVyKCk7XG4gICAgfVxufSk7XG5cbm1vZHVsZS5leHBvcnRzID0gUm91bmRzR3JpZFZpZXc7XG5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL3NyYy9qcy92aWV3cy9yb3VuZC9ncmlkLmpzXG4vLyBtb2R1bGUgaWQgPSAyNVxuLy8gbW9kdWxlIGNodW5rcyA9IDAiLCJtb2R1bGUuZXhwb3J0cyA9IGZ1bmN0aW9uKG9iaikge1xub2JqIHx8IChvYmogPSB7fSk7XG52YXIgX190LCBfX3AgPSAnJywgX19lID0gXy5lc2NhcGUsIF9faiA9IEFycmF5LnByb3RvdHlwZS5qb2luO1xuZnVuY3Rpb24gcHJpbnQoKSB7IF9fcCArPSBfX2ouY2FsbChhcmd1bWVudHMsICcnKSB9XG53aXRoIChvYmopIHtcbl9fcCArPSAnPGRpdiBjbGFzcz1cImJveCBib3gtcHJpbWFyeSAgbm8tc2hhZG93XCI+XFxuXFxuICAgIDxmb3JtIGNsYXNzPVwiYm94LWhlYWRlciB3aXRoLWJvcmRlclwiIHN0eWxlPVwiYmFja2dyb3VuZC1jb2xvcjogI2U0ZThlYztcIj5cXG4gICAgICAgICc7XG4gaWYgKGhlYWRlciAhPT0gJzAnKSB7IDtcbl9fcCArPSAnXFxuICAgICAgICAgICAgPGRpdiBjbGFzcz1cInJvd1wiPlxcbiAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiY29sLXhzLTYgY29sLXNtLTEwIGNvbC1tZC0xMFwiPlxcbiAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImZvcm0tZ3JvdXBcIj5cXG4gICAgICAgICAgICAgICAgICAgICAgICA8bGFiZWw+JyArXG4oKF9fdCA9ICggdHJhbnNsYXRlKCdSb3VuZCcpICkpID09IG51bGwgPyAnJyA6IF9fdCkgK1xuJzo8L2xhYmVsPlxcbiAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCB0eXBlPVwidGV4dFwiIGNsYXNzPVwiZm9ybS1jb250cm9sXCIgbmFtZT1cInJvdW5kX2lkXCIgdmFsdWU9XCInICtcbl9fZSggcm91bmRfaWQgKSArXG4nXCI+XFxuICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImZpZWxkLWVycm9yXCI+PC9kaXY+XFxuICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cXG4gICAgICAgICAgICAgICAgPC9kaXY+XFxuICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJjb2wteHMtNiBjb2wtc20tMiBjb2wtbWQtMlwiPlxcbiAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImJveC1zdWJtaXRcIj5cXG4gICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiYnRuIGJ0bi13YXJuaW5nIGJ0bi1ibG9jayBzdWJtaXQtYnRuXCI+XFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpIGNsYXNzPVwiZmEgZmEtc2VhcmNoXCI+PC9pPiAnICtcbigoX190ID0gKCB0cmFuc2xhdGUoJ1NlYXJjaCcpICkpID09IG51bGwgPyAnJyA6IF9fdCkgK1xuJ1xcbiAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PlxcbiAgICAgICAgICAgICAgICAgICAgPC9kaXY+XFxuICAgICAgICAgICAgICAgIDwvZGl2PlxcbiAgICAgICAgICAgIDwvZGl2PlxcbiAgICAgICAgJztcbiB9IDtcbl9fcCArPSAnXFxuXFxuICAgICAgICA8aDQgY2xhc3M9XCJob3Jpem9udGFsLWRpdmlkZXJcIj5cXG4gICAgICAgICAgICA8aSBjbGFzcz1cImZhIGZhLWJhci1jaGFydFwiPjwvaT4gJyArXG4oKF9fdCA9ICggdHJhbnNsYXRlKCdSb3VuZCBSZXN1bHRzJykgKSkgPT0gbnVsbCA/ICcnIDogX190KSArXG4nXFxuICAgICAgICA8L2g0PlxcblxcbiAgICAgICAgICAgIDxhIGNsYXNzPVwic2VjdGlvbiBnb2JhY2tcIiB0aXRsZT1cImdvIGJhY2tcIj5cXG4gICAgICAgICAgICAgICAgPGkgY2xhc3M9XCJmYSBmYS1iYWNrd2FyZFwiPjwvaT4gJyArXG5fX2UoIHRyYW5zbGF0ZSgncGxheWVyIGdhbWVzJykgKSArXG4nXFxuICAgICAgICAgICAgPC9hPlxcblxcbiAgICA8L2Zvcm0+XFxuXFxuICAgICc7XG4gaWYgKHNob3dQbGF5ZXJJbmZvKSB7IDtcbl9fcCArPSAnXFxuICAgICAgICA8ZGl2IGNsYXNzPVwiYm94LWJvZHkgYXBwLXBsYXllci1pbmZvLXdpZGdldFwiIHN0eWxlPVwicGFkZGluZzogMjBweCAwIDA7XCI+XFxuICAgICAgICAgICAgPGRpdiBjbGFzcz1cImJveC1oZWFkZXIgd2l0aC1ib3JkZXJcIiBzdHlsZT1cImJhY2tncm91bmQtY29sb3I6ICNlNGU4ZWM7XCI+XFxuICAgICAgICAgICAgICAgIDxsYWJlbCBzdHlsZT1cIm1hcmdpbjogMDtcIj4nICtcbl9fZSggdHJhbnNsYXRlKCdJbmZvJykgKSArXG4nPC9sYWJlbD5cXG4gICAgICAgICAgICA8L2Rpdj5cXG4gICAgICAgICAgICA8ZGl2IGNsYXNzPVwiYm94LWJvZHkgYXBwLXBsYXllci1pbmZvLXJlZ2lvblwiPlxcbiAgICAgICAgICAgIDwvZGl2PlxcbiAgICAgICAgPC9kaXY+XFxuICAgICc7XG4gfSA7XG5fX3AgKz0gJ1xcblxcbiAgICA8ZGl2IGNsYXNzPVwiYm94LWJvZHkgYXBwLXRvdGFscy13aWRnZXRcIiBzdHlsZT1cInBhZGRpbmc6IDIwcHggMCAwO1wiPlxcbiAgICAgICAgPGRpdiBjbGFzcz1cImJveC1oZWFkZXIgd2l0aC1ib3JkZXJcIiBzdHlsZT1cImJhY2tncm91bmQtY29sb3I6ICNlNGU4ZWM7XCI+XFxuICAgICAgICAgICAgPGxhYmVsIHN0eWxlPVwibWFyZ2luOiAwO1wiPicgK1xuX19lKCB0cmFuc2xhdGUoJ1RvdGFsJykgKSArXG4nPC9sYWJlbD5cXG4gICAgICAgIDwvZGl2PlxcbiAgICAgICAgPGRpdiBjbGFzcz1cImJveC1ib2R5IGFwcC10b3RhbHMtcmVnaW9uXCI+XFxuICAgICAgICA8L2Rpdj5cXG4gICAgPC9kaXY+XFxuXFxuICAgIDxkaXYgY2xhc3M9XCJib3gtYm9keSBhcHAtbGlzdC1yZWdpb25cIiBzdHlsZT1cInBhZGRpbmc6IDIwcHggMDtcIj5cXG4gICAgPC9kaXY+XFxuICAgIDxkaXYgY2xhc3M9XCJvdmVybGF5XCIgc3R5bGU9XCJkaXNwbGF5OiBub25lO1wiPlxcbiAgICAgICAgPGkgY2xhc3M9XCJmYSBmYS1yZWZyZXNoIGZhLXNwaW5cIj48L2k+XFxuICAgIDwvZGl2PlxcbjwvZGl2Plxcbic7XG5cbn1cbnJldHVybiBfX3Bcbn07XG5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL3NyYy9qcy92aWV3cy9yb3VuZC92aWV3LmVqc1xuLy8gbW9kdWxlIGlkID0gMjZcbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwidmFyIFJvdXRlciA9IE1hcmlvbmV0dGUuQXBwUm91dGVyLmV4dGVuZCh7XG4gICAgYXBwUm91dGVzOiB7XG4gICAgICAgICcoOnBhcmFtcyknOiAnaW5kZXgnXG4gICAgfVxufSk7XG5cbm1vZHVsZS5leHBvcnRzID0gUm91dGVyO1xuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vc3JjL2pzL2FwcC9yb3V0ZXIuanNcbi8vIG1vZHVsZSBpZCA9IDI3XG4vLyBtb2R1bGUgY2h1bmtzID0gMCIsInZhciB0cGwgPSByZXF1aXJlKCcuL3ZpZXcuZWpzJyk7XG5cblxudmFyIFNjcm9sbFRvVG9wV2lkZ2V0VmlldyA9IEJhY2tib25lLk1hcmlvbmV0dGUuVmlldy5leHRlbmQoe1xuICAgIHRlbXBsYXRlOiBfLnRlbXBsYXRlKFtcbiAgICAgICAgJzxkaXYgY2xhc3M9XCJ1aSBhcHAtc2Nyb2xsLXRvcFwiPjxjZW50ZXI+JyxcbiAgICAgICAgJzxpIGNsYXNzPVwiZmEgZmEtYW5nbGUtZG91YmxlLXVwXCIgc3R5bGU9XCJmb250LXNpemU6IDRlbTtcIj48L2k+JyxcbiAgICAgICAgJzwvY2VudGVyPjwvZGl2PiddLmpvaW4oJycpKSxcblxufSk7XG5cblxudmFyIExheW91dFZpZXcgPSBCYWNrYm9uZS5NYXJpb25ldHRlLlZpZXcuZXh0ZW5kKHtcbiAgICB0ZW1wbGF0ZTogdHBsLFxuICAgIGNsYXNzTmFtZTogJycsXG4gICAgdWk6IHtcbiAgICAgICAgY29udGVudDogJy5jb250ZW50JyxcbiAgICAgICAgb3ZlcmxheTogJy5vdmVybGF5JyxcbiAgICAgICAgc2Nyb2xsVG9wOiAnLnNjcm9sbC10b3AnXG4gICAgfSxcbiAgICByZWdpb25zOiB7XG4gICAgICAgIGNvbnRlbnQ6ICdAdWkuY29udGVudCcsXG4gICAgICAgIHNjcm9sbFRvcDogJ0B1aS5zY3JvbGxUb3AnXG4gICAgfSxcbiAgICBjaGlsZFZpZXdFdmVudHM6IHtcbiAgICAgICAgJ3N0YXRlOmNoYW5nZWQnOiAnb25DaGlsZFN0YXRlQ2hhbmdlZCdcbiAgICB9LFxuXG4gICAgZXZlbnRzOiB7XG4gICAgICAgICdjbGljayBAdWkuc2Nyb2xsVG9wJzogJ29uU2Nyb2xsVG9wQ2xpY2snXG4gICAgfSxcbiAgICBpbml0aWFsaXplOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHRoaXMuZGltZW5zaW9ucyA9IHtcbiAgICAgICAgICAgIHdpZHRoOiAwLFxuICAgICAgICAgICAgaGVpZ2h0OiAwXG4gICAgICAgIH07XG4gICAgfSxcblxuICAgIG9uUmVuZGVyOiBmdW5jdGlvbigpIHtcbiAgICAgICAgdGhpcy5zaG93Q2hpbGRWaWV3KCdzY3JvbGxUb3AnLCBuZXcgU2Nyb2xsVG9Ub3BXaWRnZXRWaWV3KCkpO1xuICAgICAgICBzZXRJbnRlcnZhbChfLmJpbmQodGhpcy5yZXNpemUsIHRoaXMpLCAyMDApO1xuICAgIH0sXG5cbiAgICBzaG93OiBmdW5jdGlvbiAodmlldykge1xuICAgICAgICB0aGlzLnNob3dDaGlsZFZpZXcoJ2NvbnRlbnQnLCB2aWV3KTtcbiAgICB9LFxuXG4gICAgcmVzaXplOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHRoaXMuJGVsLmNzcyh7d2lkdGg6ICcnfSk7XG4gICAgICAgIGlmICh0aGlzLiRlbFswXS5zY3JvbGxXaWR0aCA+ICQod2luZG93KS53aWR0aCgpKSB7XG4gICAgICAgICAgICB0aGlzLiRlbC5jc3Moe3dpZHRoOiB0aGlzLiRlbFswXS5zY3JvbGxXaWR0aCArICdweCd9KTtcbiAgICAgICAgfVxuXG4gICAgICAgIHZhciAkY29udGVudCA9IHRoaXMudWkuY29udGVudDtcbiAgICAgICAgdmFyIG1pbl9oZWlnaHQgPSAoJGNvbnRlbnQub3V0ZXJXaWR0aCgpID4gNzEwKSA/IDMwMCA6IDgwMDtcbiAgICAgICAgdmFyIGRpbWVuc2lvbnMgPSB7XG4gICAgICAgICAgICB3aWR0aDogJGNvbnRlbnQub3V0ZXJXaWR0aCgpLFxuICAgICAgICAgICAgaGVpZ2h0OiBNYXRoLm1heCgkY29udGVudC5vdXRlckhlaWdodCgpICsgMjAsIG1pbl9oZWlnaHQpXG4gICAgICAgIH07XG5cbiAgICAgICAgaWYgKHRoaXMuZGltZW5zaW9ucy53aWR0aCAhPT0gZGltZW5zaW9ucy53aWR0aCB8fCB0aGlzLmRpbWVuc2lvbnMuaGVpZ2h0ICE9PSBkaW1lbnNpb25zLmhlaWdodCkge1xuICAgICAgICAgICAgd2luZG93LnBhcmVudC5wb3N0TWVzc2FnZShKU09OLnN0cmluZ2lmeSh7XG4gICAgICAgICAgICAgICAgZXZlbnQ6ICdoYW5kc2hpc3Rvcnk6cmVzaXplJyxcbiAgICAgICAgICAgICAgICBkaW1lbnNpb25zOiBkaW1lbnNpb25zXG4gICAgICAgICAgICB9KSwgJyonKTtcbiAgICAgICAgICAgIHRoaXMuZGltZW5zaW9ucyA9IGRpbWVuc2lvbnM7XG4gICAgICAgIH1cblxuICAgICAgICB2YXIgJGJvZHkgPSAkKCdib2R5Jyk7XG4gICAgICAgIGlmICgkYm9keS5vdXRlckhlaWdodCgpID4gJCh3aW5kb3cpLmhlaWdodCgpKSB7XG4gICAgICAgICAgICB0aGlzLnNob3dTY3JvbGxlcigpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgdGhpcy5oaWRlU2Nyb2xsZXIoKTtcbiAgICAgICAgfVxuICAgIH0sXG4gICAgaGlkZVNjcm9sbGVyOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHRoaXMudWkuc2Nyb2xsVG9wLmhpZGUoKTtcbiAgICB9LFxuICAgIHNob3dTY3JvbGxlcjogZnVuY3Rpb24gKCkge1xuICAgICAgICB0aGlzLnVpLnNjcm9sbFRvcC5zaG93KCk7XG4gICAgfSxcbiAgICBvbkNoaWxkU3RhdGVDaGFuZ2VkOiBmdW5jdGlvbiAoZGF0YSkge1xuICAgICAgICAkKCdodG1sLCBib2R5JykuYW5pbWF0ZSh7c2Nyb2xsVG9wOiAwfSwgNTAwKTtcbiAgICAgICAgdGhpcy50cmlnZ2VyTWV0aG9kKCdzdGF0ZTpjaGFuZ2VkJywgZGF0YSk7XG4gICAgfSxcbiAgICBvblNjcm9sbFRvcENsaWNrOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICQoJ2h0bWwsIGJvZHknKS5hbmltYXRlKHtzY3JvbGxUb3A6IDB9LCAyNTApO1xuICAgIH0sXG4gICAgaGlkZU92ZXJsYXk6IGZ1bmN0aW9uKCkge1xuICAgICAgICB0aGlzLnVpLm92ZXJsYXkuaGlkZSgpO1xuICAgIH0sXG4gICAgc2hvd092ZXJsYXk6IGZ1bmN0aW9uKCkge1xuICAgICAgICB0aGlzLnVpLm92ZXJsYXkuc2hvdygpO1xuICAgIH1cbn0pO1xuXG5tb2R1bGUuZXhwb3J0cyA9IExheW91dFZpZXc7XG5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL3NyYy9qcy92aWV3cy9sYXlvdXQvdmlldy5qc1xuLy8gbW9kdWxlIGlkID0gMjhcbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwibW9kdWxlLmV4cG9ydHMgPSBmdW5jdGlvbihvYmopIHtcbm9iaiB8fCAob2JqID0ge30pO1xudmFyIF9fdCwgX19wID0gJycsIF9fZSA9IF8uZXNjYXBlO1xud2l0aCAob2JqKSB7XG5fX3AgKz0gJzxkaXYgY2xhc3M9XCJ3cmFwcGVyIG92ZXJsYXktd3JhcHBlclwiPlxcbiAgICA8ZGl2IGNsYXNzPVwiY29udGVudFwiPlxcbiAgICA8L2Rpdj5cXG4gICAgPGRpdiBjbGFzcz1cInNjcm9sbC10b3BcIj48L2Rpdj5cXG4gICAgPGRpdiBjbGFzcz1cIm92ZXJsYXlcIiBzdHlsZT1cImRpc3BsYXk6IG5vbmU7XCI+XFxuICAgICAgICA8aSBjbGFzcz1cImZhIGZhLXJlZnJlc2ggZmEtc3BpblwiPjwvaT5cXG4gICAgPC9kaXY+XFxuPC9kaXY+XFxuJztcblxufVxucmV0dXJuIF9fcFxufTtcblxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vc3JjL2pzL3ZpZXdzL2xheW91dC92aWV3LmVqc1xuLy8gbW9kdWxlIGlkID0gMjlcbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwidmFyIHRwbCA9IHJlcXVpcmUoJy4vdmlldy5lanMnKTtcblxudmFyIEVycm9yTW9kYWxWaWV3ID0gQmFja2JvbmUuTWFyaW9uZXR0ZS5WaWV3LmV4dGVuZCh7XG4gICAgb3B0aW9uczoge1xuICAgICAgICB2YWxpZGF0aW9uRXJyb3JUZXh0OiAnSW1wcm9wZXJseSBjb25maWd1cmVkOiAnLFxuICAgICAgICBwZXJtaXNzaW9uc0Vycm9yVGV4dDogJ1lvdSBhcmUgbm90IHBlcm1pdHRlZCB0byBhY2Nlc3MgdGhpcyBwYWdlIScsXG4gICAgICAgIGNvbW1vbkVycm9yVGV4dDogJ09vcHMsIHNvbWV0aGluZyB3ZW50IHdyb25nISBQbGVhc2UsIHRyeSBsYXRlcicsXG4gICAgICAgIHRleHQ6ICdPb3BzLCBzb21ldGhpbmcgd2VudCB3cm9uZyEnLFxuICAgICAgICBkZWZmZXJlZDogbnVsbFxuICAgIH0sXG4gICAgdGVtcGxhdGU6IHRwbCxcbiAgICBjbGFzc05hbWU6ICdtb2RhbCBmYWRlJyxcbiAgICB0ZW1wbGF0ZUNvbnRleHQ6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdmFyIHRleHQsIGVycm9ycywgc2hvd0J0bnMsXG4gICAgICAgICAgICBkZWZlcnJlZCA9IHRoaXMuZ2V0T3B0aW9uKCdkZWZlcnJlZCcpO1xuXG4gICAgICAgIGlmIChkZWZlcnJlZC5zdGF0dXMgPT09IDQwMCkge1xuICAgICAgICAgICAgdGV4dCA9IHRoaXMuZ2V0T3B0aW9uKCd2YWxpZGF0aW9uRXJyb3JUZXh0Jyk7XG4gICAgICAgICAgICBzaG93QnRucyA9IGZhbHNlO1xuICAgICAgICAgICAgZXJyb3JzID0gZGVmZXJyZWQucmVzcG9uc2VKU09OO1xuICAgICAgICB9IGVsc2UgaWYgKGRlZmVycmVkLnN0YXR1cyA9PT0gNDAzKSB7XG4gICAgICAgICAgICB0ZXh0ID0gdGhpcy5nZXRPcHRpb24oJ3Blcm1pc3Npb25zRXJyb3JUZXh0Jyk7XG4gICAgICAgICAgICBzaG93QnRucyA9IGZhbHNlO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgdGV4dCA9IHRoaXMuZ2V0T3B0aW9uKCdjb21tb25FcnJvclRleHQnKTtcbiAgICAgICAgICAgIHNob3dCdG5zID0gdHJ1ZTtcbiAgICAgICAgfVxuICAgICAgICByZXR1cm4ge1xuICAgICAgICAgICAgdGV4dDogdGV4dCxcbiAgICAgICAgICAgIHNob3dCdG5zOiBzaG93QnRucyxcbiAgICAgICAgICAgIGVycm9yczogZXJyb3JzXG4gICAgICAgIH1cbiAgICB9LFxuICAgIHVpOiB7XG4gICAgICAgIGVycm9yQm94OiAnLmFwcC1lcnJvci1ib3gnLFxuICAgICAgICBkZW55QnRuOiAnLmJ0bi5kZW55JyxcbiAgICAgICAgYXBwcm92ZUJ0bjogJy5idG4uYXBwcm92ZSdcbiAgICB9LFxuICAgIGV2ZW50czoge1xuICAgICAgICAnY2xpY2sgQHVpLmRlbnlCdG4nOiAnb25EZW55QnRuQ2xpY2snLFxuICAgICAgICAnY2xpY2sgQHVpLmFwcHJvdmVCdG4nOiAnb25BcHByb3ZlQnRuQ2xpY2snXG4gICAgfSxcbiAgICBpbml0aWFsaXplOiBmdW5jdGlvbiAob3B0aW9ucykge1xuICAgICAgICB2YXIgb2xkT25FcnJvciA9IHdpbmRvdy5vbmVycm9yO1xuICAgICAgICB3aW5kb3cub25lcnJvciA9IF8uYmluZChmdW5jdGlvbiAobXNnLCB1cmwsIGxpbmUsIGNvbCwgZXJyb3IpIHtcbiAgICAgICAgICAgIGlmIChvbGRPbkVycm9yKSBvbGRPbkVycm9yLmFwcGx5KHRoaXMsIGFyZ3VtZW50cyk7XG4gICAgICAgICAgICB0aGlzLmhpZGUoKTtcbiAgICAgICAgfSwgdGhpcyk7XG4gICAgfSxcbiAgICBoaWRlOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHRyeSB7XG4gICAgICAgICAgICB0aGlzLiRlbC5tb2RhbCgnaGlkZScpO1xuICAgICAgICB9IGNhdGNoIChlKSB7fVxuICAgIH0sXG4gICAgb25SZW5kZXI6IGZ1bmN0aW9uKCkge1xuICAgICAgICAkKCdib2R5JykuYXBwZW5kKHRoaXMuJGVsKTtcblxuICAgICAgICB0aGlzLiRlbC5vbignaGlkZGVuLmJzLm1vZGFsJywgXy5iaW5kKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHRyeSB7XG4gICAgICAgICAgICAgICAgdGhpcy5vZmYoKTtcbiAgICAgICAgICAgICAgICB0aGlzLmRlc3Ryb3koKTtcbiAgICAgICAgICAgIH0gY2F0Y2ggKGUpIHt9XG5cbiAgICAgICAgfSwgdGhpcykpO1xuICAgICAgICB0aGlzLiRlbC5tb2RhbCh7XG4gICAgICAgICAgICBzaG93OiB0cnVlLFxuICAgICAgICAgICAgY2xvc2FibGU6IGZhbHNlXG4gICAgICAgIH0pO1xuICAgIH0sXG4gICAgb25EZW55QnRuQ2xpY2s6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdGhpcy5oaWRlKCk7XG4gICAgfSxcbiAgICBvbkFwcHJvdmVCdG5DbGljazogZnVuY3Rpb24gKCkge1xuICAgICAgICB0aGlzLnRyaWdnZXJNZXRob2QoJ3JlcGVhdDpyZXF1ZXN0Jyk7XG4gICAgICAgIHRoaXMuaGlkZSgpO1xuICAgIH1cbn0pO1xubW9kdWxlLmV4cG9ydHMgPSBFcnJvck1vZGFsVmlldztcblxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vc3JjL2pzL3ZpZXdzL2Vycm9yX21vZGFsL3ZpZXcuanNcbi8vIG1vZHVsZSBpZCA9IDMwXG4vLyBtb2R1bGUgY2h1bmtzID0gMCIsIm1vZHVsZS5leHBvcnRzID0gZnVuY3Rpb24ob2JqKSB7XG5vYmogfHwgKG9iaiA9IHt9KTtcbnZhciBfX3QsIF9fcCA9ICcnLCBfX2UgPSBfLmVzY2FwZSwgX19qID0gQXJyYXkucHJvdG90eXBlLmpvaW47XG5mdW5jdGlvbiBwcmludCgpIHsgX19wICs9IF9fai5jYWxsKGFyZ3VtZW50cywgJycpIH1cbndpdGggKG9iaikge1xuX19wICs9ICdcXG48ZGl2IGNsYXNzPVwibW9kYWwtZGlhbG9nXCI+XFxuICAgIDxkaXYgY2xhc3M9XCJtb2RhbC1jb250ZW50XCI+XFxuICAgICAgICA8ZGl2IGNsYXNzPVwibW9kYWwtYm9keVwiPlxcbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJhcHAtZXJyb3ItYm94XCIgPlxcbiAgICAgICAgICAgICAgICAnICtcbigoX190ID0gKCB0ZXh0ICkpID09IG51bGwgPyAnJyA6IF9fdCkgK1xuJ1xcbiAgICAgICAgICAgICAgICAnO1xuIGlmKGVycm9ycykgeyA7XG5fX3AgKz0gJ1xcbiAgICAgICAgICAgICAgICA8YnI+PGJyPlxcbiAgICAgICAgICAgICAgICAnO1xuIHZhciBlayA9IF8ua2V5cyhlcnJvcnMpOyBmb3IodmFyIGkgaW4gZWspIHsgO1xuX19wICs9ICdcXG4gICAgICAgICAgICAgICAgICAgIDxiPicgK1xuKChfX3QgPSAoIGVrW2ldICkpID09IG51bGwgPyAnJyA6IF9fdCkgK1xuJzogPC9iPicgK1xuKChfX3QgPSAoIGVycm9yc1tla1tpXV0uam9pbignICcpICkpID09IG51bGwgPyAnJyA6IF9fdCkgK1xuJzxicj5cXG4gICAgICAgICAgICAgICAgJztcbiB9IDtcbl9fcCArPSAnXFxuICAgICAgICAgICAgICAgICc7XG4gfSA7XG5fX3AgKz0gJ1xcbiAgICAgICAgICAgIDwvZGl2PlxcbiAgICAgICAgPC9kaXY+XFxuICAgICAgICAnO1xuIGlmKHNob3dCdG5zKSB7IDtcbl9fcCArPSAnXFxuICAgICAgICA8ZGl2IGNsYXNzPVwibW9kYWwtZm9vdGVyXCI+XFxuICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJidG4gYnRuLWRhbmdlciBkZW55XCI+XFxuICAgICAgICAgICAgICAgIENhbmNlbFxcbiAgICAgICAgICAgIDwvZGl2PlxcbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJidG4gYnRuLWRlZmF1bHQgYXBwcm92ZVwiPlxcbiAgICAgICAgICAgICAgICA8aSBjbGFzcz1cImljb24gcmVmcmVzaFwiPjwvaT4gUmVwZWF0XFxuICAgICAgICAgICAgPC9kaXY+XFxuICAgICAgICA8L2Rpdj5cXG4gICAgICAgICc7XG4gfSA7XG5fX3AgKz0gJ1xcbiAgICA8L2Rpdj5cXG48L2Rpdj4nO1xuXG59XG5yZXR1cm4gX19wXG59O1xuXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvanMvdmlld3MvZXJyb3JfbW9kYWwvdmlldy5lanNcbi8vIG1vZHVsZSBpZCA9IDMxXG4vLyBtb2R1bGUgY2h1bmtzID0gMCJdLCJzb3VyY2VSb290IjoiIn0=