@props(['color' => 'purple'])

<div
  class="p-5 mx-auto mb-4 bg-gradient-to-b from-{{ $color }}-800 to-{{ $color }}-300 border border-{{ $color }}-700 rounded-md">
  <img {{ $attributes->merge(['class' => 'size-40 object-cover rounded-sm']) }}>
</div>
