<?php

use App\Models\{Brand, Category, Offer, Product};
use App\Services\ProductSearchService;
use Illuminate\Support\Collection;
use Livewire\Attributes\{Url, Validate};
use Livewire\Component;

new class extends Component {
    public array $categoriesNav, $sorts;
    public Collection $brandsProducts, $categoriesProduct, $offers, $products;

    #[Url(as: 'query')]
    #[Validate('nullable|string|max:255')]
    public string $query = '';

    #[Url(as: 'offer_id')]
    #[Validate('nullable|integer')]
    public ?int $offerId = null;

    #[Url(as: 'categories', history: true)]
    public array $selectedCategories = [];

    #[Url(as: 'brands', history: true)]
    public array $selectedBrands = [];

    #[Url(as: 'sort', history: true)]
    public ?string $selectedSort = null;

    #[Url(as: 'display', history: true)]
    public string $selectedDisplay = 'grilla';

    // Productos originales (sin filtrar)
    public Collection $originalProducts;

    public function boot()
    {
        $this->offers = Offer::with(['offerTemplate:id,name,offer_type_id,buy_qty,pay_qty', 'offerTemplate.offerType:id,code'])
            ->active()
            ->get()
            ->keyBy('id');

        $this->sorts = ['Más vendidos', 'Menor precio', 'Mayor precio', 'A-Z', 'Z-A'];
    }
    public function mount(ProductSearchService $service)
    {
        if ($this->query) {
            $this->search($service);
        } elseif ($this->offerId) {
            $this->getByOffer($service);
        } else {
            return redirect()->route('home');
        }

        $this->originalProducts = $this->products;

        if (!empty($this->selectedCategories) || !empty($this->selectedBrands)) {
            $this->applyFilters();
        }

        if (!empty($this->selectedSort) && $this->products->isNotEmpty()) {
            $this->applySorting();
        }
    }

    public function updatedSelectedCategories()
    {
        $this->applyFilters();
        $this->applySorting();
    }

    public function updatedSelectedBrands()
    {
        $this->applyFilters();
        $this->applySorting();
    }

    public function updatedSelectedSort()
    {
        $this->applySorting();
    }

    public function updatedSelectedDisplay() {}

    //  --- Métodos privados auxiliares ---
    protected function applyFilters(): void
    {
        if (empty($this->selectedCategories) && empty($this->selectedBrands)) {
            $this->products = $this->originalProducts;
        } else {
            $this->products = $this->originalProducts
                ->filter(function ($product) {
                    $matchCategory = empty($this->selectedCategories) || in_array($product->category_id, $this->selectedCategories);

                    $matchBrand = empty($this->selectedBrands) || in_array($product->brand_id, $this->selectedBrands);

                    return $matchCategory && $matchBrand;
                })
                ->values();
        }

        $this->updateNavigationAndSidebar();
    }

    protected function updateNavigationAndSidebar(): void
    {
        // --- Construir categoriesNav ---
        $nav[] = $this->categoriesNav[0];

        if (!empty($this->selectedCategories)) {
            $categoryNames = Category::whereIn('id', $this->selectedCategories)->pluck('name')->toArray();
            $nav = array_merge($nav, $categoryNames);
        }

        if (!empty($this->selectedBrands)) {
            $brandNames = Brand::whereIn('id', $this->selectedBrands)->pluck('name')->toArray();
            $nav = array_merge($nav, $brandNames);
        }

        $this->categoriesNav = $nav;

        // --- Actualizar colecciones del sidebar ---
        if ($this->products->isNotEmpty()) {
            $categoryIds = $this->products->pluck('category_id')->unique()->values();
            $this->categoriesProduct = Category::whereIn('id', $categoryIds)
                ->select(['id', 'name'])
                ->get();

            $brandIds = $this->products->pluck('brand_id')->unique()->values();
            $this->brandsProducts = Brand::whereIn('id', $brandIds)
                ->select(['id', 'name'])
                ->get();
        } else {
            $this->categoriesProduct = collect();
            $this->brandsProducts = collect();
        }
    }

    protected function applySorting(): void
    {
        $this->products->loadCount([
            'orders' => function (\Illuminate\Database\Eloquent\Builder $query) {
                $query->paid();
            },
        ]);
        $this->products = match ($this->selectedSort) {
            'Más vendidos' => $this->products->sortByDesc('orders_count'),
            'Menor precio' => $this->products->sortBy('price'),
            'Mayor precio' => $this->products->sortByDesc('price'),
            'A-Z' => $this->products->sortByDesc('name'),
            'Z-A' => $this->products->sortBy('name'),
            default => $this->products,
        };

        $this->products = $this->products->values();
    }

    protected function search(ProductSearchService $service): void
    {
        $this->products = $service->search($this->query);
        $this->categoriesNav = [$this->query];
        $this->brandsProducts = $service->getRelatedBrands($this->products);
        $this->categoriesProduct = $service->getRelatedCategories($this->products);
    }

    protected function getByOffer(ProductSearchService $service): void
    {
        $offer = Offer::active()->findOrFail($this->offerId);
        $this->products = $service->getProductsByOffer($this->offerId);
        $this->categoriesNav = [$offer->offerTemplate->name];
        $this->brandsProducts = $service->getRelatedBrands($this->products);
        $this->categoriesProduct = $service->getRelatedCategories($this->products);
    }
};
?>

