<?php

use Livewire\Attributes\{Computed, Layout, On};
use Livewire\Component;
use Livewire\WithPagination;

use App\Livewire\Forms\{OfferForm, OfferTemplateForm};
use App\Models\{Offer, OfferState, OfferTemplate, OfferType, Product};
use App\Traits\HasSortableTable;

new #[Layout('layouts::dashboard')] class extends Component {
    use WithPagination;
    use HasSortableTable;

    public OfferTemplateForm $offerTemplateForm;
    public OfferForm $offerForm;

    // | --- Modal --- |
    public $is_active = false;
    public $modalMode = 'create';
    public $modalOpen = false;

    // | --- Ordenamiento --- |
    public string $sortBy = 'end_date';
    protected array $sorts = [
        'end_date' => ['column' => 'offers.end_date', 'label' => 'Fecha de fin', 'default' => 'desc'],
        'name' => ['column' => 'offers.name', 'label' => 'Nombre', 'default' => 'asc'],
        'start_date' => ['column' => 'offers.start_date', 'label' => 'Fecha de inicio', 'default' => 'desc'],
        'offer_template' => ['label' => 'Tipo', 'default' => 'asc'],
        'offer_state' => ['label' => 'Estado', 'default' => 'asc'],
    ];
    protected array $sortAliases = [
        'offer_template_id' => 'offer_template',
        'offer_state_id' => 'offer_state',
    ];

    #[Computed]
    public function offers()
    {
        $dir = strtolower($this->sortDir) === 'asc' ? 'asc' : 'desc';
        $field = $this->sortAliases[$this->sortBy] ?? $this->sortBy;

        if (!$this->isSortable($field)) {
            $field = 'end_date';
        }

        $query = Offer::query()
            ->with(['offerState:id,slug,name', 'offerTemplate:id,name'])
            ->select(['offers.id', 'offers.name', 'offers.start_date', 'offers.end_date', 'offers.offer_state_id', 'offers.offer_template_id']);

        match ($field) {
            'offer_template' => $query->leftJoin('offer_templates', 'offer_templates.id', '=', 'offers.offer_template_id')->orderBy('offer_templates.name', $dir),
            'offer_state' => $query->leftJoin('offer_states', 'offer_states.id', '=', 'offers.offer_state_id')->orderBy('offer_states.name', $dir),
            default => $query->orderBy($this->sortColumn($field), $dir),
        };

        return $query->orderBy('offers.id', $dir)->paginate(10);
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

    #[Computed]
    public function products()
    {
        // Crear
        if ($this->modalMode === 'create') {
            return Product::query()
                ->with('brand:id,name')
                ->whereDoesntHave('offers', fn($query) => $query->active())
                ->orderBy('name')
                ->get(['id', 'name', 'brand_id']);
        }
        // Editar o Ver
        $offerProductIds = $this->offerForm->product_ids ?? [];
        return Product::query()
            ->with('brand:id,name')
            ->when(empty($offerProductIds), fn($query) => $query->whereDoesntHave('offers', fn($qry) => $qry->active()), fn($query) => $query->where(fn($q) => $q->whereIn('id', $offerProductIds)->orWhereDoesntHave('offers', fn($qry) => $qry->active())))
            ->orderBy('name')
            ->get(['id', 'name', 'brand_id']);
    }

    // MODALS
    public function createOfferModal()
    {
        abort_unless(auth()->user()?->can('manage_offers'), 403);

        $this->offerForm->reset();
        $this->offerForm->product_ids = [];
        $this->is_active = false;
        $this->modalMode = 'create';
        $this->modalOpen = true;

        $this->dispatchProducts();
        $this->dispatchTemplates();
        $this->dispatch('offer-modal-updated');
    }

    public function editOfferModal(int $id)
    {
        abort_unless(auth()->user()?->can('manage_offers'), 403);

        $offer = Offer::with('products:id')->findOrFail($id);
        $this->offerForm->setOffer($offer);
        $this->modalMode = 'edit';
        $this->modalOpen = true;

        $this->dispatchProducts();
        $this->dispatchTemplates();
        $this->dispatch('offer-modal-updated');
    }

    public function showOfferModal(int $id)
    {
        $offer = Offer::with('products:id')->findOrFail($id);
        $this->offerForm->setOffer($offer);
        $this->modalMode = 'show';
        $this->modalOpen = true;

        $this->dispatchProducts();
        $this->dispatchTemplates();
        $this->dispatch('offer-modal-updated');
    }

    private function dispatchProducts()
    {
        $this->dispatch('products-updated', products: $this->products->map(fn($product) => ['value' => (string) $product->id, 'label' => $product->name])->values()->toArray());
    }

    private function dispatchTemplates(): void
    {
        $this->dispatch('templates-updated', templates: $this->offerTemplates->map(fn($template) => ['value' => (string) $template->id, 'label' => $template->name])->values()->toArray());
    }

    #[On('closeModal')]
    public function closeModal()
    {
        $this->modalOpen = false;
        $this->modalMode = 'create';
        $this->is_active = false;
        $this->offerForm->reset();
        $this->offerForm->product_ids = [];
    }

    // MODALS ACTIONS
    public function saveOffer()
    {
        abort_unless(auth()->user()?->can('manage_offers'), 403);

        $message = $this->offerForm->save($this->is_active);

        $this->dispatch('notification', message: $message, type: 'success');
        $this->modalOpen = false;
        $this->modalMode = 'create';
        $this->offerForm->reset();
        $this->offerForm->product_ids = [];
        $this->is_active = false;
        unset($this->offers, $this->products);
        $this->resetPage(); // Para refrescar la paginación
    }

    public function deleteOffer(int $id)
    {
        abort_unless(auth()->user()?->can('manage_offers'), 403);

        $offer = Offer::with(['offerState:id,slug,name', 'offerTemplate:id,name'])->findOrFail($id);
        $type = 'success';
        $offerName = $offer->name ?? ($offer->offerTemplate?->name ?? "ID {$offer->id}");

        if ($offer->offerState?->slug === 'pending') {
            $offer->delete();
            $message = "Oferta '{$offerName}' eliminada correctamente.";
        } else {
            $message = "Oferta '{$offerName}' no puede ser eliminada. Solo las pendientes se pueden eliminar.";
            $type = 'error';
        }

        $this->dispatch('notification', message: $message, type: $type);
        unset($this->offers);
        $this->resetPage(); // Para refrescar la paginación
    }

    public function saveOfferTemplate()
    {
        abort_unless(auth()->user()?->can('manage_offers'), 403);

        $templateName = $this->offerTemplateForm->name;

        $this->offerTemplateForm->store();

        unset($this->offerTemplates);
        $newTemplate = OfferTemplate::where('name', $templateName)->select('id')->first();

        if ($newTemplate) {
            $this->offerForm->offer_template_id = $newTemplate->id;
        }

        $this->dispatchTemplates();
        $this->dispatch('close-template-modal');
        $this->dispatch('notification', message: "Plantilla '{$templateName}' creada correctamente.", type: 'success');
    }

    public function getIsStateEditableProperty()
    {
        if ($this->modalMode !== 'edit' || !$this->offerForm->offer_state_id) {
            return false;
        }
        $state = $this->offerStates->firstWhere('id', (int) $this->offerForm->offer_state_id);
        return $state && !in_array($state->slug, ['expired', 'cancelled'], true);
    }
};
?>

