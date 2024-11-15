@props(['link'])

@php
$link = $link ?? null;
@endphp

<a href="{{ $link ? route($link) : '/' }}" class="rounded-md px-3 py-2 dark:text-white dark:hover:text-white/45">
  {{ $slot }}
</a>