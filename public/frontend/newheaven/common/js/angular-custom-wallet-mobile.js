app.controller("BonusController", function(CsrfToken, $scope, $rootScope, $http, SweetAlert, $translate) {
    $scope.bonus = {};
    $scope.bonus.compAmount = 0;
    $scope.bonus.rollingAmount = 0;
    $scope.isProcessing = false;

    $rootScope.loadBonus = function() {
        var url = "/api/player/GetBonus.php";
        $http({
            method: 'GET',
            url: url,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            } // set the headers so angular passing info as form data (not request payload)
        }).success(function(data) {

            var ccomp = data.bonus.CurrentComp;
            var ucomp = data.bonus.UsedComp;
            var tcomp = data.bonus.TotalComp;

            var rccomp = data.bonus.CurrentRolling;
            var rucomp = data.bonus.UsedRolling;
            var tucomp = data.bonus.TotalRolling;

            $(".current-comp").text(ccomp);
            $(".used-comp").text(ucomp);
            $(".total-comp").text(tcomp);

            $(".rcurrent-comp").text(rccomp);
            $(".rused-comp").text(rucomp);
            $(".rtotal-comp").text(tucomp);

            /*$scope.bonus.rollingAmount = Number(rccomp.replace(/,/g, ''));
            $scope.bonus.compAmount = Number(ccomp.replace(/,/g, ''));*/
        });
    };

    $scope.processForm = function(type) {
        if (!$scope.isProcessing) {
            $scope.isProcessing = true;

            if (type == "Rolling") {
                var url = "/api/marketing/UseRolling";
            } else {
                var url = "/api/marketing/UseBonus";
            }

            CsrfToken.HttpRequest('POST', url, $scope.bonus) // pass in data as strings
                .success(function(data) {
                    if (data.result == 1) {
                        if (bowser.msie && bowser.version <= 8) {
                            //Balance.getBalance("all");
                            alert(data.message);
                        } else {
                            //Balance.getBalance("all");
                            $translate([data.message]).then(function(translations) {
                                SweetAlert.swal(translations[data.message], "", "success");
                            });
                            $scope.bonus.compAmount = 0;
                            $scope.bonus.rollingAmount = 0;
                            $scope.bonus.gameCode = "";
                        }
                        $scope.loadBonus();
                    } else {
                        if (data.alert) {
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
                    }
                }).error(function(data, result) {
                    console.error('Repos error', result, data);
                })["finally"](function() {
                    $scope.isProcessing = false;
                });
        }
    };
});

app.controller("BonusMCompController", function($rootScope, $scope, $http) {
    $scope.filteredPage = [];
    $scope.bonusHistoryMainList = {};
    $scope.totalItems = 0;
    $scope.currentPage = 1;
    $scope.maxSize = 5;
    $scope.numPerPage = 10;

    $rootScope.getMemberBonusList = function() {
        var url = "/api/player/GetBonusHistory.php?type=mComp";
        $http.get(url).success(function(data) {
            $scope.bonusHistoryMainList = data.list;
        })["finally"](function() {
            $scope.totalItems = $scope.bonusHistoryMainList.length;
            $scope.numPagesCal = function() {
                return Math.ceil($scope.bonusHistoryMainList.length / $scope.numPerPage);
            };
            $scope.numPages = $scope.numPagesCal();
            $scope.$watch('currentPage + numPerPage', function() {
                var begin = (($scope.currentPage - 1) * $scope.numPerPage),
                    end = begin + $scope.numPerPage;
                $scope.filteredPage = $scope.bonusHistoryMainList.slice(begin, end);
                $scope.begin = begin;
                $scope.ctr = $scope.totalItems;
                //console.log($scope.filteredPage);
            });
        });
    };
});

