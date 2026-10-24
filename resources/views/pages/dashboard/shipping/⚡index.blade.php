<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\{Computed, Layout, Url};

use App\Models\{Shipping, ShippingState, User};
use App\Traits\HasSortableTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

new #[Layout('layouts::dashboard')] class extends Component {
    use WithPagination;
    use HasSortableTable;

    // | --- Filtros --- |
    #[Url(history: true)]
    public array $users = [];
    #[Url(history: true)]
    public array $states = [];

    #[Url(history: true)]
    public ?string $dateFrom = null;
    #[Url(history: true)]
    public ?string $dateTo = null;
    #[Url(history: true)]
    public ?string $dateDeliveredFrom = null;
    #[Url(history: true)]
    public ?string $dateDeliveredTo = null;

    #[Url(history: true)]
    public bool $deliveredNull = false;

    // | --- Ordenamiento --- |
    #[Url(history: true)]
    public string $sortBy = 'estimated_delivery_date';
    public array $sorts = [
        'estimated_delivery_date' => ['column' => 'shippings.estimated_delivery_date', 'label' => 'Fecha Estimada', 'default' => 'desc'],
        'order_id' => ['column' => 'shippings.order_id', 'label' => '# Orden', 'default' => 'asc'],
        'cost' => ['column' => 'shippings.cost', 'label' => 'Costo', 'default' => 'desc'],
        'delivered_to' => ['column' => 'shippings.delivered_to', 'label' => 'Fecha Entrega', 'default' => 'desc'],
        'tracking_number' => ['column' => 'shippings.tracking_number', 'label' => 'Nº Seguimiento', 'default' => 'asc'],
        'address' => ['column' => 'addresses.street', 'label' => 'Dirección', 'default' => 'asc'],
        'shipping_state' => ['column' => 'shipping_states.name', 'label' => 'Estado', 'default' => 'asc'],
        'transport_user' => ['column' => 'users.surname', 'label' => 'Transportista', 'default' => 'asc'],
    ];
    public array $sortAliases = [
        'address_id' => 'address',
        'shipping_state_id' => 'shipping_state',
        'transport_user_id' => 'transport_user',
    ];

    // | --- Modal --- |
    public bool $modalOpen = false;
    public string $modalMode = 'show';
    public ?int $shippingId = null;
    public ?string $trackingNumber = null;
    public ?string $notes = null;
    public ?string $deliveredTo = null;
    public ?int $transportUserId = null;
    public ?int $shippingStateId = null;
    public ?string $estimatedDeliveryDate = null;
    public ?float $cost = null;
    public ?float $distanceKm = null;
    public ?int $shippingOrderId = null;
    public ?string $shippingAddress = null;
    public ?string $shippingRate = null;

    public function mount()
    {
        $this->dateTo = now()->format('Y-m-d');
    }

    protected function rules(): array
    {
        return [
            'transportUserId' => 'nullable|exists:users,id',
            'shippingStateId' => 'required|exists:shipping_states,id',
            'trackingNumber' => 'nullable|string|max:255',
            'deliveredTo' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
            'estimatedDeliveryDate' => 'nullable|date',
            'cost' => 'nullable|numeric|min:0',
            'distanceKm' => 'nullable|numeric|min:0',
        ];
    }

    protected function messages(): array
    {
        return [
            'shippingStateId.required' => 'Debes seleccionar un estado.',
            'shippingStateId.exists' => 'El estado seleccionado no existe.',
            'transportUserId.exists' => 'El transportista seleccionado no existe.',
            'trackingNumber.max' => 'El número de seguimiento no puede superar 255 caracteres.',
            'deliveredTo.date' => 'La fecha de entrega no es válida.',
            'notes.max' => 'Las notas no pueden superar 1000 caracteres.',
            'estimatedDeliveryDate.date' => 'La fecha estimada no es válida.',
        ];
    }

    // | --- Propiedades Computables --- |
    #[Computed]
    public function shippings()
    {
        $dir = strtolower($this->sortDir) === 'asc' ? 'asc' : 'desc';
        $field = $this->sortAliases[$this->sortBy] ?? $this->sortBy;

        if (!$this->isSortable($field)) {
            $field = 'estimated_delivery_date';
        }

        $query = $this->getShippingsQuery()->select(['shippings.id', 'shippings.order_id', 'shippings.address_id', 'shippings.transport_user_id', 'shippings.shipping_state_id', 'shippings.cost', 'shippings.estimated_delivery_date', 'shippings.delivered_to', 'shippings.tracking_number', 'shippings.distance_km', 'shippings.notes']);

        match ($field) {
            'address' => $query->leftJoin('addresses', 'addresses.id', '=', 'shippings.address_id')->orderBy('addresses.street', $dir),
            'shipping_state' => $query->leftJoin('shipping_states', 'shipping_states.id', '=', 'shippings.shipping_state_id')->orderBy('shipping_states.name', $dir),
            'transport_user' => $query->leftJoin('users', 'users.id', '=', 'shippings.transport_user_id')->orderBy('users.surname', $dir),
            default => $query->orderBy($this->sortColumn($field), $dir),
        };

        return $query->orderBy('shippings.id', $dir)->paginate(10);
    }

    #[Computed]
    public function availableUsers(): Collection
    {
        return User::role('logistics')->get(['id', 'name', 'surname']);
    }

    #[Computed]
    public function shippingStates(): Collection
    {
        return ShippingState::query()->select('id', 'name')->orderBy('name')->get();
    }

    // | --- Filtros --- |
    public function applyFilters(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['users', 'states', 'dateFrom', 'dateTo', 'dateDeliveredFrom', 'dateDeliveredTo', 'deliveredNull']);
        $this->dateTo = now()->format('Y-m-d');
        $this->resetPage();
    }

    // | --- Modals --- |
    public function showShippingModal(int $id)
    {
        abort_unless(auth()->user()?->can('view_shippings'), 403);

        $shipping = Shipping::with(['shippingState:id,slug,name', 'address:id,street', 'transportUser:id,name,surname'])->findOrFail($id);

        $this->fillShippingModal($shipping, 'show');
    }

    public function editShippingModal(int $id)
    {
        abort_unless(auth()->user()?->can('update_shippings'), 403);

        $shipping = Shipping::with(['shippingState:id,slug,name', 'address:id,street', 'transportUser:id,name,surname'])->findOrFail($id);

        $this->fillShippingModal($shipping, 'edit');
    }

    private function fillShippingModal(Shipping $shipping, string $mode): void
    {
        $this->shippingId = $shipping->id;
        $this->transportUserId = $shipping->transportUser?->id;
        $this->shippingStateId = $shipping->shipping_state_id;
        $this->trackingNumber = $shipping->tracking_number;
        $this->deliveredTo = $shipping->delivered_to?->format('Y-m-d\TH:i');
        $this->estimatedDeliveryDate = $shipping->estimated_delivery_date?->format('Y-m-d\TH:i');
        $this->cost = $shipping->cost;
        $this->distanceKm = $shipping->distance_km;
        $this->notes = $shipping->notes;
        $this->shippingOrderId = $shipping->order_id;
        $this->shippingAddress = $shipping->address?->street;
        $this->shippingRate = $shipping->shipping_rate_id;

        $this->modalMode = $mode;
        $this->modalOpen = true;

        $this->dispatch('shipping-modal-updated');
    }

    public function saveShipping()
    {
        abort_unless(auth()->user()?->can('update_shippings'), 403);

        $this->validate();

        if (!$this->shippingId) {
            return;
        }

        $shipping = Shipping::find($this->shippingId);

        if ($this->transportUserId !== null) {
            $user = User::find($this->transportUserId);
            if (!$user || !$user->hasRole('logistics')) {
                $this->addError('transportUserId', 'El usuario seleccionado no tiene rol de Logística.');
                return;
            }
        }

        $shipping->update([
            'transport_user_id' => $this->transportUserId,
            'shipping_state_id' => $this->shippingStateId,
            'tracking_number' => $this->trackingNumber,
            'delivered_to' => filled($this->deliveredTo) ? $this->deliveredTo : null,
            'estimated_delivery_date' => filled($this->estimatedDeliveryDate) ? $this->estimatedDeliveryDate : null,
            'notes' => $this->notes,
        ]);

        if ($this->shippingStateId && $shipping->shippingState?->slug === 'delivered' && !filled($this->deliveredTo)) {
            $shipping->update(['delivered_to' => now()]);
        }

        $this->dispatch('notification', message: "Envío #{$this->shippingId} actualizado correctamente.", type: 'success');
        $this->closeModal();
        unset($this->shippings);
        $this->resetPage();
    }

    #[On('closeModal')]
    public function closeModal()
    {
        $this->modalOpen = false;
        $this->modalMode = 'show';
        $this->shippingId = null;
        $this->trackingNumber = null;
        $this->notes = null;
        $this->deliveredTo = null;
        $this->transportUserId = null;
        $this->shippingStateId = null;
        $this->estimatedDeliveryDate = null;
        $this->cost = null;
        $this->distanceKm = null;
        $this->shippingOrderId = null;
        $this->shippingAddress = null;
        $this->shippingRate = null;
    }

    // | --- Exportar a Excel --- |
    public function export()
    {
        abort_unless(auth()->user()?->can('update_shippings'), 403);

        $query = $this->getShippingsQuery();

        if (!$query->exists()) {
            $this->dispatch('notification', message: 'No es posible exportar datos, no hay registros.', type: 'warning');
            return;
        }

        $filters = [
            'users' => $this->users,
            'states' => $this->states,
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
            'date_delivered_from' => $this->dateDeliveredFrom,
            'date_delivered_to' => $this->dateDeliveredTo,
            'delivered_null' => $this->deliveredNull,
        ];

        return redirect()->route('shippings.export', $filters);
    }

    // | --- Query --- |
    protected function getShippingsQuery(): Builder
    {
        $userIds = array_values(array_filter($this->users, fn($v) => $v !== null && $v !== ''));
        $includeNullUser = in_array(null, $this->users, true) || in_array('', $this->users, true);

        $query = Shipping::query()->with(['shippingState:id,name,slug', 'address:id,street', 'transportUser:id,name,surname']);

        if ($includeNullUser || filled($userIds)) {
            if ($includeNullUser && filled($userIds)) {
                $query->where(function (Builder $q) use ($userIds) {
                    $q->whereIn('transport_user_id', $userIds)->orWhereNull('transport_user_id');
                });
            } elseif ($includeNullUser) {
                $query->whereNull('transport_user_id');
            } else {
                $query->whereIn('transport_user_id', $userIds);
            }
        }

        return $query
            ->when(filled($this->states), fn(Builder $q) => $q->whereIn('shipping_state_id', $this->states))
            ->when(filled($this->dateFrom), fn(Builder $q) => $q->where('estimated_delivery_date', '>=', $this->dateFrom))
            ->when(filled($this->dateTo), fn(Builder $q) => $q->where('estimated_delivery_date', '<=', $this->dateTo))
            ->when($this->deliveredNull, fn(Builder $q) => $q->whereNull('delivered_to'))
            ->when(!$this->deliveredNull && filled($this->dateDeliveredFrom), fn(Builder $q) => $q->where('delivered_to', '>=', $this->dateDeliveredFrom))
            ->when(!$this->deliveredNull && filled($this->dateDeliveredTo), fn(Builder $q) => $q->where('delivered_to', '<=', $this->dateDeliveredTo));
    }
};
?>

