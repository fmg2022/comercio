@if ($paginator->hasPages())
  <nav class="px-5 py-6 bg-slate-800 rounded-b-md">
    <ul class="w-full grid grid-cols-3 gap-4 border-t border-slate-700 [&>li>a]:pt-3">
      <li class="flex items-center justify-start">
        @php
          $previousPageUrl = !$paginator->onFirstPage() ? $paginator->previousPageUrl() : '#';
          $disabledStyle = $paginator->onFirstPage() ? 'opacity-75 pointer-events-none' : '';
        @endphp
        <a href="{{ $previousPageUrl }}" class="flex items-center gap-3 text-xs xl:text-lg {{ $disabledStyle }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
            <path fill="currentColor"
              d="m4.836 12l6.207 6.207l1.414-1.414L7.664 12l4.793-4.793l-1.414-1.414zm5.65 0l6.207 6.207l1.414-1.414L13.314 12l4.793-4.793l-1.414-1.414z" />
          </svg>
          Anterior
        </a>
      </li>
      <li
        class="flex items-start justify-center [&>a]:px-4 [&>a]:border-t-2 [&>a]:border-transparent [&>a.active]:border-purple-800 [&>a.active]:text-purple-500 [&>a]:transition-colors">
        @foreach ($elements as $element)
          {{-- "Three Dots" Separator --}}
          @if (is_string($element))
            <span aria-disabled="true">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 16 16">
                <path fill="currentColor"
                  d="M3 9.5a1.5 1.5 0 1 1 0-3a1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3a1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3a1.5 1.5 0 0 1 0 3" />
              </svg>
            </span>
          @endif

          {{-- Array Of Links --}}
          @if (is_array($element))
            @foreach ($element as $page => $url)
              @if ($page == $paginator->currentPage())
                <a href="#!" class="active pointer-events-none" aria-current="page">
                  {{ $page }}
                </a>
              @else
                <a href="{{ $url }}">
                  {{ $page }}
                </a>
              @endif
            @endforeach
          @endif
        @endforeach
      <li class="flex items-center justify-end">
        @php
          $nextPageUrl = $paginator->hasMorePages() ? $paginator->nextPageUrl() : '#';
          $disabledStyle = !$paginator->hasMorePages() ? 'opacity-75 pointer-events-none' : '';
        @endphp
        <a href="{{ $nextPageUrl }}" class="flex items-center gap-3 text-xs xl:text-lg {{ $disabledStyle }}">
          Siguiente
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
            <path fill="currentColor"
              d="m19.164 12l-6.207-6.207l-1.414 1.414L16.336 12l-4.793 4.793l1.414 1.414zm-5.65 0L7.307 5.793L5.893 7.207L10.686 12l-4.793 4.793l1.414 1.414z" />
          </svg>
        </a>
      </li>
    </ul>
  </nav>
@endif
