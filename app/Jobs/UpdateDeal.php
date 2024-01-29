<?php

namespace VanguardLTE\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
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

    public function log($errcode)
    {
        $strlog = '';
        $strlog .= "\n";
        $strlog .= date("Y-m-d H:i:s") . ' ' . $errcode;
        $strlog .= ' ############################################### ';
        $strlog .= "\n";
        $strinternallog = '';
        if( file_exists(storage_path('logs/') . 'dealInternal.log') ) 
        {
            $strinternallog = file_get_contents(storage_path('logs/') . 'dealInternal.log');
        }
        file_put_contents(storage_path('logs/') . 'dealInternal.log', $strinternallog . $strlog);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function getNewDealCount($shop, $type)
    {

        $infoshop = $shop->info;
        if (count($infoshop) == 0)
        {
            return 0;
        }
        $inf = null;
        foreach ($infoshop as $info)
        {
            $inf = $info->info;
            if ($inf && $inf->roles == $type)
            {
                break;
            }
        }
        if ($inf == null)
        {
            return 0;
        }
        
        $win = explode(',', $inf->text);
        if ($inf->text == '')
        {
            //generate new deal counts
            $totaldeal = $inf->title; // totalcount
            $totalmiss = $inf->link; // total miss
            $win = rand_region_numbers($totaldeal, $totalmiss);
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
        $dealjobData = null;
        if (isset($this->deal_data['deal']))
        {
            $dealjobData = $this->deal_data['deal'];
        }
        else
        {
            $dealjobData = $this->deal_data;
        }

        foreach ($dealjobData as $index => $deal)
        {
            if ($deal['type'] == 'shop')
            {
                $shop = \VanguardLTE\Shop::lockForUpdate()->where('id',$deal['shop_id'])->first();               
                //check miss deal

                $type = ($shop->deal_percent==$deal['deal_percent'])?'slot':'table';
                $garant_deal = $shop->{$type .'_garant_deal'};
                $count_miss = $shop->{$type.'_miss_deal'};
                $garant_deal++;
                if ($count_miss>0 && $count_miss <= $garant_deal){ //miss deal
                    $shop->update([
                        $type . '_miss_deal' => $this->getNewDealCount($shop, $type),
                        $type . '_garant_deal' => 0
                    ]);
                    return;
                }
                
                $shop->update([
                    'deal_balance' => $shop->deal_balance + $deal['deal_profit'], 
                    'mileage' => $shop->mileage +  $deal['mileage'],
                    $type . '_garant_deal' => (($count_miss>0)?$garant_deal:0)
                ]);
            }
            else
            {
                $partner = \VanguardLTE\User::lockForUpdate()->where('id',$deal['partner_id'])->first();
                $partner->update([
                    'deal_balance' => $partner->deal_balance + $deal['deal_profit'], 
                    'mileage' => $partner->mileage +  $deal['mileage'],
                ]);
            }
        }
        \VanguardLTE\DealLog::insert($dealjobData);
    }
    public function failed(\Exception $exception)
    {
        Log::channel('monitor_game')->info($exception->getMessage());
    }
}
