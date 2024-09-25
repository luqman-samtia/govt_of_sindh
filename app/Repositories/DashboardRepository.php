<?php

namespace App\Repositories;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use Stancl\Tenancy\Database\TenantScope;

/**
 * Class DashboardRepository
 */
class DashboardRepository
{
    public function getFieldsSearchable()
    {
        // TODO: Implement getFieldsSearchable() method.
    }

    public function model(): string
    {
        return Dashboard::class;
    }

    public function getAdminDashboardData()
    {
        $user = Auth::user();
        $invoice = Invoice::all();
        $data['total_invoices'] = $invoice->count();
        $data['total_clients'] = User::with(['client', 'client.invoices', 'clients'])->whereHas('clients',
            function (Builder $q) use ($user) {
                $q->where('tenant_id', $user->tenant_id);
            })->withoutGlobalScope(new TenantScope())->count();
        $data['total_products'] = Product::count();
        $data['paid_invoices'] = $invoice->where('status', Invoice::PAID)->count();
        $data['unpaid_invoices'] = $invoice->where('status', Invoice::UNPAID)->count();
        $data['partially_paid'] = $invoice->where('status', Invoice::PARTIALLY)->count();
        $data['overdue_invoices'] = $invoice->where('status', Invoice::OVERDUE)->count();

        return $data;
    }

    public function getClientDashboardData(): array
    {
        $clientId = Auth::user()->client->id;
        $invoice = Invoice::whereClientId($clientId)->where('status', '!=', Invoice::DRAFT)->get();
        $data['total_invoices'] = $invoice->count();
        $data['total_products'] = Product::count();
        $data['paid_invoices'] = $invoice->where('status', Invoice::PAID)->count();
        $data['unpaid_invoices'] = $invoice->where('status', Invoice::UNPAID)->count();

        return $data;
    }

    public function getPaymentOverviewData(): array
    {
        $user = Auth::user();
        $data = [];
        /** @var Invoice $invoices */
        $invoices = Invoice::all();
        $data['total_records'] = $invoices->count();
        $data['received_amount'] = Payment::sum('amount');
        $data['invoice_amount'] = $invoices->where('status', '!=', Invoice::DRAFT)->sum('final_amount');
        $data['due_amount'] = $data['invoice_amount'] - $data['received_amount'];
        $data['labels'] = [
            __('messages.received_amount'),
            __('messages.invoice.due_amount'),
        ];
        $data['dataPoints'] = [$data['received_amount'], $data['due_amount']];

        return $data;
    }

    public function getInvoiceOverviewData(): array
    {
        $user = Auth::user();
        $data = [];
        $invoice = Invoice::all();
        $data['total_paid_invoices'] = $invoice->where('status', Invoice::PAID)->count();
        $data['total_unpaid_invoices'] = $invoice->where('status', Invoice::UNPAID)->count();
        $data['labels'] = [
            __('messages.paid_invoices'),
            __('messages.unpaid_invoices'),
        ];
        $data['dataPoints'] = [$data['total_paid_invoices'], $data['total_unpaid_invoices']];

        return $data;
    }

    public function prepareYearlyIncomeChartData($input): array
    {
        $start_date = Carbon::parse($input['start_date'])->format('Y-m-d');
        $end_date = Carbon::parse($input['end_date'])->format('Y-m-d');

        $income = Payment::whereBetween('payment_date', [date($start_date), date($end_date)])
        ->selectRaw("DATE_FORMAT(payment_date, '%b %d') as month, SUM(amount) as total_income")
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->keyBy('month');

        $period = CarbonPeriod::create($start_date, $end_date);
        $labelsData = array_map(function ($datePeriod) {
            return $datePeriod->format('M d');
        }, iterator_to_array($period));

        $incomeOverviewData = array_map(function ($datePeriod) use ($income) {
            $month = $datePeriod->format('M d');

            return $income->has($month) ? $income->get($month)->total_income : 0;
        }, iterator_to_array($period));

        $data['labels'] = $labelsData;
        $data['yearly_income'] = $incomeOverviewData;

        return $data;
    }

