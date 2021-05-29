<?php 
namespace VanguardLTE\Games\TheDogHouseMegawaysPM
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

            $slotEvent['slotEvent'] = $slotEvent['action'];

            if( $slotEvent['slotEvent'] == 'doInit' ) 
            { 
                // $slotSettings->SetGameData($slotSettings->slotId . 'ScattersCount', 0);
                // $slotSettings->SetGameData($slotSettings->slotId . 'WILDCollection', []);
                // $slotSettings->SetGameData($slotSettings->slotId . 'NewWILDCollection', []);

                // $slotSettings->SetGameData($slotSettings->slotId . 'FSMax', 0);
                // $slotSettings->SetGameData($slotSettings->slotId . 'FSNext', 0);
                // $slotSettings->SetGameData($slotSettings->slotId . 'FSOpt', 0);

                $objRes = [
                    'def_s' => '8,3,12,3,12,11,8,12,3,12,3,6,10,12,3,12,3,6,10,7,13,7,13,12,14,7,14,7,13,15,14,11,14,14,8,14,14,9,14,14,8,14',
                    'balance' => $BALANCE,
                    'nas' => '14',
                    'cfgs' => '5290',
                    'ver' => '2',
                    'index' => $slotEvent['index'],
                    'balance_cash' => $BALANCE,
                    'def_sb' => '13,11,13,11,13,11',
                    'reel_set_size' => '11',
                    'def_sa' => '6,6,6,13,13,13',
                    'reel_set' => '0',
                    'balance_bonus' => '0.00',
                    'na' => 's',
                    'scatters' => '1~0,0,0,0,0,0~0,0,0,0,0,0~1,1,1,1,1,1',
                    'gmb' => '0,0,0',
                    'rt' => 'd',
                    'stime' => floor(microtime(true) * 1000),
                    'sa' => '6,6,6,13,13,13',
                    'sb' => '13,11,13,11,13,11',
                    'reel_set10' => '13,13,13,4,12,12,12,6,12,5,5,5,13,10,9,11,4,4,4,5,3,7,8,11,11,11,3,3,3,8,8,8,9,9,9,6,6,6,4,11~11,11,11,9,3,3,3,4,9,9,9,3,13,13,13,12,8,6,10,6,6,6,13,12,12,12,5,7,5,5,5,11,4,4,4,8,8,8,6,3,9,13,3,9,3,13,9,12,6,9,6,3,13,9,13,6,4,9,3,12,9,12,6,4,6,4,9,4,8,13,9,4,3~8,8,8,11,4,10,3,3,3,6,13,8,3,6,6,6,7,13,13,13,9,5,12,11,11,11,4,4,4,9,9,9,5,5,5,12,12,12,3,12,3,6,9,6,12,5,12,13,5,9,12,5,11,7,4,9,4,6,13,6,13,3,5,12,3,6,11,9,12,9,13,3,13,9,4,9,6,12,11,4,12,11,4,3~12,12,12,11,8,8,8,8,5,13,13,13,7,5,5,5,10,9,9,9,12,6,6,6,3,3,3,3,13,4,4,4,6,4,9,11,11,11,6,9,6,13,8,6,8,6,3,6,9,4,9,6,11,8,5,9,4,9,7,9,8,9,4,13,5,8,9~13,5,3,3,3,9,9,9,9,7,6,11,4,3,8,12,10,11,11,11,12,12,12,5,5,5,6,6,6,4,4,4,8,8,8,13,13,13,3,8,9,6,12,8,9,3,8,4,3,11,4,3,12,6,11,9,12,4,11,9,3,8,3,11,3,11,6,12,5,9,12,9,5,8,4,9,3,12,9,6,5,9,12,3,9,5,12,4,6,4,6~8,8,8,7,10,9,13,13,13,12,6,12,12,12,13,3,8,11,4,6,6,6,5,5,5,5,4,4,4,9,9,9,3,3,3,11,11,11,4,13,6,3,11,13,3,6,5,6,9,3',
                    'sc' => '10.00,20.00,30.00,40.00,50.00,100.00,200.00,300.00,400.00,500.00,750.00,1000.00,2000.00,3000.00,4000.00,5000.00',
                    'defc' => '100.00',
                    'sh' => '7',
                    'wilds' => '2~0,0,0,0,0,0~1,1,1,1,1,1;15~0,0,0,0,0,0~1,1,1,1,1,1;16~0,0,0,0,0,0~1,1,1,1,1,1',
                    'bonuses' => '0',
                    'fsbonus' => '',
                    'c' => '10.00',
                    'sver' => '5',
                    'counter' => ((int)$slotEvent['counter'] + 1),
                    'paytable' => '0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;150,60,40,15,0,0;60,30,20,10,0,0;40,20,15,7,0,0;30,15,10,4,0,0;30,10,8,3,0,0;30,10,8,3,0,0;20,6,4,2,0,0;20,6,4,2,0,0;20,6,4,2,0,0;10,4,2,1,0,0;10,4,2,1,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0',
                    'l' => '20',
                    'rtp' => '96.55',
                    'total_bet_max' => '10,000,000.00',
                    'reel_set0' => '4,4,4,1,10,3,13,13,13,12,6,12,12,12,8,11,11,11,11,5,8,8,8,7,4,9,9,9,9,13,3,3,3,5,5,5,6,6,6,7,1,8,13,6,8,12,6,9,3,8,9,3,8,5,6,1,11,12,11,1,13,8,3,9,3,8,12,6,3,11,8,13,6~8,3,9,9,9,13,7,11,13,13,13,2,6,11,11,11,9,1,3,3,3,12,5,10,5,5,5,4,8,8,8,12,12,12,4,4,4,3,9,13,12,4,11,3,13,12,4,2,9,5,12,9,4,5,4,6,4,13,5,13,12,13~11,11,11,8,7,1,13,13,13,13,12,12,12,9,9,9,9,4,5,5,5,3,3,3,3,10,4,4,4,11,5,12,2,6,2,12,4,9,12,2,13,5,3,2~4,4,4,4,9,9,9,11,13,13,13,5,7,12,12,12,12,1,5,5,5,2,8,13,11,11,11,6,3,10,9,3,3,3,5,13,9,11,9,3,9,12,9,5,13,5,7,9,3,5,3,5,9,3,13,3,5,3,13~11,12,12,12,1,10,9,12,11,11,11,13,5,5,5,8,7,3,3,3,4,5,2,6,4,4,4,3,13,13,13,9,9,9,12,7,4,5~10,12,12,12,11,8,8,8,3,8,6,3,3,3,1,11,11,11,9,5,5,5,5,13,12,6,6,6,7,4,13,13,13,9,9,9,4,4,4,6,12,5,4,8,5,12,6,5,4,5,4,6,5,8,7,9,8,9,13,4,6,13,8,4,12,6,12,7,1,3,13,11,5,8,9,6,8,5,9,5,9,5,12',
                    's' => '8,3,12,3,12,11,8,12,3,12,3,6,10,12,3,12,3,6,10,7,13,7,13,12,14,7,14,7,13,15,14,11,14,14,8,14,14,9,14,14,8,14',
                    'reel_set2' => '8,8,8,12,10,11,11,11,13,3,13,13,13,9,3,3,3,4,6,12,12,12,11,6,6,6,8,5,5,5,7,4,4,4,5,9,9,9,10,3,13,3,4,11,9,5,3,11,10,11,13,11,9,3,9,4,5,11,10,3,11,6,5,13,3,9,5,13,5,10,3,9,6,11,3,13,11,6,4,9,4,3,11,4,11,4,5,9,3,11,13,11,3,5,12,3,4,3,6~9,9,9,5,11,11,11,12,7,11,4,4,4,13,13,13,13,4,5,5,5,10,6,3,3,3,8,8,8,8,3,12,12,12,9,5,4,3,4,5,6,11,3,13,11,3,5,11,8,11,8,12,11,13,4,13,10,4,13,4,11,8,5,3,8,3,13,5,4,13,3~5,10,4,4,4,11,8,11,11,11,9,13,6,4,3,3,3,3,8,8,8,12,7,9,9,9,12,12,12,13,13,13,5,5,5,9,8,12,4,12,4,6,11,7,11,4,3,7,11,3,8,4,8,3,4,9,11,9,11,12,11,9,7,3,11,4,7,8,3,7,9,4,3,4,3,9,11,9,12~9,7,6,3,3,3,5,8,3,4,4,4,13,4,11,12,12,12,12,10,9,9,9,13,13,13,5,5,5,11,11,11,4,5,10,13,4,3,13,12,3,12,5,13,7,3,11,5,13,3,5,4,3,13,11,7,4,12,3,5,8,5,7,13,8,3,5,12,7,3,6,5,3,7,3,6,13~4,4,4,9,3,3,3,10,12,3,4,11,11,11,8,6,5,7,11,13,9,9,9,12,12,12,5,5,5,13,13,13,11,13,9,5,9,3,9,6,13,3,13,9,13,11,9,13,11,6,9,13,3,5,12~12,13,13,13,5,4,3,12,12,12,10,9,9,9,7,11,9,6,6,6,8,13,6,4,4,4,3,3,3,11,11,11,5,5,5,8,8,8,6',
                    't' => '243',
                    'reel_set1' => '4,8,10,6,6,6,13,9,9,9,9,7,12,5,13,13,13,11,4,4,4,6,8,8,8,3,11,11,11,3,3,3,12,12,12,5,5,5,7,5,6,11,13,9,6,10,3,6,13,3,9,3,6,8,9,10,12,3,12,10,6,3,8,13,9,8,3,13,3,13,6,13,3,8,12,6,9,12,6,12,11,10,12,9,3,10,13~3,3,3,11,8,8,8,6,13,13,13,9,9,9,9,10,5,5,5,13,12,12,12,5,3,4,2,12,8,7,11,11,11,4,4,4,5~8,8,8,13,13,13,13,6,10,4,7,11,9,11,11,11,5,3,3,3,8,2,3,12,9,9,9,4,4,4,5,5,5,12,12,12,5,13,9,13,5,11,13,2,13,3,13,5,4,2,12,13,3,11,5,3,11,4,13,9,3,13,4,13,5,13,4,11,3,13,12,7,13,4,13,2,5,3,4~4,12,12,12,10,13,13,13,5,3,3,3,2,11,7,6,13,11,11,11,3,12,9,9,9,9,8,5,5,5,4,4,4,5,8,12,11,12,8,3,13,11,2,9,6,2,3,2,3,12,5,8,5,9,5,12,8,3,7,9,5,12,7,3,12,9,3,11,3,9,2,8,11,12,5~4,9,8,13,3,7,6,11,13,13,13,5,2,9,9,9,12,10,4,4,4,11,11,11,12,12,12,5,5,5,3,3,3,13,3,11,2,13,2,12,3,13,7,13,9,3,2,6,12,13,12,13,3,5,13,3,13,3,13,7,13,3,13,9,5,3,9,3,5,12,13,5,13,9,12,9,2,11,12,5,9,12,3,13,9,12,11,13,5,9,13,9,3,12,3,13,12,5,9~13,13,13,3,12,12,12,7,9,8,8,8,6,6,6,6,10,4,5,5,5,5,13,11,11,11,8,12,11,9,9,9,3,3,3,4,4,4,5,11,9,6,4,8,11,4,9,5,11,6,4,6,5,11,12,8,11,6,12,11,4,3,12,6,9,8,4,11,4,3,6,5,3,8,6,9,3,6,12,5,11,3,4,12,5,9,4,6,4,6,4,9,8,11,6,4,5,11,9,6',
                    'reel_set4' => '7,11,11,11,9,8,8,8,12,4,6,5,9,9,9,13,10,8,5,5,5,11,3,13,13,13,12,12,12,3,3,3,6,6,6,4,4,4,5,4,9,8,5,13,4,5,12,13,4,12,4,5,4,12,5,4,3,13,12~13,9,11,11,11,10,9,9,9,4,12,12,12,7,8,3,4,4,4,5,13,13,13,12,6,5,5,5,11,3,3,3,11,3,5,11,5,11,9,5,11,12,5,11,12,11,5,12,4,11,3,4,9,3,11,5,4,9,11,3,9,11,7,3,12,3,12,3,9,11,5,4,11,9,11,12,9,4,3,11,5,4,7,8,3,11,3,11,12,4,3~12,12,12,5,12,7,11,6,11,11,11,9,13,13,13,13,4,10,3,8,9,9,9,4,4,4,3,3,3,5,5,5,11,9,13,3,13,11,3,9,4,13,9,5,11,13,4,13,4,13,5,13,11,13,11,9,3,9,13,3,13,9,13,9,13,4,13,4,3,13,5,13,9,4,13,3,13~10,8,8,8,5,13,13,13,13,12,12,12,4,9,4,4,4,2,8,11,11,11,7,3,3,3,6,5,5,5,12,11,3,9,9,9,12,7,8,5,12,8,5,9,3,4,11,12,11,8,3~9,9,9,9,6,8,8,8,2,12,12,12,12,5,10,4,7,3,8,11,13,4,4,4,11,11,11,3,3,3,13,13,13,5,5,5,12,7,3,8,5,8,12,13,11,13,8,12,11,7,5,3,12,4,8,12,8,11,3,5,8~13,8,9,11,11,11,10,12,11,4,3,7,5,6,12,12,12,5,5,5,13,13,13,4,4,4,3,3,3,8,8,8,9,9,9,6,6,6,5,8,6,12,11,5,4,12,3,11,4,12,11,7,8,11,12,4,6,11,3,4,10,9,12,11,8,3,9,11,8,11,8,11,5,3,6,12,6,8,9,8,5,11,5,6,11,9',
                    'purInit' => '[{type:"fsbl",bet:2000,bet_level:0}]',
                    'reel_set3' => '3,3,3,7,5,11,9,9,9,13,12,6,6,6,9,4,4,4,10,11,11,11,6,12,12,12,3,4,5,5,5,8,13,13,13,8,8,8,6,9,6,12,7,13,5,13,6,9,11,6,13,12,7,9,4,8,6,5,7,9,11,4,6,9,8,12,4,8,6,4,5,10,4,7,9,4,13,11,9,5,12,4,12,4,11,9,4~3,3,3,3,4,12,12,12,5,13,13,13,9,12,2,4,4,4,10,9,9,9,8,8,8,8,6,11,7,13,5,5,5,11,11,11,5,9,13,12,7,12,9,11,13,11,13,12,13,12,8,11~3,9,13,13,13,11,12,5,5,5,2,13,8,5,7,10,4,6,9,9,9,8,8,8,11,11,11,12,12,12,4,4,4,3,3,3,4,12,11,9,13,5,13,4,12,4,11,12,13,8,4,6,9,5,11,4,9,5,4,13,6,11,5,9,12,5,4,11,5,9,12,13,9,6,11,12,8,4,11,13,12,9,8,4,12,8,4,12,5,12,4,9,4,11,5,8,4,9,8~9,9,9,8,11,7,12,12,12,5,6,13,10,9,3,3,3,4,12,3,13,13,13,5,5,5,11,11,11,4,4,4,8,3,13,5,3,10,8,3,5,12,11,3,12,3,5,13,5,12,11,5,10,13,5,4,12,13,12,13,12,13,5,13,5~4,4,4,8,12,5,5,5,5,9,3,9,9,9,6,4,11,13,13,13,7,12,12,12,13,10,3,3,3,11,11,11,3,7,5,12,3,12,7,3,11~4,3,3,3,3,7,6,13,13,13,13,5,10,11,11,11,11,9,8,12,12,12,12,6,6,6,5,5,5,9,9,9,8,8,8,4,4,4,5,11,3,6,9,13,5,13,10,9,5,13,9,5,11,6,9,3,13,9,13,11,8,9,13,9,5,11,13,9,8',
                    'reel_set6' => '11,3,10,13,5,4,8,6,9,12,7,10,12,9,10,4,5,7,13,7,6,4,10,7,9,6,4,5,10,4,7,4,9,10,9,6,5,6,10,5,4,5,6,4,5,10,5,6,5,4,7,9,4,7,10,4,6,7,5,7,6,7,5,9,7,9,13,4,7,5,4,9,4,6,9,10,13,4,10,9,6~4,13,9,2,11,6,8,10,12,7,5,3,7,6,7,11,2,6,2,8,7,8,7,11,5,11,2,7,3,7,3,11,3,6,5,6,8,11,2,8,3,6,12,5,11,6,7,8,7,8,5,2,3,8,2,3,11~7,12,8,10,2,9,4,5,11,6,13,3,11,3,6,12,13,3,4,6,10,11,13,11,6,12,8,5,12,6,3,5,10,5,3,12,8,5,12,9,4,11,12,8,3,4,3,12,4,8,5,6,10,3,8,5,8,4,5,6,8,11,12,4,8,10,12,11,4,5,8,3,6,12,3,11,6,12,13,12~6,10,11,8,9,12,2,7,4,3,13,5,4,11,3,13,7,2,5,7,5,4,12,4,7,5,9,13,2,4,2,4,5,4,2,4,10,12~12,11,9,7,5,2,13,4,6,8,10,3,11,5,3,13,7,2,11,8,11,10,8,11,2,8,11,10,11,3,11,6,8,13,7,3,7,11,13,2,13,8,11,9,13,3,11,6,11,13,8,11,8,13,5,7,11,2,8,13,11,9,11,3,8,11,13,10,11,13,8,9,13~8,3,6,13,11,12,4,5,10,9,7,9,7,3,5,12,5,6,10,6,10,6,13,12,3,5,7,10,7,9,13,7,3,5,10,9,6,3,12,6,7,13,6,3,7,10,13,9,7,3,10,12,6,7,6,9,7,9,12,5,7,9,12,5,10,12,3,5,6,9',
                    'reel_set5' => '5,6,7,13,10,4,11,12,3,1,8,9,11,12,6,1,6,11~5,11,6,1,10,4,13,9,7,8,3,12,2,8,3,2,7,8,2,7,2,8,6,12,1,8,1,2,12,8,2,1,3,7,12,7,6~2,7,10,1,11,8,3,9,4,12,13,5,6,13,3,1,3,1,9,5,3,4,10,4,9,5,4,1,6,3,4,7,5,11,1,13,1,5,10,1,9,3,4,5,6,3~4,13,12,10,6,7,5,2,3,1,9,11,8,3,9,7,8,1,3,1,9,7,5,12,13,3,5,9,8,1,7,1,13,10,5,1,9,1,12,1,3,9,5,9,5,9,12,1,5,8,5,10,1,9,5~6,13,12,8,11,2,3,4,7,9,1,10,5,7,2,5,10,5,9,3,4,10,7,5,12,13,5,3,9,10,8,11,2,7,8,13,10,5,10,7,13,3,4,2,7,5,10,8,1,7,8,2,5,4,5,2,8,2,5,8,5,2,10,2,7,2,8,5,2,7,5,8,13,8,5,13,2,5,9,2,13,2,1,13,9,4,10,2,3,2,7,2~9,1,4,11,10,6,8,3,7,12,13,5,13,3,13,5,10,7,3,7,3,5,13,7,13,7,8,1,7,3,6,3,8,13,3,13,8,3,13,11,13,5,13,7,5,6,13,3,6,3,7,3,8,3,8,7,12,8,5,13,7,3,8,13,7,5,13,5,13,12,3,8,5,3,5,3,7,6,11,6,3,5,7,13,8,13,12,3',
                    'reel_set8' => '13,11,5,6,8,13,13,13,10,7,3,5,5,5,12,8,8,8,9,4,4,4,4,11,11,11,9,9,9,3,3,3,12,12,12,7,7,7,3,12,11,5,8,7,3,6,3,12,10,5,11,3,9,6,3,4,5,12,3,4,5,11,12,7,4,3,12,8,3,5,12,7,4,5,3,7,6,4,8~7,6,10,13,13,13,13,12,11,9,7,7,7,3,5,2,4,8,8,8,8,12,12,12,4,4,4,11,11,11,9,9,9,3,3,3,4,12,5,3,5,2,3,5,11,13,5,12,10,5,8,11,12,13,5,11,9,12,13,9,2,10,12,9,13,11,3~3,3,3,11,7,13,13,13,9,8,8,8,6,8,3,13,4,2,12,5,5,5,10,5,11,11,11,4,4,4,12,12,12,9,9,9,8,13,11,9,8,11,13,4,8,13,9,11,4,5,11,4,5,13,4,9,4,13,5,4,13,4,11,13~4,4,4,3,7,9,9,9,9,12,13,13,13,13,8,8,8,6,12,12,12,11,4,10,7,7,7,2,5,8,3,3,3,11,11,11,8,12,13,7,13,12,7,11,3,7,5,13,8,7,3,13,5,9,12,5,10,13,9,6,12,7,12,9,12,11,10,11,8,5,13,12,9,3,7,5,9,11,13,5,7,3,12,13,11,12,9,11,10,3,5~6,3,8,12,4,5,10,11,9,7,13,5,4,11,5,4,12,5,9,5,13,8,5,9,10,5,10,8,4,5,11,5,9,10,9,5,9,3,4,9,5,12,4,10,9,4,9,5,3,5,9,13,10,11,5,4,5,8,9,10,5,9,4,5,10,13,5,10,5,9,10,5,4,5,8,13,10,12,4,5,10,12,10,13,3,9,5,9,13,4,10~7,12,3,13,8,10,6,5,11,9,4,6,3,6,5,3,11,6,9,6,5,13,6,9,4,5,13,11,6,13,9,6,5,3,6,11,5,3,11,6,5,3,4,6,3,6,3,5,6,11,10,5,6,4,3,6,11,6,4,13,6,11,6,5,4,6,10,4,3,11,6,11,5,11,6,5,4,5,6,4,9,5,6,4,13,6,5,3,6,9,3,4,6,9,4,5,6',
                    'reel_set7' => '3,6,6,6,12,13,13,13,11,7,4,9,11,11,11,8,10,9,9,9,13,8,8,8,6,3,3,3,5,5,5,5,12,12,12,4,4,4,13,12,11,13,9,11,5~12,12,12,3,5,3,3,3,6,2,7,8,8,8,10,4,4,4,13,4,13,13,13,8,9,12,9,9,9,11,11,11,11,5,5,5,7,13,5,7,5,13,5,2,6,13,5,4,8,7,5,11,3,5,2,9,5,11,8,3~6,2,3,13,7,11,11,11,10,12,12,12,5,12,4,4,4,4,11,9,9,9,8,9,5,5,5,13,13,13,3,3,3,8,8,8,12,10,9,3,12,11,7,4,5,10,12,8,9,3,4,9,13,5,8,13,5,4,3,8,12,3,7,9,10,13,7,3,10,3,11,4,5,4,3,7,4,5,4,3,7,8,5,7,2,9,5,7,13,4~4,4,4,5,8,9,9,9,6,3,9,8,8,8,12,10,5,5,5,2,11,12,12,12,7,4,13,3,3,3,11,11,11,13,13,13,8,2,8,6,3,11,12,8,3,9~13,2,5,4,4,4,8,11,9,9,9,9,10,3,12,4,6,7,5,5,5,11,11,11,13,13,13,8,8,8,3,3,3,12,12,12,8,9,10,3,4,10,12,8,5~4,9,9,9,10,12,12,12,7,11,6,12,9,5,13,13,13,13,3,11,11,11,8,3,3,3,8,8,8,4,4,4,5,5,5,6,6,6,13,8,5,12,13,12,11,10,11,8,6,5,10,9,13,5,13,5,12,13,8,5,8,13,5,12,13,5,12,8,6,5,6,5,11,5,10,8,13,5,8,5,12,8,11,5,8,5,8,13,11,10,5,12,13,12,13,12,6',
                    'reel_set9' => '7,5,8,11,9,9,9,6,10,12,3,4,4,4,4,13,9,5,5,5,8,8,8,12,12,12,13,13,13,3,3,3,11,11,11,6,6,6,9,13,9,11,8,6,5,3,5,13,12,5,13,10,13,5,9,5,13,12,5,11,4,12,13,11,9,13,12,8~9,9,9,8,3,3,3,3,6,13,13,13,9,11,4,13,5,5,5,5,7,10,8,8,8,12,11,11,11,4,4,4,12,12,12,8,3,5,4,12,3,12,11,12,3,12,11,4,13,6,11,13,12,5,4,5,8,13,5,3,4,10,3,8,11,10,5,12,11,3,4,3,5,3,10,13,8,3,4,13,7,4,12,13,12,10,13,11,3,4~13,8,8,8,12,7,4,10,8,5,6,3,9,11,11,11,11,4,4,4,5,5,5,3,3,3,13,13,13,12,12,12,9,9,9,10,12,3,10,4,11,10,7,4,11,12,4,11,12,10,3,10,8,12,9,7,11,3,10,12,11,3~8,12,3,12,12,12,10,9,9,9,4,5,11,7,4,4,4,9,6,11,11,11,13,13,13,13,3,3,3,5,5,5,4,13,11,4,3,11,10,9,11,9,11,4,3,13,3,9,11,12,3,13,9,3,11~9,5,6,11,11,11,8,4,4,4,4,7,12,12,12,3,12,13,3,3,3,10,11,13,13,13,5,5,5,9,9,9,8,5,8,3,11,13,4,11,5,11,8,11,4,5,4,11,13,11,8,11,5,7,11,5,11,6,11,4,12,11,5,11,8,5~9,9,9,12,5,5,5,13,11,12,12,12,7,6,6,6,8,6,5,9,4,10,11,11,11,3,3,3,3,4,4,4,13,13,13,8,8,8,13,8,13,8,3,13,3,5,11,6,13,4,8,4,8,13,4,6,8,7,5,8,11,13,12,6,11,13,3,4,13,5,12,4,6,3,4,8,4,11,5,11,12,7,13,12,13,5,8,4,13,10,13,12,4,12,5,13,6,5',
                    'total_bet_min' => '10.00',
                ];

                /* 마지막스핀 결과 로드 */
                if( $LASTSPIN !== NULL ) {
                    /* 윈라인 당첨 */
                    $objRes['c'] = $LASTSPIN->c ?? null;
                    $objRes['tw'] = $LASTSPIN->tw ?? null;
                    $objRes['w'] = $LASTSPIN->w ?? null;
                    // $objRes['action'] = $LASTSPIN->action ?? null;
                    $objRes['wlm_v'] = $LASTSPIN->wlm_v ?? null;
                    $objRes['wlm_p'] = $LASTSPIN->wlm_p ?? null;
                    $objRes['wlc_v'] = $LASTSPIN->wlc_v ?? null;
                    $objRes['reel_set'] = $LASTSPIN->reel_set ?? null;
                    $objRes['sa'] = $LASTSPIN->sa ?? null;
                    $objRes['sb'] = $LASTSPIN->sb ?? null;
                    $objRes['s'] = $LASTSPIN->s ?? $objRes['s'];

                    /* 프리스핀 */
                    $objRes['fs_opt_mask'] = $LASTSPIN->fs_opt_mask ?? null;
                    $objRes['na'] = $LASTSPIN->na ?? $objRes['na'];
                    $objRes['fs_opt'] = $LASTSPIN->fs_opt ?? null;
                    $objRes['fsmul'] = $LASTSPIN->fsmul ?? null;
                    $objRes['fsmax'] = $LASTSPIN->fsmax ?? null;
                    $objRes['is'] = $LASTSPIN->is ?? null;
                    $objRes['fswin'] = $LASTSPIN->fswin ?? null;
                    $objRes['rwd'] = $LASTSPIN->rwd ?? null;
                    $objRes['fs'] = $LASTSPIN->fs ?? null;
                    $objRes['fsres'] = $LASTSPIN->fsres ?? null;
                    $objRes['fsopt_i'] = $LASTSPIN->fsopt_i ?? null;
                    
                    /* 프리스핀 구매 */
                    $objRes['purtr'] = $LASTSPIN->purtr ?? null;
                    $objRes['puri'] = $LASTSPIN->puri ?? null;

                }
            }
            else if( $slotEvent['slotEvent'] == 'doSpin' ) 
            {
                $lastWILDCollection = $slotSettings->GetGameData($slotSettings->slotId . 'WILDCollection');

                /* 남은 프리스핀이 있을 경우 */
                $fsmax = $slotSettings->GetGameData($slotSettings->slotId . 'FSMax');
                $fs = $slotSettings->GetGameData($slotSettings->slotId . 'FSNext');
                $fsopt_i = $slotSettings->GetGameData($slotSettings->slotId . 'FSOpt');

                $fssticky_wildset = $slotSettings->GetGameData($slotSettings->slotId . 'FSStickyWILDSet');
                $fssticky_wildset = is_array($fssticky_wildset) ? $fssticky_wildset : [0, 1, 1, 1];

                $lines = $slotEvent['l'];       // 라인
                $bet = $slotEvent['c'];         // 베팅액

                /* 프리스핀 구매 */
                if (isset($slotEvent['pur'])) {
                    $slotEvent['slotEvent'] = 'fsStart';

                    $winType = 'bonus';                   // 보상방식
                    $_winAvaliableMoney = 0;        // 당첨금 한도
                }
                else {
                    /* 프리스핀 타입검사 */
                    if ($LASTSPIN !== NULL && $fsmax > 0) {
                        $slotEvent['slotEvent'] = $fsopt_i == 0 ? 'fsRaining' : 'fsSticky';
                    }

                    /* 스핀결과 결정 */
                    $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $bet * $lines, $lines);

                    $winType = $_spinSettings[0];                   // 보상방식
                    $_winAvaliableMoney = $_spinSettings[1];        // 당첨금 한도
                }

                /* 스캐터 생성갯수  */
                $defaultScatterCount = 0;
                if($winType == 'bonus'){                    
                    $defaultScatterCount = $slotSettings->GenerateScatterCount();  // 생성되어야할 Scatter갯수 결정
                }

                /* Balance 업데이트 */
                if ($slotEvent['slotEvent'] === 'fsStart') {
                    $allBet = $bet * $lines * 100;
                    $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);

                    /* 프리스핀 구매금액은 bonus에 충전 */
                    $slotSettings->SetBank(($slotEvent['slotEvent'] ?? ''), $allBet, 0);
                }
                else if ($slotEvent['slotEvent'] === 'fsRaining' || $slotEvent['slotEvent'] === 'fsSticky') {

                }
                else {
                    $allBet = $bet * $lines;
                    $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                    
                    $bankMoney = $allBet / 100 * $slotSettings->GetPercent();
                    $slotSettings->SetBank(($slotEvent['slotEvent'] ?? ''), $bankMoney);
                }
                $BALANCE = $slotSettings->GetBalance();
                
                /* 릴배치표 생성, 2천번 시행 */
                /*************************************************** */
                $overtry = false;           // 1500번이상 시행했을때 true

                for ($try=0; $try < 2000; $try++) { 
                    $totalWin = 0;
                    $strWinLine = '';
                    
                    $this->winLines = [];

                    /* 릴배치표 생성 */
                    if ($overtry) {
                        /* 더이상 자동릴생성은 하지 않고 최소당첨릴을 수동생성 */
                        $reels = $slotSettings->GetLimitedReelStrips($slotEvent['slotEvent'], $lastWILDCollection);
                    }
                    else {
                        $reels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent'], $defaultScatterCount, $lastWILDCollection);
                    }

                    $originReels = array_merge(array(), $reels);        // 변경되지 않은 릴배치표 보관, 프리스핀에 이용

                    $wildsCollection = [];
                    $newWILDCollection = [];        // 스티키 프리스핀에서 이용

                    /* 프리스핀일때 와일드심볼 생성 */
                    if ($slotEvent['slotEvent'] == 'fsRaining') {
                        $reels = $this->generateWILDs($reels, $slotEvent['slotEvent']);
                    }
                    else if ($slotEvent['slotEvent'] == 'fsSticky') {
                        $reels = $this->generateStickyWILDs($reels, $fssticky_wildset, $lastWILDCollection, $fs, $fsmax);

                        /* 이전 WILD심볼을 그대로 유지 */
                        $wildsCollection = $lastWILDCollection;
                    }

                    /* 와일드 멀티플라이어 할당 */ 
                    for ($i=1; $i <= $REELCOUNT; $i++) { 
                        $wilds = array_keys($reels["reel{$i}"], $WILD);

                        foreach ($wilds as $idx => $pos) {
                            if ($slotEvent['slotEvent'] == 'fsRaining') {
                                /* 레이닝 프리스핀 WILD 멀티플라이어는 최소 x2 */
                                $multiplier = $slotSettings->GetMultiValue(2);
                            }
                            else if ($slotEvent['slotEvent'] == 'fsSticky' && ($i == 2 || $i == 3)) {
                                /* 스티키 프리스핀 WILD 멀티플라이어 2,3번릴에서는 x2 */
                                $multiplier = 2;
                            }
                            else {
                                $multiplier = $slotSettings->GetMultiValue(1);
                            }
                            
                            $wildPos = $pos * 6 + $i - 1;

                            /* 스티키프리스핀은 와일드심볼 유지, */
                            if (!array_key_exists($wildPos, $wildsCollection)) {
                                $wildsCollection[$wildPos] = $multiplier;
                                $newWILDCollection[$wildPos] = $multiplier;
                            }
                        }
                    }

                    /* 윈라인 검사 */
                    for($r = 0; $r < 7; $r++){
                        if($reels['reel1'][$r] != $SCATTER && $reels['reel1'][$r] != 14){
                            $this->findZokbos($reels, $reels['reel1'][$r], 1, [$r * 6]);
                        }                        
                    }

                    /* 윈라인 응답빌드 */
                    $uniqueFirstSymbols = array_unique(
                        array_map(function ($line) {return $line['FirstSymbol'];}, $this->winLines));

                    foreach ($uniqueFirstSymbols as $idx => $symbol) {
                        $dupLines = array_filter($this->winLines, function($line) use ($symbol) {return $line['FirstSymbol'] === $symbol;});

                        // 윈라인 심볼위치배열
                        $symbols = array_unique(array_flatten(array_map(function($line) {return $line['Positions'];}, $dupLines)));
                        $strSymbols = implode(',', $symbols);

                        // 윈라인 당첨금
                        $winLineMoney = $this->calculateLineMoney($slotSettings, $dupLines, $wildsCollection) * $bet;

                        // 윈라인 응답
                        $dupCount = count($dupLines);
                        $firstLineKey = array_key_first($dupLines);
                        $strWinLine = "{$strWinLine};{$symbol}~{$winLineMoney}~{$dupCount}~{$dupLines[$firstLineKey]['RepeatCount']}~{$strSymbols}~l";
                        $strWinLine = trim($strWinLine, ";");

                        // 총보상
                        $totalWin += $winLineMoney;
                    }

                    /* 프리스핀 검사 */
                    $scattersCount = 0;
                    $_obf_scatterposes = [];
                    for($r = 0; $r < 6; $r++){
                        for( $k = 0; $k < 7; $k++ ) 
                        {
                            if( $reels['reel' . ($r + 1)][$k] == $SCATTER ) 
                            {                                
                                $scattersCount++;
                                array_push($_obf_scatterposes, $k * 6 + $r);
                            }
                        }
                    }
                    
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
                        $winType = 'none';
                        continue;
                    }

                    if( $scattersCount >= 3 && ( $winType != 'bonus' || $scattersCount != $defaultScatterCount)) 
                    {
                    }
                    // else if( $slotEvent['slotEvent'] == 'freespin' && $freeSpinNum > 0 && random_int(0, 100) < 90 ){
                    //     // 프리스핀에서 프리스핀당첨 확률 10%
                    // }
                    else if( $winType == 'bonus' && $scattersCount >= 3 ) 
                    {
                        $_obf_0D163F390C080D0831380D161E12270D0225132B261501 = $slotSettings->GetBank('bonus');
                        if( $_obf_0D163F390C080D0831380D161E12270D0225132B261501 < $_winAvaliableMoney ) 
                        {
                            $_winAvaliableMoney = $_obf_0D163F390C080D0831380D161E12270D0225132B261501;
                        }
                        else
                        {
                            break;
                        }
                    }
                    else if( $totalWin > 0 && $totalWin <= $_winAvaliableMoney && $winType == 'win' ) 
                    {
                        $_obf_0D163F390C080D0831380D161E12270D0225132B261501 = $slotSettings->GetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''));
                        if( $_obf_0D163F390C080D0831380D161E12270D0225132B261501 < $_winAvaliableMoney ) 
                        {
                            $_winAvaliableMoney = $_obf_0D163F390C080D0831380D161E12270D0225132B261501;
                        }
                        else
                        {
                            break;
                        }
                    }
                    else if( $totalWin == 0 && $winType == 'none' ) 
                    {
                        break;
                    }
                }

                /* 응답 구성 */
                /*************************************************** */
                $spinType = $totalWin > 0 ? 'c' : 's';
                
                /* lastReel 구성 */
                $lastReel = [];
                for($k = 0; $k < 7; $k++){
                    for($j = 1; $j <= 6; $j++){
                        $lastReel[($j - 1) + $k * 6] = $reels['reel'.$j][$k];

                        if (isset($originReels)) {
                            $originLastReel[($j - 1) + $k * 6] = $originReels['reel'.$j][$k];
                        }
                    }
                }

                $strLastReel = implode(',', $lastReel);
                $strReelSa = $reels['reel1'][7].','.$reels['reel2'][7].','.$reels['reel3'][7].','.$reels['reel4'][7].','.$reels['reel5'][7].','.$reels['reel6'][7];
                $strReelSb = $reels['reel1'][-1].','.$reels['reel2'][-1].','.$reels['reel3'][-1].','.$reels['reel4'][-1].','.$reels['reel5'][-1].','.$reels['reel6'][-1];               

                $objRes = [
                    'tw' => $totalWin,
                    'balance' => $BALANCE,
                    'index' => $slotEvent['index'],
                    'balance_cash' => $BALANCE,
                    'reel_set' => $reels['id'],          
                    'balance_bonus' => '0.00',
                    'na' => $spinType,
                    'stime' => floor(microtime(true) * 1000),
                    'sa' => $strReelSa,
                    'sb' => $strReelSb,
                    'sh' => '7',
                    'c' => $slotEvent['c'],
                    'sver' => '5',
                    'counter' => ((int)$slotEvent['counter'] + 1),
                    'l' => $slotEvent['l'],
                    's' => $strLastReel,
                    'w' => $totalWin
                ];

                /* Needed for init */
                // $objRes['action'] = $slotEvent['action'];       // ?? 재검토

                /* 당첨금 */
                if ($strWinLine !== '') {
                    $objRes['wlc_v'] = $strWinLine;
                }

                /* 와일드심볼 멀티플라이어 정보 */
                if (count($wildsCollection) > 0) {
                    /* 1배 WILD는 제외 */
                    $wilds = array_filter($wildsCollection, function ($multiplier, $pos) {
                        return $multiplier != 1;
                    }, ARRAY_FILTER_USE_BOTH);

                    $objRes['wlm_p'] = implode(",", array_keys($wilds));
                    $objRes['wlm_v'] = implode(",", array_values($wilds));
                }

                /* 프리스핀 준비 */
                if ($scattersCount >= 3) {
                    $objRes['fs_opt_mask'] = 'fs,m,msk';
                    $objRes['na'] = 'fso';
                    
                    $fsRainingMax = $slotSettings->freeSpinTable[0][$scattersCount];
                    $fsStickyMax = $slotSettings->freeSpinTable[1][$scattersCount];
                    $objRes['fs_opt'] = "{$fsRainingMax},1,0~{$fsStickyMax},1,0";

                    /* 프리스핀 구매 */
                    if (isset($slotEvent['pur'])) {
                        $objRes['purtr'] = 1;
                        $objRes['puri'] = 0;
                    }
                }

                /* 프리스핀 중 */
                if ($slotEvent['slotEvent'] === 'fsRaining' || $slotEvent['slotEvent'] === 'fsSticky') {
                    $fsmax = $slotSettings->GetGameData($slotSettings->slotId . 'FSMax');
                    $fs = $slotSettings->GetGameData($slotSettings->slotId . 'FSNext');
                    $fsopt_i = $slotSettings->GetGameData($slotSettings->slotId . 'FSOpt');

                    /* 프리스핀 시작밸런스 로드 */
                    $BALANCE = $slotSettings->GetGameData($slotSettings->slotId . 'FSStartBalance');
                    
                    $objRes['tw'] = ($LASTSPIN->tw ?? 0) + $totalWin;
                    $objRes['balance'] = $BALANCE;
                    $objRes['balance_cash'] = $BALANCE;
                    $objRes['fsopt_i'] = $fsopt_i;
                    $objRes['is'] = implode(",", $originLastReel);

                    /* 와일드심볼이 있다면 릴배치표 치환*/
                    if (count($wildsCollection) > 0) {
                        if ($slotEvent['slotEvent'] === 'fsSticky') {
                            $res = $this->buildfsStickyResponse($wildsCollection, $newWILDCollection, $lastReel, $fsmax <= $fs);
                        }
                        else if ($slotEvent['slotEvent'] === 'fsRaining') {
                            $res = $this->buildfsRainingResponse($wildsCollection, $lastReel);
                        }

                        $objRes = array_merge($objRes, $res);
                    }

                    if ($fsmax > $fs) {
                        /* 프리스핀중에는 스핀타입이 항상 s */
                        $objRes['na'] = 's';
                        $objRes['fsmul'] = 1;
                        $objRes['fsmax'] = $fsmax;
                        $objRes['fs'] = $fs + 1;

                        /* 프리스핀 당첨금 */
                        $objRes['fswin'] = $objRes['tw'];
                        $objRes['fsres'] = $objRes['tw'];

                        $slotSettings->SetGameData($slotSettings->slotId . 'FSNext', $fs + 1);
                    }
                    else if ($fsmax <= $fs) {
                        /* 프리스핀 완료 */
                        $objRes['na'] = $objRes['tw'] > 0 ? 'c' : 's';

                        $objRes['fs_total'] = $fsmax;
                        $objRes['fswin_total'] = $objRes['tw'];
                        $objRes['fsmul_total'] = 1;
                        $objRes['fsend_total'] = 1;
                        $objRes['fsres_total'] = $objRes['tw'];

                        $slotSettings->SetGameData($slotSettings->slotId . 'FSMax', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FSNext', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FSOpt', 0);
                    }
                    else {
                        /* throw exception */
                    }
                }
                /******************************************************* */

                /* 스핀 저장 */
                if( $totalWin > 0) 
                {
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }

                $slotSettings->SetGameData($slotSettings->slotId . 'ScattersCount', $scattersCount);

                if ($slotEvent['slotEvent'] === 'fsSticky') {
                    $slotSettings->SetGameData($slotSettings->slotId . 'WILDCollection', $wildsCollection);
                    $slotSettings->SetGameData($slotSettings->slotId . 'NewWILDCollection', $newWILDCollection);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FSStickyWILDSet', $fssticky_wildset);
                }
                else {
                    $slotSettings->SetGameData($slotSettings->slotId . 'WILDCollection', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'NewWILDCollection', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FSStickyWILDSet', []);
                }

                $_GameLog = json_encode($objRes);
                $slotSettings->SaveLogReport($_GameLog, $bet * $lines, $slotEvent['l'], $totalWin, $slotEvent['slotEvent']);
            }
            else if( $slotEvent['slotEvent'] == 'doCollect') 
            {
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
            else if( $slotEvent['slotEvent'] == 'doFSOption') {
                $scattersCount = $slotSettings->GetGameData($slotSettings->slotId . 'ScattersCount');

                if( $LASTSPIN === NULL || $scattersCount < 3) {
                    // throw exception
                    return '';
                }

                $fsRainingMax = $slotSettings->freeSpinTable[0][$scattersCount];
                $fsStickyMax = $slotSettings->freeSpinTable[1][$scattersCount];
                $fs_opt = "{$fsRainingMax},1,0~{$fsStickyMax},1,0";

                $fsType = $slotEvent['ind'];      // 0, 1 : Raining / Sticky
                $fsmax = $fsType ? $fsStickyMax : $fsRainingMax;

                $fs = 1;

                /* 프리스핀 모드선택 */
                $objRes = [
                    'fsmul' => '1',
                    'fs_opt_mask' => 'fs,m,msk',
                    'balance' => $BALANCE,
                    'fsmax' =>  $fsmax,
                    'index' => $slotEvent['index'],
                    'balance_cash' => $BALANCE,
                    'balance_bonus' => '0',
                    'na' => 's',
                    'fswin' => '0',
                    'stime' => floor(microtime(true) * 1000),
                    'fs' => $fs,
                    // 'fs_opt' => '15,1,0~7,1,0',     // 스핀갯수 설정
                    'fs_opt' => $fs_opt,
                    'fsres' => '0',
                    'sver' => '5',
                    'counter' => ((int)$slotEvent['counter'] + 1),
                    'fsopt_i' => $fsType,
                ];

                /* 스핀 저장 */
                $slotSettings->SetGameData($slotSettings->slotId . 'FSMax', $fsmax);
                $slotSettings->SetGameData($slotSettings->slotId . 'FSNext', $fs);
                $slotSettings->SetGameData($slotSettings->slotId . 'FSOpt', $fsType);
                $slotSettings->SetGameData($slotSettings->slotId . 'FSStartBalance', $BALANCE);

                /* 스티키 프리스핀 와일드셋 id 랜덤지정, 8셋 */
                if ($fsType == 1) {
                    $wildCountProbabilityMap =[
                        [0, 1, 1, 1],
                        [0, 1, 1, 2],
                        [0, 1, 1, 0, 1],
                        [0, 1, 1, 0, 2],
                        [0, 1, 1, 1, 1],
                        [0, 1, 2],
                        [0, 1, 2, 1],
                        [0, 2, 1],
                        [0, 2, 1, 0, 1],
                        [0, 2, 2],
        
                        [0, 1, 2, 2]
                    ];
        
                    $wildSetId = random_int(0, 10);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FSStickyWILDSet', $wildCountProbabilityMap[$wildSetId]);
                }
            }

            $slotSettings->SaveGameData();
            \DB::commit();
            return $this->toResponse($objRes);
        }

        public function findZokbos($reels, $firstSymbol, $repeatCount, $positions){
            $WILD = '2';
            $bPathEnded = true;
            if($repeatCount < 6){
                for($r = 0; $r < 7; $r++){
                    if($firstSymbol == $reels['reel'.($repeatCount + 1)][$r] || $reels['reel'.($repeatCount + 1)][$r] == $WILD){
                        $this->findZokbos($reels, $firstSymbol, $repeatCount + 1, array_merge($positions, [($repeatCount + $r * 6)]));
                        $bPathEnded = false;
                    }
                }
            }
            if($bPathEnded == true){
                if($repeatCount >= 3){
                    $winLine = [];
                    $winLine['FirstSymbol'] = $firstSymbol;
                    $winLine['RepeatCount'] = $repeatCount;
                    $winLine['Positions'] = $positions;
                    array_push($this->winLines, $winLine);
                }
            }
        }

        public function calculateLineMoney($slotSettings, $lines, $wildsCollection) {
            /* 윈라인 보상금 계산, WILD배당율 계산포함 */
            $linesMoney = array_map(function($line) use ($slotSettings, $wildsCollection) {
                $symbol = $line['FirstSymbol'];
                $repeatCount = $line['RepeatCount'];

                if (count($wildsCollection) > 0) {
                    /* 윈라인에 포함된 WILD 배당율 */
                    $multiplier = array_reduce($line['Positions'], function ($carry, $pos) use ($wildsCollection) {
                        $carry = isset($wildsCollection[$pos]) ? $carry * $wildsCollection[$pos] : $carry;
                        return $carry;
                    }, 1);
                }
                else {
                    /* WILD 가 없을때 */
                    $multiplier = 1;
                }

                return $slotSettings->Paytable[$symbol][$repeatCount] * $multiplier;
            }, $lines);

            return array_sum($linesMoney);
        }
        public function generateStickyWILDs($reels, $wildSet, $lastWILDCollection = [], $fs, $fsmax) {
            $REELCOUNT = 6;

            /* 이전 WILD 심볼 복원 */
            foreach ($lastWILDCollection as $pos => $multiplier) {
                // 릴에 배치가능한 심볼갯수가 변하는 경우 WILD 심볼 위치 조정
                $reelPos = intdiv($pos, $REELCOUNT);
                $reelId = $pos % $REELCOUNT + 1;
                $reels["reel{$reelId}"][$reelPos] = 2;

                // 이미 배치된 WILD는 제외
                $wildSet[$reelId - 1] -= 1;
            }

            /* 한번에 생성할 WILD 심볼갯수 랜덤선택, 최대 2개 */
            if ($fs == 1) {
                $newWILDCount = random_int(0, 1);
            }
            else {
                $newWILDCount = random_int(1, 2);
            }

            for ($i=0; $i < $newWILDCount; $i++) { 
                /* WILD심볼을 이미 갯수이상 배치했다면 */
                if (array_sum($wildSet) <= 0) {
                    break;
                }   

                /* WILD를 배치할 릴선택 */
                $availableReels = array_where($wildSet, function ($count, $key) {
                    return $count > 0;
                });

                if (count($availableReels) === 0) {
                    continue;
                }

                $reelId = array_rand($availableReels) + 1;

                /* WILD를 배치할 위치선택 */ 
                $availablePoses = array_where($reels["reel{$reelId}"], function ($symbol, $key) {
                    return $symbol != 14 && $symbol != 2 && $key != -1 && $key != 7;
                });

                if (count($availablePoses) === 0 ) {
                    continue;
                }

                $randomPos = array_rand($availablePoses);            

                $reels["reel{$reelId}"][$randomPos] = 2;

                $wildSet[$reelId - 1] -= 1;
            }
            
            return $reels;
        }

        public function generateWILDs($reels, $fsType, $lastWILDCollection = []) {
            if ($fsType == 'fsSticky') {
                /* Sticky 프리스핀일때 WILD 심볼갯수당 확률 */
                $wildsCountProbabilityMap = [
                    0 => 5,
                    1 => 5,
                    2 => 10,
                    3 => 45,
                    4 => 30,
                    5 => 4,
                    6 => 1
                ];

                /* 확룔에 기초한 WILD 심볼갯수 결정 */
                $wildCount = $this->getRandomValue($wildsCountProbabilityMap);

                /* 스티키 프리스핀 경우 이전 스핀결과의 WILD 심볼 복귀 */
                $lastWILDCount = count($lastWILDCollection);

                $REELCOUNT = 6;

                /* 이전 WILD 심볼 복원 */
                foreach ($lastWILDCollection as $pos => $multiplier) {
                    // 릴에 배치가능한 심볼갯수가 변하는 경우 WILD 심볼 위치 조정
                    $reelPos = intdiv($pos, $REELCOUNT);
                    $reelId = $pos % $REELCOUNT + 1;
                    $reels["reel{$reelId}"][$reelPos] = 2;
                }

                /* 이미 생성된 WILD 갯수가 더 많다면 더 생성하지 않는다 */
                if ($wildCount <= $lastWILDCount) {
                    return $reels;
                }

                /* 새로 생성해야 될 WILD 갯수 */
                $wildCount = $wildCount - $lastWILDCount;

                /* 한번에 생성되는 WILD 최대갯수 제한 */
                $wildCount = random_int(1, $wildCount) % 3;
            }
            else {
                /* 일반스핀, Raining 프리스핀일때 WILD 심볼갯수당 확률 */
                $wildsCountProbabilityMap = [
                    0 => 30,
                    1 => 30,
                    2 => 20,
                    3 => 10,
                    4 => 6,
                    5 => 3,
                    6 => 1
                ];

                /* 확룔에 기초한 WILD 심볼갯수 결정 */
                $wildCount = $this->getRandomValue($wildsCountProbabilityMap);
            }
            
            /* WILD 심볼 위치 결정 */
            for ($i=0; $i < $wildCount; $i++) { 
                /* 2번부터 5번사이 랜덤릴 */
                $randomReelId = random_int(2, 5);       
                
                /* 릴의 랜덤 위치 */
                $availablePoses = array_where($reels["reel{$randomReelId}"], function ($symbol, $key) {
                    return $symbol != 14 && $symbol != 2 && $key != -1 && $key != 7;
                });

                if (count($availablePoses) === 0 ) {
                    continue;
                }

                $randomPos = array_rand($availablePoses);            

                $reels["reel{$randomReelId}"][$randomPos] = 2;
            }

            return $reels;
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

        public function buildfsRainingResponse($wildsCollection, $lastReel) {
            // 2x, 3x 와일드
            $fsWildSymbols = [
                // 1 => 2,
                2 => 15,
                3 => 16
            ];  

            /* 릴배치표에서 2x, 3x 와일드 심볼값들을 이상의 값으로 치환. 15 : 2x, 16: 3x WILD*/
            $res = "";
            foreach ($fsWildSymbols as $multiplier => $value) {
                $wildPosOfSameMultipliers = array_keys($wildsCollection, $multiplier);

                if (count($wildPosOfSameMultipliers) === 0) {
                    continue;
                }

                $strPoses = implode(",", $wildPosOfSameMultipliers);
                $res = "{$res};{$value}~{$strPoses}";

                foreach ($wildPosOfSameMultipliers as $pos) {
                    $lastReel[$pos] = $value;
                }
            }
            
            return [
                'rwd' => trim($res, ";"),
                's' => implode(",", $lastReel)
            ];
        }

        public function buildfsStickyResponse($wildsCollection, $newWILDCollection, $lastReel, $fsEnded) {
           // 2x, 3x 와일드
           $fsWildSymbols = [
                // 1 => 2,
                2 => 15,
                3 => 16
            ];  

            $rwd = "";
            $sty = "";
            foreach ($fsWildSymbols as $multiplier => $value) {
                /* rwd 빌드 */
                $newWILDPosOfSameMultipliers = array_keys($newWILDCollection, $multiplier);
                
                if (count($newWILDPosOfSameMultipliers) > 0) {
                    $strPoses = implode(",", $newWILDPosOfSameMultipliers);
                    $rwd = "{$rwd};{$value}~{$strPoses}";
                }

                /* 릴배치표에서 2x, 3x 와일드 심볼값들을 이상의 값으로 치환. 15 : 2x, 16: 3x WILD*/
                $wildPosOfSameMultipliers = array_keys($wildsCollection, $multiplier);
                
                foreach ($wildPosOfSameMultipliers as $pos) {
                    $lastReel[$pos] = $value;
                }
            }

            /* sty 빌드 */
            $wildPositions = array_keys($wildsCollection);
            foreach ($wildPositions as $pos) {
                $sty= $fsEnded ? "{$sty}~{$pos},-1" : "{$sty}~{$pos},{$pos}";
            }
            
            return [
                'sty' => trim($sty, "~"),
                'rwd' => trim($rwd, ";"),
                'puri' => 0,
                's' => implode(",", $lastReel)
            ];
        }

        public function toResponse($obj) {
            $response = '';
            foreach ($obj as $key => $value) {
                if ($value !== null) {
                    $response = "{$response}&{$key}={$value}";
                }
            }

            return trim($response, "&");
        }
    }
}
