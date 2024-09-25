<?php

use App\Models\AdminCurrency;
use App\Models\City;
use App\Models\Client;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\InvoiceSetting;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Role;
use App\Models\Setting;
use App\Models\State;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\SuperAdminSetting;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Auth;
use App\Models\Tax;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Models\Tenant;
use Stancl\Tenancy\Database\TenantScope;
use Stripe\Stripe;

if (! function_exists('getLogInUser')) {
    /**
     * @return Authenticatable|null
     */
    function getLogInUser()
    {
        return Auth::user();
    }
}

if (! function_exists('getSuperAdmin')) {
    /**
     * @return User|Builder|Model|object|null
     */
    function getSuperAdmin()
    {
        static $superAdmin;
        if ($superAdmin === null) {
            $superAdmin = User::role(Role::ROLE_SUPER_ADMIN)->withoutGlobalScope(new TenantScope())->first();
        }

        return $superAdmin;
    }
}

if (! function_exists('getHomePageLanguage')) {
    /**
     * @return mixed
     */
    function getHomePageLanguage()
    {
        static $superAdminSettings;
        if ($superAdminSettings === null) {
            $superAdminSettings = User::role(Role::ROLE_SUPER_ADMIN)->withoutGlobalScope(new TenantScope())->first();
        }

        return $superAdminSettings['language'];
    }
}

if (! function_exists('getSuperAdminSettingValue')) {
    /**
     * @return \Illuminate\Database\Eloquent\Collection|SuperAdminSetting
     */
    function getSuperAdminSettingValue()
    {
        /** @var SuperAdminSetting $superAdminSettings */
        static $superAdminSettings;

        if (empty($superAdminSettings)) {
            $superAdminSettings = SuperAdminSetting::all()->keyBy('key');
        }

        return $superAdminSettings;
    }
}

if (! function_exists('getFileName')) {
    /**
     * @return string
     */
    function getFileName($fileName, $attachment)
    {
        $fileNameExtension = $attachment->getClientOriginalExtension();

        $newName = $fileName.'-'.time();

        return $newName.'.'.$fileNameExtension;
    }
}

if (! function_exists('getPaymentMode')) {
    function getPaymentMode($tenantId = null)
    {
        $paymentModeArr = [];
        if (isAuth() && Auth::user()->hasRole(Role::ROLE_CLIENT)) {
            $keyArray = [
                'stripe_enabled',
                'paypal_enabled',
                'razorpay_enabled',
                'paystack_enabled',
            ];
            $setting = Setting::where('tenant_id', getClientAdminTenantId())->whereIn(
                'key',
                $keyArray
            )->withoutGlobalScope(new TenantScope())->get(['key', 'value']);

            foreach ($setting as $value) {
                if ($value->key == 'stripe_enabled' && $value->value == '1') {
                    $stripeKey = Payment::STRIPE;
                    $paymentModeArr[$stripeKey] = 'Stripe';
                }

                if ($value->key == 'paypal_enabled' && $value->value == '1') {
                    $paypalKey = Payment::PAYPAL;
                    $paymentModeArr[$paypalKey] = 'Paypal';
                }

                if ($value->key == 'razorpay_enabled' && $value->value == '1') {
                    $razorpayKey = Payment::RAZORPAY;
                    $paymentModeArr[$razorpayKey] = 'Razorpay';
                }

                if ($value->key == 'paystack_enabled' && $value->value == '1') {
                    $paystackKey = Payment::PAYSTACK;
                    $paymentModeArr[$paystackKey] = 'Paystack';
                }

                $manualKey = Payment::MANUAL;
                $paymentModeArr[$manualKey] = 'Manual';

                $cashKey = Payment::CASH;
                $paymentModeArr[$cashKey] = 'Cash';
            }
            array_push($paymentModeArr);
        } elseif (isAuth() && Auth::user()->hasRole(Role::ROLE_ADMIN)) {
            $keyArray = [
                'stripe_enabled',
                'paypal_enabled',
                'razorpay_enabled',
                'paystack_enabled',
            ];
            $superAdminSetting = SuperAdminSetting::whereIn('key', $keyArray)->get(['key', 'value']);

            foreach ($superAdminSetting as $key => $value) {
                $manualKey = 0;
                $paymentModeArr[$manualKey] = 'Select payment method';
                if ($value->key == 'stripe_enabled') {
                    if ($value->key == 'stripe_enabled' && $value->value == '1') {
                        $stripeKey = Subscription::TYPE_STRIPE;
                        $paymentModeArr[$stripeKey] = 'Stripe';
                    } else {
                        if (config('services.stripe.secret_key') != null && config('services.stripe.key') != null) {
                            $stripeKey = Subscription::TYPE_STRIPE;
                            $paymentModeArr[$stripeKey] = 'Stripe';
                        }
                    }
                }

                if ($value->key == 'paypal_enabled') {
                    if ($value->key == 'paypal_enabled' && $value->value == '1') {
                        $paypalKey = Subscription::TYPE_PAYPAL;
                        $paymentModeArr[$paypalKey] = 'PayPal';
                    } else {
                        if (config('payments.paypal.client_id') != null && config('payments.paypal.client_secret') != null) {
                            $paypalKey = Subscription::TYPE_PAYPAL;
                            $paymentModeArr[$paypalKey] = 'PayPal';
                        }
                    }
                }

                if ($value->key == 'razorpay_enabled') {
                    if ($value->key == 'razorpay_enabled' && $value->value == '1') {
                        $RazorpayKey = Subscription::TYPE_RAZORPAY;
                        $paymentModeArr[$RazorpayKey] = 'Razorpay';
                    } else {
                        if (config('payments.razorpay.key') != null && config('payments.razorpay.secret') != null) {
                            $RazorpayKey = Subscription::TYPE_RAZORPAY;
                            $paymentModeArr[$RazorpayKey] = 'Razorpay';
                        }
                    }
                }

                if ($value->key == 'paystack_enabled') {
                    if ($value->key == 'paystack_enabled' && $value->value == '1') {
                        $paystackKey = Subscription::TYPE_PAYSTACK;
                        $paymentModeArr[$paystackKey] = 'Paystack';
                    } else {
                        if (config('paystack.publicKey') != null && config('paystack.secretKey') != null) {
                            $paystackKey = Subscription::TYPE_PAYSTACK;
                            $paymentModeArr[$RazorpayKey] = 'Paystack';
                        }
                    }
                }

                $manualKey = Subscription::TYPE_MANUAL;
                $paymentModeArr[$manualKey] = 'Manual';
            }
            array_push($paymentModeArr);
        }elseif(!isAuth()) {
            $keyArray = [
                'stripe_enabled',
                'paypal_enabled',
                'razorpay_enabled',
                'paystack_enabled',
            ];

            $setting = Setting::where('tenant_id', $tenantId)->whereIn(
                'key',
                $keyArray
            )->get(['key', 'value']);

            foreach ($setting as $value) {
                if ($value->key == 'stripe_enabled' && $value->value == '1') {
                    $stripeKey = Payment::STRIPE;
                    $paymentModeArr[$stripeKey] = 'Stripe';
                }

                if ($value->key == 'paypal_enabled' && $value->value == '1') {
                    $paypalKey = Payment::PAYPAL;
                    $paymentModeArr[$paypalKey] = 'Paypal';
                }

                if ($value->key == 'razorpay_enabled' && $value->value == '1') {
                    $RazorpayKey = Payment::RAZORPAY;
                    $paymentModeArr[$RazorpayKey] = 'Razorpay';
                }

                if ($value->key == 'paystack_enabled' && $value->value == '1') {
                    $paystackKey = Payment::PAYSTACK;
                    $paymentModeArr[$paystackKey] = 'Paystack';
                }

                $manualKey = Payment::MANUAL;
                $paymentModeArr[$manualKey] = 'Manual';

                $cashKey = Payment::CASH;
                $paymentModeArr[$cashKey] = 'Cash';
            }
            array_push($paymentModeArr);
        }

        return $paymentModeArr;
    }
}

