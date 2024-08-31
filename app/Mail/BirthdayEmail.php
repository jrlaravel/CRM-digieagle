<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;


class BirthdayReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;   
    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user  = $user;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this->subject('Birthday Wish')
                    ->view('admin/birthdayReminder');
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
