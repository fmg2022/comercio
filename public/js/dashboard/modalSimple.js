document.addEventListener('DOMContentLoaded', () => {
  const modalButtons = document.querySelectorAll('[data-uid][data-title]')

  modalButtons.forEach(button => {
    button.addEventListener('click', () => {
      const modal = document.getElementById(button.dataset.modal)
      const $form = modal.querySelector('#form-modalSimple')

      $form.action = window.location.href + '/' + button.dataset.uid
      modal.querySelector('h2').textContent = button.dataset.title
      modal.querySelector('p').textContent = button.dataset.text

      $form.querySelector('button[type="submit"]').textContent = button.dataset.button
      $form.querySelector('input[name="_method"]').value = button.dataset.button === 'Eliminar' ? 'DELETE' : 'POST'

      modal.showModal()
    })
  })
})