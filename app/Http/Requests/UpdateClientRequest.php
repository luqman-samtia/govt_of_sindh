<?php

namespace App\Http\Requests;

use App\Models\Client;
use Illuminate\Foundation\Http\FormRequest;
use Stancl\Tenancy\Database\TenantScope;

class UpdateClientRequest extends FormRequest
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
        $rules = Client::$rules;
        $client = Client::whereId($this->route('client'))->withoutGlobalScope(new TenantScope())->first();
        $userId = \App\Models\User::whereId($client->user_id)->withoutGlobalScope(new TenantScope())->first()->id;
        $rules['email'] = 'required|email:filter|regex:/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/|unique:users,email,'.$userId;
        $rules['contact'] = 'nullable|is_unique:users,contact,'.$userId;

        return $rules;
    }
}
