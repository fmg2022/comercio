document.addEventListener('DOMContentLoaded', function () {
  const $modal = document.getElementById('modal-product-mix')
  // Obtener todos los botones con el atributo data-id y data-show
  const buttons = document.querySelectorAll('button[data-id][data-show]')
  // Iterar sobre los botones y agregar un evento click a cada uno
  buttons.forEach(button => {
    button.addEventListener('click', (ev) => {
      ev.preventDefault()
      const id = button.getAttribute('data-id')
      const show = button.getAttribute('data-show')

      axios.get('/api/products/' + id)
        .then(response => {
          const $form = $modal.querySelector('#form-product-mix')
          let url = window.location.href

          // Si la URL contiene parámetros de búsqueda, los eliminamos
          if ((/\?\w+/).test(url)) {
            url = url.replace(window.location.search, '')
          }

          $form.action = url + '/' + id

          // Toggle de la clase editable
          if (show === 'true') {
            $form.classList.remove('editable')
          } else {
            $form.classList.add('editable')
          }

          const $img = $modal.querySelector('img')
          $img.src = $img.src.replace(/([a-z]{2}_\w+\.webp)$/i, response.data.image)
          $img.alt = response.data.name

          const divs = $modal.querySelectorAll('fieldset > div')

          let $input = divs[0].querySelector('input')
          $input.value = response.data.name
          $input.disabled = show === 'true'

          divs[0].querySelector('label').textContent = 'Nombre'

          $input = divs[1].querySelector('input')
          $input.value = response.data.mark
          $input.disabled = show === 'true'

          divs[1].querySelector('label').textContent = 'Marca'

          $input = divs[2].querySelector('input')
          $input.value = response.data.price
          $input.disabled = show === 'true'

          divs[2].querySelector('label').textContent = 'Precio'

          $input = divs[3].querySelector('input')
          $input.value = response.data.quantity
          $input.disabled = show === 'true'

          divs[3].querySelector('label').textContent = 'Cantidad'

          $input = divs[4].querySelector('input')
          $input.value = response.data.sku
          $input.disabled = show === 'true'

          divs[4].querySelector('label').textContent = 'SKU'

          divs[5].querySelector('select').disabled = show === 'true'
          divs[5].querySelectorAll('select > option').forEach(option => {
            if (option.value == response.data.category_id) {
              option.setAttribute('selected', '')
            } else {
              option.removeAttribute('selected')
            }
          })

          $input = divs[6].querySelector('textarea')
          $input.value = response.data.description
          $input.disabled = show === 'true'

          divs[6].querySelector('label').textContent = 'Descripción'

          $modal.showModal()
        })
        .catch(error => {
          console.error('Error:', error)
          alert(error.message)
        })
    })
  })

})
