@props(['forLabel'])

<label for="{{ $forLabel }}"
  class="absolute top-4 right-4 z-50 cursor-pointer text-slate-400 hover:text-gray-500 focus:outline-none focus:bg-gray-500 focus:text-gray-400">
  <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
  </svg>
</label>