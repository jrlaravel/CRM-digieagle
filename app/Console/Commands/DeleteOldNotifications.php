<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DeleteOldNotifications extends Command
{
    // Command name to be executed via artisan
    protected $signature = 'notifications:delete-old';

    // Command description
    protected $description = 'Delete notifications older than 10 days';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Define the period to delete notifications older than 10 days
        $deleteBefore = Carbon::now()->subDays(10);

        // Delete notifications older than 10 days
        DB::table('notification')->where('created_at', '<', $deleteBefore)->delete();

        // Log the success message
        $this->info('Notifications older than 10 days deleted successfully.');
    }
}
