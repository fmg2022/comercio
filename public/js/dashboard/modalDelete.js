document.addEventListener('DOMContentLoaded', () => {
  const modalButtons = document.querySelectorAll('[data-uid][data-title]')
  const modal = document.getElementById(modalButtons[0].dataset.modal)
  const $form = modal.querySelector('#form-modalSimple')
  const submitButton = $form.querySelector('button[type="submit"]')

  let url = window.location.href
  // Si la URL contiene parámetros de búsqueda, los eliminamos
  if ((/\?\w+/).test(url)) {
    url = url.replace(window.location.search, '')
  }

  modalButtons.forEach(button => {
    button.addEventListener('click', () => {
      const isDelete = button.dataset.button === 'Eliminar'

      modal.querySelector('h2').textContent = button.dataset.title
      modal.querySelector('p').textContent = button.dataset.text

      $form.action = `${url}/${button.dataset.uid}` + (isDelete ? '' : '/restore')
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