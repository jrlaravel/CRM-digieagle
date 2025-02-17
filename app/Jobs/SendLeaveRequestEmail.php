<?php


namespace App\Jobs;

use App\Mail\LeaveRequestMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendLeaveRequestEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $leaveDetails;
    protected $mailRecipients;

    /**
     * Create a new job instance.
     */
    public function __construct($leaveDetails, $mailRecipients)
    {
        $this->leaveDetails = $leaveDetails;
        $this->mailRecipients = $mailRecipients;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Mail::to($this->mailRecipients)->send(new LeaveRequestMail($this->leaveDetails));
    }
}
