document.addEventListener('DOMContentLoaded', () => {
  const modalButtons = document.querySelectorAll('[data-uid][data-status]')
  const modal = document.getElementById(modalButtons[0].dataset.modal)
  const $form = modal.querySelector('#form-modalSimple')
  const $select = modal.querySelector('select')

  const arrayStatusDenied = ["COMPLETO", "REEMBOLSADO", "CANCELADO", "APROBADO"]

  modalButtons.forEach(button => {
    if (!arrayStatusDenied.includes(button.dataset.status)) {
      button.classList.remove('pointer-events-none', 'text-slate-500')
      button.classList.add(['cursor-pointer'])

      button.addEventListener('click', () => {
        modal.querySelector('h3').textContent = button.dataset.from
        modal.querySelector('p').textContent = button.dataset.amount
        modal.querySelector('label').for = 'state-' + button.dataset.uid
        $select.id = 'state-' + button.dataset.uid

        $select.querySelectorAll('option').forEach(option => option.value === button.dataset.status ? option.setAttribute('selected', '') : option.removeAttribute('selected'))

        let formUrl = $form.action
        $form.action = formUrl.replace(/\/\d+\/states/, `/${button.dataset.uid}/states`)

        modal.showModal()
      })
    } else {
      button.classList.remove(['cursor-pointer'])
      button.classList.add('pointer-events-none', 'text-slate-500')
    }
  })
})