if (! function_exists('getAppName')) {
    /**
     * @return mixed
     */
    function getAppName($tenantId = null)
    {
        /** @var Setting $appName */
        static $appName;
        if (empty($appName)) {
            $userAppName = UserSetting::where('key', '=', 'app_name')->first();
            if (isAuth() && Auth::user()->hasRole(Role::ROLE_CLIENT)) {
                $appName = Setting::where('tenant_id', getClientAdminTenantId())->where('key', '=', 'app_name')->withoutGlobalScope(new TenantScope())->first();
                $appName = ! empty($appName->value) ? $appName : $userAppName;
            } else {
                if (isAuth() && Auth::user()->hasRole(Role::ROLE_ADMIN)) {
                    $appName = Setting::where('tenant_id', Auth::user()->tenant_id)->where('key', '=', 'app_name')->first();
                    $appName = ! empty($appName->value) ? $appName : $userAppName;
                } else {
                    $appName = Setting::where('key', '=', 'app_name')->first();
                    if (! empty($tenantId)) {
                        $appName = Setting::where('tenant_id', $tenantId)->where('key', '=', 'app_name')->first();
                    }
                }
            }
        }

        return $appName->value ?? '';
    }
}

if (! function_exists('getRazorPaySupportedCurrencies')) {

    function getRazorPaySupportedCurrencies(): array
    {
        return [
            'USD', 'EUR', 'GBP', 'SGD', 'AED', 'AUD', 'CAD', 'CNY', 'SEK', 'NZD', 'MXN', 'HKD', 'NOK', 'RUB', 'ALL', 'AMD',
            'ARS', 'AWG', 'BBD', 'BDT', 'BMD', 'BND', 'BOB', 'BSD', 'BWP', 'BZD', 'CHF', 'COP', 'CRC', 'CUP', 'CZK', 'DKK',
            'DOP', 'DZD', 'EGP', 'ETB', 'FJD', 'GIP', 'GMD', 'GTQ', 'GYD', 'HKD', 'HNL', 'HRK', 'HTG', 'HUF', 'IDR', 'ILS',
            'INR', 'JMD', 'KES', 'KGS', 'KHR', 'KYD', 'KZT', 'LAK', 'LBP', 'LKR', 'LRD', 'LSL', 'MAD', 'MDL', 'MKD', 'MMK',
            'MNT', 'MOP', 'MUR', 'MVR', 'MWK', 'MYR', 'NAD', 'NGN', 'NIO', 'NOK', 'NPR', 'PEN', 'PGK', 'PHP', 'PKR', 'QAR',
            'SAR', 'SCR', 'SLL', 'SOS', 'SSP', 'SVC', 'SZL', 'THB', 'TTD', 'TZS', 'UYU', 'UZS', 'YER', 'ZAR', 'GHS',
        ];
    }
}

if (! function_exists('getLogoUrl')) {
    function getLogoUrl($tenantId = null)
    {
        static $appLogo;

        $img = 'assets/images/infyom.png';

        if (empty($appLogo)) {
            $userSetting = UserSetting::toBase()->pluck('value', 'key')->toArray();
            if (isAuth() && Auth::user()->hasRole(Role::ROLE_CLIENT)) {
                /** @var Setting $setting */
                static $setting;
                if ($setting === null) {
                    $setting = Setting::where('tenant_id', getClientAdminTenantId())->where('key', '=', 'app_logo')->withoutGlobalScope(new TenantScope())->first();
                }

                return !empty($setting->value) ? asset($setting->value) : asset($userSetting['app_logo']);
            } else {
                if (isAuth() && Auth::user()->hasRole(Role::ROLE_ADMIN)) {
                    /** @var Setting $setting */
                    static $setting;
                    static $userSetting;
                    if ($setting === null) {
                        $setting = Setting::where('tenant_id', Auth::user()->tenant_id)->where('key', '=', 'app_logo')->first();
                        $userSetting = UserSetting::toBase()->pluck('value', 'key')->toArray();
                    }
                    if ($setting->value == $img) {
                        return asset('assets/images/infyom.png');
                    } else {
                        return !empty($setting->value) ? $setting->value : $userSetting['app_logo'];
                    }
                } else {
                    $appLogo = Setting::where('key', '=', 'app_logo')->first();
                    if (! empty($tenantId)) {
                        $appLogo = Setting::where('key', '=', 'app_logo')->where('tenant_id', $tenantId)->first();
                    }

                    return asset($appLogo->logo_url);
                }
            }
        }
    }
}

if (! function_exists('getFaviconUrl')) {
    /**
     * @param  null  $tenantId
     * @return mixed|string|void
     */
    function getFaviconUrl($tenantId = null)
    {
        static $appLogo;

        $img = 'assets/images/infyom.png';

        if (empty($appLogo)) {
            if (isAuth() && Auth::user()->hasRole(Role::ROLE_CLIENT)) {
                /** @var Setting $setting */
                static $setting;
                if ($setting === null) {
                    $setting = Setting::where('tenant_id', getClientAdminTenantId())->where('key', '=', 'favicon_icon')->withoutGlobalScope(new TenantScope())->first();
                    $userSetting = UserSetting::toBase()->pluck('value', 'key')->toArray();
                    if (empty($setting->value)) {
                        return asset('assets/images/infyom.png');
                    }else {
                        return $setting->value;
                    }
                }
            } else {
                if (isAuth() && Auth::user()->hasRole(Role::ROLE_ADMIN)) {
                    /** @var Setting $setting */
                    static $setting;
                    static $userSetting;
                    if ($setting === null) {
                        $setting = Setting::where('tenant_id', Auth::user()->tenant_id)->where('key', '=', 'favicon_icon')->first();
                        $userSetting = UserSetting::toBase()->pluck('value', 'key')->toArray();
                    }
                    if ($setting->value == $img) {
                        return asset('assets/images/infyom.png');
                    } else {
                        return !empty($setting->value) ? $setting->value : $userSetting['favicon_icon'];
                    }
                } else {
                    $appFavicon = Setting::where('key', '=', 'favicon_icon')->first();
                    if (! empty($tenantId)) {
                        $appFavicon = Setting::where('key', '=', 'favicon_icon')->where('tenant_id', $tenantId)->first();
                    }

                    return asset($appFavicon->favicon_url);
                }
            }
        }
    }
}

if (! function_exists('headerLanguageName')) {
    function headerLanguageName()
    {
        if (Session::has('languageChangeName')) {
            return Session::get('languageChangeName');
        }

        return 'en';
    }
}

if (! function_exists('getUserLanguages')) {
    /**
     * @return string[]
     */
    function getUserLanguages()
    {
        $language = User::LANGUAGES;
        asort($language);

        return $language;
    }
}

if (! function_exists('getHeaderLanguageName')) {
    /**
     * @return mixed|null
     */
    function getHeaderLanguageName()
    {
        return getUserLanguages()[headerLanguageName()];
    }
}

