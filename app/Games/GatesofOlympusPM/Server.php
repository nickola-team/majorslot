<?php 
namespace VanguardLTE\Games\GatesofOlympusPM
{
    include('CheckReels.php');
    class Server
    {
        public function get($request, $game, $userId) // changed by game developer
        {
            $response = '';
            \DB::beginTransaction();
            // $userId = \Auth::id();// changed by game developer
            if( $userId == null ) 
            {
            	$response = 'unlogged';
                exit( $response );
            }
            $user = \VanguardLTE\User::lockForUpdate()->find($userId);
            $credits = $userId == 1 ? $request->action === 'doInit' ? 5000 : $user->balance : null;

            $slotSettings = new SlotSettings($game, $userId, $credits);
            $this->slotSettings = $slotSettings;
            
            $slotEvent = $request->all();
            if( !isset($slotEvent['action']) ) 
            {
                return '';
            }
            
            /* 전역상수 */
            $BALANCE = $slotSettings->GetBalance();
            $LASTSPIN = $slotSettings->GetHistory();
            // $LASTSPIN = null;

            $slotEvent['slotEvent'] = $slotEvent['action'];

            if( $slotEvent['slotEvent'] == 'doInit' ) 
            { 
                $objRes = $this->doInit($slotEvent, $slotSettings);
            }
            else if( $slotEvent['slotEvent'] == 'doSpin' ) 
            {
                $objRes = $this->doSpin($slotEvent, $slotSettings, $LASTSPIN);
            }
            else if( $slotEvent['slotEvent'] == 'doCollect') 
            {
                $objRes = $this->doCollect($slotEvent);
            }
            else if( $slotEvent['slotEvent'] == 'doBonus') {
                $objRes = $this->doBonus($slotEvent, $LASTSPIN);
            }
            else if( $slotEvent['slotEvent'] == 'update' ) 
            {
                $objRes = [
                    'balance_bonus' => '0.00',
                    'balance' => $BALANCE,
                    'balance_cash' => $BALANCE,
                    'stime' => floor(microtime(true) * 1000),
                ];
            }

            $slotSettings->SaveGameData();
            \DB::commit();
            return $this->toResponse($objRes);
        }

