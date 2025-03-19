@props(['title', 'items'])

<section class="mb-14">
  <h2 class="py-3 mb-4 text-2xl font-bold text-center">{{ $title }}</h2>
  <div class="flex flex-wrap items-center justify-center gap-6 py-4 px-2">
    @foreach ($items as $item)
    <a class="flex flex-col items-center justify-center rounded-sm text-center hover:shadow-md hover:shadow-slate-600"
      href="#">
      <img src="{{ asset('images/categories/1.webp') }}" alt="{{ $item->name }}"
        class="size-28 rounded-sm aspect-square">
      <span class="font-bold py-2">{{ $item->name }}</span>
    </a>
    @endforeach
  </div>
  {{ $slot }}
</section>