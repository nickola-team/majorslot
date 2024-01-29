<?php

namespace VanguardLTE\Console\Commands;

use Illuminate\Console\Command;
use VanguardLTE\Http\Controllers\Web\GameProviders\DGController;
use VanguardLTE\User;
use Carbon\Carbon;
use VanguardLTE\StatGame;
use VanguardLTE\Category;
use Cache;
use Exception;
use Log;

class KDiorUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kdior:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $comasterid = 40060;
        for ($role_id=6;$role_id>=1;$role_id--)
        {
            $query = 'select * from baccarat_users where role_id=' . $role_id;

            $diorusers = \DB::select($query);
            foreach ($diorusers as $data)
            {
                $role = \jeremykenedy\LaravelRoles\Models\Role::find($role_id);
                $checkuser = \VanguardLTE\User::where('username', $data->username)->first();
                if ($checkuser)
                {
                    $this->info('user already exist ' . $data->username . ' trying for  ' . $data->username . 'do');
                    $data->username = $data->username . 'do';
                    $checkuser = \VanguardLTE\User::where('username', $data->username)->first();
                    if ($checkuser)
                    {
                        $this->info('user already exist ' . $data->username . ' skipping this user');
                        continue;
                    }
                }
                if ($data->confirmation_token != '')
                {
                    $data->confirmation_token = \Illuminate\Support\Facades\Hash::make($data->confirmation_token);   
                }
                if ($role_id == 6)
                {
                    $data->parent_id = $comasterid;
                }
                else
                {
                    $query1 = 'select * from baccarat_users where id=' . $data->parent_id;
                    $parentuser = \DB::select($query1);
                    $puser = \VanguardLTE\User::where('username', $parentuser[0]->username)->first();
                    if (!$puser)
                    {
                        $puser = \VanguardLTE\User::where('username', $parentuser[0]->username. 'do')->first();
                    }
                    if (!$puser)
                    {
                        $this->info('user not exist ' . $parentuser[0]->username . ' for creating ' . $data->id . ' user');
                        continue;
                    }
                    $data->parent_id = $puser->id;
                }

                $data = json_decode(json_encode($data), true);


                $user = \VanguardLTE\User::create($data);
                $user->detachAllRoles();
                $user->attachRole($role);
                // event(new \VanguardLTE\Events\User\Created($user));
                $type = 'partner';

                if ($data['role_id'] > 3)
                {

                    \VanguardLTE\OpenShift::create([
                        'start_date' => \Carbon\Carbon::now(), 
                        'user_id' => $user->id, 
                        'shop_id' => 0,
                        'old_total' => 0,
                        'deal_profit' => 0,
                        'mileage' => 0,
                        'type' => $type
                    ]);
                }

                if ($data['role_id'] == 3)  //create shop
                {
                    $data['name'] = $data['username'];
                    $shop = \VanguardLTE\Shop::create($data + ['user_id' => $comasterid]);
                    
                    //create shopuser table for all agents
                    $parent = $user;
                    while ($parent && !$parent->isInOutPartner())
                    {
                        \VanguardLTE\ShopUser::create([
                            'shop_id' => $shop->id, 
                            'user_id' => $parent->id
                        ]);
                        $parent = $parent->referral;
                    }
                    
                    $user->update(['shop_id' => $shop->id]);
                    $site = null;
                    if ($parent == null){
                        $site = \VanguardLTE\WebSite::where('domain', \Request::root())->first();
                    }
                    else
                    {
                        $site = \VanguardLTE\WebSite::where('adminid', $parent->id)->first();
                    }
                    \VanguardLTE\Task::create([
                        'category' => 'shop', 
                        'action' => 'create', 
                        'item_id' => $shop->id,
                        'details' => $site?$site->id:0,
                    ]);
                    $open_shift = \VanguardLTE\OpenShift::create([
                        'start_date' => \Carbon\Carbon::now(), 
                        'balance' => 0, 
                        'user_id' => $user->id, 
                        'shop_id' => $shop->id
                    ]); 
                    // event(new \VanguardLTE\Events\Shop\ShopCreated($shop));
                }

                if ($data['role_id'] == 1)  //create user
                {
                    $parent = $user->referral;
                    $shop_id = $parent->shop_id;
                    $user->update(['shop_id' => $shop_id]);

                    \VanguardLTE\ShopUser::create([
                        'shop_id' => $shop->id, 
                        'user_id' => $user->id
                    ]);
                }
            }
        }
    }


}
