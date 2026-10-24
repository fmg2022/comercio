<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\{Computed, Layout, Url};

use App\Models\{Order, OrderState, PaymentProvider, User};
use Illuminate\Database\Eloquent\Builder;
use App\Traits\HasSortableTable;

new #[Layout('layouts::dashboard')] class extends Component {
    use WithPagination;
    use HasSortableTable;

    #[Url(history: true)]
    public bool $isMyOrders = false;

    // | --- Ordenamiento --- |
    #[Url(history: true)]
    public string $sortBy = 'date';
    protected array $sorts = [
        'date' => ['column' => 'orders.date', 'label' => 'Fecha', 'default' => 'desc'],
        'total' => ['column' => 'orders.total', 'label' => 'Total', 'default' => 'desc'],
        'user' => ['label' => 'Usuario', 'default' => 'asc'],
        'order_state' => ['column' => 'order_states.name', 'label' => 'Estado', 'default' => 'asc'],
        'payment' => ['column' => 'payments.method', 'label' => 'M. Pago', 'default' => 'asc'],
    ];
    protected array $sortAliases = [
        'user_id' => 'user',
        'order_state_id' => 'order_state',
    ];

    // | --- Propiedades Computables --- |
    #[Computed]
    public function orders()
    {
        $dir = strtolower($this->sortDir) === 'asc' ? 'asc' : 'desc';
        $field = $this->sortAliases[$this->sortBy] ?? $this->sortBy;

        if (!$this->isSortable($field)) {
            $field = 'date';
        }

        $query = $this->getOrdersQuery()->select(['orders.id', 'orders.user_id', 'orders.date', 'orders.total', 'orders.order_state_id']);

        match ($field) {
            'user' => $query->leftJoin('users', 'users.id', '=', 'orders.user_id')->orderBy('users.name', $dir)->orderBy('users.surname', $dir),
            'order_state' => $query->leftJoin('order_states', 'order_states.id', '=', 'orders.order_state_id')->orderBy('order_states.name', $dir),
            'payment' => $query->leftJoin('payments', 'payments.order_id', '=', 'orders.id')->leftJoin('payment_providers', 'payment_providers.id', '=', 'payments.payment_provider_id')->orderBy('payment_providers.name', $dir),
            default => $query->orderBy($this->sortColumn($field), $dir),
        };

        return $query->orderBy('orders.id', $dir)->paginate(10);
    }

    #[Computed]
    public function availableUsers()
    {
        if ($this->isMyOrders) {
            return collect();
        }

        return User::query()
            ->whereHas('orders')
            ->select(['id', 'name', 'surname'])
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function orderStates()
    {
        return OrderState::query()
            ->select(['id', 'slug', 'name'])
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function paymentProviders()
    {
        return PaymentProvider::query()
            ->where('active', true)
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();
    }

    public function mount()
    {
        $this->isMyOrders = request()->routeIs('my.orders.index');
        $this->dateTo = now()->format('Y-m-d');
    }

    // | --- Filtros --- |
    #[Url(history: true)]
    public array $users = [];
    #[Url(history: true)]
    public array $states = [];
    #[Url(history: true)]
    public array $paymentMethods = [];
    #[Url(history: true)]
    public ?string $dateFrom = null;
    #[Url(history: true)]
    public ?string $dateTo = null;
    #[Url(history: true)]
    public ?float $totalFrom = null;
    #[Url(history: true)]
    public ?float $totalTo = null;

    public function updated($propertyName): void
    {
        if (in_array($propertyName, ['users', 'states', 'paymentMethods', 'dateFrom', 'dateTo', 'totalFrom', 'totalTo'])) {
            $this->resetPage();
        }
    }
    public function applyFilters(): void
    {
        $this->resetPage();
    }
    public function clearFilters(): void
    {
        $this->reset(['users', 'states', 'paymentMethods', 'dateFrom', 'dateTo', 'totalFrom', 'totalTo']);
        $this->dateTo = now()->format('Y-m-d');
        $this->resetPage();
    }

    // | --- Modal cambio de estado --- |
    public bool $changeStatusModalOpen = false;
    public ?int $changingOrderId = null;
    public ?int $changingOrderStateId = null;
    public ?string $changingOrderUser = null;
    public ?string $changingOrderAmount = null;

    public function openChangeStatusModal(int $id): void
    {
        abort_unless(auth()->user()?->can('manage_state_types'), 403);

        $order = Order::with(['user:id,name,surname', 'orderState:id,slug,name'])->findOrFail($id);

        $this->changingOrderId = $order->id;
        $this->changingOrderStateId = $order->order_state_id;
        $this->changingOrderUser = $order->user?->fullName() ?? '—';
        $this->changingOrderAmount = number_format($order->total, 2, ',', '.');

        $this->changeStatusModalOpen = true;
    }
    public function closeChangeStatusModal(): void
    {
        $this->changeStatusModalOpen = false;
        $this->changingOrderId = null;
        $this->changingOrderStateId = null;
        $this->changingOrderUser = null;
        $this->changingOrderAmount = null;
    }
    public function changeOrderState(): void
    {
        abort_unless(auth()->user()?->can('manage_state_types'), 403);

        if (!$this->changingOrderId || !$this->changingOrderStateId) {
            return;
        }

        $order = Order::findOrFail($this->changingOrderId);
        $order->update(['order_state_id' => $this->changingOrderStateId]);

        unset($this->orders);
        $this->dispatch('notification', message: "Estado de la orden #{$this->changingOrderId} actualizado.", type: 'success');

        $this->closeChangeStatusModal();
    }

    // | --- Query --- |
    protected function getOrdersQuery(): Builder
    {
        return Order::query()
            ->with(['user:id,name,surname', 'orderState:id,slug,name', 'payment:id,order_id,payment_provider_id', 'payment.paymentProvider:id,name'])
            ->when($this->isMyOrders, fn($query) => $query->where('user_id', auth()->id()))
            ->when(!$this->isMyOrders && filled($this->users), fn($query) => $query->whereIn('user_id', $this->users))
            ->when(filled($this->states), fn($query) => $query->whereIn('order_state_id', $this->states))
            ->when(filled($this->paymentMethods), fn($query) => $query->whereHas('payment', fn($q) => $q->whereIn('payment_provider_id', $this->paymentMethods)))
            ->when(filled($this->dateFrom), fn($query) => $query->where('date', '>=', $this->dateFrom))
            ->when(filled($this->dateTo), fn($query) => $query->where('date', '<=', $this->dateTo))
            ->when($this->totalFrom !== null, fn($query) => $query->where('total', '>=', $this->totalFrom))
            ->when($this->totalTo !== null, fn($query) => $query->where('total', '<=', $this->totalTo));
    }

    // | --- Exportar a Excel--- |
    public function export()
    {
        $query = $this->getOrdersQuery();

        if (!$query->exists()) {
            $this->dispatch('notification', message: 'Sin resultados para exportar.', type: 'warning');
            return;
        }

        $filters = [
            'users' => $this->users,
            'states' => $this->states,
            'providers' => $this->paymentMethods,
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
            'total_from' => $this->totalFrom,
            'total_to' => $this->totalTo,
        ];

        return redirect()->route('orders.export', $filters);
    }
};
?>

<div x-data="{
    usersChoices: null,
    statesChoices: null,
    paymentMethodsChoices: null,

    filterUsers: @js($users),
    filterStates: @js($states),
    filterPaymentMethods: @js($paymentMethods),

    changeStatusModalOpen: $wire.entangle('changeStatusModalOpen'),

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

    /* Inicializar Choices */
    initChoices() {
        if (this.$refs.usersSelect) {
            this.usersChoices = this.getOrCreateChoices(
                this.$refs.usersSelect, {
                    searchEnabled: true,
                    searchPlaceholderValue: 'Buscar usuario...',
                    removeItemButton: true,
                    placeholder: true,
                    placeholderValue: 'Selecciona uno o más usuarios',
                    shouldSort: false,
                    itemSelectText: '',
                    noResultsText: 'No se encontraron usuarios',
                    noChoicesText: 'No hay usuarios disponibles',
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

        if (this.$refs.paymentMethodsSelect) {
            this.paymentMethodsChoices = this.getOrCreateChoices(
                this.$refs.paymentMethodsSelect, {
                    searchEnabled: true,
                    searchPlaceholderValue: 'Buscar método de pago...',
                    removeItemButton: true,
                    placeholder: true,
                    placeholderValue: 'Selecciona uno o más métodos de pago',
                    shouldSort: false,
                    itemSelectText: '',
                    noResultsText: 'No se encontraron métodos de pago',
                    noChoicesText: 'No hay métodos de pago disponibles',
                    allowHTML: false,
                }, this.filterPaymentMethods)

            this.$refs.paymentMethodsSelect.addEventListener('change', (event) => {
                this.filterPaymentMethods = Array.from(
                    event.target.selectedOptions).map(option => option.value);
            })
        }
    },

    /* Llamada a Livewire */
    applyChoicesFilter() {
        this.filterUsers = this.filterUsers.map(Number)
        this.filterStates = this.filterStates.map(Number)
        this.filterPaymentMethods = this.filterPaymentMethods.map(Number)

        this.$wire.set('users', this.filterUsers)
        this.$wire.set('states', this.filterStates)
        this.$wire.set('paymentMethods', this.filterPaymentMethods)

        this.$refs.filterModal.close()

        this.$wire.call('applyFilters')
    },

    /* Limpiar filtros */
    clearChoicesFilter() {
        this.filterUsers = []
        this.filterStates = []
        this.filterPaymentMethods = []

        this.usersChoices?.removeActiveItems()
        this.statesChoices?.removeActiveItems()
        this.paymentMethodsChoices?.removeActiveItems()

        this.$wire.call('clearFilters')
    },

    selectedOrder: {
        id: null,
        user: '',
        amount: '',
        status: ''
    },

    openModal(modal, order = null) {
        this.selectedOrder = {
            id: order?.id ?? null,
            user: order?.user ?? '',
            amount: order?.amount ?? '',
            status: order?.status ?? null,
        }

        this.$nextTick(() => {
            this.$refs[modal].showModal()
        })
    },

    closeModal(modal) {
        this.$refs[modal].close()
        this.selectedOrder = {
            id: null,
            user: '',
            amount: '',
            status: ''
        }
    },
}">
  <x-sections.headerTitle class="flex justify-between items-center">
    <x-slot:textTitle>{{ $isMyOrders ? 'Mis Órdenes' : 'Órdenes' }}</x-slot:textTitle>
    <div class="flex flex-wrap items-center gap-4">
      <button type="button" @click="openModal('filterModal')"
        class="px-3 py-2.5 font-semibold text-white bg-blue-800 rounded-lg hover:bg-blue-700 cursor-pointer">
        Filtrar
      </button>

      @if (!$isMyOrders && auth()->user()->can('update_orders'))
        <x-buttons.export-to-excel />
      @endif
    </div>
  </x-sections.headerTitle>

  <x-tables.sort-bar sortStatus="{{ $this->sortStatus() }}" />
  <div class="relative">
    <x-tables.loading-overlay target="users,states,paymentMethods,dateFrom,dateTo,totalFrom,totalTo,sort,resetSort" />
    <table class="w-full border-separate border-spacing-y-1 text-sm [&_td]:py-4 [&_td]:px-3 [&_tr]:bg-slate-800">
      <thead class="text-slate-400">
        <tr class="text-left" wire:key="orders-sort-{{ $sortBy }}-{{ $sortDir }}">
          <th class="px-3 py-4">#</th>
          @if (!$isMyOrders)
            <x-tables.sort-th field="user" :sortBy="$sortBy" :sortDir="$sortDir">Usuario</x-tables.sort-th>
          @endif
          <x-tables.sort-th field="date" :sortBy="$sortBy" :sortDir="$sortDir">Fecha</x-tables.sort-th>
          <x-tables.sort-th field="total" :sortBy="$sortBy" :sortDir="$sortDir">Total</x-tables.sort-th>
          <x-tables.sort-th field="payment" :sortBy="$sortBy" :sortDir="$sortDir" class="hidden lg:table-cell">M.
            Pago</x-tables.sort-th>
          <x-tables.sort-th field="order_state" :sortBy="$sortBy" :sortDir="$sortDir"
            class="hidden md:table-cell">Estado</x-tables.sort-th>
          <th class="px-3 py-4 text-end">Opciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($this->orders as $index => $order)
          <tr wire:key="order-{{ $order->id }}">
            <td>{{ ($this->orders->currentPage() - 1) * $this->orders->perPage() + $index + 1 }}</td>
            @if (!$isMyOrders)
              <td class="font-bold">{{ $order->user?->fullName() }}</td>
            @endif
            <td class="text-slate-300">{{ $order->date->format('d/m/Y H:i') }}</td>
            <td>
              <span class="me-px font-semibold">$</span>
              {{ number_format($order->total, 2, ',', '.') }}
            </td>
            <td class="hidden text-slate-300 capitalize lg:table-cell">
              {{ $order->payment?->paymentProvider?->name ?? 'Sin pago' }}
            </td>
            <td class="hidden md:table-cell">
              <span @class([
                  "font-semibold before:content-['●'] before:me-px",
                  'text-blue-400' => $order->orderState?->slug === 'pending',
                  'text-cyan-400' => $order->orderState?->slug === 'paid',
                  'text-taupe-400' => $order->orderState?->slug === 'ready_for_pickup',
                  'text-green-400' => $order->orderState?->slug === 'completed',
                  'text-purple-400' => $order->orderState?->slug === 'refunded',
                  'text-red-400' => $order->orderState?->slug === 'cancelled',
              ])>
                {{ $order->orderState?->name ?? 'Sin estado' }}
              </span>
            </td>
            <td>
              <div class="relative flex justify-end">
                <x-popups.contentWcheck iid="chorder-{{ $order->id }}" labelClass="hover:bg-slate-900"
                  class="right-12 -top-1/4">
                  <x-slot:label>
                    <x-icons.threeDotsX class="size-6" />
                  </x-slot:label>

                  <ul
                    class="w-max py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold">
                    @can('view_orders')
                      @php
                        $route = $isMyOrders ? 'my.orders.show' : 'orders.show';
                      @endphp
                      <li>
                        <a href="{{ route($route, $order->id) }}"
                          class="px-4 py-2.5 flex items-center gap-3 hover:bg-slate-700">
                          <span>
                            <x-icons.show class="size-5" />
                          </span>
                          Detalles
                        </a>
                      </li>
                    @endcan
                    @if (!$isMyOrders)
                      @can('manage_state_types')
                        <li>
                          <button type="button" wire:click="openChangeStatusModal({{ $order->id }})"
                            class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700">
                            <span>
                              <x-icons.edit class="size-5" />
                            </span>
                            Cambiar Estado
                          </button>
                        </li>
                      @endcan
                    @endif
                  </ul>
                </x-popups.contentWcheck>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="{{ $isMyOrders ? 6 : 7 }}" class="text-center font-semibold text-slate-300">No hay órdenes
              registradas</td>
          </tr>
        @endforelse
      </tbody>
    </table>
    <div class="mt-1">
      {{ $this->orders->onEachSide(1)->links('pages.dashboard.partials.wirePagination') }}
    </div>
  </div>

  {{-- Modals --}}
  <dialog x-ref="filterModal" @click.self="closeModal('filterModal')"
    class="top-1/2 left-1/2 -translate-1/2 max-w-lg w-full max-h-[90vh] rounded-md p-0 backdrop:bg-black/60 backdrop:backdrop-blur-sm">
    <div class="max-h-[90vh] flex flex-col bg-white text-slate-900 rounded-lg">
      <div class="shrink-0 flex items-center justify-between px-5 py-4 border-b border-slate-200">
        <h2 class="mt-3 text-2xl text-center text-purple-900 font-semibold"> Filtrar órdenes </h2>
        <button type="button" @click="closeModal('filterModal')"
          class="text-slate-500 hover:text-slate-900 text-xl cursor-pointer">
          <x-icons.x />
        </button>
      </div>
      <div class="min-h-0 flex-1 p-5 space-y-5 overflow-y-auto [scrollbar-color:#62748e_transparent] scrollbar-thin">
        @if (!$isMyOrders)
          <div>
            <label for="filter_users" class="block text-sm font-medium mb-2">Usuarios</label>
            <div wire:ignore>
              <select id="filter_users" x-ref="usersSelect" multiple
                class="w-full min-h-32 border border-slate-300 rounded-md px-3 py-2 outline-none">
                @foreach ($this->availableUsers() as $user)
                  <option value="{{ $user->id }}">{{ $user->fullName() }}</option>
                @endforeach
              </select>
            </div>
            <p class="text-xs text-slate-500 mt-1">Puedes seleccionar varios usuarios.</p>
          </div>
        @endif
        <div>
          <label for="filter_states" class="block text-sm font-medium mb-2">Estados</label>
          <div wire:ignore>
            <select id="filter_states" x-ref="statesSelect" multiple
              class="w-full min-h-28 border border-slate-300 rounded-md px-3 py-2 outline-none">
              @foreach ($this->orderStates() as $state)
                <option value="{{ $state->id }}"> {{ $state->name }} </option>
              @endforeach
            </select>
          </div>
        </div>
        <div>
          <label for="filter_payment_methods" class="block text-sm font-medium mb-2">Método de pago</label>
          <div wire:ignore>
            <select id="filter_payment_methods" x-ref="paymentMethodsSelect" multiple
              class="w-full min-h-24 border border-slate-300 rounded-md px-3 py-2 outline-none">
              @foreach ($this->paymentProviders() as $method)
                <option value="{{ $method->id }}"> {{ $method->name }} </option>
              @endforeach
            </select>
          </div>
        </div>
        <div>
          <h4 class="block text-sm font-medium mb-2">Fecha</h4>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label for="filter_date_from" class="text-xs text-slate-500">Desde</label>
              <input id="filter_date_from" type="date" wire:model="dateFrom"
                class="w-full px-3 py-2 border border-slate-300 rounded-md outline-none">
            </div>
            <div>
              <label for="filter_date_to" class="text-xs text-slate-500">Hasta</label>
              <input id="filter_date_to" type="date" wire:model="dateTo"
                class="w-full px-3 py-2 border border-slate-300 rounded-md outline-none">
            </div>
          </div>
        </div>
        <div>
          <h4 class="block text-sm font-medium mb-2">Monto total</h4>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label for="filter_total_from" class="text-xs text-slate-500">Desde</label>
              <input id="filter_total_from" type="number" min="0" step="0.01" wire:model="totalFrom"
                placeholder="0" class="w-full px-3 py-2 border border-slate-300 rounded-md outline-none">
            </div>
            <div>
              <label for="filter_total_to" class="text-xs text-slate-500">Hasta</label>
              <input id="filter_total_to" type="number" min="0" step="0.01" wire:model="totalTo"
                placeholder="1.000.000" class="w-full px-3 py-2 border border-slate-300 rounded-md outline-none">
            </div>
          </div>
        </div>
      </div>
      <div class="shrink-0 flex justify-between px-5 py-4 border-t border-slate-200">
        <button type="button" @click="clearChoicesFilter()"
          class="px-3 py-2 bg-slate-600 text-white font-semibold rounded-md hover:bg-slate-500 cursor-pointer"> Limpiar
        </button>
        <div class="flex gap-3">
          <button type="button" @click="closeModal('filterModal')"
            class="px-3 py-2 bg-red-800 text-white font-semibold rounded-md hover:bg-red-700 cursor-pointer"> Cancelar
          </button>
          <button type="button" @click="applyChoicesFilter()"
            class="px-3 py-2 bg-green-700 text-white font-semibold rounded-md hover:bg-green-600 cursor-pointer">
            Aplicar filtros
          </button>
        </div>
      </div>
    </div>
  </dialog>

  @if (!$isMyOrders && auth()->user()->can('manage_state_types'))
    <div x-cloak x-show="changeStatusModalOpen" x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 flex items-center justify-center bg-black/60 "
      @click.self="changeStatusModalOpen = false; $wire.closeChangeStatusModal()"
      @keydown.escape.window="changeStatusModalOpen = false; $wire.closeChangeStatusModal()">
      <div
        class="w-full max-w-lg max-h-[90vh] p-4 relative flex flex-col items-center gap-4 bg-white rounded-lg shadow-xl overflow-y-auto scrollbar-track-transparent scrollbar-thumb-slate-300/50">
        <h2 class="mt-3 text-2xl text-center text-purple-900 font-semibold">Cambiar el estado de la orden</h2>
        <div class="w-full flex flex-col gap-4 text-slate-900">
          <div class="grid grid-cols-[auto_1fr] gap-x-5 gap-y-3">
            <span>Orden:</span>
            <strong>#{{ $changingOrderId }}</strong>
            <span>Usuario:</span>
            <strong>{{ $changingOrderUser }}</strong>
            <span>Monto:</span>
            <strong>$ {{ $changingOrderAmount }}</strong>
            <label for="select_states">Nuevo estado:</label>
            <select id="select_states" wire:model="changingOrderStateId"
              class="px-3 py-2 rounded-md border border-slate-300">
              @foreach ($this->orderStates() as $state)
                <option value="{{ $state->id }}">{{ $state->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="shrink-0 pt-4 flex items-center justify-center gap-3 border-t border-slate-200">
            <button type="button" @click="changeStatusModalOpen = false; $wire.closeChangeStatusModal()"
              class="px-3 py-2 bg-red-800 text-white font-semibold rounded-md hover:bg-red-700 cursor-pointer">
              Cancelar
            </button>
            <button type="button" wire:click="changeOrderState" wire:loading.attr="disabled"
              wire:target="changeOrderState"
              class="px-3 py-2 bg-green-700 text-white font-semibold rounded-md hover:bg-green-600 cursor-pointer disabled:opacity-50">
              <span wire:loading.remove wire:target="changeOrderState">Actualizar</span>
              <span wire:loading wire:target="changeOrderState" class="inline-flex items-center gap-2">
                <x-icons.animate.spinner class="size-4" /> Actualizando...
              </span>
            </button>
          </div>
        </div>
      </div>
    </div>
  @endif
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
