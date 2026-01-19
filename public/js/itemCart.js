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
          updateTotalCart(response.data.new_subtotal)
        })
    }, 250))
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

  // Actualizar el total del carrito
  function updateTotalCart($newTotal) {

    const $subtotal = document.querySelector('#cart-subtotal')
    const $shipping = document.querySelector('#cart-shipping')
    const $tax = document.querySelector('#cart-tax')
    const $total = document.querySelector('#cart-total')

    const $taxTotal = $tax.dataset.value * $newTotal

    $subtotal.innerHTML = formatCurrency($newTotal)
    $tax.innerHTML = formatCurrency($taxTotal)
    $total.innerHTML = formatCurrency($newTotal + $taxTotal + $shipping.dataset.value * 1)

    function formatCurrency(value) {
      return value.toLocaleString('es-AR', {
        style: 'currency',
        currency: 'ARS',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      })
    }
  }

})
