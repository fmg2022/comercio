@props(['title' => '', 'titleClass' => ''])

<dialog
  {{ $attributes->merge(['class' => 'backdrop:bg-purple-900/35 backdrop:backdrop-blur-sm rounded-md overflow-hidden top-1/2 left-1/2 -translate-1/2']) }}
  closedby="any">
  <div class="relative flex flex-col items-center gap-4 p-4">
    @if ($title)
      <h2 class="mt-3 text-2xl text-center text-purple-900 font-semibold {{ $titleClass }}">{{ $title }}</h2>
    @endif
    <form method="dialog" class="fixed top-3 right-3 w-fit">
      <button class="p-1 text-slate-500 cursor-pointer">
        <x-icons.x />
      </button>
    </form>
    {{ $slot }}
  </div>
</dialog>
