<?php

use Livewire\Attributes\{Computed, Layout, On, Url};
use Livewire\Component;
use Livewire\WithPagination;

use App\Livewire\Forms\ProductForm;
use App\Models\{Brand, Category, Product};
use App\Traits\HasSortableTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

new #[Layout('layouts::dashboard')] class extends Component {
    use WithPagination;
    use HasSortableTable;

    // | --- Filtros --- |
    #[Url(history: true)]
    public array $categories = [];
    #[Url(history: true)]
    public array $brands = [];
    #[Url(history: true)]
    public string $stockStatus = 'all';
    #[Url(history: true)]
    public ?string $priceFrom = null;
    #[Url(history: true)]
    public ?string $priceTo = null;
    #[Url(history: true)]
    public bool $deleting = false;

    // | --- Ordenamiento --- |
    #[Url(history: true)]
    public string $sortBy = 'name';
    protected array $sorts = [
        'name' => ['column' => 'products.name', 'label' => 'Nombre', 'default' => 'asc'],
        'price' => ['column' => 'products.price', 'label' => 'Precio', 'default' => 'desc'],
        'stock' => ['column' => 'products.stock', 'label' => 'Stock', 'default' => 'desc'],
        'category' => ['label' => 'Categoría', 'default' => 'asc'],
        'brand' => ['label' => 'Marca', 'default' => 'asc'],
    ];
    protected array $sortAliases = [
        'category_id' => 'category',
        'brand_id' => 'brand',
    ];

    // | --- Modal --- |
    public ProductForm $form;
    public ?int $productId = null;
    public bool $modalOpen = false;
    public string $modalMode = 'show';

    // | --- Propiedades Computables --- |
    #[Computed]
    public function products()
    {
        $dir = strtolower($this->sortDir) === 'asc' ? 'asc' : 'desc';
        $field = $this->sortAliases[$this->sortBy] ?? $this->sortBy;

        if (!$this->isSortable($field)) {
            $field = 'name';
        }

        $query = $this->getProductsQuery()->select(['products.id', 'products.name', 'products.sku', 'products.brand_id', 'products.category_id', 'products.price', 'products.stock', 'products.min_stock', 'products.weight', 'products.container', 'products.description', 'products.deleted_at']);

        if ($this->deleting) {
            $query->onlyTrashed()->orderBy('deleted_at', 'desc');
        }

        match ($field) {
            'category' => $query->leftJoin('categories', 'categories.id', '=', 'products.category_id')->orderBy('categories.name', $dir),
            'brand' => $query->leftJoin('brands', 'brands.id', '=', 'products.brand_id')->orderBy('brands.name', $dir),
            default => $query->orderBy($this->sortColumn($field), $dir),
        };

        return $query->orderBy('products.id')->paginate(10);
    }

    #[Computed]
    public function availableCategories(): Collection
    {
        return Category::query()
            ->whereDoesntHave('children')
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function availableBrands(): Collection
    {
        return Brand::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();
    }

    // | --- Filtros --- |
    public function applyFilters(): void
    {
        $this->validate([
            'categories' => ['array'],
            'categories.*' => ['integer', 'distinct', 'exists:categories,id'],

            'brands' => ['array'],
            'brands.*' => ['integer', 'distinct', 'exists:brands,id'],

            'stockStatus' => ['required', 'in:all,in_stock,low,out'],

            'priceFrom' => ['nullable', 'numeric', 'min:0'],
            'priceTo' => ['nullable', 'numeric', 'min:0', 'gte:priceFrom'],
        ]);

        $this->resetPage();
        unset($this->products);
        $this->dispatch('filters-applied');
    }
    public function clearFilters(): void
    {
        $this->reset(['categories', 'brands', 'stockStatus', 'priceFrom', 'priceTo']);
        $this->stockStatus = 'all';
        $this->resetPage();
    }

    // | --- Query --- |
    protected function getProductsQuery(): Builder
    {
        return Product::query()
            ->with(['brand:id,name', 'category:id,name'])
            ->when(filled($this->categories), fn(Builder $q) => $q->whereIn('products.category_id', $this->categories))
            ->when(filled($this->brands), fn(Builder $q) => $q->whereIn('products.brand_id', $this->brands))
            ->when($this->stockStatus === 'in_stock', fn(Builder $q) => $q->whereColumn('products.stock', '>', 'products.min_stock'))
            ->when($this->stockStatus === 'low', fn(Builder $q) => $q->whereColumn('products.stock', '<=', 'products.min_stock')->where('stock', '>', 0))
            ->when($this->stockStatus === 'out', fn(Builder $q) => $q->where('products.stock', 0))
            ->when(filled($this->priceFrom), fn(Builder $q) => $q->where('products.price', '>=', $this->priceFrom))
            ->when(filled($this->priceTo), fn(Builder $q) => $q->where('products.price', '<=', $this->priceTo));
    }

    // | --- Modals --- |
    public function createProductModal()
    {
        abort_unless(auth()->user()?->can('create_products'), 403);

        $this->form->reset();

        $this->modalMode = 'create';
        $this->modalOpen = true;

        $this->dispatch('product-modal-updated');
    }

    public function showProductModal(int $id)
    {
        abort_unless(auth()->user()?->can('view_product'), 403);

        $product = Product::withTrashed()->findOrFail($id);
        $this->productId = $id;
        $this->form->setProduct($product);

        $this->modalMode = 'show';
        $this->modalOpen = true;

        $this->dispatch('product-modal-updated');
    }

    public function editProductModal(int $id)
    {
        abort_unless(auth()->user()?->can('update_products'), 403);

        $product = Product::withTrashed()->findOrFail($id);

        if ($product->trashed()) {
            abort(403, 'No se puede editar un producto eliminado.');
        }

        $this->productId = $id;
        $this->form->setProduct($product);

        $this->modalMode = 'edit';
        $this->modalOpen = true;

        $this->dispatch('product-modal-updated');
    }

    public function saveProduct()
    {
        if ($this->modalMode === 'create') {
            abort_unless(auth()->user()?->can('create_products'), 403);

            $productId = $this->form->store();
            $message = "Producto #{$productId} creado correctamente.";
        } else {
            abort_unless(auth()->user()?->can('update_products'), 403);
            $product = $this->form->update();
            $message = "Producto #{$this->productId} actualizado correctamente.";
        }

        $this->dispatch('notification', message: $message, type: 'success');

        $this->closeModal();
        unset($this->products);
        $this->resetPage();
    }

    #[On('closeModal')]
    public function closeModal()
    {
        $this->modalOpen = false;
        $this->modalMode = 'show';
        $this->productId = null;
        $this->form->reset();
    }

    public function changeDeleting()
    {
        $this->deleting = !$this->deleting;
        $this->resetPage();
    }

    public function deleteProduct(int $id)
    {
        abort_unless(auth()->user()?->can('delete_products'), 403);

        $product = Product::findOrFail($id);
        $product->delete();

        $this->dispatch('notification', message: "Producto #{$id} eliminado correctamente.", type: 'success');
        unset($this->products);
        $this->resetPage();
    }

    public function restoreProduct(int $id)
    {
        abort_unless(auth()->user()?->can('delete_products'), 403);

        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();

        $this->dispatch('notification', message: "Producto #{$id} restaurado correctamente.", type: 'success');
        unset($this->products);
        $this->resetPage();
    }
};
?>

