document.addEventListener('DOMContentLoaded', function () {
  // Obtener todos los botones con el atributo data-id y data-show
  const buttons = document.querySelectorAll('button[data-id][data-show]')
  const $modal = document.getElementById('modal-product-mix')
  const $form = $modal.querySelector('#form-product-mix')
  const labelNames = { 'name': 'Nombre', 'mark': 'Marca', 'price': 'Precio', 'quantity': 'Cantidad', 'sku': 'SKU', 'category_id': 'Categoría', 'description': 'Descripción' }

  // Iterar sobre los botones y agregar un evento click a cada uno
  buttons.forEach(button => {
    button.addEventListener('click', (ev) => {
      ev.preventDefault()
      const id = button.getAttribute('data-id')
      const show = button.getAttribute('data-show')

      axios.get('/api/products/' + id)
        .then(response => {

          // Toggle de la clase editable
          if (show !== 'true') {
            $form.classList.add('editable')
            let url = window.location.href

            // Si la URL contiene parámetros de búsqueda, los eliminamos
            if ((/\?\w+/).test(url)) {
              url = url.replace(window.location.search, '')
            }
            $form.action = url + '/' + id
          } else {
            $form.classList.remove('editable')
            $form.action = ''
          }

          const $img = $modal.querySelector('img')
          $img.src = $img.src.replace(/([a-z]{2}_\w+\.webp)$/i, response.data.image)
          $img.alt = response.data.name

          const divs = $modal.querySelectorAll('fieldset > div')

          divs.forEach($div => {
            let $input = $div.querySelector('input')
            if ($input) {
              $input.value = response.data[$input.name]
            } else if ($input = $div.querySelector('textarea')) {
              $input.value = response.data[$input.name]
            } else {
              $input = $div.querySelector('select')
              $input.querySelectorAll('option').forEach(option => {
                if (option.value == response.data[$input.name]) {
                  option.setAttribute('selected', '')
                } else {
                  option.removeAttribute('selected')
                }
              })
            }
            $input.disabled = show === 'true'
            $div.querySelector('label').textContent = labelNames[$input.name]
          })

          $modal.showModal()
        })
        .catch(error => {
          console.error('Error:', error)
          alert(error.message)
        })
    })
  })

})
