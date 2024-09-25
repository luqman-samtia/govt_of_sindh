<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCurrencyRequest;
use App\Http\Requests\UpdateCurrencyRequest;
use App\Models\Currency;
use App\Models\Invoice;
use App\Repositories\CurrencyRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class CurrencyController extends AppBaseController
{
    /** @var CurrencyRepository */
    public $currencyRepository;

    public function __construct(CurrencyRepository $currencyRepo)
    {
        $this->currencyRepository = $currencyRepo;
    }

    public function index(): \Illuminate\View\View
    {
        return view('currencies.index');
    }

    public function store(CreateCurrencyRequest $request): JsonResponse
    {
        $input = $request->all();
        $currency = $this->currencyRepository->create($input);

        return $this->sendResponse($currency, __('messages.flash.currency_saved'));
    }

    public function edit(Currency $currency): JsonResponse
    {
        return $this->sendResponse($currency, __('messages.flash.currency_retrieved'));
    }

    public function update(UpdateCurrencyRequest $request, $currencyId): JsonResponse
    {
        $input = $request->all();
        $this->currencyRepository->update($input, $currencyId);

        return $this->sendSuccess(__('messages.flash.currency_updated'));
    }

    public function destroy(Currency $currency): JsonResponse
    {
        $invoiceModels = [
            Invoice::class,
        ];
        $result = canDelete($invoiceModels, 'currency_id', $currency->id);
        if ($result) {
            return $this->sendError(__('messages.flash.currency_cant_deleted'));
        }
        $currency->delete();

        return $this->sendSuccess(__('messages.flash.currency_deleted'));
    }
}
