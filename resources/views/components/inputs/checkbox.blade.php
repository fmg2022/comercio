<label for="{{ $labelFor }}"
  class="grid grid-flow-col items-center justify-start gap-2 text-sm select-none {{ $labelClass ?? '' }}">
  <div class="grid items-center justify-center">
    <input id="{{ $labelFor }}" {{ $attributes }}
      class="peer col-start-1 row-start-1 h-4 w-4 appearance-none rounded border border-slate-400 bg-slate-200 ring-transparent checked:border-purple-700 checked:bg-purple-600 disabled:bg-slate-300 disabled:border-slate-400"
      type="checkbox">
    <svg viewBox="0 0 14 14" fill="none"
      class="invisible col-start-1 row-start-1 stroke-white peer-checked:visible text-purple-300">
      <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
    </svg>
  </div>
  {{ $slot }}
</label>