if (! function_exists('isAuth')) {

    function isAuth(): bool
    {
        return Auth::check();
    }
}

if (! function_exists('getParseDate')) {
    /**
     * @return string
     */
    function getParseDate($date)
    {
        return Carbon::parse($date);
    }
}

if (! function_exists('getLogInUserId')) {
    /**
     * @return int
     */
    function getLogInUserId()
    {
        static $authUser;
        if (empty($authUser)) {
            $authUser = Auth::user();
        }

        return $authUser->id;
    }
}

if (! function_exists('getLogInUserEmail')) {
    /**
     * @return string
     */
    function getLogInUserEmail()
    {
        static $authUser;
        if (empty($authUser)) {
            $authUser = Auth::user();
        }

        return $authUser->email;
    }
}

if (! function_exists('getLoggedInUser')) {
    function getLoggedInUser()
    {
        return Auth::user();
    }
}

if (! function_exists('getSuperAdminDashboardURL')) {
    /**
     * @return string
     */
    function getSuperAdminDashboardURL()
    {
        return RouteServiceProvider::SUPER_ADMIN;
    }
}

if (! function_exists('getCurrentPlanDetails')) {
    /**
     * @return array
     */
    function getCurrentPlanDetails()
    {
        $currentSubscription = currentActiveSubscription();
        $isExpired = $currentSubscription->isExpired();
        $currentPlan = $currentSubscription->subscriptionPlan;

        if ($currentPlan->price != $currentSubscription->plan_amount) {
            $currentPlan->price = $currentSubscription->plan_amount;
        }

        $startsAt = Carbon::now();
        $totalDays = Carbon::parse($currentSubscription->start_date)->diffInDays($currentSubscription->end_date);
        $usedDays = Carbon::parse($currentSubscription->start_date)->diffInDays($startsAt);
        $remainingDays = $totalDays - $usedDays;

        $frequency = $currentSubscription->plan_frequency == SubscriptionPlan::MONTH ? 'Monthly' : 'Yearly';

        $days = $currentSubscription->plan_frequency == SubscriptionPlan::MONTH ? 30 : 365;

        $perDayPrice = round($currentPlan->price / $days, 2);

        if (checkIfPlanIsInTrial($currentSubscription)) {
            $remainingBalance = 0.00;
            $usedBalance = 0.00;
        } else {
            $remainingBalance = $currentPlan->price - ($perDayPrice * $usedDays);
            $usedBalance = $currentPlan->price - $remainingBalance;
        }

        return [
            'name' => $currentPlan->name.' / '.$frequency,
            'trialDays' => $currentPlan->trial_days,
            'startAt' => Carbon::parse($currentSubscription->start_date)->translatedFormat('jS M, Y'),
            'endsAt' => Carbon::parse($currentSubscription->end_date)->translatedFormat('jS M, Y'),
            'usedDays' => $usedDays,
            'remainingDays' => $remainingDays,
            'totalDays' => $totalDays,
            'usedBalance' => $usedBalance,
            'remainingBalance' => $remainingBalance,
            'isExpired' => $isExpired,
            'currentPlan' => $currentPlan,
        ];
    }
}

if (! function_exists('getProratedPlanData')) {

    function getProratedPlanData($planIDChosenByUser): array
    {
        /** @var SubscriptionPlan $subscriptionPlan */
        $subscriptionPlan = SubscriptionPlan::findOrFail($planIDChosenByUser);
        $newPlanDays = $subscriptionPlan->frequency === SubscriptionPlan::MONTH ? 30 : 365;

        /** @var Subscription $currentSubscription */
        $currentSubscription = currentActiveSubscription();
        $frequency = $subscriptionPlan->frequency === SubscriptionPlan::MONTH ? 'Monthly' : 'Yearly';

        $startsAt = Carbon::now();

        $carbonParseStartAt = Carbon::parse($currentSubscription->start_date);
        $usedDays = $carbonParseStartAt->copy()->diffInDays($startsAt);
        $totalExtraDays = 0;
        $totalDays = $newPlanDays;

        $endsAt = Carbon::now()->addDays($newPlanDays);

        $startsAt = $startsAt->copy()->translatedFormat('jS M, Y');
        if ($usedDays <= 0) {
            $startsAt = $carbonParseStartAt->copy()->translatedFormat('jS M, Y');
        }

        if (! $currentSubscription->isExpired() && ! checkIfPlanIsInTrial($currentSubscription)) {
            $amountToPay = 0;

            $currentPlan = $currentSubscription->subscriptionPlan; // TODO: take fields from subscription

            // checking if the current active subscription plan has the same price and frequency in order to process the calculation for the proration
            $planPrice = $currentPlan->price;
            $planFrequency = $currentPlan->frequency;
            if ($planPrice != $currentSubscription->plan_amount || $planFrequency != $currentSubscription->plan_frequency) {
                $planPrice = $currentSubscription->plan_amount;
                $planFrequency = $currentSubscription->plan_frequency;
            }

            $frequencyDays = $planFrequency === SubscriptionPlan::MONTH ? 30 : 365;
            $perDayPrice = round($planPrice / $frequencyDays, 2);

            $remainingBalance = round($planPrice - ($perDayPrice * $usedDays), 2);

            if ($remainingBalance < $subscriptionPlan->price) { // adjust the amount in plan
                $amountToPay = round($subscriptionPlan->price - $remainingBalance, 2);
            } else {
                $endsAt = Carbon::now();
                $perDayPriceOfNewPlan = round($subscriptionPlan->price / $newPlanDays, 2);
                $totalExtraDays = round($remainingBalance / $perDayPriceOfNewPlan);

                $endsAt = $endsAt->copy()->addDays($totalExtraDays);
                $totalDays = $totalExtraDays;
            }

            return [
                'startDate' => $startsAt,
                'name' => $subscriptionPlan->name.' / '.$frequency,
                'trialDays' => $subscriptionPlan->trial_days,
                'remainingBalance' => $remainingBalance,
                'endDate' => $endsAt->format('jS M, Y'),
                'amountToPay' => $amountToPay,
                'usedDays' => $usedDays,
                'totalExtraDays' => $totalExtraDays,
                'totalDays' => $totalDays,
            ];
        }

        return [
            'name' => $subscriptionPlan->name.' / '.$frequency,
            'trialDays' => $subscriptionPlan->trial_days,
            'startDate' => $startsAt,
            'endDate' => $endsAt->translatedFormat('jS M, Y'),
            'remainingBalance' => 0,
            'amountToPay' => $subscriptionPlan->price,
            'usedDays' => $usedDays,
            'totalExtraDays' => $totalExtraDays,
            'totalDays' => $totalDays,
        ];
    }
}

if (! function_exists('isSubscriptionExpired')) {

    function isSubscriptionExpired(): array
    {
        /** @var Subscription $subscription */
        static $subscription;
        if ($subscription === null) {
            $subscription = currentActiveSubscription();
        }

        if ($subscription && $subscription->isExpired()) {
            return [
                'status' => true,
                'message' => 'Your current plan is expired, please choose new plan.',
            ];
        }

        if ($subscription == null) {
            return [
                'status' => true,
                'message' => 'Please choose a plan to continue the service.',
            ];
        }

        $subscriptionEndDate = Carbon::parse($subscription->end_date);
        $currentDate = Carbon::parse(Carbon::now())->format('Y-m-d');

        $expirationMessage = '';
        $diffInDays = $subscriptionEndDate->diffInDays($currentDate);
        $superAdminSettingValue = getSuperAdminSettingValue();
        if ($diffInDays <= $superAdminSettingValue['plan_expire_notification']['value'] && $diffInDays != 0) {
            $expirationMessage = "Your '{$subscription->subscriptionPlan->name}' is about to expired in {$diffInDays} days.";
        }

        return [
            'status' => $subscriptionEndDate->diffInDays($currentDate) <= $superAdminSettingValue['plan_expire_notification']['value'],
            'message' => $expirationMessage,
        ];
    }
}

