<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\{Computed, Layout, Url};

use App\Models\{Payment, PaymentState, PaymentProvider, User};
use App\Traits\HasSortableTable;
use Illuminate\Database\Eloquent\Builder;

new #[Layout('layouts::dashboard')] class extends Component {
    use WithPagination;
    use HasSortableTable;

    #[Url(history: true)]
    public bool $isMyPayments = false;

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

    // | --- Ordenamiento --- |
    #[Url(history: true)]
    public string $sortBy = 'paid_at';
    #[Url(history: true)]
    public string $sortDir = 'desc';
    protected array $sorts = [
        'paid_at' => ['column' => 'payments.paid_at', 'label' => 'Fecha', 'default' => 'desc'],
        'amount' => ['column' => 'payments.amount', 'label' => 'Monto', 'default' => 'desc'],
        'user' => ['label' => 'Usuario', 'default' => 'asc'],
        'payment_state' => ['label' => 'Estado', 'default' => 'asc'],
        'provider' => ['label' => 'M. Pago', 'default' => 'asc'],
    ];
    protected array $sortAliases = [
        'payment_state_id' => 'payment_state',
        'payment_provider_id' => 'provider',
    ];

    // | --- Modal cambio de estado --- |
    public bool $changeStatusModalOpen = false;
    public ?int $changingPaymentId = null;
    public ?int $changingPaymentStateId = null;
    public ?string $changingPaymentUser = null;
    public ?string $changingPaymentAmount = null;

    public function mount()
    {
        $this->isMyPayments = request()->routeIs('my.payments.index');
        $this->dateTo = now()->format('Y-m-d');
    }

    protected function afterSort(): void
    {
        unset($this->payments, $this->sortStatus);
        $this->resetPage();
    }

    // | --- Propiedades Computables --- |
    #[Computed]
    public function payments()
    {
        $dir = strtolower($this->sortDir) === 'asc' ? 'asc' : 'desc';
        $field = $this->sortAliases[$this->sortBy] ?? $this->sortBy;

        if (!$this->isSortable($field)) {
            $field = 'paid_at';
        }

        $query = $this->getPaymentsQuery()->select(['payments.id', 'payments.order_id', 'payments.paid_at', 'payments.amount', 'payments.nro_fee', 'payments.payment_state_id', 'payments.payment_provider_id']);

        match ($field) {
            'user' => $query->leftJoin('orders', 'orders.id', '=', 'payments.order_id')->leftJoin('users', 'users.id', '=', 'orders.user_id')->orderBy('users.name', $dir)->orderBy('users.surname', $dir),
            'payment_state' => $query->leftJoin('payment_states', 'payment_states.id', '=', 'payments.payment_state_id')->orderBy('payment_states.name', $dir),
            'provider' => $query->leftJoin('payment_providers', 'payment_providers.id', '=', 'payments.payment_provider_id')->orderBy('payment_providers.name', $dir),
            default => $query->orderBy($this->sortColumn($field), $dir),
        };

        return $query->orderBy('payments.id', $dir)->paginate(10);
    }

    #[Computed]
    public function availableUsers()
    {
        if ($this->isMyPayments) {
            return collect();
        }

        return User::query()
            ->whereHas('orders')
            ->select(['id', 'name', 'surname'])
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function availablePaymentState()
    {
        return PaymentState::query()
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

    // | --- Filtros --- |
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

    // | --- Query --- |
    protected function getPaymentsQuery(): Builder
    {
        return Payment::query()
            ->with(['order.user:id,name,surname', 'paymentState:id,slug,name', 'paymentProvider:id,name'])
            ->when($this->isMyPayments, fn(Builder $q) => $q->whereHas('order', fn($q2) => $q2->where('user_id', auth()->id())))
            ->when(!$this->isMyPayments && filled($this->users), fn(Builder $q) => $q->whereHas('order', fn($q2) => $q2->whereIn('user_id', $this->users)))
            ->when(filled($this->states), fn(Builder $q) => $q->whereIn('payment_state_id', $this->states))
            ->when(filled($this->paymentMethods), fn(Builder $q) => $q->whereIn('payment_provider_id', $this->paymentMethods))
            ->when(filled($this->dateFrom), fn(Builder $q) => $q->where('paid_at', '>=', $this->dateFrom))
            ->when(filled($this->dateTo), fn(Builder $q) => $q->where('paid_at', '<=', $this->dateTo))
            ->when($this->totalFrom !== null, fn(Builder $q) => $q->where('amount', '>=', $this->totalFrom))
            ->when($this->totalTo !== null, fn(Builder $q) => $q->where('amount', '<=', $this->totalTo));
    }

    // | --- Modals --- |
    public function openChangeStatusModal(int $id)
    {
        abort_unless(auth()->user()?->can('manage_state_types'), 403);

        $payment = Payment::with(['order.user:id,name,surname', 'paymentState:id,slug,name', 'paymentProvider:id,name'])->findOrFail($id);

        $this->changingPaymentId = $payment->id;
        $this->changingPaymentStateId = $payment->payment_state_id;
        $this->changingPaymentUser = $payment->order?->user?->fullName() ?? '—';
        $this->changingPaymentAmount = number_format($payment->amount, 2, ',', '.');

        $this->changeStatusModalOpen = true;
    }

    public function closeChangeStatusModal(): void
    {
        $this->changeStatusModalOpen = false;
        $this->changingPaymentId = null;
        $this->changingPaymentStateId = null;
        $this->changingPaymentUser = null;
        $this->changingPaymentAmount = null;
    }

    public function changePaymentState(): void
    {
        abort_unless(auth()->user()?->can('manage_state_types'), 403);

        if (!$this->changingPaymentId || !$this->changingPaymentStateId) {
            return;
        }

        $payment = Payment::findOrFail($this->changingPaymentId);
        $payment->update(['payment_state_id' => $this->changingPaymentStateId]);

        unset($this->payments);
        $this->dispatch('notification', message: "Estado del pago #{$this->changingPaymentId} actualizado.", type: 'success');

        $this->closeChangeStatusModal();
    }

    // | --- Exportar a Excel --- |
    public function export()
    {
        abort_unless(auth()->user()?->can('view_payments'), 403);

        $query = $this->getPaymentsQuery();

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

        return redirect()->route('payments.export', $filters);
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
        if (!element) return null
        if (element._choicesInstance) return element._choicesInstance
        const instance = new Choices(element, options)
        element._choicesInstance = instance
        if (values.length) instance.setChoiceByValue(values.map(String))
        return instance
    },

    initChoices() {
        if (this.$refs.usersSelect) {
            this.usersChoices = this.getOrCreateChoices(this.$refs.usersSelect, {
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
                this.filterUsers = Array.from(event.target.selectedOptions).map(o => o.value)
            })
        }

        if (this.$refs.statesSelect) {
            this.statesChoices = this.getOrCreateChoices(this.$refs.statesSelect, {
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
                this.filterStates = Array.from(event.target.selectedOptions).map(o => o.value)
            })
        }

        if (this.$refs.paymentMethodsSelect) {
            this.paymentMethodsChoices = this.getOrCreateChoices(this.$refs.paymentMethodsSelect, {
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
                this.filterPaymentMethods = Array.from(event.target.selectedOptions).map(o => o.value)
            })
        }
    },

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

    clearChoicesFilter() {
        this.filterUsers = []
        this.filterStates = []
        this.filterPaymentMethods = []

        this.usersChoices?.removeActiveItems()
        this.statesChoices?.removeActiveItems()
        this.paymentMethodsChoices?.removeActiveItems()

        this.$wire.call('clearFilters')
    },

    openModal(modal) {
        this.$nextTick(() => this.$refs[modal].showModal())
    },

    closeModal(modal) {
        this.$refs[modal].close()
    },
}">
  <x-sections.headerTitle class="flex justify-between items-center">
    <x-slot:textTitle>{{ $isMyPayments ? 'Mis Pagos' : 'Pagos' }}</x-slot:textTitle>
    <div class="flex flex-wrap items-center gap-4">
      <button type="button" @click="openModal('filterModal')"
        class="px-3 py-2.5 font-semibold text-white bg-blue-800 rounded-lg hover:bg-blue-700 cursor-pointer">
        Filtrar
      </button>

      @if (!$isMyPayments && auth()->user()->can('view_any_payments'))
        <x-buttons.export-to-excel />
      @endif
    </div>
  </x-sections.headerTitle>

  <x-tables.sort-bar :sortStatus="$this->sortStatus()" />

  <div class="relative">
    <x-tables.loading-overlay target="users,states,paymentMethods,dateFrom,dateTo,totalFrom,totalTo,sort,resetSort" />
    <table class="w-full text-sm border-separate border-spacing-y-1 [&_td]:py-4 [&_td]:px-3 [&_tr]:bg-slate-800">
      <thead class="text-slate-400">
        <tr class="text-left" wire:key="payments-sort-{{ $sortBy }}-{{ $sortDir }}">
          <th class="px-3 py-4">#</th>
          @if (!$isMyPayments)
            <x-tables.sort-th field="user" :sortBy="$sortBy" :sortDir="$sortDir">Usuario</x-tables.sort-th>
          @endif
          <x-tables.sort-th field="paid_at" :sortBy="$sortBy" :sortDir="$sortDir">Fecha</x-tables.sort-th>
          <x-tables.sort-th field="amount" :sortBy="$sortBy" :sortDir="$sortDir">Monto</x-tables.sort-th>
          <x-tables.sort-th field="provider" :sortBy="$sortBy" :sortDir="$sortDir" class="hidden lg:table-cell">M.
            Pago</x-tables.sort-th>
          <x-tables.sort-th field="payment_state" :sortBy="$sortBy" :sortDir="$sortDir"
            class="hidden md:table-cell">Estado</x-tables.sort-th>
          <th class="px-3 py-4 text-end">Opciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($this->payments as $index => $payment)
          <tr wire:key="payment-{{ $payment->id }}">
            <td>{{ ($this->payments->currentPage() - 1) * $this->payments->perPage() + $index + 1 }}</td>
            @if (!$isMyPayments)
              <td class="font-bold">{{ $payment->order?->user?->fullName() ?? '—' }}</td>
            @endif
            <td class="text-slate-300">{{ $payment->paid_at?->format('d/m/Y H:i') ?? '—' }}</td>
            <td>
              <span class="me-px font-semibold">$</span>
              {{ number_format($payment->amount, 2, ',', '.') }}
            </td>
            <td class="hidden text-slate-300 capitalize lg:table-cell">
              {{ $payment->paymentProvider?->name ?? 'Sin proveedor' }}
            </td>
            <td class="hidden md:table-cell">
              <span @class([
                  "font-semibold before:content-['●'] before:me-px",
                  'text-amber-400' => $payment->paymentState?->slug === 'pending',
                  'text-orange-400' => $payment->paymentState?->slug === 'rejected',
                  'text-taupe-400' => $payment->paymentState?->slug === 'expired',
                  'text-green-400' => $payment->paymentState?->slug === 'approved',
                  'text-purple-400' => $payment->paymentState?->slug === 'refunded',
                  'text-red-400' => $payment->paymentState?->slug === 'cancelled',
              ])>
                {{ $payment->paymentState?->name ?? 'Sin estado' }}
              </span>
            </td>
            <td>
              <div class="relative flex justify-end">
                <x-popups.contentWcheck iid="chpayment-{{ $payment->id }}" labelClass="hover:bg-slate-900"
                  class="right-12 -top-1/4">
                  <x-slot:label>
                    <x-icons.threeDotsX class="size-6" />
                  </x-slot:label>
                  <ul
                    class="w-max py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold">
                    @can('manage_state_types')
                      <li>
                        <button type="button" wire:click="openChangeStatusModal({{ $payment->id }})"
                          class="relative w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors">
                          <x-icons.edit class="size-5" />
                          Cambiar Estado
                          <span wire:loading wire:target="openChangeStatusModal({{ $payment->id }})" wire:loading.flex
                            class="absolute w-full h-full top-0 left-0 bg-slate-700 opacity-60 z-10 items-center justify-center">
                            <x-icons.animate.spinner class="size-4" />
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
            <td colspan="{{ $isMyPayments ? 6 : 7 }}" class="text-center font-semibold text-slate-300">No hay pagos
              registrados</td>
          </tr>
        @endforelse
      </tbody>
    </table>
    <div class="mt-1">
      {{ $this->payments->onEachSide(1)->links('pages.dashboard.partials.wirePagination') }}
    </div>
  </div>

  {{-- Modals --}}
  <dialog x-ref="filterModal" @click.self="closeModal('filterModal')"
    class="top-1/2 left-1/2 -translate-1/2 max-w-lg w-full max-h-[90vh] rounded-md p-0 backdrop:bg-black/60 backdrop:backdrop-blur-sm">
    <div class="max-h-[90vh] flex flex-col bg-white text-slate-900 rounded-lg">
      <div class="shrink-0 flex items-center justify-between px-5 py-4 border-b border-slate-200">
        <h2 class="mt-3 text-2xl text-center text-purple-900 font-semibold">Filtrar pagos</h2>
        <button type="button" @click="closeModal('filterModal')"
          class="text-slate-500 hover:text-slate-900 text-xl cursor-pointer">
          <x-icons.x />
        </button>
      </div>
      <div class="min-h-0 flex-1 p-5 space-y-5 overflow-y-auto [scrollbar-color:#62748e_transparent] scrollbar-thin">
        @if (!$isMyPayments)
          <div>
            <label for="filter_users" class="block text-sm font-medium mb-2">Usuarios</label>
            <div wire:ignore>
              <select id="filter_users" x-ref="usersSelect" multiple
                class="w-full min-h-32 border border-slate-300 rounded-md px-3 py-2 outline-none">
                @foreach ($this->availableUsers as $user)
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
              @foreach ($this->availablePaymentState as $state)
                <option value="{{ $state->id }}">{{ $state->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div>
          <label for="filter_payment_methods" class="block text-sm font-medium mb-2">Método de pago</label>
          <div wire:ignore>
            <select id="filter_payment_methods" x-ref="paymentMethodsSelect" multiple
              class="w-full min-h-24 border border-slate-300 rounded-md px-3 py-2 outline-none">
              @foreach ($this->paymentProviders as $method)
                <option value="{{ $method->id }}">{{ $method->name }}</option>
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
          class="px-3 py-2 bg-slate-600 text-white font-semibold rounded-md hover:bg-slate-500 cursor-pointer">Limpiar</button>
        <div class="flex gap-3">
          <button type="button" @click="closeModal('filterModal')"
            class="px-3 py-2 bg-red-800 text-white font-semibold rounded-md hover:bg-red-700 cursor-pointer">Cancelar</button>
          <button type="button" @click="applyChoicesFilter()"
            class="px-3 py-2 bg-green-700 text-white font-semibold rounded-md hover:bg-green-600 cursor-pointer">Aplicar
            filtros</button>
        </div>
      </div>
    </div>
  </dialog>

  @if (!$isMyPayments && auth()->user()->can('manage_state_types'))
    <div x-cloak x-show="changeStatusModalOpen" x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 flex items-center justify-center bg-black/60 "
      @click.self="changeStatusModalOpen = false; $wire.closeChangeStatusModal()"
      @keydown.escape.window="changeStatusModalOpen = false; $wire.closeChangeStatusModal()">
      <div
        class="w-full max-w-lg max-h-[90vh] p-4 relative flex flex-col items-center gap-4 bg-white rounded-lg shadow-xl overflow-y-auto scrollbar-track-transparent scrollbar-thumb-slate-300/50">
        <h2 class="mt-3 text-2xl text-center text-purple-900 font-semibold">Cambiar el estado del pago</h2>
        <div class="w-full flex flex-col gap-4 text-slate-900">
          <div class="grid grid-cols-[auto_1fr] gap-x-5 gap-y-3">
            <span>Pago:</span>
            <strong>#{{ $changingPaymentId }}</strong>
            <span>Usuario:</span>
            <strong>{{ $changingPaymentUser }}</strong>
            <span>Monto:</span>
            <strong>$ {{ $changingPaymentAmount }}</strong>
            <label for="select_states">Nuevo estado:</label>
            <select id="select_states" wire:model="changingPaymentStateId"
              class="px-3 py-2 rounded-md border border-slate-300">
              @foreach ($this->availablePaymentState as $state)
                <option value="{{ $state->id }}">{{ $state->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="shrink-0 pt-4 flex items-center justify-center gap-3 border-t border-slate-200">
            <button type="button" @click="changeStatusModalOpen = false; $wire.closeChangeStatusModal()"
              class="px-3 py-2 bg-red-800 text-white font-semibold rounded-md hover:bg-red-700 cursor-pointer">
              Cancelar
            </button>
            <button type="button" wire:click="changePaymentState" wire:loading.attr="disabled"
              wire:target="changePaymentState"
              class="px-3 py-2 bg-green-700 text-white font-semibold rounded-md hover:bg-green-600 cursor-pointer disabled:opacity-50">
              <span wire:loading.remove wire:target="changePaymentState">Actualizar</span>
              <span wire:loading wire:target="changePaymentState" wire:loading.inline-flex class="items-center gap-2">
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