        public function doInit($slotEvent, $slotSettings) {
            $BALANCE = $slotSettings->GetBalance();
            $LASTSPIN = $slotSettings->GetHistory();

            $objRes = [
                'tw' => '0',
                'def_s' => '9,5,11,10,10,9,9,5,11,10,10,9,8,8,4,11,4,8,8,8,4,11,4,8,11,3,7,5,9,10',
                'balance' => $BALANCE,
                'action' => 'doSpin',
                'accm' => 'cp',
                'cfgs' => '6547',
                'ver' => '2',
                'acci' => '0',
                'index' => $slotEvent['index'],
                'balance_cash' => $BALANCE,
                'def_sb' => '11,3,7,5,9,10',
                'prm' => '12~2,3,4,5,6,8,10,12,15,20,25,50,100,250,500',
                'reel_set_size' => '8',
                'def_sa' => '4,10,8,8,6,11',
                'reel_set' => '1',
                'balance_bonus' => '0',
                'na' => 's',
                'accv' => '0',
                'scatters' => '1~2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,100,60,0,0,0~15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,0,0,0~1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1',
                'gmb' => '0,0,0',
                'rt' => 'd',
                'gameInfo' => '{rtps:{ante:"96.50",purchase:"96.50",regular:"96.50"},props:{max_rnd_sim:"1",max_rnd_hr:"697350",max_rnd_win:"5000",max_rnd_win_a:"4000"}}',
                'wl_i' => 'tbm~5000;tbm_a~4000',
                'bl' => '0',
                'stime' => floor(microtime(true) * 1000),
                'sa' => '6,11,8,8,9,7',
                'sb' => '7,7,11,5,7,9',
                'sc' => implode(',', $slotSettings->Bet), // '10.00,20.00,30.00,40.00,50.00,100.00,200.00,300.00,400.00,500.00,750.00,1000.00,2000.00,3000.00,4000.00,5000.00',
                'defc' => '100',
                'sh' => '5',
                'wilds' => '2~0,0,0,0,0,0~1,1,1,1,1,1',
                'bonuses' => '0',
                'fsbonus' => '',
                'c' => '100',
                'sver' => '5',
                'bls' => '20,25',
                'counter' => ((int)$slotEvent['counter'] + 1),
                'l' => '20',
                'paytable' => '0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,500,500,200,200,0,0,0,0,0,0,0;500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,200,200,50,50,0,0,0,0,0,0,0;300,300,300,300,300,300,300,300,300,300,300,300,300,300,300,300,300,300,300,100,100,40,40,0,0,0,0,0,0,0;240,240,240,240,240,240,240,240,240,240,240,240,240,240,240,240,240,240,240,40,40,30,30,0,0,0,0,0,0,0;200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,30,30,20,20,0,0,0,0,0,0,0;160,160,160,160,160,160,160,160,160,160,160,160,160,160,160,160,160,160,160,24,24,16,16,0,0,0,0,0,0,0;100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,20,20,10,10,0,0,0,0,0,0,0;80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,18,18,8,8,0,0,0,0,0,0,0;40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,15,15,5,5,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',
                'rtp' => '96.5',
                'total_bet_max' => '10,000,000.00',
                'reel_set0' => '3,1,10,11,9,7,5,11,11,11,4,10,10,10,8,6,8,8,8,1,8,5,4,8,5,1,10,1,8,4,10,1,8~11,11,11,5,7,8,4,1,10,10,10,10,11,9,3,9,9,9,6,4,10,4,9,4,10,4,10,4,6,1,9,3,9,4,3,4,9,6,9,3,10,1,10,1,3,8,10,3,10,1,9,6,3,6~5,5,5,5,1,4,8,6,8,8,8,3,7,7,7,9,7,11,10,7,8,6,7,4,6,8,11,10,4,11,7,8,6,4,11,8,7,11,10,8,4,9,6,7,8,11,7,4,9,11,8,6,8,11,7,8~4,9,7,10,10,10,6,3,3,3,10,8,7,7,7,1,11,3,5,6,6,6,10,7,11,10,5,6,10,7,10,1,7,6,3,6,1,3,1,7,10~7,8,5,10,3,9,11,8,8,8,4,9,9,9,6,1,4,4,4,7,7,7,11,11,11,8,11,9,3,1,8,3,9,6,8,6,9,6,9,11,9,11,8,6,4,9,8,9,1,8,9,8,9,4,1,11,9,8,1,6,8,9,8~6,6,6,6,9,9,9,11,5,8,3,10,10,10,7,4,10,11,11,11,9,1,4,4,4,8,10,9,11,4,8,11,4,10,11,9,5,8,4,8,4,10,9,4,9,5,9,11,7,11,9',
                's' => '11,4,9,8,4,6,8,10,8,10,5,10,10,8,10,10,5,6,11,9,7,11,7,11,9,9,11,11,9,11',
                'accInit' => '[{id:0,mask:"cp;mp"}]',
                'reel_set2' => '4,5,7,11,11,11,6,9,10,10,10,3,8,8,8,8,10,11,12,10,8,6,11,6,11,6,10,11~6,9,9,9,12,10,11,11,11,4,9,7,5,4,4,4,8,11,3,10,10,10,3,8,7,11,9,11,3,11,4,8,3,4,9,12,8,11,9,11,9,12,11,10,3,9,12,9,8,10,4,11,9,12,7,9,7,8,11,4~8,8,8,7,5,5,5,5,7,7,7,12,4,11,3,6,9,8,10,7,5,10,5,12,7,10,7,3,10,7,5,7,11,10,6,5,10,5,7,12,10,12,11,10,3,5,10,5,11,5,7,10,5,11,5,7,12,3,7,3,7,10~12,6,6,6,8,6,10,11,7,7,7,7,9,3,3,3,3,4,5,10,10,10,6,10,3,10~12,10,8,9,11,11,11,11,5,6,7,9,9,9,3,4,8,8,8,7,7,7,4,4,4,9,11,9,7,9,7,6~4,4,4,4,8,11,11,11,12,7,10,10,10,9,5,3,10,6,11,6,6,6,9,9,9,7,5,6,10,6,5,9,10,12,6,3,7,10,11,10,7,9,12,6',
                't' => 'symbol_count',
                'reel_set1' => '6,5,8,3,11,1,7,10,4,9,5,10,4,9,10,9,7,10,5,9,5,10,7,10,4,10,7,4,5,9,5,10~5,1,6,7,8,10,9,3,4,11,8,9,4,7,9,4,6,8,11,3,6,10,3,9,8,10,8,11,6,9,1,7,6,7,11,6,11,4,11,7,11,9,6,4,8,3,11,1,9,7,10,11,4,11,3,4,9,11,9,8~10,1,8,11,9,5,6,4,3,7,8,11,6,11,4,11,6,7,5,8,7,11,3~10,4,5,8,8,8,1,8,3,7,11,9,6,9,3,8,4,7,8,3,8,3,9,1,9,7,8,9,8,11,9,1,9,7,8,11,3,7,8,1,7,8~3,9,8,6,5,10,11,1,4,7,4,5,4,1,4,8,5,4,5,7,5,9,11,10,5,10,6,10,4,10,7,5,10,6,7,9,10,5,10,7,1,11,5,10,9,4,8,10,1,4,5,4,10~9,9,9,9,3,1,8,4,10,7,5,11,6,3,8,3,4,3,5,8,3,11,3,5,3,11,3,11,3,1,3,5,3,7,4,5,3,5,8,11',
                'reel_set4' => '10,11,11,11,8,10,10,10,7,8,8,8,3,11,4,9,6,12,5,7,8,11,8,11,7,8,11,4,11,4,7,8,5,8,7,6,7,11~3,10,10,10,11,9,9,9,4,12,11,11,11,5,7,8,6,10,9,10,11,9,11,10,6,10,9,11,9,10,11,5~5,11,8,8,8,9,12,7,7,7,6,3,5,5,5,7,8,4,10,7,12,8,7,6,7,10,8,12,7,8,12,11,7,8,10,8,7~6,6,6,5,10,6,11,10,10,10,8,7,4,12,3,7,7,7,9,3,3,3,8,12,10,7,3,9,3,4,3,8,7,5,3,10,4,10,3,5,3,10,12,11,7,10,3~5,7,9,9,9,3,12,6,10,11,4,8,9,11,11,11,8,8,8,4,4,4,7,7,7,9,6,7,11,4,10,12,11,8,9,7,4,3,4,7,9,4,11,4,12,9,4,12~9,10,10,10,6,4,4,4,12,7,5,6,6,6,8,4,9,9,9,11,10,3,11,11,11,7,4,8,4,12,7,11,3,11,12,6,11,5,3,7,5,10,12,4,10,5,12,10,6,11',
                'purInit' => '[{type:"fsbl",bet:2000,bet_level:0}]',
                'w' => '0',
                'reel_set3' => '8,8,8,7,9,10,10,10,11,8,11,11,11,4,12,6,1,5,3,10,1,10,9,10,11,10,11,1,11,10,11,4,1,4,11,6,12,11,10,11,10~4,9,9,9,9,6,11,11,11,1,3,10,10,10,5,12,8,10,7,11,12,1,8,9,11,12,9,11,10,12,9,6,9,11,5,12,9,11,12,11,12,9,11,10,11,5,9,1,10,9,11,5,11,5,12,9,11,5,1,12,9,11~5,5,5,6,8,8,8,12,7,7,7,8,7,10,4,11,3,5,9,1,11,9,7~1,5,12,6,6,6,4,7,7,7,9,11,3,3,3,10,10,10,10,8,6,3,7,6,9,3,10,3,9,7,11,3,10,9,10,6,11,10,7,3,6,3,6,7,10,6,3,8,3,9,7,10~4,1,7,11,11,11,10,8,9,9,9,6,9,11,12,3,5,8,8,8,7,7,7,4,4,4,1,7,9,5,9,7,12,6,8,12,9,8,11,7,8,10,11,8,11,7~10,6,5,9,6,6,6,1,4,8,7,12,3,11,9,9,9,10,10,10,4,4,4,11,11,11,4,5,3,6,1',
                'reel_set6' => '7,8,4,9,3,6,11,10,5,6,10,6,11,3,5,6,10,6,3,10,11,8,4,11,6,10,4,8,3,6,9~10,9,4,3,6,8,7,5,11,5,11,4,3,4~11,5,3,6,9,10,8,7,4,9,3,9,4,9~4,7,5,6,9,3,8,11,10,8,10,8,10,5,10,9,10~11,9,7,8,10,4,5,6,3,8,9,5,7,9,5,4,10,6,4~10,4,5,8,6,7,3,11,9,8,3',
                'reel_set5' => '10,10,10,9,10,7,12,4,3,5,5,5,5,11,8,6,6,6,6,4,4,4,3,3,3,7,7,7,9,9,9,8,8,8,11,11,11,4,5,6,5,4,3,7,6,3,7,4,6,4,5,3,11,5,6,5,4,6,3,5,4,6,4,3,11,4,5,4,5,4,5,7,5,11,4,11,3,4,6,7,6,9,12~4,4,4,9,5,10,9,9,9,11,11,11,11,3,6,6,6,7,6,3,3,3,4,7,7,7,12,8,8,8,8,10,10,10,5,5,5,3,5,6,3~10,6,6,6,11,8,5,9,6,4,12,3,7,7,7,7,3,3,3,5,5,5,9,9,9,10,10,10,8,8,8,11,11,11,4,4,4,3,5,3,9,6,3,6,8,5,11,4,7,4,3,11,6,5,11,3,6,9,3,11,3,5,11,4,9,6,3,4,6,5,3,7,5,7,11,8,4,11,6,3,5,4~5,12,3,3,3,10,4,6,8,7,11,9,3,9,9,9,7,7,7,8,8,8,5,5,5,10,10,10,11,11,11,6,6,6,4,4,4,8,4,3,10,11,3,10,9,10,3,6,8,10,9,11,9,6,9,8,3,4,8,11,10,8,11,8,11,3,9,3,9,8,10,7,9,8,10,8,11,8,7,3,4,3,11,3,8,6,10,3,4,3,8~3,7,7,7,7,9,5,3,3,3,6,4,12,10,10,10,8,10,9,9,9,11,11,11,11,4,4,4,6,6,6,5,5,5,8,8,8,9,4~8,4,3,3,3,3,5,6,6,6,11,9,9,9,7,6,8,8,8,9,10,10,10,12,10,11,11,11,5,5,5,7,7,7,4,4,4,11,4,6,9,3,5,3,7,3,12,5,3,9,11,3,9,6,4,6,3,4,3,5,4,5,7,4,11,3,7,5,3,9,6,9,4,12',
                'reel_set7' => '7,3,10,5,9,4,8,1,11,6,4,11,3,4,9,4,8,10,8,10,9,8,3,4,11,4~10,9,4,8,11,1,5,3,7,6,9,5,11,6,11,7,11,8,7,11,6,7,9,3,11,4,7,8,6~8,6,7,5,11,9,10,1,4,11,11,11,3,6,7,3,7,3,11,7,5,11,5,10,4~4,8,11,9,7,3,1,6,5,10,7,8,10,1,5,10,6,10,1,7,6,10,1,8,6,10,6,7,10,8,6,7,10,6,10,7,1,6,10,8,7,8,10,6,10,11,6,10,8,6,11,7,10,6,7,6~4,11,3,6,7,1,10,8,9,5,3,10,1,3,7,10,7,11,3,5,7,10,1,3,10,3,10,7~9,9,9,11,8,9,7,5,3,1,10,6,4,8,3,11,8,5',
                'total_bet_min' => '10',
            ];

            /* 마지막스핀 결과 로드 */
            if( $LASTSPIN !== NULL ) {
                $objRes['c'] = $LASTSPIN->c ?? null;
                $objRes['tw'] = $LASTSPIN->tw ?? null;
                $objRes['w'] = $LASTSPIN->w ?? null;
                $objRes['sa'] = $LASTSPIN->sa ?? null;
                $objRes['sb'] = $LASTSPIN->sb ?? null;
                $objRes['s'] = $LASTSPIN->s ?? $objRes['s'];
                $objRes['na'] = $LASTSPIN->na ?? $objRes['na'];
                $objRes['bl'] = $LASTSPIN->bl ?? $objRes['bl'];
                $objRes['accv'] = $LASTSPIN->accv ?? $objRes['accv'];

                if (isset($LASTSPIN->rmul)) {
                    $objRes['rmul'] = $LASTSPIN->rmul;
                }

                if (isset($LASTSPIN->apv)) {
                    $objRes['apt'] = $LASTSPIN->apt;
                    $objRes['apv'] = $LASTSPIN->apv;
                    $objRes['apwa'] = $LASTSPIN->apwa;     
                }

                /* 윈라인 l0, l1, l2 ... */
                $jsonLASTSPIN = json_decode(json_encode($LASTSPIN), true);
                $winLines = array_filter($jsonLASTSPIN, function ($value, $key) {
                    return strlen($key) > 1 && str_starts_with($key, "l");
                }, ARRAY_FILTER_USE_BOTH);

                $objRes = array_merge($objRes, $winLines);

                /* 프리스핀 구매 */
                $objRes['purtr'] = $LASTSPIN->purtr ?? null;
                $objRes['puri'] = $LASTSPIN->puri ?? null;
                
                /* 프리스핀 */
                $objRes['fsmul'] = $LASTSPIN->fsmul ?? null;
                $objRes['fsmax'] = $LASTSPIN->fsmax ?? null;
                $objRes['fs'] = $LASTSPIN->fs ?? null;
                $objRes['fswin'] = $LASTSPIN->fswin ?? null;
                $objRes['fsres'] = $LASTSPIN->fsres ?? null;
                
                /* 텀블스핀 */
                $objRes['tmb_win'] = $LASTSPIN->tmb_win ?? null;

                $objRes['rs_p'] = $LASTSPIN->rs_p ?? null;
                $objRes['rs'] = $LASTSPIN->rs ?? null;
                $objRes['tw'] = $LASTSPIN->tw ?? null;
                $objRes['w'] = $LASTSPIN->w ?? null;
                $objRes['rs_c'] = $LASTSPIN->rs_c ?? null;
                $objRes['rs_m'] = $LASTSPIN->rs_m ?? null;
                
                $objRes['tmb_res'] = $LASTSPIN->tmb_res ?? null;
                $objRes['rs_t'] = $LASTSPIN->rs_t ?? null;


                /* 프리스핀, 텀블스핀일경우 당첨금 */
                $fsmax = $slotSettings->GetGameData($slotSettings->slotId . 'FSMax');
                if ($fsmax > 0 || $objRes['rs'] != null) {
                    $objRes['balance'] = $LASTSPIN->balance ?? $BALANCE;
                    $objRes['balance_cash'] = $LASTSPIN->balance_cash ?? $BALANCE;
                }
            }

            return $objRes;
        }

