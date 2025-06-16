document.addEventListener("DOMContentLoaded", () => {
    fetch("../../../controllers/UsuarioController.php?action=listarPrograma")
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(programas => {
            const container = document.querySelector(".cardh__container");

            if (!container) {
                console.error("No se encontró el contenedor con clase .cardh__container");
                return;
            }

            container.innerHTML = ""; // Limpiar contenido inicial

            programas.forEach(programa => {
                const card = document.createElement("article");
                card.classList.add("cardh");

                card.innerHTML = `
                    <a href="Programa de formacion.html?id=${encodeURIComponent(programa.id)}">
                        <div class="card-text">
                            <h2 class="card-title">${programa.nombre_programa}</h2>
                            <p class="card-subtitle">${programa.ficha}</p>
                        </div>
                    </a>
                    <a href="ProgramaEdit.html?id=${encodeURIComponent(programa.id)}" class="buttons__crud"></a>
                `;

                container.appendChild(card);
            });
        })
        .catch(error => {
            console.error("Error al cargar los programas de formación:", error);
        });
});

document.addEventListener("DOMContentLoaded", function () {
    const id = new URLSearchParams(window.location.search).get('id');

    if (id) {
        fetch(`../../../controllers/UsuarioController.php?action=DetallePrograma&id=${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                } else {
                    document.getElementById('codigo').textContent = data.codigo || "";
                    document.getElementById('ficha').textContent = data.ficha || "";
                    document.getElementById('nivel_formacion').textContent = data.nivel_formacion || "";
                    document.getElementById('nombre_programa').textContent = data.nombre_programa || "";
                    document.getElementById('descripcion').textContent = data.descripcion || "";
                    document.getElementById('habilidades_requeridas').textContent = data.habilidades_requeridas || "";
                    document.getElementById('fecha_finalizacion').textContent = data.fecha_finalizacion || "";
                    document.getElementById('estado').textContent = data.estado || "";
                }
            })
            .catch(error => {
                console.error('Error al cargar los detalles del programa:', error);
            });
    }
});

