@props(['offers'])

<section class="p-3">
  <div class="flex w-full h-[80dvh] overflow-x-scroll snap-x snap-mandatory">
    @foreach ($offers as $offer)
      <img src="{{ $offer->name }}" alt="Offer" class="flex-[0_0_100%] w-full snap-center object-cover">
    @endforeach
  </div>
</section>