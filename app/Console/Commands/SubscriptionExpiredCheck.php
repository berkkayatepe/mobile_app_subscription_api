<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SubscriptionExpiredCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:expired_check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking and changing status of expired but not canceled records';

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
            ->select(['id', 'expiry_date', 'subscription_status', 'receipt', 'expiry_date_check'])
            ->where([['expiry_date_check', '=', 0], ['expiry_date', '<', date('Y-m-d H:i:s')], ['subscription_status', '!=', 'canceled']])
            ->chunk(500, function ($subscriptions) {
                foreach ($subscriptions as $subscription) {
                    $last_character = substr($subscription->receipt, -1);
                    if ($last_character % 2 != 0) {
                        $last_two_character = substr($subscription->receipt, -2);
                        $update_column = array(
                            'subscription_status' => 'canceled',
                            'expiry_date_check' => $last_two_character % 6 == 0 ? 0 : 1
                        );
                        DB::table('subscriptions')
                            ->where('id', $subscription->id)
                            ->update($update_column);
                    }
                }
            });
        return 1;
    }
}
