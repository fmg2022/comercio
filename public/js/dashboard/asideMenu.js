
document.addEventListener('DOMContentLoaded', (e) => {
  const $asideMenu = document.getElementById('sidebar-menu')
  const $liArr = $asideMenu.querySelectorAll('li')
  const $path = window.location.pathname

  $liArr.forEach(($li) => {
    const text = $li.querySelector('a').dataset.section
    
    // Controlare como funcionara al dirigirse a una página por ejemplo: '/dashboard/products/1'
    $path.endsWith('dashboard') && text === 'dashboard' ? $li.classList.add('active') : $li.classList.remove('active')
    $path.includes(text) ? $li.classList.add('active') : $li.classList.remove('active')
  })
})
