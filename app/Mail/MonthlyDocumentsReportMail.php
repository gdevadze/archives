<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MonthlyDocumentsReportMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var array
     */
    public array $report;

    /**
     * @var Carbon
     */
    public Carbon $period;

    /**
     * @param array  $report  // company | contract_type | total
     * @param Carbon $period  // წინა თვის თარიღი
     */
    public function __construct(array $report, Carbon $period)
    {
        $this->report = $report;
        $this->period = $period;
    }

    public function build()
    {
        return $this
            ->subject('📊 Document Upload Report — ' . $this->period->format('F Y'))
            ->view('emails.monthly_documents_report');
    }
}
