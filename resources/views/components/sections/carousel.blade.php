@props(['length' => 0, 'is_image' => false])

@php
  $idCarousel = 'carousel-' . uniqid();
@endphp

@push('styles-app')
  <style>
    .{{ $idCarousel }} {
      --index: 0;
      --slider-size: 100%;
      --sliders: {{ $is_image ? $length : $length - 1 }};

      @for ($i = 0; $i < $length; $i++)
        &:has(.cr{{ $i }}:checked) {
          --index: {{ $i }};
        }
      @endfor
      .carousel-track {
        transform: translateX(calc(var(--index) * var(--slider-size) * -1 / var(--sliders)));

        @if ($is_image)
          width: calc(var(--sliders) * 100%);

          &>li {
            width: calc(1 / var(--sliders) * 100%);
          }
        @endif
      }
    }
  </style>
@endpush

{{-- Aplicar a carousel.js y hacerlo que cicle infinitamente en x-tiempo --}}
@pushOnce('scripts-app')
  <script type="module">
    let timeout;
    const carousels = document.querySelectorAll('section[class*="carousel-"]');

    carousels.forEach(carousel => {
      const sliders = carousel.dataset.sliders;
      const inputs = Array.from(carousel.querySelectorAll('input[type="radio"]'));
      const buttons = carousel.querySelectorAll('button[data-control]');

      let index = inputs.findIndex(input => input.checked)

      inputs.forEach(input => {
        input.addEventListener('change', () => {
          // Actualiza el index cada vez que se cambia desde el label de radio
          // Necesario para que el index se actualice correctamente cuando se cambia desde el botón de control
          index = inputs.findIndex(input => input.checked);
        });
      });

      buttons.forEach(button => {
        button.addEventListener('click', e => {
          index += +e.currentTarget.dataset.control;

          if (index < 0) index = sliders - 1;
          else if (index > (sliders - 1)) index = 0;

          inputs[index].checked = true;
        })
      })

      if (!carousel.dataset.image) {
        const track = carousel.querySelector('.carousel-track')
        let sliderSize = track.clientWidth - track.parentElement.clientWidth
        carousel.style.setProperty('--slider-size', sliderSize > 0 ? sliderSize + 'px' : '0px');

        window.addEventListener('resize', () => {
          clearTimeout(timeout);
          timeout = setTimeout(() => {
            sliderSize = track.clientWidth - track.parentElement.clientWidth
            carousel.style.setProperty('--slider-size', sliderSize > 0 ? sliderSize + 'px' : '0px');
          }, 300)
        })
      }
    })
  </script>
@endpushOnce

<section {{ $attributes->merge(['class' => $idCarousel . ' mb-7']) }} data-sliders="{{ $length }}"
  data-image="{{ $is_image }}">
  <div class="relative overflow-hidden">
    <ul @class([
        'carousel-track w-max relative flex flex-nowrap transition-transform duration-600 ease-out',
        'gap-8 px-8 mx-auto' => !$is_image,
    ])>
      {{ $slot }}
    </ul>
    <button data-control="-1"
      class="absolute top-1/2 -translate-y-1/2 left-1 z-10 p-3 rounded-full bg-slate-200 cursor-pointer shadow-white hover:shadow-md/70">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
        <path d="M13.939 4.939L6.879 12l7.06 7.061l2.122-2.122L11.121 12l4.94-4.939z" fill="currentColor" />
      </svg>
    </button>
    <button data-control="1"
      class="absolute top-1/2 -translate-y-1/2 right-1 z-10 p-3 rounded-full bg-slate-200 cursor-pointer shadow-white hover:shadow-md/70">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
        <path d="M10.061 19.061L17.121 12l-7.06-7.061l-2.122 2.122L12.879 12l-4.94 4.939z" fill="currentColor" />
      </svg>
    </button>
  </div>
  <div class="mt-3 flex justify-center gap-3">
    @for ($i = 0; $i < $length; $i++)
      <label class="p-2 bg-slate-400 rounded-lg cursor-pointer hover:bg-slate-600 has-checked:bg-slate-600">
        <input type="radio" name="{{ $idCarousel }}" class="hidden cr{{ $i }}"
          @checked($i == 0) />
      </label>
    @endfor
  </div>
</section>
