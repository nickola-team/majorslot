<?php 
namespace VanguardLTE\Games\GoodFortuneMCQ9
{
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
            $originalbet = 2;
            $slotSettings->SetBet();  
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 12}],"msg": null}');
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
                                array_push($betButtons, $slotSettings->Bet[$k]* $this->demon + 0);
                            }
                            $result_val['DenomDefine'] = $denomDefine;
                            $result_val['BetButton'] = $betButtons;
                            $result_val['DefaultDenomIdx'] = 3;
                            $result_val['MaxBet'] = 2500;
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 300000000;
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
                            $result_val['DenomMultiple'] = $initDenom * $this->demon;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = [
                                "g" => "133",
                                "s" => "5.27.1.0",
                                "l" => "2.4.32.1",
                                "si" => "36"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 5;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["J4cMxevCu3fRdubHSmyf1+yu4rXWuv0u7vMEHTDSbO1pHWABAnL+Ej5NCzuMXfjoD0B5o4CkHXHyJUDlB4yvBTlnjNRiW4YQhecIQBCroeYyQiStAUORQeIIJEkPLHuO1jrGWhVLLZguzEQDPvh7Qafhvhn5v/UWpd2io7GJySRusxyhIxUh9tMsBUjE1aOaYCw4B++nGjwzln0VxoiYrkv3eni2BPCwzMESTeN5OXSjJsrK9jzqxg1ebvM=","cGluMKlxL0pvCZmTvqLuh9abDt7q77oIDf+F/132xJvRATzXYg9Xacmb5dN0uIWs51m6bhaT6eri3hof4fY4xcvsFZtYSQUEKfubo+Hak2ECrser1Lt1kB2RBETfRwP1c1rnUBaFeHlr7a2Wq/Gs0RRMckelbB30Jn8yal9qe0TkfWBJ+FEpzxHOT5iwzOWGblPSiJCTT6UHRMVQJQ5zEUrIuszztJJLPMqjbzWIgD8pszGkspdv1TTnJI2gzuDADmuvxLjUQs6S1n8N","8pNvqgLD8zRSCnM9j4C+BtvV6A5LhPY/CNMhVbhXwE/6MaooUt1qUR6nhWnvXmjwQ0eFmFhDIcrlE4XVS4u1ZVgZOcRwUmpnMJgEclvhQwZ2ehKvLrClZsPCxnewEivW7UR4l6lFJAkUBzccKpjDhxuKn2CA+mSeROHM1yOzHdfORXs+/ksKxflhRUyuO5TR6grvCtVGO4KOuDLYUL0Pbatc07y0+pyu1SiQMvkTczny7SzXlnUGhPXD2WEPLeD1X8OFoHFBxaqtMTvq","SPX6WeZj6YcJaxsmonFZGb18gH6CV770uhXzhgsMvJqDZbQcjGAqXwZg0nQYYR3As/I650BpN0Z8PzB5qX99jcqT6PoGSGAY1dQi8MPVrvSzZyWWQnb6dc4jSAjoLInBj9TeUGwkt3wI25MC6Q0bO89RRcdmUnkTb0T7wF5xPzqsaj5QY6+rAeOySaa5bYI6SVjK+RP16V4AV1TX7ZcpylBSHkoanMRRGFIR31eHcTN/ZNVWii+VqVF4Ji/CXdG8o3hVTU+Nzbf0UmF5","DNn4fEtpSkSXWDtXeTXdlmJiuEXmOLtzY+82jyFCQC0j8mpVTvbDZBzFr4W3DFgSdsEn9Vf6WCzytLk5s9mx+PYMUe3aPs14vFmMR9S0+LEXHCGuMijvkqNEO3wS+C4ppEG4MXKwdMNO1DRyObymkMg9NDerLtlUQnLf8d67Ou5fL0JCFmXBpeqmWL/BLBA1n//Jzp9/Fg99F4sd6UzcG1JnSQ5tTN72sr+QR8CyH7UnIfQ6HkC9DXvKVS/UOzBsX8CHM52eA05l9lNy22mwizWE+BRXtBjpqnlKw7bNoR52gXPhLEtD1PRgAB4="],["suHiPwLh99i4sd0ePeQHQmdDVmYwW5rGiy31Rn8iaNxD9gqfabEzALFopUwhMxYcbRpPek9i76/G5wETFdCDW61Fmq47/yvrXJMnClhqKm1bHTPYfDHgIXAT26QGYdxCPVSZXz8OlRuriBEwKWW9TE2elTsclb80lsYspMM5/dqT6fQbVyZ4/gtYhTXt/qHZY8AkPMKA65Rw4G/SFuHVc728iiKIX14ndzHS5xSD+PqnWKH9kniXsLA2IAE=","eOER976jDPIA9wIIIbvO7QK09lUS8DYaiASfhfPNgsiIgpwGhBc+stUYxr8sTpjTKPpV3ygPqjai5EwflNd0zjkWo5H2rV71sxxxH/9h8Egle/LLusxgWvVACAlzoSDhaT0+LRXFcltfqZrMustKUdkZcXHVQBV7+AeJ8u6STTHq1Gt9FsY12Pb+vzJS3HhHUQPuWUivqjKBb1xilJrjaJilimjya88B1rBbV0ffcNfy+jlZBCUpBi08mZ0=","X4wBCsTe8jLMdBTud3GCDRe9It2kk6nmTWIx9859BDMwQf3PGysYX+HWV4yaC9sZ/EEOsXQGzXfsf679CtzA5Ko4bBj4uNlN6zpdJTXHxAB0tZcmZIqZdoWjrZyh/FejEx9X2VlGfiX6hm/vYc/T5dExxaFrm9BIx9gYUyzpaOMza7F8JWxGPm1s0l+ZXdHnU4UbxFYzddrGfXZMTw44j9GpfSItvaf3wNzBg+ydHsjK8kFkjh1qkfe6+kY=","TFzyzuFTp4XfyiiZREpo+6jKqS1B3kdpa2UHMqgxvv76MYuxq9cGolAmOVS/aJZq6+EufW/QniGfdoyA20gU4oV3ZGD+zv6va55Re2TbkzuxsRnd3pCG21YNa8gYidV23ArA6nqazQ2pwaBhkKoN9QcgdEdebJ6cRetEac9rV1yBQoSZ/9uk7bez1Z2qb88bhg9s+eKwGPdmwD6OUVdki9Va9mTN35OZOL4Z/fgpP/MsDoudbslhoH5lsw5qRFKFSFVt89sTFyCKZ+LQ","rv3Dp2Zv72qYSTNKr/2hUdsYW+z3NBQvDqw73RHuUWUytRWw3BAFHVH/tS7wTFyre9sAj+Ay6cldlaHDnUEns0lY6Nk2WXxJTCQ/ux1iZbdrbpj5W1IuIqEekzK2OPvQEXvSWPwh2QcrK801XWjjEZGe9jowSRJ4pJKYqBug0VKIU3XRJyCSkdMw6e1Eec9blbCiPryCNrh7EVWG6YTA8HUKo6dpYoBBZwqyWiusKTeo7AD/dRlq1lR7dRPyJcvNQ9jdzM/+BZUGs+xk86o6beJdTnSV/tGF7J1Y7w=="],["ejbJ4gbagrm8VjofHq/kh6VNWftw52mOZaRP4+9BFzhyiUOo0UsIC3U4WsrKQxTgoV5jzu3J8BAtLayuhU41MsvBg+b6kE7RO0z92dkTyIMUCdrgs7W7iT1EsJRBbUxa6FRGuKrxc/7cq5qiBH08q6oNcFcLRjjcKZoccFYJ6ntz2dkZA6E648nbBKmX5M54Kqt06IxsC1RlInXM9Y5BzItPfuoTmNG6pDb75g==","hP970X8oYuZ0ZiypbkSYTmu5y6ED7lgOTWQK8BHNa3FX3/rDwX9ZfyCz6wUBBNO7CIqCa1ZULn1zs+Z2E+VDJmeJAFzIPl4KDg4+CaBvvPyVd4rM3bwKnOvnW2q/XosGcOr+116e+y6SM5F5cgtJh74NZ4MnyvubG3Hki35+T5X1YpyX1ANfKdjVJEqcvxKvV0T3sP+HIjaylRPLKOMqAKSkwXUKbAeiDLjF1Q==","A8Iijjss5TFSxVA2y0fSKMcYbWXzWIypzqW0n6Hix/k663GF6/QuHAbZMHff5JkeVMCIvALCX7e4F0um4m5eVVN8+WQwms9ub4EaxZwWMRNNdFhHzVLdzD00+LY5Gsiq+arefynGeMHfWrOA51sdMgero/OHsFxdKqUzbNItVtT07toFugcn4P4CaMGV55qz0B9rZsSS0qKFi68HgvmpomE2H1Aj09k78AD07Q==","3iz4v4HzVTmhQoqUvbxBsOAtOHtPPZePOL/HgcB5SQtc5al5FUGqFDSR1mv7Su64TLL1oZp3OLdV3rzC/7xaHgihEQeBRoMRbBjcFnI442I+q08hsp25AxPWCLdHDB5iM5wJ/cX8sRwYU5jGMDXXlhdtOhRCH0Tq/3d5IPc+6zWlCQ8KeIsPQnTBkIQPY4r/VADNvOaid6CXYCqCOuqc6IC2RznrYXZAm7m6Cpuqm+/NeUZ1pFs669FwC3I=","wf6xmmhhilk9R7a2XqhopAvR7cgCuDYdUt+uk43+t0zkrqW88hyVKT0fzeE9iszHI/uELOjfHRJhnctg0bTL/Nv6h9nnHW4vBsMLQJD8S8QLlkM3wcFD5g4pH7R0macZEEM/o+QFmicuxtQQPUh1RESWKRk/VKggOC93RoF3MwWZNY+lGMsXoHedcC/8+vdqfUuAiYTELreOGZmfPRRdy14sJxLKl2WLjHUaKah3+j7oE7nVdCj0KpQkzbqQj3rc7s3Q9qCKMF50I+le36V41DQ8GETmGSyOOUPAyw=="],["9G5druP2TKVtSCtHb5xjY2737u2R/fPb3/sBH0x75l1+C2i3Ayf/IT7EumKtoOXMKymWDQQxLqOnDOTrLqmE2hep7o1Ay1L1Yb/5vJqc2cRaGfqSyznHvukaiOENrK4m1Y+/EUUG1xvTtrkwyoq9fVurrprJnNhMnsZKMaYhNVgdbiFW4bBaZ7LeV03Jmd1B3dUVtsXbrUIKDRpe","cXLPGt4e2KXBt0ASagenzk3hKomlQaLXZUxVzKUwMIyRHwyT7Lv75CncXJ9y0vzT+tavBCFZXUvSpMDGxEWWu3a3df7LrzHCOFopqHDwgdfR4c5GaRimS0S99Kijdk9bS2mUiVCkCffWdCuc73ncj9OiP1T9B2tOVrQXq5Fn84APvRN8H4fhwB2y5r42oLNra7PQXKVyMnyd1no5467mcNmJiciZVT+0R6lbyg==","efTDQT8KdK6oey52Qfa+/sVrsVjMCZfuGc51ukjEp8IylXWAEF06JGgpcM1UEDW4meDt2J2Lai7/cw8G9fxxFFOPGSkYauu2NFXeMnFakNwJltjI2l/nZgnfE6uVQ8GNC3FhfSS5rH4g0lnlaAB24zLUXddi3qvrtkQEcmipYph0UYy442kVpjvjSvZICBDVhqHUzObgey2lERSu","J5YQzX0hCpDG4cbAmMeOnJLxAt1dGxH1+jCBh99V36h72J/zuP4eKF69MwBeqmoqUn7gthg1rC+u/d8UYzuKitHtvd4794/CNjWSvSscmGAT3xG/fLltvsy4iS7lS8f0O4et74b3QtxLTVgGB6vPDm8SkOEszmCSIWJAFpzmnHjFKSXWF76JHPMbLub+wr0f9xZPl9g733pFm6ZCYoFxHhp1WuDmd6GoUkvj5g==","HsovZcc2oMaOgolKaHUYxVXS2f7bBCB7/HKfwX52vLkzLaxwlV+Z+RgwB97yfcVBwXAIYCydT84lPPAo2sn4BojO9rvdR4wWV3zNkqrhCcO4wSWFYhTakWq2DG4PlSGsTdlsuGT4TOnks331XWQkwjhmY5IL9+rUn6darEHtmq2dVrcT9CeNaSqnk+vH2KquZoZVgWP3JdyYwPSNntkZGaQQ9i5ZLlcN+VGvOyoM+Cjl4XB8rhY8/549rRxHE5dLeLUEVjNSqC/qyjgU"],["K02458rwGs1KrBuTpuc3aSNGvFGB6lKdseUfXULxDOmICn0FxnbUm0ZuRm4HUauz3LlwB6sppRe0mcKgCplrDtwvXFZM56BDz5K9DHy4MMyEhrpxkxAwjjsSgMwz0enwmR4VPtv7+vVS4+GInazW/+iQ4lqwOB//WZwhElAawFClw7KzOc9i1FS4GhE=","OKOSrmiz6hM6LYNT5zc/t7xfH2N8G6DdIT7thpkXOFb5LkGEBcWLEbK3zr4XcsMNcx1x79DLv2qiChVhSLeoO3BY3odl+2ULrOFEKn0HnYsH5lnt+5BZbp0BfFR24j0utadAqy1U5gwUO8eSCBOmPCf6XszbukDW2bo+Fz9KKYCGBHeDjOCGtPJV4uipm6A2kvNQ4bau0je87s30","UDfOuq9Cfqh8sWWJ+5MoUSDQ/MTbrQL4OZaHCW1ivIVAwNcg8X+9UrrAKGy2CEo83dibDAGx9mhDCN+F8whx7kWogE6LxVeeGdxNYja37PY+pp/7zK606t62/38aGXGAUDALEI3pdXlOSA/UB/aiJxFBlUt+mIS5iPyjZeOmbSsyzWxFhICsCE9llJJyoMYbkyyf77YT3BCiVUhj","daUManekmphn181qFZKrIa7nJYip0An6RYgKZRWgFPMyx1J6EVSSHAjZDdOUOpB1tb6MeRrqsQZX6Z5n8tiHsnZ4ndaYGwheyPjWz3C5auoJp+ASIg1GNxyjf/W/PANi0iJgARMCERsDJcNc1jgwPkCvJd7McMuk71ap7x+rQSeYRc93gXkEqNS/vAHYuK2oIW9AdIe3HeWFIU6DNvKziElSnlO7QHocEtkDZQ==","HfYjd4LREOHm3vkqg+Xc9aYPM7J9he5aVH5xpWZmxL0+/5idipFkI3+V7hsrKkixM5Ml+wo0nYWlhGeiOQyc3wctUSxyfVeJ8CDwJ4oJ5TCkJHUdiRKk3XJBjnio8SYm5MHzFpj1YaBQTN/Y5F4OQA6sLExYPSfUQvdmY8aWoqVAsuR5aC8KSNtxhpObDghmYF17QrNB0lZXH8Z39pGPSVtAcmda/vm2rjc0+kr5lwXJP3HOPIUusRl1gak="]];
                            $result_val['FGStripCount'] = 5;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["HYWN7X9vCVvwTiZ8vaCEGfKi9M4LhQSrF9WiOxC4vbDwZ+NODNF/8BUlli2dGIaQ4bNd6sHtGNzw01FyE7eIPHu/iSUyAht13j+sp66gxFI9dWKwfTT44EynFRwVDTJ7DUanWupr5lrYYvfvvIhT66qQ1HWQXmUi3VN27UT+bAdYJdAZEyveFh9o1CYIPJD86JR+vnKlC21lJJ/7hHTyKEGkk0+Czh0iPD9WfpyDxAMe26UAOY6hw1Ju+6qgBei+p9lZE0e6I/Czy9Yb","6MN4xQa161TLoKuWMUXQwOYu+pSnuATrnLPSrxcySHcxVk3XA+9jmJ6iG+O3oM6hkiGYl25+OtbYHpwmeU0SI19dnK2fMR3n3sscfUGM+XQX5h75+1QDc7kh+hBBxL/a9xNpzwSZdV4ve7ZN5Ro1dLHaDA2qha54mH/OUyMwiSy+zwR/OTHJ+SkxvuWL/sAmeozBd2OeEO63AKOgIZ7RO2iiuB0mDYT1+NWSLdQiWcTJp2NjztD0umWyfAqn6gOjKx+Xjh9AyKEMaANf","VedXwUvl4EGJLUxL03Y0jeWxLCowOEFCRI+AIXsPCL9gCCd4eLb5EWvUVimn6ZaQqPiXlOzWieYAe2aJhJnrKEKTcz8IiJ/LgTGfGv2F3lRHUimaYslzcTr4C8bI/NmNQOYflX5wGdZRCnn+hmQV70HBVEiLNivFI9NIbqD9dVtQIC7VqB1+4EMtgPQC3BmZWKGvQJLFw5HJDRZWBPcGvif1IS7VPJI++ixXdZzLIDbLH2Yq8jcpDasdUSshWwvDCOUCAiiC0eI1fTEhxHaqglETtrqFt0eoNxkMUQ==","Iblem5YLe5qiGFOAMcn7bpfxB7mho1NfxREeLyTY8KmyYREy282lEW+YzVL1q5R5lyqvc0utHGqAiDFtejnnkWQUORVyDpDwn8n4zq43wDmzMLo5UqNPBoAoBgR4fyKY7gAJJoSla+z9l60RU9SXQtPuE70f1mtVriUfiDicOI6/ioqb3ATaYxeqmfaJfsfezO4SOmZ8N47Tc/2AzXxzWEMj7anvIYZO4HK0ZsQzgZv0iXJLGV6bOpW7Pty8HceWNSZXXl45nUFECTFy5TgU+uj/1dCMl8LpMjjCsQ==","WU77MVkql7zP6FuW16VKdEJ+DtyPbUrR4TvDvUUuItOZkwxIby/1OFCFIM2wLDU7s2wHcNPWDdjqQ0iLC1pJghRCxmDC37GaUy4ZATMeQzx/HpUsJtn2DD/Q1mVDQxC8hHdAIQhT3oA5RCryPyzQzgdsGMmtER8ryQUq9IUVgdlG4gbP60eWcEtZOmPAzw0FXt++tBe5CKruwO8PFS6DSnv3GKv6hPQRiwVMo2I+rUnlmbESZC+MAL8w+OU1Z0vYMqUrV8Uj/yIimMP1zdxESN3dMKXkUOLvax+0ScblZ5atALXaKEML71ORzLg="],["bBedKi78BauYUJi9Cej1TzFxQBgSI7YBiJABphua0L6cYmh9fwplWi37hKLlbIIIvk1AUJ8uuZ4KmO01QzqAIdS/7yxZKOG2itbYWOw3MvWng//UK3GhcFPDE8VE6S6Pw5ZnJR0k3G6XduBULJ0QkI84vntiENc8Ac2vcFEMncCfsGuJ8jgr3754DQkjlGW5UgwLWh5QKz8DO4TvdmlqldMn9e5KqHxzwCPR94LEmkuN/OMjPIqGdV2gtuc=","HpDDffYSgLMSDoqGCs23GaY9GJGqlgs3bFLXFAqKJFqClpCrH1bK4ItjRR5vXe//XufmEnjLKtDpaPM001HHy3fKrvnEz1hLOlQKMG8HCl9p8/o7rNuSJN/vqrpm2d6oR4LliEtpBxupYzR/mN0Uc0xtXkJec6J7EOIU2a1fBG+i7hOmTIhjeH/HzaH+jkiDYFKyjH0Kj3BTQ6GzKC3UetgcRv66OINny43nzBudeofQDMUK0SP6E+EuTzw=","5ZlVKrcTxp2dbGRzYWvf1GJz43RkYkILXc0NpkYsbB9pABC6xhPrOIcFarJC85AsJSjGmOiie7OaMlZkhv+2rSlCORCAjRN5XS3hXx9YNHZHvQykYcA1AupR1DfKY65izvXQZUqcV9C7CD83+UFY4+/2Uxuq8El0ZjmkHlY2rp+h1Uo45Wa6LZBgOmBzCOUXUxH1IZJK49fXJ4XNO8lzAx8WmzVKEB0RxI1fmnI429Z1Vh8rLF37itrHtbbu4jjAAxSh5UpHMNyIEem0","PraOKgoqhLPGW2OStLthE1CTWdkWUSfTQqx+/KgKaP9sKakqg1JB+hy0IRkcG60x+nn5qcVaUb8Dz5q6uepV2PV+tdkh1R9GSANTnYECtOJyWcFR1lwAIhg8MpWDIlMQwRMUg4u8YjB44LzSVRf9Mm2hD/7q71R0glIzwYG49+Pq69TAgn9tC1Xm/g8QX9xqqDPdE8Q/OFw/3K/t1u3znZ1IrbGQfk+w3Zz9YgL9P1p9DIVuzCpVmgpKtLM=","OeShidNvaHfNXYDjU+hUMWBDt0MFhYgVaVUNgXBpgbmQ22cqLcHgT0jEW8nJeKlOjdDhJ0Q2fdLcJFAiCv0Vxu9niLJzqo7ByXbT0/SMDTu1huQzF0Qry5FQ0xeYeshd4OyG0LsvenFMxhwBbh28XG4UHDSWas29w44DMeXMHJnZHbjOCmTiVcY3NXB6si63tu0v7KQKSH2J1GawUpo784Oi+vj5B/wusM8i6Ru9Smwm6WjTVk/J+Al50aEEHFHXXPT6TZnjJK25Q66nuKeX1szLG9mQimVu5R9/2w=="],["jWzY1FIL7MWdL156htVSp4cED8fH+rsZeHkKAfo4PQ1ifQEtxD87tVCQvEwHrlHGcS8+Wkci9GGzc4nWFNIAtxTitHQEBHWpaAFruqULsiPQwpZJ5jIBHofHUjZFhufmgcZZlZ29Gi5qpZvrUb+56ztrFi+M9z6IgkyoixapTCRLobm3fYiM130JZrbLLom/9Pg+KlGK7w7cURhtmxV7KSxPbpKL66Nvfg+Klw==","OcdRURNSOySYAfVZ8rrvmPhaV9+p6coONNqAXDgFn1jJpxjGUfmubTFjh2yQ3xIPtjuWfptMvitmfL5XERAxjNbiiYICLxI3DOH1Rr3ec1Uub1KmClBye7SDnGWRlQFxRy8TcFX3ZJsykDxJNDuDkFrGzQaXVWByA45SclJNWTYXCn0pgNsVtNxrSH0OoJf4u4z8fHCnh8KQD3Prx7NnW1EdU4Hp4I9QiscF3A==","WZV8Pxmzh4CopZNSGXcS9WZE1SiGW22xPxIE6dPA6y52q83/YWv8Bpaf6RyhBAuVyaM6Bfi4BvMVYsdKSZqoGd+Whm7/wln3wCd7v0Y1TekDPRo1zvi99pMUQvR26JAfxGPCbDda+Hp50az5senC5QQ1cN1xqyKOtSQtdIHwwbnlXheFVXRhcuYwYhnvUrLuXl2sFByr5vJLHNkw3c2hSd1jH54GXbj9gR1mZA==","UwoALSmYFbECdu1fyoNHXAXdR1WBy748NVSiiRFEtgflOMep+D/inFBsULttkcPQICZ94Mxbb4jqU2uas9Yb3ZQm4Ppm0zlb2R+BCQvTEXI+Rq5MsOtvGC19P6niXUy6IEqJnc6Ae1lqf6JDFFdFVcLPMOiQ5vbcgMzPFLOxnWg00LqG30Ay7Egc8PDLU3q08tABh4geOrUMnSZn+8EltCjcdRPEHlscREkn7A==","gw4Ge5J6s9JmrELeOhMFJz6EeL3ebIgR26xd1HPLkNuVWd3nOOscMR2Hc4zQ5njVBuOZPhmrKvJvMykDm9fpDqfJH8WvJ3fNJE88hKRCLFLkKk7te3gqKxztrSIY88fdrxwwN89q2P8Axgqc85bGXrQ+I0UFJF7dwSepYHIzVYAJuEZpKAm/q9sXZkG/ARSL/rYt5tYkcCT1oWa7qOMW1EAMM3NBtevukw+nhQ=="],["pjNhTPZm7NuWhWDatIpetU1H5+nsYhDeKYel/713ADacgx7OArXnEEJp6p71T9f9fchYLPQLxpDEKnwp/uxL72cE8Vionuh1MqQDfIOtGQbsxPo4nh8RaT48QS+AfIL44L6tvu5upzUSxaZ30q+ldDZT5h8SpbQS+gfCRXjD0C3s+tRROi3LOV3GBhA=","E5Lkw263QhqvuNJnQk0aO9vY5fFPvmNU8dMwIAUSe9sOZvzi0P+MQHHvtX6GGl6g1BXA/F11DV/XhVD9gkHzMT00/RveFQPtMheuDzFj2Hi7sxW8nT2EtvBNcty4if59OHS2NEjbRePL7MZ7eOwBQtBCvwdfCQLJLGxvTG5Nf1Oxag8mrXdmqaZ9ImY=","T9NZLuJqwvrUSZxrURLNXzsvRKjOFiRBbMEK/HGwxeV5bfeXb28+Y9YOi7TINuPNBBpa+MW1NA8o2PdDId5nGQq35cqjIDiowm3B+pP94uKZIVIrsbkvR9JHJ8fUHdEbV+MhqYFlYpZgmTwf2sIp7PjyDUpGuRBi4oqo4FBA05p+vpTtdjorNcgUI50=","Zab5PCYMZeqLeN4B5qkGrCsPaMwjCqGHcFshIHxXgdyCFVuBSWDpx/VV1iNpNehpV1l8qbFch8Kf8AyJLXW9H62vqGvRULP2t3meF35rE9doeQx1H7Yv/ej3byr8opXpbDTFcFBLJsjiuSeqYJJQywKOMEjlk+huo8PuNbWS+8TrbyXrmPDU7PrKCzBKtE9ej8czDo7ESilZKHy3","YGLyeADwIndNnXbldYUHMcMsp3hgECucPzssPspC6ZCFGRn1HT13Kp5H12IG1OLKQKHArYRJGA2SEtTYLHDXgvm1lovOu16Q2a/Y8FVBlN+yCwJUnXDB3jp35RU6+n4QicjUaaAFHMxzuPmsLgS+sixObJnGzGts5rEyWAy7Bq62nbctDAtvATjcrorrDiM5gOfJQdyrIo/C2iqt"],["UcdYFFJ2Fc2sw5xrVrGvKlM1X+2mZGMLNSIJtBS61S7bmq0TWtZH+az+V47S3GB/h+nb+ylPzfVlFjwafhKWWtBz9etTLi9Kehg3tCdG7F+4jLRPPAUVAM9hsmg/utvV/c0WkLU2pdrBdahZAXg5roSzfJhu6NN41teneg==","F0UwLbD3gITYRc3svQj+73p/du0FD9NQcroxs1q8XyeVg0gFCHrkN/v3miIZGjv8HRIvVABy39Tnzm8F91/ZEYEnKH1ltmlAMaGnKQazBk5bfdag1e5tBc/cR7a3jwANTkL6mjzcig8+HpXcssnbBYO3G04WBHHOUWngiw==","y3mYHeeFljVbvygDVM+CnW9u+UP8xdAfS+g1FdWx0L/WiduLAk3++ZFilNgJmqHunGjj2o1SW9m/hbB/zj9+jcDBFLJcVjWq4KWTCpVVtUKWkld7PuHoVr6gG6hHsO326hxFZJGGAcOs60aIUEAcYm8528FctTjjg2/iVyOrusk3+b+Hb8LWuG/4sJI=","HlFzBfEKUkZcOEiH20jwUSyru1M0sLzXgOytCyflVuAx7VlcHSIaC5hj8bwUGYTz0FSEwDc/6rqCTHGr7Nu6DBtf1jg/BL3rLskGcvmSnvs3Y7lU9YuHaEOw5A0JWW555gZDB870gDyOzBF318ItZUREee+0Bff2vwnZzZKyf3ZzsH8C0XEGqqcnpW0=","2XJ722AAcLnjCDiPEvYwO8QiEisqbTqdH3k1Ug82g+CEPIUpFgYv+AZRGHq90J/LjnHhoAs6hQyM63K7v8JJCbY/XqTfB97dBXn+iwKB/4vvf90emqKGMJwVVSux0VhgUOUOacUU7FjnsTi41rl+HPzkXEu+8X8IAxV5WWLkyy/KzB4bFLOwKi81ivI="]];
                        }else if($packet_id == 31 || $packet_id == 42){
                            //$lines = 8;
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
                                $slotEvent['slotEvent'] = 'bet';
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks  릴배치표 저장
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', $betline);
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lines * $gameData->MiniBet);
                                $slotSettings->SetBet();    
                                $slotSettings->SetBalance(-1 * ($betline * $lines * $gameData->MiniBet), $slotEvent['slotEvent']);
                                $_sum = ($betline * $lines * $gameData->MiniBet) / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '660' . substr($roundstr, 3, 9);
                                $slotSettings->SetGameData($slotSettings->slotId . 'GamePlaySerialNumber', $roundstr);
                            }

                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline* $this->demon, $lines, $originalbet);
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
                                $result_val['AccumlateWinAmt'] = ($result_val['AccumlateWinAmt'] / 8) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
                                $result_val['AccumlateJPAmt'] = 0;
                                $result_val['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / $originalbet * $betline);
                                $result_val['ScatterPayFromBaseGame'] = ($result_val['ScatterPayFromBaseGame'] / 8) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
                                $result_val['MaxRound'] = $stack['MaxRound'];
                                $result_val['AwardRound'] = $stack['AwardRound'];
                                $result_val['CurrentRound'] = $stack['CurrentRound'];
                                $result_val['MaxSpin'] = $stack['MaxSpin'];
                                $result_val['AwardSpinTimes'] = $stack['AwardSpinTimes'];
                                $result_val['Multiple'] = $stack['Multiple'];
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
                            $result_val['TotalWinAmt'] = ($result_val['TotalWinAmt'] / 8) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
                            $result_val['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / $originalbet * $betline);
                            $result_val['ScatterPayFromBaseGame'] = ($result_val['ScatterPayFromBaseGame'] / 8) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
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
                        $lines = 1;
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
                            $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline* $this->demon, $lines, $originalbet);
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
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, $betline * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet'), $lines);
            $winType = $_spinSettings[0];
            $_winAvaliableMoney = $_spinSettings[1];
            // $winType = 'win';
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);

            if($slotEvent == 'freespin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
                
            }else{
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, $betline * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet'));
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
                $stack['BaseWin'] = ($stack['BaseWin'] / $originalbet * $betline) / $this->demon;
                $stack['BaseWin'] = ($stack['BaseWin'] / 8) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
            }
            $totalWin = 0;
            if(isset($stack['TotalWin']) && $stack['TotalWin'] > 0){
                $stack['TotalWin'] = ($stack['TotalWin'] / $originalbet * $betline) / $this->demon;
                $stack['TotalWin'] = ($stack['TotalWin'] / 8) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
                $totalWin = $stack['TotalWin'] / $this->demon;
            }
            if(isset($stack['AccumlateWinAmt']) && $stack['AccumlateWinAmt'] > 0){
                $stack['AccumlateWinAmt'] = ($stack['AccumlateWinAmt'] / $originalbet * $betline) / $this->demon;
                $stack['AccumlateWinAmt'] = ($stack['AccumlateWinAmt'] / 8) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
            }
            if(isset($stack['AccumlateJPAmt']) && $stack['AccumlateJPAmt'] > 0){
                $stack['AccumlateJPAmt'] = ($stack['AccumlateJPAmt'] / $originalbet * $betline) / $this->demon;
                $stack['AccumlateJPAmt'] = ($stack['AccumlateJPAmt'] / 8) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
            }
            if(isset($stack['ScatterPayFromBaseGame']) && $stack['ScatterPayFromBaseGame'] > 0){
                $stack['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / $originalbet * $betline) / $this->demon;
                $stack['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / 8) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
            }
            $awardSpinTimes = 0;
            $currentSpinTimes = 0;
            if($slotEvent == 'freespin'){
                $awardSpinTimes = $stack['AwardSpinTimes'];    
                $currentSpinTimes = $stack['CurrentSpinTimes'];   
            }
            foreach($stack['udsOutputWinLine'] as $index => $value){
                if($value['LinePrize'] > 0){
                    $value['LinePrize'] = ($value['LinePrize'] / $originalbet * $betline) / $this->demon;
                    $value['LinePrize'] = ($value['LinePrize'] / 8) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
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
                $slotSettings->SetBalance($totalWin);
                $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
            }

            //$result_val['Multiple'] = "1";
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
                //$result_val['Multiple'] = "'". $currentSpinTimes . "'";
                //$result_val['Multiple'] = "3";
                $result_val['Multiple'] = $stack['Multiple'];
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
                }
            }
            

            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
            if($isState == true){
                $slotSettings->SaveLogReport(json_encode($gamelog), ($betline / $this->demon) * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet'), $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
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
            $proof['fg_rounds']                 = floor($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame'));
            $proof['next_s_table']              = $result_val['NextSTable'];
            $proof['extend_feature_by_game']    = [];
            $proof['extend_feature_by_game2']   = [];

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
                $sub_log['win']                 = $result_val['TotalWin'];
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
                $bet_action['amount']           = ($betline / $this->demon) * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
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
                $wager['game_id']               = 133;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = ($betline / $this->demon) * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
                $wager['play_denom']            = 100;
                $wager['bet_multiple']          = ($betline / $this->demon);
                $wager['rng']                   = $result_val['RngData'];
                $wager['multiple']              = $result_val['Multiple'];
                $wager['base_game_win']         = $result_val['TotalWin'] / $this->demon;
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