    public function prepareRevenueChartData($input): array
    {
        $start_date = Carbon::parse($input['start_date']);
        $end_date = Carbon::parse($input['end_date'])->endOfDay();

        $revenue = Transaction::with(['transactionSubscription.subscriptionPlan', 'user.media'])
        ->whereHas('transactionSubscription')
        ->whereBetween('created_at', [date($start_date), date($end_date)])
        ->selectRaw("DATE_FORMAT(created_at, '%b %d') as month, SUM(amount) as total_revenue")
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->keyBy('month');

        $period = CarbonPeriod::create($start_date, $end_date);
        $labelsData = array_map(function ($datePeriod) {
            return $datePeriod->format('M d');
        }, iterator_to_array($period));

        $revenueData = array_map(function ($datePeriod) use ($revenue) {
            $month = $datePeriod->format('M d');

            return $revenue->has($month) ? $revenue->get($month)->total_revenue : 0;
        }, iterator_to_array($period));

        $data['labels'] = $labelsData;
        $data['yearly_revenue'] = $revenueData;

        return $data;
    }

    public function getTotalActiveDeActiveUserPlans(): array
    {
        $activePlansCount = 0;
        $deActivePlansCount = 0;
        $subscriptions = Subscription::whereStatus(Subscription::ACTIVE)->get();
        foreach ($subscriptions as $sub) {
            if (! $sub->isExpired()) {   // active plans
                $activePlansCount++;
            } else {
                $deActivePlansCount++;
            }
        }

        return ['activePlansCount' => $activePlansCount, 'deActivePlansCount' => $deActivePlansCount];
    }

    public function getAdminCurrencyData()
    {
        if(getLoggedInUser()->hasRole(Role::ROLE_CLIENT)) {
            $clientId = Auth::user()->client->id;
            $invoice = Invoice::whereClientId($clientId);
        }else {
            $invoice = Invoice::query();
        }

        $totalInvoices = $invoice->where('status', '!=', Invoice::DRAFT)->get()->groupBy('currency_id');
        $invoiceIds = $invoice->pluck('id')->toArray();
        $paidInvoices = Payment::with('invoice')->where(function ($q) {
                            $q->where('payment_mode', Payment::MANUAL)
                                ->where('is_approved', Payment::APPROVED);
                            $q->orWhere('payment_mode', '!=', Payment::MANUAL);
                        })->whereIn('invoice_id', $invoiceIds)
                        ->get()->groupBy('invoice.currency_id');

        $totalInvoiceAmountArr = [];
        $paidInvoicesArr = [];
        $dueInvoicesArr = [];
        $defaultCurrencyId = getSettingValue('current_currency');

        foreach ($totalInvoices as $currencyId => $totalInvoice) {
            if (empty($currencyId)) {
                $totalInvoiceAmountArr[$defaultCurrencyId] = $totalInvoice->sum('final_amount');
            } else {
                $totalInvoiceAmountArr[$currencyId] = $totalInvoice->sum('final_amount');
            }
        }

        foreach ($paidInvoices as $currencyId => $paidInvoice) {
            if (empty($currencyId)) {
                $paidInvoicesArr[$defaultCurrencyId] = $paidInvoice->sum('amount');
                $dueInvoicesArr[$defaultCurrencyId] = $totalInvoiceAmountArr[$defaultCurrencyId] - $paidInvoice->sum('amount');
            } else {
                $paidInvoicesArr[$currencyId] = $paidInvoice->sum('amount');
                $dueInvoicesArr[$currencyId] = $totalInvoiceAmountArr[$currencyId] - $paidInvoice->sum('amount');
            }
        }

        ksort($totalInvoiceAmountArr);
        ksort($paidInvoicesArr);
        ksort($dueInvoicesArr);
        $data['totalInvoices'] = $totalInvoiceAmountArr;
        $data['paidInvoices'] = $paidInvoicesArr;
        $data['dueInvoices'] = $dueInvoicesArr;

        return $data;
    }
}
