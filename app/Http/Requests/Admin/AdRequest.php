<?php

namespace App\Http\Requests\Admin;

use App\Enums\AdPlacement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->can('manage-ads');
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:120'],
            'image_url' => ['required', 'string', 'max:1000'],
            'link_url' => ['required', 'url', 'max:1000'],
            'placement' => ['required', Rule::enum(AdPlacement::class)],
            'active' => ['nullable', 'boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ];
    }
}
