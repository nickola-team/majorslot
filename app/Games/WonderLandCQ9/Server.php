<?php 
namespace VanguardLTE\Games\WonderlandCQ9
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
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 7}],"msg": null}');
                    $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetBalance().'],"evt": 1}');                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'TriggerFree', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex', -1);
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
                            $result_val['MaxBet'] = 1000;
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 300000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = [["name"=>"addWildCount","value"=>0]];
                            $result_val['ExtendFeatureByGame2'] = null;
                            $result_val['IsReelPayType'] = false;
                            $result_val['Cobrand'] = null;
                            $result_val['PlayerOrderURL'] = config('app.cq9history') . '/platform/?gametoken=' . auth()->user()->api_token;
                            $result_val['PromotionData'] = null;
                            $result_val['IsShowFreehand'] = false;
                            $result_val['IsAllowFreehand'] = false;
                            $result_val['FeedbackURL'] = '/feedback/?token=' . auth()->user()->api_token;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['FreeTicketList'] = null;
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = [
                                "g" => "116",
                                "s" => "5.27.1.0",
                                "l" => "2.4.37.4",
                                "si" => "38"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["Op2prfzwA5euAKpUpMX/WXGqe62MKFq6xQZ0LJ3edXJ//uMzUtQl7JlZGsUgbWDQAB8EcOyzYeObxEmiGHC2eeY9tjAT1/oseb65tY1UPee+zqLAGoEiKPR2eXXjXSiG+vAp/K0WqrlH1raKGJ7p/LPNVxVVGQmOyFOBioWrzcLJL7ZNmHH9juiqXpwGgGAWF+kPDKJfVX5iAFvJ","5zkzxN1w9k3u9WB4zG5hJTTSzBdKe+7CB3lT3qljzfpEOZBZX2bJh+ZH/uBoq/iRThHE09UM9Ipr+aO7ZJEKWq0BP8DQITetcoU/paPd9BB3f4yw0cZ5Ywi2UIf8MnV6h5YlX/jstYzcUKyjjZKPLUpL/5aQb1maJQM40XqfDO3Hbtz86GEPBq7OVTBmT2tjO4PxvUdscRKf08ac","01u56fzEOaF6wA29Y+Men08dC4iqp2WAqVxzQp8KC4XtpKUZHYAoxLMmxS/dMLg4oWk1jOa2kyhicMf1Dsk850kJQS8qGbL14SFR7n6Y9wwgpuMdGlUqvuV3cC2Z4BMlvVHatTR2ror37yav2xlx+1H/E9KgFP6EyMZAF4CRSPwZfRjQs63/3ShC02coDp5ge0lUIw/E6Heufll06g/YnbKUz+rNlIus0RJgtVGyxLbhlASxwbiZPkwTKCA=","QKxCvth128qAmfdCCf+UZx5tQfeX9DbQKfIqU5gzWRHNuH5fwKc5AzG87JwigPVGAdfyqPP9mlauzmpaTGswt5OIXXIk/qIrHdrwGdlvQ2RahB4z+5JhGQ6iFwIM8jOH4TMsePFsRypZQOgFx1U1Xcz6Uuj9sURjibvarMr52SkVQT6TK82K14Ysl+GTednC6Q/WfNxT798M9EfC25UcisXl0F6Cl/8rJAPsJQ==","wJzqm1wC2zJysuR5gAVnxzbJzOaW4Y97GoCbw07cmqQw1RNXVOsguQ8fl9E5JEdwQVd68lHCAbVSTQs80k+vvIRbzpGeoUUvQ3C9QAwXKk3kS3W8tty91fZ3/ah9BXoxKebPDVZdsdJik+fl/VZM+RD/K1C6tTsB6Z6WYKVonl3G5AcG9MVKhfA+9ovr4D77cS4YcVonZ3ur098nmIt2h7MD4Qhulh8OwM0spw=="]];
                            $result_val['FGStripCount'] = 5;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["OYeDMZKfPlxXdkODHFTvF31iXGit+ZShbxoABXTNV8CZCykqJqXOiMkTTh8+KRGIfaPV+4otp9XWJEkysH8W0jA2eMb6ryvkJbayybf/O99Y2FGB2d52XZwIW0eyzC1hz/ZovTy/CwiUJsfnGZZjr40KsqkdgAUVacYWQ7qR/qkGg0RFK9aqUOopM8Mz0XEksT981m/Q/4pGefFDpnWB2wbXArRDjt3aq+OXkhvA5oCJn0lrRqEgPzai0IY=","lkSz8vRz11QuZ8RSB8kHvDVXL90q/xnxo3kgtklAJspJeLxhDvC2Fsoal2USrdwhNIqpvCJ3CLlS4uWuo3GFZFaxOZKiUiWmTi//U7qIw9SOl6cOIEu8w7DTL9T7nYXBeJbyn2BW2UCEc6Eb6w+8psR+iFHz42mG6I4gIuuUMryqcFBWdLto6N1s0anOzI2OUDhmjx+283qJpBUJaIwNxcVjTFVNmuPpTWbHHj3iRKjgG4isnzGcfSfhwZjiZVsx6sQB8CmfJedstFy7","aEvs3QNKs5OFUzjgPjq15sxqWykqgMKrTkhr8XWAo+jYWoO2YzWLptMC6glaTYQ5UJ0N+MsnNLsgwoLqJKnT2hHiAqZvhm3dkxsjwzDARd7Kj9wcxKzeE0hrxmpHQhiMFwXkch15zKnS9XJK4Azv2HBTIJ1yLzNK4SwLURoFmqM0eaJYNLEZ5TTeS568e6anOzH30ecFrnl0wMQx7hnZpd0M7H+BC7UbYYwoHkOULtuEhN6uTCgF3m/Phk3eJNY0wmt7glBdRuDBfwck","mhwvbfi0c2zjsL499UzMGfV3t41p/akZtkdf4udK7bwewW/IyA6mtaxGwvLie8pnBZk6m90BDjVnQSgZ77b2TrtESOlMqYS4tC0rID3haY55An4LpoEHNP+4J/xmpYDUGw9j5OFDYOk0mvetjmgzQHJj9hf7LFy8cW1fuCEw+JWmgAbEFcfODYNWG8TF/pPQ1sIdtIr51w7pmbveUxR/4023ecwLoo8jl62zezKQNFMXDYsQb8cAfj2DtdLnAp+3JqCTyJdbWmT/fvFY+wWFFu49ZPHa3IBvQbsR9g==","kaUxrJXhxETaPbXLDtLYGxVpMZY48DBQVCN65RLDgQpzQQMX+b9bGTqIDTFvBKCw3qP4EBFwUFccyQQFthmUFocZX5UaDCMJ9kuA92SFpTfw7vZJuP5plulFZdJkQVl/d5igpOhx1UAf+593SJ8i2oRKd4oU6Bog/TNMq+VYR3v11OYz+0tDmTSNrif7JwFEsrqNrss9u8YH4YzU3284CZTCVFX6ZKGCy1XXxpRFJZLmFVypoD1uRYeJtCY="],["93Pf5Rd4r21qzHkowmX3r3uTEDPLkfdqTQqlom8uaG45RJn9jbOG892cririprUEKPiubWidPMkG611C4YOViePQvbKhUCksZEs8tGCeMI/ibhoulnsh5TVd3naTWE0dWOE8FAg6+cXH0PJQko7MiNC1buDdgGWfDxp7al8kJbizgDMcq975+XTkDgymaC6ShE1FWa3Zqj71kH0bTJOHhM0H5ro+UEdep/KUyo+8vBTXrTGWF8hLmlBVpzM=","G6qT9cmZ7IcFyXKEROy3w40o8zU9nNE5kPQUJZk5AApzEI/VRkxKiJsOaQVRlr3RJmUncWSYu8KYMZLshQugW8haC7HcMR8k1UxwULW47J1xerztKyAo9bfmWzwMUNC4b5cxruHFtu3CmhBN+xOnpELJNTOEPhGA+AvfiSonefulnJWElGsuz2lordCrQIU1dgpi8va6rSwz8DQfiaLoyaXjJcTfz+y83myRc34jrsYCJBQTpcS934W2N2ZWGvbx3bsonoNfHJ/JFbUJ","UBmn20pGI08RmoswQP6WrSzWbpZLfNc9WNPtFM6SQmYLTpPUJ3OwV45DiVB5LeW0dYsmtGqt2g2nQmeljENOHPO3tcOK4X949dV9F+CI0N83Cdk96jF0kDmH+6n19k3/s5voomJOKCN1PQ84BCQ4RctR0Rb+DggC8tpVqJ9U+jbzIffShTXgUQ4FiQQNzfqez2nl/UTBZqHgBicR/75WXOIVaD7uXnp70HmudY1iSdEXZPAH6gBJ0yNPhFEViYK/oV11D1TlbRaDProj","m0PqCdbxutzIYSGsi59r4e4mAGzribGdEsrv8TAiHu4hLb1KnuANaq8Z/p/GWrdccsW5xZISGc1cKYl8550PPoMNGK+/mtczIDBUJDansjxsi+fdaEIhiqnCJO9LK2Nqx3PqRyf2irSQUUfbLrhMICTfDre7oDl7BVllLs7ckeSd5k7Ki/2o5fpOkH9kEEAu3GVDovs9yjgTWfN2UuRuUeOJbNMesY8rHNnHtbAaLl4tT8KzTueGYVbg/clLnIQlWaDiuuo0+s6LK0GG","Sdy2j4hCmFfwhLg23FS7m2wuwjLaiYXqtBT5VoH0Eqn8Amdear8KQ0BWHS8PRkl/kyLC+oDgxw58q4f7ErwyjqesEELKRxO/SRONITOaZgMzEbXAZ9Oz0k8IXMFzIgEN7ikyuGTwuJaXkCgp22os7Tlyjx+RG36WV5s/MVPyyzCSsoN0zweSavaI4gCtsts43/m2ucw2gMttUMjkSFVzi4PdttcIbZSdPeD89khEDDrsF3yOzwLJtjqY+W4="],["LH7PdoOgWvI0np6O21ElAzeY4h0OqKv6V2NoIY5dCEDh2C+oi+hICppRW8H2oWgqFxM8l1R+8m/Ct6eLmFWCGTSPbn3HroW/Ap093O11/YwoTVfsT6ES2EK5AWcyLPsB1JZo8egRPWhiSo3pzfe+3q+Pqoio5tufjTwGfzclDae+kk5eDq415H4422Qvo+6g2AUCcwvQsXd/SSSNB9mz3Ay24jGnBAhc3AUiq+tkX2kTWI5VUZ1QqFzHk6U=","ylDlxqOI8XCsT8q5orcWqCyLNxCnbxMEiDLjCihOZo4rC2Ve4ABk+48ZwdELvgJPJe5CZ5CUuJbfmhaay/5Y2v/FdBWGxUSYFvBHw3RNx3a2hx7u02WTpuo/1vCkLxMfjHy85PjUWaHEbrHomTZkdzbttj/FKiTQJCJaPaLXXN/g9fwccSd6mkz7pyz4K+Tmkw/Foh7V3n05y2ki0rxL0eln6JVsYc8xwyHsKA2ctUPzQ7of5N68ViPSULRTUZ5doGBuN9XWKPuQejPA","Xrd9aBpGsVT8fuMxzuhmdfzWUHsL7DP1KNTnRi09UN++UMpWUolfxs+7MlGepMQ0/Mt5zK8+Xu47m4vxbmgZJrfSnGLthCgEkFm5H2hLmKe2746L8OCmmbRN0d6Epx16GAi+MpE9vFLC8uuYHYmH9XTDCbynkW+yy6Uct5F9z9qjR6fHg99+Bv3RYIrmaKJ/SpH2iiqexZMnMJH1WxnqZt9hMX8b4OZePPs3ok6tvGFXGeSbPhD2sZxrrKYYs0ktp5SJtr8HpWT9p4vB","u7eGDcnqcmnFNehsxCgEJlA03V+NDMbHsy3w91A8iIFS+y1OqGwXhwYNYKQhJ7DQ7HIPJ5uMiK1f82Murkt5n3t72DTetP/Ik+20XjJXV8SwM/or5j+kyToqvrQFNEJbuX4qCr76OXkAGP06OpyeU2BpYJLZx1N/11tNmQImM/yxFGRe+rOF2LnYc14ImpHoL2WpcPvfYFiIJCO757SN5Js8tkr6mwjAdEpHxsycl5d7cOtW/1tEEu1MfdjRHPAhvtdUE1NNY7l+dinTPyW+OwyLWdQjLnxZwPfe0g==","l8emb2CPJ7IYiKEhd0cm78QQ37sIb6p+mltqAz5AL1aBG1uHaEedmCQ6HRax7V3yw0FVV9LDo804sQucMPBCjzi6O4HCbA+Dr/ppTueqyoZCssV9vMbnrPwTJn7FNkadBP8BPooPFKRzRc7ZbFqJnlPRSIwjpHGjuc1/JEPugpYFryyz3HpY9rJOAoWrltr1+Sv+zLZDTxpKsJ8nSII3aEF26kmm4IHUSGC20Q=="],["2vCGJs2bmV6uxeQpBFnlsT0+fJkcfed2VqVg1HTByV5rkTo4wC/2U6jHLEUJCLFiGm8+nQN7tLyKIWlQC6Y5yLrZ6pFJEMyjKRhvKtkCzTnWqvrd3y6OhqVebmoc2GnGwZa5BJ0vHkivbRLLK4Kxt6RGlLqRasfzLlMuU2244seA2/SXhVzv9bdUGpokeUK4lXX3AEuwnN6PrqJ1u22aEf0D+uOay9/ZTw61uA==","KWrO6ZOHaTbJu9EQmso6uoPDVEi0qLwBnuXWrPchad1K7iGKtA1pAONp+bRlDmkaYlCezHGKF+JopllZBlsLdzWBevUcmkKfgzOB6fJoDlUL3eGRNuzkB4tTrrZU/iVDRb+BmtLxLd4sgsbRPudKS2bYmgihEyWBDk9x6ImAh5iPfPfGe6C5mOcIKengxPYdtxxvUVkX62UbDUIFs6yJzJik3wX095bDpYtiDmWM5BhXCllQWPiw+fwVLnfuJr4VSW5OYgAMH4+3jXN0","eNLoXxxvp4Lmov4x98pV0Q+ys83yHYjKL0VaKoXOj5Wdv0utbSNRe/EAmNSInQILL3X39V+4awmz5JUjHuynWgtZhLO570yUPzxtbuO9YE7bOKxQg96iF1Uz5TF69kQeGCtpc8NyoAJiYT5EPgasX41MxJwrN9bB5jrcn5LViB71iBwHEQMpw2+csZle78AY9aDIYxOXZgkN+gBrOpHO/JlJPgt/sP8uiOE77v+cXNPPGbI6TPHWxIwjGdnVx/uDAEqm5FKY/LRb9jL4","OlRUmY5vbF07WmsErCHZS4GdEXj81O+5RtQwu+sj49L5c9hsLhN4mLhJgANJD2rNPWdKf7fb5cl/M4BrQf+uVlCKfjiUJuFvUrL4AxmJf3+ftSbO/rX6wn56USeb1yDm12AyDHK2HhfG+XM6ptkUdxWO83gh5YPB/D0NWwlJympREJ8qpOsCdHvjATTAXoYhHHtp5ROaoyEO3UznDYt+h9+cLJ0GvNivrAeMr4xGvzhTD37l8MRWZcXhU/6C9QUC2x8omqU9Rpm6xZ7zxREF/UzmTtfme9+h3cnwvQ==","fjFscVGQ4FQRKhiJePDOzPCTP1ONYufwj+U1b/VCR5v76ZgbJxnPso83HAXCfHL0qk9EjFNh7aBzdXtwsKL2gIooVOgfNbsxHIUfV/v+trTwIjj1VhcDsENiqgUKmaLdsWVMFd1gyVgvZrVt01qrXGYjrwck19ZSvix9vSYkQjwJ/gQfgjjsmJqmQz32JpUYojbFHgw9N/bFETo2ZKmtGUlRs7XDDGhLV932AO6c6nS03V7xfZSh9CqLiu4="],["rq7RlkvDS8HkSsJAGnHsC8xxVq3VXz1Kq2Qjt2nUAUuggzjJcE7sHfg5qRXBrlBrGF62qy16Yui9TmGqy9wWQSZh5YP/Ux7ejWafYRJzc2uScruOs0Ue491hJNvwzV+UyXRM1uO7VTXjfCs/v+XM92kq4zBeJjcZrJ16eTxYw4tTHgDcE8mNqnaWAxtWsv8sLqngtMVODbh8uL+gt1VJts/TVE5nmkeyvrP5GTBXDqHB7SATIWVUHkG8SUI=","w1PQAXa4rWjWcvZoUnmNPgU2mozvOkNMjH1aOx6EcpZOsOYF7ulND3y44aQtfcn2ZsuT+uO1Q8eqwU4P4BMUqU+nX4HQKAR8+tdCH4ItV3LROHgFrsMez06miUdlACzeZJ6iLrEa0qhk96veRHg/ZjqrFRp329w4rJ2iMQKmF9uyHVSnSlLLeSutFVsO84czpH8EgJXYIBIl5vcoK0wiee5olfCD7htlKMFe0PGLvFPsTjEdVrWsOGK8fBR3AccarPEvBq9iPu85btO7","BYcAhQ2F1qczoJT38hlxOr4q6g+dWuq7SMNXrVJMyfJEw9cB423Rl9ZYoZoYlJx6/pSvBWmdINS8EJrXM1UFakkJjjty+BL9UZl6aOkj2C148/Vz/y/mNqIDD+9odyr9YGq8KsVzrOmJDS3aB+wdPJX2T6u0KdBnAnKo3kd9GbX/gZgyD36mUEydgcmn/dNDTQ4UglLrVMJCECzZ+Gz7FXKYF0m9wlNtB7wVG2+6aJKODmagdcXkqdAXXYF4Pkq95wytKuO9k8Qg6d4C","rEnyc7jODFegtRhE1xx0U8Bf07JUedPmRuQZn4+SlneWdCWS7e8ZTW3aln8jgkHnh73uPpEa2Ng0RajyckUj5arTp0gg4JEWjgZ4a5VvJU6DrofMDYtr1/NPI7tUYCnbBRs8jLcc3kb6Ih0noyPlYBLSi4CbG5mTBDmUQIcqVAfrbtCPwrEpI1KcNT5XPUiSezZ8p8stNIWoG9TDSELLfXVfBpN+5z0edzx3cPnKgLTg84IoHe2sdPEElegCXdmGKdjGvxph9ZuT1vjui7xrmnKhKNyb8RncMI1+Pw==","Pb7igM3xohNYqROVppFR7smIGFup20TuLLpFhyFhOd+dRZ1Vy0i7JeAt/lPftnr7xaZuLbjTPBX1ex1uzvqIeHl+BRKlYmbZYLzEntyK4Mtew2nnLQP0iQfXEzzIKm302ua3u+G5rdgjJbfFEFgXpU14TQwmfwZ6z9VljOSNuRgiE5/flfkxhwCoj+wmnBt2sCXO9886HAxXCdj8O7J2pxgYFLETJP1c7CdMhWlkAvmONdkGFfxgAylg4vE="]];
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
                                $result_val['ExtendFeatureByGame'] = $stack['ExtendFeatureByGame'];
                                $result_val['GameExtraData'] = "";
                            }else{
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex', -1);
                               
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
                    $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                        $lines = 88;
                    if($slotSettings->GetGameData($slotSettings->slotId . 'PackID') == 44 || $slotSettings->GetGameData($slotSettings->slotId . 'PackID') == 45){
                        $pur_level = -1;
                        $tumbAndFreeStacks= $slotSettings->GetReelStrips('bet', ($betline /  $this->demon) * $lines, 0);
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
                        }
                        if(isset($stack['ScatterPayFromBaseGame'])){
                            $stack['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                        }
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'PackID') == 31 && $slotSettings->GetGameData($slotSettings->slotId . 'TriggerFree')>0){
                        //$slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 10);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex',0);
                        $tumbAndFreeStacks= $slotSettings->GetReelStrips('bet', ($betline /  $this->demon) * $lines, $slotSettings->GetGameData($slotSettings->slotId . 'FreeIndex'));
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
                            $result_val['ID'] = 145;
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
             //$winType = 'win';
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);

            if($slotEvent == 'freespin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
                
            }else{
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, $betline * $lines,$slotSettings->GetGameData($slotSettings->slotId . 'FreeIndex'));
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
                $stack['AccumlateWinAmt'] = $stack['AccumlateWinAmt'] / $originalbet * $betline;
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
                $freespinNum = 15;
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
                $wager['game_id']               = 116;
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
