@props(['classTitle' => ''])

<div {{ $attributes->merge(['class' => 'px-5 mb-6']) }}>
  <h1 class="text-2xl font-bold {{ $classTitle }}">
    {{ $textTitle }}
  </h1>
  {{ $slot }}
</div>
