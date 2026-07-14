document.querySelectorAll('.menu-dropdown').forEach((menu) => {
    menu.addEventListener('click', (e) => {
        e.preventDefault();
        const parent = menu.parentElement;
        
        const icon = menu.querySelector('.arrow-icon');

        if (parent.classList.contains('menu-open')) {
            icon.classList.remove('bi-chevron-right');
            icon.classList.add('bi-chevron-down');
        } else {
            icon.classList.remove('bi-chevron-down');
            icon.classList.add('bi-chevron-right');
        }
    });
});