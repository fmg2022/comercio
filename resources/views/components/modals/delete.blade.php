@props(['iconClass' => ''])

<dialog
  {{ $attributes->merge(['class' => 'backdrop:bg-purple-900/35 backdrop:backdrop-blur-sm rounded-md overflow-hidden top-1/2 left-1/2 -translate-1/2 max-w-md']) }}
  closedby="any">
  <div class="relative flex flex-col gap-4 p-4">
    <form method="dialog" class="fixed top-3 right-3 w-fit">
      <button class="p-1 text-slate-500 cursor-pointer">
        <x-icons.x />
      </button>
    </form>
    <div class="flex flex-col items-center justify-center">
      <span class="my-6 text-slate-500">
        <x-icons.exclamation class="size-28" />
      </span>
      <div class="px-3 mb-6 text-lg text-slate-700">
        <p>
          ¿Está seguro de que quieres <b id="form-type" class="uppercase"></b>:
        </p>
        <p class="text-center">
          <span id="form-text" class="pe-2 text-xl font-bold text-slate-800"></span>?
        </p>
      </div>
    </div>
    <div class="flex justify-end gap-3 text-white">
      <form id="form-modalSimple" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="px-3 py-2 rounded-md cursor-pointer"></button>
      </form>
      <form method="dialog">
        <button class="px-3 py-2 rounded-md bg-slate-700 hover:bg-slate-600 cursor-pointer">Cancelar</button>
      </form>
    </div>
  </div>
</dialog>