if (! function_exists('checkIfPlanIsInTrial')) {
    /**
     * @param  Subscription  $currentSubscription
     * @return bool
     */
    function checkIfPlanIsInTrial($currentSubscription)
    {
        $now = Carbon::now();
        if (! empty($currentSubscription->trial_ends_at) && $currentSubscription->trial_ends_at > $now) {
            return true;
        }

        return false;
    }
}

if (! function_exists('getSubscriptionPlanCurrencyCode')) {

    function getSubscriptionPlanCurrencyCode($key): string
    {
        $currencyPath = file_get_contents(storage_path().'/currencies/defaultCurrency.json');
        $currenciesData = json_decode($currencyPath, true)['currencies'];
        $currency = collect($currenciesData)->firstWhere(
            'code',
            strtoupper($key)
        );

        return $currency['code'];
    }
}

if (! function_exists('getAdminSubscriptionPlanCurrencyCode')) {
    function getAdminSubscriptionPlanCurrencyCode($key)
    {
        $currencyData = AdminCurrency::where('id', $key)->first();
        if ($currencyData != null) {
            $currencyCode = $currencyData->code;

            return $currencyCode;
        }
    }
}

if (! function_exists('zeroDecimalCurrencies')) {
    /**
     * @return array
     */
    function zeroDecimalCurrencies()
    {
        return [
            'BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA', 'PYG', 'RWF', 'UGX', 'VND', 'VUV', 'XAF', 'XOF', 'XPF',
        ];
    }
}

if (! function_exists('getAdminDashboardURL')) {
    /**
     * @return string
     */
    function getAdminDashboardURL()
    {
        return RouteServiceProvider::ADMIN;
    }
}

if (! function_exists('getClientDashboardURL')) {
    /**
     * @return string
     */
    function getClientDashboardURL()
    {
        return RouteServiceProvider::CLIENT_HOME;
    }
}

if (! function_exists('getClientRoleId')) {
    /**
     * @return mixed
     */
    function getClientRoleId()
    {
        return Role::whereName('client')->first()->id;
    }
}

if (! function_exists('getAdminRoleId')) {

    function getAdminRoleId(): mixed
    {
        return Role::whereName('admin')->first()->id;
    }
}

if (! function_exists('getSuperAdminRoleId')) {

    function getSuperAdminRoleId(): mixed
    {
        return Role::whereName('super_admin')->first()->id;
    }
}

if (! function_exists('removeCommaFromNumbers')) {
    /**
     * @return string|string[]
     */
    function removeCommaFromNumbers($number): array|string
    {
        return (gettype($number) == 'string' && ! empty($number)) ? str_replace(',', '', $number) : $number;
    }
}

if (! function_exists('getStates')) {
    /**
     * @return array
     */
    function getStates($countryId)
    {
        return State::where('country_id', $countryId)->toBase()->pluck('name', 'id')->toArray();
    }
}

if (! function_exists('getCities')) {

    function getCities($stateId): array
    {
        return City::where('state_id', $stateId)->toBase()->pluck('name', 'id')->toArray();
    }
}

if (! function_exists('getCurrentTimeZone')) {
    /**
     * @return mixed
     */
    function getCurrentTimeZone()
    {
        /** @var Setting $currentTimezone */
        static $currentTimezone;

        try {
            if (empty($currentTimezone)) {
                $currentTimezone = Setting::where('tenant_id', Auth::user()->tenant_id)->where('key', 'time_zone')->first();
            }
            if ($currentTimezone != null) {
                return $currentTimezone->value;
            } else {
                return null;
            }
        } catch (Exception $exception) {
            return 'Asia/Kolkata';
        }
    }
}

if (! function_exists('getCurrencies')) {
    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function getCurrencies(): Illuminate\Database\Eloquent\Collection
    {
        return Currency::all();
    }
}

if (! function_exists('getAdminCurrencies')) {
    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function getAdminCurrencies(): Illuminate\Database\Eloquent\Collection
    {
        return AdminCurrency::all();
    }
}

if (! function_exists('getCurrencyFullName')) {

    function getCurrencyFullName(): array
    {
        $currencyPath = file_get_contents(storage_path().'/currencies/defaultCurrency.json');
        $currenciesData = json_decode($currencyPath, true);
        $currencies = [];

        foreach ($currenciesData['currencies'] as $currency) {
            $convertCode = strtolower($currency['code']);
            $currencies[$convertCode] = $currency['icon'].' - '.$currency['code'].' '.$currency['name'];
        }

        return $currencies;
    }
}

if (! function_exists('getAdminCurrencyFullName')) {

    function getAdminCurrencyFullName(): array
    {
        $currenciesData = AdminCurrency::get();
        $currencies = [];

        foreach ($currenciesData as $currency) {
            $convertCode = strtolower($currency['code']);
            $currencies[$convertCode] = $currency['icon'].' - '.$currency['code'].' '.$currency['name'];
        }

        return $currencies;
    }
}

if (! function_exists('getCurrencySymbol')) {
    /**
     * @return mixed
     */
    function getCurrencySymbol($tenantId = null)
    {
        /** @var Setting $currencySymbol */
        static $currencySymbol;
        if ($currencySymbol == null) {
            if (Auth::check()) {
                $currencyIcon = Currency::where('id', getSettingValue('current_currency'))->pluck('icon')->first();
                $currencySymbol = $currencyIcon ?? '₹';
            } else {
                $adminCurrencySymbol = Setting::where('tenant_id', $tenantId)->where(
                    'key',
                    '=',
                    'current_currency'
                )->first()->value;
                $currencySymbol = Currency::where('id', $adminCurrencySymbol)->pluck('icon')->first();
            }
        }

        return $currencySymbol;
    }
}

if (! function_exists('getInvoiceCurrencySymbol')) {
    /**
     * @param  null  $tenantId
     * @return HigherOrderBuilderProxy|mixed|string
     */
    function getInvoiceCurrencySymbol($currencyId, $tenantId = null)
    {
        static $invoiceCurrencySymbol;

        if (empty($invoiceCurrencySymbol)) {
            $invoiceCurrencySymbol = Currency::whereId($currencyId)->first();
        }

        return $invoiceCurrencySymbol->icon ?? '₹';
    }
}

if (! function_exists('getDefaultTax')) {
    function getDefaultTax()
    {
        return Tax::where('is_default', '=', '1')->first()->id ?? null;
    }
}

if (! function_exists('setStripeApiKey')) {
    /**
     * @return bool
     */
    function setStripeApiKey()
    {
        $stripeSecret = Setting::where('tenant_id', getClientAdminTenantId())->where('key', 'stripe_secret')->first();

        Stripe::setApiKey($stripeSecret->value);

        return true;
    }
}

