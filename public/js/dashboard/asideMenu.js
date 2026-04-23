export default function asideMenu() {
  const $asideMenu = document.getElementById('sidebar-menu')

  if (!$asideMenu) return

  const $liArr = $asideMenu.querySelectorAll('li')
  let pathName = window.location.pathname

  if (!pathName || !$liArr.length) return

  const $pattern = /\/dashboard\/\w+\/\w+/i
  if ($pattern.test(pathName)) {
    pathName = pathName.substring(0, pathName.indexOf('/', 11))
  }

  $liArr.forEach(($li) => {
    const $a = $li.querySelector('a')

    if (!$a) return

    const text = $a.dataset.section
    const isActive = (pathName.endsWith('dashboard') && text === '') || (pathName.endsWith(text) && text)
    $li.classList.toggle('active', isActive)
  })
}
