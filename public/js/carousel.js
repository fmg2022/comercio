/**
 * Antes y después del slot:

<ul class="carousel-track ...">
  {{-- Clon del último --}}
  {{ $slot[$length - 1] }}

  {{-- Originales --}}
  {{ $slot }}

  {{-- Clon del primero --}}
  {{ $slot[0] }}
</ul>
 * 

 *Empezar en índice 1
let index = 1;
inputs[index].checked = true;

** EMPEZAR EN **
if (index < 0) index = sliders - 1;
else if (index > (sliders - 1)) index = 0;
** FIN EMPEZAR EN **

 * const track = carousel.querySelector('.carousel-track');

function moveTo(i) {
  track.style.transition = 'transform 0.6s ease-out';
  index = i;
  inputs[index].checked = true;
}

track.addEventListener('transitionend', () => {
  // Si estamos en el clon del final
  if (index === sliders + 1) {
    track.style.transition = 'none';
    index = 1;
    inputs[index].checked = true;
  }

  // Si estamos en el clon del inicio
  if (index === 0) {
    track.style.transition = 'none';
    index = sliders;
    inputs[index].checked = true;
  }
});

 * Botones
button.addEventListener('click', e => {
  moveTo(index + +e.currentTarget.dataset.control);
});

 * Autoplay infinito
let autoplay = setInterval(() => {
  moveTo(index + 1);
}, 3000);

carousel.addEventListener('mouseenter', () => clearInterval(autoplay));
carousel.addEventListener('mouseleave', () => {
  autoplay = setInterval(() => moveTo(index + 1), 3000);
});
 */