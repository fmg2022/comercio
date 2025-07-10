import toggleInputs from "./dashboard/toggleInputs.js"

import asideMenu from "./dashboard/asideMenu.js"

document.addEventListener('DOMContentLoaded', () => {
  toggleInputs('[name="toggle-btns"]')
  asideMenu()
})