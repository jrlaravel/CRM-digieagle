<?php 


namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $leave;
    public $statusText;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct($leave, $statusText, $user)
    {
        $this->leave = $leave;
        $this->statusText = $statusText;
        $this->user = $user;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->view('admin/leave-status')
                    ->with([
                        'employeeName' => $this->user->first_name,
                        'leaveStatus' => $this->statusText,
                        'startDate' => \Carbon\Carbon::parse($this->leave->start_date)->toFormattedDateString(),
                        'endDate' => \Carbon\Carbon::parse($this->leave->end_date)->toFormattedDateString(),
                    ]);
    }
}
