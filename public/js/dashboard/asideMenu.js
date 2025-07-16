export default function asideMenu() {
  const $asideMenu = document.getElementById('sidebar-menu')
  const $liArr = $asideMenu.querySelectorAll('li')
  let $path = window.location.pathname

  if (!$path || !$liArr.length) return

  if ($path.search(/\/[a-z]+\/\w+\/\w+/i) !== -1) {
    $path = $path.substring(0, $path.indexOf('/', 11))
  }

  $liArr.forEach(($li) => {
    const text = $li.querySelector('a').dataset.section

    if (($path.endsWith('dashboard') && text === 'dashboard') || $path.includes(text)) {
      $li.classList.add('active')
    } else {
      $li.classList.remove('active')
    }
  })
}
