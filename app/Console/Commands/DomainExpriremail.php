<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HostingAndDomain;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class DomainExpriremail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:domain-expriremail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $adminEmail = 'manager.digieagleinc@gmail.com'; // Replace with your admin's email address
        $thresholdDate = Carbon::now()->addDays(5);

        // Get records where domain or hosting is about to expire in 5 days
        $expiringDomains = HostingAndDomain::whereDate('domain_expire_date', $thresholdDate)->get();
        $expiringHostings = HostingAndDomain::whereDate('hosting_expire_date', $thresholdDate)->get();

        // Combine the results
        $expiringItems = $expiringDomains->merge($expiringHostings);

        if ($expiringItems->isEmpty()) {
            $this->info('No domains or hostings are expiring in 5 days.');
            return 0;
        }

        // Email content
        $data = [
            'expiringItems' => $expiringItems,
        ];

        // Send the email
        Mail::send('admin/expiryNotification', $data, function ($message) use ($adminEmail) {
            $message->to($adminEmail)
                ->subject('Upcoming Domain or Hosting Expiry Notification');
        });

        $this->info('Notification email sent to admin.');
        return 0;
    }
}
