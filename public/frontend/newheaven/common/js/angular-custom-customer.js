app.controller('CustomerController', function(CsrfToken, $rootScope, $scope, $cookies, $http, $window, SweetAlert) {

    $scope.isSet = function(checkTab) {
        return $rootScope.customerTab == checkTab;
    };

    $scope.setTab = function(setTab) {
        $rootScope.readTitle = "";
        $rootScope.readContents = "";
        $rootScope.readCommentDate = "";
        $rootScope.readComment = "";
        $rootScope.customerTab = setTab;
        $rootScope.writeCustomer = {};

        $scope.directMessageList = {};
        $rootScope.DMHideNew = [];
        $rootScope.DMReadDate = [];
        $scope.filteredPage = [];
        $scope.boardList = {};
        $scope.totalItems = 0;
        $scope.currentPage = 1;
        $scope.maxSize = 5;
        $scope.numPerPage = 10;

        $rootScope.DMHideNew = [];
        $rootScope.DMReadDate = [];

        if ($rootScope.customerTab != 7) { //faq
            var url = "/api/operation/GetBoardDetail?type=" + setTab;
            CsrfToken.HttpRequest('GET', url, '').success(function(data) {
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
                    if (data.list != undefined) {
                        $scope.boardList = data.list;
                        $scope.totalItems = data.list.length;
                    } else {
                        $scope.boardList = "";
                        $scope.totalItems = 0;
                    }
                }
            }).error(function(data, result) {
                console.error('Repos error', result, data);
            })["finally"](function() {

                //console.log($scope.totalItems);
                $scope.numPagesCal = function() {
                    return Math.ceil($scope.boardList.length / $scope.numPerPage);
                };

                $scope.numPages = $scope.numPagesCal();

                $scope.$watch('currentPage + numPerPage', function() {
                    var begin = (($scope.currentPage - 1) * $scope.numPerPage),
                        end = begin + $scope.numPerPage;
                    if (JSON.stringify($scope.boardList) !== '{}') {
                        $scope.filteredPage = $scope.boardList.slice(begin, end);
                        //console.log($scope.filteredPage);
                    }

                });
            });
        } else { //Direct Message

            $rootScope.showDirectMessageValues = {};

            $http.get("/api/operation/GetDirectMessageList")
                .success(function(data) {
                    if (data.list != undefined) {
                        $rootScope.UnreadDM = data.UnreadDM;
                        $scope.directMessageList = data.list;
                        $scope.totalItems = data.list.length;
                    } else {
                        $scope.directMessageList = "";
                        $scope.totalItems = 0;
                    }

                }).error(function(data, result) {
                    console.error('Repos error', result, data);
                })["finally"](function() {
                    $scope.numPagesCal = function() {
                        return Math.ceil($scope.directMessageList.length / $scope.numPerPage);
                    };

                    $scope.numPages = $scope.numPagesCal();

                    $scope.$watch('currentPage + numPerPage', function() {
                        var begin = (($scope.currentPage - 1) * $scope.numPerPage),
                            end = begin + $scope.numPerPage;
                        if (JSON.stringify($scope.directMessageList) !== '{}') {
                            $scope.filteredPage = $scope.directMessageList.slice(begin, end);
                            $scope.begin = begin;
                            $scope.ctr = $scope.totalItems;
                        }
                    });
                });

        }

    };

    $rootScope.processForm = function(type) {
        $rootScope.isProcessing = true;
        $rootScope.writeCustomer.type = type;
        var url = "/api/operation/GetWriteBoard";
        $http({
            method: 'POST',
            url: url,
            data: $.param($rootScope.writeCustomer), // pass in data as strings
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            } // set the headers so angular passing info as form data (not request payload)
        }).success(function(data) {
            if (data.result == 1) {
                if (bowser.msie && bowser.version <= 8) {
                    alert(data.message);
                } else {
                    SweetAlert.swal(data.message, "", "success");
                    $rootScope.writeCustomer = {};
                }
                $scope.showme = false;
                $scope.setTab(type);
            } else {
                if (data.alert) {
                    if (bowser.msie && bowser.version <= 8) {
                        alert(data.message);
                    } else {
                        SweetAlert.swal(data.message, "다시 시도해보세요", "error");
                        $rootScope.writeCustomer = {};
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