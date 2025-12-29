function debonce(func, delay) {
  let timeout;
  return function (...args) {
    clearTimeout(timeout);
    timeout = setTimeout(() => func.apply(this, args), delay);
  }
}

document.addEventListener('DOMContentLoaded', function () {
  const $inputs = document.querySelectorAll('label[data-form] input[name="quantity"]')
  const $deleteBtns = document.querySelectorAll('button[data-delete]')

  $inputs.forEach($input => {
    $input.addEventListener('input', debonce((e) => {
      e.preventDefault()
      axios({
        method: 'POST',
        url: '/cart/update',
        data: {
          _token: document.querySelector('input[name="_token"]').value,
          _method: 'PUT',
          id: $input.closest('li').dataset.id,
          quantity: $input.value
        }
      })
        .then(response => {
          window.location.reload()
        })
    }, 300))
  })

  $deleteBtns.forEach($button => {
    $button.addEventListener('click', (e) => {
      e.preventDefault()
      axios({
        method: 'POST',
        url: '/cart/' + $button.closest('li').dataset.id,
        data: {
          _token: document.querySelector('input[name="_token"]').value,
          _method: 'DELETE'
        }
      })
        .then(() => {
          window.location.reload()
        })
    })
  })

})