app.controller("BonusFCompController", function($rootScope, $scope, $http) {
    $scope.filteredPage = [];
    $scope.bonusHistoryMainList = {};
    $scope.totalItems = 0;
    $scope.currentPage = 1;
    $scope.maxSize = 5;
    $scope.numPerPage = 10;

    $rootScope.getFriendBonusList = function(offset) {
        offset = offset == undefined ? 0 : offset;
        var url = "/api/player/GetBonusHistory.php?type=fComp&&offset=" + offset + "&count=" + $scope.numPerPage;
        $http.get(url).success(function(data) {
            $scope.bonusHistoryMainList = data.list;
        })["finally"](function() {
            $scope.totalItems = $scope.bonusHistoryMainList.length;
            //console.log($scope.totalItems);
            $scope.numPagesCal = function() {
                return Math.ceil($scope.bonusHistoryMainList.length / $scope.numPerPage);
            };
            $scope.numPages = $scope.numPagesCal();
            $scope.$watch('currentPage + numPerPage', function() {
                var begin = (($scope.currentPage - 1) * $scope.numPerPage),
                    end = begin + $scope.numPerPage;
                $scope.filteredPage = $scope.bonusHistoryMainList.slice(begin, end);
                $scope.begin = begin;
                $scope.ctr = $scope.totalItems;
            });
        });
    };
});

app.controller("TransferController", function(CsrfToken, $scope, $rootScope, $http, SweetAlert, AmountService) {
    $scope.transfer = {};
    $scope.transfer.amount = 0;
    $scope.isProcessing = false;

    $scope.gspTransfer = {};
    $scope.filteredGspWalletList = $rootScope.agentGspList;
    $scope.$watch('transfer.fromWallet', function() {
        $scope.filteredGspWalletList = [];
        angular.forEach($rootScope.agentGspList, function(val) {
            if (val.gspNo != $scope.transfer.fromWallet) {
                //console.log(val);
                this.push(val);
            }
        }, $scope.filteredGspWalletList);
    });


    $scope.addAmount = function(sumAmount) {
        $scope.transfer.amount = AmountService.sumAmount($scope.transfer.amount, sumAmount);
    };

    $scope.resetAmount = function() {
        $scope.transfer.amount = AmountService.resetAmount();
    };

    $scope.processForm = function() {
        if (!$scope.isProcessing) {
            $scope.isProcessing = true;
            var url = "../api/finance/WalletTransfer";
            CsrfToken.HttpRequest('POST', url, $scope.transfer) // pass in data as strings
                .success(function(data) {
                    if (data.result == 1) {
                        if (bowser.msie && bowser.version <= 8) {
                            //Balance.getBalance("all");
                            alert(data.message);
                        } else {
                            //Balance.getBalance("all");
                            SweetAlert.swal(data.message, "", "success");
                        }
                    } else {
                        if (data.alert) {
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
                                    SweetAlert.swal(data.message, "다시 시도해보세요", "error");
                                }
                            }
                        }
                    }
                }).error(function(data, result) {
                    console.error('Repos error', result, data);
                })["finally"](function() {
                    $scope.getBalance();
                    $scope.isProcessing = false;
                    $scope.transfer.amount = 0;
                    $scope.transfer.fromWallet = undefined;
                    $scope.transfer.toWallet = undefined;
                });
        }
    };
});

app.controller("CashHistoryController", function($rootScope, $scope, $http) {
    $scope.transactionType = {
        'Transfer': '머니이동',
        'Withdrawal': '출금',
        Deposit: '입금',
        'NO': '일반',
        'FS': '첫입금',
        'DA': '일일첫입금',
        'CO': '콤프',
        'CP': '콤프',
        'ET': '기타',
        '': '',
        'DF': '매일 첫번째 입금',
        'M': '월 입금',
        'N': '일반 서비스',
        'F': '첫 입금',
        'ED': '매 입금',
        'SD': '매일 두번째 입금',
        'TD': '매일 세번째 입금',
        'WK': '매주 입금',
        'WD': '주중 입금',
        'WE': '주말 입금'
    };
    $scope.status = {
        'D': '취소',
        'P': '확인중',
        'R': '신청',
        'S': '처리완료'
    };
    $scope.filteredPage = [];
    $scope.historyList = {};
    $scope.totalItems = 0;
    $scope.currentPage = 1;
    $scope.maxSize = 5;
    $scope.numPerPage = 10;

    $rootScope.loadHistory = function() {
        var url = "../api/player/GetHistory";
        $http.get(url).success(function(data) {
            $scope.historyList = data.list;
        })["finally"](function() {
            $scope.totalItems = $scope.historyList.length;
            $scope.numPagesCal = function() {
                return Math.ceil($scope.historyList.length / $scope.numPerPage);
            };

            $scope.numPages = $scope.numPagesCal();

            $scope.$watch('currentPage + numPerPage', function() {
                var begin = (($scope.currentPage - 1) * $scope.numPerPage),
                    end = begin + $scope.numPerPage;
                $scope.filteredPage = $scope.historyList.slice(begin, end);
            });
        });
    };

});

