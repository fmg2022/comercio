document.addEventListener('DOMContentLoaded', function () {
	const buttons = document.querySelectorAll('button[data-id][data-type]')
	const $modal = document.getElementById('modal-category-mix')
	const divs = $modal.querySelectorAll('fieldset > div')
	const $form = $modal.querySelector('#form-category-mix')
	const listCategories = document.querySelectorAll('#categories-list > label > input')
	const $h3 = document.querySelector('#categories-list > h3 ')
	const $submit = $form.querySelector('button[type="submit"]')

	// Iterar sobre los botones y agregar un evento click a cada uno
	buttons.forEach(button => {
		button.addEventListener('click', (ev) => {
			ev.preventDefault()
			const type = button.getAttribute('data-type')
			const id = button.getAttribute('data-id')

			$submit.textContent = type === 'create' ? 'Crear' : 'Actualizar'

			// Toggle de la clase editable
			if (type !== 'show') {
				$form.classList.add('editable')

				// Si la URL contiene parámetros de búsqueda, los eliminamos
				let url = window.location.href
				if ((/\?\w+/).test(url)) {
					url = url.replace(window.location.search, '')
				}

				$form.action = type === 'edit' ? url + '/' + id : url
				$form.querySelector('input[name="_method"]').value = type === 'edit' ? 'PUT' : 'POST'
			} else {
				$form.classList.remove('editable')
				$form.action = ''
			}

			let $input = divs[0].querySelector('input')
			let $select = divs[1].querySelector('select')

			$input.disabled = type === 'show'
			$select.disabled = type === 'show'

			if (type !== 'create') {
				axios.get('/api/categories/' + id)
					.then(response => {
						$input.value = response.data[$input.name]

						$select.value = response.data['parent']?.id ?? ''

						const subCategories = response.data.children.map(category => category.id)
						subCategories.length === 0
							? $h3.classList.remove('hidden')
							: $h3.classList.add('hidden')

						listCategories.forEach(input => {
							subCategories.includes(+input.value)
								? input.setAttribute('checked', '')
								: input.removeAttribute('checked')

							type === 'show'
								? input.setAttribute('disabled', '')
								: input.removeAttribute('disabled')
						})
					})
					.catch(error => {
						console.error('Error:', error)
					})
			} else {
				$input.value = ''
				$select.value = ''

				listCategories.forEach(input => {
					input.removeAttribute('disabled')
					input.removeAttribute('checked')
				})
			}

			$modal.showModal()
		})
	})

})
