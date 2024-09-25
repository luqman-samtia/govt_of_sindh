<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAdminCurrencyRequest;
use App\Http\Requests\UpdateAdminCurrencyRequest;
use App\Models\AdminCurrency;
use App\Models\SubscriptionPlan;
use App\Repositories\AdminCurrencyRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AdminCurrencyController extends AppBaseController
{
    /**
     * @var AdminCurrencyRepository
     */
    public $adminCurrencyRepository;

    public function __construct(AdminCurrencyRepository $adminCurrencyRepo)
    {
        $this->adminCurrencyRepository = $adminCurrencyRepo;
    }

    public function index(Request $request): \Illuminate\View\View
    {
        return view('super_admin.currencies.index');
    }

    public function store(CreateAdminCurrencyRequest $request)
    {
        $input = $request->all();
        $currency = $this->adminCurrencyRepository->create($input);

        return $this->sendResponse($currency, __('messages.flash.currency_saved'));
    }

    public function edit($id)
    {
        $adminCurrency = AdminCurrency::findOrFail($id);

        return $this->sendResponse($adminCurrency, __('messages.flash.currency_retrieved'));
    }

    public function update(UpdateAdminCurrencyRequest $request, $currencyId)
    {
        $input = $request->all();
        $this->adminCurrencyRepository->update($input, $currencyId);

        return $this->sendSuccess(__('messages.flash.currency_updated'));
    }

    public function destroy($id)
    {
        $adminCurrency = AdminCurrency::findOrFail($id);
        $result = SubscriptionPlan::where('currency_id', $id)->count();
        if ($result > 0) {
            return $this->sendError(__('messages.flash.currency_cant_deleted'));
        }
        $adminCurrency->delete();

        return $this->sendSuccess('messages.flash.currency_deleted');
    }
}
