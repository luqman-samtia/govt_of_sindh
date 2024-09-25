<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\AppBaseController;
use App\Models\Invoice;
use App\Models\Setting;
use App\Repositories\StripeRepository;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class StripeController extends AppBaseController
{
    /**
     * @var StripeRepository
     */
    private $stripeRepository;

    public function __construct(StripeRepository $stripeRepository)
    {
        $this->stripeRepository = $stripeRepository;
    }

    public function createSession(Request $request)
    {
        $amount = $request->get('amount');
        $invoice = $request->get('invoiceId');
        session(['notes' => $request->get('notes')]);

        $invoiceData = Invoice::with('client.user')->whereId($invoice)->first();
        $invoiceId = $invoiceData->invoice_id;
        $user = $invoiceData->client->user;

        $currencyCode = strtoupper(getInvoiceCurrencyCode($invoiceData->currency_id, $user->tenant_id));
        if (! in_array($currencyCode, getStripeSupportedCurrencies())) {
            $message = strtoupper($currencyCode).' is not currently supported.';

            return $this->sendError($message);
        }

        $userEmail = $user->email;

        setStripeApiKey();
        $session = Session::create([
            'payment_method_types' => ['card'],
            'customer_email' => $userEmail,
            'line_items' => [
                [
                    'price_data' => [
                        'product_data' => [
                            'name' => 'BILL OF PRODUCT #'.$invoiceId,
                            'description' => 'BILL OF PRODUCT #'.$invoiceId,
                        ],
                        'unit_amount' => ($currenyCode != 'JPY') ? $amount * 100 : $amount,
                        'currency' => $currenyCode,
                    ],
                    'quantity' => 1,
                ],
            ],
            'billing_address_collection' => 'auto',
            'client_reference_id' => $invoice,
            'mode' => 'payment',
            'success_url' => url('client/payment-success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => url('client/failed-payment?error=payment_cancelled'),
        ]);
        $result = [
            'sessionId' => $session['id'],
        ];

        return $this->sendResponse($result, __('messages.flash.session_created'));
    }

    public function createSessionForPublic(Request $request)
    {
        $amount = $request->get('amount');
        $fetchInvoiceId = $request->get('invoiceId');
        session(['notes' => $request->get('notes'),'invoiceId' => $fetchInvoiceId]);
        $invoiceData = Invoice::with('client.user')->whereId($fetchInvoiceId)->first();
        $invoiceId = $invoiceData->invoice_id;
        $user = $invoiceData->client->user;

        $currenyCode = strtoupper(getInvoiceCurrencyCode($invoiceData->currency_id, $user->tenant_id));
        if (! in_array($currenyCode, getStripeSupportedCurrencies())) {
            $message = strtoupper($currenyCode).' is not currently supported.';

            return $this->sendError($message);
        }

        $userEmail = $user->email;
        $stripeSecret = Setting::where('tenant_id', $user->tenant_id)->where('key', 'stripe_secret')->first();

        Stripe::setApiKey($stripeSecret->value);
        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'customer_email' => $userEmail,
                'line_items' => [
                    [
                        'price_data' => [
                            'product_data' => [
                                'name' => 'BILL OF PRODUCT #'.$invoiceId,
                                'description' => 'BILL OF PRODUCT #'.$invoiceId,
                            ],
                            'unit_amount' => ($currenyCode != 'JPY') ? $amount * 100 : $amount,
                            'currency' => $currenyCode,
                        ],
                        'quantity' => 1,
                    ],
                ],
                'billing_address_collection' => 'auto',
                'client_reference_id' => $fetchInvoiceId,
                'mode' => 'payment',
                'success_url' => url('stripe-payment-success').'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => url('failed-payment?error=payment_cancelled'),
            ]);
            $result = [
                'sessionId' => $session['id'],
            ];

            return $this->sendResponse($result, __('messages.flash.session_created'));
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function paymentSuccess(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (empty($sessionId)) {
            throw new UnprocessableEntityHttpException('session_id required');
        }

        if(!Auth::check()){
            $invoiceID = $this->stripeRepository->clientPaymentSuccessForPublic($sessionId);
            Flash::success(__('messages.flash.payment_done'));

            return redirect()->route('invoice-show-url', $invoiceID);
        }

        $this->stripeRepository->clientPaymentSuccess($sessionId);
        Flash::success(__('messages.flash.payment_done'));

        return redirect(route('client.invoices.index'));
    }

    public function handleFailedPayment(): RedirectResponse
    {
        Flash::error(__('messages.flash.your_payment_cancelled'));

        if(! Auth::check()) {
            $invoice = Invoice::findOrFail(session('invoiceId'));

            return redirect()->route('invoice-show-url', $invoice->invoice_id);
        }

        return redirect()->route('client.invoices.index');
    }
}