if (! function_exists('setStripeApiKeyForPublic')) {
    /**
     * @return bool
     */
    function setStripeApiKeyForPublic($tenantId)
    {
        $stripeSecret = Setting::where('tenant_id', $tenantId)->where('key', 'stripe_secret')->first();

        Stripe::setApiKey($stripeSecret->value);

        return true;
    }
}

if (! function_exists('getStripeKey')) {
    /**
     * @return mixed
     */
    function getStripeKey($tenantId = null)
    {
        if(!empty($tenantId)) {
            $stripeKey = Setting::where('tenant_id', $tenantId)->where('key', 'stripe_key')->first();
        }else {
            $stripeKey = Setting::where('tenant_id', getClientAdminTenantId())->where('key', 'stripe_key')->withoutGlobalScope(new TenantScope())->first();
        }

        return $stripeKey->value;
    }
}

if (! function_exists('getStripeKeyForPublic')) {
    /**
     * @return mixed
     */
    function getStripeKeyForPublic($tenantId)
    {
        $stripeKey = Setting::where('tenant_id', $tenantId)->where('key', 'stripe_key')->first();

        return $stripeKey->value;
    }
}

if (! function_exists('getPaypalClientId')) {
    /**
     * @return mixed
     */
    function getPaypalClientId()
    {
        $paypalClientId = Setting::where('tenant_id', getClientAdminTenantId())->where('key', 'paypal_client_id')->first();

        return $paypalClientId->value;
    }
}

if (! function_exists('getPaypalClientIdForPublic')) {
    function getPaypalClientIdForPublic($tenantId)
    {
        $paypalClientId = Setting::where('tenant_id', $tenantId)->where('key', 'paypal_client_id')->first();

        return $paypalClientId->value;
    }
}

if (! function_exists('getPaypalSecret')) {
    /**
     * @return mixed
     */
    function getPaypalSecret()
    {
        $paypalSecret = Setting::where('tenant_id', getClientAdminTenantId())->where('key', 'paypal_secret')->first();

        return $paypalSecret->value;
    }
}

if (! function_exists('getPaypalSecretForPublic')) {
    function getPaypalSecretForPublic($tenantId)
    {
        $paypalSecret = Setting::where('tenant_id', $tenantId)->where('key', 'paypal_secret')->first();

        return $paypalSecret->value;
    }
}

if (! function_exists('getRazorpayKey')) {
    /**
     * @return mixed
     */
    function getRazorpayKey()
    {
        $razorpayKey = Setting::where('tenant_id', getClientAdminTenantId())->where('key', 'razorpay_key')->first();

        return $razorpayKey->value;
    }
}

if (! function_exists('getRazorpayKeyForPublic')) {
    function getRazorpayKeyForPublic($tenantId)
    {
        $razorpayKey = Setting::where('tenant_id', $tenantId)->where('key', 'razorpay_key')->first();

        return $razorpayKey->value;
    }
}

if (! function_exists('getRazorpaySecret')) {
    /**
     * @return mixed
     */
    function getRazorpaySecret()
    {
        $razorpaySecret = Setting::where('tenant_id', getClientAdminTenantId())->where('key', 'razorpay_secret')->first();

        return $razorpaySecret->value;
    }
}

if (! function_exists('getRazorpaySecretForPublic')) {
    function getRazorpaySecretForPublic($tenantId)
    {
        $razorpaySecret = Setting::where('tenant_id', $tenantId)->where('key', 'razorpay_secret')->first();

        return $razorpaySecret->value;
    }
}

if (! function_exists('setSuperAdminStripeApiKey')) {
    /**
     * @return bool
     */
    function setSuperAdminStripeApiKey()
    {
        $stripeSecret = SuperAdminSetting::where('key', 'stripe_secret')->first();

        if ($stripeSecret == null || $stripeSecret->value == null) {
            Stripe::setApiKey(config('services.stripe.secret_key'));
        } else {
            Stripe::setApiKey($stripeSecret->value);
        }

        return true;
    }
}

if (! function_exists('getSuperAdminStripeSecret')) {
    /**
     * @return mixed
     */
    function getSuperAdminStripeSecret()
    {
        $stripeSecret = SuperAdminSetting::where('key', 'stripe_secret')->first();

        if ($stripeSecret == null || $stripeSecret->value == null) {
            return config('services.stripe.secret_key');
        } else {
            return $stripeSecret->value;
        }
    }
}

if (! function_exists('getSuperAdminStripeKey')) {
    /**
     * @return SuperAdminSetting|\Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application
     */
    function getSuperAdminStripeKey()
    {
        /** @var SuperAdminSetting $stripeKeyValue */
        static $stripeKeyValue;

        if (! isset($stripeKeyValue) && empty($stripeKeyValue)) {
            $stripeKey = SuperAdminSetting::where('key', 'stripe_key')->first();
            if ($stripeKey == null || $stripeKey->value == null) {
                $stripeKeyValue = config('services.stripe.key');
            } else {
                $stripeKeyValue = $stripeKey->value;
            }
        }

        return $stripeKeyValue;
    }
}

if (! function_exists('getSuperAdminPaypalClientId')) {
    /**
     * @return mixed
     */
    function getSuperAdminPaypalClientId()
    {
        $paypalClientId = SuperAdminSetting::where('key', 'paypal_client_id')->first();

        if ($paypalClientId == null || $paypalClientId->value == null) {
            return config('payments.paypal.client_id');
        } else {
            return $paypalClientId->value;
        }
    }
}

if (! function_exists('getSuperAdminPaypalSecret')) {
    /**
     * @return mixed
     */
    function getSuperAdminPaypalSecret()
    {
        $paypalSecret = SuperAdminSetting::where('key', 'paypal_secret')->first();

        if ($paypalSecret == null || $paypalSecret->value == null) {
            return config('payments.paypal.client_secret');
        } else {
            return $paypalSecret->value;
        }
    }
}

if (! function_exists('getSuperAdminRazorpayKey')) {
    /**
     * @return mixed
     */
    function getSuperAdminRazorpayKey()
    {
        $razorpayKey = SuperAdminSetting::where('key', 'razorpay_key')->first();

        if ($razorpayKey == null || $razorpayKey->value == null) {
            return config('payments.razorpay.key');
        } else {
            return $razorpayKey->value;
        }
    }
}

if (! function_exists('getSuperAdminRazorpaySecret')) {
    /**
     * @return mixed
     */
    function getSuperAdminRazorpaySecret()
    {
        $razorpaySecret = SuperAdminSetting::where('key', 'razorpay_secret')->first();

        if ($razorpaySecret == null || $razorpaySecret->value == null) {
            return config('payments.razorpay.secret');
        } else {
            return $razorpaySecret->value;
        }
    }
}

if (! function_exists('currentDateFormat')) {
    // current date format
    /**
     * @return mixed
     */
    function currentDateFormat($tenantId = null)
    {
        /** @var Setting $dateFormat */
        static $dateFormat;

        if ($dateFormat === null) {
            if (isAuth() && Auth::user()->hasRole(Role::ROLE_CLIENT)) {
                $dateFormat = Setting::where('tenant_id', getClientAdminTenantId())->where('key', 'date_format')->withoutGlobalScope(new TenantScope())->first();
            } elseif (isAuth() && Auth::user()->hasRole(Role::ROLE_ADMIN)) {
                $dateFormat = Setting::where('tenant_id', Auth::user()->tenant_id)->where('key', 'date_format')->first();
            } elseif (! empty($tenantId)) {
                $dateFormat = Setting::where('tenant_id', $tenantId)->where('key', 'date_format')->first();
            } else {
                $dateFormat = Setting::where('key', 'date_format')->first();
            }
        }

        return $dateFormat->value;
    }
}

