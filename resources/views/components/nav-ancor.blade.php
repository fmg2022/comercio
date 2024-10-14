@props(['link'])

@php
  $link = $link ?? null;
@endphp

<a
  href="{{ $link ? route($link) : '/' }}"
  class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#ff7920] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
  {{ $slot }}
</a>