<?php

namespace App\Livewire\Forms;

use App\Models\OfferTemplate;
use Livewire\Attributes\Validate;
use Livewire\Form;

class OfferTemplateForm extends Form
{
    #[Validate('required|unique:offer_templates,name')]
    public ?string $name;
    #[Validate('nullable|max:255')]
    public ?string $description;
    #[Validate('required|exists:offer_types,id')]
    public ?int $offer_type_id;
    #[Validate('required|numeric')]
    public ?int $buy_qty;
    #[Validate('required|numeric')]
    public ?int $pay_qty;

    protected function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.unique' => 'Ya existe una plantilla con ese nombre.',
            'buy_qty.required' => 'La cantidad de compra es obligatoria.',
            'pay_qty.required' => 'La cantidad de pago es obligatoria.',
            'offer_type_id.required' => 'Debes seleccionar una plantilla.',
            'offer_type_id.exists' => 'No existe la plantilla.',
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
