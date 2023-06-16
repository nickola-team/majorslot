function goHome() {
    top.location.href = "/";
}

function goCasino() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: '/etc/casino.asp',
            width: 955,
            height: 600
        });
    } else {
        showLoginAlert();
    }
}

function goRemote() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: '/etc/remote.asp',
            width: 955,
            height: 600
        });
    } else {
        showLoginAlert();
    }
}

function goRule(type) {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: '/etc/rule/' + type + '.asp',
            width: 955,
            height: 600
        });
    } else {
        showLoginAlert();
    }
}


function goinfoBaccarat() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: '/info/info_rule.asp?baccarat',
            width: 850,
            height: 600
        });
    } else {
        showLoginAlert();
    }
}

function goinfoBlackjack() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: '/info/info_rule.asp?blackjack',
            width: 850,
            height: 600
        });
    } else {
        showLoginAlert();
    }
}

function goinfoRoulette() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: '/info/info_rule.asp?roulette',
            width: 850,
            height: 600
        });
    } else {
        showLoginAlert();
    }
}

function goinfoSicbo() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: '/info/info_rule.asp?sicbo',
            width: 850,
            height: 600
        });
    } else {
        showLoginAlert();
    }
}

function goinfoDragontiger() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: '/info/info_rule.asp?dragontiger',
            width: 850,
            height: 600
        });
    } else {
        showLoginAlert();
    }
}

function goinfoCaribbean() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: '/info/info_rule.asp?caribbean',
            width: 850,
            height: 600
        });
    } else {
        showLoginAlert();
    }
}

function goinfoTripleface() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: '/info/info_rule.asp?tripleface',
            width: 850,
            height: 600
        });
    } else {
        showLoginAlert();
    }
}


function goDeposit() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: '/exchange/deposit.asp',
            width: 955,
            height: 680
        });
    } else {
        showLoginAlert();
    }
}

function goWithdraw() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: '/exchange/withdraw.asp',
            width: 955,
            height: 700
        });
    } else {
        showLoginAlert();
    }
}

function goMove() {
    if (loginYN == "Y") {
        alert("현재 사용되지 않은 기능입니다.")
    } else {
        showLoginAlert();
    }
}

function goPoint() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: '/exchange/point.asp',
            width: 955,
            height: 780
        });
    } else {
        showLoginAlert();
    }
}

function goCoupon() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: '/exchange/coupon.asp',
            width: 955,
            height: 780
        });
    } else {
        showLoginAlert();
    }
}

function goHistory() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: '/exchange/history.asp',
            width: 955,
            height: 780
        });
    } else {
        showLoginAlert();
    }
}

function goMemo() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: '/memo/list.asp',
            width: 955,
            height: 780
        });
    } else {
        showLoginAlert();
    }
}



function goCustomer() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: '/etc/customer.asp',
            width: 950,
            height: 700
        });
    } else {
        showLoginAlert();
    }
}

function goMail() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: '/user/mypage_mail.asp',
            width: 850,
            height: 570
        });
    } else {
        showLoginAlert();
    }
}

function goRank() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: '/user/mypage.asp?rank',
            width: 850,
            height: 570
        });
    } else {
        showLoginAlert();
    }
}

function goIdSearch() {
    alert('아이디비번문의는 카톡ID: 추가 후 문의주세요');
}

function goMypage() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: '/user/mypage.asp',
            width: 955,
            height: 570
        });
    } else {
        showLoginAlert();
    }
}

function goJoin() {
    TINY.box.show({
        iframe: '/user/join.asp',
        width: 955,
        height: 670
    });
}


function goSign() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: '/member/sign.asp',
            width: 1060,
            height: 570
        });
    } else {
        showLoginAlert();
    }
}

function goTerms() {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: '/member/terms.asp',
            width: 1060,
            height: 570
        });
    } else {
        showLoginAlert();
    }
}

function goLogin() {
    TINY.box.show({
        iframe: '/user/login.asp',
        width: 955,
        height: 300
    });
}

function goLogout() {
    location.href = "/login/logout.asp";
}

function goBoardList(type) {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: '/board/blist/' + type + ".asp",
            width: 955,
            height: 650
        });
    } else {
        showLoginAlert();
    }
}

function goBoardView(type, num) {
    if (loginYN == "Y") {
        TINY.box.show({
            iframe: '/board/bview/' + type + '.asp?idx=' + num,
            width: 955,
            height: 650
        });
    } else {
        showLoginAlert();
    }
}


function showLoginAlert() {
    alert("로그인 후 이용가능합니다.");
    $("#userid").focus();
}


/*
로그인
마이페이지
공지사항
파트너신청
입금신청
출금신청
머니이동신청
이벤트신청
*/