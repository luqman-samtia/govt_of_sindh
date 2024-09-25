<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class CreateRoleRequest extends FormRequest
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
        return Role::$rules;
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'display_name.required' => 'Name field is required',
            'permission_id.required' => 'Please select any one permission',
        ];
    }
}
