export default function toggleInputs(selector = '') {
  if (selector) {
    const inputsChecks = document.querySelectorAll(selector)

    if (inputsChecks.length > 0) {
      inputsChecks.forEach($input => {
        $input.addEventListener("input", ev => {
          inputsChecks.forEach($input => {
            if ($input !== ev.target) $input.checked = false
          })
        })
      })
    }
  }
}
