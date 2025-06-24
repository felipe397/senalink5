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

function confirmInhabilitar(callback) {
    // Muestra la ventana emergente personalizada
    const modal = document.getElementById('custom-confirm');
    modal.classList.add('show');

    // Maneja el clic en el botón "Sí"
    document.getElementById('confirm-yes').onclick = function() {
        modal.classList.remove('show'); // Cierra la ventana
        if (typeof callback === 'function') {
            callback();
        }
    };

    // Maneja el clic en el botón "No"
    document.getElementById('confirm-no').onclick = function() {
        modal.classList.remove('show'); // Cierra la ventana
    };
}
