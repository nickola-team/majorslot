<?php 
namespace VanguardLTE\Games\PowerofThorMegawaysPM
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
            $WILD = '2';
            $SCATTER = '1';
            $REELCOUNT = 6;
            $BALANCE = $slotSettings->GetBalance();
            $LASTSPIN = $slotSettings->GetHistory();
            // $LASTSPIN = null;

            $slotEvent['slotEvent'] = $slotEvent['action'];

            if( $slotEvent['slotEvent'] == 'doInit' ) 
            { 
                $objRes = $this->doInit($slotEvent, $slotSettings, $LASTSPIN);
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

        public function doInit($slotEvent, $slotSettings, $LASTSPIN) {
            $BALANCE = $slotSettings->GetBalance();

            $objRes = [
                'tw' => '0',
                'def_s' => '19,8,7,10,12,19,10,7,3,9,5,12,11,9,1,11,12,5,11,9,8,5,12,6,11,9,10,5,19,9,11,9,19,19,19,9,19,9,19,19,19,9,19,19,19,19,19,12',
                'balance' => $BALANCE,
                'action' => 'doSpin',
                'nas' => '19',
                'accm' => 'cp~mp',
                'cfgs' => '6816',
                'ver' => '2',
                'acci' => '0',
                'index' => $slotEvent['index'],
                'balance_cash' => $BALANCE,
                'reel_set_size' => '10',
                'balance_bonus' => '0',
                'na' => 's',
                'accv' => '0~2',
                'scatters' => '1~0,0,0,0,0,0~0,0,0,0,0,0~1,1,1,1,1,1',
                'gmb' => '0,0,0',
                'rt' => 'd',
                'gameInfo' => '{rtps:{purchase:"96.97",regular:"96.55"},props:{max_rnd_sim:"1",max_rnd_hr:"867302",max_rnd_win:"5000",gamble_lvl_2:"70.39%",gamble_lvl_3:"75.02%",gamble_lvl_1:"62.81%"}}',
                'wl_i' => 'tbm~5000',
                'stime' => floor(microtime(true) * 1000),
                'sc' => implode(',', $slotSettings->Bet),   /* '10.00,20.00,30.00,40.00,50.00,100.00,200.00,300.00,400.00,500.00,750.00,1000.00,2000.00,3000.00,4000.00,5000.00' */
                'defc' => '100',
                'sh' => '8',
                'wilds' => '2~0,0,0,0,0,0~1,1,1,1,1,1',
                'bonuses' => '0',
                'fsbonus' => '',
                'st' => 'rect',
                'c' => '100',
                'sw' => '6',
                'sver' => '5',
                'g' => '{reg:{def_s:"10,7,3,9,5,12,11,9,1,11,12,5,11,9,8,5,12,6,11,9,10,5,19,9,11,9,19,19,19,9,19,9,19,19,19,9,19,19,19,19,19,12",def_sa:"10,5,5,8,6,4",def_sb:"1,9,10,5,11,12",reel_set:"3",s:"5,10,4,12,6,5,4,12,9,12,6,9,5,19,9,6,8,5,7,19,4,19,19,19,19,19,4,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19",sa:"12,3,11,6,10,11",sb:"9,4,8,6,8,5",sh:"7",st:"rect",sw:"6"},top:{def_s:"12,10,7,8",def_sa:"10",def_sb:"8",reel_set:"2",s:"10,2,8,9",sa:"8",sb:"2",sh:"4",st:"rect",sw:"1"}}',
                'counter' => ((int)$slotEvent['counter'] + 1),
                'l' => '20',
                'paytable' => '0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;400,200,100,20,10,0;100,50,40,10,0,0;50,30,20,10,0,0;40,15,10,5,0,0;30,12,8,4,0,0;25,10,8,4,0,0;20,10,5,3,0,0;18,10,5,3,0,0;16,8,4,2,0,0;12,8,4,2,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0',
                'rtp' => '96.55,96.97',
                'total_bet_max' => $slotSettings->game->rezerv,
                'reel_set0' => '6,2,11,12,9,11,10,5,12,5,9,12,4,9,5,7,4,12,4,17,11,14,12,8,2,7,7,12,11,3,8,12,12,5,5,12,9,10,7,9,5,14,16,17,12,10,2,5,17,9,10,4,11,12,9,14,8,2,4,9,9,2,2,8,9,5,7,9,12,9,10,7,12,12,4,17,12,5,5,3,7,12,10,4,11,10,10,7,3,12,7,5,2,7,6,5,18,3,3,4,8,9,9,9,12,12,9,9,12,10,9,9,12,7,5,7,2,9,5,10,9,5,4,10,9,4,9,17,2,7,12,10,12,5,9,10,9,15,12,7,4,4,8,9,7,9,17,3,16,8,4,9,8,17,2,10,5,3,17,2,10,9,9,4,12,12,10,10,18,8,7,12,10,3,9,7,17,7,11,17,2,4,2,4,17,2,12,7,6,3,14,10,9,4,6,12,18,3,9,7,7,10,2,6,12,3,10,10,10,4,8,9,6,16,12,7,3,2,9,6,11,7,4,5,5,12,11,10,4,5,4,9,9,2,15,12,3,4,2,10,10,12,9,2,5,12,7,3,3,9,4,2,3,5,17,2,4,9,4,7,17,9,8,9,12,7,10,12,15,8,7,10,9,3,15,6,7,2,18,17,3,9,5,17,12,4,2,9,12,9,9,3,5,10,12,10,2,4,17,6,2,10,5,16,12,11,8,6,7,7,4,7',
                's' => '19,9,8,2,10,19,5,10,4,12,6,5,4,12,9,12,6,9,5,19,9,6,8,5,7,19,4,19,19,19,19,19,4,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19',
                'accInit' => '[{id:0,mask:"cp;mp"}]',
                'reel_set2' => '2,2,11,3,10,7,8,12,18,5,2,6,12,12,4,11,10,7,2,17,11,10,2,12,4,2,8,16,15,7,10,11,2,11,5,12,9,10,2,8,11,9,6,6,10,10,10,12,12,14,8,6,11,18,10,2,3,2,4,4,7,12,6,8,11,10,7,10,2,13,10,10,14,11,10,2,8,7,6,5,16,8,11,8,10,11,3,4,12,9,11,9,9,9,9,10,9,9,3,17,11,10,8,4,18,9,7,18,7,7,3,11,3,8,9,8,10,12,10,3,2,15,9,10,10,7,13,10,2,11,7,11,11,5,9,12,8,7,9,9,11',
                't' => '243',
                'reel_set1' => '11,11,4,5,10,1,5,11,10,11,10,9,7,5,1,9,8,6,10,5,5,5,12,11,5,10,5,6,5,4,6,4,11,7,11,4,4,9,5,3,11,3,11,10,10,10,10,1,9,8,9,5,5,10,4,7,10,7,4,11,12,5,10,3,5,5,4,11,11,11,10,12,5,11,6,12,11,1,10,5,12,1,4,5,11,7,4,10,1,1,10,4,4,4,11,4,11,5,10,9,8,7,4,4,11,5,5,10,4,10,5,4,7,11,5,8~12,8,9,12,5,12,11,9,12,4,9,6,10,5,9,7,7,9,9,9,1,12,9,9,8,1,5,12,8,10,7,8,6,10,9,10,7,8,8,8,8,10,7,3,12,12,7,7,12,5,7,12,7,9,8,4,4,11,7,7,7,7,7,1,3,12,12,9,5,7,7,9,12,4,4,9,7,9,9,6,12,12,12,12,11,7,7,5,1,9,4,11,8,12,6,11,9,9,8,6,7,8,7~6,6,10,10,9,8,7,8,9,6,4,4,8,6,1,7,12,5,8,5,10,11,11,4,12,8,7,11,10,10,7,6,9,11,8,1,6,12,8,8,12,7,9,11,8,10,7,4,11,3,5,6,6,6,1,1,9,5,4,12,4,5,7,4,10,6,8,7,3,12,4,6,4,5,9,12,5,10,6,4,6,10,6,12,8,6,6,4,10,4,4,5,11,7,3,12,5,9,4,10,6,7,10,4,6,1,4,12~9,4,12,12,11,5,7,4,10,3,10,3,5,10,5,9,5,11,6,9,5,6,3,10,10,10,5,6,4,1,5,3,10,5,6,9,8,10,5,12,5,9,4,9,8,6,5,5,11,8,12,5,5,5,6,6,4,7,9,10,5,10,5,10,10,11,4,8,6,7,6,10,6,3,6,10,3,3,6,6,6,10,5,5,10,7,9,4,5,1,6,5,7,5,7,5,7,10,5,8,7,9,8,9,9,5,9,9,9,5,6,6,8,1,6,9,12,10,10,12,4,10,8,5,5,7,1,12,3,8,6,5,9,12,5~10,5,11,1,11,10,4,8,11,9,8,6,6,12,6,6,5,3,6,5,7,9,12,7,11,3,8,1,6,10,6,9,11,1,6,5,4,11,12,9,4,9,12,4,11,6,9,11,5,11,6,5,8,7,9,11,4,1,10,6,6,6,6,12,5,11,5,6,12,11,12,8,9,6,11,10,6,8,1,5,7,7,9,5,9,7,12,5,10,6,6,4,11,10,10,5,9,9,7,11,6,1,6,6,12,4,12,9,9,6,1,12,7,11,4,1,5,8,12,3,12,5,6,9~8,4,7,9,5,9,5,9,4,4,10,10,9,10,5,10,12,4,9,12,11,7,11,12,12,8,5,10,1,10,7,9,9,9,4,11,10,4,5,8,3,11,9,12,9,5,9,11,8,4,12,5,5,7,12,1,12,12,11,6,11,7,5,11,9,5,12,12,12,10,10,9,9,6,6,12,11,8,11,4,9,3,1,4,6,7,6,9,9,4,11,12,5,9,1,6,7,4,1,12,9,10',
                'reel_set4' => '10,10,3,12,13,7,12,10,4,3,11,11,8,16,9,12,3,6,2,15,11,8,18,2,11,9,18,12,2,18,3,10,11,18,12,11,7,10,10,18,3,8,9,2,6,17,10,10,9,8,12,12,18,10,18,5,8,11,14,7,10,17,18,8,12,7,4,13,8,7,2,11,10,2,7,5,11,8,10,6,8,9,9,11,7,7,12,5,12,11,9,10,12,12,11,10,7,3,10,10,10,9,11,6,7,8,7,7,14,11,6,11,8,11,10,11,10,12,11,10,10,11,10,12,11,11,4,12,3,2,16,16,2,11,3,11,3,10,5,5,18,10,4,4,3,8,9,10,6,8,18,9,8,2,8,7,8,9,9,13,12,7,9,10,4,7,11,8,11,9,18,11,5,8,16,8,10,6,14,4,3,7,11,3,8,5,10,13,11,10,10,12,4,11,6,4,18,10,7,2,11,9,9,9,11,2,11,8,15,10,18,10,11,2,9,15,4,11,12,5,6,11,9,10,10,9,4,17,7,18,11,14,2,11,8,7,9,7,9,9,12,12,10,3,10,18,5,7,2,9,8,17,11,8,10,2,12,3,7,4,10,11,7,18,11,9,8,8,7,11,11,15,3,11,5,8,9,10,7,10,7,7,6,2,11,7,9,9,10,11,7,3,12,10,10,11,8,9,10,7,9,7,10,4,9',
                'purInit' => '[{type:"fsbl",bet:2000,bet_level:0}]',
                'w' => '0',
                'reel_set3' => '10,8,9,9,8,5,12,12,4,12,5,7,8,12,8,5,5,11,9,9,9,9,12,8,5,5,12,12,5,12,9,4,5,9,12,12,8,9,9,8,5,5,5,5,8,12,10,9,8,10,3,7,8,6,5,9,12,8,6,5,8,4,8,8,8,10,9,9,3,10,5,8,5,12,6,7,12,12,8,9,5,7,8,6,12,12,12,9,9,11,7,8,9,7,8,12,12,7,12,11,8,8,9,12,8,5,5,4~9,10,6,10,11,11,6,7,6,8,7,10,10,10,11,4,11,12,7,4,10,7,7,3,4,11,10,4,4,4,9,5,11,5,10,6,12,7,7,8,8,4,7,7,7,7,7,9,12,5,3,7,10,10,8,11,9,11,12,11,11,11,10,11,10,11,7,7,4,10,11,7,10,8,7,11~10,7,6,11,9,5,6,9,12,11,11,9,11,6,4,12,4,8,7,9,12,5,5,4,7,6,4,4,10,8,6,10,12,8,6,6,6,6,6,8,6,9,4,7,9,8,5,9,6,8,11,11,8,8,4,8,9,6,12,7,9,11,4,3,7,6,6,8,10,4,12,12,8~4,12,9,4,9,3,10,3,9,10,10,10,6,12,9,12,11,3,8,12,10,9,12,12,12,12,6,8,7,3,12,12,4,6,7,9,9,9,6,10,9,12,6,11,10,7,5,6,8,12~12,6,10,10,8,3,6,6,12,9,5,11,12,7,11,8,6,4,6,10,5,5,6,10,12,7,12,10,11,8,12,8,10,10,5,11,12,8,3,3,5,6,5,10,9,9,4,12,10,6,12,5,9,8,9,5,7,8,9,6,5,8,6,10,6,11,5,11,7,7,12,10,10,8,10,6,5,12,5,4,7,8,6,4,5,6,12,10,11,8,6,6,5,12,4,8,11,8,5,10,6,11,6,4,12,6,5,11,10,4,10,5,3,6,5,6,6,5,11,11,4,5,11,11,9,9,3,12,6,12,11,11,5,10,6,7,12,10,6,11,11,9,7,12,9,9,12,6,6,5,6,11~4,11,8,8,5,7,9,10,10,10,10,9,8,12,6,3,12,7,9,10,11,11,11,12,10,10,11,5,11,6,11,5,4',
                'reel_set6' => '12,12,8,10,5,1,12,11,4,8,15,16,10,3,12,7,8,12,9,8,9,3,11,11,10,5,14,9,2,3,7,3,5,10,10,17,7,11,8,12,2,11,10,12,12,12,3,10,7,10,10,11,3,4,9,2,12,10,11,10,7,11,12,7,9,11,7,10,12,11,11,10,8,7,9,12,11,7,5,11,12,14,7,13,10,9,2,13,3,8,5,11,11,11,10,13,3,12,6,10,12,7,4,11,8,12,6,4,11,7,9,12,5,9,12,11,13,9,9,7,9,6,10,2,8,12,7,10,8,8,10,12,9,10,11,2,11,12,1,1,1,1,15,16,8,12,11,17,4,11,9,8,11,7,9,12,3,13,13,11,12,11,10,8,10,12,11,9,6,6,8,11,10,11,7,2,7,7,6,12,11,8,2,3,10,10,4',
                'reel_set5' => '4,12,9,5,10,6,8,6,8,6,6,12,11,8,11,12,5,9,9,9,9,8,8,10,12,9,7,10,9,8,12,8,8,9,12,12,7,9,8,12,8,12,12,12,12,12,8,4,12,8,6,4,10,11,9,12,9,8,7,6,12,8,12,6,8,8,8,3,6,4,11,8,9,8,8,9,6,12,6,5,7,9,10,9,11,6,5,8~7,12,12,4,9,8,9,12,12,10,9,8,8,11,9,4,8,8,10,9,12,6,8,10,12,5,10,7,8,5,11,11,12,12,6,3,6,9,8,12,8,3,6,8,3,9,12,5,6,12,8,7,8,9,9,6,9,8~8,7,5,4,12,12,5,10,7,12,10,11,6,11,11,10,10,12,5,12,12,8,10,6,7,9,4,12,10,4,11,7,9,10,10,8,12,10,11,9,11,6,10,10,3,9,6,11,4,9,11,8,10,9,12,7,7,7,10,9,10,12,11,5,8,9,3,8,7,7,9,7,6,8,9,9,8,4,9,8,6,11,7,7,8,9,4,10,4,7,5,7,12,4,7,8,10,5,12,6,4,7,6,9,6,7,11,8,10,11,9,7,9,10,4,6~11,8,7,9,7,5,3,9,5,3,12,9,8,4,8,9,12,3,4,12,8,7,7,6,9,11,12,9,12,3,12,9,7,9,7,12,3,12,9,5,5,5,9,10,4,12,9,8,12,4,7,5,12,7,12,7,10,7,5,8,12,11,12,12,8,5,12,3,10,12,11,4,9,3,12,12,9,12,9,7,5,12,12,12,12,12,11,7,11,8,7,7,12,10,8,7,9,7,9,12,3,5,7,7,9,12,4,5,10,12,4,10,7,7,10,7,9,12,12,9,7,3,12,3,4,9,9,9,5,8,5,8,3,3,12,12,10,9,5,3,3,12,5,9,12,11,11,9,3,12,7,7,5,4,8,5,12,11,12,10,7,6,9,10,5,9,4,12,9,12~11,4,6,6,11,9,7,6,7,6,9,5,4,10,10,5,7,8,5,11,12,12,8,12,3,7~11,7,11,6,11,9,6,10,5,11,7,5,11,10,8,11,5,10,9,5,10,6,10,9,12,10,11,6,6,5,8,9,11,3,12,5,4,10,5,9,8,11,5,4,9,10,8,12,11,11,12,11,9,6,11,5,5,5,12,6,5,12,4,9,9,10,10,11,10,11,4,5,6,8,8,5,6,3,11,7,10,11,10,11,5,9,8,5,9,9,6,11,10,12,11,7,5,3,4,6,10,11,8,10,6,8,7,5,9,5,11,5,6,9,11,11,11,8,5,6,9,9,10,12,7,5,8,12,12,4,5,12,9,12,5,8,5,7,4,6,7,7,9,6,12,5,11,10,12,4,10,12,6,10,5,12,5,12,11,4,7,11,11,3,12,5,12,12,10,7,6,12,5,5,6',
                'reel_set8' => '10,4,3,10,11,12,11,13,7,8,11,12,3,9,9,10,10,11,12,12,10,16,13,3,3,7,12,15,13,10,6,10,16,3,11,7,3,3,2,3,9,12,10,1,9,7,15,12,12,12,12,12,6,4,13,7,5,6,13,11,11,8,7,11,13,15,12,7,4,11,9,14,11,10,15,8,2,12,10,15,10,13,11,16,2,7,4,7,9,4,4,11,2,9,10,8,9,15,11,12,11,12,8,11,11,11,8,5,9,9,6,11,5,8,9,7,6,16,5,2,8,9,11,9,10,14,2,8,12,10,12,7,1,10,10,7,10,7,11,13,14,17,12,12,10,11,13,8,14,7,10,16,7,17,12,10,3,1,1,1,10,5,5,11,7,11,11,8,17,11,8,11,13,17,13,14,12,2,10,9,11,13,3,13,17,7,11,10,5,14,16,8,14,12,16,1,17,9,8,17,4,9,10,11,8,3,9,15,10,2,2,11',
                'reel_set7' => '8,9,8,10,11,10,8,12,5,9,4,12,12,6,10,9,9,10,12,10,9,6,9,6,7,6,9,8,11,12,8,11,8,9,6,8,12,8,8,6,12,9,9,9,3,8,9,6,11,4,8,5,4,6,8,9,12,8,3,12,8,11,12,12,5,9,8,12,9,7,6,6,9,6,10,12,12,8,9,7,9,6,8,6,11,8,12,12,12,12,9,8,9,11,8,12,9,12,12,8,11,8,6,9,4,8,7,12,10,12,10,12,12,8,12,10,5,6,4,5,4,6,7,9,4,8,8,6,6,12,12,8,8,8,9,12,9,8,12,8,8,5,8,8,6,8,9,12,11,8,6,7,7,9,12,8,8,12,8,11,5,6,12,8,10,6,6,8,12,12,11,10,6,5,9,9,5~5,9,10,8,8,6,12,6,8,8,10,5,12,4,9,11,12,8,12,12,5,8,9,9,11,9,6,8,6,9,12,8,7,7,8,8,3,9,9,4,10,3,11,12,12,10~4,10,9,6,8,8,9,9,6,6,10,10,7,9,7,7,12,11,8,11,11,12,9,10,10,4,7,10,8,3,9,7,5,12,7,8,11,9,11,11,9,5,4,10,10,7,11,10,12,11,10,8,9,10,8,6,10,4,5,7,9,9,4,11,8,4,11,5,4,7,7,7,8,11,6,9,9,4,4,12,6,12,5,9,7,9,3,8,12,10,12,9,6,9,6,6,7,9,7,10,7,7,4,6,12,5,12,9,11,10,10,12,7,10,12,7,5,10,12,8,6,7,11,11,4,8,8,12,7,11,9,10,12,5,8,10,4,9,10,8,6,7,10,7~4,4,12,5,7,5,11,7,9,3,7,12,7,12,12,9,5,10,12,12,7,7,6,8,9,7,5,9,9,12,12,3,7,7,3,11,12,4,11,6,7,5,5,5,7,4,10,12,3,9,4,4,11,12,12,9,8,12,9,9,12,3,12,10,8,12,7,7,12,3,11,7,9,8,9,3,9,7,12,10,9,10,7,4,9,5,12,12,12,12,10,12,8,3,12,9,10,12,4,7,3,7,12,12,9,5,5,11,5,7,3,9,12,9,10,7,7,5,9,5,3,3,12,9,8,12,12,7,3,5,12,9,9,9,8,7,12,3,11,4,7,12,5,12,9,12,7,8,3,3,5,11,5,12,12,9,8,9,8,10,10,12,7,9,12,10,5,8,9,9,4,8,11,12,4,12,5~6,5,7,8,9,12,11,12,5,6,7,11,11,6,5,6,7,8,7,9,5,10,11,5,3,6,6,11,10,8,9,12,8,4,6,7,6,3,11,8,7,7,9,7,7,10,11,4,5,9,5,12,11,5,12,7,11,12,10,10,11,6,4,12,6,7,11,10,5,9,7,7,5,5,7,10,12,7,12,7,5,12,5,4,4,11,11,12,9,6,10,11,7,8,8,5,12,8,7,12,12,11,7,12,3,12,5,4,8,7,11,6,5,6,6,11,6,3,12,12,9,5,3,7,5,9,6,3,11,6,9,7,7,12,8,4,7,10,9,6,6,7,4,6,8,11,5,7,5,8,5,5,7,7,11,7,6,12,6,12,8,8,6,6,5,4,9,11~12,9,11,8,5,7,5,5,6,9,12,4,11,10,6,9,7,6,10,10,6,11,10,8,4,9,9,6,10,10,7,12,9,11,9,5,10,9,11,5,12,5,4,5,11,8,5,10,12,8,9,7,7,11,4,5,5,5,11,11,12,12,5,11,11,3,6,8,12,5,10,5,10,7,5,6,12,11,11,6,7,12,10,8,8,12,4,11,5,10,5,11,11,12,5,10,3,9,12,6,6,11,8,6,5,3,11,5,11,12,6,5,11,10,11,11,11,7,7,6,5,5,6,5,8,10,5,8,12,12,11,11,10,10,12,9,4,9,6,8,11,5,5,6,9,7,10,12,11,4,5,9,5,6,3,4,11,6,5,10,11,4,9,12,5,10,8,7,12,12,9,10,6,9',
                'reel_set9' => '8,9,5,12,5,12,7,9,7,9,9,9,9,5,11,9,7,12,7,5,7,8,6,12,12,12,12,9,7,12,4,11,12,5,5,3,6,7,7,7,7,10,10,5,4,10,12,7,9,9,12,7~12,7,5,9,7,8,6,3,9,5,8,12,10,12,10,3,7,7,6,12,9,7,7,9,9,5,9,7,7,12,11,7,12,12,6,12,12,5,7,9,12,12,7,10,9,12,6,3,7,9,11,7,8,6,7,9,3,7,5,10,5,12,11,4,12,9,11,12,5,9,7,7,12,9,4,10,8,7,11,10,7,10,9,4,9,5,7~10,8,8,12,9,11,5,4,10,10,6,10,11,7,10,8,4,8,9,10,5,4,8,11,8,9,11,9,7,6,11,8,8,8,8,12,7,12,9,4,8,5,12,9,11,9,4,11,9,10,10,7,4,12,10,8,6,8,12,9,4,4,6,3,5,10,8,7~9,9,10,11,4,8,6,3,12,8,9,7,10,8,10,12,12,8,8,4,12,12,6,11,4,8,4,12,9,11,4,6,8,4,12,12,10,6,6,6,8,9,12,12,3,12,8,8,11,8,9,3,6,8,10,3,3,12,8,9,12,7,6,3,8,9,8,4,9,7,12,9,12,10,3,9,12,3,12,12,12,8,7,7,12,9,12,3,8,3,12,9,7,7,12,8,7,3,9,6,11,12,12,6,12,4,8,9,12,12,3,9,9,10,10,6,10,12,9,9,9,9,12,8,6,5,7,3,8,6,9,6,11,12,9,5,8,12,3,7,12,12,9,12,12,7,9,6,9,6,12,6,8,9,11,4,8,12,12,11,10,4~7,10,11,5,9,6,8,5,12,3,8,8,4,12,7,11,6~11,4,7,11,12,3,5,6,12,6,6,6,12,8,11,6,4,12,10,9,10,6,11,11,11,6,7,5,9,8,11,10,10,9,6,5',
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
                $objRes['g'] = $LASTSPIN->g ?? $objRes['g'];
                $objRes['na'] = $LASTSPIN->na ?? $objRes['na'];

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

                /* 갬블 */
                $objRes['bgid'] = $LASTSPIN->bgid ?? null;
                $objRes['wins'] = $LASTSPIN->wins ?? null;
                $objRes['coef'] = $LASTSPIN->coef ?? null;
                $objRes['level'] = $LASTSPIN->level ?? null;
                $objRes['status'] = $LASTSPIN->status ?? null;
                $objRes['bgt'] = $LASTSPIN->bgt ?? null;
                $objRes['lifes'] = $LASTSPIN->lifes ?? null;
                $objRes['bw'] = $LASTSPIN->bw ?? null;
                $objRes['wins_mask'] = $LASTSPIN->wins_mask ?? null;
                $objRes['wp'] = $LASTSPIN->wp ?? null;
                $objRes['end'] = $LASTSPIN->end ?? null;

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
            
            $lines = $slotEvent['l'];       // 라인
            $bet = $slotEvent['c'];         // 베팅액

            /* 텀블스핀 */
            /* 프리스핀 멀티플라이어 */
            $isTumble = false;

            $tumbleMultiplier = $LASTSPIN->wmv ?? 1;
            if (isset($LASTSPIN->rs)) {
                $isTumble = true;

                /* 텀블스핀이면 멀티플라이어 1증가, 아니면 유지 */
                $tumbleMultiplier = $isTumble ? $tumbleMultiplier + 1 : $tumbleMultiplier;
            }
            
            /* 텀블보너스 당첨인 경우 */
            $isTumbleBonus = $slotSettings->GetGameData($slotSettings->slotId . 'TumbleBonus');

            /* 프리스핀 구매 */
            if (isset($slotEvent['pur'])) {
                $slotEvent['slotEvent'] = 'doGamble';

                $winType = 'bonus';                   // 보상방식
                $_winAvaliableMoney = 0;        // 당첨금 한도
            }
            else if ($isTumbleBonus) {
                $winType = 'bonus';                   // 보상방식
                $_winAvaliableMoney = 0;        // 당첨금 한도
            }
            else {
                /* 남은 프리스핀이 있을 경우 */
                $fsmax = $slotSettings->GetGameData($slotSettings->slotId . 'FSMax');
                $fs = $slotSettings->GetGameData($slotSettings->slotId . 'FSNext');

                /* 이전 프리스핀검사, 새 스핀결과 결정 */
                if ($LASTSPIN !== NULL && $fsmax > 0) {
                    $slotEvent['slotEvent'] = 'freespin';
                }

                /* 스핀결과 결정 */
                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $bet * $lines, $lines);

                $winType = $_spinSettings[0];                   // 보상방식
                $_winAvaliableMoney = $_spinSettings[1];        // 당첨금 한도
            }

             /* Balance 업데이트 */
             if ($slotEvent['slotEvent'] === 'doGamble') {
                $allBet = $bet * $lines * 100;
                $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);

                /* 프리스핀 구매금액은 bonus에 충전 */
                $bankMoney = $allBet / 100 * $slotSettings->GetPercent();
                $slotSettings->SetBank(($slotEvent['slotEvent'] ?? ''), $bankMoney, 0);
            }
            else if ($slotEvent['slotEvent'] === 'freespin' || $isTumble) {
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
            $defaultScatterCount = 0;
            if($winType == 'bonus'){      
                if ($slotEvent['slotEvent'] == 'freespin') {
                    /* 프리스핀 텀블스핀일 경우 보너스당첨은 없다 */
                    if ($isTumble) {
                        $winType = 'none';
                    }
                    else {
                        /* 프리스핀중 보너스당첨은 심볼갯수 3개일때 */
                        $defaultScatterCount = 3;
                    }
                }
                else {
                    if ($isTumbleBonus) {
                        $tumbleBonusStepCount = $slotSettings->GetGameData($slotSettings->slotId . 'TumbleBonusStepCount');
                        $curTumbleBonusStep = $slotSettings->GetGameData($slotSettings->slotId . 'TumbleBonusCurrentStep');
                        $totalScatterCount = $slotSettings->GetGameData($slotSettings->slotId . 'TumbleBonusScatterCount');
                        $defaultScatterCount = $slotSettings->GetGameData($slotSettings->slotId . 'TumbleBonusCurrentScatterCount');

                        /* 텀블 마지막 스핀이면 */
                        $curTumbleBonusStep += 1;
                        if ($curTumbleBonusStep >= $tumbleBonusStepCount) {
                            $defaultScatterCount = $totalScatterCount;
                        }
                        else if ($defaultScatterCount < $totalScatterCount) {
                            $defaultScatterCount += random_int(0, 1);
                        }

                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbleBonusCurrentStep', $curTumbleBonusStep);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbleBonusCurrentScatterCount', $defaultScatterCount);
                    }
                    else {
                        $isTumbleBonus = true;

                        // 생성되어야할 Scatter갯수 결정
                        $totalScatterCount = $slotSettings->GenerateScatterCount();  

                        /* 텀블스텝 결정 */
                        $tumbleBonusStepCount = random_int(1, 3);

                        /* 스텝이 1이라면 텀블보너스가 아님 */
                        /* 처음 생성할 스캐터심볼 갯수 랜덤결정, 최종갯수와 2이상 차이나지 않도록 */
                        $defaultScatterCount = ($tumbleBonusStepCount == 1) ? $totalScatterCount : random_int($totalScatterCount - 2, $totalScatterCount);
                        
                        $curTumbleBonusStep = 1;
                        
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbleBonus', true);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbleBonusStepCount', $tumbleBonusStepCount);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbleBonusCurrentStep', $curTumbleBonusStep);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbleBonusScatterCount', $totalScatterCount);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbleBonusCurrentScatterCount', $defaultScatterCount);
                    }
                }
            }

            /* 릴배치표 생성, 2천번 시행 */
            /*************************************************** */
            $overtry = false;           // 1500번이상 시행했을때 true

            for ($try=0; $try < 2000; $try++) { 
                $this->winLines = [];
                $winMoney = 0;

                /* 릴배치표 생성 */
                if ($overtry) {
                    /* 더이상 자동릴생성은 하지 않고 최소당첨릴을 수동생성 */
                    $lastReels = $isTumble ? json_decode($LASTSPIN->g, true) : null;
                    $reels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent'], $lastReels, 0);
                }
                else {
                    $lastReels = $isTumble ? json_decode($LASTSPIN->g, true) : null;
                    $reels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent'], $lastReels, $defaultScatterCount);
                }

                /* 윈라인 검사 */
                $this->checkWinLines($reels);

                /* 스캐터심볼 검사 */
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
                    if ($winType == 'win') {
                        $winType = 'none';
                    }
                }

                $scatterCount = $this->getScatterCount($reels);

                if ($winType == 'none') {
                    if ($slotEvent['slotEvent'] == 'freespin' && $scatterCount >= 3) {
                        continue;
                    } 
                    else if ($slotEvent['slotEvent'] != 'freespin' && $scatterCount >= 4) {
                        continue;
                    }

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

                    /* 프리스핀시 멀티플라이어 적용 */
                    if ($slotEvent['slotEvent'] == 'freespin') {
                        $winMoney = $winMoney * $tumbleMultiplier;
                    }

                    /* 당첨금이 한도이상일때 스킵 */
                    if ($winMoney >= $_winAvaliableMoney) {
                        continue;
                    }

                    break;
                }
                else if ($winType == 'bonus') {
                    if ($isTumbleBonus) {
                        /* 보너스텀블 완료시점에서 윈라인이 없어야 한다 */
                        if ($curTumbleBonusStep >= $tumbleBonusStepCount) {
                            if (count($this->winLines) == 0 && $scatterCount >= 4) {
                                break;
                            }
                        }
                        else {
                            /* 텀블보너스인경우 윈라인이 반드시 있어야 */
                            if (count($this->winLines) > 0) {
                                /* reg 텀블심볼갯수가 최소한 2개가 되어야, 스캐터갯수보다 작으면 오류  */
                                if ($this->getTumbleSymbolCount($reels) < 2) {
                                    continue;
                                }

                                /* 스핀 당첨금 */
                                $winMoney = array_reduce($this->winLines, function($carry, $winLine) {
                                    $carry += $winLine['Money']; 
                                    return $carry;
                                }, 0) * $bet;

                                break;
                            }
                        }
                    }
                    else {
                        /* 텀블보너스가 아닌 경우 윈라인이 없을때만 갬블발동 */
                        if (count($this->winLines) > 0) {
                            continue;
                        }

                        if ($slotEvent['slotEvent'] == 'freespin' && $scatterCount == 3) {
                            break;
                        } 
                        if ($slotEvent['slotEvent'] != 'freespin' && $scatterCount >= 4) {
                            break;
                        }
                    }
                }
            }

            $reels_for_res = $this->buildReelSetResponse($reels);
            $str_reels = $this->stringifyReels($reels_for_res);

            $objRes = [
                'tw' => 0,
                'balance' => $BALANCE,
                'acci' => '0',
                'accm' => 'cp~mp',
                'index' => $slotEvent['index'],
                'balance_cash' => $BALANCE,
                'balance_bonus' => '0',
                'na' => 's',
                'accv' => '0~2',
                'stime' => floor(microtime(true) * 1000),
                'sh' => '8',
                'st' => 'rect',
                'c' => $bet,
                'sw' => '6',
                'sver' => '5',
                'g' => json_encode($reels_for_res),
                'counter' => ((int)$slotEvent['counter'] + 1),
                'l' => $lines,
                's' => $str_reels,
                'w' => $winMoney,
            ];

            /* 텀블당첨금 */
            $tumbleWin = $slotSettings->GetGameData($slotSettings->slotId . 'TumbleTotalWin');

            /* 최소당첨릴생성인 경우 당첨금 계산 */
            if ($overtry) {
                /* 스핀 당첨금 */
                $winMoney = array_reduce($this->winLines, function($carry, $winLine) {
                    $carry += $winLine['Money']; 
                    return $carry;
                }, 0) * $bet;

                /* 프리스핀시 멀티플라이어 적용 */
                if ($slotEvent['slotEvent'] == 'freespin') {
                    $winMoney = $winMoney * $tumbleMultiplier;
                }
            }

            if ($winMoney > 0) {
                /* 윈라인 구성 */
                foreach ($this->winLines as $idx => $winLine) {
                    $payLineId = 0;

                    /* 프리스핀시 멀티플라이어 적용 */
                    if ($slotEvent['slotEvent'] == 'freespin') {
                        $winLineMoney = $winLine['Money'] * $bet * $tumbleMultiplier;
                    }
                    else {
                        $winLineMoney = $winLine['Money'] * $bet;
                    }
                    
                    $strLineSymbolPositions = implode("~", $winLine['Positions']);
                    $objRes["l${idx}"] = "${payLineId}~${winLineMoney}~${strLineSymbolPositions}";
                }

                /* 텀블스핀중이면  */
                if ($isTumble) {
                    $BALANCE = $LASTSPIN->balance;
                    $tumbleCount = $LASTSPIN->rs_p + 1;

                    /* 텀블당첨금 누적 */
                    $tumbleWin = $tumbleWin + $winMoney;

                    /* 텀블당첨금 업데이트 */
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleTotalWin', $tumbleWin);
                }
                /* 텀블스핀 시작 */
                else {
                    $tumbleCount = 0;
                    $tumbleWin = $winMoney;

                    /* 텀블당첨금 저장 */
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleTotalWin', $winMoney);
                }
                
                /* 텀블스핀이면 */
                $objRes['balance'] = $BALANCE;
                $objRes['balance_cash'] = $objRes['balance'];

                $objRes['rs_p'] = $tumbleCount;
                $objRes['rs'] = 'mc';
                $objRes['tw'] = $tumbleWin;
                $objRes['tmb_win'] = $tumbleWin;
                $objRes['w'] = $winMoney;
                $objRes['rs_c'] = 1;
                $objRes['rs_m'] = 1;
            }
            else {
                /* 텀블스핀 완료 */
                if (isset($LASTSPIN->rs)) {
                    $objRes['balance'] = $LASTSPIN->balance;
                    $objRes['balance_cash'] = $objRes['balance'];

                    $objRes['na'] = 'c';
                    $objRes['rs_t'] = $LASTSPIN->rs_p + 1;

                    $objRes['tmb_res'] = $tumbleWin;
                    $objRes['tmb_win'] = $tumbleWin;

                    /* 일반스핀에서 총당첨금은 텀블당첨금과 같음 */
                    $objRes['tw'] = $tumbleWin;

                    /* 텀블당첨금 리셋 */
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleTotalWin', 0);
                }
            }

            /* 프리스핀 중 */
            if ($slotEvent['slotEvent'] === 'freespin') {
                /* 프리스핀 시작밸런스 로드 */
                $BALANCE = $slotSettings->GetGameData($slotSettings->slotId . 'FSStartBalance');

                /* 프리스핀 응답 빌드 */
                $objRes['tw'] = ($LASTSPIN->tw ?? 0) + $winMoney;
                $objRes['balance'] = $BALANCE;
                $objRes['balance_cash'] = $BALANCE;

                /* 프리스핀 추가 */
                if ($this->getScatterCount($reels) == 3) {
                    $fsmax += 4;
                    $slotSettings->SetGameData($slotSettings->slotId . 'FSMax', $fsmax);
                }

                if ($fsmax > $fs) {
                    /* 프리스핀중에는 스핀타입이 항상 s */
                    $objRes['na'] = 's';
                    
                    $objRes['fsmul'] = 1;
                    $objRes['fsmax'] = $fsmax;

                    $objRes['fs'] = $isTumble ? $fs : $fs + 1;      // 텀블스핀이면 프리스핀 소모없음
    
                    /* 프리스핀 당첨금, 텀블스핀이면 이전값 유지, 텀블완료이면 텀블당첨금 추가 */
                    $objRes['fswin'] =  $LASTSPIN->fswin;
                    $objRes['fsres'] = $LASTSPIN->fsres;

                    /* 텀블스핀 완료 */
                    if ($isTumble && $winMoney == 0) {
                        $objRes['fswin'] += $tumbleWin;
                        $objRes['fsres'] += $tumbleWin;
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
                        $objRes['na'] = 'c';
                        $objRes['fswin_total'] += $tumbleWin;
                        $objRes['fsres_total'] += $tumbleWin;

                        /* 프리스핀 리셋 */
                        $slotSettings->SetGameData($slotSettings->slotId . 'FSMax', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FSNext', 0);
                    }
                }
                else {
                    /* throw exception */
                }

                $objRes['wmt'] = 'pr';
                $objRes['wmv'] = $tumbleMultiplier;     // 1배 기본

                if ($tumbleMultiplier >= 2) {
                    $objRes['gwm'] = $tumbleMultiplier;
                }
            }
            else if ($winType == 'bonus') { 
                /* 보너스 당첨조건이 안될 경우, 예로 overtry = true */
                if ($winMoney == 0 && $scatterCount < 4) {
                    /* 텀블보너스 리셋 */
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleBonus', false);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleBonusStepCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleBonusCurrentStep', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleBonusScatterCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleBonusCurrentScatterCount', 0);
                }
                /* 텀블보너스 스텝이 다 되었거나 가능한 릴생성이 불가능한 경우 */
                else if ($winMoney == 0 && $scatterCount >= 4 && ($overtry || $curTumbleBonusStep >= $tumbleBonusStepCount)) {

                    /* 갬블당첨 */
                    $fsCountMap = [
                        4 => 10,
                        5 => 14,
                        6 => 18, 
                        7 => 22,
                        8 => 26,
                        9 => 30
                    ];

                    
                    /* 프리스핀 22개이상 당첨이면 갬블 스킵, 프리스핀 바로 발동 */
                    if ($fsCountMap[$scatterCount] >= 22) {
                        $fsCount = $fsCountMap[$scatterCount];
                        $gambleStep = -1;
                    }
                    else {
                        // 갬블시작 스텝설정 (10, 14, 18, 22)
                        $gambleStep = $scatterCount - 4;
                        $status = [0, 0, 0, 0];
                        $status[$gambleStep] = 1;

                        /* 갬블 설정 */
                        $objRes['na'] = 'b';
                        $objRes['bgid'] = 0;
                        $objRes['wins'] = '10,14,18,22';
                        $objRes['coef'] = $bet * $lines;
                        $objRes['level'] = 0;       // 0 레벨;
                        $objRes['status'] = implode(",", $status);
                        $objRes['bgt'] = 35;
                        $objRes['lifes'] = 1;
                        $objRes['bw'] = 1;
                        $objRes['wins_mask'] = 'nff,nff,nff,nff';
                        $objRes['wp'] = 0;
                        $objRes['end'] = 0;

                        /* 갬블 스핀횟수 결정 */
                        $fsCount = $slotSettings->GetNewGambleFSCount();
                    }

                    /* 프리스핀 구매 */
                    if ($slotEvent['slotEvent'] === 'doGamble') {
                        $objRes['purtr'] = 1;
                        $objRes['puri'] = 0;
                    }

                    /* 갬블 당첨갯수, 현재 스텝 저장 */
                    $slotSettings->SetGameData($slotSettings->slotId . 'Gamble_WinCount', $fsCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Gamble_CurStep', $gambleStep);

                    /* 텀블보너스 리셋 */
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleBonus', false);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleBonusStepCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleBonusCurrentStep', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleBonusScatterCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleBonusCurrentScatterCount', 0);
                }
            }

            /* 밸런스 업데이트 */
            if( $winMoney > 0) 
            {
                $slotSettings->SetBalance($winMoney);
                $slotSettings->SetBank($slotEvent['slotEvent'] ?? '', -1 * $winMoney);
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

        public function doBonus($slotEvent, $LASTSPIN) {
            $slotSettings = $this->slotSettings;

            $BALANCE = $LASTSPIN->balance;
            $MaxFreespinCount = 22;

            /* 갬블레벨 대 프리스핀 갯수 */
            $fsCountMap = [
                0 => 10,
                1 => 14,
                2 => 18, 
                3 => $MaxFreespinCount
            ];

            $prevStep = $slotSettings->GetGameData($slotSettings->slotId . 'Gamble_CurStep');
            $nextStep = $prevStep + 1;
            
            $objRes = [
                'tw' => $LASTSPIN->tw ?? 0,
                'bgid' => '0',
                'balance' => $BALANCE,
                'wins' => '10,14,18,22',
                'coef' => $LASTSPIN->coef,
                'level' => $LASTSPIN->level + 1,
                'index' => $slotEvent['index'],
                'balance_cash' => $BALANCE,
                'balance_bonus' => '0',
                'na' => 'b',
                'stime' => floor(microtime(true) * 1000),
                'bgt' => '35',
                'lifes' => '1',
                'wins_mask' => 'nff,nff,nff,nff',
                'wp' => '0',
                'end' => '0',
                'sver' => '5',
                'counter' => ((int)$slotEvent['counter'] + 1)
            ];

            /* 갬블 시도? 프리스핀 획득? */
            if ($slotEvent['ind'] == 1) {
                /* 갬블 당첨가능한 프리스핀갯수 */
                $fsCount = $slotSettings->GetGameData($slotSettings->slotId . 'Gamble_WinCount');
                if ($fsCount == $MaxFreespinCount && $fsCountMap[$nextStep] == $MaxFreespinCount) {
                    /* 갬블 완료, 최대프리스핀 당첨 */
                    $objRes['na'] = 's';
                    $objRes['status'] = '0,0,0,1';

                    $objRes['fsmul'] = 1;
                    $objRes['fs'] = 1;
                    $objRes['fsmax'] = $MaxFreespinCount;
                    $objRes['fswin'] = 0;
                    $objRes['fsres'] = 0;
                    
                    $objRes['rw'] = 0;
    
                    $objRes['lifes'] = 1;
                    $objRes['end'] = 1;    
                }
                else if ($fsCount >= $fsCountMap[$nextStep]) {
                    /* 갬블 당첨 */
                    $objRes['na'] = 'b';
                    $objRes['lifes'] = 1;
                    $objRes['end'] = 0;

                    $status = [0, 0, 0, 0];
                    $status[$nextStep] = 1;
                    $objRes['status'] = implode(",", $status);
                }
                else {
                    /* 갬블 종료 */
                    $objRes['na'] = 's';
                    $objRes['lifes'] = 0;
                    $objRes['end'] = 1;

                    $objRes['status'] = $LASTSPIN->status;
                    $objRes['rw'] = 0;
                }
            }
            else if ($slotEvent['ind'] == 0) {
                /* 갬블 중지, 프리스핀 획득 */
                $objRes['na'] = 's';

                $objRes['fsmul'] = 1;
                $objRes['fs'] = 1;
                $objRes['fsmax'] = $fsCountMap[$nextStep - 1];
                $objRes['fswin'] = 0;
                $objRes['fsres'] = 0;
                
                $objRes['rw'] = 0;

                $objRes['lifes'] = 1;
                $objRes['end'] = 1;
                $objRes['status'] = $LASTSPIN->status;
            }
            else {

            }
            
            /*  */
            $slotSettings->SetGameData($slotSettings->slotId . 'Gamble_CurStep', $nextStep);
            
            /* 프리스핀 시작밸런스, 카운터 저장 */
            if (isset($objRes['fsmax'])) {
                $slotSettings->SetGameData($slotSettings->slotId . 'FSMax', $objRes['fsmax']);
                $slotSettings->SetGameData($slotSettings->slotId . 'FSNext', 1);
                $slotSettings->SetGameData($slotSettings->slotId . 'FSStartBalance', $BALANCE);
            }
            
            $_GameLog = json_encode($objRes);
            $slotSettings->SaveLogReport($_GameLog, 0, 0, 0, 'doBonus');

            return $objRes;
        }

        public function checkWinLines($reels) {
            $REELCOUNT = 6;
            $EXTENDEDSYMBOLCOUNT = 8;
            $S_BLANK = 19;
            $S_SCATTER = 1;

            /* top, reg 릴 합치기 */
            $extended_reels = $reels['reg']['symbols'];
            for ($i=0; $i < $REELCOUNT; $i++) { 
                if ($i == 0 || $i == $REELCOUNT - 1) {
                    /* 2,3,4,5번 릴이 아닌 경우 빈값 등록 */
                    $extended_reels[$i][-1] = $S_BLANK;
                }
                else {
                    /* 매 릴의 -1 위치에 top 릴 심볼 등록 */
                    $extended_reels[$i][-1] = $reels['top']['symbols'][$i - 1];
                }

                /* 베이스인덱스 0으로 리셋, -1 인덱스 없애기 */
                ksort($extended_reels[$i]);
                $extended_reels[$i] = array_values($extended_reels[$i]);
            }
            
            for ($symbolId=0; $symbolId < $EXTENDEDSYMBOLCOUNT; $symbolId++) { 
                $firstSymbol = $extended_reels[0][$symbolId];
                
                /* SCATTER, 빈 심볼이면 스킵 */
                if ($firstSymbol == $S_BLANK || $firstSymbol == $S_SCATTER) {
                    continue;
                }

                $this->findZokbos($extended_reels, $firstSymbol, 1, [$symbolId * $REELCOUNT]);
            }
        }
        
        public function findZokbos($reels, $firstSymbol, $repeatCount, $positions){
            $S_WILD = 2;
            $REELCOUNT = 6;
            $EXTENDEDSYMBOLCOUNT = 8;
            $bPathEnded = true;

            if($repeatCount < 6){
                /* reg, top 통합릴셋이므로 최대심볼갯수는 7 + 1 */
                for($r = 0; $r < $EXTENDEDSYMBOLCOUNT; $r++){
                    if($firstSymbol == $reels[$repeatCount][$r] || $reels[$repeatCount][$r] == $S_WILD){
                        $this->findZokbos($reels, $firstSymbol, $repeatCount + 1, array_merge($positions, [$repeatCount + $r * $REELCOUNT]));
                        $bPathEnded = false;
                    }
                }
            }

            if($bPathEnded == true){
                if(($firstSymbol == 3 && $repeatCount == 2) || $repeatCount >= 3){
                    $winLine = [];
                    $winLine['FirstSymbol'] = $firstSymbol;
                    $winLine['RepeatCount'] = $repeatCount;
                    $winLine['Positions'] = $positions;

                    /* 페이테이블 검사 */
                    $winLine['Money'] = $this->slotSettings->PayTable[$firstSymbol][$REELCOUNT - $repeatCount];
                    array_push($this->winLines, $winLine);
                }
            }
        }

        public function getScatterCount($reels) {
            $S_SCATTER = 1;
            $REELCOUNT = 6;
            $topScatterCount = count(array_keys($reels['top']['symbols'], $S_SCATTER));
            $regScatterCount = 0;

            for ($reelId=0; $reelId < $REELCOUNT; $reelId++) { 
                $regScatterCount += count(array_keys($reels['reg']['symbols'][$reelId], $S_SCATTER));
            }

            return $topScatterCount + $regScatterCount;
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

        public function getTumbleSymbolCount($reels) {
            $REELCOUNT = 6;

            /* 텀블심볼정보 추가 */
            $count = 0;
            $winLines = $this->winLines;

            if (count($winLines) > 0) {
                /* 윈라인 심볼 얻기 */
                $winSymbolPositions = [];
                foreach ($winLines as $winLine) {
                    $winSymbolPositions = array_merge($winSymbolPositions, $winLine['Positions']);
                }
                $winSymbolPositions = array_unique($winSymbolPositions);

                /* 윈라인 심볼분리 */
                foreach ($winSymbolPositions as $pos) {
                    if ($pos < $REELCOUNT) {
                        /* top 릴에 위치한 심볼 */
                    }
                    else {
                        /* reg 릴셋에 위치한 심볼 */
                        $count ++;
                    }
                }
            }

            return $count;
        }

        public function buildReelSetResponse($reels) {
            $REELCOUNT = 6;
            $S_WILD = 2;
            $S_HAMMER = 13;

            /* reg 릴셋 1차원배열 생성 */
            $flattenRegSymbols = [];
            $saRegSymbols = [];
            $sbRegSymbols = [];

            foreach ($reels['reg']['symbols'] as $reelId => $symbols) {
                foreach ($symbols as $k => $symbol) {
                    $flattenRegSymbols[$reelId + $k * $REELCOUNT] = $symbol;
                }
            }
            ksort($flattenRegSymbols);

            /* top 릴셋 1차원배열 생성, 역방향 정렬 */
            krsort($reels['top']['symbols']);
            $flattenTopSymbols = array_values($reels['top']['symbols']);

            /* 텀블심볼정보 추가 */
            $topSymbols = [];
            $regSymbols = [];
            $winLines = $this->winLines;

            if (count($winLines) > 0) {
                /* 윈라인 심볼 얻기 */
                $winSymbolPositions = [];
                foreach ($winLines as $winLine) {
                    $winSymbolPositions = array_merge($winSymbolPositions, $winLine['Positions']);
                }
                $winSymbolPositions = array_unique($winSymbolPositions);

                /* 윈라인 심볼분리 */
                foreach ($winSymbolPositions as $pos) {
                    if ($pos < $REELCOUNT) {
                        $pos = 3 - ($pos - 1);
                        $symbol = $flattenTopSymbols[$pos];
                        /* top 릴에 위치한 심볼 */
                        array_push($topSymbols, "${pos},${symbol}");
                    }
                    else {
                        /* reg 릴셋에 위치한 심볼 */
                        $pos = $pos - $REELCOUNT;
                        $reelPos = intdiv($pos, $REELCOUNT);
                        $reelId = $pos % $REELCOUNT;
                        $symbol = $reels['reg']['symbols'][$reelId][$reelPos];
                        array_push($regSymbols, "${pos},${symbol}");
                    }
                }

                /* 망치심볼 추가 */
                $hammerReels = $reels['reg']['hammer_reels'] ?? null;
                if (isset($hammerReels)) {
                    // 1, 2번릴에 나란히 있을때만
                    if (count($hammerReels) >= 2 && ($hammerReels[0] + $hammerReels[1] == 3)) {
                        foreach ($hammerReels as $reelId) {
                            /* top 릴기준 변환 */
                            $pos = 3 - ($reelId - 1);
                            array_push($topSymbols, "${pos},${S_HAMMER}");
                        }
                    }
                }
            }

            $objRes = [
                'reg' => [
                    'reel_set' => $reels['reg']['reelset_id'],
                    's' => implode(",", $flattenRegSymbols),
                    'sa' => implode(",", $reels['reg']['after_symbols']),
                    'sb' => implode(",", $reels['reg']['before_symbols']),
                    'sh' => '7',
                    'st' => 'rect',
                    'sw' => '6',
                ],
                'top' => [
                    'reel_set' => $reels['top']['reelset_id'],
                    's' => implode(",", $flattenTopSymbols),
                    'sa' => $reels['top']['after_symbols'],
                    'sb' => $reels['top']['before_symbols'],
                    'sh' => '4',
                    'st' => 'rect',
                    'sw' => '1',
                ]
            ];

            /* 텀블심볼 결정 */
            if (count($regSymbols) > 0) {
                $objRes['reg']['tmb'] = implode("~", $regSymbols);
            }

            if (count($topSymbols) > 0) {
                $objRes['top']['tmb'] = implode("~", $topSymbols);
            }

            /* 망치심볼 결정 */
            if (isset($reels['reg']['hammer_reels'])) {
                /* 원본 릴셋 */
                $flattenRegSymbols = [];
                foreach ($reels['reg']['isymbols'] as $reelId => $symbols) {
                    foreach ($symbols as $k => $symbol) {
                        $flattenRegSymbols[$reelId + $k * $REELCOUNT] = $symbol;
                    }
                }
                ksort($flattenRegSymbols);

                $objRes['reg']['is'] = implode(",", $flattenRegSymbols);

                /* 망치심볼에 의한 WILD 심볼 셋팅 */
                $wildSymbols = [];
                foreach ($reels['reg']['hammer_reels'] as $reelId) {
                    $reel = $reels['reg']['symbols'][$reelId];
                    $symbols = array_map(function($pos, $symbol) use ($reelId, $S_WILD, $REELCOUNT) {
                        if ($symbol == $S_WILD) {
                            $flattenPos = $reelId + $pos * $REELCOUNT;
                            return "${S_WILD}~${flattenPos}";
                        }
                        return null;
                    }, array_keys($reel), $reel);

                    $wildSymbols = array_merge($wildSymbols, $symbols);
                }

                /* null인 심볼 제거 */
                $wildSymbols = array_filter($wildSymbols);
                $wildSymbolCount = count($wildSymbols);

                $objRes['reg']['ds'] = implode(";", $wildSymbols);

                $dsa = array_fill(0, $wildSymbolCount, 0);
                $objRes['reg']['dsa'] = implode(";", $dsa);

                $dsam = array_fill(0, $wildSymbolCount, 'v');
                $objRes['reg']['dsam'] = implode(";", $dsam);
            }
            
            ksort($objRes['reg']);
            ksort($objRes['top']);
            return $objRes;
        }

        public function stringifyReels($reels) {
            /* 다시 역정렬 */
            $topSymbols = explode(",", $reels['top']['s']);
            krsort($topSymbols);
            $reversed_top_reel = array_values($topSymbols);

            /* [19, top_reel, 19, reg_reel] */
            $regSymbols = explode(",", $reels['reg']['s']);
            $res = array_merge([19], $reversed_top_reel, [19], $regSymbols);
            return implode(",", $res);
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