        public function doSpin($slotEvent, $slotSettings, $LASTSPIN) {
            $S_SCATTER = 1;
            $S_MULTIPLIER = 12;

            $lines = $slotEvent['l'];       // 라인
            $bet = $slotEvent['c'];         // 베팅액

            /* 텀블스핀 판정깃발 */
            $isTumble = false;

            /* 더블벳 판정깃발 */
            $isDouble = false;

            /* 프리스핀 구매 */
            if (isset($slotEvent['pur'])) {
                $slotEvent['slotEvent'] = 'buy_freespin';

                $winType = 'bonus';                   // 보상방식
                $_winAvaliableMoney = 0;        // 당첨금 한도
            }
            else {
                /* 더블벳 */
                if ($slotEvent['bl'] == 1) {
                    $isDouble = true;

                    /* 더블벳인 경우 라인수 25개로 */
                    $lines = 25;
                }

                /* 텀블스핀 */
                if (isset($LASTSPIN->rs)) {
                    $isTumble = true;
                }

                /* 남은 프리스핀이 있을 경우 */
                $fsmax = $slotSettings->GetGameData($slotSettings->slotId . 'FSMax');
                $fs = $slotSettings->GetGameData($slotSettings->slotId . 'FSNext');

                /* 이전 프리스핀검사, 새 스핀결과 결정 */
                if ($LASTSPIN !== NULL && $fsmax > 0) {
                    $slotEvent['slotEvent'] = 'freespin';
                }
                
                /* 스핀결과 결정 */
                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $bet * $lines, $lines, $isDouble);

                $winType = $_spinSettings[0];                   // 보상방식
                $_winAvaliableMoney = $_spinSettings[1];        // 당첨금 한도
            }

