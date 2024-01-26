<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $bookings;
    public $recepientType;
    public $userEmail;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($bookings, $recepientType, $userEmail)
    {
        $this->bookings = $bookings;
        $this->recepientType = $recepientType;
        $this->userEmail = $userEmail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->recepientType === 'admin') {
            // check for admin
            return $this->from($this->userEmail)
                ->subject('New Booking Request')
                ->view('emails.userBooking');
        } else {
            return $this->from('admin@booking.com')
                ->subject('Booking Information')
                ->view('emails.booking');
        }
    }
}
