<!DOCTYPE html>
<html lang="kr">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="/evol/dist/coreui/img/kplay-logo.png">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="EFbEgSmp0abFbVO2bqxpfxHXxOkodKLMe2JDtPhY">
    <input type="hidden" id="myJsonData" value="{{$data}}"
    />


    <title>Bet Detail</title>

    <!-- Scripts -->
    <script src="/evol/jquery/jquery-3.4.1.min.js"></script>
    <script src="/evol/dist/js/utils.js?v=10"></script>
    <script src="/evol/dist/js/auth.js?v=10"></script>
    <script src="/evol/dist/js/winlossdetail.js?v=24"></script>

    <!-- Custom CSS -->
    <link href="/evol/dist/css/custom.css?v=10" rel="stylesheet">

    <!-- JqueryUI -->
    <link href="/evol/dist/jqueryui/jquery-ui.min.css?v=10" rel="stylesheet">

    <!-- JqueryUI -->
    <script src="/evol/dist/jqueryui/jquery-ui.min.js?v=10"></script>

    <!-- CoreUI -->
    <link href="/evol/dist/coreui/vendors/css/flag-icon.min.css" rel="stylesheet">
    <link href="/evol/dist/coreui/vendors/css/font-awesome.min.css" rel="stylesheet">
    <link href="/evol/dist/coreui/vendors/css/simple-line-icons.min.css" rel="stylesheet">
    <link href="/evol/dist/coreui/vendors/css/spinkit.min.css" rel="stylesheet">
    <link href="/evol/dist/coreui/vendors/css/ladda-themeless.min.css" rel="stylesheet">
    <link href="/evol/dist/coreui/css/style.css?v=10" rel="stylesheet">

    <!-- CoreUI -->
    <script src="/evol/dist/coreui/vendors/js/pace.min.js"></script>
    <script src="/evol/dist/coreui/vendors/js/Chart.min.js"></script>
    <script src="/evol/dist/coreui/vendors/js/spin.min.js"></script>
    <script src="/evol/dist/coreui/vendors/js/ladda.min.js"></script>
    <script src="/evol/dist/coreui/js/app.js?v=10" defer></script>

    <script src="/evol/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
    <script src="/evol/ajax/libs/pivottable/2.23.0/pivot.min.js"></script>

    <script type="text/javascript">
        var locale = [];
        var data = {};
        var prdId = 1;
        var aasDecimalPlace = 0;

        $(document).ready(function() {
            //data = {"data":{"id":"18123c137cfc533e41dc6fc3","gameProvider":"evolution","gameSubProvider":"evolution","startedAt":"2024-12-18 18:41:00","settledAt":"2024-12-18 18:41:28","status":"Resolved","gameType":"sicbo","gameSubType":"lightning","table":{"id":"lightningsb00001","name":"Lightning Sic Bo"},"dealer":{"uid":"tts122559_______","name":"Victoria"},"currency":"KRW","participants":[{"casinoId":"gammixkplay00001","playerId":"1693570","screenName":"gggggg2123z","playerGameId":"18123c137cfc533e41dc6fc3-spmp6m73pfkqmn5d","sessionId":"spmp6m73pfkqmn5dspmrcqd2tynme37cf92ee0ad","casinoSessionId":"7b2816a79d9b3824ce9c06bcdc5acef8","currency":"KRW","bets":[{"code":"SicBo_Total10","stake":5000,"payout":0,"placedOn":"2024-12-18 18:41:19","transactionId":"7267cb56-f2ae-4306-8093-96aae5a2ddcd","description":"Total 10"},{"code":"SicBo_Total10_Fee","stake":1000,"payout":0,"placedOn":"2024-12-18 18:41:19","transactionId":"7267cb56-f2ae-4306-8093-96aae5a2ddcd","description":"Total 10"},{"code":"SicBo_Total9","stake":5000,"payout":35000,"placedOn":"2024-12-18 18:41:19","transactionId":"7267cb56-f2ae-4306-8093-96aae5a2ddcd","description":"Total 9"},{"code":"SicBo_Total9_Fee","stake":1000,"payout":0,"placedOn":"2024-12-18 18:41:19","transactionId":"7267cb56-f2ae-4306-8093-96aae5a2ddcd","description":"Total 9"}],"configOverlays":[],"subType":"lightning","playMode":"RealMoney","channel":"desktop","os":"Windows","device":"Desktop","skinId":"5","brandId":"1","currencyRateVersion":"splrcxta5rjqaaah"}],"result":{"luckyNumbers":{"SicBo_3":[5],"SicBo_Double2":[20],"SicBo_Total6":[25],"SicBo_Triple":[50]},"winningNumbers":{"name2":"다이스 2","name3":"다이스 3","name4":"다이스 1"},"second":2,"third":6,"first":1},"gameName":"식보","bet":12000,"win":35000,"username":"togg"},"prdId":1,"txnId":"7267cb56-f2ae-4306-8093-96aae5a2ddcd","username":"togg"};
            var JsonData = document.getElementById("myJsonData");

            var myJsonData = JSON.parse(JsonData.value);
            data = myJsonData;
            prepareLocale();
            getMainData();

            $("#filterForm").on('submit', (function(e) {
                e.preventDefault();
                filterMainData();
            }));
        });

        function prepareLocale() {
            locale['mainData.information'] = "정보";
            locale['mainData.selection'] = "게임종류 ";
            locale['mainData.turnover'] = "베팅 금액";
            locale['mainData.status'] = "승패";
            locale['mainData.member'] = "회원승 (KRW)";
            locale['mainData.ag'] = "에이전트 승 (KRW)";
            locale['mainData.ma'] = "마스터 승 (KRW)";
            locale['mainData.sma'] = "시니어 승 (KRW)";
            locale['mainData.company'] = "회사 (KRW)";
            locale['mainData.upper'] = "회사 (KRW)";
            locale['modal.playername'] = "플레이어 이름";
            locale['table_name'] = "테이블 이름";
            locale['txn_id'] = "거래 아이디";
            locale['player_hand'] = "플레이어 핸드";
            locale['dealer_hand'] = "딜러 핸드";
            locale['banker_hand'] = "뱅커 핸드";
            locale['dragon_hand'] = "드래이건 핸드";
            locale['tiger_hand'] = "타이거 핸드";
            locale['black_bull'] = "블랙 불";
            locale['red_bull'] = "레드 불";
            locale['phoenix_hand'] = "피닉스 핸드";
            locale['firsthandBet'] = "첫 핸드 배팅";
            locale['firsthandBonus'] = "첫 핸드 보너스";
            locale['secondhandBet'] = "두째 핸드 배팅";
            locale['secondhandBonus'] = "두번째 보너스";
            locale['dealer'] = "딜러";
            locale['player'] = "플레이어";
            locale['resolved'] = "처리됨";
            locale['on'] = "On";
            locale['card'] = "카드";
            locale['amount'] = "금액";
            locale['netcash'] = "회원승패";
            locale['norecords'] = "데이터 찾을 수 없습니다!";
            locale['score'] = "Score";
            locale['game'] = "게임";
            locale['type'] = "유형";
            locale['matchDate'] = "매치 시간";
            locale['mainData.selection'] = "게임종류 ";
            locale['mainData.turnover'] = "베팅 금액";
            locale['mainData.status'] = "승패";
            locale['mainData.member'] = "회원승 (KRW)";
            locale['mainData.ag'] = "에이전트 승 (KRW)";
            locale['mainData.ma'] = "마스터 승 (KRW)";
            locale['mainData.sma'] = "시니어 승 (KRW)";
            locale['mainData.company'] = "회사 (KRW)";
            locale['mainData.upper'] = "회사 (KRW)";
            locale['modal.playername'] = "플레이어 이름";
            locale['txn_id'] = "거래 아이디";
            locale['player_hand'] = "플레이어 핸드";
            locale['dealer_hand'] = "딜러 핸드";
            locale['banker_hand'] = "뱅커 핸드";
            locale['dragon_hand'] = "드래이건 핸드";
            locale['tiger_hand'] = "타이거 핸드";
            locale['black_bull'] = "블랙 불";
            locale['red_bull'] = "레드 불";
            locale['phoenix_hand'] = "피닉스 핸드";
            locale['firsthandBet'] = "첫 핸드 배팅";
            locale['firsthandBonus'] = "첫 핸드 보너스";
            locale['secondhandBet'] = "두째 핸드 배팅";
            locale['secondhandBonus'] = "두번째 보너스";
            locale['dealer'] = "딜러";
            locale['player'] = "플레이어";
            locale['resolved'] = "처리됨";
            locale['on'] = "On";
            locale['card'] = "카드";
            locale['amount'] = "금액";
            locale['netcash'] = "회원승패";
            locale['norecords'] = "데이터 찾을 수 없습니다!";
            locale['score'] = "Score";
            locale['game'] = "게임";
            locale['type'] = "유형";
            locale['matchDate'] = "매치 시간";
            locale['estBetSettlementTime'] = "예상 내기 정산일";
            locale['selection'] = "선택";
            locale['inplayScore'] = "라이브 스포츠 점수";
            locale['inPlay'] = "라이브 스포츠";
            locale['over'] = "오버";
            locale['under'] = "app.reports.winloss.details.under";
            locale['betType'] = "베팅 유형";
            locale['result'] = "결과";
            locale['event'] = "이벤트";
            locale['win'] = "승";
            locale['lose'] = "패";
            locale['cashout'] = "캐쉬 아웃";
            locale['tie'] = "타이";
            locale['dowjones'] = "다우 존스";
            locale['higher'] = "상승 ";
            locale['lower'] = "하락";
            locale['nonstop'] = "논스톱";
            locale['1min'] = "1분마감";
            locale['market'] = "마켓";
            locale['entryspot'] = "입장 스폿";
            locale['exitspot'] = "퇴장 스폿";
            locale['openprice'] = "오픈 프라이즈";
            locale['payout'] = "총 지불 금액";
            locale['redEnvelopePayouts'] = "빨간 봉투 지불금";
            locale['roundId'] = "라운드 ID";
            locale['banker'] = "뱅커";
            locale['player'] = "플레이어";
            locale['player_pair'] = "플레이어 페어";
            locale['banker_pair'] = "뱅커 페어";
            locale['pps_error_msg'] = "게임 정보를 찾을 수 없습니다! 5분 후에 다시 시도하십시오.";
            locale['single'] = "싱글";
            locale['parlay'] = "파레이";
            locale['teaser'] = "티저";
            locale['1X2'] = "1X2";
            locale['handicap'] = "핸디캡";
            locale['overunder'] = "오버 언더";
            locale['hometotalbet'] = "홈 토털 베팅";
            locale['awaytotalbet'] = "어웨이 토털 베팅";
            locale['mixparlay'] = "믹스 팔레이";
            locale['manualparlay'] = "매뉴얼 플레이";
            locale['oddeven'] = "오드 이븐";
            locale['special'] = "스페셜";
            locale['accepted'] = "수락";
            locale['halflose'] = "하프 패";
            locale['halfwon'] = "하프 승";
            locale['draw'] = "무승부";
            locale['open'] = "열림";
            locale['cancel'] = "취소";
            locale['score'] = "점수";
            locale['total_multiplier'] = "총 승수";
            locale['rake'] = "레이크";
            locale['held'] = "홀드";
            locale['comm'] = "커뮤니티";
            locale['tigerBonus'] = "타이거 보너스";
            locale['insurance'] = "보험 내기";
            locale['bet'] = "내기";
            locale['double'] = "이중 내기";
            locale['split'] = "나뉘다";
            locale['black'] = "검은색";
            locale['red'] = "빨간색";
            locale['straight'] = "스트레이트";
            locale['split'] = "나뉘다";
            locale['street'] = "거리";
            locale['corner'] = "코너킥";
            locale['alley'] = "골목";
            locale['column'] = "칼럼";
            locale['odd'] = "홀수";
            locale['even'] = "조차";
            locale['dozen'] = "다스";
            locale['straight'] = "스트레이트";
            locale['onepair'] = "원페어";
            locale['twopair'] = "투페어";
            locale['highcard'] = "하이카드";
            locale['threeofakind'] = "트리플";
            locale['fourofakind'] = "포카드";
            locale['flush'] = "플러쉬";
            locale['fullhouse'] = "풀하우스";
            locale['straightflush'] = "스티플";
            locale['royalflush'] = "로티플";
            locale['top'] = "탑";
            locale['none'] = "없음";
            locale['triple'] = "트리플";
            locale['eventName'] = "이벤트 이름";
            locale['game_not_found'] = "게임 정보를 찾을 수 없습니다.";
            locale['info'] = "정보";
            locale['utils.modal.ok'] = "확인";
            locale['commission'] = "커미션";
            locale['community'] = "커뮤니티";
            locale['bonus_game'] = "보너스 게임";
            locale['no_bet_placed'] = "내기 없음";
            locale['spade'] = "스페이드";
            locale['heart'] = "하트";
            locale['club'] = "클럽";
            locale['diamond'] = "다이아몬드";
            locale['start_card'] = "main.startCard";
            locale['drawn_card'] = "main.drawnCard";
            locale['top_card'] = "탑 카드";
            locale['player1'] = "플레이어 1";
            locale['player2'] = "플레이어 2";
            locale['player3'] = "플레이어 3";
            locale['first_player'] = "첫 번째 플레이어";
            locale['last_player'] = "마지막 플레이어";
            locale['house'] = "하우스";
            locale['banker_besthand'] = "뱅커 최고 핸드";
            locale['player_besthan'] = "플레이어 최고 핸드";
            locale['playerA_hand'] = "플레이어 A 핸드";
            locale['playerB_hand'] = "플레이어 B 핸드";
            locale['bonus6'] = "보너스 6";
            locale['tickets'] = "표사는 곳";
            locale['matchnumbers'] = "일치 번호";
            locale['payoutratio'] = "지급비율";
            locale['totalpayout'] = "지급금";
            locale['drawnball'] = "드로우 볼";
            locale['powerball'] = "파워볼";
            locale['multiplier'] = "승수";
            locale['mainData.number'] = "#";
            locale['info'] = "장보";
            locale['memberid'] = "회원아이디";
            locale['txn_id'] = "거래아이디";
            locale['status'] = "상태";
            locale['provider'] = "제공 서비스";
            locale['debit'] = "베팅금";
            locale['credit'] = "적중금";
            locale['username_prefix'] = "아이디";
            locale['timestamp'] = "시간";
            locale['ag_response'] = "에이전트 응답";
            locale['processed'] = "완료";
            locale['totalexecutetime'] = "충 시행 시간";
            locale['integratorexecutetime'] = "AG 실행시간";
            locale['betTime'] = "app.reports.txn_hist.betTime";
            locale['settleTime'] = "app.reports.txn_hist.settleTime";
            locale['modal.playername'] = "플레이어 이름";
            locale['txn_id'] = "거래 아이디";
            locale['table_name'] = "테이블 이름";
            locale['player_hand'] = "플레이어 핸드";
            locale['banker_hand'] = "뱅커 핸드";
            locale['dragon_hand'] = "드래이건 핸드";
            locale['tiger_hand'] = "타이거 핸드";
            locale['dealer_hand'] = "딜러 핸드";
            locale['comm_hand'] = "커뮤니티 핸드";
            locale['andar_hand'] = "안다르 핸드";
            locale['bahar_hand'] = "바하르 핸드";
            locale['joker_hand'] = "조커 핸드";
            locale['player_8'] = "플레이어8원";
            locale['player_9'] = "플레이어9원";
            locale['player_10'] = "플레이어10원";
            locale['player_11'] = "플레이어11원";
            locale['black_bull'] = "블랙 불";
            locale['red_bull'] = "레드 불";
            locale['phoenix_hand'] = "피닉스 핸드";
            locale['firsthandBet'] = "첫 핸드 배팅";
            locale['firsthandBonus'] = "첫 핸드 보너스";
            locale['secondhandBet'] = "두째 핸드 배팅";
            locale['secondhandBonus'] = "두번째 보너스";
            locale['dealer'] = "딜러";
            locale['player'] = "플레이어";
            locale['resolved'] = "처리됨";
            locale['on'] = "On";
            locale['game_not_found'] = "게임 정보를 찾을 수 없습니다.";
            locale['betresult'] = "app.reports.txn_hist.betresult.listing";
            locale['response_time'] = "시스템 응답 시간";
            locale['win'] = "승";
            locale['lose'] = "패";
            locale['tie'] = "타이";
            locale['cancel'] = "취소";
            locale['halflose'] = "하프 패";
            locale['halfwon'] = "하프 승";
            locale['draw'] = "무승부";
            locale['open'] = "app.reports.txn_hist.open";
            locale['card'] = "카드";
            locale['amount'] = "금액";
            locale['netcash'] = "회원승패";
            locale['cashout'] = "app.reports.winloss.details.cashout";
            locale['game'] = "게임";
            locale['type'] = "유형";
            locale['matchDate'] = "매치 시간";
            locale['estBetSettlementTime'] = "예상 내기 정산일";
            locale['selection'] = "선택";
            locale['inplayScore'] = "라이브 스포츠 점수";
            locale['inPlay'] = "라이브 스포츠";
            locale['over'] = "오버";
            locale['under'] = "app.reports.winloss.details.under";
            locale['yes'] = "예";
            locale['no'] = "아니오";
            locale['betType'] = "베팅 유형";
            locale['eventName'] = "이벤트 이름";
            locale['odd'] = "app.reports.txn_hist.odd";
            locale['result'] = "결과";
            locale['event'] = "이벤트";
            locale['higher'] = "상승 ";
            locale['lower'] = "하락";
            locale['nonstop'] = "논스톱";
            locale['1min'] = "1분마감";
            locale['market'] = "마켓";
            locale['entryspot'] = "입장 스폿";
            locale['exitspot'] = "퇴장 스폿";
            locale['openprice'] = "오픈 프라이즈";
            locale['single'] = "싱글";
            locale['parlay'] = "파레이";
            locale['teaser'] = "티저";
            locale['1X2'] = "1X2";
            locale['handicap'] = "핸디캡";
            locale['overunder'] = "오버 언더";
            locale['hometotalbet'] = "홈 토털 베팅";
            locale['awaytotalbet'] = "어웨이 토털 베팅";
            locale['mixparlay'] = "믹스 팔레이";
            locale['manualparlay'] = "매뉴얼 플레이";
            locale['oddeven'] = "오드 이븐";
            locale['special'] = "스페셜";
            locale['roundId'] = "라운드 ID";
            locale['jp_contribution'] = "app.depositwithdraw.maindata.contribution_amt";
            locale['ag_code'] = "app.products.extra_pt.ag_code";
            locale['payout'] = "총 지불 금액";
            locale['redEnvelopePayouts'] = "빨간 봉투 지불금";
            locale['banker'] = "뱅커";
            locale['player'] = "플레이어";
            locale['player_pair'] = "플레이어 페어";
            locale['banker_pair'] = "뱅커 페어";
            locale['buyin'] = "app.reports.txn_hist.buyin";
            locale['accepted'] = "수락";
            locale['league'] = "리그";
            locale['tvbBetdetail'] = "유저는 TV벳 로비에서 베팅내역을 확인할 수 있습니다.<br>- 유저는 TV벳 로비의 [베팅내역] 포털을 통해 자신의 베팅 기록을 확인할 수 있습니다.<br>- 유저는 TV벳 로비의 [결과] 포털을 통해 베팅 화면을 재생할 수 있습니다.<br><br>체킹이나 구체적인 내용을 확인 하시려면 KPLAY CS에 문의 하십시오.<br><br>감사합니다";
            locale['score'] = "점수";
            locale['total_multiplier'] = "총 승수";
            locale['tigerBonus'] = "타이거 보너스";
            locale['insurance'] = "보험 내기";
            locale['bet'] = "내기";
            locale['double'] = "이중 내기";
            locale['split'] = "나뉘다";
            locale['black'] = "검은색";
            locale['red'] = "빨간색";
            locale['straight'] = "스트레이트";
            locale['split'] = "나뉘다";
            locale['street'] = "거리";
            locale['corner'] = "코너킥";
            locale['alley'] = "골목";
            locale['column'] = "칼럼";
            locale['odd'] = "홀수";
            locale['even'] = "조차";
            locale['dozen'] = "다스";
            locale['commission'] = "커미션";
            locale['provider_name'] = "app.accounts.create.provider_name";
            locale['community'] = "커뮤니티";
            locale['bonus_game'] = "보너스 게임";
            locale['no_bet_placed'] = "내기 없음";
            locale['total'] = "app.home.total";
            locale['spade'] = "스페이드";
            locale['heart'] = "하트";
            locale['club'] = "클럽";
            locale['diamond'] = "다이아몬드";
            locale['held'] = "홀드";
            locale['comm'] = "커뮤니티";
            locale['straight'] = "스트레이트";
            locale['onepair'] = "원페어";
            locale['twopair'] = "투페어";
            locale['highcard'] = "하이카드";
            locale['threeofakind'] = "트리플";
            locale['fourofakind'] = "포카드";
            locale['flush'] = "플러쉬";
            locale['fullhouse'] = "풀하우스";
            locale['straightflush'] = "스티플";
            locale['royalflush'] = "로티플";
            locale['top'] = "탑";
            locale['none'] = "없음";
            locale['triple'] = "트리플";
            locale['start_card'] = "main.startCard";
            locale['drawn_card'] = "main.drawnCard";
            locale['top_card'] = "탑 카드";
            locale['player1'] = "플레이어 1";
            locale['player2'] = "플레이어 2";
            locale['player3'] = "플레이어 3";
            locale['first_player'] = "첫 번째 플레이어";
            locale['last_player'] = "마지막 플레이어";
            locale['house'] = "하우스";
            locale['banker_besthand'] = "뱅커 최고 핸드";
            locale['player_besthan'] = "플레이어 최고 핸드";
            locale['playerA_hand'] = "플레이어 A 핸드";
            locale['playerB_hand'] = "플레이어 B 핸드";
            locale['bonus6'] = "보너스 6";
            locale['tickets'] = "표사는 곳";
            locale['matchnumbers'] = "일치 번호";
            locale['payoutratio'] = "지급비율";
            locale['totalpayout'] = "지급금";
            locale['drawnball'] = "드로우 볼";
            locale['powerball'] = "파워볼";
            locale['multiplier'] = "승수";
            locale['smartsoftBetdetail'] = "사용자는 스마트 소프트 현재 게임 플레이에서 거래 내역을 확인할 수 있습니다.<br>- 사용자는 스마트 소프트 현재 게임 플레이에서 [벳 기록] 포털에 액세스하여 자신의 베팅 기록을 확인할 수 있습니다.<br><br>자세한 정보를 확인하기 위하여 KPLAY CS에 문의하세요.<br><br>감사합니다";

        }

        var mainData;

        function getMainData() {
            $("#modalDetail").show();

            if (typeof data != "string") {
                if (prdId == '1' || prdId == '5001' || (prdId == '213' && gameId == '399') || (prdId == '5213' && gameId == '500399'))
                    getDataTableDetailsEVO(data, data['username'], data['txnId'], prdId);
                else if (prdId == '2' || prdId == '5002')
                    getDataTableDetailsBG(data, data['username']);
                else if (prdId == '5' || prdId == '5005') {
                    getDataTableDetailsASG(data, data['username'], data['txnId']);
                } else if (prdId == '6' || prdId == '5006') {
                    getDataTableDetailsDG(data, data['username'], data['txnId']);
                } else if (prdId == '8') {
                    getDataTableDetailsAV(data, data['username']);
                } else if (prdId == '301' || prdId == '5301') {
                    getDataTableDetailSCEPB(data, data['username'], data['txnId']);
                } else if (['21', '5021', '201', '202', '204', '205', '206', '207', '208', '209', '212', '215', '216', '217', '218', '219', '102', '12', '220', '221', '222', '223', '224', '19', '300', '5201', '5202', '5204', '5205', '5206', '5207', '5208', '5209', '5212', '5215', '5216', '5217', '5218', '5219', '5102', '5012', '5020', '5220', '5221', '5222', '5223', '5224', '5019', '3', '30', '5030', '268', '5268', '5278', '5263'].includes(prdId)) {
                    window.open(data);
                } else if (prdId == '18' || prdId == '5018') {
                    $('#modal-rcg').hide();
                    $('#modal-vegas').hide();
                    $('#modal-im').hide();
                    getDataTableDetailsBota(data, data['username'], data['roundId'], data['txnId'], prdId);
                } else if (prdId == '101' || prdId == '5101') {
                    $('#modal-table').hide();
                    $('#modal-mg').hide();
                    $('#modal-ukt').hide();
                    $('#modal-im').show();
                    getDataTableDetailIM(data, data['username'], data['txnId']);
                }
                /*else if(prdId == '300')
                {
                    getDataTableDetailUKT(data,txnId);
                    $('#modal-table').hide();
                    $('#modal-mg').hide();
                    $('#modal-im').hide();
                    $('#modal-ukt').show();
                    getDataTableDetailUKT(data,data['txnId']);
                }*/
                else if (prdId == '13') {
                    $('#modal-table').hide();
                    $('#modal-mg').hide();
                    $('#modal-im').hide();
                    $('#modal-ukt').hide();
                    getDataTableDetailVegas(data, data['txnId']);
                } else if (prdId == '16' || prdId == '5016') {
                    $('#modal-table').hide();
                    $('#modal-rcg').show();
                    $('#modal-vegas').hide();
                    $('#modal-im').hide();
                    $('#modal-mg').html("");
                    $('#modal-mg').show();
                    $("#tableNameRowRCG").show();

                    var iframe = document.createElement('iframe');
                    iframe.setAttribute('src', data[0]['url']);
                    iframe.style.height = "300px";
                    $('#modal-mg').append(iframe);
                    $('.modal-content').css('width', '635px');

                    var game = data[0]['desk'] + "-" + data[0]['activeNo'] + "-" + data[0]['runNo'] + "-" + data[0]['memberAccount'];
                    getDataTableDetailsRCG(data, data[0]['username'], game);
                } else if (prdId == '20' || prdId == '5020') {
                    if (data == null || data.length == 0) {
                        utils.showModal(locale['info'], locale['game_not_found']);
                    } else {
                        $('#modal-table').hide();
                        $('#modal-mg').hide();
                        $('#modal-vegas').hide();
                        $('#modal-im').hide();
                        $('#modal-pinnacle').hide();
                        $('#modal-rizal').hide();
                        getDataTableDetailRizal(data, data['txnId']);
                    }
                } else if (prdId == '103' || prdId == '5103') {
                    $('#modal-table').hide();
                    $('#modal-mg').hide();
                    $('#modal-vegas').hide();
                    $('#modal-im').hide();
                    $('#modal-pinnacle').show();
                    getDataTableDetailPinnacle(data, data['username'], txnId);
                } else if (prdId == '200' || prdId == '5200') {
                    if (data.startsWith('https')) {
                        window.open(data);
                    } else {
                        utils.showModal(locale['info'], locale['pps_error_msg'], 0, '', true, true);
                    }
                } else if (prdId == '105') {
                    $('#modal-table').hide();
                    $('#modal-mg').hide();
                    $('#modal-vegas').hide();
                    $('#modal-im').hide();
                    $('#modal-pinnacle').show();
                    getDataTableDetailBti(data, data['username'], data['txnId']);
                } else if (prdId == '109') {
                    $('#modal-table').hide();
                    $('#modal-mg').hide();
                    $('#modal-vegas').hide();
                    $('#modal-im').hide();
                    $('#modal-pinnacle').show();
                    getDataTableDetailBtrEsport(data, data['username'], data['txnId']);
                } else if (prdId == '5022' || prdId == '22') {
                    getDataTableDetailDoWin(data, data['username'], txnId);
                } else if (prdId == '5023') {
                    getDataTableDetailDD(data, data['username'], txnId);
                } else if (prdId == '5024') //for TW OG
                {
                    getDataTableDetailOG(data, data['username'], txnId);
                } else if (prdId == '24') //for SW OG
                {
                    getDataTableDetailSwOG(data, data['username'], txnId);
                } else if (prdId == '5025') {
                    getDataTableDetailARN(data, data['username'], txnId);
                } else if (prdId == '27' || prdId == '5027') {
                    $('#modal-table').hide();
                    $('#modal-mg').hide();
                    $('#modal-vegas').hide();
                    $('#modal-im').hide();
                    $('#modal-pinnacle').hide();
                    $('#modal-rizal').hide();
                    $('#modal-og').hide();
                    $('#modal-arn').hide();
                    $('#modal-yes8').show();

                    getDataTableDetailYes8(data, txnId);
                } else if (prdId == '10001' || prdId == '10002' || prdId == '37') {
                    dataSize = 0;

                    dataSize = data[0].length;
                    fullData = (prdId == '37') ? data : data[0];

                    if (prdId == '37') {
                        $.each(data, function(index, val) {
                            var indexStr = index.toString();

                            if (!isNaN(index)) {
                                index = Number(index);
                                dataSize = index + 1;
                            }
                        });
                    }

                    $('.arrow').hide();
                    $("#modal-table").hide();

                    if (dataSize > 1) {
                        $("#right_arrow").show();
                    }
                    /*if(gameDetails)
                    {*/
                    currentView = 0;
                    getDataTableDetailHoldem(fullData[0], data['username'], prdId);
                    /*}
                    else
                    {
                        loadDetails(rowSelected,fullData,rowId);
                    }*/
                    // $("#modal-json").html(JSON.stringify(data));
                } else if (prdId == '10003') {
                    // $("#modalDetail #secondCard").empty();
                    $('#modal-holdem').remove();
                    $('#modal-json').remove();
                    $('#modal-mg').remove();
                    $('#modal-im').remove();
                    $('#modal-pinnacle').remove();
                    $('#modal-dowin').remove();
                    $('#modal-dd').remove();
                    $('#modal-og').remove();
                    $('#modal-rizal').remove();
                    $('#modal-arn').remove();
                    $('#modal-yes8').remove();
                    $('#modal-scepb').remove();
                    $('#modal-table').remove();
                    $('#modal-ukt').remove();
                    $('#modal-vegas').remove();
                    $('#modal-badugi').show();

                    dataSize = data[0].length;
                    fullData = data[0];

                    if (dataSize > 1) {
                        $(".arrow").show();
                        $(".arrow").css('top', '50%');
                        $("#left_arrow").hide();
                        $("#page_no").html('1/' + dataSize);

                        var iframe = document.createElement('iframe');
                        iframe.setAttribute('srcdoc', data[0][0]);
                        iframe.style.height = "90vh";
                        iframe.style.width = "100%";

                        $("#modalDetail #secondCard #iframe-badugi").html(iframe);
                    }

                    currentView = 0;
                } else if (prdId == '35' || prdId == '5035') // XPG
                {
                    getDataTableDetailXPG(data, data['username'], data['txnId']);
                } else if (prdId == '34' || prdId == '5034') // GPI Live Casino.
                {
                    if (data == null || data.length == 0) {
                        utils.showModal(locale['info'], locale['game_not_found']);
                    } else {
                        getDataTableDetailsGPI(data, data['username'], data['txnId']);
                    }
                } else if (prdId == '38' || prdId == '5038') //DB
                {
                    if (data == null || data.length == 0) {
                        utils.showModal(locale['info'], locale['game_not_found']);
                    } else {
                        getDataTableDetailsDB(data, data['username'], data['txnId']);
                    }
                } else if (prdId == '39' || prdId == '5039') {
                    if (data == null || data.length == 0) {
                        utils.showModal(locale['info'], locale['game_not_found']);
                    } else {
                        getDataTableDetailsHilton(data, data['username'], data['roundId'], data['txnId'], prdId);
                    }
                }
            } else if (isURL(data) || ['217', '218', '222', '19', '221', '224', '5217', '5218', '5222', '5019', '5221', '5224'].indexOf(prdId) != -1) {
                $('#modal-mg').html("");
                $('#modal-table').hide();
                $('#modal-mg').show();
                var iframe = document.createElement('iframe');

                if (prdId == '267' || prdId == '5267') {
                    iframe.setAttribute('src', data);

                    // Temporary Solution for GPI Slot Bet Detail. Iframe showing blanks after few second the bet details loaded
                    // Set a timeout to check if the content is still empty after a few seconds (e.g., 3.5 seconds)
                    setTimeout(function() {
                        // Check if the iframe loaded content successfully or failed
                        if (!iframe.contentDocument || iframe.contentDocument.body.innerHTML.trim() === "") {
                            // If still blank, reload the iframe
                            iframe.src = iframe.src;
                        }
                    }, 3500); // Delay the check by 3500 milliseconds (3.5 seconds)
                } else {
                    iframe.setAttribute('src', data);
                }

                iframe.style.height = "880px";

                if (prdId == '205') {
                    iframe.style.height = "700px";
                }

                $('#modal-mg').append(iframe);
                $('.modal-content').css('width', '635px');
            } else if (!isURL(data)) {
                if (prdId == '213' || prdId == '214' || prdId == '225' || prdId == '5213' || prdId == '5214' || prdId == '5225') {
                    document.write(data);
                    document.close();
                } else if (prdId == '15' || prdId == '5015') {
                    $("#modalDetail").remove();
                    utils.showModal(locale['info'], locale['tvbBetdetail']);
                } else if (prdId == '278' || prdId == '5278') {
                    utils.showModal(locale['info'], locale['smartsoftBetdetail']);
                } else {
                    $('#modalDetail').html(data);
                }
            }
        }

        function isURL(str) {
            var pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
                '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|' + // domain name
                '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
                '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
                '(\\?[;&a-z\\d%_.~+=-]*)?'); // query string
            return pattern.test(str);
        }

        //for p2p
        function switchView(dir) {
            currentView = currentView + parseInt(dir);

            $('modal').trigger('reset');

            if (currentView <= 0) {
                currentView = 0;
                $("#left_arrow").hide();
                $("#right_arrow").show();
            } else if (currentView >= dataSize - 1) {
                currentView = dataSize - 1;
                $("#right_arrow").hide();
                $("#left_arrow").show();
            } else {
                $(".arrow").show();
            }

            dataToShow = fullData[currentView];
            getDataTableDetailHoldem(dataToShow, data['username'], prdId);
        }

        function switchViewBadugi(dir) {
            currentView = currentView + parseInt(dir);

            // $('#modalBadugi .modal-body').html('');

            if (currentView <= 0) {
                currentView = 0;
                $("#left_arrow").hide();
                $("#right_arrow").show();
                $("#page_no").html('1/' + dataSize);
            } else if (currentView >= dataSize - 1) {
                currentView = dataSize - 1;
                $("#right_arrow").hide();
                $("#left_arrow").show();
                $("#page_no").html((currentView + 1) + '/' + dataSize);
            } else {
                $(".arrow").show();
            }

            dataToShow = fullData[currentView];

            var iframe = document.createElement('iframe');
            iframe.setAttribute('srcdoc', dataToShow);
            iframe.style.height = "90vh";
            iframe.style.width = "100%";

            $("#modalDetail #secondCard #iframe-badugi").html(iframe);
        }
        //end for p2p
    </script>
    <style type="text/css">
        #main-table tr:nth-child(odd) {
            background-color: #e0ebff;
        }

        #main-table tr:nth-child(even) {
            background-color: #fff;
        }

        table td,
        table th {
            border: 1px solid #dadada;
            padding: 4px;
            text-align: center;
        }

        .imgStyle {
            width: 60px;
            margin-left: 5px;
            border: 1px solid black;
            margin-bottom: 3px;
            margin-top: 3px;
        }

        .imgStyleSbc {
            width: 50px;
            margin-left: 3px;
            border: 1px solid black;
            margin-bottom: 5px;
            margin-top: 5px;
        }

        #text {
            text-align: center;
        }

        /* #playerName
    {
        background-color: #e0ebff;
    } */

        body {
            font-size: 12px;
        }

        .header-fixed .app-body {
            margin-top: 40px;
        }

        .main .container-fluid {
            padding: 18px;
            padding-top: 0px;
        }

        .card-body {
            padding: 0.5rem;
        }

        .sidebar .nav {
            counter-reset: item;
        }

        .sidebar li a:before {
            content: counters(item, ".") "";
            counter-increment: item;
        }

        .sidebar li {
            counter-reset: div;
        }

        .sidebar a div:before {
            content: counters(item, ".") ".";
        }

        #modal-im #gameInfo table td {
            border: 0.5px solid #a4b7c1;
            font: normal 12px/14px Tahoma;
        }

        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            background-color: #5A5B5C;
            opacity: 0.5;
        }

        .arrow {
            display: none;
            position: fixed;
            top: 40%;
            font-size: 50px;
            cursor: pointer;
            user-select: none;
        }

        .ball {
            display: inline-block;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            color: white;
            text-align: center;
            line-height: 40px;
            font-size: 16px;
            margin: 5px;
            font-weight: bold;
        }

        .draw-title {
            text-align: center;
            font-size: 15px;
            color: white;
            background: linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%);
        }

        .modal-header {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            background: linear-gradient(to bottom, #e7edfa 0%, #32457a 66%, #32457a 100%);
        }

        .modal-primary,
        .modal-content {
            border-radius: 10px;
        }

        #multipleBet td,
        #bjTitle td {
            font-size: 14px;
        }

        #modal-table-newlayout {
            width: 100%;
            table-layout: fixed;
        }
    </style>
