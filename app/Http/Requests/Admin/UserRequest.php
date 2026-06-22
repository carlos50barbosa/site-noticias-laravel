<?php

namespace App\Http\Requests\Admin;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->can('manage-users');
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $id = $this->route('user')?->id;

        return [
            'name' => ['required', 'string', 'min:2', 'max:80'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($id)],
            'role' => ['required', Rule::enum(Role::class)],
            'password' => [$id ? 'nullable' : 'required', 'string', 'min:8'],
        ];
    }
}
