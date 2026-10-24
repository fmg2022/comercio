<?php

namespace App\Livewire\Forms;

use App\Models\Offer;
use App\Models\OfferState;
use Livewire\Attributes\Validate;
use Livewire\Form;

class OfferForm extends Form
{
    #[Validate('required|max:255')]
    public ?string $name = null;

    #[Validate('required|date')]
    public ?string $start_date = null;

    #[Validate('required|date|after:start_date')]
    public ?string $end_date = null;

    #[Validate('required|exists:offer_templates,id')]
    public ?int $offer_template_id = null;

    #[Validate('nullable|exists:offer_states,id')]
    public ?int $offer_state_id = null;

    #[Validate('nullable|array')]
    public ?array $product_ids = [];

    public ?int $offer_id = null;

    protected function rules()
    {
        $rules = [
            'name' => 'required|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'offer_template_id' => 'required|exists:offer_templates,id',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
        ];

        if ($this->offer_id) {
            $rules['name'] .= '|unique:offers,name,' . $this->offer_id;
            $rules['offer_state_id'] = 'required|exists:offer_states,id';
        } else {
            $rules['name'] .= '|unique:offers,name';
            $rules['start_date'] .= '|after_or_equal:now';
        }

        return $rules;
    }

    protected function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.unique' => 'Ya existe una oferta con ese nombre.',
            'start_date.required' => 'La fecha de inicio es obligatoria.',
            'start_date.after_or_equal' => 'La fecha de inicio debe ser hoy o posterior.',
            'end_date.required' => 'La fecha de fin es obligatoria.',
            'end_date.after' => 'La fecha de fin debe ser posterior a la de inicio.',
            'offer_template_id.required' => 'Debes seleccionar una plantilla.',
            'offer_template_id.exists' => 'La plantilla seleccionada no existe.',
            'offer_state_id.required' => 'Debes seleccionar un estado.',
            'offer_state_id.exists' => 'El estado seleccionado no existe.',
            'product_ids.array' => 'El formato de productos no es válido.',
            'product_ids.*.exists' => 'Uno de los productos seleccionados no existe.',
        ];
    }

    public function setOffer(Offer $offer)
    {
        $this->offer_id = $offer->id;
        $this->name = $offer->name;
        $this->start_date = $offer->start_date?->format('Y-m-d\TH:i');
        $this->end_date = $offer->end_date?->format('Y-m-d\TH:i');
        $this->offer_template_id = $offer->offer_template_id;
        $this->offer_state_id = $offer->offer_state_id;
        $this->product_ids = $offer->relationLoaded('products')
            ? $offer->products->pluck('id')->toArray()
            : $offer->products()->pluck('products.id')->toArray();
    }

    public function save(bool $isActive = false)
    {
        $this->validate();

        if ($this->offer_id) {
            // Actualizar
            $offer = Offer::with('offerState:id,slug')->findOrFail($this->offer_id);

            // Estados terminales no editables
            if (in_array($offer->offerState?->slug, ['expired', 'cancelled'], true)) {
                throw new \Exception('No se puede editar una oferta expirada o cancelada.');
            }

            $offer->update([
                'name' => $this->name,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'offer_template_id' => $this->offer_template_id,
                'offer_state_id' => $this->offer_state_id,
            ]);

            $offer->products()->sync($this->product_ids ?? []);
            $message = "Oferta '{$offer->name}' actualizada correctamente.";
        } else {
            // Crear: usar isActive para elegir el estado
            $stateId = $isActive
                ? OfferState::where('slug', 'active')->value('id')
                : OfferState::where('slug', 'pending')->value('id');

            if (!$stateId) {
                throw new \Exception('No se encontraron los estados de oferta necesarios.');
            }

            $offer = Offer::create([
                'name' => $this->name,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'offer_template_id' => $this->offer_template_id,
                'offer_state_id' => $stateId,
            ]);

            $offer->products()->sync($this->product_ids ?? []);
            $message = "Oferta '{$offer->name}' creada correctamente.";
        }

        $this->reset();

        return $message;
    }
}
