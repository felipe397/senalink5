function toggleFields() {
    const rol = document.querySelector('select[name="rol"]').value;

    const correoGroup = document.getElementById('correoGroup');
    const nitGroup = document.getElementById('nitGroup');
    const crearCuenta = document.getElementById('Crear');

    const correoInput = document.querySelector('input[name="correo"]');
    const nitInput = document.querySelector('input[name="nit"]');

    if (rol === 'empresa') {
        nitGroup.style.display = 'block';
        correoGroup.style.display = 'none';
        crearCuenta.style.display = 'inline';

        nitInput.required = true;
        correoInput.required = false;
    } else {
        nitGroup.style.display = 'none';
        correoGroup.style.display = 'block';
        crearCuenta.style.display = 'none';

        nitInput.required = false;
        correoInput.required = true;
    }
}

// Ejecutar al cargar la página
window.addEventListener('DOMContentLoaded', toggleFields);

// Ejecutar cada vez que se cambia el rol
document.querySelector('select[name="rol"]').addEventListener('change', toggleFields);