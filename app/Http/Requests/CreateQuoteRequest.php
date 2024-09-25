<?php

namespace App\Http\Requests;

use App\Models\Quote;
use Illuminate\Foundation\Http\FormRequest;

class CreateQuoteRequest extends FormRequest
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
        return Quote::$rules;
    }

    public function messages(): array
    {
        return [
            'client_id.required' => __('messages.quote.client_id_required'),
            'quote_date.required' => __('messages.quote.quote_date_required'),
            'due_date.required' => __('messages.quote.due_date_required'),
        ];
    }
}