<div x-data="{
    applyingFilters: false,
    clearingFilters: false,
    modalOpen: $wire.entangle('modalOpen'),
    modalMode: $wire.entangle('modalMode'),
    deleteModalOpen: false,
    deleteId: null,
    deleteName: '',
    deleteTrashed: false,
    categoriesChoices: null,
    brandsChoices: null,
    filterCategories: @js($categories),
    filterBrands: @js($brands),
    stockStatus: @js($stockStatus),
    priceFrom: @js($priceFrom),
    priceTo: @js($priceTo),
    modalCategoryChoices: null,
    modalBrandChoices: null,
    modalCategoryElement: null,
    modalBrandElement: null,

    init() {
        this.$nextTick(() => {
            this.initChoices()
            this.initProductChoices()
        })
    },
    closeFilterModal() {
        this.$refs.filterModal?.close()
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
        if (this.$refs.categoriesSelect) {
            this.categoriesChoices = this.getOrCreateChoices(this.$refs.categoriesSelect, {
                searchEnabled: true,
                searchPlaceholderValue: 'Buscar categoría...',
                removeItemButton: true,
                placeholder: true,
                placeholderValue: 'Selecciona una o más categorías',
                shouldSort: false,
                itemSelectText: '',
                noResultsText: 'No se encontraron categorías',
                noChoicesText: 'No hay categorías disponibles',
                allowHTML: false,
            }, this.filterCategories)

            this.$refs.categoriesSelect.addEventListener('change', (event) => {
                this.filterCategories = Array.from(event.target.selectedOptions).map(o => o.value)
            })
        }

        if (this.$refs.brandsSelect) {
            this.brandsChoices = this.getOrCreateChoices(this.$refs.brandsSelect, {
                searchEnabled: true,
                searchPlaceholderValue: 'Buscar marca...',
                removeItemButton: true,
                placeholder: true,
                placeholderValue: 'Selecciona una o más marcas',
                shouldSort: false,
                itemSelectText: '',
                noResultsText: 'No se encontraron marcas',
                noChoicesText: 'No hay marcas disponibles',
                allowHTML: false,
            }, this.filterBrands)

            this.$refs.brandsSelect.addEventListener('change', (event) => {
                this.filterBrands = Array.from(event.target.selectedOptions).map(o => o.value)
            })
        }
    },

    initProductChoices() {
        const categoryElement = this.$refs.modalCategorySelect;
        const brandElement = this.$refs.modalBrandSelect;

        if (this.modalCategoryElement !== categoryElement) {
            this.modalCategoryChoices?.destroy();
            this.modalCategoryChoices = null;
            if (this.modalCategoryElement?._choicesInstance) {
                delete this.modalCategoryElement._choicesInstance;
            }
            this.modalCategoryElement = categoryElement;
        }
        if (this.modalBrandElement !== brandElement) {
            this.modalBrandChoices?.destroy();
            this.modalBrandChoices = null;
            if (this.modalBrandElement?._choicesInstance) {
                delete this.modalBrandElement._choicesInstance;
            }
            this.modalBrandElement = brandElement;
        }
        if (categoryElement && !this.modalCategoryChoices) {
            this.modalCategoryChoices = this.getOrCreateChoices(
                categoryElement, {
                    searchEnabled: true,
                    searchPlaceholderValue: 'Buscar categoría...',
                    placeholder: true,
                    placeholderValue: 'Selecciona una categoría',
                    shouldSort: false,
                    itemSelectText: '',
                    noResultsText: 'No se encontraron categorías',
                    noChoicesText: 'No hay categorías disponibles',
                    allowHTML: false,
                }
            );
            if (categoryElement._modalChangeHandler) {
                categoryElement.removeEventListener('change', categoryElement._modalChangeHandler);
            }
            categoryElement._modalChangeHandler = (event) => {
                const value = event.target.value;
                this.$wire.set('form.categoryId', value === '' ? null : Number(value));
            };
            categoryElement.addEventListener('change', categoryElement._modalChangeHandler);
        }

        if (brandElement && !this.modalBrandChoices) {
            this.modalBrandChoices = this.getOrCreateChoices(
                brandElement, {
                    searchEnabled: true,
                    searchPlaceholderValue: 'Buscar marca...',
                    placeholder: true,
                    placeholderValue: 'Selecciona una marca',
                    shouldSort: false,
                    itemSelectText: '',
                    noResultsText: 'No se encontraron marcas',
                    noChoicesText: 'No hay marcas disponibles',
                    allowHTML: false,
                }
            );
            if (brandElement._modalChangeHandler) {
                brandElement.removeEventListener('change', brandElement._modalChangeHandler);
            }
            brandElement._modalChangeHandler = (event) => {
                const value = event.target.value;
                this.$wire.set('form.brandId', value === '' ? null : Number(value));
            };
            brandElement.addEventListener('change', brandElement._modalChangeHandler);
        }
    },

    setChoiceValue(instance, value) {
        if (!instance) return;
        instance.removeActiveItems();
        if (value !== null && value !== undefined && value !== '') {
            instance.setChoiceByValue(String(value));
        } else {
            instance.setChoiceByValue('');
        }
    },

    syncProductChoices() {
        this.$nextTick(() => {
            this.initProductChoices();

            const categoryId = this.$wire.get('form.categoryId');
            const brandId = this.$wire.get('form.brandId');

            this.setChoiceValue(this.modalCategoryChoices, categoryId);

            this.setChoiceValue(this.modalBrandChoices, brandId);

            if (this.modalMode === 'show') {
                this.modalCategoryChoices?.disable();
                this.modalBrandChoices?.disable();
            } else {
                this.modalCategoryChoices?.enable();
                this.modalBrandChoices?.enable();
            }
        });
    },


    async applyChoicesFilter() {
        if (this.applyingFilters || this.clearingFilters) return;

        this.applyingFilters = true;

        try {
            this.$wire.set('categories', this.filterCategories.map(Number), false);
            this.$wire.set('brands', this.filterBrands.map(Number), false);
            this.$wire.set('stockStatus', this.stockStatus, false);
            this.$wire.set('priceFrom', this.priceFrom || null, false);
            this.$wire.set('priceTo', this.priceTo || null, false);

            await this.$wire.call('applyFilters');
        } finally {
            this.applyingFilters = false;
        }
    },

    async clearChoicesFilter() {
        if (this.applyingFilters || this.clearingFilters) return;

        this.clearingFilters = true;

        try {
            this.filterCategories = [];
            this.filterBrands = [];
            this.stockStatus = 'all';
            this.priceFrom = null;
            this.priceTo = null;

            this.categoriesChoices?.removeActiveItems();
            this.brandsChoices?.removeActiveItems();

            await this.$wire.call('clearFilters');
        } finally {
            this.clearingFilters = false;
        }
    },

    syncFiltersFromLivewire() {
        this.filterCategories = [...$wire.categories].map(String)
        this.filterBrands = [...$wire.brands].map(String)

        this.stockStatus = $wire.stockStatus
        this.priceFrom = $wire.priceFrom
        this.priceTo = $wire.priceTo

        this.categoriesChoices?.removeActiveItems()
        this.brandsChoices?.removeActiveItems()

        this.categoriesChoices?.setChoiceByValue(this.filterCategories)
        this.brandsChoices?.setChoiceByValue(this.filterBrands)
    },

    openModal(modal) {
        if (modal === 'filterModal') {
            this.syncFiltersFromLivewire()
        }
        this.$nextTick(() => this.$refs[modal].showModal())
    },

    closeModal(modal) {
        this.$refs[modal].close()
    },
}" x-on:filters-applied.window="closeFilterModal()">
  <x-sections.headerTitle class="flex justify-between items-center">
    <x-slot:textTitle>Productos</x-slot:textTitle>
    <div class="flex flex-wrap items-center gap-4">
      @can('create_products')
        <button type="button" wire:click="createProductModal"
          class="relative px-3 py-2.5 font-semibold text-white bg-purple-700 rounded-lg hover:bg-purple-600 cursor-pointer">
          <x-icons.plus class="size-5 inline" />
          Nuevo Producto
          <span wire:loading wire:target="createProductModal" wire:loading.flex
            class="absolute z-10 top-0 left-0 w-full h-full bg-slate-700 opacity-60 items-center justify-center cursor-not-allowed pointer-events-none">
            <x-icons.animate.spinner class="size-4" />
          </span>
        </button>
      @endcan
      <button type="button" @click="openModal('filterModal')"
        class="px-3 py-2.5 font-semibold text-white bg-blue-800 rounded-lg hover:bg-blue-700 cursor-pointer">
        Filtrar
      </button>
    </div>
  </x-sections.headerTitle>

  <x-tables.sort-bar :sortStatus="$this->sortStatus()" :extraTarget="'changeDeleting'">
    <button type="button" wire:click="changeDeleting" wire:loading.attr="disabled"
      wire:target="sort, resetSort, changeDeleting"
      class="cursor-pointer rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 disabled:cursor-wait disabled:opacity-60">
      {{ $deleting ? 'Mostrar productos activos' : 'Mostrar productos eliminados' }}
    </button>
  </x-tables.sort-bar>

  <div class="relative">
    <x-tables.loading-overlay
      target="applyFilters,clearFilters,categories,brands,stockStatus,priceFrom,priceTo,sort,resetSort, changeDeleting,deleteProduct,restoreProduct" />
    <table class="w-full text-sm border-separate border-spacing-y-1 [&_td]:py-4 [&_td]:px-3 [&_tr]:bg-slate-800">
      <thead class="text-slate-400">
        <tr class="text-left" wire:key="products-sort-{{ $sortBy }}-{{ $sortDir }}">
          <th class="px-3 py-4">#</th>
          <x-tables.sort-th field="name" :sortBy="$sortBy" :sortDir="$sortDir">Nombre</x-tables.sort-th>
          <x-tables.sort-th field="brand" :sortBy="$sortBy" :sortDir="$sortDir"
            class="hidden md:table-cell">Marca</x-tables.sort-th>
          <x-tables.sort-th field="category" :sortBy="$sortBy" :sortDir="$sortDir"
            class="hidden lg:table-cell">Categoría</x-tables.sort-th>
          <x-tables.sort-th field="price" :sortBy="$sortBy" :sortDir="$sortDir">Precio</x-tables.sort-th>
          <x-tables.sort-th field="stock" :sortBy="$sortBy" :sortDir="$sortDir"
            class="hidden sm:table-cell">Stock</x-tables.sort-th>
          <th class="px-3 py-4 uppercase">Estado de Stock</th>
          <th class="px-3 py-4 text-end uppercase">Opciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($this->products as $index => $product)
          <tr wire:key="product-{{ $product->id }}">
            <td>{{ ($this->products->currentPage() - 1) * $this->products->perPage() + $index + 1 }}</td>
            <td class="font-bold">{{ $product->name }}</td>
            <td class="hidden md:table-cell">{{ $product->brand?->name ?? '—' }}</td>
            <td class="hidden lg:table-cell">{{ $product->category?->name ?? '—' }}</td>
            <td>${{ number_format($product->price, 2, ',', '.') }}</td>
            <td class="hidden sm:table-cell">{{ $product->stock }}</td>
            <td>
              <span @class([
                  "font-semibold before:content-['●'] before:me-px",
                  'text-green-400' => $product->stock > $product->min_stock,
                  'text-amber-400' =>
                      $product->stock > 0 && $product->stock <= $product->min_stock,
                  'text-red-400' => $product->stock === 0,
              ])>
                {{ $product->stock === 0 ? 'Sin stock' : ($product->stock <= $product->min_stock ? 'Bajo' : 'En stock') }}
              </span>
            </td>
            <td>
              <div class="relative flex justify-end">
                <x-popups.contentWcheck iid="chproduct-{{ $product->id }}" labelClass="hover:bg-slate-900"
                  class="right-12 -top-1/4">
                  <x-slot:label>
                    <x-icons.threeDotsX class="size-6" />
                  </x-slot:label>
                  <ul
                    class="w-max py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold">
                    @can('view_product')
                      <li>
                        <button type="button" wire:click="showProductModal({{ $product->id }})"
                          class="relative w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors">
                          <x-icons.show class="size-5" />
                          Detalles
                          <span wire:loading wire:target="showProductModal({{ $product->id }})" wire:loading.flex
                            class="absolute z-10 top-0 left-0 w-full h-full bg-slate-700 opacity-60 items-center justify-center cursor-not-allowed pointer-events-none">
                            <x-icons.animate.spinner class="size-4" />
                          </span>
                        </button>
                      </li>
                    @endcan
                    @can('update_products')
                      @if (!$product->trashed())
                        <li>
                          <button type="button" wire:click="editProductModal({{ $product->id }})"
                            class="relative w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors">
                            <x-icons.edit class="size-5" />
                            Editar
                            <span wire:loading wire:target="editProductModal({{ $product->id }})" wire:loading.flex
                              class="absolute z-10 top-0 left-0 w-full h-full bg-slate-700 opacity-60 items-center justify-center cursor-not-allowed pointer-events-none">
                              <x-icons.animate.spinner class="size-4" />
                            </span>
                          </button>
                        </li>
                      @endif
                    @endcan
                    @can('delete_products')
                      @if (!$product->trashed())
                        <li>
                          <button type="button"
                            @click="$dispatch('open-delete', { id: {{ $product->id }}, name: @js($product->name), trashed: false })"
                            class="relative w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors">
                            <x-icons.trash class="size-5" />
                            Eliminar
                          </button>
                        </li>
                      @else
                        <li>
                          <button type="button"
                            @click="$dispatch('open-delete', { id: {{ $product->id }}, name: @js($product->name), trashed: true })"
                            class="relative w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors">
                            <x-icons.restore class="size-5" />
                            Restaurar
                          </button>
                        </li>
                      @endif
                    @endcan
                    @can('view_any_orders')
                      <li>
                        <a href="{{ route('products.orders', $product->id) }}"
                          class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-700 transition-colors">
                          <span>
                            <x-icons.statistics class="size-5" />
                          </span>
                          Productos en Ordenes
                        </a>
                      </li>
                    @endcan
                  </ul>
                </x-popups.contentWcheck>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center font-semibold text-slate-300">No hay productos registrados</td>
          </tr>
        @endforelse
      </tbody>
    </table>
    <div class="mt-1">
      {{ $this->products->onEachSide(1)->links('pages.dashboard.partials.wirePagination') }}
    </div>
  </div>

  {{-- Modal Fitro --}}
  <dialog x-ref="filterModal" @click.self="closeModal('filterModal')"
    class="top-1/2 left-1/2 -translate-1/2 max-w-lg w-full max-h-[90vh] rounded-md p-0 backdrop:bg-black/60 backdrop:backdrop-blur-sm">
    <div class="max-h-[90vh] flex flex-col bg-white text-slate-900 rounded-lg">
      <div class="shrink-0 flex items-center justify-between px-5 py-4 border-b border-slate-200">
        <h2 class="mt-3 text-2xl text-center text-purple-900 font-semibold">Filtrar productos</h2>
        <button type="button" @click="closeModal('filterModal')"
          class="text-slate-500 hover:text-slate-900 text-xl cursor-pointer">
          <x-icons.x />
        </button>
      </div>
      <div class="min-h-0 flex-1 p-5 space-y-5 overflow-y-auto [scrollbar-color:#62748e_transparent] scrollbar-thin">
        <div>
          <label for="filter_categories" class="block text-sm font-medium mb-2">Categorías</label>
          <div wire:ignore>
            <select id="filter_categories" x-ref="categoriesSelect" multiple
              class="w-full min-h-28 border border-slate-300 rounded-md px-3 py-2 outline-none">
              @foreach ($this->availableCategories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
              @endforeach
            </select>
          </div>
          @error('categories')
            <span class="text-red-500 text-sm">{{ $message }}</span>
          @enderror
        </div>
        <div>
          <label for="filter_brands" class="block text-sm font-medium mb-2">Marcas</label>
          <div wire:ignore>
            <select id="filter_brands" x-ref="brandsSelect" multiple
              class="w-full min-h-28 border border-slate-300 rounded-md px-3 py-2 outline-none">
              @foreach ($this->availableBrands as $brand)
                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
              @endforeach
            </select>
          </div>
          @error('brands')
            <span class="text-red-500 text-sm">{{ $message }}</span>
          @enderror
        </div>
        <div>
          <label for="filter_stock_status" class="block text-sm font-medium mb-2">Stock</label>
          <select id="filter_stock_status" wire:model="stockStatus"
            class="w-full px-3 py-2 border border-slate-300 rounded-md outline-none">
            <option value="all">Todos</option>
            <option value="in_stock">En stock</option>
            <option value="low">Stock bajo</option>
            <option value="out">Sin stock</option>
          </select>
          @error('stockStatus')
            <span class="text-red-500 text-sm">{{ $message }}</span>
          @enderror
        </div>
        <div>
          <h4 class="block text-sm font-medium mb-2">Precio</h4>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label for="filter_price_from" class="text-xs text-slate-500">Desde</label>
              <input id="filter_price_from" type="number" min="0" step="0.01" wire:model="priceFrom"
                placeholder="0" class="w-full px-3 py-2 border border-slate-300 rounded-md outline-none">
              @error('priceFrom')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <div>
              <label for="filter_price_to" class="text-xs text-slate-500">Hasta</label>
              <input id="filter_price_to" type="number" min="0" step="0.01" wire:model="priceTo"
                placeholder="999999" class="w-full px-3 py-2 border border-slate-300 rounded-md outline-none">
              @error('priceTo')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
          </div>
        </div>
      </div>
      <div class="shrink-0 flex justify-between px-5 py-4 border-t border-slate-200">
        <button type="button" @click="clearChoicesFilter()" :disabled="applyingFilters || clearingFilters"
          class="relative px-4 py-2.5 inline-flex items-center justify-center gap-2
           rounded-lg  font-semibold bg-slate-800 text-white hover:bg-slate-600
           disabled:cursor-wait disabled:opacity-60 disabled:hover:bg-slate-800">
          <span x-show="!clearingFilters">
            Limpiar filtros
          </span>
          <span x-cloak x-show="clearingFilters" class="inline-flex items-center gap-2">
            <x-icons.animate.spinner class="size-4" />
            Limpiando...
          </span>
        </button>
        <div class="flex gap-3">
          <button type="button" @click="closeModal('filterModal')" :disabled="applyingFilters || clearingFilters"
            class="px-4 py-2.5 bg-red-800 text-white font-semibold rounded-lg hover:bg-red-700 disabled:cursor-wait disabled:opacity-60 disabled:hover:bg-red-800">Cancelar</button>
          <button type="button" @click="applyChoicesFilter()" :disabled="applyingFilters || clearingFilters"
            class="relative inline-flex items-center justify-center gap-2
           rounded-lg bg-blue-800 px-4 py-2.5 font-semibold text-white
           hover:bg-blue-700 disabled:cursor-wait disabled:opacity-60 disabled:hover:bg-blue-800">
            <span x-show="!applyingFilters">
              Aplicar filtros
            </span>
            <span x-cloak x-show="applyingFilters" class="inline-flex items-center gap-2">
              <x-icons.animate.spinner class="size-4" />
              Aplicando...
            </span>
          </button>
        </div>
      </div>
    </div>
  </dialog>

  {{-- Modal Show/Edit --}}
  <div x-cloak x-show="modalOpen" x-on:product-modal-updated.window="syncProductChoices()"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-40 flex items-center justify-center bg-black/60 "
    @click.self="modalOpen = false; $wire.closeModal()"
    @keydown.escape.window="modalOpen = false; $wire.closeModal()">
    <div
      class="w-full max-w-xl max-h-[90%] p-4 relative flex flex-col items-center gap-4 bg-white rounded-lg shadow-xl overflow-y-auto scrollbar-track-transparent scrollbar-thumb-slate-300/50">
      <h2 class="mt-3 text-2xl text-center text-purple-900 font-semibold">
        {{ $modalMode === 'show' ? 'Ver Producto' : ($modalMode === 'edit' ? 'Editar Producto' : 'Nuevo Producto') }}{{ $productId ? ' #' . $productId : '' }}
      </h2>
      <form wire:submit="saveProduct" class="w-full flex flex-col gap-4 text-gray-700">
        <div wire:key="product-modal-body-{{ $productId ?? 'none' }}">
          <fieldset
            class="w-full py-3 grid grid-cols-[repeat(auto-fit,minmax(170px,1fr))] gap-6 text-gray-700 md:px-3">
            <div class="col-span-full">
              <label class="block mb-2 font-semibold" for="name">Nombre</label>
              <input type="text" id="name" wire:model.blur="form.name" @readonly($modalMode === 'show') autocomplete="off"
                class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500 read-only:cursor-not-allowed read-only:opacity-70">
              @error('form.name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <div>
              <label class="block mb-2 font-semibold" for="sku">SKU</label>
              <input type="text" id="sku" wire:model.blur="form.sku" @readonly($modalMode === 'show')
                class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500 read-only:cursor-not-allowed read-only:opacity-70">
              @error('form.sku')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <div>
              <label class="block mb-2 font-semibold" for="price">Precio</label>
              <input type="number" id="price" step="0.01" wire:model.blur="form.price" @readonly($modalMode === 'show')
                class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500 read-only:cursor-not-allowed read-only:opacity-70">
              @error('form.price')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <div>
              <label class="block mb-2 font-semibold" for="stock">Stock</label>
              <input type="number" id="stock" wire:model.blur="form.stock" @readonly($modalMode === 'show')
                class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500 read-only:cursor-not-allowed read-only:opacity-70">
              @error('form.stock')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <div>
              <label class="block mb-2 font-semibold" for="minStock">Stock mínimo</label>
              <input type="number" id="minStock" wire:model.blur="form.minStock" @readonly($modalMode === 'show')
                class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500 read-only:cursor-not-allowed read-only:opacity-70">
              @error('form.minStock')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <div>
              <label class="block mb-2 font-semibold" for="weight">Peso (kg)</label>
              <input type="text" id="weight" wire:model.blur="form.weight" @readonly($modalMode === 'show')
                class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500 read-only:cursor-not-allowed read-only:opacity-70">
              @error('form.weight')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <div>
              <label class="block mb-2 font-semibold" for="container">Contenedor</label>
              <input type="text" id="container" wire:model.blur="form.container" @readonly($modalMode === 'show')
                class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500 read-only:cursor-not-allowed read-only:opacity-70">
              @error('form.container')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <div>
              <div wire:ignore>
                <label class="block mb-2 font-semibold" for="categoryId">Categoría</label>
                <select id="categoryId" x-ref="modalCategorySelect" @disabled($modalMode === 'show')
                  class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500 disabled:cursor-wait disabled:opacity-70">
                  @foreach ($this->availableCategories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                  @endforeach
                </select>
              </div>
              @error('form.categoryId')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <div>
              <div wire:ignore>
                <label class="block mb-2 font-semibold" for="brandId">Marca</label>
                <select id="brandId" x-ref="modalBrandSelect" @disabled($modalMode === 'show')
                  class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500 disabled:cursor-wait disabled:opacity-70">
                  @foreach ($this->availableBrands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                  @endforeach
                </select>
              </div>
              @error('form.brandId')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <div class="col-span-full">
              <label class="block mb-2 font-semibold" for="description">Descripción</label>
              <textarea id="description" rows="3" wire:model.blur="form.description" @readonly($modalMode === 'show')
                class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500 read-only:cursor-not-allowed read-only:opacity-70"></textarea>
              @error('form.description')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
          </fieldset>
          <div class="w-full flex items-center justify-center gap-7">
            @if ($modalMode !== 'show')
              <button type="submit"
                class="px-3 py-2 bg-green-900 text-lg text-white rounded-md hover:bg-green-800 cursor-pointer"
                wire:target="saveProduct" wire:loading.attr="disabled">
                <span wire:loading.remove
                  wire:target="saveProduct">{{ $modalMode === 'create' ? 'Crear' : 'Actualizar' }}</span>
                <span wire:loading wire:target="saveProduct"><x-icons.animate.spinner class="size-5 inline" />
                  {{ $modalMode === 'create' ? 'Creando...' : 'Actualizando...' }}</span>
              </button>
            @endif
            <button type="button" @click="modalOpen = false; $wire.closeModal()"
              class="px-3 py-2 bg-red-700 text-lg text-white rounded-md hover:bg-red-600 cursor-pointer">Cerrar</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  {{-- Modal Delete/Restore --}}
  <div x-cloak x-show="deleteModalOpen" x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 flex items-center justify-center bg-black/60 "
    @open-delete.window="deleteId = $event.detail.id; deleteName = $event.detail.name; deleteTrashed = !!$event.detail.trashed; deleteModalOpen = true"
    @click.self="deleteModalOpen = false" @keydown.escape.window="deleteModalOpen = false">
    <div
      class="w-full max-w-md p-4 relative flex flex-col items-center gap-4 bg-white rounded-lg shadow-xl overflow-y-auto">
      <span class="my-6 text-red-700">
        <x-icons.warning class="size-28" />
      </span>
      <div class="px-3 mb-6 text-lg text-slate-700 text-center">
        <p class="mb-2">
          ¿Está seguro de que quieres <b class="uppercase" x-text="deleteTrashed ? 'Restaurar' : 'Eliminar'"></b>:
        </p>
        <p class="text-xl font-bold text-slate-800" x-text="deleteName"></p>
      </div>
      <div class="flex justify-center gap-4 text-white md:gap-6">
        <button type="button" @click="deleteModalOpen = false"
          class="px-3 py-2 rounded-md bg-slate-700 hover:bg-slate-600 cursor-pointer">Cancelar</button>
        <button type="button" x-show="!deleteTrashed"
          @click="deleteModalOpen = false; $wire.deleteProduct(deleteId)"
          class="px-3 py-2 rounded-md bg-red-900 hover:bg-red-800 cursor-pointer">Eliminar</button>
        <button type="button" x-show="deleteTrashed"
          @click="deleteModalOpen = false; $wire.restoreProduct(deleteId)"
          class="px-3 py-2 rounded-md bg-green-900 hover:bg-green-800 cursor-pointer">Restaurar</button>
      </div>
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
