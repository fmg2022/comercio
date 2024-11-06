const list = document.querySelector('#list-oferta');
const items = list.querySelectorAll('.item');
const prevBtn = document.querySelector('#prev-oferta');
const nextBtn = document.querySelector('#next-oferta');

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

let autoMove = setInterval(() => nextBtn.click(), 5000)

function moveSlider() {
  let itemCheck = items[current].offsetLeft
  list.style.left = `-${itemCheck}px`

  clearInterval(autoMove)
  autoMove = setInterval(() => nextBtn.click(), 5000)
}


const list2 = document.querySelector('#list-product');
const items2 = list.querySelectorAll('.item');
const prevBtn2 = document.querySelector('#prev-product');
const nextBtn2 = document.querySelector('#next-product');

let current2 = 0;
let lenght2 = items2.length - 1;

nextBtn2.addEventListener('click', () => {
  if (current2 + 1 > lenght2) {
    current2 = 0
  } else {
    current2++
  }
  moveSlider2()
})
prevBtn2.addEventListener('click', () => {
  if (current2 - 1 < 0) {
    current2 = lenght2
  } else {
    current2--
  }
  moveSlider2()
})

function moveSlider2() {
  let itemCheck2 = items2[current2].offsetLeft
  list2.style.left = `-${itemCheck2}px`

  console.log(items2[current2])
}
