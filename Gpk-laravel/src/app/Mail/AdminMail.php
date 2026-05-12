<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Reservation;

class AdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;
    public $type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation, $type = 'new')
    {
        $this->reservation = $reservation;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = match($this->type) {
            'new' => '【空ノ庭】新規予約がありました',
            'cancel' => '【空ノ庭】予約キャンセルがありました',
            default => '【空ノ庭】予約通知',
        };

        return $this->subject($subject)
            ->view('emails.admin')
            ->with([
                'reservation' => $this->reservation,
                'user' => $this->reservation->user,
                'type' => $this->type,
            ]);
    }
}