             /* Balance 업데이트 */
             if ($slotEvent['slotEvent'] === 'buy_freespin') {
                $allBet = $bet * $lines * 100;
                $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);

                /* 프리스핀 구매금액은 bonus에 충전 */
                $bankMoney = $allBet / 100 * $slotSettings->GetPercent();
                $slotSettings->SetBank(($slotEvent['slotEvent'] ?? ''), $bankMoney, 0);
            }
            else if ($slotEvent['slotEvent'] === 'freespin' || $isTumble == true) {
                /* 프리스핀, 텀블스핀일때 베팅금 없음 */
                $allBet = 0;
            }
            else {
                $allBet = $bet * $lines;
                $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                
                $bankMoney = $allBet / 100 * $slotSettings->GetPercent();
                $slotSettings->SetBank(($slotEvent['slotEvent'] ?? ''), $bankMoney);
            }

            /* 벨런스 업데이트 */
            $BALANCE = $slotSettings->GetBalance();

            /* SCATTER 생성갯수  */
            $defaultScatterCount = $slotSettings->GenerateScatterCount($winType, $slotEvent['slotEvent']);  // 생성되어야할 Scatter갯수 결정;

            /* 릴배치표 생성, 2천번 시행 */
            /*************************************************** */
            $overtry = false;           // 1500번이상 시행했을때 true

