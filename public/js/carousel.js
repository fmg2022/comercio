export default function carousel(carousel, btns) {
  const $carousel = document.querySelector(carousel)
  const $btns = document.querySelectorAll(btns + ' button')
  const itemWidth = $carousel.querySelector('.item').offsetWidth
  const carouselChildren = [...$carousel.children]

  let isDragging = false, startX, starScrollLeft
  let itemsPedrView = Math.round($carousel.offsetWidth / itemWidth)

  // Copia en el principio el último elemento del carrusel
  carouselChildren.slice(-itemsPedrView).reverse().forEach(item => {
    $carousel.insertAdjacentHTML('afterbegin', item.outerHTML)
  })

  // Copia en el principio el último elemento del carrusel
  carouselChildren.slice(0, itemsPedrView).forEach(item => {
    $carousel.insertAdjacentHTML('beforeend', item.outerHTML)
  })

  $btns.forEach(btn => {
    btn.addEventListener('click', () => {
      $carousel.scrollLeft += btn.id === btns.replace('#', '')+'Prev' ? -itemWidth : itemWidth
      console.log(itemWidth);
    })
  })

  const dragStart = (e) => {
    isDragging = true
    $carousel.classList.add('dragging')
    startX = e.pageX
    starScrollLeft = $carousel.scrollLeft
  }

  const dragging = (e) => {
    if (!isDragging) return
    $carousel.scrollLeft = starScrollLeft - (e.pageX - startX)
  }

  const dragStop = () => {
    isDragging = false
    $carousel.classList.remove('dragging')
  }

  const infiniteScroll = (e) => {
    if ($carousel.scrollLeft === 0) {
      $carousel.classList.replace('scroll-smooth', 'scroll-auto')
      $carousel.scrollLeft = $carousel.scrollWidth -(2 * $carousel.offsetWidth)
      $carousel.classList.replace('scroll-auto', 'scroll-smooth')
    } else if (Math.ceil($carousel.scrollLeft) === $carousel.scrollWidth - $carousel.offsetWidth) {
      $carousel.classList.replace('scroll-smooth', 'scroll-auto')
      $carousel.scrollLeft = $carousel.offsetWidth
      $carousel.classList.replace('scroll-auto', 'scroll-smooth')
    }
  }
  
  $carousel.addEventListener('mousedown', dragStart)
  $carousel.addEventListener('mousemove', dragging)
  $carousel.addEventListener('mouseup', dragStop)
  $carousel.addEventListener('scroll', infiniteScroll)
}