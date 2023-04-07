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
        foreach ($this->bet_data as $index => $deal)
        {
            if ($deal['type'] == 'shop')
            {
                $shop = \VanguardLTE\Shop::lockForUpdate()->where('id',$deal['shop_id'])->first();               
                
                $shop->update([
                    'deal_balance' => $shop->deal_balance + $deal['deal_profit'], 
                    'mileage' => $shop->mileage +  $deal['mileage'],
                    'ggr_balance' => $shop->ggr_balance + $deal['ggr_profit'], 
                    'ggr_mileage' => $shop->ggr_mileage +  $deal['ggr_mileage'],
                    $type . '_garant_deal' => (($count_miss>0)?$garant_deal:0)
                ]);
            }
            else
            {
                $partner = \VanguardLTE\User::lockForUpdate()->where('id',$deal['partner_id'])->first();
                $partner->update([
                    'deal_balance' => $partner->deal_balance + $deal['deal_profit'], 
                    'mileage' => $partner->mileage +  $deal['mileage'],
                    'ggr_balance' => $partner->ggr_balance + $deal['ggr_profit'], 
                    'ggr_mileage' => $partner->ggr_mileage +  $deal['ggr_mileage'],
                ]);
            }

        }
        \VanguardLTE\ShareBetLog::insert($this->deal_data);
    }
    public function failed(\Exception $exception)
    {
        Log::channel('monitor_game')->info($exception->getMessage());
    }
}
