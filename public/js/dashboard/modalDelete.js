document.addEventListener('DOMContentLoaded', () => {
  const modalButtons = document.querySelectorAll('.button-delete-restore')
  const modal = document.getElementById(modalButtons[0].dataset.modalid)
  const $form = modal.querySelector('#form-modalSimple')
  const submitButton = $form.querySelector('button[type="submit"]')

  let url = window.location.href
  // Si la URL contiene parámetros de búsqueda, los eliminamos
  if ((/\?\w+/).test(url)) {
    url = url.replace(window.location.search, '')
  }

  modalButtons.forEach(button => {
    button.addEventListener('click', () => {
      const isDelete = button.dataset.delete === "true"

      modal.querySelector('#form-text').textContent = button.dataset.text
      modal.querySelector('#form-type').textContent = isDelete ? 'eliminar' : 'restaurar'
      submitButton.textContent = isDelete ? 'Eliminar' : 'Restaurar'

      $form.action = `${url}/${button.dataset.path}`
      $form.querySelector('input[name="_method"]').value = isDelete ? 'DELETE' : 'POST'

      if (isDelete) {
        submitButton.textContent = 'Eliminar'
        submitButton.classList.add('bg-red-900', 'hover:bg-red-800')
        submitButton.classList.remove('bg-green-900', 'hover:bg-green-800')
      } else {
        submitButton.textContent = 'Restaurar'
        submitButton.classList.add('bg-green-900', 'hover:bg-green-800')
        submitButton.classList.remove('bg-red-900', 'hover:bg-red-800')
      }

      modal.showModal()
    })
  })
})
