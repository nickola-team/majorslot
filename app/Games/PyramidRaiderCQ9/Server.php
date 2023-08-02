<?php 
namespace VanguardLTE\Games\PyramidRaiderCQ9
{
    class Server
    {
        public $demon = 1;
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
                                array_push($betButtons, $slotSettings->Bet[$k] * $this->demon);
                            }
                            $result_val['DenomDefine'] = $denomDefine;
                            $result_val['BetButton'] = $betButtons;
                            $result_val['DefaultDenomIdx'] = 3;
                            $result_val['MaxBet'] = $slotSettings->Bet[count($slotSettings->Bet) - 4] * $this->demon;
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 300000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = [["name"=>"IsExpandingReel","value"=>0]];
                            $result_val['ExtendFeatureByGame2'] = null;
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
                            $result_val['MemberSystemInfo'] = ["lobby"=>"","mission"=>"","playerInterfaceURL"=>"","item"=>["freeTicket"=>false,"payAdditionCard"=>false]];
                            $result_val['Tag'] = ["g"=>"112","s"=>"5.27.1.0","l"=>"2.4.34.3","si"=>"58"];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [
                                [
                                    "SZpzi9v4YEveZKRA/rL+GvCkUMIAroSb1DFJ8n6b84Ob0TuZMDp3pZlhLBTqEI+MnO/zb0I6EZP2HvNtF7oGab4KqU5tDZHlnDdht6ejgqxOii9vSGTIgrVVQDgZy9KtppHk05skF8Ls+UitKQjkw3oc/iI4gFyVTE3klztoPR/uT66PneVyf9GMHnRXLSNx7vJ5fnMr73pWo5FD",
                                    "2meVYbhSKdF4kb5kdcEcPjrz9u5kvqvfDinhzVGXnmgw5TmA4V/nfORt0PK0Cpgjs5BF5EOV79g3TapY3S28cO6fdyyo0PI+F5dVbAIMZGavMT5s3XjEb9E0LVDBvL86wdbYTK0LaH3zYdF/SgKQZzeOeh6bKVNg/s8crfvsL9dC2czLHLLr2XTD+96pzbXN77TRvpMW6tEIWTdJ",
                                    "SkXMqOY1fVaScN3ceCmUbWBTLvTRzJYHCjsPt8xcLF13u4TQohMLnXuvAFiLjKsinAqqRWnpBz4IPr3wpnY+20Xzd7COWHgMpFZDEw1mrUOXm2o/76ZcDpiDN+MTTEA0Ax9kYjgUCKigIEKQtwnnjnpqNKjSkdTeW3rK8mpbbTeda96p0sIqhQRf+1O5zREmm9oDMNCOAp1Z2jD8",
                                    "THQ6MsbPHBlJtMbequZSAKg2Frf/PBYVHCcsPru3dFCtwIrNog+MQuFzyEyPp67EmZiVvC7obhcC0mFHZR27Ded0oxmWu36iZPHm1FzNN4+pmEU2oXharbA7UfphYUaFHHMpbWV7k5zVNCXzufF+voKwGzT/YhXuiFGzkz42/igUrAcuQSoGdqo7/K9AMaM1Od04auZXod/mA1+nQiLujStCQKeuYSk8Q7YG0Q==",
                                    "8GIbDwrEUDotRcUUFxngPKAi7clxayfuoqOFKJEkLTJUx/xH6G+JPs68w+bkicYm7tySj/h5YLpRAUSvaqsmWkIh8ZxjHzu0xM27FbHIc+uodhdpSmYPRc5FxOVEBB80PR39tI8rWazzgz9j6Z4Bf23DkAuA+vIoumXA+lPCB8JsoBJIdthRA5RZEW+K/S/y74CuNx9pQB6lBVhvbx+O6DIPMW+KQycIQlF27Q=="
                                ]
                            ];
                            $result_val['FGStripCount'] = 4;
                            $result_val['FGContext'] = [[
                                "mSquakxp20BC6jm1Q6Uue66+TObNa9yrfBAvxuEors+jkgDMyWe+pHwiV0h6UE7epJ2OGKbvPOvs8JxMU2JhtUw/M9VEqXt6zh5xdYChXlpp9LXadKiYcU3aVdKIGIB7Xz/qRddq0x7RjbKG",
                                "2eSt6cLHWuUo9hMcC5ZhFmd9RQYihPpWeMD2Y3CKQ5ojL0ZW7TGZ5mVAjcSUpZZ26R4BN5orYwpxzuPDCWUsbmTxA/xr5YyKCEBBP6EmuC4fuo+YyKFIosPhhNJnLZgs9/V/r1RtxItvORM4TQC7L4cVlnu6CCZJ+y6wKk+zxFmmPJ+BVGT52Yum+7XkhIcsWTqy3+GMtpy70RbB",
                                "FivmG9pK4ACE0Ohx9NM7fLHMYJlhOzhtj8Gq744R2vlmTtsZVtA47su9ATiClXymt/rqpVKq92GZYnOXXdr07U4+l18zfkO5wXS/kfjBBfdrHLWFxPmmxdfCTXdLNns1kXhgUrpC+uwPN0i+oz4MoF22JwAxL+/9gtd9kdGqaEsnwG0QdGGMQe3Ae4Ll6ElDZ9i5rOTRvQn9gIJc",
                                "VURFz9hingb5ZdxLlj/fxwk0N3p1qnm+wHpXDjpVHooGoRwS7iX+UDmr+TReCIIySNeFubqfKSLCtkHBf4MvmoxU8eNfybMYsny5JQJDD/kfohYV2Ixkm+QTxClBvxbVh4JrOXUwFDbglhQV2zNROG0og2PBBRrvBO4KEBpYyWw1oLA4uQegw8GxQKCn2RtJUHgi0jNMCjoWvSng",
                                "jQbAJ4x1tYnP91VYXzlhk5i7353dOua97UBYkdn87kblocoYaXJ2i1mSMOsAG9ww6oHcRIzTX+e6HMU0Hc9q8ngQQnsHd5N2gPUMR/QmUDjK1JFYfjomOigA0eyunN2cX1B9qzppLuXwdUh/xGuk87zevUy3+McgnykuMH+9ejAxCbZKyXO2o4EDmYNrUA+ETON8arBRVn3ItPnB"
                            ],
                            [
                                "yGuceLVkHSe766Jo3IPt8AuE3P0nNj+0taKaVdEteDsM/7FuHYhw1sI2PAu//Q9Xsdne87NMrb/U28MVJ5gQ8wMgcGScD5gJGWv0GEsrJ+cFhOOalNqdnELIyttYEt1FG//Og6i4D8VMm18ifBw2hEEOr7aKy9GQp8yFy6Cw8ApUB7CUm5k81OdrxM0=",
                                "ESeIO2smqRQdI2lA3sjBZSrmdKjQE2Wn/XHvRp204Sx5r0VTgY/bUy+Z5l8yGr2O2BVxBpQjqswOmsNO95qLl3TZ/mfbbKOdOnTi2aKs7Hsj3wI5Juiv/7/3T5o3B7ICVb9PVaJP9WXX3WHwsi3E+zKpfd493oaZXwAGf/LSptd5Hg4m9VXxEJr6IgXEndWkNb2u79bXuY+LPH27JB7UW6LUuD1/wZMxcq/MnJVXJ8Q6r9YgWRWUNRVoHfhuZ8O4epP72nHl9HvIbddK",
                                "Csc5owC4ZgpBCYpRyrwMPdxdmsqMVGPLTajDE7dk1G2Ffof+SZ9MEsbbA+Klk7+7CjaaYnTMwJmV+bd9gsAPQ/3Tx4YMxkUSKvIZ2M+2e3k195B4e7NqLPjqV/aZ7N4KGZXfC1EOi2PYDBQsGxu5O1hDsDebsAKMJFUhkjX1eFPjQn2XyanGqLWmRaSqNM4McwAR+HI3/2oUzcNSUHHMqj9ISferJByulzHlM9DUU0kjtCwPX6l7UMcqQTPCwbkVXqJPh17zUx5vAgsK",
                                "HCVthArZHt7k0t7E8X7h7AjO4R34bAwn5NYQDLpAv1ylT2ChhLCmP57/VqNcrpyQVXqcRRWpuEj1kcLvZhlbCFnSsm9z4KzNl6uRH48iwjVTbGNXCQOySy9bj83V0pxVOMG1gE26TVv2DgGtWKHEmnkDgGlI1v3YkzZ7nOGwSVIw2YBi9bBbRgEN3cwWH54mchyA+wA4NvmWUxuWaFmbcYpIRo0DJXYmf9S9u/NJr8RLyMLxg8dZgqHySRI=",
                                "N4QPYZBiK4GTrnuVByxcLjWrHXUehgKqrAnN5P4BhvWQah/e4F4iu62b/6MVak1Rj68RAHCommoIIhsiPVYi7RJdoMxSIM2fa+TraqQ/HA23RSrGddXRFKx4kVcqgn867HOH78EZbC2SjWyKQsfTQH0CFzpZv18bXyfdfKcsar1aJxqp1+QrTHq2YMD04cju+0vz378KWkPwyE/kukC4HI/5ldSbMe/NVDwuzR//05Vc6mPJDiPm7JsPeR0="
                            ],
                            [
                                "LBdEkrsW5Z2PrR7AbNkYgD9fw0EFtkGsKreRig3HoU+NXORFXZ6Qx3J8y8WMGHqQ6QbCeu6GLcxTOVJZ1kj+PRhsuFeeyVqIklugKBMtl2oWTvXPYLwjwumlHcpEDl4zkp5g8AUXAInZn5ox6s4B2fSNxN7UTnVjMDUNcQNv9qC4gDJaIbNMNUt/DYT1S4cI+e386BmitNpETjNB6fTczHQgsigqdroquJnXE/aq4cAR6SYKioH6cxaCYkY=",
                                "SoH1Te8DaWSFWydKFHxMtNvJXsCEOXYUsRF/KlD+cDv4ea6BKsMBSRrGzW0bnFYoPLNXN84fIb3HOJYzNv/Ed09tMsKC9II8YkL1BcYplvdXtljroXBsJgoYYOT2s9FdvwQlVTRQMnsU1CxWBt60vTYFKObcwQ4n/mhbTDZo+TtQkSg8m/YAokwL3tDfEVVY0zm+RLm9Sk0uGhV8xykqkSEqe85ffgVbqrrYpgtup+D71cs293D61crUGvXO/CAtujkzvKKwhumZlptcVj5fvvgSqQXKea2CGE2kAQSTnizXcSV5y7ACN9qA8rrDAPupoOPw3emMU50lrKPg",
                                "nGlYq4ONt6BqSr0ypKHK+uCdv5dpzI2R8+cKEy6fjrxkY5eKUVLPBAo2XcfRS31NNYcGpA7gM3PU+L18Y/v9nFIaGPmdwJyTiCJTgfJtpLgN3oOW7wOdwEtczJxP2dK0lyF3kk0U4M0r70Njc9p+fCizK6gR0Wor33PJqDm3zuokUROv116FDfAHJw6KDPGo7nMC3IqAfcRUHjVx7gd20xzTw99z+sShsT6yuQCUDwpvslP7bdKx0P3QUtFPNejxRRA7FJd+u6Ia8igzAvpmnL1+TqI3oDnJI4R8/qiciw8hjVllJC9+p/kVAy6jQMZ2dDuynbc2/JJi7UYR",
                                "jZvfX7hyiCpgZKMq5zZ2f7VzZkJrbTc4QtyqsrEerCvWxyUqZBkSEwQVN6fsQFN+p2vzBo9pMETs7sHjdLp+RPUTWQkeCNanOXsrcsoXslbWKl8PC2nbDCXKAiBqZ9eWf5ZqFi+c23jrbEphzEVNExVZasxpC3ifQPHAT5r892ixJKQpn4P0PJcj5k2336mJBjTADX18so0RbUO/nDQ8eZhtgHlz+8KUAB0HF6I2CkWWah168sMZ360Wdibvh5yg726Iry1L6IxTU26t8ERXVBAkfyng04zzx+Ad64Xt3ClCobWAvaXyxiCnVD7LQgbtQxIO1gqbZrpnukt/",
                                "iOpuTHRfxMFvBO6LVFPRlIRHYiud2GWLb7RKF/aNofMOqBeNIancPvHHufj+XwmGFtwOHRnHHVUj8qVRwrXwidZ+Q1TDEqzLfij3QGu95316HCfZz3ldSHl/7p36BX41NRJmiZ5ncMGQUvoZOOSkLz7ktR1Ufu3o76zT4P8MEmn4vyr3c4hvT+Qout6jXz5twKDVh026Tpusoc2XQjexxqhCi9IRvkUhg4StxDDa3xZNiozM4MaXtxTcIBRG2UEyb3T8NGxdMNoydYfGdXcwcQAg59HaosqLUnJyUbd1AuRcDVZfwLuEzu0nAo9ZAHla0U+rOuWoDK1BuECA"
                            ],
                            [
                                "GfbxxdvNNP9WUugcGnw3EuaUbgmmsL1/CG3P/h8kuf6Qc0CopaoHh1BN2bm3MlgRpr9K/DnYVEq4qJkXURcnY5QtFbIFkz7w9uL/GjsvJupCPZGK5+PdWz36mMndgYWgHDvQRG3hcFaOlsdmSce0TaJfkpuymyXYPsZY3uBPFKAm+J1pUNuwRL5P7wB5uGCVAbuiStY5J7TMzfZAcuLgG3cBnpLgyn4qlwDLCg/abUu6IzeG5nor/vPsfeN2WgleuzlEoAdB/lBW2nZR",
                                "fwjS1xnpwOKWtkJZzqugrIk244Xv/lx0ViEsUk++xZGXXgGXQMkabf7LdQS2F9vKXwDTO/DghdMMiLbGezMYAh/eaH/Hq1+uwrUL62OEcqzrXeHKlpiTjXu6cdsbLQdsIed9KuSf3H8FcT+OkBnmFvXxYDo6HKfPwRQYQ9ogDbpVp/QRS+xSgSbtnaiZjGAP69kgEb5Nnh9RHLqyFFNFcRK6KAdP/olUzmq792KkSyLEa199pbaREym0UXKpNBTL77W9ss82Or+S/CTWcxnMGVpIm5ua5JRENOXkhQRbSS5MWw3Ly83QApXoChmixPOhNhPM8AWDN5tK9oZS",
                                "iAD9kMzdtxPa0vXWIJVrpVbAbu231eA96DlB/jpAvo5fIxGKZkjeeUvCbC+kmqRccrLEcb3Kq2WSZyYlEF7X1baEh/AQ7VABRmhQsEWzbqrWXEsa7lxrQJBJ+iDUjH3DTmtR6cwNimpOb8gsOZzZabfzRN/RDMRmmIF/G7+nG4nD/OzlcSRIzvOs3VfkOGmSjQqZsBXatzuzKK9FZhHv7EoM6a1TytruCfgpBIZXE/A9EX0k2u2NnSv5PXGrQi5fDmSx92TP50i0I8XWtmE5h3yyuRY7N+tDIYVxRi2RL24jkTkel13OTeHI4ugNKfXYeemDPhEVbmS0ehwN",
                                "go38RoRT9vQgR8fYVS2yIneRYbNxM2til7L0n/cmKBOVZr89R0BmP4ixC0/ki3a200oDp+r4y8OzZTDDImT+MjquheG9ssl7UPnksbtJ4GjEON+PR0oPXVZBaxTUCDOnLtCi+Ui9GngwhCZnath0qVBWV5NsL77l1HNPWbmny7z/O2WvYpnoHnHYBbQfoguxknsvXQN0B5S7mHEjVOVRyqHSIZky1lp8qLg6I1kx9IkXIm3XVPhW0OZSudHVnAQV2FgWy1IeVNGwCTgHxlgmVZn01Lu6AseGJuuAbki/2BrRruNP9NTnjGLq49+7ze5hVmpVULsxsFroKlkP",
                                "TPTGjEClHg8h3Kpv+5tiX5B7ET65xLapBc9nTwpy2LzImwaTF6yYop4KykI26aF2rOyBTiNVNqxxNs40Iq1E09tUJxeicYF8RIw/ewvrEa2Ua6G5IRYtiB92vxnXBUji2VEusEo+u2iPLW9R+bPLGyXFyqyarjYglil37k0Ca16lhFFamKZVBhbogNbLybndZazuSwVOo5JRmpRMkq7I8bcWGrSodZ5Al3kaTGeeF59RmDzR9UVJhAqL8QJNIeMu42+RRMtJHMiRxEwPT4nTXj6sPawpk3CnrU3FaAZln5lWimhg3802pvwILCWwUD+YlhS6l5qEcRSL1lXRkt9+HulF8YUgy5SloVVRWQ=="
                            ]
                        ];
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
                                $pur_level = -1;
                                $slotEvent['slotEvent'] = 'bet';
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur_level);
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', ($betline /  $this->demon));
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                                $slotSettings->SetBet();
                                $allBet = ($betline /  $this->demon) * $lines;
                                $isBuyFreespin = false;
                                $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                                $_sum = $allBet / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent'], $isBuyFreespin);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '6587' . substr($roundstr, 3, 8);
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
                        }else if($packet_id == 44 || $packet_id == 45 || $packet_id==46){
                            $lines = 88;
                            $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            if($packet_id == 45){
                                $pur_level = $gameData->PlayerSelectIndex;
                                $tumbAndFreeStacks= $slotSettings->GetReelStrips('bonus', ($betline /  $this->demon) * $lines, $pur_level);
                                if($tumbAndFreeStacks == null){
                                    $response = 'unlogged';
                                    exit( $response );
                                }
                                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $tumbAndFreeStacks);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                                $freespinNum = $stack['udcDataSet']['SelSpinTimes'][$pur_level];
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespinNum);
                            }else{
                                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                            }                            
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            if(isset($stack['TotalWinAmt'])){
                                $stack['TotalWinAmt'] = $stack['TotalWinAmt'] / $originalbet * $betline;
                            }
                            if(isset($stack['ScatterPayFromBaseGame'])){
                                $stack['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                            }
                            $stack['Type'] = $result_val['Type'];
                            $stack['ID'] = $result_val['ID'];
                            $stack['Version'] = $result_val['Version'];
                            $stack['ErrorCode'] = $result_val['ErrorCode'];
                            $result_val = $stack;
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
                    if($slotSettings->GetGameData($slotSettings->slotId . 'PackID') == 44 || $slotSettings->GetGameData($slotSettings->slotId . 'PackID') == 45){
                        $pur_level = 0;
                        $tumbAndFreeStacks= $slotSettings->GetReelStrips('bonus', ($betline /  $this->demon) * $lines, $pur_level);
                        if($tumbAndFreeStacks == null){
                            $response = 'unlogged';
                            exit( $response );
                        }
                        $stack = $tumbAndFreeStacks[0];
                        $freespinNum = $stack['SelSpinTimes'][$pur_level];
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespinNum);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $tumbAndFreeStacks);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 2);
                        if(isset($stack['TotalWinAmt'])){
                            $stack['TotalWinAmt'] = $stack['TotalWinAmt'] / $originalbet * $betline;
                        }
                        if(isset($stack['ScatterPayFromBaseGame'])){
                            $stack['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                        }
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        // FreeSpin Balance add
                        $slotEvent['slotEvent'] = 'freespin';
                        $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                        $lines = 20;
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
            if($slotEvent == 'freespin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
            }else{
                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, ($betline /  $this->demon) * $lines, $lines);
                $winType = $_spinSettings[0];
                // if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    // $winType = 'bonus';
                // }

                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, ($betline /  $this->demon) * $lines, $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin'));
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
                $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin / $this->demon);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
            }
            if($freespinNum > 0){
                if($slotEvent != 'freespin'){                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespinNum);
                }else{
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $freespinNum);
                }
                $isState = false;
            }
            if($isTriggerFG == true){
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
                $allBet = ($betline /  $this->demon) * $lines;
                $slotSettings->SaveLogReport(json_encode($gamelog),$allBet ,$lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon, $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
            }

            if($slotEvent != 'freespin' && $isTriggerFG == true){
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
            }
            return $result_val;
        }
        public function parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines){
            $currentTime = $this->getCurrentTime();
            $allBet = ($betline /  $this->demon) * $lines;
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
            $proof['extend_feature_by_game2']   = [];
            if(isset($result_val['extend_feature_by_game'])){
                foreach($result_val['extend_feature_by_game'] as $item){
                    $newItem = [];
                    $newItem['name'] = $item['Name'];
                    if(isset($item['Value'])){
                        $newItem['value'] = $item['Value'];
                    }
                    $proof['extend_feature_by_game'][] = $newItem;
                }
            }
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
                $sub_log['game_type']           = 51;
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
                $bet_action['amount']           = $allBet;
                $bet_action['eventtime']        = $currentTime;
                array_push($log['actionlist'], $bet_action);
                $win_action = [];
                $win_action['action']           = 'win';
                $win_action['amount']           = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon;
                $win_action['eventtime']        = $currentTime;
                array_push($log['actionlist'], $win_action);

                $wager= [];
                $wager['seq_no']                = $result_val['GamePlaySerialNumber'];
                $wager['order_time']            = $currentTime;
                $wager['end_time']              = $currentTime;
                $wager['user_id']               = $slotSettings->playerId;
                $wager['game_id']               = '112';
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = $allBet;
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
