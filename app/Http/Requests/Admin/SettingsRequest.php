<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->can('manage-settings');
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'site_name' => ['required', 'string', 'min:2', 'max:60'],
            'logo_url' => ['nullable', 'string', 'max:1000'],
            'favicon_url' => ['nullable', 'string', 'max:1000'],
            'adsense_client' => ['nullable', 'regex:/^ca-pub-\d{10,}$/'],
            'current_password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'adsense_client.regex' => 'Use o formato ca-pub-XXXXXXXXXXXXXXXX.',
        ];
    }
}
