@props([
    'field' => '',
    'name' => '',
    'sortBy' => null,
    'sortDir' => null,
])

@php
  $sortField = $field !== '' ? $field : $name;
  $isActive = $sortField !== '' && $sortBy === $sortField;
  $currentDir = $isActive ? strtolower((string) $sortDir) : null;
  $currentDir = in_array($currentDir, ['asc', 'desc'], true) ? $currentDir : null;
  $ariaSort = $isActive ? ($currentDir === 'asc' ? 'ascending' : 'descending') : 'none';
  $nextDirLabel = $isActive && $currentDir === 'asc' ? 'descendente' : 'ascendente';
@endphp

@pushOnce('styles-dashboard')
  <style>
    .sort-btn::after {
      content: "";
      box-sizing: content-box;
      flex: none;
      width: 0;
      height: 0;
      margin-left: 0.4rem;
      border-left: 4px solid transparent;
      border-right: 4px solid transparent;
      border-top: 5px solid currentColor;
      opacity: 0.28;
      transition: transform 0.18s ease, opacity 0.18s ease;
    }

    .sort-btn:hover::after {
      opacity: 0.55;
    }

    .sort-btn[data-dir="asc"]::after {
      opacity: 1;
      transform: rotate(0deg);
    }

    .sort-btn[data-dir="desc"]::after {
      opacity: 1;
      transform: rotate(180deg);
    }

    @media (prefers-reduced-motion: reduce) {
      .sort-btn::after {
        transition: none;
      }
    }
  </style>
@endPushOnce

<th scope="col" aria-sort="{{ $ariaSort }}"
  {{ $attributes->merge(['class' => 'p-0 font-semibold transition-colors']) }}>
  <button type="button" wire:click="sort('{{ $sortField }}')" wire:loading.attr="disabled" wire:target="sort"
    data-active="{{ $isActive ? 'true' : 'false' }}" data-dir="{{ $currentDir ?? '' }}"
    title="Ordenar {{ $nextDirLabel }}"
    class="sort-btn px-3 py-4 w-full flex items-center text-left font-semibold uppercase tracking-wider cursor-pointer transition-colors hover:bg-slate-700 hover:text-slate-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-indigo-500 data-[active=true]:bg-slate-700 data-[active=true]:text-indigo-300 disabled:cursor-wait disabled:opacity-70">
    {{ $slot }}
  </button>
</th>
