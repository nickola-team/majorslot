<?php 
namespace VanguardLTE\Games\DetectiveDeeCQ9
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
            $originalbet = 5;
            $slotSettings->SetBet();  
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 10}],"msg": null}');
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
                            $result_val['MaxBet'] = 6000;
                            $result_val['MaxLine'] = 10;
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
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Currency'] ="KRW";
                            $result_val['Tag'] = [
                                "g" => "32",
                                "s" => "5.27.1.0",
                                "l" => "2.4.36.0",
                                "si" => "36"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["aBkuu7CEhK8ieUtln+6X8xTOor7FLJOiNUFip5155WHb3r7H6P+T0G2rFRnUL7V5HXbsz2z+WeePt5+Ug9r6ufusogwsWYtIvX30hzSTxaWTZfLsQ3gYiI4yanxIDqwcaSdN6BaqqZPfsoj3cGSLajxIHT4y1H+x9v6AwGD6hQgmT70RoDM+MecNf8FB9OXqCigy+kP53D6EcYiz","TxPNqM4mjS09rGSR98PqjTTmLfcrciBMCB5Re/X5+e1n9td8DB45ckW3oZhD5O/M9IZy0oKXUjXLfSVmEeGlTGH/zsW7UCTU8nC9kjsKgmPKbyP8aJlTt0FecMcxTrVSIzDutjSsmDHlT0niJfsXNpVUGJdGpQVeNc9iRhHBVy4amIeMy71GpS+0/4o=","8AqXmT5e9zPwKiu7KuIrmINl1pbFnmGYZu7efiIp8X6EEYwMuQbtvIeC2mxdB/9HhiTRVPfpJ36vxVp84nrehrkIgMTU6MFfwVFhYTBmJONKdGVSVmCALL7Q2/MtmOZH9x+mhiTnPbhX1Wanlqhh+KBwiS56eV6L2OcrFJ7QOYDeY4NnAMFUPRhmu60=","i2KIuS7zg5qyT2CS9XxxB7pvBtTUPQ/duDzru334DSKgSp6IImmdk2xY5VZyUE0CE7Wv0fikvUJi/Y5I0Ad0evAcHn9sVmLFll22XvE/rtPLZeKgeIRjNmHQ06RrtNh0DlJMP1HCOCbqUL8+gCy/iLLva/EH2X4C8rS5mYzya6jNfjTxOz0S905MYI1IPGsg2M7UG6jU22yjf1R4","1upKYhxnv1Wu5ddXP1d0h6vWWOfKBw/JQz5RnUkLeH0SKv6z99l5feoVSo1vW1zxQFrkkdyw9iJm3In+e6SgFwC6H2IJtN9YKsR+KgB3DYXTDBNjz4yokHnBDNGssizJSUH6n+3cilSPqYvoAWOiNy6/bC6yqYEglaI4PLHzTGSKQ32tXKbMC3HNw3E="]];
                            $result_val['FGStripCount'] = 9;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["yhzBilV4ZTaDpDpMTjzSm4EJTxiciYu0oGbgon7WGYnZ/HyqQz5RArdLEF/pUk8dNLkF7G8KiC2cAcuUJy8RGvzrr8ayCBETWlGqu4aQXsASRLeT9MUMKjLQYybwbiS683HkRxQhvvHXbbmkpvH/CSSx5XKkCWAvKP5hzJVxAJxis1MarNsfK9A/sG5JHublJG7ieyEOw/Z16jYn","2Xoacahbf2MpasLBOOeF5SCNiq1vODY1aW1cG3F4JrKkyoKkycoUvDdRCBdRerUif9eFK5wMnJDlpuW4srmmludCt5hdzVvSgmTqJ6M1atC+7B6hKfcJ85fserBIIuv9dHRTT6MF9ifqmvP9OyfL9eXlYGeGLBlTurqdSymsFaRe3alnHA+yjVVmN1QXCKoAn2OHHGFG+Hfu48B8","JH1345GjwYb8En0o1WNcV0MK/2usK3WiatbcesTmP3aGwo9Yt5MZ61CcoZ4obgx9SiC5YzWjAubQLXbLws7EhfFVY6QtrjR3cN1kVUZLJxdF8S2fHZOTBKg/9IpOtojruu/TyU+AbDUepusLFuZpW95Orw+BDd6Mb1BjeHK8ca0xrdJnuQp4veDCjSW0U2/flKc3buLGf0XrbgoA","bV6nrEgC1oNXfEp4ZpTt86G+pqLsiL3fC9B+rqcuNh0wiLdYOlurLL2wzdZ9QP+isrwN8x/eY1eGRSiRKUw+KCUxmGdzCpHX7iDAUvkDDgP4VtDoPhN3p3VI/pPNaxqkxG4m0BieZcgvtLpuY+CUUb3x+G79X6+TaYFapLO5qu+FAVrFxfw40V26dLj9iDnYBXbZ8BURCN4//Vhm","2xPuJyLvhDFqyI6AuNdEs1bVrpKFcHoWxJ2uqL1/W/vlt7SABIJanuV3FsGfIAQykgWYoOu277r+dGGO/HVJIGFn2fqdA5T/z/V0Ki4VMGDC44gPoRHHMp1U4gaEzFY1Gmwj0bvSCmzea/OGLN/9ZLHwk3cB6RxmI0ixXL4qracY2RAieJ7YqDIMTi4xYN44I7UoRjuADBmrr6HB"],["V0Xy1jQ6dRwdYSf5PC+MyPmj4u2mrcMaf3l3umHeLA4j6QBY7y80irAJ5A+qjY2krR28Rju51XQb8nKwC6Uv6vBJ4eOABcgfo0eFbT8nJVbZDzstQ2G39wozezDbNJBrvzdTKafsYPi6GO6yhJujG9AqmGZdsYgECpLeFKhaZuaf0Wokaf7XtFGgAESOV4on7eqh30N0K1fxnLMB","awkZX8fAV5laxfeIQm2SORmXkbIwcTn2UQqWGLeAszttWU4ByGK8LYMbzhK4QMz+xotRub9q3LAu1Vxezeuui8SQP9NvSYrSARqSTePzc4VPLwFGCDjU6PquXWhsemwo8VnFW8f+/vVbqh914rvZY0cnAEh5bz9/3p5lDYGNHzbwa2z+0gmJC1LPH9MF4zPJHKbesiX1RBinLDg6","2BerTbOcttXJjFohfxVbcc/GbwwVjlr27ZPgTB239YQybAwVm+/F4f1+Z9v+rhS6BwQRut2bJpauu/maZ9nCrslLz+Vajsc25p0skjY3Lkyfvg3oFqihzJ0m3/3TIQXCzaKi0OsnjqTOoi24NCsabOra4HN7SKgQxWocgxnTpgqk/FzN2Uul4+gUk/GnSOlJBTR+8CDnu9rLJhoE","CIMf4w2D0cTnw14hyLE48l9OTVCfToLf5y9wqr6O7S0BQCgsuhvLW3MAB9WtNlj1TjioDzzKaaarCJGLwiUSz+OfnQ5cfb+A3ypYXOwba63CdIEWz81jZT+BG368o4u9cc9lvcscwYXRMndkcdwWzC0gZM02JQBRCbWJ6NVJ6LP0Zy00PySP7YCzDHjOAWlUrPOkA4sYweT5dAoy","qJANHViJWU1yw6kJL0PzG+hs64Pt5dTbPXtzBxUmXa9UMLRQSsfC1DN+HyzOYhaMjbEOi/D1H6NyHadYx+sJdLQmp7A9T7tu1A4KJVLhMmQbSodVXavU8dYVdISBw6oE0gMEFhDxSYDOxPGEoFhVseYHSZIHpOOaqRj1Gj+eiI/RTB2U4j8ii7Tx2x1OQOVbRHkYntQ+EZ+tpQLY"],["yOlbA9EvjlygWvxll5TmBKYaN1PhfY9ny4K5JX0ZLycJbe269L/l/HIKJKp+d5otSJ6ZitJ7l4wI091OCip2+Dysr+WR8XTkOvWaTIHby+gccSnYT/bCM7s2dIb0Lre8u1STs6BRK6pIlb/VFvYxcWooxgijD7eejxyTLGTSlSu/NbuGCiJ5Aqug6Vagj+FWsmRwSCBZjQHyPVso","SDRXT4QXWWJM5ZpzsUKwJsyz0gOxvkLg1ziorEOjUnW4RXvnV4ONmUkJYWGMknOj1x/cJltCd3ta+ITnzwxL3oWzsnUsyP9tFh3XWAPglwQzfJo8r/EG9JqwNXjidi248Nf3zf5SVCJFPwS3ci1EXDmdWrc+T4w1FTVDuayr6ugc/o9ooxy9sHrTEB+ErD61p/H0ybnVww2JMVLr","K6F5KrhM2SnupYeN1MaQ8Yvco12GvHPa8OdE/807e4+3gmXGFkmNLIPHui+7N892J3K5WXJo26up2YXVtfWzjfVqTsO15xXaQdUy3Iu8oe1qrJxw11uMwc0SEvSnak0TlmzJ/PB1+pdpLv1QwnVzw6RF5oa3POVKc34ta2UvVl2xpQl4qXR1R0WliWY1LlsSjp0ohS8Xvl1fFrzs","n6Kbnxt2lQESzjy8cItkhqcs1t+dZNIw8tSQB9qfbKeL7IlkYNXWFsh1XxPn/v89bR6Ob6X4U5xFHSCXDwIqSiCWbsvhitl5VVUwM0Cnx/ILtiDxYEfM1upMMaloG5SA6B81QogBgGG+Xi6RYwGjspOpqnu+6U0m3P3FY2AGdyiCQpC8L46B7q4AC0OjDvlqgHtvWR/BIzVaw05z","42WI8HEx1b8SLOpZoe8SjxgyPu1Ope/LEVsX/E5SW7tck18/HSc01WzGmv0Ez1z3Boktjn72jsTDjqrnlaXDNbB5zWqAE2GDcjBiF5tWNI5OE6mN1qaE6diEV6OWJ2mksIA8g3gVZWYGsTphZ82WZR0uraJtSLXXsoZGDEF4AJu74YUzGedpxMqd6EC/r/mSOwViYBAWqrnhoWBX"],["7oaNRCyoTmRXsAMOm5srE0qYUd7ImcHTMITOVVJPT+7Nzd7JmOvJnfHGHvijcKi9HBlBNAUcZCh1VxF2jyxC1Rj9dHqqWNqHZIUS490k7NXBmt6X+HjoHV5FXaBfIr74W49mvfirmOX3sxau2gkldMSpN9Q5rA1rtGx/ydkFeRGW9kNows0G1FdV/yOfJ9MfJqfNwGRMnDEhyZCD","B50WOEkNuUdNNdBDkOml+x3qvSiKFutGbfTyR6zqG4UAXF7JMQrDCCovHJTJzyQ03spfet4255q46mn1t9sr34r6gx2Y2ue4T+G6JdYKG2oeTL2U48+0FrenPxrTEwFMhRz0izkw+zCCN62XWwkc8boRBk/c5i/WlcXpF+gD214cXtPZaV5tGfX8GjknshGh3PQFIml+K1Ak75PJ","u3yhPz53tfdL7wbx9Itr6yhquGEZJR7/UfMIFpLPh/+P1umbTPgh4p2TBqgxhT14+MzSppzpwfz0tIjxXwGSiv5Y1GX3Krbax4CxSj8FyoifRAfvm37gKOsEsAcYQYhxB+f29er++0Tith9AyiicT2RDmMhMvnMwRQqO6lpmkb3zPhcdzUy+qHp6qt9UkUGqXJ4mPCbbaXLj8lbt","c1quoKY3P4iP9bnbDId1+LuC7Nm0EHyEKQwHAEMIKB3yhwUBdEEtASzf0NeAKYdfCzfHpCiTIcJskSNEfizrU4HHS2SgaOvVRdtrLbjOeUaqdxbHa3f0iSatWXz+KdzoDfr/GxS/LcpN6hwhlNnyykve4pXrqVAoLSLF3B8Ar4l8Ovqd6c4p61XYuSGdTp2Bs1YV7a54mSynQdpA3PZuOW/9HQOFzLV2ZaANSQ==","XSwEtQQDTevqxgjhaqx2rLyUb43P+ZyyesJJWSroPROLwew2SApSd3ZyyVD+SYKCp/8EG1KBhGu/VQk+82Cltwopdy/NcTHk5SFMobqf2GXDAuVOmfLKOUUjC8o+koeb45m/tRwV91LpHRwbOrDQyCFwSCS+GTshsRZKa/Dw6x7a/zSfd/8Xxj3ztBkFBervN1CZW3udTmiAAsCh"],["e4tXgnSlTMebpukV2rWDaC+E4HvjlndvYYSAO3tuL01LEQldXSUi84PhsNCSsI5YQ7OzbD4mZBdkGifGuzbhHK4HLeBFnUNLsmY7ayDs4T5ifGrL2NPFG/DS6frD3Y1OfX3DECv5usYjgAlMsPxg6qFuHAP/maTw0gPBsTB0cWUwmvx+NmWJt+dwCchsK6I/dyl753mY7h7Rra4u","byGr45Uuodp0R7vzfh3KZz4V8iKq9PYyQJF15R7oiOoPeBTZEFN6f71Ph9JHVuEPbnAR0U9VhQWF2mBiE4nv3TRRIMkqeoxx//zMcmiKuRHjk4bSRYatBFkNXMpqt9Ds7sklZN4JzyafwFkldSyqPNASEBXIZY96kzFvfzcMfFR90ODleMgkSL+4BdyTNntw76L1mv4DOKplHyrI","S98K2Bh8v2XcHDRR/3QJMPZ4sLTFiwpNoA3zF+l9/HhtKSpW3gotwynu28riQ5oKK94ryfBEqjJclYaPSdKDb7tofnUngQ8yueLRHD1wwjrEBaRwpGBmFUubzkF7YeadGdiN6+M7kHo8vdVTo70PBod07+JfFI/S7WfYBIfpxnhYaUu0FrXb9dFm4pcpVHf8oP4X5okHP6YE7dWr","kuZ7C7bv9DnHBIMAHWyNy12hexeJ1h2vxn+Qzcv+YtjSHI368cV3w9wan1RddJoR0qYF5cTF/i3Zht+4+7fNCBjjMkts7mdMuXJlUMJLhE62Zl3D2hTgZcp5t71gpHn1SPT2QufLRdByWqwVIYiQ+OtkihoneZ8VCnrc18nbNg7jtD1nO5ALFqz5dtEwBiIhxD9Sx9qzpLKqZuWs","AQJFwzgFypgzIasZd9Ictajb3SiV/3zdKkO45ASQHfNpHp5oVyDow0TcTZIwUHV4G5kXdAfp6XXNj0v7+8uc4PfpVQlgLSGHurA2mmUC5XOVbjifTtcdF6Cw0atudLuRaGQew8Dh4gPAi9uzUxNpa9g8FMNiobq4vm6SNsJ7btzPAVlwsOiSumkmilS1gjSl9DD+moTmGN/30i9w"],["EfA8mAj36OB0orCzhqxBQqKwmSLwqsTVJ4R2k1OQuRauIwl/YoS1GNM/rhuQpum18rF03D5cRuiUWlrVAw0HK8tinvWbapl4oOozL1vP1uYCzvjuQzaWYGHieDXZQtvzzIxabXRWkRBlKFa7ZG55soN/hI6Q3dHcgBLwpsx49xJnc1Y8F+BfFd6Ag1tmbhCHRXOkzOQHQFoL3S6p","lhyEdgyHoGvDFO5sU2rbggUCQVMSU0o9OHy1IDKr/CeE5cT8yw3QDedJB19MlLfVgyvqeg2ZACw4ua1kRfAyPh9Q1YVGp+KSZlKmitrrA+54MhGAhV8SbvFkw1hQPjT3acFOKqD2Q8eO0EszEftYeiTK4sK05k+jiNHL7/3/n0taJeN1kwVJXb8n87MZZLJ/uVWco03yzOa+SYJv","8Yd1ecixxTk3SJx4V8h9IpxHLMLKrrWZyP+YS2sU8lAYcE50OkZtc38GCSQ2tHZcnKNY36AsXAxHzrdX5iExidlAO2tNYV90X4cnkYvDtS8q9AE73N4gOgfTdhb7qACKJCCWMo0boWpU9wX2COWhiQdJ8vs7/b8004ArkBWu7/le41VdZB/JgUs1gDbqeOEDRcG1Pwp3CkvjyU62","qIvIzKj07u8TYsFYgvzBOXygzGPQ/KGCAerhVq4bnV2EaOiMI3OGzIPwMlumTQ1dGMl5BKSarkHyc64AnyD5k7IbyPKkbaAoRAJxswFJeaQxgqP6yKmkPAANgA5ZmPfxHvoJH1A08o1odAjbuUwjX3sDYS7oTpr0Uqa7irv06TQFrIPmVvnp64TUtPn3dElVBxHDx+VDqCBJyzzA","SplpTVLVGrpS9AD9R70M7e9wkiCxcJhV4EcDgWD24QpqsV1QUumK0DMHmm+SEEzc5VgZQbrAYKpn+Im47XGvr7dElWqTbScZUwQzrAnka+P5BzUinRRuLLeJ6abNlx60iq11D7RsHt4r+80EyUVyHAzoHNOtDgIKtGyqVT6IyvSkt7FMjm+Bv5XruqJH0SvO/O0Dh7ig4e7lZLB5"],["6ITlwVdutsjERg7iy9KVimHA2btWhj4hGAiAxJSDX6SXv6564nVaujTJv8biOCvlYUO82l4KlZmwxc1l3rm5dcpeuQ95rRVkxEAf9f7ljK3MKD6x+FS43NTtw41PD+7QLDzLUMBC8kzGvCUspSNeJpK1xpvT8UMySB2m0yQckc7IEg7MuSbh5FunNW0iNKhocG9pfpJp00rcszYj","kt0fpCnwQTkjOyKmZTDvo1OxIm0PMCr4i2DEa8aYYrUpEJYv8iADlolOIYBa/y8GdxuialtaWLUbb8nmdO5XHQzTRRVVMt/XZD+odQZ+bG/+Ad+00caNW/ex8FOxpHyoAaJhpUiQCkPq/Mr2v4SZyg3fsFjwFDfH3V9s8FHLBHSYa7XcI3X465kluJyVFrfObXn7pNnzGelc+krb","bn7W11BOulgyDChjwTaT/sMGVxazJAnHVJWYAToNxzEe5ZO7yP+IsTwHfAEWWVE94k2A7bchlzccTJ9093rFKmKAeZGCD6dMoU97ojEZ24yECbZRMivpR9OKU7r9IqWoLGiKWp3y0qTaPxkgWQSwk9gn91Sm/Ulzu62pHo/QJLRoRUvCMqAnKmvHWtuWHot2zh95M2eKNQdBVJA9","NmqwUVxInMYpdPDKBeuIGPtECcg8+6FFQLERdV7hetk5MkADGOovQFGYr8Xbm05f182EuC4YhelMv0mU4FWWb8+ZIPCrH6Ixv5PKCYIZyo8Z4GTM8lR88MPACxAQUj6oPKi3+HUxwcVGFGraq2C3ZHPvLminbcNx+zvbjeKhBOksidfyNSIC6RFI35ssUqkUxUaBgDnLXJCkl3Vt","O5e9XjaWAjq3sXnmNvhksyTQdmV8enYrxg0v+2f/mhromRQsu3qWojjM/dp2Q8q/StNvf9HEYAznUJTXX4kYrFSC6Zf6nVQxKk0tuzpCJ5YaWxoTZxcn9EK7j+T6aKpO6W/dfyN5bDJQfQ4Sg+TpwB/36Zm215fcCYZegBCXmWk9h2YPuzNTyNItLS9b6E0+8rJzKVMugG4PEcQU"],["CIiA3JjmfP2xYXYIghMFE3MSCCzjg6gVc9pBjCCl/ACBSYJAwBBUjl0Rxm4TMYUE3cYqeoW5mrewXhHwSTiwpyNyQZjx37j2Hd+onljb2QVENgwdYTIkln8Kzrw2nPHVIHEE4TOC2ff13iG3rqLM/FVf7npr5Q0eqk5y5WgTDfD8eYn7AsOPSjX5Iestbe27RluWmETW1ygqo4e4","v1fGiSte1lfjNwmWk8a4Irro3Om86UmubvpYvhmUS4l/++qCPkfuivGjgI0Sb2TCA3Vv8I1faPPhG+mgi7FH+HQjzYYbyHL4lxbThxTBwd+QaotVBLGTP6rGhJ4DZBb4IkORdv0AHlpOiXSj1cJOzdpLtLWE07RTeB3UfErgyf06EIkAccOspiIGx96jYx4BMokP5XiJ1HhoHkZR","ELtYczoBaoXYl3R2sNE2VmEgmd1trRlsKOFprtw7HlfXVO+EFmJ6r3FUvjT46uEC14dkZ9ZoAyXs/JHs3ze5b+TZB/wz/DLnwVQ6ijlO3SmcbezEExTKqAn/wxx74K/HIcidq01MZA8UpVIET27GNqy2PiNUrmzr3L/S3xab5RMu5gZQkBR+rJA8LhL7tEJ9DqrkjXlrasFJfGj6","mDzP6OdwnBCQiBWs7FIjgrz4KdijrUS/c/vRR/BMNGR8ytZe8cy/T8Pu5KZGyt//iLiiH/5S6hJhh0iZ3LIbR0HmbBSBIMjNdM1V3txkHUB48WTZwke2GG2syZH4bb6FyftIJPrIFENGeIr/ny+pS55JVEn0Fl4MPwEmtNs7YM5sDjh9SnU7hLUkXcccrqA2nTYELzfWAW/12NUh","hs7nutTarHXMQlhRzPKxK4IjHkYRiVLgm1HWqWmn8DwbbezaKuo0zgNmhmlPTgnBPU5AlX5Ait60BUBEywmoaPJZn8WTf9C7Qbu+E1hZVhULELLgFeX+q/1Wyz0TavRHpEwEjPUM0FB+20PtV8LBou1EaSxXh63V601Rw5xRQYxDTV+nGX2qNr3uPn9z2ysWj7uJ3YKTxOa+Zm/R"],["awzx6EZ2YTSeCIO3LFLvu+ap5WBRIAIZUr/NDBUmMoKQCkDp+6wUCNSWkgXKQ2IMc0fgtaSOpNYhh174nhkKW5q5jmowuxePfMZzmIOyw3l1Jqr0Rvvx+7s2bhNar4voYhMBPa5W9+0QypO6Dc6+IhNf1InNWwXggaou4k3BDnWaEmBzPv8jU6LoRIK0N6L3ywWQcjErOTF/2X+n","UemVfsk7T4b4wvT7UkOPPRx1gTv/z4UUeiGXbhhNOzchqUGNd1S76ToWEasF375VYivWJ8Z4ANhs9dPe62WJzneLg7yEyVXI8DvhS6zxLLUGdK0wUvvJiw1yqQCW0E4jus0b2vWw8ZT0yIqjQ3/enNX66ebzhs2iMn+jfaqJSBwCW5CrpaEAes9xNH7jPRUjc1eESr+uPQCNBp71","WYrfxqmgpmPAbO9905/Mrl8i4Kp3DgJzqySeemVpbMFx120yEC7d+IHhfLaFGNoJeoa8IlW7ccTluSJXruQhWkhTg0+qPYnHk3rgUT5cz3mOZiFJKSiFHsIRHtYMB4Norblqz18e0tk5JmHffpWwNkBUVjIV6v/AqH6EIJ8sKGaR5OYe1zxYM8fZ34VVeFLHC/Z64nxgicsAlBXE","PMn1Bfhcbc67XNQTlJEh+Uv89rUgIn8R787RUycjTK7TL9OwipDl8yAtDJutsExLiXboukvNTVHN55sJEuQuZGj/bK0xc0WFN5fI6O80RPt+HmSRxDJ8/JzhlcUrDX0aHqu36UTTpFMiED8AXjcWitw0wrnBxjbQQ4aXcgnr7Xsx6Cbx+CcH2vUiqPI0mh63bLAXemLcxBAUGPLA","07hCP9ZHKt2CVdvlw1Ylf68uz6ORCzRzay3ZIb4CFRSGNu6BRFAuk8ylxS34qqOMoLbuQQj1xdnB36Ysff5LB6lLlcwDvg7TT5rYyu1bOWREMu28Unex4HsVR7tZJ99W0lnUz9XsTZD12eQebVBrnKy3027g+6XPsTiFH2Vjb1h+FVdY4RQoyJyFyOC7GCFLC1yqSlO+iIRlq44d"]];
                        }else if($packet_id == 31 || $packet_id == 42 || $packet_id == 33){
                            $lines = 10;
                            if($packet_id == 31 || $packet_id == 33){
                                 $betline = $gameData->PlayBet;// * $gameData->MiniBet;
                            }else if($packet_id == 42){
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            }
                            if($packet_id == 42 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                                $slotEvent['slotEvent'] = 'freespin';
                            }else if($packet_id == 33 && $slotSettings->GetGameData($slotSettings->slotId . 'Respin') > 0){
                                $slotEvent['slotEvent'] = 'respin';
                            }else{
                                $slotEvent['slotEvent'] = 'bet';
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks  릴배치표 저장
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                if(isset($gameData->PlayBet)){
                                    $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                    $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                }
                                
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', $betline);
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lines);
                                $slotSettings->SetBet();    
                                $slotSettings->SetBalance(-1 * (($betline * $this->demon) * $lines), $slotEvent['slotEvent']);
                                $_sum = (($betline * $this->demon) * $lines) / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '571' . substr($roundstr, 3, 9);
                                $slotSettings->SetGameData($slotSettings->slotId . 'GamePlaySerialNumber', $roundstr);
                            }

                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet);
                            $result_val['EmulatorType'] = $emulatorType;

                            $slotSettings->SaveGameData();
                        }else if($packet_id == 32 || $packet_id == 41){
                            $result_val['ErrorCode'] = 0;
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
                        $lines = 5;
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
                            if($slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') == 1){
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            }
                            $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet);
                        }
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'Respin') > 0){
                        $slotEvent['slotEvent'] = 'respin';
                        while($slotSettings->GetGameData($slotSettings->slotId . 'Respin') > 0){
                            $result_val = [];
                            $result_val['Type'] = 3;
                            $result_val['ID'] = 133;
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
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, ($betline * $this->demon) * $lines, $lines);
            $winType = $_spinSettings[0];
            $_winAvaliableMoney = $_spinSettings[1];
            
            //$winType = 'win'; 
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);

            if($slotEvent == 'freespin' || $slotEvent == 'respin'){
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
                $stack['AccumlateWinAmt'] = ($stack['AccumlateWinAmt'] / $originalbet * $betline) * $this->demon;
            }
            if(isset($stack['AccumlateJPAmt']) && $stack['AccumlateJPAmt'] > 0){
                $stack['AccumlateJPAmt'] = ($stack['AccumlateJPAmt'] / $originalbet * $betline) * $this->demon;
            }
            if(isset($stack['ScatterPayFromBaseGame']) && $stack['ScatterPayFromBaseGame'] > 0){
                $stack['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / $originalbet * $betline)* $this->demon;
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
            /*if(isset($stack['IsRespin']) && $stack['IsRespin'] == true){
                $freespinNum = 1;
            }*/

            $newRespin = false;
            if($stack['IsRespin'] == true){
                $newRespin = true;
                $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 1);
            }else{
                $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
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
                //$result_val['Multiple'] = "'". $currentSpinTimes . "'";
                $result_val['Multiple'] = $stack['Multiple'];
                //if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes){
                if(($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes)&&($awardSpinTimes == $currentSpinTimes)){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
                }
            }else if($newRespin == true){
                $isState = false;
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
            if(isset($result_val['FreeSpin'])){
                $proof['fg_times']                  = $result_val['FreeSpin'];
            }
            
            $proof['fg_rounds']                 = floor($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') / 3);
            $proof['next_s_table']              = $result_val['NextSTable'];
            $proof['extend_feature_by_game']    = [];
            $proof['extend_feature_by_game2']   = [];
            /*foreach($result_val['ExtendFeatureByGame2'] as $item){
                $newItem = [];
                $newItem['name'] = $item['Name'];
                if(isset($item['Value'])){
                    $newItem['value'] = $item['Value'];
                }else{
                    $newItem['value'] = null;
                }
                $proof['extend_feature_by_game2'][] = $newItem;
            }*/
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
            if($slotEvent == 'freespin' || $slotEvent == 'respin'){
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
                if($slotEvent == 'freespin'){
                    $sub_log['game_type']           = 51;
                }else{
                    $sub_log['game_type']           = 30;
                }
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
                $wager['game_id']               = 32;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = ($betline *  $this->demon) * $lines;
                $wager['play_denom']            = 10000;
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
