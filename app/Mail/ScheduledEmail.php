<?php
// app/Mail/ScheduledEmail.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ScheduledEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailDetails;

    public function __construct($emailDetails)
    {
        $this->emailDetails = $emailDetails;
    }

    public function build()
    {
        return $this->subject($this->emailDetails['subject'])
                    ->markdown('emails.scheduled')
                    ->with('emailDetails', $this->emailDetails);
    }
}
