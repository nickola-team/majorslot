<?php 
namespace VanguardLTE\Games\AcrobaticsCQ9
{
    class Server
    {
        public $demon = 10;
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
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 4}],"msg": null}');
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
                                array_push($denomDefine, $initDenom * $this->demon);
                                array_push($betButtons, $slotSettings->Bet[$k] + 0);
                            }
                            $result_val['DenomDefine'] = $denomDefine;
                            $result_val['BetButton'] = $betButtons;
                            $result_val['DefaultDenomIdx'] = 3;
                            $result_val['MaxBet'] = 1000;
                            $result_val['MaxLine'] = 9;
                            $result_val['WinLimitLock'] = 30000000;
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
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Currency'] ="KRW";
                            $result_val['Tag'] = [
                                "g" => "223",
                                "s" => "5.27.1.0",
                                "l" => "1.0.37",
                                "si" => "39"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 2;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["vaVu9nna5DBbAWUBmOKFbVPN4C0DtXnE3H3nq8/Ljd0fpoKCJyVVzrTCF/RhtP1lE4SEH96Xwds3KtIi83iNDiyOY6LNNMsq88WtPcxJoSlyaJjhmWBS7QNqWJqSXlRfIk7MeHUDLi6Ov4cu6yLt41LoVgDl6QAf6GoOFbs35WxS5Thi16n4mzyIfUw=","r2AXcjlqHRysSDq1HPqhwDmXyvIwaSy9xtDKto2b81aM8QHDroai8o28nRQwV0FIDHMRtFCDZVMcKhH9CLUSW20Kru9onhj/2Nm8jjhoHj1ncWvjnXf2AsnjzgWcEopkJa5U3CaCbC9j6kVC37NKHzovXBnTsJE09lNry0zBXBrC+NWj0CwE3QqCDLw=","VUv0AaQyd8APF1LkxKUpVvWFiI/BtRDVrPRcWd2Hn08kobx3I8FB+r+W6ShgFUPlz6cBQ/Wjq94GbOE2EolJlkc/P4WQDRersxlO4WIlDFMshfL/gyDdn1bDkoo0R/AxjhaRbgIU0zpVdcmSAw7LNCJgaHCIU1f5rHwVRiWoGEGUdyM3Ozm3fUKGJE5Xfj/Q3j5p9x5Rp4MRZgNh","F3cE6PwoNgv9wnERyT8rJKQrYQUa8zwq6LryhVYt+23up7ESojWITB9TXBJVs50KMXJK97KHTAdvszHHSsbHid8vhrCLkM4m7IrBVd8mKW8ebNxYu4t7zqqJJQs3Pwj53OSORe8OtxKTTiR1cwxDIGsOAJSWv3VA1BG6zQmlb7U8QEllBKkUc2iXN5JMJP9/ctOaNU25qjZoeEQi","y3smdfq0QqZTzpKOhw6jIxxOpOE8eOxDmtsO4wgyngd7MIqkFw0fX9OHmPe+ChHre++MM+Gj+uMmUA+qXWRVbonag/rsM14OKtjHATtP+o2XjssTFjcBK3b2hK/6nZQqMDVGb4zIrZSYskAaWm82hanaQ91d7oAJSv8QRFJ52nHBQIwsXHJ3QxhUR1E="],["KhRsLSTxiWkZOL4knkx+pkrlVVyjkzJKYepZYtTHQJEvzfYAiiC1wwxPxWFHpahlGbKdHeWlvBZ3k5cxRNu8DZIiGD3J/hkiGaADypVyPPdXCC4QmxlkbCp773bZjWA6l9dTGZxlZ7LzXjlfXOGJxzTh061OH6O4LzmijNFc8ahc3YP6IbMTJQ0u40IW5XHcm8tXfhGIj/XX4XNiUcO40kNxFWTTHeNl3hwhUYkYZiXGDzRBJu5jKJ0fwJkT6wnV5ehIPfPuSPVVaImLs2qetNjRNYfLvvx/HuVnry7wh+oRt3LREw7xBVfZy5Dl0f3xgog2fO/otSSW7WMTNZG6WBmbztZy6+QkTqj/Xw==","DestHqdKdhcphsUP02jYYp4aViBElYU31z0B7rsHW63u8Lat9VZb/Vk8wwB/+ibv7W9ULkiLxiH/yFpzSBM+kHR7PIHoe6+pEG8ADzykxzBu5X58vt18oh8qYRyI9PA7GJP89TVkyyMspSOiqDMXjAx/0HjPPQtRkN+EyJZumM9qfMMFFh5BfIJtbAVXvR2gZjuMz9CFY88NIuGpfFBEbuqgVMrQxayGsJf+71kOyUG7RPUKCQ9GPWJrCMv2xrzMdr/E6eSyRAElKl070T67tdWp7pKgBxBkkYCKloNQWZJtSWtGLFF0gOp6d0qXLXyV5H+5nOoqWj86wppp","SeX0cnDhOziaMPsfaAO/5FM8GD8SWvL6NnheaXaYNR6/odFnOAtispJ5d9t8UwmDHtX60TOi4eHL1cvMxJnBrKE//u4vfnlP4kn45xlcpXtp8yRnSz4y05TLDAsV9imcQqy7+Ukfb4jMxAc4dSqnQJ1aMICTP/NTgIwKASiFtjjqfdQH4ZgYJi+CakAI1MlJsK+ygJhTqrrvaiuui+u83iViY9vn4lF5rKOuMfNtzWhRlEho9yjTzgs5ovmnTanx55wGl+IlzSwCWDuZGWiXweF2ST+GcbjYSDhwiCKk9IJaReaKahbFYvi8H8MsgDt0uyZ5yvaWd+OBqbGjogcW2XFhbHeVvBnRFin7fQINMG+8rImrXBoZCNf+okg=","jxjTQhxy5PeqcM1DoyEh1HVP+dm+gXAr0JrUeyBcTDxZBKJ6Oc074d/APd+tfFfKCE1IDRmwGrig6ikrgO7oG4P8BqOdE8n3OsfB1wbeRidECMQtraQ3tU4nmHbTHoM9GDR7ctnCAm96Pwamdnb8Ho+Bmn1J0pMlKFJJpqFtxSXW0K6b3tgtzt3ZEjcrP1a3Z7/nmYi39l6UBsr/HqgbUDgMh5CUPEeNOco3K7CVLm/GB5krk0DAoloss/RJOhtG1rJhp4z/XJMGstlagkV/ZnEqpzd1lMd1ZstRl3eBRLMUSBGgds3DFCzguClVUnebFlb282Z+QL3TL691","r2tU6h5D0FjcU8UgJ0W4XeYtlBR8rWrVAIbHuFzEBtngI47k0uyzNDLP+SP4bvBaUimEHhrRb4xyF56brV/Wd6/CPHOhJ3RHZdlNW39AJnTQWJbjpW8zYKoABMRa9eDNqR7GGeevAx2EElcTGh7W+HUCrJnJxLafmtCDUlpVwJKmRhsUkgt6YsIZ0Ed3VGz48h4k+5N148fUJlbdkjNeZDTg8uGt2jQR/JIsqpJZlDk+L6h4KMUrLtyPGlwHAUYRtJBpTLx2eT7Nol2J232lw5IHDsFpLRkV9vW94+DWwXAEL+FkQ5vVhG12dQslb1sdlrGMOMZjvKlgaeBo"]];
                            $result_val['FGStripCount'] = 3;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["ieRFtJl7RdF45WbUm5CAPa55nePLK6F6GvaCXvtJSONEujcGJNAbZgJ7UiHdg7CsnXLmXvMOCov1pMxMD8+XvaH9dNG/IY74Opfjy6BHcrEnsdjIypWG1Wi7XbfGvu5YRyLXzYxMQL+SkUpmCCBy36yFLJPNNL5wN8fxSytXSHTGPCxB9RRZHeHU0jXOKx29XIaH3bPmIfhAN3ufkw7Q7Y7Gox9RfVRsvpczEZKqIj//ymeZf51rfIT0UJA=","nJci2yma3ZJADSx7FmnI7DwF082UGLGu3gP4Wse1OiWD4EwYtxTEUKAji0eGlL0+Hiax+ACnt6GaDR3Fqosj4eBZlsdQ/GbE6/FBAGTevzHMAU4rV+iU1l7zN12DusBexJF4FMK8gTBhKenm4Y0Y+mgn9vU53k5LUtFHRpTZVU6PWyqqLj0t8/GILNrNji2w5BjkZxsbZTAQbLTmaWAO6xZ3F/t8u3MWEq/sVO5ISr2T1rdYTiDGCa5HMQY=","x0Yx7HxrEeAqXhNYql2VQelWB/JlWk3sScv1JcVAJhNRlSmsdGEvqs8CdUd64Ms+HT/IESSPW2S07H9/sguZU0fzz041DInE8cokxTr+Qt25BmbWKLBGZLvRWDEbBoKvzpYIKqSluGwv9Yjv0hxGaemAqO7+MecGA+XnGUZ4bFKdlPF2dpXXVEdwh+bCGdyz6uwoyQVQJYWgFxkttrAN0fZKJpr6Gvvc9gNRXE61gmoRbl2h+NYd8wSk5Ow=","yCoMXoYyeWHj1ZISOG/B/jhN6celjeLUbKUfvKpOpNbLY1Dog1z+/J1mfBqoD1i33IxmTpSIkR2Ia5t1Ho6r8bvweB5wRv8ADdSxWGbOKt3/Pvk8t0Urh1xX5NbFMFnswIu9yHR/Ug64SscYkU93LX8g9vGhPO2kIeC2Hiy8xOIGvKSrULhJoSudwZjJ9mJ8H23thqgS0xPvf4UDu0ojsPzTDev4kwZybI8ozGzPgVUOczWStE7Ugir1uYM=","pmW53zqUDvzAI0YbKL8nY/IK7Kz6TSeW0sEQgkJ5sVbhuqQBGbKnWH1k1HU+jnJGDBwAJjR591Z8Y88l6qAEk3tEAoMdL3e78JZ2AUQA5LvXu5EKjtmBBgJooJGmCm/s6HZR5lFYJv/rcjfzuff3J7q5L/7Lvd2qVy9TjXDNHQygw7y/mzLaaebt7vQ3aBTFMwmwgJogI1VNfOKgZhIwNIcpbP/VB+uA7bHEVvYQVaZZ33JlpGU6WjasfeY="],["Ppd9LQkmB6NoTzzn9mXQhjkd1eOdlBwZO1QdK0RAFJK2kZpuTCu5//tdLN8cqxw43FZPNzvkzifa61mMJcbKD52O1ej609WGknO1igE/y66CKBaEETjUuJriptFCRhLh6LpRxT0ApmL/Vu2W7nYncZxgs8pSKzKMOKGNavZdzZo3rB7ZV/Z7m92Wt5kV7x66VSMG1V+yvnil9piuJ1oYyRLnpW+YdVdwdLruFgypn7AqRd0hzZcvv4TWLecrLwlcjMmZTaDD3vwIJc8Iwv0lkIoo0EYELZn267KEdsp3Zv4AtdV9PEb6S1ZZbQxFVsruyEHvnyZzgH2oDUaV/MDpZeZdtiVuF47yw3kIBQ==","XnTdoA25qGkTgCjdavZUIBqZibGVEPdioelhcWTq9laRBzIe90GzjPcE7wwZpBFRaFHPcRhcTGselN9CluYoI0YPeoX+bgQ6+s88Yvtd8fVsdUhpOBDPzljSFWQSkNxPWgieYzREoOWiDvBcyYDmBrzUkgYhRK2BU216bn+wnBDboZ7MA5CQQvSEMAJ6QVVPs3B8qMXydO4/2IFM+yZiVBxYMwITClYS6Gril/tj+yKVy3XzLsJZKS/Gr7xnv5KwLc1HheQ3lUpTWT/+2yJ9nbVOmc7F1TVKUqVTkKWcyyJcXph1Ast4fqAoBE8ultbdSLLGeQ8d4RyZsHTybvWSnbyd8yaIg8IpTSM2ag==","iJM2MlIyxyOzhKYiYjaHHfKPbjwYZ4Jz8wfBWAKEy6nHO4VIGNWdmv2EK4DGuXwpwdRB5NvqJFjSQ8lUb/d/tm57fQY/iOz7HOoNbJvjCpYo2k3ITfwt3cfTN7aH1zMeOcyOjQIEa/kWoFMI+08VCMeV5v0cGzbPShhA36rnG2FG6igQ9bGjEw6JwWKvZLdpgXhOn4dthSZZYUIhKZMd5nAHEPrRgcBM3UfUxgqWl+hWgYN3tYd7dcXLEifweoTf7Un8k0uOS/CHUMvLxotZ469xiExBQ678lypCqaDJtqDHbAAoUBFjUiO5LvmCxcLuvDMVJ8BNVdacC0k9agEOnklIquB+NG/hbw7RYA==","VEkerjhiUv7fDtkkqRGWEnFSR6WsKRVXJCFhuv70b4DGUjebhkwY3NdxiGiP4IMRkbzRHmoSS0injJHmql3TnujixFgsx5LlzBNGocO9K2tRVbsQ8CMuTTeYUK1qxuUN++vSoWo/EU7F73A2jIDBOZ+eai1lvAMs6np0tkzMpFSrhDn6tipUwCupmGsycZiUxGfDjiYa6/6kUPaUqN9YwsC9va2lphVArpiRRmaO7FG5bDEm4IjxvEzKDZjN3ztk+qOYKZd4/r6SgsTcx8i3/P7hvt2zaz2QDKuHlT5O66bBnEu71qZGbvTQ6ofbOV5hmGD7bZFvNR+Xs5drOiiqODEszmFrmAfu91ZAvw==","PcU1Mtpdhi4i84vDEAw8i/Sd6vz0DytCKCQ48CPYV7AlNsuLSEL70ixiPlBPzbbzLjMTm8cm2srAZnojarYDRRm1xTXFaFmi+mGcby3mipauikCMxlEy7x4sKVLSRrfO8gYz3ZVGZ5XzVA4eS32cxD68zOmpxlIj2viTv7Z3ggyqviTuj8B8A6DlieQBmNc+9bN0wXzp63EMZL+UEwJkA+hCh1fAPH6vwPW/I9YZihF0j/lldkc/QNb1frrOhlavoNi5HaAv6GzoZmv9B2w1fbjX5XkDJK0226qDnXd4/JWPkcoaKAazG6fLqCtvALN1oKPO7KbYbH9u27PROC5yeCBuQVH/R27sSHrRhA=="],["cSbNUCBEU9oHYz5S5hDq+8PyfQu80Irl1sq234rl2k8kyJMGwsbJNis1lApIRSI7Hl6x9RoiXkSnGbRzcQWvs7A8tMH6RoEpZ8yRy/PM/QUmU36etgyzOYehoE1kxKbdKjTTWAhxFLdeL0r4k1hGJRadFWquqRXqEIJBt7MNrCwE7natz2VZw/dCUu543yHkfYvVHVONxPxHNrHLrogRwyoJ9KIhRuU0FwIJZOFg+sAthLBUnvWERxrhID9beRGr6U8esPjgiUwyinvKA6SXE9aLzM9Ar7tYuTMeHg==","wFoynYuyovJJvmwLHNzFxiQds4f9Ho0Ro/7XlYrfM+NkxYAFaReP93sPJmWLyU9py0Z4T+VyJ8NvUUx7jtwUr0r3dW3HvtxIlDyojuHNbYsjWPprYQjFog1BwNpVPsJ1mK/0pFMxaHIfWpzvQKxAZQNsJHX5kimVZEGvL3YZIK/ZmeeAoci3obykCMl1X1nJlQM8ExsydY/oZQlpWvxTXv6kNpKUedlMgsU6w6vfCwPxGPhLhgzEC+N5xCT1AZGRuNhIib1AkyO2ot7UyNjcs8o5XTFeRiPj+ZIAyw==","N9F1T4Od6ybQOf67x+NJnWCWpFvLMLEKDIn7TmwAeHoB2HrCnFvnOmhkngjNIX1iufG+vJeRxBeMFxVmkpjwEnBDNUMgd5WvH9xX1Ym09DYAfWDz4uXWCsmMvlS4nigLvUYawzaVOmtIY+gDLCxuhWgV4vhUorAZ3hjRVrAiwmuXbcsMDETsoXNisF6hoTJXEWzgXa3mSmvvfhmDrsTUNZLMCX/6ctOoiQq+Y9F5NlyUfijdRpatEV1a8WLvAw10uz8ECBO3wYkxnfICaCnpDxeh62nUtIIVsbJbXvnC2RRBgv0/+RnhAgwCXjwIZJMJBj7c4AAx/DFh4uXSipEvIbUDu6xAODvjTRfOhg==","iFt6FzcQZt4njcQnh7K+jipggm2AMFxl5SzzWsjBtehnl098xwaRemme06oLdgvCThJwWMbHpkB63fKngp6hUBv3tMQv0TtSIncc5kM4VXlhceFe98BiYVPT2pS8q0UHRy4d48fln9n+/Y0g0zePW6hHNcg8oLST17TvWdNUWD8w5wOWmnehPG0EozQydEoI5HhSOxkAwhOXtpso/2cAkCsylvCKFiKrcr+O0hD2QzyMd8RhgYHdTUHdHz1wrB7LBxoqh3mrdMzwS/Yy","K4glHorbp26ixkRebuq/KIQYOhcFnylZs4+0XYYaDH3uGnewhUviF6shPi+V1F6JY7fN1ZTvbh5RI2gTSJf5QKkJyOhnloQFgX5Cw80GwWnD/7HGSr9BKgRyF05HCBu08giAxSqegXgNimKyZY0jpuiV8Xg7fxCKTY2SjaQKilDW1ToV1MX8lsYaTjfvuUCNKE5MDlPq/A3C69F49cvMNz+hZ8wqTElpsTpjPv4veCipzx8TTtAQtuNwlfgDAY0azfqTHth+YSiw4C5gDUr2C9tnVhKuYk4ejLD/Bw=="]];
                        }else if($packet_id == 31 || $packet_id == 42){
                            $lines = 9;
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
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                if(isset($gameData->PlayBet)){
                                    $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                    $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                }
                                
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', $betline * $this->demon);
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lines);
                                $slotSettings->SetBet();    
                                $slotSettings->SetBalance(-1 * (($betline * $this->demon) * $lines), $slotEvent['slotEvent']);
                                $_sum = (($betline * $this->demon) * $lines) / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '656' . substr($roundstr, 3, 9);
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
                                $result_val['AccumlateWinAmt'] = ($stack['AccumlateWinAmt'] / $originalbet * $betline);
                                $result_val['AccumlateJPAmt'] = 0;
                                $result_val['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / $originalbet * $betline);
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
                            $result_val['TotalWinAmt'] = ($stack['TotalWinAmt'] / $originalbet * $betline);
                            $result_val['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / $originalbet * $betline);
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
                        $lines = 9;
                        $count = 0;
                        while($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                            $result_val = [];
                            $result_val['Type'] = 3;
                            $result_val['ID'] = 142;
                            $result_val['Version'] = 0;
                            $result_val['ErrorCode'] = 0;
                            $result_val['EmulatorType'] = 0;
                            if($slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') == 1){
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            }
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
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, ($betline * $this->demon) * $lines, $lines);
            $winType = $_spinSettings[0];
            $_winAvaliableMoney = $_spinSettings[1];
            // $winType = 'bonus';
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);

            if($slotEvent == 'freespin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
                
            }else{
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, ($betline * $this->demon) * $lines);
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
                $stack['BaseWin'] = ($stack['BaseWin'] / $originalbet * $betline);
            }
            $totalWin = 0;
            if(isset($stack['TotalWin']) && $stack['TotalWin'] > 0){
                $stack['TotalWin'] = ($stack['TotalWin'] / $originalbet * $betline);
                $totalWin = $stack['TotalWin'] * $this->demon;
            }
            if(isset($stack['AccumlateWinAmt']) && $stack['AccumlateWinAmt'] > 0){
                $stack['AccumlateWinAmt'] = ($stack['AccumlateWinAmt'] / $originalbet * $betline);
            }
            if(isset($stack['AccumlateJPAmt']) && $stack['AccumlateJPAmt'] > 0){
                $stack['AccumlateJPAmt'] = ($stack['AccumlateJPAmt'] / $originalbet * $betline);
            }
            if(isset($stack['ScatterPayFromBaseGame']) && $stack['ScatterPayFromBaseGame'] > 0){
                $stack['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / $originalbet * $betline);
            }
            $awardSpinTimes = 0;
            $currentSpinTimes = 0;
            if($slotEvent == 'freespin'){
                $awardSpinTimes = $stack['AwardSpinTimes'];    
                if(isset($stack['CurrentSpinTimes'])){
                    $currentSpinTimes = $stack['CurrentSpinTimes'];   
                }
                
            }
            if(isset($stack['udsOutputWinLine']) && $stack['udsOutputWinLine'] != null){
                foreach($stack['udsOutputWinLine'] as $index => $value){
                    if($value['LinePrize'] > 0){
                        $value['LinePrize'] = ($value['LinePrize'] / $originalbet * $betline);
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
            if(isset($stack['IsRespin']) && $stack['IsRespin'] == true){
                $freespinNum = 1;
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

            $result_val['Multiple'] = $stack['Multiple'];
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
                    if(($stack['AwardRound'] == $stack['CurrentRound']) && ($stack['RetriggerAddSpins'] == 0)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                        $isState = true;
                    }
                } 
            }
            

            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
            if($isState == true){
                $slotSettings->SaveLogReport(json_encode($gamelog), ($betline * $this->demon) * $lines, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
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
            
            $proof['denom_multiple']            = 10000;
            $proof['respin_reels']              = $result_val['RespinReels'];
            $proof['bonus_type']                = $result_val['BonusType'];
            $proof['special_award']             = $result_val['SpecialAward'];
            $proof['special_symbol']            = $result_val['SpecialSymbol'];
            $proof['is_respin']                 = $result_val['IsRespin'];
            $proof['fg_times']                  = $result_val['FreeSpin'];
            $proof['fg_rounds']                 = floor($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') / 3);
            $proof['next_s_table']              = $result_val['NextSTable'];
            $proof['extend_feature_by_game']    = [];
            $proof['extend_feature_by_game2']   = [];
            foreach($result_val['ExtendFeatureByGame2'] as $item){
                $newItem = [];
                $newItem['name'] = $item['Name'];
                if(isset($item['Value'])){
                    $newItem['value'] = $item['Value'];
                }else{
                    $newItem['value'] = null;
                }
                $proof['extend_feature_by_game2'][] = $newItem;
            }
            $proof['g_v']                       = "5.27.1.0";
            $proof['l_v']                       = "2.5.2.76";
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
            if($slotEvent == 'freespin'){
                $log = $slotSettings->GetGameData($slotSettings->slotId . 'GameLog');
                $log['actionlist'][1]['amount']     = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                $log['actionlist'][1]['eventtime']  = $currentTime;
                
                $log['detail']['wager']['order_time']   = $currentTime;
                $log['detail']['wager']['end_time']     = $currentTime;
                $log['detail']['wager']['total_win']    = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');

                $proof['lock_position']         = $result_val['LockPos'];

                $sub_log = [];
                $sub_log['sub_no']              = $result_val['CurrentSpinTimes'];
                $sub_log['game_type']           = 50;
                $sub_log['rng']                 = $result_val['RngData'];
                $sub_log['multiple']            = $result_val['Multiple'];
                $sub_log['win']                 = $result_val['TotalWin'] * $this->demon;
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
                $bet_action['amount']           = ($betline * $this->demon) * $lines;
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
                $wager['user_id']               = $slotSettings->playerId;
                $wager['game_id']               = 223;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = ($betline *  $this->demon) * $lines;
                $wager['play_denom']            = 100000;
                $wager['bet_multiple']          = $betline;
                $wager['rng']                   = $result_val['RngData'];
                $wager['multiple']              = $result_val['Multiple'];
                $wager['base_game_win']         = $result_val['TotalWin'] * $this->demon;
                $wager['win_over_limit_lock']   = 0;
                $wager['game_type']             = 0;
                $wager['win_type']              = $result_val['WinType'];
                $wager['settle_type']           = 0;
                $wager['wager_type']            = 0;
                $wager['total_win']             = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
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
