<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InterviewScheduledMail extends Mailable
{
    use Queueable, SerializesModels;

    public $interviewDetails;

    /**
     * Create a new message instance.
     */
    public function __construct($interviewDetails)
    {
        $this->interviewDetails = $interviewDetails;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New Interview Scheduled')
                    ->view('employee/interview_scheduled')
                    ->with([
                        'candidateName' => $this->interviewDetails['candidate_name'],
                        'interviewType' => $this->interviewDetails['interview_type'],
                        'interviewDate' => $this->interviewDetails['interview_date'],
                        'interviewTime' => $this->interviewDetails['interview_time'],
                    ]);
    }
}

