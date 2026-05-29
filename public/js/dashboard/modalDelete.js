document.addEventListener('DOMContentLoaded', () => {
  const modalButtons = document.querySelectorAll('.button-delete-restore')
  const modal = document.getElementById(modalButtons[0].dataset.modalid)
  const $form = modal.querySelector('#form-modalSimple')
  const submitButton = $form.querySelector('button[type="submit"]')

  function getBaseURL() {
    let newUrl = window.location.origin
    const currentPath = window.location.pathname.replace(/\/my(\/|$)/, '$1')
    const pathParts = currentPath.split('/')

    if (pathParts.length > 3 && pathParts[1] === 'dashboard') {
      newUrl += pathParts.slice(0, 3).join('/')
    } else {
      newUrl += currentPath
    }
    return newUrl
  }

  modalButtons.forEach(button => {
    button.addEventListener('click', () => {
      const isDelete = button.dataset.delete === "true"

      modal.querySelector('#form-text').textContent = button.dataset.text
      modal.querySelector('#form-type').textContent = isDelete ? 'eliminar' : 'restaurar'
      submitButton.textContent = isDelete ? 'Eliminar' : 'Restaurar'

      $form.action = `${getBaseURL()}/${button.dataset.path}`
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
