function debonce(func, delay) {
  let timeout;
  return function (...args) {
    clearTimeout(timeout);
    timeout = setTimeout(() => func.apply(this, args), delay);
  }
}

document.addEventListener('DOMContentLoaded', () => {
  const forms = document.querySelectorAll('form[data-submit="off"]')

  if (!forms) return

  forms.forEach($form => {
    $form.querySelectorAll('input:not([type="hidden"])').forEach($input => {
      $input.addEventListener('input', debonce((e) => {
        $form.submit()
      }, 250))
    })
  })
})