<?php

namespace App\Http\Requests;

use App\Models\SuperAdminEnquiry;
use Illuminate\Foundation\Http\FormRequest;

class CreateSuperAdminEnquiryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = SuperAdminEnquiry::$rules;

        return $rules;
    }
}
