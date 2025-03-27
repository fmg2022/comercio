import carousel from "./carousel.js";

document.addEventListener("DOMContentLoaded", (e) => {
  carousel('#list-oferta', '#btns-oferta');
  carousel('#list-product', '#btns-product');
});

// Tiene mejor resultado con lo deseado
// https://www.youtube.com/watch?v=6QE8dXq9SOE&list=PLpwngcHZlPae68z_mLFNfbJFIJVJ_Zcx2&index=4

// Toggle theme icons
const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon')
const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon')

// Change the icons inside the button based on previous settings
if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia("(prefers-color-scheme: dark)").matches)) {
  themeToggleLightIcon.classList.remove('hidden')
} else {
  themeToggleDarkIcon.classList.remove('hidden')
}

// Logic for the theme toggle button
const themeToggleButton = document.getElementById('theme-toggle')
themeToggleButton.addEventListener('click', () => {
  // toggle icons inside button
  themeToggleDarkIcon.classList.toggle('hidden')
  themeToggleLightIcon.classList.toggle('hidden')

  // if set via local storage previously
  if (localStorage.theme) {
    if (localStorage.theme === 'light') {
      document.documentElement.classList.add('dark')
      localStorage.theme = 'dark'
    } else {
      document.documentElement.classList.remove('dark')
      localStorage.theme = 'light'
    }

    // if NOT set via local storage previously
  } else {
    if (document.documentElement.classList.contains('dark')) {
      document.documentElement.classList.remove('dark')
      localStorage.theme = 'light'
    } else {
      document.documentElement.classList.add('dark')
      localStorage.theme = 'dark'
    }
  }

})

document.addEventListener('DOMContentLoaded', () => {
  const $ul = document.querySelector('#list-categories')
  fetch('/fetch-categories')
  .then(res => res.json())
  .then(data => {
    const $template = document.getElementById('li-categoria').content
    const fragment = document.createDocumentFragment()
    const categories = data.filter(dic => dic.parent_id === null)

    // ciclo de la categoría principal
    categories.forEach(category => {
      // crear el elemento de la categoría
      const li = document.importNode($template, true)
      const button = li.querySelector('button')

      button.dataset.catid = category.id
      li.querySelector('span').innerText = category.name

      // button.addEventListener('click', openaSubCategories)

      // Obtener las subcategorias 1° nivel
      const subc = data.filter(dic => dic.parent_id == category.id)

      // Hay subcategorias?
      if (subc.length > 0) {
        const fragment2 = document.createDocumentFragment()

        // ciclo de la subcategoria 1° nivel
        subc.forEach(sc => {
          const sec = document.createElement('section')
          const $a = document.createElement('a')

          sec.classList.add('px-3', 'py-1', 'flex', 'flex-col')

          $a.innerText = sc.name
          $a.href = `/categoria/${sc.id}`
          $a.classList.add('mb-2', 'text-lg', 'font-semibold', 'hover:text-purple-500')

          sec.appendChild($a)
          // Obtener las subcategorias 2° nivel
          const subcc = data.filter(dic => dic.parent_id == sc.id)

          // // Hay subcategorias?
          if (subcc.length > 0) {
            const fragment3 = document.createDocumentFragment()

            //   // ciclo de la subcategoria 2° nivel
            subcc.forEach(scc => {
              const $a = document.createElement('a')
              $a.innerText = scc.name
              $a.href = `/categoria/${scc.id}`
              $a.classList.add('hover:text-purple-500')

              fragment3.appendChild($a)
            })
            sec.appendChild(fragment3)
          }
          fragment2.appendChild(sec)
        })
        li.querySelector('div').appendChild(fragment2)
      }
      fragment.appendChild(li)
    })
    $ul.appendChild(fragment)
  })
})