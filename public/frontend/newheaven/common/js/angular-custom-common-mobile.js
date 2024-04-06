'use strict';

var app = angular.module('mobileApp', [
    'ngRoute',
    'RouteData',
    'ngMessages',
    'ngDialog',
    'ngSweetAlert',
    'ngCurrencySymbol',
    'ngCookies',
    'pascalprecht.translate',
    'ui.bootstrap.pagination',
    'ngIdle'
]);

//ngIdle

app.config(['IdleProvider', function(IdleProvider) {
    IdleProvider.idle(1800);
    IdleProvider.timeout(5);
}]);

app.run(function(Idle) {
    // start watching when the app runs. also starts the Keepalive service by default.
    Idle.watch();
});

//ngIdle end

app.config(['$routeProvider', 'RouteDataProvider', function($routeProvider, RouteDataProvider) {
    RouteDataProvider.applyConfig({
        bodyClass: 'bg-main'
    });
    RouteDataProvider.hookToRootScope(true);

    $routeProvider
        // .when('/', {RouteData: {bodyClass: 'bg-main'}, templateUrl: 'pages/casino.php', controller: '', activeTab:'casino'})
        // .when('/slots', {RouteData: {bodyClass: 'bg-slots'}, templateUrl: 'pages/slots.php', controller: 'SlotController', activeTab:'slots'})
        .otherwise({
            redirectTo: '/',
            RouteData: {
                bodyClass: 'bg-main'
            },
            templateUrl: '../index.php',
            controller: ''
        });
}]);


app.config(['$routeProvider', 'RouteDataProvider', '$translateProvider', function($routeProvider, RouteDataProvider, $translateProvider) {
    RouteDataProvider.applyConfig({
        bodyClass: 'bg-main'
    });
    RouteDataProvider.hookToRootScope(true);

    $translateProvider.useStaticFilesLoader({
        prefix: '/common/js/resources/locale-',
        suffix: '.json'
    });

    $translateProvider.useLocalStorage();
    $translateProvider.useSanitizeValueStrategy('escaped');

    $routeProvider
        .when('/', {
            RouteData: {
                bodyClass: 'bg-main'
            },
            templateUrl: '/pages/main.php',
            controller: '',
            activeTab: 'main'
        })
        // .when('/slots-view', {RouteData: {bodyClass: 'bg-main'}, templateUrl: '/mobile/pages/slots-view.php', controller: ''})
        .otherwise({
            redirectTo: '/',
            RouteData: {
                bodyClass: 'bg-main'
            },
            templateUrl: '/mobile/page/main.php',
            controller: ''
        });
}]);
app.service('loggedInStatus', function($rootScope) {
    return {
        setLoggedInStatus: function() {
            $rootScope.loggedIn = true;
            $rootScope.loggedOut = false;
        },
        setLoggedOutStatus: function() {
            $rootScope.loggedIn = false;
            $rootScope.loggedOut = true;
        }
    };
});


app.service('AmountService', function() {
    return {
        sumAmount: function(amount, amountSum) {
            //console.log(amountSum);
            if (amount == "NaN" || amount == "") {
                return parseFloat(amountSum);
            }
            amount = parseFloat(amount) + parseFloat(amountSum);
            return amount;
        },
        resetAmount: function() {
            return 0;
        }
    };
});

app.service('AmountService', function() {
    return {
        sumAmount: function(amount, amountSum) {
            //console.log(amountSum);
            if (amount == "NaN" || amount == "") {
                return parseFloat(amountSum);
            }
            amount = parseFloat(amount) + parseFloat(amountSum);
            return amount;
        },
        resetAmount: function() {
            return 0;
        }
    };
});

app.service('browser', ['$window', function($window) {
    return function() {
        var userAgent = $window.navigator.userAgent;
        var browsers = {
            chrome: /chrome/i,
            safari: /safari/i,
            firefox: /firefox/i,
            ie: /internet explorer/i
        };
        for (var key in browsers) {
            if (browsers[key].test(userAgent)) {
                return key;
            }
        }
        return 'unknown';
    }
}]);

app.filter('customCurrency', ["$filter", function($filter) {
    return function(amount, currencySymbol) {
        //console.log(amount);
        var number = $filter('number');
        if (String(amount).charAt(0) === "-") {
            return number(amount).replace("-", "-" + currencySymbol);
        }
        if (amount == undefined) {
            return "Loading";
        } else {
            return number(amount) + currencySymbol;
        }
    };
}]);

app.filter('userDateTimeTimeZone', function($filter) {
    return function(input, format, offset) {
        if (input == null) {
            return "";
        }
        var timeFromUTC = moment.utc(input);
        var tzName = jstz.determine().name();
        var _date = moment(timeFromUTC, tzName).format("YYYY-MM-DD HH:mm:ss Z");
        return _date.toString();
    }
});

app.filter('userDateTime', function($filter) {
    return function(input, format, offset) {
        if (input == null) {
            return "";
        }
        var timeFromUTC = moment.utc(input);
        var tzName = jstz.determine().name();
        var _date = moment.tz(timeFromUTC, tzName).format("YYYY-MM-DD HH:mm");
        return _date.toString();
    }
});

app.filter('userDate', function($filter) {
    return function(input, format, offset) {
        if (input == null) {
            return "";
        }
        var timeFromUTC = moment.utc(input);
        var tzName = jstz.determine().name();
        var _date = moment.tz(timeFromUTC, tzName).format("YYYY-MM-DD");
        return _date.toString();
    }
});

app.filter('nl2br', ['$sce', function($sce) {
    return function(text) {
        return text ? $sce.trustAsHtml(text.replace(/\n/g, '<br/>')) : '';
    };
}]);

//Matched Password Filter
app.directive('validPasswordC', function() {
    return {
        require: 'ngModel',
        link: function(scope, elm, attrs, ctrl) {
            var original;
            ctrl.$formatters.unshift(function(modelValue) {
                original = modelValue;
                return modelValue;
            });
            ctrl.$parsers.push(function(viewValue) {
                var noMatch = viewValue != scope.signUp.MemberPwd.$viewValue;
                ctrl.$setValidity('noMatch', !noMatch);
                return viewValue;
            });
        }
    }
});

//Matched New Password Filter
app.directive('validPasswordC2', function() {
    return {
        require: 'ngModel',
        link: function(scope, elm, attrs, ctrl) {
            var original;
            ctrl.$formatters.unshift(function(modelValue) {
                original = modelValue;
                return modelValue;
            });
            ctrl.$parsers.push(function(viewValue) {
                var noMatch = viewValue != scope.changePwdForm.newPassword.$viewValue;
                ctrl.$setValidity('noMatch', !noMatch);
                return viewValue;
            });
        }
    }
});

