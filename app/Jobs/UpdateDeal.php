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
    public function getNewDealCount($shop)
    {
        if (!$shop->info)
        {
            return 0;
        }
        
        $inf = $shop->info->dealinfo;
        if (!$inf)
        {
            return 0;
        }

        $win = explode(',', $inf->text);
        if (count($win) == 0)
        {
            //generate new deal counts
            $totaldeal = $inf->title; // totalcount
            $totalmiss = $inf->link; // total miss
            for ($i=0;$i<$totalmiss-1;$i++)
            {
                $c = rand(0, $totaldeal);
                $win[] = $c;
                $totaldeal = $totaldeal - $c;
            }
            $win[] = $totaldeal;
        }
        
        $number = rand(0, count($win) - 1);
        $newDealCount = $win[$number];
        //remove selected deal count
        array_splice($win, $number, 1); 
        if (count($win) > 0)
        {
            $inf->text = implode(',', $win);
        }
        else
        {
            $inf->text = '';
        }
        $inf->save();

        return $newDealCount;
    }

    public function handle()
    {
        foreach ($this->deal_data as $index => $deal)
        {
            if ($deal['type'] == 'shop')
            {
                $shop = \VanguardLTE\Shop::lockForUpdate()->where('id',$deal['shop_id'])->first();
                //check miss deal
                $garant_deal = $shop->garant_deal;
                $count_miss = $shop->miss_deal;
                $garant_deal++;
                if ($count_miss>0 && $count_miss <= $garant_deal){ //miss deal
                    $shop->update([
                        'count_miss' => $this->getNewDealCount($shop),
                        'garant_deal' => 0
                    ]);
                    return;
                }
                
                $shop->update([
                    'deal_balance' => $shop->deal_balance + $deal['deal_profit'], 
                    'mileage' => $shop->mileage +  $deal['mileage'],
                    'ggr_balance' => $shop->ggr_balance + $deal['ggr_profit'], 
                    'ggr_mileage' => $shop->ggr_mileage +  $deal['ggr_mileage'],
                    'garant_deal' => $shop->garant_deal + 1
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
        \VanguardLTE\DealLog::insert($this->deal_data);
    }
}
