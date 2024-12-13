@props(['link'])

@php
$link = $link ?? null;
@endphp

<a href="{{ $link ? route($link) : '/' }}" class="hover:text-purple-600 transition-colors">
  {{ $slot }}
</a>