<?php

namespace VanguardLTE\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OutDeal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = \VanguardLTE\User::lockForUpdate()->where('id',$this->data['user_id'])->first();
        if (!$user)
        {
            return -1;
        }
        $summ = $this->data['sum'];
        if ($summ > 0) {
            //out balance from master
            $master = $user->referral;
            while ($master!=null && !$master->isInoutPartner())
            {
                $master = $master->referral;
            }
            if ($master == null)
            {
                return -2;
            }
            
            if ($master->balance < $summ)
            {
                return -3;
            }
            $master->update(
                ['balance' => $master->balance - $summ ]
            );
            $master = $master->fresh();

            if ($user->hasRole('manager'))
            {
                $shop = \VanguardLTE\Shop::lockForUpdate()->where('id',$user->shop_id)->first();
                $real_deal_balance = $shop->deal_balance - $shop->mileage;
                if ($real_deal_balance < $summ)
                {
                    return -4;
                }
                $old = $shop->balance;
                $shop->balance = $shop->balance + $summ;
                $shop->deal_balance = $real_deal_balance - $summ;
                $shop->mileage = 0;
                $shop->save();
                $shop = $shop->fresh();
                \VanguardLTE\ShopStat::create([
                    'user_id' => $master->id,
                    'type' => 'deal_out',
                    'sum' => $summ,
                    'old' => $old,
                    'new' => $shop->balance,
                    'balance' => $master->balance,
                    'shop_id' => $shop->id,
                    'date_time' => \Carbon\Carbon::now()
                ]);
            }
            else
            {
                $real_deal_balance = $user->deal_balance - $user->mileage;
                if ($real_deal_balance < $summ)
                {
                    return -4;
                }
                $old = $user->balance;
                $user->balance = $user->balance + $summ;
                $user->deal_balance = $real_deal_balance - $summ;
                $user->mileage = 0;
                $user->save();
                $user = $user->fresh();
       
                \VanguardLTE\Transaction::create([
                    'user_id' => $user->id,
                    'payeer_id' => $master->id,
                    'system' => $user->username,
                    'type' => 'deal_out',
                    'summ' => $summ,
                    'old' => $old,
                    'new' => $user->balance,
                    'balance' => $master->balance,
                    'shop_id' => 0
                ]);
            }
        }
        return 0;
    }
}
