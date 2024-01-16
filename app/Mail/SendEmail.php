<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $bookings;
    public $recepientType;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($bookings, $recepientType)
    {
        $this->bookings = $bookings;
        $this->recepientType = $recepientType;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->recepientType === 'admin') {       // check for admin
            return $this->from(auth()->user()->email)->view('emails.userBooking');
        } else {
            return $this->from('admin@booking.com')->view('emails.booking');
        }
    }
}
