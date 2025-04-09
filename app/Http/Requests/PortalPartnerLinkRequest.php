<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PortalPartnerLinkRequest extends FormRequest
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
            'portal_partner_links' => ['required', 'array'],
            'portal_partner_links.*.portal_id' => ['required', 'exists:portals,id'],
            'portal_partner_links.*.partner_link_id' => ['required', 'exists:partner_links,id'],
            'portal_partner_links.*.conditions' => ['array'],
            'portal_partner_links.*.conditions.country.operator' => ['nullable', 'in:in,not'],
            'portal_partner_links.*.conditions.country.values' => ['nullable', 'array'],
            'portal_partner_links.*.conditions.lendings.values' => ['nullable', 'array'],
            'portal_partner_links.*.conditions.device.value' => ['nullable', 'in:desktop,mobile'],
            'portal_partner_links.*.priority' => ['required', 'integer', 'min:0'],
            'portal_partner_links.*.is_fallback' => ['required', 'boolean'],
        ];
    }
}
