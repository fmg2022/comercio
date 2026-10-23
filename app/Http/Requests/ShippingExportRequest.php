<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ShippingExportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()?->can('update_shippings');
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'delivered_null' => $this->boolean('delivered_null'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'users' => 'nullable|array',
            'users.*' => 'nullable|exists:users,id',
            'states' => 'nullable|array',
            'states.*' => 'nullable|exists:shipping_states,id',
            'date_from' => 'nullable|date|date_format:Y-m-d',
            'date_to' => 'nullable|date|date_format:Y-m-d|after_or_equal:date_from',
            'date_delivered_from' => 'nullable|date|date_format:Y-m-d',
            'date_delivered_to' => 'nullable|date|date_format:Y-m-d|after_or_equal:date_delivered_from',
            'delivered_null' => 'nullable|boolean',
        ];
    }
}
