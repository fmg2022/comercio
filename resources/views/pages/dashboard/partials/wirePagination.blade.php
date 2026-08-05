@if ($paginator->hasPages())
  <nav class="px-5 py-6 bg-slate-800 rounded-b-md" role="navigation" aria-label="Pagination Navigation">
    <ul class="w-full flex gap-4 border-t border-slate-700 [&>li>a]:pt-3 sm:gap-10">
      {{-- Botón Anterior --}}
      <li class="flex items-center justify-start">
        <button wire:click="previousPage" @if ($paginator->onFirstPage()) disabled @endif
          x-on:click="   ($el.closest('body') || document.querySelector('body')).scrollIntoView()"
          class="me-3 flex items-center gap-3 text-xs cursor-pointer xl:text-lg {{ $paginator->onFirstPage() ? 'opacity-75 pointer-events-none' : 'hover:text-purple-500' }}"
          rel="prev">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
            <path fill="currentColor"
              d="m4.836 12l6.207 6.207l1.414-1.414L7.664 12l4.793-4.793l-1.414-1.414zm5.65 0l6.207 6.207l1.414-1.414L13.314 12l4.793-4.793l-1.414-1.414z" />
          </svg>
          Anterior
        </button>
      </li>

      {{-- Números de página --}}
      <li
        class="grow flex justify-center items-center gap-2 [&>button]:px-2.5 [&>button]:border-t-2 [&>button]:border-transparent [&>button.active]:border-purple-800 [&>button.active]:text-purple-500 [&>button]:transition-colors [&>button]:hover:text-purple-500 [&>button]:cursor-pointer">
        @foreach ($elements as $element)
          {{-- Separador "..." --}}
          @if (is_string($element))
            <span aria-disabled="true" class="h-full flex items-end">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 16 16">
                <path fill="currentColor"
                  d="M3 9.5a1.5 1.5 0 1 1 0-3a1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3a1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3a1.5 1.5 0 0 1 0 3" />
              </svg>
            </span>
          @endif

          {{-- Enlaces de página --}}
          @if (is_array($element))
            @foreach ($element as $page => $url)
              @if ($page == $paginator->currentPage())
                <button wire:click="gotoPage({{ $page }})"
                  x-on:click="   ($el.closest('body') || document.querySelector('body')).scrollIntoView()"
                  class="active pointer-events-none" aria-current="page">
                  {{ $page }}
                </button>
              @else
                <button wire:click="gotoPage({{ $page }})"
                  x-on:click="   ($el.closest('body') || document.querySelector('body')).scrollIntoView()">
                  {{ $page }}
                </button>
              @endif
            @endforeach
          @endif
        @endforeach
      </li>

      {{-- Botón Siguiente --}}
      <li class="flex items-center justify-end">
        <button wire:click="nextPage" @if ($paginator->onLastPage()) disabled @endif
          x-on:click="   ($el.closest('body') || document.querySelector('body')).scrollIntoView()"
          class="ms-3 flex items-center gap-3 text-xs cursor-pointer xl:text-lg {{ $paginator->onLastPage() ? 'opacity-75 pointer-events-none' : 'hover:text-purple-500' }}"
          rel="next">
          Siguiente
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
            <path fill="currentColor"
              d="m19.164 12l-6.207-6.207l-1.414 1.414L16.336 12l-4.793 4.793l1.414 1.414zm-5.65 0L7.307 5.793L5.893 7.207L10.686 12l-4.793 4.793l1.414 1.414z" />
          </svg>
        </button>
      </li>
    </ul>
  </nav>
@endif
