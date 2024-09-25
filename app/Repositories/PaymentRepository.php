<?php

namespace App\Repositories;

use App\Mail\ClientMakePaymentMail;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Payment;
use Illuminate\Support\Facades\Mail;

/**
 * Class PaymentRepository
 */
class PaymentRepository extends BaseRepository
{
    public function getFieldsSearchable(): array
    {
        return [];
    }

    public function model(): string
    {
        return Payment::class;
    }

    public function store($input): mixed
    {
        if ($input['payable_amount'] == $input['amount']) {
            $input['payment_type'] = Payment::FULLPAYMENT;
        }

        /** @var Invoice $invoiceData */
        $invoiceData = Invoice::with(['client.user', 'payments'])->whereId($input['invoice_id'])->first();
        $input['tenant_id'] = $invoiceData->tenant_id;
        $input['user_id'] = $invoiceData->client->user_id;
        $input['is_approved'] = Payment::PENDING;

        if ($input['payment_mode'] == Payment::MANUAL && getManualPayment($invoiceData->tenant_id) == 1) {
            $input['is_approved'] = Payment::APPROVED;
        } else {
            $input['payment_type'] = Invoice::PROCESSING;
        }

        $payment = Payment::create($input);
        $invoice = Invoice::whereId($input['invoice_id'])->update(['status' => $input['payment_type']]);

        if (isset($input['payment_attachment'])) {
            $payment->addMedia($input['payment_attachment'])->toMediaCollection(Payment::PAYMENT_ATTACHMENT, config('app.media_disc'));
        }

        $user = $invoiceData->client->user;
        $input['first_name'] = $user->first_name;
        $input['last_name'] = $user->last_name;

        $input['invoice'] = $payment->invoice;

        if (getSettingValue('mail_notification')) {
            Mail::to($user->email)->send(new ClientMakePaymentMail($input));
        }

        return $payment;
    }

    public function getTotalPayable($invoice): array
    {
        $data = [];
        $invoice->load(['payments']);
        $data['id'] = $invoice->id;
        $payments = $invoice->payments()->get();
        $paid = $invoice->payments()->where('is_approved', Payment::APPROVED)->sum('amount');

        if ($invoice->status == Payment::PARTIALLYPAYMENT) {
            $data['total_amount'] = ($invoice->final_amount - $paid);
        } else {
            $data['total_amount'] = $invoice->final_amount;
        }

        return $data;
    }

    public function saveNotification($input)
    {
        $userId = $input['user_id'];
        $invoice = Invoice::find($input['invoice_id']);
        $title = 'Payment '.getInvoiceCurrencySymbol($input['currency_id']).$input['amount'].' received successfully for #'.$invoice->invoice_id.'.';
        addNotification([
            Notification::NOTIFICATION_TYPE['Invoice Payment'],
            $userId,
            $title,
        ]);
    }
}
