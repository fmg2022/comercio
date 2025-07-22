document.addEventListener('DOMContentLoaded', () => {
  const modalButtons = document.querySelectorAll('[data-uid][data-title]')
  const $form = modal.querySelector('#form-modalSimple')

  modalButtons.forEach(button => {
    button.addEventListener('click', () => {
      const modal = document.getElementById(button.dataset.modal)

      $form.action = window.location.href + '/' + button.dataset.uid
      modal.querySelector('h2').textContent = button.dataset.title
      modal.querySelector('p').textContent = button.dataset.text

      modal.showModal()
    })
  })
})