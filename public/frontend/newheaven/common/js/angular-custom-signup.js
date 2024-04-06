app.controller("SignUpController", function(CsrfToken, $scope, $rootScope, $http, $window, SweetAlert, ngDialog, $translate) {
    $scope.isProcessing = undefined; //Disabled button after call ajax function
    $scope.signForm = {};
    $scope.sendSmsFrom = {};
    $scope.verifyFrom = {};

    $scope.userInfo = {};
    $scope.isSend = false;
    $scope.isProcessing = false;

    $scope.sendSMS = function() {
        if (!$scope.isProcessing && $scope.isSend != true) {
            $scope.isProcessing = true;
            var url = "/api/player/VerifyPhone";

            CsrfToken.HttpRequest('POST', url, $scope.signForm).success(function(data) {

                //console.log(data);
                //console.log(data.message);
                if (data.result == 1) {
                    if (bowser.msie && bowser.version <= 8) {
                        alert(data.message);
                    } else {
                        $translate([data.message]).then(function(translations) {
                            SweetAlert.swal(translations[data.message], "", "success");
                        });
                    }
                    $scope.isSend = true
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
                            })
                        } else {
                            $translate([data.message, "PleaseTryAgain"]).then(function(translations) {
                                SweetAlert.swal(translations[data.message], translations.PleaseTryAgain, "error");
                            });
                        }
                    }
                }
            }).error(function(data, result) {
                console.error('Repos error', result, data);
            })["finally"](function() {
                $scope.isProcessing = false;
            });
        }
    };

    $scope.openDatePicker = function() {
        $('#datetimepicker').datetimepicker('toggle');
    }

    $scope.processForm = function() {
        if (!$scope.isProcessing) {
            $scope.isProcessing = true;

            var url = "/api/player/SignUp";


            CsrfToken.HttpRequest('POST', url, $scope.signForm).success(function(data) {
                if (data.result == 1) {
                    if (bowser.msie && bowser.version <= 8) {
                        alert(data.message);
                    } else {
                        SweetAlert.swal(data.message, "", "success");
                        ngDialog.close();
                    }
                    $http.get("/api/system/GetDeviceDetect")
                        .success(function(data) {
                            if (data.isMobile) {
                                $window.location.href = "/mobile/#/";
                                $window.location.reload();
                            } else {
                                $window.location.href = "/#/";
                                $window.location.reload();
                            }
                        })
                } else if (data.result == 213) {
                    if (bowser.msie && bowser.version <= 8) {
                        alert(data.message);
                    } else {
                        swal({
                                title: data.message,
                                text: "승인을 기다려주시거나 지금채팅하기를 통해 라이브 채팅으로 문의 하여 주시기 바랍니다",
                                icon: "success",
                            })
                            .then(function() {
                                $http.get("/api/system/GetDeviceDetect")
                                    .success(function(data) {
                                        if (data.isMobile) {
                                            $window.location.href = "/mobile/#/";
                                            $window.location.reload();
                                        } else {
                                            $window.location.href = "/#/";
                                            $window.location.reload();
                                        }
                                    })
                            });
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
                            SweetAlert.swal(data.message, "", "error");
                        }
                    }
                }
            }).error(function(data, result) {
                console.error('Repos error', result, data);
            })["finally"](function() {
                $scope.isProcessing = false;
            });
        }
    };

});


app.directive('useFormError', ['$log', function($log) {
    return {
        restrict: 'A',
        require: ['?ngModel', '^form'],
        scope: {
            useFormError: '@',
            useErrorExpression: "=",
            useErrorInput: "=?",
        },
        link: function(scope, elem, attrs, ctrls) {
            //We expect that the directive will be applied either on the ng-model, ng-form or another element as an attribute
            var validityObject = scope.useErrorInput || ctrls[0] || ctrls[1];
            if (validityObject && angular.isFunction(validityObject.$setValidity)) {
                if (!angular.isDefined(scope.useFormError))
                    $log.error('You must define a key for the form input "' + attrs.useErrorInput + '"');
                //We watch the changes of expression
                scope.$watch('useErrorExpression', function(newVal) {
                    $log.debug('Key: ' + attrs.useFormError + '. Expression: ' + attrs.useErrorExpression + ' is ' + newVal);
                    if (newVal)
                        validityObject.$setValidity(scope.useFormError, false);
                    else
                        validityObject.$setValidity(scope.useFormError, true);
                });
            } else
                $log.error('Form input "' + attrs.useErrorInput + '" not defined for the key: "' + scope.useFormError + '"');
        }
    };
}]);