<div>
  <x-breadcrumbs.categories :categoriesNav="$categoriesNav" />
  <div
    class="relative w-full px-3 py-2 mb-10 grid grid-cols-3 gap-3 items-center divide-x-2 divide-slate-300 border-b border-slate-300 bg-slate-200/65 rounded-xl">
    <div>
      <div class="hidden py-2 ms-5 text-base lg:flex lg:justify-start lg:items-baseline lg:gap-4">
        <h3 class="font-semibold">{{ end($categoriesNav) }}</h3>
        <span class="text-gray-500">{{ $products->count() }} productos</span>
      </div>
      <label for="toggle-filter"
        class="py-3 me-3 flex items-center justify-center gap-3 cursor-pointer rounded-lg hover:bg-slate-800/10 lg:hidden">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
          <path fill="currentColor"
            d="M13.75 2.25a.75.75 0 0 1 .75.75v4A.75.75 0 0 1 13 7V5.75H3a.75.75 0 0 1 0-1.5h10V3a.75.75 0 0 1 .75-.75M17.25 5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 0 1.5h-3a.75.75 0 0 1-.75-.75m-6.5 4.25a.75.75 0 0 1 .75.75v1.25H21a.75.75 0 0 1 0 1.5h-9.5V14a.75.75 0 0 1-1.5 0v-4a.75.75 0 0 1 .75-.75M2.25 12a.75.75 0 0 1 .75-.75h4a.75.75 0 0 1 0 1.5H3a.75.75 0 0 1-.75-.75m11.5 4.25a.75.75 0 0 1 .75.75v4a.75.75 0 0 1-1.5 0v-1.25H3a.75.75 0 0 1 0-1.5h10V17a.75.75 0 0 1 .75-.75m3.5 2.75a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 0 1.5h-3a.75.75 0 0 1-.75-.75" />
        </svg>
        <span class="hidden sm:inline">Filtrar por</span>
      </label>
    </div>
    <div class="pe-3">
      <label for="toggle-sort"
        class="py-3 flex items-center justify-center gap-3 cursor-pointer rounded-lg hover:bg-slate-800/10">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
          <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M7 3v18m3-15L7 3L4 6m16 12l-3 3l-3-3m3 3V3" />
        </svg>
        <span class="hidden sm:inline">Ordernar por</span>
      </label>
    </div>
    <div class="pe-3 grid grid-cols-2 gap-2 sm:grid-cols-3">
      <span class="hidden sm:grid sm:place-content-center md:px-2">Ver en</span>
      <!-- Falta agregar funcionalidad de aplicar estilos en el estado checked a las tarjetas de los productos -->
      <label
        class="py-3 flex items-center justify-center cursor-pointer rounded-lg has-checked:bg-slate-800/10 has-checked:pointer-events-none hover:bg-slate-800/10">
        <input type="radio" name="display" class="hidden" value="list" wire:model.live="selectedDisplay">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
          <path fill="currentColor"
            d="M19 18c.55 0 1 .45 1 1s-.45 1-1 1H5c-.55 0-1-.45-1-1s.45-1 1-1zm0-2H5c-1.654 0-3 1.346-3 3s1.346 3 3 3h14c1.654 0 3-1.346 3-3s-1.346-3-3-3m0-5c.55 0 1 .45 1 1s-.45 1-1 1H5c-.55 0-1-.45-1-1s.45-1 1-1zm0-2H5c-1.654 0-3 1.346-3 3s1.346 3 3 3h14c1.654 0 3-1.346 3-3s-1.346-3-3-3m0-5c.55 0 1 .45 1 1s-.45 1-1 1H5c-.55 0-1-.45-1-1s.45-1 1-1zm0-2H5C3.346 2 2 3.346 2 5s1.346 3 3 3h14c1.654 0 3-1.346 3-3s-1.346-3-3-3" />
        </svg>
      </label>
      <label
        class="py-3 flex items-center justify-center cursor-pointer rounded-lg has-checked:bg-slate-800/10 has-checked:pointer-events-none hover:bg-slate-800/10">
        <input type="radio" name="display" class="hidden" value="grilla" wire:model.live="selectedDisplay" checked>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
          <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M2 18c0-1.54 0-2.31.347-2.876c.194-.317.46-.583.777-.777C3.689 14 4.46 14 6 14s2.31 0 2.876.347c.317.194.583.46.777.777C10 15.689 10 16.46 10 18s0 2.31-.347 2.877c-.194.316-.46.582-.777.776C8.311 22 7.54 22 6 22s-2.31 0-2.876-.347a2.35 2.35 0 0 1-.777-.776C2 20.31 2 19.54 2 18m12 0c0-1.54 0-2.31.347-2.876c.194-.317.46-.583.777-.777C15.689 14 16.46 14 18 14s2.31 0 2.877.347c.316.194.582.46.776.777C22 15.689 22 16.46 22 18s0 2.31-.347 2.877a2.36 2.36 0 0 1-.776.776C20.31 22 19.54 22 18 22s-2.31 0-2.876-.347a2.35 2.35 0 0 1-.777-.776C14 20.31 14 19.54 14 18M2 6c0-1.54 0-2.31.347-2.876c.194-.317.46-.583.777-.777C3.689 2 4.46 2 6 2s2.31 0 2.876.347c.317.194.583.46.777.777C10 3.689 10 4.46 10 6s0 2.31-.347 2.876c-.194.317-.46.583-.777.777C8.311 10 7.54 10 6 10s-2.31 0-2.876-.347a2.35 2.35 0 0 1-.777-.777C2 8.311 2 7.54 2 6m12 0c0-1.54 0-2.31.347-2.876c.194-.317.46-.583.777-.777C15.689 2 16.46 2 18 2s2.31 0 2.877.347c.316.194.582.46.776.777C22 3.689 22 4.46 22 6s0 2.31-.347 2.876c-.194.317-.46.583-.776.777C20.31 10 19.54 10 18 10s-2.31 0-2.876-.347a2.35 2.35 0 0 1-.777-.777C14 8.311 14 7.54 14 6"
            color="currentColor" />
        </svg>
      </label>
    </div>

    <input type="checkbox" id="toggle-sort" class="hidden peer/order" />
    <section
      class="absolute top-18 left-1/2 -translate-x-1/2 z-10 hidden w-fit min-w-xs px-4 pt-4 pb-8 bg-sky-800 text-white rounded-xl shadow-xl/20 peer-checked/order:block">
      <div class="relative">
        <label for="toggle-sort"
          class="absolute top-0 right-0 hover:text-blue-200 hover:bg-white/10 rounded-lg cursor-pointer">
          <x-icons.x />
        </label>
      </div>
      <div class="mt-8">
        <div class="px-4 flex flex-col gap-1 max-h-52 overflow-y-auto scroll-smooth scrollbar-thin space-y-1"
          style="scrollbar-color: white transparent">
          @foreach ($sorts as $sort)
            <label
              class="w-full p-3 rounded-xl hover:bg-white/10 has-checked:bg-white/10 has-checked:text-sky-300 cursor-pointer">
              <input type="radio" name="sort[]" value="{{ $sort }}" class="hidden"
                wire:model.live="selectedSort" wire:key="sort-{{ $sort }}">
              {{ $sort }}
            </label>
          @endforeach
        </div>
      </div>
    </section>
  </div>
  <section class="relative px-3 pb-6 mb-8 lg:flex lg:gap-10">
    <input type="checkbox" id="toggle-filter" class="hidden peer/filter" />
    <aside
      class="absolute top-0 left-[5%] z-20 hidden w-[90%] px-4 pt-4 pb-8 bg-sky-800 text-white rounded-xl lg:z-0 lg:static lg:w-72 lg:block lg:h-max lg:shadow-xl/20 peer-checked/filter:block">
      <div class="relative">
        <label for="toggle-filter"
          class="absolute top-0 right-0 hover:text-blue-200 hover:bg-white/10 rounded-lg cursor-pointer lg:hidden">
          <x-icons.x />
        </label>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1" wire:loading.class="pointer-events-none">
        <div class="px-4 flex flex-col gap-1 max-h-52 overflow-y-auto scroll-smooth scrollbar-thin"
          style="scrollbar-color: white transparent">
          <h4 class="w-full py-1 px-3 mb-2 text-lg font-semibold text-center border-b border-white/20 lg:text-center">
            Categorias
          </h4>
          @foreach ($categoriesProduct as $category)
            <x-inputs.checkbox wire:model.live.debounce.250ms="selectedCategories" wire:loading.attr="disabled"
              wire:key="category-{{ $category->id }}" value="{{ $category->id }}" :labelClass="'font-semibold'" :labelFor="'checkbox_c' . $category->id">
              {{ $category->name }}
            </x-inputs.checkbox>
          @endforeach
        </div>
        <div class="px-4 flex flex-col gap-1 max-h-52 overflow-y-auto scroll-smooth scrollbar-thin"
          style="scrollbar-color: white transparent">
          <h4 class="w-full py-1 px-3 mb-2 text-lg font-semibold text-center border-b border-white/20 lg:text-center">
            Marcas
          </h4>
          @foreach ($brandsProducts as $brand)
            <x-inputs.checkbox wire:model.live.debounce.250ms="selectedBrands" wire:loading.attr="disabled"
              wire:key="brand-{{ $brand->id }}" value="{{ $brand->id }}" :labelClass="'font-semibold'" :labelFor="'checkbox_b' . $brand->id">
              {{ $brand->name }}
            </x-inputs.checkbox>
          @endforeach
        </div>
      </div>
    </aside>
    <section
      class="group grid [.grilla]:grid-cols-[repeat(auto-fill,minmax(264px,1fr))] [.list]:grid-cols-1 justify-items-center gap-5 lg:grow {{ $selectedDisplay }}">
      @foreach ($products as $product)
        <x-cards.product :product="$product" :offers="$offers" />
      @endforeach
    </section>
  </section>
</div>
