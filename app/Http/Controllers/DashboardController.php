<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\DashboardRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Laracasts\Flash\Flash;
use App\Models\Letter;
use Auth;

class DashboardController extends AppBaseController
{
    /* @var DashboardRepository */
    public $dashboardRepository;

    /**
     * DashboardController constructor.
     */
    public function __construct(DashboardRepository $dashboardRepo)
    {
        $this->dashboardRepository = $dashboardRepo;
    }

    public function index(): \Illuminate\View\View
    {
        $dashboardData = $this->dashboardRepository->getAdminDashboardData();
        $user = Auth::user()->id;
        $users_form = Letter::where(['user_id'=>$user,'is_submitted'=>1])->get();
        $draft = Letter::where(['user_id'=>$user,'is_submitted'=>0])->get();

        if ($draft !== null && $draft->isNotEmpty()) {
            $total_drafts = $draft->count();
            // Do something with $total_letters
        } else {
            // Handle the case where no letters were found
            $total_drafts = 0;
        }
        if ($users_form !== null && $users_form->isNotEmpty()) {
            $total_letters = $users_form->count();
            // Do something with $total_letters
        } else {
            // Handle the case where no letters were found
            $total_letters = 0;
        }
        return view('dashboard.index',compact('total_letters','total_drafts'))->with($dashboardData);
    }

    public function SuperAdminDashboardData(): \Illuminate\View\View
    {
        $users_form = Letter::withCount('user')->where('is_submitted',1)->get();
        $draft = Letter::where('is_submitted',0)->get();
        $query = User::whereHas('roles', function ($q) {
            $q->where('name', Role::ROLE_ADMIN);
        })->with('roles')->select('users.*');
        $data['users'] = $query->count();
        $revenue_amount = Transaction::with([
            'transactionSubscription.subscriptionPlan', 'user.media',
        ])->whereHas('transactionSubscription');
        $data['revenue'] = $revenue_amount->sum('amount');

        $subscriptionPlanCount = $this->dashboardRepository->getTotalActiveDeActiveUserPlans();
        $data['activeUserPlan'] = $subscriptionPlanCount['activePlansCount'];
        $data['deActiveUserPlan'] = $subscriptionPlanCount['deActivePlansCount'];

        return view('super_admin.dashboard.index', compact('data','users_form','draft'));
    }

    public function paymentOverview()
    {
        $data = $this->dashboardRepository->getPaymentOverviewData();

        return $this->sendResponse($data, __('messages.flash.payment_overview_retrieved'));
    }

    public function invoiceOverview()
    {
        $data = $this->dashboardRepository->getInvoiceOverviewData();

        return $this->sendResponse($data, __('messages.flash.payment_overview_retrieved'));
    }

    public function getYearlyIncomeChartData(Request $request): JsonResponse
    {
        $input = $request->all();

        $data = $this->dashboardRepository->prepareYearlyIncomeChartData($input);

        return $this->sendResponse($data, __('messages.flash.income_overview_retrieved'));
    }

    public function getRevenueChartData(Request $request): JsonResponse
    {
        $input = $request->all();
        $data = $this->dashboardRepository->prepareRevenueChartData($input);

        return $this->sendResponse($data, __('messages.flash.Revenue_data_retrieved'));
    }

    public function clearCache(): RedirectResponse
    {
        Artisan::call('cache:clear');
        Flash::success(__('messages.flash.cache_cleared'));

        return redirect()->back();
    }

    public function currencyReports(): View|Factory|Application
    {
        $currencyData = $this->dashboardRepository->getAdminCurrencyData();

        return view('dashboard.currency_reports', compact('currencyData'));
    }
}
