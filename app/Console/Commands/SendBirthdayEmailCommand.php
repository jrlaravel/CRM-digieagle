<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\BirthdayEmail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;


class SendBirthdayEmailCommand extends Command
{
    protected $signature = 'send:birthday-email';

    protected $description = 'Send an email to employees on their birthday';

    public function handle()
    {
        $targetDate = now()->format('m-d');

        $usersWithBirthdaysToday = User::whereRaw("DATE_FORMAT(birth_date, '%m-%d') = ?", [$targetDate])->get();

        if ($usersWithBirthdaysToday->isNotEmpty()) {
            foreach ($usersWithBirthdaysToday as $user) {
                // Send email to the employee
                Mail::to($user->email)->send(new BirthdayEmail($user));
            }
            $this->info('Birthday emails have been sent successfully.');
        } else {
            $this->info('No birthdays today.');
        }
    }
}
