<?php

namespace VanguardLTE\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateSummary implements ShouldQueue
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
        $count = count($this->deal_data);
        foreach ($this->deal_data as $index => $deal)
        {
            $partner = \VanguardLTE\User::where('id',$deal['partner_id'])->first();

            if (empty($deal['category_id']) || empty($deal['game_id']))
            {
                return;
            }
            //update category summary table
            $date = explode(' ', $deal['date_time'])[0];

            $cat_summary = \VanguardLTE\CategorySummary::lockForUpdate()->where([
                'user_id' => $deal['partner_id'], 
                'category_id' => $deal['category_id'],
                'date' => $date,
                'type' => 'today',
                ])->first();
            if ($cat_summary)
            {
                $cat_summary->update(
                    [
                        'totalbet' => $cat_summary->totalbet + $deal['bet'],
                        'totalwin' => $cat_summary->totalwin + $deal['win'],
                        'totalcount' => $cat_summary->totalcount + 1,
                        'total_deal' => $cat_summary->total_deal + $deal['deal_profit'],
                        'total_mileage' => $cat_summary->total_mileage + $deal['mileage'],
                        'total_ggr' => $cat_summary->total_ggr + $deal['ggr_profit'],
                        'total_ggr_mileage' => $cat_summary->total_ggr_mileage + $deal['ggr_mileage'],
                    ]
                );
            }
            else
            {
                \VanguardLTE\CategorySummary::create(
                    [
                        'user_id' => $deal['partner_id'], 
                        'category_id' => $deal['category_id'],
                        'date' => $date,
                        'type' => 'today',
                        'totalbet' => $deal['bet'],
                        'totalwin' => $deal['win'],
                        'totalcount' => 1,
                        'total_deal' => $deal['deal_profit'],
                        'total_mileage' => $deal['mileage'],
                        'total_ggr' => $deal['ggr_profit'],
                        'total_ggr_mileage' => $deal['ggr_mileage'],
                    ]
                    );
            }

            //update daily summary table

            $daily_summary = \VanguardLTE\DailySummary::lockForUpdate()->where([
                'user_id' => $deal['partner_id'], 
                'date' => $date,
                'type' => 'today',
                ])->first();
            if ($daily_summary)
            {
                $daily_summary->update(
                    [
                        'totalbet' => $daily_summary->totalbet + $deal['bet'],
                        'totalwin' => $daily_summary->totalwin + $deal['win'],
                        'total_deal' => $daily_summary->total_deal + $deal['deal_profit'],
                        'total_mileage' => $daily_summary->total_mileage + $deal['mileage'],
                        'total_ggr' => $daily_summary->total_ggr + $deal['ggr_profit'],
                        'total_ggr_mileage' => $daily_summary->total_ggr_mileage + $deal['ggr_mileage'],
                    ]
                );
            }
            else
            {
                \VanguardLTE\DailySummary::create(
                    [
                        'user_id' => $deal['partner_id'], 
                        'date' => $date,
                        'type' => 'today',
                        'totalbet' => $deal['bet'],
                        'totalwin' => $deal['win'],
                        'total_deal' => $deal['deal_profit'],
                        'total_mileage' => $deal['mileage'],
                        'total_ggr' => $deal['ggr_profit'],
                        'total_ggr_mileage' => $deal['ggr_mileage'],
                        'shop_id' => $partner->shop_id,
                    ]
                    );
            }

            if ($index == $count - 1) //record comaster and admin
            {
                $parent = $partner->referral;
                while ($parent != null)
                {
                    $cat_summary = \VanguardLTE\CategorySummary::lockForUpdate()->where([
                        'user_id' => $parent->id, 
                        'category_id' => $deal['category_id'],
                        'date' => $date,
                        'type' => 'today',
                        ])->first();
                    if ($cat_summary)
                    {
                        $cat_summary->update(
                            [
                                'totalbet' => $cat_summary->totalbet + $deal['bet'],
                                'totalwin' => $cat_summary->totalwin + $deal['win'],
                                'totalcount' => $cat_summary->totalcount + 1,
                                'total_deal' => $cat_summary->total_deal,
                                'total_mileage' => $cat_summary->total_mileage + $deal['deal_profit'],
                                'total_ggr' => $cat_summary->total_ggr,
                                'total_ggr_mileage' => $cat_summary->total_ggr_mileage + $deal['ggr_profit'],
                            ]
                        );
                    }
                    else
                    {
                        \VanguardLTE\CategorySummary::create(
                            [
                                'user_id' => $parent->id, 
                                'category_id' => $deal['category_id'],
                                'date' => $date,
                                'type' => 'today',
                                'totalbet' => $deal['bet'],
                                'totalwin' => $deal['win'],
                                'totalcount' => 1,
                                'total_deal' => 0,
                                'total_mileage' => $deal['deal_profit'],
                                'total_ggr' => 0,
                                'total_ggr_mileage' => $deal['ggr_profit'],
                            ]
                        );
                    }

                    $daily_summary = \VanguardLTE\DailySummary::lockForUpdate()->where([
                        'user_id' => $parent->id, 
                        'date' => $date,
                        'type' => 'today',
                        ])->first();
                    if ($daily_summary)
                    {
                        $daily_summary->update(
                            [
                                'totalbet' => $daily_summary->totalbet + $deal['bet'],
                                'totalwin' => $daily_summary->totalwin + $deal['win'],
                                'total_deal' => $daily_summary->total_deal,
                                'total_mileage' => $daily_summary->total_mileage + $deal['deal_profit'],
                                'total_ggr' => $daily_summary->total_ggr,
                                'total_ggr_mileage' => $daily_summary->total_ggr_mileage + $deal['ggr_profit'],
                            ]
                        );
                    }
                    else
                    {
                        \VanguardLTE\DailySummary::create(
                            [
                                'user_id' => $parent->id, 
                                'date' => $date,
                                'type' => 'today',
                                'totalbet' => $deal['bet'],
                                'totalwin' => $deal['win'],
                                'total_deal' => 0,
                                'total_mileage' => $deal['deal_profit'],
                                'total_ggr' => 0,
                                'total_ggr_mileage' => $deal['ggr_profit'],
                                'shop_id' => $partner->shop_id,
                            ]
                            );
                    }
                    $parent = $parent->referral;
                }
            }


            //update game summary table if admin
            if ($index == 0)
            {
                $admin = \VanguardLTE\User::where('role_id', 9)->first();
                $game_summary = \VanguardLTE\GameSummary::lockForUpdate()->where([
                    'user_id' => $admin->id, 
                    'category_id' => $deal['category_id'],
                    'game_id' => strval($deal['game_id']),
                    'date' => $date,
                    'type' => 'today',
                    ])->first();
                if ($game_summary)
                {
                    $game_summary->update(
                        [
                            'totalbet' => $game_summary->totalbet + $deal['bet'],
                            'totalwin' => $game_summary->totalwin + $deal['win'],
                            'totalcount' => $game_summary->totalcount + 1,
                        ]
                    );
                }
                else
                {
                    \VanguardLTE\GameSummary::create(
                        [
                            'user_id' => $admin->id, 
                            'name' => $deal['game'],
                            'category_id' => $deal['category_id'],
                            'game_id' => strval($deal['game_id']),
                            'date' => $date,
                            'type' => 'today',
                            'totalbet' => $deal['bet'],
                            'totalwin' => $deal['win'],
                            'totalcount' => 1,
                        ]
                    );
                }
            }
        }
    }
}
