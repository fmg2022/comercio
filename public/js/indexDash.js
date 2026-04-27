import toggleInputs from "./dashboard/toggleInputs.js"

// import asideMenu from "./dashboard/asideMenu.js"

document.addEventListener('DOMContentLoaded', () => {
  toggleInputs('[name="toggle-btns"]')
  // asideMenu()

  const targets = document.querySelectorAll('[data-target]')

  if (targets.length <= 0) return;

  targets.forEach(target => {
    const objetive = document.querySelector(`[data-objetive="${target.dataset.target}"]`)

    if (objetive) target.style.maxHeight = `${objetive.offsetHeight}px`;
  })
})