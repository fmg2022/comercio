document.addEventListener('DOMContentLoaded', () => {
  const $divs = document.querySelectorAll('div.form-item')

  $divs.forEach($div => {
    const $label = $div.querySelector('label')
    const $input = $div.querySelector('input')

    // Si el input tiene un valor, se muestra el label
    if ($input.value) {
      $label.dataset.active = ''
    } else {
      delete $label.dataset.active
    }

    $div.querySelector('input').addEventListener('input', ev => {
      if (ev.target.value) {
        $label.dataset.active = ''
      } else {
        delete $label.dataset.active
      }
    })
  })
})