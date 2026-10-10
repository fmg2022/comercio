<div x-data="{
    show: false,
    message: '',
    type: 'info',
    timeout: null,
    progress: 100,
    duration: 5000,

    notify(message, type = 'info', duration = 5000) {
        this.message = message;
        this.type = type;
        this.duration = duration;
        this.progress = 100;
        this.show = true;

        clearTimeout(this.timeout);

        this.$nextTick(() => { requestAnimationFrame(() => { this.progress = 0; }); });

        this.timeout = setTimeout(() => {
            this.close();
        }, duration);
    },

    close() {
        this.show = false;
        clearTimeout(this.timeout);
    }
}" x-init="@if(session('notification'))
notify(
    @js(session('notification.message')),
    @js(session('notification.type', 'info')),
    5000
);
@endif"
  x-on:notification.window="
        notify(
            $event.detail.message,
            $event.detail.type ?? 'info',
            $event.detail.duration ?? 5000
        )
    "
  class="fixed top-5 right-5 z-9999 w-full max-w-sm">
  <template x-if="show">
    <div x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-5"
      x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-5"
      class="overflow-hidden rounded-lg border bg-white shadow-lg"
      :class="{
          'border-green-200': type === 'success',
          'border-red-200': type === 'error',
          'border-yellow-200': type === 'warning',
          'border-blue-200': type === 'info'
      }">
      <div class="flex items-start gap-3 p-4"
        :class="{
            'bg-green-50 text-green-800': type === 'success',
            'bg-red-50 text-red-800': type === 'error',
            'bg-yellow-50 text-yellow-800': type === 'warning',
            'bg-blue-50 text-blue-800': type === 'info'
        }">
        <div class="mt-0.5 shrink-0">
          <template x-if="type === 'success'">
            <x-icons.success class="size-5 text-green-600" />
          </template>

          <template x-if="type === 'error'">
            <x-icons.error class="size-5 text-red-600" />
          </template>

          <template x-if="type === 'warning'">
            <x-icons.warning class="size-5 text-yellow-600" />
          </template>

          <template x-if="type === 'info'">
            <x-icons.info class="size-5 text-blue-600" />
          </template>
        </div>

        <div class="min-w-0 flex-1">
          <p class="text-sm font-medium" x-text="message"></p>
        </div>

        <button type="button" @click="close()" class="shrink-0 rounded-md p-1 transition hover:bg-black/10"
          aria-label="Cerrar notificación">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      {{-- Barra de tiempo --}}
      <div class="h-1 bg-black/5">
        <div class="h-full transition-[width] linear"
          :class="{
              'bg-green-500': type === 'success',
              'bg-red-500': type === 'error',
              'bg-yellow-500': type === 'warning',
              'bg-blue-500': type === 'info'
          }"
          :style="`width: ${progress}%; transition-duration: ${duration}ms;`"></div>
      </div>
  </template>
</div>
