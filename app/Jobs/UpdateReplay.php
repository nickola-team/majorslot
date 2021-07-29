<?php

namespace VanguardLTE\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class UpdateReplay implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $replay_data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->replay_data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $response = Http::timeout(3)->asForm()->post(config('app.replayurl') . '/api/top/winnings', $this->replay_data);
            if (!$response->ok())
            {
                return -1;
            }
        }
        catch (\Exception $ex)
        {
            return 0;
        }

        return 0;
    }
}
