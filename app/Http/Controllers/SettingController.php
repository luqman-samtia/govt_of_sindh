<?php

namespace App\Http\Controllers;

use Exception;
use Laracasts\Flash\Flash;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use App\Models\InvoiceSetting;
use App\Models\SuperAdminSetting;
use Illuminate\Routing\Redirector;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use App\Repositories\SettingRepository;
use App\Http\Requests\UpdateSettingRequest;
use Illuminate\Contracts\Foundation\Application;
use App\Http\Requests\UpdateSuperAdminSettingRequest;
use App\Http\Requests\UpdateSuperAdminFooterSettingRequest;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class SettingController extends AppBaseController
{
    /** @var SettingRepository */
    private $settingRepository;

    public function __construct(SettingRepository $settingRepo)
    {
        $this->settingRepository = $settingRepo;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $defaultSettings = $this->settingRepository->editSettingsData();
        $sectionName = ($request->section === null) ? 'general' : $request->section;
        $data = UserSetting::pluck('value', 'key')->toArray();

        return view("settings.$sectionName", compact('sectionName', 'data'), $defaultSettings);
    }

    public function update(UpdateSettingRequest $request)
    {
        $this->settingRepository->updateSetting($request->all());
        Flash::success(__('messages.flash.setting_updated'));

        return redirect()->back();
    }

    //Invoice Update
    public function invoiceUpdate(Request $request)
    {
        $this->settingRepository->updateInvoiceSetting($request->all());
        Flash::success(__('messages.flash.invoice_template_updated'));

        return redirect('admin/settings?section=setting-invoice');
    }

    public function editInvoiceTemplate($key)
    {
        $invoiceTemplate = InvoiceSetting::where('key', $key)->get();

        return $this->sendResponse($invoiceTemplate, __('messages.flash.invoice_template_retrieved'));
    }

    /**
     * Show the form for editing the specified Setting.
     *
     */
    public function editSuperAdminSettings(Request $request)
    {
        $settings = $this->settingRepository->getSyncListForSuperAdmin();
        $sectionName = ($request->section === null) ? 'general' : $request->section;

        return view("super_admin_settings.$sectionName", compact('settings', 'sectionName'));
    }

    public function updateSuperAdminSettings(UpdateSuperAdminSettingRequest $request)
    {
        try {
            $this->settingRepository->updateSuperAdminSetting($request->all());
            Flash::success(__('messages.flash.setting_updated'));
        } catch (Exception $exception) {
            throw new UnprocessableEntityHttpException($exception->getMessage());
        }

        return redirect(route('super.admin.settings.edit'));
    }

    public function editSuperAdminFooterSettings()
    {
        $settings = SuperAdminSetting::toBase()->pluck('value', 'key')->toArray();

        return view('super_admin_footer_settings.index', compact('settings'));
    }

    public function updateSuperAdminFooterSettings(UpdateSuperAdminFooterSettingRequest $request)
    {
        $this->settingRepository->updateSuperFooterAdminSetting($request->all());

        Flash::success(__('messages.flash.setting_updated'));

        return redirect(route('super.admin.footer.settings.edit'));
    }

    public function invoiceSettings()
    {
        $data['settings'] = $this->settingRepository->getSyncList();

        return view('settings.invoice-settings', $data);
    }
}
