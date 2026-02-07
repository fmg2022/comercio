document.addEventListener('DOMContentLoaded', function () {
	const textLabels = { 'street': 'Calle', 'city': 'Ciudad', 'province': 'Provincia' }
	// Obtener todos los botones con el atributo data-id y data-show
	const buttons = document.querySelectorAll('button[data-id][data-show]')
	const $modal = document.getElementById('modal-address-mix')
	const $form = $modal.querySelector('#form-address-mix')

	// Iterar sobre los botones y agregar un evento click a cada uno
	buttons.forEach(button => {
		button.addEventListener('click', (ev) => {
			ev.preventDefault()
			const id = button.getAttribute('data-id')
			const show = button.getAttribute('data-show')

			// Toggle de la clase editable
			if (show !== 'true') {
				$form.classList.add('editable')
				let url = window.location.href

				// Si la URL contiene parámetros de búsqueda, los eliminamos
				if ((/\?\w+/).test(url)) {
					url = url.replace(window.location.search, '')
				}

				$form.action = url + '/' + id
			} else {
				$form.classList.remove('editable')
				$form.action = ''
			}

			axios.get('/api/addresses/' + id)
				.then(response => {

					const divs = $modal.querySelectorAll('fieldset > div')

					divs.forEach($div => {
						const $input = $div.querySelector('input')
						$input.value = response.data[$input.name]
						$input.disabled = show === 'true'
					})

					const $section = $modal.querySelector('fieldset > section')
					if (response.data.is_default) {
						$section.querySelector('input[type="checkbox"]').setAttribute('checked', '')
					} else {
						$section.querySelector('input[type="checkbox"]').removeAttribute('checked')
					}

					$modal.showModal()
				})
				.catch(error => {
					console.error('Error:', error)
					alert(error.message)
				})
		})
	})

})
