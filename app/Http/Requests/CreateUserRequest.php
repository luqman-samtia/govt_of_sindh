<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
        return User::$rules;
    }

    public function messages(): array
    {
        return [
            'password_confirmation.required' => __('validation.required', ['attribute' => 'confirm password']),
            'password.same' => __('validation.same', ['attribute' => 'password', 'other' => 'confirm password'])
        ];
    }
}
