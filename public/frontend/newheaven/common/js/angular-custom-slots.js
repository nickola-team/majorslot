app.controller('SlotController', function($rootScope, $scope, $http, $filter, SweetAlert, $sce) {
    $scope.gspNo = "";
    //$scope.gspSlotItems = {};
    $scope.slotItems = {};
    $scope.category = {};


    $rootScope.displaySlotItemPlaceHolder = function(num) {
        return new Array(num);
    }

    //CALL LOAD SLOT FROM OTHER CONTROLLER
    $rootScope.getSlot = function() {
        $rootScope.loadSlot($rootScope.triggerLoadSlot);
    };

    $scope.getProgressiveFlash = function(gameCode) {
        var options = "";
        if (gameCode == "car" || gameCode == "frtf4-1") {
            options = "&local=1";
        }
        return $sce.trustAsResourceUrl("http://tickers.playtech.com/jackpots/new_jackpot.swf?info=1&casino=luckystar88&game=" + gameCode + "&font_face=arial&bold=true&currency=CNY&font_color=F9AA28" + options);
    };

    $rootScope.isLoadingSlot = true;

    $rootScope.loadSlot = function(gspNo, category) {
        if (gspNo == undefined) {
            gspNo = 1065;
        }

        //TRIGGER COMING SOON
        if (gspNo == 9999) {
            $rootScope.comingSoon();
            return; //end function
        }

        //TRIGGER PLAYGAME
        // if (gspNo == 1052) {
        //   $scope.playGame('1052', 'game', '');
        //   return;
        // }

        $scope.slotItems = {};

        if ($scope.readLang == undefined || $scope.readLang != $rootScope.currentLang || $scope.gspSlotItems == undefined) {
            var lang = $rootScope.currentLang;

            $http.get("/api/system/GetGspSlotGameList?lang=ko_KR")
                .success(function(data) {
                    $rootScope.isLoadingSlot = false;
                    $scope.gspSlotItems = data;
                }).error(function(data, result) {
                    console.error('Repos error', result, data);
                })["finally"](function() {
                    $scope.gspNo = gspNo;
                    $scope.readLang = $rootScope.currentLang;
                });
        }

        $scope.gspNo = gspNo;

        $scope.slotGames = [{
                'gspNo': 1065,
                'gspName': '프로그마틱 슬롯',
                'gspClass': 'slots-button-1slots-button-1',
                'video': 'video-pragmatic.m4v',
                'frame': 'frame-slot-pragmatic.png'
            },
            {
                'gspNo': 1066,
                'gspName': '하바네로 슬롯',
                'gspNameText': '',
                'gspClass': 'slots-button-2',
                'video': 'video-habanero.mp4',
                'frame': 'frame-slot-habanero.png'
            },
            {
                'gspNo': 1156,
                'gspName': '플레이앤고 슬롯',
                'gspClass': 'slots-button-3',
                'video': 'video-playngo.mp4',
                'frame': 'frame-slot-playngo.png'
            },
            {
                'gspNo': 1124,
                'gspName': '퀵스핀 슬롯',
                'gspClass': 'slots-button-4',
                'video': 'video-quickspin.m4v',
                'frame': 'frame-slot-quickspin.png'
            },
            {
                'gspNo': 1112,
                'gspName': '마이크로게이밍 슬롯',
                'gspClass': 'slots-button-5',
                'video': 'video-microgaming.mp4',
                'frame': 'frame-slot-microgaming.png'
            },
            {
                'gspNo': 1027,
                'gspName': '게임플레이 슬롯',
                'gspClass': 'slots-button-6',
                'video': 'video-gameplay.m4v',
                'frame': 'frame-slot-gameplay.png'
            },
            {
                'gspNo': 1012,
                'gspName': '아시아 게이밍 슬롯',
                'gspClass': 'slots-button-7',
                'video': 'video-asiagaming.m4v',
                'frame': 'frame-slot-asiagaming.png'
            },
            {
                'gspNo': 1034,
                'gspName': '벳소프트 슬롯',
                'gspClass': 'slots-button-8',
                'video': 'video-betsoft.mp4',
                'frame': 'frame-slot-betsoft.png'
            },
            {
                'gspNo': 1038,
                'gspName': '플레이텍 슬롯',
                'gspClass': 'slots-button-9',
                'video': 'video-playtech.m4v',
                'frame': 'frame-slot-playtech.png'
            },
            {
                'gspNo': 1052,
                'gspName': '비비아이엔 슬롯',
                'gspClass': 'slots-button-10',
                'video': 'video-bbin.mp4',
                'frame': 'frame-slot-bbin.png'
            },
            {
                'gspNo': 1176,
                'gspName': '넷엔트 슬롯',
                'gspClass': 'slots-button-11',
                'video': 'video-netent.mp4',
                'frame': 'frame-slot-netent.png'
            },
        ];

        $scope.getGspByNo = function(gspNumber) {
            var gspArrayName = {};
            gspArrayName[1112] = [{
                    'subSlotName': 'Video Slot',
                    'subSlotParam': 'Video_Slot',
                    'subSlotClass': 'slot-1005-4'
                },
                // {'subSlotName': 'Slot', 'subSlotParam': 'Slot', 'subSlotClass': 'slot-1005-5'},
                {
                    'subSlotName': 'Advance Slots',
                    'subSlotParam': 'Advanced_Slot',
                    'subSlotClass': 'slot-1005-1'
                },
                {
                    'subSlotName': 'Bonus Slot',
                    'subSlotParam': 'Bonus_Slot',
                    'subSlotClass': 'slot-1005-2'
                },
                {
                    'subSlotName': 'Feature Slot',
                    'subSlotParam': 'Feature_Slot',
                    'subSlotClass': 'slot-1005-3'
                },
                // {'subSlotName': 'Casual', 'subSlotParam': 'Casual_Game', 'subSlotClass': 'slot-1005-6'},
                {
                    'subSlotName': 'Video Poker',
                    'subSlotParam': 'Video_Poker',
                    'subSlotClass': 'slot-1005-8'
                },
                // {'subSlotName': 'Scratch Card', 'subSlotParam': 'Scratch_Card', 'subSlotClass': 'slot-1005-7'},
                {
                    'subSlotName': 'Table Games',
                    'subSlotParam': 'Table_Games',
                    'subSlotClass': 'slot-1005-9'
                }
            ];

            gspArrayName[1088] = [{
                    'subSlotName': 'Jackpot',
                    'subSlotParam': 'Jackpot',
                    'subSlotClass': 'slot-1088-1'
                },
                {
                    'subSlotName': 'Slot',
                    'subSlotParam': 'Slot',
                    'subSlotClass': 'slot-1088-2'
                },
                {
                    'subSlotName': 'Video Poker',
                    'subSlotParam': 'Video Poker',
                    'subSlotClass': 'slot-1088-3'
                },
                {
                    'subSlotName': 'Scratch Card',
                    'subSlotParam': 'Scratch Card',
                    'subSlotClass': 'slot-1088-4'
                },
                {
                    'subSlotName': 'Table Games',
                    'subSlotParam': 'Table Game',
                    'subSlotClass': 'slot-1088-5'
                },
                {
                    'subSlotName': 'Other Games',
                    'subSlotParam': 'Other Games',
                    'subSlotClass': 'slot-1088-6'
                }
            ];

            gspArrayName[1012] = [{
                    'subSlotName': 'Slots',
                    'subSlotParam': 'Slots',
                    'subSlotClass': 'slot-1012-1 '
                },
                {
                    'subSlotName': 'Video Poker',
                    'subSlotParam': 'Video_Poker',
                    'subSlotClass': 'slot-1012-2'
                },
                {
                    'subSlotName': 'Table Games',
                    'subSlotParam': 'Table_Game',
                    'subSlotClass': 'slot-1012-3'
                }
            ];

            gspArrayName[1027] = [{
                'subSlotName': 'R Slots',
                'subSlotParam': 'R_Slot',
                'subSlotClass': 'slot-1027-1 '
            }];

            gspArrayName[1034] = [{
                    'subSlotName': 'Slots',
                    'subSlotParam': 'Slots',
                    'subSlotClass': 'slot-1034-1'
                },
                {
                    'subSlotName': 'Video Poker',
                    'subSlotParam': 'Video_Poker',
                    'subSlotClass': 'slot-1034-4'
                },
                {
                    'subSlotName': 'Table Games',
                    'subSlotParam': 'Table_Games',
                    'subSlotClass': 'slot-1034-3'
                },
                // {'subSlotName': 'Pyramid Poker', 'subSlotParam': 'Pyramid_Poker', 'subSlotClass': 'slot-1034-5'},
                {
                    'subSlotName': 'Multihand Poker',
                    'subSlotParam': 'Multihand_Poker',
                    'subSlotClass': 'slot-1034-6'
                },
                // {'subSlotName': 'Keno', 'subSlotParam': 'Keno', 'subSlotClass': 'slot-1034-7'},
                // {'subSlotName': 'Arcades', 'subSlotParam': 'Soft_Games', 'subSlotClass': 'slot-1034-2'}
            ];

            gspArrayName[1047] = [{
                    'subSlotName': 'Jackpot',
                    'subSlotParam': 'Progressive',
                    'subSlotClass': 'slot-1047-1'
                },
                // {'subSlotName': 'Slot Machine', 'subSlotParam': 'Slot_Machines', 'subSlotClass': 'slot-1038-2'},
                {
                    'subSlotName': 'Slots',
                    'subSlotParam': 'Video_Slots',
                    'subSlotClass': 'slot-1047-4'
                },
                {
                    'subSlotName': 'Table Card Games',
                    'subSlotParam': 'Table_Card_Games',
                    'subSlotClass': 'slot-1047-5'
                },
                {
                    'subSlotName': 'Arcade',
                    'subSlotParam': 'Arcade',
                    'subSlotClass': 'slot-1047-3'
                },
                // {'subSlotName': 'Scratch Cards', 'subSlotParam': 'Scratch_Cards', 'subSlotClass': 'slot-1047-6'}
            ];

            gspArrayName[1037] = [{
                    'subSlotName': 'Slots',
                    'subSlotParam': 'Slots',
                    'subSlotClass': 'slot-1037-1'
                },
                {
                    'subSlotName': 'Table Games',
                    'subSlotParam': 'Table_Games',
                    'subSlotClass': 'slot-1037-2'
                },
                {
                    'subSlotName': 'Video Poker',
                    'subSlotParam': 'Video_Poker',
                    'subSlotClass': 'slot-1037-3'
                }
            ];

            gspArrayName[1066] = [{
                    'subSlotName': 'Slots',
                    'subSlotParam': 'Slots',
                    'subSlotClass': 'slot-1066-1'
                },
                {
                    'subSlotName': 'Table Games',
                    'subSlotParam': 'Table_Games',
                    'subSlotClass': 'slot-1066-2'
                },
                {
                    'subSlotName': 'Video Poker',
                    'subSlotParam': 'Video_Poker',
                    'subSlotClass': 'slot-1066-3'
                }
            ];

            gspArrayName[1036] = [{
                    'subSlotName': 'Slot',
                    'subSlotParam': 'Slot',
                    'subSlotClass': 'slot-1036-1'
                },
                {
                    'subSlotName': 'Video Poker',
                    'subSlotParam': 'Video_Poker',
                    'subSlotClass': 'slot-1036-2'
                },
                {
                    'subSlotName': 'Scratch Card',
                    'subSlotParam': 'Scratch_Card',
                    'subSlotClass': 'slot-1036-3'
                },
                {
                    'subSlotName': 'Table Games',
                    'subSlotParam': 'Table_Games',
                    'subSlotClass': 'slot-1036-4'
                },
                {
                    'subSlotName': 'Other Games',
                    'subSlotParam': 'Others',
                    'subSlotClass': 'slot-1036-5'
                }
            ];

            gspArrayName[1042] = [{
                    'subSlotName': 'Slots',
                    'subSlotParam': 'Slot',
                    'subSlotClass': 'slot-1042-1'
                },
                {
                    'subSlotName': 'Arcade',
                    'subSlotParam': 'Arcade',
                    'subSlotClass': 'slot-1042-2'
                },
                {
                    'subSlotName': 'Video Poker',
                    'subSlotParam': 'Video_Poker',
                    'subSlotClass': 'slot-1042-3'
                },
                {
                    'subSlotName': 'Table Games',
                    'subSlotParam': 'Table_Games',
                    'subSlotClass': 'slot-1042-4'
                }
            ];

            gspArrayName[1053] = [{
                    'subSlotName': 'Progressive',
                    'subSlotParam': 'Progressive',
                    'subSlotClass': 'slot-1053-1'
                },
                {
                    'subSlotName': 'Slots',
                    'subSlotParam': 'Video_Slots',
                    'subSlotClass': 'slot-1053-2'
                },
                {
                    'subSlotName': 'Classic',
                    'subSlotParam': 'Classic',
                    'subSlotClass': 'slot-1053-3'
                },
                {
                    'subSlotName': 'Table Games',
                    'subSlotParam': 'Table_Games',
                    'subSlotClass': 'slot-1053-4'
                },
                {
                    'subSlotName': 'Video Poker',
                    'subSlotParam': 'Video_Poker',
                    'subSlotClass': 'slot-1053-5'
                }
            ];

            gspArrayName[1087] = [{
                    'subSlotName': 'Slot',
                    'subSlotParam': 'Slot',
                    'subSlotClass': ''
                },
                {
                    'subSlotName': 'Table Games',
                    'subSlotParam': 'Table Game',
                    'subSlotClass': ''
                },
            ];

            gspArrayName[1061] = [{
                    'subSlotName': 'Slots',
                    'subSlotParam': 'Slot',
                    'subSlotClass': 'slot-1061-1'
                },
                {
                    'subSlotName': 'Table Games',
                    'subSlotParam': 'Table_Game',
                    'subSlotClass': 'slot-1061-2'
                },
            ];

            gspArrayName[1062] = [{
                'subSlotName': 'Slots',
                'subSlotParam': 'Slot',
                'subSlotClass': 'slot-1062-1'
            }];

            gspArrayName[1063] = [{
                    'subSlotName': 'Slots',
                    'subSlotParam': 'Slot',
                    'subSlotClass': 'slot-1063-1'
                },
                {
                    'subSlotName': 'Table',
                    'subSlotParam': 'Table_Game',
                    'subSlotClass': 'slot-1063-2'
                }
            ];

            gspArrayName[1065] = [{
                    'subSlotName': 'Video Slot',
                    'subSlotParam': 'Video_Slot',
                    'subSlotClass': 'slot-1065-1'
                },
                {
                    'subSlotName': 'Slot',
                    'subSlotParam': 'Slot',
                    'subSlotClass': 'slot-1065-2'
                },
                {
                    'subSlotName': 'Table Games',
                    'subSlotParam': 'Table_Game',
                    'subSlotClass': 'slot-1065-3'
                }
            ];

            gspArrayName[1067] = [{
                    'subSlotName': 'Slot',
                    'subSlotParam': 'Video_Slot',
                    'subSlotClass': 'slot-1067-1'
                },
                {
                    'subSlotName': 'Classic',
                    'subSlotParam': 'Classic_Slot',
                    'subSlotClass': 'slot-1067-2'
                },
                {
                    'subSlotName': 'Land Based Slot',
                    'subSlotParam': 'Vertical_Slot',
                    'subSlotClass': 'slot-1067-3'
                }
            ];

            gspArrayName[1052] = [{
                    'subSlotName': 'Slots',
                    'subSlotParam': 'Slots',
                    'subSlotClass': 'slot-1052-1'
                },
                {
                    'subSlotName': 'Feature Slots',
                    'subSlotParam': 'Feature_Slot',
                    'subSlotClass': 'slot-1052-1'
                },
                {
                    'subSlotName': 'Table Games',
                    'subSlotParam': 'Table_Games',
                    'subSlotClass': 'slot-1052-1'
                },
                {
                    'subSlotName': 'Arcade',
                    'subSlotParam': 'Arcade',
                    'subSlotClass': 'slot-1052-1'
                },
            ];

            gspArrayName[1071] = [{
                'subSlotName': 'Slot',
                'subSlotParam': 'Slot',
                'subSlotClass': 'slot-1071-1'
            }, ];

            gspArrayName[1079] = [{
                'subSlotName': 'Slot',
                'subSlotParam': 'Slot',
                'subSlotClass': 'slot-1079-1'
            }, ];

            gspArrayName[1080] = [{
                    'subSlotName': 'Slot',
                    'subSlotParam': 'Slot',
                    'subSlotClass': 'slot-1080-1'
                },
                {
                    'subSlotName': 'Arcade',
                    'subSlotParam': 'Arcade',
                    'subSlotClass': 'slot-1080-2'
                },
                {
                    'subSlotName': 'Table Games',
                    'subSlotParam': 'Table',
                    'subSlotClass': 'slot-1080-3'
                },
            ];

            gspArrayName[1085] = [{
                'subSlotName': 'Slot',
                'subSlotParam': 'Slots',
                'subSlotClass': ''
            }];

            gspArrayName[1086] = [{
                    'subSlotName': 'Slots',
                    'subSlotParam': 'Slots',
                    'subSlotClass': ''
                },
                {
                    'subSlotName': 'Arcade',
                    'subSlotParam': 'Arcade',
                    'subSlotClass': ''
                },
                {
                    'subSlotName': 'Table Games',
                    'subSlotParam': 'Tables',
                    'subSlotClass': ''
                }
            ];

            gspArrayName[1098] = [{
                    'subSlotName': 'Slots',
                    'subSlotParam': 'Slots',
                    'subSlotClass': ''
                },
                // {'subSlotName': 'Table Games', 'subSlotParam': 'Tables', 'subSlotClass': ''},
            ];

            gspArrayName[1099] = [{
                'subSlotName': 'Slots',
                'subSlotParam': 'Slots',
                'subSlotClass': ''
            }, ];

            gspArrayName[1097] = [{
                'subSlotName': 'Slots',
                'subSlotParam': 'Slots',
                'subSlotClass': ''
            }, ];

            gspArrayName[1008] = [{
                'subSlotName': 'Slots',
                'subSlotParam': 'Slots',
                'subSlotClass': ''
            }, ];

            gspArrayName[1100] = [{
                'subSlotName': 'Slots',
                'subSlotParam': 'Slots',
                'subSlotClass': 'slot-1100-1'
            }, ];

            gspArrayName[1102] = [{
                'subSlotName': 'Slots',
                'subSlotParam': 'Slots',
                'subSlotClass': 'slot-1102-1'
            }, ];

            gspArrayName[1115] = [{
                'subSlotName': 'Slots',
                'subSlotParam': 'Slots',
                'subSlotClass': 'slot-1102-1'
            }, ];

            gspArrayName[1156] = [{
                'subSlotName': 'Slots',
                'subSlotParam': 'Slot',
                'subSlotClass': ''
            }, ];

            gspArrayName[1176] = [{
                'subSlotName': 'Slot',
                'subSlotParam': 'Slot',
                'subSlotClass': ''
            }, ];

            gspArrayName[1124] = [{
                'subSlotName': 'Slots',
                'subSlotParam': 'Slot',
                'subSlotClass': 'slot-1062-1'
            }];

            gspArrayName[1125] = [{
                    'subSlotName': 'Slots',
                    'subSlotParam': 'Slot',
                    'subSlotClass': 'slot-1037-1'
                },
                {
                    'subSlotName': 'Table Card Games',
                    'subSlotParam': 'Table_Card_Games',
                    'subSlotClass': 'slot-1037-2'
                },
            ];

            gspArrayName[1138] = [{
                    'subSlotName': 'Slots',
                    'subSlotParam': 'Slots',
                    'subSlotClass': 'slot-1138-1'
                },
                {
                    'subSlotName': 'Table Games',
                    'subSlotParam': 'Table_Games',
                    'subSlotClass': 'slot-1138-1'
                },
                {
                    'subSlotName': 'Arcade',
                    'subSlotParam': 'Arcade',
                    'subSlotClass': 'slot-1138-1'
                },
            ];

            gspArrayName[1131] = [{
                'subSlotName': 'Slots',
                'subSlotParam': 'Slot',
                'subSlotClass': ''
            }];

            gspArrayName[1184] = [{
                'subSlotName': 'Slots',
                'subSlotParam': 'Slot',
                'subSlotClass': ''
            }];

            return gspArrayName[gspNumber];
        };

        //ADD VALUE TO CATEGORY WHEN UNDFINED
        if (category == undefined) {
            angular.forEach($rootScope.gameButtons, function(values) {
                var headStart = 0;
                angular.forEach($scope.getGspByNo(values.gspNo), function(subValues) {
                    if (headStart == 0) { //SET FIRST SUBCATEGORY TO BE ACTIVE
                        if (gspNo == values.gspNo) {
                            $scope.category = subValues.subSlotParam;
                        }
                    }
                    headStart++;
                });
            });
        } else {
            $scope.category = category;
        }

        $rootScope.SearchValue = "NONE"; //PART OF SEARCH

        /*SEARCH*/
        if ($rootScope.SearchValue == undefined || $rootScope.SearchValue == '') {
            $rootScope.SearchValue = "NONE";
        }
        $rootScope.setSearchValue = function(txtSearch) {
            $rootScope.SearchValue = txtSearch;
            if (txtSearch != '' || txtSearch != undefined) {
                if ($rootScope.SearchValue == undefined || $rootScope.SearchValue == '') {
                    $rootScope.SearchValue = "NONE";
                }
            }
        };
        /*END SEARCH*/

        $scope.slotFilter = function(slot) {
            var cond;
            if ($rootScope.SearchValue == "NONE") {
                cond = slot.cat == $scope.category;
                return (cond);
            } else {
                if (slot.cat != "Mobile" && slot.cat != "Top_Slot") {
                    // console.log(slot.cat);
                    var output = slot.GameName.toUpperCase().indexOf($rootScope.SearchValue.toUpperCase());
                    if (output < 0) {
                        cond = false;
                    } else {
                        cond = true;
                    }
                }
            }
            return cond;
        };
    };
});