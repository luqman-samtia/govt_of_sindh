<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuoteCreateClientMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        $quoteId = $this->data['quoteData']['id'];
        $clientName = $this->data['clientData']['first_name'].' '.$this->data['clientData']['last_name'];
        $quoteNumber = $this->data['quoteData']['quote_id'];
        $quoteDate = Carbon::parse($this->data['quoteData']['quote_date'])->translatedFormat(currentDateFormat());
        $dueDate = Carbon::parse($this->data['quoteData']['due_date'])->translatedFormat(currentDateFormat());
        $subject = "Quote #$quoteNumber Created";

        return $this->view('emails.create_quote_client_mail',
            compact('clientName', 'quoteNumber', 'quoteDate', 'dueDate', 'quoteId'))
            ->markdown('emails.create_quote_client_mail')
            ->subject($subject);
    }
}
