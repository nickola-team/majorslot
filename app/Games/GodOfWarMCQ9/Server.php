<?php 
namespace VanguardLTE\Games\GodOfWarMCQ9
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
            $originalbet = 1;
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
                                array_push($betButtons, $slotSettings->Bet[$k] + 0);
                            }
                            $result_val['DenomDefine'] = $denomDefine;
                            $result_val['BetButton'] = $betButtons;
                            $result_val['DefaultDenomIdx'] = 5;
                            $result_val['MaxBet'] = $slotSettings->Bet[count($slotSettings->Bet) - 1];
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 150000000;
                            $result_val['DollarSignId'] = 1;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = null;
                            $result_val['ExtendFeatureByGame2'] = null;
                            $result_val['IsReelPayType'] = false;
                            $result_val['Cobrand'] = null;
                            $result_val['PlayerOrderURL'] = config('app.cq9history') . '/?gametoken=' . auth()->user()->api_token;
                            $result_val['PromotionData'] = $slotSettings->getPromotionData();
                            $result_val['IsShowFreehand'] = false;
                            $result_val['IsAllowFreehand'] = false;
                            $result_val['FeedbackURL'] = null;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['FreeTicketList'] = null;
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['FreeSpinLeftTimesInfoList'] = null;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["ONqAOdnzaVinC6ADsUWlSvAILZOh9M90YvaQashr0+MCwwyt+QZhsiYy+VRXNICR/e77L/IDzY2i8We+ttgss6CEcNRwlCbclui3GcrbeC0XT9nl/Yst60efsCHR949x1ERM7XcE8h2BJDsyZ0MJR+n79UwWw67EMCtiTKYgAxxOfTJZLeqKTfUzNsc=",
                            "IuItAoApWCaH0h9YhkxOZAyz2FUk3slEvvlWks0xiz+ZuJ/Afbt8rp1CB+2wR5XuAQihYg3eUX45/1rltAnyeB7Zh09nDCmt6aL3hq5Ab/bawbQZ+k9ENm21HPEsEHd2oXPLST2aWWUq3CAnY9msjJXGDrEKURE3UXoU3l6SSuy3jwjrLKL7THDfkft0ZiFXrsBrGb/pDoQbukwaPJn6rEzj/VpY3+NKc3I/o1qqp4dpN3+571fXBWD1bpdXAtfd7tWADo2Nk19eD2uU",
                            "4ojK5kdbYjJxkNK485HVs+QislKyEADVtmSNPnC+a0TgRiunAVAZ/znuHvUsvU0dbhA3PV+u/hTuUt9db/ZumLUNtX7xeA1QJfuRsmCs7SLwUo4L0a1zmXTiqNlBbW0SEU+tGgWcRXHFBqZIH5MKfswCwSKVRsDaoFBkgtJ8Z0LaQoYYVxhjWh/OTv7CveM+czSSzZLtlmmijzyf",
                            "4pYDRVupFJ2CJkcbZoL/ZoQ8eyRQL+9jYbE6DM8w0fHB8g2bQewHO2KA2gwlrEC0pJ00B5LCYU6mMrFt0BkIvcSgEz+6FXz70kgwpVf2x4oYPw6hSGBrV8v8OP4HwWsbsvmcgDZ8rPA8mIMgWYSA5/x/9l2Ug26bs5b4ck2PP27dGx3vwBGZcGnc+Sh7xLNiFfTjMtOsyrk0/z6a",
                            "6de278vtwJxySOwQ91dfK4H/rgd0/67PxxOufn2kBlMzpd7PzoR3lyJwfXslH0S9w2AdWIIT+QzZYy9GrzrlnQPib5kM1SFSsqVAmlsQJJooDSSsZq4vCvzvaXSOcQUkgULtc7KZGgcg3IcDoyvjYc++j2IEZ/k/x6cP08/K/m4swFNO5AhG9ZXaHkw412lBYyUs+VWydh2v6AKkuzQn6dAwWzj4ZiVck9dkOA==",
                            "On5akmrYhHHPbFe4jrGU1K8T3yzWPdzu+3rF05bvgtGIoGqLGpBn3T0RN1xmbwG50WrbpxYeAsnnK6lGU8GiD84u/Ac1MI0hfUX9BMAaD9bsTNlbbN3LokTxJ2tTM5CSu3WXQjB5D3IBbeEE"]];
                            $result_val['FGStripCount'] = 5;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["c56uGk2wUPH6f7F3A5fJR7B31vPyPkLiGELaEvom/rWBzcXhbDWbQ8ZFLxelIDyeTLW4Ujp3SEQJ4w4T2TmFAFwNhK5/XHAXNpM8KS7nv0tEntHWQ10TtSRVbDoAKImf4vSx9ZyK3C6h3UDJoCBXo+p1+WlbWUSESQryaEpqM9L7QquPdavKwDwfvCXHD86OsuDpang8YiQquRbi08uMKYAX0g0sSGXk8g24+Q==",
                            "CgUnNL9eRQMK1kjqf1ILMUrkeW/KFOKHOGVZuLpxFWgAbwIr9JvSmXdheX6jW8d/fd9RFsfCWgh8jdloKbegoYSvSyE+uGJOlh2/+LSnTO2ni+m0SD26G4BDLo2hoZXBcKizIDB79Z/4mAn12N2AnS7OXH1t4dOe8nlXruP9AnKEqx6/n5rV3yLvJOGNpnMBfNHYQN+NnXc+LcCQSHjoTooLUG05zqv6xncTlg==",
                            "jivABVY24vHJ8vN1AyfE3yEsIQGR7rX5imrFPxi04aInDiG955YROruMKtbQt5gqayrfmw3//PPiRHr5pHEF8hh5EW49s1HwE+UQSkHbbmv5qxA4yv9v4E3mqieWXUr+SVh5ZHAcymO0YkZq4knSUkRMA37sPksrumuhb39zt4MkllQXd9xacM34BeiCohk/b46oisdVfvvmJbv05YwrFvRR/2+H4onk3T1C9g==",
                            "PHQqorPFUpIX7gwzI27Q8+DjF8DpfGa7ioUEdor4GW5DOwO+uMWXpbJfNeZBoinujcZAwkLBDt8rzAOID1JZ2ANrdO5ygOV5uvH7CRFzly9KIVwe06jcb0+Rh0Wc3IolXMlhVeTFJtvcsoyWdDfIER25TPF82Wn1jSMLR7/LYbh3YVZgGFB4CXheR4FSqJKnx5j14riV+3Oa4P6ue9R8x1yombezlOoT1dZBjg==",
                            "KzhVNHAAZ5TvAesHf54SpPKHWemYHnnX6WZrjXsTnUZcmyayLXdnY+A21PdNaiHqgxHrD5GGJAfaD0DQc/cCnYwPFvOiS8IvAUfeawjMrng/YmpyN1x2eUPPix1bafvwGX/4ZafzmJH4G2kll8gTiS0Pa3SP6wS0+zxPKeEmMj3FkH3wVztNQpYe0CEYIr761Exj7izQu1ynt4Ul4mR0oMOYUvpSO8VvsMbOun3hrUnL321kl1gYTzB/CB0="],
                            ["k6fiIgB7EWgCgkSC4k+hUW76OSgIWzulI/+NmXd7TP7dtk0CKgWKjPbEXM5CzKFndXcR76qotIkoealUdrIe3/a6xnqapQxoJzHcfxwizuF0LmtLfVmYSOLA4bTIq1EWUKDylc3JzzkfFqBDygHVVzxKskRVlPsENJoHQfWXlFJF86KnwulHj1pQ1iT7Ri6r73DgStKZt7zRyLZuDs0+WPXC/OkUuw/PJS3akQ==",
                            "CsklWj2rHNzMTeZNFqd9kvWuy9Eeztwt1FCgCuToYxcHOGp02NNc+AsbVMDz8jZeRkbM/stjxAIe/7KSMkWie7bd7hI9YQj2RGRdiV/WCbXfCPUc1bh6iMQ/QmP8lBOlCLe5dJntVUVikV+8ftoCr0df5URGnacvYk7+xHnAUhcIV0+2CNrHdE/xm/WMCSoK3b8csBz8wBnbgt4065LBq3Fcv52AKhvWt/25mQ==",
                            "WB2UOvKr9cKc9T37luDe+lyuWwH/ag2vlztBpKQgGpmE9PoBlBxwWNmfoiI8buOn0fM80aOn3CGAWffW7TGbYaALgL9FW4u7uPdZOh0u1Dq8v6Sc0/7SUO04YZ+uJSE/CAF9Btv1wfk3BQiBCHEabbP4tU04dtuJtcoYjx8yDtaUuJgw/Eh1OsOkWBCVDFvAGft4TwfdBDjt/Q1mV2qyDE6x6AUhUtIgntkV3Q==",
                            "IRc1dZaMOX69g1ApSbfg7WTVvIELQL34FFnkoFODaQQ8F9ykHEm1pDYHHbzSfyMkBmpmfo8bcdKcFftCQ0XefjqevsKiIiQwxYjxuZiXfKtUrY+Sc+c8BaDsbZR+CWwwYcuDE8rHgnUyipjBSfjlH72AELlgKINzL04pTu21xEie5gDIiny6AXqk4PtsItuKYkbk0uRrpb3qoNmaECv38Q4Fo/B3C+n/hj9sCw==",
                            "Da6NsNPmRuagIxLQWsdRNiV7+/3LzmrHS4K+5XaavRegi3e3XJBELTyV8mXUH+kcR29moWVhIGDoRIV2jCG2j2lZK7zpS9les+2cm385yt0RwxIDnZ3CVFdrZGd3ZZv+7qngEzjjurxFX+1KQEUt3ASJqldukew7i4BnBuZbxFZakjhaiC5iP4stZCTtgylu2DMJGXjjySd5Ha0itAZ+LMOO6ihpGmlYlw6AZDl/mtei1gr7WA7jfw9nyUE="],
                            ["OOUutmjm937TntwXFu5ijxju6VpRO60N0dhooW0UvhnguKZVSFOu31mQfBYurHXweQJ3/Iw15pPZ7FM1l2e05e+k8Dqh+UUoEzbpC4esp0z5JPBCrkwTAmn2l864y59z6EWeLcrOi7cnteM9w+uPe2gvxD0HowaXPocpoik2q5QzWeHT2uqHhR8oiVMDz67RtjQUNsW5ZB2D0S6MasywrRFyKWOuowIF0126Pg==",
                            "fL64D1hrQMPPwtnsIGH5lboDhq3PG4sDYFuKr/9ArPJSfMZO0ihULwgFWsYxIt9sq2ZltXtWHg5O4lZrqffciF9w995YQmThRqcOzrXnjwdJURTQAv+6d8RY09uUypJO0GkkqJe40GhOxK9KRNj9Ix59i4SaYRbSm6WRfZW/hdo1mN5W3cwygz4N0ds7cBHVn4PWIwRpz9I27QbG1II63fUyIScf+damyHuUTQ==",
                            "ZpkSjHYtGylmP0Mr0ZfNM/VNhivULxvwwvWLpBE7SIHpkLom3+4BlGL4W8XwDnhyUYIvifPzvGRqMQw5G8lArGXckWKrljNfJqAfnJX1iTGXw9Wf8+B5eb7+LU7HlfNni/211i0WpkMKK82ayx2ukgboQuUgiu6ALgSkjBHLfYZktGdN+Nqtmif3YI/vqICvEFepsSF37P57+zErM/5G25Ry00PzDNvW6AtG7g==",
                            "ZNwZZWPBdFb537RLuR+4ohNM8kP7H5liZWOyKzOBPTCBsptEutMo6ex2HwlTLLJNsJSKxSMhQ1DCIuP42akW328lQgBGycZe8mJN8MwaNL6QwXVYkqGxpqU7CvWOYcdgcFBj2qhrH6LffWSIocbCB04aNYCE43fJM9OTvkkoBp087R22Tc6EeOBOrgzvKYxkWtRTdu0fA0mhME5GsXL366/Kz3gV7vpO7JHDJQ==",
                            "naZKLEZmbrY8j5PiYwvJE2C+x7kSjKhgob91/wRIEBdL0iHDZNu/1vqFgg8Fg7GCgb+B71DB8ClMyXuGv0VNEmmmvc35Ygu7ozYm8Hhl6rFYZAOYERTVUzcXKi1sPzXtxp5tg/qQpZtRSXX0YPxZbziUF7rUEZC49oZOme/4eno3BMsigIpNdvWO4uar4Ug5emOS64GK2h5yqK2VWJKR9x7TMNqe4bnugNKjZZxxRMVGNYq18xUQjOGHYfA="],
                            ["hKuqxDENa2vYug3HmukOZyEMyaGwtIZWbCSSJoWXHZ/7tqS10c1mM3fHk2Wn95sXgfUCQR1Xgi3anbrTXQyGSC//6OdruoeUKLtRlVqOzVZqdSI6dqkChB41fQfYzLuIGymUdKT2BRMivHOH3r2SZ3mGVduPSDJGIfxuxlykK9sSVXN99HHKxRId3mc3GXTv1Tt+hLM1P+/T/j18OMETZDoqLbG23NTE2/1IdA==",
                            "DI1iXBfu65NUB30qJUYl5tFsIxM3cKDHySmEKh0t1hZvNiORNHY/ZWcUe2V5NXMCBLQWkfCy6Mbn8jfs6ZzPr1RKy3VYpS5nb5DZwQewhO0iscmSjEPxExAvJvIMpA/3FoDGtstI4kX+wkc3RrvmVK72qOrFVu8rwzPOGdNabBJf4kHxIBElub+tZzL+2D5JfIQr9QzvB8td6e5u6DjG05lHWfSkay4tGOj7RA==",
                            "ty9r1giBwFTaDG9nK6JCQOVu3jhtRE8zJVP/s5BUDxtZBSLUK/cPvsDFgVg4QbBA5ovz6mVAw0sH+U5ztdWvufTKNf34PBUNYuQrB7NnxgFW6S+/EzHS+gWZp+GJZgFbJU0Q60+A7gLGERpht1BhuGz/nHH15eaSmBx6IjdU1MQX2fZwXczPY865K0TgMrh9cs89A8Rbx5oJPXpePVNG3We5/LlNWLff2DDOGw==",
                            "aZZq7WVc8njWu0xK2RVf5QLvm7PqoBVugvSbQJzJizagflEoZE+WX9xTdqzAs7NNQz5TQkNO+ax919RtA3M+n/fUJSDZOAW4w4SIaBchOI4pKvBJ2WmdtkjCFYC9/GSXUV7op2GBzT3+Hpz3c9krSci+QVuBt7G4Q631rLfB5E/OMr17buL/EFMfGt9kljIemL4f+5R+KlbyTImSFPGXcq/smIt1VwyLBCI6sQ==",
                            "Enn0kiewnaXMjpJNupH6xjTJ9xPowaak/1Mog61CXhCh4keP8epirARHS8OdJC1RjwGjOxtO4pXFybdJbgRFFpmf98AfRsMD1KxITAtWr99nX09m4xT7jrL4zb7CLSMDI5d+HV9pJyNxjYyEJ91Sq5Q99ANYEp6ftUHr11IrrGPQNtdedZUhaG/CRJ9+e65mFi8RW47hlNAkp3FsaDOyYbyOkjz2EENmv1LlxqiB6SQTDU8MxelIqlncnPM="],
                            ["iqTsWacFGT0jaCCMQhxVzpNzyamz2mJoLEcQpP10TqerKMmLBrl4n7j3EhPimRmynbmt5WWEHVCRvxVt/SJWYw5KMBH3dA318t0+UW9Ms/nG/oYC0950DaosBiLnkCPX32/n0MET2s3fyHpMx9Kc0Yq+iFbOy+mGNSMjbezS4YKLkHVtZYlxWGak55ActYbSAH9QtHWXWAxvHtzAn4ZNlw3GimH0qvINCmoDUw==",
                            "9XPn5evdbzIsh1jcgluJNAxG1LwV9F4R10k/BXFiw33vVE5v5vVbdz2h7JeXs1nxBGmxxPE8tRfIKj/jWIPTjJTsmlKuLIvFJMkOV+DBXd9Xh9ZwT3NffiMSf32pv+25QBZBhpfYJ8i92Zmf5ndLV9y2bZWEfCQCeIarESjceIpaK5PElSWvp59jfEgBbixECiRmuU9ZSQpDiGTrJMFh2jz0LXOtsCHyphzr2g==",
                            "h7t1JUWy6t4JCCnRtCqs0DH1p9WGh4nRYwf7kHdu1LecPVnEDLgVjEVgKGKACYAyHfhQLbQ4iZNp7qre5yG+WEoPOJL5gu8PdYPZapmb0S69E53JQHVuI6gYoVKppufFuYSqIwpWGHTNHkCQWRPrwKutlv8n/N6as1jpRQMAEBIZcGoE4aZOreIbGjGExHMXlT+fgaR2Jbziup6uuxca41q0LfUgdSyTMVPRkg==",
                            "jxXsPQmJytDqCS5IWtH5hAkdHfr5Z2yJiS8UT1d+GELSe9JHky4pjeOxLo/1+oPxfHPxDLTDY7E0uCJ4vlRLiMVstsBzvoAHsmxzhbUQ2a5UdCGrTq/RjDF8KvmiKX6RqzlrX0VA2CL205Nvsg2hEgDjB8xxvEU4ap8ra+H/ykOpRI6kpncaE/uzU9WsTeyZrRv0PmF12T1ChP0lWoqqtxirf+WbRgIweMjAUQ==",
                            "XdmO9Kv7mt2hI6t3sCRsg6TXS+NZQhwjB9OzBVBHv6/6s4uMbmfolQdk2y6sis1QNNuu1n3NlENzgLinqD3RC7zGiA6vmmxpouoB65Yytwov6ZZUZrybRe+PKSo879dbogc2uL2KHSOkAXaZmROGxa7RYeXvOyI1r++V0ctiVlpiKwpYmsYRc5z7Do8f/vwgm0XH1zvCa7YuU6yVyZOIllLaXsEZBTdlZslJxRdegsV8D6V6+fztUylWjkY="]];
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
                                $slotEvent['slotEvent'] = 'bet';
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks  릴배치표 저장
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                $slotSettings->SetBalance(-1 * ($betline * $lines), $slotEvent['slotEvent']);
                                $_sum = ($betline * $lines) / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '657' . substr($roundstr, 3, 9);
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
                        $lines = 1;
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

            if($slotEvent == 'freespin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
                
            }else{
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, $betline * $lines);
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
            $proof['fg_rounds']                 = floor($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') / 8);
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
                $sub_log['sub_no']              = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
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
                $bet_action['amount']           = $betline;
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
                $wager['game_id']               = 139;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = $betline;
                $wager['play_denom']            = 100;
                $wager['bet_multiple']          = $betline;
                $wager['rng']                   = $result_val['RngData'];
                $wager['multiple']              = $result_val['Multiple'];
                $wager['base_game_win']         = $result_val['TotalWin'];
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
