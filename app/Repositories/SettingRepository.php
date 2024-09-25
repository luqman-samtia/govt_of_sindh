<?php

namespace App\Repositories;

use App\Models\InvoiceSetting;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\SuperAdminSetting;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class SettingRepository
 *
 * @version February 19, 2020, 1:45 pm UTC
 */
class SettingRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [];

    protected $availableKeys = [
        'stripe_key',
        'stripe_secret',
        'paypal_client_id',
        'paypal_secret',
        'razorpay_key',
        'razorpay_secret',
        'stripe_enabled',
        'paypal_enabled',
        'razorpay_enabled',
        'currency_after_amount',
        'paystack_enabled',
        'paystack_key',
        'paystack_secret',
    ];

    /**
     * Return searchable fields
     */
    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Setting::class;
    }

    public function getSyncList()
    {
        return Setting::toBase()->pluck('value', 'key')->toArray();
    }

    public function getSyncListForSuperAdmin()
    {
        return SuperAdminSetting::toBase()->pluck('value', 'key')->toArray();
    }

    public function updateSetting($input)
    {

        if (isset($input['invoice_settings'])) {
            $input['currency_after_amount'] = isset($input['currency_after_amount']);
            $input['show_product_description'] = isset($input['show_product_description']) ? true : false;
            $settingInputArray = Arr::only($input, [
                'current_currency', 'decimal_separator', 'thousand_separator', 'currency_after_amount', 'invoice_no_prefix', 'invoice_no_suffix', 'show_product_description', 'due_invoice_days',
            ]);
        } else {
            $input['mail_notification'] = ($input['mail_notification'] == 1) ? 1 : 0;
            $input['company_phone'] = '+' . $input['prefix_code'] . $input['company_phone'];
            $input['payment_auto_approved'] = isset($input['payment_auto_approved']);
            if (isset($input['app_logo']) && !empty($input['app_logo'])) {
                /** @var Setting $setting */
                $setting = Setting::where('key', '=', 'app_logo')->first();
                $setting = $this->uploadSettingImages($setting, $input['app_logo']);
            }
            if (isset($input['favicon_icon']) && !empty($input['favicon_icon'])) {
                /** @var Setting $setting */
                $setting = Setting::where('key', '=', 'favicon_icon')->first();
                $setting = $this->uploadSettingImages($setting, $input['favicon_icon']);
            }
            if ($input['payment_auto_approved'] == 1) {
                $manualPayments = Payment::wherePaymentMode(Payment::MANUAL)->whereIsApproved(Payment::PENDING)->get();
                foreach ($manualPayments as $manualPayment) {
                    $manualPayment->update([
                        'is_approved' => Payment::APPROVED,
                    ]);
                }
            }

            $input['show_additional_address_in_invoice'] = isset($input['show_additional_address_in_invoice']) ? 1 : 0;

            $settingInputArray = Arr::only($input, [
                'app_name', 'company_name', 'company_address', 'company_phone', 'date_format', 'time_format',
                'time_zone', 'mail_notification', 'payment_auto_approved', 'country_code', 'city', 'state', 'country', 'zipcode', 'show_additional_address_in_invoice', 'fax_no',
            ]);
        }

        foreach ($settingInputArray as $key => $value) {
            $setting = Setting::where('key', '=', $key)->first();

            if (empty($setting)) {
                Setting::create([
                    'key' => $key,
                    'value' => $value,
                    'tenant_id' => getLoggedInUser()->tenant_id ?? null,
                ]);

                continue;
            } else {
                $setting->update(['value' => $value]);
            }
        }

        return true;
    }

    public function editSettingsData()
    {
        $data = [];
        $data['timezones'] = $this->getTimezones();
        $data['settings'] = $this->getSyncList();
        $data['dateFormats'] = Setting::DateFormatArray;
        $data['currencies'] = getCurrencies();
        $data['templates'] = Setting::INVOICE__TEMPLATE_ARRAY;
        $data['invoiceTemplate'] = InvoiceSetting::all();

        return $data;
    }

    public function updateInvoiceSetting($input)
    {
        try {
            DB::beginTransaction();
            $invoiceSetting = InvoiceSetting::where('key', $input['template'])->first();
            $invoiceSetting->update([
                'template_color' => $input['default_invoice_color'],
            ]);
            /** @var Setting $setting */
            $setting = Setting::where('key', 'default_invoice_template')->first();
            $setting->update([
                'value' => $input['template'],
            ]);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($exception->getMessage());
        }

        return true;
    }

    public function uploadSettingImages($setting, $value)
    {
        $setting->clearMediaCollection(Setting::PATH);
        $media = $setting->addMedia($value)->toMediaCollection(Setting::PATH, config('app.media_disc'));
        $setting = $setting->refresh();
        $setting->update(['value' => $media->getFullUrl()]);

        return $setting;
    }

    public function updateSuperAdminSetting(array $input)
    {
        $settingInputs = Arr::only($input, [
            'app_name', 'plan_expire_notification', 'home_page_support_link', 'currency_after_amount','vat_no_label'
        ]);

        foreach ($settingInputs as $key => $value) {
            $value = ($value == null) ? '' : $value;
            $superAdminSetting = SuperAdminSetting::where('key', '=', $key)->first();

            if(!empty($superAdminSetting)) {
                $superAdminSetting->update(['value' => $value]);
            }
        }

        if (isset($input['app_logo']) && !empty($input['app_logo'])) {
            /** @var SuperAdminSetting $setting */
            $setting = SuperAdminSetting::where('key', '=', 'app_logo')->first();
            $setting->clearMediaCollection(SuperAdminSetting::PATH);
            $setting->addMedia($input['app_logo'])->toMediaCollection(
                SuperAdminSetting::PATH,
                config('app.media_disc')
            );
            $setting = $setting->refresh();
            $setting->update(['value' => $setting->logo_url]);
        }

        if (isset($input['favicon_icon']) && !empty($input['favicon_icon'])) {
            /** @var SuperAdminSetting $setting */
            $setting = SuperAdminSetting::where('key', '=', 'favicon_icon')->first();
            $setting->clearMediaCollection(SuperAdminSetting::PATH);
            $setting->addMedia($input['favicon_icon'])->toMediaCollection(
                SuperAdminSetting::PATH,
                config('app.media_disc')
            );
            $setting = $setting->refresh();
            $setting->update(['value' => $setting->logo_url]);
        }

        $input['stripe_enabled'] = (!isset($input['stripe_enabled'])) ? 0 : 1;
        $input['paypal_enabled'] = (!isset($input['paypal_enabled'])) ? 0 : 1;
        $input['razorpay_enabled'] = (!isset($input['razorpay_enabled'])) ? 0 : 1;
        $input['paystack_enabled'] = (!isset($input['paystack_enabled'])) ? 0 : 1;
        $input['currency_after_amount'] = isset($input['currency_after_amount']);
        $this->checkPaymentValidation($input);
        $inputArray = Arr::only($input, $this->availableKeys);
        foreach ($inputArray as $key => $value) {
            $value = ($value == null) ? '' : $value;
            $setting = SuperAdminSetting::where('key', '=', $key)->first();

            if(empty($setting)) {
                SuperAdminSetting::create([
                    'key' => $key,
                    'value' => $value,
                ]);
            } else {
                $setting->update(['value' => $value]);
            }
        }
    }

    private function checkPaymentValidation($input): void
    {
        if (
            isset($input['stripe_enabled']) && $input['stripe_enabled'] == 1 &&
            (empty($input['stripe_key']) || empty($input['stripe_secret']))
        ) {
            throw new UnprocessableEntityHttpException('Please fill up all value for stripe payment gateway.');
        }
        if (
            isset($input['paypal_enabled']) && $input['paypal_enabled'] == 1 &&
            (empty($input['paypal_client_id']) || empty($input['paypal_secret']))
        ) {
            throw new UnprocessableEntityHttpException('Please fill up all value for paypal payment gateway.');
        }
        if (
            isset($input['razorpay_enabled']) && $input['razorpay_enabled'] == 1 &&
            (empty($input['razorpay_key']) || empty($input['razorpay_secret']))
        ) {
            throw new UnprocessableEntityHttpException('Please fill up all value for razorpay payment gateway.');
        }
        if (
            isset($input['paystack_enabled']) && $input['paystack_enabled'] == 1 &&
            (empty($input['paystack_key']) || empty($input['paystack_secret']))
        ) {
            throw new UnprocessableEntityHttpException('Please fill up all value for paystack payment gateway.');
        }
    }

    public function updateSuperFooterAdminSetting(array $input)
    {
        $inputArray = Arr::only($input, [
            'footer_text', 'email', 'phone', 'address', 'facebook_url', 'twitter_url',
            'youtube_url', 'linkedin_url', 'region_code',
        ]);

        foreach ($inputArray as $key => $value) {
            $setting = SuperAdminSetting::where('key', '=', $key)->first();
            $setting->update(['value' => $value]);
        }

        return $setting;
    }

    public function addPaymentAutoApproveSetting($tenantId): bool
    {
        Setting::create([
            'key' => 'payment_auto_approved',
            'value' => '1',
            'tenant_id' => $tenantId,
        ]);

        return true;
    }

    public function getTimezones()
    {
        $timezones = [];
        $timezoneArr = file_get_contents(public_path('timezone/timezone.json'));
        $timezoneArr = json_decode($timezoneArr, true);

        foreach ($timezoneArr as $utcData) {
            foreach ($utcData['utc'] as $item) {
                $timezones[$item] = $item;
            }
        }

        return $timezones;
    }
}