var SPECIAL_CHAR = /^[a-zA-Z0-9\!\@\#\$\%\^\\\&\*\(\)\-\_\=\+]*$/;
//Password Special Character Filter
app.directive('specialCharC', function() {
    return {
        require: 'ngModel',
        link: function(scope, elm, attrs, ctrl) {
            ctrl.$validators.specialCharC = function(modelValue, viewValue) {
                ctrl.$setValidity('haveSpecialChar', SPECIAL_CHAR.test(viewValue));

                if (ctrl.$isEmpty(modelValue)) {
                    // consider empty models to be valid
                    return true;
                }

                if (SPECIAL_CHAR.test(viewValue)) {
                    // it is valid
                    return true;
                }

                // it is invalid
                return false;
            };
        }
    }
});

app.directive('userNameDuplicated', function(CsrfToken, $http, $window) {
    return {
        require: 'ngModel',
        link: function(scope, elm, attrs, ctrl) {
            var original;
            ctrl.$formatters.unshift(function(modelValue) {
                original = modelValue;
                return modelValue;
            });

            ctrl.$parsers.push(function(viewValue) {
                if (viewValue != undefined) {
                    if (viewValue.length >= 4) {
                        var url = "../api/player/CheckDuplicateName";
                        var param = {
                            MemberID: viewValue
                        }
                        CsrfToken.HttpRequest('POST', url, param).success(function(data) {
                            if (data.result == 0) {
                                if (data.csrf) {
                                    swal({
                                        title: "캐시 및 쿠키 삭제 후",
                                        text: "브라우저를 새로고침해 주세요",
                                        className: "csrf-swal",
                                        icon: "error",
                                        showCancelButton: false,
                                        confirmButtonClass: "#7cd1f9",
                                        confirmButtonText: "OK",
                                        closeOnConfirm: false
                                    }).then(function(confirm) {
                                        if (confirm) {
                                            $window.location.href = "/#/";
                                            $window.location.reload();
                                        }
                                    });
                                } else {
                                    ctrl.$setValidity('duplicated', false);
                                }
                            } else {
                                ctrl.$setValidity('duplicated', true);
                            }
                            ctrl.$setValidity('minlength', true);
                        });
                        return viewValue;
                    } else {
                        ctrl.$setValidity('minlength', false);
                        return viewValue;
                    }
                } else {
                    ctrl.$setValidity('minlength', false);
                    return viewValue;
                }
            })
        }
    };
});

/*app.directive('phoneNumberDuplicated', function (CsrfToken,$http) {
  return {
    require: 'ngModel',
    link: function (scope, elm, attrs, ctrl) {
      var original;
      ctrl.$formatters.unshift(function (modelValue) {
        original = modelValue;
        return modelValue;
      });

      ctrl.$parsers.push(function (viewValue) {
        if (viewValue != undefined) {
          if (viewValue.length >= 4) {
            var url = "../api/player/CheckDuplicatePhone";
            var param = { MemberPhone: viewValue }

            CsrfToken.HttpRequest('POST', url, param).success(function (data) {
              if (data.result == 0) {
                ctrl.$setValidity('duplicated', false);
              } else {
                ctrl.$setValidity('duplicated', true);
              }
              ctrl.$setValidity('minlength', true);
            });
            return viewValue;
          } else {
            ctrl.$setValidity('minlength', false);
            return viewValue;
          }
        } else {
          ctrl.$setValidity('minlength', false);
          return viewValue;
        }
      })
    }
  };
});*/

app.directive('referrerCheck', function(CsrfToken, $http) {
    return {
        require: 'ngModel',
        link: function(scope, elm, attrs, ctrl) {
            var original;
            ctrl.$formatters.unshift(function(modelValue) {
                original = modelValue;
                return modelValue;
            });

            ctrl.$parsers.push(function(viewValue) {
                if (viewValue != "") {
                    if (viewValue.length >= 4) {
                        var url = "../api/player/CheckDuplicateName";
                        var param = {
                            MemberID: viewValue
                        }
                        CsrfToken.HttpRequest('POST', url, param).success(function(data) {
                            if (data.result == 0) {
                                ctrl.$setValidity('duplicated', true);
                            } else {
                                ctrl.$setValidity('duplicated', false);
                            }
                        });
                        return viewValue;
                    } else {
                        ctrl.$setValidity('duplicated', false);
                        return viewValue;
                    }
                } else {
                    ctrl.$setValidity('duplicated', true);
                    ctrl.$setPristine();
                    return viewValue;
                }
            })
        }
    };
});

app.directive('format', ['$filter', function($filter) {
    return {
        require: '?ngModel',
        link: function(scope, elem, attrs, ctrl) {
            if (!ctrl) return;

            ctrl.$formatters.unshift(function(a) {
                if (attrs.format == "numberDecimal" || attrs.format == "number") {
                    return $filter("number")(ctrl.$modelValue)
                }

            });

            ctrl.$parsers.unshift(function(viewValue) {
                if (viewValue == "NaN") return 0;
                if (attrs.format == "numberDecimal") {
                    var plainNumber = viewValue.replace(/[^\d|\-+|\d\.\d|\d\.+]/g, '');
                    if (viewValue.slice(-1) != ".") {
                        elem.val($filter("number")(plainNumber));
                    }
                } else if (attrs.format == "number") {
                    var plainNumber = viewValue.replace(/[^\d|\-+|\.+]/g, '');
                    elem.val($filter("number")(plainNumber));
                }
                return plainNumber;
            });
        }
    };
}]);

app.controller('CommonController', function(CsrfToken, $filter, $scope, $rootScope, $window, $http, loggedInStatus, $interval, ngDialog, $cookies, $sce, ccCurrencySymbol, browser, $route, SweetAlert, $location, $timeout, $translate, $routeParams) {
    $scope.gamebuttons = [{
            'category': 'casino'
        },
        {
            'category': 'powerball'
        },
        {
            'category': 'slot'
        },
        {
            'category': 'mini-games'
        },
        {
            'category': 'sports'
        },
        {
            'category': 'jackpot'
        },
    ]
    $scope.walletCategory = [{
            "category": "Casino",
            'categoryKrw': '카지노 ',
            "target": "live-wallet",
            "img": "live"
        },
        {
            "category": "Power Ball",
            'categoryKrw': '파워볼',
            "target": "powerball-wallet",
            "img": "powerball"
        },
        {
            "category": "Slot Games",
            'categoryKrw': '슬롯 ',
            "target": "slots-wallet",
            "img": "slots"
        },
        {
            "category": "Mini Game",
            'categoryKrw': '미니게임 ',
            "target": "mini-wallet",
            "img": "mini"
        },
        // {"category": "Sports Betting",'categoryKrw':'스포츠', "target": "sports-wallet", "img": "sports"},
    ];
    $rootScope.userCurrency = "KRW";
    $rootScope.cc_currency_symbol = ccCurrencySymbol;
    $rootScope.agentGspList = [];

    $rootScope.announceList = {};
    $rootScope.realTimeTransactionList = [];
    $rootScope.readTitle = "";
    $rootScope.readDate = "";
    $rootScope.readContents = "";
    $rootScope.totalBalance = "Loading";
    $scope.getNotice = false;
    $scope.isProcessing = false;

    $rootScope.techContactNumber = "";
    $rootScope.bankContactNumber = "";

    $scope.tech = "";
    $scope.bank = "";
    $scope.email = "";
    $scope.messenger1 = "";
    $scope.messenger2 = "";
    $scope.aff_user = "";

    $scope.$on('IdleStart', function() {
        // the user appears to have gone idle
        if ($rootScope.loggedIn) {
            $rootScope.logoutToken();
        }
    });

    $scope.isTokenExpired = false;

    let debounce;
    $('html body').click(function(e) {
        if ($rootScope.checkLogoutStatus) {
            return e.preventDefault();
        }
        if ($rootScope.loggedIn && !$scope.isTokenExpired) {
            clearTimeout(debounce);
            debounce = setTimeout(
                function() {
                    $rootScope.CheckMemberToken()
                }, 1000
            );
        }
    });

    $rootScope.CheckMemberToken = function() {
        var url = '/api/player/CheckMemberToken';
        $http.get(url).success(function(data) {
            if (data.result == 201) {
                $scope.isTokenExpired = true;
                swal(data.message, "", "error").then(function(confirm) {
                    if (confirm) {
                        $rootScope.logoutToken();
                    }
                });
            }
        });
    }

    $rootScope.logoutToken = function() {
        $http.get("/api/player/Logout").success(function(data) {
            if (data.result == 1) {
                if (bowser.msie && bowser.version <= 8) {
                    alert("로그아웃 되었습니다");
                } else {
                    SweetAlert.swal("로그아웃 되었습니다", "", "success");
                }
                loggedInStatus.setLoggedOutStatus();
                $window.location.reload();
            } else {
                loggedInStatus.setLoggedOutStatus();
                $window.location.reload();
            }
        });
    }

    // !!! Unique id required
    $scope.multiplePopup = [{
            id: 'mp-04',
            image: 'popup-banner-06.png'
        },
        {
            id: 'mp-03',
            image: 'popup-banner-4.png'
        },
        {
            id: 'mp-02',
            image: 'popup-banner-2.png'
        },
        {
            id: 'mp-01',
            image: 'popup-banner-1.jpg'
        },


    ];

    function getNotTodayCookie(listArr) {
        // Get popups that are in cookie list
        var cookiesArr = listArr.map(function(list) {
            if ($cookies.get('notToday-' + list.id)) {
                return list.id
            }
        });

        // Filter to remove empty elements from array
        return cookiesArr.filter(function(x) {
            return x
        });
    }

    $scope.displayMultiplePopups = function(listArr) {
        if (!listArr) {
            return
        }
        // Check if all popups are in the cookie
        if (getNotTodayCookie(listArr).length === listArr.length) {
            return
        }

        ngDialog.open({
            template: 'popup/multiple-popup.html',
            controller: 'MultiplePopupController',
            className: 'ngdialog-theme-default ngdialog-mulitple-popup',
            showClose: false,
            closeByEscape: false,
            closeByDocument: false,
            scope: $scope,
            data: {
                lists: listArr
            },
            preCloseCallback: function() {
                window.scrollTo(0, 0);
            }
        });
    };

    $rootScope.readBoardContent = function(type, announceNo, comment, index) {
        if (comment == undefined) {
            comment = false;
        }

        $rootScope.readCommentDate = "";
        $rootScope.readComment = "";
        $rootScope.readBoardCode = "";
        $rootScope.readBoardCode = "";
        $rootScope.readCount = "";
        $rootScope.readTitle = "";
        $rootScope.readDate = "";
        $rootScope.readContents = "";

        if (index) {
            $rootScope.readCommentDate = [];
            $rootScope.readComment = [];
            $rootScope.readBoardCode = [];
            $rootScope.readCount = [];
            $rootScope.readTitle = [];
            $rootScope.readDate = [];
            $rootScope.readContents = [];
        }
        var url = "/api/operation/GetBoardDescription";
        var param = {
            "type": type,
            "code": announceNo,
            "comment": comment
        }
        CsrfToken.HttpRequest('POST', url, param)
            .success(function(data) {
                if (data.csrf) {
                    swal({
                        title: "캐시 및 쿠키 삭제 후",
                        text: "브라우저를 새로고침해 주세요",
                        className: "csrf-swal",
                        icon: "error",
                        showCancelButton: false,
                        confirmButtonClass: "#7cd1f9",
                        confirmButtonText: "OK",
                        closeOnConfirm: false
                    })
                } else {
                    if (index) {
                        $rootScope.readBoardCode[index] = data.content.BoardCode;
                        $rootScope.readCount[index] = data.content.ViewCount;
                        $rootScope.readTitle[index] = data.content.Subject;
                        $rootScope.readDate[index] = data.content.WriteDate;
                        $rootScope.readContents[index] = $sce.trustAsHtml(data.content.Contents);
                    } else {
                        $rootScope.readBoardCode = data.content.BoardCode;
                        $rootScope.readCount = data.content.ViewCount;
                        $rootScope.readTitle = data.content.Subject;
                        $rootScope.readDate = data.content.WriteDate;
                        $rootScope.readContents = $sce.trustAsHtml(data.content.Contents);
                        if (comment) {
                            $rootScope.readCommentDate = data.content['comment'][0].WriteDate;
                            $rootScope.readComment = data.content['comment'];
                        }
                    }
                }
            }).error(function(data, status) {
                console.error('Repos error', status, data);
            })["finally"](function() {
                $rootScope.isRead = true;
            });
    };

    $rootScope.restrictGamePage = function() {
        var params = $routeParams.page;
        if ((params !== 'sports' && params !== 'slot' && params !== 'casino' && params !== 'mini-game') && params) {
            return location.href = '/mobile/#/game-lobby';
        }
    };

    /*START SLOTS*/
    $rootScope.setActiveTabSlots = function(tab) {
        alert(tab)
        $scope.tab = tab;
    };

    $rootScope.goBack = function() {
        window.history.back();
    };

    $rootScope.setActiveSlot = function(gspNo, gspName) {
        $cookies.put('gspName', gspName);
        $cookies.put('gspNo', gspNo);

        $rootScope.activeGspNo = gspNo;
        $rootScope.gspName = gspName;
    };



    $scope.formatDate = function(date) {
        if (date) {
            var date = date.split("-").join("/");
            var dateOut = new Date(date);
            return dateOut;
        }
    };

    $scope.setLang = function(langKey) { //selected language
        $rootScope.currentLang = langKey;
        $cookies.get("selectedLanguage");
        var now = new Date(),
            // this will set the expiration to 12 months
            exp = new Date(now.getFullYear() + 100, now.getMonth(), now.getDate());
        // Setting a cookie
        $cookies.put("selectedLanguage", langKey, {
            expires: exp
        });
        $rootScope.languageSelector(langKey);
        $translate.use(langKey);
    };

    $rootScope.languageSelector = function(langKey) {
        var font = 'kr.css';
        var langName = " 한국어";
        var lang = langKey.substring(0, 2);

        if (lang == "en") {
            font = "en.css";
            langName = " ENGLISH";
        } else if (lang == "ko") {
            font = "kr.css";
            langName = " 한국어";
        } else if (lang == "ja") {
            font = "jp.css";
            langName = " 日本語";
        } else if (lang == "zh") {
            font = "cn.css";
            langName = " 中文";
        } else if (lang == "id") {
            font = "id.css";
            langName = " INDONESIA";
        } else {
            font = "kr.css";
            langName = " 한국어";
        }

        $('#language-flag').html('<i class="icon-lang language-' + langKey.substring(0, 2) + '"></i><span>' + langName + '</span><span class="caret"></span>');

        var fileref = document.createElement("link");
        fileref.setAttribute("rel", "stylesheet");
        fileref.setAttribute("href", "/common/css/languages/" + font);

        if (typeof fileref != "undefined") {

            $("link[href*='/common/css/languages/kr.css']").remove();
            $("link[href*='/common/css/languages/jp.css']").remove();
            $("link[href*='/common/css/languages/cn.css']").remove();
            $("link[href*='/common/css/languages/en.css']").remove();
            $("link[href*='/common/css/languages/id.css']").remove();

            document.getElementsByTagName("head")[0].appendChild(fileref);
        }
        return font;
    };

    $scope.$route = $route;
    $scope.aff_user = "";
    $scope.checkSession = function() {
        $http.get("/api/player/GetNewMemberInfo")
            .success(function(data) {
                /*WILL DISABLE IF HAS DATA*/
                $scope.aff_user = data.bonus.AffUser;
                $rootScope.UnreadDM = data.bonus.UnreadDM;

                if ($rootScope.agentGspList == undefined) {
                    $scope.getBalance();
                }

                if ($rootScope.agentGspCount != data.GameList.count) {
                    $rootScope.agentGspList = data.GameList.data;
                    $rootScope.agentGspCount = data.GameList.count;
                }

                $scope.getBalance();
                if (data.result != 1) {
                    //console.log("error"+data);
                    if (data.result == 207) {
                        //console.log("error207"+data);
                        $http.get("/api/player/Logout").success(function(data) {
                            if (data.result == 1) {
                                if (bowser.msie && bowser.version <= 8) {
                                    alert("로그아웃 되었습니다");
                                } else {
                                    SweetAlert.swal("로그아웃 되었습니다", "", "success");
                                }
                                loggedInStatus.setLoggedOutStatus();
                                $window.location.reload();
                            } else {
                                loggedInStatus.setLoggedOutStatus();
                                $window.location.reload();
                            }
                        });
                    }
                } else {
                    if (data.alert) {
                        if (bowser.msie && bowser.version <= 8) {
                            alert(data.message);
                        } else {
                            SweetAlert.swal(data.message, "", "error");
                        }
                    }
                }
            }).error(function(data, result) {
                console.error('Repos error', result, data);
            })["finally"](function() {
                $scope.isProcessing = false;
            });
    };

    $scope.stopAlert = false;
    $scope.clickedBody = false;

    $scope.DMPlayAlert = function() {
        $scope.audio = new Audio('/common/audio/ko_KR.mp3');
        $scope.playAudioPromise = function() {
            var promise = $scope.audio.play();
            if (promise !== undefined) {
                promise.then(function() {
                    // Autoplay started!
                }).catch(function(error) {
                    // Autoplay was prevented.
                });
            }
        };

        if ($rootScope.UnreadDM > 0) {
            $scope.dialogId;
            $scope.$on('ngDialog.opened', function(e, $dialog) {
                if ($dialog.name == 'customer_dialog') {
                    $scope.dialogId = $dialog.dialog[0].id;
                }
            });
            if (!ngDialog.isOpen($scope.dialogId)) {
                //$scope.displayDirectMessage(); //UNCOMMENT THIS TO POPUP NEW MESSAGE
            }
        }

        //FOR ANDROID
        var ua = navigator.userAgent.toLowerCase();
        var isAndroid = ua.indexOf("android") > -1; //&& ua.indexOf("mobile");
        if (isAndroid) {
            $scope.playAudioPromise();
        }

        //FOR IOS
        var iOS = !!navigator.platform && /iPad|iPhone|iPod/.test(navigator.platform);
        $("body").click(function() {
            if (iOS) {
                if (!$scope.clickedBody) {
                    $scope.playAudioPromise();
                }
                $scope.clickedBody = true;
            }
        });
        if ($scope.clickedBody && iOS) {
            $scope.playAudioPromise();
        }

        //PLAY AUDIO EVERY MINUTE UNLESS STOP
        setInterval(function() {
            if ($scope.stopAlert == false) {
                $scope.playAudioPromise();
            }
        }, 60000)
    };

    $scope.DMStopAlert = function() {
        if (!$scope.audio) {
            return;
        }
        $scope.stopAlert = true;
        $scope.audio.pause();
    };

    $rootScope.anouncementPopup = [{}];
    $scope.counter = 1;

    $rootScope.displayNoticeToday = function() {
        angular.forEach($rootScope.announceList, function(val) {
            if (val.PopUp == 'y' && !$scope.getNotice) {
                $rootScope.anouncementPopup = val;
                $scope.getNotice = true;
                if (!$cookies.get('notToday')) {
                    ngDialog.open({
                        template: 'popup/notice.php',
                        controller: 'NoticeController',
                        className: 'ngdialog-theme-default ngdialog-notice',
                        showClose: true,
                        closeByEscape: false,
                        closeByDocument: false,
                        scope: $scope
                    });
                }
            }
        });
    }

    //POPUP multiple
    /*  $http.get("../api/operation/GetBoardDetail?type=1&page=1")
        .success(function (data) {
          angular.forEach(data.list, function (val) {
            if (val.PopUp == 'y') {

              if (!$cookies.get('notToday-'+val.BoardCode)) {
                $rootScope.anouncementPopup.push(val);
                ngDialog.open({
                  template: 'popup/notice.php',
                  controller: 'NoticeController',
                  showClose:false,
                  className: 'ngdialog-theme-default ngdialog-custom ngdialog-notice',
                  scope: $scope,
                  data: {
                    index: $scope.counter++,
                    id: val.BoardCode
                  }
                });
              }
            }
          });
          $rootScope.announceList = data.list;

        }).error(function (data, result) {
        console.error('Repos error', result, data);
      })["finally"](function () {
      });*/

    $scope.ShowDirectMessageDetail = function(DirectMessage, index) {
        $rootScope.activeDMIndex = index;
        $rootScope.DMHideNew[DirectMessage.DMStatusIDX] = true;
        $rootScope.showDirectMessageValues = DirectMessage;

        $http.get("/api/operation/GetDirectMessageDetail?code=" + DirectMessage.DMStatusIDX)
            .success(function(data) {
                $rootScope.UnreadDM = data.list.UnreadDM;
                $rootScope.DMReadDate[DirectMessage.DMStatusIDX] = data.list.DMReadDate;
            }).error(function(data, result) {
                console.error('Repos error', result, data);
            })
    };

    $rootScope.getBalance = function() {
        if (!$scope.isProcessing) {
            $scope.isProcessing = true;
            var url = "/api/finance/CheckMemberBalanceAsync";
            CsrfToken.HttpRequest('GET', url, '')
                .success(function(data) {
                    //console.log(data);
                    angular.forEach($rootScope.agentGspList, function(val) {
                        if (data.list[val.gspNo] != undefined) {
                            val.amount = data.list[val.gspNo].Balance;
                        } else {
                            val.amount = 0;
                        }
                        //console.log(val.amount);
                    });
                    $rootScope.totalBalance = data.list['All'].Balance;
                }).error(function(data, result) {
                    console.error('Repos error', result, data);
                })["finally"](function() {
                    $scope.isProcessing = false;
                });
        }
    };

    $scope.playGame = function(gspNo, productType, gameId, isDownload, theme, gameName) { // mobile
        if (gspNo == 1052 && productType == 'slot') {
            theme = 5;
        }
        if ($rootScope.loggedIn) {
            var url = "";
            var size = "";
            var target = "";
            if (productType == "sports") {
                url = "../api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId;
                size = "";
                target = "_blank";
                if (productType == "sports" && gspNo == 1016 && (browser() == "safari" && !navigator.userAgent.match('CriOS'))) {
                    ngDialog.open({
                        template: '/mobile/popup/sportsSafariDisable.php',
                        className: 'ngdialog-theme-default ngdialog-notice',
                        showClose: false,
                        scope: $scope,
                        closeByEscape: false
                    });
                    return 0;
                } else if (productType == "slot" && gspNo == 1005 && (browser() == "safari" && navigator.userAgent.match('CriOS'))) {
                    ngDialog.open({
                        template: '/mobile/popup/slotsChromeDisable.php',
                        className: 'ngdialog-theme-default ngdialog-notice',
                        showClose: false,
                        scope: $scope,
                        closeByEscape: false
                    });
                    return 0;
                }
            } else if (productType == "live" || productType == "Ltlottery") {
                if (gspNo == 1012) {
                    if (isDownload == true) {
                        url = "http://agmbet.com";
                        size = "";
                        target = "_blank";
                    } else {
                        url = "../api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType;
                        size = "";
                        target = "_blank";
                    }
                } else if (gspNo == 1005 || gspNo == 1112) {
                    if (gameId == "20861" || gameId == "70737" || gameId == "70585" || gameId == '70581' || gameId == '1921') {
                        url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId;
                        size = "";
                        target = "_blank";
                    } else {
                        url = "http://mobile-resigner.valueactive.eu/launch88livedealer/apk?btag1=79633831&btag3=001";
                        size = "";
                        target = "_blank";
                    }
                } else if (gspNo == 1023 || gspNo == 1040) {
                    url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType;
                    size = "";
                    target = "_blank";
                } else if (gspNo == 1027) {
                    if (isDownload == true) {
                        url = "http://casino.gpiops.com/mob/download.html?op=APLUS&id=014";
                        size = "";
                        target = "_blank";
                    } else {
                        url = "../api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType;
                        size = "";
                        target = "_blank";
                    }
                } else if (gspNo == 1049 || gspNo == 1055 || gspNo == 1038 || gspNo == 1040 || gspNo == 1064 || gspNo == 1142 || gspNo == 1088 || gspNo == 1097 || gspNo == 1004 || gspNo == 1006) {
                    url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId;
                    size = "";
                    target = "_blank";
                } else if (gspNo == 1058) {
                    url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId + "&gameType=" + theme;
                    size = "";
                    target = "_blank";
                } else if (gspNo == 1023 || gspNo == 1051 || gspNo == 1070 || gspNo == 1069) {
                    url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType;
                    size = "";
                    target = "_blank";
                } else if (gspNo == 1052) {
                    url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId + "&gameType=" + theme;
                    size = "";
                    target = "_blank";
                } else {
                    url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId;
                    size = "";
                    target = "_blank";
                }
            } else if (productType == "slot" || productType == "game" || productType == "minigame") {
                if (gspNo == 1005) {
                    if (isDownload == true) {
                        url = "http://mobile-resigner.valueactive.eu/mobilecasino/download?btag1=79633831&btag2=&btag3=001";
                        size = "";
                        target = "_blank";
                    } else {
                        url = "../api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId;
                        size = "width=1024, height=592";
                        target = "_blank";
                    }
                } else if (gspNo == 1098) {
                    url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId + "&GameName=" + gameName;
                    size = "width=1024, height=592";
                } else if (gspNo == 1052) {
                    url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId + "&gameType=" + theme;
                    size = "width=1024, height=592";
                    target = "_blank";
                } else {
                    url = "../api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId;
                    size = "width=1024, height=592";
                    target = "_blank";
                }
            } else if (gspNo == 1073) {
                url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId;
                size = "width=840, height=1150";
            } else if (productType == "etc") {
                url = "../api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType;
                size = "width=1024, height=682";
            }

            if (productType == 'slot' && gspNo != 1005) {
                target = gspNo + Math.random();
            } else if (productType == 'playCheck' && gspNo == 1005) {
                target = gspNo + "playCheck";
            } else {
                // target = gspNo;
            }

            if (productType == 'slot') {
                target = productType;
            }

            var popupWindow = $window.open(url, target, size);
            popupWindow.focus();

            if (gspNo == 1150 || gspNo == 1153) {
                var pollTimer = window.setInterval(function() {
                    if (popupWindow.closed !== false) { // !== is required for compatibility with Opera
                        window.clearInterval(pollTimer);
                        CallCloseSession();
                    }
                }, 200);
            }

            function CallCloseSession() {
                window.setTimeout(function() {
                    if (popupWindow.closed) {
                        var closeUrl = url.concat("&isClose=true");
                        $http.get(closeUrl).success(function(data) {
                            SendCloseSession(data.MemberId, data.SessionId, data.GameCode)
                        });
                    }
                }, 1);
            }

        } else {
            $scope.displayLogin();
        }
    };

    function SendCloseSession(memberId, sessionId, gspCode) {

        var url = isDevelopUrl() ? "https://dev.aplus-api.com/api/close/session" : "http://v2.aplus-api.com/api/close/session";
        var param = "?memberId=" + memberId + "&sessionId=" + sessionId + "&gspCode=" + gspCode;
        url = url.concat(param);
        $http.get(url).success(function(data) {
            console.log(data)
        });

    }

    function isDevelopUrl() {
        var server = window.location.hostname;
        var pattern = /[a-z0-9]+\.develop88\.com/;

        return pattern.test(server);
    }


    // preload banner images first before showing screen
    function preloadImages(urls, allImagesLoadedCallback) {
        var loadedCounter = 0;
        var toBeLoadedNumber = urls.length;
        urls.forEach(function(url) {
            preloadImage(url, function() {
                loadedCounter++;
                if (loadedCounter == toBeLoadedNumber) {
                    allImagesLoadedCallback();
                }
            });
        });

        function preloadImage(url, anImageLoadedCallback) {
            var img = new Image();
            img.src = url;
            img.onload = anImageLoadedCallback;
        }
    }


    $rootScope.init = function(isLogin) {
        $scope.displayMultiplePopups($scope.multiplePopup);
        $scope.setLang('ko_KR');
        if (isLogin) {
            loggedInStatus.setLoggedInStatus();
            $scope.checkSession();
            $interval(function() {
                $scope.checkSession();
            }, 60000); //1min
        }

        setTimeout(function() {
            setTimeout(function() {
                $('.wrapper').fadeIn();
            }, 500);
            $('.custom-loader-container').fadeOut(500);
        }, 1000)
    };

    $rootScope.closeDialog = function() {
        ngDialog.close();
    };

    $rootScope.comingSoon = function() {
        if (bowser.msie && bowser.version <= 8) {
            alert("준비중입니다");
        } else {
            SweetAlert.swal("준비중입니다", "", "info");
        }
    };
    $rootScope.pcVersion = function() {
        SweetAlert.swal("PC버전에서 제공하는 게임입니다");
    };
    $scope.displayWallet = function() {
        ngDialog.open({
            template: 'popup/wallet.php',
            className: 'ngdialog-theme-default ngdialog-custom ngdialog-wallet',
            showClose: false,
            closeByEscape: false,
            scope: $scope
        });
    };

    $scope.displayTransfer = function() {
        ngDialog.open({
            template: 'popup/transfer.php',
            className: 'ngdialog-theme-default ngdialog-custom',
            showClose: false,
            closeByEscape: false,
            scope: $scope
        });
    };

    $rootScope.contactCC = function() {
        if (bowser.msie && bowser.version <= 8) {
            alert("고객센터로 문의 바랍니다");
        } else {
            SweetAlert.swal("고객센터로 문의 바랍니다", "", "info");
        }
    };

    $scope.displayDeposit = function() {
        if ($rootScope.loggedIn) {
            ngDialog.open({
                template: 'popup/deposit.php',
                className: 'ngdialog-theme-default ngdialog-custom',
                showClose: false,
                closeByEscape: false,
                scope: $scope
            });
        } else {
            $scope.displayLogin();
        }
    };

    $scope.displayWithdraw = function() {
        if ($rootScope.loggedIn) {
            ngDialog.open({
                template: 'popup/withdraw.php',
                className: 'ngdialog-theme-default ngdialog-custom',
                showClose: false,
                closeByEscape: false,
                scope: $scope
            });
        } else {
            $scope.displayLogin();
        }
    };

    $scope.displayBonus = function() {
        ngDialog.open({
            template: 'popup/bonus.php',
            className: 'ngdialog-theme-default ngdialog-custom',
            showClose: false,
            closeByEscape: false,
            scope: $scope
        });
    };
    $scope.displayCustomerService = function() {
        if (!$rootScope.loggedIn) {
            return $scope.displayLogin();
        }
        ngDialog.open({
            template: 'popup/customer-center.php',
            className: 'ngdialog-theme-default ngdialog-custom',
            showClose: false,
            closeByEscape: false,
            scope: $scope,
        });
    };

    $scope.displayFriends = function() {
        ngDialog.open({
            template: 'popup/friends.php',
            className: 'ngdialog-theme-default ngdialog-custom',
            showClose: false,
            closeByEscape: false,
            scope: $scope
        });
    };

    $scope.displayCoupon = function() {
        ngDialog.open({
            template: 'popup/coupon.php',
            className: 'ngdialog-theme-default ngdialog-custom',
            showClose: false,
            closeByEscape: false,
            scope: $scope
        });
    };

    $scope.displayHistory = function() {
        ngDialog.open({
            template: 'popup/history.php',
            className: 'ngdialog-theme-default ngdialog-custom',
            showClose: false,
            closeByEscape: false,
            scope: $scope
        });
    };

    $scope.displayAccount = function() {
        ngDialog.open({
            template: 'popup/account.php',
            className: 'ngdialog-theme-default ngdialog-custom',
            showClose: false,
            closeByEscape: false,
            scope: $scope
        });
    };

    $scope.displayLogin = function() {
        ngDialog.open({
            template: 'popup/login.php',
            controller: 'LoginController',
            className: 'ngdialog-theme-default ngdialog-login',
            showClose: false,
            closeByEscape: false,
            scope: $scope
        });
    };
    $scope.displayAffiliates = function() {
        if (!$rootScope.loggedIn) {
            return $scope.displayLogin();
        }
        ngDialog.open({
            template: 'popup/affiliates.php',
            className: 'ngdialog-theme-default ngdialog-custom ngdialog-affiliates',
            showClose: false,
            closeByEscape: false,
            scope: $scope,
        });
    };
    $scope.displayFaq = function() {
        if (!$rootScope.loggedIn) {
            return $scope.displayLogin();
        }
        ngDialog.open({
            template: 'popup/faq.php',
            className: 'ngdialog-theme-default ngdialog-custom ngdialog-affiliates',
            showClose: false,
            closeByEscape: false,
            scope: $scope,
        });
    };
    $scope.displayPromotionMobile = function() {
        if (!$rootScope.loggedIn) {
            $scope.displayLogin();
            return
        }
        ngDialog.open({
            template: '/mobile/popup/promotion.php',
            className: 'ngdialog-theme-default ngdialog-customer-page',
            showClose: false,
            closeByEscape: false,
            scope: $scope,
        });
    }

    $scope.displaySignUp = function() {
        ngDialog.open({
            template: 'popup/signup.php',
            controller: 'SignUpController',
            className: 'ngdialog-theme-default ngdialog-direct',
            showClose: false,
            closeByEscape: false,
            scope: $scope
        });
    };

    $scope.displayDirectMessage = function() {
        ngDialog.open({
            template: 'popup/direct_message.php',
            controller: 'SignUpController',
            className: 'ngdialog-theme-default ngdialog-custom',
            showClose: false,
            closeByEscape: false,
            scope: $scope
        });
    };

    $scope.openPlaytech = function() {
        ngDialog.open({
            template: 'popup/playtech.php',
            controller: '',
            className: 'ngdialog-theme-default ngdialog-playtech',
            showClose: true,
            closeByEscape: false,
            scope: $scope
        });
    };

    $scope.openMicrogaming = function() {
        ngDialog.open({
            template: 'popup/micro.php',
            controller: '',
            className: 'ngdialog-theme-default ngdialog-playtechHTML5',
            showClose: true,
            closeByEscape: true,
            scope: $scope,
        });
    };

    $scope.displayPromotion = function(seTab) {
        $rootScope.customerTab = seTab;
        ngDialog.open({
            template: 'popup/customer-center.php',
            controller: 'CustomerController',
            className: 'ngdialog-theme-default ngdialog-promotion ngdialog-direct',
            showClose: false,
            closeByEscape: true,
            scope: $scope,
        });
    };

    $scope.displayNotice = function() {
        ngDialog.open({
            template: 'popup/customer-notice.php',
            controller: 'CustomerController',
            className: 'ngdialog-theme-default ngdialog-promotion ngdialog-direct',
            showClose: false,
            closeByEscape: true,
            scope: $scope,
        });
    };

    $scope.displayContact = function() {
        ngDialog.open({
            template: 'popup/customer-contact.php',
            controller: 'CustomerController',
            className: 'ngdialog-theme-default ngdialog-promotion ngdialog-direct',
            showClose: false,
            closeByEscape: true,
            scope: $scope,
        });
    };


    $rootScope.loadCounter = function() {
        $http.get("/api/marketing/getJackpot")
            .success(function(data) {
                $('.jackpot-odometer').jOdometer({
                    increment: data.increment,
                    counterStart: data.counterStart,
                    counterEnd: false,
                    numbersImage: 'common/images/odometer/odometer-small.png',
                    spaceNumbers: -1,
                    formatNumber: true,
                    widthNumber: 20,
                    heightNumber: 44
                });
            }).error(function(data, status) {
                console.error('Repos error', status, data);
            });
    };
    $scope.displaySettlement = function() {
        if (!$rootScope.loggedIn) {
            $scope.displayLogin();
            return
        }
        ngDialog.open({
            template: 'popup/settlement.php',
            controller: '',
            className: 'ngdialog-theme-default ngdialog-settlement',
            showClose: true,
            closeByEscape: false,
            scope: $scope
        });
    };
    $scope.displayGames = function(category) {
        $scope.activeCategory = category;
        ngDialog.open({
            template: 'popup/gamespopup.php',
            controller: '',
            className: 'ngdialog-theme-default ngdialog-games',
            showClose: false,
            closeByEscape: false,
            scope: $scope,

        });
    };
    $scope.isProcessing = false;

    $scope.combinedMaintenance = function(agentGsp) {
        if (agentGsp.amount == '점검중') {
            return agentGsp.gspName + " - 점검중";
        } else {
            return agentGsp.gspName;
        }
    };

    $scope.combinedMaintenanceByCurrency = function(agentGsp) {
        if (agentGsp.Maintenance == 'Y') {
            return false;

        } else {
            return true;
        }
    };

    $scope.maintenanceFilter = function(agentGsp) {
        if (agentGsp.amount == '점검중') {
            return false;
        } else {
            return true;
        }
    };

    $scope.translateWalletCategory = function(agentGsp) {
        return $filter('translate')(agentGsp.category);
    };

    $scope.setActiveTab = function(tab) {
        $scope.tab = tab;
    }
    $scope.preparePlayGame = function(gspNo, category, gameId, gspName) {
        if (category == 'live' && gspNo == 1058) {
            return $scope.playGame(gspNo, 'live', gameId);
        }

        if (category == 'live') {
            if (gameId == 'mansion88') {
                return $scope.comingSoon2();
            }
            if (gameId == 'playtech') {
                return $scope.openHTML5Playtech();
            }
            if (gameId == '1930') {
                // return $scope.openMicrogaming();
                return $scope.playGame('1112', 'live', '1921')
            }
            return $scope.playGame(gspNo, 'live');
        } else if (category == 'slot') {
            $(".wrapper").animate({
                scrollTop: 0
            }, 600);

            if (gspNo == 1071) {
                $('.gsp-content-wrapper').css("display", "block");
                $('.slot-games-container').css("display", "none");
                $scope.playGame(gspNo, 'game');
                return
            }
            $('.gsp-content-wrapper').css("display", "none");
            $('.slot-games-container').css("display", "block");
            $rootScope.loadSlot(gspNo);
            $rootScope.gspName = gspName;

            return $rootScope.activeSlotGsp = gspNo;
        } else if (category == 'sports') {
            ngDialog.close();
            return $scope.playGame(gspNo, 'sports');
        } else if (category == 'Others' && gspNo == 1020) {
            return $scope.playGame(gspNo, 'live', '10001');
        } else if (category == 'Others') {
            return $scope.playGame(gspNo, 'live', gameId);
        }
    };
    $http.get("/api/system/gamelist/gamebuttons.json")
        .success(function(data) {
            $rootScope.gameButtons = data;
        });
    $scope.setActiveCategory = function(category) {
        $('.slots-view-page').css("display", "none");
        $(".games-popup-container").css("display", "none");
        setTimeout(function() {
            $(".games-popup-container").css("display", "flex");
        }, 200);
        $scope.activeCategory = category;
    };
    $scope.gameButtonFilter = function(category) {
        if (category.category == $scope.activeCategory || category.category2 == $scope.activeCategory || category.category3 == $scope.activeCategory) {
            return true;
        }
    };
    $scope.launchGame = function(gspNo, category, gameId, gspName, theme, gameType) {
        if (category == "Others" && gspNo == 1052) {
            return $scope.playGame(gspNo, 'Ltlottery', '', '', '12');
        }

        if (gspNo == 1070) {
            return $rootScope.comingSoon();
        }
        if (gspNo == 1064 && category == "Others") {
            return $scope.playGame('1064', 'live', 'black')
        }
        if (category == "Others" && gameId == "6") {
            return $scope.playGame(gspNo, 'fish', gameId);
        }
        if (category == "Others" && gameId == "110") {
            return $scope.playGame(gspNo, 'live', gameId);
        }
        if (category == "Others" && gspNo != 1070 && gspNo != 1020 && gspNo != 1012 && gspNo != 1048 && gspNo != 1004 && gspNo != 1006) {
            return $scope.playGame(gspNo, 'minigame', gameId, '');
        }
        if (category == "Others" && gspNo != 1020) {
            return $scope.playGame(gspNo, 'live', gameType);
        }
        if (category == 'live') {
            if (gameId == '1930') {
                // return $scope.microPopup();
                return $scope.playGame('1112', 'live', '1921');
            }
            if (gameId == 'mansion88') {

                return $scope.pcVersion();
            }
            return $scope.playGame(gspNo, 'live', gameId, '', theme);
        } else if (category == 'slot') {
            setTimeout(function() {
                $('.games-popup-container').css("display", "none");
                $('.slots-view-page').css("display", "block");
            })
            $rootScope.loadSlot(gspNo);
            $rootScope.gspName = gspName;
            return $rootScope.activeSlotGsp = gspNo;
        } else if (category == 'sports') {
            return $scope.playGame(gspNo, 'sports');

        } else if (category == 'Others' && gspNo == 1020) {
            return $scope.playGame(gspNo, 'live', '10001');
        }
    }
    $scope.getActiveCategory = function(category) {

        if (category == 'sports') {
            // return  $scope.displayGames('sports');
            return $scope.comingSoon();
        }
        if (category == "Others" && gameId == "1") {
            return $scope.playGame(gspNo, 'live', gameId);
        }
        if (category == 'mini-games') {
            return $scope.displayGames('Others');
        }
        if (category == 'powerball') {
            return $scope.playGame('1020', 'live', '10001');
        } else if (category == 'slot') {

            $('.games-popup-container').css("display", "none");
            $('.slots-view-page').css("display", "block");

            return $scope.displayGames('slot');
        }
    }
    $scope.showGameList = function(index) {
        $(".category.caret-" + index + " .caret").toggleClass("caret-up");
    }
    $rootScope.setActiveInside = function(setTab) {
        $rootScope.isActiveInside = setTab;
    };
    $rootScope.prepareRedirect = function(url, gspNo) {
        if (!url) {
            $rootScope.comingSoon();
        }
        if (url == 'comp') {
            return $scope.displayBonus();
        }
        if (url == 'comph') {
            return $scope.displayCoupon();
        }
        if (url == 'affiliates') {
            return $scope.displayAffiliates();
        }
        if (url == 'faq') {
            return $scope.displayFaq();
        }
        if (url == 'promotion') {
            return $scope.displayPromotionMobile()
        }
        if (url == 'notice') {
            return $scope.displayNotice();
        }
        if (url == 'service-center') {
            return $scope.displayCustomerService();
        }
        if (url == 'deposit') {
            return $scope.displayDeposit();
        }
        if (url == 'withdraw') {
            return $scope.displayWithdraw();
        }
        if (url == 'transfer') {
            return $scope.displayTransfer();
        }
        if (url == 'logout') {
            return $rootScope.logout();
        }
        if (url == 'direct-message') {
            return $scope.displayDirectMessage();
        }
        if (url == 'service-center') {
            return $scope.displayCustomerService();
        }
        if (url == 'pc') {
            return
        }

        $location.path(url);
    };
    $scope.scrllToSlotNav = function(idx) {
        if ($scope.activeCategory == 'slot') {
            setTimeout(function() {
                var scrollTo = $('#div-' + idx).position().left;
                $('.slots-nav-container').animate({
                    'scrollLeft': scrollTo
                }, 500);
            }, 1000);
        }

    }
});

app.controller("LoginController", function(CsrfToken, $scope, $http, $window, SweetAlert) {
    $scope.loginForm = {};
    $scope.processForm = function() {
        if (!$scope.isProcessing) {
            $scope.isProcessing = true;
            var url = "../api/player/Login";
            CsrfToken.HttpRequest('POST', url, $scope.loginForm)
                .success(function(data) {
                    if (data.result == 1) {
                        $window.location.href = "/mobile/#/";
                        $window.location.reload();
                    } else {
                        if (bowser.msie && bowser.version <= 8) {
                            alert(data.message);
                        } else {
                            if (data.csrf) {
                                swal({
                                    title: "캐시 및 쿠키 삭제 후",
                                    text: "브라우저를 새로고침해 주세요",
                                    className: "csrf-swal",
                                    icon: "error",
                                    showCancelButton: false,
                                    confirmButtonClass: "#7cd1f9",
                                    confirmButtonText: "OK",
                                    closeOnConfirm: false
                                }).then(function(confirm) {
                                    if (confirm) {
                                        $window.location.href = "/#/";
                                        $window.location.reload();
                                    }
                                });
                            } else {
                                SweetAlert.swal(data.message, "다시 시도해보세요", "error");
                            }
                        }
                    }
                }).error(function(data, status) {
                    console.error('Repos error', status, data);
                })["finally"](function() {
                    $scope.isProcessing = false;
                });
        }
    };
});

app.controller("LogoutController", function($scope, $http, $window, SweetAlert, loggedInStatus) {
    $scope.isProcessing = false;
    $scope.logout = function() {
        $scope.isProcessing = true;
        $http.get("../api/player/Logout")
            .success(function(data) {
                if (data.result == 1) {
                    if (bowser.msie && bowser.version <= 8) {
                        alert("로그아웃 되었습니다");
                    } else {
                        SweetAlert.swal("로그아웃 되었습니다", "", "success");
                    }
                    loggedInStatus.setLoggedOutStatus();
                    $window.location.reload();
                } else {
                    if (data.alert) {
                        if (bowser.msie && bowser.version <= 8) {
                            alert(data.message);
                        } else {
                            SweetAlert.swal("로그아웃 되었습니다", "다시 시도해보세요", "error");
                        }
                    }
                }
            }).error(function(data, result) {
                console.error('Repos error', result, data);
            })["finally"](function() {
                $scope.isProcessing = false;
            });
    }
});

app.controller('NoticeController', function($rootScope, $scope, $cookies, $http, $window, SweetAlert) {
    $rootScope.writeQuestion = {};
    // multiple
    /*  $scope.notToday = function (index) {
        var expireDate = new Date();
        expireDate.setDate(expireDate.getDate() + 1);
        $cookies.put('notToday-'+index, 'true', {'expires': expireDate});
        $scope.closeThisDialog();
      };*/

    // notice 1
    $scope.notToday = function() {
        var expireDate = new Date();
        expireDate.setDate(expireDate.getDate() + 1);
        $cookies.put('notToday', 'true', {
            'expires': expireDate
        });
        $scope.closeThisDialog();
    };

    $scope.notTodayNotice = function() {
        var expireDate = new Date();
        expireDate.setDate(expireDate.getDate() + 1);
        $cookies.put('notToday-notice', 'true', {
            'expires': expireDate
        });
        $scope.closeThisDialog();
    };
    /* $scope.notTodayNotice2 = function () {
       var expireDate = new Date();
       expireDate.setDate(expireDate.getDate() + 1);
       $cookies.put('notToday-notice2', 'true', {'expires': expireDate});
       $scope.closeThisDialog();
     };*/

    $rootScope.processForm = function() {
        $rootScope.isProcessing = true;
        var url = "/api/operation/GetWriteBoard";
        $http({
            method: 'POST',
            url: url,
            data: $.param($rootScope.writeQuestion), // pass in data as strings
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            } // set the headers so angular passing info as form data (not request payload)
        }).success(function(data) {
            if (data.result == 1) {
                if (bowser.msie && bowser.version <= 8) {
                    alert(data.message);
                } else {
                    SweetAlert.swal(data.message, "", "success");
                }
            } else {
                if (data.alert) {
                    if (bowser.msie && bowser.version <= 8) {
                        alert(data.message);
                    } else {
                        SweetAlert.swal(data.message, "다시 시도해보세요", "error");
                    }
                }
            }
        }).error(function(data, result) {
            console.error('Repos error', result, data);
        })["finally"](function() {
            $rootScope.isProcessing = false;
        });
    }
});