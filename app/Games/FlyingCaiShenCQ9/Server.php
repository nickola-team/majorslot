<?php 
namespace VanguardLTE\Games\FlyingCaiShenCQ9
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
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 8}],"msg": null}');
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
                            $result_val['ExtendFeatureByGame'] = [["name"=>"AccumulateUpCount","value"=>0],["name"=>"LevelUpCount","value"=>15],["name"=>"CurrentLevel","value"=>1],["name"=>"NextLevel","value"=>2]];
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
                            $result_val['BGContext'] = [["5B4WbdpsuXO4bYDc6+fVwiqMR8zQfAeO42lUTvmdYwzwx71cN3fdLrNL/boabvZ/eTuwxY5nVefcvUlH6jZ/wwKlLgmYSCK2yVSlCg5dO+45YfJUSB3qWHPXhYlKTm8V/KnefFd+BHG3eTQY4Ry7P9t3yey/q2Sn4fDJbOnsOhmcwN7XJDuQVJj4lIVxweCiUz/eH/ITAJnghUtnXCJpaXVBjpvy3LXW8yhBk/6GMb723q9GkImeuZ1ePQAPKwVNpvI/fT7jB8nAAWFyVExl+xdPJbOQjGShB1ypq5bbBD36drTNX0Cb7+ybQUVwmrAiXzzVB8IiYaq43ndt","C9N5irUZ1eJ3dkgLBdEpEEwTUY/THaM4IADDESPCwk4PDfu3A1oWqxT2nMUVQRMLKyy/WzrvpT4qZ40bZQn3qI6luHzaih4OFaRcNaQRT9AJbGsyDjS+x+reymyzsJu7Fq9u5JujJVINtIYeHha5sNYtv8tswuXfCkuN9lwnKt2u0Ml9XWKqwio+T6K5zBohqlmzWj4uGEphEv/sxYChrEGaq2dLhzlVQV6c5apudDc1uJ8tSGkgUsDoguEyH5Dn+3GHcci/nwXSJx961l6xq+IyU88cVk9CO2zxLE0+BLGvzYN3Nydo4E73Qspxaak0+IfqJ30MFPEacLb9+Ca8xONQGl6tX8CFPCH0oQ==","GLlFUhT1f6q2WxZVu3lpqDok6hu32RcIpvN0TZjbJ3zzN2z49fC4PBAQC18Z28dwPno2T0NjnldubRf7AvE3avqa9sBaSU0/PZOHKSvhmwDYwGLqh0DamBdq+RRmp2PztDYEaMNjM/rqTUoUl/JHc1rJxnwGUEA95XfuDDaDhqRyMU7FPuMYFRXz3kSjNjN60eg9SR3/J1B7veOZtn8DYUZKOMzSDSAoi1hp3EM2b6c/mSMKumJl+F7ENS6P9Dj3J0+UlqUHotcJIlNQTzO+e9AateYDUFi7pjwjnbWl4NmjWuRZL/LY0WKs7owxHYaNTxBcEcpHJCMYiMwYqp1d7jX0XIg1Xy1dZYWC6g==","nPGZ9OPJvHObk58OPmCqHy/mIj/5CHyqhr9KtpQl4Y/xAVZaS3UJBLFGbuaVvmBfolB++iNohLpCgPpLvQar8Y5nsSjdkoC4O2UxcgoeIw1XyB2mjACc0sljb4fg+KBj0bCX/R2oM+Oa4RjaMDs4DiDjFYatHTDhs6wV6VvR+bulLrNqiYFBiJpE7FlKtvRAtSXEBTlWoomgYIddluB3gO0Hi/N9YNUVtNe+UIM9wxhpf2iX96LQUmOb1xuFLvwtMYrCxz6HvLXcw7JlxGVPaMoGv2BapfgIb7zm07/NenDDG3+qlBiGqVeAK4fvEMevMs9FLRActdqQF8wUr4RCy9Jjr+eHOKiF/Ldv5A==","icuiXruTHXXFlDw2x7cEVvMTS+UVS+jcxv/nY647ry+2xz4DF9+FFFXUjIFggKqO4EKPjcwcvKiwhW7bSDYM9A+cqF/w6m6DGOV9QOoLVaOPXi81MoUQr5tId+U9ILw1rtal4szdZQUEJjZIaQY4h0YaXJnmBiXfm0FiVqTumcg93ea/8P/CHwGJSSuyHwa4OvzoJH16bBQ0rjhF1osgoPfjE7ig0OgZvgDvve30NWt/fyTMbZbjUdmLWZchMQv007CJjRb1S9Y3G5hFAiVWXm4hfwVlo7yNou62M83jFg25NWFC1PdLB0LXavFPLDKpa7/optvgiatina/+0BMSz5+Q+VvPCfYkvC025NrVnrzuvujCQdDS1a6Ngzo="],["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"],["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"]];
                            $result_val['FGStripCount'] = 12;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["gknYGIFeRAtpIgQ0gkVJIjZSom73yQKMFjT99+6aE2nhPmlg4qbMki5QFTS450rVhwuN37cY0WK0MTgnMgER8CDKUKzITk/q8DrYoTAREjKqzyJA08KABp6z7Ss5qLvasbS5u5NYKkWyJk+RANoN/BtRJhanQRDfB1fMmd8acMAXCgnFPqLSIk7Q1UxnL0Acx8nzDcUKh7OsixZNEvjs9F24199+knKXil1eJBs8E+V94+F5SbZLf8ZjZDlJlCAfdm+h2ugGgcCCq58XLERHFnrV+V4cdfjV0bECVk8Ychh7z/Or59jSqIKUkZ6cHRGKNgM5WDP/DVO2u8GZcXFsVLg9TycUsAqDgaSo1A==","825EuAZzyM5Pn3GC69KL6C5Vn770TBaYm6h8fVKJuALeOOX0LtWVgBxG++cO49fcDeqbYL5gSW7YNikgtIKY4WsWU9dpGjbRBdAHvV1NpihuVhinb5nunXGGCDQqQhoW+eLR7C8wa+RQ1xf5wX8xQ9P9APRmN9Epg+0iEvGaI8eU1UdziO8IuIZdvKrSLlPal6ppb6G9XAmGO3DilCWGp12fqVx7ogN3cnr4E6KudK3qtLJ/F5/q5FWYDp3+nzwDDudg6J4LYxZxiKXR8bwngcPlfFyRntyqZyeJGqBCRcQ/RemHUyuxE6JbYjSfre5Z8Wu8jKZRjmH/aCc5","I1xy8MEYAiaN9Btlqw7sVzXIb5iMtB76dQHhvY//MsZjB40jIbVKMUO3en1jhoKL6PHZelc6r9UCMmp9F1lWLaZsvxKizdsmPAS+iiI8n/+wYqFIEUYy6KIZSZqFHW0PQgQLFVn7PyMsfqucnpa6e5UhMRULH5tQQ0Eq7dLEVEAdhmQWsjCB63fwtiI2GMm2DSepA1zIWaO1ikcs8Kfo/9VtqQUyoYg9SPpBKgiF5HOann8qvXb7xYHZsBx7CLzvmPqc8+I/7Qp9VlzrZZvCKee9nLYsEkTle+3/otXZcgWWV5QuUEnCtW10JZBLeHgNbHEaX8aFehTCMwmq","CLs9f455MGu1eJnqDy/WXbEZpn1IhBbTTKEwbV2KZ0QTlizsz0y+93OGqV0uMMUTlH0awafr/fVqR4YN877tfv6FoYjWW1cxBLqF1NTgVm0kil9y6V2kM97iFBgryE0CPIfL7cdDmaNj2F9Yiz0f9Xw8xRWvReL8VFX+RrkDdfFc3hvHSAPXWM70IFkAx1wBb7Q9KYroIDX2ayE0XHMtim6Y4XkcbD3KQH3j8ihjxU3yA0/SxDOjTGTn6KkWRU8T1dY3iAyvBcacPeaJ9o/SpvwAH8T0DNT5jhcDHuQK7QJPm3wl807s6a5lPnw=","DGEgUm2zAwDQrVnUQr/5yc3pODn/Oly5PmpfEtf1HjQEa2+N4GXA6UTyZFKf+U5Zi/ZH4bMrpGbT25cGMcIBJ2RYh19bnNeU9MiciFT5uaYzb7/WBZ8sdcnsd3pWw/U1yPvmPg9klq+PDmRvs00QYbCoThqYCwfLJjfpvscnzPnwrCgbTAiac6q221MOs47aPdQy23bEQ1nuAfZBHf6HLKnV4a84m8m9lavYSNamfS6PCGFoqHEzjXlgrNfp3S4jJK6LRTOpqt7wzbMVXFzlo2m3pVJwATlYAldDfQ=="],["WhtxbqpUXT5Kn954ko8oTNUbXR69dP4tH/OVmyrb3A0bFKpxM47aTIVV3aI68pCcvKaERYD7huoLaOGAlfZbGQwDi/ve1AR8eEaTKaw3y8GbHwP4V4w10an0NyixODQqC7iS7NbIcNtL5410ikBGAqm5TchWX6IkddahiQXpYwjVKBozhoZ9aLopTMFy6WP7f8W9Cwb6ZfOfXgrSRv61AhpJsUC3RtNd1MMBSg+nPdvqlqP37/16bb/0ecvMO9jheYSVG/PCHZYKnki4G5oko3UccDiepiQoZ8Hz9LAAvPmcqIVapveQoPZsZInqiZTRyUK53CYj5ET6Qo3MaeiSB0CkYJVnJ0SbIP32AW1UNMyK230ylnIYLkSBqoA=","8lVBD3IZcP5aMOkAtdyb+7zaQsM0z8IhsLC0bFSXOyu3l14EFzmTp49OMIf/0xP4fskMS20Q5QHZZGe+vjGYTelo0zLHaacpbO254JyIGdC7txoDgJ41UkOnCIPs5bjE2G3w/0Ke4Jye016kU8GpiThzPyWySOqETJDdoo8jbzls8erzG3eGWvKY6up47nrqr5YyXsYJXwRSFDCGNoXP1eRs1glX5PDXwGKS/0yr73+K8Ce1a1SI7+zsqbgKv75Ev0fDS9LgXUwgqNp8v+H4vy8hgOfvNLCjwQibFpVa0oQCx1HRUhcMRdlbieE=","deVYUA1qOE6h9NmPQvXYc/mb640jtCEQ8JkRjdqADlWHfEiseuWs3MvnLWz4VZ8uNeuK5nvAV2OX115GEr8oZL9ilVXQTxubeWoPAYaIX/OV7K8EZxhKS9z4mKUrj2wulwhwPh/thaHg8lkrjxniRX8sVm0gxTcXpKW3TeAppgb8ucA3rhawJyvUYKcpih6rhWiXq1/Yhjvpk8k8H9CiI2q3JXovpVzWQ20y74bt0AykXvOXsIBYd4PMKrnJv2MZgdDNEsgegqkcFJzKYW1IhimPIxVHAiinzTN5Z6hM+c/KG6lqgX8ZwRNgir+B4b+Zv2ZvLinhSYPohygjJHl6Vje5ONYjAawIRTkyyhUUtnm/kL8YVSDpQzfuRbY3KAt0ZGHJewcjBeuVPYtf","dYkxm7QbuHUCPiQQz0b86733s3hfjH21/wGtjna31kJVQBnWYNHA2xwC9PAPH/7kRUny0uV7ab6j9+7FAX6bts7y9KA6gQCP/cCu/tnUQucDi49s4oaaPJNDcPrFKvamR094fIyyjPgjeEyWbVT003NN0OqfkVjuAX5JwvvfGz0LHSpSc/+axgRV0/Lklo3sYY+WERxiGidXUTHowgFgbee+9fh7xMyj7yoz0wDOAzBXzQQEy3cf4nCWUgriqEUri5DAucmBCqlV5JTsrUHJXC2jOx/B5RiKdge1JA==","wyCUvOBnUr4ybKP7GgHL7CvMkYv6VPGONOrkaEPdAMVAuKxBgYRQtLVE4tN3qjX2L9gGVA5yJKEYOdD+1EFPOGiGQKiDGLl5jKN8axvxy9Ipw+TB7bAqmms7b6aTTXHVWUl+80hUw1ZafOf1R+fFNwHuWgJL2mYouxTr0XPj3YYUdRTyvcRbe3LXwhJdqAu+DHN3mvQU8l/U1wwv1TyucyUQFnXuN1BWpcceLc0LHBT8XUWcmJE1e3JO1k8B/BczTwXhAwx9GdXTtBqXLA2agwzHD92WW9N97TCrXbgrqGRQapVrmoybkxk+EPJ0H7wkSBtBUEBaMKrXVdSQ"],["2QgyrcR50McxvoudguN4gU6ZE3EHS/fXJYITqP9vewAVzTHarNYugLo8DdGURqoxgvzsn7QDpAx/Ox9HWgUDuE44otANw7Ko/l6tmkdUANvB10Q58poLeoGQ53utwD3/tzg1skiwpb73nSeTfH1zsQm2zy/l6OkLYXIOreDDuMbQMRCfCR8ZHGvXF++FYRsWhoz89MkGMTt19om1V9AuWX9onTKinWGjWpmYwrHnz+tq1t9wTb7upgAdEqdj71pbtfb+tDj6GPys0nywDu2pH7yEElVW6E2QWLctcdj/uEYvw+iJKsxhqGYJdoEPYSHo1FHbh+Fx0dfLOPTguhRojrTkCbBGf2c3j8TXcaZFl/VUsSqEnsACxb0XNbTOlrSF/0SPL2bSGXgDPj83","cjzrbF4zZLh0rFZSTYFs8JJP9bRhnpp2kYRdao2+vEkoBr5ZXCYvb3g21EAeCyj7a07fpENCzwVlw/dzQPF/WP7ImAVW984iTML2q6weM+ZKfCU4X9Eb1l2RRh0B79iQmzXn29uhR+Zcv8WjgOfK6FnOUN92ip9ayeuIauTwK9v3gqk9XuvTso8e+GcQKbX2egGMT5r0bAlgMoB3xMUWBU57JIGCC0ai+5hG2rCrhWZ6/oCocxfQcX5E0TnqLzpZ+GCoZNCaQUiCoOLfCo/W5fEWIqUkhhTDcMbKlibkcMMM5N0W7Vwhq5SL+cxxxiTK98RUx2sge0AYyg0X0SvoF1lFHgThvGq4GzKT5w==","8MR8dqIKDl4bnBQ6vvafOM2jLLqoyESYY4weJtwTLFhCxCnmkP6JXETHQ0lFdJb3BVsx5Sn/ZQvu5JzL8C9LK/1G6NIUc8BI57Xxqix3CpWwT8/W9FF/+duDPM9ftOYnGjcgmcDK+xllUEfT3BnanXW6g7rfu7wZmM+A4wyEPBtIBGNBRKkD0SpPIXmvDTm59fWOrjBucEG0Nb7NslgfwHxBi0SznIH+K/KdvVqm7650XsZbX9a0cgN/vb73Li1QrwofDtki5LvYJA7HtJ+JkOyXY5J6HCOmzDyE6i33IM7OJUpCAfLWUJLlSTqklchwC9oIUqp/F3t4kSFkdkg3sBoAw3O2ZVwa5frtjVyjcVF3tKNwDG/5/bxYRpCb+csMmPQPtYIjIDM3xb5+XLhJHgokKr/A8/py2Ukvl6kZohf3/+jrfHRozIljFEg=","WXlv8T9nJgXuOB8Co8JUkP6kkj2YZt4dR7CuvBbSnuDPhYv0gjehPXWbhiMxoDTh5xsWt4RsgS+2A3vEXDK6Agm61ljIWGeVz5VTDeaicWUZO8cfvV1kFTtzZnXouuRRLBQOlbZV4/6KSS2ORXs/evRV+EDDIrx5UYrcASZlL7FCxB54i9m2v0Y1xOtsONFQFz+8pT2vnQ8oZuX7AoWpUyLwt3Fjd27pkD7v7R6aVrGSJ0i/tNqPSbSbmRdN11bkOT48JEsG+ePHbvWQX7ivl/6rdGfgW5c7/ZrBYzdpWHWU6n/qHhRIe9estKQ=","8UOYEmfR8M3WCYVZhy3ozFfH64AlMuP2xG2gqr9JNU9Re03KMDVoWJIeCZjFig2X+qpHhiv5W652ukGoXrWtDEdMdFabNpmFLRFH8SBLMPqHpS5g3J3+41KTNvcG2v1ZycR6ID405ozz+VPEloO62X7AbMc/XBg5dmN3v9mRWuzmlqTFdBtVAk8V1Q+CCy9T//iCBk97SSQ49h2A/ZkxbISbOIyVNb7AinENQouvjQcNzwKkOMN8Ni4ZhW93HJpx8P4Mk2tNWrqtAFib3Fcrw0wlobq5Gt/FuBMUJtdBnwDfRx5iA/VQviwpgYQiOyO2UwaSftLFDfdMi9Fu"],["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"],["bGD5wJnWI0oHYwnuX3EQZ4yqlTmt+Gywc3DDw7/Pd54NOHIGBy3U6s5YTUAbT5q2KD9YDpkFX81WqGsVLqZkU2av9HruiPcQJAYFNbTBasMi13o2k8vgjcEYfydOEUO8qa/rs7po7ttTmeeaf7SvazXhwqHbI0Oi+2ZkgjI8jyM7aKIY2hu+eKCpJtvqNgJXtoSH3NkLSdopsShx7bAbzioe6kZq5cb2RVl0QqQS0TJ9NiakLL1Q84hQw0Q0KFvhs1HubKo3sa4gcmvsgo1l5YBKn/6GDf4uhldKT35zLD3nvSd3Q0FzNBJtsHQsmD2D6a1/Hddtvw+w/gEjqUMDp6+JVvi2lboGsIBSRQ==","W75FfHj5fssR2KNhK8w8cU6igckB+IavteW5EckeWqVrwoNM0ROMKSVW/RYlJu2vDeb2Q/u11hLCOXvvhfD2U/wQSBHo3vJcwcxfU441/udnYnAr6eK+SrFGqls60DfXr14mICap3mqVFT1aR8kAyC84qdXpSm0GkiT6WLH9nOWKICz0kbB4B9LpZkYu0MzZvfydorsh/ga6h5ax5nfEDgFXkuQFcLRWiHLm1wuAd3hTbYYl/5a28wKMf+quaI7RVbRBHxKfXqN9fWWc297y9MxOJ4qKMdpdHLmPeRjO3RTW1kzyCbMkNTjcpxVHZnXCRCm0sW4/1Fm5+UE+","3iHdWolIZcz7WVBklQzUOXLV/XqulGNi9ZYDP2mgdasvf8NTdzzmgB5M8G0OUKsQAx3ycWRLsvxE6/uD2erqxvJOi0UIdzTPA8YCHKenfs4FdJxre79APBB1t86ZAiL8ork8lT0DDCF5P4cDdvqHVOo9xVm19GbEUOrfN0BrsQVAkl2wxpz8kyxL1X2jBNfMZL5bwg5GP2lcNOxL7BIWkjwBhHBNoRSJnHb4RW7NgaVJrp9qxMJTfiHoV3tF0Xliz0gAO30PSlguJPcoqRSr+tzU9hBxGAzyCyeP7ruKK+jemVjeNJulShwz1BsNzDnj/qkzbl6pp7SIruQr","xJqTYdI7GKXPcnBuIjIalQX3D3z45Yf77Zs31Zqb8NNMQ61+ptNnZQ87OEBJnHRlnw3g5Hce/RwrRepRuGRkjVskZZ43c4YOYcYAjCuoF7iN9EJ0lSc69OwDoL0WbpwrZjW9PRdZ+USutElcEPuzkN+QTlhY3la4DrRDDkEmicYoISBclVHgQ8ELVHReNmpuczNk9sRsbhtuwLgL7VzLgLQlGLA3q+/2Xi4zHrLGhjjHzJCAz1JhzSR5UxwxQvjjj7gjkk4RuTg5NljumBLnU4rypcSzkPuVlf/Ooi7TMVEGmQRK5Xptqw1CVhY=","bNvSicmUDLJ35N70TQZ1eVySoS7U71NSCYmbzzV0etwLBHxRBuo9eCZlrkAfVkAqEb6U+FgKFG6cKxuXKa8Q+XImd70/7H9goFNHDRLMxhd9gQ7DLaYovOw1Xq2q+mCtk1NB2J8jdwVDNvFs0oZB147kzpOnsmEh81XBRB5UonXZlwmJkpOZa1X5o3vnQSeulmY9qmrS8rQyeMWeyTpHeRdkdN2J1viwpj0RN0f1QNZjEk7WZ9621vr0o2BIR82jrPCVsBXvSOtmvSRuQB5SsF2J6r5UcCLOrh6CgQ=="],["kviU7qARBJV5eyynHEA3bQ4hJ6pERcMwolCKXfmMzExISTntWZS0Fjn7TfWC+2HRiRaSeXzoo0YpPKBE5wHN21GY4Jn3pzxczGLhacfNIhNPA5jzzPeGRZXj4QZM8+ZHJXIw0M58ww9XRvMkJIKAp2G/wN1aOrMN6ZTPBAkMtGNybJ7Y8LphXO+KC1kBU4M+dMTkhakfIki2poLoJ9vPEBz0WusX8N8T2mOpp2LmIlhw5Ss1Z9u20RldkA+2ggvC7O1YWDMr+q4/AJqkSNMMYZhfMwUl2OA8JDEsQrPPiUgaoJ7OCOjxBJqf6+eH/0IzDroC2rsmgJs37wy9dLaXWHRv5WyVslX/ZAvfiXh5RoHBIrlsT02ZVsA/z5U=","vUSAayb4k8k4kLKbuLozjKf4RjytEb8rJ62oFuU+F4YY7TFuP+5LVwTBcjdEEJ5HvgnXtYul6Aae0soZZ/iJGAY5ZDT5/Hz8HKwHBAjNMcwuCkS/PMVXL/jg1nZq9nP6AcbrwCoEYzHejPM6qvsIk2U0iaz3eNF4H0ckRnco2xf80jJl+3LGgxrocQ9TySPZLgWlX1sWnwEdBhf67YeYVP2AoxGwODxD6FZkTyiUdg+8r6TKR+l2vSfe01vLqpAS+zVFNxarBl0UPcH0LMXXMoOTJ6MzJfY24Mvqff2OBSFUEJvTZr2TZieCKu4=","mVS52WdlBUyuLJqVk6Xvkdw+ewQ96i0DCSY97xWJMcOTonXvZ5dZPwOdUyleaKS1Oj8MBHtOmkXtGXTxy67vb8uzXQRrGtnYKjDe2KgQQ9KhI6qw0R2WcoUI9/a7rSnk1Jpw+XuoRLaukVtHHWXQUbLZxaZ9Lb2acKvlFRl7wVFNDwwYlSFasU4ZdhVpRdXpdVVkAaUAQr4mfVmVWr/408VbhRcscGRd6LADR2j/fKQzRe91lcjRkU04w5mMqwDsCQkSOMRoN4YKQbDa3Oc7lPY3XnMgusJAg8DiHRAsuZ1OkLsoyugys570IpqrbIdlbqyNGfIpwuERPJB0XnFQKjO5xCWt+r+q1oxHtq0gDHt1MXXmBGMKnbFLaZobRMXGLES0uklpRSRHK+Bq","lk82uFmBZPac9NlSatzxP7rWWHcN9H0l4gOCxojxmbJMEw1+P+9h9cv4ZpI0u7xP9zXEjJi967Dj7WEYTtb0fl7XbTFHiRJABC8piQCWor7l0Aod8N8n56NK86ps/JPCr2sSuoH9IDGmp2+TChK0gBz4KC8yYPqHSAkdMUyej0V1OJOddj/rfoOngdkJ3Y36FZhKLheEcbl7WFlEApSW8WcKA7tSnTehl1XTQmPFx5kJCAldM5ohZSN4vbFvmKLmkY0Re3TkDE8SDqslseByFcZYxco9tR0pZSnlSw==","J0qH94VzPVMjBB9tcwMLeQnKpLduyq9CvDzTfR1jMdAJ2PkG2EjghyxLmdsDCxn1YG0VID2IBMmDO6UDB1/v6G+VQg4Msnw4rmD5vIx15VoXyVkaT4SdakY89O24C3oNsaOpaIQpsU0WylRs8sYDIp0nQCYh2/19kiG8hP1HqCjJEZ4kQWobbi7tnMmm/XydzO4HC3cK8fsVG7gztG/9ruWSWu+4dfMZGDjdKjGoebvkcl6uoYn5qKCIWkBH/9oTAcNT5lApMsLD8MptO5VBZRz6SrOnJzRZnilLv6hMW8jbdaYf211SeB7W2o888nCCEbJu7a/TkuB4lM1T"],["5YCJea3bfweLNH4FXPgEUp5IJzPfsQACnWeKdatv7ZkK25BPKnXphtzNUenSZICQ+569XveV9UpJnFPO+rrPKMAG6vYNC3f7gE2fBcdHzKE1+HUx8H/k7g8DpDpRlZ8+a4EiVeHzBWFogMlU6nyQRkW6QJoff+VepOriTu9oza+8putgCKkOA+NgfyYV3hm/vqg39/gfAHgFOk6RvQhZRzSX2zSn0oevoWeNmWsMXVmNC4y3HYM5x9eJxXZKoTuVOmLTHd+BLbEgvMQqE678mxoiSnQ73XPGtgh69q3jjncBDaZlxppw8GYUYz4HW3Y8zIaUcdSFO8bllaPQDOkwigmxj2XyBB5H7S1fMljiZsIdV5ax8iWKUrxzw/3erysjtYIMSWLYCGQgFTwh","QRGn6XrJuOWjjBPcO4kAtrZPDLmZ1G1R/5EI/lNs0QbFUwef0uk7ysNVTobvAg7EEGCM0qXzUn1kIebXkHOqyNcpBsF6wcDOzBLrM8AfzqIb9YzDqV3PB0+Lv5ytP0qu165MJxMVqcJYM+X31w1kVFiFwqnLwvBYfxa26uustuAPJKmzC/qQi0W61RVumyirjaDXPVM2F4LGcLpeHB6hIaULKmh6/QgbnbvEgaP4INRS+hanAIqkIbW/CzaxRg14rGbFCvsTLQNwSwkOOcC0hUl4vblCVO35NCc9EXlxd6Hw8dJuIRtb8HuLVsCnEb/V9miFX/a0dVSTvXle2AHCLYIfd3mmnHJdRpnNMg==","ObHfvATdq70vzjra7zRxO+un6oakZjGzgfYiht93r8Rqxou82xUxs1dXALKsGGyA3AIOfN0cHsrrlTcEvesBXt7XVu9DRXLV4Rp8fg6XRFe2QBvSlUB6J6Ot82PgNSqCetkd3YEOCYMS5t3hM3PfXVmZ9+GGQlc9vgkC+3FabohHmPOdkKnFA8spbzJIRVqaXiKePL0SQrdk1nkOmRFJMUm/aLExqc/wBi27w8ZDy+wL3q0ZRP8FsN//mRKKQNgtPIoZko47uY2//DGiKBAHfZHKo+USmXpNwkHoOiZFZwN3v4sn2UDePWzHIOWP4oudH4au8hLMWTEPEYpl1sW+L4xjWhMpw38wIH3/2xWDrFYErzhv7qNelN5zrk89vMGbdQRV+VoGIBM/929qeBf1VwCeF3wzGlGBDqJyQkvOlveJPShzGGv9ZLxkcr4=","mf9NyS6P15sMIB1GLa1wykgho4YlKD+IPGBy81iD5xTtzVnwPgHwQmklsDUqmVnzBEtb0O8JnJYIXFzyOAS5eNpszr/fM+kYSIcXaErwXynX5Y/OicXVkJSnmkLsMTvmYkaMP/p8EMKSsAQXo7flIrx/GuiuRcIVr8HaDwAnYDb75pE7WcAQb+JWVrZTHz0dXhlGiSr6sNjZe2qrQgMchXfzxHfUk3jhQC9zx4xXxSW5awzw/Y5T8fsJoDCNyzql7odHeUSh4+tC5uYqk9rSTSWSAVvcSgYu3NXQUc5TOihziqG1LbjXpxMDHzU=","MBYO18OFxAhSyOR6pFKmezYNRM/+cwiDR3iXphybggwPu7twPGOs/csHgLfaHNk6P8aQOYr2wb9IAa0gVDY4nsHVqIeQRA4JdbLeKA4sYOXkoEyCvp5kt8JlgUtzb0MJoTKpvQqV21+QTKcxMYXc/cwLGVmRnJbh+V5FP1dwXeT2s2IH+9i+i6DgaEUbP/qm/o/wbij3DlzHFHYNtmDfRvpBkDa8QlavKZaVkjvNjsMhdg4YzhX1UxN66nal43i/R7ofoWhYeO+H4DOjCJfNnX4Sldb92rk3VXPo4HF/+RhKX1saiQmSshwAe2BmVdR+ANrdgk6B21DtFojs"],["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"],["eT6XyKVgulA1O5BVFUYyKAxAFsWOxjg8InIDh9GXw18AXL7TIR+m/SBG/IHXWcdY+go+6v36eYbb4OrC93sLZYO+jLRySq5UCl1WPi1XPRdbpsA+U6O3q5r8e2s8CIXLDqmpqlKnv2b59Wp6T/79Go7QHUn7XZmH0Ia2KkGMvzJmMe6L05WJ4crxH5Hcsjj64DxmG4oiVUukx8KrZQPZ1DMlRnu2Yg8X6GeRnJkg5am3ABQsA07fVx8dPVV1EQMwz5eleVm7QF6mLFsZdxU7fwuWNaNnyPMSBDZl0S2GfE2rOX/tNgtGYeXJvxUuxKz7bAeObgu8GljLzn/K9cTaAmJhyqc2wNudnm+Znw==","yTGBHYY6edQHVf497li7NcOs2RsxQCl+I06lI7q5ms099MZweb6KnzQpVxCIXionnr/WFMXFy+AH+Z+8vwFhX7l4fgyHsJOV/lvk+EDx2XkGTliEOEsgYJ4iQC28+CzPbbB5ceEycsDEkzfiFXvjhoNuTOo4r51vu33Nq1XdRbk1DIXhzYTW+etFdAAjU175adOMZdZiLiuEb4yUyHMnw1t34EEehMxf7HvrzTBWRZn2DevHzcLr++FJ/8XwJnbRTiXxaWjF4XTGD7dNox3KDdEc+iT9tlambuGu9VS5Zl9zKyvwF/NZphK3+k+bGdAcWWcAlBiPhDrC6XL6","aSrM9Ny4ZbKjUkT8z7FWfHQDkFHdzyRe5yRDOy/mXCjFtqGFJNe9fbDGikDkUHne/RQ0rdWfAEWCYAuz+mYYHD8IMmgv+cRL2q4QI3JNJQzNs4qD+Cl0wtz6gOziXmdxxWF6/Q1kM5SKkavRDXAxcAiVVR7/hfm2U4rzW7B2Z9LHZGelSO0anEV4Ry2LRpURxwXsvCHxB3WBoq7S/XiFvxScLLogGXYo0PHphTrz4Yh81ltYoTjNAlpUGbhniQNWYovPrDtzyBqIodNMQ9ppVV5gs5tF3PTsUWIzk/TUHQO45tOQ/3RMRP+9PuUaWcgoOl7dHPJtB5eilKEt","kWjh4DkxZVtK5mfVgw1QM83fF+EgmtZvIilSstBCeNkJoW5QHw0WcvPlECUYQD24+nGqZNnQKP1t0Dc+FQ2PavdeRFBJNRvlDn+x/ozXrsosf8s1v8s+anPbhqDiSchG/0phQwHwvmDjHjdhATMJ1YRTZCAqonBsK/xUiMQcHc0LAPSqISiWuDi6iAsE1jqN1Zu03ZPEoYU6VcfmbFAu+QhqTA5+DxSz1UijxUTTaG42UtvBnsrujiRvgHjeX5AVuTuwtur7dSLL5WvVauPXlH8kc//3eLO6b9NXvkQFmD2K0IIq7pNpQps3BbA=","QNlmFRpLxDj0ioR1x1X48OxYMwL8Wy6Z9TEKEsatw0580M93dHtZ0ROvZQ+h5rr5REfO7vjFChl1KEq08k/BLpnBM07UBMnezUeMQoNgK2Cl/pqVF0/8yVkeeKggE44r0UALeweZ8tblT/L/nA/Y8gVjpkh7mrcWFHte7NNacD4OJB/MFu0ofkPizO2D5P1zhPa0D1GgMMgJpNxO/rnJ3GUJcFzb8YjnWN23pH1Xfg1ze56a8wDlrIEnClnddFEWG/BIWKtvcK/Oq5f5uD0zVKd9M+nyU7KTnBPtng=="],["CiMK4me19cP6RUHjBZ/WXwbM72ECk9+byAJAfD7QVf94+pE8y5FDWLBeM6xxtr+pEJRGlA9qxWYJFpH4jbbO3P3tziGnPYIDMJYmtEVOBBx554PynRqyjj0TWtAINo2wvtDXECMBi4iBlUCDbnMlFF745EZaZ/bYpg3Y04sCjxOm2io/cn21hl5juXuxmdzTj2PSTG4ViDYLB1JtH+YyDB3A6VL/KEIMz+U4Wv+89Vza0QDH62fq3UZBHPKb2R3fbkuom+qjRhTqujc8TjmeTT49U4o2GVNvkzl6TEWV0ZAJ5l/0EZrz3ULvovvk686dLKr6D+J8FyGAVFIOb1shOkdxbGav1PJi5kzesw1YmeiLC6w/0AqGaqYXxms=","uMtVYIDUDAkWAkBB5ta6UZnvJHn6k4e+4ooBBoXds+ncypgb63JVx94yjVBcSQljCXP1zWyFdtD3U1yzCTmqG7hOxM60iaJdFOOMF1t6G9EPjh5Km8NPQJ8Eb0BeGnnjHDbCs1dxqIMaZsnOER9J82m4rmMKK/KX7s1Nse76V8wBFIxb0ZWfbjzH1PCpeCNmwAQOQewm2x/2+0zkZt8Y/YE+TDbZC94yLyWVq3dBeUuCbfPVwXLSqqSy+VhIV8O/UGZB5CQ7opYOS8Jv0pGZIBAmoZ3qkbevn/rHsc4dCixYSpHgxY/LvvCeEWI=","YEiVnJDzhIyZQoTAk3FvXL0YQewhhCwdpVzMZPqICh+M9W63G944ONn1Ay6BlDeoIRt9etCGRZlU6u8EgqwxR0fp9SRAsjnA9tF3pQsxwZwO6x24VE+DA32iH7m3AJBbI+7WsFDwNRM2FzDG3GMstkGUrIEHKJ1OrABbhPWCEIZoqL1pBwdGWXe39FLcfr1Tk7AtuVSjGQBuZ6YzOAx5zno6Py0fL1lKDKKpjGxpldcV7VK9IDmYukfiE4dvbp5fLv8Sa9mGrcMyEDJNdB413/Brut6KlYmh6l4RT/+6FW8mWqHcoGWnfVd1yT4BHA1GM6j8c/1UXn+jK3TAq7zc/4yiqCm/vCNK93H5laj8/Bz8UNF4V6QhpjnnmXa30rch+uiwut08zLwHFsNs","pWDwzpOpgXfySoJBhceJqMeIrKd+kUOLbHVikOsLS3lgqkkZpZEtjUThTghqQdBFOBMS4uCiBnQZxSmTIq0ErT1/cnnPeBoCkX4PcBt6w1nXnLZM9WyjPT9vs2utaTNHSCi5XRnyIiF48C2pZuuCRfqeQ9fTJsAt/us973vqN1kGSjffJoeEtkl8DA9qJabJFesdQbLqgZDZwggSyeW8Za+S+E546m8yLyvq4lH5oYD02QaiJ//AKercwxN+aGg0FRalEYhRlFQMRs22Njo2o1o0jwRb/lQ6ckOP0Q==","LxVT9XGQzrZ8vksEZ14IfmFeY/VzNT4N2d8jQXY9YBZRmZ8D211n9zT7Cca+gGyx+DaVdxzPvGJL5nyiQAq4XjvLLgPN8LgyRJNZXYmSV5Ep2EH3vgN92JvVIEYcvT86YY/D2qCZ42iWuGklh+/X9bEBWvyy4QIXUc0Nwnt78TuZsl3ja1p5aBJAH1aZZlprZDyPqz/RrSXdqhdeAs2H6B7S7JQgxe5Vrp6yxDccAP3Tvle6Y04G0n7jHNQG6jJNIG5+kYxsusYmt+yoduhRgIsLll9h8Et/C1yqaUm64JWD8VYufZhlG9XVxSZnKlCTWPb5xLz06QNdvFD1"],["UrbYw8hrrWTYX242rEQz18HpfFeL6Ew2st/svo1VB823qBf7Z/yExs0qgbUCbCBFjGCuZiozUIH4IvKp1I7xkMukVPMWa022jNGNmGI5a44JHwt2UMHwNi9zPSf2nCyWRSMtiGkZugIosAqzSW847DaS+yL1f/b574Tu6FyJykBvNsL++kT24y5vGsszIJavRyTNpV1rad2N5yY38gp5thjdb9W8LjqfuOptoMJ3JVEBF/x+Bf4ahhFVawbnER4ggLevLnqfL2meaiAdkKSKp+Mipoh287BVyQckU0TjWateMArBzSDx8u3B5kyvfOt4HjVZgQ/jzNSo9ukBlkXP8pTKlkdQfO9ok4TVKGNvU3OKRvrAckjjS1JmlpalHjqErDso52jI6HECYD2I","dMmvdhVBIiLJF4tym9Cu70j85WNTRBNgqrJYBDb/w5mYWNH7dAthWM+Zf7pQe0l+JvT4h/8MJiDt+nc4ZGMlapUON8QGVY1rgpJeZuVq8bY7GjpmPF5ZH+H+7CpL3EMInd7S7leIymBXPhq5QR4H1GwDu9GlkGXgmPAKdPBi511cc7XDMkdk8aO8hhmWFjC0eExoQjf4KWpb3SgCb6+yIkVbOWgCrFX6pXXWhL1JQ9v9iM9XtNfU9vKJI1g33SPJhpQZEpzK4NWapuRbS1IOLfg+EGUxFXkeYxn2rCL/G2rYnLKEtFWxqak08VQOcU9+lcR8kC2U6ww5uuVyDRtUWOFbzE6XKC1mpXEssQ==","IObtSt7MgPErAAgNImFu6S9ldxu/zCVnK3FvBIxi9UOFhZr7Nai7bo80fXV4uL2HsTox3XWjEmLhKmnZ+0g1DWzEiJJrTurSyAqcvXlptY89w0gWZgMvJwXEjiw6DpiNQA2MkzIPMPayEUv33QQGvY45Cq/OMb28fkL4hGU7i/PNK99WStmb7QfghUYgQWVHdZ38LHRwJ1n+IYAHNOn6PYLSF5fDlmEU24Vh3+LafzevoXjqtiEBtfzRmwpTnqRrFpwa885uyb5Yhu42F+k8uGdOWNjhGe5kzpqgCrLTHFL72I3oyF3WrRuJGnVh/O0Nr52E4CrOcD2ry4YWEKSoGEB/Dhg3dw/H17sn95qoL4Fb9sjwcP6uDDqtZku97T+RaYZ9tLuw9ylM5grRvTJVKbhOF23D4fZeOgBbYb/ugDIhEUNb/Jw8cozcau0=","elEw77D8lHTzUUZMl8TSju+zRc9jAFAiTBZeAdwFzJXpeS+MXr2QiZ8LbL3lsyZkL/8qCHx5y3h+xvSPFEgCZNY1ZP9CiTjpYHe4NAq0bF2McC0M8cJyAfOwUz5L6uz+TsaF8DNeO/44YAgQlpdxB8/iNvIMzTc2yfSce6D54Fo0XjhByTrHYXz0ZxLgphz/k24+e7M437s5PO42cQ60NzsVLqPYgq3mMAPhusWGqy9KQKilHYiajW3GrCjRravIDRTCdJ5cUvY3xG/nKd6yBIz0glZXu6Bl0Z1wFg==","xAtrAAAD9OFGQiUoKGz+uxTXkuqRtrdaCCTmxs7St6WsAYcdyyGl01UEtwalK7RJYjV4PSlPUgyzy4lRpXBKlM6o9j8PavtBnksjOYJvZ7kfMVCOmjhd1MaQ9pTlW84don6nw6FhBniGsyvfGA+O5E072sTri0cjJAPCwxmd6akYRCJ5v0lUxbKM913M4PgUaO2OHk65Z9G0eBt4f9FayDSakFmFbHLQnlMw00Q4dVbgrViVBcY+ZlwLXREI718VP62ggmCX/T+txGaFc5VPmsmIy/1bAOwhU22bwFi7C6oBc1j35I9BHAK1/shabDrNyYcgiY0ak1TTk7BX"],["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"]];
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
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', $betline);
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lines * $gameData->MiniBet);
                                $slotSettings->SetBet();        
                                if(isset($slotEvent['slotEvent'])){
                                    $slotSettings->SetBalance(-1 * ($betline * $lines * $gameData->MiniBet), $slotEvent['slotEvent']);
                                }
                                
                                $_sum = ($betline * $lines * $gameData->MiniBet) / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '647' . substr($roundstr, 3, 9);
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
                            $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            $result_val['PlayerBet'] = $betline;
                            $result_val['TotalWinAmt'] = $stack['TotalWinAmt'] / $originalbet * $betline;
                            $result_val['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                            $result_val['NextModule'] = 20;
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


            //$slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 13);
            $newExtraSymbolCount = 0; 
            if(isset($stack['SymbolResult'])){
                $gameRound = $slotSettings->GetGameData($slotSettings->slotId. 'GameRounds');
                if($slotEvent == 'freespin'){

                }else{
                    //if()
                    for($i = 2; $i < 5;$i++){
                        $tempSymbol = $stack['SymbolResult'][$i];
                        if(str_contains($tempSymbol,'FW1') ){
                            $newExtraSymbolCount += 1; 
                        }
                    }
                    if($newExtraSymbolCount > 0){
                        for($i = 0;$i<2;$i++){
                            $tempSymbol = $stack['SymbolResult'][$i];
                            if(str_contains($tempSymbol,'FW1') ){
                                $newExtraSymbolCount += 1; 
                            }
                        }
                    }
                }
                
            }


            if($newExtraSymbolCount > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', $slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount') + $newExtraSymbolCount);
            }
            $stack['ExtraData'][0] = $slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount');
            
            $stack['ExtendFeatureByGame'] = [["Name"=>"AccumulateUpCount","Value"=>$slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount')],["Name"=>"LevelUpCount","Value"=>15],["Name"=>"CurrentLevel","Value"=>$slotSettings->GetGameData($slotSettings->slotId . 'GameRounds')],["Name"=>"NextLevel","Value"=>($slotSettings->GetGameData($slotSettings->slotId . 'GameRounds') + 1)]];
            if($slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount') == 15){   
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
            
            
            if($packetID == 44 || $packetID == 45){
                
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
                
                $wager['order_time']            = $currentTime;
                $wager['end_time']              = $currentTime;
                $wager['user_id']               = $slotSettings->playerId;
                $wager['game_id']               = 113;
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
