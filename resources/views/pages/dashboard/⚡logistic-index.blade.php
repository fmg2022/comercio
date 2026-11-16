<?php

use App\Models\{Shipping, ShippingState, User};
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\{Computed, Layout, Url, Validate};
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::dashboard')] class extends Component {
    use WithPagination;

    #[Url(history: true)]
    public string $period = 'today';
    #[Url(history: true)]
    public ?string $dateFrom = null;
    #[Url(history: true)]
    public ?string $dateTo = null;

    protected const TRANSITIONS = [
        'assigned' => ['in_transit'],
        'in_transit' => ['delivered', 'failed'],
    ];

    // Information
    public int $totalAssigned = 0,
        $totalDelivered = 0,
        $totalInTransit = 0,
        $totalFailed = 0,
        $totalOverdue = 0;

    // Modal
    public bool $modalOpen = false;
    public string $modalMode = 'show';

    public ?int $shippingId = null;
    public ?int $shippingOrderId = null;
    public ?int $shippingStateId = null;
    public ?string $shippingAddress = null;
    public ?string $shippingRate = null;
    public ?string $estimatedDeliveryDate = null;
    public ?string $deliveredTo = null;
    public ?string $trackingNumber = null;
    public ?string $notes = null;
    public float $cost = 0;
    public ?float $distanceKm = null;

    public function mount()
    {
        if ($this->dateFrom === null) {
            $this->dateFrom = now()->subMonth()->format('Y-m-d');
        }

        if ($this->dateTo === null) {
            $this->dateTo = now()->format('Y-m-d');
        }
        $this->loadData();
    }

    protected function rules(): array
    {
        return [
            'period' => ['required', Rule::in(['today', 'yesterday', 'week', 'month', 'year', 'all', 'custom'])],
            'dateFrom' => ['required_if:period,custom', 'nullable', 'date_format:Y-m-d'],
            'dateTo' => ['required_if:period,custom', 'nullable', 'date_format:Y-m-d', 'after_or_equal:dateFrom'],
        ];
    }
    protected function messages(): array
    {
        return [
            'period.in' => 'El periodo seleccionado no es válido.',
            'dateFrom.required' => 'La fecha inicial es obligatoria.',
            'dateFrom.date_format' => 'La fecha inicial no tiene un formato válido.',
            'dateTo.required' => 'La fecha final es obligatoria.',
            'dateTo.date_format' => 'La fecha final no tiene un formato válido.',
            'dateTo.after_or_equal' => 'La fecha final debe ser igual o posterior a la fecha inicial.',
        ];
    }
    protected function getDateRange(): ?array
    {
        return match ($this->period) {
            'today' => [today()->startOfDay(), today()->addDay()->startOfDay()],
            'yesterday' => [today()->subDay()->startOfDay(), today()->startOfDay()],
            'week' => [today()->startOfWeek(), today()->startOfWeek()->addWeek()],
            'custom' => $this->getCustomDateRange(),
            default => null,
        };
    }
    protected function getCustomDateRange(): ?array
    {
        if (!$this->dateFrom || !$this->dateTo || !Carbon::hasFormat($this->dateFrom, 'Y-m-d') || !Carbon::hasFormat($this->dateTo, 'Y-m-d')) {
            return null;
        }

        $from = Carbon::createFromFormat('Y-m-d', $this->dateFrom)->startOfDay();
        $to = Carbon::createFromFormat('Y-m-d', $this->dateTo)->startOfDay();

        if ($to->lt($from)) {
            return null;
        }

        return [$from, $to->copy()->addDay()];
    }
    protected function applyDateRange(Builder $query): Builder
    {
        $range = $this->getDateRange();
        if ($range === null) {
            return $query;
        }
        [$from, $until] = $range;
        return $query->where('estimated_delivery_date', '>=', $from)->where('estimated_delivery_date', '<', $until);
    }
    protected function resetDateFilters(): void
    {
        $this->period = 'today';
        $this->dateFrom = now()->subMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }
    protected function formatCount(int $value): string
    {
        if ($value === 0) {
            return '0';
        }

        return str_pad((string) $value, 2, '0', STR_PAD_LEFT);
    }

    #[Computed]
    public function myShippings()
    {
        $query = Shipping::query()
            ->with(['order.user:id,name,surname', 'address:id,street,intersection,locality', 'shippingState:id,slug,name'])
            ->where('transport_user_id', auth()->id())
            ->whereHas('shippingState', function (Builder $query) {
                $query->whereIn('slug', ['assigned', 'in_transit']);
            });
        $this->applyDateRange($query);

        return $query->orderBy('estimated_delivery_date')->paginate(10);
    }

    #[Computed]
    public function nextShipping(): ?Shipping
    {
        return Shipping::query()
            ->with(['order.user:id,name,surname', 'address:id,street,intersection,locality', 'shippingState:id,slug,name'])
            ->where('transport_user_id', auth()->id())
            ->whereNotNull('estimated_delivery_date')
            ->whereHas('shippingState', function (Builder $query) {
                $query->whereIn('slug', ['assigned', 'in_transit']);
            })
            ->orderBy('estimated_delivery_date')
            ->first();
    }

    #[Computed]
    public function availableShippingStates(): Collection
    {
        $shipping = Shipping::query()->with('shippingState')->whereKey($this->shippingId)->where('transport_user_id', auth()->id())->first();

        if (!$shipping) {
            return collect();
        }
        $allowedSlugs = self::TRANSITIONS[$shipping->shippingState->slug] ?? [];

        return ShippingState::query()
            ->whereIn('slug', $allowedSlugs)
            ->get(['id', 'name']);
    }

    public function refreshOverdueCount(): void
    {
        $this->totalOverdue = Shipping::query()
            ->where('transport_user_id', auth()->id())
            ->whereNotNull('estimated_delivery_date')
            ->where('estimated_delivery_date', '<', now())
            ->whereHas('shippingState', function (Builder $query) {
                $query->whereIn('slug', ['assigned', 'in_transit']);
            })
            ->count();
    }

    public function loadData(): void
    {
        $query = Shipping::query()->where('transport_user_id', auth()->id());
        $this->applyDateRange($query);
        $stats = $query
            ->join('shipping_states', 'shippings.shipping_state_id', '=', 'shipping_states.id')
            ->selectRaw(
                "
            SUM(CASE WHEN shipping_states.slug = 'assigned' THEN 1 ELSE 0 END) as total_assigned,
            SUM(CASE WHEN shipping_states.slug = 'delivered' THEN 1 ELSE 0 END) as total_delivered,
            SUM(CASE WHEN shipping_states.slug = 'in_transit' THEN 1 ELSE 0 END) as total_in_transit,
            SUM(CASE WHEN shipping_states.slug = 'failed' THEN 1 ELSE 0 END) as total_failed
        ",
            )
            ->first();

        $this->totalAssigned = (int) $stats->total_assigned;
        $this->totalDelivered = (int) $stats->total_delivered;
        $this->totalInTransit = (int) $stats->total_in_transit;
        $this->totalFailed = (int) $stats->total_failed;

        $this->refreshOverdueCount();
    }

    // Filtrado
    public function applyFilters(): void
    {
        $this->resetErrorBag();
        $this->validate();

        $range = $this->getDateRange();
        if ($this->period === 'custom' && $range === null) {
            $this->resetDateFilters();
            $this->dispatch('notification', message: 'El período o rango de fechas seleccionado no es válido.', type: 'warning');
            return;
        }

        $this->resetPage();
        unset($this->myShippings);
        $this->loadData();
        $this->dispatch('notification', message: 'Filtros aplicados correctamente.', type: 'success');
    }

    public function resetFilter()
    {
        $this->resetDateFilters();
        $this->resetPage();
        unset($this->myShippings);
        $this->loadData();
    }

    // Modales
    private function fillShippingModal(Shipping $shipping, string $mode): void
    {
        $this->shippingId = $shipping->id;
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
    public function saveShipping()
    {
        abort_unless(auth()->user()?->can('update_shippings'), 403);

        $this->validate([
            'shippingStateId' => ['required', 'integer', 'exists:shipping_states,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if (!$this->shippingId) {
            return;
        }

        DB::transaction(function () {
            $shipping = Shipping::query()->whereKey($this->shippingId)->where('transport_user_id', auth()->id())->lockForUpdate()->firstOrFail();
            $shipping->load('shippingState');

            $newState = ShippingState::findOrFail($this->shippingStateId);
            $currentSlug = $shipping->shippingState->slug;
            $newSlug = $newState->slug;

            if ($currentSlug !== $newSlug) {
                $allowed = self::TRANSITIONS[$currentSlug] ?? [];

                if (!in_array($newSlug, $allowed, true)) {
                    $this->dispatch('notification', message: 'No está permitido realizar esta transición de estado.', type: 'error');
                    return;
                }
            }

            if ($newSlug === 'failed' && blank($this->notes)) {
                $this->dispatch('notification', message: 'Debés indicar el motivo por el cual no se pudo entregar el envío.', type: 'warning');
                return;
            }

            $shipping->shipping_state_id = $newState->id;
            $shipping->notes = $this->notes;

            if ($newSlug === 'delivered' && !filled($this->deliveredTo)) {
                $shipping->delivered_to = now();
            }

            $shipping->save();
        });

        unset($this->myShippings, $this->nextShipping);
        $this->loadData();
        $this->resetPage();
        $this->closeModal();
        $this->dispatch('notify', type: 'success', message: 'Envío actualizado correctamente.');
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
        $this->shippingStateId = null;
        $this->estimatedDeliveryDate = null;
        $this->shippingOrderId = null;
        $this->shippingAddress = null;
        $this->shippingRate = null;
        $this->cost = 0;
        $this->distanceKm = null;
    }
};
?>

<div x-data="{
    modalOpen: $wire.entangle('modalOpen'),
    modalMode: $wire.entangle('modalMode'),

    openModal(modal) {
        this.$nextTick(() => {
            this.$refs[modal].showModal()
        })
    },

    closeModal(modal) {
        this.$refs[modal].close()
    },
}">
  <x-sections.headerTitle>
    <x-slot:textTitle>📊 Panel de Logística - {{ config('app.name') }}</x-slot:textTitle>
  </x-sections.headerTitle>
  {{-- Filtros --}}
  <div
    class="p-5 mb-7 flex items-center justify-between flex-wrap gap-8 bg-slate-800 rounded-lg shadow-md shadow-slate-500/60">
    <div class="flex items-center justify-start flex-wrap gap-6">
      <div class="relative">
        <label for="period" class="block mb-1 text-sm font-medium">Período</label>
        <select id="period" wire:model="period"
          class="w-36 px-2 py-1 border border-slate-500 rounded-md outline-none bg-slate-800 text-white" required>
          <option value="today">Hoy</option>
          <option value="yesterday">Ayer</option>
          <option value="week">Esta semana</option>
          <option value="custom">Personalizado</option>
        </select>
        <div wire:loading wire:target="period, applyFilters, resetFilter" wire:loading.flex value="{{ $dateFrom }}"
          class="absolute left-0 top-6 w-full h-8.5 items-center justify-center bg-slate-400/40 rounded-md">
          <x-icons.animate.spinner class="size-5 text-purple-500" />
        </div>
      </div>
      @if ($period === 'custom')
        <div class="relative">
          <label for="date_from" class="block mb-1 text-sm font-medium">Fecha desde:</label>
          <input type="date" id="date_from" wire:model="dateFrom"
            class="w-36 px-2 py-1 border border-slate-500 rounded-md outline-none" required>
          <div wire:loading wire:target="period,dateFrom, applyFilters, resetFilter" wire:loading.flex
            value="{{ $dateFrom }}"
            class="absolute left-0 top-6 w-full h-8.5 items-center justify-center bg-slate-400/40 rounded-md">
            <x-icons.animate.spinner class="size-5 text-purple-500" />
          </div>
        </div>
        <div class="relative">
          <label for="date_to" class="block mb-1 text-sm font-medium">Fecha hasta:</label>
          <input type="date" id="date_to" min="{{ $dateFrom }}" wire:model="dateTo"
            class="w-36 px-2 py-1 border border-slate-500 rounded-md outline-none" required>
          <div wire:loading wire:target="period,dateTo, applyFilters, resetFilter" wire:loading.flex
            value="{{ $dateTo }}"
            class="absolute left-0 top-6 w-full h-8.5 items-center justify-center bg-slate-400/40 rounded-md">
            <x-icons.animate.spinner class="size-5 text-purple-500" />
          </div>
        </div>
      @endif
      @foreach (['period', 'dateFrom', 'dateTo'] as $field)
        @error($field)
          <p class="p-4 text-sm font-semibold border-s-4 border-red-600 bg-red-800 rounded-e-md" x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition.duration.300ms>{{ $message }}</p>
        @enderror
      @endforeach
    </div>
    <div class="flex items-center justify-end gap-6">
      <button type="button" wire:click="applyFilters" wire:loading.attr="disabled"
        wire:target="period,applyFilters,resetFilter"
        class="relative px-3 py-2 font-semibold bg-slate-600 rounded-lg cursor-pointer hover:bg-slate-500">
        Aplicar filtros
        <div wire:loading wire:target="period, applyFilters, resetFilter" wire:loading.flex value="{{ $dateFrom }}"
          class="absolute left-0 top-0 w-full h-full items-center justify-center bg-slate-400/40 rounded-md">
          <x-icons.animate.spinner class="size-5 text-purple-500" />
        </div>
      </button>
      <button type="button" wire:click="resetFilter" wire:loading.attr="disabled"
        wire:target="period,applyFilters,resetFilter"
        class="relative px-3 py-2 font-semibold bg-slate-600 rounded-lg cursor-pointer hover:bg-slate-500">
        Resetear
        <div wire:loading wire:target="period, applyFilters, resetFilter" wire:loading.flex value="{{ $dateFrom }}"
          class="absolute left-0 top-0 w-full h-full items-center justify-center bg-slate-400/40 rounded-md pointer-events-none">
          <x-icons.animate.spinner class="size-5 text-purple-500" />
        </div>
      </button>
    </div>
  </div>
  {{-- Información de los envíos --}}
  <section class="mb-8 grid grid-cols-[repeat(auto-fit,minmax(200px,1fr))] gap-5">
    <div
      class="p-4 bg-slate-800 rounded-lg border-t-4 border-t-sky-700 shadow-md shadow-slate-600/80 transition duration-200">
      <p class="flex items-center justify-between gap-4 text-sm uppercase tracking-wider text-slate-200">
        Asignados
        <x-icons.product class="size-6 text-blue-400" />
      </p>
      <p class="my-2 text-3xl font-bold">{{ $this->formatCount($totalAssigned) }}</p>
      <p class="text-sm tracking-wider text-slate-400">Envíos asignados</p>
    </div>
    <div
      class="p-4 bg-slate-800 rounded-lg border-t-4 border-t-sky-700 shadow-md shadow-slate-600/80 transition duration-200">
      <p class="flex items-center justify-between gap-4 text-sm uppercase tracking-wider text-slate-200">
        En tránsito
        <x-icons.shippings class="size-6 text-blue-400" />
      </p>
      <p class="my-2 text-3xl font-bold">{{ $this->formatCount($totalInTransit) }}</p>
      <p class="text-sm tracking-wider text-slate-400">En proceso de entrega</p>
    </div>
    <div
      class="p-4 bg-slate-800 rounded-lg border-t-4 border-t-sky-700 shadow-md shadow-slate-600/80 transition duration-200">
      <p class="flex items-center justify-between gap-4 text-sm uppercase tracking-wider text-slate-200">
        Entregados
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
          class="text-green-400">
          <path fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="1.5"
            d="m7.687 9.687l2.66 1.426A3.5 3.5 0 0 0 12 11.53M7.687 9.687L3.884 7.65m3.803 2.038l8.496-4.555l.128-.07M3.884 7.648a3.5 3.5 0 0 0-.51 1.82v5.061a3.5 3.5 0 0 0 1.845 3.085l5.127 2.748A3.5 3.5 0 0 0 12 20.78M3.884 7.649a3.5 3.5 0 0 1 1.335-1.264l5.127-2.748a3.5 3.5 0 0 1 3.308 0L16.31 5.06M12 11.53a3.5 3.5 0 0 0 1.654-.416l6.462-3.464M12 11.529v9.25m0 0a3.5 3.5 0 0 0 1.654-.416l5.127-2.748a3.5 3.5 0 0 0 1.846-3.085V9.47a3.5 3.5 0 0 0-.511-1.821m0 0a3.5 3.5 0 0 0-1.335-1.264l-2.47-1.324" />
          <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="m14 15 1.5 1.5 2.6-3.6" />
        </svg>
      </p>
      <p class="my-2 text-3xl font-bold text-green-400">{{ $this->formatCount($totalDelivered) }}</p>
      <p class="text-sm tracking-wider text-slate-400">Entregas completadas</p>
    </div>
    <div
      class="p-4 bg-slate-800 rounded-lg border-t-4 border-t-sky-700 shadow-md shadow-slate-600/80 transition duration-200">
      <p class="flex items-center justify-between gap-4 text-sm uppercase tracking-wider text-slate-200">
        Con inconvenientes
        <x-icons.warning class="size-6 text-red-400" />
      </p>
      <p class="my-2 text-3xl font-bold text-red-400">{{ $this->formatCount($totalFailed) }}</p>
      <p class="text-sm tracking-wider text-slate-400">Requieren atención</p>
    </div>
  </section>
  @if ($totalOverdue > 0)
    <section
      class="p-4 mb-8 flex items-center gap-4 bg-slate-800 rounded-lg border-t-4 border-t-red-600 shadow-md shadow-slate-600/80 transition duration-200">
      <svg class="text-red-600" xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24">
        <path d="M0 0h24v24H0z" fill="none" />
        <path fill="currentColor"
          d="M18.524 7.37a8.06 8.06 0 0 0-3.366-2.722C9.385 2.166 3.136 7.047 4.096 13.239c.564 3.637 3.608 6.453 7.278 6.736a8 8 0 0 0 3.904-.676l.587-.293c.125-.07.585-.394.831-.466c.726-.212 1.431.463 1.252 1.197a1 1 0 0 1-.249.454c-.173.182-.75.499-.831.545a9.5 9.5 0 0 1-2.374.949c-4.079 1.017-8.299-.545-10.713-3.988C-.066 12.211 2.615 4.414 9.018 2.453c3.566-1.092 7.57-.117 10.164 2.589c.54.564 1.025 1.183 1.42 1.858c.053.089.376.624.434.863a1 1 0 0 1-.012.518c-.099.34-.379.607-.723.691a1 1 0 0 1-.517-.012c-.52-.152-.664-.644-.917-1.066Zm2.217 13.596a1 1 0 0 1-.607-.466c-.439-.76.29-1.69 1.127-1.466c.254.068.475.238.607.466c.439.76-.29 1.69-1.127 1.466m-.707-8.225c.226-.842 1.396-.996 1.832-.241c.101.175.121.302.134.5v4c-.03.451-.288.844-.741.966c-.656.176-1.217-.327-1.259-.966v-4ZM13 11.586l2.707 2.707c.359.409.404 1.01 0 1.414s-1.005.359-1.414 0l-3-3l-.159-.207c-.101-.175-.121-.302-.134-.5V7c.03-.451.288-.844.741-.966c.656-.176 1.217.327 1.259.966Z" />
      </svg>
      <div wire:poll.60s="refreshOverdueCount" class="">
        <p class="text-sm uppercase tracking-wider text-center text-slate-200">Envíos fuera de horario</p>
        <p class="my-2 text-3xl font-bold text-center text-red-400">
          {{ $this->formatCount($totalOverdue) }}
        </p>
      </div>
      <p class="grow text-sm text-slate-400">
        Cantidad de envíos cuya fecha estimada de entrega ya pasó y todavía no fueron entregados.
      </p>
      <p class="px-2 py-1 bg-red-500 font-semibold text-white text-center rounded-lg">Prioridad alta</p>
    </section>
  @endif

  <section class="mb-8">
    <h3 class="mb-3 text-xl font-semibold flex items-center gap-5">Próxima entrega</h3>
    <div
      class="w-full max-w-md mx-auto mb-8 p-4 bg-slate-800 rounded-lg md:max-w-xl shadow-md shadow-slate-600/80
           transition duration-200">
      @if ($this->nextShipping)
        @php($shipping = $this->nextShipping)
        <div class="flex flex-col gap-6">
          <div class="flex flex-col gap-3">
            <p class="text-lg font-bold whitespace-nowrap">Envío #{{ $shipping->id }}</p>
            <div class="flex flex-col gap-2">
              <div class="min-w-0">
                <h4 class="text-xs font-semibold text-slate-400 uppercase">
                  Cliente
                </h4>
                <p class="text-sm font-medium text-white">
                  {{ $shipping->order->user->fullName() }}
                </p>
              </div>
              <div class="min-w-0">
                <h4 class="text-xs font-semibold text-slate-400 uppercase">
                  Dirección
                </h4>
                <p class="text-sm text-slate-200">
                  {{ $shipping->address->shortAddress() }}
                </p>
              </div>
            </div>
          </div>
          <div class="flex flex-wrap items-center justify-between gap-x-4 gap-y-2">
            <p class="text-sm text-slate-300">
              Fecha estimada:
              <span class="font-medium text-white">
                {{ $shipping->estimated_delivery_date->format('d/m/Y H:i') }}
              </span>
            </p>
            <span class="px-1.5 py-0.5 text-sm font-semibold bg-cyan-600 rounded-lg before:content-['●'] before:me-px">
              {{ $shipping->shippingState->name }}
            </span>
          </div>
          <button type="button" wire:click="editShippingModal({{ $shipping->id }})"
            class="relative w-full sm:max-w-md sm:mx-auto shrink-0 px-4 py-2 bg-slate-600
                       rounded-lg hover:bg-slate-500 transition">
            Ver detalles
            <span wire:loading wire:target="editShippingModal({{ $shipping->id }})" wire:loading.flex
              class="absolute inset-0 bg-slate-700 opacity-60 z-10 items-center justify-center">
              <x-icons.animate.spinner class="size-4" />
            </span>
          </button>
        @else
          <p class="text-slate-400 text-sm">No tenés entregas pendientes.</p>
      @endif
    </div>
  </section>

  <section class="p-4 mb-7 bg-slate-800/60 shadow-md shadow-slate-500/50">
    <h3 class="mb-3 text-xl font-semibold">Mis próximos envíos</h3>
    <x-tables.table>
      <x-slot:thead>
        <tr class="text-left">
          <th>Envío N°</th>
          <th>Cliente</th>
          <th>Dirección</th>
          <th>Fecha estimada</th>
          <th>Estado</th>
          <th>Opcion</th>
        </tr>
      </x-slot:thead>
      <x-slot:tbody>
        @forelse ($this->myShippings as $shipping)
          <tr>
            <td class="sm:tracking-wide">#{{ $shipping->id }}</td>
            <td>{{ $shipping->order->user->fullName() }}</td>
            <td>{{ $shipping->address->shortAddress() }}</td>
            <td>{{ $shipping->estimated_delivery_date->format('d/m/Y H:i') }}</td>
            <td>
              <span @class([
                  "font-semibold before:content-['●'] before:me-px",
                  'text-cyan-400' => $shipping->shippingState?->slug === 'assigned',
                  'text-green-400' => $shipping->shippingState?->slug === 'delivered',
                  'text-zinc-400' => $shipping->shippingState?->slug === 'in_transit',
                  'text-orange-500' => $shipping->shippingState?->slug === 'failed',
              ])>
                {{ $shipping->shippingState->name ?? 'Sin estado' }}
            </td>
            <td>
              <div x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false"
                class="relative flex justify-end">
                <button type="button" @click="open = !open" :aria-expanded="open" aria-haspopup="true"
                  class="inline-flex items-center justify-center p-1.5 rounded-full cursor-pointer hover:bg-slate-900">
                  <x-icons.threeDotsX class="size-6" />
                </button>

                <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-150"
                  x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                  x-transition:leave="transition ease-in duration-100"
                  x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                  class="absolute z-50 right-12 -top-1/4"
                  @click="if ($event.target.closest('[data-close-menu]')) open = false">
                  <ul
                    class="w-max py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold">
                    @can('view_shippings')
                      <li>
                        <button type="button" wire:click="showShippingModal({{ $shipping->id }})" data-close-menu
                          class="relative w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors">
                          <x-icons.show class="size-5" />
                          Detalles
                          <span wire:loading wire:target="showShippingModal({{ $shipping->id }})" wire:loading.flex
                            class="absolute inset-0 bg-slate-700 opacity-60 z-10 items-center justify-center">
                            <x-icons.animate.spinner class="size-4" />
                          </span>
                        </button>
                      </li>
                    @endcan
                    @can('update_shippings')
                      <li>
                        <button type="button" wire:click="editShippingModal({{ $shipping->id }})" data-close-menu
                          class="relative w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors">
                          <x-icons.edit class="size-5" />
                          Editar
                          <span wire:loading wire:target="editShippingModal({{ $shipping->id }})" wire:loading.flex
                            class="absolute inset-0 bg-slate-700 opacity-60 z-10 items-center justify-center">
                            <x-icons.animate.spinner class="size-4" />
                          </span>
                        </button>
                      </li>
                    @endcan
                  </ul>
                </div>
              </div>
            </td>

            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center font-semibold text-slate-300">No hay envíos asignados</td>
          </tr>
        @endforelse
      </x-slot:tbody>
    </x-tables.table>
    <div class="mt-1">
      {{ $this->myShippings->onEachSide(1)->links('pages.dashboard.partials.wirePagination') }}
    </div>
  </section>

  {{-- MODAL SHOW/EDIT --}}
  <div x-cloak x-show="modalOpen" x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 flex items-center justify-center bg-black/60 p-3"
    @click.self="modalOpen = false; $wire.closeModal()"
    @keydown.escape.window="modalOpen = false; $wire.closeModal()">
    <div
      class="w-full max-w-2xl max-h-[90vh] p-5 relative flex flex-col gap-4 bg-white rounded-lg shadow-xl overflow-y-auto scrollbar-track-transparent scrollbar-thumb-slate-300/50"
      @click.stop>
      <div class="flex items-center justify-between gap-3">
        <h2 class="text-xl sm:text-2xl text-purple-900 font-semibold">
          {{ $modalMode === 'show' ? 'Detalle del envío' : 'Actualizar envío' }}
          #{{ $shippingId ?? '' }}
        </h2>
        <button type="button" @click="modalOpen = false; $wire.closeModal()"
          class="p-2 text-gray-500 hover:text-red-600 rounded-full">
          <x-icons.x class="size-5" />
        </button>
      </div>
      <form wire:submit="saveShipping" class="w-full flex flex-col gap-4 text-gray-700">
        <div wire:key="shipping-modal-body-{{ $shippingId ?? 'none' }}">
          <section class="w-full p-4 grid grid-cols-2 sm:grid-cols-3 gap-4 bg-slate-100 rounded-lg">
            <div>
              <h5 class="text-xs font-semibold text-gray-500 uppercase">Orden</h5>
              <span class="text-base font-bold">
                #{{ $shippingOrderId ?? '—' }}
              </span>
            </div>
            <div>
              <h5 class="text-xs font-semibold text-gray-500 uppercase">Costo</h5>
              <span class="text-base font-bold">
                ${{ number_format((float) ($cost ?? 0), 2, ',', '.') }}
              </span>
            </div>
            <div>
              <h5 class="text-xs font-semibold text-gray-500 uppercase">Distancia</h5>
              <span class="text-base">
                {{ $distanceKm ?? '—' }} km
              </span>
            </div>
            <div class="col-span-full">
              <h5 class="text-xs font-semibold text-gray-500 uppercase">Dirección de entrega</h5>
              <span class="text-base">
                {{ $shippingAddress ?? 'Sin dirección' }}
              </span>
            </div>
            <div>
              <h5 class="text-xs font-semibold text-gray-500 uppercase">Tarifa aplicada</h5>
              <span class="text-base">
                #{{ $shippingRate ?? '—' }}
              </span>
            </div>
            <div>
              <h5 class="text-xs font-semibold text-gray-500 uppercase">Fecha estimada</h5>
              <span class="text-base">
                {{ $estimatedDeliveryDate ? \Carbon\Carbon::parse($estimatedDeliveryDate)->format('d/m/Y H:i') : 'Sin fecha' }}
              </span>
            </div>
            <div>
              <h5 class="text-xs font-semibold text-gray-500 uppercase">Fecha de entrega</h5>
              <span class="text-base">
                {{ $deliveredTo ? \Carbon\Carbon::parse($deliveredTo)->format('d/m/Y H:i') : 'Pendiente' }}
              </span>
            </div>
            <div class="col-span-full">
              <h5 class="text-xs font-semibold text-gray-500 uppercase">Número de seguimiento</h5>
              <span class="text-base">
                {{ $trackingNumber ?? 'Sin número de seguimiento' }}
              </span>
            </div>
          </section>
          <fieldset class="w-full py-4 grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="col-span-full">
              <label for="shippingStateId" class="block mb-2 font-semibold">
                Estado del envío
              </label>
              <select id="shippingStateId" wire:model="shippingStateId" @disabled($modalMode === 'show')
                class="w-full px-3 py-2 text-gray-900 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500 disabled:bg-gray-100 disabled:cursor-not-allowed">
                <option value="">Seleccione una opción</option>
                @foreach ($this->availableShippingStates as $state)
                  <option value="{{ $state->id }}">
                    {{ $state->name }}
                  </option>
                @endforeach
              </select>

              @error('shippingStateId')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <div class="col-span-full">
              <label for="notes" class="block mb-2 font-semibold">
                Observaciones
              </label>
              <textarea id="notes" rows="3" wire:model.blur="notes" @readonly($modalMode === 'show')
                placeholder="Agregar observaciones sobre la entrega..."
                class="w-full px-3 py-2 text-gray-900 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500 read-only:bg-gray-100 read-only:cursor-not-allowed"></textarea>

              @error('notes')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
          </fieldset>
          <div class="w-full flex items-center justify-center gap-4 pt-3 border-t border-gray-200">
            @if ($modalMode === 'edit')
              <button type="submit" wire:target="saveShipping" wire:loading.attr="disabled"
                class="px-4 py-2 bg-green-800 text-white font-semibold rounded-md hover:bg-green-700 disabled:opacity-60">
                <span wire:loading.remove wire:target="saveShipping">
                  Actualizar
                </span>
                <span wire:loading wire:target="saveShipping">
                  <x-icons.animate.spinner class="size-5 inline" />
                  Actualizando...
                </span>
              </button>
            @endif
            <button type="button" @click="modalOpen = false; $wire.closeModal()"
              class="px-4 py-2 bg-red-700 text-white font-semibold rounded-md hover:bg-red-600">
              Cerrar
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
