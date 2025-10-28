document.addEventListener('DOMContentLoaded', function () {
  const buttons = document.querySelectorAll('button[data-id][data-type]')
  const $modal = document.getElementById('modal-payment-mix')
  const $form = $modal.querySelector('#form-payment-mix')
  const divs = $modal.querySelectorAll('fieldset > div:has(input)')

  // Iterar sobre los botones y agregar un evento click a cada uno
  buttons.forEach(button => {
    button.addEventListener('click', (ev) => {
      ev.preventDefault()
      const id = button.getAttribute('data-id')
      const $selectStatus = $form.querySelector('fieldset select[name="status"]')
      const $selectMethod = $form.querySelector('fieldset select[name="method"]')

      // Si la URL contiene parámetros de búsqueda, los eliminamos
      let url = window.location.href
      if ((/\?\w+/).test(url)) {
        url = url.replace(window.location.search, '')
      }

      $form.action = url + '/' + id

      axios.get('/api/payments/' + id)
        .then(response => {
          divs.forEach(div => {
            div.querySelector('input').value = response.data[div.querySelector('input').name] || ''
          });

          $selectMethod.value = response.data.payment_id || ''
          $selectStatus.value = response.data.payment_status_id || ''
        })
        .catch(error => {
          console.error('Error:', error)
        })

      $modal.showModal()
    })
  })

})
