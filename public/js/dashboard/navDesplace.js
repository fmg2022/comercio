document.addEventListener("DOMContentLoaded", () => {
	const navButtons = document.querySelectorAll("[data-navbar] button")
	const navContent = document.querySelector("[data-navcontent]")

	navButtons.forEach(button => {
		button.addEventListener("click", () => {
			if (!button.classList.contains("active")) {
				navButtons.forEach(btn => btn.classList.remove("active"))
				button.classList.add("active")

				navContent.style.left = `${-100 * button.dataset.pos}%`
			}
		})
	})
})