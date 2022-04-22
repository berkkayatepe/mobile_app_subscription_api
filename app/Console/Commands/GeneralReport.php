<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GeneralReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:general_report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily subscription status based report';

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
        $subscriptions = DB::table('subscriptions')
            ->select(['app_id', 'os'])
            ->groupBy('app_id', 'os')->get();
        if ($subscriptions) {
            foreach ($subscriptions as $subscription) {
                DB::table('reports')->insert(array(
                    'app_id' => $subscription->app_id,
                    'os' => $subscription->os,
                    'started_count' => DB::table('reports')->where([['subscription_status', '=', 'started'], ['app_id', '=', $subscription->app_id], ['os', '=', $subscription->os], ['updated_at', '=', date('Y-m-d H:i:s')]]),
                    'renewed_count' => DB::table('reports')->where([['subscription_status', '=', 'renewed'], ['app_id', '=', $subscription->app_id], ['os', '=', $subscription->os], ['updated_at', '=', date('Y-m-d H:i:s')]]),
                    'canceled_count' => DB::table('reports')->where([['subscription_status', '=', 'canceled'], ['app_id', '=', $subscription->app_id], ['os', '=', $subscription->os], ['updated_at', '=', date('Y-m-d H:i:s')]]),
                ));
            }
        }
        return 0;
    }
}
