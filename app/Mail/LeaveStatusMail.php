<?php 


namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveStatusMail extends Mailable
{
    public $leave;
    public $statusText;
    public $user;
    public $rejectionReason;

    public function __construct($leave, $statusText, $user, $rejectionReason = null)
    {
        $this->leave = $leave;
        $this->statusText = $statusText;
        $this->user = $user;
        $this->rejectionReason = $rejectionReason;
    }

    public function build()
    {
        return $this->view('admin/leave-status')
                    ->with([
                        'employeeName' => $this->user->name,
                        'startDate' => $this->leave->start_date,
                        'endDate' => $this->leave->end_date,
                        'leaveStatus' => $this->statusText,
                        'rejectionReason' => $this->rejectionReason, // Pass reason to view
                    ]);
    }
}
