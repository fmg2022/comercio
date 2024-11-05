const list = document.querySelector('#list-oferta');
const items = list.querySelectorAll('.item');
const prevBtn = document.querySelector('#prev-oferta');
const nextBtn = document.querySelector('#next-oferta');

console.log(list, items, prevBtn, nextBtn);

let current = 0;
let lenght = items.length - 1;

nextBtn.addEventListener('click', () => {
  if (current + 1 > lenght) {
    current = 0
  } else {
    current++
  }
  moveSlider()
})

prevBtn.addEventListener('click', () => {
  if (current - 1 < 0) {
    current = lenght
  } else {
    current--
  }
  moveSlider()
})

// const autoMove = setInterval(() => nextBtn.click(), 5000)

function moveSlider() {
  let itemCheck = items[current].offsetLeft
  list.style.left = `-${itemCheck}px`

  // clearInterval(autoMove)
  // autoMove = setInterval(() => nextBtn.click(), 5000)
}