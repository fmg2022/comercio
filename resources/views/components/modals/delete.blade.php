@props(['iconClass' => ''])

<dialog
  {{ $attributes->merge(['class' => 'backdrop:bg-purple-900/35 backdrop:backdrop-blur-sm rounded-md overflow-hidden top-1/2 left-1/2 -translate-1/2']) }}
  closedby="any">
  <div class="relative flex flex-col gap-4 p-4">
    <form method="dialog" class="fixed top-3 right-3 w-fit">
      <button class="p-1 text-slate-500 cursor-pointer">
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
        </svg>
      </button>
    </form>
    <div class="flex flex-col items-center justify-center">
      <span class="my-6 {{ $iconClass }}">
        {{ $icon }}
      </span>
      <h2 class="mt-3 text-2xl text-center text-purple-900 font-semibold"></h2>
      <p class="px-2 py-4 mb-3 text-lg"></p>
    </div>
    <div class="flex justify-end gap-3 text-white">
      <form id="form-modalSimple" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="px-3 py-2 rounded-md cursor-pointer">Eliminar</button>
      </form>
      <form method="dialog">
        <button class="px-3 py-2 rounded-md bg-slate-700 hover:bg-slate-600 cursor-pointer">Cancelar</button>
      </form>
    </div>
  </div>
</dialog>
