<?php

namespace VanguardLTE\Console\Commands;

use Illuminate\Console\Command;
use VanguardLTE\Http\Controllers\Web\GameProviders\NEXUSController;
use VanguardLTE\Http\Controllers\Web\GameProviders\RGController;

class TransactionDetail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:detail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command is used to fetch Transaction Detail.';

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
        NEXUSController::transactionDetail();
        RGController::transactionDetail();

        return 0;
    }
}
