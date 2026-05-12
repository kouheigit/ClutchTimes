<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Invitation;

class InvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invitation;
    public $reservation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
        $this->reservation = $invitation->reservation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('【空ノ庭】ご招待のお知らせ')
            ->view('emails.invitation')
            ->with([
                'name' => $this->invitation->name,
                'url' => route('invitation.register', ['token' => $this->invitation->token]),
                'reservation' => $this->reservation,
            ]);
    }
}

