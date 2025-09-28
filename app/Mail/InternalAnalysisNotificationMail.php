<?php

namespace App\Mail;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InternalAnalysisNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $submission;

    /**
     * Create a new message instance.
     */
    public function __construct(Submission $submission)
    {
        $this->submission = $submission;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New Skincare Analysis Submission - SkinTel Scanner')
                    ->view('emails.internal-analysis-notification')
                    ->with([
                        'submission' => $this->submission,
                    ]);
    }
}