app.controller("DepositController", function(CsrfToken, $scope, $http, AmountService, SweetAlert, ngDialog) {
    $scope.isProcessing = false;
    $scope.deposit = {};
    $scope.deposit.Amount = 0;
    $scope.deposit.Phone = "";
    $scope.deposit.BankAccount = "";
    $scope.havePhone = false;
    $scope.haveName = false;
    var _timezone = jstz.determine();

    //$scope.error={};

    $scope.addAmount = function(sumAmount) {
        $scope.deposit.Amount = AmountService.sumAmount($scope.deposit.Amount, sumAmount);
    };

    $scope.resetAmount = function() {
        $scope.deposit.Amount = AmountService.resetAmount();
    };

    var url = "/api/player/GetNewMemberInfo";
    $http.get(url).success(function(data) {
        /*WILL DISABLE IF HAS DATA*/

        $scope.deposit.Phone = data.bonus.MemberPhone;
        $scope.deposit.BankAccount = data.bonus.MemberName;
        $scope.havePhone = data.bonus.MemberPhone;
        $scope.haveName = data.bonus.MemberName;

        $scope.deposit.PhoneOutput = data.bonus.MemberPhone.slice(0, -3) + '***';
        $scope.deposit.NameOutput = data.bonus.MemberName.split(' ').slice(-1).join(' ').slice(0, -2) + '**';

        setTimeout(function() {
            $(".deposit-phone").val($scope.havePhone);
        }, 100)

    });

    $scope.processForm = function() {
        if (!$scope.isProcessing) {
            $scope.isProcessing = true;

            $scope.deposit.DepositDate = moment.tz($scope.deposit.DepositDate, _timezone.name()).format("YYYY-MM-DD HH:mm:ss Z");

            var url = "/api/finance/RequestDeposit";
            CsrfToken.HttpRequest('POST', url, $scope.deposit) // pass in data as strings
                .success(function(data) {
                    //$scope.error.status=false;
                    if (data.result == 1) {
                        if (bowser.msie && bowser.version <= 8) {
                            alert(data.message);
                        } else {
                            SweetAlert.swal(data.message, "", "success");
                            ngDialog.close();
                        }

                    } else {
                        //if (data.alert) {
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
                                SweetAlert.swal(data.message, "다시 시도해보세요", "error");
                            }
                        }
                    }
                }).error(function(data, result) {
                    console.error('Repos error', result, data);
                })["finally"](function() {
                    $scope.isProcessing = false;
                    $scope.deposit.Amount = "";
                    $scope.deposit.Memo = "";
                    $scope.deposit.Wallet = "";
                });
        }
    };

});

