@props(['target'])

{{-- Es necesario tener un parent por lo menos con la clase "relative" para que funcione --}}
<div wire:loading.flex wire:target="{{ $target }}"
  class="absolute inset-0 z-10 items-center justify-center gap-2 font-bold rounded-b-xl bg-slate-900/60 text-slate-200">
  <x-icons.animate.spinner class="size-6" />
  Cargando...
</div>
