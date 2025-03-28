/**
 * Sticky Navbar
 * Enable sticky behavior of navigation bar on page scroll
 */

const navbar = document.querySelector('.navbar-sticky')


if (navbar) {

  const navbarClass = navbar.classList,
    navbarH = navbar.offsetHeight,
    scrollOffset = 500

  if (navbarClass.contains('position-absolute')) {
    window.addEventListener('scroll', (e) => {
      if (e.currentTarget.pageYOffset > scrollOffset) {
        navbar.classList.add('navbar-stuck')
      } else {
        navbar.classList.remove('navbar-stuck')
      }
    })
  } else {
    window.addEventListener('scroll', (e) => {
      if (e.currentTarget.pageYOffset > scrollOffset) {
        document.body.style.paddingTop = navbarH + 'px'
        navbar.classList.add('navbar-stuck')
      } else {
        document.body.style.paddingTop = ''
        navbar.classList.remove('navbar-stuck')
      }
    })
  }
}