app.controller("WithdrawalController", function(CsrfToken, $scope, $http, AmountService, SweetAlert, ngDialog) {
    $scope.isProcessing = false;
    $scope.withdrawal = {};
    $scope.withdrawal.Amount;
    $scope.withdrawal.Phone = "";
    $scope.withdrawal.BankAccount = "";
    $scope.withdrawal.BankNo = "";
    $scope.withdrawal.BankAccountNo = "";
    $scope.havePhone = false;
    $scope.haveName = false;
    $scope.haveBankNo = false;
    $scope.haveBankAccountNo = false;

    $scope.$watch('withdrawal.Wallet', function() {
        $scope.enableWithdrawalMoney = "";
        if ($scope.withdrawal.Wallet != undefined) {
            var wallet = $scope.withdrawal.Wallet;
            var url = "/api/finance/CheckMemberBalanceSingle?Wallet=" + wallet;
            $http.get(url).success(function(data) {
                $scope.enableWithdrawalMoney = data.balance + "원 입니다";
            });
        }
    });

    var url = "/api/player/GetNewMemberInfo";
    $http.get(url).success(function(data) {
        /*WILL DISABLE IF HAS DATA*/
        $scope.withdrawal.Phone = data.bonus.MemberPhone;
        $scope.withdrawal.BankAccount = data.bonus.MemberName;

        $scope.withdrawal.BankNo = data.bonus.BankCode;

        if ($scope.withdrawal.BankNo == null) {
            $scope.withdrawal.BankNo = "0";
        }

        $scope.withdrawal.BankAccountNo = data.bonus.AccountNumber;
        $scope.havePhone = data.bonus.MemberPhone;
        $scope.haveName = data.bonus.MemberName;
        $scope.haveBankNo = data.bonus.BankCode;
        $scope.haveBankAccountNo = data.bonus.AccountNumber;
        $scope.haveBank = $scope.withdrawal.BankNo;

        $scope.withdrawal.PhoneOutput = data.bonus.MemberPhone.slice(0, -3) + '***';
        $scope.withdrawal.NameOutput = data.bonus.MemberName.split(' ').slice(-1).join(' ').slice(0, -2) + '**';

        if ($scope.withdrawal.BankNo == "0") {
            $scope.haveBankNo = false;
        } else if ($scope.withdrawal.BankNo == "1" && ($scope.withdrawal.BankAccount == undefined && $scope.withdrawal.BankAccountNo == undefined)) {
            $scope.haveBankNo = false;
        }

        $scope.withdrawal.AccountNoOutput = data.bonus.AccountNumber.slice(0, -3) + '***';
        $scope.withdrawal.NameOutput = data.bonus.MemberName.split(' ').slice(-1).join(' ').slice(0, -2) + '**';
    });

    $scope.addAmount = function(sumAmount) {
        if (!$scope.withdrawal.Amount) {
            $scope.withdrawal.Amount = 0;
        }
        $scope.withdrawal.Amount = AmountService.sumAmount($scope.withdrawal.Amount, sumAmount);
    };
    $scope.resetAmount = function() {
        $scope.withdrawal.Amount = AmountService.resetAmount();
        $scope.withdrawal.Amount = undefined;
    };

    $scope.processForm = function() {
        if (!$scope.isProcessing) {
            $scope.isProcessing = true;
            var url = "../api/finance/RequestWithdrawal";
            CsrfToken.HttpRequest('POST', url, $scope.withdrawal) // pass in data as strings
                .success(function(data) {
                    //$scope.error.status = false;
                    if (data.result == 1) {
                        if (bowser.msie && bowser.version <= 8) {
                            alert(data.message);
                        } else {
                            SweetAlert.swal(data.message, "", "success");
                            ngDialog.close();
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
                                })
                            } else {
                                SweetAlert.swal(data.message, "다시 시도해 보세요.", "error");
                            }
                        }
                    }
                }).error(function(data, result) {
                    alert("withdraw" + data);
                    console.error('Repos error', result, data);
                })["finally"](function() {
                    $scope.isProcessing = false;
                    $scope.withdrawal.Wallet = "";
                    $scope.withdrawal.Amount = "";
                    $scope.withdrawal.Memo = "";
                });
        }
    };
});

