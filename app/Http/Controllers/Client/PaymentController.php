<?php

namespace App\Http\Controllers\Client;

use App\Exports\ClientTransactionsExport;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreatePaymentRequest;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Role;
use App\Models\User;
use App\Repositories\PaymentRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;
use Maatwebsite\Excel\Facades\Excel;
use Stancl\Tenancy\Database\TenantScope;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PaymentController extends AppBaseController
{
    /** @var PaymentRepository */
    public $paymentRepository;

    public function __construct(PaymentRepository $paymentRepo)
    {
        $this->paymentRepository = $paymentRepo;
    }

    public function index(Request $request)
    {
        $paymentModeArr = Payment::PAYMENT_MODE;

        return view('client_panel.transactions.index', compact('paymentModeArr'));
    }

    public function store(CreatePaymentRequest $request)
    {
        $input = $request->all();
        $input['payment_date'] = Carbon::now();

        if ($input['payment_type'] != Payment::FULLPAYMENT && $input['payable_amount'] < $input['amount']) {
            return $this->sendError(__('messages.flash.partially_amount_less_full_amount'));
        }

        if ($input['payment_type'] == Payment::FULLPAYMENT && $input['payable_amount'] != $input['amount']) {
            return $this->sendError(__('messages.flash.only_payable_amount'));
        }

        $payment = $this->paymentRepository->store($input);
        if ($payment) {
            /** @var Invoice $invoice */
            $invoice = Invoice::with(['client.user', 'payments'])->whereId($input['invoice_id'])->first();
            $input['currency_id'] = $invoice->currency_id;
            $input['user_id'] = User::withoutGlobalScope(new TenantScope())->role(Role::ROLE_ADMIN)->where('tenant_id', $invoice->client->tenant_id)->first()->id;
            $this->paymentRepository->saveNotification($input);
        }

        return $this->sendResponse($payment, __('messages.flash.payment_done'));
    }

    public function show($invoiceId)
    {
        $invoice = Invoice::whereClientId(Auth::user()->client->id)->whereId($invoiceId)->firstOrFail();
        $totalPayable = $this->paymentRepository->getTotalPayable($invoice);
        $transaction = $invoice->load(['payments']);
        if (! empty($transaction->payments) && ! empty($transaction->payments->last())) {
            if ($transaction->payments->last()->is_approved == Payment::PENDING) {
                Flash::error(__('messages.flash.cannot_make_payment_until_admin_approves'));

                return redirect()->route('client.invoices.index');
            }
        }

        $paymentType = Payment::PAYMENT_TYPE;
        $paymentMode = getPaymentMode();
        unset($paymentMode[Payment::CASH]);

        return view('client_panel.invoices.payment', compact('paymentType', 'paymentMode', 'totalPayable', 'invoice'));
    }

    public function exportTransactionsExcel(): BinaryFileResponse
    {
        return Excel::download(new ClientTransactionsExport(), 'transactions-excel.xlsx');
    }

    public function exportTransactionsPdf(): Response
    {
        $data['payments'] = Payment::with('invoice.client.user')->whereHas('invoice.client', function (Builder $q) {
            $q->where('user_id', getLogInUser()->client->user_id);
        })->orderBy('created_at', 'desc')->get();
        $clientTransactionsPdf = PDF::loadView('transactions.export_transactions_pdf', $data);

        return $clientTransactionsPdf->download('Client-Transactions.pdf');
    }
}
