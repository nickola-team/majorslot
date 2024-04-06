angular.module('casinoApp')
    .controller('MultiplePopupController', function($scope, ngDialog, $cookies) {
        var hiddenArr = [];

        $scope.listLength = 0;

        function getNotTodayCookies(id) {
            return $cookies.get('notToday-' + id);
        }

        $scope.notTodayMultiplePopup = function(id) {
            var expireDate = new Date();
            expireDate.setDate(expireDate.getDate() + 1);
            $cookies.put('notToday-' + id, true, {
                'expires': expireDate
            });
        }

        $scope.isShown = function(id) {
            // Close popup if all popups are in the cookies
            if (hiddenArr.length == $scope.listLength) {
                // Added setTimeout to resolve $digest error
                setTimeout(function() {
                    $scope.closeThisDialog();
                }, 100)
            }

            if (getNotTodayCookies(id)) {
                $scope.hidePopup(id);
            }

            return hiddenArr.includes(id);
        }

        $scope.hidePopup = function(id) {
            if (!hiddenArr.includes(id)) {
                hiddenArr.push(id)
            }
        }
    });