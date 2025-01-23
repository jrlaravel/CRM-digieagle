<?php 
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\BirthdayReminderMail;

class SendBirthdayReminderCommand extends Command
{
    protected $signature = 'send:birthday-reminder';

    protected $description = 'Send an email reminder to admins about upcoming employee birthdays';

    public function handle()
    {
        $targetDate = now()->addDays(2)->format('m-d');

        $usersWithUpcomingBirthdays = User::whereRaw("DATE_FORMAT(birth_date, '%m-%d') = ?", [$targetDate])->get();

        if ($usersWithUpcomingBirthdays->isNotEmpty()) {
            foreach ($usersWithUpcomingBirthdays as $user) {

                // Mail::to($user->email)->send(new BirthdayReminderMail($user));
                
                Mail::to('hr.digieagleinc@gmail.com')->send(new BirthdayReminderMail($user));
           
            }

            $this->info('Birthday reminder emails have been sent successfully.');
        } else {
            $this->info('No upcoming birthdays in the next two days.');
        }
    }
}
