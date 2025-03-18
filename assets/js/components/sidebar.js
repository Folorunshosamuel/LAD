/**
 * Sidebar link active
 */


const link = document.querySelectorAll('#sidebar-account a')

link.forEach(item => {
    const currentPageUrl = window.location.href

    if (currentPageUrl === item.href) {
        return item.classList.add('active')
    } else {
        return item.classList.remove('active')
    }
})

const links = document.querySelectorAll('#navbarNav li a ')

links.forEach(item => {
    const currentPageUrl = window.location.href
    if (currentPageUrl === item.href) {
        return item.classList.add('active')
    } else {
        return item.classList.remove('active')
    }
})