<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderExportRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'users' => 'nullable|array',
            'users.*' => 'nullable|exists:users,id',
            'date_from' => 'nullable|date|date_format:Y-m-d',
            'date_to' => 'nullable|date|date_format:Y-m-d|after_or_equal:date_from',
            'states' => 'nullable|array',
            'states.*' => 'nullable|exists:order_states,id',
            'total_from' => 'nullable|numeric',
            'total_to' => 'nullable|numeric',
        ];
    }
}
