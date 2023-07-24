<?php 
namespace VanguardLTE\Games\XmasCQ9
{
    class Server
    {
        public $winLines = [];
        public function get($request, $game, $userId) // changed by game developer
        {
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
            $originalbet = 10;
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

                    $slotSettings->SetGameData($slotSettings->slotId. 'GameRounds',1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount',0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Multiple',1);
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
                                array_push($betButtons, $slotSettings->Bet[$k] + 0);
                            }
                            $result_val['DenomDefine'] = $denomDefine;
                            $result_val['BetButton'] = $betButtons;
                            $result_val['DefaultDenomIdx'] = 4;
                            $result_val['MaxBet'] = $slotSettings->Bet[count($slotSettings->Bet) - 1];
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 255000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = [1,0,15,0];
                            $result_val['ExtendFeatureByGame'] = null;
                            $result_val['ExtendFeatureByGame2'] = null;
                            $result_val['IsReelPayType'] = false;
                            $result_val['Cobrand'] = null;
                            $result_val['PlayerOrderURL'] = config('app.cq9history') . '/platform/?gametoken=' . auth()->user()->api_token;
                            $result_val['PromotionData'] = null;
                            $result_val['IsShowFreehand'] = false;
                            $result_val['IsAllowFreehand'] = false;
                            $result_val['FeedbackURL'] = null;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['FreeTicketList'] = null;
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 3;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["Jqbi5zNUGwYGaNv9JP06AKoy9kLM5CSD/z9P/wLB592SfFLwX7FBC8X85XdZojgYbFeEw2Je9BeuJNaYb/KneHdIJ59cYGlSeMPenemu1SgfgImrK3SmN1npZD4pzHKm14VItYkDyE55St4s+9IrCsutZ2Z53YdbkqHHv7DmqwaHi4atVaqZGQ9KpAKYxYBi4HTyKZJPixcyWUrNnuSRictisTfGBBysXmi/cD1vyEG5Y+X5MpQPidumhX/uT0CFKm3f7JHrDIX+pu5a9BrmdxzxecOr5LIz5aw9U5RLawLjOv2lC9xOmRF0SYsMnjC61UUkxQVBHcJnJktJ1jwXfZ3MUX16KQDuTbIgFutIOeFcAG+XMw6lE5Pa8SVkUR2NdpeaLcq32dHntESFOl4RoAutajMlu984HicxTP7dxo3L5cqtc7x/BEMeh5oXAw2MqhbPD0EGq0pawy5ko6SDuJiButo5gexBBgNCxo4nayl8tBYW2wPT4RtNSUmQQfS8DtwlN9VJDlOWQmcFS6Y/aAcJYyHPECcRzCPlFRSiXY2fVj/rmjB5iZhAYJM=",
                            "RYI81WFKDv1QMN2JDiZDHok5cPzneJUUqZbCU9TgomiGQYki7T+awb0Oj4UdSJzC8fWhFstVXGNhqQUQ5q4wtQXxnatgfPz7E4sWppKAhK0MPY9hXNxmtC8eMBoB81zahRgEkovcvD6lgeXonqZK3nm+E1Qgn0Ah65fyt8ipCx5U7WzLMqYaRjtm5JWe8masbvYhZPhEscqplb8NRG/Nm3tQWvFFLeNHZXdtx+/TaTC/hEDwWr+70uz6bymjZyx8RnELtIXzx4a9Pf0x/ZV72mhjZux398iNLg+t17aggDmXJUCqRUVzL8ocszZQnng4NAFlhGzl9ACwjphcwFPRmtE0jfzU4GqhkF3bnqTJvHWnQ7mghiGZ8bjwrzTkpOM1oDhC5RstOxrSK6KBppaQGNMSec5Vt1Q3b2N+VAk/rn0unTwGB2yyACKmfHnANcju1VDVITTVlfC7kGQc9LtAZlfHWq7eZmsMLli2t3R4g063GpPOYBGHlmZ06Qc=",
                            "TtQBcPRRYpewirZPEb/acg94UuBVXrx3W0Mthw84RX9izIMDFcg2dcH+YAmK57s34GzuX9CZQ3gzVQbgavJAoIfCOfZdOhMYHg1tMsI7Ua3BeGQunLZv6vf7GaqPbXCFsxgf3uIoRgZQvxP5CVxOAFbkMb1jBmBQ5BaRmnOLE/PjRTofshxiMUKSBJUfbH9TAFQjpIshZw9QSu5ws2rE+iSLGItWf9hLZ4OuZTkaHep1vvvSzzVFsKwufiQjYcE14a1szMsab5X8TelrSLyPl2y5CW858KuEmvGV7kVQXBn1RFRp5ij1M8vgv1eOAsAWYBG7oEP5V8x4Y6BwIdRwS4ztA5lA0OzGGNbbamurHJhn6bVqAMQXtvm+AAuITr8vt0zcN24U6yd9ONqZBdpWZ2+HvsTHEjOQgmMyrL2DZ46PQoIikpjinPnqLQ0=",
                            "ZnIELqlVGlv8HvgfBmDq432QN7caNOZVLsL72aJTvAnf9a3BVy2YBwkX9vIMITtD3pmtkMU7qmjZJUithIJuyXr/fUDq0TfY3G7Leo35IbW3P8PYn2WFIaZ2GPmYI5rWHlTdzJXwwVH1NSBRlNlsYZtWO3+knG++Gu/A9sleNy/RafVNOY2EeHIed0IBaxv0ETqxm8HH5fiBhsOMAvxXleCyfzyxshD767QfokyF0YJSWkbF4yFJQfmUeb0R0ytx/5U4IahHqcUpjYUQPHnhtgaxnqO2K1Rp/Q6VWXPGmUEDFKnAjqXonAw0/zU8qsIgWaUGnBs+Tq0OWsewFKHP5MS0xVs5rOszh2fHmzWIFq2ESCRg6NAqun8AssOdQOQ/JWJ8mJlxJ4TXNDq3Rusd7UoNMnfEdK3F3Stt5AXStvjfO+mJejaBB98iij0=",
                            "MxfpjAc48vLSeIUFueQ+cX9F2VCBSQFG9/PfCjP5liSRfF48y62QYWZteo8=","3VxiJZCv8kPrPQXpzKk5OzZYpFEeJmsL3wo6ISUHgHscpj+Fn5jTaVnnUfk="],
                            ["7NLL0qMA9msYX6LBJP8Q1YF3QitKkiQTCcCFMYfnYzrHNrx2J/VVdEseMZZdRMYOgtXF2bff80sEmUbl4hCbuoNHm8WrQe1pxs4tvtbyluypMudP9ZAs6iIogaHkvP85Uv3TyN3yzMK+fFLnPk8qoa5cXtqzeff3KBXfwDPhpx86j2OSQMDQW53GGs72j3BOmUF87I0Rxy0u5lbyMhAmMclrJJ+fRn2mkcn7RrCcMsU/bw1pImGR3qfJBQxBlBpv3wYBg7FksBJCrJ6pxOOZ5VHm+84q7zrFvNY2NS0DyHtGQheVSd5GLqPpIcKqFE3t3sC/RXUPBdUYSV84iRJ8v6yu2nzSmLSB+MeUdhoNcTWmmXSb8njAcAoDK+W/1U3pwN7CJUoU3Aqdbk1VB5JLczWI5LbhiitEuL+YK5J+df9TQGU+fOurMNw+zQVqZZLxayyrmPfUu1jB/GWA",
                            "bx6tdVJX9OAzGn7kW32R3XwZo2dIxy8Xh5FYa/N3vsr9nM34P62h17HJ9YyQLB1w6A888Y/Dk3U2FEdYyxRYXY6DQlMEIB8u6wS4oxB5xEE5p8yyzZCMY0doLHJMN6yXHSNHV4GhLKBtley+h4d0yXN9l+j60sOJ+W3UpNv43ZaWwG3KS9jo7lxYMwsMfonHJ4FNItEoLLTFXcgzdJbu4TosJylhyLxm1MgGb3wOquOlxCcSmyYjPE5PSyvgVc5EMAColiosP/erPoQxAMNEpX6pHXIzKYKXFItWfcAtjBSVjmz2KuCrtKksN11uJtnwtK7fakl7iUlBdLHg+OOTu3hUZDB+mFEonDVJ0vRshV2USrV7Jly75hxHwVOd2aDI/4AUp4ywupoeIz+ET/S3VgnmzBg/n3JTSftKeM9ayj/L2wixUIl6Hn0ZCOj7lyoLY5u3WhexR09zkjcG",
                            "MzynYEIbwmX0NXl8bo9582XhxgwZr4v0pXteHokdmKyx9lOF7cR/C+dqi4GPxJj7ERDMktJzrwE9SaftkQhdv7LGP2vIT8GE62DV9+FwMg0RjFegUKodEo4fW/Lu7thTkzH2rsA81icBRDfh9SF+Pn55X5+Xuh1soMxSLxk3wN4KHi9nZ5wB1N8pFQOq4c/B/I+0ntdhCbtnOUaqTI4lmPJdKqE6HrbZSZceLi9244PRb/ouTI+tyu7eW6VNWex7tng3GaeaJqaFzatyQ65Ix4/sKKueBzHRo/9XilJWqlE63Phvmj6hEdgFILr2PP8/rJwh436OSbrGlTCHLO25kNFx7eU0t8lUWSgzifa4bSSDHSTARbV7uneFANTe0uYLxpKtl5MVVFUCHBsfzXLb77sPhlSp4wm5Vs8AjRuXBS3fnDJiNPdAOrUds7CDGYaPv3lDKS8TTdEirqi5Dqd9KQ54hkshlF9ByAnJyw==",
                            "sU2F5uXzwKVEU0jPtP1K2SQJH1S92MQ3WsLJJSNSbuHgUmbiJMkuND2YZuLM2j/G+hLawv1G7TDv4X2iXk+JPDRF/BBsd98+yIAxJVstCU5sMpmzmazw1rNYQQCgJtePjTDtuICBjK4CqKskAOKklNkT9/0l3tTLdZv7SXjEymva42Za//XENzDGNx2oLv1PorUVKaykT02/msc6uT0ljnHtNqBGY0SErKzBwUw4CQhc/Ak0FvFPlZ2jByY4y5idfWPCmU3v4WND1E3T5x0BNyjji0T60Sl2Uv9zIKVrrIzwD+RDX5tGqGzJC9mj3DFs+jBEsBmwPhZBlor9aCgEMM41sHB+XX7zoN9t4N5h6oiRGH+msmvcWbyTCmYd7dS8kbA6VrVN1l/tdg/yRYqPCU0PRog7YuiPv8kgekcaGn6TPoPT3pXsH5DuvVEo9fdlOOfzQMhm6kvGRZ5/",
                            "qeYBEAM7Bos5DM4KYCPL4/6U+bUFFkqTIObVAyCOXj3D/nya113l1WllTmLfiRkCwlzmn4cP3wdPYZ0R2FfsHIG8xsr/GXgpWRxZlYZCYWCLQ6ix8RaZs5u+7MzC/NVViDJw4z/VrLj5RqQ7FuDB/V8tXwANZGzYLKd2Ae2Wn+Pg7+78Q9KUTGLl5TVoRM7Hg+Qo+DdTjSjKCzUA3vBvxIZv1rJdY6svlYdi//M2zAkwmkO7+Jq23gzdtkJG0vNix3rfAOGt3FhV0XpQwdNEjoALoCZe+l0azC3RDgnjVZ0kDFyvbShPE7lpuFogXry8shem36IuBX5AVHj99LqmaywbO8Rq4LQJFInFtMgnm2UE6sFd+HupVkhQk/Fk0jKLAxp+bo05sX9icJKiT6lvglckpqxTtbsjmbtjkrDElB8ZcqkRogh2pp5eufCoM9qYrwVGBTyRDUdl7mEi",
                            "2KrPGk5t2cYeLRWdJnct4rqIuGl/lwcZbW9Vx5+Manpl75T+UnPiUJ1cUr0="],
                            ["yJ4qrSzROqxj8b8NtBG03Q6fQ+YYbixqOd0Px7eUGTQDtTZrp5KSLQQXYc/pjy7d8Y6R/YS9KmO/PJ7MdvEPLHD9Ws5ZrCr26mkKg6NoN8n5KzjE8m13O6qZ87YTECWY68EDbhlhNYYGP+sv4QBjP/3eFVMNZAvkdf7hv88gHNUkJ+eCwI7pDScZ1bQyUJ0Z25xN21VM+eEXxiWvwyP6duOpqLet1nAEqNSD2fqdVN70JZalAu7BEtbvtFG54eV3xBujG2IQc5zCVsNwLIQaZmaK7CjDdbVAqmzN6Tj1BIUa7YgC549vwHrtnnM=",
                            "25CqAM1Dsg7KYOofyV6q+XFGtAZUa+sXVMgTzRFAYj7igG8G/25JAkCsV9K6gxFx0O48RTZ3vUgV5uj58QkNQZO2rNdmgq03uQ9T80G6d+hYp7ZlOILCu5rTbd4KYQAJvD8T9+15q+Z73oi+PqEBh/V8gcYywriW+cnS4jjKOCKsVcFbW1Uc+KWwl2Ll2rHLeDq9t8QAiV6PyRo0D2J28ShZsI3LUPKs2Qt/0g8RpZwj93TW/mWh2pxG6Cr0Fzp+mttAGtntiAcquQnC6R+prhRtLGYLX+JJLrGk71OAdu7Wxhzn1K9p/BCcn1GFe4GxOc9ARdE4Ih6zaKU595VJbFoMxRE9lSA9YGTqCg==",
                            "RZdI7KKfaoxZ5vQsTr/eJOrCXs7xhgZQyosnWsXlcRjFOHwXMb/11bUvAqZ5+/0Zy9y3+QPLPSRMqlIggwiTrEWvswpFKfASRAZxMdcsSqZoNSyO9ekRwPQUgYpOGt3NgHUEd/3tBPjSrR3O0ktcqZ04V/6a0yAccmLq9e9+bVPxfKpniYZ3Kp3pmEaPLl9++nUL+w+0iITQvzNgz2iXhPgSypoO+It04igAAa/YjAvd36dVBcUbFtMdgHFyVKUU4xh932pUiVcuJZpDgfe8l9iSa9S2l6hR0TutBbcGUq72J/tl+Ii84ESMpeulSm2yjIQFKNUL9O1phja1",
                            "Bgmh5xtvhDx9rcwSisjXJrXgmLYa5K+R9DSxWW7OlOxs1eTZKHWxL6QZdnoxsUwN4mGtwXjW6hMN8YCbL38KdIBM1jJQeeGRYaI28mtEBiV1Lia2SvhHpWh3f+D2kJjQnE73f3wTGJacRqtBP9zUpxI7zrwx2OUV6ImLTUGRQIznzLrVkTYVxeXrHSjjf/Ip77ys1dSaD1JUYo0G3KBvNHNHJG5g6LC4yFoJxWMdPEV1WDltunhF9JejXorDxcqYjW53UW9a9SIehfRMj/iwWSPTn1Nsds+1kOptHbWVQNNUAR/ASXHzokhmwDwY3cdOzWpNJ1VLOEmCxkCxqM8Mz1OHQ6xiwFE0oP2QQw==",
                            "6OPq5pEYMOUtSl8t+YPq8egvbY8RdtMO2PRNOH5hpYh3hUcjX95zzE4B3XzCPqjPyt27yL8BY6ZQyC7lhW02t5HHYw9yrSWtS/X8ljkXYN1/cRUihzOc0RbLxM+syCqqYfXEHppNzRnhQEiTQU9AyARPkpV2RhRyCOR781anqhJ3lVtaewM7tGGsBrwQR4LmHWIoXhXcgX8xidEFtp2wX77735tFJYIlEofbcW0UcqBqQisZ4OS53IXpiewAPttYo6clyfki10sYGAhTUEDkhVuGCFNUUUj4AEshtKHXrit8PrBpthkUSEHsF0v45WsiBsL2dzLemNQYQ/WxBySM3lMsG2wNfDlA2jOMvw==",
                            "hhuJfxqST6Uyr3JBA+VN3lIbz1lHu2Wks4qjMYMLaW9lNQ8uCz5KCCIWlsUrar/WPMdVX27L/kCt1h31+9QK2+ba+/Re8q47sSgI1CYHkp+E7hTZcTkWWGAw4uVbXFuvZ997OtrwVeK6NO+JUMXA/iyE+TnmRSJiLPTOOjrWRAVvTLBVglvcHGCHrAch+BWdu3U40RjTR0NpZWD2udDEUpNnddVgnpQuIPhD3d9Aluu9VrsEAmGWLQqvWnUtwiC25SvZA77Ze9QUd89EPGBFDvDeACR+CaD0CT1Qfz4FexD4mGnutCoVZB8FrXAkIVi9IwTbb1NHhaCwacVCI1F7JCULv6bI/7NM3Hy4iA=="]];
                            $result_val['FGStripCount'] = 3;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["F2t83e1TkxZSIZeRucc5e01fwVAgfCm0ZKJOf1QUHgemhet3vi66aDuCv7V1023D2LUbNky1bigchUOFiGGSr1ZW0PvzGNivZ6O10AWOUf+fJVCtH0wVpVXL25MwEjuYkTvpTV4oFh90JfSiT7ZmP5AjozfbjW+DZT1oxU+8Dfc9bC/+bPs5JOQX+p4v86YCOYL8uyEJWm+sv+0LNedR7CfVYqDimqlvCz8+TH+wp14wZlEjlZKlwNegS6Zv9WSn8/9UoO603uLHT/tKUmA6bi65hZCoMwlH2jLpT2jBZ4ZcM6kKhN3lTnIRG9ue/GucpFoXMbtw2+4EdLRC5VxGjrkgqSbHAwarnjsnF3WwqXMMNOXvqpELFW+gZD96nzec4fh/saSNm3rjpvmLTwXEaqLaax8q2QbxISrW9yXWyLSRE4Hfa1Rq0AksuxMSYk9vC/jkARKtY5WbUxFSU8Oi/e5zpTdBIVhTJHSQbecN3IpSWXpENxdZRBPJSSdO7qfOgPZ6Jbk5qNH/jbpD1x8kOX4afhhHDwUWY2ixLRERpuCsQb2vg8sc1npk76oUrqazMnpH9RhKsO1TeLJq+na//6pY3UoE5+5DW37OhhYmdjr9zMUE/8n3TWKBwtDUDmatMXZ0UH6hne7xgJI30Ku5RKpb1WvobEFP8P7ULg==",
                            "vEIUwLUQHrXJJXrGqjGmtge/AubkwZO5Fq8c1V05ZoUvqaSh+yUYCQzqtAq0kr0hCjR5oxjnlcLMnflHePx4iXCzPgpxLIGDKFlxd5053W3PvIX2X+q56bkIVSvrsxGH5tzvpaLV6NolQScck/kEw/+0MXi8Lp0vt1luwjk2L+8IBz14nuk5Qp2ix4ZJl8FXPOLhPvs4zoqZ7FeR1XrZCDpjICyAbntP412CW8L6Yh+OtJoBwson8EjR14cj/hK6ScuGhoKRC4UPfabM20QYnWsOZl78w1AsiZvpvu78xksYf/ViUOqBN1WEzrMFFXeaaTv8HwcTXztxVUOghv18l81Jj6lZTSDtl54va7RPQ/PYQWy+gg1vWb6fgcfNE1zlGhlvM4o44NfVJcdZp2TMAvIuJASAZe3SBc3eaq2b10Scu/mQMUgQsr8Cj1aedu9bIs8c2g8FWA3zJIEoQx88xB/DdmE4YASgcXSM2EZDS7I/rzCAH/gocIKTeF7+npZYnaIO8PANgDsveUsR/8iJBLmJDQ+qqIe/j9HNr/nJtjMUTyOEs6XacAk6cmrQTY97RgxYMZB19s72dBY2C3odbJtphUmbSzHIybR+GbPL73eNLZfk3KNLXDCnngwtP6+spxNUM/is3niNdLTBRBsx8cFKWBNaZo/SXZb9Cw==",
                            "xXyP9DZUEC51F1Yq4T1ToLkTgRfI96VcXDB2hZKW86rsVsr7teytdt91QW6FW/TXG659dZH2ej/lhqurdP2p95g3jELlDcD/zn7ismapGsEahz9UrsJ16n5X7z37/nE3BuCxSPGqYvCj8PNBIV7Eo7NW8Rb7KMXz+8G4Gcu3YiB6tUKt4qFmtR4EJSWtk8rezJpCgQNnwx23t04odlTSYL9GQwQX6q9bGdkVT/DffR4vL/JjYpW4GhhAde1eXNoAw1wwXveXCIIurFOY+rKGr9zHYO0uWIc3fJe9x4KSArfz/qWOdFLluaa01nQZ1hRedE1Pei7rM3m5j1FaMYbo6sYrxKzj2EROZB22P2d73wTNdZrGESktVgD181DQSUzWPJjLjI7yvreKev8KZVExR5T4whWZDgVr4pmwhidXE2gIsWdRBYtU49zQ8kaHZNpTFzVt7bBMmPGL4+szAiyqf/2KYMCkNtQrVJZR1i1d0XJCNuCf9cfcZLhdQ15oUvHiDWkECOkSxOMTKLyiixTVFnR6QttHWQo9a53VHgWkZbnWniMCCFQuG2RpUgVafepKnhE/8BPgVs4aKmkMgisaE7jDoN8E5CafthdEGnsjdGSil7aNI//qoSUAdXq0I9pEckvsYC389/EvWRkI8l1vD1JEYU6VzNdUOR9NlQ==",
                            "eaa5gKS9mrat8qhF2PTbpQTcYCAK5jaHo4Wt5ZUz7iiFjPSZuqa6/XZFsUDpAi3gpuVPGCfcNuMfgjCEXyw8uqoIHJxqwEvjYOa24DJLeHM3EUUSSmOU44swNpGkgajtULZzHRTyukWLvwwCFm/BNNWcdw9/hPIcdRfB1lMmpPH90id2lOQA2zcxAVcUuor4u7NFigw+21so/YionIJDpXSgyuE5FWyZpyl8os8QYGGYQXwJj+TOs02XjpIretjgOvu3v16Y1PNrBdv9bfDs1UXJ3PwixwaoY2uK2y84zuLj55DxXfElToV4eXrf+W7XpLEuxyOPDah5rdcsCl56AcFuQrbKjoSo9QPsfQV27x4H3bWWMlyNIrD53IImlG/eYTVfCSqgbvo8EiT4XY/WDScJxQuZG10r2bYrcc/6IGCTP5EvNlMLpgZDkNeavhP/NlMGg+tpYp9cy7TDtRGsdmAz0VJHPvY/6lT8PgD1/82FVcgNAS8uxTwsGzg0ujNvCbjg/k3Ftp8Mum5QFajKY5ZFUMdwTzWY3iglpdyuWkeVC+mrTAUaDqY+OKUUZ57KLTqiCu3DN6gvKgAlEdXl3onqqZ6D2p4wT3PYxHQdpkaw7cQ6O9Ynujd+z5ebqx7Sj55YapRk1n6CLI9UysMQWyWejxthJKqR7b50Xw==",
                            "mvTyTusQjuzgFmfM5qKQbOsZR3N9wjlsTBEd9O8ANxYFUebwYl2SXn++Sdw=","MsKa5Kl4iUtetnek9qrHi5YoaqCv8ouB+Vkp1O/L8uuBD33n9sRaBYr+Rvg="],
                            ["qTHfN6gioELnW8YSpsts4MNr3xjeAfh0ECUEcfFOigDb8OOzLpUG+omcWEb942EqGDRRaZ//I195Ffc/kriL1I8ibWGhqWysm/oRGwI/Wn8TLawQy8PCcoKxAHtN5ulEJvmeyVUFD7ebHfDPtPHNO+M/HHKUS/iX/Pb7OzuQIMmzNcZ9I1u5MABh3RXHbgVac6syxvkEdcQDDp5TDTqChxPjxIYsz/AA5tkbn2F7oEfBUdZtzzrJdtqZegxyidAw5R2j9HjJSMJS94puPMjgqeyTAiS1sxW+MDmuELolDCm4CNUer5mV23kql6/m3QvqHBChkq7mE/iefjy4LquiPqlNca4M+5FeIFahI5oVJ6oHOreTFZ+tjBIoJwuIZSL0vuC5jlU6LIOSTCAPXAq1/SpfEz24T3t8FfdMC1nemjhECSewPJf9vcCH00YtAp5jObyXzpAoq677cc0SaFehfQIO1fQBIhZYjg7ykmrl+sISsfxesXzXNzBdiv/KN450yV9O56avJnLYszn3HP39KYku+S+isne4R0CbPJnBzqcQl95Wf5c/hz7cAjHugWhvdcY3iiDr9xN37caXYI0F8KQYhfWj2uugbW/afcn0bhkBTxWRGY+MR5wjL2BgznvxkETMRO+SbNpOdrQRo1QlezI3Y8nyII/k6yo1zg==",
                            "OF1qTEotJRajcOOZa7fsx6dmMo8iD7teqHvDAO3p9jMtjAXnypS1bYWB79lAQBHQ+nuAwJIVg8cA1ECdCjL226upjxkockIZDzg3remTYsZxj14WE/JGGuS3sC3pRFn7zXBfWLcbl7oQKp3lRHCu01V11Sa+V3JAu9A3pkH66xanjy1OWC0/jLrSEeroagK0EsbYkx9Xh2dvDrcrmm37EudwYr17yMYTjiJFTyLgAyf6STTHMKYuYNRg/gv3qS/qSEAE58JV5xFWU6WNEzUCMW4yu5EERY4I/5e+GAAcVLR2VqU56QaCMttnzAjB4W56OG7nw5g1sqAzaJ47NkScPaAgMjAnydZANTjZ5DsAkm/f1dVvD8EWOBOHbgsX1b9FXhqenB2R5IJxNqzbhkNcTaasZzSinSUOD9VfSEkAIL88MP0Fy59RYmrB/jKQXTMruPE/8cVYs+MQuHiMpw+/sA8gYbeV4DUTT9QzmXveOExsBked+pBkN8JSMso8E7Kjq2TlAhjYzFBPsea9mdY2ijxrYLeDmMjnvRJs44Q784S6A4SHtVLfNaLL8EJEgs1JBjT5ptHvX6bkpKVH5YdtQRvCSaGMy223fyhVPEThqS/A6X1YwPLeHHgEzaKgDMbFmmT7arCSBMYBFNhGCyEXwLhePVPsyZ/7Fvuqyg==",
                            "RcPNBWhDqqPRrcXqwbpcoQhy6MzmYQ+z/lWn4zLeeZgu/DjxbJWVuL6+qKGmGt77Le2oAC+9VEwU9XJrwlynIpgksU2IUBDiSDBYVF15dCn+sPMxizaC2Xs2AGnyJvM7Io/2zFkbf/3iXlmUW2jDOaPlLebp5a71q1XJTx0HsktOD5twGUUqqxzL/qBuUuBZkME5UB0YY6BkxAEPIRiLmeD4LmJdqzwcrfUitVtr0XeP+K2XucAZsVKBnOZ9wdT8R6/MvJbDYkt+ZWy0mec34hARjMCcHX0UOYNRLmttXy6Vixhgozw/feae5zp/EU02Bry0jXVf0AOd8JjmhQIT5nvSalmJulSJ9eWgwzCjNurtJ94GzF2EfPmHH1QYTjbtCKKA9nK5M4ou1TIoAxwatyE/x7OQ+8P1GHRyUujRGv15o7y/cqm1wU6dr9UBCzD3EWKciS23A5oOact/E4kNpaBD89dKoQBYgXd8hkSuXImXGO/JcNfkoqso/B78f4xdeuCVWoGMxrwdkq/U2rSsxYpA8Lm5oKPInrTmvSbPdi/44VLLivCCfWnD3U5LRWZptXnm3hB2fDZ8Ezp4iQsSPJft2nJHJuTX0uEa9OPnnWzDk5OGDKtT7yAAJtZq70S4XCepN3uStrYzJDrqmlyGF2iIAkRjdYEDgm2rjA==",
                            "LlLLyulk3SIh1LkE7CBNIVTOMUPeGb/60Scc5e4FnNrp372XLKdcwq2q+1nv/zHulcIWK8YfZgKzRQ21H2yGXyS3YEWv+Gw1cDa3JfH4vnI0S83L2Y3uFXgJxzROwiJY3vfIaqqlTx6OGEPQRMxCo9mvTbkosgaFcofpVuC9Qjqi01Y/KR+SqByjJlfEF9KFKX8sBxB22J6llWvFwGurTJXrUbbbWlzUdKthvhiMwPP2ZDEOAYo/T+4vj3I5JKL+hZvjYdyMvFNOJ+EQH/HxwEdpiLntnlU3CpJkddP129Cp6Wxj2J7MpAl6zbMok+/swAXnizKXCuGCzWJPnBrIMdnz1+kWj+1di9z88FKCbDfP9Pzz8KLR5xOg2OY1CjWTnVS0MO8R0IjCSvfIJ/l9fYf+WRE5KT8+UNLEc9KMGmULCLWzpmVyHKSpH64SRamDRyyK93YzRTYbP+kOFJRxaPO3CcXGL5ku/GUSWue1RYj3FDv6Gz2wtQVM741A8YkjplOk60AB41sSVb0PijPC/wVy7n65ZHtoBXWO/PQR9RPjdGCSkGzLp6nXiRAOAODRpoTi5gWYIcHqvDe/6U3fq2X1TILo0kXpgrhnbJZRNavE+ED2SmC1ZV8NtaRP4+BRpFfKs2QP2VRXsNl+FyUS/zhHyxemA9kamVxXxQ==",
                            "zS4f2Ac8tMjvRuP6igMv65q6N1L61GudCOlgIJ2kRmASNLugRUw7yohSL6vlqlwf7/rnzXb78P5/u0ZJ8orRyrvqDa8MihG9UaSemZoUo64sflUzwKdQpWIHRsHBe8G09k0Gzx5h3TkCyT91Jf32OzxYhRi20tM1jzENqCsqQWcQw7j5lRDdxyA+E+cDWet4HBYKH146wR1bqouEE27F27HrQjPtZI5c/euaRO2gukoAj7B+4/IyZpRh4Guonr39xv9dNm51kM5ARY/laWP9ahSFDG28vPFKRh8JlDJ53lYgilY8AhEqlVcYFMrd8dRFL2Aya3lt15iRm7ADmvkINF77Iat60WKdJtu9VlX7fV42FgmHKj3/o6A1xnrSIsxnC1gQbliGi/UVuA/6iW2E3HhVD/z5Gwfus7sCpREPOV54MOMucGhHZ/Fff/62TDsOwzEYvbft4WE6ANLa0FCKYQBkzT2Zr73DVqckF2Sd4MGql/+mzNnz+eIkBEDccz8bmHZuo/Bz25M3/7flhLD1hC4RZPJQNWdcvhUGuFFWC48Afsa9F8iuq4K27Dpc7S+0lrpxU2jA8D/YWFI4+Hz3pIsIgKdXXf/rxFExBVmQ60bdtX6a5JESvQ17w9aJnbq5fVmcuU1pRYSdgQR7Dne7ARvIaUxSWDQBnS7UcA==",
                            "hVfDpEbVHW6Z9kCLNEZf9epxeOJ30ZH8r8zbZnnmzOJjLc1kAtzm6DXRMlQ="],
                            ["k2f6rbgjrzCf6ncUv+IpN1+GgzxQ6TYZdYy5ASJa5KS/Jb11RTDJegDESZ6LqNGi61wXOihCDiGKpzO0ZwvdfSLMFxQY20Xi8NiBhnzFoYpILI1KzN1oirgw2pnUxY+A4r5YTBbCEfQ78N9u9Khf3CxqU982Jdk2/0pPq9weebF/gV8tGv9JqwZduYZpuGaBJnLcJ/r24ZYOvYOULf6u9ThoZgrM8NRFc7xITZa4/8BQ3F5nRPw+tmdbDk/7GY0OxCVppjGwNnTeu33TNqhV0lPCy+Ajv+SBOF81ZjagUPyD60RVL6Ot5eUkYQqZWRB9ekovzD9putFDDtruj1O9ulHIECoV+4k3A+AKoNbvLDDfyGSEQHrI7S4RhDd4SQzdfTW9xglD23Zh/KX9AbmKBjElvMCCETCaKzTWdGS+YAAEqGlAlCuMQqdsPx592mbz8w+q18sJ4AUm5VDLgXT+9nhNx5jY8ZP6vHgX5IfvgMTlnxoQtzRP3Ygn0Pk51rvW5+2ClaU7h2IwfGKs+x1Q50NgxuaWnkZ0Xr2mtqEZregp9SXzk99iUz+zIkcH9e28n17Y9RX7t2U8JI0ypADgNeUcgLALH15YKZAaAQ8pa1ojqkqvEClUD/QWzIrg/ThP/Bre6izaDrRCqVkCeUQcbQqNv2KSiXu6AFVtYA==",
                            "KzfRgjxnFJFFTLRfzE9zMCTtoCRoBen4f6ThLKG2d+oJ921CnUg9Bq1/wjZId8YZ17dXzFl57SRKW3m5rYiR6oBaPbSgixfmpwVsAR1Scru6VYRG7cEKNsTWtmc6Y/XVhiCssrWRW/ShovozuR960yp6H1FavB8VNGmndur8o3CqwoBhh16apU9MX54RIPw5nt8gKPB/cQH7TLaA4JiTgQRwMwpnb62S03UjiVPz++wMwH6CIqBFI1tCz//vcwRlPZDpXtNQa51b71vonRGB9OSJrX5BpabpRTeUsWGYUgmDEuIwr5Cq/YWrJvZJFwksivQ7o035K50W4LSDG2+SZh7+o1kMiLXYSZBk66y8UiPlIqcuUrC3yOhL6sCxtMPmhAEcgoE1CaQykxGmcQitwzz7MUnHkuZ4w1JXDCbrBgHJhWWEud8VvwZeOMPR/+RsFi/txAoN9jWNmWfKDr9k2nlvuDSFA4yUM/YwzB94Fv+qbPdvZj5PUteKqRVYwGq3QzkpiaWwZ3t+kNA6mGd1kx6Iq9HRfcBFv3ToK6lHNbrpSh993QJybx8zE3fGDy/V5ibD1X9/AZsyqz35RVwwOVBKueWkdP3Bfy/o2fCdkov8feAqxM32qRm9Fv7YUtDl97a/OKwgIv/dwBdKU+G8wHkbtHIG5YShO21bAg==",
                            "TdRcAi8DBWKmkJvpsalZhDLpb+Q+zQn1hY0/XdCDhqAV9FWzJUOQs6VP1ChY57xihSd0ojvRcB0ECBk2CHmTs7Y5yJ48OXYFOV1TN1HSX7PCaMwBGHEPTAEAzYblhIYxW39TviXUksRDdncL11MnGwM+/7vFin613Lh4p6LP5QokL7y5rQ36ORuWuQiO4SopQI71aFNtyFMdHsRc4NI6WJVOvfRXI3Lx0dfGwYLQukL3WLWRrdmczk9pewLiXpQG0xOvChD0xygF1hn3JCUmOheEV5apbz6GLVwKbfwxQrUQuvkP91uz0JwDJI3+IDqkH2eCpHXRpOeWkrTETRGF1pXr1psGKGhfefG6JJloK8K/ALMMtREEydkBUXz8lZJ9yzcJVrn6GJgCTfpSCKio8619WzlkQsjF1TrBzheQM/n+SSe9hu+404N+BlPuTtfSdqbddv9rkN9aNiY+o9VjyAcDEWeeCPX4cBannlNjj/49UV/D+3dYNUFYC+87sxfpRg7jsY0ivEUodV7mIKxYGSoFyZn/qWrEGwOxkC4Wa8DcQDHLHCk0e6Ph8NWzTFKPagSvSjSVuyNfQHbOPWXHxvqInmczbxQynooQSMZTx0N74L6ChgmOxOZ9aQJZ98lFQE4ROUHAWi5zqHmgUiLAMy+zB/OEBrX7iyI2Lw==",
                            "5ka2g7lMHv1AzzciGZy5+0ERF26/oVhgRvwJ2ENHqHgorl+rGgCZyOqljFsnxHG06dwmZL3WJtLkBkAve9o7oryzL7yF1ZiTBQi5SPmqUNPaYdnGY2vJ80kJD5GfLEVnr0HTFBoPbR9G8P1aiIqEtdjTvWycj0PZeV44TgW6UpL+i0Hvq4aVRTzvOE9WTAkGqwE9K4AEWfOhHToTFRwiGdaSd+ZCivO3aU+spJviLis1SZWdz9aVzQw+iSwG6CH9jaQpKieo2nm+f+BGa81y1zNpHsDeulAd8rmYp35zOAK3Bz++O4dDJVza9bMdg8iY2UdDxEW2S5jNr5EbiejOTSuiMdEfCiWWJAFz+DrnlC7KT9E+/lcbjrQiZE9vKug0HyFTsHoZTzeGNYwqjpFm1aF/AtvzH/SU1R9XsufgVC5a5qIz1/o7krqwX3hrDXS1tdkB6jBZL2c5TDgq0K+9OflQkVuX8bxJBK8k9lkRDgslLTz4IdplJyK76/bfoXfuB42tysiugNifh6oKnYXrzMyGcId87CwWC+rwW4rK5LSrzfEyWgZuB5hU1HCV/pQakU4rS4fMajnEKkEfY32+5jCnVSYi7Ut+Y8QLk+WkYGJ//QTv2hbmRP0mTiJyA0uNHxU75s2WvBFGMIp4tnjHB9tk443Wb0M5fAHv0A==",
                            "6KW1IrbyYywLSpUm94gdGBWJHMWEtsgPQOlLYX7KdhO//WOwSpDKWYmUnGp62VYZH7AQpxTcod4TrlbbbFjxC7J3vttlK9T3ovjqRSGkGTD14P4QiOxZS39pwu+sJDZZrW31G6TD0k0m280SXy921ZjNSapXrU16zSSCRwEgcnIk/42NB9RGsuCBUcrbfnxaZlli73iV/o1nMO4RcBYLmcGzGZzKHuP4Eek/sC+2lGMMod9iiMFaqmdlm1AiXyT5WA0+ynGhq3iR+HrX/ml5CCZBSttLCd0Mq34k2kXTVSl2WVCnz3xiTnNRuZj/JhREOlyML+1OKM02dKfBkW8bVpgWR2Lj56C1Px41K+4fjA+AQEaxAoPx+47gpgNpBAXQxO5H46qTmL9WcyqCcuQYHbgAKRJ8XZao2STqRV7jrl+p2xjrU8u+c6X3AnTajEJ0/yhXeqV7r5xQ8PpzuPw5S3y4SqqCrRZX6tsohTcL+Xo94v1yiOPaAb6aa1Um+wt79WbX/6NYkmv/EZEbfp1pFz3G+uxuGsocZyIqnqIgNPX0l0nv7IPXQoR9zzju1wrgSBfvVEgdR98koK7CQqUh1JbYiw2vn7MnJrTe7ERwWYy1vgqlrjUyntmM2zn/1tlPSDMzfi2BBjktU7nTG1ZrHZcq+vk3uT3ZE1r7jA==",
                            "7e2V8sviIV6AX3ETdcQDSUtWWJl62XHme+oz6XtOguXYMYUK7dYU/HtliXF6NNJKQX6UqT/vg6iEqSrLBOUJJkuhZTSPsD3AYUWvIFqBIIll/cKQjqE4cN98H1mVh6DnRsJCpTWuI0TdqQUNQihRensTPK9yXsZeaq6Vw65E3Zxn4t+qzkFS2dq9PjrIiJyvQPkpy3FPtSnK1ATk+nC+mI1jTZSu+rTO2tM8auxncXB/1ACest9zCd5tMiXdorNZTxU6VzOq90fyAX/iuEs2svNFQVJ5WLS5IrvAcNdK/4ORxz+2M78k1fvUlnXzZbj0ch4/kNsE40E7WGKpeT0Ddc+DDKN2RUrCi9fh4gB8mTogyj7Y2vwETCFxwAm+Vu+bXPC/lfNxWkNX/cXeYDydJxbHBXDF//oD7qnidWqyItfbPDKwhwug0T3wcVWaHj3v9afeUIshTUWCY++KS6OoZm1mJtMUZyULN0mWYrcJXKFruSuam7nYXr3ZMUm7hIlaVRVtFBE9YSjaE0NRvYPZHf6kzKHtlVEAvnUiRkv8wsp6Qncsc9Bo6N4Zq9OtKmFRqpqyAmXpsOfm3IVGwx42L0HNQCFnfC24YUDNbjgqu6Gf54zZi9/+xHizZLpHW3Fidp1FPh/AuN5+ctDcE5m6+ch4PD/2ojbLXtz30Q=="]];
                        }else if($packet_id == 31 || $packet_id == 42 || $packet_id == 33){
                            $lines = 10;
                            if($packet_id == 31 || $packet_id == 33){
                                $betline = $gameData->PlayBet;// * $gameData->MiniBet;
                            }else if($packet_id == 42){
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            }
                            if($packet_id == 42 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                                $slotEvent['slotEvent'] = 'freespin';
                                //$slotSettings->SetGameData($slotSettings->slotId. 'GameRounds',$slotSettings->GetGameData($slotSettings->slotId . 'GameRounds'));
                                //$slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount',$slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount'));
                            }else if($packet_id == 33 && $slotSettings->GetGameData($slotSettings->slotId . 'Respin') > 0){
                                $slotEvent['slotEvent'] = 'respin';
                            }else{
                                if($packet_id == 31){
                                    $slotEvent['slotEvent'] = 'bet';
                                    $slotSettings->SetGameData($slotSettings->slotId. 'GameRounds',$slotSettings->GetGameData($slotSettings->slotId . 'GameRounds'));
                                    $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount',$slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount'));
                                }
                                
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks  릴배치표 저장
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', $betline);
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lines);
                                $slotSettings->SetBet();                                
                                $slotSettings->SetBalance(-1 * ($betline * $lines), $slotEvent['slotEvent']);                            
                                $_sum = ($betline * $lines) / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '565' . substr($roundstr, 3, 9);
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
                                //$result_val['Multiple'] = 1;
                                $result_val['Multiple'] = $slotSettings->GetGameData($slotSettings->slotId . 'Multiple');
                                $result_val['GameExtraData'] = "";
                            }else{
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                if($packet_id == 32){
                                    if($slotSettings->GetGameData($slotSettings->slotId . 'NextRoundAction') == 1){
                                        $slotSettings->SetGameData($slotSettings->slotId . 'GameRounds', $slotSettings->GetGameData($slotSettings->slotId . 'GameRounds') + 1);
                                        $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', $slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount') - 15);
                                        if($slotSettings->GetGameData($slotSettings->slotId . 'GameRounds') > 3){
                                            $slotSettings->SetGameData($slotSettings->slotId . 'GameRounds', 1);
                                            $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 0);
                                        }
                                        
                                        //$stack['ExtraData'] = [$slotSettings->GetGameData($slotSettings->slotId . 'GameRounds'),$slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount'),15,0];
                                        $nextRoundAction = false;
                                        $slotSettings->SetGameData($slotSettings->slotId . 'NextRoundAction', 0);
                                    }
                                }
                                
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
                    if(($packet_id == 32 || $packet_id == 31 || $packet_id == 33) && $type == 3){
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
                        $lines = 10;
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
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, $betline * $lines, $lines);
            $winType = $_spinSettings[0];
            $_winAvaliableMoney = $_spinSettings[1];
            // $winType = 'win';
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);

            if($slotEvent == 'freespin' || $slotEvent == 'respin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
            }else{
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, $betline * $lines, $slotSettings->GetGameData($slotSettings->slotId. 'GameRounds'));
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
            // $slotSettings->SetGameData($slotSettings->slotId . 'GameRounds', 2);
            // $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 13);
            $slotSettings->SetGameData($slotSettings->slotId . 'Multiple',$stack['Multiple']);
            $newExtraSymbolCount = 0;            
            $symbolWinCount  = count($stack['udsOutputWinLine']);
            if(isset($stack['udsOutputWinLine']) && $symbolWinCount > 0){
                foreach($stack['udsOutputWinLine'] as $value){
                    if($value['SymbolId'] != "F" && $value['LinePrize'] == 0){
                        $newExtraSymbolCount += $value['SymbolCount'];
                    }
                }
            }
            
            if($newExtraSymbolCount > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', $slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount') + $newExtraSymbolCount);
            }
            $stack['ExtraData'] = [$slotSettings->GetGameData($slotSettings->slotId . 'GameRounds'),$slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount'),15,0];
            if($slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount') >= 15){   
                if($slotSettings->GetGameData($slotSettings->slotId . 'GameRounds') + 1 > 3){
                    $stack['ExtraData'][3] = 1;
                }else{
                    $stack['ExtraData'][3] = $slotSettings->GetGameData($slotSettings->slotId . 'GameRounds') + 1;
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'NextRoundAction', 1);
            }else{
                $stack['ExtraData'][3] = 0;
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
            $newRespin = false;
            if($stack['IsRespin'] == true){
                $newRespin = true;
                $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 1);
            }else{
                $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
            }


            $stack['Type'] = $result_val['Type'];
            $stack['ID'] = $result_val['ID'];
            $stack['Version'] = $result_val['Version'];
            $stack['ErrorCode'] = $result_val['ErrorCode'];
            $result_val = $stack;
            
            if($totalWin > 0){
                $slotSettings->SetBalance($totalWin);
                $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
            }

            //$result_val['Multiple'] = 0;
            if($freespinNum > 0)
            {
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
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes && $newRespin == false){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
                }
            }else if($newRespin == true){
                $isState = false;
            }


            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
            if($isState == true){
                $slotSettings->SaveLogReport(json_encode($gamelog), $betline * $lines, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
            }
            

            if($slotEvent != 'freespin' && $freespinNum > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
            }
            return $result_val;
        }
        public function parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines){
            $currentTime = $this->getCurrentTime();
            $proof = [];
            $proof['bonus_type']                = $result_val['BonusType'];
            $proof['denom_multiple']            = 100;
            $proof['extend_feature_by_game']   = [];
            foreach($result_val['ExtendFeatureByGame'] as $item){
                $newItem = [];
                $newItem['name'] = $item['Name'];
                if(isset($item['Value'])){
                    $newItem['value'] = $item['Value'];
                }
                $proof['extend_feature_by_game'][] = $newItem;
            }
            $proof['extend_feature_by_game2']   = [];
            $proof['extra_data']                = $result_val['ExtraData'];
            if($proof['extra_data'][0] == 1){
                $proof['extra_data'][4] = 30;
            }else if($proof['extra_data'][0] == 2){
                $proof['extra_data'][4] = 21;
            }else if($proof['extra_data'][0] == 3){
                $proof['extra_data'][4] = 11;
            }
            if(isset($result_val['CurrentRound'])){
                $proof['fg_rounds']                 = $result_val['CurrentRound'];
            }
            
            if(isset($result_val['FreeSpin'])){
                //$proof['fg_times']                  = $result_val['FreeSpin'];
                $proof['fg_times']                  = 0;
            }
            $proof['free_ticket']  = null;
            $proof['free_ticket_given_type'] = null;
            $proof['free_ticket_times'] = 0;
            $proof['free_ticket_type'] = null;
            $proof['free_ticket_used_times'] = 0;
            $proof['is_respin']                 = $result_val['IsRespin'];
            $proof['next_s_table']              = $result_val['NextSTable'];
            $proof['pay_addition_card_id']              = null;
            $proof['pay_addition_given_type']              = null;
            $proof['pay_addition_rate']              = null;
            $proof['reel_len_change']           = $result_val['ReelLenChange'];
            if(isset($result_val['ReelPay'])){
                $proof['reel_pay']                  = $result_val['ReelPay'];
            }
            $proof['reel_pos_chg']              = $result_val['ReellPosChg'];
            $proof['respin_reels']              = $result_val['RespinReels'];
            $proof['special_award']             = $result_val['SpecialAward'];
            $proof['special_symbol']            = $result_val['SpecialSymbol'];
            $proof['l_v']                       = "2.4.34.3";
            $proof['s_v']                       = "5.27.1.0";
            $proof['symbol_data']               = $result_val['SymbolResult'];
            $proof['symbol_data_after']         = [];
            $proof['win_line_data']             = [];
            if(isset($result_val['LockPos'])){
                $proof['lock_position']         = $result_val['LockPos'];
            }

            foreach( $result_val['udsOutputWinLine'] as $index => $outWinLine) 
            {
                $lineData = [];
                $lineData['line_extra_data']    = $outWinLine['LineExtraData'];
                $lineData['line_multiplier']    = $outWinLine['LineMultiplier'];
                $lineData['line_prize']         = $outWinLine['LinePrize'];
                $lineData['line_type']          = $outWinLine['LineType'];
                $lineData['num_of_kind']        = $outWinLine['NumOfKind'];
                $lineData['symbol_count']       = $outWinLine['SymbolCount'];
                $lineData['symbol_id']          = $outWinLine['SymbolId'];
                
                $lineData['win_line_no']        = $outWinLine['WinLineNo'];
                $lineData['win_position']       = $outWinLine['WinPosition'];
                array_push($proof['win_line_data'], $lineData);
            }
            if($slotEvent == 'freespin' || $slotEvent == 'respin'){
                $log = $slotSettings->GetGameData($slotSettings->slotId . 'GameLog');
                $log['actionlist'][1]['amount']     = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                $log['actionlist'][1]['eventtime']  = $currentTime;
                
                $log['detail']['wager']['order_time']   = $currentTime;
                $log['detail']['wager']['end_time']     = $currentTime;
                $log['detail']['wager']['total_win']    = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');

                if(isset($result_val['LockPos'])){
                    $proof['lock_position']         = $result_val['LockPos'];
                }
                

                $sub_log = [];

                $sub_log['sub_no']              = count($log['detail']['wager']['sub']) + 1;
                if($slotEvent == 'freespin'){
                    $sub_log['game_type']           = 51;
                }else{
                    $sub_log['game_type']           = 30;
                }
                $sub_log['rng']                 = $result_val['RngData'];
                $sub_log['multiple']            = $result_val['Multiple'];
                $sub_log['win']                 = $result_val['TotalWin'];
                $sub_log['win_line_count']      = $result_val['WinLineCount'];
                $sub_log['win_type']            = $result_val['WinType'];
                $sub_log['proof']               = $proof;
                array_push($log['detail']['wager']['sub'], $sub_log);
            }else{
                $log = [];
                $log['account']                 = '' . $slotSettings->playerId;
                $log['parentacc']               = 'major_prod';
                $log['actionlist']              = [];
                $log['detail']                  = [];
                $bet_action = [];
                $bet_action['action']           = 'bet';
                $bet_action['amount']           = $betline * $lines;
                $bet_action['eventtime']        = $currentTime;
                array_push($log['actionlist'], $bet_action);
                $win_action = [];
                $win_action['action']           = 'win';
                $win_action['amount']           = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                $win_action['eventtime']        = $currentTime;
                array_push($log['actionlist'], $win_action);

                $wager= [];
                $wager['seq_no']                = $result_val['GamePlaySerialNumber'];
                $wager['order_time']            = $currentTime;
                $wager['end_time']              = $currentTime;
                $wager['user_id']               = '' . $slotSettings->playerId;
                $wager['game_id']               = '141';
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = '' . $betline * $lines;
                $wager['play_denom']            = "100";
                $wager['bet_multiple']          = '' . $betline;
                $wager['rng']                   = $result_val['RngData'];
                $wager['multiple']              = $result_val['Multiple'];
                $wager['base_game_win']         = '' . $result_val['TotalWin'];
                $wager['win_over_limit_lock']   = 0;
                $wager['game_type']             = 0;
                $wager['win_type']              = $result_val['WinType'];
                $wager['settle_type']           = 0;
                $wager['wager_type']            = 0;
                $wager['total_win']             = '' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                $wager['win_line_count']        = $result_val['WinLineCount'];
                $wager['bet_tid']               =  'pro-bet-' . $result_val['GamePlaySerialNumber'];
                $wager['win_tid']               =  'pro-win-' . $result_val['GamePlaySerialNumber'];
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