<div x-data="{
    modalOpen: $wire.entangle('modalOpen'),
    modalMode: $wire.entangle('modalMode'),
    productsChoices: null,
    templateChoices: null,

    init() {
        this.$nextTick(() => {
            this.initChoices()

            this.syncChoices();
            this.updateChoicesMode(this.modalMode);
        })

        this.$watch('modalMode', (mode) => {
            this.$nextTick(() => {
                this.updateChoicesMode(mode);
            })
        })

        // Preparo el modal para actualizar los choices
        Livewire.on('offer-modal-updated', () => {
            this.$nextTick(() => {
                this.syncChoices();
            });
        })

        // Cambio los productos disponibles
        Livewire.on('products-updated', ({ products = [] } = {}) => {
            if (!this.productsChoices) {
                return;
            }

            this.productsChoices.clearChoices();
            this.productsChoices.setChoices(products, 'value', 'label', true);
            this.syncProductSelection();
        })

        Livewire.on('templates-updated', ({ templates = [] } = {}) => {
            if (!this.templateChoices) {
                return;
            }

            this.templateChoices.clearChoices();
            this.templateChoices.setChoices(templates, 'value', 'label', true);
            this.syncTemplateSelection();
        })
    },

    initChoices() {
        if (
            !this.productsChoices &&
            this.$refs.productsSelect
        ) {
            this.productsChoices = new Choices(this.$refs.productsSelect, {
                searchEnabled: true,
                searchPlaceholderValue: 'Buscar producto...',
                removeItemButton: true,
                shouldSort: false,
                itemSelectText: '',
                placeholder: true,
                placeholderValue: 'Selecciona uno o más productos',
                noResultsText: 'No se encontraron productos',
                noChoicesText: 'No hay productos disponibles',
                allowHTML: false,
            });

            this.$refs.productsSelect.addEventListener('change', (event) => {
                const values = Array.from(
                    event.target.selectedOptions
                ).map(option => option.value);

                this.$wire.set(
                    'offerForm.product_ids',
                    values
                );
            });
        }

        if (
            !this.templateChoices &&
            this.$refs.templateSelect
        ) {
            this.templateChoices = new Choices(this.$refs.templateSelect, {
                searchEnabled: true,
                searchPlaceholderValue: 'Buscar plantilla...',
                shouldSort: false,
                itemSelectText: '',
                placeholder: true,
                placeholderValue: 'Selecciona una plantilla',
                noResultsText: 'No se encontraron plantillas',
                noChoicesText: 'No hay plantillas disponibles',
                allowHTML: false,
            });

            this.$refs.templateSelect.addEventListener('change', (event) => {
                this.$wire.set(
                    'offerForm.offer_template_id',
                    event.target.value || null
                );
            });
        }
    },

    syncChoices() {
        if (!this.productsChoices || !this.templateChoices) {
            return;
        }

        this.syncProductSelection();
        this.syncTemplateSelection();
    },

    syncProductSelection() {
        if (!this.productsChoices) {
            return;
        }
        const productIds = this.$wire.offerForm.product_ids ?? [];

        this.productsChoices.removeActiveItems();

        if (productIds.length) {
            this.productsChoices.setChoiceByValue(
                productIds.map(String)
            );
        }
    },

    syncTemplateSelection() {
        if (!this.templateChoices) {
            return;
        }
        const templateId = this.$wire.offerForm.offer_template_id;

        this.templateChoices.removeActiveItems();

        if (templateId) {
            this.templateChoices.setChoiceByValue(String(templateId));
        }
    },

    updateChoicesMode(mode) {
        if (!this.productsChoices || !this.templateChoices) {
            return;
        }

        const readOnly = mode === 'show';
        if (readOnly) {
            this.productsChoices.disable();
            this.templateChoices.disable();
        } else {
            this.productsChoices.enable();
            this.templateChoices.enable();
        }
    },

    openModal(modal) {
        this.$nextTick(() => {
            this.$refs[modal].showModal()
        })
    },

    closeModal(modal) {
        this.$refs[modal].close()
    },
}">
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
  <x-tables.sort-bar sortStatus="{{ $this->sortStatus() }}" />
  <div class="relative">
    <x-tables.loading-overlay target="offers,sort,resetSort" />
    <table class="w-full text-sm border-separate border-spacing-y-1 [&_td]:py-4 [&_td]:px-3 [&_tr]:bg-slate-800">
      <thead class="text-slate-400">
        <tr class="text-left" wire:key="offers-sort-{{ $sortBy }}-{{ $sortDir }}">
          <th class="px-3 py-4">#</th>
          <x-tables.sort-th field="name" :sortBy="$sortBy" :sortDir="$sortDir">Nombre</x-tables.sort-th>
          <x-tables.sort-th field="offer_template" :sortBy="$sortBy" :sortDir="$sortDir"
            class="hidden lg:table-cell">Tipo</x-tables.sort-th>
          <x-tables.sort-th field="start_date" :sortBy="$sortBy" :sortDir="$sortDir">Fecha de Inicio</x-tables.sort-th>
          <x-tables.sort-th field="end_date" :sortBy="$sortBy" :sortDir="$sortDir">Fecha de Fin</x-tables.sort-th>
          <x-tables.sort-th field="offer_state" :sortBy="$sortBy" :sortDir="$sortDir">Estado</x-tables.sort-th>
          <th class="px-3 py-4 text-right">Opciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($this->offers as $index => $offer)
          <tr wire:key="offer-{{ $offer->id }}">
            <td>{{ ($this->offers->currentPage() - 1) * $this->offers->perPage() + $index + 1 }}</td>
            <td class="font-bold">{{ $offer->name }}</td>
            <td class="hidden lg:table-cell">{{ $offer->offerTemplate?->name ?? '—' }}</td>
            <td>{{ $offer->start_date?->format('d/m/Y') ?? '—' }}</td>
            <td>{{ $offer->end_date?->format('d/m/Y') ?? '—' }}</td>
            <td>
              <span @class([
                  "font-semibold before:content-['●'] before:me-px",
                  'text-amber-400' => $offer->offerState->slug === 'pending',
                  'text-green-400' => $offer->offerState->slug === 'active',
                  'text-orange-400' => $offer->offerState->slug === 'paused',
                  'text-zinc-400' => $offer->offerState->slug === 'expired',
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
                          @click="$dispatch('open-delete', { id: {{ $offer->id }}, name: @js($offer->name ?? $offer->offerTemplate?->name) })">
                          <x-icons.trash class="size-5" />
                          Eliminar
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
            <td colspan="7" class="text-center font-semibold text-slate-300 col-span-full">No hay ofertas registradas
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $this->offers->onEachSide(1)->links('pages.dashboard.partials.wirePagination') }}

  @can('manage_offers')
    {{-- MODAL DELETE --}}
    <dialog
      class="top-1/2 left-1/2 -translate-1/2 backdrop:bg-black/60 backdrop:backdrop-blur-sm rounded-lg overflow-hidden max-w-md"
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
      x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 "
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
      x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 flex items-center justify-center bg-black/60 "
      @click.self="modalOpen = false; $wire.closeModal()"
      @keydown.escape.window="modalOpen = false; $wire.closeModal()">
      <div
        class="w-full max-w-lg max-h-[90%] p-4 relative flex flex-col items-center gap-4 bg-white rounded-lg shadow-xl overflow-y-auto scrollbar-track-transparent scrollbar-thumb-slate-300/50">
        <h2 class="mt-3 text-2xl text-center text-purple-900 font-semibold">
          {{ $modalMode === 'edit' ? 'Editar Oferta' : ($modalMode === 'show' ? 'Ver Oferta' : 'Nueva Oferta') }}</h2>
        <form wire:submit="saveOffer" :class="{ 'editable': modalMode !== 'show' }"
          class="group w-full flex flex-col gap-4">
          <fieldset class="w-full py-3 grid grid-cols-[repeat(auto-fit,minmax(170px,1fr))] gap-6 text-gray-700 md:px-3">
            <div class="col-span-full pointer-events-none group-[.editable]:pointer-events-auto">
              <label class="block mb-2 font-semibold" for="name">Nombre</label>
              <input type="text" id="name" autocomplete="off"
                class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500"
                wire:model.blur="offerForm.name" x-bind:readonly="modalMode === 'show'"
                x-bind:class="{ 'cursor-not-allowed opacity-70': modalMode === 'show' }" required>
              @error('offerForm.name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <div class="pointer-events-none group-[.editable]:pointer-events-auto">
              <label class="block mb-2 font-semibold" for="start_date">Fecha de Inicio</label>
              <input type="datetime-local" id="start_date" autocomplete="off"
                class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500"
                wire:model.blur="offerForm.start_date"x-bind:readonly="modalMode === 'show'"
                x-bind:class="{ 'cursor-not-allowed opacity-70': modalMode === 'show' }" required>
              @error('offerForm.start_date')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <div class="pointer-events-none group-[.editable]:pointer-events-auto">
              <label class="block mb-2 font-semibold" for="end_date">Fecha de Fin</label>
              <input type="datetime-local" id="end_date" autocomplete="off"
                class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500"
                wire:model.blur="offerForm.end_date" x-bind:readonly="modalMode === 'show'"
                x-bind:class="{ 'cursor-not-allowed opacity-70': modalMode === 'show' }" required>
              @error('offerForm.end_date')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <div class="col-span-full pointer-events-none group-[.editable]:pointer-events-auto">
              <h4 class="mb-2 text-lg font-semibold">Plantilla de Oferta</h4>
              <div class="flex items-center gap-2">
                <div class="flex-1" wire:ignore>
                  <select id="offer_template_id"
                    class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500"
                    x-ref="templateSelect">
                    @foreach ($this->offerTemplates as $offerTemplate)
                      <option value="{{ $offerTemplate->id }}">{{ $offerTemplate->name }}</option>
                    @endforeach
                  </select>
                </div>
                <button type="button" @click="$dispatch('open-template')"
                  class="shrink-0 size-10 flex items-center justify-center bg-purple-900 text-white rounded-md hover:bg-purple-800 transition"
                  x-bind:disabled="modalMode === 'show'"
                  x-bind:class="{
                      'cursor-not-allowed opacity-70': modalMode === 'show',
                      'cursor-pointer': modalMode !== 'show'
                  }"
                  title="Agregar nueva plantilla">
                  <x-icons.plus class="size-5" />
                </button>
              </div>
              @error('offerForm.offer_template_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <section class="col-span-full">
              <h4 class="mb-2 text-lg font-semibold">Productos {{ $modalMode === 'create' ? 'añadidos' : 'aplicados' }}
              </h4>
              <div wire:ignore>
                <select id="products_offer" x-ref="productsSelect" multiple>
                  @foreach ($this->products as $product)
                    <option value="{{ $product->id }}">
                      {{ $product->brand->name . ' | ' . $product->name }}
                    </option>
                  @endforeach
                </select>
              </div>
              @error('offerForm.product_ids')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </section>
            @if ($modalMode === 'create')
              <section
                class="col-span-full py-3 flex flex-wrap gap-x-10 gap-y-5 items-center justify-center pointer-events-none group-[.editable]:pointer-events-auto">
                <p class="w-max">¿Prefieres activar la oferta ahora?</p>
                <x-inputs.checkSwitch class="bg-slate-200 checked:bg-green-700 cursor-pointer"
                  classLabel="bg-white border-slate-300 peer-checked/switch:border-green-700 cursor-pointer"
                  wire:model="is_active" x-bind:disabled="modalMode === 'show'"
                  x-bind:class="{ 'cursor-not-allowed opacity-70': modalMode === 'show' }" />
              </section>
            @else
              <section class="col-span-full">
                <h4 class="mb-2 text-lg font-semibold">Estado de la Oferta</h4>
                <select id="offer_state_id"
                  class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500"
                  wire:model.live="offerForm.offer_state_id"
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
                wire:target="saveOffer" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="saveOffer">
                  {{ $modalMode === 'create' ? 'Crear' : 'Actualizar' }}
                </span>
                <span wire:loading wire:target="saveOffer">
                  <x-icons.animate.spinner class="size-5 inline" />
                  Actualizando...
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

@push('scripts-dashboard')
  <script src="https://cdn.jsdelivr.net/npm/choices.js@11.1.0/public/assets/scripts/choices.min.js"></script>
@endPush

@push('styles-dashboard')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js@11.1.0/public/assets/styles/choices.min.css" />
  <style>
    .choices__inner {
      background-color: #fff !important;
      border-color: #e2e8f0 !important;
      border-radius: 0.375rem !important;
      min-height: 42px !important;
    }

    .choices__list--dropdown {
      z-index: 10 !important;
    }
  </style>
@endPush
