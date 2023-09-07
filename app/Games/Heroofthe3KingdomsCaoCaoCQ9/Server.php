<?php 
namespace VanguardLTE\Games\Heroofthe3KingdomsCaoCaoCQ9
{
    class Server
    {
        public $demon = 10;
        public function get($request, $game, $userId) // changed by game developer
        {
            /*if( config('LicenseDK.APL_INCLUDE_KEY_CONFIG') != 'wi9qydosuimsnls5zoe5q298evkhim0ughx1w16qybs2fhlcpn' ) 
            {
                return false;
            }
            if( md5_file(base_path() . '/app/Lib/LicenseDK.php') != '3c5aece202a4218a19ec8c209817a74e' ) 
            {
                return false;
            }
            if( md5_file(base_path() . '/config/LicenseDK.php') != '951a0e23768db0531ff539d246cb99cd' ) 
            {
                return false;
            }
            $checked = new \VanguardLTE\Lib\LicenseDK();
            $license_notifications_array = $checked->aplVerifyLicenseDK(null, 0);
            if( $license_notifications_array['notification_case'] != 'notification_license_ok' ) 
            {
                $response = '{"responseEvent":"error","responseType":"error","serverResponse":"Error LicenseDK"}';
                exit( $response );
            }*/
            $response = '';
            \DB::beginTransaction();
            if( $userId == null ) 
            {
            	$response = 'unlogged';
                exit( $response );
            }// changed by game developer
            
            $user = \VanguardLTE\User::lockForUpdate()->find($userId);
            $credits = $userId == 1 ? $request->action === 'doInit' ? 5000 : $user->balance : null;
            $slotSettings = new SlotSettings($game, $userId, $credits);
            $find = array("#i", "#b", "#s", "#f", "#l");
            // $paramData = trim(file_get_contents('php://input'));
            $paramData = json_decode(str_replace($find, "", trim(file_get_contents('php://input'))), true);
            $paramData = $paramData['gameData'];
            $originalbet = 1;
            $slotSettings->SetBet();
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 0}],"msg": null}');
                    $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetBalance().'],"evt": 1}');                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                }else if($paramData['req'] == 2){
                    $gameDatas = $this->parseMessage($paramData['vals']);
                    $response_packet = [];
                    $result_vals = [];
                    for($i = 0; $i < count($gameDatas); $i++){
                        $gameData = json_decode($gameDatas[$i]);
                        $type = $gameData->Type;
                        $packet_id = $gameData->ID;
                        $emulatorType = 0;
                        $result_val = [];
                        $result_val['Type'] = $type;
                        $result_val['ID'] = $packet_id + 100;
                        $result_val['Version'] = 0;
                        $result_val['ErrorCode'] = 0;
                        $initDenom = 100;
                        if($packet_id == 11){
                            $denomDefine = [];
                            $betButtons = [];
                            for($k = 0; $k < count($slotSettings->Bet); $k++){
                                array_push($denomDefine, $initDenom);
                                array_push($betButtons, $slotSettings->Bet[$k] * $this->demon);
                            }
                            $result_val['DenomDefine'] = $denomDefine;
                            $result_val['BetButton'] = $betButtons;
                            $result_val['DefaultDenomIdx'] = 3;
                            $result_val['MaxBet'] = $slotSettings->Bet[count($slotSettings->Bet) - 4] * $this->demon;
                            $result_val['MaxLine'] = 50;
                            $result_val['WinLimitLock'] = 1500000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = null;
                            $result_val['ExtendFeatureByGame2'] = [["name" => "FeatureMinBet","value" => "0"]];
                            $result_val['GameFlowBranch'] = 0;
                            $result_val['IsReelPayType'] = false;
                            $result_val['Cobrand'] = null;
                            $result_val['PlayerOrderURL'] = config('app.cq9history') . '/platform/?gametoken=' . auth()->user()->api_token;
                            $result_val['PromotionData'] = null; //$slotSettings->getPromotionData();
                            $result_val['IsShowFreehand'] = false;
                            $result_val['IsAllowFreehand'] = false;
                            $result_val['FeedbackURL'] = null;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['FreeTicketList'] = null;
                            $result_val['DenomMultiple'] = $initDenom * $this->demon;
                            $result_val['FreeSpinLeftTimesInfoList'] = null;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 9;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [
                                [
                                  "b8qLSPEygdHD0qn38WYsSiYVYM//Ae/1nEXjHk4JZVJx54lwDNqQ8UvcFznkhQFDwfenDu9qvtNY5DnAHyfh27d56rGOmzcGiKAoYkDhaBqWBdpCsPNbxAPmV7R/7FxLnulIxujg8ke/4w+v7b4BmY6PMdqKrSNenKkntpMy19IEYQwf2E+jobbmIqVt1OOVfwGikqhR7WzIh34/afGgj0I2o/xEzjrpU1/A2X/prtM8xbVF6Ebuu3T2DZyh3nFa102ZfJSJTS7FUo6a2Ad36YFMZ/Mjlh0QT6904gQ/fqAS0j1qq2+3WxvG93W577lD08D8B2RS+4ySuhfY9yijmbBV0osy+vP1eo5jC+QE9P8/au6tamSFnOnG8YA=",
                                  "emgmESkxUJ5G8LA22QgC/jlQb3E5dWdQQ+ZSFsgdplBqRvMiXkuzgiWkQTc0CM/BEDVjUufKOnUo9ius+LrWzIZR2jSBPeCgaQ8deuDijIT1Dynzvfjfu+8aU8MwyirXeK0zfAccUkDzKyuOXlUJf/+1n2DciwCH++g00ysmUVLyi+M/LXNruSBCsCSZMTva9PtKX0q01PGowaD0",
                                  "WEGp6hWkbhPq5yvea4Fxh97dhhlz/+7dNpYacTFbSD4etrrILfyFrTyZxTlFOpryIsAF4gU3qJqxBcgu3CiJxyU54AAI27CnszphuLKRpbd3P9sY8MDzcUarHCJIlmDvrroKsLWAy9XriJoybsVSdA0OhU7OW98/D8wlguyh9/u3SYOPGz2Q3zD0v+esimCqJLRu4ZCQsGsEz7RM",
                                  "kGQeACg0G7anHlY5AhOSbLLMTYRsU6i8X9IM/PyZP2f0fkL2SytaZoRifkC20XCe/BC9DQLJ4lm9WIWN0fIRfaj/vudevv3ymbYZPxVuDUVwJm//28xcET90EmK986BupeIbKhRazFhcwNdAygCnHt2RSjZR259w8GnlfYhNJ73FwFnsCViZ1knkZScZjHh0do/QR8/yAXXOxdSl",
                                  "ELv3warGUpjsi5x4q/t+2Ie2yBoIJ2dBNOhJ2AZLjJbkNwE73bDv0OF7FHLeQ+Y0cV7Yc5bDGC034/sUqZXQ2KveqYFrGHj1aPXWRLE7mgI/rMIOXKgF6+zfUBYClFE9o2d+yiqP+BRws68ajJyiLv8i4gh2up/qKzDByAUC0klCrLsVwHNbqQPxGq85F1kcQbhINY+apohY+3jCVRnglvwg5q4nZoEugRxKPDMJEe1NT0XRIRrf4tlEVEjf0afnly3uR5yet+lNqHNe8uWEeiXWrw9i9gAaz1vv0SU4rNsiTkyHd9CjTOKAg2YaeS0AMU12lSNRz6v0Bta6JZE3sYSMVj/ZsuT1NaTjSbeLqSggiqYjVvEU4gmwm8w="
                                ],
                                [
                                  "5WWlR5T23CeAcoE3Ba0inA1WmTO2Mg3QM3Ae+K7gOGjVyKiK32x4lIe6HxeFknbBLJwXx7rjsar33Ta3x5aG96zESuaD0l+/2v8G+PbIi8LJbtL2eFTBuaZj32m/eo7bbHiyC/BrTiwp5P+U5Ni+IIe0jgc/FiuIHIgUBR6U38/EdqRMuxWc5r68dZn8FJlBQAElXAU4v+pTlnxxM04ZhcsfSOZIgskCfEM2CRU0dLzddr7z2KgU5aHIJKUUZZDZJz+e/nu17bEyQCB/Oa82D6S43EJ1ChiVgSAyhVf7CoJ0+MIvS4c5qJCuYSve4SgSL9BLlUyk4yGkstM1Ep6RxbU0hw9r2dChcAGd5RPjL0D4pPhuIFt6kDvjxVTO3hhdV+NU+C6bHstz74Gs",
                                  "ocBkPpoPeGoD0yaGFqbFlNIuuZtp5MLKEAosrBS14neCSsTwZ5ZynBEq5sBx5cEDTIrizWJHAln+NjyxlIGs4YCrPNco+KhZKG3/Ob+fWjZlREhU4YQw0NxqkSYlCGwsD1GVOJaofZxq5Get4xlinpI7LV96NQId8BpyC74JEp7NNI8z3OYMj2tk5DswxYq/IfvTfF++1W+i8VrY",
                                  "cR3OxCotIajy8OyaHh8I/qBgWoVIaXopFDFhMvI9LDaXyc3xwQNrgjL7LIMKxKo6p3I5eY4j8bURzYF0Ioo2Sj9k9f0aJGOTA7rD0/hExRu+VKHsg7VspvxJUtxMVd3HGk77gV0YutdloziiX5rumcpn+ecqHmf4QF2SUX+gM6aoV70Cj8LdhH9NQKBNeqE24EXgOF4YZFJhIJYA",
                                  "nlkl5AaTu8erHeFqXt/PjAx0raVTGBh++WPcBgqlY2rkSPGMFooOo646txAqfLuKM98RJmjfhqc+In839v28TqoJ40RjfsveIzNuCmSgQwjGPJ30tObWTiHNf4A7UFuBMx7xgGrTZ7m94z4Ipx+sTqHF8YgfYOYHnu1BXW20XbBetNVFFthTtOD5LWLK+N8Dd7W0plTKCGaxdlnK",
                                  "KI8goKoacfiKNTmtKG1W/elEVQXHkm4JAOzaR10et5zNMK9Sj3mRpMJ7Cl7yKE1YQg3slSMQqDkL0IiZE/DcEMmdEkdTEwDjxARKAoEFKzi4eN1oQWUb3kSW3lt28dVr4SM1NDYq+pOmioC7Y+iZD3SBX1S72OGmMTQxflrbJJfxuSmiZAiA4Qt5XSnGkeiL8dRhek29dBdD4Vn2vMk3Pm1S7NWLwW1hd8ZVu1/losJlzA0Kr5sGhIAa1h6Fo6DHa/nbUsTG187qCxEyIUsCY1dPsa5/Cdy9sBcgNXr2bnxrIbWIcY4JfWChJGtQc+9fcFc0aVaQKAhAGB0YmsYgoqeZXJfyjk3qghjGvHYdGc3rBL5r7gLQzQpn3Ok="
                                ],
                                [
                                  "tp0qr6ZyNZavBzhPuD5gqA9LHzMsC7mQElJeCnNFwLSegQ55o71KC2eB1L5JlT5avfnWVe/QQkXlJHAkLBaDNFqpUuYaMVG143KmcvdisnsQt8cE62KCOfQu2yjNkzVZnvJDYOqsFFquY+My2f0nV9uVKUp8NnuDbv/yGZ7yr+VjUKfAwKeFnniaf5NQq0AZ5l4juZxCEIosuiVCDjIvHMcyRsvnl1dNksCx3hvhm29fCv0Cm4tKly2qpsrlmnWR78VZv7qY8oWzdVIQSleFJ+iRIXfe8Ki+Qn/N8gAnqGKF2nPQsg5Ipcaz6aTdqHJb1ByuhmG3TPFMaSDhMK6yiaJAoLzzmmhtj03tqngjnOgMSY6PGp+BAi+8f4QUNePoNqL9E3kqnCROq1MQ",
                                  "yFJkhtCU6xXBqzRKMXa8UYbB18E9BZkmZTnZaPclrfjnQQ/3CufTg83NUnqTvi1+bSlzTCvMfPVGfsCJMSNLoAj0DH3pVY77rK/iAy6MrMNJ0jV6xeXcsb8vOuY1or3+FjnoEowhlzsTLVhbg/Ma0uiOvehHrHfAQrIUHdTUvUU7C0LdrASGYvS47WkVAbOZs7yq+MQ4cmLKboTR",
                                  "XCA9KWlSxLtAgGM5XkaDqbRqWDOtXOMkG3q4WxXxAftkOq+hi3yn+wLIqJ9w5D6CCl0PbzMk/G5BZNlkFPeXHXT09uAc+FEIj6ZrVVvAQ9TkU2mJ+4Zggwi9DsPtYgUd2IHjcWR3HD/tqCaDPG9EfZ4QvvB8tP7vSBCVCdV+4vQiuyvAFmSMM1C6mrJkChF0sh8k5/ae9e1JsYW2",
                                  "rv9ImygScjrS4GnTtAgUW2R1Lon1AWkK3Qw18XhLYvKWa77iex229RMNgMNkyrGuIVCjLN8iTCA7Xk/Q+OwPrsLm+dfRyz0wkYI4WyzD1bl8v8NSJA1e3m61L4acCYlupAcLjtvtmuR8XgV+tDvaqf28Yc4HONdEVCrINmI9JohEeh7RPak4BETnFfnd6m9PIY4pEpl+mD0qeAy2",
                                  "e5bTDw1ZggbEsytsx5PCHGuBHdtImZEzyk4a7o2aRAEqtkI91hDwOE6YFAZopha2As1DKR4LL95ZGmFCH0m/ghmzTwQ0eKIPFdaimROYqaytA+dMkxs9HmAc6KkwF3w8lHq1vwKAYjzjTjh3yUrScqa/x6Pi33zTA3J2yNotgP+naBK5h7MSE7/8fBDksmgVaO0B9l5cccbf5Cm+C6reXSA+W397vDcy5cyhjd3aSQol9aD5EmXQrXoqVvq8Qxzw3hS/AJVGjUOUjbVY3RTVUUpLN8tjO8FtGEanDzglGZrUzPTAS+jZaAeV/BUBwUTm2aWByV92+YCrpgY27/9R1bF+0lJy0aaEvG8HZZbA0wizM31hWlJpjYGo98Y="
                                ],
                                [
                                  "IjMqJeLBNqmnsgbDqRqXXItmmgpMjZqjY3tzKLS8N7DCBfZ1e0QD/Q7Djru25VHPzAakiIn+nSoCqHfErHSQgc8vBkVa+fwbAaZIL/nAAitc2u7+ZinkUJTmdIvT3H3TGMOoKQ7UctuRQsT5/Y98MYkXvcBAcD94I8gUqbAm5V3JwhE2zveMKfCcWQR85k9Trgkq/z2oxXnnL/VS8LsSAL7jWiMhurOLCtj7sLugiz6RC8O7aJfotC6Sl/YBtEjo8w6JceuFCNGpbPVOwDW288AUt9u+uca9xizdVHXh2YyBDvHHatPCD+GwiyHpQBeDgeG7AyjOyofY7cZdO/zlEBN4t+rEA0IVF/WZsfThoEJFnw5iFUT3dPp9iVI=",
                                  "rOKQpRIz2W8HQwRk3EzW2hDWL24gu/orEa6dv3Qn71bylslV1qRMOzdolWQqomB7UiQRm9yWEkV+TURaZTDxOBuonC7QknhZtmQn0JSD+k4cQazFvV4IPbJB+z6dVnIyYDg0oU3Ec9Oj9KTnGtkZXjutM7+0dwC2kyHHswISrRdKUgzUySfKTB17x5whASC53gUCOvznKqbnl+LZ",
                                  "2XbO1ATrNvw2UcmZVNR7XmV4zGeYsn342FzEONrQxic78MMG/ZOjxuYhE5SwXJINck84CCraROojpJE1F64rWtAUEqkEqIE2jUzuQtwu0Cf0xW8NSvWdRS5P2IgNe6c+2Jwcx6BX2RjPHuGPIUzKYVA3vslU+l91WyKfIICSK++B2qxHCnDPJyUpR7qJzrc2gIqW+cDqWWvQk6/0",
                                  "uDm482xrz8HoKSeDB9VU9TDai0QpWaYzlA/xDRUUVcJN/slkBaGfLbDVEvZ35o15p0v3GUxawGf1HDefAd37uRFfyXwFTN11LO0DEp7reG3CwGVTcCChh0GwHIo2kLCIJPHAdcywLjSWFKto1aw8N0d97mq8Tt0DzfuCNumkCkDHHBsD7BMi/og2RcSAvvQb3WPyBgoc3EwjZ+Fl",
                                  "yGi3eu2qFYSBGibf2bbVKMhlzcEE2jh02MmxH5yHWL/IGvnfuf8z87/YXf2KSwPylX784LTb59xoqoSfwd8ne8OonTccdN7aGobTYSrx6b2N7bBJHyupdzPqsytDj7vNQZsBWLFaJ9AHcLDI2D/9xK9WFaIKvJjfbLOI16S6bpFtqdgJh5P0CA0IaJ8aGUT9x2Bs86pK13veKAgxKe0Z7NludkDe/9Eek9qzCOAODhYxUlHcu9b1Eid09uqa4I5kvgQw/RY7IW3qB03XkgC5Kjw9+00wQ+A6+9dH9vQ5/JI5HmhTHAk/m9nPKAcYmCX/SNMdZiLYoL8A0UwXzejp0wzaDo95LQkZVe3bEw/JEOcykxBxrwGIzsJCea4="
                                ],
                                [
                                  "EadHQvxjCdBQRiHBdXwjJVM65oziizgdgFGs5Z0GE8F2hojImpxaiDxZOpTQvs/6Bl8LCgCcI1kSuzpFAhklf7ftTqRPy9PK2OgvtP8r49QYhG+S40wJDm/Us9lqwCRzwdnLpKGBaV/IJ0NK2WQu/xMIWvoP4Vvq6KQkzMvnQiXoMtVIDnTDR0W/DHxcswMEHwmA8SuCBc+xhIdrzcxDX8oWW5WsqEOZ+BU1HDfwOZ2iyDevuWhCCChMDJvt62z+guzS4dSP71h3I6NiuIi551vvTAM1SPXLZJK4DBc0uEPpL5Dp7aygbmxxV9wA4pBUVHhoHecXGBF5RJixGLkApGAshW0Om5LHocFzONKWJcS4Ui7/McA4vcuNeJc=",
                                  "6VEKd2y8i0aNhFKNQHrZKVSZS1NBH108oNH1xWX/3NBCOgfTnvErPrn08OelG9kUUkngeJ12PMrpJBoLcgxrWjHOkSCHLAe96LDI27GBCPAOpu+l8iUF7YjW9PX+3ujbk2VG2X1TMiQHs+XHgH2ojip7Ony81IaS+gSxwnziJg0yWFg9BfdS44usso5dkUxN/xO6UaalVC/ghzV5",
                                  "yeKjNPLtocfNlWmihomgcGQHVjVoZjbIxzlsQxWwOYGktSB2LGdmfewh68Pi8qNQypFMgRdzF9PIiYaBbugwjf5SD88c6vE6StlhupLXnODnsMgqNXNSiF+e4gjOvfVdgRWf+HyvTHKR907IZSzTi2U68Fb62XRDDHsAK/dyZhT2+Ez6xZbCa48iRQ1V7JLi/ku8GzUgIcO50vji",
                                  "FD0x9sNkucCEb9laQQs4Gnar7jGuOySFVCWuHaqmTxWf3RUtFsmPtw/Nhule1n3fteCNNp8dbaV3H5imcNKqrgY9RXOa2XTFpoLsabnlX3uTiWvBKVHJkOhrFSgF/MtMb4y1Vhly9PVoLHLRYIw17cFdWSgKosAISSL3pDKRhEIsxy72IA+xxXFciNiI/96WDSkB4Q9mjGAeASPl",
                                  "4IQPCENzKUAhHgjLLf0WLl8nO+4LvYvT2huK4+8DySZ0EFJ1RfSn7MWg+YXHoyEYiEWSjUpHmglrcaXQ6C1cmwt5N2RnRyqEFIEu99sMxfZzLPP4+MdwEM/vysMl+D3Y0NrlX3IiisKoaVlKMinS7qh9pRbpbKrawk+N8hNfGR1HzIHOMNgj9YVaSfvSxVQ74oXy8lUyOghfydgpa48trZG1IkErh9v+i7Yu+1Wb/MUL8VjUy24fW5P1Y4Yr4asCRs3pM2p3F52wW9bU1T1CDMdO+0rOyenPFe9hY97OwXmoWd7qycG1SBzI1Ck/uhI2v/2Pq682Fm0+QSm7dSsmq3xI5+78lmy8rO3IgNHhscF86gxXTp5MJ8yW5Vc="
                                ],
                                [
                                  "8463RcWhtMgxVw20ARPYNd4KbRLcRVck1pEL84FC57wyTmlEeajE/fE2Yo0aPlf2U30dVozaItNtIWojXZnk3amkxhAFeDyViHJS4ceVh0b73kbMFbnH201GnCiH3qyarI/0Z54CuW1ZylnPKiafdAg0loLQoUHNKihqtDgiA9PKAezSSPfzCyw6ZTpIyBvSEDqsip5T7sYf2knxy/ogiiQLrGfSWdIWTAOtxDyciLpTr4nTybjrVHrQzm/eajEyPfI7Kp3ltKb/2hpk+1U6Mb2nS4azvUQ9+Me4uVTfjDu+vwVhjcsZ6JPWvlN8tSBte4CXz3hcaGldrqekcpGAxMgmwnY5/oIuhqvhJ89Wg1xuifocuD8x8TDvSYI=",
                                  "ehnfQyYNyNNIKoQG3Gw62RDy/CBaSeQWc3nqymu3NuD+R5w4af9PBEev8euGMaAvwzzb8NGNnRSpexXwBAQ7ITtIPZUypaOcus5wGyI1rCBAImn9h6an6mn9mh1cq9kO/3mwfb62ZqpoFszExh3xgLpHXi5C7MJOB9MIxYtxZU3uv/ww23T40jKWBn42TqVmoItxzbyUIEBajWqg",
                                  "9EmV0S7BPhu5ZEvlve32kLnK9sDb5nT9RvZlY4nN5D5vYI1soR8PX/ccsw+9V8vsTWts2jI7pMWqMUbe3Jx9HGG4bIPGrM3/j0XdtsHf4q71OcflvY87w9CoKvt+ywOFZrzkBeN0CpZM8fVywjMVlNHOQC9bVyWEQDg3ONmV8/xYVlEYvz4GQfB6B7avy/it+2vjSEm9sgtQYo4f",
                                  "drdd778MBfmHWBbzWQEec/XcQBxvjPO3xbQyHO31vistdOsbAn1dhDnCwFXIc/gE6ajEEHBDdXxZaIexd/f7wfO2OEXaC5CD0wJWag4pYysDQ/Q76Xs3Dq13O+HXWcwUkY0oigGwLeZwSQLVskDzKen7e5TurWabNZSjsAxNdJ1R/QBZz1FpGWHwChDHNdyEpAx8I6RTaTt3ODIl",
                                  "oV2I9r01pFmJGKKVFNmi0dkEvmOaqRGFF/p2Yie15ZleRSDmDEf+p2G0PietWNRUls/pu2bEauO38CQ5/eob9G5rzgKHcMW1iiMnt91FCd47NiWbsFcr2GdYVy7zjX/yH4Mw+lh23/3yw2ZBFjtPyNK5w4koUqkqzscS9k56uuRhnwhlSRQwTfscufOGVp6owIiaGQSQQluibxpkDkuvHaTMLqd0OtTnBQy4OXUl92mBz8xZgtElkQdx5tQY+PjMcijWQhqP9cqJk2eQN7OmW0LmpdxjzCMn9LLsDBehrFxsBs6KBMWZNY5Or5Ln4JGNog/BeWTSHfqqpsAx1DBuSoSlxgxhiubCJ8IaibNQofDpqKIzWQmx8bQs1ps="
                                ],
                                [
                                  "NNnSMngdpJ3p9OwsYl5u99s6hGQLiIV7GjhjRBfZCWETI7ZJbITICXKlxPvFPo5ZvE6rXm2r8AU+SHGEtPXNsv6httFY19RkepJRLLMAP6ysZ3xyYmeQkxzwIMYQEFFUgMTtcWKebEMSKFjDs+q9Fu9MiVUNgpbatwEILe++xQyzyFR/ABZUzlUgcyyTn+wUDJR3MwqjOqzVJFEgvW1TY8O2JedbOv0jk7HfelU8V5Aw969YV9PCvF+JBqVZ9IMF7uEW+OENVBn2G5RnoeLI7hwF+ohN7hbCRVPAVmRm4+FIHiXWAXHHMbwBC0lscu0zdaJnanVFMPRAUXiattM7bCkcfHVa8tBKBY7I9g==",
                                  "qbPcrEem7CtJxh09QhBMw184wtWPOaFopgtCI4STS+TQfmeByiCMVK4QtMbfiFf//77g6y0DZw9Exftm/ec8dQE+5hif+qqC2msVz+++Y8EMm/iwyVz0TU47IVWSacpPfxl1TrIEyiBxWT6SoeoE2eVmP6l7wB/NHEDzFTGfyqwnS8J/t30upWpE11XiFfiC40BEngF9rvGz5djEtfUr0aF5mnYCSPwy2DoYfQ==",
                                  "9jOxokUM3exdW2BpNWll5GzWIHljghbS8E8ruw44uI2/8Ko58uZHE3o3lTuYlHKhWZoEaniQIxCKapYO2LUu6UivH4/yMEwPO548NmarHZDPUi861Sjqo8NJALOExGIOQTg+yJ9RPXgGp0CYd9sxbecBiypvyGrOh65ImsVsjo6wrX2fVIoTvUiY1WyLeMAMYAFgLfUUK0tMMhDf3NkRcXaKfqxmqU5J3KGAAg==",
                                  "O0fdkQabqRKVF55CjJez0eWdXIVznYqE62Ve5Mlj1A8fV/ybKXEsB8Q/z+E3T5VFfn78En4SkTqAwG23hyqYjgBbTBbvKMJrQZChFsnp1eFC/XqOfKVQ/Q9Gl+T9xE2BclkhxQIel2qP699C8y9YAtFHKLaIzlP7PyNSoAFl9JWdcRqAFDge9RQKS6v7Z7sIKPt/PsnYyf8/9mkYdjkqZVBZaH73amqjHQ053A==",
                                  "n5ZzSbcgx6BmcXW84fCvBEQZVagphpHT93ujMBtwpKdRy967rJmBonkOrY/+ILFmai66zKvMsHH8RUSEVguwsJiz2M3yWYqAWJjDwv2sfHXmdoq3GFnbSLSt+quG19UMHtZf2Byj6BLgMqIjqG1Pb4IX4m8EvMFduqMU+JPMdApaYctqH1JQsbTA+k+r2i3eTbyHkMOmVf1taHDk6fFFe1Mi3v88zlRtMr8rsNNaP5Lr9Qm1EpK/aFtXaPUB65iahAZIx6jkXw3knACOofIdrlpHk7KOaztIv7vJPjaJ2w+trgFkPMMwd517R99TD8iaLxCwhA3O5odGA2QcqNMuGHcZGSvBVC3M9lzb5w=="
                                ],
                                [
                                  "meFjslypBHNk31rvWKRGihi+FtmegDs4ZfPxzacSpgAnz+AvEU/J7TdkG4SxHJob4a7xn0wHPNVM5+qof+TKZ65Y2U4U6C8nIJqCw0lgmSDrxQ31Q8BEiLUCFBWrR7HYGZZYuuwXep3sM+7HxcfljCIuWQ0w1Jj24PGzpigrLuau2uOz4YZ9Bjrtwy2TYondx+KYLC76p2YkiVG6/u58a6rz/mOvN5QfSXNh2iYeQ2JM5M3J+mzeEgGl+q8w3j6eJ/Vjj5ghRK3MEJIuKBVbyJwGjfrJ8wId3t5ta9Dg7F5FM8D1uuKBjGKxetExniHvJMQIYdft3enyunZ6h8yMcANfI7EuhMuuw2ArdrxHQ7m+j2wB9trvfcEFlQ04AnIhVm1JF2EvNr+/NIzN",
                                  "xCiqc7a14mLD3K9t3sqyLej74JX3eKyreNOnahRCdhIgzkzw4i/WGQroaUX1XbdS4bOvycOwojFa0y8fPi+xrCfDjV3N4J+fVkXp1irraT1JZhD9ETbaabvMlw6MsMf+33wMOFdw2B4qJwLogOVR5GzYa9JBU3OaMDmGt71QEAxFTIrYrh9eN5wfpqKVQbY9ke2Bme739Xma8AF+",
                                  "aXbLjKkewayMzzMYZQaJ5Od35sf1iOZKNpR4KyMq2hRZKpkGxtBfi/zlZ79FjcXxQXwJ86FFfuZtnSIeH4M1ZNTYj0+lG0YciLu28XoIUYc3lO45FHCRROtMYQ9Z7b0cAeqXE4fjbIRg0qF1NjxioIpca5Uu7ZvhW38+rasUx/F0konz33rtSJgj7HvD/GZ7Xrw250DUEwi96J7s",
                                  "4P34GuBCIl3lsnF2G/pUruDtk9iELwL3Rzuidok3N5kHAuUdcHH1PEMJoIsC3hUrXwoCbVhAp/L3cehHlj27FToOnfJPzil47KblYgPWEbfm3PE2mQiatk8WX/kxmTzJzCDpQdByHhYB9vnk3NHFssw19qph08QWGEsby9GY4sool5FEGD1V1zEabWtjI3DN1jspAD/gUMZyDqiF",
                                  "8vm6eZdt2Jxxlnz0mU5mXnzAevwdb5dHUooc1wbetiumVapQpBaTt4SrwcR93bC5up7mZxAwZ7togg3vsVXHh8ZALAORyFIzBRwpgOb9tYuf6Gq+i3Jn1Fd9VynIlr7LaWuSP1C+ARF9jFo2Sd0LOF88gF5VxnqeS0l0lPNTdKQwEOkJp/D53aR5Fgbtrr36ypEg/YCt1w6126mWmX8bf0YlqkQXaOd8sEPd8N36QPNZufxrH1j9nDH4Cx1UyF5HYo3BhgF460dG+3LIeacckeJ+EdvIR3ZMHhShTBd8Wf4jgK0ctW+LVS2jW97sW5DPfJLBEaj/M6ku9gu08Rc3Dhs2uRaFswidpC6b7+rznDZdAdFgBKpCLFhA8ZE="
                                ],
                                [
                                  "OAqMWCMxvckfroQWJ9eS2Hdjo3yTlKkwor6sIpan8E3ijRomF/XKWyiY6a8Ypn0OaD/+7ddzGXKjeEc3WiS+clwmxQRf5ft8G/86/Zvt3KDc4CvOT9Hn4TV/OtZG1IktWrJZJyq7Nk6GGVOact1POGUTkI8g6IXchYX9zpnTVKMgi8XAgOKm3DU3iJcwDTtgTDKWop1I+CHx8k8oQu3q9gRE5mlYhDKgJo5cliYBOrYYbZ0uimK7DiZi2hhIwnwrm2dGwBhsFpc5EpoLXZbqrKMCZjZpF9MuhG9NR+XMsDTaYBjohmE6bSnW9/sUcomGobDoPZUzO8fyZZKUnSLo+Ey+N/dUMd/1dti6UTWtJAmwtFK0Nv+SuQA/Bqg/GxHd3mWnm46orKeI+qaV",
                                  "vQNBqYOqp1mIkornBpJO0UTb8Y6RmCERBfo2qKBHM8oum0xiQ5fldfEY5NIKhLPYJqd6PqBzN22vZwjsDb3vkTwGebzKP+c5c92tvlxWmIfZZvEiOEvAeCTf9Z5ai5x9d4uHIU7+P6IYsjePrwcFUsOUJRGOI8I2tC9B9nBztxltQKwI4TDW1VCaZWeml8UVlO7ZDvBQ8LHVvEq/",
                                  "nAuwo6e9WpBkOkvS2vFHQALgXe/NrlBoOZT4I5QZ9/3eq45VaW0RIWIQXiyYMLwoL2yaF1B65sedVeara5Up6to02GZifIVJCO3mUjpVPFOaE2GQOqYbDbooh2B3bH3pgLJtGAV9h+C/qZ6A7yT/4qN0w6xUtv5BWDfsyt5ABIbbIzdBvz38/HgoLo+V9pgtm3ETg0v5x8z5pNCs",
                                  "CbsBRU79fiTikwyQ9p19bvFpLV8YxRfax7JEhoE8tidm2nePM88QShQIdEeJqQZRRRzLbDZl5Jxlcp1gqSzWQ/3tBgXglE349KeDNvr2jIP92Dgxb+ubESFNDUR6xy1uPqMFIYCJ5SLnOGK5ZI0QYeGVsk0A9D1zzSX/MMOg42EowPC7x4mpFM7Qw8R3830vCVf95C5AqYeoZIEN",
                                  "SrJMR3XU9JCjLxxB2Y0/bd9QqGOyoTjVy/ztGoBwFhBmQqo3YHYaztNZA108hfiVxwiaHRdW0hunUcjZk0RlXqk4Sv+89Uo9mFZiMAgFLizH3yLO1xBuifmX4bP5X9mWGKYWhWzolOeVnz8N2aubiN2UIrJSaOQt5U+IWxKQJhFsomvjDZEhbABIb/DTebnCfgdp8Od4tLedxL2v086McCKETFtaIhmVB6N7/mw6ABd+hT37pnhnpTX78y3j55UVRNWewKRhrj5dvyOhWdijv55SeibWc60aRJeIorYgUgrwoAMhT7wVpOgv+1OhsnFxXyaUqXJrwAvQSL3ezKfcOB+yohe8wUId/N5EJ7ZpuJRGJcx2XmNUkPUakus="
                                ]
                              ];
                            $result_val['FGStripCount'] = 1;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["EwIPnGsT0HGisv4FpCrlheU2HV2G2sNUYoGE4sXxy1PU5PLRpRH6IFd+GEl44EkHwjik3jwj7HPnO/nstuHIZZ+du5x3arkyM3KQ3rMN79KjkuSwOypQIRQOPeFRy1xntEjxcZ2x79AyUyenXj8GiIlzRDGQHQLffhW7cIHPi0RyqZUXx3e1i2bKfgSyj2CXd3F47wVW1PvMoSXIAoFV8ICZ9LQpUjK9cRM3q5rm6unCVGQG6nmJT+AOiQcyUZByGBR8axZ26tNIUO7Km7V8Bl07w7QaNrDQomtnS+bjJ3hMh91DdERCEDNstaI=","0y0iebCIRxhtYKbTcuBArI0HMqq6B3J0rTtUeR0sbYxuFRVYctPncWGLEtCzYO4QZnLv3+hU1IEEThsFnRjjKXvdDxXPHuOL08C8IVuyTyZlb6IMx7Vr/GzwTBNravo5YJjVyKwNGvtSgIFXQZto682/xsRs7A644JDK23UtFP9QFFA3WEMbiXNXvHmBV1vG8YJ0zNvX+EYu+6vLN5YeLdpoIGpwiWNkIHXgWfPulFKjHguDH6ypt4fpqGCZEEzTCcXqo79Qgc+VN9HvkAbgmmJCp+WEXVc7DZ5HZ81vpLwuw3rRlqZ52hLCeiY=","pNKNYKbSpR2oBgRl5gB57oSN7rz2EgH6ZwCuSQdUuh3QJEiRNULRq5S74j2uIDLC55tWCB18HS2Jwh6p9tFhSLRD1TNWFUn/uJGYy7cCeN1dkNE3O5ruY1E2fc9q/ZhAY28z1df3rZeCaDhpi5SjPWHLrerH7xCQXb4XnSHBUWx0hTMbGd2BS0fADI9Kj3qm4U7IRiCDC6l+zm+CuQOzznUAqzAI7ssYcwv9D1fTK26IqHvkC0d3TO38uX2bhzPhRtBDuRlQ44yw1qM3DVSQkWnOT93JhyUnvf/JP0XRHlHfo2dwovn4UMeHsuY=","7ICJ3WL6e9cPmYluCmoTXfS6w8OWL6Yf44piJg4Xyp7DmCek33R8W2nTH4hmOJEWuG4JcpJqojtuQzJdwSOEKu7VD1DeOTfM6ZM5Zp7OU0/cRzuFRXh/N7MjK+ZLiViuxO4N4GIwywsE4MR2M2ZfBZXwSlBRPlhVkIGZlCcV3oaESyK9thKj4RltwlDUhyCzCm6s8HLUY44Jur0CJkQ8D96fAoIt3/kbYTo+qJ2/9oxKbKzk2vuCe1DiKRaMZnYmSOi6pE9oqAmdq2bONFKetFZoGed241KcmAa51BNdRS38/l1Ih8N/ndp/Rco=","DJegIqKacPsoNcZoGQjfEskPXzYI5DCDuggugSmhOWIRj43FDfG6bnxhihjpmtYwqMDsRfcSzHTP1P8FejLhN6z5LyNN3p9Dqi/AHGcav+YOJbqphMrLwORuEfX+fR0O8aE66Qv9Gz/2vR8Qlrjij3+vgoFgIgnxaIRet7CQLKKTZ/yPEm5dkjOqphntBObj0QytfcLSHyd81OANx6Ukm93DKYs9xbHYuwvjhesuEO67c+uHR2nowmT1ftxDOds6R2sBcWQNve3KsHqDQ6IotUb3+mnKwZnUCLX3BqGdjdfp707MXQasZWHclf8="]];
                        }else if($packet_id == 31 || $packet_id == 42){
                            $lines = 50;
                            if($packet_id == 31){
                                $betline = $gameData->PlayBet;// * $gameData->MiniBet;
                            }else if($packet_id == 42){
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            }
                            if($packet_id == 42 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                                $slotEvent['slotEvent'] = 'freespin';
                            }else{
                                $slotEvent['slotEvent'] = 'bet';
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', ($betline /  $this->demon));
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 50);
                                $slotSettings->SetBet();
                                $slotSettings->SetBalance(-1 * ($betline /  $this->demon) * $lines, $slotEvent['slotEvent']);
                                $_sum = ($betline /  $this->demon) * $lines / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '130' . substr($roundstr, 3, 7);
                                $slotSettings->SetGameData($slotSettings->slotId . 'GamePlaySerialNumber', $roundstr);
                            }

                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet);
                            $result_val['EmulatorType'] = $emulatorType;

                            $slotSettings->SaveGameData();
                        }else if($packet_id == 32 || $packet_id == 41){
                            $result_val['ErrorCode'] = 0;
                            // if($type == 3){

                            //     $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance() - $slotSettings->GetGameData($slotSettings->slotId . 'ScatterWin'));
                            // }
                            if($packet_id == 41){
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance() - $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'));
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                                $result_val['PlayerBet'] = $betline;
                                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                                $result_val['AccumlateWinAmt'] = $stack['AccumlateWinAmt'] / $originalbet * $betline;
                                $result_val['AccumlateJPAmt'] = 0;
                                $result_val['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                                $result_val['MaxRound'] = $stack['MaxRound'];
                                $result_val['AwardRound'] = $stack['AwardRound'];
                                $result_val['CurrentRound'] = $stack['CurrentRound'];
                                $result_val['MaxSpin'] = $stack['MaxSpin'];
                                $result_val['AwardSpinTimes'] = $stack['AwardSpinTimes'];
                                $result_val['Multiple'] = 0;
                                $result_val['GameExtraData'] = "";
                            }else{
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                            }
                        }else if($packet_id == 43){
                            $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                            $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            $result_val['TotalWinAmt'] = $stack['TotalWinAmt'] / $originalbet * $betline;
                            $result_val['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                            $result_val['NextModule'] = 0;
                            $result_val['GameExtraData'] = "";
                        }
                        array_push($result_vals, count($result_vals) + 1);
                        array_push($result_vals, json_encode($result_val));
                    }
                    $val_str = '';
                    $response_packet["err"] = 200;
                    $response_packet["res"] = 2;
                    $response_packet["vals"] = $result_vals;
                    $response_packet["msg"] = null;
                    // $response = $this->encryptMessage('{"err":200,"res":2,"vals":['. $val_str .'],"msg":null}');
                    $response = $this->encryptMessage(json_encode($response_packet));
                    if(($packet_id == 32 || $packet_id == 31) && $type == 3){
                        $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetGameData($slotSettings->slotId . 'CurrentBalance').'],"evt": 1}');
                    }
                }else if($paramData['req'] == 202){
                    $gameDatas = $this->parseMessage($paramData['vals']);
                    $game_id = json_decode($gameDatas[0])->GameID;
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1, "'. $slotSettings->GetNewGameLink($game_id) .'"],"msg": null}');
                }else if($paramData['req'] == 1000){  // socket closed
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        // FreeSpin Balance add
                        $slotEvent['slotEvent'] = 'freespin';
                        $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                        $lines = 50;
                        $count = 0;
                        while($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                            $result_val = [];
                            $result_val['Type'] = 3;
                            $result_val['ID'] = 142;
                            $result_val['Version'] = 0;
                            $result_val['ErrorCode'] = 0;
                            $result_val['EmulatorType'] = 0;
                            $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet);
                        }
                    }
                }
            }else if(isset($paramData['irq']) && $paramData['irq'] == 1){
                $response = $this->encryptMessage('{"err":0,"irs":1,"vals":[1,-2147483648,2,988435344],"msg":null}');
                $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetGameData($slotSettings->slotId . 'CurrentBalance').'],"evt": 1}');
            }
            $slotSettings->SaveGameData();
            \DB::commit();
            return $response;
        }

        public function parseMessage($vals){
            $result = [];
            $length = count($vals);
            for($i = 0; $i < floor($length / 2); $i++){
                $result[$i] = $vals[$i * 2 + 1];
            }
            return $result;
        }
        public function generateResult($slotSettings, $result_val, $slotEvent, $betline, $lines, $originalbet){
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, ($betline /  $this->demon) * $lines, $lines);
            $winType = $_spinSettings[0];
            $_winAvaliableMoney = $_spinSettings[1];
            // $winType = 'win';
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);

            if($slotEvent == 'freespin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
                
            }else{
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, ($betline /  $this->demon) * $lines);
                if($tumbAndFreeStacks == null){
                    $response = 'unlogged';
                    exit( $response );
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $tumbAndFreeStacks);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                $stack = $tumbAndFreeStacks[0];
            }
            $isState = true;
            $isTriggerFG =false;
            if(isset($stack['GamePlaySerialNumber'])){
                $stack['GamePlaySerialNumber'] = $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber');
            }
            if(isset($stack['BaseWin']) && $stack['BaseWin'] > 0){
                $stack['BaseWin'] = $stack['BaseWin'] / $originalbet * $betline;
            }
            $totalWin = 0;
            if(isset($stack['TotalWin']) && $stack['TotalWin'] > 0){
                $stack['TotalWin'] = $stack['TotalWin'] / $originalbet * $betline;
                $totalWin = $stack['TotalWin'];
            }
            if(isset($stack['AccumlateWinAmt']) && $stack['AccumlateWinAmt'] > 0){
                $stack['AccumlateWinAmt'] = $stack['AccumlateWinAmt'] / $originalbet * $betline;
            }
            if(isset($stack['AccumlateJPAmt']) && $stack['AccumlateJPAmt'] > 0){
                $stack['AccumlateJPAmt'] = $stack['AccumlateJPAmt'] / $originalbet * $betline;
            }
            if(isset($stack['ScatterPayFromBaseGame']) && $stack['ScatterPayFromBaseGame'] > 0){
                $stack['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
            }
            $awardSpinTimes = 0;
            $currentSpinTimes = 0;
            if($slotEvent == 'freespin'){
                $awardSpinTimes = $stack['AwardSpinTimes'];    
                $currentSpinTimes = $stack['CurrentSpinTimes'];    
            }
            foreach($stack['udsOutputWinLine'] as $index => $value){
                if($value['LinePrize'] > 0){
                    $value['LinePrize'] = $value['LinePrize'] / $originalbet * $betline;
                }
                $stack['udsOutputWinLine'][$index] = $value;
            }
            if($slotEvent != 'freespin' && isset($stack['IsTriggerFG'])){
                $isTriggerFG = $stack['IsTriggerFG'];
            }
            $freespinNum = 0;
            if(isset($stack['FreeSpin']) && count($stack['FreeSpin']) > 0){
                $freespinNum = $stack['FreeSpin'][0];
            }
            $stack['Type'] = $result_val['Type'];
            $stack['ID'] = $result_val['ID'];
            $stack['Version'] = $result_val['Version'];
            $stack['ErrorCode'] = $result_val['ErrorCode'];
            $result_val = $stack;
            
            if($totalWin > 0){
                $slotSettings->SetBalance($totalWin / $this->demon);
                if($winType == 'bonus'){
                    $slotSettings->SetBank('bonus', -1 * $totalWin / $this->demon);   
                }else{
                    $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin / $this->demon);
                }
                //$slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + ($totalWin));
            }

            $result_val['Multiple'] = 0;
            if($freespinNum > 0){
                $isTriggerFG = true;
                if($slotEvent != 'freespin'){                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespinNum);
                }else{
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $freespinNum);
                }
                $isState = false;
            }
            if($slotEvent == 'freespin'){                
                $isState = false;
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
                }
            }
            

            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
            if($isState == true){
                $slotSettings->SaveLogReport(json_encode($gamelog), ($betline /  $this->demon) * $lines, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon, $slotEvent, 'GB' . $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
            }

            if($slotEvent != 'freespin' && $freespinNum > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
            }
            return $result_val;
        }
        public function parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines){
            $currentTime = $this->getCurrentTime();
            $proof = [];
            $proof['win_line_data']             = [];
            $proof['symbol_data']               = $result_val['SymbolResult'];
            $proof['symbol_data_after']         = [];
            $proof['extra_data']                = $result_val['ExtraData'];
            $proof['reel_pos_chg']              = $result_val['ReellPosChg'];
            $proof['reel_len_change']           = $result_val['ReelLenChange'];
            if(isset($result_val['ReelPay'])){
                $proof['reel_pay']                  = $result_val['ReelPay'];
            }
            
            $proof['respin_reels']              = $result_val['RespinReels'];
            $proof['bonus_type']                = $result_val['BonusType'];
            $proof['special_award']             = $result_val['SpecialAward'];
            $proof['special_symbol']            = $result_val['SpecialSymbol'];
            $proof['is_respin']                 = $result_val['IsRespin'];
            $proof['fg_times']                  = $result_val['FreeSpin'];
            if(isset($result_val['CurrentRound'])){
                $proof['fg_rounds']                 = $result_val['CurrentRound'];
            }else{
                $proof['fg_rounds']                 = 0;
            }
            $proof['next_s_table']              = $result_val['NextSTable'];
            $proof['extend_feature_by_game']    = [];
            $proof['extend_feature_by_game2']   = $result_val['ExtendFeatureByGame2'];

            foreach( $result_val['udsOutputWinLine'] as $index => $outWinLine) 
            {
                $lineData = [];
                $lineData['line_extra_data']    = $outWinLine['LineExtraData'];
                $lineData['line_multiplier']    = $outWinLine['LineMultiplier'];
                $lineData['line_prize']         = $outWinLine['LinePrize'] /  $this->demon;
                $lineData['line_type']          = $outWinLine['LineType'];
                $lineData['symbol_id']          = $outWinLine['SymbolId'];
                $lineData['symbol_count']       = $outWinLine['SymbolCount'];
                $lineData['num_of_kind']        = $outWinLine['NumOfKind'];
                $lineData['win_line_no']        = $outWinLine['WinLineNo'];
                $lineData['win_position']       = $outWinLine['WinPosition'];
                array_push($proof['win_line_data'], $lineData);
            }
            if($slotEvent == 'freespin'){
                $log = $slotSettings->GetGameData($slotSettings->slotId . 'GameLog');
                $log['actionlist'][1]['amount']     = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon;
                $log['actionlist'][1]['eventtime']  = $currentTime;
                
                $log['detail']['wager']['order_time']   = $currentTime;
                $log['detail']['wager']['end_time']     = $currentTime;
                $log['detail']['wager']['total_win']    = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon;

                $proof['lock_position']         = $result_val['LockPos'];

                $sub_log = [];
                $sub_log['sub_no']              = $result_val['CurrentSpinTimes'];
                $sub_log['game_type']           = 50;
                $sub_log['rng']                 = $result_val['RngData'];
                $sub_log['multiple']            = $result_val['Multiple'];
                $sub_log['win']                 = $result_val['TotalWin'] /  $this->demon;
                $sub_log['win_line_count']      = $result_val['WinLineCount'];
                $sub_log['win_type']            = $result_val['WinType'];
                $sub_log['proof']               = $proof;
                array_push($log['detail']['wager']['sub'], $sub_log);
            }else{
                $log = [];
                $log['account']                 = $slotSettings->playerId;
                $log['parentacc']               = 'major_prod';
                $log['actionlist']              = [];
                $log['detail']                  = [];
                $bet_action = [];
                $bet_action['action']           = 'bet';
                $bet_action['amount']           = ($betline /  $this->demon) * $lines;
                $bet_action['eventtime']        = $currentTime;
                array_push($log['actionlist'], $bet_action);
                $win_action = [];
                $win_action['action']           = 'win';
                $win_action['amount']           = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon;
                $win_action['eventtime']        = $currentTime;
                array_push($log['actionlist'], $win_action);

                $wager= [];
                $wager['seq_no']                = 'GB' . $result_val['GamePlaySerialNumber'];
                $wager['order_time']            = $currentTime;
                $wager['end_time']              = $currentTime;
                $wager['user_id']               = $slotSettings->playerId;
                $wager['game_id']               = 'GB15';
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = ($betline /  $this->demon) * $lines;
                $wager['play_denom']            = 100;
                $wager['bet_multiple']          = $betline;
                $wager['rng']                   = $result_val['RngData'];
                $wager['multiple']              = $result_val['Multiple'];
                $wager['base_game_win']         = $result_val['TotalWin'] /  $this->demon;
                $wager['win_over_limit_lock']   = 0;
                $wager['game_type']             = 0;
                $wager['win_type']              = $result_val['WinType'];
                $wager['settle_type']           = 0;
                $wager['wager_type']            = 0;
                $wager['total_win']             = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon;
                $wager['win_line_count']        = $result_val['WinLineCount'];
                $wager['bet_tid']               =  'pro-bet-' . 'GB' . $result_val['GamePlaySerialNumber'];
                $wager['win_tid']               =  'pro-win-' . 'GB' . $result_val['GamePlaySerialNumber'];
                $wager['proof']                 = $proof;
                $wager['sub']                   = [];
                $wager['pick']                  = [];
                
                $log['detail']['wager']         = $wager;
            }
            $slotSettings->SetGameData($slotSettings->slotId . 'GameLog', $log);
            return $log;
        }
        public function getCurrentTime(){
            $date = new \DateTime(date('Y-m-d H:i:s'));
            $timezoneName = timezone_name_from_abbr("", -4*3600, false);
            $date->setTimezone(new \DateTimeZone($timezoneName));
            $time= $date->format(DATE_RFC3339_EXTENDED);
            return $time;
        }
        public function encryptMessage($param){
            $param = "~j~" . $param;
            return "~m~" . strlen($param) . "~m~" . $param;
        }
    }

}
