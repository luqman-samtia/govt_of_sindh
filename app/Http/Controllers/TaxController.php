<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaxRequest;
use App\Http\Requests\UpdateTaxRequest;
use App\Models\InvoiceItemTax;
use App\Models\Tax;
use App\Repositories\TaxRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TaxController extends AppBaseController
{
    /** @var TaxRepository */
    public $taxRepository;

    public function __construct(TaxRepository $taxRepo)
    {
        $this->taxRepository = $taxRepo;
    }

    public function index(): \Illuminate\View\View
    {
        return view('taxes.index');
    }

    public function store(CreateTaxRequest $request)
    {
        $input = $request->all();
        if ($input['is_default'] == '1') {
            Tax::where('is_default', '=', 1)->update(['is_default' => 0]);
            $tax = $this->taxRepository->create($input);
        } else {
            $tax = $this->taxRepository->create($input);
        }

        return $this->sendResponse($tax, __('messages.flash.tax_saved'));
    }

    public function edit(Tax $tax)
    {
        if ($tax->tenant_id != Auth::user()->tenant_id) {
            return $this->sendError(__('Seems, you are not allowed to access this record.'));
        }

        return $this->sendResponse($tax, __('messages.flash.tax_retrieved'));
    }

    public function update(UpdateTaxRequest $request, $taxId)
    {
        $input = $request->all();
        if ($input['is_default'] == '1') {
            Tax::where('is_default', '=', 1)->update(['is_default' => 0]);
            $this->taxRepository->update($input, $taxId);
        } else {
            $this->taxRepository->update($input, $taxId);
        }

        return $this->sendSuccess(__('messages.flash.tax_updated'));
    }

    public function destroy(Tax $tax)
    {
        if ($tax->tenant_id != Auth::user()->tenant_id) {
            return $this->sendError(__('Seems, you are not allowed to access this record.'));
        }
        $invoiceModels = [
            InvoiceItemTax::class,
        ];
        $result = canDelete($invoiceModels, 'tax_id', $tax->id);
        if ($result) {
            return $this->sendError(__('messages.flash.tax_can_not_deleted'));
        }
        $tax->delete();

        return $this->sendSuccess(__('messages.flash.tax_deleted'));
    }

    public function defaultStatus($id): JsonResponse
    {
        $tax = Tax::whereId($id)->whereTenantId(getLogInUser()->tenant_id)->first();

        if (! $tax) {
            return $this->sendError(__('Seems, you are not allowed to access this record.'));
        }

        $status = ! $tax->is_default;
        if ($status == '1') {
            Tax::where('is_default', '=', 1)->update(['is_default' => 0]);
        }

        $tax->update(['is_default' => $status]);

        return $this->sendSuccess(__('messages.flash.status_updated'));
    }
}