</head>

<div id="modalDetail" class="container-fluid">
    <div class="modal-body">
        <div class="card" style="border:none;background:transparent;">
            <div class="modal-header">
                <h4 style="color: white;" class="modal-title">거래 상세 내역</h4>
            </div>
        </div>

        <div class="card" id="secondCard">
            <div class="card" id="modal-table" style="border:none;background:transparent;">
                <table id="modal-table-newlayout">
                    <tbody class="table-md mb-4;">
                        <tr>
                            <td id="playerName" colspan="12" style="/*background-color:#e0ebff*/;color:#838b96;text-align:center;font-size:18px;border:none"></td>
                        </tr>
                        <tr>
                            <th id="time" colspan="12" style="height:33px;text-align:center;font-size:15px;color:white;background: linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%);border:none;border-top-left-radius: 10px;border-top-right-radius: 10px;"></th>
                        </tr>
                        <tr>
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">게임</td>
                            <td id='serialNo' style="font-size:14px" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">테이블 아이디</td>
                            <td id="tableId" style="font-size:14px" colspan="6"></td>
                        </tr>
                        <tr id="tableNameRowRCG" style="display: none;font-size:14px;">
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">테이블 이름</td>
                            <td id="tableNameRCG" style="font-size:14px" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">게임 상태</td>
                            <td id="status" style="font-size:14px" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">종료 시간</td>
                            <td id="endTime" style="font-size:14px" colspan="6"></td>
                        </tr>
                        <tr id="hand" style="display: none;font-size:14px;">
                            <th id="dealerhand" class="emptyContent" colspan="6" style="text-align:center;font-size:15px;color:white;background: linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%);"></th>
                            <th id="playerhand" class="emptyContent" colspan="6" style="text-align:center;font-size:15px;color:white;background: linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%);"></th>
                        </tr>
                        <tr id="images">
                        </tr>
                        <tr id="cardTitle">
                        </tr>
                        <tr id="cards">
                        </tr>
                        <tr id="score" style="text-align: center;display: none;">
                            <th id="scoredealer" class="emptyContent" colspan="6" style="background-color:#f0f0f0;"></th>
                            <th id="scoreplayer" class="emptyContent" colspan="6" style="background-color:#f0f0f0;"></th>
                        </tr>
                        <tr id="multipleBet">
                            <th colspan="2" id="bet" style="height:33px;text-align:center;font-size:14px;color:white;background:linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%)">내기</th>
                            <th colspan="2" id="amount" style="height:33px;text-align:center;font-size:14px;color:white;background:linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%)">금액</th>
                            <th colspan="2" id="netcash" style="height:33px;text-align:center;font-size:14px;color:white;background:linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%)">회원승패</th>
                            <th colspan="3" id="txnid" style="height:33px;text-align:center;font-size:14px;color:white;background:linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%)">거래 아이디</th>
                            <th colspan="3" id="placetime" style="height:33px;text-align:center;font-size:14px;color:white;background:linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%)">베팅 시간</th>
                        </tr>
                        <tr id="bjTitle" style="display: none;">
                            <th colspan="2" id="betbj" style="height:33px;text-align:center;font-size:14px;color:white;background:linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%)">내기</th>
                            <th colspan="1" id="amountbj" style="height:33px;text-align:center;font-size:14px;color:white;background:linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%)">금액</th>
                            <th colspan="1" id="netcash" style="height:33px;text-align:center;font-size:14px;color:white;background:linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%)">회원승패</th>
                            <th colspan="1" style="height:33px;text-align:center;font-size:14px;color:white;background:linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%)">Score</th>
                            <th colspan="1" style="height:33px;text-align:center;font-size:14px;color:white;background:linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%)">결과</th>
                            <th colspan="3" id="cardbj" style="height:33px;text-align:center;font-size:14px;color:white;background:linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%)">카드</th>
                            <th colspan="2" id="txnidbj" style="height:33px;text-align:center;font-size:14px;color:white;background:linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%)">거래 아이디</th>
                            <th colspan="1" id="placetimebj" style="height:33px;text-align:center;font-size:14px;color:white;background:linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%)">베팅 시간</th>
                        </tr>
                        <tr id="yourBet" class="emptyContent"></tr>
                        <tr id="wheelResult" style="display:none">
                            <th colspan="12" style="height:33px;font-size:15px;text-align:left;color:white;background:linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%)">휠 결과</th>
                        </tr>
                        <tr id="trWheelPosition" style="display:none">
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">휠 위치</td>
                            <td id="wheelPosition" style="font-size:14px" colspan="6"></td>
                        </tr>
                        <tr id="trWheelType" style="display:none">
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">휠 유형</td>
                            <td id="wheelType" style="font-size:14px" colspan="6"></td>
                        </tr>
                        <tr>
                            <th colspan="12" style="height:33px;font-size:15px;text-align:left;color:white;background:linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%)">게임 요약</th>
                        </tr>
                        <tr id="betResult">
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">결과</td>
                            <td id="results" style="font-size:14px" colspan="6"></td>
                        </tr>
                        <tr id="generatedEquity" style="display: none;">
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">골드 바 결과</td>
                            <td id="generatedEquityColumn" colspan="6" style="font-size:14px"></td>
                        </tr>
                        <tr>
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">총 베팅 금액</td>
                            <td id="totalBet" style="font-size:14px" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">총 지불 금액</td>
                            <td id="payment" style="font-size:14px" colspan="6"></td>
                        </tr>
                        <tr id="totalNet">
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">총 회원승패</td>
                            <td id="net" style="font-size:14px" colspan="6"></td>
                        </tr>
                        <tr id="trFinalMultiplier" style="display:none">
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">최종 승수</td>
                            <td id="finalMultiplier" style="font-size:14px" colspan="6"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card" id="modal-mg" style="display: none;"></div>

            <div class="card" id="modal-im" style="display: none;">
                <table>
                    <tbody class="table-md mb-4;">
                        <tr>
                            <td id="playerName" colspan="12" style="background-color:#e0ebff;color:black;text-align:center;font-size:18px;"></td>
                        </tr>
                        <tr>
                            <td colspan="6" style="width:50%">거래 아이디</td>
                            <td id="txnId" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">게임 상태</td>
                            <td id="status" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">베팅 시간</td>
                            <td id="placetime" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">종료 시간</td>
                            <td id="endTime" colspan="6"></td>
                        </tr>
                        <tr id="betResult">
                            <td colspan="6">결과</td>
                            <td id="results" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="3">배당률</td>
                            <td id="odds" colspan="3"></td>
                            <td colspan="3">유형</td>
                            <td id="type" colspan="3"></td>
                        </tr>
                        <tr>
                            <td colspan="6">총 베팅 금액</td>
                            <td id="totalBet" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">총 지불 금액</td>
                            <td id="payment" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">총 회원승패</td>
                            <td id="net" colspan="6"></td>
                        </tr>
                        <tr>
                            <th colspan="12" style="font-size:15px;text-align:center;">게임 요약</th>
                        </tr>
                        <tr>
                            <th colspan="12">
                                <div id="gameInfo" class="carousel slide" data-ride="carousel"></div>
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card" id="modal-ukt" style="display: none;">
                <table>
                    <tbody class="table-md mb-4;">
                        <tr>
                            <td id="txnId" colspan="12" style="background-color:#e0ebff;color:black;text-align:center;font-size:18px;"></td>
                        </tr>
                        <tr>
                            <td colspan="6">게임 상태</td>
                            <td id="status" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">마켓</td>
                            <td id="symbol" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">베팅 유형</td>
                            <td id="binaryType" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">내기</td>
                            <td id="contractType" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">총 베팅 금액</td>
                            <td id="totalBet" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">총 지불 금액</td>
                            <td id="payout" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">총 회원승패</td>
                            <td id="net" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">입장 스폿</td>
                            <td id="entrySpot" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">오픈 프라이즈</td>
                            <td id="openPrice" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">퇴장 스폿</td>
                            <td id="exitSpot" colspan="6"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card" id="modal-vegas" style="display: none;">
                <table>
                    <tbody class="table-md mb-4;" id="table-vegas">
                        <tr>
                            <td id="roundId" colspan="12" style="background-color:#e0ebff;color:black;text-align:center;font-size:18px;"></td>
                        </tr>
                        <tr>
                            <td colspan="6">결과</td>
                            <td id="results" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">게임 상태</td>
                            <td id="status" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="4">베팅금</td>
                            <td id="totalDebit" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="4">적중금</td>
                            <td id="totalCredit" colspan="6"></td>
                        </tr>
                        <tr style="background-color:#e0ebff;">
                            <td colspan="3">베팅 유형</td>
                            <td colspan="3">베팅금</td>
                            <td colspan="3">적중금</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card" id="modal-pinnacle" style="display: none;">
                <table>
                    <tbody class="table-md mb-4;">
                        <tr>
                            <td id="playerName" colspan="12" style="background-color:#e0ebff;color:black;text-align:center;font-size:18px;"></td>
                        </tr>
                        <tr>
                            <td colspan="6" style="width:50%">거래 아이디</td>
                            <td id="txnId" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">게임 상태</td>
                            <td id="status" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">베팅 시간</td>
                            <td id="placetime" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">종료 시간</td>
                            <td id="endTime" colspan="6"></td>
                        </tr>
                        <tr id="hand-pinnacle" style="display: none;">
                            <th id="dealerhand-pinnacle" class="emptyContent" colspan="6" style="text-align:center;font-size:15px;"></th>
                            <th id="playerhand-pinnacle" class="emptyContent" colspan="6" style="text-align:center;font-size:15px;"></th>
                        </tr>
                        <tr id="images-pinnacle">
                        </tr>
                        <tr id="betResult">
                            <td colspan="6">결과</td>
                            <td id="results" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="3" id="oddsName">배당률</td>
                            <td id="odds" colspan="3"></td>
                            <td colspan="3">유형</td>
                            <td id="type" colspan="3"></td>
                        </tr>
                        <tr>
                            <td colspan="6">총 베팅 금액</td>
                            <td id="totalBet" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">배당률 포맷</td>
                            <td id="oddsFormat" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">총 지불 금액</td>
                            <td id="payment" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">총 회원승패</td>
                            <td id="net" colspan="6"></td>
                        </tr>
                        <tr>
                            <th colspan="12" style="font-size:15px;text-align:center;">게임 요약</th>
                        </tr>
                        <tr>
                            <th colspan="12">
                                <div id="pinGameInfo" class="carousel slide" data-ride="carousel"></div>
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card" id="modal-rizal" style="display: none;">
                <table>
                    <tbody class="table-md mb-4;" id="table-rizal">
                        <tr>
                            <td id="roundId" colspan="12" style="background-color:#e0ebff;color:black;text-align:center;font-size:18px;"></td>
                        </tr>
                        <tr>
                            <td colspan="6">거래아이디</td>
                            <td id="txnId" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">결과</td>
                            <td id="results" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">베팅금</td>
                            <td id="totalDebit" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">적중금</td>
                            <td id="totalCredit" colspan="6"></td>
                        </tr>
                        <tr style="background-color:#e0ebff;">
                            <td colspan="6">베팅 유형</td>
                            <td colspan="6">베팅금</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card" id="modal-dowin" style="display: none;border:none;">
                <table id="modal-table-newlayout">
                    <tbody class="table-md mb-4;">
                        <tr>
                            <td id="playerName" colspan="12" style="color:#838b96;text-align:center;font-size:18px;border:none"></td>
                        </tr>
                        <tr>
                            <td colspan="6" style="width:50%;font-size:14px;font-weight: bold;background-color:#f0f0f0">거래 아이디</td>
                            <td id="txnId" style="font-size:14px" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">라운드 ID</td>
                            <td colspan="6" style="font-size:14px" id="roundId"></td>
                        </tr>
                        <tr>
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">베팅 시간</td>
                            <td id="placetime" style="font-size:14px" colspan="6"></td>
                        </tr>
                        <tr id="bet">
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">내기</td>
                            <td id="bet-placed" style="font-size:14px" colspan="6"></td>
                        </tr>
                        <tr id="betResult">
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">결과</td>
                            <td id="results" style="font-size:14px" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-center font-weight-bold" style="text-align:center;font-size:15px;color:white;background: linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%);">뱅커 핸드</td>
                            <td colspan="6" class="text-center font-weight-bold" style="text-align:center;font-size:15px;color:white;background: linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%);">플레이어 핸드</td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-center" id="banker-hand"></td>
                            <td colspan="6" class="text-center" id="player-hand"></td>
                        </tr>
                        <tr>
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">총 베팅 금액</td>
                            <td id="totalBet" colspan="6" style="font-size:14px"></td>
                        </tr>
                        <tr>
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">총 지불 금액</td>
                            <td id="payment" colspan="6" style="font-size:14px"></td>
                        </tr>
                        <tr>
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">총 회원승패</td>
                            <td id="net" colspan="6" style="font-size:14px"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card" id="modal-dd" style="display: none;">
                <table>
                    <tbody class="table-md mb-4;">
                        <tr>
                            <td id="playerName" colspan="12" style="background-color:#e0ebff;color:black;text-align:center;font-size:18px;"></td>
                        </tr>
                        <tr>
                            <td colspan="6" style="width:50%">거래 아이디</td>
                            <td id="txnId" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">베팅 시간</td>
                            <td id="placetime" colspan="6"></td>
                        </tr>
                        <tr id="bet">
                            <td colspan="6">내기</td>
                            <td id="bet-placed" colspan="6"></td>
                        </tr>
                        <tr id="betResult">
                            <td colspan="6">결과</td>
                            <td id="results" colspan="6"></td>
                        </tr>

                        <tr>
                            <td colspan="6" style="color:black;text-align:center;font-size:15px;">플레이어 핸드</td>
                            <td colspan="6" style="color:black;text-align:center;font-size:15px;">뱅커 핸드</td>
                        </tr>
                        <tr id="images">
                        </tr>
                        <tr id="cardTitle">
                        </tr>
                        <tr id="cards">
                        </tr>

                        <tr>
                            <td colspan="6">총 베팅 금액</td>
                            <td id="totalBet" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">총 지불 금액</td>
                            <td id="payment" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">총 회원승패</td>
                            <td id="net" colspan="6"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card" id="modal-og" style="display: none;border:none;">
                <table id="modal-table-newlayout">
                    <tbody class="table-md mb-4;">
                        <tr>
                            <td id="playerName" colspan="12" style="color:#838b96;text-align:center;font-size:18px;border:none"></td>
                        </tr>
                        <tr>
                            <th id="time" colspan="12" style="height:33px;text-align:center;font-size:15px;color:white;background: linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%);border:none;border-top-left-radius: 10px;border-top-right-radius: 10px;"></th>
                        </tr>
                        <tr>
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">거래 아이디</td>
                            <td id="txnId" style="font-size:14px" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">베팅 시간</td>
                            <td id="placetime" style="font-size:14px" colspan="6"></td>
                        </tr>
                        <tr id="bet">
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">내기</td>
                            <td id="bet-placed" style="font-size:14px" colspan="6"></td>
                        </tr>
                        <tr id="betResult">
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">결과</td>
                            <td id="results" style="font-size:14px" colspan="6"></td>
                        </tr>

                        <tr id="gameInfo">
                        </tr>

                        <tr id="images">
                        </tr>

                        <tr>
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">총 베팅 금액</td>
                            <td id="totalBet" style="font-size:14px" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">총 지불 금액</td>
                            <td id="payment" style="font-size:14px" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">총 회원승패</td>
                            <td id="net" style="font-size:14px" colspan="6"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card" id="modal-arn" style="display: none;border:none">
                <table id="modal-table-newlayout">
                    <tbody class="table-md mb-4;">
                        <tr>
                            <td id="playerName" colspan="12" style="color:#838b96;text-align:center;font-size:18px;border:none"></td>
                        </tr>
                        <tr>
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">라운드 ID</td>
                            <td id="roundId" style="font-size:14px" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">베팅 시간</td>
                            <td id="placetime" style="font-size:14px" colspan="6"></td>
                        </tr>
                        <!-- <tr id="bet">
                                <td colspan="6">내기</td>
                                <td id="bet-placed" colspan="6"></td>
                            </tr> -->
                        <tr id="betResult">
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">결과</td>
                            <td id="results" style="font-size:14px" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-center font-weight-bold" style="text-align:center;font-size:15px;color:white;background: linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%);">뱅커 핸드</td>
                            <td colspan="6" class="text-center font-weight-bold" style="text-align:center;font-size:15px;color:white;background: linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%);">플레이어 핸드</td>
                        </tr>

                        <tr id="hand" style="display: none;">
                            <th id="dealerhand" class="emptyContent" colspan="6" style="text-align:center;font-size:15px;color:white;background: linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%);"></th>
                            <th id="playerhand" class="emptyContent" colspan="6" style="text-align:center;font-size:15px;color:white;background: linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%);"></th>
                        </tr>
                        <tr id="images">
                        </tr>
                        <tr id="score" style="text-align: center;display: none;">
                            <th id="scoredealer" class="emptyContent" colspan="6"></th>
                            <th id="scoreplayer" class="emptyContent" colspan="6"></th>
                        </tr>
                        <tr id="multipleBet">
                            <th colspan="3" id="txnId" style="height:33px;text-align:center;font-size:14px;color:white;background:linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%)">거래 아이디</th>
                            <th colspan="3" id="bet" style="height:33px;text-align:center;font-size:14px;color:white;background:linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%)">내기</th>
                            <th colspan="3" id="amount" style="height:33px;text-align:center;font-size:14px;color:white;background:linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%)">금액</th>
                            <th colspan="3" id="netcash" style="height:33px;text-align:center;font-size:14px;color:white;background:linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%)">회원승패</th>
                        </tr>

                        <tr>
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">총 베팅 금액</td>
                            <td id="totalBet" style="font-size:14px" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">총 지불 금액</td>
                            <td id="payment" style="font-size:14px" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">총 회원승패</td>
                            <td id="net" style="font-size:14px" colspan="6"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card" id="modal-holdem" style="display: none;">
                <table>
                    <tbody class="table-md mb-4;">
                        <tr>
                            <td id="playerName" colspan="12" style="background-color:#e0ebff;color:black;text-align:center;font-size:18px;" class="clear_data"></td>
                        </tr>
                        <tr>
                            <th id="time" colspan="12" style="text-align:center;font-size:15px;" class="clear_data"></th>
                        </tr>
                        <tr id="game-row">
                            <td colspan="6">게임</td>
                            <td id='serialNo' colspan="6" class="clear_data"></td>
                        </tr>
                        <tr>
                            <td colspan="6">게임 No</td>
                            <td id='game_no' colspan="6" class="clear_data"></td>
                        </tr>
                        <tr>
                            <td colspan="6">라운드 ID</td>
                            <td id='round_id_val' colspan="6" class="clear_data"></td>
                        </tr>
                        <tr>
                            <td colspan="6">두 번 실행</td>
                            <td id="run_it_twice" colspan="6" class="clear_data"></td>
                        </tr>
                        <tr id="fold-row">
                            <td colspan="6">폴드</td>
                            <td id="fold" colspan="6" class="clear_data"></td>
                        </tr>
                        <tr>
                            <td colspan="6">종료 시간</td>
                            <td id="endTime" colspan="6" class="clear_data"></td>
                        </tr>
                        <tr id="hand">
                            <th id="held" class="emptyContent clear_data" colspan="6" style="text-align:center;font-size:15px;"></th>
                            <th id="comm" class="emptyContent clear_data" colspan="6" style="text-align:center;font-size:15px;"></th>
                        </tr>
                        <tr id="game1" style="text-align: center;">
                            <th id="held1" class="emptyContent clear_data" colspan="6"></th>
                            <th id="comm1" class="emptyContent clear_data" colspan="6"></th>
                        </tr>
                        <tr id="game2" class="rit_class" style="text-align: center;">
                            <th id="comm2" class="emptyContent clear_data" colspan="6"></th>
                        </tr>
                        <tr id="multipleBet">
                            <th colspan="2" id="bet" style="text-align:center;">내기</th>
                            <th colspan="2" id="amount" style="text-align:center;">승</th>
                            <th colspan="2" id="netcash" style="text-align:center;">회원승패</th>
                            <th colspan="3" id="txnid" style="text-align:center;">거래 아이디</th>
                            <th colspan="3" id="placetime" style="text-align:center;">베팅 시간</th>
                        </tr>
                        <tr id="bet">
                            <td id='bet_amt' colspan="2" rowspan="2" class="clear_data"></td>
                            <td id='bet_won' colspan="2" class="clear_data"></td>
                            <td id='bet_net' colspan="2" class="clear_data"></td>
                            <td id='bet_txn_id' colspan="3" class="clear_data"></td>
                            <td id='settle_time' colspan="3" rowspan="2" class="clear_data"></td>
                        </tr>
                        <tr id="rit" class="rit_class">
                            <td id='rit_won' colspan="2" class="clear_data"></td>
                            <td id='rit_net' colspan="2" class="clear_data"></td>
                            <td colspan="3">두 번 실행</td>
                        </tr>
                        <tr id="yourBet" class="emptyContent clear_data"></tr>
                        <tr>
                            <th colspan="12" style="font-size:15px;text-align:center;">게임 요약</th>
                        </tr>
                        <tr id="betResult">
                            <td colspan="6">결과</td>
                            <td id="results" colspan="6" class="clear_data"></td>
                        </tr>

                        <tr class="all_in_class">
                            <td colspan="6">올인</td>
                            <td id="all_in" colspan="6" class="clear_data"></td>
                        </tr>
                        <tr class="all_in_class">
                            <td colspan="6">올인 프리미엄</td>
                            <td id="all_in_premium" colspan="6" class="clear_data"></td>
                        </tr>
                        <tr class="all_in_class">
                            <td colspan="6">올인 보상</td>
                            <td id="all_in_reward" colspan="6" class="clear_data"></td>
                        </tr>

                        <tr id="rit_betResult" class="rit_class">
                            <td colspan="6">두 번 실행 결과</td>
                            <td id="rit_results" colspan="6" class="clear_data"></td>
                        </tr>
                        <tr>
                            <td colspan="6">레이크</td>
                            <td id="rake" colspan="6" class="clear_data"></td>
                        </tr>
                        <tr>
                            <td colspan="6">총 베팅 금액</td>
                            <td id="totalBet" colspan="6" class="clear_data"></td>
                        </tr>
                        <tr>
                            <td colspan="6">총 지불 금액</td>
                            <td id="payment" colspan="6" class="clear_data"></td>
                        </tr>
                        <tr>
                            <td colspan="6">총 회원승패</td>
                            <td id="net" colspan="6" class="clear_data"></td>
                        </tr>
                    </tbody>
                </table>
                <div id="page_no" style="margin: 0 auto; font-weight: bold;">
                    1/1
                </div>
                <div id="left_arrow" style="left: 0px;" class="arrow" onclick="switchView(-1)">
                    <img src="/evol/dist/images/icon/left.png">
                </div>
                <div id="right_arrow" style="right: 0px;" class="arrow" onclick="switchView(1)">
                    <img src="/evol/dist/images/icon/right.png">
                </div>
            </div>

            <div class="card" id="modal-yes8" style="display: none;">
                <table>
                    <tbody class="table-md mb-4;" id="table-yes8">
                        <tr>
                            <td id="roundId" colspan="12" style="background-color:#e0ebff;color:black;text-align:center;font-size:18px;"></td>
                        </tr>
                        <tr>
                            <td colspan="6">거래아이디</td>
                            <td id="txnId" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">결과</td>
                            <td id="results" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">베팅금</td>
                            <td id="totalDebit" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">적중금</td>
                            <td id="totalCredit" colspan="6"></td>
                        </tr>
                        <tr style="background-color:#e0ebff;">
                            <td style="width: 25%" colspan="3">베팅 유형</td>
                            <td style="width: 25%" colspan="3">베팅금</td>
                            <td style="width: 25%" colspan="3">적중금</td>
                            <td style="width: 25%" colspan="3">결과</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card" id="modal-scepb" style="display: none;">
                <table>
                    <tbody class="table-md mb-4;" id="table-scepb">
                        <tr>
                            <td id="playerName" colspan="12" style="background-color:#e0ebff;color:black;text-align:center;font-size:18px;"></td>
                        </tr>
                        <tr>
                            <td colspan="6">거래아이디</td>
                            <td id="txnId" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">결과</td>
                            <td id="results" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">베팅금</td>
                            <td id="totalDebit" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">적중금</td>
                            <td id="totalCredit" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">시장 이름</td>
                            <td id="marketName" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">이름 선택</td>
                            <td id="pickName" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">볼 번호</td>
                            <td id="ballNum" colspan="6"></td>
                        </tr>
                        <tr>
                            <td colspan="6">상태</td>
                            <td id="status" colspan="6"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card mt-0" id="modal-badugi" style="display: none" ;>
                <div id="iframe-badugi"></div>
                <div id="page_no" class="fixed-bottom text-center font-weight-bold bg-dark text-white" style="font-size: 1.5rem"></div>
                <div id="left_arrow" style="left: 0;" class="arrow" onclick="switchViewBadugi(-1)">
                    <img src="/evol/dist/images/icon/left.png">
                </div>
                <div id="right_arrow" style="right: 15px;" class="arrow" onclick="switchViewBadugi(1)">
                    <img src="/evol/dist/images/icon/right.png">
                </div>
            </div>
        </div>
    </div>
</div>