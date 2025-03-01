<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DomainCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'date_registration' => ['required', 'date'],
            'date_end' => ['required', 'date'],
            'note' => ['nullable', 'string'],
            'dns_provider' => ['required', 'string', 'max:255'],
            'dns_provider_login' => ['required', 'string', 'max:255'],
            'is_active_for_ping' => ['required', 'boolean'],
            'is_active_for_code' => ['required', 'boolean'],
        ];
    }
}
