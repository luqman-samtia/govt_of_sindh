<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\AppBaseController;
use App\Mail\ClientMakePaymentMail;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laracasts\Flash\Flash;
use Razorpay\Api\Api;
use Stancl\Tenancy\Database\TenantScope;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class RazorpayController extends AppBaseController
{
    public function onBoard(Request $request): JsonResponse
    {
        $invoice = Invoice::with('client.user')->whereId($request->invoice_id)->firstOrFail();
        $user = $invoice->client->user;
        $invoiceID = $invoice->invoice_id;

        if(! Auth::check()) {
            session(['userId' => $user->id]);
            $api = new Api(getRazorpayKeyForPublic($user->tenant_id), getRazorpaySecretForPublic($user->tenant_id));
        }else {
            $api = new Api(getRazorpayKey(), getRazorpaySecret());
        }

        $currencyCode = strtoupper(getInvoiceCurrencyCode($invoice->currency_id, $user->tenant_id));

        $orderData = [
            'receipt' => 1,
            'amount' => $request->amount * 100, // 100 = 1 rupees
            'currency' => $currencyCode,
            'notes' => [
                'email' => $user->email,
                'name' => $user->full_name,
                'invoiceID' => $request->invoice_id,
            ],
        ];

        $razorpayOrder = $api->order->create($orderData);

        $data['id'] = $razorpayOrder->id;
        $data['amount'] = $request->amount;
        $data['name'] = $user->full_name;
        $data['email'] = $user->email;
        $data['invoiceId'] = $request->invoice_id;
        $data['invoice_id'] = $invoiceID;
        $data['notes'] = $request->notes;

        return $this->sendResponse($data, __('messages.flash.payment_create'));
    }

    public function paymentSuccess(Request $request)
    {
        $input = $request->all();
        Log::info('RazorPay Payment Successfully');
        Log::info($input);
        $userId = session('userId');
        $user = User::withoutGlobalScope(new TenantScope())->findOrFail($userId);

        if(! Auth::check()) {
            $api = new Api(getRazorpayKeyForPublic($user->tenant_id), getRazorpaySecretForPublic($user->tenant_id));
        }else {
            $api = new Api(getRazorpayKey(), getRazorpaySecret());
        }

        if (count($input) && ! empty($input['razorpay_payment_id'])) {
            try {
                $payment = $api->payment->fetch($input['razorpay_payment_id']);
                $generatedSignature = hash_hmac('sha256', $payment['order_id'].'|'.$input['razorpay_payment_id'],
                    (! Auth::check()) ? getRazorpaySecretForPublic($user->tenant_id) : getRazorpaySecret());
                if ($generatedSignature != $input['razorpay_signature']) {
                    return redirect()->back();
                }

                DB::beginTransaction();
                // Create Transaction Here
                $invoiceId = $payment['notes']['invoiceID'];
                $paymentAmount = $payment['amount'] / 100;
                $notes = json_decode($payment['description'])->notes;

                /** @var Invoice $invoice */
                $invoice = Invoice::with(['client.user', 'payments'])->findOrFail($invoiceId);
                $clientUser = $invoice->client->user;

                if ($invoice->status == Payment::PARTIALLYPAYMENT) {
                    $totalAmount = ($invoice->final_amount - $invoice->payments->sum('amount'));
                } else {
                    $totalAmount = $invoice->final_amount;
                }

                if (round($totalAmount, 2) == $paymentAmount) {
                    $invoice->status = Payment::FULLPAYMENT;
                    $invoice->save();
                } elseif (round($totalAmount, 2) != $paymentAmount) {
                    $invoice->status = Payment::PARTIALLYPAYMENT;
                    $invoice->save();
                }

                $transaction = [
                    'transaction_id' => $payment->id,
                    'amount' => $paymentAmount,
                    'status' => 'paid',
                    'payment_mode' => Transaction::TYPE_RAZORPAY,
                    'meta' => $payment->toArray(),
                    'tenant_id' => $invoice->tenant_id,
                    'user_id' => $clientUser->id,
                ];

                $transaction = Transaction::create($transaction);

                $paymentData = [
                    'invoice_id' => $invoiceId,
                    'amount' => $payment['amount'] / 100,
                    'payment_mode' => Payment::RAZORPAY,
                    'payment_date' => Carbon::now(),
                    'transaction_id' => $transaction->id,
                    'tenant_id' => $invoice->tenant_id,
                    'user_id' => $clientUser->id,
                    'is_approved' => Payment::APPROVED,
                    'notes' => $notes,
                ];

                $payment = Payment::create($paymentData);

                //notification
                $title = 'Payment '.getInvoiceCurrencySymbol($invoice->currency_id).$paymentAmount.' received successfully for #'.$invoice->invoice_id.'.';

                $userId = User::withoutGlobalScope(new TenantScope())->role(Role::ROLE_ADMIN)->where('tenant_id', $invoice->client->tenant_id)->first()->id;

                addNotification([
                    Notification::NOTIFICATION_TYPE['Invoice Payment'],
                    $userId,
                    $title,
                ]);

                $invoiceData = [];
                $invoiceData['amount'] = $payment->amount;
                $invoiceData['payment_date'] = $payment->payment_date;
                $invoiceData['invoice_id'] = $invoice->id;
                $invoiceData['invoice'] = $invoice;
                $invoiceData['first_name'] = $clientUser->first_name;
                $invoiceData['last_name'] = $clientUser->last_name;
                session()->forget('userId');

                if (getSettingValue('mail_notification')) {
                    Mail::to($clientUser->email)->send(new ClientMakePaymentMail($invoiceData));
                }

                Flash::success(__('messages.flash.payment_done'));

                DB::commit();

                if(! Auth::check()) {
                    return redirect(route('invoice-show-url', $invoice->invoice_id));
                }

                return redirect(route('client.invoices.index'));
            } catch (Exception $e) {
                DB::rollBack();
                throw new UnprocessableEntityHttpException($e->getMessage());
            }
        }

        return redirect()->back();
    }

    public function paymentFailed(Request $request): Redirector|RedirectResponse|Application
    {
        $data = $request->get('data');
        Log::info('payment failed');
        Log::info($data);

        Flash::error(__('messages.flash.your_payment_cancelled'));

        return redirect(route('client.invoices.index'));
    }

    public function paymentSuccessWebHook(Request $request): bool
    {
        $input = $request->all();
        Log::info('webHook Razorpay');
        Log::info($input);
        if (isset($input['event']) && $input['event'] == 'payment.captured' && isset($input['payload']['payment']['entity'])) {
            $payment = $input['payload']['payment']['entity'];
        }

        return false;
    }
}