            for ($try=0; $try < 2000; $try++) { 
                $this->winLines = [];
                $winMoney = 0;

                /* 릴배치표 생성 */
                if ($overtry) {
                    /* 더이상 자동릴생성은 하지 않고 최소당첨릴을 수동생성 */
                    // $lastReels = $isTumble ? json_decode($LASTSPIN->g, true) : null;
                    // $reels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent'], $lastReels, 0);
                }
                else {
                    $lastReels = $isTumble ? $LASTSPIN : null;
                    $lastMULTIPLIERCollection = [];
                    if ($isTumble && isset($LASTSPIN->rmul)) {
                        $strMultipliers = explode(";", $LASTSPIN->rmul);
                        foreach ($strMultipliers as $strMultiplier) {
                            $details = explode("~", $strMultiplier);
                            $lastMULTIPLIERCollection[$details[1]] = $details[2];
                        }
                    }

                    $reels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent'], $lastReels, $defaultScatterCount, $lastMULTIPLIERCollection);
                }
                
                /* 윈라인 체크 */
                $this->checkWinLines($reels);

                /* 스캐터심볼 체크 */
                $scatterCount = $this->getScatterCount($reels);

                /* 생성된 릴배치표 검사 */
                if ($overtry) {
                    break;
                }
                else if( $try > 1500 ) 
                {
                    $overtry = true;
                    continue;
                }
                else if ( $try >= 1000 ) 
                {
                    /* 텀블스핀경우 심볼이 떨어지면서 당첨될수밖에 없는경우 */
                    // if ($winType == 'none') {
                    //     $winType = 'win';
                    //     $_winAvaliableMoney = $bet * $lines * 100;
                    // }

                    $winType = 'none';
                }

                /* 보너스당첨이 아닐때 스캐터심볼이 있을경우 스킵 */
                if ($winType != 'bonus') {
                    if ($slotEvent['slotEvent'] == 'freespin' && $scatterCount >= 3) {
                        continue;
                    } 
                    else if ($slotEvent['slotEvent'] != 'freespin' && $scatterCount >= 4) {
                        continue;
                    }
                }

                if ($winType == 'none') {
                    if (count($this->winLines) == 0) {
                        break;
                    }
                }
                else if ($winType == 'win' && count($this->winLines) > 0) {
                    /* 스핀 당첨금 */
                    $winMoney = array_reduce($this->winLines, function($carry, $winLine) {
                        $carry += $winLine['Money']; 
                        return $carry;
                    }, 0) * $bet;

                    /* 지난 텀블당첨금 */
                    $lastTumbleWin = $isTumble ? ($LASTSPIN->tmb_win ?? 0) : 0;
                    
                    /* 텀블당첨금이 한도이상일때 스킵 */
                    $multiplier = $this->getTumbleMultiplier($reels);
                    if (($lastTumbleWin + $winMoney) * $multiplier >= $_winAvaliableMoney) {
                        continue;
                    }

                    break;
                }
                else if ($winType == 'bonus') {
                    if ($slotEvent['slotEvent'] == 'freespin' && $scatterCount == 3) {
                        break;
                    } 
                    else if ($slotEvent['slotEvent'] != 'freespin' && $scatterCount >= 4) {
                        break;
                    }
                }
                else {

                }
            }

