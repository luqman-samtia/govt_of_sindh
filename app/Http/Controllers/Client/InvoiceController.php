<?php

namespace App\Http\Controllers\Client;

use App\Exports\ClientInvoicesExport;
use App\Http\Controllers\AppBaseController;
use App\Models\Invoice;
use App\Models\Payment;
use App\Repositories\InvoiceRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class InvoiceController extends AppBaseController
{
    /** @var InvoiceRepository */
    public $invoiceRepository;

    public function __construct(InvoiceRepository $invoiceRepo)
    {
        $this->invoiceRepository = $invoiceRepo;
    }

    /**
     *
     * @throws Exception
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $statusArr = Invoice::STATUS_ARR;
        $status = $request->status;
        unset($statusArr[Invoice::DRAFT]);
        $paymentType = Payment::PAYMENT_TYPE;
        $paymentMode = getPaymentMode();
        unset($paymentMode[Payment::CASH]);

        return view('client_panel.invoices.index', compact('statusArr', 'paymentType', 'paymentMode', 'status'));
    }

    public function show($invoiceId)
    {
        $invoice = Invoice::whereId($invoiceId)->whereClientId(Auth::user()->client->id)->firstOrFail();
        if ($invoice->status == Invoice::DRAFT) {
            Flash::error('Invoice Not Found.');

            return redirect()->route('client.invoices.index');
        }
        $invoiceData = $this->invoiceRepository->getInvoiceData($invoice);

        return view('client_panel.invoices.show')->with($invoiceData);
    }

    public function convertToPdf(Invoice $invoice): Response
    {
        $clientId = Auth::user()->client->id;
        $invoice->load('client.user', 'invoiceTemplate', 'invoiceItems.product', 'invoiceItems.invoiceItemTax');
        if ($clientId != $invoice->client_id || $invoice->status == Invoice::DRAFT) {
            abort(404);
        }
        $invoiceData = $this->invoiceRepository->getPdfData($invoice);
        $invoiceTemplate = $this->invoiceRepository->getDefaultTemplate($invoice);
        $pdf = PDF::loadView("invoices.invoice_template_pdf.$invoiceTemplate", $invoiceData);

        return $pdf->stream('invoice.pdf');
    }

    public function exportInvoicesExcel(): BinaryFileResponse
    {
        return Excel::download(new ClientInvoicesExport(), 'invoice-excel.xlsx');
    }

    public function exportInvoicesPdf(): Response
    {
        $data['invoices'] = Invoice::whereClientId(Auth::user()->client->id)
            ->where('status', '!=', Invoice::DRAFT)
            ->with('payments')->orderBy('created_at', 'desc')->get();
        $clientInvoicesPdf = PDF::loadView('invoices.export_invoices_pdf', $data);

        return $clientInvoicesPdf->download('Client-Invoices.pdf');
    }
}
