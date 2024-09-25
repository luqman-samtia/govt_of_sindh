<?php

namespace App\Http\Requests;

use App\Models\Client;
use Illuminate\Foundation\Http\FormRequest;
use Stancl\Tenancy\Database\TenantScope;

class CreateClientRequest extends FormRequest
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
        $existUser = \App\Models\User::whereEmail(request()->email)->withoutGlobalScope(new TenantScope())->first();

        if (empty($existUser)) {
            return Client::$rules;
        }

        return [];
    }
}
