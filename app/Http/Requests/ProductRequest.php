<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'mark' => 'required|string|max:255',
            'price' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku,' . $this->route('product'), // Ensure SKU is unique except for the current product
            'quantity' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'string|max:255',
            'description' => 'nullable|string|max:1000',
        ];
    }
}
