<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Currency;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Exports\AdminInvoicesExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use App\Repositories\InvoiceRepository;
use App\Mail\InvoicePaymentReminderMail;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Payment;
use Illuminate\Contracts\Foundation\Application;
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
     * @throws Exception
     */
    public function index(Request $request): View|Factory|Application
    {
        $this->updateInvoiceOverDueStatus();
        $statusArr = Invoice::STATUS_ARR;
        $status = $request->status;

        return view('invoices.index', compact('statusArr', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|Factory|Application
    {
        $data = $this->invoiceRepository->getSyncList();
        unset($data['statusArr'][0]);
        $data['currencies'] = getCurrencies();

        return view('invoices.create')->with($data);
    }

    public function store(CreateInvoiceRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $invoice = $this->invoiceRepository->saveInvoice($request->all());
            DB::commit();
            if ($request->status != Invoice::DRAFT) {
                $this->invoiceRepository->saveNotification($request->all(), $invoice);
                Flash::success(__('messages.flash.invoice_saved_sent'));

                return $this->sendResponse($invoice, __('messages.flash.invoice_saved_sent'));
            }
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError($e->getMessage());
        }

        Flash::success(__('messages.flash.invoice_saved'));

        return $this->sendResponse($invoice, __('messages.flash.invoice_saved'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice): View|Factory|Application
    {
        $invoiceData = $this->invoiceRepository->getInvoiceData($invoice);

        return view('invoices.show')->with($invoiceData);
    }

    public function edit(Invoice $invoice): View|Factory|RedirectResponse|Application
    {
        if ($invoice->status == Invoice::PAID || $invoice->status == Invoice::PARTIALLY) {
            Flash::error(__('messages.flash.paid_invoices_can_not_editable'));

            return redirect()->route('invoices.index');
        }

        $data = $this->invoiceRepository->prepareEditFormData($invoice);
        $data['currencies'] = getCurrencies();
        $data['selectedInvoiceTaxes'] = $invoice->invoiceTaxes()->pluck('tax_id')->toArray();

        return view('invoices.edit')->with($data);
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice): JsonResponse
    {
        $input = $request->all();
        try {
            DB::beginTransaction();
            $invoice = $this->invoiceRepository->updateInvoice($invoice->id, $input);
            DB::commit();
            if ($input['invoiceStatus'] === '1') {
                return $this->sendResponse($invoice, __('messages.flash.invoice_updated_sent'));
            }
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError($e->getMessage());
        }

        Flash::success(__('messages.flash.invoice_updated'));

        return $this->sendResponse($invoice, __('messages.flash.invoice_updated'));
    }

    public function destroy(Invoice $invoice): JsonResponse
    {
        if ($invoice->tenant_id != Auth::user()->tenant_id) {
            return $this->sendError('Seems, you are not allowed to access this record.');
        }
        $invoice->delete();

        return $this->sendSuccess(__('messages.flash.invoice_deleted'));
    }

    public function getProduct($productId): JsonResponse
    {
        $product = Product::toBase()->pluck('unit_price', 'id')->toArray();

        return $this->sendResponse($product, __('messages.flash.product_price_retrieved'));
    }

    public function getInvoiceCurrency($currencyId): mixed
    {
        $currency = Currency::whereId($currencyId)->first()->icon;

        return $this->sendResponse($currency, __('messages.flash.invoice_currency_retrieved'));
    }

    public function convertToPdf($invoiceId)
    {
        $invoice = Invoice::whereId($invoiceId)->whereTenantId(Auth::user()->tenant_id)->firstOrFail();
        $invoice->load([
            'client.user', 'invoiceTemplate', 'invoiceItems.product', 'invoiceItems.invoiceItemTax', 'invoiceTaxes',
        ]);
        $invoiceData = $this->invoiceRepository->getPdfData($invoice);

        $invoiceTemplate = $this->invoiceRepository->getDefaultTemplate($invoice);
        $pdf = PDF::loadView("invoices.invoice_template_pdf.$invoiceTemplate", $invoiceData);

        return $pdf->stream('invoice.pdf');
    }

    public function updateInvoiceStatus(Invoice $invoice, $status): mixed
    {
        $this->invoiceRepository->draftStatusUpdate($invoice);

        return $this->sendSuccess(__('messages.flash.invoice_send'));
    }

    public function updateInvoiceOverDueStatus()
    {
        $invoice = Invoice::whereStatus(Invoice::UNPAID)->get();
        $currentDate = Carbon::today()->format('Y-m-d');
        foreach ($invoice as $invoices) {
            if ($invoices->due_date < $currentDate) {
                $invoices->update([
                    'status' => Invoice::OVERDUE,
                ]);
            }
        }
    }

    public function invoicePaymentReminder($invoiceId): mixed
    {
        $invoice = Invoice::with(['client.user', 'payments'])->whereId($invoiceId)->whereTenantId(Auth::user()->tenant_id)->firstOrFail();
        $paymentReminder = Mail::to($invoice->client->user->email)->send(new InvoicePaymentReminderMail($invoice));

        return $this->sendResponse($paymentReminder, __('messages.flash.payment_reminder_mail_send'));
    }

    public function exportInvoicesExcel(): BinaryFileResponse
    {
        return Excel::download(new AdminInvoicesExport(), 'invoice-excel.xlsx');
    }

    public function showPublicInvoice($invoiceId): View|Factory|Application
    {
        $invoice = Invoice::with('client.user')->whereInvoiceId($invoiceId)->firstOrFail();
        $invoiceData = $this->invoiceRepository->getInvoiceData($invoice);
        $paymentTypes = Payment::PAYMENT_TYPE;
        $paymentModes = getPaymentMode($invoice->client->user->tenant_id);
        unset($paymentModes[Payment::CASH]);
        $tenantId = $invoice->client->user->tenant_id;

        return view('invoices.public_view', compact('paymentTypes', 'paymentModes', 'tenantId'))->with($invoiceData);
    }

    public function getPublicInvoicePdf($invoiceId): Response
    {
        $invoice = Invoice::whereInvoiceId($invoiceId)->firstOrFail();
        $invoice->load('client.user', 'invoiceTemplate', 'invoiceItems.product', 'invoiceItems.invoiceItemTax');

        $invoiceData = $this->invoiceRepository->getPdfData($invoice);
        $invoiceTemplate = $this->invoiceRepository->getDefaultTemplate($invoice);
        $pdf = PDF::loadView("invoices.invoice_template_pdf.$invoiceTemplate", $invoiceData);

        return $pdf->stream('invoice.pdf');
    }

    public function updateRecurringStatus(Invoice $invoice)
    {
        if ($invoice->tenant_id != Auth::user()->tenant_id) {
            return $this->sendError(__('Seems, you are not allowed to access this record.'));
        }

        $recurringCycle = empty($invoice->recurring_cycle) ? 1 : $invoice->recurring_cycle;
        $invoice->update([
            'recurring_status' => !$invoice->recurring_status,
            'recurring_cycle' => $recurringCycle,
        ]);

        return $this->sendSuccess(__('messages.flash.recurring_status_updated'));
    }

    public function exportInvoicesPdf()
    {
        ini_set('max_execution_time', 36000000);
        ini_set('memory_limit', '512M');
        $data['invoices'] = Invoice::with('client.user', 'payments')->orderBy('created_at', 'desc')->get();
        $invoicesPdf = PDF::loadView('invoices.export_invoices_pdf', $data);

        return $invoicesPdf->download('Invoices.pdf');
    }

    public function sendInvoiceOnWhatsapp(Request $request): JsonResponse
    {
        $request->validate([
            'invoice_id' => 'required',
            'phone_number' => 'required',
        ]);

        $data = [];
        $input = $request->all();
        $invoice = Invoice::with(['client.user','payments'])->whereId($input['invoice_id'])->firstOrFail();
        $data['appName'] = getAppName();
        $data['invoice'] = $invoice;
        $data['invoicePdfLink'] = route('public-view-invoice.pdf', ['invoice' => $invoice->invoice_id]);
        $data['phoneNumber'] = '+'.$input['region_code'].$input['phone_number'];

        return $this->sendResponse($data, 'send invoice data retrieved successfully.');
    }
}
