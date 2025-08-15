@props(['color' => 'purple'])

@php
  $colorClasses = [
      'purple' => 'from-purple-800 to-purple-300 border border-purple-700',
  ];
@endphp

<div class="p-5 mx-auto mb-4 bg-gradient-to-b {{ $colorClasses[$color] }} rounded-md">
  <img {{ $attributes->merge(['class' => 'size-40 object-cover rounded-sm']) }}>
</div>
