<?php

namespace App\Repositories;

use App\Models\Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class PaymentGatewayRepository extends BaseRepository
{
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
        'paystack_enabled',
        'paystack_key',
        'paystack_secret',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Setting::class;
    }

    public function getSyncList()
    {
        return Setting::where('tenant_id', Auth::user()->tenant_id)->pluck('value', 'key')->toBase();
    }

    public function store($input): bool
    {
        $input['stripe_enabled'] = (! isset($input['stripe_enabled'])) ? 0 : 1;
        $input['paypal_enabled'] = (! isset($input['paypal_enabled'])) ? 0 : 1;
        $input['razorpay_enabled'] = (! isset($input['razorpay_enabled'])) ? 0 : 1;
        $input['paystack_enabled'] = (! isset($input['paystack_enabled'])) ? 0 : 1;

        $this->checkPaymentValidation($input);
        $inputArray = Arr::only($input, $this->availableKeys);
        foreach ($inputArray as $key => $value) {
            $value = $value ?? '';
            $setting = Setting::where('tenant_id', Auth::user()->tenant_id)->where('key', '=', $key)->first();

            if (empty($setting)) {
                Setting::create([
                    'tenant_id' => Auth::user()->tenant_id,
                    'key' => $key,
                    'value' => $value,
                ]);
            }else {
                $setting->update(['value' => $value]);
            }
        }

        return true;
    }

    private function checkPaymentValidation($input): void
    {
        if (isset($input['stripe_enabled']) && $input['stripe_enabled'] == 1 &&
            (empty($input['stripe_key']) || empty($input['stripe_secret']))) {
            throw new UnprocessableEntityHttpException('Please fill up all value for stripe payment gateway.');
        }
        if (isset($input['paypal_enabled']) && $input['paypal_enabled'] == 1 &&
            (empty($input['paypal_client_id']) || empty($input['paypal_secret']))) {
            throw new UnprocessableEntityHttpException('Please fill up all value for paypal payment gateway.');
        }
        if (isset($input['razorpay_enabled']) && $input['razorpay_enabled'] == 1 &&
            (empty($input['razorpay_key']) || empty($input['razorpay_secret']))) {
            throw new UnprocessableEntityHttpException('Please fill up all value for razorpay payment gateway.');
        }
        if (isset($input['paystack_enabled']) && $input['paystack_enabled'] == 1 &&
            (empty($input['paystack_key']) || empty($input['paystack_secret']))) {
            throw new UnprocessableEntityHttpException('Please fill up all value for paystack payment gateway.');
        }
    }
}
