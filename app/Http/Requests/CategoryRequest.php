<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                Rule::notIn([$this->route('category')?->id])
            ],
            'children' => 'sometimes|array',
            'children.*' => 'exists:categories,id'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre de la categoría es obligatorio.',
            'parent_id.not_in' => 'La categoría no puede ser padre de sí misma.',
            'parent_id.exists' => 'La categoría padre seleccionada no existe.',
            'children.*.exists' => 'Una o más subcategorías seleccionadas no existen',
        ];
    }
}
