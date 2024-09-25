<?php

namespace App\Mail;

use App\Models\Invoice;
use App\Repositories\InvoiceRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class InvoiceCreateClientMail extends Mailable
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
        $id = $this->data['invoiceData']['id'];
        $invoiceId = $this->data['invoiceData']['invoice_id'];
        $clientName = $this->data['clientData']['first_name'].' '.$this->data['clientData']['last_name'];
        $invoiceNumber = $this->data['invoiceData']['invoice_id'];
        $invoiceDate = Carbon::parse($this->data['invoiceData']['invoice_date'])->translatedFormat(currentDateFormat());
        $dueDate = Carbon::parse($this->data['invoiceData']['due_date'])->translatedFormat(currentDateFormat());
        $subject = "Invoice #$invoiceNumber Created";
        $invoice = Invoice::whereId($id)->firstOrFail();
        $invoice->load(['client.user', 'invoiceTemplate', 'invoiceItems.product', 'invoiceItems.invoiceItemTax', 'invoiceTaxes']);
        $invoiceRepo = App::make(InvoiceRepository::class);
        $invoiceData = $invoiceRepo->getPdfData($invoice);
        $invoiceTemplate = $invoiceRepo->getDefaultTemplate($invoice);
        $pdf = PDF::loadView("invoices.invoice_template_pdf.$invoiceTemplate", $invoiceData);

        return $this->view('emails.create_invoice_client_mail',
            compact('clientName', 'invoiceNumber', 'invoiceDate', 'dueDate', 'invoiceId', 'id'))
            ->markdown('emails.create_invoice_client_mail')
            ->subject($subject)
            ->attachData($pdf->output(), 'Invoice.pdf');
    }
}
