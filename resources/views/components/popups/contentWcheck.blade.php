@props(['labelClass' => '', 'iid'])

<input type="checkbox" id="{{ $iid }}" class="hidden peer/checkOption" name="toggle-btns">
<label for="{{ $iid }}" class="inline-block p-1.5 rounded-full cursor-pointer {{ $labelClass }}">
  {{ $label ?? 'Opcion' }}
</label>
<div {{ $attributes->merge(['class' => 'absolute z-20 hidden peer-checked/checkOption:block']) }}>
  {{ $slot }}
</div>
