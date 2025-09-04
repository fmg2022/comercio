@props(['classLabel' => ''])

<div class="relative w-11 h-5">
  <input
    {{ $attributes->merge(['type' => 'checkbox', 'id' => 'switch-default', 'class' => 'peer/switch w-11 h-5 rounded-full transition-colors duration-300 appearance-none']) }}>
  <label for="switch-default"
    class="absolute left-0 top-0 size-5 rounded-full border shadow-sm transition-transform duration-300 peer-checked/switch:translate-x-6 {{ $classLabel }}"></label>
</div>
