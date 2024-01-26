<?php

namespace App\Jobs;

use App\Mail\SendEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $emailDetails;
    public $recepientType;
    public $userEmail;
    /**
     * Create a new job instance.
     * @param array $emailDetails
     * @param string $recepientType
     * @param string $userEmail
     *
     */
    public function __construct(array $emailDetails, string $recepientType, string $userEmail)
    {
        $this->emailDetails = $emailDetails;
        $this->recepientType = $recepientType;
        $this->userEmail = $userEmail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $mail = new SendEmail($this->emailDetails, $this->recepientType, $this->userEmail);
        Mail::send($mail);

    }
}
