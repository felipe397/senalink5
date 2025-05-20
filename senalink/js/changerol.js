function toggleFields() {
    const rol = document.querySelector('select[name="rol"]').value;
    const correoGroup = document.getElementById('correoGroup');
    const nitGroup = document.getElementById('nitGroup');
    const CrearUsu = document.getElementById('Crear');

    if (rol === 'empresa') {
    nitGroup.style.display = 'block';
    correoGroup.style.display = 'none';
    CrearUsu.style.display = 'block';
    } else {
    nitGroup.style.display = 'none';
    correoGroup.style.display = 'block';
    CrearUsu.style.display = 'none';
    }
}
window.onload = toggleFields;
