<?php

use App\Livewire\Forms\{OfferForm, OfferTemplateForm};
use Livewire\Attributes\{Computed, Layout, On, Validate};
use Livewire\Component;
use Livewire\WithPagination;

use App\Models\{Offer, OfferState, OfferTemplate, OfferType};

new #[Layout('layouts::dashboard')] class extends Component {
    use WithPagination;

    public OfferTemplateForm $offerTemplateForm;
    public OfferForm $offerForm;

    // Estado del modal
    public $is_active = false;
    public $modalMode = 'create';
    public $modalOpen = false;

    #[Computed]
    public function offers()
    {
        return Offer::with(['offerState:id,slug,name', 'offerTemplate:id,name'])
            ->select(['id', 'name', 'start_date', 'end_date', 'offer_state_id', 'offer_template_id'])
            ->orderBy('start_date', 'desc')
            ->paginate(10);
    }

    #[Computed]
    public function offerTemplates()
    {
        return OfferTemplate::orderBy('name')->get(['id', 'name']);
    }

    #[Computed]
    public function offerStates()
    {
        return OfferState::all(['id', 'slug', 'name']);
    }

    #[Computed]
    public function offerTypes()
    {
        return OfferType::orderBy('name')->get(['id', 'name']);
    }

    // MODALS
    public function createOfferModal()
    {
        $this->offerForm->reset();
        $this->is_active = false;
        $this->modalMode = 'create';
        $this->modalOpen = true;
    }

    public function editOfferModal($id)
    {
        $offer = Offer::findOrFail($id);
        $this->offerForm->setOffer($offer);
        $this->modalMode = 'edit';
        $this->modalOpen = true;
    }

    public function showOfferModal($id)
    {
        $offer = Offer::with('offerState', 'offerTemplate')->findOrFail($id);
        $this->offerForm->setOffer($offer);
        $this->modalMode = 'show';
        $this->modalOpen = true;
    }

    #[On('closeModal')]
    public function closeModal()
    {
        $this->modalOpen = false;
        $this->offerForm->reset();
    }

    // MODALS ACTIONS
    public function saveOffer()
    {
        $message = $this->offerForm->save($this->is_active);

        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
        $this->modalOpen = false;
        $this->offerForm->reset();
        $this->is_active = false;
        $this->resetPage(); // Para refrescar la paginación
    }

    public function deleteOffer(int $id)
    {
        $offer = Offer::findOrFail($id);
        $type = 'success';
        $message = '';
        $offerName = $offer->name ?? $offer->offerTemplate->name;

        if ($offer->offerState->slug === 'pending') {
            $offer->delete();
            $message = "Oferta '{$offerName}' eliminada correctamente.";
        } else {
            $message = "Oferta '{$offerName}' no puede ser eliminada.";
            $type = 'error';
        }

        $this->dispatch('notify', [
            'type' => $type,
            'message' => $message,
        ]);
        $this->resetPage(); // Para refrescar la paginación
    }

    public function saveOfferTemplate()
    {
        $this->offerTemplateForm->store();

        unset($this->offerTemplates);
        $newTemplate = OfferTemplate::where('name', $this->offerTemplateForm->name)->select('id')->first();

        if ($newTemplate) {
            $this->offer_template_id = $newTemplate->id;
        }

        $this->dispatch('close-template-modal');
        $this->dispatch('notify', ['type' => 'success', 'message' => "Plantila '{$this->offerTemplateForm->name}' creada correctamente."]);
    }

    public function getIsStateEditableProperty()
    {
        if ($this->modalMode !== 'edit' || !$this->offerForm->offer_state_id) {
            return false;
        }
        $state = $this->offerStates->firstWhere('id', $this->offerForm->offer_state_id);
        return $state && !in_array($state->slug, ['expired', 'cancelled']);
    }
};
?>