            $objRes = [
                'tw' => 0,
                'balance' => $BALANCE,
                'acci' => '0',
                'accm' => 'cp',
                'index' => $slotEvent['index'],
                'balance_cash' => $BALANCE,
                'balance_bonus' => '0',
                'reel_set' => $reels['id'],
                'na' => 's',
                'accv' => '0',
                'bl' => $slotEvent['bl'],
                'stime' => floor(microtime(true) * 1000),
                'sa' => implode(",", $reels['symbolsAfter']),
                'sb' => implode(",", $reels['symbolsBefore']),
                'sh' => '5',
                'c' => $bet,
                'sver' => '5',
                'counter' => ((int)$slotEvent['counter'] + 1),
                'l' => $lines,
                's' => implode(",", $reels['flattenSymbols']),
                'w' => $winMoney,
            ];

            /* 멀티플라이어 셋팅 */
            $multiplierSymbols = $reels['multiplierSymbols'];
            if (count($multiplierSymbols) > 0) {
                $multiplierSum = array_sum($multiplierSymbols);
                $objRes['accv'] = $multiplierSum;

                $strMultipliers = array_map(function($pos, $val) use ($S_MULTIPLIER) {
                    return "${S_MULTIPLIER}~${pos}~${val}";
                }, array_keys($multiplierSymbols), $multiplierSymbols);

                $objRes['rmul'] = implode(";", $strMultipliers);
            }

            /* 최소당첨릴생성인 경우 당첨금 계산 */
            // if ($overtry) {
            //     /* 스핀 당첨금 */
            //     $winMoney = array_reduce($this->winLines, function($carry, $winLine) {
            //         $carry += $winLine['Money']; 
            //         return $carry;
            //     }, 0) * $bet;

            //     /* 프리스핀시 멀티플라이어 적용 */
            //     if ($slotEvent['slotEvent'] == 'freespin') {
            //         $winMoney = $winMoney /* * $tumbleMultiplier */;
            //     }
            // }

            /* 텀블당첨금 */
            $lastTumbleWin = $isTumble ? ($LASTSPIN->tmb_win ?? 0) : 0;

            /* 텀블 멀티플라이어 체크 */
            $tumbleMultiplier = $this->getTumbleMultiplier($reels);

            $tumbleSymbols = [];
            if ($winMoney > 0) {
                /* 윈라인 구성 */
                foreach ($this->winLines as $idx => $winLine) {
                    $payLineId = 0;

                    /* 프리스핀시 멀티플라이어 적용 */
                    if ($slotEvent['slotEvent'] == 'freespin') {
                        $winLineMoney = $winLine['Money'] * $bet /* * $tumbleMultiplier */;
                    }
                    else {
                        $winLineMoney = $winLine['Money'] * $bet;
                    }
                    
                    /* 윈라인 추가 */
                    $strLineSymbolPositions = implode("~", $winLine['Positions']);
                    $objRes["l${idx}"] = "${payLineId}~${winLineMoney}~${strLineSymbolPositions}";

                    /* 텀블심볼 조사 */
                    $baseSymbol = $winLine['FirstSymbol'];
                    $lineTumbles = array_map(function($pos) use ($baseSymbol) { return "${pos},${baseSymbol}"; }, $winLine['Positions']);
                    $tumbleSymbols = array_merge($tumbleSymbols, $lineTumbles);
                }

                /* 텀블심볼 추가 */
                $objRes['tmb'] = implode("~", $tumbleSymbols);

                /* 텀블스핀중이면  */
                if ($isTumble) {
                    $BALANCE = $LASTSPIN->balance;
                    $tumbleCount = $LASTSPIN->rs_p + 1;

                    /* 텀블당첨금 누적 */
                    $tumbleWin = $lastTumbleWin + $winMoney;
                }
                /* 텀블스핀 시작 */
                else {
                    $tumbleCount = 0;
                    $tumbleWin = $winMoney;
                }
                
                /* 텀블스핀이면 */
                $objRes['balance'] = $BALANCE;
                $objRes['balance_cash'] = $objRes['balance'];

                $objRes['rs_p'] = $tumbleCount;
                $objRes['rs'] = 'mc';
                $objRes['tw'] = $tumbleWin;             // 프리스핀때 시작당첨금 계산해야됨
                $objRes['tmb_win'] = $tumbleWin;
                $objRes['w'] = $winMoney;
                $objRes['rs_c'] = 1;
                $objRes['rs_m'] = 1;
            }
            else {
                /* 텀블스핀 완료 */
                if ($isTumble) {
                    $objRes['balance'] = $LASTSPIN->balance;
                    $objRes['balance_cash'] = $objRes['balance'];

                    $objRes['na'] = 'c';
                    $objRes['rs_t'] = $LASTSPIN->rs_p + 1;

                    $objRes['tmb_res'] = $lastTumbleWin * $tumbleMultiplier;
                    $objRes['tmb_win'] = $lastTumbleWin;

                    /* 일반스핀에서 총당첨금은 텀블당첨금과 같음 */
                    $objRes['tw'] = $lastTumbleWin * $tumbleMultiplier;
                }
            }

