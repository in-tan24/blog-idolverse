document.querySelectorAll('[data-carousel]').forEach((carousel) => {
    const slides = carousel.querySelector('.slides');
    const items = carousel.querySelectorAll('.slide');
    if (!slides || items.length === 0) return;

    let index = 0;

    const render = () => {
        slides.style.transform = `translateX(-${index * 100}%)`;
    };

    const next = () => {
        index = (index + 1) % items.length;
        render();
    };

    const prev = () => {
        index = (index - 1 + items.length) % items.length;
        render();
    };

    carousel.querySelector('.next')?.addEventListener('click', next);
    carousel.querySelector('.prev')?.addEventListener('click', prev);

    setInterval(next, 5000);
});

const navDropdowns = document.querySelectorAll('.nav-dropdown');

const closeAllDropdowns = () => {
    navDropdowns.forEach((drop) => {
        drop.classList.remove('open');
        const button = drop.querySelector('.nav-dropbtn');
        const menu = drop.querySelector('.nav-menu');
        if (button) button.setAttribute('aria-expanded', 'false');
        if (menu) menu.hidden = true;
    });
};

navDropdowns.forEach((drop) => {
    const button = drop.querySelector('.nav-dropbtn');
    const menu = drop.querySelector('.nav-menu');
    if (!button || !menu) return;

    button.addEventListener('click', (event) => {
        event.stopPropagation();
        const isOpen = drop.classList.contains('open');
        closeAllDropdowns();

        if (!isOpen) {
            drop.classList.add('open');
            menu.hidden = false;
            button.setAttribute('aria-expanded', 'true');
        }
    });
});

document.addEventListener('click', (event) => {
    const insideDropdown = event.target.closest('.nav-dropdown');
    if (!insideDropdown) {
        closeAllDropdowns();
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        closeAllDropdowns();
    }
});
