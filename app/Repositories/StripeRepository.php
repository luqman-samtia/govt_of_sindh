<?php

namespace App\Repositories;

use App\Mail\ClientMakePaymentMail;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Stancl\Tenancy\Database\TenantScope;
use Stripe\Checkout\Session;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class StripeRepository
 */
class StripeRepository
{
    public function clientPaymentSuccess($sessionId): void
    {
        setStripeApiKey();
        $sessionData = Session::retrieve($sessionId);

        $stripeID = $sessionData->id;
        $paymentStatus = $sessionData->payment_status;
        $amount = (getCurrencyCode() != 'JPY') ? $sessionData->amount_total / 100 : $sessionData->amount_total;
        $invoiceId = $sessionData->client_reference_id;

        /** @var Invoice $invoice */
        $invoice = Invoice::with(['client.user', 'payments'])->findOrFail($invoiceId);
        $clientUser = $invoice->client->user;

        $transactionData = [
            'tenant_id' => $invoice->tenant_id,
            'user_id' => $clientUser->id,
            'transaction_id' => $stripeID,
            'payment_mode' => Transaction::TYPE_STRIPE,
            'amount' => $amount,
            'status' => $paymentStatus,
            'meta' => $sessionData->toArray(),
        ];

        $notes = session('notes');

        try {
            DB::beginTransaction();
            if ($invoice->status == Payment::PARTIALLYPAYMENT) {
                $totalAmount = ($invoice->final_amount - $invoice->payments->sum('amount'));
            } else {
                $totalAmount = $invoice->final_amount;
            }

            $transaction = Transaction::create($transactionData);
            $paymentData = [
                'invoice_id' => $invoiceId,
                'amount' => $amount,
                'payment_mode' => Payment::STRIPE,
                'payment_date' => Carbon::now(),
                'transaction_id' => $transaction->id,
                'tenant_id' => $invoice->tenant_id,
                'user_id' => $clientUser->id,
                'is_approved' => Payment::APPROVED,
                'notes' => $notes,
            ];

            session()->forget('notes');

            // update invoice bill
            $invoicePayment = Payment::create($paymentData);

            if (round($totalAmount, 2) == $amount) {
                $invoice->status = Payment::FULLPAYMENT;
                $invoice->save();
            } else {
                if (round($totalAmount, 2) != $amount) {
                    $invoice->status = Payment::PARTIALLYPAYMENT;
                    $invoice->save();
                }
            }
            $paymentData['userId'] = User::role(Role::ROLE_ADMIN)->where('tenant_id', $invoice->client->tenant_id)->first()->id;
            $this->saveNotification($paymentData, $invoice);
            $invoiceData = [];
            $invoiceData['amount'] = $paymentData['amount'];
            $invoiceData['payment_date'] = $paymentData['payment_date'];
            $invoiceData['invoice_id'] = $invoice->id;
            $invoiceData['invoice'] = $invoice;
            $invoiceData['first_name'] = $clientUser->first_name;
            $invoiceData['last_name'] = $clientUser->last_name;

            if (getSettingValue('mail_notification')) {
                Mail::to($clientUser->email)->send(new ClientMakePaymentMail($invoiceData));
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function clientPaymentSuccessForPublic($sessionId)
    {
        $invoiceId = session('invoiceId');
        /** @var Invoice $invoice */
        $invoice = Invoice::with(['client.user', 'payments'])->findOrFail($invoiceId);
        $clientUser = $invoice->client->user;

        setStripeApiKeyForPublic($clientUser->tenant_id);
        $sessionData = Session::retrieve($sessionId);

        $stripeID = $sessionData->id;
        $paymentStatus = $sessionData->payment_status;
        $currencyId = Setting::where('tenant_id', $clientUser->tenant_id)->where('key', 'current_currency')->withoutGlobalScope(new TenantScope())->value('value');
        $currencyCode = getInvoiceCurrencyCode($currencyId, $clientUser->tenant_id);
        $amount = ($currencyCode != 'JPY') ? $sessionData->amount_total / 100 : $sessionData->amount_total;

        $transactionData = [
            'tenant_id' => $invoice->tenant_id,
            'user_id' => $clientUser->id,
            'transaction_id' => $stripeID,
            'payment_mode' => Transaction::TYPE_STRIPE,
            'amount' => $amount,
            'status' => $paymentStatus,
            'meta' => $sessionData->toArray(),
        ];

        $notes = session('notes');

        try {
            DB::beginTransaction();
            if ($invoice->status == Payment::PARTIALLYPAYMENT) {
                $totalAmount = ($invoice->final_amount - $invoice->payments->sum('amount'));
            } else {
                $totalAmount = $invoice->final_amount;
            }

            $transaction = Transaction::create($transactionData);
            $paymentData = [
                'invoice_id' => $invoiceId,
                'amount' => $amount,
                'payment_mode' => Payment::STRIPE,
                'payment_date' => Carbon::now(),
                'transaction_id' => $transaction->id,
                'tenant_id' => $invoice->tenant_id,
                'user_id' => $clientUser->id,
                'is_approved' => Payment::APPROVED,
                'notes' => $notes,
            ];

            session()->forget('notes');
            session()->forget('invoiceId');

            // update invoice bill
            $invoicePayment = Payment::create($paymentData);

            if (round($totalAmount, 2) == $amount) {
                $invoice->status = Payment::FULLPAYMENT;
                $invoice->save();
            } else {
                if (round($totalAmount, 2) != $amount) {
                    $invoice->status = Payment::PARTIALLYPAYMENT;
                    $invoice->save();
                }
            }
            $paymentData['userId'] = User::role(Role::ROLE_ADMIN)->where('tenant_id', $invoice->client->tenant_id)->first()->id;
            $this->saveNotification($paymentData, $invoice);
            $invoiceData = [];
            $invoiceData['amount'] = $paymentData['amount'];
            $invoiceData['payment_date'] = $paymentData['payment_date'];
            $invoiceData['invoice_id'] = $invoice->id;
            $invoiceData['invoice'] = $invoice;
            $invoiceData['first_name'] = $clientUser->first_name;
            $invoiceData['last_name'] = $clientUser->last_name;

            if (getSettingValue('mail_notification')) {
                Mail::to($clientUser->email)->send(new ClientMakePaymentMail($invoiceData));
            }
            DB::commit();

            return $invoice->invoice_id;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function saveNotification($input, $invoice)
    {
        $userId = $input['userId'];

        $invoice = Invoice::find($input['invoice_id']);
        $title = 'Payment '.getInvoiceCurrencySymbol($invoice->currency_id).$input['amount'].' received successfully for #'.$invoice->invoice_id.'.';
        addNotification([
            Notification::NOTIFICATION_TYPE['Invoice Payment'],
            $userId,
            $title,
        ]);
    }
}
