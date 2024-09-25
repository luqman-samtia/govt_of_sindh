<?php

namespace App\Http\Controllers;

use App\Exports\SubscriptionTransactionsExport;
use App\Http\Requests\CreateSubscriptionPlanRequest;
use App\Http\Requests\UpdateSubscriptionPlanRequest;
use App\Models\Notification;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use App\Repositories\SubscriptionPlanRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Flash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\MediaLibrary\Support\MediaStream;

class SubscriptionPlanController extends AppBaseController
{
    private $subscriptionPlanRepository;

    public function __construct(SubscriptionPlanRepository $subscriptionPlanRepo)
    {
        $this->subscriptionPlanRepository = $subscriptionPlanRepo;
    }

    /**
     *
     * @throws Exception
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $planType = SubscriptionPlan::PLAN_TYPE;

        return view('subscription_plans.index', compact('planType'));
    }

    /**
     * Show the form for creating a new Service.
     *
     */
    public function create(): \Illuminate\View\View
    {
        $planType = SubscriptionPlan::PLAN_TYPE;
        $currencies = getAdminCurrencies();

        return view('subscription_plans.create', compact('planType', 'currencies'));
    }

    public function store(CreateSubscriptionPlanRequest $request): RedirectResponse
    {
        $input = $request->all();
        $this->subscriptionPlanRepository->store($input);
        Flash::success(__('messages.flash.subscription_plan_created'));

        return redirect(route('subscription-plans.index'));
    }

    public function edit(SubscriptionPlan $subscriptionPlan): \Illuminate\View\View
    {
        $planType = SubscriptionPlan::PLAN_TYPE;
        $currencies = getAdminCurrencies();

        return view('subscription_plans.edit',
            compact('subscriptionPlan', 'planType', 'currencies'));
    }

    public function update(UpdateSubscriptionPlanRequest $request, SubscriptionPlan $subscriptionPlan): RedirectResponse
    {
        $input = $request->all();
        $this->subscriptionPlanRepository->update($input, $subscriptionPlan->id);
        Flash::success(__('messages.flash.subscription_plan_updated'));

        return redirect(route('subscription-plans.index'));
    }

    public function show(SubscriptionPlan $subscriptionPlan): \Illuminate\View\View
    {
        $subscriptionPlan->load(['subscription', 'currencies']);

        return view('subscription_plans.show', compact('subscriptionPlan'));
    }

    public function destroy(SubscriptionPlan $subscriptionPlan)
    {
        $result = Subscription::where('subscription_plan_id', $subscriptionPlan->id)->where('status',
            Subscription::ACTIVE)->count();
        if ($result > 0) {
            return $this->sendError(__('messages.flash.subscription_plan_cant_deleted'));
        }
        $subscriptionPlan->delete();

        return $this->sendSuccess(__('messages.flash.subscription_plan_deleted'));
    }

    public function showTransactionsLists(): View|Factory|Application
    {
        $paymentTypes = Transaction::PAYMENT_TYPES;

        return view('subscription_transactions.index', compact('paymentTypes'));
    }

    public function viewTransaction(Subscription $subscription): View|Factory|Application
    {
        $subscription->load(['subscriptionPlan', 'user']);

        return view('subscription_transactions.show', compact('subscription'));
    }

    public function makePlanDefault(int $id): JsonResponse
    {
        $defaultSubscriptionPlan = SubscriptionPlan::where('is_default', 1)->first();
        $defaultSubscriptionPlan->update(['is_default' => 0]);
        $subscriptionPlan = SubscriptionPlan::findOrFail($id);
        if ($subscriptionPlan->trial_days == 0) {
            $subscriptionPlan->trial_days = SubscriptionPlan::TRAIL_DAYS;
        }
        $subscriptionPlan->is_default = 1;
        $subscriptionPlan->save();

        return $this->sendSuccess(__('messages.flash.default_plan_changed'));
    }

    public function changePaymentStatus(Request $request)
    {
        $input = $request->all();
        $transaction = Transaction::with('transactionSubscription', 'user')->findOrFail($input['id']);

        if ($input['status'] == Transaction::APPROVED) {
            $subscription = $transaction->transactionSubscription;

            DB::table('transactions')
                ->where('id', $transaction->id)
                ->update([
                    'is_manual_payment' => $input['status'],
                    'status' => Subscription::ACTIVE,
                    'tenant_id' => $transaction->user->tenant_id,
                ]);

            Subscription::findOrFail($subscription->id)->update(['status' => Subscription::ACTIVE]);

            Subscription::whereUserId($subscription->user_id)
                ->where('id', '!=', $subscription->id)
                ->update([
                    'status' => Subscription::INACTIVE,
                ]);

            $subscription->update(['status', Subscription::ACTIVE]);

            $subscriptionAmount = $subscription->plan_amount;
            $subscriptionPlanCurrency = $subscription->subscriptionPlan->currencies->icon;
            $title = 'You successfully received subscription plan amount '.$subscriptionPlanCurrency.$subscriptionAmount.' from '.$transaction->user->full_name.'.';
            addNotification([
                Notification::NOTIFICATION_TYPE['Subscription Plan Purchased'],
                getSuperAdmin()->id,
                $title,
            ]);

            return $this->sendSuccess(__('messages.flash.manual_payment_approved'));
        }

        if ($input['status'] == Transaction::DENIED) {
            $subscription = $transaction->transactionSubscription;

            DB::table('transactions')
                    ->where('id', $transaction->id)
                    ->update([
                        'is_manual_payment' => $input['status'],
                        'status' => Subscription::INACTIVE,
                        'tenant_id' => $transaction->user->tenant_id,
                    ]);

            $subscription->delete();

            return $this->sendSuccess(__('messages.flash.manual_payment_Denied'));
        }
    }

    public function downloadAttachments($transactionId): MediaStream
    {
        $transaction = Transaction::with('media')->find($transactionId);

        return MediaStream::create('payment-attachments.zip')->addMedia($transaction->media);
    }

    public function exportSubscriptionTransactionsExcel()
    {
        return Excel::download(new SubscriptionTransactionsExport(), 'subscription-transactions.xlsx');
    }

    public function exportSubscriptionTransactionsPdf()
    {
        $subscriptionTransactions = Transaction::with(['transactionSubscription.subscriptionPlan','user'])->whereHas('transactionSubscription')->orderBy('created_at', 'desc')->get();
        $data['subscriptionTransactions'] = $subscriptionTransactions;
        $pdf = Pdf::loadView('subscription_transactions.subscription_transactions_pdf', $data);

        return $pdf->download('subscription-transactions.pdf');
    }
}
