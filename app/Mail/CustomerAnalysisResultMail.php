<?php

namespace App\Mail;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerAnalysisResultMail extends Mailable
{
    use Queueable, SerializesModels;

    public $submission;
    public $recommendationsUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Submission $submission, string $recommendationsUrl)
    {
        $this->submission = $submission;
        $this->recommendationsUrl = $recommendationsUrl;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Skincare Analysis Results - SkinTel Scanner')
                    ->view('emails.customer-analysis-result')
                    ->with([
                        'submission' => $this->submission,
                        'recommendationsUrl' => $this->recommendationsUrl,
                    ]);
    }
}