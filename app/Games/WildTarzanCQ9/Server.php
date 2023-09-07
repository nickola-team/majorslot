<?php 
namespace VanguardLTE\Games\WildTarzanCQ9
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
            $slotSettings->SetBet();  
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 5}],"msg": null}');
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
                            $result_val['DefaultDenomIdx'] = 3;
                            $result_val['MaxBet'] = 1200;
                            $result_val['MaxLine'] = 40;
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
                            $result_val['FreeTicketList'] = null;
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = [
                                "g" => "4",
                                "s" => "5.27.1.0",
                                "l" => "2.5.2.12",
                                "si" => "32"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 2;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["YQXExjCDM0ENsQkTtr75sqsWbc78XSw4TqINn3uESajB8CTaiiunqZyuTP52yaweN4YpIH3yx6NCXfPRqzjd6zfdJdykxXIdLEfjygkucpBWe1rdQ78qLdCsg/JoxEhT4GMSF5C5jvi+H1TeqxKKwA2FWjZKmw/RYf/d4LNiHKfhQ39skJZftmvwDD2imVmZ5QXpjX+wPdzRlyroXuKe4JbQ5+lUSMDYqgolHBPYxjz2UAoAk0p5/uYQ8J1vQyCNjNecEd6moLYEONNT","r5dYzCmjr3Hl6klv/7l82PJ+l38UrWRb8y2bmt2rI/lKueY3caoUePFudJMrbYlEBMPAFW9ifNNDjg25FQorOdZQMXDLtL4gAIEiKqEzUmVvXdqP0An9AvYctQhHFJO4acTS7yTFIh0f3Q0JTF+uEH9adeHmwdDZEajl9bonXIxsMyd7KpGxVn+g8NbJcl5raKmVN68cDqhzLEu6CeaVSWw5279bnM6gNRNCfKosPR42rei6tOOYTeWErL+Xrdflo8cpiBpZgUUqFUYxHLBBaTjPmLFF3pKunU35SLP70Ur3xoR/F3S8MIEBhxYYVX7yXC0hOQARZLLjSlKwOuIt3QO78PX4IUtm2SwGYd672UnjGMOBWcUZZdeMVLfy0PKGbSYYk0Z0XEvgHqvc","BcE9LCviaJlpvbSHu3rjZHk3G/eOE8pXZllMG8WbiRcQVvsy2XLRnRcnbnd4SIhfbi/LRd+N8dYeu4H1HvLKFZStviWecoW4yQklPtGExEHvsCAO7zEZEB3/LE8KXU/cTH/OezFYDPHIYUG+3r27/Bkjg/vihTDn+XefpjMXhVQ7g02uiDrwhVhXqJx6+odKPG5+eo9hkAmJ9iXTueKK08nyYRDT+m6WCdjOYgHeiJR+ophTDjAx9zNcGME=","ZehQRJVFKyBZ03RFFY73QOWbZjDPfIfeNHdXwugWMgqNSQqmzIfwJ5cdG0dArcwAHzGEFAa7z0PmRHjHEqW/SfTFZ5N22vpHTqiO4vpm86BJ2sz/AujFOTbGt5eXjYI/PtucJ5x+fuA6UTkBYRTSmym5pLkWbazQdH0M3KKTka/sJOnrDdcOO4zizy+WeBHDRIJrDnRFU10ZO0U/RVCOvNQ5r315/8FyhMzOwWPmfgFSH8+BlCZDOvjD3rvY97oTuePrQKONxnd72TNqLRfF3A0ZklCMRpNXedC7j60E+wFuenUPOUPjPcu0Xlmh5NyWSceyTsXo8ijzDrsS2Cxq9bYlsPxIA2TBh9HFqA==","hDHPssS785D24Vm1W6dI9xVJRz7A0Oz05Upo2RP+ulQ7DovDmIjm8VHO+CpEnD901/1NJVrb0i42ZtvzwToM4TITLvqBf9magwEgXNaeC3oAKlTj3qiaGHoUZp5yeFOXm5pMuFAfjm3yCmwjHWSMNgMJBNvcAzpg2EaGGB6t8ulVAYBosQJG60t5lJVfqOv3ohGGkilhhU4anfNKu8Nh9I1THcYoO9CDpg52A4/O2Uf36tqO8i1c2I7/VbZGD5Y3kpKDKru6eUbbRJ/nS1WEXZg4BojvWbdC9kycypA3V91LLdrXHPK3Kk+uOQ8="],["aXcYqX5nXxqcNlZryGpfyp/OlBFIaJPYoyyGAwnUk86FHDnDRu61lPC02NlP1inOrgVTD8h59n+Sj2c1zOImlK42EMxbbh2hol/8uNhmELRZ8UEmFp9cSp33jQFW0g1rlL0s3fFmSeT1I1odpo12wN7uwkc6SvdcymKn6TfviJjjLPmr0nvBMQ+63edgo2dEG3VO9hpTqc0d12v0Kw5DRKz4nC+MAI7+ou36AH1E4ydsRA3gXMV/yARNDmI/ZkVKIBBkqW0ksb6PWNwexxHLYCPpyt/uJC7E4MW2zQ==","YAdTilkhBt7tAwNQZBnILqzplSPIoloJaHYQHExQBRlTzgBW6c3WU8DeKnkBALwu7ermShHDhWaG3y0hdOOkSR0rRpu4V4/9BjewFHPyh+eOmhUHRLmd8eg426qEG0GEwNEXUmOa7fABzqOkueQioOTCd1p7CB9DsZxbk3Wpr5RlwtK+3jIFuoR30H5nh66O5h+46nW3BNxg6jf038Wpl2T2ALnP53KFY6UgyzRg9xAiLbGmUFUc4fJUl/3edujwDIdHns/u8d6aAO3yUzrwTU4LooKO9OIL4J6ufXPxlCnSghiKNyl68Nku9fiPxDGS9fy+rtAI5gPMl361/RhwUCEFAPrlNDFFw7w6Uk+fNPmSHRTYkiZPJjXLdhEWoMf09pOzSUhij0jY4ZxV","2M57WhmwBEwmcsvo0Wn8sM6yILIR1MCjfnhRPd32sE/fs7rBgLlp+KFtrRvr1PDyGBFdT5BGqpcwi01Q2DUrKg7qih7SyTUZOTGkiGkEtrws0lONxBkjI9UahQlOyjTi5eDt6zT3J1IF/R++01ZDBHkkYcnw/fWEofAjnL8f978Px0kAKEKvGqBJ50OQZb+zAACZo35aMX43qCczIgzBbYk2oejtnTJGg3DVDTUF1U9R9G4H2keur+T3fdw=","JYmcJryobKJ5iqiUU+b+aFyUqw1m1K6rRHNQLHq3ODxVFWGX8MqCIvMVE7BuBV7wTwMbj6flHsbPujeB8PPVM8hn/SLTxTB8ZMZQjyrgn1ughySAa+TsJ4TIsusTvdrxcdIdccdBnusRcgRJR0P0nDOmeSk65e7bDzkOocmZTZWIPMJNV8eKLwIevwHEmzlbbH540bV5I3RwcSuRN3BUFkP+Za15JRuQFVvaqp7+KCPqzbVwdLUaS2VtZYMsRvGZ8+ZVnkOmtAt+OA/v4vgPAZfL3vlLe+P7LBDKvJZQDFRY+xtOrXLzosbKy34O9ZV/wtQ/CgMnYJpQgFtdspagW6OdilqreYDA2PVBYNCIh+8jgoOGYUJLSuSINBM=","8zQXLyGp2WBFiq0Bf45ehBjuOMrsSvxZRqTso7lMf7V8HpEuRbov2WzBJwDrrMOhr9Wbnpj6lBQrV2lnMd0YbW8vt8oo494+Ibn9cEUIMzWUwXoI+2IZ8QCRyiDlOjrbGOzP4YI3/6/V9sTAs/J8EnggjPgN+Ftp0Izk/LR3nU+Z7LudtsTlFdRD/W9LIRf1d+7KAVKqdruh7AvefK2TwwpQn5QVL69rjlqPgqXaovKjGPGdG8GUY5Cne2MbQJn1/LPZZ/qTheJ3AepIr9/oXm2tw/N/6oeWU8eJH/LCa9sOKIuEnID9Joydrkhv4jsNUQmQVtxp2Q9K7Jrw8iAS3I4ORQgdKPyDfA9uVw=="]];
                            $result_val['FGStripCount'] = 5;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["X4qG6ySZLIDJ3Tf0TQNQVSxOUynLOKN7H3LO38slgCyOYiNMPinACazjgzjLcukTDY2QDk5HAjzlftfrt9IfwfwiLuekV9HVb3bzqphGWTQ53GGyv2bP+tXEc5ursq0iqwHAxCB44BUyoWfPy3MfWUnlYRPrM/i9SPdZoCm4nBWZNoDnYaXgWaPIU8GhpdA2Y5uc+oEXSwos42+oXUkcPt0Dbz3NvSYKCMMzblYgQaF3kt5TDOEYZy8TLK2Jk5nbjehWWVJDkeVSF8NICNJcYfRH9kjg3Sz+jxdPkkzHPMOlh+nSNVLl3HjFCMoeSsjADbt4JXhal1LBvSCb56gwqOwA0DJllJQuxk+fulo4EVPImRxLQVh9wXrJNmCqH8iajUjJjyRzVW81JZkZ","w1JmnXVridLq0IUkDxa/mLtO+/xdgc6F804C56Z2jA3INIr3ecMdGoS+5CeNBZum3k4wZLIevWoSwWaIZ/i4GXke9NPKZdT5KwqMesg0X7OxQgy8Wn1q/nna8BdHB0vdVvEYwA3kSw2OtXwbLQIw8ioqwaJFbyL6yoXefPDd1xDFFPfl3/8YH3Z51qN/e2asjFL77B2HfjryPraV6TsPraHPu7t5ck7wWGdDmqBy1vaLIo8gxQCUa5dtNOXJ6kRZZrWdVLQJocRC7hMn9R66CzRnOLUZP0bgJoliMCklac4jG9QRwC67ur0Nt6mEInC58HZInUGRdwXNsgWkty2AhmzxmfEpfyYrqWJhmg==","rTiHBwb99ceamYUeRRkY5Cn/a+L/kZKflNGa0iYhiIzCSCIUzXXGGsqSl9hqn1o/drpgvGKOup7z+teyQtwJzMYPFM7k76Jmgxt+H1SWgS5HHyh9fcmNFQgWsCgda4eu34s6AhtaXfN9giI2lfvXDynX63XYxajtK3KcNThfvwFk5j9jJreVc/458tdbLoJQhSwYDXlMkmDhag8ssQjYsqklSHoRr91nYe1/vRHuOAYa16v+8DiuK+iIIhT4zi7KBFZP8DPjjEP3gJBknDGoraRSRs3j2+YWF4Zn1Ws2Si3cVvikBpT7yYH35+n0M1WFZP9rTKYvdIx306hZsqY++MKmYzF8+d5v6hzAhz1I2RbgjUv7Aaf3keTTVyc=","wQ9rgGO5GDDcVcQ7Ssp4/HTB7Pb4s194XtW8gggAWPVtSTKWQEOGrzctYXmwJ/ITphdp6AGZXCTM+vU1J2qeDXu/XrRhCrAiH7lAtKMoFcDi8T1mmhZEaCkv+SLtUO6ChQiVkDfCFOG/kiQsOS7GJ5ae6FbCXCWBW/1t3FLXlu8SPzye8KJXRsExYh8xE8pDrrtBaZMvn9om6o12/wBsuOUP4YiJewIhCdsKtXuQEArKWjAb0NzJf8r+1htHnXcsBzVNqvtOxnsqxIlr","1ugOTZS8IWHZ8UaThRQmIkmKLgICrBQQijX/n+BHJ5MW8SXI+ziIbihcEvPjk2Ql+hPAf9wSRkwdnPODP0g3YK4+uwDv9krSFn00aRh9muM6wl4OjWBW06YErd5ypfMXaBzzkJ1dqOyxQWLrCJ/vxq4HDGaY2wDIu1APv9rZyb3tdh2cDmRbJBuGeBGd8vFHEUdpnw3ZbIsqBx5xEj/h18oQS2s2AOA5K+d4tKOpmRxtv+0Gac1TJq9Zg6IuLbxGRN5LIPjQBNa2CIC6W5WNhlZDRwH3cmiOGSNryUFesiFprqgMl0qQ/jPZXcbBlCQRSg9WIY0StKMoYDminrRhYAt+q4iRnp4c3hNI0Q=="],["oJRFHnyMzLclfn7VUfyt8Bs5b8vO0JJ7XR7Q875Si4+VdvhVr3gXdxh5S6fjKP1NhusMMHdzFw6clWYV0t4DpDFDfi7KTpwNyjH5quCBhvQrX2TrRvHRbdjyTehNVyK8WAEQjJH01yBdeYfO8jwqXh7GAeH+ni0N4MMtSkQDWg0UwlaleWorGy1wuuLhA9waDIMb1T4ud/xInrPavMZyRuEeUH7sRf/kYhADgf4PTreP4MfoxBzgXNMOSxCKfh91q3t3mERSYU83Av1MpwOAKpmptnq9/kGNzr18xlDj4CsgXPKrHZ8EsgZSrA47Z0muT4prWm8G5UUd6Kf3TSZLdz42UfOoMAk81RruwuOVj53EN9Igb/XKaasW1pn196isXhsOTRey+JVNIj0il5e1VWVmfUQKb0KhCmPcjA==","HB5vkleBKDuYsu1g4TWB0TX4mle9MeWD3/f17KPNryoflX4BKUlmrQiKkvSQ1kd3UgiR6ClnulUtU2V6RQPniEhCKKADI9TMhqZBkufFety5NBsftvKUnodzworEvcZ4YcX342YLcPoPHU0O9ZVJHRAsoz6WlTyzgwHUs75oV3bO1hyo4lPc701dXgl+flic/qSerwdvv3jsq1PFZ9XamxHguNBt0FcfLuWlrXjdcwm4iBn7QMbvxqZPe2lOH1oCXXewwUhPekDfPkzASG0lV5JLfWbikpliVaCz7nyipqtIijU+KPSLHbNFM31iOwF7Oi4u8JQhM/lG3jBfU1nKoYeA3ZPnxUSvKGNmoqKEStbvNSOESLGdDH8/gGKX4tSZOPy7tUNE37zDMwRX","hyY3n1b0a5GwfoAllXAwSnxt31Hp/zgkp3c/M1IleecnhvPAXQf0NgHa1uQWUAZNACPfCmfttNKxNXNSDNHj3uB/eyfKG5MRQAgYIbJcKVTGosDO/kVJpQVq1gJpM5NORejf5Dxz3iRYDwVBXiKDXB5uIEn//cCclYfSVH8XH1KxQlnAwftO0hiFXkLayYF8nQfx3JymrRQI6fB41dAYWmaIC3TnbRtFugZpTFEYWiWFYdcQbq4ABudhdtNaI0EBC5YTeju5UmbxTLlmMF1Hudw9myjK5XaHfZnD624R6TVWBhoZQeqh9oHVCyeyriZ9FhK5WzV+s7CO1yRF04koOuBUluv/Wf2zO/tBuiwDS09i1YOziAMPJclqqrLPevccRH69+jtjGNlFjypaMJPIz70I44RICfi2cAKd6LjO9kSoshSqFpOWA56U18M=","dr3fpSQJ2CRu5MDRqVinDoNnq6rpxR3okXA168E+EDH4O8ocjFun/rOZckfcYhBuFBRudx5hCy8IP2muLFLQaGUg3cONmvnAzcX79iDAmEKYfX9bUqXdxwGbGCtG4wA+6xkW5lTbdyloep04Kt0Zg5mp+QkAfkYcII3gG/YLXxNNC3HFnFLOZDwmq4S5Ff7cTrjDN7qSsWOJy1htJ9dM9TysB6/lfYGk41U12zEkbY8T58P3X3smQPBKjqUO9BwhDPH5MVynsrm1wAR1RTLf4VWlhOpqFlK+iF2F2A==","Ph0VhhT3wbAaHWniuITCwuYrUSvkpshDO/Afwqj2H/ORkOnRNu44ATWbzRh/ckyF1xzlCEdoWcVaEufU/Goaz9kSAzJ9FMM6jiVd6OxU81U/NBpHCsLaM2JbjxUr0Eo/tOE0QiDqMRMy+gQZgt7tjgoZcD7Z4e+feRP9z8Jwi2mWxZq7zK3LUEM4xf+5nLRtzJtKOQavrbQmTep/ZNrRx77pnfSOZxwmzPHNPRuhzEH/9rL+QLINAwBlAH59G21nOEYjO68TvffXiMooL7ZSGyj6vu3vzanIdOf+7/J9+1VkzV/8i8oDWKTf/at3Qd5zfYWQUbbZZ6jLxGJYsDzl0iaQNMEm5onz7oGNEuD32jB/LpfdNZY3SkwNAwE="],["3WWpXweNEhQ8aiitADNSUsj3Z2OxQqhN9VnLG0NP+L0VceqSF0vHHM4axslr1MD5O0DW0U5bHF6foIXnJVTFmtTM5el1L+6rcjPbwHoN9q0T9qovlofSazSdjWtoNL+wTRGH8agOfDJMPqWg42jAjzURmm4VZB4pN6jWFxESFzislXz/NV/wymzywr0WmL+jsN9kiSKY8w3r5nZv0kpbv5ocZaN7NM6yRZ+wqrrcykvFwy/EbrpcSerxaZoVhezWmYNR/Di/MJXDWxR1sQ6hKyQHgGeYUZ0Ns8x8Af17fv5lSYAgwF/mpQW/UZugJ12ThxrdcxhG50tSkodwDR5N8q6+3PlRjaezmW9WwdUS2keTZ0X+sfMlMO9LmMSh/mDyEOfusrDVf+z23d2O6OOOHLujmyEtJmVtdpnYap8FyP0ziCnQ51uk0AWb2yE=","hwObWEHZXwpCPFfk72CNkV3nYiDQ4qmlCoV+Re77he3+LBBkNHKok4pXujrnwgfHiyghZRDWuV2FPwZ6u+quRWUfbbN3Cd2YSUwljLRTkl0hbLsipxXxYCs7dtU1bd9HlHlp1YHWVNJpsNAyPByGKF/nWMJjL7iVohfw7vqAXyaRtI9T7Mq02mS6vHMwjTlfr/3V6gyV35c/jjk/F+uLS28M/j9z8Y3ltATTraQshqZK79nrZrijTz8Pl7HOW1d1F3m6UA621GTnr8SWsGfazpqAgSUTSSnD3uMppeEWpS4Z7uZDYCwEUykfMbqXZpbkmbnPNiWxoyCBf3gDw6RVZO7FzZEpJnBY0+1EHhkbr23wIw3yhaQsKLaNZko3ECGLEud1wh6J8wA514PZGOIHy02A6FZiflRvGWJiAg==","WCMPh6u68NqIAdAG0aHdxm70iQASU7Hf1UOoGdoZ0hsecijC2JI7ThbQaAnNCPOWr2Q6O/68uLRFL97yKoQCMquK8ArrZM4unBGgtqPjIEWmwyjfItHuYayw0mgwQjp4sBcT95DSWAbLlWmvjf8JMPve1oHTVfjtRy9RKECj0ayoMt7KFeq250CpG9yhSNovLxRqJ/QCWOByhPevjIaVqdetG3OlYmcuAUmMkywvyHvOttTBcqRKES4baoruiqTHnwoTpt+657P7bNsCkU4nSRnWrs1ZHmMoZuPnTYcViKUQeVWksjvnKkXx1FY2b7E+Qc8XlMUdcBXuEy52/AlZKe8vwfBRyKsVm832RKgk+cUerw7E5KnGdqWa6976VFErUrMOfolrbqcn2F5l8yDWyqKUx3PVx+0bE4dET1n+AnyvkrvR82Zuv1e7v1k=","cZBQPTH56TSovXzMWCmk5f6Nd6GwmmgK97QnZM+MdSau5NSCKKMLILboy/ItVi5Vdurg4qP72LCei4D5/lucJNHPhZ2R17WWxDCBSiwshSGRyM6i5+C6aYyFpT4tkNjn6U9FI+6ewOrMJMif5Qd+FnVqDgv7Y3/g+hdpXIysz+Spgg63z2tQV8pT6A/VvcRdyZTyMnUgLOMkDoi9nu47gTTfQ1isibxPN5YEUH32JNZ6EnFuxhtAReJryhl3fi0Lua3Ca7xn+5aJNNeRHDUwKM+z/952gwXYLbBq7Own0DU7CpvWxsoEI+XTp8o=","qdzNlde2lbyyoM3AzlsyNGQCUkqneZJxht9hXNtQG2gLlr0DJOf7dCNoGJmmDghSoeN1b3fyv2cKQuyO0ldHpi/Fdb04u91sOtk2m/xXhKll4Ht4jw3tFQneRyWKQEoTCDfJsVQfcVHT4UwSYuiyZzLyx4aon5O4oOlxvw1t1WIkmZ3sLS6ytujsST3laepV7XwNpJ4MDKc06UnSNLa/FxLtPa1UldPSoaIQmaGtxkMaKzHvEBg+WP7wqKufNq31gu1+P92T+/xY74VkCWE2oSefQwgb8cW10EBm5AGVTFfYnFj8EZy3bbTf6oAsAXo0uhaJf7F3sNsc76HZ0CIrAMNcHq394OCRxoF8aMHG6J4vRVCBX1St7hlZyycPubmGEfydumY6hcbnj+tq"],["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"],["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"]];
                        }else if($packet_id == 31 || $packet_id == 42){
                            $lines = 40;
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
                                $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', $betline);
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lines);
                                $slotSettings->SetBet();        
                                if(isset($slotEvent['slotEvent'])){
                                    $slotSettings->SetBalance(-1 * ($betline * $gameData->MiniBet), $slotEvent['slotEvent']);
                                }
                                
                                $_sum = ($betline * $gameData->MiniBet) / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '662' . substr($roundstr, 3, 9);
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
                        $lines = 40;
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
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, $betline * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet'), $lines);
            $winType = $_spinSettings[0];
            $_winAvaliableMoney = $_spinSettings[1];
             //$winType = 'win';
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);

            if($slotEvent == 'freespin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
                
            }else{
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, $betline * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet'));
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
                $totalWin = $stack['TotalWin'];
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
                $currentSpinTimes = $stack['CurrentSpinTimes'];    
            }
            foreach($stack['udsOutputWinLine'] as $index => $value){
                if($value['LinePrize'] > 0){
                    $value['LinePrize'] = ($value['LinePrize'] / $originalbet * $betline);
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
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes && ($stack['CurrentRound'] == $stack['AwardRound'])){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
                }
            }
            

            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
            if($isState == true){
                $slotSettings->SaveLogReport(json_encode($gamelog), $betline * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet'), $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
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
                $bet_action['amount']           = $betline * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
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
                $wager['game_id']               = 4;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = $betline * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
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
