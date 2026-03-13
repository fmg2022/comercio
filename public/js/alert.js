document.addEventListener('DOMContentLoaded', () => {
  const alertContainers = document.querySelectorAll('[role="alert"]')

  if (alertContainers) {
    alertContainers.forEach(alertContainer => {
      const $closeBtn = alertContainer.querySelector('button')
      alertContainer.addEventListener('animationend', () => alertContainer.remove())
      $closeBtn.addEventListener('click', () => alertContainer.remove())
    });
  }
})