app.controller("CouponController", function(CsrfToken, $scope, $http, $rootScope, SweetAlert, $filter) {
    $scope.couponList = {};
    $scope.filteredPage = [];
    $scope.totalItems = 0;
    $scope.currentPage = 1;
    $scope.maxSize = 5;
    $scope.numPerPage = 10;
    $scope.currentDate = $filter('date')(new Date(), 'yyyy-MM-dd');

    $rootScope.loadCoupon = function() {
        var url = "/api/player/GetPlayerCoupon";
        $http.get(url).success(function(data) {
            $rootScope.couponCount = 0;
            $scope.couponList = data.list;
            angular.forEach($scope.couponList, function(value, key) {
                if (value.Status == "G" && value.CouponExpiredDate >= $scope.currentDate) {
                    $rootScope.couponCount += 1;
                }
            });
        })["finally"](function() {
            $scope.totalItems = $scope.couponList.length;
            $scope.numPagesCal = function() {
                return Math.ceil($scope.couponList.length / $scope.numPerPage);
            };

            $scope.numPages = $scope.numPagesCal();

            $scope.$watch('currentPage + numPerPage', function() {
                var begin = (($scope.currentPage - 1) * $scope.numPerPage),
                    end = begin + $scope.numPerPage;
                $scope.filteredPage = $scope.couponList.slice(begin, end);
            });
        });
    };

    $scope.useCoupon = function(GameCode, CouponCode) {

        var url = "../api/marketing/UseCoupon";
        var param = {
            'GameCode': GameCode,
            'CouponCode': CouponCode
        }
        CsrfToken.HttpRequest('POST', url, param) // pass in data as strings
            .success(function(data) {
                //console.log(data+ " " +self+ " "+couponCode+ " "+$scope.claim);
                if (data.result == 1) {
                    if (data.alert) {
                        if (bowser.msie && bowser.version <= 8) {
                            alert(data.message);
                        } else {
                            SweetAlert.swal(data.message, "", "success");
                        }
                        $scope.loadCoupon();
                    }
                } else {
                    if (data.alert) {
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
                                SweetAlert.swal(data.message, "다시 시도해보세요", "error");
                            }
                        }
                    }
                }
            }).error(function(data, result) {
                console.error('Repos error', result, data);
            })["finally"](function() {});
    }
});
app.controller("SettlementController", function($scope, $http, $rootScope, SweetAlert, $filter, ngDialog) {
    $scope.settlementList = {};
    $scope.payout = {};

    $scope.filteredPage = [];
    $scope.totalItems = 0;
    $scope.currentPage = 1;
    $scope.maxSize = 5;
    $scope.numPerPage = 10;
    $scope.currentDate = $filter('date')(new Date(), 'yyyy-MM-dd hh:mm:ss');

    $scope.payout.Phone = "";
    $scope.payout.BankAccount = "";
    $scope.payout.BankNo = "";
    $scope.payout.BankAccountNo = "";
    $scope.havePhone = false;
    $scope.haveName = false;
    $scope.haveBankNo = false;
    $scope.haveBankAccountNo = false;

    $scope.displaySettlementWithdraw = function(code, amount) {
        ngDialog.open({
            template: 'popup/settlement-withdraw.php',
            controller: '',
            showClose: true,
            className: 'ngdialog-theme-default ngdialog-settlementwithdraw',
            scope: $scope,
            data: {
                code: code,
                amount: amount
            }
        });
    };

    var url = "/api/player/GetNewMemberInfo";
    $http.get(url).success(function(data) {
        /*WILL DISABLE IF HAS DATA*/
        $scope.payout.Phone = data.bonus.MemberPhone;
        $scope.payout.BankAccount = data.bonus.MemberName;
        $scope.payout.BankNo = data.bonus.BankCode;
        $scope.payout.BankAccountNo = data.bonus.AccountNumber;
        $scope.havePhone = data.bonus.MemberPhone;
        $scope.haveName = data.bonus.MemberName;
        $scope.haveBankNo = data.bonus.BankCode;
        $scope.haveBankAccountNo = data.bonus.AccountNumber;
        $scope.haveBank = data.bonus.MemberBank;

        if ($scope.payout.BankNo == "0") {
            $scope.haveBankNo = false;
        } else if ($scope.payout.BankNo == "1" && ($scope.payout.BankAccount == undefined && $scope.payout.BankAccountNo == undefined)) {
            $scope.haveBankNo = false;
        }

        $scope.payout.AccountNoOutput = data.bonus.AccountNumber.slice(0, -3) + '***';
        $scope.payout.PhoneOutput = data.bonus.MemberPhone.slice(0, -3) + '***';
        $scope.payout.NameOutput = data.bonus.MemberName.split(' ').slice(-1).join(' ').slice(0, -2) + '**';
    });

    $rootScope.loadSettlement = function() {
        var url = "/api/player/GetSettlementList";
        $http.get(url).success(function(data) {
            $scope.settlementList = data.list;

            $rootScope.settlementCount = data.totalUnused;
            var settlementCountAll = data.totalRecord;

            if (settlementCountAll == 1) {
                //CONVERT TO ARRAY
                $scope.settlementList = [$scope.settlementList];
            }

        })["finally"](function() {
            $scope.totalItems = $scope.settlementList.length;
            $scope.numPagesCal = function() {
                return Math.ceil($scope.settlementList.length / $scope.numPerPage);
            };
            $scope.numPages = $scope.numPagesCal();
            $scope.$watch('currentPage + numPerPage', function() {
                var begin = (($scope.currentPage - 1) * $scope.numPerPage),
                    end = begin + $scope.numPerPage;
                $scope.filteredPage = $scope.settlementList.slice(begin, end);
            });
        });
    };

    $scope.settlementProcess = function(status, code, amount, wallet) {
        if (!$scope.isProcessing) {
            $scope.isProcessing = true;
            $scope.payout.Status = status;
            $scope.payout.SettlementCode = code;
            $scope.payout.Amount = amount;
            $scope.payout.Wallet = wallet;

            var url = "/api/marketing/UseSettlement";
            $http({
                method: 'POST',
                url: url,
                data: $.param(
                    $scope.payout
                ), // pass in data as strings
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                } // set the headers so angular passing info as form data (not request payload)
            }).success(function(data) {
                //$scope.error.status = false;
                if (data.result == 1) {
                    if (bowser.msie && bowser.version <= 8) {
                        alert(data.message);
                    } else {
                        SweetAlert.swal(data.message, "", "success");
                        ngDialog.close();
                    }
                } else {
                    if (bowser.msie && bowser.version <= 8) {
                        alert(data.message);
                    } else {
                        SweetAlert.swal(data.message, "다시 시도해보세요", "error");
                    }
                }
            }).error(function(data, result) {
                console.error('Repos error', result, data);
            })["finally"](function() {
                $rootScope.loadSettlement();
                $scope.isProcessing = false;
            });
        }
    };
});

