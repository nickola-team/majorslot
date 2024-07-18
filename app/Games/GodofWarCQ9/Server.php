<?php 
namespace VanguardLTE\Games\GodofWarCQ9
{

    use Dotenv\Loader\Value;

    class Server
    {
        public $demon = 1;
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
            $originalbet = 1;

            $slotSettings->SetBet();  
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 9}],"msg": null}');
                    $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetBalance().'],"evt": 1}');                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'TriggerFree', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'PurLevel', -1);
                }else if($paramData['req'] == 2){
                    $gameDatas = $this->parseMessage($paramData['vals']);
                    $response_packet = [];
                    $result_vals = [];
                    for($i = 0; $i < count($gameDatas); $i++){
                        $gameData = json_decode($gameDatas[$i]);
                        $type = $gameData->Type;
                        $packet_id = $gameData->ID;
                        $slotSettings->SetGameData($slotSettings->slotId . 'PackID', $packet_id);
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
                            $result_val['DefaultDenomIdx'] = 3;
                            $result_val['MaxBet'] = 10000;
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 3000000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
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
                            $result_val['Tag'] = [
                                "g" => "31",
                                "s" => "5.27.1.0",
                                "l" => "2.5.1.1",
                                "si" => "36"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["NfSLkUH3MgQI1GAWhR/wOcxKSaLcPWOXnHLB/GgZ5DrDUdda9e9DWnGa5hFrH0daiCLg5YgJrO+OCLPBEp0P/jHeHKVbFU1/x3ScqUg4yGzLx7caxUwlqRur7QbxV4WwlsuF/FdHSF7r6A0nTZXUl6dwqRVnf0DIRksNDrtYyIl1U/ioss6fVQ0/L0k=","2iWiPofV3fFusxabVSrgcjMhAhJaW+4+37iMy9RlkWCBsiFxYXEbBsrWGqsjsJUNBz/ueIXf0U/ETLfn8Eqr7tMMZtoU1V6j3YXkwGWonlD9FDViE6AgT8jqP8csZKNEUjmLzJ4c00W8kUmTXpeHB95HciEzxa8+Ye+1PCAW4sBZhLIifGqLRcl+kNLNAULYZl4tt0h2sRc2NVfo","zeaSHOT9KIgWpBlTCh2MYkEHaSJq6Jv7h+gm9FJEConHJUBR/LIkOkDAu11LEA64b7ZH63kpCKRhiCuuWNC7A4yVe3nDH0FZQsRUTE9RaKgEEOvbbvlv9JXDm+WMmD8ChApR+6ayHRaXxmzu8VMJDSxi5Su7WWLieSajso7pj5IV/jXTYUJ8gOT6UAJ3J9AGIofAICvZJXlcxStB","3fg7iaKfjnSENeKXQVLWX0KLsBTLcHvNGDwpS/aMYI/8Zc03fbmOsVYbh+slHoDSWsmMUMzuKdtki29WUKETSnwGIytw9l3hYWxF3gTRC76zX7iuPxP5Yj8SyTUBfNcwfGlwI70+dxDmKhv3khSMO8aRoKOqWoYrfWyR81JfoRxdXIvybjJhDLxA0dLsk3lO/L2S2TJ3yLVy2Gkga2O5bEYIwbnT9brFKZKF6A==","seBliGnWj9tqejrBirobuO+RnC1Fu8gUBlc9lZdZkvVQzA50wjl78P6DlLXMnIu2bS5/fbx/cqY/wDNL8kkSPvs1XmuLn5uTQHY6f50CIHtxl3dlQT30ANTCbkqWYd/A3ZMQKWP12oelw9Nw"]];
                            $result_val['FGStripCount'] = 5;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["pmL38fXHeGcDexVfHFBeINZXHaNdrNiL2Xw8G9PUkghI6m5amXPmeNqJrpOImj3dlt8x/PgXHtvqD5TvWPtkYpN/seP7YzRJWNX10uhdXjiRZhnw8Xss3kBFabfvF9Jfj4HJgF/ul75UPUB8PHEM9zGiVgD2IfEDLEWsg08+2u9sphZdLNYkkKBDSY8gY2FjMgdc4Q0WR6LMiIDNRxNkbGq8oTntQs8HUOtkQg==","siLOkJFl1fYUCY5AMth9RAYT8CgLWH5FTgBSZDJr1jNmbDzz8rXXP+wYK69wL8dAqMMDvUkv3ts++4yWSpyPuDuVyUyy7bpWdLCCNwT33zfhqSpcA/eqFbbtKlzAhEisMMDQSuNOyvpcQOX5CHVIhy0JaOdENl1oW3cSipMrhZeRN5ZczO6i9GetAbOk+vziLHj/KwiUSPBRa6GUcVfDaka6b/KGLs3sgtd+Og==","FS0LbEvnTmko2exqv6TUpZ8SSbtQwAp/qx8aAX3bCBxEJFZ1EedEXrDAzRtXrPZnvLgAMR6m2qHNegUpagb/tekyok7qUvnkGLEYXgKO3rWc9ERG+vYV7Jy491sOL6J1MoXGfBK24jYPs03LksCfAWKw+V1ZMduonZHGJpAZQeNSpA0piI3//WTuHfVTSaYKL+9wnpLoamMF9gUUoVjGj7NwM8ahsfp7s3YsRA==","pV66sMJhUIiyZrArSDUzRXpgs/pEawGVi4yYOnK4pPUl+ALkfQI5FXBcx8oO1r0JuAuG7QYRSLYswViKSrHqcwua4kpuMKTrJq7VRPB3KMOn5iNH5UvO5W1t0Reu+motxs3OnQRg1gyIeOC/i+xJB6E3WmCfNFxXX2iEj5NHuQBkw2dIXsn7Z1+V/D91MYtaIVYs8bFsskbDgUZN5KVunlUXh11mwCUbZ0c0Nw==","Xr5xT06ydnYbsaIqUzWfK6TAipnsDKQZWH10AvCzJMO2hVqZLSCuKzcX64ZQKVivJ84aIAnd1hEj9ww5H9bzhYxlwr2KLEG6+WZJPCF7w3hy6oLQY5z34nV5/t4nX/GmSkZQlr8WNVTNR6Fjb3SPrSSIidCideXQSF+33HCEdNncOCz75nvOpo1W9mzsf37dbItAMRITNjMzyqvrVZlcKbaWkBceYrLOuBAJSYKNL8JCJtYkckQF/g8DNx0="],["XfYRUXKvY9X90X1TX/06UzvKE4RZaDnmm02mgFfi3QpnLR5nFsqlAOeKHgx/9D7gACndlsJ18YJ4NPxiegMWJcB+YjUEIG8ScOoIyQAx4XeIUVT0iAkWAgYbVGYMeurPB2SAs8hyPeUEsgpekHqhJFwRCV39TXgmCPD76Ui27OAUhKkhx0vSct5Qu/PpVkCgvgZYRZiXGKrEQ2hzo60N1V66y+WP03KvXandRg==","v3y1sSergjyCMOqIiD334G+I+cLlFpNGiSw2EmqH21TLvzCP3RMZRl70bTxXQC5KPxFZcAY0WkdUZThTid69+hvD/9uu+ocHZQLR23B6SWyPnNIZxjQtgCKU/aaoUlwYXO36yfBFvUeJhpCkfnr8SuaRKZjKdBsh9lGZ6wiqHXvFDWboK1eQRudkbSH0hkhOQr7+VMmBRxCAwI8kzDn4535EWSPef4z2qrA2Ng==","qu7yvOxL5A7n1HcktDpR5/ChwoM7HwUZfNltqZJclUi2jUl3TPzyIgB76zzr2sLN+cCriQ2T36eD4RtDs0Q7ArM2iMLXbmFei8vKckd5tF2pXzd/fdpBrMZHITMbY4QVhc6MKceFJjgjD2bjSPuWScGyjoIUgPMw59JYCS9t4IHvnCbPxpgtYCoEkXFDXZ2f8Chb497EH5j9NroNEhVCZEPyf19WbP51b6NY/Q==","3rzy5mQOrQh7SdggLWvOY6cH7FGU+psN6EeX5pmeLWdg9D88POrsNAMlyxFxBLzDSzMFOY/f6/As+ugAh0lIRumJPx9TbYDzt2jjJdokIyrRuiH9NzwKp6o9vLHCI9K/TW3TtBe/JlA1sOiT5umlE/tBx9M0/VlPRHvA0C8lzl0MFG0ZOB4b5LDLSG4FJLLoDx7JhDBEWgSa/af5fBxwYxygA3Zq7pzd9l+4PQ==","1egvytmY1u5lo5CviFgVvJ5rNF6YUIaAkeQpWbCzF5CygKa3e2FKVIWmfoANfMDrOIg8KIxYNs+KDM62bx+D2curybPPnBxBsIuiqOy8I7fBiu7+N1mXsrufiP6WkLYGP+YH7G1xKPZt63tFbR/B4lXrxyqAv0yR2IvhDjIPy9EN/KuD+wUkZ1IhNpmfpxfvCjtxI/xYcXh/EMQ8L4q8/MgGHhRiYUQN8FT66SSEkv57CZiuxooJmey0NsM="],["PWpJ6GGyX3HoZ3XON20eVOEZBx96DTe3z+t496pKw4TQxYklUIyP2ulCtmWWvXKvuCreqBS+A7pt9gStqVFaX2EXMPAARGYiV2Cr+W36PU+0zd/Cu+S5BKJIbXUq72s+2TxvJd8K2yRy2oL3BBKj+xcMpbHS/kH7XnLDBqd9WFRpxI9L2/ek0dlgKWCUGfMlj7oOg0VyJIfLsSnW4hmIWnFwameq/xaK60FnEQ==","1bf0dvNnBo0cQvYVGzrz8atKUoZtbi5ZtPKm1fskZd1zpbiVry3g8T/8C7E2/6A8jNeYoyYvckLiXRgQOqR51KonP7E1H+psmPukEJpxw0VnAFnv/aR8xW24imAmH7KUru6X00hTd/f1FI4XMqYXBaDPKQP7fgTI/zXXMrOZ9meSpLFmgykAA5dVZpMiS9hIzBz895Im65i0iX2Cea7OP5WgxXYxeiQrdRZqbw==","KivaST4F2AENbae1izta+zEeYDHk4B2353OfK3ncO5Vv0/aFBK7H32N5lEoeETl/qjsv/BIxN90hM6DCxnPhyVCSb5N/BNkGvzcJs8Ae8gBa1apaSWsPGTx+of0s1f6I1dqsBM9GeEsq6L4WHCLCYV5h3C7DnEFnBnWBZm43U0skjHkYCqMWwdKdSjoym5aVYmvkyqZvoCvkofygJUoXDN14tbeCfrZau9Nq/g==","zy1LM7s9dBwwPz2xIFzgYOxEaFOLic/68KaWGYv//gFFu7TDo2h9thAKs7uEX1TdahvfVLVoHwE4BEqOHCrn95QBGSpg6fyh/Xd/t9J5weDADvY5o5p3WMBlkIeNkN60XDpYDziGU0Dr/sawjmiztYQinGRF2lL5aCLgifsfwREK4WHKkJe2BKMEGopQb8m2ILSZLqixEebJmCHnmydl1h2lcZlLO60XU8cs6g==","uZc0iiF2RcKfBsBDemmXkuxV7ZKLik8ag5G3FeyxAjylFXeVFLPPvwuHE20pSQfjivOPHvvL04MckA4PHm5nNr9SLMU0ht+ygrk6Am8sfwpxe6guBEsUTsP2BK29mBSKHwlay32fE9lfeoVzCANLSj2q+hDrezGVA0OV5uXzdMjyskGzcEE60QE5/1CRR+slRI4e8M4yYDnZto6OtNAFKMzszaCrPf5VnwaHz+FeR2+58R/pnkDF9YZGr54="],["sFvPkD6yEiQpaTbT/jOgTSE4H6uHAYQKgnqBXuOhzcGbwvlsgYmtynEIjGYBAzMt5CDJr7U9HkmxsVwTmPNLT1ImBOUwcKjqq9VXD8O5ChtIQlcYOo41RtSO4TWkxq6CL+d6WAKhdDLeLkpHfaTUGokZZwGyJh20QaBzisedV8mB1KZChFdZoie9Nke4J8NNAllG+pywJv7n4COpY7YYkd3COXCrVC9Aa35O+w==","EePqsbCUx2LttLrjGKW+0LiCzPnekp31XuMUr6prG0ftL7c8QG252UDGZGVPNMT+aKPqV/QHpGROxDeb2RmyUDCsi7psl8m8bQaB2+ZV+tyv3hsWWpqyO3O8FSTPudyjOtMtlvBkH8gAYxxqG1nWNSEm0yUqQYWHbRQmEXspD589XT2arhqBd1VUrsDz4Bk7FKYebz2RMNvjrRHAkNh+Lsfpci5YYoJSx5+ctA==","cl2Qtp0BgP5BmJN2lybtEr9eDf7XOpYh4drNn3uzlPF5zcSWsUY7WHGWf+xTqbrt3w4eKe2ZADzLYm2crgst3ElfPxo4nD+/YCf5vdZ4uhjmS7VsEgQHDQ+7hvgkUY+FpbRoscF9CzEWmayIhNTrvGjZNrWk5j8+taqNicXirNClAXt3XntVHH8j8HwWjYsseoYARDRA3kwjfORImi4djCSHUyGhHrb/EIZNcw==","qD4VLWh1ves9w3GFR58Tgqfz1IYDknaD42zeciOa7c1PTVhoCI8nYtwly1DlO4sjtXfOqVvSOWnKD6bW0G04WI3tSkvmwFg/S+8u25OOAXgn2ACI0hInCV9Jv4K2o1asVxijmAJs4X1uD/XuAUIqbkiBq6YJocXeXbAaL0rC7YubD9U6JpOZjjksYulKxGevGwoYsev3euNzua166TfMn2EZzDE8FlJ2gwx1gw==","iYsINjVEMtLpDEJxa+2HuEBlB5NEX50ZKh9okPPU6xRPiRix9e/HA9tflQ0oHsaw2jlmgN+m5oknJX/Jyh4n4HWTjKTH8dDuOsJS+NEGRm6RNV+gi8vUZqhftNA86yARJMSILl9qQc3sMLlJ3KO+AEPqDxqoX9MIxZshLcOZB0T1nm7xRc+I9IHTOHXjXfqL9I/8+kUDKYsW3U0rGVGJrDb4cmdChvEZN3cJ0ZWBYREeYLh9I/0RAbCnYNs="],["Sqlf8mqPISEhlbyPyxEdPbzZQY0p3gW8p1XOjyu1913lIiwlWWqRXOq3XYhLOpELa7jnWfdMvm5R1t1tFYejhFjYgxoLGuLnfbNwxuE6jd9guimyovMq3eMxevj3v3Be4NLv7Ovp+RJ6tm+ixqIyDqVKaTT4YPoo5ML+k9NuwvEFqot22EZwRbVMYIUVZdpc0nY9eqcmOl4MFImCDDor7KjVFrf6Uq8i/AdKSQ==","HTF9eid7QpHfCHazxMg8u/o9m/Nqr8mOQ0L8Au+grP+9dwmUuEDkDKfTo1/ZBQhy7Q/pAY3aAMj/Ndi+pUiI1WYTOE8pycINZhNBhhKhpRdUZaR9tKdMtBhb2GoZTeVxt53k+Y8lll7cRNQnYFU9mqMUithM44cx6vT+zWtW/vfn6x9fx7p80uD8H0YEiNjqTWcoVrYQrh4RlfNbIk5GalppZcCf0qrnpXyC9g==","mNjiFlVtKfmGNCM3u6E7EAQ3DifanNV3jH0fP1SwbUN1U4S9CQKczvQKrkWzTQti0pFI3t3UQcvyVBbu4OBrhOKjik+p6bMkwlSI96ZCj5/a8+RFhp+I3PP/8GI7nbJfxbg4sMffAJG5ZpyWTXI91KWKdCyGNxb+lT87V5yHWHE/oDomn+3h8aIWoe3NsBOhd0qkncMaQcsFTid2HbYH5VagVLNLdAf1ZG9Ncg==","VA3wi4LE4hjWj2Ao+MEJWc85gEufN3/URemZLEtgkwTMy79y7r+RedpW2psOrzP+1UWR6/pL1XgDrW5v3kPZ+2yJiVN6dBE4dtiWXZi5ZpjzENgco03i6uSJzVKJDFmzJap8mD6a49CHw5BrycrrArA0XsgkMvMtYCc5AIbqSjY1lBz84JPUudeVem1do7j0/9I0vybg0JSEn0UuLKhionvHfsWHZMZo5NmXWQ==","Ohzzlq9Y2PmX2b6OU4bgUMbCqYm0X7KPB311cd86Zpu9RFUoYVESBuP2zAT2T3giqtZ1TA6Qqj4+pxGfOM98MbpAXkoT1OhOWMB+6qc+BEckdC0RJ4ssHD4Ux7/wpjGSFOHyXRZB7oM/MCUg/CBmWCYPN+rDhdZ172Ep0ClIz73ue2h+An4b5pRAoveXRAK1l3tlpI/qIrREBO4kBaD9M9dkOJMjXJyKNQ4hJMlgEzIa8muxx0G0Thx3atU="]];
                        }else if($packet_id == 31 || $packet_id == 42){
                            $lines = 88;
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
                                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks  릴배치표 저장
                                if($packet_id == 42){
                                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                                }else{
                                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                }
                                $slotSettings->SetGameData($slotSettings->slotId . 'PurLevel', -1);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TriggerFree', 0);
                                if(isset($gameData->PlayBet)){
                                    $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                }
                                if(isset($gameData->MiniBet)){
                                    $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lines);
                                } 
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', $betline);
                                $slotSettings->SetBet();        
                                if(isset($slotEvent['slotEvent'])){
                                    $slotSettings->SetBalance(-1 * ($betline * $lines), $slotEvent['slotEvent']);
                                    
                                }
                                
                                $_sum = ($betline * $lines) / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '690' . substr($roundstr, 3, 9);
                                $slotSettings->SetGameData($slotSettings->slotId . 'GamePlaySerialNumber', $roundstr);
                            }

                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet,$packet_id);
                            $result_val['EmulatorType'] = $emulatorType;

                            $slotSettings->SaveGameData();
                        }else if($packet_id == 32 || $packet_id == 41){
                            $result_val['ErrorCode'] = 0;
                            // if($type == 3){

                            //     $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance() - $slotSettings->GetGameData($slotSettings->slotId . 'ScatterWin'));
                            // }
                            if($packet_id == 41){
                                // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance() - $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'));
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                                $result_val['PlayerBet'] = $betline;
                                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                                if(isset($stack['AccumlateWinAmt'])){
                                    $result_val['AccumlateWinAmt'] = $stack['AccumlateWinAmt'] / $originalbet * $betline;
                                }
                                
                                $result_val['AccumlateJPAmt'] = 0;
                                $result_val['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                                if(isset($stack['MaxRound'])){
                                    $result_val['MaxRound'] = $stack['MaxRound'];
                                }else{
                                    $result_val['MaxRound'] = 1;
                                }
                                
                                $result_val['AwardRound'] = $stack['AwardRound'];
                                $result_val['CurrentRound'] = $stack['CurrentRound'];
                                if(isset($stack['MaxSpin'])){
                                    $result_val['MaxSpin'] = $stack['MaxSpin'];
                                }else{
                                    $result_val['MaxSpin'] = 100;
                                }
                                
                                $result_val['AwardSpinTimes'] = $stack['AwardSpinTimes'];
                                $result_val['Multiple'] = 0;                                
                                $result_val['GameExtraData'] = "";
                            }else{
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex', -1);
                               
                            }
                        }else if($packet_id == 44){
                            $slotEvent['slotEvent'] = 'bet';
                            $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                            if($slotSettings->GetGameData($slotSettings->slotId . 'PurLevel') == 0){
                                $lines = 88;
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet,$packet_id);
                                
                            }else{
                                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                                $result_val['PlayerBet'] = $betline;
                                if(isset($stack['AccumlateWinAmt'])){
                                    $result_val['AccumlateWinAmt'] = $stack['AccumlateWinAmt'] / $originalbet * $betline;
                                }
                                
                                $result_val['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                                $result_val['MaxRound'] = $stack['MaxRound'];
                                $result_val['CurrentRound'] = $stack['CurrentRound'];
                                $result_val['udcDataSet']['SelExtraData'] = [];
                                $result_val['udcDataSet']['SelMultiplier'] = [];
                                $result_val['udcDataSet']['SelSpinTimes'] = [];
                                $result_val['udcDataSet']['SelWin'] = [];
                                $result_val['udcDataSet']['PlayerSelected'] = [0];
                            }
                            
                            //$result_val['udcDataSet'] = ["SelExtraData"=>[],"SelMultiplier"=>[],"SelSpinTimes"=>[],"SelWin"=>[],"PlayerSelected"=>[0]];
                        }else if($packet_id == 45){
                            $slotEvent['slotEvent'] = 'bet';
                            $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []);
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                            $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex',$gameData->PlayerSelectIndex);
                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, 1, $originalbet,$packet_id);
                           
                        }else if($packet_id == 46){
                            $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            $result_val['PlayerBet'] = $betline;
                            //$result_val['TotalWinAmt'] = $stack['TotalWinAmt'] / $originalbet * $betline;
                            $result_val['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                            $result_val['TotalWinAmt'] = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                            //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $result_val['TotalWinAmt']);
                            $result_val['NextModule'] = 20;
                        }else if($packet_id == 43){
                            $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            
                            $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                            $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            if($stack['NextModule'] == 40){
                                $slotSettings->SetGameData($slotSettings->slotId . 'PurLevel', 0);
                            }
                            

                            //$result_val['TotalWinAmt'] = $stack['TotalWinAmt'] / $originalbet * $betline;
                            $result_val['TotalWinAmt'] = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                            $result_val['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                            $result_val['NextModule'] = $stack['NextModule'];
                            $result_val['GameExtraData'] = "";
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex',-1);
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
                    $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                        $lines = 88;
                    if($slotSettings->GetGameData($slotSettings->slotId . 'PackID') == 44 || $slotSettings->GetGameData($slotSettings->slotId . 'PackID') == 45){
                        $pur_level = -1;
                        $tumbAndFreeStacks= $slotSettings->GetReelStrips('bet', ($betline /  $this->demon) * $lines, 0,$pur_level);
                        if($tumbAndFreeStacks == null){
                            $response = 'unlogged';
                            exit( $response );
                        }
                        $stack = $tumbAndFreeStacks[0];
                        $freespinNum = $stack['udcDataSet']['SelSpinTimes'][0];
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespinNum);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $tumbAndFreeStacks);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 3);
                        if(isset($stack['TotalWinAmt'])){
                            $stack['TotalWinAmt'] = $stack['TotalWinAmt'] / $originalbet * $betline;
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $stack['TotalWinAmt']);
                        }
                        if(isset($stack['ScatterPayFromBaseGame'])){
                            $stack['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                        }
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'PackID') == 31 && $slotSettings->GetGameData($slotSettings->slotId . 'TriggerFree')>0){
                        //$slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 10);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex',0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'PurLevel',-1);
                        $tumbAndFreeStacks= $slotSettings->GetReelStrips('bet', ($betline /  $this->demon) * $lines, $slotSettings->GetGameData($slotSettings->slotId . 'FreeIndex'),$slotSettings->GetGameData($slotSettings->slotId . 'PurLevel'));
                        $stack = $tumbAndFreeStacks[0];
                        //$freespinNum = $stack['udcDataSet']['SelSpinTimes'][0];
                        $freespinNum = 12;
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespinNum);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $tumbAndFreeStacks);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 3);
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        // FreeSpin Balance add
                        $slotEvent['slotEvent'] = 'freespin';
                        
                        $count = 0;
                        while($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                            $result_val = [];
                            $result_val['Type'] = 3;
                            $result_val['ID'] = 142;
                            $result_val['Version'] = 0;
                            $result_val['ErrorCode'] = 0;
                            $result_val['EmulatorType'] = 0;
                            $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet,30);
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
        public function generateResult($slotSettings, $result_val, $slotEvent, $betline, $lines, $originalbet,$packetID){
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, $betline * $lines, $lines);
            $winType = $_spinSettings[0];
            $_winAvaliableMoney = $_spinSettings[1];
            //$winType = 'bonus';
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);
            if($slotSettings->GetGameData($slotSettings->slotId . 'FreeIndex') > -1 || $slotSettings->GetGameData($slotSettings->slotId . 'PurLevel') > -1){
                $winType = 'bonus';
            }

            if($slotEvent == 'freespin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
                
            }else{
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, $betline * $lines,$slotSettings->GetGameData($slotSettings->slotId . 'FreeIndex'),$slotSettings->GetGameData($slotSettings->slotId . 'PurLevel'));
                if($tumbAndFreeStacks == null){
                    $response = 'unlogged';
                    exit( $response );
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $tumbAndFreeStacks);
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                if($packetID == 31){
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $stack = $tumbAndFreeStacks[0];
                }
            }
            if(isset($stack['PlayerBet'])){
                $stack['PlayerBet'] = $betline;
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
                //$stack['AccumlateWinAmt'] = $stack['AccumlateWinAmt'] / $originalbet * $betline;
                $stack['AccumlateWinAmt'] = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + ($totalWin);
            }
            if(isset($stack['AccumlateJPAmt']) && $stack['AccumlateJPAmt'] > 0){
                $stack['AccumlateJPAmt'] = $stack['AccumlateJPAmt'] / $originalbet * $betline;
            }
            if(isset($stack['ScatterPayFromBaseGame']) && $stack['ScatterPayFromBaseGame'] > 0){
                $stack['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
            }


            if(isset($stack['IsTriggerFG']) && $stack['IsTriggerFG'] == true){
                $slotSettings->SetGameData($slotSettings->slotId . 'TriggerFree', 1);
            }

            $awardSpinTimes = 0;
            $currentSpinTimes = 0;
            if($slotEvent == 'freespin'){
                if(isset($stack['AwardSpinTimes'])){
                    $awardSpinTimes = $stack['AwardSpinTimes'];    
                    if(isset($stack['CurrentSpinTimes'])){
                        $currentSpinTimes = $stack['CurrentSpinTimes'];
                    }
                    
                }
                    
            }
            if(isset($stack['udsOutputWinLine']) && count($stack['udsOutputWinLine'])>0){
                foreach($stack['udsOutputWinLine'] as $index => $value){
                    if($value['LinePrize'] > 0){
                        $value['LinePrize'] = $value['LinePrize'] / $originalbet * $betline;
                    }
                    $stack['udsOutputWinLine'][$index] = $value;
                }
            }
            
            if($slotEvent != 'freespin' && isset($stack['IsTriggerFG'])){
                $isTriggerFG = $stack['IsTriggerFG'];
            }
            $freespinNum = 0;
            if(isset($stack['FreeSpin']) && count($stack['FreeSpin']) > 0){
                $freespinNum = $stack['FreeSpin'][0];
            }
            if(isset($stack['IsTriggerFG']) && $stack['IsTriggerFG'] == true){
                $freespinNum = 20;
            }
            $stack['Type'] = $result_val['Type'];
            $stack['ID'] = $result_val['ID'];
            $stack['Version'] = $result_val['Version'];
            $stack['ErrorCode'] = $result_val['ErrorCode'];
            $result_val = $stack;
            
            if($totalWin > 0){
                $slotSettings->SetBalance($totalWin);
                if($winType == 'bonus'){
                    $slotSettings->SetBank('bonus', -1 * $totalWin);   
                }else{
                    $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                }
                //$slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + ($totalWin));
            }

            //$result_val['Multiple'] = $stack['Multiple'];

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
                $result_val['Multiple'] = $stack['Multiple'];
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes){
                    if(($stack['AwardRound'] == $stack['CurrentRound']) && ($stack['RetriggerAddSpins'] == 0)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                        $isState = true;
                    }
                } 
            }
            
            
            if($packetID == 44 || $packetID == 45){
                
            }else{
                $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
                if($isState == true){
                    $slotSettings->SaveLogReport(json_encode($gamelog), $betline * $lines, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
                }
            }
            

            // if($slotEvent != 'freespin' && $freespinNum > 0){
            //     $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
            // }
            return $result_val;
        }
        public function parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines){
            $currentTime = $this->getCurrentTime();
            $proof = [];
            $proof['win_line_data']             = [];
            if(isset($result_val['SymbolResult'])){
                $proof['symbol_data']               = $result_val['SymbolResult'];
            }
            
            $proof['symbol_data_after']         = [];
            if(isset($result_val['ExtraData'])){
                $proof['extra_data']                = $result_val['ExtraData'];
            }
            
            if(isset($result_val['ReellPosChg'])){
                $proof['reel_pos_chg']              = $result_val['ReellPosChg'];
            }
            if(isset($result_val['ReelLenChange'])){
                $proof['reel_len_change']           = $result_val['ReelLenChange'];
            }
            
            if(isset($result_val['ReelPay'])){
                $proof['reel_pay']                  = $result_val['ReelPay'];
            }
            if(isset($result_val['RespinReels'])){
                $proof['respin_reels']              = $result_val['RespinReels'];
            }
            if(isset($result_val['BonusType'])){
                $proof['bonus_type']                = $result_val['BonusType'];
            }
            if(isset($result_val['SpecialAward'])){
                $proof['special_award']             = $result_val['SpecialAward'];
            }
            if(isset($result_val['SpecialSymbol'])){
                $proof['special_symbol']            = $result_val['SpecialSymbol'];
            }
            if(isset($result_val['IsRespin'])){
                $proof['is_respin']                 = $result_val['IsRespin'];
            }
            if(isset($result_val['FreeSpin'])){
                $proof['fg_times']                  = $result_val['FreeSpin'];
            }
            
            $proof['fg_rounds']                 = floor($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame'));
            if(isset($result_val['NextSTable'])){
                $proof['next_s_table']              = $result_val['NextSTable'];
            }
            
            //$proof['extend_feature_by_game']    = $result_val['ExtendFeatureByGame'];
            if(isset($result_val['ExtendFeatureByGame'])){
                $proof['extend_feature_by_game']    = $result_val['ExtendFeatureByGame'];
            }else{
                $proof['extend_feature_by_game']    = [];
            }
            
            $proof['extend_feature_by_game2']   = [];
            $proof['denom_multiple'] = 100;
            $proof['l_v']                       = "2.4.32.1";
            $proof['s_v']                       = "5.27.1.0";
            if(isset($result_val['udsOutputWinLine'])){
                foreach( $result_val['udsOutputWinLine'] as $index => $outWinLine) 
                {
                    $lineData = [];
                    $lineData['line_extra_data']    = $outWinLine['LineExtraData'];
                    $lineData['line_multiplier']    = $outWinLine['LineMultiplier'];
                    $lineData['line_prize']         = $outWinLine['LinePrize'];
                    $lineData['line_type']          = $outWinLine['LineType'];
                    $lineData['symbol_id']          = $outWinLine['SymbolId'];
                    $lineData['symbol_count']       = $outWinLine['SymbolCount'];
                    $lineData['num_of_kind']        = $outWinLine['NumOfKind'];
                    $lineData['win_line_no']        = $outWinLine['WinLineNo'];
                    $lineData['win_position']       = $outWinLine['WinPosition'];
                    array_push($proof['win_line_data'], $lineData);
                }
            }
            
            if($slotEvent == 'freespin'){
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
                $sub_log['game_type']           = 53;
                if(isset($result_val['RngData'])){
                    $sub_log['rng']                 = $result_val['RngData'];
                }
                
                $sub_log['multiple']            = $result_val['Multiple'];
                if(isset($result_val['TotalWin'])){
                    $sub_log['win']                 = $result_val['TotalWin'];
                }
                if(isset($result_val['WinLineCount'])){
                    $sub_log['win_line_count']      = $result_val['WinLineCount'];
                }
                
                if(isset($result_val['WinType'])){
                    $sub_log['win_type']            = $result_val['WinType'];
                }
                
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
                $bet_action['amount']           = $betline * $lines;
                $bet_action['eventtime']        = $currentTime;
                array_push($log['actionlist'], $bet_action);
                $win_action = [];
                $win_action['action']           = 'win';
                $win_action['amount']           = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                $win_action['eventtime']        = $currentTime;
                array_push($log['actionlist'], $win_action);

                $wager= [];
                if(isset($result_val['GamePlaySerialNumber'])){
                    $wager['seq_no']                = $result_val['GamePlaySerialNumber'];
                }
                
                $wager['order_time']            = $currentTime;
                $wager['end_time']              = $currentTime;
                $wager['user_id']               = $slotSettings->playerId;
                $wager['game_id']               = 31;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = $betline * $lines;
                $wager['play_denom']            = 100;
                $wager['bet_multiple']          = $betline;
                if(isset($result_val['RngData'])){
                    $wager['rng']                   = $result_val['RngData'];
                }
                
                if(isset($result_val['TotalWin'])){
                    $wager['base_game_win']         = $result_val['TotalWin'];
                }
                
                $wager['win_over_limit_lock']   = 0;
                $wager['game_type']             = 0;
                if(isset($result_val['WinType'])){
                    $wager['win_type']              = $result_val['WinType'];
                }
                
                $wager['settle_type']           = 0;
                $wager['wager_type']            = 0;
                $wager['total_win']             = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                if(isset($result_val['WinLineCount'])){
                    $wager['win_line_count']        = $result_val['WinLineCount'];
                }
                
                if(isset($result_val['GamePlaySerialNumber'])){
                    $wager['bet_tid']               =  'pro-bet-' . $result_val['GamePlaySerialNumber'];
                    $wager['win_tid']               =  'pro-win-' . $result_val['GamePlaySerialNumber'];
                }
                
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