<div x-data="{
    modalOpen: $wire.entangle('modalOpen'),
    modalMode: $wire.entangle('modalMode'),
    usersChoices: null,
    statesChoices: null,
    filterUsers: @js($users),
    filterStates: @js($states),
    deliveredNull: $wire.entangle('deliveredNull'),

    init() {
        this.$nextTick(() => {
            this.initChoices()
        })
    },

    getOrCreateChoices(element, options, values = []) {
        if (!element) {
            return null
        }

        if (element._choicesInstance) {
            return element._choicesInstance
        }

        const instance = new Choices(element, options)

        element._choicesInstance = instance

        if (values.length) {
            instance.setChoiceByValue(values.map(String))
        }

        return instance
    },

    initChoices() {
        if (this.$refs.usersSelect) {
            this.usersChoices = this.getOrCreateChoices(
                this.$refs.usersSelect, {
                    searchEnabled: true,
                    searchPlaceholderValue: 'Buscar transportista...',
                    removeItemButton: true,
                    placeholder: true,
                    placeholderValue: 'Selecciona uno o más transportistas',
                    shouldSort: false,
                    itemSelectText: '',
                    noResultsText: 'No se encontraron transportistas',
                    noChoicesText: 'No hay transportistas disponibles',
                    allowHTML: false,
                }, this.filterUsers)

            this.$refs.usersSelect.addEventListener('change', (event) => {
                this.filterUsers = Array.from(
                    event.target.selectedOptions).map(option => option.value);
            })
        }

        if (this.$refs.statesSelect) {
            this.statesChoices = this.getOrCreateChoices(
                this.$refs.statesSelect, {
                    searchEnabled: true,
                    searchPlaceholderValue: 'Buscar estado...',
                    removeItemButton: true,
                    placeholder: true,
                    placeholderValue: 'Selecciona uno o más estados',
                    shouldSort: false,
                    itemSelectText: '',
                    noResultsText: 'No se encontraron estados',
                    noChoicesText: 'No hay estados disponibles',
                    allowHTML: false,
                }, this.filterStates)

            this.$refs.statesSelect.addEventListener('change', (event) => {
                this.filterStates = Array.from(
                    event.target.selectedOptions).map(option => option.value);
            })
        }
    },

    applyChoicesFilter() {
        this.filterUsers = this.filterUsers.map(v => (v === '' || v === null) ? null : Number(v))
        this.filterStates = this.filterStates.map(Number)

        this.$wire.set('users', this.filterUsers)
        this.$wire.set('states', this.filterStates)

        this.$refs.filterModal.close()

        this.$wire.call('applyFilters')
    },

    clearChoicesFilter() {
        this.filterUsers = []
        this.filterStates = []

        this.usersChoices?.removeActiveItems()
        this.statesChoices?.removeActiveItems()

        this.$wire.call('clearFilters')
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
    <x-slot:textTitle>Envíos</x-slot:textTitle>
    <div class="flex flex-wrap items-center gap-4">
      <button type="button" @click="openModal('filterModal')"
        class="px-3 py-2.5 font-semibold text-white bg-blue-800 rounded-lg hover:bg-blue-700 cursor-pointer">
        Filtrar
      </button>

      @can('update_shippings')
        <x-buttons.export-to-excel />
      @endcan
    </div>
  </x-sections.headerTitle>

  <x-tables.sort-bar sortStatus="{{ $this->sortStatus() }}" />
  <div class="relative">
    <x-tables.loading-overlay
      target="users,states,dateFrom,dateTo,dateDeliveredFrom,dateDeliveredTo,deliveredNull,sort,resetSort" />
    <table class="w-full text-sm border-separate border-spacing-y-1 [&_td]:py-4 [&_td]:px-3 [&_tr]:bg-slate-800">
      <thead class="text-slate-400">
        <tr class="text-left" wire:key="shippings-sort-{{ $sortBy }}-{{ $sortDir }}">
          <x-tables.sort-th field="order_id" :sortBy="$sortBy" :sortDir="$sortDir"># Orden</x-tables.sort-th>
          <x-tables.sort-th field="address" :sortBy="$sortBy" :sortDir="$sortDir">Dirección</x-tables.sort-th>
          <x-tables.sort-th field="transport_user" :sortBy="$sortBy" :sortDir="$sortDir">Transportista</x-tables.sort-th>
          <x-tables.sort-th field="estimated_delivery_date" :sortBy="$sortBy" :sortDir="$sortDir">
            Fecha Estimada
          </x-tables.sort-th>
          <x-tables.sort-th field="delivered_to" :sortBy="$sortBy" :sortDir="$sortDir" class="hidden md:table-cell">
            Fecha Entrega
          </x-tables.sort-th>
          <x-tables.sort-th field="tracking_number" :sortBy="$sortBy" :sortDir="$sortDir"
            class="hidden xl:table-cell">N° Seguimiento</x-tables.sort-th>
          <x-tables.sort-th field="shipping_state" :sortBy="$sortBy" :sortDir="$sortDir"
            class="hidden lg:table-cell">Estado</x-tables.sort-th>
          <th class="px-3 py-4 text-end">Opciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($this->shippings as $shipping)
          <tr wire:key="shipping-{{ $shipping->id }}">
            <td class="font-bold">
              <x-buttons.link href="{{ route('orders.show', $shipping->order_id) }}" class="text-purple-600 ">
                #{{ $shipping->order_id }}
              </x-buttons.link>
            </td>
            <td>{{ $shipping->address?->street ?? 'Sin dirección' }}</td>
            <td>{{ $shipping->transportUser ? $shipping->transportUser->fullName() : 'Sin Transportista' }}</td>
            <td class="text-slate-300">
              {{ $shipping->estimated_delivery_date ? $shipping->estimated_delivery_date->format('d/m/Y H:i') : 'Sin estimar' }}
            </td>
            <td class="hidden md:table-cell">
              {{ $shipping->delivered_to ? $shipping->delivered_to->format('d/m/Y H:i') : 'Sin entregar' }}
            </td>
            <td class="hidden xl:table-cell">{{ $shipping->tracking_number ?? 'Sin seguimiento' }}</td>
            <td class="hidden lg:table-cell">
              <span @class([
                  "font-semibold before:content-['●'] before:me-px",
                  'text-blue-400' => $shipping->shippingState?->slug === 'pending',
                  'text-cyan-400' => $shipping->shippingState?->slug === 'assigned',
                  'text-green-400' => $shipping->shippingState?->slug === 'delivered',
                  'text-zinc-400' => $shipping->shippingState?->slug === 'in_transit',
                  'text-orange-500' => $shipping->shippingState?->slug === 'failed',
                  'text-red-400' => $shipping->shippingState?->slug === 'cancelled',
              ])>
                {{ $shipping->shippingState?->name ?? 'Sin estado' }}
              </span>
            </td>
            <td>
              <div class="relative flex justify-end">
                <x-popups.contentWcheck iid="chshipping-{{ $shipping->id }}" labelClass="hover:bg-slate-900"
                  class="right-12 -top-1/4">
                  <x-slot:label>
                    <x-icons.threeDotsX class="size-6" />
                  </x-slot:label>

                  <ul
                    class="w-max py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold">
                    @can('view_shippings')
                      <li>
                        <button type="button" wire:click="showShippingModal({{ $shipping->id }})"
                          class="relative w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors">
                          <x-icons.show class="size-5" />
                          Detalles
                          <span wire:loading wire:target="showShippingModal({{ $shipping->id }})" wire:loading.flex
                            class="absolute w-full h-full top-0 left-0 bg-slate-700 opacity-60 z-10 items-center justify-center">
                            <x-icons.animate.spinner class="size-4" />
                          </span>
                        </button>
                      </li>
                    @endcan
                    @can('update_shippings')
                      <li>
                        <button type="button" wire:click="editShippingModal({{ $shipping->id }})"
                          class="relative w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors">
                          <x-icons.edit class="size-5" />
                          Editar
                          <span wire:loading wire:target="editShippingModal({{ $shipping->id }})" wire:loading.flex
                            class="absolute w-full h-full top-0 left-0 bg-slate-700 opacity-60 z-10 items-center justify-center">
                            <x-icons.animate.spinner class="size-4" />
                          </span>
                        </button>
                      </li>
                    @endcan
                    {{-- @can('manage_state_types')
                      <li>
                        <button type="button"
                          class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors">
                          <x-icons.edit class="size-5" />
                          Cambiar Estado
                        </button>
                      </li>
                    @endcan --}}
                  </ul>
                </x-popups.contentWcheck>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center font-semibold text-slate-300">No hay envíos registrados</td>
          </tr>
        @endforelse
      </tbody>
    </table>
    <div class="mt-1">
      {{ $this->shippings->onEachSide(1)->links('pages.dashboard.partials.wirePagination') }}
    </div>
  </div>

  {{-- Modals --}}
  <dialog x-ref="filterModal" @click.self="closeModal('filterModal')"
    class="top-1/2 left-1/2 -translate-1/2 max-w-lg w-full max-h-[90vh] rounded-md p-0 backdrop:bg-black/60 backdrop:backdrop-blur-sm">
    <div class="max-h-[90vh] flex flex-col bg-white text-slate-900 rounded-lg">
      <div class="shrink-0 flex items-center justify-between px-5 py-4 border-b border-slate-200">
        <h2 class="mt-3 text-2xl text-center text-purple-900 font-semibold"> Filtrar envíos </h2>
        <button type="button" @click="closeModal('filterModal')"
          class="text-slate-500 hover:text-slate-900 text-xl cursor-pointer">
          <x-icons.x />
        </button>
      </div>
      <div class="min-h-0 flex-1 p-5 space-y-5 overflow-y-auto [scrollbar-color:#62748e_transparent] scrollbar-thin">
        <div>
          <label for="filter_users" class="block text-sm font-medium mb-2">Transportistas</label>
          <div wire:ignore>
            <select id="filter_users" x-ref="usersSelect" multiple
              class="w-full min-h-32 border border-slate-300 rounded-md px-3 py-2 outline-none">
              <option value="">Sin Transportista</option>
              @foreach ($this->availableUsers as $user)
                <option value="{{ $user->id }}">{{ $user->fullName() }}</option>
              @endforeach
            </select>
          </div>
          <p class="text-xs text-slate-500 mt-1">Puedes seleccionar varios transportistas. Incluye "Sin Transportista"
            para ver envíos sin asignar.</p>
        </div>
        <div>
          <label for="filter_states" class="block text-sm font-medium mb-2">Estados</label>
          <div wire:ignore>
            <select id="filter_states" x-ref="statesSelect" multiple
              class="w-full min-h-28 border border-slate-300 rounded-md px-3 py-2 outline-none">
              @foreach ($this->shippingStates as $state)
                <option value="{{ $state->id }}"> {{ $state->name }} </option>
              @endforeach
            </select>
          </div>
        </div>
        <div>
          <h4 class="block text-sm font-medium mb-2">Fecha Estimada</h4>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label for="filter_date_from" class="text-xs text-slate-500">Desde</label>
              <input id="filter_date_from" type="datetime-local" wire:model="dateFrom"
                class="w-full px-3 py-2 border border-slate-300 rounded-md outline-none">
            </div>
            <div>
              <label for="filter_date_to" class="text-xs text-slate-500">Hasta</label>
              <input id="filter_date_to" type="datetime-local" wire:model="dateTo"
                class="w-full px-3 py-2 border border-slate-300 rounded-md outline-none">
            </div>
          </div>
        </div>
        <div>
          <h4 class="block text-sm font-medium mb-2">Fecha Entrega</h4>
          <div class="flex items-center gap-2 mb-2">
            <x-inputs.checkbox wire:model="deliveredNull" :labelClass="'font-semibold'" :labelFor="'filter_delivered_null1'">
              Sin entregar (sin fecha de entrega)
            </x-inputs.checkbox>
          </div>
          <div class="grid grid-cols-2 gap-4" x-show="!deliveredNull" x-cloak>
            <div>
              <label for="filter_date_delivered_from" class="text-xs text-slate-500">Desde</label>
              <input id="filter_date_delivered_from" type="datetime-local" wire:model="dateDeliveredFrom"
                class="w-full px-3 py-2 border border-slate-300 rounded-md outline-none">
            </div>
            <div>
              <label for="filter_date_delivered_to" class="text-xs text-slate-500">Hasta</label>
              <input id="filter_date_delivered_to" type="datetime-local" wire:model="dateDeliveredTo"
                class="w-full px-3 py-2 border border-slate-300 rounded-md outline-none">
            </div>
          </div>
        </div>
      </div>
      <div class="shrink-0 flex justify-between px-5 py-4 border-t border-slate-200">
        <button type="button" @click="clearChoicesFilter()"
          class="px-3 py-2 bg-slate-600 text-white font-semibold rounded-md hover:bg-slate-500 cursor-pointer">
          Limpiar
        </button>
        <div class="flex gap-3">
          <button type="button" @click="closeModal('filterModal')"
            class="px-3 py-2 bg-red-800 text-white font-semibold rounded-md hover:bg-red-700 cursor-pointer">
            Cancelar
          </button>
          <button type="button" @click="applyChoicesFilter()"
            class="px-3 py-2 bg-green-700 text-white font-semibold rounded-md hover:bg-green-600 cursor-pointer">
            Aplicar filtros
          </button>
        </div>
      </div>
    </div>
  </dialog>

  {{-- MODAL SHOW, EDIT --}}
  <div x-cloak x-show="modalOpen" x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 flex items-center justify-center bg-black/60 "
    @click.self="modalOpen = false; $wire.closeModal()"
    @keydown.escape.window="modalOpen = false; $wire.closeModal()">
    <div
      class="w-full max-w-2xl max-h-[90%] p-4 relative flex flex-col items-center gap-4 bg-white rounded-lg shadow-xl overflow-y-auto scrollbar-track-transparent scrollbar-thumb-slate-300/50">
      <h2 class="mt-3 text-2xl text-center text-purple-900 font-semibold">
        {{ $modalMode === 'show' ? 'Ver Envío' : 'Editar Envío' }} #{{ $shippingId ?? '' }}</h2>
      <form wire:submit="saveShipping" class="w-full flex flex-col gap-4 text-gray-700">
        <div wire:key="shipping-modal-body-{{ $shippingId ?? 'none' }}">
          <section class="w-full py-3 flex items-center justify-between gap-6 flex-wrap text-gray-700 md:px-3">
            <div>
              <h5 class="text-xs font-semibold text-gray-500 uppercase">Orden</h5>
              <span class="text-base font-bold">#{{ $shippingOrderId ?? '—' }}</span>
            </div>
            <div>
              <h5 class="text-xs font-semibold text-gray-500 uppercase">Dirección</h5>
              <span class="text-base">{{ $shippingAddress ?? 'Sin dirección' }}</span>
            </div>
            <div>
              <h5 class="text-xs font-semibold text-gray-500 uppercase">Costo</h5>
              <span class="text-base">${{ $cost ? number_format($cost, 2, ',', '.') : '—' }}</span>
            </div>
          </section>
          <section class="w-full py-3 flex items-center justify-start gap-8 flex-wrap text-gray-700 md:px-3">
            <div>
              <h5 class="text-xs font-semibold text-gray-500 uppercase">Distancia</h5>
              <span class="text-base">{{ $distanceKm ?? '—' }} km</span>
            </div>
            <div class="grow">
              <h5 class="text-xs font-semibold text-gray-500 uppercase">Tarifa aplicada</h5>
              <span class="text-base"># {{ $shippingRate ?? '—' }}</span>
            </div>
          </section>
          <fieldset
            class="w-full py-3 grid grid-cols-[repeat(auto-fit,minmax(170px,1fr))] gap-6 text-gray-700 md:px-3">
            <div class="col-span-full">
              <label class="block mb-2 font-semibold" for="shippingStateId">Estado</label>
              <select id="shippingStateId" wire:model="shippingStateId" @disabled($modalMode === 'show')
                class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500 disabled:cursor-not-allowed disabled:opacity-70">
                @foreach ($this->shippingStates as $state)
                  <option value="{{ $state->id }}">{{ $state->name }}</option>
                @endforeach
              </select>
              @error('shippingStateId')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <div class="col-span-full">
              <label class="block mb-2 font-semibold" for="transportUserId">Transportista</label>
              <select id="transportUserId" wire:model="transportUserId" @disabled($modalMode === 'show')
                class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500 disabled:cursor-not-allowed disabled:opacity-70">
                <option value="">Sin Transportista</option>
                @foreach ($this->availableUsers as $user)
                  <option value="{{ $user->id }}">{{ $user->fullName() }}</option>
                @endforeach
              </select>
              @error('transportUserId')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <div>
              <label class="block mb-2 font-semibold" for="estimatedDeliveryDate">Fecha Estimada</label>
              <input type="datetime-local" id="estimatedDeliveryDate" autocomplete="off"
                wire:model.blur="estimatedDeliveryDate" @readonly($modalMode === 'show')
                class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500 read-only:cursor-not-allowed read-only:opacity-70">
              @error('estimatedDeliveryDate')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <div>
              <label class="block mb-2 font-semibold" for="deliveredTo">Fecha Entrega</label>
              <input type="datetime-local" id="deliveredTo" autocomplete="off" wire:model.blur="deliveredTo"
                @readonly($modalMode === 'show')
                class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500 read-only:cursor-not-allowed read-only:opacity-70">
              @error('deliveredTo')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <div>
              <label class="block mb-2 font-semibold" for="trackingNumber">N° Seguimiento</label>
              <input type="text" id="trackingNumber" autocomplete="off" wire:model.blur="trackingNumber"
                @readonly($modalMode === 'show')
                class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500 read-only:cursor-not-allowed read-only:opacity-70">
              @error('trackingNumber')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <div class="col-span-full">
              <label class="block mb-2 font-semibold" for="notes">Notas</label>
              <textarea id="notes" rows="3" wire:model.blur="notes" @readonly($modalMode === 'show')
                class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500 read-only:cursor-not-allowed read-only:opacity-70"></textarea>
              @error('notes')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
          </fieldset>

          <div class="w-full flex items-center justify-center gap-7">
            @if ($modalMode === 'edit')
              <button type="submit"
                class="px-3 py-2 bg-green-900 text-lg text-white rounded-md hover:bg-green-800 cursor-pointer"
                wire:target="saveShipping" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="saveShipping">Actualizar</span>
                <span wire:loading wire:target="saveShipping">
                  <x-icons.animate.spinner class="size-5 inline" />
                  Actualizando...
                </span>
              </button>
            @endif
            <button type="button" @click="modalOpen = false; $wire.closeModal()"
              class="px-3 py-2 bg-red-700 text-lg text-white rounded-md hover:bg-red-600 cursor-pointer">
              Cerrar
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
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