if (! function_exists('getAuthLogintenantId')) {
    /**
     * @return mixed
     */
    function getAuthLogintenantId()
    {
        $user = User::where('id', Auth::id())->first();

        return $user;
    }
}

if (! function_exists('momentJsCurrentDateFormat')) {
    /**
     * @return string
     */
    function momentJsCurrentDateFormat()
    {
        /** @var Setting $key */
        static $key;
        if ($key === null) {
            $key = Setting::DateFormatArray[currentDateFormat()];
        }

        return $key;
    }
}

if (! function_exists('addNotification')) {
    /**
     * @param  array  $data
     */
    function addNotification($data)
    {
        $notificationRecord = [
            'type' => $data[0],
            'user_id' => $data[1],
            'title' => $data[2],
        ];

        $user = User::withoutGlobalScope(new TenantScope())->findOrFail($data[1]);

        if ($user) {
            Notification::create($notificationRecord);
        }
    }
}

if (! function_exists('getPayPalSupportedCurrencies')) {
    /**
     * @return array
     */
    function getPayPalSupportedCurrencies()
    {
        return [
            'AUD', 'BRL', 'CAD', 'CNY', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'ILS', 'JPY', 'MYR', 'MXN', 'TWD', 'NZD', 'NOK',
            'PHP', 'PLN', 'GBP', 'RUB', 'SGD', 'SEK', 'CHF', 'THB', 'USD',
        ];
    }
}

if (! function_exists('currentActiveSubscription')) {
    /**
     * @return Builder|Model|object|null
     */
    function currentActiveSubscription()
    {
        if (Auth::user()) {
            return null;
        }
        /** @var Subscription $currentActivePlan */
        static $currentActivePlan;
        if ($currentActivePlan === null) {
            $currentActivePlan = Subscription::whereHas('subscriptionPlan')->with('subscriptionPlan')
                ->where('status', Subscription::ACTIVE)
                ->where('user_id', Auth::user()->id)
                ->first();
        }

        return $currentActivePlan;
    }
}

if (! function_exists('getNotification')) {
    function getNotification()
    {
        /** @var Setting $notification */
        static $notification;
        if (empty($notification)) {
            $notification = Notification::whereUserId(Auth::id())->where(
                'read_at',
                null
            )->orderByDesc('created_at')->toBase()->get();
        }

        return $notification;
    }
}

if (! function_exists('getAllNotificationUser')) {
    /**
     * @param  array  $data
     * @return array
     */
    function getAllNotificationUser($data)
    {
        return array_filter($data, function ($key) {
            return $key != getLogInUserId();
        }, ARRAY_FILTER_USE_KEY);
    }
}

if (! function_exists('getNotificationIcon')) {
    /**
     * @return string|void
     */
    function getNotificationIcon($notificationType)
    {
        switch ($notificationType) {
            case 1:
            case 2:
                return 'fas fa-file-invoice';
            case 3:
                return 'fas fa-wallet';
            case 5:
                return 'fas fa-money-bill-wave-alt text-success';
        }
    }
}

if (! function_exists('getAdminUser')) {
    /**
     * @return User|Builder|Model|object|null
     */
    function getAdminUser()
    {
        /** @var User $user */
        static $user;
        if (empty($user)) {
            $user = User::with([
                'roles' => function ($q) {
                    $q->where('name', 'Admin');
                },
            ])->first();
        }

        return $user;
    }
}

if (! function_exists('getAdminUserIds')) {
    /**
     * @return Model|object|User|null
     */
    function getAdminUserIds()
    {
        static $userIds;
        if ($userIds === null) {
            $userIds = User::role(Role::ROLE_ADMIN)->pluck('id')->toArray();
        }

        return $userIds;
    }
}

if (! function_exists('canDelete')) {
    /**
     * @return bool
     */
    function canDelete(array $models, string $columnName, string $id)
    {
        foreach ($models as $model) {
            $result = $model::where($columnName, $id)->exists();

            if ($result) {
                return true;
            }
        }

        return false;
    }
}

if (! function_exists('numberFormat')) {
    function numberFormat(float $num, int $decimals = 2)
    {
        $decimal_separator = getSettingValue('decimal_separator');
        $thousands_separator = getSettingValue('thousand_separator');

        if (empty($decimal_separator) || empty($thousands_separator)) {
            return number_format($num, $decimals);

        }else{
            return number_format($num, $decimals, $decimal_separator, $thousands_separator);if(! function_exists('getPublicInvoiceAppURL')) {
                function getPublicInvoiceAppURL($tenantId)
                {
                    $setting = Setting::where('key', '=', 'app_logo')->where('tenant_id', $tenantId)->first();
                    $appLogoURL = !empty($setting->logo_url) ? asset($setting->logo_url) : '';


                    return $appLogoURL;
                }
            }
        }
    }
}

if (! function_exists('formatTotalAmount')) {

    function formatTotalAmount($totalAmount, $precision = 2): string
    {
        if ($totalAmount < 900) {
            // 0 - 900
            $numberFormat = number_format($totalAmount, $precision);
            $suffix = '';
        } elseif ($totalAmount < 900000) {
            // 0.9k-850k
            $numberFormat = number_format($totalAmount / 1000, $precision);
            $suffix = 'K';
        } elseif ($totalAmount < 900000000) {
            // 0.9m-850m
            $numberFormat = number_format($totalAmount / 1000000, $precision);
            $suffix = 'M';
        } elseif ($totalAmount < 900000000000) {
            // 0.9b-850b
            $numberFormat = number_format($totalAmount / 1000000000, $precision);
            $suffix = 'B';
        } else {
            // 0.9t+
            $numberFormat = number_format($totalAmount / 1000000000000, $precision);
            $suffix = 'T';
        }

        // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
        // Intentionally does not affect partials, eg "1.50" -> "1.50"
        if ($precision > 0) {
            $dotZero = '.'.str_repeat('0', $precision);
            $numberFormat = str_replace($dotZero, '', $numberFormat);
        }

        return $numberFormat.$suffix;
    }
}

if (! function_exists('getSettingValue')) {
    /**
     * @return mixed
     */
    function getSettingValue($keyName, $tenantId = null)
    {
        $key = 'setting'.'-'.$keyName;
        static $settingValues;

        if (isset($settingValues[$key])) {
            return $settingValues[$key];
        }
        /** @var User $user */
        $user = Auth::user();
        /** @var Setting $setting */
        if ($tenantId) {
            $setting = Setting::where('tenant_id', $tenantId)->where('key', '=', $keyName)->first();
        } elseif (isAuth() && $user->hasRole(Role::ROLE_CLIENT)) {
            $setting = Setting::where('tenant_id', getClientAdminTenantId())->where('key', '=', $keyName)->withoutGlobalScope(new TenantScope())->first();
        } elseif (isAuth() && $user->hasRole(Role::ROLE_ADMIN)) {
            $setting = Setting::where('tenant_id', $user->tenant_id)->where('key', '=', $keyName)->first();
        } else {
            $setting = SuperAdminSetting::where('key', '=', $keyName)->first();
        }

        $settingValues[$key] = $setting->value ?? '';

        return $setting->value ?? '';
    }
}

