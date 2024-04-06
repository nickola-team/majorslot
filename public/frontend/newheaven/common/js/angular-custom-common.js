var app = angular.module('casinoApp', [
    'ngRoute',
    'RouteData',
    'ngMessages',
    'ngDialog',
    'ngSweetAlert',
    'pascalprecht.translate',
    'ngCookies',
    'ngCurrencySymbol',
    'ui.bootstrap.pagination',
    'appComponents',
    'internationalPhoneNumber',
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

app.config(['$routeProvider', 'RouteDataProvider', '$translateProvider', function($routeProvider, RouteDataProvider, $translateProvider) {
    RouteDataProvider.applyConfig({
        bodyClass: 'bg-main'
    });
    RouteDataProvider.hookToRootScope(true);

    $translateProvider.useStaticFilesLoader({
        prefix: '/frontend/newheaven/common/js/resources/locale-',
        suffix: '.json'
    });

    //default load when user already selected language from dropdown
    var jsCookieLang = document.cookie;
    var arrayLang = ["ko_KR"];

    var i;
    for (i = 0; i < arrayLang.length; i++) {
        if (jsCookieLang.search(arrayLang[i]) > 0) {
            var setLang = arrayLang[i];
        }
    }

    var languageSelector = function(langKey) {
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
            font = "ko.css";
            langName = " 한국어";
        }

        $('#language-flag').html('<i class="icon-lang language-' + langKey.substring(0, 2) + '"></i><span>' + langName + '</span><span class="caret"></span>');

        var fileref = document.createElement("link");
        fileref.setAttribute("rel", "stylesheet");
        fileref.setAttribute("href", "/frontend/newheaven/common/css/languages/" + font);


        if (typeof fileref != "undefined") {
            document.getElementsByTagName("head")[0].appendChild(fileref);
        }

        return font;
    };

    if (setLang) {
        $translateProvider.preferredLanguage(setLang);

        if (setLang == "ko_KR") {
            $translateProvider.useLocalStorage(angular.lowercase(setLang.substring(3, 5)));
        } else {
            $translateProvider.useLocalStorage(setLang.substring(0, 2));
        }

        languageSelector(setLang);

    } else {
        //user default browser language
        var userLang = navigator.language || navigator.userLanguage; //for IE8 = navigator.userLanguage
        var u;
        for (u = 0; u < arrayLang.length; u++) {
            if (arrayLang[u].search(userLang.replace("-", "_")) == 0) {
                var browserLang = arrayLang[u].replace("-", "_");
            }
        }

        if (browserLang == undefined) {
            languageSelector(userLang);
            $translateProvider.preferredLanguage(userLang.substring(0, 2) + "_" + userLang.substring(3, 5).toUpperCase());
        } else {
            $translateProvider.preferredLanguage(browserLang);
            languageSelector(userLang);
        }
    }

    $translateProvider.useLocalStorage();
    $translateProvider.useSanitizeValueStrategy('escaped');

    $routeProvider
        .when('/', {
            RouteData: {
                bodyClass: 'bg-main'
            },
            templateUrl: 'pages/main.php',
            controller: '',
            activetab: 'main'
        })
        .when('/view-sports', {
            RouteData: {
                bodyClass: 'bg-viewsport'
            },
            templateUrl: 'pages/view-sports.php',
            controller: '',
            activetab: 'sports'
        })
        .otherwise({
            redirectTo: '/',
            RouteData: {
                bodyClass: 'bg-main'
            },
            templateUrl: '../pages/main.php',
            controller: ''
        });
}]);

app.config(['ngDialogProvider', function(ngDialogProvider) {
    ngDialogProvider.setDefaults({
        closeByEscape: false,
        showClose: true,
        ariaAuto: false,
        ariaRole: false,
        setOpenOnePerName: true
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

app.config(function($provide) {
    $provide.decorator('inputDirective', function($delegate, $log) {
        $log.debug('Hijacking input directive');
        var directive = $delegate[0];
        angular.extend(directive.link, {
            post: function(scope, element, attr, ctrls) {
                element.on('compositionupdate', function(event) {
                    element.triggerHandler('compositionend');
                })
            }
        });
        return $delegate;
    });
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

app.filter('htmlToPlaintext', function() {
    return function(text) {
        /*REMOVE HTML TAGS*/
        var contents = String(text).replace(/<[^>]+>/gm, '');
        /*REMOVE NBSP*/
        contents = String(contents).replace(/&nbsp;/g, " ");
        return text ? contents : '';
    };
});

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

app.filter("trustUrl", ['$sce', function($sce) {
    return function(recordingUrl) {
        return $sce.trustAsResourceUrl(recordingUrl);
    };
}]);


app.filter('unique', function() {
    return function(collection, keyname) {
        var output = [],
            keys = [];

        angular.forEach(collection, function(item) {
            var key = item[keyname];
            if (keys.indexOf(key) === -1) {
                keys.push(key);
                output.push(item);
            }
        });
        return output;
    };
});

app.directive("addAmountList", function() {
    return {
        link: function(scope, element, attrs) {
            scope.data = scope[attrs["addAmountList"]];
        },
        restrict: "A",
        template: "<button type='button' class='btn btn-drkgray btn-option' ng-repeat='item in data' ng-click='addAmount(item.price)'>{{item.price | number}} {{item.currency}}</button>"
    }
});

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
                        var url = "/frontend/newheaven/api/player/CheckDuplicateName";

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
            var url = "/api/player/CheckDuplicatePhone" ;
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
                        var url = "/frontend/newheaven/api/player/CheckDuplicateName";
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

app.controller("NavController", function($scope, $rootScope, $location, $route, $window) {
    $scope.isActive = function(viewLocation) {
        return viewLocation === $location.path();
    };
    $scope.$route = $route;
    $scope.getActiveClass = function(location) {
        $scope.setActiveClass = location;
    };

    $rootScope.gotoUrl = function(url) {
        if (!$rootScope.loggedIn && (url == "/tv" || url == 'graph')) {
            return $scope.displayLogin();
        }
        if (url == '/tv') {
            return $window.open("http://www.max-890.com/#/tv", "_blank");
        }
        $location.url(url);
    }
});

app.controller('CommonController', function(Idle, CsrfToken, $scope, $rootScope, $timeout, $window, $http, loggedInStatus, $interval, ngDialog, $cookies, $sce, SweetAlert, ccCurrencySymbol, $translate, $location, $filter) {
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
    $rootScope.emailContact = "";
    $rootScope.messengerContact1 = "";
    $rootScope.messengerContact2 = "";

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
        var url = '/frontend/newheaven/api/player/CheckMemberToken';
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
        $http.get("/frontend/newheaven/api/player/Logout").success(function(data) {
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
            id: 'mp-01',
            image: 'popup-banner-1.jpg'
        },
        {
            id: 'mp-02',
            image: 'popup-banner-2.png'
        },
        {
            id: 'mp-03',
            image: 'popup-banner-4.png'
        },
        {
            id: 'mp-04',
            image: 'popup-banner-06.png'
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

    $rootScope.getBalance = function(reload) {
        if (reload) {
            $("#preloader").show();
        }
        if (!$scope.isProcessing) {
            $scope.isProcessing = true;
            var url = "/frontend/newheaven/api/finance/CheckMemberBalanceAsync";
            CsrfToken.HttpRequest('GET', url, '')
                .success(function(data) {
                    angular.forEach($rootScope.agentGspList, function(val) {
                        if (data.list[val.gspNo] != undefined) {
                            val.amount = data.list[val.gspNo].Balance;
                        } else {
                            val.amount = 0;
                        }
                    });
                    if ($rootScope.loggedIn) {
                        $rootScope.totalBalance = data.list['All'].Balance;
                    }
                }).error(function(data, result) {
                    console.error('Repos error', result, data);
                })["finally"](function() {
                    $scope.isProcessing = false;
                    $("#preloader").hide();
                });
        }
    };

    $rootScope.checkUnreadComment = function(type, announceNo) {
        $scope.countUnread = 0;
        var url = "/frontend/newheaven/api/operation/GetBoardComment?type=" + type + "&&code=" + announceNo;
        $http({
            url: url,
            data: $.param({
                "type": type,
                "code": announceNo,
            }), // pass in data as strings
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            } // set the headers so angular passing info as form data (not request payload)
        }).success(function(data) {
            angular.forEach(data.content, function(value, key) {
                if (value.WriteStatus == "N") {
                    $scope.countUnread++;
                }
            });
            if ($scope.countUnread > 0) {
                $('.board-' + announceNo).removeClass("hidden");
            } else {
                $('.board-' + announceNo).addClass("hidden");
            }
            $scope.countUnread = 0;
        })
    }

    $rootScope.readBoardContent = function(type, announceNo, comment, index) {
        if (comment == undefined) {
            comment = false;
        }

        $rootScope.readCommentDate = "";
        $rootScope.readComment = "";
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

        var url = "/frontend/newheaven/api/operation/GetBoardDescription";
        var param = {
            "type": type,
            "code": announceNo,
            "comment": comment
        }
        CsrfToken.HttpRequest('POST', url, param).success(function(data) {
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

    $rootScope.anouncementPopup = [{}];
    $scope.counter = 1;

    // POPUP 1
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
    /*  $http.get("/api/operation/GetBoardDetail?type=1&page=1")
        .success(function (data) {
          angular.forEach(data.list, function (val) {
            if (val.PopUp == 'y') {
              if (!$cookies.get('notToday-'+val.BoardCode)) {
                $rootScope.anouncementPopup.push(val);
                ngDialog.open({
                  template: 'popup/notice.php',
                  controller: 'NoticeController',
                  className: 'ngdialog-theme-default ngdialog-notice notice-'+$scope.counter,
                  showClose: true,
                  closeByEscape: false,
                  closeByDocument: false,
                  scope: $scope,
                  preCloseCallback: function(){
                    $scope.counter--;
                    if($scope.counter == 1){
                      $('#overlay-notice').hide();
                    }
                  },
                  data: {
                    index: $scope.counter++,
                    id: val.BoardCode
                  }
                });
                $timeout(function () {
                  if($rootScope.anouncementPopup.length == 1){
                    $('#overlay-notice').hide();
                  }
                  else{
                    $('#overlay-notice').show();
                  }
                },1000);
                $interval(function () {
                  if($scope.counter == 2){
                    $('.ngdialog-notice').addClass('centerDialog');
                  }
                },100);
              }
            }
            $scope.getNotice = true;
          });
          $rootScope.announceList = data.list;

        }).error(function (data, result) {
        console.error('Repos error', result, data);
      })["finally"](function () {});*/

    $scope.ShowDirectMessageDetail = function(DirectMessage) {
        $rootScope.DMHideNew[DirectMessage.DMStatusIDX] = true;
        $rootScope.showDirectMessageValues = DirectMessage;
        $http.get("/frontend/newheaven/api/operation/GetDirectMessageDetail?code=" + DirectMessage.DMStatusIDX)
            .success(function(data) {
                $rootScope.UnreadDM = data.list.UnreadDM;
                $rootScope.DMReadDate[DirectMessage.DMStatusIDX] = data.list.DMReadDate;
            }).error(function(data, result) {
                console.error('Repos error', result, data);
            })
    };

    $scope.trustAsHtml = $sce.trustAsHtml;
    $scope.microPopup = function() {
        if (!$rootScope.loggedIn) {
            $scope.displayLogin();
            return
        }
        ngDialog.open({
            template: 'popup/micro-popup.php',
            controller: '',
            className: 'ngdialog-theme-default ngdialog-microgaming',
            showClose: true,
            closeByEscape: false,
            scope: $scope,
            preCloseCallback: function() {
                $scope.setActive = undefined;
            }
        });
    };

    $rootScope.playGame = function(gspNo, productType, gameId, theme, gameName) {
        if (gspNo == 9999) {
            $scope.comingSoon();
            return;
        }

        if (gspNo == 1052 && productType == 'slot') {
            theme = 5;
        }

        if ($rootScope.loggedIn) {
            $rootScope.isOdds = false;
            var url = "";
            var size = "";
            var scroll = "scrollbars=1";
            if (productType == "sports") {
                if (gspNo == 1031) {
                    url = "/frontend/newheaven/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType;
                    $rootScope.sportIURL = $sce.trustAsResourceUrl(url);
                } else if (gspNo == 1059) {
                    $rootScope.isOdds = true;
                    url = "/frontend/newheaven/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId;
                    $rootScope.sportIURL = $sce.trustAsResourceUrl(url);
                } else {
                    url = "/frontend/newheaven/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId;
                    $rootScope.sportIURL = $sce.trustAsResourceUrl(url);
                }
            } else {
                if (productType == 'live' && gameId == 'Desktop') {
                    url = "/frontend/newheaven/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId;
                    size = "width=1024, height=592, scrollbars=no, resizable=no ,toolbar=no,titlebar=no";
                    var popupWindow = window.open(url, 'live', size);
                }
                if (productType == "live" || productType == "playCheck" || productType == "fun" || productType == "Ltlottery") {
                    if (gspNo == 1031) {
                        url = "/frontend/newheaven/api/player/PlayGame?gspNo=" + gspNo + "&productType=sports";
                        size = "width=1024, height=592, scrollbars=no, resizable=no ,toolbar=no,titlebar=no";
                        var popupWindow = window.open(url, 'sports', size);
                        return;
                    }
                    if (gspNo == 1012) {
                        url = "/frontend/newheaven/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType;
                        size = "width=1024, height=592";
                    } else if (gspNo == 1060) {
                        url = "/frontend/newheaven/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType;
                        size = "width=1024, height=592, scrollbars=yes";
                    } else if (gspNo == 1030) {
                        url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType;
                        size = "width=985, height=592";
                    } else if (gspNo == 1022 || gspNo == 1039) {
                        url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType;
                        size = "width=1240, height=775";
                    } else if (gspNo == 1009 || gspNo == 1019) {
                        url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType;
                        size = "width=1024, height=580";
                    } else if (gspNo == 1023) {
                        url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType;
                        size = "width=1300, height=775";
                    } else if (gspNo == 1026) {
                        url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType;
                        size = "width=1262, height=711";
                    } else if (gspNo == 1005 || gspNo == 1036 || gspNo == 1055 || gspNo == 1088 || gspNo == 1112) {
                        url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId;
                        size = "width=1024, height=768";
                    } else if (gspNo == 1058) {
                        url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId + "&gameType=" + theme;
                        size = "width=1024, height=768";
                    } else if (gspNo == 1035 || gspNo == 1040 || gspNo == 1049) {
                        url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType;
                        size = "width=1240, height=775";
                    } else if (gspNo == 1057) {
                        url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId;
                        size = "width=1350, height=1240";
                    } else if (gspNo == 1020) {
                        url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId;
                        size = "width=1280, height=1400";
                    } else if (gspNo == 1142) {
                        url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType;
                        size = "width=1366, height=950";
                    } else if (gspNo == 1153) {
                        url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId;
                        size = "width=1366, height=795";
                    } else if (gspNo == 1052) {
                        url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId + "&gameType=" + theme;
                        size = "width=1360, height=768";
                    } else {
                        url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId;
                        size = "width=1024, height=768";
                    }

                } else if (productType == "slot" || productType == "game" || productType == "minigame") {
                    if (gspNo == 1005 || gspNo == 1004 || gspNo == 1011 || gspNo == 1038 || gspNo == 1036) {
                        url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId;
                        size = "width=1024, height=592";
                    } else if (gspNo == 1032) {
                        url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId;
                        size = "width=1300, height=840";
                    } else if (gspNo == 1012) {
                        url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId;
                        size = "width=1300, height=800";
                    } else if (gspNo == 1034) {
                        url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId;
                        size = "width=1200, height=800";
                    } else if (gspNo == 1073) {
                        url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId;
                        size = "width=840, height=1150";
                    } else if (gspNo == 1098) {
                        url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId + "&GameName=" + gameName;
                        size = "width=1024, height=592";
                    } else if (gspNo == 1125) {
                        url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId;
                        size = "width=0500, height=890";
                    } else if (gspNo == 1052) {
                        url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameType=" + theme + "&gameId=" + gameId;
                        size = "width=1360, height=768";
                    } else if (gspNo == 1138) {
                        url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId;
                        size = "width=1418, height=855";
                    } else {
                        url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId;
                        size = "width=1024, height=768";
                    }
                } else if (productType == "etc") {
                    url = "/api/player/PlayGame?gspNo=" + gspNo + "&productType=" + productType + "&gameId=" + gameId;;
                    size = "width=1024, height=682";
                }

                var target = "";
                if (productType == 'live') {
                    target = gspNo + productType;
                } else if (productType == 'slot' && (gspNo == 1005 || gspNo == 1032 || gspNo == 1012 || gspNo == 1036)) {
                    target = gspNo + productType;
                } else if (productType == 'playCheck' && gspNo == 1005) {
                    target = gspNo + "playCheck";
                } else {
                    target = gspNo + Math.random();
                }

                if (productType == 'slot') {
                    target = productType;
                }

                //Center Popup
                if ((navigator.userAgent.indexOf("MSIE") != -1) || (!!document.documentMode == true)) //IF IE > 10
                {
                    if (gspNo == 1004 || gspNo == 1006 || gspNo == 1027 || gspNo == 1022 || gspNo == 1025 || gspNo == 1026 || gspNo == 1028 || gspNo == 1029 || gspNo == 1032 || gspNo == 1135) {
                        $.colorbox({
                            iframe: true,
                            href: url,
                            width: "80%",
                            height: "80%",
                            overlayClose: false
                        });
                        ngDialog.close();
                        return;
                    }
                }

                var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
                var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

                var width = size.slice(6, 10);
                var height = size.slice(19, 22);
                if (gspNo == 1020) {
                    width = "1280";
                    height = "1400"
                } else if (gspNo == 1073) {
                    width = "840";
                    height = "1150"
                } else if (gspNo == 1030) {
                    width = "985";
                    height = "592"
                }
                var x = ((screen.width / 2) - (width / 2)) + dualScreenLeft;
                var y = ((screen.height / 2) - (height / 2)) + dualScreenTop;
                var total_size = "width=" + width + ",height=" + height + ",left=" + x + ",top=" + y;

                if (gspNo == 1004 || gspNo == 1006 || gspNo == 1027 || gspNo == 1022 || gspNo == 1025 || gspNo == 1026 || gspNo == 1028 || gspNo == 1029 || gspNo == 1032 || gspNo == 1135) {
                    $.colorbox({
                        iframe: true,
                        href: url,
                        width: "80%",
                        height: "80%",
                        overlayClose: false
                    });
                    ngDialog.close();
                    return;
                }

                var popupWindow = window.open(url, target, total_size);
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
            }
        } else {
            ngDialog.open({
                template: '/popup/login.php',
                controller: 'LoginController',
                className: 'ngdialog-theme-default ngdialog-login',
                scope: $scope
            });
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
        fileref.setAttribute("href", "/frontend/newheaven/common/css/languages/" + font);

        if (typeof fileref != "undefined") {

            $("link[href*='/frontend/newheaven/common/css/languages/kr.css']").remove();
            $("link[href*='/frontend/newheaven/common/css/languages/jp.css']").remove();
            $("link[href*='/frontend/newheaven/common/css/languages/cn.css']").remove();
            $("link[href*='/frontend/newheaven/common/css/languages/en.css']").remove();
            $("link[href*='/frontend/newheaven/common/css/languages/id.css']").remove();

            document.getElementsByTagName("head")[0].appendChild(fileref);
        }
        return font;
    };

    $scope.aff_user = "";
    $scope.checkSession = function() {
        $http.get("//frontend/newheaven/api/player/GetNewMemberInfo")
            .success(function(data) {
                /*WILL DISABLE IF HAS DATA*/
                if (data.result != 1) {
                    if (data.result == 207) {
                        $http.get("/frontend/newheaven/api/player/Logout").success(function(data) {
                            if (data.result == 1) {
                                if (bowser.msie && bowser.version <= 8) {
                                    alert(data.message);
                                } else {
                                    $translate([data.message, "YouHaveBeenSignedOut"]).then(function(translations) {
                                        SweetAlert.swal(translations.YouHaveBeenSignedOut, translations[data.message], "success");
                                    });
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
                            $translate([data.message, "PleaseTryAgain"]).then(function(translations) {
                                SweetAlert.swal(translations.PleaseTryAgain, translations[data.message], "success");
                            });
                        }
                    }
                }
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
            }).error(function(data, result) {
                console.error('Repos error', result, data);
            })["finally"](function() {
                $scope.isProcessing = false;
            });
    };


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

        if (localStorage.getItem('NG_TRANSLATE_LANG_KEY') === undefined) {
            var arrayLang = ["ko_KR"];
            var userLang = navigator.language || navigator.userLanguage; //for IE8 = navigator.userLanguage
            var u;
            for (u = 0; u < arrayLang.length; u++) {
                if (arrayLang[u].search(userLang.replace("-", "_")) === 0) {
                    var browserLang = arrayLang[u].replace("-", "_");
                }
            }
            if (browserLang === undefined) {
                $rootScope.currentLang = userLang.substring(0, 2) + "_" + userLang.substring(3, 5).toUpperCase();
            } else {
                $rootScope.currentLang = browserLang;
            }
        } else {
            $rootScope.currentLang = localStorage.getItem('NG_TRANSLATE_LANG_KEY');
            if ($rootScope.currentLang == null) {
                $rootScope.currentLang = "undefined";
            }
        }

        if ($location.$$path == '/view-sports' && $rootScope.activeSportsGsp == undefined) {
            window.location = '#/';
        }
    };

    $scope.stopAlert = false;
    $scope.DMPlayAlert = function() {
        $scope.audio = new Audio('/frontend/newheaven/common/audio/ko_KR.mp3');
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
        //PLAY AUDIO EVERY MINUTE UNLESS STOP
        setInterval(function() {
            if ($scope.stopAlert == false) {
                $scope.audio = new Audio('/frontend/newheaven/common/audio/ko_KR.mp3');
                $scope.audio.play();
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

    $scope.formatDate = function(date) {
        if (date) {
            var date = date.split("-").join("/");
            var dateOut = new Date(date);
            return dateOut;
        }
    };

    $scope.displayForgot = function() {
        ngDialog.open({
            template: 'popup/forgot-password.php',
            controller: 'ForgotPasswordController',
            className: 'ngdialog-theme-default ngdialog-forgot',
            scope: $scope
        });
    };

    $scope.displaySignUp = function() {
        ngDialog.close();
        ngDialog.open({
            template: 'popup/signup.php',
            controller: 'SignUpController',
            className: 'ngdialog-theme-default ngdialog-signup',
            scope: $scope,
            closeByEscape: false,
            closeByDocument: false,
            showClose: true
        });
    };

    $scope.closeThisDialogSignUp = function() {
        ngDialog.close();
    };


    $scope.displayWallet = function(tabIndex) {
        $scope.isLoadingPopup = true;
        if (!$rootScope.loggedIn) {
            return $scope.displayLogin();
        }
        if ($scope.isLoadingPopup == true) {
            $('.main-nav ul li a').addClass('disable-event');
            $('.wallet-page .wallet-button li').addClass('disable-event');
            $scope.selectWalletTab = tabIndex;
            ngDialog.open({
                template: 'popup/wallet.php',
                controller: 'WalletController',
                className: 'ngdialog-theme-default ngdialog-main-default ngdialog-wallet',
                closeByDocument: false,
                scope: $scope,
                preCloseCallback: function() {
                    $scope.isLoadingPopup = false;
                    $('.main-nav ul li a').removeClass('disable-event');
                    $('.wallet-page .wallet-button li').removeClass('disable-event');
                }
            });
        }
    };

    $http.get("/frontend/newheaven/api/system/gamelist/gamebuttons.json")
        .success(function(data) {
            $scope.gameButtons = data;
        });
    $scope.gameButtonFilter = function(category) {
        if (category.category == $scope.activeCategory || category.category2 == $scope.activeCategory || category.category3 == $scope.activeCategory) {
            return true;
        }
    };

    $scope.getActiveCategory = function(gspNo, category, gameId, gspName, theme) {
        if (gspNo == 1070) {
            return $rootScope.comingSoon();
        }
        if (category == "Others" && gspNo == 1052) {
            return $scope.playGame(gspNo, 'Ltlottery', '', '12');
        }
        if (category == "Others" && gameId == "6") {
            return $scope.playGame(gspNo, 'fish', gameId);
        }
        if (category == "Others" && gameId == "1") {
            return $scope.playGame(gspNo, 'live', gameId);
        }
        if (category == "Others" && gspNo != 1070 && gspNo != 1020 && gspNo != 1012 && gspNo != 1048) {
            return $scope.playGame(gspNo, 'minigame', gameId, '');
        }

        if (category == "Others" && gameId == "110") {
            return $scope.playGame(gspNo, 'live', gameId, '');
        }

        if (category == "Others" && gspNo != 1020) {
            return $scope.playGame(gspNo, 'live');
        }

        if (category == 'live') {
            if (gameId == "microPopup") {
                // return $scope.microPopup();
                return $scope.playGame(1112, 'live', '1930');
            }
            return $scope.playGame(gspNo, 'live', gameId, theme);
        } else if (category == 'slot') {
            $('.game-button-container').css("display", "none");
            $('.slots-games-container').css("display", "block");
            $rootScope.loadSlot(gspNo);
            $rootScope.gspName = gspName;
            return $rootScope.activeSlotGsp = gspNo;
        } else if (category == 'sports') {
            ngDialog.close();
            return $rootScope.setActiveSportsGsp(gspNo);

        } else if (category == 'Powerball' && gspNo == 1020) {
            return $scope.playGame(gspNo, 'live', '10001');
        }
    }

    $scope.displayCustomer = function(tabIndex) {
        $scope.isLoadingPopup = true;

        if ($rootScope.loggedIn && $scope.isLoadingPopup == true) {
            $('.main-nav ul li a').addClass('disable-event');
            $('.transaction-page span').addClass('disable-event');
            $('.customer-page .customer-buttons').addClass('disable-event');
            $('.wallet-page .rigth-container-title i').addClass('disable-event');
            $('.banner-container .slick-slide').addClass('disable-event');

            $scope.selectCustomerTab = tabIndex;
            ngDialog.open({
                template: 'popup/customer.php',
                controller: 'CustomerController',
                className: 'ngdialog-theme-default ngdialog-main-default',
                closeByDocument: false,
                scope: $scope,
                preCloseCallback: function() {
                    $scope.isLoadingPopup = false;
                    $('.main-nav ul li a').removeClass('disable-event');
                    $('.transaction-page span').removeClass('disable-event');
                    $('.customer-page .customer-buttons').removeClass('disable-event');
                    $('.wallet-page .rigth-container-title i').removeClass('disable-event');
                    $('.banner-container .slick-slide').removeClass('disable-event');
                }
            });

            if (tab == 'liveChat') {
                return LC_API.open_chat_window();
            }

        } else {
            $scope.displayLogin();
        }
    };

    $scope.displayLogin = function() {
        $scope.isLoadingPopup = true;
        if ($scope.isLoadingPopup == true) {
            $('.main-nav ul li a').addClass('disable-event');
            ngDialog.open({
                template: 'popup/login.php',
                controller: 'LoginController',
                className: 'ngdialog-theme-default ngdialog-login',
                closeByEscape: false,
                scope: $scope,
                preCloseCallback: function() {
                    $scope.isLoadingPopup = false;
                    $('.main-nav ul li a').removeClass('disable-event');
                }
            });
        }
    };

    $scope.displayTerms = function(tabIndex) {
        $scope.selectCustomerTab = tabIndex;
        ngDialog.open({
            template: 'popup/terms.php',
            controller: '',
            className: 'ngdialog-theme-default ngdialog-main-default',
            closeByDocument: false,
            scope: $scope
        });
    };

    $scope.displayRules = function(tabIndex) {
        $scope.isLoadingPopup = true;

        if ($rootScope.loggedIn && $scope.isLoadingPopup == true) {
            $('.main-nav ul li a').addClass('disable-event');
            $scope.pageName = 'popup/rules-' + $rootScope.currentLang + '.php';
            $scope.selectRulesTab = tabIndex;
            ngDialog.open({
                template: $scope.pageName,
                controller: '',
                className: 'ngdialog-theme-default ngdialog-main-default ngdialog-rules',
                scope: $scope,
                closeByDocument: false,
                scope: $scope,
                preCloseCallback: function() {
                    $scope.isLoadingPopup = false;
                    $('.main-nav ul li a').removeClass('disable-event');
                }
            });
        } else {
            $scope.displayLogin();
        }
    };

    $rootScope.comingSoon = function() {
        if (bowser.msie && bowser.version <= 8) {
            alert("준비중입니다");
        } else {
            SweetAlert.swal("준비중입니다", "", "info");
        }
    };

    $rootScope.contactCC = function() {
        var message = 'AccessDenied';
        if (bowser.msie && bowser.version <= 8) {
            alert(message);
        } else {
            $translate([message, "ContactCustomerCenter"]).then(function(translations) {
                SweetAlert.swal(translations.ContactCustomerCenter, '', "info");
            });
        }
    };

    $scope.displayGames = function(category) {
        $scope.isLoadingPopup = true;

        if (category == '' || category === 'sports') {
            return $rootScope.comingSoon();
            console.log('sports')
        }

        if ($scope.isLoadingPopup == true) {
            $('.click-disable').addClass('disable-event');
            $rootScope.activeCategory = category;
            ngDialog.open({
                template: 'popup/games-popup.php',
                controller: '',
                className: 'ngdialog-theme-default ngdialog-gamespopup',
                closeByEscape: false,
                closeByDocument: false,
                scope: $scope,
                preCloseCallback: function() {
                    $scope.isLoadingPopup = false;
                    $('.click-disable').removeClass('disable-event');
                }
            });
        }
    }

    $rootScope.setSlot = function(gspNo) {
        $rootScope.triggerLoadSlot = gspNo;
    };

    $rootScope.loadCounter = function() {
        $http.get("/frontend/newheaven/api/marketing/getJackpot")
            .success(function(data) {
                $('.jackpot-odometer').jOdometer({
                    increment: data.increment,
                    counterStart: data.counterStart,
                    counterEnd: false,
                    numbersImage: '/frontend/newheaven/common/images/jackpot/odometer.png',
                    spaceNumbers: -3,
                    formatNumber: true,
                    widthNumber: 35,
                    heightNumber: 74
                });
            }).error(function(data, status) {
                console.error('Repos error', status, data);
            });
    };

    $rootScope.loadIDNCounter = function() {
        $http.get("frontend/newheaven/api/marketing/GetPokerJackpot")
            .success(function(data) {
                $('.jackpot-odometer').jOdometer({
                    increment: 13.13,
                    counterStart: data.total_jackpot + "." + (Math.floor(Math.random() * 9) + 1) + '' + (Math.floor(Math.random() * 9) + 1),
                    counterEnd: false,
                    numbersImage: '/frontend/newheaven/common/images/jackpot/odometer.png',
                    spaceNumbers: 1,
                    formatNumber: true,
                    widthNumber: 45,
                    heightNumber: 60
                });
            }).error(function(data, status) {
                console.error('Repos error', status, data);
            });
    };

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

    $rootScope.$on('$locationChangeSuccess', function() {
        $scope.currentPath = $location.path();
    });

    $scope.translateWalletCategory = function(agentGsp) {
        return $filter('translate')(agentGsp.category);
    };
    /*RIGHT PANEL GSP WALLET*/

    // $scope.rightPanelLimit = 5;
    $scope.showMore = true;
    $scope.showLess = false;

    $scope.rightPanelShowMore = function() {
        $scope.rightPanelLimit = 1000;
        $scope.showMore = false;
        $scope.showLess = true;
    };

    $scope.rightPanelShowLess = function() {
        $scope.rightPanelLimit = 5;
        $scope.showMore = true;
        $scope.showLess = false;
    };

    $rootScope.setActiveInside = function(setTab) {
        $rootScope.isActiveInside = setTab;
    };
    $scope.walletCategory = [{
            "category": "Casino",
            'categoryKrw': '스포츠 ',
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
        // {"category": "Sports Betting",'categoryKrw':'카지노', "target": "sports-wallet", "img": "sports"},
    ];

    $scope.gspLogoUnique = function(footerLogo) {
        if (footerLogo.gspNo == 1031 ||
            footerLogo.gspNo == 1006 || footerLogo.gspNo == 1004 ||
            footerLogo.gspNo == 1022 || footerLogo.gspNo == 1026 ||
            footerLogo.gspNo == 1028 || footerLogo.gspNo == 1029 || footerLogo.gspNo == 1025 || footerLogo.gspNo == 1032) {
            return false;
        } else {
            return true;
        }
    }
});

app.controller("LoginController", function(CsrfToken, $scope, $http, $window, SweetAlert, loggedInStatus) {
    $scope.loginForm = {};
    $scope.isProcessing = false;
    $scope.processForm = function() {
        if (!$scope.isProcessing) {
            $scope.isProcessing = true;
            var url = "/frontend/newheaven/api/player/Login";
            CsrfToken.HttpRequest('POST', url, $scope.loginForm).success(function(data) {
                if (data.result == 1) {
                    $window.location.href = "/#/";
                    $window.location.reload();
                } else if (data.result == 213) {
                    if (bowser.msie && bowser.version <= 8) {
                        alert(data.message);
                    } else {
                        SweetAlert.swal(data.message, "승인을 기다려주시거나 지금채팅하기를 통해 고객지원팀에 문의 하여 주시기 바랍니다", "error");
                    }
                } else if (data.result == 203) {
                    if (bowser.msie && bowser.version <= 8) {
                        alert(data.message);
                    } else {
                        SweetAlert.swal(data.message, "", "error");
                    }
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

app.controller("LogoutController", function($scope, $rootScope, $http, $window, SweetAlert, loggedInStatus, $timeout) {
    $scope.isProcessing = false;
    $rootScope.logout = function() {
        $scope.isProcessing = true;
        $http.get("/frontend/newheaven/api/player/Logout")
            .success(function(data) {
                if (data.result == 1) {
                    if (bowser.msie && bowser.version <= 8) {
                        alert(data.message);
                    } else {
                        SweetAlert.swal("로그아웃 되었습니다", "", "success");
                    }
                    /*DELAY RELOAD AFTER LOGGING OUT*/
                    $timeout(function() {
                        loggedInStatus.setLoggedOutStatus();
                        $window.location.href = "/#/";
                        $window.location.reload();
                    }, 1000)
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
                $scope.isProcessing = false;
            });
    }
});

app.controller('RulesController', function($scope, $rootScope) {
    $scope.isSet = function(checkTab) {
        return $rootScope.customerTab == checkTab;
    };
    $scope.setTab = function(setTab) {
        $rootScope.customerTab = setTab;
    };
});

app.controller('NoticeController', function($rootScope, $scope, $cookies, $http, $window, SweetAlert) {
    $rootScope.writeQuestion = {};

    /*  $scope.notToday = function (index) {
        var expireDate = new Date();
        expireDate.setDate(expireDate.getDate() + 1);
        $cookies.put('notToday-'+index, 'true', {'expires': expireDate});
        $scope.closeThisDialog();
      };*/

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
    /*$scope.notTodayNotice2 = function () {
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