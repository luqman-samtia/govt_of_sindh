<?php

namespace App\Http\Controllers;

use App\Models\InvoiceSetting;
use App\Models\Setting;
use App\Repositories\SettingRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class InvoiceTemplateController extends AppBaseController
{
    protected $settingRepository;

    public function __construct(SettingRepository $settingRepo)
    {
        $this->settingRepository = $settingRepo;
    }
    
    public function invoiceTemplateView(): \Illuminate\View\View
    {
        $invoiceTemplate = InvoiceSetting::all()->toArray();
        $defaultTemplate = Setting::where('key', 'default_invoice_template')->first();

        return view('settings.setting-invoice', compact('invoiceTemplate', 'defaultTemplate'));
    }

    public function invoiceTemplateUpdate(Request $request): RedirectResponse
    {
        $this->settingRepository->updateInvoiceSetting($request->all());
        Flash::success(__('messages.flash.invoice_template_updated'));

        return redirect()->route('invoiceTemplate');
    }
}
