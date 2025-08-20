function showPage(target) {
    document.querySelectorAll('.page').forEach(p => p.classList.add('hidden'));
    document.getElementById(target).classList.remove('hidden');
    document.querySelector('.menu').classList.add('hidden');
}

document.querySelectorAll('.card').forEach(card => {
    card.addEventListener('click', () => {
        const target = card.dataset.target;
        window.location.hash = target;
        showPage(target);
    });
});

document.querySelectorAll('.close_section_button').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.page').forEach(p => p.classList.add('hidden'));
        document.querySelector('.menu').classList.remove('hidden');
        window.location.hash = '';
    });
});

// On page load
if (window.location.hash) {
    const target = window.location.hash.substring(1); // remove '#'
    showPage(target);
}
