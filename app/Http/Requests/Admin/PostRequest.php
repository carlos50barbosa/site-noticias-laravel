<?php

namespace App\Http\Requests\Admin;

use App\Enums\PostStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Autorização fina (propriedade) é feita no controller via Policy.
        return (bool) $this->user();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:180'],
            'excerpt' => ['nullable', 'string', 'max:300'],
            'content' => ['required', 'string'],
            'cover_image_url' => ['nullable', 'string', 'max:1000'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'status' => ['required', Rule::enum(PostStatus::class)],
            'tags' => ['nullable', 'string', 'max:255'],
            'scheduled_at' => ['nullable', 'date', 'required_if:status,SCHEDULED'],
            'pinned' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'scheduled_at.required_if' => 'Informe a data/hora para agendar a publicação.',
        ];
    }
}