            /* 프리스핀 구매, 보너스당첨 */
            if ($slotEvent['slotEvent'] === 'buy_freespin' || ($winType == 'bonus' && $slotEvent['slotEvent'] !== 'freespin')) { 
                /* 스캐터심볼 윈라인 당첨금 */
                $LINESYMBOLCOUNT = 30;
                $scattersWinMoney = $this->slotSettings->PayTable[$S_SCATTER][$LINESYMBOLCOUNT - $scatterCount];
                $scattersWinMoney = $scattersWinMoney * $bet;

                $objRes['tw'] = $scattersWinMoney;
                $objRes['w'] = $scattersWinMoney;

                $objRes['fsmul'] = 1;
                $objRes['fs'] = 1;
                $objRes['fsmax'] = 15;
                $objRes['fswin'] = 0;
                $objRes['fsres'] = 0;

                $strScatters = $this->stringfyScatterSymbols($reels, $bet);
                $objRes['psym'] = $strScatters;

                /* 프리스핀 구매 */
                if ($slotEvent['slotEvent'] === 'buy_freespin') {
                    $objRes['purtr'] = 1;
                    $objRes['puri'] = 0;
                }

                /* 프리스핀변수 리셋 */
                $slotSettings->SetGameData($slotSettings->slotId . 'FSMax', $objRes['fsmax']);
                $slotSettings->SetGameData($slotSettings->slotId . 'FSNext', 1);
                $slotSettings->SetGameData($slotSettings->slotId . 'FSStartBalance', $BALANCE);

                /* 밸런스 업데이트 */
                $slotSettings->SetBalance($scattersWinMoney);
                $slotSettings->SetBank($slotEvent['slotEvent'] ?? '', -1 * $scattersWinMoney);
            }
            /* 프리스핀 중 */
            else if ($slotEvent['slotEvent'] === 'freespin') {
                /* 프리스핀 시작밸런스 로드 */
                $BALANCE = $slotSettings->GetGameData($slotSettings->slotId . 'FSStartBalance');

                /* 프리스핀 멀티플라이어 합계 */
                $fsMultiplier = $slotSettings->GetGameData($slotSettings->slotId . 'FSMultiplier') ?? 0;

                /* 프리스핀 응답 빌드 */
                $objRes['tw'] = ($LASTSPIN->tw ?? 0) + $winMoney;
                $objRes['balance'] = $BALANCE;
                $objRes['balance_cash'] = $BALANCE;
                $objRes['accv'] = $fsMultiplier + $tumbleMultiplier;

                /* 프리스핀 구매 */
                if (isset($LASTSPIN->puri)) {
                    $objRes['puri'] = 0;
                }

                /* 프리스핀 추가 */
                if ($this->getScatterCount($reels) >= 3) {
                    $fsmax += 5;

                    $slotSettings->SetGameData($slotSettings->slotId . 'FSMax', $fsmax);
                }

                if ($fsmax > $fs) {
                    /* 프리스핀중에는 스핀타입이 항상 s */
                    $objRes['na'] = 's';
                    
                    $objRes['fsmul'] = 1;
                    $objRes['fsmax'] = $fsmax;

                    $objRes['fs'] = $isTumble ? $fs : $fs + 1;      // 텀블스핀이면 프리스핀 소모없음
    
                    /* 프리스핀 당첨금, 텀블스핀이면 이전값 유지, 텀블완료이면 텀블당첨금 추가 */
                    $objRes['fswin'] = $LASTSPIN->fswin;
                    $objRes['fsres'] = $LASTSPIN->fsres;

                    /* 텀블스핀 완료 */
                    if ($isTumble && $winMoney == 0) {
                        $objRes['tw'] = $LASTSPIN->tw + $lastTumbleWin * ($fsMultiplier + $tumbleMultiplier - 1);
                        $objRes['w'] = $lastTumbleWin * ($fsMultiplier + $tumbleMultiplier - 1);

                        $objRes['fswin'] = $LASTSPIN->fswin + $lastTumbleWin * ($fsMultiplier + $tumbleMultiplier);
                        $objRes['fsres'] = $LASTSPIN->fsres + $lastTumbleWin * ($fsMultiplier + $tumbleMultiplier);

                        if ($tumbleMultiplier > 1) {
                            $objRes['apt'] = 'tumbling_win_mul';
                            $objRes['apv'] = $fsMultiplier + $tumbleMultiplier;
                            $objRes['apwa'] = $objRes['w'];     

                            $slotSettings->SetGameData($slotSettings->slotId . 'FSMultiplier', $objRes['apv']);
                        }
                    }

                    /* 프리스핀 카운터 */
                    $slotSettings->SetGameData($slotSettings->slotId . 'FSNext', $objRes['fs']);
                }
                else if ($fsmax <= $fs) {
                    /* 프리스핀 완료 */
                    $objRes['na'] = 's';       

                    $objRes['fs_total'] = $fsmax;
                    $objRes['fsmul_total'] = 1;
                    $objRes['fsend_total'] = 1;
                    $objRes['fswin_total'] = $LASTSPIN->fswin ?? $LASTSPIN->fswin_total;
                    $objRes['fsres_total'] = $LASTSPIN->fsres ?? $LASTSPIN->fsres_total;

                    /* 당첨이 없거나 텀블스핀이 완료되면 프리스핀 종료 */
                    if ($winMoney == 0) {
                        $objRes['tw'] = $LASTSPIN->tw + $lastTumbleWin * ($fsMultiplier + $tumbleMultiplier - 1);
                        $objRes['w'] = $lastTumbleWin * ($fsMultiplier + $tumbleMultiplier - 1);

                        $objRes['na'] = 'c';
                        $objRes['fswin_total'] = $objRes['fswin_total'] + $lastTumbleWin * ($fsMultiplier + $tumbleMultiplier);
                        $objRes['fsres_total'] = $objRes['fsres_total'] + $lastTumbleWin * ($fsMultiplier + $tumbleMultiplier);

                        /* 프리스핀 리셋 */
                        $slotSettings->SetGameData($slotSettings->slotId . 'FSMax', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FSNext', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FSMultiplier', 0);
                    }
                }
                else {
                    /* throw exception */
                }
            }

