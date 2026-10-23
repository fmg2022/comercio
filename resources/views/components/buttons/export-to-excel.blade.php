{{-- Exportar a Excel:
  * Tener una función export() en livewire --}}
<button type="button" wire:click="export" wire:loading.attr="disabled" wire:target="export"
  class="px-3 py-2.5 rounded-lg font-semibold bg-green-700 hover:bg-green-600 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
  <span wire:loading.remove wire:target="export">Exportar a Excel</span>
  <span wire:loading wire:target="export" wire:loading.flex class="gap-3 items-center">
    Preparando...
    <x-icons.animate.spinner class="size-6" />
  </span>
</button>
