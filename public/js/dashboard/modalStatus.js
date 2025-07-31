document.addEventListener('DOMContentLoaded', () => {
  const modalButtons = document.querySelectorAll('[data-uid][data-status]')
  const modal = document.getElementById(modalButtons[0].dataset.modal)
  const $form = modal.querySelector('#form-modalSimple')
  const $select = modal.querySelector('select')

  const arrayStatusDenied = ["3", "6"]
  let url = window.location.href
  // Si la URL contiene parámetros de búsqueda, los eliminamos
  if ((/\?\w+/).test(url)) {
    url = url.replace(window.location.search, '')
  }

  modalButtons.forEach(button => {
    if (arrayStatusDenied.includes(button.dataset.status)) {
      button.classList.remove(['cursor-pointer'])
      button.classList.add('pointer-events-none', 'text-slate-500')
    } else {
      button.classList.remove('pointer-events-none', 'text-slate-500')
      button.classList.add(['cursor-pointer'])
    }

    button.addEventListener('click', () => {
      modal.querySelector('h3').textContent = button.dataset.from
      modal.querySelector('p').childNodes[1].textContent = button.dataset.amount
      modal.querySelector('label').for = 'status-' + button.dataset.uid
      $select.id = 'status-' + button.dataset.uid

      $select.querySelectorAll('option').forEach(option => option.value === button.dataset.status ? option.setAttribute('selected', '') : option.removeAttribute('selected'))

      $form.action = `${url}/${button.dataset.uid}/status`

      modal.showModal()
    })
  })
})