            /* 텀블스핀완료후 밸런스 업데이트 */
            if( $isTumble && $winMoney == 0) 
            {
                if ($slotEvent['slotEvent'] === 'freespin') {
                    $win = $lastTumbleWin * ($tumbleMultiplier + $fsMultiplier);
                }
                else {
                    $win = $lastTumbleWin * $tumbleMultiplier;
                }

                $slotSettings->SetBalance($win);
                $slotSettings->SetBank($slotEvent['slotEvent'] ?? '', -1 * $win);
            }

            $_GameLog = json_encode($objRes);
            $slotSettings->SaveLogReport($_GameLog, $allBet, $slotEvent['l'], $winMoney, $slotEvent['slotEvent']);
            
            return $objRes;
        }

        public function doCollect($slotEvent) {
            $BALANCE = $this->slotSettings->GetBalance();

            $objRes = [
                'balance' => $BALANCE,
                'index' => $slotEvent['index'],
                'balance_cash' => $BALANCE,
                'balance_bonus' => '0.00',
                'na' => 's',
                'stime' => floor(microtime(true) * 1000),
                'sver' => '5',
                'counter' => ((int)$slotEvent['counter'] + 1),
            ];
            
            return $objRes;
        }

        public function checkWinLines($reels) {
            $REELCOUNT = 6;
            $LINESYMBOLCOUNT = 30;
            
            /* 동의심볼 검출 */
            for ($baseSymbol=3; $baseSymbol <= 11; $baseSymbol++) { 
                $sameSymbols = array_filter($reels['flattenSymbols'], function ($symbol, $pos) use ($baseSymbol) {
                    return $symbol == $baseSymbol;
                }, ARRAY_FILTER_USE_BOTH);    

                /* 당첨조건이 8개이상 */
                $repeatCount = count($sameSymbols);
                if ($repeatCount >= 8) {
                    $linePositions = array_keys($sameSymbols);
                    $lineMoney =  $this->slotSettings->PayTable[$baseSymbol][$LINESYMBOLCOUNT - $repeatCount];
                    
                    array_push($this->winLines, [
                        'FirstSymbol' => $baseSymbol,
                        'RepeatCount' => $repeatCount,
                        'Positions' => $linePositions,
                        'Money' => $lineMoney,
                    ]);
                }
            }
        }

        public function getTumbleMultiplier($reels) {
            if (count($reels['multiplierSymbols']) > 0) {
                $multiplier = array_sum($reels['multiplierSymbols']);
                return $multiplier;
            }

            return 1;
        }

        public function getScatterCount($reels) {
            $S_SCATTER = 1;
            $REELCOUNT = 6;
            $scatterCount = 0;

            for ($reelId=0; $reelId < $REELCOUNT; $reelId++) { 
                $scatterCount += count(array_keys($reels['symbols'][$reelId], $S_SCATTER));
            }

            return $scatterCount;
        }

        public function stringfyScatterSymbols($reels, $bet) {
            $LINESYMBOLCOUNT = 30;
            $S_SCATTER = 1;

            $symbols = array_keys($reels['flattenSymbols'], $S_SCATTER);
            $repeatCount = count($symbols);
            $winMoney = $this->slotSettings->PayTable[$S_SCATTER][$LINESYMBOLCOUNT - $repeatCount] * $bet;

            // '1~300.00~3,7,20,22'
            return "${S_SCATTER}~${winMoney}~" . implode(",", $symbols);
        }

        public function getRandomValue($probabilityMap) {
            $max = array_sum(array_values($probabilityMap));
            $randNum = random_int(1, $max);

            $sum = 0;
            foreach ($probabilityMap as $key => $probability) {
                $sum += $probability;
                if ($randNum <= $sum) {
                    return $key;
                }
            }

            return null;
        }

        public function toResponse($obj) {
            $response = '';
            foreach ($obj as $key => $value) {
                if ($value !== null) {
                    $response = "{$response}&{$key}={$value}";
                }
            }

            /* remove double quotes around key for javascript */
            $response = preg_replace('/"(\w+)":/i', '\1:', $response);
            return trim($response, "&");
        }
    }
}
