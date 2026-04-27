document.addEventListener('DOMContentLoaded', function () {
  const buttons = document.querySelectorAll('.button-create-edit-show')
  const $modal = document.getElementById(buttons[0].dataset.modalid)
  const $form = $modal.querySelector('form.group')
  const $submit = $form.querySelector('button[type="submit"]')
  const modalType = $modal.querySelector('#modelTypeid') // ID del select


  buttons.forEach(button => {
    button.addEventListener('click', (ev) => {
      ev.preventDefault()
      const type = button.dataset.type

      // Si la URL contiene parámetros de búsqueda, los eliminamos
      let url = window.location.href
      if ((/\?\w+/).test(url)) {
        url = url.replace(window.location.search, '')
      }
      // Actualiza action y método
      const formURL = `${url}/${button.dataset.path}`
      $form.action = formURL
      $form.querySelector('input[name="_method"]').value = type === 'edit' ? 'PUT' : 'POST'

      if (type === 'edit') {
        modalType.parentElement.classList.add('hidden')
        $submit.textContent = 'Actualizar'

        axios.get('/api/' + button.dataset.path)
          .then(response => {
            $form.querySelector('input[name="code"]').value = response.data.code
            $form.querySelector('textarea[name="description"]').value = response.data.description
          })
          .catch(error => {
            console.error('Error:', error)
          })
      } else if (type === 'create') {
        modalType.parentElement.classList.remove('hidden')
        $submit.textContent = 'Crear'
        $form.querySelector('input[name="code"]').value = ''
        $form.querySelector('textarea[name="description"]').value = ''
        $form.action = formURL + modalType.value

        modalType.addEventListener('input', ev => $form.action = formURL + ev.target.value)
      }

      $modal.showModal()
    })
  })

})
