<?php

namespace App\Http\Requests;

use App\Models\SubscriptionPlan;
use Illuminate\Foundation\Http\FormRequest;

class CreateSubscriptionPlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $price = $this->request->get('price');
        $this->request->set('price', removeCommaFromNumbers($price));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return SubscriptionPlan::$rules;
    }
}
