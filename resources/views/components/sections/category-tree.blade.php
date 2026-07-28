@forelse ($categories as $category)
  @php
    $hasChildren = $category->children->isNotEmpty();
  @endphp
  <li class="border-b border-white/20">
    <input type="checkbox" id="cat-check{{ $category->id }}" class="hidden peer/categoryList">
    <label for="cat-check{{ $category->id }}" @class([
        'px-5 py-2 mb-2 flex items-center justify-between text-lg rounded-lg',
        'cursor-pointer hover:bg-sky-800/50' => $hasChildren,
    ])>
      <span>
        <x-buttons.link href="{{ route('product.index', ['query' => $category->name]) }}"
          class="block text-lg hover:text-sky-400">
          {{ $category->name }}
        </x-buttons.link>
      </span>
      @if ($hasChildren)
        <span class="p-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
              d="m7 10l5 5m0 0l5-5" />
          </svg>
        </span>
      @endif
    </label>
    @if ($hasChildren)
      <div class="px-8 mb-2 hidden bg-black/10 rounded-lg peer-checked/categoryList:block">
        <ul class="py-5 grid content-start gap-2">
          <x-sections.category-tree :categories="$category->children" />
        </ul>
      </div>
    @endif
  </li>
@empty
@endforelse
