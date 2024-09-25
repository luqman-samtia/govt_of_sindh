<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceCreateAdminMail extends Mailable
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
        $invoiceId = $this->data['invoiceData']['id'];
        $adminName = getAdminUser()->first_name.' '.getAdminUser()->last_name;
        $invoiceNumber = $this->data['invoiceData']['invoice_id'];
        $invoiceDate = Carbon::parse($this->data['invoiceData']['invoice_date'])->translatedFormat(currentDateFormat());
        $dueDate = Carbon::parse($this->data['invoiceData']['due_date'])->translatedFormat(currentDateFormat());
        $subject = "Invoice #$invoiceNumber Created";

        return $this->view('emails.create_invoice_admin_mail',
            compact('adminName', 'invoiceNumber', 'invoiceDate', 'dueDate', 'invoiceId'))
            ->markdown('emails.create_invoice_admin_mail')
            ->subject($subject);
    }
}
