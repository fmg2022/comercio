document.addEventListener('DOMContentLoaded', function () {
  // fetch('/api/products/' + 5)
  //   .then(response => {
  //     if (!response.ok) {
  //       throw new Error(response.statusText)
  //     }
  //     return response.json()
  //   })
  //   .then(data => {
  //     console.log(data)
  //   })
  //   .catch(error => {
  //     console.error('Error:', error)
  //     alert(error.message)
  //   })
  // Obtener todos los botones con el atributo data-id y data-show
  const buttons = document.querySelectorAll('button[data-id][data-show]')
  // Iterar sobre los botones y agregar un evento click a cada uno
  buttons.forEach(button => {
    button.addEventListener('click', (ev) => {
      ev.preventDefault()
      const id = button.getAttribute('data-id')
      const show = button.getAttribute('data-show')
      const $modal = document.getElementById('modal-product-mix')

      axios.get('/api/products/' + id)
        .then(response => {
          const $form = $modal.querySelector('#form-product-mix')
          $form.action = window.location.href + '/' + id

          /** Limpiar formulario
           *  Elimina todos los hijos del formulario excepto los dos primeros (csrf y method)
           *  que son los campos ocultos para CSRF y el método HTTP.
           */
          while ($form.children.length > 2) {
            $form.removeChild($form.lastChild)
          }

          // Toggle de la clase editable
          if (show === 'true') {
            $form.classList.remove('editable')
          } else {
            $form.classList.add('editable')
          }

          const template = document.getElementById('modal-content').content

          // Agregar datos a la imagen
          const $img = template.querySelector('img')
          $img.src = $img.src.replace(/([a-z]{2}_\w+\.webp)$/i, response.data.image)
          $img.alt = response.data.name

          const divs = template.querySelectorAll('fieldset > div')

          let $input = divs[0].querySelector('input')
          $input.value = response.data.name
          $input.disabled = show === 'true'
          $input.setAttribute('name', 'name')
          $input.id = 'name'

          divs[0].querySelector('label').setAttribute('for', 'name')
          divs[0].querySelector('label').textContent = 'Nombre'

          $input = divs[1].querySelector('input')
          $input.value = response.data.mark
          $input.disabled = show === 'true'
          $input.setAttribute('name', 'mark')
          $input.id = 'mark'

          divs[1].querySelector('label').setAttribute('for', 'mark')
          divs[1].querySelector('label').textContent = 'Marca'

          $input = divs[2].querySelector('input')
          $input.value = response.data.price
          $input.disabled = show === 'true'
          $input.setAttribute('name', 'price')
          $input.id = 'price'

          divs[2].querySelector('label').setAttribute('for', 'price')
          divs[2].querySelector('label').textContent = 'Precio'

          $input = divs[3].querySelector('input')
          $input.value = response.data.quantity
          $input.disabled = show === 'true'
          $input.setAttribute('name', 'quantity')
          $input.id = 'quantity'

          divs[3].querySelector('label').setAttribute('for', 'quantity')
          divs[3].querySelector('label').textContent = 'Cantidad'

          divs[4].querySelector('select').disabled = show === 'true'
          divs[4].querySelectorAll('select > option').forEach(option => {
            if (option.value == response.data.category_id) {
              option.setAttribute('selected', '')
            } else {
              option.removeAttribute('selected')
            }
          })

          $input = divs[5].querySelector('textarea')
          $input.value = response.data.description
          $input.disabled = show === 'true'
          $input.setAttribute('name', 'description')
          $input.id = 'description'

          divs[5].querySelector('label').setAttribute('for', 'description')
          divs[5].querySelector('label').textContent = 'Descripción'

          $form.appendChild(template.cloneNode(true))

          $modal.showModal()
        })
        .catch(error => {
          console.error('Error:', error)
          alert(error.message)
        })
    })
  })

})
