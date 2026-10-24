<?php

namespace App\Livewire\Forms;

use App\Models\OfferTemplate;
use Livewire\Attributes\Validate;
use Livewire\Form;

class OfferTemplateForm extends Form
{
    #[Validate('required|string|max:255|unique:offer_templates,name')]
    public ?string $name = null;
    #[Validate('nullable|string|max:255')]
    public ?string $description = null;
    #[Validate('required|exists:offer_types,id')]
    public ?int $offer_type_id = null;
    #[Validate('required|integer|min:1')]
    public ?int $buy_qty = null;
    #[Validate('required|numeric|min:0')]
    public ?float $pay_qty = null;

    protected function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.unique' => 'Ya existe una plantilla con ese nombre.',
            'buy_qty.required' => 'La cantidad de compra es obligatoria.',
            'buy_qty.integer' => 'La cantidad de compra debe ser un número entero.',
            'buy_qty.min' => 'La cantidad de compra debe ser al menos 1.',
            'pay_qty.required' => 'La cantidad de pago es obligatoria.',
            'pay_qty.numeric' => 'La cantidad de pago debe ser un número.',
            'pay_qty.min' => 'La cantidad de pago no puede ser negativa.',
            'offer_type_id.required' => 'Debes seleccionar un tipo base.',
            'offer_type_id.exists' => 'El tipo seleccionado no existe.',
        ];
    }

    public function store()
    {
        $this->validate();

        OfferTemplate::create([
            'name' => $this->name,
            'description' => $this->description ?? '',
            'offer_type_id' => $this->offer_type_id,
            'buy_qty' => $this->buy_qty,
            'pay_qty' => $this->pay_qty,
        ]);

        $this->reset();
    }
}