app.controller("FriendController", function($scope, $rootScope, $http) {
    $scope.filteredPage = [];
    $scope.friendList = {};
    $scope.totalItems = 0;
    $scope.currentPage = 1;
    $scope.maxSize = 5;
    $scope.numPerPage = 10;

    $rootScope.getFriendList = function() {
        var url = "../api/player/GetFriendList";
        $http.get(url).success(function(data) {
            $scope.friendList = data.list;
        })["finally"](function() {
            $scope.totalItems = $scope.friendList.length;
            //console.log($scope.totalItems);
            $scope.numPagesCal = function() {
                return Math.ceil($scope.friendList.length / $scope.numPerPage);
            };
            $scope.numPages = $scope.numPagesCal();
            $scope.$watch('currentPage + numPerPage', function() {
                var begin = (($scope.currentPage - 1) * $scope.numPerPage),
                    end = begin + $scope.numPerPage;
                $scope.filteredPage = $scope.friendList.slice(begin, end);
                $scope.begin = begin;
                $scope.ctr = $scope.totalItems;
            });
        });
    };
});

app.controller("ChangePasswordController", function($scope, $rootScope, $http, SweetAlert, ngDialog, $timeout) {
    $scope.changePwd = {};
    $scope.processForm = function() {
        $scope.isProcessing = true;
        var url = "../api/player/ChangePassword";
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.changePwd), // pass in data as strings
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            } // set the headers so angular passing info as form data (not request payload)
        }).success(function(data) {
            if (data.result === 1) {
                if (bowser.msie && bowser.version <= 8) {
                    alert(data.message);
                } else {
                    SweetAlert.swal(data.message, "", "success");
                    ngDialog.close();
                    $timeout(function() {
                        $http.get("/api/player/Logout")
                            .success(function(data) {
                                if (data.result == 1) {
                                    if (bowser.msie && bowser.version <= 8) {
                                        alert(data.message);
                                    } else {
                                        SweetAlert.swal("로그아웃 되었습니다", "", "success");
                                    }
                                    /*DELAY RELOAD AFTER LOGGING OUT*/
                                    $timeout(function() {
                                        // loggedInStatus.setLoggedOutStatus();
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
                            })
                    }, 2000, true);
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
        }).error(function(data, status) {
            console.error('Repos error', status, data);
        })["finally"](function() {
            $scope.isProcessing = false;
        });
    };
});