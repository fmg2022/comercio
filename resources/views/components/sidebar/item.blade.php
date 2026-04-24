@props(['section' => '', 'route' => '', 'isActive', 'textClass' => ''])

<li @class([
    'rounded-md hover:bg-slate-700 hover:text-purple-500 transition-colors duration-200',
    'bg-slate-700 text-purple-500' => $isActive,
])>
  <a href="{{ $route === '#' ? '#' : route($route) }}" class="px-3 py-2 flex items-center gap-3"
    data-section="{{ $section }}">
    <span>
      {{ $icon }}
    </span>
    <span
      class="text-nowrap xl:opacity-0 xl:invisible group-hover:opacity-100 group-hover:visible transition-opacity duration-500 ease-in-out check-visible">{{ $title }}</span>
  </a>
</li>
