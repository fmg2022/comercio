@props(['label', 'title'])

@php
$class = 'fixed z-50 w-full h-screen overflow-auto p-8 bg-white dark:bg-gray-900 border-r border-gray-100
dark:border-gray-800 transition-all duration-300 ease-in-out';
@endphp

<aside {{ $attributes->merge(['class' => $class]) }}>
  <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 leading-tight text-center mb-4">{{ $title }}</h2>
  <x-label-check-X forLabel="{{ $label }}" />
  <ul class="grid content-center gap-3">
    {{ $slot }}
  </ul>
</aside>