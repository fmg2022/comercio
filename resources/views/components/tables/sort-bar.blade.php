@props(['sortStatus', 'extraTarget' => ''])

@php
  $extraTarget = $extraTarget ? ', ' . $extraTarget : '';
@endphp

<div class="px-4 py-3 flex flex-wrap items-center justify-between gap-3 rounded-t-xl bg-slate-800 sm:px-6">
  <p class="text-sm text-slate-300" wire:loading.remove wire:target="sort, resetSort">{{ $sortStatus }}</p>
  <p class="text-sm text-slate-300" wire:loading wire:target="sort, resetSort">
    <span class="inline-flex items-center gap-2">
      <x-icons.animate.spinner class="size-4" />
      Ordenando…
    </span>
  </p>
  <div class="flex gap-4 items-center justify-end">
    {{ $slot }}
    <button type="button" wire:click="resetSort" wire:loading.attr="disabled"
      wire:target="sort, resetSort{{ $extraTarget }}"
      class="cursor-pointer rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 disabled:cursor-wait disabled:opacity-60">
      Restablecer
    </button>
  </div>
</div>