<div x-data="{
    modalOpen: $wire.entangle('modalOpen'),
    modalMode: $wire.entangle('modalMode'),
    notification: { show: false, type: 'success', message: '', timer: null },
    init() {
        Livewire.on('notify', (payload) => {
            this.notification.type = payload[0].type || 'success';
            this.notification.message = payload[0].message || 'Operación realizada.';
            this.notification.show = true;

            clearTimeout(this.notification.timer);
            this.notification.timer = setTimeout(() => { this.notification.show = false; }, 4000);
        });
    }
}">
  <div x-cloak x-show="notification.show" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-x-full"
    x-transition:enter-end="opacity-100 transform translate-x-0" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-x-0"
    x-transition:leave-end="opacity-0 transform translate-x-full"
    x-bind:class="{
        'bg-green-600': notification.type === 'success',
        'bg-red-600': notification.type === 'error',
        'bg-yellow-600': notification.type === 'warning',
    }"
    class="fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white font-semibold flex items-center gap-3">

    <span x-show="notification.type === 'success'">✅</span>
    <span x-show="notification.type === 'error'">❌</span>
    <span x-show="notification.type === 'warning'">⚠️</span>
    <span x-text="notification.message"></span>

    <button @click="notification.show = false"
      class="ml-4 text-white/80 hover:text-white text-xl leading-none cursor-pointer">
      <x-icons.x class="size-6" />
    </button>
  </div>

  <x-sections.headerTitle class="flex justify-between items-center">
    <x-slot:textTitle>Lista de Ofertas</x-slot:textTitle>

    @can('manage_offers')
      <div class="flex flex-wrap gap-4">
        <button type="button" @click="$dispatch('open-template')"
          class="relative px-4 py-2 flex items-center gap-2 rounded-md cursor-pointer bg-purple-700 active:bg-purple-800">
          <x-icons.plus class="size-6" />
          Nueva Plantilla
        </button>
        <button type="button" wire:click="createOfferModal"
          class="relative px-4 py-2 flex items-center gap-2 rounded-md cursor-pointer bg-purple-700 active:bg-purple-800">
          <x-icons.plus class="size-6" />
          Nueva Oferta
          <span wire:loading wire:target="createOfferModal" wire:loading.flex
            class="absolute w-full h-full top-0 left-0 bg-slate-700 opacity-60 z-10 items-center justify-center">
            <x-icons.animate.spinner class="size-4" />
          </span>
        </button>
      </div>
    @endcan
  </x-sections.headerTitle>

  <x-tables.table>
    <x-slot:thead>
      <tr class="text-left">
        <th>#</th>
        <th>Nombre</th>
        <th class="hidden lg:table-cell">Tipo</th>
        <th>Fecha Inicio</th>
        <th>Fecha Fin</th>
        <th>Estado</th>
        <th class="text-right">Opciones</th>
      </tr>
    </x-slot:thead>
    <x-slot:tbody>
      @forelse ($this->offers as $index => $offer)
        <tr>
          <td>{{ ($this->offers->currentPage() - 1) * $this->offers->perPage() + $index + 1 }}</td>
          <td class="font-bold">{{ $offer->name }}</td>
          <td class="hidden lg:table-cell">{{ $offer->offerTemplate->name }}</td>
          <td>{{ $offer->start_date->format('d/m/Y') }}</td>
          <td>{{ $offer->end_date->format('d/m/Y') }}</td>
          <td>
            <span @class([
                "font-semibold before:content-['●'] before:me-px",
                'text-amber-400' => $offer->offerState->slug === 'pending',
                'text-green-400' => $offer->offerState->slug === 'active',
                'text-orange-400' => $offer->offerState->slug === 'paused',
                'text-mauve-400' => $offer->offerState->slug === 'expired',
                'text-red-400' => $offer->offerState->slug === 'cancelled',
            ])>
              {{ $offer->offerState->name }}
            </span>
          </td>
          <td>
            <div class="relative flex justify-end">
              <x-popups.contentWcheck iid="choffer-{{ $offer->id }}" labelClass="hover:bg-slate-900"
                class="right-12 -top-1/4">
                <x-slot:label>
                  <x-icons.threeDotsX class="size-6" />
                </x-slot:label>

                <ul class="w-max py-2 bg-slate-800 text-slate-300 border border-slate-700 rounded-md font-semibold">
                  <li>
                    <button type="button" wire:click="showOfferModal({{ $offer->id }})"
                      class="relative w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors">
                      <x-icons.show class="size-5" />
                      Ver
                      <span wire:loading wire:target="showOfferModal({{ $offer->id }})" wire:loading.flex
                        class="absolute w-full h-full top-0 left-0 bg-slate-700 opacity-60 z-10 items-center justify-center">
                        <x-icons.animate.spinner class="size-4" />
                      </span>
                    </button>
                  </li>
                  @can('manage_offers')
                    <li>
                      <button type="button" wire:click="editOfferModal({{ $offer->id }})"
                        class="relative w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors">
                        <x-icons.edit class="size-5" />
                        Editar
                        <span wire:loading wire:target="editOfferModal({{ $offer->id }})" wire:loading.flex
                          class="absolute w-full h-full top-0 left-0 bg-slate-700 opacity-60 z-10 items-center justify-center">
                          <x-icons.animate.spinner class="size-4" />
                        </span>
                      </button>
                    </li>
                    <li>
                      <button type="button"
                        class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors"
                        @click="$dispatch('open-delete', { id: {{ $offer->id }}, name: '{{ $offer->name ?? $offer->offerTemplate->name }}' })">
                        <x-icons.trash class="size-5" />
                        Eliminar
                        </span>
                      </button>
                    </li>
                  @endcan
                </ul>
              </x-popups.contentWcheck>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="8" class="text-center font-semibold text-slate-300 col-span-full">No hay ofertas registrados
          </td>
        </tr>
      @endforelse
    </x-slot:tbody>
  </x-tables.table>

  {{ $this->offers->onEachSide(1)->links('pages.dashboard.partials.wirePagination') }}

  @can('manage_offers')
    {{-- MODAL DELETE --}}
    <dialog
      class="top-1/2 left-1/2 -translate-1/2 backdrop:bg-purple-900/35 backdrop:backdrop-blur-sm rounded-lg overflow-hidden max-w-md"
      x-ref="dialogD" x-effect="openD ? $refs.dialogD.showModal() : $refs.dialogD.close()" @click.self="openD = false"
      @keydown.escape.window="openD = false"
      @open-delete.window="
          openD = true;
          offerId = $event.detail.id;
          offerName = $event.detail.name;"
      x-data="{ openD: false, offerId: null, offerName: '' }" closedby="any">
      <div class="relative flex flex-col gap-4 p-4">
        <div class="flex flex-col items-center justify-center">
          <span class="my-6 text-red-700">
            <x-icons.warning class="size-28" />
          </span>
          <div class="px-3 mb-6 text-lg text-slate-700">
            <p class="text-center mb-2">
              ¿Está seguro de que quieres <b class="uppercase">Eliminar</b>
            </p>
            <p class="pe-2 text-xl text-center font-bold text-slate-800">
              Oferta: <span x-text="offerName"></span>?
            </p>
          </div>
        </div>
        <div class="flex justify-center gap-4 text-white md:gap-6">
          <button type="button" @click="openD = false"
            class="px-3 py-2 rounded-md bg-slate-700 hover:bg-slate-600 cursor-pointer">Cancelar</button>
          <button type="button" @click="openD = false; $wire.deleteOffer(offerId)"
            class="relative px-3 py-2 rounded-md bg-red-900 hover:bg-red-800 cursor-pointer overflow-hidden">
            Eliminar
          </button>
        </div>
      </div>
    </dialog>

    {{-- MODAL TEMPLATE --}}
    <div x-cloak x-show="openTemp" x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center bg-purple-950/35 "
      @click.self="openTemp = false" @keydown.escape.window="openTemp = false" @open-template.window="openTemp = true;"
      x-data="{
          openTemp: false,
          init() {
              Livewire.on('close-template-modal', () => { this.openTemp = false; });
          }
      }">
      <div
        class="w-full max-w-lg max-h-[90%] p-4 relative flex flex-col items-center gap-4 bg-white rounded-lg shadow-xl overflow-y-auto">
        <h2 class="mt-3 text-2xl text-center text-purple-900 font-semibold">
          Nueva Plantilla de Oferta
        </h2>
        <form wire:submit="saveOfferTemplate" class="group w-full flex flex-col gap-4 editable">
          <fieldset class="w-full py-3 grid grid-cols-[repeat(auto-fit,minmax(170px,1fr))] gap-6 text-gray-700 md:px-3">
            <div class="col-span-full pointer-events-none group-[.editable]:pointer-events-auto">
              <h4 class="mb-2 text-lg font-semibold">Tipo Base</h4>
              <select id="offerTemplateForm.offer_type_id"
                class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500"
                wire:model="offerTemplateForm.offer_type_id" required>
                <option class="text-center" value="" selected>--- Selecciona una plantilla ---</option>
                @foreach ($this->offerTypes as $offerType)
                  <option value="{{ $offerType->id }}">{{ $offerType->name }}</option>
                @endforeach
              </select>
              @error('offerTemplateForm.offer_type_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <div class="col-span-full pointer-events-none group-[.editable]:pointer-events-auto">
              <label class="block mb-2 font-semibold" for="offerTemplateForm.name">Nombre</label>
              <input id="offerTemplateForm.name" list="names" placeholder="Sugerencias de nombres"
                class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500"
                wire:model="offerTemplateForm.name" required>
              @error('offerTemplateForm.name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
              <datalist id="names">
                @foreach ($this->offerTemplates as $offerTemplate)
                  <option value="{{ $offerTemplate->name }}"></option>
                @endforeach
              </datalist>
            </div>
            <div class="pointer-events-none group-[.editable]:pointer-events-auto">
              <label class="block mb-2 font-semibold" for="offerTemplateForm.buy_qty">Cantidad de compra</label>
              <input type="number" id="offerTemplateForm.buy_qty" autocomplete="off" value="1"
                class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500"
                wire:model="offerTemplateForm.buy_qty" required>
              @error('offerTemplateForm.buy_qty')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <div class="pointer-events-none group-[.editable]:pointer-events-auto">
              <label class="block mb-2 font-semibold" for="offerTemplateForm.pay_qty">Cantidad de pago</label>
              <input type="number" id="offerTemplateForm.pay_qty" autocomplete="off" step="0.01"
                class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500"
                wire:model="offerTemplateForm.pay_qty" required>
              @error('offerTemplateForm.pay_qty')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <div class="col-span-full pointer-events-none group-[.editable]:pointer-events-auto">
              <label class="block mb-2 font-semibold" for="offerTemplateForm.description">Descripción</label>
              <input type="text" id="offerTemplateForm.description" autocomplete="off"
                placeholder="Descuento por..., etc."
                class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500"
                wire:model="offerTemplateForm.description">
              @error('offerTemplateForm.description')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
          </fieldset>
          <div class="w-full flex items-center justify-center gap-7">
            <button type="submit" wire:loading.attr="disabled" wire:target="saveOfferTemplate"
              class="px-3 py-2 hidden group-[.editable]:block bg-green-900 text-lg text-white rounded-md hover:bg-green-800 cursor-pointer">
              <span wire:loading.remove wire:target="saveOfferTemplate">
                Crear
              </span>
              <span wire:loading wire:target="saveOfferTemplate">
                Creando...
                <x-icons.animate.spinner class="size-5 inline" />
              </span>
            </button>
            <button type="button" @click="openTemp = false"
              class="px-3 py-2 hidden group-[.editable]:block bg-red-700 text-lg text-white rounded-md hover:bg-red-600 cursor-pointer">
              Cerrar</button>
          </div>
        </form>
      </div>
    </div>

    {{-- MODAL SHOW, EDIT, CREATE --}}
    <div x-cloak x-show="modalOpen" x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 flex items-center justify-center bg-purple-950/35 "
      @click.self="modalOpen = false; $wire.closeModal()"
      @keydown.escape.window="modalOpen = false; $wire.closeModal()">
      <div
        class="w-full max-w-lg max-h-[90%] p-4 relative flex flex-col items-center gap-4 bg-white rounded-lg shadow-xl overflow-y-auto">
        <h2 class="mt-3 text-2xl text-center text-purple-900 font-semibold">
          {{ $modalMode === 'edit' ? 'Editar Oferta' : ($modalMode === 'show' ? 'Ver Oferta' : 'Nueva Oferta') }}</h2>
        <form wire:submit="saveOffer" :class="{ 'editable': modalMode !== 'show' }"
          class="group w-full flex flex-col gap-4">
          <fieldset class="w-full py-3 grid grid-cols-[repeat(auto-fit,minmax(170px,1fr))] gap-6 text-gray-700 md:px-3">
            <div class="col-span-full pointer-events-none group-[.editable]:pointer-events-auto">
              <label class="block mb-2 font-semibold" for="name">Nombre</label>
              <input type="text" id="name" autocomplete="off"
                class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500"
                wire:model="offerForm.name" x-bind:readonly="modalMode === 'show'"
                x-bind:class="{ 'cursor-not-allowed opacity-70': modalMode === 'show' }" required>
              @error('name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <div class="pointer-events-none group-[.editable]:pointer-events-auto">
              <label class="block mb-2 font-semibold" for="start_date">Fecha de Inicio</label>
              <input type="datetime-local" id="start_date" autocomplete="off"
                class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500"
                wire:model="offerForm.start_date"x-bind:readonly="modalMode === 'show'"
                x-bind:class="{ 'cursor-not-allowed opacity-70': modalMode === 'show' }" required>
              @error('start_date')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <div class="pointer-events-none group-[.editable]:pointer-events-auto">
              <label class="block mb-2 font-semibold" for="end_date">Fecha de Fin</label>
              <input type="datetime-local" id="end_date" autocomplete="off"
                class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500"
                wire:model="offerForm.end_date" x-bind:readonly="modalMode === 'show'"
                x-bind:class="{ 'cursor-not-allowed opacity-70': modalMode === 'show' }" required>
              @error('end_date')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <div class="col-span-full pointer-events-none group-[.editable]:pointer-events-auto">
              <h4 class="mb-2 text-lg font-semibold">Plantilla de Oferta</h4>
              <div class="flex items-start gap-2">
                <div class="flex-1">
                  <select id="offer_template_id"
                    class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500"
                    wire:model="offerForm.offer_template_id" x-bind:disabled="modalMode === 'show'"
                    x-bind:class="{ 'cursor-not-allowed opacity-70': modalMode === 'show' }">
                    <option class="text-center" value="" selected>--- Selecciona una plantilla ---</option>
                    @foreach ($this->offerTemplates as $offerTemplate)
                      <option value="{{ $offerTemplate->id }}">{{ $offerTemplate->name }}</option>
                    @endforeach
                  </select>
                  @error('offer_template_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                  @enderror
                </div>
                <button type="button" @click="$dispatch('open-template')"
                  class="shrink-0 size-10 flex items-center justify-center bg-purple-900 text-white rounded-md hover:bg-purple-800 transition"
                  x-bind:class="{ 'cursor-not-allowed opacity-70': modalMode === 'show', 'cursor-pointer': modalMode !== 'show' }"
                  title="Agregar nueva plantilla">
                  <x-icons.plus class="size-5" />
                </button>
              </div>
            </div>
            @if ($modalMode === 'create')
              <section
                class="col-span-full py-3 flex flex-wrap gap-x-10 gap-y-5 items-center justify-center pointer-events-none group-[.editable]:pointer-events-auto">
                <p class="w-max">¿Prefires activar la oferta ahora?</p>
                <x-inputs.checkSwitch class="bg-slate-200 checked:bg-green-700 cursor-pointer"
                  classLabel="z-10 bg-white border-slate-300 peer-checked/switch:border-green-700 cursor-pointer"
                  wire:model="is_active" x-bind:disabled="modalMode === 'show'"
                  x-bind:class="{ 'cursor-not-allowed opacity-70': modalMode === 'show' }" />
              </section>
            @else
              <section class="col-span-full">
                <h4 class="mb-2 text-lg font-semibold">Estado de la Oferta</h4>
                <select id="offer_state_id"
                  class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500"
                  wire:model="offerForm.offer_state_id"
                  x-bind:disabled="modalMode === 'show' || !{{ $this->isStateEditable ? 'true' : 'false' }}"
                  x-bind:class="{
                      'cursor-not-allowed opacity-70': modalMode === 'show' || !
                          {{ $this->isStateEditable ? 'true' : 'false' }}
                  }">
                  @foreach ($this->offerStates as $offerState)
                    <option value="{{ $offerState->id }}">{{ $offerState->name }}</option>
                  @endforeach
                </select>
              </section>
            @endif
          </fieldset>
          <div class="w-full flex items-center justify-center gap-7">
            @if ($modalMode !== 'show')
              <button type="submit"
                class="px-3 py-2 hidden group-[.editable]:block bg-green-900 text-lg text-white rounded-md hover:bg-green-800 cursor-pointer"
                wire:loading.attr="disabled">
                <span wire:loading.remove>
                  {{ $modalMode === 'create' ? 'Crear' : 'Actualizar' }}
                </span>
                <span wire:loading>
                  <x-icons.animate.spinner class="size-5 inline" />
                </span>
              </button>
            @endif
            <button type="button" @click="modalOpen = false; $wire.closeModal()"
              class="px-3 py-2 hidden group-[.editable]:block bg-red-700 text-lg text-white rounded-md hover:bg-red-600 cursor-pointer">
              Cerrar</button>
          </div>
        </form>
      </div>
    </div>
  @endcan
</div>
