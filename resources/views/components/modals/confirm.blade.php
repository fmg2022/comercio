@props(['id', 'title'])

<dialog id="{{ $id }}"
  {{ $attributes->merge(['class' => 'backdrop:bg-purple-900/35 backdrop:backdrop-blur-sm rounded-md overflow-hidden top-1/2 left-1/2 -translate-1/2']) }}
  closedby="any">
  <div class="relative flex flex-col gap-4 p-4">
    <h2 class="mt-3 text-xl text-center text-purple-900 font-semibold">{{ $title }}</h2>
    <form method="dialog">
      <button class="absolute top-3 right-3 p-1 text-slate-500">
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
        </svg>
      </button>
    </form>
    {{ $slot }}
  </div>
</dialog>