if (! function_exists('getClientAdminTenantId')) {
    function getClientAdminTenantId()
    {
        $loginUserTenantID = getLogInUser()->tenant_id;
        $adminUser = User::role(Role::ROLE_ADMIN)->where('tenant_id', $loginUserTenantID)->withoutGlobalScope(new TenantScope())->first();
        $clinetAdminTenantId = $adminUser->tenant_id ?? '';

        return $clinetAdminTenantId;
    }
}

if (! function_exists('getDefaultCountryFromSetting')) {
    function getDefaultCountryFromSetting($tenantId): string
    {
        $setting = Setting::where('tenant_id', $tenantId)->where('key', '=', 'country_code')->first();
        $countryCode = ! empty($setting->value) ? $setting->value : 'in';

        return $countryCode;
    }
}

if (! function_exists('getCurrencyCode')) {
    /**
     * @return mixed
     */
    function getCurrencyCode()
    {
        $currencyId = Setting::where('key', 'current_currency')->value('value');
        $user = Auth::user();
        if ($user->hasRole(Role::ROLE_CLIENT)) {
            $currencyId = Setting::where('tenant_id', getClientAdminTenantId())->where('key', 'current_currency')->withoutGlobalScope(new TenantScope())->value('value');
        }

        $currencyCode = Currency::whereId($currencyId)->first();

        return $currencyCode->code;
    }
}

if (! function_exists('getInvoiceCurrencyCode')) {
    /**
     * @return HigherOrderBuilderProxy|mixed
     */
    function getInvoiceCurrencyCode($currencyId, $tenantId)
    {
        $currencyCode = Currency::whereId($currencyId)->first();
        if (! empty($currencyCode)) {
            return $currencyCode->code;
        } else {
            if(isAuth()) {
                $currenyId = getSettingValue('current_currency');
            }else {
                $setting = Setting::where('tenant_id', $tenantId)->where('key', '=', 'current_currency')->first();
                $currenyId = $setting->value;
            }

            $currencyCode = Currency::whereId($currenyId)->first();
            return $currencyCode->code ?? '';
        }
    }
}

if (! function_exists('getSubscriptionPlanCurrencyIcon')) {

    function getSubscriptionPlanCurrencyIcon($key): string
    {
        $currencyPath = file_get_contents(storage_path().'/currencies/defaultCurrency.json');
        $currenciesData = json_decode($currencyPath, true)['currencies'];
        $currency = collect($currenciesData)->firstWhere(
            'code',
            strtoupper($key)
        );

        return $currency['icon'];
    }
}

if (! function_exists('getAdminSubscriptionPlanCurrencyIcon')) {

    function getAdminSubscriptionPlanCurrencyIcon($key): string
    {
        static $adminCurrencyIcon;

        if (! isset($adminCurrencyIcon[$key]) && empty($adminCurrencyIcon[$key])) {
            $currencyData = AdminCurrency::where('id', $key)->first();
            if ($currencyData != null) {
                $adminCurrencyIcon[$key] = $currencyData->icon;
            }
        }

        return $adminCurrencyIcon[$key];
    }
}

if (! function_exists('getAdminPlanCurrencyCode')) {
    function getAdminPlanCurrencyCode($key)
    {
        $currencyData = AdminCurrency::where('code', $key)->first();
        if ($currencyData != null) {
            $currencyIcon = $currencyData->code;

            return $currencyIcon;
        }
    }
}

if (! function_exists('preparePhoneNumber')) {
    /**
     * @param  array  $input
     * @param  string  $key
     * @return string|null
     */
    function preparePhoneNumber($input, $key)
    {
        return (! empty($input[$key])) ? '+'.$input['prefix_code'].$input[$key] : null;
    }
}

if (! function_exists('getCurrentVersion')) {
    /**
     * @return mixed
     */
    function getCurrentVersion()
    {
        $composerFile = file_get_contents('../composer.json');
        $composerData = json_decode($composerFile, true);
        $currentVersion = $composerData['version'];

        return $currentVersion;
    }
}

if (! function_exists('currencyPosition')) {
    /**
     * @param  null  $tenantId
     * @return mixed
     */
    function currencyPosition($tenantId = null)
    {
        static $currencyPosition;
        if (empty($currencyPosition)) {
            $currencyPosition = Setting::where([
                'tenant_id' => (Auth::check()) ? Auth::user()->tenant_id : $tenantId, 'key' => 'currency_after_amount',
            ])->value('value');
        }

        return $currencyPosition;
    }
}

if (! function_exists('superAdminCurrencyPosition')) {
    /**
     * @return mixed
     */
    function superAdminCurrencyPosition()
    {
        static $currencyPosition;
        if (empty($currencyPosition)) {
            $currencyPosition = SuperAdminSetting::where('key', 'currency_after_amount')->first()->value;
        }

        return $currencyPosition;
    }
}

if (! function_exists('superAdminCurrencyAmount')) {
    function superAdminCurrencyAmount($amount, $formatting = false, $currency = null)
    {
        $formattedAmount = $formatting ? numberFormat($amount) : number_format($amount, 2);
        $currencySymbol = is_null($currency) ? getCurrencySymbol() : $currency;
        if (superAdminCurrencyPosition()) {
            return $formattedAmount.' '.$currencySymbol;
        }

        return $currencySymbol.' '.$formattedAmount;
    }
}

if (! function_exists('getCurrencyAmount')) {
    /**
     * @param  false  $formatting
     * @param  null  $tenantId
     */
    function getCurrencyAmount($amount, $formatting = false, $tenantId = null): string
    {
        if (empty($tenantId) && Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            $tenantId = $user->tenant_id;
        }
        $currencyPosition = is_null($tenantId) ? currencyPosition() : currencyPosition($tenantId);
        $currencySymbol = is_null($tenantId) ? getCurrencySymbol() : getCurrencySymbol($tenantId);
        $formattedAmount = $formatting ? numberFormat($amount) : formatTotalAmount($amount);
        if ($currencyPosition) {
            return $formattedAmount.' '.$currencySymbol;
        }

        return $currencySymbol.' '.$formattedAmount;
    }
}

if (! function_exists('getInvoiceCurrencyAmount')) {
    /**
     * @param  false  $formatting
     * @param  null  $tenantId
     */
    function getInvoiceCurrencyAmount($amount, $currencyId, $formatting = false, $tenantId = null): string
    {
        if (empty($tenantId) && Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            $tenantId = $user->tenant_id;
        }

        $currencyPosition = is_null($tenantId) ? currencyPosition() : currencyPosition($tenantId);

        $currencySymbol = Currency::where('id', $currencyId)->pluck('icon')->first();

        $formattedAmount = $formatting ? numberFormat($amount) : formatTotalAmount($amount);
        if ($currencyPosition) {
            return $formattedAmount.' '.$currencySymbol;
        }

        return $currencySymbol.' '.$formattedAmount;
    }
}

if (! function_exists('currentTenantId')) {
    function currentTenantId()
    {
        static $currentTenantId;
        if (empty($currentTenantId) && Auth::check()) {
            $currentTenantId = Auth::user()->tenant_id;
        }

        return $currentTenantId;
    }
}

if (! function_exists('getManualPayment')) {

    function getManualPayment($tenantId): mixed
    {
        static $manualPayment;

        if (empty($tenantId)) {
            $tenantId = Auth::user()->tenant_id;
        }

        if (empty($manualPayment)) {
            $manualPayment = Setting::where('key', '=', 'payment_auto_approved')->where('tenant_id', $tenantId)->firstOrFail();
        }

        return $manualPayment->value;
    }
}

