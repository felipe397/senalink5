document.addEventListener("DOMContentLoaded", function () {
    // Function to toggle the menu
    function toggleMenu() {
        const menu = document.querySelector('.menu');
        const content = document.querySelector('.content');
        menu.classList.toggle('menu-open');
        content.classList.toggle('content-shift');
    }

    // Add event listener to the menu toggle button
    const menuToggle = document.getElementById('menu-toggle');
    if (menuToggle) {
        menuToggle.addEventListener('click', toggleMenu);

        document.addEventListener('click', function(event) {
            const menu = document.getElementById('menu');
            if (menu && !menu.contains(event.target) && !menuToggle.contains(event.target)) {
                menu.classList.remove('menu-open'); // Close the menu
            }
        });
    } else {
        console.warn("Elemento 'menu-toggle' no encontrado. No se agregó el event listener.");
    }
});



