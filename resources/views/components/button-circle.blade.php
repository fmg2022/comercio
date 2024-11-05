@props(['id' => ''])

<button id="{{ $id }}" class="size-12 bg-slate-50/60 rounded-full cursor-pointer flex justify-center items-center">
  {{ $slot }}
</button>