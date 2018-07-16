<?php

namespace App\Console\Commands;

use App\Models\UserPlan;
use App\User;
use Illuminate\Console\Command;
use App\Config;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Test:concat';

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
     * @return mixed
     */
    public function handle()
    {
        //витягнути юзерпланс і пройтися циклом 
        $var = UserPlan::where('status', '=', 'active')
            ->where('date_end', '>=', date('Y-d-m'))
            ->get();
        foreach ($var as $item) {
            $item->earned += $item->accumulation;
            $item->save();
        }
    }
}
// foreach ($userplan as $plan) { $plan->earned += $plan->accumulation; $plan->save();  }