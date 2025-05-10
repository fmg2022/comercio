function openModal(modalId, buttonId) {
  const $dialog = document.getElementById(modalId)
  const $button = document.getElementById(buttonId)

  if ($dialog && $button) {
    $button.addEventListener('click', () => {
      $dialog.showModal()
    })
  }
}