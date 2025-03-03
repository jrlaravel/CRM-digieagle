<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


Artisan::command('custom:example', function () {
    $this->info('This is a custom console command');
})->describe('A simple custom command defined in routes/console.php');


Artisan::command('custom:schedule', function () {

    $schedule->command('send:birthday-reminder')->everyMinute();
    $schedule->command('notifications:delete-old')->cron('0 0 */10 * *');
    $schedule->command('send:birthday-email')->everyMinute();
});
