<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CardAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $card;
    public $employee;
    public $messageContent;
    /**
     * Create a new message instance.
     */
   public function __construct($card, $employee, $messageContent)
    {
        $this->card = $card;
        $this->employee = $employee;
        $this->messageContent  = $messageContent;
    }

    public function build()
    {
        return $this->subject('A new card has been assigned to you')
                    ->view('admin/card_assigned')
                    ->with([
                        'card' => $this->card,
                        'employee' => $this->employee,
                        'messageContent ' => $this->messageContent , 
                    ]);
    }
  
    public function attachments(): array
    {
        return [];
    }
}
