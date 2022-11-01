<?php

namespace VanguardLTE\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateDeal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $deal_data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->deal_data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \VanguardLTE\DealLog::insert($this->deal_data);
        foreach ($this->deal_data as $index => $deal)
        {
            if ($deal['type'] == 'shop')
            {
                $shop = \VanguardLTE\Shop::lockForUpdate()->where('id',$deal['shop_id'])->first();
                $shop->update([
                    'count_deal_balance' => $shop->count_deal_balance + $deal['deal_profit'], 
                    'count_mileage' => $shop->count_mileage +  $deal['mileage'],
                    'deal_balance' => $shop->deal_balance + $deal['deal_profit'], 
                    'mileage' => $shop->mileage +  $deal['mileage'],
                    'ggr_balance' => $shop->ggr_balance + $deal['ggr_profit'], 
                    'ggr_mileage' => $shop->ggr_mileage +  $deal['ggr_mileage'],
                ]);
            }
            else
            {
                $partner = \VanguardLTE\User::lockForUpdate()->where('id',$deal['partner_id'])->first();
                $partner->update([
                    'count_deal_balance' => $partner->count_deal_balance + $deal['deal_profit'], 
                    'count_mileage' => $partner->count_mileage +  $deal['mileage'],
                    'deal_balance' => $partner->deal_balance + $deal['deal_profit'], 
                    'mileage' => $partner->mileage +  $deal['mileage'],
                    'ggr_balance' => $partner->ggr_balance + $deal['ggr_profit'], 
                    'ggr_mileage' => $partner->ggr_mileage +  $deal['ggr_mileage'],
                ]);
            }

        }
    }
}
