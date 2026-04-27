document.addEventListener('DOMContentLoaded', function () {
  const buttons = document.querySelectorAll('.button-create-edit-show')
  const $modal = document.getElementById(buttons[0].dataset.modalid)
  const $form = $modal.querySelector('form.group')
  const $submit = $form.querySelector('button[type="submit"]')

  function getBaseURL() {
    let newUrl = window.location.origin
    const currentPath = window.location.pathname
    const pathParts = currentPath.split('/')

    if (pathParts.length > 3 && pathParts[1] === 'dashboard') {
      newUrl += pathParts.slice(0, 3).join('/')
    } else {
      newUrl += currentPath
    }
    return newUrl
  }

  function setFormValues(data) {
    Object.entries(data).forEach(([key, value]) => {
      const isArray = Array.isArray(value)
      const selector = Array.isArray(value) ? `[name="${key}[]"]` : `[name="${key}"]`;
      const fields = $form.querySelectorAll(selector);

      if (!fields.length) return

      fields.forEach(field => {
        const type = field.type

        if (type === 'radio') {
          field.checked = field.value === value
        } else if (field.type === 'checkbox') {

          if (isArray) {
            field.checked = value.includes(+field.value)
          } else {
            field.checked = Boolean(value)
          }
        } else {
          field.value = value?.toString() ?? ''
        }
      })
    })
  }

  buttons.forEach(button => {
    button.addEventListener('click', (ev) => {
      ev.preventDefault()
      const type = button.dataset.type

      $form.classList.toggle('editable', type !== 'show')

      if (type === 'show') {
        $form.action = ''
      } else {
        $form.action = `${getBaseURL()}/${button.dataset.path ?? ''}`
        $form.querySelector('input[name="_method"]').value = type === 'edit' ? 'PUT' : 'POST'
        $submit.textContent = type === 'edit' ? 'Actualizar' : 'Crear'
      }

      if (type !== 'create') {
        let pathName = getBaseURL().replace('dashboard', 'api') + `/${button.dataset.path}`

        axios.get(pathName)
          .then(response => setFormValues(response.data))
          .catch(error => {
            console.error('Error:', error)
          })
      } else {
        $form.reset()
      }

      $modal.showModal()
    })
  })

})
