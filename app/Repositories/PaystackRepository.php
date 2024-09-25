<?php

namespace App\Repositories;

use App\Mail\ClientMakePaymentMail;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Stancl\Tenancy\Database\TenantScope;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class PaystackRepository
 */
class PaystackRepository
{
    public function paymentSuccessForInvoice($invoiceId, $transactionId, $amount, $metaData)
    {
        try {
            DB::beginTransaction();

            /** @var Invoice $invoice */
            $invoice = Invoice::with(['payments', 'client','client.user'])->findOrFail($invoiceId);
            $clientUser = $invoice->client->user;

            $transactionData = [
                'transaction_id' => $transactionId,
                'amount' => $amount,
                'user_id' => $clientUser->id,
                'status' => Transaction::PAID,
                'payment_mode' => Transaction::TYPE_PAYSTACK,
                'tenant_id' => $invoice->tenant_id,
                'meta' => $metaData,
            ];

            $transaction = Transaction::create($transactionData);

            if ($invoice->status == Payment::PARTIALLYPAYMENT) {
                $totalAmount = ($invoice->final_amount - $invoice->payments->sum('amount'));
            } else {
                $totalAmount = $invoice->final_amount;
            }

            $note = session('note');

            $paymentData = [
                'invoice_id' => $invoiceId,
                'amount' => $amount,
                'payment_mode' => Payment::PAYSTACK,
                'payment_date' => Carbon::now(),
                'transaction_id' => $transaction->id,
                'tenant_id' => $invoice->tenant_id,
                'user_id' => $clientUser->id,
                'is_approved' => Payment::APPROVED,
                'notes' => $note,
            ];

            // update invoice bill
            $invoicePayment = Payment::create($paymentData);
            session()->forget('note');

            $invoiceData = [];
            $invoiceData['amount'] = $invoicePayment['amount'];
            $invoiceData['payment_date'] = $invoicePayment['payment_date'];
            $invoiceData['invoice_id'] = $invoicePayment['invoice_id'];
            $invoiceData['invoice'] = $invoicePayment->invoice;
            $invoiceData['first_name'] = $clientUser->first_name;
            $invoiceData['last_name'] = $clientUser->last_name;

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

            $userId = User::withoutGlobalScope(new TenantScope())->role(Role::ROLE_ADMIN)->where('tenant_id', $invoice->client->tenant_id)->first()->id;
            $title = 'Payment '.getInvoiceCurrencySymbol($invoice->currency_id).$amount.' received successfully for #'.$invoice->invoice_id.'.';
            addNotification([
                Notification::NOTIFICATION_TYPE['Invoice Payment'],
                $userId,
                $title,
            ]);

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();

            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function paymentSuccessForPlan($subscriptionId, $transactionId, $amount, $metaData)
    {
        try{
            $subscription = Subscription::findOrFail($subscriptionId)->update(['status' => Subscription::ACTIVE]);
            $subscriptionPlan = Subscription::where('id', $subscriptionId)->first();
            $subscriptionPlanIds = $subscriptionPlan->subscription_plan_id;
            $subscriptionPlanData = SubscriptionPlan::whereId($subscriptionPlanIds)->first();
            $subscriptionPlanCurrency = getAdminSubscriptionPlanCurrencyIcon($subscriptionPlanData->currency_id);  // $response->result->id gives the orderId of the order created above

            // De-Active all other subscription
            Subscription::whereUserId(getLogInUserId())
                ->where('id', '!=', $subscriptionId)
                ->update([
                    'status' => Subscription::INACTIVE,
                ]);

            $user = Auth::user();
            $transaction = Transaction::create([
                'transaction_id' => $transactionId,
                'payment_mode' => Transaction::TYPE_PAYSTACK,
                'amount' => $amount,
                'user_id' => $user->id,
                'tenant_id' => $user->tenant_id,
                'status' => Transaction::PAID,
                'meta' => $metaData,
            ]);

            $totalAmount = $amount;
            $title = 'You successfully received subscription plan amount '.$subscriptionPlanCurrency.$totalAmount.' from '.Auth::user()->full_name.'.';
            addNotification([
                Notification::NOTIFICATION_TYPE['Subscription Plan Purchased'],
                getSuperAdmin()->id,
                $title,
            ]);

            // updating the transaction id on the subscription table
            $subscription = Subscription::with('subscriptionPlan')->findOrFail($subscriptionId);
            $subscription->update(['transaction_id' => $transaction->id]);

            return $subscription;
        }catch(Exception $e) {
            DB::rollBack();

            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
