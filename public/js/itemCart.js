function debonce(func, delay) {
  let timeout;
  return function (...args) {
    clearTimeout(timeout);
    timeout = setTimeout(() => func.apply(this, args), delay);
  }
}

document.addEventListener('DOMContentLoaded', function () {
  const $forms = document.querySelectorAll('form[data-submit="empty"]')

  $forms.forEach($form => {
    $form.querySelector('input[name="quantity"]').addEventListener('input', debonce((e) => {
      $form.submit()
    }, 250))
  })

})
