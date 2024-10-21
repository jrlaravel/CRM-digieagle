<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LeadStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $lead;
    public $previousStatus;
    public $newStatus;
    public $followupMessage;
    public $companyName;
    public $callDate; 

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($lead, $previousStatus, $newStatus, $followupMessage, $companyName, $callDate = null)
    {
        $this->lead = $lead;
        $this->previousStatus = $previousStatus;
        $this->newStatus = $newStatus;
        $this->followupMessage = $followupMessage;
        $this->companyName = $companyName;
        $this->callDate = $callDate; // Store call_date
    }
    

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Lead Status Changed')
                    ->view('admin/lead-status-changed');
    }
}
