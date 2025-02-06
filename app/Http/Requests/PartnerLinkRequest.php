<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartnerLinkRequest extends FormRequest
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
            'partner_id' => ['required', 'exists:partners,id'],
            'topic_id' => ['required', 'exists:topics,id'],
            'url' => ['required', 'url'],
            'name' => ['required', 'string'],
            'helper_text' => ['nullable', 'string'],
        ];
    }
}
