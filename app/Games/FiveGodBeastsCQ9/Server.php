<?php 
namespace VanguardLTE\Games\FiveGodBeastsCQ9
{

    use Dotenv\Loader\Value;

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
            $originalbet = 1;
            $slotSettings->SetBet();  
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 7}],"msg": null}');
                    $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetBalance().'],"evt": 1}');                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());

                    $slotSettings->SetGameData($slotSettings->slotId. 'GameRounds',1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount',0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex', -1);
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
                            $result_val['DefaultDenomIdx'] = 3;
                            $result_val['MaxBet'] = 2000;
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 300000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = [["name"=>"AccumulateUpCount","value"=>0],["name"=>"LevelUpCount","value"=>3],["name"=>"CurrentLevel","value"=>1],["name"=>"NextLevel","value"=>2]];
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
                            $result_val['BGStripCount'] = 5;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["cSsYgfEdEFRFIcqc8KpceIhsAZtHo71OejbGaQ2xHtGMJPjfhpFak0w802kgmrlDjDy4ZmEShKPnD8SrJmnP3fZRcZpDwCkULuRUORKlk9lv8/kBypf5+PC5GF/ID0o5VKBcyx68yHd/CEcGSGBXh3XjsaH3Ueq8abV5dkOBJLakpqloW6udNu8PflPIIIo7vqjMaDImd8r+FLoFjtEkb/KN2i06MGLzMIcpRI/Bj7WnWtGUNt5H9zIl9+RuZ9NAl8yD3ac+PfgxvO80/W8yYVcjYAVclOjODdsgZutpOXtecR5xLYxk0E0DeN2fnqc7xiBEw99ZHuIKvdF59c+aeE5Erplg3Hi9U/9Xuqav2Dh5VpdGgIRxMWDRipo=","eF0F5AXKZAzHH2mCMWdzvGQpPBatp5Jw+U4h8/G7OYM68IT8pIVq2zp6y9UUElFhs4kIT4EbNlLz2YBCvkz1AqEh+Ajw5G9x2gRjFHgjzqM2WPDqeI2Bm+WjsywhxtIEEdLAEJ184EHzCHuxUgDK9rPqBouK3MIiYxI50aQkVTno923WtscDF2c5gr9ZYz4t2njTB+nL6nK/260iW6UwI2rqWHv7rcqCvys1l2H0AepjdRV3KQBjhpJxBJDh0XVl9OfbY94PetYxerZeb+S1AIqBbjV8k6EXiJ2xE9d5itZ8Ouk5TQLerWqloOsZREhP2N5S5iC8lTeTu0c6oP/k0aK8tUxVpjOiAT7Abi4qd3J+lu+xDarm7Cd1V0U=","R24Af1aWcS60Bzqe5Mn1P3J7wnYm6Ysbzq46pFxPRV7KhuY737s3rerAS+LPbJmOxss2+WBzVbQhQ3DdyOtQU97aEbGs748NEZ4+RNe7vRaWo0+dEZsPlAJ0WIV8FAueD6lGukrJ//ZMp1KoqGjJJG/f8WSv2ZmFRET0DKjn1/4xhw3iOxmJ7a0SolToGWPcl/GL4gMROEw6uEXCiY6yIr4XWHhhh50R+W8a/O52oaSYPG2X5+UBADuNR4Jnngq07lYwXUTLbg2XgTHlmqTkAFoU58pT/t2/z5hiAcAjcD7Ov0ur8YheZvxJX8RtvVtVRiL9C84DmcYgHznxK376USjL2mOE/m5VeiwyE9BpgO2o3thktHoJpzmF7khWlP+1pgZLhv8D7d2HNAWjWzmRpzivi32YJ6PxtXAm9Tmv3DMbiHzXNpfVwbDSeRpD+WyCErLx9ukwQxbzfEzGPrLlHHIVSThSDTEVPhxwVTmhVPUQ1qWIOUbo9Zac+Z0=","2FK6D2LN8LYwFqmdn9al9HdU7VftEoL4NiAMtcV2VErkBorMSI4EKZclQrFeiUdKkZ3n51bUA+XCqcVjkAU/xGSFKqIXwy35YEFQjsEUkwxWq6Re6knDaLT9z7pb13JPfYFTRE876yhLj8TTM9dWUsQddblQiAj//opuu+vtNo6YjIH+mrx+r8YkC77Fe5r7VUEq0KtIiWYHRMWtKhbC03IffLF9qxtKMSXT7AV9rCaueCPLgI5q9ueAxoKpBfNH0OlrIBUH95mJw2Q63O5wzeMJHobuYozRCYGGDF1VHg+XI6X1S9wd6n9EAnainIwlShKseMj8+boNqmmqQyFQzYPxIzvFzMh5KygzN0JD+hftSCFHKllHohnCpimw8FynSqlmV1jSQqTxzKMpJwwaK0IDexoYupqvUS3gfRDJjwgUa6ag8DRuoy3Tyc4jQ5gejZZb8mMdD0I8jFhdHGVhtpQlbxS4sdVVbjgM6rzpuDbV5tVfwnE/5tcD8fyKMWQGSne+6a16Fn138YQB","LdB3G3vyC1w58v7SK7wGN2/PNq97Oryux6Uw9bgTYN+iw3PMo0efeoIrtvGHCzv68ZrkKAfjZYo7/UNfIpoy2in2fmwRedUfRmc1QN+xfKWhDko5ZazLnhiK4Csb61jRR2Hz/h2vtNCOeKnEHDO8awCMNSa1PnLX/5Gsn36dDHfG3nb3TZID6JWnZnETZ8Cvy0WyoaaHNM2oI/k7DEQZiiSrSaL2gc3zbnyEJCMJMTcL+aIo5ZZgP9H+DttLtdj5A8inpxtVcFRn4cUQJdD/MfVc8U7IJ1Q8luY7FFMPAYLFhyYiyivsRz8KrXG1Rz+9oFzVDelDx3kNt9xd6SaI4czXA9uTSBK9Hu4hCqctkHpGdeNn7ZGcd3beMOb7w97fthhsJUi+6qVHEjwL1vnw7BQcPQ0lSHd4X0jTGfQhrTKxgj6Jxtd0HEkz8hzLr95R3qrC1Naj/B0cESu42fztMjdT9SC4mLy+5Or2vRLHQ9eLnPRi3+pnhSId8AhiaRJcjETXIQXliFLzobyZ"],["t6qS9aQvTzQ2aJ7GCYq6scLQ2mREv8eN51GRlhMwrnWM8rfgTG+1M9yPr4SLic8G8xFjNjm+OjVc6weXq7TVifA7OWFn7fpJFQVwqM8NxSyYXK4cAhSJxI64/ptjMwWnLwsR9o5ypak1YD1v/v1/AE8OvC6jiIfQWmI9YeOWKHVYu8FkZcUYr8zH/6GoVoRd12DyVUY/zX2Gz5Mu7AuuKaIUkKsSAX5m+kqegrzLfPokAWC+w9Ow+3LYlK/bCyy3HwjA/9Y4S79K6Wk7FAa/LJnr6npHuNRAOdb4Z4Zc26viVdHAe9eo+t66ZCk=","nYTDm8VUjhSUVS65PZ4r8B5rqLsDJ8BAvMWk9GukWkedSkUQzPUd5uhj+rN8YDP4dNXkzSSdLIJYWQJx3e3Dkc7og5KR+XKUmt6jcC3qAMiJKc16ukyPE157N3QnVYuBHs3SgeMbupqoPtleokCQhqnRST7U6x39zDTS2pwRaWvf3TGDaVLuw3K3s2ZEyERWeVKE7yBBfZnQ+O6lkZCYiz6powcc8u7EX/MtFjRQd+0m+kncdOcK5Yo3cJgpqtaNiasvTwRRpGD/m1kklzeucRs9p5/V/i0vWt+1QA==","cufECY1QLobHB1YBgF8gLwTPp2HBCg/Qq9VCiPVkN1p3MUmTufdOonpT3ekRvKnIfx3ENjEi8fO8xJV+W0W5I52JsQ+X6sB3PEuE7kspOHVLhJOYfY27nKxZH4qdohdhDbqLkfVGomlZEq1lj/bPYI6N3rM2E3vy+Mb24rgLdNIqVu9YyeBT52g1ZtVncxn5DcRncsN/OPZJk/9mnP3onTAqWJAfTrSL3Y7eXfEXmlfyfBRkcoVGRWF0T02z0617E6Cbsfqm4C+tZiXG3B2HXghRc1sk69FMrdIT6ezXJoyFebJWnR5wtsjzN6q6fEMM559haFojH+IJ/XOANsw7N37d6EvYNWDzuF/ClrezgnVvUoSeioaddpzQSdY9w7wXaBMXFp08A5f9c8lIUGIM7PQhIhakRyeXgd4z3L5Bn9UNlDemcrJ0iDNqfwZMz27GRMvhuz2fniCEZl0z1T0iP+D1bb7IzHKQOK8Iug==","HsrmDdReBG4FUKLXsTSFigqQw3ITlw2aj9a+moVrUSVXEuTcQOWyf6I7PywwmX4oxTzhyAdVafzcs1uvHxMpDsVOt9XajO0ky10sXJfO+zJ2LNgYLgH+soDYmPts/GfmKEn83OaFQ/H+eMMgSMRWROBPV+5FxsvIupDdREupaqUMnZKUerhJ5AYP1OrgY8xRoaN7EoSpN6xWFihr/PkMpkdyZ8dohTxNFJ8bwMJBTCLhePmkvoBpkvu9uiT9QJcmQgS5pIOgV7gPpRKMCQabTQ5Yfm3a9P3+STbbkUzILuAtVAgaIIv75600x8GdWwaz3lCA5Ssu6HHu40Gp","7lWRoewvhhvXSwULAr1WnaHtd68liDfrCkMWidOVGUoft5vlSLsXZMNf95rkLutrBsvCYcMzsAsN3jd5DK3ZcX5Bzg/5C3ZBnZGJ39x+WmPihv6ufROdmEObmb0l1SwgVMnKw+mFAYPjzyVRjrXVQpdHSvjWhurcKjHYKzpdgHBneb1zwyiEJJMNGLMaMNCczMx4JhBdQtptBDGeVAgMJiSGkG3///x6bctVROJ5KhDu7s5YHLCYF+EKefdZDGspWGRaSp3luDLL3/cYoxX9LGPz377fIrGSqJq/2XAV2Fj8QF1ftzuT8tzYGpF7JkR3Y7ZZTt++zWEJ3C7D"],["7hkIUrojgvmjG0e9ddL1fjsh8bscvO8WCz9tgtBlylEV7P0fdPKIrjfy666WXDYvaYx1CkncfQx/ZSopX/j0+0y7HqUPtLVWU4u7QfhYR3poIFhR28X0IVbeUL8NFpdBR4wDOo6KZ1prwzD8FRM+YDB63yIiL3jE7ukChL3ooLBPhilVEf+81QkVPEQXKBR3TXt7/AwzYiUL+VnqQc41JFsdrGS0Hl3/pj62fjCYCI1ioWBXiu9VZzlakgPeKIaM0J9sdkR7VddKjKdY","td4uSZsrud56VuEazCVj/2ytSGvJMARgY/lzd2kO3w0k0bf/jIMof11WEK30pMIObn4WfPVwn0IqHraOGInvK3HOcly/avfASwV1fywwUBZjAQk4EW5pZ7+BTDFjchXQNizjCdbSycwX1BxNa+OXo5F/KVYpmBCh1GoG6WEpZL5E9y2RmGv2PXXSBuMOHyYqkx4iSGMFTViPLROSbBeiRgbTEFMI7cEdnbwW1pyiM/Be3ms/gzByWqZqAaE=","slG10DNv6CE15xE7LsQCO89sC3zWS5+o6FjDGC17z2ZjwbC2o2QAcyZ+O7cAR9vIOhdk3GEQdL7kCWAlA5zjZScUr4v3s3eTF/8nDtH3SYT0I62YlCg3HxFEeDasM0HAhC69JBMcCiTJSMMjqRm+Rsd4EBZEyG6IOqAOkEZxBnZ5bRHLVYPP0QzsKgDQK5SiPd5uczAEJGWtB8jgWbLtKrouAwQabjU6tqUV21GatRyaDFVx4Kg2GfElPlpRuOKmKwygHfeEXCuWKufuYEvZD9pCXzpS6mfUkn4pi7YJ+ZrgslUUgC3vPbjaT/qBGqYVcqkjhuz5rdytoSmnrWazXDriVPKuBo5YDLTtzmd4mPwJmpRsLc8vNdFHuUhblnPdXy8RYeZCHnnbuzlXMMOt/lqhjslAVzVYJHwFSQ==","iPqPV105FWavzykVFGvUPSGDYLN2o/+1U3qeC2iBlYLddmuy/ay/5y9KTwNJ/o+OTtMj/SylHPXqkrb/xfOKJPVOdRNovDMVLZsOt7TLDw9OgzegngAXkeD9T47Rnn6gcXy4f7sbIbvZdPLaG3pzyexaANV4pPk+gp1mOmbH6HR9KddiWtTY7f92IaI8ETA4YilTv+hTi+jd6UKRTGiRGXnMkRpNqdl/LWSqZxB4Y+lNSxaYJrRxEAH12aZDK656o7EvZaPxq2ROgmObrsqVLWSTiLOxFkOYAsFsRA==","wuKhTqIaSFtAvq8nelUbpvUpT9dZhpFw0nRHT/mWXCH4BxSRNL4DJ5KQ5brfO/OMizcX852M90i7lx5JNJZURaF+76mJK08exjfNOM3+7QjkLzw0vW25aFsit4zFx8838K2c7cO2hSpSlnk0AEsGtsYXwGKtbi8W7qV3NMRrrcCILrfLFEjIq+cLIGIGSfMFKFb1XhtCaAYxnz0u3VrRPZU7w/t0rKgDCFjXrYN28/QC0ZLSm4U2b6IccxZew9WWd4/HkcKzPqXsrlwk42g4//y3FUJUe5J/akr+7g=="],["rjBfTFUwku0GCTuvHV+7S7mz6Er5v7f6mGIjf63/WHLt3H3AzSYvJ8peay7lKprximtoTrZoGHkP/ceTaK2Lgf8PiuuOSugCr10l83f0PZsXowctv2lnmYJ6nlvYrgk0udf8FExXDnL4fYMZrQil8esp0JHrjXYNJPxmRemdkerN4Deawj/S5xXbpsGj1DCaHjdB/n4iiLnXRDo7wXs86iGVqIKPttj6KN3Tr5rsFVLOdodiJGsL+z/eyvw67lVggMW/ARngomZuGGo3xGR7+YSyxxvGTeIzKmCd7Q==","hIbbdt5qIHc8eCTR/V0FI5ACa0PxS+p5tiXgfgsCYUc9/Mnpx4wN2Zw89bq+3E1c29L6/QxGbTTltQRO1BEcGNhbdmAIy7GDI55p+QfnsMZHtDdsmSZDiY1uY0B1bu3nox8ycIIYr60vjypxSM+tdCBiDqjPO5RHvhwDOG8VggwckKjh0DCYD/GjImowOxhPXP4OCfEFU2txVutYXt4n92jzEK2+1/R2mpWj42eZrmVNzbp0FYEXpe/zopSoOX9KmNmfI8S++V0HPITRNJH1Z28y0Y+DM6mQtrlCcQ==","ECAx6TGCMc6btFVKXQBK1AXCKa/EzM9FVOaQPO15jBexjeMdjaBSBt8T6MA0Hsucr3t0MDpHcu5cRFFuwmTnGPyIFDGc8hjUMavmRQ5eGRT9Mo18OeD7IUZ0tTwlLK4lxpoFDYlSXDEPGHOqJrahCEXlTjwgIO9f77wBxOsuNjdz/2SM334oD3CAH25w1bZYzzM/ZbfawaVS0J+e9SEZ+Y9lbBJvBe+WebC3fdU6eBIX9wYXpP59gRUiOcMMZtbzq0QU+Gp3lQXUY35DtrIHNWkA+LNLHiManQiimtOHEb2AMzjYeueHlBvymzlFB4W8Nq4Ya2J/aL2bBG+RoxVADfvMhJNBAaXwnAM0LsG0e6vZBpRGi40OkeHf17GelwSZ4VAEdU/PuGA870yqS/PSgsw6I3h1Hi/uBgcaPb6caRJa7rSfss5Wd4zk2+jUzkOPZfuQ4oNN6nIbnMxCtpqO3VCAwi5CSQ1RWI6N1Q==","dw01pMSLpJGIFHZYrQDMoA4FNSmbjUW4QrU2w4OSLZ+QTQN+WNkgyIq9bpeXRW1qgWWfv78Foo82JsWgT3JnXPX+2TpXbPghJzC60vEsBSoY4MGGot1u/L2pIdN+0dqYW1drWqxl2Vex1F8iBXyFQrtgFtZ5FCdz0jS2qcOv7dda9rVksLMoWU4EXkHdbUO7b8X3OPAvJtxnhuzC/y4D/W3nXHxnbRApHlkHPVjy0jBzyoXHCCg0+wkCJR9ydC0/3tOuJumG6zI2nxE7cJCBvy5qCXK7783Vl14m15KrD69YH8sPQiSBT1Js4x1Ct6fJ3RNQ+elozez01b4On0s5CFX4fybC2D13nPL9EA==","0HxHpZ2NXmvKAUlnCOu+Xiu8EVJloHqtWxmArxaZkdKJRR4/yogJxPDWl6mP3KrHfph6R74rdcoMttns1LGWWtiFzNoFLZLTGSjsA5gm1xNuy47y3BbTc4e0rW0Bd9GGaZpk0wwi+gI41VqC1nmuTMje9Q9DheDaN/o0l++rpKISSqyqq7kpn5PU0Z/IO/A+OAOBOUOIEoETKwrHDueMcTIxvko/cmV+LGRtU8IgVlTq9LEwpkQH/JIvCemkAEbDQxVD5D0Xz3SWL90NnpRLcGmTkxdwRwtX3kvbMVwMuF8ZOFSeH7MN43F1TOg="],["pajCQam7dZYwYFqz+9igF0Sn3GHWaFSbPnaGp1jKns9S3gKNh64HYrI2Bj4UisHfsk9hpcCTlrcxXNMwi4CuDxxhJHvp3dBpO2DhAVeDOn8jEzNRQAhRAF9U39Xp7sudEtaGE3yRNx1AZD1c5NGrHAZVIf99rdX0Wc4TvhEFBHe2q2DrzRS6zwvddpMfFH0p8ckaq7bzb+G7OAzx7a+97YxBX8Y/17MmbrCKLA==","kuls90CB83zNIdNTQJJFh+y0pBWJ6hm9XJTpCZX9zzc8FmS8XP1ySWH2vQRL56ghsA8oUI2fdPqMdmewgyqwKza2XU9VbeA2DfYsMrA1CvmWLqpXyzgau2JbchkYQtaTThh39ZZMWjbAxwJv57Z4sA/LmNCSE5qq3/ajixh1URqqEWx5DxdIuONdMLHa3YbLDtL0RVQgCWjwGP3TTnvRRIGD4osKzJ+2jKbKrD+LK3jlDGQfE97xJE394Es=","TrTg7Z7tUpj9VRSBBsBgMjX9UC8EqyAN70rVaPA8NlFNNWh+k21PMh23+uuipy576PpYLq9NCmyuvtsZjrznsSoZjn9UkZ+0H0wmj8FsnLc1ozQg3qHe5KOTWPAOb3lEt/GDU/1F5OFKKKJg/qHU+vf/fYbXKRgn6WmAR2Q/bT661BmvX/QYCr28VPhWLHB8E41lmb4pCYOo9HI5","2LL2SovWG7TATshfgq/HvI6GHGbu9Hpp8kDT+jaKNE+y93UbyYed75gNsAQ9imyGEkvJLaoJMY7hN3vinjZaieCIVR8FH6jR07zS7afO+Y5Oc6OVImsVM5eS2BnlW+TfX9Ad7iusP3aIpeQVyWW2Pi1YnT4IQhWXvpnXwKKnTXKzHH6VfVH/JVByxQD9+fMLhUdjLz7U91dIOY8+6RH62pbz/dt5qwH5j9fGDqVMsk1L0GdGu2aQITKQyZ4eTGf4JUIw7CIKart5gq09Tl2+nJfyjSWEKZ/SnHYM1A==","sfPclx8UFdBp6mtWIrhFBaxBy2uP7ILvJhgI+p9aHU3Iif/45SA5ft/ML6VjgzhYYNzdF7jzDtDTikBztNVwPVXK5YLQ1KjjuRPyB+cWTsHjrf9hfBrbODyRVqznct7eT2z7rtGRSwf2Rx8swGMIw2RBofUf8WreV9bacPQQX9k6+Ny84RCa/0lFMU+C2N5F6sUm6k8Zg6H/hUDe7tJXFFTWwzYmy7ZT/DYc++g8hrKOdOg4r/5IQQFt0Ro="]];
                            $result_val['FGStripCount'] = 5;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["UPhIMOITnlxobGBlpE/S5aOjCD+dv0VbdiLn7tfkGFK7eSpT2yT+XetQW2Ag4T1ESCJ84wWnsmz9N2C6GH9klc413ZEyt7xhg+UE5cPI7hclwbD+RxOP6uWN9ouL5X2uIcD085eInJhTjB1SL0PcJDAUe9hcmGVHcwFYBVvCS8ZUUINH95EJtpR7UJRSfe6FCM4XmFeySr6MPCkbOwqWWNGOyAIoTO1x6JHcyX2jwrmhXlWpdh5ZMZ5BnQBa7Mjp2T1L4JQCZFlwanfj","oOUnsFgcBmI9pNxbxIw2iqETJCNDxZdQtXwYNp3n3nJ893D4mDflu8jtaZxoIfwz+Xqyft80VDRPEVNTMIJNEWyHctKApl7qAQmSg6wbIzINm+6kNnn+BfQsFoHX3VziY8ysnebDBha8KzyRbL/TsZR1YX3RxGW315rxkMMByBUw3iKNFDEq4BDhoW9ccaivib0OvWGFWaGaLpOngXxRnCcG189eYO3bLFF/Ys0lQdK8UBLJkuZWY7Cn95/02WFAPRmeP68OeCAYEIbvmZFCYBoMRy2IG2ygaqFhbA==","zhJlPp7SZPZpddYz8//hPHsQFCQjIxW0zEjId/cMmyryhPcL3CddhTX5MmTjfKCL5IQENwpdUTgmUwi/7MlL6YoGjqFvEmX1M0LJXDY6bGcje4LT0FEHTmNia6q2RFyZUxiumj1GdfXi18FouTzJxP49GrNgPLrQ236TABMR0wiRdZ2mjplzsBbe8Va+JaDbtLubVYGLyuHaKRALViWYIn0+wAuRRyqeXFM4VHNImJXV0iGl0zzZLMMP5z0K77nxNHT18i7cuUa8k9o2","wmBYkPxkq8oSIp1Qb9Ymzhdtpn3YeSpjwVtHL7h25vo0HDwWed8T14+ckpQo5U0M21XRFrYT6zwyzjOzTwZKRnsDU64CsFKOWNOwVBXVHYoEDHJJJ+qsx5GNGSgIJvRAzn3rLYdlmQ2WRj4wzVKkuSOHhHiosIBHoMD7zSbhPnuEfdw8vGULrpPOWD308WDDYWOMPsDVuneHT2Dve1pxIB7mT2GTPWYSdiN4aZpTovuEoQXK+JrGkRXxvZGkwibq2c17Ccy7Y2X2/iH2AShlZYMeXz7apZTvMThpcl3P8Al2AfGb33SaWX2iMf+vYeplW2pgVICRytPmUUbr+MCPNbX4K776KPYbJZK5xA==","YboBKBG3K5gtWgexB3leH9y+AW2rLofLU6SH621QHO7eHYxoK48hHOUUJ3gZsqUkU9cjyI4mCXA3QHWnGEUl9aMEJwtcG8oEVwlbYlEy/sf8AGwh9m1fSnwTuUFTvUsk/foSy0U5XLFmhu+2ela3F7DvXqYCknDLjcn7y3OHL1EZZ5yZueMpgqyJL0Z+K+p0J3ULHeXvuY2v4FhKd8PjHyEIRwRSQ3E/vJMnSfstcimIv3YstoSzNK/dy2a1CsF+b1oTulFNoTZv7TfTDqLB/tZR7G2NOwVOlU8E2xxlFvO1rENDS+Z5OHN38b0="],["qlCIlkMUGG5O6Frul2RpILEBvv3zRBABRyuhXd/or+mUdo6FSXGp2cTvGDFx0D2iBFqb4sipImEAYKbPfFi8LuXK+JlocGiaU7wEAvethuc7mNnCe39mWMi2VBiu4XolAEoseMS6cu6Ty+dJlgbGCSY3K16lXiQ/+EjQ2sg3meGL3NkPzIRHTo+0HcAI27Yg0/Ht8W0qAR5w6vG0GBTWWacD5uy5kIPt6t3GguxdVJ6be/sxJ2nVbR65P9I=","WwMe0k3gDkmC949nit4a5htTEiWy+EfUHRfkZqT+rsGfjGaO5q2UI6L7rEQHfBvMPLrVx6cvLd9rr2ydIeImB/8e4EdQruebNc6s3yJNLkQ5mTjV4jn7epuI0hgo9I9puW7ygj2DFAgPs01EV0Fa3TtRuADejiFYlKr3fi5VJ6VCw0dVjvmMqXQ4Ra+2U3MrDB55hkDN0vxWDyATLLo4/sCnc9QBRumhRROLNluSUPlxDdUNrYtB9pOOq/s=","VTe7qgDPvAeb5WnUe4st/T4/TheyJLurAQ8kxY/1P+RA7nCO6tnT1xnocEqA74Fmg+0zRF0v6A66bbIgGrx5S40/Z6QfBy22S2xGNoFiuQsgy22rIhFjjtIhjgZ8TBmYnV37eA7zHI4H0PznEtPubW8VjOci+RLsePf2AAPCZjWrOdLqQykqWfoD7HyVMUNzKPoYlNVVnn6388+5sXleXh7TY5VGvTXVs0YxJD23p5jVOwvacUqEW5rV3slwPoZ0NHxnaZEStPksq1l1","FiMKFCqRaelaK3ciL2teBsIh/+KIV4hYgxhgAUg+jp7M5y0RgMFU82oacjW7VNqy5pTDxE4BgGBt8Hmc8H+nPpztvDHxMbzuc7/c1XkOcsInv8aSttvva/t+VXmb9lagxoxJ1zcIBcEx76nc+/B1+dDiKfr6uIdHyw4H5smL0+JjAiPZOs+ty6qf65MT1+bVXptYKj7C2/J6wPiNs+4UQlCY3OYNFTcBIR1gDWWLKQmgEH2Ac7sm9HF0x6uHU6P3MxUHNls1CE6r65uvusmTU1tFUI1+4nVBjAL+FRTQqA8W6s0ESoGDhyPQqHA=","BHMeOxXWYzCwblNPXW1QdPvyVpGAYgvFVBXbH/se+4XwDmQ2Vkl7YtvbDXNidmuEuTyjRpF1nSPv8HpLbzM4hfEh4q2YjuYD1qN4ZJ4MTbsGayW27SH9ht9siAGVgmzXLWCqsD5naVWkUCi5XHa07kR08b1AbpPwVdFdXAQ+5WMPL+Efj6/sG1Q08IhlBByNGyU+Qm2NsxEkGe6CiJWrsWeNL+fT+LJWA2+1oi0Nib64xY64tf82NXPo85CCaGwWVoie4wf/m/rSg4RZrea9ZhHEiuD6BizI1Ro+8Wz3GeloV4P1XbnC8smsndMUw+BfkvJbHLh7UVnmoRfNUJX7GT2/vNz/dg1BPpIUd9ivLwv2IRFsWma4dchG2BGyVtQVWHz0M5igPDoc4ZF5IjvXGRZTyJ23rHO+txWBfQ=="],["HR9Upw77V1EsIjyst/so9wX0VlNze27+qW7sKalfAhWE3gKBoF6K2vF0oBH+PihSp1MYez42Unf4dRi6zoMFViDk/R2OvHLJSKQ+bxlhBd7jU5Iys1A47eAf2kF/2WxqUarysGEkrPRMzlBqpPEe/v8sMp/zt43Fytb+cYYDrFvDngcl9ZPl50MXUpREhB/WjMu+oUZCCJbp3IKw/5aj4bnIe6nAvVfhkKOa04kXds8X5wIhN6kah/XNBQI=","LlO4pVBqPsmenilJe5HAbEUZ0O0meZjs4RLqAUI2BMRDaDAiyUU7RvdHbR5374yxxuCG4DuGFXlKndXBSLNji4Oqbg6JThK3XEMO0a9K+Rs4MgjcLNDc6WhLB2VwFOy88uGA7ei9nxREv8i22/qzh6EnzGHMPyUsUbRILsruEgtNYQCuPVlrvBnnY7HM16UqiGPUwPqoixi66vdk96ZimV+cXLYG+lPRj+rBlIB2yeT7xiNqSiaIkafxQLjWaxzNlMkPkuzaQ9G6ndCx","mX3vtK5R1LWNkHwOVK4RSVy4NzkyUQ+m9Bmk4uYIfNdARVAUj04LVTR2Pvv7PMF41uL78ZQIgV0+tjwJPzELtuFzgrCmyc9q5YLKc5D3qN/K5QRofweayjlaWsoOcaegRZbrJ+Fa5sTJuHqg6KKDVL3H/KHYomgREXT2/llYR3MMkrqSGJiqHM9kBIQyfVn/cJhtmxuf9OlsiNyRr6WlZNwcK1bIPYAwEbj9PsN+maWL1yXvz3Mv0G+atjdQSGn8gqds8M7KUArhsN9T","BmySLLlhyk9jTcyoXipKPlfK4G7f3HtazrlT0wkR9enj/YPf364dtlI5nuSHqIMq5HnArSlQXvi/e9Zb642+2FLJGd1tT4wsIG4mSi9s39na6hDyHnOqjY1pQ+Kkk9fkOL/gU/dr4r+tWlaJF4RHNfTsTV4jcYc5IIBr2ocdLrF2qE1Funfq14znwdbpmcts4vfgzq4FrcBwNAI9SrcCfTs84qpQlXX9yZOKpo1M2TOQrZWlg2fq4A/MzXD+CxrV5mrBL5Uo51JvDoRs","GaPUgiVdcIM5wSE6HX141aaA9Upj62sc1jq925JpBbhyop5B6kQtKr1jXFVtwr+8+EAOH0o2zkOHCx7XovILJ/c+ofamRd5EpsIclbRlWwGWhpAmQbM+e6LgEqD86wsjRsJmRsb0i7WxWOOk2GspI29hgTVCd+ZB3HtTLSnmgyNJq8eDFoWQJRmDJaspY2uawlytOHndZlszGQtIigEY1/iuD5KEs6b1oZsV9yRMp+Q0VGjjrdz26bMLbW8="],["gSF8VWkxhC6oKcYzVd/IG2Iqvlbpq51AQk/CUuEHIxj5slmrkldoNL9HGu+fGPX3LJS0ClnXXcuAlS9VzgKSaF0jmZ4ctLUlrkNhL+IkeUNAYkJ3q9oESea7+pra0c+bFVQghlwe/3mb40DEp7L/3OimA9OGRFJQd61b+gsRzmoy5Zvd4ojBIEbfo4zc6X/mNp6p/wJM+NEmcvRhWWF0p50zTRiJKpkqtV4qcOVGznco0zH64CkfD57P8A4=","caho3iKEcsPGaILMo5EKlyPxpUg9K0zIq3LZz7KjPCeoO+nbgKN/yXuf0Qje7a30RHATsBolDZv1SyiGwhU5S1FXk8KxSHKtDJ/Ubabd3UnMnei2Izh/L3EJYpfikkuhgn6gTm+tiQpl7QWo/+ZkBuE8CTzBhtiR38DSkiq9mjlipICd9yOla4R8mnvlem62N1pCwd9E3g8VrLxKXzfQfowgKplGdrF9yBGFS6Up5G7smsmsH1ie0axeelVAu8a3mdsjQfkTydSCDKZo","D5mgeiO3smhwLoXIsn/d25beGaEhibEk4eb9FHrE1xsSz+sjRADfpRctQJv1dayLG2q/y77DIIMWFgSoHUxPuqukw7g+rE/eeESRjHlmnEhaBqBYM8etj8vmtskQP3G1DVHhH8UfYr1WpuWHG4cOA7MBcr+ym0ZE79imnBnIyimx7rBM9u9LHKGuLHb4mqqVCarIUxIBSUgYcksRdcUCl0ghDEAmo+LkTAQW25o0vktRFdpSJnR4A4chK0JwDejZOAgmEzXT6iTCXb/O+IoEtrcWH67RmEobgahh6w==","8kY9hHXulfhVhxFelO14g38GjiP0S+GqOX8nr8DMVmMfsJWWpp4YV1xi36fh1siGCAEg2XksFHLDnfKpmNFJrjCXt3jd7vQHj7GXKQN+vnETriIbLjYwgb597HsKJxjBv5/iNB6MoAQW5aRe2RmhaQUROm1oVYQvvryHrk6RrXMWj5fG1chMmQ3kugGcznRiibQduhiqfwWZvPiWJrz/XAnmsP+KutIQ4b+GYWRMPg10a9LjZYdlP3cn7QvxfiuoYjLoUwSLSQJhZ+YWMIb/ZmoKkgN25cgP+UJreQ==","8HsDt1bBF5AgU8BFdsvaHSWmlX4lTa+ILhviU31pmVoW1bgfeYaGBZ5oZX5mE9fPVV4ns+EulgxN1akVQoqg8hW4by5sEczMMM5S/SugXoDbgXeZSRaw6+kbR3Pqr2lGhzMjYM5/Fn4TkjEyizdQKKUuyYtTZNFptye1nnpNqkZcUNk1fMXqrHtsJclx2qDJtxlXsuTq7IE7G62fbsPWOxEj3xA9o4yGsjCcWaovBq0uOebCYtm8zaEQmDU="],["uiYunHwBCdi0VtPxsKB0kCtcIRYlu9JSWPBbvs3z9LbfXKKK5ijLoteihmgPFsfHg0ou6VVOIa7mBCzqKm2YmIm3B4dLgVCknrQkhhdiqHvC7UFQjrHAESKPGhPEorl+4KRWJWZuG8H2kS06O3RXooyRGTP0nIyw3FY5trSX2+Bx4qkyEmxUSLghoE5ZNnB9XPnnJzRcVY0Y2b4KeYTmlHobO2+5hybxmDFGbu7DUtlnXQ5kSBkYwepjB5vzOylIalQgz7xwUjKiNxAw","cEXZ8ym1YqbMNAMo32pysCi3uQWeeVn1Yukk4WM/Yp7o2gGxQdk4QnS2s/zRrQFSEz3l5cW8hCrwIynyu9O5QHkx3zQL5i1kNu6fStE8FMl4lbQGAcfTcjssZ4/Mo3o0G2EguF+pnOaYpkhDVZMf/PjcKut1xS0mUFhCWKz01CEqHDJ8Z8C7e8t1/C3lyb1jHELBuy2WflwcOZWLQECBIo9Yz57tsVcd5fOQ4kDEi3Ua7lZVxl2WrTTxZ7765mKcqDXnsRWDIXG9dJ0AUo3MyUQsIqpkfDZuQ9y21YhVWFko/oHQ+spSupCyHk80VZUoUyGnOGXzc28JkSMa","gx1YQ2mrSgGdaFlflDNpO7QRzPi3rYalEu5pI0kwGHXM/P1Tu3KYKtVZuAkdze+DH+5CchKq+PBR+sDmPKP6+hlRiO+DiB2br63Vv+DUpc/GnfmsACMmCz1oYEnQ5QP+PqILNfF+hE6T8A/ij55yNtFZtM49Hc1F0gpGQWklVrN5v1a9cHjQEk96U8y7ltfDjydP0+qKFaBOUF64Rudwm759MdpueGEy2l3RtrJJKh+3scptfPGb+xGmBHgoRtkVu/Gq4eKBbja8Q1kO5+nLf9WP9KUZVglowdYFDShoBPRujZi87A3Icn130qw=","6moAauUGuJtoANuXZbs+0Xph/GvijGITzclmuPSpIlGUrDVPZ2LZCjTfAf8JD6m5qErhHYEb/D0O28ivAe3MC++g5rpfJvNUMnjDdmDE5ZvfekYkcPuqbEvlI6H/8n8ZhMBuxGZF3D+3qc3vkQLAFfl11GcDHDBwrLDIkE5dU2tyaXwKk+LQRZxjbURMyk9wrTFzatfyXi5ON6PVIxY3lkqmXwnxVDXI8dsrI+Hok/BBPAGdGc+BxbpVNavondetxAiixtQuvr2uXqKDTKWQxY3qnWKgcxznBXczNxyKUmf4/iYIy3Si2LsFxRp986b3kCupRvc7h+DFAbo7HUCFs6Xg2hVBfdeHS1C4n3Lo0Jgvz9PM5E9iR5+8xAOm/vEeWSAQFGPAU6aaE1q1p+CFUOIv2Mmj8B9gRiICkgbo57NW0RruOCRb4KOe7t6gFOYQsnSkDqfjyInmf/vs","G3wxzoWihp4rjkp2zxlNR5ly2ksgE1OTY5tXlfJFxLOCEhaJebsLeECJ7iIHvlG8o89Yb7BHzohgHS7KT1/rR+QYbLd8T+XwgOUtFyAExkD8EDSI9CLZ+ZICwOGCJAwtXwANYeQ26r94cUmjWImf1intp/U7hDoezD4FEe1hqwZuxq/RbwXXazy8nGNWQYb+Xzpw6Wk60tseSmsCVE9YbmKawZ4A+xYuVK0KHJpuDhrjKV9l+MM7WPVU/vg="]];
                        }else if($packet_id == 31 || $packet_id == 42){
                            if($packet_id == 31){
                                $betline = $gameData->PlayBet;// * $gameData->MiniBet;
                                $lines = $gameData->PlayLine;
                            }else if($packet_id == 42){
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                                $lines = 1;
                            }
                            if($packet_id == 42 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                                $slotEvent['slotEvent'] = 'freespin';
                            }else{
                                if($packet_id == 31){
                                    $slotSettings->SetGameData($slotSettings->slotId. 'GameRounds',$slotSettings->GetGameData($slotSettings->slotId . 'GameRounds'));
                                    $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount',$slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount'));
                                }
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
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                if(isset($gameData->PlayBet)){
                                    $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                }
                                if(isset($gameData->MiniBet)){
                                    $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                }
                                
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', $betline);
                                if(isset($gameData->MiniBet)){
                                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lines * $gameData->MiniBet);
                                }
                                
                                $slotSettings->SetBet();        
                                if(isset($slotEvent['slotEvent'])){
                                    if(isset($gameData->MiniBet)){
                                        $slotSettings->SetBalance(-1 * ($betline * $lines * $gameData->MiniBet), $slotEvent['slotEvent']);
                                    }
                                    
                                }
                                if(isset($gameData->MiniBet)){
                                    $_sum = ($betline * $lines * $gameData->MiniBet) / 100 * $slotSettings->GetPercent();
                                }
                                
                                $_sum = ($betline * $lines) / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '689' . substr($roundstr, 3, 9);
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
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance() - $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'));
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                                $result_val['PlayerBet'] = $betline;
                                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                                //$slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
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
                                $result_val['ExtendFeatureByGame'] = [["vame"=>"AccumulateUpCount","value"=>$slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount')],["name"=>"LevelUpCount","value"=>3],["name"=>"CurrentLevel","Value"=>$slotSettings->GetGameData($slotSettings->slotId . 'GameRounds')],["name"=>"NextLevel","value"=>($slotSettings->GetGameData($slotSettings->slotId . 'GameRounds') + 1)]];
                            }else{
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                if($packet_id == 32){
                                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                    if($slotSettings->GetGameData($slotSettings->slotId . 'NextRoundAction') == 1){
                                        $slotSettings->SetGameData($slotSettings->slotId . 'GameRounds', $slotSettings->GetGameData($slotSettings->slotId . 'GameRounds') + 1);
                                        $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', $slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount') - 3);
                                        if($slotSettings->GetGameData($slotSettings->slotId . 'GameRounds') > 5){
                                            $slotSettings->SetGameData($slotSettings->slotId . 'GameRounds', 1);
                                            $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 0);
                                        }
                                        
                                        //$stack['ExtraData'] = [$slotSettings->GetGameData($slotSettings->slotId . 'GameRounds'),$slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount'),15,0];
                                        $nextRoundAction = false;
                                        $slotSettings->SetGameData($slotSettings->slotId . 'NextRoundAction', 0);
                                    }
                                }
                            }
                        }else if($packet_id == 44){
                            $slotEvent['slotEvent'] = 'bet';
                            $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
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
                            //$result_val['udcDataSet'] = ["SelExtraData"=>[],"SelMultiplier"=>[],"SelSpinTimes"=>[],"SelWin"=>[],"PlayerSelected"=>[0]];
                        }else if($packet_id == 45){
                            $slotEvent['slotEvent'] = 'bet';
                            //$betline = $gameData->PlayBet;// * $gameData->MiniBet;
                            //$lines = $gameData->PlayLine;
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
                            $result_val['TotalWinAmt'] = $stack['TotalWinAmt'] / $originalbet * $betline;
                            $result_val['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                            $result_val['NextModule'] = 20;
                        }else if($packet_id == 43){
                            $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            //$slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                            $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            $result_val['TotalWinAmt'] = $stack['TotalWinAmt'] / $originalbet * $betline;
                            $result_val['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                            $result_val['NextModule'] = 0;
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
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        // FreeSpin Balance add
                        $slotEvent['slotEvent'] = 'freespin';
                        $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                        $lines = 1;
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
            // $winType = 'win';
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);

            if($slotEvent == 'freespin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
                
            }else{
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, $betline * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet'), $slotSettings->GetGameData($slotSettings->slotId. 'GameRounds'),$slotSettings->GetGameData($slotSettings->slotId . 'FreeIndex'));
                if($tumbAndFreeStacks == null){
                    $response = 'unlogged';
                    exit( $response );
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $tumbAndFreeStacks);
                
                /*if($packetID == 43){
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                }*/
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                if($packetID == 31){
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $stack = $tumbAndFreeStacks[0];
                }
                
                //$stack = $tumbAndFreeStacks[0];
            }
            $isState = true;
            $isTriggerFG =false;
            if(isset($stack['GamePlaySerialNumber'])){
                $stack['GamePlaySerialNumber'] = $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber');
            }
            $stack['GamePlaySerialNumber'] = $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber');
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


            //$slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 13);
            $newExtraSymbolCount = 0; 
            if(isset($stack['SymbolResult'])){
                $gameRound = $slotSettings->GetGameData($slotSettings->slotId. 'GameRounds');
                if($slotEvent == 'freespin'){

                }else{
                    //if()
                    for($i = 2; $i < 5;$i++){
                        $tempSymbol = $stack['SymbolResult'][$i];
                        if(str_contains($tempSymbol,'W1') ){
                            $newExtraSymbolCount += 1; 
                        }
                    }
                    if($newExtraSymbolCount > 0){
                        for($i = 0;$i<2;$i++){
                            $tempSymbol = $stack['SymbolResult'][$i];
                            if(str_contains($tempSymbol,'W1') ){
                                $newExtraSymbolCount += 1; 
                            }
                        }
                    }
                }
                
            }


            if($newExtraSymbolCount > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', $slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount') + $newExtraSymbolCount);
            }
            $stack['ExtraData'][1] = $slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount');
            $stack['ExtraData'][0] = $slotSettings->GetGameData($slotSettings->slotId . 'GameRounds');
            
            $stack['ExtendFeatureByGame'] = [["name"=>"AccumulateUpCount","value"=>$slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount')],["name"=>"LevelUpCount","value"=>3],["name"=>"CurrentLevel","value"=>$slotSettings->GetGameData($slotSettings->slotId . 'GameRounds')],["name"=>"NextLevel","value"=>($slotSettings->GetGameData($slotSettings->slotId . 'GameRounds') + 1)]];
            if($slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount') == 3){   
                /*if($slotSettings->GetGameData($slotSettings->slotId . 'GameRounds') + 1 > 3){
                    $stack['ExtendFeatureByGame'][3]['Value'] = 1;
                }else{
                    $stack['ExtraData'][3]['Value'] = $slotSettings->GetGameData($slotSettings->slotId . 'GameRounds') + 1;
                }*/
                $stack['NextSTable'] = 1;
                $stack['SpecialAward'] = 1;
                $slotSettings->SetGameData($slotSettings->slotId . 'NextRoundAction', 1);
            }

            $awardSpinTimes = 0;
            $currentSpinTimes = 0;
            if($slotEvent == 'freespin'){
                $awardSpinTimes = $stack['AwardSpinTimes'];    
                $currentSpinTimes = $stack['CurrentSpinTimes'];    
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
            /*if(isset($stack['FreeSpin']) && count($stack['FreeSpin']) > 0){
                $freespinNum = $stack['FreeSpin'][0];
            }*/
            if(isset($stack['AwardSpinTimes'])){
                $freespinNum = $stack['AwardSpinTimes'];
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

            $result_val['Multiple'] = 0;

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
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
                }
            }            
            /*if($packetID == 42){
                //$slotEvent = 'freespin';
                $isState = false;
                if(isset($stack['AwardSpinTimes'])){
                    $awardSpinTimes = $stack['AwardSpinTimes'];  
                    $currentSpinTimes = $stack['CurrentSpinTimes'];  
                }
                
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
                }
            }    */

            if($packetID == 44 || $packetID == 45){
                //$slotEvent = 'freespin';
            }else{
                $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
                if($isState == true){
                    $slotSettings->SaveLogReport(json_encode($gamelog), $betline * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet'), $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
                }
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
            if(isset($result_val['SymbolResult'])){
                $proof['symbol_data']               = $result_val['SymbolResult'];
            }
            $proof['denom_multiple']            = 100;
            $proof['symbol_data_after']         = [];
            $proof['extra_data']                = $result_val['ExtraData'];
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

            $proof['l_v']                       = "2.4.34.3";
            $proof['s_v']                       = "5.27.1.0";
            if(isset($result_val['IsRespin'])){
                $proof['is_respin']                 = $result_val['IsRespin'];
            }
            if(isset($result_val['FreeSpin'])){
                $proof['fg_times']                  = $result_val['FreeSpin'];
            }
            $proof['fg_times'] = 0;
            $proof['fg_rounds']                 = floor($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') / 8);
            if(isset($result_val['NextSTable'])){
                $proof['next_s_table']              = $result_val['NextSTable'];
            }
            
            $proof['extend_feature_by_game']    = $result_val['ExtendFeatureByGame'];
            $proof['extend_feature_by_game2']   = [];
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
                $sub_log['game_type']           = 50;
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
                $temp = [];
                $temp['pick_no'] = 1;
                $temp['multiple'] = "0";
                $temp['game_type'] = 777;
                $temp['win'] = 0;
                $temp['proof']['extend_feature_by_game2'] = [];
                $temp['proof']['extra_options'] = [];
                $temp['proof']['fg_rounds'] = 0;
                $temp['proof']['fg_times'] = 0;
                $temp['proof']['jp_item_level'] = null;
                $temp['proof']['jp_item_selected'] = null;
                $temp['proof']['multiple_options'] = [];
                $temp['proof']['next_s_table'] = 0;
                $temp['proof']['player_selected'] = [0];
                $temp['proof']['spin_times_options'] = [4,6,8,10,12];
                $temp['proof']['win_options'] = [];
            }else{
                $log = [];
                $log['account']                 = $slotSettings->playerId;
                $log['parentacc']               = 'major_prod';
                $log['actionlist']              = [];
                $log['detail']                  = [];
                $bet_action = [];
                $bet_action['action']           = 'bet';
                $bet_action['amount']           = $betline * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
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
                $wager['seq_no'] = $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber');
                $wager['order_time']            = $currentTime;
                $wager['end_time']              = $currentTime;
                $wager['user_id']               = $slotSettings->playerId;
                $wager['game_id']               = 117;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = $betline * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
                $wager['play_denom']            = 100;
                $wager['bet_multiple']          = $betline;
                if(isset($result_val['RngData'])){
                    $wager['rng']                   = $result_val['RngData'];
                }
                
                $wager['multiple']              = $result_val['Multiple'];
                if(isset($result_val['TotalWin'])){
                    $wager['base_game_win']         = $result_val['TotalWin'];
                }
                
                $wager['win_over_limit_lock']   = 0;
                $wager['game_type']             = 4;
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
                $wager['bet_tid']               =  'pro-bet-' . $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber');
                    $wager['win_tid']               =  'pro-win-' . $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber');
                
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
