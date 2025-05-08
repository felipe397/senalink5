// Función para mostrar la ventana emergente
function confirmInhabilitar(redirectUrl) {
    // Muestra la ventana emergente personalizada
    document.getElementById('custom-confirm').style.display = 'block';

    // Maneja el clic en el botón "Inhabilitar"
    document.getElementById('confirm-yes').onclick = function() {
        window.location.href = redirectUrl; // Redirige a la página deseada
    };

    // Maneja el clic en el botón "Cancelar"
    document.getElementById('confirm-no').onclick = function() {
        document.getElementById('custom-confirm').style.display = 'none'; // Cierra la ventana
    };
}

// Función para cerrar la modal de confirmación
function closeConfirm() {
    document.getElementById('custom-confirm').style.display = 'none'; // Cierra la ventana
}
