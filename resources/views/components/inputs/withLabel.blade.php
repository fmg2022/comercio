@push('scripts-dashboard')
  <script src="{{ asset('js/dashboard/labelInput.js') }}" defer></script>
@endpush

@props(['title', 'forLabel', 'containerClass' => ''])

<div class="form-item relative max-w-xs mt-6 {{ $containerClass }}">
  <input
    {{ $attributes->merge(['class' => 'peer/formItem w-full px-3 py-2 outline-none rounded-lg bg-white/10 focus:ring focus:ring-purple-500/50 transition-color']) }} />
  <label for="{{ $forLabel }}"
    class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 transition-[top] peer-focus/formItem:-top-4 data-active:-top-4">
    {{ $title }}
  </label>
</div>
