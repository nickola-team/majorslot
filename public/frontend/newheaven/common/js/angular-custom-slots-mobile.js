app.controller('SlotController', function($rootScope, $scope, $http, $filter, SweetAlert, $cookies) {
    $scope.gspNo = "";
    //$scope.gspSlotItems = {};
    $scope.slotItems = {};
    $scope.category = {};

    $scope.slotNav = [{
            type: 'slot',
            gspNo: 1065,
            title: '프라그마틱플레이'
        },
        {
            type: 'slot',
            gspNo: 1112,
            title: '마이크로게이밍'
        },
        {
            type: 'slot',
            gspNo: 1012,
            title: '아시아게이밍'
        },
        {
            type: 'slot',
            gspNo: 1156,
            title: '플레이앤고'
        },
        {
            type: 'slot',
            gspNo: 1027,
            title: '게임플레이'
        },
        {
            type: 'slot',
            gspNo: 1066,
            title: '하바네로'
        },
        {
            type: 'slot',
            gspNo: 1125,
            title: '포켓게임스'
        },
        {
            type: 'slot',
            gspNo: 1138,
            title: '씨큐나인게이밍'
        },
        {
            type: 'slot',
            gspNo: 1034,
            title: '벳소프트'
        },
        {
            type: 'slot',
            gspNo: 1047,
            title: '플레이테크'
        },
        {
            type: 'slot',
            gspNo: 1008,
            title: '드림테크'
        },
        {
            type: 'slot',
            gspNo: 1124,
            title: '퀵스핀'
        },
        {
            type: 'slot',
            gspNo: 1080,
            title: '스페이드게이밍'
        },
        {
            type: 'slot',
            gspNo: 1099,
            title: '아메바'
        },
        {
            type: 'slot',
            gspNo: 1098,
            title: '탑트렌드게이밍'
        },
        {
            type: 'slot',
            gspNo: 1052,
            title: '비비아이엔'
        },
        {
            type: 'slot',
            gspNo: 1100,
            title: '가마트론'
        },
        {
            type: 'slot',
            gspNo: 1079,
            title: '리얼타임게이밍'
        },
        {
            type: 'slot',
            gspNo: 1184,
            title: '플레이손'
        },
    ];

    responsiveThumbnail();

    $scope.initMicro = function() {
        $rootScope.loadSlot(1065);
    };

    $rootScope.loadSlot = function(gspNo, category) {
        $rootScope.activeSlotGsp = gspNo;
        $scope.navTab = gspNo;
        if (gspNo == 1071) {
            return $scope.playGame('1071', 'slot', 'download');
        }
        setTimeout(function() {
            if (gspNo == 1102 || gspNo === 1115) {
                responsiveThumbnails('.responsive-thumbnails', '120px', '4px', '120px');
            } else if (gspNo == 1131) {
                responsiveThumbnails('.responsive-thumbnails', '120px', '4px', '165px');
            } else if (gspNo == 1052 || gspNo == 1138) {
                responsiveThumbnails('.responsive-thumbnails', '120px', '4px', '155px');
            } else {
                responsiveThumbnails('.responsive-thumbnails', '120px', '4px', '215px');
            }
        }, 100);

        $scope.slotItems = {};
        if ($scope.gspSlotItems == undefined) {
            var lang = 'ko_KR';
            $http.get("/api/system/GetGspSlotMobileGameList?lang=" + lang)
                .success(function(data) {
                    $scope.gspSlotItems = data;
                }).error(function(data, result) {
                    // console.error('Repos error', result, data);
                })["finally"](function() {
                    $scope.gspNo = gspNo;
                });
        }

        $scope.gspNo = gspNo;

        $('.slot-game .slot-button, .slot-sub-button li').click(function() {
            $(this).siblings('.active').removeClass('active');
            $(this).addClass('active');
        });

        if (category == undefined) {
            if (gspNo == gspNo) {
                $scope.category = "Mobile";
                $('.slot-' + gspNo + '-first').addClass('active');
            } else {
                $scope.category = category;
            }
        }

        $scope.category = 'Mobile';

        $scope.slotFilter = function(slot) {
            return (slot.cat == $scope.category);
        };
    }
});