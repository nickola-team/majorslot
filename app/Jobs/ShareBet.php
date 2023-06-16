<?php

namespace VanguardLTE\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
class ShareBet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $bet_data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->bet_data = $data;
    }

    public function log($errcode)
    {
        $strlog = '';
        $strlog .= "\n";
        $strlog .= date("Y-m-d H:i:s") . ' ' . $errcode;
        $strlog .= ' ############################################### ';
        $strlog .= "\n";
        $strinternallog = '';
        if( file_exists(storage_path('logs/') . 'sharebetInternal.log') ) 
        {
            $strinternallog = file_get_contents(storage_path('logs/') . 'sharebetInternal.log');
        }
        file_put_contents(storage_path('logs/') . 'sharebetInternal.log', $strinternallog . $strlog);
    }

    /**
     * Execute the job.
     *
     * @return void
     */

    public function handle()
    {
        $sharejobData = null;
        if (isset($this->bet_data['share']))
        {
            $sharejobData = $this->bet_data['share'];
            if ($sharejobData){
                $statgame = \VanguardLTE\StatGame::where('id', $sharejobData['stat_id'])->first();
                $sharebetinfo = \VanguardLTE\ShareBetInfo::where(['partner_id' => $sharejobData['partner_id'], 'share_id' => $sharejobData['share_id'], 'category_id' => $sharejobData['category_id']])->first();
                if ($statgame && $sharebetinfo)
                {
                    $ct = $statgame->category;
                    $gamedetail = null;
                    if ($ct->provider != null)
                    {
                        if (method_exists('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($ct->provider) . 'Controller','getgamedetail'))
                        {
                            $gamedetail = call_user_func('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($ct->provider) . 'Controller::getgamedetail', $statgame);
                        }
                    }
                    $limit_data = json_decode($sharebetinfo->limit_info, true);
                    if ($gamedetail && isset(\VanguardLTE\ShareBetInfo::BET_TYPES[strtolower($gamedetail['type'])]))
                    {
                        $betlimit = 0;
                        $winlimit = 0;
                        foreach ($gamedetail['bets'] as $userbet)
                        {
                            $keyname = $gamedetail['type'] . '_' . $userbet['betValue'];
                            $betAmount = $userbet['betAmount'];
                            $winAmount = $userbet['winAmount'];
                            if (isset($limit_data[$keyname]) && $limit_data[$keyname] > 0)
                            {
                                if ($limit_data[$keyname] < $userbet['betAmount'])
                                {
                                    $betAmount = $limit_data[$keyname];
                                    if ($userbet['winAmount'] > 0)
                                    {
                                        $winAmount = $betAmount * $userbet['odds'];
                                    }
                                }
                            }
                            $betlimit = $betlimit + $betAmount;
                            $winlimit = $winlimit + $winAmount;
                        }

                        if ($betlimit != $sharejobData['bet']) // this is shared bet
                        {
                            $sharejobData['betlimit'] = $betlimit;
                            $sharejobData['winlimit'] = $winlimit;
                            $sharejobData['deal_limit'] = $betlimit * $sharejobData['deal_percent'] / 100;

                            $deal_share = ($sharejobData['bet'] - $betlimit) * $sharejobData['deal_percent'] / 100;

                            $sharejobData['deal_share'] = $deal_share;

                            $partner = \VanguardLTE\User::lockForUpdate()->where('id', $sharejobData['partner_id'])->first();
                            $partner->update(
                                [
                                    'deal_balance' => $partner->deal_balance + $deal_share, 
                                ]
                                );
                            \VanguardLTE\ShareBetlog::insert($sharejobData);
                        }
                    }
                }   
            }
        }
    }
    public function failed($exception)
    {
        $this->log($exception->getMessage());
    }
}
