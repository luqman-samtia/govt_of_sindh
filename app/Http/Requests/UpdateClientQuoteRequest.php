<?php

namespace App\Http\Requests;

use App\Models\Quote;
use Illuminate\Foundation\Http\FormRequest;

class UpdateClientQuoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array|string[]
     */
    public function rules(): array
    {
        $rules = Quote::$rules;
        $rules['quote_id'] = 'required|unique:quotes,quote_id,'.$this->route('quote')->id;

        return $rules;
    }

    public function messages(): array
    {
        return Quote::$messages;
    }
}
