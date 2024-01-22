<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingStatusEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $status;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($event, $status)
    {
        $this->event = $event;
        $this->status = $status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('admin@booking.com')->view('emails.bookStatus')->with([
            'event' => $this->event,
            'status' => $this->status,
        ]);
    }
}
