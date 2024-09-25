<?php

namespace App\Http\Controllers;

use App\Exports\AdminTransactionsExport;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Role;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PaymentController extends AppBaseController
{
    public function index(): View|Factory|Application
    {
        getSuperAdmin();
        $paymentModeArr = Payment::PAYMENT_MODE;

        return view('transactions.index', compact('paymentModeArr'));
    }

    public function exportTransactionsExcel(): BinaryFileResponse
    {
        return Excel::download(new AdminTransactionsExport(), 'transaction.xlsx');
    }

    public function changeTransactionStatus(Request $request)
    {
        $input = $request->all();

        /** @var Payment $payment */
        $payment = Payment::whereId($input['id'])->wherePaymentMode(Payment::MANUAL)->with('invoice')->firstOrFail();

        if ($input['status'] == Payment::MANUAL) {
            $payment->update([
                'is_approved' => $input['status'],
            ]);
            $this->updatePayment($payment);

            return $this->sendSuccess(__('messages.flash.manual_payment_approved'));
        }

        $payment->update([
            'is_approved' => $input['status'],
        ]);
        $this->updatePayment($payment);

        return $this->sendSuccess(__('messages.flash.manual_payment_Denied'));
    }

    private function updatePayment(Payment $payment): void
    {
        $paymentInvoice = $payment->invoice;
        $totalPayment = Payment::whereInvoiceId($paymentInvoice->id)->sum('amount');
        if ($payment->amount == $paymentInvoice->final_amount || $totalPayment == $paymentInvoice->final_amount) {
            $status = $payment->is_approved == Payment::APPROVED ? Invoice::PAID : Invoice::UNPAID;

            $paymentInvoice->update([
                'status' => $status,
            ]);
        } else {
            $paymentInvoice->update([
                'status' => Invoice::PARTIALLY,
            ]);
        }
    }

    public function showPaymentNotes($id): JsonResponse
    {
        $paymentNotes = Payment::where('id', $id)->first();
        if ($paymentNotes->tenant_id != Auth::user()->tenant_id) {
            return $this->sendError('Seems, you are not allowed to access this record.');
        }

        return $this->sendResponse($paymentNotes->notes, __('messages.flash.note_retrieved'));
    }

    public function downloadAttachment($transactionId)
    {
        /** @var Payment $transaction */
        $transaction = Payment::with('media')->findOrFail($transactionId);
        $attachment = $transaction->media->first();

        if (getLogInUser()->hasrole(Role::ROLE_CLIENT)) {
            if ($transaction->invoice->client->user_id !== getLogInUserId()) {
                Flash::error('Seems, you are not allowed to access this record.');

                return redirect()->back();
            }
        }
        if ($attachment) {
            return $attachment;
        }

        return null;
    }

    public function exportTransactionsPdf(): Response
    {
        $data['payments'] = Payment::with(['invoice.client.user'])->orderBy('created_at', 'desc')->get();
        $transactionsPdf = PDF::loadView('transactions.export_transactions_pdf', $data);

        return $transactionsPdf->download('Transactions.pdf');
    }
}
