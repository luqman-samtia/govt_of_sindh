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
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Laracasts\Flash\Flash;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Stancl\Tenancy\Database\TenantScope;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class PaypalController extends AppBaseController
{
    public function onBoard(Request $request)
    {
        $invoiceId = $request->get('invoiceId');
        $payable_amount = $request->get('amount');
        session(['notes' => $request->get('notes')]);

        /** @var Invoice $invoice */
        $invoice = Invoice::with('client.user')->whereId($invoiceId)->firstOrFail();
        $user = $invoice->client->user;
        $invoiceCurrencyId = $invoice->currency_id;

        $currencyCode = strtoupper(getInvoiceCurrencyCode($invoiceCurrencyId, $user->tenant_id));
        if (! in_array($currencyCode, getPayPalSupportedCurrencies())) {
            return $this->sendError($currencyCode.' is not currently supported.');
        }

        config([
            'paypal.mode' => config('payments.paypal.mode'),
            'paypal.sandbox.client_id' => getPaypalClientId(),
            'paypal.sandbox.client_secret' => getPaypalSecret(),
            'paypal.live.client_id' => getPaypalClientId(),
            'paypal.live.client_secret' => getPaypalSecret(),
        ]);

        try {
            $provider = new PayPalClient();
            $provider->getAccessToken();

            $data = [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => $invoiceId,
                        'amount' => [
                            'value' => $payable_amount,
                            'currency_code' => $currencyCode,
                        ],
                    ],
                ],
                'application_context' => [
                    'cancel_url' => route('client.paypal.failed'),
                    'return_url' => route('client.paypal.success'),
                ],
            ];

            $order = $provider->createOrder($data);

            return response()->json($order);
        } catch (Exception $exception) {
            throw new UnprocessableEntityHttpException($exception->getMessage());
        }
    }

    public function onBoardForPublic(Request $request)
    {
        $invoiceId = $request->get('invoiceId');
        $payable_amount = $request->get('amount');
        session(['notes' => $request->get('notes'), 'invoiceId' => $invoiceId]);

        /** @var Invoice $invoice */
        $invoice = Invoice::with('client.user')->whereId($invoiceId)->firstOrFail();
        $user = $invoice->client->user;
        $invoiceCurrencyId = $invoice->currency_id;

        $currencyCode = strtoupper(getInvoiceCurrencyCode($invoiceCurrencyId, $user->tenant_id));
        if (! in_array($currencyCode, getPayPalSupportedCurrencies())) {
            return $this->sendError($currencyCode.' is not currently supported.');
        }

        config([
            'paypal.mode' => config('payments.paypal.mode'),
            'paypal.sandbox.client_id' => getPaypalClientIdForPublic($user->tenant_id),
            'paypal.sandbox.client_secret' => getPaypalSecretForPublic($user->tenant_id),
            'paypal.live.client_id' => getPaypalClientIdForPublic($user->tenant_id),
            'paypal.live.client_secret' => getPaypalSecretForPublic($user->tenant_id),
        ]);

        try {
            $provider = new PayPalClient();
            $provider->getAccessToken();

            $data = [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => $invoiceId,
                        'amount' => [
                            'value' => $payable_amount,
                            'currency_code' => $currencyCode,
                        ],
                    ],
                ],
                'application_context' => [
                    'cancel_url' => route('public.paypal.failed'),
                    'return_url' => route('public.paypal.success'),
                ],
            ];

            $order = $provider->createOrder($data);

            return response()->json($order);
        } catch (Exception $exception) {
            throw new UnprocessableEntityHttpException($exception->getMessage());
        }
    }

    public function failed(): RedirectResponse
    {
        Flash::error(__('messages.flash.your_payment_cancelled'));

        if(! Auth::check()) {
            $invoice = Invoice::findOrFail(session('invoiceId'));

            return redirect()->route('invoice-show-url', $invoice->invoice_id);
        }

        return redirect()->route('client.invoices.index');
    }

    public function success(Request $request)
    {
        $invoiceId = session('invoiceId');
        /** @var Invoice $invoice */
        $invoice = Invoice::with(['client.user', 'payments'])->findOrFail($invoiceId);

        if(! Auth::check()) {
            $user = $invoice->client->user;

            config([
                'paypal.mode' => config('payments.paypal.mode'),
                'paypal.sandbox.client_id' => getPaypalClientIdForPublic($user->tenant_id),
                'paypal.sandbox.client_secret' => getPaypalSecretForPublic($user->tenant_id),
                'paypal.live.client_id' => getPaypalClientIdForPublic($user->tenant_id),
                'paypal.live.client_secret' => getPaypalSecretForPublic($user->tenant_id),
            ]);
        }else {
            config([
                'paypal.mode' => config('payments.paypal.mode'),
                'paypal.sandbox.client_id' => getPaypalClientId(),
                'paypal.sandbox.client_secret' => getPaypalSecret(),
                'paypal.live.client_id' => getPaypalClientId(),
                'paypal.live.client_secret' => getPaypalSecret(),
            ]);
        }

        $provider = new PayPalClient();
        $provider->getAccessToken();
        $token = $request->get('token');
        $orderInfo = $provider->showOrderDetails($token);

        try {
            // Call API with your client and get a response for your call
            $response = $provider->capturePaymentOrder($token);
            // If call returns body in response, you can get the deserialized version from the result attribute of the response
            // $invoiceId = $response['purchase_units'][0]['reference_id'];
            $amount = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];

            $transactionId = $response['id'];

            $input['user_id'] = User::withoutGlobalScope(new TenantScope())->role(Role::ROLE_ADMIN)->where('tenant_id', $invoice->client->tenant_id)->first()->id;
            $clientUser = $invoice->client->user;
            $transactionDetails = [
                'transaction_id' => $transactionId,
                'amount' => $amount,
                'status' => Transaction::PAID,
                'payment_mode' => Transaction::TYPE_PAYPAL,
                'meta' => json_encode($response),
                'tenant_id' => $invoice->tenant_id,
                'user_id' => $clientUser->id,
            ];

            $transaction = Transaction::create($transactionDetails);

            if ($invoice->status == Payment::PARTIALLYPAYMENT) {
                $totalAmount = ($invoice->final_amount - $invoice->payments->sum('amount'));
            } else {
                $totalAmount = $invoice->final_amount;
            }

            $notes = session('notes');

            $paymentData = [
                'invoice_id' => $invoiceId,
                'amount' => $amount,
                'payment_mode' => Payment::PAYPAL,
                'payment_date' => Carbon::now(),
                'transaction_id' => $transaction->id,
                'tenant_id' => $invoice->tenant_id,
                'user_id' => $clientUser->id,
                'is_approved' => Payment::APPROVED,
                'notes' => $notes,
            ];

            $invoicePayment = Payment::create($paymentData);

            $invoiceData = [];
            $invoiceData['amount'] = $invoicePayment['amount'];
            $invoiceData['payment_date'] = $invoicePayment['payment_date'];
            $invoiceData['invoice_id'] = $invoicePayment['invoice_id'];
            $invoiceData['invoice'] = $invoicePayment->invoice;
            $invoiceData['first_name'] = $clientUser->first_name;
            $invoiceData['last_name'] = $clientUser->last_name;
            session()->forget('notes');
            session()->forget('invoiceId');

            if (getSettingValue('mail_notification')) {
                Mail::to(getAdminUser()->email)->send(new ClientMakePaymentMail($invoiceData));
            }
            if (round($totalAmount, 2) == $amount) {
                $invoice->status = Payment::FULLPAYMENT;
                $invoice->save();
            } else {
                if (round($totalAmount, 2) != $amount) {
                    $invoice->status = Payment::PARTIALLYPAYMENT;
                    $invoice->save();
                }
            }

            Flash::success(__('messages.flash.payment_done'));

            $userId = User::withoutGlobalScope(new TenantScope())->role(Role::ROLE_ADMIN)->where('tenant_id', $invoice->client->tenant_id)->first()->id;
            $title = 'Payment '.getInvoiceCurrencySymbol($invoice->currency_id).$amount.' received successfully for #'.$invoice->invoice_id.'.';
            addNotification([
                Notification::NOTIFICATION_TYPE['Invoice Payment'],
                $userId,
                $title,
            ]);

            if(! Auth::check()) {
                return redirect()->route('invoice-show-url', $invoice->invoice_id);
            }

            return redirect(route('client.invoices.index'));
        } catch (HttpException $ex) {
            print_r($ex->getMessage());
        }
    }
}
