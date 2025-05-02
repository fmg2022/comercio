@props(['title'])

<label class="flex flex-col items-center gap-5">
  <span class="me-auto">{{ $title }}:</span>
  <input {{ $attributes->merge(['class' => 'w-full max-w-xs px-3 py-2 outline-none rounded-lg bg-white/10']) }}>
</label>