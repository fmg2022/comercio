document.addEventListener('DOMContentLoaded', function () {
  const textLabels = { 'name': 'Nombre', 'surname': 'Apellido', 'email': 'Email', 'phone': 'Teléfono' }
  // Obtener todos los botones con el atributo data-id y data-show
  const buttons = document.querySelectorAll('button[data-id][data-show]')
  const $modal = document.getElementById('modal-user-mix')
  const $form = $modal.querySelector('#form-user-mix')

  // Iterar sobre los botones y agregar un evento click a cada uno
  buttons.forEach(button => {
    button.addEventListener('click', (ev) => {
      ev.preventDefault()
      const id = button.getAttribute('data-id')
      const show = button.getAttribute('data-show')

      axios.get('/api/users/' + id)
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

          if (!$img.src.includes("sin_foto.webp")) {
            $img.src = $img.src.replace("sin_foto.webp", response.data.image)
          }
          $img.alt = response.data.name

          const divs = $modal.querySelectorAll('fieldset > div')

          divs.forEach($div => {
            const $input = $div.querySelector('input')
            $input.value = response.data[$input.name]
            $input.disabled = show === 'true'

            $div.querySelector('label').textContent = textLabels[$input.name]
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
