const body = document.querySelector('body')

function showPage(target) {
    document.querySelectorAll('.page').forEach(page => page.classList.add('hidden'));
    document.getElementById(target).classList.remove('hidden')
    document.querySelector('.menu').classList.add('hidden')
    document.querySelector('.logout_form').classList.add('hidden')
}

document.querySelectorAll('.card').forEach(card => {
    card.addEventListener('click', () => {
        const target = card.dataset.target
        window.location.hash = target
        showPage(target)
    })
})

document.querySelectorAll('.close_section_button').forEach(button => {
    button.addEventListener('click', () => {
        document.querySelectorAll('.page').forEach(page => page.classList.add('hidden'))
        document.querySelector('.menu').classList.remove('hidden')
        document.querySelector('.logout_form').classList.remove('hidden')
        window.location.hash = ''
    })
})

if (window.location.hash) {
    const target = window.location.hash.substring(1)
    showPage(target)
}
window.addEventListener('hashchange', () => {
    const target = window.location.hash.substring(1)
    if (target) {
        showPage(target)
    } else {
        document.querySelectorAll('.page').forEach(page => page.classList.add('hidden'))
        document.querySelector('.menu').classList.remove('hidden')
    }
})