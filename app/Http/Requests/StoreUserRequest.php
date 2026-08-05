<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasAnyPermission(['create_users', 'update_users']);
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'active' => $this->boolean('active'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:150',
            'surname' => 'required|string|max:150',
            'image' => 'nullable|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                // Rule::unique('users', 'email')->whereNull('deleted_at')->ignore($this->user()->id)
            ],
            'phone' => 'required|string|max:20',
            'dni' => 'required|string|max:20',
            'active' => 'required|boolean',
        ];
    }
}
