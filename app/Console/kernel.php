<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Register your commands here
        \App\Console\Commands\SendBirthdayReminderCommand::class,
        \App\Console\Commands\DeleteOldNotifications::class,
        \App\Console\Commands\SendBirthdayEmailCommand::class,



    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        // Schedule your command to run daily at midnight
        $schedule->command('send:birthday-reminder')->everyMinute();
        $schedule->command('notifications:delete-old')->cron('0 0 */10 * *');
        $schedule->command('send:birthday-email')->everyMinute();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