if (! function_exists('getInvoicePaidAmount')) {

    function getInvoicePaidAmount($invoiceId): float|int
    {
        $dueAmount = 0;
        $paid = 0;
        $invoice = Invoice::whereId($invoiceId)->with('payments')->firstOrFail();

        foreach ($invoice->payments as $payment) {
            if ($payment->payment_mode == \App\Models\Payment::MANUAL && $payment->is_approved !== \App\Models\Payment::APPROVED) {
                continue;
            }
            $paid += $payment->amount;
        }

        return $paid;
    }
}

if (! function_exists('getInvoiceDueAmount')) {
    /**
     * @return float|HigherOrderBuilderProxy|int|mixed|null
     */
    function getInvoiceDueAmount($invoiceId)
    {
        $dueAmount = 0;
        $paid = 0;
        $invoice = Invoice::whereId($invoiceId)->with('payments')->firstOrFail();

        foreach ($invoice->payments as $payment) {
            if ($payment->payment_mode == Payment::MANUAL && $payment->is_approved !== Payment::APPROVED) {
                continue;
            }
            $paid += $payment->amount;
        }
        $dueAmount = $invoice->final_amount - $paid;

        return $dueAmount;
    }
}

if (! function_exists('checkLanguageSession')) {

    function checkLanguageSession(): string
    {
        if (Session::has('languageName')) {
            return Session::get('languageName');
        }

        return 'en';
    }
}

if (! function_exists('getLanguages')) {
    /**
     * @return string[]
     */
    function getLanguages(): array
    {
        return User::LANGUAGES;
    }
}

if (! function_exists('getCurrentLanguageName')) {

    function getCurrentLanguageName(): mixed
    {
        return Auth::user()->language;
    }
}

if (! function_exists('getClient')) {
    /**
     * @return Client|Builder|Model|object|null
     */
    function getClient($userId)
    {
        return Client::whereUserId($userId)->withoutGlobalScope(new TenantScope())->first();
    }
}

if (! function_exists('getLoginTenantNames')) {

    function getLoginTenantNames(): mixed
    {
        static $organizationNames;

        if (empty($organizationNames)) {
            $loginTenantIds = getLogInUser()->clients->pluck('tenant_id')->toArray();

            $organizationNames = Tenant::whereIn('id', $loginTenantIds)->pluck('tenant_username', 'id')->toArray();
        }

        return $organizationNames;
    }
}

if (! function_exists('getMonthlyData')) {

    function getMonthlyData(): string
    {
        $carbon = Carbon::now();
        $startDate = $carbon->startOfMonth()->format('Y-m-d');
        $endDate = $carbon->endOfMonth()->format('Y-m-d');

        return $startDate.' - '.$endDate;
    }
}

if (! function_exists('getInvoiceTemplateId')) {
    /**
     * @return string
     */
    function getInvoiceTemplateId()
    {
        $setting = Setting::whereTenantId(getLogInUser()->tenant_id)->where('key', 'default_invoice_template')->first();
        $invoiceSetting = InvoiceSetting::whereTenantId(getLogInUser()->tenant_id)->where('key', $setting->value)->first();

        return $invoiceSetting->id ?? null;
    }
}

if(! function_exists('getVatNoLabel')) {
    function getVatNoLabel()
    {
        $vatNoLabel = SuperAdminSetting::where('key', 'vat_no_label')->first()->value ?? 'GSTIN';

        return $vatNoLabel;
    }
}

if(! function_exists('getPublicInvoiceAppURL')) {
    function getPublicInvoiceAppURL($tenantId)
    {
        $setting = Setting::where('key', '=', 'app_logo')->where('tenant_id', $tenantId)->first();
        $appLogoURL = !empty($setting->logo_url) ? asset($setting->logo_url) : '';


        return $appLogoURL;
    }
}

if (! function_exists('getStripeSupportedCurrencies')) {
    function getStripeSupportedCurrencies()
    {
        return [
            'AUD', 'BRL', 'CAD', 'CNY', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'ILS', 'JPY', 'MYR', 'MXN', 'TWD', 'NZD', 'NOK',
            'PHP', 'PLN', 'GBP', 'RUB', 'SGD', 'SEK', 'CHF', 'THB', 'USD','AED','ALL','AMD','ANG','AWG','AZN','BAM','BBD','BDT','BGN','BMD','BND',
            'BSD','BWP','BYN','BZD','CAD','CDF','CHF','CNY','CZK','DKK','DOP','DZD','EGP','ETB','EUR','FJD','GBP','GEL','GIP','GMD','GYD','HKD','HTG',
            'HUF','IDR','ILS','INR','ISK','JMD','JPY','KES','KGS','KHR','KMF','KRW','KYD','KZT','LBP','LKR','LRD','LSL','MAD','MDL','MGA','MKD','MMK','MNT',
            'MOP','MVR','MWK','MXN','MYR','MZN','NAD','NGN','NOK','NPR','NZD','PGK','PHP','PKR','PLN','QAR','RON','RSD','RUB','RWF','SAR','SBD','SCR','SEK',
            'SGD','SLE','SOS','SZL','THB','TJS','TOP','TRY','TTD','TWD','TZS','UAH','UGX','UZS','VND','VUV','WST','XAF','XCD','YER','ZAR','ZMW',
        ];
    }
}

if (! function_exists('getInvoiceStatusArr')) {
    function getInvoiceStatusArr()
    {
        $statusArr = Invoice::STATUS_ARR;
        unset($statusArr[Invoice::STATUS_ALL]);
        unset($statusArr[Invoice::DRAFT]);
        $statusArr[''] = 'All';
        asort($statusArr);

        return $statusArr;
    }
}

if (! function_exists('getRecurringInvoiceStatusArr')) {
    function getRecurringInvoiceStatusArr()
    {
        $recurringStatsArr = Invoice::RECURRING_STATUS_ARR;
        $recurringStatsArr[''] = 'All';
        asort($recurringStatsArr);

        return $recurringStatsArr;
    }
}

if(! function_exists('getSuperAdminSetting')) {
    function getSuperAdminSetting($key)
    {
        return SuperAdminSetting::where('key', $key)->first();
    }
}

if (! function_exists('getPaymentModeArr')) {
    function getPaymentModeArr()
    {
        $statusArr = Payment::PAYMENT_MODE;
        $statusArr[''] = 'All';
        asort($statusArr);

        return $statusArr;
    }
}

if (! function_exists('getPaymentStatusArr')) {
    function getPaymentStatusArr()
    {
        $statusArr = Payment::PAYMENT_APPROVE;
        $statusArr[''] = 'All';
        asort($statusArr);

        return $statusArr;
    }

    function cleanHtml($html)
{
    // Remove unwanted tags and attributes
    $html = strip_tags($html, '<p><b><i><u><s><strike><sub><sup><span><font><ul><ol><li><table><tr><td><th><tbody><thead><tfoot>');

    // Remove empty tags
    $html = preg_replace('/<[^\/>]*>([\s]?)*<\/[^>]*>/', '', $html);

    // Remove multiple whitespace characters
    $html = preg_replace('/\s+/', ' ', $html);

    // Remove leading and trailing whitespace characters
    $html = trim($html);

    // Convert HTML entities to characters
    $html = html_entity_decode($html, ENT_COMPAT, 'UTF-8');

    return $html;
}
}
