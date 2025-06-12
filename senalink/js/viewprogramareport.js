document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    if (!id) {
        console.error("No se proporcionó el ID del programa en la URL.");
        return;
    }

    fetch(`../../../controllers/UsuarioController.php?action=DetallePrograma&id=${encodeURIComponent(id)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (!data) {
                console.error("No se recibieron datos del programa.");
                return;
            }

            // No mostrar el id
            // document.getElementById('id').textContent = data.id || "";
            document.getElementById('codigo').textContent = data.codigo || "";
            document.getElementById('ficha').textContent = data.ficha || "";
            document.getElementById('nivel_formacion').textContent = data.nivel_formacion || "";
            document.getElementById('nombre_programa').textContent = data.nombre_programa || "";
            document.getElementById('descripcion').textContent = data.descripcion || "";
            document.getElementById('habilidades_requeridas').textContent = data.habilidades_requeridas || "";
            document.getElementById('fecha_finalizacion').textContent = data.fecha_finalizacion || "";
            document.getElementById('estado').textContent = data.estado || "";

            // Actualizar el enlace de descarga con el id del programa
            const descargarLink = document.getElementById('descargar-link');
            if (descargarLink) {
                descargarLink.href = `ProgramaReport.html?id=${encodeURIComponent(data.id)}`;
            }
        })
        .catch(error => {
            console.error("Error al cargar los datos del programa:", error);
        });
});
