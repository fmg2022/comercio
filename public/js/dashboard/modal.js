function openModal(modalId) {
  const $dialog = document.getElementById(modalId)

  if ($dialog) {
    $dialog.showModal()

    $dialog.addEventListener('click', (event) => {
      if (event.target.nodeName === "DIALOG") {
        $dialog.close()
      }
    })
  }
}