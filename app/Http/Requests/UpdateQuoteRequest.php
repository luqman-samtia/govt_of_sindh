<?php

namespace App\Http\Requests;

use App\Models\Quote;
use Illuminate\Foundation\Http\FormRequest;

class UpdateQuoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = Quote::$rules;
        $rules['quote_id'] = 'required|unique:quotes,quote_id,'.$this->route('quote')->id;

        return $rules;
    }

    public function messages(): array
    {
        return [
            'client_id.required' => __('messages.quote.client_id_required'),
            'quote_date.required' => __('messages.quote.quote_date_required'),
            'due_date' => __('messages.quote.due_date_required'),
        ];
    }
}
