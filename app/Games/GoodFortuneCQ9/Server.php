<?php 
namespace VanguardLTE\Games\GoodFortuneCQ9
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
                            $result_val['FeedbackURL'] = '/feedback/?token=' . auth()->user()->api_token;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['DenomMultiple'] = $initDenom * $this->demon;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = [
                                "g" => "50",
                                "s" => "5.27.1.0",
                                "l" => "2.4.32.1",
                                "si" => "36"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 5;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["InJUOb6b5eD1wHYWQjWlgB1An2CPpYN9bjKj5atlafJNddTYQgGXLgYlZYOBa6RqY9S9+v81FpYE1EThJ8MQno6oopv2xUbuGIiEw1cv8T6ZCnjKQEVxbc9fx/H3LZ8EUJlxgAloDT78bjCpcBvl7XX5/9lh/N9/92e+gW7I5e/qKbdxcwcbTm3hm6PRgzJsGjTDAKMWNRqDhi5xoe084Pe7nOnwxL9cJY3O6B4389I2B+jU/V9T7REingc=","gEeIjuK5CHVtnRifv11ZhW4VA+3GgKSbl9XIMkYCeciIwtbdC5r/N4VQY31UKxSekg4DCS+RwSUjEwyvEeYJylGGm2NqxuNLalxW8LPizTvbWUsmn2APQ5JUx34MBhUU4S7qQP1lPx5trrc8zdiZ8bFTML10fS7LnoeIe23mE3JmhiWOX4BthS82DpQA7FRBENJOAPUrnV/9vF+AUuDOQZzn092aHihPsOxKqbNTOcPJ/E0dfIK9yEsLu85yCHOlqHk8Qcuy7AtJ0Jhh","tvHaHUxhISJEYgjZkol/oF1cDojlTtwHvlTlgY0B82CaV0gRgtLHx7d5VW0Ea80GNfpMAtQEk0xZVscZkt4F+97fqVsldeebtERVhct3ycV5Kg/KqNSO41EqZVHOfG1fJvLzlNDpNEzM+tHS/a5s+YBEJssfy5VWxwJbt+fO8r+gfDtyvNFdEzx27jkVplTTs8PBQ09VURNxFYvRZs/9CZDYh58j/KItsWsDJQZIl+Zv7JKOseaLegcZfgWJ9x9oVBkHTn7xN5nhTN2p","XZFr2dmiDoP9NqW3B8yFZVK3I7SCheJozTpoQ/RgsdMPmz05aSbaaZd/Hq3/dQxZO11Ydju2x6XPrw6MNegc/14N3hhAOf9wNa7V3kTL21Hwre59jcJHBU4WQ/83wXO+1BQ21tEmfpRJGMJIYuylhkpBydLY7RdeIq5GVggpUfkNycKC5z0ZPtVjZ/kNVZ1LPS0URhGCRlyTDn8ye1G9cX8UdkLRZzcerSIY1sxrisGYN0uGH9rbozerpTbMeL6r55hmwOhop96p6DTS","Wd5HyweDEEjxdNhvWn2BhQcMXAJ9ndcP7DmlG9DKzhXWXCYtEkwy9wPBLU6ELG+cIwLCQu9xciW1xofIRh0H3lxLsPRbb+EXQy+zebTkgbMC3IFu8E8nWdOgWBdGnkjEeCGWxWBwGbd4p4oY5uzSOwoWhl72mz+PI9YWiPkOJ1C6WgWCD/PIAf3JM714UVc4lK6tCtEAikgcWE44QGrcSrm5OaFxLEAc/KyLpgWwYCw10Y9Hw/2dTwSb8Q/77e1qnylLhcbGEr9K8jYHX/+wERIBzhKwpLqmS1XPgPWFR7VgA6lwXdUfa6Ow1qA="],["tPmizMIQfnw9KMlLjZLNntr+Qa/ZmOsVIl2qEdUhvTmTsaKXCOXBw+8FcH5AZ3Q59WR8fZJStJaaBV+dxvphgKKBhdCalHDfCX7KvdgBP80dEJSLsNfi8KdKXMfVl9Q1SUyb0srcqef40HgmRqXXqZtd2MLg+n8qfkjUsBHNxxdsg3L7XkPdMNmmO6GqvnBnEcRsGi1hm7DDcTzg6bTLhmAaZYGS7VGVB1PMElAOUzPHCpH5gh0XPtKe2PE=","j6dPWEKxfrLdyZ2lxE3HgzXtvepPkgz1LHpCAQF2g2w2NWHdmHaPHgflO7R3m49EC8imAZ50RoADGbDwYGG4qtjkZq8C8vJpbR8raGhBJK6HaWKREvGy90lsSTs9GO3R52hacmMWeAy4cvrz7u+qinmtiwWCuZz8+0EAh+u+Da62s5IY3MwcKwZzNg72XKYgkfKCQlz0w+7gT2ysm9ZFxfOWddmvOuSDu2azE3Sbs/qN+zCu2I8Hj/HTkio=","QUmT6MAScgLKeFpVryXTcrpBnZk9lIgqUU9LPwiL1rCx45Ui3VjMxyjdb9dq76V1o0+rv1rEOuDke42DUAtLYKI90CBab2sLHxsGyPrT3zkWBnmObAqeIFqrRtp0br5BrFDKZb7gC3rkyZjvzqp8Qf1PYc/hsUhM8yUY7C69lNN/iZ9JerLkAgiwZ7nR2ZoTgxx+x4P67OuSm+754tcqh98zTYMW2ySDGgLKlfSzy+Kqzub0FxperjQ40z4=","7MEZBE78ludifREV0MeXM43X0xOtcX2qRVgZNigHjcf0QpeKX1CvFC+0vbTCXn3vykwrsVRJHjWc2pP39iOXPzyVlgky4PGfejc4ZgzcVuVo8wlvcDiOnK/gmQ3sKRcZQqEd7Tx8PpvOtYtavXdHT3TuX7j7KghIL07blpNBLNdw9QYMnadP54GqFpKSLYnPeBFo0qIuwvBPiBzdcrRYmExC8a4tHaaBVO2TKM0Cg1AnO8HSwm4P8DmCmOqRtDTAsLedRYGlKZ8VUQy2","whr0OCGMsidjb0J7b6IN9+EAVO7SrYfp1/YXxXIRah+4+6EP3ivNTiKaR+FXbVcX8cMwv0vhc3sk5tD1XbD3FmvuhDOgZdnTzGtU2EonSgFElqzDl9tuM0ul/AqtdGBoXSiIS+IajmDg5wKJlP7rumr0il/HHH2m8vLN+RmDELF/eoL6Tk4KMkcAjU0pRtRNxc/kCNQozMlNj+wQOigQONKrEd9oWJGhh/2irH6jaR2oh33Wvcxa5tdA4/c+uvXvsQSyTPHuLs+DL1J6Fe71azqtu+FoHmRAtbjg3w=="],["aoxVRdve6WoQMBzBZyocwgrReVWGTouUl8z7Pjje+z/giFbIHrzpMt8T/54H8665HCu62j6v+qJukOvw35IZEqsdyqqrsrFOqr5e5gLzRcb7VImOn5jqmyXSKmhsuXN+KPAKR0YIIX6wuUlLLONgu+Z8AdeVmnQiv//CLN5Z+ksQZwfXSE5hW85g3e+jRglLdjHsM0GEGUlYMZQzcbTFPur6ByPjjcOTrBvm5Q==","M0RqQKOcjx3tWg4lQkYzpUi5NuZLBXTvWXgPV5vN8EZHdRxsdvuftiGx0HNenIc0nYqQOJsz2HZj/3+JzxX1PqRe2l8lxFlVQznbAD6L53Z53d221s5XW908absDeQgG4ZSnfUfBW8ht0shMQ01dR1Z3THid6sOYR2j2jhQrOBkU7dlrBdj9zN9Oq43UgW/dFgmg6Ruipuby8JxxuSttgLPtbHTv9jw0WDNvGg==","kU2rGMF3JQeCmjBdLwRx2k7B9VQUmgoy0HJoSQ1WHlaYh2NYWODcQkh2P0g/6lYhlAbEXYVC+KZMtoJc5BHPwSmbFu7WZY34XddoU24A+ALqlmZkclXdVAwtRlCDAg5lbjOLaB30ovTpzR06yp37OKZyHgwRZJggI5f4EhCxHS//35UIc36OsWNAH6wfPaXLeQlLj7hOe2ukpIPeB4UlMc2KPAK7cI6O/vy5Zw==","roubQB8zj9V8vby6j44QGywycZnRwlhfNoo3mpgrIN9EJ318VviwFPlgGuMxPLBH4+snv8e7PstC/+m8QC/gCjM7Rc62IkenHkBbR4/gLltrRurMO3apX4TL19niAmAoKbCuwgemUOA34T0Dnxat2up4OdrL5bOeJe4yVYQh/Oq0i1AmE0G4518MQPXFZBVyNy0dw6wsyYXYTRqU3GqM8GQfZw+FGsb4cNiTRONE9LgP11NpBJOyBNVEoCo=","XqVsLXp8rf8U6giLaVD29q3BSB9sVJotfY7g+IRP5GSNp/j7Q5EcA/1gM8WldvJdKBEdradCokqxvB9aq+mnm7kjpagklFDlWTJVkbXHrdK6eUx9fhXEPHn8UHoZxUmfjABB5Pk2eAst3OQorvJch7T4u4dJ88JpyuULovnSijjvG1MaV08vkjbNWhFOZth60yexUGx07aDSGRaMnw0okPWu2wg2nq+UBf+2DX8ImXHKc5tkwuJocSpB6nEOM9i6U2a6/HKgZ/NF06F3BiYhvvllYU//NUJG4YcuDQ=="],["6Q5DEdviTLotBVD1ozTzIuP15AJ/5smTDnm/6fREo2FO8vyjnIPXT/fa20wTZECNYgKk5quUEzpS5laGknhAKw/+hb/gWlznZupx0XzKW6am7SC1Es5H/5iHi7O2xz6fDsimzsU3qixmbQ8hTUIEAIWTurF77K2b6nAvVyusU8BlvKvQ6VQtng9Y4uoMBrT9cNdmESfs3DQIW4On","dkGsEk3awjJPEn4WrbcVLIFtdElbZbyiS2fqdqYwvC4V1/uo8WIzVaWu+XQ6d0VyMPUlluZDZgOKjB9tTUwzyMHsOE5wrnOWzvfhvy0Wq+AT1i6gs6N5U9qyhsxe88TAkah1hVEUsTGZED3Cy/DcISuJ0SR16o5Rq0yBednpO7mk1R2Bq2GibNCJu8vwj9KzokgmVUwXOU2atxCRbAB1ulbeLpb4GBBxnU4Bjw==","UwvvoDA7AxaeIaIlKafbg0mXeDSggP82ByRtKrUsygZa9zeZnReBaxMcgZVpquqQOvYJG274js17xLtQcymiD8U+qCY98GYhoZPisFTzd3My9lkaok2o6MB3+Ewpx9LkpooIAA9n+7D1XxLCbyPaFIuHTpVNm/VQxPB8pzPYNE9G4Q52gfDsJS79pyrk9HzAQkQvPliDJ+G6QpiQ","g6H1w2ZmBNtvsFfKkn1yOxSxPCtW8VLWEk8CCcU8q0OwrwtUIbodrDKzFEAYsgFQi+UPWCvjz6ZYpa3V8VS+vMRk1quh9Ge6rCX9MYD3WPuxlPcHUp2q2LmXMFEGrXWsX8zNDIUPKWxe9TVbyDhrNS5sYgvBt8jrnIQNrHY0QbTN7p0/JlAA/zQBSn162O5yr16abM1NPHONDuYhN6Ggyc7WCm+A3LMAnqh6Xg==","tVii3zpTQw0FKEJgdfBGyo4D8602Td/dwAZWBOmWHfp/acSk2HfSKtK2fUd17gC55u3MhcX1PRsD1C+IHsWaW0GttMrrQS6qDv87FV36uT0DXfHgFYaRG67Yao4ZMwj+BZiKdAxvhtWLmGNfDBAh5Ok8Ig0qycWiNvPjjRrbWbIavlDcsAlegY4bGZIqOr8CqQ7coVnAQ3zFGBxTZdUVAkeDnGYRgnoI7SC2+2UNngYUAuFsJXEPZ+uBHNxkvOzLYmnLYqnQ30FdRCtZ"],["XVh2LUDRTCWXNNYQFrOE6rn7LlSdnQ2w+ANcZHCJXFJlZdHmXzvgq2PaKZzXADOfGwCZZr0DDHexwdYi9GFgmB2Jx2ll6z2vou26MRJIBoq0PkLnnDb+z/wyFXWnk2e+b9o/HdcjHvvnWGnMjvDU+x36gnucNZuEFDHZhA6aZzY62kMRWlMKn2KVNI4=","FAR3UEEpvov6iKO9YPodFxoMIjGY5R/hS5EYIXvcuwiJP563lLGQuieeo8jxBBxXQZ3VXylG8sWrc4vk4mLpHg8JQ9ybWdtt6znn1B3rONyhGvCubVR7EynJhgxsu17zJNzRoW/x195iL1EyAFBShWdaZtL6cpe2mXTMc9KqlNXB2JIofUrLV3fLXAVv8kNBYi8X3OkgyjHAFueZ","I9en8JI3uidJTafBHQ0v95vxlA/4SrePogO3mW/1W2OOBWLpmc5JnQFneYdlJvh8R1H+tMj23SCOiJm4/v+FV7U/QcrZXPQeMO0ooyqt/eEL0x++73cQl5Dxd/GGQJ70cHGAQGUgZ6dQEGMRijYypRkIxY300Mk65h6Nqlc+9QBqDRAcqMlhpUE2eOwsCSCkQ7qjswZ29OQTAjxO","uNs92x5EpaRPcsyfAmLXXvv/oyGv1G/7Jk1wG5oDlb0Qqw55oNzxSbVcld+iB5OuVvSZAO9ZzRNRHEzmWle3yC6VRZPiKG6JcW0+R+L34U3F8GFnxEiVinnWeg0fcELQMJyp3Bt9KeFVod1sPRAHvdR2WdstKrj2yX1X0nX/toM400LNd46A1HIFK6jJEb3XWB8BacSwXX/YyUTcg26n/ezvjF5uZKt3JRwrQQ==","1lutkow1vDIPtNs5BrHWWE6+yOXSZqRkKVWdTVB8PJWqNTH+EYiGIn31xxgGVMVEAePAQYDbL7qU0VQtfu9YqeKuY7MxltLOSSCKiO5iDd/W1CgXh52JXpzfKUGomkMwKb0LWlnkt4PezZFxo18P1PoWv/pRej5K9W9EJOGOgCIRfkPS2LWOcAfB7OXpELPA7rdUkg51uMoajxSneYKipe9UeXRMrs4gXCM28f5R+IEaRuv4pnhsaAiZyK0="]];
                            $result_val['FGStripCount'] = 5;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["bXdsOvS071gsuhbek8KZPTxMprrdg/T95gJNlr2NwNRbCcIcmEQoLFHLE3PdoA0fATr93XjaHul3Y7C52Fzc33nE16qlFtjNS7eGCxb2xg+f/tJlR/2e4/rDHX0dam5yLGaMrIO2bjafJecwfaNKZWacFzgISLlZBRa9BsT2JCq/FLn86bHz398SYaOwWyl6elyKS1Z9GKNyxEG7Tp+IJlkPJ8uvG+zKLXaiOgNl2KGcLDz1k986DfQ7f71/J469mFYDH/aalPWIAxo/","hn36TQomx70Vhhew8+3HdthWd/B9sKMxt9qrBtUhPMn302jxNQTiQDIipYFww2a2aGHZ4ncQXu2+Xh29Z33de1yO27NNFB8CiPUglz/uI4x8oKTQG6kac4n4WB+ebGsPGS+Y7EVxjE7mBMhcbeI1ZZ0OQmxywb1CbQOvxSC6V8o4t/T0wGKfWUFwCr0kIpSqOxv3ixsNfuFrhVJRk7iSieo6uwFiwm0kdGwkVYNOzZzTzZQv0gYzi6JiZs6FhqaJcUEpttqn+CPXzF5I","T9yJtLrtJU9NZF4uZdXCLMrXtY5pA87SQ85K/YIse5I8Vdwav4hMxcPnGYFeShRAFd/R2gzfAw1WfaKy3sMOkJebaieHeTT40KilWkwg+dSt4uFKxEydwHHtAAOOJBgjSmm4iqN3PqkYeeWZZBGpx4/mx9TZdXiiCUX6xvrGDmdds9dtLS51RrM6CbjFfrKEStsfzIlEdfTK6Q3zCiDOUCUO1J45FIglnnjFFlhIlZqcDElVjHwd+otIAT3Wl6EFBtj8EIQR2zSVolqHZW6gAcsZv8umDQ7jRiug4A==","wKcg09SU0qEgMDJhH7LdNGDMCFt/NWpiQudZ8Wn39y2QxnjK5nFQ8repgfs9mxmFaQ2PVrgd1yzgZSkpoHIzH5QdA5N5yS1k8er/lGSFHRjEax0CcDLepc61jZ+ocR6EuIkS6FSrouZTx4WHQiuhBH5ZQsiZbxtP+NIor/J1T2v7t7aXf1zlwLdYX19DGwJsG7pGagDOYh4AtG0aEzbdreaihHkwZhXBj5923zX1AZjtqvfaaa1bQ2gXLoYywsxyGtmpABuaf3JZKAI0GANrL5LiyZ/qx+qiT4WJUQ==","XEyT48S5EsUJwThrkIPCqOw2oBk5x/lfdz3EitfKrEESfTPTtzFUaJFkaqMuazZrJKkCxQ5Px1D8qyS6CPygsf0ZA7I5lygoqMA+iGeMpC0yuYMvqIF8hPjnrYwIbaHTwNMUIj1u7aIBe9ZeXlNa11KqLoxyuzeJPeNcqWPlR4lLiGQ+K8ZuCdlWDvtDngRVq6+kzWyPXEf4Awyz61lZQXsJbTHMOi0HVhm1/MkJXNRiUdRTwS6mAft0yJ+eK2Tqo3I3YVAbMoAIhwNcIhMbTlCNPxUU3qKMRtv3InfCO7NfE1Wkv7XoonTtydk="],["iK6VyAwPa1FQNfO19roDZkV4DcCbM7NpP8xLJbByWYZ2gD8idKBlpUj03ydI3MJaNRO4cT3LV9KvnNi/5uErO1IjyZvTT4y848/UrSTI4sJNQyZ3Fj664hiu1kKc3tc6ZGK03eZEYJMcV01AFks2Tm0gfD+PMoQ8mlYgI6oNAhHkC3XQBmFfgKkbP+JA2+5aifC3coqiCbG33qKoKDYEPTIXIVfTNLaE2HG6AIXYd08oOWzfOH1JgHVKPNM=","g1zpjNtaibGa9WKmiihfaN1C+XUJG0fWFV8zq8IF854vv9BtVaDb63w7SMLnWaoS4LUrgofW8YSqs6Dz6LyE1rO2szZy/uqX/uIIC/iXFVz8iogEsYQb1XTS8jNZlU0qG0BH4JcMfUFp28PBl8Et6WfSEtHMPtP7fgEIFP1QqUX2ourjHzjCaUaSXDenTZqjYmu/FOb8edd9cg1uovutcx0h0O77SP43FXlINqNm5phK63n9diL8LmAnfZQ=","9rVopXKSg5x0Akizb4TqQ8Jf7R10/DEfjKvBYHeO8PCbkSp9sfPmxzJgW1jBjfQhr/ANf9bozVJf0VvzN5sM3JLMWjDxitIbiAACCg5+HkNigMuae7g8H4rY3dgQcqyhyLAkFqoWV6H27sM2TktSM8ZS8Cj2woLfHYq8Veozm03TmQuBer8ma/XhjuG/ryJ+KnO0M1Evn+uzJQpigWR43PfjhVPr0ZKbGNaiq0ySAhQydgXElK0OZzBXI7zHcDu2iq6nv7p6lbLnAKXb","opih1knDJnTyaqTz88Cz6FrgHWYbFarF/YNqVzXXSxFlAKql3xEzdJBqubuguK/euk4tLCBNlOZziHdg4pdk4Mjrjbwjwa7QKoeh9NWYC0XBVT0oZ7RU77w2UBEGeRmUurdOOvpFLZqaFVEzfrr3Iys64TEgTsAN9UIj1MLlgu5Fxk14qjJ0dn4X1zz0lQvZGR+Y8GYAOwahkcESFyRzN8Qr82EwogQwD3dvrI7EOS1LhjZi+6pft6K3Os4=","URr3wSkiY2uvEBK4V1fm9rMnjG4UfmoL0lqCApyUJq83AVsWes5q+dDy8kkOl5ic/ubaEGVQKR5/D57iQeTSi+pnyYnsKfR/GqyB8P6lSRP3gziMQ7qwMEm/2lgoXJ4AsxxQNS2wiuh3RqoVPSS/wOWK0HHiT4js2oQexITssHM+uRlL8xSpIo/rM9Dxv7wfF7mf0NHgmKatXVd+qRBPD+CN81E58eEat2f8/cOLi+dQLkt9w6pUQaTWoNdbxzOLutsvzH5VqEChhgqmqEUvHTyB2kkESMLlUeOcZA=="],["ve2gIFoqRx999NmujCfth9RU3b3nSg9EkpkvepzMty4fIuEPsQ/qcHNY9YxZ15hzpVL6hKZzA15WBM035J/AzngGEZGDKpb13NnCbvt/wJa0EB24dmMh3j4DMarTPYrfRjOh7EOCytKyF9kC9DmeXMEUOobAvywsAtzxUahcFQPuHIM6RDiToI0fKGAe/FlZl0pAa13a4n79pMMQriMTIICiwTo7ypH+kaAViA==","AaPFoiR8JAY4ur22GqyGRNN0z2WB7yg1Hc7Ib3qnzCTa1UosO9YA1fS9O2kBeOgJn7WCUethXbx3oSIf78X82G2zgsz9q2Gkopk85UXndILYVYT4D+VuvGUD2iWJEghVH/Vhp8pF9N45KAI5l1IgOllhVdDd8uxDWrNa3+rnYtKYS9wX/luTfXvIeIBia2+y+MOmcW7hOckmGTCu8z+9XScw8s3k5EmFeNdeLA==","atQ4wauBsIQ1KGisByu7bWgXkmbunsY/1AjRArFFVeWirPi78sDCBSvqzOUaiSA33Srtpmze0FgHXIOONhT2ZUDgl2CFHCK6xBz4PwCF6WX7Exnz3urbgqgd8TK148Lw67w5C0vGhWLfkgzH40ZNOs/U0CwyRyfN4a2qmoobo76kSVKAAJR5EAELFf1wGsIAzz75NvmyuRS2P+Ma7wCcBwFTFiCyzKGoO+qIUw==","P2dk4wDfTSUayBPLpyNO8c4idu2RN4hQX/YZYy0kDEyBRSgKHMuZrDNXYXxk0AfYyXftuXv/4+2fG5QySGFLr5dj0Uz1FwmSIpFiEC3x2ryPHyIB6r8TDysj2H3e3XcVrXhfWjRFEDDD+cWufNLdk2/CxiRRTCjuV7upUmRwUw1nVCRZbwL1NJfr5RdGiKycfQVImcE84DK3hRSSCrmHnBQ2Qu3kFN1PRCShrA==","Ymr0VDIvkUUN1ZjYFGganeVXiF5mk5czN3J62OAmg+8FFDXHOyfIaSIhoKTSCFyBEXCcrFovQe3//3ZAcFqIziNmVVv8ARUfXYAp3EMM/xbtSP4qWlXY+tCDs/5RpXag/b7EDngIJAcvT71ptdgaNZhX4Vt5KOxVpNY/SfVuLfqS929JjyiqYvW9/hGKqwOT7MLe/f4jphfrBqQJHtwRfo+ZRLBlDCKRvZHfUQ=="],["Knmxx4wVSbRiruHLrHYq3Pnc1IrLQ5ERHbh6DQ6yVNQ5vjDoAv9RaQLpUiFDhXhxoaGbeJ05eppVY+5HOnktG0gRMCkrjb3agG8+S96DLIji88Ub3ujg+oCuix0OfPcD6qZK5ENe2/rzJzUAsOBwlRbmpRXJzAirhAdgbPa9fIgNMl75dlyKYkOCSeE=","Hwl2C8KwvDV247grMrLcv4NeqU1wonqZSMYg8hGuthXx6vUBHf+mjCRk6n9MDX+mGLHugdODBCZ1g4wHFF5arIMwCmvcagcnbORktenLzW363SfnyCgtbZ25tJV30yTJq2r3C2exL5/rc/VtsYo6i9OFw5me26ORTJiReMBPe15q99B4FfY41tUboNY=","PF9aE7ImVZWkKlZJyD6pkbBq83X/FLfJU9SNeob9oCqXVxnJ9kJ/3lRTlLoxNAlHeTwrZbizAgQ8qrR01D1YyllcC1/T+Mg8UXaEhurmTxN1kVg587sOWOSOdc0wM+XYSrRc9cOXaz7oIT/OgNPai586z930QsitIfFW5gj47ExqlLUAykfhhVYPF3g=","UZ2PKKe5iqRAUCqiBW+fLd6XBJbNL1VdpJhuxKdha8RlHiTgc2s1uvqv7AIubw86bDQuoZa1A0+5oCJ3ciP2Xa5iNkjoIuX7GQI3LfMuhY6862a6zjZdVIF883X671LqxRCVoLAQN0ZMyfde0TPGDkfZ7Cnm3MZWomNhW4DsiFniPKe0uerFDpXPLFOiYSG6k5ugPWr1cdSFzyA6","OQyy95vvZtRuC5KnjDfg7XNdVoc6QA0taQ95559iMWU/v3OapxL8dStmTWWrYSBnBkR9cX1RoNyMkoYtGyAwwGYADUjX+3OkSYvTFIKpx073+TdP1jmXDW2d9Wgggcco9vNDw2KS5S9nhwqcniVV3hDB9pWBBaswzTHgxXnVCiBUR/3kJGRg0YdrSdBYQO+4z3pFovHIl20fSpE6"],["E7o0IJxIXshfkeV42gYfBIjPGIiSxwEpMwaGhmgPEFRV4Bb1n4HXjj5iPv6Cbcq1pB4fsLm0mdrvRHaDbCwKMnrlShh45ngr4Z9F11lDhz4Y4hSgo5oXDr8walK40V+4Ft1i+ApBwDDKIEc0nATiRia1DUsoxao+CL3uUQ==","cyShDE31Ku6IslasRVp6BmseMBbp3piD2U0SvP/W43I252ZAvgvXFqYnoIaIycsgFG6bAoLChwuwVYceSRDHLTN4ZDajyWilf9jRBez0Ju32h82HvjCxv1pf7lCKaKvIsf+oT6K5+fa9bIqUYLF6mpZalNiKmv+IzFtGFg==","F2tleMHCZiIz5FaUPLl0yoMbUzUifP9ok5kGFS4Codh1BiGsybCWjWfjkkTSKwO676iv5SAsss+QonB3VsfsavvYgUnHNboEOxHDcOckT9RM2y5Q4NXJR60nzD6I+bE0HPA330no/MvocZa1GBN/xspZHjMPuhyc37cqBOml+/n2067D7HwSHhj2NDQ=","uUJ9B2JE0EVa8jNDCGzMJrt6IwVgLQBtrTdfh0+YJIITkdFI7DC266Grrij3YWwbpVNcQBZtj7Q+L6UBN9/uqLFhwGe1uOaUkd0d+MmGssT3kU8rugI2PvCefW0FDunBnF6shOa+M05lfK9hnpFkaHN7l6uSAVgTJEIbkl0ZSR9PbGMam6dVw3ngjTY=","lmRfZH1ri8j8Qeg0SakonSi7vvo04/9d1/Nak58GRmQFeoNoJGOamBMiO2IkJrAxXJqD1QBSQYXg6FFHiva69k1vRMcsjl8HZvb7ijgZeWtG5ESbnf6F41jyiuqiW8swF4ALK23D23J0hZrwMDBUpMVochmOP4NhwKNdgqE0MS6kibpgtbTO3pvJuh4="]];
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
                                $result_val['AccumlateJPAmt'] = 0;
                                $result_val['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / $originalbet * $betline);
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
            $roundId = 0;
            if($slotSettings->GetGameData($slotSettings->slotId . 'MiniBet') == 8){
                $roundId = 0;
            }else if($slotSettings->GetGameData($slotSettings->slotId . 'MiniBet') == 18){
                $roundId = 1;
            }else if($slotSettings->GetGameData($slotSettings->slotId . 'MiniBet') == 38){
                $roundId = 2;
            }else if($slotSettings->GetGameData($slotSettings->slotId . 'MiniBet') == 68){
                $roundId = 3;
            }else if($slotSettings->GetGameData($slotSettings->slotId . 'MiniBet') == 88){
                $roundId = 4;
            }

            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, $betline * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet'), $lines);
            $winType = $_spinSettings[0];
            $_winAvaliableMoney = $_spinSettings[1];
             //$winType = 'win';
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);

            if($slotEvent == 'freespin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
                
            }else{
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, $betline * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet'),$roundId);
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
            }
            $totalWin = 0;
            if(isset($stack['TotalWin']) && $stack['TotalWin'] > 0){
                $stack['TotalWin'] = ($stack['TotalWin'] / $originalbet * $betline) / $this->demon;
                $totalWin = $stack['TotalWin'] / $this->demon;
            }
            if(isset($stack['AccumlateWinAmt']) && $stack['AccumlateWinAmt'] > 0){
                $stack['AccumlateWinAmt'] = ($stack['AccumlateWinAmt'] / $originalbet * $betline) / $this->demon;
                //$stack['AccumlateWinAmt'] = ($stack['AccumlateWinAmt'] / 8) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
            }
            if(isset($stack['AccumlateJPAmt']) && $stack['AccumlateJPAmt'] > 0){
                $stack['AccumlateJPAmt'] = ($stack['AccumlateJPAmt'] / $originalbet * $betline) / $this->demon;
                //$stack['AccumlateJPAmt'] = ($stack['AccumlateJPAmt'] / 8) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
            }
            if(isset($stack['ScatterPayFromBaseGame']) && $stack['ScatterPayFromBaseGame'] > 0){
                $stack['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / $originalbet * $betline) / $this->demon;
                //$stack['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / 8) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
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
                        $value['LinePrize'] = ($value['LinePrize'] / $originalbet * $betline) / $this->demon;
                        //$value['LinePrize'] = ($value['LinePrize'] / 8) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
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
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes && ($stack['RetriggerAddRound'] == 0 && $stack['RetriggerAddSpins'] == 0)){
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
                $wager['game_id']               = 50;
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
