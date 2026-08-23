<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'street_1' => 'required|string|max:255',
            'street_2' => 'nullable|string|max:255',
            'province' => 'required|string|max:255',
            'locality' => 'required|string|max:255',
            'postal_code' => 'required|string|max:255',
        ];
    }
}
