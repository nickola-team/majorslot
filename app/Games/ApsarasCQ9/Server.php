<?php 
namespace VanguardLTE\Games\ApsarasCQ9
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
                            $result_val['ExtendFeatureByGame'] = null;
                            $result_val['ExtendFeatureByGame2'] = null;
                            $result_val['GameFlowBranch'] = 0;
                            $result_val['IsReelPayType'] = false;
                            $result_val['Cobrand'] = null;
                            $result_val['PlayerOrderURL'] = config('app.cq9history') . '/?gametoken=' . auth()->user()->api_token;
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
                            $result_val['Tag'] = ["g"=>"39","s"=>"5.27.1.0","l"=>"2.5.2.5","si"=>"38"];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [
                                [
                                    "hOWbGcll1uBuNpJnaufE/Wk1yJBhn76GNzoSlEgXG7tPexsrp0tL3TYmH8cUyCtYU4xhytP1nmUu2f0jHmvuzUWUNSoqgH1L6zH/4pLnTm9Dx7KJY5iZe8E/4tTWtmI2YPKB3E6lQUdaF0tt+uQu9CkgIWefqLj9UDoMoWcjSLMvNZW//RdWnbyRt+s4l+huYlFiRyIVsYmoy6nXC0QiyUbJCyGlOF8eePHbjAcNmejfeuj7gIGgNVmnl0RyFwoyuPr1uG8ljY82l8NRhpVaaBEhfGo+3tWtO2/PAIeIwvgS/lm8q9RcAQ4mFDAqBznO+fZMq57U2fUr9OLIs0WE3iUagWrqpkEdtZa5SnaVywCzoEzIosafIfgjhoQWkHygsZh78VrtoQWpi3bR6PZXh2jcIaFMKtzc6aXAe2H5bb1wLl+zRGFP8PmD1LFkdQdtWgKR39V80s4paffJE30e7ySua22SW9pGnyqMMAxO7Gz8ohvx52hO/WVrw6coTfBxVFtb8SV3NHLhORkev5mD4MDEtEnbCt9p/2xRWvSQRRGLu+bI/z5mzhqalaMW4FEGuh59b2u9Hvnb2do0TbgVCI+ZYD/QaVVTVcmIAPBwTz+ZL0tmKkgLGb6PfiWuo+O39+TyaOEpzDPl/7SG9X26ATA41R5aUd3xLp8TyzuWdoXC+becfx0kDe8vLhq2T+mQMwQpSL9vFVUP//84mMxjRY9UdT4dwAH5K9VCHlMweuGgLiXkbJXsv71fP8VA8DcFZY4FM4ynS7E0U1me","UQ7M9WIpC9Jv4m7l9azRHgolyJgo1ePy8J+JQAGd33DN4R4ZwNT4i5qphtEx6q2VA4A/N5uxv/N7+iwiDTOXPGTEgxGBBM/Px2fva2c769ctGPR8jmFVAaJNOjV+Zw9VTu0QC6Sl1GKeXTE8Yyg3+IH/2ZO3Acm4C0PgZYM75Q4lCwLh5+xIveMcfdO5jC6WRC/Z4cgh3U4mu+/PXnQ1gU736K/lwAs+KVVBOFv6l7V656sfbOj14UToL7WYjSL3y4BkZKyVq+007TuUZlTep8gHb9iPSBXiXWx0K/ISj/xKGXIVbxT/dvceLR/0yBamHXE1GpglIKTyfhkQ2a7WBZKX0k5JMfBdHsZ12A9KiyWapAGuhunCloHqYuZrfy9Lk8bcNrrdkAkhCzM7hc8w8TUtIVfNE8VpIBnI09NEhzWXjTtJuJo+Vkq1K5K37ZGgDi/P1On7Nt/ltFTkTkjV5hXWDKYfhNl11xxhbgC5NkLbg0f8ukQ5rtbThoKJRo3xtVa1YyfJ4ZS1w/Zz2ZFHRmYB0KHPB2ehvCWDb57HI20cAkjCL8nAVlmdYZn4UFPz+mfIT/7kPbWI/QbMUPA1gax8xuNFLBxhlu3dyg0ta0rV4VMYG03b+klgOkoyY5iEU40YrgK5/gIkZlkauyR5OajN0L/bLFOCSjHbn6Kwrqo8J5ftvrA+YkcA9IwLCkTPMqrDIkHwm9LfdcgmaLE0luRyhsxLi/0A+MHn8JoX8HNZtUl6+r5Vp4rqVR8mDfPBMOWkOwRG36PjagtuguxhZ9gHnMNDGobr1IbHnw==","SKNNOMI5Ynf05k3IV8MosdiCZ01TgO07SHcJ/qBjOrzqCfpX2RkM1Nm9HkDme7rV2hqovNg/zLFl9dlQkaJy4sfoPzovdzbDFAKthxXwzWV5W9GW+l6dWynp60jwb1qFd9jX3ATin/qShAci4SY3yhGoO4HUW4zWYmuZ0AGTYZy2Z9DKshan0nMXijT1IIZLE8q933DEmJ6YEM245JgqmJEc4zaJidfoEDw0dIO0NU97zS1TyWIpyxh5XvUMw0LiH1AZ0Ca0cmWZkT2erynU6VBYuk0vTSPkb5dp0ajKvtMNCwoB8QzqV5ZtLsYFtb3CgwoGwayY3EU/LPBGIBhACH53h04fjp7denDFbdncrphh643tBpFU8ajbRdEx1eZbHw0vZC4f836HEi61T1sYyJl4WMCErIJhP6Yp1Mg2YwZDiewC6MYWKE613kWmWDjzXZkRAjk4gGjPfB4fxEV2Ie3MggP+S8+UlnsxBXA9FhwX1MykApoaqvMChr6A/XLS8wZk9gLGp2GF/X5JizVT35rRlD4HagYoFgkL9zo19eoOoYyIVYXzEWvOZqbDl8Cnxuznur3jg2VHH8TsqL+yI0hJLWCpZpRkavKeJSA4jMmTqpRXqRySWgxs+izCFP8pIhNYNw971JyZq/qGZJxi9Bpy/GE4dO91pbF0Nc9OVigVQ/OntU3Nhw5JbhBioAnSGE+cnKNrK0QbWWwOMyjTX2r7o4oKZUnOQyfuVXWawaaZX7aRS83spRD102g=","IQb9pKsmwmT23XHGnj93wSV9r6h3zS1Kay7xjlJO2j1jyJMoY8Bg2fuxXLL5iwuHwtnyrfNbxDJUPtmpjPZjFwWiIaoxpbMXbrQAn4rn1TQ92Fim7qFMDBiXBs2dpUieg7RTlGchrOG2xkfXT1L2Fwb5tY1T0SnrSiseb7jprHa0T1OGqZrZqdFKCeRZtW1jsHFLnKHXdTrlFJDbgarFPuM1cKiIHXNMEiFqN41/OVs3KsI+uSTdpTpMXr55shpEHh/I8/D9OSmJUse3g+6kjcV1//3PCKP2h0uTSJyRh4Ql3re2yxtXaPnMSkk1npyPaiP96K2+YPh+YNobS9mPwk/fFu/LFLj0f/ET/tWS4wNUXpLhcFJimmTFUp2TKt0jb5KxOuWb/ZTmeIhjfksxDqYd4gU4XvLXg7OtaRNgtXRR9cE01BF5NUZi+ltCoX6mrGKSX/7KGuPF3veanZNH4xiRClFctHucwFmQsgFF4gi++gynsZYwbRd3T969CG4oGwFOE+qvAciIam0dQz4neTMigoMmBsOSTy/2nBfmoxrgW+7mPhDDJ+nd+zjV83weIo6P6kqqB0KPfYTBTFAuTR/bJclORuwBGEyrCMHZrEQ+MyElbuWGOnu44cvhH4UtNs7L0jMQBzS0tQbNSUp0Gsf9lbH751vzvhjbZqM8QiZLyvh3mzmj1AucQTi3G3L1BhVsRPGLG6WrZS1x4YeKSoUCYDzHM72gO77m/HpYLt8QgVgL9VbZeVkDklQXcZry7EcstGxLsfxCDi5Q2VhrkTinyo4tyuWlIq3bWw==","rl5e7BTDlxXTYQQeHIcOaUM2oAq5CIqtKEvCIavW7xlDZmC7lKP7UWmX1EPMMOO1ORZx7et6sTXhs3u7XBjcV4hiW4BjTB3Jhf4ARQEf7n7YSw/aEQL9B7xzsfM6kRj7V0H6q5TyLXN2Mb+K4qcxvEl59VYe2OLZk6CB4nA46FCovfnAy2mn09ds9O7jAB+4U9dKi4ee+DV+6drgKZD5CD2PUjnvoYHq2ZpAlxHoZseHiVrm/psYJl/UTU6uVKw55ouo48Sp+gtRo1bQUxXPbzr4TQvZs1jLWggfxzyjOa6ih3b7/F6wmPO2/u53iKkTbs4v5iMNYPhz9/wurZNyx3Rqqp3omoc1B4tdTcjmW8xXmGOlNHwLuaLXoJcWs50PDhrbaRWuzJDyfLeqfGeqlKaqES0YPz/7wdr7h75e8Wt31NjlppSlwA4HBX6qdQEoKf6/LtDevu3A/UMPOvmLYaAE5Bw6/xWorzHEpJmJQWcbO4rsULdpEzvGvYOrYgil08yox4LFrsYT/04rWvH30tGnGZalRYe+YWfqf4LroAe3OLm8dQphuQwo9ldxxZXzwSTIee3CHcfuSF0A9KwrGfeM/vB3X3Lo8fQvoP9U94cMIRzw3MIGad9yPDPrv4cITVcgMT37XXIYa4eDp3yMBWQ/PmFbZCGG0uwiUHIplxiSOfajoLOfYaNEDwbRD23O4UGYKGr2zM5BdBFjmzDDnmqdk3WyrRZyPQov1okSyqbmbXqX2J3B1aZYy+E="
                                ]
                            ];
                            $result_val['FGStripCount'] = 1;
                            $result_val['FGContext'] = [
                                [
                                    "015qqwjan88UQhgNdJ6CBm99q1AHVgJCqRlstRYmaX0iOKDDrIImlA/a8i2JHi9XP39npnZNvCzj7AURbKkaggGpsAU4xXImsvT+TvoRWvVl2/8CLfXz2d294w0U3huCY6jqWL0h+x0wWeCmfEYtEZE0XFZhenanu71EqjJM9BOaMye8ck534A6tGtT5Me2gCo5MJIu4iumbQWnvER0wbBiXNmY+niiJD6w1UJdjHxe0CA1ZQla/R4JkTvrMJc5AnyeMoQyj2istlCfCyIzpGjbHgUEgOt7QWcSFLUsSKPUm6FHFrraumdGViyDD6RMAf4WQFkI/eFAcdxeuh/Df6s47aEqEoOEUw3FK/XhZM2JH2mp1yC+epZWpcTGaEuM6+4HdZkCRw1vQ5/uYeubqAyhyQcaIVBrZL0dcHd0kcM8bITdqRo2d73GVI6E9AmFH2AJHIt4Vf4BpbhlqbQe/jUKk8XTtOuw4xJQRdy72JV9OKQSo8s5M6wYwMX3ZIcMIv9CImOif+kPKMZuDnyd9Bw7GwSSRvsR4rSbxetEo4cJgjztwIWAw6uu7KAHF/3vY6yCJFLEFVjkJoqlW+jLCsvPaljxpgUplchLQeUEXjZxXoB2Qskn6TsJPdL6qGdgpWkxp+Kci4k+hG7iJe6nr11QoXZiejGxwSukI0/1x4ocLHs20YQqUVodYH1vuHQ1ESFwxsxb4IkaNl418l+ndEbwU6JR92ooRGv/IsI/i0lcJjx/AL80Gbq3gWu+l61+UbJMeSO3/J7y4Ksy3",
                                    "ikubKwePqbSvmHv2V8qxLlEeS7LNUACvcrE/ugrotAEzXJWrJayA0VtMUk1SVqNysY3iTbeufGJkCVBynPs6aT4v9vwK8v8NcfLqTBYpZw2ZvnxgJ/WiiEAYo6nDVFp1o+Hs6piBEKNxi0PIGgCVeocT0dFhgOjuqJzdB0ddghpGGUD3r64k9e1S3wTlIorjl9KRejUUHs0RqX50S0qEzJ8Yefhqb+k/W+zfspaiM6iSLP61YEDk1MdhKK7g3zpOprILj4EoOCYjq3kHdHf0q9rB1kvz5AQ655FXWWb8IQi3qm9LwEx0knhqR49BWBFvGbrvffvxjHktEWdSs3bmiX7RaVIeZ+JQCpPS76+RWXMglYLNsdBrs/Uza/8N2nP+6mUmHgj1y7l1zzZDYlZm8gPeG9wUORZLq+STJrFiZvTFhlBicRREpSRuE9Sn69+a3rDRRLpAPHCclCkdHS2WSIxu9ESk4Eq3bnzwFOPc9UpsOM0BiR4rq80UOr2i/teYRrY0KbNSe834x4xjeTpkowYPib8EVnQIJRowYTNOIylDiqprn5BBPQzk3KQOp4mNODj6DU7qcA4vohD4C2ikZsc6syGrPQ2Jt4BZ3/EWqys+n2ytwIUpuk1cAO/p6pEFbO793qLXeBatVBvY+iI8eBhRVJt8LhKBqf38qm1KRc7Cz+iq3tG7efU1ySVGnTopnmFRheHoq2fBK52Q9NG+ooOhPN3jdIpOPOrDK0q1C0Xc9ARUIGXKWclnxtPYRB2hNcD+/VzhZFKGfb2H3s0fmeV5nyxF0Rc0cKWsUQ==",
                                    "YIov3TselaAzIyvU1fassbrhx7uC/F0JQAElc6naYCtsOfyiP88CoTc1HpuvmwCo/9RJzAaFb//TQrTTdsgzT9GGBlV7Z4jELMkUW3UQ7ChW48B66DvTvvBJ8aElB2dzHa/3HXCvzspOTkIZ7jfLp7vGH3ZcJSo0hr60ZSLzusoCV1Cn/kmexDttOgg6Z50o2q9oZEGdNeA2VuHJ7jEESLfmTI/5c1SlksMtkLCqM8amJIder4dO1l9De2/XY4H5z/JgeayDDhHvDDXekN2kT+/Ws9kj+FzR989p4jjLRnUps8HHjvHMtxCS6aArkbB13InJM/3rIvbREhs4/aBQPTrcouf8p41Dmx6x+vg9sfXUahgKtWL8kW5U/ZYlxnp8a+nq8vc0UEeeFt1rO2Bq6fVR0voavtOW/NWSQ7mRnz1/Jxs2AKgPpyC+1odsS7fjEaNXT1W9GNF9muu2IUUfI/Wffy1L25/DQxgkxzea0LqKt0FrnUvw3he2cyuNsl8CC9rKIASG06ZnrAfEpqOzdPFfbpvopC58uw6DK5RGEZItti5CbdfD6gKOHkxjxeSCjdsOL2dlbPCC+2Nz9if44PT00hM0KMsaBSvY2Kkzzcj0VBtTnTif343bLYpg0aIpiy5OGdWnJnOED7ERS31DlQj4uf/UwwrNi8iM/6jDNorPSpIxB+3uOM4hnMOQBlW0pr5HLWp5xsUWrNtGx/cnvc5p35txiFHnr7VzzA==",
                                    "WE0rZcNknY0vEOdrfU8CZmKLhrL+jFzZkx93x2r6CEpJMcIZFaRiJtv8M2sGvOny3WV5Xnjn/ClqYZd4gHrJm82fCrR5B+Y9XoMe4PzGaJ4Vd2i2PpOH42/qWrE/fK3zQ+xOtGT7sUjQx7O87Llsnv3wgfcbkM+kuOeBKWpas6DzRQKc7UENWAHPvYiU3Dpcv+kZcVSUrQNZTT4g1/T5EJUHEwv8Ge7qxgeM8FMqULKvwLvVisdYaD9HDr3QkPr7Oktlg9ae98OScY290to8mMOTGxQF0hxAdkCsDPT1EhTspFSJBJiyGbyFAwMJulfcx+Fh3F/ucODMSKzEpZhDAgkfgc3CHEOiO5Y72UX7Oe12SjgpfBcPQXgmHhdkH5o+NWYmlr1AgaAhlfzA/w7YS8PmzV+1bNeshr+gMvXgWRMgqGFvBP3wVo8aYIdJnJDTOfpk24PsE5FWDkqf+yaC5cO1ktFfiMZfKjAVMHN71TJ0HcnaLn1f3pPsOKkWHrOdAgU8SRXRVPIw1dIu07TFeRsAgMZU3LCbDA/++jVJuYGBm3lup6RlYod4f50HCoHsMf24SSEeGPCfmib6xmbGaL9QlhBonOBbnefMKpoC7DRSJgeHBU4hYDipnIo0AORKGR6wEHvZGNyBDh3zluo3GyuzNgigqUeqmp0ILo6QMxA2HI+C304v4SXyOuKEU6XjGsAqRbnXIovd5lA9jFaeLM7RUib93TtHmJY48p/SIMkfSsDMss1TTnT/5/M=",
                                    "ccRilf4y8XqIFMezVkYAiVoo4a3yj2f+zI/K3Y9l7u9Fa84zG1gVesI8IZoeNzWaKMVEViKoWlUiDd8XdLbM5VLGXnkwJcLEM8A1lGuLA/FHqzM+iJ5kVew1OADMuk+wZ+X7Gaqz1nsP72GIQfLt+M/x2z5iO6831SdpFh+anNftQXBdhCFdVRfrizK/RJE1RP1S23FkIT4TIGZTgCnjKTFozU/v+26FYinZc68896bcpVYeN1ohyU648uEPzKhRcw+MA7gSFz2f686KprAfY+ysbRHHwFzdS9HS271sdPEOXsucpyogIbILeTqe2a3BkbuK/fdFTyiOrKZ4eJFDcGboTKO9kd0I6kNW03cYZIECUwP5QN53PbwFLpirZUjJnKBwiS1SFGL0JQkOcZAYrf+9QdBdw6mvD6MvbvASa6Q4ENkfCDSBofy5QS8F4cj1r2W5O2n19pORzmp8yp8VNyjCmotP41MK1vLWSif6NHC7XaMQiWzN1Oj4f/MksCGZbWvEj1p9ZhuDW9XI9dMnty0bTYkU68pZuFZe50MKtCo6kubiULwbQJIODCqED9aZrqLxo1bdag5HwVR+u8MDuO4Ifcfp5V3MOTAT4ykhWZlo4+uP2vBpnseKBwvUI0Ogx0fvAVT75dTAuBOLWvlnVTeGWQU2potdSTWFu6ydyDPej9/XZwDqjsmZKvNMI+IX5xco3spoHptQVJkX0Q8qQYHR/2o4WcSJjGm1+w==",
                                ]
                            ];
                        }else if($packet_id == 31 || $packet_id == 42){
                            $lines = 50;
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
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lines);
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
                }else if($paramData['req'] == 1000){  // socket closed
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        // FreeSpin Balance add
                        $slotEvent['slotEvent'] = 'freespin';
                        $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                        $lines = 50;
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
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $winType = 'bonus';
                }

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
            if(isset($stack['SpecialAward']) && $stack['SpecialAward'] > 0){
                $stack['SpecialAward'] = $stack['SpecialAward'] / $originalbet * $betline;
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
                $allBet = ($betline /  $this->demon) * $lines;
                // $pur_mul = [200, 120];
                // if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                //     $allBet = $allBet * $pur_mul[$slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin')];
                // }
                $slotSettings->SaveLogReport(json_encode($gamelog),$allBet ,$lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon, $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
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
            if(isset($result_val['CurrentRound'])){
                $proof['fg_rounds']                 = $result_val['CurrentRound'];
            }else{
                $proof['fg_rounds']                 = 0;
            }
            $proof['next_s_table']              = $result_val['NextSTable'];
            $proof['extend_feature_by_game']    = [];
            $proof['extend_feature_by_game2']   = [];
            if(isset($result_val['ExtendFeatureByGame2'])){
                foreach($result_val['ExtendFeatureByGame2'] as $item){
                    $newItem = [];
                    $newItem['name'] = $item['Name'];
                    if(isset($item['Value'])){
                        $newItem['value'] = $item['Value'];
                    }
                    $proof['extend_feature_by_game2'][] = $newItem;
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
                $bet_action['amount']           = ($betline /  $this->demon) * $lines;
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
                $wager['game_id']               = '39';
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = ($betline /  $this->demon) * $lines;
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
